<?php
/*
Plugin Name: Easy Notify Lite
Plugin URI: http://www.ghozylab.com/plugins/easy-notify/
Description: Easy Notify Lite - Display notify, announcement and subscribe form ( Opt-in ) with very ease, fancy and elegant.<a href="http://ghozylab.com/plugins/pricing/#tab-1408601400-2-44" target="_blank"><strong> Upgrade to Pro Version Now</strong></a> and get a tons of awesome features.
Author: GhozyLab, Inc.
Text Domain: easy-notify-lite
Domain Path: /languages
Version: 1.1.29
Author URI: http://www.ghozylab.com/
*/

if ( ! defined('ABSPATH') ) {
	die('Please do not load this file directly.');
}

/*-------------------------------------------------------------------------------*/
/*   MAIN DEFINES
/*-------------------------------------------------------------------------------*/
// plugin path
if ( ! defined( 'ENOTIFY_DIR' ) ) {
	$en_plugin_dir = substr(plugin_dir_path(__FILE__), 0, -1);
	define( 'ENOTIFY_DIR', $en_plugin_dir );
}

// plugin url
if ( ! defined( 'ENOTIFY_URL' ) ) {
	$en_plugin_url = substr(plugin_dir_url(__FILE__), 0, -1);
	define( 'ENOTIFY_URL', $en_plugin_url );
}

if ( !defined( 'ENOTIFY_VERSION' ) ) {
	define( 'ENOTIFY_VERSION', '1.1.29' );
}

if ( !defined( 'ENOTIFY_NAME' ) ) {
	define( 'ENOTIFY_NAME', 'Easy Notify Lite' );
}
	
// WP Version	
if ( version_compare( get_bloginfo( 'version' ), '3.5', '<' ) ) {
	define( 'NOTY_WP_VER', 'l35' );	
}
else {
	define( 'NOTY_WP_VER', 'g35' );		
}	

// Pro Price
if ( !defined( 'ENOTY_PRO_PRICE' ) ) {
	define( 'ENOTY_PRO_PRICE', '14' );
}

// Pro+
if ( !defined( 'ENOTY_PRO_PLUS_PRICE' ) ) {
	define( 'ENOTY_PRO_PLUS_PRICE', '24' );
}

// Pro++ Price
if ( !defined( 'ENOTY_PRO_PLUS_PLUS_PRICE' ) ) {
	define( 'ENOTY_PRO_PLUS_PLUS_PRICE', '30' );
}

// PHP Version
if ( version_compare( PHP_VERSION, '7.1', '>' ) ) {
	define( 'ENOTY_PHP7', true );
} else {
	define( 'ENOTY_PHP7', false );
}

if ( ! defined( "ENOTY_PLUGIN_SLUG" ) ) define( "ENOTY_PLUGIN_SLUG", "easy-notify-lite/easy-notify-lite.php" );

register_activation_hook( __FILE__, 'easynotify_plugin_activate' );
add_action( 'plugins_loaded', 'easynotify_first_load' );
add_action( 'admin_init', 'easynotify_plugin_updater', 0 );
add_action( 'init', 'easynotify_general_init' );
add_action( 'init', 'easynotify_post_type' );
add_action( 'admin_menu', 'easynotify_rename_submenu' );
add_filter( 'manage_edit-easynotify_columns', 'easynotify_edit_columns' );
add_filter( 'manage_posts_custom_column',  'easynotify_edit_columns_list', 10, 2 ); 
add_filter( 'post_row_actions','easynotify_hide_post_view', 10, 2 );
add_filter( 'plugin_action_links', 'easynotify_settings_link', 10, 2 );
add_action( 'admin_init', 'easynotify_load_plugin' );
add_action( 'admin_head','enoty_dashboard_script_styles' );
add_filter( 'widget_text', 'do_shortcode', 11 );
add_filter( 'the_excerpt', 'shortcode_unautop' );
add_filter( 'the_excerpt', 'do_shortcode' );

