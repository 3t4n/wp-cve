<?php

class Meow_RFI_Core {

	public function __construct() {
		add_action( 'rss2_item', array( $this, 'rss_insert' ), 10, 1 );
		add_action( 'rss2_ns', array( $this, 'rss_ns_insert' ), 10, 1 );

		// Modify the content/description of your articles
		//add_filter( 'the_content', array( $this, 'the_content' ), 10, 1 );

		// If you want to reset your RSS cache more often
		//add_filter( 'wp_feed_cache_transient_lifetime', create_function('$a', 'return 1;') );
	}
	
	function the_content( $content ) {
		if ( !is_feed() )
			return $content;
		
		global $post;
		if ( !empty( $post->post_excerpt ) ) {
			$content = wp_strip_all_tags( $post->post_excerpt );
			if ( !empty( $content ) )
				return wp_trim_excerpt( $content );
		}
		if ( !empty( $post->post_content ) ) {
			$content = wp_strip_all_tags( $post->post_content );
			if ( !empty( $content ) )
				return wp_trim_excerpt( $content );
		}
		return "";
	}

	function rss_ns_insert() {
		echo 'xmlns:media="http://search.yahoo.com/mrss/"' . "\n";
	}

	function rss_insert( $comments ) {
		if ( !empty( $comments ) )
			return;
		global $post;
		$size = apply_filters( 'rfi_rss_image_size', 'large' );
		$image = get_the_post_thumbnail_url( $post->ID, $size );
		if ( !empty( $image ) ) {
			echo "	" . '<media:content url="' . esc_url( $image ) . '" medium="image" />' . "\n";
		}
	}
}

?>
