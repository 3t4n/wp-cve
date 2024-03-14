<?php
/*
Plugin Name: Limit Post Add-On
Plugin URI: https://doc4design.com/limit-post-add-on/
Description: Limits the displayed text length with both the_content_limit and get_the_content_limit
Version: 1.4
Requires at least: 2.7
Author: Doc4, Alfonso Sanchez-Paus Diaz, Julian Simon de Castro
Author URI: https://doc4design.com
License: GPL v2.0 or later
License URL: https://www.gnu.org/licenses/gpl-2.0.html
*/

/******************************************************************************

Copyright 2008 - 2024  Doc4 : info@doc4design.com

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
The license is also available at https://www.gnu.org/licenses/gpl-2.0.html

*********************************************************************************/



function the_content_limit($max_char, $more_link_text = '(more...)', $stripteaser = 0, $more_file = '') {
    $content = get_the_content($more_link_text, $stripteaser, $more_file);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);

   if (strlen($_GET['p']) > 0) {
      echo $content;
   }
   else if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) {
        $content = substr($content, 0, $espacio);
        $content = $content;
        echo $content;
        //echo "<a href='";
        //the_permalink();
        echo "...";
        echo "<br>";
        echo "<div class=";
        echo "'read-more'>";
        echo "<a href='";
        the_permalink();
        echo "'>".$more_link_text."</a></div></p>";
   }
   else {
      echo $content;
   }
}

function get_the_content_limit($max_char, $more_link_text = '(more...)', $stripteaser = 0, $more_file = '') {
    $content = get_the_content($more_link_text, $stripteaser, $more_file);
    $content = apply_filters('get_the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    $content = strip_tags($content);

   if (strlen($_GET['p']) > 0) {
      echo $content;
   }
   else if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) {
        $content = substr($content, 0, $espacio);
        $content = $content;
        echo $content;
        //echo "<a href='";
        //the_permalink();
        echo "...";
        echo "<br>";
        echo "<div class=";
        echo "'read-more'>";
        echo "<a href='";
        the_permalink();
        echo "'>".$more_link_text."</a></div></p>";
   }
   else {
      echo $content;
   }
}

?>
