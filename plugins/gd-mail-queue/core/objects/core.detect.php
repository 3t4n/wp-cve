<?php

if (!defined('ABSPATH')) { exit; }

class gdmaq_core_type_detect {
    public $supported_types = array();

    public function __construct() {
        $this->_init_supported_types();

        do_action('gdmaq_email_type_detection_registration_started');

        /* WordPress Core */
        add_filter('comment_moderation_headers', array($this, 'wp_comment_notify_moderator'));
        add_filter('comment_notification_headers', array($this, 'wp_comment_notify_postauthor'));
        add_filter('new_user_email_content', array($this, 'wp_email_change_confirmation'));
        add_filter('email_change_email', array($this, 'wp_email_change_notification'));
        add_filter('password_change_email', array($this, 'wp_password_change_notification'));
        add_filter('retrieve_password_message', array($this, 'wp_retrieve_password_message'));

        add_filter('user_request_confirmed_email_subject', array($this, 'wp_privacy_request_confirmation'));
        add_filter('user_confirmed_action_email_content', array($this, 'wp_privacy_erasure_fulfillment'));
        add_filter('user_request_action_email_subject', array($this, 'wp_send_user_request'));
        add_filter('wp_privacy_personal_data_email_headers', array($this, 'wp_privacy_personal_data_email_headers'));

        add_filter('new_admin_email_content', array($this, 'wp_new_admin_email_content'));
        add_filter('site_admin_email_change_email', array($this, 'wp_site_admin_email_change'));
        add_filter('wp_new_user_notification_email_admin', array($this, 'wp_new_user_notification_admin'));
        add_filter('wp_new_user_notification_email', array($this, 'wp_new_user_notification'));
        add_filter('wp_password_change_notification_email', array($this, 'wp_password_change_notification_admin'));

        add_filter('auto_plugin_theme_update_email', array($this, 'wp_auto_plugin_theme_update_email'));
        add_filter('auto_core_update_email', array($this, 'wp_auto_core_update_email'));
        add_filter('automatic_updates_debug_email', array($this, 'wp_automatic_updates_debug_email'));

        add_filter('recovery_mode_email', array($this, 'wp_recovery_mode_email'));

        /* WordPress Network */
        add_filter('wpmu_signup_blog_notification_subject', array($this, 'wpmu_signup_blog_confirmation'));
        add_filter('wpmu_signup_user_notification_subject', array($this, 'wpmu_signup_user_confirmation'));
        add_filter('update_welcome_subject', array($this, 'wpmu_welcome_blog'));
        add_filter('update_welcome_user_subject', array($this, 'wpmu_welcome_user'));
        add_filter('newblog_notify_siteadmin', array($this, 'wpmu_new_blog_siteadmin'));
        add_filter('newuser_notify_siteadmin', array($this, 'wpmu_new_user_siteadmin'));
        add_filter('new_network_admin_email_content', array($this, 'wpmu_network_admin_email_confirmation'));
        add_filter('network_admin_email_change_email', array($this, 'wpmu_network_admin_email_notification'));
        add_filter('delete_site_email_content', array($this, 'wpmu_delete_site_email_content'));

        /* bbPress Core */
        add_action('bbp_pre_notify_subscribers', array($this, 'bbpress_new_reply_in_topic'));
        add_action('bbp_pre_notify_forum_subscribers', array($this, 'bbpress_new_topic_in_forum'));

        /* GD bbPress Toolbox Pro */
        add_action('bbp_pre_notify_topic_auto_close', array($this, 'bbpress_topic_auto_close'));
        add_action('bbp_pre_notify_topic_manual_close', array($this, 'bbpress_topic_manual_close'));
        add_action('bbp_pre_notify_topic_edit_subscribers', array($this, 'bbpress_topic_edit'));
        add_action('bbp_pre_notify_reply_edit_subscribers', array($this, 'bbpress_reply_edit'));
        add_action('bbp_pre_notify_new_topic_moderators', array($this, 'bbpress_new_topic_moderators'));

        /* GD Topic Polls Pro */
        add_action('gdpol_daily_digest_notify_moderators_pre_notify', array($this, 'gdpol_digest_notify_moderators'));
        add_action('gdpol_daily_digest_notify_author_pre_notify', array($this, 'gdpol_digest_notify_author'));
        add_action('gdpol_instant_notify_pre_notify', array($this, 'gdpol_instant_notify'));

        /* WP Members */
        add_filter('wpmem_email_filter', array($this, 'wpmem_email_filter'));
        add_filter('wpmem_notify_filter', array($this, 'wpmem_notify_filter'));

        /* RankMath */
        add_filter('rank_math/auto_update_email', array($this, 'rank_math_auto_update_email'));

        /* Asgaros Forum */
        add_filter('asgarosforum_subscriber_mails_new_post', array($this, 'asgarosforum_subscriber_mails_new_post'));
        add_filter('asgarosforum_subscriber_mails_new_topic', array($this, 'asgarosforum_subscriber_mails_new_topic'));

        /* BuddyPress Core */
        add_action('bp_send_email', array($this, 'buddypress_send_email'), 10, 2);

        /* CF7 and other WPMail based detections */
        add_filter('wp_mail', array($this, 'wp_mail'), 1);

        do_action('gdmaq_email_type_detection_registration_ended');
    }

