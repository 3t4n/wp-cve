<?php // BBQ - Settings

if (!defined('ABSPATH')) exit;

function bbq_languages() {
	
	load_plugin_textdomain('block-bad-queries', false, dirname(BBQ_BASE_FILE) .'/languages/');
	
}
add_action('init', 'bbq_languages');

function bbq_options() {
	
	$bbq_options = array(
		
		'version' => BBQ_VERSION,
		
	);
	
	return $bbq_options;
}

function bbq_check_plugin() {
	
	if (class_exists('BBQ_Pro')) {
		
		if (is_plugin_active('block-bad-queries/block-bad-queries.php')) {
			
			$msg  = '<strong>'. esc_html__('Warning:', 'block-bad-queries') .'</strong> ';
			$msg .= esc_html__('Free and Pro versions of BBQ cannot be activated at the same time. ', 'block-bad-queries');
			$msg .= esc_html__('Please return to the ', 'block-bad-queries');
			$msg .= '<a href="'. admin_url('plugins.php') .'">'. esc_html__('WordPress Admin Area', 'block-bad-queries') .'</a> ';
			$msg .= esc_html__('and try again.', 'block-bad-queries');
			
			deactivate_plugins(BBQ_BASE_FILE);
			
			wp_die($msg);
			
		}
		
	}
	
}
add_action('admin_init', 'bbq_check_plugin');

function bbq_admin_footer_text($text) {
	
	$screen_id = bbq_get_current_screen_id();
	
	$ids = array('settings_page_bbq_settings');
	
	if ($screen_id && apply_filters('bbq_admin_footer_text', in_array($screen_id, $ids))) {
		
		$text = __('Like BBQ? Give it a', 'block-bad-queries');
		
		$text .= ' <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/block-bad-queries/reviews/?rate=5#new-post">';
		
		$text .= __('★★★★★ rating&nbsp;&raquo;', 'block-bad-queries') .'</a>';
		
	}
	
	return $text;
	
}
add_filter('admin_footer_text', 'bbq_admin_footer_text', 10, 1);

function bbq_get_current_screen_id() {
	
	if (!function_exists('get_current_screen')) require_once ABSPATH .'/wp-admin/includes/screen.php';
	
	$screen = get_current_screen();
	
	if ($screen && property_exists($screen, 'id')) return $screen->id;
	
	return false;
	
}

//

function bbq_callback_count() {
	
	echo number_format(get_option('bbq-block-count', 0)) .' '. esc_html__('blocked requests', 'block-bad-queries');
	
}

function bbq_maybe_display_count() {
	
	if (is_plugin_active(apply_filters('bbq_count_plugin_path', 'bbq-block-count.php'))) {
		
		add_settings_field('block-count', esc_html__('Block Count', 'block-bad-queries'), 'bbq_callback_count', 'bbq_options_free', 'general', array('id' => 'block-count', 'label' => ''));
		
	}
	
}

//

function bbq_register_settings() {
	
	// register_setting( $option_group, $option_name, $sanitize_callback );
	register_setting('bbq_options_free', 'bbq_options_free', 'bbq_validate_options');
	
	// add_settings_section( $id, $title, $callback, $page ); 
	add_settings_section('general', esc_html__('Plugin Information', 'block-bad-queries'), 'bbq_settings_section_general', 'bbq_options_free');
	
	// add_settings_field( $id, $title, $callback, $page, $section, $args );
	add_settings_field('version',       esc_html__('BBQ Version',    'block-bad-queries'), 'bbq_callback_version', 'bbq_options_free', 'general', array('id' => 'version', 'label' => ''));
	
	add_settings_field('test-firewall', esc_html__('Test Firewall',  'block-bad-queries'), 'bbq_callback_test',    'bbq_options_free', 'general', array('id' => 'test-firewall', 'label' => ''));
	
	bbq_maybe_display_count();
	
	add_settings_field('rate-plugin',  esc_html__('Rate Plugin',  'block-bad-queries'), 'bbq_callback_rate',    'bbq_options_free', 'general', array('id' => 'rate-plugin',  'label' => ''));
	
	add_settings_field('show-support', esc_html__('Show Support', 'block-bad-queries'), 'bbq_callback_support', 'bbq_options_free', 'general', array('id' => 'show-support', 'label' => ''));
	
	//
	
	add_settings_section('addons', esc_html__('BBQ Addons', 'block-bad-queries'), 'bbq_settings_section_addons', 'bbq_options_free');
	
	add_settings_section('upgrade', esc_html__('Upgrade to BBQ Pro', 'block-bad-queries'), 'bbq_settings_section_upgrade', 'bbq_options_free');
	
}
add_action('admin_init', 'bbq_register_settings');

