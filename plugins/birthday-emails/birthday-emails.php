<?php
/*
Plugin Name: Birthday Emails
Description: Automatically send an email to WordPress or BuddyPress users on their birthday.
Text Domain: birthday-emails
Domain Path: /languages/
Version: 1.2.3
Author: Carman Lawrick
License: GPL2
*/
// requires 4.5
if (!defined ( "CJL_BDEMAILS_EMAILS_TEST" ))define("CJL_BDEMAILS_EMAILS_TEST", false);
if (!defined ( "CJL_BDEMAILS_EMAILS_SLEEP" ))define("CJL_BDEMAILS_EMAILS_SLEEP", 1);
if (!defined ( "CJL_BDEMAILS_EMAILS_PER_HOUR" ))define("CJL_BDEMAILS_EMAILS_PER_HOUR", 20);
if (!defined ( "CJL_BDEMAILS_DEFAULT_START_HOUR" ))define("CJL_BDEMAILS_DEFAULT_START_HOUR", '8');
if (!defined ( "CJL_BDEMAILS_DEFAULT_START_HOUR_N" ))define("CJL_BDEMAILS_DEFAULT_START_HOUR_N", (int)CJL_BDEMAILS_DEFAULT_START_HOUR);
global $cjl_bdemail_checking_by_button;
$cjl_bdemail_checking_by_button = false;
/***************************************************************************************
 * This function, used for debugging, writes a messsage to wp-content/debug.log
 ***************************************************************************************/
if (!function_exists('cjl_write_log')) {
    function   cjl_write_log( $log )  {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }
}
/***************************************************************************************
 * This function detects buddypress plugin presence and returns true when active
 ***************************************************************************************/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( ! function_exists('cjl_bdemail_buddypress_active') ) {
    function cjl_bdemail_buddypress_active() {
        $active = false;
        if (file_exists(dirname(__FILE__) . '/../buddypress/bp-loader.php')) {
            if (is_plugin_active('buddypress/bp-loader.php')) $active = true;
        }
        return $active;
    }
}
/***************************************************************************************
 * This function detects whether an SMTP plugin is present and returns true if it's active
 ***************************************************************************************/
if ( ! function_exists('cjl_bdemail_smtp_plugin_active') ) {
    function cjl_bdemail_smtp_plugin_active() {
        $active = false;
        if (!$active) {
            if (file_exists(dirname(__FILE__) . '/../postman-smtp/postman-smtp.php')) {
                if (is_plugin_active('postman-smtp/postman-smtp.php')) $active = true;
            }
        }
        if (!$active) {
            if (file_exists(dirname(__FILE__) . '/../wp-mail-smtp/wp_mail_smtp.php')) {
                if (is_plugin_active('wp-mail-smtp/wp_mail_smtp.php')) $active = true;
            }
        }
        if (!$active) {
            if (file_exists(dirname(__FILE__) . '/../easy-wp-smtp/easy-wp-smtp.php')) {
                if (is_plugin_active('easy-wp-smtp/easy-wp-smtp.php')) $active = true;
            }
        }
        if (!$active) {
            if (file_exists(dirname(__FILE__) . '/../gmail-smtp/main.php')) {
                if (is_plugin_active('gmail-smtp/main.php')) $active = true;
            }
        }
        if (!$active) {
            if (file_exists(dirname(__FILE__) . '/../wp-mail-bank/wp-mail-bank.php')) {
                if (is_plugin_active('wp-mail-bank/wp-mail-bank.php')) $active = true;
            }
        }
        return $active;
    }
}
/***************************************************************************************
 * This function attempts to load translations for the country/language set in Settings/General
 ***************************************************************************************/

if ( ! function_exists( 'cjl_bdemail_load_plugin_textdomain' ) ) {
    function cjl_bdemail_load_plugin_textdomain()
    {
        load_plugin_textdomain('birthday-emails', FALSE, basename(dirname(__FILE__)) . '/languages/');
    }
}
add_action( 'plugins_loaded', 'cjl_bdemail_load_plugin_textdomain' );

/* =====================================================================================
 * This section handles the activation, deactivation, and uninstall of the plugin
 * ===================================================================================== */

/***************************************************************************************
 * This function runs at the time the owner activates the plugin. It installs the scheduled event
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_install' ) ) {
    function cjl_bdemail_install()
    {
        // do not generate any output here
        update_option('cjlbdemailCountDown', '3');
        if (!wp_next_scheduled('cjl_dbemail_hourly_event')) {
            wp_schedule_event(time(), 'hourly', 'cjl_dbemail_hourly_event');
        }
    }
}
register_activation_hook( __FILE__, 'cjl_bdemail_install');
/***************************************************************************************
 * This function runs at the time the owner DEactivates the plugin. It removes the scheduled event
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_deactivate' ) ) {
    function cjl_bdemail_deactivate()
    {
        // do not generate any output here
        wp_clear_scheduled_hook('cjl_dbemail_hourly_event');
    }
}
register_deactivation_hook( __FILE__, 'cjl_bdemail_deactivate');
/***************************************************************************************
 * This function runs at the time the owner deletes the plugin. It removes options and settings.
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_uninstall' ) ) {
    function cjl_bdemail_uninstall()
    {
        // do not generate any output here
        $bdemailId = get_option('cjlbdemailID');
        wp_delete_post($bdemailId, true);
        delete_option('cjlbdemailID');
        delete_option('cjl_bdemail_settings');
        delete_option('cjlbdemailCountDown');
    }
}
register_uninstall_hook( __FILE__, 'cjl_bdemail_uninstall');

/* =====================================================================================
 * This section does the actual work of the plugin, checking for birthdays and sending emails
 * ===================================================================================== */

/***************************************************************************************
 * This function runs each time the event is fired. It sends the emails out for users whose birthday is today
 * and clears the flag saying this was done, for users whose birthday is NOT today
 ***************************************************************************************/
