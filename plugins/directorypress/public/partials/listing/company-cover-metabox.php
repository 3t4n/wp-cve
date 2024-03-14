<?php global $DIRECTORYPRESS_ADIMN_SETTINGS; ?>
<?php if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_business_cover']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_business_cover']): ?>
	<script>
		var images_allowed = 1;

		(function($) {
			"use strict";

			window.directorypress_image_attachment_tpl_cover_image = function(attachment_id, uploaded_file, title) {
				
				var image_attachment_tpl_cover_image = '<div class="directorypress-attached-item cover_image">' +
					'<input type="hidden" name="attached_image_id" value="'+attachment_id+'" />' +
					'<img src="'+uploaded_file+'" alt="" width="126" height="100" />' +
					'<div class="thumb-links clearfix">' +
						'<div class="directorypress-attached-item-delete cover_image dicode-material-icons dicode-material-icons-trash-can-outline" title="<?php esc_attr_e("remove", "DIRECTORYPRESS"); ?>"></div>' +
					'</div>' +
					'</div>';

				return image_attachment_tpl_cover_image;
			};

			window.directorypress_check_images_attachments_number_cover_image = function() {
				if (images_allowed > $("#directorypress-images-upload-wrapper.cover_image .directorypress-attached-item.cover_image").length) {
					<?php if (is_admin()): ?>
					$("#directorypress-admin-upload-functions.cover_image").show();
					<?php else: ?>
					$(".directorypress-upload-item.cover_image").show();
					<?php endif; ?>
				} else {
					<?php if (is_admin()): ?>
					$("#directorypress-admin-upload-functions.cover_image").hide();
					<?php else: ?>
					$(".directorypress-upload-item.cover_image").hide();
					<?php endif; ?>
				}
			}

			$(function() {
				directorypress_check_images_attachments_number_cover_image();

				$("#directorypress-attached-images-wrapper.cover_image").on("click", ".directorypress-attached-item-delete.cover_image", function() {
					$(this).parents(".directorypress-attached-item.cover_image").remove();
		
					directorypress_check_images_attachments_number_cover_image();
				});

				<?php if (!is_admin()): ?>
				$(document).on("click", ".directorypress-upload-item-button.cover_image", function(e){
					e.preventDefault();
				
					$(this).parent().find("input").click();
				});

				$('.directorypress-upload-item.cover_image').fileupload({
					sequentialUploads: true,
					dataType: 'json',
					url: '<?php echo admin_url('admin-ajax.php?action=directorypress_upload_image&post_id='. esc_attr($listing->post->ID) .'&_wpnonce='.wp_create_nonce('upload_images')); ?>',
					dropZone: $('.directorypress-drop-attached-item.cover_image'),
					add: function (e, data) {
						const bytes = directorypress_js_instance.max_attchment_size * 1024;
						if(data.files[0].size > bytes){
							alert(data.files[0].name+' '+directorypress_js_instance.max_attchment_size_error +' '+directorypress_js_instance.max_attchment_size +' '+directorypress_js_instance.max_attchment_size_unit);
						}else{
							var jqXHR = data.submit();
						}
					},
					send: function (e, data) {
						directorypress_add_iloader_on_element($(this).find(".directorypress-drop-attached-item.cover_image"));
					},
					done: function(e, data) {
						var result = data.result;
						if (result.uploaded_file) {
							$(this).before(directorypress_image_attachment_tpl_cover_image(result.attachment_id, result.uploaded_file, data.files[0].name));
							//directorypress_custom_input_controls();
						} else {
							$(this).find(".directorypress-drop-attached-item.cover_image").append("<p>"+result.error_msg+"</p>");
						}
						$(this).find(".directorypress-drop-zone.cover_image").show();
						directorypress_delete_iloader_from_element($(this).find(".directorypress-drop-attached-item.cover_image"));

						directorypress_check_images_attachments_number_cover_image();
						
						if ($('.directorypress-attached-item.cover_image').length != 0) {
							$('#directorypress-attached-images-wrapper.cover_image').removeClass('full');
						}else{
							$('#directorypress-attached-images-wrapper.cover_image').addClass('full');
						}
					}
				});
				<?php endif; ?>
			});
		})(jQuery);
	</script>

	<div id="directorypress-images-upload-wrapper" class="cover_image">
		<p class="directorypress-submit-field-title"><?php _e('Cover Image', 'DIRECTORYPRESS'); ?></p>
		<div id="directorypress-attached-images-wrapper" class="cover_image full clearfix">
			<?php 
			$attachment_id = get_post_meta($listing->post->ID, '_attached_image_cover', true);
			if($attachment_id):
				$image_src_array = wp_get_attachment_image_src($attachment_id, 'full');
				$image_src  = $image_src_array[0]; 
				$param = array(
					'width' => 126,
					'height' => 100,
					'crop' => true
				);
			?>
				<div class="directorypress-attached-item cover_image">
					<input type="hidden" name="attached_image_id" value="<?php echo esc_attr($attachment_id); ?>" />
					<img src="<?php echo bfi_thumb($image_src, $param); ?>" width="126" height="100" alt="<?php echo esc_attr_e('Cover Image', 'DIRECTORYPRESS'); ?>" />
					<div class="thumb-links clearfix">
						<div class="directorypress-attached-item-delete cover_image dicode-material-icons dicode-material-icons-trash-can-outline" title="<?php _e("delete", "DIRECTORYPRESS"); ?>"></div>
					</div>
				</div>
			<?php endif; ?>
			<?php if (!is_admin()): ?>
			<div class="directorypress-upload-item cover_image">
				<div class="directorypress-drop-attached-item cover_image">
					<div class="directorypress-drop-zone cover_image">
						<div class="dropzone-content">
							<span class="drophere"><?php _e("Drop Your Image Here", "DIRECTORYPRESS"); ?></span>
							<button class="directorypress-upload-item-button cover_image btn btn-primary"><?php _e("Browse", "DIRECTORYPRESS"); ?></button>
							<input type="file" name="browse_file_cover_image" />
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
					$('#directorypress-admin-upload-image_cover_image').click(function(event) {
						event.preventDefault();
				
						var frame = wp.media({
							title : '',
							multiple : false,
							library : { type : 'image'},
							button : { text : '<?php echo esc_js(__('Insert', 'DIRECTORYPRESS')); ?>'},
						});
						frame.on('select', function() {
							var selection = frame.state().get('selection');
							selection.each(function(attachment) {
								attachment = attachment.toJSON();
								if (attachment.type == 'image') {
									if (images_allowed > $("#directorypress-attached-images-wrapper.cover_image .directorypress-attached-item.cover_image").length) {
										directorypress_ajax_loader_show();

										$.ajax({
											type: "POST",
											url: directorypress_js_instance.ajaxurl,
											dataType: 'json',
											data: {
												'action': 'directorypress_upload_media_image',
												'browse_file_cover_image': attachment.id,
												'post_id': <?php echo esc_attr($listing->post->ID); ?>,
												'_wpnonce': '<?php echo wp_create_nonce('upload_images'); ?>',
											},
											attachment_id: attachment.id,
											attachment_url: attachment.sizes.full.url,
											attachment_title: attachment.title,
											success: function (response_from_the_action_function){
											$("#directorypress-attached-images-wrapper.cover_image").append(directorypress_image_attachment_tpl_cover_image(this.attachment_id, this.attachment_url, this.attachment_title));
											directorypress_check_images_attachments_number_cover_image();
											
											directorypress_ajax_loader_hide();
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
		<div id="directorypress-admin-upload-functions" class="cover_image">
			<div class="directorypress-upload-option cover_image">
				<input
					type="button"
					id="directorypress-admin-upload-image_cover_image"
					class="button button-primary"
					value="<?php esc_attr_e('Browse', 'DIRECTORYPRESS'); ?>" />
			</div>
		</div>
		<?php endif; ?>
		</div>
		<div class="directorypress-clearfix"></div>
	</div>
<?php endif; ?>
