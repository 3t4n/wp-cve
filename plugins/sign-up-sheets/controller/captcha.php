<?php
/**
 * Captcha Controller
 */

namespace FDSUS\Controller;

use FDSUS\Id;
use FDSUS\Model\Settings;
use FDSUS\Lib\ReCaptcha\ReCaptcha;
use WP_Error;

class Captcha extends Base
{
    /**
     * Construct
     */
    public function __construct()
    {
        if (!is_admin()) {
            add_action('fdsus_enqueue_scripts_styles_on_signup', array(&$this, 'enqueue'), 10, 0);
            add_filter('fdsus_error_before_add_signup', array(&$this, 'signupValidation'), 10, 3);
        }

        parent::__construct();
    }

    /**
     * Enqueue
     *
     * @return void
     */
    public function enqueue()
    {
        wp_register_script(
            'fdsus-recaptcha',
            'https://www.google.com/recaptcha/api.js',
            array(),
            Id::version()
        );

        if (Settings::isRecaptchaEnabled()) {
            wp_enqueue_script('fdsus-recaptcha');
        }
    }

    /**
     * Validation on signup
     *
     * @param string|WP_Error $errorMsg
     * @param int             $taskId
     * @param int             $taskIndex
     *
     * @return string|WP_Error
     */
    public function signupValidation($errorMsg, $taskId, int $taskIndex)
    {
        if ($taskIndex !== 0) {
            // Only run the first time (if looping through multiple tasks)
            return $errorMsg;
        }

        if (!Settings::isAllCaptchaDisabled() && Settings::isRecaptchaEnabled()
            && empty($_POST['spam_check'])
            && !isset($_POST['double_signup'])
        ) {
            $recaptcha = new ReCaptcha(get_option('dls_sus_recaptcha_private_key'));
            $resp = $recaptcha->setExpectedHostname($_SERVER['HTTP_HOST'])
                ->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
            if (!$resp->isSuccess()) {
                return new WP_Error(
                    'fdsus-captcha-error',
                    __('Please check that the reCAPTCHA field is valid.', 'fdsus')
                );
            }
        } elseif (!Settings::isRecaptchaEnabled()
            && (empty($_POST['spam_check']) || (!empty($_POST['spam_check']) && trim($_POST['spam_check']) != '8'))
            && !Settings::isAllCaptchaDisabled()
        ) {
            return new WP_Error(
                'fdsus-captcha-error', sprintf(
                /* translators: %s is replaced with the users response to the simple captcha */
                    esc_html__('Oh dear, 7 + 1 does not equal %s. Please try again.', 'fdsus'),
                    esc_attr($_POST['spam_check'])
                )
            );
        }

        return $errorMsg;
    }
}
