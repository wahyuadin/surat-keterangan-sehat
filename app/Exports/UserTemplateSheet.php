<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Events\AfterSheet;

class UserTemplateSheet implements FromArray, WithHeadings, WithEvents, WithTitle
{
  public function array(): array
  {
    return [];
  }

  public function title(): string
  {
    return 'UserTemplate';
  }

  public function headings(): array
  {
    return [
      'Nama Klinik',
      'Username',
      'Nama Lengkap',
      'Alamat Email',
      'Password',
    ];
  }

  public function registerEvents(): array
  {
    return [
      AfterSheet::class => function (AfterSheet $event) {
        $sheet = $event->sheet->getDelegate();

        // Freeze baris pertama
        $sheet->freezePane('A2');

        // Tambahkan filter di baris pertama
        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());

        // Warna biru muda untuk header
        $headerRange = 'A1:E1';
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle($headerRange)->getFill()->getStartColor()->setRGB('BFEFFF');

        // Set lebar kolom A sampai E menjadi 50
        foreach (range('A', 'E') as $col) {
          $sheet->getColumnDimension($col)->setWidth(50);
        }

        // Dropdown clinic_id dari sheet Master_Provider kolom B
        for ($row = 2; $row <= 101; $row++) {
          $validation = $sheet->getCell("A{$row}")->getDataValidation();
          $validation->setType(DataValidation::TYPE_LIST);
          $validation->setErrorStyle(DataValidation::STYLE_STOP);
          $validation->setAllowBlank(true);
          $validation->setShowDropDown(true);
          $validation->setShowErrorMessage(true);
          $validation->setErrorTitle('Invalid Input');
          $validation->setError('Silakan pilih clinic_id yang valid.');
          $validation->setFormula1("Master_Provider!B2:B100");
        }
      },
    ];
  }
}
