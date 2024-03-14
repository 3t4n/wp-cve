<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
$color1 = "#291abc";
$color2 = "#2b2b2b";
$color3 = "#f5f2f5";
$color4 = "#636363";
$color5 = "#d1d1d1";
$color6 = "#e7e7e7";
$color7 = "#ffffff";
$color8 = "#2DA1CB";
$color9 = "#000000";

$array = array('color1' => $color1, 'color2' => $color2, 'color3' => $color3, 'color4' => $color4, 'color5' => $color5, 'color6' => $color6, 'color7' => $color7, 'color8' => $color8, 'color9' => $color9 );
$array = apply_filters( 'cm_theme_colors', $array, 'majestic-support' );
$color2 = $array['color2'];
$color1 = $array['color1'];
$color3 = $array['color3'];
$color4 = $array['color4'];
$color5 = $array['color5'];
$color6 = $array['color6'];
$color7 = $array['color7'];
$color8 = $array['color8'];
$color9 = $array['color9'];

majesticsupport::$_colors['color1']=$color1;
majesticsupport::$_colors['color2']=$color2;
majesticsupport::$_colors['color3']=$color3;
majesticsupport::$_colors['color4']=$color4;
majesticsupport::$_colors['color5']=$color5;
majesticsupport::$_colors['color6']=$color6;
majesticsupport::$_colors['color7']=$color7;
majesticsupport::$_colors['color8']=$color8;
majesticsupport::$_colors['color9']=$color9;

$result = "

