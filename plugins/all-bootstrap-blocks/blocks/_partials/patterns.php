<?php 
$has_pattern 			= areoi2_get_option( 'areoi-lightspeed-styles-strip-pattern', false );
if ( lightspeed_get_attribute( 'pattern', null ) ) $has_pattern = lightspeed_get_attribute( 'pattern', null );

$background_pattern 	= '';

if ( $has_pattern && !empty( $allow_pattern ) && empty( $attributes['exclude_pattern'] ) ) {

	$background_color = !empty( $attributes['background_utility'] ) ? $attributes['background_utility'] : 'bg-body';
	if ( empty( $attributes['background_utility'] ) && !empty( $attributes['background_color']['rgb'] ) ) $background_color = $attributes['background_color']['rgb'];
	$contrast = lightspeed_get_contrast_color( $background_color );
	
	$pattern_color = 'rgba( 0, 0, 0, 0.1 )';
	if ( $contrast == '#FFFFFF' ) {
		$pattern_color = 'rgba( 255, 255, 255, 0.2 )';
	}

	if ( !empty( $attributes['change_pattern_color'] ) && !empty( $attributes['pattern_color']['hex'] ) ) $pattern_color = $attributes['pattern_color']['hex'];
	
	$background_pattern_template 	= lightspeed_get_patterns_directory( $has_pattern );
	if ( $background_pattern_template && file_exists( $background_pattern_template ) ) {
		ob_start(); include( $background_pattern_template ); $background_pattern .= ob_get_clean();
	}
}

return $background_pattern;