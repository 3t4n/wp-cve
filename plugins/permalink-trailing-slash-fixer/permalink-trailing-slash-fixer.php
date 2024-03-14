<?php
/*
Plugin Name: Permalink Trailing Slash Fixer
Plugin URI: http://pioupioum.fr/wordpress/plugins/permalink-trailing-slash-fixer.html
Version: 1.0.1
Description: Quickly add a trailing slash in the URLs if it's missing in the permalink structure. Note: Single posts permalink are skipped.
Author: Mehdi Kabab
Author URI: http://pioupioum.fr/
*/
/*
# ***** BEGIN LICENSE BLOCK *****
# Copyright (C) 2009 Mehdi Kabab <http://pioupioum.fr/>
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# ***** END LICENSE BLOCK ***** */

/**
 * Public staff only.
 */
if (is_admin()) return;

$permalink_structure = get_option('permalink_structure');
if (!$permalink_structure || '/' === substr($permalink_structure, -1))
	return;

add_filter('user_trailingslashit', 'ppm_fixe_trailingslash', 10, 2);

/**
 * Appends a trailing slash if it's missing in the permalink structure.
 *
 * Conditionally adds a trailing slash if the url type is not "single".
 *
 * @param string $url A URL with or without a trailing slash.
 * @param string $type The type of URL being considered (e.g. single, category, etc).
 * @return string The URL with the trailing slash fixed.
 */
function ppm_fixe_trailingslash($url, $type)
{
	if ('single' === $type)
		return $url;

	return trailingslashit($url);
}
