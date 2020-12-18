<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Pagina;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Papel;

use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller {

    public function sair() {
        Auth::logout();
        return redirect()->route('login');
    }



    public function adicionar() {

        if(!auth()->user()->can('usuario_adicionar')){
           return redirect()->route('home');
        }
        return view('admin.usuarios.adicionar');
    }

    public function salvar(Request $request) {

        if(!auth()->user()->can('usuario_adicionar')){
            return redirect()->route('home');
        }

        $dados = $request->all();
        $usuario = new User();
        $usuario->name = $dados['name'];
        $usuario->email = $dados['email'];
        $usuario->password = bcrypt($dados['password']);
        $usuario->save();

        \Session::flash('mensagem',['msg'=>'Registro criado com sucesso!','class'=>'green white-text']);

        return redirect()->route('admin.usuarios');
    }

    public function editar($id) {
        if(!auth()->user()->can('usuario_editar')){
            return redirect()->route('home');
        }


        $usuario = User::find($id);
        //dd(    $usuario->papel_user->papel_id);

        if ($usuario->id == auth()->user()->getAuthIdentifier())
        {
           $papel_user = DB::table('papel_user')
                            ->Where([['user_id', '=', auth()->user()->id]])
                            //->Where([['papel_id', '<=', 4]])
                            ->select('papel_id')
                            ->first();

           if(!empty($papel_user))
           {
               $usuario->papel_id = $papel_user->papel_id;
           }
           else
           {
               $usuario->papel_id = 100;
           }

        }
        else
        {
            $usuario->papel_user = 100;
        }
         // dd(    $usuario);
        return view('admin.usuarios.editar', compact('usuario'));
    }

    public function atualizar(Request $request, $id) {

        if(!auth()->user()->can('usuario_editar')){
           return redirect()->route('home');
        }

        $usuario = User::find($id);
        $dados = $request->all();

        if(isset($dados['password']) && strlen($dados['password']) >= 8 ){
            $dados['password'] = bcrypt($dados['password']);
        }else{
            unset($dados['password']); //tira do request o campo pass para aplicar o update
        }

        $usuario ->update($dados);
        \Session::flash('mensagem',['msg'=>'Registro atualizado com sucesso!','class'=>'green white-text']);


        $papel_user = DB::table('papel_user')
            ->Where([['user_id', '=', auth()->user()->id]])
           // ->Where([['papel_id', '>=', 1]])
            ->select('papel_id')
            ->first();
        if($papel_user->papel_id  <= 4 )
        {
            return redirect()->route('admin.usuarios');
        }
        else
        {
            return redirect()->route('home');
        }
    }

    public function deletar($id) {
        if(!auth()->user()->can('usuario_deletar')){
            return redirect()->route('home');
        }
        User::find($id)->delete();
        \Session::flash('mensagem',['msg'=>'Registro deletado com sucesso!','class'=>'green white-text']);
        return redirect()->route('admin.usuarios');
    }

    public function login(Request $request) {
        $dados = $request->all();

        if(Auth::attempt(['email'=>$dados['email'],'password'=>$dados['password']])){

            \Session::flash('mensagem',['msg'=>'Login realizado com sucesso!'
                ,'class'=>'green white-text']);

            return redirect()->route('admin.principal');
        }

        \Session::flash('mensagem',['msg'=>'Erro! Confira seus dados.'
            ,'class'=>'red white-text']);

        return redirect()->route('admin.login');
    }

    public function papel($id) {
        if(!auth()->user()->can('usuario_editar')){
           return redirect()->route('home');
       }

       $usuario = User::find($id);
       $papel = Papel::all();

       return view('admin.usuarios.papel',compact('usuario','papel'));
    }

    public function salvarPapel(Request $request,$id) {

        if(!auth()->user()->can('usuario_editar')){
            return redirect()->route('home');
        }

        $usuario = User::find($id);
        $dados = $request->all();
        $papel = Papel::find($dados['papel_id']);
        $usuario->adicionaPapel($papel);
        return redirect()->back();
    }

    public function removerPapel($id,$papel_id) {

        if(!auth()->user()->can('usuario_editar')){
            return redirect()->route('home');
        }
        $usuario = User::find($id);
        $papel = Papel::find($papel_id);
        $usuario->removePapel($papel);
        return redirect()->back();
    }

    public function search (Request $request )
    {
        if(auth()->user()->can('usuario_listar'))
        {
            if ($request->all()['search']==NULL){
                \Session::flash('mensagem',['msg'=>'Para Filtrar ao menos um parâmetro é necessário.'
                    ,'class'=>'red white-text']);
                return redirect()->back();
            }

//            $usuarios = DB::table('users')
//                ->select('users.*')
//                ->where('name', 'like', '%' . trim($request->all()['search']) . '%')  //trim($registro->descricao)
//                ->orWhere([['document', '=', $request->all()['search']]])
//                ->orWhere([['businessUnit', '=', $request->all()['search']]])
//                ->orderBy('name' , 'asc')
//                ->paginate(10);

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
                        $usuarios = DB::table('users')
                            ->select('users.*')
                            ->where('name', 'like', '%' . trim($request->all()['search']) . '%')  //trim($registro->descricao)
                            ->orWhere([['document', '=', $request->all()['search']]])
                            ->orWhere([['businessUnit', '=', $request->all()['search']]])
                            ->orderBy('name', 'asc')
                            ->paginate(10);
                        \Session::flash('mensagem',['msg'=>'Listando todos usuários do sistema.'
                            ,'class'=>'orange white-text']);
                    }
                    break;
                case 3:
                    {
                        $usuarios = DB::table('users')
                            ->select('users.*')
                            ->Where([['se', '=', $businessUnitUser->se]])
                            ->where('name', 'like', '%' . trim($request->all()['search']) . '%')  //trim($registro->descricao)
                            ->orWhere([['document', '=', $request->all()['search']]])
                            ->orWhere([['businessUnit', '=', $request->all()['search']]])
                            ->orderBy('name', 'asc')
                            ->paginate(10);
                        \Session::flash('mensagem',['msg'=>'Listando todos usuários da Superintendência.'
                            ,'class'=>'orange white-text']);
                    }
                    break;
                case 4:
                case 5:
                    {
                        \Session::flash('mensagem',['msg'=>'Não autorizado.'
                            ,'class'=>'red white-text']);
                    }
                    break;
                case 6:
                    {
                        $usuarios = DB::table('users')
                            ->select('users.*')
                            ->Where([['se', '=', $businessUnitUser->se]])
                            ->whereIn('tipoOrgaoCod', [9, 4, 6])
                            ->where('name', 'like', '%' . trim($request->all()['search']) . '%')  //trim($registro->descricao)
                            ->orWhere([['document', '=', $request->all()['search']]])
                            ->orWhere([['businessUnit', '=', $request->all()['search']]])
                            ->orderBy('name', 'asc')
                            ->paginate(10);
                        \Session::flash('mensagem',['msg'=>'Listando Usuários de Unidades operacionais cadastrados.'
                            ,'class'=>'orange white-text']);
                    }
                    break;
            }
            return view('admin.usuarios.index',compact('usuarios'));
        }else{
            return redirect()->route('home');
        }
    }

    public function index()
    {
        $usuarios = User::all();
        $businessUnitUser = DB::table('unidades')
            ->Where([['mcu', '=', auth()->user()->businessUnit]])
            ->select('unidades.*')
            ->first();
        if(!empty( $businessUnitUser ))
        {
            if (auth()->user()->can('usuario_listar'))
            {
                $papel_user = DB::table('papel_user')
                    ->Where([['user_id', '=', auth()->user()->id]])
                    ->Where([['papel_id', '>=', 1]])
                    ->select('papel_id')
                    ->first();
                foreach ($usuarios as $user)
                {
                    if(!empty($user->document))
                    {
                        $res = DB::table('unidades')
                            ->Where([['mcu', '=', $user->businessUnit]])
                            ->select('unidades.*')
                            ->first();
                        if($res)
                        {
                            $user->se =  $res->se;
                            $user->seDescricao = $res->seDescricao;
                            $user->tipoOrgaoCod = $res->tipoOrgaoCod;
                            $user->descricao =  $res->descricao;
                            $user->tipoUnidade_id =  $res->tipoUnidade_id;
                            $user->telefone_ect =  $res->ddd . $res->telefone;
                            $user->save();
                        }

                    }

                }
                unset($usuarios);

                switch ($papel_user->papel_id)
                {
                    case 1:
                    case 2:
                        {
                            $usuarios = DB::table('users')
                                ->select('users.*')
                                ->orderBy('name', 'asc')
                                ->paginate(10);
                            \Session::flash('mensagem',['msg'=>'Listando todos usuários do sistema.'
                                ,'class'=>'blue white-text']);
                        }
                        break;
                    case 3:
                        {
                            $usuarios = DB::table('users')
                                ->select('users.*')
                                ->Where([['se', '=', $businessUnitUser->se]])
                                ->orderBy('name', 'asc')
                                ->paginate(10);
                            \Session::flash('mensagem',['msg'=>'Listando todos usuários da Superintendência.'
                                ,'class'=>'blue white-text']);
                        }
                        break;
                    case 4:
                        {
                            \Session::flash('mensagem',['msg'=>'Não autorizado.'
                                ,'class'=>'red white-text']);
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
                            $usuarios = DB::table('users')
                                ->select('users.*')
                                ->Where([['se', '=', $businessUnitUser->se]])
                                ->whereIn('tipoOrgaoCod', [9, 4, 6])
                                ->orderBy('name', 'asc')
                                ->paginate(10);
                            \Session::flash('mensagem',['msg'=>'Listando Usuários de Unidades operacionais cadastrados.'
                                ,'class'=>'blue white-text']);
                        }
                        break;
                }

                if ($papel_user->papel_id <= 4)
                {
                    return view('admin.usuarios.index', compact('usuarios'));
                }
                else
                {
                    \Session::flash('mensagem',['msg'=>'Não foi possivel processar Perfil insuficiente.'
                        ,'class'=>'red white-text']);
                    return redirect()->route('home');
                }


            }
            else
            {
                \Session::flash('mensagem',['msg'=>'Não foi possivel processar Perfil insuficiente.'
                    ,'class'=>'red white-text']);
                return redirect()->route('home');
            }
        }
        else
        {
            \Session::flash('mensagem',['msg'=>auth()->user()->name.' , a Unidade MCU '.auth()->user()->businessUnit.' , não foi localizada no sistema. Por favor atualize o cadastro desse usuário. Coloque o MCU válido da lotação correspondente.'
                ,'class'=>'red white-text']);
            return redirect()->route('home');
        }
    }

}
