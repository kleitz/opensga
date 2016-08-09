<?php


/**
 * Class AniversariosTask
 *
 * @property User $User
 */
class MainPermissionTask extends AppShell
{

    //@formatter:off
    public $uses = ['User', 'UnidadeOrganica'];

    public function execute()
    {
        $userId = $this->params['userId'];

        if ($this->User->isFuncionario($userId)) {
            $this->funcionarios($userId);
        }
        if ($this->User->isDocente($userId)) {
            $this->docentes($userId);
        }
        if ($this->User->isAluno($userId)) {
            $this->estudantes($userId);
        }

    }

    public function funcionarios($userId)
    {
        //Pegamos todos funcionarios
        $this->User->Funcionario->contain(['User']);
        if ($userId) {
            $funcionarios = $this->User->Funcionario->find('all', ['conditions' => ['User.id' => $userId]]);
        } else {

            $funcionarios = $this->User->Funcionario->find('all', [
                'conditions' => [
                    'User.ultimo_login is not
                null'
                ]
            ]);
        }


        foreach ($funcionarios as $funcionario) {
            $this->out($funcionario['User']['username']);
            $path = $this->UnidadeOrganica->getPath($funcionario['Funcionario']['unidade_organica_id'],
                ['id', 'codigo']);
            $unidadesOrganicas = Hash::extract($path, '{n}.UnidadeOrganica.codigo');

            $comandos = [];


            //Permissoes para funcionarios da faculdade
            if ($this->User->isFromFaculdade($funcionario['User']['id'])) {

                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/autocomplete";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/index";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/mostrar_foto";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/enviar_sms";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/exportar_alunos_autenticidades";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/pesquisar_candidato";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/atribuir_bolsa_candidato";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/print_bolsas_novo_ingresso";

                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/BolsaBolsas/exportar_bolseiros_ano_ingresso";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/BolsaBolsas/exportar_bolseiros_ano_ingresso_escolas";

                $comandos[] = "acl grant User.{$funcionario['User']['id']}  controllers/Messages/index";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Messages/enviar_mensagem";
                $comandos[] = "acl grant User.{$funcionario['User']['id']}  controllers/Messages/ver_mensagem";


                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/login";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/logout";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/faculdade_logout";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/trocar_senha";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/mostrar_foto";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/matricula_novo_ingresso_sucesso";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/matricular_candidato";

                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/print_declaracao_nivel";

                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/faculdade_adicionar_estudante";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/faculdade_mostrar_foto";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/faculdade_pesquisa_candidatos_action";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/faculdade_matricular_candidato";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/faculdade_matricula_novo_ingresso_sucesso";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/faculdade_alunos_sem_plano_estudo";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/faculdade_atribuir_plano_estudo";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/faculdade_alterar_status";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/faculdade_pesquisa_aluno_externo_action";

                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Avaliacaos/faculdade_ver_avaliacao";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Avaliacaos/faculdade_editar_notas_avaliacao";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Avaliacaos/faculdade_publicar_avaliacao";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Avaliacaos/faculdade_print_pauta_avaliacao";

                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Configs/faculdade_manutencao";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/faculdade_index";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/faculdade_ver_curso";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/faculdade_adicionar_curso";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/faculdade_editar_curso";

                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/faculdade_index";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/faculdade_ver_disciplina";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/faculdade_adicionar_disciplina";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/faculdade_editar_disciplina";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/faculdade_desactivar_disciplina";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/faculdade_remover_disciplina";

                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Docentes/faculdade_index";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Docentes/faculdade_adicionar_docente";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Docentes/faculdade_perfil_docente";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Docentes/faculdade_editar_docente";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Docentes/faculdade_print_lista_docentes";


                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/faculdade_anular_inscricao";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/faculdade_apagar_inscricao";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/faculdade_index";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/faculdade_inscrever";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/faculdade_inscrever_todos_planos_estudos";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/faculdade_inscrever_aluno_externo";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/faculdade_valida_inscricao";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/faculdade_ver_inscricoes_aluno";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/faculdade_adicionar_cadeiras_inscricao";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/faculdade_adicionar_cadeiras_inscricao_todos_planos";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/faculdade_print_comprovativo_inscricao";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/faculdade_ver_detalhes_inscricao";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/faculdade_relatorios";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/faculdade_relatorio_inscricoes_semestre";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/faculdade_relatorio_inscricoes_por_cadeira";

                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/faculdade_renovar_matricula";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/faculdade_print_comprovativo_matricula";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/faculdade_print_boletim_matricula";

                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Pages/faculdade_home";

                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/faculdade_index";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/faculdade_adicionar_plano_estudo";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/faculdade_editar_plano_estudo";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/faculdade_remover_plano_estudo";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/faculdade_desactivar_plano_estudo";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/faculdade_ver_plano_estudo";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/faculdade_adicionar_disciplinas";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/faculdade_adicionar_precedencias";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/faculdade_editar_disciplina";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/faculdade_remover_disciplina";

                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/faculdade_logout";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/faculdade_trocar_senha";

                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_adicionar_assistente";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_criar_avaliacao";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_criar_turma";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_fechar_turma";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_importar_pauta";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_index";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_print_pauta";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_ver_turma";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_print_lista_estudantes";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_adicionar_docente";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_get_aluno_for_inscricao";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_inscrever_aluno";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_actualizar_notas";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_manutencao";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_manutencao_turmas_sem_docente";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_manutencao_turmas_abertas";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_manutencao_turmas_sem_estudante";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/faculdade_migrar_estudantes";

                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/faculdade_index";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/faculdade_perfil_estudante";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/faculdade_pesquisa_aluno_action";

                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/faculdade_changeLoginProfile";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/faculdade_mostrar_foto";
                $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/faculdade_alterar_senha_sistema";
            } else {


                /*
                 * Permissoes comuns para todos Funcionarios da DRA
                 */


                if (in_array('DRA', $unidadesOrganicas)) {


                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/autocomplete";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/mostrar_foto";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/enviar_sms";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/exportar_alunos_autenticidades";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/pesquisar_candidato";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/atribuir_bolsa_candidato";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/print_bolsas_novo_ingresso";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/relatorios_estudantes_com_necessidades_especiais";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/BolsaBolsas/exportar_bolseiros_ano_ingresso";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/BolsaBolsas/exportar_bolseiros_ano_ingresso_escolas";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']}  controllers/Messages/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Messages/enviar_mensagem";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']}  controllers/Messages/ver_mensagem";


                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/login";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/logout";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/faculdade_logout";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/trocar_senha";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/mostrar_foto";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/matricula_novo_ingresso_sucesso";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/matricular_candidato";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/print_declaracao_nivel";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/pesquisa_aluno_action";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/matriculados_pela_faculdade";


                    /*Todas as funcoes das faculdades deviam estar acessiveis para a DRA */
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Avaliacaos/ver_avaliacao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Avaliacaos/editar_notas_avaliacao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Avaliacaos/publicar_avaliacao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Avaliacaos/print_pauta_avaliacao";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Configs/manutencao";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/ver_curso";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/adicionar_curso";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/editar_curso";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/ver_disciplina";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/adicionar_disciplina";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/editar_disciplina";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/desactivar_disciplina";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/remover_disciplina";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Docentes/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Docentes/adicionar_docente";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Docentes/perfil_docente";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Docentes/editar_docente";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/HistoricoCursos/print_certificado_conclusao";


                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/anular_inscricao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/inscrever";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/inscrever2";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/valida_inscricao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/ver_inscricoes_aluno";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/adicionar_cadeiras_inscricao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/print_comprovativo_inscricao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/ver_detalhes_inscricao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/manutencao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/manutencao_inscricoes_abertas";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/relatorios";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/relatorio_inscricoes_semestre";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Pages/faculdade_home";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/faculdade_index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/faculdade_adicionar_plano_estudo";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/faculdade_editar_plano_estudo";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/faculdade_ver_plano_estudo";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/faculdade_adicionar_disciplinas";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/faculdade_adicionar_precedencias";


                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/adicionar_estudante";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/importar_notas_historico";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/criar_avaliacao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/fechar_turma";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/importar_pauta";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/print_pauta";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/ver_turma";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/print_lista_estudantes";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/adicionar_docente";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/inscrever_aluno";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/get_aluno_for_inscricao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/actualizar_notas";


                    /*Fim de todas as funcoes das faculdades */


                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/manutencao_cursos_sem_encarregado";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/manutencao_cursos_sem_turno";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/print_todos_cursos";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Funcionarios/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Funcionarios/perfil_funcionario";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Messages/sms_index";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Pages/home";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/trocar_senha";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/changeLoginProfile";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Requisicoes/RequisicoesPedidos/entregar_cartao_novo_ingresso";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Requisicoes/RequisicoesPedidos/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Requisicoes/RequisicoesPedidos/actualizar_estado_requisicao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Requisicoes/RequisicoesPedidos/nova_requisicao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Requisicoes/RequisicoesPedidos/registar_requisicao_estudante";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Requisicoes/RequisicoesPedidos/registar_requisicao_entidade";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Requisicoes/RequisicoesPedidos/registar_requisicao_nova_entidade";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/report_matriculados_ano";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/report_matriculados_curso";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/relatorios";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/relatorios_matriculados_por_ano";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Logs/admin_index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Logs/admin_view";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Logs/admin_delete";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/AlumniAlumnis/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/AlumniAlumnis/perfil_alumni";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/AlumniAlumnis/editar_perfil_alumni";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/manutencao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/pesquisa_aluno_action";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/report_estudantes_sem_certificado";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/report_estudantes_sem_smo";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/report_estudantes_fora_tempo_estudos";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/alterar_nome";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/mudanca_curso";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/concluir_nivel";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/alterar_status";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/adicionar_estudante";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/editar_estudante";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/exportar_alunos";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/exportar_alunos_excel";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/alterar_nome";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/mudanca_curso";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/concluir_nivel";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/perfil_estudante";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/relatorios";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/print_comprovativo_matricula";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/print_comprovativo_mudanca_curso";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/relatorios_estudantes_estrangeiros";


                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/AlumniCandidatoAlumnis/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/AlumniCandidatoAlumnis/perfil_candidato_alumni";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/AlumniCandidatoAlumnis/editar_candidato_alumni";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/AlumniCandidatoAlumnis/aprovar_candidatura_alumni";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/AlumniCandidatoAlumnis/cancelar_candidatura_alumni";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Messages/sms_index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Messages/send_mass_sms";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/CandidatoGraduacaos/ver_detalhes";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/CandidatoGraduacaos/confirmar_dados";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/CandidatoGraduacaos/confirmar_pagamento";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/CandidatoGraduacaos/rejeitar_candidatura";


                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Candidaturas/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Candidaturas/print_lista_admitidos";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Candidaturas/print_boletim_matriculas";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Candidaturas/candidatos_admitidos";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Candidaturas/print_lista_admitidos";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/cerimonia_graduacao";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/CerimoniaGraduacaos/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/CerimoniaGraduacaos/ver_detalhes";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/CerimoniaGraduacaos/print_lista_graduandos";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/CerimoniaGraduacaos/nova_cerimonia";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/CerimoniaGraduacaos/inscrever_aluno";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/ver_curso";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/manutencao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/manutencao_cursos_sem_encarregado";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/manutencao_cursos_sem_plano_estudos";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/definir_encarregado";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/definir_estado_curso";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/ver_disciplina";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/adicionar_disciplina";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Docentes/relatorios";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Docentes/relatorios_docentes_por_faculdade";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/cadastro_notas_historico";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Inscricaos/index";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/criar_turma";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/relatorios";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/relatorios_turmas_abertas";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/ReportManager";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/alterar_senha_sistema";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/alterar_senha_email";


                    //Permissoes temporarias para tempo de matriculas
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/busca_candidato";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/matricular_candidato";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/matricula_novo_ingresso_sucesso";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/print_boletim_matricula";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/print_comprovativo_matricula";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/report_matriculados_ano";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/report_matriculados_curso";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/exportar_extracto_renovacao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/renovar_matricula";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/manutencao_plano_estudos_incompletos";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/ver_plano_estudo";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/adicionar_disciplinas";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/editar_plano_estudo";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/adicionar_precedencias";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/adicionar_plano_estudo";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/editar_disciplina";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/PlanoEstudos/remover_disciplina";


                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/acessos";


                }

                if (in_array('DRA_DI', $unidadesOrganicas)) {
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/index";


                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/alterar_status";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/adicionar_estudante";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/editar_estudante";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/exportar_alunos";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/exportar_alunos_excel";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/alterar_nome";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/mudanca_curso";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/concluir_nivel";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/perfil_estudante";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/AnoLectivos/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/AnoLectivos/novo_ano_lectivo";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/AnoLectivos/definir_ano_lectivo_actual";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/AnoLectivos/definir_semestre_actual";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Candidaturas/importar_candidatos";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Candidaturas/importar_candidatos_2";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Configs/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Configs/geral";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/adicionar_curso";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/DatabaseLogger/Logs";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/FinanceiroTipoPagamentos/index";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Funcionarios/adicionar_funcionario";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Funcionarios/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Funcionarios/alterar_unidade_organica";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/renovar_matricula";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/fazer_reingresso";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/busca_candidato";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/matricular_candidato";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/print_boletim_matricula";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/print_comprovativo_matricula";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/print_comprovativo_mudanca_curso";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/print_comprovativo_renovacao_matricula";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/cerimonia_graduacao";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/CerimoniaGraduacaos/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/CerimoniaGraduacaos/ver_detalhes";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/CerimoniaGraduacaos/print_lista_graduandos";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/CerimoniaGraduacaos/nova_cerimonia";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/adicionar_curso";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cursos/editar_curso";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/adicionar_disciplina";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Disciplinas/editar_disciplina";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Groups/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Groups/novo_grupo";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Groups/editar_grupo";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Groups/remover_grupo";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Groups/ver_grupo";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Logs/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Logs/view";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/carregar_ficheiro_renovacao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/renovacao_matriculas";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Requisicoes/RequisicoesPedidos/cartao_estudantes";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/gerar_turmas";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Turmas/index";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/alterar_senha_sistema";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/alterar_senha_email";
                } elseif (in_array('DRA_DR', $unidadesOrganicas)) {
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/index";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/alterar_status";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/adicionar_estudante";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/editar_estudante";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/exportar_alunos";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/exportar_alunos_excel";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/alterar_nome";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/mudanca_curso";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/concluir_nivel";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/perfil_estudante";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/print_comprovativo_matricula";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/print_comprovativo_mudanca_curso";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Matriculas/print_comprovativo_renovacao_matricula";

                } elseif (in_array('DRA_DR', $unidadesOrganicas)) {
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/index";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/perfil_estudante";
                } elseif (in_array('GC', $unidadesOrganicas)) {
                    //Funcionarios do Gabinete de Cooperacao

                    $comandos[] = "acl deny User.{$funcionario['User']['id']} controllers";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cooperacao";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/autocomplete";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/mostrar_foto";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/login";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/logout";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/faculdade_logout";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/enviar_sms";
                } elseif (in_array('CEA', $unidadesOrganicas)) {
                    //Funcionarios do Gabinete de Cooperacao

                    $comandos[] = "acl deny User.{$funcionario['User']['id']} controllers";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Cea";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/autocomplete";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Alunos/mostrar_foto";
                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/login";

                    $comandos[] = "acl grant User.{$funcionario['User']['id']} controllers/Users/logout";

                }

            }


