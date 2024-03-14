<?php

declare(strict_types=1);

namespace WcMipConnector\Repository;

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class SupplierRepository
{
    public const ATTRIBUTE_NAME = 'supplier';

    /** @var \wpdb $wpDb */
    protected $wpDb;
    /** @var string */
    protected $tableTerms;
    /** @var string */
    protected $tableTaxonomies;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
        $this->tableTerms = $this->wpDb->prefix.WordpressDatabaseService::TABLE_TERMS;
        $this->tableTaxonomies = $this->wpDb->prefix.AttributeGroupRepository::TABLE_NAME;
    }

    public function findIdByLabel(string $label): ?int
    {
        $sql = 'SELECT attribute_id FROM '.$this->tableTaxonomies.' WHERE attribute_label = "'.$label.'"';

        $result = $this->wpDb->get_row($sql, ARRAY_A);

        if (!$result) {
            return null;
        }

        return (int)$result['attribute_id'];
    }

    public function findIdByName(): ?int
    {
        $sql = 'SELECT attribute_id FROM '.$this->tableTaxonomies.' WHERE attribute_name = "'.self::ATTRIBUTE_NAME.'"';

        $result = $this->wpDb->get_row($sql, ARRAY_A);

        if (!$result) {
            return null;
        }

        return (int)$result['attribute_id'];
    }
}