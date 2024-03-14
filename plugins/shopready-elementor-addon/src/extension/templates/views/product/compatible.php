<?php
/**
 * The Template for displaying all single products
 * Override Woocommerce Template 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


get_header( 'shop' ); ?>

	<div class="shop-ready-product-details-container container grid-container uk-container pure-g">
		<div class="shop-ready-inner-content">
				
		<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php
			/**
			 * woocommerce_after_main_content hook.
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'woocommerce_after_main_content' );
		?>

		
		</div>
	</div>
<?php
get_footer( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
