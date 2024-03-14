<?php
/*
  Plugin Name: Mythic Cerberus
  Description: Guards your login form and sends with failed logins to Hades.
  Version: 1.1.2
  Author: Mythic Beasts
  Author URI: https://www.mythic-beasts.com
  License: GNU General Public License v2.0
  Text Domain: mythic-cerberus
  Requires at least: 4.0
  Tested up to: 6.2.2
  Requires PHP: 5.2

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$mythiccerberus_db_version = "1.0";
$mythiccerberusOptions = mythicCerberus_get_options();

function mythicCerberus_install()
{
    global $wpdb;
    global $mythiccerberus_db_version;
    $table_name = $wpdb->prefix . "mythic_cerberus_login_fails";

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE " . $table_name . " (
			`login_attempt_ID` bigint(20) NOT NULL AUTO_INCREMENT,
			`user_id` bigint(20) NOT NULL,
			`login_attempt_date` datetime NOT NULL default '0000-00-00 00:00:00',
			`login_attempt_IP` varchar(100) NOT NULL default '',
			PRIMARY KEY (`login_attempt_ID`),
            INDEX `mclf_user_id` (`user_id`),
            INDEX `mclf_login_attempt_date` (`login_attempt_date`),
            INDEX `mclf_login_attempt_IP`  (`login_attempt_IP`)
			);";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    $table_name = $wpdb->prefix . "mythic_cerberus";

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE " . $table_name . " (
			`lockout_ID` bigint(20) NOT NULL AUTO_INCREMENT,
			`user_id` bigint(20) NOT NULL,
			`lockout_date` datetime NOT NULL default '0000-00-00 00:00:00',
			`release_date` datetime NOT NULL default '0000-00-00 00:00:00',
			`lockout_IP` varchar(100) NOT NULL default '',
			PRIMARY KEY  (`lockout_ID`),
            INDEX mc_user_id (`user_id`),
            INDEX mc_lockout_date (`lockout_date`),
            INDEX mc_release_date (`release_date`),
            INDEX mc_lockout_IP (`lockout_IP`)
			);";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    add_option("mythiccerberus_db_version", "1.0", "", "no");

}

function mythicCerberus_deactivate()
{
    global $wpdb;
    delete_option("mythiccerberus_db_version");
    delete_option("mythiccerberusAdminOptions");
    $result1 = $wpdb->get_results("DROP TABLE IF EXISTS `" . $wpdb->prefix . "mythic_cerberus` ;");
    $result2 = $wpdb->get_results("DROP TABLE IF EXISTS `" . $wpdb->prefix . "mythic_cerberus_login_fails` ;");
}
register_deactivation_hook( __FILE__, 'mythicCerberus_deactivate' );

function mythicCerberus_countFails($username = "")
{
    global $wpdb;
    global $mythiccerberusOptions;
    $table_name = $wpdb->prefix . "mythic_cerberus_login_fails";
    $subnet = mythicCerberus_calc_subnet($_SERVER['REMOTE_ADDR']);

    $numFails = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(login_attempt_ID) FROM " . $table_name . " WHERE login_attempt_date + INTERVAL %d MINUTE > %s AND login_attempt_IP LIKE %s",
            array($mythiccerberusOptions['retries_within'], current_time('mysql'), $subnet[1]  . "%")
        )
    );

    return $numFails;
}

function mythicCerberus_incrementFails($username = "")
{
    global $wpdb;
    global $mythiccerberusOptions;
    $table_name = $wpdb->prefix . "mythic_cerberus_login_fails";
    $subnet = mythicCerberus_calc_subnet($_SERVER['REMOTE_ADDR']);

    # record the failed login
    $username = sanitize_user($username);
    $user = get_user_by('login', $username);
    if ($user === false) {
        $user_id = -1;
    } else {
        $user_id = $user->ID;
    }

    $wpdb->insert(
        $table_name,
        array(
            'user_id' => $user_id,
            'login_attempt_date' => current_time('mysql'),
            'login_attempt_IP' => $subnet[0]
        )
    );

    # if we're blocking invalid users, then add a lockout
    if ($mythiccerberusOptions['lockout_invalid_usernames'] == "yes") {
        mythicCerberus_lockout($username);
    };

}

function mythicCerberus_lockout($username = "")
{
    global $wpdb;
    global $mythiccerberusOptions;
    $table_name = $wpdb->prefix . "mythic_cerberus";
    $subnet = mythicCerberus_calc_subnet($_SERVER['REMOTE_ADDR']);

    $username = sanitize_user($username);
    $user = get_user_by('login', $username);
    if ($user || "yes" == $mythiccerberusOptions['lockout_invalid_usernames']) {
        if ($user === false) {
            $user_id = -1;
        } else {
            $user_id = $user->ID;
        }

        $wpdb->insert(
            $table_name,
            array(
                'user_id' => $user_id,
                'lockout_date' => current_time('mysql'),
                'release_date' => date('Y-m-d H:i:s', strtotime(current_time('mysql')) + $mythiccerberusOptions['lockout_length'] * 60),
                'lockout_IP' => $subnet[0]
            )
        );
    }
}

function mythicCerberus_isLockedOut()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "mythic_cerberus";
    $subnet = mythicCerberus_calc_subnet($_SERVER['REMOTE_ADDR']);

    $stillLocked = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM " . $table_name . " WHERE release_date > %s AND lockout_IP LIKE %s", array(current_time('mysql'), $subnet[1] . "%")));

    return $stillLocked;
}

function mythicCerberus_listLockedOut()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "mythic_cerberus";

    $listLocked = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT lockout_ID, floor((UNIX_TIMESTAMP(release_date)-UNIX_TIMESTAMP(%s))/60) AS minutes_left, lockout_IP FROM $table_name WHERE release_date > %s",
            array(current_time('mysql'), current_time('mysql'))
        ),
    ARRAY_A);

    return $listLocked;
}

function mythicCerberus_get_options()
{
    $mythiccerberusAdminOptions = array(
        'max_login_retries' => 5,
        'retries_within' => 5,
        'lockout_length' => 10,
        'lockout_invalid_usernames' => 'no',
        'mask_login_errors' => 'no',
        'block_xmlrpc' => 'yes',
        'show_credit_link' => 'yes'
    );
    $mythiccerberusOptions = get_option("mythiccerberusAdminOptions");
    if (!empty($mythiccerberusOptions)) {
        foreach ($mythiccerberusOptions as $key => $option) {
            $mythiccerberusAdminOptions[$key] = $option;
        }
    }
    update_option("mythiccerberusAdminOptions", $mythiccerberusAdminOptions);
    return $mythiccerberusAdminOptions;
}

function mythicCerberus_calc_subnet($ip)
{
    $subnet[0] = $ip;
    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
        $ip = mythicCerberus_expandipv6($ip);
        preg_match("/^([0-9abcdef]{1,4}:){4}/", $ip, $matches);
        $subnet[0] = $ip;
        $subnet[1] = $matches[0];
    } else {
        $subnet[1] = substr($ip, 0, strrpos($ip, ".") + 1);
    }
    return $subnet;
}

function mythicCerberus_expandipv6($ip)
{
    $hex = unpack("H*hex", inet_pton($ip));
    $ip = substr(preg_replace("/([A-f0-9]{4})/", "$1:", $hex['hex']), 0, -1);

    return $ip;
}


function mythicCerberus_print_admin_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "mythic_cerberus";
    $mythiccerberusAdminOptions = mythicCerberus_get_options();

    if (isset($_POST['update_mythiccerberusSettings'])) {

        //wp_nonce check
        check_admin_referer('mythic-cerberus_update-options');

        if (isset($_POST['ll_max_login_retries'])) {
            $mythiccerberusAdminOptions['max_login_retries'] = sanitize_text_field($_POST['ll_max_login_retries']);
        }
        if (isset($_POST['ll_retries_within'])) {
            $mythiccerberusAdminOptions['retries_within'] = sanitize_text_field($_POST['ll_retries_within']);
        }
        if (isset($_POST['ll_lockout_length'])) {
            $mythiccerberusAdminOptions['lockout_length'] = sanitize_text_field($_POST['ll_lockout_length']);
        }
        if (isset($_POST['ll_lockout_invalid_usernames'])) {
            $mythiccerberusAdminOptions['lockout_invalid_usernames'] = sanitize_text_field($_POST['ll_lockout_invalid_usernames']);
        }
        if (isset($_POST['ll_mask_login_errors'])) {
            $mythiccerberusAdminOptions['mask_login_errors'] = sanitize_text_field($_POST['ll_mask_login_errors']);
        }
        if (isset($_POST['ll_block_xmlrpc'])) {
            $mythiccerberusAdminOptions['block_xmlrpc'] = sanitize_text_field($_POST['ll_block_xmlrpc']);
        }
        if (isset($_POST['ll_show_credit_link'])) {
            $mythiccerberusAdminOptions['show_credit_link'] = sanitize_text_field($_POST['ll_show_credit_link']);
        }
        update_option("mythiccerberusAdminOptions", $mythiccerberusAdminOptions);
        ?>
        <div class="updated">
            <p><strong><?php esc_html_e("Settings Updated.", "mythiccerberus"); ?></strong></p>
        </div>
    <?php
    }
    if (isset($_POST['release_lockouts'])) {

        //wp_nonce check
        check_admin_referer('mythic-cerberus_release-lockouts');

        if (isset($_POST['releaseme'])) {
            $released = array_map( 'intval', $_POST['releaseme'] );

            foreach ($released as $release_id) {
                $wpdb->query(
                    $wpdb->prepare("UPDATE $table_name SET release_date = %s WHERE lockout_ID = %d", array(current_time('mysql'), $release_id))
                );
            }
        }
        update_option("mythiccerberusAdminOptions", $mythiccerberusAdminOptions);
    ?>
        <div class="updated">
            <p><strong><?php esc_html_e("Lockouts Released.", "mythiccerberus"); ?></strong></p>
        </div>
    <?php
    }
    $dalist = mythicCerberus_listLockedOut();
    ?>
    <div class="wrap">
        <?php

        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'settings';

        ?>
        <h2><?php esc_html_e('Mythic Cerberus Options', 'mythiccerberus') ?></h2>

        <h2 class="nav-tab-wrapper">
            <a href="?page=mythic-cerberus.php&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Settings', 'mythiccerberus') ?></a>
            <a href="?page=mythic-cerberus.php&tab=activity" class="nav-tab <?php echo $active_tab == 'activity' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Activity', 'mythiccerberus') ?> (<?php echo count($dalist); ?>)</a>
        </h2>
        <?php if ($active_tab == 'settings') { ?>
            <form method="post" action="<?php echo esc_attr($_SERVER["REQUEST_URI"]); ?>">
                <?php
                if (function_exists('wp_nonce_field'))
                    wp_nonce_field('mythic-cerberus_update-options');
                ?>

                <h3><?php esc_html_e('Max Login Retries', 'mythiccerberus') ?></h3>
                <p><?php esc_html_e('Number of failed login attempts within the "Retry Time Period Restriction" (defined below) needed to trigger a lock out.', 'mythiccerberus') ?></p>
                <p><input type="text" name="ll_max_login_retries" size="8" value="<?php echo esc_attr($mythiccerberusAdminOptions['max_login_retries']); ?>"></p>

                <h3><?php esc_html_e('Retry Time Period Restriction', 'mythiccerberus') ?></h3>
                <p><?php esc_html_e('Amount of time that determines the rate at which failed login attempts are allowed before a lock out occurs.', 'mythiccerberus') ?></p>
                <p><input type="text" name="ll_retries_within" size="8" value="<?php echo esc_attr($mythiccerberusAdminOptions['retries_within']); ?>"> minutes</p>

                <h3><?php esc_html_e('Lockout Length', 'mythiccerberus') ?></h3>
                <p><?php esc_html_e('How long a particular IP block will be locked out for once a lock out has been triggered.', 'mythiccerberus') ?></p>
                <p><input type="text" name="ll_lockout_length" size="8" value="<?php echo esc_attr($mythiccerberusAdminOptions['lockout_length']); ?>"> minutes</p>

                <h3><?php esc_html_e('Immediately Lockout Invalid Usernames?', 'mythiccerberus') ?></h3>
                <p><?php esc_html_e('By default Cerberus will treat usernames that do not exist normally. Enable this option to immediately block these IPs.', 'mythiccerberus') ?></p>
                <p><input type="radio" name="ll_lockout_invalid_usernames" value="no" <?php if ($mythiccerberusAdminOptions['lockout_invalid_usernames'] == "no") echo "checked"; ?>>&nbsp;<?php esc_html_e('No', 'mythiccerberus') ?>&nbsp;&nbsp;&nbsp;<input type="radio" name="ll_lockout_invalid_usernames" value="yes" <?php if ($mythiccerberusAdminOptions['lockout_invalid_usernames'] == "yes") echo "checked"; ?>>&nbsp;<?php esc_html_e('Yes', 'mythiccerberus') ?></p>

                <h3><?php esc_html_e('Mask Login Errors?', 'mythiccerberus') ?></h3>
                <p><?php esc_html_e('WordPress will normally display distinct messages to the user depending on whether they try and log in with an invalid username, or with a valid username but the incorrect password. Toggling this option will hide why the login failed.', 'mythiccerberus') ?></p>
                <p><input type="radio" name="ll_mask_login_errors" value="no" <?php if ($mythiccerberusAdminOptions['mask_login_errors'] == "no") echo "checked"; ?>>&nbsp;<?php esc_html_e('No', 'mythiccerberus') ?>&nbsp;&nbsp;&nbsp;<input type="radio" name="ll_mask_login_errors" value="yes" <?php if ($mythiccerberusAdminOptions['mask_login_errors'] == "yes") echo "checked"; ?>>&nbsp;<?php esc_html_e('Yes', 'mythiccerberus') ?></p>
                    

                <h3><?php esc_html_e('Disable WordPress XML-RPC?', 'mythiccerberus') ?></h3>
                <p><?php esc_html_e('The WordPress XML-RPC functions are a common target for abuse, as it allows you to query various information about the site. By default, Cerberus disables it, access but it can be enabled here.', 'mythiccerberus') ?></p>
                <p><input type="radio" name="ll_block_xmlrpc" value="yes" <?php if ($mythiccerberusAdminOptions['block_xmlrpc'] == "yes") echo "checked"; ?>>&nbsp;<?php esc_html_e('Yes', 'mythiccerberus') ?>&nbsp;&nbsp;&nbsp;<input type="radio" name="ll_block_xmlrpc" value="no" <?php if ($mythiccerberusAdminOptions['block_xmlrpc'] == "no") echo "checked"; ?>>&nbsp;<?php esc_html_e('No', 'mythiccerberus') ?></p>

                <h3><?php esc_html_e('Show Credit Link?', 'mythiccerberus') ?></h3>
                <p><?php esc_html_e('If enabled, Cerberus will display the following message on the login form', 'mythiccerberus') ?>:<br />
                <blockquote><?php esc_html_e('Login form protected by', 'mythiccerberus') ?> <a href='https://www.mythic-beasts.com/'>Mythic Cerberus</a>.</blockquote>
                <?php esc_html_e('You can enable or disable this message below', 'mythiccerberus') ?>:</p>
                <input type="radio" name="ll_show_credit_link" value="yes" <?php if ($mythiccerberusAdminOptions['show_credit_link'] == "yes" || $mythiccerberusAdminOptions['show_credit_link'] == "") echo "checked"; ?>>&nbsp;<?php esc_html_e('Yes, display the credit link.', 'mythiccerberus') ?><br />
                <input type="radio" name="ll_show_credit_link" value="shownofollow" <?php if ($mythiccerberusAdminOptions['show_credit_link'] == "shownofollow") echo "checked"; ?>>&nbsp;<?php esc_html_e('Display the credit link, but add "rel=\'nofollow\'" (ie. do not pass any links).', 'mythiccerberus') ?><br />
                <input type="radio" name="ll_show_credit_link" value="no" <?php if ($mythiccerberusAdminOptions['show_credit_link'] == "no") echo "checked"; ?>>&nbsp;<?php esc_html_e('No, do not display the credit link.', 'mythiccerberus') ?><br />

                <div class="submit">
                    <input type="submit" class="button button-primary" name="update_mythiccerberusSettings" value="<?php esc_html_e('Update Settings', 'mythiccerberus') ?>" />
                </div>
            </form>
        <?php } else { ?>
            <form method="post" action="<?php echo esc_attr($_SERVER["REQUEST_URI"]); ?>">
                <?php
                if (function_exists('wp_nonce_field'))
                    wp_nonce_field('mythic-cerberus_release-lockouts');
                ?>
                <h3><?php
                    if (count($dalist) == 1) {
                        printf(esc_html__('There is currently %d locked out IP address.', 'mythiccerberus'), count($dalist));
                    } else {
                        printf(esc_html__('There are currently %d locked out IP addresses.', 'mythiccerberus'), count($dalist));
                    } ?></h3>

                <?php
                $num_lockedout = count($dalist);
                if (0 == $num_lockedout) {
                    echo "<p>No IP blocks currently locked out.</p>";
                } else {
                    foreach ($dalist as $key => $option) {
                ?>
                        <li><input type="checkbox" name="releaseme[]" value="<?php echo esc_attr($option['lockout_ID']); ?>"> <?php echo esc_attr($option['lockout_IP']); ?> (<?php echo esc_attr($option['minutes_left']); ?> <?php esc_html_e('minutes left', 'mythiccerberus') ?>)</li>
                <?php
                    }
                }
                ?>
                <div class="submit">
                    <input type="submit" class="button button-primary" name="release_lockouts" value="<?php esc_html_e('Release Selected', 'mythiccerberus') ?>" />
                </div>
            </form>
        <?php } ?>
    </div>
<?php
} //End function mythicCerberus_print_admin_page()

function mythicCerberus_ap()
{
    if (function_exists('add_options_page')) {
        add_options_page('Mythic Cerberus', 'Mythic Cerberus', 'manage_options', basename(__FILE__), 'mythicCerberus_print_admin_page');
    }
}

function mythicCerberus_credit_link()
{
    global $mythiccerberusOptions;
    $thispage = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $homepage = get_option("home");
    $showcreditlink = $mythiccerberusOptions['show_credit_link'];
    $relnofollow = true;
    if ($showcreditlink != "shownofollow" && ($thispage == $homepage || $thispage == $homepage . "/" || substr($_SERVER["REQUEST_URI"], strlen($_SERVER["REQUEST_URI"]) - 12) == "wp-login.php")) {
        $relnofollow = false;
    }
    if ($showcreditlink != "no") {
        echo "<p>";
        esc_html_e('Login form protected by', 'mythiccerberus');
        echo " <a href='" . esc_url('https://www.mythic-beasts.com/') . "' " . ($relnofollow ? "rel='nofollow'" : "") . ">Mythic Cerberus</a>.<br /><br /><br /></p>";
    }
}

//Actions and Filters
if (isset($mythiccerberus_db_version)) {
    //Actions
    add_action('admin_menu', 'mythicCerberus_ap');
    register_activation_hook(__FILE__, 'mythicCerberus_install');
    add_action('login_form', 'mythicCerberus_credit_link');
    
    remove_filter('authenticate', 'wp_authenticate_username_password', 20, 3);
    add_filter('authenticate', 'mythicCerberus_wp_authenticate_username_password', 20, 3);

    //Filters
    if ('yes' == $mythiccerberusOptions['block_xmlrpc']) {
        add_filter('xmlrpc_enabled','__return_false');
    }

    //Functions
    function mythicCerberus_wp_authenticate_username_password($user, $username, $password)
    {
        if (is_a($user, 'WP_User')) {
            return $user;
        }

        if (empty($username) || empty($password)) {
            $error = new WP_Error();

            if (empty($username))
                $error->add('empty_username', __('<strong>ERROR</strong>: The username field is empty.', 'mythiccerberus'));

            if (empty($password))
                $error->add('empty_password', __('<strong>ERROR</strong>: The password field is empty.', 'mythiccerberus'));

            return $error;
        }

        $userdata = get_user_by('login', $username);

        if (!$userdata) {
            return new WP_Error('invalid_username', sprintf(__('<strong>ERROR</strong>: Invalid username. <a href="%s" title="Password Lost and Found">Lost your password</a>?', 'mythiccerberus'), site_url('wp-login.php?action=lostpassword', 'login')));
        }

        $userdata = apply_filters('wp_authenticate_user', $userdata, $password);
        if (is_wp_error($userdata)) {
            return $userdata;
        }

        if (!wp_check_password($password, $userdata->user_pass, $userdata->ID)) {
            return new WP_Error('incorrect_password', sprintf(__('<strong>ERROR</strong>: Incorrect password. <a href="%s" title="Password Lost and Found">Lost your password</a>?', 'mythiccerberus'), site_url('wp-login.php?action=lostpassword', 'login')));
        }

        $user =  new WP_User($userdata->ID);
        return $user;
    }


    if (!function_exists('wp_authenticate')) {
        function wp_authenticate($username, $password)
        {
            global $wpdb, $error;
            global $mythiccerberusOptions;

            $username = sanitize_user($username);
            $password = trim($password);

            if ("" != mythicCerberus_isLockedOut()) {
                return new WP_Error('incorrect_password', __("<strong>ERROR</strong>: Sorry, this IP range has been blocked due to too many recent failed login attempts.<br /><br />Please try again later.", 'mythiccerberus'));
            }

            $user = apply_filters('authenticate', null, $username, $password);

            if ($user == null) {
                // TODO what should the error message be? (Or would these even happen?)
                // Only needed if all authentication handlers fail to return anything.
                $user = new WP_Error('authentication_failed', __('<strong>ERROR</strong>: Invalid username or incorrect password.', 'mythiccerberus'));
            }

            $ignore_codes = array('empty_username', 'empty_password');

            if (is_wp_error($user) && !in_array($user->get_error_code(), $ignore_codes)) {
                mythicCerberus_incrementFails($username);

                if ($mythiccerberusOptions['max_login_retries'] <= mythicCerberus_countFails($username)) {
                    mythicCerberus_lockout($username);
                    return new WP_Error('incorrect_password', __("<strong>ERROR</strong>: We're sorry, but this IP range has been blocked due to too many recent failed login attempts.<br /><br />Please try again later.", 'mythiccerberus'));
                }
                if ('yes' == $mythiccerberusOptions['mask_login_errors']) {
                    return new WP_Error('authentication_failed', sprintf(__('<strong>ERROR</strong>: Invalid username or incorrect password. <a href="%s" title="Password Lost and Found">Lost your password</a>?', 'mythiccerberus'), site_url('wp-login.php?action=lostpassword', 'login')));
                } else {
                    do_action('wp_login_failed', $username);
                }
            }

            return $user;
        }
    }

    // multisite network-wide activation
    register_activation_hook(__FILE__, 'mythicCerberus_multisite_activate');
    function mythicCerberus_multisite_activate($networkwide)
    {
        global $wpdb;

        if (function_exists('is_multisite') && is_multisite()) {
            // check if it is a network activation - if so, run the activation function for each blog id
            if ($networkwide) {
                $old_blog = $wpdb->blogid;
                // Get all blog ids
                $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
                foreach ($blogids as $blog_id) {
                    switch_to_blog($blog_id);
                    mythicCerberus_install();
                }
                switch_to_blog($old_blog);
                return;
            }
        }
    }

    // multisite activation
    add_action('wpmu_new_blog', 'mythicCerberus_multisite_newsite', 10, 6);
    function mythicCerberus_multisite_newsite($blog_id, $user_id, $domain, $path, $site_id, $meta)
    {
        global $wpdb;

        if (is_plugin_active_for_network('mythic-cerberus/mythic-cerberus.php')) {
            $old_blog = $wpdb->blogid;
            switch_to_blog($blog_id);
            mythicCerberus_install();
            switch_to_blog($old_blog);
        }
    }

}

add_action('plugins_loaded', 'mythicCerberus_init', 10);

function mythicCerberus_init()
{
    load_plugin_textdomain('mythiccerberus', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
