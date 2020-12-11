<?php

use Illuminate\Database\Seeder;
use App\Models\Pessoas\Pessoa;
class PessoasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Pessoa::Create(
            ['priName_Razao' => 'Abilio Ferreira','email' => 'admin@gynpromo.com']

        );
        echo "Inicializado o Cadastro para Pessoas com sucesso!\n";
   }

/**
 *       //cria 10 clientes e associa 3 tel
       factory(Pessoa::class, 10)->create()->each(function ($u) {
            $u->telefones()->saveMany(factory(Telefone::class,3)->make());
       });
 */

}

