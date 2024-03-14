<?php
/*  Copyright 2008 Felix Triller  (email : info@felixtriller.de)

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
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


    Plugin Name: wpSpoiler
    Plugin URI: http://felixtriller.de/projekte/wpspoiler/
    Description: Transforms [spoiler]text[/spoiler] to a hidden box with a Show/Hide Button
    Version: 1.2
    Author: Felix Triller
    Author URI: http://felixtriller.de/


    Features:
    - easy to use, only one tag for a spoilertext
    - useable for every text, not only spoilers
    - less code, very fast

    Installation:
    - extract the archive, and upload the wpSpoiler directory to your wp-content/plugins/ folder!
    - active wpSpoiler in your wordpress admin panel
    - customize you stylesheet if wished

    Usage:
    - for use in posts and pages
    - enclose spoiler text between [spoiler] and [/spoiler]

    Changelog:
        2008-05-31: 1.2
            Bugfix: messed up with the svn :D

        2008-05-26: 1.1
            Bugfix: adding support for the_excerpt_reloaded() and the_excerpt() function 
                thanks to Eddy http://summtrulli.de/)
        Bugfix: Typo in plugin description

        2007-03-28: 1.0
            Initial release

*/

function wpSpoiler($text) {

    /* Config */
    $showtext = 'show';
    $hidetext = 'hide';
   
    // dont edit!
    $pattern = '@(\[spoiler\](.*?)\[/spoiler\])@is';
 
    // replace every [spoiler]...[/spoiler] tags
    if (preg_match_all($pattern, $text, $matches)) {
        
        for ($i = 0; $i < count($matches[0]); $i++) {
            $id   = 'id'.rand();
            $html = '';
       
            $html .= '<a class="spoiler_link_show" href="javascript:void(0)" onclick="wpSpoilerToggle(document.getElementById(\''.$id.'\'), this, \''.$showtext.'\', \''.$hidetext.'\')">'.$showtext.'</a>'.PHP_EOL;
            $html .= '<div class="spoiler_div" id="'.$id.'" style="display:none">'.$matches[2][$i].'</div>'.PHP_EOL;

            $text = str_replace($matches[0][$i], $html, $text);
        }

    }

    return $text;
}

function wpSpoiler_head() {

    // javascript
    $s = "<!-- wpSpoiler Code -->
        <script type=\"text/javascript\">
            function wpSpoilerToggle(spoiler, link, showtext, hidetext) {
                if (spoiler.style.display != 'none') {
                    spoiler.style.display = 'none';
                    link.innerHTML = showtext;
                    link.className = 'spoiler_link_show';
                } else {
                    spoiler.style.display = 'block';
                    link.innerHTML = hidetext;
                    link.className = 'spoiler_link_hide';
                }
            }
          </script>".PHP_EOL;
    echo $s;
}

// hooks
add_filter('the_content', 'wpSpoiler');
add_filter('the_excerpt', 'wpSpoiler');
add_filter('wp_head', 'wpSpoiler_head');

?>
