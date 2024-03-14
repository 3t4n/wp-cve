<?php

namespace WunderAuto\Types\Parameters\User;

use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Role
 */
class Role extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'user';
        $this->title       = 'role';
        $this->description = __('WordPress user role.', 'wunderauto');
        $this->objects     = ['user'];

        $this->usesDefault  = true;
        $this->usesReturnAs = true;
    }

    /**
     * @param \WP_User  $user
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($user, $modifiers)
    {
        global $wp_roles;
        $meta = get_userdata($user->ID);
        if ($meta === false) {
            return $this->formatField('', $modifiers);
        }
        $roles = $meta->roles;

        $value = $roles[0];
        if (isset($modifiers->return) && trim($modifiers->return) === 'label') {
            if (isset($wp_roles->roles[$value])) {
                $value = $wp_roles->roles[$value]['name'];
            }
        }

        return $this->formatField($value, $modifiers);
    }
}
