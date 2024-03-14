<?php global $DIRECTORYPRESS_ADIMN_SETTINGS; ?>
<?php if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_business_logo']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_business_logo']): ?>
	<script>
		var images_allowed = 1;

		(function($) {
			"use strict";

			window.directorypress_image_attachment_tpl_clogo = function(attachment_id, uploaded_file, title) {
				
				var image_attachment_tpl_clogo = '<div class="directorypress-attached-item clogo">' +
					'<input type="hidden" name="attached_image_id" value="'+attachment_id+'" />' +
					'<img src="'+uploaded_file+'" alt="" width="126" height="100" />' +
					'<div class="thumb-links clearfix">' +
						'<div class="directorypress-attached-item-delete clogo dicode-material-icons dicode-material-icons-trash-can-outline" title="<?php esc_attr_e("remove", "DIRECTORYPRESS"); ?>"></div>' +
					'</div>' +
					'</div>';

				return image_attachment_tpl_clogo;
			};

			window.directorypress_check_images_attachments_number_clogo = function() {
				if (images_allowed > $("#directorypress-images-upload-wrapper.clogo .directorypress-attached-item.clogo").length) {
					<?php if (is_admin()): ?>
					$("#directorypress-admin-upload-functions.clogo").show();
					<?php else: ?>
					$(".directorypress-upload-item.clogo").show();
					<?php endif; ?>
				} else {
					<?php if (is_admin()): ?>
					$("#directorypress-admin-upload-functions.clogo").hide();
					<?php else: ?>
					$(".directorypress-upload-item.clogo").hide();
					<?php endif; ?>
				}
			}

			$(function() {
				directorypress_check_images_attachments_number_clogo();

				$("#directorypress-attached-images-wrapper.clogo").on("click", ".directorypress-attached-item-delete.clogo", function() {
					$(this).parents(".directorypress-attached-item.clogo").remove();
		
					directorypress_check_images_attachments_number_clogo();
				});

				<?php if (!is_admin()): ?>
				$(document).on("click", ".directorypress-upload-item-button.clogo", function(e){
					e.preventDefault();
				
					$(this).parent().find("input").click();
				});

				$('.directorypress-upload-item.clogo').fileupload({
					sequentialUploads: true,
					dataType: 'json',
					url: '<?php echo admin_url('admin-ajax.php?action=directorypress_upload_image&post_id='. esc_attr($listing->post->ID) .'&_wpnonce='.wp_create_nonce('upload_images')); ?>',
					dropZone: $('.directorypress-drop-attached-item.clogo'),
					add: function (e, data) {
						const bytes = directorypress_js_instance.max_attchment_size * 1024;
						if(data.files[0].size > bytes){
							alert(data.files[0].name+' '+directorypress_js_instance.max_attchment_size_error +' '+directorypress_js_instance.max_attchment_size +' '+directorypress_js_instance.max_attchment_size_unit);
						}else{
							var jqXHR = data.submit();
						}
					},
					send: function (e, data) {
						directorypress_add_iloader_on_element($(this).find(".directorypress-drop-attached-item.clogo"));
					},
					done: function(e, data) {
						var result = data.result;
						if (result.uploaded_file) {
							$(this).before(directorypress_image_attachment_tpl_clogo(result.attachment_id, result.uploaded_file, data.files[0].name));
							//directorypress_custom_input_controls();
						} else {
							$(this).find(".directorypress-drop-attached-item.clogo").append("<p>"+result.error_msg+"</p>");
						}
						$(this).find(".directorypress-drop-zone.clogo").show();
						directorypress_delete_iloader_from_element($(this).find(".directorypress-drop-attached-item.clogo"));

						directorypress_check_images_attachments_number_clogo();
						
						if ($('.directorypress-attached-item.clogo').length != 0) {
							$('#directorypress-attached-images-wrapper.clogo').removeClass('full');
						}else{
							$('#directorypress-attached-images-wrapper.clogo').addClass('full');
						}
					}
				});
				<?php endif; ?>
			});
		})(jQuery);
	</script>

	<div id="directorypress-images-upload-wrapper" class="clogo">
		<p class="directorypress-submit-field-title"><?php _e('Company Logo', 'DIRECTORYPRESS'); ?></p>
		<div id="directorypress-attached-images-wrapper" class="clogo full clearfix">
			<?php 
			$attachment_id = get_post_meta($listing->post->ID, '_attached_image_clogo', true);
			if($attachment_id):
				$image_src_array = wp_get_attachment_image_src($attachment_id, 'full');
				$image_src  = $image_src_array[0]; 
				$param = array(
					'width' => 126,
					'height' => 100,
					'crop' => true
				);
			?>
				<div class="directorypress-attached-item clogo">
					<input type="hidden" name="attached_image_id" value="<?php echo esc_attr($attachment_id); ?>" />
					<img src="<?php echo esc_url(bfi_thumb($image_src, $param)); ?>" width="126" height="100" alt="<?php echo esc_attr_e('Company Logo', 'DIRECTORYPRESS'); ?>" />
					<div class="thumb-links clearfix">
						<div class="directorypress-attached-item-delete clogo dicode-material-icons dicode-material-icons-trash-can-outline" title="<?php _e("delete", "DIRECTORYPRESS"); ?>"></div>
					</div>
				</div>
			<?php endif; ?>
			<?php if (!is_admin()): ?>
			<div class="directorypress-upload-item clogo">
				<div class="directorypress-drop-attached-item clogo">
					<div class="directorypress-drop-zone clogo">
						<div class="dropzone-content">
							<span class="drophere"><?php _e("Drop Your Image Here", "DIRECTORYPRESS"); ?></span>
							<button class="directorypress-upload-item-button clogo btn btn-primary"><?php _e("Browse", "DIRECTORYPRESS"); ?></button>
							<input type="file" name="browse_file_clogo" />
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
					$('#directorypress-admin-upload-image_clogo').click(function(event) {
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
									if (images_allowed > $("#directorypress-attached-images-wrapper.clogo .directorypress-attached-item.clogo").length) {
										directorypress_ajax_loader_show();

										$.ajax({
											type: "POST",
											url: directorypress_js_instance.ajaxurl,
											dataType: 'json',
											data: {
												'action': 'directorypress_upload_media_image',
												'browse_file_clogo': attachment.id,
												'post_id': <?php echo esc_attr($listing->post->ID); ?>,
												'_wpnonce': '<?php echo wp_create_nonce('upload_clogo'); ?>',
											},
											attachment_id: attachment.id,
											attachment_url: attachment.sizes.full.url,
											attachment_title: attachment.title,
											success: function (response_from_the_action_function){
											console.log(attachment.id);
											$("#directorypress-attached-images-wrapper.clogo").append(directorypress_image_attachment_tpl_clogo(this.attachment_id, this.attachment_url, this.attachment_title));
											directorypress_check_images_attachments_number_clogo();
											
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
		<div id="directorypress-admin-upload-functions" class="clogo">
			<div class="directorypress-upload-option clogo">
				<input
					type="button"
					id="directorypress-admin-upload-image_clogo"
					class="button button-primary"
					name="browse_file_clogo"
					value="<?php esc_attr_e('Browse', 'DIRECTORYPRESS'); ?>" />
			</div>
		</div>
		<?php endif; ?>
		</div>
		<div class="directorypress-clearfix"></div>
	</div>
<?php endif; ?>