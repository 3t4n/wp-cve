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
?>
<!-- Admin-accordion -->
<div class="cvt-accordion"><!-- cvt-accordion -->
    <div class="accordion-section">
        <?php foreach ( $templates as $template ) { ?>
        <div class="cvt-accordion-body-title" data-href="#accordion_<?php echo esc_attr($checkTemplateFor); ?>_<?php echo esc_attr($template['status']); ?>">
            <input type="checkbox" name="<?php echo esc_attr($template['checkboxNameId']); ?>" id="<?php echo esc_attr($template['checkboxNameId']); ?>" class="notify_box" <?php echo ( 'on' === $template['enabled'] ) ? "checked='checked'" : ''; ?> <?php echo ( ! empty($template['chkbox_val']) ) ? "value='" . esc_attr($template['chkbox_val']) . "'" : ''; ?>  /><label><?php echo esc_html($template['title']); ?></label>
            <span class="expand_btn"></span>
        </div>
        <div id="accordion_<?php echo esc_attr($checkTemplateFor); ?>_<?php echo esc_attr($template['status']); ?>" class="cvt-accordion-body-content">
            <table class="form-table">
                <tr valign="top" style="position:relative">
                    <td>
                        <div class="smsalert_tokens">
            <?php
            foreach ( $template['token'] as $vk => $vv ) {
                echo  "<a href='#' data-val='".esc_attr($vk)."'>".esc_attr($vv)."</a> | ";
            }
            ?>
                        </div>
                        <textarea name="<?php echo esc_attr($template['textareaNameId']); ?>" id="<?php echo esc_attr($template['textareaNameId']); ?>" data-parent_id="<?php echo esc_attr($template['checkboxNameId']); ?>" <?php echo( ( 'on' === $template['enabled'] ) ? '' : "readonly='readonly'" );?>  class="token-area" ><?php echo esc_textarea($template['text-body']); ?></textarea>
                        <div id="menu_<?php echo esc_attr($checkTemplateFor); ?>_<?php echo $template['status']; ?>" class="sa-menu-token" role="listbox"></div>
                    </td>
                </tr>
            </table>
        </div>
        <?php } ?>
        <div>
        <table class="form-table">
        <tr valign="top" style="position:relative">
            <td class="td-heading">
                        Notify Me Style:                </td>
            <td><?php
                $disabled = (! is_plugin_active('elementor/elementor.php')) ? "anchordisabled" : "";
                $post = get_page_by_path('notifyme_style', OBJECT, 'sms-alert'); 
            ?>              
                <a href= <?php get_admin_url() ?>"edit.php?post_name=notifyme_style" data-parent_id="<?php echo esc_attr($template['checkboxNameId']); ?>" class="button <?php echo $disabled; ?> notifyme action" target="_blank" style="float:left;"><?php esc_html_e('Edit With Elementor', 'sms-alert'); ?></a>
                <?php if (!empty($post->post_type)) {?>
                <a href="#" onclick="return false;" data-parent_id="<?php echo esc_attr($template['checkboxNameId']); ?>" id="btn_reset_style" temp-style="notifyme_style" class="btn_reset_style btn-outline" style="float:left;"><?php esc_html_e('Reset', 'sms-alert'); ?></a>
                    <?php
                }
                ?>
                <span class="reset_style"></span>    
            <?php
            if ($disabled!='') {
                ?>        
            <span><?php esc_html_e('To edit, please install elementor plugin', 'sms-alert'); ?>    </span>
                <?php
            }
            ?>
            </td>
        </tr>
    </table>
        </div>
    </div>    
</div>

<!--help links-->
<?php
foreach ( $templates as $template ) {
    if (!empty($template['help_links']) ) {
            
        foreach ($template['help_links'] as $link) {
            echo wp_kses_post('<a href="'.$link['href'].'" alt="'.$link['alt'].'" target="'.$link['target'].'" class="'.$link['class'].'">'.$link['icon']." ".$link['label'].'</a>');
        }
    } 
} 
?>

<div class="submit" style="clear:both">
	<a href="admin.php?page=all-subscriber" class="button action alignright"><?php esc_html_e('View Subscriber', 'sms-alert'); ?></a>
</div>

