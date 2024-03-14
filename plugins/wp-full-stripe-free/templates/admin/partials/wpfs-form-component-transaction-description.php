<?php
    /** @var $view MM_WPFS_Admin_SaveCardFormView */
    /** @var $form */
    /** @var $data */
?>
<div class="wpfs-form-group">
    <label for="<?php $view->transactionDescription()->id(); ?>" class="wpfs-form-label wpfs-form-label--actions">
        <?php $view->transactionDescription()->label(); ?>
        <div class="wpfs-form-label__actions">
            <a class="wpfs-btn wpfs-btn-link js-insert-token-transaction-description" href="#"><?php esc_html_e( 'Insert token', 'wp-full-stripe-admin' ); ?></a>
        </div>
    </label>
    <textarea id="<?php $view->transactionDescription()->id(); ?>" name="<?php $view->transactionDescription()->name(); ?>" <?php $view->transactionDescription()->attributes(); ?>><?php echo esc_html( $form->stripeDescription ); ?></textarea>
    <div class="wpfs-form-help"><?php esc_html_e( 'This description appears on the Stripe dashboard.', 'wp-full-stripe-admin' ); ?></div>
</div>
<div id="wpfs-insert-token-dialog" class="wpfs-dialog-content js-insert-token-dialog" title="<?php esc_html_e( 'Insert token', 'wp-full-stripe-admin' ); ?>">
    <div class="wpfs-dialog-token-list">
        <div class="wpfs-form-group">
            <input class="wpfs-form-control js-token-autocomplete" type="text" placeholder="<?php esc_html_e( 'Search token', 'wp-full-stripe-admin' ); ?>">
        </div>
    </div>
</div>
