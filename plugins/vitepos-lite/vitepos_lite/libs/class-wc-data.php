<?php
/**
 * Its pos class-wc-data model
 *
 * @since: 21/09/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 * @package VitePos_Lite\Libs
 */

namespace VitePos_Lite\Libs;

/**
 * Class POS WC_Data
 *
 * @package VitePos_Lite\Libs
 */
class WC_Data {
	/**
	 * Get all products
	 *
	 * @param null  $fields Its null.
	 * @param null  $type Its null.
	 * @param array $filter Its array.
	 * @param int   $page Its int.
	 *
	 * @return array
	 * @since 2.1
	 */
	public function get_products( $fields = null, $type = null, $filter = array(), $page = 1 ) {

		if ( ! empty( $type ) ) {
			$filter['type'] = $type;
		}

		$filter['page'] = $page;

		$query = $this->query_products( $filter );

		$products = array();

		foreach ( $query->posts as $product_id ) {

			if ( ! $this->is_readable( $product_id ) ) {
				continue;
			}

			$products[] = current( $this->get_product( $product_id, $fields ) );
		}
		return array( 'products' => $products );
	}
	/**
	 * Checks if the given post is readable by the current user
	 *
	 * @since 2.1
	 * @see WC_API_Resource::check_permission()
	 * @param WP_Post|int $post Its int.
	 * @return bool
	 */
	protected function is_readable( $post ) {

		return $this->check_permission( $post, 'read' );
	}

	/**
	 * Checks if the given post is editable by the current user
	 *
	 * @since 2.1
	 * @see WC_API_Resource::check_permission()
	 * @param WP_Post|int $post Its int.
	 * @return bool
	 */
	protected function is_editable( $post ) {

		return $this->check_permission( $post, 'edit' );
	}

	/**
	 * Checks if the given post is deletable by the current user
	 *
	 * @since 2.1
	 * @see WC_API_Resource::check_permission()
	 * @param WP_Post|int $post Its int.
	 * @return bool
	 */
	protected function is_deletable( $post ) {

		return $this->check_permission( $post, 'delete' );
	}


	/**
	 * Checks the permissions for the current user given a post and context
	 *
	 * @since 2.1
	 * @param WP_Post|int $post Its int.
	 * @param string      $context the type of permission to check, either `read`, `write`, or `delete`.
	 * @return bool true if the current user has the permissions to perform the context on the post.
	 */
	private function check_permission( $post, $context ) {

		if ( ! is_a( $post, 'WP_Post' ) ) {
			$post = get_post( $post );
		}

		if ( is_null( $post ) ) {
			return false;
		}

		$post_type = get_post_type_object( $post->post_type );

		if ( 'read' === $context ) {
			return ( 'revision' !== $post->post_type && current_user_can( $post_type->cap->read_private_posts, $post->ID ) );
		} elseif ( 'edit' === $context ) {
			return current_user_can( $post_type->cap->edit_post, $post->ID );
		} elseif ( 'delete' === $context ) {
			return current_user_can( $post_type->cap->delete_post, $post->ID );
		} else {
			return false;
		}
	}
	/**
	 * Get an individual variation's data.
	 *
	 * @since 2.1
	 * @param WC_Product $product Its string.
	 * @return array
	 */
	private function get_variation_data( $product ) {
		$variations = array();

		foreach ( $product->get_children() as $child_id ) {
			$variation = wc_get_product( $child_id );

			if ( ! $variation || ! $variation->exists() ) {
				continue;
			}

			$variations[] = array(
				'id'                 => $variation->get_id(),
				'created_at'         => $this->server->format_datetime( $variation->get_date_created(), false, true ),
				'updated_at'         => $this->server->format_datetime( $variation->get_date_modified(), false, true ),
				'downloadable'       => $variation->is_downloadable(),
				'virtual'            => $variation->is_virtual(),
				'permalink'          => $variation->get_permalink(),
				'sku'                => $variation->get_sku(),
				'price'              => $variation->get_price(),
				'regular_price'      => $variation->get_regular_price(),
				'sale_price'         => $variation->get_sale_price() ? $variation->get_sale_price() : null,
				'taxable'            => $variation->is_taxable(),
				'tax_status'         => $variation->get_tax_status(),
				'tax_class'          => $variation->get_tax_class(),
				'managing_stock'     => $variation->managing_stock(),
				'stock_quantity'     => $variation->get_stock_quantity(),
				'in_stock'           => $variation->is_in_stock(),
				'backorders_allowed' => $variation->backorders_allowed(),
				'backordered'        => $variation->is_on_backorder(),
				'purchaseable'       => $variation->is_purchasable(),
				'visible'            => $variation->variation_is_visible(),
				'on_sale'            => $variation->is_on_sale(),
				'weight'             => $variation->get_weight() ? $variation->get_weight() : null,
				'dimensions'         => array(
					'length' => $variation->get_length(),
					'width'  => $variation->get_width(),
					'height' => $variation->get_height(),
					'unit'   => get_option( 'woocommerce_dimension_unit' ),
				),
				'shipping_class'     => $variation->get_shipping_class(),
				'shipping_class_id'  => ( 0 !== $variation->get_shipping_class_id() ) ? $variation->get_shipping_class_id() : null,
				'image'              => $this->get_images( $variation ),
				'attributes'         => $this->get_attributes( $variation ),
				'downloads'          => $this->get_downloads( $variation ),
				'download_limit'     => (int) $product->get_download_limit(),
				'download_expiry'    => (int) $product->get_download_expiry(),
			);
		}

		return $variations;
	}

