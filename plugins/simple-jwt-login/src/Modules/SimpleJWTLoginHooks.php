<?php

namespace SimpleJWTLogin\Modules;

class SimpleJWTLoginHooks
{
    const LOGIN_ACTION_NAME = 'simple_jwt_login_login_hook';
    const LOGIN_REDIRECT_NAME = 'simple_jwt_login_redirect_hook';
    const REGISTER_ACTION_NAME = 'simple_jwt_login_register_hook';
    const DELETE_USER_ACTION_NAME = 'simple_jwt_login_delete_user_hook';
    const JWT_PAYLOAD_ACTION_NAME  = 'simple_jwt_login_jwt_payload_auth';
    const NO_REDIRECT_RESPONSE = 'simple_jwt_login_no_redirect_message';
    const RESET_PASSWORD_CUSTOM_EMAIL_TEMPLATE = 'simple_jwt_login_reset_password_custom_email_template';

    const HOOK_TYPE_ACTION = 'action';
    const HOOK_TYPE_FILTER = 'filter';

    const HOOK_RESPONSE_AUTH_USER = 'simple_jwt_login_response_auth_user';
    const HOOK_RESPONSE_DELETE_USER = 'simple_jwt_login_response_delete_user';
    const HOOK_RESPONSE_REFRESH_TOKEN = 'simple_jwt_login_response_refresh_token';
    const HOOK_RESPONSE_REGISTER_USER = 'simple_jwt_login_response_register_user';
    const HOOK_RESPONSE_SEND_RESET_PASSWORD = 'simple_jwt_login_response_send_reset_password';
    const HOOK_RESPONSE_CHANGE_USER_PASSWORD = 'simple_jwt_login_response_change_user_password';
    const HOOK_RESPONSE_REVOKE_TOKEN = 'simple_jwt_login_response_revoke_token';
    const HOOK_RESPONSE_VALIDATE_TOKEN = 'simple_jwt_login_response_validate_token';
    const HOOK_GENERATE_PAYLOAD = 'simple_jwt_login_generate_payload';
    const HOOK_BEFORE_ENDPOINT = 'simple_jwt_login_before_endpoint';

