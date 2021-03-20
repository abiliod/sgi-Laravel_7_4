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
                        } // fim doteste webCont
                    }
                    // Fim do teste WebCont
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
                    // Fim do teste WebCont

                } // Fim do teste para uma superintendencias
            }
        }
        ini_set('memory_limit', '128M');
    }

//$this->command->info('Avaliação Realizada com sucesso!');
}
