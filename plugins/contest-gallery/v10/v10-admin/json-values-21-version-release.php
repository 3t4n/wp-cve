<?php

if(cg_get_version()=='contest-gallery-pro'){
    $ShareButtons = 'email,sms,gmail,yahoo,evernote,facebook,whatsapp,twitter,skype,telegram,pinterest,reddit,xing,linkedin,vk,okru,qzone,weibo,douban,renren';
}else{
    $ShareButtons = 'email,sms,gmail,yahoo,evernote,skype,telegram,pinterest,reddit,xing,linkedin';
}

$TextBeforeWpPageEntry = contest_gal1ery_htmlentities_and_preg_replace('<p  style="text-align: center;">This is <strong>"General text on entry landing page before an activated entry"</strong>, is only visible on an entry landing page of an entry and can be configured for every cg_gallery shortcode type in <strong>"Edit options" >>> "Entry view"</strong><br><strong>NOTE:</strong>the cg_gallery... shortcode with entry_id added to this page can be placed on any other of your pages<br><strong>NOTE:</strong> social media share icons can be configured in <strong>"Edit options" >>> "Entry view"</strong><br><strong>NOTE:</strong> "Back to gallery button custom URL" can be configured in <strong>"Edit options" >>> "Entry view"</strong></p>');
$TextAfterWpPageEntry = contest_gal1ery_htmlentities_and_preg_replace('<p  style="text-align: center;">This is <strong>"General text on entry landing page after an activated entry"</strong>, is only visible on an entry landing page of an entry and can be configured for every cg_gallery shortcode type in <strong>"Edit options" >>> "Entry view"</strong><br><strong>NOTE:</strong>the cg_gallery... shortcode with entry_id added to this page can be placed on any other of your pages<br><strong>NOTE:</strong> social media share icons can be configured in <strong>"Edit options" >>> "Entry view"</strong><br><strong>NOTE:</strong> "Back to gallery button custom URL" can be configured in <strong>"Edit options" >>> "Entry view"</strong></p>');
$ForwardToWpPageEntry = 0;
$ForwardToWpPageEntryInNewTab = 0;
$ShowBackToGalleryButton = 1;
$BackToGalleryButtonText = 'Back to gallery';
$BackToGalleryButtonURL = '';
$WpPageParentRedirectURL = '';
$TextDeactivatedEntry = contest_gal1ery_htmlentities_and_preg_replace('<p style="text-align: center;"><b>Entry is deactivated</b><br><br>This text can be configurated in "Edit options" >>> "Entry view options" >>> "Text on entry landing page if entry is deactivated"
<br>Every Contest Gallery entry has own entry pages for every cg_gallery shortcode type</p>');
$RedirectURLdeletedEntry = '';

$AdditionalCssGalleryPage = "body {\r\n&nbsp;&nbsp;font-family: sans-serif;\r\n&nbsp;&nbsp;font-size: 16px;\r\n&nbsp;&nbsp;background-color: white;\r\n&nbsp;&nbsp;color: black;\r\n}";
$AdditionalCssEntryLandingPage = "body {\r\n&nbsp;&nbsp;font-family: sans-serif;\r\n&nbsp;&nbsp;font-size: 16px;\r\n&nbsp;&nbsp;background-color: white;\r\n&nbsp;&nbsp;color: black;\r\n}";

$WpPageParent = 0;
$WpPageParentUser = 0;
$WpPageParentNoVoting = 0;
$WpPageParentWinner = 0;
