<?php // Register Settings

if (!defined('ABSPATH')) exit;

function banhammer_register_settings() {
	
	// register_setting( $option_group, $option_name, $sanitize_callback );
	register_setting('banhammer_settings', 'banhammer_settings', 'banhammer_validate_options');
	
	// add_settings_section( $id, $title, $callback, $page ); 
	add_settings_section('general', 'Basic Settings', 'banhammer_settings_section_general', 'banhammer_settings');
	
	// add_settings_field( $id, $title, $callback, $page, $section, $args );
	add_settings_field('enable_plugin', 'Enable Plugin', 'banhammer_callback_checkbox', 'banhammer_settings', 'general', array('id' => 'enable_plugin', 'label' => esc_html__('Enable Banhammer',       'banhammer')));
	add_settings_field('ignore_logged', 'Ignore Users',  'banhammer_callback_checkbox', 'banhammer_settings', 'general', array('id' => 'ignore_logged', 'label' => esc_html__('Ignore logged-in users', 'banhammer')));
	add_settings_field('protect_login', 'Login Page',    'banhammer_callback_checkbox', 'banhammer_settings', 'general', array('id' => 'protect_login', 'label' => esc_html__('Protect WP Login Page',  'banhammer')));
	add_settings_field('protect_admin', 'Admin Area',    'banhammer_callback_checkbox', 'banhammer_settings', 'general', array('id' => 'protect_admin', 'label' => esc_html__('Protect WP Admin Area',  'banhammer')));
	// 
	add_settings_section('response', 'Banhammer Response', 'banhammer_settings_section_response', 'banhammer_settings');
	add_settings_field('banned_response', 'Banned Response', 'banhammer_callback_select',   'banhammer_settings', 'response', array('id' => 'banned_response', 'label' => esc_html__('Response for all banned requests',   'banhammer')));
	add_settings_field('custom_message',  'Custom Message',  'banhammer_callback_textarea', 'banhammer_settings', 'response', array('id' => 'custom_message',  'label' => esc_html__('Custom message for banned requests', 'banhammer')));
	add_settings_field('redirect_url',    'Redirect URL',    'banhammer_callback_text',     'banhammer_settings', 'response', array('id' => 'redirect_url',    'label' => esc_html__('Redirect URL for banned requests',   'banhammer')));
	
	//
	add_settings_section('advanced', 'Advanced Settings', 'banhammer_settings_section_advanced', 'banhammer_settings');
	add_settings_field('target_key',     'Target Key',    'banhammer_callback_text',     'banhammer_settings', 'advanced', array('id' => 'target_key',     'label' => esc_html__('Secret key to manually add targets (keep this private!)', 'banhammer')));
	add_settings_field('status_code',    'Status Code',   'banhammer_callback_select',   'banhammer_settings', 'advanced', array('id' => 'status_code',    'label' => esc_html__('Status code for banned responses',                        'banhammer')));
	add_settings_field('reset_interval', 'Reset Armory',  'banhammer_callback_select',   'banhammer_settings', 'advanced', array('id' => 'reset_interval', 'label' => esc_html__('Time interval to auto-clear Armory',                      'banhammer')));
	add_settings_field('reset_options',  'Reset Options', 'banhammer_callback_reset',    'banhammer_settings', 'advanced', array('id' => 'reset_options',  'label' => esc_html__('Restore default plugin options',                          'banhammer')));
	add_settings_field('rate_plugin',    'Rate Plugin',   'banhammer_callback_rate',     'banhammer_settings', 'advanced', array('id' => 'rate_plugin',    'label' => esc_html__('Show support with a 5-star rating &raquo;',               'banhammer')));
	add_settings_field('show_support',   'Show Support',  'banhammer_callback_support',  'banhammer_settings', 'advanced', array('id' => 'show_support',   'label' => esc_html__('Show support with a small donation&nbsp;&raquo;',         'banhammer')));
	
}

