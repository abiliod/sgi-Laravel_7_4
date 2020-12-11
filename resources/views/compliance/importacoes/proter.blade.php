@extends('layouts._gynPromo.app')
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
                    <p>Grupo de Verificação 270, Função Prevenção de Perdas   </p>
                    <p>Assunto: Proteção de Receitas. PROTER | 270-2-FINANCEIRO-Proter_ProtecaoReceita.xlsx</p>
                    <p><b>Modo Truncate</b> Talvez necessita ajuste para aderencia Nacional</p>
                    <thead>
                        <tr>
                            <th>Abrir o Arquivo excluir a primeira linha | </th>
                            <th>Salvar a Planilha como Pasta de Trabalho Excel  formato XLSX| </th>
                            <th>O arquivo original traz informações da coluna MCU e Nome da unidade em colunas divergentes | </th>
                            <th>Foi ajustado durante o processo de importação por gentileza verificar as colunas e certificar das informações conforme planilha modelo na pasta.</th>
                        </tr>
                    </thead>
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
