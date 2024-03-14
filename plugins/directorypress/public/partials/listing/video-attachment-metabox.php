<?php if ($listing->package->videos_allowed): ?>
<script>
	var videos_allowed = <?php echo esc_attr($listing->package->videos_allowed); ?>;

	(function($) {
		"use strict";

		window.directorypress_video_attachment_tpl = function(video_id, image_url) {
			var video_attachment_tpl = '<div class="directorypress-attached-item">'+
				'<input type="hidden" name="attached_video_id[]" value="'+video_id+'" />'+
				'<div class="directorypress-attached-item-img"><img src="'+image_url+'" alt="<?php _e("Video Thumbnail", "DIRECTORYPRESS"); ?>" /></div>'+
				'<div class="thumb-links clearfix"><div class="directorypress-attached-item-delete dicode-material-icons dicode-material-icons-trash-can-outline" title="<?php _e("delete", "DIRECTORYPRESS"); ?>"></div></div>'+
			'</div>';

			return video_attachment_tpl;
		};

		window.directorypress_check_videos_attachments_number = function() {
			if (videos_allowed > $("#directorypress-attached-videos-wrapper .directorypress-attached-item").length) {
				$(".directorypress-attach-videos-functions").show();
			} else {
				$(".directorypress-attach-videos-functions").hide();
			}
		}

		$(function() {
			directorypress_check_videos_attachments_number();

			$("#directorypress-attached-videos-wrapper").on("click", ".directorypress-attached-item-delete", function() {
				$(this).parents(".directorypress-attached-item").remove();
	
				directorypress_check_videos_attachments_number();
			});
		});
	})(jQuery);
</script>
<div id="directorypress-video-attach-wrapper" class="">
	<p class="directorypress-submit-field-title"><?php _e("Listing videos", "DIRECTORYPRESS"); ?></p>
	<div class="alert alert-info"><?php echo sprintf(esc_html__('You can attach up to %s Video', 'DIRECTORYPRESS'), esc_attr($listing->package->videos_allowed)); ?></div>
	<div id="directorypress-attached-videos-wrapper">
		<?php foreach ($listing->videos AS $video): ?>
		<div class="directorypress-attached-item">
			<input type="hidden" name="attached_video_id[]" value="<?php echo esc_attr($video['id']); ?>" />
			<?php if (strlen($video['id']) == 11 || strlen($video['id']) == 8 || strlen($video['id']) == 9): ?>
				<?php
				if (strlen($video['id']) == 11) {
					$image_url = "http://i.ytimg.com/vi/" . esc_attr($video['id']) . "/0.jpg";
				} elseif (strlen($video['id']) == 8 || strlen($video['id']) == 9) {
					$data = wp_remote_get("http://vimeo.com/api/v2/video/" . esc_attr($video['id']) . ".json");
					$data = json_decode($data['body']);
					$image_url = $data[0]->thumbnail_medium;
				} ?>
				<div class="directorypress-attached-item-img"><img src="<?php echo esc_url($image_url); ?>" alt="<?php _e("Video Thumbnail", "DIRECTORYPRESS"); ?>" /></div>
			<?php endif; ?>
			<div class="thumb-links clearfix">
				<div class="directorypress-attached-item-delete dicode-material-icons dicode-material-icons-trash-can-outline" title="<?php _e("delete", "DIRECTORYPRESS"); ?>"></div>
			</div>
			
		</div>
		<?php endforeach; ?>
	</div>
	<div class="directorypress-clearfix"></div>
	<script>
		(function($) {
			"use strict";
			$('body').on('click', '#addvideo', function(e){
				e.preventDefault();
				//alert('test');
				attachVideo();
			});
			window.attachVideo = function() {
				//$("#directorypress-attach-video-input").change(function(){
					if ($("#directorypress-attach-video-input").val()) {
						var regExp_youtube = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
						var regExp_vimeo = /https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/;
						var matches_youtube = $("#directorypress-attach-video-input").val().match(regExp_youtube);
						var matches_vimeo = $("#directorypress-attach-video-input").val().match(regExp_vimeo);
						var taeget = $("#directorypress-attach-video-input");
						if (matches_youtube && matches_youtube[2].length == 11) {
							var video_id = matches_youtube[2];
							var image_url = 'http://i.ytimg.com/vi/'+video_id+'/0.jpg';
							$("#directorypress-attached-videos-wrapper").append(directorypress_video_attachment_tpl(video_id, image_url));
							
							directorypress_check_videos_attachments_number();
							clearTarget(taeget);
						} else if (matches_vimeo && (matches_vimeo[3].length == 8 || matches_vimeo[3].length == 9)) {
							var video_id = matches_vimeo[3];
							var url = "//vimeo.com/api/v2/video/" + video_id + ".json?callback=showVimeoThumb";
							var script = document.createElement('script');
							script.src = url;
							$("#directorypress-attach-videos-functions").before(script);
						} else {
							alert("<?php esc_attr_e('Wrong URL or this video is unavailable', 'DIRECTORYPRESS'); ?>");
						}
					}
				//});
			};
			//attachVideo();
			
			window.showVimeoThumb = function(data){
				var video_id = data[0].id;
			    var image_url = data[0].thumbnail_medium;
			    $("#directorypress-attached-videos-wrapper").append(directorypress_video_attachment_tpl(video_id, image_url));

			    directorypress_check_videos_attachments_number();
			};
			window.clearTarget = function(target) {
				 target.val("");
			};
			
		})(jQuery);
	</script>
	<div id="directorypress-attach-videos-functions">
		<div class="directorypress-upload-option">
			<input type="text" id="directorypress-attach-video-input" class="form-control" placeholder="<?php _e('Enter full YouTube or Vimeo video link', 'DIRECTORYPRESS'); ?>"  />
			<a id="addvideo" href="#" class="submit-listing-button"><?php echo esc_html__('Add Video', 'DIRECTORYPRESS'); ?></a>
		</div>
	</div>
</div>
<?php endif;