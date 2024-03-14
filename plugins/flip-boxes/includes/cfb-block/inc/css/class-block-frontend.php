<?php
/**
 * Css handling logic.
 *
 * @package CoolPlugins\GutenbergBlocks\CSS
 */
namespace CoolPlugins\GutenbergBlocks;

use CoolPlugins\GutenbergBlocks\Cfb_CSS_Base;

/**
 * Class Block_Frontend
 */
class Block_Frontend extends Cfb_CSS_Base {

	/**
	 * The main instance var.
	 *
	 * @var Block_Frontend|null
	 */
	public static $instance = null;

	/**
	 * The namespace to check if excerpt exists.
	 *
	 * @var bool
	 */
	private $has_excerpt = false;

	/**
	 * The namespace to check if fonts exists.
	 *
	 * @var bool
	 */
	private $has_fonts = true;

	/**
	 * Inline CSS size.
	 *
	 * @var int
	 */
	private $total_inline_size = 0;

	/**
	 * Initialize the class
	 */
	public function init() {
		add_action( 'wp', array( $this, 'render_post_css' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_font_awesome' ), 19 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_google_fonts' ), 19 );
		add_action( 'wp_head', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_footer', array( $this, 'enqueue_global_styles' ) );
	}

	public function enqueue_font_awesome( $post_id = null ) {
		if ( ! is_singular() && ! $post_id ) {
			return;
		}

		$post_id              = $post_id ? $post_id : get_the_ID();
		$font_awesome_libaray = get_post_meta( $post_id, '_CoolPlugins_gutenberg_block_fontawesome_libraray', true );
		if ( $font_awesome_libaray ) {
			if ( ! wp_script_is( 'cfb-block-fontawesome', 'enqueued' ) ) {
				wp_enqueue_style( 'cfb-block-fontawesome', CFB_URL . 'assets/fontawesome/css/font-awesome.min.css', array(), CFB_VERSION ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			}
		}
	}

	/**
	 * Method to define hooks needed.
	 *
	 * @param int|null $post_id Post id.
	 *
	 * @since   1.3.0
	 * @access  public
	 */
	public function enqueue_google_fonts( $post_id = null ) {
		if ( ! is_singular() && ! $post_id ) {
			return;
		}

		$post_id    = $post_id ? $post_id : get_the_ID();
		$fonts_list = get_post_meta( $post_id, '_CoolPlugins_gutenberg_block_fonts', true );
		$content    = get_post_field( 'post_content', $post_id );
		$blocks     = parse_blocks( $content );
		if ( empty( $fonts_list ) ) {
			$this->has_fonts = false;
			return;
		}

		if ( count( $fonts_list ) > 0 ) {
			$fonts = $this->get_fonts( $fonts_list );
			if ( count( $fonts['fonts'] ) > 0 ) {
				if ( ! wp_script_is( 'cfb-block-google-fonts', 'enqueued' ) ) {
					wp_enqueue_style( 'cfb-block-google-fonts', $fonts['url'], array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
				}
			}
		}
	}

	/**
	 * Method to Get Fonts URL.
	 *
	 * @param array $fonts_list Fonts List.
	 *
	 * @since   2.0.5
	 * @access  public
	 */
	public function get_fonts( $fonts_list = array() ) {
		$fonts = array();
		foreach ( $fonts_list as $font ) {

			$item = str_replace( ' ', '+', $font['family'] );
			if ( isset( $font['weight'] ) && ! empty( $font['weight'] ) ) {
				$item .= ':wght@' . $font['weight'];
			}
			array_push( $fonts, $item );
		}

		$fonts_url = add_query_arg(
			array(
				'family'  => implode( '&family=', $fonts ),
				'display' => 'swap',
			),
			'https://fonts.googleapis.com/css2'
		);

		$fonts_url = apply_filters( 'cfb_blocks_google_fonts_url', $fonts_url );

		$obj = array(
			'fonts' => $fonts,
			'url'   => esc_url_raw( $fonts_url ),
		);

		return $obj;
	}

	/**
	 * Render server-side CSS
	 *
	 * @since   1.3.0
	 * @access  public
	 */
	public function render_post_css() {
		$id = 0;

		if ( is_singular() ) {
			// Enqueue main post attached style.
			$id = get_the_ID();
			$this->enqueue_styles();
		}

		// Enqueue styles for other posts that display the_content, if any.
		add_filter(
			'the_content',
			function ( $content ) use ( $id ) {
				$post_id = get_the_ID();

				if ( $this->has_excerpt || $id === $post_id ) {
					return $content;
				}

				$this->enqueue_styles( $post_id );
				$this->enqueue_font_awesome( $post_id );
				$this->enqueue_google_fonts( $post_id );

				return $content;
			}
		);
	}

	/**
	 * Enqueue CSS file
	 *
	 * @param int|null $post_id Post id.
	 *
	 * @since   1.3.0
	 * @access  public
	 */
	public function enqueue_styles( $post_id = null ) {
		$post_id = $post_id ? $post_id : get_the_ID();

		if ( ! function_exists( 'has_blocks' ) ) {
			return;
		}

		if ( ! has_blocks( $post_id ) ) {
			return;
		}

		if ( is_preview() ) {
			add_action(
				'wp_footer',
				function () use ( $post_id ) {
					return $this->get_post_css( $post_id );
				},
				'the_content' === current_filter() ? PHP_INT_MAX : 10
			);

			return;
		}

		if ( ! CSS_Handler::has_css_file( $post_id ) ) {
			if ( CSS_Handler::is_writable() ) {
				CSS_Handler::generate_css_file( $post_id );
			}

			add_action(
				'wp_footer',
				function () use ( $post_id ) {
					return $this->get_post_css( $post_id );
				},
				'the_content' === current_filter() ? PHP_INT_MAX : 10
			);

			return;
		}

		$file_url = CSS_Handler::get_css_url( $post_id );

		$file_name = basename( $file_url );

		$content = get_post_field( 'post_content', $post_id );

		$blocks = parse_blocks( $content );

		if ( is_array( $blocks ) ) {
			$this->enqueue_reusable_styles( $blocks );
		}

		$total_inline_limit = 20000;
		$total_inline_limit = apply_filters( 'styles_inline_size_limit', 20000 );

		$wp_upload_dir = wp_upload_dir( null, false );
		$basedir       = $wp_upload_dir['basedir'] . '/CoolPlugins-gutenberg/';
		$file_path     = $basedir . $file_name;
		$file_size     = filesize( $file_path );

		if ( $this->total_inline_size + $file_size < $total_inline_limit ) {
			add_action(
				'wp_footer',
				function () use ( $post_id ) {
					return $this->get_post_css( $post_id );
				},
				'the_content' === current_filter() ? PHP_INT_MAX : 10
			);

			$this->total_inline_size += (int) $file_size;
			return;
		}

		if ( 'the_content' === current_filter() ) {
			wp_enqueue_style( 'cfb-' . $file_name, $file_url, array(), CFB_VERSION );
			return;
		}

		add_action(
			'wp_footer',
			function () use ( $file_name, $file_url ) {
				wp_enqueue_style( 'cfb-' . $file_name, $file_url, array(), CFB_VERSION );
			}
		);
	}

	/**
	 * Enqueue CSS file for Reusable Blocks
	 *
	 * @param array $blocks List of blocks.
	 *
	 * @since   1.3.0
	 * @access  public
	 */
	public function enqueue_reusable_styles( $blocks ) {
		foreach ( $blocks as $block ) {
			if ( 'core/block' === $block['blockName'] && ! empty( $block['attrs']['ref'] ) ) {
				$this->enqueue_styles( $block['attrs']['ref'] );
			}

			if ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
				$this->enqueue_reusable_styles( $block['innerBlocks'] );
			}
		}
	}

	/**
	 * Get Post CSS
	 *
	 * @param int $post_id Post id.
	 *
	 * @since   1.3.0
	 * @access  public
	 */
	public function get_post_css( $post_id = null ) {
		$post_id = $post_id ? $post_id : get_the_ID();
		if ( function_exists( 'has_blocks' ) && has_blocks( $post_id ) ) {
			$css = $this->get_page_css_meta( $post_id );

			if ( empty( $css ) || is_preview() ) {
				$css = $this->get_page_css_inline( $post_id );
			}
			if ( empty( $css ) ) {
				return;
			}

			$style  = "\n" . '<style type="text/css" media="all">' . "\n";
			$style .= $css;
			$style .= "\n" . '</style>' . "\n";

			echo $style;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Get Blocks CSS from Meta
	 *
	 * @param int $post_id Post id.
	 *
	 * @return string
	 * @since   1.3.0
	 * @access  public
	 */
	public function get_page_css_meta( $post_id ) {
		$style = '';
		if ( function_exists( 'has_blocks' ) && has_blocks( $post_id ) ) {
			$style .= get_post_meta( $post_id, '_coolPlugins_gutenberg_block_styles', true );

			$content = get_post_field( 'post_content', $post_id );

			$blocks = parse_blocks( $content );

			if ( ! is_array( $blocks ) || empty( $blocks ) ) {
				return $style;
			}
		}

		return $style;
	}

	/**
	 * Get Blocks CSS Inline
	 *
	 * @param int $post_id Post id.
	 *
	 * @return string
	 * @since   1.3.0
	 * @access  public
	 */
	public function get_page_css_inline( $post_id ) {
		global $post;

		// Do an early return if the condition if ( function_exists( 'has_blocks' ) && has_blocks( $post_id ) ) { isn't met.
		if ( ! function_exists( 'has_blocks' ) || ! has_blocks( $post_id ) ) {
			return '';
		}

		if ( is_preview() && ( $post_id === $post->ID ) ) {
			$content = $post->post_content;
		} else {
			$content = get_post_field( 'post_content', $post_id );
		}

		$blocks = parse_blocks( $content );

		if ( ! is_array( $blocks ) || empty( $blocks ) ) {
			return '';
		}

		$animations = boolval( preg_match( '/\banimated\b/', $content ) );

		$css = $this->cycle_through_blocks( $blocks, $animations );

		return stripslashes( $css );
	}

	/**
	 * Cycle thorugh Blocks
	 *
	 * @param array $blocks List of blocks.
	 * @param bool  $animations To check for animations or not.
	 *
	 * @return string Block styles.
	 * @since   1.3.0
	 * @access  public
	 */
	public function cycle_through_blocks( $blocks, $animations ) {
		$style  = '';
		$style .= $this->cycle_through_static_blocks( $blocks, $animations );
		// $style .= $this->cycle_through_reusable_blocks( $blocks );

		return $style;
	}

	/**
	 * Enqueue global defaults
	 *
	 * @since   2.0.0
	 * @access  public
	 */
	public function enqueue_global_styles() {
		$css = $this->cycle_through_global_styles();

		if ( empty( $css ) ) {
			return;
		}

		$style  = "\n" . '<style type="text/css" media="all">' . "\n";
		$style .= $css;
		$style .= "\n" . '</style>' . "\n";

		echo $style;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Filter to let third-party products hook cfb styles.
	 *
	 * @since   2.0.1
	 * @access  public
	 */
	public function enqueue_assets() {
		$posts = apply_filters( 'CoolPlugins_gutenberg_blocks_enqueue_assets', array() );

		if ( 0 < count( $posts ) ) {
			foreach ( $posts as $post ) {
				$class = Registration::instance();
				$class->enqueue_block_styles( $post );
				$this->enqueue_styles( $post );
			}
		}
	}

	/**
	 * The instance method for the static class.
	 * Defines and returns the instance of the static class.
	 *
	 * @static
	 * @return Block_Frontend
	 * @since 1.3.0
	 * @access public
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
	 * @access public
	 * @return void
	 * @since 1.3.0
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'cfb-blocks' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @access public
	 * @return void
	 * @since 1.3.0
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'cfb-blocks' ), '1.0.0' );
	}
}
