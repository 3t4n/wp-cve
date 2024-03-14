<?php
if (!defined('ABSPATH')) {
    die();
}

/**
 * Provide a settings area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://larapush.com
 * @since      1.0.0
 *
 * @package    Unlimited_Push_Notifications_By_Larapush
 * @subpackage Unlimited_Push_Notifications_By_Larapush/admin/partials
 */

try {
    $connection = Unlimited_Push_Notifications_By_Larapush_Admin_Helper::checkConnection();
    if ($connection) {
        $campaignFilter = Unlimited_Push_Notifications_By_Larapush_Admin_Helper::getCampaignFilter();
        $integration_done = Unlimited_Push_Notifications_By_Larapush_Admin_Helper::codeIntegration();
        update_option('unlimited_push_notifications_by_larapush_panel_integration_tried', true);
    } else {
        $campaignFilter = false;
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<div class="wrap">
    <div>
        <h1>Connect LaraPush</h1>
        <p>Send unlimited push notifications to your users directly from WordPress.</p>
        <?php settings_errors('unlimited-push-notifications-by-larapush-settings'); ?>
        <?php if (isset($error)) { ?>
            <div class="notice notice-error is-dismissible">
                <p><?php echo esc_html($error) ?></p>
            </div>
        <?php } ?>
        <?php if ($integration_done) { ?>
            <div class="notice notice-success is-dismissible">
                <p>Settings saved.</p>
            </div>
        <?php } ?>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')) ?>" style="display: inline">
            <input type="hidden" name="action" value="larapush_connect">			
            <?php wp_nonce_field('larapush_connect'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Panel URL</th>
                    <td>
                        <input type="text" name="unlimited_push_notifications_by_larapush_panel_url" value="<?php echo esc_attr(
                            Unlimited_Push_Notifications_By_Larapush_Admin_Helper::decode(
                                get_option('unlimited_push_notifications_by_larapush_panel_url')
                            )
                        ) ?>" />
                        <?php if (
                            get_option('unlimited_push_notifications_by_larapush_panel_integration_tried', false) ==
                            true
                        ) { ?>
                            <p class="description"><?php echo $connection == true
                                ? '<span class="dashicons dashicons-yes" style="color: green;"></span> Connected'
                                : '<span class="dashicons dashicons-no" style="color: red;"></span> Not Connected' ?></p>
                        <?php } ?>
                    
                </tr>
                <tr valign="top">
                    <th scope="row">Panel Email</th>
                    <td><input type="text" name="unlimited_push_notifications_by_larapush_panel_email" value="<?php echo esc_attr(
                        Unlimited_Push_Notifications_By_Larapush_Admin_Helper::decode(
                            get_option('unlimited_push_notifications_by_larapush_panel_email')
                        )
                    ) ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Panel Password</th>
                    <td><input type="password" name="unlimited_push_notifications_by_larapush_panel_password" value="<?php echo esc_attr(
                        Unlimited_Push_Notifications_By_Larapush_Admin_Helper::decode(
                            get_option('unlimited_push_notifications_by_larapush_panel_password')
                        )
                    ) ?>" /></td>
                </tr>
            
                
                <tr valign="top">
                    <th scope="row">Enable Subscriber Collection</th>
                    <td><input type="checkbox" name="unlimited_push_notifications_by_larapush_enable_push_notifications" value="1" <?php checked(
                        1,
                        get_option('unlimited_push_notifications_by_larapush_enable_push_notifications', 1),
                        true
                    ); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Push On Publish</th>
                    <td><input type="checkbox" name="unlimited_push_notifications_by_larapush_push_on_publish" value="1" <?php checked(
                        1,
                        get_option('unlimited_push_notifications_by_larapush_push_on_publish', 0),
                        true
                    ); ?> />
                    <p class="description">Send Notifications to all your subscribers on as soon as you publish a post.</p></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Push On Publish WebStories</th>
                    <td><input type="checkbox" name="unlimited_push_notifications_by_larapush_push_on_publish_for_webstories" value="1" <?php checked(
                        1,
                        get_option('unlimited_push_notifications_by_larapush_push_on_publish_for_webstories', 0),
                        true
                    ); ?> />
                    <p class="description">Send Notifications to all your subscribers on as soon as you publish a web story.</p></td>
                </tr>
                <?php if ($campaignFilter == true) { ?>
                <tr valign="top">
                    <th scope="row">Select Domains to Send Notifications</th>
                    <td>
                        <select name="unlimited_push_notifications_by_larapush_panel_domains_selected[]" multiple="multiple" style="width: 100%; height: 100px;">
                            <?php
                            $domains = get_option('unlimited_push_notifications_by_larapush_panel_domains', []);
                            $domains_selected = get_option(
                                'unlimited_push_notifications_by_larapush_panel_domains_selected',
                                []
                            );
                            foreach ($domains as $domain) { ?>
                                <option value="<?php echo esc_attr($domain) ?>"  <?php selected(
    true,
    in_array($domain, $domains_selected)
); ?>><?php echo esc_html($domain) ?></option>
                            <?php }
                            ?>
                        </select>
                        <p class="description">Use ctrl to select multiple domains</p>
                    </td>
                </tr>
                <?php if (count(get_option('unlimited_push_notifications_by_larapush_panel_migrated_domains', []))) { ?>
                <tr valign="top">
                    <th scope="row">Select Migrated Domains to Send Notifications</th>
                    <td>
                        <select name="unlimited_push_notifications_by_larapush_panel_migrated_domains_selected[]" id="unlimited_push_notifications_by_larapush_panel_migrated_domains_selected" multiple="multiple" style="width: 100%; height: 100px;">
                            <?php
                            $migrated_domains = get_option(
                                'unlimited_push_notifications_by_larapush_panel_migrated_domains',
                                []
                            );
                            $migrated_domains_selected = get_option(
                                'unlimited_push_notifications_by_larapush_panel_migrated_domains_selected',
                                []
                            );
                            foreach ($migrated_domains as $domain) { ?>
                                <option value="<?php echo esc_attr($domain) ?>"  <?php selected(
    true,
    in_array($domain, $migrated_domains_selected)
); ?>><?php echo esc_html($domain) ?></option>
                            <?php }
                            ?>
                        </select>
                        <p class="description">
                            <button type="button" class="button button-secondary" id="unselect_all_migrated_domains" onclick='document.querySelectorAll("#unlimited_push_notifications_by_larapush_panel_migrated_domains_selected>option").forEach(e => e.selected = false)'>Unselect All</button>
                        </p>
                        <p class="description">Use ctrl to select multiple domains</p>
                    </td>
                </tr>
                <?php } ?>
                <tr valign="top">
                    <th scope="row">Enable AMP</th>
                    <td><input type="checkbox" name="unlimited_push_notifications_by_larapush_add_code_for_amp" value="1" <?php checked(
                        1,
                        get_option('unlimited_push_notifications_by_larapush_add_code_for_amp', 0),
                        true
                    ); ?> />
                    <p class="description">Check to show subscribe button on AMP.</p></td>
                </tr>
                <tr valign="top">
                    <th scope="row">AMP Subscribe Button Location</th>
                    <td>
                    <?php $amp_code_location = get_option(
                        'unlimited_push_notifications_by_larapush_amp_code_location',
                        []
                    ); ?>
                        <select name="unlimited_push_notifications_by_larapush_amp_code_location[]" multiple="multiple" style="width: 100%; height: 100px;">
                            <option value="header" <?php selected(
                                true,
                                in_array('header', $amp_code_location)
                            ); ?>>Header (All Pages)</option>
                            <option value="footer" <?php selected(
                                true,
                                in_array('footer', $amp_code_location)
                            ); ?>>Footer (All Pages)</option>
                            <option value="before_post" <?php selected(
                                true,
                                in_array('before_post', $amp_code_location)
                            ); ?>>Before Post (Post Pages)</option>
                            <option value="after_post" <?php selected(
                                true,
                                in_array('after_post', $amp_code_location)
                            ); ?>>After Post (Post Pages)</option>
                        </select>
                        <p class="description">Use ctrl to select multiple locations</p>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <button type="submit" class="button button-primary" id="larapush_connect">Save Changes</button>
        </form>
    </div>
</div>