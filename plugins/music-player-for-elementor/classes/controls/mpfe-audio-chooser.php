<?php
/**
 * FileSelect control.
 *
 * A control for selecting audio files
 *
 * @since 1.0.0
 */
if (!class_exists("MPFE_Audio_Chooser_Control")) {
class MPFE_Audio_Chooser_Control extends \Elementor\Base_Data_Control {

	/**
	 * Get control type.
	 *
	 * Retrieve the control type
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'mpfe-audio-chooser';
	}

	/**
	 * Enqueue control scripts and styles.
	 *
	 * Used to register and enqueue custom scripts and styles
	 * for this control.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_media();
		wp_enqueue_style('thickbox');
	    wp_enqueue_script('media-upload');
	    wp_enqueue_script('thickbox');

		wp_enqueue_script(
			'mpfe-audio_chooser_control', 
			MPFE_DIR_URL . '/js/audio_chooser_control.js',
			[ 'jquery' ], '', true );
	}

	/**
	 * Get default settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'label_block' => true,
		];
	}

	/**
	 * Render control output in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();

		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<a href="#" class="smc-ec-select-file elementor-button" style="padding: 10px 15px; margin-top: 10px; display: block; width: 100%; text-align: center; border: 1px dashed #d5dadf;" id="select-file-<?php echo esc_attr( $control_uid ); ?>" ><?php echo esc_html__( "Choose Audio File", 'music-player-for-elementor' ); ?></a> <br />
			<div class="elementor-control-input-wrapper elementor-control-dynamic-switcher-wrapper">
				<input type="text" class="smc-selected-audio-url" id="<?php echo esc_attr( $control_uid ); ?>" data-setting="{{ data.name }}" placeholder="{{ data.placeholder }}">
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>

		<?php
	}
}
}