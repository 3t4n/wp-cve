<?php
if (!defined('ABSPATH')) {
    exit();
}
$context = WShop_Temp_Helper::clear('atts', 'templates');
?>
    <div class="xunhu-form-group">
        <div class="font-16 xunhu-form-lable"><?php echo __('Payment method', WSHOP) ?></div>
        <div class="radio">
            <?php
            $gateways = WShop::instance()->payment->get_payment_gateways();
            $index = 0;
            foreach ($gateways as $gateway):?>
                <input id="payment_<?php echo esc_attr($gateway->id)?>" name="payment_method" type="radio" value="<?php echo esc_attr($gateway->id) ?>" <?php echo $index?"":"checked";?> >
                <label for="payment_<?php echo esc_attr($gateway->id)?>" class="radio-label font-16"><?php echo $gateway->title;?></label>
            <?php endforeach; ?>
        </div>
    </div>
    <script type="text/javascript">
        (function ($) {
            $(document).bind('wshop_form_<?php echo $context?>_submit', function (e, data) {
                data.payment_method = $('input:radio[name="payment_method"]:checked').val();
            });
        })(jQuery);
    </script>
<?php

do_action('wshop_checkout_order_pay_payments', $context);
?>