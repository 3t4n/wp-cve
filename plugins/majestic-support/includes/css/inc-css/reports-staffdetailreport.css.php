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
	/* Staff Report */
	div.mjtc-support-staff-report-wrapper{float: left;width: 100%;margin: 5px 0 0 !important;}
	div.mjtc-support-top-search-wrp{float: left;width: 100%;}
	div.mjtc-support-search-heading-wrp{float: left;width: 100%; padding: 10px 10px 10px 0px;}
	div.mjtc-support-search-heading-wrp div.mjtc-support-heading-left{float: left;width: 70%;padding: 10px;}
	div.mjtc-support-search-fields-wrp{float: left;width: 100%;padding: 10px;}
	form#majesticsupportform{float: left;width: 100%;}
	div.mjtc-support-fields-wrp{float: left;width: 75%;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field{float: left; width: calc(100% / 2 - 10px);margin: 0px 5px;position: relative;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field-download-search{width:75%;margin: 0px;}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field input.mjtc-support-field-input{float: left;width: 100%;border-radius: 0px; padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-search-form-btn-wrp{float: left;width: 25%; padding: 0px 5px;}
	div.mjtc-support-search-form-btn-wrp-download {width:25%;padding: 0px;margin-top: 0px;}
	div.mjtc-support-search-form-btn-wrp input{float: left;width: calc(100% / 2 - 10px);padding: 13px 0px;text-align: center;margin-right: 10px; border-radius: unset;line-height: initial;height: 50px;}
	div.mjtc-support-downloads-wrp{float: left;width: 100%;margin-top: 18px;}
	div.mjtc-support-downloads-wrp div.mjtc-support-downloads-heading-wrp{float: left;font-weight:bold;width: 100%;padding: 15px 10px;}
	input#ms-date-start{background-image: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/calender.png);background-repeat: no-repeat;background-position: 96% 13px;background-size: 20px;}
	input#ms-date-end{background-image: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/calender.png);background-repeat: no-repeat;background-position: 96% 13px;background-size: 20px;}
	div.mjtc-admin-report-box-wrapper{float:left;width:100%;margin-top:20px;margin-bottom: 10px;}
	div.mjtc-support-download-content-wrp-mtop{margin-top: 30px;}
	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box{background:#ffffff;border:1px solid '.$color5.';padding:0px;width: calc(100% / 5 - 5px);margin: 0px 2.5px; }
	div.mjtc-admin-report-box-wrapper.mjtc-admin-controlpanel div.mjtc-admin-box{margin-right: 0px;}
	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box.mjtc-col-md-offset-2{margin-left:11%;}
	div.mjtc-admin-report-box-wrapper.mjtc-admin-controlpanel div.mjtc-admin-box.mjtc-col-md-offset-2{margin-left:0px;}
	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box div.mjtc-admin-box-image{padding:5px;}
	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box div.mjtc-admin-box-image img{max-width: 100%;max-height: 100%;}
	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box div.mjtc-admin-box-content{padding:5px;}
	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box div.mjtc-admin-box-content div.mjtc-admin-box-content-number{text-align: right;font-size:24px;font-weight: bold;}
	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box div.mjtc-admin-box-content div.mjtc-admin-box-content-label{text-align: right;font-size:12px;padding:0px;margin-top:5px;color:'.$color2.';white-space: nowrap;overflow: hidden;text-overflow:ellipsis;}
	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box.box1 div.mjtc-admin-box-content div.mjtc-admin-box-content-number{color:#159667;}
	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box.box2 div.mjtc-admin-box-content div.mjtc-admin-box-content-number{color:#2168A2;}
	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box.box3 div.mjtc-admin-box-content div.mjtc-admin-box-content-number{color:#f39f10;}
	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box.box4 div.mjtc-admin-box-content div.mjtc-admin-box-content-number{color:#B82B2B;}
	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box.box5 div.mjtc-admin-box-content div.mjtc-admin-box-content-number{color:#3D355A;}

	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box.box1 div.mjtc-admin-box-label{height:20px;background:#159667;}
	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box.box2 div.mjtc-admin-box-label{height:20px;background:#2168A2;}
	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box.box3 div.mjtc-admin-box-label{height:20px;background:#f39f10;}
	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box.box4 div.mjtc-admin-box-label{height:20px;background:#B82B2B;}
	div.mjtc-admin-report-box-wrapper div.mjtc-admin-box.box5 div.mjtc-admin-box-label{height:20px;background:#3D355A;}

	a.mjtc-admin-report-wrapper{float:left;display: block;width:95%;font-size:18px;}
	a.mjtc-admin-report-wrapper:hover{text-decoration: none;}
	a.mjtc-admin-report-wrapper div.mjtc-admin-overall-report-type-wrapper{box-shadow: 0px 0px 10px #aaaaaa;border-bottom:8px solid #6AA108;color:#6AA108;margin:10px 0px;background:url('.esc_url(MJTC_PLUGIN_URL).'includes/images/report/overall.png)  98% center no-repeat #EAF1DD;}
	a.mjtc-admin-report-wrapper div.mjtc-admin-staff-report-type-wrapper{box-shadow: 0px 0px 10px #aaaaaa;border-bottom:8px solid #159667;color:#159667;margin:10px 0px;background:url('.esc_url(MJTC_PLUGIN_URL).'includes/images/report/staffbox.png)  98% center no-repeat #EEF9FD;}
	a.mjtc-admin-report-wrapper div.mjtc-admin-user-report-type-wrapper{box-shadow: 0px 0px 10px #aaaaaa;border-bottom:8px solid #f39f10;color:#f39f10;margin:10px 0px;background:url('.esc_url(MJTC_PLUGIN_URL).'includes/images/report/userbox.png)  98% center no-repeat #FFF5EB;}
	div.mjtc-admin-staff-wrapper{display: inline-block;width:100%;background:#ffffff;margin-top:20px;border:1px solid '.$color5.';}
	div.mjtc-admin-staff-wrapper.mjtc-departmentlist{padding: 10px;}
	div.mjtc-admin-staff-wrapper.mjtc-departmentlist div.departmentname{font-size: 20px;}
	div.mjtc-admin-staff-wrapper.mjtc-departmentlist div.msposition-reletive{padding-top: 30px;}
	div.mjtc-admin-staff-wrapper.mjtc-departmentlist div.msposition-reletive div.departmentname{}
	div.mjtc-admin-staff-wrapper.padding{padding:10px;}
	div.mjtc-admin-staff-wrapper .nopadding{padding:0px;float: left;width: 40%;}
	div.mjtc-admin-staff-wrapper .mjtc-festaffreport-data {float: left;width: 60%;}
	div.mjtc-admin-staff-wrapper div.mjtc-report-staff-image-wrapper{height: 100px;width: 100px;text-align: center;line-height: 90px;float: left;border-radius: 100%;}
	div.mjtc-admin-staff-wrapper div.mjtc-report-staff-image-wrapper img.mjtc-report-staff-pic{width:80px;height:80px;margin:0 auto;display: inline-block;border-radius: 100%;}
	div.mjtc-admin-staff-wrapper div.mjtc-report-staff-name{display: block;padding:3px 0px 0;font-weight: bold;color:'.$color1.';margin-bottom:5px;}
	div.mjtc-admin-staff-wrapper div.mjtc-report-staff-cnt {float: left;width:calc(100% - 100px);padding: 6px 0 0 10px;}
	div.mjtc-admin-staff-wrapper div.mjtc-departmentname{font-weight: bold;font-size: 18px;color: '.$color4.'; margin: 15px 0px;}
	div.mjtc-admin-staff-wrapper div.mjtc-report-staff-username{display: block;padding:3px 0px;font-size: 14px;color: '.$color4.';}
	div.mjtc-admin-staff-wrapper div.mjtc-report-staff-email{display: block;padding:3px 0px;font-size: 14px;color: '.$color4.';}
	div.mjtc-admin-staff-wrapper div.mjtc-admin-report-box{background: #fff;border:1px solid '.$color5.';margin-left:8px;padding:0px;padding-top:10px;}
	div.mjtc-admin-staff-wrapper div.mjtc-admin-report-box span.mjtc-report-box-number{color:'.$color2.';display: block;font-size:22px;font-weight: bold;text-align: center;margin:5px 0px 10px 0px;}
	div.mjtc-admin-staff-wrapper div.mjtc-admin-report-box span.mjtc-report-box-title{color:'.$color4.';display: block;font-size:12px;text-align: center;padding:5px 4px 10px 4px;white-space: nowrap;text-overflow:ellipsis;overflow: hidden;}
	div.mjtc-admin-staff-wrapper div.mjtc-admin-report-box.box1{margin-left:10.4%;}
	div.mjtc-admin-staff-wrapper div.mjtc-admin-report-box.box1 div.mjtc-report-box-color{height:5px;background:#159667;}
	div.mjtc-admin-staff-wrapper div.mjtc-admin-report-box.box2 div.mjtc-report-box-color{height:5px;background:#2168A2;}
	div.mjtc-admin-staff-wrapper div.mjtc-admin-report-box.box3 div.mjtc-report-box-color{height:5px;background:#f39f10;}
	div.mjtc-admin-staff-wrapper div.mjtc-admin-report-box.box4 div.mjtc-report-box-color{height:5px;background:#B82B2B;}
	div.mjtc-admin-staff-wrapper div.mjtc-admin-report-box.box5 div.mjtc-report-box-color{height:5px;background:#3D355A;}
	a.mjtc-admin-staff-anchor-wrapper{display: inline-block;width: 100%;padding:10px;float:left;}
	table.mjtc-admin-report-tickets{width:100%;}
	table.mjtc-admin-report-tickets tr th{background:#cccccc;color:#333333;padding:8px;font-size:18px;}
	table.mjtc-admin-report-tickets tr td.overflow{white-space: nowrap;overflow: hidden;text-overflow:ellipsis;text-align: left;}
	table.mjtc-admin-report-tickets tr td{text-align: center;background:#FFFFFF;padding:8px;}
	table.mjtc-admin-report-tickets tr td span.mjtc-responsive-heading{display:none;}
	a.mjtc-admin-report-butonright{float:right;}
	div#mjtc-admin-ticketviaemail-bar{display: none;float:left;height:25px;width:35%;background:url('.esc_url(MJTC_PLUGIN_URL).'includes/images/progress_bar.gif);background-size: 100% 100%;margin-left:20px;margin-top:5px;}
	div#mjtc-admin-ticketviaemail-text{display:none;padding:10px 0px;}
	a#mjtc-admin-ticketviaemail{display: block;float:left;border:1px solid #666555;padding:8px 15px 8px 40px;background:url('.esc_url(MJTC_PLUGIN_URL).'includes/images/button_ticketviaemail.png);background-size:100% 100%;color:#ffffff;font-weight: bold;border-radius: 4px;text-decoration: none;position: relative;}
	a#mjtc-admin-ticketviaemail img{position: absolute;top:3px;left:5px;}
	div#mjtc-admin-ticketviaemail-msg{padding:10px;display:inline-block;float:none;margin-top:5px;border-radius: 4px;margin-bottom:10px;}
	div#mjtc-admin-ticketviaemail-msg.server-error{background:#FEEFB3;color:#B98324;border:1px solid #B98324;}
	div#mjtc-admin-ticketviaemail-msg.imap-error{background:#FEEFB3;color:#B98324;border:1px solid #B98324;}
	div#mjtc-admin-ticketviaemail-msg.email-error{background:#FEEFB3;color:#B98324;border:1px solid #B98324;}
	div#mjtc-admin-ticketviaemail-msg.no-error{background:#DFF2BF;color:#387B00;border:1px solid #387B00;}
	div.mjtc-admin-ticketviaemail-wrapper-checksetting{margin-top:10px;}
	span.mjtc-relative{position: relative;}
	span.mjtc-relative img.mjtc-relative-image{position: absolute;top:50px;right:0px;}
	div#tabs.tabs{float: left; width:100%}
	div.mjtc-form-wrapper div.mjtc-form-wrapper {padding-left: 2%;}
	div.mjtc-form-wrapper div.mjtc-form-value.mjtc-assingtome-chkbox{padding: 6px 15px;}
	div.mjtc-form-wrapper div.mjtc-form-value.mjtc-assingtome-chkbox label#forassigntome{padding-left: 4px;}
	div#pie3d_chart1{}
	div#no_message{background: #f6f6f6 none repeat scroll 0 0; border: 1px solid #d4d4d5; color: #723776; display: inline-block; font-size: 15px; left: 50%; min-width: 80%; padding: 15px 20px; position: absolute; text-align: center; top: 50%; transform: translate(-50%, -50%); }
	div#records div.ms_userpages{text-align: right;padding:5px; margin: 10px 5px;width: calc(100% - 10px);}
	div#records div.ms_userpages a.ms_userlink{display: inline-block;padding:5px 10px;margin-left:5px;text-decoration: none;background:rgba(0, 0, 0, 0.05) none repeat scroll 0 0;}
	div#records div.ms_userpages span.ms_userlink{display: inline-block;padding:5px 15px;margin-left:5px;}
	form div.mjtc-form-wrapper div.mjtc-form-value input#sendmail2.radiobutton{margin-left: 15px;}
	h1.mjtc-department-margin{padding-top: 15px;}
	.leftrightnull{padding-left: 0px; padding-right: 0px;}
	div.mjtc-support-download-content-wrp{float: left;width: 100%;margin-top: 30px;}
	div.mjtc-support-table-wrp{float: left;width: 100%;padding: 0;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header{float: left;width: 100%;font-weight:bold;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header div.mjtc-support-table-header-col{padding: 18px 0px;text-align: center;}
	div.mjtc-support-table-wrp div.mjtc-support-table-header div.mjtc-support-table-header-col:first-child{text-align: left;padding-left: 10px;}
	div.mjtc-support-table-body{float: left;width: 100%;}
	div.mjtc-support-table-body div.mjtc-support-data-row{float: left;width: 100%;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col{padding: 18px 0px;text-align: center;min-height:50px;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col:first-child{text-align: left;padding-left: 10px;}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col:last-child{padding: 13px 5px; }
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col span.mjtc-support-priority {display: inline-block;padding: 7px 12px;min-width: 90px;line-height: initial;}
	span.mjtc-support-display-block{display: none;}
';
/*Code For Colors*/
$majesticsupport_css .= '
	div.mjtc-support-search-fields-wrp {background: '.$color3.';}
	div.mjtc-support-top-search-wrp{border:1px solid '.$color5.';}
	div.mjtc-support-search-heading-wrp{background-color:'.$color4.';color:'.$color7.';}
	div.mjtc-support-search-heading-wrp div.mjtc-support-heading-right a.mjtc-support-add-download-btn{background:'.$color2.';color:'.$color7.';}
	div.mjtc-support-search-heading-wrp div.mjtc-support-heading-right a.mjtc-support-add-download-btn:hover{background:rgba(125, 135, 141, 0.4);color:'.$color7.';}
	div.mjtc-support-fields-wrp div.mjtc-support-form-field input.mjtc-support-field-input{background-color:#fff;border:1px solid '.$color5.';color: '.$color4.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button{background: '.$color1.' !important;color: '.$color7.' !important;border: 1px solid '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-search-button:hover{border-color: '.$color2.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button{background: '.$color2.';color: '.$color7.';border: 1px solid '.$color5.';}
	div.mjtc-support-search-form-btn-wrp input.mjtc-reset-button:hover{border-color: '.$color1.';}
	div#records div.ms_userpages a.ms_userlink:last-child{background-color:'.$color2.';color:'.$color7.';}
	div#records div.ms_userpages a.ms_userlink:last-child:hover{background-color:'.$color1.';color:'.$color7.';}
	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col span.mjtc-support-priority {color: '.$color7.';}
';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
