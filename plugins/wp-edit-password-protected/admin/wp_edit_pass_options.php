<?php

/**
 * @link              http://wpthemespace.com
 * @since             1.0.0
 * @package           wp edit password protected
 *
 * @author noor alam
 */
if (!class_exists('wpepop_options')) :
    class wpepop_options
    {

        private $settings_api;

        function __construct()
        {
            $this->settings_api = new wpepop_settings;
            add_action('wsa_form_top_pp_new_basic_settings', [$this, 'password_propage']);
            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_menu', array($this, 'admin_menu'));
        }

        function admin_init()
        {

            //set the settings
            $this->settings_api->set_sections($this->get_settings_sections());
            //  $this->settings_api->set_fields($this->get_settings_fields());

            //initialize settings
            $this->settings_api->admin_init();
        }

        function admin_menu()
        {
            add_options_page(
                __('Edit password protected options', 'wp-edit-password-protected'),
                __('WP Edit password protected options', 'wp-edit-password-protected'),
                'manage_options',
                'wp-edit-pass.php',
                array($this, 'plugin_page')
            );
        }

        function get_settings_sections()
        {
            $sections = array(
                array(
                    'id' => 'pp_new_basic_settings',
                    'title' => __('Password protected Settings', 'wp-edit-password-protected')
                ),


            );


            return $sections;
        }


        function plugin_page()
        {
            echo '<div class="wrap wpedit-pass-protected">';
            // $this->settings_api->show_navigation();
            ob_start();
            include WP_EDIT_PASS_PATH . '/admin/admin-pages/admin-page-setup.php';
            echo ob_get_clean();

            echo '</div>';
        }

        // General tab
        function password_propage()
        {
        }
    }
endif;
require plugin_dir_path(__FILE__) . '/src/class.settings-api.php';
new wpepop_options();



//Admin notice 
/*
if( !function_exists('wpspace_admin_notice')):
function wpspace_admin_notice() {
    global $pagenow;
    if( $pagenow != 'themes.php' ){
        return;
    }

    $class = 'notice notice-success is-dismissible';
    $url1 = esc_url('https://wpthemespace.com/product-category/theme/');

    $message = __( '<strong><span style="color:red;">Recommended WordPress Themes:</span>  <span style="color:green"> Awesome WordPress Theme For your WordPress Website</span>  </strong>', 'wp-edit-password-protected' );

    printf( '<div class="%1$s" style="padding:10px 15px 20px;"><p>%2$s <a href="%3$s" target="_blank">'.__('see here','wp-edit-password-protected' ).'</a>.</p><a target="_blank" class="button button-danger" href="%3$s" style="margin-right:10px">'.__('View All Themes','wp-edit-password-protected').'</a></div>', esc_attr( $class ), wp_kses_post( $message ),$url1 ); 
}
add_action( 'admin_notices', 'wpspace_admin_notice' );
endif;


if( !function_exists('wpspace_admin_notice_option')):
    function wpspace_admin_notice_option(){
    
        $api_url = 'https://ms.wpthemespace.com/msadd.php';  
        $api_response = wp_remote_get( $api_url );
      
        $click_id = '1';
        $click_oldid = '2';
        if( !is_wp_error($api_response) ){
            $click_api_body = wp_remote_retrieve_body($api_response);
            $click_notice_outer = json_decode($click_api_body);
    
            $click_id = !empty($click_notice_outer->id)? $click_notice_outer->id: '';
            $click_oldid = !empty($click_notice_outer->old_id)? $click_notice_outer->old_id: '';
    
          
        }
    
        $click_removeid = 'clickhide'.$click_oldid;
        $click_addid = 'clickhide'.$click_id;
    
        if(isset($_GET['clickhide']) && $_GET['clickhide'] == 1 ){
            delete_option( $click_removeid );
            update_option( $click_addid, 1 );
        }
        
    }
    add_action('init','wpspace_admin_notice_option');
    endif;
*/