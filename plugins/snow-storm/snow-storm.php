<?php
	
if (!defined('ABSPATH')) exit; // Exit if accessed directly	

/*
Plugin Name: Snow Storm
Description: Display falling snow flakes on the front of your WordPress website for a festive presentation.
Plugin URI: https://tribulant.com
Author: Tribulant Software
Author URI: https://tribulant.com
Version: 1.4.5
*/

if (!defined('DS')) { define('DS', DIRECTORY_SEPARATOR); }

function snow_storm_activate() {
	add_option('snowstorm_flakesMax', "128");
	add_option('snowstorm_flakesMaxActive', "64");
	add_option('snowstorm_animationInterval', "35");
	add_option('snowstorm_excludeMobile', "Y");
	add_option('snowstorm_followMouse', "N");
	add_option('snowstorm_snowColor', "#FFFFFF");
	add_option('snowstorm_snowCharacter', "&bull;");
	add_option('snowstorm_snowStick', "Y");
	add_option('snowstorm_useMeltEffect', "Y");
	add_option('snowstorm_useTwinkleEffect', "N");
	
	// Scheduled tasks
	$ratereview_scheduled = get_option('snowstorm_ratereview_scheduled');
	if (empty($ratereview_scheduled)) {
		wp_schedule_single_event(strtotime("+7 day"), 'snowstorm_ratereviewhook', array(7));
		wp_schedule_single_event(strtotime("+30 day"), 'snowstorm_ratereviewhook', array(30));
		wp_schedule_single_event(strtotime("+60 day"), 'snowstorm_ratereviewhook', array(60));
		update_option('snowstorm_ratereview_scheduled', true);
	}
}

function snowstorm_ratereview_hook($days = 30) {			
	update_option('snowstorm_showmessage_ratereview', $days);
	delete_option('snowstorm_hidemessage_ratereview');
	delete_option('snowstorm_dismissed-ratereview');

	return true;
}

