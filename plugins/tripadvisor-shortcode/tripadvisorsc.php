<?php
/*
Plugin Name: TripAdvisor shortcode
Plugin URI: http://ypraise.com/2013/wordpress/plugins/wordpress-2/tripadvidorsc-plugin/
Description: Trip Advisor shortcode allows for easy insertion of Tripadvisor review feed if your accommodation or travel website is using Wordpress.
Version: 2.2
Author: Kevin Heath
Author URI: http://ypraise.com/
License: GPLv2 or later
*/

/*
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

// set up menu and options page.

add_action( 'admin_menu', 'tripadvisorsc_menu' );


function tripadvisorsc_menu() {
	add_options_page( 'tripadvisorsc', 'TripAdvisor SC', 'manage_options', 'tripadvisorsc', 'tripadvisorsc_options' );
}

add_action ('admin_init', 'tripadvisorsc_register');

function tripadvisorsc_register(){
register_setting('tripadvisorsc_options', 'tripadvisor_url');
register_setting('tripadvisorsc_options', 'tripadvisor_name');
register_setting('tripadvisorsc_options', 'tripadvisor_id');
register_setting('tripadvisorsc_options', 'tripadvisor_buff');
}

function tripadvisorsc_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	?>
	<div class="wrap">

	<h2>TripAdvisor Shortcode</h2>
	<div id="donate_container">
 This plugin no longer works. TripAdvisor are about to turn off the business rss feeds that this plugin need.
    </div>
	
	<p><form method="post" action="options.php">	</p>
	<p>Add the url of the hotel or accommodation from Tripadvisor:</p>
	
	<?php
	
	settings_fields( 'tripadvisorsc_options' );
	
?>
<p>Add business url from TripAdvisor (exclude the http://tripadvisor.co.uk/): <input type="text" size="80" name="tripadvisor_url" value="<?php echo get_option('tripadvisor_url'); ?>" /></p>
<p>Add business name to display at top of feed (ie Reviews of BUSINESS NAME ): <input type="text" size="80" name="tripadvisor_name" value="<?php echo get_option('tripadvisor_name'); ?>" /></p>
<p>Add Tripadvisor ID (the dxxxxxx number in your url - do not include d: <input type="text" size="80" name="tripadvisor_id" value="<?php echo get_option('tripadvisor_id'); ?>" /></p>


					
					<p>Use buffering (if shortcode displays at top of content and not where you place it.:  <select name='tripadvisor_buff'>
							<option value='No' <?php selected('No',get_option('tripadvisor_buff')); ?>>No</option>
							<option value='Yes' <?php selected('Yes', get_option('tripadvisor_buff')); ?>>Yes</option>
						
						</select></p>
					
				
 <?php


	
 submit_button();
echo '</form>';

	
	echo '</div>';
}



// lets build the shortcode



function tripadvisorscode($atts) {

$name = get_option('tripadvisor_name');
$url = get_option('tripadvisor_url');
$id = get_option('tripadvisor_id');

extract( shortcode_atts( array( 
    'name' => $name,
    'url' => $url,  
	'id' => $id, 
), $atts ) ); 

$buffering = get_option('tripadvisor_buff');
if ($buffering == "Yes") {

	ob_start();
	
	}
	
?>
<script  src="http://www.tripadvisor.com/FeedsJS?f=hotels&defaultStyles=n&d=<?php echo isset($atts['id']) ? $atts['id'] : get_option('tripadvisor_id') ?>&plang=en"></script>
<script>window.realAlert = window.alert;
window.alert = function() {};</script>
<div id="TA_Header">brought to you by<br/>
<img src="http://c1.tacdn.com/img/logos_ta/logo_125px.gif" width="125" height="20"/>
</div>
<div id="TA_Link"><a href="http://www.tripadvisor.co.uk/<?php echo isset($atts['url']) ? $atts['url'] : get_option('tripadvisor_url') ?>">Reviews of   <?php echo isset($atts['name']) ? $atts['name'] : get_option('tripadvisor_name')  ?></a></div>
<div id="TA_Container"></div>
<div id="TA_Flags2">
Reviews in other languages:<br>
<table border="0" cellspacing="0" cellpadding="0"><tr>

<td><a href="http://www.tripadvisor.co.uk/<?php echo isset($atts['url']) ? $atts['url'] : get_option('tripadvisor_url') ?>"><img src="http://c1.tacdn.com/img/flags/uk.gif" alt="<?php echo isset($atts['name']) ? $atts['name'] : get_option('tripadvisor_name')   ?>" height="16" width="28"><br>More reviews</a></td>
<td><a href="http://www.tripadvisor.es/<?php echo isset($atts['url']) ? $atts['url'] : get_option('tripadvisor_url') ?>"><img src="http://c1.tacdn.com/img/flags/es.gif" alt="<?php echo isset($atts['name']) ? $atts['name'] : get_option('tripadvisor_name')   ?>" height="16" width="28"><br>Más opiniones</a></td>
<td><a href="http://www.tripadvisor.de/<?php echo isset($atts['url']) ? $atts['url'] : get_option('tripadvisor_url') ?>"><img src="http://c1.tacdn.com/img/flags/de.gif" alt="<?php echo isset($atts['name']) ? $atts['name'] : get_option('tripadvisor_name')   ?>" height="16" width="28"><br>Weitere Bewertungen</a></td>
<td><a href="http://www.tripadvisor.fr/<?php echo isset($atts['url']) ? $atts['url'] : get_option('tripadvisor_url') ?>"><img src="http://c1.tacdn.com/img/flags/fr.gif" alt="<?php echo isset($atts['name']) ? $atts['name'] : get_option('tripadvisor_name')   ?>" height="16" width="28"><br>Avis supplémentaires</a></td>
<td><a href="http://www.tripadvisor.it/<?php echo isset($atts['url']) ? $atts['url'] : get_option('tripadvisor_url') ?>"><img src="http://c1.tacdn.com/img/flags/it.gif" alt="<?php echo isset($atts['name']) ? $atts['name'] : get_option('tripadvisor_name')   ?>" height="16" width="28"><br>Altre recensioni</a></td>
</tr></table>
</div>


<?php

$buffering = get_option('tripadvisor_buff');
if ($buffering == "Yes") {
  return ob_get_clean();
  }
  
}

add_shortcode('tripadvisorsc', 'tripadvisorscode');  
?>