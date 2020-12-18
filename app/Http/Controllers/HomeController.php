<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/**
   * Abilio 29-02-2020 Inclusão de Funcionalidade
   * App\Papel
   * App\Papel_user;
   * App\User;
   * Utilizar as classes para atribuir papel inicial "Cliente/Fornecedor" para o novo usuário
   **/
  use App\Papel;
  use App\Papel_user;
  use App\User;
//  use App\Models\Pessoas\Pessoa;
  use Auth;

class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct() {

        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

     public function index() {


       /**
        *  Abilio 29-02-2020 Inclusão de Funcionalidade
        * Captura id do usuário logado;
        * Captura o código do Papel a ser assumido pelo novo cliente
        * Papel_user::create, esta função cria o papel do novo cliente e
        * atribui papel inicial "Cliente/Fornecedor" para o novo usuário
        * em seguida devolve o controle para a view Home.
        **/
       /* 06/03/2020  Cria pessoa em  relacionamento com Usuario pelo campo email sem foreng*/
//        $email = Auth::user()->email;
//
//        $pessoa = Pessoa::where('email', $email)->first();
//
//        if (!$pessoa) {
//
//            Pessoa::firstOrCreate(
//
//               ['priName_Razao' => Auth::user()->name],
//               ['email' => Auth::user()->email]
//            );
//
//        }
//        $registro = Pessoa::where('email', $email)->first();

        $usuario = User::where('email','=','admin@sgiweb.com')->first();

        if ($usuario) {
            /**
            *Abilio 20-02-2020  atribuir/manter papel admin ao usuario do sistema cujo o email é 'admin@gynpromo.com
            * @return void
            */
            if(!Papel_user::where('user_id', '=', $usuario->id )
                ->where('papel_id', '=', '1')->count()
            ) //consulta com 2 parametros

            Papel_user::UpdateOrCreate([
                'user_id'=>$usuario->id ,
                'papel_id'=>'1'
            ]);
        }

         $usuario = User::where('email','=','admin@compliance.com')->first();

         if ($usuario) {
             /**
              *Abilio 20-02-2020  atribuir/manter papel admin ao usuario do sistema cujo o email é 'admin@gynpromo.com
              * @return void
              */
             if(!Papel_user::where('user_id', '=', $usuario->id )
                 ->where('papel_id', '=', '1')->count()
             ) //consulta com 2 parametros

                 Papel_user::UpdateOrCreate([
                     'user_id'=>$usuario->id ,
                     'papel_id'=>'1'
                 ]);
         }


         /*Estabelece relacionamento com papel pelo campo user_id */
        //verifica se o usuário tem papel

         $activeUser = Auth::user()->activeUser;
         if($activeUser==true)
         {
          //   dd(  $usuario,        $activeUser );

             $user_id = Auth::user()->id;
             $user = Papel_user::where('user_id', $user_id)->first();
             if (!$user) { //usuario sem papel
                 //verifica se o papel existe
                 $papel = Papel::where('nome', "Cliente/Fornecedor")->first();
                 if ($papel) {
                     // papel existe atribui papel ao usuário
                     Papel_user::firstOrCreate(
                         ['user_id' => $user_id], ['papel_id' => $papel->id]
                     );
                     // direciona usuario para a view padrão do PAPEL
                      return view('home');
                     //return view('admin.pessoas.entradaCPF_CNPJ', compact('registro'));
                 }
             } else {
//                 verifica qual o papel do usuario
                 $papel_id = Papel::where('id', $user->papel_id)->first();
//                  direciona usuario para a view Dashboard padrão do perfil do usuário


                 switch ($papel_id->nome)
                 {
                     case $papel_id->nome:"admin";
                         return view('principal/home'); // criar a view padrão
                         break;
                     case $papel_id->nome:"gestor-ac";
                         return view('principal/home', compact('papel_id')); // criar a view padrão
                         break;
                     case $papel_id->nome:"gestor-se";
                         return view('principal/home', compact('papel_id')); // criar a view padrão
                         break;
                     case $papel_id->nome:"gerente-de-area";
                         return view('principal/home', compact('papel_id')); // criar a view padrão
                         break;
                     case $papel_id->nome:"Cliente/Fornecedor";
                         return view('principal/home', compact('papel_id')); // direcionar para o carrinho
                         break;
                     default:
                         return view('principal/home', compact('papel_id'));
                 }
             }
         }
         else
         {
             \Session::flash('mensagem',['msg'=>'Usuário Inativo. Contate o administrador'
                 ,'class'=>'red white-text']);
             return  view('principal/welcome');
         }
    }
}
