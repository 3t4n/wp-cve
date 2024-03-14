<?php
global $post;
global $maxgalleria;

$video_gallery = $maxgalleria->video_gallery;

// Get all video media source addons
$video_addons = array('' => '');
$media_source_addons = $maxgalleria->get_media_source_addons();

foreach ($media_source_addons as $addon) {
	if ($addon['subtype'] == 'video') {
		$video_addons = array_merge($video_addons, array($addon['name'] => $addon['key']));
	}
}

// Determine count of video addons
$video_addons_count = 0;
foreach ($video_addons as $name => $key) {
	if ($name != '') {
		$video_addons_count++;
	}
}

$args = array(
	'post_type' => 'attachment',
	'numberposts' => -1, // All of them
	'post_parent' => $post->ID,
	'orderby' => 'menu_order',
	'order' => 'ASC'
);

$attachments = get_posts($args);
?>

<script type="text/javascript">
// Javascript for reordering image rows  
var row;
function start(){
  row = event.target;
}

function dragover(){
  var e = event;
  e.preventDefault();

  let children= Array.from(e.target.parentNode.parentNode.children);
  if(children.indexOf(e.target.parentNode)>children.indexOf(row))
    e.target.parentNode.after(row);
  else
    e.target.parentNode.before(row);
}

function dragend() {
  // updated menu order after reorder
  var counter = 1;
  jQuery(".maxgalleria-meta .media table td.ms-order").each(function() {    
    jQuery(this).html(counter);
    jQuery(this).siblings().find(".media-order-input").val(counter);
    counter++;
  });
    
  reorderVideos();
}
    
	jQuery(document).ready(function() {
		// To add the video count in the meta box title
		jQuery("#meta-video-gallery h3.hndle span").html("<?php esc_html_e('Gallery', 'maxgalleria') ?> (<?php echo esc_html(count($attachments)) ?> <?php esc_html_e('videos', 'maxgalleria') ?>)");
		    
		// Lightbox
    jQuery('.lightbox').topbox();
    				
		jQuery("#gallery_media_select_button").click(function() {				
      jQuery('#video-add-popup').fadeIn(300);
		});
		
		//jQuery(window).bind("tb_unload", function() {
		//	reloadPage();
		//});
		    
	  jQuery(document).on("mouseenter", ".maxgalleria-meta .media table td", function () {						
      jQuery(this).find(".actions").css("visibility", "visible");
      jQuery(this).siblings().find(".actions").css("visibility", "visible");
    });

	  jQuery(document).on("mouseleave", ".maxgalleria-meta .media table td", function () {						
      jQuery(this).find(".actions").css("visibility", "hidden");
      jQuery(this).siblings().find(".actions").css("visibility", "hidden");
    });
    		
    jQuery(document).on("click", "#select-all", function() {
			 jQuery("input[type='checkbox']").attr("checked", jQuery("#select-all").is(":checked")); 
		});
		
    jQuery(document).on("click", "#bulk-action-apply", function(e) {
      e.stopImmediatePropagation();
			var bulk_action = jQuery("#bulk-action-select").val();
			
			if (bulk_action == "edit") {
				var form_data_array = jQuery("#post").serializeArray();

				var video_ids = "";
				for (i = 0; i < form_data_array.length; i++) {
					if (form_data_array[i].name == "media-id[]") {
						video_ids += form_data_array[i].value + ",";
					}
				}

				if (video_ids != "") {
          
          var nonce_value = jQuery("#<?php echo esc_attr($video_gallery->nonce_video_edit['name']) ?>").val();

          jQuery("#bulk-ajaxloader").show();   

          jQuery.ajax({
            type: "POST",
            async: true,
            data: { action: "mg_display_bulk_video", video_ids: video_ids, <?php echo esc_attr($video_gallery->nonce_video_edit['name']) ?>: nonce_value },
            url: "<?php echo admin_url('admin-ajax.php') ?>",
            dataType: "html",
            success: function (data) {
              jQuery("#bulk-ajaxloader").hide();          
              jQuery(".image-edit-bulk").html(data);
            },
            error: function (err)
              { alert(err.responseText);}
          });
    
                    
          
          jQuery('#bulk-edit-popup').fadeIn(300);
          
				}
			}
			else {
				var form_data = jQuery("#post").serialize();
				var data_action = "";
				
				if (bulk_action == "exclude") { data_action = form_data + "&action=exclude_bulk_videos_from_gallery"; }
				if (bulk_action == "include") { data_action = form_data + "&action=include_bulk_videos_in_gallery"; }
				if (bulk_action == "remove") { data_action = form_data + "&action=remove_bulk_videos_from_gallery"; }
				
				if (data_action != "") {
					jQuery.ajax({
						type: "POST",
						url: "<?php echo admin_url('admin-ajax.php') ?>",
						data: data_action,
						success: function(message) {
							if (message != "") {
								alert(message);
								reloadPage();
							}
						}
					});
				}
			}
			
			return false;
		});
		
		jQuery("#list-view").on("click", function(e) {
			e.preventDefault(); 
			jQuery("#rows-view").removeClass("active");
			jQuery("#grid-view").removeClass("active");
			jQuery(this).addClass("active");
			jQuery("#gallery-media_wrapper").removeClass("rows videos");
			jQuery("#gallery-media_wrapper").removeClass("grid");
			jQuery("#gallery-media_wrapper").addClass("list");
			jQuery(".maxgalleria-meta .bulk-actions").show();
		});

		jQuery("#rows-view").on("click", function(e) {
			e.preventDefault(); 
			jQuery("#list-view").removeClass("active");
			jQuery("#grid-view").removeClass("active");
			jQuery(this).addClass("active");
			jQuery("#gallery-media_wrapper").removeClass("list");
			jQuery("#gallery-media_wrapper").removeClass("grid");
			jQuery("#gallery-media_wrapper").addClass("rows videos");
			jQuery(".maxgalleria-meta .bulk-actions").show();
		});

		jQuery("#grid-view").on("click", function(e) {
			e.preventDefault(); 
			jQuery("#list-view").removeClass("active");
			jQuery("#rows-view").removeClass("active");
			jQuery(this).addClass("active");
			jQuery("#gallery-media_wrapper").removeClass("list");
			jQuery("#gallery-media_wrapper").removeClass("rows videos");
			jQuery("#gallery-media_wrapper").addClass("grid");
			jQuery(".maxgalleria-meta .bulk-actions").hide();
		});
             
    jQuery('#video-cancel-button').on('click', function () {
      jQuery('#edit-video-popup').fadeOut(300);
    });
    
    jQuery('#video-save-button').on('click', function () {
      var nonce_value = jQuery("#<?php echo esc_attr($video_gallery->nonce_video_edit['name']) ?>").val();

      jQuery('#edit-video-popup').fadeOut(300);
      
      var video_id = jQuery('#meta-attachment-id').val();
      var post_title = jQuery('#video-edit-title').val();
      var post_alt = jQuery('#video-edit-alt').val();
      var caption = jQuery('#video-edit-caption').val();
      
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mg_save_video_info",  video_id: video_id, post_title: post_title, post_alt: post_alt, caption: caption, <?php echo esc_attr($video_gallery->nonce_video_edit['name']) ?>: nonce_value },
        url: "<?php echo admin_url('admin-ajax.php') ?>",
        dataType: "html",
        success: function (data) {
          window.location.reload(true);
        },
        error: function (err)
          { alert(err.responseText);}
      });
        
    });
    
    jQuery(document).on("click", "#bulk-save-button", function () {
    
      var nonce_value = jQuery("#<?php echo esc_attr($video_gallery->nonce_video_edit_bulk['name']) ?>").val();
   
      var form_data = jQuery("#video-bulk-edit-form").serialize();
      
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mg_save_bulk_video", form_data: form_data, <?php echo esc_attr($video_gallery->nonce_video_edit_bulk['name']) ?>: nonce_value },
        url: "<?php echo admin_url('admin-ajax.php') ?>",
        dataType: "html",
        success: function (data) {
          window.location.reload(true);
        },
        error: function (err)
          { alert(err.responseText);}
      });
      
      jQuery('#bulk-edit-popup').fadeOut(300);  
    });    
    
    jQuery('#cancel-button').on('click', function () {
      jQuery('#edit-image-popup').fadeOut(300);
    });
        
    jQuery(document).on("click", "#bulk-cancel-button", function () {
      jQuery('#bulk-edit-popup').fadeOut(300);
    });
    
    jQuery('.close-popup').on('click', function () {
      jQuery('#bulk-edit-popup').fadeOut(300);
    });    
        
    jQuery('.video-add-cancel-button').on('click', function () {
      jQuery('#video-add-popup').fadeOut(300);
    });    
    
    jQuery(".maxgalleria-meta.video-add .actions .loading").css("display", "none");

    jQuery(document).on("click", "#save-button", function() {
      jQuery(".maxgalleria-meta.video-add .actions .loading").css("display", "inline-block");
			var post_id = <?php echo esc_attr($post->ID) ?>;
      console.log('post_id', post_id);
      var video_urls = jQuery('#video-urls').val();
		  var nonce_value = jQuery("#<?php echo esc_attr($video_gallery->nonce_video_add['name']) ?>").val();
      
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mg_add_videos",  video_urls: video_urls, post_id: post_id, <?php echo esc_attr($video_gallery->nonce_video_add['name']) ?>: nonce_value },
        url: "<?php echo admin_url('admin-ajax.php') ?>",
        dataType: "html",
        success: function (data) {
          jQuery(".maxgalleria-meta.video-add .actions .loading").css("display", "none");
          jQuery('#video-add-popup').fadeOut(300);
          reloadPage();
        },
        error: function (err)
          { alert(err.responseText);}
      });
      
      
    });

    jQuery(document).on("click", "#cancel-button", function() {
      reloadPage();
    });
    
    
  });
		
	function editVideo(video_id) {
    
		var nonce_value = jQuery("#<?php echo esc_attr($video_gallery->nonce_video_edit['name']) ?>").val();
    
    jQuery("#ajaxloader").show();   
    jQuery('#video-edit-title').val('');
    jQuery('#video-edit-alt').val('');
    jQuery('#video-edit-caption').val('');
    jQuery('#meta-thumbnail-image').html('');  
    
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mg_get_video_info",  video_id: video_id, <?php echo esc_attr($video_gallery->nonce_video_edit['name']) ?>: nonce_value },
		  url: "<?php echo admin_url('admin-ajax.php') ?>",
      dataType: "json",
      success: function (data) {
        jQuery("#ajaxloader").hide();          
        jQuery('#meta-attachment-id').val(video_id);
        jQuery('#video-edit-title').val(data.post_title);
        jQuery('#video-edit-alt').val(data.post_alt);
        jQuery('#video-edit-caption').val(data.caption);
        jQuery('#meta-thumbnail-image').html(data.image_html);        
      },
      error: function (err)
        { alert(err.responseText);}
    });
        
    
    jQuery('#edit-video-popup').fadeIn(300);
    
	}
	
	function excludeVideo(video_id) {
		var result = confirm("<?php esc_html_e('Are you sure you want to exclude this video from the gallery?', 'maxgalleria') ?>");
		if (result == true) {
			var nonce_value = jQuery("#<?php echo esc_attr($video_gallery->nonce_video_exclude_single['name']) ?>").val();
			
			jQuery.ajax({
				type: "POST",
				url: "<?php echo admin_url('admin-ajax.php') ?>",
				data: {
					action: 'exclude_single_video_from_gallery',
					id: video_id,
					<?php echo esc_attr($video_gallery->nonce_video_exclude_single['name']) ?>: nonce_value
				},
				success: function(message) {
					if (message != "") {
						alert(message);
						reloadPage();
					}
				}
			});
			
			return false;
		}
	}
	
	function includeVideo(video_id) {
		var result = confirm("<?php esc_html_e('Are you sure you want to include this video in the gallery?', 'maxgalleria') ?>");
		if (result == true) {
			var nonce_value = jQuery("#<?php echo esc_attr($video_gallery->nonce_video_include_single['name']) ?>").val();
			
			jQuery.ajax({
				type: "POST",
				url: "<?php echo admin_url('admin-ajax.php') ?>",
				data: {
					action: 'include_single_video_in_gallery',
					id: video_id,
					<?php echo esc_attr($video_gallery->nonce_video_include_single['name']) ?>: nonce_value
				},
				success: function(message) {
					if (message != "") {
						alert(message);
						reloadPage();
					}
				}
			});
			
			return false;
		}
	}
	
	function removeVideo(video_id) {
		var result = confirm("<?php esc_html_e('Are you sure you want to remove this video from the gallery?', 'maxgalleria') ?>");
		if (result == true) {
			var nonce_value = jQuery("#<?php echo esc_attr($video_gallery->nonce_video_remove_single['name']) ?>").val();
			
			jQuery.ajax({
				type: "POST",
				url: "<?php echo admin_url('admin-ajax.php') ?>",
				data: {
					action: 'remove_single_video_from_gallery',
					id: video_id,
					<?php echo esc_attr($video_gallery->nonce_video_remove_single['name']) ?>: nonce_value
				},
				success: function(message) {
					if (message != "") {
						alert(message);
						reloadPage();
					}
				}
			});
			
			return false;
		}
	}
	
	function reorderVideos() {
		
		var form_data = jQuery("#post").serialize();
		
		jQuery.ajax({
			type: "POST",
			url: "<?php echo admin_url('admin-ajax.php') ?>",
			data: form_data + "&action=reorder_videos"
		});
		
		return false;
	}
	
	function showAddingVideosNote() {
		jQuery(".maxgalleria-meta .adding-videos-note").show();
	}
	
	function reloadPage() {
    window.location.reload(true);
}
</script>
<?php 
require_once MAXGALLERIA_PLUGIN_DIR . '/addons/media-sources/youtube/youtube-options.php';

