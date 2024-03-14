<?php

namespace Payamito\Woocommerce\Settings;

use Payamito\Woocommerce\Funtions\Functions;
use Payamito\Woocommerce\P_Woocommerce;

/**
 * Register an options panel.
 *
 * @package Payamito
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

class  Settings
{
	/**
	 * Holds the options panel controller.
	 *
	 * @var object
	 */
	protected $panel;

	public $statuses;

	public $tags;

	public $meta_keys;

	/**
	 * Get things started.
	 */
	public function __construct()
	{
		add_filter('payamito_add_section', [$this, 'register_settings'], 1);
		add_action('kianfr_' . 'payamito' . '_save_before', [$this, 'option_save'], 10, 1);
		add_action('admin_footer', [$this, "print_tags"]);
	}

	/**
	 * Save Plugin options .
	 * Save all options  in external row in data base form payamito options   .
	 *
	 * @param array 1 param
	 *
	 * @return void
	 * @since 1.0
	 */
	public function option_save($options)
	{
		$user_type = Functions::user_type();

		$init = [];

		$statuses = payamito_wc()->functions::get_statuses();

		foreach ($statuses as $status => $title) {
			$slug = $status;
			foreach ($user_type as $type) {
				if (isset($options['payamito_woocommerce'][$type . "_" . $slug . "_accordion"])) {
					array_push($init, $options['payamito_woocommerce'][$type . "_" . $slug . "_accordion"]);
				}
				if (isset($options['payamito_woocommerce'][$type . "_" . $slug . "_accordion"])) {
					unset($options['payamito_woocommerce'][$type . "_" . $slug . "_accordion"]);
				}
			}
		}
		foreach ($init as $ini) {
			foreach ($ini as $index => $in) {
				$options['payamito_woocommerce'][$index] = $in;
			}
		}
		$this->otp_option_save($options['payamito_woocommerce']);
		$this->general_options_seve($options['payamito_woocommerce']);

		update_option('payamito_woocommerce_options', $options['payamito_woocommerce']);
	}

	/**
	 * Save OTP options .
	 * Save all otp options in external row in data base form payamito options   .
	 *
	 * @param array  param
	 *
	 * @return void
	 * @since 1.0
	 */
	public function otp_option_save($options)
	{
		$init = [];
		if (isset($options['otp_active']) && $options['otp_active'] == '1') {
			$init['active'] = true;

			if (isset($options['otp_active_p']) && $options['otp_active_p'] == '1') {
				$init['pattern_active'] = true;

				$init['pattern_id'] = $options['otp_p'];

				$init['pattern'] = $options['otp_repeater'];
			} else {
				$init['text'] = $options['otp_sms'];
			}
		} else {
			$init['active'] = false;
		}
		$init['force_enter'] = $options['user_add_phone_number_field_enter'];

		$init['force_otp'] = $options['user_add_phone_number_field_force_OTP'];

		$init['number_of_code_otp'] = $options['number_of_code'];

		$init['again_send_time_otp'] = $options['again_send_time'];

		$init['once'] = $options['otp_once'];

		$init['phone_number_title']       = $options['phone_number_title'];
		$init['phone_number_placeholder'] = $options['phone_number_placeholder'];
		$init['otp_title']                = $options['otp_title'];
		$init['otp_placeholder']          = $options['otp_placeholder'];

		$init['send_otp_text'] = $options['send_otp_text'];

		update_option('payamito_woocommerce_otp', $init);
	}

	public function general_options_seve($options)
	{
		update_option('payamito_woocommerce_general', $options);
	}

	public function register_settings($section)
	{
		if (!class_exists(\WooCommerce::class) || !function_exists('WC')) {
			$section[] = [
				'title'  => esc_html__('Woocommerce', 'payamito-woocommerce'),
				'fields' => [
					[
						'type'    => 'notice',
						'style'   => 'warning',
						'content' => esc_html__('Payamito SMS Woocommerce requires WooCommerce to work properly.', 'payamito-woocommerce'),
					],
				],
			];
			return $section;
		}
		if (version_compare((string) WC()->version, '7.0.0', '<')) {

			$section[] = [
				'title'  => esc_html__('Woocommerce', 'payamito-woocommerce'),
				'fields' => [
					[
						'type'    => 'notice',
						'style'   => 'warning',
						'content' => esc_html__('Payamito SMS Woocommerce requires WooCommerce to work properly.', 'payamito-woocommerce'),
					],
				],
			];
			return $section;
		}

		$settings = [
			'title'  => esc_html__('Woocommerce', 'payamito-woocommerce'),
			'fields' => [
				[
					'id'   => 'payamito_woocommerce',
					'type' => 'tabbed',
					'tabs' => $this->tabs(),
				],
			],
		];

		$settings = apply_filters('payamito_wp_settings', $settings);

		if (is_array($section)) {
			array_push($section, $settings);
		}

		return $section;
	}


	public function tabs()
	{
		$tabs            = [];
		$this->statuses  = Functions::get_statuses();
		$this->tags      = Functions::get_tags();
		$this->meta_keys = Functions::get_meta_keys();

		#--------------------------------------------------------------------------
		$general = [
			'id'     => 'payamito_woocommerce_general',
			'title'  => esc_html__('General ', 'payamito-woocommerce'),
			'fields' => [
				[
					'id'    => 'jalali_date',
					'type'  => 'switcher',
					'title' => esc_html__('Jalali date conveter in SMS', 'payamito-woocommerce'),
					'desc'  => esc_html__('At the time of sending the SMS, the Gregorian date will become the Jalali date and will be sent to the user. If you have the full WordPress solar builder plugin installed, do not enable this option.', 'payamito-woocommerce'),
				],
				[
					'id'    => 'english_number_to_persian',
					'type'  => 'switcher',
					'title' => esc_html__('English number conveter in SMS', 'payamito-woocommerce'),
					'desc'  => esc_html__('When sending sms, English numeric characters will be sent to the user as Persian numeric characters.', 'payamito-woocommerce'),
				],
			],
		];
		array_push($tabs, $general);
		#---------------------------------------------------------------------------
		$otp = [
			'id'     => 'payamito_woocommerce_otp',
			'title'  => esc_html__('OTP', 'payamito-woocommerce'),
			'fields' => [
				[
					'id'    => 'otp_active',
					'type'  => 'switcher',
					'title' => esc_html__('Enable SMS authentication', 'payamito-woocommerce'),
					'desc'  => esc_html__('By activating this option in the WooCommerce checkout form, a mobile number will be received from the user.', 'payamito-woocommerce'),
				],
				[
					'type'       => 'notice',
					'style'      => 'warning',
					'content'    => esc_html__('"notice" send pattern need to help', 'payamito-woocommerce'),
					'dependency' => ["otp_active", '==', 'true'],
					'class'      => 'pattern_background',
				],
				[
					'id'         => 'otp_active_p',
					'type'       => 'switcher',
					'title'      => payamito_dynamic_text('pattern_active_title'),
					'desc'       => payamito_dynamic_text('pattern_active_desc'),
					'help'       => payamito_dynamic_text('pattern_active_help'),
					'dependency' => ["otp_active", '==', 'true'],
					'class'      => 'pattern_background',
				],
				[
					'id'         => 'otp_p',
					'type'       => 'text',
					'title'      => payamito_dynamic_text('pattern_ID_title'),
					'desc'       => payamito_dynamic_text('pattern_ID_desc'),
					'help'       => payamito_dynamic_text('pattern_ID_help'),
					'dependency' => ["otp_active|otp_active_p", '==|==', 'true|true'],
					'class'      => 'pattern_background',
				],
				[
					'id'         => 'otp_repeater',
					'type'       => 'repeater',
					'title'      => payamito_dynamic_text('pattern_Variable_title'),
					'desc'       => payamito_dynamic_text('pattern_Variable_desc'),
					'help'       => payamito_dynamic_text('pattern_Variable_help'),
					'max'        => '2',
					'class'      => 'pattern_background',
					'dependency' => ["otp_active|otp_active_p", '==|==', 'true|true'],
					'fields'     => [
						[
							'id'          => 'opt_tags',
							'placeholder' => esc_html__("Tags", "payamito-woocommerce"),
							'type'        => 'select',
							'class'       => 'pattern_background',
							'options'     => [
								"{OTP}"       => esc_html__('OTP', 'payamito-woocommerce'),
								"{site_name}" => esc_html__('Wordpress title', 'payamito-woocommerce'),
							],
						],
						[
							'id'          => 'otp_user_otp',
							'type'        => 'number',
							'placeholder' => esc_html__("Your tag", "payamito-woocommerce"),
							'default'     => '0',
							'class'       => 'pattern_background',
						],
					],
				],
				[
					'id'          => 'otp_sms',
					'title'       => payamito_dynamic_text('send_content_title'),
					'desc'        => payamito_dynamic_text('send_content_desc'),
					'help'        => payamito_dynamic_text('send_content_help'),
					'placeholder' => esc_html__('"notice" send pattern need to help', 'payamito-woocommerce'),
					'type'        => 'textarea',
					'class'       => 'pattern_background',
					'dependency'  => ["otp_active|otp_active_p", '==|!=', 'true|true'],
				],
				[
					'id'         => 'number_of_code',
					'title'      => esc_html__('Number of OTP code', 'payamito-woocommerce'),
					'desc'       => esc_html__('Number of OTP code that you want send for user', 'payamito-woocommerce'),
					'type'       => 'select',
					'dependency' => ["otp_active", '==', 'true'],
					'options'    => apply_filters("again_send_number", [
						"4"  => "4",
						"5"  => "5",
						"6"  => "6",
						"7"  => "7",
						"8"  => "8",
						"9"  => "9",
						"10" => "10",
					]),
				],
				[
					'id'         => 'again_send_time',
					'title'      => esc_html__('Send Again', 'payamito-woocommerce'),
					'desc'       => esc_html__('When you want the user to re-request OTP.', 'payamito-woocommerce'),
					'type'       => 'select',
					'dependency' => ["otp_active", '==', 'true'],
					'options'    => apply_filters("again_send_time", [
						"30"  => "30",
						"60"  => "60",
						"90"  => "90",
						"120" => "120",
						"300" => "300",
					]),
				],
				[
					'id'         => 'otp_once',
					'type'       => 'switcher',
					'title'      => esc_html__('Authentication only once', 'payamito-woocommerce'),
					'dependency' => ["otp_active", '==', 'true'],
					'desc'       => esc_html__('If the user confirms his phone number number once, he does not need to confirm his phone number number again in the next submit order.', 'payamito-woocommerce'),

				],
				[
					'id'         => 'otp_title',
					'type'       => 'text',
					'title'      => esc_html__('OTP Title', 'payamito-woocommerce'),
					'dependency' => ["otp_active", '==', 'true'],
					'defualt'    => esc_html__('OTP', 'payamito-woocommerce'),

				],
				[
					'id'         => 'otp_placeholder',
					'type'       => 'text',
					'title'      => esc_html__('OTP Placeholder', 'payamito-woocommerce'),
					'dependency' => ["otp_active", '==', 'true'],
					'defualt'    => esc_html__('Enter your OTP', 'payamito-woocommerce'),
				],
			],

		];
		array_push($tabs, $otp);

		array_push($tabs, $this->admin_tab());
		array_push($tabs, $this->vendor_tab());
		array_push($tabs, $this->user_tab());

		return apply_filters('payamito_woocommerce_tabs', $tabs);
	}

	public function admin_tab()
	{
		$admin_tab = [
			'title'  => esc_html__('Admin SMS', 'payamito-woocommerce'),
			'fields' => [

				[
					'id'    => 'administrator_active',
					'title' => esc_html__('Enable sending sms to administrator', 'payamito-woocommerce'),
					'desc'  => esc_html__('By activating this option, you can specify that the details of the customer ordering process be sent to the store manager.', 'payamito-woocommerce'),
					'type'  => 'switcher',
				],
				$this->option_get_admin_phone_number(),
			],
		];

		foreach ($this->statuses as $status => $title) {
			array_push($admin_tab['fields'], $this->set_status_field('administrator', [$status => $title]));
		}

		return apply_filters('payamito_woocommerce_admin_tab', $admin_tab);
	}

	public function user_tab()
	{
		$user_tab = [
			'title'  => esc_html__('User SMS', 'payamito-woocommerce'),
			'fields' => [

				[
					'id'    => 'user_active',
					'title' => esc_html__('Enable sending sms to customers', 'payamito-woocommerce'),
					'desc'  => esc_html__('By activating this option, you can manage the items related to customers\' SMS.', 'payamito-woocommerce'),
					'type'  => 'switcher',
				],
			],
		];

		foreach ($this->statuses as $status => $title) {
			array_push($user_tab['fields'], $this->set_status_field('user', [$status => $title]));
		}

		return apply_filters('payamito_woocommerce_user_tab', $user_tab);
	}

	public function vendor_tab()
	{

		if (P_Woocommerce::dokan_is_active() && version_compare((string) dokan()->version, '3.2.0', '>=')) {
			$vendor_tab = [
				'title'  => esc_html__('Vendor SMS', 'payamito-woocommerce'),
				'fields' => [
					[
						'id'    => 'vendor_active',
						'title' => esc_html__('Activation of sending sms to vendors', 'payamito-woocommerce'),
						'desc'  => esc_html__('By activating this option, it will be possible to send customer order details to the vendors of your site.', 'payamito-woocommerce'),
						'type'  => 'switcher',
					],
				],
			];
		} else {
			$vendor_tab = [
				'title'  => esc_html__('Vendor SMS', 'payamito-woocommerce'),
				'fields' => [
					[
						'type'    => 'notice',
						'style'   => 'warning',
						'content' => esc_html__('Dokan plugin is not active or the version is less than 3.2.0', 'payamito-woocommerce'),
					],
				],
			];
		}

		foreach ($this->statuses as $status => $title) {
			array_push($vendor_tab['fields'], $this->set_status_field('vendor', [$status => $title]));
		}

		return apply_filters('payamito_woocommerce_vendor_tab', $vendor_tab);
	}

	public function add_header($dependency)
	{
		return [
			'type'       => 'heading',
			'content'    => esc_html__('SMS change status', 'payamito-woocommerce'),
			'dependency' => $dependency,
		];
	}



	public function get_for_select_field()
	{
		$tags_select = [];
		if (is_array($this->tags)) {
			foreach ($this->tags as $tag) {
				$tags_select[$tag['tag']] = $tag['desc'];
			}
			ksort($tags_select, SORT_STRING);

			return $tags_select;
		}

		return [];
	}

	/**
	 * print tags for modal
	 */
	public function print_tags()
	{
		$page = isset($_REQUEST['page']) ? sanitize_text_field($_REQUEST['page']) : false;
		if ($page !== 'payamito') {
			return;
		}
		$html = "<div id='payamito-woocommerce-modal' class='modal ' >";
		$html .= "<div>";
		if (!is_null($this->tags)) {
			foreach ($this->tags as $tag) {
				$html .= "<div  class='payamito-tags-modal'><p class='payamito-tag-modal' >" . $tag['tag'] . "</p>";
				$html .= "<span>" . $tag['desc'] . "</span></div>";
			}
			$html .= '</div>';
			echo $html;
		}
	}

	public function option_set_pattern($user_type, $status, $dependency)
	{
		$test = $dependency;

		return [
			'id'         => $user_type . '_' . $status . '_pattern',
			'type'       => 'repeater',
			'title'      => payamito_dynamic_text('pattern_Variable_title'),
			'desc'       => payamito_dynamic_text('pattern_Variable_desc'),
			'help'       => payamito_dynamic_text('pattern_Variable_help'),
			'max'        => '15',
			'class'      => "payamito-woocommerce-repeater pattern_background",
			'dependency' => [$user_type . "_" . $status . "_active_p" . "|" . $dependency, '==|==', 'true|true'],
			'fields'     => [
				[
					'id'          => 0,
					'type'        => 'select',
					'placeholder' => esc_html__("Select tag", "payamito-woocommerce"),
					'options'     => $this->get_for_select_field(),
				],
				[
					'id'          => 1,
					'type'        => 'number',
					'placeholder' => esc_html__("Your tag", "payamito-woocommerce"),
					'default'     => '0',
				],
			],
		];
	}

	public function option_get_admin_phone_number()
	{
		return [
			'id'         => 'admin_phone_number_repeater',
			'type'       => 'repeater',
			'title'      => esc_html__("phone number", "payamito-woocommerce"),
			'dependency' => ["administrator_active", '==', 'true'],
			'fields'     => [
				[
					'id'          => 'admin_phone_number',
					'type'        => 'text',
					'placeholder' => esc_html__("Phone Number", "payamito-woocommerce"),
					'class'       => 'payamito-woocommerce-phone-number',
					'attributes'  => [
						'type'      => 'tel',
						'maxlength' => 11,
						'minlength' => 10,
					],
				],
			],
		];
	}

	public function set_status_field($user_type, $status)
	{
		$title  = "";
		$slug   = "";
		$active = __("Enable SMS for status ", "payamito-woocommerce");

		foreach ($status as $index => $ac) {
			$title = (string) $ac;
			$slug  = (string) $index;
		}
		if (is_array($user_type) || is_array($slug)) {
			return [];
		}
		$dependency = $user_type . "_" . $slug . "_active";

		return [
			'id'         => $user_type . '_' . $slug . '_accordion',
			'type'       => 'accordion',
			'dependency' => [$user_type . "_active", '==', 'true'],
			'accordions' => [
				[
					'title'  => esc_html__(ucfirst($title), 'payamito-woocommerce'),
					'fields' => [
						[
							'id'    => $user_type . "_" . $slug . "_active",
							'title' => $active . " " . ucfirst($title),
							'type'  => 'switcher',
						],
						[
							'type'       => 'notice',
							'style'      => 'warning',
							'content'    => esc_html__('"notice" send pattern need to help', 'payamito-woocommerce'),
							'dependency' => [$user_type . "_" . $slug . "_active", '==', 'true'],
							'class'      => 'pattern_background',
						],
						[

							'id'         => $user_type . "_" . $slug . "_active_p",
							'type'       => 'switcher',
							'dependency' => [$user_type . "_" . $slug . "_active", '==', 'true'],
							'title'      => payamito_dynamic_text('pattern_active_title'),
							'desc'       => payamito_dynamic_text('pattern_active_desc'),
							'help'       => payamito_dynamic_text('pattern_active_help'),
							'class'      => 'pattern_background',

						],

						[

							'id'         => $user_type . "_" . $slug . "_p",
							'type'       => 'text',
							'title'      => payamito_dynamic_text('pattern_ID_title'),
							'desc'       => payamito_dynamic_text('pattern_ID_desc'),
							'help'       => payamito_dynamic_text('pattern_ID_help'),
							'class'      => 'pattern_background',
							'dependency' => [
								$user_type . "_" . $slug . "_active_p" . "|" . $user_type . "_" . $slug . "_active",
								'==|==',
								'true|true',
							],
						],
						$this->option_set_pattern($user_type, $slug, $dependency),
						[
							'id'          => $user_type . "_" . $slug . "_text",
							'title'       => payamito_dynamic_text('send_content_title'),
							'desc'        => payamito_dynamic_text('send_content_desc'),
							'help'        => payamito_dynamic_text('send_content_help'),
							'placeholder' => esc_html__('مشتری گرامی سفارش شما با شماره {order_id} با مبلغ سفارش { price } با موفقیت انجام شد.', 'payamito-woocommerce'),
							'type'        => 'textarea',
							'class'       => 'pattern_background',
							'dependency'  => [
								$user_type . "_" . $slug . "_active_p" . "|" . $user_type . "_" . $slug . "_active",
								'!=|==',
								'true|true',
							],
						],
						[
							'type'       => 'callback',
							'dependency' => [
								$user_type . "_" . $slug . "_active_p" . "|" . $user_type . "_" . $slug . "_active",
								'!=|==',
								'true|true',
							],
							'function'   => [$this, 'print_tags_front'],
							'class'      => 'pattern_background',
						],
					],
				],
			],
		];
	}

	public function print_tags_front()
	{
		echo "<h3 class='payamito-tags payamito-woocommerce-open-modal' >" . esc_html__('Tags', 'payamito-woocommerce') . "</h3>";
	}
}
