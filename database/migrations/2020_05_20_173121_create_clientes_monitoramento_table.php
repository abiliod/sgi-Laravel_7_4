<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesMonitoramentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes_monitoramento', function (Blueprint $table) {
            $table->id();
            $table->string('mcu_cliente');
            $table->biginteger('codigo')->default(0);
            $table->string('cliente');
            $table->timestamps();
        });

        Schema::table('clientes_monitoramento', function (Blueprint $table)
        {
            $table->index('mcu_cliente');
          //  $table->foreign('mcu_cliente')
           //     ->references('mcu')
           //     ->on('unidades')
           //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes_monitoramento');
    }
}
