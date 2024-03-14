<?php
/*
 Zia3-JS-CSS
Copyright (C) 2013  Serkan Azmi http//zia3.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version GPL3.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/
//TODO:Add option to insert into header or footer
//TODO:Add a disable inline CSS/JS injection

require_once (ABSPATH .'/wp-includes/option.php');

$zia3meta_css_dir = '';
$zia3meta_js_dir = '';
$zia3meta_css_url = get_stylesheet_directory_uri();
$zia3meta_js_url = get_stylesheet_directory_uri();
$zia3meta_selected_dir_css_label = get_stylesheet_directory();
$zia3meta_selected_dir_js_label = get_stylesheet_directory();
$zia3meta_selected_dir_url_label = '';
$zia3meta_selected_dir_url_label = '';

add_action( 'admin_menu', 'zia3meta_plugin_menu' );

function zia3meta_plugin_menu() {
	add_options_page(
	'Zia3-JS-CSS Options',    // The title to be displayed in the browser window for this page.
	'Zia3-JS-CSS',            // The text to be displayed for this menu item
	'manage_options',         // Which type of users can see this menu item
	__FILE__,                 // The unique ID - that is, the slug - for this menu item
	'zia3meta_plugin_options' // The name of the function to call when rendering the page for this menu
	);
	$hook_suffix = add_plugins_page(
			'Zia3-JS-CSS Options',    // The title to be displayed in the browser window for this page.
			'Zia3-JS-CSS',            // The text to be displayed for this menu item
			'manage_options',         // Which type of users can see this menu item
			__FILE__,                 // The unique ID - that is, the slug - for this menu item
			'zia3meta_plugin_options' // The name of the function to call when rendering the page for this menu
	);
	// Use the hook suffix to compose the hook and register an action executed when plugin's options page is loaded
	add_action( 'load-' . $hook_suffix , 'zia3meta_load_function' );
}

function zia3meta_initialize_plugin_options() {
	 
	global $zia3_meta_fields, $zia3meta_css_dir, $zia3meta_js_dir, $zia3meta_css_url, $zia3meta_js_url, $zia3meta_selected_dir_css_label, $zia3meta_selected_js_css_label;

	$options = get_option( 'zia3meta_plugin_options' );

	if( isset( $options['zia3meta_plugin_options'] ) ) {
		$zia3meta_css_dir = $options['selected_dir_css'];
		$zia3meta_js_dir = $options['selected_dir_js'];
		$zia3meta_selected_dir_css_label = $options['selected_dir_css_label'];
		$zia3meta_selected_js_css_label = $options['selected_js_css_label'];
	}

	// If the plugin options don't exist, create them.
	if( false == get_option( 'zia3meta_plugin_options' ) ) {
		add_option( 'zia3meta_plugin_options', apply_filters( 'zia3meta_plugin_default_input_options', zia3meta_plugin_default_input_options() ) );
	} // end if

	// First, we register a section. This is necessary since all future options must belong to a
	add_settings_section(
	'zia3meta_general_settings_section', // ID used to identify this section and with which to register options
	'CSS and JS Options',                // Title to be displayed on the administration page
	'zia3meta_general_options_callback', // Callback used to render the description of the section
	'zia3meta_plugin_options'            // Page on which to add this section of options
	);

	// Next, we'll introduce the fields for inserting custom CSS files.
	add_settings_field(
	'css_options',                         // ID used to identify the field throughout the theme
	'CSS Options',                         // The label to the left of the option interface element
	'zia3meta_input_element_callback_css', // The name of the function responsible for rendering the option interface
	'zia3meta_plugin_options',             // The page on which this option will be displayed
	'zia3meta_general_settings_section',   // The name of the section to which this field belongs
	array(                                 // The array of arguments to pass to the callback. In this case, just a description.
	'Type in the absolute path for your CSS directory.'
			)
	);

	// Next, we'll introduce the fields for inserting custom JS files.
	add_settings_field(
	'js_options',                          // ID used to identify the field throughout the theme
	'JavaScript Options',                  // The label to the left of the option interface element
	'zia3meta_input_element_callback_js',  // The name of the function responsible for rendering the option interface
	'zia3meta_plugin_options',             // The page on which this option will be displayed
	'zia3meta_general_settings_section',   // The name of the section to which this field belongs
	array(                                 // The array of arguments to pass to the callback. In this case, just a description.
	'Type in the absolute path for your JavaScript directory.'
			)
	);
	 
	// Finally, we register the fields with WordPress
	register_setting(
	'zia3meta_plugin_options',
	'zia3meta_plugin_options',
	'zia3meta_plugin_validate_input'
			);
	 
} // end zia3meta_initialize_theme_options
add_action('admin_init', 'zia3meta_initialize_plugin_options');

function zia3meta_load_function() {
	 
	// Current admin page is the options page for our plugin, so do not display the notice
	// (remove the action responsible for this)
	remove_action( 'admin_notices', 'zia3meta_plugin_admin_notices' );
}

function zia3meta_plugin_admin_notices() {
	echo "<div id='notice' class='updated fade'><p>Zia3-CSS-JS Plugin is now configured. Please as an absolute path to your custom CSS and JS directories.</p></div>\n";
}

function zia3meta_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	screen_icon();
	settings_errors();
	?>

<form method="post" action="options.php">

	<?php settings_fields( 'zia3meta_plugin_options' ); ?>
	<?php do_settings_sections( 'zia3meta_plugin_options' ); ?>

	<?php submit_button(); ?>

</form>

<?php
echo '</div>';

}

/**
 * This function provides a simple description for the General Options page.
 *
 * It's called from the 'zia3meta_initialize_plugin_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function zia3meta_general_options_callback() {
echo '<p>' . __( 'Enter your custom CSS and JavaScript directories as an absolute path residing on your WordPress server including trailing forward slash.', 'zia3meta' ) . '</p>';
} // end zia3meta_general_options_callback

/**
 * This function provides a simple description for the Input page.
 *
 * It's called from the 'zia3meta_plugin_intialize_input_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function zia3meta_input_callback() {
	echo '<p>' . __( 'Enter your custom CSS and JavaScript directories as an absolute path residing on your WordPress server including trailing forward slash.', 'zia3meta' ) . '</p>';
} // end zia3meta_general_options_callback

function zia3meta_input_element_callback() {

    $options = get_option( 'zia3meta_plugin_options' );

    // Render the output
    echo '<label for="'.$options['selected_dir_label'].'">'.$options['selected_dir_label'].'</label><input type="text" id="selected_dir" name="zia3meta_plugin_options[selected_dir]" size="50" value="' . $options['selected_dir'] . '" />';

} // end zia3meta_input_element_callback
function zia3meta_input_element_callback_css() {

    $options = get_option( 'zia3meta_plugin_options' );

    // Render the output
    echo '<label for="'.get_stylesheet_directory().'">'.get_stylesheet_directory().'</label><input type="text" id="selected_dir_css" name="zia3meta_plugin_options[selected_dir_css]" size="50" value="' .      $options['selected_dir_css'] . '" /><br> The above directory base points to: <a href="'.get_stylesheet_directory_uri().$options['selected_dir_css'] .'">'.get_stylesheet_directory_uri().$options['selected_dir_css'] .'</a>';

} // end zia3meta_input_element_callback_css
function zia3meta_input_element_callback_js() {

    $options = get_option( 'zia3meta_plugin_options' );

    // Render the output
    echo '<label for="'.get_stylesheet_directory().'">'.get_stylesheet_directory().'</label><input type="text" id="selected_dir_js" name="zia3meta_plugin_options[selected_dir_js]" size="50" value="' .   $options['selected_dir_js'] . '" /><br> The above directory base points to: <a href="'.get_stylesheet_directory_uri().$options['selected_dir_js'] .'">'.get_stylesheet_directory_uri().$options['selected_dir_js'] .'</a>';

} // end zia3meta_input_element_callback_css
function zia3meta_plugin_validate_input( $input ) {

        // Create our array for storing the validated options
        $output = array();

        // Loop through each of the incoming options
        foreach( $input as $key => $value ) {

            // Check to see if the current option has a value. If so, process it.
            if( isset( $input[$key] ) ) {

                // Strip all HTML and PHP tags and properly handle quoted strings
                $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );

            } // end if

        } // end foreach

        // Return the array processing any additional functions filtered by this action
        return apply_filters( 'zia3meta_plugin_validate_input', $output, $input );

}


/**
 * Provides default values for the Input Options.
 */
function zia3meta_plugin_default_input_options() {
    $current_theme_dir = get_stylesheet_directory();
    $current_theme_url= get_stylesheet_directory_uri();

    $defaults = array(
                      'selected_dir'	        =>	'',
                      'selected_dir_css'        =>	'/css/',
                      'selected_dir_js'	        =>	'/js/',
                      'selected_dir_label'	=>	$current_theme_dir,
                      'selected_dir_css_label'  =>	$current_theme_dir,
                      'selected_dir_js_label'	=>	$current_theme_dir,
                      'selected_url_label'	=>	$current_theme_url,
                      'selected_url_css_label'  =>	$current_theme_url,
                      'selected_url_js_label'	=>	$current_theme_url
                );

return apply_filters( 'zia3meta_plugin_default_input_options', $defaults );

} // end zia3meta_plugin_default_input_options
?>