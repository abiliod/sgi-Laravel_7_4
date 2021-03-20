<?php

namespace App\Jobs;

use App\Models\Correios\ModelsAuxiliares\Proter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Carbon;


class JobProter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $proters, $dt_job;
//    public $dateWanted;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dt_job, $proters)
    {
        $this->proters = $proters;
        $this->dt_job = $dt_job;

//        $this-> dateWanted = $dateWanted;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()  {
        $proters = $this->proters;
        $dt_job= $this->dt_job;
        $mcuanterior='';

        ini_set('memory_limit', '512M');

//  Inicio importar PROTERS
        foreach($proters as $registros)
        {
            foreach($registros as $dado)
            {
                $proter = DB::table('proters')
                    ->select('proters.*')
                    ->where('proters.no_do_objeto', '=', $dado['no_do_objeto'])
                    ->get();

                if(! $proter->isEmpty())
                {
                    $count  = $proter->count('no_do_objeto');
                    if ( $count > 1 )
                    {
                        DB::table('proters')
                            ->where('no_do_objeto', '=', $dado['no_do_objeto'])
                            ->delete();
                        $count  = 0;
                    }
                }
                else
                {
                    $count  = 0;
                }

                if($count == 1)
                {
                    $res = DB::table('proters')
                        ->select('id')
                        ->where('proters.no_do_objeto', '=', $dado['no_do_objeto'])
                        ->first();
                    if ($res->id >= 1)
                    {
                        $registro = Proter::find($res->id);
                        $registro->se  = $dado['se'];
                        $registro->tipo_de_pendencia  = $dado['tipo_de_unidade'];
                        $registro->status_da_pendencia  = $dado['status_da_pendencia'];
                        $registro->tipo_de_unidade  = $dado['tipo_de_unidade'];
                        $registro->mcu  = $dado['stomcu'];
                    }
                }
                else
                {
                    if(($dado['tipo_de_pendencia']  == 'CON' )or($dado['tipo_de_pendencia']  == 'DPC' ) && ($dado['status_da_pendencia'] == 'Pendente'))
                    {

                        if(! $dado['data_da_pendencia']=='')
                        {
                            $dateWanted = $dado['data_da_pendencia'];
                            $data_da_pendencia = Carbon::createFromFormat('m/d/Y', $dateWanted)->format('Y-m-d');
//                            $data_da_pendencia = $this->transformDate($dado['data_da_pendencia']);
                        }
                        else
                        {
                            $data_da_pendencia = null;
                        }

                        if(! $dado['data_da_entrega']=='')
                        {
                            $dateWanted = $dado['data_da_entrega'];
                            $data_da_entrega = Carbon::createFromFormat('m/d/Y', $dateWanted)->format('Y-m-d');

//                            $data_da_entrega = $this->transformDate($dado['data_da_entrega']);
                        }
                        else
                        {
                            $data_da_entrega = null;
                        }

                        if(! $dado['data_da_postagem']=='')
                        {
                            $dateWanted = $dado['data_da_postagem'];
                            $data_da_postagem = Carbon::createFromFormat('m/d/Y', $dateWanted)->format('Y-m-d');

//                            $data_da_postagem = $this->transformDate($dado['data_da_postagem']);
                        }
                        else
                        {
                            $data_da_postagem = null;
                        }

                        if(! $dado['data_de_leitura']=='')
                        {
                            $dateWanted = $dado['data_de_leitura'];
                            $data_de_leitura = Carbon::createFromFormat('m/d/Y', $dateWanted)->format('Y-m-d');

//                            $data_de_leitura = $this->transformDate($dado['data_de_leitura']);
                        }
                        else
                        {
                            $data_de_leitura = null;
                        }

                        $registro = new Proter;
                        $registro->data_da_pendencia =  $data_da_pendencia;
                        $registro->data_da_entrega = $data_da_entrega;
                        $registro->data_da_postagem =$data_da_postagem;
                        $registro->data_de_leitura = $data_de_leitura;
                        $registro->tipo_de_pendencia      = $dado['tipo_de_pendencia'];
                        $registro->divergencia_peso  = $dado['divergencia_peso'];
                        $registro->divergencia_cep  = $dado['divergencia_cep'];
                        $registro->origem_pendencia  = $dado['origem_pendencia'];
                        $registro->se  = $dado['se'];
                        $registro->tipo_de_unidade  = $dado['tipo_de_unidade'];;
                        $registro->mcu  = $dado['stomcu'];  //ok
                        $registro->nome_da_unidade  = $dado['nome_da_unidade'];
                        $registro->tipo_de_atendimento  = $dado['tipo_de_atendimento'];
                        $registro->matricula_atendente  = $dado['matricula_atendente'];
                        $registro->no_do_objeto  = $dado['no_do_objeto'];
                        $registro->status_da_pendencia  = $dado['status_da_pendencia'];
                        $registro->status_da_unidade  = $dado['status_da_unidade'];
                        $registro->codigo_do_servico  = $dado['codigo_do_servico'];
                        $registro->cep_contabilizado_sara  = $dado['cep_contabilizado_sara'];
                        $registro->cep_entrega_sro  = $dado['cep_entrega_sro'];
                        $registro->peso_tarifado_financeiro  = $dado['peso_tarifado_financeiro'];
                        $registro->comprimento_financeiro  = $dado['comprimento_financeiro'];
                        $registro->largura_financeiro  = $dado['largura_financeiro'];
                        $registro->altura_financeiro  = $dado['altura_financeiro'];
                        $registro->peso_cubico_financeiro  = $dado['peso_cubico_financeiro'];
                        $registro->peso_real_mectri  = $dado['peso_real_mectri'];
                        $registro->comprimento_mectri  = $dado['comprimento_mectri'];
                        $registro->largura_mectri  = $dado['largura_mectri'];
                        $registro->altura_mectri  = $dado['altura_mectri'];
                        $registro->peso_cubico_mectri  = $dado['peso_cubico_mectri'];
                        $registro->peso_tarifado_mectri  = $dado['peso_tarifado_mectri'];
                        if($dado['valor_tarifado_financeiro'] == '---------')
                        {
                            $registro->valor_tarifado_financeiro =0.00;
                        }
                        else
                        {
                            try
                            {
                                $registro->valor_tarifado_financeiro =str_replace(",", ".", $dado['valor_tarifado_financeiro']);
                            }
                            catch (\Exception $e)
                            {
                                $registro->valor_tarifado_financeiro=0.00;
                            }
                        }

                        if($dado['valor_tarifado_mectri'] == '---------')
                        {
                            $registro->valor_tarifado_mectri =0.00;
                        }
                        else
                        {
                            try
                            {
                                $registro->valor_tarifado_mectri =str_replace(",", ".", $dado['valor_tarifado_mectri']);
                            }
                            catch (\Exception $e)
                            {
                                $registro->valor_tarifado_mectri=0.00;
                            }
                        }

                        if($dado['diferenca_a_recolher'] == '---------')
                        {
                            $registro->diferenca_a_recolher =0.00;
                        }
                        else
                        {
                            try
                            {
                                $registro->diferenca_a_recolher =str_replace(",", ".", $dado['diferenca_a_recolher']);
                            }
                            catch (\Exception $e)
                            {
                                $registro->diferenca_a_recolher=0.00;
                            }
                        }
                        $registro->cnpj_do_cliente  = $dado['cnpj_do_cliente'];
                        $registro->contrato  = $dado['contrato'];
                        $registro->cartao_postagem  = $dado['cartao_postagem'];
                        $registro->nome_do_cliente  = $dado['nome_do_cliente'];
                        $registro->qtd_duplicidades  = $dado['qtd_duplicidades'];

                    }
                }
                $registro ->save();
                $row++;
                if ((! $mcuanterior == $registro->mcu ) && (! $mcuanterior == 0))
                {
                    DB::table('proters')
                        ->where('mcu', '=', $mcuanterior)
                        ->where('updated_at', '<', $dt_job)
                        ->delete();
                }
                $mcuanterior = $registro->mcu;
            }

        }
        DB::table('proters')
            ->where('status_da_pendencia', '<>', 'Pendente')
            ->delete();
//   FIM importar PROTERS

        ini_set('memory_limit', '128M');
    }

}
