<?php
/**
 * Hover Image Button
 *
 * Create and register the plugin options page in the WordPress settings menu.
 *
 * @link https://github.com/igpremuo/hover-image-button
 *
 * @package   Hover_Image_Button
 * @author    Ignacio Pérez
 * @license   GPLv2 or later
 *
 */

defined( 'HOVER_IMAGE_BUTTON' ) or die( 'No script kiddies please!' );

/**
 * Register action to add the settings menu and the settings page.
 */
add_action( 'admin_menu', 'hover_image_button_add_admin_menu' );
add_action( 'admin_init', 'hover_image_button_settings_init' );

/**
 * Add the Hover Image Button settings link to the settings menu.
 */
function hover_image_button_add_admin_menu(  ) { 
    add_submenu_page( 
        'options-general.php', 
        'Hover Image Button', 
        'Hover Image Button', 
        'manage_options', 
        'hover_image_button', 
        'hover_image_button_options_page' 
    );
}

/**
 * Initialize the settings form with all the sections and fields.
 */
function hover_image_button_settings_init(  ) { 
    register_setting( 'pluginPage', HOVER_IMAGE_BUTTON_SETTINGS );

    /*
     * Hover Background Color Section.
     */

    // Section title.
    add_settings_section(
        'hover_image_button_pluginPage_section_color', 
        __( 'On hover background color', 'hover-image-button' ), 
        'hover_image_button_settings_section_color_callback', 
        'pluginPage'
    );

    // Hover color.
    add_settings_field( 
        HOVER_IMAGE_BUTTON_COLOR, 
        __( 'Color', 'hover-image-button' ), 
        'hover_image_button_text_field_color_render', 
        'pluginPage', 
        'hover_image_button_pluginPage_section_color' 
    );

    // Hover Opacity.
    add_settings_field( 
        HOVER_IMAGE_BUTTON_OPACITY, 
        __( 'Opacity', 'hover-image-button' ), 
        'hover_image_button_text_field_opacity_render', 
        'pluginPage', 
        'hover_image_button_pluginPage_section_color' 
    );

    /*
     * Button Size Section.
     */

    // Section title.
    add_settings_section(
        'hover_image_button_pluginPage_section_size', 
        __( 'Buttons default size', 'hover-image-button' ), 
        'hover_image_button_settings_section_size_callback', 
        'pluginPage'
    );

    // Width.
    add_settings_field( 
        HOVER_IMAGE_BUTTON_WIDTH, 
        __( 'Width', 'hover-image-button' ), 
        'hover_image_button_text_field_width_render', 
        'pluginPage', 
        'hover_image_button_pluginPage_section_size' 
    );

    // Height.
    add_settings_field( 
        HOVER_IMAGE_BUTTON_HEIGHT, 
        __( 'Height', 'hover-image-button' ), 
        'hover_image_button_text_field_height_render', 
        'pluginPage', 
        'hover_image_button_pluginPage_section_size' 
    );

    /*
     * Custom CSS Section.
     */

    // Section title.
    add_settings_section(
        'hover_image_button_pluginPage_section_css', 
        __( 'Custom CSS code', 'hover-image-button' ), 
        'hover_image_button_settings_section_css_callback', 
        'pluginPage'
    );

    // Custom CSS textarea.
    add_settings_field( 
        HOVER_IMAGE_BUTTON_CSS, 
        __( 'Code', 'hover-image-button' ), 
        'hover_image_button_textarea_field_css_render', 
        'pluginPage', 
        'hover_image_button_pluginPage_section_css' 
    );
}

/**
 * Append the settings link into the links array of the plugins page.
 */
function hover_image_button_action_links( $links ) {
    $settings = '<a href="options-general.php?page=hover_image_button">' . __('Settings') . '</a>'; 
    array_unshift($links, $settings); 
    return $links; 
}

/**
 * Shows the color text field.
 */
function hover_image_button_text_field_color_render() { 
    // Get values
    $options = get_option( HOVER_IMAGE_BUTTON_SETTINGS );
    $name = HOVER_IMAGE_BUTTON_SETTINGS . '[' . HOVER_IMAGE_BUTTON_COLOR . ']';
    $value = $options[HOVER_IMAGE_BUTTON_COLOR];
    // Show field
    echo '<input type="text" name="' . $name . '" value="' . $value . '" maxlength="7" size="7"';
}

/**
 * Shows the opacity text field.
 */
function hover_image_button_text_field_opacity_render() { 
    // Get values
    $options = get_option( HOVER_IMAGE_BUTTON_SETTINGS );
    $name = HOVER_IMAGE_BUTTON_SETTINGS . '[' . HOVER_IMAGE_BUTTON_OPACITY . ']';
    $value = $options[HOVER_IMAGE_BUTTON_OPACITY];
    // Show field
    echo '<input type="text" name="' . $name . '" value="' . $value . '" maxlength="4" size="4"';
}

/**
 * Shows the width text field.
 */
function hover_image_button_text_field_width_render() { 
    // Get values
    $options = get_option( HOVER_IMAGE_BUTTON_SETTINGS );
    $name = HOVER_IMAGE_BUTTON_SETTINGS . '[' . HOVER_IMAGE_BUTTON_WIDTH . ']';
    $value = $options[HOVER_IMAGE_BUTTON_WIDTH];
    // Show field
    echo '<input type="text" name="' . $name . '" value="' . $value . '" maxlength="6" size="6"';
}

/**
 * Shows the height text field.
 */
function hover_image_button_text_field_height_render() { 
    // Get values
    $options = get_option( HOVER_IMAGE_BUTTON_SETTINGS );
    $name = HOVER_IMAGE_BUTTON_SETTINGS . '[' . HOVER_IMAGE_BUTTON_HEIGHT . ']';
    $value = $options[HOVER_IMAGE_BUTTON_HEIGHT];
    // Show field
    echo '<input type="text" name="' . $name . '" value="' . $value . '" maxlength="6" size="6"';
}

/**
 * Shows the CSS textarea field.
 */
function hover_image_button_textarea_field_css_render() { 
    // Get values
    $options = get_option( HOVER_IMAGE_BUTTON_SETTINGS );
    $name = HOVER_IMAGE_BUTTON_SETTINGS . '[' . HOVER_IMAGE_BUTTON_CSS . ']';
    $value = $options[HOVER_IMAGE_BUTTON_CSS];
    // Show field
    echo '<textarea name="' . $name . '" cols="60" rows="10">' . $value . '</textarea>';
}

/**
 * Shows the color section description.
 */
function hover_image_button_settings_section_color_callback() { 
    echo __( 'Select the color and the opacity when the mouse is hover the button.', 'hover-image-button' );
}

/**
 * Shows the size section description.
 */
function hover_image_button_settings_section_size_callback() { 
    echo __( 'Change the default size of the buttons. You must indicate the unit of the size using <b>px</b> for pixels, or <b>%</b> for percentage.', 'hover-image-button' );
}

/**
 * Shows the CSS section description.
 */
function hover_image_button_settings_section_css_callback() { 
    echo __( 'Include your own CSS code to customize the buttons.', 'hover-image-button');
}

/**
 * Create the plugin option page
 */
function hover_image_button_options_page() { 
    // Check user permissions
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    ?>
        <div class="wrap">
            <h2>Hover Image Button</h2>
            <form action="options.php" method="post">
                <p><?php _e( 'Configure the main parameters of the plugin.', 'hover-image-button' ) ?></p>
                <?php
                    settings_fields( 'pluginPage' );
                    do_settings_sections( 'pluginPage' );
                    submit_button();
                ?>
            </form>
        </div>
    <?php

}