<?php

/**
 * class WooHook
 *
 * @link       https://appcheap.io
 * @author     ngocdt
 * @since      1.2.0
 */

namespace AppBuilder\Hooks;

use WP_Comment;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit;

class WooHook {

	public function __construct() {
		add_filter(
			'woocommerce_rest_prepare_product_review',
			array(
				$this,
				'woocommerce_rest_prepare_product_review',
			),
			10,
			3
		);

		add_action(
			'woocommerce_rest_insert_product_review',
			array(
				$this,
				'woocommerce_rest_insert_product_review',
			),
			10,
			3
		);

		add_filter(
			'woocommerce_rest_prepare_product_object',
			array(
				$this,
				'woocommerce_rest_prepare_product_object',
			),
			30,
			3
		);

		add_filter(
			'woocommerce_rest_product_object_query',
			array(
				$this,
				'woocommerce_rest_product_object_query',
			),
			10,
			2
		);

		/**
		 * Add filter nearby products
		 */
		add_filter( 'posts_clauses', array( $this, 'filter_nearby_product' ), 501, 2 );
	}

	/**
	 * Filter search product by barcode value
	 *
	 * @param $args
	 * @param $request
	 *
	 * @return array
	 */
	public function woocommerce_rest_product_object_query( $args, $request ): array {
		if ( class_exists( '\YITH_Barcode' ) && isset( $request['barcode'] ) && $request['barcode'] != '' ) {
			$args['meta_query']   = array();
			$args['meta_query'][] = array(
				'key'     => \YITH_Barcode::YITH_YWBC_META_KEY_BARCODE_DISPLAY_VALUE,
				'value'   => sanitize_text_field( $request['barcode'] ),
				'compare' => 'LIKE',
			);

		}

		if ( empty( $args ) ) {
			return array();
		}

		return $args;
	}

	/**
	 *
	 * Filter product reviews object returned from the REST API.
	 *
	 * @param WP_REST_Response $response The response object.
	 * @param WP_Comment       $review Product review object used to create response.
	 * @param WP_REST_Request  $request Request object.
	 */
	public function woocommerce_rest_prepare_product_review( WP_REST_Response $response, WP_Comment $review, WP_REST_Request $request ): WP_REST_Response {

		$active_plugins = get_option( 'active_plugins' );
		$data           = $response->get_data();
		$img_post_ids   = array();

		/**
		 * Get images attachment for Photo Reviews for WooCommerce or default
		 * https://wordpress.org/plugins/woo-photo-reviews/
		 */
		$images = get_comment_meta( $review->comment_ID, 'reviews-images', true );
		if ( is_array( $images ) && count( $images ) > 0 ) {
			foreach ( $images as $attachment_id ) {
				if ( $attachment_id ) {
					$img_post_ids[] = $attachment_id;
				}
			}
		}

		/**
		 * Get images attachment for Customer Reviews for WooCommerce
		 * https://wordpress.org/plugins/customer-reviews-woocommerce/
		 */
		if ( in_array( 'customer-reviews-woocommerce/ivole.php', $active_plugins ) ) {
			$images = get_comment_meta( $review->comment_ID, 'ivole_review_image2', false );
			if ( is_array( $images ) && count( $images ) > 0 ) {
				foreach ( $images as $attachment_id ) {
					if ( $attachment_id ) {
						$img_post_ids[] = $attachment_id;
					}
				}
			}
		}

		$reviews_images = array();

		/**
		 * Get image from attachment id
		 */
		if ( is_array( $img_post_ids ) && count( $img_post_ids ) > 0 ) {
			foreach ( $img_post_ids as $attachment_id ) {
				if ( $attachment_id ) {

					$thumb = wp_get_attachment_thumb_url( $attachment_id );
					$src   = wp_get_attachment_image_url( $attachment_id, 'full' );
					if ( $thumb && $src ) {
						$reviews_images[] = array(
							'thumb' => wp_get_attachment_thumb_url( $attachment_id ),
							'src'   => wp_get_attachment_image_url( $attachment_id, 'full' ),
						);
					}
				}
			}
		}

		$data['reviews_images'] = $reviews_images;
		$response->set_data( $data );

		return $response;
	}

