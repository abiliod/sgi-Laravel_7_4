<?php

namespace App\Jobs;

use App\Models\Correios\ModelsAuxiliares\DebitoEmpregado;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;



class JobWebCont implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected   $debitoEmpregados, $dt_job ;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($debitoEmpregados, $dt_job)
    {
        $this->debitoEmpregados = $debitoEmpregados;
        $this->dt_job = $dt_job;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $debitoEmpregados = $this->debitoEmpregados;
        ini_set('memory_limit', '512M');

        foreach($debitoEmpregados as $dados)
        {
            foreach($dados as $registro)
            {
                $dateWanted = $registro['data_de_leitura'];
                $dateWanted = \Illuminate\Support\Carbon::createFromFormat('m/d/Y', $dateWanted)->format('Y-m-d');
                $debitoEmpregado = new DebitoEmpregado;
                $debitoEmpregado->cia      = $registro['cia'];
                $debitoEmpregado->conta  = $registro['conta'];
                $debitoEmpregado->competencia  = $registro['competencia'];

                $debitoEmpregado->data         = $dateWanted;
                $debitoEmpregado->lote  = $registro['lote'];
                $debitoEmpregado->tp  = $registro['tp'];
                $debitoEmpregado->sto  = $registro['mcu_doc1'];
                $debitoEmpregado->nome_unidade  = $registro['nome_agencia_doc2'];
                $debitoEmpregado->historico  = $registro['historico'];
                $debitoEmpregado->valor  = $registro['valor'];
                $debitoEmpregado->observacoes  = $registro['observacoes'];
                $debitoEmpregado->documento  = $registro['documento_ref1'];
                $debitoEmpregado->matricula  = $registro['matricula_ref2'];
                $debitoEmpregado->nomeEmpregado  = $registro['nome_empregado_ref3'];
                $debitoEmpregado->justificativa  = $registro['justificativa_ad1'];
                $debitoEmpregado->regularizacao  = $registro['regularizacao'];
                $debitoEmpregado->acao  = $registro['acao'];
                $debitoEmpregado->regularizacao  = $registro['regularizacao'];
                $debitoEmpregado->anexo  = $registro['anexo'];
                $debitoEmpregado ->save();
                $row++;
            }
            DB::table('debitoempregados')
                ->where('cia', '=', $registro['cia'])
                ->where('conta', '=', $registro['conta'])
                ->where('competencia', '<', $registro['competencia'])
            ->delete();
        }

        ini_set('memory_limit', '128M');

    }
}
