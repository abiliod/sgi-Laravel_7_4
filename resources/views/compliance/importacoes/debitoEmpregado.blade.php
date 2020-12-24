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
                    <a class="breadcrumb">Importação WebCont - Debito de Empregado</a>
                </div>
            </div>
        </nav>
    </div>
    <div class="row">
        <div class="col s12 m12">
            <div class="card #42a5f5 orange lighten-1">
                <div class="card-content white-text">
                    <p>Grupo de Verificação 270, Função Prevenção de Perdas.</p>
                        <p>Função: Prevenção de Perdas.</p>
                        <p>Assunto: Débito de Empregado: Conta  11202.994000</p>
                        <p>Item: 270-1-FINANCEIRO-WebCont_DebitoEmpregado.xlsx<br/></p>
                    <p>
                        <input id ="exibe" type="checkbox" class="filled-in col s1" onclick="Mudarestado('ajuda')"/>
                        <label for="exibe">
                            <span class="card-content orange-text">Exibir Ajuda do Item?</span>
                        </label>
                    </p>
                    <div class="input-field"  id ="ajuda" style="display:none;">
                        <i class="material-icons prefix">mode_edit</i>
                        <textarea  id="ajuda" name="ajuda" class="materialize-textarea">
                                Endereço:  http://snu0074/webcont/menu_conciliacoes/
                                As informações são obtidas por meio: Menu Consultas > Conciliações.
                                Selecionar a competência mais recente: Selecionar a CIA (Superintendência).
                                Informar a conta 11202.994000. Se não hover dados informe outra competéncia anterior até obter registros.
                                Gerar o arquivo no formato CSV, em seguida e importar para Excel.xlsx
                                lay-out: [Cia	Conta	Competência	Data	Lote	Tp	MCU (Doc1)	Nome Agência (Doc2)	Histórico	Valor	Observações	Documento (Ref1)	Matrícula (Ref2)	Nome Empregado (Ref3)	Ação	Justificativa (Ad1)	Ad2	Ad3	Ad4	Ad5	Ad6	Ad7	Regularização	Anexo]
                                Sugestão de nome do Arquivo: 270-1-WebCont_DebitoEmpregado.xlsx .
                                Frequencia: Mensal, sempre que for disponibilizado nova prévia pelo departamento.
                                Se o sistema der erro TIME-OUT, divida a planilha para que a mesma tenha uma quantidade menor de registros.
                        </textarea>
                    </div>

                    <form action="{{ route('compliance.importacao.webcont') }}" method="POST" name="importform"
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
                                <a class="btn waves-effect waves-teal blue disabled" href="{{ url('/compliance/importacoes/debitoEmpregado/export') }}">Export File
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
