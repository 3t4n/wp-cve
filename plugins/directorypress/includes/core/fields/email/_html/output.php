<?php if ($field->value):

	global $post; 
	$toogle_id =  $field->id .'-'. $post->ID;
	
	$tooltip_attr = '';
	$tootltip_triger_class = '';
	$tooltip_content_class = '';
	
?>
<div id="directorypress-<?php echo esc_attr($field->id); ?>" class="directorypress-field-item directorypress-field-type-<?php echo esc_attr($field->type); ?>">
	<meta itemprop="email" content="<?php echo esc_attr($field->value); ?>" />
	<span class="field-label">
			<?php
				if(!directorypress_is_listing_page()){
					if($listing->listing_view == 'show_grid_style'){
						if($field->is_hide_name_on_grid == 'show_only_label'){
							echo '<span class="directorypress-field-title" data-toggle="tooltip" title="'.antispambot($field->value).'" data-placement="auto">'. esc_html($field->name) .':</span>';
						}elseif($field->is_hide_name_on_grid == 'show_icon_label'){
							if ($field->icon_image){
								echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'" data-toggle="tooltip" title="'.antispambot($field->value).'" data-placement="auto"></span>';
							}
							echo '<span class="directorypress-field-title" data-toggle="tooltip" title="'.antispambot($field->value).'" data-placement="auto">'. esc_html($field->name) .':</span>';
						}elseif($field->is_hide_name_on_grid == 'show_only_icon'){
							if ($field->icon_image){
								echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'" data-toggle="tooltip" title="'.antispambot($field->value).'" data-placement="auto"></span>';
							}
						}
					}elseif($listing->listing_view == 'show_list_style'){
						if($field->is_hide_name_on_list == 'show_only_label'){
							echo '<span class="directorypress-field-title" data-toggle="tooltip" title="'.antispambot($field->value).'" data-placement="auto">'. esc_html($field->name) .':</span>';
						}elseif($field->is_hide_name_on_list == 'show_icon_label'){
							if ($field->icon_image){
								echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'" data-toggle="tooltip" title="'.antispambot($field->value).'" data-placement="auto"></span>';
							}
							echo '<span class="directorypress-field-title" data-toggle="tooltip" title="'.antispambot($field->value).'" data-placement="auto">'. esc_html($field->name) .':</span>';
						}elseif($field->is_hide_name_on_list == 'show_only_icon'){
							if ($field->icon_image){
								echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'" data-toggle="tooltip" title="'.antispambot($field->value).'" data-placement="auto"></span>';
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
	<?php if(directorypress_is_listing_page()){ ?>
		<span class="field-content">
			<a href="mailto:<?php echo antispambot($field->value); ?>"><?php echo antispambot($field->value); ?></a>
		</span>
	<?php } ?>
</div>
<?php endif; ?>