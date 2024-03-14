<?php
/*
	Plugin Name: URL ShortCodes
	Plugin URI: http://cgarvey.ie/
	Feed URI: 
	Description: Adds support for a [url_base] and [url_template] shortcodes for use in your post/page editor.
	Version: 1.2
	Author: Cathal Garvey
	Author URI: http://cgarvey.ie/
*/

/*
	Copyright (c) 2010-2013 Cathal Garvey ( http://cgarvey.ie/ )

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

// [url_base]
function url_base_function() {
	return get_bloginfo( "url" );
}
add_shortcode('url_base', 'url_base_function');

// [url_template]
function url_template_function() {
	if( get_theme_root_uri() && get_template() ) {
		return get_theme_root_uri() . "/" . get_template();
	}
	else {
		return "";
	}
}
add_shortcode('url_template', 'url_template_function');
?>
