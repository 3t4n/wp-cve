<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://www.daffodilsw.com/
 * @since      1.0.0
 *
 * @package    Wp_Mp_Register_Login
 * @subpackage Wp_Mp_Register_Login/public/partials
 */

?>

<div id="wpmpRegisterSection" class="container-fluid">
    <div class="row">
        <div class="col-xs-8 col-md-10"> 
            <?php
            $wpmp_form_settings = get_option('wpmp_form_settings');
            $form_heading = empty($wpmp_form_settings['wpmp_signup_heading']) ? 'Register' : $wpmp_form_settings['wpmp_signup_heading'];

            // check if the user already login
            if (!is_user_logged_in()) :

                ?>

                <form name="wpmpRegisterForm" id="wpmpRegisterForm" method="post">
                    <h3><?php _e($form_heading, $this->plugin_name); ?></h3>

                    <div id="wpmp-reg-loader-info" class="wpmp-loader" style="display:none;">
                        <img src="<?php echo plugins_url('images/ajax-loader.gif', dirname(__FILE__)); ?>"/>
                        <span><?php _e('Please wait ...', $this->plugin_name); ?></span>
                    </div>
                    <div id="wpmp-register-alert" class="alert alert-danger" role="alert" style="display:none;"></div>
                    <div id="wpmp-mail-alert" class="alert alert-danger" role="alert" style="display:none;"></div>
                    <?php if ($token_verification): ?>
                        <div class="alert alert-info" role="alert"><?php _e('Your account has been activated, you can login now.', $this->plugin_name); ?></div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="firstname"><?php _e('First name', $this->plugin_name); ?></label>
                        <sup class="wpmp-required-asterisk">*</sup>
                        <input type="text" class="form-control" name="wpmp_fname" id="wpmp_fname" placeholder="First name">
                    </div>
                    <div class="form-group">
                        <label for="lastname"><?php _e('Last name', $this->plugin_name); ?></label>
                        <input type="text" class="form-control" name="wpmp_lname" id="wpmp_lname" placeholder="Last name">
                    </div>
                    <div class="form-group">
                        <label for="username"><?php _e('Username', $this->plugin_name); ?></label>
                        <sup class="wpmp-required-asterisk">*</sup>
                        <input type="text" class="form-control" name="wpmp_username" id="wpmp_username" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label for="email"><?php _e('Email', $this->plugin_name); ?></label>
                        <sup class="wpmp-required-asterisk">*</sup>
                        <input type="text" class="form-control" name="wpmp_email" id="wpmp_email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="password"><?php _e('Password', $this->plugin_name); ?></label>
                        <sup class="wpmp-required-asterisk">*</sup>
                        <input type="password" class="form-control" name="wpmp_password" id="wpmp_password" placeholder="Password" >
                    </div>
                    <div class="form-group">
                        <label for="confrim password"><?php _e('Confirm Password', $this->plugin_name); ?></label>
                        <sup class="wpmp-required-asterisk">*</sup>
                        <input type="password" class="form-control" name="wpmp_password2" id="wpmp_password2" placeholder="Confirm Password" >
                    </div>

                    <?php if ($wpmp_form_settings['wpmp_enable_captcha'] == '1') { ?>
                        <div class="form-group">
                            <label class="control-label" id="captchaOperation"></label>

                            <input type="text" placeholder="Captcha answer" class="form-control" name="wpmp_captcha" />

                        </div>
                    <?php } ?>

                    <input type="hidden" name="wpmp_current_url" id="wpmp_current_url" value="<?php echo get_permalink(); ?>" />
                    <input type="hidden" name="redirection_url" id="redirection_url" value="<?php echo get_permalink(); ?>" />

                    <?php
                    // this prevent automated script for unwanted spam
                    if (function_exists('wp_nonce_field'))
                        wp_nonce_field('wpmp_register_action', 'wpmp_register_nonce');

                    ?>
                    <button type="submit" class="btn btn-primary">
                        <?php
                        $submit_button_text = empty($wpmp_form_settings['wpmp_signup_button_text']) ? 'Register' : $wpmp_form_settings['wpmp_signup_button_text'];
                        _e($submit_button_text, $this->plugin_name);

                        ?></button>
                </form>
                <?php
            else:
                $current_user = wp_get_current_user();
                $logout_redirect = (empty($wpmp_form_settings['wpmp_logout_redirect']) || $wpmp_form_settings['wpmp_logout_redirect'] == '-1') ? '' : $wpmp_form_settings['wpmp_logout_redirect'];

                echo 'Logged in as <strong>' . ucfirst($current_user->user_login) . '</strong>. <a href="' . wp_logout_url(get_permalink($logout_redirect)) . '">Log out ? </a>';
            endif;

            ?>
        </div>
    </div>
</div>
