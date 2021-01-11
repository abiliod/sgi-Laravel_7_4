<?php

namespace App\Http\Controllers\Correios\Importacao;

use App\Http\Controllers\Controller;
use App\Models\Correios\TipoDeUnidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;
use phpDocumentor\Reflection\Types\Null_;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Carbon\Carbon;

use App\Models\Correios\ModelsAuxiliares\FeriasPorMcu;
use App\Imports\ImportFeriasPorMcu;
use App\Exports\ExportFeriasPorMcu;

use App\Models\Correios\ModelsAuxiliares\UnidadeEndereco;
use App\Imports\ImportUnidadeEndereco;
use App\Exports\ExportUnidadeEndereco;


use App\Models\Correios\ModelsAuxiliares\Feriado;
use App\Imports\ImportFeriado;
use App\Exports\ExportFeriado;

use App\Models\Correios\ModelsAuxiliares\RespDefinida;
use App\Imports\ImportRespDefinida;
use App\Exports\ExportRespDefinida;

use App\Models\Correios\ModelsAuxiliares\SL02_bdf;
use App\Imports\ImportSL02_bdf;
use App\Exports\ExportSL02_bdf;

use App\Models\Correios\ModelsAuxiliares\SMBxBDF_NaoConciliado;
use App\Imports\ImportSMBxBDF_NaoConciliado;
use App\Exports\ExportSMBxBDF_NaoConciliado;

use App\Models\Correios\Cadastral;
use App\Imports\ImportCadastral;
use App\Exports\ExportCadastral;

use App\Models\Correios\ModelsAuxiliares\Proter;
use App\Imports\ImportProter;
use App\Exports\ExportProter;

use App\Models\Correios\ModelsAuxiliares\DebitoEmpregado;
use App\Imports\ImportDebitoEmpregados;
use App\Exports\ExportDebitoEmpregados;

use App\Models\Correios\Alarme;
use App\Imports\ImportAlarmes;
use App\Exports\ExportAlarmes;

use App\Models\Correios\ModelsAuxiliares\Absenteismo;
use App\Imports\ImportAbsenteismo;
use App\Exports\ExportAbsenteismo;


use App\Models\Correios\ModelsAuxiliares\Evento;
use App\Imports\ImportEventos;
use App\Exports\ExportEventos;


use App\Models\Correios\ModelsAuxiliares\Cftv;
use App\Imports\ImportCftv;
use App\Exports\ExportCftv;

use App\Models\Correios\ModelsAuxiliares\ControleDeViagem;
use App\Imports\ImportControleDeViagem;
use App\Exports\ExportControleDeViagem;

use App\Models\Correios\ModelsAuxiliares\PLPListaPendente;
use App\Imports\ImportPLPListaPendente;
use App\Exports\ExportPLPListaPendente;


use App\Models\Correios\ModelsAuxiliares\SgdoDistribuicao;
use App\Imports\ImportSgdoDistribuicao;
use App\Exports\ExportSgdoDistribuicao;

use App\Models\Correios\ModelsAuxiliares\CieEletronica;
use App\Imports\ImportCieEletronica;
use App\Exports\ExportCieEletronica;

use App\Models\Correios\ModelsAuxiliares\PainelExtravio;
use App\Imports\ImportPainelExtravio;
use App\Exports\ExportPainelExtravio;


use App\Models\Correios\ModelsAuxiliares\PagamentosAdicionais;
use App\Imports\ImportPagamentosAdicionais;
use App\Exports\ExportPagamentosAdicionais;

use App\Models\Correios\ModelsAuxiliares\BDF_FAT_02;
use App\Imports\ImportBDF_FAT_02;
use App\Exports\ExportBDF_FAT_02;

use App\Models\Correios\ModelsAuxiliares\MicroStrategy;
use App\Imports\ImportMicroStrategy;
use App\Exports\ExportMicroStrategy;

use App\Models\Correios\Unidade;
use App\Imports\ImportUnidades;

//        if( $request->file('file')->getClientOriginalName() != "270-1-FINANCEIRO-WebCont_DebitoEmpregado.xlsx") {
//            \Session::flash('mensagem',['msg'=>'Erro na Seleção do Arquivo.
//            O Arquivo de ser270-1-FINANCEIRO-WebCont_DebitoEmpregado.xlsx! Selecione Corretamente'
//                ,'class'=>'red white-text']);
//            return redirect()->route('importacao');
//        }
//  DB::table('debitoempregados')->truncate();

class ImportacaoController extends Controller
{
    /// ######################### BLOCO  DEBITO EMPREGADO #######################
    public function exportDebitoEmpregados()
    {
        return Excel::download(new ExportDebitoEmpregados, 'debitoEmpregados.xlsx');
    }
    public function importDebitoEmpregados(Request $request)
    {
        $row=0;
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        if($request->file('file') == "")
        {
            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
            O Arquivo de ser  270-1-FINANCEIRO-WebCont_DebitoEmpregado.xlsx! Selecione Corretamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }
        if($validator->passes())
        {
            $debitoEmpregados = Excel::toArray(new ImportDebitoEmpregados,  request()->file('file'));
            foreach($debitoEmpregados as $dados)
            {
                foreach($dados as $registro)
                {
                  //  dd($registro);
                    //  nome_agencia_doc2
                    // historico
                    // observacoes
                    // matricula_ref2
                 //   regularizacao
                    // acao
//

                    $debitoEmpregado = new DebitoEmpregado;
                    $debitoEmpregado->cia      = $registro['cia'];
                    $debitoEmpregado->conta  = $registro['conta'];
                    $debitoEmpregado->competencia  = $registro['competencia'];
                    $dt         = $this->transformDate($registro['data']);
                    $debitoEmpregado->data         = $dt;
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
            }
            $affected = DB::table('debitoempregados')
                ->where('cia', '=', $registro['cia'])
                ->where('conta', '=', $registro['conta'])
                ->where('competencia', '<', $registro['competencia'])
                ->delete();
//          dd($affected);

            \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
                ,'class'=>'green white-text']);
            return redirect()->route('importacao');
        }else{
            \Session::flash('mensagem',['msg'=>'Registros WebCont Não pôde ser importado! Tente novamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }
    }
    public function debitoEmpregados()
    {
        return view('compliance.importacoes.debitoEmpregado');  //
    }
    /// ######################### FIM DEBITO EMPREGADO #######################


    public function handle()
    {
        \DB::listen(function($res) {
            var_dump($res->sql);
        });
    }

    // ######################### INICIO   Microestraetegy #######################
    public function exportMicroStrategy()
    {
        return Excel::download(new ExportMicroStrategy, 'microStrategy.xlsx');
    }
    public function importMicroStrategy(Request $request)
    {
        $row = 0;
        $dtmenos210dias = Carbon::now();
        $dtmenos210dias = $dtmenos210dias->subDays(210);
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        if(empty($request->file('file')))
        {

            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
            O Arquivo de ser 277-2-4_3-ObjetosNaoEntreguePrimeiraTentativa.xlsx ! Selecione Corretamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }
//        if( $request->file('file')->getClientOriginalName() != "277-2-4_3-ObjetosNaoEntreguePrimeiraTentativa.xlsx") {
//            \Session::flash('mensagem',['msg'=>'Erro na Seleção do Arquivo.
//            O Arquivo de ser 277-2-4_3-ObjetosNaoEntreguePrimeiraTentativa.xls! Selecione Corretamente'
//                ,'class'=>'red white-text']);
//            return redirect()->route('importacao');
//        }

        if($validator->passes())
        {
            //   DB::table('micro_strategys')->truncate(); //excluir e zerar a tabela
            ini_set('max_input_time', 350);
            ini_set('max_execution_time', 350);
            $micro_strategy = Excel::toArray(new ImportMicroStrategy,  request()->file('file'));

            foreach($micro_strategy as $dados)
            {
                foreach($dados as $registro)
                {
                    if ($registro['codigo_do_evento']=='BDE')
                    {
                        $data_do_evento  = null;
                        if(!empty($registro['data_do_evento'])) {
                            try {
                                $data_do_evento = $this->transformDate($registro['data_do_evento']);
                            } catch (Exception $e) {
                                $data_do_evento      = null;
                            }
                        }
//                         var_dump($micro_strategy);
//                          dd();
//                        $micro_strategy = new MicroStrategy;
//                        $micro_strategy->dr_de_destino      = $registro['dr_de_destino'];
//                        $micro_strategy->nome_da_unidade      = $registro['nome_da_unidade'];
//                        $micro_strategy->codigo_do_objeto      = $registro['codigo_do_objeto'];
//                        $micro_strategy->descricao_do_evento      = $registro['descricao_do_evento'];
//                        $micro_strategy->codigo_do_evento      = $registro['codigo_do_evento'];
//                        $micro_strategy->data_do_evento      = $data_do_evento;
//                        $micro_strategy->codigo_do_evento      = $registro['data_do_evento'];
//                        $micro_strategy->save();


                        MicroStrategy::updateOrCreate([
                               'codigo_do_objeto' => $registro['codigo_do_objeto']
                               , 'dr_de_destino' => $registro['dr_de_destino']
                               , 'nome_da_unidade' => $registro['nome_da_unidade']
                            ],[
                                'codigo_do_objeto' => $registro['codigo_do_objeto']
                                , 'dr_de_destino' => $registro['dr_de_destino']
                                , 'nome_da_unidade' => $registro['nome_da_unidade']
                                , 'descricao_do_evento' => $registro['descricao_do_evento']
                                , 'codigo_do_evento' => $registro['codigo_do_evento']
                                , 'data_do_evento' => $registro['data_do_evento']
                                , 'data_do_evento' => $data_do_evento
                            ]
                        );
                        $row ++;
                    }
                }
            }

            DB::table('micro_strategys')
                ->where('data_do_evento', '<=', $dtmenos210dias)
            ->delete();

            \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
                ,'class'=>'green white-text']);
            return redirect()->route('importacao');
        }else{
            return back()->with(['errors'=>$validator->errors()->all()]);
        }
    }
    public function microStrategy()
    {
        return view('compliance.importacoes.microStrategy');
    }
    // ######################### FIM  Microestrategy

    // ######################### INICIO   BDF_FAT_02 #######################
    public function exportBDF_FAT_02()
    {
        return Excel::download(new ExportBDF_FAT_02, 'bdf_fat_02.xlsx');
    }
    public function importBDF_FAT_02(Request $request) {
        $dtmenos210dias = Carbon::now();
        $dtmenos210dias->subDays(210);
        DB::table('bdf_fat_02')->where('dt_postagem', '<=', $dtmenos210dias)->delete();
        $row = 0;
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:xlsx'
        ]);

        if(empty($request->file('file'))){

            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
            O Arquivo de ser 278-2-BDF_FAT_02.xlsx ! Selecione Corretamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }

