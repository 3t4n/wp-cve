<?php

$ezOptions = array();
$ezOptions['ad_text'] = array('name' => __("Ad Blocks in Your Posts", 'easy-adsenser') . "<br><small>" . __('[Appears in your posts and pages]', 'easy-adsenser') . "</small>",
    'help' => __("This ad block will appear within the body of your posts and pages. Generate your AdSense code from your Google AdSense page and paste it here. <p class=\"red\">If you have trouble saving the ad code, try replacing the string <code>&amp;lt;script&amp;gt;</code>  to <code>&amp;lt;_script&amp;gt;</code> wherever it appears. Some servers have security settings preventing submission of strings containing <code>&amp;lt;script&amp;gt;</code>. The plugin will revert this replacement and insert the right code on your pages and posts.</p>", 'easy-adsenser'),
    'type' => 'textarea',
    'value' => EzGA::$options['defaultText']);

require 'box-ad-alignment-options.php';
require 'box-suppressing-ads-options.php';
require 'pro-options.php';
