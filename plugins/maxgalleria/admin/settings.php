<?php
global $maxgalleria;
$settings = $maxgalleria->settings;

$addons = $maxgalleria->get_all_addons();
$templates = $maxgalleria->get_template_addons();
$media_sources = $maxgalleria->get_media_source_addons();

// Filter for image templates
$image_templates = array('Image Tiles' => 'image-tiles');
foreach ($templates as $template) {
	if ($template['subtype'] == 'image') {
		$image_templates = array_merge($image_templates, array($template['name'] => $template['key']));
	}
}

// Filter for video templates
$video_templates = array('Video Tiles' => 'video-tiles');
foreach ($templates as $template) {
	if ($template['subtype'] == 'video') {
		$video_templates = array_merge($video_templates, array($template['name'] => $template['key']));
	}
}

// Sort arrays
asort($templates);
asort($image_templates);
asort($video_templates);
asort($media_sources);

$addon = sanitize_text_field(isset($_GET['addon']) ? $_GET['addon'] : '');
?>

<script type="text/javascript">		
	jQuery(document).ready(function() {
    jQuery(document).on("click", "#save-general-settings", function() {
			jQuery("#save-general-settings-success").hide();
			
			var form_data = jQuery("#form-general-settings").serialize();
			form_data += "&action=save_general_settings";
			
			jQuery.ajax({
				type: "POST",
				url: "<?php echo admin_url('admin-ajax.php') ?>",
				data: form_data,
				success: function(message) {
					if (message == "success") {
						jQuery("#save-general-settings-success").show();
					}
				}
			});
			
			return false;
		});
	});
</script>

