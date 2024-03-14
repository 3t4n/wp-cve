<?php

namespace FluentSupport\App\Http\Controllers;

use FluentSupport\App\Models\Meta;
use FluentSupport\App\Services\Helper;
use FluentSupport\Framework\Support\Arr;
use FluentSupport\Framework\Request\Request;
use FluentSupport\App\Hooks\Handlers\AuthHandler;
use FluentSupport\App\Hooks\Handlers\ReCaptchaHandler;

class AuthController extends Controller
{
    /**
     * signUp method will create new user submitted data from sign up form
     * @param Request $request
     * @return \WP_REST_Response
     * @throws \FluentSupport\Framework\Validator\ValidationException
     */
    public function signup(Request $request)
    {

        if(Helper::getAuthProvider() != 'fluent_support') {
            return $this->sendError([
                'message' => __('You are not allowed to signup using this form', 'fluent-support')
            ]);
        }

        if (!wp_verify_nonce($request->get('_fsupport_signup_nonce'), 'fluent_support_signup_nonce')) {
            return $this->sendError([
                'message' => __('Security verification failed. Please try again', 'fluent-support')
            ]);
        }

        $fields = AuthHandler::getSignupFields();

        $rules = $this->getRules($fields);

        $messages = $this->getMessages($rules);

        /*
         * Filter user signup form data
         *
         * @since v1.0.0
         * @param array $formData
         */
        $formData = apply_filters('fluent_support/signup_form_data', $request->all());

        /*
         * Action before validate user signup
         *
         * @since v1.0.0
         * @param array $formData
         */
        do_action('fluent_support/before_signup_validation', $formData);

        //Testing recaptcha

        $checkRecaptchaAvailability = $this->isRecaptchaApplicable('signup_form');
        if ($checkRecaptchaAvailability) {
            $validateCaptcha = ReCaptchaHandler::validateRecaptcha($formData['g-recaptcha-response']);
            if (!$validateCaptcha) {
                return $this->response([
                    'message' => __('Your recaptcha is not verified', 'fluent-support')
                ], 422);
            }
        }
        //Testing recaptcha

        $this->validate($formData, $rules, $messages);

        /*
         * Action After validate user signup validation success
         *
         * @since v1.0.0
         * @param array $formData
         */
        do_action('fluent_support/after_signup_validation', $formData);

        $userId = $this->createUser($formData);

        if (is_wp_error($userId)) {
            return $this->response(
                apply_filters(
                    'fluent_support/signup_create_user_error',
                    ['error' => $userId->get_error_message()]
                ), 423);
        }

        /*
         * Action After creating WP user from ticket sign up form
         *
         * @since v1.0.0
         * @param array $formData
         */
        do_action('fluent_support/after_creating_user');

        $this->maybeUpdateUser($userId, $formData);
        $this->addUserMetaData($userId, $formData);
        $this->assignRole($userId);
        $this->login($userId);

        /*
         * Filter for user signup complete message and redirect
         *
         * @since v1.0.0
         * @param array $response
         */
        $response = apply_filters('fluent_support/signup_complete_response', [
            'message' => __('Successfully registered to the site.', 'fluent-support'),
            'redirect' => Arr::get($formData, '__redirect_to', Helper::getPortalBaseUrl())
        ]);

        return $this->response($response);
    }

