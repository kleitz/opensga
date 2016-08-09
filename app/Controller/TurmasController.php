<?php

use Ghunti\HighchartsPHP\Highchart;
use Ghunti\HighchartsPHP\HighchartJsExpr;

/**
 * Controller de turmas do OpenSGA
 *
 * @copyright       Copyright 2010-2011, INFOmoz (Informática-Moçambique) (http://infomoz.net)
 * @link            http://infomoz.net/opensga OpenSGA - Sistema de Gestão Académica
 * @author          Elisio Leonardo (elisio.leonardo@gmail.com)
 * @package         opensga
 * @subpackage      opensga.core.turmas
 * @version         OpenSGA v 0.5.0
 * @since           OpenSGA v 0.1.0
 *
 *
 *
 * @todo            No futuro criar uma tabela docentes_turmas vai ajudar a manter o historico completo das turmas
 *
 * @property Turma $Turma
 *
 */
class TurmasController extends AppController
{

    var $name = 'Turmas';

    /**
     * Actualiza as notas de Frequencia de uma Turma
     *
     * @param $turmaId
     *
     * @todo Fazer essa funcao
     */
    public function actualizar_dados($turmaId)
    {

    }

    public function actualizar_notas($turmaId)
    {
        $this->Turma->id = $turmaId;
        if (!$this->Turma->exists()) {
            throw new NotFoundException('Turma Não Existente');
        }

        if ($this->request->is('post')) {
            if ($this->Turma->actualizaNotas($this->request->data) === true) {
                $this->Flash->success('Notas Actualizadas com Sucesso');
                $redirect_url = $this->request->query('redirect_url');
                if ($redirect_url) {
                    $this->redirect($redirect_url);
                } else {
                    $this->redirect(['action' => 'ver_turma', $turmaId]);
                }
            } else {
                $this->Flash->error('Não foi possivel gravar as notas. Verifique os dados e tente novamente');
            }
        }

        $this->Turma->contain('Disciplina');
        $turma = $this->Turma->findById($turmaId);

        $inscricaos = $this->Turma->Inscricao->getAllByTurmaId($turmaId);

        $this->set('siga_page_title', 'Actualizar Notas da Turma');
        $this->set('siga_page_overview', '');

        $this->set(compact('inscricaos', 'turma'));

    }

    /**
     * Cria uma nova Turma Vazia
     * @throws Exception
     *
     * @Todo Verificar o security component
     */
    public function criar_turma()
    {

        if ($this->request->is('post')) {
            try {
                $this->Turma->criaTurma($this->request->data);
                $this->Flash->success('Turma Criada com Sucesso');
                $this->redirect(['action' => 'ver_turma', $this->Turma->id]);
            } catch (Exception $e) {
                $this->Flash->error('Problemas ao Criar Turma. Motivo:' . $e->getMessage());
            }
        }

        $cursos = $this->Turma->Curso->find('list', [
            'conditions' => [
            ],
        ]);
        $anoLectivos = $this->Turma->AnoLectivo->find('list', ['order' => 'AnoLectivo.ano DESC']);
        $turnos = $this->Turma->Turno->find('list');

        $this->set(compact('cursos', 'anoLectivos', 'semestreLectivos', 'turnos'));
    }

    /**
     * Cria uma nova Turma Vazia
     * @throws Exception
     *
     * @Todo Verificar o security component
     */
    public function faculdade_criar_turma()
    {

        if ($this->request->is('post')) {
            try {
                $this->Turma->criaTurma($this->request->data);
                $this->Flash->success('Turma Criada com Sucesso');
                $this->redirect(['action' => 'ver_turma', $this->Turma->id]);
            } catch (Exception $e) {
                $this->Flash->error('Problemas ao Criar Turma. Motivo:' . $e->getMessage());
            }
        }

        $unidadeOrganicaId = $this->Session->read('Auth.User.unidade_organica_id');
        $cursos = $this->Turma->Curso->find('list', [
            'conditions' => [
                'unidade_organica_id' => $unidadeOrganicaId,
            ],
        ]);
        $anoLectivos = $this->Turma->AnoLectivo->find('list', ['order' => 'AnoLectivo.ano DESC']);
        $turnos = $this->Turma->Turno->find('list');

        $this->set(compact('cursos', 'anoLectivos', 'semestreLectivos', 'turnos'));
    }

