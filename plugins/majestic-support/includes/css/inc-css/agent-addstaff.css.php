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
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field input.mjtc-support-form-field-input{float: left;width: 100%;border-radius: 0px;padding: 10px;height: 50px;line-height: initial;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select.mjtc-support-form-field-select{float: left;width: 100%;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / 4% no-repeat #eee;height: 60px;line-height: initial;padding: 10px;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field.mjtc-support-from-field-wrp-full-width select#status{float: left;width: 100%;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 98% / auto no-repeat;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp.mjtc-support-from-field-wrp-full-width div.mjtc-support-from-field select#status{float: left;width: 50%;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 98% / auto no-repeat;}
	div.mjtc-support-form-btn-wrp{float: left;width:calc(100% - 20px);margin: 0px 10px;text-align: center;padding: 25px 0px 10px 0px;}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button{padding: 20px 10px;margin-right: 10px;min-width: 120px;border-radius: 0px;line-height: initial;}
	div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button{display: inline-block; padding: 20px 10px;min-width: 120px;border-radius: 0px;line-height: initial;text-decoration: none;}
	span.tk_attachments_configform {float: left;width: 100%;font-size: 14px;line-height: 25px;margin-top: 5px;}
	div.mjtc-support-select-user-field{float: left;width: 100%;}
	div.mjtc-support-select-user-field input#username-text{width: 100%;}
	div.mjtc-support-select-user-btn{float: left;width: 30%;position: absolute;top: 0;right: 0;	}
	div.mjtc-support-select-user-btn a#userpopup{display: inline-block;width: 100%;text-align: center;padding: 15px 10px;text-decoration: none;outline: 0px;line-height: initial;height:50px;}
	#records{float: left;width: 100%;padding: 0px 10px;}
	select.mjtc-support-select-field{float: left;width: 100%;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / auto no-repeat #eee; }
	div.mjtc-support-reply-attachments{display: inline-block;width: 100%;margin-bottom: 20px;}
	div.mjtc-support-reply-attachments div.mjtc-attachment-field-title{display: inline-block;width: 100%;padding: 15px 0 5px 0px;}
	div.mjtc-support-reply-attachments div.mjtc-attachment-field{display: inline-block;width: 100%;}
	div.tk_attachment_value_wrapperform{float: left;width:100%;padding:0px 0px;}
	div.tk_attachment_value_wrapperform span.tk_attachment_value_text{float: left;width: calc(100% / 3 - 10px);padding: 5px 5px;margin: 5px 5px;position: relative;}
	div.tk_attachment_value_wrapperform span.tk_attachment_value_text input.mjtc-attachment-inputbox{width: 100%;max-width: 100%;max-height:100%;}
	span.tk_attachment_value_text span.tk_attachment_remove{background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/close.png) no-repeat;background-size: 100% 100%;position: absolute;width: 20px;height: 20px;top: 12px;right:6px;}
	span.mjtc-support-staff-img {float: left;margin: 10px 0;width: 120px;}
	span.mjtc-support-staff-img img {}
	div#userpopupblack{background: rgba(0,0,0,0.7);position: fixed;width: 100%;height: 100%;top:0px;left:0px;z-index: 9999;}
	div#userpopup{position: fixed;top:50%;left:50%;width:40%; max-height: 70%; padding-top:0px;z-index: 99999;overflow-y: auto; overflow-x: hidden;transform: translate(-50%,-50%);}
	div.ms-popup-header{width:100%;font-size:20px;float:left;padding: 20px 10px; font-weight: bold;line-height: initial;text-transform: capitalize;}
	div.popup-header-close-img{position: absolute;top:22px;right: 22px;background-image:url('.esc_url(MJTC_PLUGIN_URL).'includes/images/close-icon-white.png);background-size: 100%;width:20px;height: 20px;opacity: 1;cursor: pointer;}
	div.ms-popup-wrapper input{margin-bottom:0px; }
	div.ms-popup-wrapper input#edited_time{font-size: 16px;}
	div.ms-popup-wrapper textarea{width: 100%;}
	div.ms-popup-wrapper div.mjtc-form-button-wrapper{text-align: center;border-top: 1px solid #e0dce0;width: 94%;margin: 0px 3%;margin-top: 20px;}
	div.ms-popup-wrapper div.mjtc-form-button-wrapper input.button{display: inline-block;float: none;padding: 5px 20px;border-radius: 2px;margin-top: 15px;margin-bottom: 15px;min-width: 100px;}
	div.ms-popup-wrapper div.mjtc-form-button-wrapper input.mjtc-merge-cancel-btn{padding: 16px 10px;min-width: 120px;border-radius: unset;}
	div.ms-popup-wrapper div.mjtc-form-button-wrapper input.mjtc-merge-save-btn{padding: 16px 10px;min-width: 120px;border-radius: unset;}
	div.mjtc-support-popup-search-wrp{float: left;width: 100%;padding: 30px 5px 15px;}
	div.mjtc-support-search-top{float: left;width: 100%;}
	div.mjtc-support-search-top div.mjtc-support-search-left{float: left;width: 70%;}
	div.mjtc-support-search-top div.mjtc-support-search-left div.mjtc-support-search-fields-wrp{float: left;width: 100%;padding: 0px}
	div.mjtc-support-search-top div.mjtc-support-search-left div.mjtc-support-search-fields-wrp input.mjtc-support-search-input-fields{float: left;width: calc(100% / 3 - 10px);margin:0px 5px;padding: 10px;border-radius: 0px;line-height: initial;height: 50px;}
	div.mjtc-support-search-top div.mjtc-support-search-right{float: left;width: 30%;}
	div.mjtc-support-search-top div.mjtc-support-search-right div.mjtc-support-search-btn-wrp{float: left;width: 100%;}
	div.mjtc-support-search-top div.mjtc-support-search-right div.mjtc-support-search-btn-wrp input.mjtc-support-search-btn{width: calc(100% / 2 - 5px);padding: 10px;border-radius: 0px;line-height: initial;height: 50px;}
	div.mjtc-support-search-top div.mjtc-support-search-right div.mjtc-support-search-btn-wrp input.mjtc-support-reset-btn{width: calc(100% / 2 - 5px);padding: 10px;border-radius: 0px;line-height: initial;height: 50px;}
	div.mjtc-support-table-wrp{float: left;width: 100%;padding: 0;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header{float: left;width: 100%;margin-bottom: 10px;font-weight:bold;}
	#userpopup div.mjtc-support-table-header{margin-bottom: 0px;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header div.mjtc-support-table-header-col{padding: 15px;text-align: center;float: left;width: 25%;line-height: initial;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header div.mjtc-support-table-header-col:first-child{text-align: left;padding-left: 10px;width: 10%;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header div.mjtc-support-table-header-col:nth-child(3){width: 40%;}
	div.mjtc-support-table-body{float: left;width: 100%;}
	div.mjtc-support-table-body div.mjtc-support-data-row{float: left;width: 100%;margin-bottom: 10px;}
	#userpopup div.mjtc-support-data-row{margin-bottom: 0px;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col{padding: 15px;text-align: center;float: left;width: 25%;line-height: initial;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col span.mjtc-support-title {display: inine-block;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col a.mjtc-userpopup-link {display: inine-block;text-decoration: none;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col:first-child{text-align: left;padding-left: 10px;width: 10%;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col:nth-child(3){width: 40%;}
	span.mjtc-support-display-block{display: none;}
	div#records div.ms_userpages{text-align: right;padding:5px; margin: 10px 5px;width: calc(100% - 10px);float:left;}
	div#records div.ms_userpages a.ms_userlink{display: inline-block;padding:5px 15px;margin-left:5px;text-decoration: none;background:rgba(0, 0, 0, 0.05) none repeat scroll 0 0;line-height: initial;}
	div#records div.ms_userpages span.ms_userlink{display: inline-block;padding:5px 15px;margin-left:5px;line-height: initial;}
	span.help-block{font-size:14px;}
	span.help-block{color:red;}
	div.mjtc-support-append-signature-wrp{float: left;width: calc(100% / 2 - 25px); margin-right:25px;margin-bottom: 20px;}
	div.mjtc-support-append-signature-wrp.mjtc-support-append-signature-wrp-full-width{width: 100%;}
	div.mjtc-support-append-signature-wrp div.mjtc-support-signature-radio-box{float: left;width: calc(100% / 3 - 10px);margin: 0px 5px;padding: 11px;}
	div.mjtc-support-append-signature-wrp div.mjtc-support-signature-radio-box.mjtc-support-signature-radio-box-full-width{width: 100%;}
	div.mjtc-support-append-signature-wrp div.mjtc-support-append-field-title{float: left;width: 100%;margin-bottom: 15px;}
	div.mjtc-support-append-signature-wrp div.mjtc-support-append-field-wrp{float: left;width: 100%;}
	div.mjtc-support-append-signature-wrp div.mjtc-support-signature-radio-box label#forcanappendsignature{margin: 0px;display: inline-block;vertical-align:text-bottom;line-height: initial;margin-left: 5px;}
	select ::-ms-expand {display:none !important;}
	select{-webkit-appearance:none !important;}
	.mjtc_agent-image-wrp {display: inline-block;height: 70px;width: 120px;border-radius: 100%;position: relative;margin-top: 10px;}
	.mjtc_agent-image-wrp img.mjtc_agent-image {display: inline-block;position: absolute;top: 0;right: 0;bottom: 0;left: 0;margin: auto;max-width: 100%;max-height: 100%;}
	.mjtc_agent-image-wrp img#mjtc_delete-agent-image {position: absolute;top: 0;right: -10px;cursor: pointer;}

';
/*Code For Colors*/
$majesticsupport_css .= '

/* Add Form */
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field-title {color:'.$color2.';}
	div.mjtc-support-select-user-btn a#userpopup{background-color:'.$color1.';color:'.$color7.';border: 1px solid '.$color5.';}
	div.mjtc-support-select-user-btn a#userpopup:hover{border-color: '.$color1.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field input.mjtc-support-form-field-input{background-color:#fff;border:1px solid '.$color5.';color: '.$color4.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select#categoryid{background-color:'.$color3.';border:1px solid '.$color5.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select.mjtc-support-form-field-select{background-color:'.$color3.' !important;border:1px solid '.$color5.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select#status{background-color:#fff !important;border:1px solid '.$color5.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select#parentid{background-color:'.$color3.' !important;border:1px solid '.$color5.';}
	span.mjtc-support-sub-fields{background-color:'.$color3.' !important;border:1px solid '.$color5.';}
	.mjtc-userpopup-link{color:'.$color2.';}
	div#records div.ms_userpages a.ms_userlink:last-child{background-color:'.$color2.';color:'.$color7.';}
	div#records div.ms_userpages a.ms_userlink:last-child:hover{background-color:'.$color1.';color:'.$color7.';}
	div.mjtc-support-form-btn-wrp{border-top:2px solid '.$color2.';}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button{background-color:'.$color1.' !important;color:'.$color7.' !important;border:1px solid '.$color5.';}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button:hover{border-color:'.$color2.';}
	div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button{background: '.$color2.';color:'.$color7.';border:1px solid '.$color5.';}
	div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button:hover{border-color:'.$color1.';}
	a.mjtc-support-delete-attachment{background-color:#ed3237;color:'.$color7.';}
	div.mjtc-support-radio-btn-wrp{background-color:'.$color3.';border:1px solid '.$color5.';}
	span.tk_attachments_addform{background-color:'.$color2.';color:'.$color7.';}
	select.mjtc-support-select-field{background-color:#fff !important;border:1px solid '.$color5.';color: '.$color4.';}
	div.ms-popup-header{background: '.$color1.';color:'.$color7.';}
	div#userpopup{background: '.$color7.';}
	div.ms-popup-wrapper{background-color:'.$color7.';}
	span.tk_attachments_configform{color:'.$color4.';}
	div.tk_attachment_value_wrapperform{border: 1px solid '.$color5.';background: #fff;}
	span.tk_attachment_value_text{border: 1px solid '.$color5.';background-color:'.$color7.';}
	div.mjtc-support-append-signature-wrp div.mjtc-support-signature-radio-box{border:1px solid '.$color5.';background-color:#fff;}
	div.mjtc-support-search-top div.mjtc-support-search-left div.mjtc-support-search-fields-wrp input.mjtc-support-search-input-fields{border:1px solid '.$color5.';background-color:#fff;color: '.$color4.';}
	div.mjtc-support-search-top div.mjtc-support-search-right div.mjtc-support-search-btn-wrp input.mjtc-support-search-btn{background: '.$color1.';color:'.$color7.';border:1px solid '.$color5.';}
	div.mjtc-support-search-top div.mjtc-support-search-right div.mjtc-support-search-btn-wrp input.mjtc-support-search-btn:hover{border-color:'.$color2.';}
	div.mjtc-support-search-top div.mjtc-support-search-right div.mjtc-support-search-btn-wrp input.mjtc-support-reset-btn{background: '.$color2.';color:'.$color7.';border:1px solid '.$color5.';}
	div.mjtc-support-search-top div.mjtc-support-search-right div.mjtc-support-search-btn-wrp input.mjtc-support-reset-btn:hover{border-color: '.$color1.';}
	div.mjtc-support-table-header{background-color:'.$color2.';color:'.$color7.'; border:1px solid '.$color5.';}
	div.mjtc-support-table-header div.mjtc-support-table-header-col{color: '.$color7.';}
	div.mjtc-support-table-header div.mjtc-support-table-header-col:last-child{}
	div.mjtc-support-table-body div.mjtc-support-data-row{border:1px solid '.$color5.';}
	#userpopup div.mjtc-support-table-header, 
	#userpopup div.mjtc-support-table-body div.mjtc-support-data-row {border: 0;border-bottom:1px solid '.$color5.';}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col{}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col:last-child{}
	th.mjtc-support-table-th{border-right:1px solid '.$color5.';}
	tbody.mjtc-support-table-tbody{border:1px solid '.$color5.';}
	td.mjtc-support-table-td{border-right:1px solid '.$color5.';}

/* Add Form */

';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