function bbq_validate_options($input) {
	
	if (!isset($input['version'])) $input['version'] = null;
	
	return $input;
	
}

function bbq_settings_section_general() {
	
	echo '<p>'. esc_html__('Thanks for using the free version of ', 'block-bad-queries') .' ';
	echo '<a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/block-bad-queries/">'. esc_html__('BBQ Firewall', 'block-bad-queries') .'</a>.</p>';
	echo '<p>'. esc_html__('The free version is completely plug-&amp;-play, protecting your site automatically with no settings required.', 'block-bad-queries') .'</p>';
	
}

function bbq_settings_section_addons() {
	
	echo '<p>'. esc_html__('Want to customize BBQ Firewall? Here are some free open-source addons:', 'block-bad-queries') .'</p>';
	echo '<ul class="bbq-addons">';
	echo '<li><a target="_blank" rel="noopener noreferrer" href="https://perishablepress.com/customize-bbq-firewall/">'. esc_html__('BBQ Firewall – Customize Features', 'block-bad-queries') .'</a></li>';
	echo '<li><a target="_blank" rel="noopener noreferrer" href="https://perishablepress.com/bbq-whitelist-blacklist/">'. esc_html__('BBQ Firewall – Customize Rules', 'block-bad-queries') .'</a></li>';
	echo '<li><a target="_blank" rel="noopener noreferrer" href="https://perishablepress.com/bbq-firewall-count-blocked-requests/">'. esc_html__('BBQ Firewall – Count Blocked Requests', 'block-bad-queries') .'</a></li>';
	echo '</ul>';
	
}

function bbq_settings_section_upgrade() {
	
	$url  = esc_url('https://plugin-planet.com/bbq-pro/');
	$text = esc_html__('Upgrade your site security with advanced protection and complete control.', 'block-bad-queries');
	$alt  = esc_attr__('BBQ Pro: Advanced WordPress Firewall', 'block-bad-queries');
	$src  = esc_url(BBQ_URL .'assets/bbq-pro-960x250.jpg');
	
	$src_1 = esc_url(BBQ_URL .'assets/bbq-pro-settings.png');
	$src_2 = esc_url(BBQ_URL .'assets/bbq-pro-statistics.png');
	$src_3 = esc_url(BBQ_URL .'assets/bbq-pro-tools.png');
	
	$title = esc_attr__('Click to view full size screenshot (opens new tab)', 'block-bad-queries');
	
	$alt_1 = esc_attr__('Screenshot showing BBQ Pro settings',   'block-bad-queries');
	$alt_2 = esc_attr__('Screenshot showing BBQ Pro statistics', 'block-bad-queries');
	$alt_3 = esc_attr__('Screenshot showing BBQ Pro tools',      'block-bad-queries');
	
	$upgrade  = '<p>'. $text .' <a target="_blank" rel="noopener noreferrer" href="'. $url .'">'. esc_html__('Get BBQ Pro&nbsp;&raquo;', 'block-bad-queries') .'</a></p>';
	
	$upgrade .= '<p class="bbq-pro">';
	$upgrade .= '<a target="_blank" rel="noopener noreferrer" href="'. $url .'" title="'. $text .'"><img src="'. $src .'" width="480" alt="'. $alt .'"></a>';
	$upgrade .= '</p>';
	
	$upgrade .= '<p class="bbq-pro bbq-pro-screenshots">';
	$upgrade .= '<a target="_blank" rel="noopener noreferrer" href="'. $src_1 .'" title="'. $title .'"><img src="'. $src_1 .'" width="130" height="130" alt="'. $alt_1 .'" title="'. $alt_1 .'"></a>';
	$upgrade .= '<a target="_blank" rel="noopener noreferrer" href="'. $src_2 .'" title="'. $title .'"><img src="'. $src_2 .'" width="130" height="130" alt="'. $alt_2 .'" title="'. $alt_2 .'"></a>';
	$upgrade .= '<a target="_blank" rel="noopener noreferrer" href="'. $src_3 .'" title="'. $title .'"><img src="'. $src_3 .'" width="130" height="130" alt="'. $alt_3 .'" title="'. $alt_3 .'"></a>';
	$upgrade .= '</p>';
	
	echo $upgrade;
	
}

function bbq_callback_version($args) {
	
	$bbq_options = get_option('bbq_options_free', bbq_options());
	
	$id = isset($args['id']) ? $args['id'] : '';
	$label = isset($args['label']) ? $args['label'] : '';
	$version = isset($bbq_options[$id]) ? esc_html($bbq_options[$id]) : BBQ_VERSION;
	
	echo '<span class="bbq-version">'. $version .'</span>';
	
}

