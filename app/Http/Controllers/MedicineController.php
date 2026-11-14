<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MedicineController extends Controller
{
    public function index()
    {
        return Inertia::render('Medicines/Index', [
            'medicines' => Medicine::with('category')
                ->withSum('batches as total_stock', 'stock')
                ->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Medicines/Create', [
            'categories' => Category::all()
        ]);
    }

    public function findByCode(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $medicine = Medicine::where('code', $request->code)->first();

        if ($medicine) {
            return response()->json([
                'found' => true,
                'message' => 'Obat terdaftar! Silakan masukkan nomor batch dan jumlah stok masuk.',
                'data' => [
                    'id' => $medicine->id,
                    'code' => $medicine->code,
                    'name' => $medicine->name,
                    'category_id' => $medicine->category_id,
                    'price' => $medicine->price,
                    'minimum_stock' => $medicine->minimum_stock,
                    'rack_location' => $medicine->rack_location,
                ]
            ]);
        }

        return response()->json([
            'found' => false,
            'message' => 'Kode obat baru. Silakan isi form secara manual untuk mendaftarkan obat.'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'rack_location' => 'required|string|max:100',
            'batch_number' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'expired_date' => 'required|date',
        ]);

        // Cek atau buat master data obat berdasarkan kode barcode
        $medicine = Medicine::firstOrCreate(
            ['code' => $request->code],
            [
                'name' => $request->name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'minimum_stock' => $request->minimum_stock,
                'rack_location' => $request->rack_location,
            ]
        );

        // Jika obat sudah ada, update informasi dasar jika diperlukan
        $medicine->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'minimum_stock' => $request->minimum_stock,
            'rack_location' => $request->rack_location,
        ]);

        // Catat mutasi batch masuk (mendukung FEFO multi-batch)
        MedicineBatch::create([
            'medicine_id' => $medicine->id,
            'batch_number' => $request->batch_number,
            'stock' => $request->quantity,
            'expired_date' => $request->expired_date,
        ]);

        return redirect()->route('medicines.index')->with('success', 'Stok obat berhasil ditambahkan!');
    }

    public function show(Medicine $medicine)
    {
        $medicine->load(['category', 'batches' => function($query) {
            $query->orderBy('expired_date', 'asc');
        }]);

        return Inertia::render('Medicines/Show', [
            'medicine' => $medicine
        ]);
    }

    public function edit(Medicine $medicine)
    {
        return Inertia::render('Medicines/Edit', [
            'medicine' => $medicine,
            'categories' => Category::all()
        ]);
    }

    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'code' => 'required|string|unique:medicines,code,' . $medicine->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'rack_location' => 'required|string|max:100',
        ]);

        $medicine->update($request->only([
            'code', 'name', 'category_id', 'price', 'minimum_stock', 'rack_location'
        ]));

        return redirect()->route('medicines.index')->with('success', 'Data obat berhasil diperbarui.');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('medicines.index')->with('success', 'Data obat berhasil dihapus.');
    }
}