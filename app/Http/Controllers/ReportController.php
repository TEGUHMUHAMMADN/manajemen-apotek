<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\StockMutation;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Exports\MonthlyReportExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $medicines = $this->getMonthlyReport($month, $year);

        return Inertia::render('Reports/Index', [
            'reportData' => $medicines,
            'filters' => [
                'month' => $month,
                'year' => $year
            ]
        ]);
    }

    // Mengambil data laporan bulanan
    private function getMonthlyReport($month, $year)
    {
        return Medicine::with(['category', 'batches'])
            ->get()
            ->map(function ($medicine) use ($month, $year) {

                // Total stok masuk
                $stockIn = StockMutation::where('medicine_id', $medicine->id)
                    ->where('type', 'in')
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->sum('quantity');

                // Total stok keluar
                $stockOut = StockMutation::where('medicine_id', $medicine->id)
                    ->where('type', 'out')
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->sum('quantity');

                // Total expired dan rusak
                $expiredRusak = StockMutation::where('medicine_id', $medicine->id)
                    ->whereIn('type', ['expired', 'damaged'])
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->sum('quantity');

                // Perhitungan stok
                $stokAkhir = $medicine->total_stock;

                $stokAwal = ($stokAkhir - $stockIn)
                    + $stockOut
                    + $expiredRusak;

                // Total pendapatan
                $totalPendapatan = TransactionItem::where(
                    'medicine_id',
                    $medicine->id
                )
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
    }

    // Export laporan bulanan ke Excel
    public function exportMonthly(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $reportData = $this->getMonthlyReport($month, $year);

        $export = new MonthlyReportExport();

        return $export->download(
            $reportData,
            $month,
            $year
        );
    }
}
