@extends('layouts._sgiweb.app')

@section('content')
<div class="container">
<h2 class="center">Importações Disponíveis</h2>

<div class="row">
     <nav>
        <div class="nav-wrapper green">
              <div class="col s12">
                <a href="{{ route('home')}}" class="breadcrumb">Início</a>
                <a class="breadcrumb">Compliance Importações</a>
              </div>
        </div>
      </nav>
</div>

<div class="row">
        @can('importar_unidades')
            <div class="col s12 m6">
                <div class="card green darken-1">
                    <div class="card-content white-text">
                        <span class="card-title">Unidades</span>
                        <p>Unidades<br>Assunto: Cadastro de Unidades.</p>
                        <p>Relatório ERP | R55001A.xlsx</p>
                        <p>Manter Cadastro de Unidades</p>
                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.unidades')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_cadastral')
            <div class="col s12 m6">
                <div class="card #004d40 teal darken-4">
                    <div class="card-content white-text">
                        <span class="card-title">Cadastral</span>
                        <p>Cadastro do Efetivo<br>Assunto: Auxiliar do Sistema.</p>
                        <p>WebSGQ 3 - Efetivo analitico por MCU | WebSGQ 3 - Efetivo analitico por MCU-SE.xlsx</p>
                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.cadastral')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_feriados')
            <div class="col s12 m6">
                <div class="card blue darken-1">
                    <div class="card-content white-text">
                        <span class="card-title">Feriados</span>
                        <p><br>Relatório Feriado ERP | Feriado.xlsx</p> <br>
                         <p>Manter cadastro de feriados.</p>

                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.feriado')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_webCont')
            <div class="col s12 m6">
                <div class="card   orange darken-1">
                    <div class="card-content white-text">
                        <span class="card-title">Sistema WebCont</span>
                        <p>Função: Prevenção de Perdas.</p>
                        <p>Assunto: Débito de Empregado: Conta  11202.994000</p>
                        <p>Item: 270-1-FINANCEIRO-WebCont_DebitoEmpregado.xlsx<br/></p>
                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.webcont')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_proter')
            <div class="col s12 m6">
                <div class="card #0d47a1 blue darken-4">
                    <div class="card-content white-text">
                        <span class="card-title">Sistema Proter</span>
                        <p>Função Prevenção de Perdas.</p>
                        <p>Assunto: Proteção de Receitas. PROTER</p>
                        <p>Item: 270-2 - FINANCEIRO </p>
                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.proter')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_smbBdf')
            <div class="col s12 m6">
                <div class="card #424242 grey darken-3">
                    <div class="card-content white-text">
                        <span class="card-title">Sistema de Depósito Bancário</span>
                        <p>Função Prevenção de Perdas.</p>
                        <p>Assunto:  Integridade de Depósitos Bancários. SMB - BDF</p>
                        <p>Item: 270-3 - FINANCEIRO</p>
                        <p></p>
                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.smb_bdf')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_sld02Bdf')
            <div class="col s12 m6">
                <div class="card #b71c1c red darken-4">
                    <div class="card-content white-text">
                        <span class="card-title">Gestão de Numerário</span>
                        <p>Grupo de Verificação 270, Função Prevenção de Perdas.</p>
                        <b/>
                        <tr>
                            <p>Assunto: Saldo que passa | 270-4-FINANCEIRO-SLD02_BDF_LimiteEncaixe.xlsx</p>
                            <p><b>Modo Icremento</b></p>
                        </tr>
                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.SL02_bdf')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_respDefinida')
            <div class="col s12 m6">
                <div class="card #dd2c00 deep-orange accent-4">
                    <div class="card-content white-text">
                        <span class="card-title">Segurança Postal</span>
                        <p>Grupo de Verificação 271, Função: Processos Administrativos <br><br>Assunto: Responsabilidade Definida.</p>
                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.RespDefinida')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_alarme')
            <div class="col s12 m6">
                <div class="card blue darken-1">
                    <div class="card-content white-text">
                        <span class="card-title">Sistema de Alarme</span>
                        <p>Grupo de Verificação 272, Alarme Monitorado.</p>
                        <tr>
                            <p>Assunto: Ativação / Desativação | 272-2-SEGURANÇA-SistemaMonitoramento.xlsx</p>
                            <p><b>Modo Icremento</b></p>
                        </tr>
                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.alarme')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_frequenciaPorSE')
            <div class="col s12 m6">
                <div class="card #0d47a1 blue darken-4">
                    <div class="card-content white-text">
                        <span class="card-title">Sistema Segurança</span>
                        <p>Grupo de Verificação 272, Senhas Alarme Monitorado.</p>
                        <tr>
                            <p>Assunto: Verif. Compart. de Senhas | 272-3-WebSGQ3 - Frequencia por SE.xlsx</p>
                        </tr>
                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.absenteismo')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_cftv')
            <div class="col s12 m6">
                <div class="card #b71c1c red darken-4">
                    <div class="card-content white-text">
                        <span class="card-title">Sistema Segurança Patrimonial</span>
                        <p>Grupo/Item: 272.4, Função: Verif. Funcionamento do equipamento CFTV
                            <br>Arquivo: 272-4-SEGURANÇA-Monitoramento-CFTV</p>
                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.cftv')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_feriasPorMcu')
            <div class="col s12 m6">
                <div class="card deep-orange">
                    <div class="card-content white-text">
                        <span class="card-title">Sistema Segurança</span>
                        <p>Grupo/Item: 272.3, Função: Verif. Compart. de Senhas - FÉRIAS<p/>
                        <P></P><br>Arquivo: 272-3-WebSGQ3 - Fruicao de ferias por MCU<p/>
                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.ferias')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_PLPs')
            <div class="col s12 m6">
                <div class="card deep-purple">

                    <div class="card-content white-text">
                        <span class="card-title">Sistema: Atendimento Comercial</span>
                        <br/> <p>Grupo/Item: 274.1, Função: Condições de Aceitação, Classificação e Tarifação de Objetos
                            <br>Arquivo: 274-1-PLP-ListasPendentes</p>
                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.plpListaPendente')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_controleViagem')
            <div class="col s12 m6">
                <div class="card #424242 grey darken-3">
                    <div class="card-content white-text">
                        <span class="card-title">Sistema: Movimentação de Carga Postal</span>
                        <p>Grupo/Item: 276.1, Função: Controle de viagem Apontamentos
                            <br>Arquivo: 276-1-ControleDeViagem</p>
                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.controleDeViagem')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_sgdo')
            <div class="col s12 m6">
                <div class="card green darken-1">
                    <div class="card-content white-text">
                        <span class="card-title">Sistema: Distribuição Domiciliária <br>SGDO</span>
                        <p>Grupo/Item: 277.1, Função: Lançamentos SGDO
                            <br>Lançamentos obrigatórios
                            <br>Arquivo: 277-1-SGDO-Distribuição</p>
                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.sgdoDistribuicao')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_painelExtravio')
            <div class="col s12 m6">
                <div class="card #b71c1c red darken-4">
                    <div class="card-content white-text">
                        <span class="card-title">Sistema: Distribuição Domiciliária <br>Pré Alerta</span>
                        <p>Grupo/Item: 277.5, Função: Gestão SRO
                            <br>Conferência Eletrônica.
                            <br>Arquivo: 277-5-PainelExtravio</p>
                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.painelExtravio')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_cieEletronica')
            <div class="col s12 m6">
                <div class="card #0d47a1 blue darken-4">
                    <div class="card-content white-text">
                        <span class="card-title">Sistema: Plano de Triagem <br>Encaminhamento</span>
                        <p>Grupo/Item: 277.7, Função: Gestão SRO</p>
                        <P><br>Arquivo: 277-7-CieEletronica<p/>

                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.cieEletronica')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_pagamentosAdicionais')
            <div class="col s12 m6">
                <div class="card #004d40 teal darken-4">
                    <div class="card-content white-text">
                        <span class="card-title">Sistema: Gestão de Recursos Humanos</span>
                        <p>Grupo/Item: 278.2, Função: Gestão de Recursos Humanos</p>
                        <P><br>Arquivo: 278-2-WebSGQ-3-PagamentosAdicionais</P>

                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.pagamentosAdicionais')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('importar_bdfFat_02')
            <div class="col s12 m6">
                <div class="card #dd2c00 deep-orange accent-4">
                    <div class="card-content white-text">
                        <span class="card-title">Sistema: Gestão de Recursos Humanos </span>
                        <p>Grupo/Item: 278.2, Função: Recebimentos
                            Arquivo: 278-2-BDF_FAT_02.xlsx - Unidades que irão ser inspecionadas ultimos 210 dias.</p>
                    </div>
                    <div class="card-action">
                        <a class="white-text" href="{{route('importacao.bdf_fat_02')}}">Importar Planilha</a>
                    </div>
                </div>
            </div>
        @endcan

            @can('importar_microStrategy')
                <div class="col s12 m6">
                    <div class="card  #424242 grey darken-3">
                        <div class="card-content white-text">
                            <span class="card-title">Sistema: Gestão da Distribuição Domiciliaria</span>
                            <p>Grupo/Item: 277.2, Função: Gestão de Recursos SRO
                                <br>Arquivo: 277-2-4_3-ObjetosNaoEntreguePrimeiraTentativa</p>
                        </div>
                        <div class="card-action">
                            <a class="white-text" href="{{route('importacao.microStrategy')}}">Importar Planilha</a>
                        </div>
                    </div>
                </div>
            @endcan





</div>
@endsection
