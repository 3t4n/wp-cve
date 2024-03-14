<?php if ($field->value['url']): ?>
<div id="directorypress-<?php echo esc_attr($field->id); ?>" class="directorypress-field-item directorypress-field-type-<?php echo esc_attr($field->type); ?>">
		<span class="field-label">
			<?php
				if(!directorypress_is_listing_page()){
					if($listing->listing_view == 'show_grid_style'){
						if($field->is_hide_name_on_grid == 'show_only_label'){
							echo '<span class="directorypress-field-title" data-toggle="tooltip" title="'.esc_url($field->value['url']).'" data-placement="auto">'. esc_html($field->name) .':</span>';
						}elseif($field->is_hide_name_on_grid == 'show_icon_label'){
							if ($field->icon_image){
								echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'" data-toggle="tooltip" title="'.esc_url($field->value['url']).'" data-placement="auto"></span>';
							}
							echo '<span class="directorypress-field-title" data-toggle="tooltip" title="'.esc_url($field->value['url']).'" data-placement="auto">'. esc_html($field->name) .':</span>';
						}elseif($field->is_hide_name_on_grid == 'show_only_icon'){
							if ($field->icon_image){
								echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'" data-toggle="tooltip" title="'.esc_url($field->value['url']).'" data-placement="auto"></span>';
							}
						}
					}elseif($listing->listing_view == 'show_list_style'){
						if($field->is_hide_name_on_list == 'show_only_label'){
							echo '<span class="directorypress-field-title" data-toggle="tooltip" title="'.esc_url($field->value['url']).'" data-placement="auto">'. esc_html($field->name) .':</span>';
						}elseif($field->is_hide_name_on_list == 'show_icon_label'){
							if ($field->icon_image){
								echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'" data-toggle="tooltip" title="'.esc_url($field->value['url']).'" data-placement="auto"></span>';
							}
							echo '<span class="directorypress-field-title" data-toggle="tooltip" title="'.esc_url($field->value['url']).'" data-placement="auto">'. esc_html($field->name) .':</span>';
						}elseif($field->is_hide_name_on_list == 'show_only_icon'){
							if ($field->icon_image){
								echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'" data-toggle="tooltip" title="'.esc_url($field->value['url']).'" data-placement="auto"></span>';
							}
						}
					}
				}else{
					if ($field->icon_image){
						echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'"></span>';
					}
					if(!$field->is_hide_name){
						echo '<span class="directorypress-field-title">'. esc_html($field->name) .':</span>';
					}
				}
			?>
		</span>
		<?php if(directorypress_is_listing_page()): ?>
			<span id="<?php echo esc_attr($field->id); ?>" class="field-content">
				<a itemprop="url"
					href="<?php echo esc_url($field->value['url']); ?>"
					<?php if ($field->is_blank) echo 'target="_blank"'; ?>
					<?php if ($field->is_nofollow) echo 'rel="nofollow"'; ?>
				><?php if ($field->value['text'] && $field->use_link_text) echo esc_html($field->value['text']); else echo esc_html($field->value['url']); ?></a>
			</span>
		<?php endif; ?>
	</div>
<?php endif; ?>