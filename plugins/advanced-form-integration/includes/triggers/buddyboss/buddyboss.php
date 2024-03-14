<?php

// Get BuddyBoss triggers
function adfoin_buddyboss_get_forms( $form_provider ) {
    if( $form_provider != 'buddyboss' ) {
        return;
    }

    $triggers = array(
        'friends_friendship_accepted' => __( 'Friendship Accepted', 'advanced-form-integration' ),
        'friends_friendship_requested' => __( 'Friendship Requested', 'advanced-form-integration' ),
        'bbp_new_topic' => __( 'New Topic', 'advanced-form-integration' ),
        'bbp_new_reply' => __( 'New Reply', 'advanced-form-integration' ),

        'groups_join_group' => __( 'Join Group', 'advanced-form-integration' ),
        'groups_membership_accepted' => __( 'Membership Accepted', 'advanced-form-integration' ),
        'groups_accept_invite' =>  __( 'Accept Invite', 'advanced-form-integration' ),
        'groups_leave_group' => __( 'Leave Group', 'advanced-form-integration' ),
        'groups_remove_member' => __( 'Remove Member', 'advanced-form-integration' ),
        'bp_groups_posted_update' => __( 'Posted Update', 'advanced-form-integration' ),
        
        'groups_membership_requested' => __( 'Membership Requested', 'advanced-form-integration' ),
        'bp_member_invite_submit' => __( 'Member Invite Submit', 'advanced-form-integration' ),
        'xprofile_avatar_uploaded' => __( 'Avatar Uploaded', 'advanced-form-integration' ),
        'bp_core_activated_user' => __( 'Activated User', 'advanced-form-integration' ),
        'bp_invites_member_invite_activate_user' => __( 'Invite Activate User', 'advanced-form-integration' ),
        'bp_invites_member_invite_mark_register_user' => __( 'Invite Mark Register User', 'advanced-form-integration' ),
    );

    return $triggers;
}

