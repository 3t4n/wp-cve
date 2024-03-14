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
	
	form.mjtc-support-form{display:inline-block; width: 100%; margin-top: 5px;}
	div.mjtc-support-add-form-wrapper{float: left;width: 100%;}
	div.mjtc-support-roles-wrapper{float: left;width: 100%;margin-top: 5px;}
	div.mjtc-support-categories-heading-wrp{float: left;font-weight:bold;width: 100%;padding: 15px;line-height: initial;font-weight:700;}
	span.mjtc-support-roles-section-heading-right{display: inline-block;float: right;}

	span.mjtc-support-roles-section-heading-right input#rad_alldepartmentaccess{vertical-align:baseline;}

	span.mjtc-support-roles-section-heading-right label{display: inline-block;margin:0px;font-weight:normal;}
	div.mjtc-support-role-wrp{float: left;width: 100%; margin-top: 20px;margin-bottom: 20px;}

	div.mjtc-support-role-wrp div.mjtc-support-add-role-field-wrp{float: left;width: calc(100% / 3 - 10px);margin: 0px 5px 10px;padding: 10px;line-height: initial;}

	div.mjtc-support-role-wrp div.mjtc-support-add-role-field-wrp.mjtc-support-margin-bottom{margin-bottom: 10px;}

	input.mjtc-support-checkbox{vertical-align: baseline;}

	label.mjtc-support-label{display: inline-block;margin: 0px;}
	div.mjtc-support-form-btn-wrp{float: left;width:calc(100% - 20px);margin: 0px 10px;text-align: center;padding: 25px 0px 10px 0px;}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button{padding: 20px 10px;margin-right: 10px;min-width: 120px;border-radius: 0px;line-height: initial;}
	div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button{display: inline-block; padding: 20px 10px;min-width: 120px;border-radius: 0px;line-height: initial;text-decoration: none;}
	label.mjtc-support-label{display: inline-block;margin: 0px;vertical-align:middle;}

	span.help-block{font-size:14px;}
span.help-block{color:red;}



	

';
/*Code For Colors*/
$majesticsupport_css .= '
div.mjtc-support-top-search-wrp{border:1px solid '.$color5.';}
	div.mjtc-support-search-heading-wrp{background-color:'.$color4.';color:'.$color7.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field input.mjtc-support-form-field-input{background-color:'.$color3.';border:1px solid '.$color5.';}
	div.mjtc-support-categories-heading-wrp{background-color:'.$color2.';border:1px solid '.$color5.';color:'.$color7.';}
	div.mjtc-support-add-role-field-wrp{background-color:'.$color7.';border:1px solid '.$color5.';}
	label.mjtc-support-label{color:'.$color4.';}
	div.mjtc-support-form-btn-wrp{border-top:2px solid '.$color2.';}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button{background-color:'.$color1.' !important;color:'.$color7.' !important;border:1px solid '.$color5.';}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button:hover {border-color: '.$color2.';}
	div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button{background-color:'.$color2.';color:'.$color7.';border:1px solid '.$color5.';}
	div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button:hover {border-color: '.$color1.';}

';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
