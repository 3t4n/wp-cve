<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap">
    <h1>GDPR Settings</h1>
    <?php settings_errors(); ?>
    <hr>
    <form action="options.php" method="post" class="tochatbe-setting-table">
        <?php settings_fields( 'tochatbe-gdpr-settings' ); ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="">GDPR Status</label>
                    </th>
                    <td>
                        <input type="checkbox" name="tochatbe_gdpr_settings[status]" <?php checked( 'yes', tochatbe_gdpr_option( 'status' ), true); ?>> Enable/ Disable
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="">GDPR Message</label>
                    </th>
                    <td>
                        <textarea name="tochatbe_gdpr_settings[message]" class="regular-text" style="height: 120px;"><?php echo esc_textarea( tochatbe_gdpr_option( 'message' ) ); ?></textarea>
                        <p class="description">Use shortcode {policy_page} to add privacy page link.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="">Privacy Page Link</label>
                    </th>
                    <td>
                        <?php
                            $args = array(
                                'name'      => 'tochatbe_gdpr_settings[privacy_page]',
                                'selected'  => tochatbe_gdpr_option( 'privacy_page' ),
                            );
                            wp_dropdown_pages( $args );
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button(); ?>
    </form>
</div>

<?php require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/notifications/html-purchase-premium.php'; ?>