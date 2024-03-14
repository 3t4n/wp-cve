<?php
/**
 * Config
 *
 * @package WordPress
 * @subpackage seed_s404f
 * @since 0.1.0
 */

/**
 * Config Settings
 */
function seed_s404f_get_options(){

    /**
     * Create new menus
     */

    $seed_s404f_options[ ] = array(
        "type" => "menu",
        "menu_type" => "add_options_page",
        "page_name" => __( "404 Page by SeedProd", 'seedprod' ),
        "menu_slug" => "seed_s404f",
        "layout" => "2-col"
    );

    /**
     * Settings Tab
     */
    $seed_s404f_options[ ] = array(
        "type" => "tab",
        "id" => "seed_s404f_setting",
        "label" => __( "Page Settings", 'seedprod' ),
    );

    $seed_s404f_options[ ] = array(
        "type" => "setting",
        "id" => "seed_s404f_settings_content",
    );

    $seed_s404f_options[ ] = array(
        "type" => "section",
        "id" => "seed_s404f_section_general",
        "label" => __( "General", 'seedprod' ),
    );

    $seed_s404f_options[ ] = array(
        "type" => "radio",
        "id" => "status",
        "label" => __( "Status", 'seedprod' ),
        "option_values" => array(
            '0' => __( 'Disabled', 'seedprod' ),
            '1' => __( 'Enable 404 Page', 'seedprod' ),
        ),
        "desc" => __( "This will replace your theme's 404 page with a custom 404 page.", 'seedprod' ),
        "default_value" => "0"
    );

    // Page Setttings
    $seed_s404f_options[ ] = array(
        "type" => "section",
        "id" => "seed_s404f_section_page_settings",
        "label" => __( "Page Settings", 'seedprod' )
    );

    $seed_s404f_options[ ] = array(
        "type" => "upload",
        "id" => "logo",
        "label" => __( "Logo", 'seedprod' ),
        "desc" => __('Upload a logo or other image.', 'seedprod'),
    );

    $seed_s404f_options[ ] = array(
        "type" => "textbox",
        "id" => "headline",
        "class" => "large-text",
        "label" => __( "Headline", 'seedprod' ),
        "desc" => __( "Enter a headline for your page.", 'seedprod' ),
        'default'   => __( "404 Page by SeedProd", 'seedprod' ),
    );

    $seed_s404f_options[ ] = array(
        "type" => "wpeditor",
        "id" => "description",
        "label" => __( "Message", 'seedprod' ),
        "desc" => __( "Enter your 404 page message.", 'seedprod' ),
        "class" => "large-text"
    );

    $seed_s404f_options[ ] = array(
        "type" => "checkbox",
        "id" => "search_form",
        "label" => __( "Enable WordPress Search Form", 'seedprod' ),
        "desc" => __("This will enable the WordPress Search Form", 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        ),
        "default" => "1",
    );

    $seed_s404f_options[ ] = array(
        "type" => "textbox",
        "id" => "twitter_url",
        "class" => "large-text",
        "label" => __( "Twitter Social Profile", 'seedprod' ),
        "desc" => __( "Enter your Twitter url to display a social icon.", 'seedprod' ),
    );

    $seed_s404f_options[ ] = array(
        "type" => "textbox",
        "id" => "facebook_url",
        "class" => "large-text",
        "label" => __( "Facebook Social Profile", 'seedprod' ),
        "desc" => __( "Enter your Facebook url to display a social icon.", 'seedprod' ),
    );

     $seed_s404f_options[ ] = array( "type" => "radio",
        "id" => "footer_credit",
        "label" => __("Powered By SeedProd", 'seedprod'),
        "option_values" => array('0'=>__('Nope - Got No Love', 'seedprod'),'1'=>__('Yep - I Love You Man', 'seedprod')),
        "desc" => __("Can we show a <strong>cool stylish</strong> footer credit at the bottom the page.", 'seedprod'),
        "default_value" => "0",
    );



    /**
     * Design Tab
     */
    $seed_s404f_options[ ] = array(
        "type" => "tab",
        "id" => "seed_s404f_design",
        "label" => __( "Design Settings", 'seedprod' )
    );

    $seed_s404f_options[ ] = array(
        "type" => "setting",
        "id" => "seed_s404f_settings_design"
    );


    // Background
    $seed_s404f_options[ ] = array(
        "type" => "section",
        "id" => "seed_s404f_section_background",
        "label" => __( "Background", 'seedprod' )
    );



    $seed_s404f_options[ ] = array(
        "type" => "checkbox",
        "id" => "bg_screenshot",
        "label" => __( "Background Screenshot", 'seedprod' ),
        "desc" => __("This will capture a screenshot of your home page and use it as the background. Note: It may take a few minutes for the initial screenshot to be generated.", 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        ),
    );


    $seed_s404f_options[ ] = array(
        "type" => "upload",
        "id" => "bg_image",
        "desc" => __('This will override the screenshot image if set.', 'seedprod'),
        "label" => __( "Background Image", 'seedprod' ),
    );


    $seed_s404f_options[ ] = array(
        "type" => "color",
        "id" => "bg_color",
        "label" => __( "Background Color", 'seedprod' ),
        "default_value" => "#fafafa",
        "validate" => 'color',
    );


    $seed_s404f_options[ ] = array(
        "type" => "checkbox",
        "id" => "bg_cover",
        "label" => __( "Responsive Background", 'seedprod' ),
        "desc" => __("Scale the background image to be as large as possible so that the background area is completely covered by the background image. Some parts of the background image may not be in view within the background positioning area.", 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        ),
        "default" => "1",
    );

    $seed_s404f_options[ ] = array(
        "type" => "select",
        "id" => "bg_repeat",
        "desc" => __('This setting is not applied if Responsive Background is checked', 'seedprod' ),
        "label" => __( "Background Repeat", 'seedprod' ),
        "option_values" => array(
            'no-repeat' => __( 'No-Repeat', 'seedprod' ),
            'repeat' => __( 'Tile', 'seedprod' ),
            'repeat-x' => __( 'Tile Horizontally', 'seedprod' ),
            'repeat-y' => __( 'Tile Vertically', 'seedprod' ),
        )
    );


    $seed_s404f_options[ ] = array(
        "type" => "select",
        "id" => "bg_position",
        "desc" => __('This setting is not applied if Responsive Background is checked', 'seedprod' ),
        "label" => __( "Background Position", 'seedprod' ),
        "option_values" => array(
            'left top' => __( 'Left Top', 'seedprod' ),
            'left center' => __( 'Left Center', 'seedprod' ),
            'left bottom' => __( 'Left Bottom', 'seedprod' ),
            'right top' => __( 'Right Top', 'seedprod' ),
            'right center' => __( 'Right Center', 'seedprod' ),
            'right bottom' => __( 'Right Bottom', 'seedprod' ),
            'center top' => __( 'Center Top', 'seedprod' ),
            'center center' => __( 'Center Center', 'seedprod' ),
            'center bottom' => __( 'Center Bottom', 'seedprod' ),
        )
    );

    $seed_s404f_options[ ] = array(
        "type" => "select",
        "id" => "bg_attahcment",
        "desc" => __('This setting is not applied if Responsive Background is checked', 'seedprod' ),
        "label" => __( "Background Attachment", 'seedprod' ),
        "option_values" => array(
            'fixed' => __( 'Fixed', 'seedprod' ),
            'scroll' => __( 'Scroll', 'seedprod' ),
        )
    );


    // Text
    $seed_s404f_options[ ] = array(
        "type" => "section",
        "id" => "seed_s404f_section_text",
        "label" => __( "Text", 'seedprod' )
    );


    $seed_s404f_options[ ] = array(
        "type" => "color",
        "id" => "link_color",
        "label" => __( "Link Color", 'seedprod' ),
        "default_value" => "#27AE60",
        "validate" => 'required,color',
    );




    $seed_s404f_options[ ] = array(
        "type" => "select",
        "id" => "text_font",
        "label" => __( "Text Font", 'seedprod' ),
        "option_values" => apply_filters('seed_s404f_fonts',array(
            '_arial'     => 'Arial',
            '_arial_black' =>'Arial Black',
            '_georgia'   => 'Georgia',
            '_helvetica_neue' => 'Helvetica Neue',
            '_impact' => 'Impact',
            '_lucida' => 'Lucida Grande',
            '_palatino'  => 'Palatino',
            '_tahoma'    => 'Tahoma',
            '_times'     => 'Times New Roman',
            '_trebuchet' => 'Trebuchet',
            '_verdana'   => 'Verdana',
            )),
    );





    // Template
    $seed_s404f_options[ ] = array(
        "type" => "section",
        "id" => "seed_s404f_section_template",
        "label" => __( "Template", 'seedprod' )
    );


    $seed_s404f_options[ ] = array(
        "type" => "textarea",
        "id" => "custom_css",
        "class" => "large-text",
        "label" => __( "Custom CSS", 'seedprod' ),
        "desc" => __('Need to tweaks the styles? Add your custom CSS here.','seedprod'),
    );


    /**
     * Advanced Tab
     */
    $seed_s404f_options[ ] = array(
        "type" => "tab",
        "id" => "seed_s404f_advanced",
        "label" => __( "Advanced", 'seedprod' )
    );

    $seed_s404f_options[ ] = array(
        "type" => "setting",
        "id" => "seed_s404f_settings_advanced"
    );


    // Scripts
    $seed_s404f_options[ ] = array(
        "type" => "section",
        "id" => "seed_s404f_section_scripts",
        "label" => __( "Scripts", 'seedprod' )
    );

    $seed_s404f_options[ ] = array(
        "type" => "checkbox",
        "id" => "enable_wp_head_footer",
        "label" => __( "Enable 3rd Party Plugins", 'seedprod' ),
        "desc" => __("Turn off 3rd party plugins if you are having diplay issues on the 404 page. No other plugins will run on the 404 page when unchecked.", 'seedprod'),
        "option_values" => array(
             '1' => __( 'Disable', 'seedprod' ),
        ),
        "default" => "1",
    );

    $seed_s404f_options[ ] = array(
        "type" => "textarea",
        "id" => "header_scripts",
        "label" => __( "Header Scripts", 'seedprod' ),
        "desc" => __('Enter any custom scripts. You can enter Javascript or CSS. This will be rendered before the closing head tag.', 'seedprod'),
        "class" => "large-text"
    );

    $seed_s404f_options[ ] = array(
        "type" => "textarea",
        "id" => "footer_scripts",
        "label" => __( "Footer Scripts", 'seedprod' ),
        "desc" => __('Enter any custom scripts. This will be rendered before the closing body tag.', 'seedprod'),
        "class" => "large-text"
    );


    return $seed_s404f_options;

}
