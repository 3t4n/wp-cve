<?php
    /** @var $data */
?>
<div class="wpfs-inline-message wpfs-inline-message--info wpfs-inline-message--w448">
    <div class="wpfs-inline-message__inner">
        <div class="wpfs-inline-message__title"><?php esc_html_e( 'Add custom CSS styles', 'wp-full-stripe-admin'); ?></div>
        <p>
            <?php esc_html_e( 'Use this form ID to add custom styles to this form:', 'wp-full-stripe-admin'); ?><br>
            <strong><?php echo $data->cssSelector; ?></strong>&nbsp;&nbsp;&nbsp;<a class="wpfs-btn wpfs-btn-link js-copy-form-css-id" data-form-css-id="<?php echo $data->cssSelector; ?>"><?php esc_html_e( 'Copy to clipboard', 'wp-full-stripe-admin'); ?></a>
        </p>
        <p>
            <a class="wpfs-btn wpfs-btn-link" href="https://support.paymentsplugin.com/article/45-customizing-forms-with-css" target="_blank"><?php esc_html_e( 'Learn more about custom CSS styles', 'wp-full-stripe-admin'); ?></a>
        </p>
    </div>
</div>
