<?php
/**
 * Plugin Name: Note Finder for WooCommerce
 * Description: Search for notes in WooCommerce orders
 * Version: 1.3
 * Author: Disable Bloat
 * Text Domain: note-finder-for-woocommerce
 * Domain Path: /languages
 * Requires at least: 4.5
 * Author: Disable Bloat
 * Developer: Disable Bloat
 * Author URI: https://disablebloat.com/
 * Tested up to: 5.9
 * Requires PHP: 5.6
 * WC requires at least: 3.5
 * WC tested up to: 5.7
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ospiotrNoteFinder' ) ) {
	class ospiotrNoteFinder {

		public function __construct() {
			add_action( 'plugins_loaded', [ $this, 'load_textdomain' ], 20 );
			add_action( 'admin_menu', [ $this, 'admin_menu' ] );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'links_filter' ] );
		}

		public function load_textdomain() {
			load_plugin_textdomain( 'note-finder-for-woocommerce', false, basename( __DIR__ ) . '/languages/' );
		}

		public function admin_menu() {
			add_submenu_page(
				'woocommerce',
				esc_html__( 'Notes', 'note-finder-for-woocommerce' ),
				esc_html__( 'Notes', 'note-finder-for-woocommerce' ),
				'manage_options',
				'wc-note-finder',
				[ $this, 'notes_content' ]
			);
		}

		public function links_filter( $links ) {
			$plugin_links[] = '<a href="' . admin_url( 'admin.php?page=wc-note-finder' ) . '">' . esc_html__( 'Notes', 'note-finder-for-woocommerce' ) . '</a>';;
			$links = array_merge( $plugin_links, $links );

			return $links;
		}


		public function notes_count() {
			global $wpdb;
			$total = 0;
			$count = $wpdb->get_results("
					SELECT comment_approved, COUNT(*) AS num_comments
					FROM {$wpdb->comments}
					WHERE comment_type IN ('order_note')
					GROUP BY comment_approved
				", ARRAY_A );
			foreach ( (array) $count as $row ) {
				if ( 'post-trashed' !== $row['comment_approved'] && 'trash' !== $row['comment_approved'] ) {
					$total += $row['num_comments'];
				}
			}

			return $total;
		}

		public function notes_content() {
			$number        = isset( $_GET['number'] ) ? (int) $_GET['number'] : '50';
			$total_notes   = self::notes_count();
			$total_pages   = floor( $total_notes / $number ) + 1;
			$page          = isset( $_GET['pg'] ) ? (int) $_GET['pg'] : 1;
			$offset        = $number * ( $page - 1 );
			$searchkeyword = isset( $_GET['searchkeyword'] ) ? sanitize_text_field( $_GET['searchkeyword'] ) : '';
			require 'note-finder-html.php';
		}
	}

	add_action( 'plugins_loaded', function () {
		new ospiotrNoteFinder();
	}, 10 );
}