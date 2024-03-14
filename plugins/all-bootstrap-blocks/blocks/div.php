<?php
function areoi_render_block_div( $attributes, $content ) 
{
	$class 			= 	trim( 
		areoi_get_class_name_str( array( 
			'areoi-element',
			( !empty( $attributes['className'] ) ? $attributes['className'] : '' ),
			areoi_get_utilities_classes( $attributes )
		) ) 
		. ' ' . 
		areoi_get_display_class_str( $attributes, 'block' ) 
	);

	$url = null;
	if ( !empty( $attributes['url'] ) ) {
		$url = '
			<a class="areoi-full-link"
		';
		if ( !empty( $attributes['url'] ) ) {
			$url .= ' href="' . $attributes['url'] . '"';
		}
		if ( !empty( $attributes['rel'] ) ) {
			$url .= ' rel="' . $attributes['rel'] . '"';
		}
		if ( !empty( $attributes['linkTarget'] ) ) {
			$url .= ' target="' . $attributes['linkTarget'] . '"';
		}
		$url .= '></a>';
	}

	$background 	= include( AREOI__PLUGIN_DIR . '/blocks/_partials/background.php' );

	$output = '
		<div ' . areoi_return_id( $attributes ) . ' class="' . areoi_format_block_id( $attributes['block_id'] ) . ' ' . $class . '">
			' . $background . '
			' . $content . '
			' . $url . ' 
		</div>
	';

	return $output;
}