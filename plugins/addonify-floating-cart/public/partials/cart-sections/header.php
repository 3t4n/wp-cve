<?php
/**
 * The Template for displaying header section of floating cart.
 *
 * This template can be overridden by copying it to yourtheme/addonify/floating-cart/header.php.
 *
 * @package Addonify_Floating_Cart\Public\Partials
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$cart_title = esc_html__( 'Cart', 'addonify-floating-cart' );
if ( '1' === $strings_from_setting ) {
	$saved_cart_title = addonify_floating_cart_get_option( 'cart_title' );
	if ( $saved_cart_title ) {
		$cart_title = $saved_cart_title;
	}
}
?>
<header class="adfy__woofc-header">
	<h3 class="adfy__woofc-title">
		<?php echo esc_html( $cart_title ); ?>
		<?php
		if ( addonify_floating_cart_get_option( 'display_cart_items_number' ) ) {

			$cart_items_count = 0;

			if ( addonify_floating_cart_get_option( 'cart_badge_items_total_count' ) === 'total_products' ) {
				$cart_items_count = count( WC()->cart->get_cart_contents() );
			} else {
				$cart_items_count = WC()->cart->get_cart_contents_count();
			}

			addonify_floating_cart_display_items_count( $cart_items_count, $strings_from_setting );
		}
		?>
	</h3>
	<div class="adfy__close-button">
		<button class="adfy__woofc-fake-button adfy__hide-woofc">
			<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg>
		</button>
	</div>
</header>
<?php
