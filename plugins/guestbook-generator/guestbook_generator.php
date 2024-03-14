<?php
/*
Plugin Name: Guestbook Generator
Plugin URI: http://www.alleba.com/blog/2006/09/21/wordpress-guestbook-generator-plugin/
Description: Generates a guestbook for Wordpress blogs. Once activated, click on Options > Guestbook Generator.
Version: 0.8
Author: Andrew dela Serna
Author URI: http://www.alleba.com/blog/

  Copyright 2006 Andrew dela Serna (email andrew@alleba.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

INSTRUCTIONS
------------
1. Download the plugin from http://www.alleba.com/blog/wp-downloads/guestbook-generator.zip
2. Extract and upload the contents of the archive to 'yourserver.com/wp-content/plugins/guestbook-generator/'
3. Login to your Wordpress admin panel and browse to the Plugins section.
4. Activate the Guestbook Generator plugin.
5. Go to Options > Guestbook Generator to create your guestbook.
6. That's it!
    
*/
function gg_add_pages() {
    add_options_page('Guestbook Generator', 'Guestbook Generator', 8, __FILE__, 'gg_options_page');
}
function gg_options_page() {
	  $wpv = get_bloginfo('version');
	  echo '<div class="wrap">';
    echo '<h2>Guestbook Generator</h2>';
    if ($_POST['generate']) {
	  include('guestbook_utility.php');
	  $gb = generate_guestbook();
	  echo $gb;
  } else {
  	if ($wpv < 2.1) {
  	echo '<span style="color: red; font-weight: bold;">Warning:</span> Guestbook Generator v0.8 is compatible with Wordpress 2.1 and above.  Please visit <a href="http://www.alleba.com/blog/2006/09/21/wordpress-guestbook-generator-plugin/">this page</a> to download v0.7 for older versions of Wordpress.<br />';
    echo '<p class="submit"><input type="submit" name="generate" value="Generate Guestbook &raquo;" style="color:#cccccc;"/></p>';
    } else {
    echo 'This plugin will generate a guestbook for your Wordpress blog based on the current selected theme.<br />';
    echo 'You may repeat this process everytime you change themes but you must make sure that the files <strong>single.php</strong> and <strong>comments.php</strong> are present.<br />';
    echo 'The files <strong>guestbook.php</strong> and <strong>guestcomments.php</strong> will be added and saved into your current theme folder ';
    echo 'which you can edit later on in the Theme Editor.<br />';
    echo '<form method="post" action="">';
    echo '<p class="submit"><input type="submit" name="generate" value="Generate Guestbook &raquo;" /></p>';
    echo '</form>';
         }
    }
    echo '</div>';
}
add_action('admin_menu', 'gg_add_pages');
?>