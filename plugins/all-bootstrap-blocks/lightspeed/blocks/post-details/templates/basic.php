<?php
$color 			= lightspeed_get_attribute( 'introduction_color', lightspeed_get_default_color( 'text' ) );
$styles = '

';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100 position-relative">

	<div class="row row-cols-1 row-cols-md-3 text-center <?php echo $color; ?>">
		<?php 
		
		if ( lightspeed_get_attribute( 'include_post_author', null ) ) : 
		?>
			<div class="col">
				<?php lightspeed_author_url( $color ) ?>
			</div>
		<?php endif; ?>

		<?php if ( lightspeed_get_attribute( 'include_post_date', null ) ) : ?>
			<div class="col">
				<?php echo get_the_date() ? get_the_date() : date( 'd/M/Y' ) ?>
			</div>
		<?php endif; ?>

		<?php if ( lightspeed_get_attribute( 'include_post_categories', null ) ) : ?>
			<div class="col">
				<?php 
				$categories 	= get_categories();
				if ( ! empty( $categories ) ) {
					echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" class="' . $color . '">' . esc_html( $categories[0]->name ) . '</a>';
				}
				?>
			</div>
		<?php endif; ?>
	</div>

</div>