<?php
/*
Plugin Name: Background Videos for SiteOrigin Page Builder 
Plugin URI: https://html5backgroundvideos.com/background-video-addon-siteorigin-page-builder/
Description: Add a video background to any SiteOrigin Page Builder row.
Version: 1.0.0
Author: BG Stock, theeighth
Author URI: https://html5backgroundvideos.com
License: GPLv2 or later
*/

// don't load directly
if (!defined('ABSPATH')) die('-1');



class Background_Videos_For_SiteOrigin {


	/**
	 * Constructor - add hooks here and define shortcode
	 */
	function __construct() {
		
		// Add scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts_and_styles' ) );

		// Add a new field group to row
		add_filter( 'siteorigin_panels_row_style_groups', array( $this, 'custom_row_style_group' ), 10, 3 );

		// Add fields to row
		add_filter( 'siteorigin_panels_row_style_fields', array( $this, 'custom_row_style_fields' ) );

		// Adjust row output
		add_filter( 'siteorigin_panels_after_row', array( $this, 'video_after_row' ), 10, 3 );

		// Add a prebuilt layout
		add_filter( 'siteorigin_panels_prebuilt_layouts', array( $this, 'bgvideo_prebuilt_layouts' ) );

	}


	/**
	 * Defines output for video
	 */
	public function video_after_row( $output, $grid_item, $grid_attributes ) {

		// Debug
		//$output .= '<h1>Grid item</h1><pre>' . print_r( $grid_item, true ) . '</pre>';
		//$output .= '<h1>Grid attributes</h1><pre>' . print_r( $grid_attributes, true ) . '</pre>';

		// Custom atts
		$use_background_video = array_key_exists( 'use_background_video', $grid_item['style'] ) ? $grid_item['style']['use_background_video'] : '';
		$mp4_url = array_key_exists( 'mp4_url', $grid_item['style'] ) ? $grid_item['style']['mp4_url'] : '';
		$webm_url = array_key_exists( 'webm_url', $grid_item['style'] ) ? $grid_item['style']['webm_url'] : '';
		$ogg_url = array_key_exists( 'ogg_url', $grid_item['style'] ) ? $grid_item['style']['ogg_url'] : '';
		$poster_image = array_key_exists( 'poster_image', $grid_item['style'] ) ? $grid_item['style']['poster_image'] : '';
		$fade_in = array_key_exists( 'fade_in', $grid_item['style'] ) ? $grid_item['style']['fade_in'] : '';
		$pause_after = array_key_exists( 'pause_after', $grid_item['style'] ) ? $grid_item['style']['pause_after'] : '';
		$pause_play_button = array_key_exists( 'pause_play_button', $grid_item['style'] ) ? $grid_item['style']['pause_play_button'] : '';
		$pauseplay_xpos = array_key_exists( 'pauseplay_xpos', $grid_item['style'] ) ? $grid_item['style']['pauseplay_xpos'] : '';
		$pauseplay_ypos = array_key_exists( 'pauseplay_ypos', $grid_item['style'] ) ? $grid_item['style']['pauseplay_ypos'] : '';
		$video_overlay = array_key_exists( 'video_overlay', $grid_item['style'] ) ? $grid_item['style']['video_overlay'] : '';
		$overlay_opacity = array_key_exists( 'overlay_opacity', $grid_item['style'] ) ? $grid_item['style']['overlay_opacity'] : '';
		$overlay_color = array_key_exists( 'overlay_color', $grid_item['style'] ) ? $grid_item['style']['overlay_color'] : '';
		$overlay_pattern = array_key_exists( 'overlay_pattern', $grid_item['style'] ) ? $grid_item['style']['overlay_pattern'] : '';
		

		if( $use_background_video ) {

			/* Old - image upload for poster
			$poster_image_src = wp_get_attachment_image_src( $poster_image, 'full' );
			$poster_image_src = $poster_image_src[0];
			*/
			/* New - poster as url */
			$poster_image_src = $poster_image;
			$row_id = uniqid();

			// Pattern stuff
			$patterns_path = plugins_url('assets/patterns/', __FILE__);
			$is_pattern_overlay = false;
			$pattern_prefix = '';
			if( 'pattern_light' === $video_overlay ) {
				$pattern_prefix = 'white-';
				$is_pattern_overlay = true;
			} else if( 'pattern_dark' === $video_overlay ) {
				$pattern_prefix = 'black-';
				$is_pattern_overlay = true;
			}

			ob_start(); ?>

			<video 
				id="<?php echo 'so_bgvideo_' . $row_id; ?>" 
				class="so_video_bg jquery-background-video" 
				loop 
				autoplay 
				playsinline
				muted
				data-bgvideo
				<?php echo ( $poster_image ) ? 'poster="' . $poster_image_src . '"' : ''; ?>
				<?php echo ( $fade_in ) ? 'data-bgvideo-fade-in="500"' : 'data-bgvideo-fade-in="0"' ?>
				<?php echo ( $pause_after ) ? 'data-bgvideo-pause-after="' . $pause_after . '"' : ''; ?>
				<?php echo ( $pause_play_button ) ? 'data-bgvideo-show-pause-play=true' : 'data-bgvideo-show-pause-play=false'; ?>
				<?php echo ( $pauseplay_xpos ) ? 'data-bgvideo-pause-play-x-pos="' . $pauseplay_xpos . '"' : ''; ?>
				<?php echo ( $pauseplay_xpos ) ? 'data-bgvideo-pause-play-y-pos="' . $pauseplay_ypos . '"' : ''; ?> 
				>
					<?php echo ( $mp4_url ) ? '<source src="' . $mp4_url . '" type="video/mp4">' : ''; ?>
					<?php echo ( $webm_url ) ? '<source src="' . $webm_url . '" type="video/webm">' : ''; ?>
					<?php echo ( $ogg_url ) ? '<source src="' . $ogg_url . '" type="video/ogg">' : ''; ?>
			</video>

			<?php if( 'none' !== $video_overlay ) : ?>
				<div
					id="<?php echo 'so_bgvideo_overlay_' . $row_id; ?>"
					class="so_video_overlay"
					style="
						opacity: 0.<?php echo $overlay_opacity; ?>;
						<?php echo ( 'solid' === $video_overlay ) ? 'background-color: ' . $overlay_color . ';' : '' ?>
						<?php echo ( $is_pattern_overlay ) ? 'background-image: url(' . $patterns_path . $pattern_prefix . $overlay_pattern . '.png);' : '' ?>
					"
					>
				</div>
			<?php endif; ?>

			<script type="text/javascript">
			(function() {
				// Move the video and container into the row as the first child
				var video_tag = document.getElementById(<?php echo '"so_bgvideo_' . $row_id . '"'; ?>);
				var video_overlay = document.getElementById(<?php echo '"so_bgvideo_overlay_' . $row_id . '"'; ?>);
				var video_row = video_tag.previousSibling;
				while(video_row && video_row.nodeType != 1) {
					video_row = video_row.previousSibling;
				}
				var row_inner = video_row.firstChild;
				row_inner.insertBefore( video_tag, row_inner.firstChild );
				if( video_overlay ) {
					row_inner.insertBefore( video_overlay, video_tag.nextSibling);
				}
				row_inner.className += ' so_video_bg_row jquery-background-video-wrapper';
				video_tag.play();
			}());
			</script>

			<?php
			$output .= ob_get_clean();
			
			
		}
		return $output;
	}


