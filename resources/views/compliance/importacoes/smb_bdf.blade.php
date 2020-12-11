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
                    <a class="breadcrumb">Importação SMB X BDF - Proteção de Receita</a>
                </div>
            </div>
        </nav>
    </div>
    <div class="row">
        <div class="col s12 m12">
            <div class="card #424242 grey darken-3">
                <div class="card-content white-text">


                    <span class="card-title">Sistema de Depósito Bancário</span>
            <P>Grupo de Verificação 270, Função Prevenção de Perdas <br></p>
                    <tr>
                        <p>Assunto: Integridade de Depósitos Bancários. SMB - BDF | 270-3-FINANCEIRO-SMB_ BDF_DepositosNaoConciliados.xlsx</p>
                        <p><b>Modo Icremento</b></p>
                    </tr>
                    <form action="{{ route('compliance.importacao.smb_bdf') }}" method="POST" name="importform"
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
                                <a class="btn waves-effect waves-teal blue disabled" href="{{ url('/compliance/importacoes/smb_bdf/export') }}">Export File
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
