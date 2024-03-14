<input
    id="<?php echo esc_attr($args['label_for']); ?>"
    type="text"
    name="pushly_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
    placeholder="Replace with Pushly Domain Key"
    value="<?php echo isset($options[$args['label_for']]) ? esc_attr($options[$args['label_for']]) : null; ?>"
    style="width: 350px;"
>