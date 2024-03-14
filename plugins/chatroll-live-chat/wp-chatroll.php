<?php
/**
 * Plugin Name: Chatroll Live Chat
 * Plugin URI: https://chatroll.com
 * Description: Chatroll lets you easily add live chat to your WordPress sidebar, posts and pages. Chatroll can be fully customized to match your site's design. Supports WordPress profiles and optional Facebook and Twitter login. To get started, go to Chatroll.com to create a chat and follow the WordPress embed instructions.
 * Version: 2.4.0
 * Author: Chatroll
 * Author URI: https://chatroll.com
 * Text Domain: wp-chatroll
 */

/*  
	Copyright 2010-2020  Chatroll (email : support@chatroll.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
require_once('chatroll.php');

class wpChatrollException extends Exception {}

/**
 * WP_Chatroll is the class that handles the main widget.
 */
class WP_Chatroll extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_chatroll',
			'description' => __( 'Chatroll live chat', 'wp-chatroll' )
		);
		$control_ops = array(
			'id_base' => 'chatroll'
		);
		$name = __( 'Chatroll', 'wp-chatroll' );

		parent::__construct('chatroll', $name, $widget_ops, $control_ops);
	}

	private function _getInstanceSettings ( $instance ) {
		$defaultArgs = array(	'title'		=> '',
					'errmsg'	=> '',
					'shortcode'	=> ''
		);

		return wp_parse_args( $instance, $defaultArgs );
	}

	public function form( $instance ) {
		$instance = $this->_getInstanceSettings( $instance );
		$wpChatroll = wpChatroll::getInstance();
?>

			<p>
				<label for="<?php echo $this->get_field_id('shortcode'); ?>"><?php _e('<b>Shortcode</b> (Sign in to <a href="https://chatroll.com/" target="_blank">Chatroll</a> to create and manage Chatroll chats for your WordPress site. To get your shortcode, go to the "Embed Code" tab on your Chatroll chat dashboard and choose the "WordPress Self-Hosted" instructions:', 'wp-chatroll'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('shortcode'); ?>" name="<?php echo $this->get_field_name('shortcode'); ?>" type="text" value="<?php esc_attr_e($instance['shortcode']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('<b>Title</b> (optional):', 'wp-chatroll'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php esc_attr_e($instance['title']); ?>" />
			</p>
			<p>
				<i>To make your Chatroll bigger or smaller, change 'width' and 'height' values in the Shortcode above.</i>
			</p>
            		<p>Sign in to <a href="https://chatroll.com/" target="_blank">Chatroll</a> to manage your Chatroll chats. Available settings include:</p>
			<ul style='list-style-type:disc;margin-left:20px;'>
			<li><b>Customization</b> &ndash; Change colors, layout and sound</li>
			<li><b>Moderation Tools</b> &ndash; Manage privacy, users and content</li>
			<li><b>WordPress Profile Integration</b> &ndash; Let your visitors sign into the chat automatically using their WordPress login. Go to the <b>Settings &rarr; Access</b> tab and enable the Single Sign-On (SSO) feature.</li>
			</ul>
			<p style='padding-top:15px;'>Need Help? <?php echo $wpChatroll->getContactSupportLink(); ?></p>
<?php
		return;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $this->_getInstanceSettings( $new_instance );

		// Clean up the free-form areas
		$instance['title'] = stripslashes($new_instance['title']);
		$instance['errmsg'] = stripslashes($new_instance['errmsg']);

		// If the current user isn't allowed to use unfiltered HTML, filter it
		if ( !current_user_can('unfiltered_html') ) {
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['errmsg'] = strip_tags($new_instance['errmsg']);
		}

		return $instance;
	}

	public function flush_widget_cache() {
		wp_cache_delete('widget_chatroll', 'widget');
	}

	public function widget( $args, $instance ) {
		$instance = $this->_getInstanceSettings( $instance );
		$wpChatroll = wpChatroll::getInstance();

		echo $wpChatroll->displayWidget( wp_parse_args( $instance, $args ) );
	}
}


/**
 * wpChatroll is the class that handles everything outside the widget.
 */
class wpChatroll extends Chatroll
{
	/**
	 * Static property to hold our singleton instance
	 */
	static $instance = false;

	/**
	 * @var array Plugin settings
	 */
	private $_settings;

	/**
	 * This is our constructor, which is private to force the use of getInstance()
	 * @return void
	 */
	private function __construct() {
		/**
		 * Add filters and actions
		 */
		add_filter( 'init', array( $this, 'init_locale' ) );
		add_action( 'widgets_init', array( $this, 'register' ) );
		add_filter( 'plugin_action_links', array( $this, 'addPluginPageLinks' ), 10, 2 );
		add_action ( 'in_plugin_update_message-'.plugin_basename ( __FILE__ ) , array ( $this , '_changelog' ), null, 2 );
		add_shortcode( 'chatroll', array( $this, 'handleShortcode' ) );
	}

	/**
	 * Function to instantiate our class and make it a singleton
	 */
	public static function getInstance() {
		if ( !self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function init_locale() {
		$lang_dir = basename(dirname(__FILE__)) . '/languages';
		load_plugin_textdomain('wp-chatroll', 'wp-content/plugins/' . $lang_dir, $lang_dir);
	}

	public function _changelog ($pluginData, $newPluginData) {
		require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

		$plugin = plugins_api( 'plugin_information', array( 'slug' => $newPluginData->slug ) );

		if ( !$plugin || is_wp_error( $plugin ) || empty( $plugin->sections['changelog'] ) ) {
			return;
		}

		$changes = $plugin->sections['changelog'];

		$pos = strpos( $changes, '<h4>' . $pluginData['Version'] );
		$changes = trim( substr( $changes, 0, $pos ) );
		$replace = array(
			'<ul>'	=> '<ul style="list-style: disc inside; padding-left: 15px; font-weight: normal;">',
			'<h4>'	=> '<h4 style="margin-bottom:0;">',
		);
		echo str_replace( array_keys($replace), $replace, $changes );
	}

	public function addPluginPageLinks( $links, $file ){
		if ( $file == plugin_basename(__FILE__) ) {
			// Create Chatroll link
			$link = '<a href="https://chatroll.com/" target="_blank">' . __('Chatroll Dashboard', 'wp-chatroll') . '</a>';
			array_push( $links, $link );

			// Add Support link to our plugin
			$link = $this->getContactSupportLink();
			array_push( $links, $link );
		}
		return $links;
	}

	public function getContactSupportLink() {
		return '<a href="https://chatroll.com/help/support?r=wordpress-org" target="_blank">' . __('Contact Support', 'wp-chatroll') . '</a>';
	}

	public function register() {
		register_widget('WP_Chatroll');
	}

	public function displayWidget( $args ) {
		$args = wp_parse_args( $args );

		$widgetContent = $args['before_widget'];

		// Widget title, if specified
		if (!empty($args['title'])) {
			$widgetContent .= $args['before_title'] . $args['title'] . $args['after_title'];
		}

		// Use the shortcode to generate iframe
		$widgetContent .= do_shortcode($args['shortcode']);

		$widgetContent .= $args['after_widget'];

		return $widgetContent;
	}

	/**
	 * OVERRIDE Chatroll::appendPlatformDefaultAttr()
	 *  Set user parameters for SSO integration
	 */
	public function appendPlatformDefaultAttr($attr) {
		$attr['platform'] = 'wordpress-org';

		// Generate SSO attributes that were not specified
		$current_user = wp_get_current_user();

		if (empty($attr['uid'])) {
			$attr['uid'] = $current_user->ID;
		}
		if (empty($attr['uname'])) {
			// Get the display name to populate the Chatroll 'uname' parameter.
			// The method to retrieve the display name depends on what plugins are active.
			// To request support for additional plugins, please contact support@chatroll.com.
			$display_name = '';
			if (function_exists('um_get_display_name')) {
				// Ultimate Member plugin is active
				$display_name = um_get_display_name( $current_user->ID );
			}
			if (empty($display_name)) {
				$display_name = $current_user->display_name;
			}
			$attr['uname'] = $display_name;
		}
		if (empty($attr['upic'])) {
			// Get the avatar URL to populate the Chatroll 'upic' parameter.
			// The method to retrieve the avatar URL depends on which plugins are active.
			// To request support for additional plugins, please contact support@chatroll.com.
			$avatar_url = '';

			// WP User Avatar
			if ($avatar_url == '' && function_exists('get_wp_user_avatar_src')) {
				$avatar_url = get_wp_user_avatar_src($current_user->ID, 96);
			}

			// WordPress Social Login
			if ($avatar_url == '' && function_exists('wsl_get_user_custom_avatar')) {
				$avatar_url = wsl_get_user_custom_avatar($current_user->ID);
			}

			// Ultimate Member
			if ($avatar_url == '' && function_exists('um_get_avatar_uri')) {
				if ( um_profile('profile_photo') ) {
					$avatar_url = um_get_avatar_uri( um_profile('profile_photo'), 96 );
				} else {
					$avatar_url = um_get_default_avatar_uri();
				}
			}

			// BuddyPress
			if ($avatar_url == '' && function_exists('bp_core_fetch_avatar')) {
				$avatar_url = bp_core_fetch_avatar(array(
					'html' => false,
					'no_grav' => true,
					'item_id' => $current_user->ID,
					'type' => 'thumb',
					'width' => 96,
					'height' => 96));
			}

			// WordPress >= 4.2.0
			if ($avatar_url == '' && function_exists('get_avatar_url')) {
				$avatar_url = get_avatar_url($current_user->ID, 96);
			}

			// WordPress < 4.2.0
			if ($avatar_url == '' && function_exists('get_avatar')) {
				// Set the picture using 'get_avatar' (available in WordPress 2.5 and up)
				$avatar = get_avatar($current_user->ID, 96);
				$avatar_url = preg_replace("/.*src='([^']*)'.*/", "$1", $avatar);
				if (strlen($avatar_url) > 0) {
					if ($avatar_url[0] == '/') {
						// Turn relative image URIs into absolute image URLs.
						$url = get_bloginfo('url');
						$domain = preg_replace("/^(http[s]?:\/\/[^\/]+).*/", "$1", $url);
						$avatar_url = $domain . $avatar_url;
					}
                    // The gravatar image URL is extracted from an image tag and ampersands (&) are escaped to &amp;
                    // Thus we need to un-escape the specialchars. (e.g. &amp; -> &)
					$avatar_url = htmlspecialchars_decode($avatar_url);
				}
			}

			// DEBUG
			/*
			print "<pre>";
			print "\nAvatar URL: ";
			print_r($avatar_url);
			print "</pre>";
			*/

			// Set the 'upic' parameter:
	        // Chatroll uses the URL to download the avatar, so this must be an absolute, publicly accessible URL.
			$attr['upic'] = $avatar_url;
		}
		if (empty($attr['ulink'])) {
			$attr['ulink'] = $current_user->user_url;
		}
		if (empty($attr['ismod'])) {
			// By default, if the user can moderate comments, they can moderate the chat
			$attr['ismod'] = current_user_can('moderate_comments') ? '1' : '0';
		}
		return $attr;
	}

	/**
	 * Replace our shortCode with the Chatroll iframe
	 *
	 * @param array $attr - array of attributes from the shortCode
	 * @param string $content - Content of the shortCode
	 * @return string - formatted XHTML replacement for the shortCode
	 */
	public function handleShortcode($attr, $content = '') {
		return $this->renderChatrollHtml($attr);
	}
}

/**
 * Instantiate our class
 */
$wpChatroll = wpChatroll::getInstance();
?>