function banhammer_validate_options($input) {
	
	$banned_response = banhammer_response_options();
	$allowed_tags    = banhammer_allowed_tags();
	$status_codes    = banhammer_status_codes();
	$reset_interval  = banhammer_reset_interval();
	
	if (!isset($input['enable_plugin'])) $input['enable_plugin'] = null;
	$input['enable_plugin'] = ($input['enable_plugin'] == 1 ? 1 : 0);
	
	if (!isset($input['ignore_logged'])) $input['ignore_logged'] = null;
	$input['ignore_logged'] = ($input['ignore_logged'] == 1 ? 1 : 0);
	
	if (!isset($input['protect_login'])) $input['protect_login'] = null;
	$input['protect_login'] = ($input['protect_login'] == 1 ? 1 : 0);
	
	if (!isset($input['protect_admin'])) $input['protect_admin'] = null;
	$input['protect_admin'] = ($input['protect_admin'] == 1 ? 1 : 0);
	
	if (!isset($input['banned_response'])) $input['banned_response'] = null;
	if (!array_key_exists($input['banned_response'], $banned_response)) $input['banned_response'] = null;
	
	if (isset($input['custom_message'])) $input['custom_message'] = wp_kses(stripslashes_deep($input['custom_message']), $allowed_tags);
	
	if (isset($input['redirect_url'])) $input['redirect_url'] = esc_url($input['redirect_url']);
	
	if (isset($input['target_key'])) $input['target_key'] = esc_attr($input['target_key']);
	
	if (!isset($input['status_code'])) $input['status_code'] = null;
	if (!array_key_exists($input['status_code'], $status_codes)) $input['status_code'] = null;
	
	if (!isset($input['reset_interval'])) $input['reset_interval'] = null;
	if (!array_key_exists($input['reset_interval'], $reset_interval)) $input['reset_interval'] = null;
	
	return $input;
	
}

function banhammer_allowed_tags() {
	
	$allowed_tags = wp_kses_allowed_html('post');
	
	$allowed_tags['style'] = array('media' => true, 'scoped' => true, 'type' => true);
	
	return apply_filters('banhammer_allowed_tags', $allowed_tags);
	
}

function banhammer_settings_section_general() {
	
	echo '<p>'. esc_html__('Choose when and where Banhammer should be enabled.', 'banhammer') .'</p>';
	
}

function banhammer_settings_section_response() {
	
	echo '<p>'. esc_html__('Customize the response given to banned users and bots.', 'banhammer') .'</p>';
	
}

function banhammer_settings_section_advanced() {
	
	echo '<p>'. esc_html__('Advanced settings. Visit the Help tab for more information.', 'banhammer') .'</p>';
	
}

function banhammer_response_options() {
	
	$response = array(
		
		'default' => array(
			'value' => 'default',
			'label' => esc_html__('Default Message', 'banhammer'),
		),
		'custom' => array(
			'value' => 'custom',
			'label' => esc_html__('Custom Message', 'banhammer'),
		),
		'redirect' => array(
			'value' => 'redirect',
			'label' => esc_html__('Redirect', 'banhammer'),
		),
		
	);
	
	return $response;
	
}

function banhammer_reset_interval() {
	
	$interval = array(
		
		'banhammer_one_minute' => array(
			'value' => 'banhammer_one_minute',
			'label' => esc_html__('One Minute', 'banhammer'),
		),
		'banhammer_one_hour' => array(
			'value' => 'banhammer_one_hour',
			'label' => esc_html__('One Hour', 'banhammer'),
		),
		'banhammer_six_hours' => array(
			'value' => 'banhammer_six_hours',
			'label' => esc_html__('Six Hours', 'banhammer'),
		),
		'banhammer_twelve_hours' => array(
			'value' => 'banhammer_twelve_hours',
			'label' => esc_html__('12 Hours', 'banhammer'),
		),
		'banhammer_one_day' => array(
			'value' => 'banhammer_one_day',
			'label' => esc_html__('One Day', 'banhammer'),
		),
		'banhammer_one_week' => array(
			'value' => 'banhammer_one_week',
			'label' => esc_html__('One Week', 'banhammer'),
		),
		'banhammer_one_month' => array(
			'value' => 'banhammer_one_month',
			'label' => esc_html__('One Month', 'banhammer'),
		),
		'banhammer_one_year' => array(
			'value' => 'banhammer_one_year',
			'label' => esc_html__('One Year', 'banhammer'),
		),
		'banhammer_never' => array(
			'value' => 'banhammer_never',
			'label' => esc_html__('Never', 'banhammer'),
		)
		
	);
	
	return apply_filters('banhammer_interval', $interval);
	
}

function banhammer_callback_text($args) {
	
	global $BanhammerWP;
	
	$default = $BanhammerWP->options();
	
	$options = get_option('banhammer_settings', $default);
	
	$id    = isset($args['id'])    ? $args['id']    : '';
	$label = isset($args['label']) ? $args['label'] : '';
	$value = isset($options[$id])  ? $options[$id]  : '';
	
	$name = 'banhammer_settings['. $id .']';
	
	echo '<input id="'. esc_attr($name) .'" name="'. esc_attr($name) .'" type="text" size="40" class="regular-text code" value="'. esc_attr($value) .'">';
	echo '<label for="'. esc_attr($name) .'" class="display-block">'. esc_html($label) .'</label>';
	
}

