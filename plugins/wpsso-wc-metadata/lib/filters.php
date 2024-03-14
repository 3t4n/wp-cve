<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoWcmdFilters' ) ) {

	class WpssoWcmdFilters {

		private $p;	// Wpsso class object.
		private $a;	// WpssoWcmd class object.
		private $msgs;	// WpssoWcmdFiltersMessages class object.
		private $opts;	// WpssoWcmdFiltersOptions class object.
		private $upg;	// WpssoWcmdFiltersUpgrade class object.

		/*
		 * Instantiated by WpssoWcmd->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			static $do_once = null;

			if ( true === $do_once ) {

				return;	// Stop here.
			}

			$do_once = true;

			$this->p =& $plugin;
			$this->a =& $addon;

			require_once WPSSOWCMD_PLUGINDIR . 'lib/filters-options.php';

			$this->opts = new WpssoWcmdFiltersOptions( $plugin, $addon );

			require_once WPSSOWCMD_PLUGINDIR . 'lib/filters-upgrade.php';

			$this->upg = new WpssoWcmdFiltersUpgrade( $plugin, $addon );

			if ( is_admin() ) {

				require_once WPSSOWCMD_PLUGINDIR . 'lib/filters-messages.php';

				$this->msgs = new WpssoWcmdFiltersMessages( $plugin, $addon );
			}
		}
	}
}
