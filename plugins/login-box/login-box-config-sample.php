<?php
define("LB_THEME", "wp25");
// Type the Login-box theme

define("LB_KEY", "e");
// Choose the key (case insensitive) that will be open/close Login-box with Ctrl or Alt
// Note that this may cancel the default function of the Ctrl/Alt + key of the browser
// Ex: If you choose A, users cannot use Ctrl + A to select all texts in your blog

define("LB_CTRL", true);
// Also, you can disable Ctrl + key functions in Login-box defining this as false
// So, Login-box only will be open with Alt + key

define("LB_BACKTOPAGE", true);
// true: When login, you will be redirected to the actual page
// false: When login, you will be redirected to the WordPress Dashboard

define("LB_FADE", true);
// true: Show/hide Login-box with fadeIn/fadeOut
// false: Without fadeIn/fadeOut

define("LB_AUTO", true);
// Advanced: Set to false and Login-box will not be inserted automatically in your theme.
?>