$options = new MaxGalleriaYoutubeOptions();
if($options->get_developer_api_key_default() === '') {
  $diabled = "disabled";  
  echo wp_kses_post('<div class="maxgalleria-meta label-warning center-text">' . esc_html__('In order to access Youtube videos you are now required to obtain a Google Developer API Key. ', 'maxgalleria') . 
       '<a href="http://maxgalleria.com/youtube-api-key/" target="_blank">' . esc_html__('Click here for more details', 'maxgalleria') . '.</a> ' .          
        esc_html__('If you have Google Developer API Key ', 'maxgalleria') .
       '<a href="' . esc_url(home_url() .'/wp-admin/edit.php?post_type=maxgallery&page=maxgalleria-settings&addon=maxgalleria-youtube') . '">' . esc_html__('click here', 'maxgalleria') . '</a>' .
         esc_html__(' to enter it into Youtube Settings.', 'maxgalleria') .
          '</div><br>');  
}
else
  $diabled = "";

?>
<div class="add-media">
  <a class="mxg-btn" id="gallery_media_select_button" name="gallery_media_select_button" <?php echo esc_attr($diabled); ?> ><i class="fa fa-plus-circle"></i> <?php esc_html_e('Add Videos', 'maxgalleria') ?></a>	
</div>
<?php if (count($attachments) > 0) { ?>
	<div class="bulk-actions">
		<select name="bulk-action-select" id="bulk-action-select">
			<option value=""><?php esc_html_e('Bulk Actions', 'maxgalleria') ?></option>
			<option value="edit"><?php esc_html_e('Edit', 'maxgalleria') ?></option>
			<option value="exclude"><?php esc_html_e('Exclude', 'maxgalleria') ?></option>
			<option value="include"><?php esc_html_e('Include', 'maxgalleria') ?></option>
			<option value="remove"><?php esc_html_e('Remove', 'maxgalleria') ?></option>
		</select>
		<input type="button" id="bulk-action-apply" class="button" value="<?php esc_html_e('Apply', 'maxgalleria') ?>" />
	</div>
	<ul class="views">
		<li><a id="list-view" class="active" title="<?php esc_html_e('List', 'maxgalleria') ?>"><i class="fa fa-th-list"></i></a></li>
		<li><a id="rows-view" title="<?php esc_html_e('Rows', 'maxgalleria') ?>"><i class="fa fa-bars"></i></a></li>
		<li><a id="grid-view" title="<?php esc_html_e('Grid', 'maxgalleria') ?>"><i class="fa fa-th"></i></a></li>
	</ul>
<?php } ?>
<div class="clear"></div>

