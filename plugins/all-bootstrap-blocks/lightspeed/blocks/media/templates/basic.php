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
			<div class="row align-items-start areoi-parallax-component areoi-transition-none">

				<?php foreach ( lightspeed_get_attribute( 'gallery', array() ) as $media_key => $media ) : ?>
					<div class="col-6 col-lg-3 <?php lightspeed_media_col_class() ?>">

						<div class="areoi-media-col-content rounded overflow-hidden">
							<?php lightspeed_spacer( 'square' ) ?>

							<?php if ( $media['type'] == 'image' ) : ?>
								<img 
								src="<?php echo $media['url'] ?>" 
								class="d-block" 
								alt="<<?php echo !empty( $media['alt'] ) ? $media['alt'] : '' ?>" 
								width="<?php echo !empty( $media['width'] ) ? $media['width'] : '' ?>" 
								height="<?php echo !empty( $media['height'] ) ? $media['height'] : '' ?>"
								>
							<?php else : ?>
								<video src="<?php echo $media['url'] ?>" muted playsinline autoplay loop></video>
							<?php endif; ?>
						</div>

					</div>
				<?php endforeach; ?>

			</div>
		</div>
	
	</div>
</div>