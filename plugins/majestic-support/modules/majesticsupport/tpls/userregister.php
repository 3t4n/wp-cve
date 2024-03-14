<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest() && majesticsupport::$_config['show_captcha_on_visitor_from_ticket'] == 1 && majesticsupport::$_config['captcha_selection'] == 1) {
    wp_enqueue_script( 'majesticsupport-recaptcha', 'https://www.google.com/recaptcha/api.js' );
}
$majesticsupport_js ="
    function onSubmit(token) {
        document.getElementById('ms_registration_form').submit();
    }
";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>
<div class="ms-main-up-wrapper">
    <?php
if (majesticsupport::$_config['offline'] == 2) {
    if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) {
        // check to make sure user registration is enabled
        $is_enable = get_option('users_can_register');
        // only show the registration form if allowed
        if ($is_enable) {
            MJTC_message::MJTC_getMessage();
            include_once(MJTC_PLUGIN_PATH . 'includes/header.php'); ?>
            <div class="mjtc-support-top-sec-header">
                <img class="mjtc-transparent-header-img1" alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
                    src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/tp-image.png" />
                <div class="mjtc-support-top-sec-left-header">
                    <div class="mjtc-support-main-heading">
                        <?php echo esc_html(__("Register",'majestic-support')); ?>
                    </div>
                    <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageBreadcrumps('register'); ?>
                </div>
            </div>
    <div class="mjtc-support-cont-main-wrapper">
        <div class="mjtc-support-cont-wrapper mjtc-support-cont-wrapper-color">
            <div class="mjtc-support-add-form-wrapper">
                <?php MJTC_show_error_messages();?>
                <!-- show any error messages after form submission -->
                <form id="ms_registration_form" class="ms_form" action="" method="POST">
                    <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                        <div class="mjtc-support-from-field-title">
                            <?php echo esc_html(__('Username','majestic-support')); ?> <span style="color:red">*</span>
                        </div>
                        <div class="mjtc-support-from-field">
                            <input name="ms_user_login" id="ms_user_login"
                                class="required mjtc-support-form-field-input" type="text" />
                        </div>
                    </div>
                    <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                        <div class="mjtc-support-from-field-title">
                            <?php echo esc_html(__('Email','majestic-support')); ?> <span style="color:red">*</span>
                        </div>
                        <div class="mjtc-support-from-field">
                            <input name="ms_user_email" id="ms_user_email"
                                class="required mjtc-support-form-field-input" type="text" />
                        </div>
                    </div>
                    <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                        <div class="mjtc-support-from-field-title">
                            <?php echo esc_html(__('First Name','majestic-support')); ?>
                        </div>
                        <div class="mjtc-support-from-field">
                            <input name="ms_user_first" id="ms_user_first"
                                class="required mjtc-support-form-field-input" type="text" />
                        </div>
                    </div>
                    <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                        <div class="mjtc-support-from-field-title">
                            <?php echo esc_html(__('Last Name','majestic-support')); ?>
                        </div>
                        <div class="mjtc-support-from-field">
                            <input name="ms_user_last" id="ms_user_last" class="required mjtc-support-form-field-input"
                                type="text" />
                        </div>
                    </div>
                    <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                        <div class="mjtc-support-from-field-title">
                            <?php echo esc_html(__('Password','majestic-support')); ?> <span style="color:red">*</span>
                        </div>
                        <div class="mjtc-support-from-field">
                            <input name="ms_user_pass" id="password" class="required mjtc-support-form-field-input"
                                type="password" />
                        </div>
                    </div>
                    <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                        <div class="mjtc-support-from-field-title">
                            <?php echo esc_html(__('Repeat Password','majestic-support')); ?> <span style="color:red">*</span>
                        </div>
                        <div class="mjtc-support-from-field">
                            <input name="ms_user_pass_confirm" id="password_again"
                                class="required mjtc-support-form-field-input" type="password" />
                        </div>
                    </div>

                    <?php
                    if(in_array('mailchimp',majesticsupport::$_active_addons)){
                        ?>
                    <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                        <div class="mjtc-support-from-field">
                            <label class="mjtc-support-subscribe">
                                <input name="ms_mailchimp_subscribe" id="ms_mailchimp_subscribe" value="1" class=""
                                    type="checkbox" />
                                <?php echo esc_html(__('Subscribe to the newsletter','majestic-support')); ?>
                            </label>
                        </div>
                    </div>
                    <?php
                    }
                    ?>

                    <?php
                        if (majesticsupport::$_config['captcha_on_registration'] == 1) { ?>
                    <div class="mjtc-support-from-field-wrp mjtc-support-from-field-wrp-full-width">
                        <div class="mjtc-support-from-field-title">
                            <?php echo esc_html(__('Captcha', 'majestic-support')); ?>
                        </div>
                        <div class="mjtc-support-from-field">
                            <?php
                            $google_recaptcha_3 = false;
                            if (majesticsupport::$_config['captcha_selection'] == 1) { // Google recaptcha
                                $error = null;
                                if (majesticsupport::$_config['recaptcha_version'] == 1) {
                                    $data =  wp_enqueue_script( 'majesticsupport-recaptcha', 'https://www.google.com/recaptcha/api.js' );
                                    $data .= '<div class="g-recaptcha" data-sitekey="'.wp_kses_post(majesticsupport::$_config['recaptcha_publickey']).'"></div>';
                                    echo wp_kses($data, MJTC_ALLOWED_TAGS);
                                } else {
                                    $google_recaptcha_3 = true;
                                }
                            } else { // own captcha
                                $captcha = new MJTC_captcha;
                                echo wp_kses($captcha->MJTC_getCaptchaForForm(), MJTC_ALLOWED_TAGS);

                            }
                            ?>
                        </div>
                    </div>
                    <?php }
                        MJTC_includer::MJTC_getModel('fieldordering')->getFieldsOrderingforForm(3);
                           foreach (majesticsupport::$_data['fieldordering'] as $field) {
                               echo wp_kses(MJTC_includer::MJTC_getObjectClass('customfields')->MJTC_formCustomFields($field), MJTC_ALLOWED_TAGS);
                           ?>
                    <?php } ?>
                    <input type="hidden" name="ms_support_register_nonce"
                        value="<?php echo esc_attr(wp_create_nonce('ms-support-register-nonce')); ?>" />
                    <div class="mjtc-support-form-btn-wrp">
                        <?php
                        if($google_recaptcha_3 == true && MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()){ // to handle case of google recpatcha version 3
                            echo wp_kses(MJTC_formfield::MJTC_button('save', esc_html(__('Register', 'majestic-support')), array('class' => 'mjtc-support-save-button g-recaptcha', 'data-callback' => 'onSubmit', 'data-action' => 'submit', 'data-sitekey' => esc_attr(majesticsupport::$_config['recaptcha_publickey']))), MJTC_ALLOWED_TAGS);
                        } else {
                            echo wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Register', 'majestic-support')), array('class' => 'mjtc-support-save-button')), MJTC_ALLOWED_TAGS);
                        } ?>
                        <a href="<?php echo esc_url(majesticsupport::makeUrl(array('mjsmod'=>'majesticsupport', 'mjslay'=>'controlpanel')));?>"
                            class="mjtc-support-cancel-button"><?php echo esc_html(__('Cancel','majestic-support')); ?></a>
                    </div>
                </form>
            </div>
            <?php
        } else {
            MJTC_layout::MJTC_getRegistrationDisabled();
        }
    }else{
            MJTC_layout::MJTC_getYouAreLoggedIn();
    }
}
if(isset($google_recaptcha) && $google_recaptcha){
    wp_enqueue_script( 'majesticsupport-recaptcha', 'https://www.google.com/recaptcha/api.js' );
}
?>
        </div>
    </div>
</div>