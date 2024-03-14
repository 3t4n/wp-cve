<?php
/*
 * Plugin Name: WPSSO Product Metadata for WooCommerce SEO
 * Plugin Slug: wpsso-wc-metadata
 * Text Domain: wpsso-wc-metadata
 * Domain Path: /languages
 * Plugin URI: https://wpsso.com/extend/plugins/wpsso-wc-metadata/
 * Assets URI: https://jsmoriss.github.io/wpsso-wc-metadata/assets/
 * Author: JS Morisset
 * Author URI: https://wpsso.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Description: MPN, ISBN, GTIN, GTIN-8, UPC, EAN, GTIN-14, net dimensions, and fluid volume for WooCommerce products and variations.
 * Requires Plugins: wpsso, woocommerce
 * Requires PHP: 7.2.34
 * Requires At Least: 5.8
 * Tested Up To: 6.4.3
 * WC Tested Up To: 8.6.1
 * Version: 4.1.1
 *
 * Version Numbering: {major}.{minor}.{bugfix}[-{stage}.{level}]
 *
 *      {major}         Major structural code changes and/or incompatible API changes (ie. breaking changes).
 *      {minor}         New functionality was added or improved in a backwards-compatible manner.
 *      {bugfix}        Backwards-compatible bug fixes or small improvements.
 *      {stage}.{level} Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).
 *
 * Copyright 2020-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoAbstractAddOn' ) ) {

	require_once dirname( __FILE__ ) . '/lib/abstract/add-on.php';
}

if ( ! class_exists( 'WpssoWcmd' ) ) {

	class WpssoWcmd extends WpssoAbstractAddOn {

		public $filters;	// WpssoWcmdFilters class object.
		public $search;		// WpssoWcmdSearch class object.
		public $wc;		// WpssoWcmdWooCommerce class object.

		protected $p;	// Wpsso class object.

		private static $instance = null;	// WpssoWcmd class object.

		public function __construct() {

			parent::__construct( __FILE__, __CLASS__ );
		}

		public static function &get_instance() {

			if ( null === self::$instance ) {

				self::$instance = new self;
			}

			return self::$instance;
		}

		public function init_textdomain() {

			load_plugin_textdomain( 'wpsso-wc-metadata', false, 'wpsso-wc-metadata/languages/' );
		}

		/*
		 * Called by Wpsso->set_objects which runs at init priority 10.
		 */
		public function init_objects() {

			$this->p =& Wpsso::get_instance();

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( $this->get_missing_requirements() ) {	// Returns false or an array of missing requirements.

				return;	// Stop here.
			}

			$this->filters = new WpssoWcmdFilters( $this->p, $this );
			$this->search  = new WpssoWcmdSearch( $this->p, $this );
			$this->wc      = new WpssoWcmdWooCommerce( $this->p, $this );
		}

		public function init_check_options() {

			if ( $this->get_missing_requirements() ) {	// Returns false or an array of missing requirements.

				return;	// Stop here.
			}

			$md_config    = WpssoWcmdConfig::get_md_config();
			$save_options = false;

			foreach ( $md_config as $md_key => $cfg ) {

				/*
				 * Maybe fix some hard-coded options values.
				 */
				if ( ! empty( $cfg[ 'prefixes' ][ 'options' ] ) ) {

					foreach ( $cfg[ 'prefixes' ][ 'options' ] as $opt_pre => $opt_val ) {

						$opt_key = $opt_pre . '_' . $md_key;	// Example: 'plugin_attr_product_gtin'.

						if ( ! isset( $this->p->options[ $opt_key ] ) || $opt_val !== $this->p->options[ $opt_key ] ) {

							$save_options = true;

							$this->p->options[ $opt_key ] = $opt_val;
						}

						$this->p->options[ $opt_key . ':disabled' ] = true;
					}
				}

				/*
				 * Maybe fix some custom field options values.
				 */
				if ( WpssoWcmdConfig::is_editable( $md_key ) ) {

					$opt_key = 'plugin_cf_' . $md_key;

					if ( empty( $this->p->options[ 'wcmd_edit_' . $md_key ] ) ) {

						if ( ! empty( $this->p->options[ $opt_key ] ) ) {

							$save_options = true;

							$this->p->options[ $opt_key ] = '';

							$this->p->options[ 'wcmd_show_' . $md_key ] = 0;
						}

						$this->p->options[ $opt_key . ':disabled' ] = true;

					} elseif ( empty( $this->p->options[ $opt_key ] ) ) {

						$save_options = true;

						$this->p->options[ $opt_key ] = $cfg[ 'prefixes' ][ 'defaults' ][ 'plugin_cf' ];
					}
				}
			}

			if ( $save_options ) {

				$this->p->opt->save_options( WPSSO_OPTIONS_NAME, $this->p->options, $network = false );
			}
		}
	}

	WpssoWcmd::get_instance();	// Self-instantiate.
}
