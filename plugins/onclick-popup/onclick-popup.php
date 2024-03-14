<?php
/*
Plugin Name: Onclick Popup
Plugin URI: http://www.gopiplus.com/work/2011/11/13/wordpress-plugin-onclick-popup/
Description: One easy way to send your visitors a welcome message, notice, or advertisement is to add this popup plugin to your site. WordPress on-click Popup plugin will create a popup message to your website. The popup will appear on text click so it is named on-click popup.
Author: Gopi Ramasamy
Version: 7.2
Author URI: http://www.gopiplus.com/work/
Donate link: http://www.gopiplus.com/work/2011/11/13/wordpress-plugin-onclick-popup/
Tags: popup, onclick, plugin, widget
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: onclick-popup
Domain Path: /languages
*/

global $wpdb, $wp_version;
define("WP_ONCLICK_PLUGIN", $wpdb->prefix . "onclick_popup_plugin");
define('WP_onclickpopup_FAV', 'http://www.gopiplus.com/work/2011/11/13/wordpress-plugin-onclick-popup/');

if ( ! defined( 'WP_onclickpopup_BASENAME' ) )
	define( 'WP_onclickpopup_BASENAME', plugin_basename( __FILE__ ) );
	
if ( ! defined( 'WP_onclickpopup_PLUGIN_NAME' ) )
	define( 'WP_onclickpopup_PLUGIN_NAME', trim( dirname( WP_onclickpopup_BASENAME ), '/' ) );
	
if ( ! defined( 'WP_onclickpopup_PLUGIN_URL' ) )
	define( 'WP_onclickpopup_PLUGIN_URL', WP_PLUGIN_URL . '/' . WP_onclickpopup_PLUGIN_NAME );
	
if ( ! defined( 'WP_onclickpopup_ADMIN_URL' ) )
	define( 'WP_onclickpopup_ADMIN_URL', get_option('siteurl') . '/wp-admin/admin.php?page=onclick-popup-content' );

function OnClickPopUp() 
{
	global $wpdb;
	$li = "";
	$setting = get_option('onclickpopup_setting1');
	$setting_left = get_option('onclickpopup_setting1_left');
	$setting_top = get_option('onclickpopup_setting1_top');
	?>
    <style type="text/css">
	#PopUpFad1, #PopUpFad2, #PopUpFad3, #PopUpFad4, #PopUpFad5, #PopUpFad6 
	{
		top: <?php echo $setting_top; ?>px;
		<?php echo $setting; ?>
		position: absolute;
		margin: 0 auto;
		display: none;
		opacity: 0;
		KHTMLOpacity: 0;
		filter: alpha(opacity=0); 
		-moz-opacity: 0;
		z-index: 1000;	
	}
	</style>
    <link rel="stylesheet" type="text/css" href="<?php echo WP_onclickpopup_PLUGIN_URL; ?>/onclick-popup.css" />
	<?php
	$sSql = "select * from ".WP_ONCLICK_PLUGIN." where 1=1";
	$sSql = $sSql . " and onclickpopup_group='GROUP1'";
	$sSql = $sSql . " and (`onclickpopup_date` >= NOW() or `onclickpopup_date` = '0000-00-00')";
	$sSql = $sSql . " order by rand() limit 0,6";
	$data = $wpdb->get_results($sSql);
	if ( ! empty($data) ) 
	{
		$counter = 1;
		foreach ( $data as $data ) 
		{
			$onclickpopup_group = stripslashes($data->onclickpopup_group);
			$onclickpopup_title = htmlspecialchars(stripslashes($data->onclickpopup_title));
			//$onclickpopup_content = htmlspecialchars(stripslashes($data->onclickpopup_content));
			$onclickpopup_temp = $data->onclickpopup_content;
			$onclickpopup_temp = do_shortcode($onclickpopup_temp);
			$onclickpopup_content = stripslashes($onclickpopup_temp);
			
			$div = "'PopUpFad".$counter."'";
			$li = $li . '<li><a href="#" onclick="PopUpFadOpen('.$div.', '.$setting_left.')">'.$onclickpopup_title.'</a></li>';
			?>
			<div id="PopUpFad<?php echo $counter; ?>">
      		<div class="PopUpFadClose"><a href="#" onClick="PopUpFadCloseX('PopUpFad<?php echo $counter; ?>')">
            <img src="<?php echo WP_onclickpopup_PLUGIN_URL; ?>/close.jpg" /></a></div>
      		<div><?php echo $onclickpopup_content; ?></div>
    		</div>
    		<?php
			$counter = $counter + 1;
		}
		?><ul><?php echo $li; ?></ul><?php
	}
}

