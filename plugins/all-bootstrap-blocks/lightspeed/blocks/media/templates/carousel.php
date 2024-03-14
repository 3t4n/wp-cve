<?php  
$styles = '
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media .carousel-item img,
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media .carousel-item video,
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media .carousel-item .areoi-media-col,
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media .carousel-item .areoi-media-col-content {
	width: 100%;
	height: 100%;
	object-fit: cover;
}
@media only screen and (min-width: ' . areoi2_get_option( 'areoi-layout-grid-grid-breakpoint-lg', '992px' ) . ') {
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-block > div.container > .row {
		height: calc( ' . lightspeed_get_attribute( 'size', '100vh' ) . ' - ' . areoi2_get_option( 'areoi-lightspeed-styles-strip-padding', '0' ) * 2 . 'px );
	}
}
';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container areoi-parallax-none">
	<div class="row align-items-center">

		<div class="col areoi-hero-media position-relative areoi-transition-none">
			
			<?php lightspeed_media_carousel( lightspeed_get_attribute( 'gallery', array() ), lightspeed_get_attribute( 'media_shape', 'rectangle' ), false ) ?>
		</div>
	
	</div>
</div>