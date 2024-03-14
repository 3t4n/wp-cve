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
	div.mjtc-support-search-heading-wrp{float: left;width: 100%;}
	div.mjtc-support-search-heading-wrp div.mjtc-support-heading-left{float: left;width: 100%;line-height: initial;font-size: 26px;font-weight: 600;text-transform: capitalize;}
	div.mjtc-support-knowledgebase-details{float: left;width: 100%;padding: 15px 0;line-height: 1.8;}
	div.mjtc-support-knowledgebase-details p {margin: 0;}
	div.mjtc-support-categories-wrp{float: left;width: 100%;margin-top: 25px;}
	div.mjtc-support-categories-heading-wrp{float: left;font-weight:bold;width: 100%;padding: 15px;line-height: initial;}
	div.mjtc-support-margin-bottom{margin-bottom: 20px;margin-top: 10px;}

	div.mjtc-support-downloads-wrp{float: left;width: 100%;padding: 10px;}
	div.mjtc-support-downloads-wrp div.mjtc-support-downloads-heading-wrp{float: left;width: 100%;padding: 15px;line-height: initial;font-weight:bold;}
	div.mjtc-support-downloads-content{float: left;width: 100%;padding: 20px 0px;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box{float: left;width: 100%;padding: 10px;box-shadow: 0 8px 6px -6px #dedddd; margin-bottom: 10px;line-height: initial;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left{float: left;width: 80%;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title{float: left;width: 100%;padding: 9px; cursor: pointer;text-decoration: none;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title img.mjtc-support-download-icon{float: left;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title span.mjtc-support-download-name{width: calc(100% - 60px); display: inline-block;padding: 10px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-right{float: left;width: 20%;}
	div.mjtc-support-download-btn{float: left;width: 100%;text-align: right;padding: 7px 10px;}
	div.mjtc-support-download-btn button.mjtc-support-download-btn-style{display: inline-block;padding: 9px 20px;border-radius: unset;font-weight: unset;}
	div.mjtc-support-download-btn a.mjtc-support-download-btn-style{display: inline-block;padding: 10px 20px;border-radius: unset;font-weight: unset;text-decoration: none;outline: 0;line-height: initial;}
	div.mjtc-support-download-btn button.mjtc-support-download-btn-style img.mjtc-support-download-btn-icon{vertical-align: text-top;margin-right: 5px;}
	div.mjtc-support-download-btn a.mjtc-support-download-btn-style img.mjtc-support-download-btn-icon{vertical-align: text-top;margin-right: 5px;}
';
/*Code For Colors*/
$majesticsupport_css .= '
	div.mjtc-support-top-search-wrp{}
	div.mjtc-support-search-heading-wrp{color: '.$color2.';}
	div.mjtc-support-knowledgebase-details{color: '.$color4.';}
	div.mjtc-support-categories-heading-wrp{background-color:'.$color3.';border:1px solid  '.$color5.';color:'.$color2.';}
	div.mjtc-support-downloads-wrp div.mjtc-support-downloads-heading-wrp{background-color:'.$color2.';border:1px solid  '.$color5.';color: '.$color7.';}
	div.mjtc-support-downloads-content div.mjtc-support-download-box{background-color: #fff;border:1px solid  '.$color5.';}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title span.mjtc-support-download-name {color: '.$color4.';}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title span.mjtc-support-download-name:hover {color: '.$color2.';}
	div.mjtc-support-download-btn button.mjtc-support-download-btn-style{background-color: '.$color2.';}
	div.mjtc-support-download-btn a.mjtc-support-download-btn-style{color: '.$color1.'; background-color: '.$color7.';border:1px solid  '.$color1.';}
	div.mjtc-support-download-btn a.mjtc-support-download-btn-style:hover{color: '.$color2.';border-color:'.$color2.';}



';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