	/**
	 * Fires after a comment is created or updated via the REST API.
	 *
	 * @param WP_Comment      $review Inserted or updated comment object.
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating True when creating a comment, false when updating.
	 */
	public function woocommerce_rest_insert_product_review( WP_Comment $review, WP_REST_Request $request, bool $creating = true ) {
		if ( $creating ) {

			$active_plugins = get_option( 'active_plugins' );
			$post_id        = $review->comment_post_ID;
			$comment_id     = $review->comment_ID;

			$files = $request->get_file_params();

			if ( isset( $files['images']['name'] ) && count( $files['images']['name'] ) > 0 ) {

				require_once ABSPATH . 'wp-admin/includes/image.php';
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/media.php';

				$attachments = array();

				for ( $i = 0; $i < count( $files['images']['name'] ); $i++ ) {
					if ( $this->is_validate_image( $files['images']['name'][ $i ] ) ) {
						$file = array(
							'name'     => $files['images']['name'][ $i ],
							'type'     => $files['images']['type'][ $i ],
							'tmp_name' => $files['images']['tmp_name'][ $i ],
							'error'    => $files['images']['error'][ $i ],
							'size'     => $files['images']['size'][ $i ],
						);

						$_FILES ['upload_file'] = $file;
						$attachment_id          = media_handle_upload( 'upload_file', $post_id );

						if ( ! is_wp_error( $attachment_id ) ) {
							$attachments[] = $attachment_id;
						}
					}
				}

				/**
				 * for Customer Reviews for WooCommerce
				 */
				if ( in_array( 'customer-reviews-woocommerce/ivole.php', $active_plugins ) ) {
					foreach ( $attachments as $id ) {
						add_comment_meta( $comment_id, 'ivole_review_image2', $id );
					}
				} else {
					update_comment_meta( $comment_id, 'reviews-images', $attachments );
				}
			}
		}
	}

	/**
	 *
	 * Image validation type
	 *
	 * @param $name string image name
	 *
	 * @return bool
	 */
	public function is_validate_image( string $name ): bool {
		$extension          = substr( $name, strlen( $name ) - 4, strlen( $name ) );
		$allowed_extensions = array( '.jpg', 'jpeg', '.png', '.gif' );

		return in_array( strtolower( $extension ), $allowed_extensions );
	}

