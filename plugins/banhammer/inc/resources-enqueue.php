<?php // Enqueue Resources

if (!defined('ABSPATH')) exit;

function banhammer_admin_enqueue_scripts() {
	
	$screen_id = banhammer_get_current_screen_id();
	
	if ($screen_id === 'toplevel_page_banhammer' || $screen_id === 'banhammer_page_banhammer-armory' || $screen_id === 'banhammer_page_banhammer-tower') {
		
		wp_enqueue_style('wp-jquery-ui-dialog');
		
		wp_enqueue_style('banhammer-fonts', 'https://fonts.googleapis.com/css?family=Unlock|Fira+Mono', array(), null);
		
		wp_enqueue_style('banhammer', BANHAMMER_URL .'css/banhammer.css', array('banhammer-fonts'), BANHAMMER_VERSION);
		
	}
	
	if ($screen_id === 'toplevel_page_banhammer') {
		
		wp_enqueue_style('banhammer-settings', BANHAMMER_URL .'css/settings.css', array('banhammer', 'banhammer-fonts'), BANHAMMER_VERSION);
		
		wp_enqueue_script('banhammer-settings', BANHAMMER_URL .'js/settings.js', array('jquery', 'jquery-ui-core', 'jquery-ui-dialog'), BANHAMMER_VERSION);
		
	} elseif ($screen_id === 'banhammer_page_banhammer-armory') {
		
		wp_enqueue_style('banhammer-jbox', BANHAMMER_URL .'css/jbox.css', array(), 'jBox-v0.4.9');
		
		wp_enqueue_style('banhammer-armory', BANHAMMER_URL .'css/armory.css', array('banhammer', 'banhammer-fonts', 'banhammer-jbox'), BANHAMMER_VERSION);
		
		wp_enqueue_script('banhammer-jbox', BANHAMMER_URL .'js/jbox.min.js', array(), 'jBox-v0.4.9');
		
		wp_enqueue_script('banhammer-armory', BANHAMMER_URL .'js/armory.js', array('jquery', 'jquery-ui-core', 'jquery-ui-dialog', 'banhammer-jbox'), BANHAMMER_VERSION);
		
		banhammer_localize_script();
		
	} elseif ($screen_id === 'banhammer_page_banhammer-tower') {
		
		wp_enqueue_style('banhammer-jbox', BANHAMMER_URL .'css/jbox.css', array(), 'jBox-v0.4.9');
		
		wp_enqueue_style('banhammer-tower', BANHAMMER_URL .'css/tower.css', array('banhammer', 'banhammer-fonts', 'banhammer-jbox'), BANHAMMER_VERSION);
		
		wp_enqueue_script('banhammer-jbox', BANHAMMER_URL .'js/jbox.min.js', array(), 'jBox-v0.4.9');
		
		wp_enqueue_script('banhammer-tower', BANHAMMER_URL .'js/tower.js', array('jquery', 'jquery-ui-core', 'jquery-ui-dialog', 'banhammer-jbox'), BANHAMMER_VERSION);
		
		banhammer_localize_script_tower();
		
	}
	
	if (is_admin()) {
		
		wp_enqueue_style('dashicons-banhammer', BANHAMMER_URL .'css/dashicons.css', array(), BANHAMMER_VERSION);
		
	}
	
}

