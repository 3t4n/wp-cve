<?php

use S123\Includes\Base\S123_BaseController;

if (!defined('ABSPATH')) exit;

$vats = $this->s123_getAvailableTaxRates();
$apiVats = $this->apiRequest->s123_makeGetRequest($this->apiRequest->getApiUrl('vats'))['body'];
$orderStatuses = wc_get_order_statuses();
$mailer = WC()->mailer();
$nonce = wp_create_nonce('s123_security');
?>

<div class="tab-container">
    <div>
        <h3><?php echo __("Invoice123 Invoice Settings", "s123-invoices") ?></h3>
    </div>

    <div class="info box">
        <?php echo __("All basic woocommerce settings are set after installing the woocommerce module on app.invoice123.com", "s123-invoices") ?>
    </div>

    <form method="post" id="invoiceSettingsSubmitForm">
        <div class="s123-form__group" style="display: flex; flex-direction: column;">
            <label class="i123-font-weight-midbold"
                   for="orderStatuses"><?php echo __("Select when invoice will be generated if order status changes (default: Completed)", "s123-invoices") ?></label>
            <select id="orderStatuses" name="use_order_status" class="s123-form__control" style="max-width: 250px;">
                <?php foreach ($orderStatuses as $key => $status) : ?>
                    <option
                            value="<?php echo esc_attr(str_replace('wc-', '', $key)) ?>"
                        <?php echo esc_attr(str_replace('wc-', '', $key)) === esc_attr($this->s123_get_option("use_order_status")) ||
                        (esc_attr(str_replace('wc-', '', $key)) === 'completed' && esc_attr($this->s123_get_option("use_order_status")) === '') ? 'selected' : '' ?>
                    >
                        <?php echo esc_html($status); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if ($vats && count($vats) > 0) : ?>
            <span class="i123-font-weight-midbold"><?php echo __("Link woocommerce VAT types with app.invoice123.com VAT types if you use VAT.", "s123-invoices") ?></span>
            <div class="s123_vats margin-top">
                <?php foreach ($vats as $vat) : ?>
                    <div>
                        <label style="width: 70px"
                               for="vats"><?php echo $vat->tax_rate_country . ' ' . $vat->tax_rate; ?></label>
                        <select id="vats" name="api_vats[]" class="s123-form__control">
                            <option hidden disabled
                                    selected><?php echo __("-- Select option --", "s123-invoices") ?></option>
                            <?php if ($apiVats && $apiVats["data"]) : ?>
                                <?php foreach ($apiVats["data"] as $datum) : ?>
                                    <option
                                            value="<?php echo esc_attr($datum["id"]) . '-' . esc_attr($vat->tax_rate_id); ?>"
                                        <?php echo esc_attr($vat->s123_tax_id) === esc_attr($datum["id"]) ? 'selected' : '' ?>
                                    >
                                        <?php echo esc_html($datum["vat_code"] . ' - ' . substr($datum['tariff'], 2) / 100 . '%'); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="s123-form__group margin-top activities-checkbox">
            <input
                    type="checkbox"
                    name="use_custom_inputs"
                    id="use_custom_inputs"
                <?php echo $this->s123_get_option("use_custom_inputs") === true ? 'checked' : '' ?>
                    style="margin-top: 2px;"
            >
            <label for="use_custom_inputs" class="mx-2">
                <?php echo __("Add custom inputs to checkout for clients to provide company requisites", "s123-invoices") ?>
            </label>
            <div style="margin-top: 6px; font-size: 12px;"><?php echo __("This is used to create invoices for companies", "s123-invoices") ?></div>
        </div>

        <input type="hidden" name="action" value="s123_submit_invoice_settings">
        <input type="hidden" name="s123_security" value="<?php echo esc_attr($nonce); ?>">
        <button type="submit" class="s123-btn s123-btn__primary"><?php echo __("Save", "s123-invoices") ?></button>
    </form>
</div>