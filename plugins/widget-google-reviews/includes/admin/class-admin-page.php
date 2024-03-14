<?php

namespace WP_Rplg_Google_Reviews\Includes\Admin;

class Admin_Page {

    private $parent_slug;
    private $page_title;
    private $menu_title;
    private $capability;
    private $menu_slug;

    public function __construct($parent_slug, $page_title, $menu_title, $capability, $menu_slug) {
        $this->parent_slug = $parent_slug;
        $this->page_title  = $page_title;
        $this->menu_title  = $menu_title;
        $this->capability  = $capability;
        $this->menu_slug   = $menu_slug;
    }

    public function add_page() {
        add_submenu_page(
            $this->parent_slug,
            $this->page_title,
            $this->menu_title,
            $this->capability,
            $this->menu_slug,
            array($this, 'render')
        );
    }

    public function render() {
        echo '<div class="grw-admin-page">';
        do_action("grw_admin_page_{$this->menu_slug}");
        echo '</div>';
    }
}
