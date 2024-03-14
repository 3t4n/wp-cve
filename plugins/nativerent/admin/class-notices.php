<?php

namespace NativeRent\Admin;

use function add_action;
use function defined;
use function esc_attr;
use function esc_html;
use function esc_html__;
use function is_string;
use function menu_page_url;
use function nativerent_cache_active;
use function nativerent_clear_cache_possible;
use function sprintf;

use const NATIVERENT_PARAM_AUTH;

defined( 'ABSPATH' ) || exit;

/**
 * Nativerent Main Settings class
 */
class Notices {
	/**
	 * Notices
	 *
	 * @var array<string, array{class-string, string}|string>
	 */
	private static $notices
		= array(
			'success'                      => array( __CLASS__, 'get_success_notice' ),
			'error'                        => array( __CLASS__, 'get_error_notice' ),
			'advanced_error'               => array( __CLASS__, 'get_error_advanced_notice' ),
			'no_rights_error'              => array( __CLASS__, 'get_error_no_rights_notice' ),
			'nonce_expired'                => array( __CLASS__, 'get_refresh_page_notice' ),
			'authentication_needed'        => array( __CLASS__, 'get_authentication_notice' ),
			'refresh_authentication'       => array( __CLASS__, 'get_refresh_authentication_notice' ),
			'clear_cache'                  => array( __CLASS__, 'get_clear_cache_notice' ),
			'clear_cache_link'             => array( __CLASS__, 'get_clear_cache_with_link_notice' ),
			'clear_cache_adjust'           => array( __CLASS__, 'get_clear_cache_notice_after_settings_save' ),
			'clear_cache_adjust_link'      => array( __CLASS__, 'get_clear_cache_with_link_notice_after_settings_save' ),
			'clear_cache_deactivated'      => array( __CLASS__, 'get_clear_cache_notice_after_deactivation' ),
			'clear_cache_deactivated_link' => array( __CLASS__, 'get_clear_cache_with_link_notice_after_deactivation' ),
			'cache_is_cleared'             => array( __CLASS__, 'get_cache_is_cleared_notice' ),
			'site_on_moderation'           => array( __CLASS__, 'get_site_on_moderation' ),
		);

	/**
	 * Add notice
	 *
	 * @param  string $notice  Notice name to add.
	 */
	public static function add_notice( $notice = '' ) {
		if ( ! empty( self::$notices[ $notice ] ) ) {
			$action = self::$notices[ $notice ];
			add_action( 'admin_notices', is_string( $action ) ? array( __CLASS__, $action ) : $action );
		}
	}