    /**
     * handleLogin method will perform login functionality and redirect
     * @param Request $request
     * @return \WP_REST_Response
     */
    public function handleLogin(Request $request)
    {
        if(Helper::getAuthProvider() != 'fluent_support') {
            return $this->sendError([
                'message' => __('You are not allowed to login using this form', 'fluent-support')
            ]);
        }

        if (!wp_verify_nonce($request->get('_support_login_nonce'), 'fsupport_login_nonce')) {
            return $this->response([
                'message' => __('Security verification failed', 'fluent-support')
            ], 403);
        }

        $data = $request->all();


        $checkRecaptchaAvailability = $this->isRecaptchaApplicable('login_form');
        if ($checkRecaptchaAvailability) {
            $validateCaptcha = ReCaptchaHandler::validateRecaptcha($data['g-recaptcha-response']);

            if (!$validateCaptcha) {
                return $this->response([
                    'message' => __('Your recaptcha is not verified', 'fluent-support')
                ], 422);
            }
        }

        if (empty($data['pwd']) || empty($data['log'])) {
            return $this->response([
                'message' => __('Email and Password is required', 'fluent-support')
            ], 403);
        }
        $redirectUrl = Helper::getPortalBaseUrl();
        if ($redirect = $request->get('redirect_to')) {
            if (filter_var($redirect, FILTER_VALIDATE_URL)) {
                $redirectUrl = sanitize_url($redirect);
            }
        }

        if (get_current_user_id()) { // user already registered
            return $this->sendSuccess([
                'redirect' => $redirectUrl
            ]);
        }

        $email = sanitize_user($data['log']);
        $password = trim($data['pwd']);

        if (is_email($email)) {
            $user = get_user_by('email', $email);
        } else {
            $user = get_user_by('login', $email);
        }

        if (!$user) {
            $user = new \WP_Error('authentication_failed', __('<strong>Error</strong>: Invalid username, email address or incorrect password.', 'fluent-support'));

            do_action('wp_login_failed', $email, $user);

            return $this->response([
                'message' => __('Email or Password is not valid. Please try again', 'fluent-support')
            ], 403);

        }

        if (apply_filters('fluent_support_use_native_login', true)) {
            $user = wp_signon();
            if (is_wp_error($user)) {
                return $this->response([
                    'message' => $user->get_error_message()
                ], 403);
            }

            return $this->sendSuccess([
                'redirect' => $redirectUrl
            ]);
        }

        if (wp_check_password($password, $user->user_pass, $user->ID)) {
            $this->login($user->ID);
            return $this->sendSuccess([
                'redirect' => $redirectUrl
            ]);
        }

        return $this->response([
            'message' => __('<strong>Error</strong>: Invalid username, email address or incorrect password.', 'fluent-support')
        ], 403);
    }

    public function isRecaptchaApplicable($formName)
    {
        $reCaptchaSettingsData = Meta::where('object_type', '_fs_recaptcha_settings')->first();
        if(!isset($reCaptchaSettingsData->value)){
            return false;
        }
        $reCaptchaData = maybe_unserialize($reCaptchaSettingsData->value, []);
        if(!isset($reCaptchaData['is_enabled']) || !isset($reCaptchaData['formContainingReCaptcha'])){
            return false;
        }
        $isEnabled = filter_var($reCaptchaData['is_enabled'], FILTER_VALIDATE_BOOLEAN);
        if (!$isEnabled) {
            return false;
        }
        $formContainingReCaptcha = $reCaptchaData['formContainingReCaptcha'];
        return 'yes' === $formContainingReCaptcha[$formName];
    }

    private function nativeLoginHandler($user, $info, $redirectUrl = '')
    {
        if (!$redirectUrl) {
            $redirectUrl = Helper::getPortalBaseUrl();
        }

        $secure_cookie = is_ssl();
        if (!$secure_cookie && !force_ssl_admin()) {
            if (get_user_option('use_ssl', $user->ID)) {
                $secure_cookie = true;
                force_ssl_admin(true);
            }
        }

        if (class_exists('\Limit_Login_Attempts')) {
            global $limit_login_attempts_obj;
            $limit_login_attempts_try = $limit_login_attempts_obj->wp_authenticate_user($user, false);
            if (is_wp_error($limit_login_attempts_try)) {
                return $this->response([
                    'message' => implode('<br/>', $limit_login_attempts_try->get_error_messages())
                ], 403);
            }
        }

        $user_signon = wp_signon($info, $secure_cookie);

        if (!is_wp_error($user_signon) && empty($_COOKIE[LOGGED_IN_COOKIE])) {
            if (headers_sent()) {
                return $this->response([
                    'message' => sprintf(__('<strong>ERROR</strong>: Cookies are blocked due to unexpected output. For help, please see <a href="%1$s">this documentation</a> or try the <a href="%2$s">support forums</a>.'),
                        __('https://codex.wordpress.org/Cookies'), __('https://wordpress.org/support/'))
                ], 403);
            }
        }

        if (is_wp_error($user_signon)) {
            $errorMessage = __('Email or Password is not valid. Please try again', 'fluent-support');

            if (class_exists('Limit_Login_Attempts')) {
                global $limit_login_attempts_obj;
                if ($limit_login_attempts_obj) {
                    $limit_login_attempts_obj->limit_login_failed($user->user_login);
                    $msg = $limit_login_attempts_obj->get_message();
                    if ($msg) {
                        $errorMessage = $msg;
                    }
                }
            }

            return $this->response([
                'message' => $errorMessage
            ], 403);
        }

        // WP Last Login plugin compatibility
        if (class_exists('\Obenland_Wp_Last_Login')) {
            update_user_meta($user_signon->ID, 'wp-last-login', time());
        }

        return $this->sendSuccess([
            'redirect' => $redirectUrl
        ]);
    }

