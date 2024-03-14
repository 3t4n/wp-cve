<?php
if ( post_password_required() ) {
    echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
} else {
    $excerpt = get_the_excerpt();                    

    if ( ! empty( $excerpt ) && 'no' !== $settings['show_excerpt'] ) {
        ?>
        <p itemprop="description" class="qodef-e-excerpt">
            <?php echo Soft_template_Core_Utils::postexcerpt($settings['excerpt_length']); ?>
        </p>
    <?php }
} ?>