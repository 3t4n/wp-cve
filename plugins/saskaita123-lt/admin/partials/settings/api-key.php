<?php
if (!defined('ABSPATH')) exit;

$nonce = wp_create_nonce('s123_security');
$orderStatuses = wc_get_order_statuses();

$selectedStatus = '';
foreach ($orderStatuses as $key => $status) {
    if (esc_attr(str_replace('wc-', '', $key)) === 'completed' && esc_attr($this->s123_get_option("use_order_status")) === '') { // default status selected
        $selectedStatus = sprintf(__("Invoice is created immediately after the order status is changed to status <strong>%s</strong>.", "s123-invoices"), $status);
        break;
    } else if (esc_attr(str_replace('wc-', '', $key)) === esc_attr($this->s123_get_option("use_order_status"))){ // some other status selected
        $selectedStatus = sprintf(__("Invoice is created immediately after the order status is changed to status <strong>%s</strong>.", "s123-invoices"), $status);
        break;
    }
}
?>

<div class="tab-container">
    <div>
        <h3><?php echo __("Invoice123 API Key", "s123-invoices") ?></h3>
    </div>

    <div class="info box">
        <?php echo $selectedStatus ?>
    </div>

    <form method="post" id="apiKeySubmitForm">
        <div class="s123-form__group">
            <label class="i123-font-weight-midbold" for="s123_api_key"><?php echo __("API Key", "s123-invoices") ?></label>
            <div style="display: flex; white-space: nowrap;">
                <input
                        type="password"
                        class="s123-form__control"
                        id="s123_api_key"
                        name="api_key"
                        value="<?php echo esc_attr($this->s123_get_option('api_key')); ?>"
                >
                <button
                        type="button"
                        id="showApiKey"
                        class="s123-btn"
                >
                    <?php echo __("Show API Key", "s123-invoices") ?>
                </button>
            </div>
        </div>

        <input type="hidden" name="action" value="s123_submit_api_key">
        <input type="hidden" name="s123_security" value="<?php echo esc_attr($nonce); ?>">
        <button type="submit" class="s123-btn s123-btn__primary"><?php echo __("Save", "s123-invoices") ?></button>
    </form>
</div>