    /**
     * getRules method will prepare the rules for the input field
     * @param array $fields
     * @return mixed
     */
    protected function getRules($fields = [])
    {
        $rules = [];

        foreach ($fields as $fieldName => $field) {
            if (array_key_exists('required', $field)) {
                $rules[$fieldName] = 'required';
            }

            $pipe = array_key_exists($fieldName, $rules) ? '|' : '';

            if ($field['type'] === 'email') {
                $rules[$fieldName] = $rules[$fieldName] . $pipe . 'email';
            } elseif ($field['type'] === 'password') {
                $rules[$fieldName] = $rules[$fieldName] . $pipe . 'min:8';
            }
        }
        /*
         * Filter user signup validation rules
         *
         * @since v1.0.0
         * @param array $rules
         */
        return apply_filters('fluent_support/signup_validation_rules', $rules);
    }


    public function resetPassword(Request $request)
    {

        if(Helper::getAuthProvider() != 'fluent_support') {
            return $this->sendError([
                'message' => __('You are not allowed to reset password using this form', 'fluent-support')
            ]);
        }

        $errors = new \WP_Error();

        if (!wp_verify_nonce($request->get('_fsupport_reset_pass_nonce'), 'fluent_support_reset_pass_nonce')) {
            return $this->sendError([
                'message' => __('Security verification failed. Please try again', 'fluent-support')
            ]);
        }

        $usernameOrEmail = trim(wp_unslash($request->get('user_login')));

        if (!$usernameOrEmail) {
            return $this->sendError([
                'message' => 'Username or email is required'
            ]);
        }

        $user_data = get_user_by('email', $usernameOrEmail);

        if (!$user_data) {
            $user_data = get_user_by('login', $usernameOrEmail);
        }

        if (!$user_data) {
            return $this->sendError([
                'message' => __('Invalid username or email', 'fluent-support')
            ]);
        }

        $user_data = apply_filters('lostpassword_user_data', $user_data, $errors);

        do_action('lostpassword_post', $errors, $user_data);

        $errors = apply_filters('lostpassword_errors', $errors, $user_data);

        if ($errors->has_errors()) {
            return $this->sendError([
                'message' => $errors->get_error_message()
            ]);
        }

        if (!$user_data) {
            return $this->sendError([
                'message' => __('<strong>Error</strong>: There is no account with that username or email address.', 'fluent-support')
            ]);
        }

        if (is_multisite() && !is_user_member_of_blog($user_data->ID, get_current_blog_id())) {

            return $this->sendError([
                'message' => __('<strong>Error</strong>: Invalid username or email', 'fluent-support')
            ]);
        }

        // Redefining user_login ensures we return the right case in the email.
        $user_login = $user_data->user_login;

        do_action('retrieve_password', $user_login);

        $allow = apply_filters('allow_password_reset', true, $user_data->ID);

        if (!$allow) {
            return $this->sendError([
                'message' => __('Password reset is not allowed for this user', 'fluent-support')
            ]);
        }

        if (is_wp_error($allow)) {
            return $this->sendError([
                'message' => $allow->get_error_message()
            ]);
        }


        /*
         * Filter reset password link text
         *
         * @since v1.5.7
         * @param string $linkText
         */
        $linkText = apply_filters("fluent_support/reset_password_link", sprintf(__('Reset your password for %s', 'fluent-support'), get_bloginfo('name')));

        $resetUrl = add_query_arg([
            'action' => 'rp',
            'key' => get_password_reset_key($user_data),
            'login' => rawurlencode($user_data->user_login)
        ], wp_login_url());

        $resetLink = '<a href="' . $resetUrl . '">' . $linkText . '</a>';

        /*
         * Filter reset password email subject
         *
         * @since v1.5.7
         * @param string $mailSubject
         */
        $mailSubject = apply_filters("fluent_support/reset_password_mail_subject", sprintf(__('Reset your password for %s support portal', 'fluent-support'), get_bloginfo('name')));

        $message = sprintf(__('<p>Hi %s,</p>', 'fluent-support'), $user_data->first_name) .
            __('<p>Someone has requested a new password for the following account on WordPress:</p>', 'fluent-support') .
            sprintf(__('<p>Username: %s</p>', 'fluent-support'), $user_login) .
            sprintf(__('<p>%s</p>', 'fluent-support'), $resetLink) .
            sprintf(__('<p>If you did not request to reset your password, please ignore this email.</p>', 'fluent-support'));

        /*
         * Filter reset password email body text
         *
         * @since v1.5.7
         * @param string $message
         * @param object $user
         * @param string $resetLink
         */
        $message = apply_filters('fluent_support/reset_password_message', $message, $user_data, $resetLink);

        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail($user_data->user_email, $mailSubject, $message, $headers);

        return $this->sendSuccess([
            'message' => __('Please check your email for the reset link', 'fluent-support')
        ]);
    }