	/**
	 * Get the product for the given ID
	 *
	 * @since 2.1
	 * @param int  $id The product ID.
	 * @param null $fields Its null.
	 * @return array|WP_Error
	 */
	public function get_product( $id, $fields = null ) {

		$product = wc_get_product( $id );

				$product_data = $this->get_product_data( $product );

		if ( $product->is_type( 'variable' ) && $product->has_child() ) {
			$product_data['variations'] = $this->get_variation_data( $product );
		}

		if ( $product->is_type( 'variation' ) && $product->get_parent_id() ) {
			$product_data['parent'] = $this->get_product_data( $product->get_parent_id() );
		}

		if ( $product->is_type( 'grouped' ) && $product->has_child() ) {
			$product_data['grouped_products'] = $this->get_grouped_products_data( $product );
		}

		if ( $product->is_type( 'simple' ) ) {
			$parent_id = $product->get_parent_id();
			if ( ! empty( $parent_id ) ) {
				$_product               = wc_get_product( $parent_id );
				$product_data['parent'] = $this->get_product_data( $_product );
			}
		}
		/**
		 * Its for api product response.
		 *
		 * @since 1.0
		 */
		return array( 'product' => apply_filters( 'woocommerce_api_product_response', $product_data, $product, $fields, $this ) );
	}
	/**
	 * Get grouped products data
	 *
	 * @since  2.5.0
	 * @param  WC_Product $product Its string.
	 *
	 * @return array
	 */
	private function get_grouped_products_data( $product ) {
		$products = array();

		foreach ( $product->get_children() as $child_id ) {
			$_product = wc_get_product( $child_id );

			if ( ! $_product || ! $_product->exists() ) {
				continue;
			}

			$products[] = $this->get_product_data( $_product );

		}

		return $products;
	}
	/**
	 * Helper method to get product post objects
	 *
	 * @since 2.1
	 * @param array $args request arguments for filtering query.
	 * @return \WP_Query
	 */
	public function query_products( $args ) {

				$query_args = array(
					'fields'      => 'ids',
					'post_type'   => 'product',
					'post_status' => 'publish',
					'meta_query'  => array(),
				);

						$tax_query = array();

				$taxonomies_arg_map = array(
					'product_type'           => 'type',
					'product_cat'            => 'category',
					'product_tag'            => 'tag',
					'product_shipping_class' => 'shipping_class',
				);

				foreach ( wc_get_attribute_taxonomy_names() as $attribute_name ) {
					$taxonomies_arg_map[ $attribute_name ] = $attribute_name;
				}

				foreach ( $taxonomies_arg_map as $tax_name => $arg ) {
					if ( ! empty( $args[ $arg ] ) ) {
						$terms = explode( ',', $args[ $arg ] );

						$tax_query[] = array(
							'taxonomy' => $tax_name,
							'field'    => 'slug',
							'terms'    => $terms,
						);

						unset( $args[ $arg ] );
					}
				}

				if ( ! empty( $tax_query ) ) {
					$query_args['tax_query'] = $tax_query;
				}

				if ( ! empty( $args['sku'] ) ) {
					if ( ! is_array( $query_args['meta_query'] ) ) {
						$query_args['meta_query'] = array();
					}

					$query_args['meta_query'][] = array(
						'key'     => '_sku',
						'value'   => $args['sku'],
						'compare' => '=',
					);

					$query_args['post_type'] = array( 'product', 'product_variation' );
				}

				$query_args = $this->merge_query_args( $query_args, $args );

				return new \WP_Query( $query_args );
	}
	/**
	 * Add common request arguments to argument list before \WP_Query is run
	 *
	 * @since 2.1
	 * @param array $base_args required arguments for the query (e.g. `post_type`, etc).
	 * @param array $request_args arguments provided in the request.
	 * @return array
	 */
	public function merge_query_args( $base_args, $request_args ) {

		$args = array();

		if ( ! empty( $request_args['created_at_min'] ) || ! empty( $request_args['created_at_max'] ) || ! empty( $request_args['updated_at_min'] ) || ! empty( $request_args['updated_at_max'] ) ) {

			$args['date_query'] = array();

			if ( ! empty( $request_args['created_at_min'] ) ) {
				$args['date_query'][] = array(
					'column'    => 'post_date_gmt',
					'after'     => $this->server->parse_datetime( $request_args['created_at_min'] ),
					'inclusive' => true,
				);
			}

			if ( ! empty( $request_args['created_at_max'] ) ) {
				$args['date_query'][] = array(
					'column'    => 'post_date_gmt',
					'before'    => $this->server->parse_datetime( $request_args['created_at_max'] ),
					'inclusive' => true,
				);
			}

			if ( ! empty( $request_args['updated_at_min'] ) ) {
				$args['date_query'][] = array(
					'column'    => 'post_modified_gmt',
					'after'     => $this->server->parse_datetime( $request_args['updated_at_min'] ),
					'inclusive' => true,
				);
			}

			if ( ! empty( $request_args['updated_at_max'] ) ) {
				$args['date_query'][] = array(
					'column'    => 'post_modified_gmt',
					'before'    => $this->server->parse_datetime( $request_args['updated_at_max'] ),
					'inclusive' => true,
				);
			}
		}

		if ( ! empty( $request_args['q'] ) ) {
			$args['s'] = $request_args['q'];
		}

		if ( ! empty( $request_args['limit'] ) ) {
			$args['posts_per_page'] = $request_args['limit'];
		}

		if ( ! empty( $request_args['offset'] ) ) {
			$args['offset'] = $request_args['offset'];
		}

		if ( ! empty( $request_args['order'] ) ) {
			$args['order'] = $request_args['order'];
		}

		if ( ! empty( $request_args['orderby'] ) ) {
			$args['orderby'] = $request_args['orderby'];

			if ( ! empty( $request_args['orderby_meta_key'] ) ) {
				$args['meta_key'] = $request_args['orderby_meta_key'];
			}
		}

		if ( ! empty( $request_args['post_status'] ) ) {
			$args['post_status'] = $request_args['post_status'];
			unset( $request_args['post_status'] );
		}

		if ( ! empty( $request_args['in'] ) ) {
			$args['post__in'] = explode( ',', $request_args['in'] );
			unset( $request_args['in'] );
		}

		if ( ! empty( $request_args['in'] ) ) {
			$args['post__in'] = explode( ',', $request_args['in'] );
			unset( $request_args['in'] );
		}

				$args['paged'] = ( isset( $request_args['page'] ) ) ? absint( $request_args['page'] ) : 1;
		/**
		 * Its for api query args.
		 *
		 * @since 1.0
		 */
		$args = apply_filters( 'woocommerce_api_query_args', $args, $request_args );

		return array_merge( $base_args, $args );
	}