//        if( $request->file('file')->getClientOriginalName() != "278-2-BDF_FAT_02.xlsx") {
//
//            \Session::flash('mensagem',['msg'=>'Erro na Seleção do Arquivo.
//            O Arquivo de ser 278-2-BDF_FAT_02.xlsx! Selecione Corretamente'
//                ,'class'=>'red white-text']);
//            return redirect()->route('importacao');
//        }

        if($validator->passes())
        {
            ini_set('max_input_time', 350);
            ini_set('max_execution_time', 350);
            $bdf_fat_02 = Excel::toArray(new ImportBDF_FAT_02,  request()->file('file'));
            foreach($bdf_fat_02 as $dados)
            {
                foreach($dados as $registro)
                {
                    $dt_mov       = null;
                    if(!empty($registro['dt_mov']))
                    {
                        try
                        {
                            // $dt_mov = $this->transformDate($registro['dt_mov']);
                            $dt_mov = $this->transformDate($registro['dt_mov'])->format('Y-m-d');
                            // $dt_mov =  Carbon::createFromFormat($registro['dt_mov'])->format('Y-m-d');
                        }
                        catch (Exception $e)
                        {
                            $dt_mov = null;
                        }
                    }

                    $dt_postagem       = null;
                    if(!empty($registro['dt_postagem']))
                    {
                        try {
                            $dt_postagem = $this->transformDate($registro['dt_postagem'])->format('Y-m-d');
                            // $dt_postagem =  Carbon::createFromFormat($registro['dt_postagem'])->format('Y-m-d');
                        }
                        catch (Exception $e)
                        {
                            $dt_postagem       = null;
                        }
                    }

                    $res = DB::table('bdf_fat_02')
                        ->where('dt_mov','=', $dt_mov)
                        ->where('cd_orgao', '=',  $registro['cd_orgao'])
                        ->where('servico', '=',  $registro['servico'])
                        ->where('nome_servico', '=',  $registro['nome_servico'])
                        ->where('vlr_final', '=',  $registro['vlr_final'])
                        ->select(
                            'bdf_fat_02.id'
                        )
                    ->first();

                    if (empty($res->id)){
                        $bdf_fat_02 = new BDF_FAT_02;
                        $bdf_fat_02->cd_orgao      = $registro['cd_orgao'];
                        $bdf_fat_02->orgao  = $registro['orgao'];
                        $bdf_fat_02->dt_postagem = $dt_postagem;
                        $bdf_fat_02->etiqueta  = $registro['cd_orgao'];
                        $bdf_fat_02->servico  = $registro['servico'];
                        $bdf_fat_02->vlr_medida  = $registro['vlr_medida'];
                        $bdf_fat_02->cd_grupo_pais_destino  = $registro['cd_grupo_pais_destino'];
                        $bdf_fat_02->cep_destino  = $registro['cep_destino'];
                        $bdf_fat_02->vlr_cobrado_destinatario  = $registro['vlr_cobrado_destinatario'];
                        $bdf_fat_02->vlr_declarado  = $registro['vlr_declarado'];
                        $bdf_fat_02->cod_adm  = $registro['cod_adm'];
                        $bdf_fat_02->produto  = $registro['produto'];
                        $bdf_fat_02->qtde_prestada  = $registro['qtde_prestada'];
                        $bdf_fat_02->vlr_servico  = $registro['vlr_servico'];
                        $bdf_fat_02->vlr_desconto  = $registro['vlr_desconto'];
                        $bdf_fat_02->acrescimo  = $registro['acrescimo'];
                        $bdf_fat_02->vlr_final  = $registro['vlr_final'];
                        $bdf_fat_02->cartao  = $registro['cartao'];
                        $bdf_fat_02->documento  = $registro['documento'];
                        $bdf_fat_02->servico_adicional  = $registro['servio_adicional'];
                        $bdf_fat_02->nome_servico  = $registro['nome_servico'];
                        $bdf_fat_02->contrato  = $registro['contrato'];
                        $bdf_fat_02->dt_mov = $dt_mov;
                        $bdf_fat_02->save();
                        $row ++;
                    }
                }
            }

            if ($row >= 1){
                \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
                    ,'class'=>'green white-text']);
            }else{
                \Session::flash('mensagem',['msg'=>'O Arquivo lido e encontrado todos registros existentes'
                    ,'class'=>'green white-text']);
            }
            return redirect()->route('importacao');
        }else{
            return back()->with(['errors'=>$validator->errors()->all()]);
        }
    }
    public function bdf_fat_02()
    {
        return view('compliance.importacoes.bdf_fat_02');  //
    }
    // ######################### FIM  BDF_FAT_02

    // ######################### INICIO ABSENTEISMO POR MCU   Frequencia por SE #######################
    public function exportAbsenteismo()
    {
        return Excel::download(new ExportAbsenteismo, 'absenteismo.xlsx');
    }
    public function importAbsenteismo(Request $request)
    {
        $row = 0;
        $dtmenos12meses = Carbon::now();
        $dtmenos12meses = $dtmenos12meses->subMonth(12);
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        if(empty($request->file('file')))
        {
            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
        O Arquivo de ser 272-3-WebSGQ3 - Frequencia por SE.xlsx ! Selecione Corretamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }

//        if( $request->file('file')->getClientOriginalName() != "272-3-WebSGQ3 - Frequencia por SE.xlsx") {
//            \Session::flash('mensagem',['msg'=>'Erro na Seleção do Arquivo.
//        O Arquivo de ser  272-3-WebSGQ3 - Frequencia por SE.xls! Selecione Corretamente'
//                ,'class'=>'red white-text']);
//            return redirect()->route('importacao');
//        }

        if($validator->passes())
        {
            $absenteismos = Excel::toArray(new ImportAbsenteismo,  request()->file('file'));
            foreach($absenteismos as $dados)
            {
                foreach($dados as $registro)
                {
                    $dt = substr($registro['data_evento'],6,4).'-'. substr($registro['data_evento'],3,2) .'-'. substr($registro['data_evento'],0,2);
                    $res = DB::table('absenteismos')
                        ->where('matricula', '=',  $registro['matricula'])
                      //  ->where('lotacao', '=',  $registro['lotacao'])
                        ->where('data_evento','=', $dt)
                        ->select(
                            'absenteismos.id'
                        )
                    ->first();

                    if(!empty(  $res->id ))
                    {
                        $absenteismos = Absenteismo::find($res->id);
                    }
                    else
                    {
                        $absenteismos = new Absenteismo;
                    }
                    $absenteismos->matricula = $registro['matricula'];
                    $absenteismos->nome = $registro['nome'];
                    $absenteismos->lotacao = $registro['lotacao'];
                    $absenteismos->cargo = $registro['cargo'];
                    $absenteismos->motivo = $registro['motivo'];
                    $absenteismos->dias = $registro['dias'];
                    $absenteismos->data_evento = $dt;
                    $absenteismos->save();
                    $row++;
                }
            }
            $affected = DB::table('absenteismos')
                ->where('data_evento', '<', $dtmenos12meses)
                ->delete();


            \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
                ,'class'=>'green white-text']);
            return redirect()->route('importacao');
        }else{
            return back()->with(['errors'=>$validator->errors()->all()]);
        }
    }
    public function absenteismo()
    {
        return view('compliance.importacoes.absenteismo');  //
    }
    // ######################### FIM ABSENTEISMO  Frequencia por SE #######################




    // ######################### INICIO ALARMES #######################
    public function exportAlarme()
    {
        return Excel::download(new ExportAlarmes, 'alarme.xlsx');
    }
    public function importAlarme(Request $request)
    {
        $row = 0;
        $dtmenos12meses = Carbon::now();
        $dtmenos12meses = $dtmenos12meses->subMonth(12);

        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        if(empty($request->file('file')))
        {
            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
            O Arquivo de ser 272-2-SEGURANÇA-SistemaMonitoramento_ALARMES *.xlsx ! Selecione Corretamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }

        if($validator->passes())
        {
            ini_set('max_input_time', 350);
            ini_set('max_execution_time', 350);
            $alarmes = Excel::toArray(new ImportAlarmes,  request()->file('file'));
            foreach($alarmes as $dados)
            {
                foreach($dados as $registro)
                {
                    $dt     = $this->transformDate($registro['data']);
                    $hora   = $this->transformTime($registro['hora']);
                    $res = DB::table('alarmes')
                        ->where('mcu', '=',  $registro['mcu'])
                        ->where('data','=', $dt)
                        ->where('hora', '=', $hora )
                        ->select(
                            'alarmes.id'
                        )
                    ->first();
                    if(!empty(  $res->id ))
                    {
                        $alarme = Alarme::find($res->id);
                        $alarme->cliente      = $registro['cliente'];
                        $alarme->armedesarme  = $registro['armedesarme'];
                        $alarme->usuario  = $registro['usuario'];
                        $alarme->mcu  = $registro['mcu'];
                        $alarme->matricula  = $registro['matricula'];
                        $alarme->diaSemana    = $dt->dayOfWeek;
                        $alarme->data = $dt;
                        $alarme->hora = $hora;
                    }
                    else
                    {
                        $alarme = new Alarme;
                        $alarme->cliente      = $registro['cliente'];
                        $alarme->armedesarme  = $registro['armedesarme'];
                        $alarme->usuario  = $registro['usuario'];
                        $alarme->mcu  = $registro['mcu'];
                        $alarme->matricula  = $registro['matricula'];
                        $alarme->diaSemana    = $dt->dayOfWeek;
                        $alarme->data = $dt;
                        $alarme->hora = $hora;
                        $row ++;
                    }
                    $alarme->save();
                }
            }

            $affected = DB::table('alarmes')
                ->where('data', '<', $dtmenos12meses)
            ->delete();

            if ($row >= 1){
                \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
                    ,'class'=>'green white-text']);
            }
            return redirect()->route('importacao');
        }else{
            return back()->with(['errors'=>$validator->errors()->all()]);
        }
    }
    public function alarme()
    {
        return view('compliance.importacoes.alarme');  //
    }
    /// ######################### FIM ALARMES #######################

    /// ######################### BLOCO  SL02 BDF #######################
    public function exportSL02_bdf() {
        return Excel::download(new ExportSL02_bdf, 'SL02_bdf.xlsx');
    }
    public function importSL02_bdf(Request $request) {
        $row = 0;
        $dtmenos120dias = Carbon::now();
        $dtmenos120dias = $dtmenos120dias->subDays(120);

        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        if($request->file('file') == "") {
            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
            O Arquivo de ser  270-4-FINANCEIRO-SLD02_BDF_LimiteEncaixe.xls! Selecione Corretamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }

//        if( $request->file('file')->getClientOriginalName() != "270-4-FINANCEIRO-SLD02_BDF_LimiteEncaixe.xlsx") {
//            \Session::flash('mensagem',['msg'=>'Erro na Seleção do Arquivo.
//            O Arquivo de ser  270-4-FINANCEIRO-SLD02_BDF_LimiteEncaixe.xls! Selecione Corretamente'
//                ,'class'=>'red white-text']);
//            return redirect()->route('importacao');
//        }

        if($validator->passes())
        {
            ini_set('max_input_time', 350);
            ini_set('max_execution_time', 350);

            $SL02_bdfs = Excel::toArray(new ImportSL02_bdf,  request()->file('file'));
            foreach($SL02_bdfs as $registros)
            {
                foreach($registros as $dado)
                {
                    if(!empty($dado['dt_movimento']))
                    {
                        try
                        {
                            $dt = $this->transformDate(strtr($dado['dt_movimento'], '/', '-'));
                        }
                        catch (Exception $e)
                        {
                            $registro->Data='';
                        }
                    }
                    $saldo_atual = floatval($dado['saldo_atual']);
                    $limite = floatval($dado['limitevlr_limite_banco_postal_e_ect']);
                    $diferenca = $saldo_atual - $limite;

                    $res = DB::table('sl02bdfs')
                        ->where('cod_orgao', '=',  $dado['cod_orgao'])
                        ->where('dt_movimento','=', $dt)
                        ->select(
                            'sl02bdfs.id'
                        )
                    ->first();
                    if(!empty(  $res->id ))
                    {
                        $sl02bdfs = SL02_bdf::find($res->id);
                        $sl02bdfs->dr      = $dado['dr'];
                        $sl02bdfs->cod_orgao  = $dado['cod_orgao'];
                        $sl02bdfs->reop  = $dado['reop'];
                        $sl02bdfs->orgao  = $dado['orgao'];
                        $sl02bdfs->dt_movimento = $dt;
                        $sl02bdfs->saldo_atual = $saldo_atual;
                        $sl02bdfs->limite = $limite;
                        $sl02bdfs->diferenca = $diferenca;
                    }
                    else
                    {
                        $sl02bdfs = new SL02_bdf;
                        $sl02bdfs->dr      = $dado['dr'];
                        $sl02bdfs->cod_orgao  = $dado['cod_orgao'];
                        $sl02bdfs->reop  = $dado['reop'];
                        $sl02bdfs->orgao  = $dado['orgao'];
                        $sl02bdfs->dt_movimento = $dt;
                        $sl02bdfs->saldo_atual = $saldo_atual;
                        $sl02bdfs->limite = $limite;
                        $sl02bdfs->diferenca = $diferenca;
                    }
                   // dd(          $sl02bdfs);
                    $sl02bdfs->save();
                    $row ++;
                }
                $affected = DB::table('sl02bdfs')
                    ->where('dt_movimento', '<', $dtmenos120dias)
                    ->delete();

                \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
                    ,'class'=>'green white-text']);
                return redirect()->route('importacao');
            }
        } else {
            \Session::flash('mensagem',['msg'=>'Registros SL02_bdf Não pôde ser importado! Tente novamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }
    }
    public function SL02_bdf()
    {
        return view('compliance.importacoes.sld02_bdf');  //
    }
    /// ######################### FIM SL02 BDF ########################

    /// ######################### BLOCO  SMB BDF #######################
    public function exportSmb_bdf() {
        return Excel::download(new ExportSMBxBDF_NaoConciliado, 'SMBxBDF_NaoConciliado.xlsx');
    }
    public function importSmb_bdf(Request $request)
    {
        $row = 0;
        $dtmenos120dias = Carbon::now();
        $dtmenos120dias = $dtmenos120dias->subDays(120);

        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        if($request->file('file') == "") {
            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
            O Arquivo de ser  270-3-FINANCEIRO-SMB_ BDF_DepositosNaoConciliados.xls! Selecione Corretamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }

//        if( $request->file('file')->getClientOriginalName() != "270-3-FINANCEIRO-SMB_ BDF_DepositosNaoConciliados.xlsx") {
//            \Session::flash('mensagem',['msg'=>'Erro na Seleção do Arquivo.
//            O Arquivo de ser  270-3-FINANCEIRO-SMB_ BDF_DepositosNaoConciliados.xls! Selecione Corretamente'
//                ,'class'=>'red white-text']);
//            return redirect()->route('importacao');
//        }

        if($validator->passes())
        {
            $SMBxBDF_NaoConciliado = Excel::toArray(new ImportSMBxBDF_NaoConciliado,  request()->file('file'));
            foreach($SMBxBDF_NaoConciliado as $registros)
            {
                foreach($registros as $dado)
                {
                    $dt         = $this->transformDate($dado['data'])->format('Y-m-d');
                    $dt =   substr(   $dt,0,10);
                    try {
                        $dt         = $this->transformDate($dado['data']);
                    }catch (Exception $e)
                    {
                        $dt ='';
                    }

                    if($dado['smbdinheiro'] != 0){
                        $smbdinheiro = str_replace(",", ".", $dado['smbdinheiro']);
                    }
                    else
                    {
                        $smbdinheiro = 0.00;
                    }

                    if($dado['smbcheque'] != 0) {
                        $smbcheque = str_replace(",", ".", $dado['smbcheque']);
                    }
                    else
                    {
                        $smbcheque = 0.00;
                    }

                    if($dado['smbboleto'] != 0)
                    {
                        $smbboleto = str_replace(",", ".", $dado['smbboleto']);
                    }
                    else
                    {
                        $smbboleto = 0.00;
                    }

                    if($dado['smbestorno'] != 0)
                    {
                        $smbestorno = str_replace(",", ".", $dado['smbestorno']);
                    }
                    else
                    {
                        $smbestorno = 0.00;
                    }

                    if($dado['bdfdinheiro'] != 0)
                    {
                        $bdfdinheiro = str_replace(",", ".", $dado['bdfdinheiro']);
                    }
                    else
                    {
                        $bdfdinheiro = 0.00;
                    }

                    if($dado['bdfcheque'] != 0)
                    {
                        $bdfcheque = str_replace(",", ".", $dado['bdfcheque']);
                    }
                    else
                    {
                        $bdfcheque = 0.00;
                    }

                    if($dado['bdfboleto'] != 0)
                    {
                        $bdfboleto = str_replace(",", ".", $dado['bdfboleto']);
                    }
                    else
                    {
                        $bdfboleto = 0.00;
                    }

                    if($dado['divergencia'] != 0)
                    {
                        $divergencia = str_replace(",", ".", $dado['divergencia']);
                    }
                    else
                    {
                        $divergencia = 0.00;
                    }

                    $res = DB::table('smb_bdf_NaoConciliados')
                        ->where('mcu', '=',  $dado['mcu'])
                        ->where('data','=', $dt )
                        ->select(
                            'smb_bdf_NaoConciliados.id'
                        )
                    ->first();

                    if(!empty(  $res->id ))
                    {
                        $smb_bdf_naoconciliados = SMBxBDF_NaoConciliado::find($res->id);
                        $smb_bdf_naoconciliados->mcu  = $dado['mcu'];
                        $smb_bdf_naoconciliados->agencia      = $dado['agencia'];
                        $smb_bdf_naoconciliados->cnpj  = $dado['cnpj'];
                        $smb_bdf_naoconciliados->status  = $dado['status'];
                        $smb_bdf_naoconciliados->Data = $dt;
                        $smb_bdf_naoconciliados->smbdinheiro = $smbdinheiro;
                        $smb_bdf_naoconciliados->smbcheque = $smbcheque;
                        $smb_bdf_naoconciliados->smbboleto = $smbboleto;
                        $smb_bdf_naoconciliados->smbestorno = $smbestorno;
                        $smb_bdf_naoconciliados->bdfdinheiro = $bdfdinheiro;
                        $smb_bdf_naoconciliados->bdfcheque = $bdfcheque;
                        $smb_bdf_naoconciliados->bdfboleto = $bdfboleto;
                        $smb_bdf_naoconciliados->divergencia = $divergencia;
                    }
                    else
                    {
                        if($dado['status'] == 'Pendente' )
                        {
                            $smb_bdf_naoconciliados = new SMBxBDF_NaoConciliado;
                            $smb_bdf_naoconciliados->mcu  = $dado['mcu'];
                            $smb_bdf_naoconciliados->agencia      = $dado['agencia'];
                            $smb_bdf_naoconciliados->cnpj  = $dado['cnpj'];
                            $smb_bdf_naoconciliados->status  = $dado['status'];
                            $smb_bdf_naoconciliados->Data = $dt;
                            $smb_bdf_naoconciliados->smbdinheiro = $smbdinheiro;
                            $smb_bdf_naoconciliados->smbcheque = $smbcheque;
                            $smb_bdf_naoconciliados->smbboleto = $smbboleto;
                            $smb_bdf_naoconciliados->smbestorno = $smbestorno;
                            $smb_bdf_naoconciliados->bdfdinheiro = $bdfdinheiro;
                            $smb_bdf_naoconciliados->bdfcheque = $bdfcheque;
                            $smb_bdf_naoconciliados->bdfboleto = $bdfboleto;
                            $smb_bdf_naoconciliados->divergencia = $divergencia;
                        }
                    }
                    $smb_bdf_naoconciliados->save();
                    $row ++;
                }
                $affected = DB::table('smb_bdf_naoconciliados')
                    ->where('data', '<', $dtmenos120dias)
                    ->delete();
            }
            \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
                ,'class'=>'green white-text']);
            return redirect()->route('importacao');
        }else{
            \Session::flash('mensagem',['msg'=>'Registros SMBxBDF_NaoConciliado Não pôde ser importado! Tente novamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }
    }
    public function smb_bdf() {
        return view('compliance.importacoes.smb_bdf');  //
    }
    /// ######################### FIM SMB BDF #######################

    /// ######################### BLOCO  PROTER#######################
    public function exportProter()
    {
        return Excel::download(new ExportProter, 'proters.xlsx');
    }
    public function importProter(Request $request)
    {
        $row=0;
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        if($request->file('file') == "")
        {
            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
    O Arquivo de ser 270-2-FINANCEIRO-Proter_ProtecaoReceita.xls! Selecione Corretamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }
//        if( $request->file('file')->getClientOriginalName() != "270-2-FINANCEIRO-Proter_ProtecaoReceita.xlsx")
//        {
//            \Session::flash('mensagem',['msg'=>'Erro na Seleção do Arquivo.
//    O Arquivo de ser 270-2-FINANCEIRO-Proter_ProtecaoReceita.xls! Selecione Corretamente'
//                ,'class'=>'red white-text']);
//            return redirect()->route('importacao');
//        }

        if($validator->passes())
        {
           ini_set('max_input_time', 350);
           ini_set('max_execution_time', 350);
//            DB::table('proters')->truncate(); //excluir e zerar a tabela
            $proters = Excel::toArray(new ImportProter,  request()->file('file'));
            $dt = Carbon::now();
            foreach($proters as $registros)
            {

                foreach($registros as $dado)
                {
                    $proter = DB::table('proters')
                        ->where('no_do_objeto', '=', $dado['no_do_objeto'])
                        ->select('proters.*')
                    ->first();
                    if(!empty(  $proter->id ))
                    {
                        $affected = DB::table('proters')
                            ->where('no_do_objeto', '=', $dado['no_do_objeto'])
                            ->where('status_da_pendencia', '<>', 'Pendente')
                        ->delete();
                        if( $affected == 0)
                        {
                            if(($dado['tipo_de_pendencia']  == 'CON' )or($dado['tipo_de_pendencia']  == 'DPC' ) && ($dado['status_da_pendencia'] == 'Pendente'))
                            {
                                $registro = Proter::find($proter->id);
                                if($this->transformDate($dado['data_da_pendencia'])=='1970-01-01 00:00:00')

                                {
                                    $registro->data_da_pendencia = null;
                                }
                                else
                                {
                                    $registro->data_da_pendencia = $this->transformDate($dado['data_da_pendencia']);
                                }

                                if($this->transformDate($dado['data_da_entrega'])=='1970-01-01 00:00:00')
                                {
                                    $registro->data_da_entrega = null;
                                }
                                else
                                {
                                    $registro->data_da_entrega = $this->transformDate($dado['data_da_entrega']);
                                }

                                if($this->transformDate($dado['data_da_postagem'])=='1970-01-01 00:00:00')
                                {
                                    $registro->data_da_postagem = null;
                                }
                                else
                                {
                                    $registro->data_da_postagem = $this->transformDate($dado['data_da_postagem']);
                                }

                                if($this->transformDate($dado['data_de_leitura'])=='1970-01-01 00:00:00')
                                {
                                    $registro->data_de_leitura = null;
                                }
                                else
                                {
                                    $registro->data_de_leitura = $this->transformDate($dado['data_de_leitura']);
                                }

                                if(!empty($dado['data_ultima_manifestacao']))
                                {
                                    if($this->transformDate($dado['data_ultima_manifestacao'])=='1970-01-01 00:00:00')
                                    {
                                        $registro->data_ultima_manifestacao = null;
                                    }
                                    else
                                    {
                                        $registro->data_ultima_manifestacao = $this->transformDate($dado['data_ultima_manifestacao']);
                                    }
                                }



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
                                    catch (Exception $e)
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
                                    catch (Exception $e)
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
                                    catch (Exception $e)
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

                    }
                    else
                    {
                        if(($dado['tipo_de_pendencia']  == 'CON' )or($dado['tipo_de_pendencia']  == 'DPC' ) && ($dado['status_da_pendencia'] == 'Pendente'))
                        {
                         //   dd($dado);
                            $registro = new Proter;

                            if($this->transformDate($dado['data_da_pendencia'])=='1970-01-01 00:00:00')

                            {
                                $registro->data_da_pendencia = null;
                            }
                            else
                            {
                                $registro->data_da_pendencia = $this->transformDate($dado['data_da_pendencia']);
                            }

                            if($this->transformDate($dado['data_da_entrega'])=='1970-01-01 00:00:00')
                            {
                                $registro->data_da_entrega = null;
                            }
                            else
                            {
                                $registro->data_da_entrega = $this->transformDate($dado['data_da_entrega']);
                            }

                            if($this->transformDate($dado['data_da_postagem'])=='1970-01-01 00:00:00')
                            {
                                $registro->data_da_postagem = null;
                            }
                            else
                            {
                                $registro->data_da_postagem = $this->transformDate($dado['data_da_postagem']);
                            }

                            if($this->transformDate($dado['data_de_leitura'])=='1970-01-01 00:00:00')
                            {
                                $registro->data_de_leitura = null;
                            }
                            else
                            {
                                $registro->data_de_leitura = $this->transformDate($dado['data_de_leitura']);
                            }

                            if(!empty($dado['data_ultima_manifestacao']))
                            {
                                if($this->transformDate($dado['data_ultima_manifestacao'])=='1970-01-01 00:00:00')
                                {
                                    $registro->data_ultima_manifestacao = null;
                                }
                                else
                                {
                                    $registro->data_ultima_manifestacao = $this->transformDate($dado['data_ultima_manifestacao']);
                                }
                            }

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
                                catch (Exception $e)
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
                                catch (Exception $e)
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
                                catch (Exception $e)
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
              //      dd( $registro );
                    $registro ->save();
                    $row++;
                }
                $affected = DB::table('proters')
                    ->where('updated_at', '<', $dt)
                   // ->get();
                ->delete();
            //    dd('nao atualizados -> ', $affected );
            }
            \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
                ,'class'=>'green white-text']);
            return redirect()->route('importacao');
        }else{
              \Session::flash('mensagem',['msg'=>'Registros PROTER Não pôde ser importado! Tente novamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }
    }
    public function proter()
    {
        return view('compliance.importacoes.proter');  //
    }
    /// ######################### FIM PROTER #######################


    // ######################### INICIO FERIADOS #######################

    public function exportFeriado()
    {
        return Excel::download(new ExportFeriado, 'feriados.xlsx');
    }
    public function importFeriado(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        if(empty($request->file('file')))
        {
            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
            O Arquivo de ser feriados.xlsx ! Selecione Corretamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }
//        if( $request->file('file')->getClientOriginalName() != "Feriados.xlsx") {
//
//            \Session::flash('mensagem',['msg'=>'Erro na Seleção do Arquivo.
//            O Arquivo de ser  Feriados.xls! Selecione Corretamente'
//                ,'class'=>'red white-text']);
//            return redirect()->route('importacao');
//        }

        if($validator->passes())
        {
            $feriados = Excel::toArray(new ImportFeriado,  request()->file('file'));
          //  DB::table('feriados')->truncate(); //excluir e zerar a tabela
            ini_set('max_input_time', 350);
            ini_set('max_execution_time', 350);
            foreach($feriados as $dados)
            {
                $row = 0;
                foreach($dados as $registro)
                {
                    $cad = DB::table('feriados')
                        ->where('uf', '=',  $registro['uf'])
                        ->where('nome_municipio', '=',  $registro['nome_municipio'])
                        ->where('tipo_feriado', '=',  $registro['tipo_feriado'])
                        ->where('data_do_feriado', '=', $this->transformDateMesDia($registro['data_do_feriado']))
                        ->select(
                            'feriados.id'
                        )
                        ->first();

                    if(!empty(  $cad->id ))
                    {
                        $feriado = Feriado::find($cad->id);
                        $feriado->uf      = $registro['uf'];
                        $feriado->nome_municipio  = $registro['nome_municipio'];
                        $feriado->tipo_feriado  = $registro['tipo_feriado'];
                        $feriado->descricao_feriado  = $registro['descricao_feriado'];
                        $dt         = $this->transformDateMesDia($registro['data_do_feriado']);
                        $feriado->data_do_feriado         = $dt;
                    }
                    else
                    {
                        $feriado = new Feriado;
                        $feriado->uf      = $registro['uf'];
                        $feriado->nome_municipio  = $registro['nome_municipio'];
                        $feriado->tipo_feriado  = $registro['tipo_feriado'];
                        $feriado->descricao_feriado  = $registro['descricao_feriado'];
                        $dt         = $this->transformDateMesDia($registro['data_do_feriado']);
                        $feriado->data_do_feriado         = $dt;
                    }
                    $feriado ->save();
                    $row ++;
                }
            }
            \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
                ,'class'=>'green white-text']);
            return redirect()->route('importacao');
        }else{
            return back()->with(['errors'=>$validator->errors()->all()]);
        }
    }
    public function feriado()
    {
        return view('compliance.importacoes.feriado');  //
    }
    /// ######################### FIM FERIADOS #######################

    /// ######################### BLOCO  CADASTRAL #######################
    public function exportCadastral()
    {
        return Excel::download(new ExportCadastral, 'cadastrals.xlsx');
    }
    public function importCadastral(Request $request)
    {
        $row=0;
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:xlsx'
        ]);
        if($request->file('file') == "")
        {
            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
            O Arquivo de ser  WebSGQ 3 - Efetivo analitico por MCU.xlsx! Selecione Corretamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }

        if($validator->passes())
        {
          //  DB::table('cadastral')->truncate(); //excluir e zerar a tabela
            $cadastrals = Excel::toArray(new ImportCadastral,  request()->file('file'));
            $dt = Carbon::now();
            ini_set('max_input_time', 350);
            ini_set('max_execution_time', 350);
            foreach($cadastrals as $registros)
            {
                foreach($registros as $dado)
                {
                    $cad = DB::table('cadastral')
                        ->where('matricula', '=',  $dado['matricula'])
                        ->select(
                            'cadastral.id'
                        )
                        ->first();
                    if(!empty(  $cad->id ))
                    {
                        $registro = Cadastral::find($cad->id);
                        $registro->se      = $dado['secs'];
                        $registro->mcu      = (int)$dado['mcu'];
                        $registro->lotacao      = $dado['lotacao'];
                        $registro->matricula      = $dado['matricula'];
                        $registro->nome_do_empregado      = $dado['nome'];
                        $registro->cargo      = $dado['cargo'];
                        $registro->especializ      = $dado['especialidade'];
                        $registro->funcao      = $dado['funcao'];
                        $registro->situacao      = 'ATIVO';
                        $registro->updated_at = Carbon::now();
                    }
                    else
                    {
                        $registro = new Cadastral();
                        $registro->se      = $dado['secs'];
                        $registro->mcu      = (int)$dado['mcu'];
                        $registro->lotacao      = $dado['lotacao'];
                        $registro->matricula      = $dado['matricula'];
                        $registro->nome_do_empregado      = $dado['nome'];
                        $registro->cargo      = $dado['cargo'];
                        $registro->especializ      = $dado['especialidade'];
                        $registro->funcao      = $dado['funcao'];
                        $registro->situacao      = 'ATIVO';
                    }
                    $registro ->save();
                    $row++;
               }
                $affected = DB::table('cadastral')
                    ->where('se', $dado['secs'])
                    ->where('updated_at', '<', $dt)
                ->update(['situacao' => null]);
            }
            \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
                ,'class'=>'green white-text']);
            return redirect()->route('importacao');
        }else{
            \Session::flash('mensagem',['msg'=>'Registros Cadastral Não pôde ser importado! Tente novamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }
    }
    public function cadastral()
    {
        return view('compliance.importacoes.cadastral');
    }
    /// ######################### FIM CADASTRAL #######################

    // ######################### INICIO IMPORTAR UNIDADES #######################
    public function importUnidades(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        if(empty($request->file('file')))
        {
            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
        O Arquivo de ser R55001A.xlsx ! Selecione Corretamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }
        if( $request->file('file')->getClientOriginalName() != "R55001A.xlsx") {

            \Session::flash('mensagem',['msg'=>'Erro na Seleção do Arquivo.
        O Arquivo de ser  R55001A.xls! Selecione Corretamente'
                ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }


        if($validator->passes()) {
            ini_set('max_input_time', 350);
            ini_set('max_execution_time', 350);
            $unidades = Excel::toArray(new ImportUnidades,  request()->file('file'));
            $rowupdate = 0;
            $rowinsert = 0;
            foreach($unidades as $dados) {
                foreach($dados as $registro) {
                    $res = DB::table('unidades')
                        ->where('an8', '=',  (int)$registro['no_cad_geral'])
                        ->select(
                            'unidades.*'
                        )
                        ->first();
                   // dd( $res);
                    $tipodeunidade = DB::table('tiposdeunidade')
                        ->where('codigo', '=',  (int)$registro['tipo_do_orgao'])
                        ->orWhere('tipodescricao', '=',  $registro['descricao_tp_orgao'])
                        ->select(
                            'tiposdeunidade.id'
                        )
                        ->first();

               //     dd( $tipodeunidade->id);

                    //***
                    //  gravar somente se tiver tipo de unidade prevista para inspeção
                    if(!empty(  $tipodeunidade->id ))
                    {
                        $enderecounidades = DB::table('unidade_enderecos')
                            ->where('mcu', '=',  (int)$registro['unidades_de_negocios'])
                            ->select(
                                'unidade_enderecos.id'
                            )
                            ->first();


                        if(!empty(  $enderecounidades->id ))
                        {
                         //   dd('temendereco');
                            $enderecos = UnidadeEndereco::find($enderecounidades->id);
                            $enderecos->codIbge =	$registro['codigo_ibge_do_municipio'];
                            $enderecos->endereco = $registro['endereco'];
                            $enderecos->complemento =	$registro['complemento_endereco'];
                            $enderecos->bairro =	$registro['bairro'];
                            $enderecos->cidade =	$registro['cidade'];
                            $enderecos->uf =	$registro['uf'];
                            $enderecos->cep =	$registro['cep'];
                        }
                        else
                        {
                         //   dd('nao tem endereco');
                            $enderecos = new UnidadeEndereco();
                            $enderecos->mcu =	$registro['unidades_de_negocios'];
                            $enderecos->codIbge =	$registro['codigo_ibge_do_municipio'];
                            $enderecos->endereco = $registro['endereco'];
                            $enderecos->complemento =	$registro['complemento_endereco'];
                            $enderecos->bairro =	$registro['bairro'];
                            $enderecos->cidade =	$registro['cidade'];
                            $enderecos->uf =	$registro['uf'];
                            $enderecos->cep =	$registro['cep'];
                        }
                        if (!$res){
                          //  dd( 'nao ta cadastrada');
                            $unidade = new Unidade;
                            $unidade->tipoUnidade_id      = $tipodeunidade->id;
                            $unidade->mcu =$registro['unidades_de_negocios'];
                            $unidade->se =$registro['dr'];
                            $unidade->seDescricao =$registro['descricao_dr'];
                            $unidade->an8 =$registro['no_cad_geral'];
                            $unidade->sto =$registro['sto'];
                            $unidade->status_unidade =$registro['status_do_orgao'];
                            $unidade->status_unidadeDesc =$registro['descricao_status'];
                            $unidade->descricao =$registro['nome_fantasia'];
                            $unidade->tipoOrgaoCod =$registro['tipo_do_orgao'];
                            $unidade->tipoOrgaoDesc =$registro['descricao_tp_orgao'];
                            $unidade->cnpj =$registro['cnpj'];
                            $unidade->categoria =$registro['categoria'];
                            $unidade->mecanizacao =$registro['descricao_do_tp_mecanizacao'];
                            $unidade->faixaCepIni =$registro['faixa_ini_cep'];
                            $unidade->faixaCepFim =$registro['faixa_fim_cep'];
                            $unidade->tem_distribuicao =$registro['distribuicao'];
                            $unidade->quantidade_guiches =$registro['quantidade_guiches'];
                            $unidade->guiches_ocupados =$registro['guiches_ocupados'];
                            $unidade->ddd =$registro['ddd'];
                            $unidade->telefone =$registro['telefone_principal'];
                            $unidade->mcu_subordinacaoAdm =$registro['subordinacao_administrativa'];
                            $unidade->desc_subordinacaoAdm =$registro['descricao_subordinacao_adm'];
                            $unidade->nomeResponsavelUnidade =$registro['nome_responsavel'];
                            $unidade->documentRespUnidade =$registro['matricula_responsavel'];
                            $unidade->email=$registro['email_da_unidade'];
                            $unidade->tipoEstrutura = $registro['tipo_de_estrutura'];
                            $unidade->subordinacao_tecnica =$registro['subordinacao_tecnica'];

                            if(!empty($registro['inicio_expediente']))
                            {
                                $unidade->inicio_expediente =$registro['inicio_expediente'];
                                $unidade->final_expediente =$registro['final_expediente'];
                                $unidade->inicio_intervalo_refeicao =$registro['inicio_intervalo_refeicao'];
                                $unidade->final_intervalo_refeicao =$registro['final_intervalo_refeicao'];
                                $unidade->trabalha_sabado =$registro['trabalha_sabado'];
                                $unidade->inicio_expediente_sabado =$registro['inicio_expediente_sabado'];
                                $unidade->final_expediente_sabado =$registro['final_expediente_sabado'];
                                $unidade->trabalha_domingo =$registro['trabalha_domingo'];
                                $unidade->inicio_expediente_domingo =$registro['inicio_expediente_domingo'];
                                $unidade->final_expediente_domingo =$registro['final_expediente_domingo'];
                                $unidade->tem_plantao =$registro['tem_plantao'];
                                $unidade->inicio_plantao_sabado =$registro['inicio_plantao_sabado'];
                                $unidade->final_plantao_sabado =$registro['final_plantao_sabado'];
                                $unidade->inicio_plantao_domingo =$registro['inicio_plantao_domingo'];
                                $unidade->final_plantao_domingo =$registro['final_plantao_domingo'];
                                $unidade->inicio_distribuicao =$registro['inicio_distribuicao'];
                                $unidade->final_distribuicao =$registro['final_distribuicao'];
                                $unidade->horario_lim_post_na_semana =$registro['horario_lim_post_na_semana'];
                                $unidade->horario_lim_post_final_semana =$registro['horario_lim_post_final_semana'];
                            }
//                            dd('nao esta cadastrada',    $unidade->tipoUnidade_id , $registro ,$unidade, $enderecos);
                            $unidade->save();
                            $enderecos->save();
                            $rowinsert ++;
                        }
                        else
                        {

//                            $unidade->an8 =$registro['no_cad_geral'];// não atualizar primary key logico
                            $unidade = Unidade::find($res->id);
                            $unidade->tipoUnidade_id      = $tipodeunidade->id;
                            $unidade->mcu =$registro['unidades_de_negocios'];
                            $unidade->se =$registro['dr'];
                            $unidade->seDescricao =$registro['descricao_dr'];
                            $unidade->sto =$registro['sto'];
                            $unidade->status_unidade =$registro['status_do_orgao'];
                            $unidade->status_unidadeDesc =$registro['descricao_status'];
                            $unidade->descricao =$registro['nome_fantasia'];
                            $unidade->tipoOrgaoCod =$registro['tipo_do_orgao'];
                            $unidade->tipoOrgaoDesc =$registro['descricao_tp_orgao'];
                            $unidade->cnpj =$registro['cnpj'];
                            $unidade->categoria =$registro['categoria'];
                            $unidade->mecanizacao =$registro['descricao_do_tp_mecanizacao'];
                            $unidade->faixaCepIni =$registro['faixa_ini_cep'];
                            $unidade->faixaCepFim =$registro['faixa_fim_cep'];
                            $unidade->tem_distribuicao =$registro['distribuicao'];
                            $unidade->quantidade_guiches =$registro['quantidade_guiches'];
                            $unidade->guiches_ocupados =$registro['guiches_ocupados'];
                            $unidade->ddd =$registro['ddd'];
                            $unidade->telefone =$registro['telefone_principal'];
                            $unidade->mcu_subordinacaoAdm =$registro['subordinacao_administrativa'];
                            $unidade->desc_subordinacaoAdm =$registro['descricao_subordinacao_adm'];
                            $unidade->nomeResponsavelUnidade =$registro['nome_responsavel'];
                            $unidade->documentRespUnidade =$registro['matricula_responsavel'];
                            $unidade->email=$registro['email_da_unidade'];
                            $unidade->tipoEstrutura = $registro['tipo_de_estrutura'];
                            $unidade->subordinacao_tecnica =$registro['subordinacao_tecnica'];

                            if(!empty($registro['inicio_expediente']))
                            {
                                $unidade->inicio_expediente =$registro['inicio_expediente'];
                                $unidade->final_expediente =$registro['final_expediente'];
                                $unidade->inicio_intervalo_refeicao =$registro['inicio_intervalo_refeicao'];
                                $unidade->final_intervalo_refeicao =$registro['final_intervalo_refeicao'];
                                $unidade->trabalha_sabado =$registro['trabalha_sabado'];
                                $unidade->inicio_expediente_sabado =$registro['inicio_expediente_sabado'];
                                $unidade->final_expediente_sabado =$registro['final_expediente_sabado'];
                                $unidade->trabalha_domingo =$registro['trabalha_domingo'];
                                $unidade->inicio_expediente_domingo =$registro['inicio_expediente_domingo'];
                                $unidade->final_expediente_domingo =$registro['final_expediente_domingo'];
                                $unidade->tem_plantao =$registro['tem_plantao'];
                                $unidade->inicio_plantao_sabado =$registro['inicio_plantao_sabado'];
                                $unidade->final_plantao_sabado =$registro['final_plantao_sabado'];
                                $unidade->inicio_plantao_domingo =$registro['inicio_plantao_domingo'];
                                $unidade->final_plantao_domingo =$registro['final_plantao_domingo'];
                                $unidade->inicio_distribuicao =$registro['inicio_distribuicao'];
                                $unidade->final_distribuicao =$registro['final_distribuicao'];
                                $unidade->horario_lim_post_na_semana =$registro['horario_lim_post_na_semana'];
                                $unidade->horario_lim_post_final_semana =$registro['horario_lim_post_final_semana'];
                            }

                      //      dd($registro ,$unidade);
                            $unidade->update();
                            $enderecos->update();
                            $rowupdate ++;
                        }
                    }
                    else{
                        \Session::flash('mensagem',['msg'=>'O Arquivo lido não foi incorporado confira o pré requisito para importação desse arquivo'
                            ,'class'=>'green white-text']);
                    }
                }
            }

          //  dd("Unidades -> ". $unidade , " Endereços -> ".$enderecos );

            $row = 0;
            $row = $rowupdate + $rowinsert;
            if ($row >= 1){
                \Session::flash('mensagem',['msg'=>'O Arquivo de Unidades foi importado com '.$rowupdate.' atualizados e '.$rowinsert.' registros novos.'
                    ,'class'=>'green white-text']);
            }
            return redirect()->route('importacao');
        }else{
            return back()->with(['errors'=>$validator->errors()->all()]);
        }
    }
    public function unidades()
    {
        return view('compliance.importacoes.unidades');  //
    }
    // ######################### FIM IMPORTAR UNIDADES #######################

    // ######################### INICIO    Pagamentos adicionais  #######################
    public function exportpagamentosAdicionais()
    {
        return Excel::download(new ExportPagamentosAdicionais, 'pagamentosAdicionais.xlsx');
    }
    public function importPagamentosAdicionais(Request $request)
    {
        $dtmenos365dias = Carbon::now();
        $dtmenos365dias->subDays(365);
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:xlsx'
        ]);

        if(empty($request->file('file'))){
            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
            O Arquivo de ser 278-2-WebSGQ-3-PagamentosAdicionais.xlsx ! Selecione Corretamente'
            ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }

//        if( $request->file('file')->getClientOriginalName() != "278-2-WebSGQ-3-PagamentosAdicionais.xlsx") {
//            \Session::flash('mensagem',['msg'=>'Erro na Seleção do Arquivo.
//            O Arquivo de ser 278-2-WebSGQ-3-PagamentosAdicionais.xls! Selecione Corretamente'
//            ,'class'=>'red white-text']);
//            return redirect()->route('importacao');
//        }

        if($validator->passes())
        {
            $dtmenos10meses = new Carbon();
            $dtmenos10meses->subMonth(10);
            $row = 0;
            $ref = substr($dtmenos10meses,0,4). substr($dtmenos10meses,5,2);

            DB::table('pagamentos_adicionais')->where('ref', '<=', $ref)->delete();

            $pagamentos_adicionais = Excel::toArray(new ImportPagamentosAdicionais,  request()->file('file'));
            foreach($pagamentos_adicionais as $dados)
            {
                foreach($dados as $registro)
                {
                    $res = DB::table('pagamentos_adicionais')
                        ->where('se', '=',  $registro['se'])
                        ->where('sigla_lotacao', '=',  $registro['sigla_lotacao'])
                        ->where('matricula', '=',  $registro['matricula'])
                        ->where('rubrica', '=',  $registro['rubrica'])
                        ->where('ref', '=',  $registro['ref'])
                        ->select(
                            'pagamentos_adicionais.id'
                        )
                    ->first();
                    if (empty($res->id))
                    {
                        $pgtoAdicionais = new PagamentosAdicionais;
                        $pgtoAdicionais->se      = $registro['se'];
                        $pgtoAdicionais->sigla_lotacao  = $registro['sigla_lotacao'];
                        $pgtoAdicionais->matricula  = $registro['matricula'];
                        $pgtoAdicionais->nome  = $registro['nome'];
                        $pgtoAdicionais->cargo  = $registro['cargo'];
                        $pgtoAdicionais->espec  = $registro['espec'];
                        $pgtoAdicionais->titular_da_funcao  = $registro['titular_da_funcao'];
                        $pgtoAdicionais->dif_mer  = $registro['dif_mer'];
                        $pgtoAdicionais->rubrica  = $registro['rubrica'];
                        $pgtoAdicionais->qtd  = $registro['qtd'];
                        $pgtoAdicionais->valor  = $registro['valor'];
                        $pgtoAdicionais->ref  = $registro['ref'];
                        $pgtoAdicionais->save();
                        $row ++;
                    }

                }
            }
            if ($row >= 1){
                \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
                    ,'class'=>'green white-text']);
            }else{
                \Session::flash('mensagem',['msg'=>'O Arquivo lido e encontrado todos registros existentes'
                    ,'class'=>'green white-text']);
            }
            return redirect()->route('importacao');
        }else{
            return back()->with(['errors'=>$validator->errors()->all()]);
        }
    }
    public function pagamentosAdicionais()
    {
        return view('compliance.importacoes.pagamentosAdicionais');  //
    }
    // ######################### FIM  Pagamentos adicionais


     // ######################### INICIO    Painel de extravio  #######################
         public function exportpainelExtravio()
         {
            return Excel::download(new ExportPainelExtravio, 'painelExtravio.xlsx');
         }

        public function importPainelExtravio(Request $request)
        {
            $time='350';
            ini_set('max_input_time', $time);
            ini_set('max_execution_time', $time);
            $dtmenos365dias = Carbon::now();
            $dtmenos365dias->subDays(365);
            $row = 0;
            $validator = Validator::make($request->all(),[
                  'file' => 'required|mimes:xlsx,xls,csv'
            ]);
            if(empty($request->file('file')))
            {
                \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
                O Arquivo de ser 277-5-PainelExtravio.xlsx ! Selecione Corretamente'
                ,'class'=>'red white-text']);
                return redirect()->route('importacao');
            }
//            if( $request->file('file')->getClientOriginalName() != "277-5-PainelExtravio.xlsx")
//            {
//                \Session::flash('mensagem',['msg'=>'Erro na Seleção do Arquivo.
//                O Arquivo de ser 277-5-PainelExtravio.xls! Selecione Corretamente'
//                ,'class'=>'red white-text']);
//                return redirect()->route('importacao');
//            }
            if($validator->passes())
            {
                $painel_extravios = Excel::toArray(new ImportPainelExtravio,  request()->file('file'));

                foreach($painel_extravios as $dados)
                {
                    foreach($dados as $registro)
                    {
                        if(!empty($registro['data_evento'])) {
                            try {
                                $data_evento = $this->transformDate($registro['data_evento']);
                            }
                            catch (Exception $e)
                            {
                                $data_evento       = "";
                            }
                        }
                        $ultimo_evento_data  = null;
                        if(!empty($registro['ultimo_evento_data']))
                        {
                            try
                            {
                                $ultimo_evento_data = $this->transformDate($registro['ultimo_evento_data']);
                            }
                            catch (Exception $e)
                            {
                                $ultimo_evento_data       = "";
                            }
                        }
                        $data_postagem       = null;
                        if(!empty($registro['data_postagem']))
                        {
                            try
                            {
                                $data_postagem = $this->transformDate($registro['data_postagem']);
                            }
                            catch (Exception $e)
                            {
                                $data_postagem          = null;
                            }
                        }

                        $res = DB::table('painel_extravios')
                            ->where('objeto', '=',  $registro['objeto'])
                            ->where('data_evento','=', $data_evento)
                            ->select(
                                'painel_extravios.objeto'
                            )
                        ->first();
                        if(empty($res))
                        {
                            $painelExtravio = new PainelExtravio;
                            $painelExtravio->objeto      = $registro['objeto'];
                            $painelExtravio->data_evento      = $data_evento;
                            $painelExtravio->evento      = $registro['evento'];
                            $painelExtravio->cliente      = $registro['cliente'];
                            $painelExtravio->trecho      = $registro['trecho'];
                            $painelExtravio->evento_trecho      = $registro['evento_trecho'];
                            $painelExtravio->unid_origem      = $registro['unid_origem'];
                            $painelExtravio->unid_destino      = $registro['unid_destino'];
                            $painelExtravio->dr_origem      = $registro['dr_origem'];
                            $painelExtravio->dr_destino      = $registro['dr_destino'];
                            $painelExtravio->gestao_prealerta      = $registro['gestao_prealerta'];
                            $painelExtravio->automatico      = $registro['automatico'];
                            $painelExtravio->manual      = $registro['manual'];
                            $painelExtravio->total      = $registro['total'];
                            $painelExtravio->macroprocesso      = $registro['macroprocesso'];
                            $painelExtravio->ultimo_evento_extraviado      = $registro['ultimo_evento_extraviado'];
                            $painelExtravio->ultimo_evento_em_transito      = $registro['ultimo_evento_em_transito'];
                            $painelExtravio->ultimo_evento      = $registro['ultimo_evento'];
                            $painelExtravio->ultimo_evento_data      = $ultimo_evento_data;
                            $painelExtravio->evento_finalizador      = $registro['evento_finalizador'];
                            $painelExtravio->tipo      = $registro['tipo'];
                            $painelExtravio->analise_sro      = $registro['analise_sro'];
                            $painelExtravio->unid_origem_apelido      = $registro['unid_origem_apelido'];
                            $painelExtravio->unid_destino_apelido      = $registro['unid_destino_apelido'];
                            $painelExtravio->trecho_real      = $registro['trecho_real'];
                            $painelExtravio->se_postagem      = $registro['se_postagem'];
                            $painelExtravio->unidade_postagem      = $registro['unidade_postagem'];
                            $painelExtravio->data_postagem      = $data_postagem;
                            $painelExtravio->familia      = $registro['familia'];
                            $painelExtravio->ultimo_evento_sinistro      = $registro['ultimo_evento_sinistro'];
                            $painelExtravio->save();
                            $row ++;
                        }
                     //   dd( ' 1812 ', $res);
                    }
                }
                DB::table('painel_extravios')
                    ->where('data_evento', '<=', $dtmenos365dias)
                ->delete();
                if ($row >= 1)
                {
                    \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
                        ,'class'=>'green white-text']);
                }
                else
                {
                    \Session::flash('mensagem',['msg'=>'O Arquivo lido e encontrado todos registros existentes'
                        ,'class'=>'green white-text']);
                }
                return redirect()->route('importacao');
            }
            else
            {
                return back()->with(['errors'=>$validator->errors()->all()]);
            }
        }

        public function painelExtravio()
        {
            return view('compliance.importacoes.painelExtravio');  //
        }
        // ######################### FIM  painel de extravio #######################
        // ######################### INICIO    cie eletronica  #######################

        public function exportcieEletronica()
        {
            return Excel::download(new ExportCieEletronica, 'cieEletronica.xlsx');
        }

        public function importCieEletronica(Request $request)
    {
        $row = 0;
        $dtmenos365dias = Carbon::now();
        $dtmenos365dias->subDays(365);
        $validator = Validator::make($request->all(),[
        'file' => 'required|mimes:xlsx'
        ]);

        if(empty($request->file('file')))
        {
            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
            O Arquivo de ser 277-7-CieEletronica.xlsx ! Selecione Corretamente'
            ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }

//        if( $request->file('file')->getClientOriginalName() != "277-7-CieEletronica.xlsx") {
//
//            \Session::flash('mensagem',['msg'=>'Erro na Seleção do Arquivo.
//            O Arquivo de ser  277-7-CieEletronica.xls! Selecione Corretamente'
//            ,'class'=>'red white-text']);
//            return redirect()->route('importacao');
//        }

        if($validator->passes())
        {
            $cie_eletronicas = Excel::toArray(new ImportCieEletronica,  request()->file('file'));
            foreach($cie_eletronicas as $dados)
            {
                foreach($dados as $registro)
                {
                    $emissao       = null;
                    if(!empty($registro['emissao']))
                    {
                        if (strlen($registro['emissao']) > 11 )
                        {
                            try
                            {
                                //  31/07/2020 22:10:29
                                //$emissao=substr($registro['emissao'],6,4);
                                //$emissao=substr($registro['emissao'],3,2);
                                //$emissao=substr($registro['emissao'],0,2).'/'.substr($registro['emissao'],3,2).'/'.substr($registro['emissao'],6,4).' '.$emissao=substr($registro['emissao'],11,8);
                                //$emissao=substr($registro['emissao'],0,2).'/'.substr($registro['emissao'],3,2).'/'.substr($registro['emissao'],6,4).' '.$emissao=substr($registro['emissao'],11,8);
                                $emissao =substr($registro['emissao'],6,4).'-'.$emissao=substr($registro['emissao'],3,2).'-'.$emissao=substr($registro['emissao'],0,2).' '.$emissao=substr($registro['emissao'],11,8);
                                //var_dump($emissao );
                                //->toDateTimeString();
                                //$emissao = $this->transformDate($registro['emissao']).' '.$this->transformTime($registro['emissao']);
                                //Carbon::createFromFormat('m/d/Y H:i:s', $registro['emissao'])->format('Y-m-d H:i:s');
                            }
                            catch (Exception $e)
                            {
                                $emissao        = null;
                            }
                        }
                        else
                        {
                            try
                            {
                                $emissao = $this->transformDate($registro['emissao']);
                           }
                           catch (Exception $e)
                           {
                                $emissao      = null;
                           }
                        }
                    }
                    $data_de_resposta       = null;
                    if(!empty($registro['data_de_resposta']))
                    {
                        try
                        {
                            $data_de_resposta = $this->transformDate($registro['data_de_resposta']);
                        }
                        catch (Exception $e)
                        {
                            $data_de_resposta      = null;
                        }
                    }

                    $res = DB::table('cie_eletronicas')
                        ->where('emissao','=',  $emissao)
                        ->where('numero', '=',  $registro['numero'])
                        ->where('se_origem', '=',  $registro['se_origem'])
                        ->where('destino', '=',  $registro['destino'])
                        ->where('se_destino', '=',  $registro['se_destino'])
                        ->select(
                            'cie_eletronicas.id'
                        )
                    ->first();

                    if (empty($res->id))
                    {
                        $cie_eletronica = new CieEletronica;
                        $cie_eletronica->numero      = $registro['numero'];
                        $cie_eletronica->emissao = $emissao;
                        $cie_eletronica->se_origem  = $registro['se_origem'];
                        $cie_eletronica->destino  = $registro['destino'];
                        $cie_eletronica->se_destino  = $registro['se_destino'];
                        $cie_eletronica->irregularidade  = $registro['irregularidade'];
                        $cie_eletronica->categoria  = $registro['categoria'];
                        $cie_eletronica->numero_objeto  = $registro['numero_objeto'];
                        $cie_eletronica->lida  = $registro['lida'];
                        $cie_eletronica->respondida  = $registro['respondida'];
                        $cie_eletronica->fora_do_prazo  = $registro['fora_do_prazo'];
                        $cie_eletronica->resposta  = $registro['resposta'];
                        $cie_eletronica->data_de_resposta  = $data_de_resposta;

                    }
                    else{
                        // atualizar
                        $cie_eletronica = CieEletronica::find($res->id);
                        $cie_eletronica->irregularidade  = $registro['irregularidade'];
                        $cie_eletronica->categoria  = $registro['categoria'];
                        $cie_eletronica->numero_objeto  = $registro['numero_objeto'];
                        $cie_eletronica->lida  = $registro['lida'];
                        $cie_eletronica->respondida  = $registro['respondida'];
                        $cie_eletronica->fora_do_prazo  = $registro['fora_do_prazo'];
                        $cie_eletronica->resposta  = $registro['resposta'];
                        $cie_eletronica->data_de_resposta  = $data_de_resposta;

                    }
                    $cie_eletronica->save();
                    $row ++;
                }

            }
            DB::table('cie_eletronicas')
                ->where('created_at', '<=', $dtmenos365dias)
            ->delete();

            if ($row >= 1){
                \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
                    ,'class'=>'green white-text']);
            }else{
                \Session::flash('mensagem',['msg'=>'O Arquivo lido e encontrado todos registros existentes'
                    ,'class'=>'green white-text']);
            }
            return redirect()->route('importacao');
        }else{
            return back()->with(['errors'=>$validator->errors()->all()]);
        }
    }

        public function cieEletronica()
    {
        return view('compliance.importacoes.cieEletronica');  //
    }
        // ######################### FIM  cieEletronica #######################

        // ######################### INICIO SGDO DISTRIBUIÇÃO  #######################
    public function exportSgdoDistribuicao()
    {
        return Excel::download(new ExportSgdoDistribuicao, 'sgdoDistribuicao.xlsx');
    }

    public function importSgdoDistribuicao(Request $request)
    {
        $row = 0;
        $dtmenos180dias = Carbon::now();
        $dtmenos180dias->subDays(180);

        $validator = Validator::make($request->all(),[
        'file' => 'required|mimes:xlsx'
        ]);

        if($validator->passes())
        {
            $time='350';
            ini_set('max_input_time', $time);
            ini_set('max_execution_time', $time);
            $sgdoDistribuicao = Excel::toArray(new ImportSgdoDistribuicao,  request()->file('file'));
            foreach($sgdoDistribuicao as $dados)
            {
                foreach($dados as $registro)
                {
                    if(! $registro['data_inio_atividade']=='')
                    {
                        try
                        {
                            $data_incio_atividade = $this->transformDate($registro['data_inio_atividade']);
                        }
                        catch (Exception $e)
                        {
                            $data_incio_atividade       =  null;
                        }
                    } else $data_incio_atividade       = null;


                    if(! $registro['data_saa']=='')
                    {
                        try
                        {
                            $data_saida = $this->transformDate($registro['data_saa']);
                        }
                        catch (Exception $e)
                        {
                            $data_saida       = null;
                        }
                    }   else $data_saida       = null;

                    if(! $registro['data_retorno']=='')
                    {
                        try
                        {
                            $data_retorno = $this->transformDate($registro['data_retorno']);
                        }
                        catch (Exception $e)
                        {
                            $data_retorno       = null;
                        }
                    }     else $data_retorno       = null;

                    if(! $registro['data_tpc']=='')
                    {
                        try
                        {
                            $data_tpc = $this->transformDate($registro['data_tpc']);
                        }
                        catch (Exception $e)
                        {
                            // dd( $registro, $registro['data_tpc'],  $data_tpc);
                            $data_tpc       = null;
                        }
                    }
                    else $data_tpc       = null;

                    if(! $registro['data_tmino_atividade']=='')
                    {
                        try
                        {
                            $data_termino_atividade = $this->transformDate($registro['data_tmino_atividade']);
                        }
                        catch (Exception $e)
                        {
                            $data_termino_atividade       = null;
                        }
                    }  else $data_termino_atividade       = null;

                    if(! $data_incio_atividade == Null)
                    {
                        $reg = SgdoDistribuicao::firstOrCreate(
                            [
                                'mcu' =>  $registro['mcu']
                                , 'matricula' =>  $registro['matrula']
                                , 'data_incio_atividade' =>  $data_incio_atividade
                            ],[
                            'dr' => $registro['dr']
                            , 'unidade' =>  $registro['unidade']
                            , 'mcu' =>  $registro['mcu']
                            , 'centralizadora' =>  $registro['centralizadora']
                            , 'mcu_centralizadora' => $registro['mcu_centralizadora']
                            , 'distrito' =>  $registro['distrito']
                            , 'area' =>  $registro['rea']
                            , 'locomocao' =>  $registro['locomoo']
                            , 'funcionario' =>  $registro['funcionio']
                            , 'matricula' =>  $registro['matrula']
                            , 'data_incio_atividade' =>  $data_incio_atividade
                            , 'hora_incio_atividade' => $registro['hora_inio_atividade']
                            , 'data_saida' =>  $data_saida
                            , 'hora_saida' =>  $registro['hora_saa']
                            , 'data_retorno' =>  $data_retorno
                            , 'hora_retorno' =>   $registro['hora_retorno']
                            , 'data_tpc' =>  $data_tpc
                            , 'hora_do_tpc' =>  $registro['hora_do_tpc']
                            , 'data_termino_atividade' =>  $data_termino_atividade
                            , 'hora_termino_atividade' =>   $registro['hora_tmino_atividade']
                            , 'justificado' =>  $registro['justificado']
                            , 'peso_da_bolsa_kg' =>  $registro['peso_da_bolsa_kg']
                            , 'peso_do_da_kg' =>  $registro['peso_do_da_kg']
                            , 'quantidade_de_da' =>  $registro['quantidade_de_da']
                            , 'quantidade_de_gu' =>  $registro['quantidade_de_gu']
                            , 'quantidade_de_objetos_qualificados' =>  $registro['quantidade_de_objetos_qualificados']
                            , 'quantidade_de_objetos_coletados' =>  $registro['quantidade_de_objetos_coletados']
                            , 'quantidade_de_pontos_de_entregacoleta' =>  $registro['quantidade_de_pontos_de_entregacoleta']
                            , 'quilometragem_percorrida' =>  $registro['quilometragem_percorrida']
                            , 'residuo_simples' =>  $registro['resuo_simples']
                            , 'residuo_qualificado' =>  $registro['resuo_qualificado']
                            , 'almoca_na_unidade' =>  $registro['almo_na_unidade']
                            , 'compartilhado' =>  $registro['compartilhado']
                            , 'tipo_de_distrito' =>  $registro['tipo_de_distrito']
                        ]);
//dd(   $reg );
                        $row ++;
                    }
                }
            }
            $afected = DB::table('sgdo_distribuicao')
                ->where('data_incio_atividade', '<',   $dtmenos180dias)
                ->where('matricula', '=',   $registro['matrula'])
            ->delete();

            \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
            ,'class'=>'green white-text']);
            return redirect()->route('importacao');
        }else{
            return back()->with(['errors'=>$validator->errors()->all()]);
        }
    }
    public function sgdoDistribuicao()
    {
        return view('compliance.importacoes.sgdoDistribuicao');  //
    }
    // ######################### FIM SGDO DISTRIBUICAO #######################

    // ######################### INICIO Controle de Viagem  #######################
    public function exportControleDeViagem()
    {
        return Excel::download(new ExportControleDeViagem, 'ControleDeViagem.xlsx');
    }
    public function importControleDeViagem(Request $request)
    {
        $row = 0;
        $time='350';
        $dtmenos90dias = Carbon::now();
        $dtmenos90dias = $dtmenos90dias->subDays(90);

        $validator = Validator::make($request->all(),[
        'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        if(empty($request->file('file')))
        {
            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
            O Arquivo de ser 276-1-ControleDeViagem.xlsx ! Selecione Corretamente'
            ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }
//        if( $request->file('file')->getClientOriginalName() != "276-1-ControleDeViagem.xlsx") {
//            \Session::flash('mensagem',['msg'=>'Erro na Seleção do Arquivo.
//            O Arquivo de ser 276-1-ControleDeViagem.xls! Selecione Corretamente'
//            ,'class'=>'red white-text']);
//            return redirect()->route('importacao');
//        }

        if($validator->passes()) {
           // DB::table('controle_de_viagens')->truncate(); //excluir e zerar a tabela
            $controle_de_viagens = Excel::toArray(new ImportControleDeViagem,  request()->file('file'));
            foreach($controle_de_viagens as $dados)
            {
                foreach($dados as $registro)
                {
                    $inicio_viagem = null;
                    if(!empty($registro['inicio_viagem']))
                    {
                        try
                        {
                            $inicio_viagem = $this->transformDate($registro['inicio_viagem']);
                        }
                        catch (Exception $e)
                        {
                            $inicio_viagem       = null;
                        }
                    }
                    $data_chegada_prevista     = null;
                    if(!empty($registro['data_chegada_prevista'])) {
                        try {
                            $data_chegada_prevista = $this->transformDate($registro['data_chegada_prevista']);
                        } catch (Exception $e) {
                            $data_chegada_prevista     = null;
                        }
                    }
                    $data_partida_prevista     = null;
                    if(!empty($registro['data_partida_prevista'])) {
                        try {
                            $data_partida_prevista = $this->transformDate($registro['data_partida_prevista']);
                        } catch (Exception $e) {
                            $data_partida_prevista     = null;
                        }
                    }

                    ControleDeViagem::firstOrCreate(
                        [
                            'controle_viagem' =>  $registro['controle_viagem']
                        ]
                        ,[
                            'dr_detentora' => $registro['dr_detentora']
                        , 'unidade_detentora' =>  $registro['unidade_detentora']
                        , 'origem_destino' =>  $registro['origem_destino']
                        , 'tipo_linha' =>  $registro['tipo_linha']
                        , 'dr_detentora' =>  $registro['dr_detentora']
                        , 'numero_da_linha' =>  $registro['numero_da_linha']
                        , 'controle_viagem' =>  $registro['controle_viagem']
                        , 'numero_ficha_tec' =>  $registro['numero_ficha_tec']
                        , 'sentido' =>  $registro['sentido']
                        , 'status' =>  $registro['status']
                        , 'sequencia_do_cv' =>  $registro['sequencia_do_cv']
                        , 'ponto_parada' =>  $registro['ponto_parada']
                        , 'descricao_ponto_parada' =>  $registro['descricao_ponto_parada']
                        , 'drac_ponto_de_parada' =>  $registro['drac_ponto_de_parada']
                        , 'tipo_de_operacao' =>  $registro['tipo_de_operacao']
                        , 'quantidade' =>  $registro['quantidade']
                        , 'peso' =>  $registro['peso']
                        , 'unitizador' =>  $registro['unitizador']
                        , 'tipo_de_servico' =>  $registro['tipo_de_servico']
                        , 'descricao_do_servico' =>  $registro['descricao_do_servico']
                        , 'codigo_de_destino' =>  $registro['codigo_de_destino']
                        , 'local_de_destino' =>  $registro['local_de_destino']
                        , 'inicio_viagem' =>  $inicio_viagem
                        , 'data_chegada_prevista' =>  $data_chegada_prevista
                        , 'data_partida_prevista' =>  $data_partida_prevista
                        , 'horario_chegada_prevista' =>  $registro['horario_chegada_prevista']
                        , 'horario_partida_prevista' =>  $registro['horario_partida_prevista']
                    ]);
                    $row ++;
                }
            }
            $afected = DB::table('controle_de_viagens')
                ->where('inicio_viagem', '<',   $dtmenos90dias)
                ->delete();

            \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
            ,'class'=>'green white-text']);
            return redirect()->route('importacao');
        }
        else
        {
            return back()->with(['errors'=>$validator->errors()->all()]);
        }
    }
    public function controleDeViagem()
    {
        return view('compliance.importacoes.controleDeViagem');  //
    }
    // ######################### FIM Controle de Viagem #######################

    // ######################### INICIO plp pendente  #######################
    public function exportPLPListaPendente()
    {
        return Excel::download(new ExportPLPListaPendente, 'PLPListaPendente.xlsx');
    }
    public function importPLPListaPendente(Request $request)
    {
        $row = 0;
        $time='350';
        $datahoje = Carbon::now();

        $validator = Validator::make($request->all(),[
        'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        if(empty($request->file('file')))
        {
            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
            O Arquivo de ser 274-1-PLP-ListasPendentes.xlsx ! Selecione Corretamente'
            ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }
//            if( $request->file('file')->getClientOriginalName() != "274-1-PLP-ListasPendentes.xlsx")
//            {
//                \Session::flash('mensagem',['msg'=>'Erro na Seleção do Arquivo.
//                O Arquivo de ser  274-1-PLP-ListasPendentes.xls! Selecione Corretamente'
//                ,'class'=>'red white-text']);
//                return redirect()->route('importacao');
//            }

        if($validator->passes())
        {
            $plpListaPendentes = Excel::toArray(new ImportPLPListaPendente,  request()->file('file'));
          ///  DB::table('plpListaPendentes')->truncate(); //excluir e zerar a tabela
            foreach($plpListaPendentes as $dados)
            {
                foreach($dados as $registro)
                {
                    if(!empty($registro['dh_lista_postagem']))
                    {
                        try
                        {
                            $dh_lista_postagem = $this->transformDate($registro['dh_lista_postagem']);
                        }
                        catch (Exception $e)
                        {
                            $dh_lista_postagem       = "";
                        }
                    }
                    $plpListaPendente = new PLPListaPendente;
                    $plpListaPendente->dr      = $registro['dr'];
                    $plpListaPendente->stomcu      = $registro['stomcu'];
                    $plpListaPendente->nome_agencia      = $registro['nome_agencia'];
                    $plpListaPendente->lista      = $registro['lista'];
                    $plpListaPendente->plp      = $registro['plp'];
                    $plpListaPendente->objeto      = $registro['objeto'];
                    $plpListaPendente->cliente      = $registro['cliente'];
                    $plpListaPendente->dh_lista_postagem      =  $dh_lista_postagem;
                    $plpListaPendente->save();
                    $row ++;
                    $afected = DB::table('plplistapendentes')
                        ->where('dr', '=',   $plpListaPendente->dr)
                        ->where('created_at', '<',   $datahoje)
                    ->delete();
                }
            }
            \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
            ,'class'=>'green white-text']);
            return redirect()->route('importacao');
        }else{
            return back()->with(['errors'=>$validator->errors()->all()]);
        }
    }
    public function plpListaPendente()
    {
        return view('compliance.importacoes.plpListaPendente');  //
    }
    // ######################### FIM plp pendentes #######################

    // ######################### INICIO CFTV  #######################
    public function exportCftv()
    {
        return Excel::download(new ExportCftv, 'cftvs.xlsx');
    }
    public function importCftv(Request $request)
    {
        $row = 0;
        $validator = Validator::make($request->all(),[
           'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        if(empty($request->file('file'))){
            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
            O Arquivo de ser 272-4-SEGURANÇA-Monitoramento-CFTV.xlsx ! Selecione Corretamente'
            ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }

    //    if( $request->file('file')->getClientOriginalName() != "272-4-SEGURANÇA-Monitoramento-CFTV.xlsx") {
    //
    //        \Session::flash('mensagem',['msg'=>'Erro na Seleção do Arquivo.
    //        O Arquivo de ser  272-4-SEGURANÇA-Monitoramento-CFTV.xls! Selecione Corretamente'
    //        ,'class'=>'red white-text']);
    //        return redirect()->route('importacao');
    //    }

        if($validator->passes())
        {
            $cftvs = Excel::toArray(new ImportCftv,  request()->file('file'));
          //  DB::table('cftvs')->truncate(); //excluir e zerar a tabela
            foreach($cftvs as $dados)
            {
                foreach($dados as $registro) {
                    if(!empty($registro['data_ultima_conexao']))
                    {
                        try
                        {
                            $data_ultima_conexao = $this->transformDate($registro['data_ultima_conexao']);
                        }
                            catch (Exception $e) {
                             $data_ultima_conexao       = "";
                        }
                    }
                    $cftv = Cftv::firstOrNew(
                        [
                            'mcu' => $registro['mcu']
                            ,'end_ip' => $registro['end_ip']
                        ],
                        [
                            'cameras_fixa_cf' => $registro['cameras_fixa_cf']
                            ,'mcu' => $registro['mcu']
                            ,'unidade' => $registro['unidade']
                            , 'cameras_infra_vermelho_cir' => $registro['cameras_infra_vermelho_cir']
                            , 'dome' => $registro['dome']
                            , 'modulo_dvr' => $registro['modulo_dvr']
                            , 'no_break' => $registro['no_break']
                            , 'hack' => $registro['hack']
                            , 'pc_auxiliar' => $registro['pc_auxiliar']
                            , 'portaweb' => $registro['portaweb']
                            , 'end_ip' => $registro['end_ip']
                            , 'link' => $registro['link']
                            , 'user' => $registro['user']
                            , 'password' => $registro['password']
                            , 'port' => $registro['port']
                            , 'marcamodelo' => $registro['marcamodelo']
                            , 'statusconexao' => $registro['statusconexao']
                            , 'observacao' => $registro['observacao']
                            , 'data_ultima_conexao' => $data_ultima_conexao
                        ]);
                    $cftv ->save();
                    $row ++;
                }
            }
            \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
            ,'class'=>'green white-text']);
            return redirect()->route('importacao');
        }else{
            return back()->with(['errors'=>$validator->errors()->all()]);
        }
    }
    public function cftv()
    {
        return view('compliance.importacoes.cftv');  //
    }
    // ######################### FIM CFTV #######################

    // ######################### INICIO FERIAS POR MCU #######################
    public function exportFerias()
    {
        return Excel::download(new ExportFerias, 'ferias.xlsx');
    }
    public function importFerias(Request $request)
    {
        $row = 0;
        $time='350';
        $datahoje = Carbon::now();

        $validator = Validator::make($request->all(),[
           'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        if(empty($request->file('file')))
        {
        \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
            O Arquivo de ser 272-3-WebSGQ3 - Fruicao de ferias por MCU.xlsx ! Selecione Corretamente'
            ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }
        if($validator->passes())
        {
        $temp = Excel::toArray(new ImportFeriasPorMcu,  request()->file('file'));
        $ferias = Excel::toArray(new ImportFeriasPorMcu,  request()->file('file'));
            ini_set('max_input_time', $time);
            ini_set('max_execution_time', $time);
            foreach($ferias as $dados)
            {
                foreach($dados as $registro)
                {
                    if(!empty($registro['inicio_fruicao']))
                    {
                        try
                        {
                            $dtini =  substr($registro['inicio_fruicao'],6,4)
                                .'-'. substr($registro['inicio_fruicao'],3,2)
                                .'-'. substr($registro['inicio_fruicao'],0,2);
                        }
                        catch (Exception $e)
                        {
                            $dtini ="";
                        }
                    }
                    if(!empty($registro['termino_fruicao']))
                    {
                        try
                        {
                            $dtfim =  substr($registro['termino_fruicao'],6,4)
                                .'-'. substr($registro['termino_fruicao'],3,2)
                                .'-'. substr($registro['termino_fruicao'],0,2);
                        } catch (Exception $e)
                        {
                            $dtfim ="";
                        }
                    }
                    $reg = new FeriasPorMcu;
                        $reg->matricula      = $registro['matricula'];
                        $reg->nome      = $registro['nome'];
                        $reg->lotacao      = $registro['lotacao'];
                        $reg->funcao      = $registro['funcao'];
                        $reg->inicio_fruicao      =  $dtini;
                        $reg->termino_fruicao      =  $dtfim;
                        $reg->dias      = $registro['dias'];
                    $reg->save();
                    $row ++;
                    $afected = DB::table('ferias_por_mcu')
                        ->where('matricula', '=',   $reg->matricula)
                        ->where('created_at', '<',   $datahoje)
                    ->delete();
                }
            }
            \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
            ,'class'=>'green white-text']);
            return redirect()->route('importacao');
        }else{
            return back()->with(['errors'=>$validator->errors()->all()]);
        }
    }
    public function ferias()
    {
        return view('compliance.importacoes.ferias');  //
    }
    // ######################### FIM FERIAS POR MCU #######################

    /// ######################### BLOCO  RespDefinida  #######################
    public function exportRespDefinida() {
        return Excel::download(new ExportRespDefinida, 'RespDefinida.xlsx');
    }
    public function importRespDefinida(Request $request) {
        $row = 0;

        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        if($request->file('file') == "") {
            \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi Selecionado
            O Arquivo de ser  271-1-SEGURANÇA-POSTAL-PendênciasApuracaoRespDefinida.xls! Selecione Corretamente'
            ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }

        // dd($request->file('file'));
//
//        if( $request->file('file')->getClientOriginalName() != "271-1-SEGURANÇA-POSTAL-PendênciasApuracaoRespDefinida.xlsx") {
//            \Session::flash('mensagem',['msg'=>'Erro na Seleção do Arquivo.
//            O Arquivo de ser  271-1-SEGURANÇA-POSTAL-PendênciasApuracaoRespDefinida.xls! Selecione Corretamente'
//            ,'class'=>'red white-text']);
//            return redirect()->route('importacao');
//        }



        if($validator->passes()) {
         //   DB::table('resp_definidas')->truncate(); //excluir e zerar a tabela
            $RespDefinidas = Excel::toArray(new ImportRespDefinida,  request()->file('file'));
            foreach($RespDefinidas as $registros)
            {
                foreach($registros as $dado)
                {
                    $res = DB::table('resp_definidas')
                        ->where('objeto', '=',  $dado['objeto'])
                         ->select(
                            'resp_definidas.id'
                        )
                    ->first();
                    if(!empty(  $res->id ))
                    {
                        $registro = RespDefinida::find($res->id);
                    }
                    else
                    {
                        $registro = new RespDefinida;
                    }
                    $registro->unidade      = $dado['unidade'];
                    if(!empty($dado['data_pagamento'])) {
                        try {
                            $dt = $this->transformDate(strtr($dado['data_pagamento'], '/', '-'));
                            $registro->data_pagamento = $dt;
                        } catch (Exception $e) {
                            dd("erro conversão de data");
                        }
                    }
                    $registro->objeto      = $dado['objeto'];
                    if(!empty($dado['datapostagem'])) {
                        try {
                            $dt = $this->transformDate(strtr($dado['datapostagem'], '/', '-'));
                            $registro->datapostagem         = $dt;
                        }  catch (Exception $e) {
                            $registro->datapostagem         = null;
                        }
                    }
                    $registro->servico_produto      = $dado['servico_produto'];
                    if($dado['valor_da_indenizacao'] != 0) {
                        $registro->valor_da_indenizacao = str_replace(",", ".", $dado['valor_da_indenizacao']);
                    }
                    $registro->sto      = $dado['sto'];
                    $registro->mcu      = $dado['mcu'];
                    $registro->subordinacao      = $dado['subordinacao'];
                    $registro->nu_pedidoinformacao      = $dado['nu_pedidoinformacao'];
                    $registro->se_pagadora      = $dado['se_pagadora'];
                    if(!empty($dado['data'])) {
                        try {
                            $dt = $this->transformDate(strtr($dado['data'], '/', '-'));
                            $registro->data         = $dt;
                        } catch (Exception $e) {
                            $registro->data         = null;
                        }
                    }
                    $registro->nu_sei      = $dado['nu_sei'];
                    $registro->nu_sei_abertounidade      = $dado['nu_sei_abertounidade'];
                    $registro->situacao      = $dado['situacao'];
                    $registro->empregadoresponsavel      = $dado['empregadoresponsavel'];
                    $registro->observacoes      = $dado['observacoes'];
                    $registro->conclusao      = $dado['conclusao'];
                    $registro->providenciaadotada      = $dado['providenciaadotada'];
                    $registro->save();
                    $row ++;
                }
            }

            \Session::flash('mensagem',['msg'=>'O Arquivo subiu com '.$row.' linhas Corretamente'
            ,'class'=>'green white-text']);
            return redirect()->route('importacao');
        }else {
            \Session::flash('mensagem',['msg'=>'Registros Responsabilidade Definida  Não pôde ser importado! Tente novamente'
            ,'class'=>'red white-text']);
            return redirect()->route('importacao');
        }

    }
    public function RespDefinida()
    {
        return view('compliance.importacoes.respDefinida');  //
    }
    /// ######################### FIM RespDefinida #######################

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
    public function transformTime($value, $format = 'H:i:s')
    {
        try
        {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        }
        catch (\ErrorException $e)
        {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
    public function transformDateTime($value, $format = 'Y-m-d H:i:s')
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
    public function transformHoraMin($value, $format = 'H:i')
    {
        try
        {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        }
        catch (\ErrorException $e)
        {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
    public function transformDayOfWeek($value, $format = 'd')
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
    public function transformDateMesDia($value, $format = 'm-d')
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
    public function index()
    {
        return view('compliance.importacoes.index');  //
    }
}
