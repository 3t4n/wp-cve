<?php

defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');

function siteseo_google_analytics_enable_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$check = isset($options['google_analytics_enable']); ?>

<label for="siteseo_google_analytics_enable">
	<input id="siteseo_google_analytics_enable"
		name="siteseo_google_analytics_option_name[google_analytics_enable]" type="checkbox" <?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>

	<?php esc_html_e('Enable Google Analytics tracking (Global Site Tag: gtag.js)', 'siteseo'); ?>
</label>

<?php
}

function siteseo_google_analytics_ga4_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_ga4']) ? $options['google_analytics_ga4'] : null;

	printf(
'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_ga4]" placeholder="' . esc_html__('Enter your measurement ID (G-XXXXXXXXXX)', 'siteseo') . '" aria-label="' . esc_html__('Enter your measurement ID', 'siteseo') . '" value="%s"/>',
esc_html($check)
); ?>

<p class="siteseo-help description">
	<span class="dashicons dashicons-external"></span>
	<a href="https://support.google.com/analytics/answer/9539598?hl=en&ref_topic=9303319" target="_blank">
		<?php esc_html_e('Find your measurement ID', 'siteseo'); ?>
	</a>
</p>
<?php
}

function siteseo_google_analytics_hook_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$selected = isset($options['google_analytics_hook']) ? $options['google_analytics_hook'] : null; ?>

<select id="siteseo_google_analytics_hook"
	name="siteseo_google_analytics_option_name[google_analytics_hook]">
	<option <?php if ('wp_body_open' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="wp_body_open"><?php esc_html_e('After the opening body tag (recommended)', 'siteseo'); ?>
	</option>
	<option <?php if ('wp_footer' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="wp_footer"><?php esc_html_e('Footer', 'siteseo'); ?>
	</option>
	<option <?php if ('wp_head' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="wp_head"><?php esc_html_e('Head (not recommended)', 'siteseo'); ?>
	</option>
</select>

<p class="description">
	<?php esc_html_e('Your theme must be compatible with wp_body_open hook introduced in WordPress 5.2 if "opening body tag" option selected.'); ?>
</p>

<?php
}

function siteseo_google_analytics_disable_callback() {
	$docs = siteseo_get_docs_links();

	$options = get_option('siteseo_google_analytics_option_name');

	$check = isset($options['google_analytics_disable']); ?>

<label for="siteseo_google_analytics_disable">
	<input id="siteseo_google_analytics_disable"
		name="siteseo_google_analytics_option_name[google_analytics_disable]" type="checkbox" <?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Request user\'s consent for analytics tracking (required by GDPR)', 'siteseo'); ?>
</label>

<div class="siteseo-notice">
	<span class="dashicons dashicons-info"></span>
	<p><?php echo wp_kses_post(__('The user must click the <strong>Accept button</strong> to allow tracking.', 'siteseo')); ?>
	</p>
</div>

<p class="description">
	<?php esc_html_e('User roles excluded from tracking will not see the consent message.', 'siteseo'); ?>
</p>
<p class="description">
	<?php esc_html_e('If you use a caching plugin, you have to exclude this JS file in your settings:', 'siteseo'); ?>
</p>
<p class="description">
	<?php echo wp_kses_post(__('<strong>/wp-content/plugins/siteseo/assets/js/siteseo-cookies-ajax.js</strong> and this cookie <strong>siteseo-user-consent-accept</strong>', 'siteseo')); ?>
	<?php echo wp_kses_post(siteseo_tooltip_link($docs['analytics']['custom_tracking'], esc_html__('Hook to add custom tracking code with user consent - new window', 'siteseo'))); ?>
</p>

<?php
}

function siteseo_google_analytics_half_disable_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$check = isset($options['google_analytics_half_disable']); ?>

<label for="siteseo_google_analytics_half_disable">
	<input id="siteseo_google_analytics_half_disable"
		name="siteseo_google_analytics_option_name[google_analytics_half_disable]" type="checkbox" <?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Display and automatically accept the user‘s consent on page load (not fully GDPR)', 'siteseo'); ?>
</label>

<p class="description">
	<?php esc_html_e('The previous option must be checked to use this.', 'siteseo'); ?>
</p>

<?php
}

function siteseo_google_analytics_opt_out_edit_choice_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$check = isset($options['google_analytics_opt_out_edit_choice']); ?>

<label for="siteseo_google_analytics_opt_out_edit_choice">
	<input id="siteseo_google_analytics_opt_out_edit_choice"
		name="siteseo_google_analytics_option_name[google_analytics_opt_out_edit_choice]" type="checkbox"
		<?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Allow user to change its choice about cookies', 'siteseo'); ?>
</label>

<?php
}

function siteseo_google_analytics_opt_out_msg_callback() {
	$docs	= siteseo_get_docs_links();
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_opt_out_msg']) ? $options['google_analytics_opt_out_msg'] : null;

	printf(
'<textarea id="siteseo_google_analytics_opt_out_msg" name="siteseo_google_analytics_option_name[google_analytics_opt_out_msg]" rows="4" placeholder="' . esc_html__('Enter your message (HTML allowed)', 'siteseo') . '" aria-label="' . esc_html__('This message will only appear if request user\'s consent is enabled.', 'siteseo') . '">%s</textarea>',
esc_html($check)); ?>

<?php echo wp_kses_post(siteseo_tooltip_link($docs['analytics']['consent_msg'], esc_html__('Hook to filter user consent message - new window', 'siteseo'))); ?>

<p class="description">
	<?php esc_html_e('HTML tags allowed: strong, em, br, a href / target', 'siteseo'); ?>
</p>
<p class="description">
	<?php esc_html_e('Shortcode allowed to get the privacy page set in WordPress settings: [siteseo_privacy_page]', 'siteseo'); ?>
</p>

<?php
}

function siteseo_google_analytics_opt_out_msg_ok_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_opt_out_msg_ok']) ? $options['google_analytics_opt_out_msg_ok'] : null;

	printf(
'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_opt_out_msg_ok]" placeholder="' . esc_html__('Accept', 'siteseo') . '" aria-label="' . esc_html__('Change the button value', 'siteseo') . '" value="%s"/>',
esc_html($check)
);
}

function siteseo_google_analytics_opt_out_msg_close_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check   = isset($options['google_analytics_opt_out_msg_close']) ? $options['google_analytics_opt_out_msg_close'] : null;

	printf(
'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_opt_out_msg_close]" placeholder="' . esc_html__('default: X', 'siteseo') . '" aria-label="' . esc_html__('Change the close button value', 'siteseo') . '" value="%s"/>',
esc_html($check)
);
}

function siteseo_google_analytics_opt_out_msg_edit_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check   = isset($options['google_analytics_opt_out_msg_edit']) ? $options['google_analytics_opt_out_msg_edit'] : null;

	printf(
'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_opt_out_msg_edit]" placeholder="' . esc_html__('default: Manage cookies', 'siteseo') . '" aria-label="' . esc_html__('Change the edit button value', 'siteseo') . '" value="%s"/>',
esc_html($check)
);
}

function siteseo_google_analytics_cb_exp_date_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$check = isset($options['google_analytics_cb_exp_date']); ?>

<input type="number" min="1" name="siteseo_google_analytics_option_name[google_analytics_cb_exp_date]" <?php if ('1' == $check) { ?>
value="<?php echo esc_attr($options['google_analytics_cb_exp_date']); ?>"
<?php } ?>
value="30"/>

<p class="description">
	<?php esc_html_e('Default: 30 days before the cookie expiration.', 'siteseo'); ?>
</p>

<?php
}

function siteseo_google_analytics_cb_pos_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$selected = isset($options['google_analytics_cb_pos']) ? $options['google_analytics_cb_pos'] : null; ?>

