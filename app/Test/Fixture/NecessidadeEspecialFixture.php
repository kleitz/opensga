<?php

/**
 * NecessidadeEspecial Fixture
 */
class NecessidadeEspecialFixture extends CakeTestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    public $fields = [
        'id' => [
            'type' => 'integer',
            'null' => false,
            'default' => null,
            'length' => 3,
            'unsigned' => true,
            'key' => 'primary',
        ],
        'name' => [
            'type' => 'string',
            'null' => false,
            'default' => null,
            'length' => 45,
            'collate' => 'latin1_swedish_ci',
            'charset' => 'latin1',
        ],
        'indexes' => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'],
    ];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'name' => 'Lorem ipsum dolor sit amet',
        ],
    ];

}
