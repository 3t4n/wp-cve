<?php
/**
 * Plugin Name: LH Add Media from URL
 * Plugin URI: https://lhero.org/portfolio/lh-add-media-from-url/
 * Author: Peter Shaw
 * Author URI: https://shawfactor.com
 * Text Domain: lh-add-media-from-url
 * Domain Path: /languages
 * Version: 1.23
 * Description: This plugin allows you to fetch the remote file and save to your local WordPress, via wp-admin or bookmarklet.
 */

if (!class_exists('LH_add_media_from_url_plugin')) {


class LH_add_media_from_url_plugin {

    private static $instance;
    var $upload_result = false;

    static function return_plugin_namespace(){
    
        return 'lh_add_media_from_url';
    
    }
    
    static function return_plugin_text_domain(){

        return 'lh-add-media-from-url';
        
    }
    
    static function isValidURL($url){
        
        if (empty($url)){
            
            return false;
            
        } else {
            
            return (bool)wp_parse_url($url);
            
        }
    
    }

    static function reconstruct_url($url){
        
        $url_parts = wp_parse_url($url);
        $constructed_url = $url_parts['scheme'] . '://' . $url_parts['hostname'] . $url_parts['path'];
    
        return $constructed_url;
        
    }


    static function return_bookmarklet_string(){
    
        $string = "javascript: (function() { var jsScript = document.createElement('script'); var jsScript = document.createElement('script'); jsScript.setAttribute('src', '".plugins_url( 'bookmarklet.php', __FILE__ )."'); document.getElementsByTagName('head')[0].appendChild(jsScript); })();";
    
        $string = "javascript: (function() { window.location.href='".admin_url( 'upload.php?page=lh-add-media-from-url.php')."&lh_add_media_from_url-file_url=' + encodeURIComponent(location.href);})();";
    
        return $string;
    
    }


    static function handle_upload_v2(){

        if (current_user_can('upload_files')){

            if (!wp_verify_nonce( $_POST['lh_add_media_from_url-nonce'], "lh_add_media_from_url-file_url")) {
                
        		return new WP_Error('lh_add_media_from_url', 'Could not verify request nonce');
        		
        	}
	
	
            $upload_url = $_POST['lh_add_media_from_url-file_url'];
	
            $upload_url = str_replace(' ', '%20', $upload_url);

            $upload_url = esc_url($upload_url);


            if (!class_exists('LH_copy_from_url_class')) {

                include_once("includes/lh-copy-from-url-class.php");

            }

            $id = LH_copy_from_url_class::save_external_file($upload_url,0);

            return $id;

        }

    }



    public function add_media_from_url() {

        global $pagenow;

        //Check to make sure we're on the right page and performing the right action
        if( 'upload.php' != $pagenow ){
	
	        return false;
    
        } elseif ( empty( $_POST[ 'lh_add_media_from_url-file_url' ] ) ){
        
            return false;
        		
        } else {
        
        	
            $return = self::handle_upload_v2();
            
            if ( is_wp_error( $return ) ) {
            
                //Upload has failed add
                $this->upload_result = $return;
            
            } else {
            
                //Upload has succeeded, redirect to mediapage
                wp_safe_redirect( admin_url( 'post.php?post='.$return.'&action=edit') );
                exit();
            
            }
            
        }
        
    }

    public function plugin_menu() {
    
        add_media_page(__('LH Add Media from URL', self::return_plugin_text_domain() ), __('Add from URL', self::return_plugin_text_domain()), 'upload_files', 'lh-add-media-from-url.php', array($this,'plugin_options'));
    
    }

    public function plugin_options() {

        if (!current_user_can('upload_files')){

            wp_die( __('You do not have sufficient permissions to access this page.',self::return_plugin_text_domain() ) );

        }

        echo "<h1>" . __( 'Add Media from URL', self::return_plugin_text_domain() ) . "</h1>";


        if (!empty($this->upload_result)){

            if ( is_wp_error( $this->upload_result ) ) {

                foreach ( $this->upload_result->get_error_messages() as $error ) {

                    echo '<strong>'.__( 'Error', self::return_plugin_text_domain() ).'</strong>: ';
                    echo $error . '<br/>';

                }

            } 

        }

        if (!empty($_POST[ 'lh_add_media_from_url-file_url' ]) && wp_http_validate_url($_POST[ 'lh_add_media_from_url-file_url' ])){
        
            $value = $_POST['lh_add_media_from_url-file_url'];
        
        } elseif (isset($_GET['lh_add_media_from_url-file_url']) && wp_http_validate_url($_GET['lh_add_media_from_url-file_url'])){
        
            $value = $_GET['lh_add_media_from_url-file_url'];
        
        }

        // Now display the upload form
        include ('partials/upload.php');


        if (empty($value)){  

            echo '<h4>'.__( 'Bookmarklet', self::return_plugin_text_domain() ).'</h4>';
            echo '<p>'.__( 'Drag the bookmarklet below to your bookmarks bar. Then, when you find a file online you want to upload, simply "Upload" it', self::return_plugin_text_domain() ).'</p>';
            echo '<p><a title="'.__( 'Bookmark this link', self::return_plugin_text_domain() ).'" href="'.self::return_bookmarklet_string().'">'.__( 'Upload URL to ', self::return_plugin_text_domain()).get_bloginfo("name").'</a><br/>';
            echo __( ' or edit your bookmarks and paste the below code', self::return_plugin_text_domain() ).'<br/>';
            echo self::return_bookmarklet_string().'</p>';

        }
        
    }

    public function plugins_init(){
    
        load_plugin_textdomain( self::return_plugin_text_domain(), false, basename( dirname( __FILE__ ) ) . '/languages' ); 
        
        add_action('admin_menu', array($this,'plugin_menu'));
        add_action( 'admin_init', array($this,'add_media_from_url'));
        
    }

    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        
        if (null === self::$instance) {
            
            self::$instance = new self();
            
        }
 
        return self::$instance;
        
    }
    


    public function __construct() {
        
        add_action( 'plugins_loaded', array($this,'plugins_init'));
    
    }

}

$lh_add_media_from_url_instance = LH_add_media_from_url_plugin::get_instance();


}


?>