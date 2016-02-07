<?php
	App::uses('AppModel', 'Model');

	/**
	 * SmsNotificationType Model
	 *
	 * @property SmsNotification $SmsNotification
	 */
	class SmsNotificationType extends AppModel
	{


		//The Associations below have been created with all possible keys, those that are not needed can be removed

		/**
		 * hasMany associations
		 *
		 * @var array
		 */
		public $hasMany = [
			'SmsNotification' => [
				'className'    => 'SmsNotification',
				'foreignKey'   => 'sms_notification_type_id',
				'dependent'    => false,
				'conditions'   => '',
				'fields'       => '',
				'order'        => '',
				'limit'        => '',
				'offset'       => '',
				'exclusive'    => '',
				'finderQuery'  => '',
				'counterQuery' => '',
			],
		];

	}
