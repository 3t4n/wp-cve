<?php
$ezOptions['kill_feed'] = array('name' => __("RSS feeds", 'easy-adsenser'),
    'help' => __("Suppress ads on RSS feeds from your blog? This option may not work because the flag indicating that the content is an RSS feed may not be ready when the plugin checks for it. Use the <strong>Pro</strong> option <strong>Allow ad blocks in feeds?</strong> if your feeds do not validate.", 'easy-adsenser'),
    'value' => 1,
    'type' => 'checkbox2');
$ezOptions['kill_page'] = array('name' => '<a href="http://codex.wordpress.org/Pages" target="_blank">' . __('Static Pages', 'easy-adsenser') . '</a>',
    'help' => __("If checked, ads will appear only on blog posts, not on blog pages. Click to see the difference between posts and pages.", 'easy-adsenser'),
    'value' => 1,
    'type' => 'checkbox2');
$ezOptions['kill_home'] = array('name' => __("Home Page", 'easy-adsenser'),
    'help' => __("Suppress ads on your home page? Home Page and Front Page are the same for most blogs.", 'easy-adsenser'),
    'value' => 0,
    'type' => 'checkbox2');
$ezOptions['kill_front_page'] = array('name' => __("Front Page", 'easy-adsenser'),
    'help' => __("Suppress ads on your front page? Home Page and Front Page are the same for most blogs.", 'easy-adsenser'),
    'value' => 0,
    'type' => 'checkbox2');
$ezOptions['kill_attachment'] = array('name' => __("Attachment Page", 'easy-adsenser'),
    'help' => __("Suppress ads on pages that show attachments?", 'easy-adsenser'),
    'value' => 1,
    'type' => 'checkbox2');
$ezOptions['kill_category'] = array('name' => __("Category Pages", 'easy-adsenser'),
    'help' => __("Suppress ads on pages that come up when you click on category names?", 'easy-adsenser'),
    'value' => 0,
    'type' => 'checkbox2');
$ezOptions['kill_search'] = array('name' => __("Search Page", 'easy-adsenser'),
    'help' => __("Suppress ads on pages that show search results?", 'easy-adsenser'),
    'value' => 1,
    'type' => 'checkbox2');
$ezOptions['kill_sticky'] = array('name' => __("Sticky Front Page", 'easy-adsenser'),
    'help' => __("Suppress ads on posts that are defined as the sticky front page?", 'easy-adsenser'),
    'value' => 1,
    'type' => 'checkbox2');
$ezOptions['kill_tag'] = array('name' => __("Tag Pages", 'easy-adsenser'),
    'help' => __("Suppress ads on pages that come up when you click on tag names?", 'easy-adsenser'),
    'value' => 0,
    'type' => 'checkbox2');
$ezOptions['kill_archive'] = array('name' => __("Archive Pages", 'easy-adsenser'),
    'help' => __("Suppress ads on pages that come up when you click on year/month archives?", 'easy-adsenser'),
    'value' => 0,
    'type' => 'checkbox2');