    /**
     * getMessages message will return the validation message regarding sign up or sign in
     * @param array $rules
     * @return mixed
     */
    protected function getMessages($rules = [])
    {
        /*
         * Filter user signup validation message
         *
         * @since v1.0.0
         * @param array $arg
         * @param array $rules
         */
        return apply_filters('fluent_support/signup_validation_messages', [], $rules);
    }

    /**
     * createUser method will create new user
     * @param array $formData
     * @return mixed
     */
    public function createUser($formData = [])
    {
        /*
         * Filter user signup email
         *
         * @since v1.0.0
         * @param string $email
         */
        $email = apply_filters('fluent_support/signup_email', Arr::get($formData, 'email'));

        /*
         * Filter user signup username
         *
         * @since v1.0.0
         * @param string $username
         */
        $userName = apply_filters('fluent_support/signup_username', Arr::get($formData, 'username'));

        if (empty($formData['password'])) {
            $password = wp_generate_password(8);
        } else {
            $password = $formData['password'];
        }

        /*
         * Filter user signup password
         *
         * @since v1.0.0
         * @param string $password
         */
        $password = apply_filters('fluent_support/signup_password', $password);

        /*
         * Action before creating WP user using Fluent Support signup form
         *
         * @since v1.0.0
         * @param string $userName
         * @param string $password
         * @param string $email
         */
        do_action('fluent_support/before_creating_user', $userName, $password, $email);

        $userId = wp_create_user($userName, $password, $email);

        if (is_wp_error($userId)) {
            return false;
        }

        return $userId;

    }

    /**
     * maybeUpdateUser method will update user information if exists
     * @param $userId
     * @param $formData
     */
    public function maybeUpdateUser($userId, $formData)
    {
        $name = trim(Arr::get($formData, 'first_name') . ' ' . Arr::get($formData, 'last_name'));

        $data = array_filter([
            'ID' => $userId,
            'user_nicename' => $name,
            'display_name' => $name,
            'first_name' => Arr::get($formData, 'first_name'),
            'last_name' => Arr::get($formData, 'last_name'),
        ]);

        if ($name) {
            /*
             * Action before updating a customer/user
             *
             * @since v1.0.0
             * @param array $data
             */
            do_action('fluent_support/before_updating_user', $data);

            /*
             * Filter user updatable data
             *
             * @since v1.0.0
             * @param $data
             */
            $updateUserData = apply_filters('fluent_support/update_user_data', $data);
            wp_update_user($updateUserData);

            /*
             * Action after updating a customer/user
             *
             * @since v1.0.0
             * @param array $data
             */
            do_action('fluent_support/after_updating_user', $data);
        }
    }

    public function addUserMetaData($userId, $formData) {
        $customFieldsKey = apply_filters('fluent_support/custom_registration_form_fields_key', Helper::getBusinessSettings('custom_registration_form_field'));

        if (empty($customFieldsKey)) {
            return;
        }

        foreach ($customFieldsKey as $key) {
            if (isset($formData[$key])) {
                $fieldValue = $formData[$key];
                update_user_meta($userId, $key, $fieldValue);
            }
        }
    }

    /**
     * assignRole method will assign role to a given user id
     * @param $userId
     */
    protected function assignRole($userId)
    {
        $user = new \WP_User($userId);

        /*
         * Action before assigning role to registered user
         *
         * @since v1.0.0
         * @param array $data
         */
        do_action('fluent_support/before_assigning_role', $user);
        /*
         * Filter user assignable role after signup
         *
         * @since v1.0.0
         * @param string $setRole WordPress user role key
         */
        $setRole = apply_filters('fluent_support/user_role', 'subscriber');
        $user->set_role($setRole);

        /*
         * Action after assigning role to registered user
         *
         * @since v1.0.0
         * @param array $data
         */
        do_action('fluent_support/after_assigning_role', $user);
    }


    /**
     * login method will clear existing cookies and set new cookie for a given user id
     * @param $userId
     */
    protected function login($userId)
    {
        /*
         * Action before login
         *
         * @since v1.0.0
         * @param integer $userId
         */
        do_action('fluent_support/before_logging_in_user', $userId);

        wp_clear_auth_cookie();
        wp_set_current_user($userId);
        wp_set_auth_cookie($userId);

        /*
         * Action after login
         *
         * @since v1.0.0
         * @param integer $userId
         */
        do_action('fluent_support/after_logging_in_user', $userId);
    }

}
