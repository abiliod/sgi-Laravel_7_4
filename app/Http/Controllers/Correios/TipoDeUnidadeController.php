<?php

namespace App\Http\Controllers\Correios;

use App\Http\Controllers\Controller;
use App\Models\Correios\TipoDeUnidade;
use App\Models\Correios\Unidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoDeUnidadeController extends Controller
{

    public function update(Request $request, $id)
    {
        $registro = TipoDeUnidade::find($id);
        $dados = $request->all();
        $registro->codigo = $dados['codigo'];
        $registro->sigla = $dados['sigla'];
        $registro->tipodescricao = $dados['descricao'];
        $registro->inspecionar = $dados['inspecionar'];
        $registro->tipoInspecao = $dados['tipoInspecao'];
        $registro->update();

        \Session::flash('mensagem',['msg'=>'O Tipo de Unidade:  '.$registro->descricao.' foi atualizado com sucesso !'
            ,'class'=>'green white-text']);
        return redirect()->route('compliance.tipounidades');
    }

    public function edit($id)
    {
        $registro = TipoDeUnidade::find($id) ;
        return view('compliance.tipounidades.editar',compact('registro'));
    }


    public function index()
    {
        $registros = DB::table('tiposDeUnidade')
            ->orderBy('tipodescricao' , 'asc')
            ->paginate(10);
        return view('compliance.tipounidades.index',compact('registros'));  //
    }
}
