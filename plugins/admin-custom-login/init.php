<?php

error_reporting(0);
if (! defined('ABSPATH')) {
    exit;
}
$login_page = unserialize(get_option('Admin_custome_login_login'));
if (isset($login_page['login_redirect_force'])) {
    $login_redirect_force = $login_page['login_redirect_force'];
} else {
    $login_redirect_force = "no";
}
if ($login_redirect_force=="yes") {
    add_action('template_redirect', function () {
        /** no non-authenticated users allowed **/
        if (! is_user_logged_in()) {
            $login_page  = unserialize(get_option('Admin_custome_login_login'));
            $redirectURL = esc_url($login_page['login_force_redirect_url']);
            wp_redirect($redirectURL, 302);
            exit();
        }
    });
}

$g_page = unserialize(get_option('Admin_custome_login_gcaptcha'));
$dashboard_page = unserialize(get_option('Admin_custome_login_dashboard'));
if (isset($g_page['login_enable_gcaptcha'])) {
    $login_enable_gcaptcha = $g_page['login_enable_gcaptcha'];
    $dashboard_status = $dashboard_page['dashboard_status'];
    if ($login_enable_gcaptcha=="yes" && $dashboard_status=="enable") {
        /** Google gcaptcha code **/
        include('acl-gcaptcha.php');
    }
}

/**
* Redirect user after successful login.
*
* @param string $redirect_to URL to redirect to.
* @param string $request URL the user is coming from.
* @param object $user Logged user's data.
* @return string
*/
function ACL_login_redirect($redirect_to, $request, $user)
{
    //is there a user to check?
    if (isset($user->roles) && is_array($user->roles)) {
        /** get and load custom redirect option after user login **/
        $login_page = unserialize(get_option('Admin_custome_login_login'));
        $login_redirect_user = isset($login_page['login_redirect_user']) ? $login_page['login_redirect_user'] : '';
        /** check for admins **/
        if (in_array('administrator', $user->roles)) {
            // redirect admin to the default place
            return $redirect_to;
        } else {
            /** redirect users to another place **/
            if ($login_redirect_user != "") {
                return $login_redirect_user;
            } else {
                return $redirect_to;
            }
        }
    } else {
        return $redirect_to;
    }
}
add_filter('login_redirect', 'ACL_login_redirect', 10, 3);
/** load plugin translation **/
add_action('plugins_loaded', 'ACL_GetReadyTranslation');
function ACL_GetReadyTranslation()
{
    load_plugin_textdomain(WEBLIZAR_ACL, false, dirname(plugin_basename(__FILE__)).'/languages/');
}
/*** Admin Custom Login menu ***/
require_once("login-form-screen.php");
add_action('admin_menu', 'acl_weblizar_admin_custom_login_menu', 2);
function acl_weblizar_admin_custom_login_menu()
{
    if (current_user_can('administrator')) {
        $wl_admin_menu = add_menu_page(esc_html__('Admin Custom Login', WEBLIZAR_ACL), esc_html__('AC Login', WEBLIZAR_ACL), 'manage_options', 'admin_custom_login', 'acl_admin_custom_login_content', 'dashicons-art', 10);
        add_action('admin_print_styles-' . $wl_admin_menu, 'acl_admin_custom_login_css');
        $acl_menu = add_submenu_page('admin_custom_login', esc_html__('Settings', WEBLIZAR_ACL), esc_html__('Settings', WEBLIZAR_ACL), 'administrator', 'admin_custom_login', 'acl_admin_custom_login_content');
        add_action('admin_print_styles-' . $acl_menu, 'acl_admin_custom_login_css');
    }
}
/** load CSS Files only With Admin Custom Login Menu Page **/
function acl_admin_custom_login_css()
{
    if (strpos($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'admin_custom_login') == true) {
        wp_enqueue_style('dashboard');
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style('thickbox');
        wp_register_style('bootstrap', WEBLIZAR_NALF_PLUGIN_URL.'css/bootstrap.min.css');
        wp_enqueue_style('bootstrap');
        wp_register_style('wl-acl-isotop-css', WEBLIZAR_NALF_PLUGIN_URL.'css/isotope_css.css');
        wp_enqueue_style('wl-acl-isotop-css');
        wp_register_style('wl-acl-smartech-css', WEBLIZAR_NALF_PLUGIN_URL.'css/smartech.css');
        wp_enqueue_style('wl-acl-smartech-css');
        wp_register_style('wl-acl-jquery-ui-css', WEBLIZAR_NALF_PLUGIN_URL.'css/jquery-ui.css');
        wp_enqueue_style('wl-acl-jquery-ui-css');
        wp_register_style('wl-acl-font-awesome_min', WEBLIZAR_NALF_PLUGIN_URL.'css/fontawesome-all.min.css');
        wp_enqueue_style('wl-acl-font-awesome_min');
        wp_register_style('wl-acl-font-animate', WEBLIZAR_NALF_PLUGIN_URL.'css/animate.css');
        wp_enqueue_style('wl-acl-font-animate');
        wp_register_style('wl-acl-fontawesome-iconpicker', WEBLIZAR_NALF_PLUGIN_URL.'css/fontawesome-iconpicker.css');
        wp_enqueue_style('wl-acl-fontawesome-iconpicker');

        wp_register_style('wl-acl-dialog', WEBLIZAR_NALF_PLUGIN_URL.'css/dialog/dialog.css');
        wp_enqueue_style('wl-acl-dialog');
        wp_register_style('wl-acl-dialog-box-style', WEBLIZAR_NALF_PLUGIN_URL.'css/dialog/dialog-box-style.css');
        wp_enqueue_style('wl-acl-dialog-box-style');
        wp_register_style('wl-acl-dialog-jamie', WEBLIZAR_NALF_PLUGIN_URL.'css/dialog/dialog-jamie.css');
        wp_enqueue_style('wl-acl-dialog-jamie');
        wp_register_style('wl-acl-custom-css', WEBLIZAR_NALF_PLUGIN_URL.'css/custom.css');
        wp_enqueue_style('wl-acl-custom-css');

        wp_register_style('wl-acl-googleapis-css_01', 'https://fonts.googleapis.com/css?family=Dosis:600,700,800');
        wp_enqueue_style('wl-acl-googleapis-css_01');

        wp_register_style('wl-acl-googleapis-css_02', 'https://fonts.googleapis.com/css?family=Lato:400,300,300italic,400italic,700,700italic,900');
        wp_enqueue_style('wl-acl-googleapis-css_02');
        wp_register_style('wl-acl-googleapis-css_03', 'https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic|Montserrat:400,700');
        wp_enqueue_style('wl-acl-googleapis-css_03');
    }
}

/** load JS Files only With Admin Custom Login Menu Page  **/
add_action('admin_print_scripts', 'acl_admin_custom_login_js');
function acl_admin_custom_login_js()
{
    if (strpos($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'admin_custom_login') == true) {
        wp_enqueue_script('theme-preview');
        wp_enqueue_script('jquery');
        wp_register_script('popper', WEBLIZAR_NALF_PLUGIN_URL.'js/popper.min.js');
        wp_enqueue_script('popper');
        wp_enqueue_script('wl-acl-media-uploads', WEBLIZAR_NALF_PLUGIN_URL.'js/acl-media-upload-script.js', array('media-upload','thickbox','jquery'));
        wp_enqueue_script('acl-color-picker-script', WEBLIZAR_NALF_PLUGIN_URL.'js/acl-color-picker-script.js', array( 'wp-color-picker' ), false, true);
        wp_register_script('bootstrap', WEBLIZAR_NALF_PLUGIN_URL.'js/bootstrap.min.js');
        wp_enqueue_script('bootstrap');
        /* Localize the nonce */
        wp_localize_script( 'bootstrap', 'WEBLIZARADMINNONCE', array( 'adminsecurity' => wp_create_nonce( 'weblizar_admin_nonce' ) ) );
        wp_register_script('wl-acl-metisMenu', WEBLIZAR_NALF_PLUGIN_URL.'js/plugins/metisMenu/metisMenu.min.js');
        wp_enqueue_script('wl-acl-metisMenu');
        wp_register_script('wl-aclsmartech', WEBLIZAR_NALF_PLUGIN_URL.'js/smartech.js', array('jquery'));
        wp_enqueue_script('wl-aclsmartech');
        wp_register_script('wl-acl-nalf-sidebar-nav', WEBLIZAR_NALF_PLUGIN_URL.'js/nalf_sidebar_nav.js');
        wp_enqueue_script('wl-acl-nalf-sidebar-nav');
        wp_enqueue_script('wl-acl-media-upload-script-2-js', WEBLIZAR_NALF_PLUGIN_URL.'js/acl-media-upload-script-2.js');
        wp_register_script('wl-acl-font-icon-picker-js', WEBLIZAR_NALF_PLUGIN_URL.'js/fontawesome-iconpicker.js');
        wp_enqueue_script('wl-acl-font-icon-picker-js');

        wp_register_script('wl-acl-snap-svg-min', WEBLIZAR_NALF_PLUGIN_URL.'js/dialog/snap.svg-min.js');
        wp_enqueue_script('wl-acl-snap-svg-min');
        wp_register_script('wl-acl-modernizr-custom', WEBLIZAR_NALF_PLUGIN_URL.'js/dialog/modernizr.custom.js');
        wp_enqueue_script('wl-acl-modernizr-custom');
        wp_register_script('wl-acl-classie', WEBLIZAR_NALF_PLUGIN_URL.'js/dialog/classie.js');
        wp_enqueue_script('wl-acl-classie');
        wp_register_script('wl-acl-dialogFx', WEBLIZAR_NALF_PLUGIN_URL.'js/dialog/dialogFx.js');
        wp_enqueue_script('wl-acl-dialogFx');
        wp_register_script('wl-acl-dashboard', WEBLIZAR_NALF_PLUGIN_URL.'js/dashboard.js');
        wp_enqueue_script('wl-acl-dashboard');
    }
}

