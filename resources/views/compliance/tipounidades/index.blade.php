@extends('layouts._sgiweb.app')

@section('content')
<div class="container">
	<h2 class="center">Tipo de Unidades</h2>
	    <div class="row">

		<div class="row">
		</div>
        <nav>
			<div class="nav-wrapper green">
				<div class="col s12">
					<a href="{{ route('home')}}" class="breadcrumb">Início</a>
					<a class="breadcrumb">Tipo de Unidades</a>
				</div>
			</div>
		</nav>
	</div>
	<div class="row">
		<table>
				<thead>
					<tr>

                        <th>Codigo</th>
						<th>Sigla</th>
                        <th>Descricao</th>
                        <th>Liberado p/Inspecionar</th>
                        <th>Tipo de Inspeção</th>
                    	<th>Ação</th>
					</tr>
				</thead>
				<tbody>
				@foreach($registros as $registro)
					<tr>
                        <td>{{ $registro->codigo }}</td>
				    	<td>{{ $registro->sigla }}</td>
                        <td>{{ $registro->tipodescricao }}</td>
                        <td>{{ $registro->inspecionar }}</td>
                        <td>{{ $registro->tipoInspecao }}</td>
						<td>
                            <a class="waves-effect waves-light btn orange"
                             href="{{ route('compliance.tipounidades.editar',$registro->id) }}">Editar</a>
                            <a class="waves-effect waves-light btn red disabled" href="">Deletar</a>

                        </td>
					</tr>
                @endforeach
				</tbody>
			</table>

            <div class="row">
			     {!! $registros->links() !!}
            </div>

		</div>

		<div class="row">
			<a class="btn blue disabled" href="!#" >Adicionar</a>
		</div>
   	</div>

@endsection
