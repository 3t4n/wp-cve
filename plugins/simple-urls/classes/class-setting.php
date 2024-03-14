<?php
/**
 * Declare class Setting
 *
 * @package Setting
 */

namespace LassoLite\Classes;

use LassoLite\Admin\Constant;
use LassoLite\Models\Model;
use LassoLiteVendor\Firebase\JWT\JWT;

/**
 * Setting
 */
class Setting {
	/**
	 * Convert WP GET parameters to Lasso GET
	 *
	 * @var array|string $get
	 */
	public $get = array();

	/**
	 * Current page
	 *
	 * @var mixed|string
	 */
	public $current_page = '';

	/**
	 * List of plugins
	 *
	 * @var string $import_sources
	 */
	public static $import_sources = array(
		'earnist/earnist.php',
		'thirstyaffiliates/thirstyaffiliates.php',
		'pretty-link/pretty-link.php',
		'affiliate-link-automation/affiliate_automation.php',
		'aawp/aawp.php',
		'easyazon/easyazon.php',
		'amalinkspro/amalinkspro.php',
		'easy-affiliate-links/easy-affiliate-links.php',
	);

	/**
	 * Setting constructor.
	 */
	public function __construct() {
		$this->get          = Helper::GET();
		$this->current_page = $this->get['page'] ?? '';
	}

	/**
	 * Check whether current page is SURLS page or not.
	 */
	public function is_surls_page() {
		global $pagenow;
		return 'edit.php' === $pagenow && isset( $this->get['post_type'] ) && SIMPLE_URLS_SLUG === $this->get['post_type'] && isset( $this->get['page'] );
	}

	/**
	 * Check whether current page is WP post page
	 */
	public function is_wordpress_post() {
		global $pagenow;

		$action       = $this->get['action'] ?? '';
		$add_new_page = 'post-new.php' === $pagenow;
		$edit_page    = 'post.php' === $pagenow && 'edit' === $action;
		$post_type    = $this->get['post_type'] ?? '';

		if ( ( 'edit.php' === $pagenow || $add_new_page ) && '' === $post_type ) {
			$post_type = 'post';
		} elseif ( $add_new_page ) {
			$post_type = $this->get['post_type'] ?? $post_type;
		} elseif ( $edit_page ) {
			$post_id   = intval( $this->get['post'] ?? 0 );
			$post_type = $post_id > 0 ? get_post_type( $post_id ) : $post_type;
		}

		if ( 'term.php' === $pagenow ) {
			$post_type = '';
		}

		return 'post' === $post_type || 'page' === $post_type;
	}

	/**
	 * Check whether current page is custom post page
	 */
	public function is_custom_post() {
		global $pagenow;

		$action       = $this->get['action'] ?? '';
		$add_new_page = 'post-new.php' === $pagenow;
		$edit_page    = 'post.php' === $pagenow && 'edit' === $action;
		$post_type    = $this->get['post_type'] ?? '';

		if ( ( 'edit.php' === $pagenow || $add_new_page ) && '' === $post_type ) {
			$post_type = 'post';
		} elseif ( $add_new_page ) {
			$post_type = $this->get['post_type'] ?? $post_type;
		} elseif ( $edit_page ) {
			$post_id   = intval( $this->get['post'] ?? 0 );
			$post_type = $post_id > 0 ? get_post_type( $post_id ) : $post_type;
		}

		if ( 'term.php' === $pagenow ) {
			$post_type = '';
		}

		return '' !== $post_type && 'post' !== $post_type && 'page' !== $post_type;
	}

	/**
	 * Update lasso setting to db
	 *
	 * @param string $option_name Option name.
	 * @param string $option_default Option default. Default to null.
	 */
	public static function get_setting( $option_name, $option_default = null ) {
		if ( ! is_string( $option_name ) ) {
			return $option_default;
		}

		$options = self::get_settings();

		return $options[ $option_name ] ?? $option_default;
	}