function acl_advanced_login_form_plugin()
{
    wp_enqueue_script('jquery');
    $dashboard_page = unserialize(get_option('Admin_custome_login_dashboard'));
    $top_page = unserialize(get_option('Admin_custome_login_top'));
    if ($top_page['top_bg_type'] == "slider-background" && $dashboard_page['dashboard_status'] == "enable") {
        wp_register_script('modernizr', WEBLIZAR_NALF_PLUGIN_URL.'js/modernizr.custom.86080.js');
        wp_enqueue_script('modernizr');
        wp_register_style('demo', WEBLIZAR_NALF_PLUGIN_URL.'css/demo.css');
        wp_enqueue_style('demo');
    }
    wp_register_style('wl-acl-font-awesome_min', WEBLIZAR_NALF_PLUGIN_URL.'css/fontawesome-all.min.css');
    wp_enqueue_style('wl-acl-font-awesome_min');
    wp_register_style('wl-acl-custom-css', WEBLIZAR_NALF_PLUGIN_URL.'css/acl-custom.css');
    wp_enqueue_style('wl-acl-custom-css');
}
if (isset($dashboard_page['dashboard_status']) && $dashboard_page['dashboard_status'] == "enable") {
    add_action('login_enqueue_scripts', 'acl_advanced_login_form_plugin');
}

/*** To change the Login Button Text starts ***/
add_action('login_form', 'WACL_login_button_text');
function WACL_login_button_text()
{
    add_filter('gettext', 'WACL_loginbutton_gettext', 10, 2);
}

/**
 * WACL_loginbutton_gettext function
 *
 * @param [type] $translation
 * @param [type] $text
 * @return translation
 */
function WACL_loginbutton_gettext($translation, $text)
{
    if (get_option('Admin_custome_login_login')) {
        $label_login_button = unserialize(get_option('Admin_custome_login_login'));
        if (isset($label_login_button['label_loginButton'])) {
            $label_text = $label_login_button['label_loginButton'];
        } else {
            $label_text = "Log In";
        }
    } else {
        $label_text = "Log In";
    }

    if ('Log In' == $text) {
        return $label_text;
    }
    return $translation;
}
/*** To change the Login Button Text ends ***/
/**
 * Undocumented function
 *
 * @return void
 */
function acl_footer_func()
{
    $text_and_color_page = unserialize(get_option('Admin_custome_login_text'));
    $user_input_icon = $text_and_color_page['user_input_icon'];
    $password_input_icon = $text_and_color_page['password_input_icon'];
    $enable_inputbox_icon = $text_and_color_page['enable_inputbox_icon'];
    $heading_font_color = $text_and_color_page['heading_font_color'];
    $heading_font_size = $text_and_color_page['heading_font_size'];
    $input_font_size = $text_and_color_page['input_font_size'];
    $top_page = unserialize(get_option('Admin_custome_login_top'));
    $Social_page = unserialize(get_option('Admin_custome_login_Social'));

    $login_page = unserialize(get_option('Admin_custome_login_login'));
    if (isset($login_page['user_cust_lbl'])) {
        $user_cust_lbl= $login_page['user_cust_lbl'];
    } else {
        $user_cust_lbl = "Type Username or Email";
    }
    if (isset($login_page['pass_cust_lbl'])) {
        $pass_cust_lbl= $login_page['pass_cust_lbl'];
    } else {
        $pass_cust_lbl = "Type Password";
    }

    if (isset($login_page['label_username'])) {
        $label_username= $login_page['label_username'];
    } else {
        $label_username = "Username / Email";
    }
    if (isset($login_page['label_password'])) {
        $label_password= $login_page['label_password'];
    } else {
        $label_password = "Password";
    }
    wp_register_script('weblizar-acl-footer', '', array(), false, true);
    wp_enqueue_script('weblizar-acl-footer');

    $js = '';
    ob_start(); ?>

    jQuery(document).ready(function(){
		jQuery('html body').attr('id', 'screen');
		jQuery('#loginform label[for="user_login"]').attr('id', 'lable_login_input');
		jQuery('#loginform label[for="user_pass"]').attr('id', 'lable_password_input');
		jQuery('#loginform p').attr('id', 'lable_login_input');
		jQuery('#loginform p.forgetmenot').attr('id', 'forgetmenot_input');
		jQuery('#loginform p.submit').attr('id', 'submit_input');
		jQuery('#loginform .user-pass-wrap').attr('id', 'lable_password_input');

		<?php if($enable_inputbox_icon=='yes'){ ?>
		if (jQuery('#lable_login_input').length) {
			document.getElementById("lable_login_input").innerHTML="<?php echo esc_attr($label_username); ?><div class='input-container'> <div class='icon-ph'><i class='fa <?php echo esc_attr($user_input_icon); ?>'></i></div> <input id='user_login' name='log' class='input' type='text' placeholder='<?php echo esc_attr($user_cust_lbl); ?>'></div>";
			document.getElementById("lable_password_input").innerHTML="<?php echo esc_attr($label_password); ?><div class='input-container'> <div class='icon-ph'><i  class='fa <?php echo esc_attr($password_input_icon); ?>'></i></div> <div class='icon-end'><i id='togglePassword' class='fa fa-eye'></i></div><input id='user_pass' name='pwd' class='input' type='password' placeholder='<?php echo esc_attr($pass_cust_lbl); ?>'></div>";
			jQuery('body.login div#login form .input, .login input[type="text"]').css('padding','5px 5px 5px 45px');
            const togglePassword = document.querySelector("#togglePassword");
            const password = document.querySelector("#user_pass");

            togglePassword.addEventListener("click", function () {
                // toggle the type attribute
                const type = password.getAttribute("type") === "password" ? "text" : "password";
                password.setAttribute("type", type);

                // toggle the icon
                this.classList.toggle("bi-eye");
            });
		}
		<?php } else { ?>
		if (jQuery('#lable_login_input').length) {
			jQuery('#loginform #user_login').attr('placeholder', '<?php echo esc_attr($user_cust_lbl); ?>');
			jQuery('#loginform #user_pass').attr('placeholder', '<?php echo esc_attr($pass_cust_lbl); ?>');
			jQuery('body.login div#login form .input, .login input[type="text"]').css('padding','5px 5px 5px 5px');
		}
		<?php }?>

		<?php
			$g_page      = unserialize(get_option('Admin_custome_login_gcaptcha'));
            if (!empty($g_page)) {
                $site_key    = (array_key_exists("site_key",$g_page)) ? $g_page['site_key'] : '';
                $secret_key  = (array_key_exists("secret_key",$g_page)) ? $g_page['secret_key'] : '';
            }
			/*$site_key = $g_page['site_key'];*/
            /*$secret_key = $g_page['secret_key'];*/
		?>

		<?php if ($top_page['top_bg_type'] == "slider-background"){ ?>
		 jQuery('#screen').prepend('<ul class="cb-slideshow"> <li><span><?php esc_html_e('Image 01',WEBLIZAR_ACL); ?></span></li> <li><span><?php esc_html_e('Image 02',WEBLIZAR_ACL); ?></span></li> <li><span><?php esc_html_e('Image 03',WEBLIZAR_ACL); ?></span></li><li><span><?php esc_html_e('Image 04',WEBLIZAR_ACL); ?></span></li>  <li><span><?php esc_html_e('Image 05',WEBLIZAR_ACL); ?></span></li> <li><span><?php esc_html_e('Image 06',WEBLIZAR_ACL); ?></span></li></ul>')
		<?php } ?>

		<!--enable Social Icon In inner login form-->

		<?php if ( ! empty( $Social_page['social_link_new_window'] ) && $Social_page['social_link_new_window'] == 'yes' ) {
			$target = "_blank";
		} else {
			$target = "_self";
		}
		?>
		<?php if($Social_page['enable_social_icon'] == "inner" || $Social_page['enable_social_icon'] == "both") {?>
		jQuery( ".forgetmenot, #lostpasswordform" ).append('<div class="acl-social-inner" style="padding-top:16px"><div class="acl-social-text" style="color:<?php echo esc_attr($heading_font_color); ?>; font-size:<?php echo esc_attr($heading_font_size);?>px; "><?php esc_html_e('Find Us On Social Media',WEBLIZAR_ACL); ?></div><div style="padding-top:5px"><?php if($Social_page['social_twitter_link']!=''){ ?><a href="<?php echo esc_url($Social_page['social_twitter_link']);?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button twitter"><i class="fab fa-twitter"></i><span></span></a><?php } if($Social_page['social_facebook_link']!=''){ ?> <a href="<?php echo esc_url($Social_page['social_facebook_link']);?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button facebook"><i class="fab fa-facebook-f"></i><span></span></a> <?php } if($Social_page['social_linkedin_link']!=''){ ?> <a href="<?php echo esc_url($Social_page['social_linkedin_link']);?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button linkedin"> <i class="fab fa-linkedin-in"> </i> <span></span> </a> <?php } if($Social_page['social_pinterest_link']!=''){ ?><a href="<?php echo esc_url($Social_page['social_pinterest_link']);?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button pinterest"><i class="fab fa-pinterest-p"></i><span></span></a><?php } if($Social_page['social_digg_link']!=''){ ?><a href="<?php echo esc_url($Social_page['social_digg_link']);?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button digg"><i class="fab fa-digg"></i><span></span></a><?php } if($Social_page['social_youtube_link']!=''){ ?><a href="<?php echo esc_url($Social_page['social_youtube_link']);?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button youtube"><i class="fab fa-youtube-square"></i><span></span></a><?php } if($Social_page['social_flickr_link']!=''){ ?><a href="<?php echo esc_url($Social_page['social_flickr_link']);?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button flickr"><i class="fab fa-flickr"></i><span></span></a><?php } if($Social_page['social_tumblr_link']!=''){ ?><a href="<?php echo esc_url($Social_page['social_tumblr_link']);?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button tumblr"><i class="fab fa-tumblr"></i><span></span></a><?php } if($Social_page['social_skype_link']!=''){ ?><a href="<?php echo esc_url($Social_page['social_skype_link']);?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button skype"><i class="fab fa-skype"></i><span></span></a><?php } if($Social_page['social_instagram_link']!=''){ ?><a href="<?php echo esc_url($Social_page['social_instagram_link']);?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button instagram"><i class="fab fa-instagram"></i><span></span></a><?php } if($Social_page['social_telegram_link']!=''){ ?><a href="<?php echo esc_url($Social_page['social_telegram_link']);?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button telegram"><i class="fab fa-telegram-plane"></i><span></span></a><?php }if($Social_page['social_whatsapp_link']!=''){ ?><a href="<?php echo esc_url($Social_page['social_whatsapp_link']);?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button whatsapp"><i class="fab fa-whatsapp"></i><span></span></a><?php } ?><div></div>' );
		<?php } ?>
		<!--enable Social Icon In outer login form-->
		<?php if ( ! empty( $Social_page['social_link_new_window'] ) && $Social_page['social_link_new_window'] == 'yes' ) {
			$target = "_blank";
		} else {
			$target = "_self";
		}
		?>
		<?php if($Social_page['enable_social_icon'] == "outer" || $Social_page['enable_social_icon'] == "both") {?>
		jQuery( "#backtoblog" ).append('<div class="divsocial"><?php if($Social_page['social_twitter_link']!=''){?> <a href="<?php echo esc_url($Social_page['social_twitter_link']); ?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button twitter"><i class="fab fa-twitter"></i><span></span></a><?php } if($Social_page['social_facebook_link']!=''){?><a href="<?php echo esc_url($Social_page['social_facebook_link']); ?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button facebook"><i class="fab fa-facebook-f"></i><span></span></a> <?php } if($Social_page['social_linkedin_link']!=''){?><a href="<?php echo esc_url($Social_page['social_linkedin_link']); ?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button linkedin"><i class="fab fa-linkedin-in"></i><span></span></a><?php } if($Social_page['social_pinterest_link']!=''){?><a href="<?php echo esc_url($Social_page['social_pinterest_link']); ?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button pinterest"><i class="fab fa-pinterest-p"></i><span></span></a><?php } if($Social_page['social_digg_link']!=''){?> <a href="<?php echo esc_url($Social_page['social_digg_link']); ?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button digg"><i class="fab fa-digg"></i><span></span></a><?php } if($Social_page['social_youtube_link']!=''){?><a href="<?php echo esc_url($Social_page['social_youtube_link']); ?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button youtube"><i class="fab fa-youtube-square"></i><span></span></a><?php } if($Social_page['social_flickr_link']!=''){?><a href="<?php echo esc_url($Social_page['social_flickr_link']); ?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button flickr"><i class="fab fa-flickr"></i><span></span></a><?php } if($Social_page['social_tumblr_link']!=''){?><a href="<?php echo esc_url($Social_page['social_tumblr_link']); ?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button tumblr"><i class="fab fa-tumblr"></i><span></span></a><?php } if($Social_page['social_skype_link']!=''){?><a href="<?php echo esc_url($Social_page['social_skype_link']); ?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button skype"><i class="fab fa-skype"></i><span></span></a><?php } if($Social_page['social_instagram_link']!=''){?><a href="<?php echo esc_url($Social_page['social_instagram_link']); ?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button instagram"><i class="fab fa-instagram"></i><span></span></a><?php }if($Social_page['social_telegram_link']!=''){ ?><a href="<?php echo esc_url($Social_page['social_telegram_link']);?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button telegram"><i class="fab fa-telegram-plane"></i><span></span></a><?php }if($Social_page['social_whatsapp_link']!=''){ ?><a href="<?php echo esc_url($Social_page['social_whatsapp_link']);?>" target="<?php echo esc_attr( $target ); ?>" class="icon-button whatsapp"><i class="fab fa-whatsapp"></i><span></span></a><?php } ?></div>');
		<?php }
			$login_page = unserialize(get_option('Admin_custome_login_login'));
			if(isset($login_page['tagline_msg'])){
				$tagline_msg = $login_page['tagline_msg'];
				$edit_tagline_msg = stripslashes($tagline_msg);
			} else {
				$edit_tagline_msg = " ";
			}
		?>
		jQuery( "#backtoblog" ).append('<div class="divfooter"><?php echo wp_kses_post($edit_tagline_msg);?></div>');
	});

    <?php


    $js .= ob_get_clean();


    wp_add_inline_script('weblizar-acl-footer', $js);
}
$dashboard_page = unserialize(get_option('Admin_custome_login_dashboard'));
if (isset($dashboard_page['dashboard_status']) == "enable") {
    add_action('login_head', 'acl_footer_func');
}

