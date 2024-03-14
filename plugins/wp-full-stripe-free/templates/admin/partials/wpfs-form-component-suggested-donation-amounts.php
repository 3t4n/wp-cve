<?php
    /** @var $view MM_WPFS_Admin_DonationFormView */
    /** @var $form */
    /** @var $data */
?>
<div class="wpfs-form-group">
    <label for="" class="wpfs-form-label wpfs-form-label--actions wpfs-form-label--mb">
        <?php $view->donationAmounts()->label(); ?>
    </label>
    <div class="wpfs-field-list">
        <div id="wpfs-suggested-donation-amounts" class="wpfs-field-list__list js-sortable">&nbsp;</div>
        <a class="wpfs-field-list__add js-add-suggested-donation-amount" href="">
            <div class="wpfs-icon-add-circle wpfs-field-list__icon"></div>
            <?php $view->addAmountButton()->label(); ?>
        </a>
    </div>
    <div class="wpfs-form-check">
        <input id="<?php $view->allowCustomDonationAmount()->id(); ?>" name="<?php $view->allowCustomDonationAmount()->name(); ?>" <?php $view->allowCustomDonationAmount()->attributes(); ?> <?php echo $form->allowCustomDonationAmount == '1' ? 'checked' : ''; ?>>
        <label class="wpfs-form-check-label" for="<?php $view->allowCustomDonationAmount()->id(); ?>"><?php $view->allowCustomDonationAmount()->label(); ?></label>
    </div>
</div>
<script type="text/javascript">
    var wpfsSuggestedDonationAmounts = <?php echo $form->donationAmounts; ?>;
    var wpfsCurrencies = <?php echo json_encode( MM_WPFS_Currencies::getAvailableCurrencies() ); ?>;
</script>
<script type="text/template" id="wpfs-modal-add-suggested-donation-amount">
    <form data-wpfs-form-type="<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_SUGGESTED_DONATION_AMOUNT; ?>" class="wpfs-custom-field-form">
        <div class="wpfs-dialog-scrollable wpfs-form-group">
            <label for="wpfs-suggested-donation-amount--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_SUGGESTED_DONATION_AMOUNT; ?>" class="wpfs-form-label"><?php esc_html_e('Donation amount', 'wp-full-stripe-admin'); ?></label>
            <div class="wpfs-input-group wpfs-input-group--sm">
                <% if ( wpfsAdminSettings.preferences.currencyShowIdentifierOnLeft == '1' ) { %>
                <div class="wpfs-input-group-prepend">
                    <span class="wpfs-input-group-text"><%= pageData.currencySymbol %></span>
                </div>
                <% } %>
                <input id="wpfs-suggested-donation-amount--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_SUGGESTED_DONATION_AMOUNT; ?>" class="wpfs-input-group-form-control" type="text" name="wpfs-suggested-donation-amount">
                <% if ( wpfsAdminSettings.preferences.currencyShowIdentifierOnLeft == '0' ) { %>
                <div class="wpfs-input-group-append">
                    <span class="wpfs-input-group-text"><%= pageData.currencySymbol %></span>
                </div>
                <% } %>
            </div>
        </div>
        <div class="wpfs-dialog-content-actions">
            <button class="wpfs-btn wpfs-btn-primary js-add-suggested-donation-amount-dialog" type="submit"><?php esc_html_e('Add amount', 'wp-full-stripe-admin'); ?></button>
            <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php esc_html_e('Discard', 'wp-full-stripe-admin'); ?></button>
        </div>
    </form>
</script>
<script type="text/template" id="wpfs-modal-delete-suggested-donation-amount">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><%- confirmationMessage %></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-danger js-delete-suggested-donation-amount-dialog"><?php _e( 'Delete amount', 'wp-full-stripe-admin'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Keep amount', 'wp-full-stripe-admin' ); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-suggested-donation-amount-template">
    <div class="wpfs-icon-expand-vertical-left-right wpfs-field-list__icon"></div>
    <div class="wpfs-field-list__info">
        <div class="wpfs-field-list__title"><%- donationAmountLabel %></div>
    </div>
    <div class="wpfs-field-list__actions">
        <button class="wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-delete-suggested-donation-amount">
            <span class="wpfs-icon-trash"></span>
        </button>
    </div>
</script>