<select id="siteseo_google_analytics_cb_pos"
	name="siteseo_google_analytics_option_name[google_analytics_cb_pos]">
	<option <?php if ('bottom' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="bottom"><?php esc_html_e('Bottom (default)', 'siteseo'); ?>
	</option>
	<option <?php if ('center' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="center"><?php esc_html_e('Middle', 'siteseo'); ?>
	</option>
	<option <?php if ('top' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="top"><?php esc_html_e('Top', 'siteseo'); ?>
	</option>
</select>

<?php
}

function siteseo_google_analytics_cb_txt_align_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$selected = isset($options['google_analytics_cb_txt_align']) ? $options['google_analytics_cb_txt_align'] : 'center'; ?>

<select id="siteseo_google_analytics_cb_txt_align"
	name="siteseo_google_analytics_option_name[google_analytics_cb_txt_align]">
	<option <?php if ('left' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="left"><?php esc_html_e('Left', 'siteseo'); ?>
	</option>
	<option <?php if ('center' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="center"><?php esc_html_e('Center (default)', 'siteseo'); ?>
	</option>
	<option <?php if ('right' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="right"><?php esc_html_e('Right', 'siteseo'); ?>
	</option>
</select>

<?php
}

function siteseo_google_analytics_cb_width_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_cb_width']) ? $options['google_analytics_cb_width'] : null;

	printf(
'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_cb_width]" aria-label="' . esc_html__('Change the cookie bar width', 'siteseo') . '" value="%s"/>',
esc_html($check)
); ?>
<p class="description">
	<?php esc_html_e('Default unit is Pixels. Add % just after your custom value to use percentages (eg: 80%).', 'siteseo'); ?>
</p>

<?php
}

function siteseo_google_analytics_cb_backdrop_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$check = isset($options['google_analytics_cb_backdrop']); ?>

<hr>

<h2>
	<?php esc_html_e('Backdrop', 'siteseo'); ?>
</h2>

<p>
	<?php echo wp_kses_post(__('Customize the cookie bar <strong>backdrop</strong>.', 'siteseo')); ?>
</p>

<label for="siteseo_google_analytics_cb_backdrop">
	<input id="siteseo_google_analytics_cb_backdrop"
		name="siteseo_google_analytics_option_name[google_analytics_cb_backdrop]" type="checkbox" <?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Display a backdrop with the cookie bar', 'siteseo'); ?>
</label>

<?php
}

function siteseo_google_analytics_cb_backdrop_bg_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_cb_backdrop_bg']) ? $options['google_analytics_cb_backdrop_bg'] : null; ?>

<p class="description">
	<?php esc_html_e('Background color: ', 'siteseo'); ?>
</p>

<?php printf(
'<input type="text" data-default-color="rgba(255,255,255,0.8)" data-alpha="true" name="siteseo_google_analytics_option_name[google_analytics_cb_backdrop_bg]" aria-label="' . esc_html__('Change the background color of the backdrop', 'siteseo') . '" value="%s" class="color-picker"/>',
esc_html($check)
);
}

function siteseo_google_analytics_cb_bg_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_cb_bg']) ? $options['google_analytics_cb_bg'] : null; ?>
<hr>

<h2><?php esc_html_e('Main settings', 'siteseo'); ?>
</h2>

<p>
	<?php echo wp_kses_post(__('Customize the general settings of the <strong>cookie bar</strong>.', 'siteseo')); ?>
</p>

<p class="description">
	<?php esc_html_e('Background color: ', 'siteseo'); ?>
</p>

<?php
	printf(
'<input type="text" data-alpha="true" data-default-color="#F1F1F1" name="siteseo_google_analytics_option_name[google_analytics_cb_bg]" aria-label="' . esc_html__('Change the color of the cookie bar background', 'siteseo') . '" value="%s" class="color-picker"/>',
esc_html($check)
);
}

function siteseo_google_analytics_cb_txt_col_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_cb_txt_col']) ? $options['google_analytics_cb_txt_col'] : null; ?>

<p class="description">
	<?php esc_html_e('Text color: ', 'siteseo'); ?>
</p>

<?php
	printf(
'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_cb_txt_col]" aria-label="' . esc_html__('Change the color of the cookie bar text', 'siteseo') . '" value="%s" class="color-picker"/>',
esc_html($check)
);
}

function siteseo_google_analytics_cb_lk_col_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_cb_lk_col']) ? $options['google_analytics_cb_lk_col'] : null; ?>

<p class="description">
	<?php esc_html_e('Link color: ', 'siteseo'); ?>
</p>

<?php
	printf(
'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_cb_lk_col]" aria-label="' . esc_html__('Change the color of the cookie bar link', 'siteseo') . '" value="%s" class="color-picker"/>',
esc_html($check)
);
}

function siteseo_google_analytics_cb_btn_bg_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_cb_btn_bg']) ? $options['google_analytics_cb_btn_bg'] : null; ?>

<hr>

<h2>
	<?php esc_html_e('Primary button', 'siteseo'); ?>
</h2>

<p>
	<?php echo wp_kses_post(__('Customize the <strong>Accept button</strong>.', 'siteseo')); ?>
</p>

<p class="description">
	<?php esc_html_e('Background color: ', 'siteseo'); ?>
</p>

<?php printf(
'<input type="text" data-alpha="true" name="siteseo_google_analytics_option_name[google_analytics_cb_btn_bg]" aria-label="' . esc_html__('Change the color of the cookie bar button background', 'siteseo') . '" value="%s" class="color-picker"/>',
esc_html($check)
);
}

function siteseo_google_analytics_cb_btn_bg_hov_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_cb_btn_bg_hov']) ? $options['google_analytics_cb_btn_bg_hov'] : null; ?>

<p class="description">
	<?php esc_html_e('Background color on hover: ', 'siteseo'); ?>
</p>

<?php
	printf(
'<input type="text" data-alpha="true" name="siteseo_google_analytics_option_name[google_analytics_cb_btn_bg_hov]" aria-label="' . esc_html__('Change the color of the cookie bar button hover background', 'siteseo') . '" value="%s" class="color-picker"/>',
esc_html($check)
);
}

function siteseo_google_analytics_cb_btn_col_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check   = isset($options['google_analytics_cb_btn_col']) ? $options['google_analytics_cb_btn_col'] : null; ?>

<p class="description">
	<?php esc_html_e('Text color: ', 'siteseo'); ?>
</p>

<?php
	printf(
'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_cb_btn_col]" aria-label="' . esc_html__('Change the color of the cookie bar button', 'siteseo') . '" value="%s" class="color-picker"/>',
esc_html($check)
);
}

function siteseo_google_analytics_cb_btn_col_hov_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check   = isset($options['google_analytics_cb_btn_col_hov']) ? $options['google_analytics_cb_btn_col_hov'] : null; ?>

<p class="description">
	<?php esc_html_e('Text color on hover: ', 'siteseo'); ?>
</p>

<?php
	printf(
'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_cb_btn_col_hov]" aria-label="' . esc_html__('Change the color of the cookie bar button hover', 'siteseo') . '" value="%s" class="color-picker"/>',
esc_html($check)
);
}

function siteseo_google_analytics_cb_btn_sec_bg_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check   = isset($options['google_analytics_cb_btn_sec_bg']) ? $options['google_analytics_cb_btn_sec_bg'] : null; ?>

<hr>

<h2>
	<?php esc_html_e('Secondary button', 'siteseo'); ?>
</h2>

<p>
	<?php echo wp_kses_post(__('Customize the <strong>Close button</strong>.', 'siteseo')); ?>
</p>

<p class="description">
	<?php esc_html_e('Background color: ', 'siteseo'); ?>
</p>

<?php
	printf(
'<input type="text" data-alpha="true" name="siteseo_google_analytics_option_name[google_analytics_cb_btn_sec_bg]" aria-label="' . esc_html__('Change the color of the cookie bar secondary button background', 'siteseo') . '" value="%s" class="color-picker"/>',
esc_html($check)
);
}

function siteseo_google_analytics_cb_btn_sec_col_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check   = isset($options['google_analytics_cb_btn_sec_col']) ? $options['google_analytics_cb_btn_sec_col'] : null; ?>

<p class="description">
	<?php esc_html_e('Text color: ', 'siteseo'); ?>
</p>

<?php
	printf(
'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_cb_btn_sec_col]" aria-label="' . esc_html__('Change the color of the cookie bar secondary button hover background', 'siteseo') . '" value="%s" class="color-picker"/>',
esc_html($check)
);
}

function siteseo_google_analytics_cb_btn_sec_bg_hov_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_cb_btn_sec_bg_hov']) ? $options['google_analytics_cb_btn_sec_bg_hov'] : null; ?>

<p class="description">
	<?php esc_html_e('Background color on hover: ', 'siteseo'); ?>
</p>

<?php
	printf(
'<input type="text" data-alpha="true" data-default-color="#222222" name="siteseo_google_analytics_option_name[google_analytics_cb_btn_sec_bg_hov]" aria-label="' . esc_html__('Change the color of the cookie bar secondary button', 'siteseo') . '" value="%s" class="color-picker"/>',
esc_html($check)
);
}

function siteseo_google_analytics_cb_btn_sec_col_hov_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_cb_btn_sec_col_hov']) ? $options['google_analytics_cb_btn_sec_col_hov'] : null; ?>

<p class="description">
	<?php esc_html_e('Text color on hover: ', 'siteseo'); ?>
</p>

<?php
	printf(
'<input type="text" data-default-color="#FFFFFF" name="siteseo_google_analytics_option_name[google_analytics_cb_btn_sec_col_hov]" aria-label="' . esc_html__('Change the color of the cookie bar secondary button hover', 'siteseo') . '" value="%s" class="color-picker"/>',
esc_html($check)
);
}

function siteseo_google_analytics_roles_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	global $wp_roles;

	if ( ! isset($wp_roles)) {
		$wp_roles = new WP_Roles();
	}

	foreach ($wp_roles->get_names() as $key => $value) {
		$check = isset($options['google_analytics_roles'][$key]); ?>

<p>
	<label for="siteseo_google_analytics_roles_<?php echo esc_attr($key); ?>">
		<input
			id="siteseo_google_analytics_roles_<?php echo esc_attr($key); ?>"
			name="siteseo_google_analytics_option_name[google_analytics_roles][<?php echo esc_attr($key); ?>]"
			type="checkbox" <?php if ('1' == $check) { ?>
		checked="yes"
		<?php } ?>
		value="1"/>
		<strong><?php echo esc_html($value); ?></strong> (<em><?php echo esc_html(translate_user_role($value,  'default')); ?></em>)
	</label>
</p>

<?php
	}
}

function siteseo_google_analytics_optimize_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_optimize']) ? $options['google_analytics_optimize'] : null;

	printf(
'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_optimize]" placeholder="' . esc_html__('Enter your Google Optimize container ID', 'siteseo') . '" value="%s" aria-label="' . esc_html__('GTM-XXXXXXX', 'siteseo') . '"/>',
esc_html($check)); ?>

<p class="description">
	<?php esc_html_e('Google Optimize offers A/B testing, website testing & personalization tools.', 'siteseo'); ?>

	<a class="siteseo-help" href="https://marketingplatform.google.com/about/optimize/" target="_blank">
		<?php esc_html_e('Learn more', 'siteseo'); ?>
	</a>
	<span class="siteseo-help dashicons dashicons-external"></span>
