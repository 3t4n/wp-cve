<?php
defined( 'WPINC' ) OR exit;

/*
  Plugin Name: Prezi Embedder
  Plugin URI: https://wordpress.org/plugins/prezi-embedder/
  Description: Allows for embedding the newest iframe-based Prezis from <a href="http://www.prezi.com/recommend/qv1ms7qvtplw">prezi.com</a> using a simple shortcode [prezi id="&lt;your id here&gt;"].
  Version: 2.1
  Author: Dan Rossiter
  Author URI: http://danrossiter.org/
  License: GPLv2
  Text Domain: prezi-embedder
 */

// shortcode
add_shortcode( 'prezi', array( 'PreziEmbedder', 'doShortcode' ) );

// I18n
add_action( 'plugins_loaded', array( 'PreziEmbedder', 'loadTextDomain' ) );

class PreziEmbedder {
	private static $attr_err, $bool_err, $comment, $int_err, $min_req, $alignments;

	/**
	 * Initializes static values for PreziEmbedder.
	 */
	public static function init() {
		// run a maximum of one time per page load
		if ( ! isset( self::$comment ) ) {
			self::$comment  =
				'<!-- ' .
				__( 'Generated using Prezi Embedder. Get yours here:', 'prezi-embedder' ) .
				' http://wordpress.org/plugins/prezi-embedder/ -->' . PHP_EOL;
			self::$attr_err =
				__( 'Error: The %1$s attribute provided does not look right. You entered %1$s=%2$s. ', 'prezi-embedder' );
			self::$bool_err =
				__( 'Error: The %1$s attribute may only be %2$s or %3$s. You entered %1$s=%4$s', 'prezi-embedder' );
			self::$int_err  =
				__( 'Error: The %1$s attribute must be an integer. You entered %1$s=%2$s. ', 'prezi-embedder' );
			self::$min_req  =
				__( 'Error: You must, at minimum include an id attribute:', 'prezi-embedder' );

			self::$alignments = array(
				'none'   => '',
				'left'   => "class='alignleft'",
				'right'  => "class='alignright'",
				'center' => "class='aligncenter'"
			);
		}
	}

	/**
	 * Does the shortcode.
	 *
	 * @param array $atts
	 *
	 * @return string The embed code on success or error string(s) on failure.
	 */
	public static function doShortcode( $atts ) {
		static $ptn = '#.*prezi.com/([^/]+).*#';
		static $template = '<iframe src="//prezi.com/embed/%s/?bgcolor=ffffff&amp;lock_to_path=%u&amp;autoplay=%u&amp;autohide_ctrls=%u&amp;html5=%u" width="%u" height="%u" frameBorder="0" %s webkitAllowFullScreen="" mozAllowFullscreen="" allowfullscreen=""></iframe>';

		// get arguments from user
		extract( shortcode_atts(
			array(
				'id'             => null,
				'width'          => 500,
				'height'         => 400,
				'lock_to_path'   => 0,
				'autoplay'       => 0,
				'autohide_ctrls' => 0,
				'html5'          => 1,
				'align'          => 'none'
			),
			$atts ) );

		$err = '';

		// validate & sanitize input
		if ( ! ( $id = preg_replace( $ptn, '$1', $id ) ) ) {
			$err .= sprintf( self::$attr_err, 'id', $id );
		}

		if ( (int) $width != $width || (int) $width < 1 ) {
			$err .= sprintf( self::$int_err, 'width', $width );
		} else {
			$width = (int) $width;
		}

		if ( (int) $height != $height || (int) $height < 1 ) {
			$err .= sprintf( self::$int_err, 'height', $height );
		} else {
			$height = (int) $height;
		}

		if ( $lock_to_path != 0 && $lock_to_path != 1 ) {
			$err .= sprintf( self::$bool_err, 'lock_to_path', 0, 1, $lock_to_path );
		} else {
			$lock_to_path = (int) $lock_to_path;
		}

		if ( $autoplay != 0 && $autoplay != 1 ) {
			$err .= sprintf( self::$bool_err, 'autoplay', 0, 1, $autoplay );
		} else {
			$autoplay = (int) $autoplay;
		}

		if ( $autohide_ctrls != 0 && $autohide_ctrls != 1 ) {
			$err .= sprintf( self::$bool_err, 'autohide_ctrls', 0, 1, $autohide_ctrls );
		} else {
			$autohide_ctrls = (int) $autohide_ctrls;
		}

		if ( $html5 != 0 && $html5 != 1 ) {
			$err .= sprintf( self::$bool_err, 'html5', 0, 1, $html5 );
		} else {
			$html5 = (int) $html5;
		}

		$align = strtolower( $align );
		if ( ! array_key_exists( $align, self::$alignments ) ) {
			$err .= sprintf( self::$attr_err, 'align', $align );
		} else {
			$align = self::$alignments[ $align ];
		}

		if ( empty( $id ) ) {
			$err .= self::$min_req . ' [prezi id=\'&lt;Prezi ID&gt;\'] ';
		}

		// tell user what they did wrong
		if ( $err ) {
			return $err;
		}

		return self::$comment . sprintf( $template, $id, $lock_to_path, $autoplay, $autohide_ctrls, $html5, $width, $height, $align );
	}

	/**
	 * Load I18n
	 */
	public static function loadTextDomain() {
		load_plugin_textdomain( 'prezi-embedder', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

PreziEmbedder::init();