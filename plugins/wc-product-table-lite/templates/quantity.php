<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( 
	in_array( $product->get_type(), array( 'grouped', 'external' ) ) ||
	(
		! empty( $hide_if_sold_individually ) &&
		$product->is_sold_individually()
	)
){
	return;
}

$args = apply_filters( 'woocommerce_quantity_input_args', array(
	'input_id'     => uniqid( 'quantity_' ),
	'input_name'   => 'quantity',
	'input_value'  => '1',
	'max_value'    => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
	'min_value'    => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
	'step'         => apply_filters( 'woocommerce_quantity_input_step', 1, $product ),
	'pattern'      => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
	'inputmode'    => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
	'product_name' => $product ? $product->get_title() : '',
), $product );

// variation
if( $product->get_type() === 'variation' ){
	if( $parent_id = $product->get_parent_id() ){
		$variations = wcpt_get_variations( $product->get_parent_id() );
		foreach( $variations as $variation ){
			if( $variation['variation_id'] == $product->get_id() ){
	
				if( ! empty( $variation['max_qty'] ) ){
					$args['max_value'] = $variation['max_qty'];
				}
	
				if( ! empty( $variation['min_qty'] ) ){
					$args['min_value'] = $variation['min_qty'];
				}
				
				if( ! empty( $variation['step'] ) ){
					$args['step'] = $variation['step'];
				}			
			}
		}
	}
}

// Apply sanity to min/max args - min cannot be lower than 0.
$args['min_value'] = max( $args['min_value'], 0 );
$args['max_value'] = 0 < $args['max_value'] ? $args['max_value'] : '';

// Max cannot be lower than min if defined.
if ( 
	'' !== $args['max_value'] && 
	$args['max_value'] < $args['min_value'] 
) {
	$args['max_value'] = $args['min_value'];
}

extract( $args );

$controls_html_classes = '';

if( empty( $display_type ) ){
	$display_type = 'input';
}

if( empty( $controls ) ){
	$controls = 'browser';
}

if( 
	in_array( $controls, array('none', 'browser') ) ||
	'select' === $display_type
){
	$controls_html_classes .= 'wcpt-hide-controls';
}else{
	$controls_html_classes .= ' wcpt-controls-on-' . $controls . ' ';
}

if( $controls !== 'browser' ){
	$controls_html_classes .= ' wcpt-hide-browser-controls ';		
}

if( empty( $qty_label ) ){
	$qty_label = '';
}

if( empty( $max_qty ) ){
	$max_qty = 10;
}

if( ! isset( $initial_value ) ){
	$initial_value = 'min';
}

if( $initial_value == 'min' ){
	$value = $min_value;
}

if( $initial_value == 'empty' ){
	$value = '';
}

$wcpt_min_value = $min_value;
if( $initial_value === '0' ){
	$value = $min_value = '0';
}

if( $initial_value !== 'min' ){
	$reset_on_variation_change = false;
}

?>
<div class="quantity wcpt-quantity wcpt-noselect wcpt-display-type-<?php echo $display_type ?> <?php echo $controls_html_classes; ?> <?php echo $html_class; ?>">
	<?php if( $display_type === 'input' ): ?>
		<span class="wcpt-minus wcpt-qty-controller wcpt-noselect "></span
		><input 
			type="number" 
			id="<?php echo esc_attr( $input_id ); ?>" 
			class="input-text qty text" 
			<?php if( $product->get_sold_individually() ) echo 'disabled'; ?> 
			step="<?php echo esc_attr( $step ); ?>" 
			min="<?php echo esc_attr( $min_value ); ?>" 
			data-wcpt-min="<?php echo esc_attr( $wcpt_min_value ); ?>" 
			data-wcpt-return-to-initial="<?php echo ! isset( $return_to_initial ) || $return_to_initial; ?>"
			data-wcpt-reset-on-variation-change="<?php echo ! empty( $reset_on_variation_change ); ?>"
			max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>" 
			name="<?php echo esc_attr( $input_name ); ?>" 
			value="<?php echo $value; ?>" 
			title="<?php echo esc_attr_x( 'Quantity', 'Product quantity input tooltip', 'woocommerce' ) ?>" 
			size="4" 
			data-wcpt-initial-value="<?php echo $initial_value; ?>"
			pattern="<?php echo esc_attr( $pattern ); ?>" 
			inputmode="<?php echo esc_attr( $inputmode ); ?>" 
			autocomplete="off"
		/><span class="wcpt-plus wcpt-qty-controller wcpt-noselect"></span>

		<?php 
			// warnings

			// -- max
			if( empty( $qty_warning ) ){
				$qty_warning = 'Max: [max]';
			}
			$qty_warning = str_replace( '[max]', '<span class="wcpt-quantity-error-placeholder--max">'. $max_value .'</span>', $qty_warning );
			?>
			<div class="wcpt-quantity-error-message wcpt-quantity-error-message--max"><?php echo $qty_warning; ?></div>
			<?php

			// -- min 
			if( empty( $min_qty_warning ) ){
				$min_qty_warning = 'Min: [min]';
			}
			$min_qty_warning = str_replace( '[min]', '<span class="wcpt-quantity-error-placeholder--min">'. $min_value .'</span>', $min_qty_warning );
			?>
			<div class="wcpt-quantity-error-message wcpt-quantity-error-message--min"><?php echo $min_qty_warning; ?></div>
			<?php
			
			// -- step 
			if( empty( $qty_step_warning ) ){
				$qty_step_warning = 'Step: [step]';
			}
			$qty_step_warning = str_replace( '[step]', '<span class="wcpt-quantity-error-placeholder--step">'. $step .'</span>', $qty_step_warning );
			?>
			<div class="wcpt-quantity-error-message wcpt-quantity-error-message--step"><?php echo $qty_step_warning; ?></div>
			<?php			
			
		?>

	<?php else: ?>
		<select 
			class="wcpt-qty-select" 
			data-wcpt-qty-label="<?php echo esc_attr($qty_label); ?>"
			data-wcpt-max-qty="<?php echo $max_qty; ?>"
			min="<?php echo $wcpt_min_value; ?>"
		>
			<option value="<?php echo $wcpt_min_value; ?>"><?php echo esc_html($qty_label) . $wcpt_min_value; ?></option>
			<?php
				$val = $wcpt_min_value;
				if( ! empty( $max_value ) ){
					$max_qty = $max_value;
				}
				while( $val < $max_qty ){
					$val += $step;
					echo '<option value="'. $val .'">'. $val .'</option>';
				}
			?>
		</select>
	<?php endif; ?>
</div>