	/**
	 * Get lasso settings from db
	 */
	public static function get_settings() {
		$options = get_option( 'lassolite_settings', array() );
		if ( ! is_array( $options ) ) {
			$options = array();
		}
		$defaults = Constant::DEFAULT_SETTINGS;

		return wp_parse_args( $options, $defaults );
	}

	/**
	 * Update lasso setting to db
	 *
	 * @param string $option_name Option name.
	 * @param string $option_value Option value.
	 */
	public static function set_setting( $option_name, $option_value ) {
		if ( ! is_string( $option_name ) ) {
			return false;
		}

		$options                 = self::get_settings();
		$options[ $option_name ] = $option_value;

		return update_option( 'lassolite_settings', $options );
	}

	/**
	 * Update lasso settings to db
	 *
	 * @param array $options New options.
	 */
	public static function set_settings( $options ) {
		if ( ! is_array( $options ) ) {
			return false;
		}

		$defaults = self::get_settings();
		$options  = array_merge( $defaults, $options );

		return update_option( 'lassolite_settings', $options );
	}

	/**
	 * Check current page is dashboard page
	 *
	 * @return bool
	 */
	public function is_dashboard_page() {
		return $this->is_surls_page() && Helper::add_prefix_page( Enum::PAGE_DASHBOARD ) === $this->current_page;
	}

	/**
	 * Check current page is setting page
	 *
	 * @return bool
	 */
	public function is_setting_page() {
		$setting_pages = array(
			Helper::add_prefix_page( Enum::PAGE_SETTINGS_GENERAL ),
			Helper::add_prefix_page( Enum::PAGE_SETTINGS_DISPLAY ),
			Helper::add_prefix_page( Enum::PAGE_SETTINGS_AMAZON ),
		);

		return $this->is_surls_page() && in_array( $this->current_page, $setting_pages, true );
	}

	/**
	 * Check current page is setting - display page
	 *
	 * @return bool
	 */
	public function is_setting_display_page() {
		return $this->is_surls_page() && Helper::add_prefix_page( Enum::PAGE_SETTINGS_DISPLAY ) === $this->current_page;
	}

	/**
	 * Check current page is setting - amazon page
	 *
	 * @return bool
	 */
	public function is_setting_amazon_page() {
		return $this->is_surls_page() && Helper::add_prefix_page( Enum::PAGE_SETTINGS_AMAZON ) === $this->current_page;
	}

	/**
	 * Check current page is onboarding
	 *
	 * @return bool
	 */
	public function is_setting_onboarding_page() {
		return $this->is_surls_page() && Helper::add_prefix_page( Enum::PAGE_ONBOARDING ) === $this->current_page;
	}

	/**
	 * Update lasso settings to db
	 * Check current page is setting - display page
	 *
	 * @return bool
	 */
	public function is_setting_general_page() {
		return $this->is_surls_page() && Helper::add_prefix_page( Enum::PAGE_SETTINGS_GENERAL ) === $this->current_page;
	}

	/**
	 * Check current page is import page
	 *
	 * @return bool
	 */
	public function is_import_page() {
		return $this->is_surls_page() && Helper::add_prefix_page( Enum::PAGE_IMPORT ) === $this->current_page;
	}

	/**
	 * Check current page is group detail page
	 *
	 * @return bool
	 */
	public function is_group_detail_page() {
		return $this->is_surls_page() && Helper::add_prefix_page( Enum::PAGE_GROUP_DETAIL ) === $this->current_page;
	}

	/**
	 * Check current page is group detail page
	 *
	 * @return bool
	 */
	public function is_group_page() {
		return $this->is_surls_page() && Helper::add_prefix_page( Enum::PAGE_GROUPS ) === $this->current_page;
	}

