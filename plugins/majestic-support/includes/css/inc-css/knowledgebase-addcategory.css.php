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
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field-title{text-transform: capitalize;float: left;width: 100%;margin-bottom: 5px;}
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field{float: left;width: 100%;}
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field input.mjtc-support-form-field-input{float: left;width: 100%;border-radius: 0px;padding:14px 10px;line-height: initial;min-height: 50px;}
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select#categoryid{float: left;width: 100%;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / 4% no-repeat;padding: 10px;line-height: initial;height: 50px;}
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field textarea {width: 100%;padding: 10px;line-height: initial;min-height: 50px;}
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp .tk_attachments_configform {float: left;width: 100%;margin-top: 5px;font-size: 14px;line-height: 25px;}
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select.mjtc-support-form-field-select{float: left;width: 100%;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / 4% no-repeat #eee;padding: 10px;height: 50px;line-height: initial;}
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select#status{float: left;width: 100%;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / 4% no-repeat ;padding: 10px;height: 50px;line-height: initial;}
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select#parentid{float: left;width: 100%;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / 4% no-repeat ;padding: 10px;height: 50px;line-height: initial;}

div.mjtc-support-form-btn-wrp{float: left;width:calc(100% - 20px);margin: 0px 10px;text-align: center;padding: 25px 0px 10px 0px;}
div.mjtc-support-form-btn-wrp input.mjtc-support-save-button{padding: 20px 10px;margin-right: 10px;min-width: 120px;border-radius: 0px;line-height: initial;}
div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button{display: inline-block; padding: 20px 10px;min-width: 120px;border-radius: 0px;line-height: initial;text-decoration: none;}
span.mjtc-support-sub-fields{float:left;display: inline-block;width: calc(100% / 4 - 10px);margin-right: 10px;padding: 10px;line-height: initial;height: 50px;}
span.mjtc-support-sub-fields label {vertical-align: middle;margin-left: 5px !important;}

input#kb1{width: 20px;height:20px;vertical-align: sub;}
input#downloads1{width: 20px;height:20px;vertical-align: sub;}
input#announcement1{width: 20px;height:20px;vertical-align: sub;}
input#faqs1{width: 20px;height:20px;vertical-align: sub;}
label#forkb{display: inline-block;margin: 0px; }
label#fordownloads{display: inline-block;margin: 0px; }
label#forannouncement{display: inline-block;margin: 0px; }
label#forfaqs{display: inline-block;margin: 0px; }
input#append1{vertical-align: sub;}
label#forappend{display: inline-block;margin: 0px;}

div.mjtc-support-radio-btn-wrp{float: left;width: 100%;padding: 11px}
div.mjtc-support-radio-btn-wrp input.mjtc-support-form-field-radio-btn{margin-right: 5px; vertical-align: top;}
div.mjtc-support-radio-btn-wrp label#forsendmail{margin: 0px;display: inline-block; margin-right: 30px;}
img.mjtc-support-category-img{display: inline-block;max-width: 100%;max-height: 100%;margin-top: 10px;}
div.mjtc-support-from-field{position: relative;}

div#msgshowcategory{float: left;width: 100%;}
div#msgshowcategory div.mjtc-support-notice-wrapper{float: left; box-sizing:border-box;padding: 15px;margin-bottom: 10px; width: 100%;}
div#msgshowcategory div.mjtc-support-notice-wrapper div.mjtc-support-notice{float: left;width: auto;margin: 0px 5px 0px 0px;}
div#msgshowcategory div.mjtc-support-notice-wrapper div.mjtc-support-question{float: left;width: auto;}

div.mjtc-support-answer-btn{float: left;width: 100%;padding-top: 10px;}
div.mjtc-support-answer-btn a.mjtc-support-yes{display: inline-block;min-width: 100px;text-align: center;padding: 8px 5px;margin:0px 10px 0px 0px;}
div.mjtc-support-answer-btn a.mjtc-support-no{display: inline-block;min-width: 100px;text-align: center;padding: 8px 5px;}
span.help-block{font-size:14px;}
span.help-block{color:red;}

select ::-ms-expand {display:none !important;}
select{-webkit-appearance:none !important;}
a.mjtc-support-delete-attachment{padding: 0 5px;border-radius: 10px;}
div.mjtc_category-image-wrp{display: inline-block;height: 70px;width: 100px;border-radius: 100%;position: relative;margin-top: 10px;}
div.mjtc_category-image-wrp img.mjtc_category-image{display: inline-block;position: absolute;top: 0;right: 0;bottom: 0;left: 0;margin: auto;max-width: 100%;max-height: 100%;}
div.mjtc_category-image-wrp img#mjtc_delete-category-image{position: absolute;top: 0;right: -10px;cursor: pointer;}

';
/*Code For Colors*/
$majesticsupport_css .= '

/* Add Form */
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field-title {color: '.$color2.';}
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field input.mjtc-support-form-field-input{background-color:#fff;border:1px solid '.$color5.';color:'.$color4.';}
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select#categoryid{background-color:'.$color3.';border:1px solid '.$color5.';}
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field textarea {background-color:#fff;border:1px solid '.$color5.';color:'.$color4.';}
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select.mjtc-support-form-field-select{background-color:'.$color3.' !important;border:1px solid '.$color5.';}
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select#status{background-color:#fff !important;border:1px solid '.$color5.';color: '.$color4.';}
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select#parentid{background-color:#fff !important;border:1px solid '.$color5.';color: '.$color4.';}
span.mjtc-support-sub-fields{background-color:#fff !important;border:1px solid '.$color5.';color: '.$color4.';}
div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp .tk_attachments_configform {color: '.$color4.';}
.mjtc-userpopup-link{color:'.$color2.';}
div.mjtc-support-form-btn-wrp{border-top:2px solid '.$color2.';}
div.mjtc-support-form-btn-wrp input.mjtc-support-save-button{background-color:'.$color1.' !important;color:'.$color7.' !important;border:1px solid '.$color5.';}
div.mjtc-support-form-btn-wrp input.mjtc-support-save-button:hover{border-color:'.$color2.';}
div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button{background: '.$color2.';color:'.$color7.';border:1px solid '.$color5.';}
div.mjtc-support-form-btn-wrp a.mjtc-support-cancel-button:hover{border-color:'.$color1.';}
a.mjtc-support-delete-attachment{background-color:#ed3237;color:'.$color7.';}
div.mjtc-support-radio-btn-wrp{background-color:'.$color3.';border:1px solid '.$color5.';}
span.tk_attachments_addform{background-color:'.$color2.';color:'.$color7.';}
/* Add Form */

';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
