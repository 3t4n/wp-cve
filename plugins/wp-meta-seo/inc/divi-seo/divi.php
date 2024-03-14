<?php
defined('ABSPATH') || exit;
/**
 * Class Divi
 * Class that holds most of the divi functionality for Meta SEO.
 */
class Divi
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        add_action('wp', array($this, 'init'), 10, 1);
    }

    /**
     * Class Divi constructor.
     *
     * @return void
     */
    public function init()
    {
        add_action('template_redirect', array($this, 'set_window_lodash'), 0, 1);
        add_action('wp_footer', array($this, 'footer_enqueue_scripts'), 11, 99);
    }

    /**
     * Enqueue scripts.
     *
     * @return void
     */
    public function footer_enqueue_scripts()
    {
        /**
         * Allow other plugins to enqueue/dequeue admin styles or scripts before plugin assets.
         */

        wp_enqueue_style('wp-components');
        wp_enqueue_script('wpseo-editor-divi', WPMETASEO_PLUGIN_URL . 'inc/divi-seo/wpseo-divi.js', array(
            'wp-api-fetch',
            'wp-block-editor',
            'wp-components',
            'wp-compose',
            'wp-core-data',
            'wp-data',
            'wp-element',
            'wp-hooks',
            'wp-media-utils'
        ), WPMSEO_VERSION, true);

        $this->print_react_containers();

        \do_action_ref_array('meta_seo/admin/editor_scripts', array());
    }

    /**
     * Print React containers onto the screen.
     *
     * @return void
     */
    public function print_react_containers()
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required, render html and load js when open tab
        if (isset($_GET['et_fb']) && is_user_logged_in()) {
            require_once(WPMETASEO_PLUGIN_DIR . 'inc/class.metaseo-metabox.php');
            $wpmseometabox = new WPMSEOMetabox;
            $wpmseometabox::translateMetaBoxes();
            $popup_divi = $wpmseometabox->wpmsMetaboxContent();

            wp_enqueue_style(
                'wpms-snackbar-style',
                WPMETASEO_PLUGIN_URL . 'assets/css/snackbar.css',
                array(),
                WPMSEO_VERSION
            );
            wp_enqueue_style(
                'wpms-mytippy-style',
                WPMETASEO_PLUGIN_URL . 'assets/tippy/my-tippy.css',
                array(),
                WPMSEO_VERSION
            );
            wp_enqueue_style(
                'metaseo-google-icon',
                '//fonts.googleapis.com/icon?family=Material+Icons'
            );
            wp_enqueue_style(
                'wpmsStyleOnElementor',
                WPMETASEO_PLUGIN_URL . 'assets/css/elementor/wpms-elementor.css',
                array(),
                WPMSEO_VERSION
            );
            wp_enqueue_style(
                'wpmsStyleOnDivi',
                WPMETASEO_PLUGIN_URL . 'assets/css/divi/style.css',
                array(),
                WPMSEO_VERSION
            );
            wp_enqueue_script('jquery');
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- html tag
            echo '<div id="seo_et_ignore_iframe" class=" hidden">' . $popup_divi . '</div>';
            echo '<div id="wpseo-settings-bar-root" class="et_fb_ignore_iframe"></div>';
        }
    }

    /**
     * Set the global lodash variable.
     *
     * Lodash's `noConflict` would prevent UnderscoreJS from taking over the underscore (_)
     * global variable. Because Underscore.js will later also be assigned to the underscore (_)
     * global this function should run as early as possible.
     *
     * @return void
     */
    public function set_window_lodash()
    {
        $MetaSeoAdmin = new MetaSeoAdmin;
        $MetaSeoAdmin->loadAdminScripts();

        wp_register_script('wpmseo-set-window-lodash', '', array('lodash'), WPMSEO_VERSION, false);
        wp_enqueue_script('wpmseo-set-window-lodash');
        wp_add_inline_script('wpmseo-set-window-lodash', join("\r\n ", array(
            'var ajaxurl = ' . wp_json_encode(admin_url('admin-ajax.php', 'relative')) . ';',
            'window.isLodash = function() {',
            "if ( typeof window._ !== 'function' || typeof window._.forEach !== 'function' ) {",
            'return false;',
            '}',
            'var isLodash = true;',
            'window._.forEach(',
            "[ 'cloneDeep', 'at', 'add', 'ary', 'attempt' ],",
            'function( fn ) {',
            "if ( isLodash && typeof window._[ fn ] !== 'function' ) {",
            'isLodash = false;',
            '}',
            '}',
            ');',
            'return isLodash;',
            '}',
            'if ( window.isLodash() ) { window.lodash = window._.noConflict(); }'
        )));
    }
}
