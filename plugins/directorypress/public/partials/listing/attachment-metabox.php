<?php if ($listing->package->images_allowed): ?>
<script>
	var images_allowed_gallary_images = <?php echo esc_attr($listing->package->images_allowed); ?>;

	(function($) {
		"use strict";

		window.directorypress_image_attachment_tpl_gallary_images = function(attachment_id, uploaded_file, title) {
			
			var image_attachment_tpl_gallary_images = '<div class="directorypress-attached-item gallary_images">' +
				'<input class="attachment-id" type="hidden" name="attached_image_id[]" value="'+attachment_id+'" />' +
				'<a href="'+uploaded_file+'" data-lightbox="listing_images" class="directorypress-attached-item-img">' +
				'<img src="'+uploaded_file+'" alt="" />' +
				'</a>' +
				'<div class="thumb-links clearfix">' +
					'<div class="directorypress-attached-item-logo directorypress-radio checkbox">' +
						'<label>' +
							'<input type="radio" name="attached_image_as_logo" value="'+attachment_id+'">' +
							'<span class="radio-check-item"></span>' +
						'</label>' +
					'</div>' +
					'<div class="directorypress-attached-item-delete gallary_images dicode-material-icons dicode-material-icons-trash-can-outline" title="<?php esc_attr_e("remove", "DIRECTORYPRESS"); ?>"></div>' +
				'</div>' +
				'</div>';

			return image_attachment_tpl_gallary_images;
		};

		window.directorypress_check_images_attachments_number_gallary_images = function() {
			if (images_allowed_gallary_images > $("#directorypress-images-upload-wrapper.gallery .directorypress-attached-item.gallary_images").length) {
				<?php if (is_admin()): ?>
				$("#directorypress-admin-upload-functions.gallary_images").show();
				<?php else: ?>
				$(".directorypress-upload-item.gallary_images").show();
				<?php endif; ?>
			} else {
				<?php if (is_admin()): ?>
				$("#directorypress-admin-upload-functions.gallary_images").hide();
				<?php else: ?>
				$(".directorypress-upload-item.gallary_images").hide();
				<?php endif; ?>
			}
		}

		$(function() {
			directorypress_check_images_attachments_number_gallary_images();

			$("#directorypress-attached-images-wrapper.gallery").on("click", ".directorypress-attached-item-delete.gallary_images", function() {
				
				$(this).parents(".directorypress-attached-item.gallary_images").remove();
				
				directorypress_check_images_attachments_number_gallary_images();
			});

			<?php if (!is_admin()): ?>
			$(document).on("click", ".directorypress-upload-item-button.gallary_images", function(e){
				e.preventDefault();
			
				$(this).parent().find("input").click();
			});

			$('.directorypress-upload-item.gallary_images').fileupload({
				sequentialUploads: true,
				dataType: 'json',
				url: '<?php echo admin_url('admin-ajax.php?action=directorypress_upload_image&post_id='. esc_attr($listing->post->ID) .'&_wpnonce='.wp_create_nonce('upload_images')); ?>',
				dropZone: $('.directorypress-drop-attached-item.gallary_images'),
				add: function (e, data) {
					const bytes = directorypress_js_instance.max_attchment_size * 1024;
					//alert(bytes);
					if(data.files[0].size > bytes){
						alert(data.files[0].name+' '+directorypress_js_instance.max_attchment_size_error +' '+directorypress_js_instance.max_attchment_size +' '+directorypress_js_instance.max_attchment_size_unit);
					}else{
						var jqXHR = data.submit();
					}
				},
				send: function (e, data) {
					
					directorypress_add_iloader_on_element($(this).find(".directorypress-drop-attached-item.gallary_images"));
				},
				done: function(e, data) {
					var result = data.result;
					if (result.uploaded_file) {
						//console.log(result);
						$(this).before(directorypress_image_attachment_tpl_gallary_images(result.attachment_id, result.uploaded_file, data.files[0].name));
						//directorypress_custom_input_controls();
					} else {
						$(this).find(".directorypress-drop-attached-item.gallary_images").append("<p>"+result.error_msg+"</p>");
					}
					$(this).find(".directorypress-drop-zone.gallary_images").show();
					directorypress_delete_iloader_from_element($(this).find(".directorypress-drop-attached-item.gallary_images"));
					
					directorypress_check_images_attachments_number_gallary_images();
					
					if ($('.directorypress-attached-item.gallary_images').length != 0) {
						$('#directorypress-images-upload-wrapper.gallary_images').removeClass('full');
					}else{
						$('#directorypress-images-upload-wrapper.gallary_images').addClass('full');
					}
				}
			});
			<?php endif; ?>
		});
		$(function() {
			if ($('.directorypress-attached-item.gallary_images').length != 0) {
				$('#directorypress-images-upload-wrapper.gallary_images').removeClass('full');
			}else{
				//alert('test');
				$('#directorypress-images-upload-wrapper.gallary_images').addClass('full');
			}
		});
	})(jQuery);
