<?php

Class GRWP_Free_Menu_Pages {

    public function __construct() {

        $this->add_menu_pages();
    }

    private function add_menu_pages() {

        add_submenu_page(
            'google-reviews',
            __('How to', 'grwp'),
            __('How to', 'grwp'),
            'manage_options',
            'how-to-free-version',
            array($this, 'google_reviews_create_sub_page_how_to')
        );

    }

    /**
     * Backend how to subpage for free version
     * @return void
     */
    public function google_reviews_create_sub_page_how_to() {
        global $imgpath;
        global $allowed_html;

        $imgpath = plugin_dir_url(__FILE__) .'img/';

        echo wp_kses('<div class="wrap">', $allowed_html);
        require_once GR_BASE_PATH_ADMIN .'/includes/how-to.php';
        echo wp_kses('</div>', $allowed_html);
    }


}