function banhammer_callback_textarea($args) {
	
	global $BanhammerWP;
	
	$default = $BanhammerWP->options();
	
	$options = get_option('banhammer_settings', $default);
	
	$allowed_tags = banhammer_allowed_tags();
	
	$id    = isset($args['id'])    ? $args['id']    : '';
	$label = isset($args['label']) ? $args['label'] : '';
	$value = isset($options[$id])  ? $options[$id]  : '';
	
	$name = 'banhammer_settings['. $id .']';
	
	echo '<textarea id="'. esc_attr($name) .'" name="'. esc_attr($name) .'" rows="3" cols="50" class="large-text code">'. wp_kses(stripslashes_deep($value), $allowed_tags) .'</textarea>';
	echo '<label for="'. esc_attr($name) .'" class="display-block">'. esc_html($label) .'</label>';
	
}

function banhammer_callback_checkbox($args) {
	
	global $BanhammerWP;
	
	$default = $BanhammerWP->options();
	
	$options = get_option('banhammer_settings', $default);
	
	$id    = isset($args['id'])    ? $args['id']    : '';
	$label = isset($args['label']) ? $args['label'] : '';
	$value = isset($options[$id])  ? $options[$id]  : '';
	
	$name = 'banhammer_settings['. $id .']';
	
	echo '<label><input id="'. esc_attr($name) .'" name="'. esc_attr($name) .'" type="checkbox" '. checked($value, 1, false) .' value="1"> '. esc_html($label) .'</label>';
	
}

function banhammer_callback_select($args) {
	
	global $BanhammerWP;
	
	$default = $BanhammerWP->options();
	
	$options = get_option('banhammer_settings', $default);
	
	$id    = isset($args['id'])    ? $args['id']    : '';
	$label = isset($args['label']) ? $args['label'] : '';
	$value = isset($options[$id])  ? $options[$id]  : '';
	
	$name = 'banhammer_settings['. $id .']';
	
	$items = array();
	
	if     ($id === 'banned_response') $items = banhammer_response_options();
	elseif ($id === 'status_code')     $items = banhammer_status_codes();
	elseif ($id === 'reset_interval')  $items = banhammer_reset_interval();
	
	echo '<select id="'. esc_attr($name) .'" name="'. esc_attr($name) .'">';
	
	foreach ($items as $item) {
		
		$item_label = isset($item['label']) ? $item['label'] : '';
		$item_value = isset($item['value']) ? $item['value'] : '';
		
		echo '<option '. selected($item_value, $value, false) .' value="'. esc_attr($item_value) .'">'. esc_html($item_label) .'</option>';
		
	}
	
	echo '</select> <label for="'. esc_attr($name) .'">'. esc_html($label) .'</label>';
	
}

function banhammer_callback_reset($args) {
	
	$nonce = wp_create_nonce('banhammer_reset_options');
	
	$href  = add_query_arg(array('banhammer-reset-options' => $nonce), admin_url('admin.php?page=banhammer'));
	
	$label = isset($args['label']) ? $args['label'] : esc_html__('Restore default plugin options', 'banhammer');
	
	echo '<a class="banhammer-reset-options" href="'. esc_url($href) .'">'. esc_html($label) .'</a>';
	
}

function banhammer_callback_rate($args) {
	
	$href  = 'https://wordpress.org/support/plugin/'. BANHAMMER_SLUG .'/reviews/?rate=5#new-post';
	$title = esc_attr__('Help keep Banhammer going strong! A huge THANK YOU for your support!', 'banhammer');
	$text  = isset($args['label']) ? $args['label'] : esc_html__('Show support with a 5-star rating&nbsp;&raquo;', 'banhammer');
	
	echo '<a target="_blank" rel="noopener noreferrer" class="banhammer-rate-plugin" href="'. esc_url($href) .'" title="'. esc_attr($title) .'">'. esc_html($text) .'</a>';
	
}

function banhammer_callback_support($args) {
	
	$href  = 'https://monzillamedia.com/donate.html';
	$title = esc_attr__('Donate via PayPal, credit card, or cryptocurrency', 'banhammer');
	$text  = isset($args['label']) ? $args['label'] : esc_html__('Show support with a small donation&nbsp;&raquo;', 'banhammer');
	
	echo '<a target="_blank" rel="noopener noreferrer" class="banhammer-show-support" href="'. $href .'" title="'. $title .'">'. $text .'</a>';
	
}
