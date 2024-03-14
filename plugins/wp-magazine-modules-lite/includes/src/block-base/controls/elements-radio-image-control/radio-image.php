<?php
/**
 * Element radio image custom elementor control.
 * 
 */
class Wpmagazine_Modules_Lite_Radio_Image_Control extends \Elementor\Base_Data_Control {
    /**
     * Control name.
     */
    public function get_type() {
        return 'RADIOIMAGE';
    }
    
    /**
     * Enqueue control scripts and styles.
     *
     * Used to register and enqueue custom scripts and styles used by control.
     *
     * @since 1.0.0
     * @access public
     */
    public function enqueue() {
        wp_enqueue_style( 'wpmagazine-modules-lite-custom-radio-image-control', plugins_url( 'radio-image.css', __FILE__ ), array(), WPMAGAZINE_MODULES_LITE_VERSION, 'all' );

        wp_enqueue_script( 'wpmagazine-modules-lite-custom-radio-image-control', plugins_url( 'radio-image.js', __FILE__ ), array( 'jquery' ), WPMAGAZINE_MODULES_LITE_VERSION, true );
    }
    
    protected function get_default_settings() {
		return [
			'label_block' => true,
			'options' => [],
		];
    }
    
    public function content_template() {
        $control_uid = $this->get_control_uid();
    ?>
        <div id="elementor-radio-image-control-field">
            <label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper cvmm-elementor-custom-control">
                <ul id="<?php echo esc_attr( $control_uid ); ?>" class="cvmm-radio-image-control-wrap">
                    <# _.each( data.options, function( option ) { #>
                        <li class='cvmm-radio-image-item <# if( option.value === data.controlValue ) { #>isActive<# } #>' data-value='{{ option.value }}'>
                            <img src="{{ option.label }}" alt="{{ option.value }}">
                        </li>
                    <# }); #>
                </ul>
            </div>
        </div>
    <?php
    }
}