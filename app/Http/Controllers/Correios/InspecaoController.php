<?php

namespace App\Http\Controllers\Correios;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

use App\Models\Correios\Inspecao;
use App\Models\Correios\Itensdeinspecao;
use App\Exports\ExportLancamentosSRO;
use App\Models\Correios\ModelsDto\AcessoFinalSemana;
use App\Models\Correios\ModelsDto\PgtoAdicionaisTemp;
use App\Models\Correios\ModelsDto\LancamentosSRO;
use App\Models\Correios\ModelsDto\CompartilhaSenha;


use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use Maatwebsite\Excel\Concerns\FromCollection;



use Carbon\Carbon;
use Carbon\CarbonPeriod;
use mysql_xdevapi\Exception;


class InspecaoController extends Controller
{

    public function exportsro($codigo)
    {
        $exportLancamentosSROs  =  DB::table('lancamentossro')
            ->select('objeto')
            ->where('codigo', '=', $codigo)
            ->where('numeroGrupoVerificacao', '=', 277)
            ->where('numeroDoTeste', '=', 3)
            ->where('estado', '=',  'Pendente')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
        $xml .= '<rootelement>';
        foreach ($exportLancamentosSROs as $exportLancamentosSRO)
        {
            $xml .= "\n\t".'<Dados>';
            $xml .= "\n\t\t".'<Objeto>'.$exportLancamentosSRO->objeto.'</Objeto>';
            $xml .= "\n\t".'</Dados>';
        }
        $xml .= "\n".'</rootelement>';
        $diretorio = "xml/compliance/inspecao/";
        $arquivo = $codigo.'_AmostraNaoEntreguePrimeiraTentativa.xml';
        $arquivo = preg_replace('/\\s\\s+/', ' ', $arquivo);
        $fp = fopen($diretorio.$arquivo, 'w+');
        fwrite($fp, $xml);
        fclose($fp);
        return response()->download($diretorio.$arquivo);
    }

    public function destroy($id) {
        dd("estou destruindo");
    }

    public function deletefiles($img)
    {
        $pos = strpos($img, '&');
        $id = substr($img,$pos+1);
        $inspecao = DB::table('itensdeinspecoes')
        ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
        ->where([['itensdeinspecoes.id', '=', $id ]])
        ->first();
        $diretorio = "img/compliance/inspecao/".$inspecao->codigo."/".$id."/";
        $imagempath = substr($img,0,$pos);
        $imagempath= str_replace('-', '/', $imagempath);

        $pos = strpos( $imagempath, $diretorio );
        if ($pos === false) {
            \Session::flash('mensagem',['msg'=>'Imagem não foi Excluida do Item.'
            ,'class'=>'red white-text']);
        } else {
            unlink($imagempath);
            \Session::flash('mensagem',['msg'=>'A Imagem foi Excluida do Item.'
            ,'class'=>'green white-text']);
        }
        return redirect()-> route('compliance.inspecao.editar',$id);
    }

