<script type="text/template" id="wpfs-modal-refund-donation">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><%- confirmationMessage %></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-primary js-refund-donation-dialog"><?php _e( 'Refund donation', 'wp-full-stripe-admin'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Cancel', 'wp-full-stripe-admin' ); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-modal-refund-donation-in-progress">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><?php _e("It takes 7 days for the refund to appear on the customer's card.", 'wp-full-stripe-admin'); ?></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-primary wpfs-btn-primary--loader" disabled><?php _e( 'Refund donation', 'wp-full-stripe-admin'); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-modal-cancel-donation">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><%- confirmationMessage %></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-primary js-cancel-donation-dialog"><?php _e( 'Cancel donation', 'wp-full-stripe-admin'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Keep donation', 'wp-full-stripe-admin' ); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-modal-cancel-donation-in-progress">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><?php _e("The recurring donation will be cancelled immediately.", 'wp-full-stripe-admin'); ?></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-primary wpfs-btn-primary--loader" disabled><?php _e( 'Cancel donation', 'wp-full-stripe-admin'); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-modal-delete-donation">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><%- confirmationMessage %></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-danger js-delete-donation-dialog"><?php _e( 'Delete donation', 'wp-full-stripe-admin'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Keep donation', 'wp-full-stripe-admin' ); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-modal-delete-donation-in-progress">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><?php _e('After deleting this donatioin from the WordPress database, you will still be able to find it in Stripe.', 'wp-full-stripe-admin'); ?></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-danger wpfs-btn-danger--loader" disabled><?php _e('Delete donation', 'wp-full-stripe-admin'); ?></button>
    </div>
</script>
