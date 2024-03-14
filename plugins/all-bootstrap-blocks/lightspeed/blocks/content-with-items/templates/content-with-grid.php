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
			<div class="row">
				<?php if ( lightspeed_has_content() ) : ?>
					<div class="align-self-start col-lg-6 col-xl-5 areoi-col-content <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'offset-xxl-1 order-lg-1' : '' ?> position-sticky" style="top: 100px;">
						
						<?php lightspeed_content( 2, 'start', 'col' ) ?>

					</div>
				<?php endif; ?>
				
				<div class="<?php echo lightspeed_has_content() ? 'col-lg-6' : 'col' ?> <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? '' : 'offset-xl-1' ?>">
					<div class="row row-cols-1 row-cols-md-2 <?php echo lightspeed_has_content() ? '' : 'row-cols-lg-4' ?> areoi-parallax-component">
						<?php foreach ( lightspeed_get_attribute( 'items', array() ) as $item_key => $item ) : ?>
							<div class="col mb-4 position-relative">
								<?php lightspeed_item( $item ) ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		
	</div>

</div>