<?php
/**
 * Plugin Name: Posts Social Shares Count
 * Plugin URI: http://bishoy.me/wp-plugins/posts-social-shares-count/
 * Description: This plugin allows you to count posts and pages shares count for 7 different social networks using shortcodes and functions! If you like this free plugin, please <a href="http://bishoy.me/donate" target="_blank">consider a donation</a>.
 * Version: 1.4.1
 * Tested up to: 4.3.1
 * Author: Bishoy A.
 * Author URI: http://bishoy.me
 * License: GPL2
 */

/*  Copyright 2014  Bishoy A.  (email : hi@bishoy.me)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined( 'ABSPATH' ) or exit( 'Permission Denied' );

if ( ! class_exists( 'BaPSSC' ) ) {
	final class BaPSSC {
		/**
		 * A dummy magic method to prevent BaPSSC from being loaded more than once.
		 * @since BaPSSC (1.0.0)
		 */
		private function __construct() { }

		/**
		 * A dummy magic method to prevent BaPSSC from being cloned.
		 * @since BaPSSC (1.0.0)
		 */
		public function __clone() { _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'BaPSSC' ), '1.7' ); }

		/**
		 * A dummy magic method to prevent BaPSSC from being unserialized.
		 * @since BaPSSC (1.0.0)
		 */
		public function __wakeup() { _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'BaPSSC' ), '1.7' ); }

		/**
		 * Magic method to prevent notices and errors from invalid method calls.
		 * @since BaPSSC (1.0.0)
		 */
		public function __call( $name = '', $args = array() ) { unset( $name, $args ); return null; }

		/**
		 * Main plugin constructor
		 * @return object instance
		 * @since  BaPSSC (1.0.0)
		 */
		public static function instance() {
			static $instance = null;

			// Only run these methods if they haven't been run previously
			if ( null === $instance ) {
				$instance = new BaPSSC;
				$instance->init();
			}

			// Always return the instance
			return $instance;
		}

		/**
		 * Initialize the plugin
		 * @return void
		 * @since  BaPSSC (1.0.0)
		 */
		public function init() {
			require_once 'functions.php';
			add_shortcode( 'pssc_facebook', array( $this, 'fb_shares_count' ) );
			add_shortcode( 'pssc_twitter', array( $this, 'tw_shares_count' ) );
			add_shortcode( 'pssc_pinterest', array( $this, 'pi_shares_count' ) );
			add_shortcode( 'pssc_linkedin', array( $this, 'li_shares_count' ) );
			add_shortcode( 'pssc_delicious', array( $this, 'dl_shares_count' ) );
			add_shortcode( 'pssc_stumble', array( $this, 'st_shares_count' ) );
			add_shortcode( 'pssc_gplus', array( $this, 'gp_shares_count' ) );
			add_shortcode( 'pssc_all', array( $this, 'all_count' ) );

			add_action( 'post_submitbox_misc_actions', array( $this, 'admin_edit_shares' ) );

			add_filter( 'manage_posts_columns', array( $this, 'columns_head' ), 10, 2 );
			add_action( 'manage_posts_custom_column', array( $this, 'columns_content' ), 10, 2 );

			add_filter( 'manage_pages_columns', array( $this, 'columns_head' ), 10, 2 );
			add_action( 'manage_pages_custom_column', array( $this, 'columns_content' ), 10, 2 );
		}

		public function admin_edit_shares() {
			if ( empty( $_GET['post'] ) )
				return;
			?>
			<div class="misc-pub-section curshares misc-pub-curshares">
				<span id="timesshared">
					<span class="dashicons dashicons-share" style="color: #888;"></span> <?php _e( 'Shares' ); ?>: <b><?php echo pssc_all( $_GET['post'] ); ?></b>
				</span>
			</div>
			<?php
		}

		function columns_head( $defaults, $post_type = '' ) {
			if ( ! empty( $post_type ) ) {
				$post_type_object = get_post_type_object( $post_type );
			}

			if ( ! empty( $post_type_object ) ) {
				if ( ! $post_type_object->publicly_queryable ) return;
			}

		    $defaults['pssc_shares'] = 'Shares';
		    return $defaults;
		}

		function columns_content( $column_name, $post_ID ) {
			
			$post_type = get_post_type( $post_ID );

			if ( ! empty( $post_type ) ) {
				$post_type_object = get_post_type_object( $post_type );
			}

			if ( ! empty( $post_type_object ) ) {
				if ( ! $post_type_object->publicly_queryable ) return;
			}

		    if ( $column_name == 'pssc_shares' ) {
		        echo pssc_all( $post_ID );
		    }
		}

		/**
		 * Shortcode [pssc_facebook]
		 * @param  array $atts
		 * @uses   pssc_facebook()
		 * @return itneger
		 */
		public function fb_shares_count( $atts ) {
			$atts = shortcode_atts( array( 'post_id' => '' ), $atts );
			return pssc_facebook( $atts['post_id'] );
		}

		/**
		 * @deprecated 1.4.1
		 * Shortcode [pssc_twitter]
		 * @param  array $atts
		 * @uses   pssc_twitter()
		 * @return integer
		 */
		public function tw_shares_count( $atts ) {
			$atts = shortcode_atts( array( 'post_id' => '' ), $atts );
			return pssc_twitter( $atts['post_id'] );
		}

		/**
		 * Shortcode [pssc_pinterest]
		 * @param  array $atts
		 * @uses   pssc_pinterest()
		 * @return integer 
		 */
		public function pi_shares_count( $atts ) {
			$atts = shortcode_atts( array( 'post_id' => '' ), $atts );
			return pssc_pinterest( $atts['post_id'] );
		}

		/**
		 * Shortcode [pssc_linkedin]
		 * @param  array $atts
		 * @uses   pssc_linkedin()
		 * @return integer
		 */
		public function li_shares_count( $atts ) {
			$atts = shortcode_atts( array( 'post_id' => '' ), $atts );
			return pssc_linkedin( $atts['post_id'] );
		}

		/**
		 * Shortcode [pssc_delicious]
		 * @param  array $atts
		 * @uses   pssc_delicious()
		 * @return integer
		 */
		public function dl_shares_count( $atts ) {
			$atts = shortcode_atts( array( 'post_id' => '' ), $atts );
			return pssc_delicious( $atts['post_id'] );
		}

		/**
		 * Shortcode [pssc_stumble]
		 * @param  array $atts
		 * @uses   pssc_stumble()
		 * @return integer
		 */
		public function st_shares_count( $atts ) {
			$atts = shortcode_atts( array( 'post_id' => '' ), $atts );
			return pssc_stumble( $atts['post_id'] );
		}

		/**
		 * Shortcode [pssc_gplus]
		 * @param  array $atts
		 * @uses   pssc_gplus()
		 * @return integer
		 */
		public function gp_shares_count( $atts ) {
			$atts = shortcode_atts( array( 'post_id' => '' ), $atts );
			return pssc_gplus( $atts['post_id'] );
		}

		/**
		 * Shortcode [pssc_gplus]
		 * @param  array $atts
		 * @uses   pssc_gplus()
		 * @return integer
		 */
		public function all_count( $atts ) {
			$atts = shortcode_atts( array( 'post_id' => '' ), $atts );
			return pssc_all( $atts['post_id'] );
		}
	}
	/**
	 * Main function responsible for returning the instance
	 * @return BaPSSC
	 */
	function ba_pssc() {
		return BaPSSC::instance();
	}

	//Enjoy!
	$GLOBALS['ba_pssc'] = ba_pssc();
}