<?php
/** @var MM_WPFS_FormView $view */
/** @var \StdClass $form */

// (donation)(field): list of amount
if ($view instanceof MM_WPFS_DonationFormView && !is_null($view->donationAmountOptions()) && count($view->donationAmountOptions()->options()) > 0) {
    ?>
    <fieldset class="wpfs-form-check-group wpfs-button-group">
        <?php if (!$view->isOneSuggestedAmountOnly()) { ?>
            <legend class="wpfs-form-check-group-title">
                <?php $view->donationAmountOptions()->label(); ?>
            </legend>
        <?php } ?>
        <?php if ($view->isCustomAmountOnly()) {
            $donationAmountOption = $view->donationAmountOptions()->options()[0];
            ?>
            <input type="hidden" id="<?php $donationAmountOption->id(); ?>" name="<?php $donationAmountOption->name(); ?>"
                class="wpfs-form-check-input wpfs-custom-amount" value="<?php $donationAmountOption->value(); ?>" <?php $donationAmountOption->attributes(); ?>>
        <?php } else { ?>
            <div class="wpfs-button-group-row wpfs-button-group-row--fixed">
                <?php foreach ($view->donationAmountOptions()->options() as $donationAmountOption): ?>
                    <?php /** @var MM_WPFS_Control $donationAmountOption */?>
                    <div class="wpfs-button-group-item">
                        <input id="<?php $donationAmountOption->id(); ?>" name="<?php $donationAmountOption->name(); ?>"
                            type="radio" class="wpfs-form-check-input wpfs-custom-amount wpfs-custom-amount-radio"
                            value="<?php $donationAmountOption->value(); ?>" <?php $donationAmountOption->attributes(); ?>>
                        <label class="wpfs-btn wpfs-btn-outline-primary" for="<?php $donationAmountOption->id(); ?>">
                            <?php $donationAmountOption->label(); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php } ?>
    </fieldset>
    <?php
}
// (inline_payment|popup_payment)(field): list of amount
if ($view instanceof MM_WPFS_PaymentFormView && !is_null($view->customAmountOptions())) {
    if (count($view->customAmountOptions()->options()) > 0) {
        if (MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS === $form->customAmount) {
            if (MM_WPFS::SELECTOR_STYLE_DROPDOWN === $form->amountSelectorStyle) {
                ?>
                <div class="wpfs-form-group">
                    <label class="wpfs-form-label" for="<?php $view->customAmountOptions()->id(); ?>">
                        <?php $view->customAmountOptions()->label(); ?>
                    </label>
                    <div class="wpfs-ui wpfs-form-select">
                        <select id="<?php $view->customAmountOptions()->id(); ?>" name="<?php $view->customAmountOptions()->name(); ?>"
                            data-toggle="selectmenu" data-wpfs-select="wpfs-custom-amount-select"
                            class="wpfs-custom-amount wpfs-custom-amount-select" <?php $view->customAmountOptions()->attributes(); ?>>
                            <?php foreach ($view->customAmountOptions()->options() as $customAmountOption): ?>
                                <?php /** @var MM_WPFS_Control $customAmountOption */?>
                                <option value="<?php $customAmountOption->value(); ?>" <?php $customAmountOption->attributes(); ?>>
                                    <?php $customAmountOption->label(); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?php
            }
            if (MM_WPFS::SELECTOR_STYLE_BUTTON_GROUP === $form->amountSelectorStyle) {
                ?>
                <fieldset class="wpfs-form-check-group wpfs-button-group">
                    <legend class="wpfs-form-check-group-title">
                        <?php $view->customAmountOptions()->label(); ?>
                    </legend>
                    <div class="wpfs-button-group-row wpfs-button-group-row--fixed">
                        <?php foreach ($view->customAmountOptions()->options() as $customAmountOption): ?>
                            <?php /** @var MM_WPFS_Control $customAmountOption */?>
                            <div class="wpfs-button-group-item">
                                <input id="<?php $customAmountOption->id(); ?>" name="<?php $customAmountOption->name(); ?>" type="radio"
                                    class="wpfs-form-check-input wpfs-custom-amount wpfs-custom-amount-radio"
                                    value="<?php $customAmountOption->value(); ?>" <?php $customAmountOption->attributes(); ?>>
                                <label class="wpfs-btn wpfs-btn-outline-primary" for="<?php $customAmountOption->id(); ?>">
                                    <?php $customAmountOption->label(); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
                <?php
            }
            if (MM_WPFS::SELECTOR_STYLE_RADIO_BUTTONS === $form->amountSelectorStyle) {
                ?>
                <fieldset class="wpfs-form-check-group">
                    <legend class="wpfs-form-check-group-title">
                        <?php $view->customAmountOptions()->label(); ?>
                    </legend>
                    <?php foreach ($view->customAmountOptions()->options() as $customAmountOption): ?>
                        <?php /** @var MM_WPFS_Control $customAmountOption */?>
                        <div class="wpfs-form-check">
                            <input id="<?php $customAmountOption->id(); ?>" name="<?php $customAmountOption->name(); ?>" type="radio"
                                class="wpfs-form-check-input wpfs-custom-amount wpfs-custom-amount-radio"
                                value="<?php $customAmountOption->value(); ?>" <?php $customAmountOption->attributes(); ?>>
                            <label class="wpfs-form-check-label" for="<?php $customAmountOption->id(); ?>">
                                <?php $customAmountOption->label(); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </fieldset>
                <?php
            }
        } elseif (MM_WPFS::PAYMENT_TYPE_SPECIFIED_AMOUNT === $form->customAmount) {
            $hiddenOption = $view->customAmountOptions()->options()[0];
            ?>
            <input id="<?php $hiddenOption->id(); ?>" name="<?php $hiddenOption->name(); ?>" type="hidden"
                class="wpfs-form-check-input wpfs-custom-amount wpfs-custom-amount-hidden" value="<?php $hiddenOption->value(); ?>"
                <?php $hiddenOption->attributes(); ?>>
            <?php
        }
    } else {
        ?>
        <div class="wpfs-form-message wpfs-form-message--incorrect">
            <div class="wpfs-form-message-title">
                <?php /* translators: Banner title of internal error */
                esc_html_e('Form configuration error', 'wp-full-stripe'); ?>
            </div>
            <?php /* Banner error message of not finding products added to this form */
            esc_html_e('Add at least one product to this form!', 'wp-full-stripe'); ?>
        </div>
        <?php

    }
}
// (inline_payment|popup_payment|inline_donation|popup_donation)(field): custom amount
$renderCustomAmountField = ($view instanceof MM_WPFS_PaymentFormView && (1 == $form->allowListOfAmountsCustom || MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT == $form->customAmount)) ||
    ($view instanceof MM_WPFS_DonationFormView && 1 == $form->allowCustomDonationAmount);
