<?php
/** 
 *	Information of Plugin
 */

class YESNO_Info {

	/**
	 *	Get feed
	 */
	public static function get_feed() {
		return fetch_feed( YESNO::FEED_URL );
	}

	/** 
	 *	PLUGIN INFO (in PLUGIN OPTION PAGE)
	 */
	public static function plugin_info() {
		global $yesno;

		require_once( ABSPATH.'wp-admin/includes/plugin.php' );
		$pinfo = get_plugin_data( $yesno->mypluginfile );
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
		$url = $pinfo['PluginURI'];
	?>
	<div class="postbox">
		<h3><span><?php _e( 'Plugin Information', $pinfo['TextDomain'] ); ?></span></h3>
		<div class="inside">
			<p><?php printf( 'Version: %s', esc_html( $pinfo['Version'] ) ); ?></p>
			<p><a href="<?php echo esc_url( $url ); ?>" target="_blank">&raquo; <?php _e( "User's Guide", $pinfo['TextDomain'] ); ?></a></p>
			<p>
				<?php printf( __( 'Thank you for using "%s".', 'yesno' ), esc_html( $pinfo['Name'] ) ); ?><br />
				<?php _e( 'If wrong processing is found, please let me know.', $pinfo['TextDomain'] ); ?><br />
			</p>
			<p><i>
			</i></p>
		</div>
	</div>
	<?php
	}

	/** 
	 *	UPDATE INFO (in PLUGIN OPTION PAGE)
	 */
	public static function update_info() {
	?>
	<div class="postbox">
		<h3><span><?php _e( 'Latest from Plugin', 'yesno' ); ?></span></h3>
		<div class="inside">
		<?php
		$url = YESNO::FEED_URL;
		$feed = fetch_feed( $url );
		if ( !empty( $feed->data ) ) {
			$feed->set_cache_duration( 60*30 );
			$feed->init();
			$param = sprintf( 'title=%s&items=5&show_summary=0&show_author=0&show_date=0', __( 'Latest from Plugin', 'yesno' ) );
			@wp_widget_rss_output( $feed, $param );
		}
		?>
		</div>
	</div>
	<?php
	}
}
?>
