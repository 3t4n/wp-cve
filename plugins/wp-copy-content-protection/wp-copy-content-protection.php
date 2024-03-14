<?php
/**
 * Plugin Name: WP Copy Content Protection
 * Plugin URI : https://bloggerpng.com
 * Description: WP Copy Content Protection wordpress plugin protects the content from being stolen by content thieves by disabling right-mouse click and disabling keyboard commands. If someone copies the content then you can gain backlink for your blog by showing him Copyright Information with copied text, and more.
 * Version: 1.4
 * Author: Blogger PNG
 * Author URI: https://bloggerpng.com
 * License: GPLv2 or later
 */

// Create the Options Page
function wccp_copy_protection_option_page() {
	//Check if it is first time after installation, if so, set default values
	$valid = array();
	$valid = get_option( 'wccp_copy_protection_options' );
	if( !$valid ) {	
		$valid['enable'] = 1;
		$site = get_site_url();
		$valid['copyright'] = " Copyright (C) ". $site . ". Read more at... ";
		update_option( 'wccp_copy_protection_options', $valid );
	}
	
	?>
	<div class="wrap">
		<?php screen_icon( 'plugins'); ?>
		<h2>WP Copy Content Protection Page</h2>
		<form action="options.php" method="post">
			<?php settings_fields('wccp_copy_protection_options'); ?>
			<?php do_settings_sections('wccp_copy_protection'); ?>
			<input name="Submit" type="submit" value="Save Changes" />
			<input name="Submit2" type="submit" value="Reset to Default Values" />		
		</form>
	</div>
	<?php
}
 
// Add a menu for Options Page
add_action('admin_menu', 'wccp_copy_protection_add_page');
function wccp_copy_protection_add_page() {
	add_options_page( 'WP Copy Content Protection Plugin', 'WP Copy Content Protection', 'manage_options', 'wccp_copy_protection', 'wccp_copy_protection_option_page' );
}

// Draw the Section Header
function wccp_copy_protection_section_about_text() {
	echo '<p>WP Copy Content Protection is a plugin developed by Atul Bansal of <a href="https://bloggerpng.com/" target="_blank">Blogger PNG</a>. If you like this plugin, please vote it on Wordpress. Thanks...</p><hr />';
}

// Draw the Section Header
function wccp_copy_protection_section_text() {
	echo '<p>Please select any one option or leave default. You can change these settings at any point of time later.</p>';
}

// Register and set the Settings
add_action('admin_init', 'wccp_copy_protection_admin_init');
function wccp_copy_protection_admin_init(){
	register_setting(
		'wccp_copy_protection_options',
		'wccp_copy_protection_options',
		'wccp_copy_protection_validate_options'
	);
	
	add_settings_section(
		'wccp_copy_protection_about',
		'About WP Copy Content Protection Plugin',
		'wccp_copy_protection_section_about_text',
		'wccp_copy_protection'
	);
			
	add_settings_section(
		'wccp_copy_protection_main',
		'WP Copy Content Protection Plugin Settings',
		'wccp_copy_protection_section_text',
		'wccp_copy_protection'
	);
	
	add_settings_field(
		'wccp_copy_protection_enable',
		'Select whether to enable copy protection of contents, and by which method:',
		'wccp_copy_protection_setting_input_enable',
		'wccp_copy_protection',
		'wccp_copy_protection_main'
	);
		
	add_settings_field(
		'wccp_copy_protection_copyright',
		'Enter copyright text that you want to append to copied text:',
		'wccp_copy_protection_setting_input_copyright',
		'wccp_copy_protection',
		'wccp_copy_protection_main'
	);
}

// Display radio buttons
function wccp_copy_protection_setting_input_enable() {
	// get option 'enable' value from the database
	$options = get_option( 'wccp_copy_protection_options' );
	$enable = $options['enable'];
	
	//display the radio button field
	$message = '<input type="radio" id="radio_one" name="wccp_copy_protection_options[enable]" value="1" '. checked(1, $enable, false ) .'/>';
	$message .= '<label for="radio_one">Disable Mouse and Keyboard Commands (Ctrl+A/Ctrl+C/Ctrl+X/Ctrl+S/Ctrl+P, etc.)</label> <br><br>';
	$message .= '<input type="radio" id="radio_two" name="wccp_copy_protection_options[enable]" value="2" '. checked(2, $enable, false ) .'/>';
	$message .= '<label for="radio_two">Allow Copy but Empty Copied Text</label> <br><br>';
	$message .= '<input type="radio" id="radio_three" name="wccp_copy_protection_options[enable]" value="3" '. checked(3, $enable, false ) .'/>';
	$message .= '<label for="radio_three">Allow Copy but append Copyright Warning with your Article Link</label> <br><br>';
	$message .= '<input type="radio" id="radio_four" name="wccp_copy_protection_options[enable]" value="4" '. checked(4, $enable, false ) .'/>';
	$message .= '<label for="radio_four">DISABLE Copy Protection Completely (Restores your site to default state)</label>';
	echo $message;
	   
}

