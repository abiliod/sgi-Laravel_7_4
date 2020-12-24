@extends('layouts._sgiweb.app')
@section('content')
<div class="container">
    <h2 class="center">Importação</h2>
    <div class="row">
        <nav>
            <div class="nav-wrapper green">
                <div class="col s12">
                    <a href="{{ route('home')}}" class="breadcrumb">Início</a>
                    <a href="{{ route('importacao')}}" class="breadcrumb">Importações</a>
                    <a class="breadcrumb">Importação Proter - Proteção de Receita</a>
                </div>
            </div>
        </nav>
    </div>
    <div class="row">
        <div class="col s12 m12">
            <div class="card #0d47a1 blue darken-4">
                <div class="card-content white-text">
                    <span class="card-title">Sistema Proter</span>
                    <p>Função Prevenção de Perdas.</p>
                    <p>Assunto: Proteção de Receitas. PROTER</p>
                    <p>Item: 270-2 - FINANCEIRO </p>
                    <p>
                        <input id ="exibe" type="checkbox" class="filled-in col s1" onclick="Mudarestado('ajuda')"/>
                        <label for="exibe">
                            <span class="card-content orange-text">Exibir Ajuda do Item?</span>
                        </label>
                    </p>
                    <div class="input-field"  id ="ajuda" style="display:none;">
                        <i class="material-icons prefix">mode_edit</i>
                        <textarea  id="ajuda" name="ajuda" class="materialize-textarea">
                                Endereço:  http://app.correiosnet.int/SPNWEB/pages/inicio.jsf
                                As informações são obtidas por meio: Sistema SPN->Itens Monitorados->Objeto Postal->Relatório Pendências Geral.
                                As pendências apresentadas no PROTER referem-se ao período de Janeiro/2017 em diante.
                                Efetuar as pesquisas a partir desta data. Ao gerar o arquivo (SELECIONE TODAS SEs).
                                Fazer o download no formato (CSV). Importar os dados para uma Planilha formato xlsx.  Abrir o Arquivo excluir a primeira linha.
                                Manteha sempre esse lay-out observe as palavras quando da importação: [TIPO DE PENDÊNCIA	STATUS DA PENDÊNCIA	DATA DA PENDÊNCIA	DIVERGÊNCIA PESO	DIVERGÊNCIA CEP	ORIGEM PENDÊNCIA	SE	TIPO DE UNIDADE	STO/MCU	NOME DA UNIDADE	STATUS DA UNIDADE	TIPO DE ATENDIMENTO	MATRÍCULA ATENDENTE	Nº DO OBJETO	DATA DA POSTAGEM	DATA DA ENTREGA	CÓDIGO DO SERVIÇO	CEP CONTABILIZADO (SARA)	CEP ENTREGA SRO	PESO TARIFADO FINANCEIRO	COMPRIMENTO FINANCEIRO	LARGURA FINANCEIRO	ALTURA FINANCEIRO	PESO CÚBICO FINANCEIRO	PESO REAL MECTRI	COMPRIMENTO MECTRI	LARGURA MECTRI	ALTURA MECTRI	PESO CÚBICO MECTRI	PESO TARIFADO MECTRI	VALOR TARIFADO FINANCEIRO	VALOR TARIFADO MECTRI	DIFERENÇA A RECOLHER	CNPJ DO CLIENTE	CONTRATO	CARTÃO POSTAGEM	NOME DO CLIENTE	QTD DUPLICIDADES	ÚLTIMA MANIFESTAÇÃO	[MCU Triagem	Centro	Peso	Volume	Altura	Largura	Comprimento	Data de leitura	Tipo do objeto	Cep destino	Tipo de indução	Número da máquina	Código da estação]
                                Exclua os registros que na coluna TIPO DE PENDÊNICIA for igual ORD e SRO, salve a planilha e execute a importação.
                                Sugestão de nome do Arquivo: 270-2-FINANCEIRO-Proter_ProtecaoReceita.xlsx.
                                Frequencia: Semanal. (obs: Há possibilidade de uma pendência em anos anteriores ter sido regularizada recentemente.)
                                Se o sistema der erro TIME-OUT, Informe esse erro ao Administrador do sistema.

                        </textarea>
                    </div>


                    <form action="{{ route('compliance.importacao.proter') }}" method="POST" name="importform"
                            enctype="multipart/form-data">
                        {{csrf_field()}}

                        <div class="row">

                        </div>

                        <div class="row">
                            <div class="file-field input-field">
                                <div class="btn">
                                <span>File</span>
                                <input type="file" name="file">
                                </div>
                                <div class="file-path-wrapper">
                                <input class="file-path validate" type="text">
                                </div>
                            </div>
                            <div class="card-action">
                                <button class="btn waves-effect waves-teal"
                                    type="submit" name="action">Import File
                                    <i class="material-icons right">file_upload</i>
                                </button>
                                <a class="btn waves-effect waves-teal blue disabled" href="{{ url('/compliance/importacoes/proter/export') }}">Export File
                                <i class="material-icons right">file_download</i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
