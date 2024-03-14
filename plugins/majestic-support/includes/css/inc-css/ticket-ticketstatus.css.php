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
/* Ticket Status */
	form.mjtc-support-form{display:inline-block; width: 100%;}
	div.mjtc-support-checkstatus-wrp{float: left;width: 100%;}
	div.mjtc-support-checkstatus-wrp div.mjtc-support-checkstatus-field-wrp{float: left;width: calc(100% / 2 - 10px); margin:0px 5px;margin-bottom: 25px;}
	div.mjtc-support-field-title{float: left;width: 100%;margin-bottom: 10px;}
	div.mjtc-support-field-wrp{float: left;width: 100%;}
	div.mjtc-support-field-wrp input.mjtc-support-form-input-field{float: left;width: 100%;border-radius: 0px;padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-form-btn-wrp{float: left;width:calc(100% - 20px);margin: 0px 10px;text-align: center;padding: 25px 0px 10px 0px;}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button{padding: 20px 10px;margin-right: 10px;min-width: 120px;border-radius: 0px;line-height: initial;}
	div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button{display: inline-block; padding: 20px 10px;min-width: 120px;border-radius: 0px;line-height: initial;text-decoration: none;}


';
/*Code For Colors*/
$majesticsupport_css .= '

/*Ticket Status*/
	div.mjtc-support-field-wrp input.mjtc-support-form-input-field{background-color:#fff; border:1px solid '.$color5.';color:'.$color4.';}
	div.mjtc-support-field-title{color:'.$color2.';}
	div.mjtc-support-form-btn-wrp{border-top:2px solid '.$color2.';}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button{background-color:'.$color1.' !important;color:'.$color7.' !important;border:1px solid '.$color5.';}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button:hover{border-color:'.$color2.';}
	div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button{background-color:'.$color2.';color:'.$color7.';border:1px solid '.$color5.';}
	div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button:hover{border-color:'.$color1.';}

/*Ticket Status*/

';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
