<?php
    
    // direct access is disabled
    defined( 'ABSPATH' ) || exit;

    printf(
        '<input type="checkbox"  name="%1$s" id="%1$s" value="1" %2$s %3$s /><div class="end-label">%4$s</div>',
        $args['name'],
        $is_checked,
        $attr,
        esc_html( $end_label )
    );