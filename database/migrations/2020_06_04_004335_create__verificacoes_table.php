<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verificacoes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->biginteger('unidade_id')->unsigned();
            $table->string('descricao')->nullable();
            $table->year('ciclo');
            $table->biginteger('tipoUnidade_id');
            $table->string('tipoVerificacao');
            $table->enum('status', ['Em Inspeção', 'Inspecionado', 'Em Manifestação', 'Concluida'])->default('Em Inspeção');
            $table->string('inspetorcoordenador');
            $table->string('inspetorcolaborador');
            $table->date('datainiPreInspeção');
            $table->timestamps();
        });

        Schema::table('verificacoes', function (Blueprint $table)
        {
            $table->index('unidade_id');
            $table->foreign('unidade_id')
            ->references('id')
            ->on('unidades')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('verificacoes');

    }


}
