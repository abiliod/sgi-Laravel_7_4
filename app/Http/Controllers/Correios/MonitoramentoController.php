<?php

namespace App\Http\Controllers\Correios;

use App\Http\Controllers\Controller;
use App\Jobs\AvaliaInspecao;
use App\Jobs\GeraInspecao;
use App\Models\Correios\Inspecao;
use App\Models\Correios\Itensdeinspecao;
use App\Models\Correios\SequenceInspecao;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MonitoramentoController extends Controller
{

    public function avaliacao(Request $request)
    {
        $dtnow = new Carbon();
        $dtmenos30dias = new Carbon();
        $dtmenos60dias = new Carbon();
        $dtmenos90dias = new Carbon();
        $dtmenos3meses = new Carbon();
        $dtmenos110dias = new Carbon();
        $dtmenos120dias = new Carbon();
        $dtmenos150dias = new Carbon();
        $dtmenos4meses = new Carbon();
        $dtmenos6meses = new Carbon();
        $dtmenos12meses = new Carbon();
        $dtmenos30dias->subDays(30);
        $dtmenos60dias->subDays(60);
        $dtmenos90dias->subDays(90);
        $dtmenos3meses->subMonth(3);
        $dtmenos110dias->subDays(110);
        $dtmenos120dias->subDays(120);
        $dtmenos150dias->subDays(150);
        $dtmenos4meses->subMonth(4);
        $dtmenos6meses->subMonth(6);
        $dtmenos12meses->subMonth(12);
        $pontuado=0.00;

        $periodo = new CarbonPeriod();
        $total=0.00;
        $ocorrencias=0;
        $row =0;
        $dtmax = '';
        $count =0;
        $avaliacao = 'Conforme';
        $oportunidadeAprimoramento = '';


        $validator = Validator::make($request->all(), [
             'superintendencia' => 'required'
            , 'tipodeunidade' => 'required'
         ]);
        if (!$validator->passes()) {
            \Session::flash('mensagem', ['msg' => 'Parâmetros insuficiente para o agendamento do Job.'
                , 'class' => 'red white-text']);
            return redirect()->back();
        }
        else {

            $superintendencias = $request->all(['superintendencia']);
            $tipodeunidade =  $request->all(['tipodeunidade']);
            $ciclo =  $request->all(['ciclo']);

//            foreach ($superintendencias as $res)
//            {
//                foreach ($res as $superintendencia)
//                {
//                    if ($superintendencia == 1)
//                    {
//                        $registros = DB::table('itensdeinspecoes')
//                            ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
//                            ->join('unidades', 'itensdeinspecoes.unidade_id', '=', 'unidades.id')
//                            ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
//                            ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
//                            ->select('itensdeinspecoes.*','inspecoes.*','unidades.*','testesdeverificacao.*','gruposdeverificacao.*')
//                            ->where([['situacao', '=',  'Em Inspeção' ]])
//                            ->where([['se', '>', 1 ]])   //depois mudar a condição para ser >1
//                            ->where([['inspecoes.ciclo', '=', $ciclo ]])
//                            ->where([['itensdeinspecoes.tipoUnidade_id', '=', $tipodeunidade ]])
//                            //->where([['sto', '=', 16300050 ]]) //ac anapolis
//                            //->limit(100)
//                        ->get();
//
//                        foreach ($registros as $registro)
//                        {
//                            if((($registro->numeroGrupoVerificacao == 230)&&($registro->numeroDoTeste == 4))
//                                || (($registro->numeroGrupoVerificacao == 270)&&($registro->numeroDoTeste == 1)))
//                            {
//                                $mescompetencia = DB::table('debitoempregados')
//                                    ->select('competencia')
//                                    ->where([['debitoempregados.competencia', '>=', 1 ]])
//                                    ->orderBy('competencia' ,'desc')
//                                ->first();
//                                $debitoempregados = DB::table('debitoempregados')
//                                    ->select('data', 'documento', 'historico', 'matricula', 'valor' )
//                                    ->where([['debitoempregados.data', '<=', $dtmenos90dias ]])
//                                    ->where([['debitoempregados.sto', '=', $registro->mcu ]])
//                                    ->orWhere([['debitoempregados.sto', '=', $registro->sto ]])
//                                ->get();
//                                if(! $debitoempregados->isEmpty())
//                                {
//                                    $count = $debitoempregados->count('matricula');
//                                    $total = $debitoempregados->sum('valor'); // soma a coluna valor da coleção de dados
//                                }
//                                else
//                                {
//                                    $count = 0;
//                                    $total = 0.00;
//                                }
//                                $competencia = substr($mescompetencia->competencia, 4, 2).'/'.substr($mescompetencia->competencia, 0, 4);
//                                if($count >= 1){
//                                    $avaliacao = 'Não Conforme';
//                                    $oportunidadeAprimoramento = 'Em Análise aos dados do Sistema WebCont – Composição Analítica da conta 11202.994000, posição de '
//                                    . $competencia .', constatou-se a existência de '. $count . ' débitos de empregado sem regularização há mais de 90 dias, conforme relacionado a seguir:';
//                                    $evidencia = "\n".'Data'."\t".'Documento'."\t".'Histórico'."\t".'Matricula'."\t".'Valor';
//                                    foreach($debitoempregados as $debitoempregado){
//                                        $evidencia =  $evidencia ."\n". date( 'd/m/Y' , strtotime($debitoempregado->data))
//                                            ."\t". $debitoempregado->documento
//                                            ."\t". $debitoempregado->historico
//                                            ."\t". $debitoempregado->matricula
//                                            ."\t". ' R$ '. number_format($debitoempregado->valor, 2, ',', '.');
//                                    }
//                                    $evidencia =  $evidencia ."\n".'Total '."\t".'R$ '. number_format($total, 2, ',', '.');
//                                    $relevancias = DB::table('relevancias')
//                                       ->select( 'relevancias.*'  )
//                                    ->get();
//                                    $tolerancia = ($relevancias->min('valor_final') * 0.1);
//                                    if($total <= $tolerancia ){
//                                        $avaliacao = 'Conforme';
//                                        $oportunidadeAprimoramento = 'Em Análise aos dados do Sistema WebCont – Composição Analítica da conta 11202.994000, verificada a posição do mês '. $competencia .' constatou-se que havia histórico de pendências de débito de Empregados maior que 90 dias. Porém, a mesma está dentro da margem de tolerância definida pelo Departamento de Controle Interno que é de R$ '. number_format($relevancias->min('valor_final'), 2, ',', '.');
//                                        $pontuado=0.00;
//                                    }
//                                    else{
//                                        foreach ($relevancias as $row)
//                                        {
//                                            if(($row->valor_inicio >= $total) || ($row->valor_final <= $total))
//                                            {
//                                                $pontuado = $row->fator_multiplicador * intval($registro->totalPontos) ;
//                                            }
//                                        }
//                                    }
//                                    $dto = DB::table('itensdeinspecoes')
//                                        ->Where([['inspecao_id', '=', $registro->inspecao_id]])
//                                        ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
//                                        ->select( 'itensdeinspecoes.*'  )
//                                    ->first();
//                                    $itensdeinspecao = Itensdeinspecao::find($dto->id);
//                                    $itensdeinspecao->avaliacao  = $avaliacao;
//                                    $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
//                                    $itensdeinspecao->evidencia  = $evidencia;
//                                    $itensdeinspecao->valorFalta = $total;
//                                    $itensdeinspecao->situacao   = 'Corroborado';
//                                    $itensdeinspecao->pontuado   = $pontuado;
//                                    $itensdeinspecao->itemQuantificado = 'Sim';
//                                    $itensdeinspecao->orientacao= $registro->orientacao;
//                                    $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
//                                    $itensdeinspecao->update();
//                                } // fim se o contador de eventos  >1
//                                else
//                                {
//                                    //se não houve registro para a unidade o contador é zero e o resultado é conforme
//                                    $avaliacao = 'Conforme';
//                                    $oportunidadeAprimoramento = 'Em Análise aos dados do Sistema WebCont – Composição Analítica da conta 11202.994000, verificada a posição do mês '. $competencia .' constatou-se que não havia histórico de pendências de débito de Empregados maior que 90 dias.';
//                                    $dto = DB::table('itensdeinspecoes')
//                                        ->Where([['inspecao_id', '=', $registro->inspecao_id]])
//                                        ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
//                                        ->select( 'itensdeinspecoes.*'  )
//                                        ->first();
//                                    $itensdeinspecao = Itensdeinspecao::find($dto->id);
//                                    $itensdeinspecao->avaliacao  = $avaliacao;
//                                    $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
//                                    $itensdeinspecao->evidencia  = null;
//                                    $itensdeinspecao->valorFalta = 0.00;
//                                    $itensdeinspecao->situacao   = 'Corroborado';
//                                    $itensdeinspecao->pontuado   = 0.00;
//                                    $itensdeinspecao->itemQuantificado = 'Não';
//                                    $itensdeinspecao->orientacao= null;
//                                    $itensdeinspecao->eventosSistema = 'Item avaliado remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
//                                    $itensdeinspecao->update();
//                                }
//                            } // fim doteste 230 4
//                        }
//                    }// fim doteste se superintendencia = 1
//                    else
//                    {
//                        // inicio dotestee diversas superintendencias
//                        $registros = DB::table('itensdeinspecoes')
//                            ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
//                            ->join('unidades', 'itensdeinspecoes.unidade_id', '=', 'unidades.id')
//                            ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
//                            ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
//                            ->select('itensdeinspecoes.*','inspecoes.*','unidades.*','testesdeverificacao.*','gruposdeverificacao.*')
//                            ->where([['situacao', '=',  'Em Inspeção' ]])
//                            ->where([['se', '=', $superintendencia ]])
//                            ->where([['inspecoes.ciclo', '=', $ciclo ]])
//                            ->where([['itensdeinspecoes.tipoUnidade_id', '=', $tipodeunidade ]])
//                           // ->limit(5)
//                        ->get();
//                        foreach ($registros as $registro)
//                        {
//                            if((($registro->numeroGrupoVerificacao == 230)&&($registro->numeroDoTeste == 4))
//                                || (($registro->numeroGrupoVerificacao == 270)&&($registro->numeroDoTeste == 1)))
//                            {
//                                $mescompetencia = DB::table('debitoempregados')
//                                    ->select('competencia')
//                                    ->where([['debitoempregados.competencia', '>=', 1 ]])
//                                    ->orderBy('competencia' ,'desc')
//                                ->first();
//                                $debitoempregados = DB::table('debitoempregados')
//                                    ->select('data', 'documento', 'historico', 'matricula', 'valor' )
//                                    ->where([['debitoempregados.data', '<=', $dtmenos90dias ]])
//                                    ->where([['debitoempregados.sto', '=', $registro->mcu ]])
//                                    ->orWhere([['debitoempregados.sto', '=', $registro->sto ]])
//                                ->get();
//                                if(! $debitoempregados->isEmpty())
//                                {
//                                    $count = $debitoempregados->count('matricula');
//                                    $total = $debitoempregados->sum('valor'); // soma a coluna valor da coleção de dados
//                                }
//                                else
//                                {
//                                    $count = 0;
//                                    $total = 0.00;
//                                }
//                                $competencia = substr($mescompetencia->competencia, 4, 2).'/'.substr($mescompetencia->competencia, 0, 4);
//                                if($count >= 1){
//                                    $avaliacao = 'Não Conforme';
//                                    $oportunidadeAprimoramento = 'Em Análise aos dados do Sistema WebCont – Composição Analítica da conta 11202.994000, posição de '
//                                        . $competencia .', constatou-se a existência de '. $count . ' débitos de empregado sem regularização há mais de 90 dias, conforme relacionado a seguir:';
//                                    $evidencia = "\n".'Data'."\t".'Documento'."\t".'Histórico'."\t".'Matricula'."\t".'Valor';
//                                    foreach($debitoempregados as $debitoempregado){
//                                        $evidencia =  $evidencia ."\n". date( 'd/m/Y' , strtotime($debitoempregado->data))
//                                            ."\t". $debitoempregado->documento
//                                            ."\t". $debitoempregado->historico
//                                            ."\t". $debitoempregado->matricula
//                                            ."\t". ' R$ '. number_format($debitoempregado->valor, 2, ',', '.');
//                                    }
//                                    $evidencia =  $evidencia ."\n".'Total '."\t".'R$ '. number_format($total, 2, ',', '.');
//                                    $relevancias = DB::table('relevancias')
//                                        ->select( 'relevancias.*'  )
//                                        ->get();
//                                    $tolerancia = ($relevancias->min('valor_final') * 0.1);
//                                    if($total <= $tolerancia ){
//                                        $avaliacao = 'Conforme';
//                                        $oportunidadeAprimoramento = 'Em Análise aos dados do Sistema WebCont – Composição Analítica da conta 11202.994000, verificada a posição do mês '. $competencia .' constatou-se que havia histórico de pendências de débito de Empregados maior que 90 dias. Porém, a mesma está dentro da margem de tolerância definida pelo Departamento de Controle Interno que é de R$ '. number_format($relevancias->min('valor_final'), 2, ',', '.');
//                                        $pontuado=0.00;
//                                    }
//                                    else{
//                                        foreach ($relevancias as $row)
//                                        {
//                                            if(($row->valor_inicio >= $total) || ($row->valor_final <= $total))
//                                            {
//                                                $pontuado = $row->fator_multiplicador * intval($registro->totalPontos) ;
//                                            }
//                                        }
//                                    }
//                                    $dto = DB::table('itensdeinspecoes')
//                                        ->Where([['inspecao_id', '=', $registro->inspecao_id]])
//                                        ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
//                                        ->select( 'itensdeinspecoes.*'  )
//                                    ->first();
//                                    $itensdeinspecao = Itensdeinspecao::find($dto->id);
//                                    $itensdeinspecao->avaliacao  = $avaliacao;
//                                    $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
//                                    $itensdeinspecao->evidencia  = $evidencia;
//                                    $itensdeinspecao->valorFalta = $total;
//                                    $itensdeinspecao->situacao   = 'Corroborado';
//                                    $itensdeinspecao->pontuado   = $pontuado;
//                                    $itensdeinspecao->itemQuantificado = 'Sim';
//                                    $itensdeinspecao->orientacao= $registro->orientacao;
//                                    $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
//                                    $itensdeinspecao->update();
//
//                                }else{  //se não houve registro para a unidade o contador é zero e o resultado é conforme
//
//                                    $avaliacao = 'Conforme';
//                                    $oportunidadeAprimoramento = 'Em Análise aos dados do Sistema WebCont – Composição Analítica da conta 11202.994000, verificada a posição do mês '. $competencia .' constatou-se que não havia histórico de pendências de débito de Empregados maior que 90 dias.';
//                                    $dto = DB::table('itensdeinspecoes')
//                                        ->Where([['inspecao_id', '=', $registro->inspecao_id]])
//                                        ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
//                                        ->select( 'itensdeinspecoes.*'  )
//                                        ->first();
//                                    $itensdeinspecao = Itensdeinspecao::find($dto->id);
//                                    $itensdeinspecao->avaliacao  = $avaliacao;
//                                    $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
//                                    $itensdeinspecao->evidencia  = null;
//                                    $itensdeinspecao->valorFalta = 0.00;
//                                    $itensdeinspecao->situacao   = 'Corroborado';
//                                    $itensdeinspecao->pontuado   = 0.00;
//                                    $itensdeinspecao->itemQuantificado = 'Não';
//                                    $itensdeinspecao->orientacao= null;
//                                    $itensdeinspecao->eventosSistema = 'Item avaliado remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
//                                    $itensdeinspecao->update();
//                                }
//                            } // fim doteste
//                        }
//                    }
//                }
//            }

            //     dd($superintendencias, $tipodeunidade , $ciclo);

//            para ativar a fila no console
//            php artisan queue:work --queue=avaliaInspecao

            $job = (new AvaliaInspecao($superintendencias, $tipodeunidade , $ciclo))
                ->onQueue('avaliaInspecao')->delay($dtnow->addMinutes(1));
            dispatch($job);

            \Session::flash('mensagem', ['msg' => 'Job aguardando processamento.'
                , 'class' => 'blue white-text']);
            return redirect()->back();

//            O valor de 134217728 bytes é equivalente a 128M
//Isso ocorre porque no arquivo php.ini o parâmetro memory_limit está configurado para 128M. E para consertar o problema é só editar o arquivo e alterar para 256M.

        }
    }

    public function avaliar() {
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

            return view('compliance.monitoramento.avaliar', compact('registros', 'tiposDeUnidade'));
        } else {
            \Session::flash('mensagem', ['msg' => 'Não foi possivel exibir os itens você provavelmente não é administrador.'
                , 'class' => 'red white-text']);
            return redirect()->route('home');
        }
    }

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


    public function criar() {
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

            return view('compliance.monitoramento.criar', compact('registros', 'tiposDeUnidade'));
        } else {
            \Session::flash('mensagem', ['msg' => 'Não foi possivel exibir os itens você provavelmente não é administrador.'
                , 'class' => 'red white-text']);
            return redirect()->route('home');
        }
    }

    public function show(){
        return view('compliance.monitoramento.show');  //
    }
//    public function transformDate($value, $format = 'Y-m-d')
//    {
//        try
//        {
//            return \Carbon\Carbon::instance(
//                \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
//        }
//        catch (\ErrorException $e)
//        {
//            return \Carbon\Carbon::createFromFormat($format, $value);
//        }
//    }

}
