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

<div id="wpmpLoginSection" class="container-fluid">
    <div class="row">
        <div class="col-xs-8 col-md-10"> 
            <?php
            $wpmp_redirect_settings = get_option('wpmp_redirect_settings');
            $wpmp_form_settings = get_option('wpmp_form_settings');

            // check if the user already login
            if (!is_user_logged_in()) :
                
                $form_heading = empty($wpmp_form_settings['wpmp_signin_heading']) ? 'Login' : $wpmp_form_settings['wpmp_signin_heading'];
                $submit_button_text = empty($wpmp_form_settings['wpmp_signin_button_text']) ? 'Login' : $wpmp_form_settings['wpmp_signin_button_text'];
                $forgotpassword_button_text = empty($wpmp_form_settings['wpmp_forgot_password_button_text']) ? 'Forgot Password' : $wpmp_form_settings['wpmp_forgot_password_button_text'];
                if(isset($_GET['wpmp_reset_password_token']) && $_GET['wpmp_reset_password_token'] !=''){
                    $is_url_has_token = $_GET['wpmp_reset_password_token'];
                }else{ $is_url_has_token; }
                
                ?>
                <form name="wpmpLoginForm" id="wpmpLoginForm" method="post" class="<?php echo empty($is_url_has_token) ? '' : 'hidden' ?>">
                    
                    <h3><?php _e($form_heading, $this->plugin_name); ?></h3>
                    <div id="wpmp-login-loader-info" class="wpmp-loader" style="display:none;">
                        <img src="<?php echo plugins_url('images/ajax-loader.gif', dirname(__FILE__)); ?>"/>
                        <span><?php _e('Please wait ...', $this->plugin_name); ?></span>
                    </div>
                    <div id="wpmp-login-alert" class="alert alert-danger" role="alert" style="display:none;"></div>

                    <div class="form-group">
                        <label for="username"><?php _e('Username/Email', $this->plugin_name); ?></label>
                        <input type="text" class="form-control" name="wpmp_username" id="wpmp_username" placeholder="Username/Email">
                    </div>
                    <div class="form-group">
                        <label for="password"><?php _e('Password', $this->plugin_name); ?></label>
                        <input type="password" class="form-control" name="wpmp_password" id="wpmp_password" placeholder="Password" >
                    </div>
                    <?php
                    $login_redirect = (empty($wpmp_redirect_settings['wpmp_login_redirect']) || $wpmp_redirect_settings['wpmp_login_redirect'] == '-1') ? '' : $wpmp_redirect_settings['wpmp_login_redirect'];
                    
                    ?>
                    <input type="hidden" name="redirection_url" id="redirection_url" value="<?php echo get_permalink($login_redirect); ?>" />

                    <?php
                    // this prevent automated script for unwanted spam
                    if (function_exists('wp_nonce_field'))
                        wp_nonce_field('wpmp_login_action', 'wpmp_login_nonce');

                    ?>
                    <button type="submit" class="btn btn-primary"><?php _e($submit_button_text, $this->plugin_name); ?></button>
                    <?php
                        //render forgot password button
                        if($wpmp_form_settings['wpmp_enable_forgot_password']){                            
                    ?>
                    <button id="btnForgotPassword" type="button" class="btn btn-primary"><?php _e($forgotpassword_button_text, $this->plugin_name); ?></button>
                    <?php
                        }
                    ?>
                </form>
                <?php
                    //render the reset password form
                    if($wpmp_form_settings['wpmp_enable_forgot_password']){
                        echo do_shortcode('[wpmp_resetpassword_form]');
                    }
                ?>
            
                <?php
            else:
                $current_user = wp_get_current_user();
                $logout_redirect = (empty($wpmp_redirect_settings['wpmp_logout_redirect']) || $wpmp_redirect_settings['wpmp_logout_redirect'] == '-1') ? '' : $wpmp_redirect_settings['wpmp_logout_redirect'];                
                echo 'Logged in as <strong>' . ucfirst($current_user->user_login) . '</strong>. <a href="' . wp_logout_url(get_permalink($logout_redirect)) . '">Log out ? </a>';

            endif;

            ?>
        </div>
    </div>
</div>