function easynotify_first_load() {
	
	require_once ABSPATH.'/wp-admin/includes/plugin.php';
	
	load_plugin_textdomain( 'easy-notify-lite', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	
	/*-------------------------------------------------------------------------------*/
	/*   REQUIRES WORDPRESS VERSION ( MIN VERSION 3.3 ) 
	/*-------------------------------------------------------------------------------*/
	$plugin = plugin_basename( __FILE__ );

	if ( version_compare( get_bloginfo( 'version' ), "3.3", "<" ) ) {
		
		if ( is_plugin_active( $plugin ) ) {
			deactivate_plugins( $plugin );
			wp_die( "".ENOTIFY_NAME." requires WordPress 3.3 or higher, and has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>" );
		}
		
	}
	
	/*-------------------------------------------------------------------------------*/
	/*   REQUIRES PHP VERSION ( MIN PHP 5.2 ) 
	/*-------------------------------------------------------------------------------*/
	if ( version_compare( PHP_VERSION, '5.2', '<' ) ) {
		
		if ( is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX) ) {
			deactivate_plugins( __FILE__ );
			wp_die( "".ENOTIFY_NAME." requires PHP 5.2 or higher. The plugin has now disabled itself. Please ask your hosting provider for this issue.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>" );
		} else {
			return;
		}
		
	}
	
	/*-------------------------------------------------------------------------------*/
	/*   REQUIRES PHP GD EXT
	/*-------------------------------------------------------------------------------*/
	if (!extension_loaded('gd') && !function_exists('gd_info')) {
		
		if ( is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX) ) {
			deactivate_plugins( __FILE__ );
			wp_die( "".ENOTIFY_NAME." requires <strong>GD extension</strong>. The plugin has now disabled itself. If you are using shared hosting please contact your webhost and ask them to install the <strong>GD library</strong>.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>" );
		} else {
			return;
		}
		
	}
	
}

/*-------------------------------------------------------------------------------*/
/*   General Init
/*-------------------------------------------------------------------------------*/
function easynotify_general_init() {
	
	include_once( ENOTIFY_DIR . '/inc/functions/enoty-functions.php' );
	
	if ( is_admin() ){
		
		include_once( ENOTIFY_DIR . '/layouts/enoty-preview.php' );
		include_once( ENOTIFY_DIR . '/inc/enoty-options.php' );
		include_once( ENOTIFY_DIR . '/inc/enoty-settings.php' );
		include_once( ENOTIFY_DIR . '/inc/enoty-metaboxes.php' );
		require_once( ENOTIFY_DIR . '/inc/enoty-freeplugins.php' );
		require_once( ENOTIFY_DIR . '/inc/enoty-featured.php' );
		require_once( ENOTIFY_DIR . '/inc/enoty-notice.php' );

	
	}
	
	include_once( ENOTIFY_DIR . '/inc/enoty-frontend.php' );
	include_once( ENOTIFY_DIR . '/inc/enoty-shortcode.php' );
	include_once( ENOTIFY_DIR . '/inc/functions/enoty-loader.php' );
	
	if( ! is_admin() ) wp_enqueue_script( 'jquery' );
	
	// Load Enoty
	easynotify_init();

}

/*-------------------------------------------------------------------------------*/
/*   REGISTER CUSTOM POSTTYPE
/*-------------------------------------------------------------------------------*/
function easynotify_post_type() {
	
	$labels = array(
		'name' 				=> _x( 'Easy Notify Lite', 'post type general name', 'easy-notify-lite' ),
		'singular_name'		=> _x( 'Easy Notify Lite', 'post type singular name', 'easy-notify-lite' ),
		'add_new' 			=> __( 'Add New Notify', 'easy-notify-lite' ),
		'add_new_item' 		=> __( 'Easy Notify Item', 'easy-notify-lite' ),
		'edit_item' 		=> __( 'Edit Notify', 'easy-notify-lite' ),
		'new_item' 			=> __( 'New Notify', 'easy-notify-lite' ),
		'view_item' 		=> __( 'View Notify', 'easy-notify-lite' ),
		'search_items' 		=> __( 'Search Media', 'easy-notify-lite' ),
		'not_found' 		=> __( 'No Notify Found', 'easy-notify-lite' ),
		'not_found_in_trash'=> __( 'No Notify Found In Trash', 'easy-notify-lite' ),
		'parent_item_colon' => __( 'Parent Notify', 'easy-notify-lite' ),
		'menu_name'			=> __( 'Easy Notify Lite', 'easy-notify-lite' )
	);

	$taxonomies = array();
	$supports = array( 'title', 'thumbnail' );
	
	$post_type_args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> __( 'Easy Notify', 'easy-notify-lite' ),
		'public' 			=> false,
		'show_ui' 			=> true,
		'exclude_from_search' => true,
		'publicly_queryable'=> true,
		'query_var'			=> true,
		'capability_type' 	=> 'post',
		'has_archive' 		=> false,
		'hierarchical' 		=> false,
		'rewrite' 			=> array( 'slug' => 'easy-notify-lite', 'with_front' => false ),
		'supports' 			=> $supports,
		'menu_position' 	=> 21,
		'menu_icon' 		=>  plugins_url( 'inc/images/easynotify-cp-icon.png' , __FILE__ ),		
		'taxonomies'		=> $taxonomies
	);

	 register_post_type( 'easynotify', $post_type_args );
	 
}

