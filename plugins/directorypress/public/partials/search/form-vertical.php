<?php 
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
	$search_form->get_search_form_dynamic_css(); 
	
		
	$field_width = '100';
	$keyword_field_width = '100';
	$location_field_width = '100';
	$radius_field_width = '100';
	$button_field_width = '100';
	

	echo '<form action="'. esc_url($search_url) .'" class="search-form-style1 directorypress-search-layout-'. esc_attr($search_form->form_layout) .' directorypress-content-wrap directorypress-search-form" data-id="'. esc_attr($search_form->form_id) .'" id="directorypress-search-form-'. esc_attr($search_form->form_id) .'">';
			echo wp_kses_post($search_form->display_hidden_fields());
		echo '<div class="directorypress-search-holder clearfix">';
			echo '<div class="search-container clearfix" style="margin-left:-'. esc_attr($search_form->args['gap_in_fields']) .'px; margin-right:-'. esc_attr($search_form->args['gap_in_fields']) .'px;">';
				echo '<div class="default-search-fields-wrapper clearfix">';
					echo '<div class="default-search-fields-section-label clearfix">';
						echo '<label>'. esc_html__('filters', 'DIRECTORYPRESS') .'</label>';
					echo '</div>';
					echo '<div class="default-search-fields-content-box clearfix">';
						if ($search_form->is_categories_or_Keywords_field()){
							do_action('pre_search_what_form_html', $search_form->form_id);
							if ($search_form->is_categories() && $search_form->args['show_keywords_category_combo']) { 
								echo '<div class="keyword-search search-element-col pull-left directorypress-search-input-field-wrap" style="width:'. esc_attr($keyword_field_width) .'%; padding:0 '. esc_attr($search_form->args['gap_in_fields']) .'px;">';
									if($search_form->is_default_fields_label()){
										echo '<label>'.esc_html__('Search By:', 'DIRECTORYPRESS').'</label>';
									}
									directorypress_tax_dropdowns_menu_init($search_form->get_categories_dropmenu_params(__('Select category', 'DIRECTORYPRESS'), __('Enter Keyword', 'DIRECTORYPRESS')));
									if ($search_form->is_keyword_field_examples()){
										echo '<p class="directorypress-search-suggestions">';
											echo sprintf(__("Try to search: %s", "DIRECTORYPRESS"), wp_kses_post($search_form->get_keywords_examples()));
										echo '</p>';
									}
								echo '</div>';
							}elseif($search_form->is_categories() && !$search_form->args['show_keywords_category_combo']){
								if ($search_form->is_keywords_field_with_ajax()){
									$keywords_autocomplete_class = 'directorypress-keywords-autocomplete';
								}else{
									$keywords_autocomplete_class = '';
								}
								echo '<div class="keyword-search search-element-col pull-left directorypress-search-input-field-wrap" style="width:'. esc_attr($keyword_field_width).'%; padding:0 '. esc_attr($search_form->args['gap_in_fields']).'px;">';
									if($search_form->is_default_fields_label()){
										echo '<label>'.esc_html__('Search In:', 'DIRECTORYPRESS').'</label>';
									}
									echo '<div class="has-feedback">';
										echo '<input name="what_search" value="'.esc_attr($search_form->get_keyword_value()).'" placeholder="'. esc_attr__('Enter keywords', 'DIRECTORYPRESS') .'" class="'. esc_attr($keywords_autocomplete_class) .' form-control directorypress-default-field" autocomplete="off" />';
										if($search_form->args['default_fields_icon_type'] == 'img'){
											echo '<span class="directorypress-dropmenubox-button directorypress-form-control-feedback"><img src="'. esc_url($search_form->args['keyword_field_icon']) .'" alt="'. esc_attr__('Enter keywords', 'DIRECTORYPRESS') .'" /></span>';
										}else{
											echo '<span class="directorypress-dropmenubox-button directorypress-form-control-feedback '. esc_attr($search_form->args['keyword_field_icon']) .'"></span>';
										}
									echo '</div>';
									
									if ($search_form->is_keyword_field_examples()){
										echo '<p class="directorypress-search-suggestions">';
											echo printf(__("Try to search: %s", "DIRECTORYPRESS"), wp_kses_post($search_form->get_keywords_examples()));
										echo '</p>';
									}
								echo '</div>';
								echo '<div class="category-search search-element-col pull-left directorypress-search-input-field-wrap" style="width:'. esc_attr($keyword_field_width) .'%; padding:0 '. esc_attr($search_form->args['gap_in_fields']) .'px;">';
									if($search_form->is_default_fields_label()){
										echo '<label>'.esc_html__('Search In:', 'DIRECTORYPRESS').'</label>';
									}
									directorypress_tax_dropdowns_menu_init($search_form->get_categories_dropmenu_params(__('Select category', 'DIRECTORYPRESS'), __('Enter Keyword', 'DIRECTORYPRESS')));
								echo '</div>';
							}else{
								if ($search_form->is_keywords_field_with_ajax()){
									$keywords_autocomplete_class = 'directorypress-keywords-autocomplete';
								}else{
									$keywords_autocomplete_class = '';
								}
								echo '<div class="keyword-search search-element-col pull-left directorypress-search-input-field-wrap" style="width:'. esc_attr($keyword_field_width).'%; padding:0 '. esc_attr($search_form->args['gap_in_fields']).'px;">';
									if($search_form->is_default_fields_label()){
										echo '<label>'.esc_html__('Search In:', 'DIRECTORYPRESS').'</label>';
									}
									echo '<div class="has-feedback">';
										echo '<input name="what_search" value="'.esc_attr($search_form->get_keyword_value()).'" placeholder="'. esc_attr__('Enter keywords', 'DIRECTORYPRESS') .'" class="'. esc_attr($keywords_autocomplete_class) .' form-control directorypress-default-field" autocomplete="off" />';
										if($search_form->args['default_fields_icon_type'] == 'img'){
											echo '<span class="directorypress-dropmenubox-button directorypress-form-control-feedback"><img src="'. esc_url($search_form->args['keyword_field_icon']) .'" alt="'. esc_attr__('Enter keywords', 'DIRECTORYPRESS') .'" /></span>';
										}else{
											echo '<span class="directorypress-dropmenubox-button directorypress-form-control-feedback '. esc_attr($search_form->args['keyword_field_icon']) .'"></span>';
										}
									echo '</div>';
									
									if ($search_form->is_keyword_field_examples()){
										echo '<p class="directorypress-search-suggestions">';
											echo printf(__("Try to search: %s", "DIRECTORYPRESS"), wp_kses_post($search_form->get_keywords_examples()));
										echo '</p>';
									}
								echo '</div>';
							}
							do_action('post_search_what_form_html', $search_form->form_id);
						}
					
						//do_action('pre_search_what_form_html', $search_form->form_id);
						//do_action('post_search_what_form_html', $search_form->form_id);
						if ($search_form->is_locations_or_address_field()){
							do_action('pre_search_where_form_html', $search_form->form_id);
							echo '<div class="search-element-col pull-left directorypress-search-input-field-wrap" style="width:'. esc_attr($location_field_width) .'%; padding:0 '. esc_attr($search_form->args['gap_in_fields']) .'px;">';
								if($search_form->is_default_fields_label()){
									echo '<label>'. esc_html__('Search in Location', 'DIRECTORYPRESS') .'</label>';
								}
								if ($search_form->is_locations()) {
									directorypress_tax_dropdowns_menu_init($search_form->get_locations_dropmenu_params(esc_html__('Select Location', 'DIRECTORYPRESS'), esc_html__('Enter Address', 'DIRECTORYPRESS')));
								}else {
									echo '<div class="has-feedback">';
										echo '<input name="address" value="'. esc_attr($search_form->get_address_value()) .'" placeholder="'. esc_attr__('Enter address', 'DIRECTORYPRESS') .'" class="directorypress-address-autocomplete form-control directorypress-default-field" autocomplete="off" />';
										echo '<span class="directorypress-dropmenubox-button directorypress-form-control-feedback glyphicon glyphicon-map-marker"></span>';
									echo '</div>';
								} 
							echo '</div>';
							do_action('post_search_where_form_html', $search_form->form_id);
						}
				
						if ($search_form->is_radius()){
							if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_miles_kilometers_in_search'] == 'miles'){
								$parameter = __('Mi', 'DIRECTORYPRESS');
								$parameter_full = __('Mile', 'DIRECTORYPRESS');
							}else{
								$parameter = __('Km', 'DIRECTORYPRESS');
								$parameter_full = __('Kilometer', 'DIRECTORYPRESS');
							}
							echo '<div class="cz-areaalider search-element-col pull-left" style="width:'. esc_attr($radius_field_width) .'%; padding:0 '. esc_attr($search_form->args['gap_in_fields']) .'px;">';
								if(!$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_radius_tooltip'] && $search_form->is_default_fields_label()){
										//echo '<label>'.esc_html__('Search In', 'DIRECTORYPRESS').'</label>';
										echo '<div class="directorypress-search-radius-label" style="padding-left:5px; display:inline-block;">';
											echo '<strong id="radius_label_'. esc_attr($search_form->form_id) .'">'. esc_html($search_form->get_radius_value()) .'</strong>';
											echo ' '. esc_html($parameter);
										echo '</div>';
										
									}elseif($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_radius_tooltip'] && $search_form->is_default_fields_label()){
										//echo '<label>'.esc_html__('Search In Radius', 'DIRECTORYPRESS'). $search_form->get_radius_value(). $parameter.'</label>';
									}
								echo '<div class="form-group directorypress-jquery-ui-slider">';
									if(!$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_radius_tooltip'] && !$search_form->is_default_fields_label()){
										echo '<div class="directorypress-search-radius-label">';
											echo '<strong id="radius_label_'. esc_attr($search_form->form_id) .'">'. esc_html($search_form->get_radius_value()).'</strong>';
											echo ' '. esc_html($parameter_full);
										echo '</div>';
									}
									echo '<div class="directorypress-distance-slider">';
										echo '<div class="distance-slider" id="radius_slider_'. esc_attr($search_form->form_id) .'" data-id="'. esc_attr($search_form->form_id) .'" title="'. esc_attr($search_form->get_radius_value()) .'"></div>';
										echo '<input type="hidden" name="radius" id="radius_'. esc_attr($search_form->form_id) .'" value="'. esc_attr($search_form->get_radius_value()) .'" />';
									echo '</div>';
								echo '</div>';
							echo '</div>';
						}
					echo '</div>';
				echo '</div>';
				$directorypress_object->search_fields->display_fields($search_form);

				if (!$search_form->args['on_row_search_button']){
					echo '<div class="search-element-col pull-right directorypress-search-submit-button-wrap" style="width:'. esc_attr($button_field_width) .'%; padding:0 '. esc_attr($search_form->args['gap_in_fields']) .'px; margin-top:'. esc_attr($search_form->args['search_button_margin_top']) .'px;">';
						echo wp_kses_post($search_form->display_search_button(true));
					echo '</div>';
				}
				$directorypress_object->search_fields->display_advanced_fields($search_form);
				echo '<div class="clear_float"></div>';
			echo '</div>';
			do_action('post_search_form_html', $search_form->form_id);
		echo '</div>';
			echo '<div class="directorypress-search-section directorypress-search-form-bottom clearfix">';
				if ($search_form->is_advanced_search_panel){
					echo '<script>
						(function($) {
							"use strict";

							$(function() {
								directorypress_advancedSearch('. esc_attr($search_form->form_id) .');
							});
						})(jQuery);
					</script>';
					echo '<div class="directorypress-col-md-6 form-group pull-left">';
						echo '<a id="directorypress-advanced-search-label_'. esc_attr($search_form->form_id) .'" class="directorypress-advanced-search-label" href="javascript: void(0);"><span class="directorypress-advanced-search-text">'. esc_html__('Advanced Filters', 'DIRECTORYPRESS').'</span> <span class="directorypress-advanced-search-toggle fas fa-plus-circle"></span></a>';
					echo '</div>';
				}

				do_action('buttons_search_form_html', $search_form->form_id);
				
			echo '</div>';
	echo '</form>';