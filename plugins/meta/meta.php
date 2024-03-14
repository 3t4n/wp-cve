<?php
/**
 * @package Meta
 * @author Mervin Praison
 * @version 1.0
 */
/*
    Plugin Name: Meta
    Plugin URI: http://mervin.info/meta/
    Description: A meta box which helps us to add content or scripts to any part of the website for each individual post/page. Easy to Implement with shortcode and easy integration with the theme 
    Author: Mervin Praison
    Version: 1.0
    License: GPL
    Author URI: http://mervin.info/
    Last change: 09.03.2012
*/
/**
 * Example for use outside the loop: <?php if ( function_exists('getmetacontent') ) getmetacontent(); ?>
 * <?php getmetacontent($post->ID); ?>
 * @param $id Integer - Post-ID
 */
//avoid direct calls to this file, because now WP core and framework has been used
if ( !function_exists('add_action') ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
if ( function_exists('add_action') ) {
    //WordPress definitions
    if ( !defined('WP_CONTENT_URL') )
        define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
    if ( !defined('WP_CONTENT_DIR') )
        define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
    if ( !defined('WP_PLUGIN_URL') )
        define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
    if ( !defined('WP_PLUGIN_DIR') )
        define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');
    if ( !defined('PLUGINDIR') )
        define( 'PLUGINDIR', 'wp-content/plugins' ); // Relative to ABSPATH.  For back compat.
    if ( !defined('WP_LANG_DIR') )
        define('WP_LANG_DIR', WP_CONTENT_DIR . '/languages');
    // plugin definitions
    define( 'MP_ME_BASENAME', plugin_basename(__FILE__) );
    define( 'MP_ME_BASEDIR', dirname( plugin_basename(__FILE__) ) );
    define( 'MP_ME_CONTENT', 'meta-content-types' );
}
if ( !class_exists( 'MetaContentClass' ) ) {
    class MetaContentClass {
        // constructor
        function MetaContentClass() {
            if (is_admin() ) {
                add_action( 'admin_init', array(&$this, 'on_admin_init') );
                add_action( 'wp_insert_post', array(&$this, 'on_wp_insert_post'), 10, 2 );
                add_action( 'init', array(&$this, 'textdomain') );
                register_uninstall_hook( __FILE__, array(&$this, 'uninstall') );
                add_action( "admin_print_scripts-post.php", array($this, 'enqueue_script') );
                add_action( "admin_print_scripts-post-new.php", array($this, 'enqueue_script') );
                add_action( "admin_print_scripts-page.php", array($this, 'enqueue_script') );
                add_action( "admin_print_scripts-page-new.php", array($this, 'enqueue_script') );
            }
        }
        // active for multilanguage
        function textdomain() {
            if ( function_exists('load_plugin_textdomain') )
                load_plugin_textdomain( MP_ME_CONTENT, false, dirname( MP_ME_BASENAME ) . '/languages' );
        }
        // unsintall all postmetadata
        function uninstall() {
            $all_posts = get_posts('numberposts=0&post_type=post&post_status=');
            foreach( $all_posts as $postinfo) {
                delete_post_meta($postinfo->ID, '_meta-content-types');
            }
        }
        // add script
        function enqueue_script() {
            wp_enqueue_script( 'tinymce4dt', WP_PLUGIN_URL . '/' . MP_ME_BASEDIR . '/js/script.js', array('jquery') );
        }
        // admin init
        function on_admin_init() {
            if ( !current_user_can( 'publish_posts' ) )
                return;
            add_meta_box( 'metacontent_types',
                                    __( 'Meta Data ( Either Text or Scripts )', MP_ME_CONTENT ),
                                    array( &$this, 'meta_box' ),
                                    'post', 'normal', 'high'
                                    );
add_meta_box( 'metacontent_types',
                                    __( 'Meta Data ( Either Text or Scripts )', MP_ME_CONTENT ),
                                    array( &$this, 'meta_box' ),
                                    'page', 'normal', 'high'
                                    );
            // remove meta box for trackbacks
            remove_meta_box('trackbacksdiv', 'post', 'normal');
            // remove meta box for custom fields
            remove_meta_box('postcustom', 'post', 'normal');
        }
        // check for preview
        function is_page_preview() {
            $id = (int)$_GET['preview_id'];
            if ($id == 0) $id = (int)$_GET['post_id'];
            $preview = $_GET['preview'];
            if ($id > 0 && $preview == 'true') {
                global $wpdb;
                $type = $wpdb->get_results("SELECT post_type FROM $wpdb->posts WHERE ID=$id");
                if ( count($type) && ($type[0]->post_type == 'page') && current_user_can('edit_page') )
                    return true;
            }
            return false;
        }
        // after save post, save meta data for plugin
        function on_wp_insert_post($id) {
            global $id;
            if ( !isset($id) )
                $id = (int)$_REQUEST['post_ID'];
            if ( $this->is_page_preview() && !isset($id) )
                $id = (int)$_GET['preview_id'];
            if ( !current_user_can('edit_post') )
                return;
            
            if ( isset($_POST['dt-meta-content']) && $_POST['dt-meta-content'] != '' )
                $this->data['meta-content'] = $_POST['dt-meta-content'];
           
            if ( isset($this->data) && $this->data != '' )
                update_post_meta($id, '_meta-content-types', $this->data);
        }
        // load post_meta_data
        function load_post_meta($id) {
            return get_post_meta($id, '_meta-content-types', true);
        }
        // meta box on post/page
        function meta_box($data) {
            $value = $this->load_post_meta($data->ID);
            ?>
            <table id="dt-page-definition" width="100%" cellspacing="5px">
                
                <tr valign="top">
                    <td><label for="dt-meta-content"><?php _e( 'Meta Content:', MP_ME_CONTENT ); ?></label></td>
                    <td><textarea cols="16" rows="5" id="dt-meta-content" name="dt-meta-content" class="meta-content form-input-tip code" size="20" autocomplete="off" tabindex="6" style="width:90%"/><?php echo $value['meta-content'] ; ?></textarea>
                        <table id="post-status-info" cellspacing="0" style="line-height: 24px;">
                            <tbody>
                                <tr>
                                    <td> </td>
                                    <td> </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                
            </table>
            <?php
        }
        // return facts incl. markup
        function get_individualcontent($id, $type, $value) {
            if (!$value)
                return false;
            if ( $type == '' )
                return false;
            
            if ( 'meta-content' == $type && '' != $value['meta-content'] )
                return $value['meta-content'];
            
        }
        // echo facts, if exists
        function individualcontent($id, $type, $string) {
            if ( $id ) {
                $value = $this->load_post_meta($id);
                return $this->get_individualcontent($id, $type, $value);
            }
        }
        
    } // End class
    // instance class
    $MetaContentClass = new MetaContentClass();
    // use in template
    function getmetacontent($id='', $type = '', $string = '') {
    	global $post;
    	if($id=='')    		   	
        	$id = (int)$post->ID;
        global $MetaContentClass;
        $type = "meta-content";
        echo $MetaContentClass->individualcontent($id, $type, $string);
    }
    function mp_me_shortcode($id, $type = '', $string = '') {
    	global $post;    	
        $id = (int)$post->ID;
        $type = "meta-content";
        global $MetaContentClass;
        $res = $MetaContentClass->individualcontent($id, $type, $string);
        return $res;
    }
    add_shortcode('metacontent','mp_me_shortcode');

} // End if class exists statement
?>