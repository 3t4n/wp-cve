<input type="text" id="<?php echo esc_attr( sprintf( 'field_%s', $args['id'] ) ); ?>"
       name="<?php echo $name ?>"
       class="<?php echo isset($args['class']['input']) ? esc_attr($args['class']['input']) : 'border border-gray-200 py-2 px-3 text-grey-darkest w-full'; ?>"
       value="<?php echo esc_attr( $args['value'] ); ?>"
       <?php echo isset( $args['required'] ) && $args['required'] ? 'required="required"' : ''; ?>
>