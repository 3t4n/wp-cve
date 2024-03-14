<?php

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	die("Protected By WebTotem!");
}

/**
 * Agent Manager library.
 *
 * Agent Manager is needed to create AM file,
 *   and to check whether am and other agents are installed.
 * The AM file creates WAV and AV files,
 *   and transmits information to the Web Totem platform and back.
 */
class WebTotemAgentManager extends WebTotem {

	/**
	 * AM file install.
	 *
	 * Create AM file in the root of the site
	 *   and save the data about it in the DB.
	 *
	 * @return bool
	 *   TRUE if the AM file is successfully added.
	 */
	public static function amInstall() {
		try {
			self::removeAgents();

			$host  = WebTotemAPI::siteInfo();

			$files = self::getAgentsFiles( $host['id'] );

			if ( $files['amFilename'] ) {

				if (!is_writable(ABSPATH)) {
					WebTotemOption::setNotification('error', __('There are no permissions to write the file to the root directory', 'wtotem'));
					return FALSE;
				}

				// Download file.
				$result = self::downloadFile(
					$files['downloadLink'],
					ABSPATH . $files['amFilename']
				);

				// If the file is downloaded, then we write the data to the DB.
				if ( $result ) {
					WebTotemOption::setOptions( [
						'am_installed' => true,
						'am_file'      => $files['amFilename'],
						'waf_file'     => $files['wafFilename'],
						'av_file'      => $files['avFilename'],
					] );

					self::generateMarkerFile();

					$message = __( 'Agent manager have been successfully installed', 'wtotem');
					WebTotemOption::setNotification( 'success', $message );
				}
				else {
					$message = sprintf(__( 'Check %s folder\'s write permission.', 'wtotem' ), ABSPATH) . sprintf(__(' Read more <a href="%s" target="_blank">here</a>.', 'wtotem' ), 'https://docs.wtotem.com/agent-setup#it-additional-recommendations');
					WebTotemOption::setNotification( 'error', $message );

					return FALSE;
				}
			}

		} catch ( \Exception $e ) {
			WebTotemOption::setNotification( 'error', $e->getMessage() );

			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Get the AM file download link and agents (AV, WAF) files name.
	 *
	 * @param string $host_id
	 *   Host id on WebTotem.
	 *
	 * @return array
	 *   Agent Manager download link, names of agents
	 */
	public static function getAgentsFiles( $host_id ) {
		$result = WebTotemAPI::getAgentsFiles( $host_id );
		if ( ! $result  ) {
			$file = [ 'amFilename' => NULL, 'downloadLink' => NULL ];

			$message = __( 'Error generating the agent manager file.', 'wtotem' );
			WebTotemOption::setNotification( 'error', $message );
		} else {
			$file = $result;
		}

		return $file;
	}

	/**
	 * Check the existence of the service file and the record in the DB.
	 *
	 * @param string $service
	 *   Service short name (am, av, waf).
	 *
	 * @return array
	 *   Service file data (installed status, file exist status, file name).
	 */
	public static function checkInstalledService( $service ) {
		$file = WebTotemOption::getOption( $service . "_file" );

		return [
			'option_status' => WebTotemOption::getOption( $service . "_installed" ),
			'file_status'   => ( (bool) $file ) && is_file( ABSPATH . $file ),
			'file_name'     => $file,
		];
	}

	/**
	 * Remove all agents files and folders. Clear agents options.
	 *
	 * @return bool
	 *   Returns TRUE if agent files
	 *   and folders have been successfully deleted.
	 */
	public static function removeAgents() {
		// Deleting all agent records from the database.
		WebTotemOption::clearOptions( [
			'am_installed',
			'waf_installed',
			'av_installed',
			'am_file',
			'waf_file',
			'av_file',
		] );

		if($wp_filesystem = self::wpFileSystem()){
			$list = $wp_filesystem->dirlist( ABSPATH );

			$uploads_list = $wp_filesystem->dirlist( ABSPATH .'wp-content/uploads' ) ?: [];
			foreach ($uploads_list as $key => $item){
				$uploads_list[$key]['name'] = 'wp-content/uploads/' . $item['name'];
			}

			$list = array_merge($list, $uploads_list);

			foreach ( $list as $item ) {

				$target_item = ABSPATH . $item['name'];

				// Check whether the item is a directory.
				$recursive = ( $item['type'] == 'd' ) ? true : false;

				$pattern = '/([a-zA-Z0-9_]{64}.av.php)|([a-zA-Z0-9_]{64}.am.php)|([a-zA-Z0-9_]{64}.waf.php)|(\.wtotem_[a-zA-Z0-9_]{12,16})/';

				if ( preg_match( $pattern, $target_item ) ) {
					$wp_filesystem->delete( $target_item, $recursive, $item['type'] );
				}
			}
		}

		return TRUE;
	}

	/**
	 * Base WordPress Filesystem class which Filesystem implementations extend.
	 *
	 * @return object|bool
	 *   Instance of Filesystem class.
	 */
	private static function wpFileSystem() {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		if (empty($wp_filesystem)) {
			WebTotemOption::setNotification('error', _('WP FileSystem path error'));
			return FALSE;
		}

		return $wp_filesystem;
	}

	/**
	 * @param $url
	 *   Link from where to download the file.
	 * @param $path
	 *   Path where to save the file.
	 *
	 * @return bool
	 *   If the file is saved successfully, it returns true.
	 */
	private static function downloadFile($download_url, $path) {

		$args = [
			'timeout' => '30',
			'sslverify' => FALSE,
		];

		$response = wp_remote_get($download_url, $args);
		$http_code = wp_remote_retrieve_response_code($response);

		if ($http_code < 200) {
			WebTotemOption::setNotification('error', __( 'Could not download file.', 'wtotem' ));
			return FALSE;
		}

		$response_body = wp_remote_retrieve_body($response);

		if($wp_filesystem = self::wpFileSystem()){
			if(!empty($response_body)){
				return $wp_filesystem->put_contents($path, $response_body, FS_CHMOD_FILE);
			} else {
				$message = __( 'API: Response body is empty.', 'wtotem' );
				WebTotemOption::setNotification( 'error', $message );
			}
		}
		return FALSE;

	}

	/**
	 * Generate the file that indicates that a WAF connection is being used through the plugin.
	 */
	public static function generateMarkerFile() {
		if($am_filename = WebTotemOption::getOption('am_file')) {
			if ( $wp_filesystem = self::wpFileSystem() ) {
        $content = '<?php exit(); ?>' . $am_filename;
        $file_path = WEBTOTEM_PLUGIN_PATH . '/generate.php';
				if ( ! file_exists($file_path) or $wp_filesystem->get_contents($file_path) != $content) {

					if ( ! $wp_filesystem->put_contents( $file_path, $content, FS_CHMOD_FILE ) ) {
						$message = sprintf( __( 'Check %s folder\'s write permission.', 'wtotem' ), WEBTOTEM_PLUGIN_PATH ) . sprintf( __( ' Read more <a href="%s" target="_blank">here</a>.', 'wtotem' ), 'https://docs.wtotem.com/agent-setup#it-additional-recommendations' );
						WebTotemOption::setNotification( 'error', $message );
					}

				}
			}
		}
	}

	/**
	 * Check if the plugin version has changed.
	 */
	public static function checkVersion(){
		if(WebTotemOption::isActivated()){
			// Get version of the plugin that was previously installed.
			$version = WebTotemOption::getOption('plugin_version');

			if ($version == WEBTOTEM_VERSION) {
				return;
			}

			WebTotemOption::setOptions(['plugin_version' => WEBTOTEM_VERSION]);

			// Generate the file that indicates that a WAF connection is being used through the plugin.
			self::generateMarkerFile();
		}

	}

}
