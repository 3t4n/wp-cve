<?php  
$styles = '
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media {
	position: relative;
}
@media only screen and (min-width: ' . areoi2_get_option( 'areoi-layout-grid-grid-breakpoint-lg', '992px' ) . ') {
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-col-content {
		position: sticky;
		top: 100px;
	}
}
';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100 position-relative">
	<div class="row h-100 justify-content-between align-items-start">
		
		<div class="col-lg-6 col-xl-5 <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'order-lg-1' : '' ?> text-center text-lg-start areoi-col-content">
			
			<?php lightspeed_content( 2, 'start', 'col' ) ?>

			<?php if ( lightspeed_get_attribute( 'include_cta', false ) ) : ?>
				<div class="h1"></div>
			<?php endif; ?>
		</div>

		<?php if ( !empty( lightspeed_get_attribute( 'gallery', array() ) ) ) : ?>
			<div class="col-lg-6 areoi-hero-media">
				<div class="h1 d-lg-none"></div>
				
				<div class="rounded overflow-hidden">
					<?php foreach ( lightspeed_get_attribute( 'gallery', array() ) as $gallery_key => $media ) : ?>
						<div class="">
							<div>
								<div class="areoi-media-col-content">
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
			</div>
		<?php endif; ?>
	
	</div>
</div>

<?php //lightspeed_stretched_link() ?>