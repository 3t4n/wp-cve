<?php

namespace WunderAuto\Types\Triggers\User;

use WunderAuto\Types\Triggers\BaseReTrigger;

/**
 * Class ReTriggered
 */
class ReTriggered extends BaseReTrigger
{
    /**
     * @var array<int, int>
     */
    private $triggeredUsers;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->addProvidedObject(
            'user',
            'user',
            __('The user', 'wunderauto')
        );

        $this->triggeredUsers = [];
    }

    /**
     * @param \WP_User $user
     *
     * @return array<string, object>|false
     */
    public function getObjects($user)
    {
        if (in_array($user->ID, $this->triggeredUsers)) {
            return false;
        }

        $this->triggeredUsers[] = $user->ID;
        return $this->getResolverObjects(['user' => $user]);
    }
}
