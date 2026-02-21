<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class UserExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        return [
            new UserTemplateSheet(),
            new providerReferenceSheet(),
        ];
    }
}
