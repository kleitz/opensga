<?php
App::uses('CakeTime', 'Utility');
use Ghunti\HighchartsPHP\Highchart;
use Ghunti\HighchartsPHP\HighchartJsExpr;

/**
 * Controller inscrições
 *
 * Controller para o processamento das inscrições dos alunos
 * Executa todos os detalhes relacionados ás inscrições dos alunos no sistema
 * A maior parte das validações e filtragens de dados ocorrem aqui
 *
 *
 * @copyright       Copyright 2010-2011, INFOmoz (Informática-Moçambique) (http://infomoz.net)
 * @link            http://opensga.com OpenSGA  - Sistema de Gestão Académica
 * @author          Elisio Leonardo (elisio.leonardo@gmail.com)
 * @package         opensga
 * @subpackage      opensga.core.inscricoes
 * @since           OpenSGA v 0.1.0
 * @version         OpenSGA v 0.5.0
 *
 *
 * @property Inscricao $Inscricao
 */
class InscricaosController extends AppController
{

    var $name = 'Inscricaos';

    public function cadastro_notas_historico($alunoId)
    {


        if ($this->request->is('post')) {
            $cadastro = $this->Inscricao->CadastraNotasHistorico($this->request->data);
            debug($cadastro);
        }
        $this->Inscricao->Aluno->contain(['Entidade' => ['Genero'], 'PlanoEstudo', 'Curso']);
        $aluno = $this->Inscricao->Aluno->findById($alunoId);

        if ($aluno['Aluno']['plano_estudo_id'] == null) {
            $this->Flash->error('Este Aluno não possui um Plano de Estudos Atribuido, atribua primeiro!');
            $this->redirect(['controller' => 'alunos', 'action' => 'perfil_estudante', $aluno['Aluno']['id']]);
        }

        $totalCreditos = $this->Inscricao->Aluno->PlanoEstudo->getTotalCreditos($aluno['Aluno']['plano_estudo_id']);
        /* if ($totalCreditos < 180) {
             $this->Flash->error('O Plano de Estudos deste Estudante não possui creditos Suficientes. Por favor adicione mais cadeiras ou Ajuste os creditos');
             $this->redirect([
                 'controller' => 'plano_estudos',
                 'action'     => 'adicionar_disciplinas',
                 $aluno['Aluno']['plano_estudo_id']
             ]);
         }
         */
        $this->Inscricao->Aluno->PlanoEstudo->DisciplinaPlanoEstudo->contain(['Disciplina']);
        $disciplinaPlanoEstudos = $this->Inscricao->Aluno->PlanoEstudo->DisciplinaPlanoEstudo->find('all',
            [
                'conditions' => ['DisciplinaPlanoEstudo.plano_estudo_id' => $aluno['Aluno']['plano_estudo_id']],
                'order' => [
                    'DisciplinaPlanoEstudo.ano_curricular',
                    'DisciplinaPlanoEstudo.semestre_curricular',
                ],
            ]);

        foreach ($disciplinaPlanoEstudos as $k => $disciplinaPlanoEstudo) {
            $options['joins'] = [
                [
                    'table' => 'turmas',
                    'alias' => 'Turma',
                    'type' => 'inner',
                    'conditions' => [
                        'Inscricao.turma_id = Turma.id',
                    ],
                ],
                [
                    'table' => 'ano_lectivos',
                    'alias' => 'AnoLectivo',
                    'type' => 'inner',
                    'conditions' => [
                        'Turma.ano_lectivo_id = AnoLectivo.id',
                    ],
                ],
            ];

            $options['conditions'] = [
                'Inscricao.aluno_id' => $aluno['Aluno']['id'],
                'Turma.disciplina_id' => $disciplinaPlanoEstudo['DisciplinaPlanoEstudo']['disciplina_id'],
                'Turma.plano_estudo_id' => $aluno['Aluno']['plano_estudo_id'],
            ];
            $options['order'] = 'AnoLectivo.ano ASC';
            $options['fields'] = '*';

            $inscricaos = $this->Inscricao->find('all', $options);


            if ($inscricaos) {
                $disciplinaPlanoEstudos[$k]['Nota'] = $inscricaos;

            }
        }

        $historicoCurso = $this->Inscricao->Aluno->HistoricoCurso->find('first', [
            'conditions' => [
                'aluno_id' => $aluno['Aluno']['id'],
                'curso_id' => $aluno['Aluno']['curso_id'],
                'ano_ingresso' => $aluno['Aluno']['ano_ingresso'],
            ],
        ]);
        if (empty($historicoCurso)) {
            throw new \Cake\Network\Exception\BadRequestException('Criar Historico Urgente' . $alunoId);
        }

        $anoLectivoConditions = ['AnoLectivo.ano >=' => $aluno['Aluno']['ano_ingresso']];
        if ($historicoCurso['HistoricoCurso']['ano_fim'] != null) {
            $anoLectivoConditions['AnoLectivo.ano <='] = $historicoCurso['HistoricoCurso']['ano_fim'];
        }

        $anoLectivos = $this->Inscricao->Matricula->AnoLectivo->find('list',
            ['conditions' => $anoLectivoConditions, 'order' => 'AnoLectivo.ano ASC', 'fields' => ['ano', 'ano']]);


        $this->set(compact('aluno', 'disciplinaPlanoEstudos', 'anoLectivos'));

    }

    public function docente_cadastro_notas_historico($alunoId)
    {

    }

    /**
     * @todo Implementar a funcao
     */
    public function estudante_anular_inscricao()
    {

    }

    /**
     * @todo Implementar a funcao
     */
    public function estudante_index()
    {
        $this->redirect(['action' => 'ver_inscricoes_aluno']);

    }

    /**
     * @todo Implementar a funcao
     */
    public function estudante_inscrever()
    {

    }

    public function estudante_relatorios()
    {

    }

    /**
     * @fixme Nao eh seguro, todos funcionarios podem aceder, mesmo nao sendo da faculdade
     *
     * @param null $inscricaoId
     */
    function estudante_ver_detalhes_inscricao($inscricaoId = null)
    {

        if (!$inscricaoId) {
            $this->Session->setFlash('Invalido %s', 'flasherror');
            $this->redirect(['action' => 'index']);
        }

        $aluno = $this->Inscricao->Aluno->getByUserId(CakeSession::read('Auth.User.id'));
        $aluno = $this->Inscricao->Aluno->getAlunoForAction($aluno['Aluno']['id']);
        $this->Inscricao->contain([
            'Aluno' => ['Entidade'],
            'Turma' => ['Curso'],
            'EstadoInscricao',
        ]);
        $inscricao = $this->Inscricao->findById($inscricaoId);
        if (!empty($inscricao)) {
            if ($inscricao['Inscricao']['aluno_id'] != $aluno['Aluno']['id']) {
                $this->Flash->error('Não tem permissão para aceder a pagina anterior');
                $this->redirect($this->referer(['controller' => 'pages', 'action' => 'home']));
            }
        }


        if ($this->request->is('post') || $this->request->is('put')) {


            if ($this->Inscricao->actualizaDadosInscricao($this->request->data) === true) {
                $this->Flash->success('Dados da Inscricao Actualizados com Sucesso');
                $this->redirect([
                    'controller' => 'inscricaos',
                    'action' => 'ver_inscricoes_aluno',
                    $aluno['Aluno']['id'],
                ]);
            } else {
                $this->Flash->error('Problemas ao Actualizar a Inscricao. Verifique os dados e tente novamente');
            }
        }


        $this->request->data = $inscricao;

        $tipoInscricaos = $this->Inscricao->TipoInscricao->find('list');
        $estadoInscricaos = $this->Inscricao->EstadoInscricao->find('list');


        $this->set(compact('inscricao', 'aluno', 'tipoInscricaos', 'estadoInscricaos'));
    }

