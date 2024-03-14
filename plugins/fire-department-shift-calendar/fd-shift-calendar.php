<?php

	/*
	Plugin Name: 	Fire Department Shift Calendar
	Description:	Displays fire department shift calendar using a widget or shortcodes
	Version:		0.9.2
	Author:       	Mark Salvadore
	Author URI:		https://dev.recursion.la/contact/
	Author Email: 	mark@recursion.la
	Text Domain: 	fd_shift_calendar
	License:		GPLv2
	License URI:	http://www.gnu.org/licenses/gpl-2.0.html

	Copyright (C) 2018 Mark Salvadore
	Other portions copyright as indicated by authors in the relevant files

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
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
	*/
	

	// Set globals for plugin
	define('FD_SHIFT_CAL_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
	define('FD_SHIFT_CAL_PLUGIN_URL', plugin_dir_url( __FILE__ ));
	define('FD_SHIFT_CAL_BASENAME', plugin_basename( __FILE__ ));
	define('FD_SHIFT_CAL_SLUG', 'fd-shift-calendar');
	

	// Enqueues
	require FD_SHIFT_CAL_PLUGIN_PATH . 'functions/admin.php';

	// Enqueues
	require FD_SHIFT_CAL_PLUGIN_PATH . 'functions/enqueues.php';

	// Helpers
	require FD_SHIFT_CAL_PLUGIN_PATH . 'functions/helpers.php';

	// Calendar
	require FD_SHIFT_CAL_PLUGIN_PATH . 'functions/calendar.php';

	// Menu
	require FD_SHIFT_CAL_PLUGIN_PATH . 'functions/menu.php';

	// Shortcode
	require FD_SHIFT_CAL_PLUGIN_PATH . 'functions/shortcode.php';

	// Widget
	require FD_SHIFT_CAL_PLUGIN_PATH . 'functions/widget.php';

?>