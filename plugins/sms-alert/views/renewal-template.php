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
$notify_id = !empty($templates[0]['notify_id']) ? $templates[0]['notify_id'] : 'renewal_reminder';
?>
<div class="cvt-accordion">
    <div class="accordion-section">
        <div class="cvt-accordion-body-title" data-href="#<?php echo esc_attr($notify_id); ?>">
            <input type="checkbox" name="<?php echo esc_attr($templates[0]['checkboxNameId']); ?>" id="<?php echo esc_attr($templates[0]['checkboxNameId']); ?>" class="notify_box" <?php echo ( 'on' === $templates[0]['enabled'] ) ? "checked='checked'" : ''; ?> /><label><?php echo esc_attr($templates[0]['title']); ?></label>
            <span class="expand_btn"></span>
        </div>
        <div id="<?php echo esc_attr($notify_id); ?>" class="cvt-accordion-body-content">
            <?php
                $count = 0;
            foreach ( $templates as $template ) {
                ?>
            <table class="form-table wc_renewal_sche bottom-border" id="scheduler_<?php echo esc_attr($count); ?>">
                <tr valign="top">
                    <th>
                        <label><?php esc_html_e('Send Renewal Reminder', 'sms-alert'); ?></label>
                    </th>
                    <td>
                <?php
                $days = $template['frequency'];
                if (empty($days) ) {
                    $days = 1;
                }
                ?>
                        <select id="<?php echo esc_attr($template['selectNameId']); ?>" name="<?php echo esc_attr($template['selectNameId']); ?>" data-parent_id="<?php echo esc_attr($template['checkboxNameId']); ?>">
                            <option value='1' <?php selected($days, 1); ?>><?php esc_html_e('Before 1 days', 'sms-alert'); ?></option>
                            <option value='2' <?php selected($days, 2); ?>><?php esc_html_e('Before 2 days', 'sms-alert'); ?></option>
                            <option value='3' <?php selected($days, 3); ?>><?php esc_html_e('Before 3 days', 'sms-alert'); ?></option>
                            <option value='4' <?php selected($days, 4); ?>><?php esc_html_e('Before 4 days', 'sms-alert'); ?></option>
                            <option value='5' <?php selected($days, 5); ?>><?php esc_html_e('Before 5 days', 'sms-alert'); ?></option>
                            <option value='0' <?php selected($days, 0); ?>><?php esc_html_e('Disable notifications', 'sms-alert'); ?></option>
                        </select>
                        <a href="#" onclick="return false;" class="sa-delete-btn alignright"><span class="dashicons dashicons-dismiss"></span><?php esc_html_e('Remove', 'sms-alert'); ?></a>
                    </td>
                </tr>
                <tr valign="top">
                    <td colspan="2">
                        <div class="smsalert_tokens">
                <?php
                foreach ( $template['token'] as $vk => $vv ) {
                    echo  "<a href='#' data-val='".esc_attr($vk)."'>".esc_attr($vv)."</a> | ";
                }
                ?>
                <?php if (! empty($template['moreoption']) ) { ?>
                                <a href="<?php echo esc_url($url); ?>" class="thickbox search-token-btn">[...More]</a>
                <?php } ?>
                        </div>
                        <textarea name="<?php echo esc_attr($template['textareaNameId']); ?>" id="<?php echo esc_attr($template['textareaNameId']); ?>" data-parent_id="<?php echo esc_attr($template['checkboxNameId']); ?>" <?php echo( ( 'on' === $template['enabled'] ) ? '' : "readonly='readonly'" ); ?> class="token-area"><?php echo esc_textarea($template['text-body']); ?></textarea>
                        <div id="menu_renewal" class="sa-menu-token" role="listbox"></div>
                    </td>
                </tr>
            </table>
                <?php $count++; 
            } ?>
            <div style="padding: 10px 0px 0px 10px;">
                <button class="button action addNew" type="button" data-parent_id="<?php echo esc_attr($template['checkboxNameId']); ?>"> <span class="dashicons dashicons-plus-alt2"></span> <?php esc_html_e('Add New', 'sms-alert'); ?></button>
            </div>
            </div>
        
        </div>
    </div>
<!-- /-cvt-accordion -->
<script>
    jQuery("#<?php echo $notify_id ?> .addNew").on("click", addReminder );

    function addReminder(){
        var notify_id = jQuery(this).parents(".cvt-accordion-body-content").attr("id");
        var last_scheduler_no = jQuery('#'+notify_id).find('.form-table:last').attr("id").split('_')[1];

        jQuery("#"+notify_id+" .form-table:last").clone().insertAfter("#"+notify_id+" .form-table:last");

        var new_scheduler_no = +last_scheduler_no + 1;

        jQuery('#'+notify_id+' .form-table:last').attr('id', 'scheduler_' + new_scheduler_no);

        var scheduler_last = jQuery("#"+notify_id+" #scheduler_"+new_scheduler_no).html().replace(  /\[cron\]\[\d+\]/g,  "[cron]["+new_scheduler_no+"]");

        jQuery('#'+notify_id+' #scheduler_'+new_scheduler_no).html(scheduler_last);
    }

    //delete ab cart cron schedule
    jQuery(document).on('click',"#<?php echo $notify_id ?> .sa-delete-btn",function(){
        var notify_id = jQuery(this).parents(".cvt-accordion-body-content").attr("id");
        var last_item     = (jQuery("#"+notify_id+" .wc_renewal_sche").length==1) ? true : false;
        if(last_item)
        {
            showAlertModal(alert_msg.last_item);
            return false;
        }
        else
        {
            jQuery(this).parents("#"+notify_id+" .wc_renewal_sche").remove();
        }
    });
</script>