// Get BuddyBoss fields
function adfoin_buddyboss_get_form_fields( $form_provider, $form_id ) {
    if( $form_provider != 'buddyboss' ) {
        return;
    }

    $fields = array();

    if( in_array( $form_id, array( 'friends_friendship_accepted', 'friends_friendship_requested' ) ) ) {
        $fields = array(
            'first_name' => __( 'First Name', 'advanced-form-integration' ),
            'last_name' => __( 'Last Name', 'advanced-form-integration' ),
            'nickname' => __( 'Nickname', 'advanced-form-integration' ),
            'avatar_url' => __( 'Avatar URL', 'advanced-form-integration' ),
            'user_email' => __( 'User Email', 'advanced-form-integration' ),
            'friend_id' => __( 'Friend ID', 'advanced-form-integration' ),
            'friend_first_name' => __( 'Friend First Name', 'advanced-form-integration' ),
            'friend_last_name' => __( 'Friend Last Name', 'advanced-form-integration' ),
            'friend_nickname' => __( 'Friend Nickname', 'advanced-form-integration' ),
            'friend_avatar_url' => __( 'Friend Avatar URL', 'advanced-form-integration' ),
            'friend_email' => __( 'Friend Email', 'advanced-form-integration' ),
        );
    } elseif( in_array( $form_id, array( 'bbp_new_topic', 'bbp_new_reply' ) ) ) {
        $fields = array(
            'topic_id' => __( 'Topic ID', 'advanced-form-integration' ),
            'topic_title' => __( 'Topic Title', 'advanced-form-integration' ),
            'topic_content' => __( 'Topic Content', 'advanced-form-integration' ),
            'topic_url' => __( 'Topic URL', 'advanced-form-integration' ),
            'topic_author_id' => __( 'Topic Author ID', 'advanced-form-integration' ),
            'topic_author_first_name' => __( 'Topic Author First Name', 'advanced-form-integration' ),
            'topic_author_last_name' => __( 'Topic Author Last Name', 'advanced-form-integration' ),
            'topic_author_nickname' => __( 'Topic Author Nickname', 'advanced-form-integration' ),
            'topic_author_avatar_url' => __( 'Topic Author Avatar URL', 'advanced-form-integration' ),
            'topic_author_email' => __( 'Topic Author Email', 'advanced-form-integration' ),
            'topic_forum_id' => __( 'Topic Forum ID', 'advanced-form-integration' ),
            'topic_forum_title' => __( 'Topic Forum Title', 'advanced-form-integration' ),
            'topic_forum_slug' => __( 'Topic Forum Slug', 'advanced-form-integration' ),
            'topic_forum_description' => __( 'Topic Forum Description', 'advanced-form-integration' ),
            'topic_forum_url' => __( 'Topic Forum URL', 'advanced-form-integration' ),
        );

        if( $form_id == 'bbp_new_reply' ) {
            $fields['reply_id'] = __( 'Reply ID', 'advanced-form-integration' );
            $fields['reply_content'] = __( 'Reply Content', 'advanced-form-integration' );
            $fields['reply_url'] = __( 'Reply URL', 'advanced-form-integration' );
            $fields['reply_author_id'] = __( 'Reply Author ID', 'advanced-form-integration' );
            $fields['reply_author_first_name'] = __( 'Reply Author First Name', 'advanced-form-integration' );
            $fields['reply_author_last_name'] = __( 'Reply Author Last Name', 'advanced-form-integration' );
            $fields['reply_author_nickname'] = __( 'Reply Author Nickname', 'advanced-form-integration' );
            $fields['reply_author_avatar_url'] = __( 'Reply Author Avatar URL', 'advanced-form-integration' );
            $fields['reply_author_email'] = __( 'Reply Author Email', 'advanced-form-integration' );
        }
    } elseif( in_array( $form_id, array( 'groups_join_group', 'groups_membership_accepted', 'groups_accept_invite', 'groups_leave_group', 'groups_remove_member' ) ) ) {
        $fields = array(
            'group_id' => __( 'Group ID', 'advanced-form-integration' ),
            'group_name' => __( 'Group Name', 'advanced-form-integration' ),
            'group_slug' => __( 'Group Slug', 'advanced-form-integration' ),
            'group_description' => __( 'Group Description', 'advanced-form-integration' ),
            'group_url' => __( 'Group URL', 'advanced-form-integration' ),
            'group_creator_id' => __( 'Group Creator ID', 'advanced-form-integration' ),
            'group_creator_first_name' => __( 'Group Creator First Name', 'advanced-form-integration' ),
            'group_creator_last_name' => __( 'Group Creator Last Name', 'advanced-form-integration' ),
            'group_creator_nickname' => __( 'Group Creator Nickname', 'advanced-form-integration' ),
            'group_creator_avatar_url' => __( 'Group Creator Avatar URL', 'advanced-form-integration' ),
            'group_creator_email' => __( 'Group Creator Email', 'advanced-form-integration' ),
            'group_member_id' => __( 'Group Member ID', 'advanced-form-integration' ),
            'group_member_first_name' => __( 'Group Member First Name', 'advanced-form-integration' ),
            'group_member_last_name' => __( 'Group Member Last Name', 'advanced-form-integration' ),
            'group_member_nickname' => __( 'Group Member Nickname', 'advanced-form-integration' ),
            'group_member_avatar_url' => __( 'Group Member Avatar URL', 'advanced-form-integration' ),
            'group_member_email' => __( 'Group Member Email', 'advanced-form-integration' ),
        );

        if( $form_id == 'groups_membership_accepted' || $form_id == 'groups_accept_invite' ) {
            $fields['group_member_role'] = __( 'Group Member Role', 'advanced-form-integration' );
        }
    } elseif( in_array( $form_id, array( 'bp_groups_posted_update' ) ) ) {
        $fields = array(
            'group_id' => __( 'Group ID', 'advanced-form-integration' ),
            'group_name' => __( 'Group Name', 'advanced-form-integration' ),
            'group_slug' => __( 'Group Slug', 'advanced-form-integration' ),
            'group_description' => __( 'Group Description', 'advanced-form-integration' ),
            'group_url' => __( 'Group URL', 'advanced-form-integration' ),
            'group_creator_id' => __( 'Group Creator ID', 'advanced-form-integration' ),
            'group_creator_first_name' => __( 'Group Creator First Name', 'advanced-form-integration' ),
            'group_creator_last_name' => __( 'Group Creator Last Name', 'advanced-form-integration' ),
            'group_creator_nickname' => __( 'Group Creator Nickname', 'advanced-form-integration' ),
            'group_creator_avatar_url' => __( 'Group Creator Avatar URL', 'advanced-form-integration' ),
            'group_creator_email' => __( 'Group Creator Email', 'advanced-form-integration' ),
            'group_member_id' => __( 'Group Member ID', 'advanced-form-integration' ),
            'group_member_first_name' => __( 'Group Member First Name', 'advanced-form-integration' ),
            'group_member_last_name' => __( 'Group Member Last Name', 'advanced-form-integration' ),
            'group_member_nickname' => __( 'Group Member Nickname', 'advanced-form-integration' ),
            'group_member_avatar_url' => __( 'Group Member Avatar URL', 'advanced-form-integration' ),
            'group_member_email' => __( 'Group Member Email', 'advanced-form-integration' ),
            'activity_id' => __( 'Update ID', 'advanced-form-integration' ),
            'activity_content' => __( 'Update Content', 'advanced-form-integration' ),
            'activity_url' => __( 'Update URL', 'advanced-form-integration' ),
            'activity_author_id' => __( 'Update Author ID', 'advanced-form-integration' ),
            'activity_author_first_name' => __( 'Update Author First Name', 'advanced-form-integration' ),
            'activity_author_last_name' => __( 'Update Author Last Name', 'advanced-form-integration' ),
            'activity_author_nickname' => __( 'Update Author Nickname', 'advanced-form-integration' ),
            'activity_author_avatar_url' => __( 'Update Author Avatar URL', 'advanced-form-integration' ),
            'activity_author_email' => __( 'Update Author Email', 'advanced-form-integration' ),
        );
    } elseif( in_array( $form_id, array( 'groups_membership_requested' ) ) ) {
        $fields = array(
            'group_id' => __( 'Group ID', 'advanced-form-integration' ),
            'group_name' => __( 'Group Name', 'advanced-form-integration' ),
            'group_slug' => __( 'Group Slug', 'advanced-form-integration' ),
            'group_description' => __( 'Group Description', 'advanced-form-integration' ),
            'group_url' => __( 'Group URL', 'advanced-form-integration' ),
            'group_creator_id' => __( 'Group Creator ID', 'advanced-form-integration' ),
            'group_creator_first_name' => __( 'Group Creator First Name', 'advanced-form-integration' ),
            'group_creator_last_name' => __( 'Group Creator Last Name', 'advanced-form-integration' ),
            'group_creator_nickname' => __( 'Group Creator Nickname', 'advanced-form-integration' ),
            'group_creator_avatar_url' => __( 'Group Creator Avatar URL', 'advanced-form-integration' ),
            'group_creator_email' => __( 'Group Creator Email', 'advanced-form-integration' ),
            'group_member_id' => __( 'Group Member ID', 'advanced-form-integration' ),
            'group_member_first_name' => __( 'Group Member First Name', 'advanced-form-integration' ),
            'group_member_last_name' => __( 'Group Member Last Name', 'advanced-form-integration' ),
            'group_member_nickname' => __( 'Group Member Nickname', 'advanced-form-integration' ),
            'group_member_avatar_url' => __( 'Group Member Avatar URL', 'advanced-form-integration' ),
            'group_member_email' => __( 'Group Member Email', 'advanced-form-integration' ),
        );
    } elseif( in_array( $form_id, array( 'bp_member_invite_submit' ) ) ) {
        $fields = array(
            'invitee_id' => __( 'Invitee ID', 'advanced-form-integration' ),
            'invitee_first_name' => __( 'Invitee First Name', 'advanced-form-integration' ),
            'invitee_last_name' => __( 'Invitee Last Name', 'advanced-form-integration' ),
            'invitee_nickname' => __( 'Invitee Nickname', 'advanced-form-integration' ),
            'invitee_avatar_url' => __( 'Invitee Avatar URL', 'advanced-form-integration' ),
            'invitee_email' => __( 'Invitee Email', 'advanced-form-integration' ),
        );
    } elseif( in_array( $form_id, array( 'xprofile_avatar_uploaded' ) ) ) {
        $fields = array(
            'user_id' => __( 'User ID', 'advanced-form-integration' ),
            'first_name' => __( 'First Name', 'advanced-form-integration' ),
            'last_name' => __( 'Last Name', 'advanced-form-integration' ),
            'nickname' => __( 'Nickname', 'advanced-form-integration' ),
            'avatar_url' => __( 'Avatar URL', 'advanced-form-integration' ),
            'user_email' => __( 'User Email', 'advanced-form-integration' ),
        );
    } elseif( in_array( $form_id, array( 'bp_core_activated_user' ) ) ) {
        $fields = array(
            'user_id' => __( 'User ID', 'advanced-form-integration' ),
            'first_name' => __( 'First Name', 'advanced-form-integration' ),
            'last_name' => __( 'Last Name', 'advanced-form-integration' ),
            'nickname' => __( 'Nickname', 'advanced-form-integration' ),
            'avatar_url' => __( 'Avatar URL', 'advanced-form-integration' ),
            'user_email' => __( 'User Email', 'advanced-form-integration' ),
        );
    } elseif( in_array( $form_id, array( 'bp_invites_member_invite_activate_user' ) ) ) {
        $fields = array(
            'invitee_user_id' => __( 'Invitee User ID', 'advanced-form-integration' ),
            'invitee_first_name' => __( 'Invitee First Name', 'advanced-form-integration' ),
            'invitee_last_name' => __( 'Invitee Last Name', 'advanced-form-integration' ),
            'invitee_nickname' => __( 'Invitee Nickname', 'advanced-form-integration' ),
            'invitee_avatar_url' => __( 'Invitee Avatar URL', 'advanced-form-integration' ),
            'invitee_user_email' => __( 'Invitee User Email', 'advanced-form-integration' ),
            'inviter_user_id' => __( 'Inviter User ID', 'advanced-form-integration' ),
            'inviter_first_name' => __( 'Inviter First Name', 'advanced-form-integration' ),
            'inviter_last_name' => __( 'Inviter Last Name', 'advanced-form-integration' ),
            'inviter_nickname' => __( 'Inviter Nickname', 'advanced-form-integration' ),
            'inviter_avatar_url' => __( 'Inviter Avatar URL', 'advanced-form-integration' ),
            'inviter_user_email' => __( 'Inviter User Email', 'advanced-form-integration' ),
        );
    } elseif( in_array( $form_id, array( 'bp_invites_member_invite_mark_register_user' ) ) ) {
        $fields = array(
            'user_id' => __( 'User ID', 'advanced-form-integration' ),
            'first_name' => __( 'First Name', 'advanced-form-integration' ),
            'last_name' => __( 'Last Name', 'advanced-form-integration' ),
            'nickname' => __( 'Nickname', 'advanced-form-integration' ),
            'avatar_url' => __( 'Avatar URL', 'advanced-form-integration' ),
            'user_email' => __( 'User Email', 'advanced-form-integration' ),
        );
    }

    return $fields;

}

