<?php

namespace App\Http\Controllers\Correios;

use App\Http\Controllers\Controller;
use App\Models\Correios\Inspecao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PlanejamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $businessUnitUser = DB::table('unidades')
            ->Where([['mcu', '=', auth()->user()->businessUnit]])
            ->select('unidades.*')
            ->first();
        if(!empty( $businessUnitUser ))
        {
            $papel_user = DB::table('papel_user')
                ->Where([['user_id', '=', auth()->user()->id]])
                ->Where([['papel_id', '>=', 1]])
                ->select('papel_id')
                ->first();

            switch ($papel_user->papel_id)
            {
                case 1:
                case 2:
                    {
                        $inspetores = DB::table('papel_user')
                            ->join('users', 'users.id',  '=',   'user_id')
                            ->select('users.*','papel_user.*')
                            ->Where([['se', '=', $businessUnitUser->se]])
                            ->Where([['papel_id', '=', 6]])
                            ->get();
                        $registros = DB::table('unidades')
                            ->join('inspecoes', 'unidades.id',  '=',   'unidade_id')
                            ->select('inspecoes.*','unidades.se','unidades.seDescricao')
                            ->where([['status', '=',  'Em Inspeção']])
                            ->where([['se', '=', $businessUnitUser->se]])
                            ->where([['inspetorcoordenador', '=', null ]])
                            ->orderBy('codigo' , 'asc')
                            ->paginate(15);
        //                dd($inspetores, $businessUnitUser, $registros);
//
//                        +"tipoVerificacao": "Monitorada"
//                    +"status": "Em Inspeção"
//                    +"inspetorcoordenador": null
//                    +"inspetorcolaborador": null
                    }
                    break;
                case 3:
                    {
                        $inspetores = DB::table('papel_user')
                            ->join('users', 'users.id',  '=',   'user_id')
                            ->select('users.*','papel_user.*')
                            ->Where([['se', '=', $businessUnitUser->se]])
                            //  ->Where([['user_id', '=', auth()->user()->id]])
                            ->Where([['papel_id', '=', 6]])
                            ->get();

                        $first = DB::table('unidades')
                            ->join('inspecoes', 'unidades.id',  '=',   'unidade_id')
                            ->select('inspecoes.*','unidades.se','unidades.seDescricao')
                            ->where([['status', '=', 'Em Inspeção']])
                            ->where([['inspetorcoordenador', '=', auth()->user()->document]]);
                        $registros = DB::table('unidades')
                            ->join('inspecoes', 'unidades.id',  '=',   'unidade_id')
                            ->select('inspecoes.*','unidades.se','unidades.seDescricao')
                            ->where([['status', '=', 'Em Inspeção']])
                            ->Where([['inspetorcolaborador', '=', auth()->user()->document]])
                            ->union($first)
                            ->orderBy('codigo' , 'asc')
                            ->paginate(10);

                        \Session::flash('mensagem',['msg'=>'Listando Inspeções da '.$businessUnitUser->seDescricao
                            ,'class'=>'orange white-text']);
                    }
                    break;
                case 4:
                    {
                        $inspetores = DB::table('papel_user')
                            ->join('users', 'users.id',  '=',   'user_id')
                            ->select('users.*','papel_user.*')
                            ->Where([['se', '=', $businessUnitUser->se]])
                            //  ->Where([['user_id', '=', auth()->user()->id]])
                            ->Where([['papel_id', '=', 6]])
                            ->get();

                        $first = DB::table('unidades')
                            ->join('inspecoes', 'unidades.id',  '=',   'unidade_id')
                            ->select('inspecoes.*','unidades.se','unidades.seDescricao')
                            ->where([['status', '=', 'Em Inspeção']]);
                        // ->where([['inspetorcoordenador', '=', auth()->user()->document]]);

                        $registros = DB::table('unidades')
                            ->join('inspecoes', 'unidades.id',  '=',   'unidade_id')
                            ->select('inspecoes.*','unidades.se','unidades.seDescricao')
                            ->where([['status', '=', 'Em Inspeção']])
                            //  ->Where([['inspetorcolaborador', '=', auth()->user()->document]])
                            ->union($first)
                            ->orderBy('codigo' , 'asc')
                            ->paginate(10);

                        \Session::flash('mensagem',['msg'=>'Listando Inspeções da '.$businessUnitUser->seDescricao
                            ,'class'=>'orange white-text']);
                    }
                    break;
                case 5:
                    {
                        \Session::flash('mensagem',['msg'=>'Não autorizado.'
                            ,'class'=>'red white-text']);
                    }
                    break;
                case 6:
                    {
                        $inspetores = DB::table('papel_user')
                            ->join('users', 'users.id',  '=',   'user_id')
                            ->select('users.*','papel_user.*')
                            ->Where([['se', '=', $businessUnitUser->se]])
                            ->Where([['user_id', '=', auth()->user()->id]])
                            ->Where([['papel_id', '=', 6]])
                            ->get();

                        $first = DB::table('unidades')
                            ->join('inspecoes', 'unidades.id',  '=',   'unidade_id')
                            ->select('inspecoes.*','unidades.se','unidades.seDescricao')
                            ->where([['status', '=', 'Em Inspeção']])
                            ->where([['inspetorcoordenador', '=', auth()->user()->document]]);
                        $registros = DB::table('unidades')
                            ->join('inspecoes', 'unidades.id',  '=',   'unidade_id')
                            ->select('inspecoes.*','unidades.se','unidades.seDescricao')
                            ->where([['status', '=', 'Em Inspeção']])
                            ->Where([['inspetorcolaborador', '=', auth()->user()->document]])
                            ->union($first)
                            ->orderBy('codigo' , 'asc')
                            ->paginate(10);
                    }
                    break;
                default:  return redirect()->route('home');
            }

            $tiposDeUnidade = DB::table('tiposdeunidade')
                ->where([
                    ['inspecionar', '=', 'sim'],
                    ['tipoInspecao', '=', 'Monitorada'],
                ])
                ->get();

            return view('compliance.planejamento.index',compact('inspetores','registros', 'tiposDeUnidade'));
        }
        else
        {
            \Session::flash('mensagem',['msg'=>'Não foi possivel exibir os itens você provavelmente não é administrador.'
                ,'class'=>'red white-text']);
            return redirect()->route('home');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro = Inspecao::find($id);

        $tiposDeUnidade = DB::table('tiposdeunidade')
            ->where([
                ['inspecionar', '=', 'sim'],
                ['tipoInspecao', '=', 'Monitorada'],
            ])
        ->get();

        $businessUnitUser = DB::table('unidades')
            ->Where([['mcu', '=', auth()->user()->businessUnit]])
            ->select('unidades.*')
        ->first();
        $inspetores = DB::table('papel_user')
            ->join('users', 'users.id',  '=',   'user_id')
            ->select('users.*','papel_user.*')
            ->Where([['se', '=', $businessUnitUser->se]])
            ->Where([['papel_id', '=', 6]])
        ->get();
      //  dd($registro,$tiposDeUnidade,$inspetores);
        return view('compliance.planejamento.edit',compact('registro','tiposDeUnidade','inspetores'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
             'inspetorcoordenador' => 'required'
            , 'inspetorcolaborador' => 'required'
            , 'numHrsPreInsp' => 'required'
            , 'numHrsDesloc' => 'required'
            , 'numHrsInsp' => 'required'
        ]);
        if($validator->passes())
        {
            $dados = $request->all();

            if($dados['inspetorcoordenador']  == $dados['inspetorcolaborador'] )
            {
                \Session::flash('mensagem',['msg'=>'Inspetores devem ser diferentes !'
                    ,'class'=>'red white-text']);
                return redirect()->back();
            }

            $registro = Inspecao::find($id);
            $registro->inspetorcoordenador = $dados ['inspetorcoordenador'];
            $registro->inspetorcolaborador = $dados ['inspetorcolaborador'];
            $registro->numHrsPreInsp       = $dados ['numHrsPreInsp'];
            $registro->numHrsDesloc        = $dados ['numHrsDesloc'];
            $registro->numHrsInsp          = $dados ['numHrsInsp'];
            $registro->update();

            \Session::flash('mensagem',['msg'=>'Inspeção Atualizada com sucesso !'
                ,'class'=>'green white-text']);

            return redirect()->route('compliance.planejamento');
        }
        else
        {
            if ( (empty($dados ['inspetorcoordenador'])) ||
                (empty($dados ['inspetorcolaborador'])))
            {
                \Session::flash('mensagem',['msg'=>'Erro o Arquivo. Não foi atualizado vincule Inspetor.'
                    ,'class'=>'red white-text']);
            }
            return back();
        }
    }
}
