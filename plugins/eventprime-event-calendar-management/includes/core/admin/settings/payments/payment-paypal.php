<?php
$child_style = 'style=display:none;';
if( ! empty( $global_options->paypal_processor ) ) {
    $child_style = '';
}?>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="em_enable_paypal">
                    <?php esc_html_e( 'Enable/Disable', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="paypal_processor" id="ep_paypal_processor_settings" type="checkbox" value="1" <?php echo isset($global_options->paypal_processor ) && $global_options->paypal_processor == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enable or Disable Payment Gateway on checkout.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top" class="" id="ep_modern_paypal_child">
            <th scope="row" class="titledesc">
                <label for="paypal_client_id">
                    <?php esc_html_e( 'Paypal Client Id', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="paypal_client_id" class="regular-text" id="paypal_client_id" type="text" value="<?php echo isset($global_options->paypal_client_id) ? $global_options->paypal_client_id : '';?>" required>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php _e( sprintf('Enter your PayPal client id. <a href="%s" target="__">How to find your PayPal client ID and secret?</a>','https://www.upwork.com/resources/paypal-client-id-secret-key'), 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
    </tbody>
</table>