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





/*

*/

$majesticsupport_css = '';

/*Code for Css*/
$majesticsupport_css .= '
	/*new css*/
	div.mjtc-cp-main-wrp {float: left;width: 100%;margin: 0 !important;position: relative; z-index: 999;}
    div.mjtc-cp-wrapper { height: 345px;position:relative; }
	div.mjtc-cp-main-wrp div.mjtc-cp-left {float: left;width: 26%;padding:20px 0 0 16px;}
	div.mjtc-cp-main-wrp div.mjtc-cp-right {float: left;width: 74%;display: flex; flex-wrap:wrap;padding:15px 5px 15px 20px;}
	div.mjtc-cp-main-wrp div.mjtc-cp-right.mjtc-cp-right-fullwidth {width: 100%;padding: 15px 10px 15px 20px;}

	/* Dashboard Menu Links */
	div#mjtc-dash-menu-link-wrp{float: left;width: 100%;}
	div#mjtc-dash-menu-link-wrp.mjtc-dash-menu-link-hide{display:none}
	div.mjtc-section-heading{float: left;width: 100%;padding: 20px 15px;font-size: 20px;font-weight: bold;line-height: initial;display:none}
	div.mjtc-menu-links-wrp{float: left;width: 100%;}
	a.mjtc-support-dash-menu{float: left;width: 100%;padding: 10px;line-height: initial;}
	a.mjtc-support-dash-menu span.mjtc-support-dash-menu-icon{display: inline-block;margin-right: 5px;vertical-align: middle;}
	a.mjtc-support-dash-menu span.mjtc-support-dash-menu-icon img.mjtc-support-dash-menu-img {}
	a.mjtc-support-dash-menu span.mjtc-support-dash-menu-text{display: inline-block;vertical-align: middle;}
	.ms-admin-collapse-logo-overall-wrapper{display:none;}

	/* Count Box */
	div#mjtc-main-cp-wrapper{display: inline-block; float: left; width: 100%; padding: 15px 15px;}
	div#mjtc-main-head-cp{display: inline-block;float: left;width: calc(25% - 10px);padding: 9px 9px;margin: 0px 5px;}
	div#mjtc-main-head-cp .mjtc-cptext{display: inline-block; float: left; font-size: 25px;}
	div#mjtc-main-head-cp .mjtc-cpmenu{display: inline-block; float: right;}
	.mjtc-support-count {float: left;width: 100%;margin-bottom: 20px;padding: 10px;}
	.mjtc-support-count div.mjtc-support-link {float: left;width: calc(100% / 4);text-align: center;padding: 0 5px;}
	.mjtc-support-count a.mjtc-support-link {display: inline-block;padding: 15px 0px;text-decoration: none;min-width: 100%;}
	.mjtc-support-count .mjtc-support-cricle-wrp {float: left;width: 100%;margin-bottom: 10px;}
	.mjtc-support-count .mjtc-support-cricle-wrp .mjtc-mr-rp {margin: auto;}
	.mjtc-support-count .mjtc-myticket-link-text {float: left;width: 100%;}
	.mjtc-support-count .mjtc-support-link-text {float: left;width: 100%;}
	.mjtc-support-count .mjtc-support-cricle-wrp .mjtc-mr-rp {width: 100px;height: 100px;}
	.mjtc-support-count .mjtc-support-cricle-wrp .mjtc-mr-rp .circle .mask {clip: rect(0px, 100px, 100px, 50px);}
	.mjtc-support-count .mjtc-support-cricle-wrp .mjtc-mr-rp .circle .mask, 
	.mjtc-support-count .mjtc-support-cricle-wrp .mjtc-mr-rp .circle .fill, 
	.mjtc-support-count .mjtc-support-cricle-wrp .mjtc-mr-rp .circle .shadow {height: 100px;width: 100px;}
	.mjtc-support-count .mjtc-support-cricle-wrp .mjtc-mr-rp .circle .mask .fill {clip: rect(0px, 50px, 100px, 0px);}
	.mjtc-support-count .mjtc-support-cricle-wrp .mjtc-mr-rp .inset {height: 70px;width: 70px;}

	/* User Links */
	.mjtc-support-ticket-cont {width: 100%;padding: 30px 0 0;display:inline-flex;flex-wrap:wrap}
	.mjtc-support-ticket-cont .mjtc-support-link,
	.mjtc-support-ticket-cont .mjtc-support-link,
	.mjtc-support-ticket-cont .mjtc-support-link,
	.mjtc-support-ticket-cont .mjtc-support-link,
	.mjtc-support-ticket-cont .mjtc-support-link {width: calc(100%/3 - 14px); margin: 0px 14px 30px 0;}
	.mjtc-support-ticket-cont .box-1,
	.mjtc-support-ticket-cont .box-2,
	.mjtc-support-ticket-cont .box-3,
	.mjtc-support-ticket-cont .box-4,
	.mjtc-support-ticket-cont .box-5 {display: flex; flex-direction: column; justify-content: space-between;position:relative;}
	.mjtc-support-ticket-cont .box-1 .top-sec, 
	.mjtc-support-ticket-cont .box-2 .top-sec,
	.mjtc-support-ticket-cont .box-3 .top-sec,
	.mjtc-support-ticket-cont .box-4 .top-sec,
	.mjtc-support-ticket-cont .box-5 .top-sec {display:flex;width:100%;padding:15px 15px 0}
	.mjtc-support-ticket-cont .box-1 .top-sec .top-sec-left,
	.mjtc-support-ticket-cont .box-2 .top-sec .top-sec-left,
	.mjtc-support-ticket-cont .box-3 .top-sec .top-sec-left,
	.mjtc-support-ticket-cont .box-4 .top-sec .top-sec-left,
	.mjtc-support-ticket-cont .box-5 .top-sec .top-sec-left {width:50%;padding-top:5px;}
	.mjtc-support-ticket-cont .box-1 .top-sec .top-sec-left img,
	.mjtc-support-ticket-cont .box-3 .top-sec .top-sec-left img,
	.mjtc-support-ticket-cont .box-4 .top-sec .top-sec-left img,
	.mjtc-support-ticket-cont .box-5 .top-sec .top-sec-left img {width:35%} 
	.mjtc-support-ticket-cont .box-2 .top-sec .top-sec-left img {width:50%}
	.mjtc-support-ticket-cont .box-1 .mid-sec-left,
	.mjtc-support-ticket-cont .box-2 .mid-sec-left,
	.mjtc-support-ticket-cont .box-3 .mid-sec-left,
	.mjtc-support-ticket-cont .box-4 .mid-sec-left,
	.mjtc-support-ticket-cont .box-5 .mid-sec-left {padding: 0 15px;margin-top-10px}
	.mjtc-support-ticket-cont .box-1 .mid-sec-left .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-2 .mid-sec-left .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-3 .mid-sec-left .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-4 .mid-sec-left .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-5 .mid-sec-left .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-1 .top-sec .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-2 .top-sec .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-3 .top-sec .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-4 .top-sec .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-5 .top-sec .top-sec-left-txt {margin-top:10px; font-weight: bold;text-shadow: -1px 2px #2d2b2b7d}
	.mjtc-support-ticket-cont .box-1 .top-sec .top-sec-right,
	.mjtc-support-ticket-cont .box-2 .top-sec .top-sec-right,
	.mjtc-support-ticket-cont .box-3 .top-sec .top-sec-right,
	.mjtc-support-ticket-cont .box-4 .top-sec .top-sec-right,
	.mjtc-support-ticket-cont .box-5 .top-sec .top-sec-right {width:50%;display:flex;justify-content:end;}
	.mjtc-support-ticket-cont .box-1 .top-sec .top-sec-right img,
	.mjtc-support-ticket-cont .box-2 .top-sec .top-sec-right img,
	.mjtc-support-ticket-cont .box-3 .top-sec .top-sec-right img,
	.mjtc-support-ticket-cont .box-4 .top-sec .top-sec-right img,
	.mjtc-support-ticket-cont .box-5 .top-sec .top-sec-right img {width:70%;}
	.mjtc-support-ticket-cont .box-1 .bottom-sec img,
	.mjtc-support-ticket-cont .box-2 .bottom-sec img,
	.mjtc-support-ticket-cont .box-3 .bottom-sec img,
	.mjtc-support-ticket-cont .box-4 .bottom-sec img,
	.mjtc-support-ticket-cont .box-5 .bottom-sec img {width:100%; padding:0 0 10px}
	.mjtc-support-ticket-cont .box-1 .bottom-sec .graph-no-padding,
	.mjtc-support-ticket-cont .box-2 .bottom-sec .graph-no-padding,
	.mjtc-support-ticket-cont .box-3 .bottom-sec .graph-no-padding,
	.mjtc-support-ticket-cont .box-4 .bottom-sec .graph-no-padding,
	.mjtc-support-ticket-cont .box-5 .bottom-sec .graph-no-padding {padding:0 !important; margin-top:10px !important;}
	.mjtc-support-ticket-cont .box-4 .bottom-sec .box-4-img {margin-top:-10px}
	.mjtc-support-wrapper {float: left;width: 100%;}
	.mjtc-support-myticket-wrp {float: left;width: 100%; margin:0 0 30px;}
	.mjtc-support-myticket-wrp .mjtc-support-myticket-cont {padding: 10px 20px;}
	.mjtc-support-ticket-cont .majestic-support-box {float: left;width: calc(100% / 3 - 20px);margin: 0 10px;padding: 30px 20px;min-height: 365px;text-align: center;}
	.mjtc-support-ticket-cont .majestic-support-box img {display: inline-block;}
	.mjtc-support-ticket-cont .majestic-support-box .majestic-support-title {margin: 25px 0 17px;font-size: 20px;line-height: initial;font-weight: bold;}
	.mjtc-support-ticket-cont .majestic-support-box .majestic-support-desc {font-weight: 400;line-height: initial;}
	.mjtc-support-ticket-cont .majestic-support-box .majestic-support-btn {display: inline-block;width: 100%;padding: 10px;margin-top: 33px;line-height: initial;text-decoration: none !important;font-weight: 600;}
	.mjtc-support-box-title { position: absolute; margin-top: 55px; margin-left: 1%}
	.mjtc-support-box-img { position: absolute; margin-top: 15px; margin-left: 1%}
	.mjtc-support-box-img img { width: 35px; }
	/* Ticket Data Lists */
	.mjtc-support-data-list-wrp {float: left;width: calc(100%/3 - 14px);margin:0 14px 30px 0;background: #FFF;}
	.mjtc-support-data-list {float: left;width: 100%;padding: 10px 10px 0;}
	.mjtc-support-data-list .mjtc-support-data {text-overflow: ellipsis; white-space: nowrap; overflow: hidden;}
	.mjtc-support-data-list .mjtc-support-data, .mjtc-support-data-list .mjtc-support-data-last {float: left;width: 100%; padding: 10px 0;}
	.mjtc-support-data-list .mjtc-support-data:last-child {padding: 10px 0;}
	.mjtc-support-data-list .mjtc-support-data .mjtc-support-data-image {float: left;}
	.latst-ancmts .mjtc-support-data-list .mjtc-support-data .mjtc-support-data-tit {width: calc(100% - 50px);}
	.latst-kb .mjtc-support-data-list .mjtc-support-data .mjtc-support-data-tit {width: calc(100% - 50px);}
	.latst-faqs .mjtc-support-data-list .mjtc-support-data .mjtc-support-data-tit {width: calc(100% - 50px);}
	.mjtc-support-data-list .mjtc-support-data .mjtc-support-data-btn {float: right;text-decoration: none;padding: 10px 15px;text-align: center;line-height: initial;border-radius: unset;font-weight: normal;}
	
	

	/* Ticket Stats */
	div.mjtc-pm-graphtitle-wrp{float: left;width: 100%;margin-bottom: 20px;}
	div.mjtc-pm-graphtitle{font-size: 20px;float: left; padding: 20px 15px; width: 100%;font-weight: bold;line-height: initial;}
	div#mjtc-pm-grapharea{display: inline-block;float: left; width: 100%;padding-top: 20px;}
	div.mjtc-support-latest-ticket-header-txt {float: left;padding: 10px 0;}
	a.mjtc-support-latest-ticket-link {float: right;text-decoration: none !important;background: #fff;padding: 10px 15px;}

	/* Latest Tickets */
	div.mjtc-support-latest-ticket-wrapper{float: left;width: 100%;margin: 0 0 30px;background-color: #FFF}
	div.mjtc-support-latest-ticket-wrapper .mjtc-support-haeder-tickets .mjtc-support-header-txt {display:flex;align-items:center;font-weight:bold}
	div.mjtc-support-latest-ticket-wrapper .mjtc-support-haeder-tickets .mjtc-support-header-txt .mjtc-ticket-data-image {margin-right:10px;}
	div.mjtc-support-latest-ticket-wrapper .mjtc-support-haeder-tickets .mjtc-support-header-txt .mjtc-ticket-data-image img {margin-left:2px;}
	div.mjtc-support-haeder, div.mjtc-support-haeder-tickets {float: left;width: 100%;padding: 10px;display:flex; align-items:center;}
	div.mjtc-support-haeder-tickets {justify-content: space-between;}
	div.mjtc-support-haeder .mjtc-ticket-data-image {width:37px;}
	.mjtc-latest-ticket-data-img {margin-left:5%}
	div.mjtc-support-haeder .mjtc-latest-ticket-data-image {width:37px;}
	div.mjtc-support-haeder div.mjtc-support-header-txt, .mjtc-support-myticket-cont {float: left;padding: 6px 0 0 10px;line-height: initial;font-weight: bold;}
	.mjtc-support-myticket-cont {width:100%;}
	a.mjtc-support-header-link {padding: 0; text-decoration: underline !important;}
	div.mjtc-support-latest-tickets-wrp{float: left;width: 100%;max-height: 350px;overflow-x: hidden;overflow-y: auto;}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row{float: left;width: calc(100% - 10px); margin: 0px 5px;padding: 15px 10px;}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row .mjtc-support-toparea {padding: 0;}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-first-left{float: left;width: 54%;}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-first-left div.mjtc-support-user-img-wrp{float: left;width: 80px;height: 80px;position: relative;border-radius: 100%;}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-first-left div.mjtc-support-user-img-wrp img.mjtc-support-staff-img{width: auto;max-width: 100%;max-height: 100%;height: auto;position: absolute;top: 0;left: 0;right: 0;bottom: 0;margin: auto;border-radius: 100%}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-first-left div.mjtc-support-user-img-wrp img{width: auto;max-width: 100%;max-height: 100%;height: auto;position: absolute;top: 0;left: 0;right: 0;bottom: 0;margin: auto;border-radius: 100%}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-first-left div.mjtc-support-ticket-subject{float: left;width: calc(100% - 80px);padding: 5px 0 0 20px;}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-first-left div.mjtc-support-ticket-subject div.mjtc-support-data-row {line-height: initial;padding-bottom: 8px;}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-first-left div.mjtc-support-ticket-subject div.mjtc-support-data-row:last-child {padding-bottom: 0;display:flex;}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-first-left div.mjtc-support-ticket-subject div.mjtc-support-data-row.name {text-decoration: underline;}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-first-left div.mjtc-support-ticket-subject div.mjtc-support-data-row a.mjtc-support-data-link {display: inline-block;width: 95%;height: 25px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;text-decoration:underline;text-transform:capitalize;font-weight:600;padding-top:3px;}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-second-left{float: left;width: 20%; text-align: center;padding: 22px 0;line-height: initial;}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-second-left span.mjtc-support-status {padding: 8px;display: inline-block;font-size:12px}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-third-left{float: left;width: 11%;text-align: center;padding: 30px 0;line-height: initial;}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-fourth-left{float: left;width: 15%;padding: 23px 0;}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-fourth-left span.mjtc-support-priorty{text-align: center;padding: 8px;display: inline-block;line-height: initial;text-transform: uppercase;font-weight: bold; font-size:12px;}
	span.mjtc-support-latest-ticket-heading{display: none;}
	div.mjtc-support-zero-padding{padding: 0px !important;}
	
	/*download popup */	
	div#mjtc-support-main-black-background{position: fixed;width: 100%;height: 100%;background: rgba(0,0,0,0.7);z-index: 999;top:0px;left:0px;}
	div#mjtc-support-main-popup {position: fixed;top: 50%;left: 50%;width: 60%;max-height: 70%;padding-top: 0px;z-index: 99999;overflow-y: auto;overflow-x: hidden;background: #fff;transform: translate(-50%,-50%);}
	span#mjtc-support-popup-title {width: 100%;display: inline-block;padding: 20px;font-size: 20px;line-height: initial;text-transform: capitalize;}
	span#mjtc-support-popup-close-button{position: absolute;top:18px;right: 18px;width:25px;height: 25px;}
	span#mjtc-support-popup-close-button:hover{cursor: pointer;}
	div#mjtc-support-main-content {float: left;width: 100%;padding: 0px 25px;}
	div.mjtc-support-downloads-content {float: left;width: 100%;padding: 20px 0px;}
	div.mjtc-support-download-description {float: left;width: 100%;padding: 0px 0px 15px;line-height: 1.8;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box {float: left;width: 100%;padding: 10px;margin-bottom: 10px;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left {float: left;width: 80%;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title {float: left;width: 100%;padding: 9px;cursor: pointer;line-height: initial;text-decoration: none;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title img.mjtc-support-download-icon {float: left;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title span.mjtc-support-download-name {width: calc(100% - 60px); display: inline-block;padding: 10px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;}
	div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-right {float: left;width: 20%;}
	div#mjtc-support-main-downloadallbtn {float: left;width: 100%;padding: 0px 25px 20px;}
	#mjtc-support-main-popup div.mjtc-support-download-btn {padding: 8px 0;text-align: right;}
	div.mjtc-support-download-btn a.mjtc-support-download-btn-style {display: inline-block;padding: 15px 20px;border-radius: unset;font-weight: unset;text-decoration: none;outline: 0;line-height: initial;}
	#mjtc-support-main-popup #mjtc-support-main-downloadallbtn .mjtc-support-download-btn {text-align: left;}


';
/*Code For Colors*/
$majesticsupport_css .= '
/*Count Box*/
.mjtc-support-brown {color: #2168A2;} 
.mjtc-support-red {color: #3D355A;} 
.mjtc-support-blue {color: #621166;} 
.mjtc-support-green {color: #159667;} 
.mjtc-support-orange {color: #B82B2B;}
.mjtc-support-mariner {color: #2265D8;}
.mjtc-support-purple {color: #9922D8;}
.mjtc-support-open {background-color: #159667;} 
.mjtc-support-close {background-color: #3D355A;}
.mjtc-support-answer {background-color: #2168A2;}
.mjtc-support-overdue {background-color: #B82B2B;}
.mjtc-support-allticket {background-color: #621166;}
.mjtc-support-count {background: '.$color7.';;border: 1px solid '.$color5.';}
.mjtc-support-count a.mjtc-support-link {background-color: '.$color7.';border: 1px solid '.$color5.';}
.mjtc-support-count a.mjtc-support-link:hover {box-shadow: 0 1px 3px 0 rgba(60,64,67,0.302),0 4px 8px 3px rgba(60,64,67,0.149);}
.mjtc-support-count a.mjtc-support-link.mjtc-support-brown.active {border-color: #2168A2;}
.mjtc-support-count a.mjtc-support-link.mjtc-support-brown:hover{border-color: #2168A2;}
.mjtc-support-count a.mjtc-support-link.mjtc-support-red.active {border-color: #3D355A;}
.mjtc-support-count a.mjtc-support-link.mjtc-support-red:hover{border-color: #3D355A;}
.mjtc-support-count a.mjtc-support-link.mjtc-support-blue.active {border-color: #621166;}
.mjtc-support-count a.mjtc-support-link.mjtc-support-blue:hover{border-color: #621166;}
.mjtc-support-count a.mjtc-support-link.mjtc-support-green.active {border-color: #159667;}
.mjtc-support-count a.mjtc-support-link.mjtc-support-green:hover{border-color: #159667;}
.mjtc-support-count a.mjtc-support-link.mjtc-support-orange.active {border-color: #B82B2B;}
.mjtc-support-count a.mjtc-support-link.mjtc-support-orange:hover{border-color: #B82B2B;}
.mjtc-support-count a.mjtc-support-link.mjtc-support-mariner:hover{border-color: #2265d8;}
.mjtc-support-count a.mjtc-support-link.mjtc-support-purple:hover{border-color: #9922d9;}
.mjtc-support-count a.mjtc-support-link.mjtc-support-mariner .mjtc-report-box-title {color: #2265d8;}
.mjtc-support-count a.mjtc-support-link.mjtc-support-purple .mjtc-report-box-title {color: #9922d9;}
}

	
/*Count Box*/
	
/*download popup */	

div#mjtc-support-main-popup {background: #fff !important;}
span#mjtc-support-popup-title {background: '.$color1.';color: '.$color7.';}
div.mjtc-support-download-description {color: '.$color4.';}
div.mjtc-support-downloads-content div.mjtc-support-download-box {border: 1px solid '.$color5.';box-shadow: 0 8px 6px -6px #dedddd;}
div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title span.mjtc-support-download-name {color: '.$color4.';}
div.mjtc-support-downloads-content div.mjtc-support-download-box div.mjtc-support-download-left a.mjtc-support-download-title span.mjtc-support-download-name:hover {color: '.$color2.';}
div.mjtc-support-download-btn a.mjtc-support-download-btn-style {color: '.$color1.';border: 1px solid '.$color1.';}
div.mjtc-support-download-btn a.mjtc-support-download-btn-style:hover {color: '.$color2.';border: 1px solid '.$color2.';}
#mjtc-support-main-popup #mjtc-support-main-downloadallbtn .mjtc-support-download-btn a.mjtc-support-download-btn-style {background-color: '.$color1.';color: #ffffff;border-color: '.$color1.';}
#mjtc-support-main-popup #mjtc-support-main-downloadallbtn .mjtc-support-download-btn a.mjtc-support-download-btn-style:hover {border-color: '.$color2.';}

/* User Links */
.majestic-support-cont .majestic-support-box {background: #f6f6f6;border: 1px solid '.$color5.';box-shadow: 3px solid rgba(0,0,0,0.5);}
.majestic-support-cont .majestic-support-box .majestic-support-title {color: '.$color2.';}
.majestic-support-cont .majestic-support-box .majestic-support-desc {color: '.$color2.';}
.majestic-support-cont .majestic-support-box .majestic-support-btn {color: '.$color7.';background: '.$color2.';border-bottom: 3px solid rgba(0,0,0,0.5);}
.majestic-support-cont .majestic-support-box .majestic-support-btn:hover {background: '.$color1.';}

/* User Links */


/* Ticket Data Lists */
.mjtc-support-data-list-wrp {border: 1px solid '.$color5.';}
.mjtc-support-data-list .mjtc-support-data .mjtc-support-data-tit {color: '.$color4.';}
.mjtc-support-data-list .mjtc-support-data .mjtc-support-data-tit:hover {color: '.$color2.';cursor:pointer}
.mjtc-support-data-list .mjtc-support-data, .mjtc-support-haeder {border-bottom: 1px solid '.$color5.';}
.mjtc-support-data-list .mjtc-support-data .mjtc-support-data-btn {border: 1px solid '.$color1.';background: '.$color3.';color: '.$color1.';}
.mjtc-support-data-list .mjtc-support-data .mjtc-support-data-btn:hover {border-color: '.$color2.';background: #fff;color: '.$color2.';}

/* Ticket Data Lists */

/*Ticket Stats*/
	div.mjtc-pm-graphtitle{border:1px solid '.$color5.';background-color: #ffff;border-bottom:1px solid '.$color5.';color :'.$color2.';}
	a.mjtc-support-latest-ticket-link {color: '.$color4.';background: '.$color7.';}
/*Ticket Stats*/
/* Dashboard Menu Links */
	div#mjtc-dash-menu-link-wrp {border:1px solid '.$color5.'; background: #fff;padding: 10px }
	div.mjtc-section-heading{border-bottom:1px solid '.$color5.';color :'.$color2.';}
	a.mjtc-support-dash-menu{border-bottom:1px solid '.$color5.';}
	a.mjtc-support-dash-menu:last-child{border-bottom:0;}
	a.mjtc-support-dash-menu span.mjtc-support-dash-menu-text{color:'.$color4.';}
	a.mjtc-support-dash-menu span.mjtc-support-dash-menu-text:hover{color:'.$color2.';}
	
/* Dashboard Menu Links */
/* latest Tickets */
	div.mjtc-support-latest-ticket-wrapper{border: 1px solid '.$color5.';}
	div.mjtc-support-haeder, div.mjtc-support-haeder-tickets {background-color: '.$color3.';color: '.$color2.';}
	div.mjtc-support-haeder div.mjtc-support-header-txt {color: '.$color2.';}
	div.mjtc-support-haeder a.mjtc-support-header-link {color: '.$color2.';}
	div.mjtc-support-haeder a.mjtc-support-header-link:hover {color: '.$color1.';}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row{border-bottom:1px solid '.$color5.';}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row:last-child{border-bottom:none;}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-first-left div.mjtc-support-ticket-subject div.mjtc-support-data-row {color: '.$color4.';}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-first-left div.mjtc-support-ticket-subject div.mjtc-support-data-row.name {color: '.$color2.';}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-first-left div.mjtc-support-ticket-subject div.mjtc-support-data-row a.mjtc-support-data-link {color: '.$color1.';}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-first-left div.mjtc-support-ticket-subject div.mjtc-support-data-row a.mjtc-support-data-link:hover {color: '.$color2.';}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-first-left div.mjtc-support-ticket-subject div.mjtc-support-data-row span.mjtc-support-title {color: '.$color2.';}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-second-left span.mjtc-support-status {border: 1px solid '.$color5.';background: #fff;}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-third-left {color: '.$color4.';}
	div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-fourth-left span.mjtc-support-priorty{color:'.$color7.';}
/* latest Tickets */

';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
