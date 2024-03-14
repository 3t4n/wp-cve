<?php

/**
 * Everestthemes Demo Importer class, so we don't have to worry about namespaces.
 */
class ET_Main {

	/**
	 * @var $instance the reference to *Singleton* instance of this class
	 */
	private static $instance;

	/**
	 * Private variables used throughout the plugin.
	 */
	private $importer, $plugin_page, $import_files, $logger, $log_file_path, $selected_index, $selected_import_files, $microtime, $frontend_error_messages, $ajax_call_number;


	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return ET_Main the *Singleton* instance.
	 */
	public static function getInstance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}


	/**
	 * Class construct function, to initiate the plugin.
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 */
	protected function __construct() {

		// Actions.
		add_action( 'admin_menu', array( $this, 'create_plugin_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_ajax_ET_import_demo_data', array( $this, 'import_demo_data_ajax_callback' ) );
		add_action( 'after_setup_theme', array( $this, 'setup_plugin_with_filter_data' ) );
	}


	/**
	 * Creates the plugin page and a submenu item in WP Appearance menu.
	 */
	public function create_plugin_page() {
		$plugin_page_setup = apply_filters(
			'everest-demo-importer/plugin_page_setup',
			array(
				'parent_slug' => 'themes.php',
				'page_title'  => esc_html__( 'Everestthemes Demo Importer', 'everest-toolkit' ),
				'menu_title'  => esc_html__( 'Import Demo Content', 'everest-toolkit' ),
				'capability'  => 'import',
				'menu_slug'   => 'everest-demo-importer',
			)
		);

		$this->plugin_page = add_submenu_page(
			$plugin_page_setup['parent_slug'],
			$plugin_page_setup['page_title'],
			$plugin_page_setup['menu_title'],
			$plugin_page_setup['capability'],
			$plugin_page_setup['menu_slug'],
			array(
				$this,
				'display_plugin_page',
			)
		);

	}


	/**
	 * Plugin page display.
	 */
	public function display_plugin_page() {
		?>
		<div class="ET__modal hidden">
			<div class="ET__modal_content">
				<h1><?php esc_html_e( 'Required Plugins Notice', 'everest-toolkit' ); ?></h1>
				<p> <?php esc_html_e( 'The following required plugin(s) will be installed and activated on this demo import.', 'everest-toolkit' ); ?></p>

				<h3><?php esc_html_e( 'Plugins:', 'everest-toolkit' ); ?></h3>
				<ul></ul>
				<button class="button button-primary button-hero" id="start-import"><?php esc_html_e( 'Import', 'everest-toolkit' ); ?></button>
				<button class="button button-danger button-hero" id="cancel-import"><?php esc_html_e( 'Cancel', 'everest-toolkit' ); ?></button>
			</div>
		</div>

		<div class="ET__intro-notice notice notice-warning is-dismissible">
			<p><?php esc_html_e( 'Before you begin, make sure all the required plugins are activated.', 'everest-toolkit' ); ?></p>
		</div>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Everestthemes Demo Importer', 'everest-toolkit' ); ?><span class="title-count theme-count"><?php echo esc_html( count( $this->import_files ) ); ?></span>
			</h1>
			<p class="about-description">
				<?php esc_html_e( 'Importing demo data (post, pages, images, theme settings, ...) is the easiest way to setup your theme.', 'everest-toolkit' ); ?>
				<?php esc_html_e( 'It will allow you to quickly edit everything instead of creating content from scratch.', 'everest-toolkit' ); ?>
			</p>

			<hr>

			<p><?php esc_html_e( 'When you import the data, the following things might happen:', 'everest-toolkit' ); ?></p>

			<ul>
				<li><?php esc_html_e( 'No existing posts, pages, categories, images, custom post types or any other data will be deleted or modified.', 'everest-toolkit' ); ?></li>
				<li><?php esc_html_e( 'Posts, pages, images, widgets, menus and other theme settings will get imported.', 'everest-toolkit' ); ?></li>
				<li><?php esc_html_e( 'Please click on the Import button only once and wait, it can take a couple of minutes.', 'everest-toolkit' ); ?></li>
			</ul>
			<?php
			// Display warrning if PHP safe mode is enabled, since we wont be able to change the max_execution_time.
			if ( ini_get( 'safe_mode' ) ) {
				printf(
					esc_html__( '%1$sWarning: your server is using %2$sPHP safe mode%3$s. This means that you might experience server timeout errors.%4$s', 'everest-toolkit' ),
					'<div class="notice  notice-warning  is-dismissible"><p>',
					'<strong>',
					'</strong>',
					'</p></div>'
				);
			}

			if ( 0 < count( $this->import_files ) ) :
				?>
				<div class="ET__multi-select-import">
					<h2><?php esc_html_e( 'Choose which demo you want to import:', 'everest-toolkit' ); ?></h2>
					<div class="theme-browser rendered">
						<div class="themes wp-clearfix">
							<?php
							$installed_demos = get_option( 'everest_themes', array() );
							foreach ( $this->import_files as $index => $import_file ) :
								$is_installed = false;

								$import_file_name = isset( $import_file['import_file_name'] ) ? $import_file['import_file_name'] : '';

								if ( ! $import_file_name ) {
									if ( in_array( $import_file_name, $installed_demos ) ) {
										$is_installed = true;
									}
								}
								?>
								<div class="theme <?php echo $is_installed ? 'active' : ''; ?>">
									<div class="theme-screenshot">
										<img src="<?php echo $import_file['import_preview_image_url']; ?>"
											 alt="">
									</div>
									<a target="_blank" href="<?php echo $import_file['demo_url']; ?>"
									   style="text-decoration: none;"
									   class="more-details"><?php esc_html_e( 'Live Preview', 'everest-toolkit' ); ?></a>
									<div class="theme-author">
										<?php esc_html_e( 'By Everestthemes', 'everest-toolkit' ); ?>
									</div>
									<div class="theme-id-container">
										<h2 class="theme-name">
											<?php
											if ( $is_installed ) {

												echo '<b>Imported</b> : ';
											}
											echo $import_file['import_file_name']
											?>
										</h2>

										<div class="theme-actions">
											<?php
											$href = '';
											if ( $is_installed ) {

												$href = ' href="' . $import_file['demo_url'] . '" target="_blank"';
											}
											?>
											<a <?php echo $href; ?> class="button button-primary <?php echo ! $is_installed ? 'load-customize hide-if-no-customize jseverestimport-data ' : ''; ?>" data-index="<?php echo $index; ?>">
												<?php
												if ( ! $is_installed ) {
													esc_html_e( 'Import Demo', 'everest-toolkit' );
												} else {
													esc_html_e( 'Live Preview', 'everest-toolkit' );
												}
												?>
											</a>
												
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<?php
					// Check if at least one preview image is defined, so we can prepare the structure for display.
					$preview_image_is_defined = false;

					foreach ( $this->import_files as $import_file ) {
						if ( isset( $import_file['import_preview_image_url'] ) ) {
							$preview_image_is_defined = true;
							break;
						}
					}

					$preview_image_is_defined = false;

					if ( $preview_image_is_defined ) :
						?>
						<div class="ET__demo-import-preview-container">
							<p><?php esc_html_e( 'Import preview:', 'everest-toolkit' ); ?></p>
							<p class="ET__demo-import-preview-image-message jseverestpreview-image-message">
								<?php
								if ( ! isset( $this->import_files[0]['import_preview_image_url'] ) ) {
									esc_html_e( 'No preview image defined for this import.', 'everest-toolkit' );
								} // Leave the img tag below and the p tag above available for later changes via JS.
								?>
							</p>

							<img id="ET__demo-import-preview-image" class="jseverestpreview-image"
								 src="<?php echo ! empty( $this->import_files[0]['import_preview_image_url'] ) ? esc_url( $this->import_files[0]['import_preview_image_url'] ) : ''; ?>">
						</div>
						<?php
					endif;
					?>
				</div>
				<p><?php esc_html_e( 'Click the Import Demo button once and wait. The import process may take few minutes.', 'everest-toolkit' ); ?></p>
				<p><?php esc_html_e( 'Some of the elements, like instagram feeds, map etc... may have not been imported, due to some reasons. So manually set options for theme.', 'everest-toolkit' ); ?></p>
				<?php
			endif;
			?>
		</div>
		<?php
	}


	/**
	 * Enqueue admin scripts (JS and CSS)
	 *
	 * @param string $hook holds info on which admin page you are currently loading.
	 */
	public function admin_enqueue_scripts() {
		$localized_data = apply_filters(
			'et_admin_ajax_filter_localized_data',
			array(
				'ajax_url'     => admin_url( 'admin-ajax.php' ),
				'ajax_nonce'   => wp_create_nonce( 'et-ajax-verification' ),
				'import_files' => $this->import_files,
				'texts'        => array(
					'missing_preview_image' => esc_html__( 'No preview image defined for this import.', 'everest-toolkit' ),
				),
			)
		);
		wp_enqueue_script( 'everest-toolkit-main', ET()->plugin_url() . '/admin/js/everest-toolkit-main.js', array( 'jquery', 'jquery-form' ), EVERESTTOOLKIT_VERSION, true );

		wp_localize_script(
			'everest-toolkit-main',
			'et_admin_ajax',
			$localized_data
		);

		wp_enqueue_style( 'everest-toolkit-main', ET()->plugin_url() . '/admin/css/everest-toolkit-main.css', array(), EVERESTTOOLKIT_VERSION );
	}


	/**
	 * Main AJAX callback function for:
	 * 1. prepare import files (uploaded or predefined via filters)
	 * 2. import content
	 * 3. before widgets import setup (optional)
	 * 4. import widgets (optional)
	 * 5. import customizer options (optional)
	 * 6. after import setup (optional)
	 */
	public function import_demo_data_ajax_callback() {

		// Try to update PHP memory limit (so that it does not run out of it).
		ini_set( 'memory_limit', apply_filters( 'everest-demo-importer/import_memory_limit', '350M' ) );

		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		ET_Helpers::verify_ajax_call();

		// Install and activate the required plugin.
		$this->preimport_plugin_check();

		// Is this a new AJAX call to continue the previous import?
		$use_existing_importer_data = $this->get_importer_data();

		if ( ! $use_existing_importer_data ) {

			// Set the AJAX call number.
			$this->ajax_call_number = empty( $this->ajax_call_number ) ? 0 : $this->ajax_call_number;

			// Error messages displayed on front page.
			$this->frontend_error_messages = '';

			// Create a date and time string to use for demo and log file names.
			$demo_import_start_time = date( apply_filters( 'everest-demo-importer/date_format_for_file_names', 'Y-m-d__H-i-s' ) );

			// Define log file path.
			$this->log_file_path = ET_Helpers::get_log_path( $demo_import_start_time );

			// Get selected file index or set it to 0.
			$this->selected_index = empty( $_POST['selected'] ) ? 0 : absint( $_POST['selected'] );

			/**
			 * 1. Prepare import files.
			 * Manually uploaded import files or predefined import files via filter: et-demo-content-import
			 */
			if ( ! empty( $_FILES ) ) { // Using manual file uploads?

				// Get paths for the uploaded files.
				$this->selected_import_files = ET_Helpers::process_uploaded_files( $_FILES, $this->log_file_path );

				// Set the name of the import files, because we used the uploaded files.
				$this->import_files[ $this->selected_index ]['import_file_name'] = esc_html__( 'Manually uploaded files', 'everest-toolkit' );
			} elseif ( ! empty( $this->import_files[ $this->selected_index ] ) ) { // Use predefined import files from wp filter: et-demo-content-import.

				// Download the import files (content and widgets files) and save it to variable for later use.
				$this->selected_import_files = ET_Helpers::download_import_files(
					$this->import_files[ $this->selected_index ],
					$demo_import_start_time
				);

				// Check Errors.
				if ( is_wp_error( $this->selected_import_files ) ) {

					// Write error to log file and send an AJAX response with the error.
					ET_Helpers::log_error_and_send_ajax_response(
						$this->selected_import_files->get_error_message(),
						$this->log_file_path,
						esc_html__( 'Downloaded files', 'everest-toolkit' )
					);
				}

				// Add this message to log file.
				$log_added = ET_Helpers::append_to_file(
					sprintf(
						__( 'The import files for: %s were successfully downloaded!', 'everest-toolkit' ),
						$this->import_files[ $this->selected_index ]['import_file_name']
					) . ET_Helpers::import_file_info( $this->selected_import_files ),
					$this->log_file_path,
					esc_html__( 'Downloaded files', 'everest-toolkit' )
				);
			} else {

				// Send JSON Error response to the AJAX call.
				wp_send_json( esc_html__( 'No import files specified!', 'everest-toolkit' ) );
			}
		}

		/**
		 * 2. Import content.
		 * Returns any errors greater then the "error" logger level, that will be displayed on front page.
		 */
		$this->frontend_error_messages .= $this->import_content( $this->selected_import_files['content'] );

		/**
		 * 5. Import customize options.
		 */
		if ( ! empty( $this->selected_import_files['customizer'] ) && empty( $this->frontend_error_messages ) ) {
			$this->import_customizer( $this->selected_import_files['customizer'] );
		}

		/**
		 * 4. Import widgets.
		 */
		if ( ! empty( $this->selected_import_files['widgets'] ) && empty( $this->frontend_error_messages ) ) {
			$this->import_widgets( $this->selected_import_files['widgets'] );
		}

		/**
		 * 3. Before widgets import setup.
		 */
		$action = 'everest-demo-importer/before_widgets_import';
		if ( ( false !== has_action( $action ) ) && empty( $this->frontend_error_messages ) ) {

			// Run the before_widgets_import action to setup other settings.
			$this->do_import_action( $action, $this->import_files[ $this->selected_index ] );
		}

		/**
		 * 6. After import setup.
		 */
		$action = 'et-after-demo-content-import';
		// if ( ( false !== has_action( $action ) ) && empty( $this->frontend_error_messages ) ) {

		// Run the after_import action to setup other settings.
		$this->do_import_action( $action, $this->import_files[ $this->selected_index ] );
		// }

		// Display final messages (success or error messages).
		if ( empty( $this->frontend_error_messages ) ) {
			$response['message'] = sprintf(
				__( 'The demo import has finished. Please check your page and make sure that everything has imported correctly.', 'everest-toolkit' )
			);

		} else {
			$response['message']  = $this->frontend_error_messages . '<br>';
			$response['message'] .= sprintf(
				__( 'The demo import has finished, but there were some import errors.%1$s More details about the errors can be found in this %2$s %3$slog file%4$s %5$s.', 'everest-toolkit' ),
				'<br>',
				'<strong>',
				'<a href="' . ET_Helpers::get_log_url( $this->log_file_path ) . '" target="_blank">',
				'</strong>',
				'</a>'
			);
		}

		wp_send_json( $response );
	}

	private function preimport_plugin_check() {
		$recommended_actions = isset( $_POST['recommended_actions'] ) ? wp_unslash( $_POST['recommended_actions'] ) : '';

		$recommended_actions = json_decode( $recommended_actions, true );
		if ( empty( $recommended_actions ) ) {
			return;
		}
		if ( is_array( $recommended_actions ) && ! empty( $recommended_actions ) ) {
			foreach ( $recommended_actions as $recommended_action ) {
				if ( ! empty( $recommended_action['optional'] ) ) {
					continue;
				}

				$plugin_slug = isset( $recommended_action['plugin_slug'] ) ? $recommended_action['plugin_slug'] : '';
				$zip_url     = "https://downloads.wordpress.org/plugin/{$plugin_slug}.zip";
				wp_cache_flush();
				$plugins  = get_plugins();
				$basename = "{$plugin_slug}/{$plugin_slug}.php";

				if ( ! isset( $plugins[ $basename ] ) ) {
					$this->install_and_activate( $plugin_slug, $zip_url );
				}
				// lets regenerate the plugin cache.
				wp_cache_flush();
				$plugins = get_plugins();
				// if you are here you have downloaded the plugin file or installed.
				if ( ! is_plugin_active( $basename ) ) {
					// if you are here plugin is installed but not activated , so lets activate it.
					activate_plugin( $basename );
				}
				// if you are here the plugin is install and activated.
			}
		}

	}

	protected function install_and_activate( $plugin, $package ) {

		$plugins_dir = WP_PLUGIN_DIR;

		$plugin_folder = dirname( $plugins_dir . DIRECTORY_SEPARATOR . $plugin );
		$plugin_zip    = $plugin_folder . '.zip';

		$data = wp_remote_get(
			$package,
			array(
				'sslverify' => false,
			)
		);

		$content = wp_remote_retrieve_body( $data );

		if ( file_exists( $plugin_zip ) ) {
			unlink( $plugin_zip );
		}

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once wp_normalize_path( ABSPATH . 'wp-admin/includes/file.php' );
		}

		WP_Filesystem();

		global $wp_filesystem;

		$wp_filesystem->put_contents( $plugin_zip, $content );

		if ( ! file_exists( $plugin_zip ) ) {
			return;
		}

		unzip_file( $plugin_zip, $plugins_dir );

		unlink( $plugin_zip );

		wp_cache_delete( 'plugins', 'plugins' );

		if ( ! is_wp_error( activate_plugin( $plugin, '', is_multisite() ) ) ) {
			$this->install_activate = true;
		};

	}

	/**
	 * Import content from an WP XML file.
	 *
	 * @param string $import_file_path path to the import file.
	 */
	private function import_content( $import_file_path ) {

		$this->microtime = microtime( true );

		// This should be replaced with multiple AJAX calls (import in smaller chunks)
		// so that it would not come to the Internal Error, because of the PHP script timeout.
		// Also this function has no effect when PHP is running in safe mode
		// http://php.net/manual/en/function.set-time-limit.php.
		// Increase PHP max execution time.
		set_time_limit( apply_filters( 'everest-demo-importer/set_time_limit_for_demo_data_import', 300 ) );

		// Disable import of authors.
		add_filter( 'wxr_importer.pre_process.user', '__return_false' );

		// Check, if we need to send another AJAX request and set the importing author to the current user.
		add_filter( 'wxr_importer.pre_process.post', array( $this, 'new_ajax_request_maybe' ) );

		// Disables generation of multiple image sizes (thumbnails) in the content import step.
		if ( ! apply_filters( 'everest-demo-importer/regenerate_thumbnails_in_content_import', true ) ) {
			add_filter(
				'intermediate_image_sizes_advanced',
				function () {
					return null;
				}
			);
		}

		// Import content.
		if ( ! empty( $import_file_path ) ) {
			ob_start();
			$this->importer->import( $import_file_path );
			$message = ob_get_clean();

			// Add this message to log file.
			$log_added = ET_Helpers::append_to_file(
				$message . PHP_EOL . esc_html__( 'Max execution time after content import = ', 'everest-toolkit' ) . ini_get( 'max_execution_time' ),
				$this->log_file_path,
				esc_html__( 'Importing content', 'everest-toolkit' )
			);
		}

		// Delete content importer data for current import from DB.
		delete_transient( 'MG_importer_data' );

		// Return any error messages for the front page output (errors, critical, alert and emergency level messages only).
		return $this->logger->error_output;
	}


	/**
	 * Import widgets from WIE or JSON file.
	 *
	 * @param string $widget_import_file_path path to the widget import file.
	 */
	private function import_widgets( $widget_import_file_path ) {

		// Widget import results.
		$results = array();

		// Create an instance of the Widget Importer.
		$widget_importer = new ET_Importer_Widget();

		// Import widgets.
		if ( ! empty( $widget_import_file_path ) ) {

			// Import widgets and return result.
			$results = $widget_importer->import_widgets( $widget_import_file_path );
		}

		// Check for errors.
		if ( is_wp_error( $results ) ) {

			// Write error to log file and send an AJAX response with the error.
			ET_Helpers::log_error_and_send_ajax_response(
				$results->get_error_message(),
				$this->log_file_path,
				esc_html__( 'Importing widgets', 'everest-toolkit' )
			);
		}

		ob_start();
		$widget_importer->format_results_for_log( $results );
		$message = ob_get_clean();

		// Add this message to log file.
		$log_added = ET_Helpers::append_to_file(
			$message,
			$this->log_file_path,
			esc_html__( 'Importing widgets', 'everest-toolkit' )
		);
	}


	/**
	 * Import customizer from a DAT file, generated by the Customizer Export/Import plugin.
	 *
	 * @param string $customizer_import_file_path path to the customizer import file.
	 */
	private function import_customizer( $customizer_import_file_path ) {

		// Try to import the customizer settings.
		$results = ET_Importer_Customizer_Importer::import_customizer_options( $customizer_import_file_path );

		// Check for errors.
		if ( is_wp_error( $results ) ) {

			// Write error to log file and send an AJAX response with the error.
			ET_Helpers::log_error_and_send_ajax_response(
				$results->get_error_message(),
				$this->log_file_path,
				esc_html__( 'Importing customizer settings', 'everest-toolkit' )
			);
		}

		// Add this message to log file.
		$log_added = ET_Helpers::append_to_file(
			esc_html__( 'Customizer settings import finished!', 'everest-toolkit' ),
			$this->log_file_path,
			esc_html__( 'Importing customizer settings', 'everest-toolkit' )
		);
	}


	/**
	 * Setup other things in the passed wp action.
	 *
	 * @param string $action the action name to be executed.
	 * @param array  $selected_import with information about the selected import.
	 */
	private function do_import_action( $action, $selected_import ) {

		ob_start();
		do_action( $action, $selected_import );
		$message = ob_get_clean();

		// Add this message to log file.
		$log_added = ET_Helpers::append_to_file(
			$message,
			$this->log_file_path,
			$action
		);
	}


	/**
	 * Check if we need to create a new AJAX request, so that server does not timeout.
	 *
	 * @param array $data current post data.
	 *
	 * @return array
	 */
	public function new_ajax_request_maybe( $data ) {

		$time = microtime( true ) - $this->microtime;

		// We should make a new ajax call, if the time is right.
		if ( $time > apply_filters( 'everest-demo-importer/time_for_one_ajax_call', 25 ) ) {
			$this->ajax_call_number ++;
			$this->set_importer_data();

			$response = array(
				'status'  => 'newAJAX',
				'message' => 'Time for new AJAX request!: ' . $time,
			);

			// Add any output to the log file and clear the buffers.
			$message = ob_get_clean();

			// Add message to log file.
			$log_added = ET_Helpers::append_to_file(
				__( 'Completed AJAX call number: ', 'everest-toolkit' ) . $this->ajax_call_number . PHP_EOL . $message,
				$this->log_file_path,
				''
			);

			wp_send_json( $response );
		}

		// Set importing author to the current user.
		// Fixes the [WARNING] Could not find the author for ... log warning messages.
		$current_user_obj    = wp_get_current_user();
		$data['post_author'] = $current_user_obj->user_login;

		return $data;
	}

	/**
	 * Set current state of the content importer, so we can continue the import with new AJAX request.
	 */
	private function set_importer_data() {

		$data = array(
			'frontend_error_messages' => $this->frontend_error_messages,
			'ajax_call_number'        => $this->ajax_call_number,
			'log_file_path'           => $this->log_file_path,
			'selected_index'          => $this->selected_index,
			'selected_import_files'   => $this->selected_import_files,
		);

		$data = array_merge( $data, $this->importer->get_importer_data() );

		set_transient( 'MG_importer_data', $data, 0.5 * HOUR_IN_SECONDS );
	}

	/**
	 * Get content importer data, so we can continue the import with this new AJAX request.
	 */
	private function get_importer_data() {

		if ( $data = get_transient( 'MG_importer_data' ) ) {
			$this->frontend_error_messages = empty( $data['frontend_error_messages'] ) ? '' : $data['frontend_error_messages'];
			$this->ajax_call_number        = empty( $data['ajax_call_number'] ) ? 1 : $data['ajax_call_number'];
			$this->log_file_path           = empty( $data['log_file_path'] ) ? '' : $data['log_file_path'];
			$this->selected_index          = empty( $data['selected_index'] ) ? 0 : $data['selected_index'];
			$this->selected_import_files   = empty( $data['selected_import_files'] ) ? array() : $data['selected_import_files'];
			$this->importer->set_importer_data( $data );

			return true;
		}

		return false;
	}


	/**
	 * Get data from filters, after the theme has loaded and instantiate the importer.
	 */
	public function setup_plugin_with_filter_data() {

		// Get info of import data files and filter it.
		$this->import_files = ET_Helpers::validate_import_file_info( apply_filters( 'et-demo-content-import', array() ) );

		// Importer options array.
		$importer_options = apply_filters(
			'everest-demo-importer/importer_options',
			array(
				'fetch_attachments' => true,
			)
		);

		// Logger options for the logger used in the importer.
		$logger_options = apply_filters(
			'everest-demo-importer/logger_options',
			array(
				'logger_min_level' => 'warning',
			)
		);

		// Configure logger instance and set it to the importer.
		$this->logger            = new ET_Logger();
		$this->logger->min_level = $logger_options['logger_min_level'];

		// Create importer instance with proper parameters.
		$this->importer = new ET_Importer_Main( $importer_options, $this->logger );
	}


}

