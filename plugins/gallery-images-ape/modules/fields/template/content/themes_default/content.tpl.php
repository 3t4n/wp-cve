<?php global $post; ?>
<br />
<div id="wapeGalleryThemesDefaultButtonDiv" align="center"> 
	<button 
		id="wapeGalleryThemesDefaultButton" 
		class="button success button expanded" 
		data-nonce="<?php echo wp_create_nonce( "wpape_gallery_themes_default_".$post->ID );?>" 
		data-id="<?php echo $post->ID;?>"
		><?php _e('Set Default', 'gallery-images-ape');?></button>	
</div>
<p id="wpape_gallery_fields_themes_default_message"></p>
<p class="help-text">
	<?php _e('select this theme as default for every new and existing gallery, except galleries where you select other theme', 'gallery-images-ape');?>
</p>