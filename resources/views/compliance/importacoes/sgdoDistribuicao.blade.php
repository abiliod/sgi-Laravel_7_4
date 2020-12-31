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
                    <a class="breadcrumb">Importação Sistema: Distribuição Domiciliária</a>
                </div>
            </div>
        </nav>
    </div>
    <div class="row">
        <div class="col s12 m12">
             <div class="card green darken-1">
                <div class="card-content white-text">
                  <div class="card green darken-1">

                  <span class="card-title">Sistema: Distribuição Domiciliária <br>SGDO</span>
                      <p>Grupo de Verificação 277</p>
                      <p>Assunto: Lançamentos Obrigatórios SGDO</p>
                      <p>Verificação do Lançamentos</p>
                      <p>
                          <input id ="exibe" type="checkbox" class="filled-in col s1" onclick="Mudarestado('ajuda')"/>
                          <label for="exibe">
                              <span class="card-content orange-text">Exibir Ajuda do Item?</span>
                          </label>
                      </p>
                      <div class="input-field"  id ="ajuda" style="display:none;">
                          <i class="material-icons prefix">mode_edit</i>
                          <textarea  id="ajuda" name="ajuda" class="materialize-textarea">

                                Sistema: http://......
                                Pesquisar  Superintendencia, importar para uma planilha formato excel.xlsx
                                VEJA o lay-out:
                                [DR	Unidade	MCU	Centralizadora	MCU Centralizadora	Distrito	Area	Locomocao	Funcionario	Matricula	Data Inicio Atividade	Hora Inicio Atividade	Data Saida	Hora Saida	Data Retorno	Hora Retorno	Data TPC	Hora do TPC	Data Termino Atividade	Hora Termino Atividade	Justificado	Peso da Bolsa (Kg)	Peso do DA (Kg)	Quantidade de DA	Quantidade de GU	Quantidade de Objetos Qualificados	Quantidade de Objetos Coletados	Quantidade de Pontos de Entrega/Coleta	Quilometragem Percorrida	Residuo Simples	Residuo Qualificado	Almoco na Unidade	Compartilhado	Tipo de distrito]
                                Caso o processo seja interrompido por timeout fraguimente o  arquivo.

                                O sistema excluirá os lançamentos antigos e faráa persistêcia dos novos registros.
                                Frequencia: diária.
                            </textarea>
                      </div>
                    <form action="{{ route('compliance.importacao.sgdoDistribuicao') }}" method="POST" name="importform"
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
                            <a class="btn waves-effect waves-teal orange" href="{{ url('/compliance/importacoes/sgdoDistribuicao/export') }}">Export File
                            <i class="material-icons right">file_download</i></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
