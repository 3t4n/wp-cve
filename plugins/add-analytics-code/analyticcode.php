<?php
/*
Plugin Name: Analytics code
Plugin URI: http://www.qualitypointtech.net
Description: A simple plugin for add analytics code to your pages
Version: 1.0
Author: Qualitypoint Technologies
Author URI: http://www.qualitypointtech.net

    Copyright 2011  Qualitypoint Technologies  (email : info@qualitypointtech.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



/********Function for add analytics code ***********/
function addanalytics(){
	add_option("ananlytics_data", 'Default', '', 'yes');
}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'addanalytics'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'addanalytics_remove()' );

/***************** function for remove analytics code **********/
function addanalytics_remove() {
/* Deletes the database field */
delete_option('ananlytics_data');
}

function showanalytic(){
	if(function_exists('addanalytics'))	{
		$chosen=get_option('ananlytics_data'); 
		echo "$chosen";
	}
}

/******************** Check admin *************/
add_action('wp_head', 'showanalytic');


if (is_admin() ){

/* Call the html code */
add_action('admin_menu', 'ananlyticscode_admin_menu');

	function ananlyticscode_admin_menu() {
	add_options_page('Analytics Code', 'Analytics code', 'administrator',
	'addanalytics', 'addanalytics_html_page');
	}
}

function addanalytics_html_page(){
?>
<div>
<h2>Google analytic code Options</h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>

<table width="510">
<tr valign="top">
<th width="92" scope="row">Enter Analytics code</th>
<td width="406">
<textarea name="ananlytics_data" id="ananlytics_data" rows="15"  cols="50" >
<?php echo get_option('ananlytics_data'); ?> </textarea>
</td>
</tr>
<tr><td><A href="http://qualitypointtech.net/help/index.php/?p=16">Help</A></td></tr>
</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="ananlytics_data" />

<p>
<input type="submit" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>
<?php
}// function end 


?>