    private function _init_supported_types() {
        $this->supported_types = apply_filters('gdmaq_email_type_detection_supported_types_list', array(
            'gdmaq_email_test' => _x("GD Mail Queue: Email Test", "Email Detection Type", "gd-mail-queue"),
            'gdmaq_queue_test' => _x("GD Mail Queue: Queue Test", "Email Detection Type", "gd-mail-queue"),
            'gdmaq_notify_overview_daily' => _x("GD Mail Queue: Daily Overview Digest", "Email Detection Type", "gd-mail-queue"),
            'gdmaq_notify_overview_weekly' => _x("GD Mail Queue: Weekly Overview Digest", "Email Detection Type", "gd-mail-queue"),
            'gdmaq_notify_errors_daily' => _x("GD Mail Queue: Daily Errors Digest", "Email Detection Type", "gd-mail-queue"),
            'gdmaq_notify_errors_weekly' => _x("GD Mail Queue: Weekly Errors Digest", "Email Detection Type", "gd-mail-queue"),
            'wp_comment_notify_moderator' => _x("WordPress: Comment Notify Moderator", "Email Detection Type", "gd-mail-queue"),
            'wp_email_change_confirmation' => _x("WordPress: Email Change Confirmation", "Email Detection Type", "gd-mail-queue"),
            'wp_email_change_notification' => _x("WordPress: Email Change Notification", "Email Detection Type", "gd-mail-queue"),
            'wp_password_change_notification' => _x("WordPress: Password Change Notification", "Email Detection Type", "gd-mail-queue"),
            'wp_retrieve_password_message' => _x("WordPress: Retrieve Password Notification", "Email Detection Type", "gd-mail-queue"),
            'wp_privacy_personal_data_email_headers' => _x("WordPress: Privacy Personal Data Email", "Email Detection Type", "gd-mail-queue"),
            'wp_privacy_request_confirmation' => _x("WordPress: Privacy Request Confirmation", "Email Detection Type", "gd-mail-queue"),
            'wp_privacy_erasure_fulfillment' => _x("WordPress: Privacy Erasure Fulfillment", "Email Detection Type", "gd-mail-queue"),
            'wp_send_user_request' => _x("WordPress: Send User Request", "Email Detection Type", "gd-mail-queue"),
            'wp_site_admin_email_change' => _x("WordPress: Site Admin Email Change", "Email Detection Type", "gd-mail-queue"),
            'wp_site_admin_email_change_attempt' => _x("WordPress: Site Admin Email Change Attempt", "Email Detection Type", "gd-mail-queue"),
            'wp_auto_plugin_theme_update_email' => _x("WordPress: Plugin or Theme Update Email", "Email Detection Type", "gd-mail-queue"),
            'wp_auto_core_update_email' => _x("WordPress: Core Update Email", "Email Detection Type", "gd-mail-queue"),
            'wp_automatic_updates_debug_email' => _x("WordPress: Auto Update Debug Email", "Email Detection Type", "gd-mail-queue"),
            'wp_new_user_notification_admin' => _x("WordPress: New User Notification Admin", "Email Detection Type", "gd-mail-queue"),
            'wp_new_user_notification' => _x("WordPress: New User Notification", "Email Detection Type", "gd-mail-queue"),
            'wp_password_change_notification_admin' => _x("WordPress: Password Change Notification Admin", "Email Detection Type", "gd-mail-queue"),
            'wp_recovery_mode_email' => _x("WordPress: Recovery Mode Email", "Email Detection Type", "gd-mail-queue"),
            'wpmu_signup_blog_confirmation' => _x("WordPress: Signup Blog Confirmation", "Email Detection Type", "gd-mail-queue"),
            'wpmu_signup_user_confirmation' => _x("WordPress: Signup User Confirmation", "Email Detection Type", "gd-mail-queue"),
            'wpmu_delete_site_email_content' => _x("WordPress: Site Deleted Email", "Email Detection Type", "gd-mail-queue"),
            'wpmu_welcome_blog' => _x("WordPress: Welcome Blog", "Email Detection Type", "gd-mail-queue"),
            'wpmu_welcome_user' => _x("WordPress: Welcome User", "Email Detection Type", "gd-mail-queue"),
            'wpmu_new_blog_siteadmin' => _x("WordPress: New Blog Site Admin", "Email Detection Type", "gd-mail-queue"),
            'wpmu_new_user_siteadmin' => _x("WordPress: New User Site Admin", "Email Detection Type", "gd-mail-queue"),
            'wpmu_network_admin_email_confirmation' => _x("WordPress: Network Admin Email Confirmation", "Email Detection Type", "gd-mail-queue"),
            'wpmu_network_admin_email_notification' => _x("WordPress: Network Admin Email Notification", "Email Detection Type", "gd-mail-queue"),
            'bbpress_new_reply_in_topic' => _x("bbPress: New Reply In Topic", "Email Detection Type", "gd-mail-queue"),
            'bbpress_new_topic_in_forum' => _x("bbPress: New Topic In Forum", "Email Detection Type", "gd-mail-queue"),
            'bbpress_topic_auto_close' => _x("bbPress: Topic Auto Close", "Email Detection Type", "gd-mail-queue"),
            'bbpress_topic_manual_close' => _x("bbPress: Topic Manual Close", "Email Detection Type", "gd-mail-queue"),
            'bbpress_topic_edit' => _x("bbPress: Topic Edit", "Email Detection Type", "gd-mail-queue"),
            'bbpress_reply_edit' => _x("bbPress: Reply Edit", "Email Detection Type", "gd-mail-queue"),
            'bbpress_new_topic_moderators' => _x("bbPress: New Topic Moderators", "Email Detection Type", "gd-mail-queue"),
            'gdpol_digest_notify_moderators' => _x("GD Topic Polls: Digest Notify Moderators", "Email Detection Type", "gd-mail-queue"),
            'gdpol_digest_notify_author' => _x("GD Topic Polls: Digest Notify Author", "Email Detection Type", "gd-mail-queue"),
            'gdpol_instant_notify' => _x("GD Topic Polls: Instant Notify", "Email Detection Type", "gd-mail-queue"),
            'wpmem_custom' => _x("WP Members: Custom", "Email Detection Type", "gd-mail-queue"),
            'wpmem_newreg' => _x("WP Members: New User Registration", "Email Detection Type", "gd-mail-queue"),
            'wpmem_newmod' => _x("WP Members: New User Registration Moderated", "Email Detection Type", "gd-mail-queue"),
            'wpmem_appmod' => _x("WP Members: Registration Approved", "Email Detection Type", "gd-mail-queue"),
            'wpmem_repass' => _x("WP Members: Password Reset", "Email Detection Type", "gd-mail-queue"),
            'wpmem_getuser' => _x("WP Members: Retrieve Username", "Email Detection Type", "gd-mail-queue"),
            'wpmem_admin_notify' => _x("WP Members: Admin Notification for User Registration", "Email Detection Type", "gd-mail-queue"),
            'asgaros_subscriber_new_topic' => _x("Asgaros Forum: New Topic Email", "Email Detection Type", "gd-mail-queue"),
            'asgaros_subscriber_new_post' => _x("Asgaros Forum: New Post Email", "Email Detection Type", "gd-mail-queue"),
            'contact_form_7_email' => _x("Contact Form 7: Contact Email", "Email Detection Type", "gd-mail-queue"),
            'rank_math_auto_update_email' => _x("Rank Math: Auto Update Email", "Email Detection Type", "gd-mail-queue"),
            'buddypress_core-user-registration-with-blog' => _x("BuddyPress: Core User Registration With Blog", "Email Detection Type", "gd-mail-queue"),
            'buddypress_core-user-registration' => _x("BuddyPress: Core User Registration", "Email Detection Type", "gd-mail-queue"),
            'buddypress_friends-request' => _x("BuddyPress: Friends Request", "Email Detection Type", "gd-mail-queue"),
            'buddypress_friends-request-accepted' => _x("BuddyPress: Friends Request Accepted", "Email Detection Type", "gd-mail-queue"),
            'buddypress_activity-comment' => _x("BuddyPress: Activity Comment", "Email Detection Type", "gd-mail-queue"),
            'buddypress_activity-comment-author' => _x("BuddyPress: Activity Comment Author", "Email Detection Type", "gd-mail-queue"),
            'buddypress_messages-unread' => _x("BuddyPress: Messages Unread", "Email Detection Type", "gd-mail-queue"),
            'buddypress_settings-verify-email-change' => _x("BuddyPress: Settings Verify Email Change", "Email Detection Type", "gd-mail-queue"),
            'buddypress_groups-details-updated' => _x("BuddyPress: Groups Details Updated", "Email Detection Type", "gd-mail-queue"),
            'buddypress_groups-membership-request' => _x("BuddyPress: Groups Membership Request", "Email Detection Type", "gd-mail-queue"),
            'buddypress_groups-membership-request-accepted' => _x("BuddyPress: Groups Membership Request Accepted", "Email Detection Type", "gd-mail-queue"),
            'buddypress_groups-membership-request-rejected' => _x("BuddyPress: Groups Membership Request Rejected", "Email Detection Type", "gd-mail-queue"),
            'buddypress_groups-member-promoted' => _x("BuddyPress: Groups Member Promoted", "Email Detection Type", "gd-mail-queue"),
            'buddypress_groups-invitation' => _x("BuddyPress: Groups Invitation", "Email Detection Type", "gd-mail-queue"),
            'buddypress_groups-at-message' => _x("BuddyPress: Groups At Message", "Email Detection Type", "gd-mail-queue"),
            'buddypress_activity-at-message' => _x("BuddyPress: Activity At Message", "Email Detection Type", "gd-mail-queue")
        ));
    }

