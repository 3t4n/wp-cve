<?php
/**
 * Product recommendations screen html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$cc_product_recommendation = get_option( 'cc_product_recommendation' );
if ( empty( $product_id ) || 'enabled' !== $cc_product_recommendation ) {
	return;
}

$product = wc_get_product( $product_id );
$orderby = 'rand';
$order   = 'desc';
$upsells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' ), $orderby, $order );

// GET BEST SELLING PRODUCTS
$best_seller_args = array(
	'post_type'           => 'product',
	'post_status'         => 'publish',
	'posts_per_page'      => 5,
	'ignore_sticky_posts' => 1,
	'meta_key'            => 'total_sales',
	'orderby'             => 'meta_value_num',
	'order'               => 'DESC',
	'fields'              => 'ids',
	'post__not_in'        => array( $product_id ),
	'tax_query'           => array(
		array(
			'taxonomy' => 'product_visibility',
			'terms'    => array( 'exclude-from-catalog' ),
			'field'    => 'name',
			'operator' => 'NOT IN',
		),
	),
);
$best_seller_loop = query_posts( $best_seller_args );

/* Get up-sells products data */
$final_upsell_products = array();
if ( ! empty( $upsells ) ) {
	foreach ( $upsells as $upsell ) {
		$upsell_product_data   = wc_get_product( $upsell->get_id() );
		$upsell_product_status = $upsell_product_data->get_status();
		if ( 'draft' !== $upsell_product_status ) {
			$final_upsell_products[] = $upsell->get_id();
		}
	}
} else {
	foreach ( $best_seller_loop as $best_seller_id ) {
		$final_upsell_products[] = $best_seller_id;
	}
}
?>

<?php if ( ! empty( $final_upsell_products ) ) { ?>
	<div class="cc-pl-info-wrapper">
		<div class="cc-pl-upsells">
			<label><?php esc_html_e( 'We think you might also like...', 'caddy' ); ?></label>
			<div class="cc-pl-upsells-slider">
				<?php
				foreach ( $final_upsell_products as $upsells_product_id ) {

					$product          = wc_get_product( $upsells_product_id );
					$product_image    = $product->get_image();
					$product_name     = $product->get_name();
					$product_price    = $product->get_price_html();
					$product_link     = get_permalink( $upsells_product_id );
					$add_to_cart_text = $product->add_to_cart_text();
					?>
					<div class="slide">
						<div class="up-sells-product">
							<div class="cc-up-sells-image"><a href="<?php echo esc_url( $product_link ); ?>"><?php echo $product_image; ?></a></div>
							<div class="cc-up-sells-details">
								<a href="<?php echo esc_url( $product_link ); ?>" class="title"><?php echo $product_name; ?></a>
								<div class="cc_item_total_price">
									<span class="price"><?php echo $product_price; ?></span>
								</div>
								<?php
								if ( $product->is_type( 'simple' ) ) {
									?>
									<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
									      method="post" enctype='multipart/form-data'>
										<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"
										        class="single_add_to_cart_button button cc-button-sm alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
									</form>
								<?php } else { ?>
									<a class="button cc-button-sm" href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"><?php echo esc_html( $add_to_cart_text ); ?></a>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<div class="caddy-prev"><i class="ccicon-cheveron-left" aria-hidden="true"></i></div>
			<div class="caddy-next"><i class="ccicon-cheveron-right" aria-hidden="true"></i></div>
		</div>
	</div>
	<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				$( '.cc-pl-upsells-slider' ).not( '.slick-initialized' ).slick( {
					infinite: true,
					speed: 300,
					slidesToShow: 1,
					slidesToScroll: 1,
					prevArrow: $( '.caddy-prev' ),
					nextArrow: $( '.caddy-next' ),
					responsive: [
						{
							breakpoint: 1024,
							settings: {
								slidesToShow: 1,
								slidesToScroll: 1,
							}
						},
						{
							breakpoint: 600,
							settings: {
								slidesToShow: 1,
								slidesToScroll: 1
							}
						},
						{
							breakpoint: 480,
							settings: {
								slidesToShow: 1,
								slidesToScroll: 1
							}
						}
					]
				} );
			} );
	</script>
	<?php
}
