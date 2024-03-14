<script type="text/template" id="wpfs-modal-delete-saved-card">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><%- confirmationMessage %></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-danger js-delete-saved-card-dialog"><?php _e( 'Delete saved card', 'wp-full-stripe-admin'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Keep saved card', 'wp-full-stripe-admin' ); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-modal-delete-saved-card-in-progress">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><?php _e('After deleting this saved card from the WordPress database, you will still be able to find it in Stripe.', 'wp-full-stripe-admin'); ?></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-danger wpfs-btn-danger--loader" disabled><?php _e('Delete saved card', 'wp-full-stripe-admin'); ?></button>
    </div>
</script>
