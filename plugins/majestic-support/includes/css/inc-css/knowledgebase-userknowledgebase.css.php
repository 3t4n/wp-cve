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
	
	div.mjtc-support-knowledgebase-wrapper{float: left;width:100%;margin-top: 0px;}
	div.mjtc-support-top-search-wrp{float: left;width: 100%;}
	div.mjtc-support-search-heading-wrp{float: left;width: 100%; padding: 10px 10px 10px 0px;}
	div.mjtc-support-search-heading-wrp div.mjtc-support-heading-left{float: left;width: 70%;padding: 10px;}
	div.mjtc-support-search-fields-wrp{float: left;width: 100%;padding: 10px 10px 0 0px;background:#f5f2f5;}
	
	form#majesticsupportform{float: left;width: 100%;}
	div.mjtc-support-fields-wrp{float: left;width: 100%;}
	.mjtc-support-fields-wrp.mjtc-support-knowledgebase-field-over-all-wrapper{width: 75%;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field{float: left; width: calc(100% / 2 - 10px);margin-left:10px;position: relative;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field-download-search{width:75%;margin: 0px;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field input.mjtc-support-field-input{float: left;width: 100%;border-radius: 0px; padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field-download-search2 {margin-bottom: 10px;}
	
	div.mjtc-support-search-form-btn-wrp{float: left;width: 100%; padding: 0px 5px;margin-top: 10px;}
	div.mjtc-support-search-form-btn-wrp-download {width:25%;padding: 0px;margin-top: 0px;}
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button{float: left;width: 120px;padding: 13px 0px;height: 50px;text-align: center;margin-right: 10px; border-radius: unset;}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button{float: left;width: 120px;padding: 13px 0px;height: 50px;text-align: center;border-radius: unset;}
	div.mjtc-support-search-form-btn-wrp-download input.mjtc-search-button{float: left;width: calc(100% / 2 - 10px); padding: 15px 0px;text-align: center;margin: 0px 0px 0px 10px; border-radius: 0px; line-height: initial;}
	div.mjtc-support-search-form-btn-wrp-download input.mjtc-reset-button{float: left;width: calc(100% / 2 - 10px); padding: 15px 0px;text-align: center; margin: 0px 0px 0px 10px; border-radius: 0px;line-height: initial;}
	
	div.mjtc-support-categories-wrp{float: left;width: 100%;margin-top: 25px;}
	div.mjtc-support-margin-bottom{margin-bottom: 20px;margin-top: 10px;}
	div.mjtc-support-categories-heading-wrp{float: left;font-weight:bold;width: 100%;padding: 15px;line-height: initial;}
	div.mjtc-support-categories-wrp div.mjtc-support-position-relative{position: relative;}
	div.mjtc-support-categories-content{float: left;width: 100%;padding: 20px 0px 0px;}
	div.mjtc-support-categories-content div.mjtc-support-category-box{float: left;width:calc(13.6% - 10px);margin: 0px 5px;margin-bottom: 10px;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title{float: left;text-decoration: none;outline: 0px;width: 100%;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-name{float: left;padding: 15px 5px;width: 100%;line-height: initial;text-align: center;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-download-logo{display: inline-block;float: right;padding: 5px;width: 30px;height: 30px;text-align: center;margin: 10px 10px;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-download-logo img.mjtc-support-download-img{vertical-align: unset;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-kb-logo{display: inline-block;float: left;height: 100px;width: 100%;position: relative;margin: 0;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-kb-logo img.mjtc-support-kb-img{position: absolute;top: 0px;left: 0px;right: 0px;bottom: 0px;margin:auto;max-width: 100%;max-height: 100%;}
	
	div.mjtc-support-downloads-wrp{float: left;width: 100%;margin-top: 18px;}
	div.mjtc-support-downloads-wrp div.mjtc-support-downloads-heading-wrp{float: left;width: 100%;padding: 15px;line-height: initial;font-weight:bold;}
	div.mjtc-support-downloads-content{float: left;width: 100%;padding: 20px 0px;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box{float: left;width: 100%;padding: 10px;box-shadow: 0 8px 6px -6px #dedddd; margin-bottom: 10px;line-height: initial;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left{float: left;width: 100%;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title{float: left;width: 100%;padding: 9px; cursor: pointer;text-decoration: none;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title img.mjtc-support-download-icon{float: left;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title span.mjtc-support-download-name{width: calc(100% - 60px); display: inline-block;padding: 10px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-right{float: left;width: 20%;}
	div.mjtc-support-download-btn{float: left;width: 100%;text-align: center;}
	div.mjtc-support-download-btn button.mjtc-support-download-btn-style{display: inline-block;padding: 9px 20px;border-radius: unset;font-weight: unset;}
	div.mjtc-support-download-btn a.mjtc-support-download-btn-style{display: inline-block;padding: 9px 20px;border-radius: unset;font-weight: unset;text-decoration: none;outline: 0;}
	div.mjtc-support-download-btn button.mjtc-support-download-btn-style img.mjtc-support-download-btn-icon{vertical-align: text-top;margin-right: 5px;}
	div.mjtc-support-download-btn a.mjtc-support-download-btn-style img.mjtc-support-download-btn-icon{vertical-align: text-top;margin-right: 5px;}

	select ::-ms-expand {display:none !important;}
	select{-webkit-appearance:none !important;}
';
/*Code For Colors*/
$majesticsupport_css .= '
	div.mjtc-support-top-search-wrp{border:1px solid  '.$color5.';}
	div.mjtc-support-search-heading-wrp{background-color: '.$color4.';color: '.$color7.';}
	div.mjtc-support-search-heading-wrp div.mjtc-support-heading-right a.mjtc-support-add-download-btn{background: '.$color2.';color: '.$color7.';}
	div.mjtc-support-search-heading-wrp div.mjtc-support-heading-right a.mjtc-support-add-download-btn:hover{background:rgba(125, 135, 141, 0.4);color: '.$color7.';}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field input.mjtc-support-field-input{background-color: #fff;border:1px solid  '.$color5.';color: '.$color4.';}
	
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button{background: '.$color1.' !important;color: '.$color7.' !important;border:1px solid  '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button:hover{border-color:'.$color2.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button{background: '.$color2.';color: '.$color7.';border:1px solid  '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button:hover{border-color: '.$color1.';}
	
	div.mjtc-support-table-header{background-color:'.$color2.';color:'.$color7.'; border:1px solid '.$color5.';}
	div.mjtc-support-table-header div.mjtc-support-table-header-col{border-right:1px solid  '.$color5.';}
	div.mjtc-support-table-header div.mjtc-support-table-header-col:last-child{border-right:none;}
	div.mjtc-support-table-body div.mjtc-support-data-row{border:1px solid  '.$color5.';border-top:none}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col{border-right:1px solid  '.$color5.';}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col:last-child{border-right:none;}
	
	th.mjtc-support-table-th{border-right:1px solid  '.$color5.';}
	tbody.mjtc-support-table-tbody{border:1px solid  '.$color5.';}
	td.mjtc-support-table-td{border-right:1px solid  '.$color5.';}
	
	div.mjtc_supportattachment{background-color: '.$color3.';border:1px solid  '.$color5.';}
	div.mjtc-support-categories-heading-wrp{background-color: '.$color2.';border:1px solid  '.$color5.';color: '.$color7.';}
	div.mjtc-support-categories-content div.mjtc-support-category-box{background-color: #fff;border:1px solid  '.$color5.';}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-download-logo{border-bottom: 1px solid '.$color5.';}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title:hover{color: '.$color2.';}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-name {color: '.$color2.';}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-name:hover {color: '.$color1.';}
	div.mjtc-support-downloads-wrp div.mjtc-support-downloads-heading-wrp{background-color:'.$color2.';border:1px solid  '.$color5.';color: '.$color7.';}
	div.mjtc-support-downloads-content div.mjtc-support-download-box{background-color: #fff;border:1px solid  '.$color5.';}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title span.mjtc-support-download-name {color: '.$color4.';}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title span.mjtc-support-download-name:hover {color: '.$color2.';}
	div.mjtc-support-download-btn button.mjtc-support-download-btn-style{background-color: '.$color2.';}
	div.mjtc-support-download-btn a.mjtc-support-download-btn-style{background-color: '.$color2.'; color: '.$color7.';}
	div#mjtc-support-main-popup{background:  '.$color7.';}

';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
