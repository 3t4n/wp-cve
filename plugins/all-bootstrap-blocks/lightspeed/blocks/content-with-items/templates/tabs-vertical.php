<?php

$styles = '
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .nav-link.active {
	text-decoration: underline;
}
';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100 position-relative">
	<div class="row justify-content-<?php echo lightspeed_get_attribute( 'content_alignment', 'start' ) == 'center' ? 'center' : lightspeed_get_attribute( 'content_alignment' ) ?>">
		
		<div class="<?php echo lightspeed_get_attribute( 'alignment', 'center' ) == 'center' ? 'text-center' : '' ?>">
			
			<?php lightspeed_content( 2, 'start', 'col-lg-6 col-xl-5' ) ?>

		</div>

		<?php if ( lightspeed_get_attribute( 'items', array() ) ) : ?>
			<div class="col-12">

				<?php lightspeed_tabs_vertical( lightspeed_get_attribute( 'items', array() ) ) ?>

			</div>
		<?php endif; ?>
	
	</div>
</div>