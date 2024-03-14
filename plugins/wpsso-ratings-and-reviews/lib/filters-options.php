<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2017-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoRarFiltersOptions' ) ) {

	class WpssoRarFiltersOptions {

		private $p;	// Wpsso class object.
		private $a;	// WpssoRar class object.

		/*
		 * Instantiated by WpssoRarFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'add_custom_post_type_options' => 1,
				'add_custom_taxonomy_options'  => 1,
			) );
		}

		public function filter_add_custom_post_type_options( $opt_prefixes ) {

			$opt_prefixes[ 'rar_add_to' ]            = 0;
			$opt_prefixes[ 'plugin_avg_rating_col' ] = 1;

			return $opt_prefixes;
		}

		public function filter_add_custom_taxonomy_options( $opt_prefixes ) {

			unset( $opt_prefixes[ 'plugin_avg_rating_col_tax' ] );

			return $opt_prefixes;
		}
	}
}
