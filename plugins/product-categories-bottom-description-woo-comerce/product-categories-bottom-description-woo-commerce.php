<?php
/*
Plugin Name: Product Categories Bottom Description for WooCommerce
Plugin URI: https://wordpress.org/plugins/product-categories-bottom-description-pcbdw-comerce
Description: Add a new content field to the bottom of your WooCommerce product categories, right after the products list. Improve your SEO and UX.
Author: Diego de Guindos
Author URI: https://diegoguindos.com
Version: 2.2.0
License: GPL2
*/

defined('ABSPATH') or die('Hey, what are you doing? STOP!');

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// WooCommerce is not active, do not execute the plugin
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	return;
}


// Register details product_cat meta.
add_action( 'init', 'pcbdw_product_cat_register_meta' );
function pcbdw_product_cat_register_meta() {

	register_meta( 'term', 'details', array(
		'type'              => 'string',
		'description'       => 'Sanitizes and saves the details custom meta field.',
		'single'            => true,
		'sanitize_callback' => 'pcbdw_sanitize_details',
		'show_in_rest'      => true,
	) );

}



// Sanitize the details custom meta field.
function pcbdw_sanitize_details( $details ) {
	return wp_kses_post( $details );
}



// Add a details metabox to the Add New Product Category page.
add_action( 'product_cat_add_form_fields', 'pcbdw_product_cat_add_details_meta' );
add_action( 'product_tag_add_form_fields', 'pcbdw_product_cat_add_details_meta' );
function pcbdw_product_cat_add_details_meta() {

	wp_nonce_field( 'pcbdw_product_category_bottom_description', 'pcbdw_product_category_bottom_description_nonce' );

	?>
	<div class="form-field">
		<label for="pcbdw-bottom-description-content"><?php esc_html_e( 'Bottom description', 'pcbdw' ); ?></label>
		<textarea name="pcbdw-bottom-description-content" id="pcbdw-bottom-description-content" rows="5" cols="40"></textarea>
		<p class="description"><?php esc_html_e( 'The content in this field will be shown after the products within the product category/tag.', 'pcbdw' ); ?></p>
	</div>
	<?php

}



// Add custom field to the Edit Product Category page
add_action( 'product_cat_edit_form_fields', 'pcbdw_product_cat_custom_fields', 10, 2 );
add_action( 'product_tag_edit_form_fields', 'pcbdw_product_cat_custom_fields', 10, 2 );
function pcbdw_product_cat_custom_fields( $term ) {

	// Title
	?>
	<tr class="form-field">
		<th scope="row" valign="top"></th>
		<td>
			<hr style="margin-bottom: 25px;">
			<h3>Product categories bottom description for Woocommerce</h3>
		</td>
	</tr>
	<?php


	// Add the option to select the position where the Product Category Description is displayed
	$display_position = get_term_meta($term->term_id, 'woo_bottom_description_display_position', true);
	if ( !$display_position ) {
		$display_position = 'woocommerce_after_shop_loop';
	}
	
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="woo_bottom_description_display_position">Where would you like to display the new product category description?</label></th>
		<td>
			<select name="woo_bottom_description_display_position" id="woo_bottom_description_display_position">
				<option value="woocommerce_before_main_content"<?php if($display_position=='woocommerce_before_main_content'){echo ' selected';} ?>>Before Product Category Title</option>
				<option value="woocommerce_archive_description"<?php if($display_position=='woocommerce_archive_description'){echo ' selected';} ?>>After Woocommerce Product Description</option>
				<option value="woocommerce_before_shop_loop"<?php if($display_position=='woocommerce_before_shop_loop'){echo ' selected';} ?>>Before products</option>
				<option value="woocommerce_after_shop_loop"<?php if($display_position=='woocommerce_after_shop_loop'){echo ' selected';} ?>>After products (default)</option>
				<option value="woocommerce_after_main_content"<?php if($display_position=='woocommerce_after_main_content'){echo ' selected';} ?>>After the main content</option>
			</select>
		</td>
	</tr>
	<?php


	// Add the Woocommerce bottom description metabox to the Edit Product Category page
	$product_category_bottom_description = get_term_meta( $term->term_id, 'details', true );
	if ( ! $product_category_bottom_description ) {
		$product_category_bottom_description = '';
	}
	$settings = array( 'textarea_name' => 'pcbdw-product-cat-bottom-description' );

	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="pcbdw-product-cat-bottom-description"><?php esc_html_e( 'Bottom description', 'pcbdw' ); ?></label></th>
		<td>
			<?php wp_nonce_field( 'pcbdw_product_category_bottom_description', 'pcbdw_product_category_bottom_description_nonce' ); ?>
			<?php wp_editor( wp_kses_post( $product_category_bottom_description ), 'pcbdw-product-cat-bottom-description', $settings ); ?>
			<p class="description"><?php esc_html_e( 'The content in this field will be shown after the products within the product category page.', 'pcbdw' ); ?></p>
			<p class="description"><?php esc_html_e( 'You can also hide this description by using the option below and show the description from anywhere in the template by using the shortcode: [woo-bottom-description].', 'pcbdw' ); ?></p>
			<p class="description"><?php esc_html_e( 'It is also possible to show any product category bottom description from any other with the following shortcode: [woo-bottom-description category_slug="my-category"].', 'pcbdw' ); ?></p>
		</td>
	</tr>
	<?php


	// Add the display/hide option on the Edit Product Category page
	$display_option = get_term_meta($term->term_id, 'woo_bottom_description_display_option', true);

	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="display_option">Hide bottom description</label></th>
		<td>
			<input type="checkbox" name="woo_bottom_description_display_option" id="display_option" value="1" <?php checked($display_option, 1); ?> />
			<label for="display_option">Check this option if you want to <u>hide</u> the bottom description on the category page.</label>
			<br>
			<hr style="margin-top: 25px;">
		</td>
	</tr>
	<?php

}



