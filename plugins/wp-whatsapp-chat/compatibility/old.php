<?php

use QuadLayers\QLWAPP\Models\Box as Models_Box;
use QuadLayers\QLWAPP\Models\Button as Models_Button;
use QuadLayers\QLWAPP\Models\Display as Models_Display;
use QuadLayers\QLWAPP\Models\Scheme as Models_Scheme;
use QuadLayers\QLWAPP\Models\Contacts as Models_Contacts;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

require_once 'class-base.php';
require_once 'class-contact.php';
require_once 'class-display-services.php';

if ( class_exists( 'QLWAPP_PRO' ) ) {
	$old = QLWAPP_PRO::instance();
	remove_action( 'qlwapp_init', array( $old, 'includes' ) );
}

// based on original work from the PHP Laravel framework
if ( ! function_exists( 'str_contains' ) ) {
	function str_contains( $haystack, $needle ) {
		return $needle !== '' && mb_strpos( $haystack, $needle ) !== false;
	}
}

// filter old phone number in wp_options
/**
 * Compatibility with the old version
 */
add_filter(
	'option_qlwapp',
	function ( $qlwapp ) {

		// Replace old phone number with new phone
		if ( isset( $qlwapp['button']['phone'] ) && '12019713894' == $qlwapp['button']['phone'] ) {
			$qlwapp['button']['phone'] = QLWAPP_PHONE_NUMBER;
		}

		if ( isset( $qlwapp['contacts'] ) ) {
			foreach ( $qlwapp['contacts'] as $id => $contact ) {
				if ( isset( $contact['phone'] ) && '12019713894' == $contact['phone'] ) {
					$qlwapp['contacts'][ $id ]['phone'] = QLWAPP_PHONE_NUMBER;
				}
			}
		}

		return $qlwapp;
	}
);

class QLWAPP_Compatibility {

	protected static $instance;

	// fix required header in license tab
	public function settings_header() {
		global $submenu;
		include QLWAPP_PLUGIN_DIR . '/lib/view/backend/pages/parts/header.php';
	}

	// fix settings override with defaults on license save
	public function settings_sanitize( $qlwapp ) {
		$current = get_option( QLWAPP_DOMAIN, array() );

		return wp_parse_args( $qlwapp, $current );
	}

	// required to save license
	public function settings_register() {
		register_setting( sanitize_key( QLWAPP_DOMAIN . '-group' ), sanitize_key( QLWAPP_DOMAIN ), array( $this, 'settings_sanitize' ) );
	}

	public function previous_author( $qlwapp ) {
		// button
		if ( $phone = get_option( 'whatsapp_chat_page' ) ) {
			$qlwapp['button']['phone'] = $phone;
		}
		if ( $text = get_option( 'whatsapp_chat_button' ) ) {
			$qlwapp['button']['text'] = $text;
		}
		if ( get_option( 'whatsapp_chat_powered_by' ) ) {
			$qlwapp['button']['developer'] = 'yes';
		}
		if ( false !== get_option( 'whatsapp_chat_round' ) ) {
			$qlwapp['button']['rounded'] = 'no';
		}
		if ( false !== get_option( 'whatsapp_chat_down' ) ) {
			$vposition                    = get_option( 'whatsapp_chat_down' ) ? 'bottom' : 'middle';
			$hposition                    = get_option( 'whatsapp_chat_left_side' ) ? 'left' : 'right';
			$qlwapp['button']['position'] = "{$vposition}-{$hposition}";
		}
		if ( $message = get_option( 'whatsapp_chat_msg' ) ) {
			$qlwapp['button']['message'] = $message;
		}
		// display
		if ( $mobile = get_option( 'whatsapp_chat_mobile' ) ) {
			$qlwapp['display']['devices'] = 'mobile';
		}
		if ( get_option( 'whatsapp_chat_hide_button' ) ) {
			$qlwapp['display']['devices'] = 'hide';
		}
		if ( get_option( 'whatsapp_chat_hide_post' ) ) {
			$qlwapp['display']['post'] = array( 'none' );
		}
		if ( get_option( 'whatsapp_chat_hide_page' ) ) {
			$qlwapp['display']['page'] = array( 'none' );
		}
		// scheme
		if ( get_option( 'whatsapp_chat_dark' ) ) {
			$qlwapp['scheme']['brand'] = '#075E54';
			$qlwapp['scheme']['text']  = '#ffffff';
		} elseif ( get_option( 'whatsapp_chat_white' ) ) {
			$qlwapp['scheme']['brand'] = '#ffffff';
			$qlwapp['scheme']['text']  = '#075E54';
		} elseif ( false !== get_option( 'whatsapp_chat_white' ) ) {
			$qlwapp['scheme']['brand'] = '#20B038';
			$qlwapp['scheme']['text']  = '#ffffff';
		}

		return $qlwapp;
	}