function banhammer_localize_script() {
	
	global $BanhammerWP, $wpdb;
	
	$table = $wpdb->prefix .'banhammer';
	
	$count = $wpdb->get_var("SELECT COUNT(*) FROM ". $table);
	
	$default = $BanhammerWP->armory();
	
	$armory = get_option('banhammer_armory', $default);
	
	$fx = (isset($armory['fx']) && $armory['fx']) ? 1 : 0;
	
	$toggle = isset($armory['view']) ? $armory['view'] : 2;
	
	$limit = isset($armory['rows']) ? $armory['rows'] : 3;
	
	if ($limit <= 0) $limit = 1;
	
	$pages = ceil($count / $limit);
	
	$nonce = wp_create_nonce('banhammer');
	
	$script = array('vars' => array(
						'nonce'  => $nonce,
						'items'  => (array) [],
						'type'   => 'init',
						'bulk'   => '',
						'sort'   => 'id',
						'order'  => 'desc',
						'search' => '',
						'filter' => 'all',
						'status' => 'all',
						'jump'   => (int) 1,
						'count'  => (int) $count,
						'limit'  => (int) $limit,
						'offset' => (int) 0,
						'pages'  => (int) $pages,
						'toggle' => (int) $toggle,
						'fx'     => (int) $fx,
						'xhr'    => null,
						'dots'   => esc_attr__('Loading...', 'banhammer')
					)
				);
	
	wp_localize_script('banhammer-armory', 'banhammer', $script);
	
}

function banhammer_localize_script_tower() {
	
	global $BanhammerWP;
	
	$default = $BanhammerWP->armory();
	
	$armory = get_option('banhammer_armory', $default);
	
	$fx = (isset($armory['fx']) && $armory['fx']) ? 1 : 0;
	
	$nonce = wp_create_nonce('banhammer');
	
	$script = array('vars' => array(
						'nonce'  => $nonce,
						'sort'   => '',
						'type'   => '',
						'bulk'   => '',
						'items'  => (array) [],
						'fx'     => (int) $fx,
						'demo'   => (int) 0,
						'xhr'    => null
						
					)
				);
	
	wp_localize_script('banhammer-tower', 'banhammer', $script);
	
}

function banhammer_admin_print_scripts() { 
	
	$screen_id = banhammer_get_current_screen_id();
	
	if ($screen_id === 'toplevel_page_banhammer' || $screen_id === 'banhammer_page_banhammer-armory' || $screen_id === 'banhammer_page_banhammer-tower') :
	
	?>
	
	<script type="text/javascript">
		var 
		banhammer_alert_options_title   = '<?php _e('Confirm Reset',            'banhammer'); ?>',
		banhammer_alert_options_message = '<?php _e('Restore default options?', 'banhammer'); ?>',
		banhammer_alert_options_true    = '<?php _e('Yes, make it so.',         'banhammer'); ?>',
		banhammer_alert_options_false   = '<?php _e('No, abort mission.',       'banhammer'); ?>';
		var 
		banhammer_delete_items_title   = '<?php _e('Confirm Delete',           'banhammer'); ?>',
		banhammer_delete_items_message = '<?php _e('Delete all logged items?', 'banhammer'); ?>',
		banhammer_delete_items_true    = '<?php _e('Yes, make it so.',         'banhammer'); ?>',
		banhammer_delete_items_false   = '<?php _e('No, abort mission.',       'banhammer'); ?>';
		var 
		banhammer_delete_item_title   = '<?php _e('Confirm Delete',     'banhammer'); ?>',
		banhammer_delete_item_message = '<?php _e('Are you sure?',      'banhammer'); ?>',
		banhammer_delete_item_true    = '<?php _e('Yes, make it so.',   'banhammer'); ?>',
		banhammer_delete_item_false   = '<?php _e('No, abort mission.', 'banhammer'); ?>';
		var 
		banhammer_number_rows_title   = '<?php _e('Confirm Increase',  'banhammer'); ?>',
		banhammer_number_rows_message = '<?php _e('Due to limitations with free GeoIP-lookup services, rows are limited to 10 in the free version. Visit the Banhammer Help tab for details.', 'banhammer'); ?>',
		banhammer_number_rows_true    = '<?php _e('Use maximum value', 'banhammer'); ?>',
		banhammer_number_rows_false   = '<?php _e('Abort mission',     'banhammer'); ?>';
	</script>
	
	<?php
	
	endif;
	 
}

function banhammer_get_current_screen_id() {
	
	if (!function_exists('get_current_screen')) require_once ABSPATH .'/wp-admin/includes/screen.php';
	
	$screen = get_current_screen();
	
	if ($screen && property_exists($screen, 'id')) return $screen->id;
	
	return false;
	
}