function acl_admin_custom_login_content()
{
    require_once('includes/content.php');
}
// Guidline update
function background_enqueue_script()
{
    if (strpos($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'admin_custom_login') == true) {
        $top_page = unserialize(get_option('Admin_custome_login_top'));
        wp_register_script('wl-acl-background', WEBLIZAR_NALF_PLUGIN_URL.'js/background.js', array('jquery'), true, true);
        wp_enqueue_script('wl-acl-background');
        if( !empty($top_page ))  {
            wp_localize_script(
                'wl-acl-background',
                'frontend_ajax_object',
                array(
                    'top_bg_type'              => $top_page['top_bg_type'],
                    'top_cover'                => $top_page['top_cover'],
                    'top_repeat'               => $top_page['top_repeat'],
                    'top_position'             => $top_page['top_position'],
                    'top_attachment'           => $top_page['top_attachment'],
                    'top_slideshow_no'         => $top_page['top_slideshow_no'],
                    'top_bg_slider_animation'  => $top_page['top_bg_slider_animation'],
                )
            );
        }
    }
}
add_action('admin_enqueue_scripts', 'background_enqueue_script');

function text_and_color_enqueue()
{
    if (strpos($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'admin_custom_login') == true) {
        $text_and_color_page = unserialize(get_option('Admin_custome_login_text'));
        wp_register_script('wl-acl-text-and-color', WEBLIZAR_NALF_PLUGIN_URL.'js/textandcolor.js', array('jquery'), true, true);
        wp_enqueue_script('wl-acl-text-and-color');
        if( !empty( $text_and_color_page )) {
            wp_localize_script(
                'wl-acl-text-and-color',
                'textandcolor_object',
                array(
                    'heading_font_size'  => $text_and_color_page['heading_font_size'],
                    'input_font_size'    => $text_and_color_page['input_font_size'],
                    'link_size'          => $text_and_color_page['link_size'],
                    'button_font_size'   => $text_and_color_page['button_font_size'],
                    'heading_font_style' => $text_and_color_page['heading_font_style'],
                    'input_font_style'   => $text_and_color_page['input_font_style'],
                    'link_font_style'    => $text_and_color_page['link_font_style'],
                    'button_font_style'  => $text_and_color_page['button_font_style']
                )
            );
        }
    }
}
add_action('admin_enqueue_scripts', 'text_and_color_enqueue');

