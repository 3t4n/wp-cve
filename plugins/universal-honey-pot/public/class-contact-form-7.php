<?php

class Universal_Honey_Pot_Contact_Form_7 {

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
    public function add_honey_pot_fields_to_form( $content ) {
        $hash                 = get_universal_honey_pot_hash();
        $honey_pot            = get_universal_honey_pot_inputs_html();
    
        foreach( get_universal_honey_pot_fields() as $name => $data ) {
            if(strpos( $content, 'name="'. $name .'"' ) !== false){
                $honey_pot = str_replace( 'name="'. $name .'"', 'name="'. $name . '-' . $hash .'"', $honey_pot );
            }
        }
        
        return $honey_pot . $content;
    }

    
    /**
     * validate_honey_pot_fields
     *
     * @param  mixed $spam
     * @return void
     */
    public function validate_honey_pot_fields( $spam ) {
        $hash = get_universal_honey_pot_hash();
    
        $spam = false;
    
        foreach( get_universal_honey_pot_fields() as $name => $data ) {
            if( isset( $_POST[ $name . '-' . $hash ] ) ) {
                if( !empty( $_POST[ $name . '-' . $hash ] ) ) {
                    $spam = true;
                }
            } else {
                if( isset( $_POST[ $name ] ) && !empty( $_POST[ $name ] ) ) {
                    $spam = true;
                }
            }
        }

        if($spam) {
            update_universal_honey_pot_counter();
        }
        
        return $spam;
    }
}
