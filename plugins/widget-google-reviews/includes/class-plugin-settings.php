<?php

namespace WP_Rplg_Google_Reviews\Includes;

class Plugin_Settings {

    private $debug_info;

    public function __construct(Debug_Info $debug_info) {
        $this->debug_info = $debug_info;
    }

    public function register() {
        add_action('grw_admin_page_grw-settings', array($this, 'init'));
        add_action('grw_admin_page_grw-settings', array($this, 'render'));
    }

    public function init() {

    }

    public function render() {

        $tab = isset($_GET['grw_tab']) && strlen($_GET['grw_tab']) > 0 ? sanitize_text_field(wp_unslash($_GET['grw_tab'])) : 'active';

        $grw_enabled         = get_option('grw_active') == '1';
        $grw_demand_assets   = get_option('grw_demand_assets');
        $grw_minified_assets = get_option('grw_minified_assets');
        $grw_google_api_key  = get_option('grw_google_api_key');
        $grw_activation_time = get_option('grw_activation_time');
        $grw_debug_mode      = get_option('grw_debug_mode') == '1';

        $grw_revupd_cron     = get_option('grw_revupd_cron') == '1';
        ?>

        <div class="grw-page-title">
            Settings
        </div>

        <?php do_action('grw_admin_notices'); ?>

        <div class="grw-settings-workspace">

            <div data-nav-tabs="">

                <div class="nav-tab-wrapper">
                    <a href="#grw-general"  class="nav-tab<?php if ($tab == 'active')   { ?> nav-tab-active<?php } ?>">General</a>
                    <a href="#grw-google"   class="nav-tab<?php if ($tab == 'google')   { ?> nav-tab-active<?php } ?>">Google</a>
                    <a href="#grw-advance"  class="nav-tab<?php if ($tab == 'advance')  { ?> nav-tab-active<?php } ?>">Advance</a>
                </div>

                <div id="grw-general" class="tab-content" style="display:<?php echo $tab == 'active' ? 'block' : 'none'?>;">
                    <h3>General Settings</h3>
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php?action=grw_settings_save&grw_tab=active&active=' . (string)((int)($grw_enabled != true)))); ?>">
                        <div class="grw-field">
                            <div class="grw-field-label">
                                <label>Google Reviews plugin is currently <b><?php echo $grw_enabled ? 'enabled' : 'disabled' ?></b></label>
                            </div>
                            <div class="wp-review-field-option">
                                <?php wp_nonce_field('grw-wpnonce_active', 'grw-form_nonce_active'); ?>
                                <input type="submit" name="active" class="button" value="<?php echo $grw_enabled ? 'Disable' : 'Enable'; ?>" />
                            </div>
                        </div>
                        <div class="grw-field">
                            <div class="grw-field-label">
                                <label>Load assets on demand</label>
                            </div>
                            <div class="wp-review-field-option">
                                <label>
                                    <input type="hidden" name="grw_demand_assets" value="false">
                                    <input type="checkbox" id="grw_demand_assets" name="grw_demand_assets" value="true" <?php checked('true', $grw_demand_assets); ?>>
                                    Load static assets (JS/CSS) only on pages where reviews are showing
                                </label>
                                <div style="padding-top:15px">
                                    <input type="submit" value="Save" name="save" class="button" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="grw-google" class="tab-content" style="display:<?php echo $tab == 'google' ? 'block' : 'none'?>;">
                    <h3>Google</h3>
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php?action=grw_settings_save&grw_tab=google')); ?>">
                        <?php wp_nonce_field('grw-wpnonce_save', 'grw-form_nonce_save'); ?>
                        <div class="grw-field">
                            <div class="grw-field-label">
                                <label>Google Places API key</label>
                            </div>
                            <div class="wp-review-field-option">
                                <input type="text" id="grw_google_api_key" name="grw_google_api_key" class="regular-text" value="<?php echo esc_attr($grw_google_api_key); ?>">
                                <?php if (!$grw_google_api_key && time() - $grw_activation_time > 60 * 60 * 48) { ?>
                                <div class="grw-warn">Your Google API key is not set for this reason, reviews are not automatically updated daily.<br>Please create your own Google API key and save here.</div>
                                <?php } ?>
                                <p>API key is mandatory to make the reviews automatically updated.</p>
                                <p>If you do not know how to create it, please read: <a href="<?php echo admin_url('admin.php?page=grw-support&grw_tab=fig'); ?>" target="_blank">Full Installation Guide</a></p>
                                <div style="padding-top:15px">
                                    <input type="submit" value="Save" name="save" class="button" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="grw-advance" class="tab-content" style="display:<?php echo $tab == 'advance' ? 'block' : 'none'?>;">
                    <h3>Advance</h3>
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php?action=grw_settings_save&grw_tab=advance')); ?>">

                        <div class="grw-field">
                            <div class="grw-field-label">
                                <label>Reviews update daily schedule is <b><?php echo $grw_revupd_cron ? 'enabled' : 'disabled' ?></b></label>
                            </div>
                            <div class="wp-review-field-option">
                                <?php wp_nonce_field('grw-wpnonce_revupd_cron', 'grw-form_nonce_revupd_cron'); ?>
                                <input type="submit" value="<?php echo $grw_revupd_cron ? 'Disable' : 'Enable'; ?>" name="revupd_cron" class="button" />
                            </div>
                        </div>

                        <div class="grw-field">
                            <div class="grw-field-label">
                                <label>Re-create the database tables of the plugin (service option)</label>
                            </div>
                            <div class="wp-review-field-option">
                                <?php wp_nonce_field('grw-wpnonce_create_db', 'grw-form_nonce_create_db'); ?>
                                <input type="submit" value="Re-create Database" name="create_db" onclick="return confirm('Are you sure you want to re-create database tables?')" class="button" />
                            </div>
                        </div>
                        <div class="grw-field">
                            <div class="grw-field-label">
                                <label><b>Please be careful</b>: this removes all settings, reviews, feeds and install the plugin from scratch</label>
                            </div>
                            <div class="wp-review-field-option">
                                <?php wp_nonce_field('grw-wpnonce_create_db', 'grw-form_nonce_create_db'); ?>
                                <input type="submit" value="Install from scratch" name="install" onclick="return confirm('It will delete all current feeds, are you sure you want to install from scratch the plugin?')" class="button" />
                                <p><label><input type="checkbox" id="install_multisite" name="install_multisite"> For all sites (WP Multisite)</label></p>
                            </div>
                        </div>
                        <div class="grw-field">
                            <div class="grw-field-label">
                                <label><b>Please be careful</b>: this removes all plugin-specific settings, reviews and feeds</label>
                            </div>
                            <div class="wp-review-field-option">
                                <?php wp_nonce_field('grw-wpnonce_reset_all', 'grw-form_nonce_reset_all'); ?>
                                <input type="submit" value="Delete All Data" name="reset_all" onclick="return confirm('Are you sure you want to reset all plugin data including feeds?')" class="button" />
                                <p><label><input type="checkbox" id="reset_all_multisite" name="reset_all_multisite"> For all sites (WP Multisite)</label></p>
                            </div>
                        </div>
                        <div id="debug_info" class="grw-field">
                            <div class="grw-field-label">
                                <label>Debug information</label>
                            </div>
                            <div class="wp-review-field-option">
                                <input type="button" value="Copy Debug Information" name="reset_all" onclick="window.grw_debug_info.select();document.execCommand('copy');window.grw_debug_msg.innerHTML='Debug Information copied, please paste it to your email to support';" class="button" />
                                <textarea id="grw_debug_info" style="display:block;width:30em;height:250px;margin-top:10px" onclick="window.grw_debug_info.select();document.execCommand('copy');window.grw_debug_msg.innerHTML='Debug Information copied, please paste it to your email to support';" readonly><?php $this->debug_info->render(); ?></textarea>
                                <p id="grw_debug_msg"></p>
                            </div>
                        </div>
                        <div class="grw-field" style="display:none">
                            <div class="grw-field-label">
                                <label>Debug mode is currently <b><?php echo $grw_debug_mode ? 'enabled' : 'disabled' ?></b></label>
                            </div>
                            <div class="wp-review-field-option">
                                <?php wp_nonce_field('grw-wpnonce_debug_mode', 'grw-form_nonce_debug_mode'); ?>
                                <input type="submit" name="debug_mode" class="button" value="<?php echo $grw_debug_mode ? 'Disable' : 'Enable'; ?>" />
                            </div>
                        </div>
                        <div class="grw-field">
                            <div class="grw-field-label">
                                <label>Execute db update manually</label>
                            </div>
                            <div class="wp-review-field-option">
                                <?php wp_nonce_field('grw-wpnonce_update_db', 'grw-form_nonce_update_db'); ?>
                                <input type="submit" name="update_db" class="button" />
                                <input type="text" name="update_db_ver" style="width:94px;height:22px" placeholder="version" />
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>
        <?php
    }

}
