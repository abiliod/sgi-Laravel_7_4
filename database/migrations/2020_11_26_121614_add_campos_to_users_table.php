<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposToUsersTable extends Migration
{
    /**  ok
     * Run the migrations.
     * Altera a tabela de Usuarios acrescenta os campos:
     * document para guardar Matricula e businessUnit para guardar a localização do usuário
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('document')->nullable()->after('email');
            $table->string('businessUnit')->nullable()->after('document');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('document');
            $table->dropColumn('businessUnit');
        });
    }
}