	public function previous_versions( $qlwapp ) {
		if ( isset( $qlwapp['chat']['response'] ) && ! isset( $qlwapp['box']['response'] ) ) {
			$qlwapp['box']['response'] = $qlwapp['chat']['response'];
		}
		if ( isset( $qlwapp['box']['enable'] ) && ! isset( $qlwapp['button']['box'] ) ) {
			$qlwapp['button']['box'] = $qlwapp['box']['enable'];
		}
		if ( isset( $qlwapp['user']['message'] ) && ! isset( $qlwapp['button']['message'] ) ) {
			$qlwapp['button']['message'] = $qlwapp['user']['message'];
		}
		if ( isset( $qlwapp['button']['rounded'] ) && $qlwapp['button']['rounded'] == 1 ) {
			$qlwapp['button']['rounded'] = 'yes';
		}
		if ( isset( $qlwapp['button']['developer'] ) && $qlwapp['button']['developer'] == 1 ) {
			$qlwapp['button']['developer'] = 'yes';
		}
		// display
		// part free
		if ( isset( $qlwapp['display']['target'] ) ) {

			if ( ! isset( $qlwapp['display']['target']['ids'] ) && isset( $qlwapp['display']['target'][0] ) ) {

				if ( $qlwapp['display']['target'][0] == 'none' ) {
					$qlwapp['display']['target']['ids']     = array(
						'all' => esc_html__( 'All', 'wp-whatsapp-chat' ),
					);
					$qlwapp['display']['target']['include'] = '0';
				} else {
					$qlwapp['display']['target']['ids']     = $qlwapp['display']['target'];
					$qlwapp['display']['target']['include'] = '1';
				}
			}
		}
		// part pro taxonomies
		// rename-re asign
		if ( isset( $qlwapp['display']['category'] ) ) {
			if ( ! isset( $qlwapp['display']['category']['ids'] ) && isset( $qlwapp['display']['category'][0] ) ) {
				$qlwapp['display']['taxonomies']['category']['include'] = '1';
				if ( in_array( 'none', $qlwapp['display']['category'] ) ) {
					$key                                   = array_search( 'none', $qlwapp['display']['category'] );
					$qlwapp['display']['category'][ $key ] = 'all';
					$qlwapp['display']['taxonomies']['category']['include'] = '0';
				}
				$qlwapp['display']['taxonomies']['category']['ids'] = $qlwapp['display']['category'];
			}
		}
		// part PRO
		// rename-re asign o ['entries']
		// PAGE
		if ( isset( $qlwapp['display']['page'] ) ) {
			if ( ! isset( $qlwapp['display']['page']['ids'] ) && isset( $qlwapp['display']['page'][0] ) ) {
				$qlwapp['display']['entries']['page']['include'] = '0';
				if ( in_array( 'none', $qlwapp['display']['page'] ) ) {
					$key                               = array_search( 'none', $qlwapp['display']['page'] );
					$qlwapp['display']['page'][ $key ] = 'all';
					$qlwapp['display']['entries']['page']['include'] = '0';
				}
				$qlwapp['display']['entries']['page']['ids'] = $qlwapp['display']['page'];
			}
		}
		// POST
		if ( isset( $qlwapp['display']['post'] ) ) {
			if ( ! isset( $qlwapp['display']['post']['ids'] ) && isset( $qlwapp['display']['post'][0] ) ) {
				$qlwapp['display']['entries']['post']['include'] = '1';

				if ( in_array( 'none', $qlwapp['display']['post'] ) ) {
					$key                               = array_search( 'none', $qlwapp['display']['post'] );
					$qlwapp['display']['post'][ $key ] = 'all';
					$qlwapp['display']['entries']['post']['include'] = '0';
				}
				$qlwapp['display']['entries']['post']['ids'] = $qlwapp['display']['post'];
			}
		}

		return $qlwapp;
	}

