<?php
/**
 * View: User Login
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/users/login.php
 *
 */
defined( 'ABSPATH' ) || exit;

// check for already loggedin user
if( ! empty( $args->current_user ) && ! empty( $args->current_user->ID ) ) {?>
    <div class="emagic">
        <div id="ep-logged-in-user-wrap" class="ep-logged-in-user-wrap">   
            <div class="ep-logged-user ep-py-3 ep-border ep-rounded">
                <div class="ep-box-row">
                    <div class="ep-box-col-3 ep-text-center">
                        <img class="ep-rounded-circle" src="<?php echo esc_url(get_avatar_url($args->current_user->ID)); ?>" style="height: 100px;">
                    </div>
                    <div class="ep-box-col-6 ep-text-center">
                        <div><?php esc_html_e('Welcome', 'eventprime-event-calendar-management'); ?></div>
                        <div class="ep-fw-bold"><?php echo esc_html(ep_get_current_user_profile_name()); ?></div>
                        <p><?php esc_html_e('You are already logged in', 'eventprime-event-calendar-management'); ?></p>
                    </div>
                    
                    <div class="ep-box-col-3">
                    </div>
                </div>
                
                <div class="ep-box-row ep-pt-3 ep-pb-2">
                    <div class="ep-box-col-12 ">
                        <div class="ep-border-bottom"></div>
                    </div>
                </div>
                
                <div class="ep-box-row">
                    <div class="ep-box-col-6 ep-align-left">
                        <a href="<?php echo esc_url(get_permalink(ep_get_global_settings('profile_page'))); ?>" class="ep-px-2">
                            <?php esc_html_e('My Account', 'eventprime-event-calendar-management'); ?>
                        </a>

                    </div>
                    <div class="ep-box-col-6 ep-align-right">
                        <a href="<?php echo wp_logout_url(); ?>" class="ep-px-2">
                            <?php esc_html_e('Logout', 'eventprime-event-calendar-management'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>    
    </div><?php
} else{
    do_action( 'ep_before_attendee_login_form' );
    $is_recaptcha_enabled = 0;
    if( $args->login_google_recaptcha == 1 && ! empty( $args->google_recaptcha_site_key ) ) {
        $is_recaptcha_enabled = 1;?>
        <script src='https://www.google.com/recaptcha/api.js'></script><?php 
    }
    $redirect_url = $args->redirect_url;
    if( isset( $_GET['redirect'] ) ) {
        $redirect_url = sanitize_url( sanitize_text_field( $_GET['redirect'] ) );
        
    }?>
    <!-- Login Form Wrapper -->
    <div class="emagic">
        <div id="ep_attendee_login_form_wrapper" class="<?php echo isset($args->block_login_class) ? esc_attr($args->block_login_class) :" "; ?>">
            <?php
            $register_text = ep_global_settings_button_title('Register');
             ?>
            <div class="ep-login-form ep-shadow-sm ep-border ep-rounded ep-p-5 ep-mb-3 wp-block-create-block-ep-login-block align<?php echo isset($args->align) ? esc_attr($args->align) : ''; ?>" style="Color: <?php echo isset($args->textColor) ? esc_attr($args->textColor) . '!important' : ''; ?>; background-color: <?php echo isset($args->backgroundColor) ? esc_attr($args->backgroundColor) . '!important' : ''; ?>">
                <div class="ep-event-avatar ep-bg-primary ep-rounded-circle"><i class="material-icons">&#xE7FF;</i></div> 
                <h4 class="ep-modal-title ep-text-center ep-my-3" style="Color: <?php echo isset($args->textColor) ? esc_attr($args->textColor) : " "; ?>;">
                    <?php echo wp_kses_post( stripslashes( $args->login_heading_text ) );?>
                </h4>
                <div class="ep-modal-sub-heading ep-text-center ep-my-3 ep-fs-6 ep-text-muted">
                    <?php echo esc_html( stripslashes( $args->login_subheading_text ) );?>
                </div>
                <form class="ep-attendee-login-form" id="ep_attendee_login_form" method="post">
                    <?php do_action( 'ep_attendee_login_form_start' );?>
                    <div class="ep-login-response ep-mb-3"></div>
                    <div class="ep-form-row ep-form-group ep-mb-3">
                        <label for="user_name" class="ep-form-label">
                            <?php echo wp_kses_post( stripslashes( $args->login_username_label ) );?>
                            <input type="hidden" name="login_id_field" class="ep-form-control" value="<?php echo esc_attr( $args->login_id_field );?>">
                            <span class="required ep-text-danger">*</span>
                        </label>
                        <input type="text" name="user_name" required id="ep_login_user_name" class="ep-form-input ep-input-text ep-form-control" value="<?php echo ( ! empty( $_POST['user_name'] ) ) ? esc_attr( wp_unslash( $_POST['user_name'] ) ) : ''; ?>" />
                    </div>

                    <div class="ep-form-row ep-form-group ep-mb-3">
                        <label for="password" class="ep-form-label">
                            <?php echo wp_kses_post( stripslashes( $args->login_password_label ) );?>
                            <span class="required ep-text-danger">*</span>
                        </label>
                        <input type="password" name="password" required id="ep_login_password" class="ep-form-control ep-form-input ep-input-text" />
                    </div>
                    
                    <?php 
                    if( $args->login_google_recaptcha == 1 && !empty($args->google_recaptcha_site_key) ){?>
                        <div class="ep-form-row">
                            <div class="g-recaptcha"  data-sitekey="<?php echo esc_attr( $args->google_recaptcha_site_key );?>"></div>
                        </div><?php 
                    } ?>
                    
                    <?php do_action( 'ep_attendee_login_form' ); ?>
                    
                    <div class="ep-form-row ep-form-group ep-text-small ep-d-flex ep-justify-content-between ep-mb-3">
                        <?php if( ep_get_global_settings( 'login_show_rememberme' ) !== 0 ){ ?>
                            <label for="ep_login_rememberme" class="ep-form-label ep-checkbox-inline ep-text-small">
                                <input type="checkbox" name="rememberme" id="ep_login_rememberme" class="ep-form-input ep-input-checkbox" />
                                <?php echo wp_kses_post( stripslashes( $args->login_show_rememberme_label ) );?>&nbsp;
                            </label><?php
                        }?>
                            
                        <?php if( ep_get_global_settings( 'login_show_forgotpassword' ) !== 0 ){ ?>
                            <div class="ep-login-forgotpass ep-text-small">
                                <?php echo wp_kses_post( stripslashes( $args->login_show_forgotpassword_label ) ); ?>
                                <a href="<?php echo esc_url( wp_login_url() . '?action=lostpassword');?>" id="ep_login_forgotpass">
                                <?php echo wp_kses_post( stripslashes( $args->login_click_here_label ) );?>
                                </a>
                            </div><?php
                        }?>   
                    </div>
                    <!-- Login Button Section for blocks and default start -->
                        <?php
                            if ( ! empty( $args->block_login_button_label ) ) {?>
                                <div class="ep-form-row ep-login-btn-section ep-mb-3">
                                <?php wp_nonce_field( 'ep-attendee-login', 'ep-attendee-login-nonce' ); ?>
                                <div type="submit" class="ep-box-w-100 ep-mb-2 ep-py-2 ep-login-form-submit ep-text-center" name="ep_login">
                                    <span class="ep-spinner ep-spinner-border-sm ep-mr-1"></span><?php echo wp_kses_post( stripslashes( $args->login_button_label ) ); ?>
                                </div>
                                <?php if( $redirect_url == 'reload' || $redirect_url == 'off' || $redirect_url == 'no-redirect' ) {?>
                                    <input type="hidden" name="redirect" value="<?php echo esc_attr( $redirect_url ); ?>" /><?php
                            } else{?>
                                <input type="hidden" name="redirect" value="<?php echo esc_url( $redirect_url ); ?>" /><?php
                            }?>
                        </div>
                           <?php }  else {?>
                            <div class="ep-form-row ep-login-btn-section ep-mb-3">
                                <?php wp_nonce_field( 'ep-attendee-login', 'ep-attendee-login-nonce' ); ?>
                                <button type="submit" class="ep-btn ep-btn-dark ep-box-w-100 ep-mb-2 ep-py-2 ep-login-form-submit" name="ep_login" value="<?php echo esc_attr( stripslashes( $args->login_button_label ) ); ?>">
                                    <span class="ep-spinner ep-spinner-border-sm ep-mr-1"></span><?php echo esc_html( stripslashes( $args->login_button_label ) ); ?>
                                </button>
                                <?php if( $redirect_url == 'reload' || $redirect_url == 'off' || $redirect_url == 'no-redirect' ) {?>
                                    <input type="hidden" name="redirect" value="<?php echo esc_attr( $redirect_url ); ?>" /><?php
                                } else{?>
                                    <input type="hidden" name="redirect" value="<?php echo esc_url( $redirect_url ); ?>" /><?php
                                }?>
                    </div>
                    <?php } ?>
                    <!-- Login Button Section for blocks and default end -->

                    <?php if( ep_get_global_settings( 'login_show_registerlink' ) !== 0 ){ ?>
                        <div class="ep-form-row">
                            <div class="ep-login-register">
                                <?php
                                $form_link = 'javascript:void(0)';
                                $form_id = 'em_login_register';
                                $login_registration_form = ep_get_global_settings( 'login_registration_form' );
                                if( $login_registration_form == 'wp' ) {
                                    $form_link = esc_url( home_url( '/wp-login.php' ) );
                                }
                                if( $login_registration_form != 'wp'){
                                    $register_page = ep_get_global_settings( 'register_page' );
                                    if($register_page){
                                        $form_link = get_permalink($register_page);
                                    }   
                                }
                                $login_show_registerlink_label = ep_get_global_settings( 'login_show_registerlink_label' );

                                if( ! empty( $args->block_login_register_link_label ) ){
                                    if ( ! empty( $login_show_registerlink_label ) ){
                                        $login_show_registerlink_label = $args->block_login_register_link_label;
                                    }else{
                                        $register_text = $args->block_login_register_link_label;
                                    }
                                }
                                if( !empty( $login_show_registerlink_label ) ) {
                                    echo wp_kses_post( stripslashes( $args->dont_have_account_label ) ); ?> 
                                    <a href="<?php echo esc_url( $form_link );?>"><?php
                                      echo $login_show_registerlink_label;?>
                                    </a><?php
                                } else{
                                    echo wp_kses_post( stripslashes( $args->dont_have_account_label ) ); ?> 
                                    <a href="<?php echo esc_url( $form_link );?>">
                                        <?php echo esc_html__( 'Please','eventprime-event-calendar-management' ).' '.esc_html( $register_text ); ?>
                                    </a><?php
                                }?>
                            </div>
                        </div><?php
                    }?>

                    <?php do_action( 'ep_attendee_login_form_end' );?>
                </form>
            </div>
        </div>
    </div><?php
}?>