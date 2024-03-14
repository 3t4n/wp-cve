<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

use SiteSEO\Core\Kernel;

/**
 * Get a service.
 *
 * @since 4.3.0
 *
 * @param string $service
 *
 * @return object
 */
function siteseo_get_service($service) {
	return Kernel::getContainer()->getServiceByName($service);
}

/*
 * Get first key of an array if PHP < 7.3
 * @since 4.2.1
 * @return string
 * author Softaculous
 */
function siteseo_array_key_first(array $arr) {
	
	if (function_exists('array_key_first')) {
		return array_key_first($arr);
	}
	
	foreach ($arr as $key => $unused) {
		return $key;
	}

	return null;
}

/*
 * Get last key of an array if PHP < 7.3
 * @since 4.2.1
 * @return string
 * author Softaculous
 */
function siteseo_array_key_last(array $arr) {
	if (function_exists('array_key_last')) {
		return array_key_last($arr);
	}

	end($arr);
	$key = key($arr);

	return $key;
}

/**
 * Get all custom fields (limit: 250).
 *
 * @author Softaculous
 *
 * @return array custom field keys
 */
function siteseo_get_custom_fields() {
	$cf_keys = wp_cache_get('siteseo_get_custom_fields');

	if (false === $cf_keys) {
		global $wpdb;

		$limit   = (int) apply_filters('postmeta_form_limit', 250);
		$cf_keys = $wpdb->get_col($wpdb->prepare("
			SELECT DISTINCT meta_key
			FROM $wpdb->postmeta
			GROUP BY meta_key
			HAVING meta_key NOT LIKE %s
			ORDER BY meta_key
			LIMIT %d", '\_%%', $limit));

		if (is_plugin_active('types/wpcf.php')) {
			$wpcf_fields = get_option('wpcf-fields');

			if ( ! empty($wpcf_fields)) {
				foreach ($wpcf_fields as $key => $value) {
					$cf_keys[] = $value['meta_key'];
				}
			}
		}

		$cf_keys = apply_filters('siteseo_get_custom_fields', $cf_keys);

		if ($cf_keys) {
			natcasesort($cf_keys);
		}
		wp_cache_set('siteseo_get_custom_fields', $cf_keys);
	}

	return $cf_keys;
}

/**
 * Check SSL for schema.org.
 *
 * @author Softaculous
 *
 * @return string correct protocol
 */
function siteseo_check_ssl() {
	if (is_ssl()) {
		return 'https://';
	}

	return 'http://';
}

/**
 * Get IP address.
 *
 * @author Softaculous
 *
 * @return (string) $ip
 **/
function siteseo_get_ip_address() {
	foreach (['HTTP_CLIENT_IP', 'HTTP_CF_CONNECTING_IP', 'HTTP_VIA', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
		if (true === array_key_exists($key, $_SERVER)) {
			foreach (explode(',', sanitize_text_field(wp_unslash($_SERVER[$key]))) as $ip) {
				$ip = trim($ip); // just to be safe

				return apply_filters('siteseo_404_ip', $ip ? $ip : '');
			}
		}
	}
}

/**
 * Disable Query Monitor for CA.
 *
 * @return array
 *
 * author Softaculous
 *
 * @param mixed $url
 * @param mixed $allcaps
 * @param mixed $caps
 * @param mixed $args
 */
function siteseo_disable_qm($allcaps, $caps, $args) {
	$allcaps['view_query_monitor'] = false;

	return $allcaps;
}
/**
 * Clear content for CA.
 *
 * author Softaculous
 */
function siteseo_clean_content_analysis() {
	if (current_user_can('edit_posts')) {
		if (isset($_GET['no_admin_bar']) && '1' === $_GET['no_admin_bar']) {
			//Remove admin bar
			add_filter('show_admin_bar', '__return_false');

			//Disable Query Monitor
			add_filter('user_has_cap', 'siteseo_disable_qm', 10, 3);

			//Disable wptexturize
			add_filter('run_wptexturize', '__return_false');

			//Remove Edit nofollow links from TablePress
			add_filter( 'tablepress_edit_link_below_table', '__return_false');

			//Oxygen compatibility
			if (function_exists('ct_template_output')) {
				add_action('template_redirect', 'siteseo_get_oxygen_content');
			}

			//Allow user to run custom action to clean content
			do_action('siteseo_content_analysis_cleaning');
		}
	}
}
add_action('plugins_loaded', 'siteseo_clean_content_analysis');

/**
 * Test if a URL is in absolute.
 *
 * @return bool true if absolute
 *
 * author Softaculous
 *
 * @param mixed $url
 */
function siteseo_is_absolute($url) {
	$pattern = "%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu";

	return (bool) preg_match($pattern, $url);
}

/**
 * Manage localized links.
 *
 * @return string locale for documentation links
 *
 * author Softaculous
 */
function siteseo_get_locale() {
	switch (get_user_locale(get_current_user_id())) {
		case 'fr_FR':
		case 'fr_BE':
		case 'fr_CA':
		case 'fr_LU':
		case 'fr_MC':
		case 'fr_CH':
			$locale_link = 'fr';
		break;
		default:
			$locale_link = '';
		break;
	}

	return $locale_link;
}

/**
 * Check empty global title template.
 *
 * @since 5.0
 *
 * @param string $type
 * @param string $metadata
 * @param bool   $notice
 *
 * @return string notice with list of empty cpt titles
 *
 * author Softaculous
 */
function siteseo_get_empty_templates($type, $metadata, $notice = true) {
	$cpt_titles_empty = [];
	$templates		= '';
	$data			= '';
	$html			= '';
	$list			= '';

	if ('cpt' === $type) {
		$templates   = $postTypes = siteseo_get_service('WordPressData')->getPostTypes();
		$notice_i18n = __('Custom Post Types', 'siteseo');
	}
	if ('tax' === $type) {
		$templates   = siteseo_get_service('WordPressData')->getTaxonomies();
		$notice_i18n = __('Custom Taxonomies', 'siteseo');
	}
	foreach ($templates as $key => $value) {
		$options			= get_option('siteseo_titles_option_name');

		if (!empty($options)) {
			if ('cpt' === $type) {
				if (!empty($options['titles_single_titles'])) {
					if (!array_key_exists($key, $options['titles_single_titles'])) {
						$cpt_titles_empty[] = $key;
					} else {
						$data = isset($options['titles_single_titles'][$key][$metadata]) ? $options['titles_single_titles'][$key][$metadata] : '';
					}
				}
			}
			if ('tax' === $type) {
				if (!empty($options['titles_tax_titles'])) {
					if (!array_key_exists($key, $options['titles_tax_titles'])) {
						$cpt_titles_empty[] = $key;
					} else {
						$data = isset($options['titles_tax_titles'][$key][$metadata]) ? $options['titles_tax_titles'][$key][$metadata] : '';
					}
				}
			}
		}

		if (empty($data)) {
			$cpt_titles_empty[] = $key;
		}
	}

	if ( ! empty($cpt_titles_empty)) {
		$list .= '<ul>';
		foreach ($cpt_titles_empty as $cpt) {
			$list .= '<li>' . esc_html($cpt) . '</li>';
		}
		$list .= '</ul>';

		if (false === $notice) {
			return $list;
		} else {
			$html .= '<div class="siteseo-notice is-warning">
			<span class="dashicons dashicons-warning"></span>
			<div>
	<p>';
			/* translators: %s: "Custom Post Types" or "Custom Taxonomies" %s: "title" or "description" */
			$html .= sprintf(__('Some <strong>%s</strong> have no <strong>meta %s</strong> set! We strongly encourage you to add one by filling in the fields below.', 'siteseo'), esc_html($notice_i18n), wp_kses_post($metadata));
			$html .= '</p>';
			$html .= $list;
			$html .= '</div>';
			$html .= '</div>';

			return $html;
		}
	}
}

/**
 * Generate Tooltip.
 *
 * @since 3.8.2
 *
 * @param string $tooltip_title, $tooltip_desc, $tooltip_code
 * @param mixed  $tooltip_desc
 * @param mixed  $tooltip_code
 *
 * @return string tooltip title, tooltip description, tooltip url
 *
 * author Softaculous
 */
function siteseo_tooltip($tooltip_title, $tooltip_desc, $tooltip_code) {
	$html =
	'<button type="button" class="siteseo-tooltip"><span class="dashicons dashicons-editor-help"></span>
	<span class="siteseo-tooltiptext" role="tooltip" tabindex="0">
		<span class="siteseo-tooltip-headings">' . $tooltip_title . '</span>
		<span class="siteseo-tooltip-desc">' . $tooltip_desc . '</span>
		<span class="siteseo-tooltip-code">' . $tooltip_code . '</span>
	</span></button>';

	return $html;
}

/**
 * Generate Tooltip (alternative version).
 *
 * @since 3.8.6
 *
 * @param string $tooltip_title, $tooltip_desc, $tooltip_code
 * @param mixed  $tooltip_anchor
 * @param mixed  $tooltip_desc
 *
 * @return string tooltip title, tooltip description, tooltip url
 *
 * author Softaculous
 */
function siteseo_tooltip_alt($tooltip_anchor, $tooltip_desc) {
	$html =
	'<button type="button" class="siteseo-tooltip alt">' . $tooltip_anchor . '
	<span class="siteseo-tooltiptext" role="tooltip" tabindex="0">
		<span class="siteseo-tooltip-desc">' . $tooltip_desc . '</span>
	</span>
	</button>';

	return $html;
}

/**
 * Generate Tooltip link.
 *
 * @since 5.0
 *
 * @param string $tooltip_title, $tooltip_desc, $tooltip_code
 * @param mixed  $tooltip_anchor
 * @param mixed  $tooltip_desc
 *
 * @return string tooltip title, tooltip description, tooltip url
 *
 * author Softaculous
 */
function siteseo_tooltip_link($tooltip_anchor, $tooltip_desc) {
	$html = '<a href="' . esc_url($tooltip_anchor) . '"
	target="_blank" class="siteseo-doc">
	<span class="dashicons dashicons-editor-help"></span>
	<span class="screen-reader-text">
		' . $tooltip_desc . '
	</span>
</a>';

	return $html;
}

/**
 * Remove BOM.
 *
 * @since 3.8.2
 *
 * @param mixed $text
 *
 * @return mixed $text
 *
 * author Softaculous
 */
function siteseo_remove_utf8_bom($text) {
	$bom  = pack('H*', 'EFBBBF');
	$text = preg_replace("/^$bom/", '', $text);

	return $text;
}

/**
 * Generate notification (Notifications Center).
 *
 * @since 3.8.2
 *
 * @param array $args
 *
 * @return string HTML notification
 *
 * author Softaculous
 */
function siteseo_notification($args) {
	if ( ! empty($args)) {
		$id			 = isset($args['id']) ? $args['id'] : null;
		$title		  = isset($args['title']) ? $args['title'] : null;
		$desc		   = isset($args['desc']) ? $args['desc'] : null;
		$impact		 = isset($args['impact']) ? $args['impact'] : [];
		$link		   = isset($args['link']) ? $args['link'] : null;
		$deleteable	 = isset($args['deleteable']) ? $args['deleteable'] : null;
		$icon		   = isset($args['icon']) ? $args['icon'] : null;
		$wrap		   = isset($args['wrap']) ? $args['wrap'] : null;

		$class = '';
		if ( ! empty($impact)) {
			$class .= ' impact';
			$class .= ' ' . key($impact);
		}

		if (true === $deleteable) {
			$class .= ' deleteable';
		}

		echo '<div id="' . esc_attr($id) . '-alert" class="siteseo-alert">';

		if ( ! empty($impact)) {
			echo '<span class="screen-reader-text">' . wp_kses_post(reset($impact)) . '</span>';
		}

		if ( ! empty($icon)) {
			echo '<span class="dashicons ' . esc_attr($icon) . '"></span>';
		} else {
			echo '<span class="dashicons dashicons-info"></span>';
		}

		echo '<div><h3>' . esc_html($title) . '</h3>';

		if (false === $wrap) {
			echo wp_kses_post($desc);
		} else {
			echo '<p>' . wp_kses_post($desc) . '</p>';
		}
		
		echo '</div>';
		
		$href = '';
		if (function_exists('siteseo_get_locale') && 'fr' == siteseo_get_locale() && isset($link['fr'])) {
			$href = $link['fr'];
		} elseif (isset($link['en'])) {
			$href = $link['en'];
		}

		$target = '';
		if (isset($link['external']) && true === $link['external']) {
			$target = '_blank';
		}

		if ( ! empty($link) || true === $deleteable) {
			echo '<p class="siteseo-card-actions">';

			if ( ! empty($link)) {
				echo '<a class="btn btnSecondary" href="'. esc_url($href).'" target="'.esc_attr($target).'">' . esc_html($link['title']) . '</a>';
			}
			if (true === $deleteable) {
				echo '<button id="' . esc_attr($id) . '" name="notice-title-tag" type="button" class="btn btnTertiary" data-notice="' . esc_attr($id) . '">' . esc_html__('Dismiss', 'siteseo') . '</button>';
			}

			echo '</p>';
		}
		echo '</div>';
	}
}
/**
 * Filter the capability to allow other roles to use the plugin.
 *
 * @since 3.8.2
 *
 * @author Softaculous
 *
 * @return string
 *
 * @param mixed $cap
 * @param mixed $context
 */
function siteseo_capability($cap, $context = '') {
	$newcap = apply_filters('siteseo_capability', $cap, $context);
	if ( ! current_user_can($newcap)) {
		return $cap;
	}

	return $newcap;
}

/**
 * Check if the page is one of ours.
 *
 * @since 3.8.2
 *
 * @author Softaculous
 *
 * @return bool
 */
function siteseo_is_valid_page() {
	if ( ! is_admin() && ( ! isset($_REQUEST['page']) || ! isset($_REQUEST['post_type']))) {
		return false;
	}

	if (isset($_REQUEST['page'])) {
		return 0 === strpos(siteseo_opt_req('page'), 'siteseo');
	} elseif (isset($_REQUEST['post_type'])) {
		if (is_array(siteseo_opt_req('post_type')) && !empty($_REQUEST['post_type'])) {
			return 0 === strpos(siteseo_opt_req('post_type')[0], 'siteseo');
		} else {
			return 0 === strpos(siteseo_opt_req('post_type'), 'siteseo');
		}
	}
}

/**
 * Only add our notices on our pages.
 *
 * @since 3.8.2
 *
 * @author Softaculous
 *
 * @return bool
 */
function siteseo_remove_other_notices() {
	if (siteseo_is_valid_page()) {
		remove_all_actions('network_admin_notices');
		remove_all_actions('admin_notices');
		remove_all_actions('user_admin_notices');
		remove_all_actions('all_admin_notices');
		add_action('admin_notices', 'siteseo_admin_notices');
		if (is_plugin_active('siteseo-pro/siteseo-pro.php')) {
			add_action('admin_notices', 'siteseo_pro_admin_notices');
		}
	}
}
add_action('in_admin_header', 'siteseo_remove_other_notices', 1000);//keep this value high to remove other notices

/**
 * We replace the WP action by ours.
 *
 * @since 3.8.2
 *
 * @author Softaculous
 *
 * @return bool
 */
function siteseo_admin_notices() {
	do_action('siteseo_admin_notices');
}

/**
 * Return the 7 days in correct order.
 *
 * @since 3.8.2
 *
 * @author Softaculous
 *
 * @return bool
 */
function siteseo_get_days() {
	$start_of_week = (int) get_option('start_of_week');

	return array_map(
		function () use ($start_of_week) {
			//static $start_of_week;

			return ucfirst(date_i18n('l', strtotime($start_of_week++ - date('w', 0) . ' day', 0)));
		},
		array_combine(
			array_merge(
				array_slice(range(0, 6), $start_of_week, 7),
				array_slice(range(0, 6), 0, $start_of_week)
			),
			range(0, 6)
		)
	);
}

/**
 * Check if a key exists in a multidimensional array.
 *
 * @since 3.8.2
 *
 * @author Softaculous
 *
 * @return bool
 *
 * @param mixed $key
 */
function siteseo_if_key_exists(array $arr, $key) {
	// is in base array?
	if (array_key_exists($key, $arr)) {
		return true;
	}

	// check arrays contained in this array
	foreach ($arr as $element) {
		if (is_array($element)) {
			if (siteseo_if_key_exists($element, $key)) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Get Oxygen Content for version 4.0
 *
 * @since 5.9.0
 *
 * @author Softaculous
 *
 * @return null
 */
function siteseo_get_oygen_content_v4($data, $content = ""){
	if(!is_array($data)){
		return $content;
	}

	if(isset($data['children'])){
		foreach($data['children'] as $child){
			$content = siteseo_get_oygen_content_v4($child, $content);
		}
	}

	if(isset($data['options']['ct_content'])){
		$content .= $data['options']['ct_content'];
	}

	return $content . " ";

}

/**
 * Get Oxygen Content.
 *
 * @since 3.8.5
 *
 * @author Softaculous
 *
 * @return null
 */
function siteseo_get_oxygen_content() {
	if (is_plugin_active('oxygen/functions.php') && function_exists('ct_template_output')) {
		if (!empty(get_post_meta(get_the_ID(), 'ct_builder_json', true))) {
			$oxygen_content = get_post_meta(get_the_ID(), 'ct_builder_json', true);
			$siteseo_get_the_content = siteseo_get_oygen_content_v4(json_decode($oxygen_content, true));
		} else {
			$siteseo_get_the_content = ct_template_output(true); //shortcodes?
		}

		//Get post content
		if ( ! $siteseo_get_the_content) {
			$siteseo_get_the_content = apply_filters('the_content', get_post_field('post_content', get_the_ID()));
			$siteseo_get_the_content = normalize_whitespace(wp_strip_all_tags($siteseo_get_the_content));
		}

		if ($siteseo_get_the_content) {
			//Get Target Keywords
			if (get_post_meta(get_the_ID(), '_siteseo_analysis_target_kw', true)) {
				$siteseo_analysis_target_kw = array_filter(explode(',', strtolower(esc_attr(get_post_meta(get_the_ID(), '_siteseo_analysis_target_kw', true)))));

				$siteseo_analysis_target_kw = apply_filters( 'siteseo_content_analysis_target_keywords', $siteseo_analysis_target_kw, get_the_ID() );

				//Keywords density
				foreach ($siteseo_analysis_target_kw as $kw) {
					if (preg_match_all('#\b(' . $kw . ')\b#iu', $siteseo_get_the_content, $m)) {
						$data['kws_density']['matches'][$kw][] = $m[0];
					}
				}
			}

			//Words Counter
			$data['words_counter'] = preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u", $siteseo_get_the_content, $matches);

			if ( ! empty($matches[0])) {
				$words_counter_unique = count(array_unique($matches[0]));
			} else {
				$words_counter_unique = '0';
			}
			$data['words_counter_unique'] = $words_counter_unique;

			//Update analysis
			update_post_meta(get_the_ID(), '_siteseo_analysis_data_oxygen', $data);
		}
	}
}

/**
 * Output submit button.
 *
 * @since 5.0
 *
 * @author Softaculous
 *
 * @param mixed $value
 * @param mixed $classes
 * @param mixed $type
 */
function siteseo_submit_button($value ='', $classes = 'btn btnPrimary', $type = 'submit') {
	if ('' === $value) {
		$value = __('Save changes', 'siteseo');
	}

	$html = '<p class="submit"><input id="submit" name="submit" type="' . esc_attr($type) . '" class="' . esc_attr($classes) . '" value="' . esc_attr($value) . '"/></p>';


	echo wp_kses($html, [
		'input' => [
			'type' => true,
			'name' => true,
			'value' => true,
			'id' => true,
			'class' => true
		],
		'p' => [
			'class' => true,
		]
	]);
}

/**
 * Generate HTML buttons classes
 *
 * @since 5.0
 *
 * @author Softaculous
 * @return
 */
function siteseo_btn_secondary_classes() {
	//Classic Editor compatibility
	global $pagenow;
	
	$current_screen = null;
	
	if(function_exists('get_current_screen')){
		$current_screen = get_current_screen();
	}
	
	if (!empty($current_screen) && method_exists($current_screen, 'is_block_editor') && true === $current_screen->is_block_editor()) {
		$btn_classes_secondary = 'components-button is-secondary';
	} elseif (isset($pagenow) && ($pagenow === 'term.php' || $pagenow === 'post.php' || $pagenow === 'post-new.php') ) {
		$btn_classes_secondary = 'button button-secondary';
	} else {
		$btn_classes_secondary = 'btn btnSecondary';
	}

	return $btn_classes_secondary;
}

/*
 * Global trailingslash option from SEO, Advanced, Advanced tab (useful for backwards compatibility with SiteSEO < 5.9)
 * @since 5.9
 * @return string 1 if true
 * author Softaculous
 */
if ( ! function_exists('siteseo_advanced_advanced_trailingslash_option')) {
	function siteseo_advanced_advanced_trailingslash_option(){
		$options = get_option('siteseo_advanced_option_name');
		if (! empty($options)) {
			if (isset($options['siteseo_advanced_advanced_trailingslash'])) {
				return $options['siteseo_advanced_advanced_trailingslash'];
			}
		}
	}
}


/*
 * Disable Add to cart GA tracking code on archive page / related products for Elementor PRO to avoid a JS conflict
 * @since 5.3
 * @return empty string
 * author Softaculous
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (is_plugin_active('elementor-pro/elementor-pro.php')) {
	add_filter('siteseo_gtag_ec_add_to_cart_archive_ev', 'siteseo_elementor_gtag_ec_add_to_cart_archive_ev');
	function siteseo_elementor_gtag_ec_add_to_cart_archive_ev($js) {
		return '';
	}
}


/**
 * Helper function needed for PHP 8.1 compatibility with "current" function
 * Get mangled object vars
 * @since 6.2.0
 */
function siteseo_maybe_mangled_object_vars($data){
	if(!function_exists('get_mangled_object_vars')){
		return $data;
	}

	if(!is_object($data)){
		return $data;
	}

	return get_mangled_object_vars($data);

}

function siteseo_check_ajax_referer($action){
	check_ajax_referer($action, sanitize_text_field(wp_unslash($_REQUEST['_ajax_nonce'])), true);
}

function siteseo_opt_get($name){
	return siteseo_opt_req($name);
}

function siteseo_opt_post($name){
	return siteseo_opt_req($name);
}

function siteseo_opt_req($name){
	
	if(empty($name)){
		return '';
	}
	
	if(!isset($_REQUEST[$name])){
		return '';
	}
	
	if(is_array($_REQUEST[$name]) || is_object($_REQUEST[$name])){
		return map_deep(map_deep($_REQUEST[$name], 'wp_unslash'), 'sanitize_text_field');
	}

	return sanitize_text_field(wp_unslash($_REQUEST[$name]));
}
