<?php

if (!defined('ABSPATH')) { exit; }

$_panel = gdmaq_admin()->panel === false ? 'whatsnew' : gdmaq_admin()->panel;

if (!in_array($_panel, array('changelog', 'whatsnew', 'info', 'dev4press'))) {
    $_panel = 'whatsnew';
}

include(GDMAQ_PATH.'forms/about/header.php');

include(GDMAQ_PATH.'forms/about/'.$_panel.'.php');

include(GDMAQ_PATH.'forms/about/footer.php');
