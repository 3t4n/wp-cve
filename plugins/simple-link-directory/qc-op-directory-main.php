<?php
/**
 * Plugin Name: Simple Link Directory - Lite
 * Plugin URI: https://wordpress.org/plugins/simple-link-directory
 * Description: Directory WordPress plugin to curate topic based link collections. Curate gorgeous Link Directory, Local Business Directory, Partners or Vendors Directory
 * Version: 8.1.4
 * Author: QuantumCloud
 * Author URI: https://www.quantumcloud.com/
 * Requires at least: 4.6
 * Tested up to: 6.4
 * Text Domain: qc-opd
 * Domain Path: /lang/
 * License: GPL2
 */

defined('ABSPATH') or die("No direct script access!");

//Custom Constants
define('QCOPD_URL', plugin_dir_url(__FILE__));
define('QCOPD_IMG_URL', QCOPD_URL . "assets/images");
define('QCOPD_ASSETS_URL', QCOPD_URL . "assets");

define('QCOPD_DIR', dirname(__FILE__));
define('QCOPD_INC_DIR', QCOPD_DIR . "/inc");
define('OCOPD_TPL_URL', QCOPD_URL . "templates");
define('OCOPD_TPL_DIR', QCOPD_DIR . "templates");

//Include files and scripts

require_once( 'qc-op-directory-post-type.php' );
require_once( 'qc-op-directory-assets.php' );
require_once( 'qc-op-directory-shortcodes.php' );
require_once( 'embed/embedder.php' );

require_once( 'qcopd-shortcode-generator.php' );
require_once( 'qc-op-directory-import.php' );
require_once( 'qc-opd-ajax-stuffs.php' );

/*05-31-2017*/
require_once('qc-support-promo-page/class-qc-support-promo-page.php');
require_once('class-qc-free-plugin-upgrade-notice.php');
/*05-31-2017 - Ends*/
/* Option page */
require_once('qc-opd-setting-options.php');

require_once('qc-rating-feature/qc-rating-class.php');
require_once('modules/addons/addons.php');


add_action('wp_head', 'qcopd_add_outbound_click_tracking_script');

function qcopd_add_outbound_click_tracking_script()
{


    if(!function_exists('wp_get_current_user')) {
        include(ABSPATH . "wp-includes/pluggable.php");
    }
 
 
	if(is_user_logged_in()){
		$current_user = wp_get_current_user();
		if(in_array('administrator',$current_user->roles)){
		  return;
		}
	}

    $outbound_conf = get_option( 'sld_enable_click_tracking' );

    if ( isset($outbound_conf) && $outbound_conf == 'on' ) {
		wp_enqueue_script( 'sld-admin-trackoutbound-script' );
    }
}

/*Add Promotional Link - Bue Pro - 12-30-2016*/
add_action( 'manage_posts_extra_tablenav', 'promo_link_in_cpt_table' );

function promo_link_in_cpt_table()
{
    $screen = get_current_screen();
    
    $current_screen = $screen->id;
    
    $link = "";
    
    if( $current_screen == 'edit-sld' )
    {   
        $link = '<div class="alignleft actions"><a href="'.esc_url("https://www.quantumcloud.com/products/simple-link-directory/").'" target="_blank" class="button qcsld-promo-link">'.esc_html( "Upgrade to Pro" ).'</a></div>';
    }
    
    echo $link;
    
}

add_action( 'buypro_promotional_link', 'promo_link_in_settings_page' );

function promo_link_in_settings_page()
{
    $screen = get_current_screen();
    
    $current_screen = $screen->id;
    
    $link = "";
    
    $link = '<div class="alignleft actions"><a href="'.esc_url("https://www.quantumcloud.com/products/simple-link-directory/").'" target="_blank" class="button qcsld-promo-link">'.esc_html( "Upgrade to Pro" ).'</a></div>';
    
    echo $link;
    
}

/**
 * Submenu filter function. Tested with Wordpress 4.1.1
 * Sort and order submenu positions to match your custom order.
 *
 * @author Hendrik Schuster <contact@deviantdev.com>
 */
