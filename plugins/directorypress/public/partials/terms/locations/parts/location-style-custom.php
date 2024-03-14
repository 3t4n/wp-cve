<?php
 
	global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
	$terms_count = count($terms);
	$terms_number = count($terms);
	$counter = 0;
	$tcounter = 0;
	
	echo '<div id="loaction-styles'. esc_attr($instance->args['id']) .'" class="location-style-'. esc_attr($instance->location_style) .' grid-item directorypress-locations-columns clearfix" style="padding:'. esc_attr($instance->location_padding) .'px;">';
			
		foreach ($terms AS $key=>$term) {
			$tcounter++;
						
			
			$term_count = ' ('. $instance->getCount($term) .')';
			
			$instance->has_child = get_terms( DIRECTORYPRESS_LOCATIONS_TAX, array(
				'parent'    => $term->term_id,
				'hide_empty' => false
			) );
			
			echo '<div class="directorypress-location-item">';
						
				echo '<div class="directorypress-location-item-holder">';
					if($instance->depth > 1 && $instance->has_child){
						echo '<i class="location-plus-icon fas fa-plus-circle" data-popup-open="' . esc_attr($term->term_id) . '"></i>';
					}
					echo '<div class="directorypress-parent-location">';
						echo '<a href="' . get_term_link($term) . '" title="' . esc_attr($term->name) . esc_attr($term_count) . '"><span class="loaction-name">' . esc_html($term->name) .'</span><span class="location-item-numbers">'. esc_html($term_count) .'</span></a>';
					echo '</div>';
				echo '</div>';
				if($instance->depth > 1 && $instance->has_child){
					echo '<div class="directorypress-custom-popup" data-popup="' . esc_attr($term->term_id) . '">';
									echo '<div class="directorypress-custom-popup-inner">';
										echo '<div class="sub-category">';
											echo '<div class="categories-title">'. esc_html__('Select your Location', 'DIRECTORYPRESS') .'<a class="directorypress-custom-popup-close" data-popup-close="' . esc_attr($term->term_id) . '" href="#"><i class="far fa-times-circle"></i></a></div>';
											echo '<ul class="loc-sub-main-ul clearfix">';
														
												wp_list_categories( array(
																'orderby' => 'name',
																'show_count' => true,
																'use_desc_for_title' => false,
																'child_of' => $term->term_id,
																'hide_empty' => false,
																'taxonomy' => DIRECTORYPRESS_LOCATIONS_TAX,
																'title_li' => ''
												) ); 
															 
											echo '</ul>';
										echo '</div>';	
									echo '</div>';		
					echo '</div>';
				}	
						
			echo '</div>';
					
			$counter++;
			if ($counter == $instance->col) {
				$counter = 0;
			}
				
		}
	echo '</div>';