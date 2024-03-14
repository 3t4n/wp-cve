<?php
 
	global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
	$terms_count = count($terms);
	$terms_number = count($terms);
	$counter = 0;
	$tcounter = 0;
	$row = '';
	
	echo '<div id="loaction-styles'. esc_attr($instance->args['id']).'" class="row location-style-'. esc_attr($instance->location_style) .' grid-item directorypress-locations-columns clearfix">';
			
		foreach ($terms AS $key=>$term) {
			$tcounter++;
			$term_count = ($instance->count) ? '('. $instance->getCount($term) .')': '';
			$icon_image = '<span class="location-icon"><i class="dicode-material-icons dicode-material-icons-map-marker-outline"></i></span>';
			
			echo '<div class="directorypress-location-item col-md-' . esc_attr($instance->col) . ' col-sm-' . esc_attr($instance->col_tab) . ' col-xs-' . esc_attr($instance->col_mobile) . '">';	
				echo '<div class="directorypress-location-item-holder">';
					echo '<div class="directorypress-parent-location">';
						echo '<a href="' . get_term_link($term) . '" title="' . esc_attr($term->name) . esc_attr($term_count) . '"><span class="location-icon"><i class="dicode-material-icons dicode-material-icons-map-marker-outline"></i></span><span class="loaction-name">' . esc_html($term->name) .'</span><span class="location-item-numbers">'. esc_html($term_count) .'</span></a>';
					echo '</div>';
					if($instance->depth > 1){
						echo wp_kses_post($instance->_display($term->term_id, $instance->depth));
					}
				echo '</div>';		
			echo '</div>';
					
			$counter++;
			if ($counter == $instance->col) {
				$counter = 0;
			}
				
		}
	echo '</div>';