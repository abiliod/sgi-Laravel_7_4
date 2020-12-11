<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->string('se');
            $table->biginteger('tipoUnidade_id')->unsigned();
            $table->string('descricao');
            $table->biginteger('an8');
            $table->string('mcu');
            $table->string('cnpj')->nullable();
            $table->string('faixaCepIni')->nullable();
            $table->string('faixaCepFim')->nullable();
            $table->string('tipoEstrutura')->nullable();
            $table->string('sto');
            $table->string('status_unidade');
            $table->time('inicio_expediente');
            $table->time('final_expediente');
            $table->time('inicio_intervalo_refeicao')->nullable();
            $table->time('final_intervalo_refeicao')->nullable();
            $table->string('trabalha_sabado');
            $table->time('inicio_expediente_sabado')->nullable();
            $table->time('final_expediente_sabado')->nullable();
            $table->string('trabalha_domingo');
            $table->time('inicio_expediente_domingo')->nullable();
            $table->time('final_expediente_domingo')->nullable();
            $table->string('tem_plantao');
            $table->time('inicio_plantao_sabado')->nullable();
            $table->time('final_plantao_sabado')->nullable();
            $table->time('inicio_plantao_domingo')->nullable();
            $table->time('final_plantao_domingo')->nullable();
            $table->string('tem_distribuicao');
            $table->time('inicio_distribuicao')->nullable();
            $table->time('final_distribuicao')->nullable();
            $table->time('horario_lim_post_na_semana')->nullable();
            $table->time('horario_lim_post_final_semana')->nullable();
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::table('unidades', function (Blueprint $table) {

            $table->index('mcu');
            $table->index('tipoUnidade_id');
            $table->foreign('tipoUnidade_id')
                ->references('id')
                ->on('tiposDeUnidade')
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
            Schema::dropIfExists('unidades');
    }
}
