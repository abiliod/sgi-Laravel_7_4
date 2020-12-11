<?php

use Illuminate\Database\Seeder;

class MicroStrategyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('micro_strategys')->insert([
            ['dr_de_destino' => 'GO','nome_da_unidade' => 'AC RIALMA','codigo_do_objeto' => 'PY411909861BR','descricao_do_evento' => 'ENTREGUE','codigo_do_evento' => 'BDE','data_do_evento' => '2020-06-17'],
            ['dr_de_destino' => 'GO','nome_da_unidade' => 'AC RIALMA','codigo_do_objeto' => 'PY411909861BR','descricao_do_evento' => 'ENTREGUE','codigo_do_evento' => 'BDE','data_do_evento' => '2020-06-17']

        ]);
        $this->command->info('MicroStrategy importados com sucesso!');
    }
}