if ( ! function_exists( 'cjl_dbemail_hourly' ) ) {
    function cjl_dbemail_hourly()
    {
        // do something every hour
        // skip the first 3 hours after activation, giving time to setup the plugin before actually sending emails for today
        $countdown = get_option('cjlbdemailCountDown');
        if ($countdown) {
            $icountdown = intval($countdown);
            if ($icountdown) {
                $icountdown--;
                update_option('cjlbdemailCountDown', '' . $icountdown);
            }
            if ($icountdown) return;
        }
        $timezoneString = get_option('timezone_string');
        if ($timezoneString) date_default_timezone_set($timezoneString);
        // 1.0.1 - See if we're not yet at the hour to send emails
        $nowHour = (int)date('G'); // get the current hour number
        $options = get_option('cjl_bdemail_settings');
        if (!$options)$startHour = CJL_BDEMAILS_DEFAULT_START_HOUR_N;
        else $startHour = (int)$options['cjl_bdemail_text_field_Hour'];
        if (!$startHour) {$startHour = CJL_BDEMAILS_DEFAULT_START_HOUR_N;if ($options){$options['cjl_bdemail_text_field_Hour'] = CJL_BDEMAILS_DEFAULT_START_HOUR;update_option('cjl_bdemail_settings', $options);}}
        if ($startHour < 0){$startHour = CJL_BDEMAILS_DEFAULT_START_HOUR_N;if ($options){$options['cjl_bdemail_text_field_Hour'] = CJL_BDEMAILS_DEFAULT_START_HOUR;update_option('cjl_bdemail_settings', $options);}}
        if ($startHour > 23){$startHour = CJL_BDEMAILS_DEFAULT_START_HOUR_N;if ($options){$options['cjl_bdemail_text_field_Hour'] = CJL_BDEMAILS_DEFAULT_START_HOUR;update_option('cjl_bdemail_settings', $options);}}
        global $cjl_bdemail_checking_by_button;
        if ($nowHour < $startHour) {//if current hour is less than starting hour, don't check for birthdays, unless using the send emails now button
            if (!$cjl_bdemail_checking_by_button) {
                return;
            }
        }
        $cjl_bdemail_checking_by_button = false;

        // loop through all users with today as birthday and not yet done, sending email, marking done
        $todayDay = date('j');
        $todayMonth = date('n');

        // see if buddypress was installed
        $query = "";
        global $wpdb;
        $cjl_bdemail_buddypress_active = cjl_bdemail_buddypress_active();
        $field_id = 0;
        if (!$cjl_bdemail_buddypress_active) {// if NOT buddypress but straight WordPress, build these query parms
            // WP_User_Query arguments
            $args = array(
                'meta_query' => array(
                    array(
                        'key' => 'cjl_birthday',
                        'value' => $todayDay,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                ),
                'fields' => 'all_with_meta',
            );
        } else { //if buddypress, prepare for query this way instead:
            if (strlen($todayDay)<2) $todayDay = '0'.$todayDay;
            if (strlen($todayMonth)<2) $todayMonth = '0'.$todayMonth;
            $profilename = $options['cjl_bdemail_text_field_buddypress'];
            if (!$profilename) return;
            $query = 'SELECT id FROM ' . $wpdb->prefix . 'bp_xprofile_fields WHERE name = "' . $profilename . '"';
            $field_ids = $wpdb->get_results($query);
            $field_id = '';
            foreach ($field_ids as $rec) {
                $field_id = $rec->id;
                break;
            }
            if ( $field_id) {
                $query = "SELECT user_id FROM " . $wpdb->prefix . "bp_xprofile_data WHERE field_id = " . $field_id . " AND value LIKE '%-" . $todayMonth . "-" . $todayDay . " %'";
            }
            else
                return;
        }

        // The User Query
        if (!$cjl_bdemail_buddypress_active) {// if NOT buddypress but straight WordPress, we query users like this:
            $user_query = new WP_User_Query($args);
        } else { //if buddypress, query this way instead:
            $custom_ids = $wpdb->get_col( $query );
        }

        // The User Loop
        $emails_per_hour_count = CJL_BDEMAILS_EMAILS_PER_HOUR;
        if (!$cjl_bdemail_buddypress_active) {// if NOT buddypress but straight WordPress, we process users like this:
            if (!empty($user_query->results)) {
                foreach ($user_query->results as $user) {
                    if ((!cjl_bdemail_smtp_plugin_active()) && ($emails_per_hour_count < 1)) break;
                    if ($user->exists()) {
                        if ($user->has_prop('cjl_birthday')) $birthday = $user->get('cjl_birthday');
                        else $birthday = '';
                        if ($user->has_prop('cjl_birthmonth')) $birthmonth = $user->get('cjl_birthmonth');
                        else $birthmonth = '';
                        if ($user->has_prop('cjl_bdemailUnsubscribed')) $unsubscribed = ($user->get('cjl_bdemailUnsubscribed') == 'true');
                        else $unsubscribed = false;
                        if ($unsubscribed) {
                            break;
                        }
                        if ($user->has_prop('cjl_bdemailDone')) $bdemailDone = ($user->get('cjl_bdemailDone') == 'true');
                        else $bdemailDone = false;
                        if (CJL_BDEMAILS_EMAILS_TEST) $bdemailDone = false;
                        if (!$bdemailDone && $todayDay == $birthday && $todayMonth == $birthmonth) {
                            $emailStatus = cjl_bdemail_processEmail($user->get('ID'), $user->get('user_email'), $user->get('display_name'), $user->get('first_name'), $user->get('nickname'));
                            $emails_per_hour_count--;
                            if ($emailStatus) update_user_meta($user->get('ID'), 'cjl_bdemailDone', 'true');
                            if (!cjl_bdemail_smtp_plugin_active()) sleep(CJL_BDEMAILS_EMAILS_SLEEP);
                            if ($emailStatus) {
                                cjl_bdemail_sendNotification($user->get('display_name'));
                                $emails_per_hour_count--;
                                if (!cjl_bdemail_smtp_plugin_active()) sleep(CJL_BDEMAILS_EMAILS_SLEEP);
                            }
                        }
                    }
                }
            }
        } else { //if buddypress, process users this way instead:
            if (!empty($custom_ids)) {
                foreach ($custom_ids as $custom_id) {
                    if ((!cjl_bdemail_smtp_plugin_active()) && ($emails_per_hour_count < 1)) break;
                    $args = array(
                        'search'         => $custom_id,
                        'search_columns' => array( 'ID' ),
                        'fields' => 'all_with_meta'
                    );
                    $user_query = new WP_User_Query($args);
                    if (!empty($user_query->results)) {
                        foreach ($user_query->results as $user) {
                            if ($user->exists()) {
                                if ($user->has_prop('cjl_bdemailUnsubscribed')) $unsubscribed = ($user->get('cjl_bdemailUnsubscribed') == 'true');
                                else $unsubscribed = false;
                                if ($unsubscribed) {
                                    break;
                                }
                                if ($user->has_prop('cjl_bdemailDone')) $bdemailDone = ($user->get('cjl_bdemailDone') == 'true');
                                else $bdemailDone = false;
                                if (CJL_BDEMAILS_EMAILS_TEST) $bdemailDone = false;
                                if (!$bdemailDone) {
                                    $emailStatus = cjl_bdemail_processEmail($user->get('ID'), $user->get('user_email'), $user->get('display_name'), $user->get('first_name'), $user->get('nickname'));
                                    $emails_per_hour_count--;
                                    if ($emailStatus) {
                                        $result = update_user_meta($user->get('ID'), 'cjl_bdemailDone', 'true');
                                    }
                                    if (!cjl_bdemail_smtp_plugin_active()) sleep(CJL_BDEMAILS_EMAILS_SLEEP);
                                    if ($emailStatus) {
                                        cjl_bdemail_sendNotification($user->get('display_name'));
                                        $emails_per_hour_count--;
                                        if (!cjl_bdemail_smtp_plugin_active()) sleep(CJL_BDEMAILS_EMAILS_SLEEP);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // loop through all users with done, and not today as birthday, marking not done
        // WP_User_Query arguments
        $args = array(
            'meta_query' => array(
                array(
                    'key' => 'cjl_bdemailDone',
                    'value' => 'true',
                    'compare' => '=',
                    'type' => 'CHAR',
                ),
            ),
            'fields' => 'all_with_meta',
        );

        // The User Query
        $user_query = new WP_User_Query($args);

        // The User Loop
        if (!empty($user_query->results)) {
            foreach ($user_query->results as $user) {
                if ($user->exists()) {
                    if (!$cjl_bdemail_buddypress_active) {// if NOT buddypress but straight WordPress, we get user's day and month like this:
                        if ($user->has_prop('cjl_birthday')) $birthday = $user->get('cjl_birthday');
                        else $birthday = '';
                        if ($user->has_prop('cjl_birthmonth')) $birthmonth = $user->get('cjl_birthmonth');
                        else $birthmonth = '';
                    } else { //if buddypress, we get user's day and month like this instead:
                        $query = "SELECT value FROM " . $wpdb->prefix . "bp_xprofile_data WHERE field_id = " . $field_id . " AND user_id = " . $user->get('ID') ;
                        $dateValues = $wpdb->get_results($query);
                        foreach ($dateValues as $rec) {
                            $dateValue = $rec->value;
                            break;
                        }
                        if ($dateValue) {
                            $birthday = substr($dateValue, 8, 2);
                            $birthmonth = substr($dateValue, 5, 2);
                        } else continue;
                    }
                    if ($user->has_prop('cjl_bdemailDone')) $bdemailDone = ($user->get('cjl_bdemailDone') == 'true');
                    else $bdemailDone = false;
                    if ($bdemailDone && ($todayDay != $birthday || $todayMonth != $birthmonth)) update_user_meta($user->get('ID'), 'cjl_bdemailDone', 'false');
                }
            }
        }
        // go through unsubscribe table and remove entries over 90 days old
        $table_name = $wpdb->prefix.'cjl_bdemail_unsubscribe';
        $days_ago = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 90, date("Y")));
        $query = 'SELECT id from '.$table_name.' WHERE created < "'. $days_ago .'"';
        $results = $wpdb->get_results($query, ARRAY_A);
        if ($results) {
            foreach ($results as $result) {
                $wpdb->delete($table_name, $result);
            }
        }
    }
}
add_action('cjl_dbemail_hourly_event', 'cjl_dbemail_hourly');

/***************************************************************************************
 * This function sends a Notification email about the birthday wishes just sent if the option is selected in the admin options page.
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_sendNotification' ) ) {
    function cjl_bdemail_sendNotification($displayName)
    {
        $options = get_option('cjl_bdemail_settings');
        $fromName = $options['cjl_bdemail_text_field_From_Name'];
        $fromEmail = $options['cjl_bdemail_text_field_From_Email'];
        $headers = [];
        if ($fromEmail) $headers[] = "From: " . 'WebMaster @ ' . get_bloginfo('name') . " <$fromEmail>";
        if ($fromEmail) $headers[] = "Reply-to: $fromEmail";
        $headers[] = 'Content-type: text/plain; charset=utf-8';
        $headers = implode("\r\n", $headers);
        $checked = '';
        if (array_key_exists('cjl_bdemail_checkbox_Notify_YesNo', $options)) {
            if ($options['cjl_bdemail_checkbox_Notify_YesNo']) {
                $checked = isset($options['cjl_bdemail_text_field_Notification_Email']) ? esc_attr($options['cjl_bdemail_text_field_Notification_Email']) : '';
            }
        }
        if ($checked) {
            wp_mail($checked, __('Notification of Birthday Email Sent', 'birthday-emails'), __('A birthday email was sent to ','birthday-emails') . $displayName . '.', $headers);
        }
    }
}
/***************************************************************************************
 * This function does the work of assembling and sending an email.
 * It returns true if the email was successfully sent, indicating that the User should be marked with cjl_bdemailDone
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_processEmail' ) ) {
    function cjl_bdemail_processEmail($recipientID, $recipientAddr, $fullname, $firstname, $nickname)
    {
        $template = null;
        $email_status = false;
        $bdemailId = get_option('cjlbdemailID'); //see if we already created the Birthday Email Template
        if ($bdemailId) { //if an ID was recorded, be sure we can retrieve the email template, if not say it wasn't recorded.
            $template = get_post($bdemailId);
            if (null === $template) {
                $bdemailId = 0;
            } else {
                $postTitle = $template->post_title;
                $template = $template->post_content;
            }
        }
        if ($bdemailId) {
            $options = get_option('cjl_bdemail_settings');
            $fromName = $options['cjl_bdemail_text_field_From_Name'];
            $fromEmail = $options['cjl_bdemail_text_field_From_Email'];
            $template = str_replace('@fullname', $fullname, $template);
            $postTitle = str_replace('@fullname', $fullname, $postTitle);
            $template = str_replace('@firstname', $firstname, $template);
            $postTitle = str_replace('@firstname', $firstname, $postTitle);
            $template = str_replace('@nickname', $nickname, $template);
            $postTitle = str_replace('@nickname', $nickname, $postTitle);
            $blog_title = get_bloginfo('name');
            $template = str_replace('@sitetitle', $blog_title, $template);
            $postTitle = str_replace('@sitetitle', $blog_title, $postTitle);
            $url = plugins_url('images/birthdaycake.jpg', __FILE__);
            $img = "<img class=\"alignnone size-full wp-image-10\" src=\"@urlhere\" />";
            $img = str_replace('@urlhere', $url, $img);
            $template = str_replace('@defaultcakeimage', $img, $template);
            $template = str_replace('@unsubscribe', cjl_bdemail_getUnsubscribe($recipientID), $template);
            $template = wpautop($template);
            $headers = [];
            if ($fromEmail) $headers[] = "From: $fromName <$fromEmail>";
            if ($fromEmail) $headers[] = "Reply-to: $fromEmail";
            $headers[] = 'Content-type: text/html; charset=utf-8';
            $headers = implode("\r\n", $headers);
            $toaddr = $fullname . ' <' . $recipientAddr . '>';
            $_POST['cjl_bdemail'] = 'present';
            $email_status = wp_mail($toaddr, $postTitle, $template, $headers);
        }
        return $email_status;
    }
}
/***************************************************************************************
 * This function creates and returns a link containing an unique URL to use for unsubscribing.
 * The unique identifier is placed in a database table along with the userID of the person unsubscribing.
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_getUnsubscribe' ) ) {
    function cjl_bdemail_getUnsubscribe($recipientID)
    {
        // first, generate and store a hash of the $recipientID
        if (!$recipientID) {
            return '';
        }
        // generate hash of recipientID
        $random = openssl_random_pseudo_bytes(18);
        $salt = sprintf('$2y$%02d$%s',
            13, // 2^n cost factor
            substr(strtr(base64_encode($random), '+', '.'), 0, 22)
        );
        $hash = crypt($recipientID, $salt);
        $hashenc =urlencode($hash);
        //store the hash in the unsubscribe table
        global $wpdb;
        $table_name = $wpdb->prefix.'cjl_bdemail_unsubscribe';
        $wpdb->insert(
            $table_name,
            array(
                'created' => current_time( 'mysql' ),
                'hash' => $hash,
                'userid' => $recipientID
            )
        );
        // build the URL for unsubscribe
        $url = get_site_url().'/wp-content/plugins/birthday-emails/unsubscribe.php?uid='.$hashenc;
        // build the <a> link for unsubscribe
        $ret =  '<a href="' . $url .'">' . __('To unsubscribe, click here.', 'birthday-emails') . '</a>';
        return $ret;
    }
}


/* =====================================================================================
 * This section builds the admin page and manages the settings contained therein
 * ===================================================================================== */

/***************************************************************************************
 * This function adds an option to the User menu. It is run on admin_menu action
 * The options page is called cjl_bdemail_options_page
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_custom_admin_menu' ) ) {
    function cjl_bdemail_custom_admin_menu()
    {
        add_users_page(
            __('Birthday Emails Settings','birthday-emails'),
            __('Birthday Emails Settings','birthday-emails'),
            'manage_options',
            'birthday-emails.php',
            'cjl_bdemail_options_page'
        );
    }
}
add_action( 'admin_menu', 'cjl_bdemail_custom_admin_menu' );

/***************************************************************************************
 * This function adds the Settings choice underneath the Plugin Name on the Plugins page
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_plugin_action_links' ) ) {
    function cjl_bdemail_plugin_action_links( $links, $file ) {
        if ( ! is_network_admin() ) {
            if (current_user_can('manage_options')) {
                static $this_plugin;
                if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);

                if ($file == $this_plugin) {
                    $settings_link = '<a href="admin.php?page=birthday-emails.php">' . __('Settings','birthday-emails') . '</a>';
                    array_unshift($links, $settings_link);
                }
            }
        }
        return $links;
    }
}
add_filter( 'plugin_action_links', 'cjl_bdemail_plugin_action_links', 10, 2 );

/***************************************************************************************
 * This function adds the Settings choice underneath the Plugin Description on the Plugins page
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_register_plugin_links' ) ) {
    function cjl_bdemail_register_plugin_links( $links, $file ) {
        $base = plugin_basename( __FILE__ );
        if ( $file == $base ) {
            if ( ! is_network_admin() ) {
                if (current_user_can('manage_options')) {
                    $links[] = '<a href="admin.php?page=birthday-emails.php">' . __('Settings','birthday-emails') . '</a>';
                }
            }
        }
        return $links;
    }
}
add_filter( 'plugin_row_meta', 'cjl_bdemail_register_plugin_links', 10, 2 );

/***************************************************************************************
 * This function is called by WordPress when it is time to render the options page.
 * It is registered with the page itself, above.
 * Included here are additional submit buttons, in their own forms, to perform their own separate actions.
 * These actions are 'cjlbdtest' and 'cjlbdedit' and 'cjlbddohourly' which are handled by functions below.
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_options_page' ) ) {
    function cjl_bdemail_options_page()
    {
        $cjl_bdemail_buddypress_active = cjl_bdemail_buddypress_active();
        ?>
        <form action='options.php' method='post' xmlns="http://www.w3.org/1999/html">

            <h1><?php _e('Birthday Emails Settings','birthday-emails'); ?></h1>

            <h2><?php _e('Instructions:','birthday-emails'); ?></h2>

            <p><?php _e('Use the "Edit Birthday Email Template" button, below, to make the email look the way you want.','birthday-emails'); ?></p>

            <p><?php _e('Use the "Send Test Email" button, below, to send a test email to yourself so you can see how it will look.','birthday-emails'); ?></p>

            <p><?php if (!$cjl_bdemail_buddypress_active) { _e('Enter birthday day and month numbers on each user\'s profile page, under "Contact Info". Be sure to use numbers only, and','birthday-emails'); ?>
                    <span style="font-weight: bold"> <?php _e('no leading zero.','birthday-emails'); ?>
                    </span>
                <?php } else {
                    _e('Create a BuddyPress Profile Field for the member\'s birthdate, then select the name of the field you created, below.','birthday-emails');
                }?>
            </p>

            <?php
            settings_fields('cjlbdpluginPage');
            do_settings_sections('cjlbdpluginPage');
            submit_button();
            ?>
        </form>
        <div style="display: table;">
            <div style="display: table-row">
                <div style="width: 150px; display: table-cell;">
                    <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                        <?php wp_nonce_field('cjlbdtest'); ?>
                        <input type="hidden" name="action" value="cjlbdtest"/>
                        <input class="button button-primary" type="submit" value="<?php _e('Send Test Email','birthday-emails'); ?>" />
                    </form>
                </div>
                <div style="width: 220px; display: table-cell;">
                    <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                        <?php wp_nonce_field('cjlbdedit'); ?>
                        <input type="hidden" name="action" value="cjlbdedit"/>
                        <input class="button button-primary" type="submit" value="<?php _e('Edit Birthday Email Template','birthday-emails'); ?>" />
                    </form>
                    <br/><br/>
                </div>
                <?php
                    $adminURL = admin_url('admin.php');
                ?>
                <div style="width: 200px; display: table-cell;">
                    <form method="POST" action="<?php echo $adminURL ?>">
                        <?php wp_nonce_field('cjlbddohourly'); ?>
                        <input type="hidden" name="action" value="cjlbddohourly" />
                        <input class="button button-primary" type="submit" value="<?php _e('Check and Send Immediately','birthday-emails'); ?>" />
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
}
/***************************************************************************************
 * This function sanitizes user input from the admin options page.
 * It is run on by WordPress whenever the options page is saved.
 * It is registered as part of the settings defined below.
 ***************************************************************************************/
if ( ! function_exists( 'cjlbdemail_sanitize' ) ) {
    function cjlbdemail_sanitize($input)
    {
        $new_input = array();
        if (isset($input['cjl_bdemail_text_field_Hour'])) {
            $new_val = sanitize_text_field($input['cjl_bdemail_text_field_Hour']);
            if ($new_val != '0') {
                if (!$new_val) $new_val = CJL_BDEMAILS_DEFAULT_START_HOUR;
                $new_num = (int)$new_val;
                if (!$new_num) {
                    $new_val = CJL_BDEMAILS_DEFAULT_START_HOUR;
                    $new_num = CJL_BDEMAILS_DEFAULT_START_HOUR_N;
                }
                $new_val = (string)$new_num;
                if (!$new_val) $new_val = CJL_BDEMAILS_DEFAULT_START_HOUR;
                if ($new_num < 0) {
                    $new_val = CJL_BDEMAILS_DEFAULT_START_HOUR;
                    $new_num = CJL_BDEMAILS_DEFAULT_START_HOUR_N;
                }
                if ($new_num > 23) {
                    $new_val = CJL_BDEMAILS_DEFAULT_START_HOUR;
                    $new_num = CJL_BDEMAILS_DEFAULT_START_HOUR_N;
                }
            }
            $new_input['cjl_bdemail_text_field_Hour'] = $new_val;
        }
        if (isset($input['cjl_bdemail_text_field_From_Name']))
            $new_input['cjl_bdemail_text_field_From_Name'] = sanitize_text_field($input['cjl_bdemail_text_field_From_Name']);
        if (isset($input['cjl_bdemail_text_field_From_Email']))
            $new_input['cjl_bdemail_text_field_From_Email'] = sanitize_text_field($input['cjl_bdemail_text_field_From_Email']);
        if (isset($input['cjl_bdemail_checkbox_Notify_YesNo']))
            $new_input['cjl_bdemail_checkbox_Notify_YesNo'] = sanitize_text_field($input['cjl_bdemail_checkbox_Notify_YesNo']);
        if (isset($input['cjl_bdemail_text_field_Notification_Email']))
            $new_input['cjl_bdemail_text_field_Notification_Email'] = sanitize_text_field($input['cjl_bdemail_text_field_Notification_Email']);
        if (isset($input['cjl_bdemail_text_field_test_email']))
            $new_input['cjl_bdemail_text_field_test_email'] = sanitize_text_field($input['cjl_bdemail_text_field_test_email']);
        if (isset($input['cjl_bdemail_text_field_test_name']))
            $new_input['cjl_bdemail_text_field_test_name'] = sanitize_text_field($input['cjl_bdemail_text_field_test_name']);
        if (isset($input['cjl_bdemail_text_field_buddypress'])) {
            $new_input['cjl_bdemail_text_field_buddypress'] = sanitize_text_field($input['cjl_bdemail_text_field_buddypress']);
/*
            global $wpdb;
            $profilename = sanitize_text_field($input['cjl_bdemail_text_field_buddypress']);
            if ($profilename) {
                $query = 'SELECT id FROM ' . $wpdb->prefix . 'bp_xprofile_fields WHERE name = "' . $profilename . '"';
                $field_ids = $wpdb->get_results($query);
                $field_id = '';
                foreach ($field_ids as $rec) {
                    $field_id = $rec->id;
                    break;
                }
                if ($field_id) {
                    $new_input['cjl_bdemail_text_field_buddypress'] = $profilename;
                }
            }
*/
        }
        return $new_input;
    }
}
/***************************************************************************************
 * This function defines/registers a set of automatically managed admin settings
 * It is run on admin_init action. The settings are called cjl_bdemail_settings.
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_settings_init' ) ) {
    function cjl_bdemail_settings_init()
    {
        $cjl_bdemail_buddypress_active = cjl_bdemail_buddypress_active();

        register_setting('cjlbdpluginPage', 'cjl_bdemail_settings', 'cjlbdemail_sanitize');
        if ($cjl_bdemail_buddypress_active) {
            add_settings_section(
                'cjl_bdemail_pluginPage_section_buddypress',
                __('BuddyPress Integration', 'birthday-emails'),
                'cjl_bdemail_settings_section_buddypress_callback',
                'cjlbdpluginPage'
            );
            add_settings_field(
                'cjl_bdemail_text_field_buddypress',
                __('Name of birthdate Profile Field in BuddyPress', 'birthday-emails'),
                'cjl_bdemail_text_field_buddypress_render',
                'cjlbdpluginPage',
                'cjl_bdemail_pluginPage_section_buddypress'
            );
        }
        add_settings_section(
            'cjl_bdemail_pluginPage_section_hour',
            __('When to Start Sending Emails', 'birthday-emails'),
            'cjl_bdemail_settings_section_hour_callback',
            'cjlbdpluginPage'
        );
        add_settings_field(
            'cjl_bdemail_text_field_Hour',
            __('Hour to Start Sending', 'birthday-emails'),
            'cjl_bdemail_text_field_Hour_render',
            'cjlbdpluginPage',
            'cjl_bdemail_pluginPage_section_hour'
        );
        add_settings_section(
            'cjl_bdemail_pluginPage_section_from',
            __('Who the Emails Are From', 'birthday-emails'),
            'cjl_bdemail_settings_section_from_callback',
            'cjlbdpluginPage'
        );
        add_settings_field(
            'cjl_bdemail_text_field_From_Name',
            __('Name for From', 'birthday-emails'),
            'cjl_bdemail_text_field_From_Name_render',
            'cjlbdpluginPage',
            'cjl_bdemail_pluginPage_section_from'
        );
        add_settings_field(
            'cjl_bdemail_text_field_From_Email',
            __('Email Address for From', 'birthday-emails'),
            'cjl_bdemail_text_field_From_Email_render',
            'cjlbdpluginPage',
            'cjl_bdemail_pluginPage_section_from'
        );
        add_settings_section(
            'cjl_bdemail_pluginPage_section_notify',
            __('Send Notification', 'birthday-emails'),
            'cjl_bdemail_settings_section_notify_callback',
            'cjlbdpluginPage'
        );
        add_settings_field(
            'cjl_bdemail_checkbox_Notify_YesNo',
            __('Send Notification Too', 'birthday-emails'),
            'cjl_bdemail_checkbox_Notify_YesNo_render',
            'cjlbdpluginPage',
            'cjl_bdemail_pluginPage_section_notify'
        );
        add_settings_field(
            'cjl_bdemail_text_field_Notification_Email',
            __('Email Address for Notification', 'birthday-emails'),
            'cjl_bdemail_text_field_Notify_Email_render',
            'cjlbdpluginPage',
            'cjl_bdemail_pluginPage_section_notify'
        );
        add_settings_section(
            'cjl_bdemail_pluginPage_section',
            __('Settings for a Test Email', 'birthday-emails'),
            'cjl_bdemail_settings_section_callback',
            'cjlbdpluginPage'
        );

        add_settings_field(
            'cjl_bdemail_text_field_test_name',
            __('Full Name for Test Email', 'birthday-emails'),
            'cjl_bdemail_text_field_test_name_render',
            'cjlbdpluginPage',
            'cjl_bdemail_pluginPage_section'
        );
        add_settings_field(
            'cjl_bdemail_text_field_test_email',
            __('Email Address for Test Email', 'birthday-emails'),
            'cjl_bdemail_text_field_test_email_render',
            'cjlbdpluginPage',
            'cjl_bdemail_pluginPage_section'
        );

    }
}
add_action( 'admin_init', 'cjl_bdemail_settings_init' );
/***************************************************************************************
 * These functions are called by WordPress when it is time to render the settings fields
 * These functions are registered along with the settings, above.
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_text_field_buddypress_render' ) ) {
    function cjl_bdemail_text_field_buddypress_render()
    {
        global $wpdb;
        $options = get_option('cjl_bdemail_settings');
        $profilename = (isset($options['cjl_bdemail_text_field_buddypress'])) ? esc_attr($options['cjl_bdemail_text_field_buddypress']) : '';
        echo '<select name="cjl_bdemail_settings[cjl_bdemail_text_field_buddypress]" style="width: 250px;">';
        echo '<option value=""></option>';
        $query = 'SELECT name FROM ' . $wpdb->prefix . 'bp_xprofile_fields';
        $results = $wpdb->get_results($query);
        foreach ($results as $rec) {
            $field_name = $rec->name;
            $selectedText = '';
            if ($profilename == $field_name) $selectedText = 'selected';
            echo '<option value="'. $field_name .'" '. $selectedText .'>'. $field_name .'</option>';
        }
        echo '</select>';
    }
}
if ( ! function_exists( 'cjl_bdemail_text_field_Hour_render' ) ) {
    function cjl_bdemail_text_field_Hour_render()
    {
        $options = get_option('cjl_bdemail_settings');
        printf(
            '<input type="text" id="title" name="cjl_bdemail_settings[cjl_bdemail_text_field_Hour]" size="35" value="%s" />',
            isset($options['cjl_bdemail_text_field_Hour']) ? esc_attr($options['cjl_bdemail_text_field_Hour']) : ''
        );
    }
}
if ( ! function_exists( 'cjl_bdemail_text_field_From_Name_render' ) ) {
    function cjl_bdemail_text_field_From_Name_render()
    {
        $options = get_option('cjl_bdemail_settings');
        printf(
            '<input type="text" id="title" name="cjl_bdemail_settings[cjl_bdemail_text_field_From_Name]" size="35" value="%s" />',
            isset($options['cjl_bdemail_text_field_From_Name']) ? esc_attr($options['cjl_bdemail_text_field_From_Name']) : ''
        );
    }
}
if ( ! function_exists( 'cjl_bdemail_text_field_From_Email_render' ) ) {
    function cjl_bdemail_text_field_From_Email_render()
    {
        $options = get_option('cjl_bdemail_settings');
        printf(
            '<input type="text" id="title" name="cjl_bdemail_settings[cjl_bdemail_text_field_From_Email]" size="35" value="%s" />',
            isset($options['cjl_bdemail_text_field_From_Email']) ? esc_attr($options['cjl_bdemail_text_field_From_Email']) : ''
        );
    }
}
if ( ! function_exists( 'cjl_bdemail_checkbox_Notify_YesNo_render' ) ) {
    function cjl_bdemail_checkbox_Notify_YesNo_render()
    {
        $options = get_option('cjl_bdemail_settings');
        $checked = " ";
        if (array_key_exists('cjl_bdemail_checkbox_Notify_YesNo', $options)) {
            if ($options['cjl_bdemail_checkbox_Notify_YesNo']) {
                $checked = " checked='checked' ";
            }
        }

        echo '<input type="checkbox" name="cjl_bdemail_settings[cjl_bdemail_checkbox_Notify_YesNo]" value="true" ' . $checked . ' " />';
    }
}
if ( ! function_exists( 'cjl_bdemail_text_field_Notify_Email_render' ) ) {
    function cjl_bdemail_text_field_Notify_Email_render()
    {
        $options = get_option('cjl_bdemail_settings');
        printf(
            '<input type="text" id="title" name="cjl_bdemail_settings[cjl_bdemail_text_field_Notification_Email]" size="35" value="%s" />',
            isset($options['cjl_bdemail_text_field_Notification_Email']) ? esc_attr($options['cjl_bdemail_text_field_Notification_Email']) : ''
        );
    }
}
if ( ! function_exists( 'cjl_bdemail_text_field_test_email_render' ) ) {
    function cjl_bdemail_text_field_test_email_render()
    {
        $options = get_option('cjl_bdemail_settings');
        printf(
            '<input type="text" id="title" name="cjl_bdemail_settings[cjl_bdemail_text_field_test_email]" size="35" value="%s" />',
            isset($options['cjl_bdemail_text_field_test_email']) ? esc_attr($options['cjl_bdemail_text_field_test_email']) : ''
        );
    }
}
if ( ! function_exists( 'cjl_bdemail_text_field_test_name_render' ) ) {
    function cjl_bdemail_text_field_test_name_render()
    {
        $options = get_option('cjl_bdemail_settings');
        printf(
            '<input type="text" id="title" name="cjl_bdemail_settings[cjl_bdemail_text_field_test_name]" size="35" value="%s" />',
            isset($options['cjl_bdemail_text_field_test_name']) ? esc_attr($options['cjl_bdemail_text_field_test_name']) : ''
        );
    }
}
/***************************************************************************************
 * These functions are called by WordPress when it is time to render the instructions for a section of options.
 * These functions are registered along with the settings, above.
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_settings_section_callback' ) ) {
    function cjl_bdemail_settings_section_callback()
    {

        _e('These fields allow you to designate a recipient of a Test Email, so you can see what your email will look like when sent to someone on their birthday.', 'birthday-emails');

    }
}
if ( ! function_exists( 'cjl_bdemail_settings_section_buddypress_callback' ) ) {
    function cjl_bdemail_settings_section_buddypress_callback()
    {

        _e('You should have created a BuddyPress Profile Field for the member to enter their birthdate. You will have given that field a name. Select that field name in this drop-down box.', 'birthday-emails');

    }
}
if ( ! function_exists( 'cjl_bdemail_settings_section_hour_callback' ) ) {
    function cjl_bdemail_settings_section_hour_callback()
    {

        _e('Specify the hour of each day in which to start sending emails. (0-23) eg. 8 means start sending emails in the 8th hour of each day (ie. some time after 8:00am).', 'birthday-emails');

    }
}
if ( ! function_exists( 'cjl_bdemail_settings_section_from_callback' ) ) {
    function cjl_bdemail_settings_section_from_callback()
    {

        _e('These fields allow you to specify who the birthday emails shall be from.', 'birthday-emails');

    }
}
if ( ! function_exists( 'cjl_bdemail_settings_section_notify_callback' ) ) {
    function cjl_bdemail_settings_section_notify_callback()
    {
        _e('For each birthday email sent, you can have a notification email sent informing you of the fact. This way you\'ll know who got birthday wishes and when.', 'birthday-emails');
    }
}
/***************************************************************************************
 * This function is called when the form with 'cjlbdedit' for a hidden 'action' is clicked
 * It opens the post edit page for the custom post type that contains the email template.
  ***************************************************************************************/
if ( ! function_exists( 'cjlbdedit_admin_action' ) ) {
    function cjlbdedit_admin_action()
    {
        check_admin_referer('cjlbdedit');
        if (current_user_can('manage_options')||current_user_can('administrator')) {
            $editorURL = $_SERVER['HTTP_REFERER'];
            $editorURL = substr($editorURL, 0, strripos($editorURL, '/'));
            $bdemailId = get_option('cjlbdemailID');
            $editorURL .= '/post.php?post=' . $bdemailId . '&action=edit';
            wp_redirect($editorURL);
            exit();
        }
    }
}
add_action( 'admin_action_cjlbdedit', 'cjlbdedit_admin_action' );

/***************************************************************************************
 * This function is called when the form with 'cjlbdtest' for a hidden 'action' is clicked
 * It sends a test email to the name and address on the adm form.
 ***************************************************************************************/
if ( ! function_exists( 'cjlbdtest_admin_action' ) ) {
    function cjlbdtest_admin_action()
    {
        if (current_user_can('manage_options')||current_user_can('administrator')) {
            check_admin_referer('cjlbdtest');
            $options = get_option('cjl_bdemail_settings');
            cjl_bdemail_processEmail(-999999, $options['cjl_bdemail_text_field_test_email'], $options['cjl_bdemail_text_field_test_name'], $options['cjl_bdemail_text_field_test_name'], $options['cjl_bdemail_text_field_test_name']);
            wp_redirect($_SERVER['HTTP_REFERER']);
            exit();
        }
    }
}
add_action( 'admin_action_cjlbdtest', 'cjlbdtest_admin_action' );

/***************************************************************************************
 * This function is called when the form with 'cjlbddohourly' for a hidden 'action' is clicked
 * It immediately runs the function that normally fires once an hour to process the Users and send birthday emails.
 * This was used for testing.
 ***************************************************************************************/
if ( ! function_exists( 'cjlbddohourly_admin_action' ) ) {
    function cjlbddohourly_admin_action()
    {
        if (current_user_can('manage_options')||current_user_can('administrator')) {
            check_admin_referer('cjlbddohourly');
            update_option('cjlbdemailCountDown', '' . 0);
            global $cjl_bdemail_checking_by_button;
            $cjl_bdemail_checking_by_button = true;
            cjl_dbemail_hourly();
            wp_redirect($_SERVER['HTTP_REFERER']);
            exit();
        }
    }
}
add_action( 'admin_action_cjlbddohourly', 'cjlbddohourly_admin_action' );


/* =====================================================================================
 * This section manages the User Profile page, adding birthday fields to the page
 * ===================================================================================== */

/***************************************************************************************
 * This function adds ot the list of contact fields, two new ones, for birthday info
 * It is applied as a filter on 'user_contactmethods'
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_modify_contact_methods' ) ) {
    function cjl_bdemail_modify_contact_methods($profile_fields)
    {
        $cjl_bdemail_buddypress_active = cjl_bdemail_buddypress_active();
        if (!$cjl_bdemail_buddypress_active) {
            // Add new fields
            $profile_fields['cjl_birthday'] = __('Birth Day Number (1-31)', 'birthday-emails');
            $profile_fields['cjl_birthmonth'] = __('Birth Month Number (1-12)', 'birthday-emails');
        }
        $profile_fields['cjl_bdemailUnsubscribed'] = __('Birthday Emails Unsubscribed', 'birthday-emails');
        return $profile_fields;
    }
}
add_filter('user_contactmethods', 'cjl_bdemail_modify_contact_methods');


/* =====================================================================================
 * This section manages the custom post type that holds the email template
 * ===================================================================================== */

/***************************************************************************************
 * This function defines a custom post type (CPT) to hold the birthday email template.
 * This is done to take advantage of the post editor page associated with post types.
 * It is called on the 'init' action.
 * Note that all options except show ui are false. So this post type is private and only shown
 * to the user when the user clicks the button on the admin page.
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_custom_post_type' ) ) {
    function cjl_bdemail_custom_post_type()
    {

// Set UI labels for Custom Post Type
        $labels = array(
            'name' => _x('Birthday Emails', 'Post Type General Name', 'birthday-emails'),
            'singular_name' => _x('Birthday Email', 'Post Type Singular Name', 'birthday-emails'),
            'menu_name' => __('Birthday Emails', 'birthday-emails'),
            'parent_item_colon' => __('Parent Email', 'birthday-emails'),
            'all_items' => __('All Birthday Emails', 'birthday-emails'),
            'view_item' => __('View Birthday Email', 'birthday-emails'),
            'add_new_item' => __('Add New Birthday Email', 'birthday-emails'),
            'add_new' => __('Add New', 'birthday-emails'),
            'edit_item' => __('Edit Birthday Email Template', 'birthday-emails'),
            'update_item' => __('Update Birthday Email', 'birthday-emails'),
            'search_items' => __('Search Birthday Emails', 'birthday-emails'),
            'not_found' => __('Not Found', 'birthday-emails'),
            'not_found_in_trash' => __('Not found in Trash', 'birthday-emails'),
        );

// Set other options for Custom Post Type

        $args = array(
            'label' => 'cjlbdemails',
            'description' => __('Email Templates for Birthday Emails', 'birthday-emails'),
            'labels' => $labels,
            // Features this CPT supports in Post Editor
            'supports' => array('title', 'editor'),
            //'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
            // You can associate this CPT with a taxonomy or custom taxonomy.
//        'taxonomies'          => array( 'genres' ),
            /* A hierarchical CPT is like Pages and can have
            * Parent and child items. A non-hierarchical CPT
            * is like Posts.
            */
            'hierarchical' => false,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => false,
            'menu_position' => 5,
            'can_export' => false,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability_type' => 'page',
            'capabilities' => array(
                'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
            ),
            'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
        );

        // Registering your Custom Post Type
        register_post_type('cjlbdemails', $args);

    }
}
add_action( 'init', 'cjl_bdemail_custom_post_type', 0 );

/***************************************************************************************
 * Build initial settings, options, and template - builds content if none exists yet.
 * This function checks for the existence of the option that stores the post ID of the email template.
 * If not found, or the email template itself is not found, then the email template is created with default content.
 * It is called on the 'init' action.
 ***************************************************************************************/
if ( ! function_exists( 'cjl_bdemail_buildbdemail' ) ) {
    function cjl_bdemail_buildbdemail()
    {
        $bdemailId = get_option('cjlbdemailID'); //see if we already created the Birthday Email Template
        if ($bdemailId) { //if an ID was recorded, be sure we can retrieve the email template, if not say it wasn't recorded.
            if (null === get_post($bdemailId)) {
                $bdemailId = 0;
            }
        }
        if (!$bdemailId) { //if not, see if it was just not recorded
            $the_query = new WP_Query(array('post_type' => 'cjlbdemails', 'post_status' => 'publish'));
            while ($the_query->have_posts()) {
                $the_query->the_post(); //found it
                $bdemailId = get_the_ID();
                update_option('cjlbdemailID', $bdemailId); //record it
            }
            $the_query = null;
        }
        if (!$bdemailId) { //we didn't create the email template yet, so do it now.
            $args = array(
                'post_title' => __('Happy Birthday', 'birthday-emails').' @fullname',
                'post_content' => '<p><h1>'. __('Happy Birthday', 'birthday-emails').' @fullname'. __('!','birthday-emails').'</h1></p><p>@defaultcakeimage</p><p>'.__('We hope you have a great birthday, and many happy returns.','birthday-emails').'</p><p></p><p>'.__('Sincerely','birthday-emails').',</p><p>'.__('Admin','birthday-emails').' @ @sitetitle.</p><p style="text-align: center;">@unsubscribe</p>',
                'post_status' => 'publish',
                'post_type' => 'cjlbdemails'
            );
            $bdemailId = wp_insert_post($args);
            if ($bdemailId) update_option('cjlbdemailID', $bdemailId); //record it
        }
        $bdemailId = get_option('cjl_bdemail_settings'); //see if we already added admin options
        if (!$bdemailId) {// if we didn't already set admin options:
            $blog_title = get_bloginfo('name');
            $blog_email = get_bloginfo('admin_email');
            $args = [];
            $args['cjl_bdemail_text_field_Hour'] = CJL_BDEMAILS_DEFAULT_START_HOUR;
            $args['cjl_bdemail_text_field_From_Name'] = __('WebMaster','birthday-emails')." @ $blog_title";
            $args['cjl_bdemail_text_field_From_Email'] = $blog_email;
            //$args['cjl_bdemail_checkbox_Notify_YesNo'] = 'true';
            $args['cjl_bdemail_text_field_Notification_Email'] = $blog_email;
            $args['cjl_bdemail_text_field_test_email'] = $blog_email;
            $args['cjl_bdemail_text_field_test_name'] = __('WebMaster','birthday-emails')." @ $blog_title";
            update_option('cjl_bdemail_settings', $args);
            $bdemailId = get_option('cjl_bdemail_settings');
        } else { //if we did already add them, see if we set the hour to start sending emails (new feature with 1.1)
            if (!isset($bdemailId['cjl_bdemail_text_field_Hour'])){
                $bdemailId['cjl_bdemail_text_field_Hour'] = CJL_BDEMAILS_DEFAULT_START_HOUR;
                update_option('cjl_bdemail_settings', $bdemailId);
            }
        }
        // if BuddyPress, then attempt to make sure something is selected for the birthdate Profile Field, something that contains 'birth'
        global $wpdb;
        if (!isset($bdemailId['cjl_bdemail_text_field_buddypress'])){
            if (cjl_bdemail_buddypress_active()){
                $results = $wpdb->get_results('SELECT name FROM ' . $wpdb->prefix . 'bp_xprofile_fields');
                foreach ($results as $rec) {
                    $field_name = $rec->name;
                    $pos = strpos(strtolower($field_name),'birth');
                    if ($pos !== false){
                        $bdemailId['cjl_bdemail_text_field_buddypress'] = $field_name;
                        update_option('cjl_bdemail_settings', $bdemailId);
                        break;
                    }
                }
            }
        }

        //see if unsubscribe table exists, create it if not
        global $wpdb;
        $table_name = $wpdb->prefix.'cjl_bdemail_unsubscribe';
        $charset_collate = $wpdb->get_charset_collate();
        $existing = $wpdb->get_var('SHOW TABLES LIKE "'.$table_name.'";');
        if ($existing != $table_name) {
            $sql = "CREATE TABLE $table_name (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    hash varchar(100) NOT NULL,
                    userid mediumint(9) NOT NULL,
                    created date NOT NULL,
                    PRIMARY KEY  (ID)
            ) $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
    }
}
add_action( 'init', 'cjl_bdemail_buildbdemail' );

/***************************************************************************************
 * This function adds instructions to the bottom of the post edit form, IF the post ID is the one with the email template,
 * It is called on the 'edit_form_after_editor' action, new to WordPress 4.5 - this is the reason the plugin requires at least that version.
 ***************************************************************************************/
if ( ! function_exists( 'cjlbdemail_edit_form_after_editor' ) ) {
    function cjlbdemail_edit_form_after_editor()
    {
        global $post;
        if ($post->post_type == 'cjlbdemails') {

            echo '<h1>'.__('Note:', 'birthday-emails').'</h1><br/>'.__('Use the Title, at the top of this page, for the Subject line of the Email.', 'birthday-emails').'<br/><br/>'._x('To insert the user\'s full name type "','ends with opening quote','birthday-emails').'@fullname' . _x('" (without quotes)','begins with close quote','birthday_emails') . '<br/><br/>';
            echo _x('"','opening quote','opening quote','birthday-emails').'@fullname'._x('" will be replaced with the user\'s Full Name whose birthday it is.','begins with close quote','birthday-emails').'<br/>';
            echo _x('"','opening quote','birthday-emails').'@firstname'._x('" will be replaced with the user\'s First Name whose birthday it is.','begins with close quote','birthday-emails').'<br/>';
            echo _x('"','opening quote','birthday-emails').'@nickname'._x('" will be replaced with the user\'s Nick Name whose birthday it is.','begins with close quote','birthday-emails').'<br/>';
            echo _x('"','opening quote','birthday-emails').'@sitetitle'._x('" will be replaced with the Blog Site Title from the Settings/General page.','begins with close quote','birthday-emails').'<br/>';
            echo _x('"','opening quote','birthday-emails').'@unsubscribe'._x('" will be replaced with a link allowing the recipient to unsubscribe from Birthday Emails.','begins with close quote','birthday-emails').'<br/>';
            echo _x('"','opening quote','birthday-emails').'@defaultcakeimage'._x('" will be replaced with an image of a birthday cake that comes with the Birthday Emails plugin.','begins with close quote','birthday-emails').'<br/>';
        }
    }
}
add_action( 'edit_form_after_editor', 'cjlbdemail_edit_form_after_editor' );

/***************************************************************************************
 * This function adds css to the head of the admin pages if the post type is the email template
 * The css hides the publish information and also the 'Move to Trash' option.
 * It is called by the 'admin_head-post.php' action.
 ***************************************************************************************/
if ( ! function_exists( 'cjl_hide_publishing_actions' ) ) {
    function cjl_hide_publishing_actions()
    {
        global $post;
        if ($post->post_type == 'cjlbdemails') {
            echo '
                <style type="text/css">
                    #delete-action,
                    #misc-publishing-actions,
                    #minor-publishing-actions{
                        display:none;
                    }
                </style>
            ';
        }
    }
}
add_action('admin_head-post.php', 'cjl_hide_publishing_actions');