	/**
	 * Save support
	 *
	 * @param bool $is_ajax check whether $is_ajax request.
	 */
	public static function save_support( $is_ajax = true ) {
		$post         = Helper::POST();
		$email        = $post['email'] ?? '';
		$is_subscribe = $post['is_subscribe'] ?? '';
		if ( $is_ajax && false === is_email( $email ) ) {
			wp_send_json_success(
				array(
					'success' => false,
					'msg'     => 'Email is invalid.',
				)
			);
		} else {
			$settings = self::get_settings();

			if ( $is_ajax ) {
				$settings[ Enum::IS_SUBSCRIBE ]    = $is_subscribe;
				$settings[ Enum::EMAIL_SUPPORT ]   = $email;
				$settings[ Enum::SUPPORT_ENABLED ] = true;
			} else {
				$email = $settings[ Enum::EMAIL_SUPPORT ];
			}
			$support_enable_time = $settings[ Enum::SUPPORT_ENABLED_TIME ] ?? '';
			$current_date                      = date( 'm/d/Y', time() ); // phpcs:ignore
			if ( ! $is_ajax || empty( $support_enable_time ) || $current_date > $support_enable_time ) {
				global $wp_version;
				$jwt_data = array(
					'email'             => $email,
					'installed_version' => LASSO_LITE_VERSION,
					'datetime'          => gmdate( 'Y-m-d H:i:s' ),
					'site_id'           => Helper::get_option( Constant::SITE_ID_KEY ),
					'install_url'       => site_url(),
					'wordpress_version' => $wp_version,
					'php_version'       => phpversion(),
					'mysql_version'     => Model::get_wpdb()->db_version(),
					'is_classic_editor' => Helper::is_classic_editor() ? 1 : 0,
				);

				$jwt          = JWT::encode( $jwt_data, Constant::JWT_SECRET_KEY, 'HS256' );
				$data['data'] = $jwt;
				$response     = Helper::send_request( 'post', Constant::LASSO_LINK . 'lasso-lite/enable-support', $data );
				$is_succeed   = boolval( $response['response']->succeed );
				if ( $is_succeed ) {
					$user_hash                              = $response['response']->user_hash;
					$settings[ Enum::SUPPORT_ENABLED_TIME ] = date( 'm/d/Y', time() ); // phpcs:ignore
					$settings[ Enum::USER_HASH ]            = $user_hash;
				}
			}

			self::set_settings( $settings );
			if ( $is_ajax ) {
				wp_send_json_success(
					array(
						'success' => true,
					)
				);
			}
		}
	}

	/**
	 * Check plugins for importing
	 *
	 * @return string
	 */
	public function check_plugins_for_import() {
		// ? import plugin check
		$plugins_for_import     = $this->get_import_sources();
		$setting_page_link      = '';
		$plugins_for_import_txt = '';

		if ( ! empty( $plugins_for_import ) ) {
			$verb                   = count( $plugins_for_import ) > 1 ? 'are' : 'is';
			$plugins_for_import_txt = implode( ', ', $plugins_for_import ) . ' ' . $verb;
		}

		return $plugins_for_import_txt;
	}

	/**
	 * Check whether the plugins are activated
	 *
	 * @return array|false
	 */
	public static function get_import_sources() {
		$plugin_list = self::$import_sources;

		$result          = array();
		$plugin_path_abs = dirname( SIMPLE_URLS_DIR );

		try {
			foreach ( $plugin_list as $plugin ) {
				$full_plugin_path = $plugin_path_abs . '/' . $plugin;
				if ( ! file_exists( $full_plugin_path ) ) {
					continue;
				}

				// ? Check plugin is activated
				if ( is_plugin_active( $plugin ) ) {
					$plugin_name    = self::get_plugin_name( $plugin );
					$key            = $plugin;
					$result[ $key ] = $plugin_name;
				}
			}
		} catch ( \Exception $e ) {
			return false;
		}

		return $result;
	}

	/**
	 * Get plugin name
	 *
	 * @param string $plugin Plugin file path.
	 */
	public static function get_plugin_name( $plugin ) {
		$plugin_name = 'The plugin ' . $plugin . ' has been deleted.';
		$plugin_path = WP_PLUGIN_DIR . '/' . $plugin;
		if ( file_exists( $plugin_path ) ) {
			$plugin_data = get_plugin_data( $plugin_path );
			$plugin_name = $plugin_data['Name'];
		}

		return $plugin_name;
	}
}
