<?php

namespace WunderAuto\Types\Triggers\User;

use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class RoleChanged
 */
class RoleChanged extends BaseTrigger
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->title       = __('User role changed', 'wunderauto');
        $this->group       = __('Users', 'wunderauto');
        $this->description = __('This trigger fires when a users role is changed', 'wunderauto');

        $this->addProvidedObject(
            'user',
            'user',
            __('The user who\'s role was changed', 'wunderauto'),
            true
        );
    }

    /**
     * Register our hooks with WordPress
     *
     * @return void
     */
    public function registerHooks()
    {
        if (!$this->registered) {
            add_action('set_user_role', [$this, 'userRoleChanged'], 99, 2);
        }
        $this->registered = true;
    }

    /**
     * Handle the ser_user_role action
     *
     * @param int      $userId
     * @param \WP_Role $newRole
     *
     * @return void
     */
    public function userRoleChanged($userId, $newRole)
    {
        $user    = get_user_by('id', $userId);
        $objects = ['user' => $user];
        $this->doTrigger($objects);
    }
}
