<div class="wunderauto <?php esc_attr_e($class); // @phpstan-ignore-line ?>"
     data-id="<?php esc_attr_e($id); // @phpstan-ignore-line ?>">
    <p><?php echo wp_kses($message, wp_kses_allowed_html('post')); // @phpstan-ignore-line?></p>
</div>
