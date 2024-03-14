<?php
/*
Plugin Name: Image slider with description
Plugin URI: http://www.gopiplus.com/work/2011/11/04/wordpress-plugin-image-slider-with-description/
Description: Image slider with description WordPress plugin is a Jquery based image slideshow script that incorporates some of your most requested features all rolled into one. Not only image this slideshow have images, title and description.
Author: Gopi Ramasamy
Version: 9.2
Author URI: http://www.gopiplus.com/work/2011/11/04/wordpress-plugin-image-slider-with-description/
Donate link: http://www.gopiplus.com/work/2011/11/04/wordpress-plugin-image-slider-with-description/
Tags: image, slider, slideshow
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: image-slider-with-description
Domain Path: /languages
*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb, $wp_version;
define("WP_ImgSlider_TABLE", $wpdb->prefix . "ImgSlider_plugin");
define('WP_ImgSlider_FAV', 'http://www.gopiplus.com/work/2011/11/04/wordpress-plugin-image-slider-with-description/');

if ( ! defined( 'WP_ImgSlider_BASENAME' ) )
	define( 'WP_ImgSlider_BASENAME', plugin_basename( __FILE__ ) );
	
if ( ! defined( 'WP_ImgSlider_PLUGIN_NAME' ) )
	define( 'WP_ImgSlider_PLUGIN_NAME', trim( dirname( WP_ImgSlider_BASENAME ), '/' ) );
	
if ( ! defined( 'WP_ImgSlider_PLUGIN_URL' ) )
	define( 'WP_ImgSlider_PLUGIN_URL', WP_PLUGIN_URL . '/' . WP_ImgSlider_PLUGIN_NAME );
	
if ( ! defined( 'WP_ImgSlider_ADMIN_URL' ) )
	if(!defined('WP_ImgSlider_ADMIN_URL')) define( 'WP_ImgSlider_ADMIN_URL', site_url( '/wp-admin/admin.php?page=ImgSlider_image_management' ) );

function ImgSlider_install() 
{
	global $wpdb;
	if($wpdb->get_var("show tables like '". WP_ImgSlider_TABLE . "'") != WP_ImgSlider_TABLE) 
	{
		$sSql = "CREATE TABLE IF NOT EXISTS `". WP_ImgSlider_TABLE . "` (";
		$sSql = $sSql . "`ImgSlider_id` INT NOT NULL AUTO_INCREMENT ,";
		$sSql = $sSql . "`ImgSlider_path` VARCHAR( 1024 ) NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_link` VARCHAR( 1024 ) NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_target` VARCHAR( 50 ) NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_title` VARCHAR( 500 ) NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_desc` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_order` INT NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_status` VARCHAR( 10 ) NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_type` VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_extra1` VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_extra2` VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_date` datetime NOT NULL default '0000-00-00 00:00:00' ,";
		$sSql = $sSql . "PRIMARY KEY ( `ImgSlider_id` )";
		$sSql = $sSql . ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		$wpdb->query($sSql);
		
		$IsSql = "INSERT INTO `". WP_ImgSlider_TABLE . "` (`ImgSlider_path`, `ImgSlider_link`, `ImgSlider_target` , `ImgSlider_title` , `ImgSlider_desc` , `ImgSlider_order` , `ImgSlider_status` , `ImgSlider_type` , `ImgSlider_date`)"; 
		
		$siteurl_link = get_option('siteurl') . "/";
		for($i=1; $i<=2; $i++)
		{
			$sSql = $IsSql . " VALUES ('$siteurl_link/wp-content/plugins/image-slider-with-description/sample/600x400_$i.jpg', '#', '_blank', 'This is image title $i', 'This is image desc $i', '$i', 'YES', 'GROUP1', '0000-00-00 00:00:00');";
			$wpdb->query($sSql);
		}
		for($i=1; $i<=2; $i++)
		{
			$sSql = $IsSql . " VALUES ('$siteurl_link/wp-content/plugins/image-slider-with-description/sample/600x400_$i.jpg', '#', '_blank', 'This is image title $i', 'This is image desc $i', '$i', 'YES', 'GROUP2', '0000-00-00 00:00:00');";
			$wpdb->query($sSql);
		}
	}

	add_option('ImgSlider_option_1', "333-3-222-000000-000000-1-1-0-0-0-green-slide-600");
	add_option('ImgSlider_option_2', "600-3-400-000000-000000-1-1-0-0-0-yellow-fade-700");
}

function ImgSlider_admin_options() 
{
	global $wpdb;
	echo "<div class='wrap'>";
	echo '<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>';
	echo "<h2>"; 
	_e('Image slider with description', 'image-slider-with-description');
	echo "</h2>";
	
	if (isset($_POST['ImgSlider_submit'])) 
	{
		$a = stripslashes(sanitize_text_field($_POST['ImgSlider_sliderWidth']));
		$b = stripslashes(sanitize_text_field($_POST['ImgSlider_borderWidth']));
		$c = stripslashes(sanitize_text_field($_POST['ImgSlider_sliderHeight']));
		$d = stripslashes(sanitize_text_field($_POST['ImgSlider_backgroundColor']));
		$e = stripslashes(sanitize_text_field($_POST['ImgSlider_descColor']));
		$f = stripslashes(sanitize_text_field($_POST['ImgSlider_showButtons']));
		$g = stripslashes(sanitize_text_field($_POST['ImgSlider_showNames']));
		$h = stripslashes(sanitize_text_field($_POST['ImgSlider_showDesc']));
		$i = stripslashes(sanitize_text_field($_POST['ImgSlider_showLink']));
		$j = ""; //stripslashes(@$_POST['ImgSlider_linkNewWindow']);
		$k = stripslashes(sanitize_text_field($_POST['ImgSlider_buttonColor']));
		$l = stripslashes(sanitize_text_field($_POST['ImgSlider_animation']));
		$m = stripslashes(sanitize_text_field($_POST['ImgSlider_fadeSpeed']));
		
		$a1 = stripslashes(sanitize_text_field($_POST['ImgSlider_sliderWidth_1']));
		$b1 = stripslashes(sanitize_text_field($_POST['ImgSlider_borderWidth_1']));
		$c1 = stripslashes(sanitize_text_field($_POST['ImgSlider_sliderHeight_1']));
		$d1 = stripslashes(sanitize_text_field($_POST['ImgSlider_backgroundColor_1']));
		$e1 = stripslashes(sanitize_text_field($_POST['ImgSlider_descColor_1']));
		$f1 = stripslashes(sanitize_text_field($_POST['ImgSlider_showButtons_1']));
		$g1 = stripslashes(sanitize_text_field($_POST['ImgSlider_showNames_1']));
		$h1 = stripslashes(sanitize_text_field($_POST['ImgSlider_showDesc_1']));
		$i1 = stripslashes(sanitize_text_field($_POST['ImgSlider_showLink_1']));
		@$j1 = ""; //stripslashes(@$_POST['ImgSlider_linkNewWindow_1']);
		$k1 = stripslashes(sanitize_text_field($_POST['ImgSlider_buttonColor_1']));
		$l1 = stripslashes(sanitize_text_field($_POST['ImgSlider_animation_1']));
		$m1 = stripslashes(sanitize_text_field($_POST['ImgSlider_fadeSpeed_1']));

		$ImgSlider_option_1 = $a."-".$b."-".$c."-".$d."-".$e."-".$f."-".$g."-".$h."-".$i."-".$j."-".$k."-".$l."-".$m;
		$ImgSlider_option_2 = $a1."-".$b1."-".$c1."-".$d1."-".$e1."-".$f1."-".$g1."-".$h1."-".$i1."-".$j1."-".$k1."-".$l1."-".$m1;

		update_option('ImgSlider_option_1', $ImgSlider_option_1 );
		update_option('ImgSlider_option_2', $ImgSlider_option_2 );
	}
	
	$ImgSlider_option_1 = get_option('ImgSlider_option_1');
	$ImgSlider_option_2 = get_option('ImgSlider_option_2');
	
	list($ImgSlider_sliderWidth, $ImgSlider_borderWidth, $ImgSlider_sliderHeight, $ImgSlider_backgroundColor, $ImgSlider_descColor, $ImgSlider_showButtons, $ImgSlider_showNames,$ImgSlider_showDesc,$ImgSlider_showLink,$ImgSlider_linkNewWindow,$ImgSlider_buttonColor,$ImgSlider_animation,$ImgSlider_fadeSpeed) = explode("-", $ImgSlider_option_1);
	list($ImgSlider_sliderWidth_1, $ImgSlider_borderWidth_1, $ImgSlider_sliderHeight_1, $ImgSlider_backgroundColor_1, $ImgSlider_descColor_1, $ImgSlider_showButtons_1, $ImgSlider_showNames_1,$ImgSlider_showDesc_1,$ImgSlider_showLink_1,$ImgSlider_linkNewWindow_1,$ImgSlider_buttonColor_1,$ImgSlider_animation_1,$ImgSlider_fadeSpeed_1) = explode("-", $ImgSlider_option_2);

	echo '<form name="ImgSlider_form" method="post" action="">';
	
	echo '<table width="80%" border="0" cellspacing="0" cellpadding="0">';
	
	echo '<tr><td style="width:50%;"><h3>'.__( 'Setting 1', 'image-slider-with-description' ).'</h3></td><td style="width:50%;"><h3>'.__( 'Setting 2', 'image-slider-with-description' ).'</h3></td></tr>';
	echo '<tr><td style="width:50%;">';
	echo '<p>'.__( 'Slider Width :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" maxlength="3" type="text" value="';
	echo $ImgSlider_sliderWidth . '" name="ImgSlider_sliderWidth" id="ImgSlider_sliderWidth" /> '.__( '(only number)', 'image-slider-with-description' ).'</p>';

	echo '<p>'.__( 'Border Width :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" maxlength="2" type="text" value="';
	echo $ImgSlider_borderWidth . '" name="ImgSlider_borderWidth" id="ImgSlider_borderWidth" /> '.__( '(only number)', 'image-slider-with-description' ).'</p>';

	echo '<p>'.__( 'Slider Height :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" type="text" maxlength="3" value="';
	echo $ImgSlider_sliderHeight . '" name="ImgSlider_sliderHeight" id="ImgSlider_sliderHeight" /> '.__( '(only number)', 'image-slider-with-description' ).'</p>';

	echo '<p>'.__( 'Background Color :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" type="text" maxlength="6" value="';
	echo $ImgSlider_backgroundColor . '" name="ImgSlider_backgroundColor" id="ImgSlider_backgroundColor" /> '.__( '(color code without #)', 'image-slider-with-description' ).'</p>';
	
	echo '<p>'.__( 'Description Text Color :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" maxlength="6" type="text" value="';
	echo $ImgSlider_descColor . '" name="ImgSlider_descColor" id="ImgSlider_descColor" /> '.__( '(color code without #)', 'image-slider-with-description' ).'</p>';
	
	echo '<p>'.__( 'Show Button :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	echo $ImgSlider_showButtons . '" name="ImgSlider_showButtons" id="ImgSlider_showButtons" /> (0/1)</p>';
	
	echo '<p>'.__( 'Show Name :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	echo $ImgSlider_showNames . '" name="ImgSlider_showNames" id="ImgSlider_showNames" /> (0/1)</p>';
	
	echo '<p>'.__( 'Show Description :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	echo $ImgSlider_showDesc . '" name="ImgSlider_showDesc" id="ImgSlider_showDesc" /> (0/1)</p>';
	
	echo '<p>'.__( 'Show Link :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	echo $ImgSlider_showLink . '" name="ImgSlider_showLink" id="ImgSlider_showLink" /> (0/1)</p>';
	
	//echo '<p>Link New Window :<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	//echo $ImgSlider_linkNewWindow . '" name="ImgSlider_linkNewWindow" id="ImgSlider_linkNewWindow" /> (0/1)</p>';
	
	echo '<p>'.__( 'Button Color :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" type="text" maxlength="8" value="';
	echo $ImgSlider_buttonColor . '" name="ImgSlider_buttonColor" id="ImgSlider_buttonColor" /> (green/yellow/brick/pink/purple/white)</p>';
	
	echo '<p>'.__( 'Plugin Animation :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" maxlength="5" type="text" value="';
	echo $ImgSlider_animation . '" name="ImgSlider_animation" id="ImgSlider_animation" /> (slide/fade)</p>';
	
	echo '<p>'.__( 'Fade Speed :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" maxlength="3" type="text" value="';
	echo $ImgSlider_fadeSpeed . '" name="ImgSlider_fadeSpeed" id="ImgSlider_fadeSpeed" /> '.__( '(only number)', 'image-slider-with-description' ).'</p>';
	echo '</td>';
	
	echo '<td style="width:50%;">';
	echo '<p>'.__( 'Slider Width :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" maxlength="3" type="text" value="';
	echo $ImgSlider_sliderWidth_1 . '" name="ImgSlider_sliderWidth_1" id="ImgSlider_sliderWidth_1" /> '.__( '(only number)', 'image-slider-with-description' ).'</p>';

	echo '<p>'.__( 'Border Width :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" maxlength="2" type="text" value="';
	echo $ImgSlider_borderWidth_1 . '" name="ImgSlider_borderWidth_1" id="ImgSlider_borderWidth_1" /> '.__( '(only number)', 'image-slider-with-description' ).'</p>';

	echo '<p>'.__( 'Slider Height :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" type="text" maxlength="3" value="';
	echo $ImgSlider_sliderHeight_1 . '" name="ImgSlider_sliderHeight_1" id="ImgSlider_sliderHeight_1" /> '.__( '(only number)', 'image-slider-with-description' ).'</p>';

	echo '<p>'.__( 'Background Color :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" type="text" maxlength="6" value="';
	echo $ImgSlider_backgroundColor_1 . '" name="ImgSlider_backgroundColor_1" id="ImgSlider_backgroundColor_1" /> '.__( '(color code without #)', 'image-slider-with-description' ).'</p>';
	
	echo '<p>'.__( 'Description Text Color :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" type="text" maxlength="6" value="';
	echo $ImgSlider_descColor_1 . '" name="ImgSlider_descColor_1" id="ImgSlider_descColor_1" /> '.__( '(color code without #)', 'image-slider-with-description' ).'</p>';
	
	echo '<p>'.__( 'Show Button :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	echo $ImgSlider_showButtons_1 . '" name="ImgSlider_showButtons_1" id="ImgSlider_showButtons_1" /> (0/1)</p>';
	
	echo '<p>'.__( 'Show Name :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	echo $ImgSlider_showNames_1 . '" name="ImgSlider_showNames_1" id="ImgSlider_showNames_1" /> (0/1)</p>';
	
	echo '<p>'.__( 'Show Description :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	echo $ImgSlider_showDesc_1 . '" name="ImgSlider_showDesc_1" id="ImgSlider_showDesc_1" /> (0/1)</p>';
	
	echo '<p>'.__( 'Show Link :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	echo $ImgSlider_showLink_1 . '" name="ImgSlider_showLink_1" id="ImgSlider_showLink_1" /> (0/1)</p>';
	
	//echo '<p>Link New Window :<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	//echo $ImgSlider_linkNewWindow_1 . '" name="ImgSlider_linkNewWindow_1" id="ImgSlider_linkNewWindow_1" /> (0/1)</p>';
	
	echo '<p>'.__( 'Button Color :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" type="text" maxlength="8" value="';
	echo $ImgSlider_buttonColor_1 . '" name="ImgSlider_buttonColor_1" id="ImgSlider_buttonColor_1" /> (green/yellow/brick/pink/purple/white)</p>';
	
	echo '<p>'.__( 'Plugin Animation :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" type="text" maxlength="5" value="';
	echo $ImgSlider_animation_1 . '" name="ImgSlider_animation_1" id="ImgSlider_animation_1" /> (slide/fade)</p>';
	
	echo '<p>'.__( 'Fade Speed :', 'image-slider-with-description' ).'<br><input  style="width: 150px;" type="text" maxlength="3" value="';
	echo $ImgSlider_fadeSpeed_1 . '" name="ImgSlider_fadeSpeed_1" id="ImgSlider_fadeSpeed_1" /> '.__( '(only number)', 'image-slider-with-description' ).'</p>';
	echo '</td></tr>';
	
	echo '</table>';
	echo '*'.__( 'Note : 1 = Enable, 0 = Disable', 'image-slider-with-description' ).'<br><br>';
	
	echo '<input name="ImgSlider_submit" id="ImgSlider_submit" class="button-primary" value="'.__( 'Save Both Image slider Setting', 'image-slider-with-description' ).'" type="submit" />';

	echo '</form>';
	?>
	<br />
	<p class="description">
	<?php _e('Check official website for more information', 'image-slider-with-description'); ?>
	<a target="_blank" href="<?php echo WP_ImgSlider_FAV; ?>"><?php _e('click here', 'image-slider-with-description'); ?></a>
	</p>
	<?php
	echo '</div><br>';
}

function ImgSlider_Group($number) 
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
		case 0: 
			$group = "GROUP0";
			break;
		default:
			$group = "GROUP1";
	}
	return $group;
}

function ImgSlider_Fun( $setting, $group ) 
{
	$arr = array();
	$arr["setting"]=$setting;
	$arr["group"]=$group;
	echo ImgSlider_shortcode( $arr );
}

add_shortcode( 'image-slider-desc', 'ImgSlider_shortcode' );

function ImgSlider_shortcode( $atts ) 
{
	global $wpdb;
	
	$Slider_Img = "";
	$Slider_Desc = "";
	$Slider = "";
	$Slider_random = "";
	
	//[image-slider-desc setting="1" group="1"]
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	$setting = $atts['setting'];
	$group = $atts['group'];
	
	if($setting == "1")
	{
		$ImgSlider_Setting = get_option('ImgSlider_option_1');
	}
	else
	{
		$ImgSlider_Setting = get_option('ImgSlider_option_2');
	}
	
	$set = explode("-", $ImgSlider_Setting);
	
	if($group == "")
	{
		$group = "GROUP1";	
	}
	else
	{
		$group = ImgSlider_Group($group);
	}
	
	$sSql = "select * from ".WP_ImgSlider_TABLE." where 1=1";
	
	if($group <> ""){ 
		$sSql = $sSql . " and ImgSlider_type = %s "; 
		$sSql = $wpdb->prepare($sSql, $group);
	}	
	
	if(@$Slider_random == "YES"){ $sSql = $sSql . " ORDER BY RAND()"; }else{ $sSql = $sSql . " ORDER BY ImgSlider_order"; }
	
	$data = $wpdb->get_results($sSql);
	
	$Slider_count = 1;
	$Slider_Img = "";
	if ( ! empty($data) ) 
	{
		foreach ( $data as $data ) 
		{ 
			 $ImgSlider_path 	= $data->ImgSlider_path;
			 $ImgSlider_link 	= $data->ImgSlider_link;
			 $ImgSlider_target 	= $data->ImgSlider_target;
			 $ImgSlider_path 	= $data->ImgSlider_path;
			 $ImgSlider_title 	= $data->ImgSlider_title;
			 $ImgSlider_desc 	= $data->ImgSlider_desc;
			 
			 if($ImgSlider_link <> "")
			 {
				 $Slider_Img = $Slider_Img . '<a href="'.$ImgSlider_link.'" target="'.$ImgSlider_target.'">';
			 }
			 
			 $Slider_Img = $Slider_Img . '<img id="slide-img-'.$Slider_count.'" src="'.$ImgSlider_path.'" class="slide" alt="'.$ImgSlider_title.'">';
			 
			 if($ImgSlider_link <> "")
			 {
			 	$Slider_Img = $Slider_Img . '</a>';
			 }
			 
			 $Slider_Desc = $Slider_Desc . '{"id":"slide-img-'.$Slider_count.'","client":"'.$ImgSlider_title.'","desc":"'.$ImgSlider_desc.'"},';
			 
			 $Slider_count = $Slider_count + 1;
		}	
		$Slider_Desc = substr($Slider_Desc,0,(strlen($Slider_Desc)-1));
	
	$sliderWidth = $set[0];
	$borderWidth = $set[1];
	$sliderWidth2 = $sliderWidth - $borderWidth;
	$borderWidth2 = $borderWidth/2;
	$sliderHeight = $set[2];
	$sliderHeight2 = $sliderHeight - $borderWidth;
	$backgroundColor = $set[3];
	$descColor = $set[4];
	$showButtons = $set[5];
	$showNames = $set[6];
	$showDesc = $set[7];
	$showLink = $set[8];
	$linkNewWindow = $set[9];
	$buttonColor = $set[10];
	$animation   = $set[11];
	$fadeSpeed   = $set[12];
	
	if ($showButtons==0) { $showButtonsDisplay = 'display:none;'; }else{ $showButtonsDisplay = ''; }
	if ($buttonColor=="white") { $buttonColorValue = "666"; }else{ $buttonColorValue = "fff"; }

$ssg_pluginurl = get_option('siteurl') . "/wp-content/plugins/image-slider-with-description/";

$Slider = $Slider .'<link rel="stylesheet" href="'.$ssg_pluginurl.'style/style.css" type="text/css" />';
	
$Slider = $Slider . '<style type="text/css"> ';
$Slider = $Slider . 'div#header_hotslider div.wrap-ImgSlider { ';
$Slider = $Slider . 'width:'.$sliderWidth.'px; ';
$Slider = $Slider . 'margin:0 auto; ';
$Slider = $Slider . 'text-align:left; ';
$Slider = $Slider . '} ';
	
$Slider = $Slider . 'div#top div#nav { ';
$Slider = $Slider . 'float:left; ';
$Slider = $Slider . 'clear:both; ';
$Slider = $Slider . 'width:'.$sliderWidth.'px; ';
$Slider = $Slider . 'height:52px; ';
$Slider = $Slider . 'margin:22px 0 0; ';
$Slider = $Slider . '} ';
	
$Slider = $Slider . 'div#header_hotslider div.wrap-ImgSlider { ';
$Slider = $Slider . 'height:'.$sliderHeight.'px; ';
$Slider = $Slider . 'background:#'.$backgroundColor.'; ';
$Slider = $Slider . '} ';
	
$Slider = $Slider . 'div#header_hotslider div#slide-holder { ';
$Slider = $Slider . 'width:'.$sliderWidth.'px; ';
$Slider = $Slider . 'height:'.$sliderHeight.'px; ';
$Slider = $Slider . 'position:absolute; ';
$Slider = $Slider . '} ';
	
$Slider = $Slider . 'div#header_hotslider div#slide-holder div#slide-runner { ';
$Slider = $Slider . 'top:'.$borderWidth2.'px; ';
$Slider = $Slider . 'left:'.$borderWidth2.'px; ';
$Slider = $Slider . 'width:'.$sliderWidth2.'px; ';
$Slider = $Slider . 'height:'.$sliderHeight2.'px; ';
$Slider = $Slider . 'overflow:hidden; ';
$Slider = $Slider . 'position:absolute; ';
$Slider = $Slider . '} ';
	
$Slider = $Slider . 'div#header_hotslider div#slide-holder div#slide-controls { ';
$Slider = $Slider . 'left:0; ';
$Slider = $Slider . 'top:10px; ';
$Slider = $Slider . 'width:'.$sliderWidth2.'px; ';
$Slider = $Slider . 'height:46px; ';
$Slider = $Slider . 'display:none; ';
$Slider = $Slider . 'position:absolute; ';
$Slider = $Slider . 'background:url('.$ssg_pluginurl.'images/slide-bg.png) 0 0; ';
$Slider = $Slider . '}';
	
$Slider = $Slider . 'div#header_hotslider div#slide-holder div#slide-controls div#slide-nav { ';
$Slider = $Slider . 'float:right; ';
$Slider = $Slider . $showButtonsDisplay;
$Slider = $Slider . '} ';
	
$Slider = $Slider . 'p.textdesc { ';
$Slider = $Slider . 'float:left; ';
$Slider = $Slider . 'color:#fff; ';
$Slider = $Slider . 'display:inline; ';
$Slider = $Slider . 'font-size:10px; ';
$Slider = $Slider . 'line-height:16px; ';
$Slider = $Slider . 'margin:15px 0 0 20px; ';
$Slider = $Slider . 'text-transform:uppercase; ';
$Slider = $Slider . 'overflow:hidden; ';
$Slider = $Slider . 'color:#'.$descColor.'; ';
$Slider = $Slider . '} ';
	
$Slider = $Slider . 'div#header_hotslider div#slide-holder div#slide-controls div#slide-nav a { ';
$Slider = $Slider . 'background-image:url('.$ssg_pluginurl.'images/slide-nav-'.$buttonColor.'.png); ';
$Slider = $Slider . 'color:#'.$buttonColorValue.'; ';
$Slider = $Slider . 'top:11px; ';
$Slider = $Slider . 'position:relative; ';
$Slider = $Slider . 'text-decoration: none; ';
$Slider = $Slider . '} ';
$Slider = $Slider . '</style> ';

$Slider = $Slider . '<div id="header_hotslider">';
    $Slider = $Slider . '<div class="wrap-ImgSlider">';
      $Slider = $Slider . '<div id="slide-holder">';
        $Slider = $Slider . '<div id="slide-runner">'; 
		$Slider = $Slider . $Slider_Img;
          $Slider = $Slider . '<div id="slide-controls">';
            $Slider = $Slider . '<div id="slide-nav"></div>';
			if ($showNames!=0) 
			{
            	$Slider = $Slider . '<p id="slide-client" class="text"><span></span></p>';
			}
			if ($showDesc!=0) 
			{
            	$Slider = $Slider . '<div style="clear:both"></div>';
     			$Slider = $Slider . '<p id="slide-desc" class="textdesc"></p>';
			}
          $Slider = $Slider . '</div>';
        $Slider = $Slider . '</div>';
      $Slider = $Slider . '</div>';
      $Slider = $Slider . '<script type="text/javascript">';
    $Slider = $Slider . 'if(!window.slider) var slider={};slider.anim="'.$animation.'";slider.fade_speed='.$fadeSpeed.';slider.data=['.$Slider_Desc.'];';
   $Slider = $Slider . '</script> ';
    $Slider = $Slider . '</div>';
  $Slider = $Slider . '</div>';
	}
	else
	{
		$Slider = __( 'Please check the short code, may be no image available for this group', 'image-slider-with-description' );
	}
return $Slider;
}

function ImgSlider_deactivation() 
{
	// No action required.
}

function ImgSlider_image_management() 
{
	global $wpdb;
	$current_page = isset($_GET['sp']) ? $_GET['sp'] : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/image-management-edit.php');
			break;
		case 'add':
			include('pages/image-management-add.php');
			break;
		default:
			include('pages/image-management-show.php');
			break;
	}
}

function ImgSlider_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'imgslider.scripts', WP_ImgSlider_PLUGIN_URL.'/js/scripts.js');
	}	
}

function ImgSlider_add_admin_menu_option() 
{
	if (is_admin()) 
	{
		add_menu_page( __( 'Image Slider', 'image-slider-with-description' ), __( 'Image Slider', 'image-slider-with-description' ), 'administrator', 'ImgSlider', 'ImgSlider_admin_options' );
		add_submenu_page( 'ImgSlider', __( 'Slider Setting', 'image-slider-with-description' ), 
							__( 'Slider Setting', 'image-slider-with-description' ),'administrator', 'ImgSlider', 'ImgSlider_admin_options' );
		add_submenu_page( 'ImgSlider', __( 'Image Management', 'image-slider-with-description' ), 
							__( 'Image Management', 'image-slider-with-description' ),'administrator', 'ImgSlider_image_management', 'ImgSlider_image_management' );
	}
}

function ImgSlider_adminscripts() 
{
	if( !empty( $_GET['page'] ) ) 
	{
		switch ( $_GET['page'] ) 
		{
			case 'ImgSlider_image_management':
				wp_register_script( 'ImgSlider-adminscripts', WP_ImgSlider_PLUGIN_URL . '/pages/setting.js', '', '', true );
				wp_enqueue_script( 'ImgSlider-adminscripts' );
				$ImgSlider_select_params = array(
					'ImgSlider_path'   		=> __( 'Please enter the image path.', 'ImgSlider-select', 'image-slider-with-description' ),
					'ImgSlider_link'  		=> __( 'Please enter the target link.', 'ImgSlider-select', 'image-slider-with-description' ),
					'ImgSlider_title'   	=> __( 'Please enter the image title.', 'ImgSlider-select', 'image-slider-with-description' ),
					'ImgSlider_order'  		=> __( 'Please enter the display order, only number.', 'ImgSlider-select', 'image-slider-with-description' ),
					'ImgSlider_order1'  	=> __( 'Please enter the display order, only number.', 'ImgSlider-select', 'image-slider-with-description' ),
					'ImgSlider_delete'		=> __( 'Do you want to delete this record?', 'ImgSlider-select', 'image-slider-with-description' ),
				);
				wp_localize_script( 'ImgSlider-adminscripts', 'ImgSlider_adminscripts', $ImgSlider_select_params );
				break;
		}
	}
} 

function ImgSlider_textdomain() 
{
	  load_plugin_textdomain( 'image-slider-with-description', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action('plugins_loaded', 'ImgSlider_textdomain');
add_action('admin_menu', 'ImgSlider_add_admin_menu_option');
add_action('wp_enqueue_scripts', 'ImgSlider_add_javascript_files');
register_activation_hook(__FILE__, 'ImgSlider_install');
register_deactivation_hook(__FILE__, 'ImgSlider_deactivation');
add_action('admin_enqueue_scripts', 'ImgSlider_adminscripts');
?>