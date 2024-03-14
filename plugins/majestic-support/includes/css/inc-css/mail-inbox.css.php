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

	div.mjtc-support-mail-wrapper{float: left;width: 100%;margin-top: 5px;}
	div.mjtc-support-mails-btn-wrp{float: left;width: 100%;margin-top: 20px;padding: 0px 5px; }
	div.mjtc-support-mails-btn-wrp div.mjtc-support-mail-btn{float: left;width:calc(100% / 3 - 10px);margin: 0px 5px;text-align: center;}
	div.mjtc-support-mails-btn-wrp div.mjtc-support-mail-btn a.mjtc-add-link{display: inline-block;float: left;width: 100%;padding: 15px;text-decoration: none;outline: 0;height: initial;}
	div.mjtc-support-margin-top{margin-top: 50px;}
	th:first-child, td:first-child{padding-left: 10px !important;}
	img.mjtc-support-mail-img{vertical-align: sub;}
	div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field select#to{background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 98% / 2% no-repeat;padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-top-search-wrp{float: left;width: 100%;}
	form#majesticsupportform{float: left;width: 100%;}
	div.mjtc-support-fields-wrp{float: left;width: 100%;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field{float: left; width: calc(100% / 2 - 10px);margin: 0px 5px;position: relative;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field-download-search{width:75%;margin: 0px;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field input.mjtc-support-field-input{float: left;width:calc(100% - 10px);margin-right:10px; border-radius: 0px; padding: 10px;line-height: initial;height: 50px;}
	select.mjtc-support-select-field{float: left;width: 100%;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / auto no-repeat #eee; padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-search-form-btn-wrp{float: left;width: 100%; padding: 0px 5px;margin-top: 10px;}
	div.mjtc-support-search-form-btn-wrp-download {width:25%;padding: 0px;margin-top: 0px;}
	div.mjtc-support-search-form-btn-wrp input{float: left;width: calc(100% / 2);padding: 17px 0px;text-align: center;border-radius: unset;line-height: initial;}
	div.mjtc-support-search-form-btn-wrp-download input.mjtc-search-button{float: left;width: calc(100% / 2 - 5px); padding: 13px 0px;text-align: center; margin: 0px 10px 0px 0px; border-radius: 0px; height: 50px;}
	div.mjtc-support-search-form-btn-wrp-download input.mjtc-reset-button{float: left;width: calc(100% / 2 - 5px); padding: 13px 0px;text-align: center; border-radius: 0px;height: 50px;}

	div.mjtc-support-download-content-wrp{float: left;width: 100%;margin-top: 30px;}
	div.mjtc-support-table-heading-wrp{float: left;width: 100%; padding: 10px;}
	div.mjtc-support-table-heading-wrp div.mjtc-support-table-heading-left{float: left;width: 70%;padding: 15px 10px;line-height: initial;}
	div.mjtc-support-table-heading-wrp div.mjtc-support-table-heading-right{float: left;width: 30%;text-align: right;}
	div.mjtc-support-table-heading-wrp div.mjtc-support-table-heading-right a.mjtc-support-table-add-btn{display: inline-block;padding: 15px 25px;text-decoration: none;outline: 0px;line-height: initial;}
	div.mjtc-support-table-heading-wrp div.mjtc-support-table-heading-right a.mjtc-support-table-add-btn span.mjtc-support-table-add-img-wrp{display: inline-block;margin-right: 5px;}
	div.mjtc-support-table-heading-wrp div.mjtc-support-table-heading-right a.mjtc-support-table-add-btn span.mjtc-support-table-add-img-wrp img{vertical-align: text-bottom;}
	div.mjtc-support-search-fields-wrp{float: left;width: 100%;padding: 10px;}
	div.mjtc-support-table-wrp{float: left;width: 100%;padding: 0;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header{float: left;width: 100%;margin-bottom: 15px;font-weight:bold;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header div.mjtc-support-table-header-col{padding: 15px;text-align: center;line-height: initial;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header div.mjtc-support-table-header-col:first-child{text-align: left;}
	div.mjtc-support-table-body{float: left;width: 100%;}
	div.mjtc-support-table-body div.mjtc-support-data-row{float: left;width: 100%;margin-bottom: 15px;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col{padding:20px 15px 10px 15px;text-align: center;line-height: initial;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col:first-child{text-align: left;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col:last-child{padding:17px;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col .mjtc-support-title-anchor {display: inline-block;text-decoration: none;height: 25px;width: 95%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;font-weight: bold;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col .mjtc-support-table-action-btn {padding: 4px 5px 8px;margin: 0 2px;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col .mjtc-support-table-action-btn img {display:inline-block;}
	span.mjtc-support-display-block{display: none;}
';
/*Code For Colors*/
$majesticsupport_css .= '
	div.mjtc-support-search-fields-wrp {background: '.$color3.';}
	div.mjtc-support-mails-btn-wrp div.mjtc-support-mail-btn a.mjtc-add-link{background-color: '.$color3.';border:1px solid  '.$color5.'; color: '.$color2.';}
	div.mjtc-support-mails-btn-wrp div.mjtc-support-mail-btn a.mjtc-add-link:hover{background-color: '.$color1.';border:1px solid  '.$color2.'; color: '.$color7.';}
	div.mjtc-support-mails-btn-wrp div.mjtc-support-mail-btn a.mjtc-add-link:hover img{ filter: invert(100%);}
	div.mjtc-support-mails-btn-wrp div.mjtc-support-mail-btn a.mjtc-add-link.active{background-color: '.$color1.' !important; border:1px solid  '.$color2.' !important; color: '.$color7.' !important;}
	div.mjtc-support-mails-btn-wrp div.mjtc-support-mail-btn a.mjtc-add-link.active img{filter: invert(100%) !important;}
	div.mjtc-support-mails-btn-wrp div.mjtc-support-mail-btn a.mjtc-add-link img{display:inline-block;}
	div.mjtc-support-top-search-wrp{border:1px solid '.$color5.';}
	div.mjtc-support-table-heading-wrp{background-color:'.$color4.';color:'.$color7.';}
	div.mjtc-support-table-heading-wrp div.mjtc-support-table-heading-right a.mjtc-support-table-add-btn{background:'.$color2.';color:'.$color7.';}
	div.mjtc-support-table-heading-wrp div.mjtc-support-table-heading-right a.mjtc-support-table-add-btn:hover{background:rgba(125, 135, 141, 0.4);color:'.$color7.';}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field input.mjtc-support-field-input{background-color:#fff;border:1px solid '.$color5.';color: '.$color4.';}
	select.mjtc-support-select-field{background-color:'.$color3.' !important;border:1px solid '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button{background: '.$color1.' !important;color:'.$color7.' !important;border: 1px solid '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button:hover{border-color: '.$color2.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button{background: '.$color2.';color:'.$color7.';border: 1px solid '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button:hover{border-color: '.$color1.';}
	div.mjtc-support-table-header{background-color:'.$color2.';color:'.$color7.'; border:1px solid '.$color5.';}
	div.mjtc-support-table-header div.mjtc-support-table-header-col{color: '.$color7.';}
	div.mjtc-support-table-header div.mjtc-support-table-header-col:last-child{}
	div.mjtc-support-table-body div.mjtc-support-data-row{border:1px solid '.$color5.';}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col{}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col:last-child{}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col .mjtc-support-table-action-btn {border: 1px solid '.$color5.';background: #fff;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col .mjtc-support-table-action-btn:hover {border-color: '.$color2.';}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col .mjtc-support-title-anchor {color: '.$color2.';}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col .mjtc-support-title-anchor:hover {color: '.$color1.';}
	div.mjtc-support-download-content-wrp div.mjtc-support-table-body div.mjtc-support-data-row {border:1px solid'.$color5.';}
	th.mjtc-support-table-th{border-right:1px solid '.$color5.';}
	tbody.mjtc-support-table-tbody{border:1px solid '.$color5.';}
	td.mjtc-support-table-td{border-right:1px solid '.$color5.';}

';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