function onclickpopup_install() 
{
	global $wpdb;
	if($wpdb->get_var("show tables like '". WP_ONCLICK_PLUGIN . "'") != WP_ONCLICK_PLUGIN) 
	{
		$sSql = "CREATE TABLE IF NOT EXISTS `". WP_ONCLICK_PLUGIN . "` (";
		$sSql = $sSql . "`onclickpopup_id` INT NOT NULL AUTO_INCREMENT ,";
		$sSql = $sSql . "`onclickpopup_group` VARCHAR( 10 ) NOT NULL ,";
		$sSql = $sSql . "`onclickpopup_title` VARCHAR( 1024 ) NOT NULL ,";
		$sSql = $sSql . "`onclickpopup_content` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,";
		$sSql = $sSql . "`onclickpopup_Random` VARCHAR( 4 ) NOT NULL ,";
		$sSql = $sSql . "`onclickpopup_Extra1` VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "`onclickpopup_Extra2` VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "`onclickpopup_Extra3` VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "`onclickpopup_date` datetime NOT NULL default '0000-00-00 00:00:00' ,";
		$sSql = $sSql . "PRIMARY KEY ( `onclickpopup_id` )";
		$sSql = $sSql . ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		$wpdb->query($sSql);
		
		$IsSql = "INSERT INTO `". WP_ONCLICK_PLUGIN . "` (`onclickpopup_group`, `onclickpopup_title`, `onclickpopup_content`, `onclickpopup_date`)"; 
		$dummy = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec mollis. Quisque convallis libero in sapien pharetra tincidunt.";
		$dummy = $dummy . "Aliquam elit ante, malesuada id, tempor eu, gravida id, odio. Maecenas suscipit, risus et eleifend imperdiet, nisi orci ullamcorper massa";
		for($i=1;$i<=5;$i++)
		{
			$sSql = $IsSql . " VALUES ('GROUP1', 'Sample Title $i', '$dummy', '0000-00-00 00:00:00');";
			$wpdb->query($sSql);
		}
	}

	add_option('onclickpopup_title', "Onclick Popup");
	add_option('onclickpopup_setting1', "width: 320px; background: #FFF; text-align: left; font-family: Arial,sans-serif; padding: 10px; border: 2px solid #666;");
	add_option('onclickpopup_setting1_left', '400');
	add_option('onclickpopup_setting1_top', '50');
	add_option('onclickpopup_setting2', "width: 320px; background: #FFF; text-align: left; font-family: Arial,sans-serif; padding: 10px; border: 2px solid #666;");
	add_option('onclickpopup_setting2_left', '400');
	add_option('onclickpopup_setting2_top', '50');
	add_option('onclickpopup_setting3', "width: 320px; background: #FFF; text-align: left; font-family: Arial,sans-serif; padding: 10px; border: 2px solid #666;");
	add_option('onclickpopup_setting3_left', '400');
	add_option('onclickpopup_setting3_top', '50');
	add_option('onclickpopup_setting4', "width: 320px; background: #FFF; text-align: left; font-family: Arial,sans-serif; padding: 10px; border: 2px solid #666;");
	add_option('onclickpopup_setting4_left', '400');
	add_option('onclickpopup_setting4_top', '50');
	add_option('onclickpopup_setting5', "width: 320px; background: #FFF; text-align: left; font-family: Arial,sans-serif; padding: 10px; border: 2px solid #666;");
	add_option('onclickpopup_setting5_left', '400');
	add_option('onclickpopup_setting5_top', '50');
}

function onclickpopup_admin_options() 
{
	global $wpdb;
	include('pages/content-setting.php');
}

function onclickpopup_admin_content() 
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/content-management-edit.php');
			break;
		case 'add':
			include('pages/content-management-add.php');
			break;
		default:
			include('pages/content-management-show.php');
			break;
	}
}

add_shortcode( 'onclick-popup', 'onclickpopup_shortcode' );

