<?php
use Illuminate\Database\Seeder;
use App\Permissao;
use Illuminate\Support\Facades\DB;

class PermissaoTableSeeder extends Seeder {
/**
* Run the database seeds.
*
* @return void
*/
public function run() {
    DB::table('permissaos')->truncate(); //excluir e zerar a tabela

    if(!Permissao::where('nome','=','usuario_listar')->count()) {
        Permissao::create([
            'nome'=>'usuario_listar',
            'descricao'=>'Listar Usuários'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','usuario_listar')->first();
        $permissao->update([
            'nome'=>'usuario_listar',
            'descricao'=>'Listar Usuários'
        ]);
    }

    if(!Permissao::where('nome','=','usuario_adicionar')->count()) {
        Permissao::create([
            'nome'=>'usuario_adicionar',
            'descricao'=>'Adicionar Usuários'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','usuario_adicionar')->first();
        $permissao->update([
            'nome'=>'usuario_adicionar',
            'descricao'=>'Adicionar Usuários'
        ]);
    }

    if(!Permissao::where('nome','=','usuario_editar')->count()) {
        Permissao::create([
            'nome'=>'usuario_editar',
            'descricao'=>'Editar Usuários'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','usuario_editar')->first();
        $permissao->update([
            'nome'=>'usuario_editar',
            'descricao'=>'Editar Usuários'
        ]);
    }

    if(!Permissao::where('nome','=','usuario_deletar')->count()) {
        Permissao::create([
            'nome'=>'usuario_deletar',
            'descricao'=>'Deletar Usuários'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','usuario_deletar')->first();
        $permissao->update([
            'nome'=>'usuario_deletar',
            'descricao'=>'Deletar Usuários'
        ]);
    }



    if(!Permissao::where('nome','=','papel_listar')->count()) {
        Permissao::create([
            'nome'=>'papel_listar',
            'descricao'=>'Listar Papéis'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','papel_listar')->first();
        $permissao->update([
            'nome'=>'papel_listar',
            'descricao'=>'Listar Papéis'
        ]);
    }

    if(!Permissao::where('nome','=','papel_adicionar')->count()) {
        Permissao::create([
            'nome'=>'papel_adicionar',
            'descricao'=>'Adicionar Papéis'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','papel_adicionar')->first();
        $permissao->update([
            'nome'=>'papel_adicionar',
            'descricao'=>'Adicionar Papéis'
        ]);
    }

    if(!Permissao::where('nome','=','papel_editar')->count()) {
        Permissao::create([
            'nome'=>'papel_editar',
            'descricao'=>'Editar Papéis'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','papel_editar')->first();
        $permissao->update([
            'nome'=>'papel_editar',
            'descricao'=>'Editar Papéis'
        ]);
    }

    if(!Permissao::where('nome','=','papel_deletar')->count()) {
        Permissao::create([
            'nome'=>'papel_deletar',
            'descricao'=>'Deletar Papéis'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','papel_deletar')->first();
        $permissao->update([
            'nome'=>'papel_deletar',
            'descricao'=>'Deletar Papéis'
        ]);
    }

    if(!Permissao::where('nome','=','pagina_listar')->count()) {
        Permissao::create([
            'nome'=>'pagina_listar',
            'descricao'=>'Listar Página'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','pagina_listar')->first();
        $permissao->update([
            'nome'=>'pagina_listar',
            'descricao'=>'Listar Página'
        ]);
    }

    if(!Permissao::where('nome','=','pagina_adicionar')->count()) {
        Permissao::create([
            'nome'=>'pagina_adicionar',
            'descricao'=>'Adicionar Página'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','pagina_adicionar')->first();
        $permissao->update([
            'nome'=>'pagina_adicionar',
            'descricao'=>'Adicionar Página'
        ]);
    }

    if(!Permissao::where('nome','=','pagina_editar')->count()) {
        Permissao::create([
            'nome'=>'pagina_editar',
            'descricao'=>'Editar Página'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','pagina_editar')->first();
        $permissao->update([
            'nome'=>'pagina_editar',
            'descricao'=>'Editar Página'
        ]);
    }

    if(!Permissao::where('nome','=','pagina_deletar')->count()) {
        Permissao::create([
            'nome'=>'pagina_deletar',
            'descricao'=>'Deletar Página'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','pagina_deletar')->first();
        $permissao->update([
            'nome'=>'pagina_deletar',
            'descricao'=>'Deletar Página'
        ]);
    }

    // 28/02/2020 inicio permissões para Slides
    if(!Permissao::where('nome','=','slide_listar')->count()) {
        Permissao::create([
            'nome'=>'slide_listar',
            'descricao'=>'Listar Slide'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','slide_listar')->first();
        $permissao->update([
            'nome'=>'slide_listar',
            'descricao'=>'Listar Slide'
        ]);
    }

    if(!Permissao::where('nome','=','slide_adicionar')->count()) {
        Permissao::create([
            'nome'=>'slide_adicionar',
            'descricao'=>'Adicionar Slide'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','slide_adicionar')->first();
        $permissao->update([
            'nome'=>'slide_adicionar',
            'descricao'=>'Adicionar Slide'
        ]);
    }

    if(!Permissao::where('nome','=','slide_editar')->count()) {
        Permissao::create([
            'nome'=>'slide_editar',
            'descricao'=>'Editar Slide'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','slide_editar')->first();
        $permissao->update([
            'nome'=>'slide_editar',
            'descricao'=>'Editar Slide'
        ]);
    }

    if(!Permissao::where('nome','=','slide_deletar')->count()) {
        Permissao::create([
            'nome'=>'slide_deletar',
            'descricao'=>'Deletar Slide'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','slide_deletar')->first();
        $permissao->update([
            'nome'=>'slide_deletar',
            'descricao'=>'Deletar Slide'
        ]);
    }
// 28/02/2020 fim permissões para Slides

// 14/04/2020  permissões para pessoas
    if(!Permissao::where('nome','=','pessoa_listar')->count()) {
        Permissao::create([
            'nome'=>'pessoa_listar',
            'descricao'=>'Listar Pessoas'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','pessoa_listar')->first();
        $permissao->update([
            'nome'=>'pessoa_listar',
            'descricao'=>'Listar Pessoas'
        ]);
    }

    if(!Permissao::where('nome','=','pessoa_adicionar')->count()) {
        Permissao::create([
            'nome'=>'pessoa_adicionar',
            'descricao'=>'Adicionar Pessoas'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','pessoa_adicionar')->first();
        $permissao->update([
            'nome'=>'pessoa_adicionar',
            'descricao'=>'Adicionar Pessoas'
        ]);
    }

    if(!Permissao::where('nome','=','pessoa_editar')->count()) {
        Permissao::create([
            'nome'=>'pessoa_editar',
            'descricao'=>'Editar Pessoas'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','pessoa_editar')->first();
        $permissao->update([
            'nome'=>'pessoa_editar',
            'descricao'=>'Editar Pessoas'
        ]);
    }

    if(!Permissao::where('nome','=','pessoa_deletar')->count()) {
        Permissao::create([
            'nome'=>'pessoa_deletar',
            'descricao'=>'Deletar Pessoas'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','pessoa_deletar')->first();
        $permissao->update([
           'nome'=>'pessoa_deletar',
           'descricao'=>'Deletar Pessoas'
        ]);
    }
            // 14/04/2020  fim permissões para pessoas

        // 14/04/2020  permissões para cidades
        if(!Permissao::where('nome','=','cidade_listar')->count()) {
            Permissao::create([
                'nome'=>'cidade_listar',
                'descricao'=>'Listar Cidades'
            ]);
        }else{
            $permissao = Permissao::where('nome','=','cidade_listar')->first();
            $permissao->update([
                'nome'=>'cidade_listar',
                'descricao'=>'Listar Cidades'
            ]);
        }

        if(!Permissao::where('nome','=','cidades_adicionar')->count()) {
            Permissao::create([
                'nome'=>'cidade_adicionar',
                'descricao'=>'Adicionar Cidades'
            ]);
        }else{
            $permissao = Permissao::where('nome','=','cidade_adicionar')->first();
            $permissao->update([
                'nome'=>'cidade_adicionar',
                'descricao'=>'Adicionar Cidades'
            ]);
        }

        if(!Permissao::where('nome','=','cidade_editar')->count()) {
            Permissao::create([
                'nome'=>'cidade_editar',
                'descricao'=>'Editar Cidades'
            ]);
        }else{
            $permissao = Permissao::where('nome','=','cidade_editar')->first();
            $permissao->update([
                'nome'=>'cidade_editar',
                'descricao'=>'Editar Cidades'
            ]);
        }

        if(!Permissao::where('nome','=','cidade_deletar')->count()) {
            Permissao::create([
                'nome'=>'cidade_deletar',
                'descricao'=>'Deletar Cidades'
            ]);
        }else{
            $permissao = Permissao::where('nome','=','cidade_deletar')->first();
            $permissao->update([
            'nome'=>'cidade_deletar',
            'descricao'=>'Deletar Cidades'
            ]);
        }
                // 14/04/2020  fim permissões para cidades


            // 26/07/2020  permissões para importar modulo Compliance
            if(!Permissao::where('nome','=','compliance_listar_importacoes')->count()) {
                Permissao::create([
                    'nome'=>'compliance_listar_importacoes',
                    'descricao'=>'Compliance listar importacoes'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','compliance_listar_importacoes')->first();
                $permissao->update([
                    'nome'=>'compliance_listar_importacoes',
                    'descricao'=>'Compliance listar importacoes'
                ]);
            }

            // adicionar demais  restrições conforme a necessidade

            // 26/07/2020  permissões para grupo de verificação

            if(!Permissao::where('nome','=','grupoverificacao_listar')->count()) {
                Permissao::create([
                    'nome'=>'grupoverificacao_listar',
                    'descricao'=>'Listar Grupo de verificação'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','grupoverificacao_listar')->first();
                $permissao->update([
                    'nome'=>'grupoverificacao_listar',
                    'descricao'=>'Listar Grupo de verificação'
                ]);
            }

            if(!Permissao::where('nome','=','grupoverificacao_adicionar')->count()) {
                Permissao::create([
                    'nome'=>'grupoverificacao_adicionar',
                    'descricao'=>'Adicionar Grupo de verificação'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','grupoverificacao_adicionar')->first();
                $permissao->update([
                    'nome'=>'grupoverificacao_adicionar',
                    'descricao'=>'Adicionar Grupo de verificação'
                ]);
            }

            if(!Permissao::where('nome','=','grupoverificacao_editar')->count()) {
                Permissao::create([
                    'nome'=>'grupoverificacao_editar',
                    'descricao'=>'Editar Grupo de verificação'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','grupoverificacao_editar')->first();
                $permissao->update([
                    'nome'=>'grupoverificacao_editar',
                    'descricao'=>'Editar Grupo de verificação'
                ]);
            }

            if(!Permissao::where('nome','=','grupoverificacao_deletar')->count()) {
                Permissao::create([
                    'nome'=>'grupoverificacao_deletar',
                    'descricao'=>'Excluir Grupo de verificação'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','grupoverificacao_deletar')->first();
                $permissao->update([
                    'nome'=>'grupoverificacao_deletar',
                    'descricao'=>'Excluir Grupo de verificação'
                ]);
            }


            // 26/07/2020  permissões Compliance para relato modelos

            if(!Permissao::where('nome','=','relato_listar')->count()) {
                Permissao::create([
                    'nome'=>'relato_listar',
                    'descricao'=>'Listar Relato'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','relato_listar')->first();
                $permissao->update([
                    'nome'=>'relato_listar',
                    'descricao'=>'Listar Relato'
                ]);
            }

            if(!Permissao::where('nome','=','relato_adicionar')->count()) {
                Permissao::create([
                    'nome'=>'relato_adicionar',
                    'descricao'=>'Adicionar Relato'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','relato_adicionar')->first();
                $permissao->update([
                    'nome'=>'relato_adicionar',
                    'descricao'=>'Adicionar Relato'
                ]);
            }

            if(!Permissao::where('nome','=','relato_editar')->count()) {
                Permissao::create([
                    'nome'=>'relato_editar',
                    'descricao'=>'Editar Relato'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','relato_editar')->first();
                $permissao->update([
                    'nome'=>'relato_editar',
                    'descricao'=>'Editar Relato'
                ]);
            }

            if(!Permissao::where('nome','=','relato_deletar')->count()) {
                Permissao::create([
                    'nome'=>'relato_deletar',
                    'descricao'=>'Excluir Relato'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','relato_deletar')->first();
                $permissao->update([
                    'nome'=>'relato_deletar',
                    'descricao'=>'Excluir Relato'
                ]);
            }

            // 26/07/2020  permissões Compliance para unidades

            if(!Permissao::where('nome','=','unidade_listar')->count()) {
                Permissao::create([
                    'nome'=>'unidade_listar',
                    'descricao'=>'Listar Unidade'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','unidade_listar')->first();
                $permissao->update([
                    'nome'=>'unidade_listar',
                    'descricao'=>'Listar Unidade'
                ]);
            }

            if(!Permissao::where('nome','=','unidade_adicionar')->count()) {
                Permissao::create([
                    'nome'=>'unidade_adicionar',
                    'descricao'=>'Adicionar Unidade'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','unidade_adicionar')->first();
                $permissao->update([
                    'nome'=>'unidade_adicionar',
                    'descricao'=>'Adicionar Unidade'
                ]);
            }

            if(!Permissao::where('nome','=','unidade_editar')->count()) {
                Permissao::create([
                    'nome'=>'unidade_editar',
                    'descricao'=>'Editar Unidade'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','unidade_editar')->first();
                $permissao->update([
                    'nome'=>'unidade_editar',
                    'descricao'=>'Editar Unidade'
                ]);
            }

            if(!Permissao::where('nome','=','unidade_deletar')->count()) {
                Permissao::create([
                    'nome'=>'unidade_deletar',
                    'descricao'=>'Excluir Unidade'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','unidade_deletar')->first();
                $permissao->update([
                    'nome'=>'unidade_deletar',
                    'descricao'=>'Excluir Unidade'
                ]);
            }

            // 26/07/2020  permissões Compliance para unidades

            if(!Permissao::where('nome','=','inspecao_listar')->count()) {
                Permissao::create([
                    'nome'=>'inspecao_listar',
                    'descricao'=>'Listar Inspeção'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','inspecao_listar')->first();
                $permissao->update([
                    'nome'=>'inspecao_listar',
                    'descricao'=>'Listar Inspeção'
                ]);
            }

            if(!Permissao::where('nome','=','inspecao_adicionar')->count()) {
                Permissao::create([
                    'nome'=>'inspecao_adicionar',
                    'descricao'=>'Adicionar Inspeção'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','inspecao_adicionar')->first();
                $permissao->update([
                    'nome'=>'inspecao_adicionar',
                    'descricao'=>'Adicionar Inspeção'
                ]);
            }

            if(!Permissao::where('nome','=','inspecao_editar')->count()) {
                Permissao::create([
                    'nome'=>'inspecao_editar',
                    'descricao'=>'Editar Inspeção'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','inspecao_editar')->first();
                $permissao->update([
                    'nome'=>'inspecao_editar',
                    'descricao'=>'Editar Inspeção'
                ]);
            }

            if(!Permissao::where('nome','=','inspecao_deletar')->count()) {
                Permissao::create([
                    'nome'=>'inspecao_deletar',
                    'descricao'=>'Excluir Inspeção'
                ]);
            }else{
                $permissao = Permissao::where('nome','=','inspecao_deletar')->first();
                $permissao->update([
                    'nome'=>'inspecao_deletar',
                    'descricao'=>'Excluir Inspeção'
                ]);
            }

    // 16/10/2020  permissões Compliance para Concessionarias; falta completar o ciclo

    if(!Permissao::where('nome','=','concessionarias_listar')->count()) {
        Permissao::create([
            'nome'=>'concessionarias_listar',
            'descricao'=>'Listar Concessionarias'
        ]);
    }else{
        $permissao = Permissao::where('nome','=','concessionarias_listar')->first();
        $permissao->update([
            'nome'=>'concessionarias_listar',
            'descricao'=>'Listar Concessionarias'
        ]);
    }


                echo "Permissões geradas com sucesso!\n";
    }
}
