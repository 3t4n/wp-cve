<?php

$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
$plugin_url = admin_url() . '?page=wp-share-buttons-analytics-by-getsocial%2Finit.php';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Popup Test</title>

    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700' rel='stylesheet' type='text/css'>

    <style>
        body{
            background: #ddd;
        }
        .gs-sidebar-tooltip{
            font-family: 'Source Sans Pro', sans-serif;
            width: 350px;
            color: #606060;
            background: #fff;
            border-radius: 4px;
            padding: 10px 25px;
            margin-bottom: 10px;
            position: absolute;
            bottom: 100%;
            left: 20px;
            box-shadow: 0 1px 30px rgba(0,0,0,.2);
            z-index: 9999999;
            cursor: initial;
        }
        .gs-sidebar-tooltip:before{
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 14px 14px 0 14px;
            border-color: #fff transparent transparent transparent;
            content: '';
            display: block;
            position: absolute;
            top: 100%;
            left: 20px;
        }
        .gs-sidebar-tooltip .gs-sidebar-tooltip-title{
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            background: #02b6b3;
            text-align: center;
            border-radius: 4px 4px 0 0;
            padding: 12px 25px;
            margin: -10px -25px 10px;
        }
        .gs-sidebar-tooltip p{
            font-size: 14px;
            line-height: 17px;
            margin-bottom: 10px;
        }
        .gs-sidebar-tooltip .gs-sidebar-tooltip-link{
            text-align: center;
            margin: 15px 0 10px;
        }
        .gs-sidebar-tooltip a{
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block !important;
            padding: 6px 15px 8px !important;
            border-radius: 4px;
            -webkit-transition: all 200ms;
            transition: all 200ms;
        }
        .gs-sidebar-tooltip a:not(.gs-close-tooltip){
            color: #fff !important;
            background: #02b6b3;
            margin-right: 8px;
        }
        .gs-sidebar-tooltip a:not(.gs-close-tooltip):hover, .gs-sidebar-tooltip a:not(.gs-close-tooltip):focus{
            color: #fff !important;
            background: #2687b9;
        }
        .gs-sidebar-tooltip a.gs-close-tooltip{
            color: #d36c65 !important;
            background: #fff;
        }
        .gs-sidebar-tooltip a.gs-close-tooltip:hover, .gs-sidebar-tooltip a.gs-close-tooltip:focus{
            color: #AB4F48 !important;
        }
        .gs-sidebar-tooltip .gs-sidebar-tooltip-video{
            margin: 20px 0 15px;
        }
        .gs-sidebar-tooltip .gs-sidebar-tooltip-video iframe{
            width: 100%;
            min-height: 175px;
        }
        .gs-sidebar-tooltip.gs-menu-hidden {
            position: fixed;
            bottom: 5px;
        }
    </style>
</head>
<body>
    <div id="getsocial-onboarding-popup" class="gs-sidebar-tooltip">
        <div class="gs-sidebar-tooltip-title">Welcome to GetSocial!</div>
        <p>Thanks for installing <strong>GetSocial.io</strong>, the plugin that grows your traffic, shares and subscribers on WordPress.</p>
        <p>Here’s a quick video on how to install and use GetSocial:</p>

        <div class="gs-sidebar-tooltip-video">
            <iframe src="https://www.youtube.com/embed/o4P6utEAN54" frameborder="0" allowfullscreen></iframe>
        </div>

        <p>Thanks for installing GetSocial.io, the plugin that grows your traffic, shares and subscribers on WordPress.</p>

        <div class="gs-sidebar-tooltip-link">
            <a href="<?php echo $plugin_url; ?>">Open Plugin</a>
            <a href="javascript:close_onboarding_popup()" class="gs-close-tooltip">Close</a>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">

function close_onboarding_popup() {

    var data = {
        'action': 'save_popup_visit',
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajaxurl, data, function(response) {
    });

    jQuery('#getsocial-onboarding-popup').hide('slow');
}
</script>
