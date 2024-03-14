<?php  
$styles = '
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media {
	position: relative;
}
@media only screen and (min-width: ' . areoi2_get_option( 'areoi-layout-grid-grid-breakpoint-lg', '992px' ) . ') {
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media {
		position: absolute;
		' . (lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'left' : 'right') . ': 0;
	}
}
';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100">
	<div class="row h-100 align-items-center justify-content-between <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'justify-content-lg-end' : '' ?>">
		
		<div class="col-lg-6 col-xl-5 text-center text-lg-start position-relative">
			
			<?php lightspeed_content( 2, 'start', 'col' ) ?>

			<?php if ( lightspeed_get_attribute( 'include_cta', false ) ) : ?>
				<div class="h1"></div>
			<?php endif; ?>
		</div>

		<?php if ( !empty( lightspeed_get_attribute( 'gallery', array() ) ) ) : ?>
			<div class="col-lg-6 areoi-hero-media p-0">
				<div class="h1 d-lg-none"></div>
				
				<div class="areoi-drag-container">
					<ul>
						<?php foreach ( lightspeed_get_attribute( 'gallery', array() ) as $media_key => $media ) : ?>
							<li>
								<div class="<?php lightspeed_media_col_class() ?> rounded overflow-hidden">
									<div class="areoi-media-col-content">
										<?php lightspeed_square_spacer() ?>
										<?php if ( $media['type'] == 'image' ) : ?>
											<img src="<?php echo $media['url'] ?>" class="d-block img-fluid" alt="<?php echo $media['alt'] ?>" width="<?php echo $media['width'] ?>" height="<?php echo $media['height'] ?>">
										<?php else : ?>
											<video src="<?php echo $media['url'] ?>" muted playsinline autoplay loop class="img-fluid"></video>
										<?php endif; ?>
									</div>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		<?php endif; ?>
	
	</div>
</div>

<?php //lightspeed_stretched_link() ?>