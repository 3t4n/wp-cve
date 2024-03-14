<?php defined( 'ABSPATH' ) || exit; ?>

<div class="wrap">
    <h1>Facebook Analytics Settings</h1>
    <?php settings_errors(); ?>
    <hr>
    <form action="options.php" method="post" class="tochatbe-setting-table">
        <?php settings_fields( 'tochatbe-facebook-analytics-settings' ); ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="">Facebook Analytics</label>
                    </th>
                    <td>
                        <input type="checkbox" name="tochatbe_facebook_analytics_settings[status]" <?php checked( 'yes', tochatbe_facebook_analytics_option( 'status' ) ); ?> > Enable/ Disable
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="">Facebook Event Name</label>
                    </th>
                    <td>
                        <input type="text" name="tochatbe_facebook_analytics_settings[name]" class="regular-text" value="<?php echo esc_attr( tochatbe_facebook_analytics_option( 'name' ) ); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="">Facebook Event Level</label>
                    </th>
                    <td>
                        <input type="text" name="tochatbe_facebook_analytics_settings[label]" class="regular-text" value="<?php echo esc_attr( tochatbe_facebook_analytics_option( 'label' ) ); ?>">
                    </td>
                </tr>
            </tbody>
        </table>
        
        <?php submit_button(); ?>
    </form>
</div>

<?php require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/notifications/html-purchase-premium.php'; ?>