// Get User Data
function adfoin_buddyboss_get_userdata( $user_id ) {
    $user_data = array();
    $user      = get_userdata($user_id);

    if( $user ) {
        $user_data['first_name'] = $user->first_name;
        $user_data['last_name']  = $user->last_name;
        $user_data['nickname']   = $user->nickname;
        $user_data['avatar_url'] = get_avatar_url($user_id);
        $user_data['user_email'] = $user->user_email;
        $user_data['user_id']    = $user_id;
    }

    return $user_data;
}

// Send data
function adfoin_buddyboss_send_data( $saved_records, $posted_data ) {
    $job_queue = get_option( 'adfoin_general_settings_job_queue' );

    foreach ($saved_records as $record) {
        $action_provider = $record['action_provider'];

        if ($job_queue) {
            as_enqueue_async_action( "adfoin_{$action_provider}_job_queue", array(
                'data' => array(
                    'record' => $record,
                    'posted_data' => $posted_data
                )
            ) );
        } else {
            call_user_func( "adfoin_{$action_provider}_send_data", $record, $posted_data );
        }
    }
}

add_action( 'friends_friendship_accepted', 'adfoin_buddyboss_friends_friendship_accepted', 10, 4 );

function adfoin_buddyboss_friends_friendship_accepted( $id, $sender_id, $friend_id, $friendship ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'buddyboss', 'friends_friendship_accepted' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();
    $user_data = adfoin_buddyboss_get_userdata( $friend_id );

    $posted_data['first_name'] = $user_data['first_name'];
    $posted_data['last_name'] = $user_data['last_name'];
    $posted_data['nickname'] = $user_data['nickname'];
    $posted_data['avatar_url'] = $user_data['avatar_url'];
    $posted_data['user_email'] = $user_data['user_email'];

    $friend_data = adfoin_buddyboss_get_userdata( $sender_id );

    $posted_data['friend_id'] = $friend_data['user_id'];
    $posted_data['friend_first_name'] = $friend_data['first_name'];
    $posted_data['friend_last_name'] = $friend_data['last_name'];
    $posted_data['friend_nickname'] = $friend_data['nickname'];
    $posted_data['friend_avatar_url'] = $friend_data['avatar_url'];
    $posted_data['friend_email'] = $friend_data['user_email'];

    adfoin_buddyboss_send_data( $saved_records, $posted_data );
}

