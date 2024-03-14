<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


class Zwt_wp_linkpreviewer_Linktable extends WP_List_Table
{

    protected $tab_key;
    protected $dbInstance;

    public function __construct($dbInstance, $tab_key)
    {
        parent::__construct();
        $this->dbInstance = $dbInstance;
        $this->tab_key = $tab_key;
    }

    function get_columns()
    {
        return array(
            'img_compact_len' => Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_LINKS_COL_IMG,
            'url' => Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_LINKS_COL_URL,
            'title' => Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_LINKS_COL_TITLE,
            'description' => Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_LINKS_COL_DESC,
            'date' => Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_LINKS_COL_DATE
        );
    }


    function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->getData();
    }

    function column_url($item)
    {
        $settings_slug = esc_html(Zwt_wp_linkpreviewer_Constants::$SETTINGS_SLUG);
        $hash_md5 = esc_html($item['hash_md5']);
        $actions = array(
            'refresh' => sprintf('<a href="?page=%s&tab=%s&action=%s&item=%s">' . Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_LINKS_ACTION_REFRESH . '</a>', $settings_slug, esc_html($this->tab_key), 'refresh', $hash_md5),
            'delete' => sprintf('<a href="?page=%s&tab=%s&action=%s&item=%s">' . Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_LINKS_ACTION_DELETE . '</a>', $settings_slug, esc_html($this->tab_key), 'delete', $hash_md5),
        );
        return sprintf('%1$s %2$s', esc_url($item['url']), $this->row_actions($actions));
    }

    function column_img_compact_len($item)
    {
        if ($item['img_compact_len'] > 0) {
            return Zwt_wp_linkpreviewer_Utils::render_img_html_admin(esc_html($item['hash_md5']));
        }
        return "";
    }

    function column_title($item)
    {
        return esc_html($item['title']);
    }

    function column_description($item)
    {
        return esc_html($item['description']);
    }

    function column_date($item)
    {
        return esc_html($item['date']);
    }

    function getData()
    {
        return $this->dbInstance->getEntries();
    }

}