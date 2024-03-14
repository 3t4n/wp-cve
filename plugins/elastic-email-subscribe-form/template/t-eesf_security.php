<?php
defined('EE_ADMIN_SUBSCRIBE_7250232799') or die('No direct access allowed.');

wp_enqueue_style('eesubscribe-bootstrap-grid');
wp_enqueue_style('eesubscribe-css');

if (isset($_GET['settings-updated'])):
    ?>
    <div id="message" class="updated">
        <p><strong><?php _e('Settings saved.', 'elastic-email-subscribe-form') ?></strong></p>
    </div>
<?php endif; ?>

<div class="eewp-eckab-frovd">
<div class="eewp_container">
    <div class="col-12 col-md-12 col-lg-7">
        <?php
        if (get_option('eesf-connecting-status') === 'disconnected') {
            include 't-eesf_connecterror.php';
        } else { ?>
            <div class="ee_header">
                <div class="ee_pagetitle">
                    <h1><?php _e('Security', 'elastic-email-subscribe-form') ?></h1>
                </div>
            </div>

            <p><?php _e('Enter the Google reCaptcha integration keys to protect your forms against spam and abuse.', 'elastic-email-subscribe-form') ?></p>

            <div class="ee_security-container">
                <form action="options.php" method="post">
                    <?php
                    settings_fields('ee_security_options_group');
                    do_settings_sections('ee-security-settings');
                    ?>
                    <input name="Submit" class="ee_button-test" type="submit"
                           value="<?php esc_attr_e('Save Changes'); ?>"/>
                </form>
            </div>

            <div class="ee_security-faq-container">
                <div class="ee_security-faq-section-title">
                    <p>FAQ</p>
                </div>
                <div id="ee_security-faq">
                    <div class="ee_security-faq-item">
                        <div class="ee_security-faq-header">
                            <p><?php _e('What is reCaptcha?', 'elastic-email-subscribe-form') ?></p>
                        </div>
                        <div class="ee_security-faq-description">
                            <p>
                                <?php _e('Google reCAPTCHA is a free service that protects your site from spam and abuse. It uses advanced risk analysis techniques to tell the difference between humans and bots.', 'elastic-email-subscribe-form') ?>
                                <a href="https://developers.google.com/recaptcha/"><?php _e('Read more', 'elastic-email-subscribe-form') ?></a>.
                            </p>
                        </div>
                    </div>

                    <div class="ee_security-faq-item">
                        <div class="ee_security-faq-header">
                            <p><?php _e('Where can I find the keys to the integration with reCaptcha?', 'elastic-email-subscribe-form') ?></p>
                        </div>
                        <div class="ee_security-faq-description">
                            <p>
                                <?php _e('You can find the keys in the reCaptcha', 'elastic-email-subscribe-form') ?>
                                <a href="https://www.google.com/recaptcha/intro/v3.html"><?php _e('administration panel', 'elastic-email-subscribe-form') ?></a>.
                            </p>
                        </div>
                    </div>

                    <div class="ee_security-faq-item">
                        <div class="ee_security-faq-header">
                            <p><?php _e('Do I need an account to generate the integration key?', 'elastic-email-subscribe-form') ?></p>
                        </div>
                        <div class="ee_security-faq-description">
                            <p>
                                <?php _e('Yes. ReCaptcha is a service offered by Google. It is required to set up an account and properly configure this service for it to function.', 'elastic-email-subscribe-form') ?>
                            </p>
                        </div>
                    </div>

                    <div class="ee_security-faq-item">
                        <div class="ee_security-faq-header">
                            <p><?php _e('Which reCaptcha version do I need to generate the keys for?', 'elastic-email-subscribe-form') ?></p>
                        </div>
                        <div class="ee_security-faq-description">
                            <p><?php _e('Generating a key for reCaptcha v2 invisible is required for integration. Other options will not work with this plugin.', 'elastic-email-subscribe-form') ?></p>
                        </div>
                    </div>

                    <div class="ee_security-faq-item">
                        <div class="ee_security-faq-header">
                            <p><?php _e('Do I have to complete the fields?', 'elastic-email-subscribe-form') ?></p>
                        </div>
                        <div class="ee_security-faq-description">
                            <p><?php _e('No, it’s a recommended additional security for your forms. However, if you don\'t feel the need, leave these fields blank and the reCaptcha will be disabled.', 'elastic-email-subscribe-form') ?></p>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>
    </div>

    <?php
    include 't-eesf_marketing.php';
    ?>

    </div>
</div>