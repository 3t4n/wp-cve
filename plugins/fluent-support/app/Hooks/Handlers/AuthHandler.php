<?php

namespace FluentSupport\App\Hooks\Handlers;

use FluentSupport\App\App;
use FluentSupport\App\Modules\PermissionManager;
use FluentSupport\App\Services\Helper;
use FluentSupport\App\Services\Includes\CountryNames;
use FluentSupport\Framework\Support\Arr;
use FluentSupport\App\Models\Meta;

class AuthHandler
{

    protected $loaded = false;

    public function init()
    {
        add_shortcode('fluent_support_login', array($this, 'loginForm'));
        add_shortcode('fluent_support_signup', array($this, 'registrationForm'));
        add_shortcode('fluent_support_auth', array($this, 'authForm'));
        add_shortcode('fluent_support_reset_password', array($this, 'restPasswordForm'));
        add_action('wp_ajax_fluent_support_renew_rest_nonce', [$this, 'maybeRenewNonce']);
    }

    /**
     * loginForm will generate html for login form
     * @param $attributes
     * @return string
     */
    public function loginForm($attributes)
    {
        if (get_current_user_id()) {
            return '<p>' . __('You are already logged in.', 'fluent-support') . '</p>';
        }

        $attributes = $this->getShortcodes($attributes);
        if (!empty($attributes['redirect-to'])) {
            $redirect = $attributes['redirect-to'];
        } else {
            $redirect = Helper::getPortalBaseUrl();
        }


        $this->handleAlreadyLoggedIn($attributes);

        if ($this->authProvider() == 'fluent_auth') {
            // Will be handled by FluentAuth plugin
            $attributes['redirect_to'] = $redirect;
            return (new \FluentAuth\App\Hooks\Handlers\CustomAuthHandler())->loginForm($attributes);
        }

        $this->loadAssets();

        $return = '<div class="fst_login_form_auth_wrapper">';
        $return .= '<div id="fst_login_form" class="fst_login_wrapper">';


        /*
         * Filter login form
         *
         * @since v1.0.0
         *
         * @param array $loginArgs
         */
        $loginArgs = apply_filters('fluent_support/login_form_args', [
            'echo'           => false,
            'redirect'       => $redirect,
            'remember'       => true,
            'value_remember' => true,
        ]);

        $return .= wp_login_form($loginArgs);

        if ($attributes['show-signup'] == 'true') {
            $return .= '<p style="text-align: center">'
                . __('Not registered?', 'fluent-support')
                . ' <a href="#" id="fs_show_signup">'
                . __('Create an Account', 'fluent-support')
                . '</a></p>';
        }

        if ($attributes['show-reset-password'] == 'true') {
            $return .= '<p style="text-align: center">'
                . __('Forgot your password?', 'fluent-support')
                . ' <a href="#" id="fs_show_reset_password">'
                . __('Reset Password', 'fluent-support')
                . '</a></p>';
        }

        $return .= '</div>';

        if ($attributes['show-signup'] == 'true') {
            $return .= do_shortcode('[fluent_support_signup hide=true]');
        }

        if ($attributes['show-reset-password'] == 'true') {
            $return .= do_shortcode('[fluent_support_reset_password hide=true]');
        }

        $return .= '</div>';
        return $return;
    }

    /**
     * registrationForm method will generate html for sign up form
     * @param $attributes
     * @return string
     */
    public function registrationForm($attributes)
    {
        if (get_current_user_id()) {
            return '<p>' . __('You are already logged in.', 'fluent-support') . '</p>';
        }

        $attributes = $this->getShortcodes($attributes);
        $this->handleAlreadyLoggedIn($attributes);

        if ($this->authProvider() == 'fluent_auth') {
            return (new \FluentAuth\App\Hooks\Handlers\CustomAuthHandler())->registrationForm($attributes);
        }

        $customFieldsKey = apply_filters('fluent_support/custom_registration_form_fields_key', Helper::getBusinessSettings('custom_registration_form_field'));

        if (!empty($customFieldsKey)) {
            add_filter('fluent_support/registration_form_fields', function($fields) use ($customFieldsKey) {
                return $this->addCustomFieldsToRegistrationForm($fields,$customFieldsKey);
            });
        }

        $registrationFields = static::getSignupFields();
        $hide = $attributes['hide'] == 'true' ? 'hide' : '';

        $this->loadAssets($hide);

        return $this->buildRegistrationForm($registrationFields, $hide, $attributes);
    }

