<input type="hidden" name="wppay_subscription" id="wppay_subscription" value="1">
<input type="hidden" name="wppay_token_type" id="wppay_token_type" value="">
<input type="hidden" name="wppay_value" id="wppay_value" value="">
<input type="hidden" name="wppay_masked_card" id="wppay_masked_card" value="">
<input type="hidden" name="wppay_type" id="wppay_type" value="">
<button style="display:none;" id="wppay_place_order"></button>
<script
        src="<?php echo esc_url($widget_url); ?>"
        pay-button="#wppay_place_order"
        merchant-pos-id="<?php echo esc_attr($merchant_pos_id); ?>"
        shop-name="<?php echo esc_attr($shop_name); ?>"
        total-amount="<?php echo esc_attr($total_amount); ?>"
        currency-code="<?php echo esc_attr($currency_code); ?>"
        customer-language="<?php echo esc_attr($customer_language); ?>"
        store-card="<?php echo esc_attr($store_card); ?>"
        recurring-payment="<?php echo esc_attr($recurring_payment); ?>"
        customer-email="<?php echo esc_attr($customer_email); ?>"
        sig="<?php echo esc_attr($sig); ?>"
        success-callback="wppay_subscription_callback"
>
</script>
<script type="text/javascript">
    function wppay_subscription_callback(response) {
        jQuery.wppayResponse = response;
        fillSignUpForm(response);
        jQuery('#place_order').click();
    }

    function fillSignUpForm(response) {
        jQuery('#wppay_token_type').val(response.tokenType);
        jQuery('#wppay_value').val(response.value);
        jQuery('#wppay_masked_card').val(response.maskedCard);
        jQuery('#wppay_type').val(response.type);
    }

    (function ($) {
        $(document).ready(function () {
            $(document).on('click', '#place_order', function (e) {
                var wppayResponse = jQuery.wppayResponse;

                if (!wppayResponse && jQuery('#payment_method_wppay_recurring').is(':checked')) {
                    e.preventDefault();
                    $('#wppay_place_order').click();
                } else {
                    fillSignUpForm(wppayResponse);
                }
            })
        })
    })(jQuery);

</script>
