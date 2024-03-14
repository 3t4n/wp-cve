<?php
/*
	Routine Name: WP247 Extension Notification System Client Co-Requisite Notice
	Version: 1.0
	Description: Provides notice that WP247 Extension Notification System Client is required to send extension notices
	Author: wp247
	Author URI: http://wp247.net/
*/

// Don't allow direct execution
defined( 'ABSPATH' ) or die( 'Forbidden' );

if ( !class_exists( 'WP247XNS_Client_Corequisite_Notice' ) )
{
	/**
	 * WP247XNS_Client_Corequisite_Notice Class
	 *
	 * Provides all.
	 *
	 * @return void
	 */
	class WP247XNS_Client_Corequisite_Notice
	{
		/**
		 * Option Name Prefix (appended with the extension's slug)
		 */
		private static $option_prefix	= 'wp247xns-client-corequisite-notice-';

		/**
		 * AJAX Nonce for dismiss requests
		 */
		private static $ajax_nonce		= null;

		/**
		 * Extension Name
		 */
		private $extension_name			= null;

		/**
		 * Extension Slug
		 */
		private $extension_slug			= null;

		/**
		 * Nag Frequency
		 */
		private $nag_frequency			= null;

		/**
		 * Message to show
		 */
		private $message_html			= null;

		/**
		 * Text Domain
		 */
		private $text_domain			= null;

		/**
		 * Class constructor
		 *
		 * Prepare each instance for use - should only happen once.
		 *
		 * @return void
		 */
		function __construct( $extension_name, $nag_frequency = '30 days', $text_domain = 'wp247xns-client-corequisite-notice' )
		{
			$this->extension_name	= $extension_name;
			$this->extension_slug	= sanitize_title_with_dashes( $extension_name );
			$this->text_domain		= $text_domain;
			$this->nag_frequency	= strtotime( $nag_frequency, 0 );
			if ( false == $this->nag_frequency
			  or $this->nag_frequency > 31622400	/* 1 year */
			) $this->nag_frequency = 2592000;		// 30 days

			if ( !is_admin() or !current_user_can( 'manage_options' ) ) return;

			$option = get_option( self::$option_prefix . $this->extension_slug, array( 'dismissed' => false, 'dismiss-date' => date( 'Y-m-d' ) ) );
			if ( $option[ 'dismissed' ] and $option[ 'dismiss-date' ] < date( 'Y-m-d', time() - $this->nag_frequency ) )
			{
				$option[ 'dismissed' ] = false;
				update_option( self::$option_prefix . $this->extension_slug, $option );
			}
			if ( !$option[ 'dismissed' ] )
			{
				add_action( 'admin_notices', array( $this, 'do_action_admin_notices' ) );
			}

		} // function __construct

		/**
		 * Enqueue Admin Scripts
		 *
		 * @return void
		 */
		static function do_action_admin_enqueue_scripts()
		{
			wp_enqueue_script( 'wp247xns-client-corequisite-notice-script', plugins_url( 'wp247xns-client-corequisite-notice.js', __FILE__ ), array( 'jquery', 'thickbox' ) );
		}

		/**
		 * Output ajax nonce
		 *
		 * @return void
		 */
		static function do_action_admin_head()
		{
			if ( is_null( self::$ajax_nonce ) ) :
				self::$ajax_nonce = wp_create_nonce( 'wp247xns_client_corequisite_notice_nonce' );
?>
	<script type="text/javascript">var wp247xns_client_corequisite_notice_dismiss_ajax_nonce = '<?php echo self::$ajax_nonce; ?>';</script>
<?php
			endif;
		}

		/**
		 * Output Co-requisite Notice
		 *
		 * @return void
		 */
		function do_action_admin_notices()
		{
			if ( !empty( $this->get_message() ) ) :
				add_thickbox();
?>
	<div class="wp247xns-client-corequisite-notice notice notice-warning is-dismissible" data-xid="<?php echo $this->extension_slug;?>">
<?php echo $this->get_message(); ?>
		<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
	</div>
<?php
			endif;
		}


		/**
		 * Dismiss Co-requisite Notice
		 *
		 * @return string
		 */
		private function get_message()
		{
			if ( is_null( $this->message_html ) )
			{
				$this->message_html = '';
				if ( !defined( 'WP247XNS_CLIENT_PLUGIN_ID' ) )
				{
					$this->message_html .= '<p style="max-width: 910px;">' . sprintf( __( 'The <strong>%s</strong> plugin uses the <strong>WP247 Extension Notification Client</strong> plugin to periodically communicate important information. Don\'t miss out. Please install the <strong>WP247 Extension Notification Client</strong> plugin from the WordPress Plugin Directory so we can notify you of important changes as they arise.', $this->text_domain ), $this->extension_name ) . '</p>';
					$this->message_html .= '<p><a class="open-plugin-details-modal" href="/wp-admin/plugin-install.php?s=wp247-extension-notification-client&tab=search&type=term"><span class="dashicons dashicons-admin-plugins" style="text-decoration: none; padding-right: 5px;"></span>' . __( 'Install <strong>WP247 Extension Notification Client</strong> plugin', $this->text_domain ) . ' </a></p>';
//					$this->message_html .= '<p><a class="thickbox open-plugin-details-modal" href="/wp-admin/plugin-install.php?s=wp247-extension-notification-client&tab=search&type=term&TB_iframe=true"><span class="dashicons dashicons-admin-plugins" style="text-decoration: none; padding-right: 5px;"></span>' . __( 'Install <strong>WP247 Extension Notification Client</strong> plugin', $this->text_domain ) . ' </a></p>';
//					$this->message_html .= '<p><a class="thickbox open-plugin-details-modal" href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=wp247-extension-notification-client&TB_iframe=true&width=600&height=800"><span class="dashicons dashicons-admin-plugins" style="text-decoration: none; padding-right: 5px;"></span>' . __( 'Install <strong>WP247 Extension Notification Client</strong> plugin', $this->text_domain ) . ' </a></p>';
				}
				else if ( function_exists( 'wp247xns_client_is_extension_enabled' )
				 and !wp247xns_client_is_extension_enabled( $this->extension_slug )
				)
				{
					$this->message_html .= '<p style="max-width: 910px;">' . sprintf( __( 'The <strong>%s</strong> plugin uses the <strong>WP247 Extension Notification Client</strong> plugin to periodically communicate important information. Don\'t miss out. Please enable extension notifications for the <strong>%1$s</strong> plugin in the <strong>WP247 Extension Notification Client</strong> plugin\'s Settings area at %2$s<i>wp&#8209;admin&nbsp;>&nbsp;Settings&nbsp;>&nbsp;Extension&nbsp;Notifications</i>%3$s.', $this->text_domain ), $this->extension_name, '<a href="/wp-admin/options-general.php?page=wp247xns_client_options">', '</a>' ).'</p>';
				}
			}
			return $this->message_html;
		}

		/**
		 * Dismiss Co-requisite Notice
		 *
		 * @return void
		 */
		static function do_action_wp247xns_client_corequisite_notice_dismiss()
		{
			check_ajax_referer( 'wp247xns_client_corequisite_notice_nonce', 'security' );
			if ( !current_user_can( 'manage_options' ) ) wp_die();
			if ( !isset( $_POST[ 'xid' ] ) ) wp_die();
			$xid = sanitize_title_with_dashes( $_POST[ 'xid' ] );
			if ( $xid != $_POST[ 'xid' ] ) wp_die();
			update_option( self::$option_prefix . $xid, array( 'dismissed' => true, 'dismiss-date' => date( 'Y-m-d' ) ) );
			wp_die();
		}

	} // class WP247XNS_Client_Corequisite_Notice

	add_action( 'admin_enqueue_scripts', array( 'WP247XNS_Client_Corequisite_Notice', 'do_action_admin_enqueue_scripts' ) );
	add_action( 'admin_head', array( 'WP247XNS_Client_Corequisite_Notice', 'do_action_admin_head' ), 9999 );
	add_action( 'wp_ajax_wp247xns_client_corequisite_notice_dismiss', array( 'WP247XNS_Client_Corequisite_Notice', 'do_action_wp247xns_client_corequisite_notice_dismiss' ) );

} // if ( !class_exists( 'WP247XNS_Client_Corequisite_Notice' ) )