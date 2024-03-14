<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( empty( $template ) ){
	return;
}

if( ! empty( $use_default_template ) ){
	if( 
		! empty( $variable_switch ) &&
		in_array( $product->get_type(), array( 'variable-subscription', 'variable' ) )
	){
		?>
		<div class="wcpt-variable-price-default-woocommerce-template wcpt-variable-switch <?php echo $html_class; ?>">
			<div class="wcpt-variable-switch__default"> 
				<?php echo $product->get_price_html(); ?>
			</div>
		</div>
		<?php
	}else{
		echo $product->get_price_html();

	}

	return;
}

$on_sale_class = '';
$sale_price = '';

$regular_template = $template;


$variable_switch_class = '';

// variable product
if( $product->get_type() === 'variable' ){

	$prices = $product->get_variation_prices( true ); 

	if( ! empty( $variable_switch ) ){
		$variable_switch_class = 'wcpt-variable-switch';
	}

	if ( empty( $prices['price'] ) ) {
		return apply_filters( 'woocommerce_variable_empty_price_html', '', $product );

	} else {
		$min_price     = apply_filters('wcpt_product_get_lowest_price', current( $prices['price'] ), $product);
		$max_price     = apply_filters('wcpt_product_get_highest_price', end( $prices['price'] ), $product);
		$min_reg_price = current( $prices['regular_price'] );
		$max_reg_price = apply_filters('wcpt_product_get_highest_price', end( $prices['regular_price'] ), $product);

		if ( $min_price !== $max_price ) {
			$template = $variable_template;

		} elseif ( 
			apply_filters( 'wcpt_product_is_on_sale', $product->is_on_sale(), $product ) && 
			$min_reg_price === $max_reg_price 
		) {
			$on_sale_class = 'wcpt-product-on-sale';
			$template = $sale_template;

		} else {
			// regular template
			// already assigned by default
		}

	}

// sale - simple product
}else if( apply_filters( 'wcpt_product_is_on_sale', $product->is_on_sale(), $product ) ){
	$on_sale_class = 'wcpt-product-on-sale';
	$template = $sale_template;
}

// grouped product
if( $product->get_type() === 'grouped' ){

	$prices = wcpt_get_grouped_product_price();

	if( gettype( $prices ) == 'string' ){
		$template = $prices;

	}else if( $prices['max_price'] !== $prices['min_price'] ){
		$template = $variable_template; 

	}

}

?>
<span
	class="wcpt-price <?php echo $html_class . ' ' . $on_sale_class . ' ' . $variable_switch_class; ?>"
	<?php if( $product->get_type() == 'variable' ): ?>
	data-wcpt-element-id="<?php echo $id; ?>"
	data-wcpt-lowest-price="<?php echo wcpt_price_decimal( $min_price ); ?>"
	data-wcpt-highest-price="<?php echo wcpt_price_decimal( $max_price ); ?>"
	data-wcpt-regular-price="<?php echo wcpt_price_decimal( $max_reg_price ); ?>"
	data-wcpt-sale-price="<?php echo wcpt_price_decimal( $min_price ) ?>"
	data-wcpt-variable-template="<?php echo $on_sale_class ? 'sale' : ($min_price !== $max_price ? 'variable' : 'regular'); ?>"
	<?php endif; ?>
>
	<?php echo wcpt_parse_2( $template, $product ) ?>
</span>
<?php

// print all templates
$table_data = wcpt_get_table_data();
$table_id = $table_data['id'];

if(	$product->get_type() == 'variable' ){
	if( empty( $GLOBALS['wcpt_' . $table_id . '_price_templates'] ) ){
		$GLOBALS['wcpt_' . $table_id . '_price_templates'] = array();
	}

	if( empty( $GLOBALS['wcpt_' . $table_id . '_price_templates'][$id] ) ){
		$GLOBALS['wcpt_' . $table_id . '_price_templates'][$id] = array(
			'regular' 	=> wcpt_parse_2( $regular_template, $product ),
			'sale'	 		=> wcpt_parse_2( $sale_template, $product ),
			'variable'	=> wcpt_parse_2( $variable_template, $product ),
		);
	}
}
