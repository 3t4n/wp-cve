<?php
include_once(ABSPATH.'wp-admin/includes/plugin.php');
/**
 * Load last .po and .pot
 *
 * @hook init
 * @return $loaded
 */
function spoki_update_po_file()
{
	$domain = SPOKI_PLUGIN_NAME;
	$locale = apply_filters('plugin_locale', get_locale(), $domain);
	$loaded = load_textdomain($domain, SPOKI_DIR . 'languages/' . $domain . '-' . $locale . '.mo');
	if ($loaded) {
		return $loaded;
	} else {
		return load_plugin_textdomain($domain, false, SPOKI_DIR . 'languages/');
	}
}

/**
 * Check if a string starts with another
 *
 * @param $string
 * @param $startString
 * @return bool
 */
function spoki_starts_with($string, $startString)
{
	$len = strlen($startString);
	return (substr($string, 0, $len) === $startString);
}

/**
 * Get WhatsApp logo svg
 * @param $size
 * @return string
 */
function spoki_get_wa_logo($size = null)
{
	$style = $size ? "height:{$size}px;width:{$size}px;" : "";
	return '
<svg version="1.1" id="Livello_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 682 682" style="enable-background:new 0 0 682 682;' . $style . '" xml:space="preserve">
<style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;fill:#FFFFFF;}</style>
<path class="st0" d="M567.2,113.9C507.3,54,427.8,21,343,21C168.4,21,26.3,163.1,26.2,337.8c0,55.8,14.6,110.3,42.3,158.4
L23.6,660.4l168-44.1c46.3,25.2,98.4,38.5,151.4,38.6h0.1c174.6,0,316.8-142.1,316.8-316.8C659.9,253.4,627,173.8,567.2,113.9z
M343,601.4h-0.1c-47.3,0-93.6-12.7-134-36.7l-9.6-5.7l-99.7,26.1l26.6-97.2l-6.3-10C93.6,436,79.7,387.6,79.7,337.8
C79.8,192.6,197.9,74.5,343.2,74.5c70.3,0,136.5,27.4,186.2,77.2s77.1,115.9,77.1,186.3C606.3,483.2,488.2,601.4,343,601.4z
M487.5,404.1c-7.9-4-46.8-23.1-54.1-25.8c-7.3-2.6-12.5-4-17.8,4c-5.3,7.9-20.4,25.8-25.1,31c-4.6,5.3-9.2,5.9-17.2,2
c-7.9-4-33.4-12.3-63.7-39.3c-23.5-21-39.4-46.9-44-54.8c-4.6-7.9,0-11.8,3.5-16.2c8.6-10.6,17.2-21.8,19.8-27.1
c2.6-5.3,1.3-9.9-0.7-13.9c-2-4-17.8-42.9-24.4-58.8c-6.4-15.4-13-13.3-17.8-13.6c-4.6-0.2-9.9-0.3-15.2-0.3c-5.3,0-13.9,2-21.1,9.9
c-7.3,7.9-27.7,27.1-27.7,66s28.4,76.6,32.3,81.9c4,5.3,55.8,85.2,135.2,119.5c18.9,8.2,33.6,13,45.1,16.7c19,6,36.2,5.2,49.9,3.1
c15.2-2.3,46.8-19.2,53.4-37.6c6.6-18.5,6.6-34.3,4.6-37.6C500.7,410.1,495.4,408.1,487.5,404.1z"/>
</svg>';
}

/**
 * Check if WooCommerce is installed
 * @return bool
 */
function spoki_has_woocommerce(): bool {
	if ( !function_exists( 'is_plugin_active' ) ) {
		return in_array( 'woocommerce/woocommerce.php', (array) get_option( 'active_plugins', array() ) );
	}
	return is_plugin_active( 'woocommerce/woocommerce.php');
}

/**
 * Check if Elementor is installed
 * @return bool
 */
function spoki_has_elementor(): bool {
	if ( !function_exists( 'is_plugin_active' ) ) {
		return in_array( 'elementor/elementor.php', (array) get_option( 'active_plugins', array() ) );
	}
	return is_plugin_active( 'elementor/elementor.php');
}

/**
 * Check if now is not a working day or time
 * @return bool
 */
function spoki_is_non_working_day_time($working_days_times_options): bool {
	$is_working_days_times_enabled = isset($working_days_times_options['enabled']) && $working_days_times_options['enabled'] == 1;
	if (!$is_working_days_times_enabled) {
		return false;
	}

	$current_week_day = current_time('w');
	$is_working_today = isset($working_days_times_options['day_' . $current_week_day]) &&  $working_days_times_options['day_' . $current_week_day] == 1;
	if (!$is_working_today) {
		return true;
	}
	$opening_time = (isset($working_days_times_options['opening_time']) && trim($working_days_times_options['opening_time']) != '') ? $working_days_times_options['opening_time'] : null;
	$closing_time = (isset($working_days_times_options['closing_time']) && trim($working_days_times_options['closing_time']) != '') ? $working_days_times_options['closing_time'] : null;
	if (!$opening_time && !$closing_time) {
		return false;
	}
	if ($opening_time) {
		$is_before_opening = strtotime(current_time('H:i')) <= strtotime($opening_time);
		if ($is_before_opening) {
			return true;
		}
	}
	if ($closing_time) {
		$is_before_closing = strtotime(current_time('H:i')) <= strtotime($closing_time);
		return !$is_before_closing;
	}
	return false;
}
