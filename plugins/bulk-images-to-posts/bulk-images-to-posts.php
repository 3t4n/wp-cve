<?php
/*
* Plugin Name: Bulk Images to Posts
* Plugin URI: http://www.mezzaninegold.com
* Text Domain: bulk-images-to-posts
* Domain Path: /lang
* Description: Bulk upload images to automatically create posts / custom posts with featured images.
* Version: 3.6.6.3
* Author: Mezzanine gold
* Author URI: http://mezzaninegold.com
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


add_action('plugins_loaded', 'bip_load_textdomain');
function bip_load_textdomain() {
	load_plugin_textdomain( 'bulk-images-to-posts', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}


require_once( 'includes/bip-category-walker.php' );
require_once( 'includes/bip-settings.php' );

add_action( 'admin_init', 'bip_admin_init' );
 
   function bip_admin_init() {
       /* Register our stylesheet and javascript. */
       wp_register_style( 'bip-css', plugins_url('css/style.css', __FILE__) );
       wp_register_script( 'bip-js', plugins_url('js/script.js', __FILE__), array( 'jquery' ), '', true );
       wp_register_script( 'dropzone-js', plugins_url('js/dropzone.js', __FILE__), array( 'jquery' ), '', true );     
   }   
   function bip_admin_styles() {
       wp_enqueue_style( 'bip-css' );
       wp_enqueue_script( 'bip-js' );
       wp_enqueue_script( 'dropzone-js' );
	   wp_enqueue_script( 'jquery-form' );
	   wp_enqueue_style( 'dashicons' );
   }

   function bip_admin_notice(){ ?>
    <div class="notice notice-error error is-dismissible">
        <p><?php _e( 'Bulk Images to Posts','bulk-images-to-posts') ?>: <a href="<?php echo site_url('wp-admin/admin.php?page=bip-settings-page'); ?>"><?php _e('Please update your settings before uploading!', 'bulk-images-to-posts' ); ?></a></p>
    </div>
  
<?php }
$bipUpdated = get_option('bip_updated');
if ( empty($bipUpdated) ) {
	add_action('admin_notices', 'bip_admin_notice');
} 


// create plugin settings menu
add_action('admin_menu', 'bip_create_menu');


// Used in category walker
function bip_in_array_check($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && bip_in_array_check($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

function bip_create_menu() {

    // create new top-level menu
    global $bip_admin_page;
    $bip_admin_page = add_menu_page(__('Bulk Images to Posts Uploader','bulk-images-to-posts'), __('Bulk','bulk-images-to-posts'), 'manage_options', 'bulk-images-to-post','bip_upload_page','dashicons-images-alt2');
    // create submenu pages
    add_submenu_page( 'bulk-images-to-post', __('Bulk Images to Post - Upload','bulk-images-to-posts'), __('Uploader','bulk-images-to-posts'), 'manage_options', 'bulk-images-to-post');
	$bip_submenu_page = add_submenu_page( 'bulk-images-to-post', __('Bulk Images to Post - Settings','bulk-images-to-posts'), __('Settings','bulk-images-to-posts'), 'manage_options', 'bip-settings-page', 'bip_settings_page');
    // call register settings function
    add_action( 'admin_init', 'bip_register_settings' );
    // enqueue scripts
    add_action( 'admin_print_styles-' . $bip_admin_page, 'bip_admin_styles' );
    add_action( 'admin_print_styles-' . $bip_submenu_page, 'bip_admin_styles' );
     
}


	/*
	* Register Setting - Needs updating to an array of options. 
	*/
function bip_register_settings() {
    register_setting( 'bip-upload-group', 'bip_terms' );
    register_setting( 'bip-settings-group', 'bip_updated' );
    register_setting( 'bip-settings-group', 'bip_post_type' );
    register_setting( 'bip-settings-group', 'bip_image_title' );
    register_setting( 'bip-settings-group', 'bip_post_status' );
    register_setting( 'bip-settings-group', 'bip_taxonomy' );
    register_setting( 'bip-settings-group', 'bip_image_content' );
    register_setting( 'bip-settings-group', 'bip_image_content_size' );
}

	/*
	* The main upload page 
	*/
function bip_upload_page() { ?>

<div class="grid">
	<div class="whole unit">

	<h2><?php _e('Bulk Images to Posts - Uploader','bulk-images-to-posts'); ?></h2>
	<p><?php _e('Please use the settings page to configure your uploads','bulk-images-to-posts'); ?>
		<a href="<?php echo site_url('wp-admin/admin.php?page=bip-settings-page') ?>">
			<?php _e('Click here','bulk-images-to-posts'); ?>
		</a>
	</p>
	</div>
</div>
<div id="poststuff" class="grid">
        <div class="one-third unit">
			<form method="post" action="options.php" id="bip-upload-form">
			    <?php settings_fields( 'bip-upload-group' ); ?>
			    <?php do_settings_sections( 'bip-upload-group' ); ?>
			
					

				    <?php
$selected_taxs = get_option('bip_taxonomy');
if (!empty($selected_taxs)) {
foreach ($selected_taxs as $selected_tax) { ?>


					<?php
					$selected_cats = get_option('bip_terms');
				    $walker = new Walker_Bip_Terms( $selected_cats, $selected_tax ); ?>
				    <div class="postbox">
					  	<div title="Click to toggle" class="handlediv"><br></div>
					  	<h3 class="hndle"><span><?php echo $selected_tax ?></span></h3>
					    <div class="inside">
						    <div class="buttonbox">
						    <p class="uncheck"><input type="button" class="check button button-primary" value="Uncheck All" /></p>
						    <?php submit_button(); ?>
						    </div>
						    <div class="categorydiv">
							    <div class="tabs-panel">
								    <div class="checkbox-container">
									    <ul class="categorychecklist ">
											<?php 
										    $args = array(
										    'descendants_and_self'  => 0,
										    'selected_cats'         => $selected_cats,
										    'popular_cats'          => false,
										    'walker'                => $walker,
										    'taxonomy'              => $selected_tax,
										    'checked_ontop'         => false ); ?>
											<?php wp_terms_checklist( 0, $args ); ?>
									    </ul>
								    </div>
							    </div>
						    </div>
					    </div>
				    </div>

				    <?php } } ?>

			</form>
			<div id="saveResult"></div>
</div>
<div class="two-thirds unit">
	<div class="postbox">
		<div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span><?php _e('Images','bulk-images-to-posts'); ?></span></h3>
			<?php include 'includes/bip-dropzone.php';?>
		</div>
	</div>
</div>
<?php } ?>