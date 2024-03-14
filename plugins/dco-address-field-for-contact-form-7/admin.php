<?php
defined('ABSPATH') or die;

add_action('admin_init', 'dco_af_cf7_register_settings');

function dco_af_cf7_register_settings() {
    register_setting('dco_af_cf7', 'dco_af_cf7');

    add_settings_section(
            'dco_af_cf7_google', __('Google'), '', 'dco_af_cf7'
    );

    add_settings_field(
            'google_maps_api_key', esc_html__('Google Maps API Key', 'dco-address-field-for-contact-form-7'), 'dco_af_cf7_google_maps_api_key_render', 'dco_af_cf7', 'dco_af_cf7_google'
    );

    add_settings_field(
            'load_google_maps_api', esc_html__('Load Google Maps API', 'dco-address-field-for-contact-form-7'), 'dco_af_cf7_load_google_maps_api_render', 'dco_af_cf7', 'dco_af_cf7_google'
    );

    add_settings_section(
            'dco_af_cf7_yandex', __('Yandex'), '', 'dco_af_cf7'
    );

    add_settings_field(
            'load_yandex_maps_api', esc_html__('Load Yandex Maps API', 'dco-address-field-for-contact-form-7'), 'dco_af_cf7_load_yandex_maps_api_render', 'dco_af_cf7', 'dco_af_cf7_yandex'
    );
}

function dco_af_cf7_load_yandex_maps_api_render() {
    $options = dco_af_cf7_get_options();
    ?>
    <input type="hidden" name="dco_af_cf7[load_yandex_maps_api]" value="0">
    <input type="checkbox" name="dco_af_cf7[load_yandex_maps_api]" <?php checked($options['load_yandex_maps_api'], 1); ?> value="1">
    <p class="description"><?php esc_html_e('Disable this if your theme or plugins are already load Yandex Maps API v2.1.'); ?></p>
    <?php
}

function dco_af_cf7_load_google_maps_api_render() {
    $options = dco_af_cf7_get_options();
    ?>
    <input type="hidden" name="dco_af_cf7[load_google_maps_api]" value="0">
    <input type="checkbox" name="dco_af_cf7[load_google_maps_api]" <?php checked($options['load_google_maps_api'], 1); ?> value="1">
    <p class="description"><?php esc_html_e('Disable this if your theme or plugins are already load Google Maps API'); ?></p>
    <?php
}

function dco_af_cf7_google_maps_api_key_render() {
    $options = dco_af_cf7_get_options();
    ?>
    <input type="text" name="dco_af_cf7[google_maps_api_key]" value="<?php echo esc_attr($options['google_maps_api_key']); ?>">
    <p class="description"><?php _e('You can get it <a href="https://developers.google.com/maps/documentation/embed/get-api-key">here</a>.'); ?></p>
    <?php
}

add_action('admin_menu', 'dco_af_cf7_create_menu');

function dco_af_cf7_create_menu() {
    add_options_page(__('DCO Address Field for Contact Form 7', 'dco-address-field-for-contact-form-7'), esc_html__('DCO Address Field for Contact Form 7', 'dco-address-field-for-contact-form-7'), 'manage_options', 'dco-address-field-for-contact-form-7', 'dco_af_cf7_render');
}

function dco_af_cf7_render() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('DCO Address Field for Contact Form 7', 'dco-address-field-for-contact-form-7'); ?></h1>
        <form action="options.php" method="post">
            <?php settings_fields('dco_af_cf7'); ?>
            <?php do_settings_sections('dco_af_cf7'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}