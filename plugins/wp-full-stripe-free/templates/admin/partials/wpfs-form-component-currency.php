<?php
    /** @var $view MM_WPFS_Admin_DonationFormView|MM_WPFS_Admin_PaymentFormView */
    /** @var $form */
    /** @var $data */
?>
<div class="wpfs-form-group">
    <label for="<?php $view->currency()->id(); ?>" class="wpfs-form-label"><?php $view->currency()->label(); ?></label>
    <div class="wpfs-ui wpfs-form-select">
        <select id="<?php $view->currency()->id(); ?>" name="<?php $view->currency()->name(); ?>" <?php $view->currency()->attributes(); ?>>
            <option value=""><?php esc_html_e( 'Select currency', 'wp-full-stripe-admin' ); ?></option>
            <?php
            foreach ( $data->currencies as $currencyKey => $currency ) {
            ?>
            <option value="<?php echo $currencyKey ?>" data-currency-code="<?php echo $currency['code']; ?>" data-currency-symbol="<?php echo $currency['symbol']; ?>" data-zero-decimal-support="<?php echo $currency['zeroDecimalSupport'] == true ? 'true' : 'false'; ?>" <?php echo $form->currency === $currencyKey ? 'selected' : ''; ?>><?php echo $currency['name'] . ' (' . $currency['code'] . ')'; ?></option>
            <?php } ?>
        </select>
    </div>
</div>
