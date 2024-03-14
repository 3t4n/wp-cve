<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="hentry-inner">
		<?php
		// Check if has thumbnail
		if ( has_post_thumbnail( get_the_ID() ) ) :
			?>
		<div class="food-menu-thumbnail post-thumbnail">
			<a href="<?php echo get_the_permalink(); ?>">
			<?php echo get_the_post_thumbnail( get_the_ID(), array( 50, 50 ) ); ?>			
			</a>
		</div>
		<?php endif; ?>
		<div class="entry-container">
			<div class="entry-description">
				<header class="entry-header">
					<?php the_title( '<h2 class="entry-title"><a href="' . get_the_permalink() . '">', '</a></h2>' ); ?>
				</header>

				<div class="entry-content">
					<?php the_excerpt(); ?>
				</div>
			</div>

			<div class="entry-price">
				<p class="item-price"><?php echo esc_html( get_post_meta( get_the_ID(), 'ect_food_price', true ) ); ?></p>
			</div>
		</div>
	</div><!-- .hentry-inner -->
</article><!-- .hentry -->