function onclickpopup_shortcode( $atts ) 
{
	global $wpdb;
	$OnClickPopup = "";
	$li = "";
	
	//[onclick-popup setting="1" group="1"]
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	$setting = $atts['setting'];
	$group = $atts['group'];
	
	if($setting == "1")
	{
		$setting = get_option('onclickpopup_setting1');
		$setting_left = get_option('onclickpopup_setting1_left');
		$setting_top = get_option('onclickpopup_setting1_top');
	}
	elseif($setting == "2")
	{
		$setting = get_option('onclickpopup_setting2');
		$setting_left = get_option('onclickpopup_setting2_left');
		$setting_top = get_option('onclickpopup_setting2_top');
	}
	elseif($setting == "3")
	{
		$setting = get_option('onclickpopup_setting3');
		$setting_left = get_option('onclickpopup_setting3_left');
		$setting_top = get_option('onclickpopup_setting3_top');
	}
	elseif($setting == "4")
	{
		$setting = get_option('onclickpopup_setting4');
		$setting_left = get_option('onclickpopup_setting4_left');
		$setting_top = get_option('onclickpopup_setting4_top');
	}
	elseif($setting == "5")
	{
		$setting = get_option('onclickpopup_setting5');
		$setting_left = get_option('onclickpopup_setting5_left');
		$setting_top = get_option('onclickpopup_setting5_top');
	}
	
	$OnClickPopup = $OnClickPopup . '<style type="text/css">';
	$OnClickPopup = $OnClickPopup . '#PopUpFad1, #PopUpFad2, #PopUpFad3, #PopUpFad4, #PopUpFad5, #PopUpFad6 ';
	$OnClickPopup = $OnClickPopup . '{';
		$OnClickPopup = $OnClickPopup . 'top: '. $setting_top. 'px; ';
		$OnClickPopup = $OnClickPopup . $setting;
		$OnClickPopup = $OnClickPopup . 'position: absolute;';
		$OnClickPopup = $OnClickPopup . 'margin: 0 auto;';
		$OnClickPopup = $OnClickPopup . 'display: none;';
		$OnClickPopup = $OnClickPopup . 'opacity: 0;';
		$OnClickPopup = $OnClickPopup . 'KHTMLOpacity: 0;';
		$OnClickPopup = $OnClickPopup . 'filter: alpha(opacity=0); ';
		$OnClickPopup = $OnClickPopup . '-moz-opacity: 0;';
		$OnClickPopup = $OnClickPopup . 'z-index: 1000;	';
	$OnClickPopup = $OnClickPopup . '}';
	$OnClickPopup = $OnClickPopup . '</style>';
    $OnClickPopup = $OnClickPopup . '<link rel="stylesheet" type="text/css" href="'.WP_onclickpopup_PLUGIN_URL.'/onclick-popup.css" />';
    
	$sSql = "select * from ".WP_ONCLICK_PLUGIN." where 1=1";
	$sSql = $sSql . " and onclickpopup_group = %s ";
	$sSql = $sSql . " and (`onclickpopup_date` >= NOW() or `onclickpopup_date` = '0000-00-00')";
	$sSql = $sSql . " order by rand() limit 0,6";

	$sSql = $wpdb->prepare($sSql, 'GROUP' . $group);
	
	$data = $wpdb->get_results($sSql);
	if ( ! empty($data) ) 
	{
		$counter = 1;
		foreach ( $data as $data ) 
		{
			$onclickpopup_group = stripslashes($data->onclickpopup_group);
			$onclickpopup_title = stripslashes(esc_html($data->onclickpopup_title));
			//$onclickpopup_content = stripslashes($data->onclickpopup_content);
			$onclickpopup_temp = $data->onclickpopup_content;
			$onclickpopup_temp = do_shortcode($onclickpopup_temp);
			$onclickpopup_content = stripslashes(esc_html($onclickpopup_temp));
			
			$div = "'PopUpFad".$counter."'";
			$li = $li . '<div><a href="#" onclick="PopUpFadOpen('.$div.', '.$setting_left.')">'.$onclickpopup_title.'</a></div>';
			
			$OnClickPopup = $OnClickPopup . '<div id='.$div.'>';
      		$OnClickPopup = $OnClickPopup . '<div class="PopUpFadClose"><a href="#" onClick="PopUpFadCloseX('.$div.')">';
            $OnClickPopup = $OnClickPopup . '<img src="'.WP_onclickpopup_PLUGIN_URL.'/close.jpg" /></a></div>';
      		$OnClickPopup = $OnClickPopup . '<div>'.$onclickpopup_content.'</div>';
    		$OnClickPopup = $OnClickPopup . '</div>';
    		
			$counter = $counter + 1;
		}
		$OnClickPopup = $OnClickPopup .$li;
	}
	return $OnClickPopup;
}

