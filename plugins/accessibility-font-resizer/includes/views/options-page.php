<div class="wrap">
    <div class="alignright">
        <a target="_blank" href="<?php _e( 'https://www.satellitewp.com/en', 'swp-font-resizer' ); ?>">
            <img src="<?php echo $this->plugin_dir_url; ?>/images/satellitewp.png" alt="SatelliteWP" style="height:38px; width:202px">
        </a>
    </div>
    <h1><?php esc_html_e( 'Accessibility Font Resizer', 'swp-font-resizer' ); ?></h1>
    <p>
        <?php esc_html_e( 'Manage your font resizing settings to enable a better accessibility for your visitors.', 'swp-font-resizer' ); ?>
    </p>
    <div class="notice notice-info">
        <p>
            <?php esc_html_e( 'You like this plugin?', 'swp-font-resizer' ); ?>
            <a href="https://wordpress.org/support/plugin/accessibility-font-resizer/reviews/" target="_blank">
                <?php esc_html_e( 'Help us by giving it 5 stars', 'swp-font-resizer' ); ?>
            </a>.
        </p>
        <p>
            <?php esc_html_e( 'Not totally happy or need support?', 'swp-font-resizer' ); ?>
            <a href="https://wordpress.org/support/plugin/accessibility-font-resizer/" target="_blank">
                <?php esc_html_e( 'Give us your thoughts in the forum', 'swp-font-resizer' ); ?>
            </a>.
        </p>
        <p>
            <a target="_blank" href="<?php _e( 'https://www.satellitewp.com/en', 'swp-font-resizer' ); ?>">
                <?php _e( 'Need WordPress experts?', 'swp-font-resizer' ); ?>
            </a>
        </p>
    </div>

    <form method="post" action="options.php">
        <?php
        settings_fields( 'swp_font_resizer' );
        do_settings_sections( 'swp_font_resizer' );
        submit_button();
        ?>
    </form>
</div>