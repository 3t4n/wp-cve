<?php

namespace WunderAuto\Types\Actions;

use WunderAuto\Types\Internal\Action;

/**
 * Class CreateUser
 */
class CreateUser extends BaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('Create user ', 'wunderauto');
        $this->description = __('Create a new user', 'wunderauto');
        $this->group       = 'WordPress';

        $this->addProvidedObject(
            'newuser',
            'user',
            'The newly created user'
        );
    }

    /**
     * @param Action $config
     *
     * @return void
     */
    public function sanitizeConfig($config)
    {
        parent::sanitizeConfig($config);
        $config->sanitizeObjectProp($config->value, 'login', 'text');
        $config->sanitizeObjectProp($config->value, 'password', 'text');
        $config->sanitizeObjectProp($config->value, 'email', 'text');
        $config->sanitizeObjectProp($config->value, 'firstName', 'text');
        $config->sanitizeObjectProp($config->value, 'lastName', 'text');
        $config->sanitizeObjectProp($config->value, 'role', 'text');
        $config->sanitizeObjectProp($config->value, 'description', 'textarea');
        $config->sanitizeObjectProp($config->value, 'notify', 'key');
    }

    /**
     * @return bool
     */
    public function doAction()
    {
        $login       = $this->getResolved('value.login');
        $password    = $this->getResolved('value.password');
        $email       = $this->getResolved('value.email');
        $firstName   = $this->getResolved('value.firstName');
        $lastName    = $this->getResolved('value.lastName');
        $role        = $this->getResolved('value.role');
        $description = $this->getResolved('value.description');
        $notify      = $this->getResolved('value.notify');

        $notify = $notify == '' ? 'none' : $notify;

        if (strlen($login) === 0) {
            return false;
        }

        if (strlen($password) === 0) {
            $password = wp_generate_password();
        }

        $userId = wp_insert_user([
            'user_login'  => $login,
            'user_pass'   => $password,
            'user_email'  => $email,
            'first_name'  => $firstName,
            'last_name'   => $lastName,
            'role'        => $role,
            'description' => $description,
        ]);

        $newUser = wa_empty_wp_user();
        if (!($userId instanceof \WP_Error)) {
            if ($notify !== 'none') {
                wp_new_user_notification($userId, null, $notify);
            }

            $newUser = get_user_by('id', $userId);
        }

        $this->resolver->addObject('newuser', 'user', $newUser);
        return !is_wp_error($userId);
    }
}
