<?php
/**
 *  Common logic for CSS handling class.
 *
 * @package CoolPlugins\GutenbergBlocks
 */

namespace CoolPlugins\GutenbergBlocks;

/**
 * Class Cfb_CSS_Base
 */
class Cfb_CSS_Base {

	/**
	 * The namespace under which the blocks are registered.
	 *
	 * @var string
	 */
	protected $library_prefix = 'cp';

	/**
	 * Rest route namespace.
	 *
	 * @var string
	 */
	public $namespace = 'cfb/';

	/**
	 * Rest route version.
	 *
	 * @var string
	 */
	public $version = 'v1';

	/**
	 * The namespace under which the block classees are saved.
	 *
	 * @var array
	 */
	protected static $blocks_classes = array();

	/**
	 * The namespace under which the fonts are saved.
	 *
	 * @var array
	 */
	protected static $google_fonts = array();

	/**
	 * Indicates whether the Font Awesome library is loaded.
	 *
	 * @var bool
	 */
	protected static $font_awesome_lobrary_load = false;

	/**
	 * Cfb_CSS_Base constructor.
	 *
	 * @since   1.3.0
	 * @access  public
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'autoload_block_classes' ), 99 );
	}

	/**
	 * Autoload classes for each block.
	 *
	 * @since   1.3.0
	 * @access  public
	 */
	public function autoload_block_classes() {
		self::$blocks_classes = array(
			'\CoolPlugins\GutenbergBlocks\CSS\Blocks\Flip_CSS',
		);

		self::$blocks_classes = apply_filters( 'cfb_blocks_register_css', self::$blocks_classes );
	}

	/**
	 * Check if string is empty without accepting zero
	 *
	 * @param string $var Var to check.
	 *
	 * @return bool
	 * @since   1.3.1
	 * @access  public
	 */
	public function is_empty( $var ) {
		return empty( $var ) && 0 !== $var;
	}

	/**
	 * Get block attribute value with default
	 *
	 * @param mixed $attr Attributes.
	 * @param mixed $default Default value.
	 *
	 * @return mixed
	 * @since   1.3.0
	 * @access  public
	 */
	public function get_attr_value( $attr, $default = 'unset' ) {
		if ( ! $this->is_empty( $attr ) ) {
			return $attr;
		} else {
			return $default;
		}
	}

	/**
	 * Load Font Awesome library if content type includes icon.
	 *
	 * @param array $attr Attributes array.
	 * @return void
	 * @since   1.3.0
	 * @access  public
	 */
	public function font_awesome_library( $attr ) {
		// Check if content type includes icon.
		$back_content_type = isset( $attr['backContentType'] ) ? $attr['backContentType'] : 'none';
		$front_icon        = ! isset( $attr['frontContentType'] ) ? true : false;
		$cfb_flipbox       = isset( $attr['cfbBlockFlipboxVersion'] ) ? true : false;
		$data              = array();
		$data['back']      = $back_content_type;
		$data['front']     = $front_icon;
		if ( $cfb_flipbox && ( 'icon' === $back_content_type || $front_icon ) ) {
			self::$font_awesome_lobrary_load = true;
		}
	}

	/**
	 * Get Google Fonts
	 *
	 * @param array $attr Attr values.
	 *
	 * @since   1.3.0
	 * @access  public
	 */
	public function get_google_fonts( $attr ) {
		// $data         = array( 'font' => self::$google_fonts );
		$sides      = array( 'front', 'back' );
		$fonts_tags = array( 'Title', 'Desc' );
		// $data['attr'] = $attr;
		foreach ( $sides as $side ) {
			foreach ( $fonts_tags as $font_tag ) {
				$font_enable = $side . $font_tag . 'GoogleFont';
				if ( array_key_exists( $font_enable, $attr ) && $attr[ $font_enable ] ) {
					$font_family = $attr[ $side . $font_tag . 'FontFamily' ];
					$font_weight = $attr[ $side . $font_tag . 'FontWeight' ];
					$font        = $font_family . $font_weight;
					if ( ! array_key_exists( $font, self::$google_fonts ) ) {
						self::$google_fonts[ $font ] = array(
							'family' => $font_family,
							'weight' => $font_weight,
						);
					}
				}
			}
		}
	}

