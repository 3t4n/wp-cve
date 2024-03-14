<?php

/**
 * Template Name: Product Builder for WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(); ?>

<div class="vi-wpb-wrapper">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'vi-wpb-single_container' ); ?>>
			<header class="woopb-entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header>
			<?php $class = is_active_sidebar( 'woopb-sidebar' ) ? 'woopb-has-sidebar' : ''; ?>

			<div class="woopb-entry-content <?php echo esc_attr( $class ) ?>">
				<?php
				do_action( 'woocommerce_product_builder_single_content' );
				?>
			</div>
		</article>
	<?php endwhile; ?>
</div>

<?php get_footer(); ?>
