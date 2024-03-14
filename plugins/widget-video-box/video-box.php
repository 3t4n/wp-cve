<?php
/*
Plugin Name: Easy Video Widget Box
 Plugin URI: http://www.purpleturtle.pro
 Description: Simple plugin to add video into your widget box. Supports Youtube, Dailymotion, Vimeo, Vevo, Veoh and Metacafe and many other sites that provide embed code.. just don't forget to edit the embed code to the size of your widget box
 Version: 1.6
 Author: Purple Turtle Productions
 Author URI: http://www.w3bdesign.ca/
*/


class VideoBox extends WP_Widget
{
    function VideoBox()
    {
        $widget_ops = array('classname' => 'VideoBox', 'description' => 'Paste Embed code from Youtube, Dailymotion, Vimeo, Vevo, Veoh and Metacafe' );
        $this->WP_Widget('VideoBox', 'Video Box', $widget_ops);
    }

    function form($instance)
    {
        $instance = wp_parse_args( (array) $instance, array( 'video_title' => '', 'video_text' => '', 'video_more' => '', 'video_link' => '' ) );
        $video_title = $instance['video_title'];
        $video_text = $instance['video_text'];
        $video_more = $instance['video_more'];
        $video_link = $instance['video_link'];
?>
  <p><label for="<?php echo $this->get_field_id('video_title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('video_title'); ?>" name="<?php echo $this->get_field_name('video_title'); ?>" type="text" value="<?php echo attribute_escape($video_title); ?>" /></label></p>
  <p><label for="<?php echo $this->get_field_id('video_text'); ?>">Embed Code: <textarea class="widefat" id="<?php echo $this->get_field_id('video_text'); ?>" name="<?php echo $this->get_field_name('video_text'); ?>"><?php echo attribute_escape($video_text); ?></textarea></label></p>
  <p><label for="<?php echo $this->get_field_id('video_more'); ?>">More Text: <input class="widefat" id="<?php echo $this->get_field_id('video_more'); ?>" name="<?php echo $this->get_field_name('video_more'); ?>" type="text" value="<?php echo attribute_escape($video_more); ?>" /></label></p>
  <p><label for="<?php echo $this->get_field_id('video_link'); ?>">Link: <input class="widefat" id="<?php echo $this->get_field_id('video_link'); ?>" name="<?php echo $this->get_field_name('video_link'); ?>" type="text" value="<?php echo attribute_escape($video_link); ?>" /></label></p>
<?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['video_title'] = $new_instance['video_title'];
        $instance['video_text'] = $new_instance['video_text'];
        $instance['video_more'] = $new_instance['video_more'];
        $instance['video_link'] = $new_instance['video_link'];
        return $instance;
    }

    function widget($args, $instance)
    {
        $video_title = $instance['video_title'];
        $video_text = $instance['video_text'];
        $video_more = $instance['video_more'];
        $video_link = $instance['video_link'];
        /*
        if (  get_option('videojs_options') ) {
            preg_match('/ *video[^>]*mp4 *= *["\']?([^"\']*)/i', $video_text, $matches);
            $params['mp4'] = $matches[1];
            $video_text = buildVideoJs($params);
        }
        */
        
        ?>
        <div class="video-box">
        	<?php if ( strlen($video_title)>0) { echo '<h2>'.$video_title.'</h2>'; } ?>
			<?php echo $video_text; ?>
        	<?php if ( strlen($video_more)>0) { echo '<br/><a href="'.$video_link.'">'.$video_more.'</a>'; } ?>
		</div>
        <?php
    }

}
add_action( 'widgets_init', create_function('', 'return register_widget("VideoBox");') );

/*
function buildVideoJs($params) {
    	
	$options = get_option('videojs_options'); //load the defaults
	
	extract(shortcode_atts(array(
		'mp4' => $params['mp4'],
		'webm' => '',
		'ogg' => '',
		'poster' => '',
		'width' => $options['videojs_width'],
		'height' => $options['videojs_height'],
		'preload' => $options['videojs_preload'],
		'autoplay' => $options['videojs_autoplay'],
		'id' => '',
		'class' => ''
	), $atts));

	// ID is required for multiple videos to work
	if ($id == '')
		$id = 'example_video_id_'.rand();

	// MP4 Source Supplied
	if ($mp4)
		$mp4_source = '<source src="'.$mp4.'" type=\'video/mp4\' />';
	else
		$mp4_source = '';

	// WebM Source Supplied
	if ($webm)
		$webm_source = '<source src="'.$webm.'" type=\'video/webm; codecs="vp8, vorbis"\' />';
	else
		$webm_source = '';

	// Ogg source supplied
	if ($ogg)
		$ogg_source = '<source src="'.$ogg.'" type=\'video/ogg; codecs="theora, vorbis"\' />';
	else
		$ogg_source = '';
	
	// Poster image supplied
	if ($poster)
		$poster_attribute = ' poster="'.$poster.'"';
	else
		$poster_attribute = '';
	
	// Preload the video?
	if ($preload) {
		if ($preload == "on")
			$preload = "auto";
			
		$preload_attribute = 'preload="'.$preload.'"';
	} else {
		$preload_attribute = '';
	}

	// Autoplay the video?
	if ($autoplay == "true" || $autoplay == "on")
		$autoplay_attribute = " autoplay";
	else
		$autoplay_attribute = "";
	
	// Is there a custom class?
	if ($class)
		$class = ' ' . $class;


	$videojs = <<<_end_

	<!-- Begin Video.js -->
	<video id="{$id}" class="video-js vjs-default-skin{$class}" width="{$width}" height="{$height}"{$poster_attribute} controls {$preload_attribute}{$autoplay_attribute} data-setup="{}">
		{$mp4_source}
		{$webm_source}
		{$ogg_source}
	</video>
	<!-- End Video.js -->

_end_;
    return $videojs;
}
*/

?>