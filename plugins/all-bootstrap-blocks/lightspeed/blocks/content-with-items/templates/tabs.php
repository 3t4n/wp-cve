<?php

$styles = '
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .nav-link.active {
	border-bottom-color: ' . lightspeed_get_theme_color( lightspeed_get_attribute( 'background_utility', null ) ) . ' !important;
}
';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100 position-relative">
	<div class="row h-100 align-items-center justify-content-between">
		
		<div class="col-lg-6 col-xl-5 <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'order-lg-1' : '' ?>">
			
			<?php lightspeed_content( 2, 'start', 'col' ) ?>
		</div>

		<?php if ( lightspeed_get_attribute( 'items', array() ) ) : ?>
			<div class="col-lg-6">

				<?php lightspeed_tabs( lightspeed_get_attribute( 'items', array() ) ) ?>

			</div>
		<?php endif; ?>
	
	</div>
</div>