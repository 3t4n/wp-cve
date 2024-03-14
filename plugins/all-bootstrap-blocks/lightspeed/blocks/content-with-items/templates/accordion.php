<?php 
$styles = '
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .accordion-item {
	background: none;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .accordion-button {
	background: none;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .accordion-button:not(.collapsed,:focus) {
	box-shadow: none;
	border-bottom-style: solid;
	border-bottom-width: 1px;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .accordion-body :last-of-type {
	margin-bottom: 0;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .accordion-button:after {
	display: none;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .accordion-button .bi-chevron-down {
	flex-shrink: 0;
    font-size: 22px;
    margin-left: auto !important;
    transition: transform 0.2s ease-in-out;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .accordion-button:not(.collapsed) .bi-chevron-down {
	transform: rotate( 180deg );
}
';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100 position-relative">
	<div class="row h-100 align-items-center <?php echo lightspeed_has_content() ? 'justify-content-between' : 'justify-content-center' ?>">
		
		<?php if ( lightspeed_has_content() ) : ?>
			<div class="col-lg-6 col-xl-5 <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'order-lg-1' : '' ?>">
					<?php echo $attributes['content_alignment'] ?>	
				<?php lightspeed_content( 2, 'start', 'col' ) ?>

			</div>
		<?php endif; ?>

		<?php if ( lightspeed_get_attribute( 'items', array() ) ) : ?>
			<div class="col-lg-6">

				<?php lightspeed_accordion( lightspeed_get_attribute( 'items', array() ) ) ?>

			</div>
		<?php endif; ?>
	
	</div>
</div>