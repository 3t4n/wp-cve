<?php
/**
 * Podcast player utility functions.
 *
 * @link       https://www.vedathemes.com
 * @since      3.3.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 */

namespace Podcast_Player\Helper\Functions;

use Podcast_Player\Helper\Functions\Getters as Get_Fn;
use Podcast_Player\Helper\Functions\Validation as Validation_Fn;
use Podcast_Player\Frontend\Inc\Icon_Loader as Icons;
use Podcast_Player\Frontend\Inc\Instance_Counter;

/**
 * Podcast player utility functions.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 * @author     vedathemes <contact@vedathemes.com>
 */
class Markup {

	/**
	 * Constructor method.
	 *
	 * @since  3.3.0
	 */
	public function __construct() {}

	/**
	 * Get button markup.
	 *
	 * @since 3.3.0
	 *
	 * @param array  $attr    Button markup attributes.
	 * @param string $hlabel  Button visually hidden label.
	 * @param string $iconstr Button icon.
	 * @param string $label   Button visible label.
	 */
	public static function get_button_markup( $attr, $hlabel = '', $iconstr = '', $label = '' ) {
		$attributes = '';
		foreach ( $attr as $key => $val ) {
			$attributes .= sprintf( ' %s="%s"', esc_html( $key ), esc_attr( $val ) );
		}

		$label_markup = '';
		if ( $label ) {
			$label_markup = sprintf( '<span>%s</span>', esc_html( $label ) );
		}

		$hlabel_markup = '';
		if ( $hlabel ) {
			$hlabel_markup = sprintf( '<span class="ppjs__offscreen">%s</span>', esc_html( $hlabel ) );
		}

		$icon_markup = '';
		if ( $iconstr ) {

			// If multiple icons separated by space.
			$icons = explode( ' ', $iconstr );
			foreach ( $icons as $icon ) {
				$icon_markup .= self::get_icon( array( 'icon' => $icon ) );
			}
			$icon_markup = sprintf( '<span class="btn-icon-wrap">%s</span>', $icon_markup );
		}

		return sprintf( '<button %1$s>%2$s%3$s%4$s</button>', $attributes, $label_markup, $hlabel_markup, $icon_markup );
	}

	/**
	 * Get player markup.
	 *
	 * @since 3.3.0
	 *
	 * @param array $src      Podcast media src.
	 * @param int   $instance current podcast player instance.
	 */
	public static function get_player_markup( $src, $instance ) {
		if ( ! Validation_Fn::is_valid_url( $src ) ) {
			$option = get_option( 'pp_media_src_index' );
			if ( $src && $option && is_array( $option ) && isset( $option[ $src ] ) ) {
				$src = $option[ $src ];
			} else {
				return '';
			}
		}

		// Strip querystring variables to get media type.
		$src_url = preg_replace( '/\?.*/', '', $src );

		$html       = '';
		$media_type = Get_Fn::get_media_type( $src );
		$mime_type  = wp_check_filetype( $src_url, wp_get_mime_types() );
		$mime_type  = $mime_type['type'];

		if ( 'audio' === $media_type ) {
			$html = self::get_audio_markup( $src, $instance, $mime_type );
		} elseif ( 'video' === $media_type ) {
			$html = self::get_video_markup( $src, $instance, $mime_type );
			$inst_class = Instance_Counter::get_instance();
			$inst_class->set_vcast(true);
		}

		return $html;
	}

	/**
	 * Get audio player markup.
	 *
	 * @since 3.3.0
	 *
	 * @param array  $src      Podcast media src.
	 * @param int    $instance current podcast player instance.
	 * @param string $type     Media mime type.
	 */
	public static function get_audio_markup( $src, $instance, $type ) {
		$class = 'pp-podcast-episode';
		if ( 'yes' === Get_Fn::get_plugin_option( 'hide_data' ) ) {
			$class .= ' hide-audio';
		}
		$html_atts = array(
			'id'      => 'pp-podcast-' . esc_attr( $instance ) . '-player',
			'preload' => 'none',
			'class'   => $class,
			'style'   => 'width: 100%;',
		);

		$attr_strings = array();
		foreach ( $html_atts as $k => $v ) {
			$attr_strings[] = $k . '="' . esc_attr( $v ) . '"';
		}

		// Do not change src for custom dynamic message.
		if ( false === strpos( $instance, '-amsg' ) ) {
			$src = esc_attr( apply_filters( 'podcast_player_mask_audio_url', $src ) );
		} else {
			$src = esc_attr( esc_url( $src ) );
		}

		$html  = sprintf( '<audio %s controls="controls">', join( ' ', $attr_strings ) );
		$html .= sprintf( '<source type="%s" src="%s" />', $type, $src );
		$html .= '</audio>';
		return $html;
	}

