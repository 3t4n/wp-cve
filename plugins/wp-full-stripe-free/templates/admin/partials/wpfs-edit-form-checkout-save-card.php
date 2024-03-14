<?php
/** @var $view MM_WPFS_Admin_CheckoutSaveCardFormView */
/** @var $form */
?>
<form <?php $view->formAttributes(); ?>>
    <input id="<?php $view->action()->id(); ?>" name="<?php $view->action()->name(); ?>" value="<?php $view->action()->value(); ?>" <?php $view->action()->attributes(); ?>>
    <input name="<?php echo MM_WPFS_Admin_FormViewConstants::FIELD_FORM_ID; ?>" value="<?php echo $form->checkoutFormID; ?>" type="hidden">
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
                    <?php include( 'wpfs-form-component-seat-country.php' ); ?>
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
                    <?php include( 'wpfs-form-component-checkout-form-language.php' ); ?>
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
                    <?php include( 'wpfs-form-component-customer-data-checkout-no-phone-number.php' ); ?>
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
