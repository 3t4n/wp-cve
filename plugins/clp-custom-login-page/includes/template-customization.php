<?php 

add_filter( 'gettext', 'clp_login_page_text', 20, 3 );
add_filter( 'login_errors',	'clp_login_errors', 999, 2 );
add_filter( 'login_body_class', 'clp_login_body_class' );
add_filter( 'login_link_separator', 'clp_login_link_separator' );
add_action( 'login_enqueue_scripts', 'clp_login_enqueue_scripts', 10 );
add_action( 'login_enqueue_scripts', 'clp_login_add_custom_css', 999 );
add_action( 'clp_login_header', 'clp_login_add_header_html' );
add_action( 'login_header', 'clp_login_add_header_html' );
add_action( 'clp_login_footer', 'clp_login_add_footer_html' );
add_action( 'login_footer', 'clp_login_add_footer_html' );


/**
 * Enequeue plugin CSS
 * @since 1.0.0
**/
function clp_login_enqueue_scripts() {
    // form font
    $form_font = json_decode(get_option('clp_form_typography-google_fonts', '{"family":"Roboto","variants":["100","100italic","300","300italic","regular","italic","500","500italic","700","700italic","900","900italic"],"selected":{"variant":"regular"}}'), true);
    $form_variant = CLP_Helper_Functions::process_google_font_variant($form_font['selected']['variant']);

    wp_enqueue_style( 'clp-custom-login', CLP_PLUGIN_PATH . 'assets/css/custom-login.css', array(), CLP_DEV ? filemtime(CLP_PLUGIN_DIR . 'assets/css/custom-login.css' ) : CLP_VERSION , 'all');
    // load google font if logo is text
    wp_enqueue_style( 'form-google-font', '//fonts.googleapis.com/css?family='. esc_attr( $form_font['family'] ) .':300,700,'.esc_attr( $form_font['selected']['variant'] ), array(), '1.0', 'all' );
    
    if ( get_option('clp_logo', 'image') === 'text' ) { 
        $logo_font = json_decode(get_option('clp_logo-google_fonts', '{"family":"Roboto","variants":["100","100italic","300","300italic","regular","italic","500","500italic","700","700italic","900","900italic"],"selected":{"variant":"regular"}}'), true);
        $variant = CLP_Helper_Functions::process_google_font_variant($logo_font['selected']['variant']);
        wp_enqueue_style( 'logo-google-font', '//fonts.googleapis.com/css?family='. esc_attr( $logo_font['family'] ) .':'.esc_attr( $logo_font['selected']['variant'] ), array(), '1.0', 'all' );
    }
    
}

/**
 * Echo custom CSS into login header
 * @since 1.0.0
**/
function clp_login_add_custom_css() {
    // get customizer CSS
    $css = new CLP_Generate_CSS;
    $inline_css = $css->get_customizer_css();
    $logo_font = json_decode(get_option('clp_logo-google_fonts', '{"family":"Roboto","variants":["100","100italic","300","300italic","regular","italic","500","500italic","700","700italic","900","900italic"],"selected":{"variant":"regular"}}'), true);
    $variant = CLP_Helper_Functions::process_google_font_variant($logo_font['selected']['variant']);
    $rememberme = get_option('clp_input-remember', true);
    $form_font = json_decode(get_option('clp_form_typography-google_fonts', '{"family":"Roboto","variants":["100","100italic","300","300italic","regular","italic","500","500italic","700","700italic","900","900italic"],"selected":{"variant":"regular"}}'), true);
    $form_variant = CLP_Helper_Functions::process_google_font_variant($form_font['selected']['variant']);

    ob_start(); ?>
    <style><?php 
    if ( get_option('clp_logo', 'image') === 'text' ) : ?>     
        .login .clp-login-logo {
            font-family: <?php echo esc_attr( $logo_font['family'] ); ?>;
            font-weight: <?php echo esc_attr( $variant['weight'] ); ?>;
            font-style: <?php echo esc_attr( $variant['style'] );?>;
        }
    <?php endif; ?>
    body.login {
        font-family: <?php echo esc_attr( $form_font['family'] ); ?>;
        opacity: 0;
    }
    body.login.loaded {
        opacity: 1;
    }
    body.login, .login form, .login form .forgetmenot {
        font-weight: <?php echo esc_attr( $form_variant['weight'] ); ?>;
        font-style: <?php echo esc_attr( $form_variant['style'] );?>;
    }
    .login-overlay {
        background-color: rgba(0,0,0,0.4);
    }
    </style>
    <?php 
    // output customizer CSS
    echo $inline_css;
    echo get_option('clp_css', '') !== '' ? get_option('clp_css', '') : null; 

    $inline_css = str_replace(array('<style>', '</style>'), '', ob_get_clean());

    wp_add_inline_style ('clp-custom-login', $inline_css);
}

