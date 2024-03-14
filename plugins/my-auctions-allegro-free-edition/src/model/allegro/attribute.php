<?php
declare(strict_types=1);
/**
 * My auctions allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */
class GJMAA_Model_Allegro_Attribute extends GJMAA_Model
{
	protected $tableName = 'gjmaa_allegro_attributes';

	protected $defaultPK = 'attribute_id';

	protected $model = 'allegro_attribute';

	protected $columns = [
		'attribute_id' => [
			'schema' => [
				'INT',
				'AUTO_INCREMENT',
				'NOT NULL'
			],
			'format' => '%d',
            'update' => false
		],
		'attribute_category_allegro_id' => [
			'schema' => [
				'TEXT',
				'NULL'
			],
			'format' => '%s',
            'update' => false
		],
		'attribute_allegro_id' => [
			'schema' => [
				'INT',
				'NOT NULL'
			],
			'format' => '%d',
            'update' => false
		],
		'attribute_name' => [
			'schema' => [
				'VARCHAR (255)',
				'NOT NULL'
			],
			'format' => '%s',
            'update' => false
		],
		'attribute_type' => [
			'schema' => [
				'VARCHAR (255)',
				'NOT NULL'
			],
			'format' => '%s',
            'update' => true
		],
		'attribute_required' => [
			'schema' => [
				'SMALLINT',
				'NOT NULL'
			],
			'format' => '%d',
            'update' => true
		],
		'attribute_options' => [
			'schema' => [
				'TEXT',
				'NULL'
			],
			'format' => '%s',
            'update' => true
		],
		'attribute_dictionary' => [
			'schema' => [
				'MEDIUMTEXT',
				'NULL'
			],
			'format' => '%s',
            'update' => true
		],
		'attribute_restrictions' => [
			'schema' => [
				'TEXT',
				'NULL'
			],
			'format' => '%s',
            'update' => true
		]
	];

	public function update($version)
	{
		if (version_compare($version, '2.6.3') < 0) {
			$this->uninstall();
			$this->install();
		}

		if (version_compare($version, '2.6.18') < 0) {
			$this->updateColumn($this->getTable(), 'attribute_dictionary', 'MEDIUMTEXT');
			$this->removeAttributesFromCategoryWithInvalidJson();
		}
	}

	public function loadByAttributeAndCategory($attributeAllegroId, $categoryAllegroId)
	{
		return $this->load([
			$attributeAllegroId,
			$categoryAllegroId
		], [
			'attribute_allegro_id',
			'attribute_category_allegro_id'
		]);
	}

	public function loadByCategoryId($categoryAllegroId)
	{
		$filters = [
			'WHERE' => sprintf('attribute_category_allegro_id = %s', $categoryAllegroId)
		];

		return $this->getAllBySearch(
			$filters,
			100
		);
	}

	public function saveMultiple($data)
	{

		$query = "INSERT INTO {$this->getTable()} VALUES ";
		$query .= '(';
        $queryWithNullId = $query;
		foreach($this->getColumns() as $columnName => $columnData) {
			$query .= $columnData['format'] . ',';
            if($columnName === 'attribute_id') {
                $queryWithNullId .= 'NULL,';
            } else {
                $queryWithNullId .= $columnData['format'] . ',';
            }
		}
		$query = rtrim($query,',');
        $queryWithNullId = rtrim($queryWithNullId, ',');

        $query .= ') ON DUPLICATE KEY UPDATE ';
        $queryWithNullId .= ') ON DUPLICATE KEY UPDATE ';

        foreach ($this->getColumns() as $columnName => $columnData) {
            if(!$columnData['update']) {
                continue;
            }

            $query .= sprintf('%s = VALUES(%s), ', $columnName, $columnName);
            $queryWithNullId .= sprintf('%s = VALUES(%s), ', $columnName, $columnName);
        }

        $query = rtrim($query, ', ');
        $queryWithNullId = rtrim($queryWithNullId, ', ');

		foreach($data as $item) {
            if(is_null($item['attribute_id'])) {
                $this->getWpdb()->query($this->getWpdb()->prepare($queryWithNullId, $item['attribute_category_allegro_id'], $item['attribute_allegro_id'], $item['attribute_name'], $item['attribute_type'], $item['attribute_required'], $item['attribute_options'], $item['attribute_dictionary'], $item['attribute_restrictions']));
            } else {
                $this->getWpdb()->query($this->getWpdb()->prepare($query, $item['attribute_id'], $item['attribute_category_allegro_id'], $item['attribute_allegro_id'], $item['attribute_name'], $item['attribute_type'], $item['attribute_required'], $item['attribute_options'], $item['attribute_dictionary'], $item['attribute_restrictions']));
            }
		}
	}

    public function removeAttributesFromCategoryWithInvalidJson()
    {
        $categoryIds = $this->getWpdb()->get_col(sprintf('SELECT %s FROM %s WHERE !JSON_VALID(%s)', 'attribute_category_allegro_id', $this->getTable(), 'attribute_dictionary'));

        if(!empty($this->getWpdb()->last_error)) {
            $categoryIds = $this->getAttributeCategoriesWithInvalidJson();
        }

        foreach ($categoryIds as $categoryId) {
            $this->getWpdb()->query(sprintf('DELETE FROM %s WHERE attribute_category_allegro_id = \'%s\'', $this->getTable(), $categoryId));
        }
    }

    private function getAttributeCategoriesWithInvalidJson()
    {
        $categoryIds = [];

        $attributes = $this->getAll();
        foreach($attributes as $attribute) {
            $categoryId = $attribute['attribute_category_allegro_id'];
            if(in_array($categoryId, $categoryIds)) {
                continue;
            }

            $dictionary = $attribute['attribute_dictionary'];

            if(!$this->isJson($dictionary)) {
                $categoryIds[] = $categoryId;
            }
        }

        return $categoryIds;
    }
}