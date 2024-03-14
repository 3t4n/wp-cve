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
/* Annoucement */
	
	div.mjtc-support-announcement-wrapper{float: left;width: 100%;margin-top: 5px;}
	div.mjtc-support-top-search-wrp{float: left;width: 100%;}
	div.mjtc-support-search-fields-wrp{float: left;width: 100%;padding: 10px 5px;}
	
	form#majesticsupportform{float: left;width: 100%;}
	
	div.mjtc-support-fields-wrp{float: left;width: 75%;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field{float: left; width: calc(100% / 2 - 10px);margin: 0px 5px;position: relative;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field-download-search{width:75%;margin: 0px;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field input.mjtc-support-field-input{float: left;width: 100%;border-radius: 0px; padding: 10px;line-height: initial;height: 50px;}
	select.mjtc-support-select-field{float: left;width: 100%;border-radius: 0px;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / auto no-repeat #eee; padding: 10px;line-height: initial;height: 50px;}
	
	div.mjtc-support-search-form-btn-wrp{float: left;width: 25%; padding: 0px 5px;}
	div.mjtc-support-search-form-btn-wrp-download {width:25%;padding: 0px;margin-top: 0px;}
	div.mjtc-support-search-form-btn-wrp input{float: left;width: calc(100% / 2);padding: 13px 0px;text-align: center;border-radius: unset;line-height: initial;height: 50px;}
	div.mjtc-support-search-form-btn-wrp-download input.mjtc-search-button{float: left;width: calc(100% / 2 - 10px); padding: 13px 0px;text-align: center;margin: 0px 0px 0px 10px; border-radius: 0px; height: 50px;}
	div.mjtc-support-search-form-btn-wrp-download input.mjtc-reset-button{float: left;width: calc(100% / 2 - 10px); padding: 13px 0px;text-align: center; margin: 0px 0px 0px 10px; border-radius: 0px;height: 50px;}
	
	div.mjtc-support-download-content-wrp{float: left;width: 100%;margin-top: 30px;}
	div.mjtc-support-table-heading-wrp{float: left;width: 100%; padding: 10px;}
	div.mjtc-support-table-heading-wrp div.mjtc-support-table-heading-left{float: left;width: 70%;padding: 15px 10px;line-height: initial;}
	div.mjtc-support-table-heading-wrp div.mjtc-support-table-heading-right{float: left;width: 30%;text-align: right;}
	div.mjtc-support-table-heading-wrp div.mjtc-support-table-heading-right a.mjtc-support-table-add-btn{display: inline-block;padding: 15px 25px;text-decoration: none;outline: 0px;line-height: initial;}
	div.mjtc-support-table-heading-wrp div.mjtc-support-table-heading-right a.mjtc-support-table-add-btn span.mjtc-support-table-add-img-wrp{display: inline-block;margin-right: 5px;}
	div.mjtc-support-table-heading-wrp div.mjtc-support-table-heading-right a.mjtc-support-table-add-btn span.mjtc-support-table-add-img-wrp img{vertical-align: text-bottom;}
	
	div.mjtc-support-table-wrp{float: left;width: 100%;padding: 0;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header{float: left;width: 100%;margin-bottom: 15px;font-weight:bold;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header div.mjtc-support-table-header-col{padding: 15px;text-align: center;line-height: initial;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header div.mjtc-support-table-header-col:first-child{text-align: left;}
	div.mjtc-support-table-body{float: left;width: 100%;}
	div.mjtc-support-table-body div.mjtc-support-data-row{float: left;width: 100%;margin-bottom: 15px;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col{padding:20px 15px 10px 15px;text-align: center;line-height: initial;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col:first-child{text-align: left;padding-left: 10px;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col .mjtc-support-title-anchor {display: inline-block;text-decoration: none;height: 25px;width: 95%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;font-weight: bold;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col .mjtc-support-table-action-btn {padding: 4px 5px 8px;margin: 0 2px;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col .mjtc-support-table-action-btn img {display:inline-block;}
	span.mjtc-support-display-block{display: none;}
	
	div.mjtc-support-attached-files-wrp{float: left;width: calc(100% / 2 - 10px);margin: 0px 5px;margin-top: 15px;} 
	div.mjtc_supportattachment{float: left;width: 70%;padding: 10px 5px;}
	a.mjtc-support-delete-attachment{display:inline-block;float: left;width: 30%;padding: 11px 5px;text-align: center;text-decoration: none;outline: 0px;}
	span.help-block{font-size: 14px;}
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button{margin-right:10px;width:calc(100% / 2 - 5px);}
	div.mjtc-support-categories-wrp{float: left;width: 100%;margin-top: 25px;}
	div.mjtc-support-margin-bottom{margin-bottom: 20px;margin-top: 10px;}
	div.mjtc-support-categories-heading-wrp{float: left;width: 100%;padding: 15px 10px;font-weight:bold;}
	div.mjtc-support-categories-wrp div.mjtc-support-position-relative{position: relative;}
	div.mjtc-support-categories-content{float: left;width: 100%;padding: 20px 0px 0px;}
	div.mjtc-support-categories-content div.mjtc-support-category-box{float: left;width:calc(100% / 3 - 10px);margin: 0px 5px;margin-bottom: 10px;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title{display: inline-block;text-decoration: none;outline: 0px;width: 100%;padding: 0px 5px;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-name{display: inline-block;padding: 13px 0px;text-align: center;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-download-logo{display: inline-block;float: right;padding: 5px;width: 30px;height: 30px;text-align: center;margin: 10px 10px;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-download-logo img.mjtc-support-download-img{vertical-align: unset;}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-kb-logo{display: inline-block;float: left;padding:2px;width: 50px;height: 50px;position: relative;margin: 0px 5px 0px 0px; }
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-kb-logo img.mjtc-support-kb-img{position: absolute;top: 0px;left: 0px;right: 0px;bottom: 0px;margin:auto;max-width: 80%;width: auto;}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button{width:calc(100% / 2 - 5px);}
	div.mjtc-support-downloads-wrp{float: left;width: 100%;margin-top: 18px;}
	div.mjtc-support-downloads-wrp div.mjtc-support-downloads-heading-wrp{float: left;width: 100%;padding: 15px 10px;font-weight:bold;}
	div.mjtc-support-downloads-content{float: left;width: 100%;padding: 20px 0px;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box{float: left;width: 100%;padding: 8px 0px;box-shadow: 0 8px 6px -6px #dedddd; margin-bottom: 10px;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left{float: left;width: 100%;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title{float: left;width: 100%;padding: 9px; cursor: pointer;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title img.mjtc-support-download-icon{float: left;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title span.mjtc-support-download-name{width: calc(100% - 60px); display: inline-block;padding: 10px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-right{float: left;width: 20%;}
	div.mjtc-support-download-btn{float: left;width: 100%;text-align: center;}
	div.mjtc-support-download-btn button.mjtc-support-download-btn-style{display: inline-block;padding: 9px 20px;border-radius: unset;font-weight: unset;}
	div.mjtc-support-download-btn a.mjtc-support-download-btn-style{display: inline-block;padding: 9px 20px;border-radius: unset;font-weight: unset;text-decoration: none;outline: 0;}
	div.mjtc-support-download-btn button.mjtc-support-download-btn-style img.mjtc-support-download-btn-icon{vertical-align: text-top;margin-right: 5px;}
	div.mjtc-support-download-btn a.mjtc-support-download-btn-style img.mjtc-support-download-btn-icon{vertical-align: text-top;margin-right: 5px;}
	
	div#mjtc-support-main-black-background{position: fixed;width: 100%;height: 100%;background: rgba(0,0,0,0.7);z-index: 999;top:0px;left:0px;}
	div#mjtc-support-main-popup{position: fixed;top:30%;left:20%;width:60%;height: 40%;padding-top:0px;z-index: 99999;overflow-y: auto; overflow-x: hidden;}
	span#mjtc-support-popup-close-button{position: absolute;top:22px;right: 21px;width:20px;height: 20px;}
	span#mjtc-support-popup-close-button:hover{cursor: pointer;}
	span#mjtc-support-popup-title{width:100%;display: inline-block;padding: 20px 15px;font-size: 20px;}
	div#mjtc-support-popup-head{width: 100%;padding-top: 5px; padding-bottom: 5px;}
	div.mjtc-support-popup-row-downloadall-button{text-align: center;display: inline-block; padding: 5px 0px;width: 140px;}
	div.mjtc-support-popup-desctiption{padding: 5px 15px; font-size: 12px;}
	div.mjtc-support-popup-download-row{padding: 5px; margin: 5px 0px;display: inline-block;width: 100%;}
	div.mjtc-support-popup-download-name{display: inline-block; padding: 0px;}
	div#mjtc-support-main-content{float: left;width: 100%;padding: 0px 25px;}
	div#mjtc-support-main-downloadallbtn{float: left;width: 100%;padding: 0px 25px 20px;}
	div.mjtc-support-download-description{float: left;width: 100%;padding: 0px 0px 15px;}

	select ::-ms-expand {display:none !important;}
	select{-webkit-appearance:none !important;}
';
/*Code For Colors*/
$majesticsupport_css .= '

	div.mjtc-support-top-search-wrp{border:1px solid  '.$color5.';}
	div.mjtc-support-search-fields-wrp {background: '.$color3.';}
	select.mjtc-support-select-field{background-color: #fff !important;border:1px solid  '.$color5.';color: '.$color4.';}
	select#departmentid{background-color: '.$color3.';border:1px solid  '.$color5.';}
	div.mjtc-support-premade-msg-wrp div.mjtc-support-premade-field-wrp select#departmentid{background-color: '.$color3.';border:1px solid  '.$color5.';}
	div.mjtc-support-premade-msg-wrp div.mjtc-support-premade-field-wrp select#staffid{background-color: '.$color3.';border:1px solid  '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button{background: '.$color1.' !important;color: '.$color7.' !important;border:1px solid  '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button:hover {border-color: '.$color2.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button{background:  '.$color2.';color: '.$color7.';border:1px solid  '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button:hover {border-color: '.$color1.';}
	div.mjtc-support-table-heading-wrp{background-color: '.$color3.';color: '.$color2.';}
	div.mjtc-support-table-heading-wrp div.mjtc-support-table-heading-right a.mjtc-support-table-add-btn{background: '.$color2.';color: '.$color7.';border:1px solid  '.$color5.';}
	div.mjtc-support-table-heading-wrp div.mjtc-support-table-heading-right a.mjtc-support-table-add-btn:hover{border-color: '.$color1.';}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field input.mjtc-support-field-input{background-color: #fff;border:1px solid  '.$color5.';color: '.$color4.';}
	div.mjtc-support-table-header{background-color:'.$color2.';color:'.$color7.'; border:1px solid '.$color5.';}
	div.mjtc-support-table-header div.mjtc-support-table-header-col{color: '.$color7.';}
	div.mjtc-support-table-header div.mjtc-support-table-header-col:last-child{}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col .mjtc-support-title-anchor{color: '.$color2.';}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col .mjtc-support-title-anchor:hover {color: '.$color1.';}
	div.mjtc-support-table-body div.mjtc-support-data-row{border:1px solid  '.$color5.';}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col{color: '.$color4.';}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col:nth-child(3n){padding:12px;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col:last-child{padding:17px;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col .mjtc-support-table-action-btn {border: 1px solid '.$color5.';background: #fff;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col .mjtc-support-table-action-btn:hover {border-color: '.$color1.';}
	div.mjtc-support-announcement-wrapper div.mjtc-support-table-body div.mjtc-support-data-row {border:1px solid'.$color5.';}
	th.mjtc-support-table-th{border-right:1px solid  '.$color5.';}
	tbody.mjtc-support-table-tbody{border:1px solid  '.$color5.';}
	td.mjtc-support-table-td{border-right:1px solid  '.$color5.';}
	div.mjtc_supportattachment{background-color: '.$color3.';border:1px solid  '.$color5.';}
	div.mjtc-support-categories-heading-wrp{background-color:'.$color6.';border:1px solid  '.$color5.';}
	div.mjtc-support-categories-content div.mjtc-support-category-box{background-color: '.$color3.';border:1px solid  '.$color5.';}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title span.mjtc-support-category-download-logo{background: '.$color2.';}
	div.mjtc-support-downloads-wrp div.mjtc-support-downloads-heading-wrp{background-color:'.$color6.';border:1px solid  '.$color5.';}
	div.mjtc-support-downloads-content div.mjtc-support-download-box{background-color: '.$color3.';border:1px solid  '.$color5.';}
	div.mjtc-support-download-btn button.mjtc-support-download-btn-style{background-color: '.$color2.';}
	div.mjtc-support-download-btn a.mjtc-support-download-btn-style{background-color: '.$color2.'; color: '.$color7.';}
	div#mjtc-support-main-popup{background:  '.$color7.';}
	span#mjtc-support-popup-title{background-color: '.$color2.';color: '.$color7.';}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title:hover{color: '.$color2.';}
	div.mjtc-support-categories-content div.mjtc-support-category-box a.mjtc-support-category-title:hover{color: '.$color2.';}';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
