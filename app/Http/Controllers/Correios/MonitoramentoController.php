<?php

namespace App\Http\Controllers\Correios;

use App\Http\Controllers\Controller;
use App\Models\Correios\Inspecao;
use App\Models\Correios\Itensdeinspecao;
use App\Models\Correios\SequenceInspecao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoramentoController extends Controller
{
    public function create(Request $request)
    {
        ini_set('max_input_time', 350);
        ini_set('max_execution_time', 350);
        $dados = $request->all();
        $superintendencias = $request->all(['superintendencia']);
        $ciclo =  substr($dados['data'],0,4);
        $dataAgenda =  $dados['data'];
        $status = 'Criado e instalado';
        $tipoInspecao = DB::table('tiposdeunidade')
           ->where([
               ['tipoInspecao', '=', 'Monitorada'],
           ])
            ->where([
                ['id', '=', $dados['tipodeunidade']],
            ])
        ->get();
// dd($tipoInspecao);
        if(! $tipoInspecao->isEmpty())
        {
            foreach ($superintendencias as $dados)
            {
                foreach ($dados as $superintendencia)
                {
                  //  dd($superintendencia);
                    if ($superintendencia == 1)
                    {

                        dd('Gera inspeção para todas regionais e sai do loop   ->'.$superintendencia);
                    }
                    else
                    {
                        //dd('aki superintendencia da vez ->'.$superintendencia, $tipoInspecao);

                        // veroficar se existe inspeção monitorada para a SE  nos ultimos 6 meses

                        $unidades = DB::table('unidades')
                            ->join('tiposdeunidade', 'unidades.tipoUnidade_id', '=', 'tiposdeunidade.id')
                            ->select(
                                'unidades.*', 'tiposdeunidade.inspecionar', 'tiposdeunidade.tipoInspecao'
                            )
                            ->where([['tiposdeunidade.inspecionar', '=',  'Sim']])
                            ->where([['tiposdeunidade.tipoInspecao', '=',  'Monitorada']])
                            ->where([['unidades.status_unidadeDesc', '=',  $status]])
                            ->Where([['se', '=', $superintendencia]])
                            ->orderBy('seDescricao', 'desc')
                            ->orderBy('tipoOrgaoDesc', 'asc')
                            ->orderBy('descricao', 'asc')
                        ->get();

                    //    dd('aki superintendencia da vez ->'.$superintendencia, $tipoInspecao , $unidades);

                        if(! $unidades->isEmpty())
                        {
                            foreach ($unidades as $unidade)
                            {
                                //gerar numeração
                                $sequence_inspcaos = DB::table('sequence_inspcaos')
                                    ->select('sequence_inspcaos.*')
                                    ->Where([['se', '=', $superintendencia ]])
                                    ->Where([['ciclo', '=', $ciclo ]])
                                    ->first();
                                if( !empty( $sequence_inspcaos ))
                                {
                                    $sequence = $sequence_inspcaos->sequence;
                                    $sequence ++;
                                    $sequenceInspecao = SequenceInspecao::find($sequence_inspcaos->id);
                                    $sequenceInspecao->se      = $superintendencia;
                                    $sequenceInspecao->ciclo =  $ciclo;
                                    $sequenceInspecao->sequence      = $sequence;
                                    $sequenceInspecao->update();
                                }
                                else
                                {
                                    $sequence=1;
                                    $sequenceInspecao = new SequenceInspecao;
                                    $sequenceInspecao->se    = $superintendencia;
                                    $sequenceInspecao->ciclo =  $ciclo;
                                    $sequenceInspecao->sequence      = $sequence;
                                    $sequenceInspecao->save();
                                }
                                $sequence = str_pad(  $sequenceInspecao->sequence , 4, '0', STR_PAD_LEFT);

                                if ($superintendencia < 10)
                                {
                                    $se = '0'.$superintendencia;
                                }
                                else
                                {
                                    $se = $superintendencia;
                                }

                                $codigo = $se.$sequence.$ciclo;

//                              gera inspeção
                                $inspecao = new Inspecao;
                                    $inspecao->ciclo        =   $ciclo;
                                    $inspecao->descricao    =  $unidade->descricao;
                                    $inspecao->datainiPreInspeção      = $dataAgenda;
                                    $inspecao->codigo      = $codigo;
                                    $inspecao->unidade_id  = $unidade->id;
                                    $inspecao->tipoUnidade_id      = $unidade->tipoUnidade_id;
                                    $inspecao->tipoVerificacao     = 'Monitorada';
                                    $inspecao->status      =  'Em Inspeção';
                                    $inspecao->unidade_id  = $unidade->id;
                                $inspecao->save();

                                $parametros = DB::table('tiposdeunidade')
                                    ->join('gruposdeverificacao', 'tiposdeunidade.id',  '=',   'tipoUnidade_id')
                                    ->join('testesdeverificacao', 'grupoVerificacao_id', '=', 'gruposdeverificacao.id')
                                    ->where([
                                        ['gruposdeverificacao.tipoUnidade_id', '=',  $inspecao->tipoUnidade_id  ] //" tipoUnidade_id " => " 1 "
                                    ])
                                    ->where([
                                        ['gruposdeverificacao.tipoVerificacao', '=', $inspecao->tipoVerificacao  ] //" tipoVerificacao " => " Remoto "
                                    ])
                                    ->where([
                                        ['gruposdeverificacao.ciclo', '=', $inspecao->ciclo  ] // REGRA o Caderno é por ciclo
                                    ])
                                    ->get();
                                foreach($parametros as $parametro)
                                {
                                    $registro = new Itensdeinspecao;
                                        $registro->inspecao_id =  $inspecao->id ; //veriricação relacionada
                                        $registro->unidade_id =  $unidade->id; //unidade verificada
                                        $registro->tipoUnidade_id =  $unidade->tipoUnidade_id; //Tipo de unidade
                                        $registro->grupoVerificacao_id =  $parametro->grupoVerificacao_id;//grupo de verificação
                                        $registro->testeVerificacao_id =  $parametro->id;// $registro->id teste de verificação
                                        $registro->oportunidadeAprimoramento = $parametro->roteiroConforme;
                                        $registro->consequencias =   $parametro->consequencias;
                                        $registro->norma  =   $parametro->norma;
                                    $registro->save();
                                }
                            }
                         //   dd('gerado ', $registro);
                        }
                        else
                        {
                            dd('n~~ao há unidades ');
                        }
                    }
                }
            }
        }
        else
        {
            dd( 'Tipo de inspeção não habilitado!');
        }






    }


    public function index()
    {
        $status = 'Criado e instalado';
        $businessUnitUser = DB::table('unidades')
            ->Where([['mcu', '=', auth()->user()->businessUnit]])
            ->select('unidades.*')
            ->first();
        if(!empty( $businessUnitUser ))
        {
            $papel_user = DB::table('papel_user')
                ->Where([['user_id', '=', auth()->user()->id]])
                ->Where([['papel_id', '>=', 1]])
                ->select('papel_id')
                ->first();
            switch ($papel_user->papel_id)
            {
                case 1:
                case 2:
                    {
                        $registros = DB::table('unidades')
                            ->select(
                                 'id', 'se', 'seDescricao'
                            )
                            ->where([['se', '>',  1 ]])
                            ->where([['status_unidadeDesc', '=',  $status]])
                            ->groupBy('se')
                            ->orderBy('seDescricao', 'asc')
                            ->get();
                    }
                    break;
            }

            $tiposDeUnidade = DB::table('tiposdeunidade')
                ->where([
                    ['inspecionar', '=', 'sim'],
                    ['tipoInspecao', '=', 'Monitorada'],
                ])
            ->get();

            return view('compliance.monitoramento.index',compact('registros', 'tiposDeUnidade'));
        }

        else
        {
            \Session::flash('mensagem',['msg'=>'Não foi possivel exibir os itens você provavelmente não é administrador.'
                ,'class'=>'red white-text']);
            return redirect()->route('home');
        }
    }



}
