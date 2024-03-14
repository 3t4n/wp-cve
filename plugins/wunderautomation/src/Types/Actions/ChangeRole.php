<?php

namespace WunderAuto\Types\Actions;

use WunderAuto\Types\Internal\Action;

/**
 * Class ChangeRole
 */
class ChangeRole extends BaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('Change user role', 'wunderauto');
        $this->description = __('Change user role', 'wunderauto');
        $this->group       = 'WordPress';
    }

    /**
     * @param Action $config
     *
     * @return void
     */
    public function sanitizeConfig($config)
    {
        parent::sanitizeConfig($config);
        $config->sanitizeObjectProp($config->value, 'who', 'key');
        $config->sanitizeObjectProp($config->value, 'newRole', 'key');
    }

    /**
     * @return bool
     */
    public function doAction()
    {
        $who = $this->get('value.who');
        if (is_null($who)) {
            $who = 'user';
        }
        $user = $this->resolver->getObject($who);

        if (!($user instanceof \WP_User)) {
            return false;
        }

        $newRole = $this->get('value.newRole');
        if (!$newRole) {
            return false;
        }
        $newRole = $newRole === 'no_role' ? '' : $newRole;

        $user->set_role($newRole);
        return true;
    }
}
