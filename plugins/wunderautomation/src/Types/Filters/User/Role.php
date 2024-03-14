<?php

namespace WunderAuto\Types\Filters\User;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Role
 */
class Role extends BaseFilter
{
    /**
     * @var string
     */
    protected $resolverName;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->resolverName = 'user';
        $this->group        = __('User', 'wunderauto');
        $this->title        = __('User role', 'wunderauto');
        $this->description  = __('Filters based on user role', 'wunderauto');
        $this->objects      = ['user'];

        $this->operators = $this->setOperators();
        $this->inputType = 'multiselect';
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize()
    {
        global $wp_roles;

        $allRoles      = $wp_roles->roles;
        $editableRoles = apply_filters('editable_roles', $allRoles);
        foreach ($editableRoles as $value => $role) {
            $this->compareValues[] = ['value' => $value, 'label' => $role['name']];
        }
    }

    /**
     * Evaluate filter
     *
     * @return bool
     */
    public function evaluate()
    {
        $user = $this->getObject();
        if (!($user instanceof \WP_User)) {
            return false;
        }

        $meta = get_userdata($user->ID);
        if (empty($meta)) {
            return false;
        }

        $roles       = $meta->roles;
        $actualValue = $roles[0];

        return $this->evaluateCompare($actualValue);
    }
}