	/**
	 *
	 * Prepare product object response
	 *
	 * @param $response
	 * @param $post
	 * @param $request
	 *
	 * @return mixed
	 * @since    1.0.0
	 */
	public function woocommerce_rest_prepare_product_object( $response, $post, $request ) {
		global $woocommerce_wpml;

		$data = $response->get_data();

		if ( empty( $data ) ) {
			return $response;
		}

		$type = $data['type'];

		if ( isset( $data['images'] ) ) {
			$sizes = wp_get_registered_image_subsizes();
			foreach ( $data['images'] as $key => $image ) {
				foreach ( $sizes as $size => $value ) {
					$image_info = wp_get_attachment_image_src( $image['id'], $size );
					if ( $image_info ) {
						$data['images'][ $key ][ $size ] = $image_info[0];
					}
				}
			}
		}

		/**
		 *
		 * External product create by content egg plugin
		 *
		 * @since 1.0.11
		 *
		 * When url is external
		 */
		if ( $type == 'external' && isset( $data['images'] ) && appBuilder()->addons()->get( 'content-egg' ) ) {
			$images = is_array( $data['images'] ) ? $data['images'] : array();

			$thumb = get_post_meta( $post->get_id(), '_cegg_thumbnail_external', true );

			if ( $thumb ) {
				$image = array();
				foreach ( $sizes as $size => $value ) {
					$image[ $size ] = $thumb['url'];
				}
				$images[]       = $image;
				$data['images'] = $images;
			}
		}

		/**
		 * Get product addons
		 */
		if ( class_exists( 'WC_Product_Addons_Helper' ) ) {
			$product_addons = \WC_Product_Addons_Helper::get_product_addons( $data['id'], false );
			if ( count( $product_addons ) > 0 && isset( $data['meta_data'] ) ) {
				$meta_data = is_array( $data['meta_data'] ) ? $data['meta_data'] : array();

				/**
				 * sanitize_title option product add-ons
				 */
				$addons = array();
				foreach ( $product_addons as $value ) {
					if ( isset( $value['options'] ) && is_array( $value['options'] ) ) {
						$options = array();
						foreach ( $value['options'] as $option ) {
							$option['sanitize_label'] = sanitize_title( $option['label'] );
							$options[]                = $option;
						}
						$value['options'] = $options;
					}

					$addons[] = $value;
				}

				$meta_data[]       = array(
					'id'    => 0,
					'key'   => 'product_addons',
					'value' => $addons,
				);
				$data['meta_data'] = $meta_data;
			}
		}

		/**
		 * Get ACF fields
		 */
		if ( isset( $data['acf'] ) && function_exists( 'get_field_objects' ) ) {
			if ( ! empty( $data['acf'] ) ) {
				$data['afc_fields'] = get_field_objects( $data['id'] );
			} else {
				unset( $data['acf'] );
			}
		}

		$response->set_data( apply_filters( 'app_builder_prepare_product_object', $data, $post, $request ) );

		return $response;
	}

	/**
	 * Add filter nearby products
	 *
	 * @param $args
	 * @param $wp_query
	 *
	 * @return mixed
	 */
	public function filter_nearby_product( $args, $wp_query ) {

		if ( ! function_exists( 'GMW' ) ) {
			return $args;
		}

		global $wpdb;

		if ( ! empty( $_GET['radius_lat'] ) && ! empty( $_GET['radius_lng'] ) ) {

			$lat      = wc_clean( $_GET['radius_lat'] );
			$lng      = wc_clean( $_GET['radius_lng'] );
			$distance = ! empty( $_GET['radius_range'] ) ? esc_sql( wc_clean( $_GET['radius_range'] ) ) : 50;

			$earth_radius = 6371;
			$units        = 'km';
			$degree       = 111.045;

			// add units to locations data.
			$args['fields'] .= ", '{$units}' AS units";

			$args['fields'] .= ", ROUND( {$earth_radius} * acos( cos( radians( {$lat} ) ) * cos( radians( gmw_locations.latitude ) ) * cos( radians( gmw_locations.longitude ) - radians( {$lng} ) ) + sin( radians( {$lat} ) ) * sin( radians( gmw_locations.latitude ) ) ),1 ) AS distance";
			$args['join']   .= " INNER JOIN {$wpdb->base_prefix}gmw_locations gmw_locations ON $wpdb->posts.ID = gmw_locations.object_id ";

			// calculate the between point.
			$bet_lat1 = $lat - ( $distance / $degree );
			$bet_lat2 = $lat + ( $distance / $degree );
			$bet_lng1 = $lng - ( $distance / ( $degree * cos( deg2rad( $lat ) ) ) );
			$bet_lng2 = $lng + ( $distance / ( $degree * cos( deg2rad( $lat ) ) ) );

			$args['where'] .= " AND gmw_locations.object_type = 'post'";
			$args['where'] .= " AND gmw_locations.latitude BETWEEN {$bet_lat1} AND {$bet_lat2}";
			// $args['where'] .= " AND gmw_locations.longitude BETWEEN {$bet_lng1} AND {$bet_lng2} ";

			// filter locations based on the distance.
			$args['having'] = "HAVING distance <= {$distance} OR distance IS NULL";

			$args['orderby'] .= ', distance ASC';

		}

		return $args;
	}
}
