<?php
global $post;
global $maxgalleria;

$image_gallery = $maxgalleria->image_gallery;

if (class_exists('MaxGalleriaWatermark')) {
  global $maxgalleria_watermark;
}

$gallery_id = sanitize_text_field($_GET['post']);
if(!is_numeric($gallery_id))
  $gallery_id = 0;  
$template = get_post_meta( $gallery_id, 'maxgallery_template', true );

$uploads_dir = wp_upload_dir();
$base_uploads_url = $uploads_dir['baseurl'];

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
    
  reorderImages();
}
    
	jQuery(document).ready(function() {
		// To add the image count in the meta box title
		jQuery("#meta-image-gallery h3.hndle span").html("<?php esc_html_e('Gallery', 'maxgalleria') ?> (<?php echo esc_html(count($attachments)) ?> <?php esc_html_e('images', 'maxgalleria') ?>)");
    
		// Lightbox
    jQuery('.lightbox').topbox();

	  jQuery(document).on("mouseenter", ".maxgalleria-meta .media table td", function () {						
      jQuery(this).find(".actions").css("visibility", "visible");
      jQuery(this).siblings().find(".actions").css("visibility", "visible");
    });

	  jQuery(document).on("mouseleave", ".maxgalleria-meta .media table td", function () {						
      jQuery(this).find(".actions").css("visibility", "hidden");
      jQuery(this).siblings().find(".actions").css("visibility", "hidden");
    });
    		
    jQuery(document).on("click", "#select-all", function() {
			 jQuery(".checkbox input[type='checkbox']").attr("checked", jQuery("#select-all").is(":checked")); 
		});
		
    jQuery(document).on("click", "#bulk-action-apply", function() {
			var bulk_action = jQuery("#bulk-action-select").val();
			

			if (bulk_action == "watermark") {
				
	    <?php if (class_exists("MaxGalleriaWatermark")) { ?>
						
				var counter = 0;
				var form_data_array = jQuery("#post").serializeArray();
				var image_ids = "";
				for (i = 0; i < form_data_array.length; i++) {
					if (form_data_array[i].name == "media-id[]") {
						if(counter == 0)
						  image_ids += form_data_array[i].value;
						else
							image_ids += "," + form_data_array[i].value;
						counter++;
					}
				}

				if (image_ids != "") {
					
					var wm_nonce_value = jQuery("#<?php echo esc_attr($maxgalleria_watermark->nonce_watermark_gallery_images['name']) ?>").val();

					jQuery.ajax({
						type: "POST",
						url: ajaxurl,          
						data: {
								action: 'mg_watermark_gallery_images',
								image_ids: image_ids,
								wm_nonce_value: wm_nonce_value
						},
						success: function(result) {
							if (result == "success") {
								reloadPage();
								alert('The images were successful watermarked.');              
							}
							else {
								alert("There was a problem watermarking the images.");
							}
						}                
					});					
				}
				
			<?php }?>

		  } else if (bulk_action == "edit") {
				var form_data_array = jQuery("#post").serializeArray();

				var image_ids = "";
				for (i = 0; i < form_data_array.length; i++) {
					if (form_data_array[i].name == "media-id[]") {
						image_ids += form_data_array[i].value + ",";
					}
				}

				if (image_ids != "") {
          
          var nonce_value = jQuery("#<?php echo esc_attr($image_gallery->nonce_image_edit['name']) ?>").val();

          jQuery("#bulk-ajaxloader").show();   

          jQuery.ajax({
            type: "POST",
            async: true,
            data: { action: "mg_display_bulk_edit", image_ids: image_ids, <?php echo esc_attr($image_gallery->nonce_image_edit['name']) ?>: nonce_value },
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
				
				if (bulk_action == "exclude") { data_action = form_data + "&action=exclude_bulk_images_from_gallery"; }
				if (bulk_action == "include") { data_action = form_data + "&action=include_bulk_images_in_gallery"; }
				if (bulk_action == "remove") { data_action = form_data + "&action=remove_bulk_images_from_gallery"; }
				
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
      console.log('list-view');
			e.preventDefault(); 
			jQuery("#rows-view").removeClass("active");
			jQuery("#grid-view").removeClass("active");
			jQuery(this).addClass("active");
			jQuery("#gallery-media_wrapper").removeClass("rows images");
			jQuery("#gallery-media_wrapper").removeClass("grid");
			jQuery("#gallery-media_wrapper").addClass("list");
			jQuery(".maxgalleria-meta .bulk-actions").show();
		});

		jQuery("#rows-view").on("click", function(e) {
      console.log('rows-view');
			e.preventDefault(); 
			jQuery("#list-view").removeClass("active");
			jQuery("#grid-view").removeClass("active");
			jQuery(this).addClass("active");
			jQuery("#gallery-media_wrapper").removeClass("list");
			jQuery("#gallery-media_wrapper").removeClass("grid");
			jQuery("#gallery-media_wrapper").addClass("rows images");
			jQuery(".maxgalleria-meta .bulk-actions").show();
		});

		jQuery("#grid-view").on("click", function(e) {
      console.log('grid-view');
			e.preventDefault(); 
			jQuery("#list-view").removeClass("active");
			jQuery("#rows-view").removeClass("active");
			jQuery(this).addClass("active");
			jQuery("#gallery-media_wrapper").removeClass("list");
			jQuery("#gallery-media_wrapper").removeClass("rows images");
			jQuery("#gallery-media_wrapper").addClass("grid");
			jQuery(".maxgalleria-meta .bulk-actions").hide();
		});
    
    jQuery('#save-button').on('click', function () {
      var nonce_value = jQuery("#<?php echo esc_attr($image_gallery->nonce_image_edit['name']) ?>").val();

      jQuery('#edit-image-popup').fadeOut(300);
      
      var image_id = jQuery('#meta-attachment-id').val();
      var post_title = jQuery('#image-edit-title').val();
      var post_alt = jQuery('#image-edit-alt').val();
      var caption = jQuery('#image-edit-caption').val();
      var image_link = jQuery('#image-edit-link').val();
      var action_link_text = jQuery('#image-edit-action-text').val(); 
      var template = jQuery('#meta-template').val(); 
      
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mg_save_image_info",  image_id: image_id, post_title: post_title, post_alt: post_alt, caption: caption, image_link: image_link, action_link_text: action_link_text, template: template, <?php echo esc_attr($image_gallery->nonce_image_edit['name']) ?>: nonce_value },
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
    
      var nonce_value = jQuery("#<?php echo esc_attr($image_gallery->nonce_image_edit_bulk['name']) ?>").val();
   
      var template = jQuery('#bulk-meta-template').val(); 
      var form_data = jQuery("#image-bulk-edit-form").serialize();

      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mg_save_bulk_info", form_data: form_data, template: template,  <?php echo esc_attr($image_gallery->nonce_image_edit_bulk['name']) ?>: nonce_value },
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
	
	});
	
	function editImage(image_id) {
  
		var nonce_value = jQuery("#<?php echo esc_attr($image_gallery->nonce_image_edit['name']) ?>").val();
    
    jQuery("#ajaxloader").show();   
    jQuery('#image-edit-title').val('');
    jQuery('#image-edit-alt').val('');
    jQuery('#image-edit-caption').val('');
    jQuery('#image-edit-link').val('');
    jQuery('#image-edit-action-text').val('');        
    jQuery('#meta-thumbnail-image').html('');        
    
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mg_get_image_info",  image_id: image_id, <?php echo esc_attr($image_gallery->nonce_image_edit['name']) ?>: nonce_value },
      url: "<?php echo admin_url('admin-ajax.php') ?>",
      dataType: "json",
      success: function (data) {
        jQuery("#ajaxloader").hide();          
        jQuery('#meta-attachment-id').val(image_id);
        jQuery('#image-edit-title').val(data.post_title);
        jQuery('#image-edit-alt').val(data.post_alt);
        jQuery('#image-edit-caption').val(data.caption);
        jQuery('#image-edit-link').val(data.image_link);
        jQuery('#image-edit-action-text').val(data.action_link_text);        
        jQuery('#meta-thumbnail-image').html(data.image_html);        
      },
      error: function (err)
        { alert(err.responseText);}
    });
    
    
    jQuery('#edit-image-popup').fadeIn(300);
    
	}
  
	function excludeImage(image_id) {
		var result = confirm("<?php esc_html_e('Are you sure you want to exclude this image from the gallery?', 'maxgalleria') ?>");
		if (result == true) {
			var nonce_value = jQuery("#<?php echo esc_attr($image_gallery->nonce_image_exclude_single['name']) ?>").val();
			
			jQuery.ajax({
				type: "POST",
				url: "<?php echo admin_url('admin-ajax.php') ?>",
				data: {
					action: 'exclude_single_image_from_gallery',
					id: image_id,
					<?php echo esc_attr($image_gallery->nonce_image_exclude_single['name']) ?>: nonce_value
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
	
	function includeImage(image_id) {
		var result = confirm("<?php esc_html_e('Are you sure you want to include this image in the gallery?', 'maxgalleria') ?>");
		if (result == true) {
			var nonce_value = jQuery("#<?php echo esc_attr($image_gallery->nonce_image_include_single['name']) ?>").val();
			
			jQuery.ajax({
				type: "POST",
				url: "<?php echo admin_url('admin-ajax.php') ?>",
				data: {
					action: 'include_single_image_in_gallery',
					id: image_id,
					<?php echo esc_attr($image_gallery->nonce_image_include_single['name']) ?>: nonce_value
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
	
	function removeImage(image_id) {
		var result = confirm("<?php esc_html_e('Are you sure you want to remove this image from the gallery?', 'maxgalleria') ?>");
		if (result == true) {
			var nonce_value = jQuery("#<?php echo esc_attr($image_gallery->nonce_image_remove_single['name']) ?>").val();
			
			jQuery.ajax({
				type: "POST",
				url: "<?php echo admin_url('admin-ajax.php') ?>",
				data: {
					action: 'remove_single_image_from_gallery',
					id: image_id,
					<?php echo esc_attr($image_gallery->nonce_image_remove_single['name']) ?>: nonce_value
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
	
	function reorderImages() {
		
		var form_data = jQuery("#post").serialize();
		
		jQuery.ajax({
			type: "POST",
			url: "<?php echo admin_url('admin-ajax.php') ?>",
			data: form_data + "&action=reorder_images"
		});
		
		return false;
	}
	
	function reloadPage() {
    window.location.reload(true);
	}	
</script>

<div class="add-media">
  <a class="mxg-btn maxgalleria-open-media" href="#"><i class="fa fa-plus-circle"></i> <?php esc_html_e('Add Images', 'maxgalleria') ?></a>		
</div>
<?php if (count($attachments) > 0) { ?>
	<div class="bulk-actions">
		<select name="bulk-action-select" id="bulk-action-select">
			<option value=""><?php esc_html_e('Bulk Actions', 'maxgalleria') ?></option>
			<option value="edit"><?php esc_html_e('Edit', 'maxgalleria') ?></option>
			<option value="exclude"><?php esc_html_e('Exclude', 'maxgalleria') ?></option>
			<option value="include"><?php esc_html_e('Include', 'maxgalleria') ?></option>
			<option value="remove"><?php esc_html_e('Remove', 'maxgalleria') ?></option>
		<?php if(class_exists('MaxGalleriaWatermark')) { ?>
			<option value="watermark"><?php esc_html_e('Watermark', 'maxgalleria') ?></option>
		<?php } ?>	
		</select>
		<input type="button" id="bulk-action-apply" class="button" value="<?php esc_html_e('Apply', 'maxgalleria') ?>" />
	</div>
  <?php if (class_exists('MaxGalleriaWatermark')) { ?>
  <div class="add-media">
		<input type="hidden" name="gallery_id" id="gallery_id" value="<?php echo esc_attr($post->ID) ?>" />    
	  <?php wp_nonce_field($maxgalleria_watermark->nonce_watermark_gallery_images['action'], $maxgalleria_watermark->nonce_watermark_gallery_images['name']); ?>        
  </div>  
  <?php } ?>
	<ul class="views">
		<li><a id="list-view" class="active" title="<?php esc_html_e('List', 'maxgalleria') ?>"><i class="fa fa-th-list"></i></a></li>
		<li><a id="rows-view" title="<?php esc_html_e('Rows', 'maxgalleria') ?>"><i class="fa fa-bars"></i></a></li> 
		<li><a id="grid-view" title="<?php esc_html_e('Grid', 'maxgalleria') ?>"><i class="fa fa-th"></i></a></li>
	</ul>
<?php } ?>
<div class="clear"></div>

<div class="media">	
	<?php if($template === 'image-showcase') { ?>
	  <p><?php esc_html_e('For the best user experience you will want your images to be the same height. Use the Crop feature under Images to adjust your images as needed.', 'maxgalleria') ?></p>
	<?php } else if($template === 'masonry') { ?>
		<p><?php esc_html_e('For the best performance, we recommend uploading images that are the actual size to be displayed rather than full size images. It is also helpful if the images vary in height. Large gallery images are automatically cropped. The Masonry addon automatically determines the best layout for your images based on their size.', 'maxgalleria') ?></p>
	<?php } ?>
	<div class="adding-media-library-images-note alert alert-info">
		<div class="gif">
			<img src="<?php echo esc_url(MAXGALLERIA_PLUGIN_URL .'/images/loading-small.gif') ?>" width="16" height="16" alt="" />
		</div>
		<div class="text">
			<h4><?php esc_html_e('Adding images from media library, this might take a few moments, please wait...', 'maxgalleria') ?></h4>
		</div>
		<div class="clear"></div>
	</div>
	
	<?php if (count($attachments) < 1) { ?>
		<h4><?php esc_html_e('No images have been added to this gallery.', 'maxgalleria') ?></h4>
	<?php } else { ?>
		  <h4><?php esc_html_e('To change the order of an image, place the mouse over the first column in the gallery table, the Reorder column, hold down the mouse button and drag the row to a new position in the gallery and release the mouse button.', 'maxgalleria') ?></h4>
      <div id="gallery-media_wrapper" class="list">
      <table id="gallery-media" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th class="ms-order"><?php esc_html_e('Reorder', 'maxgalleria') ?></th>
					<th class="checkbox"><input type="checkbox" name="select-all" id="select-all" /></th>
					<th class="thumb image">&nbsp;</th>
					<th>&nbsp;</th>
					<th class="reorder"></th>
				</tr>
			</thead>
			<tbody>
			<?php $uploads = wp_upload_dir(); ?>
			<?php $new_menu_order = 1 ?>	
			<?php foreach ($attachments as $attachment) { ?>
				<?php $is_excluded = get_post_meta($attachment->ID, 'maxgallery_attachment_image_exclude', true) ?>
				
				<tr id="<?php echo esc_attr($attachment->ID) ?>" draggable="true" ondragstart="start()" ondragover="dragover()" ondragend="dragend()">
					<?php 
					if($attachment->menu_order === -1 || $attachment->menu_order === 0 )						
					  $menu_order = $new_menu_order;
					else
					  $menu_order = $attachment->menu_order;	
					?>
					<td class="ms-order"><?php echo esc_html($menu_order) ?>
          </td>
					<td class="checkbox">
						<input type="checkbox" name="media-id[]" id="media-id-<?php echo esc_attr($attachment->ID) ?>" value="<?php echo esc_attr($attachment->ID) ?>" />
						<input type="hidden" name="media-order[]" id="media-order-<?php echo esc_attr($attachment->ID) ?>" value="<?php echo esc_attr($attachment->menu_order) ?>" class="media-order-input" />
						<input type="hidden" name="media-order-id[]" id="media-order-id-<?php echo esc_attr($attachment->ID) ?>" value="<?php echo esc_attr($attachment->ID) ?>" />
					</td>
					<td class="thumb image">
						<a href="<?php echo esc_url($maxgalleria->mg_get_attachment_url($attachment, $uploads)) ?>" class="lightbox" rel="media">
							<?php 
							  $meta_image = wp_get_attachment_image($attachment->ID, MAXGALLERIA_META_IMAGE_THUMB_SMALL);
							?>
							<?php if ($is_excluded == true) { ?>
								<div class="exclude">
									<?php echo wp_kses_post($meta_image) ?>
								</div>
							<?php } else { ?>
								<?php echo wp_kses_post($meta_image) ?>
							<?php } ?>
						</a>
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
								<?php echo esc_html($image_gallery->get_image_size_display($attachment)) ?> |
								<?php echo esc_html($attachment->post_mime_type) ?> |
								<?php echo esc_html(date(get_option('date_format'), strtotime($attachment->post_date))) ?>
							</div>
							<div class="clear"></div>
							
							<div class="detail-label"><?php esc_html_e('Link', 'maxgalleria') ?>:</div>
							<div class="detail-value">
								<a href="<?php echo esc_url(get_post_meta($attachment->ID, 'maxgallery_attachment_image_link', true)) ?>" target="_blank">
									<?php echo esc_url(get_post_meta($attachment->ID, 'maxgallery_attachment_image_link', true)) ?>
								</a>
							</div>
							<div class="clear"></div>
						</div>
					</td>
					<td class="reorder">
						<?php $crop_url = site_url() . "/wp-admin/post.php?post=" . esc_attr($attachment->ID) . "&action=edit&image-editor"; ?>	
						<div class="mxg-actions">
              <a class="mg-meta-edit" title="<?php esc_html_e('Edit', 'maxgalleria') ?>" onclick="javascript:editImage(<?php echo esc_attr($attachment->ID) ?>); return false;"><i class="fa fa-fw fa-pencil"></i></a>
              <a href="<?php echo esc_url($crop_url); ?>" target="_blank" title="<?php esc_html_e('Crop', 'maxgalleria') ?>" ><i class="fa fa-fw fa-crop"></i></a>
              <a title="<?php esc_html_e('Remove', 'maxgalleria') ?>" onclick="javascript:removeImage(<?php echo esc_attr($attachment->ID) ?>); return false;"><i class="fa fa-fw fa-close"></i></a>
							<?php if ($is_excluded) { ?>
              <a title="<?php esc_html_e('Include', 'maxgalleria') ?>" onclick="javascript:includeImage(<?php echo esc_attr($attachment->ID) ?>); return false;"><i class="fa fa-fw fa-eye"></i></a>
							<?php } else { ?>
              <a title="<?php esc_html_e('Exclude', 'maxgalleria') ?>" onclick="javascript:excludeImage(<?php echo esc_js($attachment->ID) ?>); return false;"><i class="fa fa-fw fa-eye-slash"></i></a>
							<?php } ?>
            </div>

					</td>
				</tr>
				<?php $new_menu_order++; ?>
			<?php } ?>
			</tbody>
		</table>      
    </div>   
  <div id="edit-image-popup">
    <div class="popup-content">
      <h2>Edit Image</h2>
      
      <div id="alwrap">
        <div style="display:none" id="ajaxloader"></div>
      </div>
            
        <div class="maxgalleria-meta image-edit">	
          <form id="testform">
          <input type="hidden" id="meta-attachment-id" value="">
          <input type="hidden" id="meta-template" value="<?php echo esc_html($template) ?>">
            <table cellpadding="0" cellspacing="0">
              <tr>
                <td>
                  <div class="fields">
                    <div class="field">
                      <div class="field-label">
                        <?php esc_html_e('Title', 'maxgalleria') ?>
                      </div>
                      <div class="field-value">
                        <input type="text" id="image-edit-title" name="image-edit-title" value="" />
                      </div>
                    </div>

                    <div class="field">
                      <div class="field-label">
                        <?php esc_html_e('Alternate Text', 'maxgalleria') ?>
                      </div>
                      <div class="field-value">
                        <input type="text" id="image-edit-alt" name="image-edit-alt" value="" />
                      </div>
                    </div>

                    <div class="field">
                      <div class="field-label">
                        <?php esc_html_e('Caption', 'maxgalleria') ?>
                      </div>
                      <div class="field-value">
                        <textarea id="image-edit-caption" name="image-edit-caption"></textarea>
                      </div>
                    </div>

                    <?php if($template === 'material-design') {	?>

                    <div class="field">
                      <div class="field-label">
                        <?php esc_html_e('Action Link Text', 'maxgalleria') ?>
                      </div>
                      <div class="field-value">
                        <input type="text" id="image-edit-action-text" name="image-edit-action-text" value="" />
                      </div>
                    </div>						

                    <?php }	?>

                    <div class="field">
                      <div class="field-label">
                        <?php esc_html_e('Link', 'maxgalleria') ?>
                      </div>
                      <div class="field-value">
                        <input type="text" id="image-edit-link" name="image-edit-link" value="" />
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
                  <a class="btn btn-primary" id="save-button"><?php esc_html_e('Save Changes', 'maxgalleria') ?></a>
                <input type="button" class="btn" id="cancel-button" value="<?php esc_html_e('Cancel', 'maxgalleria') ?>" />
                </td>
              </tr>
            </table>

            <?php wp_nonce_field($image_gallery->nonce_image_edit['action'], $image_gallery->nonce_image_edit['name']) ?>
            
          </form>
        </div>
    </div>
  </div>
      
  <div id="bulk-edit-popup">
    <div class="bulk-popup-content">
      <h2>Bulk Edit Images</h2>
      
      <div id="alwrap">
        <div style="display:none" id="bulk-ajaxloader"></div>
      </div>
            
      <!--form begins here-->
      <input type="hidden" id="bulk-meta-template" value="<?php echo esc_html($template) ?>">
	    <form id="image-bulk-edit-form" method="post">
        <div class="maxgalleria-meta image-edit-bulk">	
        </div>
        <a class="close-popup" title="<?php esc_html_e('Close without saving', 'maxgalleria') ?>">x</a>            
	    </form>
      <!--form ends here-->
    </div>
  </div>  
      

    
    
    
		<?php wp_nonce_field($image_gallery->nonce_image_exclude_single['action'], $image_gallery->nonce_image_exclude_single['name']) ?>
		<?php wp_nonce_field($image_gallery->nonce_image_exclude_bulk['action'], $image_gallery->nonce_image_exclude_bulk['name']) ?>
		<?php wp_nonce_field($image_gallery->nonce_image_include_single['action'], $image_gallery->nonce_image_include_single['name']) ?>
		<?php wp_nonce_field($image_gallery->nonce_image_include_bulk['action'], $image_gallery->nonce_image_include_bulk['name']) ?>
		<?php wp_nonce_field($image_gallery->nonce_image_remove_single['action'], $image_gallery->nonce_image_remove_single['name']) ?>
		<?php wp_nonce_field($image_gallery->nonce_image_remove_bulk['action'], $image_gallery->nonce_image_remove_bulk['name']) ?>
		<?php wp_nonce_field($image_gallery->nonce_image_reorder['action'], $image_gallery->nonce_image_reorder['name']) ?>
		<?php wp_nonce_field($image_gallery->nonce_crop_image['action'], $image_gallery->nonce_crop_image['name']) ?>
	<?php } ?>
</div>