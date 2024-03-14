<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// if header is calling later
MJTC_includer::MJTC_getModel('majesticsupport')->checkIfMainCssFileIsEnqued();

$color1 = majesticsupport::$_colors['color1'];
$color2 = majesticsupport::$_colors['color2'];
$color3 = majesticsupport::$_colors['color3'];
$color4 = majesticsupport::$_colors['color4'];
$color5 = majesticsupport::$_colors['color5'];
$color6 = majesticsupport::$_colors['color6'];
$color7 = majesticsupport::$_colors['color7'];
$color8 = majesticsupport::$_colors['color8'];
$color9 = majesticsupport::$_colors['color9'];

$majesticsupport_css = '';

/*Code for Css*/
$majesticsupport_css .= '
/* Login Page */
	div.mjtc-support-login-wrapper{float: left;width: 100%;margin: 0 !important;}
	div.mjtc-support-login-wrapper div.mjtc-support-login{float: left;width: 100%;}
	div.mjtc-support-login-wrapper div.mjtc-support-login form#loginform-custom{width:100%;float: left;padding: 10px;margin: 0px;}
	form#loginform-custom p.login-username{width:calc(50% - 10px);float:left;margin-right:10px !important;margin-bottom: 15px;}
	form#loginform-custom p.login-username label{font-weight: unset;margin-bottom: 7px;}
	form#loginform-custom p.login-password{width:50%;float:left;margin-bottom: 15px!important;}
	form#loginform-custom p.login-password label{font-weight: unset;margin-bottom: 7px;}
	form#loginform-custom p.login-remember label{font-weight: unset;margin-bottom: 7px;}
	form#loginform-custom p.login-remember {margin-top: 10px !important;}
	form#loginform-custom p.login-remember label input#rememberme{vertical-align: baseline;}
	form#loginform-custom p.login-submit{width:100%;float:left;padding:20px 0px;text-align: center;margin-top:15px !important;}
	form#loginform-custom p.login-username input#user_login{border-radius: unset;width:100%;padding: 10px;height: 50px;}
	form#loginform-custom p.login-password input#user_pass{border-radius: unset;width:100%;padding: 10px;height: 50px;}
	form#loginform-custom p.login-submit input#wp-submit{min-width: 120px;border-radius: unset;padding: 20px 10px;line-height: initial;}
	span.help-block{font-size:14px;}
	span.help-block{color:red;}
	div.ms-main-up-wrapper a:link:hover{color:blue;text-decoration:underline;}
	div.ms-main-up-wrapper a:hover{color:blue;text-decoration:underline;}
	div.ms-main-up-wrapper a{margin-left:5px;}
';
/*Code For Colors*/
$majesticsupport_css .= '
	/* Login Page */
		form#loginform-custom p.login-username label{color:'.$color2.';}
		form#loginform-custom p.login-submit{border-top:2px solid '.$color2.';}
		form#loginform-custom p.login-username input#user_login{background-color:#fff; border:1px solid '.$color5.';color:'.$color4.';}
		form#loginform-custom p.login-password input#user_pass{background-color:#fff; border:1px solid '.$color5.';color:'.$color4.';}
		form#loginform-custom p.login-submit input#wp-submit{background-color:'.$color1.';color:'.$color7.';border:1px solid '.$color5.';}
		form#loginform-custom p.login-submit input#wp-submit:hover{border-color:'.$color2.';}
		form#loginform-custom p.login-remember {color:'.$color2.';}
	/* Login Page */
';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
