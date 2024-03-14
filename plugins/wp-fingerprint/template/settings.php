<div class="wrap">
        <h2>WP Fingerprint Settings</h2>
        <form action="options.php" method="POST">
            <?php settings_fields( 'wp-fingerprint-settings' ); ?>
            <?php do_settings_sections( 'wp-fingerprint-settings' ); ?>
            <?php submit_button(); ?>
        </form>
</div>
