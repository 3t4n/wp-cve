<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div class="ms-main-up-wrapper">
    <?php
if (majesticsupport::$_config['offline'] == 2) {
    MJTC_message::MJTC_getMessage();
    include_once(MJTC_PLUGIN_PATH . 'includes/header.php'); ?>
    <div class="mjtc-support-top-sec-header">
        <img class="mjtc-transparent-header-img1" alt="<?php echo esc_html(__('image', 'majestic-support')); ?>"
            src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/tp-image.png" />
        <div class="mjtc-support-top-sec-left-header">
            <div class="mjtc-support-main-heading">
                <?php echo esc_html(__("Login",'majestic-support')); ?>
            </div>
            <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageBreadcrumps('login'); ?>
        </div>
    </div>
    <div class="mjtc-support-cont-main-wrapper">
        <div class="mjtc-support-cont-wrapper mjtc-support-cont-wrapper-color">
            <div class="mjtc-support-login-wrapper">
                <div class="mjtc-support-login">
                <?php
                $redirecturl = MJTC_request::MJTC_getVar('mjtc_redirecturl','GET', MJTC_majesticsupportphplib::MJTC_safe_encoding(majesticsupport::makeUrl(array('mjsmod'=>'majesticsupport','mjslay'=>'controlpanel'))));
                $redirecturl = MJTC_majesticsupportphplib::MJTC_safe_decoding($redirecturl);
                if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) { // Display WordPress login form:
                    $args = array(
                        'redirect' => $redirecturl,
                        'form_id' => 'loginform-custom',
                        'label_username' => esc_html(__('Username', 'majestic-support')),
                        'label_password' => esc_html(__('Password', 'majestic-support')),
                        'label_remember' => esc_html(__('Keep me login', 'majestic-support')),
                        'label_log_in' => esc_html(__('Login', 'majestic-support')),
                        'remember' => true
                    );
                    wp_login_form($args);
                }else{ // user not Staff
                    MJTC_layout::MJTC_getYouAreLoggedIn();
                }
                ?>
            </div>
        </div>
        <?php
} ?>
    </div>
</div>
