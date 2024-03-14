<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


add_action( 'product_cat_edit_form_fields', 'wt_fbfeed_category_form_fields', 10, 1 );
add_action( 'product_cat_add_form_fields', 'wt_fbfeed_category_form_fields', 10, 1 );

add_action( 'edit_product_cat', 'wt_fbfeed_category_form_save', 10, 1 );
add_action( 'create_category', 'wt_fbfeed_category_form_save', 10, 1 );

function wt_fbfeed_category_form_fields( $category ) {


	$fb_category_id = '';
	if ( current_filter() == 'product_cat_edit_form_fields' ) {
		$fb_category_id = get_term_meta( $category->term_id, 'wt_fb_category', true );
	}
	?>

	<tr class="form-field">
		<th scope="row" valign="top"><label for="wt_facebook_category"><?php esc_html_e(' Facebook Category', 'webtoffee-product-feed' ); ?></label></th>
		<td>
                    <select name="wt_facebook_category" class="wc-enhanced-select">
	<?php echo wt_fb_category_dropdown( $fb_category_id ); ?>
			</select>

			<p class="description"><?php esc_html_e(' The Facebook Category corresponding to this category in the website.', 'webtoffee-product-feed'); ?></p></td>
	</tr>
	<input type="hidden" name="wt_category_edit_nonce" value="<?php echo wp_create_nonce( 'wt_category_edit_nonce' ); ?>" />

	<?php
}

function wt_fbfeed_category_form_save( $term_id ) {


	if ( isset( $_POST[ 'wt_facebook_category' ] ) ) {
		if(! wp_verify_nonce( $_POST['wt_category_edit_nonce'], 'wt_category_edit_nonce' )){
			return false;
		}

		$wt_fb_category = absint( $_POST[ 'wt_facebook_category' ] );
		if(0 == $wt_fb_category){
			delete_term_meta($term_id, 'wt_fb_category');
		}else{
		update_term_meta( $term_id, 'wt_fb_category', $wt_fb_category );
		}
	}
}
