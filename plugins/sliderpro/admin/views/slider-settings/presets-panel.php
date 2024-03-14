<?php
    $presets = get_option( 'sliderpro_setting_presets' );

    if ( $presets === false || empty( $presets ) ) {
        $presets = array( 'Default' => array() );
        $default_settings = BQW_SliderPro_Settings::getSettings();

        foreach ( $default_settings as $key => $value ) {
            $presets[ 'Default' ][ $key ] = $value[ 'default_value' ];
        }

        update_option( 'sliderpro_setting_presets', $presets );
    }

    echo '<label for="slider-setting-presets">' . __( 'Saved Presets', 'sliderpro' ) . '</label>';
    echo '<select id="slider-setting-presets" name="slider-setting-presets" class="slider-setting-presets">';

    foreach ( $presets as $preset_name => $preset_settings ) {
        echo '<option value="' . esc_attr( $preset_name ) . '"' . '>' . esc_html( $preset_name ) . '</option>';
    }

    echo '</select>';

    $update_presets_nonce = wp_create_nonce( 'update-presets' );

    $save_new_preset_url = admin_url( 'admin.php?page=sliderpro&action=update-presets&method=save-new' ) . '&up_nonce=' . $update_presets_nonce;
    $update_preset_url = admin_url( 'admin.php?page=sliderpro&action=update-presets&method=update' ) . '&up_nonce=' . $update_presets_nonce;
    $delete_preset_url = admin_url( 'admin.php?page=sliderpro&action=update-presets&method=delete' ) . '&up_nonce=' . $update_presets_nonce;

    echo '<a href="' . $save_new_preset_url . '" class="button update-presets">' . __( 'Save New', 'sliderpro' ) . '</a>';
    echo ' <a href="' . $update_preset_url . '" class="button update-presets">' . __( 'Update Preset', 'sliderpro' ) . '</a>';
    echo ' <a href="' . $delete_preset_url . '" class="button update-presets">' . __( 'Delete Preset', 'sliderpro' ) . '</a>';

    $hide_info = get_option( 'sliderpro_hide_inline_info' );

    if ( $hide_info != true ) {
?>
    <div class="inline-info presets-info">
        <input type="checkbox" id="show-hide-presets-info" class="show-hide-info">
        <label for="show-hide-presets-info" class="show-info"><?php _e( 'Show info', 'sliderpro' ); ?></label>
        <label for="show-hide-presets-info" class="hide-info"><?php _e( 'Hide info', 'sliderpro' ); ?></label>
        
        <div class="info-content">
            <p><?php _e( 'You can save setting configuration in order to easily reuse them for other sliders. After you decided on a certain configuration you just need to click on the <i>Save New</i> button and then you will be prompted to specify a name for the saved preset. The preset will then be saved and added to the list of saved presets. You can later update the configuration of a certain preset or delete it if you want.', 'sliderpro' ); ?></p>
        </div>
    </div>
<?php
    }
?>