/*--------------------------------------------------------------------------------*/
/*  Add Custom Columns for Notify 
/*--------------------------------------------------------------------------------*/
function easynotify_edit_columns( $easynotify_columns ){
	
	$easynotify_columns = array(  
		'cb' => '<input type="checkbox" />',  
		'title' => _x( 'Title', 'column name', 'easy-notify-lite' ),
		'enoty_layout' => __( 'Layout Mode', 'easy-notify-lite'),
		'enoty_shortcode' => __( 'Shortcode', 'easy-notify-lite'),
		'enoty_id' => __( 'ID', 'easy-notify-lite'),
		'enoty_preview' => __( 'Preview', 'easy-notify-lite'),
		'enoty_cookie' => __( 'Clear Cookies', 'easy-notify-lite')			
			
	);
	return $easynotify_columns;
	
}

function easynotify_edit_columns_list( $easynotify_columns, $post_id ){  

	switch ( $easynotify_columns ) {
		
	    case 'enoty_layout':
		
		echo '<img class="enoty-layout-mode" width="90" height="60" src="'.plugins_url( 'css/images/layouts/'.get_post_meta( $post_id, 'enoty_cp_layoutmode', true ).'' , __FILE__ ).'" alt="Layout Mode"></img>';

	        break;
		
	    case 'enoty_id':
		
		echo $post_id;

	        break;
		
	    case 'enoty_shortcode':
		
		//echo '<span class="scode-block">[easy-notify id='.$post_id.']</span>';
		echo '<input size="25" readonly="readonly" value="[easy-notify id='.$post_id.']" class="enoty-scode-block" type="text">';

	        break;
			
	    case 'enoty_cookie':
		
		echo '<span class="button resetcookie" id="'.'notify-'.$post_id.'"><span class="dashicons dashicons-trash" style="margin: 4px 5px 0px 0px;"></span>Clear Cookies</a>';

	        break;
			
	    case 'enoty_preview':
		
		echo '<a class="button notifyprev" href="admin-ajax.php?action=easynotify_generate_preview&noty_id='.$post_id.'" target="_blank"><span class="dashicons dashicons-desktop" style="margin: 4px 5px 0px 0px;"></span>Preview</a>';
	        break;

		default:
			break;
	}
	
}

/*-------------------------------------------------------------------------------*/
/*   RENAME SUBMENU
/*-------------------------------------------------------------------------------*/
function easynotify_rename_submenu() {  
   
    global $submenu;     
	$submenu['edit.php?post_type=easynotify'][5][0] = __( 'Overview', 'easy-notify-lite' );  

}  

/*-------------------------------------------------------------------------------*/
/*   Hide & Disabled View, Quick Edit and Preview Button
/*-------------------------------------------------------------------------------*/
function easynotify_hide_post_view( $actions ) {
	
	global $post;
    if( $post->post_type == 'easynotify' ) {
		unset( $actions['view'] );
		unset($actions['inline hide-if-no-js']);
	}
    return $actions;
	
}

