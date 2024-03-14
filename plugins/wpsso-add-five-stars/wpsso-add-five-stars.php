<?php
/*
 * Plugin Name: WPSSO Add Five Stars
 * Plugin Slug: wpsso-add-five-stars
 * Text Domain: wpsso-add-five-stars
 * Domain Path: /languages
 * Plugin URI: https://wpsso.com/extend/plugins/wpsso-add-five-stars/
 * Assets URI: https://surniaulula.github.io/wpsso-add-five-stars/assets/
 * Author: JS Morisset
 * Author URI: https://wpsso.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Description: Add a 5 star rating and review from the site organization if the Schema markup does not already have an 'aggregateRating' property.
 * Requires Plugins: wpsso
 * Requires PHP: 7.2.34
 * Requires At Least: 5.8
 * Tested Up To: 6.4.3
 * Version: 2.1.0
 *
 * Version Numbering: {major}.{minor}.{bugfix}[-{stage}.{level}]
 *
 *      {major}         Major structural code changes and/or incompatible API changes (ie. breaking changes).
 *      {minor}         New functionality was added or improved in a backwards-compatible manner.
 *      {bugfix}        Backwards-compatible bug fixes or small improvements.
 *      {stage}.{level} Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).
 *
 * Copyright 2021-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoAbstractAddOn' ) ) {

	require_once dirname( __FILE__ ) . '/lib/abstract/add-on.php';
}

if ( ! class_exists( 'WpssoAfs' ) ) {

	class WpssoAfs extends WpssoAbstractAddOn {

		public $filters;	// WpssoAfsFilters class object.

		protected $p;		// Wpsso class object.

		private static $instance = null;	// WpssoAfs class object.

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

			load_plugin_textdomain( 'wpsso-add-five-stars', false, 'wpsso-add-five-stars/languages/' );
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

			$this->filters = new WpssoAfsFilters( $this->p, $this );
		}
	}

        global $wpssoafs;

	$wpssoafs =& WpssoAfs::get_instance();
}
