<?php

/**
 * @package WP Product Review Feed Manager/Functions
 * @version 1.0.0
 * @since 2.10.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds custom fields to the products inventory card that can be used in the feeds.
 */
function wppfm_create_gtin_wc_support_field() {

	// Add the Brand field.
	woocommerce_wp_text_input(
		array(
			'id'          => 'wppfm_product_brand',
			'label'       => 'Product brand',
			'class'       => 'wppfm_product_brand',
			'desc_tip'    => true,
			'description' => __( 'Brand name of the product. If the product has no brand name you can use the manufacturer or supplier name.', 'wp-product-feed-manager' ),
		)
	);

	// Add the GTIN field.
	woocommerce_wp_text_input(
		array(
			'id'          => 'wppfm_product_gtin',
			'label'       => 'Product GTIN',
			'class'       => 'wppfm_product_gtin',
			'desc_tip'    => true,
			'description' => __( 'GTIN refers to a products Global Trade Item Number. You can also use a UPC, EAN, JAN, ISBN or ITF-14 number here.', 'wp-product-feed-manager' ),
		)
	);

	// Add the MPN field.
	woocommerce_wp_text_input(
		array(
			'id'          => 'wppfm_product_mpn',
			'label'       => 'Product MPN',
			'class'       => 'wppfm_product_mpn',
			'desc_tip'    => true,
			'description' => __( 'Add your product\'s Manufacturer Part Number (MPN).', 'wp-product-feed-manager' ),
		)
	);
}

add_action( 'woocommerce_product_options_inventory_product_data', 'wppfm_create_gtin_wc_support_field' );

/**
 * Saves the custom fields' data.
 *
 * @param mixed     $post_id    Post ID of the product.
 */
function wppfm_save_custom_fields( $post_id ) {
	$product = wc_get_product( $post_id );

	// Get the custom fields' data.
	$brand = $_POST['wppfm_product_brand'] ?? '';
	$gtin  = $_POST['wppfm_product_gtin'] ?? '';
	$mpn   = $_POST['wppfm_product_mpn'] ?? '';

	// Save the custom fields' data.
	$product->update_meta_data( 'wppfm_product_brand', sanitize_text_field( $brand ) );
	$product->update_meta_data( 'wppfm_product_gtin', sanitize_text_field( $gtin ) );
	$product->update_meta_data( 'wppfm_product_mpn', sanitize_text_field( $mpn ) );

	$product->save();
}

add_action( 'woocommerce_process_product_meta', 'wppfm_save_custom_fields' );

/**
 * Adds custom fields to the products inventory card of the product variations.
 *
 * @param   array   $loop
 * @param   object  $variation_data
 * @param   object  $variation
 *
 * @noinspection PhpUnusedParameterInspection*/
function wppfm_create_mpn_wc_variation_support_field( $loop, $variation_data, $variation ) {

	echo '<div class="options_group form-row form-row-full">';

	// Add the MPN text field to the variation cards.
	woocommerce_wp_text_input(
		array(
			'id'          => 'wppfm_product_mpn[' . $variation->ID . ']',
			'label'       => __( 'Product MPN', 'wp-product-feed-manager' ),
			'desc_tip'    => true,
			'description' => __( 'Add your product\'s Manufacturer Part Number (MPN).', 'wp-product-feed-manager' ),
			'value'       => get_post_meta( $variation->ID, 'wppfm_product_mpn', true ),
		)
	);

	// Add the GTIN text field to the variation cards.
	woocommerce_wp_text_input(
		array(
			'id'          => 'wppfm_product_gtin[' . $variation->ID . ']',
			'label'       => 'Product GTIN',
			'desc_tip'    => true,
			'description' => __( 'GTIN refers to a products Global Trade Item Number. You can also use a UPC, EAN, JAN, ISBN or ITF-14 number here.', 'wp-product-feed-manager' ),
			'value'       => get_post_meta( $variation->ID, 'wppfm_product_gtin', true ),
		)
	);

	echo '</div>';
}

add_action( 'woocommerce_variation_options', 'wppfm_create_mpn_wc_variation_support_field', 10, 3 );

/**
 * Saves the custom fields data of the product variations.
 *
 * @param   int     $post_id
 */
function wppfm_save_variation_custom_fields( $post_id ) {

	// Get the variations mpn and gtin.
	$woocommerce_mpn_field = $_POST['wppfm_product_mpn'][ $post_id ];
	$woocommerce_gtin_field = $_POST['wppfm_product_gtin'][ $post_id ];

	// Update.
	update_post_meta( $post_id, 'wppfm_product_mpn', sanitize_text_field( $woocommerce_mpn_field ) );
	update_post_meta( $post_id, 'wppfm_product_gtin', sanitize_text_field( $woocommerce_gtin_field ) );
}

