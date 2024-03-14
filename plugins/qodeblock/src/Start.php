<?php

namespace WordressLaravel\Wp;

/**
 * Class Plugin
 *
 * @package WordressLaravel\Wp
 */
class Start {

    const TAG = 'wordress-laravel-plugin';

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * Plugin constructor.
     *
     * @param string $mainFile
     */
    public function __construct( $mainFile ) {
        $this->fileManager = new FileManager( $mainFile );
        $this->jquery_script_method();
        $this->registerHooks();
    }

    /**
     * Function register hooks
     *
     */
    private function registerHooks() {

        $functions    = new Functions();
        $api_actions  = new ApiActions();

        add_action( 'init',    [ $functions, 'init_actions' ] );
        // add_action( 'init',    [ $this,      'check_wc_access' ] );

        add_action( 'rest_api_init', [ $api_actions, 'api_functions' ] );
    }

    /**
     * Run plugin part
     */
    public function run() {
        $this->jquery_script_method();
        $this->api_script_method();
    }

    /**
     * Turn on woocommerce access to products-orders
     *
     * @param $permission
     * @param $context
     * @param $object_id
     * @param $post_type
     * @return bool
     */
    function woocommerce_set_permissions( $permission, $context, $object_id, $post_type  ){
        return true;
    }

    /**
     * Functions register and enqueue styles and scripts
     *
     */
    public function jquery_script_method() {
        add_action( 'wp_enqueue_scripts', function () {
            $date_now = date('m.d.H.s');

            wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', false, null, true);
            wp_enqueue_script('jquery');

            wp_register_script('script-from-plugin', plugins_url('../assets/script.js', __FILE__), array('jquery', 'moment-js'), $date_now);

            wp_enqueue_script('script-from-plugin');
            wp_enqueue_style('style-from-plugin', plugins_url('../assets/style.css', __FILE__), 'all');

            wp_localize_script('script-from-plugin', 'get',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'siteurl' => get_template_directory_uri(),
                )
            );
        });

        add_action( 'admin_enqueue_scripts', function () {
            $date_now = date('m.d.H.s');

            wp_register_script('admin-script-from-plugin', plugins_url('../assets/admin-script.js', __FILE__), array('jquery'), $date_now);

            wp_enqueue_script('admin-script-from-plugin');
            wp_enqueue_style('admin-style-from-plugin', plugins_url('../assets/admin-style.css', __FILE__), 'all');

            wp_localize_script('admin-script-from-plugin', 'get',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'siteurl' => get_template_directory_uri(),
                )
            );
        });

    }

    /**
     * Functions register api script file from laravel platform
     *
     */
    public function api_script_method() {

        $api_file_link = get_option('api_file_link');
        $api_file_link = $api_file_link ?? '';

        $api_version = explode('?ver=', $api_file_link);
        $api_version = !empty($api_version[1]) ? $api_version[1] : date('m.d.H.s');

        if (empty($api_file_link)) {
            return true;
        }

        add_action('wp_enqueue_scripts', function () use ($api_file_link, $api_version) {
            wp_register_script('api-script-front', $api_file_link, array('jquery'), $api_version);
            wp_enqueue_script('api-script-front');
        });

        /*
        add_action( 'admin_enqueue_scripts', function () use ($api_file_link, $api_version) {
            wp_register_script('api-script-admin', $api_file_link, array('jquery'),$api_version);
            wp_enqueue_script('api-script-admin');
        });
        */
    }


    /**
     * Function checking woocommerce access
     *
     */
    public function check_wc_access() {
        $wc_access = get_option('wc_api_access');

        if (!empty($wc_access) && ($wc_access == 'yes')) {
            add_filter( 'woocommerce_rest_check_permissions', [ $this, 'woocommerce_set_permissions'], 90, 4 );
        }
    }

}