function snow_storm_textdomain() {
	load_plugin_textdomain('snow-storm', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

function snow_storm_links($links = array()) {
	$settings_link = '<a href="' . admin_url('options-general.php?page=snow-storm') . '">' . __('Settings', "snow-storm") . '</a>'; 
	array_unshift($links, $settings_link); 
	return $links;
}

function snow_storm() {	
	add_action('wp_ajax_snowstorm_searchpp', 'snow_storm_searchpp');
}

function snow_storm_searchpp() {
	define('DOING_AJAX', true);
	define('SHORTINIT', true);
	
	$data = array();
	
	$query_args = array('s' => $_REQUEST['q']);
	$query = new WP_Query($query_args);
	
	$data['total_count'] = count($query -> posts);
	
	if (!empty($query -> posts)) {
		foreach ($query -> posts as $post) {
			$data['items'][] = array('id' => $post -> ID, 'text' => $post -> post_title);
		}
	}
	
	echo json_encode($data);
	
	exit();
	die();
}

function snow_storm_head() {
	include dirname(__FILE__) . DS . 'views' . DS . 'default' . DS . 'head.php';
}

function snow_storm_menu() {
	$page = add_options_page(__('Snow Storm', "snow-storm"), __('Snow Storm', "snow-storm"), 'manage_options', 'snow-storm', 'snow_storm_admin');
	add_action('admin_head-' . $page, 'snow_storm_admin_head');
}

function snow_storm_admin_head() {
	add_meta_box('submitdiv', __('Settings', 'snow-storm'), 'snow_storm_metabox_submit', 'settings_page_snow-storm', 'side', 'core');
	add_meta_box('plugins', __('Recommended Plugin', 'snow-storm'), 'snow_storm_metabox_plugins', 'settings_page_snow-storm', 'side', 'core');
	add_meta_box('settings', __('Settings', 'snow-storm'), 'snow_storm_metabox_settings', 'settings_page_snow-storm', 'normal', 'core');
}

function snow_storm_metabox_submit() {
	include dirname(__FILE__) . DS . 'views' . DS . 'admin' . DS . 'metaboxes' . DS . 'submit.php';
}

function snow_storm_metabox_plugins() {
	include dirname(__FILE__) . DS . 'views' . DS . 'admin' . DS . 'metaboxes' . DS . 'plugins.php';
}

function snow_storm_metabox_settings() {
	include dirname(__FILE__) . DS . 'views' . DS . 'admin' . DS . 'metaboxes' . DS . 'settings.php';
}

function snow_storm_enqueue_scripts() {		
	wp_enqueue_script('jquery');
	
	if (is_admin()) {				
		wp_enqueue_script('select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js', array('jquery'), false, true);
		wp_enqueue_style('select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css', false, '4.0.1', "all");
		
		wp_enqueue_script('common', false, false, false, true);
		wp_enqueue_script('wp-lists', false, false, false, true);
		wp_enqueue_script('postbox', false, false, false, true);
		wp_enqueue_script('snow-storm-postboxes', plugin_dir_url(__FILE__) . 'js/postboxes.js', array('jquery'), false, true);
		wp_enqueue_script('plugin-install');
		wp_enqueue_script('updates');
		add_thickbox();
		
		wp_enqueue_script('snow-storm', plugins_url('js/snow-storm.js', SNOW_STORM_PLUGIN), array('jquery'), false, true);
				
		if (!empty($_GET['page']) && $_GET['page'] == "snow-storm") {			
			wp_enqueue_style('snow-storm', plugin_dir_url(__FILE__) . 'css/snow-storm.css', false, "1.0", "all");
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');
		}
	} else {		
		global $post;
		$pp = get_option('snowstorm_pp');
		
		if (empty($pp) || (!empty($pp) && in_array($post -> ID, $pp))) {
			wp_enqueue_script('snow-storm', plugin_dir_url(__FILE__) . 'snow-storm.js', false, '1.4.5');
		}
	}
}

function snowstorm_admin_notices() {	
	// Rate & Review
	$showmessage_ratereview = get_option('snowstorm_showmessage_ratereview');
	if (!empty($showmessage_ratereview)) {
		$rate_url = "https://wordpress.org/support/plugin/snow-storm/reviews/?rate=5#new-post";
		$message = sprintf(__('You have been using %s for some time. Please consider to %s on %s. We appreciate it very much!', 'snow-storm'), '<a href="https://wordpress.org/support/plugin/snow-storm/" target="_blank">' . __('Snow Storm', 'snow-storm') . '</a>', '<a href="' . $rate_url . '" target="_blank" class="button"><i class="fa fa-star"></i> ' . __('leave your rating', 'snow-storm') . '</a>', '<a href="https://wordpress.org/support/plugin/snow-storm/reviews/" target="_blank">WordPress.org</a>');
		snowstorm_render_message($message, 'success', true, 'ratereview');
	}
}

function snowstorm_ajax_dismissed_notice() {
	define('DOING_AJAX', true);
	define('SHORTINIT', true);
	
	// Pick up the notice "slug" - passed via jQuery (the "data-notice" attribute on the notice)
    $slug = esc_html($_REQUEST['slug']);
    // Store it in the options table
    update_option('snowstorm_dismissed-' . $slug, true);
    
    exit();
    die();
}

function snowstorm_render_message($message = null, $type = 'success', $dismissible = true, $slug = null) {			
	if (!empty($dismissible) && !empty($slug)) {
		$dismissed = get_option('snowstorm_dismissed-' . $slug);
		if (!empty($dismissed)) {
			return;
		}
	}
	
	if (!empty($message)) {
		?>
		
		<div id="<?php echo $type; ?>" class="notice notice-<?php echo $type; ?> notice-snow-storm <?php echo (!empty($dismissible)) ? 'is-dismissible' : ''; ?>" data-notice="<?php echo esc_attr($slug); ?>">
	        <p>
		        <?php
			        
			        
			    switch ($type) {
				    case 'error'			:
				    	echo '<i class="fa fa-times fa-fw"></i>';
				    	break;
				    case 'warning'			:
				    	echo '<i class="fa fa-exclamation-triangle fa-fw"></i>';
				    	break;
				    case 'success'			:
				    default 				:
				    	echo '<i class="fa fa-check fa-fw"></i>';
				    	break;
			    }    
		        
		        ?>
		        <?php echo $message; ?>
		    </p>
	    </div>
		
		<?php
	}
}

function snow_storm_admin() {
	if (!empty($_POST)) {
		delete_option('snowstorm_pp');
		
		foreach ($_POST as $pkey => $pval) {			
			update_option('snowstorm_' . $pkey, $pval);
		}
		
		$message = __('Settings have been saved.', "snow-storm");
		include dirname(__FILE__) . DS . 'views' . DS . 'admin' . DS . 'message.php';
	}
	
	include dirname(__FILE__) . DS . 'views' . DS . 'admin' . DS . 'index.php';
}

$plugin = plugin_basename(__FILE__); 
define('SNOW_STORM_PLUGIN', $plugin);

add_action('plugins_loaded', 'snow_storm_textdomain', 10, 1);
add_filter('plugin_action_links_' . $plugin, 'snow_storm_links', 10, 1);
add_action('init', 'snow_storm', 10);
add_action('wp_head', 'snow_storm_head', 11);
add_action('admin_menu', 'snow_storm_menu', 10);
add_action('wp_enqueue_scripts', 'snow_storm_enqueue_scripts', 10, 1);
add_action('admin_print_scripts', 'snow_storm_enqueue_scripts', 10, 1);
add_action('admin_notices', 'snowstorm_admin_notices', 10, 1);
add_action('snowstorm_ratereviewhook', 'snowstorm_ratereview_hook', 10, 1);
add_action('wp_ajax_snowstorm_dismissed_notice', 'snowstorm_ajax_dismissed_notice');

register_activation_hook(__FILE__, 'snow_storm_activate');

?>