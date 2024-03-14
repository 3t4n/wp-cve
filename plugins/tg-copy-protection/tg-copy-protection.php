<?php
/**
 * Plugin Name: TG Copy Protection
 * Plugin URI : http://www.tekgazet.com/tg-copy-protection-plugin
 * Description: Copy protect contents by disabling mouse and keyboard commands, or by emptying copied text, or show copyright text with copied text, and more.
 * Version: 1.0
 * Author: Ashok Dhamija
 * Author URI: http://tilakmarg.com/dr-ashok-dhamija/
 * License: GPLv2 or later
 */
 
 /*
  Copyright 2015 Ashok Dhamija web: http://tilakmarg.com/dr-ashok-dhamija/

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
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */
 
// Add a menu for our option page
add_action('admin_menu', 'tg_copy_protection_add_page');
function tg_copy_protection_add_page() {
	add_options_page( 'TG Copy Protection Plugin', 'TG Copy Protection', 'manage_options', 'tg_copy_protection', 'tg_copy_protection_option_page' );
}

// Draw the option page
function tg_copy_protection_option_page() {
	
	//Check if it is first time after installation, if so, set default values
	$valid = array();
	$valid = get_option( 'tg_copy_protection_options' );
	if( !$valid ) {	
		$valid['enable'] = 1;
		$site = get_site_url();
		$valid['copyright'] = "...Copyright (C) ". $site . " . Read more at ";
		update_option( 'tg_copy_protection_options', $valid );
	}
	
	?>
	<div class="wrap">
		<?php screen_icon( 'plugins'); ?>
		<h2>TG Copy Protection Page</h2>
		<form action="options.php" method="post">
			<?php settings_fields('tg_copy_protection_options'); ?>
			<?php do_settings_sections('tg_copy_protection'); ?>
			<input name="Submit" type="submit" value="Save Changes" />
			<input name="Submit2" type="submit" value="Reset to Default Values" />		
		</form>
	</div>
	<?php
}

// Register and define the settings
add_action('admin_init', 'tg_copy_protection_admin_init');
function tg_copy_protection_admin_init(){
	register_setting(
		'tg_copy_protection_options',
		'tg_copy_protection_options',
		'tg_copy_protection_validate_options'
	);
	
	add_settings_section(
		'tg_copy_protection_about',
		'About TG Copy Protection Plugin',
		'tg_copy_protection_section_about_text',
		'tg_copy_protection'
	);
			
	add_settings_section(
		'tg_copy_protection_main',
		'TG Copy Protection Plugin Settings',
		'tg_copy_protection_section_text',
		'tg_copy_protection'
	);

	
	add_settings_field(
		'tg_copy_protection_enable',
		'Select whether to enable copy protection of contents, and by which method:',
		'tg_copy_protection_setting_input_enable',
		'tg_copy_protection',
		'tg_copy_protection_main'
	);

		
	add_settings_field(
		'tg_copy_protection_copyright',
		'Enter copyright text that you want to append to copied text:',
		'tg_copy_protection_setting_input_copyright',
		'tg_copy_protection',
		'tg_copy_protection_main'
	);
	
}

// Draw the section header
function tg_copy_protection_section_about_text() {
	echo '<p>TG Copy Protection is a plugin developed by <a href="http://tilakmarg.com/dr-ashok-dhamija/" target="_blank">Ashok Dhamija</a>. For any help or support issues, please leave your comments at <a href="http://www.tekgazet.com/tg-copy-protection-plugin" target="_blank">TG Copy Protection Plugin Page</a>, where you can also read more about the detailed functioning of this plugin. If you like this plugin, please vote favorably for it at its <a href="https://wordpress.org/plugins/tg-copy-protection/" target="_blank">WordPress plugin page</a>.</p><hr />';
}


// Draw the section header
function tg_copy_protection_section_text() {
	echo '<p>Enter your settings here for copy protection of contents of your website. You can change these settings any time later.</p>';
	//Display the Save Changes and Reset buttons at the top
	echo '<input name="Submit" type="submit" value="Save Changes" />';
	echo '<input name="Submit2" type="submit" value="Reset to Default Values" />';	
}

