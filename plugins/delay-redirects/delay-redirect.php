<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
Plugin Name: Delay Redirect
Plugin URI: https://iamjagdish.com/wordpress-plugins/delay-redirect/
Description: Redirect pages to any path with delay.
Author: Jagdish Kashyap
Version: 1.0.0
Author URI: https://iamjagdish.com
License: GPL2
*/


if ( ! class_exists( "delay_redirect" ) ) {



	class delay_redirect {

		private $screens = array(
			'page',
		);
		private $fields = array(
			array(
				'id' => 'delay-in-seconds',
				'label' => 'Delay (in Seconds)',
				'type' => 'number',
			),
			array(
				'id' => 'destination-path-url',
				'label' => 'Destination Path URL',
				'type' => 'url',
			),
		);

		public function __construct() {

			// Add Fields
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
			add_action( 'save_post', array( $this, 'save_post' ) );
			add_action( 'wp_head', array( $this, "delay_redirect_output" ), 0 );


		}

		/**
		 * Hooks into WordPress' add_meta_boxes function.
		 * Goes through screens (post types) and adds the meta box.
		 */
		public function add_meta_boxes() {
			foreach ( $this->screens as $screen ) {
				add_meta_box(
					'delay-redirect',
					__( 'Delay Redirect', 'delay-redirect' ),
					array( $this, 'add_meta_box_callback' ),
					$screen,
					'normal',
					'high'
				);
			}
		}

		/**
		 * Generates the HTML for the meta box
		 *
		 * @param object $post WordPress post object
		 */
		public function add_meta_box_callback( $post ) {
			wp_nonce_field( 'delay_redirect_data', 'delay_redirect_nonce' );
			echo 'Redirect page with delay to your destination path.';
			$this->generate_fields( $post );
		}

		/**
		 * Generates the field's HTML for the meta box.
		 */
		public function generate_fields( $post ) {
			$output = '';
			foreach ( $this->fields as $field ) {
				$label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
				$db_value = get_post_meta( $post->ID, 'delay_redirect_' . $field['id'], true );
				switch ( $field['type'] ) {
					default:
						$input = sprintf(
							'<input %s id="%s" name="%s" type="%s" value="%s">',
							$field['type'] !== 'color' ? 'class="regular-text"' : '',
							$field['id'],
							$field['id'],
							$field['type'],
							$db_value
						);
				}
				$output .= $this->row_format( $label, $input );
			}
			echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
		}

		/**
		 * Generates the HTML for table rows.
		 */
		public function row_format( $label, $input ) {
			return sprintf(
				'<tr><th scope="row">%s</th><td>%s</td></tr>',
				$label,
				$input
			);
		}
		/**
		 * Hooks into WordPress' save_post function
		 */
		public function save_post( $post_id ) {
			if ( ! isset( $_POST['delay_redirect_nonce'] ) )
				return $post_id;

			$nonce = $_POST['delay_redirect_nonce'];
			if ( !wp_verify_nonce( $nonce, 'delay_redirect_data' ) )
				return $post_id;

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return $post_id;

			foreach ( $this->fields as $field ) {
				if ( isset( $_POST[ $field['id'] ] ) ) {
					switch ( $field['type'] ) {
						case 'email':
							$_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
							break;
						case 'text':
							$_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
							break;
					}
					update_post_meta( $post_id, 'delay_redirect_' . $field['id'], $_POST[ $field['id'] ] );
				} else if ( $field['type'] === 'checkbox' ) {
					update_post_meta( $post_id, 'delay_redirect_' . $field['id'], '0' );
				}
			}
		}
		/**
		 * Delay Redirects Output
		 */
		public function delay_redirect_output() {
			$delay = get_post_meta( get_the_id(), 'delay_redirect_delay-in-seconds', true );
			$destination = get_post_meta( get_the_id(), 'delay_redirect_destination-path-url', true );
			$output = '<meta http-equiv="refresh" content="'.$delay.';URL=\''.$destination.'\'" />';
			echo $output;
		}

		public static function activate() {
			// Do something
		}
		public static function deactivate() {
			// Do something
		}


	}
}

if ( class_exists( 'delay_redirect' ) ) {

	register_activation_hook( __FILE__, array( 'delay_redirect', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'delay_redirect', 'deactivate' ) );

	$plugin = new delay_redirect();
}