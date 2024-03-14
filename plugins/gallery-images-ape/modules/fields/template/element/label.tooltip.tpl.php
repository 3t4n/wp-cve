<div class="wpape_gallery_tooltip">
	<h5 class="inline-block twoj-gallery-help-label"><?php echo $label; ?></h5>
 
	<span 
		class="dashicons dashicons-info twoj-gallery-help-button" 
		data-help="help_content_<?php echo $id; ?>"
	></span>
	<span class="wpape_gallery_tooltiptext">
		<?php _e('Click for information', 'gallery-images-ape'); ?>	
	</span>

	<?php if($help) : ?>
		<div id="help_content_<?php echo $id; ?>" class="twoj-gallery-help-dialog">
			<?php echo $help; ?>
		</div>
	<?php endif; ?>
</div>