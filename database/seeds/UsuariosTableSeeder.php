<?php
use Illuminate\Database\Seeder;
use App\User;

class UsuariosTableSeeder extends Seeder{
    /**
    * @return void
    */
    public function run(){

        if(User::where('email','=','admin@sgiweb.com')->count()){
            $usuario = User::where('email','=','admin@sgiweb.com')->first();
            $usuario->name = "Masterkey-Abilio";
            $usuario->document	 = '83288082';
            $usuario->businessUnit	 = '434448';
            $usuario->email = "admin@sgiweb.com";
            $usuario->password = bcrypt("12345678");
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name = "Masterkey-Abilio";
            $usuario->email = "admin@sgiweb.com";
            $usuario->document	 = '83288082';
            $usuario->businessUnit	 = '434448';
            $usuario->password = bcrypt("12345678");
            $usuario->save();
        }


        if(User::where('email','=','admin@compliance.com')->count()){
            $usuario = User::where('email','=','admin@compliance.com')->first();
            $usuario->name = "Masterkey-MarcosV";
            $usuario->email = "admin@compliance.com";
            $usuario->document	 = '89056582';
            $usuario->businessUnit	 = '434063';
            $usuario->password = bcrypt("12345678");
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name = "Masterkey-MarcosV";
            $usuario->email = "admin@compliance.com";
            $usuario->document	 = '89056582';
            $usuario->businessUnit	 = '434063';
            $usuario->password = bcrypt("12345678");
            $usuario->save();
        }



        echo "\n Usuario Master criado com sucesso!"
        ,"\n email = admin@compliance.com"
        ,"\n password = 12345678";

        echo "\n Demais Usuários criados com sucesso!"
        ,"\n email = >seuemail>@correios.com.br"
        ,"\n password = MATRICULA-> 99999999";

    }
}
