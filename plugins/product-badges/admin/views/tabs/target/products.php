<?php
if ( ! defined( 'LION_BADGES_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

?>

<div class="tab_options_group">
	<p class="field">
		<input type="checkbox" name="<?php $this->input_name( 'products', 'display_for_all_sale_products' ); ?>" id="<?php $this->input_name( 'products', 'display_for_all_sale_products' ); ?>" value="1" <?php checked( $this->get_input_value( 'products', 'display_for_all_sale_products' ), '1' ); ?> /> <label for="<?php $this->input_name( 'products', 'display_for_all_sale_products' ); ?>" class="label-checkbox"><?php _e('Display for all products that are on sale', 'lionplugins'); ?></label>
	</p>

	<p class="field">
		<label for="<?php $this->input_name( 'products', 'select' ); ?>"><?php _e( 'Products', 'lionplugins' ); ?></label>
		<select id="<?php $this->input_name( 'products', 'select' ); ?>" class="js-lion-badges-get-products" name="<?php $this->input_name( 'products', 'select', true ); ?>" multiple="multiple" style="width: 300px;">
			<?php $this->select_wc_product_value( 'products', 'select' ); ?>
		</select>
		<?php wp_nonce_field( 'lion_badges_get_products', 'lion_badges_get_products_nonce' ); ?>
	</p>
</div>
