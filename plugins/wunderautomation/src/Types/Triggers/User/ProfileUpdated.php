<?php

namespace WunderAuto\Types\Triggers\User;

use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class ProfileUpdated
 */
class ProfileUpdated extends BaseTrigger
{
    /**
     * Keep track of updated users
     *
     * @var array<int, int>
     */
    private $updatedUsers = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->title       = __('User profile updated', 'wunderauto');
        $this->group       = __('Users', 'wunderauto');
        $this->description = __('This trigger fires when a user profile is updated', 'wunderauto');

        $this->addProvidedObject(
            'user',
            'user',
            __('The user who\'s profile was updated', 'wunderauto'),
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
            add_action('profile_update', [$this, 'userProfileUpdated'], 99, 2);
        }
        $this->registered = true;
    }

    /**
     * Handle the profile_update action
     *
     * @param int    $userId
     * @param object $oldUserData
     *
     * @return void
     */
    public function userProfileUpdated($userId, $oldUserData)
    {
        $this->updatedUsers[] = $userId;
        if (count($this->updatedUsers) === 1) {
            add_action('shutdown', [$this, 'handleUpdatedUsers']);
        }
    }

    /**
     * Handle all updated users at end of request life cycle.
     *
     * @return void
     */
    public function handleUpdatedUsers()
    {
        $this->updatedUsers = array_unique($this->updatedUsers);

        foreach ($this->updatedUsers as $updatedUserId) {
            $user = get_user_by('id', $updatedUserId);
            if ($user instanceof \WP_User) {
                $objects = ['user' => $user];
                $this->doTrigger($objects);
            }
        }
    }
}
