<div class="paymode_field paymode_paypal_marketplace">
    <div class="form-field">
        <p class="wcfm_title wcfm_ele paymode_field paymode_paypal_marketplace"><strong><?php esc_html_e( 'PayPal Email', 'wc-frontend-manager-direct-paypal' ); ?></strong></p>
        <?php if( !$paypal_connected ) { ?>
            <label class="screen-reader-text" for="paypal_email"><?php esc_html_e( 'PayPal Email', 'wc-frontend-manager-direct-paypal' ); ?></label>
            <input type="text" id="paypal_email" name="payment[paypal_marketplace][email]" class="wcfm-text wcfm_ele paymode_field paymode_paypal_marketplace" value="<?php esc_attr_e( $paypal_email ); ?>" placeholder="">
        <?php } ?>
    </div>
    <div class="form-field wcfm-inline">
        <?php if( !$paypal_connected ) { ?>
            <p class="wcfm_title wcfm_ele paymode_field paymode_paypal_marketplace"><strong></strong></p>
            <label class="screen-reader-text" for="paypal_signup"></label>
            <input type="button" id="paypal_signup" class="button wcfm_ele paymode_field paymode_paypal_marketplace" value="<?php esc_attr_e( 'Sign up', 'wc-frontend-manager-direct-paypal' ); ?>" placeholder="">
        <?php } else { ?>
            <p class="wcfm_title wcfm_ele paymode_field paymode_paypal_marketplace"><strong></strong></p>
            <label class="screen-reader-text" for="disconnect_paypal"></label>
            <input type="button" id="disconnect_paypal" class="button wcfm_ele paymode_field paymode_paypal_marketplace" value="<?php esc_attr_e( 'Disconnect paypal account', 'wc-frontend-manager-direct-paypal' ); ?>" placeholder="">
            <p class="wcfm_title wcfm_ele paymode_field paymode_paypal_marketplace"><strong></strong></p>
            <p class="wcfm_title wcfm_ele paymode_field paymode_paypal_marketplace wcfm-green"><strong><?php esc_html_e( 'You are now connected to Paypal', 'wc-frontend-manager-direct-paypal' ); ?></strong></p>
            <p class="wcfm_title wcfm_ele paymode_field paymode_paypal_marketplace"><strong></strong></p>
            <code class="wcfm-inline-block">
                <?php
                foreach ( $paypal_settings as $key => $value ) {
                    echo $key . ' : ' . $value . '<br />'; 
                }
                ?>
            </code>
        <?php } ?>
    </div>
    <div class="form-field wcfm-inline-block">
        <div id="paypal_connect_button"></div>
    </div>
</div>