<?php

function pin_generator_acccess_key_settings() {

    // Authentication settings section
    add_settings_section(
        // Unique identifier for the section
        'pin_generator_acccess_key_settings_section',
        // Section Title
        __( 'Authentication', 'pin-generator' ),
        // Callback for an optional description
        'pin_generator_access_key_settings_section_callback',
        // Admin page to add section to
        'pin-generator-settings'
    );

    add_settings_field(
        // Unique identifier for field
        'pin_generator_access_key',
        // Field Title
        __( 'Access key', 'pin-generator'),
        // Callback for field markup
        'pin_generator_settings_access_key_callback',
        // Page to go on
        'pin-generator-settings',
        // Section to go in
        'pin_generator_acccess_key_settings_section'
      );


    register_setting(
        'pin_generator_options',
        'pin_generator_access_key',
    );
}
add_action( 'admin_init', 'pin_generator_acccess_key_settings' );

function fetch_templates_from_api() {

    // Get a list of templates from the API 
    //http://localhost:5002/pingenerator-43a15/europe-west3/api
    //https://europe-west3-pingenerator-43a15.cloudfunctions.net/api

    // Use the pin_generator_access_key in the api call
    $access_key = get_option( 'pin_generator_access_key' );
    $api_url = 'https://europe-west3-pingenerator-43a15.cloudfunctions.net/api/user/templates-from-access-key/' . $access_key;

    $response = wp_remote_get( $api_url );
   
    // Check for errors
    if (is_wp_error($response) || wp_remote_retrieve_response_code( $response ) != 200) {
        // Output error to console as an error message
        echo '<script>console.log(' . json_encode('Error fetching templates') . ');</script>';
        echo '<script>console.log(' . json_encode($response) . ');</script>';
        return [];
    }else{
        // echo '<script>console.log(' . json_encode('Successfully fetched templates') . ');</script>';
        // echo '<script>console.log(' . json_encode($response) . ');</script>';
        $templates = json_decode( wp_remote_retrieve_body( $response ), true );
    }

    // Check if json_decode() returned an array
    if (!is_array($templates)) {
        echo '<script>console.log(' . json_encode('Error. Template is not an array') . ');</script>';
        return [];
    }

    // Return the templates
    return $templates;
}

function pin_generator_design_settings() {

    // Design settings section
    add_settings_section(
        // Unique identifier for the section
        'pin_generator_design_settings_section',
        // Section Title
        __( 'Design Settings', 'pin-generator' ),
        // Callback for an optional description
        'pin_generator_design_settings_section_callback',
        // Admin page to add section to
        'pin-generator-settings'
    );

    add_settings_field(
        // Unique identifier for field
        'pin_generator_design_color',
        // Field Title
        __( 'Pin Background Color', 'pin-generator'),
        // Callback for field markup
        'pin_generator_settings_color_callback',
        // Page to go on
        'pin-generator-settings',
        // Section to go in
        'pin_generator_design_settings_section'
      );

      // Existing templates
    $templates = [
        'RandomTemplate' => 'Random template',
        'CenterWave' => 'Center Wave',
        'TextBlob' => 'Text Blob',
        'ColorBlobs' => 'Color Blobs',
        'WaveySplit' => 'Wavey Split',
        'SquareTextWithLandscapeImage' => 'Square Text With Landscape Image',
        'TextOverlay' => 'Text Overlay',
        'TextSideRight' => 'Text Side Right',
        'BigTopText' => 'Big Top Text',
        'StandardTemplate' => 'Standard Template',
        'LandscapeFriendly' => 'Landscape Friendly',
        'FullImageWithBanner' => 'Full Image With Banner',
        'PortraitFriendly' => 'Portrait Friendly',
        'ImageOnly' => 'Image Only',
        'BlankWithFrame' => 'Blank With Frame',
        'CircleImageWithBanner' => 'Circle Image With Banner'
    ];

    // Get a list of templates from the API 
    $api_templates = fetch_templates_from_api();

    // output api_templates
    // echo '<script>console.log(' . json_encode('Templates from API:') . ');</script>';
    // echo '<script>console.log(' . json_encode($api_templates) . ');</script>';

    // If templates were returned from the api
    // Add them to the templates array
    if (count($api_templates) > 0 && is_array($api_templates)) {
        // print out api templates to console
        foreach ($api_templates as $api_template) {
            //push the template to the array
            $templates[$api_template['id']] = $api_template['title'];
        }
    }
    
        
    // Add the templates to the settings page
    add_settings_field(
        // Unique identifier for field
        'pin_generator_design_template',
        // Field Title
        __( 'Template', 'pin-generator'),
        // Callback for field markup
        'pin_generator_settings_template_callback',
        // Page to go on
        'pin-generator-settings',
        // Section to go in
        'pin_generator_design_settings_section',
        $templates
      );

      add_settings_field(
        // Unique identifier for field
        'pin_generator_design_attribution',
        // Field Title
        __( 'Show us some love', 'pin-generator'),
        // Callback for field markup
        'pin_generator_settings_attribution_callback',
        // Page to go on
        'pin-generator-settings',
        // Section to go in
        'pin_generator_design_settings_section'
      );
      
    register_setting(
        'pin_generator_options',
        'pin_generator_design_settings'
    );
}
add_action( 'admin_init', 'pin_generator_design_settings' );