// Display and fill the form field
function tg_copy_protection_setting_input_enable() {
	// get option 'enable' value from the database
	$options = get_option( 'tg_copy_protection_options' );
	$enable = $options['enable'];
	
	//display the radio button field
	$msg1 = '<input type="radio" id="radio_one" name="tg_copy_protection_options[enable]" value="1" '. checked(1, $enable, false ) .'/>';
	$msg1 .= '<label for="radio_one">Enable copy protection by disabling mouse and key commands for copying, printing, saving, source-code, etc.</label> <br><br>';
	$msg1 .= '<input type="radio" id="radio_two" name="tg_copy_protection_options[enable]" value="2" '. checked(2, $enable, false ) .'/>';
	$msg1 .= '<label for="radio_two">Enable copy protection by setting copied text to nothing, i.e., by emptying it</label> <br><br>';
	$msg1 .= '<input type="radio" id="radio_three" name="tg_copy_protection_options[enable]" value="3" '. checked(3, $enable, false ) .'/>';
	$msg1 .= '<label for="radio_three">Disable copy protection, but append copyright text with page link to copied text</label> <br><br>';
	$msg1 .= '<input type="radio" id="radio_four" name="tg_copy_protection_options[enable]" value="4" '. checked(4, $enable, false ) .'/>';
	$msg1 .= '<label for="radio_four">Disable copy protection completely, i.e., allow full copying</label>';
	echo $msg1;
	   
}


// Display and fill the form field
function tg_copy_protection_setting_input_copyright() {
	// get option 'copyright' value from the database
	$options = get_option( 'tg_copy_protection_options' );
	$copyright = $options['copyright'];
	// echo the field
	echo "<input id='copyright' size='100' name='tg_copy_protection_options[copyright]' type='text' value='$copyright' />";
	echo '<p>This copyright text is relevant only for third option above, i.e., "Disable copy protection, but append copyright text with page link to copied text". Your home-page link is already shown here. Please note that link of the page from where text is copied will be automatically set dynamically by this plugin and will be automatically appended and you MUST NOT enter that link here.<p>';
}

// Validate user input 
function tg_copy_protection_validate_options( $input ) {		
	$valid = array();	

	//Reset to default values, if needed
	if ( isset( $_POST['Submit2'] ) ) 
	{ 
		$valid['enable'] = 1;
		$site = get_site_url();
		$valid['copyright'] = "...Copyright (C) ". $site . " . Read more at ";
		//Show message for defaults restored
		add_settings_error(
			'tg_copy_protection_option_page',
			'tg_copy_protection_texterror',
			'Default values have been restored.',
			'updated'
			);	
			
		return $valid;
	}

	
	$valid['enable'] = $input['enable'] ;
	$valid['copyright'] = $input['copyright'] ;
	return $valid;		
}


add_action('wp_enqueue_scripts', tg_copy_protection_enq_script);
add_action('wp_head', tg_copy_protection_new_code);
function tg_copy_protection_enq_script() 
{        
	wp_enqueue_script('jquery');     
}

 function tg_copy_protection_new_code() 
 {
    $options = get_option( 'tg_copy_protection_options' );
	$enable = $options['enable'];
	if ($enable == 1) {
		$output  = 	'<script type="text/javascript">
					jQuery(document).bind("keydown", function(e) {
					if(e.ctrlKey && (e.which == 44 || e.which == 65 || e.which == 67 || e.which == 73 || e.which == 75 || e.which == 80 || e.which == 88 || e.which == 83 ||e.which == 85)) {
					e.preventDefault(); return false; 	} });
					jQuery(document).on( "mousedown", function(event) { if(event.which=="3") 
					{ document.oncontextmenu = document.body.oncontextmenu = function() {return false;} } });
					</script>
					<style type="text/css">
					body { 	-webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none;
							-moz-user-select: none; -ms-user-select: none; user-select: none; 	} </style>';
			echo $output;
		}
	elseif ($enable == 2) {
		?>		
		<script type="text/javascript">
		function addCopy() {
			var body_element = document.getElementsByTagName('body')[0];
			var selection = window.getSelection();
			var copytext = '';
			var newdiv = document.createElement('div');
			newdiv.style.position = 'absolute';
			newdiv.style.left = '-99999px';
			body_element.appendChild(newdiv);
			newdiv.innerHTML = copytext;
			selection.selectAllChildren(newdiv);
			window.setTimeout(function() {
			body_element.removeChild(newdiv);
			},0);
		}
		document.oncopy = addCopy;
		</script>		
		<?php	
	}
	elseif ($enable == 3) {
			$copyright = $options['copyright'];
			?>
			<script type="text/javascript">
			function addCopy() {
			var body_element = document.getElementsByTagName('body')[0];
			var selection = window.getSelection();
			var pagelink = "<br></br> <?php echo $copyright; ?> <a href='" + document.location.href + "'>" + document.location.href + "</a>"  + " .";
			var copytext = selection + pagelink;
			var newdiv = document.createElement('div');
			newdiv.style.position = 'absolute';
			newdiv.style.left = '-99999px';
			body_element.appendChild(newdiv);
			newdiv.innerHTML = copytext;
			selection.selectAllChildren(newdiv);
			window.setTimeout(function() {
			body_element.removeChild(newdiv);
			},0);
			}
			document.oncopy = addCopy;
			</script>
			<?php
	}
}	
	 
?>