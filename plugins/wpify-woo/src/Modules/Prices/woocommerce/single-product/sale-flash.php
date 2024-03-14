<?php
/**
 * Single Product Sale Flash
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

?>
<?php if ( $product->is_on_sale() ) : ?>

	<?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woocommerce' ) . '</span>', $post, $product ); ?>

<?php
else :
	$custom_prices = wpify_woo_container()->get( WpifyWoo\Modules\Prices\PricesModule::class )->get_setting( 'custom_prices' );

	if ( is_array( $custom_prices ) ) {
		foreach ( $custom_prices as $price ) {
			$custom_prices_meta = get_post_meta( $product->get_id(), '_custom_prices', true );
			$has_price          = ! empty( $custom_prices_meta ) && isset( $custom_prices_meta[ $price['uuid'] ] );

			if ( $has_price && isset( $price['badge_label'] ) && ! empty( $price['badge_label'] ) ) {
				echo '<span class="wpify-woo-prices__badge ' . ( $price['badge_class'] ?: 'onsale' ) . '">' . $price['badge_label'] . '</span>';
				break;
			}
		}
	}

endif;