function bbq_callback_test($args) {
	
	$href  = add_query_arg('bbq-test', 'eval(', get_home_url());
	$title = esc_attr__('Click to test if BBQ is working (opens new tab)', 'block-bad-queries');
	$text  = esc_html__('Test BBQ Firewall &raquo;', 'block-bad-queries');
	
	echo '<a target="_blank" rel="noopener noreferrer" class="bbq-test-firewall" href="'. $href .'" title="'. $title .'">'. $text .'</a>';
	
}

function bbq_callback_rate($args) {
	
	$href  = 'https://wordpress.org/support/plugin/block-bad-queries/reviews/?rate=5#new-post';
	$title = esc_attr__('Let others know about BBQ Firewall! A huge THANK YOU for your support!', 'block-bad-queries');
	$text  = esc_html__('Show support with a 5-star rating&nbsp;&raquo;', 'block-bad-queries');
	
	echo '<a target="_blank" rel="noopener noreferrer" class="bbq-rate-plugin" href="'. $href .'" title="'. $title .'">'. $text .'</a>';
	
}

function bbq_callback_support($args) {
	
	$href  = 'https://monzillamedia.com/donate.html';
	$title = esc_attr__('Donate via PayPal, credit card, or cryptocurrency', 'block-bad-queries');
	$text  = esc_html__('Show support with a small donation&nbsp;&raquo;', 'block-bad-queries');
	
	echo '<a target="_blank" rel="noopener noreferrer" class="bbq-show-support" href="'. $href .'" title="'. $title .'">'. $text .'</a>';
	
}

function bbq_action_links($links, $file) {
	
	if ($file == BBQ_BASE_FILE && current_user_can('manage_options')) {
		
		$settings_url   = admin_url('options-general.php?page=bbq_settings');
		$settings_title = esc_attr__('Visit the BBQ plugin page', 'block-bad-queries');
		$settings_text  = esc_html__('Settings', 'block-bad-queries');
		
		$settings_link  = '<a href="'. $settings_url .'" title="'. $settings_title .'">'. $settings_text .'</a>';
		
		array_unshift($links, $settings_link);
		
	}
	
	if ($file == BBQ_BASE_FILE) {
		
		$pro_url   = esc_url('https://plugin-planet.com/bbq-pro/');
		$pro_title = esc_attr__('Get BBQ Pro for advanced protection', 'block-bad-queries');
		$pro_text  = esc_html__('Go&nbsp;Pro', 'block-bad-queries');
		$pro_style = esc_attr('font-weight:bold;');
		
		$pro_link  = '<a target="_blank" rel="noopener noreferrer" href="'. $pro_url .'" title="'. $pro_title .'" style="'. $pro_style .'">'. $pro_text .'</a>';
		
		array_unshift($links, $pro_link);
		
	}
	
	return $links;
	
}
add_filter('plugin_action_links', 'bbq_action_links', 10, 2);

function bbq_meta_links($links, $file) {
	
	if ($file == BBQ_BASE_FILE) {
		
		$home_href  = 'https://perishablepress.com/block-bad-queries/';
		$home_title = esc_attr__('Plugin Homepage', 'block-bad-queries');
		$home_text  = esc_html__('Homepage', 'block-bad-queries');
		
		$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $home_href .'" title="'. $home_title .'">'. $home_text .'</a>';
		
		$rate_url   = esc_url('https://wordpress.org/support/plugin/block-bad-queries/reviews/?rate=5#new-post');
		$rate_title = esc_attr__('Click here to rate and review this plugin at WordPress.org', 'block-bad-queries');
		$rate_text  = esc_html__('Rate this plugin&nbsp;&raquo;', 'block-bad-queries');
		
		$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $rate_url .'" title="'. $rate_title .'">'. $rate_text .'</a>';
		
	}
	
	return $links;
	
}
add_filter('plugin_row_meta', 'bbq_meta_links', 10, 2);

function bbq_menu_page() {
	
	$title = esc_html__('BBQ Firewall', 'block-bad-queries');
	
	// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
	add_options_page($title, $title, 'manage_options', 'bbq_settings', 'bbq_display_settings');
	
}
add_action('admin_menu', 'bbq_menu_page');

function bbq_display_settings() { ?>
	
	<div class="wrap">
		<h1><?php esc_html_e('BBQ Firewall', 'block-bad-queries'); ?></h1>
		<form method="post" action="options.php">
			<?php 
				settings_fields('bbq_options_free');
				do_settings_sections('bbq_options_free');
				// submit_button();
			?>
		</form>
	</div>
	
<?php }

