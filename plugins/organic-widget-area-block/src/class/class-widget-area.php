<?php
/**
 * Organic Widget Area Block Class
 *
 * @package Organic Widget Area Block
 * @since Organic Widget Area Block 1.2
 */
class OWA_Block {

	/**
	 * The instance of the class
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Object
	 */
	private static $instance;

	/**
	 * Load instance of the class
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      Object
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Callback for block render.
	 *
	 * @param array $att Render widget area based on title.
	 */
	public function render_block_html( $att ) {

		$randomid     = wp_rand();
		$widgetareaid = "organic-widget-area-$randomid";

		$alignment = ( isset( $att['align'] ) ) ? $att['align'] : '';
		$classes   = 'align' . $alignment . '';

		ob_start();

		$widget_area_title = $att['widgetAreaTitle'];

		?>

		<div id="<?php echo esc_html( $widgetareaid ); ?>" class="organic-block organic-block-widget-area <?php echo esc_html( $classes ); ?>">

			<?php dynamic_sidebar( sanitize_title( $widget_area_title ) ); ?>

		</div>

		<?php

		$output = ob_get_clean();

		return $output;
	}

	/**
	 * Load widgets area for all pages/posts.
	 */
	public function register_widget_sidebar() {

		$saved_widgets = get_option( 'organic_widget-area' );

		if ( ! empty( $saved_widgets ) ) {
			foreach ( $saved_widgets as $post_id => $widgets ) {
				if ( false !== get_post_status( $post_id ) ) {
					foreach ( $widgets as $widget_name ) {
						if ( '' !== trim( $widget_name ) ) {
							$side_bar_id = register_sidebar(
								array(
									'name'          => __( $widget_name, 'owa' ),
									'id'            => sanitize_title( $widget_name ),
									'description'   => __( 'Add widgets here to appear in your widget area.', 'owa' ),
									'before_widget' => '<div id="%1$s" class="organic-widget widget %2$s">',
									'after_widget'  => '</div>',
									'before_title'  => '<h2 class="widget-title">',
									'after_title'   => '</h2>',
								)
							);
						}
					}
				}
			}
		}

	}

	/**
	 * Save newly added widgets in options on post save.
	 *
	 * @param int    $post_id post id.
	 * @param object $post
	 * @param string $update
	 */
	public function update_widgets_log( $post_id, $post, $update ) {
		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$saved_widgets = get_option( 'organic_widget-area' );

		$blocks = parse_blocks( $post->post_content );
		if ( isset( $saved_widgets[ $post_id ] ) ) {
			unset( $saved_widgets[ $post_id ] );
		}

		if ( ! empty( $blocks ) ) {
			foreach ( $blocks as $block ) {
				if ( isset( $block['blockName'] ) && 'organic/widget-area' === $block['blockName'] ) {
					if ( '' !== trim( $block['attrs']['widgetAreaTitle'] ) ) {
						$saved_widgets[ $post_id ][] = $block['attrs']['widgetAreaTitle'];
					}
				}
			}
		}

		$saved_widgets = self::find_saved_widgets_recursive( $saved_widgets, $post_id, $blocks );

		update_option( 'organic_widget-area', $saved_widgets, true );

	}

	/**
	 * Walk the post content blocks recursively and find widget area blocks.
	 *
	 * @param array $saved_widgets
	 * @param int $post_id post id.
	 * @param array $blocks
	 * @return array $saved_widgets
	 */
	protected static function find_saved_widgets_recursive( &$saved_widgets, $post_id, $blocks ) {

		if ( ! empty( $blocks ) ) {
			foreach ( $blocks as $block ) {
				if ( isset( $block['blockName'] ) && 'organic/widget-area' === $block['blockName'] ) {
					if ( '' !== trim( $block['attrs']['widgetAreaTitle'] ) ) {
						$saved_widgets[ $post_id ][] = $block['attrs']['widgetAreaTitle'];
					}
				} elseif ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) ) {
					self::find_saved_widgets_recursive( $saved_widgets, $post_id, $block['innerBlocks'] );
				}
			}
		}

		return $saved_widgets;
	}

	/**
	 * Update when a post deleted for widgets in options.
	 *
	 * @param int $post_id post id.
	 */
	public function delete_widgets_log( $post_id ) {
		$saved_widgets = get_option( 'organic_widget-area' );

		if ( isset( $saved_widgets[ $post_id ] ) ) {
			unset( $saved_widgets[ $post_id ] );
		}

		update_option( 'organic_widget-area', $saved_widgets, true );
	}

}
