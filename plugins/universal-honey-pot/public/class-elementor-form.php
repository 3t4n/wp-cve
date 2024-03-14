<?php

class Universal_Honey_Pot_Elementor_Form {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
    
    /**
     * add_honey_pot_fields_to_form
     *
     * @param  mixed $content
     * @return void
     */
    public function add_honey_pot_fields_to_form( $form ) {
        $hash                 = get_universal_honey_pot_hash();
        $hash_without_numbers = preg_replace( '/[0-9]+/', '', $hash );

        $honey_pot = get_universal_honey_pot_inputs_html();
        
        ?>
        <!-- Added by UHP plugin -->
        <script>
            setTimeout(() => {
                const elementor_forms = document.querySelectorAll('form.elementor-form');
                elementor_forms.forEach(elementor_form => {
                    if (elementor_form.querySelector('.<?php echo esc_attr( $hash_without_numbers ); ?>')) {
                        return;
                    }
                    const container = document.createElement('div');
                    container.classList.add('<?php echo esc_attr( $hash_without_numbers ); ?>');
                    const html_code = `<?php echo wp_kses( $honey_pot, array('style' => array(), 'label' => array(), 'input' => array('type' => array(), 'name' => array(), 'value' => array(), 'autocomplete' => array(), 'tabindex' => array())) ); ?>`;
                    container.innerHTML = html_code;
                    elementor_form.insertBefore(container, elementor_form.firstChild);
                });
            }, 500);
        </script>
        <!-- END Added by UHP plugin -->
        <?php
    }

    
    /**
     * validate_honey_pot_fields
     *
     * @param  mixed $spam
     * @return void
     */
    public function validate_honey_pot_fields( $record, $ajax_handler ) {
        $hash = get_universal_honey_pot_hash();

        $spam = false;
        
        foreach( get_universal_honey_pot_fields() as $name => $data ) {
            $spam = isset( $_POST[ $name ] ) && !empty( $_POST[ $name ] ) ? true : $spam;
        }
    
        if($spam){
            update_universal_honey_pot_counter();
            $ajax_handler->add_error(
                'spam',
                'Spam detected by Universal Honey Pot.'
            );
        }
    }
}
