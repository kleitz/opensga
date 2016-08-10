<?php

/**
 * OpenSGA - Sistema de Gest�o Acad�mica
 *   Copyright (C) 2010-2011  INFOmoz (Inform�tica-Mo�ambique)
 *
 * Este programa � um software livre: Voc� pode redistribuir e/ou modificar
 * todo ou parte deste programa, desde que siga os termos da licen�a por nele
 * estabelecidos. Grande parte do c�digo deste programa est� sob a licen�a
 * GNU Affero General Public License publicada pela Free Software Foundation.
 * A vers�o original desta licen�a est� dispon�vel na pasta raiz deste software.
 *
 * Este software � distribuido sob a perspectiva de que possa ser �til para
 * satisfazer as necessidades dos seus utilizadores, mas SEM NENHUMA GARANTIA. Veja
 * os termos da licen�a GNU Affero General Public License para mais detalhes
 *
 * As redistribui��es deste software, mesmo quando o c�digo-fonte for modificado significativamente,
 * devem manter est� informa��o legal, assim como a licen�a original do software.
 *
 * @copyright     Copyright 2010-2011, INFOmoz (Inform�tica-Mo�ambique) (http://infomoz.net)
 * * @link          http://opensga.com OpenSGA  - Sistema de Gestão Académica
 * @author          Elisio Leonardo (elisio.leonardo@gmail.com)
 * @package       opensga
 * @subpackage    opensga.core.controller
 * @since         OpenSGA v 0.10.0.0
 *
 */
class AvaliacaosController extends AppController
{

    var $name = 'Avaliacaos';
    var $components = ['Session'];

    function index()
    {

        $this->set('avaliacaos', $this->paginate());
    }

    function docente_index()
    {
        $userId = $this->Session->read('Auth.User.id');
        $docente = $this->Avaliacao->TurmaTipoAvaliacao->Turma->DocenteTurma->Docente->getByUserID($userId);
        $avaliacaos = $this->Avaliacao->getAllByDocente($docente['Docente']['id']);

        $this->set('avaliacaos', $this->paginate());


    }

    /**
     * Mostra o formulario para que se possa introduzir as notas dos estudantes
     * @param type $id
     * @todo verificar se o find de inscricoes apenas retorna 1 avaliacao, rerente a este tipo de avaliacao
     */
    function docente_ver_avaliacao($turmaTipoAvaliacaoId = null)
    {
        $this->Avaliacao->TurmaTipoAvaliacao->id = $turmaTipoAvaliacaoId;
        if (!$this->Avaliacao->TurmaTipoAvaliacao->exists()) {
            throw new NotFoundException(__('Avaliacao Invalida'));
        }


        if ($this->request->is('post')) {
            try {
                $this->Avaliacao->gravaNotas($this->request->data);
                $this->Flash->success('Notas Gravadas com Sucesso');
            } catch (DataNotSavedException $e) {
                $this->Flash->error($e->getMessage());
            }
        }
        $this->Avaliacao->TurmaTipoAvaliacao->contain([
            'Turma' => [
                'AnoLectivo',
                'Curso' => [
                    'UnidadeOrganica',
                ],
                'Disciplina',
                'AnoLectivo',
            ],
            'TipoAvaliacao',
        ]);
        $turmaTipoAvaliacao = $this->Avaliacao->TurmaTipoAvaliacao->read(null, $turmaTipoAvaliacaoId);
        $docente = $this->Avaliacao->TurmaTipoAvaliacao->Turma->DocenteTurma->Docente->getByUserId($this->Session->read('Auth.User.id'));

        if (!$this->Avaliacao->TurmaTipoAvaliacao->Turma->isDocente($turmaTipoAvaliacao['Turma']['id'],
            $docente['Docente']['id'])
        ) {
            $this->Session->SetFlash('Nao tem permissao para aceder a pagina anterior');
            $this->redirect(['action' => 'index']);
        }

        $this->Avaliacao->TurmaTipoAvaliacao->Turma->Inscricao->contain([
            'EstadoInscricao',
            'Matricula' => [
                'Aluno' => [
                    'Entidade' => [
                        'User',
                    ],
                    'Avaliacao' => [
                        'conditions' => [
                            'turma_tipo_avaliacao_id' => $turmaTipoAvaliacaoId,
                            'estado_avaliacao_id' => 1,
                        ],
                    ],
                ],
            ],
            'Turma' => [
                'Curso' => [
                    'fields' => ['name'],
                ],
                'Disciplina',
                'Turno',
                'AnoLectivo',
            ],
        ]);
        $inscricaos = $this->Avaliacao->TurmaTipoAvaliacao->Turma->Inscricao->find('all',
            ['conditions' => ['turma_id' => $turmaTipoAvaliacao['Turma']['id']]]);

        $disabled = false;
        if ($turmaTipoAvaliacao['TurmaTipoAvaliacao']['estado_turma_avaliacao_id'] == 5) {
            $disabled = 'disabled';
        }

        $this->set(compact('inscricaos', 'turmaTipoAvaliacao', 'docente', 'disabled'));
    }