/**
 * Echo custom HTML into login page
 * @since 1.0.0
**/
function clp_login_add_header_html() { ?>
    <div class="clp-background">
        <div id="clp-background-wrapper" class="clp-background-wrapper">
            <div class="login-background"></div>
            <div class="login-overlay" style="display:<?php echo get_option('clp_background-overlay-enable', '') === ''  ? 'none' : 'block';?>"></div>
        </div>
    </div>
    <div class="clp-content clp-half">
        <div class="clp-form-container">
            <div class="clp-login-form-container">
                
    
    <?php 

    echo CLP_Render_HTML::logo();

    do_action('clp_add_login_form_header_html');
}



/**
 * Echo custom HTML into login page footer
 * @since 1.0.0
**/
function clp_login_add_footer_html() {
    ?>
            <div class="clp-form-footer-html">
            <?php do_action('clp_add_login_form_footer_html'); ?>
            </div>
            <!-- class="clp-login-form-container" -->
            </div>
        <!-- class="clp-form-container" -->
        </div> 
    <!-- class="clp-content clp-half" -->
    </div>
    <?php

    echo CLP_Render_HTML::page_footer();

    echo CLP_Render_HTML::dom_elements_manipulation();
    
    echo get_option('clp_background', 'color') === 'video' ? CLP_Render_HTML::footer_video_script() : null;

}

/**
 * Add specific classes to body tag
 * @since 1.0.0
**/
function clp_login_body_class( $classes  ) { 
    $classes[] = 'clp-login';
    $classes[] = 'clp-' . get_option('clp_logo', 'image') . '-logo';
    $classes[] = !get_option('clp_logo-image') ? 'clp-default-logo' : '';
    $classes[] = 'clp-background-' . get_option('clp_background', 'image');
    $classes[] = get_option('clp_background', 'image') === 'pattern' ? 'clp-pattern-' . get_option('clp_background-pattern', 'fabric') : '';
    $classes[] = get_option('clp_background-blur', '0') ? 'clp-background-blur' : '';
    $classes[] = !get_option('clp_input-remember', true) ? 'clp-hide-rememberme' : '';
    $classes[] = !get_option('clp_input-showpassword', true) ? 'clp-hide-show-pw' : '';
    $classes[] = !get_option('clp_form_footer-display_backtoblog', '1') ? 'clp-hide-backtoblog' : '';
    $classes[] = !get_option('clp_form_footer-display_forgetpassword', '1') ? 'clp-hide-forgetpassword' : '';
    $classes[] = !get_option('clp_form_footer-display_register', '1') ? 'clp-hide-register' : 'clp-show-register';
    $classes[] = !get_option('clp_form_footer-display_privacy', '1') ? 'clp-hide-privacy' : 'clp-show-privacy';
    $classes[] = get_option('clp_layout-width', '100') != '100' ? 'clp-content-half' : '';
    $classes[] = get_option('clp_layout-content-skew', '0') != '0' ? 'clp-content-skew' : '';
    $classes[] = CLP_Helper_Functions::get_opacity_from_color_code(get_option('clp_layout-content-background-color', 'rgba(255,255,255,1)')) < 1 ? 'clp-content-opaque' : '';
    $classes[] = 'form-footer-' . get_option('clp_form_footer-align', 'left');
    $classes[] = get_option('users_can_register') == false ? 'can-register-0' : 'can-register-1';
    $classes[] = !get_option('clp_footer-enable', '1') ? 'clp-footer-disabled' : 'clp-footer-enabled';
    $classes[] = 'clp-template-' . get_option('clp_templates', 'default');
    $classes[] = !get_option('clp_input-label_display', '1') ? 'clp-hide-labels' : '';
	return array_filter($classes);
}

