<?php 
/**
 * @package CodeFlavors floating menu
 * @author CodeFlavors ( http://www.codeflavors.com )
 * @version 1.1.5
 */

/*
Plugin Name: CodeFlavors Floating Menu
Plugin URI: http://www.codeflavors.com/codeflavors-floating-menu-wordpress-plugin/
Description: Displays a floating menu on the right or left side of your WordPress blog.
Author: CodeFlavors
Version: 1.1.5
Author URI: http://www.codeflavors.com
Text Domain: codeflavors-floating-menu
Domain Path: /languages
*/	

define('CFM_LOCATION', 'cfm_floating_menu');

/**
 * 
 */
function cfm_menu_plugin_textdomain() {
    // needed only in admin area
    if( !is_admin() ){
        return;
    }
    
    load_plugin_textdomain( 'codeflavors-floating-menu', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'cfm_menu_plugin_textdomain' );

/**
 * Admin menu page
 */
add_action('admin_menu', 'cfm_admin_menu');
// action callback
function cfm_admin_menu(){
	$page = add_submenu_page('themes.php', __('CodeFlavors Menu', 'codeflavors-floating-menu'), __('CodeFlavors Menu', 'codeflavors-floating-menu'), 'manage_options', 'cfm_menu_options', 'cfm_admin_page');
	add_action('load-'.$page, 'cfm_admin_page_load');
}

/**
 * Administration page display
 */
function cfm_admin_page(){
	// get options
	$options = cfm_get_options();
	// errors display
	global $cfm_errors;
	if( is_wp_error( $cfm_errors ) ){
		$options = $_POST;		
	}	
?>
<div class="wrap">
	<div class="icon32" id="icon-themes"><br></div>
	<h2><?php _e('CodeFlavors Floating Menu', 'codeflavors-floating-menu');?></h2>
	<?php if( isset($_GET['message']) && 'success' == $_GET['message'] && !is_wp_error($cfm_errors) ):?>
	<div class="updated"><p><?php _e('Settings saved.', 'codeflavors-floating-menu');?></p></div>
	<?php endif;//end of success message display?>
	<?php if( is_wp_error($cfm_errors) ):?>
	<div class="error">
		<p>
			<?php 
				$err_code = $cfm_errors->get_error_code();
				echo $cfm_errors->get_error_message($err_code);
			?>
		</p>
	</div>	
	<?php endif;?>	
	<p><?php printf( __('To assign a menu, navigate to %1$sMenus%2$s and select a menu for CodeFlavors floating menu location.', 'codeflavors-floating-menu'), '<a href="nav-menus.php">', '</a>' );?></p>
	<form method="post" action="">
		
		<!-- Animation option -->
		<label for="animation"><?php _e('Menu animation', 'codeflavors-floating-menu');?>: </label>
		<select name="animation" id="animation">
			<option value="fixed"><?php _e('Fixed - no animation', 'codeflavors-floating-menu')?></option>
			<option value="animated"<?php if('animated'==$options['animation']):?> selected="selected"<?php endif;?>><?php _e('Animated', 'codeflavors-floating-menu')?></option>
		    <option value="none"<?php if('none'==$options['animation']):?> selected="selected"<?php endif;?>><?php _e('None - will stay into the same place', 'codeflavors-floating-menu')?></option>
		</select><br />
		
		<!-- Menu position option -->
		<label for="position"><?php _e('Menu position', 'codeflavors-floating-menu')?>: </label>
		<select name="position" id="position">
			<option value="left"><?php _e('Left', 'codeflavors-floating-menu');?></option>
			<option value="right"<?php if('right'==$options['position']):?> selected="selected"<?php endif;?>><?php _e('Right', 'codeflavors-floating-menu');?></option>
		</select><br />
		
		<!-- Minimum top distance -->
		<label for="top_distance"><?php _e('Top distance', 'codeflavors-floating-menu');?>: </label>
		<input type="text" name="top_distance" value="<?php echo $options['top_distance'];?>" id="top_distance" size="2" /> px.<br />
		
		<!-- Menu title -->
		<label for="menu_title"><?php _e('Menu title', 'codeflavors-floating-menu');?>: </label>
		<input type="text" name="menu_title" id="menu_title" value="<?php echo $options['menu_title'];?>" size="40" />
		<input type="checkbox" name="hide_menu_title" id="hide_menu_title" value="1"<?php if(1 == $options['hide_menu_title']):?> checked="checked"<?php endif;?> />
		<label for="hide_menu_title" class="inline"> <?php _e('hide it', 'codeflavors-floating-menu');?> </label>
		<br />
		
		<!-- Disable on mobile devices -->
		<label for="hide_on_mobile"><?php _e('Hide menu on mobile devices', 'codeflavors-floating-menu');?>: </label>
		<input type="checkbox" value="1" <?php if(1 == $options['hide_on_mobile']):?> checked="checked"<?php endif;?> name="hide_on_mobile" id="hide_on_mobile" />
		
		<?php wp_nonce_field('cfm_update_settings', 'cfm_nonce');?>
		<?php submit_button( __('Save settings', 'codeflavors-floating-menu'), 'primary', 'submit' );?>
	</form>
</div>
<?php 
}

/**
 * Load action on administration page.
 * Saves options and sets errors to be displayed in case needed
 */
function cfm_admin_page_load(){
	// add styles to administration page
	wp_enqueue_style('cfm_admin_page', plugins_url('css/admin.css', __FILE__));
	// save options
	if( isset( $_POST['cfm_nonce'] ) ){
		if( wp_verify_nonce($_POST['cfm_nonce'], 'cfm_update_settings') ){
			$defaults = cfm_default_options();
			
			global $cfm_errors;
			
			foreach( $defaults as $name=>$value ){
				if( empty( $_POST[$name] ) ){
					if( !empty($value) ){
						$cfm_errors = new WP_Error();
						$cfm_errors->add('cfm_empty_field', __('Settings were not saved. Please try to fill all fields.', 'codeflavors-floating-menu'));
						break;
					}
				}
				
				if( is_numeric( $value ) && isset( $_POST[ $name ] ) ){
					$value = absint( $_POST[$name] );					
				}elseif ( is_string( $value ) ){
					$value = sanitize_text_field( $_POST[ $name ] );
				}				
				$defaults[$name] = $value;
			}	
			// if no errors, do save
			if( !is_wp_error( $cfm_errors ) ){
				update_option('cfm_floating_menu', $defaults);
				$page = add_query_arg(array(
					'message' => 'success'
				), menu_page_url( 'cfm_menu_options', false ) );
				
				wp_redirect( $page, false );
				exit();
			}
		}
	}
}

/**
 * Register menu location
 */
add_action('init', 'cfm_floating_menu');
// action callback
function cfm_floating_menu(){	
	register_nav_menu( CFM_LOCATION, __( 'CodeFlavors Floating Menu', 'codeflavors-floating-menu' ) );
}

/**
 * Display menu on front-end
 */
add_action('wp_footer', 'cfm_show_menu');
// action callback
function cfm_show_menu(){
	
	if( !cfm_has_menu() ){
		return;
	}
	// send only the flag that the menu is codeflavors's
	$args = array('is_cfm_menu' => true);
	// add a filter on args to put back the right args
	add_filter('wp_nav_menu_args', 'cfm_nav_menu_args', 5000, 1);
	// show the menu	
	wp_nav_menu($args);
	// plugin options
	$options = cfm_get_options();
	$opt = array(
		'is_mobile' 	=> cfm_is_mobile(),
		'top_distance' 	=> $options['top_distance'],
		'animate' 		=> 'animated' == $options['animation'] ? 1 : 0,
		'position' 		=> $options['position']
	);
	
	// plugin JavaScript params
	$params = "\n".'<script language="javascript" type="text/javascript">'."\n";
	$params.= "\tvar CFM_MENU_PARAMS='".json_encode($opt)."';\n";
	$params.= '</script>'."\n";
	echo $params;
}

/**
 * Put back the right args on the menu to ensure other plugins or themes don't add 
 * classes or ids
 * @param array $args
 */
function cfm_nav_menu_args($args){
	
	if( !isset($args['is_cfm_menu']) ){
		return $args;
	}
	
	$container_class = 'cfn_menu_floating_menu_container';
	$options = cfm_get_options();
	if( 'animated' == $options['animation'] ){
		$container_class.= ' animated';
	}
	if( 'right' == $options['position'] ){
		$container_class.= ' right';
	}	
	if( 'none' == $options['animation'] ){
	    $container_class.= ' static-menu';
	}
	
	// get the menu
    $args = array(
		'menu' 				=> '',
	    'container' 		=> 'div',
		'container_class' 	=> $container_class,
		'container_id' 		=> 'cfn_floating_menu',
    	'menu_class' 		=> 'menu', 
    	'menu_id' 			=> '',
    	'echo' 				=> true,
    	'fallback_cb' 		=> 'wp_page_menu', 
    	'before' 			=> '', 
    	'after' 			=> '', 
    	'link_before' 		=> '', 
    	'link_after' 		=> '', 
    	'items_wrap' 		=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'depth' 			=> 0, 
		'walker' 			=> '',    
    	'theme_location' 	=> CFM_LOCATION		
	);	
	return $args;	
}

/**
 * Filter on navigation menu to put the menu title on CFM menu
 * @param array $sorted_menu_items - menu items
 * @param array $args - menu arguments
 */
add_filter('wp_nav_menu_objects', 'cfm_nav_menu_filter', 10, 2);
// filter callback
function cfm_nav_menu_filter( $sorted_menu_items, $args ){
	if( CFM_LOCATION != $args->theme_location ){
		return $sorted_menu_items;
	}
	// get options
	$options = cfm_get_options();
	// if menu title isn't set, return only the pages list
	if( !isset($options['menu_title']) || empty($options['menu_title']) || $options['hide_menu_title'] ){
		return $sorted_menu_items;
	}
	
	// add menu title to menu
	$item = new stdClass();
	$item->title = $options['menu_title'];
	$item->guid = '#';
	$item->url = '#';
	$item->menu_item_parent = false;
	$item->ID = -1;
	$item->db_id = -1;
	$item->is_menu_title = true;
	$item->classes = array('cfm_menu_title_li closed');
	
	add_filter('nav_menu_item_id', 'cfm_menu_title_filter', 10, 2);
	
	// put menu title in menu elements	
	array_unshift($sorted_menu_items, $item);
	return $sorted_menu_items;
}

/**
 * Filter callback to put a unique ID on CFM menu title list element.
 * Gets called only if menu title is on and only for menu title li element.
 */
function cfm_menu_title_filter( $id, $item ){
	if( !isset( $item->is_menu_title ) ){
		return $id;
	}	
	return 'cfm_menu_title_li';
}

/**
 * Add styles top front-end
 */
add_action('wp_print_styles', 'cfm_frontend_styles');
// action callback
function cfm_frontend_styles(){
	if( is_admin() || !cfm_has_menu() ){
		return;
	}	
	wp_enqueue_style(
		'cfm_frontend_menu', 
		plugins_url('css/cfm_menu.css', __FILE__)
	);
} 

/**
 * Add scripts to front-end
 */
add_action('wp_print_scripts', 'cfm_frontend_scripts');
// action callback
function cfm_frontend_scripts(){
	if( is_admin() || !cfm_has_menu() ){
		return;
	}
	wp_enqueue_script(
		'cfm-frontend-menu-script', 
		plugins_url('js/cfm_menu.js', __FILE__), 
		array('jquery')
	);
}

/**************************************************
 * Helper functions
 **************************************************/

/**
 * Verifies if a menu is assigned to plugin menu position. Useful to check if
 * styles and scripts should load into pages.
 */
function cfm_has_menu(){
    /**
     * A simple filter to prevent the menu from being displayed
     *
     * @var boolean
     */
    $show = apply_filters( 'cfm_show_menu', true );
    if( !$show ){
        return false;
    }
    
	// check if it should display on mobile devices
	if( cfm_prevent_on_mobile() ){
		return false;
	}	
	// check menu locations
	if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ CFM_LOCATION ] ) ) {
		// get menu object
    	$menu = wp_get_nav_menu_object( $locations[ CFM_LOCATION ] );
    	if( $menu ){
    		return true;
    	}
	}
	return false;
}

