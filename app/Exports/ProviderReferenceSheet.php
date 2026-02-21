<?php

namespace App\Exports;

use App\Models\Provider;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProviderReferenceSheet implements FromCollection, WithHeadings, WithTitle, WithEvents, ShouldAutoSize
{
  public function title(): string
  {
    return 'Master_Provider';
  }

  public function collection()
  {
    return Provider::select('clinic_id', 'clinic_name')->get();
  }

  public function headings(): array
  {
    return [
      'Kode Klinik',
      'Nama Klinik',
    ];
  }

  public function registerEvents(): array
  {
    return [
      AfterSheet::class => function (AfterSheet $event) {
        $sheet = $event->sheet->getDelegate();
        $sheet->getProtection()->setSheet(true);
        $sheet->getStyle($sheet->calculateWorksheetDimension())->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
        $sheet->getProtection()->setPassword('readonly');
        $sheet->getProtection()->setSort(false);
        $sheet->getProtection()->setInsertRows(false);
        $sheet->getProtection()->setFormatCells(false);
        // Freeze row pertama
        $sheet->freezePane('A2');

        // Auto filter
        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());

        // Warna kuning muda pada header
        $headerRange = 'A1:B1';
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle($headerRange)->getFill()->getStartColor()->setRGB('FFF2CC'); // Yellow light
      },
    ];
  }
}
