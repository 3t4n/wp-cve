<?php 
$background_row_class 	= areoi_get_class_name_str( array(
	( !empty( $attributes['background_horizontal_align'] ) ? $attributes['background_horizontal_align'] : '' )
));

$background_col_class 	= 	areoi_get_class_name_str( array(
	( !empty( $attributes['background_col_xs'] ) ? $attributes['background_col_xs'] : '' ),
	( !empty( $attributes['background_col_sm'] ) ? $attributes['background_col_sm'] : '' ),
	( !empty( $attributes['background_col_md'] ) ? $attributes['background_col_md'] : '' ),
	( !empty( $attributes['background_col_lg'] ) ? $attributes['background_col_lg'] : '' ),
	( !empty( $attributes['background_col_xl'] ) ? $attributes['background_col_xl'] : '' ),
	( !empty( $attributes['background_col_xxl'] ) ? $attributes['background_col_xxl'] : '' )
));

$background_utility = !empty( $attributes['background_utility'] ) ? $attributes['background_utility'] : '';

$background = '';

$background_pattern = '';
if ( areoi_is_lightspeed() ) {
	$background_pattern = include( AREOI__PLUGIN_DIR . 'blocks/_partials/patterns.php' );
}

if ( !empty( $attributes['background_display'] ) ) {
	$background = '
		<div class="areoi-background ' . $background_utility . ' ' . areoi_get_background_display_class_str( $attributes, 'block' )  . '">
			<div class="container-fluid" style="padding: 0;">
				<div class="row ' . $background_row_class . '">
					<div class="col ' . $background_col_class . '">
			            ' . ( !empty( $attributes['background_color'] ) && !$background_utility ? 
	                        '<div class="' . areoi_get_class_name_str( array( 
		                            'areoi-background__color'
		                        ) ) . '" 
	                        	style="background: ' . areoi_get_rgba_str( $attributes['background_color']['rgb'] ) . '">
	                        </div>'  : ''
	                    ) . '

	                    ' . ( !empty( $attributes['background_image'] ) ? 
	                        '
	                        	<div class="areoi-background__image" style="background-image:url(' . $attributes['background_image']['url'] . ')"></div>
	                        '  : ''
	                    ) . '

	                    ' . ( !empty( $attributes['background_video'] ) ? 
	                        '<video autoplay loop playsinline muted>
	                            <source src="' . $attributes['background_video']['url'] . '" />
	                        </video>'  : ''
	                    ) . '

	                    ' . ( !empty( $attributes['background_display_overlay'] ) && !empty( $attributes['background_overlay'] ) ? 
	                        '<div class="' . areoi_get_class_name_str( array( 
		                            'areoi-background__overlay'
		                        ) ) . '" 
	                        	style="background: ' . areoi_get_rgba_str( $attributes['background_overlay']['rgb'] ) . '">
	                        </div>'  : ''
	                    ) . '
	    			</div>
	    		</div>
	    	</div>
	    </div>
	';
}

return $background . $background_pattern;