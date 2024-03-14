<?php  
$styles = '

';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100 position-relative">
	<div class="row h-100 align-items-center">

		<div class="col">

			<div class="row justify-content-<?php echo lightspeed_get_attribute( 'content_alignment', 'start' ) == 'center' ? 'center' : lightspeed_get_attribute( 'content_alignment' ) ?>">
				<div class="col">
					
					<?php lightspeed_content( 2, 'start', 'col-lg-6 col-xl-5' ) ?>

				</div>
			</div>

			<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 justify-content-<?php echo lightspeed_get_attribute( 'content_alignment', 'start' ) == 'center' ? 'center' : lightspeed_get_attribute( 'content_alignment' ) ?> <?php echo 'text-' . lightspeed_get_attribute( 'content_alignment', 'start' ) ?>">
				
				<?php foreach ( lightspeed_get_attribute( 'items', array() ) as $item_key => $item ) : ?>
					<div class="col mb-4 position-relative">
						<?php lightspeed_item( $item, false, false, true ) ?>						
					</div>
				<?php endforeach; ?>
			</div>

		</div>
	
	</div>
</div>