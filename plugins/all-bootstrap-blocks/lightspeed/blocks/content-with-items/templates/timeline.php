<?php  
$styles = '
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-timeline {
	position: relative;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-timeline:before {
	content: "";
	background: ' . lightspeed_get_default_color( 'hex' ) . ';
	width: 3px;
	border-radius: 50px;
	height: 100%;
	position: absolute;
	top: 0;
	left: 50%;
	transform: translate( -50%, 0 );
	opacity: 0.1;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-timeline-row {
	position: relative;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-timeline-row:before {
	content: "";
	background: ' . lightspeed_get_theme_color( lightspeed_get_attribute( 'heading_color', lightspeed_get_default_color( 'hex' ) ) ) . ';
	width: 15px;
	height: 15px;
	border-radius: 50%;
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate( -50%, -50% );
}
';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100 position-relative">
	<div class="row h-100 align-items-center">

		<div class="col">

			<div class="row justify-content-<?php echo lightspeed_get_attribute( 'content_alignment', 'start' ) == 'center' ? 'center' : lightspeed_get_attribute( 'content_alignment' ) ?>">
				<div class="col-lg-6 col-xl-5">
					
					<?php lightspeed_content( 2, 'start', 'col' ) ?>

				</div>
			</div>

			<div class="areoi-timeline">
				<?php foreach ( lightspeed_get_attribute( 'items', array() ) as $item_key => $item ) : ?>
					<div class="areoi-timeline-row row row-cols-1 align-items-center position-relative">
						
						<div class="col col-md-4 <?php echo $item_key % 2 == 0 ? 'order-md-1 offset-md-2' : 'text-md-end offset-md-1' ?>">
							<?php lightspeed_heading( 3, $item ) ?>

							<?php lightspeed_introduction( $item ) ?>

							<?php lightspeed_cta( $item ) ?>				
						</div>

						<div class="col col-md-5 <?php echo $item_key % 2 == 0 ? '' : 'offset-md-2' ?>">
							<div class="<?php lightspeed_media_col_class() ?> rounded overflow-hidden">
								<div class="areoi-media-col-content">
									<?php lightspeed_square_spacer() ?>
									<?php echo lightspeed_get_media( $item ) ?>
								</div>
							</div>				
						</div>
						
					</div>
				<?php endforeach; ?>
			</div>

		</div>
	
	</div>
</div>