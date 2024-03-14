<?php

namespace Qodax\CheckoutManager\DB\Migrations;

use Qodax\CheckoutManager\DB\Migration;

if ( ! defined('ABSPATH')) {
    exit;
}

class UpdateFieldsTable_202310120137 extends Migration
{
    public function name(): string
    {
        return '202310120137_update_fields_table';
    }

    /**
     * @param mixed $db
     * @return void
     */
    public function up($db): void
    {
        $prefix = $db->prefix;

        $db->query("
            ALTER TABLE {$prefix}qodax_checkout_manager_fields
            ADD COLUMN `data_version` INT(10) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`,
            ADD INDEX u_name (`field_name`)
        ");
    }
}