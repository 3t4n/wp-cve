<?php

/**
 * WP_Meteor
 *
 * @package   WP_Meteor
 * @author    Aleksandr Guidrevitch <alex@excitingstartup.com>
 * @copyright 2020 wp-meteor.com
 * @license   GPL 2.0+
 * @link      https://wp-meteor.com
 */
?>

<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    <form id="settings" method="post">
        <input type="hidden" name="wpmeteor_action" value="save_settings" />
        <?php wp_nonce_field('wpmeteor_save_settings_nonce', 'wpmeteor_save_settings_nonce'); ?>

        <div id="tabs" class="settings-tab">
            <ul>
                <li><a href="#settings" class="tab-handle"><?php esc_html_e('Settings', WPMETEOR_TEXTDOMAIN); ?></a></li>
                <li><a href="#exclusions" class="tab-handle"><?php esc_html_e('Exclusions', WPMETEOR_TEXTDOMAIN); ?></a></li>
                <li><a href="#elementor" class="tab-handle"><?php esc_html_e('Elementor', WPMETEOR_TEXTDOMAIN); ?></a></li>
                <li><a href="#advanced" class="tab-handle"><?php esc_html_e('Advanced', WPMETEOR_TEXTDOMAIN); ?></a></li>
            </ul>
            <div id="settings" class="tab">
                <?php do_action(WPMETEOR_TEXTDOMAIN . '-backend-display-settings-ultimate'); ?>
                <div className="field">
                    <input type="submit" name="submit" id="submit" class="button" value="Save Changes" />
                </div>
            </div>
            <div id="exclusions" class="tab">
                <?php do_action(WPMETEOR_TEXTDOMAIN . '-backend-display-settings-exclusions'); ?>
                <div className="field">
                    <input type="submit" name="submit" id="submit" class="button" value="Save Changes" />
                </div>
            </div>
            <div id="elementor" class="tab">
                <?php do_action(WPMETEOR_TEXTDOMAIN . '-backend-display-settings-elementor'); ?>
                <div className="field">
                    <input type="submit" name="submit" id="submit" class="button" value="Save Changes" />
                </div>
            </div>
            <div id="advanced" class="tab">
                <ul>
                    <li><input type="checkbox" name="enable-cdn" label="Global CDN serving" disabled /><label for="enable-cdn">Global CDN serving</label></li>
                    <li><input type="checkbox" name="enable-image-optimization" label="Image optimization" disabled /><label>Image optimization</label></li>
                    <li><input type="checkbox" name="enable-cdn" label="CSS/ JS minification" disabled /><label>CSS/ JS minification</label></li>
                    <li><input type="checkbox" name="enable-cdn" label="WebP/AVIF optimization on the fly" disabled /><label>WebP/AVIF optimization on the fly</label></li>
                    <li><input type="checkbox" name="enable-cache" label="SmartCache Accelerator" disabled /><label for="enable-cache">Smart Cache Accelerator</label></li>
                </ul>
                <div className="field">
                    All these options and more are available in <strong>FastPixel Accelerator plugin</strong> - a freemium, commercial grade plugin spawned from WP Meteor. <a href="https://fastpixel.io/?utm_source=wpmeteor-tab-advanced">Try it for free.</a>
                </div>
            </div>
        </div>
    </form>
</div>