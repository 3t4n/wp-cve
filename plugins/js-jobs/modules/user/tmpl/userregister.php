<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (!is_user_logged_in()) {
    // check to make sure user registration is enabled
    $is_enable = get_option('users_can_register');
    // only show the registration form if allowed
    if ($is_enable) {
        ?>
        <!-- registration form fields -->
    <div id="jsjobs-wrapper">
        <div class="page_heading">
            <?php echo __('Register New Account', 'js-jobs'); ?>
        </div>
        <?php
        // show any error messages after form submission
        jsjobs_show_error_messages();
        ?>
        <form id="jsjobs_registration_form" class="jsjobs_form" action="" method="POST">
            <fieldset>
                <p>
                    <label for="jsjobs_user_Login"><?php _e('Username'); ?>*</label>
                    <input name="jsjobs_user_login" id="jsjobs_user_login" class="required" type="text"/>
                </p>
                <p>
                    <label for="jsjobs_user_email"><?php _e('Email'); ?>*</label>
                    <input name="jsjobs_user_email" id="jsjobs_user_email" class="required" type="email"/>
                </p>
                <p>
                    <label for="jsjobs_user_first"><?php _e('First name'); ?></label>
                    <input name="jsjobs_user_first" id="jsjobs_user_first" type="text"/>
                </p>
                <p>
                    <label for="jsjobs_user_last"><?php _e('Last name'); ?></label>
                    <input name="jsjobs_user_last" id="jsjobs_user_last" type="text"/>
                </p>
                <p>
                    <label for="password"><?php _e('Password'); ?>*</label>
                    <input name="jsjobs_user_pass" id="password" class="required" type="password"/>
                </p>
                <p>
                    <label for="password_again"><?php _e('Password Again'); ?>*</label>
                    <input name="jsjobs_user_pass_confirm" id="password_again" class="required" type="password"/>
                </p>
                <p>
                    <?php
                    do_action('register_form');
                    ?>

                    <?php
                    $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('captcha');
                    $google_recaptcha = false;
                    if ($config_array['cap_on_reg_form'] == 1) {
                        if ($config_array['captcha_selection'] == 1) { // Google recaptcha
                            $google_recaptcha = true; 
                            ?>
                            <div class="g-recaptcha" data-sitekey="<?php echo esc_attr($config_array['recaptcha_publickey']);?>"></div>
                        <?php
                        } else { // own captcha
                            $captcha = new JSJOBScaptcha;
                            echo wp_kses($captcha->getCaptchaForForm(), JSJOBS_ALLOWED_TAGS);
                        }
                    }
                    ?>
                    <input type="hidden" name="jsjobs_jobs_register_nonce" value="<?php echo esc_attr(wp_create_nonce('jsjobs-jobs-register-nonce')); ?>"/>
                    <div id="save">
                        <input type="submit" id="save" value="<?php _e('Register New Account','js-jobs'); ?>"/>
                    </div>
                </p>
            </fieldset>
        </form>
    </div>
        <?php
    } else {
        JSJOBSlayout::getRegistrationDisabled();
    }
}
?>
<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
if(isset($google_recaptcha) && $google_recaptcha){
    wp_enqueue_script('jsjobs-repaptcha-scripti','https://www.google.com/recaptcha/api.js');
}
?>
</div>
