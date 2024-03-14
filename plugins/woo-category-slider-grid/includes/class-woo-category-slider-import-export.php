<?php
/**
 * Custom import export by ShapedPlugin.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package Woo_category_Slider.
 * @subpackage Woo_Category_Slider/includes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Woo_Category_Slider_Import_Export' ) ) {
	/**
	 * Custom import export.
	 */
	class Woo_Category_Slider_Import_Export {

		/**
		 * Export
		 *
		 * @param  mixed $shortcode_ids Export woo-category-slider shortcode ids.
		 * @return object
		 */
		public function export( $shortcode_ids ) {
			$export = array();
			if ( ! empty( $shortcode_ids ) ) {

				$post_in    = 'all_shortcodes' === $shortcode_ids ? '' : $shortcode_ids;
				$args       = array(
					'post_type'        => 'sp_wcslider',
					'post_status'      => array( 'inherit', 'publish' ),
					'orderby'          => 'modified',
					'suppress_filters' => 1, // wpml, ignore language filter.
					'posts_per_page'   => -1,
					'post__in'         => $post_in,
				);
				$shortcodes = get_posts( $args );
				if ( ! empty( $shortcodes ) ) {
					foreach ( $shortcodes as $shortcode ) {
						$shortcode_export = array(
							'title'       => $shortcode->post_title,
							'original_id' => $shortcode->ID,
							'meta'        => array(),
						);
						foreach ( get_post_meta( $shortcode->ID ) as $metakey => $value ) {
							$shortcode_export['meta'][ $metakey ] = $value[0];
						}
						$export['shortcode'][] = $shortcode_export;

						unset( $shortcode_export );
					}
					$export['metadata'] = array(
						'version' => SP_WCS_VERSION,
						'date'    => gmdate( 'Y/m/d' ),
					);
				}
				return $export;
			}
		}

		/**
		 * Export woo-category-slider by ajax.
		 *
		 * @return void
		 */
		public function export_shortcodes() {
			$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			if ( ! wp_verify_nonce( $nonce, 'spf_options_nonce' ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Error: Invalid nonce verification!', 'woo-category-slider-grid' ),
					),
					401
				);
			}
			$shortcode_ids = isset( $_POST['wcsp_ids'] ) ? $_POST['wcsp_ids'] : ''; // phpcs:ignore

			$export = $this->export( $shortcode_ids );

			if ( is_wp_error( $export ) ) {
				wp_send_json_error(
					array(
						'message' => $export->get_error_message(),
					),
					400
				);
			}

			if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
				// @codingStandardsIgnoreLine
				echo wp_json_encode($export, JSON_PRETTY_PRINT);
				die;
			}

			wp_send_json( $export, 200 );
		}

		/**
		 * Import shortcode.
		 *
		 * @param  mixed $shortcodes Import sliders shortcode array.
		 * @throws \Exception Get error messages.
		 * @return object
		 */
		public function import( $shortcodes ) {
			$errors = array();
			foreach ( $shortcodes as $index => $shortcode ) {
				$errors[ $index ] = array();
				$new_shortcode_id = 0;
				try {
					$new_shortcode_id = wp_insert_post(
						array(
							'post_title'  => isset( $shortcode['title'] ) ? $shortcode['title'] : '',
							'post_status' => 'publish',
							'post_type'   => 'sp_wcslider',
						),
						true
					);
					if ( is_wp_error( $new_shortcode_id ) ) {
						throw new \Exception( $new_shortcode_id->get_error_message() );
					}

					if ( isset( $shortcode['meta'] ) && is_array( $shortcode['meta'] ) ) {
						foreach ( $shortcode['meta'] as $key => $value ) {
							update_post_meta(
								$new_shortcode_id,
								$key,
								maybe_unserialize( str_replace( '{#ID#}', $new_shortcode_id, $value ) )
							);
						}
					}
				} catch ( \Exception $e ) {
					array_push( $errors[ $index ], $e->getMessage() );

					// If there was a failure somewhere, clean up.
					wp_trash_post( $new_shortcode_id );
				}

				// If no errors, remove the index.
				if ( ! count( $errors[ $index ] ) ) {
					unset( $errors[ $index ] );
				}

				// External modules manipulate data here.
				do_action( 'sp_woo_category_slider_imported', $new_shortcode_id );
			}

			$errors = reset( $errors );
			return isset( $errors[0] ) ? new \WP_Error( 'import_woo_category_slider_error', $errors[0] ) : '';
		}

		/**
		 * Import woo-product-slider by ajax.
		 *
		 * @return void
		 */
		public function import_shortcodes() {
			$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			if ( ! wp_verify_nonce( $nonce, 'spf_options_nonce' ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Error: Invalid nonce verification!', 'woo-category-slider-grid' ),
					),
					401
				);
			}
			$data       = isset( $_POST['shortcode'] ) ? wp_kses_data( wp_unslash( $_POST['shortcode'] ) ) : '';
			$data       = json_decode( $data );
			$data       = json_decode( $data, true );
			$shortcodes = $data['shortcode'];
			if ( ! $data ) {
				wp_send_json_error(
					array(
						'message' => __( 'Nothing to import.', 'woo-category-slider-grid' ),
					),
					400
				);
			}

			$status = $this->import( $shortcodes );

			if ( is_wp_error( $status ) ) {
				wp_send_json_error(
					array(
						'message' => $status->get_error_message(),
					),
					400
				);
			}

			wp_send_json_success( $status, 200 );
		}
	}
}
