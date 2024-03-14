<?php

/**
 * Provides the markup for any checkbox field
 *
 * @link       http://digitalapps.com
 * @since      1.0.0
 *
 * @package    Adblock Detect
 * @subpackage AdUnblocker/admin/partials
 */

?><label for="<?php echo esc_attr( $atts['id'] ); ?>">
    <input aria-role="checkbox"
        <?php checked( 'y', $atts['value'], true ); ?>
        class="<?php echo esc_attr( $atts['class'] ); ?>"
        id="<?php echo esc_attr( $atts['id'] ); ?>"
        name="<?php echo esc_attr( $atts['name'] ); ?>"
        value="<?php echo esc_attr( $atts['default_value'] ); ?>"
        type="checkbox" />
    <span class="description"><?php esc_html_e( $atts['description'], 'adunblocker' ); ?></span>
</label>