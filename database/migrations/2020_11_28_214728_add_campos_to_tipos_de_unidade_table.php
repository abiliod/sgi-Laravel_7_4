<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposToTiposDeUnidadeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tiposDeUnidade', function (Blueprint $table) {
            $table->renameColumn('descricao', 'tipodescricao');
            // $table->string('descricao');
            // $table->string('tipodescricao');
            $table->enum('inspecionar', ['Sim', 'Não'])->default('Não');
            $table->enum('tipoInspecao', ['Ambos', 'Presencial', 'Remoto','Suspenso'])->default('Suspenso');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tiposDeUnidade', function (Blueprint $table) {
            $table->renameColumn( 'tipodescricao', 'descricao');
            $table->dropColumn('inspecionar');
            $table->dropColumn('tipoInspecao');
        });
    }
}
