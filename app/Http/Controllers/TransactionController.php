<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\StockMutation;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index()
    {
        // Ambil daftar obat beserta batch yang masih ada stoknya dan diurutkan berdasarkan expired terdekat (FEFO)
        $medicines = Medicine::with(['batches' => function($query) {
            $query->where('stock', '>', 0)->orderBy('expired_date', 'asc');
        }])->get();

        return Inertia::render('Transactions/Index', [
            'medicines' => $medicines
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
            'cash_amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $transactionItemsData = [];

            foreach ($request->items as $item) {
                $medicine = Medicine::findOrFail($item['medicine_id']);
                $qtyNeeded = $item['quantity'];

                // Terapkan Logika FEFO: Ambil batch dengan expired paling dekat yang stoknya > 0
                $batches = MedicineBatch::where('medicine_id', $medicine->id)
                    ->where('stock', '>', 0)
                    ->orderBy('expired_date', 'asc')
                    ->get();

                $totalAvailableStock = $batches->sum('stock');
                if ($totalAvailableStock < $qtyNeeded) {
                    throw new \Exception("Stok tidak mencukupi untuk obat: {$medicine->name}");
                }

                foreach ($batches as $batch) {
                    if ($qtyNeeded <= 0) break;

                    $takeQty = min($batch->stock, $qtyNeeded);
                    
                    // Kurangi stok batch
                    $batch->stock -= $takeQty;
                    $batch->save();

                    $subtotal = $takeQty * $medicine->price;
                    $totalAmount += $subtotal;

                    // Simpan data item transaksi
                    $transactionItemsData[] = [
                        'medicine_id' => $medicine->id,
                        'medicine_batch_id' => $batch->id,
                        'quantity' => $takeQty,
                        'price' => $medicine->price,
                        'subtotal' => $subtotal,
                    ];

                    // Catat mutasi barang keluar kasir
                    StockMutation::create([
                        'medicine_id' => $medicine->id,
                        'medicine_batch_id' => $batch->id,
                        'type' => 'out',
                        'quantity' => $takeQty,
                        'description' => 'Penjualan Kasir POS',
                    ]);

                    $qtyNeeded -= $takeQty;
                }
            }

            // Validasi uang tunai
            if ($request->cash_amount < $totalAmount) {
                throw new \Exception("Uang tunai kurang dari total belanja!");
            }

            $changeAmount = $request->cash_amount - $totalAmount;

            // Simpan Transaksi Utama
            $transaction = Transaction::create([
                'invoice_number' => 'INV-' . date('Ymd') . '-' . rand(1000, 9999),
                'total_amount' => $totalAmount,
                'cash_amount' => $request->cash_amount,
                'change_amount' => $changeAmount,
                'user_id' => auth()->id(),
            ]);

            // Simpan detail item transaksi
            foreach ($transactionItemsData as &$tItem) {
                $tItem['transaction_id'] = $transaction->id;
                $tItem['created_at'] = now();
                $tItem['updated_at'] = now();
            }
            TransactionItem::insert($transactionItemsData);

            DB::commit();
            return redirect()->back()->with('success', 'Transaksi berhasil! Kembalian: Rp ' . number_format($changeAmount, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}