</p>

<?php
}

function siteseo_google_analytics_ads_callback() {
	$docs	= siteseo_get_docs_links();
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_ads']) ? $options['google_analytics_ads'] : null; ?>

<?php
	printf(
'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_ads]" placeholder="' . esc_html__('Enter your Google Ads conversion ID (eg: AW-123456789)', 'siteseo') . '" value="%s" aria-label="' . esc_html__('AW-XXXXXXXXX', 'siteseo') . '"/>',
esc_html($check)); ?>
<p class="description">
	<a class="siteseo-help" href="<?php echo esc_attr($docs['analytics']['gads']); ?>" target="_blank">
		<?php esc_html_e('Learn how to find your Google Ads Conversion ID', 'siteseo'); ?>
	</a>
	<span class="siteseo-help dashicons dashicons-external"></span>
</p>

<?php
}

function siteseo_google_analytics_other_tracking_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_other_tracking']) ? $options['google_analytics_other_tracking'] : '';

	printf(
'<textarea id="siteseo_google_analytics_other_tracking" name="siteseo_google_analytics_option_name[google_analytics_other_tracking]" rows="16" placeholder="' . esc_html__('Paste your tracking code here like Google Tag Manager (head). Do NOT paste GA4 or Universal Analytics codes here. They are automatically added to your source code.', 'siteseo') . '" aria-label="' . esc_html__('Additional tracking code field', 'siteseo') . '">%s</textarea>',
esc_textarea($check)); ?>
<p class="description">
	<?php esc_html_e('This code will be added in the head section of your page.', 'siteseo'); ?>
</p>
<?php
}

function siteseo_google_analytics_other_tracking_body_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$docs = siteseo_get_docs_links();
	$check = isset($options['google_analytics_other_tracking_body']) ? $options['google_analytics_other_tracking_body'] : '';

	printf(
'<textarea id="siteseo_google_analytics_other_tracking_body" name="siteseo_google_analytics_option_name[google_analytics_other_tracking_body]" rows="16" placeholder="' . esc_html__('Paste your tracking code here like Google Tag Manager (body)', 'siteseo') . '" aria-label="' . esc_html__('Additional tracking code field added to body', 'siteseo') . '">%s</textarea>',
esc_textarea($check)); ?>
<p class="description"><?php esc_html_e('This code will be added just after the opening body tag of your page.', 'siteseo'); ?>
</p>

<p class="description"><?php echo wp_kses_post(__('You don‘t see your code? Make sure to call <strong>wp_body_open();</strong> just after the opening body tag in your theme.', 'siteseo')); ?>
</p>

<p class="description">
	<a class="siteseo-help"
		href="<?php echo esc_attr($docs['analytics']['gtm']); ?>"
		target="_blank">
		<?php esc_html_e('Learn how to integrate Google Tag Manager', 'siteseo'); ?>
	</a>
	<span class="siteseo-help dashicons dashicons-external"></span>
</p>

<?php
}

function siteseo_google_analytics_other_tracking_footer_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_other_tracking_footer']) ? $options['google_analytics_other_tracking_footer'] : '';

	printf(
'<textarea id="siteseo_google_analytics_other_tracking_footer" name="siteseo_google_analytics_option_name[google_analytics_other_tracking_footer]" rows="16" placeholder="' . esc_html__('Paste your tracking code here (footer)', 'siteseo') . '" aria-label="' . esc_html__('Additional tracking code field added to footer', 'siteseo') . '">%s</textarea>',
esc_textarea($check)); ?>

<p class="description">
	<?php esc_html_e('This code will be added just after the closing body tag of your page.', 'siteseo'); ?>
</p>
<?php
}

function siteseo_google_analytics_remarketing_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$check = isset($options['google_analytics_remarketing']); ?>

<label for="siteseo_google_analytics_remarketing">
	<input id="siteseo_google_analytics_remarketing"
		name="siteseo_google_analytics_option_name[google_analytics_remarketing]" type="checkbox" <?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Enable remarketing, demographics, and interests reporting', 'siteseo'); ?>
</label>

<p class="description">
	<?php esc_html_e('A remarketing audience is a list of cookies or mobile-advertising IDs that represents a group of users you want to re-engage because of their likelihood to convert.', 'siteseo'); ?>
	<a class="siteseo-help" href="https://support.google.com/analytics/answer/2611268?hl=en" target="_blank">
		<?php esc_html_e('Learn more', 'siteseo'); ?>
	</a>
	<span class="siteseo-help dashicons dashicons-external"></span>
</p>

<?php
}

function siteseo_google_analytics_ip_anonymization_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$check = isset($options['google_analytics_ip_anonymization']); ?>

<label for="siteseo_google_analytics_ip_anonymization">
	<input id="siteseo_google_analytics_ip_anonymization"
		name="siteseo_google_analytics_option_name[google_analytics_ip_anonymization]" type="checkbox" <?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Enable IP Anonymization', 'siteseo'); ?>
</label>

<p class="description">
	<?php esc_html_e('When a customer of Analytics requests IP address anonymization, Analytics anonymizes the address as soon as technically feasible at the earliest possible stage of the collection network.', 'siteseo'); ?>
	<a class="siteseo-help" href="https://developers.google.com/analytics/devguides/collection/gtagjs/ip-anonymization"
		target="_blank">
		<?php esc_html_e('Learn more', 'siteseo'); ?>
	</a>
	<span class="siteseo-help dashicons dashicons-external"></span>
</p>

<?php
}

function siteseo_google_analytics_link_attribution_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$check = isset($options['google_analytics_link_attribution']); ?>

<label for="siteseo_google_analytics_link_attribution">
	<input id="siteseo_google_analytics_link_attribution"
		name="siteseo_google_analytics_option_name[google_analytics_link_attribution]" type="checkbox" <?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Enhanced Link Attribution', 'siteseo'); ?>
</label>

<p class="description">
	<?php esc_html_e('Enhanced Link Attribution improves the accuracy of your In-Page Analytics report by automatically differentiating between multiple links to the same URL on a single page by using link element IDs.', 'siteseo'); ?>
	<a class="siteseo-help"
		href="https://developers.google.com/analytics/devguides/collection/gtagjs/enhanced-link-attribution"
		target="_blank">
		<?php esc_html_e('Learn more', 'siteseo'); ?>
	</a>
	<span class="siteseo-help dashicons dashicons-external"></span>
</p>

<?php
}

function siteseo_google_analytics_cross_enable_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$check = isset($options['google_analytics_cross_enable']); ?>

<label for="siteseo_google_analytics_cross_enable">
	<input id="siteseo_google_analytics_cross_enable"
		name="siteseo_google_analytics_option_name[google_analytics_cross_enable]" type="checkbox" <?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Enable cross-domain tracking', 'siteseo'); ?>
</label>

<p class="description">
	<?php esc_html_e('Cross domain tracking makes it possible for Analytics to see sessions on two related sites (such as an ecommerce site and a separate shopping cart site) as a single session. This is sometimes called site linking.', 'siteseo'); ?>
	<a class="siteseo-help" href="https://developers.google.com/analytics/devguides/collection/gtagjs/cross-domain"
		target="_blank">
		<?php esc_html_e('Learn more', 'siteseo'); ?>
	</a>
	<span class="siteseo-help dashicons dashicons-external"></span>
</p>

<?php
}

function siteseo_google_analytics_cross_domain_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_cross_domain']) ? $options['google_analytics_cross_domain'] : null;

	printf(
'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_cross_domain]" placeholder="' . esc_html__('Enter your domains: siteseo.io,sub.siteseo.io,sub2.siteseo.io', 'siteseo') . '" value="%s" aria-label="' . esc_html__('Cross domains', 'siteseo') . '"/>',
esc_html($check)
);
}

function siteseo_google_analytics_link_tracking_enable_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$check = isset($options['google_analytics_link_tracking_enable']); ?>

<label for="siteseo_google_analytics_link_tracking_enable">
	<input id="siteseo_google_analytics_link_tracking_enable"
		name="siteseo_google_analytics_option_name[google_analytics_link_tracking_enable]" type="checkbox"
		<?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Enable external links tracking', 'siteseo'); ?>
</label>

<?php
}

function siteseo_google_analytics_download_tracking_enable_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$check = isset($options['google_analytics_download_tracking_enable']); ?>

<label for="siteseo_google_analytics_download_tracking_enable">
	<input id="siteseo_google_analytics_download_tracking_enable"
		name="siteseo_google_analytics_option_name[google_analytics_download_tracking_enable]" type="checkbox"
		<?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Enable download tracking', 'siteseo'); ?>
</label>

<?php
}

function siteseo_google_analytics_download_tracking_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_download_tracking']) ? $options['google_analytics_download_tracking'] : null;

	printf(
'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_download_tracking]" placeholder="' . esc_html__('pdf|docx|pptx|zip', 'siteseo') . '" aria-label="' . esc_html__('Track downloads\' clicks', 'siteseo') . '" value="%s"/>',
esc_html($check)
); ?>
<p class="description">
	<?php esc_html_e('Separate each file type extensions with a pipe "|"', 'siteseo'); ?>
</p>

<?php
}

function siteseo_google_analytics_affiliate_tracking_enable_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$check = isset($options['google_analytics_affiliate_tracking_enable']); ?>

