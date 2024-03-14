<?php
/* 
Plugin Name: EU Cookie Law Compliance Message
Plugin URI: http://timtrott.co.uk/europe-cookie-law-plugin/
Description: This is a small plugin which adds a banner to the page on the first page view for each visitor. This plugin is used for implied consent, which means that if the guest continues using the site they agree to cookie use. See plugin homepage for live demo!
Author: Tim Trott
Version: 2.05
Author URI: http://timtrott.co.uk/
*/

// Tell WordPress we need jQuery loaded
function EUCLC_enqueueScripts()  
{  
    $setting_value = get_option('EUCLC');
	if (isset($setting_value['chkJquery']) && ($setting_value['chkJquery'] == 1))
	{
		wp_enqueue_script('jquery');  
	}
} 
add_action('wp_enqueue_scripts', 'EUCLC_enqueueScripts'); 


// Output the message to the page footer
function EUCLC_cookieMessage()
{
	// Get the settings
	$setting_value = get_option('EUCLC');
	
	// Split and parse the settings array
	$title = str_replace("'", "\'", __($setting_value['notificationTitle'], 'EUCLC'));
	$message = str_replace("'", "\'", __($setting_value['notificationMessage'], 'EUCLC'));
	$message = str_replace("\n", "<br/>", $message);
	$message = str_replace("\r", "", $message);
	$close = str_replace("'", "\'", __($setting_value['notificationClose'], 'EUCLC'));
	$padding = $setting_value['notificationPadding'];
	$style = $setting_value['notificationStyle'];
	$maxWidth = $setting_value['notificationMaxWidth'];
	
	if (isset($setting_value['chkBlock']))
	{
		$absPosition = $setting_value['chkBlock'] == 'on' ? '' : 'position:absolute;';
	}
	else
	{
		$absPosition = 'position:absolute;';
	}
	
	if ((isset($setting_value['chkBlock'])) && ($setting_value['chkDebug'] == 'on'))
	{
		$debug = true;
	}
	else
	{
		$debug = false;
	}

	
	if ($style == 'dark')
	{
		$backgroundColour = '0,0,0';
		$backgroundTransparency = '0.8';
		$titleColour = '#ffffff';
		$titleSize = '1.6em';
		$titleFont = 'ariel,sans-serif';
		$messageColour = '#BEBEBE';
		$messageSize = '1em';
		$messageFont = 'ariel,sans-serif';
		$closeColour = '#ffffff';
		$closeSize = '1.25em';
		$closeFont = 'ariel,sans-serif';
	}
	else if ($style == 'light')
	{
		$backgroundColour = '255,255,255';
		$backgroundTransparency = '0.8';
		$titleColour = '#000000';
		$titleSize = '1.6em';
		$titleFont = 'ariel,sans-serif';
		$messageColour = '#444444';
		$messageSize = '1em';
		$messageFont = 'ariel,sans-serif';
		$closeColour = '#000000';
		$closeSize = '1.25em';
		$closeFont = 'ariel,sans-serif';
	}
	else
	{
		$backgroundColour = EUCLC_hex2rgb($setting_value['backgroundColour']);
		$backgroundTransparency = $setting_value['backgroundTransparency'];
		$titleColour = $setting_value['titleColour'];
		$titleSize = $setting_value['titleSize'];
		$titleFont = $setting_value['titleFont'];
		$messageColour = $setting_value['messageColour'];
		$messageSize = $setting_value['messageSize'];
		$messageFont = $setting_value['messageFont'];
		$closeColour = $setting_value['closeColour'];
		$closeSize = $setting_value['closeSize'];
		$closeFont = $setting_value['closeFont'];
	}
	
	// 	The actual code sent to the browser
?>
<script type="text/javascript">
jQuery(function(){ 
  if (navigator.cookieEnabled === true)
  {
    if (document.cookie.indexOf("visited") == -1)
	{
      jQuery('body').prepend('<div id="cookie"><div id="wrapper"><h2><?php echo $title; ?></h2><p><?php echo $message; ?></p><div id="close"><a href="#" id="closecookie"><?php echo $close; ?></a></div><div style="clear:both"></div></div></div>');
	  jQuery('head').append('<style type="text/css">#cookie {<?php echo $absPosition; ?>left:0;top:0;width:100%;height:100%;background:rgb(<?php echo $backgroundColour; ?>);background:rgba(<?php echo $backgroundColour; ?>,<?php echo $backgroundTransparency; ?>);z-index:99999;}#cookie #wrapper {padding:<?php echo $padding; ?>;}#cookie h2 {color:<?php echo $titleColour; ?>;display:block;text-align:center;font-family:<?php echo $titleFont; ?>;font-size:<?php echo $titleSize; ?>}#cookie p {color:<?php echo $messageColour; ?>;display:block;font-family:<?php echo $messageFont; ?>;font-size:<?php echo $messageSize; ?>}#cookie #close{text-align:center;}#closecookie{color:<?php echo $closeColour; ?>;font-family:<?php echo $closeFont; ?>;font-size:<?php echo $closeSize; ?>;text-decoration:none}@media only screen and (min-width: 480px) {#cookie {height:auto;}#cookie #wrapper{max-width:<?php echo $maxWidth; ?>;margin-left:auto;margin-right:auto;}#cookie h2{width:18%;margin-top:0;margin-right:2%;float:left;text-align:right;}#cookie p {width:68%;margin:0 1%;float:left;}#cookie #close{width:9%;float:right;}}</style>');
	  jQuery('#cookie').show("fast");
	  jQuery('#closecookie').click(function() {jQuery('#cookie').hide("fast");});
<?php if ($debug === false) { ?>
	  document.cookie="visited=yes; expires=Thu, 31 Dec 2020 23:59:59 UTC; path=/";
<?php } ?>
	}
  }
})
</script>
<?php
}
add_action('wp_footer', 'EUCLC_cookieMessage'); 



