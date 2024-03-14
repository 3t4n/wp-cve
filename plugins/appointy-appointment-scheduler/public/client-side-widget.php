<?php

function OldAppointyWidget()
{
    $helper = new Appointy_helper_functions();
    $settings = new AppointySettings($helper);
    $settings->ParseFromSettingString($helper->get_iframe_val());

    if ($settings->widget == "website") {
        $url_components = parse_url($settings->url);
        if (strcmp($url_components["host"], "booking.appointy.com") == 0) {
            echo "<script type='text/javascript' src='https://code.jquery.com/jquery-2.2.4.min.js'></script>";
            echo "<script type='text/javascript' src='" . plugin_dir_url(__FILE__) . "js/new-appointy-widget.js'></script>";
            echo "<script type='text/javascript'> widget('" . $settings->GetUserNameFromUrl() . "', '" . $settings->maxWidth . "', '" . $settings->maxHeight . "', '');</script>";
            echo "<script type='text/javascript' src='https://cdn.appointy.com/web/blob-web/js/appointy-widget.js'></script>";
        } else {
            echo "<script type='text/javascript' src='https://code.jquery.com/jquery-2.2.4.min.js'></script>";
            echo "<script type='text/javascript' src='" . plugin_dir_url(__FILE__) . "js/old-appointy-widget.js'></script>";
            echo "<script type='text/javascript'> widget('" . $settings->GetUserNameFromUrl() . "', '" . $settings->maxWidth . "', '" . $settings->maxHeight . "', '');</script>";
            echo "<script type='text/javascript' src='http://static.appointy.com/widget/js/appointyOverlayGadget.js'></script>";
        }
    }
}
