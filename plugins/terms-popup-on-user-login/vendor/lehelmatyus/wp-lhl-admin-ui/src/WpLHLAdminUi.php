<?php

class WpLHLAdminUi {

    private $uniqKey = "";

    function __construct($uniqKey = '') {
        $this->uniqKey = $uniqKey;
    }

    /**
     * Enq Admin Styles
     */
    function wp_enqueue_style() {
        $uniqKey = "";
        if (!empty($this->uniqKey)) {
            $uniqKey = " " . $this->uniqKey;
        }
        wp_enqueue_style('wp-lhl-admin-ui-styles' . esc_attr($uniqKey),  plugin_dir_url(dirname(__FILE__)) . '/css/wp-lhl-admin-ui.css');
    }
}
