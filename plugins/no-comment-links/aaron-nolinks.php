<?php
/*
Plugin Name: No Comment Links
Plugin URI: http://aaron-kelley.net/tech/wordpress/plugin-nolinks/
Description: When activated, disables automatic parsing and creation of clickable links in comments, including http, ftp, and e-mail links.  Links can still be added using HTML tags, if allowed.  Requires WordPress 1.5 or later.
Version: 1.0.1
Author: Aaron A. Kelley
Author URI: http://aaron-kelley.net/
*/

/*  Copyright 2009-2010  Aaron A. Kelley  (email : aaronkelley@hotmail.com)

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
*/

remove_filter('comment_text', 'make_clickable', 9);

?>