    public function faculdade_ver_avaliacao($turmaTipoAvaliacaoId = null)
    {
        $this->Avaliacao->TurmaTipoAvaliacao->id = $turmaTipoAvaliacaoId;
        if (!$this->Avaliacao->TurmaTipoAvaliacao->exists()) {
            throw new NotFoundException(__('Avaliacao Invalida'));
        }

        $this->Avaliacao->TurmaTipoAvaliacao->contain([
            'Turma' => [
                'AnoLectivo',
                'Curso' => [
                    'UnidadeOrganica',
                ],
                'Disciplina',
                'AnoLectivo',
            ],
            'TipoAvaliacao',
        ]);
        $turmaTipoAvaliacao = $this->Avaliacao->TurmaTipoAvaliacao->read(null, $turmaTipoAvaliacaoId);
        $userId = $this->Session->read('Auth.User.id');
        if (!$this->Avaliacao->Aluno->Entidade->Funcionario->isFromUnidadeOrganica($userId,
            $turmaTipoAvaliacao['Turma']['Curso']['unidade_organica_id'])
        ) {
            $this->Session->setFlash('Nao tem permissao para aceder a pagina anterior');
            $this->redirect('/');
        }

        $this->Avaliacao->TurmaTipoAvaliacao->Turma->Inscricao->contain([
            'EstadoInscricao',
            'Matricula' => [
                'Aluno' => [
                    'Entidade' => [
                        'User',
                    ],
                    'Avaliacao' => [
                        'conditions' => [
                            'turma_tipo_avaliacao_id' => $turmaTipoAvaliacaoId,
                            'estado_avaliacao_id' => 1,
                        ],
                    ],
                ],
            ],
            'Turma' => [
                'Curso' => [
                    'fields' => ['name'],
                ],
                'Disciplina',
                'Turno',
                'AnoLectivo',
            ],
        ]);
        $inscricaos = $this->Avaliacao->TurmaTipoAvaliacao->Turma->Inscricao->find('all',
            ['conditions' => ['turma_id' => $turmaTipoAvaliacao['Turma']['id']]]);

        $this->set(compact('inscricaos', 'turmaTipoAvaliacao', 'docente'));
    }

    public function faculdade_editar_notas_avaliacao($turmaTipoAvaliacaoId = null)
    {
        $this->Avaliacao->TurmaTipoAvaliacao->id = $turmaTipoAvaliacaoId;
        if (!$this->Avaliacao->TurmaTipoAvaliacao->exists()) {
            throw new NotFoundException(__('Avaliacao Invalida'));
        }

        $this->Avaliacao->TurmaTipoAvaliacao->contain([
            'Turma' => [
                'AnoLectivo',
                'Curso' => [
                    'UnidadeOrganica',
                ],
                'Disciplina',
                'AnoLectivo',
            ],
            'TipoAvaliacao',
        ]);
        $turmaTipoAvaliacao = $this->Avaliacao->TurmaTipoAvaliacao->read(null, $turmaTipoAvaliacaoId);
        $userId = $this->Session->read('Auth.User.id');
        if (!$this->Avaliacao->Aluno->Entidade->Funcionario->isFromUnidadeOrganica($userId,
            $turmaTipoAvaliacao['Turma']['Curso']['unidade_organica_id'])
        ) {
            $this->Session->setFlash('Nao tem permissao para aceder a pagina anterior');
            $this->redirect('/');
        }

        if ($this->request->is('post')) {
            if ($this->Avaliacao->editaNotasAvaliacao($this->request->data)) {
                $this->Session->setFlash('Dados Processados com sucesso');
                $this->redirect(['action' => 'ver_avaliacao', $turmaTipoAvaliacaoId]);
            }
        }

        $this->Avaliacao->TurmaTipoAvaliacao->Turma->Inscricao->contain([
            'EstadoInscricao',
            'Matricula' => [
                'Aluno' => [
                    'Entidade' => [
                        'User',
                    ],
                    'Avaliacao' => [
                        'conditions' => [
                            'turma_tipo_avaliacao_id' => $turmaTipoAvaliacaoId,
                            'estado_avaliacao_id' => 1,
                        ],
                    ],
                ],
            ],
            'Turma' => [
                'Curso' => [
                    'fields' => ['name'],
                ],
                'Disciplina',
                'Turno',
                'AnoLectivo',
            ],
        ]);
        $inscricaos = $this->Avaliacao->TurmaTipoAvaliacao->Turma->Inscricao->find('all',
            ['conditions' => ['turma_id' => $turmaTipoAvaliacao['Turma']['id']]]);

        $this->set(compact('inscricaos', 'turmaTipoAvaliacao', 'docente'));
    }

    public function faculdade_publicar_avaliacao()
    {

    }

    public function faculdade_print_pauta_avaliacao()
    {

    }

}

?>