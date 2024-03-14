<?php  
$styles = '

';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100 position-relative">
	<div class="row h-100 align-items-center justify-content-between">
		
		<div class="col-lg-6 col-xxl-5 <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'order-lg-1' : '' ?>">
			
			<?php lightspeed_content( 2, 'start', 'col' ) ?>

			<?php lightspeed_search() ?>

		</div>

		<div class="col-lg-6 areoi-hero-media">

			<div class="h1 d-lg-none"></div>

			<div id="<?php lightspeed_block_id() ?>-media-carousel" class="carousel slide" data-bs-ride="carousel">
				
				<?php if ( count( lightspeed_get_attribute( 'gallery', array() ) ) > 1 ) : ?>
					<div class="carousel-indicators">
						<?php foreach ( lightspeed_get_attribute( 'gallery', array() ) as $gallery_key => $media ) : ?>
							<button 
							type="button" 
							data-bs-target="#<?php lightspeed_block_id() ?>-media-carousel" 
							data-bs-slide-to="<?php echo $gallery_key ?>" 
							class="<?php echo $gallery_key == 0 ? 'active' : '' ?>" 
							aria-current="true" 
							aria-label="Slide <?php echo $gallery_key + 1 ?>"
							></button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="carousel-inner areoi-has-mask">
					<?php foreach ( lightspeed_get_attribute( 'gallery', array() ) as $gallery_key => $media ) : ?>
						<div class="carousel-item <?php echo $gallery_key == 0 ? 'active' : '' ?>">
							<div class="<?php lightspeed_media_col_class() ?>">
								<div class="areoi-media-col-content">
									<?php lightspeed_spacer( lightspeed_get_attribute( 'media_shape', 'square' ) ) ?>
									<?php if ( $media['type'] == 'image' ) : ?>
										<img src="<?php echo $media['url'] ?>" class="d-block img-fluid" alt="<?php echo $media['alt'] ?>" width="<?php echo $media['width'] ?>" height="<?php echo $media['height'] ?>">
									<?php else : ?>
										<video src="<?php echo $media['url'] ?>" muted playsinline autoplay loop class="img-fluid"></video>
									<?php endif; ?>
								</div>
							</div>
							
						</div>
					<?php endforeach; ?>
				</div>

				<?php if ( count( lightspeed_get_attribute( 'gallery', array() ) ) > 1 ) : ?>
					<button class="carousel-control-prev" type="button" data-bs-target="#<?php lightspeed_block_id() ?>-media-carousel" data-bs-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="visually-hidden">Previous</span>
					</button>
					<button class="carousel-control-next" type="button" data-bs-target="#<?php lightspeed_block_id() ?>-media-carousel" data-bs-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="visually-hidden">Next</span>
					</button>
				<?php endif; ?>
			</div>

		</div>
	
	</div>
</div>