	/**
	 * Get standard product data that applies to every product type
	 *
	 * @since 2.1
	 * @param WC_Product|int $product Its int.
	 *
	 * @return array
	 */
	private function get_product_data( $product ) {
		if ( is_numeric( $product ) ) {
			$product = wc_get_product( $product );
		}

		if ( ! is_a( $product, 'WC_Product' ) ) {
			return array();
		}

		return array(
			'title'              => $product->get_name(),
			'id'                 => $product->get_id(),
			'created_at'         => $this->format_datetime( $product->get_date_created(), false, true ),
			'updated_at'         => $this->format_datetime( $product->get_date_modified(), false, true ),
			'type'               => $product->get_type(),
			'status'             => $product->get_status(),
			'downloadable'       => $product->is_downloadable(),
			'virtual'            => $product->is_virtual(),
			'permalink'          => $product->get_permalink(),
			'sku'                => $product->get_sku(),
			'price'              => $product->get_price(),
			'regular_price'      => $product->get_regular_price(),
			'sale_price'         => $product->get_sale_price() ? $product->get_sale_price() : null,
			'price_html'         => $product->get_price_html(),
			'taxable'            => $product->is_taxable(),
			'tax_status'         => $product->get_tax_status(),
			'tax_class'          => $product->get_tax_class(),
			'managing_stock'     => $product->managing_stock(),
			'stock_quantity'     => $product->get_stock_quantity(),
			'in_stock'           => $product->is_in_stock(),
			'backorders_allowed' => $product->backorders_allowed(),
			'backordered'        => $product->is_on_backorder(),
			'sold_individually'  => $product->is_sold_individually(),
			'purchaseable'       => $product->is_purchasable(),
			'featured'           => $product->is_featured(),
			'visible'            => $product->is_visible(),
			'catalog_visibility' => $product->get_catalog_visibility(),
			'on_sale'            => $product->is_on_sale(),
			'product_url'        => $product->is_type( 'external' ) ? $product->get_product_url() : '',
			'button_text'        => $product->is_type( 'external' ) ? $product->get_button_text() : '',
			'weight'             => $product->get_weight() ? $product->get_weight() : null,
			'dimensions'         => array(
				'length' => $product->get_length(),
				'width'  => $product->get_width(),
				'height' => $product->get_height(),
				'unit'   => get_option( 'woocommerce_dimension_unit' ),
			),
			'shipping_required'  => $product->needs_shipping(),
			'shipping_taxable'   => $product->is_shipping_taxable(),
			'shipping_class'     => $product->get_shipping_class(),
			'shipping_class_id'  => ( 0 !== $product->get_shipping_class_id() ) ? $product->get_shipping_class_id() : null,
			/**
			 * Its for short_description.
			 *
			 * @since 1.0
			 */
			'short_description'  => apply_filters( 'woocommerce_short_description', $product->get_short_description() ),
			'reviews_allowed'    => $product->get_reviews_allowed(),
			'average_rating'     => wc_format_decimal( $product->get_average_rating(), 2 ),
			'rating_count'       => $product->get_rating_count(),
			'related_ids'        => array_map( 'absint', array_values( wc_get_related_products( $product->get_id() ) ) ),
			'upsell_ids'         => array_map( 'absint', $product->get_upsell_ids() ),
			'cross_sell_ids'     => array_map( 'absint', $product->get_cross_sell_ids() ),
			'parent_id'          => $product->get_parent_id(),
			'categories'         => wc_get_object_terms( $product->get_id(), 'product_cat', 'name' ),
			'tags'               => wc_get_object_terms( $product->get_id(), 'product_tag', 'name' ),
			'images'             => $this->get_images( $product ),
			'featured_src'       => wp_get_attachment_url( get_post_thumbnail_id( $product->get_id() ) ),
			'attributes'         => $this->get_attributes( $product ),
			'downloads'          => $this->get_downloads( $product ),
			'download_limit'     => $product->get_download_limit(),
			'download_expiry'    => $product->get_download_expiry(),
			'download_type'      => 'standard',
			'purchase_note'      => wpautop( do_shortcode( wp_kses_post( $product->get_purchase_note() ) ) ),
			'total_sales'        => $product->get_total_sales(),
			'variations'         => array(),
			'parent'             => array(),
			'grouped_products'   => array(),
		);
	}
	/**
	 * Get the images for a product or product variation
	 *
	 * @since 2.1
	 * @param WC_Product|WC_Product_Variation $product Its string.
	 * @return array
	 */
	private function get_images( $product ) {
		$images         = array();
		$attachment_ids = array();
		$product_image  = $product->get_image_id();

		if ( ! empty( $product_image ) ) {
			$attachment_ids[] = $product_image;
		}

				$attachment_ids = array_merge( $attachment_ids, $product->get_gallery_image_ids() );

		foreach ( $attachment_ids as $position => $attachment_id ) {

			$attachment_post = get_post( $attachment_id );

			if ( is_null( $attachment_post ) ) {
				continue;
			}

			$attachment = wp_get_attachment_image_src( $attachment_id, 'full' );

			if ( ! is_array( $attachment ) ) {
				continue;
			}

			$images[] = array(
				'id'         => (int) $attachment_id,
				'created_at' => $this->format_datetime( $attachment_post->post_date_gmt ),
				'updated_at' => $this->format_datetime( $attachment_post->post_modified_gmt ),
				'src'        => current( $attachment ),
				'title'      => get_the_title( $attachment_id ),
				'alt'        => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
				'position'   => (int) $position,
			);
		}

		if ( empty( $images ) ) {

			$images[] = array(
				'id'         => 0,
				'created_at' => $this->format_datetime( time() ),
				'updated_at' => $this->format_datetime( time() ),
				'src'        => wc_placeholder_img_src(),
				'title'      => __( 'Placeholder', 'woocommerce' ),
				'alt'        => __( 'Placeholder', 'woocommerce' ),
				'position'   => 0,
			);
		}

		return $images;
	}

