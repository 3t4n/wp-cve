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
/* FeedBack */
	div.mjtc-support-feedback-fields-margin-bottom{margin-bottom: 10px !important;}
	div.mjtc-support-feedback-wrapper{float: left;width: 100%;margin-top: 5px;}
	div.mjtc-support-feedback-list-wrapper{float: left;width: 100%; padding: 0px;margin-top: 20px;}
	div.mjtc-support-feedback-heading{display: inline-block;width: 100%;padding: 15px;font-weight: bold;margin-bottom: 20px;line-height: initial;}
	div.ms-feedback-det-wrp {float: left;width: 100%;box-shadow: 0 8px 6px -6px #dedddd;margin-bottom: 15px;}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list {float: left;width: 100%;margin: 0px 0;background: #fff;}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list div.ms-feedback-det-list-top {float: left;width: 100%;padding: 10px;} 
	div.ms-feedback-det-list-data-wrp {float: left;width: calc(100% - 100px);padding: 10px;}
	div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-top {float: left;width: 100%;padding: 10px 0px;padding-top: 0px;}
	div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-top div.ms-feedback-det-list-data-top-title {float: left;}
	div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-top div.ms-feedback-det-list-data-top-val {float: left;}
	div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-top div.ms-feedback-det-list-data-top-val a.ms-feedback-det-list-data-top-val-txt {display: inline-block;}
	div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-top div.ms-feedback-det-list-data-top-val a.ms-feedback-det-list-data-top-val-txt img{display: inline-block;margin-left: 5px;}
	div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-btm{display:inline-block;float:left;width: calc(100% / 3);}
	div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-btm div.ms-feedback-det-list-datea-btm-rec{display:inline-block;float:left;padding: 10px 0px;}
	div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-btm div.ms-feedback-det-list-datea-btm-rec div.ms-feedback-det-list-data-btm-title{display:inline-block;float:left;}
	div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-btm div.ms-feedback-det-list-datea-btm-rec div.ms-feedback-det-list-data-btm-val{display:inline-block;float:left;}
	div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-left {float: left;width: 65%;}
	div.ms-feedback-det-list-data-row {float: left;width: 100%;padding-bottom: 10px;}
	div.ms-feedback-det-list-data-row div.ms-feedback-det-list-data-title {float: left;line-height: initial;}
	div.ms-feedback-det-list-data-row div.ms-feedback-det-list-data-val {float: left;margin-left: 5px;line-height: initial;}
	div.ms-feedback-det-list-data-row div.ms-feedback-det-list-data-val a.ms-feedback-det-list-data-anch {display: inline-block;text-decoration: none;}
	div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-right {float: left;width: 35%;}
	div.ms-feedback-det-list-cust-flds {float: left;width: 100%;padding-bottom: 10px;}
	div.ms-feedback-det-list-cust-flds:last-child {padding-bottom: 0px;}
	div.ms-feedback-det-list-cust-flds .ms-feedback-det-list-cust-flds-title {float: left;line-height: initial;}
	div.ms-feedback-det-list-cust-flds .ms-feedback-det-list-cust-flds-val {float: left;margin-left: 5px;line-height: initial;}

	div.ms-feedback-det-wrp  div.ms-feedback-det-list div.ms-feedback-det-list-btm{display:inline-block;width:100%;float:left;padding: 15px;background: #fafafa;}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list div.ms-feedback-det-list-btm div.ms-feedback-det-list-btm-title{display:inline-block;float:left;line-height: initial;}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list div.ms-feedback-det-list-btm div.ms-feedback-det-list-btm-val{display:inline-block;float:left;line-height: initial;}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list-btm{display:inline-block;width:100%;}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list div.ms-feedback-det-list-img-wrp{display:inline-block;float: left;text-align: center;padding: 10px;width: 100px;height: 100px;border-radius: 100%;}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list div.ms-feedback-det-list-img-wrp img{display:inline-block;max-width: 100%;height: auto;}
	div.mjtc-support-top-search-wrp{float: left;width: 100%;}
	div.mjtc-support-search-heading-wrp{float: left;width: 100%; padding: 10px 10px 10px 0px;}
	div.mjtc-support-search-heading-wrp div.mjtc-support-heading-left{float: left;width: 70%;padding: 10px;}
	div.mjtc-support-search-heading-wrp div.mjtc-support-heading-right{float: left;width: 30%;text-align: right;}
	div.mjtc-support-search-heading-wrp div.mjtc-support-heading-right a.mjtc-support-add-download-btn{display: inline-block;padding: 10px;text-decoration: none;outline: 0px;}
	div.mjtc-support-search-heading-wrp div.mjtc-support-heading-right a.mjtc-support-add-download-btn span.mjtc-support-add-img-wrp{display: inline-block;margin-right: 5px;}
	div.mjtc-support-search-heading-wrp div.mjtc-support-heading-right a.mjtc-support-add-download-btn span.mjtc-support-add-img-wrp img{vertical-align: text-bottom;}
	div.mjtc-support-search-fields-wrp{float: left;width: 100%;padding: 10px 0 10px 10px;}
	form#majesticsupportform{float: left;width: 100%;}
	div.mjtc-support-fields-wrp{float: left;width: 100%;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field{float: left; width: calc(100% / 2);position: relative;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field-download-search{width:75%;margin: 0px;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field input.mjtc-support-field-input{float: left;width:calc(100% - 10px);margin-right:10px; border-radius: 0px; padding: 10px;line-height: initial;height: 50px;}
	select.mjtc-support-select-field{float: left;width:calc(100% - 10px);margin-right:10px; border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / auto no-repeat #eee; padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-search-form-btn-wrp{float: left;width: 100%;}
	.mjtc-support-feedbacks-search-form-btn-overall-wrp .mjtc-support-search-form-btn-wrp{width:auto;}
	div.mjtc-support-search-form-btn-wrp-download {width:25%;padding: 0px;margin-top: 0px;}
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button{float: left;width: 120px;padding: 17px 0px;text-align: center;margin-right: 10px; border-radius: unset;line-height: initial;}
	.mjtc-support-feedbacks-search-form-btn-overall-wrp .mjtc-support-search-form-btn-wrp input.mjtc-search-button{float: left;width: 120px;padding: 15px 0px;text-align: center;margin-right: 10px; border-radius: unset;line-height: initial;}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button{float: left;width: 120px;padding: 17px 0px;text-align: center;border-radius: unset;line-height: initial;}
	.mjtc-support-feedbacks-search-form-btn-overall-wrp .mjtc-support-search-form-btn-wrp input.mjtc-reset-button{float: left;width: 120px;padding: 15px 0px;text-align: center;border-radius: unset;line-height: initial;}
	div.mjtc-support-search-form-btn-wrp-download input.mjtc-search-button{float: left;width: calc(100% / 2 - 10px); padding: 17px 0px;text-align: center;margin: 0px 0px 0px 10px; border-radius: 0px; }
	div.mjtc-support-search-form-btn-wrp-download input.mjtc-reset-button{float: left;width: calc(100% / 2 - 10px); padding: 17px 0px;text-align: center; margin: 0px 0px 0px 10px; border-radius: 0px;}

	select ::-ms-expand {display:none !important;}
	select{-webkit-appearance:none !important;}
';
/*Code For Colors*/
$majesticsupport_css .= '
	div.mjtc-support-search-fields-wrp {background: '.$color3.';}
	div.mjtc-support-top-search-wrp{border:1px solid '.$color5.';}
	div.mjtc-support-search-heading-wrp{background-color:'.$color4.';color:'.$color7.';}
	div.mjtc-support-search-heading-wrp div.mjtc-support-heading-right a.mjtc-support-add-download-btn{background:'.$color2.';color:'.$color7.';}
	div.mjtc-support-search-heading-wrp div.mjtc-support-heading-right a.mjtc-support-add-download-btn:hover{background:rgba(125, 135, 141, 0.4);color:'.$color7.';}
	div.ms-feedback-det-list-data-row div.ms-feedback-det-list-data-val a.ms-feedback-det-list-data-anch {color: '.$color4.';}
	div.ms-feedback-det-list-data-row div.ms-feedback-det-list-data-val.name {color: '.$color1.';}
	div.ms-feedback-det-list-data-row div.ms-feedback-det-list-data-title {color: '.$color2.';}
	div.ms-feedback-det-list-data-row div.ms-feedback-det-list-data-val {color: '.$color4.';}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field input.mjtc-support-field-input{background-color:#fff;border:1px solid '.$color5.';color: '.$color4.';}
	select.mjtc-support-select-field{background-color:#fff !important;border:1px solid '.$color5.';color: '.$color4.';}
	select#departmentid{background-color:'.$color3.';border:1px solid '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button{background: '.$color1.' !important;color:'.$color7.' !important;border: 1px solid '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button:hover{border-color: '.$color2.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button{background: '.$color2.';color:'.$color7.';border: 1px solid '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button:hover{border-color: '.$color1.';}
	div.ms-feedback-det-list-cust-flds .ms-feedback-det-list-cust-flds-title {color: '.$color2.';}
	div.ms-feedback-det-list-cust-flds .ms-feedback-det-list-cust-flds-value {color: '.$color4.';}
	div.ms-feedback-det-wrp div.ms-feedback-det-list div.ms-feedback-det-list-btm div.ms-feedback-det-list-btm-title {color: '.$color4.';}
	div.ms-feedback-det-wrp div.ms-feedback-det-list div.ms-feedback-det-list-btm div.ms-feedback-det-list-btm-val {color: '.$color2.';}


';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
