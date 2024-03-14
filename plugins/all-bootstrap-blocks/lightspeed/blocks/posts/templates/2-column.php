<?php
$the_query = lightspeed_get_posts();

$styles = '

';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100 position-relative">
	<div class="row h-100 align-items-center">

		<div class="col">

			<div class="row justify-content-<?php echo lightspeed_get_attribute( 'content_alignment', 'start' ) == 'center' ? 'center' : lightspeed_get_attribute( 'alignment' ) ?>">
				<div class="col">
					
					<?php lightspeed_content( 2, 'start', 'col-lg-6 col-xl-5' ) ?>

				</div>
			</div>

			<div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 justify-content-<?php echo lightspeed_get_attribute( 'content_alignment', 'start' ) == 'center' ? 'center' : lightspeed_get_attribute( 'alignment' ) ?> <?php echo 'text-' . lightspeed_get_attribute( 'content_alignment', 'start' ) ?>">
				
				<?php $post_count = 0; while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
					<div class="col mb-4 position-relative">
						<?php lightspeed_item( null, true ) ?>					
					</div>
				<?php $post_count++; endwhile; ?>

			</div>

			<?php lightspeed_post_pagination( $the_query ) ?>

		 	<?php wp_reset_postdata(); ?>

		</div>
	
	</div>
</div>