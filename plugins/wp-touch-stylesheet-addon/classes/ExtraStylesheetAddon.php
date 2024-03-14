<?php 

class ExtraStylesheetAddon {

/**
 * Holds the values to be used in the fields callbacks
 */
private $options;


/**
 * Start up
 */
public function __construct() {

    add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
    add_action( 'admin_init', array( $this, 'page_init' ) );

}

/**
 * Create stylesheet
 */
public function create_stylesheet_on_activation()
{

	$dir = wp_upload_dir();

	// Prefix CSS File with Message
	$css_message  = "";
	$css_message .= "/**\n";
	$css_message .= " * Addon CSS File for WPtouch\n";
	$css_message .= " * Author: Miles Stewart\n";
	$css_message .= " * Author URI: http://www.milesstewart.co.uk\n";
	$css_message .= " */";

	$create_css_file = fopen($dir["basedir"]  . "/wp-touch-stylesheet-addon.css", 'w');
	fwrite($create_css_file, $css_message);
	fclose($create_css_file);
}

/**
 * Add submenu
 */
public function add_plugin_page() {
	add_options_page( 
		'Custom Stylesheet Extension for WPtouch',
		'Custom Stylesheet WPtouch',
		'manage_options',
		'wp_touch_stylesheet_addon_settings', 
		array( $this, 'wp_touch_stylesheet_addon_settings_page' ) 
	);
}

/**
 * Create the settings page
 */
public function wp_touch_stylesheet_addon_settings_page() 
{
    // Set class property
    $this->options = get_option( 'wp_touch_stylesheet_addon_settings' );
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <h2>Custom Stylesheet Extension for WPtouch</h2>           
        <form method="post" action="options.php">
        <?php
            // This prints out all hidden setting fields
            settings_fields( 'wp_touch_stylesheet_addon_settings_group' );   
            do_settings_sections( 'wp_touch_stylesheet_addon_settings' );
            submit_button(); 
        ?>
        </form>
    </div>
    <?php
}

/**
 * Register the settings fields
 */
public function page_init()
{        
    register_setting(
        'wp_touch_stylesheet_addon_settings_group', // Option group
        'wp_touch_stylesheet_addon_settings', // Option name
        array( $this, 'sanitize' ) // Sanitize
    );

    add_settings_section(
        'setting_section_id', // ID
        '', // Title
        array( $this, 'wp_touch_stylesheet_addon_desc_callback' ), // Callback
        'wp_touch_stylesheet_addon_settings' // Page
    );  

    /**
     * Url of stylesheet
     */
    add_settings_field(
        'stylesheet_path', // ID
        'Stylesheet Path', // Title 
        array( $this, 'wp_touch_stylesheet_addon_path_callback' ), // Callback
        'wp_touch_stylesheet_addon_settings', // Page
        'setting_section_id' // Section           
    );  
}

/**
 * Sanitize each setting field as needed
 * @param array $input Contains all settings fields as array keys
 */
public function sanitize( $input )
{
    $new_input = array();

    if( isset($input['stylesheet_path']) ) {
    	$new_input['stylesheet_path'] = $input['stylesheet_path'] ;	
    }

    return $new_input;    
}

/**
 * Text to go on settings page
 */
public function wp_touch_stylesheet_addon_desc_callback()
{
	print '<div style="margin-top:20px;">Enter a custom stylesheet below. This stylesheet should link to an already existing stylesheet <br /> eg. /wp-content/themes/your-theme-name/test-mobile-addon.css</div>';
}

/**
 * Style sheet input field
 */
public function wp_touch_stylesheet_addon_path_callback()
{
    printf(
        '<input type="text" id="stylesheet_path" name="wp_touch_stylesheet_addon_settings[stylesheet_path]" value="%s" />',
        isset( $this->options['stylesheet_path'] ) ? esc_attr( $this->options['stylesheet_path']) : ''
    );
}

/**
 * Enqueue the style sheet
 */
public function mobile_extra_stylesheet() {
 
	global $wp_styles;

    $this->options = get_option( 'wp_touch_stylesheet_addon_settings' );

	$handle = array_map('basename', (array) wp_list_pluck($wp_styles->registered, 'handle') );

	if ( in_array('wptouch-parent-theme-css', $handle) ) {

		if ($this->options['stylesheet_path'] == "") {
			wp_enqueue_style( 'wp_touch_stylesheet_addon', '/wp-content/uploads/wp-touch-stylesheet-addon.css', False, NULL); 
		}
		if ($this->options['stylesheet_path'] != "") {
			wp_enqueue_style( 'wp_touch_stylesheet_addon', $this->options['stylesheet_path'] , False, NULL); 
		}
 
 	} else {
    	/** Do nothing **/ 
	}

}

}