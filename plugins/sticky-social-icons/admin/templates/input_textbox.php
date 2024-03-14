<?php
    
    // direct access is disabled
    defined( 'ABSPATH' ) || exit;

    printf(
        '<input type="text" class="regular-text %3$s" name="%1$s" id="%1$s" value="%2$s" /> <div class="end-label">%4$s</div>',
        esc_attr( $args['name'] ),
        esc_attr( $db_value ),
        esc_attr( $css_class ),
        esc_attr( $end_label )
    );