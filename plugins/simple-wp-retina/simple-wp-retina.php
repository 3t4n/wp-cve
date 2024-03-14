<?php
/*
Plugin Name: Simple WP Retina
Plugin URI: http://wordpress.org/extend/plugins/simple-wp-retina/
Description: Adds seamless support for Retina and other high pixel density screens.
Version: 1.1.1
Author: Jonathan Desrosiers & Slocum Design Studio
Author URI: http://slocumstudio.com
License: GPLv2
*/

/**
 * Simple_WP_Retina class.
 */
class Simple_WP_Retina {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ), 999 );
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );
		add_filter( 'the_content', array( $this, 'the_content' ) );
		add_filter( 'post_thumbnail_size', array( $this, 'post_thumbnail_size' ), 999, 1 );
		add_filter( 'post_thumbnail_html', array( $this, 'post_thumbnail_html' ), 999, 5 );

		add_filter( 'post_gallery', array( $this, 'post_gallery' ), 999, 2 );
	}

	//Adds an @2x image size for core and theme defined image sizes
	function after_setup_theme() {

		$image_sizes = get_intermediate_image_sizes();
		foreach( $image_sizes as $image_size ) {

			if ( $image_size == 'full' )
				continue;

			if ( $image = $this->get_image_size( $image_size ) )
				add_image_size( $image_size . '@2x', $image['width'] * 2, $image['height'] * 2, $image['crop'] );
			else if ( $image_size == 'thumb' || $image_size == 'thumbnail' )
				add_image_size( 'thumbnail@2x', intval( get_option( 'thumbnail_size_w' ) ) * 2, intval( get_option( 'thumbnail_size_h' ) ) * 2, true );
			else if ( $image_size == 'medium' )
				add_image_size( 'medium@2x', intval( get_option( 'medium_size_w' ) ) * 2, intval( get_option( 'medium_size_h' ) ) * 2, false );
			else if ( $image_size == 'large' )
				add_image_size( 'large@2x', intval( get_option( 'large_size_w' ) ) * 2, intval( get_option( 'large_size_h' ) ) * 2, false );
		}
	}

	//makes surethat a given image name is a registered image size
	function get_image_size( $name ) {
		global $_wp_additional_image_sizes;

		if ( isset( $_wp_additional_image_sizes[ $name ] ) )
			return $_wp_additional_image_sizes[ $name ];

		return false;
	}

	//verifies that the user's screen is a high pixel density display
	function is_high_res() {
		if ( isset( $_COOKIE['devicePixelRatio'] ) && $_COOKIE['devicePixelRatio'] > 1.5 )
			return true;
		else
			return false;
	}

	//Changes the post thumbnail size to @2x
	function post_thumbnail_size( $size ) {
		if ( $this->is_high_res() && $size != 'full' )
			$size .= '@2x';
		return $size;
	}

	/**
	 * post_thumbnail_html function.
	 *
	 * @access public
	 * @param mixed $html
	 * @param mixed $post_id
	 * @param mixed $post_thumbnail_id
	 * @param mixed $size
	 * @param mixed $attr
	 * @return void
	 */
	function post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
		if ( $this->is_high_res() ) {
			//find images
		    preg_match_all( "#<img(.*?)\/?>#", $html, $matches );

		    $new_images = array();

		    foreach ( $this->break_apart_images( $matches[1] ) as $img_key => $image ) {
		    	$new_images[ $img_key ] = array( $matches[0][ $img_key ] );

			    $image['width'] = (int) ( $image['width'] / 2 );
			    $image['height'] = (int) ( $image['height'] / 2 );
			    $image['class'] .= ' attachment-' . str_replace( '@2x', '', $size );

			    $new_html = '<img';

			    foreach ( $image as $attr_name => $attr_value )
				    $new_html .= ' ' . $attr_name . '="' . $attr_value . '"';
				$new_html .= ' />';

				$new_images[ $img_key ][] = $new_html;
		    }

		    foreach ( $new_images as $new )
		    	$html = str_replace( $new[0], $new[1], $html );
		}
	    return $html;
	}

	/**
	 * break_apart_images function.
	 *
	 * @access public
	 * @param mixed $images
	 * @return void
	 */
	function break_apart_images( $images ) {
		 // extract attributes from each image and place in $images array
	    $image_attr = array();
	    foreach ( $images as $img ) {
	        preg_match_all( "#(\w+)=['\"]{1}([^'\"]*)#", $img, $matches2 );
	        // code below could be a lot neater using array_combine(), but it's php5 only
	        $tempArray = array();
	        foreach( $matches2[1] as $key => $val )
	            $tempArray[ $val ] = $matches2[2][ $key ];

	        $image_attr[] = $tempArray;
	    }

	    return $image_attr;
	}

	/**
	 * the_content function.
	 *
	 * @access public
	 * @param mixed $content
	 * @return void
	 */
	function the_content( $content ) {
		$new_content = $content;

		if ( $this->is_high_res() ) {
			//find images
		    preg_match_all( "#<img(.*?)\/?>#", $content, $matches );

		    $defaults = array( 'size-medium', 'size-large', 'size-thumbnail' );

		    $new_image_tags = array();

		    foreach ( $this->break_apart_images( $matches[1] ) as $key => $image ) {

			    $classes = explode( ' ', $image['class'] );

			    foreach ( $classes as $class_key => $value ) {

				    if ( in_array( $value, $defaults ) ) {
					    $classes[] .= $classes[ $class_key ] . '@2x';
					    $twox_class = substr( $classes[ $class_key ] . '@2x', 5 );
					}

				    if ( strstr( $value, 'wp-image-' ) ) {
					    $id_classes = explode( '-', $value );
					    $image_id = $id_classes[2];
				    }
			    }

			    if ( ! empty( $image_id ) && ! empty( $twox_class ) ) {

				    $new_src = wp_get_attachment_image_src( $image_id, $twox_class );

    			    $new_image_tags[ $key ] = array( $matches[0][ $key ] );

    			    $new_image_tags[ $key ][] = '<img src="' . $new_src[0] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" class="' . implode( ' ', $classes ) . '" />';

				}
		    }

		    //Replace old image with new
		    foreach ( $new_image_tags as $new_img )
			    $new_content = str_replace( $new_img[0], $new_img[1], $new_content );
		}
		return $new_content;
	}

	/**
	 * post_gallery function.
	 *
	 * @access public
	 * @param mixed $output
	 * @param mixed $attr
	 * @return void
	 */
	function post_gallery( $output, $attr ) {
		global $post;

		static $instance = 0;
		$instance++;

		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if ( isset( $attr['orderby'] ) ) {
			$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( ! $attr['orderby'] )
				unset( $attr['orderby'] );
		}

		extract( shortcode_atts( array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'itemtag'    => 'dl',
			'icontag'    => 'dt',
			'captiontag' => 'dd',
			'columns'    => 3,
			'size'       => 'thumbnail',
			'include'    => '',
			'exclude'    => ''
		), $attr ) );

		if ( $this->is_high_res() )
			$size .= '@2x';

		$id = intval( $id );
		if ( 'RAND' == $order )
			$orderby = 'none';

		if ( ! empty( $include ) ) {
			$include = preg_replace( '/[^0-9,]+/', '', $include );
			$_attachments = get_posts( array( 'include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[ $val->ID ] = $_attachments[ $key ];
			}
		} elseif ( ! empty( $exclude ) ) {
			$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
			$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
		} else {
			$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
		}

		if ( empty( $attachments ) )
			return '';

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment )
				$output .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
			return $output;
		}

		$itemtag = tag_escape( $itemtag );
		$captiontag = tag_escape( $captiontag );
		$columns = intval( $columns );
		$itemwidth = $columns > 0 ? floor( 100 / $columns ) : 100;
		$float = is_rtl() ? 'right' : 'left';

		$selector = "gallery-{$instance}";

		$gallery_style = $gallery_div = '';
		if ( apply_filters( 'use_default_gallery_style', true ) )
			$gallery_style = "
			<style type='text/css'>
				#{$selector} {
					margin: auto;
				}
				#{$selector} .gallery-item {
					float: {$float};
					margin-top: 10px;
					text-align: center;
					width: {$itemwidth}%;
				}
				#{$selector} img {
					border: 2px solid #cfcfcf;
				}
				#{$selector} .gallery-caption {
					margin-left: 0;
				}
			</style>
			<!-- see gallery_shortcode() in wp-includes/media.php -->";
		$size_class = sanitize_html_class( $size );
		$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
		$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			$link = isset( $attr['link'] ) && 'file' == $attr['link'] ? wp_get_attachment_link( $id, $size, false, false ) : wp_get_attachment_link( $id, $size, true, false );

			//Replace our images with half size
			if ( $this->is_high_res() ) {
				preg_match_all( '/width="([^"]*)"/i', $link, $widths );

				foreach ( $widths[1] as $key => $w )
					$link = str_replace( $widths[0][ $key ], 'width="' . (int)( $w / 2 ) . '"', $link );

				preg_match_all( '/height="([^"]*)"/i', $link, $heights );

				foreach ( $heights[1] as $key => $h )
					$link = str_replace( $heights[0][ $key ], 'height="' . (int)( $h / 2 ) . '"', $link );
			}

			//If lightbox is present, let's add classes
			if ( class_exists( 'wp_lightboxplus' ) ) {
				global $wp_lightboxplus;
				if ( ! empty( $wp_lightboxplus->lightboxOptions ) )
					$lightboxPlusOptions = $wp_lightboxplus->getAdminOptions( $wp_lightboxplus->lightboxOptionsName );
				if ( $lightboxPlusOptions['multiple_galleries'] )
					$link = $wp_lightboxplus->lightboxPlusReplace( $link,'-'.$instance );
				else
					$link = $wp_lightboxplus->lightboxPlusReplace( $link,'' );
			}

			$output .= "<{$itemtag} class='gallery-item'>";
			$output .= "
				<{$icontag} class='gallery-icon'>
					$link
				</{$icontag}>";
			if ( $captiontag && trim( $attachment->post_excerpt ) ) {
				$output .= "
					<{$captiontag} class='wp-caption-text gallery-caption'>
					" . wptexturize( $attachment->post_excerpt ) . "
					</{$captiontag}>";
			}
			$output .= "</{$itemtag}>";
			if ( $columns > 0 && ++$i % $columns == 0 )
				$output .= '<br style="clear: both" />';
		}

		$output .= "
				<br style='clear: both;' />
			</div>\n";

		return $output;
	}

	/**
	 * wp_footer function.
	 *
	 * @access public
	 * @return void
	 */
	function wp_footer() {
	?>
		<script type="text/javascript">// <![CDATA[
			if( window.devicePixelRatio !== undefined ) document.cookie = 'devicePixelRatio = ' + window.devicePixelRatio;
		// ]]></script>
		<?php
	}
}

$simple_wp_retina = new Simple_WP_Retina();