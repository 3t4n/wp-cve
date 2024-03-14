<?php

namespace Bookit\Classes\Admin;

use Bookit\Classes\Base\AddonsFactory;
use Bookit\Classes\Base\Plugin;
use Bookit\Classes\Database\Categories;
use Bookit\Classes\Database\Services;
use Bookit\Classes\Database\Staff;
use Bookit\Classes\Template;
use Bookit\Classes\Vendor\Payments;
use Bookit\Helpers\AddonHelper;
use Bookit\Helpers\CleanHelper;
use Bookit\Helpers\FreemiusHelper;
use Bookit\Helpers\MailTemplateHelper;
use Bookit\Helpers\TimeSlotHelper;

class SettingsController extends DashboardController {

	public static $default_currency           = 'usd';
	public static $default_sender_name        = '';
	public static $default_sender_email       = '';
	public static $default_view_type          = 'default';
	public static $settings_key               = 'bookit_settings';
	public static $time_slot_default_duration = 15;
	public static $calendar_view_types        = array( 'default', 'step_by_step' );
	public static $temp_bookit_pro_slug       = 'bookit-pro/bookit-pro.php';

	private static function getCleanRules() {
		return array(
			'booking_type'                      => array( 'type' => 'strval' ),
			'theme'                             => array( 'type' => 'strval' ),
			'sender_name'                       => array( 'type' => 'strval' ),
			'sender_email'                      => array( 'type' => 'strval' ),
			'hide_header_titles'                => array( 'type' => 'strval' ),
			'currency_symbol'                   => array( 'type' => 'strval' ),
			'currency_position'                 => array( 'type' => 'strval' ),
			'thousands_separator'               => array( 'type' => 'strval' ),
			'decimals_separator'                => array( 'type' => 'strval' ),
			'decimals_number'                   => array( 'type' => 'intval' ),
			'custom_colors_enabled'             => array( 'type' => 'strval' ),
			'hide_from_for_equal_service_price' => array( 'type' => 'strval' ),
			'currency'                          => array(
				'function' => array(
					'custom' => false,
					'name'   => 'strtolower',
				),
			),
			'payments'                          => array(
				'function' => array(
					'custom' => true,
					'name'   => 'custom_sanitize_json',
				),
			),
			'emails'                            => array(
				'function' => array(
					'custom' => true,
					'name'   => 'custom_sanitize_json',
				),
			),
			'custom_colors'                     => array(
				'function' => array(
					'custom' => true,
					'name'   => 'custom_sanitize_json',
				),
			),
		);
	}

	/**
	 * Display Rendered Template
	 * @return bool|string
	 */
	public static function render() {
		self::enqueue_styles_scripts();

		$installedAddons = FreemiusHelper::get_installed_addons();
		$existAddons     = AddonsFactory::getExistAddonsList( array_column( $installedAddons, 'name' ) );

		return Template::load_template(
			'dashboard/bookit-settings',
			array(
				'settings'              => self::get_settings(),
				'addons'                => array_merge( $installedAddons, $existAddons ),
				'pro_disabled'          => bookit_pro_features_disabled(), //todo remove
				'pro_installed'         => AddonHelper::isProPaymentsInstalled(),
				'woocommerce_enabled'   => class_exists( 'WooCommerce' ) ? 'true' : 'false', //todo remove
				'woocommerce_products'  => self::bookit_woocommerce_get_all_products(), //todo remove
				'categories'            => Categories::get_all(),
				'services'              => Services::get_all(),
				'staff'                 => Staff::get_all(),
				'currencies'            => Payments::get_currency_list(),
				'time_slot_options'     => TimeSlotHelper::TIME_SLOT_POSSIBLE_VALUES,
				'calendar_view_options' => self::$calendar_view_types,
			),
			true
		);
	}

	/**
	 * Validate data
	 */
	public static function validate( $data ) {
		$errors = array();

		$is_currency = array_search( strtoupper( $data['currency'] ), array_column( Payments::get_currency_list(), 'value' ) );

		if ( strlen( $data['currency'] ) != 3 || false === $is_currency ) {
			$errors['currency'] = esc_html__( 'Wrong currency value', 'bookit' );
		}

		if ( count( $errors ) > 0 ) {
			wp_send_json_error(
				array(
					'errors'  => $errors,
					'message' => __( 'Error occurred!', 'bookit' ),
				)
			);
		}
	}

