<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoWcmdFiltersOptions' ) ) {

	class WpssoWcmdFiltersOptions {

		private $p;	// Wpsso class object.
		private $a;	// WpssoWcmd class object.

		/*
		 * Instantiated by WpssoWcmdFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'get_defaults' => 1,
				'option_type'  => 2,
			) );
		}

		public function filter_get_defaults( array $defs ) {

			$md_config = WpssoWcmdConfig::get_md_config();

			foreach ( $md_config as $md_key => $cfg ) {

				foreach ( $cfg[ 'prefixes' ][ 'defaults' ] as $opt_pre => $opt_val ) {

					$defs[ $opt_pre . '_' . $md_key ] = $opt_val;
				}
			}

			return $defs;
		}

		public function filter_option_type( $type, $base_key ) {

			/*
			 * Return 'not_blank' for enabled WPSSO WCMD metadata custom fields.
			 */
			if ( 0 === strpos( $base_key, 'plugin_cf_' ) ) {

				$md_suffix = substr( $base_key, strlen( 'plugin_cf_' ) );

				if ( ! empty( $this->p->options[ 'wcmd_edit_' . $md_suffix ] ) ) {

					return 'not_blank';
				}
			}

			if ( ! empty( $type ) ) {	// Return early if we already have a type.

				return $type;

			} elseif ( 0 !== strpos( $base_key, 'wcmd_' ) ) {	// Nothing to do.

				return $type;
			}

			switch ( $base_key ) {

				case ( false !== strpos( $base_key, 'wcmd_edit_label_' ) ? true : false ):
				case ( false !== strpos( $base_key, 'wcmd_edit_holder_' ) ? true : false ):
				case ( false !== strpos( $base_key, 'wcmd_show_label_' ) ? true : false ):

					return 'not_blank';

				case ( false !== strpos( $base_key, 'wcmd_edit_' ) ? true : false ):
				case ( false !== strpos( $base_key, 'wcmd_show_' ) ? true : false ):

					return 'checkbox';
			}

			return $type;
		}
	}
}
