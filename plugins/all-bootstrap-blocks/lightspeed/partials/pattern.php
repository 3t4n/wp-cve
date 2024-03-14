<?php 
if ( !isset( $width ) ) $width = 300;
if ( !isset( $margin ) ) $margin = '0';
if ( !isset( $margin_2 ) ) $margin_2 = '0';
if ( !isset( $pattern_align ) ) $pattern_align = 'start'; 

$bottom = '0px';
if ( areoi2_get_option( 'areoi-lightspeed-styles-strip-background', null ) ) $bottom = '-125px';
if ( ( areoi2_get_option( 'areoi-lightspeed-parallax-parallax', null ) && areoi2_get_option( 'areoi-lightspeed-parallax-patterns', null ) ) || areoi2_get_option( 'areoi-lightspeed-transition-background', null ) ) $bottom = '125px';
if ( areoi2_get_option( 'areoi-lightspeed-parallax-parallax', null ) && areoi2_get_option( 'areoi-lightspeed-parallax-patterns', null ) && !areoi2_get_option( 'areoi-lightspeed-styles-strip-background', null ) ) $bottom = '370px';
?>
<div class="areoi-background-pattern">
	<div class="h-100">
		<div class="container-fluid h-100">
			<div class="row h-100 align-items-center">

				<div class="col-lg-6 h-100 position-relative <?php echo $pattern_align == 'start' ? '' : 'order-1' ?>">
					<div class="position-absolute p-4" style="bottom: <?php echo $bottom ?>; left: <?php echo $pattern_align == 'start' ? '-25%' : '50%' ?>;">
						<div class="d-flex justify-content-center" style="margin: <?php echo $margin ?>">
							<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve" style="width: <?php echo $width ?>px;"><?php echo $pattern_svg ?></svg>
						</div>
						<div class=" d-flex justify-content-end">
							<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve" style="width: <?php echo $width ?>px; margin: <?php echo $margin_2 ?>"><?php echo $pattern_svg ?></svg>
							<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve" style="width: <?php echo $width ?>px; margin: <?php echo $margin_2 ?>"><?php echo $pattern_svg ?></svg>
						</div>
					</div>
				</div>

				<div class="col-6"></div>

			</div>
		</div>
	</div>
</div>