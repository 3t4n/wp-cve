<?php  
$styles = '
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media {
	position: relative;
}
@media only screen and (min-width: ' . areoi2_get_option( 'areoi-layout-grid-grid-breakpoint-lg', '992px' ) . ') {
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media {
		position: absolute;
		' . (lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'left' : 'right') . ': 0
	}
}
';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100">
	<div class="row h-100 align-items-center">

		<div class="col-lg-6 col-xl-5 position-relative <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'offset-lg-6 offset-xxl-7' : '' ?>">
			
			<?php lightspeed_content( 2, 'start', 'col' ) ?>
		</div>

		<div class="col-12 col-lg-6  p-0 areoi-hero-media <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? '' : 'offset-xxl-1' ?>">
			<div class="areoi-drag-container">
				<ul>
					<?php foreach ( lightspeed_get_attribute( 'items', array() ) as $item_key => $item ) : ?>
						<li class=" position-relative">
							<?php if ( !empty( lightspeed_get_attribute( 'media_position', null ) ) && lightspeed_get_attribute( 'media_position', null ) == 'background' ) : ?>
								<?php lightspeed_item_with_background( $item ) ?>
							<?php else : ?>
								<?php lightspeed_item( $item ) ?>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	
	</div>
</div>