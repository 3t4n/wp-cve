<?php
/*
Plugin Name:Dashboard Editor
Plugin URI: http://anthologyoi.com/plugins/
Description: Allows you to customise the dashboard.
Author: Aaron Harun
Version: 1.1
Author URI: http://anthologyoi.com/
*/
$dashboard = get_option("dashboard");

/* Okay, since you are reading this, you will notice it is a VERY hack and slash plugin.
I'll clean it up later. For now, it works.*/

if($_GET['page'] == 'dashboard.php'){

	add_action('admin_head', 'dashboard_head');

	function dashboard_head(){
		echo '
		<style type="text/css">
			textarea{
				width:100%;
			}
		</style>
		';
	}
}

if($dashboard['sidebar']){
	add_action('init', 'dashboard_sidebar');

	function dashboard_sidebar(){
		global $dashboard;
		if(function_exists('register_sidebar')){
			register_sidebar('name=admin');
				$dashboard['admin_sidebars'] = (int)$dashboard['admin_sidebars'];
			if($dashboard['admin_sidebars'] > 0){
				for($i=1; $i <= $dashboard['admin_sidebars']; $i++){
				register_sidebar("name=admin$i");
				}
			}

		}
	}
}

if(strpos($_SERVER['SCRIPT_NAME'],'/index.php') == true && ($_SERVER['QUERY_STRING'] == '' || $_GET['post_status'] != '')  && $dashboard){

	if($dashboard['planetnews']){
		$dashboard['dashboard_primary'] = $dashboard['planetnews'];
		unset($dashboard['planetnews']);
	}
	if($dashboard['incoming']){
		$dashboard['dashboard_incoming_links'] = $dashboard['incoming'];
		unset($dashboard['incoming']);
	}

	if($dashboard['planetnews']){
		$dashboard['dashboard_secondary'] = $dashboard['devnews'];
		unset($dashboard['devnews']);
	}

	if($wp_version < 2.5){
		add_action('admin_head', 'dashboard_start_23');
	}else{
		add_action('admin_head', 'dashboard_start');
		add_filter( 'wp_dashboard_widgets', 'dash_remove_widgets');
	}

}

function dash_remove_widgets($dashboard_widgets){
global $dashboard;
	$remaining = array();
	if($dashboard['complete_wipe'] == 1 || $dashboard['wp_widgets'] == 1){
		return $remaining;
	}

	foreach($dashboard_widgets as $widget){
		if($dashboard[$widget] != 1){
			$remaining[] = $widget;
		}
	}
	return $remaining;


}

