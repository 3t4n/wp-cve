<?php
if (isset($_POST['spr_reset_votes']))
{
    if (current_user_can('activate_plugins'))
    {
        spr_truncate_tables();
    }
}
spr_save_settings();
$options=spr_options();
spr_load_localization();
wp_enqueue_style('farbtastic');
wp_enqueue_script('farbtastic');
wp_enqueue_style('spr_style', plugins_url('/resources/spr_style.css', __FILE__));
wp_enqueue_script('spr_admin', plugins_url('/resources/spr_admin.js', __FILE__), array('farbtastic', 'jquery'), NULL);
wp_localize_script('spr_admin', 'spr_ajax_object', array('scale'=>$options['scale'], 'spr_type'=>$options['color'].$options['shape']));
?>
<h1><?php spr_localize('settings_header'); ?></h1>
<div style="float:left;">
    <form name="form" method="POST" style="margin-top:15px;">
        <table>
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_show_rating'); ?></label></td>
                <td><input type="checkbox" name="spr_activated" id="spr_activated" value="<?php echo $options['activated']; ?>" <?php checked($options['activated'], 1, true); ?>></td>
                <td class="spr_hint_container"><div class="spr_hint tooltip-right" data-tooltip="<?php spr_localize('settings_option_show_rating_tip'); ?>"></div></td>
            </tr>
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_allow_guest_votes'); ?></label></td>
                <td><input type="checkbox" name="spr_allow_guest_vote" id="spr_allow_guest_vote" value="<?php echo $options['allow_guest_vote']; ?>" <?php checked($options['allow_guest_vote'], 1, true); ?>></td>
                <td class="spr_hint_container"><div class="spr_hint tooltip-right" data-tooltip="<?php spr_localize('settings_option_allow_guest_votes_tip'); ?>"></div></td>
            </tr>
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_insertion_method'); ?></label></td>
                <td>
                    <select name="spr_method" id="spr_method" class="spr_admin_input">
                        <option value="auto" <?php selected($options['method'], 'auto', true); ?>><?php spr_localize('settings_option_insertion_method_automatic'); ?></option>
                        <option value="manual" <?php selected($options['method'], 'manual', true); ?>><?php spr_localize('settings_option_insertion_method_manual'); ?></option>
                    </select>
                </td>
                <td class="spr_hint_container"><div class="spr_hint tooltip-right" data-tooltip="<?php spr_localize('settings_option_insertion_method_tip'); ?>"></div></td>
            </tr> 
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_shape'); ?></label></td>
                <td>
                    <select name="spr_shape" id="spr_shape" class="spr_admin_input">
                        <option value="s" <?php selected($options['shape'], 's', true); ?>><?php spr_localize('settings_option_shape_stars'); ?></option>
                        <option value="c" <?php selected($options['shape'], 'c', true); ?>><?php spr_localize('settings_option_shape_circles'); ?></option>
                        <option value="h" <?php selected($options['shape'], 'h', true); ?>><?php spr_localize('settings_option_shape_hearts'); ?></option>
                        <option value="b" <?php selected($options['shape'], 'b', true); ?>><?php spr_localize('settings_option_shape_bar'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_color'); ?></label></td>
                <td>
                    <select name="spr_color" id="spr_color" class="spr_admin_input">
                        <option value="y" <?php selected($options['color'], 'y', true); ?>><?php spr_localize('settings_option_color_yellow'); ?></option>
                        <option value="p" <?php selected($options['color'], 'p', true); ?>><?php spr_localize('settings_option_color_purple'); ?></option>
                        <option value="g" <?php selected($options['color'], 'g', true); ?>><?php spr_localize('settings_option_color_green'); ?></option>
                        <option value="b" <?php selected($options['color'], 'b', true); ?>><?php spr_localize('settings_option_color_blue'); ?></option>
                        <option value="r" <?php selected($options['color'], 'r', true); ?>><?php spr_localize('settings_option_color_red'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_alignment'); ?></label></td>
                <td>
                    <select name="spr_alignment" id="spr_alignment" class="spr_admin_input">
                        <option value="center" <?php selected($options['alignment'], 'center', true); ?>><?php spr_localize('settings_option_alignment_center'); ?></option>
                        <option value="right" <?php selected($options['alignment'], 'right', true); ?>><?php spr_localize('settings_option_alignment_right'); ?></option>
                        <option value="left" <?php selected($options['alignment'], 'left', true); ?>><?php spr_localize('settings_option_alignment_left'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_show_vote_count'); ?></label></td>
                <td><input type="checkbox" name="spr_show_vote_count" id="spr_show_vote_count" value="<?php echo $options['show_vote_count']; ?>" <?php checked($options['show_vote_count'], 1, true); ?>></td>
            </tr>
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_vote_count_color'); ?></label></td>
                <td>
                    <input type="text" size="10" maxlength="8" name="spr_vote_count_color" id="spr_vote_count_color" value="<?php echo $options['vote_count_color']; ?>" class="spr_admin_input">
                </td>
                <td><a href="#" id="spr_vote_count_color_box" class="pickcolor" style="padding: 4px 11px; border: 1px solid #dfdfdf; background-color: <?php echo $options['vote_count_color']; ?>;"></a>
                    <div id="psr_color_picker" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div></td>
            </tr>
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_vote_count_style'); ?></label></td>
                <td> 
                    <?php spr_localize('settings_option_vote_count_style_bold'); ?> <input type="checkbox" name="spr_vc_bold" id="spr_vc_bold" value="<?php echo $options['vc_bold']; ?>" <?php checked($options['vc_bold'], 1, true); ?>>
                    <?php spr_localize('settings_option_vote_count_style_italic'); ?> <input type="checkbox" name="spr_vc_italic" id="spr_vc_italic" value="<?php echo $options['vc_italic']; ?>" <?php checked($options['vc_italic'], 1, true); ?>>
                </td>
            </tr>
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_scale'); ?></label></td>
                <td><input type="text" size="10" maxlength="200" name="spr_scale" id="spr_scale" value="<?php echo $options['scale']; ?>" class="spr_admin_input"></td>
                <td class="spr_hint_container"><div class="spr_hint tooltip-right" data-tooltip="<?php spr_localize('settings_option_scale_tip'); ?>"></div></td>
            </tr>
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_where_to_add'); ?></label></td>
                <td>
                    <?php echo spr_get_post_types_fo(); ?>
                </td>
            </tr> 
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_position'); ?></label></td>
                <td>
                    <select name="spr_position" id="spr_position" class="spr_admin_input">
                        <option value="before" <?php selected($options['position'], 'before', true); ?>><?php spr_localize('settings_option_position_before'); ?></option>
                        <option value="after" <?php selected($options['position'], 'after', true); ?>><?php spr_localize('settings_option_position_after'); ?></option>
                    </select>
                </td>
            </tr>  
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_show_in_loops'); ?></label></td>
                <td><input type="checkbox" name="spr_show_in_loops" id="spr_show_in_loops" value="<?php echo $options['show_in_loops']; ?>" <?php checked($options['show_in_loops'], 1, true); ?>></td>
                <td class="spr_hint_container"><div class="spr_hint tooltip-right" data-tooltip="<?php spr_localize('settings_option_show_in_loops_tip'); ?>"></div></td>
            </tr>
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_show_in_loops_hompage'); ?></label></td>
                <td><input type="checkbox" name="spr_loop_on_hp" id="spr_loop_on_hp" value="<?php echo $options['loop_on_hp']; ?>" <?php checked($options['loop_on_hp'], 1, true); ?>></td>
                <td class="spr_hint_container"><div class="spr_hint tooltip-right" data-tooltip="<?php spr_localize('settings_option_show_in_loops_hompage_tip'); ?>"></div></td>
            </tr>
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_aggregated'); ?></label></td>
                <td><input type="checkbox" name="spr_use_aggregated" id="spr_use_aggregated" value="<?php echo $options['use_aggregated']; ?>" <?php checked($options['use_aggregated'], 1, true); ?>></td>
                <td class="spr_hint_container"><div class="spr_hint tooltip-right" data-tooltip="<?php spr_localize('settings_option_aggregated_tip'); ?>"></div></td>
            </tr>
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_statistics_metabox'); ?></label></td>
                <td><input type="checkbox" name="spr_show_stats_metabox" id="spr_show_stats_metabox" value="<?php echo $options['show_stats_metabox']; ?>" <?php checked($options['show_stats_metabox'], 1, true); ?>></td>
                <td class="spr_hint_container"><div class="spr_hint tooltip-right" data-tooltip="<?php spr_localize('settings_option_statistic_metabox_tip'); ?>"></div></td>
            </tr>
            <tr>
                <td  class="spr_adm_label"><label><?php spr_localize('settings_option_locale'); ?></label></td>
                <td>
                    <select name="spr_locale" id="spr_locale" class="spr_admin_input">
                        <?php
                        $locales=spr_scan_locales(true);
                        foreach ($locales as $locale)
                        {
                            echo '<option value="'.$locale["code"].'" '.selected($options['localization'], $locale["code"], true).'>'.$locale["language"].'</option>';
                        }
                        ?>

                    </select>
                </td>
            </tr>  
        </table>
        <input type="submit" style="margin-top:10px;" class='button button-primary button-large' value="<?php spr_localize('settings_save_button'); ?>">
    </form>
</div>
<div id="postbox-container-1" class="postbox-container" style="float: right;display:inline-block;width: 280px;margin-right:20px;">
    <div class="postbox ">
        <h3 class="spr_widget_title">
            <span><?php spr_localize('settings_widgets_live_preview_title'); ?></span>
        </h3>
        <div class="inside">         
            <div id="spr_container"><div class="spr_visual_container"><?php echo spr_show_voting(5, 25, $options['show_vote_count']); ?></div></div>
        </div>
    </div>
    <div class="postbox ">
        <h3 class="spr_widget_title">
            <span><?php spr_localize('settings_widgets_donate_title'); ?></span>
        </h3>
        <div class="inside">         
            <form action="https://www.moneybookers.com/app/payment.pl" method="post">
                <input type="hidden" name="pay_to_email" value="igor.yavych@gmail.com">
                <input type="hidden" name="status_url" value="mailto:igor.yavych@gmail.com">
                <input type="hidden" name="language" value="EN">
                <input type="hidden" name="recipient_description" value="Simple Rating">
                <input type="text" name="amount" size="5"  value="5" />
                <select name="currency" style="margin-top:-1px;" id="currency">
                    <option value="USD" selected="selected">USD</option>
                    <option value="EUR">EUR</option>
                </select>
                <input type="hidden" name="confirmation_note" value="Thanks for your support!">
                <br/><input class="spr_button button button-primary button-small" type="submit" value="<?php spr_localize('settings_widgets_donate_button'); ?>">
            </form>
        </div>
    </div>
    <div class="postbox ">
        <h3 class="spr_widget_title">
            <span><?php spr_localize('settings_widgets_reset_votes_title'); ?></span>
        </h3>
        <div class="inside">         
            <form method="post" onsubmit="return confirm('<?php spr_localize('settings_widgets_reset_votes_confirmation'); ?>')">
                <?php spr_localize('settings_widgets_reset_votes_description'); ?><br/>
                <input type="hidden" name="spr_reset_votes" value="1">
                <input class="spr_button button button-primary button-small" type="submit" value="<?php spr_localize('settings_widgets_reset_votes_button'); ?>">
            </form>
        </div>
    </div>
    <div class="postbox ">
        <h3 class="spr_widget_title">
            <span><?php spr_localize('settings_widgets_feedback_title'); ?></span>
        </h3>
        <div class="inside">         
            <?php spr_localize('settings_widgets_feedback_description'); ?>
        </div>
    </div>
</div>