/*-------------------------------------------------------------------------------*/
/*   ADD SETTINGS LINK
/*-------------------------------------------------------------------------------*/
function easynotify_settings_link( $link, $file ) {
	
	static $this_plugin;
	
	if ( !$this_plugin )
		$this_plugin = plugin_basename( __FILE__ );

	if ( $file == $this_plugin ) {
		$settings_link = '<a href="' . admin_url( 'edit.php?post_type=easynotify&page=easynotify_settings' ) . '">' . __( 'Settings', 'easy-notify-lite' ) . '</a>';
		array_unshift( $link, $settings_link );
	}
	
	return $link;
	
}

/*-------------------------------------------------------------------------------*/
/*   FIRST ACTION
/*-------------------------------------------------------------------------------*/
function easynotify_plugin_activate() {

  add_option( 'Activated_EN_Plugin', 'enoty-activate' );

}

function easynotify_load_plugin() {

    if ( is_admin() && get_option( 'Activated_EN_Plugin' ) == 'enoty-activate' ) {
		
		$enoty_optval = get_option( 'easynotify_opt' );
		
		if ( !is_array( $enoty_optval ) ) update_option( 'easynotify_opt', array() );		
		
		$tmp = get_option( 'easynotify_opt' );
		if ( isset( $tmp['easynotify_deff_init'] ) != '1' ) {
			easynotify_1st_config();
			}

        delete_option( 'Activated_EN_Plugin' );
		
		if ( ! is_network_admin() ) wp_redirect("edit.php?post_type=easynotify&page=easynotify_free_plugins");
		
    }
	
}

/*
|--------------------------------------------------------------------------
| PLUGIN AUTO UPDATE
|--------------------------------------------------------------------------
*/
function easynotify_plugin_updater() {
	
	$enoty_is_auto_update = enoty_get_option( 'easynotify_disen_autoupdt' );
	
	switch ( $enoty_is_auto_update ) {
		
		case '1':
			if ( !wp_next_scheduled( "enoty_auto_update" ) ) {
				wp_schedule_event( time(), "daily", "enoty_auto_update" );
				}
			add_action( "enoty_auto_update", "plugin_enoty_auto_update" );
		break;
		
		case '':
			wp_clear_scheduled_hook( "enoty_auto_update" );
		break;
						
	}

}
		
function plugin_enoty_auto_update() {
	try
	{
		require_once( ABSPATH . "wp-admin/includes/class-wp-upgrader.php" );
		require_once( ABSPATH . "wp-admin/includes/misc.php" );
		define( "FS_METHOD", "direct" );
		require_once( ABSPATH . "wp-includes/update.php" );
		require_once( ABSPATH . "wp-admin/includes/file.php" );
		wp_update_plugins();
		ob_start();
		$plugin_upg = new Plugin_Upgrader();
		$plugin_upg->upgrade( "easy-notify-lite/easy-notify-lite.php" );
		$output = @ob_get_contents();
		@ob_end_clean();
	}
	catch(Exception $e) {}
	
}

function enoty_dashboard_script_styles() {
	
	global $current_screen;
	
	if( 'easynotify' == $current_screen->post_type ) {

    	echo '<style type="text/css">
		.enoty-scode-block {
		padding: 4px;
		background: none repeat scroll 0% 0% rgba(0, 0, 0, 0.07);
		font-family: "courier new",courier;
		cursor: pointer;
		text-align: center;
		font-size:1em !important;
		border: 1px dashed #bab6ac !important;
		}
		.enoty-shortcode-message {
    	font-style: italic;
    	color: #2EA2CC !important;
		}
		.column-enoty_layout {width:130px;}
		.column-enoty_shortcode {width:215px;}
		.column-enoty_id {width:75px;}
    	</style>';
		?>
		<script>
		jQuery(function($) {
			$('.enoty-scode-block, .enoty-sc-metabox').click( function () {
				try {
					//select the contents
					this.select();
					//copy the selection
					document.execCommand('copy');
					//show the copied message
					$('.enoty-shortcode-message').remove();
					$(this).after('<p class="enoty-shortcode-message"><?php _e( 'Shortcode copied to clipboard','image-slider-widget' ); ?></p>');
				} catch(err) {
					console.log('Oops, unable to copy!');
				}
			});
		});
        </script>
	<?php
		
	}

}