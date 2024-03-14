<?php
/**
 * Image Selector Control Class
 *
 * @package EazyGridElementor
 */
namespace EazyGrid\Elementor\Controls;

use \Elementor\Base_Data_Control;

class Image_Selector extends Base_Data_Control {

	const TYPE = 'eazygrid-image-selector';

	public function get_type() {
		return self::TYPE;
	}

	public function enqueue() {
		wp_enqueue_style(
			'eazygrid-editor-css',
			EAZYGRIDELEMENTOR_URL . 'assets/admin/css/image-selector.css',
			[],
			EAZYGRIDELEMENTOR_VERSION
		);
	}

	protected function get_default_settings() {
		return [
			'label_block' => true,
			'toggle'      => true,
			'options'     => [],
		];
	}

	public function content_template() {
		$control_uid = $this->get_control_uid( '{{ value }}' );
		?>
		<div class="elementor-control-field eazygrid-elementor-control-image-selector {{ data.content_classes }}" <# if ( data.column_height ) { #> style="--colum-height: {{ data.column_height }};" <# } #>>
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="eazygrid-elementor-control-image-selector-inner">
				<# _.each( data.options, function( options, value ) { #>
				<input id="<?php echo esc_attr( $control_uid ); ?>" type="radio" name="eazygrid-elementor-image-selector-{{ data.name }}-{{ data._cid }}" value="{{ value }}" data-setting="{{ data.name }}">
				<label class="eazygrid-elementor-image-selector-label tooltip-target" for="<?php echo esc_attr( $control_uid ); ?>" data-tooltip="{{ options.title }}" title="{{ options.title }}">
					<img src="{{ options.url }}" alt="{{ options.title }}">
					<span class="elementor-screen-only">{{{ options.title }}}</span>
				</label>
				<# } ); #>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}

}
