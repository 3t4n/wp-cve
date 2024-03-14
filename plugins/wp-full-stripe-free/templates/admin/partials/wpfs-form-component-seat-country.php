<?php
    /** @var $view MM_WPFS_Admin_PaymentFormView|MM_WPFS_Admin_SubscriptionFormView|MM_WPFS_Admin_DonationFormView|MM_WPFS_Admin_SaveCardFormView */
    /** @var $form */
    /** @var $data */

?>
<div class="wpfs-form-group" id="seller-seat-country">
    <label for="<?php $view->defaultBillingCountry()->id(); ?>" class="wpfs-form-label"><?php $view->defaultBillingCountry()->label(); ?></label>
    <div class="wpfs-ui wpfs-form-select">
        <?php
        $defaultBillingCountry = is_null( $form->defaultBillingCountry ) ? MM_WPFS::DEFAULT_BILLING_COUNTRY_INITIAL_VALUE : $form->defaultBillingCountry;
        ?>
        <select id="<?php $view->defaultBillingCountry()->id(); ?>" name="<?php $view->defaultBillingCountry()->name(); ?>" <?php $view->defaultBillingCountry()->attributes(); ?>>
            <?php foreach ( MM_WPFS_Countries::getAvailableCountries() as $countryKey => $countryObject ) { ?>
                <option value="<?php echo $countryKey; ?>" <?php echo $countryKey === $defaultBillingCountry ? 'selected': ''; ?>><?php echo MM_WPFS_Admin::translateLabelAdmin($countryObject['name']); ?></option>
            <?php } ?>
        </select>
    </div>
</div>
