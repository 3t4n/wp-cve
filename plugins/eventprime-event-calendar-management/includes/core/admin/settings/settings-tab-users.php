<?php $global_options = $options['global'];?>
<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'Users', 'eventprime-event-calendar-management' );?></h2>
    <input type="hidden" name="em_setting_type" value="users_settings">
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="enable_seo_urls">
                    <?php esc_html_e( 'Enable Pretty URLs', 'eventprime-event-calendar-management' );?>
                    <span class="ep-help-tip" tooltip="Turning this on will replace current ID based event URLs with custom subdirectory and slug structure. You may wish to redirect your old URLs to new ones if they are already cached by search engines."></span>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="enable_seo_urls" id="enable_seo_urls" onclick="hide_show_google_share_setting(this,'ep_enable_seo_urls_child')" type="checkbox" value="1" <?php echo isset($global_options->enable_seo_urls ) && $global_options->enable_seo_urls == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
            </td>
        </tr>
    </tbody>
</table>