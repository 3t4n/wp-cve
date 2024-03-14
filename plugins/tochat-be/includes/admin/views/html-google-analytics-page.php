<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap">
    <h1>Google Event Analytics Settings</h1>
    <?php settings_errors(); ?>
    <hr>
    <form action="options.php" method="post" class="tochatbe-setting-table">
        <?php settings_fields( 'tochatbe-google-analytics-settings' ); ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="">Google Event Analytics</label>
                    </th>
                    <td>
                        <input type="checkbox" name="tochatbe_google_analytics_settings[status]" <?php checked( 'yes', tochatbe_google_analytics_option( 'status' ) ); ?> > Enable/ Disable
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="">Event Category</label>
                    </th>
                    <td>
                        <input type="text" name="tochatbe_google_analytics_settings[category]" class="regular-text" value="<?php echo esc_attr( tochatbe_google_analytics_option( 'category' ) ); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="">Event Action</label>
                    </th>
                    <td>
                        <input type="text" name="tochatbe_google_analytics_settings[action]" class="regular-text" value="<?php echo esc_attr( tochatbe_google_analytics_option( 'action' ) ); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="">Event Label</label>
                    </th>
                    <td>
                        <input type="text" name="tochatbe_google_analytics_settings[label]" class="regular-text" value="<?php echo esc_attr( tochatbe_google_analytics_option( 'label' ) ); ?>">
                    </td>
                </tr>
            </tbody>
        </table>
        
        <?php submit_button(); ?>
    </form>
</div>

<?php require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/notifications/html-purchase-premium.php'; ?>