    // This method `buildRegistrationForm` will generate html for sign up form
    private function buildRegistrationForm($registrationFields, $hide, $attributes)
    {
        $registrationForm = '<div class="fst_registration_wrapper ' . $hide . '"><form id="fstRegistrationForm" class="fs_registration_form" method="post" name="fs_registration_form">';

        foreach ($registrationFields as $fieldName => $registrationField) {
            $registrationForm .= $this->renderField($fieldName, $registrationField);
        }

        $registrationForm .= '<input type="hidden" name="__redirect_to" value="' . $attributes['redirect-to'] . '">';
        $registrationForm .= '<input type="hidden" name="_fsupport_signup_nonce" value="' . wp_create_nonce('fluent_support_signup_nonce') . '">';
        $registrationForm .= '<button type="submit" id="fst_submit">' . $this->submitBtnLoadingSvg() . '<span>' . __('Signup', 'fluent-support') . '</span></button>';

        $registrationForm .= '</form>';

        $registrationForm .= apply_filters('fluent_support/before_registration_form_close', '', $registrationFields, $attributes);

        if ($hide) {
            $registrationForm .= '<p style="text-align: center">'
                . __('Already have an account?', 'fluent-support')
                . ' <a href="#" id="fs_show_login">'
                . __('Login', 'fluent-support')
                . '</a></p>';
        }

        $registrationForm .= '</div>';

        return $registrationForm;
    }

    public function restPasswordForm($attributes)
    {
        if (get_current_user_id()) {
            return '<p>' . __('You are already logged in.', 'fluent-support') . '</p>';
        }

        $attributes = $this->getShortcodes($attributes);

        if ($this->authProvider() == 'fluent_auth') {
            return (new \FluentAuth\App\Hooks\Handlers\CustomAuthHandler())->restPasswordForm($attributes);
        }

        $this->handleAlreadyLoggedIn($attributes);

        $resetPasswordFields = static::resetPasswordFields();
        $hide = $attributes['hide'] == 'true' ? 'hide' : '';

        $this->loadAssets($hide);

        return $this->buildResetPassForm($resetPasswordFields, $hide, $attributes);
    }

    // This method `buildResetPassForm` will generate html for password reset form
    private function buildResetPassForm($resetPasswordFields, $hide, $attributes)
    {
        $restePasswordForm = '<div class="fst_reset_pass_wrapper ' . $hide . '"><form id="fstResetPasswordForm" class="fs_reset_pass_form" method="post" name="fs_reset_pass_form">';

        foreach ($resetPasswordFields as $fieldName => $resetPasswordField) {
            $restePasswordForm .= $this->renderField($fieldName, $resetPasswordField);
        }

        $restePasswordForm .= '<input type="hidden" name="__redirect_to" value="' . $attributes['redirect-to'] . '">';
        $restePasswordForm .= '<input type="hidden" name="_fsupport_reset_pass_nonce" value="' . wp_create_nonce('fluent_support_reset_pass_nonce') . '">';
        $restePasswordForm .= '<button type="submit" id="fst_reset_pass">' . $this->submitBtnLoadingSvg() . '<span>' . __('Reset Password', 'fluent-support') . '</span></button>';

        $restePasswordForm .= '</form>';

        $restePasswordForm .= '</div>';

        return $restePasswordForm;
    }

