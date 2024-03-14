<?php 
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
	if (count($search_field->field->selection_items)){
		echo '<div class="search-element-col field-id-'. esc_attr($search_field->field->id) .' field-form-id-'. esc_attr($search_form->form_id) .' unique-form-field-id-'. esc_attr($search_field->field->id) .'_'. esc_attr($search_form->form_id) .' field-type-'. esc_attr($search_field->field->type) .' pull-left clearfix" style=" width:'. esc_attr($search_field->field_width($search_form)) .'%; padding:0 '. esc_attr($search_form->args['gap_in_fields']) .'px;">';
			$search_field->field_label($search_form);
			echo '<div class="search-field-content-wrapper search-checkbox-wrapper">';
				foreach ($search_field->field->selection_items AS $key=>$item){
					$checked = (in_array($key, $search_field->value))? 'checked': '';
					echo '<div class="search-checkbox">';
						echo '<label>';
							echo '<input type="checkbox" name="field_'. esc_attr($search_field->field->slug) .'[]" value="'. esc_attr($key) .'" '. esc_attr($checked) .' class="selectpicker" />';
							echo '<span class="checkbox-item-name">';
								echo esc_html($item);
								if ($search_field->items_count && $key !== ""){ 
									echo '<span class="field-item-count">';
										if (isset($items_count_array[$key])){
											echo esc_html($items_count_array[$key]);
										}else{ 
											echo 0;
										}
									echo '</span>';
								}
							echo '</span>';
							echo '<span class="search-checkbox-item"></span>';
						echo '</label>';
					echo '</div>';
				}
			echo '</div>';
		echo '</div>';
	}