	/**
	 * Get video player markup.
	 *
	 * @since 3.3.0
	 *
	 * @param array  $src      Podcast media src.
	 * @param int    $instance current podcast player instance.
	 * @param string $type     Media mime type.
	 */
	public static function get_video_markup( $src, $instance, $type ) {
		$width  = isset( $GLOBALS['content_width'] ) ? $GLOBALS['content_width'] : 800;
		$height = 0.5625 * $width;

		$html_atts = array(
			'id'      => 'pp-podcast-' . absint( $instance ) . '-player',
			'preload' => 'none',
			'class'   => 'pp-podcast-episode',
			'width'   => $width,
			'height'  => $height,
		);

		$attr_strings = array();
		foreach ( $html_atts as $k => $v ) {
			$attr_strings[] = $k . '="' . esc_attr( $v ) . '"';
		}

		$html  = sprintf( '<video %s controls="controls">', join( ' ', $attr_strings ) );
		$html .= sprintf( '<source type="%s" src="%s" />', $type, esc_url( $src ) );
		$html .= '</video>';
		$html  = sprintf(
			'<div style="width: %dpx;" class="wp-video">%s</div>',
			$html_atts['width'],
			$html
		);
		return $html;
	}

	/**
	 * Display font icon SVG markup.
	 *
	 * @param array $args {
	 *     Parameters needed to display an SVG.
	 *
	 *     @type string $icon  Required SVG icon filename.
	 *     @type string $title Optional SVG title.
	 *     @type string $desc  Optional SVG description.
	 * }
	 */
	public static function the_icon( $args = array() ) {
		echo self::get_icon( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Return font icon SVG markup.
	 *
	 * This function incorporates code from Twenty Seventeen WordPress Theme,
	 * Copyright 2016-2017 WordPress.org. Twenty Seventeen is distributed
	 * under the terms of the GNU GPL.
	 *
	 * @param array $args {
	 *     Parameters needed to display an SVG.
	 *
	 *     @type string $icon  Required SVG icon filename.
	 *     @type string $title Optional SVG title.
	 *     @type string $desc  Optional SVG description.
	 * }
	 * @return string Font icon SVG markup.
	 */
	public static function get_icon( $args = array() ) {
		// Make sure $args are an array.
		if ( empty( $args ) ) {
			return esc_html__( 'Please define default parameters in the form of an array.', 'podcast-player' );
		}

		// Define an icon.
		if ( false === array_key_exists( 'icon', $args ) ) {
			return esc_html__( 'Please define an SVG icon filename.', 'podcast-player' );
		}

		// Add icon to icon loader array.
		$loader = Icons::get_instance();
		$loader->add( $args['icon'] );

		// Set defaults.
		$defaults = array(
			'icon'     => '',
			'title'    => '',
			'desc'     => '',
			'fallback' => false,
		);

		// Parse args.
		$args = wp_parse_args( $args, $defaults );

		// Set aria hidden.
		$aria_hidden = ' aria-hidden="true"';

		// Set ARIA.
		$aria_labelledby = '';

		/*
		* Podcast Player doesn't use the SVG title or description attributes; non-decorative icons are
		* described with .ppjs__offscreen. However, child themes can use the title and description
		* to add information to non-decorative SVG icons to improve accessibility.
		*
		* Example 1 with title: <?php echo podcast_player_get_svg( [ 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ) ] ); ?>
		*
		* Example 2 with title and description: <?php echo podcast_player_get_svg( [ 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ), 'desc' => __( 'This is the description', 'textdomain' ) ] ); ?>
		*
		* See https://www.paciellogroup.com/blog/2013/12/using-aria-enhance-svg-accessibility/.
		*/
		if ( $args['title'] ) {
			$aria_hidden     = '';
			$unique_id       = uniqid();
			$aria_labelledby = ' aria-labelledby="title-' . $unique_id . '"';

			if ( $args['desc'] ) {
				$aria_labelledby = ' aria-labelledby="title-' . $unique_id . ' desc-' . $unique_id . '"';
			}
		}

		// Begin SVG markup.
		$svg = '<svg class="icon icon-' . esc_attr( $args['icon'] ) . '"' . $aria_hidden . $aria_labelledby . ' role="img" focusable="false">';

		// Display the title.
		if ( $args['title'] ) {
			$svg .= '<title id="title-' . $unique_id . '">' . esc_html( $args['title'] ) . '</title>';

			// Display the desc only if the title is already set.
			if ( $args['desc'] ) {
				$svg .= '<desc id="desc-' . $unique_id . '">' . esc_html( $args['desc'] ) . '</desc>';
			}
		}

		/*
		* Display the icon.
		*
		* The whitespace around `<use>` is intentional - it is a work around to a keyboard navigation bug in Safari 10.
		*
		* See https://core.trac.wordpress.org/ticket/38387.
		*/
		$svg .= ' <use href="#icon-' . esc_attr( $args['icon'] ) . '" xlink:href="#icon-' . esc_attr( $args['icon'] ) . '"></use> ';

		// Add some markup to use as a fallback for browsers that do not support SVGs.
		if ( $args['fallback'] ) {
			$svg .= '<span class="svg-fallback icon-' . esc_attr( $args['icon'] ) . '"></span>';
		}

		$svg .= '</svg>';

		return $svg;
	}

	/**
	 * Get template markup for podcast player.
	 *
	 * Let theme override the core template.
	 *
	 * @since  5.6.0
	 *
	 * @param string $path  Template relative path.
	 * @param string $name  Template file name without .php suffix.
	 * @param bool   $ispro Is called from the pro plugin.
	 */
	public static function get_template_markup( $path, $name, $ispro = false ) {
		$markup   = '';
		$template = self::locate_template( $path, $name, $ispro );
		if ( $template ) {
			ob_start();
			require $template;
			$markup .= ob_get_clean();
		}

		$markup = self::remove_breaks( $markup );
		return $markup;
	}

	/**
	 * Locate template part for podcast player.
	 *
	 * Let theme override the core template.
	 *
	 * @since  1.0.0
	 *
	 * @param string $path  Template relative path.
	 * @param string $name  Template file name without .php suffix.
	 * @param bool   $ispro Is called from the pro plugin.
	 */
	public static function locate_template( $path, $name, $ispro = false ) {
		$located   = '';
		$templates = array(
			get_stylesheet_directory() . "/podcast-player/{$path}/{$name}.php",
			get_stylesheet_directory() . "/podcast-player/{$name}.php",
		);

		if ( $ispro && defined( 'PP_PRO_DIR' ) ) {
			$templates = array_merge(
				$templates,
				array(
					PP_PRO_DIR . "inc/templates/{$path}/{$name}.php",
					PP_PRO_DIR . "inc/templates/{$name}.php",
				)
			);
		} else {
			$templates = array_merge(
				$templates,
				array(
					PODCAST_PLAYER_DIR . "frontend/templates/{$path}/{$name}.php",
					PODCAST_PLAYER_DIR . "frontend/templates/{$name}.php",
				)
			);
		}

		foreach ( $templates as $template ) {
			if ( file_exists( $template ) ) {
				$located = $template;
				break;
			}
		}

		/**
		 * Locate a template part for podcast player.
		 *
		 * @since 3.3.0
		 *
		 * @param string $located Located template file.
		 * @param string $path Template relative path.
		 * @param string $name Template file name.
		 */
		return apply_filters( 'podcast_player_locate_template', $located, $path, $name );
	}

	/**
	 * Locate admin template part for podcast player.
	 *
	 * Let pro override the core template.
	 *
	 * @since  1.0.0
	 *
	 * @param string $path  Template relative path.
	 */
	public static function locate_admin_template( $path ) {
		$located   = '';
		$templates = array();

		if (
			defined( 'PP_PRO_DIR' ) &&
			defined( 'PP_REQUIRED' ) &&
			defined( 'PODCAST_PLAYER_VERSION' ) &&
			version_compare( PODCAST_PLAYER_VERSION, PP_REQUIRED, '>=')
		) {
			$templates = array(
				PP_PRO_DIR . "admin/templates/{$path}.php",
				PODCAST_PLAYER_DIR . "backend/admin/templates/{$path}.php",
			);
		} else {
			$templates = array( PODCAST_PLAYER_DIR . "backend/admin/templates/{$path}.php" );
		}

		foreach ( $templates as $template ) {
			if ( file_exists( $template ) ) {
				$located = $template;
				break;
			}
		}

		/**
		 * Locate a template part for podcast player.
		 *
		 * @since 3.3.0
		 *
		 * @param string $located Located template file.
		 * @param string $path Template relative path.
		 * @param string $name Template file name.
		 */
		return apply_filters( 'podcast_player_locate_admin_template', $located, $path );
	}

	/**
	 * Remove breaks from HTML tags.
	 *
	 * @since 5.1.0
	 *
	 * @param string $string String to be processed.
	 */
	public static function remove_breaks( $string ) {
		$string = str_replace( PHP_EOL, '', $string );
		return preg_replace( '~>\\s+<~m', '><', $string );
	}
}
