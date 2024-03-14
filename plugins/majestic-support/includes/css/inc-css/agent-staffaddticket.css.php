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
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp.mjtc-support-from-field-wrp-full-width{float: left;width: calc(100% / 1 - 10px); margin-bottom: 30px; }
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field-title{float: left;width: 100%;margin-bottom: 5px;text-transform: capitalize;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field{float: left;width: 100%;position: relative;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field input.mjtc-support-form-field-input{float: left;width: 100%;border-radius: 0px;padding: 10px;line-height: initial;height: 50px;}

	select.mjtc-support-select-field{float: left;width: 100%;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / auto no-repeat !important; }
	div.mjtc-support-custom-radio-box {width: 20%;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select.mjtc-support-form-field-select{float: left;width: 100%;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / 4% no-repeat #eee;}
	div.mjtc-support-form-btn-wrp{float: left;width:calc(100% - 20px);margin: 0px 10px;text-align: center;padding: 25px 0px 10px 0px;}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button{padding: 20px;margin-right: 10px;min-width: 120px;border-radius: 0px;line-height: initial;font-weight: normal;}
	div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button{display: inline-block; padding: 20px;min-width: 120px;border-radius: 0px;line-height: initial;text-decoration: none;}
	div.mjtc-support-from-field-wrp div.mjtc-support-from-field input.mjtc-form-date-field {float: left;width: 100%;padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-from-field-wrp div.mjtc-support-from-field textarea.mjtc-support-custom-textarea {float: left;width: 100%;padding: 10px;line-height: initial;height: 60px;}
	div.mjtc-support-from-field-wrp div.mjtc-support-from-field select.mjtc-form-input-field {float: left;width: 100%;padding: 10px;line-height: initial;height: 60px;}
	span.mjtc-attachment-file-box {padding: 9px 10px 8px;}
	div.mjtc-support-radio-box {width: auto;}
	span#premade{display: inline-block;float: left; width: 80%;position: relative;}
	span#premade select#premadeid{background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 98% / 2% no-repeat !important;}
	input#duedate{background-image: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/calender.png);background-repeat: no-repeat;background-position: 97% 13px;background-size: 20px;}
	span#mjtc-support-no-premade{display: inline-block;float: left;width: 100%;position: relative;padding: 10px;height: 60px;}

	div.mjtc-support-radio-btn-wrp{float: left;width: 100%;padding: 11px}
	div.mjtc-support-radio-btn-wrp input.mjtc-support-form-field-radio-btn{margin-right: 5px; vertical-align: top;}
	div.mjtc-support-radio-btn-wrp label#forsendmail{margin: 0px;display: inline-block; margin-right: 30px;}

	div.mjtc-support-reply-attachments{display: inline-block;width: 100%;margin-bottom: 20px;}
	div.mjtc-support-reply-attachments div.mjtc-attachment-field-title{display: inline-block;width: 100%;padding: 15px 0 5px 0px;}
	div.mjtc-support-reply-attachments div.mjtc-attachment-field{display: inline-block;width: 100%;}

	div.tk_attachment_value_wrapperform{float: left;width:100%;padding:0px 0px;}
	div.tk_attachment_value_wrapperform span.tk_attachment_value_text{float: left;width: calc(100% / 3 - 10px);padding: 5px 5px;margin: 5px 5px;position: relative;}
	div.tk_attachment_value_wrapperform span.tk_attachment_value_text input.mjtc-attachment-inputbox{width: 100%;max-width: 100%;max-height:100%;}
	span.tk_attachment_value_text span.tk_attachment_remove{background: url("'.esc_url(MJTC_PLUGIN_URL).'includes/images/close.png") no-repeat;background-size: 100% 100%;position: absolute;width: 30px;height: 30px;top: 3px;right:7px;cursor: pointer;}
	span.tk_attachments_configform{display: inline-block;float:left;line-height: 25px;margin-top: 10px;width: 100%; font-size: 14px;}
	span.tk_attachments_addform{position: relative;display: inline-block;padding: 8px 10px;cursor: pointer;margin-top: 10px;min-width: 120px;text-align: center;}

	div.mjtc-support-assigned-tome{float: left;width: 100%;padding: 11px 10px;}
	div.mjtc-support-assigned-tome input#assignedtome1{margin-right: 5px; vertical-align: middle;}
	div.mjtc-support-assigned-tome label#forassignedtome{margin: 0px;display: inline-block;}
	label#forassigntome{margin: 0 0 0 2px;display: inline-block;line-height: initial;text-transform: capitalize;}
	input#assigntome1{margin-right:5px;}
	#records{float: left;width: 100%;padding: 0px 10px;}

	div.mjtc-support-select-user-field{float: left;width: 100%;position: relative;}
	div.mjtc-support-select-user-field input#username-text{width: 100%;}
	div.mjtc-support-select-user-btn{float: left;width: 30%;position: absolute;top: 0;right: 0;}
	div.mjtc-support-select-user-btn a#userpopup{display: inline-block;width: 100%;text-align: center;padding: 15px 12px;text-decoration: none;outline: 0px;line-height: initial;height: 50px;}
	div.mjtc-support-premade-msg-wrp div.mjtc-support-premade-field-wrp select.mjtc-support-premade-select{display: inline-block;width: 50%;float: left;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / 4% no-repeat #eee;}

	span.mjtc-support-apend-radio-btn{float: left;padding:9.5px;background-image: linear-gradient(to right,#324ac1, #2483aa);font-size: 15px;margin: 4px;}
	span.mjtc-support-apend-radio-btn input{margin: 0 5px;width: 16px;height: 16px;vertical-align: middle !important;}
	span.mjtc-support-apend-radio-btn input#append_premade1display{vertical-align: middle;}
	span.help-block{font-size:14px;}
	input#append1{vertical-align: baseline;}
	input#append_premade1{vertical-align: baseline;margin-right:5px;}
	label#forappend{display: inline-block;margin: 0px 0 0 2px;line-height: initial;}


	div#userpopupblack{background: rgba(0,0,0,0.7);position: fixed;width: 100%;height: 100%;top:0px;left:0px;z-index: 9999;}
	div.mjtc-support-popup-row{float: left;width: 100%;margin: 0;}

	form#userpopupsearch{margin-bottom: 10px;float: left;width: 100%;}
	form#userpopupsearch{margin-bottom: 0px;}
	form#userpopupsearch div.search-center div.mjtc-search-value{padding: 0 5px;}
	form#userpopupsearch div.search-center div.mjtc-search-value input{min-height: 28px;}
	form#userpopupsearch div.search-center div.mjtc-search-value-button{padding: 0;}
	form#userpopupsearch div.search-center div.mjtc-search-value-button div.mjtc-button{padding: 0 5px; width: 50%; float: left; display: inline-block;}
	form#userpopupsearch div.search-center{width:99%;margin-left:4px;font-size:15px;float:left;font-weight: bold;}
	form#userpopupsearch div.search-center-history{width:100%;font-size:17px;float:left;padding: 20px 10px; font-weight: bold;}
	form#userpopupsearch div.search-center input{width: 100% !important;padding: 17px 15px;}
	form#userpopupsearch div.search-center-heading{padding:10px 0px 10px 10px;margin-bottom: 10px;}
	form#userpopupsearch div.search-center span.close{position: absolute;top:10px;right: 10px;background-image:url('.esc_url(MJTC_PLUGIN_URL).'includes/images/ticketdetailicon/popup-close.png);background-size: 100%;width:20px;height: 20px;opacity: 1;}
	form#userpopupsearch div.search-center-history span.close-history{position: absolute;top: 22px;right: 16px;background:url('.esc_url(MJTC_PLUGIN_URL).'includes/images/ticketdetailicon/popup-close.png) no-repeat;background-size: 100%;width:20px;height: 20px;cursor: pointer;}

	div#userpopup{position: fixed;top:50%;left:50%;width:40%; max-height: 80%; padding-top:0px;z-index: 99999;overflow-y: auto; overflow-x: hidden;transform: translate(-50%,-50%);}
	div#userpopupblack{background: rgba(0,0,0,0.7);position: fixed;width: 100%;height: 100%;top:0px;left:0px;z-index: 9999;}

	div.ms-popup-header{width:100%;font-size:20px;float:left;padding: 20px 10px; font-weight: bold;line-height: initial;text-transform: capitalize;}
	div.popup-header-close-img{position: absolute;top:22px;right: 22px;background-image:url('.esc_url(MJTC_PLUGIN_URL).'includes/images/close-icon-white.png);background-size: 100%;width:20px;height: 20px;opacity: 1;cursor: pointer;}

	div.mjtc-support-popup-search-wrp{float: left;width: 100%;padding: 30px 5px 15px;}
	div.mjtc-support-search-top{float: left;width: 100%;}
	div.mjtc-support-search-top div.mjtc-support-search-left{float: left;width: 70%;}
	div.mjtc-support-search-top div.mjtc-support-search-left div.mjtc-support-search-fields-wrp{float: left;width: 100%;padding: 0px}
	div.mjtc-support-search-top div.mjtc-support-search-left div.mjtc-support-search-fields-wrp input.mjtc-support-search-input-fields{float: left;width: calc(100% / 3 - 10px);margin:0px 5px;padding: 10px;border-radius: 0px;line-height: initial;height: 50px;}
	div.mjtc-support-search-top div.mjtc-support-search-right{float: left;width: 30%;}
	div.mjtc-support-search-top div.mjtc-support-search-right div.mjtc-support-search-btn-wrp{float: left;width: 100%;}
	div.mjtc-support-search-top div.mjtc-support-search-right div.mjtc-support-search-btn-wrp input.mjtc-support-search-btn{width: calc(100% / 2 - 5px);padding: 13px 0;border-radius: 0px;line-height: initial;height: 50px;float: left;margin-right: 5px;}
	div.mjtc-support-search-top div.mjtc-support-search-right div.mjtc-support-search-btn-wrp input.mjtc-support-reset-btn{width: calc(100% / 2 - 5px);padding: 13px 0;border-radius: 0px;line-height: initial;height: 50px;float: left;}

	div.mjtc-support-table-wrp{float: left;width: 100%;padding: 0;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header{float: left;width: 100%;font-weight:bold;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header div.mjtc-support-table-header-col{padding: 15px;text-align: left;width: 25%;float: left;line-height: initial;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header div.mjtc-support-table-header-col:first-child{width: 10%;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header div.mjtc-support-table-header-col:nth-child(3){width: 40%;}
	div.mjtc-support-table-body{float: left;width: 100%;}
	div.mjtc-support-table-body div.mjtc-support-data-row{float: left;width: 100%;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col{padding: 15px;text-align: left;float: left;width: 25%;line-height: initial;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col a.mjtc-userpopup-link {display: inline-block;text-decoration: none;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col:first-child{width: 10%;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col:nth-child(3){width: 40%;}


	div#records div.ms_userpages{text-align: right;padding:5px; margin: 10px 5px;width: calc(100% - 10px);float:left;}
	div#records div.ms_userpages a.ms_userlink{display: inline-block;padding:5px 15px;margin-left:5px;text-decoration: none;background:rgba(0, 0, 0, 0.05) none repeat scroll 0 0;line-height: initial;}
	div#records div.ms_userpages span.ms_userlink{display: inline-block;padding:5px 15px;margin-left:5px;line-height: initial;}
	span.mjtc-support-display-block{display: none;}
	span.help-block{color:red !important;bottom: -30px;font-size: 13px;}
	select ::-ms-expand {display:none !important;}
	select{-webkit-appearance:none !important;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field input.mjtc-support-form-field-input.loading {background-image: url("'.esc_url(MJTC_PLUGIN_URL).'includes/images/spinning-wheel.gif");background-size: 25px 25px;background-position:right center;background-repeat: no-repeat;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field span.ms_product_found{background-image: url("'.esc_url(MJTC_PLUGIN_URL).'includes/images/good.png");background-size: 25px 25px;background-position:right center;background-repeat: no-repeat;width:30px;height:30px;top:10px;right:10px;position:absolute;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field span.ms_product_not_found{background-image: url("'.esc_url(MJTC_PLUGIN_URL).'includes/images/close.png");background-size: 25px 25px;background-position:right center;background-repeat: no-repeat;width:30px;height:30px;top:10px;right:10px;position:absolute;}

	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field span#premade div.mjtc-form-perm-msg{float: left;margin: 4px;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field span#premade div.mjtc-form-perm-msg .permade-no-rec{float: left;margin-top: 10px;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field span#premade div.mjtc-form-perm-msg a{display: inline-block;width: 100%;text-decoration: none;padding: 9px;border: 1px solid #e0e1e0;background: #fff;color: #575455;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field span#premade div.mjtc-form-perm-msg a:hover{border-color: #1572e8;color: #1572e8;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field.mjtc-support-form-premade-wrp{border: 1px solid #e0e1e0;padding: 10px;max-height: 163px;overflow-x: hidden;overflow-y: scroll;}

';
/*Code For Colors*/
$majesticsupport_css .= '
/* Add Form */
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field-title {color: '.$color2.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field input.mjtc-support-form-field-input{background-color:#fff;border:1px solid '.$color5.';color: '.$color4.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select#categoryid{background-color:'.$color3.';border:1px solid '.$color5.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select.mjtc-support-select-field{background-color:#fff !important;border:1px solid '.$color5.';color: '.$color4.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select.mjtc-support-form-field-select{background-color:'.$color3.' !important;border:1px solid '.$color5.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select#status{background-color:'.$color3.' !important;border:1px solid '.$color5.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select#parentid{background-color:'.$color3.' !important;border:1px solid '.$color5.';}
	span.mjtc-support-sub-fields{background-color:'.$color3.' !important;border:1px solid '.$color5.';}
	.mjtc-userpopup-link{color:'.$color2.';}
	div#records div.ms_userpages a.ms_userlink:last-child{background-color:'.$color2.';color:'.$color7.';}
	div#records div.ms_userpages a.ms_userlink:last-child:hover{background-color:'.$color1.';color:'.$color7.';}
	div.mjtc-support-form-btn-wrp{border-top:2px solid '.$color2.';}
	div.mjtc-support-reply-attachments div.mjtc-attachment-field-title{border:'.$color2.';}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button{background-color:'.$color1.' !important;color:'.$color7.' !important;border: 1px solid '.$color5.';}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button:hover{border-color: '.$color2.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select {border: 1px solid '.$color5.';color: '.$color4.';background: '.$color7.';}
	div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button{background: '.$color2.';color:'.$color7.';border: 1px solid '.$color5.';}
	div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button:hover{border-color:'.$color1.';}
	a.mjtc-support-delete-attachment{background-color:#ed3237;color:'.$color7.';}
	div.mjtc-support-radio-btn-wrp{background-color:'.$color3.';border:1px solid '.$color5.';}
	span.tk_attachments_addform{background-color:'.$color1.';color:'.$color7.';border: 1px solid '.$color5.';}
	span.tk_attachments_addform:hover{border-color: '.$color2.';}
	span.mjtc-support-apend-radio-btn{border:1px solid #6899d6;background-color: #fff;color: '.$color7.';}
	div.tk_attachment_value_wrapperform{border: 1px solid '.$color5.';background: #fff;}
	span.tk_attachment_value_text{border: 1px solid '.$color5.';background-color:'.$color7.';}
	div.mjtc-support-assigned-tome{border:1px solid '.$color5.';background: #fff;}
	span.help-block{color:red;}
	span.tk_attachments_configform {color: '.$color4.';}
	div#userpopup{background: '.$color7.';}
	div.ms-popup-header{background: '.$color1.';color:'.$color7.';}
	div.ms-popup-wrapper{background-color:'.$color7.';}
	div.mjtc-support-search-top div.mjtc-support-search-left div.mjtc-support-search-fields-wrp input.mjtc-support-search-input-fields{border:1px solid '.$color5.';background-color:#fff;color:'.$color4.';}
	div.mjtc-support-search-top div.mjtc-support-search-right div.mjtc-support-search-btn-wrp input.mjtc-support-search-btn{background: '.$color1.';color:'.$color7.';border:1px solid '.$color5.';}
	div.mjtc-support-search-top div.mjtc-support-search-right div.mjtc-support-search-btn-wrp input.mjtc-support-search-btn:hover{border-color:'.$color2.';}
	div.mjtc-support-search-top div.mjtc-support-search-right div.mjtc-support-search-btn-wrp input.mjtc-support-reset-btn{background: '.$color2.';color:'.$color7.';border:1px solid '.$color5.';}
	div.mjtc-support-search-top div.mjtc-support-search-right div.mjtc-support-search-btn-wrp input.mjtc-support-reset-btn:hover{border-color:'.$color1.';}

	div.mjtc-support-table-header{background-color:'.$color2.';color:'.$color7.'; border:1px solid '.$color5.';}
	div.mjtc-support-table-header div.mjtc-support-table-header-col{color: '.$color7.';}
	div.mjtc-support-table-header div.mjtc-support-table-header-col:last-child{}
	div.mjtc-support-table-body div.mjtc-support-data-row{border: 0;border-bottom:1px solid '.$color5.';}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col{}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col:last-child{}
	th.mjtc-support-table-th{border-right:1px solid '.$color5.';}
	tbody.mjtc-support-table-tbody{border:1px solid '.$color5.';}
	td.mjtc-support-table-td{border-right:1px solid '.$color5.';}
	div.mjtc-support-select-user-btn a#userpopup{background-color:'.$color1.';color:'.$color7.';border: 1px solid '.$color5.';}
	div.mjtc-support-select-user-btn a#userpopup:hover{border-color: '.$color1.';}
	span#premade select#premadeid {background-color: #fff !important;}



/* Add Form */

';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
