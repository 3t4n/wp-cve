<?php
/*
Plugin Name: Add Any Extension to Pages
Version: 1.5
Plugin URI: http://infolific.com/technology/software-worth-using/add-any-extension-to-pages/
Description: Add any extension of your choosing (e.g. .html, .htm, .jsp, .aspx, .cfm) to WordPress pages.
Author: Marios Alexandrou
Author URI: http://infolific.com/technology/
License: GPLv2 or later
Text Domain: add-any-extension-to-pages
*/

/*
Copyright 2015 Marios Alexandrou

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

//Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

function aaetp_activate() {
	global $wp_rewrite;

    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
	}
	
    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
    check_admin_referer( "activate-plugin_{$plugin}" );

	$aaetp_settings = get_option( 'aaetp_plugin_settings' );
	if ( isset ( $aaetp_settings['aaetp_extension'] ) && strlen( trim( $aaetp_settings['aaetp_extension'] ) ) > 0 ) {
		$aaetp_extension = $aaetp_settings['aaetp_extension'];
		if ( !strpos( $wp_rewrite->get_page_permastruct(), $aaetp_extension ) ) {
			$wp_rewrite->page_structure = $wp_rewrite->page_structure . $aaetp_extension;
			$wp_rewrite->flush_rules();
		}
	}
}	
register_activation_hook( __FILE__, 'aaetp_activate' );

function aaetp_deactivate() {
	global $wp_rewrite;

	if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
	}
	
    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
    check_admin_referer( "deactivate-plugin_{$plugin}" );

	$aaetp_settings = get_option( 'aaetp_plugin_settings' );
	if ( isset ( $aaetp_settings['aaetp_extension'] ) && strlen( trim( $aaetp_settings['aaetp_extension'] ) ) > 0 ) {
		$aaetp_extension = $aaetp_settings['aaetp_extension'];
		$wp_rewrite->page_structure = str_replace( $aaetp_extension, '', $wp_rewrite->page_structure );
		$wp_rewrite->flush_rules();
	}
}
register_deactivation_hook( __FILE__, 'aaetp_deactivate' );

/*
function aaetp_uninstall()
{
    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
	}
	
    check_admin_referer( 'add-any-extension-to-pages' );

    if ( __FILE__ != WP_UNINSTALL_PLUGIN ) {
        return;
	}

	exit( var_dump( $_GET ) );
}
register_uninstall_hook(__FILE__, 'aaetp_uninstall' );
*/

function aaetp_permalink_with_extension() {
	global $wp_rewrite;

	$aaetp_settings = get_option( 'aaetp_plugin_settings' );
	if ( isset ( $aaetp_settings['aaetp_extension'] ) && strlen( trim( $aaetp_settings['aaetp_extension'] ) ) > 0 ) {
		$aaetp_extension = $aaetp_settings['aaetp_extension'];
		if ( !strpos( $wp_rewrite->get_page_permastruct(), $aaetp_extension ) ) {
			$wp_rewrite->page_structure = $wp_rewrite->page_structure . $aaetp_extension;
		}
	}
	
}
add_action( 'init', 'aaetp_permalink_with_extension', -1 );

function aaetp_no_page_trailing_slash($string, $type) {
	global $wp_rewrite;

	if ( $wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes == true && $type == 'page' ){
		return untrailingslashit( $string );
	} else {
		return $string;
	}
}
add_filter( 'user_trailingslashit', 'aaetp_no_page_trailing_slash', 66, 2 );


