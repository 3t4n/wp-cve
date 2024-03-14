<div class="field small-12 columns">
	<?php if ($label) : ?>
		<label>
			<?php echo $label; ?>
		</label>
	<?php endif; ?>
	
	<div class="input-group large-10 small-12 columns">

		<select id="<?php echo $id; ?>" <?php echo $attributes; ?>
			    name="<?php echo $name; ?>"
			    data-dependents='<?php echo $dependents; ?>' 
				class="input-group-field"
			    >
			    <option value="-1" <?php selected( $value, '-1' ); ?> ><?php _e('Default theme', 'gallery-images-ape');  ?> </option>
			    <?php 
			    	foreach ($themes as $theme){
			    		$type = get_post_meta( (int) $theme->ID, WPAPE_GALLERY_NAMESPACE.'type', true );
	    				echo '<option value="'.$theme->ID.'" '.selected( $value, $theme->ID ).'>'.esc_html($theme->post_title.' ['.$type.']').'</option>';
					} 
				?>
		</select>

	</div>
	<div class="large-2 small-12 columns">
		<a 
			id="link_edit_<?php echo $id; ?>" 
			target="_blank" 
			href="<?php echo admin_url('edit.php?'.( $value==-1 ? 'post_type=wpape_gallery_theme&wpape_gallery_theme_action=themeDefaultRedirect':  'action=edit&post='.(int)$value ) ); ?>" 
			class="success button"
		>
			<?php _e('Edit'); ?>
		</a>
		<script>
			( function() {
				var themeSelect = document.getElementById('<?php echo $id; ?>');
				var themeEditLink = document.getElementById('link_edit_<?php echo $id; ?>');
				
				themeEditLink.addEventListener("click", function(event) {
					event.preventDefault();
					var themeID = themeSelect.value;
					var link = 'post.php?action=edit&post='+themeID;
					if(themeID==-1){
						link= 'edit.php?post_type=wpape_gallery_theme&wpape_gallery_theme_action=themeDefaultRedirect';
					}
					themeEditLink.setAttribute('href', link);
					var win = window.open(link, '_blank');
  					win.focus();
				});
			})();
		</script>
	</div>

	<?php if ($description) : ?>
		<div class="large-12  small-12 columns">
			<p class="help-text"><?php echo $description; ?></p>
		</div>
	<?php endif; ?>
</div>
