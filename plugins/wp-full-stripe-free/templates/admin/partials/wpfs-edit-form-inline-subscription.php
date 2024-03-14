<?php
/** @var $view MM_WPFS_Admin_InlineSubscriptionFormView */
/** @var $form */
?>
<form <?php $view->formAttributes(); ?>>
    <input id="<?php $view->action()->id(); ?>" name="<?php $view->action()->name(); ?>" value="<?php $view->action()->value(); ?>" <?php $view->action()->attributes(); ?>>
    <input name="<?php echo MM_WPFS_Admin_FormViewConstants::FIELD_FORM_ID; ?>" value="<?php echo $form->subscriptionFormID; ?>" type="hidden">
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
                    <?php include( 'wpfs-form-component-subscription-plans.php' ); ?>
                    <?php include( 'wpfs-form-component-subscription-quantity.php' ); ?>
                    <?php include( 'wpfs-form-component-action-buttons.php' ); ?>
                </div>
            </div>
            <div class="wpfs-form__col">
                <?php include( 'wpfs-form-component-plan-hint.php' ); ?>
            </div>
        </div>
    </div>
    <div class="wpfs-edit-form-pane" data-tab-id="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_TAX; ?>" style="display: none;">
        <div class="wpfs-form__cols">
            <div class="wpfs-form__col">
                <div class="wpfs-form-block">
                    <?php include('wpfs-form-component-tax-type.php'); ?>
                </div>
                <div class="wpfs-form-block" id="tax-rates-settings" style="<?php echo $view->doesFormCalculateTax( $form ) ? '' : 'display: none;' ?>">
                    <?php include('wpfs-form-component-tax-rate-type.php'); ?>
                    <?php include( 'wpfs-form-component-collect-customer-tax-id.php' ); ?>
                    <?php include( 'wpfs-form-component-tax-rates.php' ); ?>
                </div>
                <div class="wpfs-form-block">
                    <?php include( 'wpfs-form-component-action-buttons.php' ); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="wpfs-edit-form-pane" data-tab-id="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_APPEARANCE; ?>" style="display: none;">
        <div class="wpfs-form__cols">
            <div class="wpfs-form__col">
                <div class="wpfs-form-block">
                    <?php include( 'wpfs-form-component-product-selector-style.php' ); ?>
                </div>
                <div class="wpfs-form-block">
                    <?php include( 'wpfs-form-component-submit-form-button-label.php' ); ?>
                    <?php include( 'wpfs-form-component-card-field-language.php' ); ?>
                </div>
                <div class="wpfs-form-block">
                    <div class="wpfs-form-block__title"><?php esc_html_e( 'Locale', 'wp-full-stripe-admin'); ?></div>
                    <?php include( 'wpfs-form-component-locale-currency.php' ); ?>
                </div>
                <?php include( 'wpfs-form-component-action-buttons.php' ); ?>
            </div>
            <div class="wpfs-form__col">
                <?php include( 'wpfs-form-component-css-selector.php' ); ?>
                <?php include( 'wpfs-form-component-elements-appearance-selector.php' ); ?>
            </div>
        </div>
    </div>
    <div class="wpfs-edit-form-pane" data-tab-id="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_FORM_LAYOUT; ?>" style="display: none;">
        <div class="wpfs-form__cols">
            <div class="wpfs-form__col">
                <div class="wpfs-form-block">
                    <div class="wpfs-form-block__title"><?php esc_html_e( 'Optional form fields', 'wp-full-stripe-admin'); ?></div>
                    <?php include( 'wpfs-form-component-customer-data-inline.php' ); ?>
                    <?php include( 'wpfs-form-component-terms-of-service.php' ); ?>
                    <?php include( 'wpfs-form-component-coupon.php' ); ?>
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
