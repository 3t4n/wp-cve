<?php
/**
 * Order Customer Shipping Details
 *
 */

defined( 'ABSPATH' ) || exit;

	
     
?>

	<?php if ( $show_shipping ) : ?>
		<?php if($heading !== ''): ?>
			<h3 class="woo-ready-shipping-address"> <?php echo esc_html($heading); ?> </h3>  
		<?php endif; ?> 
		<address class="woo-ready-shipping">
			<?php echo wp_kses_post( $order->get_formatted_shipping_address( $shiping_default_content ) ); ?>
			<?php if ( $order->get_shipping_phone() ) : ?>
				<p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_shipping_phone() ); ?></p>
			<?php endif; ?>
		</address>

	<?php endif; ?>

	