// Save all plugin fields
add_action('create_product_cat', 'save_woo_bottom_description_display_option');
add_action('edit_product_cat', 'save_woo_bottom_description_display_option');
add_action('create_product_tag', 'save_woo_bottom_description_display_option');
add_action('edit_product_tag', 'save_woo_bottom_description_display_option');
function save_woo_bottom_description_display_option($term_id) {


	// Save the position where the product category description is displayed
	$old_details = get_term_meta( $term_id, 'woo_bottom_description_display_position', true );
	$new_details = isset( $_POST['woo_bottom_description_display_position'] ) ? esc_textarea( $_POST['woo_bottom_description_display_position'] ) : '';

	if ( $old_details && '' === $new_details ) {
		delete_term_meta( $term_id, 'woo_bottom_description_display_position' );
	} else if ( $old_details !== $new_details ) {
		update_term_meta(
			$term_id,
			'woo_bottom_description_display_position',
			pcbdw_sanitize_details( $new_details )
		);
	}


	// Save Product Category description custom field
	if ( ! isset( $_POST['pcbdw_product_category_bottom_description_nonce'] ) || ! wp_verify_nonce( $_POST['pcbdw_product_category_bottom_description_nonce'], 'pcbdw_product_category_bottom_description' ) ) {
		return;
	}

	$old_details = get_term_meta( $term_id, 'details', true );
	$new_details = isset( $_POST['pcbdw-product-cat-bottom-description'] ) ? wp_kses_post( $_POST['pcbdw-product-cat-bottom-description'] ) : '';

	if ( $old_details && '' === $new_details ) {
		delete_term_meta( $term_id, 'details' );
	} else if ( $old_details !== $new_details ) {
		update_term_meta(
			$term_id,
			'details',
			pcbdw_sanitize_details( $new_details )
		);
	}


	// Save the product category description show/hide option
	$old_details = get_term_meta( $term_id, 'woo_bottom_description_display_option', true );
	$new_details = isset( $_POST['woo_bottom_description_display_option'] ) ? esc_textarea( $_POST['woo_bottom_description_display_option'] ) : '';

	if ( $old_details && '' === $new_details ) {
		delete_term_meta( $term_id, 'woo_bottom_description_display_option' );
	} else if ( $old_details !== $new_details ) {
		update_term_meta(
			$term_id,
			'woo_bottom_description_display_option',
			pcbdw_sanitize_details( $new_details )
		);
	}

}



// Display details meta on Product Category archives.
add_action('wp', function(){

	if ( ! is_tax( 'product_cat' ) ) {
		return;
	}

	$term = get_queried_object();
	$display_position = get_term_meta($term->term_id, 'woo_bottom_description_display_position', true);

	add_action( $display_position, 'pcbdw_product_cat_display_details_meta' );
	function pcbdw_product_cat_display_details_meta() {

		// Check if the "show description" option is selected to show/hide its content
		$term = get_queried_object();
		$display_option = get_term_meta($term->term_id, 'woo_bottom_description_display_option', true);
		$checked = ($display_option === '1') ? 'checked' : '';
		
		if ( !$checked ) {
			$t_id = get_queried_object()->term_id;
			$details = get_term_meta( $t_id, 'details', true );
			$formatted_details = wpautop($details);
			if ( '' !== $details ) {
				?>
				<div class="pcbdw-bottom-description-content">
					<?php echo apply_filters( 'the_content', wp_kses_post( $formatted_details ) ); ?>
				</div>
				<?php
			}
		}
	}
	
});



// Display details meta on Product Tag archives.
add_action('wp', function(){

	if ( ! is_tax( 'product_tag' ) ) {
		return;
	}

	$term = get_queried_object();
	$display_position = get_term_meta($term->term_id, 'woo_bottom_description_display_position', true);

	add_action( $display_position, 'pcbdw_product_tag_display_details_meta' );
	function pcbdw_product_tag_display_details_meta() {

		// Check if the "show description" option is selected to show/hide its content
		$term = get_queried_object();
		$display_option = get_term_meta($term->term_id, 'woo_bottom_description_display_option', true);
		$checked = ($display_option === '1') ? 'checked' : '';
		
		if ( !$checked ) {
			$t_id = get_queried_object()->term_id;
			$details = get_term_meta( $t_id, 'details', true );
			$formatted_details = wpautop($details);
			if ( '' !== $details ) {
				?>
				<div class="pcbdw-bottom-description-content">
					<?php echo apply_filters( 'the_content', wp_kses_post( $formatted_details ) ); ?>
				</div>
				<?php
			}
		}
	}
	
});



// Create the shortcode to display the custom metabox content
add_shortcode('woo-bottom-description', 'pcbdw_product_category_bottom_description_shortcode');
function pcbdw_product_category_bottom_description_shortcode($atts) {
	
	$atts = shortcode_atts(array(
		'category_slug' => ''
	), $atts);

	// If the category slug is provided, get the category by slug
	if (!empty($atts['category_slug'])) {
		$category = get_term_by('slug', $atts['category_slug'], 'product_cat');
		if ($category) {
			$details = get_term_meta($category->term_id, 'details', true);
			$formatted_details = wpautop($details);
			return $formatted_details;
		}
	}

	// If no category slug is provided or the category is not found, get the current category
	$current_category = get_queried_object();
	if ($current_category && !empty($current_category->term_id)) {
		$details = get_term_meta($current_category->term_id, 'details', true);
		$formatted_details = wpautop($details);
		return $formatted_details;
	}

	return ''; // If no category details are found, return an empty string
}