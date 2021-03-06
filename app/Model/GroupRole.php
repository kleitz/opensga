<?php
App::uses('AppModel', 'Model');

/**
 * GroupRole Model
 *
 * @property Group $Group
 * @property Role $Role
 * @property EstadoObjecto $EstadoObjecto
 */
class GroupRole extends AppModel
{


    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = [
        'Group' => [
            'className' => 'Group',
            'foreignKey' => 'group_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'Role' => [
            'className' => 'Role',
            'foreignKey' => 'role_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'EstadoObjecto' => [
            'className' => 'EstadoObjecto',
            'foreignKey' => 'estado_objecto_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];
}
