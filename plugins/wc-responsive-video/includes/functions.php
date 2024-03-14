<?php
if ( ! function_exists( 'wc_responsive_video_html' ) ) {
	function wc_responsive_video_html( $html, $url, $attr, $post_ID ) {
		if ( ! preg_match( '/^<iframe.*\<\/iframe\>$/', $html ) ) {
			return $html;
		}
		if ( ! $ratio = wc_responsive_video_html_get_ratio( $html ) ) {
			return $html;
		}

		$class = array();
		$class[] = 'wc-responsive-video';
		$class[] = 'wc-rv-id-' . sanitize_title( $url );
		$class[] = 'wc-rv-post-' . $post_ID;
		$class[] = 'wc-rv-ratio-' . $ratio;

		return '<p class="' . implode( ' ', $class ) . '">' . $html . '</p>';
	}
}
add_filter( 'embed_oembed_html', 'wc_responsive_video_html', 999, 4 );

if ( ! function_exists( 'wc_responsive_video_html_jetpack' ) ) {
	function wc_responsive_video_html_jetpack( $html ) {
		if ( ! preg_match( '/^<iframe.*\<\/iframe\>$/', $html ) ) {
			return $html;
		}
		if ( ! $ratio = wc_responsive_video_html_get_ratio( $html ) ) {
			return $html;
		}

		$class = array();
		$class[] = 'wc-responsive-video';
		$class[] = 'wc-rv-ratio-' . $ratio;

		return '<p class="' . implode( ' ', $class ) . '">' . $html . '</p>';
	}
}
add_filter( 'video_embed_html', 'wc_responsive_video_html_jetpack', 999, 1 );

if ( ! function_exists( 'wc_responsive_video_get_width_height' ) ) {
	/**
	 * Return the width and height value of html element
	 *
	 * @since 3.8
	 * @access public
	 *
	 * @param string $h typically an img/iframe/ect string
	 * @return array width|height
	 */
	function wc_responsive_video_html_get_width_height( $h ) {
		preg_match('/width=["|\'](\d+)["|\'] height=["|\'](\d+)["|\']/', $h, $m);

		if ( array_key_exists( 1, $m ) )
			$a[] = intval( $m[1] ); //width
		else
			$a[] = 0;

		if ( array_key_exists( 2, $m ) )
			$a[] = intval( $m[2] ); //height
		else
			$a[] = 0;

		return $a;
	}
}

if ( ! function_exists( 'wc_responsive_video_get_ratio' ) ) {
	/**
	 * Return the aspect ratio of video
	 *
	 * @since 3.8
	 * @access public
	 *
	 * @param string $h typically an img/iframe/ect string
	 * @return array width|height
	 */
	function wc_responsive_video_html_get_ratio( $html ) {
		list( $width, $height ) = wc_responsive_video_html_get_width_height( $html );

		// check for twitter html that doesn't have width or height set in HTML.
		if ( empty( $width ) || empty( $height ) ) {
			return 0;
		}

		$ratio = round( $width / $height, 1 );

		switch ( $ratio ) {
			case 1.7 :
			case 1.8 :
			case 1.9 :
				return '16-9';
			case 1.4 :
			case 1.3 :
			case 1.2 :
				return '4-3';
			default :
				return 0;
		}

	}
}