    /**
     * @return array[]
     */
    public static function getHooksDetails()
    {
        return [
            [
                'name' => self::LOGIN_ACTION_NAME,
                'type' => self::HOOK_TYPE_ACTION,
                'parameters' => [
                    'Wp_User $user'
                ],
                'description' => __('This hook is called after the user is logged in.', 'simple-jwt-login'),
            ],
            [
                'name' => self::LOGIN_REDIRECT_NAME,
                'type' => self::HOOK_TYPE_ACTION,
                'parameters' => [
                    'string $url',
                    'array $request'
                ],
                'description' =>
                    __(
                        'This hook is called before the user is redirected to the page' .
                        'that he specified in the login section.',
                        'simple-jwt-login'
                    ),
            ],
            [
                'name' => self::REGISTER_ACTION_NAME,
                'type' => self::HOOK_TYPE_ACTION,
                'parameters' => [
                    'Wp_User $user',
                    'string $password'
                ],
                'description' => __(
                    'This hook is called after a new user is created.',
                    'simple-jwt-login'
                ),
            ],
            [
                'name' => self::DELETE_USER_ACTION_NAME,
                'type' => self::HOOK_TYPE_ACTION,
                'parameters' => [
                    'Wp_User $user'
                ],
                'description' => __(
                    'This hook is called right after the user was deleted.',
                    'simple-jwt-login'
                ),
            ],
            [
                'name' => self::JWT_PAYLOAD_ACTION_NAME,
                'type' => self::HOOK_TYPE_FILTER,
                'parameters' => [
                    'array $payload',
                    'array $request'
                ],
                'return' => 'array $payload',
                'description' => __(
                    'This hook is called on /auth endpoint.'
                    . 'Here you can modify payload parameters.',
                    'simple-jwt-login'
                ),
            ],
            [
                'name' => self::NO_REDIRECT_RESPONSE,
                'type' => self::HOOK_TYPE_FILTER,
                'parameters' => [
                    'array $response',
                    'array $request'
                ],
                'return' => 'array $response',
                'description' => __(
                    'This hook is called on /autologin endpoint when the option'
                    . '`No Redirect` is selected. You can customize the message and add parameters.',
                    'simple-jwt-login'
                ),
            ],
            [
                'name' => self::RESET_PASSWORD_CUSTOM_EMAIL_TEMPLATE,
                'type' => self::HOOK_TYPE_FILTER,
                'parameters' => [
                    'string $template',
                    'array $request'
                ],
                'return' => 'string $template',
                'description' => __(
                    'This is executed when POST /user/reset_password is called.'
                    . ' It will replace the email template that has been added in Reset Password settings',
                    'simple-jwt-login'
                ),
            ],
            [
                'name' => self::HOOK_RESPONSE_AUTH_USER,
                'type' => self::HOOK_TYPE_FILTER,
                'parameters' => [
                    'array $response',
                    'WP_User $user'
                ],
                'return' => 'array $response',
                'description' => __(
                    'This is executed before displaying the response of auth endpoint.',
                    'simple-jwt-login'
                ),
            ],
            [
                'name' => self::HOOK_RESPONSE_DELETE_USER,
                'type' => self::HOOK_TYPE_FILTER,
                'parameters' => [
                    'array $response',
                    'WP_User $user'
                ],
                'return' => 'array $response',
                'description' => __(
                    'This is executed before displaying the response of delete user endpoint.',
                    'simple-jwt-login'
                ),
            ],
            [
                'name' => self::HOOK_RESPONSE_REFRESH_TOKEN,
                'type' => self::HOOK_TYPE_FILTER,
                'parameters' => [
                    'array $response',
                    'WP_User $user'
                ],
                'return' => 'array $response',
                'description' => __(
                    'This is executed before displaying the response of refresh token endpoint.',
                    'simple-jwt-login'
                ),
            ],
            [
                'name' => self::HOOK_RESPONSE_REGISTER_USER,
                'type' => self::HOOK_TYPE_FILTER,
                'parameters' => [
                    'array $response',
                    'WP_User $user'
                ],
                'return' => 'array $response',
                'description' => __(
                    'This is executed before displaying the response of register user endpoint.',
                    'simple-jwt-login'
                ),
            ],
            [
                'name' => self::HOOK_RESPONSE_SEND_RESET_PASSWORD,
                'type' => self::HOOK_TYPE_FILTER,
                'parameters' => [
                    'array $response',
                    'WP_User $user'
                ],
                'return' => 'array $response',
                'description' => __(
                    'This is executed before displaying the response of send reset password endpoint.',
                    'simple-jwt-login'
                ),
            ],
            [
                'name' => self::HOOK_RESPONSE_CHANGE_USER_PASSWORD,
                'type' => self::HOOK_TYPE_FILTER,
                'parameters' => [
                    'array $response',
                    'WP_User $user'
                ],
                'return' => 'array $response',
                'description' => __(
                    'This is executed before displaying the response of change user password endpoint.',
                    'simple-jwt-login'
                ),
            ],
            [
                'name' => self::HOOK_RESPONSE_REVOKE_TOKEN,
                'type' => self::HOOK_TYPE_FILTER,
                'parameters' => [
                    'array $response',
                    'WP_User $user'
                ],
                'return' => 'array $response',
                'description' => __(
                    'This is executed before displaying the response of revoke token endpoint.',
                    'simple-jwt-login'
                ),
            ],
            [
                'name' => self::HOOK_RESPONSE_VALIDATE_TOKEN,
                'type' => self::HOOK_TYPE_FILTER,
                'parameters' => [
                    'array $response',
                    'WP_User $user'
                ],
                'return' => 'array $response',
                'description' => __(
                    'This is executed before displaying the response of validate token endpoint.',
                    'simple-jwt-login'
                ),
            ],
            [
                'name' => self::HOOK_GENERATE_PAYLOAD,
                'type' => self::HOOK_TYPE_FILTER,
                'parameters' => [
                    'array $payload',
                    'WP_User $user'
                ],
                'return' => 'array $payload',
                'description' => __(
                    'This is executed before generating the JWT payload.',
                    'simple-jwt-login'
                ) .
                    __(
                        'This will allow you to append extra properties in JWT on authentication.',
                        'simple-jwt-login'
                    )
                ,
            ],
            [
                'name' => self::HOOK_BEFORE_ENDPOINT,
                'type' => self::HOOK_TYPE_ACTION,
                'parameters' => [
                    'string $method',
                    'string $endpoint',
                    'array $request'
                ],
                'description' => __(
                    'This is executed before the simple-jwt-login rest route is initialized.',
                    'simple-jwt-login'
                ),
            ],
        ];
    }
}
