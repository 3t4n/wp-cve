<?php
namespace DarklupLite\CustomControl;
 /**
  * 
  * @package    DarklupLite - WP Dark Mode
  * @version    1.0.0
  * @author     
  * @Websites: 
  *
  */
if( ! defined( 'ABSPATH' ) ) {
    die( DARKLUPLITE_ALERT_MSG );
}

class Image_Select extends \Elementor\Base_Data_Control{


    public function get_type() {
        return 'image-select';
    }

    public function content_template() {
        $control_uid = $this->get_control_uid();

        ?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title"></label>
			<div class="elementor-control-input-wrapper">
				
				<div class="select-image-wrapper">
					<div class="inner-image">
						<?php 
						$this->popup_element();
						?>
					</div>
				</div>

			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
	
        <?php
    }

    public function enqueue() {

    	wp_enqueue_style( 'image-controls', plugin_dir_url( __FILE__ ).'assets/css/image-controls.css', array(), DARKLUPLITE_VERSION, false );
    	wp_enqueue_script( 'image-controls', plugin_dir_url( __FILE__ ).'assets/js/image-controls.js', array('jquery'), DARKLUPLITE_VERSION, true );
  
    }

    protected function get_default_settings() {
		return [
			'options' => [],
			'toggle' => false,
		];
    }

    function popup_element() {
    	?>
        <div class="image-select-content-wrapper">
            <# _.each( data.options, function( option, value ) { #>
            <div class="darkluplite-image-select-item" data-filter-item>
                <label for="{{ value }}" class="image-item">
                    <img src="{{ option.url }}" />
                    <input id="{{ value }}" type="radio" data-setting="{{ data.name }}" name="elementor-image-select-{{ data.name }}-{{ data._cid }}" value="{{ value }}" />
                </label>
            </div>
            <#} ); #>
        </div>
    	<?php
    }

}