<label for="siteseo_google_analytics_affiliate_tracking_enable">
	<input id="siteseo_google_analytics_affiliate_tracking_enable"
		name="siteseo_google_analytics_option_name[google_analytics_affiliate_tracking_enable]"
		type="checkbox" <?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Enable affiliate/outbound tracking', 'siteseo'); ?>
</label>

<?php
}

function siteseo_google_analytics_affiliate_tracking_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_affiliate_tracking']) ? $options['google_analytics_affiliate_tracking'] : null;

	printf(
'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_affiliate_tracking]" placeholder="' . esc_html__('aff|go|out', 'siteseo') . '" aria-label="' . esc_html__('Track affiliate/outbound links', 'siteseo') . '" value="%s"/>',
esc_html($check)
); ?>
<p class="description">
	<?php esc_html_e('Separate each keyword with a pipe "|"', 'siteseo'); ?>
</p>
<?php
}

function siteseo_google_analytics_phone_tracking_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$check = isset($options['google_analytics_phone_tracking']); ?>

<label for="siteseo_google_analytics_phone_tracking">
	<input id="siteseo_google_analytics_phone_tracking"
		name="siteseo_google_analytics_option_name[google_analytics_phone_tracking]"
		type="checkbox" <?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Enable tracking of "tel:" links', 'siteseo'); ?>
</label>

<p class="description">
	<pre>&lt;a href="tel:+33123456789"&gt;</pre>
</p>

<?php
}

function siteseo_google_analytics_cd_author_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$selected = isset($options['google_analytics_cd_author']) ? $options['google_analytics_cd_author'] : null; ?>
<select id="siteseo_google_analytics_cd_author"
	name="siteseo_google_analytics_option_name[google_analytics_cd_author]">
	<option <?php if ('none' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="none"><?php esc_html_e('None', 'siteseo'); ?>
	</option>

	<?php for ($i=1; $i <= 20; ++$i) { ?>
	<option <?php if ('dimension' . $i . '' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="dimension<?php echo esc_attr($i); ?>"><?php printf(esc_html__('Custom Dimension #%d', 'siteseo'), esc_html($i)); ?>
	</option>
	<?php } ?>
</select>

<?php
}

function siteseo_google_analytics_cd_category_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$selected = isset($options['google_analytics_cd_category']) ? $options['google_analytics_cd_category'] : null; ?>
<select id="siteseo_google_analytics_cd_category"
	name="siteseo_google_analytics_option_name[google_analytics_cd_category]">
	<option <?php if ('none' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="none"><?php esc_html_e('None', 'siteseo'); ?>
	</option>

	<?php for ($i=1; $i <= 20; ++$i) { ?>
	<option <?php if ('dimension' . $i . '' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="dimension<?php echo esc_attr($i); ?>"><?php printf(esc_html__('Custom Dimension #%d', 'siteseo'), esc_attr($i)); ?>
	</option>
	<?php } ?>
</select>

<?php
}

function siteseo_google_analytics_cd_tag_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$selected = isset($options['google_analytics_cd_tag']) ? $options['google_analytics_cd_tag'] : null; ?>

<select id="siteseo_google_analytics_cd_tag"
	name="siteseo_google_analytics_option_name[google_analytics_cd_tag]">
	<option <?php if ('none' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="none"><?php esc_html_e('None', 'siteseo'); ?>
	</option>

	<?php for ($i=1; $i <= 20; ++$i) { ?>
	<option <?php if ('dimension' . $i . '' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="dimension<?php echo esc_attr($i); ?>"><?php printf(esc_html__('Custom Dimension #%d', 'siteseo'), esc_attr($i)); ?>
	</option>
	<?php } ?>
</select>

<?php
}

function siteseo_google_analytics_cd_post_type_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$selected = isset($options['google_analytics_cd_post_type']) ? $options['google_analytics_cd_post_type'] : null; ?>

<select id="siteseo_google_analytics_cd_post_type"
	name="siteseo_google_analytics_option_name[google_analytics_cd_post_type]">
	<option <?php if ('none' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="none"><?php esc_html_e('None', 'siteseo'); ?>
	</option>

	<?php for ($i=1; $i <= 20; ++$i) { ?>
	<option <?php if ('dimension' . $i . '' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="dimension<?php echo esc_attr($i); ?>"><?php printf(esc_html__('Custom Dimension #%d', 'siteseo'), esc_attr($i)); ?>
	</option>
	<?php } ?>
</select>
<?php
}

function siteseo_google_analytics_cd_logged_in_user_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$selected = isset($options['google_analytics_cd_logged_in_user']) ?
	$options['siteseo_google_analytics_cd_logged_in_user'] : null; ?>

<select id="siteseo_google_analytics_cd_logged_in_user"
	name="siteseo_google_analytics_option_name[google_analytics_cd_logged_in_user]">
	<option <?php if (' none' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="none"><?php esc_html_e('None', 'siteseo'); ?>
	</option>
	<?php for ($i=1; $i <= 20; ++$i) { ?>
	<option <?php if ('dimension' . $i . '' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="dimension<?php echo esc_attr($i); ?>"><?php printf(esc_html__('Custom Dimension #%d', 'siteseo'), esc_attr($i)); ?>
	</option>
	<?php } ?>
</select>
<?php
}
function siteseo_google_analytics_matomo_enable_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_matomo_enable']); ?>


<label for="siteseo_google_analytics_matomo_enable">
	<input id="siteseo_google_analytics_matomo_enable"
		name="siteseo_google_analytics_option_name[google_analytics_matomo_enable]" type="checkbox" <?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>

	<?php esc_html_e('Enable Matomo tracking', 'siteseo'); ?>
	<p class="description">
		<?php esc_html_e('A Matomo Cloud account or a self hosted Matomo installation is required.', 'siteseo'); ?>
	</p>
</label>

<?php
}

function siteseo_google_analytics_matomo_self_hosted_callback() {
	$docs = siteseo_get_docs_links();
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_matomo_self_hosted']); ?>


<label for="siteseo_google_analytics_matomo_self_hosted">
	<input id="siteseo_google_analytics_matomo_self_hosted"
		name="siteseo_google_analytics_option_name[google_analytics_matomo_self_hosted]" type="checkbox" <?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>

	<?php esc_html_e('Yes, self-hosted installation', 'siteseo'); ?>
	<p class="description">
		<?php esc_html_e('If you use Matomo Cloud, uncheck this option.', 'siteseo'); ?>
	</p>
	<p class="description">
		<span class="dashicons dashicons-external"></span>
		<?php printf('<a href="%s" target="_blank">'.esc_html__('Learn how to install Matomo On-Premise on your server.', 'siteseo').'</a>', esc_attr($docs['analytics']['matomo']['on_premise'])); ?>
	</p>
</label>

<?php
}

function siteseo_google_analytics_matomo_id_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$check = isset($options['google_analytics_matomo_id']) ? $options['google_analytics_matomo_id'] : null;

	printf('<input type="text" name="siteseo_google_analytics_option_name[google_analytics_matomo_id]" placeholder="'
		. esc_html__('Enter "example" if you Matomo account URL is "example.matomo.cloud"', ' siteseo')
		. '" value="%s" aria-label="' . esc_html__('Matomo URL (Cloud or Self-hosted)', 'siteseo') . '"/>', esc_html($check)); ?>

<p class="description">
	<?php echo wp_kses_post(__('Enter only the <strong>host without the quotes</strong> like this <strong>"example.matomo.cloud"</strong> (Cloud) or <strong>"matomo.example.com"</strong> (self-hosted).')); ?>
</p>

<?php
}

function siteseo_google_analytics_matomo_site_id_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_matomo_site_id']) ? $options['google_analytics_matomo_site_id'] : null;

	printf(
		'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_matomo_site_id]"
			placeholder="' . esc_html__('Enter your site ID here', 'siteseo') . '" value="%s"
			aria-label="' . esc_html__('Matomo Site ID', 'siteseo') . '" />',
		esc_html($check)
		); ?>

<p class="description">
	<?php echo wp_kses_post(__('To find your site ID, go to your <strong>Matomo Cloud account, Websites, Manage page</strong>. Look at "Site ID" on the right part.', 'siteseo')); ?><br>
	<?php esc_html_e('For self-hosted installations, go to your Matomo administration, Settings, Websites, Manage. From the list of your websites, find the ID line.', 'siteseo'); ?>
</p>
<?php
}

function siteseo_google_analytics_matomo_subdomains_callback() {
	$options = get_option('siteseo_google_analytics_option_name');

	$check = isset($options['google_analytics_matomo_subdomains']); ?>

<label for="siteseo_google_analytics_matomo_subdomains">
	<input id="siteseo_google_analytics_matomo_subdomains"
		name="siteseo_google_analytics_option_name[google_analytics_matomo_subdomains]" type="checkbox"
		<?php if (' 1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Tracking one domain and its subdomains in the same website', 'siteseo'); ?>
</label>

<p class="description">
	<?php esc_html_e('If one visitor visits x.example.com and y.example.com, they will be counted as a unique visitor.', 'siteseo'); ?>
</p>

<?php
}

function siteseo_google_analytics_matomo_site_domain_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_matomo_site_domain']); ?>

<label for="siteseo_google_analytics_matomo_site_domain">
	<input id="siteseo_google_analytics_matomo_site_domain"
		name="siteseo_google_analytics_option_name[google_analytics_matomo_site_domain]" type="checkbox"
		<?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Prepend the site domain to the page title when tracking', 'siteseo'); ?>
</label>
<p class="description">
	<?php esc_html_e('If someone visits the \'About\' page on blog.example.com it will be recorded as \'blog / About\'. This is the easiest way to get an overview of your traffic by sub-domain.', 'siteseo'); ?>
</p>

<?php
}

function siteseo_google_analytics_matomo_no_js_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_matomo_no_js']); ?>

<label for="siteseo_google_analytics_matomo_no_js">
	<input id="siteseo_google_analytics_matomo_no_js"
		name="siteseo_google_analytics_option_name[google_analytics_matomo_no_js]" type="checkbox" <?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Track users with JavaScript disabled', 'siteseo'); ?>
</label>

<?php
}

