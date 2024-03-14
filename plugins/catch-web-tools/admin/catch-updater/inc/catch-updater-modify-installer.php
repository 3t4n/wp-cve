<?php
/**
 * Modify WordPress core installer
 *
 * @package catch-updater
 *
 * @since catch-updater 0.1
 */

if ( ! class_exists( 'CatchUpdaterModifyInstaller' ) ) {
	class CatchUpdaterModifyInstaller {
		var $_errors 		= array();

		var $_backup_status = 0;

		/**
		 * CatchUpdaterModifyInstaller _constructor to enqueue scripts, and modify output of the theme-install page
		 * @uses add_action
		 * @hooks  admin_init, load-theme-install.php, admin_enqueue_scripts
		 */
		function __construct() {

			if ( preg_match( '/update\.php/', $_SERVER['REQUEST_URI'] ) && isset( $_REQUEST['action'] ) ) {
				add_action( 'admin_init', array( $this, 'handle_updates' ), 100 );
			}

			add_action( 'load-theme-install.php', array( $this, 'start_output_buffering' ) );
		}

		/**
		 * filter_output filters the output of theme-install page and modifies the content
		 * @param  $output the contents of current output buffer
		 * @return modified output
		 * @uses preg_replace
		 */
		function filter_output( $output ) {
			$text = '<div id="catch-updater-main">';

				$text .= '<h3>' . __( 'CWT: Catch Updater Theme', 'catch-web-tools' ) . '</h3>';

				$output = preg_replace( '/(<input [^>]*name="(?:theme)zip".+?\n)/', "$text\$1", $output );

				$text = '<p><i>' . __( 'By default, the Catch Updater will overwrite an existing theme and create a backup in media library.', 'catch-web-tools' ) . '</i></p>';

				$text .= '<a class="button button-primary" id="more_options_show_button" />'. __('More Options', 'catch-web-tools') .'</a>';

				$text .= '<a class="button button-primary" id="more_options_hide_button"  style="display: none;" />'. __('Less Options', 'catch-web-tools') .'</a>';

				$text .= '<div id="more_options">';
					$text .= '<p><label>' . __( 'Update existing Theme? ', 'catch-web-tools' ) . '</label>
								<select name="catch_updater_update_existing">
									<option value="yes">' . __ ( 'Yes', 'catch-web-tools' ) . '</option>
									<option value="no">' . __( 'No', 'catch-web-tools' ) . '</option>
								</select>
							</p>';

					$text .= '<p><label>' . __( 'Create Backup? ', 'catch-web-tools' ) . '</label>
								<select name="catch_updater_create_backup">
									<option value="yes">' . __ ( 'Yes', 'catch-web-tools' ) . '</option>
									<option value="no">' . __( 'No', 'catch-web-tools' ) . '</option>
								</select>
							</p>';

					$text .= '<p>' . __( 'Message to display in front-end until update has finished', 'catch-web-tools' ) . '
								<textarea name="catch_updater_update_message" cols="15">' . __( 'The site is being updated and will be back in a few minutes.', 'catch-web-tools' ) . '</textarea>
							</p>';
					$text .= '</div>';
			$text .= '</div>';

			$output = preg_replace( '/(<input [^>]*name="themezip".+?\n)/', "\$1$text", $output );

			return $output;
		}

		/**
		 * start_output_buffering Just start outpur buffering
		 * @uses  $this->filter_output()
		 */
		function start_output_buffering() {
			ob_start( array( $this, 'filter_output' ) );
		}

		/**
		 * _get_themes return current themes
		 * @return current themes
		 */
		function _get_themes() {
			global $wp_themes;

			if ( isset( $wp_themes ) ) {
				return $wp_themes;
			}

			$themes 	= wp_get_themes();

			$wp_themes 	= array();

			foreach ( $themes as $theme ) {
				$name 	= $theme->get( 'Name' );

				if ( isset( $wp_themes[$name] ) )
					$wp_themes[$name . '/' . $theme->get_stylesheet()] = $theme;
				else
					$wp_themes[$name] = $theme;
			}

			return $wp_themes;
		}

		/**
		 * _get_theme_data gets the data for the theme passed as parameter
		 * @param  $directory theme of which, data in required
		 * @return theme data
		 */
		function _get_theme_data( $directory ) {
			$data 			= array();

			$themes 		= $this->_get_themes();

			$active_theme 	= wp_get_theme();

			$current_theme 	= array();

			foreach ( (array) $themes as $theme_name => $theme_data ) {
				if ( $directory === $theme_data['Stylesheet'] )
					$current_theme = $theme_data;
			}

			if ( empty( $current_theme ) )
				return $data;

			$data['version'] 	= $current_theme['Version'];

			$data['name'] 		= $current_theme['Name'];

			$data['directory'] 	= $current_theme['Stylesheet Dir'];

			$data['is_active'] 	= false;

			if ( ( $active_theme->template_dir === $current_theme['Template Dir'] ) || ( $active_theme->template_dir === $current_theme['Template Dir'] ) )
				$data['is_active'] = true;

			global $wp_version;

			if ( version_compare( '2.8.6', $wp_version, '>' ) )
				$data['directory'] = WP_CONTENT_DIR . $current_theme['Stylesheet Dir'];

			return $data;
		}

		/**
		 * handle_updates main function that handles upgrades
		 */
		function handle_updates() {
			if ( empty( $_POST['catch_updater_update_existing'] ) ) {
				return;
			}

			if ( 'no' === $_POST['catch_updater_update_existing'] ) {
				if ( version_compare( $GLOBALS['wp_version'], '3.8.9', '>' ) ) {
					$link = admin_url( "theme-install.php?upload" );
				} else {
					$link = admin_url( "theme-install.php?tab=upload" );
				}

				$this->_errors[] = __( 'You must select "Yes" from the "update existing theme?" dropdown option in order to update an existing theme.', 'catch-web-tools') .' <a href="'. esc_url( $link ). '">'. __( 'Try again', 'catch-web-tools') .'</a>.';
				add_action( 'admin_notices', array( $this, 'show_update_option_error_message' ) );

				return;
			}

			remove_action( 'admin_print_styles', 'builder_add_global_admin_styles' );

			include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

			require_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );

			require_once( ABSPATH . 'wp-admin/includes/file.php' );

			check_admin_referer( "theme-upload" );

			@set_time_limit( 300 );

			$archive 	= new PclZip( $_FILES["themezip"]['tmp_name'] );

			$directory 	= '';

			$contents 	= $archive->listContent();

			foreach ( (array) $contents as $content ) {
				if ( preg_match( '^(.*?)\/^', $content['filename'], $matches ) ) {
					$directory = $matches[1];
					break;
				}
			}

			$data = $this->_get_theme_data( $directory );

			if ( empty( $data ) )
				return;

			if ( 'yes' === $_POST['catch_updater_create_backup'] ) {
				$time_string 	= time();

				$zip_file 		= "$directory-{$data['version']}-$time_string.zip";

				$wp_upload_dir 	= wp_upload_dir();

				$zip_path 		= $wp_upload_dir['path'] . '/' . $zip_file;

				$zip_url 		= $wp_upload_dir['url'] . '/' . $zip_file;

				$archive 		= new PclZip( $zip_path );

				$zip_result 	= $archive->create( $data['directory'], PCLZIP_OPT_REMOVE_PATH, dirname( $data['directory'] ) );

				if ( 0 == $zip_result ) {
					$this->_errors[] = __( 'Unable to make a backup of the existing theme. Will not proceed with the update.', 'catch-web-tools' );
					add_action( 'admin_notices', array( $this, 'show_update_option_error_message' ) );

					return;
				}


				$attachment = array(
					'post_mime_type'	=> 'application/zip',
					'guid'				=> $zip_url,
					'post_title'		=> "Catch Updater Backup - {$data['name']} - {$data['version']}",
					'post_content'		=> '',
				);

				$id = wp_insert_attachment( $attachment, $zip_path );
				if ( !is_wp_error( $id ) )
					wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $zip_path ) );

				$this->_zip_url 		= $zip_url;

				$this->backup_status 	= 1;

			}
			if ( $data['is_active'] ) {
				set_transient( 'catch_updater_in_maintenance_mode', '1', 300 );

				set_transient( 'catch_updater_update_message', $_POST['catch_updater_update_message'], 300 );
			}

			global $wp_filesystem;

			if ( ! WP_Filesystem() ) {
				$this->_errors[] = __( 'Unable to initialize WP_Filesystem. Will not proceed with the update.', 'catch-web-tools' );
				add_action( 'admin_notices', array( $this, 'show_update_option_error_message' ) );

				return;
			}

			if ( ! $wp_filesystem->delete( $data['directory'], true ) ) {
				$this->_errors[] = __( 'Unable to remove the existing theme directory. Will not proceed with the update.', 'catch-web-tools' );
				add_action( 'admin_notices', array( $this, 'show_update_option_error_message' ) );

				return;
			}


			add_action( 'all_admin_notices', array( $this, 'show_message' ) );

			delete_transient( 'catch_updater_in_maintenance_mode' );

			delete_transient( 'catch_updater_update_message' );
		}

		/**
		 * show_message display message
		 */
		function show_message() {
			if( isset($this->_zip_url) ){
				echo '<div id="message" class="updated fade">
						<p>
							<strong>'. sprintf( __( 'A backup zip file of the old theme version can be downloaded %s here %s .', 'catch-web-tools' ), '<a href="'. esc_url( $this->_zip_url ) .'">', '</a>' ) . '</strong>
						</p>
					</div>';
			}
			else {
				echo '<div id="message" class="updated fade">
						<p>
							<strong>'. __( 'Update Successful. No backup created.', 'catch-web-tools' ). '</strong>
						</p>
					</div>';
			}
		}

		/**
		 * show_update_option_error_message show error message if error occurs
		 */
		function show_update_option_error_message() {
			if ( ! isset( $this->_errors ) )
				return;

			if ( ! is_array( $this->_errors ) )
				$this->_errors = array( $this->_errors );

			foreach ( (array) $this->_errors as $error )
				echo "<div id=\"message\" class=\"error\"><p><strong>$error</strong></p></div>\n";
		}
	}

	new CatchUpdaterModifyInstaller();
}