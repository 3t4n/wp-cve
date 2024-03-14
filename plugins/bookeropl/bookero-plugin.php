<?php
/*
   Plugin Name: Bookero Plugin
   Plugin URI: http://wordpress.org/extend/plugins/bookero-plugin/
   Version: 1.4
   Author: Bookero.pl
   Description: Wtyczka do wordpress, wyświetlająca formularz rezerwacji online Bookero
   Text Domain: bookero-plugin
   License: GPLv2 or later
*/

include_once('libraries/bookero.php');
include_once('libraries/bookero-panel.php');
include_once('libraries/bookero-settings.php');
include_once('libraries/bookero-front.php');


function getPluginDir() {
    return plugin_dir_path(__FILE__);
}

if( is_admin() ){
    $bookero_panel = new BookeroPanelPage();
}
else{
    $bookero_front_page = new BookeroFrontPage();
}