// Display and enter copyright warning
function wccp_copy_protection_setting_input_copyright() {
	// get option 'copyright' value from the database
	$options = get_option( 'wccp_copy_protection_options' );
	$copyright = $options['copyright'];
	// echo the field
	echo "<input id='copyright' size='100' name='wccp_copy_protection_options[copyright]' type='text' value='$copyright' />";
	echo '<p>This copyright text is relevant only for third option above, i.e., <strong>"Allow Copy but append Copyright Warning with your Article Link"</strong>. Your home-page link is already shown here. Please note that link of the post from where content is copied will be automatically set dynamically by this plugin and you MUST NOT enter that link here.<p>';
}

// Validate the input done by user 
function wccp_copy_protection_validate_options( $input ) {		
	$valid = array();	
	//Reset to default values, if needed
	if ( isset( $_POST['Submit2'] ) ) 
	{ 
		$valid['enable'] = 1;
		$site = get_site_url();
		$valid['copyright'] = " Copyright (C) ". $site . ". Read more at... ";
		//Show message for defaults restored
		add_settings_error(
			'wccp_copy_protection_option_page',
			'wccp_copy_protection_texterror',
			'Default values have been restored.',
			'updated'
			);	
			
		return $valid;
	}
	
	$valid['enable'] = $input['enable'] ;
	$valid['copyright'] = $input['copyright'] ;
	return $valid;		
}

add_action('wp_enqueue_scripts', 'wccp_copy_protection_enq_script');
add_action('wp_head', 'wccp_copy_protection_code');

function wccp_copy_protection_enq_script() 
{        
	wp_enqueue_script('jquery');     
}

//This is the actual code that does all job of protecting the content from content-thieves. Do not change it else sky will fall on your head.
 function wccp_copy_protection_code() 
 {
    $options = get_option( 'wccp_copy_protection_options' );
	$enable = $options['enable'];
	if ($enable == 1) {
		$outputdata  = 	'<script type="text/javascript">
					jQuery(document).bind("keydown", function(e) {
					if(e.ctrlKey && (e.which == 44 || e.which == 65 || e.which == 67 || e.which == 73 || e.which == 75 || e.which == 80 || e.which == 88 || e.which == 83 ||e.which == 85)) {
					e.preventDefault(); return false; 	} });
					jQuery(document).on( "mousedown", function(event) { if(event.which=="3") 
					{ document.oncontextmenu = document.body.oncontextmenu = function() {return false;} } });
					</script>
					<style type="text/css">
					body { 	-webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none;
							-moz-user-select: none; -ms-user-select: none; user-select: none; 	} </style>';
			echo $outputdata;
		}
	elseif ($enable == 2) {
		?>		
		<script type="text/javascript">
		function addCopy() {
			var body_element = document.getElementsByTagName('body')[0];
			var selectedtext = window.getSelection();
			var copiedtext = '';
			var newdiv = document.createElement('div');
			newdiv.style.position = 'absolute';
			newdiv.style.left = '-99999px';
			body_element.appendChild(newdiv);
			newdiv.innerHTML = copiedtext;
			selectedtext.selectAllChildren(newdiv);
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
			var selectedtext = window.getSelection();
			var articlelink = "<br></br> <?php echo $copyright; ?> <a href='" + document.location.href + "'>" + document.location.href + "</a>"  + " .";
			var copiedtext = selectedtext + articlelink;
			var newdiv = document.createElement('div');
			newdiv.style.position = 'absolute';
			newdiv.style.left = '-99999px';
			body_element.appendChild(newdiv);
			newdiv.innerHTML = copiedtext;
			selectedtext.selectAllChildren(newdiv);
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