    public function get_type_label($type) {
        return isset($this->supported_types[$type]) ? $this->supported_types[$type] : _x("Unspecified", "Email Detection Type", "gd-mail-queue");
    }

    public function wp_mail($atts) {
        if (is_string($atts['headers']) && !empty($atts['headers'])) {
            if (strpos($atts['headers'], 'X-WPCF7-Content-Type') !== false) {
                gdmaq_mailer()->set_current_type('contact_form_7_email');
            }
        }

        return $atts;
    }

    public function wp_comment_notify_postauthor($return) {
        gdmaq_mailer()->set_current_type('wp_comment_notify_postauthor');

        return $return;
    }

    public function wp_comment_notify_moderator($return) {
        gdmaq_mailer()->set_current_type('wp_comment_notify_moderator');

        return $return;
    }

    public function wp_email_change_confirmation($return) {
        gdmaq_mailer()->set_current_type('wp_email_change_confirmation');

        return $return;
    }

    public function wp_email_change_notification($return) {
        gdmaq_mailer()->set_current_type('wp_email_change_notification');

        return $return;
    }

    public function wp_retrieve_password_message($return) {
        gdmaq_mailer()->set_current_type('wp_retrieve_password_message');

        return $return;
    }

    public function wp_password_change_notification($return) {
        gdmaq_mailer()->set_current_type('wp_password_change_notification');

        return $return;
    }

