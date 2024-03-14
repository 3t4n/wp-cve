<?php 
$styles = '
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block ul {
	display: inline-block;
}
';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<?php 

$primary = lightspeed_get_theme_color( 'primary' );
$rgb = lightspeed_hex_to_rgb( $primary );
$contrast = lightspeed_get_default_color( 'bg' );

$attributes['background_display'] = true;
$attributes['background_utility'] = !empty( $attributes['background_utility'] ) ? $attributes['background_utility'] : $contrast;
$attributes['background_image'] = lightspeed_get_attribute( 'image', null );
$attributes['background_video'] = lightspeed_get_attribute( 'video', null );

$attributes['background_overlay'] = ( !empty( $attributes['background_overlay'] ) && $attributes['background_display_overlay'] ) ? $attributes['background_overlay'] : array('rgb' => array( 'r'=> $rgb['r'], 'g'=> $rgb['g'], 'b'=> $rgb['b'], 'a'=> '0.8' ) );
$attributes['background_display_overlay'] = true;

lightspeed_set_attribute( 'background_display', $attributes['background_display'] );
lightspeed_set_attribute( 'background_utility', $attributes['background_utility'] );
lightspeed_set_attribute( 'background_image', $attributes['background_image'] );
lightspeed_set_attribute( 'background_video', $attributes['background_video'] );
lightspeed_set_attribute( 'background_display_overlay', $attributes['background_display_overlay'] );
lightspeed_set_attribute( 'background_overlay', $attributes['background_overlay'] );

echo include( AREOI__PLUGIN_DIR . '/blocks/_partials/background.php' ); 
?>

<div class="container h-100 position-relative">
	<div class="row h-100 align-items-center">
		<div class="col">

			<div class="row justify-content-center">
				<div class="col-md-12 col-lg-8 col-xl-6">
					<?php lightspeed_content( 1, 'center', 'col' ) ?>
				</div>
			</div>
			
		</div>
	
	</div>
</div>