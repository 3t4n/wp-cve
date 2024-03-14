<?php
/** @var $selectedPlanId */
/** @var stdClass $popupFormSubmit */
/** @var $view */
/** @var $form */
?>
<form <?php $view->formAttributes(); ?>>
    <?php if (isset($popupFormSubmit) && $popupFormSubmit->formHash === $view->getFormHash()): ?>
        <?php
        $messageClass = 'wpfs-form-message--incorrect';
        if (MM_WPFS_CheckoutSubmissionService::POPUP_FORM_SUBMIT_STATUS_SUCCESS === $popupFormSubmit->status) {
            $messageClass = 'wpfs-form-message--correct';
        }
        ?>
        <div class="wpfs-form-message <?php echo $messageClass; ?>">
            <div class="wpfs-form-message-title">
                <?php echo esc_html($popupFormSubmit->lastMessageTitle); ?>
            </div>
            <?php echo esc_html($popupFormSubmit->lastMessage); ?>
        </div>
    <?php endif; ?>
    <?php // (common)(field): action ?>
    <input id="<?php $view->action()->id(); ?>" name="<?php $view->action()->name(); ?>"
        value="<?php $view->action()->value(); ?>" <?php $view->action()->attributes(); ?>>
    <?php // (common)(field): form name ?>
    <input id="<?php $view->formName()->id(); ?>" name="<?php $view->formName()->name(); ?>"
        value="<?php $view->formName()->value(); ?>" <?php $view->formName()->attributes(); ?>>
    <?php // (common)(field): form get parameters ?>
    <input id="<?php $view->formGetParameters()->id(); ?>" name="<?php $view->formGetParameters()->name(); ?>"
        value="<?php $view->formGetParameters()->value(); ?>" <?php $view->formGetParameters()->attributes(); ?>>
    <?php
    // show custom amount options. Includes:
    // - dropdown for products with custom price
    // - list of buttons with different prices
    // - list of radio buttons with different prices
    // - input field for custom price
    if ($view instanceof MM_WPFS_DonationFormView || $view instanceof MM_WPFS_PaymentFormView) {
        include("components/wpfs-component-custom-price.php");
    }
    // Show donation frequency options
    if ($view instanceof MM_WPFS_DonationFormView) {
        include("components/wpfs-component-donation-freq.php");
    }

    // Show subscription plans
    if ($view instanceof MM_WPFS_CheckoutSubscriptionFormView || $view instanceof MM_WPFS_SubscriptionFormView) {
        include("components/wpfs-component-subscription-plans.php");
    }

    // show coupon field
    if ($view->isCouponFieldVisible()) {
        include("components/wpfs-component-coupon-field.php");
    }

    // (common)(field): custom inputs
    $showCustomInputGroup = isset($form->showCustomInput) && 1 == $form->showCustomInput;
    if ($view instanceof MM_WPFS_CheckoutSubscriptionFormView && 1 == $form->simpleButtonLayout) {
        $showCustomInputGroup = false;
    }
    ?>
    <?php if ($showCustomInputGroup): ?>
        <?php foreach ($view->customInputs() as $input): ?>
            <?php /** @var MM_WPFS_Control $input */?>
            <div class="wpfs-form-group">
                <label class="wpfs-form-label" for="<?php $input->id(); ?>">
                    <?php $input->label(); ?>
                </label>
                <input id="<?php $input->id(); ?>" name="<?php $input->name(); ?>" type="text" class="wpfs-form-control"
                    value="<?php $input->value(); ?>" <?php $input->attributes(); ?>>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php // (inline_payment|inline_subscription|inline_card_capture)(field): billing and shipping address ?>
    <?php include('wpfs-form-billing-shipping-address.php'); ?>

    <?php // (inline_payment|inline_subscription)(fields): buying as a business, business name, tax id ?>
    <?php if ($view instanceof MM_WPFS_InlinePaymentFormView || $view instanceof MM_WPFS_InlineSubscriptionFormView) {
        if ($form->vatRateType !== MM_WPFS::FIELD_VALUE_TAX_RATE_NO_TAX) { ?>
            <?php if (
                ($form->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_DYNAMIC ||
                    $form->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_STRIPE_TAX ||
                    $form->collectCustomerTaxId == '1') &&
                $form->showAddress == '0'
            ) { ?>
                <div class="wpfs-form-group">
                    <label class="wpfs-form-label" for="<?php $view->taxCountry()->id(); ?>">
                        <?php $view->taxCountry()->label(); ?>
                    </label>
                    <div class="wpfs-ui wpfs-form-select">
                        <select id="<?php $view->taxCountry()->id(); ?>" name="<?php $view->taxCountry()->name(); ?>"
                            data-toggle="selectmenu" data-wpfs-select="wpfs-tax-country-select" class="wpfs-tax-country-select"
                            <?php $view->taxCountry()->attributes(); ?>>
                            <?php foreach ($view->taxCountry()->options() as $country): ?>
                                <?php /** @var MM_WPFS_Control $country */?>
                                <option value="<?php $country->value(); ?>" <?php $country->attributes(); ?>>
                                    <?php $country->caption(); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?php if ($form->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_DYNAMIC) { ?>
                    <div class="wpfs-form-group" id="wpfs-tax-state-row"
                        style="<?php echo $form->defaultBillingCountry == 'US' ? '' : 'display: none;' ?>">
                        <label class="wpfs-form-label" for="<?php $view->taxState()->id(); ?>">
                            <?php $view->taxState()->label(); ?>
                        </label>
                        <div class="wpfs-ui wpfs-form-select">
                            <select id="<?php $view->taxState()->id(); ?>" name="<?php $view->taxState()->name(); ?>"
                                data-toggle="selectmenu" data-wpfs-select="wpfs-tax-state-select" class="wpfs-tax-state-select" <?php $view->taxState()->attributes(); ?>>
                                <?php foreach ($view->taxState()->options() as $state): ?>
                                    <?php /** @var MM_WPFS_Control $country */?>
                                    <option value="<?php $state->value(); ?>" <?php $state->attributes(); ?>>
                                        <?php $state->caption(); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php } else if ($form->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_STRIPE_TAX) { ?>
                        <div class="wpfs-form-group" id="wpfs-tax-postal-code-row">
                            <label class="wpfs-form-label" for="<?php $view->taxZip()->id(); ?>">
                            <?php $view->taxZip()->label(); ?>
                            </label>
                            <input id="<?php $view->taxZip()->id(); ?>" name="<?php $view->taxZip()->name(); ?>" type="text"
                                class="wpfs-form-control" value="<?php $view->taxZip()->value(); ?>" <?php $view->taxZip()->attributes(); ?>>
                        </div>
                <?php } ?>
            <?php } ?>
            <?php if ($form->collectCustomerTaxId == '1') { ?>
                <div class="wpfs-form-check">
                    <input type="checkbox" class="wpfs-form-check-input" id="<?php $view->buyingAsBusiness()->id(); ?>"
                        name="<?php $view->buyingAsBusiness()->name(); ?>" value="1">
                    <label class="wpfs-form-check-label" for="<?php $view->buyingAsBusiness()->id(); ?>">
                        <?php $view->buyingAsBusiness()->label(); ?>
                    </label>
                </div>
                <?php if ($form->showAddress == '0') { ?>
                    <div class="wpfs-form-group" id="wpfs-business-name-row" style="display: none;">
                        <label class="wpfs-form-label" for="<?php $view->businessName()->id(); ?>">
                            <?php $view->businessName()->label(); ?>
                        </label>
                        <input id="<?php $view->businessName()->id(); ?>" name="<?php $view->businessName()->name(); ?>" type="text"
                            class="wpfs-form-control" <?php $view->businessName()->attributes(); ?>>
                    </div>
                <?php } ?>
                <div class="wpfs-form-row" id="wpfs-tax-id-row" style="display: none;">
                    <div class="wpfs-form-col">
                        <div class="wpfs-form-group">
                            <label class="wpfs-form-label" for="<?php $view->taxIdType()->id(); ?>">
                                <?php $view->taxIdType()->label(); ?>
                            </label>
                            <div class="wpfs-ui wpfs-form-select wpfs-tax-id-type-select">
                                <select id="<?php $view->taxIdType()->id(); ?>" name="<?php $view->taxIdType()->name(); ?>"
                                    data-toggle="selectmenu" data-wpfs-select="wpfs-tax-id-type-select"
                                    class="wpfs-tax-id-type-select" <?php $view->taxIdType()->attributes(); ?>>
                                    <?php foreach ($view->taxIdType()->options() as $taxIdType): ?>
                                        <?php /** @var MM_WPFS_Control $taxIdType */?>
                                        <option value="<?php $taxIdType->value(); ?>" <?php $taxIdType->attributes(); ?>>
                                            <?php $taxIdType->caption(); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="wpfs-form-col">
                        <div class="wpfs-form-group">
                            <label class="wpfs-form-label" for="<?php $view->taxId()->id(); ?>">
                                <?php $view->taxId()->label(); ?>
                            </label>
                            <input id="<?php $view->taxId()->id(); ?>" name="<?php $view->taxId()->name(); ?>" type="text"
                                class="wpfs-form-control" <?php $view->taxId()->attributes(); ?>>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    <?php } ?>
    <?php // (inline_payment|inline_subscription|inline_card_capture|inline_donation)(field): cardholder email ?>
    <?php if (
        $view instanceof MM_WPFS_InlinePaymentFormView || $view instanceof MM_WPFS_InlineSaveCardFormView ||
        $view instanceof MM_WPFS_InlineSubscriptionFormView || $view instanceof MM_WPFS_InlineDonationFormView
    ): ?>
        <div class="wpfs-form-group">
            <label class="wpfs-form-label" for="<?php $view->cardHolderEmail()->id(); ?>">
                <?php $view->cardHolderEmail()->label(); ?>
            </label>
            <input id="<?php $view->cardHolderEmail()->id(); ?>" name="<?php $view->cardHolderEmail()->name(); ?>"
                type="email" class="wpfs-form-control" value="<?php $view->cardHolderEmail()->value(); ?>" <?php $view->cardHolderEmail()->attributes(); ?>>
        </div>
    <?php endif; ?>
    <?php // (inline_payment|inline_subscription|inline_card_capture)(field): cardholder name ?>
    <?php if (
        $view instanceof MM_WPFS_InlinePaymentFormView || $view instanceof MM_WPFS_InlineSaveCardFormView ||
        $view instanceof MM_WPFS_InlineSubscriptionFormView || $view instanceof MM_WPFS_InlineDonationFormView
    ): ?>
        <div class="wpfs-form-group">
            <label class="wpfs-form-label" for="<?php $view->cardHolderName()->id(); ?>">
                <?php $view->cardHolderName()->label(); ?>
            </label>
            <input id="<?php $view->cardHolderName()->id(); ?>" name="<?php $view->cardHolderName()->name(); ?>" type="text"
                class="wpfs-form-control" value="<?php $view->cardHolderName()->value(); ?>" <?php $view->cardHolderName()->attributes(); ?>>
        </div>
    <?php endif; ?>
    <?php // (inline_payment|inline_subscription|inline_card_capture)(field): card ?>
    <?php if (
        $view instanceof MM_WPFS_InlinePaymentFormView || $view instanceof MM_WPFS_InlineSaveCardFormView ||
        $view instanceof MM_WPFS_InlineSubscriptionFormView || $view instanceof MM_WPFS_InlineDonationFormView
    ): ?>
        <div class="wpfs-form-group">

            <div  id="<?php $view->card()->id(); ?>" data-toggle="card"
                  data-wpfs-form-id="<?php $view->_formName(); ?>"></div>
        </div>
    <?php endif; ?>

    <?php // (common)(field): terms of use ?>
    <?php if (isset($form->showTermsOfUse) && 1 == $form->showTermsOfUse): ?>
        <div class="wpfs-form-check">
            <input type="checkbox" class="wpfs-form-check-input" id="<?php $view->tOUAccepted()->id(); ?>"
                name="<?php $view->tOUAccepted()->name(); ?>" value="1">
            <label class="wpfs-form-check-label" for="<?php $view->tOUAccepted()->id(); ?>">
                <?php $view->tOUAccepted()->label(); ?>
            </label>
        </div>
    <?php endif; ?>
    <?php // (inline_payment|inline_subscription|inline_card_capture|inline_donation)(div): captcha ?>
    <?php if (
        $view instanceof MM_WPFS_InlinePaymentFormView || $view instanceof MM_WPFS_InlineSaveCardFormView ||
        $view instanceof MM_WPFS_InlineSubscriptionFormView || $view instanceof MM_WPFS_InlineDonationFormView
    ): ?>
        <?php if (MM_WPFS_ReCaptcha::getSecureInlineForms($this->staticContext)): ?>
            <div class="wpfs-form-group">
                <label class="wpfs-form-label">
                    <?php /* translators: Form field label for captcha */
                    _e('Prove you are a human', 'wp-full-stripe'); ?>
                </label>
                <div class="wpfs-form-captcha" data-wpfs-field-name="g-recaptcha-response"
                    data-wpfs-form-hash="<?php echo esc_attr($view->getFormHash()); ?>"></div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if (
        $view instanceof MM_WPFS_CheckoutPaymentFormView || $view instanceof MM_WPFS_CheckoutSaveCardFormView ||
        $view instanceof MM_WPFS_CheckoutSubscriptionFormView || $view instanceof MM_WPFS_CheckoutDonationFormView
    ): ?>
        <?php if (MM_WPFS_ReCaptcha::getSecureCheckoutForms($this->staticContext)): ?>
            <div class="wpfs-form-group">
                <label class="wpfs-form-label">
                    <?php /* translators: Form field label for captcha */
                    _e('Prove you are a human', 'wp-full-stripe'); ?>
                </label>
                <div class="wpfs-form-captcha" data-wpfs-field-name="g-recaptcha-response"
                    data-wpfs-form-hash="<?php echo esc_attr($view->getFormHash()); ?>"></div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php // (common)(button): submit ?>
    <div class="wpfs-form-actions">
        <button class="wpfs-btn wpfs-btn-primary wpfs-mr-2" id="<?php $view->submitButton()->id(); ?>" type="submit"
            <?php $view->submitButton()->attributes(); ?>>
            <?php $view->submitButton()->caption(); ?>
        </button>
        <?php
        // (inline_payment)(table): payment details
        $showPaymentDetails = false;
        if ($view instanceof MM_WPFS_SubscriptionFormView) {
            $showPaymentDetails = true;

            if (($view instanceof MM_WPFS_CheckoutPaymentFormView || $view instanceof MM_WPFS_CheckoutSubscriptionFormView) && 1 == $form->simpleButtonLayout) {
                $showPaymentDetails = false;
            }
            if ($view instanceof MM_WPFS_SubscriptionFormView && count($view->plans()->options()) == 0) {
                $showPaymentDetails = false;
            }
        } else if (
            $view instanceof MM_WPFS_InlinePaymentFormView &&
            !($view instanceof MM_WPFS_InlineSaveCardFormView)
        ) {
            $showPaymentDetails = true;
        } else if (
            $view instanceof MM_WPFS_CheckoutPaymentFormView &&
            !($view instanceof MM_WPFS_CheckoutSaveCardFormView)
        ) {
            $showPaymentDetails = true;
        }
        ?>
        <?php if ($showPaymentDetails): ?>
            <a href="" id="payment-details--<?php echo $view->getFormHash(); ?>"
                class="wpfs-btn wpfs-btn-link wpfs-btn-link--sm" data-toggle="tooltip"
                data-tooltip-content="<?php echo esc_attr('wpfs-form-summary-' . $view->getFormHash()); ?>">
                <?php /* translators: Link that trigger the opening of the payment details table */
                _e('Payment details', 'wp-full-stripe'); ?>
            </a>
            <div class="wpfs-tooltip-content"
                data-tooltip-id="<?php echo esc_attr('wpfs-form-summary-' . $view->getFormHash()); ?>">
                <div class="wpfs-summary">
                    <table class="wpfs-summary-table">
                        <tbody>
                            <tr class="wpfs-summary-table-row" data-wpfs-summary-row="setupFee">
                                <td class="wpfs-summary-table-cell" data-wpfs-summary-row-label="setupFee"> </td>
                                <td class="wpfs-summary-table-cell" data-wpfs-summary-row-value="setupFee">&nbsp;</td>
                            </tr>
                            <tr class="wpfs-summary-table-row" data-wpfs-summary-row="product">
                                <td class="wpfs-summary-table-cell" data-wpfs-summary-row-label="product"> </td>
                                <td class="wpfs-summary-table-cell" data-wpfs-summary-row-value="product">&nbsp;</td>
                            </tr>
                            <tr class="wpfs-summary-table-row" data-wpfs-summary-row="discount">
                                <td class="wpfs-summary-table-cell" data-wpfs-summary-row-label="discount"> </td>
                                <td class="wpfs-summary-table-cell" data-wpfs-summary-row-value="discount">&nbsp;</td>
                            </tr>
                            <tr class="wpfs-summary-table-row" data-wpfs-summary-row="tax-0">
                                <td class="wpfs-summary-table-cell" data-wpfs-summary-row-label="tax-0"> </td>
                                <td class="wpfs-summary-table-cell" data-wpfs-summary-row-value="tax-0">&nbsp;</td>
                            </tr>
                            <tr class="wpfs-summary-table-row" data-wpfs-summary-row="tax-1">
                                <td class="wpfs-summary-table-cell" data-wpfs-summary-row-label="tax-1"> </td>
                                <td class="wpfs-summary-table-cell" data-wpfs-summary-row-value="tax-1">&nbsp;</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="wpfs-summary-table-total" data-wpfs-summary-row="total">
                                <td class="wpfs-summary-table-cell" data-wpfs-summary-row-label="total">
                                    <?php /* translators: Label for the total price  */
                                    esc_html_e('Total', 'wp-full-stripe'); ?>
                                </td>
                                <td class="wpfs-summary-table-cell" data-wpfs-summary-row-value="total">&nbsp;</td>
                            </tr>
                        </tfoot>
                    </table>
                    <p class="wpfs-summary-description">&nbsp;</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</form>
<?php
if (
    $view instanceof MM_WPFS_InlinePaymentFormView ||
    $view instanceof MM_WPFS_CheckoutPaymentFormView ||
    $view instanceof MM_WPFS_InlineSubscriptionFormView ||
    $view instanceof MM_WPFS_CheckoutSubscriptionFormView
) {
    if (count($view->getProductPricing()) > 0) {
        ?>
        <script type="text/javascript">
            var wpfsProductPricing = typeof (wpfsProductPricing) == 'undefined' ? [] : wpfsProductPricing;
            wpfsProductPricing['<?php echo $view->getFormName(); ?>'] = <?php echo json_encode($view->getProductPricing()); ?>;
            var wpfsCouponData = typeof (wpfsCouponData) == 'undefined' ? [] : wpfsCouponData;
            wpfsCouponData['<?php echo $view->getFormName(); ?>'] = <?php echo ($view->getCouponData() !== null ? json_encode($view->getCouponData()) : 'null'); ?>;
        </script>
        <?php
    }
} ?>
<?php if (!defined('WPFP_FORM_TAX_DATA')) {
    define('WPFP_FORM_TAX_DATA', 'WPFP_FORM_TAX_DATA');
    ?>
    <script type="text/javascript">
        var wpfsTaxIdData = <?php echo json_encode(MM_WPFS_CustomerTaxId::getTaxIdTypesByCountry()); ?>;
    </script>
<?php } ?>
