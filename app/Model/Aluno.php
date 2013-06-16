<?php

App::uses('AppModel', 'Model');

/**
 * Classe Model do Aluno
 *
 * @copyright     Copyright (C) 2010-2012, INFOmoz (Informática-Moçambique) (http://infomoz.net)
 * @link          http://infomoz.net/opensga OpenSGA - Sistema de Gestão Académica
 * @author		  Elisio Leonardo (elisio.leonardo@gmail.com)
 * @package       opensga
 * @subpackage    opensga.core.estudantes
 * @version       OpenSGA v 0.5.0
 * @since         OpenSGA v 0.1.0

 *
 * @property User $User
 * @property Matricula $Matricula
 * @property Inscricao $Inscricao
 * @property FinanceiroPagamento $FinanceiroPagamento
 *
 *
 */
class Aluno extends AppModel {

    var $name = 'Aluno';
    //var $recursive = 0;
    //The Associations below have been created with all possible keys, those that are not needed can be removed



    public $hasOne = array(
        'AlunoNivelMedio' => array(
            'className' => 'AlunoNivelMedio',
            'dependent' => true
        )
    );
    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Entidade' => array(
            'className' => 'Entidade',
            'foreignKey' => 'entidade_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Curso' => array(
            'className' => 'Curso',
            'foreignKey' => 'curso_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Areatrabalho' => array(
            'className' => 'Areatrabalho',
            'foreignKey' => 'areatrabalho_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'EstadoAluno' => array(
            'className' => 'EstadoAluno',
            'foreignKey' => 'estado_aluno_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    
    );
    var $hasMany = array(
        'Matricula' => array(
            'className' => 'Matricula',
            'foreignKey' => 'aluno_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => 'data',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Inscricao' => array(
            'className' => 'Inscricao',
            'foreignKey' => 'aluno_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'FinanceiroPagamento' => array(
            'className' => 'FinanceiroPagamento',
            'foreignKey' => 'aluno_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'RequisicoesPedido' => array(
            'className' => 'RequisicoesPedido',
            'foreignKey' => 'aluno_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    /*
    public $validate = array(
        'curso_id' => array(
            'rule' => 'notempty', // or: array('ruleName', 'param1', 'param2' ...)
            'required' => true,
            'allowEmpty' => false,
            'on' => 'create', // or: 'update'
            'message' => 'Curso é um campo obrigatório'
        ),
        'turno_id' => array(
            'rule' => 'notempty',
            'message' => 'campo obrigatorio'
        )
    );
*/
    /**
     * Retorna o plano de estudos activo do aluno
     * @param int $id
     * @return int
     */
    function getPlanoEstudoCorrente($id) {
        App::import('Model', 'Matricula');
        $matriculas = new Matricula;
        $matriculas->recursive = -1;

        $matricula = $matriculas->find('first', array('conditions' => array('estadomatricula_id' => 1, 'Aluno_id' => $id)));

        $plano_estudo = $matricula['Matricula']['planoestudo_id'];
        return $plano_estudo;
    }

    /**
     * Retorna as cadeiras em que o aluno esta inscrito actualmente
     * @return array com ID das disciplinas activas do aluno
     */
    public function getAllInscricoesActivas($aluno_id) {

        $this->Inscricao->contain('Turma');
        $inscricoes = $this->Inscricao->find('all', array('conditions' => array('Inscricao.estadoinscricao_id' => 1, 'Inscricao.aluno_id' => $aluno_id), 'recursive' => 0, 'fields' => array('Turma.disciplina_id')));

        $disciplinas = Hash::extract($inscricoes, '{n}.Turma.disciplina_id');
        return $disciplinas;
    }

    /**
     * Retorna todas as cadeiras que o aluno ja aprovou para a inscricao
     * @return type
     */
    public function getAllInscricoesActivasAndAprovadasForInscricao($aluno_id) {
        $this->Inscricao->contain('Turma');
        $inscricoes = $this->Inscricao->find('all', array('conditions' => array('Inscricao.estadoinscricao_id' => array(1, 2), 'Inscricao.aluno_id' => $aluno_id), 'recursive' => 0, 'fields' => array('Turma.disciplina_id')));

        $disciplinas = Hash::extract($inscricoes, '{n}.Turma.disciplina_id');
        return $disciplinas;
    }

    /**
     * Funcao generica para retornar todas as turmas de um aluno com base no estado da turma
     * @Todo Testar :-)
     */
    function getAllTurmasByEstado($aluno, $estado = 1) {
        App::import('Model', 'Turma');
        App::import('Model', 'Inscricao');
        App::import('Model', 'Matricula');

        $turma = new Turma;
        $inscricao = new Inscricao;
        $Matricula = new Matricula;

        $plano_estudo = $this->query("Select planoestudo_id from matriculas where aluno_id = {$aluno}");
        $turma->recursive = -1;

        $turmas = $turma->getAllTurmasActivasByPlanoEstudo($plano_estudo[0]['matriculas']['planoestudo_id']);

        return $turmas;
    }

    function getAllTurmasByAluno($aluno_id, $estado = 1) {
        App::import('Model', 'Turma');
        App::import('Model', 'Inscricao');
        App::import('Model', 'Matricula');

        $turma = new Turma;
        $inscricao = new Inscricao;
        $Matricula = new Matricula;

        $plano_estudo = $this->query("Select planoestudo_id from matriculas where aluno_id = {$aluno}");
        $turma->recursive = -1;
        $turmas = $turma->getAllTurmasByAluno($plano_estudo[0]['matriculas']['planoestudo_id']);
        return $turmas;
    }

    // function getAllTurmasCompletas

    function geraCodigo() {
        $ano = date('Y');
        $this->recursive = -1;
        $id = $this->find('count', array('conditions' => array('Aluno.codigo LIKE' => '%' . $ano . '%'), 'order' => array('Aluno.created DESC'), 'fields' => 'id'));
        $id_for = str_pad($id + 1, 4, "0", STR_PAD_LEFT);
        $codigo = $ano . $id_for;

        return $codigo;
    }

    /**
     * Retorna a conta do aluno
     * @param type $aluno_id
     */
    public function getContaByAlunoId($aluno_id) {
        $this->id = $aluno_id;
        $entidade_id = $this->field('entidade_id');

        $this->Entidade->FinanceiroConta->recursive = -1;
        $conta = $this->Entidade->FinanceiroConta->findByEntidadeId($entidade_id);

        return $conta;
    }

    /**
     * Retorna o Id da conta do aluno
     * @param type $aluno_id
     */
    public function getContaIdByAlunoId($aluno_id) {
        $conta = $this->getContaByAlunoId($aluno_id);
        return $conta['FinanceiroConta']['id'];
    }

    /**
     * Devolve todas as turmas que o aluno pode se inscrever, baseado nas cadeiras feitas e precedencias
     * @Todo por enquanto as precedencias e cadeiras feitas nao sao processadas
     *
     *
     * @todo falta isso
     */
    function getAllTurmasForInscricao() {

    }

    public function getAllTurmasNormaisForInscricao($aluno_id) {


        //Primeiro, pegar a ultima matricula deste aluno, se nao for deste ano, mandar matricular
        if (!$matricula = $this->getMatriculaCorrente($aluno_id)) {
            return false;
        }

        $this->Inscricao->contain(array(
            'Turma'
        ));
        $turmas_aprovadas = $this->Inscricao->find('all', array('conditions' => array('Inscricao.aluno_id' => $aluno_id)));

        //$matricula -
        debug($turmas_aprovadas);
    }

    public function getMatriculaCorrente($aluno_id) {
        return $this->Matricula->find('first', array('conditions' => array('Matricula.aluno_id' => $aluno_id, 'Matricula.anolectivo_id' => Configure::read('OpenSGA.ano_lectivo_id'), 'Matricula.estadomatricula_id' => 1), 'recursive' => -1));
    }

    /**
     * Retorna o nivel academico mais elevado que o aluno ja frequentou.
     * Esta funcao é importante para se verificar o que sao cadeiras normais e o que sao cadeiras em atraso
     *
     * @FIXME Pode nao dar certos em instituicoes que permitem puxar cadeiras
     * @todo Implementar e testar
     * @param type $aluno_id
     */
    public function getNivelAcademicoElevado($aluno_id) {
        $this->Inscricao->contain('Turma');
        $todas_inscricoes = $this->Inscricao->find('all', array('conditions' => array('aluno_id' => $aluno_id)));
        // debug($todas_inscricoes);
    }

    /**
     * Retorna o Nivel Academico Real do aluno em questao, tendo em conta os criterios da universidade para a definicao de nivel academico
     *
     * @todo implementar e testar
     * @param type $aluno_id
     */
    public function getNivelAcademicoReal($aluno_id) {

    }

    /**
     *Regista os dados do Aluno no Sistema e faz a primeira matricula
     * @param array $data
     * @return type
     */
    public function cadastraAluno(array $data) {
        $dataSource = $this->getDataSource();

        $dataSource->begin();

        $data_matricula = array();
        if ($data['Aluno']['numero_estudante'] == '') {
            $data['Aluno']['codigo'] = $this->geraCodigo();
        } else {
            $data['Aluno']['codigo'] = $data['Aluno']['numero_estudante'];
            $data['Aluno']['numero_estudante_antigo'] = $data['Aluno']['numero_estudante'];
        }

        //Grava os dados do Usuario
        $this->User->create();
        $data['User']['username'] = $data['Aluno']['codigo'];
        $data['User']['password'] = Security::hash($data['Aluno']['codigo'],'blowfish');
        $data['User']['codigocartao'] = $data['Aluno']['codigo'];
        $data['User']['name'] = $data['Entidade']['name'];
        $data['User']['group_id'] = 3;
        if ($this->User->save($data)) {
            //Grava os dados da Entidade
            $data['Aluno']['user_id'] = $this->User->getLastInsertID();
            $data['Entidade']['user_id'] = $this->User->getLastInsertID();
            $this->Entidade->create();
            if ($this->Entidade->save($data)) {

                //Grava os dados do Aluno
                $data['Aluno']['entidade_id'] = $this->Entidade->getLastInsertID();
                $this->create();
                if ($this->save($data)) {
                    //Grava os dados de Identificacao

                    $identificacao = array('EntidadeIdentificacao'=>$data['EntidadeIdentificacao']);
                    $identificacao['EntidadeIdentificacao']['entidade_id'] = $this->Entidade->getLastInsertID();
                    $identificacao['EntidadeIdentificacao']['estado_objecto_id'] = 1;

                    $this->Entidade->EntidadeIdentificacao->create();
                    $this->Entidade->EntidadeIdentificacao->save($identificacao);


                    //Grava os dados de Morada e Contactos

                    $contactos = $data['EntidadeContacto'];
                    foreach($contactos as $k=>$v){
                        $this->Entidade->EntidadeContacto->create();
                        $this->Entidade->EntidadeContacto->save(
                                array(
                                    'EntidadeContacto'=>array(
                                        'entidade_id'=>  $this->Entidade->getLastInsertID(),
                                        'tipo_contacto_id'=>$k,
                                        'valor'=>$v,
                                        'estado_objecto_id'=>1
                                        )
                                    )
                                );
                    }

                    //Pega os dados da matricula e realiza a matricula
                    $data_matricula['aluno_id'] = $this->getInsertID();
                    $data_matricula['curso_id'] = $data['Aluno']['curso_id'];
                    $data_matricula['planoestudo_id'] = $data['Aluno']['planoestudo_id'];
                    $data_matricula['estadomatricula_id'] = 1;
                    $data_matricula['data'] = $data['Aluno']['dataingresso'];
                    $data_matricula['user_id'] = SessionComponent::read('Auth.User.id');
                    $data_matricula['anolectivo_id'] = SessionComponent::read('SGAConfig.anolectivo_id');
                    $data_matricula['turno_id'] = $data['Aluno']['turno_id'];
                    $data_matricula['nivel'] = $data['Aluno']['nivel'];
                    $data_matricula['tipo_matricula_id'] = 1;
                    if (Configure::read('OpenSGA.matriculas.regimes' == 2)) {
                        $data_matricula['regimelectivo_id'] == $data['Aluno']['regimelectivo_id'];
                    }

                    $matricula_gravar = array('Matricula' => $data_matricula);
                    $this->Matricula->create();
                    if ($this->Matricula->save($matricula_gravar)) {

                        return $dataSource->commit();
                    }
                }
            }
        }


        $dataSource->rollback();
    }

    public function isBolseiro($aluno_id, $ano_lectivo_id = null) {

        //$this->BolsaBolsa->find('first');
        return TRUE;
    }

    /**
     * Verifica se o aluno esta matriculado
     */
    public function isMatriculado($aluno_id, $anolectivo_id) {
        $matricula = $this->Matricula->find('first', array('conditions' => array('aluno_id' => $aluno_id, 'anolectivo_id' => $anolectivo_id), 'recursive' => -1));
        return $matricula;
    }

    public function setNovaMatricula($data) {
        if (empty($data['Matricula']['anolectivo_id'])) {
            $data['Matricula']['anolectivo_id'] = $data['Sessao']['anolectivo_id'];
        }

        $data['Matricula']['user_id'] = $data['Sessao']['user_id'];
        $data['Matricula']['aluno_id'] = $this->id;
        if ($this->isMatriculado($this->id, $data['Matricula']['user_id'])) {
            return false;
        }
        if ($this->Matricula->save($data)) {
            return true;
        }
        return false;
    }

    public function getPerfilAluno() {

    }

    /**
     * Retorna o numero de estudantes por faculdade
     */
    public function getEstudantesByFaculdade() {
        $alunos_faculdade = $this->find('all', array('conditions' => array(), 'group' => 'faculdade_ingresso_id', 'fields' => array('Count(*) as total', 'Faculdade.name')));
        return $alunos_faculdade;
    }

    public function getEstudantesByCurso($curso_id) {
        $alunos_curso = $this->find('all', array('conditions' => array('Aluno.curso_ingresso_id' => $curso_id)));


        return $alunos_curso;
    }

    public function getTotalAlunos() {
        return $this->find('count');
    }

    /**
     * Verifica se o aluno possui foto no Sistema
     * @param type $codigo
     */
    public function hasFoto($codigo) {
        App::uses('File', 'Utility');

        $path = APP . 'Assets' . DS . 'Fotos' . DS . 'Estudantes' . DS;
        $file = new File($path . $codigo . '.jpg');
        if (!$file->exists()) {
            return false;
        }
        return true;
    }
    
    /**
     * Verifica se um determinado aluno é regular. Para tal, basta verificar se todas as matriculas foram renovadas ate ao presente ano, verificar a situacao da conclusao e o status na tabela alunos
     * @param type $id_aluno
     * @return array Um array contendo o status e o motivo
     */
    function isRegular($aluno_id) {
        $this->contain('EstadoAluno');
        $aluno = $this->findById($aluno_id);
        
        if($aluno['Aluno']['estado_aluno_id']==1){
            $renovacoes = $this->Matricula->getStatusRenovacao($aluno_id);
            if(empty($renovacoes)){
                return array("estado"=>1,"mensagem"=>'Estudante Regular');
            } else{
                $string_retorno = __("Não renovou matricula nos seguintes anos: ");
                foreach($renovacoes as $renovacao){
                    $string_retorno.=$renovacao['Anolectivo']['ano'].", ";
                }
                return array("estado"=>2,"mensagem"=>$string_retorno);
            }
        }
        if($aluno['Aluno']['estado_aluno_id']==3){
            $renovacoes = $this->Matricula->getStatusRenovacao($aluno_id);
            if(empty($renovacoes)){
                return array("estado"=>3,"mensagem"=>'Concluiu o Nivel');
            } else{
                $string_retorno = __("Concluiu o nível, mas não renovou matricula nos seguintes anos: ");
                foreach($renovacoes as $renovacao){
                    $string_retorno.=$renovacao['Anolectivo']['ano'].", ";
                }
                return array("estado"=>4,"mensagem"=>$string_retorno);
            }
        }
        
        
        return array("estado"=>5,"mensagem"=>$aluno['EstadoAluno']['name']);
    }

}
