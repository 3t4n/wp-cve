<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! WC()->cart ){
	return;
}

$wcpt_settings = wcpt_get_settings_data();
$settings = $wcpt_settings['cart_widget'];

$total_qty = apply_filters( 'wcpt_cart_total_quantity', WC()->cart->cart_contents_count );
// total price
// -- subtotal
if( 
	! empty( $settings['cost_source'] ) && 
	$settings['cost_source'] == 'subtotal' 
){
	$cart_cost = WC()->cart->get_subtotal();

// -- total	
}else{ 
	$cart_cost =  WC()->cart->get_total('edit');

}

$total_price = apply_filters(
	'wcpt_cart_total_price', 
	wcpt_price($cart_cost)
);

if( 
	! $total_qty &&
	! $total_price  
){
	return;
}

$labels = $settings['labels'];
$locale = get_locale();

$strings = array();

if( ! empty( $labels ) ){
	foreach( $labels as $key => $translations ){
		$strings[$key] = array();
		$translations = preg_split ('/$\R?^/m', $translations);
		foreach( $translations as $translation ){
			$array = explode( ':', $translation );
			if( ! empty( $array[1] ) ){
				$strings[$key][ trim( $array[0] ) ] = stripslashes( trim( $array[1] ) );
			}else{
				$strings[$key][ 'default' ] = stripslashes( trim( $array[0] ) );
			}
		}
	}
}

ob_start();
?>
<style media="screen">
	@media(min-width:1200px){
		.wcpt-cart-widget{
			display: <?php echo $settings['toggle'] == 'enabled' ? 'inline-block' : 'none'; ?>;
			<?php
				if( 
					isset( $settings['style']['bottom'] ) &&
					trim( $settings['style']['bottom'] ) !== ""
				){
					?>
					bottom: <?php echo $settings['style']['bottom'] . 'px'; ?>;
					<?php
				}
			?>
		}
	}
	@media(max-width:1199px){
		.wcpt-cart-widget{
			display: <?php echo $settings['r_toggle'] == 'enabled' ? 'inline-block' : 'none'; ?>;
		}
	}

	.wcpt-cart-widget{
		<?php
			if( ! empty( $settings['style'] ) ){
				foreach( $settings['style'] as $prop => $val ){
					if( $prop == 'bottom' ) continue;

					if( ! empty( $val ) ){
						echo $prop . ' : ' . $val . '; ';
		 			}
				}
			}
		?>
	}

</style>
<?php
$style = ob_get_clean();

if( empty( $settings['cost_source'] ) ){
	$settings['cost_source'] = 'subtotal';
}

if( empty( $settings['link'] ) ){
	$settings['link'] = 'cart';
}

switch ( $settings['link'] ) {
	case 'checkout':

		$link_url = wc_get_checkout_url();		

		break;
	
	case 'custom_url':
		$link_url = ! empty( $settings['custom_url'] ) ? $settings['custom_url'] : wc_get_cart_url();

		break;

	default: // cart

		$link_url = wc_get_cart_url();
	
		break;
}

$hide = $total_qty ? false : true;

// maybe use defaults
foreach( $strings as $item => &$translations ){
	if( empty( $translations[ $locale ] ) ){
		if( ! empty( $translations[ 'default' ] ) ){
			$translations[ $locale ] = $translations[ 'default' ];			
		}else if( ! empty( $translations[ 'en_US' ] ) ){
			$translations[ $locale ] = $translations[ 'en_US' ];
		}
	}
}

?>
<div 
	class="wcpt-cart-widget <?php echo $total_qty ? '' : 'wcpt-hide'; ?>" 
	data-wcpt-href="<?php echo $link_url; ?>"
><?php echo $style; ?>
  <div class="wcpt-cw-half">
		<!-- top -->
		<div class="wcpt-cw__totals">
			<!-- total quantity -->
			<span class="wcpt-cw-qty-total">
				<span class="wcpt-cw-figure"><?php echo $total_qty; ?></span>
				<span class="wcpt-cw-text">
					<?php
						if( $total_qty > 1 ){
							echo ( ! empty( $strings['items'] ) && ! empty( $strings['items'][$locale] ) ) ?  $strings['items'][$locale] : __('Items', 'wc-product-table');
						}else{
							echo ( ! empty( $strings['item'] ) && ! empty( $strings['item'][$locale] ) ) ?  $strings['item'][$locale] : __('Item', 'wc-product-table');
						}
					?>
				</span>
			</span>
			<span class="wcpt-cw-separator">&nbsp;</span>
			<!-- total price -->
			<span class="wcpt-cw-price-total">
				<?php echo $total_price; ?>
			</span>
		</div>

		<!-- bottom -->
		<?php 
			if(
				! empty( $strings['extra_charges'] ) && 
				! empty( $strings['extra_charges'][$locale] )
			){
				echo '<div class="wcpt-cw-footer">';
				echo $strings['extra_charges'][$locale];		
				echo '</div>';
			}
		?>
	</div
	><a href="<?php echo apply_filters('wcpt_cart_widget_url', $link_url);?>" class="wcpt-cw-half">
      <span class="wcpt-cw-loading-icon"><?php wcpt_icon('loader'); ?></span>
      <span class="wcpt-cw-view-label">
				<?php 
					if( 
						! empty( $strings['view_cart'] ) &&
						! empty( $strings['view_cart'][$locale] )
					){
						echo $strings['view_cart'][$locale];
					}else{
						_e('View Cart', 'woocommerce');
					}
				?>
			</span>
      <span class="wcpt-cw-cart-icon">
				<?php 
					ob_start();
					wcpt_icon('shopping-bag'); 
					echo apply_filters('wcpt_cart_widget_icon_markup', ob_get_clean());
				?>
			</span>
  </a>
</div>
