<?php

use CoolPlugins\GutenbergBlocks\Registration;

/**
 * Class Cfb_Block
 */
class Cfb_Block {
	/**
	 * Singleton.
	 *
	 * @var Cfb_Block|null Class object.
	 */
	protected static $instance = null;

	/**
	 * Method to define hooks needed.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function init() {
		// Add action to autoload classes.
		add_action( 'init', array( $this, 'autoload_classes' ), 9 );
		// Add filter to modify script loader tag.
		add_filter( 'script_loader_tag', array( $this, 'filter_script_loader_tag' ), 10, 2 );
		// Add filter to modify safe style CSS properties.
		add_filter( 'safe_style_css', array( $this, 'used_css_properties' ), 99 );
		// Add filter to modify allowed HTML properties.
		add_filter( 'wp_kses_allowed_html', array( $this, 'used_html_properties' ), 10, 2 );
	}

	public function required_files() {
		require_once CFB_DIR_PATH . '/includes/cfb-block/inc/class-registration.php';
		require_once CFB_DIR_PATH . '/includes/cfb-block/inc/class-cfb-css-base.php';
		require_once CFB_DIR_PATH . '/includes/cfb-block/inc/css/class-block-frontend.php';
		require_once CFB_DIR_PATH . '/includes/cfb-block/inc/css/class-css-handler.php';
		require_once CFB_DIR_PATH . 'includes/cfb-block/inc/css/class-css-utility.php';
		require_once CFB_DIR_PATH . '/includes/cfb-block/inc/css/class-flip-css.php';
	}

	/**
	 * Autoload classes for each block.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function autoload_classes() {
		$this->required_files();
		load_plugin_textdomain( 'cfb-blocks', false, basename( CFB_DIR_PATH ) . '/languages/' );

		$classnames = array(
			'CoolPlugins\GutenbergBlocks\Registration',
			'CoolPlugins\GutenbergBlocks\Block_Frontend',
			'CoolPlugins\GutenbergBlocks\CSS_Handler',
		);

		$classnames = apply_filters( 'cfb_blocks_autoloader', $classnames );

		foreach ( $classnames as $classname ) {
			$classname = new $classname();

			if ( method_exists( $classname, 'instance' ) ) {
				$classname->instance();
			}
		}
	}

	/**
	 * Adds async/defer attributes to enqueued / registered scripts.
	 *
	 * If #12009 lands in WordPress, this function can no-op since it would be handled in core.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12009
	 *
	 * @param string $tag The script tag.
	 * @param string $handle The script handle.
	 *
	 * @return string Script HTML string.
	 */
	public function filter_script_loader_tag( $tag, $handle ) {
		foreach ( array( 'async', 'defer' ) as $attr ) {
			if ( ! wp_scripts()->get_data( $handle, $attr ) ) {
				continue;
			}
			// Prevent adding attribute when already added in #12009.
			if ( ! preg_match( ":\s$attr(=|>|\s):", $tag ) ) {
				$tag = preg_replace( ':(?=></script>):', " $attr", $tag, 1 );
			}
			// Only allow async or defer, not both.
			break;
		}

		return $tag;
	}

	/**
	 * Used CSS properties
	 *
	 * @param array $attr Array to check.
	 *
	 * @return array
	 * @since   2.0.0
	 * @access  public
	 */
	public function used_css_properties( $attr ) {
		$props = array(
			'background-attachment',
			'background-position',
			'background-repeat',
			'background-size',
			'border-radius',
			'border-top-left-radius',
			'border-top-right-radius',
			'border-bottom-right-radius',
			'border-bottom-left-radius',
			'box-shadow',
			'display',
			'justify-content',
			'mix-blend-mode',
			'opacity',
			'text-shadow',
			'text-transform',
			'transform',
		);

		$list = array_merge( $props, $attr );

		return $list;
	}

	/**
	 * Used HTML properties
	 *
	 * @param array  $tags Allowed HTML tags.
	 * @param string $context Context.
	 *
	 * @return array
	 * @since   2.0.11
	 * @access  public
	 */
	public function used_html_properties( $tags, $context ) {
		// Check if context is post.
		if ( 'post' !== $context ) {
			return $tags;
		}

		$global_attributes = array(
			'aria-describedby' => true,
			'aria-details'     => true,
			'aria-label'       => true,
			'aria-labelledby'  => true,
			'aria-hidden'      => true,
			'class'            => true,
			'data-*'           => true,
			'dir'              => true,
			'id'               => true,
			'lang'             => true,
			'style'            => true,
			'title'            => true,
			'role'             => true,
			'xml:lang'         => true,
		);

		if ( isset( $tags['div'] ) ) {
			$tags['div']['name'] = true;
		}

		if ( isset( $tags['form'] ) ) {
			$tags['form']['class'] = true;
		} else {
			$tags['form'] = array(
				'class' => true,
			);
		}

		if ( ! isset( $tags['svg'] ) ) {
			$tags['svg'] = array_merge(
				array(
					'xmlns'   => true,
					'width'   => true,
					'height'  => true,
					'viewbox' => true,
				),
				$global_attributes
			);
		}

		if ( ! isset( $tags['g'] ) ) {
			$tags['g'] = array( 'fill' => true );
		}

		if ( ! isset( $tags['title'] ) ) {
			$tags['title'] = array( 'title' => true );
		}

		if ( ! isset( $tags['path'] ) ) {
			$tags['path'] = array(
				'd'    => true,
				'fill' => true,
			);
		}

		if ( ! isset( $tags['lottie-player'] ) ) {
			$tags['lottie-player'] = array_merge(
				array(
					'autoplay'   => true,
					'hover'      => true,
					'loop'       => true,
					'count'      => true,
					'speed'      => true,
					'direction'  => true,
					'trigger'    => true,
					'mode'       => true,
					'background' => true,
					'src'        => true,
					'width'      => true,
				),
				$global_attributes
			);
		}

		if ( ! isset( $tags['dotlottie-player'] ) ) {
			$tags['dotlottie-player'] = array_merge(
				array(
					'autoplay'   => true,
					'loop'       => true,
					'count'      => true,
					'speed'      => true,
					'direction'  => true,
					'trigger'    => true,
					'mode'       => true,
					'background' => true,
					'src'        => true,
					'width'      => true,
				),
				$global_attributes
			);
		}

		if ( ! isset( $tags['o-dynamic'] ) ) {
			$tags['o-dynamic'] = $global_attributes;
		}

		if ( ! isset( $tags['o-dynamic-link'] ) ) {
			$tags['o-dynamic-link'] = $global_attributes;
		}

		if ( ! isset( $tags['input'] ) ) {
			$tags['input'] = array();
		}

		$tags['input'] = array_merge(
			$tags['input'],
			array(
				'type'        => true,
				'name'        => true,
				'required'    => true,
				'placeholder' => true,
			),
			$global_attributes
		);

		$textarea = array();

		if ( ! isset( $tags['textarea'] ) ) {
			$tags['textarea'] = array();
		}

		$tags['textarea'] = array_merge(
			$tags['textarea'],
			array(
				'name'        => true,
				'required'    => true,
				'placeholder' => true,
				'rows'        => true,
			),
			$global_attributes
		);

		return $tags;
	}

	/**
	 * Singleton method.
	 *
	 * @static
	 *
	 * @return  Cfb_Block
	 * @since   1.0.0
	 * @access  public
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
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
	 * @since   1.0.0
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', '1.0.0' );
	}
}