    /**
     * authForm will render the login form html
     * @param $attributes
     * @return string
     */
    public function authForm($attributes)
    {
        if (get_current_user_id()) {
            return '<p>' . sprintf(__('You are already logged in. <a href="%s">Go to support portal</a>', 'fluent-support'), Helper::getPortalBaseUrl()) . '</p>';
        }

        $attributes = $this->getShortcodes($attributes);

        if ($this->authProvider() == 'fluent_auth') {
            return (new \FluentAuth\App\Hooks\Handlers\CustomAuthHandler())->authForm($attributes);
        }

        $authForm = do_shortcode('[fluent_support_login show-signup=true show-reset-password=true]');

        return $authForm;
    }

    /**
     * renderField method will generate html for a field
     * @param $fieldName
     * @param $field
     * @return string
     */
    private function renderField($fieldName, $field)
    {
        $fieldType = Arr::get($field, 'type');
        $isRequired = Arr::get($field, 'required');
        $isRequired = $isRequired ? 'is-required' : '';

        $html = '<div class="fst_field_group fst_field_' . $fieldName . '">';

        if ($label = Arr::get($field, 'label')) {
            $html .= '<div class="fst_field_label ' . $isRequired . '"><label for="' . Arr::get($field, 'id') . '">' . $label . '</label></div>';
        }

        $textTypes = ['text', 'email', 'password', 'number', 'date'];
        $selectTypes = ['select']; // Add 'select' type for dropdowns

        if (in_array($fieldType, $textTypes)) {
            $html .= $this->renderTextInput($fieldName, $field);
        } elseif (in_array($fieldType, $selectTypes)) {
            $html .= $this->renderSelectInput($fieldName, $field);
        } else {
            return '';
        }

        return $html . '</div>';
    }

    private function renderTextInput($fieldName, $field)
    {
        $inputAtts = array_filter([
            'type'        => esc_attr(Arr::get($field, 'type')),
            'id'          => esc_attr(Arr::get($field, 'id')),
            'placeholder' => esc_attr(Arr::get($field, 'placeholder')),
            'name'        => esc_attr($fieldName),
            'required'    => Arr::get($field, 'required') ? 'required' : '',
        ]);

        $atts = '';

        foreach ($inputAtts as $attKey => $att) {
            $atts .= $attKey . '="' . $att . '" ';
        }

        return '<div class="fs_input_wrap"><input ' . $atts . ' /></div>';
    }

    /**
     * Render a select input field based on the provided field configuration.
     *
     * @param string $fieldName The name of the select input field.
     * @param array $field The field configuration containing options and attributes.
     *
     * @return string The rendered select input field HTML.
     */
    private function renderSelectInput($fieldName, $field)
    {
        $choices = Arr::get($field, 'options', []);
        $placeholder = Arr::get($field, 'placeholder', '');

        $options = '<option value="" disabled selected>' . esc_attr($placeholder) . '</option>';

        foreach ($choices as $value => $optionData) {
            $options .= '<option value="' . esc_attr($value) . '">' . esc_attr($optionData) . '</option>';
        }

        $selectAtts = [
            'id' => esc_attr(Arr::get($field, 'id')),
            'name' => esc_attr($fieldName),
            'required' => Arr::get($field, 'required') ? 'required' : '',
        ];

        $select = '<div class="fs_input_wrap"><select ' . $this->renderAttributes($selectAtts) . '>' . $options . '</select></div>';

        return $select;
    }

    private function renderAttributes($attributes)
    {
        $atts = '';

        foreach ($attributes as $attKey => $att) {
            $atts .= $attKey . '="' . $att . '" ';
        }

        return $atts;
    }