    /**
     * Adiciona docente e assistente a turma passada pelo id
     *
     * @param int $turma_id
     *
     * @todo no futuro apenas mostrar docentes capacitado a dar a turma em questao, de acordo com o perfil
     */
    public function adicionar_docente($turma_id)
    {
        $this->Turma->id = $turma_id;
        if (!$this->Turma->exists()) {
            throw new NotFoundException(__('Turma Invalida'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {

            if ($this->Turma->adicionaDocente($this->request->data)) {
                $this->Session->setFlash('Os docentes desta turma foram actualizados com sucesso', 'default', [
                    'class' => 'alert alert-success',
                ]);
                $this->redirect(['controller' => 'turmas', 'action' => 'ver_turma', $turma_id]);
            } else {
                $this->Session->setFlash('Problemas ao adicionar a turma', 'default',
                    ['class' => 'alert alert-danger']);
            }
        }


        $turma = $this->Turma->findById($turma_id);
        $this->Turma->DocenteTurma->Docente->contain([
            'Entidade',
        ]);
        $docentes = $this->Turma->DocenteTurma->Docente->find('list', ['fields' => ['Entidade.name']]);
        $tipoDocenteTurmas = $this->Turma->DocenteTurma->TipoDocenteTurma->find('list');

        $this->set('siga_page_title', 'Adicionar Docente a Turma');
        $this->set('siga_page_overview', '');
        $this->set(compact('turma', 'docentes', 'tipoDocenteTurmas', 'turma_id'));
    }

    public function docente_adicionar_assistente($turmaId){
        $this->Turma->id = $turmaId;
        if (!$this->Turma->exists()) {
            throw new NotFoundException(__('Turma Invalida'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {

            if ($this->Turma->adicionaDocente($this->request->data)) {
                $this->Session->setFlash('Os docentes desta turma foram actualizados com sucesso', 'default', [
                    'class' => 'alert alert-success',
                ]);
                $this->redirect(['controller' => 'turmas', 'action' => 'ver_turma', $turmaId]);
            } else {
                $this->Session->setFlash('Problemas ao adicionar a turma', 'default',
                    ['class' => 'alert alert-danger']);
            }
        }

        $this->Turma->contain(['Disciplina', 'Curso', 'AnoLectivo', 'SemestreLectivo']);
        $turma = $this->Turma->findById($turmaId);
        $this->Turma->DocenteTurma->Docente->contain([
            'Entidade',
            'UnidadeOrganica',
        ]);
        $docentes = $this->Turma->DocenteTurma->Docente->find('list', ['fields' => ["Entidade.name"]]);
        $tipoDocenteTurmas = $this->Turma->DocenteTurma->TipoDocenteTurma->find('list');

        $this->set('siga_page_title', 'Adicionar Docente a Turma');
        $this->set('siga_page_overview', '');
        $this->set(compact('turma', 'docentes', 'tipoDocenteTurmas', 'turmaId'));

    }

    /**
     * Cria uma avaliacao para a turma
     *
     * @todo quando selecciona exames, esconder o campo de percentagem
     *
     * @param $turmaId
     */
    public function docente_criar_avaliacao($turmaId)
    {
        $this->Turma->id = $turmaId;
        if (!$this->Turma->exists()) {
            throw new NotFoundException(__('Turma Inválida'));
        }

        $this->Turma->contain([
            'Turno',
            'PlanoEstudo',
            'AnoLectivo',
            'EstadoTurma',
            'Curso' => [
                'UnidadeOrganica',
            ],
            'Disciplina',
            'AnoLectivo',
            'SemestreLectivo',
        ]);
        $turma = $this->Turma->read(null, $turmaId);

        $docente = $this->Turma->DocenteTurma->Docente->getByUserId($this->Session->read('Auth.User.id'));

        if (!$this->Turma->isDocente($turmaId, $docente['Docente']['id'])) {
            $this->Session->SetFlash('Nao tem permissao para aceder a pagina anterior');
            $this->redirect(['action' => 'index']);
        }

        if ($this->request->is('post')) {

            try {
                $this->Turma->criaAvaliacao($this->request->data);
                $this->Flash->success('Avaliacao Criada com Sucesso');
                $this->redirect(['action' => 'ver_turma', $turmaId]);
            } catch (Exception $e) {
                $this->Flash->error($e->getMessage());
            }
        }
        $tipoAvaliacaos = $this->Turma->TurmaTipoAvaliacao->TipoAvaliacao->find('list');

        $this->set(compact('tipoAvaliacaos', 'turma', 'docente'));
    }

    public function docente_fechar_turma($turmaId)
    {
        $this->Turma->id = $turmaId;
        if (!$this->Turma->exists()) {
            throw new NotFoundException('Turma Não Existente');
        }

        $turma = $this->Turma->findById($turmaId);
        if ($turma['Turma']['estado_turma_id'] != 1) {
            $this->Flash->warning('Esta Turma ja esta fechada ou foi Cancelada. Nao e possivel fecha-la novamente');
            $this->redirect(['action' => 'ver_turma', $turmaId]);
        }
        if ($this->request->is('post')) {
            if ($this->Turma->podeSerFechada($turmaId)) {
                if ($this->Turma->fecharTurma($turmaId)) {
                    $this->Flash->success('Turma Fechada com Sucesso. Os Estudantes Serao Notificados');
                    $this->redirect(['action' => 'ver_turma', $turmaId]);
                } else {
                    $this->Flash->error('Problemas ao fechar Turma. Verifique as Pre-Condicoes');
                }
            }
        }


        $inscricaos = $this->Turma->Inscricao->getAllByTurmaId($turmaId);
        $podeSerFechada = $this->Turma->podeSerFechada($turmaId);
        $this->set(compact('inscricaos', 'turma', 'podeSerFechada'));
    }

    public function docente_importar_pauta($turmaId)
    {
        $this->loadModel('Upload');
        if ($this->request->is('post')) {

            $type = $this->request->data['Upload']['file']['type'];
            if ($type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {

                $upload_sucesso = $this->Upload->uploadFiles('uploads', [$this->request->data['Upload']['file']],
                    'pautas');
                if (isset($upload_sucesso['urls'])) {


                    $this->request->data['Upload']['name'] = $this->request->data['Upload']['file']['name'];
                    $this->request->data['Upload']['size'] = $this->request->data['Upload']['file']['size'];
                    $this->request->data['Upload']['file_url'] = $upload_sucesso['urls'][0];
                    $this->request->data['Upload']['tipo_upload_id'] = 2;
                    $this->Upload->create();
                    $this->Upload->save($this->request->data);

                    $processado = $this->Turma->processaPauta($upload_sucesso['path'][0], $turmaId);
                    if ($processado) {
                        $this->Session->setFlash(__('Pauta Processada com Sucesso'), 'default',
                            ['class' => 'alert alert-success']);
                        //$this->redirect(['action' => 'ver_turma', $turmaId]);
                    }
                }
            } else {
                $this->Session->setFlash(__('Tentou carregar um ficheiro no formato errado.'), 'default',
                    ['class' => 'alert error']);
            }
        }
    }

    /**
     * Lista de turmas atribuidas a um dado docente
     */
    public function docente_index()
    {
        $userId = $this->Session->read('Auth.User.id');

        $docente = $this->Turma->DocenteTurma->Docente->getByUserID($userId);
        if (empty($docente)) {
            throw new NotFoundException('Docente Invalido ou tentativa de Fraude');
        }

        $turmasDocente = $this->Turma->DocenteTurma->find('all', [
            'conditions' => [
                'DocenteTurma.docente_id'              => $docente['Docente']['id'],
                'DocenteTurma.estado_docente_turma_id' => 1,
            ],
        ]);
        $turmaIds = Hash::extract($turmasDocente, '{n}.DocenteTurma.turma_id');


        $conditions['Turma.estado_turma_id'] = 1;
        $conditions['Turma.id'] = $turmaIds;
        $this->paginate = [
            'conditions' => $conditions,
            'contain'    => [
                'Disciplina',
                'AnoLectivo',
                'PlanoEstudo',
                'Curso' => ['UnidadeOrganica'],
            ],
            'limit'      => 20,
            'order'      => 'Turma.created DESC',
        ];

        $this->set('turmas', $this->paginate());
    }

    /**
     * Mostra detalhes da turma de um dado docente.
     * FIXME So deve mostrar turmas as quais o docente em questao foi atribuido. Um docente nao pode ver detalhes
     * das turmas dos outros nem com esperteza :D
     *
     * @param type $id
     *
     * @throws NotFoundException
     */

    public function docente_print_lista_estudantes($turmaId)
    {
        $inscricaos = $this->Turma->getAllAlunosActivos($turmaId);

        $totalAlunos = count($inscricaos);
        $totalPaginas = intdiv($totalAlunos, 35) + 1;
        $this->set(compact('inscricaos', 'totalPaginas'));
    }

    public function docente_print_pauta($turmaId)
    {
        $this->Turma->id = $turmaId;
        $turma = $this->Turma->read();

        $todasTurmas = $this->Turma->find('list', [
            'conditions' => [
                'Turma.curso_id'       => $turma['Turma']['curso_id'],
                'Turma.disciplina_id'  => $turma['Turma']['disciplina_id'],
                'Turma.ano_lectivo_id' => $turma['Turma']['ano_lectivo_id'],
            ],
        ]);
        $todasTurmasIds = array_keys($todasTurmas);

        $this->Turma->Inscricao->contain([
            'EstadoInscricao',
            'Matricula' => [
                'Aluno' => [
                    'Entidade' => [],
                ],
            ],
            'Turma'     => [
                'Curso' => [
                    'fields' => ['name'],
                ],
                'Disciplina',
                'Turno',
                'AnoLectivo',
            ],
        ]);
        $inscricaos2 = $this->Turma->Inscricao->find('all',
            ['conditions' => ['turma_id' => $todasTurmasIds, 'Inscricao.estado_inscricao_id' => 1]]);
        $inscricaos = Hash::sort($inscricaos2, '{n}.Matricula.Aluno.Entidade.apelido', 'asc');

        $faculdade = $this->Turma->Curso->getFaculdadeByCursoId($inscricaos[0] ['Turma']['curso_id']);
        $this->set(compact('inscricaos', 'faculdade'));
    }

    public function docente_ver_turma($turmaId = null)
    {
        $this->Turma->id = $turmaId;
        if (!$this->Turma->exists()) {
            throw new NotFoundException(__('Turma Inválida'));
        }

        if (empty($this->data)) {
            $this->Turma->contain([
                'Turno',
                'PlanoEstudo',
                'AnoLectivo',
                'EstadoTurma',
                'Curso' => [
                    'UnidadeOrganica',
                ],
                'Disciplina',
                'AnoLectivo',
            ]);
            $this->data = $this->Turma->read(null, $turmaId);
        }
        $docente = $this->Turma->DocenteTurma->Docente->getByUserId($this->Session->read('Auth.User.id'));

        if (!$this->Turma->isDocente($turmaId, $docente['Docente']['id'])) {
            $this->Session->SetFlash('Nao tem permissao para aceder a pagina anterior');
            $this->redirect(['action' => 'index']);
        }

        $inscricaos = $this->Turma->getAllAlunosActivos($turmaId);


        $this->Turma->TurmaTipoAvaliacao->contain([
            'TipoAvaliacao',
            'EstadoTurmaAvaliacao',
        ]);
        $turmaTipoAvaliacaos = $this->Turma->TurmaTipoAvaliacao->find('all',
            ['conditions' => ['turma_id' => $this->data['Turma']['id']]]);
        $estados = ['1' => 'Activa', '2' => 'Cancelada', '3' => 'Fechada'];
        $anosemestrecurr = ['1' => '1', '2' => '2', '3' => '3', '4' => '4'];
        $anolectivos = $this->Turma->AnoLectivo->find('list');
        $cursos = $this->Turma->Curso->find('list');
        $planoestudos = $this->Turma->PlanoEstudo->find('list');
        $turnos = $this->Turma->Turno->find('list');
        $disciplinas = $this->Turma->Disciplina->find('list');
        $regente = $this->Turma->getRegente($turmaId);
        $assistentes = $this->Turma->getAllAssistentes($turmaId);
        $turmaPodeSerFechada = $this->Turma->podeSerFechada($turmaId);

        $estatisticas = $this->Turma->getEstatisticas($turmaId);

        $this->set('turma', $this->data);
        $this->set(compact('inscricaos', 'turmaTipoAvaliacaos', 'anolectivos', 'estados', 'mediaTurma',
            'anosemestrecurr', 'cursos', 'planoestudos', 'turnos', 'disciplinas', 'docentes', 'ano_curricular',
            'regente', 'assistentes', 'turmaPodeSerFechada','estatisticas'));
    }

    public function faculdade_actualizar_notas($turmaId)
    {
        $this->Turma->id = $turmaId;
        if (!$this->Turma->exists()) {
            throw new NotFoundException('Turma Não Existente');
        }

        if ($this->request->is('post')) {
            if ($this->Turma->actualizaNotas($this->request->data) === true) {
                $this->Flash->success('Notas Actualizadas com Sucesso');
                $redirect_url = $this->request->query('redirect_url');
                if ($redirect_url) {
                    $this->redirect($redirect_url);
                } else {
                    $this->redirect(['action' => 'ver_turma', $turmaId]);
                }
            } else {
                $this->Flash->error('Não foi possivel gravar as notas. Verifique os dados e tente novamente');
            }
        }

        $this->Turma->contain('Disciplina');
        $turma = $this->Turma->findById($turmaId);

        $inscricaos = $this->Turma->Inscricao->getAllByTurmaId($turmaId);

        $this->set('siga_page_title', 'Actualizar Notas da Turma');
        $this->set('siga_page_overview', '');

        $this->set(compact('inscricaos', 'turma'));

    }

    public function faculdade_adicionar_assistente($turmaId){

    }
    /**
     * Associa um Docente a Turma em questao
     *
     * @todo criar a funcionalidade docentes recomendados, de acordo com as disciplinas do docente
     *
     * @param $turmaId
     */
    public function faculdade_adicionar_docente($turmaId)
    {
        $this->Turma->id = $turmaId;
        if (!$this->Turma->exists()) {
            throw new NotFoundException(__('Turma Invalida'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {

            if ($this->Turma->adicionaDocente($this->request->data)) {
                $this->Session->setFlash('Os docentes desta turma foram actualizados com sucesso', 'default', [
                    'class' => 'alert alert-success',
                ]);
                $this->redirect(['controller' => 'turmas', 'action' => 'ver_turma', $turmaId]);
            } else {
                $this->Session->setFlash('Problemas ao adicionar a turma', 'default',
                    ['class' => 'alert alert-danger']);
            }
        }

        $this->Turma->contain(['Disciplina', 'Curso', 'AnoLectivo', 'SemestreLectivo']);
        $turma = $this->Turma->findById($turmaId);
        $this->Turma->DocenteTurma->Docente->contain([
            'Entidade',
            'UnidadeOrganica',
        ]);
        $docentes = $this->Turma->DocenteTurma->Docente->find('list', ['fields' => ["Entidade.name"]]);
        $tipoDocenteTurmas = $this->Turma->DocenteTurma->TipoDocenteTurma->find('list');

        $this->set('siga_page_title', 'Adicionar Docente a Turma');
        $this->set('siga_page_overview', '');
        $this->set(compact('turma', 'docentes', 'tipoDocenteTurmas', 'turmaId'));
    }

    public function faculdade_criar_avaliacao()
    {

    }

    public function faculdade_fechar_turma($turmaId)
    {

        $this->Turma->id = $turmaId;
        if (!$this->Turma->exists()) {
            throw new NotFoundException('Turma Não Existente');
        }

        $turma = $this->Turma->findById($turmaId);
        if ($turma['Turma']['estado_turma_id'] != 1) {
            $this->Flash->warning('Esta Turma ja esta fechada ou foi Cancelada. Nao e possivel fecha-la novamente');
            $this->redirect(['action' => 'ver_turma', $turmaId]);
        }
        if ($this->request->is('post')) {
            if ($this->Turma->podeSerFechada($turmaId)) {
                if ($this->Turma->fecharTurma($turmaId)) {
                    $this->Flash->success('Turma Fechada com Sucesso. Os Estudantes Serao Notificados');
                    $this->redirect(['action' => 'ver_turma', $turmaId]);
                } else {
                    $this->Flash->error('Problemas ao fechar Turma. Verifique as Pre-Condicoes');
                }
            }
        }


        $inscricaos = $this->Turma->Inscricao->getAllByTurmaId($turmaId);

        $podeSerFechada = $this->Turma->podeSerFechada($turmaId);
        $this->set(compact('inscricaos', 'turma', 'podeSerFechada'));
    }

    /**
     * Pesquisa um aluno para inscrever numa turma aberta existente
     *
     * @todo Verificar o curso e plano de estudos do aluno
     * @todo verificar o estado da turma
     *
     * @param $turmaId
     */
    public function faculdade_get_aluno_for_inscricao($turmaId)
    {
        if ($this->request->is('post')) {
            $conditions = [];
            if (!empty($this->request->data['Turma']['codigo_aluno'])) {
                $conditions['Aluno.codigo'] = $this->request->data['Turma']['codigo_aluno'];
            } else {
                if (!empty($this->request->data['Turma']['apelido_aluno'])) {
                    $conditions['Entidade.apelido LIKE'] = '%' . $this->request->data['Turma']['apelido_aluno'] . '%';
                }
                if (!empty($this->request->data['Turma']['nomes_aluno'])) {
                    $conditions['Entidade.nomes LIKE'] = '%' . $this->request->data['Turma']['nomes_aluno'] . '%';
                }
            }

            $alunos = $this->Turma->Inscricao->Aluno->find('all', ['conditions' => $conditions]);
            if (count($alunos) == 1) {
                $inscricaoExiste = $this->Turma->Inscricao->findByTurmaIdAndAlunoId($turmaId,
                    $alunos[0]['Aluno']['id']);
                if ($inscricaoExiste) {
                    $this->Flash->error('Este Estudante ja esta inscrito nesta turma');
                } else {
                    $redirect_url = $this->request->query('redirect_url');
                    $this->redirect([
                        'action' => 'inscrever_aluno',
                        $alunos[0]['Aluno']['id'],
                        $turmaId,
                        '?'      => ['redirect_url' => $redirect_url],
                    ]);
                }

            }
        }
    }

    public function faculdade_importar_pauta($turmaId)
    {
        $this->loadModel('Upload');
        if ($this->request->is('post')) {

            $type = $this->request->data['Upload']['file']['type'];
            debug($type);
            if ($type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {

                $uploadSucesso = $this->Upload->uploadFiles('uploads', [$this->request->data['Upload']['file']],
                    'pautas_pendentes');
                if (isset($uploadSucesso['urls'])) {
                    $this->request->data['Upload']['name'] = $this->request->data['Upload']['file']['name'];
                    $this->request->data['Upload']['size'] = $this->request->data['Upload']['file']['size'];
                    $this->request->data['Upload']['file_url'] = $uploadSucesso['urls'][0];
                    $this->request->data['Upload']['tipo_upload_id'] = 2;
                    $this->Upload->create();
                    $this->Upload->save($this->request->data);

                    $pautaPath = Configure::read('OpenSGA.Pautas.save_path');
                    if (!is_dir($pautaPath)) {
                        mkdir($pautaPath, 0777, true);
                        chmod($pautaPath, 0755);
                    }
                    CakeResque::enqueue(
                        'default', 'TurmaShell',
                        ['processaPauta', $turmaId, $this->Upload->id, $uploadSucesso['urls'][0]]
                    );
                    $this->Session->setFlash(__('Pauta Carregada com Sucesso. A Pauta Sera processada dentro de alguns minutos'),
                        'default', ['class' => 'alert alert-info']);
                    $this->redirect(['action' => 'ver_turma', $turmaId]);
                }
            } else {
                $this->Session->setFlash(__('Tentou carregar um ficheiro no formato errado.'), 'default',
                    ['class' => 'alert error']);
            }
        }
    }

    function faculdade_index()
    {

        $this->Turma->contain([
            'AnoLectivo',
            'Disciplina',
            'PlanoEstudo',
            'Curso' => ['UnidadeOrganica'],
        ]);

        $unidade_organica_id = $this->Session->read('Auth.User.unidade_organica_id');

        $conditions = [];
        $paginationOptions = [];
        $estadoTurma = $this->request->query('estado_turma');
        $codigo = $this->request->query('codigo');
        $name = $this->request->query('name');
        $anoLectivoAno = $this->request->query('ano');
        if ($codigo) {
            $conditions['Turma.codigo'] = $codigo;
        }
        if ($name) {
            $conditions['Turma.name LIKE'] = '%' . $name . '%';
        }
        if ($anoLectivoAno) {
            $conditions['AnoLectivo.ano'] = $anoLectivoAno;
            $paginationOptions['url']['ano_lectivo'] = $anoLectivoAno;
        }
        if ($estadoTurma) {
            $conditions['Turma.estado_turma_id'] = $estadoTurma;
        } else {
            $conditions['Turma.estado_turma_id'] = 1;
        }


        if ($this->request->is('ajax')) {
            if (isset($this->request->params['named']['ano_lectivo'])) {
                $conditions['AnoLectivo.ano'] = $this->request->params['named']['ano_lectivo'];
            }
        }

        $conditions['Curso.unidade_organica_id'] = $unidade_organica_id;

        $this->paginate = [
            'conditions' => $conditions,
            'contain'    => [
                'AnoLectivo',
                'Disciplina',
                'PlanoEstudo',
                'Curso'     => ['UnidadeOrganica'],
                'Inscricao' => [
                    'conditions' => [
                        'estado_inscricao_id' => 1,
                    ],
                ],
            ],
            'limit'      => 20,
            'order'      => 'Turma.created DESC',
        ];

        $estadoTurma2 = $this->Turma->EstadoTurma->findById($conditions['Turma.estado_turma_id']);

        $turmas = $this->paginate('Turma');
        $this->set(compact('turmas', 'paginationOptions', 'estadoTurma','estadoTurma2'));
    }

    public function faculdade_inscrever_aluno($alunoId, $turmaId)
    {
        if ($this->request->is('post')) {
            if ($this->Turma->Inscricao->inscreveAlunoNaTurma($this->request->data) === true) {
                $this->Flash->success('Aluno Inscrito com Sucesso na Turma');
                $redirect_url = $this->request->query('redirect_url');
                if ($redirect_url) {
                    $this->redirect($redirect_url);
                } else {
                    $this->redirect(['action' => 'ver_turma', $turmaId]);
                }
            } else {
                $this->Flash->error('Algo Estranho aconteceu com esta turma');
            }
        }

        $this->Turma->Inscricao->Aluno->contain(['Entidade', 'Curso']);
        $aluno = $this->Turma->Inscricao->Aluno->findById($alunoId);

        $this->Turma->contain('AnoLectivo');
        $turma = $this->Turma->findById($turmaId);

        $tipoInscricaos = $this->Turma->Inscricao->TipoInscricao->find('list');

        $this->set(compact('aluno', 'turma', 'tipoInscricaos'));

    }

    public function faculdade_manutencao()
    {

        $anoLectivoAno = Configure::read('OpenSGA.ano_lectivo');
        $anoLectivo = $this->Turma->AnoLectivo->findByAno($anoLectivoAno);
        $anoLectivoId = $anoLectivo['AnoLectivo']['id'];

        $semestreLectivoSemestre = Configure::read('OpenSGA.semestre_lectivo');
        $semestreLectivo = $this->Turma->AnoLectivo->SemestreLectivo->findByAnoLectivoIdAndSemestre($anoLectivoId,
            $semestreLectivoSemestre);
        $semestreLectivoId = $semestreLectivo['SemestreLectivo']['id'];

        $unidadeOrganicaId = CakeSession::read('Auth.User.unidade_organica_id');

        $totalTurmasSemDocente = $this->Turma->getTurmasSemDocente($anoLectivoId, $semestreLectivoId,
            $unidadeOrganicaId, 'count');


        $this->set(compact('totalTurmasSemDocente'));
    }

    public function faculdade_manutencao_turmas_abertas()
    {

    }

    public function faculdade_manutencao_turmas_sem_docente()
    {

        $anoLectivoAno = Configure::read('OpenSGA.ano_lectivo');
        $anoLectivo = $this->Turma->AnoLectivo->findByAno($anoLectivoAno);
        $anoLectivoId = $anoLectivo['AnoLectivo']['id'];

        $semestreLectivoSemestre = Configure::read('OpenSGA.semestre_lectivo');
        $semestreLectivo = $this->Turma->AnoLectivo->SemestreLectivo->findByAnoLectivoIdAndSemestre($anoLectivoId,
            $semestreLectivoSemestre);
        $semestreLectivoId = $semestreLectivo['SemestreLectivo']['id'];


        $unidadeOrganicaId = CakeSession::read('Auth.User.unidade_organica_id');

        $this->Turma->contain(['AnoLectivo', 'SemestreLectivo', 'Disciplina']);
        $turmasSemDocente = $this->Turma->getTurmasSemDocente($anoLectivoId, $semestreLectivoId, $unidadeOrganicaId,
            'all');

        $this->set(compact('turmasSemDocente'));

    }

    public function faculdade_manutencao_turmas_sem_estudante()
    {

    }

    public function faculdade_print_lista_estudantes($turma_id)
    {
        $inscricaos = $this->Turma->getAllAlunosActivos($turma_id);

        $totalAlunos = count($inscricaos);
        $totalPaginas = intdiv($totalAlunos, 35) + 1;
        $this->set(compact('inscricaos', 'totalPaginas'));
    }

    public function faculdade_print_pauta($turmaId)
    {
        $this->Turma->id = $turmaId;
        $turma = $this->Turma->read();

        //primeiro vamos ver se nenhuma pauta foi gerada previamente
        $pautaFile = new File($turma['Turma']['pauta_path']);
        if ($pautaFile->exists()) {
            $this->response->file($pautaFile,
                ['name' => Inflector::slug($turma['Turma']['name']), 'download' => true]);

            return $this->response;
        } else {
            $todasTurmas = $this->Turma->find('list', [
                'conditions' => [
                    'Turma.curso_id'       => $turma['Turma']['curso_id'],
                    'Turma.disciplina_id'  => $turma['Turma']['disciplina_id'],
                    'Turma.ano_lectivo_id' => $turma['Turma']['ano_lectivo_id'],
                    //'Turma.semestre_curricular'=>$turma['Turma']['semestre_curricular']
                ],
            ]);
            $todasTurmasIds = array_keys($todasTurmas);

            $this->Turma->Inscricao->contain([
                'EstadoInscricao',
                'Matricula' => [
                    'Aluno' => [
                        'Entidade' => [],
                    ],
                ],
                'Turma'     => [
                    'Curso' => [
                        'fields' => ['name'],
                    ],
                    'Disciplina',
                    'Turno',
                    'AnoLectivo',
                ],
            ]);
            $inscricaos2 = $this->Turma->Inscricao->find('all', [
                'conditions' => [
                    'turma_id'                          => $todasTurmasIds,
                    'Inscricao.estado_inscricao_id NOT' => 9,
                ],
            ]);
            $inscricaos = Hash::sort($inscricaos2, '{n}.Matricula.Aluno.Entidade.apelido', 'asc');
            $faculdade = $this->Turma->Curso->getFaculdadeByCursoId($inscricaos[0] ['Turma']['curso_id']);
            $this->set(compact('inscricaos', 'faculdade'));
        }
    }

    public function faculdade_ver_turma($id = null)
    {
        $this->Turma->id = $id;
        if (!$this->Turma->exists()) {
            throw new NotFoundException(__('Turma Inválida'));
        }

        if (empty($this->data)) {
            $this->Turma->contain([
                'Turno',
                'PlanoEstudo',
                'AnoLectivo',
                'EstadoTurma',
                'Curso' => [
                    'UnidadeOrganica',
                ],
                'Disciplina',
                'AnoLectivo',
            ]);
            $this->data = $this->Turma->read(null, $id);
        }
        $unidadeOrganicaTurma = $this->data['Curso'] ['unidade_organica_id'];
        $unidadeOrganicaUser = $this->Session->read('Auth.User.unidade_organica_id');
        if ($unidadeOrganicaTurma != $unidadeOrganicaUser) {
            $this->Session->SetFlash('Nao tem permissao para aceder a pagina anterior');
            $this->redirect(['action' => 'index']);
        }


        $inscricaos = $this->Turma->getAllAlunosActivos($id);

        $this->Turma->TurmaTipoAvaliacao->contain([
            'TipoAvaliacao',
        ]);
        $turmaTipoAvaliacaos = $this->Turma->TurmaTipoAvaliacao->find('all',
            ['conditions' => ['turma_id' => $this->data['Turma']['id']]]);
        $estados = ['1' => 'Activa', '2' => 'Cancelada', '3' => 'Fechada'];
        $anosemestrecurr = ['1' => '1', '2' => '2', '3' => '3', '4' => '4'];
        $anolectivos = $this->Turma->AnoLectivo->find('list');
        $cursos = $this->Turma->Curso->find('list');
        $planoestudos = $this->Turma->PlanoEstudo->find('list');
        $turnos = $this->Turma->Turno->find('list');
        $disciplinas = $this->Turma->Disciplina->find('list');
        $regente = $this->Turma->getRegente($id);
        $assistentes = $this->Turma->getAllAssistentes($id);
        $turmaPodeSerFechada = $this->Turma->podeSerFechada($id);

        $estatisticas = $this->Turma->getEstatisticas($id);

        $this->set('turma', $this->data);
        $this->set(compact('inscricaos', 'turmaTipoAvaliacaos', 'anolectivos', 'estados', 'mediaTurma',
            'anosemestrecurr', 'cursos', 'planoestudos', 'turnos', 'disciplinas', 'docentes', 'ano_curricular',
            'regente', 'assistentes', 'turmaPodeSerFechada','estatisticas'));
    }

    public function fechar_todas_turmas($semestre)
    {
        $this->Turma->fecharTodasTurmas($semestre);

        $this->redirect([
            'action'
            => 'index',
        ]);
    }

    /**
     * Passa todos estudantes da turma actual para uma outra turma a Indicar.
     * Migra as inscricoes, docentes associados e avaliacoes criadas
     * @param $turmaId
     *
     */
    public function faculdade_migrar_estudantes($turmaId)
    {

        if ($this->request->is('post')) {
            try {
                $this->Turma->migraEstudantes($this->request->data);
                $this->Flash->success('Turma Migrada com Sucesso');
                $this->redirect(['action' => 'ver_turma', $this->request->data['Turma']['turma_id']]);
            } catch (Exception $e) {
                $this->Flash->error('Turma Nao Migrada. Motivo:' . $e->getMessage());
            }
        }
        $this->Turma->contain(['Curso', 'Disciplina', 'AnoLectivo', 'SemestreLectivo']);
        $turma = $this->Turma->findById($turmaId);

        $turmas = $this->Turma->find('list', [
            'conditions' => [
                'curso_id'            => $turma['Turma']['curso_id'],
                'ano_lectivo_id'      => $turma['Turma']['ano_lectivo_id'],
                'semestre_lectivo_id' => $turma['Turma']['semestre_lectivo_id'],
            ],
            'order'      => 'Turma.name',
        ]);
        $this->set(compact('turma', 'turmaId', 'turmas'));
    }

    /**
     * Esta funcao fecha uma determinada turma. Mas so fecha se a turma nao tiver avaliacoes abertas
     * Condicoes para Fecho da Turma:
     *  -Todas Avaliacoes devem ser realizadas e Fechadas
     *  -Todas Aulas devem ser Leccionadas
     *  -Todos os alunos devem ter um estado de Inscricao nao aberto(Ou passaram ou chumbaram)
     *
     * @param type $id
     */
    public function fechar_turma($turmaId)
    {
        $this->Turma->id = $turmaId;
        if (!$this->Turma->exists()) {
            throw new NotFoundException('Turma Não Existente');
        }

        $turma = $this->Turma->findById($turmaId);
        if ($turma['Turma']['estado_turma_id'] != 1) {
            $this->Flash->warning('Esta Turma ja esta fechada ou foi Cancelada. Nao e possivel fecha-la novamente');
            $this->redirect(['action' => 'ver_turma', $turmaId]);
        }
        if ($this->request->is('post')) {
            if ($this->Turma->podeSerFechada($turmaId)) {
                if ($this->Turma->fecharTurma($turmaId)) {
                    $this->Flash->success('Turma Fechada com Sucesso. Os Estudantes Serao Notificados');
                    $this->redirect(['action' => 'ver_turma', $turmaId]);
                } else {
                    $this->Flash->error('Problemas ao fechar Turma. Verifique as Pre-Condicoes');
                }
            }
        }


        $inscricaos = $this->Turma->Inscricao->getAllByTurmaId($turmaId);

        $podeSerFechada = $this->Turma->podeSerFechada($turmaId);

        $this->set(compact('inscricaos', 'turma', 'podeSerFechada'));

    }

    /**
     * Gera todas as turmas em funcao do plano de estudos.
     *
     * @todo Garantir que isso funciona
     */
    function gerar_turmas()
    {
        if (!empty($this->request->data)) {
            /**
             * @todo Verificar o ajuste do anolectivo ao regime antes de enviar para o modelo
             */
            $this->Turma->criarTurmas(29);

            $this->Session->setFlash('As Turmas foram Geradas com Sucesso', 'flashok');
            //$this->redirect(array('action' => 'index'));
        }


        $anolectivos = $this->Turma->AnoLectivo->find('list');
        $planoestudos = $this->Turma->PlanoEstudo->find('list');
        $semestrelectivos = $this->Turma->SemestreLectivo->find('list');
        $RegimeLectivos = $this->Turma->AnoLectivo->RegimeLectivo->find('list');
        $turnos = $this->Turma->Turno->find('list');

        $this->set(compact('anolectivos', 'planoestudos', 'semestrelectivos', 'RegimeLectivos', 'turnos'));
    }

    /**
     * Pesquisa um aluno para inscrever numa turma aberta existente
     *
     * @todo Verificar o curso e plano de estudos do aluno
     * @todo verificar o estado da turma
     *
     * @param $turmaId
     */
    public function get_aluno_for_inscricao($turmaId)
    {
        if ($this->request->is('post')) {
            $conditions = [];
            if (!empty($this->request->data['Turma']['codigo_aluno'])) {
                $conditions['Aluno.codigo'] = $this->request->data['Turma']['codigo_aluno'];
            } else {
                if (!empty($this->request->data['Turma']['apelido_aluno'])) {
                    $conditions['Entidade.apelido LIKE'] = '%' . $this->request->data['Turma']['apelido_aluno'] . '%';
                }
                if (!empty($this->request->data['Turma']['nomes_aluno'])) {
                    $conditions['Entidade.nomes LIKE'] = '%' . $this->request->data['Turma']['nomes_aluno'] . '%';
                }
            }

            $alunos = $this->Turma->Inscricao->Aluno->find('all', ['conditions' => $conditions]);
            if (count($alunos) == 1) {
                $inscricaoExiste = $this->Turma->Inscricao->findByTurmaIdAndAlunoId($turmaId,
                    $alunos[0]['Aluno']['id']);
                if ($inscricaoExiste) {
                    $this->Flash->error('Este Estudante ja esta inscrito nesta turma');
                } else {
                    $redirect_url = $this->request->query('redirect_url');
                    $this->redirect([
                        'action' => 'inscrever_aluno',
                        $alunos[0]['Aluno']['id'],
                        $turmaId,
                        '?'      => ['redirect_url' => $redirect_url],
                    ]);
                }

            }
        }
    }

    /**
     * Lista de turmas activas no Sistema.
     *
     * @todo remover o filtro de docentes
     * @todo adicionar o filtro de unidades organicas
     */
    function index()
    {
        $this->Turma->contain([
            'AnoLectivo',
            'Disciplina',
            'PlanoEstudo',
            'Curso' => ['UnidadeOrganica'],
        ]);

        $conditions = [];
        $paginationOptions = [];
        $estadoTurma = $this->request->query('estado_turma');
        $codigo = $this->request->query('codigo');
        $name = $this->request->query('name');
        $anoLectivoAno = $this->request->query('ano');
        $cursoId = $this->request->query('curso_id');
        if ($codigo) {
            $conditions['Turma.codigo'] = $codigo;
        }
        if ($name) {
            $conditions['Turma.name LIKE'] = '%' . $name . '%';
        }
        if ($cursoId) {
            $conditions['Turma.curso_id'] = $cursoId;
        }
        if ($anoLectivoAno) {
            $conditions['AnoLectivo.ano'] = $anoLectivoAno;
            $paginationOptions['url']['ano_lectivo'] = $anoLectivoAno;
        }
        if ($estadoTurma) {
            $conditions['Turma.estado_turma_id'] = $estadoTurma;
        } else {
            $conditions['Turma.estado_turma_id'] = 1;
        }
        if ($this->request->is('ajax')) {
            if (isset($this->request->params['named']['ano_lectivo'])) {
                $conditions['AnoLectivo.ano'] = $this->request->params['named']['ano_lectivo'];
            }
        }
        $this->paginate = [
            'conditions' => $conditions,
            'contain'    => [
                'AnoLectivo',
                'Disciplina',
                'PlanoEstudo',
                'Curso'     => ['UnidadeOrganica'],
                'Inscricao' => [
                    'conditions' => [
                        //'estado_inscricao_id' => 1
                    ],
                ],
            ],
            'limit'      => 20,
            'order'      => 'Turma.created DESC',
        ];

        $cursos = $this->Turma->Curso->find('list');
        $estadoTurma2 = $this->Turma->EstadoTurma->findById($conditions['Turma.estado_turma_id']);
        $turmas = $this->paginate('Turma');
        $this->set(compact('turmas', 'paginationOptions', 'estadoTurma2', 'cursos', 'estadoTurma'));
    }

    public function inscrever_aluno($alunoId, $turmaId)
    {
        if ($this->request->is('post')) {
            if ($this->Turma->Inscricao->inscreveAlunoNaTurma($this->request->data) === true) {
                $this->Flash->success('Aluno Inscrito com Sucesso na Turma');
                $redirect_url = $this->request->query('redirect_url');
                if ($redirect_url) {
                    $this->redirect($redirect_url);
                } else {
                    $this->redirect(['action' => 'ver_turma', $turmaId]);
                }
            } else {
                $this->Flash->error('Algo Estranho aconteceu com esta turma');
            }
        }

        $this->Turma->Inscricao->Aluno->contain(['Entidade', 'Curso']);
        $aluno = $this->Turma->Inscricao->Aluno->findById($alunoId);

        $this->Turma->contain('AnoLectivo');
        $turma = $this->Turma->findById($turmaId);

        $tipoInscricaos = $this->Turma->Inscricao->TipoInscricao->find('list');

        $this->set(compact('aluno', 'turma', 'tipoInscricaos'));

    }

    public function print_lista_estudantes($turma_id)
    {
        $this->Turma->Inscricao->contain([
            'EstadoInscricao',
            'Matricula' => [
                'Aluno' => [
                    'Entidade' => [
                        'User',
                    ],
                ],
            ],
            'Turma'     => [
                'Curso' => [
                    'UnidadeOrganica',
                ],
                'Disciplina',
                'Turno',
                'AnoLectivo',
            ],
        ]);

        $inscricaos = $this->Turma->getAllAlunosActivos($turma_id);

        $this->set(compact('inscricaos'));
    }

    public function print_pauta($turmaId)
    {

        $this->Turma->id = $turmaId;
        $turma = $this->Turma->read();
        $this->Turma->Inscricao->contain([
            'EstadoInscricao',
            'Matricula' => [
                'Aluno' => [
                    'Entidade' => [],
                ],
            ],
            'Turma'     => [
                'Curso' => [
                    'fields' => ['name'],
                ],
                'Disciplina',
                'Turno',
                'AnoLectivo',
            ],
        ]);
        $inscricaos = $this->Turma->Inscricao->find('all',
            ['conditions' => ['turma_id' => $turmaId, 'Inscricao.estado_inscricao_id' => 1]]);
        $inscricaos = Hash::sort($inscricaos, '{n}.Matricula.Aluno.Entidade.apelido', 'asc');

        $this->set(compact('inscricaos'));
    }

    public function relatorios()
    {

    }

    public function relatorios_turmas_abertas()
    {

        $conditions = [];
        $unidadeOrganicaId = $this->request->query('unidadeOrganicaId');
        $anoLectivo = $this->request->query('anoLectivo');
        if ($unidadeOrganicaId) {
            $conditions['Curso.unidade_organica_id'] = $unidadeOrganicaId;
        }
        if ($anoLectivo) {
            $conditions['AnoLectivo.ano'] = $anoLectivo;
        }
        $conditions['Turma.estado_turma_id'] = 1;

        //$turmas_abertas = $this->Turma->find('count',)
        $chart = new Highchart();

        $chart->chart->renderTo = "abertas-vs-fechadas";
        $chart->chart->plotBackgroundColor = null;
        $chart->chart->plotBorderWidth = null;
        $chart->chart->plotShadow = false;
        $chart->title->text = "Turmas Abertas vs Fechadas";

        $chart->tooltip->formatter = new HighchartJsExpr(
            "function() {
    return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';}");

        $chart->plotOptions->pie->allowPointSelect = 1;
        $chart->plotOptions->pie->cursor = "pointer";
        $chart->plotOptions->pie->dataLabels->enabled = 1;
        $chart->plotOptions->pie->dataLabels->color = "#000000";
        $chart->plotOptions->pie->dataLabels->connectorColor = "#000000";

        $chart->plotOptions->pie->dataLabels->formatter = new HighchartJsExpr(
            "function() {
    return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %'; }");

        $chart->series[] = [
            'type' => "pie",
            'name' => "Browser share",
            'data' => [
                [
                    "Firefox",
                    45,
                ],
                [
                    "IE",
                    26.8,
                ],
            ],
        ];


        $this->set(compact('chart'));
    }

    function ver_turma($id = null)
    {
        $this->Turma->id = $id;
        if (!$this->Turma->exists()) {
            throw new NotFoundException(__('Turma Inválida'));
        }

        if (empty($this->data)) {
            $this->Turma->contain([
                'Turno',
                'PlanoEstudo',
                'AnoLectivo',
                'EstadoTurma',
                'Curso' => [
                    'UnidadeOrganica',
                ],
                'Disciplina',
                'AnoLectivo',
            ]);
            $this->data = $this->Turma->read(null, $id);
        }


        $inscricaos = $this->Turma->getAllAlunosActivos($id);

        $this->Turma->TurmaTipoAvaliacao->contain([
            'TipoAvaliacao',
        ]);
        $turmaTipoAvaliacaos = $this->Turma->TurmaTipoAvaliacao->find('all',
            ['conditions' => ['turma_id' => $this->data['Turma']['id']]]);
        $estados = ['1' => 'Activa', '2' => 'Cancelada', '3' => 'Fechada'];
        $anosemestrecurr = ['1' => '1', '2' => '2', '3' => '3', '4' => '4'];
        $anolectivos = $this->Turma->AnoLectivo->find('list');
        $cursos = $this->Turma->Curso->find('list');
        $planoestudos = $this->Turma->PlanoEstudo->find('list');
        $turnos = $this->Turma->Turno->find('list');
        $disciplinas = $this->Turma->Disciplina->find('list');
        $regente = $this->Turma->getRegente($id);
        $assistentes = $this->Turma->getAllAssistentes($id);
        $turmaPodeSerFechada = $this->Turma->podeSerFechada($turmaId);

        $this->set('turma', $this->data);
        $this->set(compact('inscricaos', 'turmaTipoAvaliacaos', 'anolectivos', 'estados', 'mediaTurma',
            'anosemestrecurr', 'cursos', 'planoestudos', 'turnos', 'disciplinas', 'docentes', 'ano_curricular',
            'regente', 'assistentes', 'turmaPodeSerFechada'));
    }


    public function estudante_ver_turma($turmaId = null)
    {
        $this->Turma->id = $turmaId;
        if (!$this->Turma->exists()) {
            throw new NotFoundException(__('Turma Inválida'));
        }

        if (empty($this->data)) {
            $this->Turma->contain([
                'Turno',
                'PlanoEstudo',
                'AnoLectivo',
                'EstadoTurma',
                'Curso' => [
                    'UnidadeOrganica',
                ],
                'Disciplina',
                'AnoLectivo',
            ]);
            $this->data = $this->Turma->read(null, $turmaId);
        }

        $aluno = $this->Turma->Inscricao->Aluno->findByUserId(CakeSession::read('Auth.User.id'));
        $this->Turma->Inscricao->contain([
            'EstadoInscricao',
            'Matricula' => [
                'Aluno' => [
                    'Entidade' => [
                        'User',
                    ],
                ],
            ],
            'Turma'     => [
                'Curso' => [
                    'fields' => ['name'],
                ],
                'Disciplina',
                'Turno',
                'AnoLectivo',
            ],
        ]);
        $inscricao = $this->Turma->Inscricao->findByAlunoIdAndTurmaId($aluno['Aluno']['id'], $turmaId);
        if (empty($inscricao)) {
            $this->Flash->error('Não possui permissão para ver esta turma');
            $this->redirect($this->referer(['controller' => 'pages', 'action' => 'home']));
        }


        $this->Turma->TurmaTipoAvaliacao->contain([
            'TipoAvaliacao',
        ]);
        $turmaTipoAvaliacaos = $this->Turma->TurmaTipoAvaliacao->find('all',
            ['conditions' => ['turma_id' => $this->data['Turma']['id']]]);
        $estados = ['1' => 'Activa', '2' => 'Cancelada', '3' => 'Fechada'];
        $anosemestrecurr = ['1' => '1', '2' => '2', '3' => '3', '4' => '4'];
        $anolectivos = $this->Turma->AnoLectivo->find('list');
        $cursos = $this->Turma->Curso->find('list');
        $planoestudos = $this->Turma->PlanoEstudo->find('list');
        $turnos = $this->Turma->Turno->find('list');
        $disciplinas = $this->Turma->Disciplina->find('list');
        $regente = $this->Turma->getRegente($turmaId);
        $assistentes = $this->Turma->getAllAssistentes($turmaId);

        $this->set('turma', $this->data);
        $this->set(compact('turmaTipoAvaliacaos', 'anolectivos', 'estados', 'mediaTurma',
            'anosemestrecurr', 'cursos', 'planoestudos', 'turnos', 'disciplinas', 'docentes', 'ano_curricular',
            'regente', 'assistentes'));
    }

    function estudante_index()
    {

        $this->Turma->contain([
            'AnoLectivo',
            'Disciplina',
            'PlanoEstudo',
            'Curso' => ['UnidadeOrganica'],
        ]);

        $aluno = $this->Turma->Inscricao->Aluno->findByUserId(CakeSession::read('Auth.User.id'));
        $inscricoesActivas = $this->Turma->Inscricao->find('list', [
            'conditions' => [
                'aluno_id'            => $aluno['Aluno']['id'],
                'estado_inscricao_id' => $this->Turma->Inscricao->estadoInscricoesAbertas,
            ],
            'fields'     => 'turma_id',
        ]);


        $conditions = [];
        $estadoTurma = $this->request->query('estado_turma');
        if ($estadoTurma) {
            $conditions['Turma.estado_turma_id'] = $estadoTurma;
        } else {
            $conditions['Turma.estado_turma_id'] = 1;
        }
        $paginationOptions = [];
        if ($this->request->is('post')) {

            if ($this->request->data['Turma']['codigo'] != '') {
                $conditions['Turma.codigo'] = $this->request->data['Turma']['codigo'];
            }
            if ($this->request->data['Turma']['name'] != '') {
                $conditions['Turma.name LIKE'] = '%' . $this->request->data['Turma']['name'] . '%';
            }
            if ($this->request->data['AnoLectivo']['ano'] != '') {
                $conditions['AnoLectivo.ano'] = $this->request->data['AnoLectivo']['ano'];
                $paginationOptions['url']['ano_lectivo'] = $this->request->data['AnoLectivo']['ano'];
            }
        }
        if ($this->request->is('ajax')) {
            if (isset($this->request->params['named']['ano_lectivo'])) {
                $conditions['AnoLectivo.ano'] = $this->request->params['named']['ano_lectivo'];
            }
        }

        $conditions['Turma.id'] = array_values($inscricoesActivas);

        $this->paginate = [
            'conditions' => $conditions,
            'contain'    => [
                'AnoLectivo',
                'Disciplina',
                'PlanoEstudo',
                'Curso'     => ['UnidadeOrganica'],
                'Inscricao' => [
                    'conditions' => [
                        'estado_inscricao_id' => 1,
                    ],
                ],
            ],
            'limit'      => 20,
            'order'      => 'Turma.created DESC',
        ];

        $estadoTurma = $this->Turma->EstadoTurma->findById($conditions['Turma.estado_turma_id']);

        $turmas = $this->paginate('Turma');
        $this->set(compact('turmas', 'paginationOptions', 'estadoTurma'));
    }

    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Security->unlockedActions = ['faculdade_criar_turma', 'criar_turma', 'actualizar_notas'];

        /* if($this->action == 'criar_turma') {
            $this->Security->csrfUseOnce = false; // We will use CSRF token for more than one time
        }*/

    }

    public function adicionar_estudante($turmaId)
    {

        if ($this->request->is('post')) {
            try {
                $this->Turma->adicionaEstudante($this->request->data);
                $this->Flash->success('Estudante Adicionardo Com Sucesso');
                $this->redirect(['action' => 'ver_turma', $turmaId]);
            } catch (Exception $e) {
                $this->Flash->error('Estudante não Adicionado. Motivo: ' . $e->getMessage());
            }
        }

        $this->Turma->contain(['Curso', 'Disciplina', 'AnoLectivo', 'SemestreLectivo']);
        $turma = $this->Turma->findById($turmaId);
        $tipoInscricaos = $this->Turma->Inscricao->TipoInscricao->find('list');

        $this->set(compact('turma', 'turmaId', 'tipoInscricaos'));
    }

    public function importar_notas_historico()
    {

    }

}

?>