<?php
/*
Plugin Name: Seemore
Plugin URI: http://thunderguy.com/semicolon/wordpress/seemore-wordpress-plugin/
Description: Change the (more...) link so it displays the entire post, not just the part after the "more".
Version: 1.1.1
Author: Bennett McElwee
Author URI: http://thunderguy.com/semicolon/

$Revision: 74 $

Copyright (C) 2005-12 Bennett McElwee (bennett at thunderguy dotcom)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License,
available at http://www.gnu.org/copyleft/gpl.html
*/

add_filter('the_content', 'tguy_seemore_remove_anchor');

function tguy_seemore_remove_anchor($content) {
/*	
	Remove the anchor portion from links like
	<a href="http://www.com/blog/the-post/#more-23" ...>
	(Will remove the #more-23 part)
*/
	global $id;
	return str_replace('#more-'.$id.'"', '"', $content);
}
