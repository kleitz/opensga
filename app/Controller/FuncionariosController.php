<?php

/**
 * Controller do Funcionario
 *
 * @copyright       Copyright 2010-2011, INFOmoz (Inform�tica-Mo�ambique) (http://infomoz.net)
 * @link            http://opensga.com OpenSGA  - Sistema de Gestão Académica
 * @author          Elisio Leonardo (elisio.leonardo@gmail.com)
 * @package         opensga
 * @subpackage      opensga.core.controller
 * @since           OpenSGA v 0.10.0.0
 *
 * @property Funcionario $Funcionario
 *
 *
 */
class FuncionariosController extends AppController
{

    var $name = 'Funcionarios';

    function adicionar_funcionario()
    {
        if (!empty($this->data)) {
            try {
                $this->Funcionario->cadastraFuncionario($this->request->data);
                $this->Flash->sucess('Funcionario Registrado com Sucesso');
                $this->redirect(['action' => 'index']);
            } catch (Exception $e) {
                $this->Flash->error($e->getMessage());
            }

        }

        $grausAcademicos = $this->Funcionario->GrauAcademico->find('list');
        $tipofuncionarios = $this->Funcionario->TipoFuncionario->find('list');
        $documentoIdentificacaos = $this->Funcionario->Entidade->DocumentoIdentificacao->find('list');
        $unidadeOrganicas = $this->Funcionario->UnidadeOrganica->find('list');
        $paises = $this->Funcionario->Entidade->PaisNascimento->find('list');
        $cidades = $this->Funcionario->Entidade->CidadeNascimento->find('list');
        $provincias = $this->Funcionario->Entidade->ProvinciaNascimento->find('list');
        $generos = $this->Funcionario->Entidade->Genero->find('list');
        $estadoCivil = $this->Funcionario->Entidade->EstadoCivil->find('list');
        $naturalidade = '';
        $grauParentescos = '';
        $this->set(compact('grauParentescos', 'naturalidade', 'estadoCivil', 'grausAcademicos', 'tipofuncionarios',
            'departamentos', 'cargos', 'faculdades', 'seccaos', 'unidadeOrganicas', 'documentoIdentificacaos',
            'paises', 'cidades', 'provincias', 'generos'));
    }

    /**
     * @deprecated temos de ver se isso tem alguma valia
     */
    public function autocomplete()
    {

        $this->Funcionario->contain('Entidade');
        $funcionarios = $this->Funcionario->find('all', [
            'conditions' => ['Entidade.name LIKE' => "%" . $this->request->query['term'] . "%"],
            'fields' => ['CONCAT(Funcionario.codigo,"-",Entidade.name) as nome'],
        ]);

        $funcionarios2 = [];
        foreach ($funcionarios as $f) {
            $funcionarios2[] = $f[0]['nome'];
        }
        //debug($funcionarios);
        $this->set(compact('funcionarios2'));
    }

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    function beforeRender()
    {
        $this->set('current_section', 'administracao');
    }

    function delete($id = null)
    {
        //App::Import('Model','Logmv');
        //$logmv = new Logmv;
        App::Import('Model', 'User');
        $users = new User;
        App::Import('Model', 'Turma');
        $turmas = new Turma;

        if (!$id) {
            $this->Session->setFlash('Codigo Invalido para %s', 'flasherror');
            $this->redirect(['action' => 'index']);
        }

        $dados = $turmas->getTurmasByFuncionario($id);

        if (empty($dados)) {

            $user_id = $users->getUserByFuncionario($id);

            //var_dump($user_id[0]["us"]["id"]);
            $users = $users->deleteUser($user_id[0]["us"]["id"]);

            if ($this->Funcionario->delete($id)) {
                $this->Session->setFlash('Dados deletedos com sucesso ', 'flashok');
                $this->redirect(['action' => 'index']);
            }
        }
        $this->Session->setFlash('Nao e possivel apagar. Turmas associadas ao Funcionario', 'flasherror');
        $this->redirect(['action' => 'index']);
    }

    public function editar_funcao($funcionario_id)
    {
        $funcionario = $this->Funcionario->read(null, $funcionario_id);
        if ($this->request->is('post') || $this->request->is('put')) {

            if ($this->request->data['FuncionariosFuncaoProfissional']['nova_funcao_funcionario'] != "") {
                $funcao_existe = $this->Funcionario->FuncionariosFuncaoProfissional->FuncaoProfissional->findByName($this->request->data['FuncionariosFuncaoProfissional']['nova_funcao_funcionario']);
                if (empty($funcao_existe)) {
                    $array_nova_funcao = [
                        'FuncaoProfissional' => [
                            'name' => $this->request->data['FuncionariosFuncaoProfissional']['nova_funcao_funcionario'],
                        ],
                    ];
                    $this->Funcionario->FuncionariosFuncaoProfissional->FuncaoProfissional->create();
                    $this->Funcionario->FuncionariosFuncaoProfissional->FuncaoProfissional->save($array_nova_funcao);
                    $this->request->data['FuncionariosFuncaoProfissional']['funcao_profissional_id'] = $this->Funcionario->FuncionariosFuncaoProfissional->FuncaoProfissional->id;
                } else {
                    $this->request->data['FuncionariosFuncaoProfissional']['funcao_profissional_id'] = $funcao_existe['FuncaoProfissional']['id'];
                    $this->Funcionario->FuncionariosFuncaoProfissional->create();
                    $this->Funcionario->FuncionariosFuncaoProfissional->save($this->request->data);
                }
            } else {
                $this->Funcionario->FuncionariosFuncaoProfissional->create();
                $this->Funcionario->FuncionariosFuncaoProfissional->save($this->request->data);
            }


            $this->Session->setFlash(__("Função Profissional atribuida ao funcionário com sucesso"), 'default',
                ['class' => "alert success"]);
            $this->redirect([
                'action' => "perfil_funcionario",
                $this->request->data['FuncionariosFuncaoProfissional']['funcionario_id'],
            ]);
        }

        $funcaoProfissionals = $this->Funcionario->FuncionariosFuncaoProfissional->FuncaoProfissional->find('list');
        $this->set(compact('funcionario', 'funcaoProfissionals'));
    }

