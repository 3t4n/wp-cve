<?php
global $post;
$options = new MaxGalleryOptions($post->ID);

if(get_option('show_template_ad', "on") === "on")
  $show_temp_ad = true;
else
  $show_temp_ad = false;

// Get all templates
global $maxgalleria;
$all_templates = $maxgalleria->get_template_addons();

// Filter for image templates
$image_templates = array();
foreach ($all_templates as $template) {
	if ($template['subtype'] == 'image' || $template['subtype'] == 'both') {
		$arr = array($template['key'] => array('name' => $template['name'], 'image' => $template['image']));
		$image_templates = array_merge($image_templates, $arr);
	}
}

// Filter for video templates
$video_templates = array();
foreach ($all_templates as $template) {
	if ($template['subtype'] == 'video' || $template['subtype'] == 'both') {
		$arr = array($template['key'] => array('name' => $template['name'], 'image' => $template['image']));
		$video_templates = array_merge($video_templates, $arr);
	}
}

// Default to image templates
$templates = $image_templates;

// But check to see if it should use video templates
if ($options->is_video_gallery()) {
	$templates = $video_templates;
}

asort($templates);
?>

<script type="text/javascript">		
	jQuery(document).ready(function() {
		
    jQuery(document).on("click", ".meta-options .meta-template img", function() {
			var images = jQuery(".meta-options .meta-template img");
			jQuery.each(images, function() {
				jQuery(this).removeClass("selected");
			});
			
			jQuery(this).addClass("selected");
			jQuery(this).next().attr("checked", true);
			
			jQuery("#post").submit();
			return false;
		});
		
  jQuery(document).on("click", "#add-close-btn", function() {
		jQuery.ajax({
			type: "POST",
			async: true,
			data: { action: "mg_hide_template_ad",  nonce: "<?php echo wp_create_nonce(MG_META_NONCE); ?>" },
			url: "<?php echo admin_url('admin-ajax.php') ?>",
			dataType: "html",
			success: function (data) {
				jQuery("#mg-template-ad").hide();          
			},
			error: function (err)
				{ alert(err.responseText);}
		});

	});		
		
	});
</script>

<div class="meta-options">
<div class="meta-ad-box">	
	<?php if (count($templates) > 0) { ?>
		<?php foreach ($templates as $key => $template) { ?>
			<div class="meta-template">
				<img src="<?php echo esc_url($template['image']) ?>" alt="<?php echo esc_attr($template['name']) ?>" title="<?php echo esc_attr($template['name']) ?>" <?php echo esc_attr(($options->get_template() == $key) ? 'class=selected' : '') ?> />
				<input type="radio" name="<?php echo esc_attr($options->template_key) ?>" id="<?php echo esc_attr($options->template_key . '_' . $key) ?>" value="<?php echo esc_attr($key) ?>" <?php echo esc_attr(($options->get_template() == $key) ? 'checked="checked"' : '') ?> />
				<br />
				<?php echo esc_html($template['name']) ?>			
			</div>
		<?php } ?>
		
		<!-- Saves count is for internal use only -->
		<input type="hidden" id="<?php echo esc_attr($options->saves_count_key) ?>" name="<?php echo esc_attr($options->saves_count_key) ?>" value="<?php echo esc_attr($options->get_saves_count()) ?>" />
	<?php } else { ?>
		<?php if ($options->is_image_gallery()) { ?>
			<p class="no-templates"><?php esc_html_e('You do not have any image gallery templates installed.', 'maxgalleria')?></p>
		<?php } ?>
		
		<?php if ($options->is_video_gallery()) { ?>
			<p class="no-templates"><?php esc_html_e('You do not have any video gallery templates installed.', 'maxgalleria')?></p>
		<?php } ?>
	<?php } ?>
