<!-- Dropdown Structure -->
<ul id="dropdown1" class="dropdown-content">
    <li><a href="#">Administrativo</a></li>
    @can('usuario_listar')
        <li><a href="{{ route('admin.usuarios') }}">Usuários</a></li>
    @endcan
    @can('usuario_editar')
        <li><a href="{{ route('admin.usuarios.editar',Auth::user()->id) }}">Meu Cadastro</a></li>
    @endcan

    @can('tipoUnidade_listar')
        <li><a href="{{route('compliance.tipounidades')}}">Tipo de Unidades</a></li>
    @endcan

    @can('grupoverificacao_listar')
        <li><a href="{{route('compliance.grupoVerificacao')}}">Grupo de Verificação</a></li>
    @endcan

    @can('relato_listar')
        <li><a href="{{route('compliance.relatos')}}">Relatos</a></li>
    @endcan
    <li class="divider"></li>
    @can('compliance_listar_importacoes')
        <li><a href="{{route('importacao')}}">Importações</a></li>
    @endcan
    <li class="divider"></li>


    <li><a href="#">Operacional</a></li>
    <li class="divider"></li>
    @can('unidade_listar')
        <li><a href="{{route('compliance.unidades')}}">Unidades</a></li>
    @endcan

    @can('inspecao_listar')
        <li><a href="{{route('compliance.verificacoes')}}">Inspecionar</a></li>
    @endcan

    @can('inspecao_listar')
        <li><a href="{{route('compliance.inspecionados')}}">Inspecionadas</a></li>
    @endcan
    <li class="divider"></li>
    <li><a href="#">Site Conteúdo</a></li>

    @can('slide_listar')   {{--- inicio 26/02/2020 inclusao da funcionalidade Slide ---}}
    <li><a href="{{route('admin.slides')}}">Slides</a></li>
    @endcan

    @can('papel_listar')
        <li><a href="{{ route('admin.papel') }}">Papel</a></li>
    @endcan

    @can('pagina_listar')
        <li><a href="{{ route('admin.paginas') }}">Páginas</a></li>
    @endcan
</ul>
<nav margin-bottom="7px">
    <div class="nav-wrapper  #1b5e20 green darken-4">
        <div class="container">
            <a href="{{ route('home') }}" class="brand-logo left">SgiWeb</a>
            <a href="#" data-activates="mobile-demo" class="button-collapse">
                <i class="material-icons">menu</i>
            </a>
            <ul class="right hide-on-med-and-down">
                @if(Auth::guest())
                    <li><a href="{{ route('login') }}">Login</a></li>

                    <li><a href="{{ route('site.sobre') }}">Sobre</a></li>
                    <li><a href="{{ route('site.contato') }}">Contato</a>
                @else
                    <li><a  class="dropdown-button" href="#!" data-activates="dropdown1">{{ Auth::user()->name }}
                            <i class="material-icons right">arrow_drop_down</i></a></li>
                    <ul id="dropdown1" class="dropdown-content"></ul>
                    <li><a href="{{ route('site.sobre') }}">Sobre</a></li>
                    <li><a href="{{ route('site.contato') }}">Contato</a>
                    <li><a href="{{ route('sair') }}">Sair</a>
                @endif
            </ul>
            <ul class="side-nav" id="mobile-demo">
                @if(Auth::guest())
                    <li><a href="{{ route('login') }}">Login</a></li>

                    <li><a href="{{ route('site.sobre') }}">Sobre</a></li>
                    <li><a href="{{ route('site.contato') }}">Contato</a>
                    <li><a href="{{ route('sair') }}">Sair</a>
                @else
                    <li><a href="#">Administrativo</a></li>
                    @can('usuario_listar')
                        <li><a href="{{ route('admin.usuarios') }}">Usuários</a></li>
                    @endcan
                    @can('usuario_editar')
                        <li><a href="{{ route('admin.usuarios.editar',Auth::user()->id) }}">Meu Cadastro</a></li>
                    @endcan

                    @can('grupoverificacao_listar')
                        <li><a href="{{route('compliance.grupoVerificacao')}}">Grupo de Verificação</a></li>
                    @endcan

                    @can('relato_listar')
                        <li><a href="{{route('compliance.relatos')}}">Relatos</a></li>
                    @endcan
                    <li class="divider"></li>
                    @can('compliance_listar_importacoes')
                        <li><a href="{{route('importacao')}}">Importações</a></li>
                    @endcan
                    <li class="divider"></li>
                    <li><a href="#">Operacional</a></li>
                    @can('unidade_listar')
                        <li><a href="{{route('compliance.unidades')}}">Unidades</a></li>
                    @endcan

                    @can('inspecao_listar')
                        <li><a href="{{route('compliance.verificacoes')}}">Inspecionar</a></li>
                    @endcan

                    @can('inspecao_listar')
                        <li><a href="{{route('compliance.inspecionados')}}">Inspecionadas</a></li>
                    @endcan
                    <li class="divider"></li>
                    <li><a href="#">Site Conteúdo</a></li>

                    @can('slide_listar')   {{--- inicio 26/02/2020 inclusao da funcionalidade Slide ---}}
                    <li><a href="{{route('admin.slides')}}">Slides</a></li>
                    @endcan

                    @can('papel_listar')
                        <li><a href="{{ route('admin.papel') }}">Papel</a></li>
                    @endcan

                    @can('pagina_listar')
                        <li><a href="{{ route('admin.paginas') }}">Páginas</a></li>
                    @endcan

                    <li><a href="{{ route('site.sobre') }}">Sobre</a></li>
                    <li><a href="{{ route('site.contato') }}">Contato</a>
                @endif
            </ul>
        </div>
    </div>
</nav>