function siteseo_google_analytics_matomo_cross_domain_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_matomo_cross_domain']); ?>

<label for="siteseo_google_analytics_matomo_cross_domain">
	<input id="siteseo_google_analytics_matomo_cross_domain"
		name="siteseo_google_analytics_option_name[google_analytics_matomo_cross_domain]" type="checkbox"
		<?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Enables cross domain linking', 'siteseo'); ?>
</label>

<p class="description">
	<?php esc_html_e('By default, the visitor ID that identifies a unique visitor is stored in the browser\'s first party cookies which can only be accessed by pages on the same domain.', 'siteseo'); ?>
</p>
<p class="description">
	<?php esc_html_e('Enabling cross domain linking lets you track all the actions and pageviews of a specific visitor into the same visit even when they view pages on several domains.', 'siteseo'); ?>
</p>
<p class="description">
	<?php esc_html_e('Whenever a user clicks on a link to one of your website\'s alias URLs, it will append a URL parameter pk_vid forwarding the Visitor ID.', 'siteseo'); ?>
</p>

<?php
}

function siteseo_google_analytics_matomo_cross_domain_sites_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_matomo_cross_domain_sites']) ? $options['google_analytics_matomo_cross_domain_sites'] : null;

	printf('<input type="text" name="siteseo_google_analytics_option_name[google_analytics_matomo_cross_domain_sites]" placeholder="'
			. esc_html__('Enter your domains: siteseo.io,sub.siteseo.io,sub2.siteseo.io', 'siteseo')
			. '" value="%s" aria-label="' . esc_html__('Cross domains', 'siteseo') . '"/>', esc_html($check));
}

function siteseo_google_analytics_matomo_dnt_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_matomo_dnt']); ?>

<label for="siteseo_google_analytics_matomo_dnt">
	<input id="siteseo_google_analytics_matomo_dnt"
		name="siteseo_google_analytics_option_name[google_analytics_matomo_dnt]" type="checkbox" <?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Enable client side DoNotTrack detection', 'siteseo'); ?>
</label>

<p class="description">
	<?php esc_html_e('Tracking requests will not be sent if visitors do not wish to be tracked.', 'siteseo'); ?>
</p>

<?php
}

function siteseo_google_analytics_matomo_no_cookies_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_matomo_no_cookies']); ?>

<label for="siteseo_google_analytics_matomo_no_cookies">
	<input id="siteseo_google_analytics_matomo_no_cookies"
		name="siteseo_google_analytics_option_name[google_analytics_matomo_no_cookies]" type="checkbox"
		<?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Disables all first party cookies. Existing Matomo cookies for this website will be deleted on the next page view.', 'siteseo'); ?>
</label>

<?php
}

function siteseo_google_analytics_matomo_link_tracking_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_matomo_link_tracking']); ?>

<label for="siteseo_google_analytics_matomo_link_tracking">
	<input id="siteseo_google_analytics_matomo_link_tracking"
		name="siteseo_google_analytics_option_name[google_analytics_matomo_link_tracking]" type="checkbox"
		<?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Enabling Download & Outlink tracking', 'siteseo'); ?>
</label>

<p class="description">
	<?php esc_html_e('By default, any file ending with one of these extensions will be considered a "download" in the Matomo interface:','siteseo'); ?><br>
</p>

<pre>7z|aac|arc|arj|apk|asf|asx|avi|bin|bz|bz2|csv|deb|dmg|doc|exe|flv|gif|gz|gzip|hqx|jar|jpg|jpeg|js|mp2|mp3|mp4|mpg|mpeg|mov|movie|msi|msp|odb|odf|odg|odp|ods|odt|ogg|ogv| pdf|phps|png|ppt|qt|qtm|ra|ram|rar|rpm|sea|sit|tar|tbz|tbz2|tgz|torrent|txt|wav|wma|wmv|wpd|xls|xml|z|zip</pre>

<?php
}

function siteseo_google_analytics_matomo_no_heatmaps_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_matomo_no_heatmaps']); ?>

<label for="siteseo_google_analytics_matomo_no_heatmaps">
	<input id="siteseo_google_analytics_matomo_no_heatmaps"
		name="siteseo_google_analytics_option_name[google_analytics_matomo_no_heatmaps]" type="checkbox"
		<?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Disabling all heatmaps and session recordings', 'siteseo'); ?>
</label>

<?php
}

function siteseo_google_analytics_clarity_enable_callback() {
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_clarity_enable']); ?>


<label for="siteseo_google_analytics_clarity_enable">
	<input id="siteseo_google_analytics_clarity_enable"
		name="siteseo_google_analytics_option_name[google_analytics_clarity_enable]" type="checkbox" <?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Add Microsoft Clarity code to your site', 'siteseo'); ?>
</label>

<?php
}

function siteseo_google_analytics_clarity_project_id_callback() {
	$docs = siteseo_get_docs_links();
	$options = get_option('siteseo_google_analytics_option_name');
	$check = isset($options['google_analytics_clarity_project_id']) ? $options['google_analytics_clarity_project_id'] : null;

	printf(
'<input type="text" name="siteseo_google_analytics_option_name[google_analytics_clarity_project_id]" placeholder="' . esc_html__('Enter your Project ID', 'siteseo') . '" aria-label="' . esc_html__('Enter your Project ID', 'siteseo') . '" value="%s"/>',
esc_html($check)
); ?>

<p class="siteseo-help description">
	<span class="dashicons dashicons-external"></span>
	<a href="<?php echo esc_url($docs['analytics']['clarity']['project']); ?>" target="_blank">
		<?php esc_html_e('Find your project ID', 'siteseo'); ?>
	</a>
</p>
<?php
}

function siteseo_print_section_info_google_analytics_enable(){
	?>

<div class="siteseo-sub-tabs">
	<a class="siteseo-active-sub-tabs" href="#siteseo-analytics-general"><?php esc_html_e('General', 'siteseo'); ?></a>
	<a href="#siteseo-analytics-tracking"><?php esc_html_e('Tracking', 'siteseo'); ?></a>
	<a href="#siteseo-analytics-events"><?php esc_html_e('Events', 'siteseo'); ?></a>
	<?php do_action('siteseo_analytics_settings_section'); ?>
</div>
<div class="siteseo-section-body">
	<div class="siteseo-section-header">
		<h2>
			<?php esc_html_e('Google Analytics', 'siteseo'); ?>

		</h2>
	</div>
	<p>
		<?php esc_html_e('Link your Google Analytics to your website. The tracking code will be automatically added to your site.', 'siteseo'); ?>
	</p>
	<hr>
	<h3 id="siteseo-analytics-general"><?php esc_html_e('General','siteseo'); ?></h3>

<?php
}

function siteseo_print_section_info_google_analytics_gdpr()
{
	?>
<div class="siteseo-section-header">
	<h2>
		<?php esc_html_e('Cookie bar / GDPR', 'siteseo'); ?>
	</h2>
</div>
<p>
	<?php esc_html_e('Manage user consent for GDPR and customize your cookie bar easily.', 'siteseo'); ?>
</p>

<p>
	<?php echo wp_kses_post(__('Works with <strong>Google Analytics</strong> and <strong>Matomo</strong>.', 'siteseo')); ?>
</p>

<?php
}

function siteseo_print_section_info_google_analytics_features()
{ ?>

<hr>
<h3 id="siteseo-analytics-tracking">
	<?php esc_html_e('Tracking', 'siteseo'); ?>
</h3>

<p>
	<?php esc_html_e('Configure your Google Analytics tracking code.', 'siteseo'); ?>
</p>

<?php
}

function siteseo_print_section_info_google_analytics_custom_tracking()
{
	?>
<div class="siteseo-section-header">
	<h2>
		<?php esc_html_e('Custom Tracking', 'siteseo'); ?>
	</h2>
</div>
<p>
	<?php echo wp_kses_post(__('Add your own scripts like GTM or Facebook Pixel by copy and paste the provided code to the HEAD/BODY or FOOTER.', 'siteseo')); ?>
</p>

<div class="siteseo-notice is-warning">
	<span class="dashicons dashicons-warning"></span>
	<p>
		<?php echo wp_kses_post(__('<strong>GA4 or Universal Analytics</strong> codes are <strong>automatically added to your source code</strong> if you have enter your <strong>Measurement ID and / or your Universal Analytics ID</strong> from <strong>General</strong> tab.', 'siteseo')); ?>
	</p>
</div>
<?php
}

function siteseo_print_section_info_google_analytics_events()
{
$docs = siteseo_get_docs_links();
?>
<hr>
<h3 id="siteseo-analytics-events">
	<?php esc_html_e('Events', 'siteseo'); ?>
</h3>
<p>
	<?php esc_html_e('Track events in Google Analytics.', 'siteseo'); ?>
</p>

<p class="siteseo-help description">
	<span class="dashicons dashicons-external"></span>
	<a href="<?php echo esc_url($docs['analytics']['events']); ?>" target="_blank">
		<?php esc_html_e('Learn how to track events with Google Analytics','siteseo'); ?>
	</a>
</p>
<?php
}

