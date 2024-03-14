<?php

namespace Qodax\CheckoutManager\DB\Migrations;

use Qodax\CheckoutManager\DB\Migration;

if ( ! defined('ABSPATH')) {
    exit;
}

class CreateDisplayRulesTable_202310120109 extends Migration
{
    /**
     * @return string
     */
    public function name(): string
    {
        return '202310120109_create_display_rules_table';
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
          CREATE TABLE IF NOT EXISTS {$prefix}qodax_checkout_manager_display_rules (
            `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `field_id` INT(10) UNSIGNED NOT NULL,
            `action` ENUM('show', 'hide') NOT NULL,
            `logic` ENUM('and', 'or') NOT NULL,
            `data_version` INT(10) UNSIGNED NOT NULL,
            `conditions` LONGTEXT NOT NULL,
            `priority` INT(10) UNSIGNED NOT NULL DEFAULT 0,
            `created_at` TIMESTAMP NOT NULL,
            PRIMARY KEY (id),
            KEY i_field(`field_id`)
          ) $collate
        ");
    }
}