add_action( 'friends_friendship_requested', 'adfoin_buddyboss_friends_friendship_requested', 10, 4 );

function adfoin_buddyboss_friends_friendship_requested( $id, $sender_id, $friend_id, $friendship ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'buddyboss', 'friends_friendship_requested' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();
    $user_data = adfoin_buddyboss_get_userdata( $sender_id );

    $posted_data['first_name'] = $user_data['first_name'];
    $posted_data['last_name'] = $user_data['last_name'];
    $posted_data['nickname'] = $user_data['nickname'];
    $posted_data['avatar_url'] = $user_data['avatar_url'];
    $posted_data['user_email'] = $user_data['user_email'];

    $friend_data = adfoin_buddyboss_get_userdata( $friend_id );

    $posted_data['friend_id'] = $friend_data['user_id'];
    $posted_data['friend_first_name'] = $friend_data['first_name'];
    $posted_data['friend_last_name'] = $friend_data['last_name'];
    $posted_data['friend_nickname'] = $friend_data['nickname'];
    $posted_data['friend_avatar_url'] = $friend_data['avatar_url'];
    $posted_data['friend_email'] = $friend_data['user_email'];

    adfoin_buddyboss_send_data( $saved_records, $posted_data );
}

add_action( 'bbp_new_topic', 'adfoin_buddyboss_bbp_new_topic', 10, 4 );

