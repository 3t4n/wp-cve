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
	div.mjtc-support-download-wrapper{float: left;width: 100%;margin-top: 5px;}
	div.mjtc-support-top-search-wrp{float: left;width: 100%;}
	div.mjtc-support-search-fields-wrp{float: left;width: 100%;padding: 10px;}
	form#majesticsupportform{float: left;width: 100%;}
	div.mjtc-support-fields-wrp{float: left;width: 100%;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field{float: left; width: calc(100% / 2 - 10px);margin: 0px 5px;position: relative;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field-download-search{width:75%;margin: 0px;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field input.mjtc-support-field-input{float: left;width: 100%;border-radius: 0px; padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-search-form-btn-wrp{float: left;width: 100%; padding: 0px 5px;margin-top: 10px;}
	div.mjtc-support-search-form-btn-wrp-download {width:25%;padding: 0px;margin-top: 0px;}
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button{float: left;width: 120px;padding: 13px 0px;height: 50px;text-align: center;margin-right: 10px; border-radius: unset;}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button{float: left;width: 120px;padding: 13px 0px;height: 50px;text-align: center;border-radius: unset;}
	div.mjtc-support-search-form-btn-wrp-download input.mjtc-search-button{float: left;width: calc(100% / 2 - 10px); padding: 17px 0px;text-align: center;margin: 0px 0px 0px 10px; border-radius: 0px;line-height: initial;}
	div.mjtc-support-search-form-btn-wrp-download input.mjtc-reset-button{float: left;width: calc(100% / 2 - 10px); padding: 17px 0px;text-align: center; margin: 0px 0px 0px 10px; border-radius: 0px;line-height: initial;}
	div.mjtc-support-download-content-wrp{float: left;width: 100%;margin-top: 30px;}
	div.mjtc-support-table-wrp{float: left;width: 100%;padding: 0;}

	div.ms-smart-reply-listing-wrp{float: left;width: 100%;margin-bottom: 30px;border: 1px solid #e0e1e0;border-bottom-left-radius: 8px;border-bottom-right-radius: 8px;}
	div.ms-smart-reply-listing-wrp .ms-smart-reply-listing-head{float: left;width: 100%;border-bottom: 1px solid #e0e1e0;background: #edeeef;}
	div.ms-smart-reply-listing-wrp .ms-smart-reply-listing-head .ms-smart-reply-listing-head-left{float: left;width: calc(100% - 350px);padding: 18px;}
	div.ms-smart-reply-listing-wrp .ms-smart-reply-listing-head .ms-smart-reply-listing-head-left a{float: left;width: 100%;color: #4b4b4d;font-size: 17px;font-weight: 500;text-decoration: none;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
	div.ms-smart-reply-listing-wrp .ms-smart-reply-listing-head .ms-smart-reply-listing-head-right {float: right;padding: 10px;}
	div.ms-smart-reply-listing-wrp .ms-smart-reply-listing-head .ms-smart-reply-listing-head-right a{display: inline-block;padding: 3px;border: 1px solid #e1e0e1;}
	div.ms-smart-reply-listing-wrp .ms-smart-reply-listing-body{float: left;width: 100%;padding: 20px;background: #f0f0f3;}
	div.ms-smart-reply-listing-wrp .ms-smart-reply-listing-body .ms-smart-reply-listing-ticket-subject{float: left;width: 99%;padding-bottom: 14px;margin-bottom: 14px;border-bottom: 2px solid #e8e9ea;font-size: 14px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
	div.ms-smart-reply-listing-wrp .ms-smart-reply-listing-body .ms-smart-reply-listing-ticket-reply{float: left;width: 100%;padding: 12px;border: 2px solid #e1e2e1;background: #edeeef;margin: 10px 0 30px;}
	div.ms-smart-reply-listing-wrp .ms-smart-reply-listing-body .ms-smart-reply-listing-ticket-reply img{margin-top: -1px;float: left;}
	div.ms-smart-reply-listing-wrp .ms-smart-reply-listing-body .ms-smart-reply-listing-ticket-reply span{float: left;font-size: 14px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;margin-top: 3px;margin-left: 5px;width: calc(100% - 150px);}

';
/*Code For Colors*/
$majesticsupport_css .= '
/* Add Form */
	div.mjtc-support-top-search-wrp{border:1px solid '.$color5.';}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field input.mjtc-support-field-input{background-color:#fff;border:1px solid '.$color5.';color:'.$color4.';}
	div.mjtc-support-search-fields-wrp {background: '.$color3.';}
	select.mjtc-support-select-field{background-color:'.$color3.' !important;border:1px solid '.$color5.';}
	select#departmentid{background-color:'.$color3.';border:1px solid '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button{background: '.$color1.' !important;color:'.$color7.' !important;border:1px solid '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button:hover{border-color:'.$color2.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button{background: '.$color2.';color:'.$color7.';border:1px solid '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button:hover{border-color:'.$color1.';}

/* Add Form */

';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
