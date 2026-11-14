<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Mengambil rekapitulasi mutasi per obat pada bulan dan tahun tertentu
        $medicines = Medicine::with(['category', 'batches'])->get()->map(function ($medicine) use ($month, $year) {
            
            // Total Barang Masuk pada bulan tersebut
            $stockIn = StockMutation::where('medicine_id', $medicine->id)
                ->where('type', 'in')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('quantity');

            // Total Barang Keluar (Kasir) pada bulan tersebut
            $stockOut = StockMutation::where('medicine_id', $medicine->id)
                ->where('type', 'out')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('quantity');

            // Total Barang Expired/Rusak pada bulan tersebut
            $expiredRusak = StockMutation::where('medicine_id', $medicine->id)
                ->whereIn('type', ['expired', 'damaged'])
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('quantity');

            $stokAkhir = $medicine->total_stock;
            $stokAwal = ($stokAkhir - $stockIn) + $stockOut + $expiredRusak;

            // Total Pendapatan dari obat ini di bulan tersebut
            $totalPendapatan = \App\Models\TransactionItem::where('medicine_id', $medicine->id)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('subtotal');

            return [
                'code' => $medicine->code,
                'name' => $medicine->name,
                'stok_awal' => max(0, $stokAwal),
                'stok_masuk' => $stockIn,
                'stok_keluar' => $stockOut,
                'expired_rusak' => $expiredRusak,
                'stok_akhir' => $stokAkhir,
                'total_pendapatan' => $totalPendapatan,
            ];
        });

        return Inertia::render('Reports/Index', [
            'reportData' => $medicines,
            'filters' => ['month' => $month, 'year' => $year]
        ]);
    }
}