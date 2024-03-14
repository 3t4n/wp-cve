<?php
/*
Plugin Name: Remove Blank P Tag
Plugin URI: http://www.godazzle.in/remove-blank-p-tag.zip
Description: This plugin removes empty p and br tag from the post and page content.
Version: 1.1.1
Author: Vishit Shah
Author URI: https://www.linkedin.com/in/vishit-shah-5b393383/
License: GPLv2
*/

/* Plugin Licence

Copyright 2014 VISHIT SHAH (email : vishit99@gmail.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

   remove_filter('the_excerpt', 'wpautop');
   remove_filter('the_content', 'wpautop');
   remove_filter('widget_text_content', 'wpautop');
?>
