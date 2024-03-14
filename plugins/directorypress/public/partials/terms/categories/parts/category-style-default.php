<?php
 
	global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
	
	echo '<div id="directorypress-category-'. esc_attr($instance->args['id']) .'" class="cat-style-'. esc_attr($instance->cat_style) .'">';
			
		$terms_count = count($terms);
		$terms_number = count($terms);
		$counter = 0;
		$tcounter = 0;
		if($instance->scroll == 1){
			$scroll_attr = $instance->slick_attrs;
			$scroll_class = 'dp-slick-carousel';
		}else{
			$scroll_attr = '';
			$scroll_class = '';
		}
		
		echo '<div class="row directorypress-categories-wrapper '. esc_attr($scroll_class) . ' clearfix" '. wp_kses_post($scroll_attr) .'>';
	
			foreach ($terms AS $key=>$term) {
				$tcounter++;
					if ($instance->scroll == 0 && count( get_term_children( $term->term_id, DIRECTORYPRESS_CATEGORIES_TAX ) ) > 0 ) {
						$more_cat_icon = '<i class="fas fa-plus-circle" data-popup-open="' . esc_attr($term->term_id) .'"></i>';
					}else{
						$more_cat_icon = '';
					} 
							
					// term wrapper
					echo '<div class="directorypress-category-item col-md-' . esc_attr($instance->col) . ' col-sm-' . esc_attr($instance->col_tab) . ' col-xs-' . esc_attr($instance->col_mobile) . '">';
						echo '<div id="cat-wrapper-'. esc_attr($term->term_id) .'" class="directorypress-category-holder clearfix">';		
							echo '<div class="directorypress-parent-category"><a href="' . get_term_link($term) . '" title="' . esc_attr($term->name) . '">' . wp_kses_post($instance->termIcon($term->term_id)) .'<span class="categories-name">'. esc_html($term->name) .'</span><span class="categories-count">'. esc_attr($instance->renderTermCount($term)) . '</span></a></div>';
							if($instance->depth > 1){
								echo wp_kses_post($instance->_display($term->term_id, $instance->depth));
							}
						echo '</div>';
						if($instance->depth > 1){
							// modal
							echo '<div class="directorypress-custom-popup" data-popup="' . esc_attr($term->term_id) . '">';
								echo '<div class="directorypress-custom-popup-inner">';
									echo '<div class="sub-category"><div class="categories-title">'. esc_html__('Select your Category', 'DIRECTORYPRESS') .'<a class="directorypress-custom-popup-close" data-popup-close="' . esc_attr($term->term_id) . '" href="#"><i class="far fa-times-circle"></i></a></div><ul class="cat-sub-main-ul clearfix">';			
										wp_list_categories( array(
											'orderby' => 'name',
											'show_count' => true,
											'use_desc_for_title' => false,
											'child_of' => $term->term_id,
											'hide_empty' => false,
											'taxonomy' => DIRECTORYPRESS_CATEGORIES_TAX,
											'title_li' => ''
										) ); 		 
									echo '</ul></div>';
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
	echo '</div>';