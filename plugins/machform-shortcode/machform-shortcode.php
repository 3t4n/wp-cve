<?php
/*
Plugin Name: Machform Shortcode
Plugin URI: http://www.laymance.com/products/wordpress-plugins/machform-shortcode/
Description: Creates a shortcode for inserting Machform forms into your posts or pages (only tested with MachForm 3.5+)
Version: 1.4.1
Author: Laymance Technologies LLC
Author URI: http://www.laymance.com
License: GPL2

Copyright 2017  Laymance Technologies LLC  (email : support@laymance.com)

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


// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
        echo 'WOW! This isn\'t supposed to happen! I can\'t be called directly dude!';
        exit;
}


// Set our option name where we will store the url config var and
// then retrieve it from the database.  Defaults to blank.
$mfsc_option_name = 'machform_shortcode_domain';
$machform_domain = get_option($mfsc_option_name, '');


// Do some cleanup related to our old default setting of "--", we
// have now moved to a empty string default.  The only people who 
// may have this double dash are people who installed this plugin
// a long time ago but never set it up.
if ( $machform_domain == '--' ){
	if ( get_option($mfsc_option_name) !== false ){
		delete_option($mfsc_option_name);
	}
	$machform_domain = '';
}


if ( $machform_domain == '' ){
	// The plugin has not been configured... so we'll show a notice at the
	// top of their screen telling them to configure it
	add_action( 'admin_notices', 'machform_sc_admin_notices' );
} else {
	// The plugin has been configured... setup our shortcode, but first we need
	// to make sure the domain is valid
	if ( strpos($machform_domain, 'http') === false and substr($machform_domain, 0, 2) !== '//' ){
		$machform_domain = 'http://' . $machform_domain;
	}
	
	if ( substr($machform_domain, -1) != '/' ) $machform_domain .= '/';

	// Create our short code
	add_shortcode( 'machform', 'machform_shortcode' );
}



// Create the menu entry
add_action('admin_menu', 'machform_sc_plugin_menu');

function machform_shortcode( $atts_raw ){
	global $machform_domain;
	
	// If the shortcode is being executed in admin, just return an
	// empty string.  There are some conflicts when the javascript
	// is loaded in admin and a page builder is being used, particularly
	// when the Yoast plugin is installed.  This ensures the best compatibility 
	// with Yoast so that we don't have shortcodes in the SEO text and things
	// will still work.
	if ( is_admin() ) return '';
	
	// The shortcode attribute keys should be lowercase
	foreach($atts_raw as $attkey=>$attval) $atts[ strtolower($attkey) ] = $attval;

	// If no ID is given, return a blank string
	$atts['id'] = intval($atts['id']);
	if ( $atts['id'] < 1 ) return '';
	
	// If no "type" is given, default to javascript embed
	if ( ! isset($atts['type']) or $atts['type'] == '' ) $atts['type'] = 'js';
	
	// Support URL Parameters
	$additional_parms = '';
	$skip_keys = array('id','type','height');
	
	foreach( $atts as $attkey=>$attval ){
    	$attkey = trim($attkey);
    	
    	// Skip known keys that are used for other functions
    	if ( in_array($attkey, $skip_keys) ) continue;
    	
    	// Real URL parameter keys from Machforms will not have a space in them,
    	// so skip over them... the keys should be in the form of element_1_1, 
    	// element_1_2, etc.
    	//
    	// ** This should never happen, just being extra cautious.
    	if ( strpos($attkey, ' ') !== false ) continue;
    	
    	$additional_parms .= '&' . strtolower($attkey) . '=' . urlencode($attval);
	}
	
	
	$atts['height'] = intval($atts['height']);
	if ( intval($atts['height']) < 1 ) $atts['height'] = 800;

	if ( strtolower($atts['type']) == 'js' ){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'machform-postmessage', $machform_domain . 'js/jquery.ba-postmessage.min.js' );
		wp_enqueue_script( 'machform-loader', $machform_domain . 'js/machform_loader.js', false, false, true );

        $content = '<div id="mf_placeholder" data-formurl="' . $machform_domain . 'embed.php?id=' . $atts['id'] . $additional_parms . '" data-formheight="' . $atts['height'] . '" data-paddingbottom="10"></div>';
        
	} elseif ( strtolower($atts['type']) == 'iframe' ){
    	$content = '<iframe onload="javascript:parent.scrollTo(0,0);" height="' . $atts['height'] . '" allowTransparency="true" frameborder="0" scrolling="no" style="width:100%;border:none" ';
    	$content .= 'src="' . $machform_domain . 'embed.php?id=' . $atts['id'] . $additional_parms . '"><a href="' . $machform_domain . 'view.php?id=' . $atts['id'] . $additional_parms . '">Click here to complete the form.</a></iframe>';
    	
	} else {
		// Don't know what they are requesting, return a blank string
		$content = '';
		
	}
	
	return $content;
}

function machform_sc_plugin_menu() {
	$hook_suffix = add_options_page('Machform Shortcode Options', 'Machform Shortcode', 'manage_options', 'machform_shortcodes', 'machform_sc_options');
}

function machform_sc_admin_notices() {
	echo "<div id='notice' class='updated fade'><p>The Machform Shortcodes plugin has not been configured yet. It must be configured before it can be used. <a href=\"/wp-admin/options-general.php?page=machform_shortcodes\">Click here to configure.</a></p></div>\n";
}

function machform_sc_options(){
	global $mfsc_option_name, $machform_domain;
	
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	// See if a form has been submitted, if so, process it
	if ( $_POST['mfsc_submit'] == 1 ){
		if ( $_POST['machform_url'] == '' ){
			// No address was given - if an address was previously given, delete
			// it from the db
			if ( get_option($mfsc_option_name) !== false ){
				delete_option($mfsc_option_name);
			}
		} else {
			// An address was given
			if ( strpos($_POST['machform_url'], 'http') === false and substr($_POST['machform_url'], 0, 2) !== '//' ){
				$_POST['machform_url'] = 'http://' . $_POST['machform_url'];
			}
			
			if ( substr($_POST['machform_url'], -1) != '/' ) $_POST['machform_url'] .= '/';
			
			if ( get_option($mfsc_option_name) !== false ){
				update_option($mfsc_option_name, $_POST['machform_url']);
			} else {
				add_option($mfsc_option_name, $_POST['machform_url'], null, 'yes');
			}
		}
		
		$machform_domain = $_POST['machform_url'];
		
		$alert = "<div id='notice' class='updated fade'><p>Your configuration options have been saved!</p></div><br />";
	} else {
		$alert = '';
	}
	
	
	ob_start();
	?>
	<div class="wrap" id="contain">
		<h2>Machform Shortcodes</h2>
		The Machform Shortcodes plugin creates the shortcodes necessary for you to insert javascript or iframe forms created by Machform into your posts or pages! Configuration is simple,<br />
		we simply need the URL for your Machforms installation. If you are not using the excellent forms application from App Nitro, you should check it out <a href="http://www.machform.com" target="_blank">here</a>!<br /><br />
		<strong>Please Note:</strong> this plugin works with the 3rd party Machform web app, <u>it is not included with this plugin</u>. This plugin only allows the easy use of Machforms within your WordPress site.<br><br>
		<div class="mfsc_config_container">
			<h3>Configuration</h3>
			<?php echo $alert; ?>
			<form method="post">
				<input type="hidden" name="mfsc_submit" value="1">
				<table border="0" cellpadding="5" cellspacing="0">
				<tr>
					<td valign="middle"><strong>Machform URL/Location</strong></td>
					<td valign="middle"><input type="text" name="machform_url" value="<?php echo $machform_domain; ?>" size="45"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td valign="top">Example: http://forms.mydomain.com/   OR   https://www.mydomain.com/machforms/</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" name="submit" class="mfsc_save_btn" value=" Save Configuration "></td>
				</tr>
				</table>
			</form>
		</div>
		<br />
		<hr>
		<br />
		<h2>How do you use the Machform Shortcode?</h2>
		Using the short codes is easy!  The shortcode supports both the javascript method as well as the iframe method of Machform.  Follow these simple steps in order to use Machforms on your site:<br />
		<br />
		
		<strong>Step 1:</strong> In Machforms, click on the "Code" option for the form you wish to embed in order to see the Machforms embed code.<br />
		<br />
		
		<strong>Step 2:</strong> In the embed code, make note of the "height" and the "id" of your form, you will use that in your shortcode!<br />
		<br />
		
		<strong>Step 3:</strong> In your content where you want the form to appear, insert a shortcode using the following format:&nbsp;&nbsp; [machform type=(<em>"js" or "iframe"</em>) id=(<em>ID #</em>) height=(<em>height #</em>)]<br />
		<br />
		* The type option must be "js" for javascript, or "iframe" for the iFrame method. If the type is not specified, it defaults to the javascript method.<br />
		* The id option is the ID number of the form from your embed code. <span style="color:maroon; font-weight:bold;">The ID is required.</span><br />
		* The height option is the height size of the form from your embed code. If a height is not specified, it defaults to 800.<br />
		<br />
		An example of a finished shortcode:  [machform type=js id=1593 height=703]<br />
		<br />
		<br />
		<strong>URL Parameters</strong><br />
		The plugin now supports URL Parameters.  You can read more about Machform's implementation of URL Parameters by visiting <a href="http://www.appnitro.com/doc-url-parameters" rel="nofollow">their website here</a>.<br />
		<br />
		To use URL parameters with your shortcodes, just add the additional parameters inside of the shortcode like the following example:<br />
		<br />
		<pre>[machform type=js id=1593 height=703 element_1_1="Field Text Here" element_1_2="Field Text Here"]</pre>
		<br />
		<br />
		
		<strong>Step 4:</strong> You are done, save your content and your form should appear!<br />
		<br />
		<br />
		<hr>
		<br />
		
		<h2>Please leave a review!</h2>
		If you like this plugin and it has been helpful, could you leave a review?  Just goto <a href="https://wordpress.org/support/view/plugin-reviews/machform-shortcode" target="_blank">https://wordpress.org/support/view/plugin-reviews/machform-shortcode</a> 
		and click on "Add your own review", it would be much appreciated!<br /><br />
		<br />
		This plugin was created by <a href="http://www.laymance.com" target="_blank">Laymance Technologies</a>, a web development and marketing agency with offices in Knoxville and Nashville Tennessee. We offer web and graphics design, and WordPress Support... 
		but we also specialize in custom development projects, custom WordPress templates and plugins, and so much more. Find us on the web at <a href="http://www.laymance.com" target="_blank">www.laymance.com</a>, by email at 
		<a href="sales@laymance.com">sales@laymance.com</a> or call us at (865) 583-6360.<br />
		<br />
		<strong>Need a WordPress support contract, or just need help for one-time issues?</strong>  Visit <a href="http://www.blackbeltwp.com">BlackBeltWP.com</a> to chat real-time with a WordPress expert. We take our WordPress black belt skills and we round-house 
		kick your issues!<br />
		<br />
		<br />
		<style>
		.mfsc_config_container {
			margin-top: 20px;
			margin-bottom: 20px;
			padding: 5px 20px 20px 20px;
			border: 1px solid #aaa;
			background-color: #dedede;
		}
		input.mfsc_save_btn {
			padding: 10px 25px;
			border: 1px solid #ccc;
			background-color: navy;
			color: #fff;
		}
		input.mfsc_save_btn:hover {
			background-color: maroon;
		}
		</style>
		    
	</div>
	<?php
	$content = ob_get_clean();
	
	echo $content;	
}

?>