/**
 * Change login error meessages
 * @since 1.0.0
**/
function clp_login_errors( $error, $wp_error = null ) { 
    global $errors;
    $err_codes = [];

    $lang_code = CLP_Helper_Functions::get_locale();

    if ( $wp_error ) {
        $err_codes = $wp_error->get_error_codes();
    } else if ( $errors ) {
        $err_codes = $errors->get_error_codes();
    }

    $new_error = '';
    foreach ( $err_codes as $err_code ) {
        switch ( $err_code ) {
            case 'invalid_username':
                $error = get_option( 'clp_messages-invalid_username') ? stripslashes(get_option( 'clp_messages-invalid_username')) : $error;
                $error = apply_filters('clp_translate_string', $error, 'Error: Invalid Username', $lang_code);
                break;
            case 'invalid_email':
                $error = get_option( 'clp_messages-invalid_email' ) ? stripslashes(get_option( 'clp_messages-invalid_email' )) : $error;
                $error = apply_filters('clp_translate_string', $error, 'Error: Invalid Email', $lang_code);
                break;
            case 'empty_username':
                $error = get_option( 'clp_messages-empty_username' ) ? stripslashes(get_option( 'clp_messages-empty_username' )) . '<br />': $error;
                $error = apply_filters('clp_translate_string', $error, 'Error: Empty Username', $lang_code);
                break;
            case 'empty_password':
                $error = get_option( 'clp_messages-empty_password' ) ? stripslashes(get_option( 'clp_messages-empty_password' )). '<br />' : $error;
                $error = apply_filters('clp_translate_string', $error, 'Error: Empty Password', $lang_code);
                break;
            case 'incorrect_password':
                $error = get_option( 'clp_messages-incorrect_password' ) ? stripslashes(get_option( 'clp_messages-incorrect_password' )) : $error;
                $error = apply_filters('clp_translate_string', $error, 'Error: Incorrect Password', $lang_code);
                break;
            case 'invalidcombo':
                $error = get_option( 'clp_messages-invalidcombo' ) ? stripslashes(get_option( 'clp_messages-invalidcombo' )) : $error;
                $error = apply_filters('clp_translate_string', $error, 'Error: Invalid Combination', $lang_code);
                break;
            case 'username_exists':
                $error = get_option( 'clp_messages-username_exists' ) ? stripslashes(get_option( 'clp_messages-username_exists' )) : $error;
                $error = apply_filters('clp_translate_string', $error, 'Error: Username Exists', $lang_code);
                break;
            case 'email_exists':
                $error = get_option( 'clp_messages-email_exists' ) ? stripslashes(get_option( 'clp_messages-email_exists' )) : $error;
                $error = apply_filters('clp_translate_string', $error, 'Error: Email Exists', $lang_code);
                break;
            case 'empty_email':
                $error = get_option( 'clp_messages-empty_email' ) ? stripslashes(get_option( 'clp_messages-empty_email' )) : $error;
                $error = apply_filters('clp_translate_string', $error, 'Error: Empty Email', $lang_code);
                break;
            case 'authentication_failed':
                $error = get_option( 'clp_messages-authentication_failed' ) ? stripslashes(get_option( 'clp_messages-authentication_failed' )) : $error;
                $error = apply_filters('clp_translate_string', $error, 'Error: Invalid username, email address or incorrect password.', $lang_code);
                break;
            
            default:
                break;
        }   

        if ( $new_error !== $error ) {
            $new_error .= $error;

        }
        
    }
    
	return $new_error;
}

