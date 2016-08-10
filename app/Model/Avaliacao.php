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
 * @property $TurmaTipoAvaliacao TurmaTipoAvaliacao
 * @property $Aluno Aluno
 */
class Avaliacao extends AppModel
{

    var $name = 'Avaliacao';
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $belongsTo = [
        'TurmaTipoAvaliacao' => [
            'className' => 'TurmaTipoAvaliacao',
            'foreignKey' => 'turma_tipo_avaliacao_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'Aluno' => [
            'className' => 'Aluno',
            'foreignKey' => 'aluno_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];

    public function editaNotasAvaliacao($data)
    {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
        foreach ($data['Avaliacao'] as $avaliacao) {
            $avaliacaoExiste = $this->findByAlunoIdAndTurmaTipoAvaliacaoId($avaliacao['aluno_id'],
                $avaliacao['turma_tipo_avaliacao_id']);
            if ($avaliacaoExiste) {
                $avaliacao['id'] = $avaliacaoExiste['Avaliacao']['id'];
                $this->save(['Avaliacao' => $avaliacao]);
            } else {
                $this->create();
                $this->save(['Avaliacao' => $avaliacao]);
            }
        }

        return $dataSource->commit();
    }

    //Devolve o ano lectivo
    function getAnoLectivo($ano_lectivo_id)
    {
        $query = "select codigo from anolectivos where id = {$ano_lectivo_id}";
        //var_dump($query);
        $resultado = $this->query($query);

        return $resultado;
    }

    //Devolve o Curso
    function getCurso($curso_id)
    {
        $query = "select name from cursos where id = {$curso_id}";
        $resultado = $this->query($query);

        //var_dump($resultado);
        return $resultado;
    }

    //Devolve o Plano
    function getPlano($plano_estudo_id)
    {
        $query = "select name from t0005planoestudos where id = {$plano_estudo_id}";
        $resultado = $this->query($query);

        //var_dump($resultado);
        return $resultado;
    }

    function getTurma($turma_id)
    {
        $query = "select name from turmas where id = {$turma_id}";
        $resultado = $this->query($query);

        //var_dump($resultado);
        return $resultado;
    }

    function getEpocaAvaliacaos($EpocaAvaliacao_id)
    {
        $query = "select name from EpocaAvaliacaos where id = {$EpocaAvaliacao_id}";
        $resultado = $this->query($query);

        //var_dump($resultado);
        return $resultado;
    }

    function getTipoAvaliacaos($tipoavaliacao_id)
    {
        $query = "select name from tipoavaliacaos where id = {$tipoavaliacao_id}";
        $resultado = $this->query($query);

        //var_dump($resultado);
        return $resultado;
    }

    public function getAllByDocente($docenteId){
        $turmas = $this->TurmaTipoAvaliacao->Turma->getAllByDocente($docenteId);
        $turmaIds = Hash::extract($turmas,'{n}.Turma.id');

        $options['joins'] = array(
            array('table' => 'turma_tipo_avaliacaos',
                  'alias' => 'TurmaTipoAvaliacao',
                  'type' => 'LEFT',
                  'conditions' => array(
                      'TurmaTipoAvaliacao.id = Avaliacao.turma_tipo_avaliacao_id',
                  )
            ),
            array('table' => 'turmas',
                  'alias' => 'Turma',
                  'type' => 'LEFT',
                  'conditions' => array(
                      'Turma.id = TurmaTipoAvaliacao.turma_id',
                  )
            ),
            array('table' => 'cursos',
                  'alias' => 'Curso',
                  'type' => 'LEFT',
                  'conditions' => array(
                      'Curso.id = Turma.curso_id',
                  )
            ),
            array('table' => 'disciplinas',
                  'alias' => 'Disciplina',
                  'type' => 'LEFT',
                  'conditions' => array(
                      'Disciplina.id = Turma.disciplina_id',
                  )
            ),
            array('table' => 'unidade_organicas',
                  'alias' => 'UnidadeOrganica',
                  'type' => 'LEFT',
                  'conditions' => array(
                      'UnidadeOrganica.id = Curso.unidade_organica_id',
                  )
            ),
        );
        $options['fields']= '*';
        $avaliacaos  = $this->find('all', $options);
        debug($this->getLog());
        debug($avaliacaos);
        die();
    }

    function ifExist($inscricao_id)
    {
        $query = "select inscricao_id from avaliacaos where  inscricao_id = {$inscricao_id}";
        $resultado = $this->query($query);

        //var_dump($query);
        return $resultado;
    }

    function update_plano()
    {
        App::Import('Model', 'PlanoEstudo');
        $t005planoestudos = new PlanoEstudo;

        //select tp.name from t0005planoestudos tp,t0003cursos tc where tp.t0003curso_id = tc.id and tp.t0003curso_id = 1

        $planoestudo = $t005planoestudos->find('all',
            ['conditions' => ['Aluno_id' => $this->data['Inscricao']['Aluno_id']]]);
        $plano = $planoestudo[0]['PlanoEstudo'];
        $this->set('plano', $plano);
        $this->layout = 'ajax';
    }



    public function gravaNotas($data)
    {
        foreach ($data['Avaliacao'] as $avaliacao) {
            $avaliacaoExiste = $this->findByAlunoIdAndTurmaTipoAvaliacaoId($avaliacao['aluno_id'],
                $avaliacao['turma_tipo_avaliacao_id']);
            $turmaTipoAvaliacaoId = $avaliacao['turma_tipo_avaliacao_id'];
            $avaliacaoUpdate = [
                'Avaliacao' => $avaliacao
            ];
            if ($avaliacaoExiste) {
                $this->id = $avaliacaoExiste['Avaliacao']['id'];
                if (!$this->save($avaliacaoUpdate)) {
                    throw new DataNotSavedException($this->validationErrors);
                }
            } else {
                $this->create();
                if (!$this->save($avaliacaoUpdate)) {
                    throw new DataNotSavedException($this->validationErrors);
                }
            }
        }
        if ($data['metodo'] == 'PUBLICAR') {
            $this->TurmaTipoAvaliacao->id = $turmaTipoAvaliacaoId;
            $this->TurmaTipoAvaliacao->set('estado_turma_avaliacao_id', 5);
            if (!$this->TurmaTipoAvaliacao->save()) {
                throw new DataNotSavedException($this->TurmaTipoAvaliacao->validationErrors);
            }
        } else {
            $this->TurmaTipoAvaliacao->id = $turmaTipoAvaliacaoId;
            $this->TurmaTipoAvaliacao->set('estado_turma_avaliacao_id', 2);
            if (!$this->TurmaTipoAvaliacao->save()) {
                throw new DataNotSavedException($this->TurmaTipoAvaliacao->validationErrors);
            }
        }
        return true;
    }

}

?>