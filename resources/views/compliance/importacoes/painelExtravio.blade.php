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
                    <a class="breadcrumb">Importação Sistema: Pré Alerta</a>
                </div>
            </div>
        </nav>
    </div>
    <div class="row">
        <div class="col s12 m12">
             <div class="card #b71c1c red darken-4">
                <div class="card-content white-text">
                  <div class="card #b71c1c red darken-4">

                  <span class="card-title">Sistema: Distribuição Domiciliária <br>Pré Alerta</span>
                        <p>Grupo/Item: 277.5, Função: Gestão SRO
                        <br>Conferência Eletrônica.
                        <br>Arquivo: 277-5-PainelExtravio</p>
                    <thead>
                        <tr>
                            <th>LayOut do Arquivo: Unidade | </th>
                        </tr>
                    </thead>
                    <form action="{{ route('compliance.importacao.painelExtravio') }}" method="POST" name="importform"
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
                            <a class="btn waves-effect waves-teal orange" href="{{ url('/compliance/importacoes/painelExtravio/export') }}">Export File
                            <i class="material-icons right">file_download</i></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