<div id="maxgalleria-admin">
	<div class="wrap">
		<div class="icon32">
			<a href="http://maxgalleria.com" target="_blank"><img src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/maxgalleria-icon-32.png" alt="MaxGalleria') ?>" /></a>
		</div>
		
		<h2 class="title"><?php esc_html_e('MaxGalleria: Settings', 'maxgalleria') ?></h2>
		
		<div class="clear"></div>
		
		<div class="mg-settings">
			<div class="main">
				<div class="inside">
					<div class="settings-menu">
						<ul>
							<li><!-- Spacer --></li>
							
							<?php if ($addon == '') { ?>
								<li class="selected"><a href="<?php echo esc_url(MAXGALLERIA_SETTINGS) ?>"><?php esc_html_e('General', 'maxgalleria') ?></a></li>
							<?php } else { ?>
								<li><a href="<?php echo esc_url(MAXGALLERIA_SETTINGS) ?>"><?php esc_html_e('General', 'maxgalleria') ?></a></li>
							<?php } ?>
							
							<?php if (class_exists('MaxGalleriaAlbums')) { ?>
								<?php if ($addon == 'albums') { ?>
									<li class="selected"><a href="<?php echo esc_url(MAXGALLERIA_SETTINGS . '&addon=albums') ?>"><?php esc_html_e('Albums', 'maxgalleria') ?></a></li>
								<?php } else { ?>
									<li><a href="<?php echo esc_url(MAXGALLERIA_SETTINGS . '&addon=albums') ?>"><?php esc_html_e('Albums', 'maxgalleria') ?></a></li>
								<?php } ?>
							<?php } ?>
							
							<li><!-- Spacer --></li>
							<li><strong><?php esc_html_e('Templates', 'maxgalleria') ?></strong></li>
							
							<?php foreach ($templates as $template) { ?>
								<li class="<?php echo esc_attr($template['key'] == $addon ? 'selected' : '') ?>">
									<a href="<?php echo esc_url(MAXGALLERIA_SETTINGS . '&addon=' . $template['key']) ?>"><?php echo esc_html($template['name']) ?></a>
								</li>
							<?php } ?>
							
							<li><!-- Spacer --></li>
							<li><strong><?php esc_html_e('Media Sources', 'maxgalleria') ?></strong></li>
							
							<?php foreach ($media_sources as $media_source) { ?>
								<li class="<?php echo esc_attr($media_source['key'] == $addon ? 'selected' : '') ?>">
									<a href="<?php echo esc_url(MAXGALLERIA_SETTINGS . '&addon=' . $media_source['key']) ?>"><?php echo esc_html($media_source['name']) ?></a>
								</li>
							<?php } ?>
							
							<li><!-- Spacer --></li>
							<li><strong><?php esc_html_e('Other', 'maxgalleria') ?></strong></li>
							
							<?php if (class_exists('MaxGalleriaWatermark')) { ?>
              <li class="<?php echo esc_attr('maxgalleria-watermark' == $addon ? 'selected' : '') ?>">
                <a href="<?php echo esc_url(MAXGALLERIA_SETTINGS . '&addon=maxgalleria-watermark') ?>"><?php esc_html_e('Watermark', 'maxgalleria') ?></a>
              </li>
							<?php } ?>
							
						</ul>
					</div>
					
					<div class="settings-content">
						<?php if ($addon == '') { ?>
							<div id="save-general-settings-success" class="alert alert-success" style="display: none;">
								<?php printf(esc_html__('Settings saved. %sRe-save your permalinks%s to avoid "page not found" errors.', 'maxgalleria'), '<a href="' . admin_url() . 'options-permalink.php">', '</a>') ?>
							</div>
							
							<form id="form-general-settings">								
								<div class="settings-title">
									<?php esc_html_e('Gallery Rewrite Slug', 'maxgalleria') ?>
								</div>
								<div class="settings-options">
									<p class="note"><?php esc_html_e('The rewrite slug is how WordPress knows to display your galleries.', 'maxgalleria') ?></p>
									<p><?php echo esc_url(home_url()) ?>/<input type="text" id="<?php echo esc_attr(MAXGALLERIA_SETTING_REWRITE_SLUG) ?>" name="<?php echo esc_attr(MAXGALLERIA_SETTING_REWRITE_SLUG) ?>" value="<?php echo esc_html($maxgalleria->settings->get_rewrite_slug()) ?>" style="font-size: 13px; width: 80px;" />/<?php esc_html_e('gallery-name', 'maxgalleria') ?></p>
								</div>
							
								<div class="settings-title">
									<?php esc_html_e('Searchable Galleries', 'maxgalleria') ?>
								</div>
								<div class="settings-options">
									<p class="note"><?php esc_html_e('By default all galleries will appear in your search results; you can disable this using the option below. This setting affects all galleries.', 'maxgalleria') ?></p>
									<p>
										<label for="<?php echo esc_attr(MAXGALLERIA_SETTING_EXCLUDE_GALLERIES_FROM_SEARCH) ?>"><?php esc_html_e('Exclude from Search:', 'maxgalleria') ?></label>
										<input type="checkbox" style="margin-left: 10px;" id="<?php echo esc_attr(MAXGALLERIA_SETTING_EXCLUDE_GALLERIES_FROM_SEARCH) ?>" name="<?php echo esc_attr(MAXGALLERIA_SETTING_EXCLUDE_GALLERIES_FROM_SEARCH) ?>" <?php echo esc_attr(($maxgalleria->settings->get_exclude_galleries_from_search() == 'on') ? 'checked' : '') ?> />
									</p>
								</div>
																
								<div class="settings-title">
									<?php esc_html_e('Default Templates', 'maxgalleria') ?>
								</div>
								<div class="settings-options">
									<p class="note"><?php esc_html_e('Select the default template for image and video galleries. These can be changed per gallery.', 'maxgalleria') ?></p>
									<table>
										<tr>
											<td><?php esc_html_e('Image Galleries:', 'maxgalleria') ?></td>
											<td>
												<select id="<?php echo esc_attr(MAXGALLERIA_SETTING_DEFAULT_IMAGE_GALLERY_TEMPLATE) ?>" name="<?php echo esc_attr(MAXGALLERIA_SETTING_DEFAULT_IMAGE_GALLERY_TEMPLATE) ?>">
												<?php foreach ($image_templates as $name => $key) { ?>
													<?php $selected = ($maxgalleria->settings->get_default_image_gallery_template() == $key) ? 'selected=selected' : ''; ?>
													<option value="<?php echo esc_attr($key) ?>" <?php echo esc_attr($selected) ?>><?php echo esc_html($name) ?></option>
												<?php } ?>
												</select>
											</td>
										</tr>
										<tr>
											<td><?php esc_html_e('Video Galleries:', 'maxgalleria') ?></td>
											<td>
												<select id="<?php echo esc_attr(MAXGALLERIA_SETTING_DEFAULT_VIDEO_GALLERY_TEMPLATE) ?>" name="<?php echo esc_attr(MAXGALLERIA_SETTING_DEFAULT_VIDEO_GALLERY_TEMPLATE) ?>">
												<?php foreach ($video_templates as $name => $key) { ?>
													<?php $selected = ($maxgalleria->settings->get_default_video_gallery_template() == $key) ? 'selected=selected' : ''; ?>
													<option value="<?php echo esc_attr($key) ?>" <?php echo esc_attr($selected) ?>><?php echo esc_html($name) ?></option>
												<?php } ?>
												</select>
											</td>
										</tr>
									</table>									
								</div>
																																								
								<?php wp_nonce_field($settings->nonce_save_general_settings['action'], $settings->nonce_save_general_settings['name']) ?>
							</form>
							
							<a id="save-general-settings" href="#" class="button button-primary"><?php esc_html_e('Save Settings', 'maxgalleria') ?></a>
						<?php } else { ?>						
							<?php if (class_exists('MaxGalleriaAlbums') && $addon == 'albums') { ?>
								<?php global $maxgalleria_albums ?>
								<?php include_once($maxgalleria_albums->settings) ?>
							<?php } ?>
							
							<?php foreach ($addons as $a) { ?>
								<?php if ($a['key'] == $addon) { ?>
									<?php include_once($a['settings']) ?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</div>
					
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
</div>
