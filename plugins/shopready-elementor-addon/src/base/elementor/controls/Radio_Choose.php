<?php
namespace Shop_Ready\base\elementor\controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Radio Image Selector Option
 * @see https://themes.artbees.net/blog/creating-a-custom-control/ https://developers.elementor.com/creating-a-new-control/
 */
class Radio_Choose extends \Elementor\Base_Data_Control {

	public function get_type() {
		return 'wrradioimage';
	}

	public function enqueue() {

		// styles
		wp_register_style( 'woo-ready-rm-control' ,  SHOP_READY_PUBLIC_ROOT_CSS . 'plugins/imgclr.css', [], time() );
		wp_enqueue_style( 'woo-ready-rm-control' );
		
		// script
		wp_register_script( 'woo-ready-rm-control' ,  SHOP_READY_PUBLIC_ROOT_JS . 'plugins/imgclr.js' );	
		wp_enqueue_script( 'woo-ready-rm-control' );
       
	}

	public function content_template() {
		
		$control_uid = $this->get_control_uid( '{{name}}' );
	  
		?>
<div class="elementor-control-field">
    <label class="elementor-control-title">{{{ data.label }}}</label>
    <div class="elementor-control-input-wrapper woo-ready-radio-img">
        <div class="elementor-image-choices woo-ready-elementor-image-choices">
            <# _.each( data.options, function( options, value ) { #>
                <div class="image-choose-label-block" style="width:{{ options.width }}">
                    <input id="<?php echo esc_attr($control_uid); ?>{{ options.title }}" type="radio"
                        name="elementor-choose-{{ data.name }}-{{ data._cid }}" value="{{ value }}">
                    <label class="elementor-image-choices-label"
                        for="<?php echo esc_attr($control_uid); ?>{{ options.title }}" title="{{ options.title }}">
                        <img class="imagesmall" src="{{ options.imagesmall }}" alt="{{ options.title }}" />
                        <img class="imagelarge" src="{{ options.imagelarge }}" alt="{{ options.title }}" />
                        <span class="elementor-screen-only">{{{ options.title }}}</span>
                    </label>
                </div>
                <# } ); #>
        </div>
    </div>
</div>

<# if ( data.description ) { #>
    <div class="elementor-control-field-description">{{{ data.description }}}</div>
    <# } #>
        <?php
	}
	
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'options' => []
		];
	}
}