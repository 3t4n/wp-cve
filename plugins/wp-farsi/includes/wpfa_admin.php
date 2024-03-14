<?php 
/*
    wp-farsi admin inc
 */
function wpfa_load_first() {
    $plugins = get_option('active_plugins');
    $path = plugin_basename( WPFA_FILE );
    if (is_array($plugins) and $plugins[0] !== $path) {
        $key = array_search($path, $plugins);
        array_splice($plugins, $key, 1);
        array_unshift($plugins, $path);
        update_option('active_plugins', $plugins);
    }
}

function wpfa_nums_field() {
    register_setting('general', 'wpfa_nums', 'esc_attr');
    add_settings_field('wpfa_nums', '<label for="wpfa_nums">ساختار اعداد</label>', create_function('', '
        echo \'<label><input type="checkbox" name="wpfa_nums" ' . (WPFA_NUMS === "on" ? "checked" : "") . '/> 
        <span>فارسی ۰۱۲۳۴۵۶۷۸۹</span></label>\';'), 'general');
}

function wpfa_js_css() {
    wp_deregister_script('ztjalali_reg_admin_js');
    wp_deregister_script('ztjalali_reg_date_js');
    wp_deregister_script('wpp_admin');
    wp_deregister_style('wp-parsi-fonts');
    wp_deregister_style('wp-parsi-admin');
    wp_enqueue_script( 'wpfajs', plugin_dir_url(WPFA_FILE) . 'assets/wpfajs.js', array('jquery'));
    wp_enqueue_style( 'wpfa-font', plugin_dir_url(WPFA_FILE) . 'assets/wpfa-font.css');
}

add_action('admin_enqueue_scripts', 'wpfa_js_css', 900);
