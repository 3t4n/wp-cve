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
$formmarker_forms = SAFormMaker::getFormMaker();
if (! empty($formmarker_forms) ) {
    ?>
<div class="cvt-accordion">
    <div class="accordion-section">
    <?php foreach ( $formmarker_forms as $ks => $vs ) { ?>
        <div class="cvt-accordion-body-title" data-href="#accordion_<?php echo esc_attr($ks); ?>">
            <input type="checkbox" name="smsalert_formmarker_general[formmarker_admin_notification_<?php echo esc_attr($ks); ?>]" id="smsalert_formmarker_general[formmarker_admin_notification_<?php echo esc_attr($ks); ?>]" class="notify_box" <?php echo ( ( smsalert_get_option('formmarker_admin_notification_' . $ks, 'smsalert_formmarker_general', 'on') === 'on' ) ? "checked='checked'" : '' ); ?>/><label><?php echo esc_html(ucwords(str_replace('-', ' ', $vs))); ?></label>
            <span class="expand_btn"></span>
        </div>
        <div id="accordion_<?php echo esc_attr($ks); ?>" class="cvt-accordion-body-content">
            <table class="form-table">
                <tr valign="top" style="position:relative">
                <td>
                <a href="admin.php?page=manage_fm&task=edit&current_id=<?php echo $ks;?>" title="Edit Form" target="_blank" class="alignright"><small><?php esc_html_e('Edit Form', 'sms-alert')?></small></a>
                <div class="smsalert_tokens">
        <?php
        $fields = SAFormMaker::getFormMakerVariables($ks);
        foreach ( $fields as $key=>$value ) {
            echo  "<a href='#' data-val='[" . esc_attr($key) . "]'>".esc_attr($value)."</a> | ";
        }
        ?>
                </div>                
                <textarea data-parent_id="smsalert_formmarker_general[formmarker_admin_notification_<?php echo esc_attr($ks); ?>]" name="smsalert_formmarker_message[formmarker_admin_sms_body_<?php echo esc_attr($ks); ?>]" id="smsalert_formmarker_message[formmarker_admin_sms_body_<?php echo esc_attr($ks); ?>]" <?php echo( ( smsalert_get_option('formmarker_admin_notification_' . esc_attr($ks), 'smsalert_formmarker_general', 'on') === 'on' ) ? '' : "readonly='readonly'" ); ?> class="token-area"><?php echo esc_textarea(smsalert_get_option('formmarker_admin_sms_body_' . $ks, 'smsalert_formmarker_message', SmsAlertMessages::showMessage('DEFAULT_CONTACT_FORM_ADMIN_MESSAGE'))); ?></textarea>
                <div id="menu_formmarker_admin_<?php echo esc_attr($ks); ?>" class="sa-menu-token" role="listbox"></div>
                </td>
                </tr>
            </table>
        </div>
    <?php } ?>
    </div>
</div>
    <?php
} else {
    echo '<h3>No Form(s) published</h3>';
}
?>
