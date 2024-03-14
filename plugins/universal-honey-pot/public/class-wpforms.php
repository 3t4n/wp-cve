<?php

class Universal_Honey_Pot_Wpforms {

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
    public function add_honey_pot_fields_to_form( $html ) {        
        echo get_universal_honey_pot_inputs_html();
    }

    
    /**
     * validate_honey_pot_fields
     *
     * @param  mixed $spam
     * @return void
     */
    public function validate_honey_pot_fields( $fields, $entry, $form_data, $entry_id ) {
        $hash = get_universal_honey_pot_hash();

        $spam = false;

        foreach( get_universal_honey_pot_fields() as $name => $data ) {
            $spam = isset( $_POST[ $name ] ) && !empty( $_POST[ $name ] ) ? true : $spam;
        }

        if( $spam ) {
            wpforms()->process->errors[ $form_data['id'] ] = __( 'You are a spammer !', 'universal-honey-pot' );
        }
    }
}
