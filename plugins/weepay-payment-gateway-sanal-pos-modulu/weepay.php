
<?php if ($error_message) {?>
      <div class="row">
        <ul class="woocommerce-error" id="errDiv">
            <li>
                <?php echo __('Payment Error.', 'weepay-payment') ?>
                <b><?php echo esc_html($error_message); ?></b><br/>
                <?php echo __('Please check the form and try again.', 'weepay-payment') ?>
            </li>
        </ul>
    </div>
<?php }?>
   <h2 style="" class='weepay-h'><?php echo esc_html($text_credit_card); ?></h2>
   <div class="weepay-form-content" id="payment"><?php echo $CheckoutForm; ?></div>
  <div id="weePay-checkout-form" class="<?php echo esc_html($form_class); ?>"></div>