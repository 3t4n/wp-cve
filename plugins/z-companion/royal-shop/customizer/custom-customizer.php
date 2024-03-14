<?php
/**
 * Sanitization for textarea field
 */
function z_companion_sanitize_textarea( $input ){
    global $allowedposttags;
    $output = wp_kses( $input, $allowedposttags );
    return $output;
}
function z_companion_sanitize_text( $string ) {
    return wp_kses_post( balanceTags( $string ) );
}
function z_companion_sanitize_textarea_html( $input ) {
    $output = esc_html( $input );
    return $output;
}
/**
 * Returns a sanitized filepath if it has a valid extension.
 */
function z_companion_sanitize_upload( $upload ) {
    $return = '';
    $fype = wp_check_filetype( $upload );
    if ( $fype["ext"] ) {
        $return = esc_url_raw( $upload );
    }
    return $return;
}
/**
 * Checkbox sanitization callback
 *
 */
function z_companion_sanitize_checkbox( $checked ) {
    // Boolean check.
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Select sanitization callback
 */
function z_companion_sanitize_select( $input, $setting ) {
    // Ensure input is a slug.
    $input = sanitize_key( $input );
    
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;
    
    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}
function z_companion_sanitize_number( $val, $setting ){

            $input_attrs = $setting->manager->get_control( $setting->id )->input_attrs;

            if ( isset( $input_attrs ) ) {

                $input_attrs['min']  = isset( $input_attrs['min'] ) ? $input_attrs['min'] : 0;
                $input_attrs['step'] = isset( $input_attrs['step'] ) ? $input_attrs['step'] : 1;

                if ( isset( $input_attrs['max'] ) && $val > $input_attrs['max'] ) {
                    $val = $input_attrs['max'];
                } elseif ( $val < $input_attrs['min'] ) {
                    $val = $input_attrs['min'];
                }

                        $dv = $val / $input_attrs['step'];

                        $dv = round( $dv );

                        $val = $dv * $input_attrs['step'];

                    $val = number_format( (float) $val, 2, '.', '' );
                if ( $val == (int) $val ) {
                    $val = (int) $val;
                }
            }

            return is_numeric( $val ) ? $val : 0;
        }
// radio
function z_companion_sanitize_radio( $input, $setting ){

  // Ensure input is a slug.
  $input = sanitize_key( $input );

  // Get list of choices from the control associated with the setting.
  $choices = $setting->manager->get_control( $setting->id )->choices;

  // If the input is a valid key, return it; otherwise, return the default.
  return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}
//Repeater Control
/**
 * Sanitize repeater control.
 *
 * @param object $value Control output.
 *
 * @return object
 */
function z_companion_Repeater_sanitize( $value ) {
    $value_decoded = json_decode( $value, true );

    if ( ! empty( $value_decoded ) ) {
        foreach ( $value_decoded as $boxk => $box ) {
            foreach ( $box as $key => $value ) {

                $value_decoded[ $boxk ][ $key ] = wp_kses_post( force_balance_tags( $value ) );

            }
        }

        return json_encode( $value_decoded );
    }

    return $value;
}
// Multiple Checkbox Show
function z_companion_checkbox_explode( $values ){
    $multi_values = !is_array( $values ) ? explode( ',', $values ) : $values;
    return !empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
}