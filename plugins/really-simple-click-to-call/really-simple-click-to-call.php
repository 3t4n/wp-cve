<?php

/*
Plugin Name: Really Simple Click To Call
Plugin URI: http://joenickdow.com
Description: Add a simple click to call bar to the bottom of your page on mobile devices.
Version: 1.0.6
Author: Joseph Nickdow
Author URI: http://joenickdow.com
License: License: GPLv2 or later
*/

// Add Admin Stuff

add_action( 'admin_menu', 'click_to_call_add_admin_menu' );
add_action( 'admin_init', 'click_to_call_settings_init' );
add_action( 'admin_enqueue_scripts', 'ctc_add_color_picker' );

//Add Click To Call Bar to Footer

add_action( 'wp_footer', 'click_to_call_code' );

// Load Color Picker

function ctc_add_color_picker( $hook ) {

    if( is_admin() ) {

        // Add Color Picker CSS
        wp_enqueue_style( 'wp-color-picker' );

        // Include Color Picker JS
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'js/ctc.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
    }
}

// Add to Menu > Tools

function click_to_call_add_admin_menu(  ) {

    add_submenu_page( 'options-general.php', 'Really Simple Click to Call', 'Really Simple Click to Call', 'manage_options', 'really_simple_click_to_call', 'click_to_call_options_page' );

}

// Adding Settings

function click_to_call_settings_init(  ) {

    register_setting( 'ctc_plugin_page', 'click_to_call_settings' );

    add_settings_section(
        'click_to_call_ctc_plugin_page_section',
        __( 'A simple plugin that adds click to call functionality to your WordPress site on mobile devices.', 'click_to_call' ),
        'click_to_call_settings_section_callback',
        'ctc_plugin_page'
    );

    add_settings_field(
        'click_to_call_enable',
        __( 'Enable Click to Call', 'click_to_call' ),
        'click_to_call_enable_render',
        'ctc_plugin_page',
        'click_to_call_ctc_plugin_page_section'
    );

    add_settings_field(
        'click_to_call_message',
        __( 'Your Click to Call Message', 'click_to_call' ),
        'click_to_call_message_render',
        'ctc_plugin_page',
        'click_to_call_ctc_plugin_page_section'
    );

    add_settings_field(
        'click_to_call_number',
        __( 'Your Click to Call Number', 'click_to_call' ),
        'click_to_call_number_render',
        'ctc_plugin_page',
        'click_to_call_ctc_plugin_page_section'
    );

    add_settings_field(
        'click_to_call_color',
        __( 'Click to Call Text Color', 'click_to_call' ),
        'click_to_call_color_render',
        'ctc_plugin_page',
        'click_to_call_ctc_plugin_page_section'
    );

    add_settings_field(
        'click_to_call_bg',
        __( 'Click to Call Background Color', 'click_to_call' ),
        'click_to_call_bg_render',
        'ctc_plugin_page',
        'click_to_call_ctc_plugin_page_section'
    );
}

// Render Admin Input

function click_to_call_enable_render(  ) {

    $options = get_option( 'click_to_call_settings' );
    ?>
    <input name="click_to_call_settings[click_to_call_enable]" type="hidden" value="0" />
    <input name="click_to_call_settings[click_to_call_enable]" type="checkbox" value="1" <?php checked( '1', $options['click_to_call_enable'] ); ?> />

    <?php

}


function click_to_call_message_render(  ) {

    $options = get_option( 'click_to_call_settings' );
    ?>
    <input type='text' placeholder="ex. Call Now!" name='click_to_call_settings[click_to_call_message]' value='<?php echo $options['click_to_call_message']; ?>'>
    <?php

}


function click_to_call_number_render(  ) {

    $options = get_option( 'click_to_call_settings' );
    ?>
    <input type='text' placeholder="ex. 5555555555" name='click_to_call_settings[click_to_call_number]' value='<?php echo $options['click_to_call_number']; ?>'>
    <?php

}


function click_to_call_color_render(  ) {

    $options = get_option( 'click_to_call_settings' );
    ?>
    <input type='text' class="color-field"  name='click_to_call_settings[click_to_call_color]' value='<?php echo $options['click_to_call_color']; ?>'>
    <?php

}


function click_to_call_bg_render(  ) {

    $options = get_option( 'click_to_call_settings' );
    ?>
    <input type='text' class="color-field" name='click_to_call_settings[click_to_call_bg]' value='<?php echo $options['click_to_call_bg']; ?>'>
    <?php

}

function click_to_call_settings_section_callback(  ) {
    echo __( 'Enter your information in the fields below. If you don\'t enter anything into the message area a phone icon will still show. Google Analytics event tracking is added and will show under the events section as \'Phone\'. The plugin will only show on devices with a screen width under 736px. ', 'click_to_call' );

}

// Output Code If Enabled

function click_to_call_code() {
    $options = get_option( 'click_to_call_settings' );
    if ($options['click_to_call_enable'] == '1') {
        echo '<a href="tel:' . $options['click_to_call_number'] . '" onclick="ga(\'send\',\'event\',\'Phone\',\'Click To Call\', \'Phone\')"; style="color:' . $options['click_to_call_color'] . ' !important; background-color:' . $options['click_to_call_bg'] . ';" class="ctc_bar" id="click_to_call_bar""> <span class="icon  ctc-icon-phone"></span>' . $options['click_to_call_message'] . '</a>';
        wp_enqueue_style('ctc-styles', plugin_dir_url( __FILE__ ) . 'css/ctc_style.css' );
    } 
}

// Display Admin Form

function click_to_call_options_page(  ) {

    ?>
    <form action='options.php' method='post'>

        <h1>Really Simple Click to Call Bar</h1>

        <?php
        settings_fields( 'ctc_plugin_page' );
        do_settings_sections( 'ctc_plugin_page' );
        submit_button();
        ?>

    </form>
    <?php

}

?>
