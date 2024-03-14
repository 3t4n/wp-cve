<?php

if ( ! defined( 'LION_BADGES_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

?>
<div class="tab_options_group">
	<p class="field">
		<label for="<?php $this->input_id( 'categories', 'select' ); ?>"><?php _e( 'Categories', 'lionplugins' ); ?></label>
		<select class="js-lion-badges-get-product-categories" name="<?php $this->input_name( 'categories', 'select', true ); ?>" id="<?php $this->input_id( 'categories', 'select' ); ?>" multiple="multiple" style="width: 300px;">
			<?php $this->select_wc_product_cat_value( 'categories', 'select' ); ?>
		</select>
		<?php wp_nonce_field( 'lion_badges_get_product_categories', 'lion_badges_get_product_categories_nonce' ); ?>
	</p>
</div>
