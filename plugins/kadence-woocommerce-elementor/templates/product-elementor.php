<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see      https://docs.woocommerce.com/document/template-structure/
 * @author   WooThemes
 * @package  WooCommerce/Templates
 * @version  3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Hook for print notices.
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}
global $post;
?>

<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div id="kwtb-wrap" class="kadence-woo-template-builder kwtb-wrap">
	<?php
		/**
		 * Hook for product builder.
		 * kadence_woocommerce_product_builder
		 *
		 * @hooked Kadence_Single_Products_Elementor -> get_product_content() - 10.
		 * @hooked Kadence_Single_Products_Elementor -> product_schema() - 20.
		 */
		do_action( 'kadence_woocommerce_product_builder', $post );
		?>
	</div>
</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
