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
			<div class="row">
				<div class="align-self-start col-lg-6 col-xxl-5 areoi-col-content <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'offset-xxl-1 order-lg-1' : '' ?> position-sticky" style="top: 100px;">
					
					<?php lightspeed_content( 2, 'start', 'col-lg-6 col-xl-5' ) ?>

				</div>
				
				<div class="col-lg-6 <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? '' : 'offset-xxl-1' ?>">
					<div class="row row-cols-1 row-cols-md-2 areoi-parallax-component">
						<?php $post_count = 0; while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
							<div class="col mb-4 position-relative areoi-has-url">
								<?php lightspeed_item( null, true ) ?>
							</div>
						<?php $post_count++; endwhile; ?>
					</div>

					<?php lightspeed_post_pagination( $the_query ) ?>

		 			<?php wp_reset_postdata(); ?>
				</div>
			</div>
		</div>
		
	</div>

</div>