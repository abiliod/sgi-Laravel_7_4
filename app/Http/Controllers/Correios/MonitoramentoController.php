<?php

namespace App\Http\Controllers\Correios;

use App\Http\Controllers\Controller;
use App\Jobs\GeraInspecao;
use App\Models\Correios\Inspecao;
use App\Models\Correios\Itensdeinspecao;
use App\Models\Correios\SequenceInspecao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MonitoramentoController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
             'data' => 'required'
            , 'superintendencia' => 'required'
            , 'tipodeunidade' => 'required'
            , 'ciclo' => 'required'
        ]);
        if (!$validator->passes()) {
            \Session::flash('mensagem', ['msg' => 'Parâmetros insuficiente para o agendamento do Job.'
                , 'class' => 'red white-text']);
            return redirect()->back();
        }
        else
        {
            $dados = $request->all();
            $superintendencias = $request->all(['superintendencia']);
            $ciclo = $dados['ciclo'];
            $dataAgenda = $this->transformDate($dados['data']);
            $status = 'Criado e instalado';
            $tipoInspecao = DB::table('tiposdeunidade')
                ->where([
                    ['tipoInspecao', '=', 'Monitorada'],
                ])
                ->where([
                    ['id', '=', $dados['tipodeunidade']],
                ])
                ->get();
            if ($tipoInspecao->isEmpty()) {
                \Session::flash('mensagem', ['msg' => 'Tipo de Inspeção não Previsto!'
                    , 'class' => 'red white-text']);
                return redirect()->back();
            }
            else
            {
// php artisan queue:work --queue=geraInspecao
//        ####################  begin  JOB ################

//                foreach ($superintendencias as $dados) {
//                    foreach ($dados as $superintendencia) {
//                        if ($superintendencia == 1)
//                        {
//                            $unidades = DB::table('unidades')
//                                ->join('tiposdeunidade', 'unidades.tipoUnidade_id', '=', 'tiposdeunidade.id')
//                                ->select(
//                                    'unidades.*', 'tiposdeunidade.inspecionar', 'tiposdeunidade.tipoInspecao'
//                                )
//                                ->where([['tiposdeunidade.inspecionar', '=', 'Sim']])
//                                ->where([['tiposdeunidade.tipoInspecao', '=', 'Monitorada']])
//                                ->where([['unidades.status_unidadeDesc', '=', $status]])
//                                ->Where([['se', '>', $superintendencia]])
//                                ->orderBy('seDescricao', 'desc')
//                                ->orderBy('tipoOrgaoDesc', 'asc')
//                                ->orderBy('descricao', 'asc')
//                                ->get();
//                            if (!$unidades->isEmpty()) {
//                                foreach ($unidades as $unidade)
//                                {
////                              geracao
//                                    $res = DB::table('inspecoes')
//                                        ->Where([['unidade_id', '=', $unidade->id]])
//                                        ->Where([['ciclo', '=', $ciclo ]])
//                                        ->Where([['tipoVerificacao', '=', 'Monitorada' ]])
//                                        ->get();
//                                    if ($res->isEmpty()){
//                                        $sup = DB::table('unidades')
//                                            ->select('unidades.se' )
//                                            ->where([['id', '=', $unidade->id]])
//                                            ->first();
//                                        if ( $sup->se>1) {
//                                            $sequence_inspcaos = DB::table('sequence_inspcaos')
//                                                ->select('sequence_inspcaos.*')
//                                                ->Where([['se', '=', $sup->se]])
//                                                ->Where([['ciclo', '=', $ciclo]])
//                                                ->first();
//                                            if (!empty($sequence_inspcaos)) {
//                                                $sequence_id = $sequence_inspcaos->id;
//                                                $sequence = $sequence_inspcaos->sequence;
//                                                $sequence++;
//                                                DB::table('sequence_inspcaos')
//                                                    ->where('id', $sequence_id)
//                                                    ->update(['sequence' => $sequence]);
//                                            } else {
//                                                $sequence = 2001;
//                                                SequenceInspecao::create([
//                                                    'se' => $sup->se
//                                                    , 'ciclo' => $ciclo
//                                                    , 'sequence' => $sequence
//                                                ]);
//                                            }
//                                            $sequence = str_pad($sequence, 4, '0', STR_PAD_LEFT);
//                                            if ($sup->se < 10) {
//                                                $se = '0' . $sup->se;
//                                            } else {
//                                                $se = $sup->se;
//                                            }
//                                            $codigo = $se . $sequence . $ciclo;
//
//                                        //    dd($sequence, $sequence_inspcaos, $sup, $superintendencia, $unidade);
//
//                                            $inspecao = new Inspecao;
//                                            $inspecao->ciclo = $ciclo;
//                                            $inspecao->descricao = $unidade->descricao;
//                                            $inspecao->datainiPreInspeção = $dataAgenda;
//                                            $inspecao->codigo = $codigo;
//                                            $inspecao->unidade_id = $unidade->id;
//                                            $inspecao->tipoUnidade_id = $unidade->tipoUnidade_id;
//                                            $inspecao->tipoVerificacao = 'Monitorada';
//                                            $inspecao->status = 'Em Inspeção';
//                                            $inspecao->unidade_id = $unidade->id;
//                                            $inspecao->save();
//                                            $parametros = DB::table('tiposdeunidade')
//                                                ->join('gruposdeverificacao', 'tiposdeunidade.id', '=', 'tipoUnidade_id')
//                                                ->join('testesdeverificacao', 'grupoVerificacao_id', '=', 'gruposdeverificacao.id')
//                                                ->where([
//                                                    ['gruposdeverificacao.tipoUnidade_id', '=', $inspecao->tipoUnidade_id] //" tipoUnidade_id " => " 1 "
//                                                ])
//                                                ->where([
//                                                    ['gruposdeverificacao.tipoVerificacao', '=', $inspecao->tipoVerificacao] //" tipoVerificacao " => " Remoto "
//                                                ])
//                                                ->where([
//                                                    ['gruposdeverificacao.ciclo', '=', $inspecao->ciclo] // REGRA o Caderno é por ciclo
//                                                ])
//                                                ->get();
//                                            foreach ($parametros as $parametro) //itens de inspeção
//                                            {
//                                                $registro = new Itensdeinspecao;
//                                                $registro->inspecao_id = $inspecao->id; //veriricação relacionada
//                                                $registro->unidade_id = $unidade->id; //unidade verificada
//                                                $registro->tipoUnidade_id = $unidade->tipoUnidade_id; //Tipo de unidade
//                                                $registro->grupoVerificacao_id = $parametro->grupoVerificacao_id;//grupo de verificação
//                                                $registro->testeVerificacao_id = $parametro->id;// $registro->id teste de verificação
//                                                $registro->oportunidadeAprimoramento = $parametro->roteiroConforme;
//                                                $registro->consequencias = $parametro->consequencias;
//                                                $registro->norma = $parametro->norma;
//                                                $registro->save();
//                                            }
//                                        }
//                                    }
//                                }
//                            }
//                        }
//                        else
//                        {
//                            //gerar para todas selecionadas
//                            $unidades = DB::table('unidades')
//                                ->join('tiposdeunidade', 'unidades.tipoUnidade_id', '=', 'tiposdeunidade.id')
//                                ->select(
//                                    'unidades.*', 'tiposdeunidade.inspecionar', 'tiposdeunidade.tipoInspecao'
//                                )
//                                ->where([['tiposdeunidade.inspecionar', '=', 'Sim']])
//                                ->where([['tiposdeunidade.tipoInspecao', '=', 'Monitorada']])
//                                ->where([['unidades.status_unidadeDesc', '=', $status]])
//                                ->Where([['se', '=', $superintendencia]])
//                                ->orderBy('seDescricao', 'desc')
//                                ->orderBy('tipoOrgaoDesc', 'asc')
//                                ->orderBy('descricao', 'asc')
//                                ->get();
//                            if (!$unidades->isEmpty())
//                            {
//                                foreach ($unidades as $unidade)
//                                {
//                                    //checa se já não existe inspeção para o ciclo
//                                    $res = DB::table('inspecoes')
//                                        ->Where([['unidade_id', '=', $unidade->id]])
//                                        ->Where([['ciclo', '=', $ciclo ]])
//                                        ->Where([['tipoVerificacao', '=', 'Monitorada' ]])
//                                        ->get();
//                                    if ($res->isEmpty()){
////                              gera numeração
//                                        $sequence_inspcaos = DB::table('sequence_inspcaos')
//                                            ->select('sequence_inspcaos.*')
//                                            ->Where([['se', '=', $superintendencia]])
//                                            ->Where([['ciclo', '=', $ciclo ]])
//                                        ->first();
//                                        $strsuperintendencia = intval($superintendencia);
//                                        if (!empty($sequence_inspcaos)) {
//                                            $sequence_id = $sequence_inspcaos->id;
//                                            $sequence = $sequence_inspcaos->sequence;
//                                            $sequence ++;
//                                            $affected = DB::table('sequence_inspcaos')
//                                                ->where('id', $sequence_id)
//                                                ->update(['sequence' => $sequence]);
//                                      //      dd($affected , $sequence);
//                                        } else {
//                                            $sequence = 2001;
//                                            $affected =  SequenceInspecao::create([
//                                                'se' => $strsuperintendencia
//                                                ,'ciclo' => $ciclo
//                                                ,'sequence' => $sequence
//                                            ]);
////                                           dd('else ' ,$affected);
//                                        }
//
////                                        dd($superintendencia, $strsuperintendencia, $sequence_inspcaos);
//                                        $sequence = str_pad($sequence, 4, '0', STR_PAD_LEFT);
//                                        if ($strsuperintendencia < 10) {
//                                            $se = '0'.$strsuperintendencia;
//                                        } else {
//                                            $se = $strsuperintendencia;
//                                        }
//                                        $codigo = $se . $sequence . $ciclo;
//                                       // dd($codigo);
//                                        $inspecao = new Inspecao;
//                                        $inspecao->ciclo = $ciclo;
//                                        $inspecao->descricao = $unidade->descricao;
//                                        $inspecao->datainiPreInspeção = $dataAgenda ;
//                                        $inspecao->codigo = $codigo;
//                                        $inspecao->unidade_id = $unidade->id;
//                                        $inspecao->tipoUnidade_id = $unidade->tipoUnidade_id;
//                                        $inspecao->tipoVerificacao = 'Monitorada';
//                                        $inspecao->status =  'Em Inspeção';
//                                        $inspecao->unidade_id = $unidade->id;
//                                        $inspecao->save();
//                                        $parametros = DB::table('tiposdeunidade')
//                                            ->join('gruposdeverificacao', 'tiposdeunidade.id', '=', 'tipoUnidade_id')
//                                            ->join('testesdeverificacao', 'grupoVerificacao_id', '=', 'gruposdeverificacao.id')
//                                            ->where([
//                                                ['gruposdeverificacao.tipoUnidade_id', '=', $inspecao->tipoUnidade_id] //" tipoUnidade_id " => " 1 "
//                                            ])
//                                            ->where([
//                                                ['gruposdeverificacao.tipoVerificacao', '=', $inspecao->tipoVerificacao] //" tipoVerificacao " => " Remoto "
//                                            ])
//                                            ->where([
//                                                ['gruposdeverificacao.ciclo', '=', $inspecao->ciclo] // REGRA o Caderno é por ciclo
//                                            ])
//                                            ->get();
//                                        foreach ($parametros as $parametro) {
//                                            $registro = new Itensdeinspecao;
//                                            $registro->inspecao_id = $inspecao->id; //veriricação relacionada
//                                            $registro->unidade_id = $unidade->id; //unidade verificada
//                                            $registro->tipoUnidade_id = $unidade->tipoUnidade_id; //Tipo de unidade
//                                            $registro->grupoVerificacao_id = $parametro->grupoVerificacao_id;//grupo de verificação
//                                            $registro->testeVerificacao_id = $parametro->id;// $registro->id teste de verificação
//                                            $registro->oportunidadeAprimoramento = $parametro->roteiroConforme;
//                                            $registro->consequencias = $parametro->consequencias;
//                                            $registro->norma = $parametro->norma;
//                                            $registro->save();
//                                        }
//                                    }
//                                //    dd($inspecao ,'ultimo teste ->',$registro);
//                                }
//                            }
//                        }
//                    }
//                }

//        ####################  end  JOB ################

//dd($superintendencias, $status , $ciclo, $dataAgenda);
                
                $job = (new GeraInspecao($superintendencias, $status , $ciclo, $dataAgenda))
                    ->onQueue('geraInspecao')->delay($dataAgenda->addMinutes(1));
                dispatch($job);

                \Session::flash('mensagem', ['msg' => 'Job aguardando processamento.'
                    , 'class' => 'blue white-text']);
                return redirect()->back();
            }
        }
    }


    public function index()
    {
        $status = 'Criado e instalado';
        try {
            $businessUnitUser = DB::table('unidades')
                ->Where([['mcu', '=', auth()->user()->businessUnit]])
                ->select('unidades.*')
                ->first();
        }catch ( \Exception $e){
        return redirect()->route('login');
    }


        if (!empty($businessUnitUser)) {
            $papel_user = DB::table('papel_user')
                ->Where([['user_id', '=', auth()->user()->id]])
                ->Where([['papel_id', '>=', 1]])
                ->select('papel_id')
                ->first();
            switch ($papel_user->papel_id) {
                case 1:
                case 2:
                    {
                        $registros = DB::table('unidades')
                            ->select(
                                'id', 'se', 'seDescricao'
                            )
                            ->where([['se', '>', 1]])
                            ->where([['status_unidadeDesc', '=', $status]])
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

            return view('compliance.monitoramento.index', compact('registros', 'tiposDeUnidade'));
        } else {
            \Session::flash('mensagem', ['msg' => 'Não foi possivel exibir os itens você provavelmente não é administrador.'
                , 'class' => 'red white-text']);
            return redirect()->route('home');
        }
    }

    public function transformDate($value, $format = 'Y-m-d')
    {
        try
        {
            return \Carbon\Carbon::instance(
                \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        }
        catch (\ErrorException $e)
        {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }

}
