<?php
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

class GJMAA_Model_Attachments extends GJMAA_Model
{

    protected $tableName = 'gjmaa_attachments';

    protected $defaultPK = 'id';

    protected $model = 'attachments';


    protected $columns = [
        'id'        => [
            'schema' => [
                'INT',
                'AUTO_INCREMENT',
                'NOT NULL'
            ],
            'format' => '%d'
        ],
        'attach_id' => [
            'schema' => [
                'BIGINT',
                'NOT NULL'
            ],
            'format' => '%f'
        ],
        'destination_path' => [
            'schema' => [
                'VARCHAR (255)',
                'NOT NULL'
            ],
            'format' => '%s'
        ]
    ];

    public function update($currentVersion)
    {
        if (version_compare($currentVersion, '3.2.0') < 0) {
            $this->install();
        }
    }

    public function deleteById($id)
    {
        $whereData = [
            $this->getDefaultPk() => $id
        ];

        $this->getWpdb()->delete($this->getTable(), $whereData, $this->parseFormat($whereData));
    }
}