    /**
     * getSignupFields method will return the list of fields that will be used for sign up form
     * @return mixed
     */
    public static function getSignupFields()
    {
        /*
         * Filter signup form field
         *
         * @since v1.0.0
         *
         * @param array $fields Form fields
         */
        return apply_filters('fluent_support/registration_form_fields', [
            'first_name' => [
                'required'    => true,
                'type'        => 'text',
                'label'       => __('First name', 'fluent-support'),
                'id'          => 'fst_first_name',
                'placeholder' => __('First name', 'fluent-support')
            ],
            'last_name'  => [
                'type'        => 'text',
                'label'       => __('Last Name', 'fluent-support'),
                'id'          => 'fst_last_name',
                'placeholder' => __('Last name', 'fluent-support')
            ],
            'username'   => [
                'required'    => true,
                'type'        => 'text',
                'label'       => __('Username', 'fluent-support'),
                'id'          => 'fst_username',
                'placeholder' => __('Username', 'fluent-support')
            ],
            'email'      => [
                'required'    => true,
                'type'        => 'email',
                'label'       => __('Email Address', 'fluent-support'),
                'id'          => 'fst_email',
                'placeholder' => __('Your Email Address', 'fluent-support')
            ],
            'password'   => [
                'required'    => true,
                'type'        => 'password',
                'label'       => __('Password', 'fluent-support'),
                'id'          => 'fst_password',
                'placeholder' => __('Password', 'fluent-support')
            ]
        ]);
    }

    private function addCustomFieldsToRegistrationForm($fields, $customFieldsKey) {
        $customFields = $this->allCustomFields();

        foreach ($customFieldsKey as $key) {
            $fields[$key] = $customFields[$key];
        }

        return $fields;
    }

    public static function allCustomFields(): array {
        $countryList = CountryNames::get();

        return apply_filters('fluent_support/custom_registration_form_fields', [
            'address_line_1' => [
                'required'    => false,
                'type'        => 'text',
                'label'       => __('Address Line 1', 'fluent-support'),
                'id'          => 'fst_address_line_1',
                'placeholder' => __('Address Line 1', 'fluent-support'),
            ],
            'address_line_2' => [
                'required'    => false,
                'type'        => 'text',
                'label'       => __('Address Line 2', 'fluent-support'),
                'id'          => 'fst_address_line_2',
                'placeholder' => __('Address Line 2', 'fluent-support'),
            ],
            'city'      => [
                'required'    => false,
                'type'        => 'text',
                'label'       => __('City', 'fluent-support'),
                'id'          => 'fst_city',
                'placeholder' => __('City', 'fluent-support'),
            ],
            'zip'   => [
                'required'    => false,
                'type'        => 'text',
                'label'       => __('Zip', 'fluent-support'),
                'id'          => 'fst_zip',
                'placeholder' => __('Zip', 'fluent-support'),
            ],
            'state'   => [
                'required'    => false,
                'type'        => 'text',
                'label'       => __('State', 'fluent-support'),
                'id'          => 'fst_state',
                'placeholder' => __('State', 'fluent-support'),
            ],
            'country' => [
                'required'   => false,
                'type'       => 'select',
                'label'      => __('Country', 'fluent-support'),
                'id'         => 'fst_country',
                'placeholder' => __('Select a Country', 'fluent-support'),
                'options'    =>  $countryList,
            ],
        ]);
    }

    public static function resetPasswordFields()
    {
        /*
         * Filter reset password form field
         *
         * @since v1.5.7
         *
         * @param array $fields Form fields
         */
        return apply_filters('fluent_support/reset_password_form', [
            'user_login' => [
                'required'    => true,
                'type'        => 'text',
                'label'       => __('Email Address', 'fluent-support'),
                'id'          => 'fst_email',
                'placeholder' => __('Your Email Address', 'fluent-support')
            ]
        ]);
    }

    protected function submitBtnLoadingSvg()
    {
        $loadingIcon = '<svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
           width="40px" height="20px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
        <path fill="#000" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">
          <animateTransform attributeType="xml"
            attributeName="transform"
            type="rotate"
            from="0 25 25"
            to="360 25 25"
            dur="0.6s"
            repeatCount="indefinite"/>
          </path>
        </svg>';

        /*
         * Filter signup form loading icon
         *
         * @since v1.0.0
         *
         * @param string $loadingIcon this accepts html element
         */
        return apply_filters('fluent_support/signup_loading_icon', $loadingIcon);
    }

