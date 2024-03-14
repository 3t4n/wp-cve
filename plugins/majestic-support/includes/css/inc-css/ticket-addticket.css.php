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
	form.mjtc-support-form1{display:inline-block; width: 100%;padding: 25px;}
	/*.mjtc-support-add-form-wrapper {display:flex; flex-wrap:wrap;}*/
	.mjtc-support-add-form-wrapper {float:left;width: 100%;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp{float: left;width: calc(100% / 2 - 10px);margin: 0px 5px 20px; }
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp.mjtc-support-from-field-wrp-full-width{float: left;width: calc(100% / 1 - 10px); margin-bottom: 30px; }
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field-title{text-transform: capitalize;float: left;width: 100%;margin-bottom: 5px;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field{float: left;width: 100%;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field input.mjtc-support-form-field-input{float: left;width: 100%;border-radius: 0px;padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select.mjtc-support-form-field-select{float: left;width: 100%;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / 4% no-repeat #eee;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select.inputbox{float: left;width: 100%;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / 4% no-repeat #fff;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select.inputbox.mjtc-form-multi-select-field{background: #fff;}
	div.mjtc-support-form-btn-wrp{float: left;width:calc(100% - 20px);margin: 0px 10px;text-align: center;padding: 25px 0px 10px 0px;}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button{padding: 20px 10px;margin-right: 10px;min-width: 120px;border-radius: 0px;line-height: initial;}
	div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button{display: inline-block; padding: 20px 10px;min-width: 120px;border-radius: 0px;line-height: initial;text-decoration: none;}
	select.mjtc-support-select-field{float: left;width: 100%;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / auto no-repeat #eee; }
	div.mjtc-support-reply-attachments{display: inline-block;width: 100%;margin-bottom: 20px;}
	div.mjtc-support-reply-attachments div.mjtc-attachment-field-title{display: inline-block;width: 100%;padding: 15px 0 5px 0px;}
	div.mjtc-support-reply-attachments div.mjtc-attachment-field{display: inline-block;width: 100%;}
	div.tk_attachment_value_wrapperform{float: left;width:100%;padding:0px 0px;}
	div.tk_attachment_value_wrapperform span.tk_attachment_value_text{float: left;width: calc(100% / 3 - 10px);padding: 5px 5px;margin: 5px 5px;position: relative;}
	div.tk_attachment_value_wrapperform span.tk_attachment_value_text input.mjtc-attachment-inputbox{width: 100%;max-width: 100%;max-height:100%;}
	span.tk_attachment_value_text span.tk_attachment_remove{background: url("'.esc_url(MJTC_PLUGIN_URL).'includes/images/close.png") no-repeat;background-size: 100% 100%;position: absolute;width: 30px;height: 30px;top: 3px;right:7px;cursor: pointer;}
	span.tk_attachments_configform{display: inline-block;float:left;line-height: 25px;margin-top: 10px;width: 100%; font-size: 14px;}
	span.tk_attachments_addform{text-transform:capitalize; position: relative;display: inline-block;padding: 13px 10px;cursor: pointer;margin-top: 10px;min-width: 120px;text-align: center;line-height: initial;} 
	span.help-block{font-size:13px;color:red !important;bottom: -30px;}
	.mjtc-attachment-field span.help-block{bottom: -35px;}
	select ::-ms-expand {display:none !important;}
	select{-webkit-appearance:none !important;}
	div.mjtc-support-custom-radio-box {width: 20%;}
	div.mjtc-support-radio-box {width: auto;}
	div.mjtc-support-from-field-wrp div.mjtc-support-from-field input.mjtc-form-date-field {float: left;width: 100%;padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-from-field-wrp div.mjtc-support-from-field textarea.mjtc-support-custom-textarea {float: left;width: 100%;padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-from-field-wrp div.mjtc-support-from-field select.mjtc-form-input-field {float: left;width: 100%;padding: 10px;line-height: initial;height: 50px;}
	span.mjtc-attachment-file-box {padding: 9px 10px 8px;}
	input#append_premade1{vertical-align: baseline;margin-right:5px;}
	a.mjtc-support-delete-attachment{padding: 0 5px;border-radius: 10px;}


	
';
/*Code For Colors*/
$majesticsupport_css .= '

	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field input.mjtc-support-form-field-input{border:1px solid '.$color5.';color: '.$color4.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select.mjtc-support-form-field-select{border:1px solid '.$color5.';color: '.$color2.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select.inputbox{border:1px solid '.$color5.';color: '.$color4.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field-title {color: '.$color2.';}
	select.mjtc-support-select-field{border:1px solid '.$color5.';color: '.$color2.';background-color: #fff !important;}
	div.mjtc-support-reply-attachments div.mjtc-attachment-field-title{color:'.$color2.';}
	span.tk_attachments_configform{color:'.$color4.';}
	div.mjtc-support-form-btn-wrp{border-top:2px solid '.$color2.';}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button{background-color:'.$color1.' !important;color:'.$color7.' !important;border: 1px solid '.$color5.';}
	div.mjtc-support-form-btn-wrp input.mjtc-support-save-button:hover{border-color: '.$color2.';}
	div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button{background-color:'.$color2.';color:'.$color7.';border: 1px solid '.$color5.';}
	div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button:hover{border-color: '.$color1.';}
	a.mjtc-support-delete-attachment{background-color:#ed3237;color:'.$color7.';}
	div.mjtc-support-radio-btn-wrp{background-color:'.$color3.';border:1px solid '.$color5.';}
	span.tk_attachments_addform{background-color:'.$color1.';color:'.$color7.';border: 1px solid '.$color1.';}
	span.tk_attachments_addform:hover{border-color: '.$color2.';}
	span.mjtc-support-apend-radio-btn{border:1px solid '.$color5.';background-color: '.$color3.';}
	div.tk_attachment_value_wrapperform{border: 1px solid '.$color5.';}
	span.tk_attachment_value_text{border: 1px solid '.$color5.';background-color:'.$color7.';}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select{border: 1px solid '.$color5.';color: '.$color4.';}
	span.help-block{color:red !important;}
';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
