<?php
use Illuminate\Database\Seeder;
use App\Papel;

class PapelTableSeeder extends Seeder{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run(){


        if(!Papel::where('nome','=','admin')->count()){
            $admin = Papel::create([
                'nome'=>'admin',
                'descricao'=>'Administrador do sistema'
            ]);
        }
        if(!Papel::where('nome','=','gerente')->count()){
            $admin = Papel::create([
                'nome'=>'gerente',
                'descricao'=>'Gerente do sistema'
            ]);
        }
        if(!Papel::where('nome','=','vendedor')->count()){
            $admin = Papel::create([
                'nome'=>'vendedor',
                'descricao'=>'Equipe de vendas'
            ]);
        }


        if(!Papel::where('nome','=','Representante')->count()){
            $admin = Papel::create([
                'nome'=>'Representante',
                'descricao'=>'Representante Equipe de vendas'
            ]);
        }

        if(!Papel::where('nome','=','Cliente/Fornecedor')->count()){
            $admin = Papel::create([
                'nome'=>'Cliente/Fornecedor',
                'descricao'=>'Cliente'
            ]);
        }

        echo "Papeis gerados com sucesso!\n";
    }
}