</script>
<div class="directorypress-clearfix"></div>
<div id="directorypress-images-upload-wrapper" class="gallary_images">
	<p class="directorypress-submit-field-title"><?php _e('Listing images', 'DIRECTORYPRESS'); ?></p>
	<div class="alert alert-info"><?php echo sprintf(esc_html__('You can upload up to %s Images', 'DIRECTORYPRESS'), esc_attr($listing->package->images_allowed)); ?></div>
	<div id="directorypress-attached-images-wrapper" class="gallery clearfix">
		<?php if(is_admin()){ ?>
			<div class="items">
		<?php } ?>
		<?php foreach ($listing->images AS $attachment_id=>$attachment): ?>
		<?php //$src = wp_get_attachment_image_src($attachment_id, array(250, 250)); ?>
		<?php $src_full = wp_get_attachment_image_src($attachment_id, 'full');
				$image_src_array = wp_get_attachment_image_src($attachment_id, 'full');
				$image_src  = $image_src_array[0]; 
				$param = array(
					'width' => 126,
					'height' => 100,
					'crop' => true
				);
				if(is_admin()){
					$thumbnail_url = admin_url('/post.php?post='.esc_attr($attachment_id).'&action=edit');
				}else{
					$thumbnail_url = $src_full[0];
				}
				
		?>
		
		<div class="directorypress-attached-item gallary_images">
			
			<input class="attachment-id" type="hidden" name="attached_image_id[]" value="<?php echo esc_attr($attachment_id); ?>" />
			<a href="<?php echo esc_url($thumbnail_url); ?>" target="_blank" data-lightbox="listing_images" class="directorypress-attached-item-img"><img src="<?php echo esc_url(bfi_thumb($image_src, $param)); ?>" width="126" height="100" alt="<?php echo esc_attr_e($attachment['post_title']); ?>" /></a>
			<div class="thumb-links clearfix">
				<div class="directorypress-attached-item-logo directorypress-radio checkbox">
					<label title="<?php _e("Set as Thumbnail Image", "DIRECTORYPRESS"); ?>">
						<input type="radio" name="attached_image_as_logo" value="<?php echo esc_attr($attachment_id); ?>" <?php checked($listing->logo_image, $attachment_id); ?>>
						<span class="radio-check-item"></span>
					</label>
				</div>
				<div class="directorypress-attached-item-delete gallary_images dicode-material-icons dicode-material-icons-trash-can-outline" title="<?php _e("delete", "DIRECTORYPRESS"); ?>"></div>
			</div>
		</div>
		<?php endforeach; ?>
		<?php if(is_admin()){ ?>
			</div>
		<?php } ?>
		<?php if (!is_admin()): ?>
		<div class="directorypress-upload-item gallary_images">
			<div class="directorypress-drop-attached-item gallary_images">
				<div class="directorypress-drop-zone gallary_images">
					<div class="dropzone-content">
						<i class="pacz-icon-photo"></i>
						<span class="drophere"><?php _e("Drag and Drop Images Here", "DIRECTORYPRESS"); ?></span>
						<span class="drophere-desc-text"><?php _e("Photos must be JPEG or PNG format with ideal Aspect Ratio 16:9 ", "DIRECTORYPRESS"); ?></span>
						<button class="directorypress-upload-item-button gallary_images btn btn-primary"><?php _e("Browse Files", "DIRECTORYPRESS"); ?></button>
						<input type="file" name="browse_file" multiple />
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<?php if (is_admin() && current_user_can('upload_files')): ?>
	<script>
		(function($) {
			"use strict";
		
			$(function() {
				$('#directorypress-admin-upload-image').click(function(event) {
					event.preventDefault();
			
					var frame = wp.media({
						title : '<?php echo esc_js(sprintf(__('Upload image (%d maximum)', 'DIRECTORYPRESS'), $listing->package->images_allowed)); ?>',
						multiple : true,
						library : { type : 'image'},
						button : { text : '<?php echo esc_js(__('Insert', 'DIRECTORYPRESS')); ?>'},
					});
					frame.on('select', function() {
						var selection = frame.state().get('selection');
						selection.each(function(attachment) {
							attachment = attachment.toJSON();
							if (attachment.type == 'image') {
								if (images_allowed_gallary_images > $("#directorypress-attached-images-wrapper.gallery .directorypress-attached-item.gallary_images").length) {
									$('#directorypress-attached-images-wrapperdirectorypress-upload-option').append(loader);
										//alert(attachment.id);
									$.ajax({
										type: "POST",
										url: directorypress_js_instance.ajaxurl,
										dataType: 'json',
										data: {
											'action': 'directorypress_upload_media_image',
											'attachment_id': attachment.id,
											'post_id': <?php echo esc_attr($listing->post->ID); ?>,
											'_wpnonce': '<?php echo wp_create_nonce('upload_images'); ?>',
										},
										attachment_id: attachment.id,
										attachment_url: attachment.sizes.full.url,
										attachment_title: attachment.title,
										success: function (response_from_the_action_function){
										//alert(response_from_the_action_function);
										$("#directorypress-attached-images-wrapper.gallery .items").append(directorypress_image_attachment_tpl_gallary_images(this.attachment_id, this.attachment_url, this.attachment_title));
										directorypress_check_images_attachments_number_gallary_images();
										
										$('#directorypress-attached-images-wrapper').remove('.dpbackend-loader-wrapper');
										}
									});
								}
							}
						});
					});
					frame.open();
				});
			});
		})(jQuery);
	</script>
	<div id="directorypress-admin-upload-functions">
		<div class="directorypress-upload-option">
			<input
				type="button"
				id="directorypress-admin-upload-image"
				class="button button-primary"
				value="<?php esc_attr_e('Browse', 'DIRECTORYPRESS'); ?>" />
		</div>
	</div>
	<?php endif; ?>
	</div>
	<div class="directorypress-clearfix"></div>
</div>
<?php endif;