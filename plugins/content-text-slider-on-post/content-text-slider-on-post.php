<?php
/*
Plugin Name: Content text slider on post
Plugin URI: http://www.gopiplus.com/work/2012/01/02/content-text-slider-on-post-wordpress-plugin/
Description: Content text slider on post is a WordPress plugin from gopiplus.com website. We can use this plugin to scroll the content vertically in the posts and pages. We have option to enter content title, description and link for the content. All entered details scroll vertically into the posts and pages.
Author: Gopi Ramasamy
Version: 8.2
Author URI: http://www.gopiplus.com/work/2012/01/02/content-text-slider-on-post-wordpress-plugin/
Donate link: http://www.gopiplus.com/work/2012/01/02/content-text-slider-on-post-wordpress-plugin/
Tags: Wordpress, plugin, Content, Text, Slider
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: content-text-slider-on-post
Domain Path: /languages
*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb, $wp_version;
define("WP_ctsop_TABLE", $wpdb->prefix . "ctsop_plugin");
define('WP_ctsop_FAV', 'http://www.gopiplus.com/work/2012/01/02/content-text-slider-on-post-wordpress-plugin/');

if ( ! defined( 'WP_ctsop_BASENAME' ) )
	define( 'WP_ctsop_BASENAME', plugin_basename( __FILE__ ) );
	
if ( ! defined( 'WP_ctsop_PLUGIN_NAME' ) )
	define( 'WP_ctsop_PLUGIN_NAME', trim( dirname( WP_ctsop_BASENAME ), '/' ) );
	
if ( ! defined( 'WP_ctsop_PLUGIN_URL' ) )
	define( 'WP_ctsop_PLUGIN_URL', WP_PLUGIN_URL . '/' . WP_ctsop_PLUGIN_NAME );
	
if ( ! defined( 'WP_ctsop_ADMIN_URL' ) )
	define( 'WP_ctsop_ADMIN_URL', get_option('siteurl') . '/wp-admin/options-general.php?page=content-text-slider-on-post' );

function ctsop_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'content-text-slider-on-post', WP_ctsop_PLUGIN_URL.'/content-text-slider-on-post.js');
	}	
}

function ctsop_install() 
{
	global $wpdb;
	if($wpdb->get_var("show tables like '". WP_ctsop_TABLE . "'") != WP_ctsop_TABLE) 
	{
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `". WP_ctsop_TABLE . "` (
			  `ctsop_id` int(11) NOT NULL auto_increment,
			  `ctsop_title` VARCHAR( 1024 ) NOT NULL,
			  `ctsop_text` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			  `ctsop_link` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			  `ctsop_order` int(11) NOT NULL default '0',
			  `ctsop_status` char(3) NOT NULL default 'No',
			  `ctsop_group` VARCHAR( 100 ) NOT NULL,
			  `ctsop_date` datetime NOT NULL default '0000-00-00 00:00:00',
			  PRIMARY KEY  (`ctsop_id`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
			");
		$iIns = "INSERT INTO `". WP_ctsop_TABLE . "` (`ctsop_title`, `ctsop_text`, `ctsop_link`, `ctsop_order`, `ctsop_status`, `ctsop_group`, `ctsop_date`)"; 
		$DummyTitle = "Lorem Ipsum is simply dummy.";
		$DummyText = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.";
		$DummyLink = "http://www.gopiplus.com/work/";
		$DummyImg = '<img src="'.get_option('siteurl').'/wp-content/plugins/content-text-slider-on-post/images/100x100_1.jpg" style="float:left;padding:5px;" /> '. $DummyText;
		
		$sSql = $iIns . "VALUES ('$DummyTitle', '$DummyText','$DummyLink', '1', 'YES', 'GROUP1', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		$sSql = $iIns . "VALUES ('$DummyTitle', '$DummyText','$DummyLink', '2', 'YES', 'GROUP1', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		$sSql = $iIns . "VALUES ('$DummyTitle', '$DummyImg','$DummyLink', '3', 'YES', 'GROUP1', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		$sSql = $iIns . "VALUES ('$DummyTitle', '$DummyText','$DummyLink', '4', 'YES', 'GROUP2', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		$sSql = $iIns . "VALUES ('$DummyTitle', '$DummyText' ,'$DummyLink', '5', 'YES', 'GROUP2', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
	}
	add_option('ctsop_height_display_length_s1', "200_2_500");
	add_option('ctsop_height_display_length_s2', "190_1_500");
	add_option('ctsop_height_display_length_s3', "190_3_500");	
	
	add_option( 'ctsop_speed', 2 );
    add_option( 'ctsop_waitseconds', 2 );
}

function ctsop_admin_options() 
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/content-edit.php');
			break;
		case 'add':
			include('pages/content-add.php');
			break;
		case 'set':
			include('pages/content-setting.php');
			break;
		default:
			include('pages/content-show.php');
			break;
	}
}

function ctsop_Group($number) 
{
	switch ($number) 
	{ 
		case 1: 
			$group = "GROUP1";
			break;
		case 2: 
			$group = "GROUP2";
			break;
		case 3: 
			$group = "GROUP3";
			break;
		case 4: 
			$group = "GROUP4";
			break;
		case 5: 
			$group = "GROUP5";
			break;
		case 6: 
			$group = "GROUP6";
			break;
		case 7: 
			$group = "GROUP7";
			break;
		case 8: 
			$group = "GROUP8";
			break;
		case 9: 
			$group = "GROUP9";
			break;
		case 10: 
			$group = "GROUP10";
			break;
		default:
			$group = "GROUP1";
	}
	return $group;
}

add_shortcode( 'content-text-slider', 'ctsop_shortcode' );

function ctsop_shortcode( $atts ) 
{
	global $wpdb;
	
	//[content-text-slider setting="1" group="1"]
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	$ctsop_setting = $atts['setting'];
	$ctsop_group = $atts['group'];
	
	if($ctsop_setting == "1")
	{
		$ctsop_newsetting = get_option('ctsop_height_display_length_s1');
	}
	elseif($ctsop_setting == "2")
	{
		$ctsop_newsetting = get_option('ctsop_height_display_length_s2');
	}
	elseif($ctsop_setting == "3")
	{
		$ctsop_newsetting = get_option('ctsop_height_display_length_s3');
	}
	else
	{
		$ctsop_newsetting = get_option('ctsop_height_display_length_s1');
	}
	
	if($ctsop_group == "")
	{
		$ctsop_group = "GROUP1";	
	}
	else
	{
		$ctsop_group = ctsop_Group($ctsop_group);
	}
	
	$ctsop_height_display_length = explode("_", $ctsop_newsetting);
	$ctsop_scrollheight = @$ctsop_height_display_length[0];
	$ctsop_sametimedisplay = @$ctsop_height_display_length[1];
	$ctsop_textlength = @$ctsop_height_display_length[2];
	
	if(!is_numeric($ctsop_textlength)){ $ctsop_textlength = 250; }
	if(!is_numeric($ctsop_sametimedisplay)){ $ctsop_sametimedisplay = 2; }
	if(!is_numeric($ctsop_scrollheight)){ $ctsop_scrollheight = 150; }
	
	$ctsop_speed = get_option('ctsop_speed');
	if(!is_numeric($ctsop_speed)) { $ctsop_speed = 2; }
	$ctsop_waitseconds = get_option('ctsop_waitseconds');
	if(!is_numeric($ctsop_waitseconds)) { $ctsop_waitseconds = 2; }
	
	$sSql = "select ctsop_id,ctsop_title,ctsop_text,ctsop_link from ".WP_ctsop_TABLE." where 1=1 and ctsop_status='YES'";
	if($ctsop_group == "ALL" ) 
	{ 
		// display all
	}
	elseif($ctsop_group != "" ) 
	{ 
		$sSql = $sSql . " and ctsop_group = %s "; 
		$sSql = $wpdb->prepare($sSql, $ctsop_group);
	}
	//if($IR_random == "YES"){ $sSql = $sSql . " ORDER BY RAND()"; }else{ $sSql = $sSql . " ORDER BY IR_order"; }
	$sSql = $sSql . " ORDER BY ctsop_order";

	$ctsop_data = $wpdb->get_results($sSql);

	if ( ! empty($ctsop_data) ) 
	{
		$ctsop_count = 0;
		$ctsop_html = "";
		$IRjsjs = "";
		$ctsop_x = "";
		foreach ( $ctsop_data as $ctsop_data ) 
		{
			//$IR_path = mysql_real_escape_string(trim($ctsop_data->IR_path));
			$ctsop_link = trim($ctsop_data->ctsop_link);
			$ctsop_target = "_blank";
			$ctsop_title = trim($ctsop_data->ctsop_title);
			$ctsop_text = trim($ctsop_data->ctsop_text);
			
			if(is_numeric($ctsop_textlength))
			{
				if($ctsop_textlength <> "" && $ctsop_textlength > 0 )
				{
					$ctsop_text = substr($ctsop_text, 0, $ctsop_textlength);
				}
			}
			
			$ctsop_scrollheights = $ctsop_scrollheight."px";	
			
			$ctsop_html = $ctsop_html . "<div class='ctsop_div' style='height:".$ctsop_scrollheights.";padding:1px 0px 1px 0px;'>"; 
			
			//if($IR_path <> "" )
//			{
//				$ctsop_html = $ctsop_html . "<div class='IR-regimage'>"; 
//				$IRjsjs = "<div class=\'IR-regimage\'>"; 
//				if($ctsop_link <> "" ) 
//				{ 
//					$ctsop_html = $ctsop_html . "<a href='$ctsop_link'>"; 
//					$IRjsjs = $IRjsjs . "<a href=\'$ctsop_link\'>";
//				} 
//				$ctsop_html = $ctsop_html . "<img src='$IR_path' al='Test' />"; 
//				$IRjsjs = $IRjsjs . "<img src=\'$IR_path\' al=\'Test\' />";
//				if($ctsop_link <> "" ) 
//				{ 
//					$ctsop_html = $ctsop_html . "</a>"; 
//					$IRjsjs = $IRjsjs . "</a>";
//				}
//				$ctsop_html = $ctsop_html . "</div>";
//				$IRjsjs = $IRjsjs . "</div>";
//			}
			
			if($ctsop_title <> "" )
			{
				$ctsop_html = $ctsop_html . "<div style='padding-left:4px;'><strong>";	
				$IRjsjs = $IRjsjs . "<div style=\'padding-left:4px;\'><strong>";				
				if($ctsop_link <> "" ) 
				{ 
					$ctsop_html = $ctsop_html . "<a href='$ctsop_link'>"; 
					$IRjsjs = $IRjsjs . "<a href=\'$ctsop_link\'>";
				} 
				$ctsop_html = $ctsop_html . $ctsop_title;
				$IRjsjs = $IRjsjs . $ctsop_title;
				if($ctsop_link <> "" ) 
				{ 
					$ctsop_html = $ctsop_html . "</a>"; 
					$IRjsjs = $IRjsjs . "</a>";
				}
				$ctsop_html = $ctsop_html . "</strong></div>";
				$IRjsjs = $IRjsjs . "</strong></div>";
			}
			
			if($ctsop_text <> "" )
			{
				$ctsop_html = $ctsop_html . "<div style='padding-left:4px;'>$ctsop_text</div>";	
				$IRjsjs = $IRjsjs . "<div style=\'padding-left:4px;\'>$ctsop_text</div>";	
			}
			
			$ctsop_html = $ctsop_html . "</div>";
			
			
			$ctsop_x = $ctsop_x . "ctsop[$ctsop_count] = '<div class=\'ctsop_div\' style=\'height:".$ctsop_scrollheights.";padding:1px 0px 1px 0px;\'>$IRjsjs</div>'; ";	
			$ctsop_count++;
			$IRjsjs = "";
		}
		
		//$ctsop_scrollheight = $ctsop_scrollheight + 4;
		$ctsop_scrollheight = $ctsop_scrollheight + 0;
		if($ctsop_count >= $ctsop_sametimedisplay)
		{
			$ctsop_count = $ctsop_sametimedisplay;
			$ctsop_scrollheight_New = ($ctsop_scrollheight * $ctsop_sametimedisplay);
		}
		else
		{
			$ctsop_count = $ctsop_count;
			$ctsop_scrollheight_New = ($ctsop_count  * $ctsop_scrollheight);
		}
		
		$ctsop = "";
		$ctsop = $ctsop . '<div style="padding-top:8px;padding-bottom:8px;">';
		$ctsop = $ctsop . '<div style="text-align:left;vertical-align:middle;text-decoration: none;overflow: hidden; position: relative; margin-left: 3px; height: '. @$ctsop_scrollheight .'px;" id="IRHolder">'.@$ctsop_html.'</div>';
		$ctsop = $ctsop . '</div>';
		$ctsop = $ctsop . '<script type="text/javascript">';
		$ctsop = $ctsop . 'var ctsop = new Array();';
		$ctsop = $ctsop . "var objctsop	= '';";
		$ctsop = $ctsop . "var ctsop_scrollPos 	= '';";
		$ctsop = $ctsop . "var ctsop_numScrolls	= '';";
		$ctsop = $ctsop . 'var ctsop_heightOfElm = '. $ctsop_scrollheight. ';';
		$ctsop = $ctsop . 'var ctsop_numberOfElm = '. $ctsop_count. ';';
		$ctsop = $ctsop . 'var ctsop_speed = '. $ctsop_speed. ';';
		$ctsop = $ctsop . 'var ctsop_waitseconds = '. $ctsop_waitseconds. ';';
		$ctsop = $ctsop . "var ctsop_scrollOn 	= 'true';";
		$ctsop = $ctsop . 'function ctsopScroll() ';
		$ctsop = $ctsop . '{';
		$ctsop = $ctsop . $ctsop_x;
		$ctsop = $ctsop . "objctsop	= document.getElementById('IRHolder');";
		$ctsop = $ctsop . "objctsop.style.height = (ctsop_numberOfElm * ctsop_heightOfElm) + 'px';";
		$ctsop = $ctsop . 'ctsopContent();';
		$ctsop = $ctsop . '}';
		$ctsop = $ctsop . '</script>';
		$ctsop = $ctsop . '<script type="text/javascript">';
		$ctsop = $ctsop . 'ctsopScroll();';
		$ctsop = $ctsop . '</script>';
	}
	else
	{
		$ctsop = "No data available. Please check your short code.";
	}
	return $ctsop;
}

function ctsop_add_to_menu() 
{
	if (is_admin()) 
	{
		add_options_page(__('Content text slider', 'content-text-slider-on-post'), 
							__('Content text slider', 'content-text-slider-on-post'), 'manage_options', 'content-text-slider-on-post', 'ctsop_admin_options' );
	}
}

function ctsop_deactivation() 
{
	// No action required.
}

function ctsop_textdomain() 
{
	  load_plugin_textdomain( 'content-text-slider-on-post', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function ctsop_adminscripts() 
{
	if( !empty( $_GET['page'] ) ) 
	{
		switch ( $_GET['page'] ) 
		{
			case 'content-text-slider-on-post':
				wp_register_script( 'ctsop-adminscripts', WP_ctsop_PLUGIN_URL . '/pages/setting.js', '', '', true );
				wp_enqueue_script( 'ctsop-adminscripts' );
				$ctsop_select_params = array(
					'ctsop_text'   	=> __( 'Please enter the message.', 'ctsop-select', 'content-text-slider-on-post' ),
					'ctsop_status'  => __( 'Please select display status.', 'ctsop-select', 'content-text-slider-on-post' ),
					'ctsop_group'   => __( 'Please select group name. this field is used to group the message.', 'ctsop-select', 'content-text-slider-on-post' ),
					'ctsop_order1'  => __( 'Please enter display order, only number.', 'ctsop-select', 'content-text-slider-on-post' ),
					'ctsop_order2'  => __( 'Please enter display order, only number.', 'ctsop-select', 'content-text-slider-on-post' ),
					'ctsop_delete'  => __( 'Do you want to delete this record?', 'ctsop-select', 'content-text-slider-on-post' ),
				);
				wp_localize_script( 'ctsop-adminscripts', 'ctsop_adminscripts', $ctsop_select_params );
				break;
		}
	}
}

add_action('plugins_loaded', 'ctsop_textdomain');
add_action('wp_enqueue_scripts', 'ctsop_add_javascript_files');
register_activation_hook(__FILE__, 'ctsop_install');
register_deactivation_hook(__FILE__, 'ctsop_deactivation');
add_action('admin_menu', 'ctsop_add_to_menu');
add_action('admin_enqueue_scripts', 'ctsop_adminscripts');
?>