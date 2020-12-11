@extends('layouts._gynPromo.app')
@section('content')
<div class="container">
	<h2 class="center">Editar Unidades</h2>
	<div class="row">
	 	<nav>
		    <div class="nav-wrapper green">
		      	<div class="col s12">
			        <a href="{{ route('home')}}" class="breadcrumb">Início</a>
			        <a href="{{route('compliance.unidades')}}" class="breadcrumb">Lista de Unidades</a>
			        <a class="breadcrumb">Editar Unidades</a>
		      	</div>
		    </div>
	  	</nav>
	</div>
	<div class="row">
		<form action="{{route('compliance.unidades.atualizar', $registro->id)}}" method="post">
		{{csrf_field()}}
		<input type="hidden" name="_method" value="put">
		    @include('compliance.unidades._form')
        <button class="btn blue">Atualizar</button>
		</form>
	</div>
</div>
@endsection