// Create the admin menu
function EUCLC_createMenu() 
{
	add_submenu_page('options-general.php', __('EU Cookie Message', 'EUCLC'), __('EU Cookie Message', 'EUCLC'), 'administrator', 'EUCLC_settings', 'EUCLC_settingsPage'); 
	add_action('admin_init', 'EUCLC_registerSettings');
}
add_action('admin_menu', 'EUCLC_createMenu');

// Output the admin option page
function EUCLC_settingsPage() 
{
?>
<div class="wrap">
<h2><?php _e('EU Cookie Law Complience Message', 'EUCLC'); ?></h2>
<form method="post" action="options.php">
    <?php settings_fields('EUCLC'); ?>
    <?php do_settings_sections('EUCLC_settings'); ?>
</form>
</div>
<?php 
}

// Tell WordPress what settings we are going to be using
function EUCLC_registerSettings() 
{
	register_setting('EUCLC', 'EUCLC');
	
	add_settings_section('EUCLC_main', 'Main Settings', 'EUCLC_main_text', 'EUCLC_settings');
	add_settings_field('notificationTitle', 'Notification Title', 'EUCLC_notificationTitle', 'EUCLC_settings', 'EUCLC_main');
	add_settings_field('notificationMessage', 'Message', 'EUCLC_notificationMessage', 'EUCLC_settings', 'EUCLC_main');
	add_settings_field('notificationClose', 'Close Link Text', 'EUCLC_notificationClose', 'EUCLC_settings', 'EUCLC_main');
	add_settings_field('notificationPadding', 'Content Padding', 'EUCLC_notificationPadding', 'EUCLC_settings', 'EUCLC_main');
	add_settings_field('notificationMaxWidth', 'Message Maximum Width', 'EUCLC_notificationMaxWidth', 'EUCLC_settings', 'EUCLC_main');
	add_settings_field('chkBlock', 'Message hovers over content', 'EUCLC_chkBlock', 'EUCLC_settings', 'EUCLC_main');
	add_settings_field('notificationStyle', 'Visual Style', 'EUCLC_notificationStyle', 'EUCLC_settings', 'EUCLC_main');
	add_settings_field('chkJquery', 'Enqueue jQuery', 'EUCLC_chkJquery', 'EUCLC_settings', 'EUCLC_main');
	add_settings_field('chkDebug', 'Debug Mode', 'EUCLC_chkDebug', 'EUCLC_settings', 'EUCLC_main');
	add_settings_field('chkReset', 'Reset Options', 'EUCLC_chkReset', 'EUCLC_settings', 'EUCLC_main');
	add_settings_field('submit', '', 'EUCLC_Submit', 'EUCLC_settings', 'EUCLC_main');
	
	add_settings_section('EUCLC_custom', 'Custom Styles (advanced)', 'EUCLC_custom_text', 'EUCLC_settings');
	add_settings_field('backgroundColour', 'Message Background Colour', 'EUCLC_backgroundColour', 'EUCLC_settings', 'EUCLC_custom');
	add_settings_field('backgroundTransparency', 'Message Transparency', 'EUCLC_backgroundTransparency', 'EUCLC_settings', 'EUCLC_custom');
	add_settings_field('titleColour', 'Title Heading Colour', 'EUCLC_titleColour', 'EUCLC_settings', 'EUCLC_custom');
	add_settings_field('titleSize', 'Title Heading Font Size', 'EUCLC_titleSize', 'EUCLC_settings', 'EUCLC_custom');
	add_settings_field('titleFont', 'Title Heading Font Family', 'EUCLC_titleFont', 'EUCLC_settings', 'EUCLC_custom');
	add_settings_field('messageColour', 'Message Colour', 'EUCLC_messageColour', 'EUCLC_settings', 'EUCLC_custom');
	add_settings_field('messageSize', 'Message Font Size', 'EUCLC_messageSize', 'EUCLC_settings', 'EUCLC_custom');
	add_settings_field('messageFont', 'Message Font Family', 'EUCLC_messageFont', 'EUCLC_settings', 'EUCLC_custom');
	add_settings_field('closeColour', 'Close Link Colour', 'EUCLC_closeColour', 'EUCLC_settings', 'EUCLC_custom');
	add_settings_field('closeSize', 'Close Link Font Size', 'EUCLC_closeSize', 'EUCLC_settings', 'EUCLC_custom');
	add_settings_field('closeFont', 'Close Link Font Family', 'EUCLC_closeFont', 'EUCLC_settings', 'EUCLC_custom');
	add_settings_field('submit', '', 'EUCLC_Submit', 'EUCLC_settings', 'EUCLC_custom');
}

