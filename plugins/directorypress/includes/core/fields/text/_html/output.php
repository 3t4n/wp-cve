<?php if ($field->value): 
	global $post;
	$field_label_display = (!$field->is_field_in_line)? 'block-field-label' : 'inline-field-label';
	$field_content_display = (!$field->is_field_in_line)? 'block-field-content' : 'inline-field-content';
?>
<div id="directorypress-<?php echo esc_attr($field->id); ?>" class="directorypress-field-item directorypress-field-type-<?php echo esc_attr($field->type); ?>">
	<span class="field-label <?php echo esc_attr($field_label_display); ?>">
			<?php
				if(!directorypress_is_listing_page() || (directorypress_is_listing_page() && $listing->is_widget)){
					if($listing->listing_view == 'show_grid_style'){
						
						if($field->is_hide_name_on_grid == 'show_only_label'){
							if(!$field->is_field_in_line){
								echo '<span class="directorypress-field-title">'. esc_html($field->name) .':</span>';
							}else{
								echo '<span class="directorypress-field-title" data-toggle="tooltip" title="'.antispambot($field->value).'" data-placement="auto">'. esc_html($field->name) .':</span>';
							}
						}elseif($field->is_hide_name_on_grid == 'show_icon_label'){
							if ($field->icon_image){
								if(!$field->is_field_in_line){
									echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'"></span>';
								}else{
									echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'" data-toggle="tooltip" title="'.antispambot($field->value).'" data-placement="auto">'. esc_html($field->name) .':</span>';
								}
							}
							echo '<span class="directorypress-field-title" data-toggle="tooltip" title="'.antispambot($field->value).'" data-placement="auto">'. esc_html($field->name) .':</span>';
						}elseif($field->is_hide_name_on_grid == 'show_only_icon'){
							if ($field->icon_image){
								if(!$field->is_field_in_line){
									echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'"></span>';
								}else{
									echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'" data-toggle="tooltip" title="'.antispambot($field->value).'" data-placement="auto"></span>';
								}
							}
						}
					}elseif($listing->listing_view == 'show_list_style'){
						if($field->is_hide_name_on_list == 'show_only_label'){
							if(!$field->is_field_in_line){
								echo '<span class="directorypress-field-title">'. esc_html($field->name) .':</span>';
							}else{
								echo '<span class="directorypress-field-title" data-toggle="tooltip" title="'.antispambot($field->value).'" data-placement="auto">'. esc_html($field->name) .':</span>';
							}
						}elseif($field->is_hide_name_on_list == 'show_icon_label'){
							if ($field->icon_image){
								if(!$field->is_field_in_line){
									echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'"></span>';
								}else{
									echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'" data-toggle="tooltip" title="'.antispambot($field->value).'" data-placement="auto">'. esc_html($field->name) .':</span>';
								}
							}
							echo '<span class="directorypress-field-title" data-toggle="tooltip" title="'.antispambot($field->value).'" data-placement="auto">'. esc_html($field->name) .':</span>';
						}elseif($field->is_hide_name_on_list == 'show_only_icon'){
							if ($field->icon_image){
								if(!$field->is_field_in_line){
									echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'"></span>';
								}else{
									echo '<span class="directorypress-field-icon '. esc_attr($field->icon_image) .'" data-toggle="tooltip" title="'.antispambot($field->value).'" data-placement="auto"></span>';
								}
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
	<?php if(directorypress_is_listing_page() && !$listing->is_widget): ?>
		<span id="<?php echo esc_attr($field->id); ?>" class="field-content <?php if ($field->is_phone): ?>field-phone-content<?php endif; ?>">
			<?php if ($field->is_phone): ?>
			<meta itemprop="telephone" content="<?php echo esc_attr($field->value); ?>" />
			<a href="tel:<?php echo esc_attr($field->value); ?>"><?php echo antispambot($field->value); ?></a>
			<?php else: ?>
			<?php echo esc_html($field->value); ?>
			<?php endif; ?>
		</span>
	<?php else: ?>
		<?php if(!$field->is_field_in_line): ?>
			<span id="<?php echo esc_attr($field->id); ?>" class="field-content <?php echo esc_attr($field_content_display); ?> <?php if ($field->is_phone): ?>field-phone-content<?php endif; ?>">
				<?php if ($field->is_phone): ?>
				<meta itemprop="telephone" content="<?php echo esc_attr($field->value); ?>" />
				<a href="tel:<?php echo esc_attr($field->value); ?>"><?php echo antispambot($field->value); ?></a>
				<?php else: ?>
				<?php echo esc_html($field->value); ?>
				<?php endif; ?>
			</span>
		<?php endif; ?>
	<?php endif; ?>
</div>
<?php endif; ?>