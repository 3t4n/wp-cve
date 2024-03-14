<?php

namespace Codemanas\InactiveLogout;

/**
 * Class with a few helpers
 *
 * @package inactive-logout
 */
class Helpers {

	public static $message;

	/**
	 * Convert seconds to minutes.
	 *
	 * @param  int  $value  Number of seconds.
	 *
	 * @return string
	 */
	public static function convertToMinutes( $value ) {
		$minutes = floor( $value / 60 );

		return $minutes . ' ' . esc_html__( 'Minute(s)', 'inactive-logout' );
	}

	/**
	 * Get all roles.
	 *
	 * @return array List of roles.
	 */
	public static function getAllRoles() {
		$result = array();

		$roles = get_editable_roles();
		foreach ( $roles as $role => $role_name ) {
			$result[ $role ] = $role_name['name'];
		}

		return $result;
	}

	/**
	 * Get All Pages and Posts
	 *
	 * @return array
	 * @since  1.2.0
	 */
	public static function getAllPostsPages() {
		$result = array();
		$pages  = get_posts( array(
			'order'          => 'ASC',
			'posts_per_page' => - 1,
			'post_type'      => apply_filters( 'ina_free_get_custom_post_types', array( 'post', 'page' ) ),
			'post_status'    => 'publish',
		) );

		if ( ! empty( $pages ) ) {
			foreach ( $pages as $page ) {
				$result[ $page->post_type ][] = array(
					'ID'        => $page->ID,
					'title'     => $page->post_title,
					'permalink' => get_the_permalink( $page->ID ),
					'post_type' => $page->post_type,
				);
			}
		}

		return $result;
	}

	/**
	 * Check role is available in settings for multi-user.
	 *
	 * @param  null|string  $role  Name of role, default is null.
	 *
	 * @return bool Returns true if passed role is available, Otherwise false.
	 */
	public static function CheckRoleForMultiUser( $role = null ) {
		$selected = false;
		if ( ! empty( $role ) ) {
			$ina_multiuser_settings = self::get_option( '__ina_multiusers_settings' );
			if ( ! empty( $ina_multiuser_settings ) ) {
				foreach ( $ina_multiuser_settings as $ina_multiuser_setting ) {
					if ( in_array( $role, $ina_multiuser_setting, true ) ) {
						$selected = true;
					}
				}
			}
		}

		return $selected;
	}

	/**
	 * Get inactive logout settings
	 *
	 * @return \stdClass
	 */
	public static function getInactiveSettingsData() {
		$logout_time            = Helpers::get_option( '__ina_logout_time' );
		$countdown_time         = Helpers::get_option( '__ina_countdown_timeout' );
		$disable_countdown      = Helpers::get_option( '__ina_disable_countdown' );
		$warn_only              = Helpers::get_option( '__ina_warn_message_enabled' );
		$concurrent_login       = Helpers::get_option( '__ina_concurrent_login' );
		$ina_enable_redirect    = Helpers::get_option( '__ina_enable_redirect' );
		$ina_redirect_page_link = Helpers::get_option( '__ina_redirect_page_link' );
		$automatic_redirect     = Helpers::get_option( '__ina_disable_automatic_redirect_on_logout' );
		$debugger               = Helpers::get_option( '__ina_enable_debugger' );

		$settings                         = new \stdClass();
		$settings->logout_time            = ! empty( $logout_time ) ? $logout_time : 15 * 60;
		$settings->prompt_countdown_timer = ! empty( $countdown_time ) ? $countdown_time : 10;
		$settings->disable_prompt_timer   = ! empty( $disable_countdown );
		$settings->warn_only_enable       = ! empty( $warn_only );
		$settings->concurrent_enabled     = ! empty( $concurrent_login );
		$settings->enabled_redirect       = ! empty( $ina_enable_redirect );
		$settings->redirect_page_link     = ! empty( $ina_redirect_page_link ) ? $ina_redirect_page_link : false;
		$settings->automatic_redirect     = ! empty( $automatic_redirect );
		$settings->debugger               = ! empty( $debugger );

		$enabledMultiUser = Helpers::get_option( '__ina_enable_timeout_multiusers' );
		if ( $enabledMultiUser ) {
			global $current_user;
			$multiSetting      = false;
			$multiUserSettings = Helpers::get_option( '__ina_multiusers_settings' );
			if ( ! empty( $multiUserSettings ) && ! empty( $current_user->roles ) ) {
				foreach ( $multiUserSettings as $ina_multiuser_setting ) {
					if ( in_array( $ina_multiuser_setting['role'], $current_user->roles, true ) ) {
						$multiUserSettings = $ina_multiuser_setting;
						$multiSetting      = true;
						break;
					}
				}
			}

			if ( $multiSetting ) {
				$settings->advanced = $multiUserSettings;
			}
		}

		return $settings;
	}

