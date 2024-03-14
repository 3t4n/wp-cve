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

	form.mjtc-support-form{display:inline-block; width: 100%;margin-top: 5px;}
	div.mjtc-support-add-form-wrapper{float: left;width: 100%;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp{float: left;width: calc(100% / 2 - 10px);margin: 0px 5px; margin-bottom: 20px; }
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field-title{text-transform: capitalize;float: left;width: 100%;margin-bottom: 5px;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field{float: left;width: 100%; position: relative;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field input.mjtc-support-form-field-input{float: left;width: 100%;border-radius: 0px;padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select.mjtc-support-form-field-select{float: left;width: 100%;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / 4% no-repeat #eee;padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field .ms-formfield-radio-button-wrap {display: inline-block;margin-right: 10px;}
	div.mjtc-support-radio-btn-wrp{float: left;width: 100%;padding: 10px;height: 50px;}
	div.mjtc-support-radio-btn-wrp input.mjtc-support-form-field-radio-btn{margin-right: 5px; vertical-align: top;}
	div.mjtc-support-form-btn-wrp{float: left;width:calc(100% - 20px);margin: 0px 10px;text-align: center;padding: 25px 0px 10px 0px;}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button{padding: 20px 10px;margin-right: 10px;min-width: 120px;border-radius: 0px;line-height: initial;}
	div.mjtc-support-select-user-btn {float: left;width: 30%;position: absolute;top: 0;right: 0;}
	div.mjtc-support-select-user-btn a#userpopup {display: inline-block;width: 100%;text-align: center;padding: 15px 12px;text-decoration: none;outline: 0px;line-height: initial;height: 50px;}
	div.mjtc-support-select-user-field {float: left;width: 100%;position: relative;}
	/*popup*/
	div#userpopupblack {background: rgba(0,0,0,0.5);position: fixed;width: 100%;height: 100%;top: 0px;left: 0px;z-index: 9999;}
	div#userpopup * {box-sizing: border-box;}
	div#userpopup{position: fixed;top:50%;left:50%;width:50%;z-index: 9999999999;transform: translate(-50%, -50%);background: #fff;box-sizing: border-box;max-height: 70%;overflow-x: hidden;overflow-y: auto;}
	div#userpopup .userpopup-top {float: left;width: 100%;padding: 15px;}
	div#userpopup .userpopup-top .userpopup-heading {float: left;color: #fff;font-weight: bold;font-size: 20px;line-height: initial;text-transform: capitalize;}
	div#userpopup .userpopup-top .userpopup-close {float: right;cursor: pointer;}
	div#userpopup .userpopup-search {float: left;width: 100%;}
	div#userpopup .userpopup-search form .userpopup-fields-wrp {float: left;width: 100%;padding: 10px;}
	div#userpopup .userpopup-search form .userpopup-fields-wrp .userpopup-fields {float: left;width: calc(100% / 3 - 10px);margin: 0 5px;}
	div#userpopup .userpopup-search form .userpopup-fields-wrp .userpopup-fields input {display: inline-block;width: 100%;padding: 10px;height: 40px;border: 1px solid #e0e1e0;background: #f8fafc;color: #6c757d;box-shadow: unset;}
	div#userpopup .userpopup-search form .userpopup-fields-wrp .userpopup-btn-wrp {float: left;width: 100%;}
	div#userpopup .userpopup-search form .userpopup-fields-wrp .userpopup-btn-wrp input {float: left;padding: 10px 35px;border: 1px solid;margin: 8px 0 0 5px;cursor: pointer;}
	div#userpopup .userpopup-search div.popup-field-title {float: left;width: 100%;padding: 10px;}
	div#userpopup .userpopup-search div.popup-field-obj {float: left;width: 100%;padding: 0 10px;}
	div#userpopup .userpopup-search div.popup-field-obj input {float: left;width: 100%;height: 45px;padding: 10px;margin: 0;box-shadow: unset;color: #6c757d;background: #f8fafc;border: 1px solid #d1d3d3;}
	div#userpopup div.popup-act-btn-wrp {float: left;width: 100%;text-align: center;padding: 15px;}
	div#userpopup div.popup-act-btn-wrp .popup-act-btn {display: inline-block;padding: 10px 35px;border-radius: unset;height: auto;line-height: auto;font-size: 14px;border: 1px solid #1572e8;color: #fff;background-color: #1572e8;}

	div#userpopup #userpopup-records-wrp {float: left;width: 100%;}
	div#userpopup #userpopup-records-wrp #userpopup-records {}
	div#userpopup #userpopup-records-wrp #userpopup-records .userpopup-records-desc {text-align: center;padding: 50px 15px;color: #23282d;}
	#majesticsupportform #userpopup {float: left;height: 45px;line-height: initial;border-radius: unset;box-shadow: unset;margin: 3px 3px;padding: 15px 10px;text-decoration: underline;color: #23282d;}
	#majesticsupportform #userpopup:hover {color: #23282d;text-decoration: none;}
	/*table*/
	.mjtc-support-table-wrp {float: left;width: 100%;}
	.mjtc-support-table-wrp .mjtc-support-table-header {float: left;width: 100%;padding: 10px 15px;border-top: 1px solid #e0e1e0;border-bottom: 1px solid #e0e1e0;}
	.mjtc-support-table-wrp .mjtc-support-table-header .mjtc-support-table-header-col {float: left;width: calc(100% / 4);font-weight: bold;}
	.mjtc-support-table-wrp .mjtc-support-table-body {float: left;width: 100%;}
	.mjtc-support-table-wrp .mjtc-support-table-body div.mjtc-support-data-row {float: left;width: 100%;padding: 10px 15px;border-bottom: 1px solid #e0e1e0;}
	.mjtc-support-table-wrp .mjtc-support-table-body div.mjtc-support-data-row .mjtc-support-table-body-col {float: left;width: calc(100% / 4);color: #23282d;}
	.mjtc-support-table-wrp .mjtc-support-table-body div.mjtc-support-data-row .mjtc-support-table-body-col .mjtc-userpopup-link {color: #1572e8;}
	.mjtc-support-table-wrp .mjtc-support-table-body div.mjtc-support-data-row .mjtc-support-table-body-col .mjtc-userpopup-link:hover {color: #1572e8;}
	.mjtc-support-table-wrp .mjtc-support-table-body div.mjtc-support-data-row .mjtc-support-table-body-col .mjtc-support-display-block {display: none;}
	.mjtc-support-table-wrp .mjtc-support-table-header div:nth-child(1),
	.mjtc-support-table-wrp .mjtc-support-table-body div.mjtc-support-data-row div:nth-child(1) {width: 10% !important;padding: 5px 0;}
	.mjtc-support-table-wrp .mjtc-support-table-header div:nth-child(2),
	.mjtc-support-table-wrp .mjtc-support-table-body div.mjtc-support-data-row div:nth-child(2) {width: 25% !important;padding: 5px 0;}
	.mjtc-support-table-wrp .mjtc-support-table-header div:nth-child(3),
	.mjtc-support-table-wrp .mjtc-support-table-body div.mjtc-support-data-row div:nth-child(3) {width: 40% !important;padding: 5px 0;}
	.mjtc-support-table-wrp .mjtc-support-table-header div:nth-child(4),
	.mjtc-support-table-wrp .mjtc-support-table-body div.mjtc-support-data-row div:nth-child(4) {width: 25% !important;padding: 5px 0;}
	/* pagination */
	.ms_userpages {float: left;width: 100%;padding: 10px 15px;text-align: right;}
	.ms_userpages .ms_userlink {display: inline-block;text-decoration: none;padding: 5px 10px;margin-left: 5px;background: #f8fafc;color: #1572e8;}
	.ms_userpages .ms_userlink:hover {background: #1572e8;color: #fff;}
	.ms_userpages .ms_userlink.selected {color: #23282d;background: transparent;}
	.ms_userpages .ms_userlink.selected:hover {color: #23282d;background: transparent;}

';
/*Code For Colors*/
$majesticsupport_css .= '
/* Add Form */
	div#userpopup .userpopup-top {background: '.$color1.';}
	div#userpopup .userpopup-search form .userpopup-fields-wrp .userpopup-btn-wrp .userpopup-search-btn {background: '.$color1.';border-color: '.$color1.';color: '.$color7.';}
	div#userpopup .userpopup-search form .userpopup-fields-wrp .userpopup-btn-wrp .userpopup-search-btn:hover {background: '.$color7.';color: '.$color1.';}
	div#userpopup .userpopup-search form .userpopup-fields-wrp .userpopup-btn-wrp .userpopup-reset-btn {background: '.$color2.';border-color: '.$color2.';color: '.$color7.';}
	div#userpopup .userpopup-search form .userpopup-fields-wrp .userpopup-btn-wrp .userpopup-reset-btn:hover {background: '.$color7.';color: '.$color2.';}
	div.mjtc-support-table-header{color: '.$color7.';}
	div.mjtc-support-select-user-btn a#userpopup {background-color: '.$color1.';color: '.$color7.';border: 1px solid '.$color5.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field-title {color: '.$color2.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field input.mjtc-support-form-field-input{background-color:#fff;border:1px solid '.$color5.';color: '.$color4.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select.mjtc-support-form-field-select{background-color:#fff !important;border:1px solid '.$color5.';color: '.$color4.';}
	div.mjtc-support-form-btn-wrp{border-top:2px solid '.$color2.';}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button{background-color:'.$color1.' !important;color:'.$color7.' !important;border: 1px solid '.$color5.';}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button:hover{border-color: '.$color2.';}
	div.mjtc-support-radio-btn-wrp{background-color:#fff;border:1px solid '.$color5.';color: '.$color4.';}

/* Add Form */

';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
