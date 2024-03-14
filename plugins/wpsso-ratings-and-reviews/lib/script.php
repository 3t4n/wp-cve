<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2017-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoRarScript' ) ) {

	class WpssoRarScript {

		private $p;	// Wpsso class object.
		private $a;	// WpssoRar class object.
		private $doing_dev = false;
		private $file_ext  = 'min.js';
		private $version   = '';

		/*
		 * Instantiated by WpssoRar->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->doing_dev = SucomUtilWP::doing_dev();
			$this->file_ext  = $this->doing_dev ? 'js' : 'min.js';
			$this->version   = WpssoRarConfig::get_version() . ( $this->doing_dev ? gmdate( '-ymd-His' ) : '' );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), WPSSO_ADMIN_SCRIPTS_PRIORITY );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		public function admin_enqueue_scripts( $hook_name ) {

			/*
			 * Don't load our javascript where we don't need it.
			 */
			switch ( $hook_name ) {

				case 'edit.php':

					wp_enqueue_script( 'wpsso-rar-admin-script',
						WPSSORAR_URLPATH . 'js/admin-script.' . $this->file_ext,
							array( 'jquery' ), $this->version, $in_footer = true );

					break;	// Stop here.
			}

		}

		public function enqueue_scripts() {

			if ( ! $post_id = get_the_ID() ) {

				return;

			} elseif ( ! WpssoRarComment::is_rating_enabled( $post_id ) ) {

				return;
			}

			wp_enqueue_script( 'wpsso-rar-script',
				WPSSORAR_URLPATH . 'js/script.' . $this->file_ext,
					array( 'jquery' ), $this->version );

			wp_localize_script( 'wpsso-rar-script',
				'wpsso_rar_script', $this->get_script_data() );
		}

		public function get_script_data() {

			$is_reply = empty( $_GET[ 'replytocom' ] ) ? false : true;

			return array(
				'_required_rating_transl' => esc_attr__( 'Please select a rating before submitting.', 'wpsso-ratings-and-reviews' ),
				'_required_review_transl' => esc_attr__( 'Please write a review before submitting.', 'wpsso-ratings-and-reviews' ),
				'_rating_required'        => empty( $this->p->options[ 'rar_rating_required' ] ) || $is_reply ? false : true,
			);
		}
	}
}