    /**
     *
     *
     *
     * @fixme esta matricula nao eh consistente para casos em que aluno muda de curso,etc
     */
    public function estudante_ver_inscricoes_aluno()
    {
        $userId = $this->Session->read('Auth.User.id');
        $this->Inscricao->Aluno->contain([
            'Entidade' => ['User'],
            'PlanoEstudo',
            'Curso' => ['UnidadeOrganica'],
        ]);
        $aluno = $this->Inscricao->Aluno->find('first', ['conditions' => ['Entidade.user_id' => $userId]]);
        if (empty($aluno)) {
            throw new NotFoundException('Este aluno não existe no Sistema');
        }
        //Pegamos todas inscricoes activas
        $this->Inscricao->contain([
            'Turma' => [
                'Disciplina',
                'AnoLectivo',
            ],
            'TipoInscricao',
        ]);
        $inscricoesActivas = $this->Inscricao->find('all',
            [
                'conditions' => [
                    'Inscricao.estado_inscricao_id' => $this->Inscricao->estadoInscricoesAbertas,
                    'Inscricao.aluno_id' => $aluno['Aluno']['id'],
                    'Turma.ano_lectivo_id' => Configure::read('OpenSGA.ano_lectivo_id'),
                    'Turma.semestre_lectivo_id' => Configure::read('OpenSGA.semestre_lectivo_id'),
                ],
                'order' => 'Inscricao.id DESC',
            ]);

        $this->Inscricao->contain([
            'Turma' => [
                'Disciplina',
                'AnoLectivo',
            ],
            'TipoInscricao',
        ]);
        $inscricoesAbertas = $this->Inscricao->find('all',
            [
                'conditions' => [
                    'Inscricao.estado_inscricao_id' => [1, 2, 3],
                    'Inscricao.aluno_id' => $aluno['Aluno']['id'],
                    'OR' => [
                        [
                            'Turma.ano_lectivo_id NOT' => Configure::read('OpenSGA.ano_lectivo_id'),
                            'Turma.semestre_lectivo_id NOT' => Configure::read('OpenSGA.semestre_lectivo_id'),
                        ],
                        [
                            'Turma.ano_lectivo_id' => Configure::read('OpenSGA.ano_lectivo_id'),
                            'Turma.semestre_lectivo_id NOT' => Configure::read('OpenSGA.semestre_lectivo_id'),
                        ],


                    ],

                ],
                'order' => 'Inscricao.id DESC',
            ]);

        $this->Inscricao->contain([
            'Turma' => [
                'Disciplina',
                'AnoLectivo',
            ],
            'TipoInscricao',
        ]);
        $cadeirasFeitas = $this->Inscricao->find('all',
            [
                'conditions' => [
                    'Inscricao.estado_inscricao_id' => [4, 5, 6, 13],
                    'Inscricao.aluno_id' => $aluno['Aluno']['id'],
                ],
                'order' => 'Inscricao.id DESC',
            ]);
        $anoLectivo = $this->Inscricao->Matricula->AnoLectivo->findByAno(Configure::read('OpenSGA.ano_lectivo'));

        $matricula = $this->Inscricao->Matricula->findByAlunoIdAndCursoIdAndAnoLectivoId($aluno['Aluno']['id'],
            $aluno['Aluno']['curso_id'], $anoLectivo['AnoLectivo']['id']);


        $cadeirasPendentes = $this->Inscricao->getAllCadeirasPendentesByAluno($aluno['Aluno']['id']);
        $isRegular = $this->Inscricao->Aluno->isRegular($aluno['Aluno']['id']);


        if (count($isRegular) == 1 && $isRegular[0]['regular'] == true) {
            if ($isRegular[0]['estado'] == 1) {
                $classeEstado = "alert alert-info";
            } else {
                $classeEstado = "alert alert-success";
            }
        } else {
            $classeEstado = "alert alert-danger";
        }
        $this->set(compact('inscricoesActivas', 'cadeirasPendentes', 'aluno', 'matricula', 'isRegular',
            'classeEstado', 'inscricoesAbertas', 'cadeirasFeitas'));
    }

    /**
     * Adiciona novas cadeiras a um aluno que ja fez inscricao num dado semestre, e recalcula as mensalidades
     *
     * @param type $aluno_id
     * @param type $matricula_id
     *
     * @todo Implementar essa cena :(
     */
    public function faculdade_adicionar_cadeiras_inscricao($alunoId, $matriculaId)
    {
        $this->loadModel('Turma');
        $this->loadModel('Aluno');
        $this->loadModel('FinanceiroPagamento');
        $this->loadModel('Matricula');

        $unidadeOrganicaId = $this->Session->read('Auth.User.unidade_organica_id');

        $this->Inscricao->Aluno->contain([
            'Entidade',
            'PlanoEstudo',
            'Curso' => ['UnidadeOrganica'],
        ]);
        $aluno = $this->Inscricao->Aluno->findById($alunoId);

        $isExterno = 1;
        $planoEstudoId = $this->request->query('planoEstudoId');
        if (empty($planoEstudoId)) {
            if ($aluno['Curso']['unidade_organica_id'] != $unidadeOrganicaId) {
                $this->Session->setFlash(__('Nao Possui permissao para aceder a pagina anterior2'));
                $this->redirect($this->referer());
            }
            $isExterno = null;
        }


        if ($this->request->is('post') || $this->request->is('put')) {

            $alunoId = $this->request->data['Inscricao']['aluno_id'];
            $matriculaId = $this->request->data['Inscricao']['matricula_id'];
            $inscricao_nova = [];

            if (isset($this->request->data['disciplinas'])) {
                foreach ($this->request->data['disciplinas'] as $k => $v) {
                    if ($v > 0) {
                        $inscricao_nova[] = $v;
                    }
                }
            }

            $this->Session->write('OpenSGA.inscricao.cadeiras', $inscricao_nova);
            $this->Session->write('OpenSGA.inscricao.matricula_id', $matriculaId);
            $this->Session->write('OpenSGA.inscricao.aluno_id', $alunoId);
            $this->redirect([
                'controller' => 'inscricaos',
                'action' => 'valida_inscricao',
                '?' => ['planoEstudoId' => $planoEstudoId, 'isExterno' => $isExterno],
            ]);
        }


        if (empty($planoEstudoId)) {
            $disciplinas = $this->Inscricao->getAllDisciplinasForInscricao($alunoId);
        } else {
            $disciplinas = $this->Inscricao->getAllDisciplinasForInscricaoByPlanoEstudo($alunoId, $planoEstudoId);
        }

        $this->set('aluno_id', $alunoId);
        $this->set('matricula_id', $matriculaId);
        $this->set(compact('disciplinas', 'planoEstudoId'));
    }

    /**
     * Adiciona novas cadeiras a um aluno que ja fez inscricao num dado semestre, e recalcula as mensalidades
     *
     * @param type $aluno_id
     * @param type $matricula_id
     *
     * @todo Implementar essa cena :(
     */
    public function faculdade_adicionar_cadeiras_inscricao_todos_planos($alunoId, $matriculaId)
    {
        $this->loadModel('Turma');
        $this->loadModel('Aluno');
        $this->loadModel('FinanceiroPagamento');
        $this->loadModel('Matricula');

        $unidadeOrganicaId = $this->Session->read('Auth.User.unidade_organica_id');

        $this->Inscricao->Aluno->contain([
            'Entidade',
            'PlanoEstudo',
            'Curso' => ['UnidadeOrganica'],
        ]);
        $aluno = $this->Inscricao->Aluno->findById($alunoId);

        $isExterno = 1;
        $planoEstudoId = $this->request->query('planoEstudoId');
        if (empty($planoEstudoId)) {
            if ($aluno['Curso']['unidade_organica_id'] != $unidadeOrganicaId) {
                $this->Session->setFlash(__('Nao Possui permissao para aceder a pagina anterior2'));
                $this->redirect($this->referer());
            }
            $isExterno = null;
        }


        if ($this->request->is('post') || $this->request->is('put')) {

            $alunoId = $this->request->data['Inscricao']['aluno_id'];
            $matriculaId = $this->request->data['Inscricao']['matricula_id'];
            $inscricao_nova = [];

            if (isset($this->request->data['disciplinas'])) {
                foreach ($this->request->data['disciplinas'] as $k => $v) {
                    if ($v > 0) {
                        $inscricao_nova[] = $v;
                    }
                }
            }

            $this->Session->write('OpenSGA.inscricao.cadeiras', $inscricao_nova);
            $this->Session->write('OpenSGA.inscricao.matricula_id', $matriculaId);
            $this->Session->write('OpenSGA.inscricao.aluno_id', $alunoId);
            $this->redirect([
                'controller' => 'inscricaos',
                'action' => 'valida_inscricao',
                '?' => ['planoEstudoId' => $planoEstudoId, 'isExterno' => $isExterno],
            ]);
        }


        $disciplinas = $this->Inscricao->getAllDisciplinasForInscricaoByCurso($alunoId);

        $this->set('aluno_id', $alunoId);
        $this->set('matricula_id', $matriculaId);
        $this->set(compact('disciplinas', 'planoEstudoId'));
        $this->render('faculdade_adicionar_cadeiras_inscricao');
    }

    /**
     *
     * @param type $inscricaoId
     *
     * @throws MethodNotAllowedException
     * @todo Ver a questao de 30 dias para anulacao da Inscricao
     */
    public function faculdade_anular_inscricao($inscricaoId)
    {
        if ($this->request->is('post')) {
            $this->Inscricao->id = $inscricaoId;
            $this->Inscricao->set('estado_inscricao_id', 9);
            $this->Inscricao->save();
            $this->Session->setFlash(__('Inscricao Anulada com Sucesso'), 'default',
                ['class' => 'alert alert-success']);
            $this->redirect($this->referer(['action' => 'index']));
        } else {
            throw new MethodNotAllowedException('Erro no Sistema');
        }
    }

    public function faculdade_apagar_inscricao($inscricao_id)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Inscricao->id = $inscricao_id;
        $inscricao = $this->Inscricao->read();

