<?php

namespace FluentConnect\App\Services\Triggers\WP;

use FluentConnect\App\Services\Triggers\BaseTrigger;
use FluentConnect\Framework\Support\Arr;

class ProfileUpdated extends BaseTrigger
{
    public function __construct()
    {
        $this->triggerProvider = 'wp';
        $this->triggerName = 'profile_update';
        parent::__construct();
    }

    public function getInfo()
    {
        return [
            'title'                  => 'User Profile Updated',
            'description'            => 'This will trigger when a profile gets updated',
            'has_remote_integration' => false,
            'supported_fields'       => [
                'email',
                'user_id',
                'first_name',
                'last_name'
            ],
            'is_enabled' => $this->isEnabled(),
            'settings_defaults' => $this->getSettingsDefaults()
        ];
    }

    public function getSettingsDefaults()
    {
        return [
            'user_roles'  => []
        ];
    }

    public function getSettingsFields($trigger)
    {
        return [
            'user_roles' => [
                'type'        => 'input-options',
                'label'       => 'Select Target User Role',
                'placeholder' => 'Select User Roles (Leave blank for all)',
                'options'     => [
                    [
                        'id' => 'administrator',
                        'title' => 'Administrator'
                    ]
                ],
                'show_id' => false,
                'is_multiple' => true
            ]
        ];
    }

    public function isEnabled()
    {
        return true;
    }

    public function getFormattedData($trigger, $args)
    {
        $user = get_user_by('ID', $args[0]);

        if(!$user) {
            return false;
        }

        $targetRoles = Arr::get($trigger->settings, 'user_roles', []);

        if($targetRoles) {
            if(!array_intersect((array) $user->roles, $targetRoles)) {
                return false;
            }
        }

        return [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'name' => $user->display_name,
            'email' => $user->user_email,
            'user_id' => $user->ID,
            '__user' => $user,
            '__runner_hash' => $user->ID
        ];
    }
}
