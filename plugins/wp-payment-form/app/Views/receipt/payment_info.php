<div class="wpf_payment_info">
    <div class="wpf_payment_info_item wpf_payment_info_item_order_id">
        <?php if ($submission->order_items) : ?>
            <div class="wpf_item_heading"><?php _e('Order ID:', 'wp-payment-form'); ?></div>
        <?php else : ?>
            <div class="wpf_item_heading"><?php _e('Submission ID:', 'wp-payment-form'); ?></div>
        <?php endif; ?>
        <div class="wpf_item_value">#<?php echo esc_html($submission->id); ?></div>
    </div>
    <div class="wpf_payment_info_item wpf_payment_info_item_date">
        <div class="wpf_item_heading"><?php _e('Date:', 'wp-payment-form'); ?></div>
        <div class="wpf_item_value"><?php echo date(get_option('date_format'), strtotime($submission->created_at)); ?></div>
    </div>
    <?php if ($submission->payment_total) : ?>
        <?php
        $currencySetting = \WPPayForm\App\Services\GeneralSettings::getGlobalCurrencySettings();
        $currencySetting['currency_sign'] = \WPPayForm\App\Services\GeneralSettings::getCurrencySymbol($submission->currency);
        ?>
        <div class="wpf_payment_info_item wpf_payment_info_item_total">
            <div class="wpf_item_heading"><?php _e('Total:', 'wp-payment-form'); ?></div>
            <div class="wpf_item_value"><?php echo ($submission->payment_total > 0) ? wpPayFormFormattedMoney($submission->payment_total, $currencySetting) : 'pending'; ?></div>
        </div>
    <?php endif; ?>
    <?php if ($submission->payment_method) : ?>
        <div class="wpf_payment_info_item wpf_payment_info_item_payment_method">
            <div class="wpf_item_heading"><?php _e('Payment Method:', 'wp-payment-form'); ?></div>
            <div class="wpf_item_value"><?php echo ucfirst(esc_attr($submission->payment_method)); ?></div>
        </div>
    <?php endif; ?>
    <?php if ($submission->payment_status && $submission->order_items) : ?>
        <div class="wpf_payment_info_item wpf_payment_info_item_payment_status">
            <div class="wpf_item_heading"><?php _e('Payment Status:', 'wp-payment-form'); ?></div>
            <div class="wpf_item_value"><?php echo ucfirst(esc_attr($submission->payment_status)); ?></div>
        </div>
    <?php endif; ?>
</div>
