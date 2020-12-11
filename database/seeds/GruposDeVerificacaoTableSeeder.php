<?php

use Illuminate\Database\Seeder;

class GruposDeVerificacaoTableSeeder extends Seeder

{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gruposDeVerificacao')->insert([
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'230','nomegrupo'=>'FINANCEIRO'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'231','nomegrupo'=>'SEGURANÇA POSTAL'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'232','nomegrupo'=>'SEGURANÇA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'233','nomegrupo'=>'BENS MÓVEIS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'234','nomegrupo'=>'INFRAESTRUTURA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'235','nomegrupo'=>'CONDIÇÕES DE ACEITAÇÃO, CLASSIFICAÇÃO E TARIFAÇÃO DE OBJETOS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'236','nomegrupo'=>'CAIXA POSTAL'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'237','nomegrupo'=>'ENTREGA INTERNA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'238','nomegrupo'=>'ASPECTOS COMERCIAIS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'239','nomegrupo'=>'CARGA POSTAL'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'240','nomegrupo'=>'DISTRIBUIÇÃO DOMICILIÁRIA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'241','nomegrupo'=>'RECURSOS HUMANOS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'242','nomegrupo'=>'SEGURANÇA NO TRABALHO'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'500','nomegrupo'=>'OUTRAS OPORTUNIDADES DE APRIMORAMENTO'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'212','nomegrupo'=>'FATURAMENTO E TARIFAÇÃO'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'213','nomegrupo'=>'PROTEÇÃO DE RECEITAS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'214','nomegrupo'=>'PRODUTOS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'215','nomegrupo'=>'MÁQUINA DE FRANQUEAR'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'216','nomegrupo'=>'ESTOQUE DE ETIQUETAS E COMPROVANTES'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'217','nomegrupo'=>'ATENDIMENTO'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'218','nomegrupo'=>'CAIXA POSTAL'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'219','nomegrupo'=>'CONTRATOS COMERCIAIS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'220','nomegrupo'=>'RECURSOS HUMANOS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'221','nomegrupo'=>'SEGURANÇA POSTAL'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'222','nomegrupo'=>'EXPEDIÇÃO'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'223','nomegrupo'=>'ORGANIZAÇÃO DA UNIDADE'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'224','nomegrupo'=>'GERENCIAMENTO'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'225','nomegrupo'=>'INFRAESTRUTURA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'226','nomegrupo'=>'AGF - GERENCIAL - PPP'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'227','nomegrupo'=>'AGF - ORGANIZAÇÃO - PPP'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'228','nomegrupo'=>'AGF - PROCESSOS - PPP'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'12','numeroGrupoVerificacao'=>'500','nomegrupo'=>'OUTRAS OPORTUNIDADES DE APRIMORAMENTO'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'14','numeroGrupoVerificacao'=>'200','nomegrupo'=>'CARGA POSTAL'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'14','numeroGrupoVerificacao'=>'201','nomegrupo'=>'DISTRIBUIÇÃO DOMICILIÁRIA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'14','numeroGrupoVerificacao'=>'202','nomegrupo'=>'PROTEÇÃO DE RECEITAS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'14','numeroGrupoVerificacao'=>'203','nomegrupo'=>'UNIDADES CENTRALIZADORAS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'14','numeroGrupoVerificacao'=>'204','nomegrupo'=>'ENTREGA INTERNA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'14','numeroGrupoVerificacao'=>'205','nomegrupo'=>'SEGURANÇA POSTAL'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'14','numeroGrupoVerificacao'=>'206','nomegrupo'=>'SEGURANÇA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'14','numeroGrupoVerificacao'=>'207','nomegrupo'=>'BENS MÓVEIS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'14','numeroGrupoVerificacao'=>'208','nomegrupo'=>'INFRAESTRUTURA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'14','numeroGrupoVerificacao'=>'209','nomegrupo'=>'RECURSOS HUMANOS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'14','numeroGrupoVerificacao'=>'210','nomegrupo'=>'SEGURANÇA NO TRABALHO'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'14','numeroGrupoVerificacao'=>'211','nomegrupo'=>'TRATAMENTO (SUBCENTRALIZADORA)'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'14','numeroGrupoVerificacao'=>'500','nomegrupo'=>'OUTRAS OPORTUNIDADES DE APRIMORAMENTO'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'16','numeroGrupoVerificacao'=>'200','nomegrupo'=>'CARGA POSTAL'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'16','numeroGrupoVerificacao'=>'201','nomegrupo'=>'DISTRIBUIÇÃO DOMICILIÁRIA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'16','numeroGrupoVerificacao'=>'202','nomegrupo'=>'PROTEÇÃO DE RECEITAS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'16','numeroGrupoVerificacao'=>'203','nomegrupo'=>'UNIDADES CENTRALIZADORAS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'16','numeroGrupoVerificacao'=>'204','nomegrupo'=>'ENTREGA INTERNA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'16','numeroGrupoVerificacao'=>'205','nomegrupo'=>'SEGURANÇA POSTAL'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'16','numeroGrupoVerificacao'=>'206','nomegrupo'=>'SEGURANÇA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'16','numeroGrupoVerificacao'=>'207','nomegrupo'=>'SEGURANÇA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'16','numeroGrupoVerificacao'=>'208','nomegrupo'=>'BENS MÓVEIS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'16','numeroGrupoVerificacao'=>'209','nomegrupo'=>'INFRAESTRUTURA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'16','numeroGrupoVerificacao'=>'210','nomegrupo'=>'RECURSOS HUMANOS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'16','numeroGrupoVerificacao'=>'211','nomegrupo'=>'SEGURANÇA NO TRABALHO'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'16','numeroGrupoVerificacao'=>'500','nomegrupo'=>'TRATAMENTO (SUBCENTRALIZADORA)'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'303','nomegrupo'=>'SEGURANÇA POSTAL'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'304','nomegrupo'=>'PROTEÇÃO DE RECEITAS (ENCOMENDAS)'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'305','nomegrupo'=>'TRANSPORTE E ACONDICIONAMENTO DA CARGA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'306','nomegrupo'=>'BENS MÓVEIS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'307','nomegrupo'=>'INFRAESTRUTURA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'308','nomegrupo'=>'RECURSOS HUMANOS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'309','nomegrupo'=>'SEGURANÇA PATRIMONIAL'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'310','nomegrupo'=>'SEGURANÇA NO TRABALHO'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'311','nomegrupo'=>'TRABALHOS PREPARATÓRIOS - PPP'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'312','nomegrupo'=>'DESCARREGAMENTO E CARREGAMENTO - PPP'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'313','nomegrupo'=>'PRÉ-ABERTURA, ABERTURA E PRÉ-TRIAGEM -  PPP'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'314','nomegrupo'=>'TRATAMENTO DE MENSAGENS -  PPP'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'315','nomegrupo'=>'TRATAMENTO MANUAL DE ENCOMENDAS E MALOTES -  PPP'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'316','nomegrupo'=>'TRATAMENTO AUTOMATIZADO DE ENCOMENDAS E MALOTES -  PPP'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'317','nomegrupo'=>'EXPEDIÇÃO -  PPP'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'318','nomegrupo'=>'RECONDICIONAMENTO -  PPP'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'319','nomegrupo'=>'OPERAÇÃO DE EQUIPAMENTOS DE SEGURANÇA POSTAL -  PPP'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'320','nomegrupo'=>'GESTÃO DE UNITIZADORES -  PPP'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'321','nomegrupo'=>'LISTA GERAL -  PPP'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'322','nomegrupo'=>'PLANEJAMENTO E QUALIDADE -  PPP'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'21','numeroGrupoVerificacao'=>'500','nomegrupo'=>'OUTRAS OPORTUNIDADES DE APRIMORAMENTO'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'31','numeroGrupoVerificacao'=>'400','nomegrupo'=>'CONTROLE E CAPTAÇÃO GCCAP/CCCAP (MENSAGENS)'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'31','numeroGrupoVerificacao'=>'401','nomegrupo'=>'CONTROLE E CAPTAÇÃO GCCAP/CCCAP (ENCOMENDAS)'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'31','numeroGrupoVerificacao'=>'402','nomegrupo'=>'CONTROLE E CAPTAÇÃO GCCAP/CCCAP (MENSAGENS E ENCOMENDAS)'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'31','numeroGrupoVerificacao'=>'403','nomegrupo'=>'PROTEÇÃO DE RECEITAS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'31','numeroGrupoVerificacao'=>'404','nomegrupo'=>'RECURSOS HUMANOS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Presencial','tipoUnidade_id'=>'31','numeroGrupoVerificacao'=>'500','nomegrupo'=>'OUTRAS OPORTUNIDADES DE APRIMORAMENTO'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Remoto','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'270','descricao'=>'FINANCEIRO'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Remoto','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'271','descricao'=>'SEGURANÇA POSTAL'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Remoto','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'272','descricao'=>'SEGURANÇA '],
            ['ciclo'=>'2020','tipoVerificacao'=>'Remoto','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'273','descricao'=>'BENS MÓVEIS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Remoto','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'274','descricao'=>'CONDIÇÕES DE ACEITAÇÃO, CLASSIFICAÇÃO E TARIFAÇÃO DE OBJETOS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Remoto','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'275','descricao'=>'ENTREGA INTERNA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Remoto','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'276','descricao'=>'CARGA POSTAL'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Remoto','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'277','descricao'=>'DISTRIBUIÇÃO, EXPEDIÇÃO E CONFERÊNCIA DA CARGA'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Remoto','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'278','descricao'=>'RECURSOS HUMANOS'],
            ['ciclo'=>'2020','tipoVerificacao'=>'Remoto','tipoUnidade_id'=>'1','numeroGrupoVerificacao'=>'500','descricao'=>'OUTRAS OPORTUNIDADES DE APRIMORAMENTO']



        ]);
        $this->command->info('Grupos de Inspecao De Unidades importados com sucesso!');
    }
}
