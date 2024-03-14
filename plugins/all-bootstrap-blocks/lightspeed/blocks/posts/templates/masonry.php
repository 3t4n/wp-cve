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

			<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'justify-content-end' : '' ?>">
				
				<?php $post_count = 0; while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
					<div class="col <?php echo in_array( $post_count, array( 0, 5) ) ? 'col-lg-6' : '' ?> mb-4 position-relative areoi-has-url">
						<?php if ( !empty( lightspeed_get_attribute( 'media_position', null ) ) && lightspeed_get_attribute( 'media_position', null ) == 'background' ) : ?>
							<?php lightspeed_item_with_background( null, true ) ?>
						<?php else : ?>
							<?php lightspeed_item( null, true ) ?>
						<?php endif; ?>						
					</div>
				<?php $post_count++; if ( $post_count == 6 ) $post_count = 0; endwhile; ?>

			</div>

			<?php lightspeed_post_pagination( $the_query ) ?>

		 	<?php wp_reset_postdata(); ?>

		</div>
	
	</div>
</div>