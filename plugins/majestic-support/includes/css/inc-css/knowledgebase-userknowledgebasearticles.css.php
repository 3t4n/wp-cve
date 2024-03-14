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
	div.mjtc-support-categories-wrp{float: left;width: 100%;margin-top: 20px;}
	div.mjtc-support-margin-bottom{margin-bottom: 20px;margin-top: 10px;}
	div.mjtc-support-categories-heading-wrp{float: left;font-weight:bold;width: 100%;padding: 15px;}
	div.mjtc-support-categories-wrp div.mjtc-support-position-relative{position: relative;}
	div.mjtc-support-head-category-image{display: inline-block;width: 60px;}
	img.mjtc-support-kb-dtl-img{max-width:100%}
	span.mjtc-support-head-text{display: inline-block;margin-left: 8px;}
	div.mjtc-support-knowledgebase-wrapper{float: left;width:100%;margin-top: 0px;}
	div.mjtc-support-knowledgebase-details{float: left;width: 100%;padding: 15px;}
	div.mjtc-support-categories-content{float: left;width: 100%;padding: 20px 0px 0px;}
	div.mjtc-support-categories-content div.mjtc-support-category-box{float: left;width:calc(100% / 3 - 10px);margin: 0px 5px;margin-bottom: 10px;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title{display: inline-block;text-decoration: none;outline: 0px;width: 100%;padding: 0px 5px;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-name{display: inline-block;padding: 13px 0px;text-align: center;line-height: initial;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-download-logo{display: inline-block;float: right;padding: 5px;width: 30px;height: 30px;text-align: center;margin: 10px 10px;position:relative;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-download-logo img.mjtc-support-arrow-icon{max-width: 100px%;margin: auto;position: absolute;right: 0;left: 0;top: 0;bottom: 0;}

	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-download-logo img.mjtc-support-download-img{vertical-align: unset;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-kb-logo{display: inline-block;float: left;padding:2px;width: 50px;height: 50px;position: relative;margin: 0px 5px 0px 0px; }
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-kb-logo img.mjtc-support-kb-img{position: absolute;top: 0px;left: 0px;right: 0px;bottom: 0px;margin:auto;max-width: 80%;width: auto;}

	div.mjtc-support-downloads-wrp{float: left;width: 100%;margin-top: 18px;}
	div.mjtc-support-downloads-wrp div.mjtc-support-downloads-heading-wrp{float: left;font-weight:bold; width: 100%;padding: 15px;line-height: initial;}
	div.mjtc-support-downloads-content{float: left;width: 100%;padding: 20px 0px;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box{float: left;width: 100%;padding: 8px 0px;box-shadow: 0 8px 6px -6px #dedddd; margin-bottom: 10px;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left{float: left;width: 100%;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title{float: left;width: 100%;padding: 9px; cursor: pointer;line-height: initial;}
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
	div.mjtc-support-fields-wrp div.mjtc-support-form-field input.mjtc-support-field-input{background-color: '.$color3.';border:1px solid  '.$color5.';}
	select.mjtc-support-select-field{background-color: '.$color3.' !important;border:1px solid  '.$color5.';}
	select#departmentid{background-color: '.$color3.';border:1px solid  '.$color5.';}
	div.mjtc-support-premade-msg-wrp div.mjtc-support-premade-field-wrp select#departmentid{background-color: '.$color3.';border:1px solid  '.$color5.';}
	div.mjtc-support-premade-msg-wrp div.mjtc-support-premade-field-wrp select#staffid{background-color: '.$color3.';border:1px solid  '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button{background: '.$color2.' !important;color: '.$color7.' !important;}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button{background: #606062;color: '.$color7.';}
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
	div.mjtc-support-categories-heading-wrp{border:1px solid  '.$color5.';color: '.$color2.';background-color: '.$color3.';}
	div.mjtc-support-categories-content div.mjtc-support-category-box{background-color: '.$color3.';border:1px solid  '.$color5.';}
	.mjtc-support-kb-dtl-img{width:46px;height:46px;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-download-logo{background: '.$color2.';}
	div.mjtc-support-downloads-wrp div.mjtc-support-downloads-heading-wrp{background-color:'.$color2.';border:1px solid  '.$color5.';color: '.$color7.';}
	div.mjtc-support-downloads-content div.mjtc-support-download-box{background-color: #fff;border:1px solid  '.$color5.';}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title span.mjtc-support-download-name {color: '.$color4.';}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title span.mjtc-support-download-name:hover {color: '.$color2.';}
	div.mjtc-support-download-btn button.mjtc-support-download-btn-style{background-color: '.$color2.';}
	div.mjtc-support-download-btn a.mjtc-support-download-btn-style{background-color: '.$color2.'; color: '.$color7.';}
	div#mjtc-support-main-popup{background:  '.$color7.';}
	span#mjtc-support-popup-title{background-color: '.$color2.';color: '.$color7.';}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title:hover{color: '.$color2.';}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title:hover{color: '.$color2.';}

';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