function siteseo_print_section_info_google_analytics_custom_dimensions(){
	$docs = siteseo_get_docs_links(); ?>
<div class="siteseo-sub-tabs">
	<a class="siteseo-active-sub-tabs" href="#siteseo-analytics-cd"><?php esc_html_e('Custom Dimensions', 'siteseo'); ?></a>
	<a href="#siteseo-analytics-misc"><?php esc_html_e('Misc', 'siteseo'); ?></a>
</div>
<div class="siteseo-section-body">
	<div class="siteseo-section-header">
		<h2>
			<?php esc_html_e('Advanced settings', 'siteseo'); ?>
		</h2>
	</div>
	<div class="siteseo-notice">
		<span class="dashicons dashicons-info"></span>
		<p>
			<?php echo wp_kses_post(__('All advanced settings work with <strong>Google Analytics</strong> and <strong>Matomo</strong> tracking code.', 'siteseo')); ?>
		</p>
	</div>
	<hr>
	<h3 id="siteseo-analytics-cd"><?php esc_html_e('Custom Dimensions','siteseo'); ?></h3>

<p>
	<?php esc_html_e('Configure your Google Analytics custom dimensions.', 'siteseo'); ?>
</p>
<p>
	<?php esc_html_e('Custom dimensions and custom metrics are like the default dimensions and metrics in your Analytics account, except you create them yourself.', 'siteseo'); ?>
</p>
<p>
	<?php esc_html_e('Use them to collect and analyze data that Analytics doesn\'t automatically track.', 'siteseo'); ?>
</p>
<p>
	<?php esc_html_e('Please note that you also have to setup your custom dimensions in your Google Analytics account. More info by clicking on the help icon.', 'siteseo'); ?>
</p>
<?php
}

function siteseo_print_section_info_google_analytics_advanced()
{
?>
<br>
<hr>
<h3 id="siteseo-analytics-misc"><?php esc_html_e('Misc','siteseo'); ?></h3>

<?php
}

function siteseo_print_section_info_google_analytics_matomo(){
	?>
<div class="siteseo-sub-tabs">
	<a class="siteseo-active-sub-tabs" href="#siteseo-matomo-tracking"><?php esc_html_e('Tracking', 'siteseo'); ?></a>
	<?php do_action('siteseo_matomo_settings_section'); ?>
</div>
<div class="siteseo-section-body">
	<div class="siteseo-section-header">
		<h2>
			<?php esc_html_e('Matomo', 'siteseo'); ?>
		</h2>
	</div>
	<p>
		<?php esc_html_e('Use Matomo to track your users with privacy in mind. We support both On Premise and Cloud installations.', 'siteseo'); ?>
	</p>
	<hr>
	<h3 id="siteseo-matomo-tracking">
		<?php esc_html_e('Tracking', 'siteseo'); ?>
	</h3>

	<div class="siteseo-notice">
		<span class="dashicons dashicons-info"></span>
		<p>
			<?php echo wp_kses_post(__('Your <strong>Custom Dimensions</strong> will also work with Matomo tracking code.', 'siteseo')); ?>
		</p>
	</div>

<?php
}

function siteseo_print_section_info_google_analytics_clarity()
{
	?>
<div class="siteseo-section-header">
	<h2>
		<?php esc_html_e('Microsoft Clarity', 'siteseo'); ?>
	</h2>
</div>
<p>
	<?php esc_html_e('Use Microsoft Clarity to capture session recordings, get instant heatmaps and powerful Insights for Free. Know how people interact with your site to improve user experience and conversions.', 'siteseo'); ?>
</p>

<div class="siteseo-notice">
	<span class="dashicons dashicons-info"></span>
	<p>
		<?php printf(wp_kses_post(__('Create your first Microsoft Clarity project <a href="%s" target="_blank">here</a>.', 'siteseo')), esc_url('https://clarity.microsoft.com/')); ?>
	</p>
</div>

<?php
}

//Google Analytics Enable SECTION==========================================================
add_settings_section(
	'siteseo_setting_section_google_analytics_enable', // ID
	'',
	//__("Google Analytics","siteseo"), // Title
	'siteseo_print_section_info_google_analytics_enable', // Callback
	'siteseo-settings-admin-google-analytics-enable' // Page
);

add_settings_field(
	'siteseo_google_analytics_enable', // ID
	__('Enable Google Analytics tracking', 'siteseo'), // Title
	'siteseo_google_analytics_enable_callback', // Callback
	'siteseo-settings-admin-google-analytics-enable', // Page
	'siteseo_setting_section_google_analytics_enable' // Section
);

add_settings_field(
	'siteseo_google_analytics_ga4', // ID
	__('Enter your measurement ID (GA4)', 'siteseo'), // Title
	'siteseo_google_analytics_ga4_callback', // Callback
	'siteseo-settings-admin-google-analytics-enable', // Page
	'siteseo_setting_section_google_analytics_enable' // Section
);

//Cookie bar / GDPR SECTION================================================================
add_settings_section(
	'siteseo_setting_section_google_analytics_gdpr', // ID
	'',
	//__("Google Analytics","siteseo"), // Title
	'siteseo_print_section_info_google_analytics_gdpr', // Callback
	'siteseo-settings-admin-google-analytics-gdpr' // Page
);