	/**
	 * Convert HEX to RGBA.
	 *
	 * @param string   $color Color data.
	 * @param bool|int $opacity Opacity status.
	 *
	 * @return mixed
	 * @since   1.3.0
	 * @access  public
	 */
	public static function hex2rgba( $color, $opacity = false ) {
		$default = 'rgb(0,0,0)';

		if ( empty( $color ) ) {
			return $default;
		}

		if ( '#' == $color[0] ) {
			$color = substr( $color, 1 );
		}

		if ( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		$rgb = array_map( 'hexdec', $hex );

		if ( $opacity >= 0 ) {
			if ( abs( $opacity ) > 1 ) {
				$opacity = 1.0;
			}
			$output = 'rgba( ' . implode( ',', $rgb ) . ',' . $opacity . ' )';
		} else {
			$output = 'rgb( ' . implode( ',', $rgb ) . ' )';
		}

		return $output;
	}

	/**
	 * Get Blocks CSS
	 *
	 * @param int $post_id Post id.
	 * @return string|void
	 * @since   1.3.0
	 * @access  public
	 */
	public function get_blocks_css( $post_id ) {
		if ( ! function_exists( 'has_blocks' ) ) {
			return;
		}

		$content = get_post_field( 'post_content', $post_id );
		$blocks  = parse_blocks( $content );

		if ( ! is_array( $blocks ) || empty( $blocks ) ) {
			return;
		}

		$animations = boolval( preg_match( '/\banimated\b/', $content ) );
		return $this->cycle_through_static_blocks( $blocks, $animations );
	}

	/**
	 * Get Reusable Blocks CSS
	 *
	 * @param int $post_id Post id.
	 * @return string|void
	 * @since   1.3.0
	 * @access  public
	 */
	public function get_reusable_block_css( $post_id ) {
		$reusable_block = get_post( $post_id );
		if ( ! $reusable_block || 'wp_block' !== $reusable_block->post_type ) {
			return;
		}

		if ( 'publish' !== $reusable_block->post_status || ! empty( $reusable_block->post_password ) ) {
			return;
		}

		$blocks     = parse_blocks( $reusable_block->post_content );
		$animations = boolval( preg_match( '/\banimated\b/', $reusable_block->post_content ) );
		return $this->cycle_through_static_blocks( $blocks, $animations );
	}

	/**
	 * Cycle thorugh Static Blocks
	 *
	 * @param array $blocks List of blocks.
	 * @param bool  $animations To check for animations or not.
	 *
	 * @return string Style.
	 * @since   1.3.0
	 * @access  public
	 */
	public function cycle_through_static_blocks( $blocks, $animations = true ) {
		$style = '';
		foreach ( $blocks as $block ) {
			foreach ( self::$blocks_classes as $classname ) {
				$path = new $classname();
				if ( method_exists( $path, 'render_css' ) && isset( $path->block_prefix ) ) {
					if ( ( isset( $path->library_prefix ) ? $path->library_prefix : $this->library_prefix ) . '/' . $path->block_prefix === $block['blockName'] ) {
						$style .= $path->render_css( $block );
					}
				}
			}
			$custom_css = apply_filters( 'cfb_blocks_css', $block );

			if ( is_string( $custom_css ) ) {
				$style .= $custom_css;
			}

			if ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
				$style .= $this->cycle_through_static_blocks( $block['innerBlocks'], false );
			}
		}
		return $style;
	}

	/**
	 * Cycle thorugh Global Styles
	 *
	 * @return string Style.
	 * @since   2.0.0
	 * @access  public
	 */
	public function cycle_through_global_styles() {
		$style = '';
		foreach ( self::$blocks_classes as $classname ) {
			$path = new $classname();

			if ( method_exists( $path, 'render_global_css' ) ) {
				$style .= $path->render_global_css();
			}
		}

		return $style;
	}

	/**
	 * Check if an url points to an image by checking if the an image extension exists.
	 *
	 * @param string $url The url.
	 *
	 * @return bool
	 * @since   1.4.4
	 * @access  public
	 */
	public static function is_image_url( $url ) {
		return is_string( $url ) && ( preg_match( '/\.(jpeg|jpg|png|gif|svg|bmp|ico|tiff|webp)$/i', $url ) || preg_match( '/\/dynamic\/?.[^"]*/i', $url ) );
	}

	/**
	 * Method to return path to child class in a Reflective Way.
	 *
	 * @return  string
	 * @since   1.3.0
	 * @access  protected
	 */
	protected function get_dir() {
		return dirname( __FILE__ );
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @access  public
	 * @return  void
	 * @since   1.3.0
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @access  public
	 * @return  void
	 * @since   1.3.0
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', '1.0.0' );
	}
}