add_action( 'woocommerce_save_product_variation', 'wppfm_save_variation_custom_fields', 10, 2 );

/**
 * Adds the custom fields to the products quick edit form.
 */
function wppfm_show_wc_quick_edit_custom_fields() {
	// Add the Brand field.
	?>
	<label>
		<span class="title"><?php esc_html_e('Brand', 'woocommerce'); ?></span>
		<span class="input-text-wrap">
            <input type="text" name="wppfm_product_brand" class="text wppfm_product_brand" value="">
        </span>
	</label>
	<br class="clear" />
	<?php

	// Add the GTIN field.
	?>
	<label>
		<span class="title"><?php esc_html_e('GTIN', 'woocommerce'); ?></span>
		<span class="input-text-wrap">
            <input type="text" name="wppfm_product_gtin" class="text wppfm_product_gtin" value="">
        </span>
	</label>
	<br class="clear" />
	<?php

	// Add the MPN field.
	?>
	<label>
		<span class="title"><?php esc_html_e('MPN', 'woocommerce'); ?></span>
		<span class="input-text-wrap">
            <input type="text" name="wppfm_product_mpn" class="text wppfm_product_mpn" value="">
        </span>
	</label>
	<br class="clear" />
	<?php
}

add_action( 'woocommerce_product_quick_edit_start', 'wppfm_show_wc_quick_edit_custom_fields' );

/**
 * Adds the custom fields data to the products quick edit form by placing the data in a hidden data field.
 *
 * @param   string  $column
 * @param   int     $post_id
 */
function wppfm_add_wc_quick_edit_custom_fields_data( $column, $post_id ){
	if ( 'name' !== $column ) {
		return;
	}

	echo '<div id="wppfm_product_brand_data_' . $post_id . '" hidden>' . esc_html( get_post_meta( $post_id, 'wppfm_product_brand', true ) ) . '</div>
	<div id="wppfm_product_gtin_data_' . $post_id . '" hidden>' . esc_html( get_post_meta( $post_id, 'wppfm_product_gtin', true ) ) . '</div>
	<div id="wppfm_product_mpn_data_' . $post_id . '" hidden>' . esc_html( get_post_meta( $post_id, 'wppfm_product_mpn', true ) ) . '</div>';
}

add_action( 'manage_product_posts_custom_column', 'wppfm_add_wc_quick_edit_custom_fields_data', 10, 2 );

/**
 * Populates the custom fields values in the quick edit form as soon as the quick edit for is opened.
 */
function wppfm_populate_wc_quick_edit_custom_fields() {
	?>
	<script type="text/javascript">
		(function($) {
			$('#the-list').on('click', '.editinline', function() {

				var post_id = $(this).closest('tr').attr('id');
				post_id = post_id.replace('post-', '');

				var brand_field = $('#wppfm_product_brand_data_' + post_id).text();
				$('input[name="wppfm_product_brand"]', '.inline-edit-row').val(brand_field);

				var gtin_field = $('#wppfm_product_gtin_data_' + post_id).text();
				$('input[name="wppfm_product_gtin"]', '.inline-edit-row').val(gtin_field);

				var mpn_field = $('#wppfm_product_mpn_data_' + post_id).text();
				$('input[name="wppfm_product_mpn"]', '.inline-edit-row').val(mpn_field);
			});
		})(jQuery);		</script>
	<?php
}

add_action( 'admin_footer', 'wppfm_populate_wc_quick_edit_custom_fields' );

/**
 * Saves the custom fields data of the products quick edit form.
 *
 * @param   object  $product
 */
function wppfm_save_wc_quick_edit_custom_fields( $product ) {
	if ( function_exists('wppfm_create_gtin_wc_support_field' ) ) {
		// Get the custom fields' data.
		$brand = $_POST['wppfm_product_brand'] ?? '';
		$gtin  = $_POST['wppfm_product_gtin'] ?? '';
		$mpn   = $_POST['wppfm_product_mpn'] ?? '';

		// Save the custom fields' data.
		$product->update_meta_data( 'wppfm_product_brand', sanitize_text_field( $brand ) );
		$product->update_meta_data( 'wppfm_product_gtin', sanitize_text_field( $gtin ) );
		$product->update_meta_data( 'wppfm_product_mpn', sanitize_text_field( $mpn ) );

		$product->save();
	}
}

add_action( 'woocommerce_product_quick_edit_save', 'wppfm_save_wc_quick_edit_custom_fields' );
