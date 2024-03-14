<?php

namespace Yay_Swatches\Engine;

use Yay_Swatches\Utils\SingletonTrait;
use Yay_Swatches\Helpers\Helper;

use stdClass;

defined( 'ABSPATH' ) || exit;

class Ajax {

	use SingletonTrait;

	private $default_swatch_customize_settings;
	private $default_button_customize_settings;
	private $default_swatch_color_array;
	private $default_sold_out_customize_settings;

	protected function __construct() {

		$this->default_swatch_customize_settings   = Helper::get_default_swatch_customize_settings();
		$this->default_button_customize_settings   = Helper::get_default_button_customize_settings();
		$this->default_swatch_color_array          = Helper::get_colors_list();
		$this->default_sold_out_customize_settings = Helper::get_default_sold_out_settings();

		add_action( 'wp_ajax_yaySwatches_get_all_data', array( $this, 'get_all_data' ) );
		add_action( 'wp_ajax_yaySwatches_get_affected_products_pagination', array( $this, 'get_affected_products_info' ) );

		add_action( 'wp_ajax_yaySwatches_get_product_permalink', array( $this, 'ger_product_permalink' ) );
		add_action( 'wp_ajax_get_available_variation', array( $this, 'get_available_variation' ) );
		add_action( 'wp_ajax_nopriv_get_available_variation', array( $this, 'get_available_variation' ) );
	}


	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function get_all_data() {
		check_ajax_referer( 'yay-swatches-nonce', 'nonce', true );
		$attributes             = wc_get_attribute_taxonomies();
		$attributes_custom_data = new stdClass();
		$attributes_name_array  = array();

		if ( $attributes ) {
			foreach ( $attributes as $attribute ) {
				$attribute_id    = $attribute->attribute_id;
				$attribute_name  = $attribute->attribute_name;
				$attribute_label = $attribute->attribute_label;
				array_push( $attributes_name_array, $attribute_name );

				$attribute_style = get_option( 'yay-swatches-attribute-style-' . $attribute_id, 'dropdown' );
				$is_archive_show = get_option( 'yay-swatches-attribute-show-archive-' . $attribute_id, 'no' );
				$terms           = get_terms( wc_attribute_taxonomy_name( $attribute_name ), array( 'hide_empty' => false ) );
				if ( in_array( $attribute_style, array( 'custom', 'dropdown' ) ) ) {
					foreach ( $terms as $term ) {
						$term_name                              = sanitize_title( $term->name );
						$default_swatch_color_by_term_name      = in_array( $term_name, array_keys( $this->default_swatch_color_array ), true ) ? $this->default_swatch_color_array[ $term_name ] : '#2271b1';
						$default_swatch_dual_color_by_term_name = in_array( $term_name, array_keys( $this->default_swatch_color_array ), true ) ? $this->default_swatch_color_array[ $term_name ] : '#2271b1';

						$swatch_color          = get_option( 'yay-swatches-swatch-color-' . $term->term_id, $default_swatch_color_by_term_name );
						$swatch_showHide       = get_option( 'yay-swatches-show-hide-color-' . $term->term_id, false );
						$swatch_dual_color     = get_option( 'yay-swatches-swatch-dual-color-' . $term->term_id, $default_swatch_dual_color_by_term_name );
						$swatch_image          = get_option( 'yay-swatches-swatch-image-' . $term->term_id, '' );
						$term->swatchColor     = $swatch_color;
						$term->showHideDual    = ( '1' === strtolower( $swatch_showHide ) || 'true' === strtolower( $swatch_showHide ) ) ? true : false;
						$term->swatchDualColor = $swatch_dual_color;
						$term->swatchImage     = $swatch_image;
					}
				}

				$attributes_custom_data->$attribute_name = array(
					'ID'              => $attribute_id,
					'name'            => $attribute_name,
					'label'           => $attribute_label,
					'style'           => $attribute_style,
					'is_archive_show' => $is_archive_show,
					'terms'           => $terms,
				);
			}
		}

		$swatch_customize_settings   = get_option( 'yay-swatches-swatch-customize-settings', $this->default_swatch_customize_settings );
		$button_customize_settings   = get_option( 'yay-swatches-button-customize-settings', $this->default_button_customize_settings );
		$sold_out_customize_settings = get_option( 'yay-swatches-sold-out-customize-settings', $this->default_sold_out_customize_settings );

		foreach ( $attributes_name_array as $attribute_name ) {
			$filtered_products = $this->get_affected_products_info( $attribute_name );
			foreach ( $filtered_products['listAffectedProducts'] as $product ) {
				$current_product   = wc_get_product( $product->ID );
				$image_id          = $current_product->get_image_id();
				$image_url         = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : wc_placeholder_img_src();
				$product->imageUrl = $image_url;
			}

			$attributes_custom_data->{$attribute_name}['affectedProductsQuantity'] = $filtered_products['affectedProductsQuantity'];
			$attributes_custom_data->{$attribute_name}['listAffectedProducts']     = $filtered_products['listAffectedProducts'];
		}

		$all_data_settings = array(
			'attributes_custom_data'        => wp_json_encode( $attributes_custom_data ),
			'swatch_customize_settings'     => wp_json_encode( $swatch_customize_settings ),
			'button_customize_settings'     => wp_json_encode( $button_customize_settings ),
			'sold_out_customize_Settings'   => wp_json_encode( $sold_out_customize_settings ),
			'collection_customize_settings' => wp_json_encode( Helper::get_default_collection_customize_settings() ),
		);
		wp_send_json( $all_data_settings );
	}

