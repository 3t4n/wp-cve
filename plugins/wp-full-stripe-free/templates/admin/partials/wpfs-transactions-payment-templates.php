<script type="text/template" id="wpfs-modal-refund-payment">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><%- confirmationMessage %></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-primary js-refund-payment-dialog"><?php _e( 'Refund payment', 'wp-full-stripe-admin'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Cancel', 'wp-full-stripe-admin' ); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-modal-refund-payment-in-progress">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><?php _e("It takes 7 days for the refund to appear on the customer's card.", 'wp-full-stripe-admin'); ?></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-primary wpfs-btn-primary--loader" disabled><?php _e( 'Refund payment', 'wp-full-stripe-admin'); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-modal-capture-payment">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><%- confirmationMessage %></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-primary js-capture-payment-dialog"><?php _e( 'Capture payment', 'wp-full-stripe-admin'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Cancel', 'wp-full-stripe-admin' ); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-modal-capture-payment-in-progress">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><?php _e("A captured payment is charged immediately."); ?></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-primary wpfs-btn-primary--loader" disabled><?php _e( 'Capture payment', 'wp-full-stripe-admin'); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-modal-delete-payment">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><%- confirmationMessage %></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-danger js-delete-payment-dialog"><?php _e( 'Delete payment', 'wp-full-stripe-admin'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Keep payment', 'wp-full-stripe-admin' ); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-modal-delete-payment-in-progress">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><?php _e('After deleting this payment from the WordPress database, you will still be able to find it in Stripe.', 'wp-full-stripe-admin'); ?></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-danger wpfs-btn-danger--loader" disabled><?php _e('Delete payment', 'wp-full-stripe-admin'); ?></button>
    </div>
</script>
