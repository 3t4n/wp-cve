<?php

namespace GRIM_CEW\Fields;

use GRIM_CEW\Vendor\Field;

class Autocomplete extends Field {
	public function __construct() {
		add_action( 'wp_ajax_cew_autocomplete_search', array( $this, 'search' ) );
	}

	public static function render( $args = array() ) {
		wp_enqueue_script( 'jquery-ui', CEW_URL . 'assets/js/jquery-ui.min.js', array( 'jquery' ), CEW_VERSION, true );

		parent::load( 'autocomplete', $args );
	}

	public function search() {
		global $wpdb;

		$search_term = trim( $wpdb->esc_like( $_POST['term'] ?? '' ) );

		if ( ! empty( $search_term ) ) {
			$data    = array();
			$results = $wpdb->get_results( "SELECT ID,post_title FROM {$wpdb->posts} WHERE post_title LIKE '%{$search_term}%' AND post_status = 'publish'" );

			foreach ( $results as $row ) {
				$data[] = array(
					'label' => $row->post_title,
					'value' => $row->ID,
				);
			}

			if ( ! empty( $data ) ) {
				wp_send_json(
					array(
						'success' => true,
						'data'    => $data,
					)
				);

				wp_die();
			}
		}

		wp_send_json(
			array(
				'success' => false,
				'message' => esc_html__( 'No posts found.', 'classic-editor-and-classic-widgets' ),
			)
		);

		wp_die();
	}
}
