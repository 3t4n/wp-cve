<?php
/**
 * Class to handle WooCommerce filtering based on user selections
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewduwcfWooCommerceFiltering' ) ) {
class ewduwcfWooCommerceFiltering {

	public function __construct() {

		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'add_product_attribute_information' ) );

		add_action( 'woocommerce_product_query', array( $this, 'modify_wc_query' ) );

		add_filter( 'woocommerce_attribute_label', array( $this, 'custom_attribute_label' ) );
	}

	/**
	 * Adds product filtering information after each product
	 * @since 3.0.0
	 */
	public function add_product_attribute_information() {

		ewd_uwcf_load_view_files();

		$filtering = new ewduwcfViewProductFilters( array() );

		echo $filtering->render();
	}

	/**
	 * Modifies the WooCommerce product query to add rating, in-stock and on-sale filtering
	 * @since 3.0.0
	 */
	public function modify_wc_query( $query ) {
		global $wpdb;
		global $ewd_urp_controller;
		global $ewd_uwcf_controller;

		$meta_query = $query->get( 'meta_query' );

		$tax_query = $query->get( 'tax_query' );
	
		if ( $ewd_uwcf_controller->settings->get_setting( 'ratings-filtering-ratings-type' ) == 'ultimate_reviews' and ! empty( $ewd_urp_controller ) ) { 
				
			$modifier = 5 / $ewd_urp_controller->settings->get_setting( 'maximum-score' );
			$key = 'EWD_URP_Average_Score'; 
		}
		else { 

			$modifier = 1;
			$key = '_wc_average_rating'; 
		}

		if ( isset( $_GET['min_rating'] ) and isset( $_GET['max_rating'] ) ) {

			$meta_query[] = array(
				'key' => '_wc_average_rating',
				'value' => array(
					$modifier * intval( $_GET['min_rating'] ),
					$modifier * intval( $_GET['max_rating'] )
				),
				'compare' => 'BETWEEN'
			);
		}
		elseif ( isset( $_GET['min_rating'] ) ) {

			$meta_query[] = array(
				'key' => '_wc_average_rating',
				'value' => $modifier * intval( $_GET['min_rating'] ),
				'compare' => '>='
			);
		}
		elseif ( isset( $_GET['max_rating'] ) ) {

			$meta_query[] = array(
				'key' => '_wc_average_rating',
				'value' => $modifier * intval( $_GET['max_rating'] ),
				'compare' => '<='
			);
		}
	
		if ( isset( $_GET['instock'] ) ) {

			$meta_query[] = array(
				'key' => '_stock_status',
				'value' => 'instock',
				'compare' => '='
			);
		}
	
		if ( isset( $_GET['onsale'] ) ) {

			$product_ids_on_sale = wc_get_product_ids_on_sale(); 
			$query->set( 'post__in', $product_ids_on_sale );
		}

		foreach ( ewd_uwcf_get_woocommerce_taxonomies() as $attribute_taxonomy ) {

			if ( isset( $_GET[ $attribute_taxonomy->attribute_name ] ) ) {

				$tax_query[] = array(
					'taxonomy' => 'pa_' . $attribute_taxonomy->attribute_name,
					'field'	=> 'slug',
					'terms' => explode( ',', sanitize_text_field( $_GET[ $attribute_taxonomy->attribute_name ] ) ),
				);
			}

		}
	
		$query->set( 'meta_query', $meta_query );

		$query->set( 'tax_query', $tax_query );
	}

	/**
	 * Adjust the labels of the attributes
	 * @since 3.0.0
	 */
	public function custom_attribute_label( $label ) {
		global $ewd_uwcf_controller;

		if ( $label == 'UWCF Colors' ) {

			$label = $ewd_uwcf_controller->settings->get_setting( 'label-product-page-colors' );
		}

		if ( $label == 'UWCF Sizes' ) {

			$label = $ewd_uwcf_controller->settings->get_setting( 'label-product-page-sizes' );
		}
		return $label;
	}

	}
} // endif;