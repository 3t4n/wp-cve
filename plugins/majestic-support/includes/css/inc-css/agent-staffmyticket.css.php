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
/* Top Circle Count Boxes */
	div.mjtc-support-top-cirlce-count-wrp{float: left;width: 100%;margin:0 0 20px 0;padding: 10px 5px;}
	div.mjtc-myticket-link{text-align:center;padding-left:5px;padding-right:5px; width: calc(100% / 5);}
	div.mjtc-support-myticket-link-myticket{width: calc(100% / 4);}
	div.mjtc-myticket-link a.mjtc-myticket-link{display: inline-block;padding:15px 0px; text-decoration: none;min-width: 100%;}
	.mjtc-mr-rp{margin: auto;}
	div.mjtc-support-cricle-wrp{float: left;width: 100%;margin-bottom: 10px;}

/* Search Ticket Form*/
	div.mjtc-support-search-wrp{float: left;width: 100%;margin-bottom: 17px;}
	div.mjtc-support-search-wrp div.mjtc-support-search-heading{float: left;width: 100%;padding: 15px;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp{float: left;width: 100%;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form{display: inline-block;width: 100%;float: left;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper{float: left;width: 100%;padding: 10px 5px;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper div.mjtc-filter-form-fields-wrp{padding: 0 5px;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-field-wrp{margin-bottom: 10px;padding: 0 5px;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-field-wrp input.mjtc-support-input-field{border-radius: unset;width:100%;padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-field-wrp input.inputbox{border-radius: unset;width:100%;padding: 10px;height: 50px;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-field-wrp input#ms-datestart{background-image: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/calender.png);background-repeat: no-repeat;background-position: 96% 13px;padding: 10px;line-height: initial;height: 50px;background-size: 20px;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-field-wrp input#ms-dateend{background-image: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/calender.png);background-repeat: no-repeat;background-position: 96% 13px;padding: 10px;line-height: initial;height: 50px;background-size: 20px;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-field-wrp select#ms-departmentid{width: 100%;border-radius: unset;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / 4% no-repeat;padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-field-wrp select#ms-priorityid{width: 100%;border-radius: unset;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / 4% no-repeat ;padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-field-wrp select#staffid{width: 100%;border-radius: unset;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / 4% no-repeat ;padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper div.mjtc-filter-form-fields-wrp input{width:100%;border-radius: unset;padding: 10px;line-height: initial;height: 50px;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper div.mjtc-filter-form-fields-wrp input#assignedtome1 {width: auto;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper div.mjtc-filter-form-fields-wrp label {display: inline-block;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper div.mjtc-filter-title {width:100%;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper div.mjtc-filter-button{padding-top: 10px; padding-bottom: 10px; display: inline-block; width: 100%; text-align: center;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper div.mjtc-filter-button input[class="button"]{min-width: 90px;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper div.mjtc-filter-value select{width:100%;border-radius: unset;background: url('.esc_url(MJTC_PLUGIN_URL).'includes/images/selecticon.png) 96% / 4% no-repeat;padding: 10px;height: 50px;line-height: initial;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper div.mjtc-filter-value textarea{width:100%;border-radius: unset;padding: 10px;height: 50px;line-height: initial;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-button-wrp{padding: 0 5px;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-button-wrp .mjtc-search-filter-btn {float: left;padding: 15px 0;line-height: initial;min-width: calc(100% / 3);margin-right: 10px;text-align: center;height: 50px;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-button-wrp input.mjtc-support-search-btn{min-width: calc(100% / 3); float: left; border-radius: unset; margin-right: 10px; padding: 13px 0px;line-height: initial;height: 50px;}
	div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-button-wrp input.mjtc-support-reset-btn{min-width: calc(100% / 3 - 20px); float: left; border-radius: unset; padding: 13px 0px;line-height: initial;height: 50px;}
	div.mjtc-filter-wrapper-toggle-ticketid input.mjtc-support-input-field{border-radius: unset;}
	div#mjtc-filter-wrapper-toggle-area div.mjtc-filter-wrapper div.mjtc-filter-value input.mjtc-support-input-field{border-radius: unset;}
	div#mjtc-filter-wrapper-toggle-area{}
	div#mjtc-filter-wrapper-toggle-btn{float: left;width: calc(100% - 94% - 5px);margin-left: 5px;}
	div#mjtc-filter-wrapper-toggle-plus{float: left;width: 100%;cursor: pointer;padding: 15px;text-align: center;line-height: initial;}
	div#mjtc-filter-wrapper-toggle-minus{float: left;width: 100%;cursor: pointer;padding: 15px;text-align: center;line-height: initial;}
	div.mjtc-filter-wrapper-toggle-ticketid{display: none;}
	div#mjtc-filter-wrapper-toggle-minus{display: none;}
	div#mjtc-filter-wrapper-toggle-area{display: none;}
	span.mjtc-filter-form-data-xs{display: none;}
	div.mjtc-support-sorting{float: left;width: 100%;}
	

/* My Tickets $ Staff My Tickets*/
	div.mjtc-support-wrapper{margin:8px 0px;padding-left: 0px;padding-right: 0px;}
	div.mjtc-support-wrapper div.mjtc-support-pic{text-align: center;width: 12%;position: relative;height: 125px;padding: 0 10px;}
	div.mjtc-support-wrapper div.mjtc-support-pic img {width: auto;max-width: 93px;max-height: 93px;height: auto;position: absolute;left: 0;right: 0;bottom: 0;top: 0;margin: auto;}
	div.mjtc-support-wrapper div.mjtc-support-pic img.mjtc-support-staff-img{width: 90px;max-width: 90%;max-height: 90%;height: 90px;position: absolute;top: 0;left: 0;right: 0;bottom: 0;margin: auto;border-radius: 100%;}
	div.mjtc-support-wrapper div.mjtc-support-data{position: relative;padding: 15px 20px 15px 0;width: 63%;}
	div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-status{position: absolute;top: 42px;right: 119px;padding: 7px 12px;font-size: 14px;font-weight: bold;line-height: initial;display: inline-block;}
	div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-status img.ticketstatusimage{position: absolute;top:0px;}
	div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-status img.ticketstatusimage.one{left:-40px;}
	div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-status img.ticketstatusimage.two{left:-80px;}
	div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-closedby-wrp{position: relative;font-size: 14px;}
	div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-closedby{display: inline-block;color: #463e8f;text-transform: capitalize;border: 1px solid #817cb3;padding: 0 8px;cursor:pointer;margin-left: 5px;}
	div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-closed-date{color: #3f3f41;border: 1px solid #e6e5e5;padding: 0 8px;position: absolute;background-image: linear-gradient(to top, #d3d3d2, #f6f6f6);top: 30px;display:none;min-width: 170px;z-index:1;}
	div.mjtc-support-wrapper div.mjtc-support-data .mjtc-support-title-anchor {text-transform: capitalize;display: inline-block;width: 65%;height: 35px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;padding-top: 7px;font-weight:600;text-decoration:underline;}
	div.mjtc-support-wrapper div.mjtc-support-data1{margin:0px 0px;padding: 17px 15px 17px 15px;width: 25%;}
	
	div.mjtc-support-wrapper div.mjtc-support-data1 div.mjtc-support-data-row {padding-bottom: 7px;}
	div.mjtc-support-wrapper div.mjtc-support-data1 div.mjtc-support-data-row:last-child {padding-bottom: 0;}
	div.mjtc-support-wrapper div.mjtc-support-data1 div.mjtc-support-data-row .mjtc-support-data-tit {display: inline-block;margin-right: 5px;}
	div.mjtc-support-wrapper div.mjtc-support-data1 div.mjtc-support-data-row .mjtc-support-data-val {display: inline-block;}

	div.mjtc-support-wrapper div.mjtc-support-bottom-line{position:absolute;display: inline-block;width:90%;margin:0 5%;height:1px;left:0px;bottom: 0px;}
	div.mjtc-support-wrapper div.mjtc-support-toparea{position: relative;padding:0px;}
	div.mjtc-support-wrapper div.mjtc-support-toparea .mjtc-support-body-data-elipses {padding: 0;}
	div.mjtc-support-wrapper div.mjtc-support-bottom-data-part{padding: 0px;margin-bottom: 10px;}
	div.mjtc-support-wrapper div.mjtc-support-bottom-data-part a.button{float:right;margin-left: 10px;padding:0px 20px;line-height: 30px;height:32px;}
	div.mjtc-support-wrapper div.mjtc-support-bottom-data-part a.button img{height:16px;margin-right:5px;}
	div.mjtc-support-assigned-tome{float: left;width: 100%;padding: 12px 10px;height: 50px;}
	div.mjtc-support-assigned-tome input#assignedtome1{margin-right: 5px; vertical-align: middle;}
	div.mjtc-support-assigned-tome label#forassignedtome{margin: 0px;display: inline-block;}
	label#forassigntome{margin: 0px;display: inline-block;}
	input#assigntome1{margin-right:5px;}
	span.mjtc-support-wrapper-textcolor{display: inline-block;padding: 7px 16px;text-align: center;line-height: initial;position: absolute;top: 42px;right: 20px;font-size: 16px;font-weight: bold;min-width: 80px;}

/* Sorting Section */
	div.mjtc-support-sorting{margin-bottom: 15px;padding: 10px;}
	div.mjtc-support-sorting span.mjtc-support-sorting-link{padding-right:0px;padding-left: 0px;}
	div.mjtc-support-sorting span.mjtc-support-sorting-link a{text-decoration: none;display: block;padding: 15px; text-align:center;}
	div.mjtc-support-sorting span.mjtc-support-sorting-link a img{display: inline-block;vertical-align: text-top;}
	div.mjtc-support-sorting-left {float: left;width: 50%;}
	div.mjtc-support-sorting-heading {float: left;width: 100%;padding: 15px 10px;line-height: initial;font-size:18px;font-weight:bold;}
	div.mjtc-support-sorting-right {float: right;width: 50%;}
	div.mjtc-support-sorting-right div.mjtc-support-sort {float: right;}
	div.mjtc-support-sorting-right div.mjtc-support-sort select.mjtc-support-sorting-select {float: left;width: 125px;height: 50px;padding: 10px;appearance: none;line-height: initial;}
	div.mjtc-support-sorting-right div.mjtc-support-sort a.mjtc-admin-sort-btn {float: left;padding: 14px 7px;line-height: initial;height: 50px;}

	select ::-ms-expand {display:none !important;}
	select{-webkit-appearance:none !important;}


';
/*Code For Colors*/
$majesticsupport_css .= '

/* My Tickets */
	div.mjtc-support-top-cirlce-count-wrp {border:1px solid'.$color5.';}
	/* Top Circle Count Box*/
		div.mjtc-myticket-link a.mjtc-myticket-link{border:1px solid'.$color5.';}
		div.mjtc-myticket-link a.mjtc-myticket-link:hover{background: rgba(227, 231, 234, 0.7);}
		.mjtc-support-answer{background-color:#2168A2;}
		.mjtc-support-close{background-color:#3D355A;}
		.mjtc-support-allticket{background-color:#621166;}
		.mjtc-support-open{background-color:#159667;}
		.mjtc-support-overdue{background-color:#B82B2B;}
		.mjtc-support-blue{color:#621166;}
		.mjtc-support-red{color:#3D355A;}
		.mjtc-support-green {color: #159667;}
		.mjtc-support-brown {color: #2168A2;}
		.mjtc-support-orange {color: #B82B2B;}
		div.mjtc-myticket-link a.mjtc-myticket-link span.mjtc-support-circle-count-text.mjtc-support-blue{color:#621166;}
		div.mjtc-myticket-link a.mjtc-myticket-link span.mjtc-support-circle-count-text.mjtc-support-red{color:#3D355A;}
		div.mjtc-myticket-link a.mjtc-myticket-link span.mjtc-support-circle-count-text.mjtc-support-orange{color:#B82B2B;}
		div.mjtc-myticket-link a.mjtc-myticket-link span.mjtc-support-circle-count-text.mjtc-support-green{color:#159667;}
		div.mjtc-myticket-link a.mjtc-myticket-link span.mjtc-support-circle-count-text.mjtc-support-brown{color:#2168A2;}
		div.mjtc-myticket-link a.mjtc-myticket-link span.mjtc-support-circle-count-text.mjtc-support-yellow{color:#D78D39;}
		div.mjtc-myticket-link a.mjtc-myticket-link span.mjtc-support-circle-count-text.mjtc-support-pink{color:#B82B2B;}
		div.mjtc-myticket-link a.mjtc-myticket-link div.progress::after {border: 25px solid #bfbfbf;}
		div.mjtc-myticket-link a.mjtc-myticket-link:hover{box-shadow: 0 1px 3px 0 rgba(60,64,67,0.302),0 4px 8px 3px rgba(60,64,67,0.149);background-color: #fafafb;}
		div.mjtc-myticket-link a.mjtc-myticket-link.mjtc-support-green.active{border-color:#159667;}
		div.mjtc-myticket-link a.mjtc-myticket-link.mjtc-support-blue.active{border-color:#621166;}
		div.mjtc-myticket-link a.mjtc-myticket-link.mjtc-support-red.active{border-color:#3D355A;}
		div.mjtc-myticket-link a.mjtc-myticket-link.mjtc-support-orange.active{border-color:#B82B2B;}
		div.mjtc-myticket-link a.mjtc-myticket-link.mjtc-support-pink.active{border-color:#B82B2B;}
		div.mjtc-myticket-link a.mjtc-myticket-link.mjtc-support-brown.active{border-color:#2168A2}

		div.mjtc-myticket-link a.mjtc-myticket-link.mjtc-support-green:hover{border-color:#159667;}
		div.mjtc-myticket-link a.mjtc-myticket-link.mjtc-support-blue:hover{border-color:#621166;}
		div.mjtc-myticket-link a.mjtc-myticket-link.mjtc-support-red:hover{border-color:#3D355A;}
		div.mjtc-myticket-link a.mjtc-myticket-link.mjtc-support-orange:hover{border-color:#B82B2B;}
		div.mjtc-myticket-link a.mjtc-myticket-link.mjtc-support-pink:hover{border-color:#B82B2B;}
		div.mjtc-myticket-link a.mjtc-myticket-link.mjtc-support-brown:hover{border-color:#2168A2}


	/* Top Circle Count Box*/
	/* Search Ticket Form*/
		div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper div.mjtc-filter-value select{background-color:'.$color7.';border: 1px solid '.$color5.';}
		div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper div.mjtc-filter-value textarea{background-color:'.$color7.';border: 1px solid '.$color5.';}
		div.mjtc-support-search-wrp{border:1px solid'.$color5.';}
		div.mjtc-support-search-wrp div.mjtc-support-search-heading{background-color:#e7ecf2;border-bottom:1px solid'.$color5.'; color:'.$color4.'}
		div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper {background-color:'.$color3.';}
		div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-wrapper div.mjtc-filter-form-fields-wrp input.mjtc-support-input-field{border:1px solid '.$color5.';color: '.$color4.';}
		div.mjtc-filter-wrapper-toggle-ticketid input.mjtc-support-input-field{background-color:'.$color3.';border:1px solid'.$color5.';}
		div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-field-wrp input.mjtc-support-input-field{background-color:#fff;border:1px solid '.$color5.';color: '.$color4.';}
		div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-field-wrp input.inputbox{background-color:'.$color7.';border:1px solid'.$color5.';}
		div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-field-wrp select#ms-departmentid{background-color:#fff;border:1px solid'.$color5.';color: '.$color4.';}
		div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-field-wrp select#ms-priorityid{background-color:#fff;border:1px solid'.$color5.';color: '.$color4.';}
		div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-field-wrp select#staffid{background-color:#fff;border:1px solid'.$color5.';color: '.$color4.';}
		div#mjtc-filter-wrapper-toggle-area div.mjtc-filter-wrapper div.mjtc-filter-value input.mjtc-support-input-field{background-color:'.$color3.';border:1px solid'.$color5.';}
		div#mjtc-filter-wrapper-toggle-area div.mjtc-filter-wrapper div.mjtc-filter-value select#ms-departmentid{background-color:'.$color3.';border:1px solid'.$color5.';}
		div#mjtc-filter-wrapper-toggle-area div.mjtc-filter-wrapper div.mjtc-filter-value select#ms-priorityid{background-color:'.$color3.';border:1px solid'.$color5.';}
		div#mjtc-filter-wrapper-toggle-plus{background-color:#474749;}
		div#mjtc-filter-wrapper-toggle-minus{background-color:#474749;}
		div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-button-wrp .mjtc-search-filter-btn {border: 1px solid '.$color5.';color: '.$color4.';background: #fff;}
		div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-button-wrp .mjtc-search-filter-btn:hover {border-color: '.$color1.';}
		div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-button-wrp input.mjtc-support-search-btn{background-color:'.$color1.';color:'.$color7.';border: 1px solid '.$color5.';}
		div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-button-wrp input.mjtc-support-search-btn:hover{border-color: '.$color2.';}
		div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-button-wrp input.mjtc-support-reset-btn{background-color:'.$color2.';color:'.$color7.';border: 1px solid '.$color5.';}
		div.mjtc-support-search-wrp div.mjtc-support-form-wrp form.mjtc-filter-form div.mjtc-filter-button-wrp input.mjtc-support-reset-btn:hover{border-color: '.$color1.';}
		span.mjtc-support-wrapper-textcolor{color: '.$color7.';}
	/* Search Ticket Form*/
	/* My Tickets $ Staff My Tickets*/
		div.mjtc-support-wrapper{border:1px solid'.$color5.';box-shadow: 0 8px 6px -6px #dedddd;}
		div.mjtc-support-wrapper:hover div.mjtc-support-pic{}
		div.mjtc-support-wrapper:hover div.mjtc-support-data1{}
		div.mjtc-support-wrapper:hover div.mjtc-support-bottom-line{background:'.$color2.';}
		div.mjtc-support-wrapper div.mjtc-support-pic{}
		div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-status{background-color:#FFFFFF;border: 1px solid '.$color5.';}
		div.mjtc-support-wrapper div.mjtc-support-data1{}
		div.mjtc-support-wrapper div.mjtc-support-data1 div.mjtc-support-data-row .mjtc-support-data-tit {color: '.$color2.';}
		div.mjtc-support-wrapper div.mjtc-support-data1 div.mjtc-support-data-row .mjtc-support-data-val {color: '.$color4.';}
		div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-title{color: '.$color2.';}
		a.mjtc-support-title-anchor:hover{color:'.$color2.' !important;}
		div.mjtc-support-wrapper div.mjtc-support-data span.mjtc-support-value{color:'.$color4.';}
		div.mjtc-support-wrapper div.mjtc-support-data .mjtc-support-title-anchor {color:'.$color1.';}
		div.mjtc-support-wrapper div.mjtc-support-data .name span.mjtc-support-value {color:'.$color4.';}
		div.mjtc-support-wrapper div.mjtc-support-bottom-line{background:'.$color2.';}
		div.mjtc-support-assigned-tome{border:1px solid'.$color5.';background-color:#fff;color: '.$color4.';}
		div.mjtc-support-sorting {background:'.$color2.';color: '.$color7.';}
		div.mjtc-support-sorting span.mjtc-support-sorting-link a{background:#373435;color: '.$color7.';color:#fff;}
		div.mjtc-support-sorting span.mjtc-support-sorting-link a.selected,
		div.mjtc-support-sorting span.mjtc-support-sorting-link a:hover{background:'.$color2.';}
		div.mjtc-support-sorting-right div.mjtc-support-sort select.mjtc-support-sorting-select {background: #fff;color: '.$color2.';border: 1px solid '.$color5.';}
		div.mjtc-support-sorting-right div.mjtc-support-sort a.mjtc-admin-sort-btn {background: #fff;}
	/* My Tickets $ Staff My Tickets*/
/* My Tickets */';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
