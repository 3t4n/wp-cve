<?php  
$styles = '
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media {
	position: relative;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media img,
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media video {
	width: 100%;
	height: auto;
	display: block;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-container-content > .row {
	height: auto;
	min-height: unset !important;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media {
	margin: 0 0 -100px 0;
}
@media only screen and (min-width: ' . areoi2_get_option( 'areoi-layout-grid-grid-breakpoint-lg', '992px' ) . ') {
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media {
		margin: -' . $padding . 'px 0;
	}
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-col-content {
		position: sticky;
		top: 100px;
	}
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-container-content {
		position: absolute;
		left: 50%;
		transform: translate( -50%, 0 );
		z-index: 1;
	}
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-container-content > .row {
		height: calc( 100% - ' . $padding * 2 . 'px );
	}
}
';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100 areoi-container-content areoi-resize-container">
	<div class="row <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'justify-content-end' : 'justify-content-start' ?> align-items-start  ">
		
		<div class="col-lg-6 col-xl-5 text-center text-lg-start areoi-col-content areoi-resize-content">
			
			<?php lightspeed_content( 2, 'start', 'col' ) ?>

		</div>
	</div>
</div>

<div class="container-fluid h-100 position-relative">
	<div class="row h-100 <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'justify-content-start' : 'justify-content-end' ?> align-items-start">

		<?php if ( !empty( lightspeed_get_attribute( 'gallery', array() ) ) ) : ?>
			<div class="col-lg-6 areoi-hero-media p-0 areoi-resize-media">
				
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