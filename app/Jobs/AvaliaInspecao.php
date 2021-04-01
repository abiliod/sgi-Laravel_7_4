<?php

namespace App\Jobs;

use App\Models\Correios\Itensdeinspecao;
use App\Models\Correios\ModelsAuxiliares\SL02_bdf;
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
    public function handle() {
        ini_set('memory_limit', '512M');

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
        $valorRisco=0.00;
        $valorSobra=0.00;
        $valorFalta=0.00;

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

        foreach ($superintendencias as $res) { // request é Array de indice para Superintendências
            foreach ($res as $superintendencia) { // percorre o Array de objeto Superintendências

                // testa se o primeiro parâmetro é para todas superintendecia onde SE == 1
                // Inicio do teste para todas superintendencias
                if ($superintendencia == 1) {
                    // se verdadeiro se SE == 1 seleciona todas superintendência cujo a SE > 1
                    $registros = DB::table('itensdeinspecoes')
                        ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
                        ->join('unidades', 'itensdeinspecoes.unidade_id', '=', 'unidades.id')
                        ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
                        ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
                        ->select('itensdeinspecoes.*','inspecoes.*','unidades.*','testesdeverificacao.*','gruposdeverificacao.*')
                        ->where([['situacao', '=',  'Em Inspeção' ]])
                        ->where([['se', '=', 1 ]])
                        ->where([['inspecoes.ciclo', '=', $ciclo ]])
                        ->where([['itensdeinspecoes.tipoUnidade_id', '=', $tipodeunidade ]])
                        ->get();

//                      Inicio processamento da aavaliação
                    foreach ($registros as $registro) {


//                      Inicio  do teste Extravio Responsabilidade Definida
                        if((($registro->numeroGrupoVerificacao == 205)&&($registro->numeroDoTeste == 2))
                            || (($registro->numeroGrupoVerificacao==334)&&($registro->numeroDoTeste==1))
                            || (($registro->numeroGrupoVerificacao==372)&&($registro->numeroDoTeste==1))
                            || (($registro->numeroGrupoVerificacao==286)&&($registro->numeroDoTeste==2))
                            || (($registro->numeroGrupoVerificacao==221)&&($registro->numeroDoTeste==2))
                            || (($registro->numeroGrupoVerificacao==354)&&($registro->numeroDoTeste==1))
                            || (($registro->numeroGrupoVerificacao == 231)&&($registro->numeroDoTeste == 1))
                            || (($registro->numeroGrupoVerificacao==271)&&($registro->numeroDoTeste==1))) {


                            $codVerificacaoAnterior = null;
                            $numeroGrupoReincidente = null;
                            $numeroItemReincidente = null;
                            $consequencias = null;
                            $orientacao = null;
                            $evidencia = null;
                            $valorSobra = null;
                            $valorFalta = null;
                            $valorRisco = null;
                            $total = 0;
                            $pontuado = null;
                            $itemQuantificado = 'Não';
                            $reincidente = 0;
                            $reinc = 'Não';
                            $dtmin = $dtnow;
                            $count = 0;

                            //verifica histórico de inspeções
                            $reincidencia = DB::table('snci')
                                ->select('no_inspecao', 'no_grupo', 'no_item', 'dt_fim_inspecao', 'dt_inic_inspecao')
                                ->where([['descricao_item', 'like', '%objetos indenizados por extravio%']])
                                ->where([['sto', '=', $registro->sto]])
                                ->orderBy('no_inspecao', 'desc')
                                ->first();

                            try {

                                if ($reincidencia->no_inspecao > 1) {
//                                        dd($reincidencia);
                                    $reincidente = 1;
                                    $reinc = 'Sim';
                                    $codVerificacaoAnterior = $reincidencia->no_inspecao;
                                    $numeroGrupoReincidente = $reincidencia->no_grupo;
                                    $numeroItemReincidente = $reincidencia->no_item;
                                    $reincidencia_dt_fim_inspecao = new Carbon($reincidencia->dt_fim_inspecao);
                                    $reincidencia_dt_inic_inspecao = new Carbon($reincidencia->dt_inic_inspecao);
                                    $reincidencia_dt_fim_inspecao->subMonth(3);
                                    $reincidencia_dt_inic_inspecao->subMonth(3);

                                    //se houver registros de inspeções anteriores  consulta  com range  entre datas
                                    $resp_definidas = DB::table('resp_definidas')
                                        ->select('mcu', 'unidade', 'data_pagamento', 'objeto', 'nu_sei', 'data', 'situacao', 'valor_da_indenizacao')
                                        ->where('mcu', '=', $registro->mcu)
                                        ->where('data_pagamento', '<=', $dtmenos90dias)
                                        ->where('data_pagamento', '>=', $reincidencia_dt_fim_inspecao)
                                        ->where('nu_sei', '=', '')

                                        ->get();

                                }
                                else{
                                    $resp_definidas = DB::table('resp_definidas')
                                        ->select('mcu', 'unidade', 'data_pagamento', 'objeto', 'nu_sei', 'data', 'situacao', 'valor_da_indenizacao')
                                        ->where('mcu', '=', $registro->mcu)
                                        ->where('data_pagamento', '<=', $dtmenos90dias)
                                        ->where('nu_sei', '=', '')
                                        ->get();
                                }
                            }
                            catch (\Exception $e) {

                                $resp_definidas = DB::table('resp_definidas')
                                    ->select('mcu', 'unidade', 'data_pagamento', 'objeto', 'nu_sei', 'data', 'situacao', 'valor_da_indenizacao')
                                    ->where('mcu', '=', $registro->mcu)
                                    ->where('data_pagamento', '<=', $dtmenos90dias)
                                    ->where('nu_sei', '=', '')
                                    ->get();
                            }

                            if (!$resp_definidas->isEmpty()) {
                                $count = $resp_definidas->count('objeto');
                                $total = $resp_definidas->sum('valor_da_indenizacao');
                                $dtmax = $dtmenos90dias;
                                $avaliacao = 'Não Conforme';
                                $oportunidadeAprimoramento = 'Em análise à planilha de controle de processos de apuração de extravios de objetos indenizados com responsabilidade definida, disponibilizada pela área de Segurança da Superintendência Regional CSEP, que detem informações a partir de 2015 até ' . date('d/m/Y', strtotime($dtmax)) . ', constatou-se a existência de ' . $count . ' processos pendentes de conclusão há mais de 90 dias sob responsabilidade da unidade, conforme relacionado a seguir:';
                                $consequencias = $registro->consequencias;
                                $orientacao = $registro->orientacao;
                                $valorFalta =  $total;
                                $evidencia = $evidencia. "\n" . 'Número Objeto' . "\t" . 'Número Processo' . "\t" . 'Data Processo' . "\t" . 'Data Atualização' . "\t" . 'Última Atualização' . "\t" . 'Valor' ;

                                foreach ($sl02bdfs90 as $tabela) {
//      ########## ATENÇÃO ##########
// 01/04/2020 Abilio esse trecho de código precisa ser testado não havia dados suficiete para implementar o
// teste no desenvolvimento caso houver algum ajuste  aualizar o controller InspeçãoController para esse item.

                                    $evidencia = $evidencia . "\n" . $tabela->objeto . "\t"
                                        . (isset($tabela->nu_sei) && $tabela->nu_sei == ''  ? '   ----------  ' : $tabela->nu_sei)
                                        . "\t" . (isset($tabela->data_pagamento) && $tabela->data_pagamento == ''  ? '   ----------  '
                                            : date('d/m/Y', strtotime($tabela->data_pagamento)))
                                        . "\t" . (isset($tabela->data) && $tabela->data == ''  ? '   ----------  '
                                            : date('d/m/Y', strtotime($tabela->data)))
                                        . "\t" . (isset($tabela->situacao) && $tabela->situacao == ''  ? '   ----------  '
                                            : $tabela->situacao)
                                        . "\t" .  'R$'.number_format($tabela->valor_da_indenizacao, 2, ',', '.');
                                }
                                $evidencia = $evidencia . "\n" . 'Valor em Falta :'. "\t" .  'R$'.number_format($valorFalta, 2, ',', '.');
//        ####################
                            } else {
                                $dtmax = $dtmenos90dias;
                                $avaliacao = 'Conforme';
                                $oportunidadeAprimoramento = 'Em análise à planilha de controle de processos de apuração de extravios de objetos indenizados com responsabilidade definida, disponibilizada pela área de Segurança da Superintendência Regional CSEP, que detem informações a partir de 2015 até ' . date('d/m/Y', strtotime($dtmax)) . ', constatou-se a inexistência de processos pendentes de conclusão há mais de 90 dias sob responsabilidade da unidade.';
                            }

                            $quebra = DB::table('relevancias')
                                ->select('valor_final')
                                ->where('fator_multiplicador', '=', 1)
                                ->first();
                            $quebracaixa = $quebra->valor_final * 0.1;

                            if( $valorFalta > $quebracaixa){
                                $fm = DB::table('relevancias')
                                    ->select('fator_multiplicador', 'valor_final', 'valor_inicio')
                                    ->where('valor_inicio', '<=', $total)
                                    ->orderBy('valor_final', 'desc')
                                    ->first();
                                $pontuado = $registro->totalPontos * $fm->fator_multiplicador;
                            }
                            else{
                                $pontuado = $registro->totalPontos * 1;
                            }
                            $dto = DB::table('itensdeinspecoes')
                                ->Where([['inspecao_id', '=', $registro->inspecao_id]])
                                ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
                                ->select('itensdeinspecoes.*')
                                ->first();
                            $itensdeinspecao = Itensdeinspecao::find($dto->id);
                            $itensdeinspecao->avaliacao = $avaliacao;
                            $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
                            $itensdeinspecao->evidencia = $evidencia;
                            $itensdeinspecao->valorFalta = $valorFalta;
                            $itensdeinspecao->valorSobra = $valorSobra;
                            $itensdeinspecao->valorRisco = $valorRisco;
                            $itensdeinspecao->situacao = 'Inspecionado';
                            $itensdeinspecao->pontuado = $pontuado;
                            $itensdeinspecao->itemQuantificado = $itemQuantificado;
                            $itensdeinspecao->orientacao = $registro->orientacao;
                            $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em ' . date('d/m/Y', strtotime($dtnow)) . '.';
                            $itensdeinspecao->reincidencia = $reinc;
                            $itensdeinspecao->consequencias = $consequencias;
                            $itensdeinspecao->orientacao = $orientacao;
                            $itensdeinspecao->codVerificacaoAnterior = $codVerificacaoAnterior;
                            $itensdeinspecao->numeroGrupoReincidente = $numeroGrupoReincidente;
                            $itensdeinspecao->numeroItemReincidente = $numeroItemReincidente;
//                          dd('line 1400 -> ',$itensdeinspecao);
                            $itensdeinspecao->update();
//
//                                    return view('compliance.inspecao.editar', compact
//                                    (
//                                        'registro'
//                                        , 'id'
//                                        , 'total'
//                                        , 'resp_definidas'
//                                        , 'dtmax'
//                                        , 'dtmin'
//                                        , 'count'
//                                    ));

                        }
//                      Final  do teste Extravio Responsabilidade Definida

//                       Inicio  do teste SLD-02-BDF
                        if ((($registro->numeroGrupoVerificacao == 230) && ($registro->numeroDoTeste == 7))
                            || (($registro->numeroGrupoVerificacao == 270) && ($registro->numeroDoTeste == 4))) {

                            $acumulados30 = 0;
                            $acumulados60 = 0;
                            $acumulados90 = 0;
                            $ocorrencias30 = 0;
                            $ocorrencias60 = 0;
                            $ocorrencias90 = 0;
                            $codVerificacaoAnterior = null;
                            $numeroGrupoReincidente = null;
                            $numeroItemReincidente = null;
                            $evidencia = null;
                            $valorSobra = null;
                            $valorFalta = null;
                            $valorRisco = null;
                            $total = 0;
                            $pontuado = null;
                            $itemQuantificado='Não';
                            $reincidente = 0;
                            $reinc = 'Não';

                            $sl02bdfsMaxdata = SL02_bdf::where('cod_orgao', $registro->sto)->max('dt_movimento');

                            if(! empty($sl02bdfsMaxdata))
                            {
                                $sl02bdfsMaxdata = new Carbon($sl02bdfsMaxdata);
                                $dtmenos30dias = new Carbon($sl02bdfsMaxdata);
                                $dtmenos60dias = new Carbon($sl02bdfsMaxdata);
                                $dtmenos90dias = new Carbon($sl02bdfsMaxdata);
                                $dtmenos30dias = $dtmenos30dias->subDays(30);
                                $dtmenos60dias = $dtmenos60dias->subDays(60);
                                $dtmenos90dias = $dtmenos90dias->subDays(90);
                                $evidencia = null;

                                $sl02bdfs30 = DB::table('sl02bdfs')
                                    ->select('sl02bdfs.*')
                                    ->where('cod_orgao', '=', $registro->sto)
                                    ->where('dt_movimento', '>=', $dtmenos30dias)
                                    ->where('diferenca', '>=', 1)
                                    ->orderBy('dt_movimento', 'desc')
                                    ->get();

                                if (! $sl02bdfs30->isEmpty()) {
                                    $acumulados30 = $sl02bdfs30->sum('diferenca'); // soma a coluna valor da coleção de dados
                                    $ocorrencias30 = $sl02bdfs30->count('diferenca');
                                    $evidencia = $evidencia. "\n" . 'Período '
                                        . date('d/m/Y', strtotime($sl02bdfsMaxdata)).', até '
                                        . date('d/m/Y', strtotime($dtmenos30dias)).'.';
                                    $evidencia = $evidencia. "\n" . 'Data' . "\t" . 'Saldo de Numerário' . "\t" . 'Limite de Saldo' . "\t" . 'Diferença' ;
                                    $row=1;
                                    foreach ($sl02bdfs30 as $tabela) {

                                        $evidencia = $evidencia . "\n" . date('d/m/Y', strtotime($tabela->dt_movimento))
                                            . "\t" . 'R$'.number_format($tabela->saldo_atual, 2, ',', '.')
                                            . "\t" . 'R$'.number_format($tabela->limite, 2, ',', '.')
                                            . "\t" . 'R$'.number_format($tabela->diferenca, 2, ',', '.');
                                        $row ++;
                                    }
                                    $acumulados30 = $acumulados30 / $row;
                                    $evidencia = $evidencia . "\n" .'Quantidade de ocorrências em 30 dias ' .$ocorrencias30
                                        .'. Quantidade média de ocorrências em 30 dias '
                                        .number_format((($ocorrencias30 / 23) * 100), 2, ',', '.')
                                        .'. Valor médio ultrapassado R$ '
                                        .number_format($acumulados30, 2, ',', '.');

                                }

                                $sl02bdfs60 = DB::table('sl02bdfs')
                                    ->select('sl02bdfs.*')
                                    ->where('cod_orgao', '=', $registro->sto)
                                    ->where('dt_movimento', '<', $dtmenos30dias)
                                    ->where('dt_movimento', '>=', $dtmenos60dias)
                                    ->where('diferenca', '>=', 1)
                                    ->orderBy('dt_movimento', 'desc')
                                    ->get();

                                if (! $sl02bdfs60->isEmpty()) {
                                    $acumulados60 = $sl02bdfs60->sum('diferenca'); // soma a coluna valor da coleção de dados
                                    $ocorrencias60 = $sl02bdfs60->count('diferenca');
                                    $evidencia = $evidencia. "\n" . 'Período '
                                        . date('d/m/Y', strtotime($dtmenos30dias)).', até '
                                        . date('d/m/Y', strtotime($dtmenos60dias)).'.';
                                    $evidencia = $evidencia. "\n" . 'Data' . "\t" . 'Saldo de Numerário' . "\t" . 'Limite de Saldo' . "\t" . 'Diferença' ;
                                    $row=1;
                                    foreach ($sl02bdfs60 as $tabela) {

                                        $evidencia = $evidencia . "\n" . date('d/m/Y', strtotime($tabela->dt_movimento))
                                            . "\t" . 'R$'.number_format($tabela->saldo_atual, 2, ',', '.')
                                            . "\t" . 'R$'.number_format($tabela->limite, 2, ',', '.')
                                            . "\t" . 'R$'.number_format($tabela->diferenca, 2, ',', '.');
                                        $row ++;
                                    }
                                    $acumulados60 = $acumulados60 / $row;
                                    $evidencia = $evidencia . "\n" .'Quantidade de ocorrências em 30 dias ' .$ocorrencias60
                                        .'. Quantidade média de ocorrências em 30 dias '
                                        .number_format((($ocorrencias60 / 23) * 100), 2, ',', '.')
                                        .'. Valor médio ultrapassado R$ '
                                        .number_format($acumulados60, 2, ',', '.');

                                }

                                $sl02bdfs90 = DB::table('sl02bdfs')
                                    ->select('sl02bdfs.*')
                                    ->where('cod_orgao', '=', $registro->sto)
                                    ->where('dt_movimento', '<', $dtmenos60dias)
                                    ->where('dt_movimento', '>=', $dtmenos90dias)
                                    ->where('diferenca', '>=', 1)
                                    ->orderBy('dt_movimento', 'desc')
                                    ->get();

                                if (! $sl02bdfs90->isEmpty()) {
                                    $acumulados90 = $sl02bdfs90->sum('diferenca'); // soma a coluna valor da coleção de dados
                                    $ocorrencias90 = $sl02bdfs90->count('diferenca');
                                    $evidencia = $evidencia. "\n" . 'Período '
                                        . date('d/m/Y', strtotime($dtmenos60dias)).', até '
                                        . date('d/m/Y', strtotime($dtmenos90dias)).'.';
                                    $evidencia = $evidencia. "\n" . 'Data' . "\t" . 'Saldo de Numerário' . "\t" . 'Limite de Saldo' . "\t" . 'Diferença' ;
                                    $row=1;
                                    foreach ($sl02bdfs90 as $tabela) {

                                        $evidencia = $evidencia . "\n" . date('d/m/Y', strtotime($tabela->dt_movimento))
                                            . "\t" . 'R$'.number_format($tabela->saldo_atual, 2, ',', '.')
                                            . "\t" . 'R$'.number_format($tabela->limite, 2, ',', '.')
                                            . "\t" . 'R$'.number_format($tabela->diferenca, 2, ',', '.');
                                        $row ++;
                                    }
                                    $acumulados90 = $acumulados90 / $row;
                                    $evidencia = $evidencia . "\n" .'Quantidade de ocorrências em 30 dias ' .$ocorrencias90
                                        .'. Quantidade média de ocorrências em 30 dias '
                                        .number_format((($ocorrencias90 / 23) * 100), 2, ',', '.')
                                        .'. Valor médio ultrapassado R$ '
                                        .number_format($acumulados90, 2, ',', '.');

                                }

                                if(($acumulados30 >= 1) && ($acumulados60 >= 1) && ($acumulados90 >= 1)){
                                    $total = ($acumulados30 + $acumulados60 + $acumulados90)/3;
                                    $ocorrencias = $ocorrencias30 + $ocorrencias60 + $ocorrencias90;
                                }

                                if(($acumulados30 >= 1) && ($acumulados60 >= 1) && ($acumulados90 == 0)){
                                    $total = ($acumulados30 + $acumulados60)/2;
                                    $ocorrencias = $ocorrencias30 + $ocorrencias60;
                                }

                                if(($acumulados30 >= 1) && ($acumulados60 == 0) && ($acumulados90 == 0)){
                                    $total = $acumulados30;
                                    $ocorrencias = $ocorrencias30;
                                }

                                if(($acumulados30 == 0) && ($acumulados60 >= 1) && ($acumulados90 == 0)){
                                    $total = $acumulados60;
                                    $ocorrencias = $ocorrencias60;
                                }

                                if(($acumulados30 == 0) && ($acumulados60 == 0) && ($acumulados90 >= 1)){
                                    $total = $acumulados90;
                                    $ocorrencias = $ocorrencias90;
                                }
//                                  if ( ((($ocorrencias30 / 23) * 100) > 20)  || ((($ocorrencias60 / 23) * 100) > 20) || ((($ocorrencias90 / 23) * 100) > 20))  // 20%
                                if (($ocorrencias30 >= 7) || ($ocorrencias60 >= 7) || ($ocorrencias90 >= 7))   // maior ou igul 7 ocorrências imprime tudo
                                {
                                    $avaliacao = 'Não Conforme';
                                    $oportunidadeAprimoramento = 'Em análise ao Relatório "Saldo de Numerário em relação
                                         ao Limite de Saldo", do sistema BDF, referente ao período de ' . date('d/m/Y', strtotime($dtnow))
                                        . ' a ' . date('d/m/Y', strtotime($dtmenos90dias)) . ',
                                            constatou-se que que o limite do saldo estabelecido para a unidade foi descumprido em '
                                        . $ocorrencias . ' dias, o que corresponde a uma média de ' . $ocorrencias/3 . ' ocorrências por mês, considerando o período, conforme detalhado a seguir:';

                                    $reincidencia = DB::table('snci')
                                        ->select('no_inspecao', 'no_grupo', 'no_item', 'dt_fim_inspecao', 'dt_inic_inspecao')
                                        ->where([['descricao_item', 'like', '%Saldo que Passa%']])
                                        ->where([['sto', '=', $registro->sto]])
                                        ->orderBy('no_inspecao', 'desc')
                                        ->first();

                                    try {
                                        if ($reincidencia->no_inspecao > 1) {
//                                        dd($reincidencia);
                                            $reincidente = 1;
                                            $reinc = 'Sim';
                                            $codVerificacaoAnterior = $reincidencia->no_inspecao;
                                            $numeroGrupoReincidente = $reincidencia->no_grupo;
                                            $numeroItemReincidente = $reincidencia->no_item;
                                            $reincidencia_dt_fim_inspecao = new Carbon($reincidencia->dt_fim_inspecao);
                                            $reincidencia_dt_inic_inspecao = new Carbon($reincidencia->dt_inic_inspecao);
                                            $reincidencia_dt_fim_inspecao->subMonth(3);
                                            $reincidencia_dt_inic_inspecao->subMonth(3);
                                            $evidencia = null;
                                        }
                                    }
                                    catch (\Exception $e) {
                                        $reincidente = 0;
                                        $reinc = 'Não';
                                    }
                                    if ($total > 0.00) {
                                        $itemQuantificado ='Sim';
                                        $evidencia  = $evidencia . "\n" . 'Em Risco ' .number_format($total, 2, ',', '.');
                                        $valorFalta = null;
                                        $valorSobra = null;
                                        $valorRisco = $total;
                                    }

                                    $quebra = DB::table('relevancias')
                                        ->select('valor_final')
                                        ->where('fator_multiplicador', '=', 1)
                                        ->first();
                                    $quebracaixa = $quebra->valor_final * 0.1;

                                    if( $valorFalta > $quebracaixa){

                                        $fm = DB::table('relevancias')
                                            ->select('fator_multiplicador', 'valor_final', 'valor_inicio')
                                            ->where('valor_inicio', '<=', $total)
                                            ->orderBy('valor_final', 'desc')
                                            ->first();
                                        $pontuado = $registro->totalPontos * $fm->fator_multiplicador;
                                    }
                                    else{
                                        $pontuado = $registro->totalPontos * 1;
                                    }
                                }
                                else {
                                    $avaliacao = 'Conforme';
                                    $oportunidadeAprimoramento = 'Em análise ao Relatório "Saldo de Numerário em relação ao Limite
                                         de Saldo", do sistema BDF, referente ao período de ' . date('d/m/Y', strtotime($dtnow)) . ' a '
                                        . date('d/m/Y', strtotime($dtmenos90dias)) . ',
                                            constatou-se que não houve descumprimento do limite de saldo estabelecido para a unidade.';
                                }
                            }
                            else {
                                $avaliacao = 'Nao Verificado';
                                $oportunidadeAprimoramento = 'Não há Registros na base de dados para avaliar a unidade.';
                            }

                            $dto = DB::table('itensdeinspecoes')
                                ->Where([['inspecao_id', '=', $registro->inspecao_id]])
                                ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
                                ->select('itensdeinspecoes.*')
                                ->first();

                            $itensdeinspecao = Itensdeinspecao::find($dto->id);
                            $itensdeinspecao->avaliacao = $avaliacao;
                            $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
                            $itensdeinspecao->evidencia = $evidencia;
                            $itensdeinspecao->valorFalta = $valorFalta;
                            $itensdeinspecao->valorSobra = $valorSobra;
                            $itensdeinspecao->valorRisco = $valorRisco;
                            $itensdeinspecao->situacao = 'Inspecionado';
                            $itensdeinspecao->pontuado = $pontuado;
                            $itensdeinspecao->itemQuantificado = $itemQuantificado;
                            $itensdeinspecao->orientacao = $registro->orientacao;
                            $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em ' . date('d/m/Y', strtotime($dtnow)) . '.';
                            $itensdeinspecao->reincidencia = $reinc;
                            $itensdeinspecao->codVerificacaoAnterior = $codVerificacaoAnterior;
                            $itensdeinspecao->numeroGrupoReincidente = $numeroGrupoReincidente;
                            $itensdeinspecao->numeroItemReincidente = $numeroItemReincidente;
//                                                dd('line 1277 -> ',$itensdeinspecao);
                            $itensdeinspecao->update();
                        }
//                       Final  do teste SLD-02-BDF

//                       Inicio  do teste SMB_BDF
                        if((($registro->numeroGrupoVerificacao == 230)&&($registro->numeroDoTeste == 6))
                            || (($registro->numeroGrupoVerificacao == 270)&&($registro->numeroDoTeste== 3))) {

                            $reincidencia = DB::table('snci')
                                ->select('no_inspecao', 'no_grupo', 'no_item', 'dt_fim_inspecao', 'dt_inic_inspecao')
                                ->where([['descricao_item', 'like', '%valor depositado na conta bancária%']])
                                ->where([['sto', '=', $registro->sto]])
                                ->orderBy('no_inspecao', 'desc')
                                ->first();
                            try {
                                if ($reincidencia->no_inspecao > 1) {
//                                        dd($reincidencia);
                                    $reincidente = 1;
                                    $reinc = 'Sim';
                                    $codVerificacaoAnterior = $reincidencia->no_inspecao;
                                    $numeroGrupoReincidente = $reincidencia->no_grupo;
                                    $numeroItemReincidente = $reincidencia->no_item;
                                    $reincidencia_dt_fim_inspecao = new Carbon($reincidencia->dt_fim_inspecao);
                                    $reincidencia_dt_inic_inspecao = new Carbon($reincidencia->dt_inic_inspecao);
                                    $reincidencia_dt_fim_inspecao->subMonth(3);
                                    $reincidencia_dt_inic_inspecao->subMonth(3);
                                    $evidencia=null;
                                }
                            } catch (\Exception $e) {
                                $reincidente = 0;
                                $reinc = 'Não';
                                $codVerificacaoAnterior = null;
                                $numeroGrupoReincidente = null;
                                $numeroItemReincidente = null;
                                $evidencia=null;
                            }
                            $smb_bdf_naoconciliados = DB::table('smb_bdf_naoconciliados')
                                ->select(
                                    'smb_bdf_naoconciliados.*'
                                )
                                ->where('mcu', '=', $registro->mcu)
                                ->where('Divergencia', '<>', 0)
                                ->where('Status', '=', 'Pendente')
                                ->where('Data', '>=', $dtmenos90dias)
                                ->orderBy('Data', 'asc')
                                ->get();
//                              Inicio  se tem registro de pendências SMB_BDF
                            if (!$smb_bdf_naoconciliados->isEmpty()) {
                                $count = $smb_bdf_naoconciliados->count('id');
                                $dtfim = $smb_bdf_naoconciliados->max('Data');

//                              Inicio  se há divergencia
                                if ($count !== 0) {

                                    $smb = $smb_bdf_naoconciliados->sum('SMBDinheiro') + $smb_bdf_naoconciliados->sum('SMBBoleto');
                                    $bdf = $smb_bdf_naoconciliados->sum('BDFDinheiro') + $smb_bdf_naoconciliados->sum('BDFBoleto');
                                    $divergencia = $smb_bdf_naoconciliados->sum('Divergencia');
//                                      Inicio  se divergencia é um valor diferente de zero
                                    if ($divergencia !== 0.0) {

                                        foreach ($smb_bdf_naoconciliados as $smb_bdf_naoconciliado) {
                                            $smblast = $smb_bdf_naoconciliado->SMBDinheiro + $smb_bdf_naoconciliado->SMBBoleto;
                                            $bdflast = $smb_bdf_naoconciliado->BDFDinheiro + $smb_bdf_naoconciliado->BDFBoleto;
                                            $divergencialast = $smb_bdf_naoconciliado->Divergencia;
                                            $total = ($smblast - $bdflast) - $divergencialast;
                                        }
//                                          Inicio Testa ultimo registro se tem compensação
                                        if (($smblast + $bdflast) == ($divergencialast * -1)) {

                                            $avaliacao = 'Conforme';
                                            $oportunidadeAprimoramento = 'Em análise ao sistema SDE – Sistema de Depósito Bancário, na opção "Contabilização", Conciliação SMB x BDF – dados “Não Conciliados”, referente ao período de '.date( 'd/m/Y' , strtotime($dtmenos90dias)) .' a ' . date( 'd/m/Y' , strtotime($dtnow)).', verificou-se a inexistência de divergências.';
                                            $dto = DB::table('itensdeinspecoes')
                                                ->Where([['inspecao_id', '=', $registro->inspecao_id]])
                                                ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
                                                ->select( 'itensdeinspecoes.*'  )
                                                ->first();
                                            $itensdeinspecao = Itensdeinspecao::find($dto->id);
                                            $itensdeinspecao->avaliacao  = $avaliacao;
                                            $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
                                            $itensdeinspecao->evidencia  = $evidencia;
                                            $itensdeinspecao->valorFalta = $valorFalta;
                                            $itensdeinspecao->valorSobra = $valorSobra;
                                            $itensdeinspecao->valorRisco = $valorRisco;
                                            $itensdeinspecao->consequencias = null;
                                            $itensdeinspecao->situacao   = 'Inspecionado';
                                            $itensdeinspecao->pontuado   = 0.00;
                                            $itensdeinspecao->itemQuantificado = 'Não';
                                            $itensdeinspecao->orientacao= null;
                                            $itensdeinspecao->eventosSistema = 'Item avaliado remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
//                                            dd('line -> 818' ,$itensdeinspecao);
//                                            $itensdeinspecao->update();

                                        }
//                                          Final Testa ultimo registro se tem compensação
//                                          Inicio Testa ultimo registro com compensação
                                        else{

                                            $avaliacao = 'Não Conforme';
                                            $oportunidadeAprimoramento = 'Em análise ao sistema SDE – Sistema de Depósito Bancário, na opção "Contabilização", Conciliação SMB x BDF – dados “Não Conciliados”, referente ao período de ' . date( 'd/m/Y' , strtotime($dtmenos90dias)). ' a ' . date( 'd/m/Y' , strtotime($dtnow)) .', constatou-se a existência de divergências entre o valor depositado na conta bancária dos Correios pela Agência e o valor do bloqueto gerado no sistema SARA, no total de R$ ' .number_format($divergencia, 2, ',', '.').' , conforme relacionado a seguir:';

                                            $evidencia = $evidencia ."\n".'Data'."\t".'Divergência'."\t".'Tipo';
                                            foreach ($smb_bdf_naoconciliados as $smb_bdf_naoconciliado){
                                                $evidencia = $evidencia. "\n"
                                                    .date( 'd/m/Y' , strtotime($smb_bdf_naoconciliado->Data))
                                                    ."\t".'R$ '.number_format($smb_bdf_naoconciliado->Divergencia, 2, ',', '.');

                                                if(($smb_bdf_naoconciliado->BDFDinheiro<>0) && ($smb_bdf_naoconciliado->BDFCheque<>0) && ($smb_bdf_naoconciliado->BDFBoleto<>0)){
                                                    $evidencia = $evidencia. "\t".'Dinheiro/Cheque/Boleto';
                                                }
                                                elseif (($smb_bdf_naoconciliado->BDFDinheiro<>0) && ($smb_bdf_naoconciliado->BDFBoleto<>0)){
                                                    $evidencia = $evidencia. "\t".'Dinheiro/Boleto';
                                                }
                                                elseif (($smb_bdf_naoconciliado->BDFDinheiro<>0) && ($smb_bdf_naoconciliado->BDFCheque<>0)){
                                                    $evidencia = $evidencia. "\t".'Dinheiro/Cheque';
                                                }
                                                elseif (($smb_bdf_naoconciliado->BDFBoleto<>0) && ($smb_bdf_naoconciliado->BDFCheque<>0)){
                                                    $evidencia = $evidencia. "\t".'Boleto/Cheque';
                                                }
                                                elseif ($smb_bdf_naoconciliado->BDFDinheiro<>0){
                                                    $evidencia = $evidencia . "\t".'Dinheiro';
                                                }
                                                elseif ($smb_bdf_naoconciliado->BDFBoleto<>0){
                                                    $evidencia = $evidencia. "\t".'Boleto';
                                                }
                                                elseif ($smb_bdf_naoconciliado->BDFCheque<>0){
                                                    $evidencia = $evidencia . "\t".'Cheque';
                                                }
                                                else{
                                                    $evidencia = $evidencia . "\t".'Não identificado';
                                                }
                                            }

                                            if($divergencia > 0.00) {
                                                $total= $divergencia;
                                                $evidencia = $evidencia. "\n".'Em Falta '.$divergencia;
                                                $valorFalta = $total;
                                                $valorSobra = null;
                                                $valorRisco= null;

                                            }
                                            else{
                                                $total= $divergencia *-1;
                                                $evidencia = $evidencia."\n".'Em Falta '.$total;
                                                $valorSobra = null;
                                                $valorFalta = $total;
                                                $valorRisco= null;
                                            }
//                                                dd('line 876',  $smb , $bdf ,$divergencia, $total );

                                            $quebra = DB::table('relevancias')
                                                ->select('valor_final' )
                                                ->where('fator_multiplicador', '=', 1 )
                                                ->first();
                                            $quebracaixa = $quebra->valor_final * 0.1;
                                            $fm = DB::table('relevancias')
                                                ->select('fator_multiplicador', 'valor_final', 'valor_inicio' )
                                                ->where('valor_inicio', '<=', $total )
                                                ->orderBy('valor_final' ,'desc')
                                                ->first();
                                            $pontuado = $registro->totalPontos * $fm->fator_multiplicador;

//                                                dd('line 821',  $smb , $bdf ,$divergencia, $total );

                                            $dto = DB::table('itensdeinspecoes')
                                                ->Where([['inspecao_id', '=', $registro->inspecao_id]])
                                                ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
                                                ->select( 'itensdeinspecoes.*'  )
                                                ->first();

                                            $itensdeinspecao = Itensdeinspecao::find($dto->id);
                                            $itensdeinspecao->avaliacao  = $avaliacao;
                                            $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
                                            $itensdeinspecao->evidencia  = $evidencia;
                                            $itensdeinspecao->valorFalta = $valorFalta;
                                            $itensdeinspecao->valorSobra = $valorSobra;
                                            $itensdeinspecao->valorRisco = $valorRisco;
                                            $itensdeinspecao->situacao   = 'Inspecionado';
                                            $itensdeinspecao->pontuado   = $pontuado;
                                            $itensdeinspecao->itemQuantificado = 'Sim';
                                            $itensdeinspecao->orientacao = $registro->orientacao;
                                            $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                            $itensdeinspecao->reincidencia = $reinc;
                                            $itensdeinspecao->codVerificacaoAnterior = $codVerificacaoAnterior;
                                            $itensdeinspecao->numeroGrupoReincidente = $numeroGrupoReincidente;
                                            $itensdeinspecao->numeroItemReincidente = $numeroItemReincidente;
//                                                dd('line 917 -> ',$itensdeinspecao);
                                            $itensdeinspecao->update();
                                        }
//                                          Final Testa ultimo registro com compensação
                                    }
//                                      Final  se divergencia é um valor diferente de zero

//                                      Inicio  se divergencia é um valor igual zero
                                    if ($divergencia == 0.0){

                                        $dataanterior = null;
                                        foreach ($smb_bdf_naoconciliados as $smb_bdf_naoconciliado) {
                                            if ($dataanterior !== null) {
                                                $dataantual = $dataanterior;
                                                $dataantual->addDays(1);
                                                $unidade_enderecos = DB::table('unidade_enderecos')
                                                    ->Where([['mcu', '=', $registro->mcu]])
                                                    ->select( 'unidade_enderecos.*'  )
                                                    ->first();
                                                $feriado = DB::table('feriados')
                                                    ->Where([['data_do_feriado', '=', $dataantual]])
                                                    ->Where([['nome_municipio', '=', $unidade_enderecos->cidade]])
                                                    ->Where([['uf', '=', $unidade_enderecos->uf]])
                                                    ->select( 'feriados.*'  )
                                                    ->first();

                                                if($feriado)  {
                                                    $diasemana = $dataanterior;
                                                    $diasemana->addDays(5);
                                                }
                                                else {
                                                    // dayOfWeek returns a number between 0 (sunday) and 6 (saturday)
                                                    $diasemana = $dataanterior->dayOfWeek;
                                                    if ($diasemana == 5) { //Sexta
                                                        $dataanterior->addDays(3);
                                                    }
                                                    if ($diasemana == 4) { //Quinta
                                                        $dataanterior->addDays(4);
                                                    }
                                                    if ($diasemana <= 3) { // seg a quarta
                                                        $dataanterior->addDays(2);
                                                    }
                                                }


                                                $periodo = CarbonPeriod::create($dataanterior, $smb_bdf_naoconciliado->Data);

                                                if($periodo->count()>1){
                                                    $avaliacao = 'Não Conforme';
                                                    $oportunidadeAprimoramento = 'Em análise ao sistema SDE – Sistema de Depósito Bancário, na opção "Contabilização", Conciliação SMB x BDF – dados “Não Conciliados”,  referente ao período de '. date( 'd/m/Y' , strtotime($dtmenos90dias)). ' a ' . date( 'd/m/Y' , strtotime($dtnow)) .', constatou-se a existência de depositos na conta dos Correios pela Agência com prazo superior D+1. Evento em data anterior à '.date( 'd/m/Y' , strtotime($dataanterior)) ;
                                                    $total = $smb_bdf_naoconciliado->BDFBoleto;
                                                    $valorRisco = $smb_bdf_naoconciliado->BDFBoleto;
                                                    break;
                                                }
                                            }
                                            $dataanterior = new Carbon($smb_bdf_naoconciliado->Data);
                                        }
                                        if($periodo->count()>1){
                                            $quebra = DB::table('relevancias')
                                                ->select('valor_final' )
                                                ->where('fator_multiplicador', '=', 1 )
                                                ->first();
                                            $quebracaixa = $quebra->valor_final * 0.1;

                                            $fm = DB::table('relevancias')
                                                ->select('fator_multiplicador', 'valor_final', 'valor_inicio' )
                                                ->where('valor_inicio', '<=', $total )
                                                ->orderBy('valor_final' ,'desc')
                                                ->first();
                                            $pontuado = $registro->totalPontos * $fm->fator_multiplicador;

                                            $evidencia = $evidencia."\n".'Data'."\t".'Valor do Boleto';
                                            foreach ($smb_bdf_naoconciliados as $smb_bdf_naoconciliado) {
                                                $evidencia = $evidencia . "\n"
                                                    . date('d/m/Y', strtotime($smb_bdf_naoconciliado->Data))
                                                    . "\t" . 'R$ ' . number_format($smb_bdf_naoconciliado->BDFBoleto, 2, ',', '.');
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
                                            $itensdeinspecao->valorFalta = $valorFalta;
                                            $itensdeinspecao->valorSobra = $valorSobra;
                                            $itensdeinspecao->valorRisco = $valorRisco;
                                            $itensdeinspecao->situacao   = 'Inspecionado';
                                            $itensdeinspecao->pontuado   = $pontuado;
                                            $itensdeinspecao->itemQuantificado = 'Sim';
                                            $itensdeinspecao->orientacao = $registro->orientacao;
                                            $itensdeinspecao->consequencias = null;
                                            $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                            $itensdeinspecao->reincidencia = $reinc;
                                            $itensdeinspecao->codVerificacaoAnterior = $codVerificacaoAnterior;
                                            $itensdeinspecao->numeroGrupoReincidente = $numeroGrupoReincidente;
                                            $itensdeinspecao->numeroItemReincidente = $numeroItemReincidente;
//                                                dd('line 977 ->  valor em risco ',$itensdeinspecao);
                                            $itensdeinspecao->update();
                                        }
                                        else{
                                            $avaliacao = 'Conforme';
                                            $oportunidadeAprimoramento = 'Em análise ao sistema SDE – Sistema de Depósito Bancário, na opção "Contabilização", Conciliação SMB x BDF – dados “Não Conciliados”, referente ao período de '.date( 'd/m/Y' , strtotime($dtmenos90dias)) .' a ' . date( 'd/m/Y' , strtotime($dtnow)).', verificou-se a inexistência de divergências.';
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
                                            $itensdeinspecao->situacao   = 'Inspecionado';
                                            $itensdeinspecao->pontuado   = 0.00;
                                            $itensdeinspecao->itemQuantificado = 'Não';
                                            $itensdeinspecao->orientacao= null;
                                            $itensdeinspecao->eventosSistema = 'Item avaliado remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
//                                                dd('line -> 994' ,$itensdeinspecao);
                                            $itensdeinspecao->update();
                                        }
                                    }
//                                      Final  se divergencia é um valor igual zero
                                }
//                              Final  se há divergencia

                            }
//                              Final  se tem registro de pendências SMB_BDF
//                              Inicio  se Não tem registro de pendências SMB_BDF
                            else{
                                $avaliacao = 'Conforme';
                                $oportunidadeAprimoramento = 'Em análise ao sistema SDE – Sistema de Depósito Bancário, na opção "Contabilização", Conciliação SMB x BDF – dados “Não Conciliados”, referente ao período de '.date( 'd/m/Y' , strtotime($dtmenos90dias)) .' a ' . date( 'd/m/Y' , strtotime($dtnow)).', verificou-se a inexistência de divergências.';

                                $dto = DB::table('itensdeinspecoes')
                                    ->Where([['inspecao_id', '=', $registro->inspecao_id]])
                                    ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
                                    ->select( 'itensdeinspecoes.*'  )
                                    ->first();
                                $itensdeinspecao = Itensdeinspecao::find($dto->id);
                                $itensdeinspecao->avaliacao  = $avaliacao;
                                $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
                                $itensdeinspecao->evidencia  = $evidencia;
                                $itensdeinspecao->valorFalta = $valorFalta;
                                $itensdeinspecao->valorSobra = $valorSobra;
                                $itensdeinspecao->valorRisco = $valorRisco;
                                $itensdeinspecao->situacao   = 'Inspecionado';
                                $itensdeinspecao->pontuado   = 0.00;
                                $itensdeinspecao->itemQuantificado = 'Não';
                                $itensdeinspecao->consequencias = null;
                                $itensdeinspecao->orientacao= null;
                                $itensdeinspecao->eventosSistema = 'Item avaliado remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
//                                    dd('line -> 1027 não tem registro de pendências SMB_BDF' ,$itensdeinspecao);
                                $itensdeinspecao->update();
                            }
//                              Final  se Não tem registro de pendências SMB_BDF

                        }
//                      Final  do teste SMB_BDF



//                      Inicio do teste PROTER
                        if((($registro->numeroGrupoVerificacao == 202)&&($registro->numeroDoTeste == 1))
                            || (($registro->numeroGrupoVerificacao == 332)&&($registro->numeroDoTeste ==1))
                            || (($registro->numeroGrupoVerificacao == 213)&&($registro->numeroDoTeste ==1))
                            || (($registro->numeroGrupoVerificacao == 230)&&($registro->numeroDoTeste == 5))
                            || (($registro->numeroGrupoVerificacao == 270)&&($registro->numeroDoTeste == 2)))  {
//                                dd($registro);
                            $countproters_peso =0;
                            $countproters_cep =0;
                            $reincidencia = DB::table('snci')
                                ->select('no_inspecao',   'no_grupo',  'no_item','dt_fim_inspecao','dt_inic_inspecao')
                                ->where([['descricao_item',  'like', '%PROTER%']])
                                ->where([['sto', '=', $registro->sto ]])
                                ->orderBy('no_inspecao' ,'desc')
                                ->first();
                            try{
                                if( $reincidencia->no_inspecao > 1) {
                                    $reincidente = 1;
                                    $reinc = 'Sim';
                                    $codVerificacaoAnterior = $reincidencia->no_inspecao;
                                    $numeroGrupoReincidente = $reincidencia->no_grupo;
                                    $numeroItemReincidente = $reincidencia->no_item;
                                    $reincidencia_dt_fim_inspecao = new Carbon($reincidencia->dt_fim_inspecao);
                                    $reincidencia_dt_inic_inspecao = new Carbon($reincidencia->dt_inic_inspecao);
                                    $reincidencia_dt_fim_inspecao->subMonth(3);
                                    $reincidencia_dt_inic_inspecao->subMonth(3);
                                }
                            }
                            catch (\Exception $e) {
                                $reincidente = 0;
                                $reinc = 'Não';
                            }
//                          Inicio tem Reincidencia proter
                            if($reincidente == 1) {
                                $proters_con = DB::table('proters')
                                    ->select(
                                        'tipo_de_pendencia'
                                        , 'no_do_objeto'
                                        , 'cep_entrega_sro'
                                        ,'data_da_pendencia'
                                    )
                                    ->where([['mcu', '=', $registro->mcu]])
                                    ->where([['tipo_de_pendencia', '=', 'CON']])
                                    ->where([['data_da_pendencia', '>=', $reincidencia_dt_fim_inspecao ]])
                                    ->where([['data_da_pendencia', '<=', $dtmenos90dias ]])
                                    ->get();
                                $proters_peso = DB::table('proters')
                                    ->select(
                                        'tipo_de_pendencia'
                                        , 'no_do_objeto'
                                        , 'cep_destino'
                                        , 'divergencia_peso'
                                        , 'diferenca_a_recolher'
                                        , 'data_da_pendencia'
                                    )
                                    ->where([['mcu', '=', $registro->mcu]])
                                    ->where([['tipo_de_pendencia', '=', 'DPC']])
                                    ->where([['divergencia_peso', '=', 'S']])
                                    ->where([['data_da_pendencia', '>=', $reincidencia_dt_fim_inspecao ]])
                                    ->where([['data_da_pendencia', '<=', $dtmenos90dias ]])
                                    ->get();


                                $proters_cep = DB::table('proters')
                                    ->select(
                                        'tipo_de_pendencia'
                                        , 'no_do_objeto'
                                        , 'cep_destino'
                                        , 'divergencia_cep'
                                        , 'diferenca_a_recolher'
                                        , 'data_da_pendencia'
                                    )
                                    ->where([['mcu', '=', $registro->mcu]])
                                    ->where([['tipo_de_pendencia', '=', 'DPC']])
                                    ->where([['divergencia_cep', '=', 'S']])
                                    ->where([['data_da_pendencia', '>=', $reincidencia_dt_fim_inspecao ]])
                                    ->where([['data_da_pendencia', '<=', $dtmenos90dias ]])
                                    ->get();
//                                    dd('var -> ', $proters_cep,$registro->mcu, $reincidencia_dt_fim_inspecao,  $dtmenos90dias );

                                if(! $proters_con->isEmpty()){
                                    $countproters_con = $proters_con->count('no_do_objeto');
                                }
                                else{
                                    $countproters_con = 0;
                                }

                                if(! $proters_peso->isEmpty())
                                {
                                    $countproters_peso = $proters_peso->count('no_do_objeto');
                                    $total_proters_peso  = $proters_peso->sum('diferenca_a_recolher');
                                }
                                else
                                {
                                    $total_proters_peso  = 0.00;
                                }

                                if(! $proters_cep->isEmpty())
                                {
                                    $countproters_cep = $proters_cep->count('no_do_objeto');
                                    $total_proters_cep  = $proters_cep->sum('diferenca_a_recolher');
                                }
                                else
                                {
                                    $total_proters_cep  = 0.00;
                                }

                                $total = $total_proters_peso + $total_proters_cep;
                                $countproters = $countproters_con + $countproters_peso +$countproters_cep;


//                                  Inicio se tem pendencia proter com reincidencia
                                if(($countproters_con >= 1) || ($total > 0.00) ){
                                    $pontuado = 0;  //  verificar  declaração no inicio da rotina
                                    if($total > 0.00) {
                                        $quebra = DB::table('relevancias')
                                            ->select('valor_final' )
                                            ->where('fator_multiplicador', '=', 1 )
                                            ->first();
                                        $quebracaixa = $quebra->valor_final * 0.1;

                                        $fm = DB::table('relevancias')
                                            ->select('fator_multiplicador', 'valor_final', 'valor_inicio' )
                                            ->where('valor_inicio', '<=', $total )
                                            ->orderBy('valor_final' ,'desc')
                                            ->first();
                                        $pontuado = $registro->totalPontos * $fm->fator_multiplicador;
                                    }

                                    $avaliacao = 'Não Conforme';
                                    $oportunidadeAprimoramento = 'Em Análise aos dados do Sistema Proter a partir de Jan/2017. Excetuando os ultimos 90 dias da data dessa inspeção, constatou-se a existência de pendências sem regularização há mais de 90 dias conforme relacionado a seguir:';
                                    $evidencia ='';

                                    if(! $proters_con->isEmpty()){
                                        $countproters_con = $proters_con->count('no_do_objeto');
                                        $evidencia = "\n".'Pendencia(s) de Contabilização: '.$countproters_con.' Pendência(s)';
                                        $evidencia = $evidencia = "\n".'Data da Pendência'."\t".'Número do Objeto'."\t".'CEP Entrega';

                                        foreach($proters_con as $proter_con){
                                            $evidencia = $evidencia = "\n".date( 'd/m/Y' , strtotime($proter_con->data_da_pendencia))
                                                ."\t".$proter_con->no_do_objeto
                                                ."\t".$proter_con->cep_entrega_sro;
                                        }
                                    }

                                    if ($total > $quebracaixa ) {

                                        if (!$proters_peso->isEmpty()) {

                                            $countproters_peso = $proters_peso->count('no_do_objeto');
                                            $evidencia1 = "\n" . 'Divergência(s) de Peso: ' . $countproters_peso . ' Pendência(s)';
                                            $evidencia1 = $evidencia1 = "\n" . 'Data da Pendência' . "\t" . 'Número do Objeto' . "\t" . 'Diferença na Tarifação (R$)';
                                            foreach ($proters_peso as $proter_peso) {
                                                $evidencia1 = $evidencia1 = "\n" . date('d/m/Y', strtotime($proter_peso->data_da_pendencia))
                                                    . "\t" . $proter_peso->no_do_objeto
                                                    . "\t" . 'R$ ' . number_format($proter_peso->diferenca_a_recolher, 2, ',', '.');
                                            }
                                        }
                                        if (!$proters_cep->isEmpty()) {

                                            $countproters_cep = $proters_cep->count('no_do_objeto');
                                            $evidencia2 = "\n" . 'Divergência(s) de CEP: ' . $countproters_cep . ' Pendência(s)';
                                            $evidencia2 = $evidencia2 = "\n" . 'Data da Pendência' . "\t" . 'Número do Objeto' . "\t" . 'Diferença na Tarifação (R$)';
                                            foreach ($proters_cep as $proter_cep) {
                                                $evidencia2 = $evidencia2 = "\n" . date('d/m/Y', strtotime($proter_cep->data_da_pendencia))
                                                    . "\t" . $proter_cep->no_do_objeto
                                                    . "\t" . 'R$ ' . number_format($proter_cep->diferenca_a_recolher, 2, ',', '.');
                                            }
                                        }

                                        $evidencia3 = "\n" . 'Total ' . "\t" . 'R$ ' . number_format($total, 2, ',', '.');
                                    }

                                    if ((!empty($evidencia2)) && (!empty($evidencia1))) {
                                        $evidencia = $evidencia . $evidencia1 . $evidencia2 . $evidencia3;
                                    }
                                    elseif (!empty($evidencia1)) {
                                        $evidencia = $evidencia . $evidencia1 . $evidencia3;
                                    }
                                    elseif(!empty($evidencia2)){
                                        $evidencia = $evidencia . $evidencia2 . $evidencia3;
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
                                    $itensdeinspecao->situacao   = 'Inspecionado';
                                    $itensdeinspecao->pontuado   = $pontuado;
                                    $itensdeinspecao->itemQuantificado = 'Sim';
                                    $itensdeinspecao->orientacao = $registro->orientacao;
                                    $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                    $itensdeinspecao->reincidencia = $reinc;
                                    $itensdeinspecao->codVerificacaoAnterior = $codVerificacaoAnterior;
                                    $itensdeinspecao->numeroGrupoReincidente = $numeroGrupoReincidente;
                                    $itensdeinspecao->numeroItemReincidente = $numeroItemReincidente;
                                    $itensdeinspecao->update();
                                }
//                                  Fim se tem pendencia proter com reincidencia
//                                  Inicio Não tem pendencia proter com reincidencia
                                else{
                                    $avaliacao = 'Conforme';
                                    $oportunidadeAprimoramento = 'Em análise aos dados do Sistema Proter, do período de Janeiro/2017 a '. date( 'd/m/Y' , strtotime($dtmenos90dias)).', constatou-se que não havia pendências com mais de 90 dias.';
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
                                    $itensdeinspecao->situacao   = 'Inspecionado';
                                    $itensdeinspecao->pontuado   = 0.00;
                                    $itensdeinspecao->itemQuantificado = 'Não';
                                    $itensdeinspecao->orientacao= null;
                                    $itensdeinspecao->eventosSistema = 'Item avaliado remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                    $itensdeinspecao->update();
                                }
//                                  fim Não tem pendencia proter com reincidencia

                            }
//                          Fim se tem reincidencia

//                          Inicio se não reincidencia
                            else {
                                $proters_con = DB::table('proters')
                                    ->select(
                                        'tipo_de_pendencia'
                                        , 'no_do_objeto'
                                        , 'cep_entrega_sro'
                                        ,'data_da_pendencia'
                                    )
                                    ->where([['mcu', '=', $registro->mcu]])
                                    ->where([['tipo_de_pendencia', '=', 'CON']])
//                                        ->where([['data_da_pendencia', '>=', $reincidencia_dt_fim_inspecao ]])
                                    ->where([['data_da_pendencia', '<=', $dtmenos90dias ]])
                                    ->get();

                                $proters_peso = DB::table('proters')
                                    ->select(
                                        'tipo_de_pendencia'
                                        , 'no_do_objeto'
                                        , 'cep_destino'
                                        , 'divergencia_peso'
                                        , 'diferenca_a_recolher'
                                        , 'data_da_pendencia'
                                    )
                                    ->where([['mcu', '=', $registro->mcu]])
                                    ->where([['tipo_de_pendencia', '=', 'DPC']])
                                    ->where([['divergencia_peso', '=', 'S']])
//                                        ->where([['data_da_pendencia', '>=', $reincidencia_dt_fim_inspecao ]])
                                    ->where([['data_da_pendencia', '<=', $dtmenos90dias ]])
                                    ->get();

                                $proters_cep = DB::table('proters')
                                    ->select(
                                        'tipo_de_pendencia'
                                        , 'no_do_objeto'
                                        , 'cep_destino'
                                        , 'divergencia_cep'
                                        , 'diferenca_a_recolher'
                                        , 'data_da_pendencia'
                                    )
                                    ->where([['mcu', '=', $registro->mcu]])
                                    ->where([['tipo_de_pendencia', '=', 'DPC']])
                                    ->where([['divergencia_cep', '=', 'S']])
//                                        ->where([['data_da_pendencia', '>=', $reincidencia_dt_fim_inspecao ]])
                                    ->where([['data_da_pendencia', '<=', $dtmenos90dias ]])
                                    ->get();

                                if(! $proters_con->isEmpty()){
                                    $countproters_con = $proters_con->count('no_do_objeto');
                                }
                                else{
                                    $countproters_con = 0;
                                }

                                if(! $proters_peso->isEmpty())
                                {
                                    $countproters_peso = $proters_peso->count('no_do_objeto');
                                    $total_proters_peso  = $proters_peso->sum('diferenca_a_recolher');
                                }
                                else
                                {
                                    $total_proters_peso  = 0.00;
                                }

                                if(! $proters_cep->isEmpty())
                                {
                                    $countproters_cep = $proters_cep->count('no_do_objeto');
                                    $total_proters_cep  = $proters_cep->sum('diferenca_a_recolher');
                                }
                                else
                                {
                                    $total_proters_cep  = 0.00;
                                }

                                $total = $total_proters_peso + $total_proters_cep;
                                $countproters = $countproters_con + $countproters_peso +$countproters_cep;
//                                  Inicio se tem pendencia proter sem reincidencia
                                if($countproters >= 1){

                                    if(($countproters_con >= 1) || ($total > 0.00) ) {
                                        $pontuado = 0;  //  verificar  declaração no inicio da rotina
                                        if ($total > 0.00) {
                                            $quebra = DB::table('relevancias')
                                                ->select('valor_final')
                                                ->where('fator_multiplicador', '=', 1)
                                                ->first();
                                            $quebracaixa = $quebra->valor_final * 0.1;

                                            $fm = DB::table('relevancias')
                                                ->select('fator_multiplicador', 'valor_final', 'valor_inicio')
                                                ->where('valor_inicio', '<=', $total)
                                                ->orderBy('valor_final', 'desc')
                                                ->first();
                                            $pontuado = $registro->totalPontos * $fm->fator_multiplicador;
                                        }

                                        $avaliacao = 'Não Conforme';
                                        $oportunidadeAprimoramento = 'Em análise aos dados do Sistema Proter do período de  Janeiro/2017 a ' . date('d/m/Y', strtotime($dtmenos90dias)) . ' constatou-se as seguintes pendências com mais de 90 dias:';
                                        $evidencia = '';
                                        if (!$proters_con->isEmpty()) {

                                            $countproters_con = $proters_con->count('no_do_objeto');
                                            $evidencia = "\n" . 'Pendencia(s) de Contabilização: ' . $countproters_con . ' Pendência(s)';
                                            $evidencia = $evidencia = "\n" . 'Data da Pendência' . "\t" . 'Número do Objeto' . "\t" . 'CEP Entrega';

                                            foreach ($proters_con as $proter_con) {
                                                $evidencia = $evidencia = "\n" . date('d/m/Y', strtotime($proter_con->data_da_pendencia))
                                                    . "\t" . $proter_con->no_do_objeto
                                                    . "\t" . $proter_con->cep_entrega_sro;
                                            }
                                        }

                                        if ($total > $quebracaixa) {

                                            if (!$proters_peso->isEmpty()) {

                                                $countproters_peso = $proters_peso->count('no_do_objeto');
                                                $evidencia1 = "\n" . 'Divergência(s) de Peso: ' . $countproters_peso . ' Pendência(s)';
                                                $evidencia1 = $evidencia1 = "\n" . 'Data da Pendência' . "\t" . 'Número do Objeto' . "\t" . 'Diferença na Tarifação (R$)';
                                                foreach ($proters_peso as $proter_peso) {
                                                    $evidencia1 = $evidencia1 = "\n" . date('d/m/Y', strtotime($proter_peso->data_da_pendencia))
                                                        . "\t" . $proter_peso->no_do_objeto
                                                        . "\t" . 'R$ ' . number_format($proter_peso->diferenca_a_recolher, 2, ',', '.');
                                                }
                                            }
                                            if (!$proters_cep->isEmpty()) {

                                                $countproters_cep = $proters_cep->count('no_do_objeto');
                                                $evidencia2 = "\n" . 'Divergência(s) de CEP: ' . $countproters_cep . ' Pendência(s)';
                                                $evidencia2 = $evidencia2 = "\n" . 'Data da Pendência' . "\t" . 'Número do Objeto' . "\t" . 'Diferença na Tarifação (R$)';
                                                foreach ($proters_cep as $proter_cep) {
                                                    $evidencia2 = $evidencia2 = "\n" . date('d/m/Y', strtotime($proter_cep->data_da_pendencia))
                                                        . "\t" . $proter_cep->no_do_objeto
                                                        . "\t" . 'R$ ' . number_format($proter_cep->diferenca_a_recolher, 2, ',', '.');
                                                }
                                            }

                                            $evidencia3 = "\n" . 'Total ' . "\t" . 'R$ ' . number_format($total, 2, ',', '.');
                                        }

                                        if ((!empty($evidencia2)) && (!empty($evidencia1))) {
                                            $evidencia = $evidencia . $evidencia1 . $evidencia2 . $evidencia3;
                                        }
                                        elseif (!empty($evidencia1)) {
                                            $evidencia = $evidencia . $evidencia1 . $evidencia3;
                                        }
                                        elseif(!empty($evidencia2)){
                                            $evidencia = $evidencia . $evidencia2 . $evidencia3;
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
                                    $itensdeinspecao->situacao   = 'Inspecionado';
                                    $itensdeinspecao->pontuado   = $pontuado;
                                    $itensdeinspecao->itemQuantificado = 'Sim';
                                    $itensdeinspecao->orientacao = $registro->orientacao;
                                    $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                    $itensdeinspecao->reincidencia = $reinc;
                                    $itensdeinspecao->codVerificacaoAnterior = $codVerificacaoAnterior;
                                    $itensdeinspecao->numeroGrupoReincidente = $numeroGrupoReincidente;
                                    $itensdeinspecao->numeroItemReincidente = $numeroItemReincidente;
                                    $itensdeinspecao->update();
                                }
//                                  Fim se tem pendencia proter sem reincidencia
//                                  Inicio conforme
                                else{
                                    $avaliacao = 'Conforme';
                                    $oportunidadeAprimoramento = 'Em análise aos dados do Sistema Proter, do período de Janeiro/2017 a '. date( 'd/m/Y' , strtotime($dtmenos90dias)).', constatou-se que não havia pendências com mais de 90 dias.';
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
                                    $itensdeinspecao->situacao   = 'Inspecionado';
                                    $itensdeinspecao->pontuado   = 0.00;
                                    $itensdeinspecao->itemQuantificado = 'Não';
                                    $itensdeinspecao->orientacao= null;
                                    $itensdeinspecao->eventosSistema = 'Item avaliado remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                    $itensdeinspecao->update();
                                }
//                                  Fim Conforme


                            }
//                          Fim se não  reincidencia
//                          dd( '653',$dtmenos90dias , $registro->mcu, $countproters_con, $countproters_peso,  $countproters_cep );

                        }
//                      Final do teste PROTER

//                      Início do teste WebCont
                        if((($registro->numeroGrupoVerificacao == 230)&&($registro->numeroDoTeste == 4))
                            || (($registro->numeroGrupoVerificacao == 270)&&($registro->numeroDoTeste == 1))) {

                            $reincidencia = DB::table('snci')
                                ->select('no_inspecao',   'no_grupo',  'no_item','dt_fim_inspecao','dt_inic_inspecao')
                                ->where([['descricao_item',  'like', '%3131)?%']])
                                ->where([['sto', '=', $registro->sto ]])
                                ->orderBy('no_inspecao' ,'desc')
                                ->first();
                            try{
                                if( $reincidencia->no_inspecao > 1) {
                                    $reincidente = 1;
                                    $reinc = 'Sim';
                                    $codVerificacaoAnterior = $reincidencia->no_inspecao;
                                    $numeroGrupoReincidente = $reincidencia->no_grupo;
                                    $numeroItemReincidente = $reincidencia->no_item;
                                    $reincidencia_dt_fim_inspecao = new Carbon($reincidencia->dt_fim_inspecao);
                                    $reincidencia_dt_inic_inspecao = new Carbon($reincidencia->dt_inic_inspecao);
                                    $reincidencia_dt_fim_inspecao->subMonth(3);
                                    $reincidencia_dt_inic_inspecao->subMonth(3);
                                }
                            }
                            catch (\Exception $e) {
                                $reincidente = 0;
                            }
                            $mescompetencia = DB::table('debitoempregados')
                                ->select('competencia')
                                ->where([['debitoempregados.competencia', '>=', 1 ]])
                                ->orderBy('competencia' ,'desc')
                                ->first();
                            $competencia = substr($mescompetencia->competencia, 4, 2).'/'.substr($mescompetencia->competencia, 0, 4);
                            if($reincidente == 1) {
                                $debitoempregados = DB::table('debitoempregados')
                                    ->select('data', 'documento', 'historico', 'matricula', 'valor' )
                                    ->where([['debitoempregados.data', '<=', $dtmenos90dias ]])
                                    ->where([['debitoempregados.data', '>=', $reincidencia_dt_fim_inspecao ]])
                                    ->Where([['debitoempregados.sto', '=', $registro->sto ]])
                                    ->get();
                            }
                            else {
                                $debitoempregados = DB::table('debitoempregados')
                                    ->select('data', 'documento', 'historico', 'matricula', 'valor' )
                                    ->where([['debitoempregados.data', '<=', $dtmenos90dias ]])
                                    ->Where([['debitoempregados.sto', '=', $registro->sto ]])
                                    ->get();
                            }

                            if(! $debitoempregados->isEmpty()) {
                                $count = $debitoempregados->count('matricula');
                                $total = $debitoempregados->sum('valor'); // soma a coluna valor da coleção de dados
                                $quebra = DB::table('relevancias')
                                    ->select('valor_final' )
                                    ->where('fator_multiplicador', '=', 1 )
                                    ->first();

                                $quebracaixa = $quebra->valor_final * 0.1;

                                $fm = DB::table('relevancias')
                                    ->select('fator_multiplicador', 'valor_final', 'valor_inicio' )
                                    ->where('valor_inicio', '<=', $total )
                                    ->orderBy('valor_final' ,'desc')
                                    ->first();

                                if(( $count >= 1 ) && ( $total > $quebracaixa ) ) {
                                    $avaliacao = 'Não Conforme';
                                    $pontuado = $registro->totalPontos * $fm->fator_multiplicador;
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
                                    $itensdeinspecao->situacao   = 'Inspecionado';
                                    $itensdeinspecao->pontuado   = $pontuado;
                                    $itensdeinspecao->itemQuantificado = 'Sim';
                                    $itensdeinspecao->orientacao = $registro->orientacao;
                                    $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                    $itensdeinspecao->reincidencia = $reinc;
                                    $itensdeinspecao->codVerificacaoAnterior = $codVerificacaoAnterior;
                                    $itensdeinspecao->numeroGrupoReincidente = $numeroGrupoReincidente;
                                    $itensdeinspecao->numeroItemReincidente = $numeroItemReincidente;
                                    $itensdeinspecao->update();
                                }
                            }
                            else {
//                                    dd('nao  temmmmmmm debitos');
                                //se não houve registro para a unidade o resultado é conforme
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
                                $itensdeinspecao->situacao   = 'Inspecionado';
                                $itensdeinspecao->pontuado   = 0.00;
                                $itensdeinspecao->itemQuantificado = 'Não';
                                $itensdeinspecao->orientacao= null;
                                $itensdeinspecao->eventosSistema = 'Item avaliado remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                $itensdeinspecao->update();
//                                     dd($competencia);
                            }
                        }
                        // fim doteste webCont

                    }

                }  // Fim do teste para todas superintendencias se superintendencia = 1

                // inicio do testee para uma superintendencias
                else {

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
                        ->get();
//                  Inicio processamento da aavaliação
                    foreach ($registros as $registro) {
                        
//                      Inicio  do teste Extravio Responsabilidade Definida
                        if((($registro->numeroGrupoVerificacao == 205)&&($registro->numeroDoTeste == 2))
                            || (($registro->numeroGrupoVerificacao==334)&&($registro->numeroDoTeste==1))
                            || (($registro->numeroGrupoVerificacao==372)&&($registro->numeroDoTeste==1))
                            || (($registro->numeroGrupoVerificacao==286)&&($registro->numeroDoTeste==2))
                            || (($registro->numeroGrupoVerificacao==221)&&($registro->numeroDoTeste==2))
                            || (($registro->numeroGrupoVerificacao==354)&&($registro->numeroDoTeste==1))
                            || (($registro->numeroGrupoVerificacao == 231)&&($registro->numeroDoTeste == 1))
                            || (($registro->numeroGrupoVerificacao==271)&&($registro->numeroDoTeste==1))) {


                            $codVerificacaoAnterior = null;
                            $numeroGrupoReincidente = null;
                            $numeroItemReincidente = null;
                            $consequencias = null;
                            $orientacao = null;
                            $evidencia = null;
                            $valorSobra = null;
                            $valorFalta = null;
                            $valorRisco = null;
                            $total = 0;
                            $pontuado = null;
                            $itemQuantificado = 'Não';
                            $reincidente = 0;
                            $reinc = 'Não';
                            $dtmin = $dtnow;
                            $count = 0;

                            //verifica histórico de inspeções
                            $reincidencia = DB::table('snci')
                                ->select('no_inspecao', 'no_grupo', 'no_item', 'dt_fim_inspecao', 'dt_inic_inspecao')
                                ->where([['descricao_item', 'like', '%objetos indenizados por extravio%']])
                                ->where([['sto', '=', $registro->sto]])
                                ->orderBy('no_inspecao', 'desc')
                                ->first();

                            try {

                                if ($reincidencia->no_inspecao > 1) {
//                                        dd($reincidencia);
                                    $reincidente = 1;
                                    $reinc = 'Sim';
                                    $codVerificacaoAnterior = $reincidencia->no_inspecao;
                                    $numeroGrupoReincidente = $reincidencia->no_grupo;
                                    $numeroItemReincidente = $reincidencia->no_item;
                                    $reincidencia_dt_fim_inspecao = new Carbon($reincidencia->dt_fim_inspecao);
                                    $reincidencia_dt_inic_inspecao = new Carbon($reincidencia->dt_inic_inspecao);
                                    $reincidencia_dt_fim_inspecao->subMonth(3);
                                    $reincidencia_dt_inic_inspecao->subMonth(3);

                                    //se houver registros de inspeções anteriores  consulta  com range  entre datas
                                    $resp_definidas = DB::table('resp_definidas')
                                        ->select('mcu', 'unidade', 'data_pagamento', 'objeto', 'nu_sei', 'data', 'situacao', 'valor_da_indenizacao')
                                        ->where('mcu', '=', $registro->mcu)
                                        ->where('data_pagamento', '<=', $dtmenos90dias)
                                        ->where('data_pagamento', '>=', $reincidencia_dt_fim_inspecao)
                                        ->where('nu_sei', '=', '')

                                        ->get();

                                }
                                else{
                                    $resp_definidas = DB::table('resp_definidas')
                                        ->select('mcu', 'unidade', 'data_pagamento', 'objeto', 'nu_sei', 'data', 'situacao', 'valor_da_indenizacao')
                                        ->where('mcu', '=', $registro->mcu)
                                        ->where('data_pagamento', '<=', $dtmenos90dias)
                                        ->where('nu_sei', '=', '')
                                        ->get();
                                }
                            }
                            catch (\Exception $e) {

                                $resp_definidas = DB::table('resp_definidas')
                                    ->select('mcu', 'unidade', 'data_pagamento', 'objeto', 'nu_sei', 'data', 'situacao', 'valor_da_indenizacao')
                                    ->where('mcu', '=', $registro->mcu)
                                    ->where('data_pagamento', '<=', $dtmenos90dias)
                                    ->where('nu_sei', '=', '')
                                    ->get();
                            }

                            if (!$resp_definidas->isEmpty()) {
                                $count = $resp_definidas->count('objeto');
                                $total = $resp_definidas->sum('valor_da_indenizacao');
                                $dtmax = $dtmenos90dias;
                                $avaliacao = 'Não Conforme';
                                $oportunidadeAprimoramento = 'Em análise à planilha de controle de processos de apuração de extravios de objetos indenizados com responsabilidade definida, disponibilizada pela área de Segurança da Superintendência Regional CSEP, que detem informações a partir de 2015 até ' . date('d/m/Y', strtotime($dtmax)) . ', constatou-se a existência de ' . $count . ' processos pendentes de conclusão há mais de 90 dias sob responsabilidade da unidade, conforme relacionado a seguir:';
                                $consequencias = $registro->consequencias;
                                $orientacao = $registro->orientacao;
                                $valorFalta =  $total;
                                $evidencia = $evidencia. "\n" . 'Número Objeto' . "\t" . 'Número Processo' . "\t" . 'Data Processo' . "\t" . 'Data Atualização' . "\t" . 'Última Atualização' . "\t" . 'Valor' ;

                                foreach ($sl02bdfs90 as $tabela) {
//      ########## ATENÇÃO ##########
// 01/04/2020 Abilio esse trecho de código precisa ser testado não havia dados suficiete para implementar o
// teste no desenvolvimento caso houver algum ajuste  aualizar o controller InspeçãoController para esse item.

                                    $evidencia = $evidencia . "\n" . $tabela->objeto . "\t"
                                        . (isset($tabela->nu_sei) && $tabela->nu_sei == ''  ? '   ----------  ' : $tabela->nu_sei)
                                        . "\t" . (isset($tabela->data_pagamento) && $tabela->data_pagamento == ''  ? '   ----------  '
                                            : date('d/m/Y', strtotime($tabela->data_pagamento)))
                                        . "\t" . (isset($tabela->data) && $tabela->data == ''  ? '   ----------  '
                                            : date('d/m/Y', strtotime($tabela->data)))
                                        . "\t" . (isset($tabela->situacao) && $tabela->situacao == ''  ? '   ----------  '
                                            : $tabela->situacao)
                                        . "\t" .  'R$'.number_format($tabela->valor_da_indenizacao, 2, ',', '.');
                                }
                                $evidencia = $evidencia . "\n" . 'Valor em Falta :'. "\t" .  'R$'.number_format($valorFalta, 2, ',', '.');
//        ####################
                            } else {
                                $dtmax = $dtmenos90dias;
                                $avaliacao = 'Conforme';
                                $oportunidadeAprimoramento = 'Em análise à planilha de controle de processos de apuração de extravios de objetos indenizados com responsabilidade definida, disponibilizada pela área de Segurança da Superintendência Regional CSEP, que detem informações a partir de 2015 até ' . date('d/m/Y', strtotime($dtmax)) . ', constatou-se a inexistência de processos pendentes de conclusão há mais de 90 dias sob responsabilidade da unidade.';
                            }

                            $quebra = DB::table('relevancias')
                                ->select('valor_final')
                                ->where('fator_multiplicador', '=', 1)
                                ->first();
                            $quebracaixa = $quebra->valor_final * 0.1;

                            if( $valorFalta > $quebracaixa){
                                $fm = DB::table('relevancias')
                                    ->select('fator_multiplicador', 'valor_final', 'valor_inicio')
                                    ->where('valor_inicio', '<=', $total)
                                    ->orderBy('valor_final', 'desc')
                                    ->first();
                                $pontuado = $registro->totalPontos * $fm->fator_multiplicador;
                            }
                            else{
                                $pontuado = $registro->totalPontos * 1;
                            }
                            $dto = DB::table('itensdeinspecoes')
                                ->Where([['inspecao_id', '=', $registro->inspecao_id]])
                                ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
                                ->select('itensdeinspecoes.*')
                                ->first();
                            $itensdeinspecao = Itensdeinspecao::find($dto->id);
                            $itensdeinspecao->avaliacao = $avaliacao;
                            $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
                            $itensdeinspecao->evidencia = $evidencia;
                            $itensdeinspecao->valorFalta = $valorFalta;
                            $itensdeinspecao->valorSobra = $valorSobra;
                            $itensdeinspecao->valorRisco = $valorRisco;
                            $itensdeinspecao->situacao = 'Inspecionado';
                            $itensdeinspecao->pontuado = $pontuado;
                            $itensdeinspecao->itemQuantificado = $itemQuantificado;
                            $itensdeinspecao->orientacao = $registro->orientacao;
                            $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em ' . date('d/m/Y', strtotime($dtnow)) . '.';
                            $itensdeinspecao->reincidencia = $reinc;
                            $itensdeinspecao->consequencias = $consequencias;
                            $itensdeinspecao->orientacao = $orientacao;
                            $itensdeinspecao->codVerificacaoAnterior = $codVerificacaoAnterior;
                            $itensdeinspecao->numeroGrupoReincidente = $numeroGrupoReincidente;
                            $itensdeinspecao->numeroItemReincidente = $numeroItemReincidente;
//                          dd('line 1400 -> ',$itensdeinspecao);
                            $itensdeinspecao->update();
//
//                                    return view('compliance.inspecao.editar', compact
//                                    (
//                                        'registro'
//                                        , 'id'
//                                        , 'total'
//                                        , 'resp_definidas'
//                                        , 'dtmax'
//                                        , 'dtmin'
//                                        , 'count'
//                                    ));

                        }
//                      Final  do teste Extravio Responsabilidade Definida

//                       Inicio  do teste SLD-02-BDF
                        if ((($registro->numeroGrupoVerificacao == 230) && ($registro->numeroDoTeste == 7))
                            || (($registro->numeroGrupoVerificacao == 270) && ($registro->numeroDoTeste == 4))) {

                            $acumulados30 = 0;
                            $acumulados60 = 0;
                            $acumulados90 = 0;
                            $ocorrencias30 = 0;
                            $ocorrencias60 = 0;
                            $ocorrencias90 = 0;
                            $codVerificacaoAnterior = null;
                            $numeroGrupoReincidente = null;
                            $numeroItemReincidente = null;
                            $evidencia = null;
                            $valorSobra = null;
                            $valorFalta = null;
                            $valorRisco = null;
                            $total = 0;
                            $pontuado = null;
                            $itemQuantificado='Não';
                            $reincidente = 0;
                            $reinc = 'Não';

                            $sl02bdfsMaxdata = SL02_bdf::where('cod_orgao', $registro->sto)->max('dt_movimento');

                            if(! empty($sl02bdfsMaxdata))
                            {
                                $sl02bdfsMaxdata = new Carbon($sl02bdfsMaxdata);
                                $dtmenos30dias = new Carbon($sl02bdfsMaxdata);
                                $dtmenos60dias = new Carbon($sl02bdfsMaxdata);
                                $dtmenos90dias = new Carbon($sl02bdfsMaxdata);
                                $dtmenos30dias = $dtmenos30dias->subDays(30);
                                $dtmenos60dias = $dtmenos60dias->subDays(60);
                                $dtmenos90dias = $dtmenos90dias->subDays(90);
                                $evidencia = null;

                                $sl02bdfs30 = DB::table('sl02bdfs')
                                    ->select('sl02bdfs.*')
                                    ->where('cod_orgao', '=', $registro->sto)
                                    ->where('dt_movimento', '>=', $dtmenos30dias)
                                    ->where('diferenca', '>=', 1)
                                    ->orderBy('dt_movimento', 'desc')
                                    ->get();

                                if (! $sl02bdfs30->isEmpty()) {
                                    $acumulados30 = $sl02bdfs30->sum('diferenca'); // soma a coluna valor da coleção de dados
                                    $ocorrencias30 = $sl02bdfs30->count('diferenca');
                                    $evidencia = $evidencia. "\n" . 'Período '
                                        . date('d/m/Y', strtotime($sl02bdfsMaxdata)).', até '
                                        . date('d/m/Y', strtotime($dtmenos30dias)).'.';
                                    $evidencia = $evidencia. "\n" . 'Data' . "\t" . 'Saldo de Numerário' . "\t" . 'Limite de Saldo' . "\t" . 'Diferença' ;
                                    $row=1;
                                    foreach ($sl02bdfs30 as $tabela) {

                                        $evidencia = $evidencia . "\n" . date('d/m/Y', strtotime($tabela->dt_movimento))
                                            . "\t" . 'R$'.number_format($tabela->saldo_atual, 2, ',', '.')
                                            . "\t" . 'R$'.number_format($tabela->limite, 2, ',', '.')
                                            . "\t" . 'R$'.number_format($tabela->diferenca, 2, ',', '.');
                                        $row ++;
                                    }
                                    $acumulados30 = $acumulados30 / $row;
                                    $evidencia = $evidencia . "\n" .'Quantidade de ocorrências em 30 dias ' .$ocorrencias30
                                        .'. Quantidade média de ocorrências em 30 dias '
                                        .number_format((($ocorrencias30 / 23) * 100), 2, ',', '.')
                                        .'. Valor médio ultrapassado R$ '
                                        .number_format($acumulados30, 2, ',', '.');

                                }

                                $sl02bdfs60 = DB::table('sl02bdfs')
                                    ->select('sl02bdfs.*')
                                    ->where('cod_orgao', '=', $registro->sto)
                                    ->where('dt_movimento', '<', $dtmenos30dias)
                                    ->where('dt_movimento', '>=', $dtmenos60dias)
                                    ->where('diferenca', '>=', 1)
                                    ->orderBy('dt_movimento', 'desc')
                                    ->get();

                                if (! $sl02bdfs60->isEmpty()) {
                                    $acumulados60 = $sl02bdfs60->sum('diferenca'); // soma a coluna valor da coleção de dados
                                    $ocorrencias60 = $sl02bdfs60->count('diferenca');
                                    $evidencia = $evidencia. "\n" . 'Período '
                                        . date('d/m/Y', strtotime($dtmenos30dias)).', até '
                                        . date('d/m/Y', strtotime($dtmenos60dias)).'.';
                                    $evidencia = $evidencia. "\n" . 'Data' . "\t" . 'Saldo de Numerário' . "\t" . 'Limite de Saldo' . "\t" . 'Diferença' ;
                                    $row=1;
                                    foreach ($sl02bdfs60 as $tabela) {

                                        $evidencia = $evidencia . "\n" . date('d/m/Y', strtotime($tabela->dt_movimento))
                                            . "\t" . 'R$'.number_format($tabela->saldo_atual, 2, ',', '.')
                                            . "\t" . 'R$'.number_format($tabela->limite, 2, ',', '.')
                                            . "\t" . 'R$'.number_format($tabela->diferenca, 2, ',', '.');
                                        $row ++;
                                    }
                                    $acumulados60 = $acumulados60 / $row;
                                    $evidencia = $evidencia . "\n" .'Quantidade de ocorrências em 30 dias ' .$ocorrencias60
                                        .'. Quantidade média de ocorrências em 30 dias '
                                        .number_format((($ocorrencias60 / 23) * 100), 2, ',', '.')
                                        .'. Valor médio ultrapassado R$ '
                                        .number_format($acumulados60, 2, ',', '.');

                                }

                                $sl02bdfs90 = DB::table('sl02bdfs')
                                    ->select('sl02bdfs.*')
                                    ->where('cod_orgao', '=', $registro->sto)
                                    ->where('dt_movimento', '<', $dtmenos60dias)
                                    ->where('dt_movimento', '>=', $dtmenos90dias)
                                    ->where('diferenca', '>=', 1)
                                    ->orderBy('dt_movimento', 'desc')
                                    ->get();

                                if (! $sl02bdfs90->isEmpty()) {
                                    $acumulados90 = $sl02bdfs90->sum('diferenca'); // soma a coluna valor da coleção de dados
                                    $ocorrencias90 = $sl02bdfs90->count('diferenca');
                                    $evidencia = $evidencia. "\n" . 'Período '
                                        . date('d/m/Y', strtotime($dtmenos60dias)).', até '
                                        . date('d/m/Y', strtotime($dtmenos90dias)).'.';
                                    $evidencia = $evidencia. "\n" . 'Data' . "\t" . 'Saldo de Numerário' . "\t" . 'Limite de Saldo' . "\t" . 'Diferença' ;
                                    $row=1;
                                    foreach ($sl02bdfs90 as $tabela) {

                                        $evidencia = $evidencia . "\n" . date('d/m/Y', strtotime($tabela->dt_movimento))
                                            . "\t" . 'R$'.number_format($tabela->saldo_atual, 2, ',', '.')
                                            . "\t" . 'R$'.number_format($tabela->limite, 2, ',', '.')
                                            . "\t" . 'R$'.number_format($tabela->diferenca, 2, ',', '.');
                                        $row ++;
                                    }
                                    $acumulados90 = $acumulados90 / $row;
                                    $evidencia = $evidencia . "\n" .'Quantidade de ocorrências em 30 dias ' .$ocorrencias90
                                        .'. Quantidade média de ocorrências em 30 dias '
                                        .number_format((($ocorrencias90 / 23) * 100), 2, ',', '.')
                                        .'. Valor médio ultrapassado R$ '
                                        .number_format($acumulados90, 2, ',', '.');

                                }

                                if(($acumulados30 >= 1) && ($acumulados60 >= 1) && ($acumulados90 >= 1)){
                                    $total = ($acumulados30 + $acumulados60 + $acumulados90)/3;
                                    $ocorrencias = $ocorrencias30 + $ocorrencias60 + $ocorrencias90;
                                }

                                if(($acumulados30 >= 1) && ($acumulados60 >= 1) && ($acumulados90 == 0)){
                                    $total = ($acumulados30 + $acumulados60)/2;
                                    $ocorrencias = $ocorrencias30 + $ocorrencias60;
                                }

                                if(($acumulados30 >= 1) && ($acumulados60 == 0) && ($acumulados90 == 0)){
                                    $total = $acumulados30;
                                    $ocorrencias = $ocorrencias30;
                                }

                                if(($acumulados30 == 0) && ($acumulados60 >= 1) && ($acumulados90 == 0)){
                                    $total = $acumulados60;
                                    $ocorrencias = $ocorrencias60;
                                }

                                if(($acumulados30 == 0) && ($acumulados60 == 0) && ($acumulados90 >= 1)){
                                    $total = $acumulados90;
                                    $ocorrencias = $ocorrencias90;
                                }
//                                  if ( ((($ocorrencias30 / 23) * 100) > 20)  || ((($ocorrencias60 / 23) * 100) > 20) || ((($ocorrencias90 / 23) * 100) > 20))  // 20%
                                if (($ocorrencias30 >= 7) || ($ocorrencias60 >= 7) || ($ocorrencias90 >= 7))   // maior ou igul 7 ocorrências imprime tudo
                                {
                                    $avaliacao = 'Não Conforme';
                                    $oportunidadeAprimoramento = 'Em análise ao Relatório "Saldo de Numerário em relação
                                         ao Limite de Saldo", do sistema BDF, referente ao período de ' . date('d/m/Y', strtotime($dtnow))
                                        . ' a ' . date('d/m/Y', strtotime($dtmenos90dias)) . ',
                                            constatou-se que que o limite do saldo estabelecido para a unidade foi descumprido em '
                                        . $ocorrencias . ' dias, o que corresponde a uma média de ' . $ocorrencias/3 . ' ocorrências por mês, considerando o período, conforme detalhado a seguir:';

                                    $reincidencia = DB::table('snci')
                                        ->select('no_inspecao', 'no_grupo', 'no_item', 'dt_fim_inspecao', 'dt_inic_inspecao')
                                        ->where([['descricao_item', 'like', '%Saldo que Passa%']])
                                        ->where([['sto', '=', $registro->sto]])
                                        ->orderBy('no_inspecao', 'desc')
                                        ->first();

                                    try {
                                        if ($reincidencia->no_inspecao > 1) {
//                                        dd($reincidencia);
                                            $reincidente = 1;
                                            $reinc = 'Sim';
                                            $codVerificacaoAnterior = $reincidencia->no_inspecao;
                                            $numeroGrupoReincidente = $reincidencia->no_grupo;
                                            $numeroItemReincidente = $reincidencia->no_item;
                                            $reincidencia_dt_fim_inspecao = new Carbon($reincidencia->dt_fim_inspecao);
                                            $reincidencia_dt_inic_inspecao = new Carbon($reincidencia->dt_inic_inspecao);
                                            $reincidencia_dt_fim_inspecao->subMonth(3);
                                            $reincidencia_dt_inic_inspecao->subMonth(3);
                                            $evidencia = null;
                                        }
                                    }
                                    catch (\Exception $e) {
                                        $reincidente = 0;
                                        $reinc = 'Não';
                                    }
                                    if ($total > 0.00) {
                                        $itemQuantificado ='Sim';
                                        $evidencia  = $evidencia . "\n" . 'Em Risco ' .number_format($total, 2, ',', '.');
                                        $valorFalta = null;
                                        $valorSobra = null;
                                        $valorRisco = $total;
                                    }
                                    $quebra = DB::table('relevancias')
                                        ->select('valor_final')
                                        ->where('fator_multiplicador', '=', 1)
                                        ->first();
                                    $quebracaixa = $quebra->valor_final * 0.1;
                                    if( $valorFalta > $quebracaixa){
                                        $fm = DB::table('relevancias')
                                            ->select('fator_multiplicador', 'valor_final', 'valor_inicio')
                                            ->where('valor_inicio', '<=', $total)
                                            ->orderBy('valor_final', 'desc')
                                            ->first();
                                        $pontuado = $registro->totalPontos * $fm->fator_multiplicador;
                                    }
                                    else{
                                        $pontuado = $registro->totalPontos * 1;
                                    }
                                }
                                else {
                                    $avaliacao = 'Conforme';
                                    $oportunidadeAprimoramento = 'Em análise ao Relatório "Saldo de Numerário em relação ao Limite
                                         de Saldo", do sistema BDF, referente ao período de ' . date('d/m/Y', strtotime($dtnow)) . ' a '
                                        . date('d/m/Y', strtotime($dtmenos90dias)) . ',
                                            constatou-se que não houve descumprimento do limite de saldo estabelecido para a unidade.';
                                }
                            }
                            else {
                                $avaliacao = 'Nao Verificado';
                                $oportunidadeAprimoramento = 'Não há Registros na base de dados para avaliar a unidade.';
                            }

                            $dto = DB::table('itensdeinspecoes')
                                ->Where([['inspecao_id', '=', $registro->inspecao_id]])
                                ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
                                ->select('itensdeinspecoes.*')
                                ->first();

                            $itensdeinspecao = Itensdeinspecao::find($dto->id);
                            $itensdeinspecao->avaliacao = $avaliacao;
                            $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
                            $itensdeinspecao->evidencia = $evidencia;
                            $itensdeinspecao->valorFalta = $valorFalta;
                            $itensdeinspecao->valorSobra = $valorSobra;
                            $itensdeinspecao->valorRisco = $valorRisco;
                            $itensdeinspecao->situacao = 'Inspecionado';
                            $itensdeinspecao->pontuado = $pontuado;
                            $itensdeinspecao->itemQuantificado = $itemQuantificado;
                            $itensdeinspecao->orientacao = $registro->orientacao;
                            $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em ' . date('d/m/Y', strtotime($dtnow)) . '.';
                            $itensdeinspecao->reincidencia = $reinc;
                            $itensdeinspecao->codVerificacaoAnterior = $codVerificacaoAnterior;
                            $itensdeinspecao->numeroGrupoReincidente = $numeroGrupoReincidente;
                            $itensdeinspecao->numeroItemReincidente = $numeroItemReincidente;
//                                                dd('line 1277 -> ',$itensdeinspecao);
                            $itensdeinspecao->update();
                        }
//                       Final  do teste SLD-02-BDF

//                       Inicio  do teste SMB_BDF
                        if((($registro->numeroGrupoVerificacao == 230)&&($registro->numeroDoTeste == 6))
                            || (($registro->numeroGrupoVerificacao == 270)&&($registro->numeroDoTeste== 3))) {

                            $reincidencia = DB::table('snci')
                                ->select('no_inspecao', 'no_grupo', 'no_item', 'dt_fim_inspecao', 'dt_inic_inspecao')
                                ->where([['descricao_item', 'like', '%valor depositado na conta bancária%']])
                                ->where([['sto', '=', $registro->sto]])
                                ->orderBy('no_inspecao', 'desc')
                                ->first();
                            try {
                                if ($reincidencia->no_inspecao > 1) {
//                                        dd($reincidencia);
                                    $reincidente = 1;
                                    $reinc = 'Sim';
                                    $codVerificacaoAnterior = $reincidencia->no_inspecao;
                                    $numeroGrupoReincidente = $reincidencia->no_grupo;
                                    $numeroItemReincidente = $reincidencia->no_item;
                                    $reincidencia_dt_fim_inspecao = new Carbon($reincidencia->dt_fim_inspecao);
                                    $reincidencia_dt_inic_inspecao = new Carbon($reincidencia->dt_inic_inspecao);
                                    $reincidencia_dt_fim_inspecao->subMonth(3);
                                    $reincidencia_dt_inic_inspecao->subMonth(3);
                                    $evidencia=null;
                                }
                            } catch (\Exception $e) {
                                $reincidente = 0;
                                $reinc = 'Não';
                                $codVerificacaoAnterior = null;
                                $numeroGrupoReincidente = null;
                                $numeroItemReincidente = null;
                                $evidencia=null;
                            }
                            $smb_bdf_naoconciliados = DB::table('smb_bdf_naoconciliados')
                                ->select(
                                    'smb_bdf_naoconciliados.*'
                                )
                                ->where('mcu', '=', $registro->mcu)
                                ->where('Divergencia', '<>', 0)
                                ->where('Status', '=', 'Pendente')
                                ->where('Data', '>=', $dtmenos90dias)
                                ->orderBy('Data', 'asc')
                                ->get();
//                              Inicio  se tem registro de pendências SMB_BDF
                            if (!$smb_bdf_naoconciliados->isEmpty()) {
                                $count = $smb_bdf_naoconciliados->count('id');
                                $dtfim = $smb_bdf_naoconciliados->max('Data');

//                              Inicio  se há divergencia
                                if ($count !== 0) {

                                    $smb = $smb_bdf_naoconciliados->sum('SMBDinheiro') + $smb_bdf_naoconciliados->sum('SMBBoleto');
                                    $bdf = $smb_bdf_naoconciliados->sum('BDFDinheiro') + $smb_bdf_naoconciliados->sum('BDFBoleto');
                                    $divergencia = $smb_bdf_naoconciliados->sum('Divergencia');
//                                      Inicio  se divergencia é um valor diferente de zero
                                    if ($divergencia !== 0.0) {

                                        foreach ($smb_bdf_naoconciliados as $smb_bdf_naoconciliado) {
                                            $smblast = $smb_bdf_naoconciliado->SMBDinheiro + $smb_bdf_naoconciliado->SMBBoleto;
                                            $bdflast = $smb_bdf_naoconciliado->BDFDinheiro + $smb_bdf_naoconciliado->BDFBoleto;
                                            $divergencialast = $smb_bdf_naoconciliado->Divergencia;
                                            $total = ($smblast - $bdflast) - $divergencialast;
                                        }
//                                          Inicio Testa ultimo registro se tem compensação
                                        if (($smblast + $bdflast) == ($divergencialast * -1)) {

                                            $avaliacao = 'Conforme';
                                            $oportunidadeAprimoramento = 'Em análise ao sistema SDE – Sistema de Depósito Bancário, na opção "Contabilização", Conciliação SMB x BDF – dados “Não Conciliados”, referente ao período de '.date( 'd/m/Y' , strtotime($dtmenos90dias)) .' a ' . date( 'd/m/Y' , strtotime($dtnow)).', verificou-se a inexistência de divergências.';
                                            $dto = DB::table('itensdeinspecoes')
                                                ->Where([['inspecao_id', '=', $registro->inspecao_id]])
                                                ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
                                                ->select( 'itensdeinspecoes.*'  )
                                                ->first();
                                            $itensdeinspecao = Itensdeinspecao::find($dto->id);
                                            $itensdeinspecao->avaliacao  = $avaliacao;
                                            $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
                                            $itensdeinspecao->evidencia  = $evidencia;
                                            $itensdeinspecao->valorFalta = $valorFalta;
                                            $itensdeinspecao->valorSobra = $valorSobra;
                                            $itensdeinspecao->valorRisco = $valorRisco;
                                            $itensdeinspecao->consequencias = null;
                                            $itensdeinspecao->situacao   = 'Inspecionado';
                                            $itensdeinspecao->pontuado   = 0.00;
                                            $itensdeinspecao->itemQuantificado = 'Não';
                                            $itensdeinspecao->orientacao= null;
                                            $itensdeinspecao->eventosSistema = 'Item avaliado remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
//                                            dd('line -> 818' ,$itensdeinspecao);
//                                            $itensdeinspecao->update();

                                        }
//                                          Final Testa ultimo registro se tem compensação
//                                          Inicio Testa ultimo registro com compensação
                                        else{

                                            $avaliacao = 'Não Conforme';
                                            $oportunidadeAprimoramento = 'Em análise ao sistema SDE – Sistema de Depósito Bancário, na opção "Contabilização", Conciliação SMB x BDF – dados “Não Conciliados”, referente ao período de ' . date( 'd/m/Y' , strtotime($dtmenos90dias)). ' a ' . date( 'd/m/Y' , strtotime($dtnow)) .', constatou-se a existência de divergências entre o valor depositado na conta bancária dos Correios pela Agência e o valor do bloqueto gerado no sistema SARA, no total de R$ ' .number_format($divergencia, 2, ',', '.').' , conforme relacionado a seguir:';

                                            $evidencia = $evidencia ."\n".'Data'."\t".'Divergência'."\t".'Tipo';
                                            foreach ($smb_bdf_naoconciliados as $smb_bdf_naoconciliado){
                                                $evidencia = $evidencia. "\n"
                                                    .date( 'd/m/Y' , strtotime($smb_bdf_naoconciliado->Data))
                                                    ."\t".'R$ '.number_format($smb_bdf_naoconciliado->Divergencia, 2, ',', '.');

                                                if(($smb_bdf_naoconciliado->BDFDinheiro<>0) && ($smb_bdf_naoconciliado->BDFCheque<>0) && ($smb_bdf_naoconciliado->BDFBoleto<>0)){
                                                    $evidencia = $evidencia. "\t".'Dinheiro/Cheque/Boleto';
                                                }
                                                elseif (($smb_bdf_naoconciliado->BDFDinheiro<>0) && ($smb_bdf_naoconciliado->BDFBoleto<>0)){
                                                    $evidencia = $evidencia. "\t".'Dinheiro/Boleto';
                                                }
                                                elseif (($smb_bdf_naoconciliado->BDFDinheiro<>0) && ($smb_bdf_naoconciliado->BDFCheque<>0)){
                                                    $evidencia = $evidencia. "\t".'Dinheiro/Cheque';
                                                }
                                                elseif (($smb_bdf_naoconciliado->BDFBoleto<>0) && ($smb_bdf_naoconciliado->BDFCheque<>0)){
                                                    $evidencia = $evidencia. "\t".'Boleto/Cheque';
                                                }
                                                elseif ($smb_bdf_naoconciliado->BDFDinheiro<>0){
                                                    $evidencia = $evidencia . "\t".'Dinheiro';
                                                }
                                                elseif ($smb_bdf_naoconciliado->BDFBoleto<>0){
                                                    $evidencia = $evidencia. "\t".'Boleto';
                                                }
                                                elseif ($smb_bdf_naoconciliado->BDFCheque<>0){
                                                    $evidencia = $evidencia . "\t".'Cheque';
                                                }
                                                else{
                                                    $evidencia = $evidencia . "\t".'Não identificado';
                                                }
                                            }

                                            if($divergencia > 0.00) {
                                                $total= $divergencia;
                                                $evidencia = $evidencia. "\n".'Em Falta '.$divergencia;
                                                $valorFalta = $total;
                                                $valorSobra = null;
                                                $valorRisco= null;
//                                                o Dpto disse para pontuar como falta.pare

                                            }
                                            else{
                                                $total= $divergencia *-1;
                                                $evidencia = $evidencia."\n".'Em Falta '.$total;
                                                $valorSobra = null;
                                                $valorFalta = $total;
                                                $valorRisco= null;
                                            }
//                                                dd('line 876',  $smb , $bdf ,$divergencia, $total );

                                            $quebra = DB::table('relevancias')
                                                ->select('valor_final' )
                                                ->where('fator_multiplicador', '=', 1 )
                                                ->first();
                                            $quebracaixa = $quebra->valor_final * 0.1;
                                            $fm = DB::table('relevancias')
                                                ->select('fator_multiplicador', 'valor_final', 'valor_inicio' )
                                                ->where('valor_inicio', '<=', $total )
                                                ->orderBy('valor_final' ,'desc')
                                                ->first();
                                            $pontuado = $registro->totalPontos * $fm->fator_multiplicador;

//                                                dd('line 821',  $smb , $bdf ,$divergencia, $total );

                                            $dto = DB::table('itensdeinspecoes')
                                                ->Where([['inspecao_id', '=', $registro->inspecao_id]])
                                                ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
                                                ->select( 'itensdeinspecoes.*'  )
                                                ->first();

                                            $itensdeinspecao = Itensdeinspecao::find($dto->id);
                                            $itensdeinspecao->avaliacao  = $avaliacao;
                                            $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
                                            $itensdeinspecao->evidencia  = $evidencia;
                                            $itensdeinspecao->valorFalta = $valorFalta;
                                            $itensdeinspecao->valorSobra = $valorSobra;
                                            $itensdeinspecao->valorRisco = $valorRisco;
                                            $itensdeinspecao->situacao   = 'Inspecionado';
                                            $itensdeinspecao->pontuado   = $pontuado;
                                            $itensdeinspecao->itemQuantificado = 'Sim';
                                            $itensdeinspecao->orientacao = $registro->orientacao;
                                            $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                            $itensdeinspecao->reincidencia = $reinc;
                                            $itensdeinspecao->codVerificacaoAnterior = $codVerificacaoAnterior;
                                            $itensdeinspecao->numeroGrupoReincidente = $numeroGrupoReincidente;
                                            $itensdeinspecao->numeroItemReincidente = $numeroItemReincidente;
//                                                dd('line 917 -> ',$itensdeinspecao);
                                            $itensdeinspecao->update();
                                        }
//                                          Final Testa ultimo registro com compensação
                                    }
//                                      Final  se divergencia é um valor diferente de zero

//                                      Inicio  se divergencia é um valor igual zero
                                    if ($divergencia == 0.0){

                                        $dataanterior = null;
                                        foreach ($smb_bdf_naoconciliados as $smb_bdf_naoconciliado) {
                                            if ($dataanterior !== null) {
                                                $dataantual = $dataanterior;
                                                $dataantual->addDays(1);
                                                $unidade_enderecos = DB::table('unidade_enderecos')
                                                    ->Where([['mcu', '=', $registro->mcu]])
                                                    ->select( 'unidade_enderecos.*'  )
                                                    ->first();
                                                $feriado = DB::table('feriados')
                                                    ->Where([['data_do_feriado', '=', $dataantual]])
                                                    ->Where([['nome_municipio', '=', $unidade_enderecos->cidade]])
                                                    ->Where([['uf', '=', $unidade_enderecos->uf]])
                                                    ->select( 'feriados.*'  )
                                                    ->first();
                                                if($feriado)  {
                                                    $diasemana = $dataanterior;
                                                    $diasemana->addDays(5);
                                                }
                                                else {
                                                    // dayOfWeek returns a number between 0 (sunday) and 6 (saturday)
                                                    $diasemana = $dataanterior->dayOfWeek;
                                                    if ($diasemana == 5) { //Sexta
                                                        $dataanterior->addDays(3);
                                                    }
                                                    if ($diasemana == 4) { //Quinta
                                                        $dataanterior->addDays(4);
                                                    }
                                                    if ($diasemana <= 3) { // seg a quarta
                                                        $dataanterior->addDays(2);
                                                    }
                                                }


                                                $periodo = CarbonPeriod::create($dataanterior, $smb_bdf_naoconciliado->Data);

                                                if($periodo->count()>1){
                                                    $avaliacao = 'Não Conforme';
                                                    $oportunidadeAprimoramento = 'Em análise ao sistema SDE – Sistema de Depósito Bancário, na opção "Contabilização", Conciliação SMB x BDF – dados “Não Conciliados”,  referente ao período de '. date( 'd/m/Y' , strtotime($dtmenos90dias)). ' a ' . date( 'd/m/Y' , strtotime($dtnow)) .', constatou-se a existência de depositos na conta dos Correios pela Agência com prazo superior D+1. Evento em data anterior à '.date( 'd/m/Y' , strtotime($dataanterior)) ;
                                                    $total = $smb_bdf_naoconciliado->BDFBoleto;
                                                    $valorRisco = $smb_bdf_naoconciliado->BDFBoleto;
                                                    break;
                                                }
                                            }
                                            $dataanterior = new Carbon($smb_bdf_naoconciliado->Data);
                                        }
                                        if($periodo->count()>1){
                                            $quebra = DB::table('relevancias')
                                                ->select('valor_final' )
                                                ->where('fator_multiplicador', '=', 1 )
                                                ->first();
                                            $quebracaixa = $quebra->valor_final * 0.1;

                                            $fm = DB::table('relevancias')
                                                ->select('fator_multiplicador', 'valor_final', 'valor_inicio' )
                                                ->where('valor_inicio', '<=', $total )
                                                ->orderBy('valor_final' ,'desc')
                                                ->first();
                                            $pontuado = $registro->totalPontos * $fm->fator_multiplicador;

                                            $evidencia = $evidencia."\n".'Data'."\t".'Valor do Boleto';
                                            foreach ($smb_bdf_naoconciliados as $smb_bdf_naoconciliado) {
                                                $evidencia = $evidencia . "\n"
                                                    . date('d/m/Y', strtotime($smb_bdf_naoconciliado->Data))
                                                    . "\t" . 'R$ ' . number_format($smb_bdf_naoconciliado->BDFBoleto, 2, ',', '.');
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
                                            $itensdeinspecao->valorFalta = $valorFalta;
                                            $itensdeinspecao->valorSobra = $valorSobra;
                                            $itensdeinspecao->valorRisco = $valorRisco;
                                            $itensdeinspecao->situacao   = 'Inspecionado';
                                            $itensdeinspecao->pontuado   = $pontuado;
                                            $itensdeinspecao->itemQuantificado = 'Sim';
                                            $itensdeinspecao->orientacao = $registro->orientacao;
                                            $itensdeinspecao->consequencias = null;
                                            $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                            $itensdeinspecao->reincidencia = $reinc;
                                            $itensdeinspecao->codVerificacaoAnterior = $codVerificacaoAnterior;
                                            $itensdeinspecao->numeroGrupoReincidente = $numeroGrupoReincidente;
                                            $itensdeinspecao->numeroItemReincidente = $numeroItemReincidente;
//                                                dd('line 1314 ->  valor em risco ',$itensdeinspecao);
                                            $itensdeinspecao->update();
                                        }
                                        else{
                                            $avaliacao = 'Conforme';
                                            $oportunidadeAprimoramento = 'Em análise ao sistema SDE – Sistema de Depósito Bancário, na opção "Contabilização", Conciliação SMB x BDF – dados “Não Conciliados”, referente ao período de '.date( 'd/m/Y' , strtotime($dtmenos90dias)) .' a ' . date( 'd/m/Y' , strtotime($dtnow)).', verificou-se a inexistência de divergências.';
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
                                            $itensdeinspecao->situacao   = 'Inspecionado';
                                            $itensdeinspecao->pontuado   = 0.00;
                                            $itensdeinspecao->itemQuantificado = 'Não';
                                            $itensdeinspecao->orientacao= null;
                                            $itensdeinspecao->eventosSistema = 'Item avaliado remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
//                                                dd('line -> 994' ,$itensdeinspecao);
                                            $itensdeinspecao->update();
                                        }
                                    }
//                                      Final  se divergencia é um valor igual zero
                                }
//                              Final  se há divergencia

                            }
//                              Final  se tem registro de pendências SMB_BDF
//                              Inicio  se Não tem registro de pendências SMB_BDF
                            else{
                                $avaliacao = 'Conforme';
                                $oportunidadeAprimoramento = 'Em análise ao sistema SDE – Sistema de Depósito Bancário, na opção "Contabilização", Conciliação SMB x BDF – dados “Não Conciliados”, referente ao período de '.date( 'd/m/Y' , strtotime($dtmenos90dias)) .' a ' . date( 'd/m/Y' , strtotime($dtnow)).', verificou-se a inexistência de divergências.';

                                $dto = DB::table('itensdeinspecoes')
                                    ->Where([['inspecao_id', '=', $registro->inspecao_id]])
                                    ->Where([['testeVerificacao_id', '=', $registro->testeVerificacao_id]])
                                    ->select( 'itensdeinspecoes.*'  )
                                    ->first();
                                $itensdeinspecao = Itensdeinspecao::find($dto->id);
                                $itensdeinspecao->avaliacao  = $avaliacao;
                                $itensdeinspecao->oportunidadeAprimoramento = $oportunidadeAprimoramento;
                                $itensdeinspecao->evidencia  = $evidencia;
                                $itensdeinspecao->valorFalta = $valorFalta;
                                $itensdeinspecao->valorSobra = $valorSobra;
                                $itensdeinspecao->valorRisco = $valorRisco;
                                $itensdeinspecao->situacao   = 'Inspecionado';
                                $itensdeinspecao->pontuado   = 0.00;
                                $itensdeinspecao->itemQuantificado = 'Não';
                                $itensdeinspecao->consequencias = null;
                                $itensdeinspecao->orientacao= null;
                                $itensdeinspecao->eventosSistema = 'Item avaliado remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
//                                    dd('line -> 1027 não tem registro de pendências SMB_BDF' ,$itensdeinspecao);
                                $itensdeinspecao->update();
                            }
//                              Final  se Não tem registro de pendências SMB_BDF

                        }
//                      Final  do teste SMB_BDF


//                      Inicio do teste PROTER
                        if((($registro->numeroGrupoVerificacao == 202)&&($registro->numeroDoTeste == 1))
                            || (($registro->numeroGrupoVerificacao == 332)&&($registro->numeroDoTeste ==1))
                            || (($registro->numeroGrupoVerificacao == 213)&&($registro->numeroDoTeste ==1))
                            || (($registro->numeroGrupoVerificacao == 230)&&($registro->numeroDoTeste == 5))
                            || (($registro->numeroGrupoVerificacao == 270)&&($registro->numeroDoTeste == 2)))  {
//                                dd($registro);
                            $countproters_peso =0;
                            $countproters_cep =0;
                            $reincidencia = DB::table('snci')
                                ->select('no_inspecao',   'no_grupo',  'no_item','dt_fim_inspecao','dt_inic_inspecao')
                                ->where([['descricao_item',  'like', '%PROTER%']])
                                ->where([['sto', '=', $registro->sto ]])
                                ->orderBy('no_inspecao' ,'desc')
                                ->first();
                            try{
                                if( $reincidencia->no_inspecao > 1) {
                                    $reincidente = 1;
                                    $reinc = 'Sim';
                                    $codVerificacaoAnterior = $reincidencia->no_inspecao;
                                    $numeroGrupoReincidente = $reincidencia->no_grupo;
                                    $numeroItemReincidente = $reincidencia->no_item;
                                    $reincidencia_dt_fim_inspecao = new Carbon($reincidencia->dt_fim_inspecao);
                                    $reincidencia_dt_inic_inspecao = new Carbon($reincidencia->dt_inic_inspecao);
                                    $reincidencia_dt_fim_inspecao->subMonth(3);
                                    $reincidencia_dt_inic_inspecao->subMonth(3);
                                }
                                else{
                                    $reincidente = 0;
                                    $reinc = 'Não';
                                    $codVerificacaoAnterior = null;
                                    $numeroGrupoReincidente = null;
                                    $numeroItemReincidente = null;
                                }
                            }
                            catch (\Exception $e) {
                                $reincidente = 0;
                                $reinc = 'Não';
                                $codVerificacaoAnterior = null;
                                $numeroGrupoReincidente = null;
                                $numeroItemReincidente = null;
                            }
//                          Inicio tem Reincidencia proter
                            if($reincidente == 1) {
                                $proters_con = DB::table('proters')
                                    ->select(
                                        'tipo_de_pendencia'
                                        , 'no_do_objeto'
                                        , 'cep_entrega_sro'
                                        ,'data_da_pendencia'
                                    )
                                    ->where([['mcu', '=', $registro->mcu]])
                                    ->where([['tipo_de_pendencia', '=', 'CON']])
                                    ->where([['data_da_pendencia', '>=', $reincidencia_dt_fim_inspecao ]])
                                    ->where([['data_da_pendencia', '<=', $dtmenos90dias ]])
                                    ->get();
                                $proters_peso = DB::table('proters')
                                    ->select(
                                        'tipo_de_pendencia'
                                        , 'no_do_objeto'
                                        , 'cep_destino'
                                        , 'divergencia_peso'
                                        , 'diferenca_a_recolher'
                                        , 'data_da_pendencia'
                                    )
                                    ->where([['mcu', '=', $registro->mcu]])
                                    ->where([['tipo_de_pendencia', '=', 'DPC']])
                                    ->where([['divergencia_peso', '=', 'S']])
                                    ->where([['data_da_pendencia', '>=', $reincidencia_dt_fim_inspecao ]])
                                    ->where([['data_da_pendencia', '<=', $dtmenos90dias ]])
                                    ->get();


                                $proters_cep = DB::table('proters')
                                    ->select(
                                        'tipo_de_pendencia'
                                        , 'no_do_objeto'
                                        , 'cep_destino'
                                        , 'divergencia_cep'
                                        , 'diferenca_a_recolher'
                                        , 'data_da_pendencia'
                                    )
                                    ->where([['mcu', '=', $registro->mcu]])
                                    ->where([['tipo_de_pendencia', '=', 'DPC']])
                                    ->where([['divergencia_cep', '=', 'S']])
                                    ->where([['data_da_pendencia', '>=', $reincidencia_dt_fim_inspecao ]])
                                    ->where([['data_da_pendencia', '<=', $dtmenos90dias ]])
                                    ->get();
//                                    dd('var -> ', $proters_cep,$registro->mcu, $reincidencia_dt_fim_inspecao,  $dtmenos90dias );

                                if(! $proters_con->isEmpty()){
                                    $countproters_con = $proters_con->count('no_do_objeto');
                                }
                                else{
                                    $countproters_con = 0;
                                }

                                if(! $proters_peso->isEmpty())
                                {
                                    $countproters_peso = $proters_peso->count('no_do_objeto');
                                    $total_proters_peso  = $proters_peso->sum('diferenca_a_recolher');
                                }
                                else
                                {
                                    $total_proters_peso  = 0.00;
                                }

                                if(! $proters_cep->isEmpty())
                                {
                                    $countproters_cep = $proters_cep->count('no_do_objeto');
                                    $total_proters_cep  = $proters_cep->sum('diferenca_a_recolher');
                                }
                                else
                                {
                                    $total_proters_cep  = 0.00;
                                }

                                $total = $total_proters_peso + $total_proters_cep;
                                $countproters = $countproters_con + $countproters_peso +$countproters_cep;


//                                  Inicio se tem pendencia proter com reincidencia
                                if(($countproters_con >= 1) || ($total > 0.00) ){
                                    $pontuado = 0;  //  verificar  declaração no inicio da rotina
                                    if($total > 0.00) {
                                        $quebra = DB::table('relevancias')
                                            ->select('valor_final' )
                                            ->where('fator_multiplicador', '=', 1 )
                                            ->first();
                                        $quebracaixa = $quebra->valor_final * 0.1;

                                        $fm = DB::table('relevancias')
                                            ->select('fator_multiplicador', 'valor_final', 'valor_inicio' )
                                            ->where('valor_inicio', '<=', $total )
                                            ->orderBy('valor_final' ,'desc')
                                            ->first();
                                        $pontuado = $registro->totalPontos * $fm->fator_multiplicador;
                                    }

                                    $avaliacao = 'Não Conforme';
                                    $oportunidadeAprimoramento = 'Em Análise aos dados do Sistema Proter a partir de Jan/2017. Excetuando os ultimos 90 dias da data dessa inspeção, constatou-se a existência de pendências sem regularização há mais de 90 dias conforme relacionado a seguir:';
                                    $evidencia ='';

                                    if(! $proters_con->isEmpty()){
                                        $countproters_con = $proters_con->count('no_do_objeto');
                                        $evidencia = "\n".'Pendencia(s) de Contabilização: '.$countproters_con.' Pendência(s)';
                                        $evidencia = $evidencia = "\n".'Data da Pendência'."\t".'Número do Objeto'."\t".'CEP Entrega';

                                        foreach($proters_con as $proter_con){
                                            $evidencia = $evidencia = "\n".date( 'd/m/Y' , strtotime($proter_con->data_da_pendencia))
                                                ."\t".$proter_con->no_do_objeto
                                                ."\t".$proter_con->cep_entrega_sro;
                                        }
                                    }

                                    if ($total > $quebracaixa ) {

                                        if (!$proters_peso->isEmpty()) {

                                            $countproters_peso = $proters_peso->count('no_do_objeto');
                                            $evidencia1 = "\n" . 'Divergência(s) de Peso: ' . $countproters_peso . ' Pendência(s)';
                                            $evidencia1 = $evidencia1 = "\n" . 'Data da Pendência' . "\t" . 'Número do Objeto' . "\t" . 'Diferença na Tarifação (R$)';
                                            foreach ($proters_peso as $proter_peso) {
                                                $evidencia1 = $evidencia1 = "\n" . date('d/m/Y', strtotime($proter_peso->data_da_pendencia))
                                                    . "\t" . $proter_peso->no_do_objeto
                                                    . "\t" . 'R$ ' . number_format($proter_peso->diferenca_a_recolher, 2, ',', '.');
                                            }
                                        }
                                        if (!$proters_cep->isEmpty()) {

                                            $countproters_cep = $proters_cep->count('no_do_objeto');
                                            $evidencia2 = "\n" . 'Divergência(s) de CEP: ' . $countproters_cep . ' Pendência(s)';
                                            $evidencia2 = $evidencia2 = "\n" . 'Data da Pendência' . "\t" . 'Número do Objeto' . "\t" . 'Diferença na Tarifação (R$)';
                                            foreach ($proters_cep as $proter_cep) {
                                                $evidencia2 = $evidencia2 = "\n" . date('d/m/Y', strtotime($proter_cep->data_da_pendencia))
                                                    . "\t" . $proter_cep->no_do_objeto
                                                    . "\t" . 'R$ ' . number_format($proter_cep->diferenca_a_recolher, 2, ',', '.');
                                            }
                                        }

                                        $evidencia3 = "\n" . 'Total ' . "\t" . 'R$ ' . number_format($total, 2, ',', '.');
                                    }

                                    if ((!empty($evidencia2)) && (!empty($evidencia1))) {
                                        $evidencia = $evidencia . $evidencia1 . $evidencia2 . $evidencia3;
                                    }
                                    elseif (!empty($evidencia1)) {
                                        $evidencia = $evidencia . $evidencia1 . $evidencia3;
                                    }
                                    elseif(!empty($evidencia2)){
                                        $evidencia = $evidencia . $evidencia2 . $evidencia3;
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
                                    $itensdeinspecao->situacao   = 'Inspecionado';
                                    $itensdeinspecao->pontuado   = $pontuado;
                                    $itensdeinspecao->itemQuantificado = 'Sim';
                                    $itensdeinspecao->orientacao = $registro->orientacao;
                                    $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                    $itensdeinspecao->reincidencia = $reinc;
                                    $itensdeinspecao->codVerificacaoAnterior = $codVerificacaoAnterior;
                                    $itensdeinspecao->numeroGrupoReincidente = $numeroGrupoReincidente;
                                    $itensdeinspecao->numeroItemReincidente = $numeroItemReincidente;
                                    $itensdeinspecao->update();
                                }
//                                  Fim se tem pendencia proter com reincidencia
//                                  Inicio Não tem pendencia proter com reincidencia
                                else{
                                    $avaliacao = 'Conforme';
                                    $oportunidadeAprimoramento = 'Em análise aos dados do Sistema Proter, do período de Janeiro/2017 a '. date( 'd/m/Y' , strtotime($dtmenos90dias)).', constatou-se que não havia pendências com mais de 90 dias.';
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
                                    $itensdeinspecao->situacao   = 'Inspecionado';
                                    $itensdeinspecao->pontuado   = 0.00;
                                    $itensdeinspecao->itemQuantificado = 'Não';
                                    $itensdeinspecao->orientacao= null;
                                    $itensdeinspecao->eventosSistema = 'Item avaliado remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                    $itensdeinspecao->update();
                                }
//                                  fim Não tem pendencia proter com reincidencia

                            }
//                          Fim se tem reincidencia

//                          Inicio se não reincidencia
                            else {
                                $proters_con = DB::table('proters')
                                    ->select(
                                        'tipo_de_pendencia'
                                        , 'no_do_objeto'
                                        , 'cep_entrega_sro'
                                        ,'data_da_pendencia'
                                    )
                                    ->where([['mcu', '=', $registro->mcu]])
                                    ->where([['tipo_de_pendencia', '=', 'CON']])
//                                        ->where([['data_da_pendencia', '>=', $reincidencia_dt_fim_inspecao ]])
                                    ->where([['data_da_pendencia', '<=', $dtmenos90dias ]])
                                    ->get();

                                $proters_peso = DB::table('proters')
                                    ->select(
                                        'tipo_de_pendencia'
                                        , 'no_do_objeto'
                                        , 'cep_destino'
                                        , 'divergencia_peso'
                                        , 'diferenca_a_recolher'
                                        , 'data_da_pendencia'
                                    )
                                    ->where([['mcu', '=', $registro->mcu]])
                                    ->where([['tipo_de_pendencia', '=', 'DPC']])
                                    ->where([['divergencia_peso', '=', 'S']])
//                                        ->where([['data_da_pendencia', '>=', $reincidencia_dt_fim_inspecao ]])
                                    ->where([['data_da_pendencia', '<=', $dtmenos90dias ]])
                                    ->get();

                                $proters_cep = DB::table('proters')
                                    ->select(
                                        'tipo_de_pendencia'
                                        , 'no_do_objeto'
                                        , 'cep_destino'
                                        , 'divergencia_cep'
                                        , 'diferenca_a_recolher'
                                        , 'data_da_pendencia'
                                    )
                                    ->where([['mcu', '=', $registro->mcu]])
                                    ->where([['tipo_de_pendencia', '=', 'DPC']])
                                    ->where([['divergencia_cep', '=', 'S']])
//                                        ->where([['data_da_pendencia', '>=', $reincidencia_dt_fim_inspecao ]])
                                    ->where([['data_da_pendencia', '<=', $dtmenos90dias ]])
                                    ->get();

                                if(! $proters_con->isEmpty()){
                                    $countproters_con = $proters_con->count('no_do_objeto');
                                }
                                else{
                                    $countproters_con = 0;
                                }

                                if(! $proters_peso->isEmpty())
                                {
                                    $countproters_peso = $proters_peso->count('no_do_objeto');
                                    $total_proters_peso  = $proters_peso->sum('diferenca_a_recolher');
                                }
                                else
                                {
                                    $total_proters_peso  = 0.00;
                                }

                                if(! $proters_cep->isEmpty())
                                {
                                    $countproters_cep = $proters_cep->count('no_do_objeto');
                                    $total_proters_cep  = $proters_cep->sum('diferenca_a_recolher');
                                }
                                else
                                {
                                    $total_proters_cep  = 0.00;
                                }

                                $total = $total_proters_peso + $total_proters_cep;
                                $countproters = $countproters_con + $countproters_peso +$countproters_cep;
//                                  Inicio se tem pendencia proter sem reincidencia
                                if($countproters >= 1){

                                    if(($countproters_con >= 1) || ($total > 0.00) ) {
                                        $pontuado = 0;  //  verificar  declaração no inicio da rotina
                                        if ($total > 0.00) {
                                            $quebra = DB::table('relevancias')
                                                ->select('valor_final')
                                                ->where('fator_multiplicador', '=', 1)
                                                ->first();
                                            $quebracaixa = $quebra->valor_final * 0.1;

                                            $fm = DB::table('relevancias')
                                                ->select('fator_multiplicador', 'valor_final', 'valor_inicio')
                                                ->where('valor_inicio', '<=', $total)
                                                ->orderBy('valor_final', 'desc')
                                                ->first();
                                            $pontuado = $registro->totalPontos * $fm->fator_multiplicador;
                                        }

                                        $avaliacao = 'Não Conforme';
                                        $oportunidadeAprimoramento = 'Em análise aos dados do Sistema Proter do período de  Janeiro/2017 a ' . date('d/m/Y', strtotime($dtmenos90dias)) . ' constatou-se as seguintes pendências com mais de 90 dias:';
                                        $evidencia = '';
                                        if (!$proters_con->isEmpty()) {

                                            $countproters_con = $proters_con->count('no_do_objeto');
                                            $evidencia = "\n" . 'Pendencia(s) de Contabilização: ' . $countproters_con . ' Pendência(s)';
                                            $evidencia = $evidencia = "\n" . 'Data da Pendência' . "\t" . 'Número do Objeto' . "\t" . 'CEP Entrega';

                                            foreach ($proters_con as $proter_con) {
                                                $evidencia = $evidencia = "\n" . date('d/m/Y', strtotime($proter_con->data_da_pendencia))
                                                    . "\t" . $proter_con->no_do_objeto
                                                    . "\t" . $proter_con->cep_entrega_sro;
                                            }
                                        }

                                        if ($total > $quebracaixa) {

                                            if (!$proters_peso->isEmpty()) {

                                                $countproters_peso = $proters_peso->count('no_do_objeto');
                                                $evidencia1 = "\n" . 'Divergência(s) de Peso: ' . $countproters_peso . ' Pendência(s)';
                                                $evidencia1 = $evidencia1 = "\n" . 'Data da Pendência' . "\t" . 'Número do Objeto' . "\t" . 'Diferença na Tarifação (R$)';
                                                foreach ($proters_peso as $proter_peso) {
                                                    $evidencia1 = $evidencia1 = "\n" . date('d/m/Y', strtotime($proter_peso->data_da_pendencia))
                                                        . "\t" . $proter_peso->no_do_objeto
                                                        . "\t" . 'R$ ' . number_format($proter_peso->diferenca_a_recolher, 2, ',', '.');
                                                }
                                            }
                                            if (!$proters_cep->isEmpty()) {

                                                $countproters_cep = $proters_cep->count('no_do_objeto');
                                                $evidencia2 = "\n" . 'Divergência(s) de CEP: ' . $countproters_cep . ' Pendência(s)';
                                                $evidencia2 = $evidencia2 = "\n" . 'Data da Pendência' . "\t" . 'Número do Objeto' . "\t" . 'Diferença na Tarifação (R$)';
                                                foreach ($proters_cep as $proter_cep) {
                                                    $evidencia2 = $evidencia2 = "\n" . date('d/m/Y', strtotime($proter_cep->data_da_pendencia))
                                                        . "\t" . $proter_cep->no_do_objeto
                                                        . "\t" . 'R$ ' . number_format($proter_cep->diferenca_a_recolher, 2, ',', '.');
                                                }
                                            }

                                            $evidencia3 = "\n" . 'Total ' . "\t" . 'R$ ' . number_format($total, 2, ',', '.');
                                        }

                                        if ((!empty($evidencia2)) && (!empty($evidencia1))) {
                                            $evidencia = $evidencia . $evidencia1 . $evidencia2 . $evidencia3;
                                        }
                                        elseif (!empty($evidencia1)) {
                                            $evidencia = $evidencia . $evidencia1 . $evidencia3;
                                        }
                                        elseif(!empty($evidencia2)){
                                            $evidencia = $evidencia . $evidencia2 . $evidencia3;
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
                                    $itensdeinspecao->situacao   = 'Inspecionado';
                                    $itensdeinspecao->pontuado   = $pontuado;
                                    $itensdeinspecao->itemQuantificado = 'Sim';
                                    $itensdeinspecao->orientacao = $registro->orientacao;
                                    $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                    $itensdeinspecao->reincidencia = $reinc;
                                    $itensdeinspecao->codVerificacaoAnterior = $codVerificacaoAnterior;
                                    $itensdeinspecao->numeroGrupoReincidente = $numeroGrupoReincidente;
                                    $itensdeinspecao->numeroItemReincidente = $numeroItemReincidente;
                                    $itensdeinspecao->update();
                                }
//                                  Fim se tem pendencia proter sem reincidencia
//                                  Inicio conforme
                                else{
                                    $avaliacao = 'Conforme';
                                    $oportunidadeAprimoramento = 'Em análise aos dados do Sistema Proter, do período de Janeiro/2017 a '. date( 'd/m/Y' , strtotime($dtmenos90dias)).', constatou-se que não havia pendências com mais de 90 dias.';
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
                                    $itensdeinspecao->situacao   = 'Inspecionado';
                                    $itensdeinspecao->pontuado   = 0.00;
                                    $itensdeinspecao->itemQuantificado = 'Não';
                                    $itensdeinspecao->orientacao= null;
                                    $itensdeinspecao->eventosSistema = 'Item avaliado remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                    $itensdeinspecao->update();
                                }
//                                  Fim Conforme


                            }
//                          Fim se não  reincidencia
//                          dd( '653',$dtmenos90dias , $registro->mcu, $countproters_con, $countproters_peso,  $countproters_cep );

                        }
//                      Final do teste PROTER

//                      Início do teste WebCont
                        if((($registro->numeroGrupoVerificacao == 230)&&($registro->numeroDoTeste == 4))
                            || (($registro->numeroGrupoVerificacao == 270)&&($registro->numeroDoTeste == 1))) {

                            $reincidencia = DB::table('snci')
                                ->select('no_inspecao',   'no_grupo',  'no_item','dt_fim_inspecao','dt_inic_inspecao')
                                ->where([['descricao_item',  'like', '%3131)?%']])
                                ->where([['sto', '=', $registro->sto ]])
                                ->orderBy('no_inspecao' ,'desc')
                                ->first();
                            try{
                                if( $reincidencia->no_inspecao > 1) {
                                    $reincidente = 1;
                                    $reinc = 'Sim';
                                    $codVerificacaoAnterior = $reincidencia->no_inspecao;
                                    $numeroGrupoReincidente = $reincidencia->no_grupo;
                                    $numeroItemReincidente = $reincidencia->no_item;
                                    $reincidencia_dt_fim_inspecao = new Carbon($reincidencia->dt_fim_inspecao);
                                    $reincidencia_dt_inic_inspecao = new Carbon($reincidencia->dt_inic_inspecao);
                                    $reincidencia_dt_fim_inspecao->subMonth(3);
                                    $reincidencia_dt_inic_inspecao->subMonth(3);
                                }
                            }
                            catch (\Exception $e) {
                                $reincidente = 0;
                            }
                            $mescompetencia = DB::table('debitoempregados')
                                ->select('competencia')
                                ->where([['debitoempregados.competencia', '>=', 1 ]])
                                ->orderBy('competencia' ,'desc')
                                ->first();
                            $competencia = substr($mescompetencia->competencia, 4, 2).'/'.substr($mescompetencia->competencia, 0, 4);
                            if($reincidente == 1) {
                                $debitoempregados = DB::table('debitoempregados')
                                    ->select('data', 'documento', 'historico', 'matricula', 'valor' )
                                    ->where([['debitoempregados.data', '<=', $dtmenos90dias ]])
                                    ->where([['debitoempregados.data', '>=', $reincidencia_dt_fim_inspecao ]])
                                    ->Where([['debitoempregados.sto', '=', $registro->sto ]])
                                    ->get();
                            }
                            else {
                                $debitoempregados = DB::table('debitoempregados')
                                    ->select('data', 'documento', 'historico', 'matricula', 'valor' )
                                    ->where([['debitoempregados.data', '<=', $dtmenos90dias ]])
                                    ->Where([['debitoempregados.sto', '=', $registro->sto ]])
                                    ->get();
                            }

                            if(! $debitoempregados->isEmpty()) {
                                $count = $debitoempregados->count('matricula');
                                $total = $debitoempregados->sum('valor'); // soma a coluna valor da coleção de dados
                                $quebra = DB::table('relevancias')
                                    ->select('valor_final' )
                                    ->where('fator_multiplicador', '=', 1 )
                                    ->first();

                                $quebracaixa = $quebra->valor_final * 0.1;

                                $fm = DB::table('relevancias')
                                    ->select('fator_multiplicador', 'valor_final', 'valor_inicio' )
                                    ->where('valor_inicio', '<=', $total )
                                    ->orderBy('valor_final' ,'desc')
                                    ->first();

                                if(( $count >= 1 ) && ( $total > $quebracaixa ) ) {
                                    $avaliacao = 'Não Conforme';
                                    $pontuado = $registro->totalPontos * $fm->fator_multiplicador;
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
                                    $itensdeinspecao->situacao   = 'Inspecionado';
                                    $itensdeinspecao->pontuado   = $pontuado;
                                    $itensdeinspecao->itemQuantificado = 'Sim';
                                    $itensdeinspecao->orientacao = $registro->orientacao;
                                    $itensdeinspecao->eventosSistema = 'Item avaliado Remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                    $itensdeinspecao->reincidencia = $reinc;
                                    $itensdeinspecao->codVerificacaoAnterior = $codVerificacaoAnterior;
                                    $itensdeinspecao->numeroGrupoReincidente = $numeroGrupoReincidente;
                                    $itensdeinspecao->numeroItemReincidente = $numeroItemReincidente;
                                    $itensdeinspecao->update();
                                }
                            }
                            else {
//                                    dd('nao  temmmmmmm debitos');
                                //se não houve registro para a unidade o resultado é conforme
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
                                $itensdeinspecao->situacao   = 'Inspecionado';
                                $itensdeinspecao->pontuado   = 0.00;
                                $itensdeinspecao->itemQuantificado = 'Não';
                                $itensdeinspecao->orientacao= null;
                                $itensdeinspecao->eventosSistema = 'Item avaliado remotamente por Websgi em '.date( 'd/m/Y' , strtotime($dtnow)).'.';
                                $itensdeinspecao->update();
//                                     dd($competencia);
                            }
                        } // fim doteste webCont
                    }
                } // Fim do teste para uma superintendencias
            }
        }
        ini_set('memory_limit', '128M');
    }

//$this->command->info('Avaliação Realizada com sucesso!');
}
