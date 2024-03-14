<?php
/**
 * Plugin Name: SnapEngage plugin
 * Plugin URI: http://help.snapengage.com/wordpress-plugin/
 * Description: Use our live chat in wordpress
 * Version: 1.0
 * Author: SnapEngage
 * Author URI: http://www.snapengage.com
 * License: GPL2
 */

class SnapEngageSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_snapengage_page' ) );
        add_action( 'admin_init', array( $this, 'snapengage_init' ) );
    }

    /**
     * Add options page
     */
    public function add_snapengage_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'SnapEngage', 
            'manage_options', 
            'snapengage_settings', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'snapengage_option' );
        $img = plugins_url( 'img/snapengage.png' , __FILE__ );

        ?>
        <div class="wrap">
            <?php printf('<a target="_blank" class="logo" href="http://www.snapengage.com"><img src="%s" alt="SnapEngage"></a>', $img); ?>
            <h2>SnapEngage Settings</h2>
            <p>By setting your SnapEngage Widget ID below and enabling the widget you'll install SnapEngage on your Wordpress site.</p>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'snapengage_group' );   
                do_settings_sections( 'snapengage_settings' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function snapengage_init()
    {        
        register_setting(
            'snapengage_group', // Option group
            'snapengage_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'snapengage_settings' // Page
        );  

        add_settings_field(
            'enable', 
            'Enable SnapEngage', 
            array( $this, 'enabled_callback' ), 
            'snapengage_settings', 
            'setting_section_id'
        );   

        add_settings_field(
            'widget_id', // ID
            'Widget ID', // Title 
            array( $this, 'widget_id_callback' ), // Callback
            'snapengage_settings', // Page
            'setting_section_id' // Section           
        );      
   
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['widget_id'] ) )
            $new_input['widget_id'] = sanitize_text_field( $input['widget_id'] );

        if( isset( $input['enable'] ) )
            $new_input['enable'] = $input['enable'];

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Please enter your SnapEngage data below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function widget_id_callback()
    {
        printf(
            '<input type="text" id="widget_id" name="snapengage_option[widget_id]" value="%s" /> <a href="https://www.snapengage.com/getwidgetid" target="_blank">Find my widget ID</a> ',
            isset( $this->options['widget_id'] ) ? esc_attr( $this->options['widget_id']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function enabled_callback()
    {
    	$checked = '';
    	if ($this->options['enable'] == 'checked') {
    		$checked = 'checked="true"';
    	}
        printf(
            '<input type="checkbox" id="enable" name="snapengage_option[enable]" value="checked" %s/>',
            $checked
        );
    }
}

if( is_admin() )
    $snapengage = new SnapEngageSettingsPage();

function get_snapengage_code($widget_id) {
	$snapengage = get_option( 'snapengage_option' );
	if($snapengage['enable'] && $snapengage['widget_id']) {
		printf('<!-- begin SnapEngage code --><script type="text/javascript">(function() {var se = document.createElement(\'script\'); se.type = \'text/javascript\'; se.async = true;se.src = \'//commondatastorage.googleapis.com/code.snapengage.com/js/%s.js\';var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(se, s);})();</script><!-- end SnapEngage code -->', $snapengage['widget_id']);
	}
}
add_action( 'wp_footer', 'get_snapengage_code' );

?>