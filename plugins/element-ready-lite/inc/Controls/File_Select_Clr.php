<?php

namespace Element_Ready\Controls;

class File_Select_Clr extends \Elementor\Base_Data_Control {

	public function get_type() {
		return 'file-select';
	}
	
	public function enqueue() {

		wp_enqueue_media();
		wp_enqueue_style('thickbox');
	    wp_enqueue_script('media-upload');
	    wp_enqueue_script('thickbox');
		// Scripts
		wp_register_script( 'er-file-select-control', ELEMENT_READY_ROOT_JS .'file-upload.js' );
		wp_enqueue_script( 'er-file-select-control' );
	
	}

	
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
			<div class="elementor-control-input-wrapper">
				<a href="#" class="element-ready-select-file elementor-button elementor-button-success" style="padding: 10px 15px; display: block;text-align: center;" id="select-file-<?php echo esc_attr( $control_uid ); ?>" ><?php echo esc_html__( "Choose File", 'element-ready-lite' ); ?></a> <br />
				<input type="text" class="element-ready-selected-fle-url" id="<?php echo esc_attr( $control_uid ); ?>" data-setting="{{ data.name }}" placeholder="{{ data.placeholder }}">
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}