    protected function getShortcodes($attributes)
    {
        /*
         * Filter shortcode behavior for agent
         *
         * @since v1.0.0
         *
         * @param array $shortCodeDefaults
         */
        $shortCodeDefaults = apply_filters('fluent_support/auth_shortcode_defaults', [
            'auto-redirect'       => false,
            'redirect-to'         => Helper::getPortalBaseUrl(),
            'hide'                => false,
            'show-signup'         => false,
            'show-reset-password' => false,
        ]);

        $attributes = shortcode_atts($shortCodeDefaults, $attributes);

        if (isset($attributes['redirect-to'])) {
            $attributes['redirect_to'] = $attributes['redirect-to'];
        }

        return $attributes;

    }

    protected function handleAlreadyLoggedIn($attributes)
    {
        if (get_current_user_id() && !wp_is_json_request() && is_singular()) {
            if ($attributes['auto-redirect'] === 'true') {
                $redirect = $attributes['redirect-to'];
                ?>
                <script type="text/javascript">
                    document.addEventListener("DOMContentLoaded", function () {
                        var redirect = "<?php echo esc_url($redirect); ?>";
                        window.location.replace(redirect);
                    });
                </script>
                <?php
            }
            die();
        }
    }

    public function loadAssets($hide = '')
    {
        if ($this->loaded) {
            return false;
        }

        $app = App::getInstance();
        $assets = $app['url.assets'];
        wp_enqueue_style('fluent_support_login_style', $assets . 'admin/css/all_public.css', [], FLUENT_SUPPORT_VERSION);
        wp_enqueue_script('fluent_support_login_helper', $assets . 'portal/js/login_helper.js', [], FLUENT_SUPPORT_VERSION);

        //Get Recaptcha settings and enqueue recaptcha script
        $reCaptchaSettingsData = Meta::where('object_type', '_fs_recaptcha_settings')->first();
        $reCaptchaData = ($reCaptchaSettingsData) ? maybe_unserialize($reCaptchaSettingsData->value, []) : '';

        if (!empty($reCaptchaData) && isset($reCaptchaData['is_enabled']) && $reCaptchaData['is_enabled'] == "true") {
            unset($reCaptchaData['secretKey']);
            $recaptchaVersion = $reCaptchaData["reCaptcha_version"];
            $reCaptchaApiUrl = 'https://www.google.com/recaptcha/api.js';

            if ("recaptcha_v3" === $recaptchaVersion) {
                $reCaptchaApiUrl .= '?render=' . $reCaptchaData["siteKey"];
            }

            wp_enqueue_script('recaptcha', $reCaptchaApiUrl);
        }

        wp_localize_script('fluent_support_login_helper', 'fluentSupportPublic', [
            'signup'                => rest_url($app->config->get('app.rest_namespace') . '/' . $app->config->get('app.rest_version')) . '/signup',
            'login'                 => rest_url($app->config->get('app.rest_namespace') . '/' . $app->config->get('app.rest_version')) . '/login',
            'nonce'                 => wp_create_nonce('wp_rest'),
            'hide'                  => $hide,
            'redirect_fallback'     => Helper::getPortalBaseUrl(),
            'fsupport_login_nonce'  => wp_create_nonce('fsupport_login_nonce'),
            'resetPass'             => rest_url($app->config->get('app.rest_namespace') . '/' . $app->config->get('app.rest_version')) . '/reset_pass',
            'reCaptchaSettingsData' => $reCaptchaData,
            'fs_two_fa'             => rest_url($app->config->get('app.rest_namespace') . '/' . $app->config->get('app.rest_version')) . '/two_fa'
        ]);


        $this->loaded = true;
    }

    public function maybeRenewNonce()
    {
        if (!PermissionManager::currentUserPermissions()) {
            wp_send_json([
                'error' => 'You do not have permission to do this'
            ], 403);
        }

        wp_send_json([
            'nonce' => wp_create_nonce('wp_rest')
        ], 200);
    }

    private function authProvider()
    {
        return \FluentSupport\App\Services\Helper::getAuthProvider();
    }
}