<div class="media">
	<div class="adding-videos-note alert alert-info">
		<div class="gif">
			<img src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/loading-small.gif') ?>" width="16" height="16" alt="" />
		</div>
		<div class="text">
			<h4><?php esc_html_e('Adding videos, one moment...', 'maxgalleria') ?></h4>
		</div>
		<div class="clear"></div>
	</div>
	
	<?php if (count($attachments) < 1) { ?>
		<h4><?php esc_html_e('No videos have been added to this gallery.', 'maxgalleria') ?></h4>
	<?php } else { ?>
    
		<h4><?php esc_html_e('To change the order of a video, place the mouse over the first column in the gallery table, the Reorder column, hold down the mouse button and drag the row to a new position in the gallery and release the mouse button.', 'maxgalleria') ?></h4>
    <div id="gallery-media_wrapper" class="list">
    <table id="gallery-media" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th class="ms-order"><?php esc_html_e('Reorder', 'maxgalleria') ?></th>
					<th class="checkbox"><input type="checkbox" name="select-all" id="select-all" /></th>
					<th class="thumb image">&nbsp;</th>
					<th>&nbsp;</th>
					<th class="reorder"></th>
					<!--<th class="reorder">< ?php esc_html_e('Reorder', 'maxgalleria') ?></th>-->
				</tr>
			</thead>
			<tbody>
			<?php foreach ($attachments as $attachment) { ?>
				<?php $is_excluded = get_post_meta($attachment->ID, 'maxgallery_attachment_video_exclude', true) ?>
				
				<tr id="<?php echo esc_attr($attachment->ID) ?>" draggable="true" ondragstart="start()" ondragover="dragover()" ondragend="dragend()">          
					<td class="ms-order"><?php echo esc_attr($attachment->menu_order) ?></td>
					<td class="checkbox">
						<input type="checkbox" name="media-id[]" id="media-id-<?php echo esc_attr($attachment->ID) ?>" value="<?php echo esc_attr($attachment->ID) ?>" />
						<input type="hidden" name="media-order[]" id="media-order-<?php echo esc_attr($attachment->ID) ?>" value="<?php echo esc_attr($attachment->menu_order) ?>" class="media-order-input" />
						<input type="hidden" name="media-order-id[]" id="media-order-id-<?php echo esc_attr($attachment->ID) ?>" value="<?php echo esc_attr($attachment->ID) ?>" />
					</td>
					<td class="thumb video">    
            <?php 
              $href = esc_url(get_post_meta($attachment->ID, 'maxgallery_attachment_video_url', true)); 
              // reformat youtube urls
              $id_pos = strpos($href, '?v=');
              if($id_pos !== false) {
                $href = "https://youtu.be/" . substr($href, $id_pos + 3);
              }              
            ?>
            <a class="lightbox" href="<?php echo esc_url($href) ?>">
							<?php 
							  $meta_image = wp_get_attachment_image($attachment->ID, MAXGALLERIA_META_VIDEO_THUMB_SMALL);
								if(class_exists("MaxGalleriaMediaLibProS3")) {
									global $maxgalleria_media_library_pro_s3;
									$s3_url = $maxgalleria_media_library_pro_s3->form_s3_upload_url();
								  $meta_image = str_replace($maxgalleria_media_library_pro_s3->uploadsurl, $s3_url, $meta_image);
								}
							?>							
							<?php if ($is_excluded == true) { ?>
								<div class="exclude">
									<?php echo wp_kses_post($meta_image) ?>
								</div>
							<?php } else { ?>							
								<?php echo wp_kses_post($meta_image) ?>
							<?php } ?>
						</a>
						
						<?php
							//$video_url = str_replace('https://', 'http://', get_post_meta($attachment->ID, 'maxgallery_attachment_video_url', true));
              $video_url = esc_url(get_post_meta($attachment->ID, 'maxgallery_attachment_video_url', true));
							$enable_related_videos = get_post_meta($attachment->ID, 'maxgallery_attachment_video_enable_related_videos', true);
							$enable_hd_playback = get_post_meta($attachment->ID, 'maxgallery_attachment_video_enable_hd_playback', true);
							
							// Initialize the embed code and then pass it to the filter to be populated
							$embed_code = '';
              // this filter uses the get_video_embed_code() function in addons/media-sources/youtube.youtube.php
							$embed_code = apply_filters(MAXGALLERIA_FILTER_VIDEO_EMBED_CODE, $embed_code, $video_url, $enable_related_videos, $enable_hd_playback);
							
							echo '<div id="video-' . esc_attr($attachment->ID) . '" style="display: none;">';
							echo '	<div align="center">';
							echo wp_kses_post($embed_code);
							echo '	</div>';
							echo '</div>';
						?>
					</td>
					<td class="text">
						<div class="details">
							<div class="detail-label"><?php esc_html_e('Title', 'maxgalleria') ?>:</div>
							<div class="detail-value title-value"><?php echo esc_html($attachment->post_title) ?></div>
							<div class="clear"></div>
							
							<div class="detail-label"><?php esc_html_e('Alt Text', 'maxgalleria') ?>:</div>
							<div class="detail-value"><?php echo esc_html(get_post_meta($attachment->ID, '_wp_attachment_image_alt', true)) ?></div>
							<div class="clear"></div>
							
							<div class="detail-label"><?php esc_html_e('Caption', 'maxgalleria') ?>:</div>
							<div class="detail-value"><?php echo wp_kses_post($attachment->post_excerpt) ?></div>
							<div class="clear"></div>
							
							<div class="detail-label"><?php esc_html_e('Meta', 'maxgalleria') ?>:</div>
							<div class="detail-value">
								<?php echo esc_html($maxgalleria->common->format_seconds_to_time(get_post_meta($attachment->ID, 'maxgallery_attachment_video_seconds', true))) ?> |
								<?php echo esc_html(date(get_option('date_format'), strtotime($attachment->post_date))) ?>
							</div>
							<div class="clear"></div>
							
							<div class="detail-label"><?php esc_html_e('URL', 'maxgalleria') ?>:</div>
							<div class="detail-value">
								<a href="<?php echo esc_url(get_post_meta($attachment->ID, 'maxgallery_attachment_video_url', true)) ?>" target="_blank">
									<?php echo get_post_meta($attachment->ID, 'maxgallery_attachment_video_url', true) ?>
								</a>
							</div>
							<div class="clear"></div>
						</div>
					</td>
					<td class="reorder">
						<div class="mxg-actions">
              <a class="mg-meta-edit" title="<?php esc_html_e('Edit', 'maxgalleria') ?>" onclick="javascript:editVideo(<?php echo esc_attr($attachment->ID) ?>); return false;"><i class="fa fa-fw fa-pencil"></i></a>
              <a title="<?php esc_html_e('Remove', 'maxgalleria') ?>" onclick="javascript:removeVideo(<?php echo esc_attr($attachment->ID) ?>); return false;"><i class="fa fa-fw fa-close"></i></a>
							<?php if ($is_excluded) { ?>
              <a title="<?php esc_html_e('Include', 'maxgalleria') ?>" onclick="javascript:includeVideo(<?php echo esc_attr($attachment->ID) ?>); return false;"><i class="fa fa-fw fa-eye"></i></a>
							<?php } else { ?>
              <a title="<?php esc_html_e('Exclude', 'maxgalleria') ?>" onclick="javascript:excludeVideo(<?php echo esc_attr($attachment->ID) ?>); return false;"><i class="fa fa-fw fa-eye-slash"></i></a>
							<?php } ?>
            </div>

					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
    </div>
    <div id="edit-video-popup">
      <div class="popup-content">
        <h2>Edit Video</h2>

        <div id="alwrap">
          <div style="display:none" id="ajaxloader"></div>
        </div>
        
        <div class="maxgalleria-meta video-edit">	
          <form id="video-edit-form">
            <input type="hidden" id="meta-attachment-id" value="">
            <table cellpadding="0" cellspacing="0">
              <tr>
                <td>
                  <div class="fields">
                    <div class="field">
                      <div class="field-label">
                        <?php esc_html_e('Title', 'maxgalleria') ?>
                      </div>
                      <div class="field-value">
                        <input type="text" id="video-edit-title" name="video-edit-title" value="" />
                      </div>
                    </div>

                    <div class="field">
                      <div class="field-label">
                        <?php esc_html_e('Alternate Text', 'maxgalleria') ?>
                      </div>
                      <div class="field-value">
                        <input type="text" id="video-edit-alt" name="video-edit-alt" value="" />
                      </div>
                    </div>

                    <div class="field">
                      <div class="field-label">
                        <?php esc_html_e('Caption', 'maxgalleria') ?>
                      </div>
                      <div class="field-value">
                        <textarea id="video-edit-caption" name="video-edit-caption"></textarea>
                      </div>
                    </div>

                  </div>
                </td>
                <td>
                  <div id="meta-thumbnail-image" class="thumb">                    
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <a class="btn btn-primary" id="video-save-button"><?php esc_html_e('Save Changes', 'maxgalleria') ?></a>
                <input type="button" class="btn" id="video-cancel-button" value="<?php esc_html_e('Cancel', 'maxgalleria') ?>" />
                </td>
              </tr>
            </table>

            <?php wp_nonce_field($video_gallery->nonce_video_edit['action'], $video_gallery->nonce_video_edit['name']) ?>
          </form>
        </div>
      </div>
    </div>
        
    <div id="bulk-edit-popup">
      <div class="bulk-popup-content">
        <h2>Bulk Edit Videos</h2>

        <div id="alwrap">
          <div style="display:none" id="bulk-ajaxloader"></div>
        </div>

        <!--form begins here-->
        <form id="video-bulk-edit-form" method="post">
          <div class="maxgalleria-meta image-edit-bulk">	
          </div>
          <a class="close-popup" title="<?php esc_html_e('Close without saving', 'maxgalleria') ?>">x</a>            
        </form>
        <!--form ends here-->
      </div>
    </div>  
        
        				
		<?php wp_nonce_field($video_gallery->nonce_video_exclude_single['action'], $video_gallery->nonce_video_exclude_single['name']) ?>
		<?php wp_nonce_field($video_gallery->nonce_video_exclude_bulk['action'], $video_gallery->nonce_video_exclude_bulk['name']) ?>
		<?php wp_nonce_field($video_gallery->nonce_video_include_single['action'], $video_gallery->nonce_video_include_single['name']) ?>
		<?php wp_nonce_field($video_gallery->nonce_video_include_bulk['action'], $video_gallery->nonce_video_include_bulk['name']) ?>
		<?php wp_nonce_field($video_gallery->nonce_video_remove_single['action'], $video_gallery->nonce_video_remove_single['name']) ?>
		<?php wp_nonce_field($video_gallery->nonce_video_remove_bulk['action'], $video_gallery->nonce_video_remove_bulk['name']) ?>
		<?php wp_nonce_field($video_gallery->nonce_video_reorder['action'], $video_gallery->nonce_video_reorder['name']) ?>
	<?php } ?>
    
  <div id="video-add-popup">
    <div class="video-add-popup-content">      
      <div class="maxgalleria-meta video-add">

        <?php if ($video_addons_count < 1) { ?>
          <p><?php esc_html_e('You do not have any video addons installed.', 'maxgalleria') ?></p>
          <p><?php printf(esc_html__('You can get video addons from the %sMaxGalleria website%s.', 'maxgalleria'), '<a href="http://maxgalleria.com/shop/category/addons/" target="_blank">', '</a>') ?></p>
          <div class="actions">
            <div class="cancel">
              <input type="button" class="btn video-add-cancel-button" value="<?php esc_html_e('Close', 'maxgalleria') ?>" />
            </div>
          </div>
        <?php } else { ?>
          <form id="video-add-form" method="post">
            <p><?php esc_html_e('You can add as many videos to this gallery as you like. Simply enter the page URL of each video (not the embedded URL) in the box, and data about each video will be retrieved automatically.', 'maxgalleria') ?></p>
            <p><?php esc_html_e('Video URLs from the following sites are currently supported:', 'maxgalleria') ?></p>

            <ul class="addons">
              <?php foreach ($video_addons as $name => $key) { ?>
                <?php if ($name != '') { ?>
                  <li><?php echo esc_html($name) ?></li>
                <?php } ?>
              <?php } ?>
            </ul>

            <div class="fields">
              <div class="field">
                <div class="field-label">
                  <?php esc_html_e('Video URLs', 'maxgalleria') ?> <span><?php esc_html_e('Multiple URLs accepted, one per line', 'maxgalleria') ?></span>
                </div>
                <div class="field-value">
                  <textarea id="video-urls" name="video-urls"></textarea>
                </div>
              </div>
            </div>

            <div class="actions">
              <div class="save">
                <input type="button" class="btn btn-primary" id="save-button" value="<?php esc_html_e('Add to Gallery', 'maxgalleria') ?>" />
              </div>
              <div class="cancel">
                <input type="button" class="btn video-add-cancel-button" value="<?php esc_html_e('Cancel', 'maxgalleria') ?>" />
              </div>
              <div class="loading">
                <div class="gif">
                  <img src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/loading-small.gif') ?>" width="16" height="16" alt="" />
                </div>
                <div class="text">
                  <?php esc_html_e('Adding media to gallery...', 'maxgalleria') ?>
                </div>
                <div class="clear"></div>
              </div>
            </div>

            <?php wp_nonce_field($video_gallery->nonce_video_add['action'], $video_gallery->nonce_video_add['name']) ?>
          </form>
        <?php } ?>

      </div>
    </div>
  </div>
    
</div>