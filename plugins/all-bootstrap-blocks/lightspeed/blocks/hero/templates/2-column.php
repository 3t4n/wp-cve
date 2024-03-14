<?php  
$styles = '
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media {
	margin-bottom: -' . $mobile_padding . 'px;
}
@media only screen and (min-width: ' . areoi2_get_option( 'areoi-layout-grid-grid-breakpoint-lg', '992px' ) . ') {
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media {
		margin-bottom: 0;
	}
}
';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100 position-relative">
	<div class="row h-100 align-items-center justify-content-between">
		
		<div class="col-lg-6 col-xl-5 text-center text-lg-start <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'order-lg-1' : '' ?>">
			
			<?php lightspeed_content( 1, 'start', 'col' ) ?>
		</div>

		<?php if ( lightspeed_get_attribute( 'video', null ) || lightspeed_get_attribute( 'image', null ) || ( lightspeed_get_attribute( 'is_post_image', null ) && get_post_thumbnail_id() ) ) : ?>
			<div class="col-lg-6 areoi-hero-media">
				<div class="h1 d-lg-none"></div>
				<div class="<?php lightspeed_media_col_class() ?> areoi-has-mask rounded overflow-hidden">
					<div class="areoi-media-col-content">
						<?php lightspeed_spacer( 'square' ) ?>
						<?php lightspeed_media() ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	
	</div>
</div>

<?php //lightspeed_stretched_link() ?>