function aaetp_plugin_meta( $links, $file ) { // add some links to plugin meta row
	if ( strpos( $file, 'add-any-extension-to-pages.php' ) !== false ) {
		$links = array_merge( $links, array( '<a href="' . esc_url( get_admin_url(null, 'options-general.php?page=add-any-extension-to-pages') ) . '">Settings</a>' ) );
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'aaetp_plugin_meta', 10, 2 );

/*
* Add a submenu under Tools
*/
function aaetp_add_pages() {
	$page = add_submenu_page( 'options-general.php', 'Add Any Extension to Pages', 'Add Any Extension to Pages', 'activate_plugins', 'add-any-extension-to-pages', 'aaetp_options_page' );
	add_action( "admin_print_scripts-$page", "aaetp_admin_scripts" );
}
add_action( 'admin_menu', 'aaetp_add_pages' );

function aaetp_admin_scripts() {
	wp_enqueue_style( 'aaetp_styles', plugins_url() . '/add-any-extension-to-pages/css/aaetp.css' );
}

function aaetp_options_page() {
	global $wp_rewrite;

	if ( isset( $_POST['setup-update'] ) ) {	
		check_admin_referer( 'aaetp_settings_form' );
		unset( $_POST['setup-update'] );
		update_option( 'aaetp_plugin_settings', $_POST );
		echo '<div id="message" class="updated fade">';
			echo '<p><strong>Options Updated</strong></p>';
		echo '</div>';
	}
?>
<div class="wrap" style="padding-bottom: 5em;">
	<h2>Add Any Extension to Pages</h2>
	<p>Type in an extension starting with a period (.) and save.</p>
	<p>Currently your permalink structure, including any extension you've specified, is <strong><?php echo $wp_rewrite->permalink_structure; ?></strong>. Make sure the extension below matches the extension on the Permalinks page.</p>
	<div id="aaetp-items">
		<form method="post" action="<?php echo esc_url( $_SERVER["REQUEST_URI"] ); ?>">
			<?php wp_nonce_field( 'aaetp_settings_form' ); ?>
			<ul id="aaetp_itemlist">
				<li>
				<?php
				$aaetp_settings = get_option( 'aaetp_plugin_settings' );

				if ( isset ( $aaetp_settings['aaetp_extension'] ) && strlen( trim( $aaetp_settings['aaetp_extension'] ) ) > 0 ) {
					$aaetp_extension = $aaetp_settings['aaetp_extension'];
					
					echo "<div>";
						echo "<label class='side-label' for='aaetp_extension'>Extension:</label>";
						echo "<input class='textbox' type='text' name='aaetp_extension' id='aaetp_extension' value='$aaetp_extension' />";
						echo "<br />";
					echo "</div>";

				} else {

					echo "<div>";
						echo "<label class='side-label' for='aaetp_extension'>Extension:</label>";
						echo "<input class='textbox' type='text' name='aaetp_extension' id='aaetp_extension' />";
						echo "<br />";
					echo "</div>";

				}
				?>
				</li>
			</ul>
			<div id="divTxt"></div>
		    <div class="clearpad"></div>
			<input type="submit" class="button left" value="Update Settings" />
			<input type="hidden" name="setup-update" />
		</form>
	</div>

	<div id="aaetp-sb">
		<div class="postbox" id="aaetp-sbone">
			<h3 class='hndle'><span>Documentation</span></h3>
			<div class="inside">
				<strong>Instructions</strong>
				<p>This plugin allows you to specify an extension for pages. </p>
				<ol>
					<li>Make sure your permalinks are already set to use an extension.</li>
					<li>Type in the extension you would like to use e.g. .html, .htm, .jsp, or any other.</li>
					<li>Save. Test. You're done!</li>
				</ol>
			</div>
		</div>
		<div class="postbox"  id="aaetp-sbtwo">
			<h3 class='hndle'><span>Support</span></h3>
			<div class="inside">
				<p>Your best bet is to post on the <a href="https://wordpress.org/plugins/add-any-extension-to-pages/">plugin support page</a>.</p>
				<p>Please consider supporting me by <a href="https://wordpress.org/support/view/plugin-reviews/add-any-extension-to-pages">rating this plugin</a>. Thanks!</p>
			</div>
		</div>
		<div class="postbox" id="aaetp-sbthree">
			<h3 class='hndle'><span>Other Plugins</span></h3>
			<div class="inside">
				<ul>
					<li><a href="https://wordpress.org/plugins/real-time-find-and-replace/">Real-Time Find and Replace</a>: Set up find and replace rules that are executed AFTER a page is generated by WordPress, but BEFORE it is sent to a user's browser.</li>
					<li><a href="https://wordpress.org/plugins/republish-old-posts/">Republish Old Posts</a>: Republish old posts automatically by resetting the date to the current date. Puts your evergreen posts back in front of your users.</li>
					<li><a href="https://wordpress.org/extend/plugins/rss-includes-pages/">RSS Includes Pages</a>: Modifies RSS feeds so that they include pages and not just posts. My most popular plugin!</li>
					<li><a href="https://wordpress.org/extend/plugins/enhanced-plugin-admin">Enhanced Plugin Admin</a>: At-a-glance info (rating, review count, last update date) on your site's plugin page about the plugins you have installed (both active and inactive).</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php } ?>