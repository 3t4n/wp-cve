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

			<div class="row justify-content-<?php echo lightspeed_get_attribute( 'alignment', 'center' ) ?>">
				<div class="col-md-10 col-lg-8 col-xl-6">
					
					<?php lightspeed_content( 2, 'center', 'col' ) ?>

				</div>
			</div>
			
		</div>
	
	</div>
</div>