    public function update(Request $request, $id)
    {
        $now = Carbon::now();
        $now->format('d-m-Y H:i:s');

        $inspecao = DB::table('itensdeinspecoes')
        ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
        ->where([['itensdeinspecoes.id', '=', $id ]])
        ->first();
        $diretorio = "img/compliance/inspecao/".$inspecao->codigo."/".$id.'/';  //id da inspeção e id do item inspecionado
        $dados = $request->all();
        $registro = Itensdeinspecao::find($id);
        if($dados['situacao'] =="cancel")
        {
           return redirect()-> route('compliance.inspecao',$registro->inspecao_id);
        }

        if($dados['avaliacao'] =="Conforme") {
            $registro->avaliacao = trim($dados['avaliacao']);
            $registro->oportunidadeAprimoramento  = trim($dados['oportunidadeAprimoramento']);
            $registro->norma = trim($dados['norma']);
            $registro->situacao = trim($dados['situacao']);
            $registro->itemQuantificado ='Não';
            $registro->reincidencia ='Não';
            $registro->evidencia =null;
            $registro->consequencias = null;
            $registro->valorFalta ='0.00';
            $registro->valorSobra ='0.00';
            $registro->valorRisco ='0.00';
            $registro->codVerificacaoAnterior = null;
            $registro->numeroGrupoReincidente = null;
            $registro->numeroItemReincidente = null;
            $registro->orientacao = null;

            if(($dados['situacao']=="Inspecionado")||($dados['situacao']=="Corroborado")){
                $registro->eventosSistema =
                    "\nInspecionado por: ".Auth::user()->name." em ".$now
                    ."\nSituação: ".trim($dados['situacao'])
                    ."\nAvaliação: ".trim($dados['avaliacao'])
                    ."\nOportunidade de Aprimoramento: ".trim($dados['oportunidadeAprimoramento'])
                    ."\n"
                    ."\n #######################    Registro Anterior   #########################"
                    ."\n"
                    .$registro->eventosSistema;
            }
        }
        elseif($dados['avaliacao'] =="Não Conforme") {

            $registro->avaliacao = trim($dados['avaliacao']);
            $registro->oportunidadeAprimoramento  = trim($dados['oportunidadeAprimoramento']);
            $registro->norma = trim($dados['norma']);
            $registro->situacao = trim($dados['situacao']);
            $registro->evidencia = trim($dados['evidencia']);
            $registro->reincidencia = $dados['reincidencia'];
            $registro->orientacao = trim($dados['orientacao']);
            $registro->consequencias =  trim($dados['consequencias']);
            $registro->itemQuantificado =  trim($dados['itemQuantificado']);
            $registro->itemQuantificado ='Não';
            $registro->valorFalta ='0.00';
            $registro->valorSobra ='0.00';
            $registro->valorRisco ='0.00';
            if(isset($dados['itemQuantificado'])){
               if($dados['itemQuantificado'] =="Sim"){
                    $registro->itemQuantificado  = $dados['itemQuantificado'];
                    $registro->valorFalta  = $dados['valorFalta'];
                    $registro->valorSobra  = $dados['valorSobra'];
                    $registro->valorRisco  = $dados['valorRisco'];
                   //dd(  $registro->itemQuantificado ,   $dados['itemQuantificado']);
                    if(($dados['valorFalta']=="") && ($dados['valorSobra']=="") &&($dados['valorRisco']=="")) {
                        \Session::flash('mensagem',['msg'=>'Informe ao menos um valor quantificado ausente.'
                        ,'class'=>'red white-text']);
                        return back()->withInput();
                    }
               }
          }

          if(isset($dados['reincidencia'])) {
                if($dados['reincidencia'] =="Sim") {
                    $registro->reincidencia  = trim($dados['reincidencia']);
                    if(($dados['codVerificacaoAnterior']=="") || ($dados['numeroGrupoReincidente']=="")
                        || ($dados['numeroItemReincidente']=="")) {
                        \Session::flash('mensagem',['msg'=>'Informação de reincidência está pendente ou incompleta.'
                        ,'class'=>'red white-text']);
                        return back()->withInput();
                    }
                    $registro->codVerificacaoAnterior  = trim($dados['codVerificacaoAnterior']);
                    $registro->numeroGrupoReincidente  = trim($dados['numeroGrupoReincidente']);
                    $registro->numeroItemReincidente  = trim($dados['numeroItemReincidente']);
                }
          }

          if($request->hasfile('imagem')) {
                request()->validate([
                    'imagem' => 'required',
                    'imagem.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);

                if ($image = $request->file('imagem')) {
                    foreach ($image as $files) {
                        $destinationPath = $diretorio; // upload path
                        $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
                        $files->move($destinationPath, $profileImage);
                        $imagens[]['image'] = "$profileImage";
                    }
                }
                $registro->diretorio=$diretorio;
                $registro->imagem = $registro->imagem . json_encode($imagens);
          }

          if(($dados['situacao']=="Inspecionado")
            ||($dados['situacao']=="Corroborado")) {
              $registro->eventosSistema =
                  $dados['situacao']." por: ".Auth::user()->name." em ".$now
                  ."\nSituação ".trim($dados['situacao'])
                  ."\nAvaliação: ".trim($dados['avaliacao'])
                  ."\nOportunidade de Aprimoramento: ".trim($dados['oportunidadeAprimoramento'])
                  ."\nEvidencias: ".trim($dados['evidencia'])
                  ."\nNorma: ".trim($dados['norma'])
                  ."\nConsequencias: ".trim($dados['consequencias'])
                  ."\nValor Falta: ".trim($dados['valorFalta'])
                  ."\nValor Sobra: ".trim($dados['valorSobra'])
                  ."\nValor Risco: ".trim($dados['valorRisco'])
                  ."\nÉ Reincidência: ".trim($dados['reincidencia'])
                  ."\nCódigo da Verificação Anterior: ".trim($dados['codVerificacaoAnterior'])
                  ."\nGrupo Reincidente: ".trim($dados['numeroGrupoReincidente'])
                  ."\nItemReincidente:  ".trim($dados['numeroItemReincidente'])
                  ."\nOrientações: ".trim($dados['orientacao'])
                  ."\n"
                  ."\n #######################    Registro Anterior   #########################"
                  ."\n"
                  .$registro->eventosSistema;
          }

        }

        else if($dados['avaliacao'] =="Não Verificado") {

            $registro->avaliacao = trim($dados['avaliacao']);
            $registro->oportunidadeAprimoramento  = trim($dados['oportunidadeAprimoramento']);
            $registro->situacao = trim($dados['situacao']);
            $registro->consequencias = null;
            $registro->evidencia =null;
            if(isset($dados['norma'])) $registro->norma  = trim($dados['norma']);
            $registro->itemQuantificado ='Não';
            $registro->valorFalta ='0.00';
            $registro->valorSobra ='0.00';
            $registro->valorRisco ='0.00';
            $registro->reincidencia ='Não';
            $registro->codVerificacaoAnterior = null;
            $registro->numeroGrupoReincidente = null;
            $registro->numeroItemReincidente = null;
            $registro->orientacao = null;

            if(($dados['situacao']=="Inspecionado")
                ||($dados['situacao']=="Corroborado")) {
                    $registro->eventosSistema=
                        "\nInspecionado  ou Corroborado por: ".Auth::user()->name." em ".$now
                        ." ".trim($dados['situacao'])
                        ." ".trim($dados['avaliacao'])
                        ." ".trim($dados['oportunidadeAprimoramento'])
                        ."\n"
                        ."\n #######################    Registro Anterior   #########################"
                        ."\n"
                        .$registro->eventosSistema;
                }

        }else {  ////Não Executa Tarefa

            $registro->avaliacao = trim($dados['avaliacao']);
            $registro->oportunidadeAprimoramento  = trim($dados['oportunidadeAprimoramento']);
            $registro->situacao = trim($dados['situacao']);
            $registro->consequencias = null;
            $registro->evidencia = null;
            if(isset($dados['norma'])) $registro->norma  = trim($dados['norma']);
            $registro->itemQuantificado ='Não';
            $registro->valorFalta ='0.00';
            $registro->valorSobra ='0.00';
            $registro->valorRisco ='0.00';
            $registro->reincidencia ='Não';
            $registro->codVerificacaoAnterior =null;
            $registro->numeroGrupoReincidente =null;
            $registro->numeroItemReincidente =null;
            $registro->orientacao = null;

            if(($dados['situacao']=="Inspecionado")
            ||($dados['situacao']=="Corroborado")) {
            $registro->eventosSistema=
                $dados['situacao']." por: ".Auth::user()->name." em ".$now
                ." ".trim($dados['situacao'])
                ." ".trim($dados['avaliacao'])
                ." ".trim($dados['oportunidadeAprimoramento'])
                ."\n"
                ."\n #######################    Registro Anterior   #########################"
                ."\n"
                .$registro->eventosSistema;
            }

        }
        $registro->update();

        \Session::flash('mensagem',['msg'=>'Registro inspecionado com sucesso!'
        ,'class'=>'green white-text']);

        if($dados['situacao'] =="Em Inspeção")
        {
           return redirect()-> route('compliance.inspecao.editar',$registro->id);
        }

        return redirect()-> route('compliance.inspecao',$inspecao->inspecao_id);
    }

    public function editsro(Request $request, $id)
    {
         $dados = $request->all();
         //dd($dados);
         $registro = LancamentosSRO::find($id);

         $inspecao  =  DB::table('inspecoes')
            ->where('codigo', '=', $registro->codigo)
            ->first();

        if(( $request['numeroGrupoVerificacao']==277) && ($request['numeroDoTeste']==2))
        {
            $registro->estado = $dados['estado'];
            $registro->falhaDetectada = $dados['falhaDetectada'];
        }
        elseif (( $dados['numeroGrupoVerificacao']==277) && ($dados['numeroDoTeste']==3))
        {
            $registro->estado = $dados['estado'];
            $registro->falhaDetectada = $dados['falhaDetectada'];
            $registro->enderecoPostagem = $dados['enderecoPostagem'];
            $registro->localBaixa1tentativa = $dados['localBaixa1tentativa'];
        }

        $registro->update();

        $res  =  DB::table('lancamentossro')
            ->where('codigo', '=', $registro->codigo)
            ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
            ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
            //->where('estado', '=',  'Pendente')
            ->get();

        $pend = 0;
        $aval = 0;
        foreach ($res as $register) {
            if ($register->estado == 'Pendente') {
                $pend++;
            } else {
                $aval++;
            }
        }
        $mostra = $pend + $aval;

        //dd('pendencia '.$pend, 'mostra'.$mostra, 'avaliados'. $aval);

        $id =  $dados['item'];

        if ($aval < $mostra)
        {
            //return redirect()-> route('compliance.inspecao.editar',$id);
            // dd('pendencia '.$pend,'amostra '. $mostra);
            $res = DB::table('lancamentossro')
                ->where('codigo', '=', $registro->codigo)
                ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                ->where('estado', '=', 'Pendente')
                ->get();

            $registro = DB::table('itensdeinspecoes')
                ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
                ->join('unidades', 'itensdeinspecoes.unidade_id', '=', 'unidades.id')
                ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
                ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
                ->select('itensdeinspecoes.*','inspecoes.*','unidades.*','testesdeverificacao.*','gruposdeverificacao.*')
                ->where([['itensdeinspecoes.id', '=', $id ]])
                ->first();
           //  dd('avaliados '.$aval,'amostra '. $mostra);
            return view('compliance.inspecao.index_sro', compact
            (
                'registro'
                , 'id'
                , 'res'
            ));
        }
        if ($aval == $mostra)
        {
            return redirect()-> route('compliance.inspecao.editar',$id);
        }
    }

    public function edit($id)
    {
        $dtmenos90dias = new Carbon();
        $dtmenos90dias->subDays(90);
        $dtmes3mesesatras = new Carbon();
        $dtmes3mesesatras->subMonth(2);
        //  $now = Carbon::now();
        // $now->format('Y-m-d');
        $periodo = new CarbonPeriod();

        $registro = DB::table('itensdeinspecoes')
            ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
            ->join('unidades', 'itensdeinspecoes.unidade_id', '=', 'unidades.id')
            ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
            ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
            ->select('itensdeinspecoes.*','inspecoes.*','unidades.*','testesdeverificacao.*','gruposdeverificacao.*')
            ->where([['itensdeinspecoes.id', '=', $id ]])
            ->first();

            if(($registro->numeroGrupoVerificacao == 270)&&($registro->numeroDoTeste == 1)) //revisado em 03/01/2021
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

               $competencia= substr($mescompetencia->competencia, 4, 2).'/'.substr($mescompetencia->competencia, 0, 4);
                return view('compliance.inspecao.editar',compact(
                    'registro'
                    ,'id'
                    , 'debitoempregados'
                    ,'competencia'
                    ,'total'
                   ,'count'
                ));
            }

            if(($registro->numeroGrupoVerificacao == 270)&&($registro->numeroDoTeste == 2)) //revisado em 03/01/2021
            {
                $countproters_con =0;
                $countproters_peso =0;
                $countproters_cep =0;
                $total_proters_cep  = 0.00;
                $total_proters_peso  = 0.00;


                $proters_con = DB::table('proters')
                    ->select(
                        'tipo_de_pendencia'
                      , 'data_da_postagem'
                      , 'no_do_objeto'
                      , 'qtd_duplicidades'
                      , 'cep_entrega_sro'
                      , 'ultima_manifestacao'
                      , 'nome_da_unidade'
                     )
                     ->where([['mcu', '=', $registro->mcu]])
                     ->where([['tipo_de_pendencia', '=', 'CON']])
                     ->where([['data_da_postagem', '<=', $dtmenos90dias ]])
                 ->get();

                if(! $proters_con->isEmpty())
                    $countproters_con = $proters_con->count('no_do_objeto');
                $proters_peso = DB::table('proters')
                     ->select(
                        'tipo_de_pendencia'
                      , 'data_da_postagem'
                      , 'no_do_objeto'
                      , 'cep_destino'
                      , 'ultima_manifestacao'
                      , 'divergencia_peso'
                      , 'divergencia_cep'
                      , 'diferenca_a_recolher'
                      , 'nome_da_unidade'
                     )
                     ->where([['mcu', '=', $registro->mcu]])
                     ->where([['tipo_de_pendencia', '=', 'DPC']])
                     ->where([['divergencia_peso', '=', 'S']])
                     ->where([['data_da_postagem', '<=', $dtmenos90dias ]])
                ->get();

                if(! $proters_peso->isEmpty())
                    $countproters_peso = $proters_peso->count('no_do_objeto');

                $proters_cep = DB::table('proters')
                     ->select(
                        'tipo_de_pendencia'
                        , 'data_da_postagem'
                        , 'no_do_objeto'
                        , 'cep_destino'
                        , 'ultima_manifestacao'
                        , 'divergencia_peso'
                        , 'divergencia_cep'
                        , 'diferenca_a_recolher'
                        , 'nome_da_unidade'
                     )
                     ->where([['mcu', '=', $registro->mcu]])
                     ->where([['tipo_de_pendencia', '=', 'DPC']])
                     ->where([['divergencia_cep', '=', 'S']])
                     ->where([['data_da_postagem', '<=', $dtmenos90dias ]])
                ->get();

                if(! $proters_cep->isEmpty())
                {
                  //  dd('tem registro');
                    $total_proters_cep  = $proters_cep->sum('diferenca_a_recolher');
                    $total=$total_proters_cep;
                }
                else
                {
                 //   dd('não há registro');
                    $total_proters_cep  = 0.00;
                    $total=$total_proters_cep;
                }

                if(! $proters_peso->isEmpty())
                {
                    $total_proters_peso  = $proters_peso->sum('diferenca_a_recolher');
                    $total=$total_proters_peso;
                }
                else
                {

                    $total_proters_peso  = 0.00;
                    $total=$total_proters_peso;
                }

                if(( $total_proters_cep  ) && ( $total_proters_peso >= 1 ))
                {
                    $total = $total_proters_peso + $total_proters_cep;
                }
                return view('compliance.inspecao.editar',compact('registro','id'
                    , 'proters_con'
                    , 'proters_cep'
                    , 'proters_peso'
                    , 'total_proters_cep'
                    , 'total_proters_peso'
                    ,'countproters_con'
                    ,'countproters_cep'
                    ,'countproters_peso'
                    ,'dtmenos90dias'
                    , 'total'
                ));
            }

            if(($registro->numeroGrupoVerificacao == 270)&&($registro->numeroDoTeste== 3))
            {
                $dtnow = new Carbon();
                $dtmenos90dias = new Carbon();
                $dtmenos90dias->subDays(90);
                $total=0.00;
                $smb_bdf_naoconciliados = DB::table('smb_bdf_naoconciliados')
                    ->select(
                        'smb_bdf_naoconciliados.*'
                    )
                    ->where([['smb_bdf_naoconciliados.Agencia', '=', $registro->mcu]])
                    ->where([['smb_bdf_naoconciliados.Divergencia', '!=', 0]])
                    ->where([['smb_bdf_naoconciliados.Status', '=', 'Pendente']])
                    ->where([['smb_bdf_naoconciliados.Data', '>=', $dtmenos90dias ]])
                    ->orderBy('Data' ,'asc')
                ->get();

                $periodo = DB::table('smb_bdf_naoconciliados')
                    ->select(
                   'smb_bdf_naoconciliados.*'
                    )
                    ->where([['smb_bdf_naoconciliados.Data', '>=', $dtmenos90dias ]])
                ->get();

                $dtini = $periodo->min('Data');
                $dtfim = $periodo->max('Data');

                $total  = $smb_bdf_naoconciliados->sum('Divergencia'); // soma a coluna valor da coleção de dados
                return view('compliance.inspecao.editar',compact(
                    'registro'
                    ,'id'
                    ,'smb_bdf_naoconciliados'
                    ,'total'
                    ,'dtini'
                    ,'dtfim'
                    ,'dtnow'
                    ));
            }

            if(($registro->numeroGrupoVerificacao==270)&&($registro->numeroDoTeste==4)) //revisadoem 03/01/2020
            {
                $total=0.00;
                $dtnow = new Carbon();
                $dtmenos30dias = new Carbon();
                $dtmenos60dias = new Carbon();
                $dtmenos90dias = new Carbon();
                $dtmenos120dias = new Carbon();
                $dtmenos30dias->subDays(30);
                $dtmenos60dias->subDays(60);
                $dtmenos90dias->subDays(90);
                $dtmenos120dias->subDays(120);
                $ocorrencias=0;

                $sl02bdfs30 = DB::table('sl02bdfs')
                    ->select('sl02bdfs.*')
                    ->where('cod_orgao', '=', $registro->sto)
                    ->where('dt_movimento', '<=', $dtmenos30dias)
                    ->where('diferenca', '>=', 1)
                    ->orderBy('dt_movimento', 'desc')
                ->get();

                $sl02bdfs60 = DB::table('sl02bdfs')
                    ->select('sl02bdfs.*')
                    ->where('cod_orgao', '=', $registro->sto)
                    ->where('dt_movimento', '>', $dtmenos30dias)
                    ->where('dt_movimento', '<=', $dtmenos60dias)
                    ->where('diferenca', '>=', 1)
                    ->orderBy('dt_movimento', 'desc')
                    ->get();

                $sl02bdfs90 = DB::table('sl02bdfs')
                    ->select('sl02bdfs.*')
                    ->where('cod_orgao', '=', $registro->sto)
                    ->where('dt_movimento', '>', $dtmenos60dias)
                    ->where('dt_movimento', '<=', $dtmenos90dias)
                    ->where('diferenca', '>=', 1)
                    ->orderBy('dt_movimento', 'desc')
                    ->get();

                $sl02bdfs120 = DB::table('sl02bdfs')
                    ->select('sl02bdfs.*')
                    ->where('cod_orgao', '=', $registro->sto)
                    ->where('dt_movimento', '>', $dtmenos90dias)
                    ->where('dt_movimento', '<=', $dtmenos120dias)
                    ->where('diferenca', '>=', 1)
                    ->orderBy('dt_movimento', 'desc')
                ->get();

                if(! $sl02bdfs30->isEmpty())
                {
                    $acumulados30  = $sl02bdfs30->sum('diferenca'); // soma a coluna valor da coleção de dados
                    $ocorrencias30  = $sl02bdfs30->count('diferenca');
                    if($ocorrencias30==0)
                    {
                        $media30=0;
                    }
                    else
                    {
                        $media30 =   $acumulados30/$ocorrencias30 ;
                    }
                    $limite = $sl02bdfs30->max('limite');
                    if ($limite==0) $limite=1;
                    $porcentagem30 = $media30 / $limite;
                }
                else
                {
                    $acumulados30=0;
                    $ocorrencias30=0;
                    $media30=0;
                    $porcentagem30=0;
                }
                if(! $sl02bdfs60->isEmpty())
                {
                    $acumulados60  = $sl02bdfs60->sum('diferenca'); // soma a coluna valor da coleção de dados
                    $ocorrencias60  = $sl02bdfs60->count('diferenca');
                    if($ocorrencias60==0)
                    {
                        $media60=0;
                    }
                    else
                    {
                        $media60 =   $acumulados60/$ocorrencias60 ;
                    }
                    $limite = $sl02bdfs60->max('limite');
                    if ($limite==0) $limite=1;
                    $porcentagem60 = $media60 / $limite;
                }
                else
                {
                    $acumulados60=0;
                    $ocorrencias60=0;
                    $media60=0;
                    $porcentagem60=0;
                }

                if(! $sl02bdfs90->isEmpty())
                {
                    $acumulados90  = $sl02bdfs90->sum('diferenca'); // soma a coluna valor da coleção de dados
                    $ocorrencias90  = $sl02bdfs90->count('diferenca');
                    if($ocorrencias90==0)
                    {
                        $media90=0;
                    }
                    else
                    {
                        $media90 =   $acumulados90/$ocorrencias90 ;
                    }
                    $limite = $sl02bdfs90->max('limite');
                    if ($limite==0) $limite=1;
                    $porcentagem90 = $media90 / $limite;
                }
                else
                {
                    $acumulados90=0;
                    $ocorrencias90=0;
                    $media90=0;
                    $porcentagem90=0;
                }

                if(! $sl02bdfs120->isEmpty())
                {
                    $acumulados120  = $sl02bdfs120->sum('diferenca'); // soma a coluna valor da coleção de dados
                    $ocorrencias120  = $sl02bdfs120->count('diferenca');
                    if($ocorrencias120==0)
                    {
                        $media120=0;
                    }
                    else
                    {
                        $media120 =   $acumulados120/$ocorrencias120 ;
                    }
                    $limite = $sl02bdfs120->max('limite');
                    if ($limite==0) $limite=1;
                    $porcentagem120 = $media120 / $limite;
                }
                else
                {
                    $acumulados120=0;
                    $ocorrencias120=0;
                    $media120=0;
                    $porcentagem120=0;
                }

                $total  =    $acumulados30+$acumulados60+$acumulados90+$acumulados120;
                $ocorrencias = $ocorrencias30+$ocorrencias60+$ocorrencias90+$ocorrencias120;

                if( $ocorrencias >= 1 )
                {
                    $mediaocorrencias = (($ocorrencias/120)*22);
                    $mediaocorrencias = number_format($mediaocorrencias, 2, ',', '.');
                }
                else
                {
                    $mediaocorrencias = 0;
                }

//             Considerando-se o contido no MANAFI 3/2, Em situações emergenciais,
//             devidamente justificadas, as unidades sem Banco Postal poderão pernoitar
//             com valores superiores ao limite estabelecido, desde que a média de dias
//             ultrapassados não extrapolem o percentual de 20% ao mês."", considerar
//             como NÃO CONFORME o item quando em um período de 30 dias foram identificados
//             7 ou mais dias em que o ""Limite de Saldo"" tenha sido ultrapassado.
//             O período a ser verificado deverá ser a partir do dia anterior da verificação (D-1),
//             retroagindo 90 dias. Caso o sistema não apresente dados do dia anterior,
//             avaliar os últimos 90 dias, de forma retroativa, a partir da data que estiver disponível no sistema.

                return view('compliance.inspecao.editar',compact(
                    'registro'
                    ,'id'
                    ,'total'
                    ,'dtnow'
                    ,'sl02bdfs30','acumulados30','ocorrencias30', 'media30', 'porcentagem30'
                    ,'sl02bdfs60','acumulados60','ocorrencias60', 'media60', 'porcentagem60'
                    ,'sl02bdfs90','acumulados90','ocorrencias90', 'media90', 'porcentagem90'
                    ,'sl02bdfs120','acumulados120','ocorrencias120', 'media120', 'porcentagem120'
                    ,'dtmenos30dias',  'dtmenos60dias' , 'dtmenos90dias' ,'dtmenos120dias'
                    ,'ocorrencias','mediaocorrencias'
                ));
            }

            if(($registro->numeroGrupoVerificacao==271)&&($registro->numeroDoTeste==1))
            {
                $dtmenos90dias = new Carbon();
                $dtmenos90dias->subDays(90);


                $resp_definidas = DB::table('resp_definidas')
                    ->select('mcu','unidade','data_pagamento', 'objeto', 'nu_sei', 'data', 'situacao', 'valor_da_indenizacao' )
                    ->where('mcu', '=', $registro->mcu)
                    ->where('situacao', '!=', 'CONCLUIDO')
                    ->where('data', '<=',  $dtmenos90dias)
                ->get();

                if(! $resp_definidas->isEmpty())
                {
                    $count = $resp_definidas->count('objeto');
                    $total = $resp_definidas->sum('valor_da_indenizacao');
                    $dtmax = $dtmenos90dias;
                    $dtmin = $resp_definidas->min('data');
                }
                else
                {
                    $total = 0.00;
                    $dtmax = $dtmenos90dias;
                    $dtmin = new Carbon();
                    $count = 0;
                }


                return view('compliance.inspecao.editar',compact
                (
                  'registro'
                , 'id'
                , 'total'
                , 'resp_definidas'
                , 'dtmax'
                , 'dtmin'
                , 'count'
                ));
            }

            if(( $registro->numeroGrupoVerificacao == 272 ) && ( $registro->numeroDoTeste == 2 ))
            {
                $dtmenos12meses = Carbon::now();
                $dtmenos12meses = $dtmenos12meses->subMonth(12);
                $now = Carbon::now();
                $now->format('Y-m-d');

                $rowAberturaFinalSemana=0;
                $tempoDesarme='';
                $tempoDePermanencia='';
                $row =0;
                $acessosEmFeriados='';
                $dtmax = '';
                $dataultimoevento='';
                $aviso='';
                $diferencaAbertura ='';
                $tempoAbertura ='';//armazena tempo de abertura menor que o previsto
                $riscoAbertura ='';//armazena risco abertura fora do horário de atendimento
                $rowtempoAbertura=0;
                $rowriscoAbertura=0;
                $rowtempoAberturaAntecipada=0;
                $rowtempoAberturaPosExpediente=0;
                $tempoAberturaPosExpediente='';
                $tempoAberturaAntecipada='';
                $naoMonitorado='';
                $total = 0;
                $acessos_final_semana ='';

                $alarmesFinalSemana = DB::table('alarmes')
                    ->select('alarmes.*')
                    ->where('mcu', '=', $registro->mcu)
                    ->where('data', '>', $dtmenos12meses)
                    ->whereIn('diaSemana', [0, 6])
                    ->orderBy('data' ,'asc')
                    ->orderBy('hora' ,'asc')
                ->get();

                if(! $alarmesFinalSemana->isEmpty())
                {
                    $count_alarmesFinalSemana  = $alarmesFinalSemana->count('armedesarme');
                    DB::table('acessos_final_semana')
                        ->where('mcu', '=', $registro->mcu)
                    ->delete();
                    foreach ($alarmesFinalSemana  as $tabela)
                    {
                        if($tabela->armedesarme == 'Desarme' )
                        {
                            $tempoDesarme = (substr($tabela->hora,0,2)*60)+substr($tabela->hora,3,2);
                            $horaDesarme = $tabela->hora;
                            $dataDesarme = $tabela->data;
                            $eventodesarme = $tabela->armedesarme;
                            $diaEvento = $tabela->diaSemana;
                        }
                        else
                        {
                            if (( isset($diaEvento) ) &&( $diaEvento == $tabela->diaSemana ) && ( $tabela->armedesarme == 'Arme' ))
                            {
                                $horaFechamento = $tabela->hora;
                                $tempoArme = (substr($tabela->hora, 0, 2) * 60) + substr($tabela->hora, 3, 2);
                                $tempoDePermanencia = $tempoArme - $tempoDesarme;
                                $h = intdiv($tempoDePermanencia, 60);
                                $m = $tempoDePermanencia % 60;


                                if ($tabela->diaSemana == 6)
                                {
                                    $diasemana = 'Sábado';
                                }
                                else
                                {
                                    $diasemana = 'Domingo';
                                }
                                $peranencia = ($h < 10 ? '0' . $h : $h) . ':' . ($m < 10 ? '0' . $m : $m);
                                $dataDesarme = date("d/m/Y", strtotime($dataDesarme));

                                $acessoFinalSemana = new AcessoFinalSemana();
                                    $acessoFinalSemana->mcu = $registro->mcu;
                                    $acessoFinalSemana->evAbertura = $eventodesarme;
                                    $acessoFinalSemana->evDataAbertura = $dataDesarme;
                                    $acessoFinalSemana->evHoraAbertura = $horaDesarme;
                                    $acessoFinalSemana->evFechamento = $tabela->armedesarme;
                                    $acessoFinalSemana->evHoraFechamento = $horaFechamento;
                                    $acessoFinalSemana->diaSemana = $diasemana;
                                    $acessoFinalSemana->tempoPermanencia = $peranencia;

                                if ($registro->trabalha_sabado =='Não')
                                {
                                    $acessoFinalSemana->save();
                                    $rowAberturaFinalSemana++;
                                }
                                elseif ($registro->trabalha_domingo =='Não')
                                {
                                    $acessoFinalSemana->save();
                                    $rowAberturaFinalSemana++;
                                }
                            }
                        }
                    }
                    if ( $rowAberturaFinalSemana >= 1 )
                    {
                        $acessos_final_semana = DB::table('acessos_final_semana')
                            ->where('mcu',  '=', $registro->mcu)
                            ->select(
                                'acessos_final_semana.*'
                            )
                            ->get();
                    }
                    else { $acessos_final_semana = '';}
                }
                else
                {
                    $count_alarmesFinalSemana  = 0;
                }
        /////////////////   Finais de semana  /////////////////////////////

        /////////////////   Feriados /////////////////////////////
                $feriadoporUnidades = DB::table('unidades')
                    ->join('unidade_enderecos', 'unidades.mcu', '=', 'unidade_enderecos.mcu')
                    ->join('feriados', 'unidade_enderecos.cidade', '=', 'feriados.nome_municipio')
                    ->select(
                              'feriados.*'
                    )
                    ->where([['unidades.mcu', '=', $registro->mcu]])
                    ->where('data_do_feriado', '>=', $dtmenos12meses)
                ->get();

                $eventos = DB::table('alarmes')
                    ->select('alarmes.*')
                    ->where('mcu', '=', $registro->mcu)
                    ->where('armedesarme', '=', 'Desarme')
                    ->orderBy('data' ,'asc')
                    ->orderBy('hora' ,'asc')
                ->get();

                foreach ($feriadoporUnidades  as $feriadoporUnidade){
                        foreach ($eventos  as $evento){
                            if($feriadoporUnidade->data_do_feriado   ==  $evento->data){

                                $acessosEmFeriados = ([
                                    $row => ['Acesso' => \Carbon\Carbon::parse($evento->data)->format('d/m/Y'), 'hora' => $evento->hora],
                                    ]);

                            }
                            $row++;

                        }
                }

   /////////////////   Feriados /////////////////////////////

   /////////////////   Acessos fora do Padrão /////////////////////////////

                $eventos = DB::table('alarmes')
                    ->select('alarmes.*')
                    ->where('mcu', '=', $registro->mcu)
                    ->where('data', '>', $dtmenos12meses)
                    ->orderBy('data' ,'asc')
                    ->orderBy('hora' ,'asc')
                ->get();

                if(! $eventos->isEmpty())
                {
                    $dtmax = $eventos->max('data');
                    if((isset($registro->inicio_expediente)) && (!empty($registro->inicio_expediente))
                        ||(isset($registro->final_expediente)) && (!empty($registro->final_expediente)) )
                    {
                        $minutosinicioExpediente = (substr($registro->inicio_expediente,0,2)*60)+substr($registro->inicio_expediente,3,2);
                        $minutosfinalExpediente = (substr($registro->final_expediente,0,2)*60)+substr($registro->final_expediente,3,2);
                    }
                    else
                    {
                        \Session::flash('mensagem',['msg'=> auth()->user()->name.', Base de Dados da Unidade não atualizada.
                         Atualize os horários de funcionamento.'
                            ,'class'=>'red white-text']);
                        return redirect()->route('compliance.unidades.editar',$registro->unidade_id);
                    }

                    foreach ($eventos  as $evento)
                    {
                        $eventominutos = (substr($evento->hora,0,2)*60)+substr($evento->hora,3,2);
                        if ($evento->armedesarme =='Desarme' )
                        {
                            if (($eventominutos < ($minutosinicioExpediente-90)))
                            {
                                $diferencaAbertura =  $minutosinicioExpediente - $eventominutos;

                                if($diferencaAbertura <0)
                                {
                                    $diferencaAbertura = $diferencaAbertura *-1;
                                }
                                $h = intdiv ($diferencaAbertura,60);
                                if ($h<10)
                                {
                                    $h='0'.$h;
                                }
                                $m = $diferencaAbertura % 60;
                                if ($m<10)
                                {
                                    $m='0'.$m;
                                }
                                $diferencaAbertura = $h.':'.$m.':'.substr($evento->hora,6,2);
                                $tempoAbertura = ([
                                    $rowtempoAbertura => ['dataInicioExpediente' => $evento->data,
                                    'InicioExpediente' => $registro->inicio_expediente,
                                    'HorárioDeAbertura' => $evento->hora,
                                    'DiferencaTempoDeAbertura' => $diferencaAbertura],
                                ]);
                                $rowtempoAbertura++;
                            }
                            ///////////////////////   TEMPO DE ABERTURA   //////////////////////////

                            ///////////////////////   risco  DE ABERTURA   //////////////////////////
                            if (($eventominutos < ($minutosinicioExpediente-120))){
                                $diferencaAbertura =  $minutosinicioExpediente - $eventominutos;
                                $h = intdiv ($diferencaAbertura,60);
                                if ($h<10){
                                    $h='0'.$h;
                                }
                                $m = $diferencaAbertura % 60;
                                if ($m<10){
                                    $m='0'.$m;
                                }
                                $diferencaAbertura = $h.':'.$m.':'.substr($evento->hora,6,2);
                                $tempoAberturaAntecipada = ([
                                    $rowtempoAbertura => ['dataInicioExpediente' => $evento->data,
                                    'InicioExpediente' => $registro->inicio_expediente,
                                    'HorárioDeAbertura' => $evento->hora,
                                    'DiferencaTempoDeAbertura' => $diferencaAbertura],
                                ]);
                                $rowtempoAberturaAntecipada++;
                            }
                            if (($eventominutos > ($minutosfinalExpediente+50)))
                            {
                                $diferencaAbertura =  $eventominutos - $minutosfinalExpediente;
                                $h = intdiv ($diferencaAbertura,60);
                                if ($h<10)
                                {
                                    $h='0'.$h;
                                }
                                $m = $diferencaAbertura % 60;
                                if ($m<10)
                                {
                                    $m='0'.$m;
                                }
                                $diferencaAbertura = $h.':'.$m.':'.substr($evento->hora,6,2);
                                $tempoAberturaPosExpediente = ([
                                    $rowtempoAbertura => ['dataFinalExpediente' => $evento->data,
                                    'FinalExpediente' => $registro->final_expediente,
                                    'HorárioDeAbertura' => $evento->hora,
                                    'DiferencaTempoDeAbertura' => $diferencaAbertura],
                                ]);
                                $rowtempoAberturaPosExpediente++;
                            }
                        }
                        $periodo = CarbonPeriod::create($dtmax ,  $now );
                        $dataultimoevento = \Carbon\Carbon::parse($evento->data)->format('d/m/Y');
                        if ($periodo->count()>=15)
                        {
                            $aviso ='a) Não foi possível avaliar eventos recente da utilização do alarme monitorado
                            dado que a unidade não está sendo monitorada há '. $periodo->count().' dias. Incluindo
                            a data da Inspeção. Adicionalmente verificaram que o último evento transmitido
                             foi no dia ' .$dataultimoevento. '.';
                        }
                    }
                }
                if(($rowAberturaFinalSemana==0) && ($row ==0) && ($rowtempoAbertura==0) && ($rowtempoAberturaAntecipada ==0) && ($rowtempoAberturaPosExpediente ==0) )
                {
                    $maxdata = DB::table('alarmes')
                        ->where('mcu', '=', $registro->mcu )
                        ->max('data');
                    if(! $maxdata->isEmpty())
                 //  if(!empty($maxdata ))
                   {
                       $dataultimoevento = \Carbon\Carbon::parse($maxdata)->format('d/m/Y');
                   }
                   else
                   {
                       $dataultimoevento = 'data não localizada nos parâmetros dessa pesquisa de inspeção';
                   }
                    $naoMonitorado ='Não foi possível avaliar eventos recente da utilização do alarme monitorado
                            dado que a unidade não está sendo monitorada.
                            Adicionalmente verificaram que o último evento transmitido
                             foi em ' .$dataultimoevento. '.';
                }

                return view('compliance.inspecao.editar',compact
                (
                  'registro'
                , 'id'
                , 'total'
                , 'acessos_final_semana'
                , 'rowAberturaFinalSemana'
                , 'count_alarmesFinalSemana'
                , 'acessosEmFeriados'
                , 'tempoAbertura'
                , 'tempoAberturaAntecipada'
                , 'tempoAberturaPosExpediente'
                , 'aviso'
                , 'dtmax'
                , 'now'
                ,'dtmenos12meses'
                ,'naoMonitorado'
                ));
            }

            if (($registro->numeroGrupoVerificacao==272) && ($registro->numeroDoTeste==3))
            {
                $now = Carbon::now();
                $now = $now->format('Y-m-d');
                $dtmenos12meses =  Carbon::now()->subMonth(12);
                $dtmenos12meses = $dtmenos12meses->format('Y-m-d');
                $dtini = $dtmenos12meses;
                $dtfim = $now;
                $row=0;
                $aviso = '';
                $periodo=array();
                $naoMonitorado='';

                $eventos = DB::table('alarmes')
                    ->select('alarmes.*')
                    ->where('mcu', '=', $registro->mcu)
                    ->where('data', '>', $dtmenos12meses)
                    ->orderBy('data' ,'asc')
                    ->orderBy('hora' ,'asc')
                ->get();

                if( $eventos->isEmpty() )
                {
                    $dataultimoevento = \Carbon\Carbon::parse($dtmenos12meses)->format('d/m/Y');
                    $aviso = 'a) A unidade inspecionada não está sendo monitorada há pelo menos 12 meses. Adicionalmente, informa que o último dia pesquisado foi ' .$dataultimoevento. '.';
                    $dtmax = \Carbon\Carbon::parse($now)->format('d/m/Y');
                   // $dataultimoevento = 'data não localizada nos parâmetros dessa pesquisa de inspeção';
                    $naoMonitorado ='Não foi possível confrontar os dados obtidos com as informações de férias e afastamentos, com objetivo de identificar possíveis compartilhamentos de  senha recente da utilização do alarme monitorado. Dado que, a unidade não está sendo monitorada. Adicionalmente verificaram que o último evento transmitido foi em ' .$dataultimoevento. '.';
                }
                else
                {
                    $dtmax = $eventos->max('data');

                    $periodo = CarbonPeriod::create($dtmax ,  $now );
                    $dataultimoevento = \Carbon\Carbon::parse($dtmax)->format('d/m/Y');
                    if ($periodo->count()>=15)  $aviso = 'a) A unidade inspecionada não está sendo monitorada há '. $periodo->count().' dias. Adicionalmente, verificaram que o último evento transmitido foi no dia ' .$dataultimoevento. '.';


                    // Se tem dados de alarme obter a lista de ferias por empregados da unidade
                    $ferias_por_mcu = DB::table('ferias_por_mcu')
                        ->join('cadastral', 'ferias_por_mcu.matricula', '=', 'cadastral.matricula')
                        ->select( 'ferias_por_mcu.*', 'cadastral.mcu' )
                        ->where([['cadastral.mcu', '=',  $registro->mcu  ]])
                        ->where([['ferias_por_mcu.inicio_fruicao', '<>', null ]])
                        ->where([['ferias_por_mcu.inicio_fruicao', '>',  $dtmenos12meses ]])
                        ->orderBy('ferias_por_mcu.inicio_fruicao' , 'asc')
                    ->get();
                    if(! $ferias_por_mcu->isEmpty())
                   // if(!empty($ferias_por_mcu ))
                    {
                        foreach ($ferias_por_mcu  as $ferias)
                        {
                            $inicio_fruicao = Carbon::parse($ferias->inicio_fruicao)->format('Y-m-d');
                            $termino_fruicao = Carbon::parse($ferias->termino_fruicao)->format('Y-m-d');
                            $compartilhaSenha =  DB::table('compartilhasenhas')
                                ->where('codigo', '=', $registro->codigo)
                                ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                                ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                                ->delete();

                            $res = DB::table('alarmes')
                                ->select('alarmes.*')
                                ->where([['mcu', '=', $registro->mcu]])
                                ->where([['matricula', '=', $ferias->matricula]])
                                ->whereBetween('data', [$inicio_fruicao , $termino_fruicao])
                                ->orderBy('data' , 'asc')
                                ->orderBy('hora' , 'asc')
                                ->get();

                            if ($res->count('matricula')>=1)
                            {
                                $motivo='Férias';
                                foreach ($res  as $dado)
                                {
                                    $compartilhaSenha = new CompartilhaSenha();
                                    $compartilhaSenha->codigo = $registro->codigo;
                                    $compartilhaSenha->numeroGrupoVerificacao  = $registro->numeroGrupoVerificacao;
                                    $compartilhaSenha->numeroDoTeste = $registro->numeroDoTeste;
                                    $compartilhaSenha->matricula = $dado->matricula;
                                    $compartilhaSenha->evento = $dado->armedesarme;
                                    $compartilhaSenha->data = $dado->data;
                                    $compartilhaSenha->tipoafastamento = $motivo;
                                    $compartilhaSenha->save();
                                }
                            }
                        }
                    }

                    // Se tem dados de alarme obter a lista de absenteísmo por empregados da unidade
                    $frequencias = DB::table('absenteismos')
                        ->join('cadastral', 'absenteismos.matricula', '=', 'cadastral.matricula')
                        ->select('absenteismos.*', 'cadastral.mcu')
                        ->where([['cadastral.mcu', '=',  $registro->mcu  ]])
                        ->where([['data_evento', '>',  $dtmenos12meses  ]])
                        ->whereBetween('data_evento', [$dtmenos12meses , $now])
                    ->get();

                  //  if ($frequencias->count('matricula')>=1)
                    if(! $frequencias->isEmpty())
                   // if(!empty($frequencias ))
                    {
                        foreach ($frequencias as $frequencia)
                        {
                            $dt = new Carbon($frequencia->data_evento);
                            if ($frequencia->dias>1)
                            {
                                $dt =  $dt->addDays($frequencia->dias);
                                $dt = $dt->format('Y-m-d');
                            }
                            $res = DB::table('alarmes')
                                ->select('alarmes.*')
                                ->where([['mcu', '=', $registro->mcu]])
                                ->where([['matricula', '=', $frequencia->matricula]])
                                ->whereBetween('data', [$frequencia->data_evento , $dt])
                                ->orderBy('data' , 'asc')
                                ->orderBy('hora' , 'asc')
                            ->get();
                            if(! $res->isEmpty())
                           // if(!empty($res ))
                            {
                                foreach ($res  as $dado)
                                {
                                    $compartilhaSenha = new CompartilhaSenha();
                                    $compartilhaSenha->codigo = $registro->codigo;
                                    $compartilhaSenha->numeroGrupoVerificacao  = $registro->numeroGrupoVerificacao;
                                    $compartilhaSenha->numeroDoTeste = $registro->numeroDoTeste;
                                    $compartilhaSenha->matricula = $dados->matricula;
                                    $compartilhaSenha->evento = $dados->armedesarme;
                                    $compartilhaSenha->data = $dados->data;
                                    $compartilhaSenha->tipoafastamento = $frequencia->motivo;
                                    $compartilhaSenha->save();
                                }
                            }
                        }
                    }
                    $compartilhaSenhas  =  DB::table('compartilhasenhas')
                        ->where('codigo', '=', $registro->codigo)
                        ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                        ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                        ->orderBy('data' ,'asc')
                        ->get();
                    $row= $compartilhaSenhas->count('codigo');

                    $dtmax = \Carbon\Carbon::parse($eventos->max('data'))->format('d/m/Y');

                } //tem dados de alarme

                $total=0.00;
                return view('compliance.inspecao.editar',compact
                                (
                                    'registro'
                                    , 'id'
                                    , 'total'
                                    , 'compartilhaSenhas'
                                    , 'row'
                                    , 'aviso'
                                    , 'dtini'
                                    , 'dtfim'
                                    , 'dtmax'
                                    ,'naoMonitorado'
                                ));
            }

            if (($registro->numeroGrupoVerificacao==272) && ($registro->numeroDoTeste==4))
            {
                $cftvs = DB::table('cftvs')
                   ->select( 'cftvs.*' )
                   ->where([['mcu', '=',  $registro->mcu  ]])
                   ->get();
               $total=0.00;
               return view('compliance.inspecao.editar',compact
                               (
                                   'registro'
                                   , 'id'
                                   , 'total'
                                   , 'cftvs'

                               ));
            }

            if (($registro->numeroGrupoVerificacao==274) && ($registro->numeroDoTeste==1))
            {
                $total=0.00;
                $count = 0;

                $plplistapendentes = DB::table('plplistapendentes')
                   ->select( 'plplistapendentes.*' )
                   ->where([['stomcu', '=',  $registro->mcu  ]])
                ->get();

                if( !empty($plplistapendentes->dh_lista_postagem ))
                {
                    $count = $plplistapendentes->count('lista');
                    $dtfim = $plplistapendentes->max('dh_lista_postagem');
                }
                else
                {
                    $dtfim = Carbon::now();
                }
                return view('compliance.inspecao.editar',compact
                               (
                                   'registro'
                                   , 'id'
                                   , 'total'
                                   , 'plplistapendentes'
                                   ,'count'
                                   ,'dtfim'
                               ));
            }

            if (($registro->numeroGrupoVerificacao==276) && ($registro->numeroDoTeste==1))
            {
                $count = 0;
                $total=0.00;
                $dtmenos3meses = Carbon::now();
                $dtmenos3meses = $dtmenos3meses->subMonth(3);

                $controle_de_viagens = DB::table('controle_de_viagens')
                   ->select( 'controle_de_viagens.*' )
                   ->where('ponto_parada', '=', $registro->an8)
                    ->where('inicio_viagem', '=', $dtmenos3meses)
                ->get();


                if( !empty($controle_de_viagens->ponto_parada ))
                {
                    $count = $controle_de_viagens->count('ponto_parada');
                }
                $dtini = $dtmenos3meses;
                $dtfim =  Carbon::now();
                $periodo = new CarbonPeriod();
                $periodo = CarbonPeriod::create($dtini ,  $dtfim );
                $dias =$periodo->count()-1;
                if ($dias >=7 ){
                    $dias = intdiv($dias, 7)*5;
                } elseif($dias == 6){
                    $dias=5;
                }
                $viagens = $dias*2;
                $viagemNaorealizada =   $viagens-$count;
//                dd(      $controle_de_viagens);
                return view('compliance.inspecao.editar',compact
                   (
                       'registro'
                       , 'id'
                       , 'total'
                       , 'controle_de_viagens'
                       ,'dtini'
                       ,'dtfim'
                       ,'count'
                       ,'viagens'
                       ,'viagemNaorealizada'

                   ));
            }

            if (($registro->numeroGrupoVerificacao==277) && ($registro->numeroDoTeste==1))
            {

                $dtmenos110dias = new Carbon();
                $dtmenos110dias->subDays(110);
                $dtmenos110dias = \Carbon\Carbon::parse($dtmenos110dias)->format('Y-m-d');
                $count = 0;
                $total=0.00;

                if($registro->tem_distribuicao == 'Não tem distribuição')
                {
                    $dtfim = new Carbon();
                    $dtini = $dtmenos110dias;
                    $sgdo_distribuicao='';
                }
                else
                {
                    $sgdo_distribuicao = DB::table('sgdo_distribuicao')
                        ->select( 'sgdo_distribuicao.*' )
                        ->where([['mcu', '=',  $registro->mcu  ]])
                        ->where([['data_incio_atividade', '>=',  $dtmenos110dias  ]])
                    ->get();


                    if(! $sgdo_distribuicao->isEmpty())
                   // if( !empty( $sgdo_distribuicao ))
                    {
                        $count = $sgdo_distribuicao->count('mcu');
                        $dtfim = $sgdo_distribuicao->max('data_incio_atividade');
                        $dtini = $sgdo_distribuicao->min('data_incio_atividade');
                    }
                    else
                    {
                        $dtfim = new Carbon();
                        $dtini = $dtmenos110dias;
                    }
                }
                return view('compliance.inspecao.editar',compact
                                (
                                    'registro'
                                    , 'id'
                                    , 'total'
                                    , 'sgdo_distribuicao'
                                    , 'count'
                                    , 'dtini'
                                    , 'dtfim'
                                ));
            }

            if (($registro->numeroGrupoVerificacao==277) && ($registro->numeroDoTeste==2))
            {
                $dtmenos120dias = new Carbon();
                $dtmenos120dias = $dtmenos120dias->subDays(120);
                $count = 0;
                $total=0.00;
              //  $item=1;
                $dtini=null;
                $dtfim=null;
                $media=null;
                $random=null;
                $amostra=null;
                $qtd_falhas=null;
                $percentagem_falhas=null;


                $lancamentossros =  DB::table('lancamentossro')
                    ->where('codigo', '=', $registro->codigo)
                    ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                    ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                ->get();

                if( $lancamentossros->count('codigo') >= 1)      // játem lançamentos para avaliar...
                {

                    $pend = 0;
                    $aval = 0;
                    foreach ($lancamentossros as $register)
                    {
                        if ($register->estado == 'Pendente')
                        {
                            $pend++;
                        }
                        else
                        {
                            $aval++;
                        }
                    }
                    $mostra = $pend + $aval;
                    if ($aval == $mostra) // não existem objetos pendentes de avaliação
                    {
                        $avaliados = DB::table('lancamentossro')
                            ->where('codigo', '=', $registro->codigo)
                            ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                            ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                            ->where('estado', '=', 'Avaliado')
                            ->get();
                        $lancamentossro = DB::table('lancamentossro')
                            ->where('codigo', '=', $registro->codigo)
                            ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                            ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                            ->get();
                        $qtd_falhas = 0;
                        $amostra = 0;
                        foreach ($lancamentossro as $lancamento)
                        {
                            if (($lancamento->falhaDetectada <> 'Ok') && ($lancamento->estado == 'Avaliado'))
                            {
                                $qtd_falhas++;
                            }
                            if ($lancamento->estado == 'Avaliado')
                            {
                                $amostra++;
                            }
                        }
                        $percentagem_falhas = (($qtd_falhas / $amostra) * 100);
                        $percentagem_falhas = number_format($percentagem_falhas, 2, ',', '.');

                        $res = DB::table('lancamentossro')
                            ->where('codigo', '=', $registro->codigo)
                            ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                            ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                            ->where('falhaDetectada', '<>', 'Ok')
                            ->where('estado', '=', 'Avaliado')
                        ->get();
//
//                        if( !empty( $res ))
//                        {
//                            echo 'tem res';
//                            var_dump($res );
//                        }
//                        else
//                        {
//                            echo 'nao tem res';
//                        }
//                        dd();

                        $micro_strategys = DB::table('micro_strategys')
                            ->where('nome_da_unidade', 'like', '%' . trim($registro->descricao) . '%')  //trim($registro->descricao)
                            ->where([['data_do_evento', '>=', $dtmenos120dias]])
                            ->where(function ($query) {
                                $query
                                    ->where('codigo_do_objeto', 'not like', 'B%')
                                    ->where('codigo_do_objeto', 'not like', 'E%')
                                    ->where('codigo_do_objeto', 'not like', 'F%')
                                    ->where('codigo_do_objeto', 'not like', 'I%')
                                    ->where('codigo_do_objeto', 'not like', 'J%')
                                    ->where('codigo_do_objeto', 'not like', 'L%')
                                    ->where('codigo_do_objeto', 'not like', 'M%')
                                    ->where('codigo_do_objeto', 'not like', 'N%')
                                    ->where('codigo_do_objeto', 'not like', 'R%')
                                    ->where('codigo_do_objeto', 'not like', 'T%')
                                    ->where('codigo_do_objeto', 'not like', 'U%')
//                                    ->where('descricao_do_evento', '=', 'DESTINATARIO AUSENTE')
                                    ->where('descricao_do_evento', '=', 'ENTREGUE')
                                    ->orWhere('descricao_do_evento', '=', 'DISTRIBUÍDO AO REMETENTE')
                                    ->orWhere('descricao_do_evento', '=', 'DESTINATÁRIO MUDOU-SE')
                                    ->orWhere('descricao_do_evento', '=', 'DESTINATÁRIO DESCONHECIDO NO ENDEREÇO')
                                    ->orderBy('data_do_evento', 'asc')
                                    ->groupBy('codigo_do_objeto');
                            })
                        ->get();

                        $count = $micro_strategys->count('codigo_do_objeto');
                        $dtini = $micro_strategys->min('data_do_evento');
                        $dtfim = $micro_strategys->max('data_do_evento');
                        $periodo = CarbonPeriod::create($dtini, $dtfim);
                        $dias = $periodo->count() - 1;
                       // dd(' 1470  mostra-> '.     $mostra , 'val '.$aval);
                        return view('compliance.inspecao.editar', compact
                        (
                            'registro'
                            , 'id'
                            , 'total'
                            , 'count' // x
                            , 'dtini' // x
                            , 'dtfim' //
                            , 'media'
                            , 'random'
                            , 'amostra'
                      //      , 'item'
                            , 'res'
                            , 'qtd_falhas'
                            , 'percentagem_falhas'
                        ));
                    }
                    if ($pend <= $mostra) // existem objetos pendentes de avaliação
                    {
                        $res = DB::table('lancamentossro')
                            ->where('codigo', '=', $registro->codigo)
                            ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                            ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                            ->where('estado', '=', 'Pendente')
                            ->get();
                        return view('compliance.inspecao.index_sro', compact
                        (
                            'registro'
                            , 'id'
                            , 'res'
                        ));
                    }
                }
                else  // ainda não tem lançamentos para avaliar...
                {
                    $micro_strategys = DB::table('micro_strategys')
                        ->where('nome_da_unidade', 'like', '%' . trim($registro->descricao) . '%')  //trim($registro->descricao)
                        ->where([['data_do_evento', '>=', $dtmenos120dias]])
                       // ->where([['dr_de_destino', '>=', $registro->se]])
                        ->where(function ($query) {
                              $query
                                  ->where('codigo_do_objeto', 'not like', 'B%')
                                  ->where('codigo_do_objeto', 'not like', 'E%')
                                  ->where('codigo_do_objeto', 'not like', 'F%')
                                  ->where('codigo_do_objeto', 'not like', 'I%')
                                  ->where('codigo_do_objeto', 'not like', 'J%')
                                  ->where('codigo_do_objeto', 'not like', 'L%')
                                  ->where('codigo_do_objeto', 'not like', 'M%')
                                  ->where('codigo_do_objeto', 'not like', 'N%')
                                  ->where('codigo_do_objeto', 'not like', 'R%')
                                  ->where('codigo_do_objeto', 'not like', 'T%')
                                  ->where('codigo_do_objeto', 'not like', 'U%')
                            //    ->where('descricao_do_evento', '=', 'DESTINATARIO AUSENTE')
                                  ->where('descricao_do_evento', '=', 'ENTREGUE')
                                  ->orWhere('descricao_do_evento', '=', 'DISTRIBUÍDO AO REMETENTE')
                                  ->orWhere('descricao_do_evento', '=', 'DESTINATÁRIO MUDOU-SE')
                                  ->orWhere('descricao_do_evento', '=', 'DESTINATÁRIO DESCONHECIDO NO ENDEREÇO')
                                  ->orderBy('data_do_evento', 'asc')
                                  ->groupBy('codigo_do_objeto');
                        })
                    ->get();
                    if(! $micro_strategys->isEmpty())
                   // if( !empty( $micro_strategys ))// tem objetos na consulta para inserir no banco
                    {
                        $count = $micro_strategys->count('codigo_do_objeto');
                        $dtini = $micro_strategys->min('data_do_evento');
                        $dtfim = $micro_strategys->max('data_do_evento');
                        $periodo = CarbonPeriod::create($dtini, $dtfim);
                        $dias = $periodo->count() - 1;
                        // $media = intval($count / $dias);
                        $amostra = 0;  //- tamanho da amostra
                        $N = intval($count / $dias) * 30;   //N =  tamanho universo da população;
                        $z = 1.9;   //Z = nível de confiança desejado 90%
                        $e = 900;   // e = a margem de erro máximo que é admitida;
                        $d = 4000;  //d Desvio padrão 4000 da população determinado
                        //  formula       (z^2*desvio^2*N)/(z^2*desvio^2+e^2*(N-1))
                        $dividendo = (pow($z, 2) * pow($d, 2) * $N);
                        $divisor = (pow($z, 2) * pow($d, 2) + pow($e, 2) * ($N - 1));
                        $amostra = intval($dividendo / $divisor);

                        // InvalidArgumentException
                        //Você solicitou 53 itens, mas há apenas 43 itens disponíveis.
                        if( $amostra  > $count  ) $amostra = $count ;
                        //dd($amostra, $count);

                        if ($amostra >= 1)
                        {
                            $random = $micro_strategys->random($amostra);
                        }
                        if ($amostra == 0)
                        {
                            if ($count <= 60)
                                $random = $micro_strategys->random($count);
                            else
                                $random = $micro_strategys->random(25);
                        }
                        $random->all();

                        foreach ($random as $dado)
                        {
                            $lancamentossro = new LancamentosSRO();
                            $lancamentossro->codigo = $registro->codigo;
                            $lancamentossro->numeroGrupoVerificacao = $registro->numeroGrupoVerificacao;
                            $lancamentossro->numeroDoTeste = $registro->numeroDoTeste;
                            $lancamentossro->objeto = $dado->codigo_do_objeto;
                            $lancamentossro->data = $dado->data_do_evento;
                            $lancamentossro->localBaixa1tentativa = $dado->descricao_do_evento;
                            $lancamentossro->estado = 'Pendente';
                            $lancamentossro->save();
                        }
                        $res = DB::table('lancamentossro')
                            ->where('codigo', '=', $registro->codigo)
                            ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                            ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                            ->where('estado', '=', 'Pendente')
                        ->get();

                        return view('compliance.inspecao.index_sro', compact
                        (
                            'registro'
                            , 'id'
                            , 'res'
                        ));
                    }
                    else // Nãp tem objetos na consulta
                    {
                     //   dd('Não tem objetos para avaliar');
                        return view('compliance.inspecao.editar', compact
                        (
                            'registro'
                            , 'id'
                            , 'total' //xx
                            , 'count' //xx
                            , 'dtini'
                            , 'dtfim'
                            , 'media'
                            , 'random'
                            , 'amostra'
                          //  , 'item' //xx
                            , 'res'
                            , 'qtd_falhas'
                            , 'percentagem_falhas'
                        ));
                    }
                }

            }

            if (($registro->numeroGrupoVerificacao==277) && ($registro->numeroDoTeste==3))
            {
                $dtmenos120dias = new Carbon();
                $dtmenos120dias = $dtmenos120dias->subDays(120);
                $count = 0;
                $total=0.00;
                //  $item=1;
                $dtini=null;
                $dtfim=null;
                $media=null;
                $random=null;
                $amostra=null;
                $qtd_falhas=null;
                $percentagem_falhas=null;

                $lancamentossros =  DB::table('lancamentossro')
                    ->where('codigo', '=', $registro->codigo)
                    ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                    ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                    ->get();
                if( $lancamentossros->count('codigo') >= 1)       // játem lançamentos para avaliar...
                {
                    $pend = 0;
                    $aval = 0;
                    foreach ($lancamentossros as $register)
                    {
                        if ($register->estado == 'Pendente')
                        {
                            $pend++;
                        }
                        else
                        {
                            $aval++;
                        }
                    }
                    $mostra = $pend + $aval;
                    if ($aval == $mostra) // não existem objetos pendentes de avaliação
                    {
                        $avaliados = DB::table('lancamentossro')
                            ->where('codigo', '=', $registro->codigo)
                            ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                            ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                            ->where('estado', '=', 'Avaliado')
                            ->get();
                        $lancamentossro = DB::table('lancamentossro')
                            ->where('codigo', '=', $registro->codigo)
                            ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                            ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                            ->get();
                        $qtd_falhas = 0;
                        $amostra = 0;
                        foreach ($lancamentossro as $lancamento)
                        {
                            if (($lancamento->falhaDetectada <> 'Ok') && ($lancamento->estado == 'Avaliado'))
                            {
                                $qtd_falhas++;
                            }
                            if ($lancamento->estado == 'Avaliado')
                            {
                                $amostra++;
                            }
                        }
                        $percentagem_falhas = (($qtd_falhas / $amostra) * 100);
                        $percentagem_falhas = number_format($percentagem_falhas, 2, ',', '.');

                        $res = DB::table('lancamentossro')
                            ->where('codigo', '=', $registro->codigo)
                            ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                            ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                            ->where('falhaDetectada', '<>', 'Ok')
                            ->where('estado', '=', 'Avaliado')
                            ->get();

                        $micro_strategys = DB::table('micro_strategys')
                            ->where('nome_da_unidade', 'like', '%' . trim($registro->descricao) . '%')  //trim($registro->descricao)
                            ->where([['data_do_evento', '>=', $dtmenos120dias]])
                            ->where(function ($query) {
                                $query
                                    ->where('codigo_do_objeto', 'not like', 'B%')
                                    ->where('codigo_do_objeto', 'not like', 'E%')
                                    ->where('codigo_do_objeto', 'not like', 'F%')
                                    ->where('codigo_do_objeto', 'not like', 'I%')
                                    ->where('codigo_do_objeto', 'not like', 'J%')
                                    ->where('codigo_do_objeto', 'not like', 'L%')
                                    ->where('codigo_do_objeto', 'not like', 'M%')
                                    ->where('codigo_do_objeto', 'not like', 'N%')
                                    ->where('codigo_do_objeto', 'not like', 'R%')
                                    ->where('codigo_do_objeto', 'not like', 'T%')
                                    ->where('codigo_do_objeto', 'not like', 'U%')
                                    ->where('descricao_do_evento', '=', 'DESTINATARIO AUSENTE')
//                                    ->where('descricao_do_evento', '=', 'ENTREGUE')
//                                    ->orWhere('descricao_do_evento', '=', 'DISTRIBUÍDO AO REMETENTE')
//                                    ->orWhere('descricao_do_evento', '=', 'DESTINATÁRIO MUDOU-SE')
//                                    ->orWhere('descricao_do_evento', '=', 'DESTINATÁRIO DESCONHECIDO NO ENDEREÇO')
                                    ->orderBy('data_do_evento', 'asc')
                                    ->groupBy('codigo_do_objeto');
                            })
                            ->get();

                        $count = $micro_strategys->count('codigo_do_objeto');
                        $dtini = $micro_strategys->min('data_do_evento');
                        $dtfim = $micro_strategys->max('data_do_evento');
                        $periodo = CarbonPeriod::create($dtini, $dtfim);
                        $dias = $periodo->count() - 1;
                        // dd(' 1470  mostra-> '.     $mostra , 'val '.$aval);
                        return view('compliance.inspecao.editar', compact
                        (
                            'registro'
                            , 'id'
                            , 'total'
                            , 'count' // x
                            , 'dtini' // x
                            , 'dtfim' //
                            , 'media'
                            , 'random'
                            , 'amostra'
                            //      , 'item'
                            , 'res'
                            , 'qtd_falhas'
                            , 'percentagem_falhas'
                        ));
                    }
                    if ($pend <= $mostra) // existem objetos pendentes de avaliação
                    {
                        $res = DB::table('lancamentossro')
                            ->where('codigo', '=', $registro->codigo)
                            ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                            ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                            ->where('estado', '=', 'Pendente')
                            ->get();
                        return view('compliance.inspecao.index_sro', compact
                        (
                            'registro'
                            , 'id'
                            , 'res'
                        ));
                    }
                }
                else  // ainda não tem lançamentos para avaliar...
                {
                    $micro_strategys = DB::table('micro_strategys')
                        ->where('nome_da_unidade', 'like', '%' . trim($registro->descricao) . '%')  //trim($registro->descricao)
                        ->where([['data_do_evento', '>=', $dtmenos120dias]])
                        // ->where([['dr_de_destino', '>=', $registro->se]])
                        ->where(function ($query) {
                            $query
                                ->where('codigo_do_objeto', 'not like', 'B%')
                                ->where('codigo_do_objeto', 'not like', 'E%')
                                ->where('codigo_do_objeto', 'not like', 'F%')
                                ->where('codigo_do_objeto', 'not like', 'I%')
                                ->where('codigo_do_objeto', 'not like', 'J%')
                                ->where('codigo_do_objeto', 'not like', 'L%')
                                ->where('codigo_do_objeto', 'not like', 'M%')
                                ->where('codigo_do_objeto', 'not like', 'N%')
                                ->where('codigo_do_objeto', 'not like', 'R%')
                                ->where('codigo_do_objeto', 'not like', 'T%')
                                ->where('codigo_do_objeto', 'not like', 'U%')
                                ->where('descricao_do_evento', '=', 'DESTINATARIO AUSENTE')
//                                ->where('descricao_do_evento', '=', 'ENTREGUE')
//                                ->orWhere('descricao_do_evento', '=', 'DISTRIBUÍDO AO REMETENTE')
//                                ->orWhere('descricao_do_evento', '=', 'DESTINATÁRIO MUDOU-SE')
//                                ->orWhere('descricao_do_evento', '=', 'DESTINATÁRIO DESCONHECIDO NO ENDEREÇO')
                                ->orderBy('data_do_evento', 'asc')
                                ->groupBy('codigo_do_objeto');
                        })
                        ->get();
                    if(! $micro_strategys->isEmpty())
                   // if( !empty( $micro_strategys ))// tem objetos na consulta para inserir no banco
                    {
                        $count = $micro_strategys->count('codigo_do_objeto');
                        $dtini = $micro_strategys->min('data_do_evento');
                        $dtfim = $micro_strategys->max('data_do_evento');
                        $periodo = CarbonPeriod::create($dtini, $dtfim);
                        $dias = $periodo->count() - 1;
                        // $media = intval($count / $dias);
                        $amostra = 0;  //- tamanho da amostra
                        $N = intval($count / $dias) * 30;   //N =  tamanho universo da população;
                        $z = 1.9;   //Z = nível de confiança desejado 90%
                        $e = 900;   // e = a margem de erro máximo que é admitida;
                        $d = 4000;  //d Desvio padrão 4000 da população determinado
                        //  formula       (z^2*desvio^2*N)/(z^2*desvio^2+e^2*(N-1))
                        $dividendo = (pow($z, 2) * pow($d, 2) * $N);
                        $divisor = (pow($z, 2) * pow($d, 2) + pow($e, 2) * ($N - 1));
                        $amostra = intval($dividendo / $divisor);

                        // InvalidArgumentException
                        //Você solicitou 53 itens, mas há apenas 43 itens disponíveis.
                        if( $amostra  > $count  ) $amostra = $count ;
                        //dd($amostra, $count);

                        if ($amostra >= 1)
                        {
                            $random = $micro_strategys->random($amostra);
                        }
                        if ($amostra == 0)
                        {
                            if ($count <= 60)
                                $random = $micro_strategys->random($count);
                            else
                                $random = $micro_strategys->random(25);
                        }
                        $random->all();

                        foreach ($random as $dado)
                        {
                            $lancamentossro = new LancamentosSRO();
                            $lancamentossro->codigo = $registro->codigo;
                            $lancamentossro->numeroGrupoVerificacao = $registro->numeroGrupoVerificacao;
                            $lancamentossro->numeroDoTeste = $registro->numeroDoTeste;
                            $lancamentossro->objeto = $dado->codigo_do_objeto;
                            $lancamentossro->data = $dado->data_do_evento;
                            $lancamentossro->localBaixa1tentativa = $dado->descricao_do_evento;
                            $lancamentossro->estado = 'Pendente';
                            $lancamentossro->save();
                        }
                        $res = DB::table('lancamentossro')
                            ->where('codigo', '=', $registro->codigo)
                            ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                            ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                            ->where('estado', '=', 'Pendente')
                            ->get();

                        return view('compliance.inspecao.index_sro', compact
                        (
                            'registro'
                            , 'id'
                            , 'res'
                        ));
                    }
                    else // Nãp tem objetos na consulta
                    {
                        //   dd('Não tem objetos para avaliar');
                        return view('compliance.inspecao.editar', compact
                        (
                            'registro'
                            , 'id'
                            , 'total' //xx
                            , 'count' //xx
                            , 'dtini'
                            , 'dtfim'
                            , 'media'
                            , 'random'
                            , 'amostra'
                            //  , 'item' //xx
                            , 'res'
                            , 'qtd_falhas'
                            , 'percentagem_falhas'
                        ));
                    }
                }

            }

            if (($registro->numeroGrupoVerificacao==277) && ($registro->numeroDoTeste==5))
            {
                $dtmenos150dias = new Carbon();
                $dtmenos150dias->subDays(150);
                $dtini = $dtmenos150dias;

                switch ($registro->se)
                {
                    case 1 :{ $superintendência = 'CS'; } break;
                    case 4 :{ $superintendência = 'AL'; } break;
                    case 6 :{ $superintendência = 'AM'; } break;
                    case 8 :{ $superintendência = 'BA'; } break;
                    case 10 :{ $superintendência = 'BSB'; } break;
                    case 12 :{ $superintendência = 'CE'; } break;
                    case 14 :{ $superintendência = 'ES'; } break;
                    case 16 :{ $superintendência = 'GO'; } break;
                    case 18 :{ $superintendência = 'MA'; } break;
                    case 20 :{ $superintendência = 'MG'; } break;
                    case 22 :{ $superintendência = 'MS'; } break;
                    case 24 :{ $superintendência = 'MT'; } break;
                    case 26 :{ $superintendência = 'RO'; } break;
                    case 28 :{ $superintendência = 'PA'; } break;
                    case 30 :{ $superintendência = 'PB'; } break;
                    case 32 :{ $superintendência = 'PE'; } break;
                    case 34 :{ $superintendência = 'PI'; } break;
                    case 36 :{ $superintendência = 'PR'; } break;
                    case 50 :{ $superintendência = 'RJ'; } break;
                    case 60 :{ $superintendência = 'RN'; } break;
                    case 64 :{ $superintendência = 'RS'; } break;
                    case 68 :{ $superintendência = 'SC'; } break;
                    case 72 :{ $superintendência = 'SPM'; } break;
                    case 74 :{ $superintendência = 'SPI'; } break;
                    case 75 :{ $superintendência = 'TO'; } break;
                }
                // dd( ' 2012 ', $registro->se,  $superintendência);
                $painel_extravios = DB::table('painel_extravios')
                   ->select( 'painel_extravios.*' )
                   ->where([['painel_extravios.data_evento', '>=',  $dtmenos150dias  ]])
                   ->where([['painel_extravios.dr_destino', '=',  $superintendência  ]])//o relatório não tem mcu
                   ->where([['painel_extravios.unid_destino_apelido', '=',  $registro->descricao  ]])
                   ->where([['painel_extravios.gestao_prealerta', '=',  'Gestão Automática' ]])
                ->get();

                $count = $painel_extravios->count('unid_destino_apelido');
                $dtfim = $painel_extravios->max('data_evento');

                //var_dump($painel_extravios);
               //dd( $count);

//                $datas = DB::table('painel_extravios')
//                   ->select( 'painel_extravios.data_evento' )
//                   ->where([['painel_extravios.data_evento', '>=',  $dtmenos150dias  ]])
//
//                   ->get();
//
//                $dtfim = $datas->max('data_evento');

                $countSupervisor=0;
                $cadastral = DB::table('cadastral')
                ->select( 'cadastral.*' )
                ->where([['cadastral.mcu', '=',   $registro->mcu  ]])
                ->where('cadastral.funcao',  'like', '%' . 'SUPERVISOR' . '%')
                ->get();

                $countSupervisor = $cadastral->count('funcao');


                $total=0.00;
                return view('compliance.inspecao.editar',compact
                               (
                                   'registro'
                                   , 'id'
                                   , 'total'
                                   , 'painel_extravios'
                                   ,'count'
                                   ,'dtini'
                                   ,'dtfim'
                                   ,'countSupervisor'

                               ));
            }

            if (($registro->numeroGrupoVerificacao==277) && ($registro->numeroDoTeste==6))
            {
                $dtmenos150dias = new Carbon();
                $dtmenos150dias->subDays(150);
                $dtini = $dtmenos150dias;

                switch ($registro->se)
                {
                    case 1 :{ $superintendência = 'CS'; } break;
                    case 4 :{ $superintendência = 'AL'; } break;
                    case 6 :{ $superintendência = 'AM'; } break;
                    case 8 :{ $superintendência = 'BA'; } break;
                    case 10 :{ $superintendência = 'BSB'; } break;
                    case 12 :{ $superintendência = 'CE'; } break;
                    case 14 :{ $superintendência = 'ES'; } break;
                    case 16 :{ $superintendência = 'GO'; } break;
                    case 18 :{ $superintendência = 'MA'; } break;
                    case 20 :{ $superintendência = 'MG'; } break;
                    case 22 :{ $superintendência = 'MS'; } break;
                    case 24 :{ $superintendência = 'MT'; } break;
                    case 26 :{ $superintendência = 'RO'; } break;
                    case 28 :{ $superintendência = 'PA'; } break;
                    case 30 :{ $superintendência = 'PB'; } break;
                    case 32 :{ $superintendência = 'PE'; } break;
                    case 34 :{ $superintendência = 'PI'; } break;
                    case 36 :{ $superintendência = 'PR'; } break;
                    case 50 :{ $superintendência = 'RJ'; } break;
                    case 60 :{ $superintendência = 'RN'; } break;
                    case 64 :{ $superintendência = 'RS'; } break;
                    case 68 :{ $superintendência = 'SC'; } break;
                    case 72 :{ $superintendência = 'SPM'; } break;
                    case 74 :{ $superintendência = 'SPI'; } break;
                    case 75 :{ $superintendência = 'TO'; } break;
                }
                // dd( ' 2012 ', $registro->se,  $superintendência);
                $painel_extravios = DB::table('painel_extravios')
                    ->select( 'painel_extravios.*' )
                    ->where([['painel_extravios.data_evento', '>=',  $dtmenos150dias  ]])
                    ->where([['painel_extravios.dr_destino', '=',  $superintendência  ]])//o relatório não tem mcu
                    ->where([['painel_extravios.unid_destino_apelido', '=',  $registro->descricao  ]])
                    ->where([['painel_extravios.gestao_prealerta', '=',  'Gestão Automática' ]])
                ->get();

                $count = $painel_extravios->count('unid_destino_apelido');
                $dtfim = $painel_extravios->max('data_evento');


                $countSupervisor=0;
                $cadastral = DB::table('cadastral')
                    ->select( 'cadastral.*' )
                    ->where([['cadastral.mcu', '=',   $registro->mcu  ]])
                    ->where('cadastral.funcao',  'like', '%' . 'SUPERVISOR' . '%')
                ->get();

                $countSupervisor = $cadastral->count('funcao');

                $total=0.00;
                return view('compliance.inspecao.editar',compact
                               (
                                   'registro'
                                   , 'id'
                                   , 'total'
                                   , 'painel_extravios'
                                   ,'count'
                                   ,'dtini'
                                   ,'dtfim'
                                   ,'countSupervisor'

                               ));
            }

            if (($registro->numeroGrupoVerificacao==277) && ($registro->numeroDoTeste==7))
            {
                $dtmenos365dias = new Carbon();
                $dtmenos365dias->subDays(365);
                $dtini = $dtmenos365dias;

                switch ($registro->se)
                {
                    case 1 :{ $superintendência = 'CS'; } break;
                    case 4 :{ $superintendência = 'AL'; } break;
                    case 6 :{ $superintendência = 'AM'; } break;
                    case 8 :{ $superintendência = 'BA'; } break;
                    case 10 :{ $superintendência = 'BSB'; } break;
                    case 12 :{ $superintendência = 'CE'; } break;
                    case 14 :{ $superintendência = 'ES'; } break;
                    case 16 :{ $superintendência = 'GO'; } break;
                    case 18 :{ $superintendência = 'MA'; } break;
                    case 20 :{ $superintendência = 'MG'; } break;
                    case 22 :{ $superintendência = 'MS'; } break;
                    case 24 :{ $superintendência = 'MT'; } break;
                    case 26 :{ $superintendência = 'RO'; } break;
                    case 28 :{ $superintendência = 'PA'; } break;
                    case 30 :{ $superintendência = 'PB'; } break;
                    case 32 :{ $superintendência = 'PE'; } break;
                    case 34 :{ $superintendência = 'PI'; } break;
                    case 36 :{ $superintendência = 'PR'; } break;
                    case 50 :{ $superintendência = 'RJ'; } break;
                    case 60 :{ $superintendência = 'RN'; } break;
                    case 64 :{ $superintendência = 'RS'; } break;
                    case 68 :{ $superintendência = 'SC'; } break;
                    case 72 :{ $superintendência = 'SPM'; } break;
                    case 74 :{ $superintendência = 'SPI'; } break;
                    case 75 :{ $superintendência = 'TO'; } break;
                }

//            dd( ' 2012 ', $registro->se,  $superintendência);

//            a) Documentos respondidos acima do prazo de 03 dias úteis;
//            b) Se há CIEs sem registro das providências adotadas ou com ações genéricas, que não demonstrem assertividade ou não comprovem efetividade, como por exemplo: ""Empregado orientado"", ""Estamos apurando o ocorrido"";
//            c) A ocorrência de reincidência. Considerar a existência de 03 CIEs recebidas pelos mesmos Motivos dentro do período de 01 mês;
//            d) Comunicados de Irregularidades com status ""Pendente"" e/ou ""Não Lido"".
                $cie_eletronicas = DB::table('cie_eletronicas')
                   ->select( 'cie_eletronicas.*' )
                   ->where([['cie_eletronicas.emissao', '>=',  $dtmenos365dias  ]])
                   ->where([['cie_eletronicas.se_destino', '=',   $superintendência   ]])
                   ->where([['cie_eletronicas.destino',  'like', '%' . $registro->descricao . '%']])
                   ->where([['cie_eletronicas.respondida', '=',  'N' ]])
                   ->get();
                $count = $cie_eletronicas->count('respondida');
                $dtfim = $cie_eletronicas->max('emissao');

                $total=0.00;
                return view('compliance.inspecao.editar',compact
                               (
                                   'registro'
                                   , 'id'
                                   , 'total'
                                   , 'cie_eletronicas'
                                   ,'count'
                                   ,'dtini'
                                   ,'dtfim'


                               ));
            }

            if (($registro->numeroGrupoVerificacao==278) && ($registro->numeroDoTeste==1))
            {
                $dtmenos6meses = new Carbon();
                $dtmenos6meses->subMonth(6);
                $ref = substr($dtmenos6meses,0,4). substr($dtmenos6meses,5,2);
                $dtini = substr($dtmenos6meses,0,4).'-'. substr($dtmenos6meses,5,2).'-01';
                $dtini = \Carbon\Carbon::parse($dtini)->format('d/m/Y');
                $count = 0;
                $total=0.00;
                $rowtfs=0;
                $situacao =null;
                $pgtoAdicionais='';
                $counteventostfs=0;

                $pgtadd = DB::table('pagamentos_adicionais')
                    ->select(
                                'pagamentos_adicionais.ref'
                    )
                ->get();

                if(! $pgtadd->isEmpty())
                {
                    $reffinal = $pgtadd->max('ref');

                    if(substr($reffinal,5,2)<10){
                        $dt='0'.substr($reffinal,5,2);
                    }else{
                        $dt= substr($reffinal,5,2);
                    }
                    $reffinal =  substr($reffinal,0,4).'-'.$dt;
                    $reffinal = new Carbon($reffinal);
                    $reffinal=$reffinal->lastOfMonth();
                    $reffinal = \Carbon\Carbon::parse($reffinal)->format('d/m/Y');
                }
                else
                {
                    $reffinal=null;
                }

                $pagamentos_adicionais = DB::table('pagamentos_adicionais')
                    ->where('sigla_lotacao',  'like', '%' . trim($registro->descricao) . '%')  //trim($registro->descricao)
                    ->where('ref', '>=', $ref) //
                    ->where(function ($query) {
                $query
                    ->where('rubrica', '=', 'Trab. Fins Semana - Proporcional')
                    ->where('rubrica', '=', 'Trabalho Fins Semana')
            //        ->where('rubrica', '=', 'Trabalho Fins de Semana Judicial (15%)')

                    ->orWhere('rubrica', '=', 'Hora Extra   70% - Norm')
                    ->orWhere('rubrica', '=', 'Hora Extra 100% - Norm')
                    ->orWhere('rubrica', '=', 'Hora Extra Not.70% - Norm')
                    ->orderBy('ref' ,'asc');
                })
                ->get();


                if(! $pagamentos_adicionais->isEmpty())
                {
                    $count = $pagamentos_adicionais->count('sigla_lotacao');
                }
                else
                {
                    $count = 0;
                }

                if  ( $count >= 1 ){

                    DB::table('pgto_adicionais_temp')
                        ->where('codigo', '=', $registro->codigo)
                        ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                        ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                    ->delete();
                    foreach ($pagamentos_adicionais  as $adicional){
                        $situacao = null;
                        $periodo = new Carbon(substr($adicional->ref,0,4).'-'. substr($adicional->ref,5,2));
                        $periodo->subMonth(1);
                        $month = $periodo->month;
                        $year = $periodo->year;
                        if($adicional-> rubrica == 'Trab. Fins Semana - Proporcional' ){

                            $eventos = DB::table('alarmes')
                                ->where('mcu', '=', $registro->mcu)
                                ->whereYear('data', $year)
                                ->whereMonth('data',  $month)
                                ->where('diaSemana', '=', 6)
                                ->select(
                                            'alarmes.*'
                                )
                                ->orderBy('data' ,'asc')
                            ->get();

                            if(! $eventos->isEmpty())
                            {
                                $counteventostfs = $eventos->count('data');
                            }
                            else
                            {
                                $counteventostfs = 0;
                            }

                            if( $counteventostfs == 0){
                                $situacao = 'Provento registrado em período que não houve registro de Desarme do Sistema de Alarme.';
                            }else{
                                $rowtfs=0;
                                foreach ($eventos  as $evento){
                                    $rowtfs++;
                                }
                                $situacao=null;
                            }

                        }
                        elseif
                               (($adicional-> rubrica    == 'Hora Extra   70% - Norm')
                            || ($adicional-> rubrica == 'Hora Extra 100% - Norm')
                            || ($adicional-> rubrica == 'Hora Extra Not.70% - Norm') )
                        {

                                //   dd($registro);
                                //+ " inicio_expediente ": " 09:00:00 "
                                //+ " final_expediente ": " 17:00:00 "
                                $inicio_expediente = new Carbon($registro->inicio_expediente); //$registro->inicio_expediente;
                                $final_expediente = new Carbon($registro->final_expediente); //$registro->inicio_expediente;

                                //  addHours
                                //  subHours

                                $inicio_expediente  = $inicio_expediente->subHours(3);
                                $final_expediente   = $final_expediente->addHours(3);// 2012-02-04 00:00:00

                                $inicio_expediente   = $inicio_expediente->toTimeString();
                                $final_expediente   = $final_expediente->toTimeString(); //14:15:16

                                // dd('hora '. $final_expediente);

                                $eventos = DB::table('alarmes')
                                    ->where('mcu', '=', $registro->mcu)
                                    ->whereYear('data', $year)
                                    ->whereMonth('data',  $month)
                                    ->whereTime('hora', '>', $inicio_expediente)
                                    ->whereTime('hora', '<', $final_expediente)
                                    ->whereNotIn('diaSemana', [0])
                                    ->select(
                                                'alarmes.*'
                                    )
                                    ->orderBy('data' ,'asc')
                                    ->orderBy('hora' ,'asc')
                                ->get();

                                if(! $eventos->isEmpty())
                                {
                                    $counteventoshe = $eventos->count('data');
                                }
                                else
                                {
                                    $counteventoshe = 0;
                                }

                                if( $counteventoshe == 0){
                                    $situacao = 'Provento registrado em período e horários que não houve registro de Arme/Desarme do Sistema de Alarme.';
                                }else{
                                    $rowhe=0;
                                    foreach ($eventos  as $evento){
                                        $rowhe++;
                                    }
                                    $situacao = null;
                                //    $situacao = 'Provento registrado em período que não houve registro de Desarme do Sistema de Alarme para '.intval($adicional->qtd/2) .' ocorrencias.' ;
                            }

                        }

                        if(($adicional-> rubrica == 'Trabalho Fins Semana' )&&($pgtoAdicionaisTemp->ref > '202008')){
                           $situacao = 'O  Acórdão do Dissídio Coletivo 2020/2021, vigente a partir de 01/08/2020, não prevê a manutenção do pagamento do Adicional de Fim de Semana.';
                        }

                        //dd($situacao);

                        if (!$situacao==null)
                        {
                            $pgtoAdicionaisTemp = new PgtoAdicionaisTemp();
                            $pgtoAdicionaisTemp->sto = $registro->sto;
                            $pgtoAdicionaisTemp->mcu = $registro->mcu;
                            $pgtoAdicionaisTemp->codigo = $registro->codigo;
                            $pgtoAdicionaisTemp->numeroGrupoVerificacao = $registro->numeroGrupoVerificacao;
                            $pgtoAdicionaisTemp->numeroDoTeste = $registro->numeroDoTeste;
                            $pgtoAdicionaisTemp->matricula = $adicional->matricula;
                            $pgtoAdicionaisTemp->cargo = $adicional->cargo;
                            $pgtoAdicionaisTemp->rubrica = $adicional->rubrica;
                            $pgtoAdicionaisTemp->ref = $adicional->ref;
                            $pgtoAdicionaisTemp->quantidade = $adicional->qtd/2;
                            $pgtoAdicionaisTemp->valor = $adicional->valor;
                            $pgtoAdicionaisTemp->situacao = $situacao;
                            $pgtoAdicionaisTemp->save();
                            $situacao=null;
                        }

                    }

                    $pgtoAdicionais = DB::table('pgto_adicionais_temp')
                        ->where('sto',  '=', $registro->sto)
                        ->where('mcu',  '=', $registro->mcu)
                        ->where('codigo',  '=', $registro->codigo)
                        ->where('numeroGrupoVerificacao',  '=', $registro->numeroGrupoVerificacao)
                        ->where('numeroDoTeste',  '=', $registro->numeroDoTeste)
                        ->select(
                                    'pgto_adicionais_temp.*'
                        )
                    ->get();

                    $total == 0.00;
                    $count == 0;

                    if(! $pgtoAdicionais->isEmpty()) {
                        $total = $pgtoAdicionais->sum('valor');
                        $count = $pgtoAdicionais->count('matricula');
                    }
                    else
                    {
                        $total=0.00;
                        $count = 0;
                    }

                }
               //dd($pgtoAdicionais);
                return view('compliance.inspecao.editar',compact
                (
                    'registro'
                    , 'id'
                    , 'total'
                    , 'dtini'
                    , 'count'
                    , 'pgtoAdicionais'
                    , 'counteventostfs'
                    , 'reffinal'
                ));
            }

            if (($registro->numeroGrupoVerificacao==278) && ($registro->numeroDoTeste==2))
            {
                $dtmenos4meses = new Carbon();
                $dtmenos4meses->subMonth(4);
                $ref = substr($dtmenos4meses,0,4). substr($dtmenos4meses,5,2);
                $count_atend = 0;
                $count_dist = 0;
                $count = 0;
                $refini = DB::table('pagamentos_adicionais')
                    ->select( 'pagamentos_adicionais.ref' )
                    ->where('ref', '>=', $ref)
                ->get();

                $dtini = $refini->min('ref');
                $dtfim = $refini->max('ref');

                switch ($registro->se)
                {
                    case 1 :{ $superintendência = 'CS'; } break;
                    case 4 :{ $superintendência = 'AL'; } break;
                    case 6 :{ $superintendência = 'AM'; } break;
                    case 8 :{ $superintendência = 'BA'; } break;
                    case 10 :{ $superintendência = 'BSB'; } break;
                    case 12 :{ $superintendência = 'CE'; } break;
                    case 14 :{ $superintendência = 'ES'; } break;
                    case 16 :{ $superintendência = 'GO'; } break;
                    case 18 :{ $superintendência = 'MA'; } break;
                    case 20 :{ $superintendência = 'MG'; } break;
                    case 22 :{ $superintendência = 'MS'; } break;
                    case 24 :{ $superintendência = 'MT'; } break;
                    case 26 :{ $superintendência = 'RO'; } break;
                    case 28 :{ $superintendência = 'PA'; } break;
                    case 30 :{ $superintendência = 'PB'; } break;
                    case 32 :{ $superintendência = 'PE'; } break;
                    case 34 :{ $superintendência = 'PI'; } break;
                    case 36 :{ $superintendência = 'PR'; } break;
                    case 50 :{ $superintendência = 'RJ'; } break;
                    case 60 :{ $superintendência = 'RN'; } break;
                    case 64 :{ $superintendência = 'RS'; } break;
                    case 68 :{ $superintendência = 'SC'; } break;
                    case 72 :{ $superintendência = 'SPM'; } break;
                    case 74 :{ $superintendência = 'SPI'; } break;
                    case 75 :{ $superintendência = 'TO'; } break;
                }

                $pagamentos_adicionais_dist = DB::table('pagamentos_adicionais')
                   ->select( 'pagamentos_adicionais.*' )
                   ->where([['pagamentos_adicionais.se', '>=', 'SE/'.$superintendência ]])
                   ->where([['pagamentos_adicionais.sigla_lotacao', 'like', '%' . trim($registro->descricao) . '%' ]])
                   ->where([['pagamentos_adicionais.ref', '>=', $ref ]])
                   ->where([['pagamentos_adicionais.rubrica', '=',  'AADC-Adic.Ativ. Distrib/Coleta Ext.' ]])
                   ->get();
                if(! $pagamentos_adicionais_dist->isEmpty())
                {
                    $count_dist = $pagamentos_adicionais_dist->count('sigla_lotacao');
                }
                else
                {
                    $count_dist = 0;
                }
                if( $count_dist >= 1)
                {
                    DB::table('pgtoAdicionaisTemp')
                    ->where('codigo', '=', $registro->codigo)
                    ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                    ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                    ->delete(); // limpa dados anteriores existentes do empregado da tabela temporária
                }

                foreach ($pagamentos_adicionais_dist  as $adicionais)
                {
                    $situacao="Sem eventos de Distribuição Domiciliária.";
                    $mes = intval(substr($adicionais->ref,4,2));

                    $sgdo_distribuicao = DB::table('sgdo_distribuicao')
                        ->select('sgdo_distribuicao.*')
                        ->where([[ 'mcu', '>=', $registro->mcu ]])
                        ->where([[ 'matricula', '=', $adicionais->matricula ]])
                        ->whereMonth('data_termino_atividade', $mes)
                    ->get();

                    if(! $sgdo_distribuicao->isEmpty())
                    {
                        $count_sgdo = $sgdo_distribuicao->count('matricula');
                    }
                    else
                    {
                        $count_sgdo = 0;
                    }

                    if(! $sgdo_distribuicao->isEmpty())
                    {
                        $pgtoAdicionaisTemp = new PgtoAdicionaisTemp();
                        $pgtoAdicionaisTemp->sto = $registro->sto;
                        $pgtoAdicionaisTemp->mcu = $registro->mcu;
                        $pgtoAdicionaisTemp->codigo = $registro->codigo;
                        $pgtoAdicionaisTemp->numeroGrupoVerificacao = $registro->numeroGrupoVerificacao;
                        $pgtoAdicionaisTemp->numeroDoTeste = $registro->numeroDoTeste;
                        $pgtoAdicionaisTemp->matricula = $adicionais->matricula;
                        $pgtoAdicionaisTemp->cargo = $adicionais->cargo;
                        $pgtoAdicionaisTemp->rubrica = $adicionais->rubrica;
                        $pgtoAdicionaisTemp->ref = $adicionais->ref;
                        $pgtoAdicionaisTemp->valor = $adicionais->valor;
                        $pgtoAdicionaisTemp->situacao = $situacao;

                        $ferias_por_mcu = DB::table('ferias_por_mcu')
                        ->select('ferias_por_mcu.*')
                        ->where([[ 'matricula', '=', $adicionais->matricula ]])
                        ->whereMonth('inicio_fruicao', $mes-1)
                        ->whereYaer('inicio_fruicao', $registro->ciclo)
                        ->count();
                        if ($ferias_por_mcu == 0){
                            $pgtoAdicionaisTemp->save();
                        }else{
                            unset($pgtoAdicionaisTemp);
                        }

                    }
                }

                $pagamentos_adicionais_atend = DB::table('pagamentos_adicionais')
                   ->select( 'pagamentos_adicionais.*' )
                   ->where([['pagamentos_adicionais.se', '>=', 'SE/'.$superintendência ]])
                   ->where([['pagamentos_adicionais.sigla_lotacao', 'like', '%' . trim($registro->descricao) . '%' ]])
                   ->where([['pagamentos_adicionais.ref', '>=', $ref ]])
                   ->where([['pagamentos_adicionais.rubrica', '=',  'AAG - Adic. de Atend. em Guichê' ]])
                   ->get();

                if(! $pagamentos_adicionais_atend->isEmpty())
                {
                    $count_atend = $pagamentos_adicionais_atend->count('matricula');
                }
                else
                {
                    $count_atend = 0;
                }

                foreach ($pagamentos_adicionais_atend  as $adicionais)
                {

                    $situacao="Sem eventos de atendimento a clientes.";
                    $mes = intval(substr($adicionais->ref,4,2));

                    $bdf_fat_02 = DB::table('bdf_fat_02')
                        ->select('bdf_fat_02.*')
                        ->where([[ 'cd_orgao', '>=', $registro->sto ]])
                        ->where([[ 'atendimento', '=', $adicionais->matricula ]])
                        ->whereMonth('dt_mov', $mes)
                        ->get();
                    if( ! $bdf_fat_02->isEmpty() ){
                        $pgtoAdicionaisTemp = new PgtoAdicionaisTemp();
                        $pgtoAdicionaisTemp->sto = $registro->sto;
                        $pgtoAdicionaisTemp->mcu = $registro->mcu;
                        $pgtoAdicionaisTemp->codigo = $registro->codigo;
                        $pgtoAdicionaisTemp->numeroGrupoVerificacao = $registro->numeroGrupoVerificacao;
                        $pgtoAdicionaisTemp->numeroDoTeste = $registro->numeroDoTeste;
                        $pgtoAdicionaisTemp->matricula = $adicionais->matricula;
                        $pgtoAdicionaisTemp->cargo = $adicionais->cargo;
                        $pgtoAdicionaisTemp->rubrica = $adicionais->rubrica;
                        $pgtoAdicionaisTemp->ref = $adicionais->ref;
                        $pgtoAdicionaisTemp->valor = $adicionais->valor;
                        $pgtoAdicionaisTemp->situacao = $situacao;

                        $ferias_por_mcu = DB::table('ferias_por_mcu')
                        ->select('ferias_por_mcu.*')
                        ->where([[ 'matricula', '=', $adicionais->matricula ]])
                        ->whereMonth('inicio_fruicao', $mes-1)
                        ->whereYear('inicio_fruicao', $registro->ciclo)
                        ->first();

                        if ($ferias_por_mcu->isEmpty())
                        {
                            $pgtoAdicionaisTemp->save();
                        }else{
                            unset($pgtoAdicionaisTemp);
                        }
                    }
                }
                $total=0.00;
                if (( $count_atend >= 1 ) || ( $count_dist >= 1 ))
                {
                    $pgtoAdicionais = DB::table('pgto_adicionais_temp')
                    ->where('sto',  '=', $registro->sto)
                    ->where('mcu',  '=', $registro->mcu)
                    ->where('codigo',  '=', $registro->codigo)
                    ->where('numeroGrupoVerificacao',  '=', $registro->numeroGrupoVerificacao)
                    ->where('numeroDoTeste',  '=', $registro->numeroDoTeste)
                    ->select(
                                'pgto_adicionais_temp.*'
                    )
                    ->get();
                    $total=$pgtoAdicionais->sum('valor');
                    $count = $pgtoAdicionais->count('matricula');
                }
                else
                {
                    $pgtoAdicionais = '';
                }
                return view('compliance.inspecao.editar',compact
                (
                    'registro'
                    , 'id'
                    , 'total'
                    , 'pagamentos_adicionais_dist'
                    , 'pagamentos_adicionais_atend'
                    , 'dtini'
                    , 'dtfim'
                    , 'pgtoAdicionais'
                    , 'count'
                ));
            }
        $total=0.00;
        return view('compliance.inspecao.editar',compact('registro','id','total'));
    }



    public function search (Request $request )
    {
        //$dados = $request->all();

        if(($request->all()['gruposdeverificacao']==NULL) && ($request->all()['situacao']==NULL) && ($request->all()['search']==NULL))
        {
            \Session::flash('mensagem',['msg'=>'Para Filtrar ao menos uma opção é necessária.'
            ,'class'=>'red white-text']);
            return redirect()->back();
        }else if(($request->all()['gruposdeverificacao']!=NULL) && ($request->all()['situacao']==NULL) && ($request->all()['search']==NULL))
        {
        // dd($dados);
            $registros = DB::table('itensdeinspecoes')
            ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
            ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
            ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
            ->select('itensdeinspecoes.*'
                    ,'gruposdeverificacao.numeroGrupoVerificacao'
                    ,'gruposdeverificacao.nomegrupo'
                    ,'testesdeverificacao.numeroDoTeste'
                    ,'testesdeverificacao.teste'
            )
            ->where([['inspecao_id', '=', $request->all()['id']]])
          //  ->where([['testesdeverificacao.teste', 'LIKE', '%' . $request->all()['search'] .'%' ]])
            ->Where([['itensdeinspecoes.grupoVerificacao_id', '=', $request->all()['gruposdeverificacao']]])
           // ->Where([['itensdeinspecoes.status', '=', $request->all()['status']]])
           ->orderBy('itensdeinspecoes.testeVerificacao_id' , 'asc')
           ->paginate(10);

         //  \Session::flash('mensagem',['msg'=>'Filtro Aplicado! Grupo de Verificação'
         //   ,'class'=>'orange white-text']);

        }else if(($request->all()['gruposdeverificacao']!=NULL) && ($request->all()['situacao']!=NULL) && ($request->all()['search']==NULL))
        {
            //dd($dados);
            $registros = DB::table('itensdeinspecoes')
            ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
            ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
            ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
            ->select('itensdeinspecoes.*'
                    ,'gruposdeverificacao.numeroGrupoVerificacao'
                    ,'gruposdeverificacao.nomegrupo'
                    ,'testesdeverificacao.numeroDoTeste'
                    ,'testesdeverificacao.teste'
            )
            ->where([['inspecao_id', '=', $request->all()['id']]])
          //  ->where([['testesdeverificacao.teste', 'LIKE', '%' . $request->all()['search'] .'%' ]])
            ->Where([['itensdeinspecoes.grupoVerificacao_id', '=', $request->all()['gruposdeverificacao']]])
            ->Where([['itensdeinspecoes.situacao', '=', $request->all()['situacao']]])
            ->orderBy('itensdeinspecoes.testeVerificacao_id' , 'asc')
           ->paginate(10);
           \Session::flash('mensagem',['msg'=>'Filtro Aplicado! Grupo de Verificação e Status'
            ,'class'=>'orange white-text']);

        }else if(($request->all()['gruposdeverificacao']==NULL) && ($request->all()['situacao']!=NULL) && ($request->all()['search']==NULL))
        {
           // dd($dados);
            $registros = DB::table('itensdeinspecoes')
            ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
            ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
            ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
            ->select('itensdeinspecoes.*'
                    ,'gruposdeverificacao.numeroGrupoVerificacao'
                    ,'gruposdeverificacao.nomegrupo'
                    ,'testesdeverificacao.numeroDoTeste'
                    ,'testesdeverificacao.teste'
            )
            ->where([['inspecao_id', '=', $request->all()['id']]])
          //  ->where([['testesdeverificacao.teste', 'LIKE', '%' . $request->all()['search'] .'%' ]])
           // ->Where([['itensdeinspecoes.grupoinspecao_id', '=', $request->all()['gruposdeverificacao']]])
            ->Where([['itensdeinspecoes.situacao', '=', $request->all()['situacao']]])
            ->orderBy('itensdeinspecoes.testeVerificacao_id' , 'asc')
            ->paginate(10);
            \Session::flash('mensagem',['msg'=>'Filtro Aplicado! Status'
            ,'class'=>'orange white-text']);

        }else if(($request->all()['gruposdeverificacao']==NULL) && ($request->all()['situacao']==NULL) && ($request->all()['search']!=NULL))
        {
           // dd($dados);
            $registros = DB::table('itensdeinspecoes')
            ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
            ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
            ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
            ->select('itensdeinspecoes.*'
                    ,'gruposdeverificacao.numeroGrupoVerificacao'
                    ,'gruposdeverificacao.nomegrupo'
                    ,'testesdeverificacao.numeroDoTeste'
                    ,'testesdeverificacao.teste'
            )
            ->where([['inspecao_id', '=', $request->all()['id']]])
            ->where([['testesdeverificacao.teste', 'LIKE', '%' . $request->all()['search'] .'%' ]])
           // ->Where([['itensdeinspecoes.grupoVerificacao_id', '=', $request->all()['gruposdeverificacao']]])
          //  ->Where([['itensdeinspecoes.status', '=', $request->all()['status']]])
          ->orderBy('itensdeinspecoes.testeVerificacao_id' , 'asc')
            ->paginate(10);
            \Session::flash('mensagem',['msg'=>'Filtro Aplicado! Descrição'
            ,'class'=>'orange white-text']);

        }else{
            //dd($dados);
            $registros = DB::table('itensdeinspecoes')
            ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
            ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
            ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
            ->select('itensdeinspecoes.*'
                    ,'gruposdeverificacao.numeroGrupoVerificacao'
                    ,'gruposdeverificacao.nomegrupo'
                    ,'testesdeverificacao.numeroDoTeste'
                    ,'testesdeverificacao.teste'
            )
            ->where([['inspecao_id', '=', $request->all()['id']]])
            ->where([['testesdeverificacao.teste', 'LIKE', '%' . $request->all()['search'] .'%' ]])
            ->Where([['itensdeinspecoes.grupoVerificacao_id', '=', $request->all()['gruposdeverificacao']]])
            ->Where([['itensdeinspecoes.situacao', '=', $request->all()['situacao']]])
            ->orderBy('itensdeinspecoes.testeVerificacao_id' , 'asc')
            //->orderBy('gruposdeverificacao.numeroGrupoVerificacao', 'asc','itensdeinspecoes.id' , 'asc')
           ->paginate(10);

            \Session::flash('mensagem',['msg'=>'Filtro Aplicado!  Grupo de Verificação, Descrição e Status'
            ,'class'=>'orange white-text']);
           // return redirect()->back();
        }

        $inspecao = Inspecao::find($request->all()['id']);

        $gruposdeverificacao = DB::table('gruposdeverificacao')
        ->select('gruposdeverificacao.*')
        ->where([['tipoUnidade_id', '=', $inspecao['tipoUnidade_id']]])
        ->where([['tipoVerificacao', '=', $inspecao['tipoVerificacao']]])
        ->get();

        $dado = DB::table('itensdeinspecoes')
            ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
            ->join('unidades', 'itensdeinspecoes.unidade_id', '=', 'unidades.id')
            ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
            ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
            ->select('itensdeinspecoes.*','inspecoes.*','unidades.*','testesdeverificacao.*','gruposdeverificacao.*')
            ->where([['inspecoes.id', '=', $request->all()['id'] ]])
            ->first();

        return view('compliance.inspecao.index',compact('inspecao','registros','gruposdeverificacao','dado'));
    }

    public function index($id)  {

        $inspecao = Inspecao::find($id);

        $registros = DB::table('itensdeinspecoes')
            ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
            ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
            ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
            ->select('inspecoes.*'
                , 'itensdeinspecoes.*'
                ,'gruposdeverificacao.numeroGrupoVerificacao'
                ,'gruposdeverificacao.nomegrupo'
                ,'testesdeverificacao.numeroDoTeste'
                ,'testesdeverificacao.teste'
            )
            ->where([['inspecao_id', '=', $id]])
            ->where([['situacao', '=', 'Em Inspeção' ]])
            ->orderBy('itensdeinspecoes.testeVerificacao_id' , 'asc')
        ->paginate(10);

        $count = $registros->count('situacao');

        if($count == 0)
        {
            $registros = DB::table('itensdeinspecoes')
                ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
                ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
                ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
                ->select('inspecoes.*'
                    , 'itensdeinspecoes.*'
                    ,'gruposdeverificacao.numeroGrupoVerificacao'
                    ,'gruposdeverificacao.nomegrupo'
                    ,'testesdeverificacao.numeroDoTeste'
                    ,'testesdeverificacao.teste'
                )
                ->where([['inspecao_id', '=', $id]])
                ->Where([['situacao', '=', 'Inspecionado' ]])
                ->orderBy('itensdeinspecoes.testeVerificacao_id' , 'asc')
            ->paginate(10);
            $count = $registros->count('situacao');
        }

        if($count == 0)
        {
            $registros = DB::table('itensdeinspecoes')
                ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
                ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
                ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
                ->select('inspecoes.*'
                    , 'itensdeinspecoes.*'
                    ,'gruposdeverificacao.numeroGrupoVerificacao'
                    ,'gruposdeverificacao.nomegrupo'
                    ,'testesdeverificacao.numeroDoTeste'
                    ,'testesdeverificacao.teste'
                )
                ->where([['inspecao_id', '=', $id]])
                ->Where([['situacao', '!=', 'Corroborado' ]])
                ->orderBy('itensdeinspecoes.testeVerificacao_id' , 'asc')
            ->get();
            $count = $registros->count('situacao');
        }
        if($count == 0)
        {

            $inspecoes = Inspecao::find( $id );
            $inspecoes->status = 'Inspecionado';
            $inspecoes->save();
            return redirect()-> route('compliance.verificacoes');
        }

        $gruposdeverificacao = DB::table('gruposdeverificacao')
            ->select('gruposdeverificacao.*')
            ->where([['tipoUnidade_id', '=', $inspecao['tipoUnidade_id']]])
            ->where([['tipoVerificacao', '=', $inspecao['tipoVerificacao']]])
        ->get();

        $dado = DB::table('itensdeinspecoes')
            ->join('inspecoes', 'itensdeinspecoes.inspecao_id', '=', 'inspecoes.id')
            ->join('unidades', 'itensdeinspecoes.unidade_id', '=', 'unidades.id')
            ->join('testesdeverificacao', 'itensdeinspecoes.testeVerificacao_id', '=', 'testesdeverificacao.id')
            ->join('gruposdeverificacao', 'itensdeinspecoes.grupoVerificacao_id', '=', 'gruposdeverificacao.id')
            ->select('itensdeinspecoes.*','inspecoes.*','unidades.*','testesdeverificacao.*','gruposdeverificacao.*')
            ->where([['inspecoes.id', '=', $id ]])
            ->first();

        return view('compliance.inspecao.index',compact('inspecao','registros','gruposdeverificacao','dado'));
    }

    public function transformDate($value, $format = 'Y-m-d') {
        try {
            return \Carbon\Carbon::instance(
            \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));

        } catch (\ErrorException $e)
        {
             return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }

    public function transformTime($value, $format = 'H:i:s') {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));

        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }

    public function corroborar($id)
    {
        $now = Carbon::now();
        $now->format('d-m-Y H:i:s');
        $inspecao = Inspecao::find($id);

        $registros = DB::table('itensdeinspecoes')
            ->select('itensdeinspecoes.*'

            )
            ->where([['inspecao_id', '=', $id]])
            ->where([['situacao', '=', 'Inspecionado' ]])
        ->get();

        $count = $registros->count('situacao'=='Inspecionado');

        if($count >= 1){
            foreach ($registros as $registro){
                $dado = Itensdeinspecao::find($registro->id);
                $dado->eventosSistema =
                    "Corroborado por: ".Auth::user()->name." em ".$now
                    ."\n"
                    .$registro->eventosSistema;
                 $dado->situacao = 'Corroborado' ;
                $dado->save();
            }

            $inspecao->status = 'Inspecionado';
            $inspecao->eventoInspecao = $inspecao->eventoInspecao . "\r\n".'Inspecionado, inspeção concluida por '.Auth::user()->name." em ".\Carbon\Carbon::parse($now)->format( 'd/m/Y' );
            $inspecao->save();
        }
        \Session::flash('mensagem',['msg'=>'Inspeção Concluida!'
            ,'class'=>'blue white-text']);
        return redirect()-> route('compliance.verificacoes');
    }
}
