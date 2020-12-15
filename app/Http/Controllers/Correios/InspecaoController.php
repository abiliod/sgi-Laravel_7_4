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
//use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;



use Carbon\Carbon;
use Carbon\CarbonPeriod;
use mysql_xdevapi\Exception;


class InspecaoController extends Controller
{

    public function exportLancamentosSRO  ($codigo)
    {
       // dd($codigo);
        //$exportLancamentosSRO = new ExportLancamentosSRO;
        $exportLancamentosSRO  =  DB::table('lancamentossro')
            ->where('codigo', '=', $codigo)
            ->where('numeroGrupoVerificacao', '=', 277)
            ->where('numeroDoTeste', '=', 3)
            ->where('estado', '=',  'Pendente')
            ->get();

        //$exportLancamentosSRO =  ExportLancamentosSRO::where('codigo', $codigo)->get();
        return $exportLancamentosSRO->download('lancamentossro.xlsx');



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

            if(($registro->numeroGrupoVerificacao==270)&&($registro->numeroDoTeste==1))
            {
                $competencia = DB::table('debitoempregados')
                ->select('competencia')
                ->where([['debitoempregados.id', '=', 1 ]])
                ->get();

                $debitoempregados = DB::table('debitoempregados')
                ->select('data', 'documento', 'historico', 'matricula', 'valor' )
                ->where([['debitoempregados.data', '<=', $dtmenos90dias ]])
                ->where([['debitoempregados.sto', '=', $registro->mcu ]])
                ->orWhere([['debitoempregados.sto', '=', $registro->sto ]])
                ->get();
                $count = $debitoempregados->count('matricula');
            //var_dump($debitoempregados );
                $total = $debitoempregados->sum('valor'); // soma a coluna valor da coleção de dados
                return view('compliance.inspecao.editar',compact(
                    'registro'
                    ,'id'
                    , 'debitoempregados'
                    ,'total'
                    , 'competencia'
                    ,'count'

                ));
            }

            if(($registro->numeroGrupoVerificacao==270)&&($registro->numeroDoTeste==2))
            {
                $countproters_con =0;
                $countproters_peso =0;
                $countproters_cep =0;

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
                 ->where([['nome_da_unidade', '=', $registro->descricao]])
                 ->where([['tipo_de_pendencia', '=', 'CON']])
                 ->where([['data_da_postagem', '<=', $dtmenos90dias ]])
                 ->get();

                if(!empty($proters_con))  $countproters_con = $proters_con->count('no_do_objeto');

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
                 ->where([['nome_da_unidade', '=', $registro->descricao]])
                 ->where([['tipo_de_pendencia', '=', 'DPC']])
                 ->where([['divergencia_peso', '=', 'S']])
                 ->where([['data_da_postagem', '<=', $dtmenos90dias ]])
                 ->get();


                if(!empty($proters_peso))  $countproters_peso = $proters_peso->count('no_do_objeto');



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
                 ->where([['nome_da_unidade', '=', $registro->descricao]])
                 ->where([['tipo_de_pendencia', '=', 'DPC']])
                 ->where([['divergencia_cep', '=', 'S']])
                 ->where([['data_da_postagem', '<=', $dtmenos90dias ]])
                 ->get();



                if(!empty($proters_cep))  $countproters_cep = $proters_cep->count('no_do_objeto');



                if (empty($proters_cep)) {"vazio";}else
                 {
                    $total_proters_cep  = $proters_cep->sum('diferenca_a_recolher');
                    $total=$total_proters_cep;
                 }
                 if (empty($proters_peso)) {"vazio";}else
                 {
                     $total_proters_peso  = $proters_peso->sum('diferenca_a_recolher');
                     $total=$total_proters_peso;
                 }

                 if((isset($total_proters_cep)) && (isset($total_proters_peso)))
                 {
                    $total=$total_proters_peso + $total_proters_cep;
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


            if(($registro->numeroGrupoVerificacao==270)&&($registro->numeroDoTeste==3)) {
                $dtmenos90dias = new Carbon();
                $dtmenos90dias->subDays(90);

                $smb_bdf_naoconciliados = DB::table('smb_bdf_NaoConciliados')

                    ->select(
                        'smb_bdf_NaoConciliados.*'

                    )
                    ->where([['smb_bdf_NaoConciliados.Agencia', '=', $registro->mcu]])
                    ->where([['smb_bdf_NaoConciliados.Divergencia', '!=', 0]])
                    ->where([['smb_bdf_NaoConciliados.Status', '=', 'Pendente']])
                    ->where([['smb_bdf_NaoConciliados.Data', '>=', $dtmenos90dias ]])
                    ->orderBy('Data' ,'asc')
                    ->get();

                    $periodo = DB::table('smb_bdf_NaoConciliados')
                    ->select(
                        'smb_bdf_NaoConciliados.*'

                    )
                    ->where([['smb_bdf_NaoConciliados.Data', '>=', $dtmenos90dias ]])
                  //  ->orderBy('Data' ,'asc')
                    ->get();
                    $dtini = $periodo->min('Data');
                  //  dd( $dtini );

                    $dtfim = $periodo->max('Data');

               // var_dump($periodo);
              //  dd();
                $total=0.00;
                $total  = $smb_bdf_naoconciliados->sum('Divergencia'); // soma a coluna valor da coleção de dados

                return view('compliance.inspecao.editar',compact(
                    'registro','id','smb_bdf_naoconciliados','total','dtini','dtfim'
                    ));
            }

            if(($registro->numeroGrupoVerificacao==270)&&($registro->numeroDoTeste==4))
            {


                $sl02bdfs01 = DB::table('sl02bdfs')
                ->where('orgao', '=', $registro->descricao)
                ->where('diferenca', '>', 0)
                ->whereYear('dt_movimento', $registro->ciclo)
                ->whereMonth('dt_movimento', 1)
                ->get();

                $sl02bdfs02 = DB::table('sl02bdfs')
                ->where('orgao', '=', $registro->descricao)
                ->where('diferenca', '>', 0)
                ->whereYear('dt_movimento', $registro->ciclo)
                ->whereMonth('dt_movimento', 2)
                ->get();

                $sl02bdfs03 = DB::table('sl02bdfs')
                ->where('orgao', '=', $registro->descricao)
                ->where('diferenca', '>', 0)
                ->whereYear('dt_movimento', $registro->ciclo)
                ->whereMonth('dt_movimento', 3)
                ->get();

                $sl02bdfs04 = DB::table('sl02bdfs')
                ->where('orgao', '=', $registro->descricao)
                ->where('diferenca', '>', 0)
                ->whereYear('dt_movimento', $registro->ciclo)
                ->whereMonth('dt_movimento', 4)
                ->get();

                $sl02bdfs05 = DB::table('sl02bdfs')
                ->where('orgao', '=', $registro->descricao)
                ->where('diferenca', '>', 0)
                ->whereYear('dt_movimento', $registro->ciclo)
                ->whereMonth('dt_movimento', 5)
                ->get();
//var_dump($sl02bdfs05);
                $sl02bdfs06 = DB::table('sl02bdfs')
                ->where('orgao', '=', $registro->descricao)
                ->where('diferenca', '>', 0)
                ->whereYear('dt_movimento', $registro->ciclo)
                ->whereMonth('dt_movimento', 6)
                ->get();

                $sl02bdfs07 = DB::table('sl02bdfs')
                ->where('orgao', '=', $registro->descricao)
                ->where('diferenca', '>', 0)
                ->whereYear('dt_movimento', $registro->ciclo)
                ->whereMonth('dt_movimento', 7)
                ->get();

                $sl02bdfs08 = DB::table('sl02bdfs')
                ->where('orgao', '=', $registro->descricao)
                ->where('diferenca', '>', 0)
                ->whereYear('dt_movimento', $registro->ciclo)
                ->whereMonth('dt_movimento', 8)
                ->get();

                $sl02bdfs09 = DB::table('sl02bdfs')
                ->where('orgao', '=', $registro->descricao)
                ->where('diferenca', '>', 0)
                ->whereYear('dt_movimento', $registro->ciclo)
                ->whereMonth('dt_movimento', 9)
                ->get();

                $sl02bdfs10 = DB::table('sl02bdfs')
                ->where('orgao', '=', $registro->descricao)
                ->where('diferenca', '>', 0)
                ->whereYear('dt_movimento', $registro->ciclo)
                ->whereMonth('dt_movimento', 10)
                ->get();

                $sl02bdfs11 = DB::table('sl02bdfs')
                ->where('orgao', '=', $registro->descricao)
                ->where('diferenca', '>', 0)
                ->whereYear('dt_movimento', $registro->ciclo)
                ->whereMonth('dt_movimento', 11)
                ->get();

                $sl02bdfs12 = DB::table('sl02bdfs')
                ->where('orgao', '=', $registro->descricao)
                ->where('diferenca', '>', 0)
                ->whereYear('dt_movimento', $registro->ciclo)
                ->whereMonth('dt_movimento', 12)
                ->get();


                if(!empty($sl02bdfs01))
                {
                    $acumuladoMes1  = $sl02bdfs01->sum('diferenca'); // soma a coluna valor da coleção de dados
                    $ocorrencias1  = $sl02bdfs01->count('diferenca');
                    if($ocorrencias1==0){
                        $media1=0;
                      }else{
                        $media1 =   $acumuladoMes1/$ocorrencias1;
                      }
                    $limite =$sl02bdfs01->max('limite');
                    if ($limite==0) $limite=1;
                    $porcentagem1 = $media1 / $limite;
                    $mes_Ano01 = '01-' . $registro->ciclo;

                }
                else
                {
                    $acumuladoMes1=0;
                    $ocorrencias1=0;
                    $media1=0;
                    $porcentagem1=0;
                }
                if(!empty($sl02bdfs02))
                {
                    $acumuladoMes2  = $sl02bdfs02->sum('diferenca'); // soma a coluna valor da coleção de dados
                    $ocorrencias2  = $sl02bdfs02->count('diferenca');
                    if($ocorrencias2==0){
                        $media2=0;
                      }else{
                        $media2 =   $acumuladoMes2/$ocorrencias2;
                      }
                    $limite =$sl02bdfs02->max('limite');
                    if ($limite==0) $limite=1;
                    $porcentagem2 = $media2 / $limite;
                    $mes_Ano02 = '02-' . $registro->ciclo;
                }
                else
                {
                    $acumuladoMes2=0;
                    $ocorrencias2=0;
                    $media2=0;
                    $porcentagem2=0;
                }
                if(!empty($sl02bdfs03))
                {
                    $acumuladoMes3  = $sl02bdfs03->sum('diferenca'); // soma a coluna valor da coleção de dados
                    $ocorrencias3  = $sl02bdfs03->count('diferenca');
                    if($ocorrencias3==0){
                        $media3=0;
                      }else{
                        $media3 =   $acumuladoMes3/$ocorrencias3;
                      }
                    $limite =$sl02bdfs03->max('limite');
                    if ($limite==0) $limite=1;
                    $porcentagem3 = $media3 / $limite;
                    $mes_Ano03 = '03-' . $registro->ciclo;
                }
                else
                {
                    $acumuladoMes3=0;
                    $ocorrencias3=0;
                    $media3=0;
                    $porcentagem3=0;
                }

                if(!empty($sl02bdfs04))
                {
                    $acumuladoMes4  = $sl02bdfs04->sum('diferenca'); // soma a coluna valor da coleção de dados
                    $ocorrencias4  = $sl02bdfs04->count('diferenca');
                    if($ocorrencias4==0){
                        $media4=0;
                      }else{
                        $media4 =   $acumuladoMes4/$ocorrencias4;
                      }
                    $limite =$sl02bdfs04->max('limite');
                    if ($limite==0) $limite=1;
                    $porcentagem4 = $media4 / $limite;
                    $mes_Ano04 = '04-' . $registro->ciclo;
                }
                else
                {
                    $acumuladoMes4=0;
                    $ocorrencias4=0;
                    $media4=0;
                    $porcentagem4=0;
                }

                if(!empty($sl02bdfs05))
                {
                    $acumuladoMes5  = $sl02bdfs05->sum('diferenca'); // soma a coluna valor da coleção de dados
                    $ocorrencias5  = $sl02bdfs05->count('diferenca');
                    if($ocorrencias5==0){
                        $media5=0;
                      }else{
                        $media5 =   $acumuladoMes5/$ocorrencias5;
                      }
                    $limite =$sl02bdfs05->max('limite');
                    if ($limite==0) $limite=1;
                    $porcentagem5 = $media5 / $limite;
                    $mes_Ano05 = '05-' . $registro->ciclo;
                }
                else
                {
                    $acumuladoMes5=0;
                    $ocorrencias5=0;
                    $media5=0;
                    $porcentagem5=0;
                }

                if(!empty($sl02bdfs06))
                {
                    $acumuladoMes6  = $sl02bdfs06->sum('diferenca'); // soma a coluna valor da coleção de dados
                    $ocorrencias6  = $sl02bdfs06->count('diferenca');
                    if($ocorrencias6==0){
                        $media6=0;
                      }else{
                        $media6 =   $acumuladoMes6/$ocorrencias6;
                      }
                    $limite =$sl02bdfs06->max('limite');
                    if ($limite==0) $limite=1;
                    $porcentagem6 = $media6 / $limite;
                    $mes_Ano06 = '06-' . $registro->ciclo;
                }
                else
                {
                    $acumuladoMes6=0;
                    $ocorrencias6=0;
                    $media6=0;
                    $porcentagem6=0;
                }


                if(!empty($sl02bdfs07))
                {
                    $acumuladoMes7  = $sl02bdfs07->sum('diferenca'); // soma a coluna valor da coleção de dados
                    $ocorrencias7  = $sl02bdfs07->count('diferenca');
                    if($ocorrencias7==0){
                        $media7=0;
                      }else{
                        $media7 =   $acumuladoMes7/$ocorrencias7;
                      }
                    $limite =$sl02bdfs07->max('limite');
                    if ($limite==0) $limite=1;
                    $porcentagem7 = $media7 / $limite;
                    $mes_Ano07 = '07-' . $registro->ciclo;
                }
                else
                {
                    $acumuladoMes7=0;
                    $ocorrencias7=0;
                    $media7=0;
                    $porcentagem7=0;
                }


                if(!empty($sl02bdfs08))
                {
                    $acumuladoMes8  = $sl02bdfs08->sum('diferenca'); // soma a coluna valor da coleção de dados
                    $ocorrencias8  = $sl02bdfs08->count('diferenca');
                    if($ocorrencias8==0){
                        $media8=0;
                      }else{
                        $media8 =   $acumuladoMes8/$ocorrencias8;
                      }
                    $limite =$sl02bdfs08->max('limite');
                    if ($limite==0) $limite=1;
                    $porcentagem8 = $media8 / $limite;
                    $mes_Ano08 = '08-' . $registro->ciclo;
                }
                else
                {
                    $acumuladoMes8=0;
                    $ocorrencias8=0;
                    $media8=0;
                    $porcentagem8=0;
                }

                if(!empty($sl02bdfs09))
                {
                    $acumuladoMes9  = $sl02bdfs09->sum('diferenca'); // soma a coluna valor da coleção de dados
                    $ocorrencias9  = $sl02bdfs09->count('diferenca');
                    if($ocorrencias9==0){
                        $media9=0;
                      }else{
                        $media9 =   $acumuladoMes9/$ocorrencias9;
                      }
                    $limite =$sl02bdfs09->max('limite');
                    if ($limite==0) $limite=1;
                    $porcentagem9 = $media9 / $limite;
                    $mes_Ano09 = '09-' . $registro->ciclo;
                }
                else
                {
                    $acumuladoMes9=0;
                    $ocorrencias9=0;
                    $media9=0;
                    $porcentagem9=0;
                }

                if(!empty($sl02bdfs10))
                {
                    $acumuladoMes10  = $sl02bdfs10->sum('diferenca'); // soma a coluna valor da coleção de dados
                    $ocorrencias10  = $sl02bdfs10->count('diferenca');
                    if($ocorrencias10==0){
                        $media10=0;
                      }else{
                        $media10 =   $acumuladoMes10/$ocorrencias10 ;
                      }
                    $limite =$sl02bdfs10->max('limite');
                    if ($limite==0) $limite=1;
                    $porcentagem10 = $media10 / $limite;
                    $mes_Ano10 = '10-' . $registro->ciclo;
                }
                else
                {
                    $acumuladoMes10=0;
                    $ocorrencias10=0;
                    $media10=0;
                    $porcentagem10=0;
                }

                if(!empty($sl02bdfs11))
                {
                    $acumuladoMes11  = $sl02bdfs11->sum('diferenca'); // soma a coluna valor da coleção de dados
                    $ocorrencias11  = $sl02bdfs11->count('diferenca');
                    if($ocorrencias11==0){
                        $media11=0;
                      }else{
                        $media11 =   $acumuladoMes11/$ocorrencias11 ;
                      }
                    $limite =$sl02bdfs11->max('limite');
                    if ($limite==0) $limite=1;
                    $porcentagem11 = $media11 / $limite;
                    $mes_Ano11 = '11-' . $registro->ciclo;
                }
                else
                {
                    $acumuladoMes11=0;
                    $ocorrencias11=0;
                    $media11=0;
                    $porcentagem11=0;
                }

                if(!empty($sl02bdfs12))
                {
                    $acumuladoMes12  = $sl02bdfs12->sum('diferenca'); // soma a coluna valor da coleção de dados
                    $ocorrencias12  = $sl02bdfs12->count('diferenca');
                    if($ocorrencias12==0){
                      $media12=0;
                    }else{
                      $media12 =   $acumuladoMes12/$ocorrencias12 ;
                    }
                    $limite =$sl02bdfs12->max('limite');
                    if ($limite==0) $limite=1;
                    $porcentagem12 = $media12 / $limite;
                    $mes_Ano12 = '12-' . $registro->ciclo;

                }
                else
                {
                    $acumuladoMes12=0;
                    $ocorrencias12=0;
                    $media12=0;
                    $porcentagem12=0;
                }



        $dt = new Carbon();
        $mesatual = substr($dt,5,2).'/'.substr($dt,0,4);



        $dt->subMonth(1);
        $mesPassado = substr($dt,5,2).'/'.substr($dt,0,4);

        $dt->subMonth(1);
        $mesAtrasado = substr($dt,5,2).'/'.substr($dt,0,4);

                $data = [
                    'sl02bdfs01' => ['MesReferencia' => $mes_Ano01, 'AcumuladoMes' => $acumuladoMes1, 'Ocorrencias' => $ocorrencias1, 'Média' => $media1 , 'Percentual' => $porcentagem1],
                    'sl02bdfs02' => ['MesReferencia' => $mes_Ano02, 'AcumuladoMes' => $acumuladoMes2, 'Ocorrencias' => $ocorrencias2, 'Média' => $media2 , 'Percentual' => $porcentagem2],
                    'sl02bdfs03' => ['MesReferencia' => $mes_Ano03, 'AcumuladoMes' => $acumuladoMes3, 'Ocorrencias' => $ocorrencias3, 'Média' => $media3 , 'Percentual' => $porcentagem3],
                    'sl02bdfs04' => ['MesReferencia' => $mes_Ano04, 'AcumuladoMes' => $acumuladoMes4, 'Ocorrencias' => $ocorrencias4, 'Média' => $media4 , 'Percentual' => $porcentagem4],
                    'sl02bdfs05' => ['MesReferencia' => $mes_Ano05, 'AcumuladoMes' => $acumuladoMes5, 'Ocorrencias' => $ocorrencias5, 'Média' => $media5 , 'Percentual' => $porcentagem5],
                    'sl02bdfs06' => ['MesReferencia' => $mes_Ano06, 'AcumuladoMes' => $acumuladoMes6, 'Ocorrencias' => $ocorrencias6, 'Média' => $media6 , 'Percentual' => $porcentagem6],
                    'sl02bdfs07' => ['MesReferencia' => $mes_Ano07, 'AcumuladoMes' => $acumuladoMes7, 'Ocorrencias' => $ocorrencias7, 'Média' => $media7 , 'Percentual' => $porcentagem7],
                    'sl02bdfs08' => ['MesReferencia' => $mes_Ano08, 'AcumuladoMes' => $acumuladoMes8, 'Ocorrencias' => $ocorrencias8, 'Média' => $media8 , 'Percentual' => $porcentagem8],
                    'sl02bdfs09' => ['MesReferencia' => $mes_Ano09, 'AcumuladoMes' => $acumuladoMes9, 'Ocorrencias' => $ocorrencias9, 'Média' => $media9 , 'Percentual' => $porcentagem9],
                    'sl02bdfs10' => ['MesReferencia' => $mes_Ano10, 'AcumuladoMes' => $acumuladoMes10, 'Ocorrencias' => $ocorrencias10, 'Média' => $media10 , 'Percentual' => $porcentagem10],
                    'sl02bdfs11' => ['MesReferencia' => $mes_Ano11, 'AcumuladoMes' => $acumuladoMes11, 'Ocorrencias' => $ocorrencias11, 'Média' => $media11 , 'Percentual' => $porcentagem11],
                    'sl02bdfs12' => ['MesReferencia' => $mes_Ano12, 'AcumuladoMes' => $acumuladoMes12, 'Ocorrencias' => $ocorrencias12, 'Média' => $media12 , 'Percentual' => $porcentagem12],
                ];

               $total=0.00;
          //    $total  = $smb_bdf_naoconciliados->sum('Divergencia'); // soma a coluna valor da coleção de dados
              return view('compliance.inspecao.editar',compact(
                'registro'
                ,'id'
                ,'total'
                ,'data'
                ,'sl02bdfs01','sl02bdfs02','sl02bdfs03','sl02bdfs04','sl02bdfs05', 'sl02bdfs06','sl02bdfs07','sl02bdfs08','sl02bdfs09','sl02bdfs10','sl02bdfs11','sl02bdfs12'
                ,'mesAtrasado','mesPassado','mesatual'  ));
            }

            if(($registro->numeroGrupoVerificacao==271)&&($registro->numeroDoTeste==1)) {
                $dtmenos90dias = new Carbon();
                $dtmenos90dias->subDays(90);

                $resp_definidas = DB::table('resp_definidas')
                    ->select('unidade','data_pagamento', 'objeto', 'nu_sei', 'data', 'situacao', 'valor_da_indenizacao' )
                    ->where('unidade', '=', $registro->descricao)
                    ->where('situacao', '!=', 'CONCLUIDO')
                 //   ->where('data', '<=',  $dtmenos90dias)
                   // ->orwhere('data', '=',  null)

                ->get();
                $count = $resp_definidas->count('objeto');
                $total = $resp_definidas->sum('valor_da_indenizacao');
                $dtmax = $dtmenos90dias;
                $dtmin = $resp_definidas->min('data');

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

            /////////
        ///
            if(( $registro->numeroGrupoVerificacao == 272 ) && ( $registro->numeroDoTeste == 2 )) {
              //  ini_set('memory_limit', '1024M');
              //  ini_set('max_input_time', 350);
              //  ini_set('max_execution_time', 350);
                $now = Carbon::now();
             //   $dtmenos210dias = $now->subDays(210);
              //  $dtmenos5meses= $now->subMonth(5);
                $now->format('Y-m-d');
               // $dtmenos210dias = \Carbon\Carbon::parse($dtmenos210dias)->format('Y-m-d');
                $alarmesFinalSemana = DB::table('alarmes')
                    ->where('mcu', '=', $registro->mcu )
                    ->where(function ($query) {
                    $query

                        ->whereYear('data','=', '2020' )
                        ->where('diaSemana', '=', 6)
                        ->orWhere('diaSemana', '=', 0)
                        ->orderBy('data' ,'asc')
                        ->orderBy('hora' ,'asc');
                })
                ->get();

                $count_alarmesFinalSemana  = $alarmesFinalSemana->count('armedesarme');

            //    var_dump($alarmesFinalSemana);
            //    dd('aki  ' .$count_alarmesFinalSemana);

                $rowAberturaFinalSemana=0;
                $aviso ='';

                $tempoDesarme='';
                $tempoDePermanencia='';
                if($count_alarmesFinalSemana > 0){

                    DB::table('acessos_final_semana')
                        ->where('mcu', '=', $registro->mcu)
                        ->delete();

                    foreach ($alarmesFinalSemana  as $tabela){

                        if($tabela->armedesarme == 'Desarme' ){
                            $tempoDesarme = (substr($tabela->hora,0,2)*60)+substr($tabela->hora,3,2);
                            $horaDesarme = $tabela->hora;
                            $dataDesarme = $tabela->data;
                            $eventodesarme = $tabela->armedesarme;
                            $diaEvento = $tabela->diaSemana;
                        }else{
                            if (( isset($diaEvento) ) &&( $diaEvento == $tabela->diaSemana ) && ( $tabela->armedesarme == 'Arme' )) {

                                $horaFechamento = $tabela->hora;
                                $tempoArme = (substr($tabela->hora, 0, 2) * 60) + substr($tabela->hora, 3, 2);

                                $tempoDePermanencia = $tempoArme - $tempoDesarme;
                                $h = intdiv($tempoDePermanencia, 60);
                                $m = $tempoDePermanencia % 60;

                                if ($tabela->diaSemana == 6) {
                                    $diasemana = 'Sábado';
                                } else {
                                    $diasemana = 'Domingo';
                                }

                                $peranencia = ($h < 10 ? '0' . $h : $h) . ':' . ($m < 10 ? '0' . $m : $m);

                             //   $dattt  = $dataDesarme;
                               // if ($dataDesarme == '31/12/1969') {

                              //      var_dump($acessoFinalSemana);
                              //      dd($dataDesarme , '   '. $dattt , ' table   '.$tabela->data);
                             //   }

                                $dataDesarme = date("d/m/Y", strtotime($dataDesarme));
                               // $dataDesarme = date("d/m/Y", strtotime($tabela->data));

                                $acessoFinalSemana = new AcessoFinalSemana();
                                $acessoFinalSemana->mcu = $registro->mcu;
                                $acessoFinalSemana->evAbertura = $eventodesarme;
                                $acessoFinalSemana->evDataAbertura = $dataDesarme;
                                $acessoFinalSemana->evHoraAbertura = $horaDesarme;
                                $acessoFinalSemana->evFechamento = $tabela->armedesarme;
                                $acessoFinalSemana->evHoraFechamento = $horaFechamento;
                                $acessoFinalSemana->diaSemana = $diasemana;
                                $acessoFinalSemana->tempoPermanencia = $peranencia;
                                $acessoFinalSemana->save();
                                $rowAberturaFinalSemana++;
                            }
                        }
                    }
                }

                if ( $rowAberturaFinalSemana >= 1 ) {
                    $acessos_final_semana = DB::table('acessos_final_semana')
                    ->where('mcu',  '=', $registro->mcu)
                    ->select(
                                'acessos_final_semana.*'
                    )
                    ->get();
                } else { $acessos_final_semana = '';}

        /////////////////   Finais de semana  /////////////////////////////

        /////////////////   Feriados /////////////////////////////
                $feriadoporUnidades = DB::table('unidades')
                ->join('unidade_enderecos', 'unidades.mcu', '=', 'unidade_enderecos.mcu')
                ->join('feriados', 'unidade_enderecos.cidade', '=', 'feriados.nome_municipio')
                ->select(
                          'feriados.*'
                )
                ->where([['unidades.mcu', '=', $registro->mcu]])
                ->get();

                $eventos = DB::table('alarmes')
                    ->where('mcu', '=', $registro->mcu )
                    ->where(function ($query) {
                        $query
                            //->where('data','>=',    $dtmenos5meses )
                            ->whereYear('data','=', '2020' )
                            ->where('armedesarme', '=', 'Desarme')
                            ->orderBy('data' ,'asc')
                            ->orderBy('hora' ,'asc');
                    })
                    ->get();

                $row =0;
                $acessosEmFeriados='';
                foreach ($feriadoporUnidades  as $feriadoporUnidade){
                        foreach ($eventos  as $evento){
                            if($feriadoporUnidade->data_do_feriado   ==  $evento->data){

                               // $acessosEmFeriados = $acessosEmFeriados.'<br>'. $row.' Acesso '.\Carbon\Carbon::parse($evento->data)->format('d/m/Y').' hora '.$evento->hora;
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
                    ->where('mcu', '=', $registro->mcu )
                    ->where(function ($query) {
                        $query
                            //->where('data','>=',    $dtmenos5meses )
                            ->whereYear('data','=', '2020'  )
                            ->orderBy('data' ,'asc')
                            ->orderBy('hora' ,'asc');
                    })
                    ->get();
                $dtmax = $eventos->max('data');
                $dataultimoevento='';
                $aviso='';
                $diferencaAbertura ='';
                $tempoAbertura ='';//armazena tempo de abertura menor que o previsto
                $riscoAbertura ='';//armazena risco abertura fora do horário de atendimento

                if( !empty($eventos)){
                    $minutosinicioExpediente = (substr($registro->inicio_expediente,0,2)*60)+substr($registro->inicio_expediente,3,2);
                    $minutosfinalExpediente = (substr($registro->final_expediente,0,2)*60)+substr($registro->final_expediente,3,2);
                    $rowtempoAbertura=0;
                    $rowriscoAbertura=0;
                    $rowtempoAberturaAntecipada=0;
                    $rowtempoAberturaPosExpediente=0;
                    $tempoAberturaPosExpediente='';
                    $tempoAberturaAntecipada='';

                    foreach ($eventos  as $evento){
                        $eventominutos = (substr($evento->hora,0,2)*60)+substr($evento->hora,3,2);

                        if ($evento->armedesarme =='Desarme' ){
                            if (($eventominutos > ($minutosinicioExpediente-15))){
                                $diferencaAbertura =  $minutosinicioExpediente - $eventominutos;
                                if($diferencaAbertura <0){
                                    $diferencaAbertura= $diferencaAbertura*-1;
                                }
                                $h = intdiv ($diferencaAbertura,60);
                                if ($h<10){
                                    $h='0'.$h;
                                }
                                $m = $diferencaAbertura % 60;
                                if ($m<10){
                                    $m='0'.$m;
                                }
                                $diferencaAbertura = $h.':'.$m.':'.substr($evento->hora,6,2);
                                //dd( $diferencaAbertura);
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
                            if (($eventominutos > ($minutosfinalExpediente+120))){
                                $diferencaAbertura =  $eventominutos - $minutosfinalExpediente;
                                $h = intdiv ($diferencaAbertura,60);
                                if ($h<10){
                                    $h='0'.$h;
                                }
                                $m = $diferencaAbertura % 60;
                                if ($m<10){
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

                            if ($periodo->count()>=15){
                                $aviso ='a) Não foi possível avaliar eventos recente da utilização do alarme monitorado
                                dado que a unidade não está sendo monitorada há '. $periodo->count().' dias. Incluindo
                                a data da Inspeção. Adicionalmente verificaram que o último evento transmitido
                                 foi no dia ' .$dataultimoevento. '.';
                            }

                    }
                }
                $naoMonitorado='';
                if(($rowAberturaFinalSemana==0) && ($row ==0) && ($rowtempoAbertura==0) && ($rowtempoAberturaAntecipada ==0) && ($rowtempoAberturaPosExpediente ==0) ) {
                    $maxdata = DB::table('alarmes')
                        ->where('mcu', '=', $registro->mcu )
                        ->max('data');
                   if(!empty($maxdata ))
                   {
                       $dataultimoevento = \Carbon\Carbon::parse($maxdata)->format('d/m/Y');
                   }else{
                       $dataultimoevento = 'data não localizada nos parâmetros dessa pesquisa de inspeção';
                   }
                    $naoMonitorado ='Não foi possível avaliar eventos recente da utilização do alarme monitorado
                            dado que a unidade não está sendo monitorada.
                            Adicionalmente verificaram que o último evento transmitido
                             foi em ' .$dataultimoevento. '.';
                }

                $total = 0;
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
                ,'naoMonitorado'
                ));
            }

            if (($registro->numeroGrupoVerificacao==272) && ($registro->numeroDoTeste==3)) {

                $now = Carbon::now();
                $now = $now->format('Y-m-d');
                $dtmenos12meses =  Carbon::now()->subMonth(12);
                $dtmenos12meses = $dtmenos12meses->format('Y-m-d');
                $dtini = $dtmenos12meses;
                $dtfim = $now;
                $row=0;
                $aviso = '';
                $periodo=array();
                $eventos = DB::table('alarmes')
                    ->where('mcu', '=', $registro->mcu )
                    ->where(function ($query) {
                        $query
                            ->whereYear('data','=', '2020' )
                            ->orderBy('data' ,'asc')
                            ->orderBy('hora' ,'asc');
                    })
                ->get();

                if($eventos){
                    $dtmax = $eventos->max('data');
                    $periodo = CarbonPeriod::create($dtmax ,  $now );
                    $dataultimoevento = \Carbon\Carbon::parse($dtmax)->format('d/m/Y');
                } else if ($periodo->count()>=15){
                        $aviso = 'a) A unidade inspecionada não está sendo monitorada há '. $periodo->count().' dias. Adicionalmente, verificaram que o último evento transmitido foi no dia ' .$dataultimoevento. '.';
                }
                //  obter a lista de alterações por empregados
                $ferias_por_mcu = DB::table('ferias_por_mcu')
                    ->select( 'ferias_por_mcu.*' )
                    ->where([['lotacao', '=',  $registro->descricao  ]])
                    ->where([['inicio_fruicao', '<>', null ]])
                    ->where([['inicio_fruicao', '<=',  $now ]])
                    ->orderBy('ferias_por_mcu.inicio_fruicao' , 'asc')
                    ->get();

                if(!empty($ferias_por_mcu )){
                    foreach ($ferias_por_mcu  as $ferias) {
                        $inicio_fruicao = Carbon::parse($ferias->inicio_fruicao)->format('Y-m-d');
                        $termino_fruicao = Carbon::parse($ferias->termino_fruicao)->format('Y-m-d');

                        $compartilhaSenha =  DB::table('compartilhaSenhas')
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

                            if ($res->count('matricula')>=1){
                                $motivo='Férias';
                                foreach ($res  as $dado){
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
                $frequencias = DB::table('absenteismos')
                    ->select('absenteismos.*')
                    ->where([['lotacao', '=',  $registro->descricao  ]])
                    ->where([['data_evento', '>=',  $dtmenos12meses  ]])
                    ->whereBetween('data_evento', [$dtmenos12meses , $now])
                    ->get();
                if ($frequencias->count('matricula')>=1){
                 //   var_dump($frequencias );
                    foreach ($frequencias as $frequencia){
                        $dt = new Carbon($frequencia->data_evento);

                        if ($frequencia->dias>1){
                            $dt =  $dt->addDays($frequencia->dias);
                            $dt = $dt->format('Y-m-d');
                        }
                       // dd($frequencia->data_evento. ' ----------- '. $dt);
                        $res = DB::table('alarmes')
                            ->select('alarmes.*')
                            ->where([['mcu', '=', $registro->mcu]])
                            ->where([['matricula', '=', $frequencia->matricula]])
                            ->whereBetween('data', [$frequencia->data_evento , $dt])
                            ->orderBy('data' , 'asc')
                            ->orderBy('hora' , 'asc')
                            ->get();
                    }

                    if ($res->count('matricula')>=1){
                        foreach ($res  as $dado){
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
                $compartilhaSenhas  =  DB::table('compartilhaSenhas')
                    ->where('codigo', '=', $registro->codigo)
                    ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                    ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                    ->orderBy('data' ,'asc')
                    ->get();
                $row= $compartilhaSenhas->count('codigo');

                $maiordata = DB::table('alarmes')
                    ->where('mcu', '=', $registro->mcu )
                    ->where(function ($query) {
                        $query
                            //->where('data','>=',    $dtmenos5meses )
                            ->whereYear('data','=', '2020'  )
                            ->orderBy('data' ,'asc')
                            ->orderBy('hora' ,'asc');
                    })
                    ->get();


                if(!empty($maiordata ))
                {
                    $dtmax = \Carbon\Carbon::parse($maiordata->max('data'))->format('d/m/Y');
                }else{
                    $dtmax =Carbon::now();
                    $dtmax = \Carbon\Carbon::parse($dtmax)->format('d/m/Y');
                }

               // dd( $dtmax );

                $naoMonitorado='';
                $maxdata = DB::table('alarmes')
                    ->where('mcu', '=', $registro->mcu )
                    ->max('data');
                if(!empty($maxdata ))
                {
                    $dataultimoevento = \Carbon\Carbon::parse($maxdata)->format('d/m/Y');
                }else{
                    $dataultimoevento = 'data não localizada nos parâmetros dessa pesquisa de inspeção';
                }
                $naoMonitorado ='Não foi possível confrontar os dados obtidos com as informações de férias e afastamentos, com objetivo de identificar possíveis compartilhamentos de  senha recente da utilização do alarme monitorado. Dado que, a unidade não está sendo monitorada. Adicionalmente verificaram que o último evento transmitido foi em ' .$dataultimoevento. '.';


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

            if (($registro->numeroGrupoVerificacao==272) && ($registro->numeroDoTeste==4)) {

                $cftvs = DB::table('cftvs')
                   ->select( 'cftvs.*' )
                   ->where([['cftvs.unidade', '=',  $registro->descricao  ]])
                   ->get();
               // var_dump(  $cftvs );
              //  dd(   );
               $total=0.00;
               return view('compliance.inspecao.editar',compact
                               (
                                   'registro'
                                   , 'id'
                                   , 'total'
                                   , 'cftvs'

                               ));
            }

            if (($registro->numeroGrupoVerificacao==274) && ($registro->numeroDoTeste==1)) {
                $plplistapendentes = DB::table('plpListaPendentes')
                   ->select( 'plpListaPendentes.*' )
                   ->where([['stomcu', '=',  $registro->mcu  ]])
                   ->get();
                $count = $plplistapendentes->count('lista');

                $datas = DB::table('plpListaPendentes')
                   ->select( 'plpListaPendentes.*' )
                   ->get();
                $dtfim = $datas->max('dh_lista_postagem');

                $total=0.00;
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

            if (($registro->numeroGrupoVerificacao==276) && ($registro->numeroDoTeste==1)) {
                $controle_de_viagens = DB::table('controle_de_viagens')
                   ->select( 'controle_de_viagens.*' )
                   ->where([['controle_de_viagens.ponto_parada', '=',  $registro->an8  ]])
                   ->get();
                $count = $controle_de_viagens->count('ponto_parada');

                $controle = DB::table('controle_de_viagens')
                   ->select( 'controle_de_viagens.inicio_viagem' )
                   ->get();
                $dtini = $controle->min('inicio_viagem');
                $dtfim = $controle->max('inicio_viagem');
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

                $total=0.00;
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

            if (($registro->numeroGrupoVerificacao==277) && ($registro->numeroDoTeste==1)) {

                $dtmenos110dias = new Carbon();
                $dtmenos110dias->subDays(110);
                $dtmenos110dias = \Carbon\Carbon::parse($dtmenos110dias)->format('Y-m-d');

                $sgdo_distribuicao = DB::table('sgdo_distribuicao')
                    ->select( 'sgdo_distribuicao.*' )
                    ->where([['mcu', '=',  $registro->mcu  ]])
                    ->where([['data_incio_atividade', '>=',  $dtmenos110dias  ]])
                    //->where([['data_saida', '=',  ''  ]])
                    //->where([['data_retorno', '=',  ''  ]])
                    //->where([['data_tpc', '=',   ''  ]])
                   // ->where([['data_termino_atividade', '=',   ''  ]])


                  ->get();


                $count = $sgdo_distribuicao->count('mcu');
                $dtini = $dtmenos110dias;
                $dtfim = $sgdo_distribuicao->max('data_incio_atividade');
                $total=0.00;


             //   var_dump( $sgdo_distribuicao );
             //   dd();
              //  dd($dtmenos110dias);


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
                $item=1;
                $dtini=null;
                $dtfim=null;
                $media=null;
                $random=null;
                $amostra=null;

                $lancamentossros =  DB::table('lancamentossro')
                    ->where('codigo', '=', $registro->codigo)
                    ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                    ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                    ->get();
                if (!empty($lancamentossros))
                { //existe registros
                    $pend = 0;
                    $aval = 0;
                    foreach ($lancamentossros as $register) {
                        if ($register->estado == 'Pendente') {
                            $pend++;
                        } else {
                            $aval++;
                        }
                    }
                    $mostra = $pend + $aval;


                    if ($mostra == 0) { // não existe registros
                        //dd(   ' Não existe lançamentos sro' );
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
//                                    ->where('descricao_do_evento', '=', 'DESTINATARIO AUSENTE' )
                                    ->where('descricao_do_evento', '=', 'ENTREGUE')
                                    ->orWhere('descricao_do_evento', '=', 'DISTRIBUÍDO AO REMETENTE')
                                    ->orWhere('descricao_do_evento', '=', 'DESTINATÁRIO MUDOU-SE')
                                    ->orWhere('descricao_do_evento', '=', 'DESTINATÁRIO DESCONHECIDO NO ENDEREÇO')
                                    ->orderBy('data_do_evento', 'asc')
                                    ->groupBy('codigo_do_objeto');
                            })
                            ->get();

                        if ($micro_strategys->count('codigo_do_objeto') >= 1) {

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

                            if ($amostra >= 1) {
                                $random = $micro_strategys->random($amostra);
                            }
                            if ($amostra == 0) {
                                if ($count <= 60)
                                    $random = $micro_strategys->random($count);
                                else
                                    $random = $micro_strategys->random(25);
                            }
                            $random->all();
                            $sro = DB::table('lancamentossro')
                                ->where('codigo', '=', $registro->codigo)
                                ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                                ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                                ->get();
                            $row = 0;
                            $row = $sro->count('codigo');
                            if ($row == 0) {
                                foreach ($random as $dado) {
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
                        }
                    }

                    if ($aval == $mostra) {
                        //   dd(' Nao existe pendencias ' . $row);
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

                        foreach ($lancamentossro as $lancamento) {
                            if (($lancamento->falhaDetectada <> 'Ok') && ($lancamento->estado == 'Avaliado')) {
                                $qtd_falhas++;
                            }
                            if ($lancamento->estado == 'Avaliado') {
                                $amostra++;
                            }
                        }
                        //   dd($registro->codigo);

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
// dd('parou');
                        return view('compliance.inspecao.editar', compact
                        (
                            'registro'
                            , 'id'
                            , 'total'
                            , 'count'
                            , 'dtini'
                            , 'dtfim'
                            , 'media'
                            , 'random'
                            , 'amostra'
                            , 'item'
                            , 'res'
                            , 'qtd_falhas'
                            , 'percentagem_falhas'
                        ));


                    }

                    if ($pend <= $mostra) {
                        $res = DB::table('lancamentossro')
                            ->where('codigo', '=', $registro->codigo)
                            ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                            ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                            ->where('estado', '=', 'Pendente')
                            ->get();
                        // dd(  $res , $registro, $id);
                        return view('compliance.inspecao.index_sro', compact
                        (
                            'registro'
                            , 'id'
                            , 'res'
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
                $item=1;
                $dtini=null;
                $dtfim=null;
                $media=null;
                $random=null;
                $amostra=null;

                $lancamentossros =  DB::table('lancamentossro')
                    ->where('codigo', '=', $registro->codigo)
                    ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                    ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                    ->get();
                if (!empty($lancamentossros))
                { //existe registros
                    $pend = 0;
                    $aval = 0;
                    foreach ($lancamentossros as $register) {
                        if ($register->estado == 'Pendente') {
                            $pend++;
                        } else {
                            $aval++;
                        }
                    }
                    $mostra = $pend + $aval;


                    if ($mostra == 0) { // não existe registros
                        //dd(   ' Não existe lançamentos sro' );
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
                                    ->where('descricao_do_evento', '=', 'DESTINATARIO AUSENTE' )
//                                    ->where('descricao_do_evento', '=', 'ENTREGUE')
//                                    ->orWhere('descricao_do_evento', '=', 'DISTRIBUÍDO AO REMETENTE')
//                                    ->orWhere('descricao_do_evento', '=', 'DESTINATÁRIO MUDOU-SE')
//                                    ->orWhere('descricao_do_evento', '=', 'DESTINATÁRIO DESCONHECIDO NO ENDEREÇO')
                                    ->orderBy('data_do_evento', 'asc')
                                    ->groupBy('codigo_do_objeto');
                            })
                            ->get();

                        if ($micro_strategys->count('codigo_do_objeto') >= 1) {

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

                            if ($amostra >= 1) {
                                $random = $micro_strategys->random($amostra);
                            }
                            if ($amostra == 0) {
                                if ($count <= 60)
                                    $random = $micro_strategys->random($count);
                                else
                                    $random = $micro_strategys->random(25);
                            }
                            $random->all();
                            $sro = DB::table('lancamentossro')
                                ->where('codigo', '=', $registro->codigo)
                                ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                                ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                                ->get();
                            $row = 0;
                            $row = $sro->count('codigo');
                            if ($row == 0) {
                                foreach ($random as $dado) {
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
                        }
                    }

                    if ($aval == $mostra) {
                     //   dd(' Nao existe pendencias ' . $row);
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

                        foreach ($lancamentossro as $lancamento) {
                            if (($lancamento->falhaDetectada <> 'Ok') && ($lancamento->estado == 'Avaliado')) {
                                $qtd_falhas++;
                            }
                            if ($lancamento->estado == 'Avaliado') {
                                $amostra++;
                            }
                        }
                         //   dd($registro->codigo);

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
// dd('parou');
                        return view('compliance.inspecao.editar', compact
                        (
                            'registro'
                            , 'id'
                            , 'total'
                            , 'count'
                            , 'dtini'
                            , 'dtfim'
                            , 'media'
                            , 'random'
                            , 'amostra'
                            , 'item'
                            , 'res'
                            , 'qtd_falhas'
                            , 'percentagem_falhas'
                        ));


                    }

                    if ($pend <= $mostra) {
                        $res = DB::table('lancamentossro')
                            ->where('codigo', '=', $registro->codigo)
                            ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                            ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                            ->where('estado', '=', 'Pendente')
                            ->get();
                        // dd(  $res , $registro, $id);
                        return view('compliance.inspecao.index_sro', compact
                        (
                            'registro'
                            , 'id'
                            , 'res'
                        ));
                    }

                }
            }

            if (($registro->numeroGrupoVerificacao==277) && ($registro->numeroDoTeste==5)) {
                $dtmenos150dias = new Carbon();
                $dtmenos150dias->subDays(150);

                $painel_extravios = DB::table('painel_extravios')
                   ->select( 'painel_extravios.*' )
                   ->where([['painel_extravios.data_evento', '>=',  $dtmenos150dias  ]])
                   ->where([['painel_extravios.unid_destino_apelido', '=',  $registro->descricao  ]])
                   ->where([['painel_extravios.gestao_prealerta', '=',  'Gestão Automática' ]])
                   ->get();
                $count = $painel_extravios->count('mcu');

                //var_dump($painel_extravios);
               //dd( $count);

                $datas = DB::table('painel_extravios')
                   ->select( 'painel_extravios.data_evento' )
                  // ->where([['sgdo_distribuicao.mcu', '=',  $registro->descricao  ]])
                   ->where([['painel_extravios.data_evento', '>=',  $dtmenos150dias  ]])

                   ->get();
                $dtini = $dtmenos150dias;
                $dtfim = $datas->max('data_evento');

                $countSupervisor=0;
                $cadastral = DB::table('cadastral')
                ->select( 'cadastral.*' )
                //->where([['cadastral.lotacao', '>=',     ]])
                ->where([['cadastral.lotacao',  'like', '%' . $registro->descricao . '%']])
                ->where('cadastral.funcao',  'like', '%' . 'SUPERVISOR' . '%')
                ->get();

                $countSupervisor = $cadastral->count('funcao');
//dd($countSupervisor);

             //   var_dump($sgdo_distribuicao);
             //   dd('<br> Total: '. $count.'<br> Data Inicial: '. $dtini.'<br> Data Final: '. $dtfim);


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

            if (($registro->numeroGrupoVerificacao==277) && ($registro->numeroDoTeste==6)) {
                $dtmenos150dias = new Carbon();
                $dtmenos150dias->subDays(150);

                $painel_extravios = DB::table('painel_extravios')
                   ->select( 'painel_extravios.*' )
                   ->where([['painel_extravios.data_evento', '>=',  $dtmenos150dias  ]])
                   ->where([['painel_extravios.unid_destino_apelido', '=',  $registro->descricao  ]])
                   ->where([['painel_extravios.gestao_prealerta', '=',  'Gestão Automática' ]])


                   ->get();
                $count = $painel_extravios->count('mcu');

                //var_dump($painel_extravios);
               //dd( $count);

                $datas = DB::table('painel_extravios')
                   ->select( 'painel_extravios.data_evento' )
                  // ->where([['sgdo_distribuicao.mcu', '=',  $registro->descricao  ]])
                   ->where([['painel_extravios.data_evento', '>=',  $dtmenos150dias  ]])

                   ->get();
                $dtini = $datas->min('data_evento');
                $dtfim = $datas->max('data_evento');

             //   var_dump($sgdo_distribuicao);
             //   dd('<br> Total: '. $count.'<br> Data Inicial: '. $dtini.'<br> Data Final: '. $dtfim);
             $countSupervisor=0;
             $cadastral = DB::table('cadastral')
             ->select( 'cadastral.*' )
             //->where([['cadastral.lotacao', '>=',     ]])
             ->where([['cadastral.lotacao',  'like', '%' . $registro->descricao . '%']])
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


            if (($registro->numeroGrupoVerificacao==277) && ($registro->numeroDoTeste==7)) {
                $dtmenos365dias = new Carbon();
                $dtmenos365dias->subDays(365);

                $cie_eletronicas = DB::table('cie_eletronicas')
                   ->select( 'cie_eletronicas.*' )
                   ->where([['cie_eletronicas.emissao', '>=',  $dtmenos365dias  ]])
                   ->where([['cie_eletronicas.destino',  'like', '%' . $registro->descricao . '%']])
                   ->where([['cie_eletronicas.respondida', '=',  'N' ]])
                   ->get();
                $count = $cie_eletronicas->count('respondida');

              //  var_dump($cie_eletronicas);
              // dd( $count);

                $datas = DB::table('cie_eletronicas')
                   ->select( 'cie_eletronicas.emissao' )
                   ->where([['cie_eletronicas.emissao', '>=',  $dtmenos365dias  ]])

                   ->get();
                $dtini = $datas->min('emissao');
                $dtfim = $datas->max('emissao');


//dd($countSupervisor);

             //   var_dump($sgdo_distribuicao);
             //   dd('<br> Total: '. $count.'<br> Data Inicial: '. $dtini.'<br> Data Final: '. $dtfim);


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

            if (($registro->numeroGrupoVerificacao==278) && ($registro->numeroDoTeste==1)) {
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
               // dd( $reffinal);
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
                $count = $pagamentos_adicionais->count('sigla_lotacao');

//var_dump( $pagamentos_adicionais) ;
//dd();

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
                        //dd($periodo);
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
                           // var_dump($eventos );
                         //   dd($month , $year , $ref, $registro->descricao);
                            $counteventostfs = $eventos->count('data');

                           // dd($counteventostfs);

                            if( $counteventostfs == 0){
                                $situacao = 'Provento registrado em período que não houve registro de Desarme do Sistema de Alarme.';
                            }else{
                                $rowtfs=0;
                                foreach ($eventos  as $evento){
                                    $rowtfs++;
                                }
                               // dd( $rowtfs.' ss');
                                $situacao=null;
                            }

                        }elseif
                               (($adicional-> rubrica    == 'Hora Extra   70% - Norm')
                            || ($adicional-> rubrica == 'Hora Extra 100% - Norm')
                            || ($adicional-> rubrica == 'Hora Extra Not.70% - Norm') ) {

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

                                $counteventoshe = $eventos->count('data');
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

                        if (!$situacao==null) {
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
                    if($pgtoAdicionais){
                        $total=$pgtoAdicionais->sum('valor');
                        $count = $pgtoAdicionais->count('matricula');
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


            if (($registro->numeroGrupoVerificacao==278) && ($registro->numeroDoTeste==2)) {
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
                $pagamentos_adicionais_dist = DB::table('pagamentos_adicionais')
                   ->select( 'pagamentos_adicionais.*' )
                   ->where([['pagamentos_adicionais.sigla_lotacao', 'like', '%' . trim($registro->descricao) . '%' ]])
                   ->where([['pagamentos_adicionais.ref', '>=', $ref ]])
                   ->where([['pagamentos_adicionais.rubrica', '=',  'AADC-Adic.Ativ. Distrib/Coleta Ext.' ]])
                   ->get();
                $count_dist = $pagamentos_adicionais_dist->count('sigla_lotacao');

                if( $count_dist >= 1){
                    DB::table('pgtoAdicionaisTemp')
                    ->where('codigo', '=', $registro->codigo)
                    ->where('numeroGrupoVerificacao', '=', $registro->numeroGrupoVerificacao)
                    ->where('numeroDoTeste', '=', $registro->numeroDoTeste)
                    ->delete(); // limpa dados anteriores existentes do empregado da tabela temporária
                }

                foreach ($pagamentos_adicionais_dist  as $adicionais){
                    $situacao="Sem eventos de Distribuição Domiciliária.";
                    $mes = intval(substr($adicionais->ref,4,2));

                    $sgdo_distribuicao = DB::table('sgdo_distribuicao')
                        ->select('sgdo_distribuicao.*')
                        ->where([[ 'mcu', '>=', $registro->mcu ]])
                        ->where([[ 'matricula', '=', $adicionais->matricula ]])
                        ->whereMonth('data_termino_atividade', $mes)
                        ->get();
                    $count_sgdo = $sgdo_distribuicao->count('matricula');

                    if(!empty($sgdo_distribuicao)){


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
                   ->where([['pagamentos_adicionais.sigla_lotacao', 'like', '%' . trim($registro->descricao) . '%' ]])
                   ->where([['pagamentos_adicionais.ref', '>=', $ref ]])
                   ->where([['pagamentos_adicionais.rubrica', '=',  'AAG - Adic. de Atend. em Guichê' ]])
                   ->get();
                $count_atend = $pagamentos_adicionais_atend->count('matricula');

                foreach ($pagamentos_adicionais_atend  as $adicionais){

                    $situacao="Sem eventos de atendimento a clientes.";
                    $mes = intval(substr($adicionais->ref,4,2));

                    $bdf_fat_02 = DB::table('bdf_fat_02')
                        ->select('bdf_fat_02.*')
                        ->where([[ 'cd_orgao', '>=', $registro->sto ]])
                        ->where([[ 'atendimento', '=', $adicionais->matricula ]])
                        ->whereMonth('dt_mov', $mes)
                        ->get();
                    if(! $bdf_fat_02){
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

                      //  DB::enableQueryLog();

                        $ferias_por_mcu = DB::table('ferias_por_mcu')
                        ->select('ferias_por_mcu.*')
                        ->where([[ 'matricula', '=', $adicionais->matricula ]])
                        ->whereMonth('inicio_fruicao', $mes-1)
                        ->whereYear('inicio_fruicao', $registro->ciclo)
                        ->first();
                        if (! $ferias_por_mcu){
                          //  var_dump(   $pgtoAdicionaisTemp );
                           // dd("parou");
                            $pgtoAdicionaisTemp->save();
                        }else{
                            unset($pgtoAdicionaisTemp);
                        }

                    }

                }
                $total=0.00;
                if (( $count_atend >= 1 ) || ( $count_dist >= 1 )){
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

                } else { $pgtoAdicionais = '';}

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

        return view('compliance.inspecao.editar',compact('registro','id'));

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


        if($count == 0){
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

        if($count == 0){
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
        if($count == 0){

            $inspecoes = Inspecao::find( $id );
            $inspecoes->status = 'Inspecionado';
            //dd('aki '.  $count);
            $inspecoes->save();
            return redirect()-> route('compliance.verificacoes');
            //, 'Em Manifestação', 'Concluida')
        }


        //No contacts



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

//        dd(  $dado);

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



    public function corroborar($id)  {
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
                // $dado->situacao = 'Corroborado' ;
                // dd($dado->eventosSistema);
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
