<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposToVerificacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('verificacoes', function (Blueprint $table) {
            $table->enum('status', ['Em Inspeção', 'Inspecionado','Em Análise', 'Em Manifestação', 'Concluida'])->default('Em Inspeção');
            $table->string('NumHrsPreInsp')->nullable();
            $table->string('NumHrsDesloc')->nullable();
            $table->string('NumHrsInsp')->nullable();
            $table->text('eventoInspecao')->nullable();//pode ser nulo
            $table->string('xml')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('verificacoes', function (Blueprint $table) {
            $table->enum('status', ['Em Inspeção', 'Inspecionado', 'Em Análise', 'Em Manifestação', 'Concluida'])->default('Em Inspeção');
            $table->dropColumn('NumHrsPreInsp');
            $table->dropColumn('NumHrsDesloc');
            $table->dropColumn('NumHrsInsp');
            $table->dropColumn('eventoInspecao');
            $table->dropColumn('xml');
        });
    }
}
