<?php

namespace WunderAuto\Types\Triggers\User;

use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class Created
 */
class Created extends BaseTrigger
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->title       = __('User created', 'wunderauto');
        $this->group       = __('Users', 'wunderauto');
        $this->description = __('This trigger fires when a user account is created', 'wunderauto');

        $this->addProvidedObject(
            'user',
            'user',
            __('The user that was created', 'wunderauto'),
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
            add_action('user_register', [$this, 'userRegister'], 99, 1);
        }
        $this->registered = true;
    }

    /**
     * Event handler for the hooked event
     *
     * @param int $userId
     *
     * @return void
     */
    public function userRegister($userId)
    {
        $user = get_user_by('id', $userId);
        if ($user instanceof \WP_User) {
            $objects = ['user' => $user];
            $this->doTrigger($objects);
        }
    }
}