/**
 * Customize form footer separator text
 * @since 1.0.0
**/
function clp_login_link_separator( $login_link_separator ) {
    return !get_option('clp_form_footer-display_register', '1') ? '' : ' ' . stripslashes(get_option('clp_form_footer-login_link_separator', '|')) . ' ';
}

/**
 * Customize form input texts
 * @since 1.0.0
**/
function clp_login_page_text ( $translation, $text, $domain ) {
    $lang_code = CLP_Helper_Functions::get_locale();

    switch ( $text ) {
        case 'Get New Password':
            $translation = get_option('clp_input-get_new_password_text') ? stripslashes(get_option('clp_input-get_new_password_text')) : $translation;
            $translation = apply_filters('clp_translate_string', $translation, 'Submit Button - Get New Password', $lang_code);
            break;
        case 'Remember Me':
            $translation = get_option('clp_input-remember_text') ? stripslashes(get_option('clp_input-remember_text')) : $translation;
            $translation = apply_filters('clp_translate_string', $translation, 'Remember Me', $lang_code);
            break;
        case 'Username or Email Address':
            $translation = get_option('clp_input-login_input_text') ? stripslashes(get_option('clp_input-login_input_text')) : $translation;
            $translation = apply_filters('clp_translate_string', $translation, 'Username or Email Address Label', $lang_code);
            break;  
        case 'Username':
            $translation = get_option('clp_input-login_input_text_username') ? stripslashes(get_option('clp_input-login_input_text_username')) : $translation;
            $translation = apply_filters('clp_translate_string', $translation, 'Username Label', $lang_code);
            break;  
        case 'Email':
            $translation = get_option('clp_input-login_input_text_email') ? stripslashes(get_option('clp_input-login_input_text_email')) : $translation;
            $translation = apply_filters('clp_translate_string', $translation, 'Email Label', $lang_code);
            break;  
        case 'Password':
            $translation = get_option('clp_input-password_input_text') ? stripslashes(get_option('clp_input-password_input_text')) : $translation;
            $translation = apply_filters('clp_translate_string', $translation, 'Password Label', $lang_code);
            break;   
        case 'Lost your password?':
            $translation = get_option('clp_form_footer-forgetpassword_text') ? stripslashes(get_option('clp_form_footer-forgetpassword_text')) : $translation;
            $translation = apply_filters('clp_translate_string', $translation, 'Footer - Forget Password', $lang_code);
            break;
        case 'Log in':
            $translation = get_option('clp_form_footer-login_text') ? stripslashes(get_option('clp_form_footer-login_text')) : $translation;
            $translation = apply_filters('clp_translate_string', $translation, 'Footer - Log In', $lang_code);
            break;
        // login button
        case 'Log In':
            $translation = get_option('clp_button-text') ? stripslashes(get_option('clp_button-text')) : $translation;
            $translation = apply_filters('clp_translate_string', $translation, 'Submit Button - Log In', $lang_code);
            break;
        // register text
        case 'Register':
            $translation = get_option('clp_form_footer-register_text') ? stripslashes(get_option('clp_form_footer-register_text')) : $translation;
            $translation = apply_filters('clp_translate_string', $translation, 'Footer - Register', $lang_code);
            break;        
        case 'Register For This Site':
            $translation = get_option('clp_messages-register_message') ? stripslashes(get_option('clp_messages-register_message')) : $translation;
            $translation = apply_filters('clp_translate_string', $translation, 'Message: Register', $lang_code);
            break;        
        case 'Registration confirmation will be emailed to you.':
            $translation = get_option('clp_messages-register_message2') ? stripslashes(get_option('clp_messages-register_message2')) : $translation;
            $translation = apply_filters('clp_translate_string', $translation, 'Message: Registration', $lang_code);
            break;
        // Lost Password Message
        case 'Please enter your username or email address. You will receive an email message with instructions on how to reset your password.':
            $translation = get_option('clp_messages-forgetpassword_message') ? stripslashes(get_option('clp_messages-forgetpassword_message')) : $translation;
            $translation = apply_filters('clp_translate_string', $translation, 'Message: Forget Password', $lang_code);
            break;         
        default:
            break;
    }

    return $translation;
}