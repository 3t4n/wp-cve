<?php
 
	global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
	$terms_count = count($terms);
	$terms_number = count($terms);
	$counter = 0;
	$tcounter = 0;
	echo '<div class="category-style-'. esc_attr($instance->cat_style) .' clearfix">';
			
		foreach ($terms AS $key=>$term) {
			$tcounter++;
						
			
			$term_count = $instance->getCount($term);
			echo '<div class="terms-list-item">';
					echo '<a href="' . get_term_link($term) . '" title="' . esc_attr($term->name) . esc_attr($term_count) . '">'. esc_html($term->name) .'</a>';
			echo '</div>';
			
			$counter++;
			if ($counter == $instance->col) {
				$counter = 0;
			}
				
		}
	echo '</div>';