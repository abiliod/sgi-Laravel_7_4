<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNovoscamposTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('se')->nulllable()->after('activeUser');
            $table->string('seDescricao')->nulllable()->after('se');
            $table->string('tipoOrgaoCod')->nulllable()->after('seDescricao');
            $table->string('descricao')->nulllable()->after('tipoOrgaoCod');
            $table->string('tipoUnidade_id')->nulllable()->after('descricao');
      
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
            $table->dropColumn('se');
            $table->dropColumn('seDescricao');
            $table->dropColumn('tipoOrgaoCod');
            $table->dropColumn('descricao');
            $table->dropColumn('tipoUnidade_id');
            $table->dropColumn('document');
        });
    }
}
