<div class="<?php echo isset($args['class']['container']) ? esc_attr ( $args['class']['container'] ) : 'flex'; ?>">
    <label for="<?php echo esc_attr( sprintf('switch_%s', $args['id']) ) ?>" class="flex cursor-pointer">
        <div class="relative">
            <input
                type="checkbox"
                id="<?php echo esc_attr( sprintf('switch_%s', $args['id']) ) ?>"
                class="option_settings switch sr-only"
                data-option="<?php echo esc_attr( sprintf('field_%s', $args['id']) ) ?>"
                <?php echo ( esc_attr( $args['value'] ) ) ? 'checked' : ''; ?>
            >
            <div class="block bg-gray-300 w-10 h-6 rounded-full"></div>
            <div class="fab absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition"></div>
        </div>
    </label>
    <input type="hidden"
           name="<?php echo esc_attr( $name ) ?>"
           id="<?php echo esc_attr( sprintf('field_%s', $args['id']) ) ?>"
           value="<?php echo esc_attr( $args['value'] ); ?>"
    >
    <span class="pl-2" style="padding-top:2px;"><?php echo esc_attr( $args['label']['text'] ); ?></span>
</div>