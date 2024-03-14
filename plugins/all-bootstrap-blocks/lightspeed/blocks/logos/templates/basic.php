<?php  
$styles = '
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-lightspeed-block-logos img,
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block  .areoi-lightspeed-block-logos video {
	width: auto; 
	height:  auto; 
	max-width: ' . lightspeed_get_attribute( 'max_width', '80' ) . '%; 
	max-height: ' . lightspeed_get_attribute( 'max_height', '90' ) . 'px;
}
';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100 position-relative">
	<div class="row align-items-center">

		<div class="col">

			<div class="row justify-content-<?php echo lightspeed_get_attribute( 'content_alignment', 'center' ) == 'center' ? 'center' : lightspeed_get_attribute( 'alignment' ) ?>">
				<div class="col-lg-6">
					
					<?php lightspeed_content( 2, 'center', 'col' ) ?>

					<?php if ( lightspeed_get_attribute( 'include_cta', false ) ) : ?>
						<div class="h1"></div>
					<?php endif; ?>

				</div>
			</div>
			
			<div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 justify-content-center areoi-lightspeed-block-logos">
				<?php foreach ( lightspeed_get_attribute( 'gallery', array() ) as $media_key => $media ) : ?>
					<div class="col mb-4 mt-4 d-flex align-items-center justify-content-center">
						<?php if ( $media['type'] == 'image' ) : ?>
							<img 
								src="<?php echo $media['url'] ?>" 
								alt="<?php echo !empty( $media['alt'] ) ? $media['alt'] : '' ?>" 
								width="<?php echo !empty( $media['width'] ) ? $media['width'] : '' ?>" 
								height="<?php echo !empty( $media['height'] ) ? $media['height'] : '' ?>"
							>
						<?php else : ?>
							<video 
								src="<?php echo $media['url'] ?>" 
								muted 
								playsinline 
								autoplay 
								loop 
							></video>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>

		</div>
	
	</div>
</div>