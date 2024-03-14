<?php
namespace Thim_EL_Kit\Elementor\Controls;

class Image_Select extends \Elementor\Base_Data_Control {

	public function get_type() {
		return Controls_Manager::IMAGE_SELECT;
	}

	public function enqueue() {
		wp_enqueue_style( 'thim-ekit-image-select-control', THIM_EKIT_PLUGIN_URL . 'inc/elementor/controls/assets/css/image-select.css', array(), '' );
	}

	protected function get_default_settings() {
		return array(
			'label_block' => true,
			'toggle'      => true,
			'options'     => array(),
			// You can use styles for style columns: Example: 'width: 30%;' for 3 columns.
			'styles'      => '',
		);
	}

	public function content_template() {
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-image-selector-wrapper">
				<# _.each( data.options, function( options, value ) { #>
				<input id="<?php $this->print_control_uid( '{{ value }}' ); ?>" type="radio" name="elementor-image-selector-{{ data.name }}-{{ data._cid }}" value="{{ value }}" data-setting="{{ data.name }}">
				<label class="elementor-image-selector-label tooltip-target" for="<?php $this->print_control_uid( '{{ value }}' ); ?>" data-tooltip="{{ options.title }}" title="{{ options.title }}" style="{{ styles }}">
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

// ! Use only thim-elementor-kit plugin.
// $this->add_control(
// 	'image_select_test',
// 	[
// 		'label'   => esc_html__( 'Image Select', 'thim-elementor-kit' ),
// 		'type'    => class_exists( '\Thim_EL_Kit\Elementor\Controls\Image_Select' ) ? 'thim-ekit-image-select' : 'select',
// 		'options' => [
// 			'left-sidebar' => [
// 				'title' => esc_html__( 'Left Sidebar', 'text-domain' ),
// 				'url' => 'https://picsum.photos/100',
// 			],
// 			'right-sidebar' => [
// 				'title' => esc_html__( 'Right Sidebar', 'text-domain' ),
// 				'url' => 'https://picsum.photos/100',
// 			],
// 			'no-sidebar' => [
// 				'title' => esc_html__( 'No Sidebar', 'text-domain' ),
// 				'url' => 'https://picsum.photos/100',
// 			],
// 		],
// 		'default' => 'right-sidebar',
// 		'styles'  => 'width: 30%;',
// 	]
// );
