<?php

namespace AOP\App\Database;

use AOP\App\Plugin;

class DB
{
    /**
     * @return \QM_DB|\wpdb
     */
    private static function wpdb()
    {
        global $wpdb;

        return $wpdb;
    }

    /**
     * @return string
     */
    private static function pluginTableName()
    {
        return static::wpdb()->prefix . Plugin::_NAME;
    }

    public static function createPluginTable()
    {
        $charsetCollate = static::wpdb()->get_charset_collate();

        $tableName = static::pluginTableName();

        $sql = "CREATE TABLE $tableName (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            menu_slug text NOT NULL,
            page_value text NOT NULL,
            plugin text NOT NULL,
            PRIMARY KEY  (id)
        ) $charsetCollate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        \dbDelta($sql);
    }

    public static function dropPluginTable()
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $sql = sprintf('DROP TABLE IF EXISTS %s', static::pluginTableName());
        static::wpdb()->query($sql);
    }

    /**
     * @param $id
     *
     * @return bool|false|int
     */
    protected static function deleteRowById($id)
    {
        return static::wpdb()->delete(static::pluginTableName(), ['id' => $id], '%d');
    }

    /**
     * @return bool
     */
    protected static function tableExist()
    {
        $sql = sprintf('select 1 from `%s` LIMIT 1', static::pluginTableName());

        static::wpdb()->hide_errors();
        $result = static::wpdb()->get_var($sql);
        static::wpdb()->show_errors();

        return $result === '1';
    }

    /**
     * @return array|object|null
     */
    protected static function allRowsFromPluginTable()
    {
        $sql = sprintf('SELECT * FROM %s', static::pluginTableName());

        return static::wpdb()->get_results($sql);
    }

    /**
     * @return array|object|null
     */
    protected static function getAllFromColumnPageValue()
    {
        $sql = sprintf('SELECT %s FROM %s', 'page_value', static::pluginTableName());

        return static::wpdb()->get_results($sql);
    }

    /**
     * @return array|object|null
     */
    protected static function rowByParentSlug()
    {
        $sql = sprintf('SELECT %s FROM %s', 'page_value', static::pluginTableName());

        return static::wpdb()->get_results($sql);
    }

    /**
     * @param $id
     *
     * @return array|object|null
     */
    protected static function rowFromPluginTable($id)
    {
        $sql = sprintf('SELECT * FROM %s WHERE id=%d', static::pluginTableName(), $id);

        return static::wpdb()->get_results($sql);
    }

    /**
     * @param string $fields
     * @param        $menuSlug
     */
    protected function insertNewPage($menuSlug, $fields = '')
    {
        static::wpdb()->insert(
            static::pluginTableName(),
            [
                'time' => current_time('mysql'),
                'menu_slug' => $menuSlug,
                'page_value' => $fields,
                'plugin' => Plugin::_NAME
            ]
        );
    }

    /**
     * @return int
     */
    protected function insertId()
    {
        return static::wpdb()->insert_id;
    }

    /**
     * @param        $pageId
     * @param string $fields
     * @param        $menuSlug
     */
    protected function updateEditPage($pageId, $menuSlug, $fields = '')
    {
        static::wpdb()->update(
            static::pluginTableName(),
            [
                'time' => current_time('mysql'),
                'menu_slug' => $menuSlug,
                'page_value' => $fields,
                'plugin' => Plugin::_NAME
            ],
            ['ID' => $pageId]
        );
    }

    /**
     * @return array|object|null
     */
    protected static function allOptionNamesFromOptionsTable()
    {
        $sql = sprintf('SELECT %s FROM %s', 'option_name', static::wpdb()->prefix . 'options');

        return static::wpdb()->get_results($sql);
    }
}
