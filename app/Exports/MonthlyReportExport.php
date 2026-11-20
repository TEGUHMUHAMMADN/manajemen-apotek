<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MonthlyReportExport
{
    public function download($reportData, $month, $year)
    {
        // Membuat workbook Excel
        $excel = new Spreadsheet();

        $sheet = $excel->getActiveSheet();
        $sheet->setTitle('Laporan Bulanan');

        // Judul laporan
        $sheet->setCellValue('A1', 'LAPORAN MEDISTOCK');
        $sheet->mergeCells('A1:H1');

        $sheet->setCellValue(
            'A2',
            'Periode: ' . $month . '/' . $year
        );

        $sheet->mergeCells('A2:H2');

        // Header tabel
        $headers = [
            'Kode',
            'Nama Obat',
            'Stok Awal',
            'Stok Masuk',
            'Stok Keluar',
            'Expired / Rusak',
            'Stok Akhir',
            'Total Pendapatan'
        ];

        foreach ($headers as $index => $header) {
            $column = Coordinate::stringFromColumnIndex($index + 1);

            $sheet->setCellValue(
                $column . '4',
                $header
            );
        }

        // Mengisi data laporan
        $row = 5;

        foreach ($reportData as $item) {
            $sheet->fromArray([
                $item['code'],
                $item['name'],
                $item['stok_awal'],
                $item['stok_masuk'],
                $item['stok_keluar'],
                $item['expired_rusak'],
                $item['stok_akhir'],
                $item['total_pendapatan'],
            ], null, 'A' . $row);

            $row++;
        }

        // Styling judul
        $sheet->getStyle('A1:H1')
            ->getFont()
            ->setBold(true)
            ->setSize(18)
            ->getColor()
            ->setRGB('059669');

        $sheet->getStyle('A1:H2')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Styling header tabel
        $sheet->getStyle('A4:H4')
            ->getFont()
            ->setBold(true)
            ->getColor()
            ->setRGB('FFFFFF');

        $sheet->getStyle('A4:H4')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('059669');

        $sheet->getStyle('A4:H4')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Format angka rupiah
        if ($row > 5) {
            $sheet->getStyle('H5:H' . ($row - 1))
                ->getNumberFormat()
                ->setFormatCode('"Rp" #,##0');
        }

        // Lebar kolom otomatis
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)
                ->setAutoSize(true);
        }

        // Membuat garis tabel
        $sheet->getStyle('A4:H' . max(4, $row - 1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Freeze header
        $sheet->freezePane('A5');

        // Nama file
        $filename = "Laporan-MediStock-$year-$month.xlsx";

        // Download Excel
        $writer = new Xlsx($excel);

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save('php://output');
            },
            $filename,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }
}