// Callback function to output forms
function EUCLC_main_text() {
?><p><?php _e('Change the settings below to alter how the Cookie message will be shown. You can select the light theme, the dark theme or create your own by selecting custom.');?></p><?php
}

function EUCLC_custom_text() {
?><p><?php _e('If you set the Visual Style to custom, you can use these options to customise the Cookie Message styles.');?></p><?php
}

function EUCLC_Submit() {
	submit_button();
}

function EUCLC_notificationTitle() {
$setting_value = get_option('EUCLC');
$setting_value = esc_attr($setting_value['notificationTitle']);
?><input type="text" name="EUCLC[notificationTitle]" value="<?php echo $setting_value; ?>" class="regular-text"/><?php
}
function EUCLC_notificationMessage() {
$setting_value = get_option('EUCLC');
$setting_value = esc_attr($setting_value['notificationMessage']);
?><textarea name="EUCLC[notificationMessage]" class="large-text"><?php echo $setting_value; ?></textarea><?php
}
function EUCLC_notificationClose() {
$setting_value = get_option('EUCLC');
$setting_value = esc_attr($setting_value['notificationClose']);
?><input type="text" name="EUCLC[notificationClose]" value="<?php echo $setting_value; ?>" class="regular-text"/><?php
}
function EUCLC_notificationPadding() {
$setting_value = get_option('EUCLC');
$setting_value = esc_attr($setting_value['notificationPadding']);
?><input type="text" name="EUCLC[notificationPadding]" value="<?php echo $setting_value; ?>" class="regular-text"/><?php
}
function EUCLC_notificationMaxWidth() {
$setting_value = get_option('EUCLC');
$setting_value = esc_attr($setting_value['notificationMaxWidth']);
?><input type="text" name="EUCLC[notificationMaxWidth]" value="<?php echo $setting_value; ?>" class="regular-text"/><?php
}
function EUCLC_notificationStyle() {
$setting_value = get_option('EUCLC');
$setting_value = $setting_value['notificationStyle'];
?><select name="EUCLC[notificationStyle]"><option value="dark" <?php if ($setting_value == 'dark') echo ' selected';?>>Dark - Black Background, White Text</option><option value="light"<?php if ($setting_value == 'light') echo ' selected';?>>Light - White Background, Dark Grey Text</option><option value="custom"<?php if ($setting_value == 'custom') echo ' selected';?>>Custom - Enter Your Own Values Below</option></select>
<?php
}
function EUCLC_chkReset() {
$setting_value = get_option('EUCLC');
if (isset($setting_value['chkReset'])) $setting_value = $setting_value['chkReset']; else $setting_value = '';
if($setting_value) { $checked = ' checked="checked" '; } else { $checked = ''; }
?><input type='checkbox' name='EUCLC[chkReset]' <?php echo $checked; ?> id="chkReset" /> <label for='chkReset'> Tick this and click save changes to reset back to default.</label><?php
}
function EUCLC_chkBlock() {
$setting_value = get_option('EUCLC');#
if (isset($setting_value['chkBlock'])) $setting_value = $setting_value['chkBlock']; else $setting_value = '';
if($setting_value) { $checked = ' checked="checked" '; } else { $checked = ''; }
?><input type='checkbox' name='EUCLC[chkBlock]' <?php echo $checked; ?> id="chkBlock" /> <label for='chkBlock'> If enabled, cookie message will hover over the page content. If disabled, message will push down the page content.</label><?php
}
function EUCLC_chkDebug() { 
$setting_value = get_option('EUCLC');
if (isset($setting_value['chkDebug'])) $setting_value = $setting_value['chkDebug']; else $setting_value = '';
if($setting_value) { $checked = ' checked="checked" '; } else { $checked = ''; }
?><input type='checkbox' name='EUCLC[chkDebug]' <?php echo $checked; ?> id="chkDebug" /> <label for='chkDebug'> If enabled the cookie will not be set, so you can reload the page many times and still view the message. Remember to disable thie when you put your site live!</label><?php
}
function EUCLC_chkJquery() { 
$setting_value = get_option('EUCLC');
if (isset($setting_value['chkJquery'])) $setting_value = $setting_value['chkJquery']; else $setting_value = '';
if($setting_value) { $checked = ' checked="checked" '; } else { $checked = ''; }
?><input type='checkbox' name='EUCLC[chkJquery]' <?php echo $checked; ?> id="chkJquery" /> <label for='chkJquery'> If enabled the plugin will enqueue jQuery. If disabled, the plugin will not attempt to load jQuery so you must ensure that your theme includes jQuery. If in doubt leave checked.</label><?php
}




