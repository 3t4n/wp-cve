<?php

/**
 * Provides the markup for any WP Editor field
 *
 * @link       http://digitalapps.com
 * @since      1.0.0
 *
 * @package    AdUnblocker
 * @subpackage AdUnblocker/admin/partials
 */

if ( ! empty( $atts['label'] ) ) {

    ?><label for="<?php

    echo esc_attr( $atts['id'] );

    ?>"><?php

        esc_html_e( $atts['label'], 'adunblocker' );

    ?>: </label><?php

} ?>

<textarea
    id="<?php echo esc_attr( $atts['id'] ); ?>"
    name="<?php echo esc_attr( $atts['name'] ); ?>"
    disabled>
    <?php echo esc_html_e( $atts['value'] ); ?>
</textarea>

<span class="description"><?php esc_html_e( $atts['description'], 'adunblocker' ); ?></span>