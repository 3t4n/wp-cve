<?php

/*
 *
 *	Plugin Name: Export emails
 *	Plugin URI: http://www.joeswebtools.com/wordpress-plugins/export-emails/
 *	Description: Adds an export emails page that allows you to export the email list of your subscribers and the email list of all the people who left comments. Go to <a href="tools.php?page=export-emails/export-emails.php">Tools &rarr; Export Emails</a> after activating the plugin to access the email lists.
 *	Version: 1.3.1
 *	Author: Joe's Web Tools
 *	Author URI: http://www.joeswebtools.com/
 *
 *	Copyright (c) 2009 Joe's Web Tools. All Rights Reserved.
 *
 *	This program is free software; you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation; either version 2 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program; if not, write to the Free Software
 *	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 *	If you are unable to comply with the terms of this license,
 *	contact the copyright holder for a commercial license.
 *
 *	We kindly ask that you keep links to Joe's Web Tools so
 *	other people can find out about this plugin.
 *
 */





/*
 *
 *	export_email_page
 *
 */

function export_email_page() {

	global $wpdb;

	// Page wrapper start
	echo '<div class="wrap">';

	// Title
	screen_icon();
	echo '<h2>Export Emails</h2>';

	// Emails from the user database
	echo	'<div id="poststuff" class="ui-sortable">';
	echo		'<div class="postbox opened">';
	echo			'<h3>Emails from the user database</h3>';
	echo			'<div class="inside">';
	echo				'<form method="post">';
	echo					'<table  class="form-table">';
	echo						'<tr>';
	echo							'<th scope="row" valign="top">';
	echo								'<b>Users emails</b>';
	echo							'</th>';
	echo							'<td>';
	echo								'<textarea readonly="readonly" rows="10" cols="40" onfocus="javascript:this.select();">';
											$email = $wpdb->get_col("SELECT user_email FROM $wpdb->users GROUP BY user_email");
											foreach($email as $email_out) {
												echo $email_out . "\r\n";
											}
	echo								'</textarea>';
	echo							'</td>';
	echo						'</tr>';
	echo					'</table>';
	echo				'</form>';
	echo			'</div>';
	echo		'</div>';
	echo	'</div>';

	// Emails from the comment database
	echo	'<div id="poststuff" class="ui-sortable">';
	echo		'<div class="postbox opened">';
	echo			'<h3>Emails from the comment database</h3>';
	echo			'<div class="inside">';
	echo				'<form method="post">';
	echo					'<table  class="form-table">';
	echo						'<tr>';
	echo							'<th scope="row" valign="top">';
	echo								'<b>Commenters emails</b>';
	echo							'</th>';
	echo							'<td>';
	echo								'<textarea readonly="readonly" rows="10" cols="40" onfocus="javascript:this.select();">';
											$email = $wpdb->get_col("SELECT comment_author_email FROM $wpdb->comments WHERE comment_approved<>'spam' GROUP BY comment_author_email");
											foreach($email as $email_out) {
												echo $email_out . "\r\n";
											}
	echo								'</textarea>';
	echo							'</td>';
	echo						'</tr>';
	echo					'</table>';
	echo				'</form>';
	echo			'</div>';
	echo		'</div>';
	echo	'</div>';

	// About
	echo	'<div id="poststuff" class="ui-sortable">';
	echo		'<div class="postbox opened">';
	echo			'<h3>About</h3>';
	echo			'<div class="inside">';
	echo				'<form method="post">';
	echo					'<table  class="form-table">';
	echo						'<tr>';
	echo							'<th scope="row" valign="top">';
	echo								'<b>Like this plugin?</b>';
	echo							'</th>';
	echo							'<td>';
	echo								'Developing, maintaining and supporting this plugin requires time. Why not do any of the following:<br />';
	echo								'&nbsp;&bull;&nbsp;&nbsp;Check out our <a href="http://www.joeswebtools.com/wordpress-plugins/">other plugins</a>.<br />';
	echo								'&nbsp;&bull;&nbsp;&nbsp;Link to the <a href="http://www.joeswebtools.com/wordpress-plugins/export-emails/">plugin homepage</a>, so other folks can find out about it.<br />';
	echo								'&nbsp;&bull;&nbsp;&nbsp;Give this plugin a good rating on <a href="http://wordpress.org/extend/plugins/export-emails/">WordPress.org</a>.<br />';
	echo								'&nbsp;&bull;&nbsp;&nbsp;Support further development with a <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5162912">donation</a>.<br />';
	echo							'</td>';
	echo						'</tr>';
	echo						'<tr>';
	echo							'<th scope="row" valign="top">';
	echo								'<b>Need support?</b>';
	echo							'</th>';
	echo							'<td>';
	echo									'If you have any problems or good ideas, please talk about them on the <a href="http://www.joeswebtools.com/wordpress-plugins/export-emails/">plugin homepage</a>.<br />';
	echo							'</td>';
	echo						'</tr>';
	echo						'<tr>';
	echo							'<th scope="row" valign="top">';
	echo								'<b>Credits</b>';
	echo							'</th>';
	echo							'<td>';
	echo									'<a href="http://www.joeswebtools.com/wordpress-plugins/export-emails/">Export Emails</a> is developped by Philippe Paquet for <a href="http://www.joeswebtools.com/">Joe\'s Web Tools</a>. This plugin is released under the GNU GPL version 2. If you are unable to comply with the terms of the GNU General Public License, contact the copyright holder for a commercial license.<br />';
	echo							'</td>';
	echo						'</tr>';
	echo					'</table>';
	echo				'</form>';
	echo			'</div>';
	echo		'</div>';
	echo	'</div>';

	// Page wrapper end
	echo '</div>';
}





/*
 *
 *	add_export_email_menu
 *
 */

function add_export_email_menu() {

	// Add the menu page
	add_submenu_page('tools.php', 'Export Emails', 'Export Emails', 10, __FILE__, 'export_email_page');
}

add_action('admin_menu', 'add_export_email_menu');

?>