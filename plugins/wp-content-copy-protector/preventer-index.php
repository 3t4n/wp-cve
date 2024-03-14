<?php ob_start();
/*
Plugin Name: WP Content Copy Protection & No Right Click
Plugin URI: http://wordpress.org/plugins/w-p-content-copy-protector/
Description: This wp plugin protect the posts content from being copied by any other web site author , you dont want your content to spread without your permission!!
Version: 3.5.8
Author: wp-buy
Text Domain: wp-content-copy-protector
Domain Path: /languages
Author URI: http://www.wp-buy.com/
*/
?>
<?php
//delete_option('wccp_settings'); //Just for testing purposes
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//define all variables the needed alot
include 'the_globals.php';
include_once('notifications.php');
$wccp_settings = wccp_read_options();
//---------------------------------------------------------------------------------------------
//Load plugin textdomain to load translations
//---------------------------------------------------------------------------------------------
function wccp_free_load_textdomain() {
  load_plugin_textdomain( 'wp-content-copy-protector', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_action( 'init', 'wccp_free_load_textdomain' );

//---------------------------------------------------------<!-- SimpleTabs -->
function wccp_enqueue_scripts() {
	global $pluginsurl;
	$admincore = '';
	if (isset($_GET['page'])) $admincore = sanitize_text_field($_GET['page']);
	if( ( current_user_can('editor') || current_user_can('administrator') ) && $admincore == 'wccpoptionspro') {
	wp_enqueue_script('jquery');
	wp_register_script('simpletabsjs', $pluginsurl.'/js/simpletabs_1.3.js');
	wp_enqueue_script('simpletabsjs');
	
	wp_register_style('simpletabscss', $pluginsurl.'/css/simpletabs.css');
	wp_enqueue_style('simpletabscss');
	
	wp_register_style('font-awesome.min.css', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_enqueue_style('font-awesome.min.css');
	}
}
// Hook into the 'wp_enqueue_scripts' action
add_action('admin_enqueue_scripts', 'wccp_enqueue_scripts');

function wccp_free_enqueue_front_end_scripts() {
	wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'wccp_free_enqueue_front_end_scripts');
//------------------------------------------------------------------------
function wpcp_disable_Right_Click()
{
?>
<script id="wpcp_disable_Right_Click" type="text/javascript">
document.ondragstart = function() { return false;}
	function nocontext(e) {
	   return false;
	}
	document.oncontextmenu = nocontext;
</script>
<?php
}
//////////////////////////////////////////////////////////////////////////////////////
function wpcp_disable_selection()
{
global $wccp_settings;
?>
<script id="wpcp_disable_selection" type="text/javascript">
var image_save_msg='You are not allowed to save images!';
	var no_menu_msg='Context Menu disabled!';
	var smessage = "<?php echo $wccp_settings['smessage'];?>";

function disableEnterKey(e)
{
	var elemtype = e.target.tagName;
	
	elemtype = elemtype.toUpperCase();
	
	if (elemtype == "TEXT" || elemtype == "TEXTAREA" || elemtype == "INPUT" || elemtype == "PASSWORD" || elemtype == "SELECT" || elemtype == "OPTION" || elemtype == "EMBED")
	{
		elemtype = 'TEXT';
	}
	
	if (e.ctrlKey){
     var key;
     if(window.event)
          key = window.event.keyCode;     //IE
     else
          key = e.which;     //firefox (97)
    //if (key != 17) alert(key);
     if (elemtype!= 'TEXT' && (key == 97 || key == 65 || key == 67 || key == 99 || key == 88 || key == 120 || key == 26 || key == 85  || key == 86 || key == 83 || key == 43 || key == 73))
     {
		if(wccp_free_iscontenteditable(e)) return true;
		show_wpcp_message('You are not allowed to copy content or view source');
		return false;
     }else
     	return true;
     }
}


/*For contenteditable tags*/
function wccp_free_iscontenteditable(e)
{
	var e = e || window.event; // also there is no e.target property in IE. instead IE uses window.event.srcElement
  	
	var target = e.target || e.srcElement;

	var elemtype = e.target.nodeName;
	
	elemtype = elemtype.toUpperCase();
	
	var iscontenteditable = "false";
		
	if(typeof target.getAttribute!="undefined" ) iscontenteditable = target.getAttribute("contenteditable"); // Return true or false as string
	
	var iscontenteditable2 = false;
	
	if(typeof target.isContentEditable!="undefined" ) iscontenteditable2 = target.isContentEditable; // Return true or false as boolean

	if(target.parentElement.isContentEditable) iscontenteditable2 = true;
	
	if (iscontenteditable == "true" || iscontenteditable2 == true)
	{
		if(typeof target.style!="undefined" ) target.style.cursor = "text";
		
		return true;
	}
}

////////////////////////////////////
function disable_copy(e)
{	
	var e = e || window.event; // also there is no e.target property in IE. instead IE uses window.event.srcElement
	
	var elemtype = e.target.tagName;
	
	elemtype = elemtype.toUpperCase();
	
	if (elemtype == "TEXT" || elemtype == "TEXTAREA" || elemtype == "INPUT" || elemtype == "PASSWORD" || elemtype == "SELECT" || elemtype == "OPTION" || elemtype == "EMBED")
	{
		elemtype = 'TEXT';
	}
	
	if(wccp_free_iscontenteditable(e)) return true;
	
	var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor);
	
	var checker_IMG = '<?php echo $wccp_settings['img'];?>';
	if (elemtype == "IMG" && checker_IMG == 'checked' && e.detail >= 2) {show_wpcp_message(alertMsg_IMG);return false;}
	if (elemtype != "TEXT")
	{
		if (smessage !== "" && e.detail == 2)
			show_wpcp_message(smessage);
		
		if (isSafari)
			return true;
		else
			return false;
	}	
}

//////////////////////////////////////////
function disable_copy_ie()
{
	var e = e || window.event;
	var elemtype = window.event.srcElement.nodeName;
	elemtype = elemtype.toUpperCase();
	if(wccp_free_iscontenteditable(e)) return true;
	if (elemtype == "IMG") {show_wpcp_message(alertMsg_IMG);return false;}
	if (elemtype != "TEXT" && elemtype != "TEXTAREA" && elemtype != "INPUT" && elemtype != "PASSWORD" && elemtype != "SELECT" && elemtype != "OPTION" && elemtype != "EMBED")
	{
		return false;
	}
}	
function reEnable()
{
	return true;
}
document.onkeydown = disableEnterKey;
document.onselectstart = disable_copy_ie;
if(navigator.userAgent.indexOf('MSIE')==-1)
{
	document.onmousedown = disable_copy;
	document.onclick = reEnable;
}
function disableSelection(target)
{
    //For IE This code will work
    if (typeof target.onselectstart!="undefined")
    target.onselectstart = disable_copy_ie;
    
    //For Firefox This code will work
    else if (typeof target.style.MozUserSelect!="undefined")
    {target.style.MozUserSelect="none";}
    
    //All other  (ie: Opera) This code will work
    else
    target.onmousedown=function(){return false}
    target.style.cursor = "default";
}
//Calling the JS function directly just after body load
window.onload = function(){disableSelection(document.body);};

//////////////////special for safari Start////////////////
var onlongtouch;
var timer;
var touchduration = 1000; //length of time we want the user to touch before we do something

var elemtype = "";
function touchstart(e) {
	var e = e || window.event;
  // also there is no e.target property in IE.
  // instead IE uses window.event.srcElement
  	var target = e.target || e.srcElement;
	
	elemtype = window.event.srcElement.nodeName;
	
	elemtype = elemtype.toUpperCase();
	
	if(!wccp_pro_is_passive()) e.preventDefault();
	if (!timer) {
		timer = setTimeout(onlongtouch, touchduration);
	}
}

function touchend() {
    //stops short touches from firing the event
    if (timer) {
        clearTimeout(timer);
        timer = null;
    }
	onlongtouch();
}

onlongtouch = function(e) { //this will clear the current selection if anything selected
	
	if (elemtype != "TEXT" && elemtype != "TEXTAREA" && elemtype != "INPUT" && elemtype != "PASSWORD" && elemtype != "SELECT" && elemtype != "EMBED" && elemtype != "OPTION")	
	{
		if (window.getSelection) {
			if (window.getSelection().empty) {  // Chrome
			window.getSelection().empty();
			} else if (window.getSelection().removeAllRanges) {  // Firefox
			window.getSelection().removeAllRanges();
			}
		} else if (document.selection) {  // IE?
			document.selection.empty();
		}
		return false;
	}
};

document.addEventListener("DOMContentLoaded", function(event) { 
    window.addEventListener("touchstart", touchstart, false);
    window.addEventListener("touchend", touchend, false);
});

function wccp_pro_is_passive() {

  var cold = false,
  hike = function() {};

  try {
	  const object1 = {};
  var aid = Object.defineProperty(object1, 'passive', {
  get() {cold = true}
  });
  window.addEventListener('test', hike, aid);
  window.removeEventListener('test', hike, aid);
  } catch (e) {}

  return cold;
}
/*special for safari End*/
</script>
<?php
}
//------------------------------------------------------------------------
function alert_message()
{
	global $wccp_settings;
?>
	<div id="wpcp-error-message" class="msgmsg-box-wpcp hideme"><span>error: </span><?php echo $wccp_settings['smessage'];?></div>
	<script>
	var timeout_result;
	function show_wpcp_message(smessage)
	{
		if (smessage !== "")
			{
			var smessage_text = '<span>Alert: </span>'+smessage;
			document.getElementById("wpcp-error-message").innerHTML = smessage_text;
			document.getElementById("wpcp-error-message").className = "msgmsg-box-wpcp warning-wpcp showme";
			clearTimeout(timeout_result);
			timeout_result = setTimeout(hide_message, 3000);
			}
	}
	function hide_message()
	{
		document.getElementById("wpcp-error-message").className = "msgmsg-box-wpcp warning-wpcp hideme";
	}
	</script>
	<?php 
	global $wccp_settings;
	if(array_key_exists('prnt_scr_msg', $wccp_settings))
	{
	if($wccp_settings['prnt_scr_msg'] != ''){ ?>
	<style>
	@media print {
	body * {display: none !important;}
		body:after {
		content: "<?php echo $wccp_settings['prnt_scr_msg']; ?>"; }
	}
	</style>
	<?php }} ?>
	<style type="text/css">
	#wpcp-error-message {
	    direction: ltr;
	    text-align: center;
	    transition: opacity 900ms ease 0s;
	    z-index: 99999999;
	}
	.hideme {
    	opacity:0;
    	visibility: hidden;
	}
	.showme {
    	opacity:1;
    	visibility: visible;
	}
	.msgmsg-box-wpcp {
		border:1px solid #f5aca6;
		border-radius: 10px;
		color: #555;
		font-family: Tahoma;
		font-size: 11px;
		margin: 10px;
		padding: 10px 36px;
		position: fixed;
		width: 255px;
		top: 50%;
  		left: 50%;
  		margin-top: -10px;
  		margin-left: -130px;
  		-webkit-box-shadow: 0px 0px 34px 2px rgba(242,191,191,1);
		-moz-box-shadow: 0px 0px 34px 2px rgba(242,191,191,1);
		box-shadow: 0px 0px 34px 2px rgba(242,191,191,1);
	}
	.msgmsg-box-wpcp span {
		font-weight:bold;
		text-transform:uppercase;
	}
	<?php global $pluginsurl; ?>
	.warning-wpcp {
		background:#ffecec url('<?php echo $pluginsurl ?>/images/warning.png') no-repeat 10px 50%;
	}
    </style>
<?php
}
//------------------------------------------------------------------------
function wccp_css_script()
{
?>
<style>
.unselectable
{
-moz-user-select:none;
-webkit-user-select:none;
cursor: default;
}
html
{
-webkit-touch-callout: none;
-webkit-user-select: none;
-khtml-user-select: none;
-moz-user-select: none;
-ms-user-select: none;
user-select: none;
-webkit-tap-highlight-color: rgba(0,0,0,0);
}
</style>
<script id="wpcp_css_disable_selection" type="text/javascript">
var e = document.getElementsByTagName('body')[0];
if(e)
{
	e.setAttribute('unselectable',"on");
}
</script>
<?php
}
//------------------------------------------------------------------------
/* sanitize */
function wccp_sanitize($unsafe_val,$type='text')
{
	switch ($type) {
		case 'text': return stripslashes(htmlentities(sanitize_text_field($unsafe_val),ENT_QUOTES));
			break;
		case 'int': return intval($unsafe_val);
			break;
		case 'email': return sanitize_email($unsafe_val);
			break;
		case 'filename': return sanitize_file_name($unsafe_val);
			break;
		case 'title': return sanitize_title($unsafe_val);
			break;
		case 'URL': return esc_url($unsafe_val);
			break;
		case 'textbox': return stripslashes(htmlentities(sanitize_text_field($unsafe_val),ENT_QUOTES));
			break;
		default:
			return sanitize_text_field($unsafe_val);
	}
}
//------------------------------------------------------------------------
function wccp_css_settings()
{
	global $wccp_settings;
	if(!current_user_can( 'manage_options' ) || (current_user_can( 'manage_options' ) && $wccp_settings['exclude_admin_from_protection'] == 'No')){
			if (((is_home() || is_front_page() || is_archive() || is_post_type_archive() ||  is_404() || is_attachment() || is_author() || is_category() || is_feed()) && $wccp_settings['home_css_protection'] == 'Enabled'))
			{
				wccp_css_script();
				return;
			}
			if (is_single() && $wccp_settings['posts_css_protection'] == 'Enabled')
			{
				wccp_css_script();
				return;
			}
			if (is_page() && !is_front_page() && $wccp_settings['pages_css_protection'] == 'Enabled')
			{
				wccp_css_script();
				return;
			}
	}
}
//------------------------------------------------------------------------
function wccp_main_settings()
{
	global $wccp_settings;
	if(!current_user_can( 'manage_options' ) || (current_user_can( 'manage_options' ) && $wccp_settings['exclude_admin_from_protection'] == 'No')){
			if (((is_home() || is_front_page() || is_archive() || is_post_type_archive() ||  is_404() || is_attachment() || is_author() || is_category() || is_feed() || is_search()) && $wccp_settings['home_page_protection'] == 'Enabled'))
			{
				wpcp_disable_selection();
				return;
			}
			if (is_single() && $wccp_settings['single_posts_protection'] == 'Enabled')
			{
				wpcp_disable_selection();
				return;
			}
			if (is_page() && !is_front_page() && $wccp_settings['page_protection'] == 'Enabled')
			{
				wpcp_disable_selection();
				return;
			}
	}
}
//------------------------------------------------------------------------
function right_click_premium_settings()
{
	global $wccp_settings;
	if(!current_user_can( 'manage_options' ) || (current_user_can( 'manage_options' ) && $wccp_settings['exclude_admin_from_protection'] == 'No')){
			if (((is_home() || is_front_page() || is_archive() || is_post_type_archive() ||  is_404() || is_attachment() || is_author() || is_category() || is_feed()) && $wccp_settings['right_click_protection_homepage'] == 'checked'))
			{
				wpcp_disable_Right_Click();
				return;
			}
		if (is_single() && $wccp_settings['right_click_protection_posts'] == 'checked')
			{
				wpcp_disable_Right_Click();
				return;
			}
		if (is_page() && !is_front_page() && $wccp_settings['right_click_protection_posts'] == 'checked')
			{
				wpcp_disable_Right_Click();
				return;
			}
	}
}
//------------------------------------------------------------------------
function wccp_find_image_urls( $content ) {
	
	global $wccp_settings;
	
	$remove_img_urls = "Yes";
	
	if($remove_img_urls == "Yes"){

	$regexp = '(href=\"http)(.*)(.jpg|.jpeg|.png)';

	if(preg_match_all("/$regexp/iU", $content, $matches, PREG_SET_ORDER)) {

		if( !empty($matches) ) {

			$srcUrl = get_permalink();

			for ($i=0; $i <= count($matches); $i++)
			{
				if (isset($matches[$i]) && isset($matches[$i][0]))

					$tag = $matches[$i][0];

				else

					$tag = '';

				$tag2 = '';

				$content = str_replace($tag,$tag2,$content);
			}
		}
	}
	}
	return '<div class="protcted_area">'.$content.'</div>';
}
//------------------------------------------------------------------------
// Add specific CSS class by filter
function wccp_class_names($classes) {
global  $wccp_settings;
if(!current_user_can( 'manage_options' ) || (current_user_can( 'manage_options' ) && $wccp_settings['exclude_admin_from_protection'] == 'No'))
	{
			if ($wccp_settings['home_css_protection'] == 'Enabled' || $wccp_settings['posts_css_protection'] == 'Enabled' ||  $wccp_settings['pages_css_protection'] == 'Enabled')
			{
				$classes[] = 'unselectable';
				return $classes;
			}
			else
			{
				$classes[] = 'none';
				return $classes;
			}
	}else
	{
		$classes[] = 'none';
		return $classes;
	}
}

//Don't serve actions for live editors & builders
global $pagenow;
if ($pagenow != 'post.php' && !isset($_GET["elementor-preview"]) && !isset($_GET["siteorigin_panels_live_editor"]) && !isset($_GET["preview_id"]) && !isset($_GET["fl_builder"]) && !isset($_GET["et_fb"])) {
	add_action('wp_head','wccp_main_settings');
	add_action('wp_head','right_click_premium_settings');
	add_action('wp_head','wccp_css_settings');
	add_action('wp_footer','alert_message');
	add_filter('body_class','wccp_class_names');
	//add_filter( 'the_content', 'wccp_find_image_urls');
}
//-------------------------------------------------------Function to read options from the database
function wccp_read_options()
{
	if (get_option('wccp_settings'))
		$wccp_settings = get_option('wccp_settings');
	else
		$wccp_settings = wccp_default_options();

	$wccp_settings = array_merge(wccp_default_options(), $wccp_settings);//Set default value for any unexisted key
	if ((isset($_GET['page']) && $_GET['page'] != 'wccpoptionspro') || !isset($_GET['page']))
	{
		//We don't want this merge to work inside plugin admin panel
		
	}
	return $wccp_settings;
}
//---------------------------------------------------------------------
//To use debug console in PHP because its just allowed using JavaScript 
//---------------------------------------------------------------------
function wccp_free_debug_to_console($data)
{
	global $wccp_settings;
	 
	if(array_key_exists("developer_mode", $wccp_settings))
	{	
		if($wccp_settings['developer_mode'] == "Yes")
		{
			$output = $data;
			if ( is_array( $output ))
			{
				foreach ( $output as $element )
					if(isset($element))
					{
						//echo "<script>console.log('Debug Objects: " . $element . "' );</script>";
					}
			}
		}
	}
}
//-------------------------------------------------------Set default values to the array
function wccp_default_options(){
	$pluginsurl = plugins_url( '', __FILE__ );
	$wccp_settings =
	Array (
			'single_posts_protection' => 'Enabled', // prevent content copy, take 3 parameters, 1.content: to prevent content copy only	2.all 	3.none
			'home_page_protection' => 'Enabled', //
			'page_protection' => 'Enabled', //
			'top_bar_icon_btn' => 'Visible', //
			'right_click_protection_posts' => 'checked', //
			'right_click_protection_homepage' => 'checked', //
			'right_click_protection_pages' => 'checked', //
			'home_css_protection' => 'Enabled', // premium option
			'posts_css_protection' => 'Enabled', // premium option
			'pages_css_protection' => 'Enabled', // premium option
			'exclude_admin_from_protection' => 'No',
			'img' => '',
			'a' => '',
			'pb' => '',
			'input' => '',
			'h' => '',
			'textarea' => '',
			'emptyspaces' => '',
			'smessage' => 'Content is protected !!',
			'alert_msg_img' => '',
			'alert_msg_a' => '',
			'alert_msg_pb' => '',
			'alert_msg_input' => '',
			'alert_msg_h' => '',
			'alert_msg_textarea' => '',
			'alert_msg_emptyspaces' => '',
			'prnt_scr_msg' => 'You are not allowed to print preview this page, Thank you'
		);
	return $wccp_settings;
}
//---------------------------------------- Add plugin settings link to Plugins page
function wccp_plugin_add_settings_link( $links )
{
	$settings_link = '<a href="admin.php?page=wccpoptionspro">' . __( 'Settings', 'wp-content-copy-protector') . '</a>';
	array_push( $links, $settings_link );
	
	$go_pro_link = '<a title="Upgrade to PRO verion Now" target="_blank" style="font-weight:bold;color: chocolate;" href="https://www.wp-buy.com/product/wp-content-copy-protection-pro/#wccp_go_pro">' . __( 'Go PRO', 'wp-content-copy-protector') . '</a>';
	array_push( $links, $go_pro_link );
	
	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'wccp_plugin_add_settings_link' );
//------------------------------------------------------------------------
//Make a WordPress function to add to the correct menu.
function wpccp_after_plugin_row( $plugin_file, $plugin_data, $status ) {
	$plugin_name = substr(__FILE__, strlen(ABSPATH . PLUGINDIR . '/'));
	$class_name = '';
	if ($plugin_file != $plugin_name) return;
	$FS_PATH = plugin_basename( __FILE__ );
	if ($FS_PATH)
	{
		$class_name = $plugin_data['slug'];
		$p_url = "http://www.wp-buy.com/product/wp-content-copy-protection-pro/";
		echo '<tr id="' .$class_name. '-plugin-update" class="active">';
		echo '<th class="check-column" scope="row"></th>';
		echo '<td colspan="3" class="plugin-update">';
		echo '<div id="wccp-update-message" style="background:#edf4f7;padding:10px;" >';
		echo __('You are running WP Content Copy Protection & No Right Click (free). To get more features, you can ', 'wp-content-copy-protector') . '<a href="' .$p_url. '" target="_blank"><strong>' . __('Upgrade Now', 'wp-content-copy-protector') . '</strong></a>,    <a id="HideMe" href="javascript:void(0)"><strong>' . __('Dismiss', 'wp-content-copy-protector') . '</strong></a>.';
		echo '</div>';
		echo '</td>';
		echo '</tr>';
	}
	?>
	<script type="text/javascript">
	function wccp_hide_upgrade_message()
	{
		jQuery("#wccp-update-message").empty(); 
		jQuery("#wccp-update-message").removeAttr("style"); 
		localStorage.setItem("wccp_upgrade_message", "hide_upgrade_msg");
		if (!jQuery("#<?php echo $class_name;?>-update")[0]){// Do something if class exists
			jQuery('#<?php echo $class_name;?>-plugin-update').closest('tr').prev().removeClass('update');
		}
		jQuery('#<?php echo $class_name;?>-plugin-update').empty();
	}
	jQuery(document).ready(function() {
		
		var row = jQuery('#<?php echo $class_name;?>-plugin-update').closest('tr').prev();
		jQuery(row).addClass('update');
		
		jQuery("#HideMe").click(wccp_hide_upgrade_message);
	  
	  if(localStorage.getItem("wccp_upgrade_message") == "hide_upgrade_msg")
	  {
		 wccp_hide_upgrade_message();
	  }

	});
	
	</script>
	<?php
}
?>
<?php
$path = plugin_basename( __FILE__ );
add_action("after_plugin_row_{$path}", "wpccp_after_plugin_row", 10, 3 );
//---------------------------------------------Add button with icon to the admin bar
global $wccp_settings;
if (!is_array($wccp_settings)) $wccp_settings = wccp_read_options();
if(array_key_exists('top_bar_icon_btn', $wccp_settings))
{
	if($wccp_settings['top_bar_icon_btn'] == 'Visible')
	{
		add_action('admin_bar_menu', 'wccp_free_add_items',  40);
		add_action('wp_enqueue_scripts', 'wccp_free_top_bar_enqueue_style');
		add_action('admin_enqueue_scripts', 'wccp_free_top_bar_enqueue_style');
	}
}
function wccp_free_top_bar_enqueue_style() {
?>
<style>
#wpadminbar #wp-admin-bar-wccp_free_top_button .ab-icon:before {
	content: "\f160";
	color: #02CA02;
	top: 3px;
}
#wpadminbar #wp-admin-bar-wccp_free_top_button .ab-icon {
	transform: rotate(45deg);
}
</style>
<?php
}
///////////////////
function wccp_free_add_items($admin_bar)
{
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	global $pluginsurl;
	//The properties of the new item. Read More about the missing 'parent' parameter below
	$args = array(
			'id'    => 'wccp_free_top_button',
			'parent' => null,
			'group'  => null,
			'title' => '<span class="ab-icon"></span>' . __('Protection', 'wp-content-copy-protector'),
			'href'  => admin_url('admin.php?page=wccpoptionspro'),
			'meta'  => array('title' => __('Copy Protection & No right click', 'wp-content-copy-protector'),//This title will show on hover
							'class' => '')
			);
 
	//This is where the magic works.
	$admin_bar->add_menu( $args);
}
//------------------------------------------------------------------------
function wccp_options_page_pro() {
     include 'admin-core.php';
}
//------------------------------------------------------------------------
//Make our function to call the WordPress function to add to the correct menu.
function wccp_add_options()
{
	//add_options_page(__('WP Content Copy Protection', 'wp-content-copy-protector'), __('WP Content Copy Protection', 'wp-content-copy-protector'), 'manage_options', 'wccpoptionspro', 'wccp_options_page_pro');
	add_menu_page
		(
			'WP Content Copy Protection',       // use null for parent slug to hide it from admin menu
			'Copy Protection',    // page title
			'manage_options',           // capability
			'wccpoptionspro', // slug
			'wccp_options_page_pro', // callback
			'dashicons-lock',
			6
		);
	add_submenu_page('wccpoptionspro', 'Settings', 'Settings', 'manage_options', 'wccpoptionspro', 'wccp_options_page_pro');
}
//First use the add_action to add onto the WordPress menu.
add_action('admin_menu', 'wccp_add_options');

add_action('admin_menu', 'wccp_free_add_external_links_as_submenu');

function wccp_free_add_external_links_as_submenu() {
	
	global $submenu;
	
	$search_url = "plugin-install.php?s=wp-buy&tab=search&type=author";
	
	$network_dir_append = "";
	
	If (is_multisite()) $network_dir_append = "network/";
	
	$menu_slug = "wccpoptionspro"; // used as "key" in menus
	
	$submenu[$menu_slug][] = array('<span style="color:#f18500">More Plugins</span>', 'manage_options', admin_url( $network_dir_append . 'plugin-install.php?s=wp-buy&tab=search&type=author' ));
}
?>