	/**
	 * Print notice
	 *
	 * @param  string $text         to the Notice.
	 * @param  string $type         Notice type.
	 * @param  string $dismissible  Dismiss-ability.
	 */
	private static function print_notice( $text = '', $type = 'error', $dismissible = 'is-dismissible' ) {
		$template = '<div class="notice notice-' . $type . ' ' . $dismissible . '"><p>%s</p></div>';

		echo sprintf( $template, $text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Success notice
	 *
	 * @return void
	 */
	public static function get_success_notice() {
		self::print_notice(
			esc_html__( 'Успешно!', 'nativerent' ),
			'success'
		);
	}

	/**
	 * Error notice
	 *
	 * @return void
	 */
	public static function get_error_notice() {
		self::print_notice(
			esc_html__(
				'Возникла ошибка. Пожалуйста, попробуйте снова.',
				'nativerent'
			)
		);
	}

	/**
	 * Error notice
	 *
	 * @return void
	 */
	public static function get_error_advanced_notice() {
		self::print_notice(
			esc_html__(
				'Возникла ошибка. Пожалуйста, попробуйте снова. Если ошибка продолжит возникать, отключите синхронный режим и свяжитесь с техподдержкой Native Rent.',
				'nativerent'
			)
		);
	}

	/**
	 * Error No rights notice
	 *
	 * @return void
	 */
	public static function get_error_no_rights_notice() {
		self::print_notice(
			esc_html__(
				'Недостаточно прав для удаления файла кэша.',
				'nativerent'
			)
		);
	}

	/**
	 * Notification to refresh page
	 *
	 * @return void
	 */
	public static function get_refresh_page_notice() {
		self::print_notice(
			esc_html__(
				'Страница устарела. Пожалуйста, попробуйте снова.',
				'nativerent'
			)
		);
	}

	/**
	 *  Notification to authentication
	 *
	 * @return void
	 */
	public static function get_authentication_notice() {
		self::print_notice(
			sprintf(
				'Для того чтобы заработал показ рекламы,
				необходимо <a href="%s">подключить</a> сайт к Native Rent.',
				esc_html( menu_page_url( 'nativerent', false ) )
			),
			'info',
			''
		);
	}

	/**
	 * Notification to fix authentication
	 *
	 * @return void
	 */
	public static function get_refresh_authentication_notice() {
		self::print_notice(
			sprintf(
				'Плагин Native Rent работает некорректно.
				Для возобновления работы <a href="%s">авторизуйтесь</a> в плагине повторно.',
				esc_attr( menu_page_url( 'nativerent', false ) . '&' . NATIVERENT_PARAM_AUTH . '=1' )
			),
			'warning'
		);
	}

	/**
	 * Get clear cache notice
	 *
	 * @return void
	 */
	public static function get_clear_cache_notice() {
		$text = 'Native Rent: ';
		if ( nativerent_cache_active() && nativerent_clear_cache_possible() ) {
			$text .= sprintf(
				'Для корректной работы необходимо сбросить кэш. &nbsp; %s',
				Cache_Actions::$button->add_button()
			);
		} else {
			$text .= 'Если на сайте настроено кэширование, то необходимо сбросить кэш';
		}

		self::print_warning( $text );
	}

	/**
	 * Get clear cache notice
	 *
	 * @return void
	 * @deprecated
	 */
	public static function get_clear_cache_with_link_notice() {
		self::get_clear_cache_notice();
	}

	/**
	 * Get clear cache notice
	 *
	 * @return void
	 */
	public static function get_clear_cache_notice_after_settings_save() {
		$text = 'Native Rent: ';
		if ( nativerent_cache_active() && nativerent_clear_cache_possible() ) {
			$text .= sprintf(
				'Чтобы применить настройки необходимо сбросить кэш. &nbsp; %s',
				Cache_Actions::$button->add_button()
			);
		} else {
			$text .= 'Если на сайте настроено кэширование, то для применения настроек сбросьте кэш.';
		}

		self::print_warning( $text );
	}

	/**
	 * Get clear cache notice
	 *
	 * @return void
	 * @deprecated
	 */
	public static function get_clear_cache_with_link_notice_after_settings_save() {
		self::get_clear_cache_notice_after_settings_save();
	}

	/**
	 * Cache is cleared notice
	 *
	 * @return void
	 */
	public static function get_cache_is_cleared_notice() {
		self::print_notice( 'Native Rent: кэш сброшен', 'info' );
	}

	/**
	 * Cache notice after deactivation
	 *
	 * @return void
	 */
	public static function get_clear_cache_notice_after_deactivation() {
		$text = 'Native Rent: ';
		if ( nativerent_cache_active() && nativerent_clear_cache_possible() ) {
			$text .= sprintf(
				'Для отключения плагина необходимо сбросить кэш. &nbsp; %s',
				Cache_Actions::$button->add_button()
			);
		} else {
			$text .= 'Если на сайте настроено кэширование, то для отключения плагина сбросьте кэш.';
		}

		self::print_warning( $text );
	}

	/**
	 * Cache with clear link notice after deactivation
	 *
	 * @return void
	 * @deprecated
	 */
	public static function get_clear_cache_with_link_notice_after_deactivation() {
		self::get_clear_cache_notice_after_deactivation();
	}

	/**
	 * Notice about moderation.
	 *
	 * @return void
	 */
	public static function get_site_on_moderation() {
		self::print_notice(
			esc_html__(
				'Сайт на модерации. После того, как модератор проверит сайт вы получите уведомление на почту.',
				'nativerent'
			),
			'info',
			''
		);
	}

	/**
	 * Print warning with button to dismiss
	 *
	 * @param  string $message  Message to print.
	 */
	private static function print_warning( $message = '' ) {
		if ( ! $message ) {
			return;
		}
		self::print_notice(
			$message,
			'warning ' . Clear_Cache_Button::DISMISS_CACHE_NOTICE
		);
	}
}
