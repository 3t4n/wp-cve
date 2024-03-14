<?php

add_action( 'woocommerce_product_options_pricing', 'pmcs_product_pricing_list' );
function pmcs_product_pricing_list() {
	$currencies = pmcs()->switcher->get_currencies();
	$default_code = pmcs()->switcher->get_woocommerce_currency();
	global $post;
	$id = $post->ID;
	?>
	<div class="pmcs-price-wrapper">
		<p class="form-field pmcs-price-field _pmcs_price_heading">
			<label><?php _e( 'Advanced price', 'pmcs' ); ?></label>
			<span class="wrap">
				<span class="label"><?php _e( 'Regular price', 'pmcs' ); ?></span>
				<span class="label"><?php _e( 'Sale price', 'pmcs' ); ?></span>
			</span>
		</p>
		<?php
		foreach ( $currencies as $code => $currency ) {
			$title = __( 'Price(%1$s)', 'pmcs' );
			$tip = $currency['display_text'];
			$symbol = get_woocommerce_currency_symbol( $code );

			$price = get_post_meta( $id, '_regular_price_' . $code, true );
			$sale = get_post_meta( $id, '_sale_price_' . $code, true );

			?>
			<p class="form-field pmcs-price-field _pmcs_price_<?php echo esc_attr( $code ); ?>">
				<label><?php printf( $title, $symbol ); ?></label>
				<span class="wrap">
					<input placeholder="<?php esc_attr_e( 'Regular price', 'pmcs' ); ?>" class="input-text wc_input_decimal" size="6" type="text" name="_regular_price_<?php echo esc_attr( $code ); ?>" value="<?php echo esc_attr( $price ); ?>">
					<input placeholder="<?php esc_attr_e( 'Sale price', 'pmcs' ); ?>" 	 class="input-text wc_input_decimal last" size="6" type="text" name="_sale_price_<?php echo esc_attr( $code ); ?>" value="<?php echo esc_attr( $sale ); ?>">
				</span>
				<?php echo wc_help_tip( $tip ); ?>
			</p>
			<?php
		}
		?>
	</div>
	<?php
}


add_action( 'woocommerce_process_product_meta', 'pmcs_save_price_fields', 10, 2 );
function pmcs_save_price_fields( $id, $post ) {
	$currencies = pmcs()->switcher->get_currencies();
	$default_code = pmcs()->switcher->get_woocommerce_currency();

	foreach ( $currencies as $code => $currency ) {
		$price = isset( $_POST[ '_regular_price_' . $code ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_regular_price_' . $code ] ) ) : '';
		$sale = isset( $_POST[ '_sale_price_' . $code ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_sale_price_' . $code ] ) ) : '';
		update_post_meta( $id, '_regular_price_' . $code, $price );
		update_post_meta( $id, '_sale_price_' . $code, $sale );

	}
}


// do_action( 'woocommerce_variation_options_pricing', $loop, $variation_data, $variation );
add_action( 'woocommerce_variation_options_pricing', 'pmcs_woocommerce_variation_options_pricing', 15, 3 );
function pmcs_woocommerce_variation_options_pricing( $loop, $variation_data, $variation ) {
	$currencies = pmcs()->switcher->get_currencies();
	$default_code = pmcs()->switcher->get_woocommerce_currency();
	$id = $variation->ID;
	?>
	<p class="p-variable form-row form-row-full">
		<a href="#" class="pmcs-show-price-variable"><?php _e( 'More price for currencies', 'pmcs' ); ?></a>
	</p>
	<div class="pmcs-price-variable-wrapper" style="display: none;">
		<?php
		foreach ( $currencies as $code => $currency ) {
			$tip    = $currency['display_text'];
			$symbol = get_woocommerce_currency_symbol( $code );
			$price  = get_post_meta( $id, '_regular_price_' . $code, true );
			$sale   = get_post_meta( $id, '_sale_price_' . $code, true );

			// echo wc_help_tip( $tip );
			?>
			<p class="pmcs-variable-price-field form-field variable_regular_price_<?php echo esc_attr( $code ); ?>_field form-row form-row-first">
				<label><?php printf( __( 'Regular price (%s)', 'pmcs' ), $symbol ); ?></label>
				<input type="text" class="short" name="variable_regular_price_<?php echo esc_attr( $code ) . '[' . $loop . ']'; ?>" value="<?php echo esc_attr( $price ); ?>" placeholder=""> 
			</p>
			<p class="pmcs-variable-price-field form-field variable_sale_price_<?php echo esc_attr( $code ); ?>_field form-row form-row-last">
				<label><?php printf( __( 'Sale price (%s)', 'pmcs' ), $symbol ); ?></label><input type="text" class="short" style="" name="variable_sale_price_<?php echo esc_attr( $code ) . '[' . $loop . ']'; ?>" value="<?php echo esc_attr( $sale ); ?>" placeholder=""> 
			</p>
			<?php
		}
		?>
	</div>
	<?php
}


add_action( 'woocommerce_save_product_variation', 'pmcs_woocommerce_save_product_variation', 15, 2 );
function pmcs_woocommerce_save_product_variation( $variation_id, $i ) {
	$currencies = pmcs()->switcher->get_currencies();
	$default_code = pmcs()->switcher->get_woocommerce_currency();

	foreach ( $currencies as $code => $currency ) {
		$rkey  = 'variable_regular_price_' . $code;
		$skey  = 'variable_sale_price_' . $code;
		$price = isset( $_POST[ $rkey ][ $i ] ) ? sanitize_text_field( wp_unslash( $_POST[ $rkey ][ $i ] ) ) : '';
		$sale  = isset( $_POST[ $skey ][ $i ] ) ? sanitize_text_field( wp_unslash( $_POST[ $skey ][ $i ] ) ) : '';
		update_post_meta( $variation_id, '_regular_price_' . $code, $price );
		update_post_meta( $variation_id, '_sale_price_' . $code, $sale );
	}
}
