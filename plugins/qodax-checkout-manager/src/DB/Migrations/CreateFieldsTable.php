<?php

namespace Qodax\CheckoutManager\DB\Migrations;

use Qodax\CheckoutManager\DB\Migration;

if ( ! defined('ABSPATH')) {
    exit;
}

class CreateFieldsTable extends Migration
{
    /**
     * @return string
     */
    public function name(): string
    {
        return 'create_fields_table';
    }

    /**
     * @param mixed $db
     *
     * @return void
     */
    public function up($db): void
    {
        $collate = $db->get_charset_collate();
        $prefix = $db->prefix;

        $db->query("
          CREATE TABLE IF NOT EXISTS {$prefix}qodax_checkout_manager_fields (
            `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `field_name` VARCHAR(100) NOT NULL,
            `field_type` VARCHAR(100) NOT NULL,
            `field_meta` LONGTEXT NULL DEFAULT NULL,
            `section` VARCHAR(100) NOT NULL,
            `native` TINYINT UNSIGNED NOT NULL,
            `required` TINYINT UNSIGNED NOT NULL,
            `active` TINYINT UNSIGNED NOT NULL,
            `priority` INT(10) UNSIGNED NOT NULL,
            PRIMARY KEY (id)
          ) $collate
        ");
    }
}