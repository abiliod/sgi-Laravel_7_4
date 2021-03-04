<?php

namespace App\Jobs;

use App\Models\Correios\Itensdeinspecao;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AvaliaInspecao implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */


    protected   $superintendencias, $tipodeunidade , $ciclo;

    public function __construct(  $superintendencias, $tipodeunidade , $ciclo )
    {
        $this->superintendencias = $superintendencias;
        $this->tipodeunidade = $tipodeunidade;
        $this->ciclo = $ciclo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
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

        $superintendencias = $this->superintendencias;
        $tipodeunidade  = $this->tipodeunidade;
        $ciclo  = $this->ciclo;


        foreach ($superintendencias as $res)
        {
            foreach ($res as $superintendencia)
            {
                if ($superintendencia == 1)
                {
                    $registros = DB::table('itensdeinspecoes')
                        ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
                        ->join('unidades', 'itensdeinspecoes.unidade_id', '=', 'unidades.id')
                        ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
                        ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
                        ->select('itensdeinspecoes.*','inspecoes.*','unidades.*','testesdeverificacao.*','gruposdeverificacao.*')
                        ->where([['situacao', '=',  'Em Inspeção' ]])
                        ->where([['se', '>', 1 ]])   //depois mudar a condição para ser >1
                        ->where([['inspecoes.ciclo', '=', $ciclo ]])
                        ->where([['itensdeinspecoes.tipoUnidade_id', '=', $tipodeunidade ]])
                        //->where([['sto', '=', 16300050 ]]) //ac anapolis
                        //->limit(100)
                        ->get();

                    foreach ($registros as $registro)
                    {
                        if((($registro->numeroGrupoVerificacao == 230)&&($registro->numeroDoTeste == 4))
                            || (($registro->numeroGrupoVerificacao == 270)&&($registro->numeroDoTeste == 1)))
                        {
                            $mescompetencia = DB::table('debitoempregados')
                                ->select('competencia')
                                ->where([['debitoempregados.competencia', '>=', 1 ]])
                                ->orderBy('competencia' ,'desc')
                                ->first();
                            $debitoempregados = DB::table('debitoempregados')
                                ->select('data', 'documento', 'historico', 'matricula', 'valor' )
                                ->where([['debitoempregados.data', '<=', $dtmenos90dias ]])
                                ->where([['debitoempregados.sto', '=', $registro->mcu ]])
                                ->orWhere([['debitoempregados.sto', '=', $registro->sto ]])
                                ->get();
                            if(! $debitoempregados->isEmpty())
                            {
                                $count = $debitoempregados->count('matricula');
                                $total = $debitoempregados->sum('valor'); // soma a coluna valor da coleção de dados
                            }
                            else
                            {
                                $count = 0;
                                $total = 0.00;
                            }
                            $competencia = substr($mescompetencia->competencia, 4, 2).'/'.substr($mescompetencia->competencia, 0, 4);
                            if($count >= 1){
                                $avaliacao = 'Não Conforme';
                                $oportunidadeAprimoramento = 'Em Análise aos dados do Sistema WebCont – Composição Analítica da conta 11202.994000, posição de '
                                    . $competencia .', constatou-se a existência de '. $count . ' débitos de empregado sem regularização há mais de 90 dias, conforme relacionado a seguir:';
                                $evidencia = "\n".'Data'."\t".'Documento'."\t".'Histórico'."\t".'Matricula'."\t".'Valor';
                                foreach($debitoempregados as $debitoempregado){
                                    $evidencia =  $evidencia ."\n". date( 'd/m/Y' , strtotime($debitoempregado->data))
                                        ."\t". $debitoempregado->documento
                                        ."\t". $debitoempregado->historico
                                        ."\t". $debitoempregado->matricula
                                        ."\t". ' R$ '. number_format($debitoempregado->valor, 2, ',', '.');
                                }
                                $evidencia =  $evidencia ."\n".'Total '."\t".'R$ '. number_format($total, 2, ',', '.');
                                $relevancias = DB::table('relevancias')
                                    ->select( 'relevancias.*'  )
                                    ->get();
                                $tolerancia = ($relevancias->min('valor_final') * 0.1);
                                if($total <= $tolerancia ){
                                    $avaliacao = 'Conforme';
                                    $oportunidadeAprimoramento = 'Em Análise aos dados do Sistema WebCont – Composição Analítica da conta 11202.994000, verificada a posição do mês '. $competencia .' constatou-se que havia histórico de pendências de débito de Empregados maior que 90 dias. Porém, a mesma está dentro da margem de tolerância definida pelo Departamento de Controle Interno que é de R$ '. number_format($relevancias->min('valor_final'), 2, ',', '.');
                                    $pontuado=0.00;
                                }
                                else{
                                    foreach ($relevancias as $row)
                                    {
                                        if(($row->valor_inicio >= $total) || ($row->valor_final <= $total))
                                        {
                                            $pontuado = $row->fator_multiplicador * intval($registro->totalPontos) ;
                                        }
                                    }
                                }
                                $dto = DB::table('itensdeinspecoes')
                                    ->Where([['inspecao_id', '=', $registro->inspecao_id]])
                                    ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
                                    ->select( 'itensdeinspecoes.*'  )
                                    ->first();
                                $itensdeinspecao = Itensdeinspecao::find($dto->id);
                                $itensdeinspecao->avaliacao  = $avaliacao;
                                $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
                                $itensdeinspecao->evidencia  = $evidencia;
                                $itensdeinspecao->valorFalta = $total;
                                $itensdeinspecao->situacao   = 'Corroborado';
                                $itensdeinspecao->pontuado   = $pontuado;
                                $itensdeinspecao->itemQuantificado = 'Sim';
                                $itensdeinspecao->orientacao= $registro->orientacao;
                                $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                $itensdeinspecao->update();
                            } // fim se o contador de eventos  >1
                            else
                            {
                                //se não houve registro para a unidade o contador é zero e o resultado é conforme
                                $avaliacao = 'Conforme';
                                $oportunidadeAprimoramento = 'Em Análise aos dados do Sistema WebCont – Composição Analítica da conta 11202.994000, verificada a posição do mês '. $competencia .' constatou-se que não havia histórico de pendências de débito de Empregados maior que 90 dias.';
                                $dto = DB::table('itensdeinspecoes')
                                    ->Where([['inspecao_id', '=', $registro->inspecao_id]])
                                    ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
                                    ->select( 'itensdeinspecoes.*'  )
                                    ->first();
                                $itensdeinspecao = Itensdeinspecao::find($dto->id);
                                $itensdeinspecao->avaliacao  = $avaliacao;
                                $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
                                $itensdeinspecao->evidencia  = null;
                                $itensdeinspecao->valorFalta = 0.00;
                                $itensdeinspecao->situacao   = 'Corroborado';
                                $itensdeinspecao->pontuado   = 0.00;
                                $itensdeinspecao->itemQuantificado = 'Não';
                                $itensdeinspecao->orientacao= null;
                                $itensdeinspecao->eventosSistema = 'Item avaliado remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                $itensdeinspecao->update();
                            }
                        } // fim doteste 230 4
                    }
                }// fim doteste se superintendencia = 1
                else
                {
                    // inicio dotestee diversas superintendencias
                    $registros = DB::table('itensdeinspecoes')
                        ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
                        ->join('unidades', 'itensdeinspecoes.unidade_id', '=', 'unidades.id')
                        ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
                        ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
                        ->select('itensdeinspecoes.*','inspecoes.*','unidades.*','testesdeverificacao.*','gruposdeverificacao.*')
                        ->where([['situacao', '=',  'Em Inspeção' ]])
                        ->where([['se', '=', $superintendencia ]])
                        ->where([['inspecoes.ciclo', '=', $ciclo ]])
                        ->where([['itensdeinspecoes.tipoUnidade_id', '=', $tipodeunidade ]])
                        // ->limit(5)
                        ->get();
                    foreach ($registros as $registro)
                    {
                        if((($registro->numeroGrupoVerificacao == 230)&&($registro->numeroDoTeste == 4))
                            || (($registro->numeroGrupoVerificacao == 270)&&($registro->numeroDoTeste == 1)))
                        {
                            $mescompetencia = DB::table('debitoempregados')
                                ->select('competencia')
                                ->where([['debitoempregados.competencia', '>=', 1 ]])
                                ->orderBy('competencia' ,'desc')
                                ->first();
                            $debitoempregados = DB::table('debitoempregados')
                                ->select('data', 'documento', 'historico', 'matricula', 'valor' )
                                ->where([['debitoempregados.data', '<=', $dtmenos90dias ]])
                                ->where([['debitoempregados.sto', '=', $registro->mcu ]])
                                ->orWhere([['debitoempregados.sto', '=', $registro->sto ]])
                                ->get();
                            if(! $debitoempregados->isEmpty())
                            {
                                $count = $debitoempregados->count('matricula');
                                $total = $debitoempregados->sum('valor'); // soma a coluna valor da coleção de dados
                            }
                            else
                            {
                                $count = 0;
                                $total = 0.00;
                            }
                            $competencia = substr($mescompetencia->competencia, 4, 2).'/'.substr($mescompetencia->competencia, 0, 4);
                            if($count >= 1){
                                $avaliacao = 'Não Conforme';
                                $oportunidadeAprimoramento = 'Em Análise aos dados do Sistema WebCont – Composição Analítica da conta 11202.994000, posição de '
                                    . $competencia .', constatou-se a existência de '. $count . ' débitos de empregado sem regularização há mais de 90 dias, conforme relacionado a seguir:';
                                $evidencia = "\n".'Data'."\t".'Documento'."\t".'Histórico'."\t".'Matricula'."\t".'Valor';
                                foreach($debitoempregados as $debitoempregado){
                                    $evidencia =  $evidencia ."\n". date( 'd/m/Y' , strtotime($debitoempregado->data))
                                        ."\t". $debitoempregado->documento
                                        ."\t". $debitoempregado->historico
                                        ."\t". $debitoempregado->matricula
                                        ."\t". ' R$ '. number_format($debitoempregado->valor, 2, ',', '.');
                                }
                                $evidencia =  $evidencia ."\n".'Total '."\t".'R$ '. number_format($total, 2, ',', '.');
                                $relevancias = DB::table('relevancias')
                                    ->select( 'relevancias.*'  )
                                    ->get();
                                $tolerancia = ($relevancias->min('valor_final') * 0.1);
                                if($total <= $tolerancia ){
                                    $avaliacao = 'Conforme';
                                    $oportunidadeAprimoramento = 'Em Análise aos dados do Sistema WebCont – Composição Analítica da conta 11202.994000, verificada a posição do mês '. $competencia .' constatou-se que havia histórico de pendências de débito de Empregados maior que 90 dias. Porém, a mesma está dentro da margem de tolerância definida pelo Departamento de Controle Interno que é de R$ '. number_format($relevancias->min('valor_final'), 2, ',', '.');
                                    $pontuado=0.00;
                                }
                                else{
                                    foreach ($relevancias as $row)
                                    {
                                        if(($row->valor_inicio >= $total) || ($row->valor_final <= $total))
                                        {
                                            $pontuado = $row->fator_multiplicador * intval($registro->totalPontos) ;
                                        }
                                    }
                                }
                                $dto = DB::table('itensdeinspecoes')
                                    ->Where([['inspecao_id', '=', $registro->inspecao_id]])
                                    ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
                                    ->select( 'itensdeinspecoes.*'  )
                                    ->first();
                                $itensdeinspecao = Itensdeinspecao::find($dto->id);
                                $itensdeinspecao->avaliacao  = $avaliacao;
                                $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
                                $itensdeinspecao->evidencia  = $evidencia;
                                $itensdeinspecao->valorFalta = $total;
                                $itensdeinspecao->situacao   = 'Corroborado';
                                $itensdeinspecao->pontuado   = $pontuado;
                                $itensdeinspecao->itemQuantificado = 'Sim';
                                $itensdeinspecao->orientacao= $registro->orientacao;
                                $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                $itensdeinspecao->update();

                            }else{  //se não houve registro para a unidade o contador é zero e o resultado é conforme

                                $avaliacao = 'Conforme';
                                $oportunidadeAprimoramento = 'Em Análise aos dados do Sistema WebCont – Composição Analítica da conta 11202.994000, verificada a posição do mês '. $competencia .' constatou-se que não havia histórico de pendências de débito de Empregados maior que 90 dias.';
                                $dto = DB::table('itensdeinspecoes')
                                    ->Where([['inspecao_id', '=', $registro->inspecao_id]])
                                    ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
                                    ->select( 'itensdeinspecoes.*'  )
                                    ->first();
                                $itensdeinspecao = Itensdeinspecao::find($dto->id);
                                $itensdeinspecao->avaliacao  = $avaliacao;
                                $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
                                $itensdeinspecao->evidencia  = null;
                                $itensdeinspecao->valorFalta = 0.00;
                                $itensdeinspecao->situacao   = 'Corroborado';
                                $itensdeinspecao->pontuado   = 0.00;
                                $itensdeinspecao->itemQuantificado = 'Não';
                                $itensdeinspecao->orientacao= null;
                                $itensdeinspecao->eventosSistema = 'Item avaliado remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                $itensdeinspecao->update();
                            }
                        } // fim doteste
                    }
                }
            }
        }

    }
}