function qclsldf_order_index_catalog_menu_page( $menu_ord ) 
{

  global $submenu;

  // Enable the next line to see a specific menu and it's order positions
  //echo '<pre>'; print_r( $submenu['edit.php?post_type=sld'] ); echo '</pre>'; exit();

    $arr = array();
    if( current_user_can('edit_posts') ){ 

        if(isset($submenu['edit.php?post_type=sld'][5]))
            $arr[] = $submenu['edit.php?post_type=sld'][5];

        if(isset($submenu['edit.php?post_type=sld'][10]))
            $arr[] = $submenu['edit.php?post_type=sld'][10];

        if(isset($submenu['edit.php?post_type=sld'][15]))
            $arr[] = $submenu['edit.php?post_type=sld'][15];

        if(isset($submenu['edit.php?post_type=sld'][16]))
            $arr[] = $submenu['edit.php?post_type=sld'][16];

        if(isset($submenu['edit.php?post_type=sld'][18]))
            $arr[] = $submenu['edit.php?post_type=sld'][18];

        if(isset($submenu['edit.php?post_type=sld'][17]))
            $arr[] = $submenu['edit.php?post_type=sld'][17];

        if(isset($submenu['edit.php?post_type=sld'][250]))
            $arr[] = $submenu['edit.php?post_type=sld'][250];

        if(isset($submenu['edit.php?post_type=sld'][301]))
            $arr[] = $submenu['edit.php?post_type=sld'][301];
    
        if(isset($submenu['edit.php?post_type=sld'][300]))
            $arr[] = $submenu['edit.php?post_type=sld'][300];
    
    }
    $submenu['edit.php?post_type=sld'] = $arr;

    return $menu_ord;

}
add_filter( 'custom_menu_order', 'qclsldf_order_index_catalog_menu_page' );

add_action( 'admin_menu' , 'qcopd_help_link_submenu', 20 );
function qcopd_help_link_submenu(){
    global $submenu;
    
    $link_text = "Help";
    $submenu["edit.php?post_type=sld"][250] = array( $link_text, 'activate_plugins' , admin_url('edit.php?post_type=sld&page=sld_settings#help') );
    ksort($submenu["edit.php?post_type=sld"]);
    
    return ($submenu);
}


function options_instructions_example() {
    global $my_admin_page;
    $screen = get_current_screen();
    
    if ( is_admin() && ($screen->post_type == 'sld') ) {
		wp_enqueue_script( 'jqc-slick.min-js', QCOPD_ASSETS_URL . '/js/slick.min.js', array('jquery'));
        ?>
        <div class="notice notice-info is-dismissible sld-notice" style="display:none"> 
            <div class="sld_info_carousel">

                <div class="sld_info_item"><?php echo esc_html('**SLD Pro Tip: Did you know that you can'); ?> <strong style="color: yellow"><?php echo esc_html('Auto Generate'); ?></strong> <?php echo esc_html('Title, Subtitle & Thumbnail with the Pro Version in Just 2 Clicks?'); ?> <strong style="color: yellow"><?php echo esc_html('Triple Your Link Entry Speed!'); ?></strong></div>
                
                <div class="sld_info_item"><?php echo esc_html('**SLD Tip: Lists are the base pillars of SLD, not individual links. Group your links into different Lists for the best performance.'); ?></div>
                
                <div class="sld_info_item"><?php echo esc_html('**SLD Tip: SLD looks the best when you create multiple Lists and use the Show All Lists mode.'); ?></div>

                <div class="sld_info_item"><?php echo esc_html('**SLD Pro Tip: Did you know that SLD Pro version lets you monetize your directory and earn'); ?> <strong style="color: yellow"><?php echo esc_html('passive income?'); ?></strong> <?php echo esc_html('Upgrade now!'); ?></div>
                
                <div class="sld_info_item"><?php echo esc_html('**SLD Tip: Try to keep the maximum number of links below 30 per list. Create multiple Lists as needed.'); ?></div>

                <div class="sld_info_item"><?php echo esc_html('**SLD Tip: Use the handy shortcode generator to make life easy. It is a small, blue [SLD] button found at the toolbar of any page\'s visual editor.'); ?></div>
                
                <div class="sld_info_item"><?php echo esc_html('**SLD Pro Tip: You can display your'); ?> <strong style="color: yellow"><?php echo esc_html('Lists by category'); ?> </strong><?php echo esc_html('with the SLD pro version.'); ?> <strong style="color: yellow"><?php echo esc_html('16+ Templates, Multi page mode'); ?></strong><?php echo esc_html(', Widgets are also available.'); ?></div>
                
                <div class="sld_info_item"><?php echo esc_html('**SLD Tip: You can create a page with a contact form and link the Add Link button to that page so people can submit links to your directory by email.'); ?></div>

                <div class="sld_info_item"><?php echo esc_html('**SLD Tip: If you are having problem with adding more items or saving a list then you may need to increase max_input_vars value in server. Check the help section for more details.'); ?></div>
                
                <div class="sld_info_item"><?php echo esc_html('**SLD Pro Tip: SLD pro version has'); ?> <strong style="color: yellow"><?php echo esc_html('front end dashboard'); ?></strong> <?php echo esc_html('for user registration and link management. As well as tags and instant search.'); ?> <strong style="color: yellow"><?php echo esc_html('Upgrade to the Pro version now!'); ?></strong></div>

            </div>

        </div>
        <?php
		
		
    }
}

add_action( 'admin_notices', 'options_instructions_example' );

/*
* This is for radium-importer plugin conflict issue.
*/



/**
 * Detect plugin. For use in Admin area only.
 */
// For removing conflict with Demo Data Impoter
class Radium_Theme_Demo_Data_Importer{
	static $instance;
}