function adfoin_buddyboss_bbp_new_topic( $topic_id, $forum_id, $data, $topic_author ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'buddyboss', 'bbp_new_topic' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();
    $user_data = adfoin_buddyboss_get_userdata( $topic_author );

    $posted_data['topic_id'] = $topic_id;
    $posted_data['topic_title'] = get_the_title( $topic_id );
    $posted_data['topic_content'] = get_post_field( 'post_content', $topic_id );
    $posted_data['topic_url'] = get_permalink( $topic_id );
    $posted_data['topic_author_id'] = $user_data['user_id'];
    $posted_data['topic_author_first_name'] = $user_data['first_name'];
    $posted_data['topic_author_last_name'] = $user_data['last_name'];
    $posted_data['topic_author_nickname'] = $user_data['nickname'];
    $posted_data['topic_author_avatar_url'] = $user_data['avatar_url'];
    $posted_data['topic_author_email'] = $user_data['user_email'];

    $forum_data = get_post( $forum_id );

    $posted_data['topic_forum_id'] = $forum_data->ID;
    $posted_data['topic_forum_title'] = $forum_data->post_title;
    $posted_data['topic_forum_slug'] = $forum_data->post_name;
    $posted_data['topic_forum_description'] = $forum_data->post_content;
    $posted_data['topic_forum_url'] = get_permalink( $forum_data->ID );

    adfoin_buddyboss_send_data( $saved_records, $posted_data );
}

add_action( 'bbp_new_reply', 'adfoin_buddyboss_bbp_new_reply', 10, 3 );

function adfoin_buddyboss_bbp_new_reply( $reply_id, $topic_id, $forum_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'buddyboss', 'bbp_new_reply' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();
    $topic_data = get_post( $topic_id );
    $topic_user_data = adfoin_buddyboss_get_userdata( $topic_data->post_author );

    $posted_data['topic_id'] = $topic_data->ID;
    $posted_data['topic_title'] = $topic_data->post_title;
    $posted_data['topic_content'] = $topic_data->post_content;
    $posted_data['topic_url'] = get_permalink( $topic_data->ID );
    $posted_data['topic_author_id'] = $topic_user_data['user_id'];
    $posted_data['topic_author_first_name'] = $topic_user_data['first_name'];
    $posted_data['topic_author_last_name'] = $topic_user_data['last_name'];
    $posted_data['topic_author_nickname'] = $topic_user_data['nickname'];
    $posted_data['topic_author_avatar_url'] = $topic_user_data['avatar_url'];
    $posted_data['topic_author_email'] = $topic_user_data['user_email'];

    $forum_data = get_post( $forum_id );

    $posted_data['topic_forum_id'] = $forum_data->ID;
    $posted_data['topic_forum_title'] = $forum_data->post_title;
    $posted_data['topic_forum_slug'] = $forum_data->post_name;
    $posted_data['topic_forum_description'] = $forum_data->post_content;
    $posted_data['topic_forum_url'] = get_permalink( $forum_data->ID );

    $reply_data = get_post( $reply_id );
    $user_data = adfoin_buddyboss_get_userdata( $reply_data->post_author );

    $posted_data['reply_id'] = $reply_data->ID;
    $posted_data['reply_content'] = $reply_data->post_content;
    $posted_data['reply_url'] = get_permalink( $reply_data->ID );
    $posted_data['reply_author_id'] = $user_data['user_id'];
    $posted_data['reply_author_first_name'] = $user_data['first_name'];
    $posted_data['reply_author_last_name'] = $user_data['last_name'];
    $posted_data['reply_author_nickname'] = $user_data['nickname'];
    $posted_data['reply_author_avatar_url'] = $user_data['avatar_url'];
    $posted_data['reply_author_email'] = $user_data['user_email'];

    adfoin_buddyboss_send_data( $saved_records, $posted_data );
}

add_action( 'groups_join_group', 'adfoin_buddyboss_groups_join_group', 10, 2 );