function EUCLC_backgroundColour() {
$setting_value = get_option('EUCLC');
$setting_value = esc_attr($setting_value['backgroundColour']);
?>
<input type="text" name="EUCLC[backgroundColour]" value="<?php echo $setting_value; ?>" class="regular-text"/><br/>
<small>Hexadecimal colour code to use for the background of the message bar.</small><?php
}
function EUCLC_backgroundTransparency() {
$setting_value = get_option('EUCLC');
$setting_value = esc_attr($setting_value['backgroundTransparency']);
?><input type="text" name="EUCLC[backgroundTransparency]" value="<?php echo $setting_value; ?>" class="regular-text"/><br/>
<small>Enter the transparency for the message background. 0 is invisible and 1 is solid colour. Default is 0.8.</small><?php
}
function EUCLC_titleColour() {
$setting_value = get_option('EUCLC');
$setting_value = esc_attr($setting_value['titleColour']);
?><input type="text" name="EUCLC[titleColour]" value="<?php echo $setting_value; ?>" class="regular-text"/><br/>
<small>Hexadecimal colour code to use for the message title.</small><?php
}
function EUCLC_titleSize() {
$setting_value = get_option('EUCLC');
$setting_value = esc_attr($setting_value['titleSize']);
?><input type="text" name="EUCLC[titleSize]" value="<?php echo $setting_value; ?>" class="regular-text"/><br/>
<small>Font size for the message title. Can be in pixels (px), ems (em), points (pt) or percent (%).</small><?php
}
function EUCLC_titleFont() {
$setting_value = get_option('EUCLC');
$setting_value = esc_attr($setting_value['titleFont']);
?><input type="text" name="EUCLC[titleFont]" value="<?php echo $setting_value; ?>" class="regular-text"/><br/>
<small>The HTML font family to use for the message title. You can use any valid font-family declarations.</small><?php
}
function EUCLC_messageColour() {
$setting_value = get_option('EUCLC');
$setting_value = esc_attr($setting_value['messageColour']);
?><input type="text" name="EUCLC[messageColour]" value="<?php echo $setting_value; ?>" class="regular-text"/><br/>
<small>Hexadecimal colour code to use for the message body text.</small><?php
}
function EUCLC_messageSize() {
$setting_value = get_option('EUCLC');
$setting_value = esc_attr($setting_value['messageSize']);
?><input type="text" name="EUCLC[messageSize]" value="<?php echo $setting_value; ?>" class="regular-text"/><br/>
<small>Font size for the message body text.</small><?php
}
function EUCLC_messageFont() {
$setting_value = get_option('EUCLC');
$setting_value = esc_attr($setting_value['messageFont']);
?><input type="text" name="EUCLC[messageFont]" value="<?php echo $setting_value; ?>" class="regular-text"/><br/>
<small>The HTML font family to use for the message title.</small><?php
}
function EUCLC_closeColour() {
$setting_value = get_option('EUCLC');
$setting_value = esc_attr($setting_value['closeColour']);
?><input type="text" name="EUCLC[closeColour]" value="<?php echo $setting_value; ?>" class="regular-text"/><br/>
<small>Hexadecimal colour code to use for the close message link.</small><?php
}
function EUCLC_closeSize() {
$setting_value = get_option('EUCLC');
$setting_value = esc_attr($setting_value['closeSize']);
?><input type="text" name="EUCLC[closeSize]" value="<?php echo $setting_value; ?>" class="regular-text"/><br/>
<small>Font size for the close message link.</small><?php
}
function EUCLC_closeFont() {
$setting_value = get_option('EUCLC');
$setting_value = esc_attr($setting_value['closeFont']);
?><input type="text" name="EUCLC[closeFont]" value="<?php echo $setting_value; ?>" class="regular-text"/><br/>
<small>The HTML font family to use for the close message link.</small>
</div><?php
}





