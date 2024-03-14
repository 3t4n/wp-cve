<?php 
if ( !isset( $margin ) ) $margin = '0';
?>
<?php if ( in_array( lightspeed_get_attribute( 'block_type' ), [ 'content-with-media', 'hero', 'search' ] ) ) : ?>
	<div class="areoi-parallax-none">
		<div class="areoi-background-pattern areoi-background-pattern-media d-none d-lg-block">
			<div class="h-100">
				<div class="container h-100">
					<div class="row h-100 align-items-center">


						<div class="col-lg-6 position-relative <?php echo $pattern_align == 'start' ? 'order-1' : '' ?>">
							
							<svg class="position-absolute top-50 start-50 translate-middle" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve" style="width: 110%; margin: <?php echo $margin ?>;"><?php echo $pattern_svg ?></svg>
							
						</div>

						<div class="col-6"></div>

					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>