function adfoin_buddyboss_groups_join_group( $group_id, $user_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'buddyboss', 'groups_join_group' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();
    $group = groups_get_group( $group_id );
    $group_user_data = adfoin_buddyboss_get_userdata( $group->creator_id );

    $posted_data['group_id'] = $group->id;
    $posted_data['group_name'] = $group->name;
    $posted_data['group_slug'] = $group->slug;
    $posted_data['group_description'] = $group->description;
    $posted_data['group_url'] = bp_get_group_permalink( $group );
    $posted_data['group_creator_id'] = $group_user_data['user_id'];
    $posted_data['group_creator_first_name'] = $group_user_data['first_name'];
    $posted_data['group_creator_last_name'] = $group_user_data['last_name'];
    $posted_data['group_creator_nickname'] = $group_user_data['nickname'];
    $posted_data['group_creator_avatar_url'] = $group_user_data['avatar_url'];
    $posted_data['group_creator_email'] = $group_user_data['user_email'];

    $user_data = adfoin_buddyboss_get_userdata( $user_id );

    $posted_data['group_member_id'] = $user_data['user_id'];
    $posted_data['group_member_first_name'] = $user_data['first_name'];
    $posted_data['group_member_last_name'] = $user_data['last_name'];
    $posted_data['group_member_nickname'] = $user_data['nickname'];
    $posted_data['group_member_avatar_url'] = $user_data['avatar_url'];
    $posted_data['group_member_email'] = $user_data['user_email'];

    adfoin_buddyboss_send_data( $saved_records, $posted_data );
}

add_action( 'groups_membership_accepted', 'adfoin_buddyboss_groups_membership_accepted', 10, 2 );

function adfoin_buddyboss_groups_membership_accepted( $user_id, $group_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'buddyboss', 'groups_membership_accepted' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();
    $group = groups_get_group( $group_id );
    $group_user_data = adfoin_buddyboss_get_userdata( $group->creator_id );

    $posted_data['group_id'] = $group->id;
    $posted_data['group_name'] = $group->name;
    $posted_data['group_slug'] = $group->slug;
    $posted_data['group_description'] = $group->description;
    $posted_data['group_url'] = bp_get_group_permalink( $group );
    $posted_data['group_creator_id'] = $group_user_data['user_id'];
    $posted_data['group_creator_first_name'] = $group_user_data['first_name'];
    $posted_data['group_creator_last_name'] = $group_user_data['last_name'];
    $posted_data['group_creator_nickname'] = $group_user_data['nickname'];
    $posted_data['group_creator_avatar_url'] = $group_user_data['avatar_url'];
    $posted_data['group_creator_email'] = $group_user_data['user_email'];

    $user_data = adfoin_buddyboss_get_userdata( $user_id );

    $posted_data['group_member_id'] = $user_data['user_id'];
    $posted_data['group_member_first_name'] = $user_data['first_name'];
    $posted_data['group_member_last_name'] = $user_data['last_name'];
    $posted_data['group_member_nickname'] = $user_data['nickname'];
    $posted_data['group_member_avatar_url'] = $user_data['avatar_url'];
    $posted_data['group_member_email'] = $user_data['user_email'];
    $posted_data['group_member_role'] = 'member';

    adfoin_buddyboss_send_data( $saved_records, $posted_data );
}

add_action( 'groups_accept_invite', 'adfoin_buddyboss_groups_membership_accepted', 10, 2 );

add_action( 'groups_leave_group', 'adfoin_buddyboss_groups_leave_group', 10, 2 );

function adfoin_buddyboss_groups_leave_group( $group_id, $user_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'buddyboss', 'groups_leave_group' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();
    $group = groups_get_group( $group_id );
    $group_user_data = adfoin_buddyboss_get_userdata( $group->creator_id );

    $posted_data['group_id'] = $group->id;
    $posted_data['group_name'] = $group->name;
    $posted_data['group_slug'] = $group->slug;
    $posted_data['group_description'] = $group->description;
    $posted_data['group_url'] = bp_get_group_permalink( $group );
    $posted_data['group_creator_id'] = $group_user_data['user_id'];
    $posted_data['group_creator_first_name'] = $group_user_data['first_name'];
    $posted_data['group_creator_last_name'] = $group_user_data['last_name'];
    $posted_data['group_creator_nickname'] = $group_user_data['nickname'];
    $posted_data['group_creator_avatar_url'] = $group_user_data['avatar_url'];
    $posted_data['group_creator_email'] = $group_user_data['user_email'];

    $user_data = adfoin_buddyboss_get_userdata( $user_id );

    $posted_data['group_member_id'] = $user_data['user_id'];
    $posted_data['group_member_first_name'] = $user_data['first_name'];
    $posted_data['group_member_last_name'] = $user_data['last_name'];
    $posted_data['group_member_nickname'] = $user_data['nickname'];
    $posted_data['group_member_avatar_url'] = $user_data['avatar_url'];
    $posted_data['group_member_email'] = $user_data['user_email'];

    adfoin_buddyboss_send_data( $saved_records, $posted_data );
}

