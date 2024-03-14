<?php
	/*
* @Author 		engr.sumonazma@gmail.com
* Copyright: 	mage-people.com
*/
	if (!defined('ABSPATH')) {
		die;
	} // Cannot access pages directly.
	if (!class_exists('MP_Global_Function')) {
		class MP_Global_Function {
			public function __construct() {
				add_action('mp_load_date_picker_js', [$this, 'date_picker_js'], 10, 2);
			}
			public static function query_post_type($post_type, $show = -1, $page = 1): WP_Query {
				$args = array(
					'post_type' => $post_type,
					'posts_per_page' => $show,
					'paged' => $page,
					'post_status' => 'publish'
				);
				return new WP_Query($args);
			}
			public static function get_all_post_id($post_type, $show = -1, $page = 1): array {
				return get_posts(array(
					'fields' => 'ids',
					'post_type' => $post_type,
					'posts_per_page' => $show,
					'paged' => $page,
					'post_status' => 'publish'
				));
			}
			public static function get_taxonomy($name) {
				return get_terms(array('taxonomy' => $name, 'hide_empty' => false));
			}


        public static function get_meta_values( $meta_key = '', $post_type = 'post', $post_status = 'publish' ) {

                global $wpdb;

                if( empty( $meta_key ) )
                    return;

                $meta_values = $wpdb->get_col( $wpdb->prepare( "
        SELECT pm.meta_value FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = %s 
        AND p.post_type = %s 
        AND p.post_status = %s 
    ", $meta_key, $post_type, $post_status ) );

                return $meta_values;
            }
			public static function get_post_info($post_id, $key, $default = '') {
				$data = get_post_meta($post_id, $key, true) ?: $default;
				return self::data_sanitize($data);
			}
			public static function get_submit_info($key, $default = '') {
				return self::data_sanitize($_POST[$key] ?? $default);
			}
			public static function data_sanitize($data) {
				$data = maybe_unserialize($data);
				if (is_string($data)) {
					$data = maybe_unserialize($data);
					if (is_array($data)) {
						$data = self::data_sanitize($data);
					}
					else {
						$data = sanitize_text_field(stripslashes(strip_tags($data)));
					}
				}
				elseif (is_array($data)) {
					foreach ($data as &$value) {
						if (is_array($value)) {
							$value = self::data_sanitize($value);
						}
						else {
							$value = sanitize_text_field(stripslashes(strip_tags($value)));
						}
					}
				}
				return $data;
			}
			//**************Date related*********************//
			public static function date_picker_format($option, $key = 'date_format'): string {
				$format = MP_Global_Function::get_settings($option, $key, 'D d M , yy');
				$date_format = 'Y-m-d';
				$date_format = $format == 'yy/mm/dd' ? 'Y/m/d' : $date_format;
				$date_format = $format == 'yy-dd-mm' ? 'Y-d-m' : $date_format;
				$date_format = $format == 'yy/dd/mm' ? 'Y/d/m' : $date_format;
				$date_format = $format == 'dd-mm-yy' ? 'd-m-Y' : $date_format;
				$date_format = $format == 'dd/mm/yy' ? 'd/m/Y' : $date_format;
				$date_format = $format == 'mm-dd-yy' ? 'm-d-Y' : $date_format;
				$date_format = $format == 'mm/dd/yy' ? 'm/d/Y' : $date_format;
				$date_format = $format == 'd M , yy' ? 'j M , Y' : $date_format;
				$date_format = $format == 'D d M , yy' ? 'D j M , Y' : $date_format;
				$date_format = $format == 'M d , yy' ? 'M  j, Y' : $date_format;
				return $format == 'D M d , yy' ? 'D M  j, Y' : $date_format;
			}
			public function date_picker_js($selector, $dates) {
				$start_date = $dates[0];
				$start_year = date('Y', strtotime($start_date));
				$start_month = (date('n', strtotime($start_date)) - 1);
				$start_day = date('j', strtotime($start_date));
				$end_date = end($dates);
				$end_year = date('Y', strtotime($end_date));
				$end_month = (date('n', strtotime($end_date)) - 1);
				$end_day = date('j', strtotime($end_date));
				$all_date = [];
				foreach ($dates as $date) {
					$all_date[] = '"' . date('j-n-Y', strtotime($date)) . '"';
				}
				?>
				<script>
					jQuery(document).ready(function () {
						jQuery("<?php echo esc_attr($selector); ?>").datepicker({
							dateFormat: mp_date_format,
							minDate: new Date(<?php echo esc_attr($start_year); ?>, <?php echo esc_attr($start_month); ?>, <?php echo esc_attr($start_day); ?>),
							maxDate: new Date(<?php echo esc_attr($end_year); ?>, <?php echo esc_attr($end_month); ?>, <?php echo esc_attr($end_day); ?>),
							autoSize: true,
							changeMonth: true,
							changeYear: true,
							beforeShowDay: WorkingDates,
							onSelect: function (dateString, data) {
								let date = data.selectedYear + '-' + ('0' + (parseInt(data.selectedMonth) + 1)).slice(-2) + '-' + data.selectedDay;
								jQuery(this).closest('label').find('input[type="hidden"]').val(date).trigger('change');
							}
						});
						function WorkingDates(date) {
							let availableDates = [<?php echo implode(',', $all_date); ?>];
							let dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
							if (jQuery.inArray(dmy, availableDates) !== -1) {
								return [true, "", "Available"];
							} else {
								return [false, "", "unAvailable"];
							}
						}
					});
				</script>
				<?php
			}
			public static function date_format($date, $format = 'date') {
				$date_format = get_option('date_format');
				$time_format = get_option('time_format');
				$wp_settings = $date_format . '  ' . $time_format;
				$timezone = wp_timezone_string();
				$timestamp = strtotime($date . ' ' . $timezone);
				if ($format == 'date') {
					$date = date_i18n($date_format, $timestamp);
				}
				elseif ($format == 'time') {
					$date = date_i18n($time_format, $timestamp);
				}
				elseif ($format == 'full') {
					$date = date_i18n($wp_settings, $timestamp);
				}
				elseif ($format == 'day') {
					$date = date_i18n('d', $timestamp);
				}
				elseif ($format == 'month') {
					$date = date_i18n('M', $timestamp);
				}
				elseif ($format == 'year') {
					$date = date_i18n('Y', $timestamp);
				}
				else {
					$date = date_i18n($format, $timestamp);
				}
				return $date;
			}
			public static function date_separate_period($start_date, $end_date, $repeat = 1): DatePeriod {
				$repeat = max($repeat, 1);
				$_interval = "P" . $repeat . "D";
				$end_date = date('Y-m-d', strtotime($end_date . ' +1 day'));
				return new DatePeriod(new DateTime($start_date), new DateInterval($_interval), new DateTime($end_date));
			}
			public static function check_time_exit_date($date) {
				if ($date) {
					$parse_date = date_parse($date);
					if (($parse_date['hour'] && $parse_date['hour'] > 0) || ($parse_date['minute'] && $parse_date['minute'] > 0) || ($parse_date['second'] && $parse_date['second'] > 0)) {
						return true;
					}
				}
				return false;
			}
			//***********************************//
			public static function get_settings($section, $key, $default = '') {
				$options = get_option($section);
				if (isset($options[$key]) && $options[$key]) {
					$default = $options[$key];
				}
				return $default;
			}
			public static function get_style_settings($key, $default = '') {
				return self::get_settings('mp_style_settings', $key, $default);
			}
			public static function get_slider_settings($key, $default = '') {
				return self::get_settings('mp_slider_settings', $key, $default);
			}
			//***********************************//
			public static function price_convert_raw($price) {
				$price = wp_strip_all_tags($price);
				$price = str_replace(get_woocommerce_currency_symbol(), '', $price);
				$price = str_replace(wc_get_price_thousand_separator(), 't_s', $price);
				$price = str_replace(wc_get_price_decimal_separator(), 'd_s', $price);
				$price = str_replace('t_s', '', $price);
				$price = str_replace('d_s', '.', $price);
				$price = str_replace('&nbsp;', '', $price);
				return max($price, 0);
			}
			public static function wc_price($post_id, $price, $args = array()): string {
				$num_of_decimal = get_option('woocommerce_price_num_decimals', 2);
				$args = wp_parse_args($args, array(
					'qty' => '',
					'price' => '',
				));
				$_product = self::get_post_info($post_id, 'link_wc_product', $post_id);
				$product = wc_get_product($_product);
				$qty = '' !== $args['qty'] ? max(0.0, (float)$args['qty']) : 1;
				$tax_with_price = get_option('woocommerce_tax_display_shop');
				if ('' === $price) {
					return '';
				}
				elseif (empty($qty)) {
					return 0.0;
				}
				$line_price = (float)$price * (int)$qty;
				$return_price = $line_price;
				if ($product->is_taxable()) {
					if (!wc_prices_include_tax()) {
						$tax_rates = WC_Tax::get_rates($product->get_tax_class());
						$taxes = WC_Tax::calc_tax($line_price, $tax_rates);
						if ('yes' === get_option('woocommerce_tax_round_at_subtotal')) {
							$taxes_total = array_sum($taxes);
						}
						else {
							$taxes_total = array_sum(array_map('wc_round_tax_total', $taxes));
						}
						$return_price = $tax_with_price == 'excl' ? round($line_price, $num_of_decimal) : round($line_price + $taxes_total, $num_of_decimal);
					}
					else {
						$tax_rates = WC_Tax::get_rates($product->get_tax_class());
						$base_tax_rates = WC_Tax::get_base_tax_rates($product->get_tax_class('unfiltered'));
						if (!empty(WC()->customer) && WC()->customer->get_is_vat_exempt()) { // @codingStandardsIgnoreLine.
							$remove_taxes = apply_filters('woocommerce_adjust_non_base_location_prices', true) ? WC_Tax::calc_tax($line_price, $base_tax_rates, true) : WC_Tax::calc_tax($line_price, $tax_rates, true);
							if ('yes' === get_option('woocommerce_tax_round_at_subtotal')) {
								$remove_taxes_total = array_sum($remove_taxes);
							}
							else {
								$remove_taxes_total = array_sum(array_map('wc_round_tax_total', $remove_taxes));
							}
							// $return_price = round( $line_price, $num_of_decimal);
							$return_price = round($line_price - $remove_taxes_total, $num_of_decimal);
						}
						else {
							$base_taxes = WC_Tax::calc_tax($line_price, $base_tax_rates, true);
							$modded_taxes = WC_Tax::calc_tax($line_price - array_sum($base_taxes), $tax_rates);
							if ('yes' === get_option('woocommerce_tax_round_at_subtotal')) {
								$base_taxes_total = array_sum($base_taxes);
								$modded_taxes_total = array_sum($modded_taxes);
							}
							else {
								$base_taxes_total = array_sum(array_map('wc_round_tax_total', $base_taxes));
								$modded_taxes_total = array_sum(array_map('wc_round_tax_total', $modded_taxes));
							}
							$return_price = $tax_with_price == 'excl' ? round($line_price - $base_taxes_total, $num_of_decimal) : round($line_price - $base_taxes_total + $modded_taxes_total, $num_of_decimal);
						}
					}
				}
				$return_price = apply_filters('woocommerce_get_price_including_tax', $return_price, $qty, $product);
				$display_suffix = get_option('woocommerce_price_display_suffix') ? get_option('woocommerce_price_display_suffix') : '';
				return wc_price($return_price) . ' ' . $display_suffix;
			}
			//***********************************//
			public static function get_image_url($post_id = '', $image_id = '', $size = 'full') {
				if ($post_id) {
					$image_id = self::get_post_info($post_id, 'mp_thumbnail');
					$image_id = $image_id ?: get_post_thumbnail_id($post_id);
				}
				return wp_get_attachment_image_url($image_id, $size);
			}
			public static function get_page_by_slug($slug) {
				if ($pages = get_pages()) {
					foreach ($pages as $page) {
						if ($slug === $page->post_name) {
							return $page;
						}
					}
				}
				return false;
			}
			public static function check_woocommerce(): int {
				include_once(ABSPATH . 'wp-admin/includes/plugin.php');
				$plugin_dir = ABSPATH . 'wp-content/plugins/woocommerce';
				if (is_plugin_active('woocommerce/woocommerce.php')) {
					return 1;
				}
				elseif (is_dir($plugin_dir)) {
					return 2;
				}
				else {
					return 0;
				}
			}
			//***********************************//
			public static function get_order_item_meta($item_id, $key): string {
				global $wpdb;
				$table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
				$results = $wpdb->get_results($wpdb->prepare("SELECT meta_value FROM $table_name WHERE order_item_id = %d AND meta_key = %s", $item_id, $key));
				foreach ($results as $result) {
					$value = $result->meta_value;
				}
				return $value ?? '';
			}
			//***********************************//
			public static function wc_product_sku($product_id) {
				if ($product_id) {
					return new WC_Product($product_id);
				}
				return null;
			}
			public static function all_tax_list(): array {
				global $wpdb;
				$table_name = $wpdb->prefix . 'wc_tax_rate_classes';
				$result = $wpdb->get_results("SELECT * FROM $table_name");
				$tax_list = [];
				foreach ($result as $tax) {
					$tax_list[$tax->slug] = $tax->name;
				}
				return $tax_list;
			}
			public static function week_day(): array {
				return [
					'monday' => esc_html__('Monday', 'mptbm_plugin'),
					'tuesday' => esc_html__('Tuesday', 'mptbm_plugin'),
					'wednesday' => esc_html__('Wednesday', 'mptbm_plugin'),
					'thursday' => esc_html__('Thursday', 'mptbm_plugin'),
					'friday' => esc_html__('Friday', 'mptbm_plugin'),
					'saturday' => esc_html__('Saturday', 'mptbm_plugin'),
					'sunday' => esc_html__('Sunday', 'mptbm_plugin'),
				];
			}
			public static function get_plugin_data($data) {
				$plugin_data = get_plugin_data(__FILE__);
				return $plugin_data[$data];
			}
			public static function array_to_string($array) {
				$ids = '';
				if (sizeof($array) > 0) {
					foreach ($array as $data) {
						if ($data) {
							$ids = $ids ? $ids . ',' . $data : $data;
						}
					}
				}
				return $ids;
			}
			public static function esc_html($string): string {
				$allow_attr = array(
					'input' => [
						'type' => [],
						'class' => [],
						'id' => [],
						'name' => [],
						'value' => [],
						'size' => [],
						'placeholder' => [],
						'min' => [],
						'max' => [],
						'checked' => [],
						'required' => [],
						'disabled' => [],
						'readonly' => [],
						'step' => [],
						'data-default-color' => [],
						'data-price' => [],
					],
					'p' => ['class' => []],
					'img' => ['class' => [], 'id' => [], 'src' => [], 'alt' => [],],
					'fieldset' => [
						'class' => []
					],
					'label' => [
						'for' => [],
						'class' => []
					],
					'select' => [
						'class' => [],
						'name' => [],
						'id' => [],
						'data-price' => [],
					],
					'option' => [
						'class' => [],
						'value' => [],
						'id' => [],
						'selected' => [],
					],
					'textarea' => [
						'class' => [],
						'rows' => [],
						'id' => [],
						'cols' => [],
						'name' => [],
					],
					'h1' => ['class' => [], 'id' => [],],
					'h2' => ['class' => [], 'id' => [],],
					'h3' => ['class' => [], 'id' => [],],
					'h4' => ['class' => [], 'id' => [],],
					'h5' => ['class' => [], 'id' => [],],
					'h6' => ['class' => [], 'id' => [],],
					'a' => ['class' => [], 'id' => [], 'href' => [],],
					'div' => [
						'class' => [],
						'id' => [],
						'data-ticket-type-name' => [],
					],
					'span' => [
						'class' => [],
						'id' => [],
						'data' => [],
						'data-input-change' => [],
					],
					'i' => [
						'class' => [],
						'id' => [],
						'data' => [],
					],
					'table' => [
						'class' => [],
						'id' => [],
						'data' => [],
					],
					'tr' => [
						'class' => [],
						'id' => [],
						'data' => [],
					],
					'td' => [
						'class' => [],
						'id' => [],
						'data' => [],
					],
					'thead' => [
						'class' => [],
						'id' => [],
						'data' => [],
					],
					'tbody' => [
						'class' => [],
						'id' => [],
						'data' => [],
					],
					'th' => [
						'class' => [],
						'id' => [],
						'data' => [],
					],
					'svg' => [
						'class' => [],
						'id' => [],
						'width' => [],
						'height' => [],
						'viewBox' => [],
						'xmlns' => [],
					],
					'g' => [
						'fill' => [],
					],
					'path' => [
						'd' => [],
					],
					'br' => array(),
					'em' => array(),
					'strong' => array(),
				);
				return wp_kses($string, $allow_attr);
			}
			//***********************************//
		}
		new MP_Global_Function();
	}