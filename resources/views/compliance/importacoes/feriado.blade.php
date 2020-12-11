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
                    <a class="breadcrumb">Importação Feriado - Feriados Por Municipio</a>
                </div>
            </div>
        </nav>
    </div>
    <div class="row">
        <div class="col s12 m12">
            <div class="card blue darken-1">
                <div class="card-content white-text">
                    <span class="card-title">Sistema de Feriados</span>
                    <p>Feriados: Assunto:  Auxiliar do Sistema.<br>Mimes: xlsx</p>
                    <tr>
                        <p>Relatório Feriado ERP | Feriado.xlsx</p>
                        <p><b>Modo Truncate</b> necessita ajuste para aderencia Nacional</p>
                    </tr>
                    <form action="{{ route('compliance.importacao.feriado') }}" method="POST" name="importform"
                            enctype="multipart/form-data">
                        {{csrf_field()}}
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

                            <a class="btn waves-effect waves-teal orange disabled" href="{{ url('/compliance/importacoes/feriado/export') }}">Export File
                            <i class="material-icons right">file_download</i></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
