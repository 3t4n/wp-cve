<?php

namespace WPAdminify\Inc\Modules\NotificationBar\Inc;

use WPAdminify\Inc\Modules\NotificationBar\Inc\Settings\General;
use WPAdminify\Inc\Modules\NotificationBar\Inc\Settings\Content;
use WPAdminify\Inc\Modules\NotificationBar\Inc\Settings\Style;
use WPAdminify\Inc\Modules\NotificationBar\Inc\Settings\Display;

/**
 * WP Adminify
 * Module: Notification Bar Customization
 *
 * @author Jewel Theme <support@jeweltheme.com>
 */

if ( ! class_exists( 'Notification_Customize' ) ) {
	class Notification_Customize extends NotificationBarModel {

		public $defaults = [];

		public function __construct() {
			// this should be first so the default values get stored
			$this->notification_bar_options();
			$options = (array) get_option( $this->prefix );
			$options = $this->validation_options( $options );
			parent::__construct( $options );
		}

		protected function get_defaults() {
			return $this->defaults;
		}

		public function validation_options( $options ) {

			$options['show_notif_bar'] = ( !empty($options['show_notif_bar'] ) ) ? wp_validate_boolean( $options['show_notif_bar'] ) : false;
			$options['content_align'] = ( !empty($options['content_align'] ) ) ? sanitize_text_field( $options['content_align'] ) : 'center';
			$options['display_position'] = ( !empty($options['display_position'] ) ) ? sanitize_text_field( $options['display_position'] ) : 'top';
			$options['show_btn_close'] = ( !empty($options['show_btn_close'] ) ) ? wp_validate_boolean( $options['show_btn_close'] ) : true;
			$options['close_btn_text'] = ( !empty($options['close_btn_text'] ) ) ? sanitize_text_field( $options['close_btn_text'] ) : 'x';
			$options['expires_in'] = ( !empty($options['expires_in'] ) ) ? absint( $options['expires_in'] ) : 30;
			$options['padding'] = ( !empty($options['padding'] ) ) ? array_map( 'sanitize_text_field', $options['padding'] ) : '';

			if ( !empty( $options['notif_bar_content_section'] ) ) {
				$options['notif_bar_content_section']['notif_bar_content'] = wp_kses_post( $options['notif_bar_content_section']['notif_bar_content'] );
				$options['notif_bar_content_section']['show_notif_bar_btn'] = wp_validate_boolean( $options['notif_bar_content_section']['show_notif_bar_btn'] );
				$options['notif_bar_content_section']['notif_btn'] = sanitize_text_field( $options['notif_bar_content_section']['notif_btn'] );
				$options['notif_bar_content_section']['notif_btn_url'] = array_map( 'sanitize_text_field', $options['notif_bar_content_section']['notif_btn_url'] );
			}

			$options['bg_color'] = ( !empty($options['bg_color'] ) ) ? sanitize_hex_color( $options['bg_color'] ) : '#000';
			$options['text_color'] = ( !empty($options['text_color'] ) ) ? sanitize_hex_color( $options['text_color'] ) : '#fff';
			$options['btn_color'] = ( !empty($options['btn_color'] ) ) ? sanitize_hex_color( $options['btn_color'] ) : '#d35400';
			$options['btn_text_color'] = ( !empty($options['btn_text_color'] ) ) ? sanitize_hex_color( $options['btn_text_color'] ) : '#fff';
			$options['link_bg_color'] = ( !empty($options['link_bg_color'] ) ) ? sanitize_hex_color( $options['link_bg_color'] ) : '';
			$options['link_color'] = ( !empty($options['link_color'] ) ) ? sanitize_hex_color( $options['link_color'] ) : '#009fdd';

			$options['display_devices'] = ( !empty($options['display_devices'] ) ) ? sanitize_text_field( $options['display_devices'] ) : 'all';

			$options['display_pages'] = ( !empty($options['display_pages'] ) ) ? array_map( 'sanitize_text_field', $options['display_pages'] ) : [ 'homepage', 'posts', 'pages' ];

			return $options;

		}

		public function notification_bar_options() {
			if ( ! class_exists( 'ADMINIFY' ) ) {
				return;
			}
			// Create customize options
			\ADMINIFY::createCustomizeOptions(
				$this->prefix,
				[
					'database'        => 'option',
					'transport'       => 'postMessage',
					'capability'      => 'manage_options',
					'save_defaults'   => true,
					'enqueue_webfont' => true,
					'async_webfont'   => false,
					'output_css'      => true,
				]
			);

			$this->defaults = array_merge( $this->defaults, ( new General() )->get_defaults() );
			$this->defaults = array_merge( $this->defaults, ( new Content() )->get_defaults() );
			$this->defaults = array_merge( $this->defaults, ( new Style() )->get_defaults() );
			$this->defaults = array_merge( $this->defaults, ( new Display() )->get_defaults() );
		}
	}
}
