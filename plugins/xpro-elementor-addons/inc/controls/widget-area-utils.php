<?php

namespace XproElementorAddons\Control;

use Elementor\Plugin;

defined( 'ABSPATH' ) || exit;

class Xpro_Elementor_Widget_Area_Utils {


	public static function parse( $content, $widget_key, $index = 1 ) {
		$key         = ( '' === $content ) ? $widget_key : $content;
		$extract_key = explode( '***', $key );
		$extract_key = $extract_key[0];
		ob_start(); ?>

		<div class="widgetarea_wrapper widgetarea_wrapper_editable" data-xpro-widgetarea-key="<?php echo esc_attr( $extract_key ); ?>" data-xpro-widgetarea-index="<?php echo esc_attr( $index ); ?>">
			<div class="widgetarea_wrapper_edit" data-xpro-widgetarea-key="<?php echo esc_attr( $extract_key ); ?>" data-xpro-widgetarea-index="<?php echo esc_attr( $index ); ?>">
				<i class="eicon-edit" aria-hidden="true"></i>
				<span class="widgetarea_wrapper_edit_text"><?php esc_html_e( 'Edit Content', 'xpro-elementor-addons' ); ?></span>
			</div>

			<div class="elementor-widget-container">
				<?php
				$builder_post_title = 'dynamic-content-widget-' . $extract_key . '-' . $index;
				$builder_post       = xpro_get_page_by_title( $builder_post_title, OBJECT, 'xpro_content' );
				$elementor          = Plugin::instance();

				if ( isset( $builder_post->ID ) && empty( get_option( 'xpro_dynamic_template_id' ) ) ) {
					update_post_meta( $builder_post->ID, 'xpro_dynamic_template_id', $builder_post_title );
				}

				$builder_post = get_posts(
					array(
						'post_type'  => 'xpro_content',
						'meta_key'   => 'xpro_dynamic_template_id',
						'meta_value' => $builder_post_title,
					)
				);

				if ( isset( $builder_post ) && isset( $builder_post[0] ) ) {
					$content = str_replace(
						'#elementor',
						'',
						xpro_elementor_render_tab_content(
							$elementor->frontend->get_builder_content_for_display( $builder_post[0]->ID ),
							$builder_post[0]->ID
						)
					);
					echo $content; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					if ( Plugin::$instance->editor->is_edit_mode() ) {
						echo '<span class="widgetarea_wrapper_content">' . esc_html__( 'Click edit content icon to add content.', 'xpro-elementor-addons' ) . '</span>';
					} else {
						echo '<span class="widgetarea_wrapper_content">' . esc_html__( 'Content not found.', 'xpro-elementor-addons' ) . '</span>';
					}
				}
				?>
			</div>
		</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();
		echo $output; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function init() {
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'modal_content' ) );
	}

	public function modal_content() {
		ob_start();
		?>
		<div class="widgetarea_iframe_modal">
			<?php include 'widget-area-modal.php'; ?>
		</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		echo $output; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

$widget_area = new Xpro_Elementor_Widget_Area_Utils();
$widget_area->init();
