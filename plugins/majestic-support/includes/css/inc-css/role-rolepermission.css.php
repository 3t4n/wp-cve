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
	div.mjtc-support-roles-wrapper{float: left;width: 100%;margin-top: 5px;}
	div.mjtc-support-top-search-wrp{float: left;width: 100%;}
	div.mjtc-support-search-heading-wrp{float: left;width: 100%; padding: 10px 10px 10px 0px;}
	div.mjtc-support-search-heading-wrp div.mjtc-support-heading-left{float: left;width: 70%;padding: 10px;line-height: initial;}
	div.mjtc-support-roles-list-wrapper{float: left;width: 100%;margin-top: 20px;}

	div.mjtc-support-add-role-field-wrp-top{margin: 0px !important;width: 100% !important;}
	div.mjtc-support-categories-heading-wrp{float: left;font-weight:bold;width: 100%;padding: 15px;line-height: initial;font-weight:700;}
	div.mjtc-support-role-wrp{float: left;width: 100%; margin-top: 20px;margin-bottom: 20px;}
	div.mjtc-support-role-wrp div.mjtc-support-add-role-field-wrp{float: left;width: calc(100% / 3 - 10px);margin: 0px 5px 10px;padding: 10px;line-height: initial;}
	div.mjtc-support-role-wrp div.mjtc-support-add-role-field-wrp.mjtc-support-margin-bottom{margin-bottom: 10px;}
	input.mjtc-support-checkbox{vertical-align: baseline;}
	label.mjtc-support-label{display: inline-block;margin: 0px;vertical-align:middle;}
	span.help-block{font-size:14px;}
span.help-block{color:red;}




';
/*Code For Colors*/
$majesticsupport_css .= '

	div.mjtc-support-top-search-wrp{border:1px solid '.$color5.';}
	div.mjtc-support-search-heading-wrp{background-color:'.$color3.';color:'.$color2.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field input.mjtc-support-form-field-input{background-color:'.$color3.';border:1px solid '.$color5.';}

	div.mjtc-support-categories-heading-wrp{background-color:'.$color2.';border:1px solid '.$color5.';color: '.$color7.';}
	div.mjtc-support-add-role-field-wrp{background-color: #fff;border:1px solid '.$color5.';color: '.$color4.';}
	label.mjtc-support-label{color:'.$color4.';}


';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
