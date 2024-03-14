<?php
/** 
 *	Information of Plugin
 */

class ATTMGR_Info {
	/**
	 *	Initialize
	 */
	public static function init() {
		add_action( ATTMGR::PLUGIN_ID.'_plugin_info', array( 'ATTMGR_Info', 'plugin_info') );
		add_action( ATTMGR::PLUGIN_ID.'_latest_info', array( 'ATTMGR_Info', 'latest_info') );
		add_action( ATTMGR::PLUGIN_ID.'_extensions_info', array( 'ATTMGR_Info', 'extensions_info') );
	}

	/** 
	 *	PLUGIN INFO (in PLUGIN OPTION PAGE)
	 */
	public static function plugin_info() {
		global $attmgr;

		require_once( ABSPATH.'wp-admin/includes/plugin.php' );
		$pinfo = get_plugin_data( $attmgr->mypluginfile );
		/*
		Array(
		    [Name] => Attendance Manager
		    [PluginURI] => http://example.com
		    [Version] => 0.2.0
		    [Description] => Each user can edit their attendance schedule by themselves.
		    [Author] => tnomi
		    [AuthorURI] => http://sukimalab.com
		    [TextDomain] => attendance-manager
		    [DomainPath] => /languages/ 
		    [Network] => 
		    [Title] => Attendance Manager
		    [AuthorName] => tnomi
		)*/
		$url = ATTMGR::URL;
		if ( get_locale() != 'ja' ) {
			$url .= 'en/';
		}
	?>
	<div class="postbox">
		<h3><span><?php _e( 'Plugin Information', $pinfo['TextDomain'] ); ?></span></h3>
		<div class="inside">
			<p><?php printf( 'Version: %s', $pinfo['Version'] ); ?></p>
			<p><a href="<?php echo $url; ?>" target="_blank">&raquo; <?php _e( "User's Guide", $pinfo['TextDomain'] ); ?></a></p>
			<p>
				<?php printf( __( 'Thank you for using "%s".', ATTMGR::TEXTDOMAIN ), $pinfo['Name'] ); ?><br />
				<?php _e( 'If wrong processing is found, please let me know.', $pinfo['TextDomain'] ); ?><br />
				<a href="<?php echo $url; ?>" target="_blank">&raquo; <?php _e( "Plugin site", $pinfo['TextDomain'] ); ?></a>
			</p>
			<p><i>
			</i></p>
		</div>
	</div>
	<?php
	}

	/** 
	 *	LATEST INFO (in PLUGIN OPTION PAGE)
	 */
	public static function latest_info() {
	?>
	<div class="postbox">
		<h3><span><?php _e( 'Latest from Plugin', ATTMGR::TEXTDOMAIN ); ?></span></h3>
		<div class="inside">
		<?php
		$url = ATTMGR::URL;
		$feed = fetch_feed( $url );
		if ( !empty( $feed->data ) ) {
			$feed->set_cache_duration( 60*60 );
			$feed->init();
			$param = sprintf( 'title=%s&items=5&show_summary=0&show_author=0&show_date=0', __( 'Latest from Plugin', ATTMGR::TEXTDOMAIN ) );
			@wp_widget_rss_output( $feed, $param );
		}
		?>
		</div>
	</div>
	<?php
	}

	/** 
	 *	EXTENSIONS INFO (in PLUGIN OPTION PAGE)
	 */
	public static function extensions_info() {
		$url = ATTMGR::URL.'extensions/feed';
		$feed = fetch_feed( $url );
		if ( !empty( $feed->data ) ) {
			$feed->set_cache_duration( 60*60 );
			$feed->init();
	?>
	<div class="postbox">
		<h3><span><?php _e( 'Extentions Information', ATTMGR::TEXTDOMAIN ); ?></span></h3>
		<div class="inside">
			<p><a href="<?php echo ATTMGR::URL; ?>extensions/" target="_blank">&raquo; <?php _e( 'Extension codes for ATTMGR', ATTMGR::TEXTDOMAIN ); ?></a></p>
			<hr>
		<?php
			$param = sprintf( 'title=%s&items=5&show_summary=0&show_author=0&show_date=0', __( 'Extentions Information', ATTMGR::TEXTDOMAIN ) );
			@wp_widget_rss_output( $feed, $param );
		?>
		</div>
	</div>
	<?php
		}
	}
}
?>