$showCustomAmountField = ($view instanceof MM_WPFS_PaymentFormView && MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT == $form->customAmount) ||
    ($view instanceof MM_WPFS_PaymentFormView && MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS == $form->customAmount && 1 == $form->allowListOfAmountsCustom && $view->customAmount()->value(false)) ||
    ($view instanceof MM_WPFS_DonationFormView && $view->isCustomAmountOnly()) ||
    ($view instanceof MM_WPFS_DonationFormView && 1 == $form->allowCustomDonationAmount && $view->customAmount()->value(false));

if ($renderCustomAmountField) {
    ?>
    <div class="wpfs-form-group wpfs-w-20" data-wpfs-amount-row="custom-amount" <?php echo ($showCustomAmountField ? '' : 'style="display: none;"'); ?>>
        <label <?php $view->customAmount()->labelAttributes(); ?> for="<?php $view->customAmount()->id(); ?>">
            <?php $view->customAmount()->label(); ?>
        </label>
        <div class="wpfs-input-group">
            <?php if ($view->showCurrencySignAtFirstPosition()): ?>
                <div class="wpfs-input-group-prepend">
                    <span class="wpfs-input-group-text">
                        <?php $view->_currencySign(); ?>
                    </span>
                </div>
            <?php endif; ?>
            <input id="<?php $view->customAmount()->id(); ?>" name="<?php $view->customAmount()->name(); ?>" type="text"
                class="wpfs-input-group-form-control wpfs-custom-amount--unique"
                value="<?php $view->customAmount()->value(); ?>"
                placeholder="<?php $view->customAmount()->placeholder(); ?>" <?php $view->customAmount()->attributes(); ?>>
            <?php if (!$view->showCurrencySignAtFirstPosition()): ?>
                <div class="wpfs-input-group-append">
                    <span class="wpfs-input-group-text">
                        <?php $view->_currencySign(); ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
?>