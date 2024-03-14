<?php
/*
Plugin Name: MoonClerk WP Shortcode
Description: This plugin gives you the ability to add MoonClerk embeds via Shortcodes.
Version: 1.0.8
Tags: embeds, moonclerk, recurring payments, payments, recurring billing, billing, subscription billing, shortcode
Author URI: http://www.moonclerk.com

/--------------------------------------------------------------------\
|                                                                    |
| License: GPL                                                       |
|                                                                    |
| WordPress MoonClerk Embeds - embeds via Shortcodes.                |
| Copyright (C) 2012, MoonClerk,                                     |
| http://moonclerk.com                                               |
| All rights reserved.                                               |
|                                                                    |
| This program is free software; you can redistribute it and/or      |
| modify it under the terms of the GNU General Public License        |
| as published by the Free Software Foundation; either version 2     |
| of the License, or (at your option) any later version.             |
|                                                                    |
| This program is distributed in the hope that it will be useful,    |
| but WITHOUT ANY WARRANTY; without even the implied warranty of     |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the      |
| GNU General Public License for more details.                       |
|                                                                    |
| You should have received a copy of the GNU General Public License  |
| along with this program; if not, write to the                      |
| Free Software Foundation, Inc.                                     |
| 51 Franklin Street, Fifth Floor                                    |
| Boston, MA  02110-1301, USA                                        |
|                                                                    |
\--------------------------------------------------------------------/
*/

// readme.txt help
// http://www.smashingmagazine.com/2011/11/23/improve-wordpress-plugins-readme-txt/

// svn help
// https://wordpress.org/plugins/about/svn/

// protect yourself
if ( !function_exists( 'add_action') ) {
  echo "Hi there! Nice try. Come again.";
  exit;
}

class moonClerk_wp {
  // when object is created
  function __construct() {
    add_action('admin_menu', array($this, 'admin_menu')); // add item to menu

    // Uncomment this part if an API is wanted to be added.
    // Link: http://www.sitepoint.com/adding-a-media-button-to-the-content-editor/
    // This will add a moonclerk button above the media editor.

    // this will add the media button, all that needs to be added is the forms list in a model box for example
    // add_action('media_buttons', array($this, 'media_buttons'), 99 );
    // add_action('wp_enqueue_media', array($this, 'wp_enqueue_media'));
  }

  // make menu
  function admin_menu() {
    add_submenu_page('tools.php', 'MoonClerk Embeds', 'MoonClerk', 'switch_themes', __FILE__,array($this, 'settings_page'), '', '');
  }

  // create page for output and input
  function settings_page() {
    include('page.php');
  }

  function media_buttons() {
    echo '<a href="#" id="insert-moonclerk-media" class="button">MoonClerk Form</a>';
  }

  function wp_enqueue_media() {
    wp_enqueue_script('media_button', plugin_dir_url(__FILE__) .  'media_btn.js', array('jquery'), '1.0', true);
  }

}

new moonClerk_wp();
include('shortcodes.php');
