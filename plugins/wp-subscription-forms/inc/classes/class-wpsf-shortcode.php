<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('WPSF_Shortcode')) {

    class WPSF_Shortcode extends WPSF_Library {

        function __construct() {
            add_shortcode('wp_subscription_forms', array($this, 'generate_shortcode_output'));
        }

        function generate_shortcode_output($atts) {
            wp_enqueue_style('wpsf-frontend-custom', WPSF_CSS_DIR . '/wpsf-custom.css', array(), WPSF_VERSION);
            if (isset($atts['alias'])) {
                $alias = $atts['alias'];
                $form_row = $this->get_form_row_by_alias($alias);
                if (!empty($form_row) && $form_row->form_status == 1) {
                    ob_start();
                    include(WPSF_PATH . 'inc/views/frontend/wpsf-shortcode.php');
                    $form_html = ob_get_contents();
                    ob_clean();
                    return $form_html;
                }
            } else {
                return '';
            }
        }

    }

    new WPSF_Shortcode();
}
