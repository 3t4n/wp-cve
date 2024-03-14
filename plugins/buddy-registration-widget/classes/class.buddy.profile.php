<?php

class Buddy_Profile extends Buddy_Registration
{

    /**
     * Instance of the class
     * @var type 
     */
    static $instance;

    /**
     * Constructor of the Class
     * Author : Yogesh Pawar
     * Date : 4th Feb 2019
     */
    function __construct()
    {

        add_action('admin_init', array(&$this, 'wpCustomBuddyOptions'));

        //record on new avatar upload
        add_action('xprofile_avatar_uploaded', array(&$this, 'avatarLogUploaded'));

        //on avatar delete, remove the log
        add_action('bp_core_delete_existing_avatar', array(&$this, 'avatarLogDeleted'));

        add_action('bp_template_redirect', array(&$this, 'checkOrRedirect'), 1);

        //load languages file
        add_action('bp_init', array(&$this, 'loadTextDomain'));

        //Remove actvity
        add_action('bp_setup_nav', array(&$this, 'removeBPActivityTab'), 201);

        add_action('admin_menu', array(&$this, 'customBPMenu'));

        //skipping if admin has disabled profile cover image
        if (esc_attr(get_option('buddy_member_cover')) == 1) {
            add_filter('bp_is_profile_cover_image_active', '__return_false');
        }

        //skipping if admin has disabled group cover image
        if (esc_attr(get_option('buddy_group_cover')) == 1) {
            add_filter('bp_is_groups_cover_image_active', '__return_false');
        }
    }

    /**
     * Function for Activity Tab
     * @global type $bp
     * Author : Yogesh Pawar
     * Date : 5th Sept
     */
    function removeBPActivityTab()
    {
        if (esc_attr(get_option('buddy_hide_activity_tab')) == 1) {
            global $bp;
            bp_core_remove_nav_item('activity');
        }
    }

    /**
     * Singleton Instance
     * @return type
     * Author : Yogesh Pawar
     * Date : 4th Feb 2019
     */
    static function get_instance()
    {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Function to load language
     * Author : Yogesh Pawar
     * Date : 4th Feb 2019
     */
    function loadTextDomain()
    {
        load_plugin_textdomain('bp-force-profile-photo', false, plugin_basename(dirname(__FILE__)) . '/languages');
    }

    /**
     * Checks if a user has uploaded avatar and redirects to upload page if not
     * @return type
     * Author : Yogesh Pawar
     * Date : 4th Feb 2019
     */
    function checkOrRedirect()
    {

        if (!is_user_logged_in() || is_super_admin()) {
            return;
        }

        $user_id = get_current_user_id();

        //skipping if admin has allowed all users to browse the website without the need for an profile pic
        if (esc_attr(get_option('buddy_profile_image')) == 1) {
            return;
        }

        //should we skip check for the current user?
        if ($this->skipCheck($user_id)) {
            
        }
        //if we are here, the user is logged in
        if ($this->hasUploadedAvatar($user_id)) {
            return;
        }

        if (bp_is_my_profile() && bp_is_user_change_avatar()) {
            return;
        }

        bp_core_add_message(__('Please upload your profile photo to start using this site.', 'bp-force-profile-photo'), 'error');
        //if we are here, user has not uploaded an avatar, let us redirect them to upload avatar page
        bp_core_redirect(bp_loggedin_user_domain() . buddypress()->profile->slug . '/change-avatar/');
    }

    /**
     * Function to check Skip check level
     * @global type $wpdb
     * @param type $user_id
     * @return boolean
     * Author : Yogesh Pawar
     * Date : 4th Feb 2019
     */
    function skipCheck($user_id)
    {

        $meta_keys = array(
            '_fbid', //for kleo
            'fb_account_id', //for BuddyPress Facebook Connect Plus
            'oa_social_login_user_picture', //social login plugin
            'oa_social_login_user_thumbnail', //social login plugin
            'wsl_current_user_image', //WordPress social login plugin, may not work in some case
            'facebook_avatar_full', //wp-fb-autoconnect
            'facebook_uid' //for wp-fb-autoconnect
        );
        //use the below filter to remove/add any extra key
        $meta_keys = apply_filters('bp_force_profile_photo_social_meta', $meta_keys);

        if (empty($meta_keys)) {
            return false; // we do not need to skip the test
        }

        $meta_keys = array_map('esc_sql', $meta_keys);

        $list = '\'' . join('\', \'', $meta_keys) . '\'';

        $meta_list = '(' . $list . ')';

        global $wpdb;

        $has_meta = $wpdb->get_col($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE meta_key IN {$meta_list} and user_id = %d ", $user_id));

        if (!empty($has_meta)) {
            return true;
        }

        return false;
    }

    /**
     * On New Avatar Upload, add the usermeta to reflect that user has uploaded an avatar
     * Author : Yogesh Pawar
     * Date : 4th Feb 2014
     */
    function avatarLogUploaded()
    {

        bp_update_user_meta(get_current_user_id(), 'has_avatar', 1);
    }

    /**
     * On Delete Avatar, delete the user meta to reflecte the change
     * @param type $args
     * @return type
     * Author : Yogesh Pawar
     * Date : 4th Feb 2019
     */
    function avatarLogDeleted($args)
    {

        if ($args['object'] != 'user') {
            return;
        }

        bp_delete_user_meta(get_current_user_id(), 'has_avatar');
    }

    /**
     * has this user uploaded an avatar?
     * @param type $user_id
     * @return type
     * Author : Yogesh Pawar
     * Date : 4th Feb 2019
     */
    function hasUploadedAvatar($user_id)
    {

        $has_avatar = bp_get_user_meta($user_id, 'has_avatar', true);

        if (!$has_avatar) {
            $has_avatar = bp_get_user_has_avatar($user_id); //fallback
        }

        return $has_avatar;
    }

    /**
     * Function to Initiliase Custom Buddy Options
     * Author : Yogesh Pawar
     * Date : 5th Feb 2019
     */
    function wpCustomBuddyOptions()
    {

        register_setting('buddy-ct-group', 'buddy_member_cover');
        register_setting('buddy-ct-group', 'buddy_group_cover');
        register_setting('buddy-ct-group', 'buddy_profile_image');
        register_setting('buddy-ct-group', 'buddy_hide_activity_tab');
        register_setting('buddy-ct-group', 'buddy_custom_widget_template');
        register_setting('buddy-ct-group', 'buddy_custom_shortcode_template');
    }
}

?>