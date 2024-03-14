<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */

defined('ABSPATH') or die;
?>
<div id="wlpe-settings">
    <div class="wlpe-setting-page-holder">
        <div class="wlpe-spinner">
            <span class="spinner"></span>
        </div>
        <form id="wlpe-settings_form" method="post">
            <div class="wlpe-settings-header">
                <div class="wlpe-setting-heading"><p><?php esc_html_e('SETTINGS', 'wp-loyalty-rules') ?></p></div>
                <div class="wlpe-button-block">
                    <div class="wlpe-back-to-apps">
                        <a class="button" target="_self"
                           href="<?php echo isset($app_url) ? esc_url($app_url) : '#'; ?>">
                            <img src="<?php echo (isset($back) && !empty($back)) ? esc_url($back) : ''; ?>"
                                 alt="<?php esc_html_e("Back", "wp-loyalty-rules"); ?>">
                            <?php esc_html_e('Back to WPLoyalty', 'wp-loyalty-rules'); ?></a>
                    </div>
                    <div class="wlpe-save-changes">
                        <button type="button" id="wlpe-setting-submit-button" onclick="wlpe.saveSettings();">
                            <img src="<?php echo (isset($save) && !empty($save)) ? esc_url($save) : ''; ?>">
                            <span><?php esc_html_e('Save Changes', 'wp-loyalty-rules') ?></span>
                        </button>
                    </div>
                    <span class='spinner'></span>
                </div>
            </div>
            <div class="wlpe-setting-body">
                <div class="wlpe-settings-body-content">
                    <div class="wlpe-field-block">
                        <div>
                            <?php $enable_expire_point = isset($options['enable_expire_point']) && !empty($options['enable_expire_point']) && ($options['enable_expire_point'] === 'yes') ?
                                $options['enable_expire_point'] : 'no'; ?>
                            <input type="checkbox" id="wlpe_enable_expire_point" name="enable_expire_point"
                                   value="<?php echo esc_attr($enable_expire_point); ?>"
                                   onclick="wlpe.enableExpiryPoint('wlpe_enable_expire_point');"
                                <?php echo isset($options['enable_expire_point']) && !empty($options['enable_expire_point']) && ($options['enable_expire_point'] == 'yes') ?
                                    'checked="checked"' : ""; ?>><label class="wlpe-enable-expire-point-label"
                                                                        for="wlpe_enable_expire_point"><?php esc_html_e('Enable Points Expiry feature ?', 'wp-loyalty-rules'); ?></label>
                        </div>
                    </div>
                    <div class="wlpe-field-block">
                        <div>
                            <label
                                    class="wlpe-settings-notification-label"><?php esc_html_e('Set validity for the points', 'wp-loyalty-rules'); ?></label>
                        </div>
                        <div class="wlpe-expire-time-block">
                            <div>
                                <label
                                        class="wlpe-setting-label"><?php esc_html_e('How long the points will be valid ?', 'wp-loyalty-rules') ?></label>
                            </div>
                            <div class="wlpe_expire_after_value_block">
                                <div class="wlpe-expire-time-1">
                                    <div class="wlpe-input-field">
                                        <?php $expire_after = isset($options) && !empty($options) && is_array($options) && isset($options['expire_after']) && !empty($options['expire_after']) ? $options['expire_after'] : 45 ?>
                                        <input type="number" min="0" name="expire_after" class="wlpe-expire-after"
                                               value="<?php echo esc_attr($expire_after); ?>"/>
                                    </div>
                                    <div class="wlpe-days">
                                        <p><?php esc_html_e('in days', 'wp-loyalty-rules'); ?></p>
                                    </div>
                                    <input type="hidden" min="0" name="expire_period" class="wlpe-expired-period"
                                           value="day"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wlpe-email-expiry-email">
                        <div class="wlpe-send-email-checkbox">
                            <?php $enable_expire_email = isset($options['enable_expire_email']) && !empty($options['enable_expire_email']) ? $options['enable_expire_email'] : 0; ?>
                            <input type="checkbox" id="wlpe_enable_expire_email" name="enable_expire_email" value="1"
                                   onclick="wlpe.toggleSection();" <?php echo $enable_expire_email ? 'checked="checked"' : ""; ?>><label
                                    for="wlpe_enable_expire_email"><?php esc_html_e('Send an email notification before the expiry of points?', 'wp-loyalty-rules'); ?></label>
                        </div>
                        <div class="wlpe-email-notification"
                             style="display: <?php echo $enable_expire_email ? 'block' : 'none'; ?>">
                            <div id="wlpe_expire_email_block">
                                <label
                                        class="wlpe-setting-label"><?php esc_html_e('How many days before an expiry email notification be sent ?', 'wp-loyalty-rules'); ?></label>
                            </div>
                            <div class="wlpe_expire_email_after_value_block">
                                <div class="wlpe-expire-time-1">
                                    <div class="wlpe-input-field">
                                        <?php $expire_email_after = isset($options) && !empty($options) && is_array($options) && isset($options['expire_email_after']) && !empty($options['expire_email_after']) ? $options['expire_email_after'] : 7 ?>
                                        <input type="number" min="0" name="expire_email_after"
                                               class="wlpe-email-notification-value"
                                               value="<?php echo esc_attr($expire_email_after); ?>"/>
                                    </div>
                                    <div class="wlpe-days">
                                        <p><?php esc_html_e('in days', 'wp-loyalty-rules'); ?></p>
                                    </div>
                                    <input type="hidden" min="0" name="expire_email_period"
                                           class="wlpe-email-notification-time"
                                           value="day"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wlpe-email-expiry-email wlpe-email-expiry-editor"
                         style="display: <?php echo $enable_expire_email ? 'flex' : 'none'; ?>">
                        <div class="wlpe-send-email-checkbox">
                            <?php $enable_expire_email = isset($options['enable_expire_email']) && !empty($options['enable_expire_email']) ? $options['enable_expire_email'] : 0; ?>
                            <label
                                    for="wlpe-expire-email-template-label"><?php esc_html_e('Points Expiry Email Template Content', 'wp-loyalty-rules'); ?></label>
                        </div>
                        <div class="wlpe-email-template" id="wlpe-email-template-editor">
                            <a href="<?php echo isset($manage_email_url) ? $manage_email_url : '#'; ?>"
                               target="_blank" class="redirect-to-loyalty">
                                <?php esc_html_e("Manage email template", "wp-loyalty-rules"); ?>
                            </a>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="wlpe_save_settings">
                    <input type="hidden" name="wlpe_nonce"
                           value="<?php echo isset($wlpe_setting_nonce) && !empty($wlpe_setting_nonce) ? esc_attr($wlpe_setting_nonce) : ''; ?>">
                    <input type="hidden" name="option_key"
                           value="<?php echo !empty($save_key) ? esc_attr($save_key) : 'wlpe_settings' ?>">
                    <div class="wlpe-field-block">
                        <div>
                            <?php $enable_customer_page_expire_content = isset($options['enable_customer_page_expire_content']) && !empty($options['enable_customer_page_expire_content']) && ($options['enable_customer_page_expire_content'] === 'yes') ?
                                $options['enable_customer_page_expire_content'] : 'no'; ?>
                            <input type="checkbox" id="wlpe_enable_customer_page_expire_content"
                                   name="enable_customer_page_expire_content"
                                   value="<?php echo esc_attr($enable_customer_page_expire_content); ?>"
                                   onclick="wlpe.enableExpiryPoint('wlpe_enable_customer_page_expire_content')" <?php echo isset($options['enable_customer_page_expire_content']) && !empty($options['enable_customer_page_expire_content']) && ($options['enable_customer_page_expire_content'] == 'yes') ?
                                'checked="checked"' : ""; ?>><label class="wlpe-enable-expire-point-label"
                                                                    for="wlpe_enable_customer_page_expire_content"><?php esc_html_e('Show a list of "Upcoming Points Expiration" in the customer reward page', 'wp-loyalty-rules'); ?></label>
                        </div>
                    </div>
                    <div class="wlpe-field-block" id="wlpe_enable_customer_page_expire_content_section"
                         style="<?php
                         echo isset($options['enable_customer_page_expire_content']) &&
                         $options['enable_customer_page_expire_content'] === 'yes' ? 'display:block' : 'display:none'; ?>">
                        <div class="wlpe-expire-time-block">
                            <div>
                                <label
                                        class="wlpe-setting-label"><?php echo esc_html_e('How many days to consider for the "Upcoming Points Expiration" List ?', 'wp-loyalty-rules') ?></label>
                            </div>
                            <div class="wlpe_expire_after_value_block">
                                <div class="wlpe-expire-time-1">
                                    <div class="wlpe-input-field">
                                        <input type="number" min="0" name="expire_date_range" class="wlpe-expire-after"
                                               value="<?php echo isset($options) && !empty($options) && is_array($options) && isset($options['expire_date_range']) && !empty($options['expire_date_range']) ? $options['expire_date_range'] : 30 ?>"/>
                                    </div>
                                    <div class="wlpe-days">
                                        <p><?php esc_html_e('days', 'wp-loyalty-rules'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
