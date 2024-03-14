<?php 
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_wdo_ult_video extends WPBakeryShortCode {

			protected function content( $atts, $content = null ) {

				extract(shortcode_atts( array(
				    "wdo_video_url"				=> '',
				    "wdo_video_width"			=> '400px',
				    "wdo_video_height"			=> '300px',
				), $atts));

				preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $wdo_video_url, $match);
				$youtube_id = $match[1];
				ob_start();
				?>
				<div class="wdo-video-container">
					<iframe width="<?php echo $wdo_video_width; ?>" height="<?php echo $wdo_video_height; ?>" src="https://www.youtube.com/embed/<?php echo $youtube_id; ?>">
					</iframe>
				</div>
		<?php
			return ob_get_clean();
			}
		}
	}

	if ( function_exists( "vc_map" ) ) {
		vc_map( array(
			'name'		=> 'Youtube Video',
			"description" => __("Embed videos.", 'wdo-ultimate-addons'),
			'base'		=> 'wdo_ult_video',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/youtube-icon.png',
			'allowed_container_element' => 'vc_row',
			'params' => array(

					array(
						"type" => "textfield",
						"heading" => "Enter Complete Video URL",
						"description" => __("Make sure you add the actual URL of the video and not the share URL.<br><b>Valid :</b>  https://www.youtube.com/watch?v=HJRzUQMhJMQ<br><b>Invalid :</b> https://youtu.be/HJRzUQMhJMQ.", 'wdo-ultimate-addons'),
						"param_name" => "wdo_video_url"
					),
					array(
						"type" => "textfield",
						"heading" => "Video Width",
						"description" => __("Give width to video.", 'wdo-ultimate-addons'),
						"param_name" => "wdo_video_width"
					),
					array(
						"type" => "textfield",
						"heading" => "Video Height",
						"description" => __("Give height to video.", 'wdo-ultimate-addons'),
						"param_name" => "wdo_video_height"
					),

			)
		) );
	}
 ?>