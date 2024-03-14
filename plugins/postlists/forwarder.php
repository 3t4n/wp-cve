<?php
//-----------------------------------------------------------------------------
/*
Plugin Name: PostLists
Version: 2.0.2
Plugin URI: http://www.rene-ade.de/inhalte/wordpress-plugin-postlists.html
Description: This WordPress plugin provides placeholders for configurable dynamic lists of posts, that can be used in posts, pages, widgets or template files. After activation please go to "Manage" and then to the submenu "<a href="edit.php?page=postlists">PostLists</a>", to manage your lists.
Author: Ren&eacute; Ade
Author URI: http://www.rene-ade.de
*/
//-----------------------------------------------------------------------------
?>
<?php
  function pl_plugin_basename() {
    return plugin_basename(__FILE__);
  }
  include 'postlists/postlists.php';
?>