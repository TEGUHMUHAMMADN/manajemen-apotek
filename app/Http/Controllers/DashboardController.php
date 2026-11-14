<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMedicineTypes = Medicine::count();
        $totalStockAvailable = MedicineBatch::sum('stock');
        
        // Stok Menipis (Total stok obat < 10)
        $lowStockCount = Medicine::all()->filter(function ($medicine) {
            return $medicine->total_stock < 10;
        })->count();

        // Hampir Kadaluarsa (H-90 dari hari ini)
        $ninetyDaysLater = Carbon::now()->addDays(90);
        $nearExpiryCount = MedicineBatch::where('stock', '>', 0)
            ->whereBetween('expired_date', [Carbon::now(), $ninetyDaysLater])
            ->count();

        // Sudah Kadaluarsa
        $expiredCount = MedicineBatch::where('stock', '>', 0)
            ->where('expired_date', '<', Carbon::now())
            ->count();

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_types' => $totalMedicineTypes,
                'total_stock' => $totalStockAvailable,
                'low_stock' => $lowStockCount,
                'near_expiry' => $nearExpiryCount,
                'expired' => $expiredCount,
            ]
        ]);
    }
}