function login_form_enqueue_script()
{
    if (strpos($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'admin_custom_login') == true) {
        $login_page          = unserialize(get_option('Admin_custome_login_login'));
        $text_and_color_page = unserialize(get_option('Admin_custome_login_text'));
        $Social_page         = unserialize(get_option('Admin_custome_login_Social'));
        wp_register_script('wl-acl-login-form', WEBLIZAR_NALF_PLUGIN_URL.'js/inlinejs/loginform.js', array('jquery'), true, true);
        wp_enqueue_script('wl-acl-login-form');

        // print_r($login_page);
        // die;

        if( !empty( $login_page ) && !empty( $text_and_color_page ) || !empty( $Social_page )  ) {
            wp_localize_script(
                'wl-acl-login-form',
                'login_object',
                array(
                    'login_form_position'   => $login_page['login_form_position'],
                    'login_form_opacity'    => $login_page['login_form_opacity'],
                    'login_form_width'      => $login_page['login_form_width'],
                    'login_form_left'       => $login_page['login_form_left'],
                    'login_form_top'        => $login_page['login_form_top'],
                    'login_form_radius'     => $login_page['login_form_radius'],
                    'login_border_thikness' => $login_page['login_border_thikness'],
                    'login_msg_fontsize'    => $login_page['login_msg_fontsize'],
                    'login_bg_type'         => $login_page['login_bg_type'],
                    'login_bg_effect'       => $login_page['login_bg_effect'],
                    'login_border_style'    => $login_page['login_border_style'],
                    'login_redirect_force'  => $login_page['login_redirect_force'],
                    'login_bg_repeat'       => $login_page['login_bg_repeat'],
                    'login_bg_position'     => $login_page['login_bg_position'],
                    'heading_font'          => $text_and_color_page["heading_font_style"],
                    'button_font'           => $text_and_color_page['input_font_style'],
                    'link_font'             => $text_and_color_page['link_font_style'],
                    'bfont_style'           => $text_and_color_page['link_font_style'],
                    'enable_social_icon'    => $Social_page['enable_social_icon'],

                )
            );
        }
    }
}
add_action('admin_enqueue_scripts', 'login_form_enqueue_script');
add_action('admin_enqueue_scripts', 'page_settings_enqueue_script');

