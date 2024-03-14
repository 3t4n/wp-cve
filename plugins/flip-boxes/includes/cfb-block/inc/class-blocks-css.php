<?php
/**
 * Class for CSS logic.
 *
 * @package CoolPlugins
 */

namespace CoolPlugins\GutenbergBlocks;

/**
 * Class Blocks_CSS.
 */
class Blocks_CSS {

	/**
	 * The main instance var.
	 *
	 * @var Blocks_CSS|null
	 */
	public static $instance = null;

	/**
	 * Initialize the class
	 */
	public function init() {
		if ( ! defined( 'BLOCKS_CSS_URL' ) ) {
			define( 'BLOCKS_CSS_URL', CFB_URL );
			define( 'BLOCKS_CSS_PATH', CFB_DIR_PATH );
		}

		add_action( 'wp_head', array( $this, 'render_server_side_css' ) );
	}

	/**
	 * Render server-side CSS
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function render_server_side_css() {
		if ( function_exists( 'has_blocks' ) && has_blocks( get_the_ID() ) ) {
			global $post;

			if ( ! is_object( $post ) ) {
				return;
			}

			$content = '';

			if (
				! defined( 'CFB_BLOCK_VERSION' ) &&
				get_queried_object() === null &&
				function_exists( 'wp_is_block_theme' ) &&
				wp_is_block_theme() &&
				current_theme_supports( 'block-templates' )
			) {
				global $_wp_current_template_content;

				$slugs           = array();
				$template_blocks = parse_blocks( $_wp_current_template_content );

				foreach ( $template_blocks as $template_block ) {
					if ( 'core/template-part' === $template_block['blockName'] ) {
						$slugs[] = $template_block['attrs']['slug'];
					}
				}

				$templates_parts = get_block_templates( array( 'slugs__in' => $slugs ), 'wp_template_part' );

				foreach ( $templates_parts as $templates_part ) {
					if ( ! empty( $templates_part->content ) && ! empty( $templates_part->slug ) && in_array( $templates_part->slug, $slugs ) ) {
						$content .= $templates_part->content;
					}
				}

				$content .= $_wp_current_template_content;
			} else {
				$content = $post->post_content;
			}

			$blocks = parse_blocks( $content );

			if ( ! is_array( $blocks ) || empty( $blocks ) ) {
				return;
			}

			$css = $this->cycle_through_blocks( $blocks, $post->ID );

			if ( empty( $css ) ) {
				return;
			}

			$style  = "\n" . '<style type="text/css" media="all">' . "\n";
			$style .= $css;
			$style .= "\n" . '</style>' . "\n";

			echo $style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Cycle through Blocks
	 *
	 * @param array $inner_blocks Array of blocks.
	 * @param int   $id Post ID.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function cycle_through_blocks( $inner_blocks, $id ) {
		$style = '';

		foreach ( $inner_blocks as $block ) {
			$file_name  = get_post_meta( $id, '_CoolPlugins_gutenberg_block_stylesheet', true );
			$render_css = empty( $file_name ) || strpos( $file_name, 'post-v2' ) === false;

			if ( $render_css && isset( $block['attrs'] ) ) {
				if ( isset( $block['attrs']['hasCustomCSS'] ) && isset( $block['attrs']['customCSS'] ) ) {
					$style .= $block['attrs']['customCSS'];
				}
			}
		}

		return $style;
	}

	/**
	 * The instance method for the static class.
	 * Defines and returns the instance of the static class.
	 *
	 * @static
	 * @since 1.0.0
	 * @access public
	 * @return Blocks_CSS
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
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', '1.0.0' );
	}
}