	/**
	 * Save Settings
	 */
	public static function save() {
		check_ajax_referer( 'bookit_save_settings', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$data = CleanHelper::cleanData( $_POST, self::getCleanRules() );
		self::validate( $data );

		if ( ! empty( $data ) ) {

			unset( $data['nonce'] );

			do_action( 'bookit_before_update_setting', $data );

			self::save_settings( $data );
			/** Rewrite email templates to WPML strings if WPML installed */
			MailTemplateHelper::registerTemplateDataToWPMLStrings();

			wp_send_json_success( array( 'message' => __( 'Settings Saved!', 'bookit' ) ) );
		}

		wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
	}

	/**
	 * Save Default Settings
	 * @return boolean
	 */
	public static function save_default_settings() {
		if ( empty( (array) self::get_settings() ) ) {
			$settings = array(
				'booking_type'                      => 'registered',
				'calendar_view'                     => self::$default_view_type,
				'time_slot_duration'                => static::$time_slot_default_duration,
				'hide_header_titles'                => false,
				'clean_all_on_delete'               => false,
				'currency'                          => static::$default_currency,
				'currency_symbol'                   => '$',
				'currency_position'                 => 'left',
				'thousands_separator'               => ',',
				'decimals_separator'                => '.',
				'decimals_number'                   => 2,
				'custom_colors_enabled'             => false,
				'hide_from_for_equal_service_price' => false,
				'is_step_by_step_view'              => false,
				'sender_name'                       => static::$default_sender_name,
				'sender_email'                      => static::$default_sender_email,
				'custom_colors'                     => array(
					'base_color'      => '#006666',
					'base_bg_color'   => '#f0f8f8',
					'highlight_color' => '#ffd400',
					'white_color'     => '#ffffff',
					'dark_color'      => '#272727',
				),
				'payments'                          => array(
					'locally'     => array( 'enabled' => true ),
					'paypal'      => array( 'enabled' => false ),
					'stripe'      => array( 'enabled' => false ),
					'woocommerce' => array( 'enabled' => false ),
				),
				'emails'                            => MailTemplateHelper::getTemplates(),
			);

			return self::save_settings( $settings );
		}

		return true;
	}

	/**
	 * Get Settings
	 * @return mixed
	 */
	public static function get_settings() {
		return get_option( self::$settings_key, (object) array() );
	}

	/**
	 * Save Settings
	 * @param $settings
	 * @return mixed
	 */
	public static function save_settings( $settings ) {
		update_option( self::$settings_key, $settings, self::getCleanRules() );
	}

	/**
	 * Get All Products
	 * @return array
	 */
	////todo remove
	public static function bookit_woocommerce_get_all_products() {
		$products     = array();
		$paymentAddon = AddonHelper::getAddonDataByName( AddonHelper::$paymentAddon );
		if ( ( bookit_pro_active() || $paymentAddon['isCanUse'] ) && class_exists( 'WooCommerce' ) ) {
			$args         = array(
				'post_type'      => 'product',
				'posts_per_page' => -1,
			);
			$all_products = get_posts( $args );

			foreach ( $all_products as $key => $product ) {
				$products[ $key ]['id']    = $product->ID;
				$products[ $key ]['title'] = $product->post_title;
			}
		}

		return $products;
	}

	public static function bookit_load_setting_icon() {
		check_ajax_referer( 'bookit_load_icon', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		if ( ! ( is_array( $_POST ) && is_array( $_FILES ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			wp_send_json_error(
				array(
					'errors'  => array( 'woocommerce_icon' => __( 'No data', 'bookit' ) ),
					'message' => __( 'Error occurred!', 'bookit' ),
				)
			);
		}

		if ( empty( $_FILES['file'] ) ) {
			wp_send_json_error(
				array(
					'errors'  => array( 'woocommerce_icon' => __( 'File is empty', 'bookit' ) ),
					'message' => __( 'Error occurred!', 'bookit' ),
				)
			);
		}

		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$file_info = wp_handle_upload( $_FILES['file'], array( 'test_form' => false ) );
		/** save  */
		$settings = self::get_settings();
		$settings['payments']['woocommerce']['custom_icon'] = $file_info['url'];
		self::save_settings( $settings );

		wp_send_json_success(
			array(
				'message'  => __( 'Icon Uploaded!', 'bookit' ),
				'icon_url' => $file_info['url'],
			)
		);
	}

	public static function bookit_remove_icon() {
		check_ajax_referer( 'bookit_load_icon', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		/** save  */
		$settings = self::get_settings();
		$settings['payments']['woocommerce']['custom_icon'] = '';
		self::save_settings( $settings );

		wp_send_json_success( array( 'message' => __( 'Icon Removed!', 'bookit' ) ) );
	}

	/** remove duplicate menu items for old bookit pro */
	public static function removeBookitProFreemiusSubMenuDuplicate() {
		$installedPlugins = AddonHelper::getInstalledPluginBySlug( self::$temp_bookit_pro_slug );
		if ( empty( $installedPlugins ) || ! is_plugin_active( self::$temp_bookit_pro_slug )
			|| ! version_compare( $installedPlugins['Version'], '2.0.0', '<' ) ) {
			return;
		}
		add_action( 'admin_head', array( self::class, 'remove_bookit_pro_double_submenu_pages' ) );
	}

	/** remove contact us menu item for free plugin */
	public static function removeBookitContactUsForFreeVersion() {
		if ( function_exists( 'bookit_fs' ) ) {
			$bookit_fs = bookit_fs();
			$addons    = $bookit_fs->get_installed_addons();
			if ( empty( $addons ) ) {
				add_action(
					'admin_head',
					array( self::class, 'remove_fm_contact_us_submenu_for_free' )
				);
				add_action(
					'init',
					array( self::class, 'redirect_to_main_page' )
				);
			}
		}
	}

	/** redirct to main page from contact  */
	public static function redirect_to_main_page() {
		$freemiusContactSlug = 'bookit-contact';
		if ( isset( $_GET['page'] ) && $_GET['page'] == $freemiusContactSlug ) {
			wp_redirect( home_url() . '/wp-admin/admin.php?page=bookit' );
			exit();
		}
	}

	/**
	 *  Remove contact us for free version
	 */
	public static function remove_fm_contact_us_submenu_for_free() {
		$freemiusContactSlug = 'bookit-contact';
		remove_submenu_page( 'bookit', $freemiusContactSlug );
	}
	/**
	 *  If bookit pro is installed remove
	 *  Contact Us and Account menu dublicates
	 */
	public static function remove_bookit_pro_double_submenu_pages() {
		$freemiusAccountSlug = 'bookit-account';
		remove_submenu_page( 'bookit', $freemiusAccountSlug );
	}
}
