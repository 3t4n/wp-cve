<?php
/** @var $view MM_WPFS_Admin_CheckoutDonationFormView */
/** @var $form */
?>
<form <?php $view->formAttributes(); ?>>
    <input id="<?php $view->action()->id(); ?>" name="<?php $view->action()->name(); ?>" value="<?php $view->action()->value(); ?>" <?php $view->action()->attributes(); ?>>
    <input name="<?php echo MM_WPFS_Admin_FormViewConstants::FIELD_FORM_ID; ?>" value="<?php echo $form->checkoutDonationFormID; ?>" type="hidden">
    <input id="<?php $view->minimumDonationAmountHidden()->id(); ?>" name="<?php $view->minimumDonationAmountHidden()->name(); ?>" <?php $view->minimumDonationAmountHidden()->attributes(); ?>>
    <input id="<?php $view->donationAmounts()->id(); ?>" name="<?php $view->donationAmounts()->name(); ?>" value="" <?php $view->donationAmounts()->attributes(); ?>>
    <input id="<?php $view->checkoutProductImage()->id(); ?>" name="<?php $view->checkoutProductImage()->name(); ?>" <?php $view->checkoutProductImage()->attributes(); ?>>
    <div class="wpfs-edit-form-pane" data-tab-id="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_GENERAL; ?>">
        <div class="wpfs-form__cols">
            <div class="wpfs-form__col">
                <div class="wpfs-form-block">
                    <div class="wpfs-form-block__title"><?php esc_html_e( 'Properties', 'wp-full-stripe-admin'); ?></div>
                    <?php include( 'wpfs-form-component-display-name.php' ); ?>
                </div>
                <div class="wpfs-form-block">
                    <div class="wpfs-form-block__title"><?php esc_html_e( 'Behavior', 'wp-full-stripe-admin'); ?></div>
                    <?php include( 'wpfs-form-component-redirect-after-payment.php' ); ?>
                </div>
                <?php include( 'wpfs-form-component-action-buttons.php' ); ?>
            </div>
        </div>
    </div>
    <div class="wpfs-edit-form-pane" data-tab-id="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_PAYMENT; ?>" style="display: none;">
        <div class="wpfs-form__cols">
            <div class="wpfs-form__col">
                <div class="wpfs-form-block">
                    <?php include( 'wpfs-form-component-currency.php' ); ?>
                    <?php include( 'wpfs-form-component-seat-country.php' ); ?>
                    <?php include( 'wpfs-form-component-suggested-donation-amounts.php' ); ?>
                    <?php include( 'wpfs-form-component-minimum-donation-amount.php' ); ?>
                    <?php include( 'wpfs-form-component-donation-frequencies.php' ); ?>
                    <?php include( 'wpfs-form-component-generate-invoice.php' ); ?>
                    <?php include( 'wpfs-form-component-transaction-description.php' ); ?>
                    <?php include( 'wpfs-form-component-action-buttons.php' ); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="wpfs-edit-form-pane" data-tab-id="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_APPEARANCE; ?>" style="display: none;">
        <div class="wpfs-form__cols">
            <div class="wpfs-form__col">
                <div class="wpfs-form-block">
                    <?php include( 'wpfs-form-component-open-form-button-label.php' ); ?>
                    <?php include( 'wpfs-form-component-checkout-product-description.php' ); ?>
                    <?php include( 'wpfs-form-component-checkout-product-image.php' ); ?>
                    <?php include( 'wpfs-form-component-checkout-form-language.php' ); ?>
                </div>
                <div class="wpfs-form-block">
                    <div class="wpfs-form-block__title"><?php esc_html_e( 'Locale', 'wp-full-stripe-admin'); ?></div>
                    <?php include( 'wpfs-form-component-locale-currency.php' ); ?>
                </div>
                <?php include( 'wpfs-form-component-action-buttons.php' ); ?>
            </div>
            <div class="wpfs-form__col">
                <?php include( 'wpfs-form-component-css-selector.php' ); ?>
            </div>
        </div>
    </div>
    <div class="wpfs-edit-form-pane" data-tab-id="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_FORM_LAYOUT; ?>" style="display: none;">
        <div class="wpfs-form__cols">
            <div class="wpfs-form__col">
                <div class="wpfs-form-block">
                    <div class="wpfs-form-block__title"><?php esc_html_e( 'Optional form fields', 'wp-full-stripe-admin'); ?></div>
                    <?php include( 'wpfs-form-component-customer-data-checkout-with-phone-number.php' ); ?>
                    <?php include( 'wpfs-form-component-terms-of-service.php' ); ?>
                </div>
                <div class="wpfs-form-block">
                    <div class="wpfs-form-block__title"><?php esc_html_e( 'Custom fields', 'wp-full-stripe-admin'); ?></div>
                    <?php include( 'wpfs-form-component-custom-fields.php' ); ?>
                </div>
                <?php include( 'wpfs-form-component-action-buttons.php' ); ?>
            </div>
        </div>
    </div>
    <div class="wpfs-edit-form-pane" data-tab-id="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_EMAIL_NOTIFICATIONS; ?>" style="display: none;">
        <?php include( 'wpfs-form-component-email-templates.php' ); ?>
        <?php include( 'wpfs-form-component-action-buttons.php' ); ?>
    </div>
</form>
