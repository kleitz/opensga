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
 ** @link          http://opensga.com OpenSGA  - Sistema de Gestão Académica
 * @author          Elisio Leonardo (elisio.leonardo@gmail.com)
 * @package       opensga
 * @subpackage    opensga.core.controller
 * @since         OpenSGA v 0.10.0.0
 *
 *
 * @property Provincia $Provincia
 * @property Pais $Pais
 * @Property Entidade $Entidade
 * @property Bairro $Bairro
 * @property Rua $Rua
 *
 */
class Cidade extends AppModel
{
    var $name = 'Cidade';
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $belongsTo = [
        'Provincia' => [
            'className' => 'Provincia',
            'foreignKey' => 'provincia_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'Pais' => [
            'className' => 'Pais',
            'foreignKey' => 'pais_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];

    var $hasMany = [
        'Entidade' => [
            'className' => 'Entidade',
            'foreignKey' => 'cidade_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ],
        'Bairro' => [
            'className' => 'Bairro',
            'foreignKey' => 'cidade_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ],
        'Rua' => [
            'className' => 'Rua',
            'foreignKey' => 'Cidade_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ],
    ];

    public function getProvinciaIdByCidadeId($cidadeId)
    {
        $this->id = $cidadeId;

        return $this->field('provincia_id');
    }

}

?>