/**
 * Default options
 */
function cfm_default_options(){
	// menu options defaults
	$defaults = array(
		'animation' 		=> 'fixed',
		'position' 			=> 'left',
		'top_distance' 		=> '50',
		'menu_title' 		=> '',
		'hide_menu_title' 	=> 0,
		'hide_on_mobile'	=> 0
	);
	return $defaults;
}

/**
 * Database plugin options
 */
function cfm_get_options(){
	// get default values for options
	$defaults = cfm_default_options();
	// get saved options
	$saved_options = get_option('cfm_floating_menu', $defaults);
	// check if any defaults are missing (ie. in case of update and extending options, new options would be missing)
	foreach( $defaults as $option => $value ){
		if( !isset( $saved_options[$option] ) ){
			$saved_options[$option] = $value;
		}
	}	
	return $saved_options;
}

/**
 * Check if device is mobile and if option 
 * is set not to display on such devices
 */
function cfm_prevent_on_mobile(){
	// check mobile devices
	$options = cfm_get_options();
	if( $options['hide_on_mobile'] && cfm_is_mobile() ){
		return true;
	}
	return false;
}

/**
 * Wrapper function for wp_is_mobile. In older WP versions, the function is missing,
 * use it on versions that have it or add its functionality if not existing.
 */
function cfm_is_mobile(){
	if( function_exists('wp_is_mobile') ){
		return wp_is_mobile();
	}
	
	static $is_mobile;
	
	if ( isset($is_mobile) )
		return $is_mobile;

	if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
		$is_mobile = false;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false ) {
			$is_mobile = true;
	} else {
		$is_mobile = false;
	}

	return $is_mobile;
}