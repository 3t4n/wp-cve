<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoWcmdFiltersMessages' ) ) {

	class WpssoWcmdFiltersMessages {

		private $p;	// Wpsso class object.
		private $a;	// WpssoWcmd class object.

		/*
		 * Instantiated by WpssoWcmdFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'messages_info'    => 2,
				'messages_tooltip' => 2,
			) );
		}

		public function filter_messages_info( $text, $msg_key ) {

			if ( 0 !== strpos( $msg_key, 'info-wcmd-' ) ) {

				return $text;
			}

			switch ( $msg_key ) {

				case 'info-wcmd-custom-fields':

					$text .= '<blockquote class="top-info">';

					$text .= '<p>' . __( 'Edit enabled metadata is included in the WooCommerce product data metabox when editing a product.', 'wpsso-wc-metadata' ) . '</p>';

					$text .= '<p>' . __( 'Show enabled metadata is included in the WooCommerce product page under the additional information tab.', 'wpsso-wc-metadata' ) . '</p>';

					$text .= '</blockquote>';

					break;
			}

			return $text;
		}

		public function filter_messages_tooltip( $text, $msg_key ) {

			if ( 0 !== strpos( $msg_key, 'tooltip-wcmd_' ) ) {

				return $text;
			}

			switch ( $msg_key ) {

				case ( 0 === strpos( $msg_key, 'tooltip-wcmd_edit_' ) ? true : false ):

					$md_config = WpssoWcmdConfig::get_md_config();
					$md_suffix = substr( $msg_key, strlen( 'tooltip-wcmd_edit_' ) );

					$text .= sprintf( __( 'Enable or disable the %s metadata field, modify the editing field label, the editing field placeholder, and the information label shown in the WooCommerce product page under the additional information tab.', 'wpsso-wc-metadata' ), $md_config[ $md_suffix ][ 'label' ] ) . ' ';

					$opt_cf_key = 'plugin_cf_' . $md_suffix;

					if ( ! empty( $this->p->options[ $opt_cf_key ] ) ) {

						$text .= sprintf( __( 'The editing field value is saved in the WooCommerce product or variation %s custom field name (aka metadata name).', 'wpsso-wc-metadata' ),  '<code>' . $this->p->options[ $opt_cf_key ] . '</code>' );
					}

					break;
			}

			return $text;
		}
	}
}
