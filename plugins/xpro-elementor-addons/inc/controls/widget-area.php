<?php

namespace XproElementorAddons\Control;

use Elementor\Base_Data_Control;

defined( 'ABSPATH' ) || exit;

class Xpro_Elementor_Widget_Area extends Base_Data_Control {


	const TYPE = 'widgetarea';

	/**
	 * Set control type.
	 */
	public function get_type() {
		return self::TYPE;
	}

	/**
	 * Enqueue ontrol scripts and styles.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue() {

		// Style
		wp_enqueue_style( 'xpro-widgetarea-inspector', XPRO_ELEMENTOR_ADDONS_DIR_URL . 'inc/controls/assets/css/widgetarea-inspector.css', array(), XPRO_ELEMENTOR_ADDONS_VERSION );

		// script
		wp_enqueue_script( 'xpro-widgetarea-inspector', XPRO_ELEMENTOR_ADDONS_DIR_URL . 'inc/controls/assets/js/widgetarea-inspector.js', null, XPRO_ELEMENTOR_ADDONS_VERSION, true );
	}

	/**
	 * Render choose control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div style="display:none" class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label
				}}}</label>
			<div class="elementor-control-input-wrapper">
				<input id="<?php echo esc_attr( $control_uid ); ?>" type="text" data-setting="{{ data.name }}"/>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}

	/**
	 * Get choose control default settings.
	 *
	 * Retrieve the default settings of the choose control. Used to return the
	 * default settings while initializing the choose control.
	 *
	 * @return array Control default settings.
	 * @since 1.0.0
	 * @access protected
	 *
	 */
	protected function get_default_settings() {
		return array(
			'label_block'      => true,
			'show_edit_button' => false,
		);
	}
}