function page_settings_enqueue_script()
{
    if (strpos($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'admin_custom_login') == true) {
        $logo_page = unserialize(get_option('Admin_custome_login_logo'));
        wp_register_script('wl-acl-page-settings', WEBLIZAR_NALF_PLUGIN_URL.'js/inlinejs/pagesettings.js', array('jquery'), true, true);
        wp_enqueue_script('wl-acl-page-settings');
        if ( !empty( $logo_page ) ) {
            wp_localize_script(
                'wl-acl-page-settings',
                'page_settings_object',
                array(
                    'logo_width'   => $logo_page['logo_width'],
                    'logo_height'   => $logo_page['logo_height'],
                )
            );
        }
    }
}
/**** Process a settings export that generates a .json file of the shop settings ***/
function acl_export_settings()
{
    if (empty($_POST['acl_export_action']) || 'export_settings' != $_POST['acl_export_action']) {
        return;
    }

    if (! wp_verify_nonce($_POST['acl_export_nonce'], 'acl_export_nonce')) {
        return;
    }
    if (! current_user_can('manage_options')) {
        return;
    }

    /****  Get value of Dashboard page **/
    $dashboard_page   = unserialize(get_option('Admin_custome_login_dashboard'));
    $dashboard_status = $dashboard_page['dashboard_status'];

    /****  Get value of Top page **/
    $top_page                = unserialize(get_option('Admin_custome_login_top'));
    $top_bg_type             = $top_page['top_bg_type'];
    $top_color               = $top_page['top_color'];
    $top_image               = $top_page['top_image'];
    $top_cover               = $top_page['top_cover'];
    $top_repeat              = $top_page['top_repeat'];
    $top_position            = $top_page['top_position'];
    $top_attachment          = $top_page['top_attachment'];
    global $top_slideshow_no ;
    $top_slideshow_no        = $top_page['top_slideshow_no'];
    $top_bg_slider_animation = $top_page['top_bg_slider_animation'];

    /**** Get value of Login page **/
    $login_page               = unserialize(get_option('Admin_custome_login_login'));
    $login_form_position      = $login_page['login_form_position'];
    $login_form_left          = $login_page['login_form_left'];
    $login_form_float         = $login_page['login_form_float'];
    $login_custom_css         = $login_page['login_custom_css'];
    $login_form_top           = $login_page['login_form_top'];
    $login_bg_type            = $login_page['login_bg_type'];
    $login_bg_color           = $login_page['login_bg_color'];
    $login_bg_effect          = $login_page['login_bg_effect'];
    $login_bg_image           = $login_page['login_bg_image'];
    $login_form_opacity       = $login_page['login_form_opacity'];
    $login_form_width         = $login_page['login_form_width'];
    $login_form_radius        = $login_page['login_form_radius'];
    $login_border_style       = $login_page['login_border_style'];
    $login_border_thikness    = $login_page['login_border_thikness'];
    $login_border_color       = $login_page['login_border_color'];
    $login_bg_repeat          = $login_page['login_bg_repeat'];
    $login_bg_position        = $login_page['login_bg_position'];
    $login_enable_shadow      = $login_page['login_enable_shadow'];
    $login_enable_language_switcher      = $login_page['login_enable_language_switcher'];
    $login_shadow_color       = $login_page['login_shadow_color'];
    $log_form_above_msg       = $login_page['log_form_above_msg'];
    $login_pass_reset_msg     = $login_page['login_pass_reset_msg'];
    $login_redirect_force     = $login_page['login_redirect_force'];
    $login_redirect_user      = $login_page['login_redirect_user'];
    $login_force_redirect_url = $login_page['login_force_redirect_url'];
    $login_msg_fontsize       = $login_page['login_msg_fontsize'];
    $login_msg_font_color     = $login_page['login_msg_font_color'];
    $login_tagline_text_color = isset($login_page['login_tagline_text_color']) ? $login_page['login_tagline_text_color'] : '#ffffff';
    $login_tagline_link_color = isset($login_page['login_tagline_link_color']) ? $login_page['login_tagline_link_color'] : '#f00';
    $tagline_msg              = $login_page['tagline_msg'];
    $user_cust_lbl            = $login_page['user_cust_lbl'];
    $pass_cust_lbl            = $login_page['pass_cust_lbl'];
    $label_username           = $login_page['label_username'];
    $label_password           = $login_page['label_password'];
    $label_loginButton        = $login_page['label_loginButton'];


    /**** Get value of Text and Color page **/
    $text_and_color_page      = unserialize(get_option('Admin_custome_login_text'));
    $heading_font_color       = $text_and_color_page['heading_font_color'];
    $input_font_color         = $text_and_color_page['input_font_color'];
    $link_color               = $text_and_color_page['link_color'];
    $button_color             = $text_and_color_page['button_color'];
    $login_button_font_color  = isset($text_and_color_page['login_button_font_color']) ? $text_and_color_page['login_button_font_color'] : '#ffffff';
    $heading_font_size        = $text_and_color_page['heading_font_size'];
    $input_font_size          = $text_and_color_page['input_font_size'];
    $link_size                = $text_and_color_page['link_size'];
    $button_font_size         = $text_and_color_page['button_font_size'];
    $enable_link_shadow       = $text_and_color_page['enable_link_shadow'];
    $show_remember_me_field   =isset($text_and_color_page['show_remember_me_field']) ? $text_and_color_page['show_remember_me_field'] : 'yes';
    $show_back_to_site_link   = isset($text_and_color_page['show_back_to_site_link']) ? $text_and_color_page['show_back_to_site_link'] : 'yes';
    $show_copyright_link_text =isset($text_and_color_page['show_copyright_link_text']) ? $text_and_color_page['show_copyright_link_text'] : 'yes';
    $link_shadow_color        = $text_and_color_page['link_shadow_color'];
    $heading_font_style       = $text_and_color_page['heading_font_style'];
    $input_font_style         = $text_and_color_page['input_font_style'];
    $link_font_style          = $text_and_color_page['link_font_style'];
    $button_font_style        = $text_and_color_page['button_font_style'];
    $enable_inputbox_icon     = $text_and_color_page['enable_inputbox_icon'];
    $user_input_icon          = $text_and_color_page['user_input_icon'];
    $password_input_icon      = $text_and_color_page['password_input_icon'];

    /**** Get value of Logo page **/
    $logo_page      = unserialize(get_option('Admin_custome_login_logo'));
    $logo_show      = isset($logo_page['logo_show']) ? $logo_page['logo_show'] : 'yes';
    $logo_image     = $logo_page['logo_image'];
    $logo_width     = $logo_page['logo_width'];
    $logo_height    = $logo_page['logo_height'];
    $logo_url       = $logo_page['logo_url'];
    $logo_url_title = $logo_page['logo_url_title'];

    /**** Get value of Slidshow image **/
    $Slidshow_image            = unserialize(get_option('Admin_custome_login_Slidshow'));
    $Slidshow_image_1          = $Slidshow_image['Slidshow_image_1'];
    $Slidshow_image_2          = $Slidshow_image['Slidshow_image_2'];
    $Slidshow_image_3          = $Slidshow_image['Slidshow_image_3'];
    $Slidshow_image_4          = $Slidshow_image['Slidshow_image_4'];
    $Slidshow_image_5          = $Slidshow_image['Slidshow_image_5'];
    $Slidshow_image_6          = $Slidshow_image['Slidshow_image_6'];
    $Slidshow_image_label_1    = $Slidshow_image['Slidshow_image_label_1'];
    $Slidshow_image_label_2    = $Slidshow_image['Slidshow_image_label_2'];
    $Slidshow_image_label_3    = $Slidshow_image['Slidshow_image_label_3'];
    $Slidshow_image_label_4    = $Slidshow_image['Slidshow_image_label_4'];
    $Slidshow_image_label_5    = $Slidshow_image['Slidshow_image_label_5'];
    $Slidshow_image_label_6    = $Slidshow_image['Slidshow_image_label_6'];
    $Social_page               = unserialize(get_option('Admin_custome_login_Social'));
    $enable_social_icon        = $Social_page['enable_social_icon'];
    $social_icon_size          = $Social_page['social_icon_size'];
    $social_icon_layout        = $Social_page['social_icon_layout'];
    $social_link_new_window    = $Social_page['social_link_new_window'];
    $social_icon_color         = $Social_page['social_icon_color'];
    $social_icon_color_onhover = $Social_page['social_icon_color_onhover'];
    $social_icon_bg            = $Social_page['social_icon_bg'];
    $social_icon_bg_onhover    = $Social_page['social_icon_bg_onhover'];
    $social_facebook_link      = $Social_page['social_facebook_link'];
    $social_twitter_link       = $Social_page['social_twitter_link'];
    $social_linkedin_link      = $Social_page['social_linkedin_link'];
    $social_pinterest_link     = $Social_page['social_pinterest_link'];
    $social_digg_link          = $Social_page['social_digg_link'];
    $social_youtube_link       = $Social_page['social_youtube_link'];
    $social_flickr_link        = $Social_page['social_flickr_link'];
    $social_tumblr_link        = $Social_page['social_tumblr_link'];
    $social_skype_link         = $Social_page['social_skype_link'];
    $social_instagram_link     = $Social_page['social_instagram_link'];
    $social_telegram_link      = $Social_page['social_telegram_link'];
    $social_whatsapp_link      = $Social_page['social_whatsapp_link'];
    $g_page                    = unserialize(get_option('Admin_custome_login_gcaptcha'));
    $site_key                  = (array_key_exists("site_key",$g_page)) ? $g_page['site_key'] : '';
    $secret_key                = (array_key_exists("secret_key",$g_page)) ? $g_page['secret_key'] : '';
    $login_enable_gcaptcha     = (array_key_exists("login_enable_gcaptcha",$g_page)) ? $g_page['login_enable_gcaptcha'] : 'light';

    if (isset($g_page['acl_gcaptcha_theme'])) {
        $acl_gcaptcha_theme = $g_page['acl_gcaptcha_theme'];
    } else {
        $acl_gcaptcha_theme = 'light';
    }


    $ACL_ALL_Settings= serialize(array(
        'dashboard_status' 			=> $dashboard_status,
        'top_bg_type'				=> $top_bg_type,
        'top_color' 				=> $top_color,
        'top_image' 				=> $top_image,
        'top_cover' 				=> $top_cover,
        'top_repeat' 				=> $top_repeat,
        'top_position' 				=> $top_position,
        'top_attachment' 			=> $top_attachment,
        'top_slideshow_no' 			=> $top_slideshow_no,
        'top_bg_slider_animation' 	=> $top_bg_slider_animation,
        'login_form_position'		=> $login_form_position,
        'login_form_left'			=> $login_form_left,
        'login_form_float'			=> $login_form_float,
        'login_custom_css'			=> $login_custom_css,
        'login_form_top'			=> $login_form_top,
        'login_bg_type'				=> $login_bg_type,
        'login_bg_color' 			=> $login_bg_color,
        'login_bg_effect' 			=> $login_bg_effect,
        'login_bg_image' 			=> $login_bg_image,
        'login_form_opacity' 		=> $login_form_opacity,
        'login_form_width' 			=> $login_form_width,
        'login_form_radius' 		=> $login_form_radius,
        'login_border_style' 		=> $login_border_style,
        'login_border_thikness' 	=> $login_border_thikness,
        'login_border_color' 		=> $login_border_color,
        'login_bg_repeat' 			=> $login_bg_repeat,
        'login_bg_position' 		=> $login_bg_position,
        'login_enable_shadow' 		=> $login_enable_shadow,
        'login_enable_language_switcher' 		=> $login_enable_language_switcher,
        'login_shadow_color' 		=> $login_shadow_color,
        'log_form_above_msg' 		=> $log_form_above_msg,
        'login_pass_reset_msg' 		=> $login_pass_reset_msg,
        'login_redirect_force' 		=> $login_redirect_force,
        'login_redirect_user' 		=> $login_redirect_user,
        'login_force_redirect_url' 	=> $login_force_redirect_url,
        'login_msg_fontsize' 		=> $login_msg_fontsize,
        'login_msg_font_color' 		=> $login_msg_font_color,
        'tagline_msg' 				=> $tagline_msg,
        'login_tagline_text_color' 	=> $login_tagline_text_color,
        'login_tagline_link_color' 	=> $login_tagline_link_color,
        'user_cust_lbl'				=> $user_cust_lbl,
        'pass_cust_lbl' 			=> $pass_cust_lbl,
        'label_username'			=> $label_username,
        'label_password'			=> $label_password,
        'label_loginButton'			=> $label_loginButton,

        'heading_font_color'		=> $heading_font_color,
        'input_font_color'			=> $input_font_color,
        'link_color'				=> $link_color,
        'button_color'				=> $button_color,
        'login_button_font_color'	=> $login_button_font_color,
        'heading_font_size'			=> $heading_font_size,
        'input_font_size'			=> $input_font_size,
        'link_size'					=> $link_size,
        'button_font_size'			=> $button_font_size,
        'enable_link_shadow'		=> $enable_link_shadow,
        'show_remember_me_field'    => $show_remember_me_field,
        'show_back_to_site_link'    => $show_back_to_site_link,
        'show_copyright_link_text'  => $show_copyright_link_text,
        'link_shadow_color'			=> $link_shadow_color,
        'heading_font_style'		=> $heading_font_style,
        'input_font_style'			=> $input_font_style,
        'link_font_style'			=> $link_font_style,
        'button_font_style'			=> $button_font_style,
        'enable_inputbox_icon'		=> $enable_inputbox_icon,
        'user_input_icon'			=> $user_input_icon,
        'password_input_icon'		=> $password_input_icon,

        'logo_image'				=> $logo_image,
        'logo_show'				    => $logo_show,
        'logo_width'				=> $logo_width,
        'logo_height'				=> $logo_height,
        'logo_url'					=> $logo_url,
        'logo_url_title'			=> $logo_url_title,

        'enable_social_icon'		=> $enable_social_icon,
        'social_icon_size'			=> $social_icon_size ,
        'social_icon_layout'		=> $social_icon_layout ,
        'social_link_new_window'	=> $social_link_new_window ,
        'social_icon_color'			=> $social_icon_color ,
        'social_icon_color_onhover'	=> $social_icon_color_onhover ,
        'social_icon_bg'			=> $social_icon_bg,
        'social_icon_bg_onhover'	=> $social_icon_bg_onhover ,
        'social_facebook_link'		=> $social_facebook_link ,
        'social_twitter_link'		=> $social_twitter_link,
        'social_linkedin_link'		=> $social_linkedin_link,
        'social_pinterest_link'		=> $social_pinterest_link,
        'social_digg_link'			=> $social_digg_link,
        'social_youtube_link'		=> $social_youtube_link,
        'social_flickr_link'		=> $social_flickr_link,
        'social_tumblr_link'		=> $social_tumblr_link,
        'social_skype_link'			=> $social_skype_link,
        'social_instagram_link'		=> $social_instagram_link,
        'social_telegram_link'		=> $social_telegram_link,
        'social_whatsapp_link'		=> $social_whatsapp_link,

        'Slidshow_image_1'			=> $Slidshow_image_1,
        'Slidshow_image_2'			=> $Slidshow_image_2,
        'Slidshow_image_3'			=> $Slidshow_image_3,
        'Slidshow_image_4'			=> $Slidshow_image_4,
        'Slidshow_image_5'			=> $Slidshow_image_5,
        'Slidshow_image_6'			=> $Slidshow_image_6,
        'Slidshow_image_label_1'	=> $Slidshow_image_label_1,
        'Slidshow_image_label_2'	=> $Slidshow_image_label_2,
        'Slidshow_image_label_3'	=> $Slidshow_image_label_3,
        'Slidshow_image_label_4'	=> $Slidshow_image_label_4,
        'Slidshow_image_label_5'	=> $Slidshow_image_label_5,
        'Slidshow_image_label_6'	=> $Slidshow_image_label_6,

        'site_key'					=> $site_key,
        'secret_key'				=> $secret_key,
        'login_enable_gcaptcha'		=> $login_enable_gcaptcha,
        'acl_gcaptcha_theme'		=> $acl_gcaptcha_theme
    ));

    ignore_user_abort(true);

    nocache_headers();
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename=acl-settings-export-' . date('m-d-Y') . '.json');
    header("Expires: 0");

    echo json_encode(esc_attr($ACL_ALL_Settings));
    exit;
}
add_action('admin_init', 'acl_export_settings');

