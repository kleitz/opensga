<?php
App::uses('AppModel', 'Model');

/**
 * NotificationUser Model
 *
 * @property User $User
 * @property Notification $Notification
 * @property EstadoMessage $EstadoMessage
 */
class NotificationUser extends AppModel
{

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = [
        'user_id' => [
            'numeric' => [
                'rule' => ['numeric'],
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
    ];

    // The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = [
        'User' => [
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'Notification' => [
            'className' => 'Notification',
            'foreignKey' => 'notification_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
        'EstadoMessage' => [
            'className' => 'EstadoMessage',
            'foreignKey' => 'estado_message_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ],
    ];
}
