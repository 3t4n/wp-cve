<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $post, $wpdb, $woocommerce, $compatibility_mode;
$compatibility_mode = 'true';
$product = new WC_Product( $product_id );
?>
<div class="woocommerce">
<div class="woo3dv-wrapper product woo3dv-compat">
	<div class="woocommerce-notices-wrapper">
	<?php if (function_exists('wc_print_notices')) echo wc_print_notices(); ?>
	</div>

	<div class="woo3dv-images">
		<?php

	echo woo3dv_woocommerce_single_product_image_html('', $product_id);
	//thumbs go here
	$attachment_ids = $product->get_gallery_image_ids();

	if ( $attachment_ids ) {
		$loop 		= 0;
		$columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
		?>
		<div class="thumbnails <?php echo 'columns-' . $columns; ?>"><?php
		foreach ( $attachment_ids as $attachment_id ) {

			$classes = array( 'zoom' );

			if ( $loop === 0 || $loop % $columns === 0 ) {
				$classes[] = 'first';
			}

			if ( ( $loop + 1 ) % $columns === 0 ) {
				$classes[] = 'last';
			}

			$image_class = implode( ' ', $classes );
			$props       = wc_get_product_attachment_props( $attachment_id, $post );

			if ( ! $props['url'] ) {
				continue;
			}

			echo 
				sprintf(
					'<a href="%s" class="%s" title="%s" data-rel="prettyPhoto[product-gallery]">%s</a>',
					esc_url( $props['url'] ),
					esc_attr( $image_class ),
					esc_attr( $props['caption'] ),
					wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0, $props )
				);

			$loop++;
		}

	?></div>
	<?php
	}

	?>


	</div>
	<div class="woo3dv-details">
	<input type="hidden" id="woo3dv_page_id" value="<?php echo get_the_ID();?>">
	<?php
		//the_title( '<h1 class="product_title entry-title">', '</h1>' );
	?>
<h1 class="product_title entry-title"><?php echo $product->get_title();?></h1>
<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $product->get_price_html(); ?></p>

		<div class="woocommerce-product-details__short-description">
			<?php
				echo nl2br($product->get_short_description());
			?>
		</div>

<?php
	woocommerce_template_single_add_to_cart();
?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'woocommerce' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' ); ?></span></span>

	<?php endif; ?>

	<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>

	<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>
<?php
	do_action( 'woocommerce_share' );
?>

	</div>
<?php
#	do_action( 'woocommerce_after_single_product_summary' );
?>
</div>
<?php #do_action( 'woocommerce_after_single_product' ); ?>
</div>