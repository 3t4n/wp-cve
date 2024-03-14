<?php  
$styles = '
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-media-col {
    position: relative;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block {
	padding: 0;
}
';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container-fluid h-100">
	<div class="row align-items-center">

		<div class="col">
			<div class="row align-items-start">

				<?php foreach ( lightspeed_get_attribute( 'gallery', array() ) as $media_key => $media ) : ?>
					<div class="col-6 col-lg-3 <?php lightspeed_media_col_class() ?> p-0">

						<div class="areoi-media-col-content">
							<?php lightspeed_spacer( 'square' ) ?>

							<?php lightspeed_gallery_media( $media ) ?>
						</div>

					</div>
				<?php endforeach; ?>

			</div>
		</div>
	
	</div>
</div>