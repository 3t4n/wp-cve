<?php
    /** @var $view MM_WPFS_Admin_CheckoutDonationFormView */
    /** @var $form */
?>
<div class="wpfs-form-group wpfs-product-image-selector">
    <label for="" class="wpfs-form-label"><?php esc_html_e( 'Donation product image', 'wp-full-stripe-admin' ); ?></label>
    <div class="wpfs-form-image-preview">
        <img class="js-image-selector-preview" src="<?php echo empty( $form->image ) ? '' : $form->image; ?>" style="width: auto; max-height: 128px; <?php echo empty( $form->image ) ? 'display: none;' : ''; ?>">
    </div>
    <a class="wpfs-btn wpfs-btn-text wpfs-btn-text--blue js-select-media" href=""><?php esc_html_e( 'Select or upload image', 'wp-full-stripe-admin' ); ?></a>
    <a class="wpfs-btn wpfs-btn-text js-discard-image" href=""><?php esc_html_e( 'Discard', 'wp-full-stripe-admin' ); ?></a>
</div>