	/**
	 * Get the attributes for a product or product variation
	 *
	 * @since 2.1
	 * @param WC_Product|WC_Product_Variation $product Its String.
	 * @return array
	 */
	private function get_attributes( $product ) {

		$attributes = array();

		if ( $product->is_type( 'variation' ) ) {

			foreach ( $product->get_variation_attributes() as $attribute_name => $attribute ) {

					$attributes[] = array(
						'name'   => wc_attribute_label( str_replace( 'attribute_', '', $attribute_name ), $product ),
						'slug'   => str_replace( 'attribute_', '', wc_attribute_taxonomy_slug( $attribute_name ) ),
						'option' => $attribute,
					);
			}
		} else {

			foreach ( $product->get_attributes() as $attribute ) {
				$attributes[] = array(
					'name'      => wc_attribute_label( $attribute['name'], $product ),
					'slug'      => wc_attribute_taxonomy_slug( $attribute['name'] ),
					'position'  => (int) $attribute['position'],
					'visible'   => (bool) $attribute['is_visible'],
					'variation' => (bool) $attribute['is_variation'],
					'options'   => $this->get_attribute_options( $product->get_id(), $attribute ),
				);
			}
		}

		return $attributes;
	}
	/**
	 * Get the downloads for a product or product variation
	 *
	 * @since 2.1
	 * @param WC_Product|WC_Product_Variation $product Its string.
	 * @return array
	 */
	private function get_downloads( $product ) {

		$downloads = array();

		if ( $product->is_downloadable() ) {

			foreach ( $product->get_downloads() as $file_id => $file ) {

				$downloads[] = array(
					'id'   => $file_id,
					'name' => $file['name'],
					'file' => $file['file'],
				);
			}
		}

		return $downloads;
	}
	/**
	 * Get attribute options.
	 *
	 * @param int   $product_id Its int.
	 * @param array $attribute Its array.
	 * @return array
	 */
	protected function get_attribute_options( $product_id, $attribute ) {
		if ( isset( $attribute['is_taxonomy'] ) && $attribute['is_taxonomy'] ) {
			return wc_get_product_terms( $product_id, $attribute['name'], array( 'fields' => 'names' ) );
		} elseif ( isset( $attribute['value'] ) ) {
			return array_map( 'trim', explode( '|', $attribute['value'] ) );
		}

		return array();
	}
	/**
	 * Format a unix timestamp or MySQL datetime into an RFC3339 datetime
	 *
	 * @since 2.1
	 * @param int|string $timestamp unix timestamp or MySQL datetime.
	 * @param bool       $convert_to_utc Its bool.
	 * @param bool       $convert_to_gmt Use GMT timezone.
	 * @return string RFC3339 datetime
	 */
	public function format_datetime( $timestamp, $convert_to_utc = false, $convert_to_gmt = false ) {
		if ( $convert_to_gmt ) {
			if ( is_numeric( $timestamp ) ) {
				$timestamp = gmdate( 'Y-m-d H:i:s', $timestamp );
			}

			$timestamp = get_gmt_from_date( $timestamp );
		}

		if ( $convert_to_utc ) {
			$timezone = new DateTimeZone( wc_timezone_string() );
		} else {
			$timezone = new DateTimeZone( 'UTC' );
		}

		try {

			if ( is_numeric( $timestamp ) ) {
				$date = new DateTime( "@{$timestamp}" );
			} else {
				$date = new DateTime( $timestamp, $timezone );
			}

			if ( $convert_to_utc ) {
				$date->modify( -1 * $date->getOffset() . ' seconds' );
			}
		} catch ( Exception $e ) {

			$date = new DateTime( '@0' );
		}

		return $date->format( 'Y-m-d\TH:i:s\Z' );
	}
}
