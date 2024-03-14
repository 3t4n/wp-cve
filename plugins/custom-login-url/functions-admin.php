<?php

function clu_admin_init() {
    
    if(isset($_POST["clu_config"])) {
        clu_options_validate($_POST["clu_config"]);
    }
    
    add_settings_section('clu_permalinks', 'Authentication Permalinks', 'clu_permalinks_section', 'permalink');
    add_settings_field('clu_login_url', 'Login URL', 'clu_login_url_input', 'permalink', 'clu_permalinks');
    add_settings_field('clu_register_url', 'Registration URL', 'clu_register_url_input', 'permalink', 'clu_permalinks');
    add_settings_field('clu_lostpassword_url', 'Lost Password URL', 'clu_lostpassword_url_input', 'permalink', 'clu_permalinks');
    add_settings_field('clu_logout_url', 'Logout URL', 'clu_logout_url_input', 'permalink', 'clu_permalinks');
    
    add_settings_section('clu_redirects', 'Authentication Redirects', 'clu_redirects_section', 'permalink');
    add_settings_field('clu_login_redirect', 'Login Redirect URL', 'clu_login_redirect_input', 'permalink', 'clu_redirects');
    add_settings_field('clu_logout_redirect', 'Logout Redirect URL', 'clu_logout_redirect_input', 'permalink', 'clu_redirects');
}

function clu_options_validate($input) {

    $options = get_option('clu_config');
    
    if(!is_array($options)) {
        $options = array();
    }
    
    $params = array('login', 'register', 'lostpassword', 'logout',
        "redirect_login", "redirect_logout"
    );
    
    foreach($params as $action) {
        $value = trim($input[$action]);
        if(!empty($value)) {
            $options[$action] = "/".ltrim($value, "/");
        } else {
            $options[$action] = null;
        }
    }

    update_option("clu_config", $options);
}

function clu_permalinks_section() {

}

function clu_redirects_section() {

}

function clu_login_url_input() {
    $options = get_option('clu_config');
    ?>
        <code><?php esc_html_e(site_url()) ?></code>
        <input id='clu_login_url' name='clu_config[login]' size='40' type='text' value='<?php esc_attr_e($options["login"]) ?>' placeholder="/wp-login.php" />
    <?php
}

function clu_register_url_input() {
    $options = get_option('clu_config');
    ?>
        <code><?php esc_html_e(site_url()) ?></code>
        <input id='clu_register_url' name='clu_config[register]' size='40' type='text' value='<?php esc_attr_e($options["register"]) ?>' placeholder="/wp-login.php?action=register" />
    <?php
}

function clu_lostpassword_url_input() {
    $options = get_option('clu_config');
    ?>
        <code><?php esc_html_e(site_url()) ?></code>
        <input id='clu_lostpassword_url' name='clu_config[lostpassword]' size='40' type='text' value='<?php esc_attr_e($options["lostpassword"]) ?>' placeholder="/wp-login.php?action=lostpassword" />
    <?php
}

function clu_logout_url_input() {
    $options = get_option('clu_config');
    ?>
        <code><?php esc_html_e(site_url()) ?></code>
        <input id='clu_logout_url' name='clu_config[logout]' size='40' type='text' value='<?php esc_attr_e($options["logout"]) ?>' placeholder="/wp-login.php?action=logout" />
    <?php
}

function clu_login_redirect_input() {
    $options = get_option('clu_config');
    ?>
        <code><?php esc_html_e(site_url()) ?></code>
        <input id='clu_login_redirect' name='clu_config[redirect_login]' size='40' type='text' value='<?php esc_attr_e($options["redirect_login"]) ?>' placeholder="/wp-admin/" />
    <?php
}

function clu_logout_redirect_input() {
    $options = get_option('clu_config');
    ?>
        <code><?php esc_html_e(site_url()) ?></code>
        <input id='clu_logout_redirect' name='clu_config[redirect_logout]' size='40' type='text' value='<?php esc_attr_e($options["redirect_logout"]) ?>' placeholder="/" />
    <?php
}