    public function wp_signup_blog_confirmation($return) {
        gdmaq_mailer()->set_current_type('wpmu_signup_blog_confirmation');

        return $return;
    }

    public function wp_signup_user_confirmation($return) {
        gdmaq_mailer()->set_current_type('wpmu_signup_user_confirmation');

        return $return;
    }

    public function wp_privacy_request_confirmation($return) {
        gdmaq_mailer()->set_current_type('wp_privacy_request_confirmation');

        return $return;
    }

    public function wp_privacy_erasure_fulfillment($return) {
        gdmaq_mailer()->set_current_type('wp_privacy_erasure_fulfillment');

        return $return;
    }

    public function wp_send_user_request($return) {
        gdmaq_mailer()->set_current_type('wp_send_user_request');

        return $return;
    }

    public function wp_new_admin_email_content($return) {
        gdmaq_mailer()->set_current_type('wp_site_admin_email_change_attempt');

        return $return;
    }

    public function wp_site_admin_email_change($return) {
        gdmaq_mailer()->set_current_type('wp_site_admin_email_change');

        return $return;
    }

    public function wp_new_user_notification_admin($return) {
        gdmaq_mailer()->set_current_type('wp_new_user_notification_admin');

        return $return;
    }

    public function wp_new_user_notification($return) {
        gdmaq_mailer()->set_current_type('wp_new_user_notification');

        return $return;
    }