add_settings_field(
	'siteseo_google_analytics_hook', // ID
	__('Where to load the cookie bar?', 'siteseo'), // Title
	'siteseo_google_analytics_hook_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_disable', // ID
	__('Analytics tracking opt-in', 'siteseo'), // Title
	'siteseo_google_analytics_disable_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_half_disable', // ID
	'', // Title
	'siteseo_google_analytics_half_disable_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_opt_out_edit_choice', // ID
	__('Allow user to change its choice', 'siteseo'), // Title
	'siteseo_google_analytics_opt_out_edit_choice_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_opt_out_msg', // ID
	__('Consent message for user tracking', 'siteseo'), // Title
	'siteseo_google_analytics_opt_out_msg_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_opt_out_msg_ok', // ID
	__('Accept button for user tracking', 'siteseo'), // Title
	'siteseo_google_analytics_opt_out_msg_ok_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_opt_out_msg_close', // ID
	__('Close button', 'siteseo'), // Title
	'siteseo_google_analytics_opt_out_msg_close_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_opt_out_msg_edit', // ID
	__('Edit cookies button', 'siteseo'), // Title
	'siteseo_google_analytics_opt_out_msg_edit_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_exp_date', // ID
	__('User consent cookie expiration date', 'siteseo'), // Title
	'siteseo_google_analytics_cb_exp_date_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_pos', // ID
	__('Cookie bar position', 'siteseo'), // Title
	'siteseo_google_analytics_cb_pos_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_txt_align', // ID
	__('Text alignment', 'siteseo'), // Title
	'siteseo_google_analytics_cb_txt_align_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_width', // ID
	__('Cookie bar width', 'siteseo'), // Title
	'siteseo_google_analytics_cb_width_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_backdrop', // ID
	'', // Title
	'siteseo_google_analytics_cb_backdrop_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_backdrop_bg', // ID
	'', // Title
	'siteseo_google_analytics_cb_backdrop_bg_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_bg', // ID
	'', // Title
	'siteseo_google_analytics_cb_bg_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_txt_col', // ID
	'', // Title
	'siteseo_google_analytics_cb_txt_col_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_lk_col', // ID
	'', // Title
	'siteseo_google_analytics_cb_lk_col_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_btn_bg', // ID
	'', // Title
	'siteseo_google_analytics_cb_btn_bg_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_btn_bg_hov', // ID
	'', // Title
	'siteseo_google_analytics_cb_btn_bg_hov_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_btn_col', // ID
	'', // Title
	'siteseo_google_analytics_cb_btn_col_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_btn_col_hov', // ID
	'', // Title
	'siteseo_google_analytics_cb_btn_col_hov_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_btn_sec_bg', // ID
	'', // Title
	'siteseo_google_analytics_cb_btn_sec_bg_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_btn_sec_col', // ID
	'', // Title
	'siteseo_google_analytics_cb_btn_sec_col_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_btn_sec_bg_hov', // ID
	'', // Title
	'siteseo_google_analytics_cb_btn_sec_bg_hov_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

add_settings_field(
	'siteseo_google_analytics_cb_btn_sec_col_hov', // ID
	'', // Title
	'siteseo_google_analytics_cb_btn_sec_col_hov_callback', // Callback
	'siteseo-settings-admin-google-analytics-gdpr', // Page
	'siteseo_setting_section_google_analytics_gdpr' // Section
);

//Google Analytics Tracking SECTION========================================================

add_settings_section(
	'siteseo_setting_section_google_analytics_features', // ID
	'',
	//__("Google Analytics","siteseo"), // Title
	'siteseo_print_section_info_google_analytics_features', // Callback
	'siteseo-settings-admin-google-analytics-features' // Page
);

add_settings_field(
	'siteseo_google_analytics_optimize', // ID
	__('Enable Google Optimize', 'siteseo'), // Title
	'siteseo_google_analytics_optimize_callback', // Callback
	'siteseo-settings-admin-google-analytics-features', // Page
	'siteseo_setting_section_google_analytics_features' // Section
);

add_settings_field(
	'siteseo_google_analytics_ads', // ID
	__('Enable Google Ads', 'siteseo'), // Title
	'siteseo_google_analytics_ads_callback', // Callback
	'siteseo-settings-admin-google-analytics-features', // Page
	'siteseo_setting_section_google_analytics_features' // Section
);

add_settings_field(
	'siteseo_google_analytics_remarketing', // ID
	__('Enable remarketing, demographics, and interests reporting', 'siteseo'), // Title
	'siteseo_google_analytics_remarketing_callback', // Callback
	'siteseo-settings-admin-google-analytics-features', // Page
	'siteseo_setting_section_google_analytics_features' // Section
);

add_settings_field(
	'siteseo_google_analytics_ip_anonymization', // ID
	__('Enable IP Anonymization', 'siteseo'), // Title
	'siteseo_google_analytics_ip_anonymization_callback', // Callback
	'siteseo-settings-admin-google-analytics-features', // Page
	'siteseo_setting_section_google_analytics_features' // Section
);

add_settings_field(
	'siteseo_google_analytics_link_attribution', // ID
	__('Enhanced Link Attribution', 'siteseo'), // Title
	'siteseo_google_analytics_link_attribution_callback', // Callback
	'siteseo-settings-admin-google-analytics-features', // Page
	'siteseo_setting_section_google_analytics_features' // Section
);

add_settings_field(
	'siteseo_google_analytics_cross_domain_enable', // ID
	__('Enable cross-domain tracking', 'siteseo'), // Title
	'siteseo_google_analytics_cross_enable_callback', // Callback
	'siteseo-settings-admin-google-analytics-features', // Page
	'siteseo_setting_section_google_analytics_features' // Section
);

add_settings_field(
	'siteseo_google_analytics_cross_domain', // ID
	__('Cross domains', 'siteseo'), // Title
	'siteseo_google_analytics_cross_domain_callback', // Callback
	'siteseo-settings-admin-google-analytics-features', // Page
	'siteseo_setting_section_google_analytics_features' // Section
);

//Google Analytics Custom Tracking SECTION=========================================================

add_settings_section(
	'siteseo_setting_section_google_analytics_custom_tracking', // ID
	'',
	//__("Google Analytics","siteseo"), // Title
	'siteseo_print_section_info_google_analytics_custom_tracking', // Callback
	'siteseo-settings-admin-google-analytics-custom-tracking' // Page
);

add_settings_field(
	'siteseo_google_analytics_other_tracking', // ID
	__('[HEAD] Add an additional tracking code (like Facebook Pixel, Hotjar...)', 'siteseo'), // Title
	'siteseo_google_analytics_other_tracking_callback', // Callback
	'siteseo-settings-admin-google-analytics-custom-tracking', // Page
	'siteseo_setting_section_google_analytics_custom_tracking' // Section
);

add_settings_field(
	'siteseo_google_analytics_other_tracking_body', // ID
	__('[BODY] Add an additional tracking code (like Google Tag Manager...)', 'siteseo'), // Title
	'siteseo_google_analytics_other_tracking_body_callback', // Callback
	'siteseo-settings-admin-google-analytics-custom-tracking', // Page
	'siteseo_setting_section_google_analytics_custom_tracking' // Section
);

add_settings_field(
	'siteseo_google_analytics_other_tracking_footer', // ID
	__('[BODY (FOOTER)] Add an additional tracking code (like Google Tag Manager...)', 'siteseo'), // Title
	'siteseo_google_analytics_other_tracking_footer_callback', // Callback
	'siteseo-settings-admin-google-analytics-custom-tracking', // Page
	'siteseo_setting_section_google_analytics_custom_tracking' // Section
);

//Google Analytics Events SECTION==========================================================

add_settings_section(
	'siteseo_setting_section_google_analytics_events', // ID
	'',
	//__("Google Analytics","siteseo"), // Title
	'siteseo_print_section_info_google_analytics_events', // Callback
	'siteseo-settings-admin-google-analytics-events' // Page
);

add_settings_field(
	'siteseo_google_analytics_link_tracking_enable', // ID
	__('Enable external links tracking', 'siteseo'), // Title
	'siteseo_google_analytics_link_tracking_enable_callback', // Callback
	'siteseo-settings-admin-google-analytics-events', // Page
	'siteseo_setting_section_google_analytics_events' // Section
);

add_settings_field(
	'siteseo_google_analytics_download_tracking_enable', // ID
	__('Enable downloads tracking (eg: PDF, XLSX, DOCX...)', 'siteseo'), // Title
	'siteseo_google_analytics_download_tracking_enable_callback', // Callback
	'siteseo-settings-admin-google-analytics-events', // Page
	'siteseo_setting_section_google_analytics_events' // Section
);

add_settings_field(
	'siteseo_google_analytics_download_tracking', // ID
	__("Track downloads' clicks", 'siteseo'), // Title
	'siteseo_google_analytics_download_tracking_callback', // Callback
	'siteseo-settings-admin-google-analytics-events', // Page
	'siteseo_setting_section_google_analytics_events' // Section
);

add_settings_field(
	'siteseo_google_analytics_affiliate_tracking_enable', // ID
	__('Enable affiliate/outbound links tracking (eg: aff, go, out, recommends)', 'siteseo'), // Title
	'siteseo_google_analytics_affiliate_tracking_enable_callback', // Callback
	'siteseo-settings-admin-google-analytics-events', // Page
	'siteseo_setting_section_google_analytics_events' // Section
);

add_settings_field(
	'siteseo_google_analytics_affiliate_tracking', // ID
	__('Track affiliate/outbound links', 'siteseo'), // Title
	'siteseo_google_analytics_affiliate_tracking_callback', // Callback
	'siteseo-settings-admin-google-analytics-events', // Page
	'siteseo_setting_section_google_analytics_events' // Section
);

add_settings_field(
	'siteseo_google_analytics_phone_tracking', // ID
	__('Track phone links', 'siteseo'), // Title
	'siteseo_google_analytics_phone_tracking_callback', // Callback
	'siteseo-settings-admin-google-analytics-events', // Page
	'siteseo_setting_section_google_analytics_events' // Section
);

//Google Analytics Custom Dimensions SECTION===============================================

add_settings_section(
	'siteseo_setting_section_google_analytics_custom_dimensions', // ID
	'',
	//__("Google Analytics","siteseo"), // Title
	'siteseo_print_section_info_google_analytics_custom_dimensions', // Callback
	'siteseo-settings-admin-google-analytics-custom-dimensions' // Page
);

add_settings_field(
	'siteseo_google_analytics_cd_author', // ID
	__('Track Authors', 'siteseo'), // Title
	'siteseo_google_analytics_cd_author_callback', // Callback
	'siteseo-settings-admin-google-analytics-custom-dimensions', // Page
	'siteseo_setting_section_google_analytics_custom_dimensions' // Section
);

add_settings_field(
	'siteseo_google_analytics_cd_category', // ID
	__('Track Categories', 'siteseo'), // Title
	'siteseo_google_analytics_cd_category_callback', // Callback
	'siteseo-settings-admin-google-analytics-custom-dimensions', // Page
	'siteseo_setting_section_google_analytics_custom_dimensions' // Section
);

add_settings_field(
	'siteseo_google_analytics_cd_tag', // ID
	__('Track Tags', 'siteseo'), // Title
	'siteseo_google_analytics_cd_tag_callback', // Callback
	'siteseo-settings-admin-google-analytics-custom-dimensions', // Page
	'siteseo_setting_section_google_analytics_custom_dimensions' // Section
);

add_settings_field(
	'siteseo_google_analytics_cd_post_type', // ID
	__('Track Post Types', 'siteseo'), // Title
	'siteseo_google_analytics_cd_post_type_callback', // Callback
	'siteseo-settings-admin-google-analytics-custom-dimensions', // Page
	'siteseo_setting_section_google_analytics_custom_dimensions' // Section
);

add_settings_field(
	'siteseo_google_analytics_cd_logged_in_user', // ID
	__('Track Logged In Users', 'siteseo'), // Title
	'siteseo_google_analytics_cd_logged_in_user_callback', // Callback
	'siteseo-settings-admin-google-analytics-custom-dimensions', // Page
	'siteseo_setting_section_google_analytics_custom_dimensions' // Section
);

//Google Analytics Advanced SECTION================================================================

add_settings_section(
	'siteseo_setting_section_google_analytics_advanced', // ID
	'',
	//__("Advanced","siteseo"), // Title
	'siteseo_print_section_info_google_analytics_advanced', // Callback
	'siteseo-settings-admin-google-analytics-advanced' // Page
);

add_settings_field(
	'siteseo_google_analytics_roles', // ID
	__('Exclude user roles from tracking (Google Analytics and Matomo)', 'siteseo'), // Title
	'siteseo_google_analytics_roles_callback', // Callback
	'siteseo-settings-admin-google-analytics-advanced', // Page
	'siteseo_setting_section_google_analytics_advanced' // Section
);

//Matomo SECTION===========================================================================
add_settings_section(
	'siteseo_setting_section_google_analytics_matomo', // ID
	'',
	//__("Google Analytics","siteseo"), // Title
	'siteseo_print_section_info_google_analytics_matomo', // Callback
	'siteseo-settings-admin-google-analytics-matomo' // Page
);

add_settings_field(
	'siteseo_google_analytics_matomo_enable', // ID
	__('Enable Matomo tracking', 'siteseo'), // Title
	'siteseo_google_analytics_matomo_enable_callback', // Callback
	'siteseo-settings-admin-google-analytics-matomo', // Page
	'siteseo_setting_section_google_analytics_matomo' // Section
);

add_settings_field(
	'siteseo_google_analytics_matomo_self_hosted', // ID
	__('Self hosted Matomo installation', 'siteseo'), // Title
	'siteseo_google_analytics_matomo_self_hosted_callback', // Callback
	'siteseo-settings-admin-google-analytics-matomo', // Page
	'siteseo_setting_section_google_analytics_matomo' // Section
);

add_settings_field(
	'siteseo_google_analytics_matomo_id', // ID
	__('Enter your tracking ID', 'siteseo'), // Title
	'siteseo_google_analytics_matomo_id_callback', // Callback
	'siteseo-settings-admin-google-analytics-matomo', // Page
	'siteseo_setting_section_google_analytics_matomo' // Section
);

add_settings_field(
	'siteseo_google_analytics_matomo_site_id', // ID
	__('Enter your site ID', 'siteseo'), // Title
	'siteseo_google_analytics_matomo_site_id_callback', // Callback
	'siteseo-settings-admin-google-analytics-matomo', // Page
	'siteseo_setting_section_google_analytics_matomo' // Section
);

add_settings_field(
	'siteseo_google_analytics_matomo_subdomains', // ID
	__('Track visitors across all subdomains', 'siteseo'), // Title
	'siteseo_google_analytics_matomo_subdomains_callback', // Callback
	'siteseo-settings-admin-google-analytics-matomo', // Page
	'siteseo_setting_section_google_analytics_matomo' // Section
);

add_settings_field(
	'siteseo_google_analytics_matomo_site_domain', // ID
	__('Prepend the site domain', 'siteseo'), // Title
	'siteseo_google_analytics_matomo_site_domain_callback', // Callback
	'siteseo-settings-admin-google-analytics-matomo', // Page
	'siteseo_setting_section_google_analytics_matomo' // Section
);

add_settings_field(
	'siteseo_google_analytics_matomo_no_js', // ID
	__('Track users with JavaScript disabled', 'siteseo'), // Title
	'siteseo_google_analytics_matomo_no_js_callback', // Callback
	'siteseo-settings-admin-google-analytics-matomo', // Page
	'siteseo_setting_section_google_analytics_matomo' // Section
);

add_settings_field(
	'siteseo_google_analytics_matomo_cross_domain', // ID
	__('Enables cross domain linking', 'siteseo'), // Title
	'siteseo_google_analytics_matomo_cross_domain_callback', // Callback
	'siteseo-settings-admin-google-analytics-matomo', // Page
	'siteseo_setting_section_google_analytics_matomo' // Section
);

add_settings_field(
	'siteseo_google_analytics_matomo_cross_domain_sites', // ID
	__('Cross domain', 'siteseo'), // Title
	'siteseo_google_analytics_matomo_cross_domain_sites_callback', // Callback
	'siteseo-settings-admin-google-analytics-matomo', // Page
	'siteseo_setting_section_google_analytics_matomo' // Section
);
add_settings_field(
	'siteseo_google_analytics_matomo_dnt', // ID
	__('Enable DoNotTrack detection', 'siteseo'), // Title
	'siteseo_google_analytics_matomo_dnt_callback', // Callback
	'siteseo-settings-admin-google-analytics-matomo', // Page
	'siteseo_setting_section_google_analytics_matomo' // Section
);

add_settings_field(
	'siteseo_google_analytics_matomo_no_cookies', // ID
	__('Disable all tracking cookies', 'siteseo'), // Title
	'siteseo_google_analytics_matomo_no_cookies_callback', // Callback
	'siteseo-settings-admin-google-analytics-matomo', // Page
	'siteseo_setting_section_google_analytics_matomo' // Section
);

add_settings_field(
	'siteseo_google_analytics_matomo_link_tracking', // ID
	__('Download & Outlink tracking', 'siteseo'), // Title
	'siteseo_google_analytics_matomo_link_tracking_callback', // Callback
	'siteseo-settings-admin-google-analytics-matomo', // Page
	'siteseo_setting_section_google_analytics_matomo' // Section
);

add_settings_field(
	'siteseo_google_analytics_matomo_no_heatmaps', // ID
	__('Disable all heatmaps and session recordings', 'siteseo'), // Title
	'siteseo_google_analytics_matomo_no_heatmaps_callback', // Callback
	'siteseo-settings-admin-google-analytics-matomo', // Page
	'siteseo_setting_section_google_analytics_matomo' // Section
);

//Microsoft Clarity SECTION========================================================================
add_settings_section(
	'siteseo_setting_section_google_analytics_clarity', // ID
	'',
	//__("Microsoft Clarity","siteseo"), // Title
	'siteseo_print_section_info_google_analytics_clarity', // Callback
	'siteseo-settings-admin-google-analytics-clarity' // Page
);

add_settings_field(
	'siteseo_google_analytics_clarity_enable', // ID
	__('Enable Microsoft Clarity', 'siteseo'), // Title
	'siteseo_google_analytics_clarity_enable_callback', // Callback
	'siteseo-settings-admin-google-analytics-clarity', // Page
	'siteseo_setting_section_google_analytics_clarity' // Section
);

add_settings_field(
	'siteseo_google_analytics_clarity_project_id', // ID
	__('Enter your Clarity project ID', 'siteseo'), // Title
	'siteseo_google_analytics_clarity_project_id_callback', // Callback
	'siteseo-settings-admin-google-analytics-clarity', // Page
	'siteseo_setting_section_google_analytics_clarity' // Section
);



$this->options = get_option('siteseo_google_analytics_option_name');
if (function_exists('siteseo_admin_header')) {
	siteseo_admin_header();
} ?>
<form method="post"
	action="<?php echo esc_url(admin_url('options.php')); ?>"
	class="siteseo-option">
	<?php
settings_fields('siteseo_google_analytics_option_group'); ?>

	<div id="siteseo-tabs" class="wrap">
	<?php
	echo wp_kses($this->siteseo_feature_title('google-analytics'), ['h1' => true, 'input' => ['type' => true, 'name' => true, 'id' => true, 'class' => true, 'data-*' => true], 'label' => ['for' => true], 'span' => ['id' => true, 'class' => true], 'div' => ['id' => true, 'class' => true]]);
	$current_tab = '';

	$plugin_settings_tabs = [
		'tab_siteseo_google_analytics_enable'			  => esc_html__('Google Analytics', 'siteseo'),
		'tab_siteseo_google_analytics_matomo'			  => esc_html__('Matomo', 'siteseo'),
		'tab_siteseo_google_analytics_clarity'			 => esc_html__('Clarity', 'siteseo'),
		'tab_siteseo_google_analytics_custom_dimensions'   => esc_html__('Advanced', 'siteseo'),
		'tab_siteseo_google_analytics_gdpr'				=> esc_html__('Cookie bar / GDPR', 'siteseo'),
		'tab_siteseo_google_analytics_custom_tracking'	 => esc_html__('Custom Tracking', 'siteseo'),
	];

echo '<div class="nav-tab-wrapper">';
foreach ($plugin_settings_tabs as $tab_key => $tab_caption) {
	echo '<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab" href="?page=siteseo-google-analytics#tab=' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
}
echo '</div>'; ?>
		<div class="siteseo-tab <?php if ('tab_siteseo_google_analytics_enable' == $current_tab) {
		echo 'active';
	} ?>" id="tab_siteseo_google_analytics_enable">
			<?php do_settings_sections('siteseo-settings-admin-google-analytics-enable'); ?>
			<?php do_settings_sections('siteseo-settings-admin-google-analytics-features'); ?>
			<?php do_settings_sections('siteseo-settings-admin-google-analytics-events'); ?>
			<?php if (is_plugin_active('siteseo-pro/siteseo-pro.php')) {
				do_settings_sections('siteseo-settings-admin-google-analytics-ecommerce');
				do_settings_sections('siteseo-settings-admin-google-analytics-dashboard');
			} ?>
			</div>
		</div> <!-- Start div in first sections callback function and END here -->
		<div class="siteseo-tab <?php if ('tab_siteseo_google_analytics_custom_tracking' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_google_analytics_custom_tracking"><?php do_settings_sections('siteseo-settings-admin-google-analytics-custom-tracking'); ?>
		</div>
		<div class="siteseo-tab <?php if ('tab_siteseo_google_analytics_custom_dimensions' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_google_analytics_custom_dimensions">
			<?php do_settings_sections('siteseo-settings-admin-google-analytics-custom-dimensions'); ?>
			<?php do_settings_sections('siteseo-settings-admin-google-analytics-advanced'); ?>
			</div><!-- Start div in first sections callback function and END here -->
		</div>
		<div class="siteseo-tab <?php if ('tab_siteseo_google_analytics_gdpr' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_google_analytics_gdpr"><?php do_settings_sections('siteseo-settings-admin-google-analytics-gdpr'); ?>
		</div>
		<div class="siteseo-tab <?php if ('tab_siteseo_google_analytics_matomo' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_google_analytics_matomo"><?php do_settings_sections('siteseo-settings-admin-google-analytics-matomo'); ?>
<?php do_settings_sections('siteseo-settings-admin-google-analytics-matomo-widget'); ?>
			</div><!--- Start div in first sections callback function and END here -->
		</div>
		<div class="siteseo-tab <?php if ('tab_siteseo_google_analytics_clarity' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_google_analytics_clarity"><?php do_settings_sections('siteseo-settings-admin-google-analytics-clarity'); ?>
		</div>
	</div>

	<?php siteseo_submit_button(__('Save changes', 'siteseo')); ?>
</form>
<?php
