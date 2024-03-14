<?php

/*
Plugin Name: WordPress Photo Gallery
Plugin URI: https://uxgallery.net/pricing/
Description: Are you browsing WodPress in search of a universal gallery product? You are in the right place where we have thought about all your needs as a user.
Version: 2.0.4
Author: UXgallery
Author URI: https://uxgallery.net/
License: GPLv3 or later
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


include_once('config.php');
require_once('debug.php');

if (!class_exists('UXGallery')) :

    final class UXGallery
    {

        /**
         * Version of plugin
         * @var float
         */
        public $version = '2.0.4';

        /**
         * Instance of UXGallery_Admin class to manage admin
         * @var UXGallery_Admin instance
         */
        public $admin = null;

        /**
         * Instance of UXGallery_Template_Loader class to manage admin
         * @var UXGallery_Template_Loader instance
         */
        public $template_loader = null;

        /**
         * The single instance of the class.
         *
         * @var UXGallery
         */

        protected static $_instance = null;

        /**
         * Main UXGallery Instance.
         *
         * Ensures only one instance of UXGallery is loaded or can be loaded.
         *
         * @static
         * @return UXGallery - Main instance.
         * @see UXGallery()
         */
        public static function instance()
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        private function __clone()
        {
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'gallery-img'), '2.1');
        }

        /**
         * Unserializing instances of this class is forbidden.
         */
        public function __wakeup()
        {
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'gallery-img'), '2.1');
        }

        /**
         * UXGallery Constructor.
         */
        private function __construct()
        {
            $this->define_constants();
            $this->includes();
            $this->init_hooks();
            global $UXGallery_url, $UXGallery_path;
            $UXGallery_path = untrailingslashit(plugin_dir_path(__FILE__));
            $UXGallery_url = plugins_url('', __FILE__);
            do_action('UXGallery_loaded');
        }

        /**
         * Hook into actions and filters.
         */
        private function init_hooks()
        {
            register_activation_hook(__FILE__, array('UXGallery_Install', 'install'));
            add_action('init', array($this, 'init'), 0);
            add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
            add_action('widgets_init', array('UXGallery_Widgets', 'init'));
            add_filter('block_categories', array($this, 'gutenbergBlockCategory'), 10, 2);
            add_action('init', array($this, 'gutenbergBlock'));
            add_action('elementor/widgets/widgets_registered', array($this, 'elementorWidgets'));
        }

        public function elementorWidgets()
        {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new UXGallery_Gallery_Elementor_Widget());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new UXGallery_Album_Elementor_Widget());
        }

        public function gutenbergBlock()
        {
            if (!function_exists('register_block_type')) {
                return;
            }

            wp_register_script(
                'uxgallery_gutenberg_block',
                UXGALLERY_PLUGIN_URL . 'assets/js/gutenberg.block.js',
                array('wp-blocks', 'wp-element', 'wp-components')
            );

            global $wpdb;
            $galleriesTable = $wpdb->prefix . 'ux_gallery_gallerys';
            $albumsTable = $wpdb->prefix . 'ux_gallery_albums';
            $galleries = $wpdb->get_results("SELECT id, name FROM `" . $galleriesTable . "` order by id desc ");
            $albums = $wpdb->get_results("SELECT id, name FROM `" . $albumsTable . "` order by id desc ");
            $galleryOptions = array(
                array(
                    'value' => 0,
                    'label' => 'Select'
                ),
            );
            $albumOptions = array(
                array(
                    'value' => 0,
                    'label' => 'Select'
                ),
            );
            $galleryMetas = array();
            $albumMetas = array();
            if (!empty($galleries)) {
                foreach ($galleries as $gallery) {
                    $galleryOptions[] = array(
                        'value' => $gallery->id,
                        'label' => $gallery->name,
                    );
                    $galleryMetas[$gallery->id] = $gallery->name;
                }
            }
            if (!empty($albums)) {
                foreach ($albums as $album) {
                    $albumOptions[] = array(
                        'value' => $album->id,
                        'label' => $album->name
                    );
                    $albumMetas[$album->id] = $album->name;
                }
            }

            wp_localize_script('uxgallery_gutenberg_block', 'uxgalleryBlockI10n', array(
                'galleries' => $galleryOptions,
                'galleryMetas' => $galleryMetas,
                'albums' => $albumOptions,
                'albumMetas' => $albumMetas
            ));

            register_block_type('uxgallery/gallery', array(
                'editor_script' => 'uxgallery_gutenberg_block',
                'editor_style' => null,
            ));
        }

        public function gutenbergBlockCategory($categories, $post)
        {

            return array_merge(
                $categories,
                array(
                    array(
                        'slug' => 'uxgallery',
                        'title' => __('UXGallery', 'uxgallery'),
                    ),
                )
            );
        }

        /**
         * Define Image Gallery Constants.
         */
        private function define_constants()
        {
            $this->define('UXGALLERY_PLUGIN_URL', plugin_dir_url(__FILE__));
            $this->define('UXGALLERY_PLUGIN_FILE', __FILE__);
            $this->define('UXGALLERY_PLUGIN_BASENAME', plugin_basename(__FILE__));
            $this->define('UXGALLERY_VERSION', $this->version);
            $this->define('UXGALLERY_IMAGES_PATH', $this->plugin_path() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR);
            $this->define('UXGALLERY_IMAGES_URL', untrailingslashit($this->plugin_url() . '/assets/images/'));
            $this->define('UXGALLERY_TEMPLATES_PATH', $this->plugin_path() . DIRECTORY_SEPARATOR . 'templates');
            $this->define('UXGALLERY_TEMPLATES_URL', untrailingslashit($this->plugin_url()) . '/templates/');
        }

        /**
         * Define constant if not already set.
         *
         * @param string $name
         * @param string|bool $value
         */
        private function define($name, $value)
        {
            if (!defined($name)) {
                define($name, $value);
            }
        }

        /**
         * What type of request is this?
         * string $type ajax, frontend or admin.
         *
         * @return bool
         */
        private function is_request($type)
        {
            switch ($type) {
                case 'admin' :
                    return is_admin();
                case 'ajax' :
                    return defined('DOING_AJAX');
                case 'cron' :
                    return defined('DOING_CRON');
                case 'frontend' :
                    return !is_admin() && !defined('DOING_CRON');
            }
        }

        /**
         * Include required core files used in admin and on the frontend.
         */
        public function includes()
        {
            include_once('includes/gallery-img-functions.php');
            include_once('includes/gallery-img-video-function.php');
            if ($this->is_request('admin')) {
                include_once('includes/admin/gallery-img-admin-functions.php');
            }
        }

        /**
         * Load plugin text domain
         */
        public function load_plugin_textdomain()
        {
            load_plugin_textdomain('gallery-img', false, $this->plugin_path() . '/languages/');
        }

        /**
         * Init Image gallery when WordPress `initialises.
         */
        public function init()
        {
            // Before init action.
            do_action('before_UXGallery_init');

            $this->template_loader = new UXGallery_Template_Loader();

            if ($this->is_request('admin')) {

                $this->admin = new UXGallery_Admin();

                new UXGallery_Admin_Assets();

            }

            new UXGallery_Frontend_Scripts();

            new UXGallery_Ajax();

            new UXGallery_Shortcode();


            // Init action.
            do_action('UXGallery_init');
        }

        /**
         * Get Ajax URL.
         * @return string
         */
        public function ajax_url()
        {
            return admin_url('admin-ajax.php', 'relative');
        }

        /**
         * Image Gallery Plugin Path.
         *
         * @return string
         * @var string
         */
        public function plugin_path()
        {
            return untrailingslashit(plugin_dir_path(__FILE__));
        }

        /**
         * Image Gallery Plugin Url.
         * @return string
         */
        public function plugin_url()
        {
            return plugins_url('', __FILE__);
        }
    }

endif;

function UXGallery()
{
    return UXGallery::instance();
}

$GLOBALS['UXGallery'] = UXGallery();