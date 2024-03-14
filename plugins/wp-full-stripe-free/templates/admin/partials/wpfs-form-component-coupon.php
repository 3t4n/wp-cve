<?php
    /** @var $view MM_WPFS_Admin_PaymentFormView|MM_WPFS_Admin_SubscriptionFormView */
    /** @var $form */
    /** @var $data */
?>
<div class="wpfs-form-group">
    <label class="wpfs-form-label"><?php esc_html_e( "Other", 'wp-full-stripe-admin' ); ?></label>
    <div class="wpfs-form-check-list">
        <div class="wpfs-form-check">
            <input id="<?php $view->showCouponField()->id(); ?>" name="<?php $view->showCouponField()->name(); ?>" value="<?php $view->showCouponField()->value(); ?>" <?php $view->showCouponField()->attributes(); ?> <?php echo $form->showCouponInput == $view->showCouponField()->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $view->showCouponField()->id(); ?>"><?php $view->showCouponField()->label(); ?></label>
        </div>
    </div>
</div>