	/**
	 * Check if pro version is active
	 *
	 * @return bool
	 */
	public static function is_pro_version_active() {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( is_plugin_active( 'inactive-logout-addon/inactive-logout-addon.php' ) ) {
			return true;
		} else {
			return false;
		}
	}

	public static function show_advanced_enable_notification() {
		$ina_multiuser_timeout_enabled = self::get_option( '__ina_enable_timeout_multiusers' );
		if ( ! empty( $ina_multiuser_timeout_enabled ) ) {
			?>
            <div id="message" class="notice notice-warning">
                <p><?php esc_html_e( 'Is inactive logout or few functionalities not working for you ? Might be because you have added this user role in Role Based tab ?', 'inactive-logout' ); ?></p>
            </div>
			<?php
		}
	}

	/**
	 * Get Overridden multisite setting - Just here for backwards compatibility.
	 *
	 * Will be removed in next major release.
	 *
	 * @param $key
	 *
	 * @return mixed|void
	 * @deprecated since 3.1.1
	 */
	public static function get_overrided_option( $key ) {
		if ( is_multisite() ) {
			$network_id = get_main_network_id();
			$override   = get_network_option( $network_id, '__ina_overrideby_multisite_setting' ) ? true : false;
			if ( $override ) {
				$result = get_network_option( $network_id, $key );
			} else {
				$result = self::get_option( $key );
			}
		} else {
			$result = self::get_option( $key );
		}

		return $result;
	}

	/**
	 * Get Option based on multisite or only one site
	 *
	 * @param $key
	 *
	 * @return mixed|void
	 */
	public static function get_option( $key ) {
		if ( is_multisite() ) {
			$network_id = get_main_network_id();
			$override   = get_network_option( $network_id, '__ina_overrideby_multisite_setting' );
			if ( ! empty( $override ) ) {
				return get_network_option( $network_id, $key );
			}

			if ( is_main_site() || is_network_admin() ) {
				return get_site_option( $key );
			} else {
				return get_option( $key );
			}
		}

		return get_option( $key );
	}

	/**
	 * Update option
	 *
	 * @param $key
	 * @param $value
	 */
	public static function update_option( $key, $value ) {
		if ( is_network_admin() && is_multisite() || ( get_current_blog_id() == get_main_network_id() ) ) {
			update_site_option( $key, $value );
		} else {
			update_option( $key, $value );
		}
	}

	/**
	 * Get Logout redirect page link.
	 *
	 * @param  bool  $settings
	 *
	 * @return false|mixed|string
	 */
	public static function getLogoutRedirectPage( $settings = false ) {
		if ( empty( $settings ) ) {
			$settings = Helpers::getInactiveSettingsData();
		}

		if ( ! empty( $settings->advanced ) && ! empty( $settings->advanced['redirect_page'] ) ) {
			$redirect_link = get_the_permalink( $settings->advanced['redirect_page'] );

			return ! empty( $redirect_link ) ? $redirect_link : $settings->advanced['redirect_page'];
		} elseif ( ! empty( $settings->enabled_redirect ) ) {
			if ( 'custom-page-redirect' == $settings->redirect_page_link ) {
				$ina_redirect_page_link = Helpers::get_option( '__ina_custom_redirect_text_field' );
				$redirect_link          = $ina_redirect_page_link;
			} else {
				$redirect_link = get_the_permalink( $settings->redirect_page_link );
			}
		}

		return ! empty( $redirect_link ) ? $redirect_link : false;
	}

	/**
	 * Get the admin notice message
	 *
	 * @return mixed|null
	 */
	public static function getMessage() {
		$session_message = Helpers::get_option( '__ina_saved_options' );
		if ( $session_message ) {
			Helpers::update_option( '__ina_saved_options', '' );

			return $session_message;
		}

		return apply_filters( 'ina_admin_get_message', self::$message );
	}

	/**
	 * Set admin notice message
	 *
	 * @param $message
	 *
	 * @return void
	 */
	public static function set_message( $message ) {
		self::$message = $message;
	}
}