	/**
	 * Add a custom group for background video options
	 */
	public function custom_row_style_group( $groups, $post_id, $args ) {

		$groups['bgvideo'] = array(
			'name' => __('Background Video', 'siteorigin-panels'),
			'priority' => 25
		);

		return $groups;
	}

	
	/**
	 * Custom row style fields
	 */
	public function custom_row_style_fields( $fields ) {
		
		$group = 'bgvideo';
		$order = 10;

		// Intro
		$fields['use_background_video'] = array(
			'name'        => __('Background Video', 'siteorigin-panels'),
			'type'        => 'checkbox',
			'group'       => $group,
			'description' => __('Looking for great background videos? Try <a style="font-weight: bold;" href="https://html5backgroundvideos.com/?utm_source=SO%20Video%20Backgrounds%20Addon&utm_medium=Text%20Link&utm_content=Intro%20on%20background%20tab&utm_campaign=WordPress%20Plugins" target="_blank">BG&nbsp;Stock</a> - a library of stock videos specifically for website backgrounds.', 'siteorigin-panels'),
			'priority'    => $order ++,
			'default'     => ''
		);

		/* === Video Files === */

		// Mp4 URL
		$fields['mp4_url'] = array(
			'name'        => __('Mp4 URL', 'siteorigin-panels'),
			'type'        => 'url',
			'group'       => $group,
			'description' => __('Required file type. We recommend using <a href="https://html5backgroundvideos.com/converter?utm_source=SO%20Video%20Backgrounds%20Addon&utm_medium=Text%20Link&utm_content=Files%20section%20of%20background%20tab&utm_campaign=WordPress%20Plugins" target="_blank">this converter</a> to generate your background video files.', 'siteorigin-panels'),
			'priority'    => $order ++,
			'default'     => ''
		);

		// Webm URL
		$fields['webm_url'] = array(
			'name'        => __('WebM URL', 'siteorigin-panels'),
			'type'        => 'url',
			'group'       => $group,
			'description' => __('Recommended file type.', 'siteorigin-panels'),
			'priority'    => $order ++,
			'default'     => ''
		);

		// Ogg URL
		$fields['ogg_url'] = array(
			'name'        => __('Ogg URL', 'siteorigin-panels'),
			'type'        => 'url',
			'group'       => $group,
			'description' => __('Optional file type.', 'siteorigin-panels'),
			'priority'    => $order ++,
			'default'     => ''
		);
/*
		// Poster (image)
		$fields['poster_image'] = array(
			'name'        => __('Poster / fallback image', 'siteorigin-panels'),
			'type'        => 'image',
			'group'       => $group,
			'description' => __('This image will be used on devices that don\'t support background video, and will be displayed while the video is loading. We recommend a high-quality screenshot of one of the first few frames. For best results, you should also set this as the background image for this row.', 'siteorigin-panels'),
			'priority'    => $order ++,
			'default'     => ''
		);
*/
		// Poster (url)
		$fields['poster_image'] = array(
			'name'        => __('Poster / fallback image URL', 'siteorigin-panels'),
			'type'        => 'url',
			'group'       => $group,
			'description' => __('This image will be used on devices that don\'t support background video, and will be displayed while the video is loading. We recommend a high-quality screenshot of one of the first few frames. For best results, you should also set this as the background image for this row.', 'siteorigin-panels'),
			'priority'    => $order ++,
			'default'     => ''
		);

		/* === Overlay === */

		// Overlay Type
		$fields['video_overlay'] = array(
			'name'        => __('Video Overlay', 'siteorigin-panels'),
			'type'        => 'select',
			'group'       => $group,
			'description' => __('An overlay can help to provide contrast with your row content, or disguise low quality video.', 'siteorigin-panels'),
			'priority'    => $order ++,
			'options'     => array(
								'none'          => 'No overlay',
								'solid'         => 'Color',
								'pattern_light' => 'Light Pattern',
								'pattern_dark'  => 'Dark Pattern'
							 ),
			'default'     => 'none'
		);

		// Overlay Opacity
		$fields['overlay_opacity'] = array(
			'name'        => __('Overlay Opacity', 'siteorigin-panels'),
			'type'        => 'text',
			'group'       => $group,
			'description' => __('Enter a number between 0 and 99. (Only relevant if you choose an overlay type)', 'siteorigin-panels'),
			'priority'    => $order ++,
			'default'     => ''
		);

		// Solid Overlay Colour
		$fields['overlay_color'] = array(
			'name'        => __('Overlay Color', 'siteorigin-panels'),
			'type'        => 'color',
			'group'       => $group,
			'description' => __('(Only relevant if you choose a colored overlay)', 'siteorigin-panels'),
			'priority'    => $order ++,
			'default'     => ''
		);

		// Pattern
		$fields['overlay_pattern'] = array(
			'name'        => __('Overlay Pattern', 'siteorigin-panels'),
			'type'        => 'select',
			'group'       => $group,
			'description' => __('(Only relevant if you set overlay type to a pattern). Patterns are especially good at disguising low quality video. See a demo of these patterns <a href="https://html5backgroundvideos.com/pattern-overlays?utm_source=SO%20Video%20Backgrounds%20Addon&utm_medium=Text%20Link&utm_content=Pattern%20dropdown%20description&utm_campaign=WordPress%20Plugins" target="_blank">here</a>.', 'siteorigin-panels'),
			'priority'    => $order ++,
			'options'     => array_flip( array( // Just because I already had it in the reverse order
								'Dots' => 'dots',
								'Squares' => 'squares',
								'Small Checks' => 'small-checks',
								'Medium Checks' => 'medium-checks',
								'Large Checks' => 'large-checks',
								'Vertical Stripes' => 'vertical-stripes',
								'Vertical Lines' => 'vertical-lines',
								'Horizontal Stripes' => 'horizontal-stripes',
								'Horizontal Lines' => 'horizontal-lines',
								'Criss-cross' => 'criss-cross',
								'Diagonal Lines' => 'diagonal-lines',
								'Fly Screen' => 'fly-screen',
								'Plus Signs' => 'plus-signs',
								'Zig Zag' => 'zig-zag',
								'Broken Lines' => 'broken-lines'
							 ) ),
			'default'     => 'dots'
		);

		/* === Options === */

		// Fade in
		$fields['fade_in'] = array(
			'name'        => __('Fade in on start?', 'siteorigin-panels'),
			'type'        => 'checkbox',
			'default'     => 'true',
			'group'       => $group,
			'description' => __('Fading the video in on start can help avoid distracting the user.', 'siteorigin-panels'),
			'priority'    => $order ++
		);

		// Pause after
		$fields['pause_after'] = array(
			'name'        => __('Pause video after...', 'siteorigin-panels'),
			'type'        => 'text',
			'default'     => '120',
			'group'       => $group,
			'description' => __('Enter a number of seconds to play before pausing, or 0 for no pause. We recommend pausing after a while and fading out, to reduce your users\' power consumption.', 'siteorigin-panels'),
			'priority'    => $order ++
		);

		// Pause button
		$fields['pause_play_button'] = array(
			'name'        => __('Add pause/play button?', 'siteorigin-panels'),
			'type'        => 'checkbox',
			'default'     => 'true',
			'group'       => $group,
			'description' => __('It\'s a good idea to allow your user to pause the video if they wish.', 'siteorigin-panels'),
			'priority'    => $order ++
		);

		// Pause X Position
		$fields['pauseplay_xpos'] = array(
			'name'        => __('Pause/play button X position', 'siteorigin-panels'),
			'type'        => 'select',
			'group'       => $group,
			'description' => __('(Only relevant if you check "Add pause/play button")', 'siteorigin-panels'),
			'priority'    => $order ++,
			'options'     => array(
								'left' => 'Left',
								'center' => 'Center',
								'right' => 'Right'
							 ),
			'default'     => 'right'
		);

		// Pause Y Position
		$fields['pauseplay_ypos'] = array(
			'name'        => __('Pause/play button Y position', 'siteorigin-panels'),
			'type'        => 'select',
			'group'       => $group,
			'description' => __('(Only relevant if you check "Add pause/play button")', 'siteorigin-panels'),
			'priority'    => $order ++,
			'options'     => array(
								'top' => 'Top',
								'center' => 'Center',
								'bottom' => 'Bottom'
							 ),
			'default'     => 'top'
		);

		return $fields;
	}


