<?php

namespace WunderAuto\Types\Triggers\User;

use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class Login
 */
class Login extends BaseTrigger
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->title       = __('User login', 'wunderauto');
        $this->group       = __('Users', 'wunderauto');
        $this->description = __('This trigger fires when a user logs in', 'wunderauto');

        $this->addProvidedObject(
            'user',
            'user',
            __('The user that logged in', 'wunderauto'),
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
            add_action('wp_login', [$this, 'userLogin'], 99, 2);
        }
        $this->registered = true;
    }

    /**
     * Event handler for the hooked event
     *
     * @param string   $userLogin
     * @param \WP_User $user
     *
     * @return void
     */
    public function userLogin($userLogin, $user)
    {
        $this->doTrigger(['user' => $user]);
    }
}
