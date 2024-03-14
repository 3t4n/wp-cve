<?php

namespace WpifyWooDeps\Wpify\Core\Models;

use WP_User;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractComponent;
class UserModel extends AbstractComponent
{
    /**
     * Disable auto init by default
     *
     * @var bool
     */
    protected $auto_init = \false;
    /** @var WP_User */
    private $user = null;
    public function __construct($user)
    {
        if ($user instanceof WP_User) {
            $this->user = $user;
        } else {
            $check_user_by = array();
            if (\is_numeric($user)) {
                $check_user_by[] = 'ID';
            } elseif (\filter_var($user, \FILTER_VALIDATE_EMAIL)) {
                $check_user_by[] = 'email';
                $check_user_by[] = 'login';
            } else {
                $check_user_by[] = 'login';
                $check_user_by[] = 'slug';
            }
            foreach ($check_user_by as $field) {
                $maybe_user = get_user_by($field, $user);
                if ($maybe_user) {
                    $this->user = $maybe_user;
                    break;
                }
            }
        }
    }
    public function get_id() : ?int
    {
        return $this->user->ID ?? null;
    }
    public function get_user() : ?WP_User
    {
        return $this->user;
    }
}
