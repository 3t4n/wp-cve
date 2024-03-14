<?php

namespace Memsource\Service\Migrate;

use Exception;
use Memsource\Utils\DatabaseUtils;

class SchemaService
{
    public function createDatabaseSchema()
    {
        global $wpdb;
        $charsetCollate = $wpdb->get_charset_collate();
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_TRANSLATIONS;
        $sql = "
             create table if not exists {$tableName} (
                id bigint not null primary key auto_increment,
                item_id bigint,
                type varchar(100) not null,
                group_id bigint not null,
                source_language varchar(10),
                target_language varchar(10) not null,
                unique index (item_id),
                unique index (group_id, target_language)
            ) {$charsetCollate}";
        $this->createTable($tableName, $sql);
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_WORK_DATA;
        $sql = "
             create table if not exists {$tableName} (
                id bigint not null primary key auto_increment,
                post_id bigint,
                target_language varchar(10) not null,
                work_uuid varchar(190) not null,
                work_unit_uuid varchar(190),
                status varchar(100) not null,
                index (post_id),
                unique index (post_id, target_language),
                index (work_uuid)
            ) {$charsetCollate}";
        $this->createTable($tableName, $sql);
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_SHORT_CODES;
        $sql = "
             create table if not exists {$tableName} (
                id bigint not null primary key auto_increment,
                type varchar(20) not null,
                tag varchar(100) not null,
                ignore_body bool not null default '0',
                editable bool not null default '0',
                status varchar(20) not null default 'Active',
                index (type),
                unique index (type, tag),
                index (status)
            ) {$charsetCollate}";
        $this->createTable($tableName, $sql);
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_SHORT_CODE_ATTRIBUTES;
        $sql = "
             create table if not exists {$tableName} (
                id bigint not null primary key auto_increment,
                short_code_id bigint not null,
                name varchar(20) not null,
                type varchar(20),
                encoding varchar(100),
                editable bool not null default '0',
                status varchar(20) not null default 'Active',
                index (short_code_id),
                unique index (short_code_id, name),
                index (status)
            ) {$charsetCollate}";
        $this->createTable($tableName, $sql);
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_TRANSFORMED_CONTENT;
        $sql = "
             create table if not exists {$tableName} (
                id bigint not null primary key auto_increment,
                uuid varchar(100) not null,
                post_id bigint not null,
                content longtext not null,
                index (uuid),
                index (post_id)
            ) {$charsetCollate}";
        $this->createTable($tableName, $sql);
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_TRANSLATION;
        $sql = "
            create table if not exists {$tableName} (
              `id` bigint(20) not null primary key auto_increment,
              `item_id` bigint(20) not null,
              `target_id` bigint(20) not null,
              `target_language` varchar(10) not null,
              `type` varchar(20) not null,
              `set_id` bigint(20) default null,
              index (item_id),
              index (target_id),
              index (type),
              index (set_id),
              unique index (item_id, target_id, type)
            ) {$charsetCollate}";
        $this->createTable($tableName, $sql);
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_LANGUAGE_MAPPING;
        $sql = "
            create table if not exists {$tableName} (
              `id` int(11) not null auto_increment,
              `code` varchar(7) not null,
              `memsource_code` varchar(7) not null,
              primary key (`id`),
              unique key `original_code` (`code`),
              unique key `memsource_code` (`memsource_code`)
            ) {$charsetCollate}";
        $this->createTable($tableName, $sql);
        $this->runQuery("ALTER TABLE {$tableName} CHANGE `code` `code` VARCHAR(15) not null, CHANGE `memsource_code` `memsource_code` VARCHAR(15) not null");
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_CONTENT_SETTINGS;
        $sql = "
            create table if not exists {$tableName} (
              `id` int(11) not null auto_increment,
              `content_id` varchar(255) not null,
              `content_type` varchar(20) not null,
              `send` tinyint(1) not null default '1',
              primary key (`id`)
            ) {$charsetCollate}";
        $this->createTable($tableName, $sql);
        $this->addColumn($tableName, 'hash', 'TEXT NULL AFTER `id`');

        // gutenberg blocks
        $tableName = $wpdb->prefix . DatabaseUtils::TABLE_BLOCKS;
        $sql = "
            create table if not exists {$tableName} (
                `id` int not null primary key auto_increment,
                `name` varchar(100) not null,
                `attribute` varchar(100) not null,
                index (`name`),
                unique index (`name`, `attribute`)
            ) {$charsetCollate}";
        $this->createTable($tableName, $sql);
    }

    private function createTable($tableName, $sql)
    {
        global $wpdb;
        if (0 !== strcasecmp($wpdb->get_var("show tables like '{$tableName}'"), $tableName)) {
            $this->runQuery($sql);
        }
    }

    /**
     * Add column to table.
     * @param $table string
     * @param $column string
     * @param $definition string
     * @return bool
    */
    private function addColumn($table, $column, $definition)
    {
        if ($this->isColumnInTable($table, $column) !== true) {
            $this->runQuery("ALTER TABLE {$table} ADD `{$column}` {$definition}");
        }
        return true;
    }

    /**
     * @param $query string sql query
     * @param $exception bool throw exception on some error
     * @return bool
     * @throws Exception in case of error
    */
    private function runQuery($query, $exception = true)
    {
        global $wpdb;
        $result = $wpdb->query($query);
        if ($exception === true && $result === false) {
            throw new Exception($wpdb->last_error);
        }
        return true;
    }

    /**
     * Is column exist?
     * @param $table string
     * @param $column string
     * @return bool
    */
    private function isColumnInTable($table, $column)
    {
        global $wpdb;
        $query = $wpdb->prepare("show columns from {$table} like %s", $column);
        $found = $wpdb->get_var($query);
        return $found !== null;
    }
}