    public function wp_password_change_notification_admin($return) {
        gdmaq_mailer()->set_current_type('wp_password_change_notification_admin');

        return $return;
    }

    public function wp_recovery_mode_email($return) {
        gdmaq_mailer()->set_current_type('wp_recovery_mode_email');

        return $return;
    }

    public function wp_auto_core_update_email($return) {
        gdmaq_mailer()->set_current_type('wp_auto_core_update_email');

        return $return;
    }

    public function wp_auto_plugin_theme_update_email($return) {
        gdmaq_mailer()->set_current_type('wp_auto_plugin_theme_update_email');

        return $return;
    }

    public function wp_automatic_updates_debug_email($return) {
        gdmaq_mailer()->set_current_type('wp_automatic_updates_debug_email');

        return $return;
    }

    public function wp_privacy_personal_data_email_headers($return) {
        gdmaq_mailer()->set_current_type('wp_privacy_personal_data_email_headers');

        return $return;
    }

    public function wpmu_delete_site_email_content($return) {
        gdmaq_mailer()->set_current_type('wpmu_delete_site_email_content');

        return $return;
    }

    public function wpmu_welcome_blog($return) {
        gdmaq_mailer()->set_current_type('wpmu_welcome_blog');

        return $return;
    }

    public function wpmu_welcome_user($return) {
        gdmaq_mailer()->set_current_type('wpmu_welcome_user');

        return $return;
    }

    public function wpmu_new_blog_siteadmin($return) {
        gdmaq_mailer()->set_current_type('wpmu_new_blog_siteadmin');

        return $return;
    }

    public function wpmu_new_user_siteadmin($return) {
        gdmaq_mailer()->set_current_type('wpmu_new_user_siteadmin');

        return $return;
    }

    public function wpmu_network_admin_email_confirmation($return) {
        gdmaq_mailer()->set_current_type('wpmu_network_admin_email_confirmation');

        return $return;
    }

    public function wpmu_network_admin_email_notification($return) {
        gdmaq_mailer()->set_current_type('wpmu_network_admin_email_notification');

        return $return;
    }

    public function wpmem_email_filter($return) {
        $email_type = isset($return['tag']) && !empty($return['tag']) ? $return['tag'] : 'custom';
        gdmaq_mailer()->set_current_type('wpmem_'.$email_type);

        return $return;
    }

    public function wpmem_notify_filter($return) {
        gdmaq_mailer()->set_current_type('wpmem_admin_notify');

        return $return;
    }

    public function asgarosforum_subscriber_mails_new_post($return) {
        gdmaq_mailer()->set_current_type('asgaros_subscriber_new_post');

        return $return;
    }

    public function asgarosforum_subscriber_mails_new_topic($return) {
        gdmaq_mailer()->set_current_type('asgaros_subscriber_new_topic');

        return $return;
    }

    public function rank_math_auto_update_email($return) {
        gdmaq_mailer()->set_current_type('rank_math_auto_update_email');

        return $return;
    }

    public function bbpress_new_reply_in_topic() {
        gdmaq_mailer()->set_current_type('bbpress_new_reply_in_topic');
    }

    public function bbpress_new_topic_in_forum() {
        gdmaq_mailer()->set_current_type('bbpress_new_topic_in_forum');
    }

    public function bbpress_topic_auto_close() {
        gdmaq_mailer()->set_current_type('bbpress_topic_auto_close');
    }

    public function bbpress_topic_manual_close() {
        gdmaq_mailer()->set_current_type('bbpress_topic_manual_close');
    }

    public function bbpress_topic_edit() {
        gdmaq_mailer()->set_current_type('bbpress_topic_edit');
    }

    public function bbpress_reply_edit() {
        gdmaq_mailer()->set_current_type('bbpress_reply_edit');
    }

    public function bbpress_new_topic_moderators() {
        gdmaq_mailer()->set_current_type('bbpress_new_topic_moderators');
    }

    public function buddypress_send_email(&$email, $email_type) {
        gdmaq_mailer()->set_current_type('buddypress_'.$email_type);
    }

    public function gdpol_digest_notify_moderators($data) {
        gdmaq_mailer()->set_current_type('gdpol_digest_notify_moderators');
    }

    public function gdpol_digest_notify_author($data) {
        gdmaq_mailer()->set_current_type('gdpol_digest_notify_author');
    }

    public function gdpol_instant_notify($data) {
        gdmaq_mailer()->set_current_type('gdpol_instant_notify');
    }
}
