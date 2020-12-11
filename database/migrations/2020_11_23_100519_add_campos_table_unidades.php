<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposTableUnidades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unidades', function (Blueprint $table) {

            $table->biginteger('tipoUnidade_id')->after('id');
            $table->string('se')->after('tipoUnidade_id');
            $table->string('seDescricao')->after('se');
            $table->string('mcu')->after('seDescricao');
            $table->biginteger('an8')->after('mcu');
            $table->string('sto')->after('an8');
            $table->string('status_unidadeDesc')->after('sto');
            $table->string('status_unidade')->after('status_unidadeDesc');
            $table->string('descricao')->after('status_unidade');
            $table->string('tipoOrgaoCod')->nullable()->after('descricao');
            $table->string('tipoOrgaoDesc')->nullable()->after('tipoOrgaoCod');
            $table->string('cnpj')->nullable()->after('tipoOrgaoDesc');
            $table->string('categoria')->nullable()->after('cnpj');
            $table->string('mecanizacao')->nullable()->after('categoria');
            $table->string('faixaCepIni')->nullable()->after('mecanizacao');
            $table->string('faixaCepFim')->nullable()->after('faixaCepIni');
            $table->string('tem_distribuicao')->nullable()->after('faixaCepFim');
            $table->string('tipoEstrutura')->nullable()->after('tem_distribuicao');
            $table->string('quantidade_guiches')->nullable()->after('tipoEstrutura');
            $table->string('guiches_ocupados')->nullable()->after('quantidade_guiches');
            $table->string('ddd')->nullable()->after('guiches_ocupados');
            $table->string('telefone')->nullable()->after('ddd');
            $table->string('mcu_subordinacaoAdm')->nullable()->after('telefone');
            $table->string('desc_subordinacaoAdm')->nullable()->after('mcu_subordinacaoAdm');
            $table->string('nomeResponsavelUnidade')->nullable()->after('descricao_subordinacao_adm');
            $table->string('documentRespUnidade')->nullable()->after('nomeResponsavelUnidade');
            $table->string('email')->nullable()->after('documentRespUnidade');
            $table->string('tipo_de_estrutura')->nullable()->after('email');
            $table->string('subordinacao_tecnica')->after('tipo_de_estrutura');
            $table->time('inicio_expediente')->nullable()->after('subordinacao_tecnica');
            $table->time('final_expediente')->nullable()->after('inicio_expediente');
            $table->time('inicio_intervalo_refeicao')->nullable()->after('final_expediente');
            $table->time('final_intervalo_refeicao')->nullable()->after('inicio_intervalo_refeicao');
            $table->string('trabalha_sabado')->nullable()->after('final_intervalo_refeicao');
            $table->time('inicio_expediente_sabado')->nullable()->after('trabalha_sabado');
            $table->time('final_expediente_sabado')->nullable()->after('final_expediente_sabado');
            $table->string('trabalha_domingo')->nullable()->after('final_expediente_sabado');
            $table->time('inicio_expediente_domingo')->nullable()->after('trabalha_domingo');
            $table->time('final_expediente_domingo')->nullable()->after('inicio_expediente_domingo');
            $table->string('tem_plantao')->nullable()->after('final_expediente_domingo');
            $table->time('inicio_plantao_sabado')->nullable()->after('tem_plantao');
            $table->time('final_plantao_sabado')->nullable()->after('inicio_plantao_sabado');
            $table->time('inicio_plantao_domingo')->nullable()->after('final_plantao_sabado');
            $table->time('final_plantao_domingo')->nullable()->after('inicio_plantao_domingo');
            $table->time('inicio_distribuicao')->nullable()->after('final_plantao_domingo');
            $table->time('final_distribuicao')->nullable()->after('inicio_distribuicao');
            $table->time('horario_lim_post_na_semana')->nullable()->after('final_distribuicao');
            $table->time('horario_lim_post_final_semana')->nullable()->after('horario_lim_post_na_semana');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unidades', function (Blueprint $table) {
            $table->dropColumn('seDescricao');
            $table->dropColumn('status_unidadeDesc');
            $table->dropColumn('status_unidade');
            $table->dropColumn('tipoOrgaoCod');
            $table->dropColumn('tipoOrgaoDesc');
            $table->dropColumn('categoria');
            $table->dropColumn('mecanizacao');
            $table->dropColumn('tem_distribuicao');
            $table->dropColumn('quantidade_guiches');
            $table->dropColumn('guiches_ocupados');
            $table->dropColumn('ddd');
            $table->dropColumn('mcu_subordinacaoAdm');
            $table->dropColumn('desc_subordinacaoAdm');
            $table->dropColumn('nomeResponsavelUnidade');
            $table->dropColumn('documentRespUnidade');
            $table->dropColumn('tipo_de_estrutura');
            $table->dropColumn('subordinacao_tecnica');

        });
    }
}
