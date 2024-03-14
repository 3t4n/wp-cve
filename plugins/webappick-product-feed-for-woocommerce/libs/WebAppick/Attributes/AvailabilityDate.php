<?php
/*
 * Adding availability date to products.
 *
 * */

namespace WebAppick\Attributes;

use CTXFeed\V5\Compatibility\WCMLCurrency;

class AvailabilityDate implements AttributeInterface {

    /**
     * Initializes hooks
     *
     * @return void
     * @since 4.4.14
     */
    public function __construct () {

        // Display Field
        add_action( 'woocommerce_product_options_inventory_product_data' , [&$this, 'admin_render_simple'], 11 ); // Simple product
        add_action( 'woocommerce_product_after_variable_attributes' , [&$this, 'admin_render_variable'], 11, 3 ); // Variable product

        // Save Field
        add_action('woocommerce_process_product_meta', [ &$this, 'admin_simple_save' ] ); // Simple product
        add_action('woocommerce_save_product_variation', [ &$this, 'admin_variable_save' ], 10, 2 ); // Variable product


    }

    /**
     * Renders availability date fields for simple product.
     *
     * @return void
     * @since 4.4.14
     */
    public function admin_render_simple() {

        global $woocommerce, $post;

        $availability_date = get_post_meta( $post->ID, 'woo_feed_availability_date', true );
		if( empty( $availability_date ) && is_plugin_active( 'woocommerce-multilingual/wpml-woocommerce.php' )){
			$originalId = apply_filters('woo_feed_original_post_id',  $post->ID );
			$availability_date = get_post_meta( $originalId, 'woo_feed_availability_date', true );
		}
        // Add Availability Date Field
        $field_data = array(
            'id' => "woo_feed_availability_date",
            'name' => "woo_feed_availability_date",
            'placeholder' => '',
            'label' => __('Availability Date', 'woo-feed'),
            'type' => 'date',
            'value' => esc_attr( $availability_date ),
            'desc_tip' => false,
            'description' => __( 'Set availability date for backorder products.', 'woo-feed' ),
        );

        woocommerce_wp_text_input( $field_data );

    }

	/**
	 * Renders availability date fields for variable products.
     *
     * @param int $loop
     *
     * @return void
     * @since 4.4.14
	 */
    public function admin_render_variable( $loop, $variation_data, $variation ) {

        global $post;

        $availability_date = get_post_meta( $variation->ID, 'woo_feed_availability_date_var', true );

        // Add Availability Date Field
        $field_data = array(
            'id' => "woo_feed_availability_date_var{$loop}",
            'name' => "woo_feed_availability_date_var[{$loop}]",
            'placeholder' => '',
            'label' => __('Availability Date', 'woo-feed'),
            'type' => 'date',
            'desc_tip' => true,
            'description' => __( 'Set availability date for backorder products.', 'woo-feed' ),
            'value' => esc_attr( $availability_date ),
            'wrapper_class' => 'form-row form-row-full',
        );

        woocommerce_wp_text_input( $field_data );

    }

	/**
     * Saves availability date fields for simple product
     *
	 * @param $post_id
     *
     * @return void
     * @since 4.4.14
	 */
    public function admin_simple_save( $post_id ) {
		$woo_feed_availability_date = !empty( $_POST['woo_feed_availability_date'] ) ? $_POST['woo_feed_availability_date'] : '';
        if( $woo_feed_availability_date ) {
            update_post_meta($post_id, 'woo_feed_availability_date', esc_attr( $woo_feed_availability_date ));
        }
    }

    /**
     * Saves availability date fields for variable product
     *
     * @param int $post_id
     * @param int $loop
     *
     * @return void
     * @since 4.4.14
     */
    public function admin_variable_save( $post_id, $loop ) {
		$var = !empty($_POST['woo_feed_availability_date_var'][$loop]) ? $_POST['woo_feed_availability_date_var'][$loop] : '';
        if( $var) {
            update_post_meta($post_id, 'woo_feed_availability_date_var', esc_attr( $var ));
        }
    }

	/**
     * Generates value for feed
     *
	 * @param $product
     *
	 * @return false|string
     * @since 4.4.14
	 */
    public function get_value( $product ){

        if( $product->get_stock_status() !== 'onbackorder' ) {
	        return '';
        }

        $value = get_post_meta( $product->get_id(), 'woo_feed_availability_date', true );

        if( isset( $value ) && !empty( $value ) && $value !== false ) {
	        return date( 'c', strtotime( $value ) );
        } else {
	        return false;
        }

    }
}
