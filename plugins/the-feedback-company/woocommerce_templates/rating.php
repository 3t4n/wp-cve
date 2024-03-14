<?php
/**
 * WooCommerce template for overriding the product rating with our own widget
 */

if (!defined( 'ABSPATH'))
	exit();
?>

<div class="woocommerce-product-rating">
	<?php echo feedbackcompany_woocommerce::output_rating(); ?>
</div>