/*BreadCrumbs*/
	div.mjtc-support-flat a:hover, div.mjtc-support-flat a.active, div.mjtc-support-flat a:hover::after, div.mjtc-support-flat a.active::after{background-color:$color2;}
	div.mjtc-support-breadcrumb-wrp .breadcrumb li:first-child a{background-color:$color2;}
	div.mjtc-support-breadcrumb-wrp .breadcrumb li:first-child a:hover::after{background-color:transparent !important;}
	div.mjtc-support-breadcrumb-wrp .breadcrumb li:first-child a::after {border-left-color:$color2;}
	div.mjtc-support-breadcrumb-wrp .breadcrumb li a::after{border-left-color:#c9c9c9;}
	div.mjtc-support-breadcrumb-wrp .breadcrumb li a{color:$color4;}
	div.mjtc-support-breadcrumb-wrp .breadcrumb li a:hover{color:$color2;}
/*BreadCrumbs*/

/*Top Header*/
	div#ms-header, div.mjtc-cp-wrapper, mjtc-support-add-form-wrapper {background-color:$color1;}
    .mjtc-support-top-sec-header {background-color:$color1;}
    .mjtc-support-top-sec .mjtc-support-top-sec-right .mjtc-support-button {color: $color7;background: rgba(0,0,0,0.2);}
    .mjtc-support-top-sec .mjtc-support-top-sec-right .mjtc-support-button:hover {background: rgba(0,0,0,0.4);}
    .mjtc-support-top-sec-header .mjtc-support-top-sec-right-header .mjtc-support-button-header {color: $color7;background: rgba(0,0,0,0.2);}
    .mjtc-support-top-sec-header .mjtc-support-top-sec-right-header .mjtc-support-button-header:hover {background: rgba(0,0,0,0.4);}
    form.mjtc-support-form, .mjtc-support-form1 {background-color: $color7;}
	a.mjtc-support-header-links{color:$color7;}
	a.mjtc-support-header-links:hover{color: $color7;;}
	div#ms-header div#ms-header-heading{color:$color3;}
	div#ms-header div.ms-header-tab a.mjtc-cp-menu-link{background:rgba(0,0,0,0.2);color:$color7;}
	div#ms-header div.ms-header-tab a.mjtc-cp-menu-link:hover{background:rgba(0,0,0,0.4);color:$color7;}
	div#ms-header div.ms-header-tab.active a.mjtc-cp-menu-link{background:$color1;color:$color7;}
	div#ms_breadcrumbs_parent div.home a{background:$color2;}
    .mjtc-support-button, .mjtc-support-button-header { background: $color6; color: $color7 !important;}
    .mjtc-support-button:hover, .mjtc-support-button-header:hover {background:rgba(0,0,0,0.4);}
    .mjtc-support-myticket-wrp { border: 1px solid $color5;}
    .mjtc-support-box-title { color: $color7 }
    .mjtc-support-myticket-wrp .mjtc-support-myticket-cont {background: $color3; border-bottom: 1px solid $color5;}
    .mjtc-ticket-data-image, .mjtc-latest-ticket-data-image {background: $color1}
    .mjtc-support-ticket-cont .box-1 .top-sec .top-sec-left-heading,
    .mjtc-support-ticket-cont .box-2 .top-sec .top-sec-left-heading,
    .mjtc-support-ticket-cont .box-3 .top-sec .top-sec-left-heading,
    .mjtc-support-ticket-cont .box-4 .top-sec .top-sec-left-heading,
    .mjtc-support-ticket-cont .box-5 .top-sec .top-sec-left-heading,
    .mjtc-support-ticket-cont .box-1 .mid-sec-left .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-2 .mid-sec-left .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-3 .mid-sec-left .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-4 .mid-sec-left .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-5 .mid-sec-left .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-1 .top-sec .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-2 .top-sec .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-3 .top-sec .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-4 .top-sec .top-sec-left-txt,
	.mjtc-support-ticket-cont .box-5 .top-sec .top-sec-left-txt {color:$color7}
    .mjtc-support-ticket-cont .box-1 {background-image: linear-gradient(to right, #1dc98d , #159667);}
	.mjtc-support-ticket-cont .box-2 {background-image: linear-gradient(to right, #2c88dd , #2168A2);}
	.mjtc-support-ticket-cont .box-3 {background-image: linear-gradient(to right, #484562 , #3D355A);}
	.mjtc-support-ticket-cont .box-3.box-6 {background-image: linear-gradient(to right, #f8a312 , #db951e);}
    .mjtc-support-ticket-cont .box-4 {background-image: linear-gradient(to right, #e24d4d , #B82B2B);}
    .mjtc-support-ticket-cont .box-5 {background-image: linear-gradient(to right, #6d2b6f , #621166);}
    .mjtc-support-cont-main-wrapper .mjtc-support-cont-wrapper, .mjtc-support-cont-wrapper1 {border: 1px solid $color5}
    .mjtc-support-ticket-detail-wrapper-color, .mjtc-support-cont-wrapper-color {background-color: #fff;}
    .mjtc-support-breadcrumps span {color:$color7}
/*Top Header*/

/*addons*/
.mjtc-support-header-link {color: $color1 !important}
.mjtc-support-header-link:hover {color: $color2 !important}
/* Error Message Page */
    div.mjtc-support-messages-data-wrapper span.mjtc-support-messages-main-text {color:$color4;}
    div.mjtc-support-messages-data-wrapper span.mjtc-support-messages-block_text {color:$color4;}
    span.mjtc-support-user-login-btn-wrp a.mjtc-support-login-btn{background-color:$color1;color:$color7;border: 1px solid $color5;}
	span.mjtc-support-user-login-btn-wrp a.mjtc-support-login-btn:hover{border-color: $color2;}
    span.mjtc-support-user-login-btn-wrp a.mjtc-support-register-btn{background-color:$color2;color:$color7;border: 1px solid $color5;}
	span.mjtc-support-user-login-btn-wrp a.mjtc-support-register-btn:hover{border-color: $color1;}
	div.MJTC_errors span.error{color:#871414;border:1px solid #871414;background-color: #ffd2d3;}
/* Error Message Page */
/* multiform */
    div#multiformpopup div.ms-multiformpopup-header{background: $color1;color:$color7;}
    div#multiformpopup div.mjtc-support-table-body div.mjtc-support-multiform-row {border: 1px solid $color5;background: #f5f5f5;}
    div#multiformpopup div.mjtc-support-table-body div.mjtc-support-multiform-row:hover {border: 1px solid $color1;background: $color7;}
    div#multiformpopup div.mjtc-support-table-body div.mjtc-support-multiform-row div.mjtc-support-table-body-col{border-top: 1px solid $color5;}
    div#multiformpopup div.mjtc-support-table-body div.mjtc-support-multiform-row div.mjtc-support-table-body-col {color: $color1;}
    div#multiformpopup div.mjtc-support-table-body div.mjtc-support-multiform-row:hover div.mjtc-support-table-body-col {color: $color2;}
    div#multiformpopup div.mjtc-support-table-body div.mjtc-support-multiform-row div.mjtc-support-table-body-col:first-child{color: $color2;}
    div#multiformpopup div.mjtc-support-table-body div.mjtc-support-multiform-row:hover div.mjtc-support-table-body-col:first-child{color: $color1;}
    div#multiformpopup div.mjtc-support-table-body div.mjtc-support-multiform-row div.mjtc-support-table-body-col:last-child{color: #6c757d;}
    div#multiformpopup div.mjtc-support-table-body div.mjtc-multiformpopup-link-wrp{display:none;}
/* multiform */
/* Feedbacks */
	div.mjtc-support-feedback-heading{border: 1px solid $color5;background-color: $color2;color: $color7;}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list {border:1px solid $color5;}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list div.ms-feedback-det-list-top, div.mjtc-support-haeder, div.mjtc-support-haeder-tickets {border-bottom: 1px solid $color5;}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list div.ms-feedback-det-list-top div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-top {}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list div.ms-feedback-det-list-top div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-top div.ms-feedback-det-list-data-top-title {color: $color4;}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list div.ms-feedback-det-list-top div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-top div.ms-feedback-det-list-data-top-val {color: $color4;}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list div.ms-feedback-det-list-top div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-top div.ms-feedback-det-list-data-top-val a.ms-feedback-det-list-data-top-val-txt {color: $color2;}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list div.ms-feedback-det-list-top div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-btm div.ms-feedback-det-list-datea-btm-rec div.ms-feedback-det-list-data-btm-title{color: $color4;}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list div.ms-feedback-det-list-top div.ms-feedback-det-list-data-wrp div.ms-feedback-det-list-data-btm div.ms-feedback-det-list-datea-btm-rec div.ms-feedback-det-list-data-btm-val{color: $color4;}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list div.ms-feedback-det-list-btm div.ms-feedback-det-list-btm-title{color:$color4;}
	div.ms-feedback-det-wrp  div.ms-feedback-det-list div.ms-feedback-det-list-img-wrp{}
/* Feedbacks */
/* Existing colors */
	div.mjtc-support-body-data-elipses a{color:$color2;text-decoration:none;}
	div.mjtc-support-detail-wrapper div.mjtc-support-openclosed{background:$color6;color:$color4;border-right:1px solid $color5;}
	div#records div.ms_userpages a.ms_userlink:hover{background: $color2;color:$color7;}
	span.ms_userlink.selected{background: $color2;color: $color7;}
	/* Pagination */
	div.tablenav div.tablenav-pages{border:1px solid #f1f1fc;width:100%;}
    div.tablenav div.tablenav-pages span.page-numbers.current{background: $color7;color: $color2;border: 1px solid $color1;padding:11px 20px;line-height: initial;display: inline-block;}
    div.tablenav div.tablenav-pages a.page-numbers:hover{background:$color7;color:$color1;border: 1px solid $color5;text-decoration: none;}
    div.tablenav div.tablenav-pages a.page-numbers{background: $color7; /* Old browsers */background: -moz-linear-gradient(top,  $color7 0%, #f2f2f2 100%); /* FF3.6+ */background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,$color7), color-stop(100%,#f2f2f2)); /* Chrome,Safari4+ */background: -webkit-linear-gradient(top,  $color7 0%,#f2f2f2 100%); /* Chrome10+,Safari5.1+ */background: -o-linear-gradient(top,  $color7 0%,#f2f2f2 100%); /* Opera 11.10+ */background: -ms-linear-gradient(top,  $color7 0%,#f2f2f2 100%); /* IE10+ */background: linear-gradient(to bottom,  $color7 0%,#f2f2f2 100%); /* W3C */filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='$color7', endColorstr='#f2f2f2',GradientType=0 ); /* IE6-9 */color: $color4;border:1px solid $color5;padding:11px 20px;line-height: initial;display: inline-block;}
    div.tablenav div.tablenav-pages a.page-numbers.next{background: $color1;color: $color7;border: 1px solid $color1;}
	div.tablenav div.tablenav-pages a.page-numbers.prev{background: $color2;color: $color7;border: 1px solid $color2;}
	/* Pagination */
/* Existing colors */
	/******** Widgets ***********/
	div#ms-widget-myticket-wrapper{background: $color3;border:1px solid $color5;}
	div#ms-widget-myticket-wrapper div.ms-widget-myticket-topbar{border-bottom: 1px solid $color5;}
	div#ms-widget-myticket-wrapper div.ms-widget-myticket-topbar span.ms-widget-myticket-subject a{color:$color2;}
	div#ms-widget-myticket-wrapper div.ms-widget-myticket-topbar span.ms-widget-myticket-status{color:$color7;}
	div#ms-widget-myticket-wrapper div.ms-widget-myticket-bottombar span.ms-widget-myticket-priority{color: $color7;}
	div#ms-widget-myticket-wrapper div.ms-widget-myticket-bottombar span.ms-widget-myticket-from span.widget-from{color:$color4;}
	div#ms-widget-myticket-wrapper div.ms-widget-myticket-bottombar span.ms-widget-myticket-from span.widget-fromname{color:$color4;}
	div#ms-widget-mailnotification-wrapper{background:$color3;border:1px solid $color5;}
	div#ms-widget-mailnotification-wrapper img{}
	div#ms-widget-mailnotification-wrapper span.ms-widget-mailnotification-upper{color:$color4;}
	div#ms-widget-mailnotification-wrapper span.ms-widget-mailnotification-upper span.ms-widget-mailnotification-created{color:$color4;}
	div#ms-widget-mailnotification-wrapper span.ms-widget-mailnotification-upper span.ms-widget-mailnotification-new{color:#0752AD;}
	div#ms-widget-mailnotification-wrapper span.ms-widget-mailnotification-upper span.ms-widget-mailnotification-replied{color:#ED6B6D;}
	div.ms-visitor-message-wrapper{border:1px solid $color5;}
	div.ms-visitor-message-wrapper img{border-right:1px solid $color5}
	div.feedback-sucess-message{border:1px solid $color5;}
	div.feedback-sucess-message span.feedback-message-text{border-top:1px solid $color5;}
	div.mjtc-support-thread-wrapper div.mjtc-support-thread-upperpart a.ticket-edit-reply-button{border:1px solid $color2;background:$color3;color:$color2;}
	div.mjtc-support-thread-wrapper div.mjtc-support-thread-upperpart a.ticket-edit-time-button{border:1px solid $color5;background:$color3;color:$color4;}
	span.mjtc-support-value.mjtc-support-creade-via-email-spn{border:1px solid $color5;background:$color3;color:$color4;}

    /* Social Login */
    .mjtc-support-sociallogin .mjtc-support-sociallogin-heading {color: $color4;}

	/*Custom Fields*/
	input.custom_date{background-color:$color7;border: 1px solid $color5;}
	select.mjtc-support-custom-select{background-color:$color3;border: 1px solid $color5;}
	div.mjtc-support-custom-radio-box{background-color:$color7;border: 1px solid $color5;}
	div.mjtc-support-custom-radio-box label{color:$color4;}
	div.mjtc-support-radio-box{border: 1px solid $color5;background-color:$color7;}
	div.mjtc-support-radio-box label{color:$color4;}
	 .mjtc-support-custom-textarea{border: 1px solid $color5;background-color:$color7;}
	 span.mjtc-attachment-file-box{border: 1px solid $color5;background-color:$color7;}

	div.mjtc-support-table-body div.mjtc-support-data-row{border:1px solid  $color5;border-top:none}
  	div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col{}
	div.mjtc-support-table-header{background-color:$color2;border:1px solid  $color5;}
  	div.mjtc-support-table-header div.mjtc-support-table-header-col{color: $color7;}
  	div.mjtc-support-table-header div.mjtc-support-table-header-col:last-child{border-right:none;}
  	div.mjtc-support-downloads-wrp div.mjtc-support-downloads-heading-wrp{background-color: $color2;border:1px solid $color5;color: $color7;}

    /* Majestic Support Woocommerce */
  	.mjtc-support-wc-order-box .mjtc-support-wc-order-item .mjtc-support-wc-order-item-title{
        color: ".$color1.";
    }
    .mjtc-support-wc-order-box .mjtc-support-wc-order-link{
        background-color: ".$color2.";
        color: ".$color7.";
    }
    div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field .mjtc-support-custom-terms-and-condition-box{border: 1px solid $color5;background:$color7;color: $color4;}
    div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field .mjtc-support-custom-terms-and-condition-box label {color: $color4;}
    div.mjtc-support-add-form-wrapper div.mjtc-support-from-field-wrp div.mjtc-support-from-field .helptopic-no-rec{border: 1px solid $color5;background:$color7;color: $color4;}
    .termsandconditions_link_anchor {color: $color4;}

    div.ms-main-up-wrapper a.mjtc-support-delete-attachment {color:$color7;text-decoration:none}
    .mjtc-support-recaptcha{background-color:$color7; border:1px solid $color5;color:$color4;}
    /*responsive*/
    @media (max-width: 782px){
        div.mjtc-support-wrapper div.mjtc-support-data1 {border-top: 1px solid $color5;}
    }
    @media (max-width: 650px){
        div.mjtc-support-latest-tickets-wrp div.mjtc-support-row div.mjtc-support-first-left {
            border-bottom: 0;
        }
    }

";
if ( is_rtl() ) {
    $result .= "div.mjtc-support-wrapper:hover div.mjtc-support-pic{border-right:0px;border-left:1px solid $color2;}"
            . "div.mjtc-support-wrapper:hover div.mjtc-support-data1{border-left:0px;border-right:1px solid $color2;}"
            . "div.mjtc-support-wrapper div.mjtc-support-pic{border:0px;border-left:1px solid $color5;float:right;}"
            . "div.mjtc-support-wrapper div.mjtc-support-data1{border-left:0px;border-right:1px solid $color5;}"
            . "div.mjtc-support-detail-wrapper div.mjtc-support-topbar div.mjtc-openclosed{float:right;border:0px;border-left: 1px solid $color5;}"
            . "div.mjtc-support-detail-wrapper div.mjtc-support-openclosed{border-right:0px;border-left:1px solid $color5;}"
            . "div.mjtc-support-detail-wrapper div.mjtc-support-topbar div.mjtc-last-left{border-left:0px;border-right: 1px solid $color5;}"
            . "div.mjtc-filter-form-head div{border-right:0px; border-left: 1px solid $color3;}
               div.mjtc-filter-form-data div{border-right:0px; border-left: 1px solid $color5;}"
            . "	div.mjtc-support-body-row-button{border-left:0px;border-right: 1px solid $color5;}"
            . "	div.ms-visitor-message-wrapper img{border-right:none;border-left:1px solid $color5}

            /*My Ticket*/
            div.mjtc-support-detail-box div.mjtc-support-detail-right{border-right: 1px solid $color5;border-left:unset;}
            /*My Ticket*/

            /*Roles*/
            div.mjtc-support-table-header div.mjtc-support-table-header-col{border-left: 1px solid $color5;border-right:unset;}
            div.mjtc-support-table-body div.mjtc-support-data-row div.mjtc-support-table-body-col{border-left: 1px solid $color5;border-right:unset;}
            /*Roles*/

            /*BreadCrumbs*/
            	div.mjtc-support-breadcrumb-wrp .breadcrumb li a::after{border-right-color: #c9c9c9 !important;border-left-color: unset;}
				div.mjtc-support-breadcrumb-wrp .breadcrumb li:first-child a::after{border-right-color:$color2 !important;border-left-color: unset;}
            ";

}
$location = 'left';
$borderradius = '0px 8px 8px 0px';
$padding = '5px 10px 5px 20px';
switch (majesticsupport::$_config['screentag_position']) {
    case 1: // Top left
        $top = "30px";
        $left = "0px";
        $right = "auto";
        $bottom = "auto";
    break;
    case 2: // Top right
        $top = "30px";
        $left = "auto";
        $right = "0px";
        $bottom = "auto";
        $location = 'right';
        $borderradius = '8px 0px 0px 8px';
        $padding = '5px 20px 5px 10px';
    break;
    case 3: // middle left
        $top = "48%";
        $left = "0px";
        $right = "auto";
        $bottom = "auto";
    break;
    case 4: // middle right
        $top = "48%";
        $left = "auto";
        $right = "0px";
        $bottom = "auto";
        $location = 'right';
        $borderradius = '8px 0px 0px 8px';
        $padding = '5px 20px 5px 10px';
    break;
    case 5: // bottom left
        $top = "auto";
        $left = "0px";
        $right = "auto";
        $bottom = "30px";
    break;
    case 6: // bottom right
        $top = "auto";
        $left = "auto";
        $right = "0px";
        $bottom = "30px";
        $location = 'right';
        $borderradius = '8px 0px 0px 8px';
        $padding = '5px 20px 5px 10px';
    break;
}
$result .= '
    div#mjtc-support_screentag{opacity:1;position:fixed;top:'.$top.';left:'.$left.';right:'.$right.';bottom:'.$bottom.';padding:'.$padding.';background:rgba(18, 17, 17, 0.5);z-index:9999;border-radius:'.$borderradius.';}
            div#mjtc-support_screentag img.mjtc-support_screentag_image{margin-'.$location.':10px;display:inline-block;width:40px;height:40px;}
            div#mjtc-support_screentag a.mjtc-support_screentag_anchor{color:'.$color7.';text-decoration:none;}
            div#mjtc-support_screentag span.text{display:inline-block;font-family:sans-serif;font-size:15px;}
        ';
return $result;
?>