function dasboard_wipe($buffer){
	global $dashboard;

		if($dashboard['wp_widgets'] == 1 || $dashboard['complete_wipe'] == 1){
				$all = 1;
		}
					if($dashboard['dashboard_primary'] == 1 || $all == 1)
						$buffer = str_replace("jQuery('#dashboard_primary div.dashboard-widget-content').not( '.dashboard-widget-control' ).find( '.widget-loading' ).parent().load('index-extra.php?jax=devnews');",'',$buffer);
					if($dashboard['dashboard_secondary'] == 1|| $all == 1)
						$buffer = str_replace("jQuery('#dashboard_secondary div.dashboard-widget-content').not( '.dashboard-widget-control' ).find( '.widget-loading' ).parent().load('index-extra.php?jax=planetnews');",'',$buffer);
					if($dashboard['dashboard_incoming_links'] == 1|| $all == 1)
						$buffer = str_replace("jQuery('#dashboard_incoming_links div.dashboard-widget-content').not( '.dashboard-widget-control' ).find( '.widget-loading' ).parent().load('index-extra.php?jax=incominglinks');",'',$buffer);
					if($dashboard['dashboard_plugins'] == 1|| $all == 1)
						$buffer = str_replace("jQuery('#dashboard_plugins div.dashboard-widget-content').not( '.dashboard-widget-control' ).find( '.widget-loading' ).parent().load('index-extra.php?jax=plugins');",'',$buffer);

			$buffer = str_replace("var update2 = new Ajax.Updater( 'devnews', 'index-extra.php?jax=devnews' );",'',$buffer);


		if($dashboard['complete_wipe'] == 1){
			preg_split('@\<div class=\"wrap\"\>[\S\s]*\<\/div\>\<!-- wrap --\>@','',$buffer);
			$parts[0] .= '<div class="wrap">';
			$parts[1] .= '</div><!-- wrap -->';
		}else{
			$parts = preg_split('/\<\/div\>\<!-- rightnow --\>/',$buffer);
			$parts[0] .= '</div><!-- rightnow -->';

			if($dashboard['wp_widgets'] != 1 && $dashboard['complete_wipe'] != 1){
				$parts2 = preg_split('/\<div id="dashboard-widgets-wrap"\>/',$parts[1]);
				$parts[1] = '<div id="dashboard-widgets-wrap">'.$parts2[0];

				$parts[2] = $parts2[1];
			}

		}

		if(count($parts) > 0){
			echo $parts[0];

			if($dashboard['wp_widgets'] == 1 || $dashboard['complete_wipe'] == 1){
				eval ('?>'.stripslashes($dashboard['dashboard_code'])) ;
			}


			echo $after;
			echo $parts[1];
			if($parts[2]){
				dashboard_fake_widgets();
			echo $parts[2];
			}
		}else{
			echo $buffer;
		}
	}

	function dashboard_fake_widgets(){
	global $dashboard;
		$count = (int)$dashboard[admin_widgets] + 1;
		for($x = 0; $x <= $count; $x++){
			if($dashboard['dashboard_widget_code'.$x]){
			?>

			<div id="dashboard_widget_code<?php echo $x?>" class="dashboard-widget-holder widget_rss wp_dashboard_empty">

				<div class="dashboard-widget">

			<h3 class="dashboard-widget-title"><span>Dashboard Editor Widget <?php echo $x+1;?></span><br class="clear"/></h3>

			<div class="dashboard-widget-content">

			<?php eval ('?>'.stripslashes($dashboard['dashboard_widget_code'.$x])) ; ?>
			</div>

			</div>

			</div>

			<?php
			}
		}


	}

	function dashboard_start($buffer){
	global $dashboard;
		echo '<style type="text/css">';
			if($dashboard['complete_wipe'] != 1){
				if($dashboard['wp_widgets'] == 1){
					echo "#dashboard-widgets {display:none;}";
				}
				if($dashboard['started'] == 1){
					echo "#rightnow {display:none;}";
				}else{
					if($dashboard['youhave'] == 1)
						echo ".youhave{display:none;}";

					if($dashboard['youare'] == 1)
						echo ".youare{display:none;}";
				}

			}

		echo '</style>';

		echo '<script type="text/javascript">jQuery(function() {';
			if($dashboard['complete_wipe'] != 1){
				if($dashboard['wp_widgets'] == 1){
					echo "jQuery('#dashboard-widgets').remove();";
				}

				if($dashboard['started'] == 1){
					echo "jQuery('#rightnow').remove();";
				}else{
					if($dashboard['youhave'] == 1)
						echo "jQuery('.youhave').remove();";
					if($dashboard['youare'] == 1)
						echo "jQuery('.youare').remove();";
				}
			}

		echo '});</script>';
		ob_start();
		add_action('admin_footer', 'dashboard_end');
	}

	function dashboard_end($buffer){
		$buffer = ob_get_contents();
		ob_end_clean();
		dasboard_wipe($buffer);
	}

/**
For 2.3 and below.
**/
	function dasboard_wipe_23($buffer){
	global $dashboard;

		if($dashboard['dashboard_primary'] == 1 || $dashboard['complete_wipe'] == 1){
			$buffer = str_replace("var update2 = new Ajax.Updater( 'devnews', 'index-extra.php?jax=devnews' );",'',$buffer);
		}else{
			$after .= '<div id="devnews"></div>';
		}
		if($dashboard['dashboard_secondary'] == 1 || $dashboard['complete_wipe'] == 1){
			$buffer = str_replace("var update3 = new Ajax.Updater( 'planetnews', 'index-extra.php?jax=planetnews'	);",'',$buffer);
		}else{
			$after .='<div id="planetnews"></div>';
		}
		if($dashboard['dashboard_incoming_links'] == 1 || $dashboard['complete_wipe'] == 1){
			$buffer = str_replace("var update1 = new Ajax.Updater( 'incominglinks', 'index-extra.php?jax=incominglinks' );",'',$buffer);
		}
		if($dashboard['started'] == 1 || $dashboard['complete_wipe'] == 1){
			$buffer = preg_replace('/\<\/div\>\s*\<p\>.*?\<\/p\>[\S\s]*?\<\/ul\>/','</div>',$buffer);
		}
		if($dashboard['complete_wipe'] == 1){
			$parts = preg_split('/\<div class=\"wrap\"\>[\S\s]*\<div id=\"footer\"\>/',$buffer);
			$parts[0] .= '<div class="wrap">';
			$parts[1] .= '</div>';
		}else{
			$parts = preg_split('/\<div id="devnews">[\S\s]*\<div id=\"footer\"\>/',$buffer);

		}

		if(count($parts) > 0){
			echo $parts[0];
			eval ('?>'.stripslashes($dashboard['dashboard_code'])) ;


			echo $after;
			echo '<div style="clear: both">&nbsp;<br clear="all" /></div></div><div id="footer">';
			echo $parts[1];
		}else{
			echo $buffer;
		}
	}

	function dashboard_start_23($buffer){
		ob_start();
		add_action('admin_footer', 'dashboard_end_23');
	}

	function dashboard_end_23($buffer){
		$buffer = ob_get_contents();
		ob_end_clean();
		dasboard_wipe_23($buffer);
	}