/*** Process a settings import from a json file ***/
function acl_import_settings()
{
    if (empty($_POST['acl_import_action']) || 'import_settings' != $_POST['acl_import_action']) {
        return;
    }

    if (! wp_verify_nonce($_POST['acl_import_nonce'], 'acl_import_nonce')) {
        return;
    }

    if (! current_user_can('manage_options')) {
        return;
    }

    list($oteher_extension, $extension) = explode(".", $_FILES['import_file']['name']);

    if ($extension != 'json') {
        wp_die(esc_html__('Please upload a valid .json file', WEBLIZAR_ACL));
    }

    $import_file = $_FILES['import_file']['tmp_name'];

    if (empty($import_file)) {
        wp_die(esc_html__('Please upload a file to import', WEBLIZAR_ACL));
    }

    $stream = fopen($import_file, "r");

    $c_settings = stream_get_contents($stream);

    fclose($stream);

    $settings = json_decode($c_settings, true);

    $ACL_Settings = @unserialize($settings);

    $dashboard_status         = isset($ACL_Settings['dashboard_status']) ? $ACL_Settings['dashboard_status'] : '' ;
    $top_bg_type              = isset($ACL_Settings['top_bg_type']) ? $ACL_Settings['top_bg_type'] :'static-background-image';
    $top_color                = isset($ACL_Settings['top_color']) ? $ACL_Settings['top_color'] : '#f9fad2';
    $top_image                = isset($ACL_Settings['top_image']) ? $ACL_Settings['top_image'] :
    $top_cover                = isset($ACL_Settings['top_cover']) ? $ACL_Settings['top_cover'] : 'yes' ;
    $top_repeat               = isset($ACL_Settings['top_repeat']) ? $ACL_Settings['top_repeat'] : 'repeat' ;
    $top_position             = isset($ACL_Settings['top_position']) ? $ACL_Settings['top_position'] : 'left top';
    $top_attachment           = isset($ACL_Settings['top_attachment']) ? $ACL_Settings['top_attachment'] : 'fixed';
    $top_slideshow_no         = isset($ACL_Settings['top_slideshow_no']) ? $ACL_Settings['top_slideshow_no'] : '6';
    $top_bg_slider_animation  = isset($ACL_Settings['top_bg_slider_animation']) ? $ACL_Settings['top_bg_slider_animation'] : 'slider-style1';
    $login_form_position      = isset($ACL_Settings['login_form_position']) ? $ACL_Settings['login_form_position'] : 'default';
    $login_form_left          = isset($ACL_Settings['login_form_left']) ? $ACL_Settings['login_form_left'] : '700';
    $login_form_float         = isset($ACL_Settings['login_form_float']) ? $ACL_Settings['login_form_float'] : 'center';
    $login_custom_css         = isset($ACL_Settings['login_custom_css']) ? $ACL_Settings['login_custom_css'] : '' ;
    $login_form_top           = isset($ACL_Settings['login_form_top']) ? $ACL_Settings['login_form_top'] : '300';
    $login_bg_type            = isset($ACL_Settings['login_bg_type']) ? $ACL_Settings['login_bg_type'] : 'static-background-image';
    $login_bg_color           = isset($ACL_Settings['login_bg_color']) ? $ACL_Settings['login_bg_color'] : '#1e73be' ;
    $login_bg_effect          = isset($ACL_Settings['login_bg_effect']) ? $ACL_Settings['login_bg_effect'] : 'pattern-1';
    $login_bg_image           = isset($ACL_Settings['login_bg_image']) ? $ACL_Settings['login_bg_image'] : '' ;
    $login_form_opacity       = isset($ACL_Settings['login_form_opacity']) ? $ACL_Settings['login_form_opacity'] : '10';
    $login_form_width         = isset($ACL_Settings['login_form_width']) ? $ACL_Settings['login_form_width'] : '358' ;
    $login_form_radius        = isset($ACL_Settings['login_form_radius']) ? $ACL_Settings['login_form_radius'] : '10';
    $login_border_style       = isset($ACL_Settings['login_border_style']) ? $ACL_Settings['login_border_style'] : 'solid';
    $login_border_thikness    = isset($ACL_Settings['login_border_thikness']) ? $ACL_Settings['login_border_thikness'] : '4' ;
    $login_border_color       = isset($ACL_Settings['login_border_color']) ? $ACL_Settings['login_border_color'] : '#0069A0';
    $login_bg_repeat          = isset($ACL_Settings['login_bg_repeat']) ? $ACL_Settings['login_bg_repeat'] : 'repeat';
    $login_bg_position        = isset($ACL_Settings['login_bg_position']) ? $ACL_Settings['login_bg_position'] : 'left top';
    $login_enable_shadow      = isset($ACL_Settings['login_enable_shadow']) ? $ACL_Settings['login_enable_shadow']: 'yes';
    $login_enable_language_switcer      = isset($ACL_Settings['login_enable_language_switcher']) ? $ACL_Settings['login_enable_language_switcher']: 'yes';
    $login_shadow_color       = isset($ACL_Settings['login_shadow_color']) ? $ACL_Settings['login_shadow_color'] :  '#C8C8C8';
    $log_form_above_msg       = isset($ACL_Settings['log_form_above_msg']) ? $ACL_Settings['log_form_above_msg'] : '' ;
    $login_pass_reset_msg     = isset($ACL_Settings['login_pass_reset_msg']) ? $ACL_Settings['login_pass_reset_msg'] : '' ;
    $login_redirect_force     = isset($ACL_Settings['login_redirect_force']) ? $ACL_Settings['login_redirect_force'] : '' ;
    $login_redirect_user      = isset($ACL_Settings['login_redirect_user']) ? $ACL_Settings['login_redirect_user'] :  '';
    $login_force_redirect_url = isset($ACL_Settings['login_force_redirect_url']) ? $ACL_Settings['login_force_redirect_url'] : '' ;
    $login_msg_fontsize       = isset($ACL_Settings['login_msg_fontsize']) ? $ACL_Settings['login_msg_fontsize'] : '16' ;
    $login_msg_font_color     = isset($ACL_Settings['login_msg_font_color']) ? $ACL_Settings['login_msg_font_color'] : '#000000' ;
    $login_tagline_text_color = isset($ACL_Settings['login_tagline_text_color']) ? $ACL_Settings['login_tagline_text_color'] : '#ffffff';
    $login_tagline_link_color = isset($ACL_Settings['login_tagline_link_color']) ? $ACL_Settings['login_tagline_link_color'] : '#f00';
    $tagline_msg              = isset($ACL_Settings['tagline_msg']) ? $ACL_Settings['tagline_msg'] : '' ;
    $user_cust_lbl            = isset($ACL_Settings['user_cust_lbl']) ? $ACL_Settings['user_cust_lbl'] : 'Type Username or Email';
    $pass_cust_lbl            = isset($ACL_Settings['pass_cust_lbl']) ? $ACL_Settings['pass_cust_lbl'] : 'password';
    $label_username           = isset($ACL_Settings['label_username']) ? $ACL_Settings['label_username'] : 'Username / Email' ;
    $label_password           = isset($ACL_Settings['label_password']) ? $ACL_Settings['label_password'] : 'Password' ;
    $label_loginButton        = isset($ACL_Settings['label_loginButton']) ? $ACL_Settings['label_loginButton'] : 'Log In' ;
    $heading_font_color      = isset ($ACL_Settings['heading_font_color']) ? $ACL_Settings['heading_font_color'] : '#ffffff';
    $input_font_color        = isset ($ACL_Settings['input_font_color']) ? $ACL_Settings['input_font_color'] : '#000000';
    $link_color              = isset ($ACL_Settings['link_color']) ? $ACL_Settings['link_color'] : '#ffffff';
    $button_color            = isset ($ACL_Settings['button_color']) ? $ACL_Settings['button_color'] : '#dd3333';
    $login_button_font_color = isset ($ACL_Settings['login_button_font_color']) ? $ACL_Settings['login_button_font_color'] : '#ffffff';
    $heading_font_size       = isset ($ACL_Settings['heading_font_size']) ? $ACL_Settings['heading_font_size'] : '14';
    $input_font_size         = isset ($ACL_Settings['input_font_size']) ? $ACL_Settings['input_font_size'] : '18' ;
    $link_size               = isset ($ACL_Settings['link_size']) ? $ACL_Settings['link_size'] : '14';
    $button_font_size        = isset($ACL_Settings['button_font_size']) ? $ACL_Settings['button_font_size'] : '14';
    $enable_link_shadow      = isset($ACL_Settings['enable_link_shadow']) ? $ACL_Settings['enable_link_shadow'] : 'yes';
    $show_remember_me_field = isset($ACL_Settings['show_remember_me_field']) ? $ACL_Settings['show_remember_me_field'] : 'yes';
    $show_back_to_site_link = isset($ACL_Settings['show_back_to_site_link']) ? $ACL_Settings['show_back_to_site_link'] : 'yes';
    $show_copyright_link_text =isset($ACL_Settings['show_copyright_link_text']) ? $ACL_Settings['show_copyright_link_text'] : 'yes';
    $link_shadow_color 		= isset($ACL_Settings['link_shadow_color']) ? $ACL_Settings['link_shadow_color'] : '$ffffff' ;
    $heading_font_style 	= isset($ACL_Settings['heading_font_style']) ? $ACL_Settings['heading_font_style'] : 'Open Sans' ;
    $input_font_style 		= isset($ACL_Settings['input_font_style']) ? $ACL_Settings['input_font_style'] : 'Open Sans' ;
    $link_font_style 		= isset($ACL_Settings['link_font_style']) ? $ACL_Settings['link_font_style'] : 'Open Sans' ;
    $button_font_style 		= isset($ACL_Settings['button_font_style']) ? $ACL_Settings['button_font_style'] : 'Open Sans' ;
    $enable_inputbox_icon 	= isset($ACL_Settings['enable_inputbox_icon']) ? $ACL_Settings['enable_inputbox_icon'] : 'yes' ;
    $user_input_icon		= isset($ACL_Settings['user_input_icon']) ? $ACL_Settings['user_input_icon'] : 'fa-user' ;
    $password_input_icon 	= isset($ACL_Settings['password_input_icon']) ? $ACL_Settings['password_input_icon'] : 'fa-key' ;

    $logo_image 			= isset($ACL_Settings['logo_image']) ? $ACL_Settings['logo_image'] : '' ;
    $logo_show              = isset($ACL_Settings['logo_show']) ? $ACL_Settings['logo_show'] : 'yes';
    $logo_width 			= isset($ACL_Settings['logo_width']) ? $ACL_Settings['logo_width'] : '274' ;
    $logo_height 			= isset($ACL_Settings['logo_height']) ? $ACL_Settings['logo_height'] : '63' ;
    $logo_url 				= isset($ACL_Settings['logo_url']) ? $ACL_Settings['logo_url'] : '' ;
    $logo_url_title 		= isset($ACL_Settings['logo_url_title']) ? $ACL_Settings['logo_url_title'] : 'Your Site Name and Info' ;

    $Slidshow_image_1		= isset($ACL_Settings['Slidshow_image_1']) ? $ACL_Settings['Slidshow_image_1'] : '' ;
    $Slidshow_image_2		= isset($ACL_Settings['Slidshow_image_2']) ? $ACL_Settings['Slidshow_image_2'] : '' ;
    $Slidshow_image_3		= isset($ACL_Settings['Slidshow_image_3']) ? $ACL_Settings['Slidshow_image_3'] : '' ;
    $Slidshow_image_4		= isset($ACL_Settings['Slidshow_image_4']) ? $ACL_Settings['Slidshow_image_4'] : '' ;
    $Slidshow_image_5		= isset($ACL_Settings['Slidshow_image_5']) ? $ACL_Settings['Slidshow_image_5'] : '' ;
    $Slidshow_image_6		= isset($ACL_Settings['Slidshow_image_6']) ? $ACL_Settings['Slidshow_image_6'] : '' ;

    $Slidshow_image_label_1 = isset($ACL_Settings['Slidshow_image_label_1']) ? $ACL_Settings['Slidshow_image_label_1'] : '' ;
    $Slidshow_image_label_2 = isset($ACL_Settings['Slidshow_image_label_2']) ? $ACL_Settings['Slidshow_image_label_2'] : '' ;
    $Slidshow_image_label_3 = isset($ACL_Settings['Slidshow_image_label_3']) ? $ACL_Settings['Slidshow_image_label_3'] : '' ;
    $Slidshow_image_label_4 = isset($ACL_Settings['Slidshow_image_label_4']) ? $ACL_Settings['Slidshow_image_label_4'] : '' ;
    $Slidshow_image_label_5 = isset($ACL_Settings['Slidshow_image_label_5']) ? $ACL_Settings['Slidshow_image_label_5'] : '' ;
    $Slidshow_image_label_6 = isset($ACL_Settings['Slidshow_image_label_6']) ? $ACL_Settings['Slidshow_image_label_6'] : '' ;

    $enable_social_icon        = isset($ACL_Settings['enable_social_icon']) ? $ACL_Settings['enable_social_icon'] : 'outer' ;
    $social_icon_size          = isset($ACL_Settings['social_icon_size']) ? $ACL_Settings['social_icon_size'] : 'mediam' ;
    $social_icon_layout        = isset($ACL_Settings['social_icon_layout']) ? $ACL_Settings['social_icon_layout'] : 'rectangle' ;
    $social_link_new_window    = isset($ACL_Settings['social_link_new_window']) ? $ACL_Settings['social_link_new_window'] : 'yes' ;
    $social_icon_color         = isset($ACL_Settings['social_icon_color']) ? $ACL_Settings['social_icon_color'] : '#ffffff' ;
    $social_icon_color_onhover = isset($ACL_Settings['social_icon_color_onhover']) ? $ACL_Settings['social_icon_color_onhover'] : '#1e73be' ;
    $social_icon_bg            = isset($ACL_Settings['social_icon_bg']) ? $ACL_Settings['social_icon_bg'] : '#1e73be' ;
    $social_icon_bg_onhover    = isset($ACL_Settings['social_icon_bg_onhover']) ? $ACL_Settings['social_icon_bg_onhover'] : '#ffffff' ;
    $social_facebook_link      = isset($ACL_Settings['social_facebook_link']) ? $ACL_Settings['social_facebook_link'] : 'http://facebook.com';
    $social_twitter_link       = isset($ACL_Settings['social_twitter_link']) ? $ACL_Settings['social_twitter_link'] : 'https://twitter.com/minimalmonkey';
    $social_linkedin_link      = isset($ACL_Settings['social_linkedin_link']) ? $ACL_Settings['social_linkedin_link'] : '';
    $social_pinterest_link     = isset($ACL_Settings['social_pinterest_link']) ? $ACL_Settings['social_pinterest_link'] : '';
    $social_digg_link          = isset($ACL_Settings['social_digg_link']) ? $ACL_Settings['social_digg_link'] : '';
    $social_youtube_link       = isset($ACL_Settings['social_youtube_link']) ? $ACL_Settings['social_youtube_link'] : 'https://youtube.com';
    $social_flickr_link        = isset($ACL_Settings['social_flickr_link']) ? $ACL_Settings['social_flickr_link'] : 'https://flickr.com';
    $social_tumblr_link        = isset($ACL_Settings['social_tumblr_link']) ? $ACL_Settings['social_tumblr_link'] : '';
    $social_skype_link         = isset($ACL_Settings['social_skype_link']) ? $ACL_Settings['social_skype_link'] : '';
    $social_instagram_link     = isset($ACL_Settings['social_instagram_link']) ? $ACL_Settings['social_instagram_link'] : 'https://instagram.com';
    $social_telegram_link      = isset($ACL_Settings['social_telegram_link']) ? $ACL_Settings['social_telegram_link'] : '';
    $social_whatsapp_link      = isset($ACL_Settings['social_whatsapp_link']) ? $ACL_Settings['social_whatsapp_link'] : '';

    $site_key 				= isset($ACL_Settings['site_key']) ? $ACL_Settings['site_key'] : ' ';
    $secret_key			    = isset($ACL_Settings['secret_key']) ? $ACL_Settings['secret_key'] : ' ';
    $login_enable_gcaptcha  = isset($ACL_Settings['login_enable_gcaptcha']) ? $ACL_Settings['login_enable_gcaptcha'] : 'no';
    $acl_gcaptcha_theme     = isset($ACL_Settings['acl_gcaptcha_theme']) ? $ACL_Settings['acl_gcaptcha_theme'] : ' ';

    $upload_dir  = wp_upload_dir();
    $plugins_dir = plugins_url();

    /**** Top Background Image **/
    $data = $top_image;
    if (strpos($data, 'uploads') == true) {
        list($oteher_path, $image_path) = explode("uploads", $data);
        $top_image = $upload_dir['baseurl']. $image_path;
    } elseif (strpos($data, 'plugins') == true) {
        list($oteher_path, $image_path) = explode("plugins", $data);
        $top_image = $plugins_dir. $image_path;
    }

    /**** Login From Background Image **/
    $data1 = $login_bg_image;
    if (strpos($data1, 'uploads') == true) {
        list($oteher_path1, $image_path1) = explode("uploads", $data1);
        $login_bg_image = $upload_dir['baseurl']. $image_path1;
    } elseif (strpos($data1, 'plugins') == true) {
        list($oteher_path1, $image_path1) = explode("plugins", $data1);
        $login_bg_image = $plugins_dir. $image_path1;
    }

    /**** Login From Background Image **/
    $data2 = $logo_image;
    if (strpos($data2, 'uploads') == true) {
        list($oteher_path2, $image_path2) = explode("uploads", $data2);
        $logo_image = $upload_dir['baseurl']. $image_path2;
    } elseif (strpos($data2, 'plugins') == true) {
        list($oteher_path2, $image_path2) = explode("plugins", $data2);
        $logo_image = $plugins_dir. $image_path2;
    }

    /**** Slider Image 1 **/
    $Slidshow_image_url_1 = $Slidshow_image_1;
    if (strpos($Slidshow_image_url_1, 'uploads') == true) {
        list($oteher_path, $image_path) = explode("uploads", $Slidshow_image_url_1);
        $Slidshow_image_1 = $upload_dir['baseurl']. $image_path;
    } elseif (strpos($Slidshow_image_url_1, 'plugins') == true) {
        list($oteher_path, $image_path) = explode("plugins", $Slidshow_image_url_1);
        $Slidshow_image_1 = $plugins_dir. $image_path;
    }

    /**** Slider Image 2 **/
    $Slidshow_image_url_2 = $Slidshow_image_2;
    if (strpos($Slidshow_image_url_2, 'uploads') == true) {
        list($oteher_path, $image_path) = explode("uploads", $Slidshow_image_url_2);
        $Slidshow_image_2 = $upload_dir['baseurl']. $image_path;
    } elseif (strpos($Slidshow_image_url_2, 'plugins') == true) {
        list($oteher_path, $image_path) = explode("plugins", $Slidshow_image_url_2);
        $Slidshow_image_2 = $plugins_dir. $image_path;
    }

    /**** Slider Image 3 ***/
    $Slidshow_image_url_3 = $Slidshow_image_3;
    if (strpos($Slidshow_image_url_3, 'uploads') == true) {
        list($oteher_path, $image_path) = explode("uploads", $Slidshow_image_url_3);
        $Slidshow_image_3 = $upload_dir['baseurl']. $image_path;
    } elseif (strpos($Slidshow_image_url_3, 'plugins') == true) {
        list($oteher_path, $image_path) = explode("plugins", $Slidshow_image_url_3);
        $Slidshow_image_3 = $plugins_dir. $image_path;
    }

    /**** Slider Image 4  ***/
    $Slidshow_image_url_4 = $Slidshow_image_4;
    if (strpos($Slidshow_image_url_4, 'uploads') == true) {
        list($oteher_path, $image_path) = explode("uploads", $Slidshow_image_url_4);
        $Slidshow_image_4 = $upload_dir['baseurl']. $image_path;
    } elseif (strpos($Slidshow_image_url_4, 'plugins') == true) {
        list($oteher_path, $image_path) = explode("plugins", $Slidshow_image_url_4);
        $Slidshow_image_4 = $plugins_dir. $image_path;
    }

    /****Slider Image 5 **/
    $Slidshow_image_url_5 = $Slidshow_image_5;
    if (strpos($Slidshow_image_url_5, 'uploads') == true) {
        list($oteher_path, $image_path) = explode("uploads", $Slidshow_image_url_5);
        $Slidshow_image_5 = $upload_dir['baseurl']. $image_path;
    } elseif (strpos($Slidshow_image_url_5, 'plugins') == true) {
        list($oteher_path, $image_path) = explode("plugins", $Slidshow_image_url_5);
        $Slidshow_image_5 = $plugins_dir. $image_path;
    }

    /**** Slider Image 6 ***/
    $Slidshow_image_url_6 = $Slidshow_image_6;
    if (strpos($Slidshow_image_url_6, 'uploads') == true) {
        list($oteher_path, $image_path) = explode("uploads", $Slidshow_image_url_6);
        $Slidshow_image_6 = $upload_dir['baseurl']. $image_path;
    } elseif (strpos($Slidshow_image_url_6, 'plugins') == true) {
        list($oteher_path, $image_path) = explode("plugins", $Slidshow_image_url_6);
        $Slidshow_image_6 = $plugins_dir. $image_path;
    }

    $dashboard_page= serialize(array(
        'dashboard_status' 			=> $dashboard_status
    ));
    update_option('Admin_custome_login_dashboard', $dashboard_page);

    $top_page= serialize(array(
        'top_bg_type'				=> $top_bg_type ,
        'top_color' 				=> $top_color ,
        'top_image' 				=> $top_image,
        'top_cover' 				=> $top_cover,
        'top_repeat' 				=> $top_repeat ,
        'top_position' 				=> $top_position ,
        'top_attachment' 			=> $top_attachment,
        'top_slideshow_no' 			=> $top_slideshow_no,
        'top_bg_slider_animation' 	=> $top_bg_slider_animation
    ));
    update_option('Admin_custome_login_top', $top_page);

    $login_page= serialize(array(
        'login_form_position'		=> $login_form_position,
        'login_form_left'			=> $login_form_left,
        'login_form_float'			=> $login_form_float,
        'login_custom_css'			=> $login_custom_css,
        'login_form_top'			=> $login_form_top,
        'login_bg_type'				=> $login_bg_type,
        'login_bg_color' 			=> $login_bg_color,
        'login_bg_effect' 			=> $login_bg_effect,
        'login_bg_image' 			=> $login_bg_image,
        'login_form_opacity' 		=> $login_form_opacity,
        'login_form_width' 			=> $login_form_width,
        'login_form_radius' 		=> $login_form_radius,
        'login_border_style' 		=> $login_border_style,
        'login_border_thikness' 	=> $login_border_thikness,
        'login_border_color' 		=> $login_border_color,
        'login_bg_repeat' 			=> $login_bg_repeat,
        'login_bg_position' 		=> $login_bg_position,
        'login_enable_shadow' 		=> $login_enable_shadow,
        'login_enable_language_switcher'=> isset($login_enable_language_switcher) ? $login_enable_language_switcher : '',
        'login_shadow_color' 		=> $login_shadow_color,
        'log_form_above_msg' 		=> $log_form_above_msg,
        'login_pass_reset_msg' 		=> $login_pass_reset_msg,
        'login_redirect_force' 		=> $login_redirect_force,
        'login_redirect_user' 		=> $login_redirect_user,
        'login_force_redirect_url' 	=> $login_force_redirect_url,
        'login_msg_fontsize' 		=> $login_msg_fontsize,
        'login_msg_font_color' 		=> $login_msg_font_color,
        'login_tagline_text_color' 	=> $login_tagline_text_color,
        'login_tagline_link_color' 	=> $login_tagline_link_color,
        'tagline_msg' 				=> $tagline_msg,
        'user_cust_lbl'				=> $user_cust_lbl,
        'pass_cust_lbl'				=> $pass_cust_lbl,
        'label_username'			=> $label_username,
        'label_password'			=> $label_password,
        'label_loginButton'			=> $label_loginButton,
    ));
    update_option('Admin_custome_login_login', $login_page);

    $text_and_color_page= serialize(array(
        'heading_font_color'		=> $heading_font_color,
        'input_font_color'			=> $input_font_color,
        'link_color'				=> $link_color,
        'button_color'				=> $button_color,
        'login_button_font_color'	=> $login_button_font_color,
        'heading_font_size'			=> $heading_font_size,
        'input_font_size'			=> $input_font_size,
        'link_size'					=> $link_size,
        'button_font_size'			=> $button_font_size,
        'enable_link_shadow'		=> $enable_link_shadow,
        'show_remember_me_field'    => $show_remember_me_field,
        'show_back_to_site_link'    => $show_back_to_site_link,
        'show_copyright_link_text'  => $show_copyright_link_text,
        'link_shadow_color'			=> $link_shadow_color,
        'heading_font_style'		=> $heading_font_style,
        'input_font_style'			=> $input_font_style,
        'link_font_style'			=> $link_font_style,
        'button_font_style'			=> $button_font_style,
        'enable_inputbox_icon'		=> $enable_inputbox_icon,
        'user_input_icon'			=> $user_input_icon,
        'password_input_icon'		=> $password_input_icon
    ));
    update_option('Admin_custome_login_text', $text_and_color_page);

    $logo_page= serialize(array(
        'logo_image'			=> $logo_image,
        'logo_show'			    => $logo_show,
        'logo_width'			=> $logo_width,
        'logo_height'			=> $logo_height,
        'logo_url'				=> $logo_url,
        'logo_url_title'		=> $logo_url_title
    ));
    update_option('Admin_custome_login_logo', $logo_page);

    $Social_page= serialize(array(
        'enable_social_icon'        => $enable_social_icon ,
        'social_icon_size'          => $social_icon_size ,
        'social_icon_layout'        => $social_icon_layout ,
        'social_link_new_window'    => $social_link_new_window ,
        'social_icon_color'         => $social_icon_color ,
        'social_icon_color_onhover' => $social_icon_color_onhover ,
        'social_icon_bg'            => $social_icon_bg,
        'social_icon_bg_onhover'    => $social_icon_bg_onhover ,
        'social_facebook_link'      => $social_facebook_link ,
        'social_twitter_link'       => $social_twitter_link,
        'social_linkedin_link'      => $social_linkedin_link ,
        'social_pinterest_link'     => $social_pinterest_link,
        'social_digg_link'          => $social_digg_link,
        'social_youtube_link'       => $social_youtube_link,
        'social_flickr_link'        => $social_flickr_link,
        'social_tumblr_link'        => $social_tumblr_link,
        'social_skype_link'         => $social_skype_link,
        'social_instagram_link'     => $social_instagram_link,
        'social_telegram_link'      => $social_telegram_link,
        'social_whatsapp_link'      => $social_telegram_link,
    ));
    update_option('Admin_custome_login_Social', $Social_page);

    $Slidshow_image= serialize(array(
        'Slidshow_image_1'		=> $Slidshow_image_1 ,
        'Slidshow_image_2'		=> $Slidshow_image_2 ,
        'Slidshow_image_3'		=> $Slidshow_image_3 ,
        'Slidshow_image_4'		=> $Slidshow_image_4 ,
        'Slidshow_image_5'		=> $Slidshow_image_5 ,
        'Slidshow_image_6'		=> $Slidshow_image_6 ,
        'Slidshow_image_label_1'=> $Slidshow_image_label_1 ,
        'Slidshow_image_label_2'=> $Slidshow_image_label_2 ,
        'Slidshow_image_label_3'=> $Slidshow_image_label_3 ,
        'Slidshow_image_label_4'=> $Slidshow_image_label_4 ,
        'Slidshow_image_label_5'=> $Slidshow_image_label_5 ,
        'Slidshow_image_label_6'=> $Slidshow_image_label_6
    ));
    update_option('Admin_custome_login_Slidshow', $Slidshow_image);

    $g_page= serialize(array(
        'site_key'					=> $site_key,
        'secret_key'				=> $secret_key,
        'login_enable_gcaptcha'		=> $login_enable_gcaptcha,
        'acl_gcaptcha_theme'		=> $acl_gcaptcha_theme
    ));
    update_option('Admin_custome_login_gcaptcha', $g_page);
}
add_action('admin_init', 'acl_import_settings');