function pin_generator_access_key_settings_section_callback() {

    echo '<p>Get your FREE access key from <a href="https://pingenerator.com/profile" target="_blank">the Pin Generator website</a>.</p>';
}

function pin_generator_design_settings_section_callback() {

  esc_html_e( 'Update the design settings for your generated pins.', 'pin-generator' );
  echo '<p>Your custom templates will be loaded after you save your access key.</p>';
}

function pin_generator_settings_access_key_callback() {

    $access_key = get_option( 'pin_generator_access_key' );
  
    echo '<input type="text" id="pin_generator_access_key" name="pin_generator_access_key" value="' . esc_attr($access_key) . '" />';
}

function pin_generator_settings_template_callback($args) {

    $design_options = get_option( 'pin_generator_design_settings' );

	$template = '';
	if( isset( $design_options[ 'template' ] ) ) {
		$template = esc_attr( $design_options['template'] );
	}
  
    $html ='<div>';
    $html .= '<select id="pin_generator_template" name="pin_generator_design_settings[template]">';

    // Loop through the templates and add them to the select
    foreach ($args as $key => $value) {
        $html .= '<option value="' . $key . '"' . selected( $template, $key, false) . '>' . $value . '</option>';
    }
	$html .= '</select>';
    $html .='</div>';

    // Escape
    $arr = array(   
        'div' => array(), 
        'select' => array(
            'id' => array(),
            'name' => array()
        ), 
        'option' => array(
            'value' => array(),
            'selected' => array(),
        ));

    echo wp_kses($html, $arr);
}

function pin_generator_settings_color_callback() {

    $design_options = get_option( 'pin_generator_design_settings' );

	$color = '';
	if( isset( $design_options[ 'color' ] ) ) {
		$color = esc_html( $design_options['color'] );
	}

  //echo '<input type="text" id="pin_generator_color" name="pin_generator_design_settings[color]" value="#' . $color . '" />';
  echo '<input type="text" id="pin_generator_color" name="pin_generator_design_settings[color]" value="' . esc_attr($color) . '" class="pg-color-field" data-default-color="#27c3a6" />';
}

function pin_generator_settings_attribution_callback() {

    $design_options = get_option( 'pin_generator_design_settings' );

  //echo '<input type="text" id="pin_generator_color" name="pin_generator_design_settings[color]" value="#' . $color . '" />';
  echo '<input type="checkbox" id="pin_generator_attribution" name="pin_generator_design_settings[attribution]" value="1" ' . esc_attr(checked( "1", $design_options['attribution'], false )) . ' />';
  echo '<label for="pin_generator_attribution"> show "Generated by Pin Generator"</label>';
}