	/**
	 * Adds prebuilt layouts
	 */
	public function bgvideo_prebuilt_layouts( $layouts ){

		$layouts['example-background-video'] = array (
			'name' => __('Example Background Video', 'siteorigin-panels'),
        	'description' => __('A pre-built background video layer with a simple text widget.', 'siteorigin-panels'),
		  	'widgets' => 
			  array (
			    0 => 
			    array (
			      'title' => 'Easy HTML5 Background Videos',
			      'text' => 'The Video Backgrounds for SiteOrigin Page Builder plugin lets you quickly and easily add a background video to any page builder row.

			P.S. Looking for great background videos? Try <a style="font-weight: bold;" href="https://html5backgroundvideos.com/?utm_source=SO%20Video%20Backgrounds%20Addon&utm_medium=Text%20Link&utm_content=Intro%20on%20prebuilt%20layout&utm_campaign=WordPress%20Plugins" target="_blank">BG&nbsp;Stock</a> - a library of stock videos specifically for website backgrounds.',
			      'filter' => true,
			      'panels_info' => 
			      array (
			        'class' => 'WP_Widget_Text',
			        'grid' => 0,
			        'cell' => 0,
			        'id' => 0,
			        'style' => 
			        array (
			          'widget_css' => 'text-align: center;
									font-size: 1.2em;',
			          'background_image_attachment' => false,
			          'background_display' => 'tile',
			        ),
			      ),
			    ),
			  ),
			  'grids' => 
			  array (
			    0 => 
			    array (
			      'cells' => 1,
			      'style' => 
			      array (
			        'use_background_video' => true,
			        'mp4_url' => 'https://d3k5xyayaartr5.cloudfront.net/wet-leaf/wet-leaf.mp4',
			        'webm_url' => 'https://d3k5xyayaartr5.cloudfront.net/wet-leaf/wet-leaf.webm',
			        'ogg_url' => 'https://d3k5xyayaartr5.cloudfront.net/wet-leaf/wet-leaf.ogv',
			        'poster_image' => 'https://html5backgroundvideos.com/wp-content/uploads/2015/09/wet-leaf.jpg',
			        'video_overlay' => 'pattern_dark',
			        'overlay_opacity' => '15',
			        'overlay_pattern' => 'fly-screen',
			        'fade_in' => true,
			        'pause_after' => '120',
			        'pause_play_button' => true,
			        'pauseplay_xpos' => 'right',
			        'pauseplay_ypos' => 'top',
			        'cell_class' => 'force-white-text dark-text-shadow',
			        'padding' => '18%',
			        'row_stretch' => 'full',
			        'background_image' => 'https://html5backgroundvideos.com/wp-content/uploads/2015/09/wet-leaf.jpg',
			        'background_image_repeat' => '',
			        'no_margin' => '',
			      ),
			    ),
			  ),
			  'grid_cells' => 
			  array (
			    0 => 
			    array (
			      'grid' => 0,
			      'weight' => 1,
			    ),
			  ),
		);

		return $layouts;

	}


	/**
	 * Add scripts and styles
	 */
	public function add_scripts_and_styles() {

		wp_register_style( 'jquery-background-video', plugins_url('assets/jquery.background-video.css', __FILE__) );
		wp_register_style( 'so_video_background', plugins_url('assets/so_video_background.css', __FILE__) );
		wp_enqueue_style( 'jquery-background-video' );
		wp_enqueue_style( 'so_video_background' );

		// If you need any javascript files on front end, here is how you can load them.
		wp_register_script( 'jquery-background-video', plugins_url('assets/jquery.background-video.js', __FILE__), array('jquery'), '1.1.1', true );
		wp_enqueue_script( 'jquery-background-video' );

	}


}

// Finally initialize code
$bg_vids_for_siteorigin = new Background_Videos_For_SiteOrigin();
