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
$Jet_forms = JetForm::getJetformForms();
if (! empty($Jet_forms) ) {
    ?>
<!-- accordion -->
<div class="cvt-accordion">
    <div class="accordion-section">
    <?php foreach ( $Jet_forms as $ks => $vs ) { ?>
        <div class="cvt-accordion-body-title" data-href="#accordion_cust_<?php echo esc_attr($ks); ?>">
            <input type="checkbox" name="smsalert_jetform_general[jetform_order_status_<?php echo esc_attr($ks); ?>]" id="smsalert_jetform_general[jetform_order_status_<?php echo esc_attr($ks); ?>]" class="notify_box" <?php echo ( ( smsalert_get_option('jetform_order_status_' . esc_attr($ks), 'smsalert_jetform_general', 'on') === 'on' ) ? "checked='checked'" : '' ); ?>/><label><?php echo esc_attr(ucwords(str_replace('-', ' ', $vs))); ?></label>
            <span class="expand_btn"></span>
        </div>
        <div id="accordion_cust_<?php echo esc_attr($ks); ?>" class="cvt-accordion-body-content">
            <table class="form-table">
                <tr>
                    <td><input data-parent_id="smsalert_jetform_general[jetform_order_status_<?php echo esc_attr($ks); ?>]" type="checkbox" name="smsalert_jetform_general[jetform_message_<?php echo esc_attr($ks); ?>]" id="smsalert_jetform_general[jetform_message_<?php echo esc_attr($ks); ?>]" class="notify_box" <?php echo ( ( smsalert_get_option('jetform_message_' . esc_attr($ks), 'smsalert_jetform_general', 'on') === 'on' ) ? "checked='checked'" : '' ); ?>/><label for="smsalert_jetform_general[jetform_message_<?php echo esc_attr($ks); ?>]">Enable Message</label>
                    <a href="post.php?post=<?php echo $ks;?>&action=edit" title="Edit Form" target="_blank" class="alignright"><small><?php esc_html_e('Edit Form', 'sms-alert')?></small></a>
                    </td>                    
                    </tr>
                <tr valign="top"  style="position:relative">
                    <td>
                        <div class="smsalert_tokens">
        <?php
        $fields = JetForm::getJetformVariables($ks);
        foreach ( $fields as $key=>$value ) {
            echo  "<a href='#' data-val='[" . esc_attr($key) . "]'>".esc_attr($value)."</a> | ";
        }
        ?>
                        </div>
                        <textarea data-parent_id="smsalert_jetform_general[jetform_message_<?php echo esc_attr($ks); ?>]" name="smsalert_jetform_message[jetform_sms_body_<?php echo esc_attr($ks); ?>]" id="smsalert_jetform_message[jetform_sms_body_<?php echo esc_attr($ks); ?>]" <?php echo( ( smsalert_get_option('jetform_order_status_' . esc_attr($ks), 'smsalert_jetform_general', 'on') === 'on' ) ? '' : "readonly='readonly'" ); ?> class="token-area"><?php echo esc_textarea(smsalert_get_option('jetform_sms_body_' . esc_attr($ks), 'smsalert_jetform_message', SmsAlertMessages::showMessage('DEFAULT_CONTACT_FORM_CUSTOMER_MESSAGE'))); ?></textarea>
                        <div id="menu_jetform_cust_<?php echo esc_attr($ks); ?>" class="sa-menu-token" role="listbox"></div>
                    </td>
                </tr>                
                <tr>
                    <td>
                        Select Phone Field : <select name="smsalert_jetform_general[jetform_sms_phone_<?php echo esc_attr($ks); ?>]">
        <?php
        foreach ( $fields as $key=>$value ) {
            ?>
                            <option value="<?php echo esc_attr($key); ?>" <?php echo ( trim(smsalert_get_option('jetform_sms_phone_' . $ks, 'smsalert_jetform_general', '')) === $key ) ? 'selected="selected"' : ''; ?>><?php echo esc_attr($value); ?></option>
            <?php
        }
        ?>
                        </select>
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
