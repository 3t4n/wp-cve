<?php
namespace Thim_EL_Kit\Utilities;

use Thim_EL_Kit\SingletonTrait;

class Elementor {
	use SingletonTrait;

	private static $printed_with_css = array();

	public function render_content_css( $id ) {
		if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
			$css_file = new \Elementor\Core\Files\CSS\Post( $id );

			$meta = $css_file->get_meta();

			if ( $meta['status'] === $css_file::CSS_STATUS_FILE ) {
				ob_start();
				?>
				<link rel="stylesheet" id="elementor-post-<?php echo esc_attr( $id ); ?>-css" href="<?php echo esc_url( $css_file->get_url() ); ?>" type="text/css" media="all">
				<?php
				return ob_get_clean();
			}
		}
	}

	public function render_content( $id ) {
		return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id );
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $post_id post id
	 * @param [type] $template_id loop item template id
	 * @return void
	 */
	public function render_loop_item_content( $template_id ) {
		$document = \Elementor\Plugin::$instance->documents->get( $template_id );

		if ( ! $document ) {
			return;
		}

		// check if css elementor is enqueue.
		if ( empty( self::$printed_with_css[ $template_id ] ) ) {
			$this->print_loop_css( $template_id );
			self::$printed_with_css[ $template_id ] = true;
		}

		if ( \Elementor\Plugin::$instance->preview->is_preview_mode( $template_id ) ) {
			echo \Elementor\Plugin::$instance->preview->builder_wrapper( '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			$edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

			add_filter( 'elementor/frontend/builder_content/before_print_css', array( $this, 'prevent_inline_css_printing' ) );

			\Elementor\Plugin::$instance->editor->set_edit_mode( false );

			$content = do_shortcode( \Elementor\Plugin::$instance->frontend->get_builder_content( $template_id, false ) );

			remove_filter( 'elementor/frontend/builder_content/before_print_css', array( $this, 'prevent_inline_css_printing' ) );

			\Elementor\Plugin::$instance->editor->set_edit_mode( $edit_mode );

			echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	protected function print_loop_css( $id ) {
		$css_file = new \Elementor\Core\Files\CSS\Post( $id );
		$post_css = $css_file->get_content();

		if ( empty( $post_css ) ) {
			return;
		}

		echo '<style id="thim-ekit-loop-post-' . esc_attr( $id ) . '">' . $post_css . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function prevent_inline_css_printing() {
		return false;
	}
}