function bbq_enqueue_resources_admin() {
	
	$screen_id = bbq_get_current_screen_id();
	
	if ($screen_id === 'settings_page_bbq_settings') {
		
		// wp_enqueue_style ( $handle, $src, $deps, $ver, $media )
		wp_enqueue_style('bbq_admin', BBQ_URL .'assets/admin-styles.css', array('dashicons', 'wp-jquery-ui-dialog'), BBQ_VERSION);
		
		// wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer )
		wp_enqueue_script('bbq_admin', BBQ_URL .'assets/admin-scripts.js', array('jquery', 'jquery-ui-core', 'jquery-ui-dialog', 'jquery-ui-sortable'), BBQ_VERSION);
		
	}
	
}
add_action('admin_enqueue_scripts', 'bbq_enqueue_resources_admin');

function bbq_print_js_vars_admin() { 
	
	$screen_id = bbq_get_current_screen_id();
	
	if ($screen_id === 'settings_page_bbq_settings') : ?>
		
		<script type="text/javascript">
			var 
			alert_test_firewall_title   = '<?php _e('Confirm Test', 'block-bad-queries'); ?>',
			alert_test_firewall_message = '<?php _e('This test opens a new tab. If the response is 403 Forbidden (HTTP Error 403), the firewall is working.', 'block-bad-queries'); ?>',
			alert_test_firewall_true    = '<?php _e('Okay run the test.', 'block-bad-queries'); ?>',
			alert_test_firewall_false   = '<?php _e('No, abort mission.', 'block-bad-queries'); ?>';
		</script>
		
	<?php endif;
	
}
add_action('admin_print_scripts', 'bbq_print_js_vars_admin');

//

function bbq_admin_notice() {
	
	if (bbq_get_current_screen_id() === 'settings_page_bbq_settings') {
		
		if (!bbq_check_date_expired() && !bbq_dismiss_notice_check()) {
			
			?>
			
			<div class="notice notice-success">
				<p>
					<strong><?php esc_html_e('Go Pro!', 'block-bad-queries'); ?></strong> 
					<?php esc_html_e('Save 30% on our', 'block-bad-queries'); ?> 
					<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/"><?php esc_html_e('Pro WordPress plugins', 'block-bad-queries'); ?></a> 
					<?php esc_html_e('and', 'block-bad-queries'); ?> 
					<a target="_blank" rel="noopener noreferrer" href="https://books.perishablepress.com/"><?php esc_html_e('books', 'block-bad-queries'); ?></a>. 
					<?php esc_html_e('Apply code', 'block-bad-queries'); ?> <code>PLANET24</code> <?php esc_html_e('at checkout. Sale ends 5/25/24.', 'block-bad-queries'); ?> 
					<?php echo bbq_dismiss_notice_link(); ?>
				</p>
			</div>
			
			<?php
			
		}
		
	}
	
}
add_action('admin_notices', 'bbq_admin_notice');

function bbq_dismiss_notice_activate() {
	
	delete_option('bbq-firewall-dismiss-notice');
	
}
register_activation_hook(BBQ_FILE, 'bbq_dismiss_notice_activate');

function bbq_dismiss_notice_version() {
	
	$version_current = BBQ_VERSION;
	
	$version_previous = get_option('bbq-firewall-dismiss-notice');
	
	$version_previous = ($version_previous) ? $version_previous : $version_current;
	
	if (version_compare($version_current, $version_previous, '>')) {
		
		delete_option('bbq-firewall-dismiss-notice');
		
	}
	
}
add_action('admin_init', 'bbq_dismiss_notice_version');

function bbq_dismiss_notice_check() {
	
	$check = get_option('bbq-firewall-dismiss-notice');
	
	return ($check) ? true : false;
	
}

function bbq_dismiss_notice_save() {
	
	if (isset($_GET['dismiss-notice-verify']) && wp_verify_nonce($_GET['dismiss-notice-verify'], 'bbq_dismiss_notice')) {
		
		if (!current_user_can('manage_options')) exit;
		
		$result = update_option('bbq-firewall-dismiss-notice', BBQ_VERSION, false);
		
		$result = $result ? 'true' : 'false';
		
		$location = admin_url('options-general.php?page=bbq_settings&dismiss-notice='. $result);
		
		wp_redirect($location);
		
		exit;
		
	}
	
}
add_action('admin_init', 'bbq_dismiss_notice_save');

function bbq_dismiss_notice_link() {
	
	$nonce = wp_create_nonce('bbq_dismiss_notice');
	
	$href  = add_query_arg(array('dismiss-notice-verify' => $nonce), admin_url('options-general.php?page=bbq_settings'));
	
	$label = esc_html__('Dismiss', 'block-bad-queries');
	
	echo '<a class="bbq-dismiss-notice" href="'. esc_url($href) .'">'. esc_html($label) .'</a>';
	
}

function bbq_check_date_expired() {
	
	$expires = apply_filters('bbq_check_date_expired', '2024-05-25');
	
	return (new DateTime() > new DateTime($expires)) ? true : false;
	
}