add_action( 'admin_menu' , 'qcsld_help_link_submenu', 20 );
function qcsld_help_link_submenu(){
	global $submenu;
	
	$link_text = esc_html("Help");
	$submenu["edit.php?post_type=sld"][250] = array( $link_text, 'activate_plugins' , admin_url('edit.php?post_type=sld&page=sld_settings#help') );
	ksort($submenu["edit.php?post_type=sld"]);
	
	return ($submenu);
}


add_action( 'add_meta_boxes', 'sld_meta_box_video' );
function sld_meta_box_video()
{					                  // --- Parameters: ---
    add_meta_box( 'qc-sld-meta-box-id', // ID attribute of metabox
                  esc_html('Shortcode Generator for SLD'),       // Title of metabox visible to user
                  'sld_meta_box_callback', // Function that prints box in wp-admin
                  'page',              // Show box for posts, pages, custom, etc.
                  'side',            // Where on the page to show the box
                  'high' );            // Priority of box in display order
}

function sld_meta_box_callback( $post )
{
    ?>
    <p>
        <label for="sh_meta_box_bg_effect"><p><?php echo esc_html('Click the button below to generate shortcode'); ?></p></label>
		<input type="button" id="sld_shortcode_generator_meta" class="button button-primary button-large" value="<?php echo esc_attr('Generate Shortcode'); ?>" />
    </p>
    
    <?php
}

//convert previous settings to new settings
add_action( 'plugins_loaded', 'sld_plugin_loaded_fnc' );
function sld_plugin_loaded_fnc(){

	if(!get_option('sld_ot_convrt')){
		$prevOptions = get_option('option_tree');		
		if(!empty($prevOptions) && @array_key_exists('sld_enable_top_part', $prevOptions)){
			
			foreach($prevOptions as $key=>$val){
				
				update_option( $key, $val);
			}
		}		
		add_option( 'sld_ot_convrt', 'yes');
	}

}

function sld_activation_redirect( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        if( 'cli' !== php_sapi_name() ){
            exit( wp_redirect( admin_url( 'edit.php?post_type=sld&page=sld_settings#help') ) );
        }
    }
}
add_action( 'activated_plugin', 'sld_activation_redirect' );


if( function_exists('register_block_type') ){
	function qcopd_sld_gutenberg_block() {
	    require_once plugin_dir_path( __FILE__ ).'/gutenberg/sld-block/plugin.php';
	}
	add_action( 'init', 'qcopd_sld_gutenberg_block' );
}


// Remove view from custom post type.
add_filter( 'post_row_actions', 'qc_sld_remove_row_actions', 10, 1 );
function qc_sld_remove_row_actions( $actions )
{
	if( get_post_type() === 'sld' ){
	 unset( $actions['view'] );
	}
	 
	return $actions;
}
// Remove view from taxonomies
add_filter( 'sld_cat_row_actions', 'qc_sld_category_remove_row_actions', 10, 1 );
function qc_sld_category_remove_row_actions($actions){
	unset($actions['view']);
	return $actions;
}

if( is_admin() ){
    require_once('class-plugin-deactivate-feedback.php');
    $SlD_feedback = new SLD_Usage_Feedback( __FILE__, 'plugins@quantumcloud.com', false, true );
}

function sld_remove_admin_menu_items() {
    if( !current_user_can( 'edit_posts' ) ):
        remove_menu_page( 'edit.php?post_type=sld' );
    endif;
}
add_action( 'admin_menu', 'sld_remove_admin_menu_items' );


add_action( 'admin_notices', 'sld_wp_shortcode_notice',100 );

function sld_wp_shortcode_notice(){

    global $pagenow, $typenow;

    if ( isset($typenow) && $typenow == 'sld'  ) {
    ?>

       <!--  <div id="message-sld" class="notice notice-info is-dismissible">
            <?php
            printf(
                __('%s  %s  %s', 'dna88-wp-notice'),
                '<a href="'.esc_url('https://www.quantumcloud.com/products/simple-link-directory/').'" target="_blank">',
                '<img src="'.esc_url(QCOPD_ASSETS_URL).'/images/new-year-23.gif" >',
                '</a>'
            );

            ?>
        </div> -->


        <div id="message" class="notice notice-info is-dismissible">
            <p>
                <?php
                printf(
                    __('%s Simple Link Directory %s works the best when you create multiple Lists and show them all in a page. Use the following shortcode to display All lists on any page:  %s  %s  %s  Use the %s shortcode generator %s to select style and other options. ', 'dna88-wp-notice'),
                    '<strong>',
                    '</strong>',
                    '<code>',
                    ' [qcopd-directory mode="all" column="2" style="simple" orderby="date" order="DESC" enable_embedding="false"] ',
                    '</code>',
                    '<strong>',
                    '</strong>'
                );

                ?>
            </p>
        </div>
    <?php 
        
    }

}