<?php
/**
 * Element multicheckbox custom elementor control.
 * 
 */
class Wpmagazine_Modules_Lite_Multicheckbox_Control extends \Elementor\Base_Data_Control {
    /**
     * Control name.
     */
    public function get_type() {
        return 'MULTICHECKBOX';
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
        wp_enqueue_style( 'wpmagazine-modules-lite-custom-multicheckbox-control', plugins_url( 'multicheckbox.css', __FILE__ ), array(), WPMAGAZINE_MODULES_LITE_VERSION, 'all' );

        wp_enqueue_script( 'wpmagazine-modules-lite-custom-multicheckbox-control', plugins_url( 'multicheckbox.js', __FILE__ ), array( 'jquery' ), WPMAGAZINE_MODULES_LITE_VERSION, true );
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
        <div id="elementor-multicheckbox-control-field">
            <label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper cvmm-elementor-custom-control">
                <div id="<?php echo esc_attr( $control_uid ); ?>" class="cvmm-multicheckbox-control-wrap">
                    <# _.each( data.options, function( option, key ) { #>
                        <div class="cvmm-multicheckbox-item <# if( _.contains( data.controlValue, key ) ) { #>isActive<# } #>">
                            <input type="checkbox" value="{{{ key }}}" name="{{{ data.name }}}" class="cvmm-multicheckbox-field" <# if( _.contains( data.controlValue, key ) ) { #>checked<# } #>>
                            <label class="cvmm-multicheckbox-label">{{{ option }}}</label>
                        </div>
                    <# }); #>
                </div>
            </div>
        </div>
    <?php
    }
}