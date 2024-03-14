<?php

/**
 * WP_Meteor
 *
 * @package   WP_Meteor
 * @author    Aleksandr Guidrevitch <alex@excitingstartup.com>
 * @copyright 2020 wp-meteor.com
 * @license   GPL 2.0+
 * @link      https://wp-meteor.com
 */

namespace WP_Meteor\Frontend;

use WP_Meteor\Engine\Base as Engine_Base;

abstract class Base extends Engine_Base
{
    public $priority = null;
    public $canRewrite = true;

    /**
     * Initialize the class.
     *
     * @return boolean
     */
    public function initialize()
    {
        if (isset($_SERVER['QUERY_STRING']) && preg_match('/wpmeteordisable/', $_SERVER['QUERY_STRING'])) {
            return;
        }

        if (defined('NITROPACK_VERSION')) {
            return;
        }

        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://www.squirrly.co') {
            return;
        }

        // if (defined('SiteGround_Optimizer\VERSION')) {
        //     return;
        // }

        // if (defined('WPFC_WP_CONTENT_DIR')) { // WP Fastest Cache
        //     return;
        // }

        if (defined('WP_ROCKET_VERSION')) {
			$wp_rocket_settings = get_option('wp_rocket_settings');
			if (@$wp_rocket_settings['delay_js']) {
                return;
            }
        }

        add_action('wp', [$this, 'wp_hook'], $this->priority);
        // add_action('parse_query', [$this, 'parse_query_hook'], $this->priority);

        $this->register();
    }

    public function parse_query_hook() {
        // Replaced by Rewrite::rewrite() check for text/html content type

        /* fix for WP < 4.8.1 https://core.trac.wordpress.org/ticket/41745 */
        global $wp_query;
        if (!isset($wp_query) || !method_exists($wp_query, 'get')) return;
        /* end of fix */

        // SEOPress fix for sitemap
        // if ('1' === get_query_var('seopress_sitemap_xsl')) {
        //     $this->canRewrite = false;
        //     return;
        // }
    }

    public function wp_hook()
    {
        $is = new \WP_Meteor\Engine\Is_Methods();
        if (!$is->is_frontend()) {
            $this->canRewrite = false;
            return;
        }

        if (!apply_filters('wpmeteor_enabled', true)) {
            $this->canRewrite = false;
        }

        foreach([
            'bricks',// Bricks plugin
            'brizy-edit-iframe',// Brizy plugin
            'builder', // Fusion Builder
            'ct_builder', // Oxygen
            'elementor-preview', // Elementor
            'et_fb', // Divi
            'fb-edit', // Fusion Builder
            'fl_builder', // Beaver Builder
            'preview', // Blockeditor & Gutenberg
            'tb-preview', // Themify
            'tve', // Thrive
            'uxb_iframe', // Flatsome UX Builder
            'vc_action', // WP Bakery
            'vc_editable', // WP Bakery
            'vcv-action', // WP Bakery
            'wyp_mode',// Yellowpencil plugin
            'wyp_page_type', // Yellowpencil plugin
            'zionbuilder-preview',// Zion Builder plugin
        ] as $key) {
            if (isset($_GET[$key]) || isset($_POST[$key])) {
                $this->canRewrite = false;
                return;
            }
        }

        if (function_exists('is_amp_endpoint') && \is_amp_endpoint()) {
            $this->canRewrite = false;
            return;
        }

        if (function_exists('ampforwp_is_amp_endpoint') && \ampforwp_is_amp_endpoint()) {
            $this->canRewrite = false;
            return;
        }

        if (class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance) {
            if (\Elementor\Plugin::$instance->editor && \Elementor\Plugin::$instance->editor->is_edit_mode()) {
                $this->canRewrite = false;
                return;
            }
            if (\Elementor\Plugin::$instance->preview && \Elementor\Plugin::$instance->preview->is_preview_mode()) {
                $this->canRewrite = false;
                return;
            }
        }

        if (class_exists('\FLBuilderModel') && \FLBuilderModel::is_builder_active()) {
            $this->canRewrite = false;
            return;
        }

        if (function_exists('vc_is_inline') && \vc_is_inline()) {
            $this->canRewrite = false;
            return;
        }

        if (function_exists('et_core_is_builder_used_on_current_request') && \et_core_is_builder_used_on_current_request()) {
            $this->canRewrite = false;
            return;
        }

        if (class_exists('Fusion_App') && ($instance = \Fusion_App::get_instance()) && $instance->is_builder) {
            $this->canRewrite = false;
            return;
        }
    }

    public abstract function register();
}
