<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('ABSPATH'))
    die('Restricted Access');

$color1 = "#00A9E0";
$color2 = "#0097C9";
$color3 = "#FAFAFA";
$color4 = "#64676A";
$color5 = "#D4D4D5";
$color6 = "#F0F0F0";
$color7 = "#FFFFFF";
$color8 = "#3c3435";
$color9 = "#D34034";
$color10 = adjustBrightness($color1, -30);

function adjustBrightness($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));
    // Normalize into a six character long hex string
    $hex = jsjobslib::jsjobs_str_replace('#', '', $hex);
    if (jsjobslib::jsjobs_strlen($hex) == 3) {
        $hex = jsjobslib::jsjobs_str_repeat(jsjobslib::jsjobs_substr($hex, 0, 1), 2) . jsjobslib::jsjobs_str_repeat(jsjobslib::jsjobs_substr($hex, 1, 1), 2) . jsjobslib::jsjobs_str_repeat(jsjobslib::jsjobs_substr($hex, 2, 1), 2);
    }
    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';
    foreach ($color_parts as $color) {
        $color = hexdec($color); // Convert to decimal
        $color = max(0, min(255, $color + $steps)); // Adjust color
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    }
    return $return;
}
?>
<style >
    div.js-jobs-resume-apply-now-visitor{border:1px solid <?php echo esc_attr($color1); ?>;}
    input#jsjobs-login-btn{background: <?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div.jsjobs-button-search input#btnsubmit-search,div.jsjobs-button-search input#reset{background: <?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#jsjob-search-popup span.popup-title, div#jsjobs-listpopup span.popup-title{background: <?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper a{color:<?php echo esc_attr($color2); ?>;}
    div#jsjobs-pagination span.page-numbers.current{color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-pagination a.page-numbers.next{background:<?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-pagination a.page-numbers.prev{background:<?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div.page_heading a.additem{border:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color4); ?>;background:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.page_heading a.additem:hover{border:1px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewrow div.quickviewrow span.visitor-message{border:1px solid #F8E69C;background:#FEFED8;color:#444442;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewrow div.quickviewhalfwidth input[type="email"] ,
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewrow div.quickviewhalfwidth input[type="text"] {border:1px solid <?php echo esc_attr($color5); ?>;color: <?php echo esc_attr($color4); ?>;background-color:<?php echo esc_attr($color3); ?> ;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewrow div.quickviewhalfwidth label {color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.commentrow textarea {border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewrow div.quickviewfullwidth label {color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewrow div.quickviewfullwidth textarea {border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.commentrow label {color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewlower div.quickviewrow {color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewlower div.quickviewrow span.title {color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.viewcompany-upper-wrapper{background:<?php echo esc_attr($color3); ?>;color:<?php echo esc_attr($color4); ?>;border-bottom:1px solid <?php echo esc_attr($color5); ?>; }
    div#jsjobs-wrapper div.viewcompany-upper-wrapper div.viewcompnay-name{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.viewcompany-upper-wrapper div.viewcompnay-url{border-right:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.viewcompany-upper-wrapper div.viewcompnay-url a{color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.viewcompany-lower-wrapper div.viewcompany-logo{border:1px solid <?php echo esc_attr($color5); ?>;border-left:5px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.viewcompany-lower-wrapper div.viewcompany-data div.data-row{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.viewcompany-lower-wrapper div.viewcompany-data a.contact-detail-button{background:<?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div.bottombutton{border-top:1px solid <?php echo esc_attr($color2); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewlower div.quickviewrow-without-border1 p{border:1px solid <?php echo esc_attr($color5); ?>;color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.bottombutton a{background:<?php echo esc_attr($color6); ?>;color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.bottombutton a:hover{background:<?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewupper{background:<?php echo esc_attr($color3); ?>;border-bottom:1px solid <?php echo esc_attr($color5); ?>;border-top:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewupper span.quickviewcompanytitle a{color: <?php echo esc_attr($color1); ?>;}
    div#jsjob-contentarea div.quickviewupper span.quickviewhalfwidth:first-child{border-right:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewlower span.quickviewtitle{border-bottom:3px solid <?php echo esc_attr($color1); ?>;background:<?php echo esc_attr($color3); ?>;color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewlower div.quickviewrow{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewlower div.quickviewbutton a.quickviewbutton{background:<?php echo esc_attr($color6); ?>;color:<?php echo esc_attr($color4); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewlower div.quickviewbutton a#apply-now-btn{background:<?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewlower div.quickviewbutton a.quickviewbutton.login{background:<?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewlower div.quickviewbutton a.quickviewbutton.login:hover{background:<?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewlower div.quickviewbutton a.quickviewbutton:hover{background:<?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewlower div.quickviewbutton a#apply-now-btn.quickviewbutton.applyvisitor{background:#79b32b;color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewbutton a.resumeaddlink{background: <?php echo esc_attr($color6); ?>;color:<?php echo esc_attr($color8); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-listpopup span.no-resume-span{background: <?php echo esc_attr($color3); ?>;border:1px solid <?php echo esc_attr($color5); ?>; color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewlower div.quickviewbutton a#apply-now-btn.quickviewbutton.applyvisitor:hover{background:#79b32b;color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-listpopup a.no-resume-link{float:left;width:100%;display:inline-block;color:<?php echo esc_attr($color2); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewupper span.quickviewhalfwidth{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewupper span.quickviewhalfwidth-right{color:<?php echo esc_attr($color4); ?>;border-right:1px solid <?php echo esc_attr($color5); ?>; }
    div#jsjob-search-popup div.js-searchform-value input {border:1px solid <?php echo esc_attr($color5); ?>;color: <?php echo esc_attr($color4); ?>}
    div#jsjob-search-popup form#job_form div.jsjob-contentarea div#jsjobs-hide div.jsjobs-searchwrapper div.js-form-wrapper input,
    div#jsjob-search-popup form#job_form div.jsjob-contentarea div#jsjobs-hide div.jsjobs-searchwrapper div.js-form-wrapper select {border:1px solid <?php echo esc_attr($color5); ?>;color: <?php echo esc_attr($color4); ?>;background: <?php echo esc_attr($color7); ?>}
    div#jsjob-search-popup div.js-searchform-title {color:<?php echo esc_attr($color8); ?>; }
    div#jsjobs-refine-actions div.js-form button#submit_btn{color:<?php echo esc_attr($color7); ?>;border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-refine-actions div.js-form button#reset_btn{color:<?php echo esc_attr($color8); ?>;border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color6); ?>;}
    div#jsjobs-popup{background: <?php echo esc_attr($color7); ?>;border-bottom:10px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-popup span.popup-title{background-color: <?php echo esc_attr($color1); ?>;color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-popup div.popup-row{border-bottom: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-popup div.popup-row:first-child{border-top: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-popup div.popup-row.button a.proceed{color:<?php echo esc_attr($color7); ?>;border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-popup div.popup-row.button a.cancel{color:<?php echo esc_attr($color8); ?>;border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color6); ?>;}
    div#jsjobs-popup div.popup-row span.title{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-popup div.popup-row span.value{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.department-content-data{border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.department-content-data span.upper-app-title{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.department-content-data div.data-upper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.department-content-data div.data-icons img.icon-img {border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color6); ?>;}
    div#jsjobs-wrapper div.department-content-data div.data-icons{border-left:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.department-content-data div.data-lower a{color:<?php echo esc_attr($color2); ?>;}
    div#jsjobs-wrapper div.department-content-data div.data-lower span.lower-text1 a{color:<?php echo esc_attr($color2); ?>;}
    div#jsjobs-wrapper div.department-content-data div.data-lower span.title{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.department-content-data div.data-lower span.get-status.red{color: #E22828;}
    div#jsjobs-wrapper div.department-content-data div.data-lower span.get-status.green{color: #87D554;}
    div#jsjobs-wrapper div.department-content-data div.data-lower span.get-status.orange{color: #FF9900;}
    div#jsjobs-wrapper div#department-name {border-bottom:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#department-company{border-bottom:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#department-disc{border-bottom:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper span.view-department-title{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper span.wrapper-text{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper span.wrapper-text1{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.cover-letter-content-data{border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.cover-letter-content-data span.datecreated{color:<?php echo esc_attr($color8); ?>;border-left:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.cover-letter-content-data div.data-upper{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.cover-letter-content-data div.data-icons img.icon-img {border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color6); ?>;}
    div#jsjobs-wrapper div.cover-letter-content-data div.data-icons{border-left:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#cover-letter-wrapper-title {border-bottom:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#cover-letter-wrapper-disc{border-bottom:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper span.cover-letter-title{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper span.wrapper-text{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper span.wrapper-text1{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.type-wrapper div.jobs-by-type-wrapper{border: 1px solid <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color3); ?>;color: <?php echo esc_attr($color4); ?>;font-size: 14px;}
    div#jsjobs-wrapper div.type-wrapper div.jobs-by-type-wrapper:hover{border-color: <?php echo esc_attr($color1); ?>; cursor: pointer;}
    div#jsjobs-wrapper div.type-wrapper span.totat-jobs:hover{border-color: <?php echo esc_attr($color1); ?>; cursor: pointer;}
    div#jsjobs-wrapper div#popup-main div#popup-bottom-part {color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#my-jobs-header ul li{border: 1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color7); ?>;background-color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div#my-jobs-header ul li a{color:<?php echo esc_attr($color7); ?>}
    div#jsjobs-wrapper div ul li a.selected{background-color:<?php echo esc_attr($color1); ?>}
    div#jsjobs-wrapper div.my-jobs-data{border:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.my-jobs-data span.fir a{background-color:white;border:2px solid <?php echo esc_attr($color5); ?>;border-left:4px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.my-jobs-data div.data-bigupper div.big-upper-upper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.my-jobs-data div.data-bigupper span.title {color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.my-jobs-data div.data-bigupper div.big-upper-upper a{color: <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.my-jobs-data div.data-bigupper div.big-upper-upper div.headingtext{color:<?php echo esc_attr($color2); ?>;}
    div#jsjobs-wrapper div.my-jobs-data div.data-bigupper span.bigupper-jobtotal {color:<?php echo esc_attr($color4); ?>;background-color:<?php echo esc_attr($color3); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.my-jobs-data div.data-big-lower {background-color:<?php echo esc_attr($color3); ?>;border-top:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.my-jobs-data div.data-big-lower img.big-lower-img {background-color:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.my-jobs-data div.data-bigupper a{color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.my-jobs-data div.data-bigupper div.big-upper-upper span.title{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.my-jobs-data div.data-big-lower div.big-lower-data-icons img.icon-img {border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color6); ?>;}
    div#jsjobs-wrapper div.my-jobs-data div.data-big-lower div.big-lower-data-icons span.icon-text-box{border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color6); ?>;}
    div#jsjobs-wrapper div.my-jobs-data div.data-big-lower div.big-lower-data-icons span.icons-resume{color:<?php echo esc_attr($color7); ?>; background-color:<?php echo esc_attr($color1); ?>; border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.my-jobs-data div.data-bigupper div.big-upper-upper span.buttonu {color:<?php echo esc_attr($color4); ?>;background-color:<?php echo esc_attr($color3); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#my-jobs-header ul li img#posted-img{border:1px solid white;}
    div#jsjobs-wrapper div#my-jobs-header ul li:hover{background-color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.search-wrapper-content-data{border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.search-wrapper-content-data span.upper-app-title{color:<?php echo esc_attr($color8); ?>;border-right:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.search-wrapper-content-data div.data-upper{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.search-wrapper-content-data div.data-icons img.icon-img {border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color6); ?>;}
    div#jsjobs-wrapper div.search-wrapper-content-data div.data-icons{border-left:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#my-resume-header ul li{border: 1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color7); ?>;background-color:<?php echo esc_attr($color8); ?>;} div.my-resume-data{border:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.my-resume-data span.fir a {background-color:white;border:2px solid <?php echo esc_attr($color5); ?>;border-left:4px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.my-resume-data div.data-bigupper div.big-upper-upper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.my-resume-data div.data-bigupper div.big-upper-upper span.headingtext{color: <?php echo esc_attr($color2); ?>}
    div#jsjobs-wrapper div.my-resume-data div.data-bigupper div.big-upper-upper span.buttonu{color:<?php echo esc_attr($color4); ?>;background-color:<?php echo esc_attr($color3); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.my-resume-data div.data-big-lower {background-color:<?php echo esc_attr($color3); ?>;border-top:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.my-resume-data div.data-big-lower img.big-lower-img {background-color:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.my-resume-data div.data-bigupper span.title{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.my-resume-data div.data-bigupper div.big-upper-upper span.title{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div#my-resume-header ul li:hover{background-color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.my-resume-data div.data-bigupper span.bigupper-jobtotal{background-color:<?php echo esc_attr($color3); ?>;border-top:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.my-resume-data div.data-big-lower div.big-lower-data-icons img.icon-img {border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div#my-resume-header ul li img#posted-img{border:1px solid white;}
    div#jsjobs-wrapper div#my-resume-header ul li a{color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#my-resume-header ul li a:focus{background-color:<?php echo esc_attr($color1); ?>}
    div#jsjobs-wrapper div.my-resume-data div.data-big-lower div.big-lower-data-icons span.icon-text-box{border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.my-resume-data div.data-big-lower div.big-lower-data-icons span.icons-resume{color:white;background-color:#2993CF;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.my-resume-data div.data-bigupper div.big-upper-lower div.big-upper-lower1 span.lower-upper-title{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.message-content-data{border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color3); ?>;color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.message-content-data div.data-left span.upper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.message-content-data div.data-left span.lower{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.message-content-data div.data-left{border-right:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.message-content-data div.data-left span.title{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.message-content-data div.data-left span.lower a { color:<?php echo esc_attr($color1); ?>}
    div#jsjobs-wrapper div.message-content-data div.data-right a.text-right{border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color6); ?>;color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.message-content-data div.data-right a.text-right:hover{background-color:<?php echo esc_attr($color2); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div.credit-wrapper{border-bottom: 1px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.credit-wrapper div.credit-content-data{border: 1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.credit-wrapper div.credit-content-data span.data-top{border-bottom: 1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.credit-wrapper div.credit-content-data span.data-top span.top-left{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.credit-wrapper div.credit-content-data span.data-top span.right-discount{background-color:#3AB31B;color:white;}
    div#jsjobs-wrapper div.credit-wrapper div.credit-content-data span.data-top span.top-right span.discounted-amount{text-decoration-color:red;}
    div#jsjobs-wrapper div.credit-wrapper div.credit-content-data span.data-top span.top-right span.net-amount{background-color: <?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div.credit-wrapper div.credit-content-data span.data-middle{border-bottom: 1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color8); ?>;background-color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div.credit-log-wrapper span.desc a{color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.credit-wrapper div.credit-content-data span.data-middle span.middle-right{color: <?php echo esc_attr($color8); ?>;border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.credit-wrapper div.credit-content-data span.data-bottom {color: <?php echo esc_attr($color4); ?>;border-bottom: 1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color3); ?>; }
    div#jsjobs-wrapper div.credit-wrapper div.credit-content-data span.bottom-expiry {color: <?php echo esc_attr($color4); ?>;border-bottom:1px solid <?php echo esc_attr($color5); ?>; background-color:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.purchase-history-wrapper {border-top: 1px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.purchase-history-wrapper:last-child{border-bottom: 1px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.purchase-history-wrapper div.purchase-history-data{border: 1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color8); ?>;background-color:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.purchase-history-wrapper div.purchase-history-data span.data-credit{border-top: 1px solid <?php echo esc_attr($color5); ?>;border-bottom: 1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color8); ?>;background: #FFF;}
    div#jsjobs-wrapper div.purchase-history-wrapper div.purchase-history-data span.data-price{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.purchase-history-wrapper div.purchase-history-data span.data-price span.amount{color:<?php echo esc_attr($color7); ?>;background: <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.purchase-history-wrapper div.purchase-history-data span.data-created{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.purchase-history-wrapper div.purchase-history-data span.data-status{border-left:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.purchase-header div.total img{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.purchase-header div.spent img{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.purchase-header div.remaining img{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.purchase-header div.total{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.purchase-header div.spent{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.purchase-header div.remaining{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.purchase-header span.text{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.purchase-header span.number{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.page_heading span.expire {border: 1px solid <?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color8); ?>;background-color:<?php echo esc_attr($color3); ?>;border-bottom:none;}
    div#jsjobs-wrapper div.log-header{border-bottom: 1px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.log-header div.total img{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.log-header div.spent img{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.log-header div.remaining img{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.log-header div.total{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.log-header div.spent{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.log-header div.remaining{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.log-header span.text{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.log-header span.number{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.credit-log-wrapper{border: 1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color8); ?>;background-color:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.credit-log-wrapper { background-color: <?php echo esc_attr($color3); ?>;border: 1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.credit-log-wrapper span.date-time{border-right: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.credit-log-wrapper span.desc{border-left: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.credit-log-wrapper span.cost{color:<?php echo esc_attr($color7); ?>;background-color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper input#save{background:none; color:<?php echo esc_attr($color7); ?>;background-color:<?php echo esc_attr($color1); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#comments textArea {border:1px solid <?php echo esc_attr($color5); ?>}
    div#jsjobs-wrapper div#comments div.email-feilds input {border:1px solid <?php echo esc_attr($color5); ?>}
    div#jsjobs-wrapper span#popup_coverletter_title{border-bottom: 1px solid <?php echo esc_attr($color5); ?>;color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div#popup-main div#popup-bottom-part{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#popup-main-outer{background-color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#popup-main{border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#popup-main span.popup-top{width:100%;display:inline-block;background-color:<?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>; }
    div#jsjobs-wrapper div.company-wrapper div.company-upper-wrapper div.company-detail div.company-detail-lower div.company-detail-lower-left span.js-text{color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper span#popup-coverletter_title{border-bottom: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#popup-main span.popup-top span#popup_title{color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#popup-main div#popup-bottom-part{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#popup-main-outer{background-color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#popup-main{border:1px solid <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper span#popup-coverletter_title{border-bottom: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#popup-main span.popup-top span#popup_title{color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#save-button{border-top:2px solid <?php echo esc_attr($color2); ?>;} 
    div#jsjobs-wrapper div#send-message-wrapper div.top-data {border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#send-message-wrapper div.top-data span.data-right-part span.right-top {border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#send-message-wrapper div.top-data span.data-right-part {color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#send-message-wrapper div.top-data span.top-data-img div{border:1px solid <?php echo esc_attr($color5); ?>;border-left:4px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#send-message-wrapper div.top-data span.title{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div#send-message-wrapper div.message-subject span.subject-text{color:<?php echo esc_attr($color4); ?>;background-color:<?php echo esc_attr($color3); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#send-message-wrapper div.message-subject span.subject{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div#send-message-wrapper div.top-data span.data-right-part a{color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#send-message-wrapper span.history-title{color:<?php echo esc_attr($color7); ?>;background-color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div#send-message-wrapper div.message-history span.message-my{border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div#send-message-wrapper div.message-history span.message-my.mesend{background-color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#send-message-wrapper div.message-history span.message-my span.message-created{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#send-message-wrapper div.message-history span.message-my span.message-desc{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#send-message-wrapper div.message-history span.message-my span.message-data span.message-title{color:<?php echo esc_attr($color8); ?>;background-color:<?php echo esc_attr($color3); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#send-message-wrapper div.message-history span.message-my span.message-other span.message-title{color:<?php echo esc_attr($color7); ?>;background-color:<?php echo esc_attr($color1); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#send-message-wrapper div.message-history span.message-my span.message-admin span.message-title {color: <?php echo esc_attr($color7); ?>;background-color: <?php echo esc_attr($color8); ?>;border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#comments{border-top:1px solid <?php echo esc_attr($color5); ?>;}
    div.js_job_error_messages_wrapper{border:1px solid <?php echo esc_attr($color1); ?>; color: <?php echo esc_attr($color7); ?>;background:<?php echo esc_attr($color7); ?>;}
    div.js_job_error_messages_wrapper div.message2{box-shadow: 0px 3px 3px 2px <?php echo esc_attr($color5); ?>; background:<?php echo esc_attr($color1); ?>; color: <?php echo esc_attr($color7); ?>;}
    div.js_job_error_messages_wrapper div.message3{box-shadow: 0px 3px 3px 2px <?php echo esc_attr($color5); ?>; background:#B81D20; color: <?php echo esc_attr($color7); ?>;}
    div.js_job_error_messages_wrapper div.footer{background: <?php echo esc_attr($color3); ?>; border-top: 1px solid <?php echo esc_attr($color5); ?>;}
    div.js_job_error_messages_wrapper div.message1 span{ font-size: 30px; font-weight: bold;color:<?php echo esc_attr($color8); ?>}
    div.js_job_error_messages_wrapper div.message2 span.img{border:1px solid <?php echo esc_attr($color1); ?>;}
    div.js_job_error_messages_wrapper div.message2 span.message-text{font-size: 24px; font-weight: bold; }
    div.js_job_error_messages_wrapper div.message3 span.img{border:1px solid <?php echo esc_attr($color1); ?>;}
    div.js_job_error_messages_wrapper div.message3 span.message-text{font-size: 24px; font-weight: bold; }
    div.js_job_error_messages_wrapper div.footer a{background: <?php echo esc_attr($color6); ?>; color:<?php echo esc_attr($color8); ?> !important;border: 1px solid <?php echo esc_attr($color5); ?>; font-size: 16px;}
    div.js_job_error_messages_wrapper div.footer a:hover{background: <?php echo esc_attr($color1); ?>; color:<?php echo esc_attr($color7); ?> !important;}
    div#jsjobs-wrapper div#companies-wrapper div.view-companies-wrapper div.company-upper-wrapper div.company-detail div.company-detail-lower span.js-get-title{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.credits-log-wrapper div.credits-log-header{border-bottom: 1px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.credits-log-wrapper div.credits-log-header div.block{background: <?php echo esc_attr($color3); ?>; border: 1px solid <?php echo esc_attr($color5); ?>; border-radius: 5px;}
    div#jsjobs-wrapper div.credits-log-wrapper div.credits-log-header div.block img{border-radius: 3px;}
    div#jsjobs-wrapper div.credits-log-wrapper div.credits-log-header div.block span.figure{font-size: 22px; display: inline-block; text-align: left; font-weight: bold; color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.credits-log-wrapper div.credits-log-header div.block span.text{font-size: 22px;  color: <?php echo esc_attr($color4); ?>; display: inline-block; text-align: left;}
    div#jsjobs-wrapper div.credits-log-wrapper div.log-list-wrapper{background: <?php echo esc_attr($color3); ?>; border: 1px solid <?php echo esc_attr($color5); ?>;font-size: 20px;  color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.credits-log-wrapper div.log-list-wrapper span:nth-child(1){border-right: 2px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.credits-log-wrapper div.log-list-wrapper span.upper{border-right: 2px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.company-wrapper div.company-upper-wrapper div.company-detail div.company-detail-upper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.company-wrapper div.company-upper-wrapper div.company-detail div.company-detail-lower div.company-detail-lower-left {color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.company-wrapper div.company-upper-wrapper div.company-detail div.company-detail-lower div.company-detail-lower-left span.js-text{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.company-wrapper div.company-upper-wrapper div.company-detail div.company-detail-lower div.company-detail-lower-left span.category-text{color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.company-wrapper div.company-upper-wrapper div.company-detail div.company-detail-lower div.company-detail-lower-right span.status-text{color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.company-wrapper div.company-upper-wrapper div.company-detail div.company-detail-lower div.company-detail-lower-left span.get-category{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.company-wrapper div.company-upper-wrapper div.company-detail div.company-detail-upper span.company-date{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.company-wrapper div.company-lower-wrapper span.company-address{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.page_heading{color:<?php echo esc_attr($color8); ?>;border-bottom:2px solid <?php echo esc_attr($color2); ?>;}
    div#jsjobs-wrapper span.applied-resume-count{background-color: <?php echo esc_attr($color3); ?>;color: <?php echo esc_attr($color8); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.company-wrapper{border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.company-wrapper div.company-upper-wrapper div.company-img a{ border: 1px solid <?php echo esc_attr($color5); ?>; border-left: 4px solid <?php echo esc_attr($color1); ?>; background-color: white;}
    div#jsjobs-wrapper div.company-wrapper div.company-upper-wrapper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.company-wrapper div.company-lower-wrapper div.company-lower-wrapper-right div.button{ border: 1px solid; border-color: <?php echo esc_attr($color5); ?>; background-color: <?php echo esc_attr($color6); ?>; }
    div#jsjobs-wrapper div.company-wrapper div.company-lower-wrapper{background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.company-wrapper div.company-upper-wrapper div.company-detail div.company-detail-upper div.company-detail-upper-left span.gold{background: #CC9900; color: white; border-radius: 6px; font-weight: bold; font-size: 10px;}
    div#jsjobs-wrapper div.company-wrapper div.company-upper-wrapper div.company-detail div.company-detail-lower div.company-detail-lower-right span.get-status.red{color:  #E22828;}
    div#jsjobs-wrapper div.company-wrapper div.company-upper-wrapper div.company-detail div.company-detail-lower div.company-detail-lower-right span.get-status.green{color: #87D554;}
    div#jsjobs-wrapper div#companies-wrapper div.view-companies-wrapper div.company-upper-wrapper div.company-detail div.company-detail-upper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#companies-wrapper div.view-companies-wrapper div.company-upper-wrapper div.company-detail div.company-detail-lower span.website-url-text{font-weight: bold; color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div#companies-wrapper div.view-companies-wrapper div.company-upper-wrapper div.company-detail div.company-detail-lower a.get-website-url{ color: <?php echo esc_attr($color1); ?>; text-decoration: none;}
    div#jsjobs-wrapper div#companies-wrapper div.view-companies-wrapper div.company-lower-wrapper span.company-address{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#companies-wrapper div.view-companies-wrapper{border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#companies-wrapper div.view-companies-wrapper div.company-upper-wrapper div.company-img a{border: 1px solid <?php echo esc_attr($color5); ?>; border-left: 4px solid <?php echo esc_attr($color1); ?>; background-color: white;}
    div#jsjobs-wrapper div#companies-wrapper div.view-companies-wrapper div.company-upper-wrapper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#companies-wrapper div.view-companies-wrapper div.company-lower-wrapper div.company-lower-wrapper-right a.viewall-jobs{border: 1px solid <?php echo esc_attr($color5); ?>;background-color: <?php echo esc_attr($color6); ?>; font-size: 12px;color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div#companies-wrapper div.view-companies-wrapper div.company-lower-wrapper div.company-lower-wrapper-right a.viewall-jobs:hover{color: 1px solid <?php echo esc_attr($color5); ?>; background-color: <?php echo esc_attr($color1); ?>;color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#companies-wrapper div.view-companies-wrapper div.company-lower-wrapper{background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div#companies-wrapper div.view-companies-wrapper div.company-upper-wrapper div.company-detail div.company-detail-upper span.gold{background: #CC9900; color: white; border-radius: 6px; font-weight: bold; font-size: 10px;}
    div#jsjobs-wrapper div#companies-wrapper div.view-companies-wrapper div.company-upper-wrapper div.company-detail div.company-detail-upper span.feature{background: #2993CF; color: white; border-radius: 4px; font-weight: bold; font-size: 10px; padding: 1px;}
    div#jsjobs-wrapper div#companies-wrapper div.filter-wrapper{border-bottom: 2px solid <?php echo esc_attr($color2); ?>;}
    div#jsjobs-wrapper div.folder-wrapper{border:1px solid <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.folder-wrapper{display: inline-block; width: 100%;}
    div#jsjobs-wrapper div.folder-wrapper div.folder-firsl{border-right: 1px solid <?php echo esc_attr($color5); ?> ;}
    div#jsjobs-wrapper div.folder-wrapper div.folder-firsl span{color:<?php echo esc_attr($color8); ?> ; font-size: 13px; font-weight: bold;}
    div#jsjobs-wrapper div.folder-wrapper div.folder-second span{color:<?php echo esc_attr($color8); ?> ; font-size: 14px; font-weight: bold;}
    div#jsjobs-wrapper div.folder-wrapper div.folder-second span.get-status.red{ color: #E22828}
    div#jsjobs-wrapper div.folder-wrapper div.folder-second span.get-status.green{ color: #87D554}
    div#jsjobs-wrapper div.folder-wrapper div.folder-second span.get-status.orange{ color: #FF9900}
    div#jsjobs-wrapper div.folder-wrapper div.folder-second{border-right: 1px solid <?php echo esc_attr($color5); ?> ;}
    div#jsjobs-wrapper div.folder-wrapper div.folder-third div.button-section div.button{ border: solid 1px <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color6); ?>;}
    div#jsjobs-wrapper div.folder-wrapper div.folder-third div.button-section div.button a{color: <?php echo esc_attr($color4); ?>; font-size: 14px;}
    div#jsjobs-wrapper div.folder-wrapper div.folder-third div.button-section div.button a:hover{color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div.folder-wrapper div.folder-third div.button-section div.resume-button:hover{background: <?php echo esc_attr($color1); ?>; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div.resume-save-search-wrapper{border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.resume-save-search-wrapper span.upper-app-title{color:<?php echo esc_attr($color8); ?>;border-right:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.resume-save-search-wrapper div.data-upper{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.resume-save-search-wrapper div.data-icons img.icon-img {border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color6); ?>;}
    div#jsjobs-wrapper div.resume-save-search-wrapper div.data-icons{border-left:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.resume-save-search-wrapper{border:1px solid <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.resume-save-search-wrapper div.div-left{border-right: 1px solid <?php echo esc_attr($color5); ?> ;}
    div#jsjobs-wrapper div.resume-save-search-wrapper div.div-left span{color:<?php echo esc_attr($color8); ?> ; font-size: 14px; font-weight: bold;}
    div#jsjobs-wrapper div.resume-save-search-wrapper div.div-middel span.created-text{color:<?php echo esc_attr($color8); ?> ; font-size: 14px; font-weight: bold;}
    div#jsjobs-wrapper div.resume-save-search-wrapper div.div-middel span.get-resume-date{color:<?php echo esc_attr($color8); ?> ; font-size: 14px;}
    div#jsjobs-wrapper div.resume-save-search-wrapper div.div-middel{border-right: 1px solid <?php echo esc_attr($color5); ?> ;}
    div#jsjobs-wrapper div.resume-save-search-wrapper div.div-right div.button{ border: solid 1px <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color6); ?>;}
    div#jsjobs-wrapper div.resume-save-search-wrapper div.div-right div.button a{color: <?php echo esc_attr($color4); ?>; font-size: 14px;}
    div#jsjobs-wrapper div.resume-save-search-wrapper div.div-right div.button:hover{background: <?php echo esc_attr($color1); ?>; color: <?php echo esc_attr($color7); ?>;} 
    div#jsjobs-wrapper div.view-folder-wrapper div.name{border-bottom: 1px solid <?php echo esc_attr($color5); ?>; font-size: 16px;}
    div#jsjobs-wrapper div.view-folder-wrapper div.name span.name-text{color: <?php echo esc_attr($color8); ?>; font-weight: bold;}
    div#jsjobs-wrapper div.view-folder-wrapper div.name span.get-name{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.view-folder-wrapper div.description{border-bottom: 1px solid <?php echo esc_attr($color5); ?>; font-size: 16px;}
    div#jsjobs-wrapper div.view-folder-wrapper div.description span.description-text{ color: <?php echo esc_attr($color8); ?>; font-weight: bold; }
    div#jsjobs-wrapper div.view-folder-wrapper div.description span.get-description{color: <?php echo esc_attr($color4); ?>; }
    div#jsjobs-wrapper div.jobs-by-categories-wrapper{border: 1px solid <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color3); ?>;color: <?php echo esc_attr($color4); ?>;font-size: 14px;}
    div#jsjobs-wrapper div.jobs-by-categories-wrapper:hover{border-color: <?php echo esc_attr($color1); ?>; cursor: pointer;}
    div#jsjobs-wrapper div.jobs-by-categories-wrapper span.total-jobs:hover{border-color: <?php echo esc_attr($color1); ?>; cursor: pointer;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-navebar ul li{background:<?php echo esc_attr($color8); ?>; font-size: 14px; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-navebar ul li a{color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-navebar ul li:hover{background:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list{border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-upper-wrapper div.job-img a{border:1px solid <?php echo esc_attr($color5); ?>; border-left: 6px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-upper-wrapper div.job-detail div.job-detail-upper{border-bottom:1px solid  <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-upper-wrapper div.job-detail div.job-detail-upper div.job-detail-upper-left a{color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-upper-wrapper div.job-detail-upper div.job-detail-upper span.job-date{color: <?php echo esc_attr($color3); ?>}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-upper-wrapper div.job-detail div.job-detail-upper span.time-of-job{border: 1px solid <?php echo esc_attr($color5); ?>;background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-upper-wrapper div.job-detail div.job-detail-upper span{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-lower-wrapper span.company-address{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-upper-wrapper div.company-img{ border: 1px solid <?php echo esc_attr($color5); ?>; border-left: 6px solid <?php echo esc_attr($color1); ?>; background-color: white;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-upper-wrapper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-right span.shortlist{color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-lower-wrapper{background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list span.heading{color: <?php echo esc_attr($color8); ?>}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list  div#full-width-top{border-top: 1px solid <?php echo esc_attr($color5); ?>}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list span.get-text{color: <?php echo esc_attr($color4); ?>}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list span.get-text a{color: <?php echo esc_attr($color1); ?>}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-left:<?php echo esc_attr($color8); ?>;font-size: 14px;font-weight: bold;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-left color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-right span.heading{color:<?php echo esc_attr($color8); ?>;font-size: 14px;font-weight: bold;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-right span.get-text{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-right span.btn-shortlist a{background: <?php echo esc_attr($color1); ?>; font-size: 14px; text-decoration: none; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-lower-wrapper div.button{background: <?php echo esc_attr($color1); ?>; font-size: 14px; text-decoration: none; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#my-applied-jobs-wrraper div#my-applied-jobs-list div.jobs-lower-wrapper a.applied-info-button{border:1px solid <?php echo esc_attr($color5); ?>;background: <?php echo esc_attr($color6); ?>;color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div#folder-resume-wrapper{border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-upper-wrapper div.job-img{border:1px solid <?php echo esc_attr($color5); ?>; border-left: 6px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-upper-wrapper div.job-detail div.job-detail-upper{border-bottom:1px solid  <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-upper-wrapper div.job-detail div.job-detail-upper div.job-detail-upper-left a{color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-upper-wrapper div.job-detail-upper div.job-detail-upper span.job-date{color: <?php echo esc_attr($color3); ?>}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-upper-wrapper div.job-detail div.job-detail-upper span.time-of-job{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-upper-wrapper div.job-detail div.job-detail-upper span{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-lower-wrapper span.company-address{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-upper-wrapper div.company-img{ border: 1px solid <?php echo esc_attr($color5); ?>; border-left: 6px solid <?php echo esc_attr($color1); ?>; background-color: white;}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-upper-wrapper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-right a{background: <?php echo esc_attr($color1); ?>; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-lower-wrapper{background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-left span.heading{color:<?php echo esc_attr($color8); ?>;font-size: 14px;font-weight: bold;}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-left span.get-text{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-right span.heading{color:<?php echo esc_attr($color8); ?>;font-size: 14px;font-weight: bold;}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-right span.get-text{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-right span.btn-shortlist a{background: <?php echo esc_attr($color1); ?>; font-size: 14px; text-decoration: none; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-lower-wrapper div.jobs-lower-wrapper-right div.button a{background: <?php echo esc_attr($color1); ?>; font-size: 14px; text-decoration: none; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div.jsjobs-shorlisted-wrapper{border:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.jsjobs-shorlisted-wrapper div.jsdata-icon a{background-color:white;border:2px solid <?php echo esc_attr($color5); ?>;border-left:6px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.jsjobs-shorlisted-wrapper div.data-bigupper div.big-upper-upper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.jsjobs-shorlisted-wrapper div.data-bigupper div.comments{ background: <?php echo esc_attr($color3); ?>; color: <?php echo esc_attr($color4); ?>; border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.jsjobs-shorlisted-wrapper div.data-bigupper div.big-upper-upper span.headingtext{color:blue;}
    div#jsjobs-wrapper div.jsjobs-shorlisted-wrapper div.data-bigupper div.big-upper-upper span.buttonu{border: 1px solid <?php echo esc_attr($color5); ?>;color: <?php echo esc_attr($color8); ?>;background-color:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.jsjobs-shorlisted-wrapper div.data-big-lower {background-color:<?php echo esc_attr($color3); ?>;border-top:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.jsjobs-shorlisted-wrapper div.data-big-lower img.big-lower-img {background-color:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.jsjobs-shorlisted-wrapper div.data-bigupper span.title{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.jsjobs-shorlisted-wrapper div.data-bigupper div.big-upper-upper span.title{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.jsjobs-shorlisted-wrapper div.data-bigupper span.bigupper-jobtotal{background-color:<?php echo esc_attr($color3); ?>;border-top:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.jsjobs-shorlisted-wrapper div.data-big-lower div.big-lower-data-icons a.btn {border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color6); ?>; color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.jsjobs-shorlisted-wrapper div.data-big-lower div.big-lower-data-icons a.btn:last-child{background-color:<?php echo esc_attr($color1); ?>; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list-navebar ul li{background:<?php echo esc_attr($color8); ?>; font-size: 14px; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list-navebar ul li:hover{background:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list{border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-upper-wrapper div.resume-img a{border:1px solid <?php echo esc_attr($color5); ?>; border-left: 6px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-upper-wrapper div.resume-detail div.resume-detail-upper{border-bottom:1px solid  <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-upper-wrapper div.resume-detail div.resume-detail-upper div.resume-detail-upper-left a{color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-upper-wrapper div.resume-detail-upper div.resume-detail-upper span.resume-date{color: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-upper-wrapper div.resume-detail div.resume-detail-upper span.time-of-resume{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-upper-wrapper div.resume-detail div.resume-detail-upper span{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-lower-wrapper span.company-address{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-upper-wrapper div.company-img{ border: 1px solid <?php echo esc_attr($color5); ?>; border-left: 6px solid <?php echo esc_attr($color1); ?>; background-color: white;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-upper-wrapper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-upper-wrapper div.resume-detail div.resume-detail-lower div.resume-detail-lower-right a{background: <?php echo esc_attr($color1); ?>; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-lower-wrapper{background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-upper-wrapper div.resume-detail div.resume-detail-lower span.get-text{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-upper-wrapper div.resume-detail div.resume-detail-lower div.resume-detail-lower-left span.heading{color:<?php echo esc_attr($color8); ?>;font-size: 14px;font-weight: bold;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-upper-wrapper div.resume-detail div.resume-detail-lower div.resume-detail-lower-left span.get-text{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-upper-wrapper div.resume-detail div.resume-detail-lower div.resume-detail-lower-right span.heading{color:<?php echo esc_attr($color8); ?>;font-size: 14px;font-weight: bold;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-upper-wrapper div.resume-detail div.resume-detail-lower div.resume-detail-lower-right span.get-text{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-upper-wrapper div.resume-detail div.resume-detail-lower div.resume-detail-lower-right span.btn-shortlist a{background: <?php echo esc_attr($color1); ?>; font-size: 14px; text-decoration: none; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-lower-wrapper div.resume-lower-wrapper-right div.button a{background: <?php echo esc_attr($color1); ?>; font-size: 14px; text-decoration: none; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume-navebar ul li{background:<?php echo esc_attr($color8); ?>; font-size: 14px; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#comments span.detail span.heading{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div#comments span.detail{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume-navebar ul li:hover{background:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume-navebar ul li a:focus{background:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume-inner-navebar ul li{background:<?php echo esc_attr($color6); ?>; }
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume-inner-navebar ul li a{font-size: 16px; color: <?php echo esc_attr($color8); ?>; text-decoration:none; padding: 10px 20px;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume-inner-navebar ul li a:hover{background:<?php echo esc_attr($color1); ?>; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume-inner-navebar ul li a.selected{background:<?php echo esc_attr($color1); ?>; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume{border:1px solid <?php echo esc_attr($color5); ?>; }
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-upper-left div.job-img{border:2px solid <?php echo esc_attr($color1); ?>; border-radius: 200px; box-shadow: 0px 0px 10px #ECECEC;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-detail div.job-detail-upper{border-bottom:1px solid  <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-detail div.job-detail-upper div.job-detail-upper-left{color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-detail-upper div.job-detail-upper span.job-date{color: <?php echo esc_attr($color3); ?>}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-detail div.job-detail-upper span.time-of-job{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-detail div.job-detail-upper span.created{font-weight: bold;color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-detail div.job-detail-upper span.job-title{color: <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-detail div.job-detail-upper span.job-date{color: <?php echo esc_attr($color4); ?>}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-lower-wrapper span.company-address{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.company-img{ border: 1px solid <?php echo esc_attr($color5); ?>; border-left: 6px solid <?php echo esc_attr($color1); ?>; background-color: white;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper{}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-right a{background: <?php echo esc_attr($color1); ?>; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-lower-wrapper{background: <?php echo esc_attr($color3); ?>;border-top:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-left span.heading{color:<?php echo esc_attr($color8); ?>;font-size: 14px;font-weight: bold;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-left span.get-text{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-right span.heading{color:<?php echo esc_attr($color8); ?>;font-size: 14px;font-weight: bold;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-right span.get-text{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.job-detail-lower-right span.btn-shortlist a{background: <?php echo esc_attr($color1); ?>; font-size: 14px; text-decoration: none; color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-detail div.notes{background: <?php echo esc_attr($color6); ?>; color: <?php echo esc_attr($color8); ?>; border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-lower-wrapper{background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-lower-wrapper div.buttons a{background: <?php echo esc_attr($color6); ?>; color: <?php echo esc_attr($color8); ?>; border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-upper-left div.anchor a.view-resume{background: <?php echo esc_attr($color1); ?>; color: <?php echo esc_attr($color7); ?>; border-radius: 5px; text-decoration: none;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-upper-left div.anchor a.view-coverletter{background: <?php echo esc_attr($color7); ?>; color: <?php echo esc_attr($color8); ?>; border:2px solid <?php echo esc_attr($color1); ?>;  border-radius: 5px; text-decoration: none;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume-inner-navebar{border-bottom: 2px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-upper-left div.job-img img{border: 1px solid <?php echo esc_attr($color5); ?>; border-radius: 50%;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume-inner-navebar a.export-all{ border-radius: 3px; border: 1px solid <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color3); ?>; color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.view-resume-wrapper div.most-upper-wrapper div.img{border: 1px solid <?php echo esc_attr($color5); ?>; border-left: 6px solid <?php echo esc_attr($color2); ?>; }
    div#jsjobs-wrapper div.view-resume-wrapper div.most-upper-wrapper div.right-upper-wrapper div.inner-upper{border-bottom: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.view-resume-wrapper div.most-upper-wrapper div.right-upper-wrapper div.inner-lower div.text{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.view-resume-wrapper div.most-upper-wrapper div.right-upper-wrapper div.inner-lower div.btn span:hover{cursor: pointer;}
    div#jsjobs-wrapper div.view-resume-wrapper div.most-upper-wrapper div.right-upper-wrapper div.inner-lower div.btn span.grayBtn{background: <?php echo esc_attr($color6); ?>; color: <?php echo esc_attr($color8); ?>; border:1px solid <?php echo esc_attr($color5); ?>; font-size: 14px;}
    div#jsjobs-wrapper div.view-resume-wrapper div.most-upper-wrapper div.right-upper-wrapper div.inner-lower div.btn span.blueBtn{background: <?php echo esc_attr($color1); ?>; color: <?php echo esc_attr($color7); ?>; font-size: 14px;}
    div#jsjobs-wrapper div.view-resume-wrapper div.most-upper-wrapper div.right-upper-wrapper div.inner-upper span{color: <?php echo esc_attr($color8); ?>; font-size: 16px; font-weight: bold;}
    div#jsjobs-wrapper div.view-resume-wrapper div.main-heading{border-bottom: 2px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.view-resume-wrapper div.innerHeading{border-bottom: 2px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.view-resume-wrapper div.main-heading img.heading-img{background: <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.view-resume-wrapper div.main-heading span{font-size: 20px; font-weight: bold; color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.view-resume-wrapper div.innerHeading span{font-size: 16px; font-weight: bold; color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.view-resume-wrapper div.detail{border-bottom: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.view-resume-wrapper div.detail span.heading{ font-size: 16px; color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.view-resume-wrapper div.detail span.txt{font-size: 16px; color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.view-resume-wrapper div.detail-blueBorder span.heading{ font-size: 16px; color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.view-resume-wrapper div.detail-blueBorder span.txt{font-size: 16px; color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.view-resume-wrapper div.detail-blueBorder{border-bottom: 1px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div.view-resume-wrapper div.btnSection{border: 1px solid <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color6); ?>; color: <?php echo esc_attr($color8); ?>; font-size: 16px;}
    div#jsjobs-wrapper div.view-resume-wrapper div.btnSection:hover{color: <?php echo esc_attr($color7); ?>; background: <?php echo esc_attr($color1); ?>; cursor: pointer;}
    div#jsjobs-wrapper div.view-resume-wrapper div.resume-page{border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.view-resume-wrapper div.skills-page{border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.control-pannel-header{border-bottom:2px solid <?php echo esc_attr($color2); ?>;}
    div#jsjobs-wrapper div.control-pannel-header span.heading{color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.control-pannel-header span.notify img:hover{cursor: pointer;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.control-pannel-categories{background: <?php echo esc_attr($color3); ?>; }
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.left div.show-items{background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.left div.show-items div.job-wrapper div.img a{border: 1px solid <?php echo esc_attr($color5); ?>; border-left: 6px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.left div.show-items div.job-wrapper{border: 1px solid <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.left div.show-items div.job-wrapper div.upper{border-bottom: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.left div.show-items div.job-wrapper div.upper a{background:none;color: <?php echo esc_attr($color8); ?>; text-decoration: none; font-size: 16px; }
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.left div.show-items div.job-wrapper div.lower span{color: <?php echo esc_attr($color4); ?>;  font-size: 14px; }
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.right div.show-items{background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.right div.show-items div.job-wrapper{background: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.right div.show-items div.job-wrapper div.img a{border: 1px solid <?php echo esc_attr($color5); ?>; border-left: 6px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.right div.show-items div.job-wrapper{border: 1px solid <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.right div.show-items div.job-wrapper div.upper{border-bottom: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.right div.show-items div.job-wrapper div.upper a{background:none;color: <?php echo esc_attr($color1); ?>; text-decoration: none; font-size: 16px; }
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.right div.show-items div.job-wrapper div.header{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.right div.show-items div.job-wrapper div.lower div.resume {color: <?php echo esc_attr($color4); ?>;  font-size: 14px;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.right div.show-items div.job-wrapper div.lower div.email span.heading{color: <?php echo esc_attr($color8); ?>;  font-size: 14px; font-weight: bold;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.right div.show-items div.job-wrapper div.lower div.category span.heading{color: <?php echo esc_attr($color8); ?>;  font-size: 14px; font-weight: bold;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.right div.show-items div.job-wrapper div.lower div.email span.get-text{color: <?php echo esc_attr($color4); ?>;  font-size: 14px;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.right div.show-items div.job-wrapper div.lower div.category span.get-text{color: <?php echo esc_attr($color4); ?>;  font-size: 14px;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.right div.show-items div.job-wrapper div.footer{background: <?php echo esc_attr($color3); ?>; color: <?php echo esc_attr($color4); ?>;}

    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.messages{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.messages div.heading{background: <?php echo esc_attr($color3); ?>; color: <?php echo esc_attr($color8); ?>; border-bottom: 1px solid <?php echo esc_attr($color5); ?>; font-weight: bold;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.messages div.message{border-bottom: 1px solid <?php echo esc_attr($color5); ?>; color: <?php echo esc_attr($color4); ?>; background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.control-pannel-header{border-bottom: 2px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.control-pannel-header span.notify span.count_notifications{color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.control-pannel-header span.notify img:hover{cursor: pointer;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.control-pannel-header span.notify span.count_messages{color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.control-pannel-header span.heading{color: <?php echo esc_attr($color4); ?>; font-weight: bold;font-size: 18px;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.control-pannel-categories{}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.left{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.right{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.extra-activities div.detail-wrapper div.header-left{ color: <?php echo esc_attr($color7); ?>; font-weight: bold; font-size: 16px;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.extra-activities div.detail-wrapper a{ color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.extra-activities div.detail-wrapper div.header-right{ color: <?php echo esc_attr($color7); ?>;font-weight: bold; font-size: 16px;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.extra-activities div.detail-wrapper a{ color: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.extra-activities div.detail-wrapper div.show-items{background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.extra-activities div.detail-wrapper div.show-items div.job-wrapper div.img a{border: 1px solid <?php echo esc_attr($color5); ?>; border-left: 6px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.extra-activities div.detail-wrapper div.show-items div.job-wrapper{border: 1px solid <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.extra-activities div.detail-wrapper div.show-items div.job-wrapper div.upper{border-bottom: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.extra-activities div.detail-wrapper div.show-items div.job-wrapper div.upper a{background:none;color: <?php echo esc_attr($color1); ?>; font-weight: bold; text-decoration: none; font-size: 16px; }
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.extra-activities div.detail-wrapper div.show-items div.job-wrapper div.lower div.resume_title{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.extra-activities div.detail-wrapper div.show-items div.job-wrapper div.lower span.get-text{color: <?php echo esc_attr($color4); ?>;  font-size: 14px; }
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.extra-activities div.detail-wrapper div.show-items div.job-wrapper div.lower span.text{color: <?php echo esc_attr($color8); ?>;  font-size: 16px; }

    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.notifications{border: 1px solid <?php echo esc_attr($color5);?>;box-shadow: -5px 4px 4px 0 #a9abae;background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.notifications div.message div.title div.heading{color: <?php echo esc_attr($color8);?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.notifications div.message div.title div.text{color: <?php echo esc_attr($color4); ?>;}

    div#jsjobs-wrapper div.js-topstats div.tprow{border:1px solid <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.js-topstats div.tprow div.js-headtext{color: <?php echo esc_attr($color4); ?>;}
    table#js-table thead.stats tr{background: <?php echo esc_attr($color3); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    table#js-table thead.stats tr th{border-right:1px solid <?php echo esc_attr($color5); ?>;color: <?php echo esc_attr($color8); ?>;background: <?php echo esc_attr($color3); ?>;}
    table#js-table thead.stats tr th:last-child{border-right:none;}

    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.notifications{background: <?php echo esc_attr($color3); ?>;box-shadow: 0px 0px 18px #a9abae;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.notifications div.heading{color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.notifications div.message{background: <?php echo esc_attr($color7); ?>;border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.notifications div.message div.title div.heading{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.notifications div.message div.title div.text{color:<?php echo esc_attr($color4); ?>;}

    table#js-table tbody.stats tr td{border:1px solid <?php echo esc_attr($color5); ?>; color: <?php echo esc_attr($color4); ?>; background: <?php echo esc_attr($color7); ?>;}
    table#js-table tbody.stats tr td{border-right: none;}
    table#js-table tbody.stats tr td:last-child{border-right: 1px solid <?php echo esc_attr($color5); ?>;}
    table#js-table tbody.stats tr td.publish{color: <?php echo esc_attr($color8); ?>;}
    table#js-table tbody.stats tr td.expired{color: <?php echo esc_attr($color8); ?>;}

    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.notifications div.heading{background: <?php echo esc_attr($color3); ?>; color: <?php echo esc_attr($color8); ?>; border-bottom: 1px solid <?php echo esc_attr($color5); ?>; font-weight: bold;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.notifications div.message{border-bottom: 1px solid <?php echo esc_attr($color5); ?>; color: <?php echo esc_attr($color4); ?>; background: <?php echo esc_attr($color3); ?>}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.messages{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.messages div.heading{background: <?php echo esc_attr($color3); ?>; color: <?php echo esc_attr($color8); ?>; border-bottom: 1px solid <?php echo esc_attr($color5); ?>; font-weight: bold;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.messages div.message{border: 1px solid <?php echo esc_attr($color5); ?>;box-shadow: -5px 4px 4px 0 #a9abae;background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div#employer-control-pannel-wrapper div.messages div.message div.title div.text{color: <?php echo esc_attr($color4); ?>}
    div#jsjobs-wrapper div#jsjobs-admin-wrapper div.dashboard span{background: <?php echo esc_attr($color7); ?>;border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#jsjobs-admin-wrapper div.dashboard span.heading-dashboard{background: <?php echo esc_attr($color7); ?>; border-radius: 5px; color: <?php echo esc_attr($color4); ?>; border: 1px solid <?php echo esc_attr($color5); ?>; font-weight: bold; font-size: 18px;}
    div#jsjobs-wrapper div#view-job-wrapper div.top{background: <?php echo esc_attr($color3); ?>; border-bottom: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.top div.jobname{font-size: 16px; font-weight: bold; color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.top div.inner-wrapper div.jobdetail span.get-text{border-right: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.top div.inner-wrapper div.jobdetail{font-size: 14px;}
    div#jsjobs-wrapper div#view-job-wrapper div.top div.inner-wrapper div.jobdetail span.get-text span.gold{color: <?php echo esc_attr($color7); ?>; font-size: 12px;}
    div#jsjobs-wrapper div#view-job-wrapper div.top div.inner-wrapper div.jobdetail span.get-text span.featured{color: <?php echo esc_attr($color7); ?>; font-size: 12px;}
    div#jsjobs-wrapper div#view-job-wrapper div.top div.inner-wrapper div.jobdetail span.agodays{color: <?php echo esc_attr($color4); ?>; }
    div#jsjobs-wrapper div#view-job-wrapper div.top div.inner-wrapper div.jobdetail span.city{color: <?php echo esc_attr($color4); ?>; border-right: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.btn-div a.btn{color: <?php echo esc_attr($color8); ?>; background: <?php echo esc_attr($color6); ?>; border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.btn-div a.btn:hover{color: <?php echo esc_attr($color7); ?>; background: <?php echo esc_attr($color1); ?>; border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.btn-div a.btn:focus{color: <?php echo esc_attr($color7); ?>; background: <?php echo esc_attr($color1); ?>; border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.btn-div a.blue{color: <?php echo esc_attr($color7); ?>; background: <?php echo esc_attr($color1); ?>; border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.heading1{color: <?php echo esc_attr($color8); ?>; background: <?php echo esc_attr($color3); ?>; border-bottom: 2px solid <?php echo esc_attr($color1); ?>; font-size: 16px; font-weight: bold;}
    div#jsjobs-wrapper div#view-job-wrapper div.heading2{color: <?php echo esc_attr($color8); ?>; background: <?php echo esc_attr($color3); ?>; border-bottom: 2px solid <?php echo esc_attr($color1); ?>; font-size: 16px; font-weight: bold;}
    div#jsjobs-wrapper div#view-job-wrapper div.detail-wrapper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.left{border-right:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.detail-wrapper span.heading{color: <?php echo esc_attr($color8); ?>; }
    div#jsjobs-wrapper div#view-job-wrapper div.detail-wrapper span.txt{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.peragraph{color: <?php echo esc_attr($color4); ?>; border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.apply a.apply-btn {color: <?php echo esc_attr($color7); ?>; background: <?php echo esc_attr($color1); ?>; border-radius: 3px;}
    div#jsjobs-wrapper div#view-job-wrapper div.apply{border-top: 2px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.fb-heading { color: <?php echo esc_attr($color7); ?>;   font-size: 18px;}
    div#jsjobs-wrapper div#view-job-wrapper div.main div.right div.companywrapper{background: <?php echo esc_attr($color3); ?>;border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.main div.right div.company-img{border: 1px solid <?php echo esc_attr($color5); ?>;background: <?php echo esc_attr($color7); ?>; border-left: 6px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.main div.right div.copmany-detail span.heading{color: <?php echo esc_attr($color8); ?>; font-size: 16px;}
    div#jsjobs-wrapper div#view-job-wrapper div.main div.right div.copmany-detail a.url{color: <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.main div.right div.copmany-detail span.address{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#view-job-wrapper div.main div.right div.copmany-detail span.share{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div.company-wrapper div.company-upper-wrapper div.company-detail div.company-detail-upper div.company-detail-upper-left span.company-title a{color: <?php echo esc_attr($color1); ?>}
    div#js_main_wrapper div.js-resume-section-title{background:<?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color3); ?>;}
    div#js_main_wrapper div div.js-resume-section-title{background:<?php echo esc_attr($color2); ?>;color:<?php echo esc_attr($color3); ?>;}
    div#js_main_wrapper div div.js-resume-section-body{background:<?php echo esc_attr($color5); ?>;border:1px solid <?php echo esc_attr($color4); ?>;/*color:<?php echo esc_attr($color3); ?>;*/}
    div#js_main_wrapper div div.js-resume-field-container{color:<?php echo esc_attr($color6); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div.js-resume-checkbox-container{color:<?php echo esc_attr($color6); ?>;border:1px solid <?php echo esc_attr($color4); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div div.js-resume-field-container div.upload-field{border:1px solid <?php echo esc_attr($color4); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div div.js-resume-field-container div.files-field{border:1px solid <?php echo esc_attr($color4); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div div.js-resume-field-container div.files-field div.selectedFiles span.selectedFile{border:1px solid <?php echo esc_attr($color4); ?>;background:<?php echo esc_attr($color5); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div.uploadedFiles{border:1px solid <?php echo esc_attr($color4); ?>;color:<?php echo esc_attr($color8); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div.uploadedFiles span.selectedFile{border:1px solid <?php echo esc_attr($color4); ?>;background:<?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color8); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-section-view div.js-resume-data div div.filesList{border:1px solid <?php echo esc_attr($color4); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-section-view div.js-resume-data div div.filesList ul li.selectedFile{border:1px solid <?php echo esc_attr($color4); ?>;background:<?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color8); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-section-view div.js-resume-data div div.filesList a.zip-downloader{background:<?php echo esc_attr($color2); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div div.js-resume-field-container span.upload_btn{border:1px solid <?php echo esc_attr($color4); ?>;background:<?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color6); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div div.js-resume-field-container div.upload-field:hover span.upload_btn{background:<?php echo esc_attr($color2); ?>;color:<?php echo esc_attr($color3); ?>;border:1px solid <?php echo esc_attr($color2); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div div.js-resume-field-container div.files-field:hover span.upload_btn{background:<?php echo esc_attr($color2); ?>;color:<?php echo esc_attr($color3); ?>;border:1px solid <?php echo esc_attr($color2); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div div.js-resume-field-container ul{border:1px solid <?php echo esc_attr($color4); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div div.js-resume-show-hide-btn span{border:1px solid <?php echo esc_attr($color4); ?>;background:<?php echo esc_attr($color7); ?>;color:<?php echo esc_attr($color8); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div div.js-resume-show-hide-btn span:hover{background:<?php echo esc_attr($color2); ?>;color:<?php echo esc_attr($color3); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div div.loc-field a.map-link{border:1px solid <?php echo esc_attr($color4); ?>;background:<?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color6); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div div.loc-field a.map-link:hover{background:<?php echo esc_attr($color2); ?>;color:<?php echo esc_attr($color3); ?>;border:1px solid <?php echo esc_attr($color2); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div div.js-resume-submit-container{border-top:1px solid <?php echo esc_attr($color4); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div div.js-resume-submit-container button{background:<?php echo esc_attr($color7); ?>;border:1px solid <?php echo esc_attr($color4); ?>;color:<?php echo esc_attr($color8); ?>;}
    div#js_main_wrapper div div.js-resume-section-body form div div.js-resume-submit-container button:hover{background:<?php echo esc_attr($color2); ?>;color:<?php echo esc_attr($color3); ?>;}
    div#js_main_wrapper div div.js-resume-section-view{color:<?php echo esc_attr($color3); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div.js-resume-section-view div.js-resume-profile div img.avatar{border:1px solid <?php echo esc_attr($color4); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-section-view div.js-resume-profile-info div div.js-resume-profile-name{color:<?php echo esc_attr($color6); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-section-view div.js-resume-profile-info div.profile-name-outer{border-bottom:1px solid <?php echo esc_attr($color4); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-section-view div.js-resume-profile-info div.js-resume-profile-email{color:<?php echo esc_attr($color6); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-section-view div.js-resume-profile-info div.js-resume-profile-cell{color:<?php echo esc_attr($color6); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-section-view div.js-resume-data div div.js-resume-data-title{color:<?php echo esc_attr($color9); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-section-view div.js-resume-data div div.js-resume-data-value{color:<?php echo esc_attr($color6); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-address-section-view{border:1px solid <?php echo esc_attr($color4); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-address-section-view div span.addressDetails{color:<?php echo esc_attr($color9); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div#editorView div span{color:<?php echo esc_attr($color9); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-address-section-view div span.sectionText{color:<?php echo esc_attr($color1); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-address-section-view div.map-toggler{background:<?php echo esc_attr($color5); ?>; border:1px solid <?php echo esc_attr($color4); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-address-section-view div.map-toggler span{color:<?php echo esc_attr($color1); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-address-section-view div.map_container{border:1px solid <?php echo esc_attr($color4); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.add-resume-address a{color:<?php echo esc_attr($color1); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.add-resume-form a{color:<?php echo esc_attr($color1); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-data-section-view{border:1px solid <?php echo esc_attr($color4); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-data-section-view div.js-resume-data-head{border-bottom:1px solid <?php echo esc_attr($color4); ?>;color:<?php echo esc_attr($color6); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-data-section-view div div.js-resume-data-title{color:<?php echo esc_attr($color9); ?>;}
    div#js_main_wrapper div div.js-resume-section-body div div.js-resume-data-section-view div div.js-resume-data-value{color:<?php echo esc_attr($color6); ?>;}
    div#jsjobs-wrapper div.email-checkbox {background-color:<?php echo esc_attr($color3); ?>;color: <?php echo esc_attr($color4); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.radio-fields {background-color: <?php echo esc_attr($color3); ?>;color: <?php echo esc_attr($color4); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.chck-box-email-text {color: <?php echo esc_attr($color4); ?>;}
    <?php /* Resume Form file popup designs */ ?>
    div#resumeFilesPopup.resumeFilesPopup{background:<?php echo esc_attr($color3); ?>;}
    div#resumeFilesPopup div#resumeFiles_headline{background:<?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color3); ?>;border-bottom:5px solid <?php echo esc_attr($color2); ?>;}
    div#resumeFilesPopup div.chosenFiles_heading{border-bottom:1px solid <?php echo esc_attr($color2); ?>;color:<?php echo esc_attr($color8); ?>}
    div#resumeFilesPopup div.filesInfo{border-bottom:1px solid <?php echo esc_attr($color2); ?>;}
    div#resumeFilesPopup div.fileSelectionButton span.fileSelector{border:1px solid <?php echo esc_attr($color4); ?>;background:<?php echo esc_attr($color7); ?>;color:<?php echo esc_attr($color8); ?>;}
    div#resumeFilesPopup div.fileSelectionButton:hover span.fileSelector{background:<?php echo esc_attr($color2); ?>;color:<?php echo esc_attr($color3); ?>;}
    div#resumeFilesPopup div.resumeFiles_close span{border:1px solid <?php echo esc_attr($color4); ?>;background:<?php echo esc_attr($color7); ?>;color:<?php echo esc_attr($color8); ?>;}
    div#resumeFilesPopup div.resumeFiles_close span:hover{background:<?php echo esc_attr($color2); ?>;color:<?php echo esc_attr($color3); ?>;}
    div#resumeFilesPopup div.filesInfo div.chosenFiles div.hoverLayer span.deleteChosenFiles{border:1px solid <?php echo esc_attr($color2); ?>;background:<?php echo esc_attr($color7); ?>;color:<?php echo esc_attr($color8); ?>;}
    div#resumeFilesPopup div.filesInfo div.chosenFiles div.hoverLayer span.deleteChosenFiles:hover{background:<?php echo esc_attr($color2); ?>;color:<?php echo esc_attr($color3); ?>;}
    div#resumeFilesPopup div.filesInfo div.chosenFiles div.hoverLayer{border:1px solid <?php echo esc_attr($color2); ?>;}
    div#jsst_breadcrumbs_parent div.home{background-color:<?php echo esc_attr($color2); ?>;}
    div#jsst_breadcrumbs_parent div.links a.links{color:<?php echo esc_attr($color2); ?>;}
    div#jsjobs-header-main-wrapper{background:<?php echo esc_attr($color1); ?>;border-bottom:5px solid <?php echo esc_attr($color2); ?>;box-shadow: 0px 4px 1px <?php echo esc_attr($color5); ?>;}
    div#jsjobs-header-main-wrapper a.headerlinks{color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-header-main-wrapper a.headerlinks:hover{background:<?php echo esc_attr($color7); ?>;color:<?php echo esc_attr($color1); ?>;}
    div#js-jobs-wrapper{border: 1px solid <?php echo esc_attr($color5); ?>; color: <?php echo esc_attr($color4); ?>;}
    div#js-jobs-wrapper span.js-bold{color:<?php echo esc_attr($color8); ?>;}
    div#js-jobs-wrapper span.get-text{color:<?php echo esc_attr($color4); ?>;}
    div#js-jobs-wrapper div.js-toprow div.js-image a{border: 1px solid <?php echo esc_attr($color5); ?>; border-left: 4px solid <?php echo esc_attr($color1); ?>;}
    div#js-jobs-wrapper div.js-toprow div.js-data div.js-first-row{border-bottom: 1px solid <?php echo esc_attr($color5); ?>;}
    div#js-jobs-wrapper div.js-toprow div.js-data div.js-first-row span.js-title a{text-decoration:none;color: <?php echo esc_attr($color1); ?>;}
    div#js-jobs-wrapper div.js-toprow div.js-data div.js-first-row span.js-jobtype span.js-type{border: 1px solid <?php echo esc_attr($color5); ?>; border-bottom: none;background: <?php echo esc_attr($color3); ?>;}
    div#js-jobs-wrapper div.js-toprow div.js-data div.js-midrow a.js-companyname{color: <?php echo esc_attr($color4); ?>;}
    div#js-jobs-wrapper div.js-toprow div.js-data div.js-midrow a.js-companyname:hover{color: <?php echo esc_attr($color1); ?>;}
    div#js-jobs-wrapper div.js-toprow div.js-data div.js-second-row div.js-fields span.js-totaljobs{border: 1px solid <?php echo esc_attr($color5); ?>;background: <?php echo esc_attr($color3); ?>;}
    div#js-jobs-wrapper div.js-bottomrow{border-top: 1px solid <?php echo esc_attr($color5); ?>;background: <?php echo esc_attr($color3); ?>; color: <?php echo esc_attr($color4); ?>;}
    div#js-jobs-wrapper div.js-bottomrow div.js-actions a.js-button{border: 1px solid <?php echo esc_attr($color5); ?>;background: <?php echo esc_attr($color6); ?>;}
    div#js-jobs-wrapper div.js-bottomrow div.js-actions a.js-btn-apply{background: <?php echo esc_attr($color1); ?>; color: <?php echo esc_attr($color7); ?>;}
    /*shoaib*/
    div#jsjobs-wrapper div.category-row-wrapper div.category-wrapper div.jsjobs-subcategory-wrapper{background:<?php echo esc_attr($color7); ?>;border:1px solid <?php echo esc_attr($color5); ?>;box-shadow: 0px 0px 10px #EAEAEA;}
    div#jsjobs-listpopup div.jsjob-contentarea div.jsjobs-subcategory-wrapper div.jsjobs-subcategory-wrapper{background:<?php echo esc_attr($color7); ?>;border:1px solid <?php echo esc_attr($color5); ?>;box-shadow: 0px 0px 10px #EAEAEA;}
    div#jsjobs-wrapper div.category-row-wrapper div.category-wrapper div.jsjobs-subcategory-wrapper div.showmore-wrapper a.showmorebutton,
    div#jsjobs-listpopup div.jsjob-contentarea div.jsjobs-subcategory-wrapper div.category-wrapper div.jsjobs-subcategory-wrapper div.showmore-wrapper a.showmorebutton{background: <?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.jsjobs-subcategory-wrapper div.jobs-by-categories-wrapper{border: 1px solid <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color3); ?>;color: <?php echo esc_attr($color4); ?>;font-size: 14px;}
    div#jsjobs-listpopup div.jsjob-contentarea div.jsjobs-subcategory-wrapper div.jobs-by-categories-wrapper:hover{border-color: <?php echo esc_attr($color1); ?>; cursor: pointer;}
    div#jsjobs-listpopup div.jsjob-contentarea div.jsjobs-subcategory-wrapper div.jobs-by-categories-wrapper span.total-jobs:hover{border-color: <?php echo esc_attr($color1); ?>; cursor: pointer;}
    /********/
    /* Resume */
    div#resume-wrapper div.resume-section-title{background:<?php echo esc_attr($color3); ?>;border-bottom:2px solid <?php echo esc_attr($color1); ?>;}
    div#resume-wrapper div.resume-section-title img{background:<?php echo esc_attr($color1); ?>;}
    div#resume-wrapper div.section_wrapper div.resume-heading-row{background: <?php echo esc_attr($color3); ?>;border-bottom: 2px solid <?php echo esc_attr($color1); ?>;}
    div#resume-wrapper div.section_wrapper div.resume-row-wrapper-wrapper{border-bottom: 1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-wrapper div.section_wrapper div.resume-row-full-view{border-bottom: 1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-wrapper div.resume-section-data{border:1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-wrapper div.resume-heading-row span.resume-employer-position{background: <?php echo esc_attr($color2); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#resume-wrapper div.resume-top-section{background: <?php echo esc_attr($color3); ?>;}
    div#resume-wrapper div.resume-top-section div.js-col-lg-4 img{background: <?php echo esc_attr($color7); ?>;border:2px solid <?php echo esc_attr($color1); ?>;}
    div#resume-wrapper div.resume-top-section div.js-col-lg-8 span.resume-tp-name{border-bottom: 1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-wrapper div.resume-top-section div.js-col-lg-8 span.resume-tp-apptitle{color:<?php echo esc_attr($color4); ?>;}
    div#resume-wrapper div.resume-top-section div.js-col-lg-8 a{background: <?php echo esc_attr($color6); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-wrapper div.resume-top-section div.js-col-lg-8 a:hover{border:1px solid <?php echo esc_attr($color1); ?>;}
    div#resume-wrapper div.resume-top-section div.js-col-lg-8 a.downloadall{background: <?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#resume-wrapper div.resume-row-wrapper div.row-title,
    div#resume-wrapper div.resume-map-edit div.row-title{color:<?php echo esc_attr($color8); ?>;}
    div#resume-wrapper div.resume-row-wrapper div.row-value img#rs_photo.rs_photo{border:2px solid <?php echo esc_attr($color1); ?>;}
    div#resume-wrapper div.resume-row-wrapper div.row-value{color:<?php echo esc_attr($color4); ?>;}
    div#resume-wrapper div.resume-map div.row-title{background: <?php echo esc_attr($color3); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-wrapper div.resume-map div.row-value{border:1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-wrapper div.section_wrapper div.resume-row-full-view div.row-value.attachments a.file{color:<?php echo esc_attr($color4); ?>;background: <?php echo esc_attr($color3); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-wrapper div.section_wrapper div.resume-row-full-view div.row-value.attachments a.file img.filedownload{background: <?php echo esc_attr($color1); ?>;}
    div#resume-wrapper div.section_wrapper div.resume-row-full-view div.row-value.attachments{border:1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-wrapper div.section_wrapper div.resume-row-full-view div.row-title.attachments{color:<?php echo esc_attr($color8); ?>;}
    div#resume-wrapper div.section_wrapper.form{border:1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-wrapper div.section_wrapper.form div.formsectionheading{border-bottom:2px solid <?php echo esc_attr($color1); ?>;background: <?php echo esc_attr($color3); ?>;}
    div#resume-wrapper a.add{background: <?php echo esc_attr($color6); ?>;color:<?php echo esc_attr($color8); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-wrapper div.resume-map-edit div.row-value div.map{border:1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-files-popup-wrapper{background: <?php echo esc_attr($color3); ?>;border-bottom:5px solid <?php echo esc_attr($color1); ?>;}
    div#resume-files-popup-wrapper span.close-resume-files{background: <?php echo esc_attr($color1); ?>;border-bottom:5px solid <?php echo esc_attr($color10); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#resume-files-popup-wrapper div.resumepopupsectionwrapper span.clickablefiles{background: <?php echo esc_attr($color6); ?>;color:<?php echo esc_attr($color8); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-files-popup-wrapper div.resumepopupsectionwrapper span.headingpopup{background: <?php echo esc_attr($color8); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#resume-files-popup-wrapper div.resumepopupsectionwrapper span#resume-files-selected{background: <?php echo esc_attr($color7); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-wrapper div.resume-row-wrapper.form div.row-value input,
    div#resume-wrapper div.resume-row-wrapper.form div.row-value select{background: <?php echo esc_attr($color3); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-wrapper div.resume-row-wrapper.form div.row-value div#resumefileswrapper{background: <?php echo esc_attr($color3); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-wrapper div.resume-row-wrapper.form div.row-value div#resumefileswrapper span.resume-selectfiles{background: <?php echo esc_attr($color1); ?>;}
    div#resume-wrapper div.resume-row-wrapper.form div.row-value div#resumefileswrapper a.file{background: <?php echo esc_attr($color7); ?>;border:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color4); ?>;}
    div#resume-files-popup-wrapper div.resumepopupsectionwrapper span#resume-files-selected div.resumefileselected{border:1px solid <?php echo esc_attr($color5); ?>;background:<?php echo esc_attr($color3); ?>;color:<?php echo esc_attr($color4); ?>;}
    div#resume-files-popup-wrapper div.resumepopupsectionwrapper span#resume-files-selected div.resumefileselected.errormsg{border:1px solid #ED3237;background:<?php echo esc_attr($color3); ?>;}
    div#resume-files-popup-wrapper div.resumepopupsectionwrapper span#resume-files-selected div.resumefileselected.errormsg span.filename{color:#ED3237;}
    div#resume-files-popup-wrapper div.resumepopupsectionwrapper span#resume-files-selected div.error_msg{color:#ED3237;}
    div#resume-files-popup-wrapper div.resumepopupsectionwrapper span#resume-files-selected div.resumefileselected button{background:<?php echo esc_attr($color6); ?>;color:<?php echo esc_attr($color8); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#resume-files-popup-wrapper div.resumepopupsectionwrapper span#resume-files-selected div.resumefileselected button:hover{background:<?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#resume-files-popup-wrapper div.resume-filepopup-lowersection-wrapper{color:<?php echo esc_attr($color4); ?>;}
    div#resume-wrapper span.resume-moreoptiontitle{background: <?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#comments label {color: <?php echo esc_attr($color8); ?>;}
    /* ratelist */
    div#jsjobs-wrapper div.rate-list-item{border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color3); ?>;color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.rate-list-item span.rate-list-top{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.rate-list-item span.rate-list-bottom{color:<?php echo esc_attr($color4); ?>;background-color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div.rate-list-item span.rate-list-bottom span.bold{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.rate-list-item span.rate-list-bottom-right{border:1px solid <?php echo esc_attr($color5); ?>;background-color:<?php echo esc_attr($color3); ?>;}
    div#resume-wrapper div.resume-section-button input{background: <?php echo esc_attr($color6); ?>;border:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color8); ?>;}
    div#job-applied-resume-jobtitle{background: <?php echo esc_attr($color3); ?>;border:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-wrapper div#popup-main div#popup-bottom-part.center input.jsjobs-button{background: <?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;border:1px solid <?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper div#popup-main div#popup-bottom-part.center input.jsjobs-button:hover{background: <?php echo esc_attr($color7); ?>;color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-listpopup div.job-alert-popup div#save-button input[type="submit"]{background: <?php echo esc_attr($color6); ?> !important;border:1px solid<?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-listpopup div.job-alert-popup div#save-button input[type="submit"]:hover{background: <?php echo esc_attr($color3); ?> !important;border:1px solid<?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-listpopup div.job-alert-popup input#emailaddress{color:<?php echo esc_attr($color4); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-listpopup div.job-alert-popup div.js-form-title{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.listing-fields div.custom-field-wrapper span.js-bold{color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div.listing-fields div.custom-field-wrapper span.get-text{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs_jstags span.jstagstitle{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs_jstags a.jsjob_tags_a:hover{color:<?php echo esc_attr($color1); ?>; border:1px solid <?php echo esc_attr($color1); ?>;background:unset;}
    div#jsjobs_module_wrapper div#jsjobs_module_wrap div#jsjobs_module_data_fieldwrapper span#jsjobs_module_data_fieldtitle{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs_module_wrapper div#jsjobs_module_wrap div#jsjobs_module_data_fieldwrapper span#jsjobs_module_data_fieldvalue{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs_module_wrapper div#tp_heading {border-bottom: 2px solid <?php echo esc_attr($color2); ?>;}
    div#jsjobs_module{background:<?php echo esc_attr($color3); ?>;border:1px solid  <?php echo esc_attr($color5); ?>;}
    div#jsjobs_modulelist_databar{background:<?php echo esc_attr($color3); ?>;border:1px solid  <?php echo esc_attr($color5); ?>;}
    div#jsjobs_modulelist_titlebar{background:<?php echo esc_attr($color3); ?>;border:1px solid  <?php echo esc_attr($color5); ?>;}
    div#jsjobs_module span#jsjobs_module_heading{border-bottom:1px solid  <?php echo esc_attr($color2); ?>;}
    div#jsjobs_module a{color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs_mod_wrapper div#jsjobs-data-wrapper div.anchor a.anchor{background:<?php echo esc_attr($color3); ?>;border:1px solid  <?php echo esc_attr($color5); ?>;color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs_mod_wrapper div#jsjobs-mod-heading{border-bottom:2px solid  <?php echo esc_attr($color2); ?>;color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs_mod_wrapper div.jsjobs-value{color: <?php echo esc_attr($color4); ?>;}
    div#jsjobs-popup.loginpopup div.popup-row.name div.login-heading{color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-popup.loginpopup div.popup-row.name input#user_login,
    div#jsjobs-popup.loginpopup div.popup-row.name input#user_pass{color:<?php echo esc_attr($color4); ?>;border:1px solid <?php echo esc_attr($color5); ?>;background:<?php echo esc_attr($color3); ?>;}
    div#jsjobs-popup.loginpopup div.popup-row.name input#wp-submit{color:<?php echo esc_attr($color7); ?>;background:<?php echo esc_attr($color1); ?>;border:0px;outline: unset;}
    div#jsjobs-popup.loginpopup div.popup-row.name p.login-remember label{color:<?php echo esc_attr($color4); ?>;}
    div#jsjobs-popup.loginpopup div.loginintocomment span.logintext{color: <?php echo esc_attr($color8); ?>;}
    div#jsjobs-popup.loginpopup div.loginintocomment hr.loginhr{border: 1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-listpopup div.jsjob-contentarea div.quickviewrow span.jobtitle{border-bottom:1px solid <?php echo esc_attr($color5); ?>;border-top:2px solid <?php echo esc_attr($color2); ?>; color: <?php echo esc_attr($color8); ?>;background: <?php echo esc_attr($color3); ?>;}
    div.companies.filterwrapper form span.filterlocation img{border:1px solid <?php echo esc_attr($color5); ?>;border-bottom: none;}
    div.companies.filterwrapper form span.filterlocation ul.jsjobs-input-list-jsjobs{border:1px solid <?php echo esc_attr($color5); ?>;}
    div.companies.filterwrapper form input#jsjobs-company{background:<?php echo esc_attr($color7); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div.companies.filterwrapper{background: <?php echo esc_attr($color3); ?>;border-bottom:2px solid <?php echo esc_attr($color5); ?>;}
    div.companies.filterwrapper form input#jsjobs-go{background:<?php echo esc_attr($color1); ?>;border:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color7); ?>;}
    div.companies.filterwrapper form input#jsjobs-go:hover{background:<?php echo esc_attr($color7); ?>;border:1px solid <?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color1); ?>;}
    div.companies.filterwrapper form input#jsjobs-reset{background:<?php echo esc_attr($color6); ?>;border:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color8); ?>;}
    div.companies.filterwrapper form input#jsjobs-reset:hover{background:<?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div#job-applied-resume div.jobs-upper-wrapper div.job-detail div.job-detail-lower div.block span.applyassocial{background:<?php echo esc_attr($color3); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    span.job-right-heading{color:<?php echo esc_attr($color8); ?>;background:<?php echo esc_attr($color3); ?>;border-bottom:1px solid <?php echo esc_attr($color2); ?>;}
    div#jsjobsfooter{border:1px solid <?php echo esc_attr($color5); ?>;background: <?php echo esc_attr($color3); ?>;}
    div#jsresume-tags-wrapper span.jsresume-tags-title{background: <?php echo esc_attr($color8); ?>;color:<?php echo esc_attr($color7); ?>;}
    div#jsresume-tags-wrapper div.tags-wrapper-border{border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsresume-tags-wrapper div.tags-wrapper-border a.jsjob_tags_a:hover{background: unset;border:1px solid <?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color1); ?>;}
    div#jsjobs-wrapper a#showmorejobs{color:<?php echo esc_attr($color7); ?>;background: <?php echo esc_attr($color2); ?>;}
    
    div.js-form-wrapper-newlogin{border: 1px solid <?php echo esc_attr($color5); ?>;background: <?php echo esc_attr($color3); ?>;}
    div.js-form-wrapper-newlogin div.js-imagearea div.js-img{background: <?php echo esc_attr($color1); ?>;}
    div.js-form-wrapper-newlogin div.js-dataarea div.js-form-heading{color:<?php echo esc_attr($color8); ?>; border-bottom: 2px solid <?php echo esc_attr($color2); ?>;}

    div#jsjobs-wrapper form#jsjobs_registration_form input#save{background:none; color:<?php echo esc_attr($color7); ?>;background-color:<?php echo esc_attr($color1); ?>;border:1px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper form#jsjobs_registration_form fieldset label{color:<?php echo esc_attr($color8); ?>}
    div#jsjobs-wrapper form#jsjobs_registration_form fieldset p span{color:<?php echo esc_attr($color8); ?>}
    div#jsjobs-wrapper form#jsjobs_registration_form fieldset div#save{border-top:2px solid <?php echo esc_attr($color2); ?>;}

    div#jsjobs-wrapper div.js-login-wrapper{background-color:<?php echo esc_attr($color3); ?>;border:2px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.js-login-wrapper div.login-heading{color: <?php echo esc_attr($color8);?>;}
    div#jsjobs-wrapper div.js-login-wrapper div.js-ourlogin form#loginform-custom p label{color: <?php echo esc_attr($color8);?>;}
    div#jsjobs-wrapper div.js-login-wrapper div.js-ourlogin form#loginform-custom p.login-submit input#wp-submit{border:none; outline: none; color: <?php echo esc_attr($color7);?>; background: <?php echo esc_attr($color1);?>;}
    div#jsjobs-wrapper div.js-login-wrapper div.js-ourlogin form#loginform-custom p input.input{border:2px solid <?php echo esc_attr($color5); ?>;}
    div#jsjobs-wrapper div.js-login-wrapper div.js-seprator div.js-vline{border-left: 1px solid <?php echo esc_attr($color5);?>;}
    div#jsjobs-wrapper div#job-applied-resume-wrapper div.jsjobs-button-search{border-top: 2px solid <?php echo esc_attr($color2);?>;}

	div#jsjobs-wrapper div#send-message-wrapper div.message-history span.js-msg-left div{border: 1px solid <?php echo esc_attr($color5);?>;border-left:3px solid <?php echo esc_attr($color1);?> }

    div#jsjobs-wrapper div.my-resume-data div.data-bigupper div.big-upper-lower.listing-fields div.myresume-list-data-profile {border: 1px solid <?php echo esc_attr($color5);?>;}
    div#jsjobs-wrapper div.my-resume-data div.data-bigupper div.big-upper-lower.listing-fields div.myresume-list-data-profile  span.myresume-profile-heading{border-bottom: 1px solid <?php echo esc_attr($color5);?>; background: <?php echo esc_attr($color3); ?>;}
    div#jsjobs-wrapper div.my-resume-data div.data-bigupper div.big-upper-lower.listing-fields div.myresume-list-data-profile  span.myresume-profile-title{ border-top: 1px solid <?php echo esc_attr($color5);?>;}


    div#resume-wrapper div.jsresume_addnewbutton{border:1px solid <?php echo esc_attr($color5); ?>;background: <?php echo esc_attr($color3);?>;color:<?php echo esc_attr($color8);?>;}

    
    div#resume-wrapper div.resume-section-button div#save-button.bottombutton input.jsjb-jm-btn-primary{border:1px solid <?php echo esc_attr($color1); ?>;background: <?php echo esc_attr($color3);?>;color:<?php echo esc_attr($color8);?>;}
    div#resume-wrapper div.resume-section-button div#save-button.bottombutton input.jsjb-jh-btn-primary{border:1px solid <?php echo esc_attr($color1); ?>;background: <?php echo esc_attr($color3);?>;color:<?php echo esc_attr($color8);?>;}
    div#resume-wrapper div.resume-section-button div#save-button.bottombutton input.-btn-primary{border:1px solid <?php echo esc_attr($color1); ?>;background: <?php echo esc_attr($color3);?>;color:<?php echo esc_attr($color8);?>;}
    div#resume-wrapper div.resume-section-button div#save-button.bottombutton a.resume_submits.cancel{border:1px solid <?php echo esc_attr($color1); ?>;background: <?php echo esc_attr($color3);?>;color:<?php echo esc_attr($color8);?>;}
    div#jsjobs-wrapper div.js-form-wrapper div.resumefieldvalue div a.js-resume-close-cross{border:1px solid <?php echo esc_attr($color1); ?>;}
    
    div#resume-wrapper div.section_wrapper{background: <?php echo esc_attr($color3);?>}

    div#jsjobs-wrapper div#my-companies-header ul li{border: 1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color7); ?>;background-color:<?php echo esc_attr($color8); ?>;}
    div#jsjobs-wrapper div#my-companies-header ul li a{color:<?php echo esc_attr($color7); ?>}
    div#jsjobs-wrapper div#my-companies-header ul li img#posted-img{border:1px solid white;}
    div#jsjobs-wrapper div#my-companies-header ul li:hover{background-color:<?php echo esc_attr($color1); ?>;}

    .jsjobs-job-listing-total-jobs{border:1px solid <?php echo esc_attr($color5);?>;background: <?php echo esc_attr($color3);?>;color:<?php echo esc_attr($color4);?>;}
    .jsjobs-job-listing-total-jobs span{color:<?php echo esc_attr($color8);?>;}
    span.jsjobs-resume-moreoptiontitle{background: <?php echo esc_attr($color1); ?>;color:<?php echo esc_attr($color7); ?>;}

    div#jsjobs-wrapper div.visitor-apply-job-jobinforamtion-wrapper{border-bottom: 1px solid <?php echo esc_attr($color1);?>}

    div#jsjobs-wrapper .js-form-value .tmce-active .switch-tmce ,
    div#jsjobs-wrapper .js-form-value .tmce-active .switch-html {background: <?php echo esc_attr($color6); ?> !important;color: <?php echo esc_attr($color4); ?> !important;border-bottom-color: <?php echo esc_attr($color6); ?> !important;}
    div#jsjobs-wrapper .js-form-value button:not(:hover):not(:active):not(.has-background) {background: <?php echo esc_attr($color6); ?> !important;color: <?php echo esc_attr($color4); ?> !important;}
    .chosen-container-multi .chosen-choices,
    div#jsjob-search-popup div.js-searchform-value select#gender,
    div#jsjob-search-popup div.js-searchform-value select.sal,
    div.jsjob-contentarea div.quickviewrow div.quickviewhalfwidth select,
    div#jsjobs-wrapper form#jsjobs_registration_form fieldset input,
    div#jsjobs-wrapper form#jsjobs_registration_form fieldset select,
    div#jsjobs-wrapper div.js-form-wrapper div.js-form-value input[type="text"],
    div#jsjobs-wrapper div.js-form-wrapper div.js-form-value textarea,
    div#jsjobs-wrapper div.js-form-wrapper div.js-form-value select{border: 1px solid <?php echo esc_attr($color5); ?>;background-color: <?php echo esc_attr($color7); ?>;color: <?php echo esc_attr($color4); ?>;}


    @media(min-width: 481px) and (max-width: 780px) {
        div#jsjobs-wrapper div.message-content-data div.data-right{border-top:1px solid <?php echo esc_attr($color5); ?>;}
    }
    @media(min-width: 481px) and (max-width: 650px) {
        div#jsjobs-wrapper div.department-content-data div.data-lower{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
        div#jsjobs-wrapper div.search-wrapper-content-data div.data-upper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
        div#jsjobs-wrapper div.search-wrapper-content-data span.upper-app-title{border:none;border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
        div#jsjobs-wrapper div.cover-letter-content-data div.data-upper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
        div#jsjobs-wrapper div.cover-letter-content-data span.upper-app-title{border:none;border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
        div#jsjobs-wrapper div.folder-wrapper div.folder-firsl{border-bottom: 1px solid <?php echo esc_attr($color5); ?> ;}
        div#jsjobs-wrapper div.js-login-wrapper div.js-seprator div.js-vline{border-top: 1px solid <?php echo esc_attr($color5);?>;}
        div#jsjobs-wrapper div.purchase-history-wrapper div.purchase-history-data span.data-price{border-top: 1px solid <?php echo esc_attr($color5);?>}
    }
    @media (max-width: 480px) {
        div#resume-wrapper div.section_wrapper div.resume-row-wrapper-wrapper{border:0px;}
        div#resume-wrapper div.resume-row-wrapper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
        div#resume-wrapper div.resume-row-wrapper.form{border-bottom:0px;}
        div#jsjobs-wrapper div.department-content-data div.data-lower{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
        div#jsjobs-wrapper div.cover-letter-content-data div.data-upper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
        div#jsjobs-wrapper div.cover-letter-content-data span.upper-app-title{border:none;border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
        div#jsjobs-wrapper div#view-cover-letter-wrapper div#wrapper-content div.content-data span.upper-app-title{border:none;border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
        div#jsjobs-wrapper div.search-wrapper-content-data div.data-upper{border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
        div#jsjobs-wrapper div.search-wrapper-content-data span.upper-app-title{border:none;border-bottom:1px solid <?php echo esc_attr($color5); ?>;}
        div#jsjobs-wrapper div.message-content-data div.data-left span.lower{border-bottom:1px solid <?php echo esc_attr($color5); ?>;color:<?php echo esc_attr($color4); ?>;}
        div#jsjobs-wrapper div.credit-wrapper div.credit-content-data span.data-top span.top-right{border-top:1px solid <?php echo esc_attr($color5); ?>;}
        div#jsjobs-wrapper div.credit-log-wrapper span.desc{border:none;border-top: 1px solid <?php echo esc_attr($color5); ?>;}
        div#jsjobs-wrapper div.folder-wrapper{display:inline-block;border:1px solid <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color3); ?>;}
        div#jsjobs-wrapper div.folder-wrapper{display: inline-block; width: 100%;}
        div#jsjobs-wrapper div.folder-wrapper div.folder-firsl{border-bottom: 1px solid <?php echo esc_attr($color5); ?>;}
        div#jsjobs-wrapper div.folder-wrapper div.folder-firsl span{color:<?php echo esc_attr($color8); ?> ; font-size: 13px; font-weight: bold;}
        div#jsjobs-wrapper div.folder-wrapper div.folder-second span{color:<?php echo esc_attr($color8); ?> ; font-size: 14px; font-weight: bold;}
        div#jsjobs-wrapper div.folder-wrapper div.folder-second{border-bottom: 1px solid <?php echo esc_attr($color5); ?> ;}
        div#jsjobs-wrapper div.folder-wrapper div.folder-third div.button-section div.button{ border: solid 1px <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color6); ?>;}
        div#jsjobs-wrapper div.folder-wrapper div.folder-third div.button-section div.button a{color: <?php echo esc_attr($color4); ?>; font-size: 14px;}
        div#jsjobs-wrapper div.folder-wrapper div.folder-third div.button-section div.resume-button:hover{background: <?php echo esc_attr($color1); ?>; color: <?php echo esc_attr($color7); ?>;}
        div#jsjobs-wrapper div.resume-save-search-wrapper{ border:1px solid <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color3); ?>;}
        div#jsjobs-wrapper div.resume-save-search-wrapper div.div-left{border-bottom: 1px solid <?php echo esc_attr($color5); ?> ; border-right: 0px;}
        div#jsjobs-wrapper div.resume-save-search-wrapper div.div-left span{color:<?php echo esc_attr($color8); ?> ; font-size: 13px; font-weight: bold;}
        div#jsjobs-wrapper div.resume-save-search-wrapper div.div-middel span{color:<?php echo esc_attr($color8); ?> ; font-size: 14px; font-weight: bold;}
        div#jsjobs-wrapper div.resume-save-search-wrapper div.div-middel{border-bottom: 1px solid <?php echo esc_attr($color5); ?> ; border-right: 0px;}
        div#jsjobs-wrapper div.resume-save-search-wrapper div.div-right div.button{ border: solid 1px <?php echo esc_attr($color5); ?>; background: <?php echo esc_attr($color6); ?>;}
        div#jsjobs-wrapper div.resume-save-search-wrapper div.div-right div.button a{color: <?php echo esc_attr($color4); ?>; font-size: 14px;}
        div#jsjobs-wrapper div.resume-save-search-wrapper div.div-right div.button:hover{background: <?php echo esc_attr($color1); ?>; color: <?php echo esc_attr($color7); ?>;} 
        div#jsjobs-wrapper div.resume-save-search-wrapper span.upper-app-title{border-right: none;}
        div#js-jobs-wrapper div.js-toprow div.js-image a{border: 1px solid <?php echo esc_attr($color5); ?>;border-left: 4px solid <?php echo esc_attr($color1); ?>;}
        
        div#jsjobs-wrapper div.purchase-history-wrapper div.purchase-history-data span.data-price{border:none;border-top:1px solid <?php echo esc_attr($color5); ?>;}

        table#js-table tbody.stats tr td.title{border-right: 1px solid <?php echo esc_attr($color5); ?>;}
        table#js-table tbody.stats tr td.publish{border-right: 1px solid <?php echo esc_attr($color5); ?>;}
    }

        table#js-table tbody.stats tr td.gold{border-left:3px solid #Ff6600;}
        table#js-table tbody.stats tr td.feature{border-left:3px solid #00AFEF;}
        table#js-table tbody.stats tr td.simplejob{border-left:3px solid #65A324;}

    <?php  if (is_rtl()) {?>
        div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.control-pannel-header-2{border-left: 1px solid #d4d4d5;}
        div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.red-right {border-left: 1px solid <?php echo esc_attr($color5); ?> }
        div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.meg-right {border-left: 1px solid <?php echo esc_attr($color5);?> ;}
        div#jsjobs-wrapper div#companies-wrapper div.view-companies-wrapper div.company-upper-wrapper div.company-img a{border:none;}
        div#js-jobs-wrapper div.js-toprow div.js-image a { border: 1px solid <?php echo esc_attr($color5); ?>;border-right: 4px solid <?php echo esc_attr($color1); ?>;}
        div#jsjobs-wrapper div#view-job-wrapper div.main div.left{border: none;border-left:1px solid <?php echo esc_attr($color5);?>; }
        div#jsjobs-wrapper div.message-content-data div.data-left{border: none;border-left:1px solid <?php echo esc_attr($color5);?>; }


        table#js-table tbody.stats tr td.gold {border:1px solid <?php echo esc_attr($color5);?>;border-right: 3px solid #F60;}
        table#js-table tbody.stats tr td.feature{border:1px solid <?php echo esc_attr($color5);?>;border-right:3px solid #00AFEF;}
        table#js-table tbody.stats tr td.simplejob{border:1px solid <?php echo esc_attr($color5);?>;border-right:3px solid #65A324;}
        div#jsjobs-wrapper div#resume-list-wrraper div#resume-list div.resume-upper-wrapper div.resume-img a{border:1px solid <?php echo esc_attr($color5);?>;border-right:3px solid <?php echo esc_attr($color1);?>;}

        div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.right div.show-items div.job-wrapper div.img a { border: 1px solid <?php echo esc_attr($color5);?>;border-right:5px solid <?php echo esc_attr($color1);?>;}
        div#jsjobs-wrapper div#jobseeker-control-pannel-wrapper div.extra-activities div.left div.show-items div.job-wrapper div.img a{ border: 1px solid <?php echo esc_attr($color5);?>;border-right:5px solid <?php echo esc_attr($color1);?>;}

        
        div#jsjobs-wrapper div.department-content-data div.data-icons{border: none;border-right: 1px solid <?php echo esc_attr($color5);?>}
        div#jsjobs-wrapper div.folder-wrapper div.folder-second{border: none;border-left: 1px solid <?php echo esc_attr($color5);?>}
        div#jsjobs-wrapper div.folder-wrapper div.folder-firsl{border: none;border-left: 1px solid <?php echo esc_attr($color5);?>}
        div#jsjobs-wrapper div#folder-resume-wrapper div.jobs-upper-wrapper div.job-img{border: 1px solid <?php echo esc_attr($color5);?>;border-right:3px solid <?php echo esc_attr($color1);?> }
        div#jsjobs-wrapper div#send-message-wrapper div.top-data span.top-data-img div{border: 1px solid <?php echo esc_attr($color5);?>;border-right:3px solid <?php echo esc_attr($color1);?> }
        div#jsjobs-wrapper div#send-message-wrapper div.message-history span.js-msg-left div{border: 1px solid <?php echo esc_attr($color5);?>;border-right:3px solid <?php echo esc_attr($color1);?> }

    <?php } ?>
</style>
