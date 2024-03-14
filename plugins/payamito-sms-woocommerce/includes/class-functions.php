<?php

namespace Payamito\Woocommerce\Funtions;

/**
 * Plugin public functions
 *
 * @package "payamito_woocommerce
 * @since   1.0.0
 */

defined('ABSPATH') || exit;

if (!class_exists("Functions")) {
	class Functions
	{

		/**
		 * Getting statuses  order
		 *
		 * @access public
		 * @return array
		 * @static
		 * @since  1.0.0
		 */

		public static function get_statuses()
		{
			$statuses = [];

			if (function_exists('wc_get_order_statuses')) {
				$order_statuses = wc_get_order_statuses();

				foreach ($order_statuses as $status => $title) {
					$statuses[str_replace('wc-', '', $status)] = $title;
				}
			}

			return apply_filters('payamito_woocommerce_order_statuses ', $statuses);
		}

		public static function user_type()
		{
			$users = ["administrator", "user", "vendor"];

			return apply_filters('payamito_woocommerce_user_type ', $users);
		}

		public static function english_number_to_persian($arg)
		{
			global $pwoo_general_options;
			if (!isset($pwoo_general_options['english_number_to_persian']) || $pwoo_general_options['english_number_to_persian'] == '0') {
				return $arg;
			}
			$persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
			$english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

			return str_replace($english, $persian, $arg);
		}

		public static function get_tags()
		{
			$tags = [
				[
					'tag'  => '{status}',
					'desc' => esc_html__('status', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{all_items}',
					'desc' => esc_html__(' all items', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{all_items_qty}',
					'desc' => esc_html__('all items qty ', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{count_items}',
					'desc' => esc_html__('count items', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{price}',
					'desc' => esc_html__('price', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{post_id}',
					'desc' => esc_html__('Post id', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{order_id}',
					'desc' => esc_html__('Order id', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{date}',
					'desc' => esc_html__('Date', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{transaction_id}',
					'desc' => esc_html__('Transaction id', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{description}',
					'desc' => esc_html__('Description', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{payment_method}',
					'desc' => esc_html__('Payment method', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{shipping_method}',
					'desc' => esc_html__('Shipping method', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{b_first_name}',
					'desc' => esc_html__('First name billing', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{b_last_name}',
					'desc' => esc_html__('Last name billing', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{b_company}',
					'desc' => esc_html__('Company billing', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{b_country}',
					'desc' => esc_html__('Country billing', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{b_state}',
					'desc' => esc_html__('State billing ', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{b_city}',
					'desc' => esc_html__('City billing', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{b_address_1}',
					'desc' => esc_html__('Address 1 billing', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{b_address_2}',
					'desc' => esc_html__('Address 2 billing', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{b_postcode}',
					'desc' => esc_html__('Postcode billing', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{sh_first_name}',
					'desc' => esc_html__('First_name shipping ', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{sh_last_name}',
					'desc' => esc_html__('Last name shipping ', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{sh_company}',
					'desc' => esc_html__('company shipping ', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{sh_country}',
					'desc' => esc_html__('country shipping ', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{sh_state}',
					'desc' => esc_html__('state shipping', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{sh_city}',
					'desc' => esc_html__('city shipping', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{sh_address_1}',
					'desc' => esc_html__('address 1 shipping ', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{sh_address_2}',
					'desc' => esc_html__('address 2 shipping ', 'payamito-woocommerce'),
				],
				[
					'tag'  => '{sh_postcode}',
					'desc' => esc_html__('postcode shipping ', 'payamito-woocommerce'),
				],
			];

			return apply_filters('payamito_woocommerce_tags', $tags);
		}

		public static function option_preparation($options)
		{
			$option_preparation = [];

			$user_type = self::user_type();

			$statuses = self::get_statuses();

			if (!is_array($options)) {
				return [];
			}
			if ($options['administrator_active'] != '1' && $options['user_active'] != '1' && $options['vendor_active'] != '1') {
				return $option_preparation['active'] = false;
			} else {
				$option_preparation['active'] = true;
			}

			foreach ($user_type as $user) {
				$is_active= $options[$user . '_active']??'0';
				if ($is_active == '1') {
					$option_preparation[$user]['active'] = true;

					if ($user == 'administrator') {
						$option_preparation[$user]['phone_number'] = isset($options['admin_phone_number_repeater']) ? $options['admin_phone_number_repeater'] : '';
					}
					if ($user === 'vendor') {
						$option_preparation[$user]['default_phone_number'] = $options['vendor_default_store_phone'] ?? '0';
					}
					if ($user === 'user') {
						$option_preparation[$user]['user_default_billing_phone'] = $options['user_default_billing_phone'] ?? '0';
					}

					$option_preparation[$user]['meta_key'] = isset($options[$user . '_meta_key']) ? $options[$user . '_meta_key'] : '';
					if ($option_preparation[$user]['meta_key'] === 'dokan_profile_settings') {
						$option_preparation[$user]['meta_key'] = self::get_phone_dokan_meta_key($option_preparation[$user]['meta_key']);
					}
				} else {
					$option_preparation[$user]['active'] = false;
				}

				foreach ($statuses as $status => $title) {
					$slug = $status;
					if (isset($options[$user . '_' . $slug . '_active']) && $options[$user . '_' . $slug . '_active'] == '1') {
						$option_preparation[$user][$slug] = true;

						if (isset($options[$user . '_' . $slug . '_active_p']) && $options[$user . '_' . $slug . '_active_p'] == '1') {
							$option_preparation[$user][$slug . '_pattern_active'] = true;

							$option_preparation[$user][$slug . '_pattern'] = isset($options[$user . '_' . $slug . '_pattern']) ? $options[$user . '_' . $slug . '_pattern'] : "";

							$option_preparation[$user][$slug . '_pattern_id'] = isset($options[$user . '_' . $slug . '_p']) ? $options[$user . '_' . $slug . '_p'] : '';
						} else {
							$option_preparation[$user][$slug]                   = [];
							$option_preparation[$user][$slug]['pattern_active'] = false;

							$option_preparation[$user][$slug . '_text_active'] = true;

							$option_preparation[$user][$slug . '_text'] = isset($options[$user . '_' . $slug . '_text']) ? $options[$user . '_' . $slug . '_text'] : '';
						}
					} else {
						$option_preparation[$user][$slug] = false;
					}
				}
			}

			return $option_preparation;
		}

		/**
		 * Getting user meta key from database
		 *
		 * @access public
		 * @return array
		 * @static
		 * @since  1.0.0
		 */
		public static function get_meta_keys()
		{
			global $wpdb;

			$final   = [];
			$sql     = "SELECT DISTINCT `meta_key` FROM `{$wpdb->usermeta}`";
			$results = $wpdb->get_results($sql, 'ARRAY_A');
			if (is_array($results)) {
				foreach ($results as $result) {
					$final[$result['meta_key']] = $result['meta_key'];
				}
			}

			return $final;
		}

		/**
		 * What type of request is this?
		 *
		 * @param string $type admin, ajax, cron or frontend.
		 *
		 * @return bool
		 */
		public static function is_request($type)
		{
			switch ($type) {
				case 'admin':
					return is_admin();
				case 'ajax':
					return defined('DOING_AJAX');
				case 'cron':
					return defined('DOING_CRON');
				case 'frontend':
					return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
			}
		}

		public static function jalali_converter($g_y, $g_m, $g_d, $mod = '')
		{
			$d_4   = $g_y % 4;
			$g_a   = [0, 0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
			$doy_g = $g_a[(int) $g_m] + $g_d;
			if ($d_4 == 0 and $g_m > 2) {
				$doy_g++;
			}
			$d_33 = (int) ((($g_y - 16) % 132) * .0305);
			$a    = ($d_33 == 3 or $d_33 < ($d_4 - 1) or $d_4 == 0) ? 286 : 287;
			$b    = (($d_33 == 1 or $d_33 == 2) and ($d_33 == $d_4 or $d_4 == 1)) ? 78 : (($d_33 == 3 and $d_4 == 0) ? 80 : 79);
			if ((int) (($g_y - 10) / 63) == 30) {
				$a--;
				$b++;
			}
			if ($doy_g > $b) {
				$jy    = $g_y - 621;
				$doy_j = $doy_g - $b;
			} else {
				$jy    = $g_y - 622;
				$doy_j = $doy_g + $a;
			}
			if ($doy_j < 187) {
				$jm = (int) (($doy_j - 1) / 31);
				$jd = $doy_j - (31 * $jm++);
			} else {
				$jm = (int) (($doy_j - 187) / 30);
				$jd = $doy_j - 186 - ($jm * 30);
				$jm += 7;
			}

			$jd = $jd > 9 ? $jd : '0' . $jd;
			$jm = $jm > 9 ? $jm : '0' . $jm;

			return ($mod == '') ? [$jy, $jm, $jd] : $jy . $mod . $jm . $mod . $jd;
		}

		public static function get_phone_dokan_meta_key($metakey)
		{
			if (!is_string($metakey)) {
				return "";
			}
			$metakey = unserialize($metakey);
			if (!is_array($metakey)) {
				return "";
			}
			if (!isset($metakey['phone'])) {
				return "";
			}

			return $metakey['phone'];
		}
	}
}