</div>
<?php if($show_temp_ad) { ?>
<div class="clearfix"></div>
	<div id="mg-template-ad" class=" meta-ad-box meta-seprator">
		<a id="add-close-btn">x</a>
		<p class="mg-promo-template-title"><a target="_blank" href="<?php echo esc_url(MG_ADDON_PAGE_LINK); ?>">If you are looking for other Layouts we offer the following</a></p>
    
    <div>
    
    <ul>      
      
			<li class="mg-templates small-2 medium-2 large-2 columns temp-ad-height">
							<a href="<?php echo esc_url(MG_SLICK_SLIDER_LINK) ?>" target="_blank" >
				  <img class="mg-ad-image anime" width="99" height="89" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL . '/images/templates/slick-slider-1.png') ?>" />
				</a>
				<h5>
					<a href="<?php echo esc_url(MG_SLICK_SLIDER_LINK) ?>" target="_blank" >SLICK SLIDER</a>
				</h5>
			</li> 				
      
			<li class="mg-templates small-2 medium-2 large-2 columns temp-ad-height">
        <a href="<?php echo esc_url(MG_IMAGE_CAROUSEL_LINK) ?>" target="_blank" >				
				  <img class="mg-ad-image anime" width="99" height="89" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/templates/image-carousel-1.png') ?>" />
				</a>
				<h5>
					<a href="<?php echo esc_url(MG_IMAGE_CAROUSEL_LINK) ?>" target="_blank" >IMAGE CAROUSE</a>
				</h5>
			</li>
      
			<li class="mg-templates small-2 medium-2 large-2 columns temp-ad-height">
				<a href="<?php echo esc_url(MG_IMAGE_SLIDER_LINK) ?>" target="_blank" >
				  <img class="mg-ad-image anime" width="99" height="89" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/templates/image-slider-1.png') ?>" />
				</a>
				<h5>
					<a href="<?php echo esc_url(MG_IMAGE_SLIDER_LINK) ?>" target="_blank" >IMAGE SLIDER</a>
				</h5>
			</li>

			<li class="mg-templates small-2 medium-2 large-2 columns temp-ad-height">
				<a href="<?php echo esc_url(MG_IMAGE_SHOWCASE_LINK) ?>" target="_blank" >
				  <img class="mg-ad-image anime" width="99" height="89" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/templates/image-showcase-1.png') ?>" />
				</a>
				<h5>
					<a href="<?php echo esc_url(MG_IMAGE_SHOWCASE_LINK) ?>" target="_blank" >IMAGE SHOWCASE</a>
				</h5>
			</li>
      
			<li class="mg-templates small-2 medium-2 large-2 columns temp-ad-height">
				<a href="<?php echo esc_url(MG_MASONRY_LINK) ?>" target="_blank" >
				  <img class="mg-ad-image anime" width="99" height="89" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/templates/masonry-1.png') ?>" />
				</a>
				<h5>
					<a href="<?php echo esc_url(MG_MASONRY_LINK) ?>" target="_blank" >MASONRY</a>
				</h5>
			</li>
				
			<li class="mg-templates small-2 medium-2 large-2 columns temp-ad-height">
				<a href="<?php echo esc_url(MG_VIDEO_SHOWCASE_LINK) ?>" target="_blank" >
				  <img class="mg-ad-image anime" width="99" height="89" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/templates/video-showcase-1.png') ?>" />
				</a>
				<h5>
					<a href="<?php echo esc_url(MG_VIDEO_SHOWCASE_LINK) ?>" target="_blank" >VIDEO SHOWCASE</a>
				</h5>
			</li>

			<li class="mg-templates small-2 medium-2 large-2 columns temp-ad-height">
				<a href="<?php echo esc_url(MG_ALBUMS_LINK) ?>" target="_blank" >
				  <img class="mg-ad-image anime" width="99" height="89" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/templates/albums-1.png') ?>" />
				</a>
				<h5>
					<a href="<?php echo esc_url(MG_ALBUMS_LINK) ?>" target="_blank" >ALBUMS</a>
				</h5>
			</li>
							
			<li class="mg-templates small-2 medium-2 large-2 columns temp-ad-height">
							<a href="<?php echo esc_url(MG_FWGRID_LINK) ?>" target="_blank" >
				  <img class="mg-ad-image anime" width="99" height="89" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/templates/fw-grid.png') ?>" />
				</a>
				<h5>
					<a href="<?php echo esc_url_raw(MG_FWGRID_LINK); ?>" target="_blank" >FULL WIDTH GRID</a>
				</h5>
			</li> 								
				
			<li class="mg-templates small-2 medium-2 large-2 columns temp-ad-height">
							<a href="<?php echo esc_url(MG_HERO_LINK) ?>" target="_blank" >
				  <img class="mg-ad-image anime" width="99" height="89" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/templates/hero-slider.png') ?>" />
				</a>
				<h5>
					<a href="<?php echo esc_url(MG_HERO_LINK) ?>" target="_blank" >HERO SLIDER</a>
				</h5>
			</li> 												
        
    </ul>
    <div style="clear:both"></div>
    
    </div>
   		
		<p class="mg-promo-template-title"><a target="_blank" href="<?php echo esc_url(MG_ADDON_PAGE_LINK) ?>">We offer the following Media Source Addons</a></p>		
		
		<ul id="mg-media-sources">

				<li class="mg-templates small-3 medium-3 columns temp-ad-height">
					<a href="<?php echo esc_url(MG_FACEBOOK_LINK) ?>" target="_blank" >
						<img class="mg-ad-image anime" width="99" height="89" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/templates/facebook-1.png') ?>" />
					</a>
					<h5>
						<a href="<?php echo esc_url(MG_FACEBOOK_LINK) ?>" target="_blank" ">FACEBOOK</a>
					</h5>
				</li>

				<li class="mg-templates small-3 medium-3 columns temp-ad-height">
					<a href="<?php echo esc_url(MG_VIMEO_LINK) ?>" target="_blank" >
						<img class="mg-ad-image anime" width="99" height="89" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/templates/vimeo-1.png') ?>" />
					</a>
					<h5>
						<a href="<?php echo esc_url(MG_VIMEO_LINK) ?>" target="_blank" >VIMEO</a>
					</h5>
				</li>

				<li class="mg-templates small-3 medium-3 columns temp-ad-height">
					<a href="<?php echo esc_url(MG_INSTAGRAM_LINK) ?>" target="_blank" >
						<img class="mg-ad-image anime" width="99" height="89" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/templates/instagram-1.png') ?>" />
					</a>
					<h5>
						<a href="<?php echo esc_url(MG_INSTAGRAM_LINK) ?>" target="_blank">INSTAGRAM</a>
					</h5>
				</li>

				<li class="mg-templates small-3 medium-3 columns temp-ad-height">
					<a href="<?php echo esc_url(MG_FLICKR_LINK) ?>" target="_blank" >
						<img class="mg-ad-image anime" width="99" height="89" src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/templates/flickr-1.png') ?>" />
					</a>
					<h5>
						<a href="<?php echo esc_url(MG_FLICKR_LINK) ?>" target="_blank" >FLICKR</a>
					</h5>
				</li>

		</ul>
    <div style="clear:both"></div>
		
	</div>
<?php } ?>
</div>
			
