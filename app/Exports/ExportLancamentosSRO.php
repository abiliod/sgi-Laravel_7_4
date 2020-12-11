<?php


namespace App\Exports;


use App\Models\Correios\ModelsDto\LancamentosSRO;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;


//use App\Http\Controllers\Correios\InspecaoController;
//use Illuminate\Contracts\View\View;
//use Maatwebsite\Excel\Concerns\FromView;


class ExportLancamentosSRO implements FromCollection
{
use Exportable;
    public function collection()
    {
        // TODO: Implement collection() method.

        return LancamentosSRO::query();

    }


}
