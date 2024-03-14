<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'wzslider-thumbnail', 9999, 55 );
}

class wpz_plugin_wzslider {
	public static $atts;
	public static $scriptAtts;

	public static $galleries = array();

	static public function init( $atts, $content = null, $code = '' ) {
		global $post;

		// Shortcode defaults
		$default_atts = array(
			'autoplay'   => 'false',
			'interval'   => '3000',
			'info'       => 'false',
			'height'     => '500',
			'lightbox'   => 'false',
			'clicknext'  => 'true',
			'transition' => 'fade',
			'exclude'    => ''
		);

		$atts = shortcode_atts( $default_atts, $atts );

		if ( $atts['height'] != '500' ) {
			self::$scriptAtts .= "height: {$atts['height']},";
		} else {
			self::$scriptAtts .= "height: 500,";
		}

		if ( $atts['info'] != 'false' ) {
			self::$scriptAtts .= "showInfo: true,";
		} else {
			self::$scriptAtts .= "showInfo: false,";
		}

		if ( $atts['lightbox'] != 'true' ) {
			self::$scriptAtts .= "clicknext: true,";
		}

		if ( $atts['lightbox'] != 'false' ) {
			self::$scriptAtts .= "lightbox: true,";
		} else {
			self::$scriptAtts .= "lightbox: false,";
		}

		if ( $atts['autoplay'] != 'false' ) {
			self::$scriptAtts .= "autoplay: {$atts['interval']},";
		} else {
			self::$scriptAtts .= "autoplay: false,";
		}

		if ( $atts['transition'] != 'fade' ) {
			self::$scriptAtts .= "transition: {$atts['transition']}";
		} else {
			self::$scriptAtts .= "transition: 'fade'";
		}

		$exclude = array_map( 'intval', explode( ',', $atts['exclude'] ) );

		$args = array(
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
			'post_type'      => 'attachment',
			'post_parent'    => $post->ID,
			'post_mime_type' => 'image',
			'post_status'    => null,
			'numberposts'    => - 1,
		);

		$attachments = get_posts( $args );

		if ( $attachments ) {
			$content = '<div id="galleria-' . $post->ID . '">';

			foreach ( $attachments as $attachment ) {
				if ( in_array( $attachment->ID, $exclude ) ) {
					continue;
				}

				$url = wp_get_attachment_image_src( $attachment->ID, apply_filters( 'wzslider_image_size', 'large', $post->ID ) );
				$url = $url[0];

				$big = wp_get_attachment_image_src( $attachment->ID, apply_filters( 'wzslider_big_size', 'large', $post->ID ) );
				$big = $big[0];

				$thumb = wp_get_attachment_image_src( $attachment->ID, apply_filters( 'wzslider_thumbnail_size', 'wzslider-thumbnail', $post->ID ) );
				$thumb = $thumb[0];

				$alt   = $attachment->post_content;
				$title = apply_filters( 'the_title', $attachment->post_title );

				$content .= '<a href="' . $url . '"><img title="' . $title . '" alt="' . $alt . '" src="' . $thumb . '" data-big="' . $big . '"></a>';
			}

			$content .= '</div>';
		}

		self::$galleries[] = array(
			"id"      => $post->ID,
			"options" => self::$scriptAtts
		);

		self::$scriptAtts = "";
		self::$atts       = "";

		return $content;
	}

	static public function loadStatic() {
		wp_enqueue_script( 'galleria', WPZOOM_Shortcodes_Plugin_Init::$assets_path . '/js/galleria.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'wzslider', WPZOOM_Shortcodes_Plugin_Init::$assets_path . '/js/wzslider.js', array( 'jquery' ), null, true );
	}

	static public function loadStyles() {
		wp_register_style( 'wzslider', WPZOOM_Shortcodes_Plugin_Init::$assets_path . '/css/wzslider.css' );
		wp_enqueue_style( 'wzslider' );
	}

	static public function galleriaScript() {
		$script = '<script>(function($){$(document).ready(function(){';

		foreach ( self::$galleries as $galleria ) {
			$id      = $galleria['id'];
			$options = $galleria['options'];
			$script .= "$('#galleria-$id').galleria({{$options}});";
		}

		$script .= '});})(jQuery);</script>';

		// fire
		echo $script;
	}

	static public function check( $posts ) {
		if ( empty( $posts ) ) {
			return $posts;
		}

		// $found = false;

		// foreach ($posts as $post) {
		//     if (stripos($post->post_content, '[wzslider') !== false) {
		//         $found = true;
		//     }

		//     break;
		// }

		$found = true;

		if ( $found ) {
			add_action( 'wp_footer', 'wpz_plugin_wzslider::galleriaScript' );

			add_action( 'wp_enqueue_scripts', 'wpz_plugin_wzslider::loadStatic' );
			add_action( 'wp_enqueue_scripts', 'wpz_plugin_wzslider::loadStyles' );
		}

		return $posts;
	}
}

add_action( 'the_posts', 'wpz_plugin_wzslider::check' );

// Adding shortcode button to TynyMCE editor

function wpz_plugin_add_slider_button() {
	if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
		return;
	}

	if ( get_user_option( 'rich_editing' ) == 'true' ) {
		add_filter( 'mce_external_plugins', 'wpz_plugin_add_slider_tinymce_plugin' );
		add_filter( 'mce_buttons', 'wpz_plugin_register_slider_button' );
	}
}

add_action( 'init', 'wpz_plugin_add_slider_button' );


function wpz_plugin_register_slider_button( $buttons ) {
	array_push( $buttons, "|", "wzslider" );

	return $buttons;
}

function wpz_plugin_add_slider_tinymce_plugin( $plugin_array ) {
	$plugin_array['wzslider'] = WPZOOM_Shortcodes_Plugin_Init::$assets_path . '/js/wzslider_button.js';

	return $plugin_array;
}

function wpz_plugin_wzslider_refresh_mce( $ver ) {
	$ver += 3;

	return $ver;
}

add_filter( 'tiny_mce_version', 'wpz_plugin_wzslider_refresh_mce' );