/**

Admin menu

**/
add_action('admin_menu', 'dashboard_menu');

function dashboard_menu() {
 // Add a submenu to the Dashboard:
    add_submenu_page('index.php', 'Dashboard Managment', 'Dashboard Managment', 8, 'wordpress-dashboard-editor/'.__file__, 'dashboard_manage');
}

function dashboard_manage(){
	global $wpdb, $dashboard,$use_options,$wp_version;
		  if ($_POST["action"] == "saveconfiguration") {
					dashboard_update_options($_REQUEST['dashboard']);
					update_option('dashboard',$dashboard);
		  			$message .= 'dashboard options updated.<br/>';

				//if we don't the panel will show old value...which may scare people.
				//$dashboard_all doesn't need to be updated because it has the new values added to it immediately
				$use_options = get_option('dashboard_use_options');

			    echo '<div class="updated"><p><strong>'.$message;
			    echo '</strong></p></div>';
			}

	$options = array('dashboard_primary','dashboard_secondary', 'complete_wipe', 'dashboard_incoming_links', 'started', 'sidebar', 'youare', 'youhave', 'dashboard_recent_comments','dashboard_recent_comments','wp_widgets','wrap','dashboard_plugins');
	foreach($options as $name){
		if($dashboard[$name] == 1){
			$dashboard[$name] = 'checked="checked"';
		}else{
			$dashboard[$name] = '';
		}
	}
?>
<div class="wrap">
		<form method="post">
<table>
		<tr>
			<td colspan="2"><strong>Dashboard Configuration</strong></td>
		</tr>
		<tr>
			<td colspan="2">Please note that some options are only availible for WordPress 2.5. Options valid in WordPress 2.3 are marked as such.</td>
		</tr>
		<tr>
			<td>Completely wipe dashboard? (will remove everything except the header and footer): (works in 2.3)</td>
			<td><input type="checkbox" value="1" <?php echo $dashboard[complete_wipe]?> name="dashboard[complete_wipe]"></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:center; font-weight:800;">Manage Right Now Section. </td>
		</tr>
		<tr>
			<td>Remove entire "Right Now" section: (Getting started in wp 2.3)</td>
			<td><input type="checkbox" value="1" <?php echo $dashboard[started]?> name="dashboard[started]"></td>
		</tr>
		<tr>
			<td>Remove "You Have" section:</td>
			<td><input type="checkbox" value="1" <?php echo $dashboard[youhave]?> name="dashboard[youhave]"></td>
		</tr>
		<tr>
			<td>Remove "You Are" section:</td>
			<td><input type="checkbox" value="1" <?php echo $dashboard[youare]?> name="dashboard[youare]"></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:center; font-weight:800;">Manage Widget Section.</td>
		</tr>
		<tr>
			<td>Remove entire Widget section:</td>
			<td><input type="checkbox" value="1" <?php echo $dashboard[wp_widgets]?> name="dashboard[wp_widgets]"></td>
		</tr>
		<tr>
			<td>Remove Developers news?: (Also for WP 2.3)</td>
			<td><input type="checkbox" value="1" <?php echo $dashboard[dashboard_primary]?> name="dashboard[dashboard_primary]"></td>
		</tr>
		<tr>
			<td>Remove Planet News: (Also for WP 2.3)</td>
			<td><input type="checkbox" value="1" <?php echo $dashboard[dashboard_secondary]?> name="dashboard[dashboard_secondary]"></td>
		</tr>
		<tr>
			<td>Remove Wordpress Plugins:</td>
			<td><input type="checkbox" value="1" <?php echo $dashboard[dashboard_plugins]?> name="dashboard[dashboard_plugins]"></td>
		</tr>
		<tr>
			<td>Remove Incoming Links: (Also for WP 2.3)</td>
			<td><input type="checkbox" value="1" <?php echo $dashboard[dashboard_incoming_links]?> name="dashboard[dashboard_incoming_links]"></td>
		</tr>
		<tr>
			<td>Remove recent comments:</td>
			<td><input type="checkbox" value="1" <?php echo $dashboard[dashboard_recent_comments]?> name="dashboard[dashboard_recent_comments]"></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:center; font-weight:800;">Sidebar Widget options</td>
		</tr>
		<tr>
			<td>Use Sidebar Widgets: (works in 2.3)</td>
			<td><input type="checkbox" value="1" <?php echo $dashboard[sidebar]?> name="dashboard[sidebar]"></td>
		</tr>
		<tr>
			<td>If you would like more than 1 sidebar how many extra would you like?: (name will be 'admin#')</td>
			<td><input type="text" value="<?php echo $dashboard[admin_sidebars]?>" name="dashboard[admin_sidebars]"></td>
		</tr>

		<tr>
			<td colspan="2"><p><strong>New Dashboard Code</strong> Use valid HTML, XHTML or PHP.</p></td>
		</tr>
				<tr>
			<td colspan="2"><p>The following code will be added before the wordpress widget section. (works in 2.3)</p></td>
		</tr>
		<tr>
			<td colspan="2">
<?php

			the_editor(stripslashes($dashboard['dashboard_code']),'dashboard[dashboard_code]');

?>
			</td>
			<tr>
			<td colspan="2"><p>The following code will be added inside the wordpress widget section as a widget.</p></td>
		</tr>

		<tr>
			<td>If you would like more than 1 "widget" how many extra would you like?:</td>
			<td><input type="text" value="<?php echo $dashboard[admin_widgets]?>" name="dashboard[admin_widgets]"></td>
		</tr>
<?php
$count = (int)$dashboard[admin_widgets];
	for($x = 0; $x <= $count; $x++){
?>
		<tr>
			<td colspan="2">
	<?php

				the_editor(stripslashes($dashboard['dashboard_widget_code'.$x]),'dashboard[dashboard_widget_code'.$x.']');

	?>
			</td>
		</tr>
<?php
	}
?>
		</table>
		<input type="hidden" name="action" value="saveconfiguration">
		<input type="submit" value="Save">
	</form>


	<p>
		Have you found this Plugin useful?<br/>
		If this Plugin has helped you, isn't it worth a little bit of time or money? <strong>If you are feeling monetarily generous make a donation</strong>.<br/> <strong>How much is entirely up to you</strong>, but numbers with lots of 0's, a 1 on the left and a decimal point on the right are the best kind. =D
	</p>

	<span style="text-align:center;">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="admin@anthologyoi.com">
			<input type="hidden" name="item_name" value="Donation For Dashboard Editor">
			<input type="hidden" name="no_shipping" value="2">
			<input type="hidden" name="note" value="1">
			<input type="hidden" name="currency_code" value="USD">
			<input type="hidden" name="tax" value="0">
			<input type="hidden" name="lc" value="US">
			<input type="hidden" name="bn" value="PP-DonationsBF">
			<input type="image" src="http://www.paypal.com/en_US/i/btn/x-click-but04.gif" border="0" name="submit" alt="Make a donation with PayPal - it's fast, free and secure!">
			<img alt="" border="0" src="http://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
	</span>
	<p>
		Or if circumstances make a donation impossible, <em>links, refferals and comments are appreciated</em>.
	</p>

	<strong>Quick Doc</strong><br/>

	<p>To add widget support add &lt;?php dynamic_sidebar('admin') ?&gt; to the Dashboard Editor Context box. You can then add widgets just as you would normally.</p>

</div>
<?php

}

function dashboard_update_options($options){
global $dashboard;

	$checkboxes = array('dashboard_primary','dashboard_secondary', 'complete_wipe', 'dashboard_incoming_links', 'started', 'sidebar', 'youare', 'youhave', 'dashboard_recent_comments','dashboard_recent_comments','wp_widgets','wrap','dashboard_plugins','admin_widgets');

	foreach($checkboxes as $name){
		if(!$options[$name]){ $options[$name] = 0; }
	}

	while (list($option, $value) = each($options)) {
			if( get_magic_quotes_gpc() ) {
			$value = stripslashes($value);
			}
		$dashboard[$option] =  $value;
	}
return $dashboard;
}
?>
