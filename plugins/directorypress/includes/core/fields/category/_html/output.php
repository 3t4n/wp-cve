<?php global $DIRECTORYPRESS_ADIMN_SETTINGS; ?>
<?php if (has_term('', DIRECTORYPRESS_CATEGORIES_TAX, $listing->post->ID)): ?>
<div class="directorypress-field-item directorypress-field-type-<?php echo esc_attr($field->type); ?>">
	<?php if ($field->icon_image || !$field->is_hide_name): ?>
	<span class="field-label">
		<?php if ($field->icon_image): ?>
		<span class="directorypress-field-icon<?php echo esc_attr($field->icon_image); ?>"></span>
		<?php endif; ?>
		<?php if (!$field->is_hide_name): ?>
		<span class="directorypress-field-title"><?php echo esc_html($field->name); ?>:</span>
		<?php endif; ?>
	</span>
	<?php endif; ?>
	<span class="field-content">
		<?php
		$terms = get_the_terms($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX);
		foreach ($terms as $term):?>
		<?php 
		if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_post_style'] == 4){
			$directorypress_cat_color = get_listing_category_color($term->term_id);
			if(!empty($directorypress_cat_color)){
			$icon_color = get_listing_category_color($term->term_id); 
			}else{
				$icon_color = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_primary_color'];
			}
		}else{
			
			$icon_color = '';
		}
		?>
			<span class="directorypress-label directorypress-label-primary"><a style="background-color:<?php echo esc_attr($icon_color); ?>;" href="<?php echo get_term_link($term, DIRECTORYPRESS_CATEGORIES_TAX); ?>" rel="tag"><?php echo esc_html($term->name); ?></a>&nbsp;&nbsp;<span class="glyphicon glyphicon-tag"></span></span>
		<?php endforeach; ?>
	</span>
</div>
<?php endif; ?>