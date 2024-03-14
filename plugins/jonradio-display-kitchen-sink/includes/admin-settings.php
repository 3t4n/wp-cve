<?php

defined( 'ABSPATH' ) || exit;

/**
 * Settings page for plugin
 * 
 * Display and Process Settings page for this plugin.
 *
 */
function jr_dks_settings_page() {
	global $jr_dks_plugin_data, $jr_dks_plugin_basename;
	$current_wp_version = get_bloginfo( 'version' );
	
	/*	Display error/update messages
	*/
	settings_errors( 'jr_dks_settings' );
	
	echo '
<div class="wrap">
	<h1>'
	. $jr_dks_plugin_data['Name'];
?></h1>
	<h2>
	Important
	</h2>
	<p>
	Unless you are using a really old version of WordPress, prior to the introduction of the Page/Post Block Editor,
	this plugin will not help you, as the Kitchen Sink does not exist in the new Block Editor.
	</p>
	<p>
	For more recent versions of WordPress, with the Block Editor,
	the Classic Editor plugin automatically displays the Kitchen Sink.
	The <?php
	echo $jr_dks_plugin_data['Name'];
	?> goes one additional step, however,
	by eliminating the Kitchen Sink icon.
	</p>
	<h2>
	Overview
	</h2>
	<p>
	This plugin displays the <b>Kitchen Sink</b>:
	the second row of icons above the Editing Area
	in the Visual Editor for Pages and Posts.
	The Visual Editor is shown on the <b>Visual</b> tab of the Add New Post, Edit Post, Add New Page and Edit Page panels,
	accessible through the Page and Post submenus on the left sidebar of
	<?php
	if ( function_exists( 'is_network_admin' ) && is_network_admin() ) {
		echo "every Site's WordPress Admin panels.</p>";
	} else {
		if ( is_multisite() ) {
			echo "this and every other Site's WordPress Admin panels.</p>";
		} else {
			echo 'this and every other WordPress Admin panel.</p>';
		}
		?>
		<h2>
		Users
		</h2>
		<p>
		<?php
		$editor_disabled = array();
		foreach ( $users = get_users( array( 'fields' => 'all_with_meta' ) ) as $obj ) {
			/*	get_users() returns array[integer user ID] containing an object
				with elements like:  'user_email'=>'e-mailname@domain.com'
				rich_editing is a string with values of "true" or "false"
			*/
			if ( 'false' === $obj->rich_editing ) {
				$editor_disabled[] = $obj->ID;
			}
		}
		echo 'There ' .  sprintf( _n( 'is only one User', 'are %s Users', count( $users ) ), count( $users ) ) . ' registered to access this WordPress site';
		
		if ( 0 === count( $editor_disabled ) ) {
			echo _n( ' and that User has', '. All of them have', count( $users ) );
			echo ' the Visual Editor enabled in their User Profile for this Site, allowing them to see the Kitchen Sink whenever the Visual tab is selected.</p>';
		} else {
			if ( count( $users ) === count( $editor_disabled ) ) {
				echo _n( ', but that one, shown below, has', '. All of them, shown below, have', count( $editor_disabled ) );
			} else {
				printf( _n( '. One of them, shown below, has', '; %s of them, shown below, have', count( $editor_disabled ) ), count( $editor_disabled ) );
			}
			echo ' the Visual Editor disabled in their User Profile for this Site, making it impossible for them to view the Kitchen Sink.</p><table class="widefat"><tbody>';
			$td_style = 'style="text-align: center; vertical-align: middle"';
			$td = "<td $td_style>";
			$head_foot = array( 'head' );
			if ( count( $editor_disabled ) > 9 ) {
				/*	Table is large enough to justify a Footer of Column titles.
				*/
				$head_foot[] = 'foot';
			}
			foreach ( $head_foot as $where ) {
				echo "<t$where><tr>";
				foreach ( array( 'User ID', 'Username', 'Role', 'Display Name', 'User e-mail', 'Edit User' ) as $title ) {
					echo "<th $td_style>$title</th>";
				}
				echo "</tr></t$where>";
			}
			sort( $editor_disabled );
			foreach ( $editor_disabled as $id ) {
				echo '<tr>';
				echo $td;
				echo $id;
				echo '</td>';
				echo $td;
				echo $users[$id]->user_login;
				echo '</td>';
				echo $td;
				$user = new WP_User( $id );
				if ( isset( $user->roles[0] ) ) {
					echo $user->roles[0];
				} else {
					echo 'No Role on this Site';
				}
				echo '</td>';
				echo $td;
				echo $users[$id]->display_name;
				if ( '' != trim( $name = $users[$id]->user_firstname . ' ' . $users[$id]->user_lastname ) ) {
					echo " ($name)";
				}
				echo '</td>';
				echo $td;
				echo $users[$id]->user_email;
				echo '</td>';
				echo $td;
				echo '<a class="button-secondary" href="' . admin_url( "user-edit.php?user_id=$id&wp_http_referer=%2Fwp-admin%2Fusers.php%3Fpage%3Djr_dks_settings" ) . '">Profile</a>';  // &wp_http_referer=%2Fwp-admin%2Fusers.php
				echo '</td></tr>';
			}
			echo '</tbody></table>';
		}
	}
	?>
	<h2>
	Details
	</h2>
	<p>
	When this plugin is activated, all users will see the second row of icons ("The Kitchen Sink") displayed in the Admin panel's Page Edit and Post Edit Visual tab.
	To avoid possible confusion,
	the first row's Kitchen Sink icon 
	(last icon on the right)
	has been removed,
	because it no longer serves a useful function.
	</p>
	<h3>
	Where is the third row of Icons?
	</h3>
	<p>
	The Kitchen Sink is the second row of Icons in the Visual Editor on the Add New and Edit panels for Posts and Pages. 
	If you are looking for additional rows of Icons, you will need to install and activate additional plugins. 
	The <a href="http://wordpress.org/plugins/">WordPress Plugin Directory</a> includes (free of charge) popular editor plugins such as WP Super Edit, Ultimate TinyMCE and TinyMCE Advanced.
	</p>
	<?php
	if ( is_multisite() ) {
	?>
		<h3>
		In a WordPress Network (Multisite) installation, 
		how do I force Kitchen Sink on only some sites?
		</h3>
		<p>
		Do not Network Activate this plugin. Instead, Activate it on each site individually, using the Admin panel for each site, not the Network Admin panel.
		</p>
	<?php
	}
	?>
	<h2>
	Troubleshooting
	</h2>
	<p>
	Here are some ideas that might help you figure out what things are not working as you expect them to, 
	when you use this plugin with WordPress.
	</p>
	<h3>
	First Place to Check
	</h3>
	<p>
	Be sure that the Visual Editor is not disabled for the User experiencing the problem of not seeing the "Kitchen Sink".
	</p>
	<p>
	For example, if you are the User experiencing the problem: in the WordPress Admin panels, go to Users then Your Profile in the Users submenu. 
	The first setting is "Disable the visual editor when writing". 
	The Checkbox must be Empty (no checkmark), then hit the Update Profile button at the very bottom of the panel.
	</p>
	<h3>
	Check the Basics
	</h3>
	<p>
	In the WordPress Admin panels, you should be in the Posts or Pages submenu, on an Add New or Edit Page/Post panel. Please verify:
	<ol>
	<li>
    You see the Visual and Text tabs
	</li>
	<li>
    That the word "Visual" is black and "Text" is grey
	</li>
	<li>
    If the Kitchen Sink plugin is working, you should see two rows of icons: 
	Row 1 begins with a "B", 
	Row 2 begins with a drop-down box with Paragraph, Heading or something similar.
	</li>
	<li>
    If Kitchen Sink is turned off, you will only see one row.
	</li>
	</ol>
	If the last point (see only one row) is your situation, 
	then go to the Installed Plugins page of the WordPress Admin panels.
	Be sure that the <b><?php
	echo $jr_dks_plugin_data['Name']
		. '</b> plugin has been installed and activated.';
	if ( is_multisite() ) {
		?>
		Because you are in a WordPress Network ("Multisite"),
		you may have to look at both the Network Admin's Installed Plugins panel
		and the Installed Plugins panel for the individual site you are investigating.
		<?php
	}
	echo '</p><hr /><h2>System Information</h2><p>You are currently running:<ul>';
	echo "<li> &raquo; The {$jr_dks_plugin_data['Name']} plugin Version {$jr_dks_plugin_data['Version']}</li>";
	if ( is_multisite() ) {
		if ( is_plugin_active_for_network( $jr_dks_plugin_basename ) ) {
			echo '<li> &nbsp; &raquo;&raquo; The Plugin is Network Activated, meaning that it is available (activated) on all Sites within this WordPress Network';
		} else {
			/*	wp_get_sites() did not exist before WordPress Version 3.7
			*/
			if ( version_compare( get_bloginfo( 'version' ), '3.7.0', '>=' ) ) {
				/*	"No Network Too Large"
					Always return FALSE.
				*/
				add_filter( 'wp_is_large_network', '__return_false' );
				$sites_arrays = wp_get_sites( array( 'limit' => NULL ) );
				$current_blog_id = get_current_blog_id();
				$sites = array();
				foreach ( $sites_arrays as $site_array ) {
					/*	!= not !== because get_current_blog_id() returns an Integer
						and wp_get_sites() a String
					*/
					if ( $site_array['blog_id'] != $current_blog_id ) {
						if ( switch_to_blog( $site_array['blog_id'] ) ) {
							if ( is_plugin_active( $jr_dks_plugin_basename ) ) {
								$sites[] = $site_array['domain'] . $site_array['path'];
							}
						}
					}
				}
				switch_to_blog( $current_blog_id );
				if ( empty( $sites ) ) {
					echo '<li> &nbsp; &raquo;&raquo; The Plugin is not available (activated) on other Sites on this WordPress Network</li>';
				} else {
					$num_sites = count( $sites );
					if ( $num_sites > 25 ) {
						echo "<li> &nbsp; &raquo;&raquo; The Plugin is also available (activated) on $num_sites other Sites on this WordPress Network</li>";
					} else {
						echo '<li> &nbsp; &raquo;&raquo; The Plugin is also available (activated) on other Sites on this WordPress Network:</li>';
						foreach ( $sites as $url ) {
							echo "<li> &nbsp; &nbsp; &raquo;&raquo;&raquo; $url</li>";
						}
					}
				}
			}
		}
	}
	echo "<li> &nbsp; &raquo;&raquo; The Path to the plugin's directory is " . rtrim( jr_dks_path(), '/' ) . '</li>';
	if ( is_multisite() && ( !( function_exists( 'is_network_admin' ) && is_network_admin() ) ) && ( 1 !== get_current_blog_id() ) ) {
	} else {
		echo "<li> &nbsp; &raquo;&raquo; The URL to the plugin's directory is " . plugins_url() . "/{$jr_dks_plugin_data['slug']}</li>";
	}
	echo "<li> &raquo; WordPress Version $current_wp_version</li>";
	if ( is_multisite() ) {
		echo '<li> &nbsp; &raquo;&raquo; WordPress is being run as a Network, i.e. - a single installation of WordPress capable of providing multiple individual WordPress Sites (Multisite)</li>';
	} else {
		echo '<li> &nbsp; &raquo;&raquo; WordPress is being run as a Single Site, i.e. - not a Network/Multisite</li>';
	}
	echo '<li> &nbsp; &raquo;&raquo; WordPress language is set to ' , get_bloginfo( 'language' ) . '</li>';
	echo '<li> &raquo; ' . php_uname( 's' ) . ' operating system, Release/Version ' . php_uname( 'r' ) . ' / ' . php_uname( 'v' ) . '</li>';
	echo '<li> &raquo; ' . php_uname( 'm' ) . ' computer hardware</li>';
	echo '<li> &raquo; Host name ' . php_uname( 'n' ) . '</li>';
	echo '<li> &raquo; php Version ' . phpversion() . '</li>';
	echo '<li> &nbsp; &raquo;&raquo; php memory_limit ' . ini_get('memory_limit') . '</li>';
	echo '<li> &raquo; Zend engine Version ' . zend_version() . '</li>';
	echo '<li> &raquo; Web Server software is ' . getenv( 'SERVER_SOFTWARE' ) . '</li>';
	if ( function_exists( 'apache_get_version' ) && ( FALSE !== $apache = apache_get_version() ) ) {
		echo "<li> &nbsp; &raquo;&raquo; Apache Version $apache</li>";
	}
	echo '
	</ul>
</div>';
}

?>