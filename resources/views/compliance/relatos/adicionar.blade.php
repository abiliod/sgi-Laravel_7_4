@extends('layouts._gynPromo.app')

@section('content')
<div class="container">
	<h2 class="center">Adição de Grupo de Verificação</h2>
	<div class="row">
	 	<nav>
         <div class="nav-wrapper green">
		      	<div class="col s12">
			        <a href="{{ route('home')}}" class="breadcrumb">Início</a>
			        <a href="{{route('compliance.relatos')}}" class="breadcrumb">Grupos de Verificação</a>
			        <a class="breadcrumb">Adição de Itens de Verificação</a>
		      	</div>
            </div>

          </nav>
    </div>
    @if($errors->any())
        <div class="row red">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </div>
    @endif
	<div class="row">
		<form action="{{ route('compliance.relatos.salvar') }}" method="post">
		    @CSRF
            @include('compliance.relatos._form')
            <div class="input-field col s12">
                <button class="btn blue">Adicionar</button>
            </div>
		</form>
	</div>
</div>
@endsection
