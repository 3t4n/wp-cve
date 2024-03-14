<?php

function n360_do_settings_sections() {
    echo '<div class="n360-row">
            <div class="n360-column">
                <h2>Splash Screen Images</h2><hr>';
                n360_settings_checkbox( 'enable_n360_ss', 'Enable Splash Image' );
                n360_splash_image();
                n360_settings_checkbox( 'enable_bg_img', 'Enable Background Image' );
                n360_bgnd_img();
                n360_bgnd_color();
                n360_settings_checkbox( 'run_always', 'Run always on home page' );
            echo '</div>
            <div class="n360-column">
                <h2>Timing Envelope</h2><hr>';
                echo '<img src="' . N360_SPLASH_PAGE_ROOT_URL . 'assets/images/envelope.png" alt="Timing Envelope">';
                n360_number( 'timing', 'delay' );
                n360_number( 'timing', 'fadein' );
                n360_number( 'timing', 'sustain' );
                n360_number( 'timing', 'fadeout' );
                n360_number( 'timing', 'resume' );
            echo '</div>';
    echo '</div>';
};

function n360_settings_checkbox( $id, $label ) {
    global $n360_defaults;
    $options = get_option( 'n360_config' );

    if ( isset( $options[$id] ) ) {
        $value = $options[$id];
    } else {
        $value = $n360_defaults[$id];
    }

    $html = '<p><input type="checkbox" id="' . $id . '" name="n360_config[' . $id . ']"';
    $html .= 'value="1"' . checked( 1, $value, false ) . '/>';
    $html .= '&nbsp;';
    $html .= '<label for="' . $id  . '">' . $label . '</label></p>';
    echo $html;
}

function n360_enable_bgnd_img( $label ) {
    global $n360_defaults;
    $options = get_option( 'n360_config' );

    if ( isset( $options['enable_bg_img'] ) ) {
        $value = $options['enable_bg_img'];
    } else {
        $value = $n360_defaults['enable_bg_img'];
    }

    $html = '<p><input type="checkbox" id="n360_enable_bg_img" name="n360_config[enable_bg_img]"';
    $html .= 'value="1"' . checked( 1, $value, false ) . '/>';
    $html .= '&nbsp;';
    $html .= '<label for="enable_background">' . $label . '</label></p>';
    echo $html;
}

function n360_splash_image() {
    global $n360_defaults;
    $options = get_option( 'n360_config' );

    if ( isset( $options['splash_image'] ) ) {
        $value = $options['splash_image'];
    } else {
        $value = $n360_defaults['splash_image'];
    }

    echo '<input type="text" 
        id="n360_splash_image_path" 
        size="30%" 
        value="' . $value . '" 
        name="n360_config[splash_image]">
        <a data-multiple="false" data-target="#n360_splash_image_path" 
        data-type="image" href="#" class="button upload-btn">Select Image</a>';
}

function n360_bgnd_img() {
    global $n360_defaults;
    $options = get_option( 'n360_config' );

    if ( isset( $options['background_image'] ) ) {
        $value = $options['background_image'];
    } else {
        $value = $n360_defaults['background_image'];
    }

    echo '<input type="text" 
        id="n360_bgnd_image_path" 
        size="30" 
        value="' . $value . '" 
        name="n360_config[background_image]">
        <a data-multiple="false" data-target="#n360_bgnd_image_path" 
        data-type="image" href="#" class="button upload-btn">Select Image</a>';
}

function n360_bgnd_color() {
    global $n360_defaults;
    $options = get_option( 'n360_config' );

    if ( isset( $options['background_color'] ) ) {
        $value = $options['background_color'];
    } else {
        $value = $n360_defaults['background_color'];
    }
    echo '<p><input type="text"
        value="' . $value . '" 
        class="my-color-field" 
        name="n360_config[background_color]" 
        data-default-color="' . $n360_defaults['background_color'] . '" />
        (if no bg-image is selected)</p>';
}

function n360_number($id, $label) {
    global $n360_defaults;
    $options = get_option( 'n360_config' );

    if ( isset( $options[$id][$label] ) ) {
        $value = $options[$id][$label];
    } else {
        $value = $n360_defaults[$id][$label];
    }

    echo '<p>
    <input type="number"
           id="n360-' . $label . '" 
           class="n360-timing"
           name="n360_config[' . $id . '][' . $label . ']"
           min="0.5"
           max="5"
           step="0.5"
           value="' . $value . '">
    <label for="n360-' . $label . '">' . $label . ' (0.5 - 5 sec)</label></p>';
}