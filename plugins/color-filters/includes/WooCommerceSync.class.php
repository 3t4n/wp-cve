<?php
/**
 * Class to handle all syncing WooCommerce taxonomies with UWCF taxonomies
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewduwcfWooCommerceSync' ) ) {
class ewduwcfWooCommerceSync {

	public function __construct() {

		add_action( 'save_post_product', array( $this, 'add_product_save_id' ) );
		add_action( 'admin_init', array( $this, 'sync_taxonomies' ), 99 );

		add_action( 'admin_init', array( $this, 'update_colors_product_page_display_attribute' ) );
		add_action( 'admin_init', array( $this, 'update_colors_used_for_variations_attribute' ) );
		add_action( 'admin_init', array( $this, 'update_sizes_product_page_display_attribute' ) );
		add_action( 'admin_init', array( $this, 'update_sizes_used_for_variations_attribute' ) );
	}

	/**
	 * Save the post_id when a product is updated, 
	 * so that the size and color attributes can also be updated
	 * @since 3.0.0
	 */
	public function add_product_save_id( $post_id ){

		// If this is an autosave, don't do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
	
		update_option( 'ewd_uwcf_update_product', $post_id );
	}

	/**
	 * Sync UWCF and WooCommerce taxonomy terms
	 * @since 3.0.0
	 */
	public function sync_taxonomies() {

		$post_id = get_option( 'ewd_uwcf_update_product' );

		if ( empty( $post_id ) ) { return; }

		$this->sync_color_taxonomy();
		$this->sync_size_taxonomy();

		delete_option( 'ewd_uwcf_update_product' );
	}

	/**
	 * Sync UWCF and WooCommerce color taxonomy terms
	 * @since 3.0.0
	 */
	public function sync_color_taxonomy() {
		global $ewd_uwcf_controller;

		$colors_visible = $ewd_uwcf_controller->settings->get_setting( 'color-filtering-product-page-display' ) ? true : false;
		$colors_variation = $ewd_uwcf_controller->settings->get_setting( 'color-filtering-colors-for-variations' ) ? true : false;

		$post_id = get_option( 'ewd_uwcf_update_product' );

		$uwcf_colors = wp_get_post_terms( $post_id, 'product_color' );

		$wc_color_term_ids = array();
		foreach ( $uwcf_colors as $color ) {

			$wc_color_term_ids[] = (int) get_term_meta( $color->term_id, 'EWD_UWCF_WC_Term_ID',true );
		}

		wp_set_post_terms( $post_id, $wc_color_term_ids, 'pa_ewd_uwcf_colors' );

		$attributes = is_array( get_post_meta( $post_id, '_product_attributes', true ) ) ? get_post_meta( $post_id, '_product_attributes', true ) : array();
		
		if ( ! in_array( 'pa_ewd_uwcf_colors', $attributes ) ) {

			$attributes['pa_ewd_uwcf_colors'] = array( 
				'name' => 'pa_ewd_uwcf_colors', 
				'value' => '', 
				'position' => sizeOf( $attributes ), 
				'is_visible' => $colors_visible, 
				'is_variation' => $colors_variation, 
				'is_taxonomy' => true
			);
		}
		
		update_post_meta( $post_id, '_product_attributes', $attributes );
	}

	/**
	 * Sync UWCF and WooCommerce size taxonomy terms
	 * @since 3.0.0
	 */
	public function sync_size_taxonomy() {
		global $ewd_uwcf_controller;

		$sizes_visible = $ewd_uwcf_controller->settings->get_setting( 'size-filtering-product-page-display' ) ? true : false;
		$sizes_variation = $ewd_uwcf_controller->settings->get_setting( 'size-filtering-colors-for-variations' ) ? true : false;

		$post_id = get_option( 'ewd_uwcf_update_product' );

		$uwcf_sizes = wp_get_post_terms( $post_id, 'product_size' );

		$wc_size_term_ids = array();
		foreach ( $uwcf_sizes as $size ) {

			$wc_size_term_ids[] = (int) get_term_meta( $size->term_id, 'EWD_UWCF_WC_Term_ID', true );
		}

		wp_set_post_terms( $post_id, $wc_size_term_ids, 'pa_ewd_uwcf_sizes' );

		$attributes = is_array( get_post_meta( $post_id, '_product_attributes', true ) ) ? get_post_meta( $post_id, '_product_attributes', true ) : array();
		
		if ( ! in_array( 'pa_ewd_uwcf_sizes', $attributes ) ) {

			$attributes['pa_ewd_uwcf_sizes'] = array( 
				'name' => 'pa_ewd_uwcf_sizes', 
				'value' => '', 
				'position' => sizeOf( $attributes ), 
				'is_visible' => $sizes_visible, 
				'is_variation' => $sizes_variation, 
				'is_taxonomy' => true
			);
		}
		
		update_post_meta( $post_id, '_product_attributes', $attributes );
	}

	public function update_colors_product_page_display_attribute() {
		global $wpdb;
		global $ewd_uwcf_controller;

		if ( ! isset( $_POST['ewd-uwcf-settings']['color-filtering-product-page-display'] ) ) { return; }

		$product_attribute_values = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key='_product_attributes'" ); 
		foreach ( $product_attribute_values as $product_attribute_value ) {
			
			$attributes = unserialize( $product_attribute_value->meta_value );
			foreach ( $attributes as $attribute_name => $product_attribute ) {

				if ( $attribute_name == 'pa_ewd_uwcf_colors' ) {

					$attributes[$attribute_name]['is_visible'] = intval( $ewd_uwcf_controller->settings->get_setting( 'color-filtering-product-page-display' ) ); 
					$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_value=%s WHERE meta_key='_product_attributes' AND post_id=%d", serialize( $attributes ), $product_attribute_value->post_id ) );
				}
			}
		}
	}

	public function update_colors_used_for_variations_attribute() {
		global $wpdb;
		global $ewd_uwcf_controller;

		if ( ! isset( $_POST['ewd-uwcf-settings']['color-filtering-colors-for-variations'] ) ) { return; }

		$product_attribute_values = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key='_product_attributes'" );
		foreach ( $product_attribute_values as $product_attribute_value ) {
			
			$attributes = unserialize( $product_attribute_value->meta_value );
			foreach ( $attributes as $attribute_name => $product_attribute ) {

				if ( $attribute_name == 'pa_ewd_uwcf_colors' ) {

					$attributes[$attribute_name]['is_variation'] = $ewd_uwcf_controller->settings->get_setting( 'color-filtering-colors-for-variations' );
					$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_value=%s WHERE meta_key='_product_attributes' AND post_id=%d", serialize( $attributes ), $product_attribute_value->post_id ) );
				}
			}
		}
	}

	public function update_sizes_product_page_display_attribute() {
		global $wpdb;
		global $ewd_uwcf_controller;

		if ( ! isset( $_POST['ewd-uwcf-settings']['size-filtering-product-page-display'] ) ) { return; }

		$product_attribute_values = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key='_product_attributes'" );
		foreach ( $product_attribute_values as $product_attribute_value ) {
			
			$attributes = unserialize( $product_attribute_value->meta_value );
			foreach ( $attributes as $attribute_name => $product_attribute ) {

				if ( $attribute_name == 'pa_ewd_uwcf_sizes' ) {

					$attributes[$attribute_name]['is_visible'] = $ewd_uwcf_controller->settings->get_setting( 'size-filtering-product-page-display' );
					$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_value=%s WHERE meta_key='_product_attributes' AND post_id=%d", serialize( $attributes ), $product_attribute_value->post_id ) );
				}
			}
		}
	}

	public function update_sizes_used_for_variations_attribute() {
		global $wpdb;
		global $ewd_uwcf_controller;

		if ( ! isset( $_POST['ewd-uwcf-settings']['size-filtering-sizes-for-variations'] ) ) { return; }

		$product_attribute_values = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key='_product_attributes'" );
		foreach ( $product_attribute_values as $product_attribute_value ) {
			
			$attributes = unserialize( $product_attribute_value->meta_value );
			foreach ( $attributes as $attribute_name => $product_attribute ) {

				if ( $attribute_name == 'pa_ewd_uwcf_sizes' ) {

					$attributes[$attribute_name]['is_variation'] = $ewd_uwcf_controller->settings->get_setting( 'size-filtering-sizes-for-variations' );
					$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_value=%s WHERE meta_key='_product_attributes' AND post_id=%d", serialize( $attributes ), $product_attribute_value->post_id ) );
				}
			}
		}
	}

	}
} // endif;