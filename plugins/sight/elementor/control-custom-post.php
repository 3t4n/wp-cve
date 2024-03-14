<?php
/**
 * Control Custom Post.
 *
 * @package Sight
 */

namespace Sight_Elementor\Controls;

use Elementor\Base_Data_Control;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Control
 *
 * @since 1.0.0
 */
class Sight_Control_Custom_Post extends Base_Data_Control {

	/**
	 * Retrieve the control type.
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'custom_post';
	}

	/**
	 * Retrieve the default settings.
	 *
	 * @return string Default settings.
	 */
	protected function get_default_settings() {
		return array(
			'label'       => esc_html__( 'Custom Post', 'sight' ),
			'label_block' => true,
		);
	}

	/**
	 * Render field control output in the editor.
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>

			<div class="elementor-control-input-wrapper">
				<select name="eae-file-link" class="elementor-control-tag-area" title="{{ data.title }}" data-setting="{{ data.name }}" id="<?php echo esc_attr( $control_uid ); ?>">
					<# if ( data.controlValue ) { #>
						<option value="{{ data.controlValue }}" selected="selected">{{{ data.controlValue }}}</div>
					<# } #>
				</select>
			</div>
		</div>
		<?php

	}

	/**
	 * Enqueue control scripts and styles.
	 *
	 * Used to register and enqueue custom scripts and styles used by the control.
	 */
	public function enqueue() {
		wp_enqueue_style( 'elementor-select2' );

		wp_enqueue_script( 'jquery-elementor-select2' );

		wp_register_script(
			'custom_post',
			SIGHT_URL . 'elementor/assets/custom-post.js',
			array( 'jquery', 'jquery-elementor-select2' ),
			filemtime( SIGHT_PATH . 'elementor/assets/custom-post.js' ),
			true
		);

		wp_localize_script(
			'custom_post',
			'cpConfig',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);

		wp_enqueue_script( 'custom_post' );
	}
}
