<?php
/*
Plugin Name: Advanced Disable Parent Menu Link
Description: A plugin which allows you to disable parent menu link.
Author: Kapil Chugh
Plugin URI: http://kapilchugh.wordpress.com/
Version: 1.0
*/

  add_action('wp_footer', 'advanced_disable_parent_menu_link');

  function advanced_disable_parent_menu_link () {
    wp_print_scripts('jquery'); ?>
    <script type="text/javascript">
      if (jQuery("ul li.page_item:has(ul.children)").length > 0) {
        jQuery("ul li.page_item:has(ul.children)").hover(function () {
          jQuery(this).children("a").removeAttr('href');
          jQuery(this).children("a").css('cursor', 'default');
          jQuery(this).children("a").click(function () {
            return false;
          });
        });
      }	else if (jQuery("ul li.menu-item:has(ul.sub-menu)").length > 0) {
        jQuery("ul li.menu-item:has(ul.sub-menu)").hover(function () {
          jQuery(this).children("a").removeAttr('href');
          jQuery(this).children("a").css('cursor', 'default');
          jQuery(this).children("a").click(function () {
            return false;
          });
        });
      }
    </script> <?php
  }