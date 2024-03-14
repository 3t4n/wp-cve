<?php if ($field->value && isset($field->selection_items[$field->value])):
	$is_listing_widget = do_action('is_listing_widget') . '1';
	global $directorypress_object;
?>
<div class="directorypress-field-item directorypress-field-type-<?php echo esc_attr($field->type); ?>">
	<span class="field-label">
		<?php
			if(!directorypress_is_listing_page() || (directorypress_is_listing_page() && $listing->is_widget)){
				if($listing->listing_view == 'show_grid_style'){
					if($field->is_hide_name_on_grid == 'show_only_label'){
						echo '<span class="directorypress-field-title">'. esc_html($field->name) .':</span>';
					}elseif($field->is_hide_name_on_grid == 'show_icon_label'){
						if ($field->icon_image){
							echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'"></span>';
						}
						echo '<span class="directorypress-field-title">'. esc_html($field->name) .':</span>';
					}elseif($field->is_hide_name_on_grid == 'show_only_icon'){
						if ($field->icon_image){
							echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'"></span>';
						}
					}
				}elseif($listing->listing_view == 'show_list_style'){
					if($field->is_hide_name_on_list == 'show_only_label'){
						echo '<span class="directorypress-field-title">'. esc_html($field->name) .':</span>';
					}elseif($field->is_hide_name_on_list == 'show_icon_label'){
						if ($field->icon_image){
							echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'"></span>';
						}
						echo '<span class="directorypress-field-title">'. esc_html($field->name) .':</span>';
					}elseif($field->is_hide_name_on_list == 'show_only_icon'){
						if ($field->icon_image){
							echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'"></span>';
						}
					}
				}
			}else{
				if ($field->icon_image){
					echo '<span class="directorypress-field-icon fa fa-lg '. esc_attr($field->icon_image) .'"></span>';
				}
				if(!$field->is_hide_name){
					echo '<span class="directorypress-field-title">'. esc_html($field->name) .':</span>';
				}
			}
		?>
	</span>
	<span class="field-content">
		<?php echo esc_html($field->selection_items[$field->value]); ?>
	</span>
</div><?php endif; ?>