	public function get_affected_products_info( $attribute_name ) {
		check_ajax_referer( 'yay-swatches-nonce', 'nonce', true );
		$data          = isset( $_GET['data'] ) ? Helper::sanitize( $_GET ) : null;
		$attribute     = isset( $data['attribute'] ) ? $data['attribute'] : $attribute_name;
		$page          = isset( $data['page'] ) ? $data['page'] : 1;
		$post_per_page = 10;
		$skip          = ( $page - 1 ) * $post_per_page;

		$terms            = get_terms( wc_attribute_taxonomy_name( $attribute ), array( 'hide_empty' => false ) );
		$terms_slug_array = array();
		foreach ( $terms as $term ) {
			array_push( $terms_slug_array, $term->slug );
		}
		$term_filter_query             = array(
			'taxonomy' => 'pa_' . $attribute,
			'field'    => 'slug',
			'terms'    => $terms_slug_array,
			'operator' => 'IN',
		);
		$variable_product_filter_query = array(
			'taxonomy' => 'product_type',
			'field'    => 'slug',
			'terms'    => 'variable',
		);
		$args_query                    = array(
			'post_type'      => array( 'product' ),
			'posts_per_page' => -1,
			// 'nopaging'       => false,
			// 'paged'          => $page,
			'tax_query'      => array(
				$term_filter_query,
				$variable_product_filter_query,
			),
		);
		if ( $data ) {
			$args_query                               = array(
				'post_type'      => array( 'product' ),
				'posts_per_page' => $post_per_page,
				'nopaging'       => false,
				'paged'          => $page,
				'tax_query'      => array(
					$term_filter_query,
					$variable_product_filter_query,
				),
			);
			$filtered_products_list_by_attribute_name = new \WP_Query(
				$args_query
			);
			$filtered_products                        = array(
				'listAffectedProducts' => $filtered_products_list_by_attribute_name->posts,
			);
			foreach ( $filtered_products['listAffectedProducts'] as $product ) {
				$current_product   = wc_get_product( $product->ID );
				$image_id          = $current_product->get_image_id();
				$image_url         = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : wc_placeholder_img_src();
				$product->imageUrl = $image_url;
			}
			wp_send_json( $filtered_products['listAffectedProducts'] );
		} else {
			$filtered_products_list_by_attribute_name = new \WP_Query(
				$args_query
			);
			$filtered_products                        = array(
				'affectedProductsQuantity' => $filtered_products_list_by_attribute_name->post_count,
				'listAffectedProducts'     => array_slice( $filtered_products_list_by_attribute_name->posts, $skip, $post_per_page ),
			);
			return $filtered_products;
		}
	}

	public function ger_product_permalink() {
		check_ajax_referer( 'yay-swatches-nonce', 'nonce', true );
		if ( isset( $_GET['product_id'] ) ) {
			$product_id        = sanitize_title( $_GET['product_id'] );
			$product_id        = intval( $product_id );
			$product_permalink = get_the_permalink( $product_id );
			wp_send_json_success( $product_permalink );
		}
		wp_send_json_error();
	}

	public function get_available_variation() {
		$nonce = isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ? sanitize_title( $_POST['_wpnonce'] ) : false;
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'yay-swatches-nonce' ) ) {
			wp_send_json_error();
		}

		if ( isset( $_POST['product_id'] ) ) {
			$product_ID         = intval( sanitize_title( $_POST['product_id'] ) );
			$available_variants = wc_get_product( $product_ID )->get_available_variations( 'objects' );
			$results            = array();
			foreach ( $available_variants as $product_variant ) {
				if ( $product_variant->is_in_stock() ) {
					$results[] = $product_variant->get_attributes();
				}
			}
			wp_send_json( $results );
		}
	}

}
