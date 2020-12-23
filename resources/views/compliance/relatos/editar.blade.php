@extends('layouts._sgiweb.app')
@section('content')
<div class="container">
	<h2 class="center">Editar Itens de Inspeções</h2>
	<div class="row">
	 	<nav>
		    <div class="nav-wrapper green">
		      	<div class="col s12">
			        <a href="{{ route('home')}}" class="breadcrumb">Início</a>
			        <a href="{{route('compliance.relatos')}}" class="breadcrumb">Itens de Verificação</a>
			        <a class="breadcrumb">Editar Itens de Inspeções</a>
		      	</div>
		    </div>
	  	</nav>
	</div>
	<div class="row">
		<form action="{{route('compliance.relatos.atualizar', $registro->id)}}" method="post">
		{{csrf_field()}}
		<input type="hidden" name="_method" value="put">
		    @include('compliance.relatos._form')
        <button class="btn blue">Atualizar</button>
		</form>
	</div>
</div>
@endsection
