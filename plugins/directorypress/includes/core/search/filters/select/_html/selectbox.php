<?php 
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
	if (count($search_field->field->selection_items)){
		$disable = (!$search_field->value)? 'disabled selected' : '';
		echo '<div class="search-element-col field-id-'. esc_attr($search_field->field->id) .' field-form-id-'. esc_attr($search_form->form_id) .' unique-form-field-id-'. esc_attr($search_field->field->id) .'_'. esc_attr($search_form->form_id) .' field-type-'. esc_attr($search_field->field->type) .' pull-left clearfix" style=" width:'. esc_attr($search_field->field_width($search_form)) .'%; padding:0 '. esc_attr($search_form->args['gap_in_fields']) .'px;">';
			$search_field->field_label($search_form);
			echo '<div class="search-field-content-wrapper">';
				echo '<select name="field_'. esc_attr($search_field->field->slug) .'" class="search-field-content-wrapper cs-select cs-skin-elastic directorypress-select2" style="width: 100%;">';
					echo '<option value="" '. esc_attr($disable) .'>';
						printf(esc_html__('- Select %s -', 'DIRECTORYPRESS'), $search_field->field->name);
					echo '</option>';
					foreach ($search_field->field->selection_items AS $key=>$item):
						$selected = (in_array((string)$key, $search_field->value, true))? 'selected' : '';
						echo '<option value="'. esc_attr($key) .'" '. esc_attr($selected) .'>';
							echo esc_html($item);
							if ($search_field->items_count && $key !== ""){ 
								echo '[';
									if (isset($items_count_array[$key])){
										echo esc_html($items_count_array[$key]);
									}else{ 
										echo 0;
									}
								echo ']';
							}
					echo '</option>';
					endforeach;
				echo '</select>';
			echo '</div>';
		echo '</div>';
	}