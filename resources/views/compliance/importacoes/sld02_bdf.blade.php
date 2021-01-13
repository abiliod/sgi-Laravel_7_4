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
                    <a class="breadcrumb">Importação SLD-02 - Saldo que Passa</a>
                </div>
            </div>
        </nav>
    </div>
    <div class="row">
        <div class="col s12 m12">
            <div class="card #b71c1c red darken-4">
                <div class="card-content white-text">

                 <span class="card-title">Gestão de Numerário</span>
                    <p>Grupo de Verificação 270, Função Prevenção de Perdas.</p>
                    <p>Assunto: Saldo que passa</p>
                    <p>Item: 270-4 - FINANCEIRO</p>
                    <p>
                        <input id ="exibe" type="checkbox" class="filled-in col s1" onclick="Mudarestado('ajuda')"/>
                        <label for="exibe">
                            <span class="card-content orange-text">Exibir Ajuda do Item?</span>
                        </label>
                    </p>
                    <div class="input-field"  id ="ajuda" style="display:none;">
                        <i class="material-icons prefix">mode_edit</i>
                        <textarea  id="ajuda" name="ajuda" class="materialize-textarea">
Acessar o sistema BDF > Clicar em "Conectar DR", selecionar a SE e clicar em "Conectar" > Clicar no menu "Saldo" > Selecionar "BDF-SLD-002 Saldo de Numerário em relação ao Limite de Saldo" >
Na tela, selecionar "Tipos de órgãos que serão inspecionados" clicando no ícone "..." > Selecione  - "9 Agência de Correio" > Informe o período "4 meses"> Clica em "Analítico" > "Tipo Limite" - Correios > AMBOS Clica em "Importar" nomeie o arquivo a ser gerado EX: SL02-bdf.txt.
Aguarde a importação em formato txt
Importar os dados para uma planilha formato xlsx.
VEJA o lay-out:
[DR	COD_ORGAO	REOP	ORGAO	DT_MOVIMENTO	SALDO_ATUAL	 limitevlr_limite_banco_postal_e_ect]
Tipo de importação: Por incremento.
O Sistema ao importar a planilha grava os registros não existentes
Em seguida irá apagará os registros existentes na tabela com data de movimento maior que 120 dias.

                        </textarea>
                    </div>

                    <form action="{{ route('compliance.importacao.SL02_bdf') }}" method="POST" name="importform"
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
                                <a class="btn waves-effect waves-teal blue disabled" href="{{ url('/compliance/importacoes/SL02_bdf/export') }}">Export File
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
