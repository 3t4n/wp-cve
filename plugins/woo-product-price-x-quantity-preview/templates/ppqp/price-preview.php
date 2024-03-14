<?php
/**
 * product price x quantity preview display
 *
 * This is a javascript-based template for product price x quantity preview (see https://codex.wordpress.org/Javascript_Reference/wp.template).
 * The values will be dynamically replaced after changing quantity.
 *
 * @version 0.1
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<script type="text/template" id="tmpl-ppqp-price-template">
	<?php
		$price_label = '<span class="price-label">' . __( 'Product Total:', 'woo-ppqp' ) . '</span>';
		$price_format = get_woocommerce_price_format();
		$currency_html = '<span class="currency woocommerce-Price-currencySymbol">{{{ data.currency }}}</span>';
		$price_html = '<span class="amount">{{{ data.price }}}</span>';
		ob_start();
		?>
		<p class="price product-page-price ">
			<span class="woocommerce-Price-amount amount">
				<?php echo $price_label; ?>
				<?php echo sprintf( $price_format, $currency_html, $price_html ); ?>
			</span>
		</p>
		<?php 
		echo apply_filters('ppqp_price_html', ob_get_clean(), $price_format, $currency_html, $price_html );
	?>
</script>