	public function premium_version() {
		global $qlwapp;

		$qlwapp = array();

		// models
		$models_button   = Models_Button::instance();
		$models_box      = Models_Box::instance();
		$models_contacts = Models_Contacts::instance();
		$models_display  = Models_Display::instance();
		$models_scheme   = Models_Scheme::instance();

		// objects
		$qlwapp['button']   = $models_button->get();
		$qlwapp['box']      = $models_box->get();
		$qlwapp['contacts'] = $models_contacts->get_contacts_reorder();
		$qlwapp['display']  = $models_display->get();
		$qlwapp['scheme']   = $models_scheme->get();

		if ( ! is_admin() ) {
			if ( isset( $qlwapp['button']['phone'] ) ) {
				$qlwapp['button']['phone'] = qlwapp_format_phone( $qlwapp['button']['phone'] );
			}
			if ( isset( $qlwapp['button']['timezone'] ) ) {
				$qlwapp['button']['timezone'] = qlwapp_get_timezone_offset( $qlwapp['button']['timezone'] );
			}
		}

		if ( isset( $qlwapp['contacts'] ) ) {
			if ( count( $qlwapp['contacts'] ) ) {
				foreach ( $qlwapp['contacts'] as $id => $c ) {
					$qlwapp['contacts'][ $id ] = wp_parse_args( $c, $models_contacts->get_args() );

					if ( ! is_admin() ) {
						if ( ! empty( $qlwapp['contacts'][ $id ]['phone'] ) ) {
							$qlwapp['contacts'][ $id ]['phone'] = qlwapp_format_phone( $qlwapp['contacts'][ $id ]['phone'] );
						}
						if ( ! empty( $qlwapp['contacts'][ $id ]['timezone'] ) ) {
							$qlwapp['contacts'][ $id ]['timezone'] = qlwapp_get_timezone_offset( $qlwapp['contacts'][ $id ]['timezone'] );
						}
					}
				}
			}
		}
	}

	// Compatibility Since Version 7.2.0
	public function previous_versions_qlwapp( $model_slug ) {
		$qlwapp = get_option( 'qlwapp', array() );
		if ( ! empty( $qlwapp[ $model_slug ] ) ) {
			$model = array();

			foreach ( $qlwapp[ $model_slug ] as $key => $value ) {
				$model[ str_replace( '-', '_', $key ) ] = $value;
				$model[ $key ]                          = $value;
			}

			return $model;
		}
		return null;
	}

	public function __construct() {
		add_filter( 'wp', array( $this, 'premium_version' ) );
		add_action( 'customize_register', array( $this, 'premium_version' ), -10 );
		add_action( 'admin_init', array( $this, 'settings_register' ) );
		add_filter( 'option_qlwapp', array( $this, 'previous_versions' ) );
		add_filter( 'default_option_qlwapp', array( $this, 'previous_author' ), 20 );
		// Compatibility Since Version 7.2.0
		add_filter(
			'default_option_qlwapp_box',
			function () {
				return $this->previous_versions_qlwapp( 'box' );
			}
		);
		add_filter(
			'default_option_qlwapp_button',
			function () {
				return $this->previous_versions_qlwapp( 'button' );
			}
		);
		add_filter(
			'default_option_qlwapp_display',
			function () {
				return $this->previous_versions_qlwapp( 'display' );
			}
		);
		add_filter(
			'default_option_qlwapp_scheme',
			function () {
				return $this->previous_versions_qlwapp( 'scheme' );
			}
		);
		add_filter(
			'default_option_qlwapp_settings',
			function () {
				return $this->previous_versions_qlwapp( 'settings' );
			}
		);
		add_filter(
			'default_option_qlwapp_woocommerce',
			function () {
				return $this->previous_versions_qlwapp( 'woocommerce' );
			}
		);
		add_filter(
			'default_option_qlwapp_contacts',
			function () {
				return $this->previous_versions_qlwapp( 'contacts' );
			}
		);
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

QLWAPP_Compatibility::instance();
