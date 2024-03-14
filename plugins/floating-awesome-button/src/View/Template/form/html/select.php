<select id="<?php echo esc_attr( sprintf( 'field_%s', $args['id'] ) ); ?>"
    name="<?php echo esc_attr($name); ?>"
    class="<?php echo isset($args['class']['input']) ? esc_attr($args['class']['input']) : 'select2'; ?>"
    data-selected="<?php echo esc_attr( $args['value'] ); ?>">
    <?php if(!empty($options)): ?>
        <?php foreach($options as $option): ?>
            <option value="<?php echo esc_attr($option['id']) ?>"><?php echo esc_attr($option['text']) ?></option>
        <?php endforeach; ?>
    <?php endif; ?>
</select>