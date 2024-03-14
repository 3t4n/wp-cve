<?php
global $maxgalleria;
$nextgen = $maxgalleria->nextgen;

if ($nextgen->is_nextgen_installed()) {
	// Get all the NextGEN galleries
	$nextgen_galleries = $nextgen->get_nextgen_galleries();
	
	// Get all the MaxGalleria galleries in publish, private, and draft status
	$args = array(
		'post_type' => MAXGALLERIA_POST_TYPE,
		'post_status' => 'publish,private,draft',
		'numberposts' => -1, // All of them
		'orderby' => 'title',
		'order' => 'ASC'
	);

	$maxgalleria_galleries = get_posts($args);
}
?>

<script type="text/javascript">	
	jQuery(document).ready(function() {
    jQuery(document).on("click", "#import_button", function() {
			// Reset the import counters
			jQuery.ajax({
				type: "POST",
				url: "<?php echo admin_url('admin-ajax.php') ?>",
				data: "action=reset_nextgen_import"
			});
			
			// Hide/show message and progress bar
			jQuery("#import_message").hide();
			jQuery("#import_progress").show();
			jQuery("#import_progress .progress .bar").css("width", "0%");
			
			// Start the import process
			var form_data = jQuery("#import_form").serialize();
			form_data += "&action=import_nextgen_gallery";
			jQuery.ajax({
				type: "POST",
				url: "<?php echo admin_url('admin-ajax.php') ?>",
				data: form_data,
				success: function(message) {
					if (message != "") {
						clearInterval(interval_id);
						jQuery("#import_message").html(message);
						jQuery("#import_message").show();
						jQuery("#import_progress").delay(2500).slideUp(500);
					}
				}
			});
			
			// Start polling
			interval_id = setInterval(pollImportPercent, 500);
			
			return false;
		});
	});
	
	function pollImportPercent() {
		jQuery.ajax({
			type: "POST",
			url: "<?php echo admin_url('admin-ajax.php') ?>",
			data: "action=get_nextgen_import_percent",
			success: function(percentage) {
				jQuery("#import_progress .progress .bar").css("width", percentage + "%");
			}
		});
		
		return false;
	}
</script>

<div id="maxgalleria-admin">
	<div class="wrap">
		<div class="icon32">
			<a href="http://maxgalleria.com" target="_blank"><img src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/maxgalleria-icon-32.png') ?>" alt="MaxGalleria" /></a>
		</div>
		
		<h2 class="title"><?php esc_html_e('MaxGalleria: NextGEN Importer', 'maxgalleria') ?></h2>
		
		<div class="clear"></div>
		
		<div class="section">
			<div class="inside">
				<div class="import">
					<?php if ($nextgen->is_nextgen_installed()) { ?>
						<div id="import_message" class="alert alert-success" style="display: none;"></div>
						
						<div id="import_progress" class="alert alert-info" style="display: none;">
							<table>
								<tr>
									<td valign="top" width="40" rowspan="2">
										<img src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/loading.gif') ?>" style="margin-top: 3px;" />
									</td>
									<td valign="top">
										<h4><?php esc_html_e('Please wait while the images are imported from NextGEN. This may take a few minutes, depending on the number and size of the images.', 'maxgalleria') ?></h4>
									</td>
								</tr>
								<tr>
									<td valign="top">
										<div class="progress">
											<div class="bar" style="width: 0%;"></div>
										</div>
									</td>
								</tr>
							</table>
						</div>
						
						<?php if (isset($nextgen_galleries)) { ?>
							<form id="import_form" method="post">
								<table>
									<tr>
										<td>
											<h4 style="margin: 0px;"><?php esc_html_e('Which NextGEN gallery do you want to import?', 'maxgalleria') ?></h4>
										</td>
									</tr>
									<tr>
										<td>
											<select id="nextgen_gallery_id" name="nextgen_gallery_id">
											<?php
											foreach ($nextgen_galleries as $gallery) {
												$picture_count = $nextgen->get_nextgen_gallery_picture_count($gallery->gid);
												
												$number = '';
												if ($picture_count == 0) { $number = esc_html__(' (0 images)', 'maxgalleria'); }
												if ($picture_count == 1) { $number =  esc_html__(' (1 image)', 'maxgalleria'); }
												if ($picture_count > 1) { $number = sprintf(esc_html__(' (%d images)', 'maxgalleria'), $picture_count); }
												
												echo '<option value="' . esc_attr($gallery->gid) . '">' . esc_html($gallery->title) . esc_html($number) . '</option>';
											}
											?>
											</select>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>
											<h4 style="margin: 0px;"><?php esc_html_e('Where do you want to import it?', 'maxgalleria') ?></h4>
										</td>
									</tr>
									<tr>
										<td>
											<input type="radio" id="maxgalleria_gallery_new" name="maxgalleria_gallery_where" value="new" checked="checked" />
											<label for="maxgalleria_gallery_new"><?php esc_html_e('New MaxGalleria gallery', 'maxgalleria') ?></label>
											<br />
											<input type="text" id="maxgalleria_gallery_title" name="maxgalleria_gallery_title" value="<?php esc_html_e('Enter gallery title', 'maxgalleria') ?>" />
											
											<br /><br />
											
											<input type="radio" id="maxgalleria_gallery_existing" name="maxgalleria_gallery_where" value="existing" />
											<label for="maxgalleria_gallery_existing"><?php esc_html_e('Existing MaxGalleria gallery', 'maxgalleria') ?></label>
											
											<br />
											
											<select id="maxgalleria_gallery_id" name="maxgalleria_gallery_id">
											<?php foreach ($maxgalleria_galleries as $gallery) { ?>
												<?php
												$options = new MaxGalleryOptions($gallery->ID);
												if ($options->is_image_gallery()) {
													$args = array('post_parent' => $gallery->ID, 'post_type' => 'attachment', 'numberposts' => -1);
													$attachments = get_posts($args);
													
													$number = '';
													if (count($attachments) == 0) { $number = esc_html__(' (0 images)', 'maxgalleria'); }
													if (count($attachments) == 1) { $number =  esc_html__(' (1 image)', 'maxgalleria'); }
													if (count($attachments) > 1) { $number = sprintf(esc_html__(' (%d images)', 'maxgalleria'), count($attachments)); }
													
													echo '<option value="' . esc_attr($gallery->ID) . '">' . esc_html($gallery->post_title) . esc_html($number) . '</option>';
												}
												?>
											<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>
											<input type="button" class="button-primary" id="import_button" value="<?php esc_html_e('Import NextGEN Gallery', 'maxgalleria') ?>" />
										</td>
									</tr>
								</table>
								
								<?php wp_nonce_field($nextgen->nonce_nextgen_importer['action'], $nextgen->nonce_nextgen_importer['name']) ?>
							</form>
						<?php } else { ?>
							<h4><?php esc_html_e('You do not have any NextGEN galleries.', 'maxgalleria') ?></h4>
						<?php } ?>
					<?php } else { ?>
						<h4><?php esc_html_e('You do not have the NextGEN Gallery plugin installed.', 'maxgalleria') ?></h4>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
