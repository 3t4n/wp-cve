<?php
if( !function_exists ('wc_responsive_video_scripts') ) :
	function wc_responsive_video_scripts() {
		$ver = WC_RESPONSIVE_VIDEO_VERSION;

		wp_enqueue_style( 'wc-responsive-video-scripts', plugin_dir_url( __FILE__ ) . 'css/style.css', array( ), $ver );
	}
	add_action('wp_enqueue_scripts', 'wc_responsive_video_scripts');
endif;
