<?php

namespace Watchful\Helpers\Sso;

use stdClass;
use WP_Error;
use WP_User;

class UserManager
{
    /**
     * @param stdClass $user_data
     * @return WP_User|WP_Error
     */
    public function get_wp_user_by_data($user_data)
    {
        $existing_user = $this->get_existing_wp_user($user_data);

        if (empty($existing_user)) {
            return $this->upsert_wp_user($user_data);
        }

        if (empty(get_user_meta($existing_user->ID, 'watchful_sso_id', true))) {
            return new WP_Error(
                'user_exist',
                'Another user with the same username/email exist, Watchful SSO cannot be used.'
            );
        }

        return $this->upsert_wp_user($user_data, $existing_user->ID);
    }

    /**
     * @param $user_data
     * @return false|WP_User
     */
    private function get_existing_wp_user($user_data)
    {
        $user = get_user_by('login', $user_data->username);
        if (!empty($user)) {
            return $user;
        }

        return get_user_by('email', $user_data->email);
    }

    /**
     * @param $user_data
     * @param int|null $user_id
     * @return WP_User|WP_Error
     */
    private function upsert_wp_user($user_data, $user_id = null)
    {
        $user_id = wp_insert_user(
            array(
                'ID' => $user_id,
                'user_login' => $user_data->username,
                'user_email' => $user_data->email,
                'user_pass' => wp_generate_password(24, true, true),
                'nickname' => $user_data->name,
                'role' => $this->get_user_role_by_group_id($user_data->wpgroupid ?: $user_data->groupid),
            )
        );

        if (is_wp_error($user_id)) {
            return $user_id;
        }

        update_user_meta($user_id, 'watchful_sso_id', $user_data->id);

        return get_user_by('id', $user_id);
    }

    /**
     * @param int $group_id
     * @return string
     */
    private function get_user_role_by_group_id($group_id)
    {
        if ($group_id === 7 || $group_id === 8) {
            return 'administrator';
        }

        if ($group_id === 4 || $group_id === 5 || $group_id === 6) {
            return 'editor';
        }

        if ($group_id === 3) {
            return 'author';
        }

        if ($group_id === 2) {
            return 'contributor';
        }

        return 'subscriber';
    }
}
