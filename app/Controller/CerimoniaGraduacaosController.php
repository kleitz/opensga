<?php
    ini_set('max_execution_time', 300);
    App::uses('AppController', 'Controller');

    /**
     * CerimoniaGraduacaos Controller
     *
     * @property CerimoniaGraduacao $CerimoniaGraduacao
     * @property PaginatorComponent $Paginator
     */
    class CerimoniaGraduacaosController extends AppController
    {

        /**
         * Components
         *
         * @var array
         */
        public $components = ['Paginator'];

        /**
         * delete method
         *
         * @throws NotFoundException
         *
         * @param string $id
         *
         * @return void
         */
        public function delete($id = null)
        {
            $this->CerimoniaGraduacao->id = $id;
            if (!$this->CerimoniaGraduacao->exists()) {
                throw new NotFoundException(__('Invalid cerimonia graduacao'));
            }
            $this->request->onlyAllow('post', 'delete');
            if ($this->CerimoniaGraduacao->delete()) {
                $this->Session->setFlash(__('The cerimonia graduacao has been deleted.'));
            } else {
                $this->Session->setFlash(__('The cerimonia graduacao could not be deleted. Please, try again.'));
            }

            return $this->redirect(['action' => 'index']);
        }

        /**
         * edit method
         *
         * @throws NotFoundException
         *
         * @param string $id
         *
         * @return void
         */
        public function edit($id = null)
        {
            if (!$this->CerimoniaGraduacao->exists($id)) {
                throw new NotFoundException(__('Invalid cerimonia graduacao'));
            }
            if ($this->request->is('post') || $this->request->is('put')) {
                if ($this->CerimoniaGraduacao->save($this->request->data)) {
                    $this->Session->setFlash(__('The cerimonia graduacao has been saved.'));

                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Session->setFlash(__('The cerimonia graduacao could not be saved. Please, try again.'));
                }
            } else {
                $options = ['conditions' => ['CerimoniaGraduacao.' . $this->CerimoniaGraduacao->primaryKey => $id]];
                $this->request->data = $this->CerimoniaGraduacao->find('first', $options);
            }
        }

        public function estudante_index()
        {
            $this->CerimoniaGraduacao->recursive = 0;
            $this->set('cerimoniaGraduacaos', $this->Paginator->paginate());
        }

        public function estudante_inscricao()
        {

        }

        public function estudante_pagamento_inscricao()
        {

        }

        public function estudante_ver_detalhes($id = null)
        {
            if (!$this->CerimoniaGraduacao->exists($id)) {
                throw new NotFoundException(__('Cerimónia de Graduação Inválida'));
            }

            $options = ['conditions' => ['CerimoniaGraduacao.' . $this->CerimoniaGraduacao->primaryKey => $id]];
            $this->CerimoniaGraduacao->recursive = 0;
            $this->set('cerimoniaGraduacao', $this->CerimoniaGraduacao->find('first', $options));

            $this->CerimoniaGraduacao->CandidatoGraduacao->contain([
                'Aluno' => ['Entidade', 'Curso']
            ]);
            $candidatos = $this->CerimoniaGraduacao->CandidatoGraduacao->find('all',
                ['conditions' => ['cerimonia_graduacao_id' => $id]]);
            $this->set(compact('candidatos'));

        }

        /**
         * index method
         *
         * @return void
         */
        public function index()
        {
            $this->CerimoniaGraduacao->recursive = 0;
            $this->paginate = [
                'order' => 'data DESC'
            ];
            $this->set('cerimoniaGraduacaos', $this->Paginator->paginate());
        }

        /**
         * add method
         *
         * @return void
         */
        public function nova_cerimonia()
        {
            if ($this->request->is('post')) {
                $this->CerimoniaGraduacao->create();
                if ($this->CerimoniaGraduacao->save($this->request->data)) {
                    $this->Session->setFlash(__('A Cerimónia de Graduação foi Registrada com Sucesso'), 'default',
                        ['class' => 'alert success']);

                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Session->setFlash(__('Problemas ao Registrar a Cerimónia de Graduação, tente novamente'),
                        'default', ['class' => 'alert success']);
                }
            }
        }

        public function print_lista_graduandos($cerimonia_graduacao_id)
        {
            $this->CerimoniaGraduacao->CandidatoGraduacao->contain([
                'Aluno' => [
                    'Entidade' => [
                        'Genero',
                        'PaisNascimento',
                        'ProvinciaNascimento',
                        'User'
                    ],
                    'Curso'    => [
                        'UnidadeOrganica'
                    ]
                ]
            ]);
            $candidatos = $this->CerimoniaGraduacao->CandidatoGraduacao->find('all',
                ['conditions' => ['cerimonia_graduacao_id' => $cerimonia_graduacao_id]]);

            $candidatos2 = [];
            foreach ($candidatos as $candidato) {
                $is_regular = $this->CerimoniaGraduacao->CandidatoGraduacao->Aluno->isRegular($candidato['CandidatoGraduacao']['aluno_id']);
                if ($is_regular[0]['regular'] == true) {
                    $candidato['Aluno']['Status'] = 'Regular';
                } else {
                    $candidato['Aluno']['Status'] = 'Não Regular';
                }
                $candidatos2[] = $candidato;
            }
            $candidatos = $candidatos2;
            $this->set(compact('candidatos'));
            //debug($candidatos);
        }

        /**
         * view method
         *
         * @throws NotFoundException
         *
         * @param string $id
         *
         * @return void
         */
        public function ver_detalhes($id = null)
        {
            if (!$this->CerimoniaGraduacao->exists($id)) {
                throw new NotFoundException(__('Cerimónia de Graduação Inválida'));
            }

            $options = ['conditions' => ['CerimoniaGraduacao.' . $this->CerimoniaGraduacao->primaryKey => $id]];
            $this->CerimoniaGraduacao->recursive = 0;
            $this->set('cerimoniaGraduacao', $this->CerimoniaGraduacao->find('first', $options));

            $this->CerimoniaGraduacao->CandidatoGraduacao->contain([
                'Aluno' => ['Entidade', 'Curso']
            ]);
            $candidatos = $this->CerimoniaGraduacao->CandidatoGraduacao->find('all',
                ['conditions' => ['cerimonia_graduacao_id' => $id]]);
            $this->set(compact('candidatos'));

        }
    }