add_action( 'groups_remove_member', 'adfoin_buddyboss_groups_leave_group', 10, 2 );

add_action( 'bp_groups_posted_update', 'adfoin_buddyboss_bp_groups_posted_update', 10, 4 );

function adfoin_buddyboss_bp_groups_posted_update( $content, $user_id, $group_id, $activity_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'buddyboss', 'bp_groups_posted_update' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();

    $group = groups_get_group( $group_id );
    $group_user_data = adfoin_buddyboss_get_userdata( $group->creator_id );

    $posted_data['group_id'] = $group->id;
    $posted_data['group_name'] = $group->name;
    $posted_data['group_slug'] = $group->slug;
    $posted_data['group_description'] = $group->description;
    $posted_data['group_url'] = bp_get_group_permalink( $group );
    $posted_data['group_creator_id'] = $group_user_data['user_id'];
    $posted_data['group_creator_first_name'] = $group_user_data['first_name'];
    $posted_data['group_creator_last_name'] = $group_user_data['last_name'];
    $posted_data['group_creator_nickname'] = $group_user_data['nickname'];
    $posted_data['group_creator_avatar_url'] = $group_user_data['avatar_url'];
    $posted_data['group_creator_email'] = $group_user_data['user_email'];

    $user_data = adfoin_buddyboss_get_userdata( $user_id );

    $posted_data['group_member_id'] = $user_data['user_id'];
    $posted_data['group_member_first_name'] = $user_data['first_name'];
    $posted_data['group_member_last_name'] = $user_data['last_name'];
    $posted_data['group_member_nickname'] = $user_data['nickname'];
    $posted_data['group_member_avatar_url'] = $user_data['avatar_url'];
    $posted_data['group_member_email'] = $user_data['user_email'];

    // get BuddyBoss activity
    $activity = bp_activity_get_specific( array( 'activity_ids' => $activity_id ) );

    $posted_data['activity_id'] = $activity['activities'][0]->id;
    $posted_data['activity_content'] = $activity['activities'][0]->content;
    $posted_data['activity_url'] = bp_activity_get_permalink( $activity['activities'][0] );
    $posted_data['activity_author_id'] = $activity['activities'][0]->user_id;
    $posted_data['activity_author_first_name'] = $activity['activities'][0]->user_fullname;
    $posted_data['activity_author_last_name'] = $activity['activities'][0]->user_fullname;
    $posted_data['activity_author_nickname'] = $activity['activities'][0]->user_nicename;
    $posted_data['activity_author_avatar_url'] = bp_core_fetch_avatar( array( 'item_id' => $activity['activities'][0]->user_id, 'html' => false ) );
    $posted_data['activity_author_email'] = $activity['activities'][0]->user_email;

    adfoin_buddyboss_send_data( $saved_records, $posted_data );
}

add_action( 'groups_membership_requested', 'adfoin_buddyboss_groups_membership_requested', 10, 4 );

function adfoin_buddyboss_groups_membership_requested( $user_id, $admins, $group_id, $request_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'buddyboss', 'groups_membership_requested' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();

    $group = groups_get_group( $group_id );
    $group_user_data = adfoin_buddyboss_get_userdata( $group->creator_id );

    $posted_data['group_id'] = $group->id;
    $posted_data['group_name'] = $group->name;
    $posted_data['group_slug'] = $group->slug;
    $posted_data['group_description'] = $group->description;
    $posted_data['group_url'] = bp_get_group_permalink( $group );
    $posted_data['group_creator_id'] = $group_user_data['user_id'];
    $posted_data['group_creator_first_name'] = $group_user_data['first_name'];
    $posted_data['group_creator_last_name'] = $group_user_data['last_name'];
    $posted_data['group_creator_nickname'] = $group_user_data['nickname'];
    $posted_data['group_creator_avatar_url'] = $group_user_data['avatar_url'];
    $posted_data['group_creator_email'] = $group_user_data['user_email'];

    $user_data = adfoin_buddyboss_get_userdata( $user_id );

    $posted_data['group_member_id'] = $user_data['user_id'];
    $posted_data['group_member_first_name'] = $user_data['first_name'];
    $posted_data['group_member_last_name'] = $user_data['last_name'];
    $posted_data['group_member_nickname'] = $user_data['nickname'];
    $posted_data['group_member_avatar_url'] = $user_data['avatar_url'];
    $posted_data['group_member_email'] = $user_data['user_email'];

    adfoin_buddyboss_send_data( $saved_records, $posted_data );
}

