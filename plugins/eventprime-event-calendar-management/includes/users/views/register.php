<?php
/**
 * View: User Registration
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/users/register.php
 *
 */

defined( 'ABSPATH' ) || exit;

// check for already loggedin user
if( ! empty( $args->current_user ) && ! empty( $args->current_user->ID ) ) {?>
  <div class="emagic">
    <div id="ep-logged-in-register-user-wrap" class="ep-logged-in-user-wrap"> 
        <div class="ep-register-user ep-shadow-sm ep-border ep-rounded ep-p-5 ep-mb-3">
            <div class="ep-box-row">
                <div class="ep-box-col-3">
                    <img class="ep-rounded-circle" src="<?php echo esc_url( get_avatar_url( $args->current_user->ID ) ); ?>" style="height: 100px;">
                </div>
                <div class="ep-box-col-6 ep-text-center">
                    <div><?php esc_html_e( 'Welcome', 'eventprime-event-calendar-management' ); ?></div>
                    <div class="ep-fw-bold"><?php echo esc_html( ep_get_current_user_profile_name() ); ?></div>
                    <p><?php esc_html_e( 'You are already logged in', 'eventprime-event-calendar-management' ); ?></p>
                </div>
                <div class="ep-box-col-3"></div>
            </div>
            <div class="ep-box-row ep-pt-3 ep-pb-2">
                <div class="ep-box-col-12 ep-border-bottom"></div>
            </div>
            <div class="ep-box-row">
                <div class="ep-box-col-6 ep-align-left">
                    <a href="<?php echo esc_url(get_permalink(ep_get_global_settings('profile_page'))); ?>">
                        <?php esc_html_e('My Account', 'eventprime-event-calendar-management'); ?>
                    </a>
                </div>
                <div class="ep-box-col-6 ep-align-right">
                    <a href="<?php echo wp_logout_url(); ?>">
                        <?php esc_html_e('Logout', 'eventprime-event-calendar-management'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
  </div>   <?php
} else{
    do_action( 'ep_before_attendee_register_form' );

    $is_recaptcha_enabled = 0;
    if( $args->register_google_recaptcha == 1 && !empty($args->google_recaptcha_site_key) ){
        $is_recaptcha_enabled = 1;?>
        <script src='https://www.google.com/recaptcha/api.js'></script><?php 
    }?>
    <div class="emagic">
        <div id="ep_attendee_register_form_wrapper" class="<?php echo isset($args->block_register_class) ? esc_attr($args->block_register_class) : " "; ?>"><?php 
            $register_text = ep_global_settings_button_title('Register');
            if( ! empty( $args->register_button_label ) ) {
                $register_text = $args->register_button_label;
            }?>
            <!-- <h2><?php echo wp_kses_post( $register_text ); ?></h2> -->
            <?php $form = null;
            $registration_form = ep_get_global_settings( 'login_registration_form' );
            if( class_exists( 'Registration_Magic' ) && $registration_form == 'rm' ) {
                $form_id = absint(ep_get_global_settings('login_rm_registration_form'));
                if( ! empty( $form_id ) ) {
                    $form = new RM_Forms;
                    $form->load_from_db($form_id);
                }
            }
            if ( ! empty( $form ) && $form->form_type == 1 ){
                echo do_shortcode( "[RM_Form id='$form_id']" );
                $form_link = "javascript:void(0)";
                $login_page = ep_get_global_settings( 'login_page' );
                if( $login_page ) {
                    $form_link = esc_url( get_permalink( $login_page ) );
                }?>
 
                <div class="ep-form-row ep-form-group ep-mb-3">
                    <div class="ep-register-login">
                        <?php esc_html_e( 'Already have an Account?', 'eventprime-event-calendar-management' ); ?> 
                        <a href="<?php echo $form_link;?>" id="em_register_login1">
                            <?php echo esc_html__( 'Please','eventprime-event-calendar-management' ) . ' '. esc_html( $args->login_button_label ); ?>
                        </a>
                    </div>
                </div><?php
            } else{?>
                <form id="ep_attendee_register_form" class="ep-attendee-register-form ep-shadow-sm ep-border ep-rounded ep-p-5 ep-mb-3 wp-block-create-block-ep-register-block align<?php echo isset($args->align) ? esc_attr($args->align) : " ";?>" style="Color: <?php echo isset($args->textColor) ? esc_attr($args->textColor) . '!important' : " " ;?>; background-color: <?php echo isset($args->backgroundColor) ? esc_attr($args->backgroundColor) . '!important' : " "; ?>;" method="post">
                    <?php do_action( 'ep_attendee_register_form_start' );?>
                    <div class="ep-register-response"></div>
                    <?php if( ! empty( $args->register_username_show ) ) {?>
                        <div class="ep-form-row ep-form-group ep-mb-3">
                            <label for="user_name" class="ep-form-label">
                                <?php echo wp_kses_post( stripslashes( $args->register_username_label ) ); ?>&nbsp;
                                <?php if( $args->register_username_mandatory == 1 ) {?>
                                    <span class="required">*</span><?php
                                }?>
                            </label>
                            <input type="text" name="user_name" id="ep_register_user_name" class="ep-form-control ep-form-input ep-input-text" value="<?php echo ( ! empty( $_POST['user_name'] ) ) ? esc_attr( wp_unslash( $_POST['user_name'] ) ) : ''; ?>" <?php if( $args->register_username_mandatory == 1 ) { echo 'required="required"'; }?> />
                        </div><?php
                    }?>

                    <div class="ep-form-row ep-form-group ep-mb-3">
                        <label for="email" class="ep-form-label">
                            <?php echo wp_kses_post( stripslashes( $args->register_email_label ) ); ?>&nbsp;
                            <span class="required">*</span>
                        </label>
                        <input type="email" name="email" required id="ep_register_email" class="ep-form-control ep-form-input ep-input-email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" />
                    </div>
                    <?php if( ! empty( $args->register_password_show ) ) {?>
                        <div class="ep-form-row ep-form-group ep-mb-3">
                            <label for="password" class="ep-form-label">
                                <?php echo wp_kses_post( stripslashes( $args->register_password_label ) ); ?>&nbsp;
                                <?php if( $args->register_password_mandatory == 1 ) {?>
                                    <span class="required">*</span><?php
                                }?>
                            </label>
                            <input type="password" name="password" id="ep_register_password" class="ep-form-control ep-form-input ep-input-text" <?php if( $args->register_password_mandatory == 1 ) { echo 'required="required"'; }?> />
                        </div><?php
                    }?>
                    
                    <?php if( ! empty( $args->register_repeat_password_show ) ) {?>
                        <div class="ep-form-row ep-form-group ep-mb-3">
                            <label for="repeat_password" class="ep-form-label">
                                <?php echo wp_kses_post( stripslashes( $args->register_repeat_password_label ) ); ?>&nbsp;
                                <?php if( $args->register_repeat_password_mandatory == 1 ) {?>
                                    <span class="required">*</span><?php
                                }?>
                            </label>
                            <input type="password" name="repeat_password" id="ep_register_repeat_password" class="ep-form-control ep-form-input ep-input-text" <?php if( $args->register_repeat_password_mandatory == 1 ) { echo 'required="required"'; }?> />
                        </div><?php
                    }?>

                    <?php if( ! empty( $args->register_dob_show ) ) {?>
                        <div class="ep-form-row ep-form-group ep-mb-3">
                            <label for="dob" class="ep-form-label">
                                <?php echo esc_html( stripslashes( $args->register_dob_label ) ); ?>&nbsp;
                                <?php if( $args->register_dob_mandatory == 1 ) {?>
                                    <span class="required">*</span><?php
                                }?>
                            </label>
                            <input type="date" name="dob" id="ep_register_dob" class="ep-form-control ep-form-input ep-input-text" <?php if( $args->register_dob_mandatory == 1 ) { echo 'required="required"'; }?> />
                        </div><?php
                    }?>

                    <?php if( ! empty( $args->register_phone_show ) ) {?>
                        <div class="ep-form-row ep-form-group ep-mb-3">
                            <label for="dob" class="ep-form-label">
                                <?php echo wp_kses_post( stripslashes( $args->register_phone_label ) ); ?>&nbsp;
                                <?php if( $args->register_phone_mandatory == 1 ) {?>
                                    <span class="required">*</span><?php
                                }?>
                            </label>
                            <input type="tel" name="phone" id="ep_register_phone" class="ep-form-control ep-form-input ep-input-text" <?php if( $args->register_phone_mandatory == 1 ) { echo 'required="required"'; }?> />
                        </div><?php
                    }?>

                    <?php if( ! empty( $args->register_timezone_show ) ) {?>
                        <div class="ep-form-row ep-form-group ep-mb-3">
                            <label for="dob" class="ep-form-label">
                                <?php echo esc_html( stripslashes( $args->register_timezone_label ) ); ?>&nbsp;
                                <?php if( $args->register_timezone_mandatory == 1 ) {?>
                                    <span class="required">*</span><?php
                                }?>
                            </label>
                            <select name="timezone" id="ep_register_timezone" class="ep-form-control ep-form-input ep-input-text ep-py-1" <?php if( $args->register_timezone_mandatory == 1 ) { echo 'required="required"'; }?> />>
                                <?php 
                                echo wp_timezone_choice( 'UTC+0' );?>
                            </select>
                        </div><?php
                    }?>

                    <?php do_action( 'ep_attendee_register_form' ); ?>
                    
                    <?php 
                    if( $args->register_google_recaptcha == 1 && !empty($args->google_recaptcha_site_key) ){
                        echo '<div class="ep-form-row ep-form-group ep-mb-3">
                            <div class="g-recaptcha"  data-sitekey="'.$args->google_recaptcha_site_key.'"></div>
                        </div>'; 
                    } ?>
                    <!-- Register Button Section for blocks and default start -->
                    <?php
                    if ( ! empty( $args->block_register_button_label ) ) {?>
                        <div class="ep-form-row ep-register-btn-section ep-form-group ep-mb-3">
                            <?php wp_nonce_field( 'ep-attendee-register', 'ep-attendee-register-nonce' ); ?>
                            <div type="submit" class="ep-mb-2 ep-register-form-submit" name="ep_register" value="<?php esc_html_e( 'Register', 'eventprime-event-calendar-management' );?>" >
                                <span class="ep-spinner ep-spinner-border-sm ep-mr-1"></span><?php echo wp_kses_post( $register_text ); ?>
                            </div>
                            <input type="hidden" name="redirect" value="" />
                        </div>
                    <?php }  else {?>
                        <div class="ep-form-row ep-register-btn-section ep-form-group ep-mb-3">
                            <?php wp_nonce_field( 'ep-attendee-register', 'ep-attendee-register-nonce' ); ?>
                            <button type="submit" class="ep-btn ep-btn-dark ep-mb-2 ep-register-form-submit" name="ep_register" value="<?php esc_html_e( 'Register', 'eventprime-event-calendar-management' );?>" >
                                <span class="ep-spinner ep-spinner-border-sm ep-mr-1"></span><?php echo esc_html( $register_text ); ?>
                            </button>
                            <input type="hidden" name="redirect" value="<?php echo esc_attr( $args->redirect_url ); ?>" />
                        </div>
                    <?php } ?>
                    <!-- Register Button Section for blocks and default end -->
                    <?php 
                    $form_link = "javascript:void(0)";
                    $login_page = ep_get_global_settings( 'login_page' );
                    if( $login_page ) {
                        $form_link =  esc_url( get_permalink( $login_page ) );
                    }?>
                    <div class="ep-form-row ep-form-group ep-mb-3">
                        <div class="ep-register-login">
                            <?php echo wp_kses_post( stripslashes( $args->already_have_account_label ) );  ?> 
                            <a href="<?php echo $form_link;?>" id="em_register_login1">
                                <?php echo esc_html( $args->login_button_label ); ?>
                                
                            </a>
                        </div>
                    </div>
                    <?php do_action( 'ep_attendee_register_form_end' );?>
                </form><?php
            }?>
        </div>
    </div><?php
}?>