function onclickpopup_deactivation() 
{
	// No action required.
}

function onclickpopup_add_javascript_files() 
{	
	wp_enqueue_script( 'onclick-popup', WP_onclickpopup_PLUGIN_URL.'/onclick-popup.js');
}

function onclickpopup_add_to_menu() 
{
	add_menu_page( __( 'OnClick Popup', 'onclick-popup' ), __( 'OnClick Popup', 'onclick-popup' ), 'administrator', 'onclick-popup', 'onclickpopup_admin_options' );
	add_submenu_page( 'onclick-popup', __( 'Popup Setting', 'onclick-popup' ), __( 'Popup Setting', 'onclick-popup' ),'administrator', 'onclick-popup', 'onclickpopup_admin_options' );
	add_submenu_page( 'onclick-popup', __( 'Content Management', 'onclick-popup' ), __( 'Content Management', 'onclick-popup' ),'administrator', 'onclick-popup-content', 'onclickpopup_admin_content' );
}

function onclickpopup_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('onclickpopup_Title');
	echo $after_title;
	OnClickPopUp();
	echo $after_widget;
}

function onclickpopup_control() 
{
	echo '<p><b>';
	_e('OnClick Popup', 'onclick-popup');
	echo '.</b> ';
	_e('Check official website for more information', 'onclick-popup');
	?> <a target="_blank" href="<?php echo WP_onclickpopup_FAV; ?>"><?php _e('click here', 'onclick-popup'); ?></a></p><?php
}

function onclickpopup_init()
{
	if(function_exists('register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('onclick-popup', __( 'OnClick Popup', 'onclick-popup' ), 'onclickpopup_widget');
	}
	
	if(function_exists('register_widget_control')) 
	{
		wp_register_widget_control('onclick-popup', array( __( 'OnClick Popup', 'onclick-popup' ), 'widgets'), 'onclickpopup_control');
	} 
}

if (!is_admin())
{
	add_action('init', 'onclickpopup_add_javascript_files');
}
else
{
	add_action('admin_menu', 'onclickpopup_add_to_menu');
}

function onclickpopup_textdomain() 
{
	  load_plugin_textdomain( 'onclick-popup', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function onclickpopup_adminscripts() 
{
	if( !empty( $_GET['page'] ) ) 
	{
		switch ( $_GET['page'] ) 
		{
			case 'onclick-popup-content':
				wp_register_script( 'onclickpopup-adminscripts', WP_onclickpopup_PLUGIN_URL . '/pages/setting.js', '', '', true );
				wp_enqueue_script( 'onclickpopup-adminscripts' );
				$onclickpopup_select_params = array(
					'onclickpopup_group'   	=> __( 'Please select popup group.', 'onclickpopup-select', 'onclick-popup' ),
					'onclickpopup_title'  	=> __( 'Please enter the popup title.', 'onclickpopup-select', 'onclick-popup' ),
					'onclickpopup_content'  => __( 'Please enter the popup content.', 'onclickpopup-select', 'onclick-popup' ),
					'onclickpopup_delete'	=> __( 'Do you want to delete this record?', 'onclickpopup-select', 'onclick-popup' ),
				);
				wp_localize_script( 'onclickpopup-adminscripts', 'onclickpopup_adminscripts', $onclickpopup_select_params );
				break;
		}
	}
} 

add_action('plugins_loaded', 'onclickpopup_textdomain');
add_action("plugins_loaded", "onclickpopup_init");
register_activation_hook(__FILE__, 'onclickpopup_install');
register_deactivation_hook(__FILE__, 'onclickpopup_deactivation');
add_action('admin_enqueue_scripts', 'onclickpopup_adminscripts');
?>