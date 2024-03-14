<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('STUL_Shortcode')) {

    class STUL_Shortcode extends STUL_Library {

        function __construct() {
            add_shortcode('subscribe_to_unlock_form', array($this, 'generate_shortcode_output'));
            add_action('wp_footer', array($this, 'append_svg_filter'));
        }

        function generate_shortcode_output($atts, $content = null) {
            $form_details = get_option('stul_settings');
            ob_start();
            include(STUL_PATH . 'inc/views/frontend/stul-shortcode.php');
            $form_html = ob_get_contents();
            ob_clean();
            ob_end_flush();
            return $form_html;
        }

        function append_svg_filter() {
            ?>
            <svg id="svg-filter">
            <filter id="svg-blur">
                <feGaussianBlur in="SourceGraphic" stdDeviation="12"></feGaussianBlur>
            </filter>
            </svg>
            <?php

        }

    }

    new STUL_Shortcode();
}
