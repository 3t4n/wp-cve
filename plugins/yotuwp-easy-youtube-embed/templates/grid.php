<?php
global $yotuwp;

$settings = $yotuwp->data['settings'];
$data = $yotuwp->data['data'];
$classes = apply_filters( 'yotu_classes', array("yotu-videos yotu-mode-grid"), $settings);
$video_classes = apply_filters( 'yotu_video_classes', array("yotu-video"), $settings);
?>
<div class="<?php echo wp_kses_post(implode(" ", $classes));?>">
	<ul>
		<?php
		$total = count($data->items);
		if (is_object($data) && $total >0):

			$count = 0;

			foreach($data->items as $video){
				if ( $yotuwp->is_private($video) ) continue;
				
				$thumb = yotuwp_video_thumb($video);
				$videoId = $yotuwp->getVideoId($video);

				$video_title = yotuwp_video_title($video);
				$video->settings = $settings;

				$_classes_li = $count==0?' yotu-first':'';
				if ( ($count+1)==$total ) {
					$_classes_li .= ' yotu-last';
				}

				$_title_encode = $yotuwp->encode($video->snippet->title);
				$_desc = yotuwp_video_description($video);

				$_classes_a = implode(" ", $video_classes);
			?>
			<li class="<?php esc_attr_e( $_classes_li );?>">
				<?php do_action('yotuwp_before_link', $videoId, $video);?>
				<a href="#<?php esc_attr_e( $videoId );?>" class="<?php esc_attr_e( $_classes_a ) ;?>" data-videoid="<?php esc_attr_e( $videoId );?>" data-title="<?php esc_attr_e( $_title_encode );?>" title="<?php esc_attr_e( $video_title );?>">
					<div class="yotu-video-thumb-wrp">
						<div>
							<?php do_action('yotuwp_before_thumbnail', $videoId, $video, $settings);?>
							<img class="yotu-video-thumb" src="<?php esc_attr_e( $thumb );?>" alt="<?php esc_attr_e( $video_title ) ;?>">	
							<?php do_action('yotuwp_after_thumbnail', $videoId, $video);?>
						</div>
					</div>
					<?php if( isset($settings['title']) && $settings['title'] == 'on' ):?>
						<h3 class="yotu-video-title"><?php echo wp_kses_post( $video_title );?></h3>
					<?php endif;?>
					<?php do_action('yotuwp_after_title', $videoId, $video);?>
					<?php if(isset($settings['description']) && $settings['description'] == 'on'):?>
						<div class="yotu-video-description"><?php wp_kses_post( $_desc );?></div>
					<?php endif;?>
				</a>
				<?php do_action('yotuwp_after_link', $videoId, $video);?>
			</li>
				
			<?php
			$count++;
			}
		endif;	
		?>
	</ul>
</div>