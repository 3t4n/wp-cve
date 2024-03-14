<?php

class Youzify_Basic_Infos {

    function __construct() {
    }

    /**
     * Profile Picture Settings.
     */
    function profile_picture() {

        wp_enqueue_style( 'youzify-bp-uploader' );

        global $Youzify_Settings;

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Profile Picture', 'youzify' ),
                'id'    => 'profile-picture',
                'icon'  => 'fas fa-user-circle',
                'type'  => 'bpDiv'
            )
        );

        echo '<div class="youzify-uploader-change-item youzify-change-avatar-item">';
        bp_get_template_part( 'members/single/profile/change-avatar' );
        echo '</div>';

        $Youzify_Settings->get_field( array( 'type' => 'endbpDiv' ) );

    }

    /**
     * User Capabilities Settings.
     */
    function user_capabilities() {

        global $Youzify_Settings;

        do_action( 'bp_before_member_settings_template' );

        $Youzify_Settings->get_field(
            array(
                'form_action'   => bp_displayed_user_url() . bp_get_settings_slug() . '/capabilities/',
                'title'         => __( 'User Capabilities Settings', 'youzify' ),
                'form_name'     => 'account-capabilities-form',
                'submit_id'     => 'capabilities-submit',
                'button_name'   => 'capabilities-submit',
                'id'            => 'capabilities-settings',
                'icon'          => 'fas fa-wrench',
                'type'          => 'open',
            )
        );

        bp_get_template_part( 'members/single/settings/capabilities' );

        $Youzify_Settings->get_field(
            array(
                'type' => 'close',
                'hide_action' => true,
                'submit_id'     => 'capabilities-submit',
                'button_name'   => 'capabilities-submit'
            )
        );

        do_action( 'bp_after_member_settings_template' );

    }

    /**
     * Profile Fields Group Settings.
     */
    function group_fields() {

        global $Youzify_Settings, $group;

        $group_data = BP_XProfile_Group::get(
            array( 'profile_group_id' => bp_get_current_profile_group_id() )
        );

        $Youzify_Settings->get_field(
            array(
                'icon'  => youzify_get_xprofile_group_icon( $group_data[0]->id ),
                'title' => $group_data[0]->name,
                'id'    => 'profile-picture',
                'type'  => 'open'
            )
        );

        bp_get_template_part( 'members/single/profile/edit' );

        $Youzify_Settings->get_field( array( 'type' => 'close' ) );

    }

    /**
     * Account Privacy Settings.
     */
    function account_privacy() {

        global $Youzify_Settings;

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Account Privacy', 'youzify' ),
                'id'    => 'account-privacy',
                'icon'  => 'fas fa-user-secret',
                'type'  => 'open'
            )
        );

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Private Account', 'youzify' ),
                'desc'  => __( 'Make your profile private, only friends can access.', 'youzify' ),
                'id'    => 'youzify_enable_private_account',
                'type'  => 'checkbox',
                'std'   => 'off',
            ), true
        );

        if ( youzify_is_woocommerce_active() ) {
            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Activity Stream Purchases', 'youzify' ),
                    'desc'  => __( 'Post my purchases in the activity stream.', 'youzify' ),
                    'id'    => 'youzify_wc_purchase_activity',
                    'type'  => 'checkbox',
                    'std'   => apply_filters( 'youzify_wc_purchase_activity', 'on' ),
                ), true, 'youzify_options'
            );
        }

        do_action( 'youzify_user_account_privacy_settings', $Youzify_Settings );

        $Youzify_Settings->get_field( array( 'type' => 'close' ) );

    }

    /**
     * Delete Account Settings.
     */
    function data() {

        global $Youzify_Settings;

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Export Data', 'youzify' ),
                'id'    => 'export-data',
                'icon'  => 'fas fa-file-export',
                'type'  => 'bpDiv'
            )
        );

        bp_get_template_part( 'members/single/settings/data' );

        $Youzify_Settings->get_field( array( 'type' => 'endbpDiv' ) );

    }

    /**
     * Delete Account Settings.
     */
    function delete_account() {

        global $Youzify_Settings;

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Delete Account', 'youzify' ),
                'id'    => 'delete-account',
                'icon'  => 'fas fa-trash-alt',
                'type'  => 'bpDiv'
            )
        );

        echo '<div class="youzify-delete-account-item">';
        bp_get_template_part( 'members/single/settings/delete-account' );
        echo '</div>';

        $Youzify_Settings->get_field( array( 'type' => 'endbpDiv' ) );

    }

    /**
     * Profile Notifications Settings.
     */
    function notifications_settings() {

        global $Youzify_Settings;

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Notifications Settings', 'youzify' ),
                'id'    => 'notifications-settings',
                'icon'  => 'fas fa-bell',
                'type'  => 'open'
            )
        );

        // # Activity Notifications.

        if ( bp_is_active( 'activity' ) ) :

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Mentions Notifications', 'youzify' ),
                    'desc'  => __( 'Email me when a member mentions me in a post', 'youzify' ),
                    'id'    => 'youzify_notification_activity_new_mention',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzify_notifications'
            );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Replies Notifications', 'youzify' ),
                    'desc'  => __( 'Mail me when a member replies to a post or comment I have posted', 'youzify' ),
                    'id'    => 'youzify_notification_activity_new_reply',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzify_notifications'
            );

        endif;

        // # Messages Notifications.

        if ( bp_is_active( 'messages' ) ) :

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Messages Notifications', 'youzify' ),
                    'desc'  => __( 'Email me when a member sends me a new message', 'youzify' ),
                    'id'    => 'youzify_notification_messages_new_message',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzify_notifications'
            );

        endif;

        // # Friends Notifications.

        if ( bp_is_active( 'friends' ) ) :

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Friendship Requested Notifications', 'youzify' ),
                    'desc'  => __( 'Email me when a member sends me a friendship request', 'youzify' ),
                    'id'    => 'youzify_notification_friends_friendship_request',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzify_notifications'
            );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Friendship Accepted Notifications', 'youzify' ),
                    'desc'  => __( 'Email me when a member accepts my friendship request', 'youzify' ),
                    'id'    => 'youzify_notification_friends_friendship_accepted',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzify_notifications'
            );

        endif;

        // # Groups Notifications.

        if ( bp_is_active( 'groups' ) ) :

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Group Invitations Notifications', 'youzify' ),
                    'desc'  => __( 'Email me when a member invites me to join a group', 'youzify' ),
                    'id'    => 'youzify_notification_groups_invite',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzify_notifications'
            );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Group Information Notifications', 'youzify' ),
                    'desc'  => __( 'Email me when a group information is updated', 'youzify' ),
                    'id'    => 'youzify_notification_groups_group_updated',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzify_notifications'
            );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Group Admin Promotion Notifications', 'youzify' ),
                    'desc'  => __( 'Email me when I am promoted to a group administrator or moderator', 'youzify' ),
                    'id'    => 'youzify_notification_groups_admin_promotion',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzify_notifications'
            );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Join Group Notifications', 'youzify' ),
                    'desc'  => __( 'Email me when a member requests to join a private group for which I am an admin', 'youzify' ),
                    'id'    => 'youzify_notification_groups_membership_request',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzify_notifications'
            );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Group Membership Request Notifications', 'youzify' ),
                    'desc'  => __( 'Email me when my request to join a group has been approved or denied', 'youzify' ),
                    'id'    => 'youzify_notification_membership_request_completed',
                    'type'  => 'checkbox',
                    'std'   => 'on',
                ), true, 'youzify_notifications'
            );

        endif;

        $Youzify_Settings->get_field( array( 'type' => 'close' ) );

    }

    /**
     * Profile Cover Settings.
     */
    function profile_cover() {

        // Cover Image Uploader Script.
        wp_enqueue_style( 'youzify-bp-uploader' );
        bp_attachments_enqueue_scripts( 'BP_Attachment_Cover_Image' );

        global $Youzify_Settings;

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Profile Cover', 'youzify' ),
                'id'    => 'profile-cover',
                'icon'  => 'fas fa-camera-retro',
                'type'  => 'bpDiv'
            )
        );

        echo '<div class="youzify-uploader-change-item youzify-change-cover-item">';
        bp_get_template_part( 'members/single/profile/change-cover-image' );
        echo '</div>';

        $Youzify_Settings->get_field( array( 'type' => 'endbpDiv' ) );

    }

    /**
     * Password Settings.
     */
    function general() {

        global $Youzify_Settings;


        /**
         * Fires after the display of the submit button for user general settings saving.
         *
         * @since 1.5.0
         */
        do_action( 'bp_core_general_settings_after_submit' );

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Email & Password', 'youzify' ),
                'id'    => 'change-password',
                'icon'  => 'fas fa-lock',
                'type'  => 'open'
            )
        );

        if ( ! is_super_admin() ) {

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Current Password', 'youzify' ),
                    'desc'  => __( 'Required to update email or change current password', 'youzify' ),
                    'id'    => 'pwd',
                    'no_options' => true,
                    'type'  => 'password'
                ), true
            );

        }

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Account Email', 'youzify' ),
                'desc'  => __( 'Change your account email', 'youzify' ),
                'std'   => bp_get_displayed_user_email(),
                'id'    => 'email',
                'no_options' => true,
                'type'  => 'text' ), true
            );


        $Youzify_Settings->get_field(
            array(
                'title' => __( 'New Password', 'youzify' ),
                'desc'  => __( 'Type your new password', 'youzify' ),
                'id'    => 'pass1',
                'no_options' => true,
                'type'  => 'password' ), true
            );

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Confirm Password', 'youzify' ),
                'desc'  => __( 'Confirm your new password', 'youzify' ),
                'id'    => 'pass2',
                'no_options' => true,
                'type'  => 'password'
            ), true
        );

        wp_nonce_field( 'bp_settings_general' );

        /**
         * Fires before the display of the submit button for user general settings saving.
         *
         * @since 1.5.0
         */
        do_action( 'bp_core_general_settings_before_submit' );

        $Youzify_Settings->get_field( array( 'type' => 'close', 'button_name' => 'submit', 'hide_action' => true ) );

    }

    /**
     * Block Members Plugin.
     */
    function members_block() {

        global $Youzify_Settings;

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Block Members', 'youzify' ),
                'id'    => 'block-member',
                'icon'  => 'fas fa-ban',
                'type'  => 'bpDiv'
            )
        );

        bp_my_blocked_members_screen();

        $Youzify_Settings->get_field( array( 'type' => 'endbpDiv' ) );

    }

    /**
     * Change Username Plugin.
     */
    function change_username() {

        $bp = buddypress();

        global $Youzify_Settings;

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Change Username', 'youzify' ),
                'button_name' => 'change_username_submit',
                'id'    => 'change-username',
                'form_name' => 'username_changer',
                'icon'  => 'fas fa-sync-alt',
                'type'  => 'open'
            )
        );

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Current Username', 'bp-username-changer' ),
                'desc'  => __( 'This is your current username', 'bp-username-changer' ),
                'id'    => 'current_user_name',
                'no_options' => true,
                'type'  => 'text',
                'disabled' => true,
                'std'   => esc_attr( $bp->displayed_user->userdata->user_login )
            ), true
        );

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'New Username', 'bp-username-changer' ),
                'desc'  => __( 'Enter the new username of your choice', 'bp-username-changer' ),
                'id'    => 'new_user_name',
                'no_options' => true,
                'type'  => 'text'
            ), true
        );

        wp_nonce_field( 'bp-change-username' );

        $Youzify_Settings->get_field( array( 'type' => 'close', 'button_name' => 'change_username_submit', 'hide_action' => true ) );

    }

    /**
     * Buddypress Deactivator Plugin.
     */
    function account_deactivator() {

        $user_id = bp_displayed_user_id();

        // not used is_displayed_user_inactive to avoid conflict.
        $is_inactive = bp_account_deactivator()->is_inactive( $user_id ) ? 1 : 0;

        if ( $is_inactive ) {
            $class= 'inactive';
            $message = __( 'Activate your account', 'bp-deactivate-account' );
            $status  = __( 'Deactivated', 'bp-deactivate-account' );
            update_user_meta( bp_displayed_user_id(), '_bp_account_deactivator_status', 0 );

        } else {

            $class= 'active';
            $message = __( 'Deactivate your account', 'bp-deactivate-account' );
            $status  = __( 'Active', 'bp-deactivate-account' );
            update_user_meta( bp_displayed_user_id(), '_bp_account_deactivator_status', 1 );
        }

        $bp = buddypress();

        global $Youzify_Settings;

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Account Status', 'bp-deactivate-account' ),
                'button_name' => 'bp_account_deactivator_update_settings',
                'id'    => 'bp-account-deactivator-settings',
                'form_name' => 'bp-account-deactivator-settings',
                'icon'  => 'fas fa-user-cog',
                'button_value' => 'save',
                'type'  => 'open'
            )
        );

        echo '<div class="youzify-bp-deactivator-' . $class . '">' . __( 'Your current account status: ', 'bp-deactivate-account' ) . '<span>' . $status . '</span></div>';

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Update Status', 'bp-deactivate-account' ),
                'desc'  => __( 'If you select deactivate, you will be hidden from the users.', 'bp-deactivate-account' ),
                'id'    => '_bp_account_deactivator_status',
                'opts'  => array( '1' => __( 'Activate', 'bp-deactivate-account' ), '0' => __( 'Deactivate', 'bp-deactivate-account' )),
                'no_options' => true,
                'type'  => 'radio',
            ), true
        );

        wp_nonce_field( 'bp-account-deactivator' );

        $Youzify_Settings->get_field( array( 'type' => 'close', 'button_name' => 'bp_account_deactivator_update_settings', 'hide_action' => true, 'button_value' => 'save' ) );

    }
}