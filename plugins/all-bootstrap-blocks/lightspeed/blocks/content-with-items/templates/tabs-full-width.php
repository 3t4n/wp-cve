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
	<div class="row h-100 justify-content-<?php echo lightspeed_get_attribute( 'content_alignment', 'start' ) == 'center' ? 'center' : lightspeed_get_attribute( 'content_alignment' ) ?>">
		
		<div class="col-lg-6 col-xl-5 <?php echo lightspeed_get_attribute( 'alignment', 'center' ) == 'center' ? 'text-center' : '' ?>">
			
			<?php lightspeed_content( 2, 'start', 'col' ) ?>

		</div>

		<?php if ( lightspeed_get_attribute( 'items', array() ) ) : ?>
			<div class="col-12">

				<?php lightspeed_tabs( lightspeed_get_attribute( 'items', array() ), 'square', 'full' ) ?>

			</div>
		<?php endif; ?>
	
	</div>
</div>