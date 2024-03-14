<?php
/**
 * Template.
  * PHP version 5
 *
 * @category View
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */
$forminator_forms = SA_Forminator::getForminatorForms();
if (! empty($forminator_forms) ) {
    ?>
<!-- accordion -->
<div class="cvt-accordion">
    <div class="accordion-section">
    <?php foreach ( $forminator_forms as $ks => $vs ) { ?>
        <div class="cvt-accordion-body-title" data-href="#accordion_cust_<?php echo esc_attr($ks); ?>">
            <input type="checkbox" name="smsalert_forminator_general[forminator_form_status_<?php echo esc_attr($ks); ?>]" id="smsalert_forminator_general[forminator_form_status_<?php echo esc_attr($ks); ?>]" class="notify_box" <?php echo ( ( smsalert_get_option('forminator_form_status_' . esc_attr($ks), 'smsalert_forminator_general', 'on') === 'on' ) ? "checked='checked'" : '' ); ?>/><label><?php echo esc_attr(ucwords(str_replace('-', ' ', $vs))); ?></label>
            <span class="expand_btn"></span>
        </div>
        <div id="accordion_cust_<?php echo esc_attr($ks); ?>" class="cvt-accordion-body-content">
            <table class="form-table">
                <tr>
                    <td><input data-parent_id="smsalert_forminator_general[forminator_form_status_<?php echo esc_attr($ks); ?>]" type="checkbox" name="smsalert_forminator_general[forminator_message_<?php echo esc_attr($ks); ?>]" id="smsalert_forminator_general[forminator_message_<?php echo esc_attr($ks); ?>]" class="notify_box" <?php echo ( ( smsalert_get_option('forminator_message_' . esc_attr($ks), 'smsalert_forminator_general', 'on') === 'on' ) ? "checked='checked'" : '' ); ?>/><label for="smsalert_forminator_general[forminator_message_<?php echo esc_attr($ks); ?>]">Enable Message</label>
                    <a href="admin.php?page=forminator-cform-wizard&id=<?php echo $ks;?>" title="Edit Form" target="_blank" class="alignright"><small><?php esc_html_e('Edit Form', 'sms-alert')?></small></a>
                    </td>
                    </tr>
                <tr valign="top"  style="position:relative">
                    <td>
                        <div class="smsalert_tokens">
        <?php
        $fields = SA_Forminator::getForminatorVariables($ks);
        foreach ( $fields as $key=>$value ) {
            echo  "<a href='#' data-val='[" . esc_attr($key) . "]'>".esc_attr($value)."</a> | ";
        }
        ?>
                        </div>
                        <textarea data-parent_id="smsalert_forminator_general[forminator_message_<?php echo esc_attr($ks); ?>]" name="smsalert_forminator_message[forminator_sms_body_<?php echo esc_attr($ks); ?>]" id="smsalert_forminator_message[forminator_sms_body_<?php echo esc_attr($ks); ?>]" <?php echo( ( smsalert_get_option('forminator_form_status_' . esc_attr($ks), 'smsalert_forminator_general', 'on') === 'on' ) ? '' : "readonly='readonly'" ); ?> class="token-area"><?php echo esc_textarea(smsalert_get_option('forminator_sms_body_' . esc_attr($ks), 'smsalert_forminator_message', SmsAlertMessages::showMessage('DEFAULT_CONTACT_FORM_CUSTOMER_MESSAGE'))); ?></textarea>
                        <div id="menu_forminator_cust_<?php echo esc_attr($ks); ?>" class="sa-menu-token" role="listbox"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Select Phone Field : <select name="smsalert_forminator_general[forminator_sms_phone_<?php echo esc_attr($ks); ?>]">
        <?php
        foreach ( $fields as $key=>$value ) {
            ?>
                            <option value="<?php echo esc_attr($key); ?>" <?php echo ( trim(smsalert_get_option('forminator_sms_phone_' . $ks, 'smsalert_forminator_general', '')) === $key ) ? 'selected="selected"' : ''; ?>><?php echo esc_attr($value); ?></option>
            <?php
        }
        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><input data-parent_id="smsalert_forminator_general[forminator_form_status_<?php echo esc_attr($ks); ?>]" type="checkbox" name="smsalert_forminator_general[forminator_otp_<?php echo esc_attr($ks); ?>]" id="smsalert_forminator_general[forminator_otp_<?php echo esc_attr($ks); ?>]" class="notify_box" <?php echo ( ( smsalert_get_option('forminator_otp_' . esc_attr($ks), 'smsalert_forminator_general', 'off') === 'on' ) ? "checked='checked'" : '' ); ?>/><label for="smsalert_forminator_general[forminator_otp_<?php echo esc_attr($ks); ?>]">Enable Mobile Verification</label>
                    </td>
                </tr>
            </table>
        </div>
    <?php } ?>
    </div>
</div>
<!--end accordion-->
    <?php
} else {
    echo '<h3>No Form(s) published</h3>';
}
?>
