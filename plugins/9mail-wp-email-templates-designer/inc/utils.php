<?php

namespace EmTmplF\Inc;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Utils {

	protected static $instance = null;

	public static $email_ids;

	private function __construct() {
	}

	public static function init() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function get_email_ids() {
		return apply_filters( 'emtmpl_email_types', [
			'default' => esc_html__( 'Default', '9mail-wp-email-templates-designer' ),
//			'moderation_notify_new_comment' => esc_html__( 'Moderation notify new comment', '9mail-wp-email-templates-designer' ),
		] );
	}

	public static function shortcodes() {
		return [
			'{admin_email}'  => get_bloginfo( 'admin_email' ),
			'{home_url}'     => home_url(),
			'{site_title}'   => get_bloginfo( 'name' ),
			'{current_year}' => date_i18n( 'Y', current_time( 'U' ) ),
		];
	}

	public static function register_email_type() {
		$r                   = [];
		$register_email_type = self::register_3rd_email_type();
		if ( ! empty( $register_email_type ) && is_array( $register_email_type ) ) {
			foreach ( $register_email_type as $id => $data ) {
				if ( empty( $data['name'] ) ) {
					continue;
				}
				$r[ $id ] = $data['name'];
			}
		}

		return $r;
	}

	public static function get_accept_elements_data() {
		$basic_elements = apply_filters( 'emtmpl_register_element_for_all_email_type', [
			'layout/grid1cols',
			'layout/grid2cols',
			'layout/grid3cols',
			'layout/grid4cols',
			'html/text',
			'html/image',
			'html/button',
			'html/post',
			'html/contact',
			'html/menu',
			'html/social',
			'html/divider',
			'html/spacer',
		] );

		$emails = [
			'default' => [ 'html/recover_content' ],
		];

		foreach ( $emails as $type => $el ) {
			$emails[ $type ] = array_merge( $basic_elements, $el );
		}

		$register_email_type = self::register_3rd_email_type();
		if ( ! empty( $register_email_type ) && is_array( $register_email_type ) ) {
			foreach ( $register_email_type as $id => $data ) {
				$accept        = empty( $data['accept_elements'] ) ? [] : $data['accept_elements'];
				$emails[ $id ] = array_merge( $basic_elements, $accept );
			}
		}

		return $emails;
	}

	public static function get_hide_rules_data() {
		$r = [ 'default' => [ 'country' ] ];

		$register_email_type = self::register_3rd_email_type();
		if ( ! empty( $register_email_type ) && is_array( $register_email_type ) ) {
			foreach ( $register_email_type as $id => $data ) {
				if ( empty( $data['hide_rules'] ) ) {
					continue;
				}
				$r[ $id ] = $data['hide_rules'];
			}
		}

		return $r;
	}

	public static function register_3rd_email_type() {
		return apply_filters( 'emtmpl_register_email_type', [] );
	}

	public static function register_shortcode_for_builder() {
		return apply_filters( 'emtmpl_live_edit_shortcodes', [] );
	}

	public static function get_register_shortcode_for_builder() {
		$result = [];
		$scs    = self::register_shortcode_for_builder();

		if ( ! empty( $scs ) && is_array( $scs ) ) {
			foreach ( $scs as $key => $sc ) {
				if ( ! is_array( $sc ) ) {
					continue;
				}
				$result = array_merge( $result, array_keys( $sc ) );
			}
		}

		return $result;
	}

	public static function get_register_shortcode_for_text_editor() {
		$result = [];

		$email_types = self::register_email_type();
		$scs         = self::register_shortcode_for_builder();

		if ( ! empty( $email_types ) && is_array( $email_types ) ) {
			foreach ( $email_types as $key => $name ) {
				$sc = ! empty( $scs[ $key ] ) ? $scs[ $key ] : '';
				if ( ! $sc || ! is_array( $sc ) ) {
					continue;
				}
				$menu = [];
				foreach ( $sc as $text => $value ) {
					if ( ! $text ) {
						continue;
					}
					$menu[] = [ 'text' => $text, 'value' => $text ];
				}
				$result[ $key ] = [ 'text' => $name, 'menu' => $menu ];
			}
		}

		return $result;
	}

	public static function get_register_shortcode_for_replace() {
		$result = [];

		$scs = self::register_shortcode_for_builder();
		if ( ! empty( $scs ) && is_array( $scs ) ) {
			foreach ( $scs as $key => $sc ) {
				$result = array_merge( $result, $sc );
			}
		}

		return $result;
	}

	public static function get_admin_bar_stt() {
		return get_option( 'emtmpl_admin_bar_stt' );
	}

	public static function default_shortcode_for_replace() {
		return [
			'{admin_email}' => get_option( 'admin_email' ),
			'{site_title}'  => get_bloginfo( 'name' ),
			'{site_url}'    => site_url(),
			'{home_url}'    => home_url(),

			'{user_login}'    => '',
			'{user_password}' => '',
			'{current_year}'  => date_i18n( 'Y', current_time( 'U' ) ),
		];
	}

	public static function minify_html( $message ) {
		$replace = [
			'/\>[^\S ]+/s' => '>',     // strip whitespaces after tags, except space
			'/[^\S ]+\</s' => '<',     // strip whitespaces before tags, except space
			'/(\s)+/s'     => '\\1',         // shorten multiple whitespace sequences
//			'/<!--(.|\s)*?-->/' => '' // Remove HTML comments
		];

		return preg_replace( array_keys( $replace ), array_values( $replace ), $message );
	}
}

