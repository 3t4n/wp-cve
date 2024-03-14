<?php
/********************************/
/***     CONTENT SETTINGS     ***/
/********************************/
function cpln_initialize_content_settings() {
    // If the options don't exist, create them.
    if( !get_option( 'cpln_content_settings' ) ) {
        add_option( 'cpln_content_settings' );
    }
	
    add_settings_section(
        'cpln_content_settings_section',	// ID used to identify this section and with which to register options
        'Leave Notice Content',             // Title to be displayed on the administration page
        'cpln_content_settings_desc', 		// Callback used to render the description of the section
        'cpln_content_settings'     		// Page on which to add this section of options
    );
     
    // Next, we'll introduce the fields for toggling the visibility of content elements.
    add_settings_field( 
        'cpln_title_content',                  	// ID used to identify the field throughout the theme
        'Pop Up Title',                        // The label to the left of the option interface element
        'cpln_title_content_output', 			// The name of the function responsible for rendering the option interface
        'cpln_content_settings',    		// The page on which this option will be displayed
        'cpln_content_settings_section',  	// The name of the section to which this field belongs
        array(                              // The array of arguments to pass to the callback. In this case, just a description.
            ''
        )
    );
     
    add_settings_field( 
        'cpln_body_content',
        'Pop Up Message',
        'cpln_warning_body_output',
        'cpln_content_settings',
        'cpln_content_settings_section',
        array(                              
            ''
        )
    );
	
    // register the fields with WordPress
    register_setting('cpln_content_settings', 'cpln_content_settings');
     
} // end cpln_initialize_content_settings
add_action('admin_init', 'cpln_initialize_content_settings');


/********************************/
/***     STYLING SETTINGS     ***/
/********************************/
function cpln_initialize_styling_settings(){
	if( !get_option( 'cpln_styling_settings' ) ) {
        add_option( 'cpln_styling_settings' );
    }
}
//add_action('admin_init', 'cpln_initialize_styling_settings');


/**********************************/
/***     EXCLUSION SETTINGS     ***/
/**********************************/
function cpln_initialize_exclusions(){
	if( !get_option( 'cpln_exclusions' ) ) {
        add_option( 'cpln_exclusions' );
    }
	
	add_settings_section(
        'cpln_exclusion_section',
        'Leave Notice Exclusions',
        'cpln_exclusions_desc',
        'cpln_exclusions'
    );
	
	add_settings_field( 
        'cpln_exclusion_list',
        'Exclusion List',
        'cpln_exclusion_list_output',
        'cpln_exclusions',
        'cpln_exclusion_section',
        array(                              
            ''
        )
	);
	
	// register the fields with WordPress
    register_setting('cpln_exclusions', 'cpln_exclusions');
}
add_action('admin_init', 'cpln_initialize_exclusions');


/******************************/
/***     OTHER SETTINGS     ***/
/******************************/
function cpln_initialize_other_settings(){
	if( !get_option( 'cpln_other_settings' ) ) {
        add_option( 'cpln_other_settings' );
    }
	
	add_settings_section(
        'cpln_other_setting_section',
        'Other Leave Notice Settings',
        'cpln_other_settings_desc',
        'cpln_other_settings'
    );
	
	add_settings_field( 
        'cpln_redirect_timer_bool',
        'Enable Redirect Timer',
        'cpln_redirect_timer_bool_output',
        'cpln_other_settings',
        'cpln_other_setting_section',
        array(
            ''
        )
	);
	add_settings_field( 
        'cpln_redirect_time',
        'Time Until Auto-Redirect',
        'cpln_redirect_time_output',
        'cpln_other_settings',
        'cpln_other_setting_section',
        array(
            ''
        )
	);
	
	// register the fields with WordPress
    register_setting('cpln_other_settings', 'cpln_other_settings');
}
add_action('admin_init', 'cpln_initialize_other_settings');