add_action( 'bp_member_invite_submit', 'adfoin_buddyboss_bp_member_invite_submit', 10, 2 );

function adfoin_buddyboss_bp_member_invite_submit( $user_id, $post_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'buddyboss', 'bp_member_invite_submit' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();

    $user_data = adfoin_buddyboss_get_userdata( $user_id );

    $posted_data['invitee_id'] = $user_data['user_id'];
    $posted_data['invitee_first_name'] = $user_data['first_name'];
    $posted_data['invitee_last_name'] = $user_data['last_name'];
    $posted_data['invitee_nickname'] = $user_data['nickname'];
    $posted_data['invitee_avatar_url'] = $user_data['avatar_url'];
    $posted_data['invitee_email'] = $user_data['user_email'];

    adfoin_buddyboss_send_data( $saved_records, $posted_data );
}

add_action( 'xprofile_avatar_uploaded', 'adfoin_buddyboss_xprofile_avatar_uploaded', 10, 3 );

function adfoin_buddyboss_xprofile_avatar_uploaded( $item_id, $type, $r ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'buddyboss', 'xprofile_avatar_uploaded' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();

    $user_data = adfoin_buddyboss_get_userdata( $r['item_id'] );

    $posted_data['user_id'] = $user_data['user_id'];
    $posted_data['first_name'] = $user_data['first_name'];
    $posted_data['last_name'] = $user_data['last_name'];
    $posted_data['nickname'] = $user_data['nickname'];
    $posted_data['avatar_url'] = $user_data['avatar_url'];
    $posted_data['user_email'] = $user_data['user_email'];

    adfoin_buddyboss_send_data( $saved_records, $posted_data );
}

add_action( 'bp_core_activated_user', 'adfoin_buddyboss_bp_core_activated_user', 10, 1 );

function adfoin_buddyboss_bp_core_activated_user( $user_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'buddyboss', 'bp_core_activated_user' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();

    $user_data = adfoin_buddyboss_get_userdata( $user_id );

    $posted_data['user_id'] = $user_data['user_id'];
    $posted_data['first_name'] = $user_data['first_name'];
    $posted_data['last_name'] = $user_data['last_name'];
    $posted_data['nickname'] = $user_data['nickname'];
    $posted_data['avatar_url'] = $user_data['avatar_url'];
    $posted_data['user_email'] = $user_data['user_email'];

    adfoin_buddyboss_send_data( $saved_records, $posted_data );
}

add_action( 'bp_invites_member_invite_activate_user', 'adfoin_buddyboss_bp_invites_member_invite_activate_user', 10, 2 );

function adfoin_buddyboss_bp_invites_member_invite_activate_user( $user_id, $inviter_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'buddyboss', 'bp_invites_member_invite_activate_user' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();

    $user_data = adfoin_buddyboss_get_userdata( $user_id );

    $posted_data['invitee_user_id'] = $user_data['user_id'];
    $posted_data['invitee_first_name'] = $user_data['first_name'];
    $posted_data['invitee_last_name'] = $user_data['last_name'];
    $posted_data['invitee_nickname'] = $user_data['nickname'];
    $posted_data['invitee_avatar_url'] = $user_data['avatar_url'];
    $posted_data['invitee_user_email'] = $user_data['user_email'];

    $inviter_data = adfoin_buddyboss_get_userdata( $inviter_id );

    $posted_data['inviter_user_id'] = $inviter_data['user_id'];
    $posted_data['inviter_first_name'] = $inviter_data['first_name'];
    $posted_data['inviter_last_name'] = $inviter_data['last_name'];
    $posted_data['inviter_nickname'] = $inviter_data['nickname'];
    $posted_data['inviter_avatar_url'] = $inviter_data['avatar_url'];
    $posted_data['inviter_user_email'] = $inviter_data['user_email'];

    adfoin_buddyboss_send_data( $saved_records, $posted_data );
}

add_action( 'bp_invites_member_invite_mark_register_user', 'adfoin_buddyboss_bp_invites_member_invite_mark_register_user', 10, 2 );

function adfoin_buddyboss_bp_invites_member_invite_mark_register_user( $user_id, $inviter_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'buddyboss', 'bp_invites_member_invite_mark_register_user' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();

    $user_data = adfoin_buddyboss_get_userdata( $inviter_id );

    $posted_data['user_id'] = $user_data['user_id'];
    $posted_data['first_name'] = $user_data['first_name'];
    $posted_data['last_name'] = $user_data['last_name'];
    $posted_data['nickname'] = $user_data['nickname'];
    $posted_data['avatar_url'] = $user_data['avatar_url'];
    $posted_data['user_email'] = $user_data['user_email'];

    adfoin_buddyboss_send_data( $saved_records, $posted_data );
}