    function editar_funcionario($id = null)
    {
        $this->Funcionario->id = $id;
        if (!$this->Funcionario->exists()) {
            throw new NotFoundException('Funcionário Invalido');
        }
        //App::Import('Model','Logmv');
        //$logmv = new Logmv;
        if (!$id && empty($this->data)) {
            $this->Session->setFlash('Invalido %s', 'flasherror');
            $this->redirect(['action' => 'index']);
        }
        if (!empty($this->data)) {
            if ($this->Funcionario->save($this->data)) {
                //$logmv->logUpdate(16,$this->Session->read('Auth.User.id'),$id,$this->data['Funcionario']["name"]);
                $this->Session->setFlash('Dados Editados com sucesso', 'flashok');
                $this->redirect(['action' => 'index']);
            } else {
                $this->Session->setFlash('Erro ao editar dados. Por favor tente de novo.', 'flasherror');
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Funcionario->read(null, $id);
        }
        $users = $this->Funcionario->User->find('list');
        $grauacademicos = $this->Funcionario->Grauacademico->find('list');
        $paises = $this->Funcionario->Paise->find('list');
        $cidades = $this->Funcionario->Cidade->find('list');
        $provincias = $this->Funcionario->Provincia->find('list');
        $documentos = $this->Funcionario->DocumentoIdentificacao->find('list');

        $departamentos = $this->Funcionario->Departamento->find('list');
        $tipofuncionarios = $this->Funcionario->Tipofuncionario->find('list');
        $generos = $this->Funcionario->Genero->find('list');

        $departamento = $this->Funcionario->Departamento->find('list');
        $this->set(compact('users', 'Grauacademicos', 'Paises', 'Cidades', 'Provincias', 'DocumentoIdentificacaos',
            'tg0005cargos', 'tg0006departamentos', 'tg0011tipofuncionarios', 'generos', 'tg0005cargos',
            'tg0006departamento'));
    }

    public function importa_cenas()
    {
        App::import('Vendor', 'excel_reader2');
        $data = new Spreadsheet_Excel_Reader('i.xls', true);
        debug($data);
        $this->set('data', $data);
    }

    function index()
    {

        $conditions = [];
        if ($this->request->is('post')) {
            if ($this->request->data['Funcionario']['unidade_organica_id'] != '') {
                $conditions['Funcionario.unidade_organica_id'] =
                    $this->request->data['Funcionario']['unidade_organica_id'];
                $conditions['Entidade.nomes LIKE'] = '%' . $this->request->data['Funcionario']['nomes'] . '%';
                $conditions['Entidade.apelido LIKE'] = '%' . $this->request->data['Funcionario']['apelido'] . '%';
            } else {
                $conditions['Entidade.nomes LIKE'] = '%' . $this->request->data['Funcionario']['nomes'] . '%';
                $conditions['Entidade.apelido LIKE'] = '%' . $this->request->data['Funcionario']['apelido'] . '%';
            }
        }


        $this->paginate = [
            'conditions' => $conditions,
            'contain' => [
                'Entidade' => [
                    'User' => [
                        'Group',
                    ],
                ],
                'UnidadeOrganica',
            ],
        ];


        $unidadeOrganicas = $this->Funcionario->UnidadeOrganica->find('list');
        $this->set(compact('unidadeOrganicas'));
        $this->set('funcionarios', $this->paginate());
    }

    function perfil_funcionario($funcionarioId = null)
    {
        if (!$funcionarioId) {
            $this->Flash->error('Funcionario Invalido');
            $this->redirect(['action' => 'index']);
        }


        $funcionario = $this->Funcionario->getFuncionarioForPerfil($funcionarioId);


        $this->set(compact('funcionario'));
    }

    public function alterar_unidade_organica($funcionarioId)
    {


        if ($this->request->is('post')) {
            if ($this->Funcionario->alteraUnidadeOrganica($this->request->data) === true) {
                $this->Flash->success('Unidade Organica Alterada com Sucesso. As permissoes do Funcionario serao actualizadas');
                $this->redirect(['action' => 'perfil_funcionario', $funcionarioId]);
            } else {
                $this->Flash->error('Problemas ao Alterar Unidade Organica. Tente Novamente');
            }
        }
        $funcionario = $this->Funcionario->getFuncionarioForAction($funcionarioId);
        $unidadeOrganicas = $this->Funcionario->UnidadeOrganica->find('list');

        $this->set(compact('funcionario', 'unidadeOrganicas'));
    }

}

?>