            foreach ($comandos as $comando) {
                $this->out($comando);
                $this->dispatchShell($comando);
            }
        }
    }

    public function docentes($userId)
    {

        $this->User->Entidade->Docente->contain([
            'Entidade' => [
                'User'
            ]
        ]);
        $docentes = $this->User->Entidade->Docente->find('all', ['conditions' => ['Entidade.user_id' => $userId]]);
        $comandos = [];
        foreach ($docentes as $docente) {
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Users/docente_trocar_senha";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Docentes/docente_meu_perfil";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Pages/docente_home";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Users/docente_trocar_senha";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Users/docente_changeLoginProfile";

            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Alunos/docente_index";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Alunos/docente_perfil_estudante";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Alunos/docente_enviar_sms";

            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Avaliacaos/docente_ver_avaliacao";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Avaliacaos/docente_index";

            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Aulas/docente_index";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Aulas/docente_ver_aula";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Aulas/docente_criar_aula";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Aulas/docente_editar_aula";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Aulas/docente_marcar_presenca";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Aulas/docente_cancelar_aula";


            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Docentes/docente_meu_perfil";

            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Messages/docente_index";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Messages/docente_enviar_mensagem";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Messages/docente_ver_mensagem";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Messages/docente_editar_mensagem";

            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Pages/docente_home";

            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Turmas/docente_adicionar_assistente";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Turmas/docente_criar_avaliacao";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Turmas/docente_index";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Turmas/docente_ver_turma";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Turmas/docente_print_pauta";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Turmas/docente_print_lista_estudantes";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Turmas/docente_ver_turma";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Turmas/docente_importar_pauta";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Turmas/docente_fechar_turma";

            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Users/docente_login";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Users/docente_logout";

            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Docentes/docente_meu_perfil";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Pages/docente_home";

            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Users/docente_perfil";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Users/docente_trocar_senha";
            $comandos[] = "acl grant User.{$docente['Entidade']['User']['id']} controllers/Users/docente_changeLoginProfile";
        }

        foreach ($comandos as $comando) {
            $this->out($comando);
            $this->dispatchShell($comando);
        }
    }

    public function estudantes($userId)
    {
        $comandos[] = "acl deny User.{$userId} controllers";
        $comandos[] = "acl grant User.{$userId} controllers/Alunos/estudante_perfil";
        $comandos[] = "acl grant User.{$userId} controllers/Alunos/estudante_editar_perfil";
        $comandos[] = "acl grant User.{$userId} controllers/Alunos/estudante_mostrar_foto";
        $comandos[] = "acl grant User.{$userId} controllers/Alunos/estudante_index";

        $comandos[] = "acl grant User.{$userId} controllers/CerimoniaGraduacaos/estudante_index";
        $comandos[] = "acl grant User.{$userId} controllers/CerimoniaGraduacaos/estudante_inscricao";
        $comandos[] = "acl grant User.{$userId} controllers/CerimoniaGraduacaos/estudante_pagamento_inscricao";
        $comandos[] = "acl grant User.{$userId} controllers/CerimoniaGraduacaos/estudante_ver_detalhes";

        $comandos[] = "acl grant User.{$userId} controllers/Inscricaos/estudante_ver_inscricoes_aluno";
        $comandos[] = "acl grant User.{$userId} controllers/Inscricaos/estudante_index";
        $comandos[] = "acl grant User.{$userId} controllers/Inscricaos/estudante_ver_detalhes_inscricao";
        $comandos[] = "acl grant User.{$userId} controllers/Inscricaos/estudante_inscrever";
        $comandos[] = "acl grant User.{$userId} controllers/Inscricaos/estudante_anular_inscricao";
        $comandos[] = "acl grant User.{$userId} controllers/Inscricaos/estudante_adicionar_cadeiras_inscricao";

        $comandos[] = "acl grant User.{$userId} controllers/Matriculas/estudante_como_renovar";
        $comandos[] = "acl grant User.{$userId} controllers/Matriculas/estudante_index";
        $comandos[] = "acl grant User.{$userId} controllers/Matriculas/estudante_anular_matricula";
        $comandos[] = "acl grant User.{$userId} controllers/Matriculas/estudante_ver_matricula";
        $comandos[] = "acl grant User.{$userId} controllers/Matriculas/estudante_renovar_matricula";

        $comandos[] = "acl grant User.{$userId} controllers/Messages/estudante_index";
        $comandos[] = "acl grant User.{$userId} controllers/Messages/estudante_enviar_mensagem";
        $comandos[] = "acl grant User.{$userId} controllers/Messages/estudante_ver_mensagem";

        $comandos[] = "acl grant User.{$userId} controllers/Pages/estudante_home";

        $comandos[] = "acl grant User.{$userId} controllers/Turmas/estudante_index";
        $comandos[] = "acl grant User.{$userId} controllers/Turmas/estudante_ver_turma";

        $comandos[] = "acl grant User.{$userId} controllers/users/estudante_mostrar_foto";
        $comandos[] = "acl grant User.{$userId} controllers/users/estudante_perfil";
        $comandos[] = "acl grant User.{$userId} controllers/users/estudante_trocar_senha";
        $comandos[] = "acl grant User.{$userId} controllers/users/estudante_after_fb_login";
        $comandos[] = "acl grant User.{$userId} controllers/users/estudante_changeLoginProfile";

        foreach ($comandos as $comando) {
            $this->dispatchShell($comando);
        }
    }

}

?>