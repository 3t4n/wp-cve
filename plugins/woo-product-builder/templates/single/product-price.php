<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
    <div class="woopb-product-price"><?php echo wp_kses_post( $price_html ); ?></div>
<?php endif; ?>
