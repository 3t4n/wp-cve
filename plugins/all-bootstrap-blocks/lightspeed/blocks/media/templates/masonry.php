<?php  
$styles = '
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-media-col {
	margin-top: calc(var(--bs-gutter-x) * .5);
    margin-bottom: calc(var(--bs-gutter-x) * .5);
    position: relative;
}
';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100 position-relative">
	<div class="row align-items-center">

		<div class="col">
			<div class="row d-block areoi-parallax-component">

				<?php 
				$counter = 1;
				foreach ( lightspeed_get_attribute( 'gallery', array() ) as $media_key => $media ) : ?>
					<div style="float: <?php echo $counter == 8 ? 'right' : 'left' ?>" class="col-6 <?php echo ( in_array( $counter, array( 1, 8 ) ) ) ? 'col-lg-6' : 'col-lg-3' ?> <?php lightspeed_media_col_class() ?>">

						<div class="areoi-media-col-content rounded overflow-hidden">
							<?php lightspeed_spacer( 'square' ) ?>

							<?php lightspeed_gallery_media( $media ) ?>
						</div>

					</div>
				<?php 
					$counter++;
					if ( $counter == 11 ) $counter = 1;
				endforeach; ?>

				<div style="clear: both;"></div>

			</div>
		</div>
	
	</div>
</div>