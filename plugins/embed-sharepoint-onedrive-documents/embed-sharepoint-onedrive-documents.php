<?php

/*
Plugin Name: Embed SharePoint OneDrive Documents
Plugin URI: https://plugins.miniorange.com/
Description: This plugin will allow you to sync embed sharepoint focuments, folders and files in the wordpress. Download, preview sharepoint files from the wordpress itself. 
Version: 2.2.2
Author: miniOrange
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

namespace MoSharePointObjectSync;
require_once __DIR__ . '/vendor/autoload.php';

use MoSharePointObjectSync\View\adminView;
use MoSharePointObjectSync\Controller\adminController;
use MoSharePointObjectSync\Observer\adminObserver;
use MoSharePointObjectSync\Observer\appConfigObserver;
use MoSharePointObjectSync\Observer\documentObserver;
use MoSharePointObjectSync\Observer\shortcodeSharepoint;

use MoSharePointObjectSync\View\feedbackForm;
use MoSharePointObjectSync\Wrappers\wpWrapper;

define('MO_SPS_PLUGIN_FILE',__FILE__);
define('MO_SPS_PLUGIN_DIR',__DIR__.DIRECTORY_SEPARATOR);
define( 'PLUGIN_VERSION', '2.2.2' );

class MOsps{

    private static $instance;
    public static $version = PLUGIN_VERSION;

    private function __construct() {
		$this->mo_sps_load_hooks();
	}

    public static function mo_sps_load_instance(){
        if(!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function mo_sps_load_hooks(){
        add_action('admin_menu',[$this,'mo_sps_admin_menu']);
        add_action( 'admin_enqueue_scripts', [$this, 'mo_sps_enqueue_admin_styles' ] );
        add_action( 'admin_enqueue_scripts', array( $this, 'mo_sps_settings_script' ) );
        add_action( 'admin_init', array ($this, 'mo_sp_redirect_after_activation') );
        add_action('admin_init',[adminController::getController(),'mo_sps_admin_controller']);
        add_action( 'admin_footer', [feedbackForm::getView() , 'mo_sps_display_feedback_form'] );
        add_action('init',[adminObserver::getObserver(),'mo_sps_admin_observer']);
	    register_activation_hook(__FILE__,array($this,'mo_sps_plugin_activate'));
        add_shortcode( 'MO_SPS_SHAREPOINT', [shortcodeSharepoint::getObserver(),'mo_sps_shortcode_document_observer'] );
        add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this,'settings_link'));
        add_action( 'init',[$this,'mo_sps_gutenburg']);
        add_action( "wp_ajax_mo_doc_embed", [documentObserver::getObserver(), 'mo_sps_doc_embed']);
        add_action('wp_ajax_mo_sps_app_configuration',[appConfigObserver::getObserver(),'mo_sps_app_configuration_api_handler']);
        register_uninstall_hook(__FILE__, 'mo_sps_uninstall');
        add_action('admin_init', [$this,'mo_sps_plugin_check_migration']);
        add_action('admin_init', [$this,'mo_sps_plugin_handle_migration_action']);
    }

    function mo_sps_plugin_check_migration() {
        if ( get_option('mo_sps_application_config') && !get_option('mo_sps_plugin_migration_completed')) {
            add_action('admin_notices', [$this,'mo_sps_plugin_migration_notice']);
        } else if(! get_option('mo_sps_application_config')) {
            update_option('mo_sps_plugin_migration_completed', true);
        }
    }

    function mo_sps_plugin_migration_notice() {
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'app_config';
        if((isset($_GET['page']) && $_GET['page'] == 'mo_sps') && $tab == 'app_config') {
        ?>
        <div class="notice notice-info">
            <p><?php _e('It seems you already have some configurations set up from the previous version of the plugin. Click on the button below to migrate your configurations.'); ?></p>
            <form method="post" action="">
                <input type="hidden" name="mo_sps_plugin_migration_action" value="migrate_configurations">
                <?php submit_button(__('Migrate Configurations'), 'primary', 'mo_sps_plugin_migrate_button'); ?>
            </form>
        </div>
        <?php
        }
    }

    function mo_sps_plugin_handle_migration_action() {
        if (isset($_POST['mo_sps_plugin_migration_action']) && $_POST['mo_sps_plugin_migration_action'] === 'migrate_configurations' && !get_option('mo_sps_plugin_migration_completed')) {
            update_option('mo_sps_test_connection_status', 'success');
            update_option('mo_sps_plugin_migration_completed', true);
            ?>
            <script type="text/javascript">
                window.onload = function() {
                    if (window.location.href.indexOf('page=mo_sps&tab=app_config') !== -1) {
                        location.reload();
                    }
                };
            </script>
            <?php
        }
    }

    public function mo_sps_load_media_library_scripts()
    {
        global $pagenow;

        $mode = get_user_option('media_library_mode', get_current_user_id()) ? get_user_option('media_library_mode', get_current_user_id()) : 'grid';
        if ($mode === 'list' || $mode === 'grid') {
            $this->mo_sps_load_assets();
        }

        
    }

    public function mo_sps_load_assets()
    {
        wp_enqueue_script('jquery');

  
        wp_enqueue_style(
            'mo-sps-style',
            plugins_url('/includes/css/media.css', __FILE__),
            array(),
            PLUGIN_VERSION
        );

            wp_register_script(
                'mo-sps-base',
                plugins_url('/includes/js/media.js', __FILE__),
                array('jquery'),
                PLUGIN_VERSION
            );
            
            $params = [  
                'sharepoint_icon' => esc_url(plugin_dir_url(__FILE__).'/images/microsoft-sharepoint.svg'),
                'admin_uri' => admin_url(),
            ];
            wp_enqueue_script('mo-sps-base');
            wp_add_inline_script('mo-sps-base', 'var mo_sps='.json_encode($params).';', 'before' );

  
    }

	public function mo_sps_plugin_activate(){
        wpWrapper::mo_sps_set_option("mo_sps_feedback_config",array());
		update_option('mo_sp_do_activation_redirect', true);
	}
    
    function mo_sp_redirect_after_activation() {
	    if (get_option('mo_sp_do_activation_redirect')) {
		    delete_option('mo_sp_do_activation_redirect');

		    if(!isset($_GET['activate-multi'])) {
			    wp_redirect(admin_url() . 'admin.php?page=mo_sps&tab=app_config');
			    exit;
		    }
	    }
    }

    public function mo_sps_admin_menu(){
        $page = add_menu_page(
            'SharePoint/OneDrive' .__('+ Sync'),
            'SharePoint /   OneDrive',
            'administrator',
            'mo_sps',
            [adminView::getView(),'mo_sps_menu_display'],
            plugin_dir_url( __FILE__ ) . 'images/miniorange.png'
        );
    }

    function mo_sps_enqueue_admin_styles($page){

        global $pagenow;

        if ($pagenow === 'upload.php') {
            $this->mo_sps_load_media_library_scripts();
        }

        if( $page != 'toplevel_page_mo_sps'){
            return;
        }

        $css_url = plugins_url('includes/css/mo_sps_settings.css',__FILE__);
        $css_phone_url = plugins_url('includes/css/phone.css',__FILE__);
        $css_jquery_ui_url = plugins_url('includes/css/jquery-ui.css',__FILE__);
        $css_license_view_url = plugins_url('includes/css/license.css',__FILE__);

        wp_enqueue_style('mo_sps_css',$css_url,array(),self::$version);
        wp_enqueue_style('mo_sps_phone_css',$css_phone_url,array(),self::$version);
        wp_enqueue_style('mo_sps_jquery_ui_css',$css_jquery_ui_url,array(),self::$version);
        wp_enqueue_style('mo_sps_license_view_css',$css_license_view_url,array(),self::$version);
    }

    function mo_sps_enqueue_styles($page) {
        global $pagenow;

        if ($pagenow === 'upload.php') {
            $this->mo_sps_load_media_library_scripts();
        }

        if( $page != 'toplevel_page_mo_sps'){
            return;
        }

        $css_jquery_ui_url = plugins_url('includes/css/jquery-ui.css',__FILE__);
        wp_enqueue_style('mo_sps_jquery_ui_css',$css_jquery_ui_url,array(),self::$version);
    }

    function mo_sps_settings_script($page){
        $phone_js_url = plugins_url('includes/js/phone.js',__FILE__);
        $setting_js_url= plugins_url('includes/js/settings.js',__FILE__);
        wp_enqueue_script('mo_sps_phone_js',$phone_js_url,array(),self::$version);
        wp_enqueue_script('mo_settings_js',$setting_js_url,array(),self::$version);
    }

    function so_enqueue_scripts(){
        wp_register_script( 
          'ajaxHandle', 
          plugins_url('includes/js/ajax.js', __FILE__), 
          array(), 
          false, 
          true 
        );
        wp_enqueue_script( 'ajaxHandle' );
        wp_add_inline_script( 
          'ajaxHandle', 
          'var ajax_object='.json_encode(array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ).';', 'before'
        );
      }

    function settings_link( $links ) {
        // Build and escape the URL.
        $url = esc_url( add_query_arg(
            'page',
            'mo_sps',
            get_admin_url() . 'admin.php'
        ) );
        // Create the link.
        $settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
        // Adds the link to the end of the array.
        array_push(
            $links,
            $settings_link
        );
        return $links;
    }
    function mo_sps_gutenburg()  {
        $src = plugins_url('includes/js/gutenburg-block.js',__FILE__);
        
        wp_register_script('custom-cta-js', $src, array('wp-blocks','wp-editor'), self::$version);

        
        if(isset($_GET['post']))
        {
            $post_id = $_GET['post'];
            $post_info = get_post( $post_id);
            $post_content = ! empty($post_info->post_content) ? wp_strip_all_tags($post_info->post_content) : '';
           
        wp_add_inline_script('custom-cta-js','var post_content='.json_encode($post_content).';', 'before');

            
    }

        register_block_type('sps/custom-cta',array(
             'editor_script' => 'custom-cta-js',

        ));

        
    }

}
$mo_sharepoint = MOsps::mo_sps_load_instance();