        if (!$this->Inscricao->exists()) {
            throw new NotFoundException(__('Inscricao Invalida'));
        }
        if (!CakeTime::isToday($inscricao['Inscricao']['data'])) {
            $this->Flash->error('Esta Inscricao não pode ser apagada. Apenas anulada');
            $this->redirect($this->referer(['action' => 'index']));
        }
        if ($this->Inscricao->delete()) {
            $this->Session->setFlash(__('Inscricao Apagada com Sucesso'), 'default', ['class' => 'alert success']);
            $this->redirect([
                'action' => 'ver_inscricoes_aluno',
                $inscricao['Inscricao']['aluno_id'],
                'faculdade' => true,
            ]);
        }
        $this->Session->setFlash(__('Problemas ao apagar a inscricao. Tente novamente'), 'default',
            ['class' => 'alert error']);
        $this->redirect([
            'action' => 'ver_inscricoes_aluno',
            $inscricao['Inscricao']['aluno_id'],
            'faculdade' => true,
        ]);

        debug($this->request->data);
    }

    public function faculdade_cadastro_notas_historico($alunoId)
    {

    }

    function faculdade_editar_inscricao($inscricao_id = null)
    {
        $this->Inscricao->id = $id;
        if (!$this->Inscricao->exists()) {
            throw new NotFoundException('Inscricao Invalida');
        }

        App::Import('Model', 'Turma');
        $turma = new Turma;
        //App::Import('Model','Logmv');
        //$logmv = new Logmv;

        if (!$id && empty($this->data)) {
            $this->Session->setFlash('Invalido %s', 'flasherror');
            $this->redirect(['action' => 'index']);
        }

        if (!empty($this->data)) {
            if ($this->Inscricao->save($this->data)) {
                $this->Session->setFlash('Dado Editados com sucesso', 'flashok');
                $this->redirect(['action' => 'index']);
            } else {
                $this->Session->setFlash('Erro ao editar dados. Por favor tente de novo.', 'flasherror');
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Inscricao->read(null, $id);
            //$logmv->logUpdate(11,$this->Session->read('Auth.User.id'),$id,$this->data["Inscricao"]["Aluno_id"]);
        }

        //var_dump($this->data);
        $alunos = $this->Inscricao->Aluno->find('list');
        $turmas = $this->Inscricao->Turma->find('list');
        $EpocaAvaliacaos = $this->Inscricao->EpocaAvaliacao->find('list');
        //$notafrequencia = $this->data['Inscricao']['notafrequencia'];

        $estadoinscricao = $this->Inscricao->Estadoinscricao->find('list');

        $curso = $turma->getCursoAluno($this->data['Inscricao']['t0010turma_id']);
        $curso1 = $curso[0]['tc']['name'];

        $docente = $turma->getDocente($this->data['Inscricao']['t0010turma_id']);
        $docente1 = $docente[0]['tf']['name'];

        $assistente = $turma->getAssistente($this->data['Inscricao']['t0010turma_id']);
        $assistente1 = $assistente[0]['tf']['name'];

        $plano = $turma->getPlanoEstudo($this->data['Inscricao']['t0010turma_id']);
        $plano1 = $plano[0]['tp']['name'];

        $turma = $turma->getTurma($this->data['Inscricao']['t0010turma_id']);
        $turma1 = $turma[0]['tt']['name'];

        $turno = $turma->getTurno($this->data['Inscricao']['t0010turma_id']);
        $turno1 = $turno[0]['ttu']['name'];

        $anoCurricular = $turma->getAnoCurricular($this->data['Inscricao']['t0010turma_id']);
        $anoCurricular1 = $anoCurricular[0]['tt']['ano_curricular'];

        $semestreCurricular = $turma->getSemestreCurricular($this->data['Inscricao']['t0010turma_id']);
        $semestreCurricular1 = $semestreCurricular[0]['tt']['semestre_curricular'];

        $anoLectivo = $turma->getAnoLectivo($this->data['Inscricao']['t0010turma_id']);
        $anoLectivo1 = $anoLectivo[0]['tal']['codigo'];


        $this->set(compact('Alunos', 't0010turmas', 'EpocaAvaliacaos', 'tg0020estadoinscricao', 'funcionarios',
            'curso1', 'docente1', 'assistente1', 'plano1', 'turma1', 'turno1', 'anoCurricular1',
            'semestreCurricular1',
            'anoLectivo1'));
    }

    public function faculdade_index()
    {

        $unidadeOrganicaId = $this->Session->read('Auth.User.unidade_organica_id');
        $conditions = [];
        // $conditions['Inscricao.estado_inscricao_id'] = [1, 2, 3];
        $this->Inscricao->Turma->contain('Curso');
        $cursosFaculdade = $this->Inscricao->Turma->Curso->find('list', [
            'conditions' => [
                'Curso.unidade_organica_id' => $unidadeOrganicaId,
                //'Curso.estado_objecto_id NOT'=>2
            ],
        ]);
        $turmasFaculdade = $this->Inscricao->Turma->find('list', [
            'conditions' => [
                'Curso.unidade_organica_id' => $unidadeOrganicaId,
                'Turma.estado_turma_id' => 1,

            ],
        ]);
        $turmasFilter = array_keys($turmasFaculdade);
        $conditions['Inscricao.turma_id'] = $turmasFilter;

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->request->data['Inscricao']['curso_id'] != '') {
                $conditions['Turma.curso_id'] = $this->request->data['Inscricao']['curso_id'];
                $turmasFaculdade = $this->Inscricao->Turma->find('list', [
                    'conditions' => [
                        'Turma.curso_id' => $this->request->data['Inscricao']['curso_id'],
                        'Turma.estado_turma_id' => 1,
                    ],
                ]);
            }
            if ($this->request->data['Inscricao']['turma_id'] != '') {
                $conditions['Inscricao.turma_id'] = $this->request->data['Inscricao']['turma_id'];
            }
            if ($this->request->data['Inscricao']['numero_estudante'] != '') {
                $conditions['Aluno.codigo'] = $this->request->data['Inscricao']['numero_estudante'];
            }
        }


        $this->paginate = [
            'conditions' => $conditions,
            'contain' => [
                'Aluno' => [
                    'Entidade',
                ],
                'Turma' => [
                    'Curso',
                    'Disciplina',
                    'AnoLectivo',
                    'SemestreLectivo',
                ],
                'EstadoInscricao',
            ],
            'order' => 'Inscricao.modified Desc',
        ];
        $inscricaos = $this->paginate();
        $this->set(compact('inscricaos', 'turmasFaculdade', 'cursosFaculdade'));
    }

    /**
     * Inscreve alunos depois da primeira matricula
     *
     * @param type $aluno_id
     * @param type $matricula_id
     *
     *
     * @todo garantir que so se inscreve quem nao tem dividas no semestre anterior
     * @todo A lista de disciplinas deve ter em consideracao  a tabela de precedencias
     * @todo Deve existir possibilidade de inscricao condicional
     */
    public function faculdade_inscrever($alunoId, $matriculaId, $planoEstudoId = null, $inscricaoCondicional = null)
    {
        $this->loadModel('Turma');
        $this->loadModel('Aluno');
        $this->loadModel('FinanceiroPagamento');
        $this->loadModel('Matricula');

        $unidadeOrganicaId = $this->Session->read('Auth.User.unidade_organica_id');

        $isRegular = $this->Inscricao->Aluno->isRegular($alunoId);
        if (!$isRegular[0]['regular'] == true) {
            $this->Session->setFlash(__('So pode fazer Inscricoes depois de renovar a matricula para o ano lectivo actual'),
                'default', ['class' => 'alert error']);
            $this->redirect($this->referer());
        }
        $this->Inscricao->Aluno->contain([
            'Entidade',
            'PlanoEstudo',
            'Curso' => ['UnidadeOrganica'],
        ]);

        if (empty($planoEstudoId)) {
            $planoEstudoId = $this->request->query('planoEstudoId');
        }

        $isExterno = 1;

        $aluno = $this->Inscricao->Aluno->findById($alunoId);
        if (empty($planoEstudoId)) {
            if ($aluno['Curso']['unidade_organica_id'] != $unidadeOrganicaId) {
                $this->Session->setFlash(__('Nao Possui permissao para aceder a pagina anterior2'));
                $this->redirect($this->referer());
            }
            $isExterno = null;
        }


        //Primeiro vemos se ainda nao se matriculou no ano lectivo em questao

        $this->Inscricao->contain([
            'Turma',
        ]);
        $inscricoesActivas = $this->Inscricao->find('all', [
            'conditions' => [
                'Inscricao.aluno_id' => $alunoId,
                'Turma.ano_lectivo_id' => Configure::read('OpenSGA.ano_lectivo_id'),
                'Turma.semestre_lectivo_id' => Configure::read('OpenSGA.semestre_lectivo_id'),
            ],
        ]);
        if (!empty($inscricoesActivas)) {
            $this->Session->setFlash(__('Este Aluno já fez inscrições neste ano. As cadeiras seguintes serão adicionadas ás inscrições anteriores, e o valor da inscrição será recalculado'),
                'default', ['class' => 'alert info']);
            $this->redirect([
                'controller' => 'inscricaos',
                'action' => 'adicionar_cadeiras_inscricao',
                $alunoId,
                $matriculaId,
                '?' => ['planoEstudoId' => $planoEstudoId],
            ]);
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $alunoId = $this->request->data['Inscricao']['aluno_id'];
            $matriculaId = $this->request->data['Inscricao']['matricula_id'];
            $inscricaoNova = [];

            foreach ($this->request->data['disciplinas'] as $k => $v) {
                if ($v > 0) {
                    $inscricaoNova[] = $v;
                }
            }


            $this->Session->write('OpenSGA.inscricao.cadeiras', $inscricaoNova);
            $this->Session->write('OpenSGA.inscricao.matricula_id', $matriculaId);
            $this->Session->write('OpenSGA.inscricao.aluno_id', $alunoId);
            $this->redirect([
                'controller' => 'inscricaos',
                'action' => 'valida_inscricao',
                '?' => ['isExterno' => $isExterno, 'planoEstudoId' => $planoEstudoId],
            ]);
        }


        if (empty($planoEstudoId)) {
            $disciplinas = $this->Inscricao->getAllDisciplinasForInscricao($alunoId);
        } else {
            $disciplinas = $this->Inscricao->getAllDisciplinasForInscricaoByPlanoEstudo($alunoId, $planoEstudoId);
        }


        $this->set('aluno_id', $alunoId);
        $this->set('matricula_id', $matriculaId);
        $this->set(compact('disciplinas', 'planoEstudoId'));
    }

    public function faculdade_inscrever2($aluno_id, $matricula_id)
    {
        $unidade_organica_id = $this->Session->read('Auth.User.unidade_organica_id');
        $anoLectivoAno = Configure::read('OpenSGA.ano_lectivo');
        $anoLectivo = $this->Inscricao->Turma->AnoLectivo->findByAno($anoLectivoAno);
        $semestreLectivoSemestre = Configure::read('OpenSGA.semestre_lectivo');
        $semestreLectivo = $this->Inscricao->Turma->SemestreLectivo->findByAnoLectivoIdAndSemestre($anoLectivo['AnoLectivo']['id'],
            $semestreLectivoSemestre);

        $is_regular = $this->Inscricao->Aluno->isRegular($aluno_id);
        if (!$is_regular[0]['regular'] == true) {
            $this->Session->setFlash(__('So pode fazer Inscricoes depois de renovar a matricula para o ano lectivo actual'),
                'default', ['class' => 'alert error']);
            $this->redirect($this->referer());
        }
        $this->Inscricao->Aluno->contain([
            'Entidade',
            'PlanoEstudo',
            'Curso' => ['UnidadeOrganica'],
        ]);
        $aluno = $this->Inscricao->Aluno->findById($aluno_id);

        if ($aluno['Curso']['unidade_organica_id'] != $unidade_organica_id) {
            $this->Session->setFlash(__('Nao Possui permissao para aceder a pagina anterior'));
            $this->redirect($this->referer());
        }

        $matricula = $this->Inscricao->Matricula->findById($matricula_id);

        //Primeiro vemos se ainda nao se inscreveu no ano lectivo em questao

        $this->Inscricao->contain([
            'Turma',
        ]);
        $inscricoes_activas = $this->Inscricao->find('all', [
                'conditions' => [
                    'Inscricao.aluno_id' => $aluno_id,
                    'Turma.ano_lectivo_id' => $anoLectivo['AnoLectivo']['id'],
                    'Turma.semestre_lectivo_id' => $semestreLectivo['SemestreLectivo']['id'],
                ],
            ]
        );
        if (!empty($inscricoes_activas)) {
            $this->Session->setFlash(__('Este Aluno já fez inscrições neste ano. As cadeiras seguintes serão adicionadas ás inscrições anteriores, e o valor da inscrição será recalculado'),
                'default', ['class' => 'alert info']);
            $this->redirect([
                'controller' => 'inscricaos',
                'action' => 'adicionar_cadeiras_inscricao',
                $aluno_id,
                $matricula_id,
            ]);
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $aluno_id = $this->request->data['Inscricao']['aluno_id'];
            $matricula_id = $this->request->data['Inscricao']['matricula_id'];


            foreach ($this->request->data['disciplina'] as $planoEstudoId => $disciplinas) {
                $planoEstudo = $this->Inscricao->Turma->PlanoEstudo->findById($planoEstudoId);
                foreach ($disciplinas as $k => $v) {
                    if ($v > 0) {
                        $turmaExiste = $this->Inscricao->Turma->find('first', [
                            'conditions' => [
                                'disciplina_id' => $v,
                                'ano_lectivo_id' => $anoLectivo['AnoLectivo']['id'],
                                'semestre_lectivo_id' => $semestreLectivo['SemestreLectivo']['id'],
                                'curso_id' => $planoEstudo['PlanoEstudo']['id'],
                                'plano_estudo_id' => $planoEstudo['PlanoEstudo']['id'],
                            ],

                        ]);
                        if (empty($turmaExiste)) {
                            $this->Inscricao->Turma->PlanoEstudo->DisciplinaPlanoEstudo->contain('Disciplina');
                            $disciplinaPlanoEstudo = $this->Inscricao->Turma->PlanoEstudo->DisciplinaPlanoEstudo->find('first',
                                [
                                    'disciplina_id' => $v,
                                    'plano_estudo_id' => $planoEstudoId,
                                ]);

                            $turma = [];

                            $turma['ano_lectivo_id'] = $anoLectivo['AnoLectivo']['id'];
                            $turma['ano_curricular'] = $disciplinaPlanoEstudo['DisciplinaPlanoEstudo']['ano_curricular'];
                            $turma['semestre_curricular'] = $disciplinaPlanoEstudo['DisciplinaPlanoEstudo']['semestre_curricular'];
                            $turma['curso_id'] = $planoEstudo['PlanoEstudo']['curso_id'];
                            $turma['escola_id'] = 1;
                            $turma['plano_estudo_id'] = $planoEstudoId;
                            $turma['disciplina_id'] = $disciplinaPlanoEstudo['DisciplinaPlanoEstudo']['disciplina_id'];
                            $turma['estado_turma_id'] = 1;
                            $turma['semestre_lectivo_id'] = $semestreLectivo['SemestreLectivo']['id'];
                            $nome = $disciplinaPlanoEstudo['Disciplina']['name'] . " - " . $planoEstudo['PlanoEstudo']['name'];
                            $turma['name'] = $nome;

                            $this->Inscricao->Turma->create();
                            $this->Inscricao->Turma->save(['Turma' => $turma]);
                            $inscricao_nova[] = $this->Inscricao->Turma->id;
                        } else {
                            $inscricao_nova[] = $turmaExiste['Turma']['id'];
                        }

                    }
                }

            }
            $this->Session->write('OpenSGA.inscricao.cadeiras', $inscricao_nova);
            $this->Session->write('OpenSGA.inscricao.matricula_id', $matricula_id);
            $this->Session->write('OpenSGA.inscricao.aluno_id', $aluno_id);
            $this->redirect(['controller' => 'inscricaos', 'action' => 'valida_inscricao']);
        }
        $planoEstudos = $this->Inscricao->Turma->getAllDisciplinasForInscricao($aluno_id);

        $this->set('aluno_id', $aluno_id);
        $this->set('matricula_id', $matricula_id);
        $this->set(compact('turmas', 'disciplinas', 'turmas2', 'planoEstudos'));
    }

    /**
     * Inscreve alunos depois da primeira matricula
     *
     * @param type $aluno_id
     * @param type $matricula_id
     *
     *
     * @todo garantir que so se inscreve quem nao tem dividas no semestre anterior
     * @todo A lista de disciplinas deve ter em consideracao  a tabela de precedencias
     * @todo Deve existir possibilidade de inscricao condicional
     */
    public function faculdade_inscrever_aluno($aluno_id, $matricula_id)
    {
        $this->loadModel('Turma');
        $this->loadModel('Aluno');
        $this->loadModel('FinanceiroPagamento');
        $this->loadModel('Matricula');

        $unidade_organica_id = $this->Session->read('Auth.User.unidade_organica_id');

        $is_regular = $this->Inscricao->Aluno->isRegular($aluno_id);
        if (!$is_regular[0]['regular'] == true) {
            $this->Session->setFlash(__('So pode fazer Inscricoes depois de renovar a matricula para o ano lectivo actual'),
                'default', ['class' => 'alert error']);
            $this->redirect($this->referer());
        }
        $this->Inscricao->Aluno->contain([
            'Entidade',
            'PlanoEstudo',
            'Curso' => ['UnidadeOrganica'],
        ]);
        $aluno = $this->Inscricao->Aluno->findById($aluno_id);

        if ($aluno['Curso']['unidade_organica_id'] != $unidade_organica_id) {
            $this->Session->setFlash(__('Nao Possui permissao para aceder a pagina anterior'));
            $this->redirect($this->referer());
        }


        $matricula = $this->Inscricao->Matricula->findById($matricula_id);

        //Primeiro vemos se ainda nao se matriculou no ano lectivo em questao

        $this->Inscricao->contain([
            'Turma',
        ]);
        $inscricoes_activas = $this->Inscricao->find('all', [
            'conditions' => [
                'Inscricao.aluno_id' => $aluno_id,
                'Turma.ano_lectivo_id' => Configure::read('OpenSGA.ano_lectivo_id'),
                'Turma.semestre_lectivo_id' => Configure::read('OpenSGA.semestre_lectivo_id'),
            ],
        ]);
        if (!empty($inscricoes_activas)) {
            $this->Session->setFlash(__('Este Aluno já fez inscrições neste ano. As cadeiras seguintes serão adicionadas ás inscrições anteriores, e o valor da inscrição será recalculado'),
                'default', ['class' => 'alert info']);
            $this->redirect([
                'controller' => 'inscricaos',
                'action' => 'adicionar_cadeiras_inscricao',
                $aluno_id,
                $matricula_id,
            ]);
        }
        if ($this->request->is('post') || $this->request->is('put')) {


            $aluno_id = $this->request->data['Inscricao']['aluno_id'];
            $matricula_id = $this->request->data['Inscricao']['matricula_id'];
            $inscricao_nova = [];

            foreach ($this->request->data['disciplinas'] as $k => $v) {
                if ($v > 0) {
                    $inscricao_nova[] = $v;
                }
            }
            foreach ($this->request->data['disciplinas2'] as $k => $v) {
                if ($v > 0) {
                    $inscricao_nova[] = $v;
                }
            }


            $this->Session->write('OpenSGA.inscricao.cadeiras', $inscricao_nova);
            $this->Session->write('OpenSGA.inscricao.matricula_id', $matricula_id);
            $this->Session->write('OpenSGA.inscricao.aluno_id', $aluno_id);
            $this->redirect(['controller' => 'inscricaos', 'action' => 'valida_inscricao']);
        }


        $turmas = $this->Turma->getAllByAlunoForInscricao($aluno_id);

        $turmas2 = $this->Turma->getAllByPlanoEstudoAntigo($aluno_id);

        $this->set('aluno_id', $aluno_id);
        $this->set('matricula_id', $matricula_id);
        $this->set(compact('turmas', 'disciplinas', 'turmas2'));
    }

    public function faculdade_inscrever_aluno_externo(int $alunoId)
    {

        $aluno = $this->Inscricao->Aluno->getAlunoForAction($alunoId);

        if ($this->request->is('post')) {
            $matricula = $this->Inscricao->Matricula->findByAlunoIdAndAnoLectivoId($alunoId,
                Configure::read('OpenSGA.ano_lectivo_id'));
            $this->redirect([
                'action' => 'inscrever',
                $alunoId,
                $matricula['Matricula']['id'],
                $this->request->data['Inscricao']['plano_estudo_id'],
            ]);
        }

        $isRegular = $this->Inscricao->Aluno->isRegular($alunoId);
        if ($isRegular[0]['regular'] !== true) {
            $this->Flash->error('Este Aluno Possui as seguintes irregularidades: ' . $isRegular[0]['mensagem'] . '. Por isso não pode se inscrever');
            $this->redirect(['action' => 'index']);
        }


        $unidadeOrganicaId = $this->Session->read('Auth.User.unidade_organica_id');
        $funcionario = $this->Inscricao->Aluno->Entidade->Funcionario->getByUserId($this->Session->read('Auth.User.id'));

        $cursos = $this->Inscricao->Aluno->Curso->find('list', [
            'conditions' => [
                'OR' => ['estado_objecto_id is null', 'estado_objecto_id' => 1],
                'unidade_organica_id' => $unidadeOrganicaId,
            ],
        ]);
        $cursoIds = array_keys($cursos);
        $planoEstudos = $this->Inscricao->Aluno->Curso->PlanoEstudo->find('list', [
            'conditions' => [
                'OR' => ['estado_objecto_id is null', 'estado_objecto_id' => 1],
                'curso_id' => $cursoIds,
            ],
        ]);
        $this->set(compact('aluno', 'funcionario', 'cursos', 'planoEstudos'));

    }

    /**
     * Inscreve alunos depois da primeira matricula
     *
     * @param type $aluno_id
     * @param type $matricula_id
     *
     *
     * @todo garantir que so se inscreve quem nao tem dividas no semestre anterior
     * @todo A lista de disciplinas deve ter em consideracao  a tabela de precedencias
     * @todo Deve existir possibilidade de inscricao condicional
     */
    public function faculdade_inscrever_todos_planos_estudos(
        $alunoId,
        $matriculaId,
        $planoEstudoId = null,
        $inscricaoCondicional = null
    ) {
        $this->loadModel('Turma');
        $this->loadModel('Aluno');
        $this->loadModel('FinanceiroPagamento');
        $this->loadModel('Matricula');

        $unidadeOrganicaId = $this->Session->read('Auth.User.unidade_organica_id');

        $isRegular = $this->Inscricao->Aluno->isRegular($alunoId);
        if (!$isRegular[0]['regular'] == true) {
            $this->Session->setFlash(__('So pode fazer Inscricoes depois de renovar a matricula para o ano lectivo actual'),
                'default', ['class' => 'alert error']);
            $this->redirect($this->referer());
        }
        $this->Inscricao->Aluno->contain([
            'Entidade',
            'PlanoEstudo',
            'Curso' => ['UnidadeOrganica'],
        ]);

        if (empty($planoEstudoId)) {
            $planoEstudoId = $this->request->query('planoEstudoId');
        }

        $isExterno = 1;

        $aluno = $this->Inscricao->Aluno->findById($alunoId);
        if (empty($planoEstudoId)) {
            if ($aluno['Curso']['unidade_organica_id'] != $unidadeOrganicaId) {
                $this->Session->setFlash(__('Nao Possui permissao para aceder a pagina anterior2'));
                $this->redirect($this->referer());
            }
            $isExterno = null;
        }


        //Primeiro vemos se ainda nao se matriculou no ano lectivo em questao

        $this->Inscricao->contain([
            'Turma',
        ]);
        $inscricoesActivas = $this->Inscricao->find('all', [
            'conditions' => [
                'Inscricao.aluno_id' => $alunoId,
                'Turma.ano_lectivo_id' => Configure::read('OpenSGA.ano_lectivo_id'),
                'Turma.semestre_lectivo_id' => Configure::read('OpenSGA.semestre_lectivo_id'),
            ],
        ]);
        if (!empty($inscricoesActivas)) {
            $this->Session->setFlash(__('Este Aluno já fez inscrições neste ano. As cadeiras seguintes serão adicionadas ás inscrições anteriores, e o valor da inscrição será recalculado'),
                'default', ['class' => 'alert info']);
            $this->redirect([
                'controller' => 'inscricaos',
                'action' => 'adicionar_cadeiras_inscricao_todos_planos',
                $alunoId,
                $matriculaId,
            ]);
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $alunoId = $this->request->data['Inscricao']['aluno_id'];
            $matriculaId = $this->request->data['Inscricao']['matricula_id'];
            $inscricaoNova = [];

            foreach ($this->request->data['disciplinas'] as $k => $v) {
                if ($v > 0) {
                    $inscricaoNova[] = $v;
                }
            }


            $this->Session->write('OpenSGA.inscricao.cadeiras', $inscricaoNova);
            $this->Session->write('OpenSGA.inscricao.matricula_id', $matriculaId);
            $this->Session->write('OpenSGA.inscricao.aluno_id', $alunoId);
            $this->redirect([
                'controller' => 'inscricaos',
                'action' => 'valida_inscricao',
                '?' => ['isExterno' => $isExterno, 'planoEstudoId' => $planoEstudoId],
            ]);
        }

        $disciplinas = $this->Inscricao->getAllDisciplinasForInscricaoByCurso($alunoId);

        $this->set('aluno_id', $alunoId);
        $this->set('matricula_id', $matriculaId);
        $this->set(compact('disciplinas', 'planoEstudoId'));
    }

    /**
     *
     * Imprime o Comprovativo de inscricao
     *
     * @param type $aluno_id
     * @param type $anolectivo_ano
     *
     * @throws NotFoundException
     */
    public function faculdade_print_comprovativo_inscricao($alunoId, $anolectivoAno = null)
    {
        if ($anolectivoAno == null) {
            $anoLectivo = $this->Inscricao->Turma->AnoLectivo->findByAno(Configure::read('OpenSGA.ano_lectivo'));
        } else {
            $anoLectivo = $this->Inscricao->Turma->AnoLectivo->findByAno($anolectivoAno);
        }

        $this->Inscricao->Aluno->contain([
            'Entidade' => [
                'User',
            ],
        ]);
        $aluno = $this->Inscricao->Aluno->findById($alunoId);
        $matricula = $this->Inscricao->Aluno->Matricula->findByAlunoIdAndAnoLectivoId($alunoId,
            $anoLectivo['AnoLectivo']['id']);

        //Pegamos todas inscricoes activas
        $this->Inscricao->contain([
            'Turma' => [
                'Disciplina' => [
                    'DisciplinaPlanoEstudo' => [
                        'conditions' => [
                            'plano_estudo_id' => $matricula['Matricula']['plano_estudo_id'],
                        ],
                    ],
                ],
                'Curso' => [
                    'UnidadeOrganica',
                ],
                'PlanoEstudo',
                'Turno',
            ],
            'TipoInscricao',
        ]);
        $inscricoesActivas = $this->Inscricao->find('all', [
            'conditions' => [
                'estado_inscricao_id' => 1,
                'aluno_id' => $alunoId,
                'Turma.ano_lectivo_id' => $anoLectivo['AnoLectivo']['id'],
                'Turma.semestre_lectivo_id' => Configure::read('OpenSGA.semestre_lectivo_id'),
            ],
        ]);

        $this->Inscricao->contain([
            'Turma'
        ]);
        $inscricaoRecente = $this->Inscricao->find('first', [
            'conditions' => [
                'estado_inscricao_id' => 1,
                'aluno_id' => $alunoId,
                'Turma.ano_lectivo_id' => $anoLectivo['AnoLectivo']['id'],
                'Turma.semestre_lectivo_id' => Configure::read('OpenSGA.semestre_lectivo_id'),
            ],
            'order' => 'Inscricao.data DESC'
        ]);


        if (empty($inscricoesActivas)) {
            $this->Session->setFlash(__('Este estudante nao possui inscricoes para este ano'), 'default',
                ['class' => 'alert alert-warning']);
            $this->redirect(['action' => 'ver_inscricoes_aluno', 'faculdade' => true, $alunoId]);
        }

        if ($inscricaoRecente['Inscricao']['created_by'] == null) {
            $inscricaoRecente['Inscricao']['created_by'] = 1;
        }
        $this->loadModel('Funcionario');
        $this->Funcionario->contain('Entidade');
        $funcionario = $this->Funcionario->getByUserId($inscricaoRecente['Inscricao']['created_by']);

        $this->set(compact('inscricoesActivas', 'aluno', 'anoLectivo', 'funcionario'));
    }

    public function faculdade_relatorio_inscricoes_semestre()
    {

        
    }

    public function faculdade_relatorio_inscricoes_por_curso(){


    }

    public function faculdade_relatorio_inscricoes_por_cadeira()
    {

        $cursos = $this->Inscricao->Turma->Curso->findAllByUnidadeOrganicaId(CakeSession::read('Auth.User.unidade_organica_id'));
        $cursoIds = Hash::extract($cursos, '{n}.Curso.id');

        $anoLectivo = $this->Inscricao->Turma->AnoLectivo->findByAno(Configure::read('OpenSGA.ano_lectivo'));
        $semestreLectivo = $this->Inscricao->Turma->SemestreLectivo->findByAnoLectivoIdAndSemestre($anoLectivo['AnoLectivo']['id'],
            Configure::read('OpenSGA.semestre_lectivo'));
        $this->Inscricao->contain(['Turma' => ['Disciplina', 'Curso']]);
        $inscricaos = $this->Inscricao->find('all',
            [
                'conditions' => [
                    'Turma.curso_id' => array_values($cursoIds),
                    'Turma.ano_lectivo_id' => $anoLectivo['AnoLectivo']['id'],
                    'Turma.semestre_lectivo_id' => $semestreLectivo['SemestreLectivo']['id'],
                ],
                'fields' => [
                    //'Curso.name',
                    //'Disciplina.name',
                    'Turma.disciplina_id',
                    'Turma.curso_id',
                    'count(*) as total'

                ],
                'group' => ['Inscricao.turma_id']
            ]);

        debug($inscricaos);
        die();
    }

    public function faculdade_relatorios()
    {

    }

    /**
     * Verifica se esta tudo bem com a inscricao e o pagamento
     */
    public function faculdade_valida_inscricao()
    {


        $unidadeOrganicaId = $this->Session->read('Auth.User.unidade_organica_id');
        $alunoId = $this->Session->read('OpenSGA.inscricao.aluno_id');
        $planoEstudoId = $this->request->query('planoEstudoId');
        $this->Inscricao->Aluno->contain([
            'Entidade',
            'PlanoEstudo',
            'Curso' => ['UnidadeOrganica'],
        ]);
        $aluno = $this->Inscricao->Aluno->findById($alunoId);

        $isExterno = $this->request->query('isExterno');

        if (!$isExterno) {
            if ($aluno['Curso']['unidade_organica_id'] != $unidadeOrganicaId) {
                $this->Session->setFlash(__('Nao Possui permissao para aceder a pagina anterior'));
                $this->redirect($this->referer());
            }
        }


        $this->Inscricao->Turma->PlanoEstudo->DisciplinaPlanoEstudo->contain([
            'Disciplina',
        ]);
        $disciplinaPlanoEstudos = $this->Inscricao->Turma->PlanoEstudo->DisciplinaPlanoEstudo->find('all', [
            'conditions' => [
                'DisciplinaPlanoEstudo.id' => $this->Session->read('OpenSGA.inscricao.cadeiras'),
            ],
            'order' => [
                'DisciplinaPlanoEstudo.ano_curricular',
                'DisciplinaPlanoEstudo.semestre_curricular',
            ],
        ]);
        $this->Inscricao->Turma->contain([
            'Disciplina',
        ]);
        // $turmas = $this->Inscricao->Turma->find('all', array('conditions' => array('Turma.id' => $this->Session->read('OpenSGA.inscricao.cadeiras')), ));

        $this->loadModel('FinanceiroPagamento');

        $pagamento_normal = $this->FinanceiroPagamento->FinanceiroTipoPagamento->findByCodigo(44);
        $pagamento_atraso = $this->FinanceiroPagamento->FinanceiroTipoPagamento->findByCodigo(45);
        $total_normal = 0;
        $total_atraso = 0;
        $cadeiras_atraso = 0;
        $cadeiras_normais = 0;
        $turmas_normais = [];
        $turmas_atraso = [];
        $turmas_tipo = [];
        $ano_maior = $disciplinaPlanoEstudos[0]['DisciplinaPlanoEstudo']['ano_curricular'];
        /* @todo verificar isso. Esta a pegar ano menor */
        $semestre_maior = $disciplinaPlanoEstudos[0]['DisciplinaPlanoEstudo']['semestre_curricular'];
        foreach ($disciplinaPlanoEstudos as $disciplina) {
            if ($disciplina['DisciplinaPlanoEstudo']['ano_curricular'] == $ano_maior and $disciplina['DisciplinaPlanoEstudo']['semestre_curricular'] == $semestre_maior) {
                $turmas_normais[] = $disciplina;
                $total_normal = $total_normal + $pagamento_normal['FinanceiroTipoPagamento']['valor'];
                $turmas_tipo[$disciplina['DisciplinaPlanoEstudo']['id']] = 1;
            } else {
                $turmas_atraso[] = $disciplina;
                $total_atraso = $total_atraso + $pagamento_atraso['FinanceiroTipoPagamento']['valor'];
                $turmas_tipo[$disciplina['DisciplinaPlanoEstudo']['id']] = 2;
            }
        }


        $matriculaId = $this->Session->read('OpenSGA.inscricao.matricula_id');

        $imprimir = false;

        if ($this->request->is('post') || $this->request->is('put')) {

            $this->request->data['disciplinas'] = $this->Session->read('OpenSGA.inscricao.cadeiras');
            $total_normal = 0;
            $total_atraso = 0;
            foreach ($this->request->data['Disciplina'] as $disciplina) {

                $turmas_tipo[$disciplina['id']] = $disciplina['tipo'];
                if ($disciplina['tipo'] == 1) {
                    $total_normal = $total_normal + $pagamento_normal['FinanceiroTipoPagamento']['valor'];
                    $cadeiras_normais += 1;
                } else {
                    $total_atraso = $total_atraso + $pagamento_atraso['FinanceiroTipoPagamento']['valor'];
                    $cadeiras_atraso += 1;
                }
            }

            $this->request->data['total_normal'] = $total_normal;
            $this->request->data['total_atraso'] = $total_atraso;
            $this->request->data['aluno_id'] = $alunoId;
            $this->request->data['matricula_id'] = $matriculaId;
            $this->request->data['turmas_tipo'] = $turmas_tipo;

            if ($cadeiras_atraso > 3 && $cadeiras_normais >= 1) {
                //Nao pode fazer cadeiras normais com mais de 3 cadeiras em atraso
                $this->Session->setFlash(__('Não pode fazer cadeiras normais com mais de 3 cadeiras em atraso'),
                    'default', ['class' => 'alert_error']);
                //    $this->redirect(array('controller' => 'alunos', 'action' => 'perfil_estudante', $aluno_id));
            }

            $this->request->data['cadeiras_normais'] = $cadeiras_normais;
            $this->request->data['cadeiras_atraso'] = $cadeiras_atraso;

            $inscricao = $this->Inscricao->inscreveAluno($this->request->data);
            if ($inscricao) {
                $this->Session->setFlash(sprintf(__('O Aluno  Foi inscrito com sucesso', true)), 'default',
                    ['class' => 'alert alert-success']);

                $imprimir = true;
            } else {
                $this->Session->setFlash(sprintf(__('O Aluno  nao foi inscrito', true)), 'default',
                    ['class' => 'alert alert-danger']);

                //$this->redirect(array('controller' => 'alunos', 'action' => 'perfil_estudante', $aluno_id));
            }
        }

        $tipoInscricaos = $this->Inscricao->TipoInscricao->find('list');
        $this->set(compact('disciplinas', 'turmas_normais', 'turmas_atraso', 'total_normal', 'total_atraso',
            'matriculaId', 'alunoId', 'imprimir', 'aluno', 'tipoInscricaos', 'planoEstudoId'));
    }

    /**
     * @fixme Nao eh seguro, todos funcionarios podem aceder, mesmo nao sendo da faculdade
     *
     * @param null $inscricaoId
     */
    function faculdade_ver_detalhes_inscricao($inscricaoId = null)
    {

        if (!$inscricaoId) {
            $this->Session->setFlash('Invalido %s', 'flasherror');
            $this->redirect(['action' => 'index']);
        }
        $this->Inscricao->contain([
            'Aluno' => ['Entidade'],
            'Turma' => ['Curso'],
            'EstadoInscricao',
        ]);
        $inscricao = $this->Inscricao->findById($inscricaoId);
        $aluno = $this->Inscricao->Aluno->getAlunoForAction($inscricao['Inscricao']['aluno_id']);

        if ($this->request->is('post') || $this->request->is('put')) {


            if ($this->Inscricao->actualizaDadosInscricao($this->request->data) === true) {
                $this->Flash->success('Dados da Inscricao Actualizados com Sucesso');
                $this->redirect([
                    'controller' => 'inscricaos',
                    'action' => 'ver_inscricoes_aluno',
                    $aluno['Aluno']['id'],
                ]);
            } else {
                $this->Flash->error('Problemas ao Actualizar a Inscricao. Verifique os dados e tente novamente');
            }
        }


        $this->request->data = $inscricao;

        $tipoInscricaos = $this->Inscricao->TipoInscricao->find('list');
        $estadoInscricaos = $this->Inscricao->EstadoInscricao->find('list');


        $this->set(compact('inscricao', 'aluno', 'tipoInscricaos', 'estadoInscricaos'));
    }

    /**
     *
     * @param type $aluno_id
     *
     * @fixme esta matricula nao eh consistente para casos em que aluno muda de curso,etc
     */
    public function faculdade_ver_inscricoes_aluno($aluno_id)
    {
        $unidade_organica_id = $this->Session->read('Auth.User.unidade_organica_id');

        $this->Inscricao->Aluno->contain([
            'Entidade' => ['User'],
            'PlanoEstudo',
            'Curso' => ['UnidadeOrganica'],
        ]);
        $aluno = $this->Inscricao->Aluno->findById($aluno_id);

        if ($aluno['Curso']['unidade_organica_id'] != $unidade_organica_id) {
            $this->Session->setFlash(__('Nao Possui permissao para aceder a pagina anterior'));
            $this->redirect($this->referer());
        }
        //Pegamos todas inscricoes activas
        $this->Inscricao->contain([
            'Turma' => [
                'Disciplina',
                'AnoLectivo',
            ],
            'TipoInscricao',
        ]);
        $inscricoesActivas = $this->Inscricao->find('all',
            [
                'conditions' => [
                    'Inscricao.estado_inscricao_id' => $this->Inscricao->estadoInscricoesAbertas,
                    'Inscricao.aluno_id' => $aluno_id,
                    'Turma.ano_lectivo_id' => Configure::read('OpenSGA.ano_lectivo_id'),
                    'Turma.semestre_lectivo_id' => Configure::read('OpenSGA.semestre_lectivo_id'),
                ],
                'order' => 'Inscricao.id DESC',
            ]);

        $this->Inscricao->contain([
            'Turma' => [
                'Disciplina',
                'AnoLectivo',
            ],
            'TipoInscricao',
        ]);
        $inscricoesAbertas = $this->Inscricao->find('all',
            [
                'conditions' => [
                    'Inscricao.estado_inscricao_id' => [1, 2, 3],
                    'Inscricao.aluno_id' => $aluno_id,
                    'OR' => [
                        [
                            'Turma.ano_lectivo_id NOT' => Configure::read('OpenSGA.ano_lectivo_id'),
                            'Turma.semestre_lectivo_id NOT' => Configure::read('OpenSGA.semestre_lectivo_id'),
                        ],
                        [
                            'Turma.ano_lectivo_id' => Configure::read('OpenSGA.ano_lectivo_id'),
                            'Turma.semestre_lectivo_id NOT' => Configure::read('OpenSGA.semestre_lectivo_id'),
                        ],


                    ],

                ],
                'order' => 'Inscricao.id DESC',
            ]);

        $this->Inscricao->contain([
            'Turma' => [
                'Disciplina',
                'AnoLectivo',
            ],
            'TipoInscricao',
        ]);
        $cadeirasFeitas = $this->Inscricao->find('all',
            [
                'conditions' => [
                    'Inscricao.estado_inscricao_id' => [4, 5, 6, 13],
                    'Inscricao.aluno_id' => $aluno_id,
                ],
                'order' => 'Inscricao.id DESC',
            ]);
        $anoLectivo = $this->Inscricao->Matricula->AnoLectivo->findByAno(Configure::read('OpenSGA.ano_lectivo'));

        $matricula = $this->Inscricao->Matricula->findByAlunoIdAndCursoIdAndAnoLectivoId($aluno_id,
            $aluno['Aluno']['curso_id'], $anoLectivo['AnoLectivo']['id']);


        $cadeirasPendentes = $this->Inscricao->getAllCadeirasPendentesByAluno($aluno_id);
        $isRegular = $this->Inscricao->Aluno->isRegular($aluno_id);

        if (count($isRegular) == 1 && $isRegular[0]['regular'] == true) {
            if ($isRegular[0]['estado'] == 1) {
                $classeEstado = "alert alert-info";
            } else {
                $classeEstado = "alert alert-success";
            }
        } else {
            $classeEstado = "alert alert-danger";
        }
        $this->set(compact('inscricoesActivas', 'cadeirasPendentes', 'aluno', 'matricula', 'isRegular',
            'classeEstado', 'inscricoesAbertas', 'cadeirasFeitas'));
    }

    public function index()
    {

        $this->Prg->commonProcess();

        $this->Paginator->settings['conditions'] = $this->Inscricao->parseCriteria($this->Prg->parsedParams());
        $this->Paginator->settings['contain'] = [
            'Aluno' => [
                'Entidade',
            ],
            'Turma' => [
                'Curso',
                'Disciplina',
                'AnoLectivo',
                'SemestreLectivo',
            ],
            'EstadoInscricao',
        ];
        $this->Paginator->settings['order'] = 'Inscricao.modified Desc';


        $inscricaos = $this->paginate();

        $faculdades = $this->Inscricao->Turma->Curso->UnidadeOrganica->find('list',
            ['conditions' => ['tipo_unidade_organica_id' => 1]]);
        $cursos = $this->Inscricao->Turma->Curso->find('list', ['conditions' => ['estado_objecto_id' => 1]]);
        $turmas = $this->Inscricao->Turma->find('list', ['limit' => 10]);
        $estadoInscricaos = $this->Inscricao->EstadoInscricao->find('list');
        $this->set(compact('inscricaos', 'turmas', 'cursos', 'faculdades', 'estadoInscricaos'));
    }

    public function manutencao()
    {
        $totalInscricoesAbertas = $this->Inscricao->find('count',
            ['conditions' => ['estado_inscricao_id' => $this->Inscricao->estadoInscricoesAbertas]]);
        $this->set(compact('totalInscricoesAbertas'));
    }

    public function manutencao_inscricoes_abertas()
    {
        $this->paginate = [
            'conditions' => $conditions,
            'contain' => [
                'Aluno' => [
                    'Entidade',
                ],
                'Turma' => [
                    'Curso',
                    'Disciplina',
                    'AnoLectivo',
                    'SemestreLectivo',
                ],
                'EstadoInscricao',
            ],
            'order' => 'Inscricao.modified Desc',
        ];
        $inscricaos = $this->Paginate('Inscricao');
        $this->set(compact('inscricoes'));
    }

    public function relatorio_inscricoes_semestre()
    {

    }

    public function relatorios()
    {

        $unidadeOrganicas = $this->Inscricao->Aluno->Curso->UnidadeOrganica->find('list',
            ['conditions' => ['tipo_unidade_organica_id' => 1]]);
        $arrayX = [];
        $arrayY = [];
        foreach ($unidadeOrganicas as $k => $v) {
            $cursos = $this->Inscricao->Aluno->Curso->find('list', ['conditions' => ['unidade_organica_id' => $k]]);

            $this->Inscricao->contain(['Aluno', 'Turma']);
            $inscricaos = $this->Inscricao->find('count', [
                'conditions' => [
                    'Aluno.curso_id' => array_keys($cursos),
                    'Turma.ano_lectivo_id' => Configure::read('OpenSGA.ano_lectivo_id'),
                    'Turma.semestre_lectivo_id' => Configure::read('OpenSGA.semestre_lectivo_id'),
                ],
                'fields' => 'DISTINCT Inscricao.aluno_id',
            ]);
            $alunos = $this->Inscricao->Aluno->find('count', [
                'conditions' => [
                    'Aluno.curso_id' => array_keys($cursos),
                    'Aluno.estado_aluno_id' => [1, 11, 14],
                ],
            ]);
            $matriculas = $this->Inscricao->Aluno->Matricula->find('count', [
                'conditions' => [
                    'Matricula.curso_id' => array_keys($cursos),
                    'Matricula.ano_lectivo_id' => Configure::read('OpenSGA.ano_lectivo_id'),
                ],
            ]);

            $arrayX[] = $v;
            $arrayInscritos[] = $inscricaos;
            $arrayAlunos[] = $alunos;
            $arrayMatriculas[] = $matriculas;


        }

        $chart = new Highchart();
        $chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
        $chart->chart->renderTo = "inscritos-semestre";
        $chart->chart->type = "column";
        $chart->title->text = "Inscricoes por Semestre";
        $chart->subtitle->text = "Fonte: siga.uem.mz";


        $chart->xAxis->categories = $arrayX;

        $chart->yAxis->min = 0;
        $chart->yAxis->title->text = "Total de Inscricoes";
        $chart->legend->layout = "vertical";
        $chart->legend->backgroundColor = "#FFFFFF";
        $chart->legend->align = "left";
        $chart->legend->verticalAlign = "top";
        $chart->legend->x = 100;
        $chart->legend->y = 70;
        $chart->legend->floating = 1;
        $chart->legend->shadow = 1;

        $chart->tooltip->formatter = new HighchartJsExpr("function() {
    return '' + this.x +': '+ this.y +'';}");

        $chart->plotOptions->column->pointPadding = 0.2;
        $chart->plotOptions->column->borderWidth = 0;
        $chart->plotOptions->column->dataLabels->enabled = true;


        $chart->series[] = [
            'name' => "Total de Inscricoes",
            'data' => $arrayInscritos,
        ];
        $chart->series[] = [
            'name' => "Estudantes Activos",
            'data' => $arrayAlunos,
        ];
        $chart->series[] = [
            'name' => "Estudantes Matriculados em " . Configure::read('OpenSGA.ano_lectivo'),
            'data' => $arrayMatriculas,
        ];


        $this->set(compact('chart'));

    }

    function ver_detalhes_inscricao($inscricaoId = null)
    {


        if (!$inscricaoId) {
            $this->Session->setFlash('Invalido %s', 'flasherror');
            $this->redirect(['action' => 'index']);
        }
        $this->Inscricao->contain([
            'Aluno' => ['Entidade'],
            'Turma' => ['Curso'],
            'EstadoInscricao',
        ]);
        $inscricao = $this->Inscricao->findById($inscricaoId);
        $aluno = $this->Inscricao->Aluno->getAlunoForAction($inscricao['Inscricao']['aluno_id']);

        if ($this->request->is('post') || $this->request->is('put')) {


            if ($this->Inscricao->actualizaDadosInscricao($this->request->data) === true) {
                $this->Flash->success('Dados da Inscricao Actualizados com Sucesso');
                $redirect_url = $this->request->query('redirect_url');
                if (!empty($redirect_url)) {
                    $this->redirect($redirect_url);
                } else {
                    $this->redirect([
                        'controller' => 'inscricaos',
                        'action' => 'ver_inscricoes_aluno',
                        $aluno['Aluno']['id'],
                    ]);
                }

            } else {
                $this->Flash->error('Problemas ao Actualizar a Inscricao. Verifique os dados e tente novamente');
            }
        }


        $this->request->data = $inscricao;

        $tipoInscricaos = $this->Inscricao->TipoInscricao->find('list');
        $estadoInscricaos = $this->Inscricao->EstadoInscricao->find('list');


        $this->set(compact('inscricao', 'aluno', 'tipoInscricaos', 'estadoInscricaos'));
    }

    /**
     *
     * @param type $aluno_id
     *
     * @fixme esta matricula nao eh consistente para casos em que aluno muda de curso,etc
     */
    public function ver_inscricoes_aluno($aluno_id)
    {
        $unidade_organica_id = $this->Session->read('Auth.User.unidade_organica_id');

        $this->Inscricao->Aluno->contain([
            'Entidade' => ['User'],
            'PlanoEstudo',
            'Curso' => ['UnidadeOrganica'],
        ]);
        $aluno = $this->Inscricao->Aluno->findById($aluno_id);

        if ($aluno['Curso']['unidade_organica_id'] != $unidade_organica_id) {
            $this->Session->setFlash(__('Nao Possui permissao para aceder a pagina anterior'));
            $this->redirect($this->referer());
        }
        //Pegamos todas inscricoes activas
        $this->Inscricao->contain([
            'Turma' => [
                'Disciplina',
                'AnoLectivo',
            ],
            'TipoInscricao',
        ]);
        $inscricoesActivas = $this->Inscricao->find('all',
            [
                'conditions' => [
                    'Inscricao.estado_inscricao_id' => $this->Inscricao->estadoInscricoesAbertas,
                    'Inscricao.aluno_id' => $aluno_id,
                    'Turma.ano_lectivo_id' => Configure::read('OpenSGA.ano_lectivo_id'),
                    'Turma.semestre_lectivo_id' => Configure::read('OpenSGA.semestre_lectivo_id'),
                ],
                'order' => 'Inscricao.id DESC',
            ]);

        $this->Inscricao->contain([
            'Turma' => [
                'Disciplina',
                'AnoLectivo',
            ],
            'TipoInscricao',
        ]);
        $inscricoesAbertas = $this->Inscricao->find('all',
            [
                'conditions' => [
                    'Inscricao.estado_inscricao_id' => [1, 2, 3],
                    'Inscricao.aluno_id' => $aluno_id,
                    'OR' => [
                        [
                            'Turma.ano_lectivo_id NOT' => Configure::read('OpenSGA.ano_lectivo_id'),
                            'Turma.semestre_lectivo_id NOT' => Configure::read('OpenSGA.semestre_lectivo_id'),
                        ],
                        [
                            'Turma.ano_lectivo_id' => Configure::read('OpenSGA.ano_lectivo_id'),
                            'Turma.semestre_lectivo_id NOT' => Configure::read('OpenSGA.semestre_lectivo_id'),
                        ],


                    ],

                ],
                'order' => 'Inscricao.id DESC',
            ]);

        $this->Inscricao->contain([
            'Turma' => [
                'Disciplina',
                'AnoLectivo',
            ],
            'TipoInscricao',
        ]);
        $cadeirasFeitas = $this->Inscricao->find('all',
            [
                'conditions' => [
                    'Inscricao.estado_inscricao_id' => [4, 5, 6, 13],
                    'Inscricao.aluno_id' => $aluno_id,
                ],
                'order' => 'Inscricao.id DESC',
            ]);
        $anoLectivo = $this->Inscricao->Matricula->AnoLectivo->findByAno(Configure::read('OpenSGA.ano_lectivo'));

        $matricula = $this->Inscricao->Matricula->findByAlunoIdAndCursoIdAndAnoLectivoId($aluno_id,
            $aluno['Aluno']['curso_id'], $anoLectivo['AnoLectivo']['id']);

        $cadeirasPendentes = $this->Inscricao->getAllCadeirasPendentesByAluno($aluno_id);
        $isRegular = $this->Inscricao->Aluno->isRegular($aluno_id);


        if (count($isRegular) == 1 && $isRegular[0]['regular'] == true) {
            if ($isRegular[0]['estado'] == 1) {
                $classeEstado = "alert alert-info";
            } else {
                $classeEstado = "alert alert-success";
            }
        } else {
            $classeEstado = "alert alert-danger";
        }


        $this->set(compact('inscricoesActivas', 'cadeirasPendentes', 'aluno', 'matricula', 'isRegular',
            'classeEstado', 'inscricoesAbertas', 'cadeirasFeitas'));
    }


}

?>