// Configure the defaults and option resets
function EUCLC_defaults() 
{
	$tmp = get_option('EUCLC');
    if ((!is_array($tmp)) || ((isset($tmp['chkReset'])) && ($tmp['chkReset'] =='on')))
	{
		$arr = array(
			'notificationTitle' => 'Cookies on this website',
			'notificationMessage' => 'We use cookies to ensure that we give you the best experience on our website. If you continue without changing your settings, we\'ll assume that you are happy to receive all cookies from this website. If you would like to change your preferences you may do so by following the instructions <a href="http://www.aboutcookies.org/Default.aspx?page=1" rel="nofollow">here</a>.',
			'notificationClose' => 'Close',
			'notificationPadding' => '20px',
			'notificationStyle' => 'dark',
			'notificationMaxWidth' => '980px',
			'chkBlock' => '',
			'chkReset' => '',
			'chkDebug' => '',
			'chkJquery' => '1',
			'backgroundColour' => '#000000',
			'backgroundTransparency' => '0.8',
			'titleColour' => '#ffffff',
			'titleSize' => '1.6em',
			'titleFont' => 'ariel,sans-serif',
			'messageColour' => '#BEBEBE',
			'messageSize' => '1em',
			'messageFont' => 'ariel,sans-serif',
			'closeColour' => '#ffffff',
			'closeSize' => '1.25em',
			'closeFont' => 'ariel,sans-serif',
		);
		update_option('EUCLC', $arr);
	}
}
add_action('admin_init','EUCLC_defaults');

// Utility function to convert hex colour code to RGB
// Credit: http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
function EUCLC_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   return implode(",", $rgb); // returns the rgb values separated by commas
   //return $rgb; // returns an array with the rgb values
}


?>