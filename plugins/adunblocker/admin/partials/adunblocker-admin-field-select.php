<?php

/**
 * Provides the markup for a select field
 *
 * @link       http://digitalapps.com
 * @since      1.0.0
 *
 * @package    Adblock Detect
 * @subpackage AdUnblocker/admin/partials
 */

if ( ! empty( $atts['label'] ) ) {

    ?><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'employees' ); ?>: </label><?php

}

?><select
    aria-label="<?php esc_attr( _e( $atts['aria'], 'adunblocker' ) ); ?>"
    class="<?php echo esc_attr( $atts['class'] ); ?>"
    id="<?php echo esc_attr( $atts['id'] ); ?>"
    name="<?php echo esc_attr( $atts['name'] ); ?>"><?php

if ( ! empty( $atts['blank'] ) ) {

    ?><option value><?php esc_html_e( $atts['blank'], 'adunblocker' ); ?></option><?php

}

foreach ( $atts['selections'] as $selection ) {

    if ( is_array( $selection ) ) {

        $label = $selection['label'];
        $value = $selection['value'];

    } else {

        $label = strtolower( $selection );
        $value = strtolower( $selection );

    }

    if ( empty( $selection['attribute'] ) ) {
        $selection['attribute'] = '';
    }

    ?><option
        value="<?php echo esc_attr( $value ); ?>" <?php
        selected( $atts['value'], $value ); ?> <?php echo esc_attr( $selection['attribute'] ); ?>><?php

        esc_html_e( $label, 'adunblocker' );

    ?></option><?php

} // foreach

?></select>
<span class="description"><?php esc_html_e( $atts['description'], 'adunblocker' ); ?></span>
</label>
