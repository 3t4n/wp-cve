<?php
/*
Plugin Name: SKT Builder
Plugin URI: https://www.sktthemes.org/shop/skt-page-builder/
Text Domain: sktbuilder
Domain Path: /languages
Description: SKT Page Builder is an intuitive page builder created in order to save time and efforts of creating landing pages and for adding your content the way you like it or love it.
Version: 4.3
Author: SKT Themes
Author URI: https://www.sktthemes.org/
*/
 

class Sktbuilder {
	private $mode = 'prod';
	/**
	 * Default post types
	 *
	 * @var string
	 */
	private $version = '4.3';
	/**
	 * Register actions for plugin
	 */
	public function __construct( $mode = 'prod' ) {
		$this->mode = $mode;

		// Plugin have been loaded
		add_action( 'plugins_loaded', array( $this, 'pluginLoaded' ), 10, 2 );

		// Load libraries data action. Needs for front and backend.
		add_action( 'wp_ajax_sktbuilder_load_libraries_data', array( $this, 'loadSktbuilderLibrariesData' ) );
		add_action( 'wp_ajax_nopriv_sktbuilder_load_libraries_data', array( $this, 'loadSktbuilderLibrariesData' ) );

		if ( is_admin() ) {
			// =================ONLY ADMIN ZONE=====================
			// Register action for sktbuilder builder page
			add_action( 'post_action_sktbuilder', array( $this, 'starterPage' ) );

			// Add scripts for media library dialog
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueueBackendScripts' ) );

			// Ajax load page data
			add_action( 'wp_ajax_sktbuilder_load_page_data', array( $this, 'loadSktbuilderPageData' ) );

			// Ajax save page data
			add_action( 'wp_ajax_sktbuilder_save_page_data', array( $this, 'saveSktbuilderPageData' ) );

			// Ajax load page templates
			add_action( 'wp_ajax_sktbuilder_load_page_templates', array( $this, 'loadSktbuilderPageTemplates' ) );

			// Ajax save page template
			add_action( 'wp_ajax_sktbuilder_save_page_template', array( $this, 'SaveSktbuilderPageTemplate' ) );

			// Add new image to media
			add_action( 'wp_ajax_sktbuilder_add_new_image', array( $this, 'addNewImage' ) );

			// Add new video to media
			add_action( 'wp_ajax_sktbuilder_add_new_video', array( $this, 'addNewVideo' ) );

			// Controlling revisions
			add_action( 'save_post', array( $this, 'savePostMeta' ) );
			add_action( 'wp_restore_post_revision', array( $this, 'restoreRevision' ), 10, 2 );

			// Add demo blocks lib
			add_filter( 'sktbuilder_libs', array( $this, 'addDemoLib' ), 10, 2 );

			// Add metabox
			add_action( 'add_meta_boxes', array( $this, 'infoMetabox' ) );

			// Remove tinymce if page was used as Sktbuilder page
			add_action( 'load-page.php', array( $this, 'onPageEdit' ) );

			// Add edit link
			add_filter( 'page_row_actions', array( $this, 'addEditLinkPost' ) );

			// Filter sktbuilder meta data
			add_filter( 'wxr_export_skip_postmeta', array( $this, 'filterChangeExportSktbuildermeta' ), 10, 3 );

			// Add new lib by url
			add_action( 'admin_post_sktbuilder_add_library', array( $this, 'addNewLib' ) );

			// Register a custom menu page.
			add_action( 'admin_menu', array($this, 'registerSktbuilderAdminMenu') );
		} else {
			// ==================ONLY FRONTEND======================
			// add edit link to admin bar
			add_action( 'admin_bar_menu', array( &$this, 'addAdminBarLink' ), 999 );
			// Adding filter to check content
			add_filter( 'the_content', array( $this, 'modifyContent' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueFrontendScripts' ) );
		}
	}

	/**
	 * Add actions when class is loaded.
	 *
	 * @return void
	 */
	public function pluginLoaded() {
		// load localize
		load_plugin_textdomain( 'sktbuilder', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		// start update when loaded
		$this->pluginUpdate();
	}

	/**
	 * Update plugin on migrating between versions
	 */
	public function pluginUpdate() {
		// Update sktbuilder version
		if ( get_site_option( 'sktbuilder_version' ) !== $this->version ) {
	        $cur_version = get_site_option( 'sktbuilder_version' ) ? get_site_option( 'sktbuilder_version' ) : '0.0.0';

	        if ( version_compare( $this->version, $cur_version ) > 0 ) {
	        	update_option( 'sktbuilder_version', $this->version );
	        }
		}
	}

	public function enqueueFrontendScripts() {
		wp_enqueue_style( 'sktbuilder-frontend-style', plugins_url( 'assets/css/sktbuilder-frontend-custom.css', __FILE__ ) );

		// if page have $get['sktbuilder']
		if ( isset( $_GET['sktbuilder'] ) && $_GET['sktbuilder'] == true ) {
			wp_enqueue_script( 'sktbuilder-frontend-custom', plugins_url( 'assets/js/sktbuilder-frontend-custom.js', __FILE__ ), array( 'jquery' ) );
		}
		
		wp_enqueue_script( 'sktbuilder-frontend-custom-front', plugins_url( 'assets/js/sktbuilder-frontend-custom-front.js', __FILE__ ), array( 'jquery' ) );
	}
	
	public function enqueueBackendScripts() {
		wp_enqueue_media();
		wp_enqueue_script( 'sktbuilder-backend-starter', plugins_url( 'sktbuilder/sktbuilder-backend-starter.js', __FILE__ ), array( 'jquery' ) );
		wp_register_script( 'sktbuilder-backend-custom', plugins_url( 'assets/js/sktbuilder-backend-custom.js', __FILE__ ),  array( 'jquery' ), true );

		// Media WP style
		wp_enqueue_style( 'common' );
		wp_enqueue_style( 'forms' );

		// Localize the script with new data
		$translation_array = array(
			'button_text' => esc_html__( 'Edit with SKT Builder', 'sktbuilder' ),
			'theme_url' => get_template_directory_uri(),
		);
		wp_localize_script( 'sktbuilder-backend-custom', 'sktbuilder_backend_custom', $translation_array );
		// Enqueued script with localized data.
		wp_enqueue_script( 'sktbuilder-backend-custom' );

		wp_enqueue_style( 'sktbuilder-backend-style',  plugins_url( 'assets/css/sktbuilder-backend-custom.css', __FILE__ ) );
	}

	/**
	 * Checking for administration rights
	 */
	public function checkAccess() {

		return (current_user_can( 'edit_posts' ) ? true : false);
	}

	private function getDriverHtml() {
		global $post;
		if ( empty( $post ) ) {
			wp_die( 'Post not found' );
		}
		return '<script type="text/javascript" src="' . plugins_url( 'sktbuilder-wordpress-driver.js', __FILE__ ) . '"></script><script type="text/javascript"> var starter = new SktbuilderStarter({"mode": "' . $this->mode . '", "skip":["jquery","underscore","backbone"],"sktbuilderUrl": "' . plugins_url( 'sktbuilder/', __FILE__ ) . '", "driver": new SktbuilderWordpressDriver({"ajaxUrl": "' . admin_url( 'admin-ajax.php' ) . '", "iframeUrl": "' . add_query_arg( 'sktbuilder', 'true', get_permalink( $post->ID ) ) . '", "pageId": ' . $post->ID . ', "pages": ' . $this->getSktbuilderPages() . ', "page": "' . ( $post->post_title != '' ? wp_slash( $post->post_title ) : esc_html__( "No title", 'sktbuilder' ) ) . '" }) });</script>';
	}

	/**
	 * Render sktbuilder builder starter page
	 */
	public function starterPage( $post_id ) {
		if ( ! $this->checkAccess() ) {
			wp_die( 'Access is denied' );
		}

		// if new page
		$post_data = get_post( $post_id );
		if ( 'auto-draft' === $post_data->post_status ) {
			$data = array(
				'ID' => $post_id,
				'post_status' => 'publish',
				'post_title' => '',
			);

			$result = wp_update_post( $data, true );

			if ( is_wp_error( $result ) ) {
				wp_die( 'Post not saved' );
			}
		}

		global $hook_suffix;
		echo '<!DOCTYPE html><html><head>';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
		echo '<title>' . esc_html__( 'Edit Page with sktbuilder', 'sktbuilder' ) . '</title>';
		do_action( 'admin_enqueue_scripts', $hook_suffix );
		do_action( 'admin_print_styles' );
		do_action( 'admin_print_scripts' );
		echo '<script type="text/javascript">var ajaxurl = "' . admin_url( 'admin-ajax.php', 'relative' ) . '";</script>';
		echo $this->getDriverHtml();
		add_filter( 'admin_footer_text', '__return_empty_string', 11 );
		add_filter( 'update_footer', '__return_empty_string', 11 );
		echo '</head><body>';
		include( ABSPATH . 'wp-admin/admin-footer.php' );
		die();
	}

	/**
	 * Send array of libraries data for Sktbuilder
	 */
	public function loadSktbuilderLibrariesData() {
		$libs = $this->getLibs();

		$response = array(
			'success' => true,
			'libs' => (isset( $libs ) ? $libs : array()),
		);

		wp_send_json( $response );
	}

	/**
	 * Get libs
	 *
	 * @return json
	 */
	public function getLibs() {
		$libs = apply_filters( 'sktbuilder_libs', array() );

		$result = array();
		foreach ( $libs as $value ) {
			if ( file_exists( $value ) ) {
				$lib = json_decode( file_get_contents( $value ), true );
				if ( ! array_key_exists( 'url', $lib ) ) {
					$lib['url'] = $this->getUrlFromPath( str_replace( '/lib.json', '', $value ) );
				}

				// if old version lib
				if ( ! array_key_exists( 'version', $lib ) ) {
					// Set current version
					$lib['version'] = $this->version;

					// masks from assets
					if ( isset($lib['assets']) ) {
						$themeUrl = get_template_directory_uri();
						foreach ( $lib['assets'] as $k => $v ) {
							if ( is_array( $lib['assets'][ $k ]['src'] ) ) {
								$lib['assets'][ $k ]['src']['url'] = str_replace( '%theme_url%', $themeUrl, $lib['assets'][ $k ]['src']['url'] );
								if ( isset( $lib['assets'][ $k ]['src']['preview'] ) ) {
									$lib['assets'][ $k ]['src']['preview'] = str_replace( '%theme_url%', $themeUrl, $lib['assets'][ $k ]['src']['preview'] );
								}
							} else {
								$lib['assets'][ $k ]['src'] = str_replace( '%theme_url%', $themeUrl, $lib['assets'][ $k ]['src'] );
								if ( isset( $lib['assets'][ $k ]['preview'] ) ) {
									$lib['assets'][ $k ]['preview'] = str_replace( '%theme_url%', $themeUrl, $lib['assets'][ $k ]['preview'] );
								}
							}
						}
					}

					foreach ( $lib['blocks'] as $index => $block ) {
						$lib['blocks'][ $index ]['url'] = str_replace( '%theme_url%/blocks/', '', $block['url'] );
					}
					
					if ( isset($lib['res']) ) {
						foreach ( $lib['res'] as $key => $val ) {
							// @deprecated 3.0.0
							if ( 'js' === $key || 'css' === $key ) {
								foreach ( $val as $v ) {
									$temp = array(
											'type' => $key,
											'name' => $v['name'],
											'src' => str_replace( '%theme_url%/blocks/', '', $v['url'] ),
											);

									if ( isset( $v['use'] ) && $v['use'] ) {
										$lib['res'][] = array_merge_recursive( $temp, $v['use'] );
									} else {
										$lib['res'][] = $temp;
									}

									unset( $temp );
								}
							} else {
								// from version 3.0.0 and high
								$lib['res'][$key]['src'] = str_replace( '%theme_url%/blocks/', '', $lib['res'][$key]['src'] );
							}
						}

						unset( $lib['res']['css'] );
						unset( $lib['res']['js'] );
					}
				}

				$result[] = $lib;
			}
		}

		// Get libs from base
		$list_libs = get_option( 'sktbuilder_libraries' );

		if ( $list_libs ) {
			foreach ($list_libs as $lib_url) {
				if ( $data = @file_get_contents( $lib_url ) ) {
					// decode the JSON data
					$lib = json_decode( $data, true );
					
					$lib['external'] = true;
					$lib['url'] = $lib_url;

					if ( json_last_error() === 0 ) {
						// JSON is valid
						array_push( $result, $lib );
					}
				}
			}
		}

		return $result;
	}

	public function getUrlFromPath( $path ) {
		// Get correct URL and path to wp-content
		$content_url = untrailingslashit( dirname( dirname( get_stylesheet_directory_uri() ) ) );
		$content_dir = untrailingslashit( WP_CONTENT_DIR );

		// Fix path on Windows
		$path = wp_normalize_path( $path );
		$content_dir = wp_normalize_path( $content_dir );

		return str_replace( $content_dir, $content_url, $path );
	}

	/**
	 * Load data page
	 *
	 * @return json
	 */
	public function loadSktbuilderPageData() {
		if ( ! $this->checkAccess() ) {
			$response = array(
				'success' => false,
				'error' => 'Access is denied',
			);
		} else {
			if ( is_string( get_post_status( $_REQUEST['page_id'] ) ) ) {
				$data = get_post_meta( $_REQUEST['page_id'], 'sktbuilder_data', true );

				// Send decoded page data to the Sktbuilder editor page
				if ( '' !== $data ) {
					$data = wp_unslash( json_decode( $data, true ) );

					if ( ! empty( $data['blocks'] ) && is_null( $data['version'] ) ) {
						foreach ( $data['blocks'] as $index => $block ) {
							$data['blocks'][ $index ]['block'] = $data['blocks'][ $index ]['template'];
							unset( $data['blocks'][ $index ]['template'] );
						}

						$data['version'] = $this->version;
					}

					$response = array(
						'success' => true,
						'data' => $data,
					);
				} else {
					$response = array( 'success' => true, 'data' => array( 'version' => $this->version, 'blocks' => array() ) );
				}
			} else {
				$response = array( 'success' => false, 'error' => 'Page with id ' + $_REQUEST['page_id'] + ' not found.' );
			}
		}
		wp_send_json( $response );
	}

	/**
	 * Save data page
	 *
	 * @return json
	 */
	public function saveSktbuilderPageData() {
		if( current_user_can('editor') || current_user_can('administrator') ) {
		$incoming = file_get_contents( 'php://input' );

		$decoded = json_decode( $incoming, true );
		$data = $decoded['data'];

		$blocks_html = trim( $data['html'] );

		$post_id = $decoded['pageId'];

		$sktbuilder_data = wp_slash( wp_json_encode( $data['data'] ) );

		// Saving metafield
		$updated_meta = update_post_meta( $post_id, 'sktbuilder_data', $sktbuilder_data );

		// Updating post content and post content filtered
		$update_args = array(
		  'ID'           => $post_id,
		  'post_content' => $blocks_html,
		);

		$updated = wp_update_post( $update_args );

		if ( $updated && $updated_meta ) {
			$response = array( 'success' => true );
		} else {
			$response = array( 'success' => false, 'error' => "Couldn't save data" );
		}

		wp_send_json( $response );
		}
	}

	/**
	 * Load page templates
	 *
	 * @return json
	 */
	public function loadSktbuilderPageTemplates() {
		$id = 0;

		if ( $page_templates = get_site_option( 'sktbuilder_page_templates' ) ) {
			$page_templates = json_decode( wp_unslash( $page_templates ), true );
			foreach ($page_templates as $template) {
				if ( $template['id'] > $id )
					$id = $template['id'];
			}
		} else {
			$page_templates = array();
		}

		$default_page_templates = apply_filters( 'sktbuilder_page_templates', array() );

		if ( $default_page_templates ) {
			$default_page_templates = json_decode( wp_unslash( $default_page_templates ), true );
			foreach ( $default_page_templates as $key => $template ) {
				$id++;
				$default_page_templates[$key]['external'] = true;
				$default_page_templates[$key]['id'] = $id;
			}
		} else {
			$default_page_templates = array();
		}

		$result = array_merge( $page_templates, $default_page_templates );

		if ( count($result) > 0 ) {
			$response = array( 'success' => true, 'templates' => json_encode( $result ) );
		} else {
			$response = array( 'success' => false );
		}

		wp_send_json( $response );
	}

	/**
	 * Save page template
	 *
	 * @return json
	 */
	public function SaveSktbuilderPageTemplate() {
		$incoming = file_get_contents( 'php://input' );

		$update_option = update_option( 'sktbuilder_page_templates', wp_slash( $incoming ) );

		if ( $update_option ) {
			$response = array( 'success' => true );
		} else {
			$response = array( 'success' => false, 'error' => "Couldn't save template" );
		}

		wp_send_json( $response );
	}

	/**
	 * Filtering the content for theme templating
	 */
	public function modifyContent( $content = null ) {
		$result = '';
		if ( is_user_logged_in() && (isset( $_GET['sktbuilder'] ) && $_GET['sktbuilder'] == true) ) {
			$result = '<div id="sktbuilder-blocks"></div>';
		} else {
			global $post;
			$data = json_decode( get_post_meta( $post->ID, 'sktbuilder_data', true ), true );

			// If have blocks - use get_the_content() function. In other way - return basic content
			if ( !empty( $data['blocks'] ) && count( $data['blocks'] ) ) {
				$result = do_shortcode( stripslashes( get_the_content() ) );
			} else {
				$result = do_shortcode( $content );
			}
		}
		$result .= '    <!-- sktbuilder starter --><script type="text/javascript" src="' . plugins_url( 'sktbuilder/sktbuilder-frontend-starter.js', __FILE__ ) . '"></script>'
		. $this->getDriverHtml() . '<!-- end sktbuilder starter -->';
		return $result;
	}

	/**
	 * Add link to admin bar
	 *
	 * @param WP_Admin_Bar $wp_admin_bar
	 */
	public function addAdminBarLink( $wp_admin_bar ) {
		if ( ! is_object( $wp_admin_bar ) ) {
			global $wp_admin_bar;
		}

		global $wp_query;
		if ( isset( $wp_query->post ) ) {
			$id = $wp_query->post->ID;
			$post_meta = json_decode( get_post_meta( $id, 'sktbuilder_data', true ), true );

			if ( is_singular() && ( !is_null( $post_meta ) && count( $post_meta ) ) ) {
					$wp_admin_bar->add_menu(array(
						'id' => 'sktbuilder-admin-bar-link',
						'title' => esc_html__( 'Edit with SKT Builder', 'sktbuilder' ),
						'href' => $this->getEditWithSktbuilderUrl( $id ),
						'meta' => array( 'class' => 'sktbuilder-inline-link' ),
					));
			}
		}
	}

	/**
	 * Get sktbuilder url edit page
	 *
	 * @param string $id Post id
	 * @return string
	 */
	public function getEditWithSktbuilderUrl( $pageId ) {
		return admin_url() . 'post.php?post=' . $pageId . '&action=sktbuilder';
	}

	/**
	 * Saving post meta to revision
	 *
	 * @param  int $post_id ID of the current post
	 */
	public function savePostMeta( $post_id ) {
		$parent_id = wp_is_post_revision( $post_id );
		if ( $parent_id ) {
			$parent  = get_post( $parent_id );
			$sktbuilder_data = get_post_meta( $parent->ID, 'sktbuilder_data', true );
			if ( $sktbuilder_data !== false ) {
				add_metadata( 'post', $post_id, 'sktbuilder_data', $sktbuilder_data );
			}
		}
	}

	/**
	 * Updating post metadata during revision restoring
	 *
	 * @param  int $post_id     Post id
	 * @param  int $revision_id Current revision id
	 */
	public function restoreRevision( $post_id, $revision_id ) {
		$revision = get_post( $revision_id );
		$data  = get_metadata( 'post', $revision->ID, 'sktbuilder_data', true );
		if ( false !== $data ) {
			update_post_meta( $post_id, 'sktbuilder_data', $data );
		} else {
			delete_post_meta( $post_id, 'sktbuilder_data' );
		}
	}

	/**
	 * Create array libs from json file
	 *
	 * @param array $sktbuilderLibs Array libs from json file.
	 */
	public function addDemoLib( $sktbuilder_libs ) {
		$sktbuilder_libs[] = plugin_dir_path( __FILE__ ) . 'sktbuilder/blocks/lib.json';
		return $sktbuilder_libs;
	}

	/**
	 * Add metabox with info about current Sktbuilder page
	 */
	public function infoMetabox() {
		global $post;
		$post_meta = json_decode( get_post_meta( $post->ID, 'sktbuilder_data', true ), true );

		// If have blocks - remove tinymce editor
		if ( !is_null($post_meta['blocks']) && count( $post_meta['blocks'] ) ) {
			add_meta_box( 'sktbuilder-page-info', esc_html__( 'Attention!', 'sktbuilder' ), array( $this, 'infoMetaboxDisplay' ), 'page' );
		}
	}

	/**
	 * Display metabox
	 */
	public function infoMetaboxDisplay() {
		$allowed_html = array(
			'p' => array(),
		);
		echo wp_kses( __( '<p>Current page has been edited with SKT Builder. To edit this page as regular one - go to SKT Builder editor by pressing "Edit with SKT Builder" button and remove all blocks.</p>', 'sktbuilder' ), $allowed_html );
	}

	/**
	 * Filtering metaboxes on page edit screen
	 */
	public function onPageEdit() {
		if ( isset( $_GET['post'] ) ) {
			// Getting sktbuilder_html from metadata
			$post_id = $_GET['post'];
			$data = json_decode( get_post_meta( $post_id, 'sktbuilder_data', true ), true );

			// If have blocks - remove tinymce editor
			if ( ! is_null($data['blocks']) && count( $data['blocks'] ) ) {
				remove_post_type_support( 'page', 'editor' );
			}
		}
	}

	/**
	 * Add link to posts list
	 *
	 * @param $actions
	 * @return mixed
	 */
	public function addEditLinkPost( $actions ) {
		$post_data = get_post();
		$id = ( strlen( $post_data->ID ) > 0 ? $post_data->ID : get_the_ID() );
		$url = $this->getEditWithSktbuilderUrl( $id );
		// Check for sktbuilder page
		$post_meta = json_decode( get_post_meta( $id, 'sktbuilder_data', true ), true );

		if ( ! is_null($post_meta) && count( $post_meta ) ) {
			$actions['edit_sktbuilder'] = '<a href="' . $url . '">' . esc_html__( 'Edit with SKT Builder', 'sktbuilder' ) . '</a>';
		}
		return $actions;
	}

	/**
	 * Quote sktbuilder_data with slashes
	 *
	 * @param (bool)   $skip Whether to skip the current post meta. Default false.
	 * @param  (string) $meta_key  Current meta key.
	 * @param  (object) $meta Current meta object.
	 * @return Returns the escaped meta object.
	 */
	public function filterChangeExportSktbuildermeta( $skip, $meta_meta_key, $meta ) {
		if ( 'sktbuilder_data' === $meta_meta_key ) {
			$meta->meta_value = addslashes( $meta->meta_value );
		}
		return $skip;
	}

	/**
	 * Upload file
	 *
	 * @param (array) file data array
	 */
	public function uploadFile( $file = array() ) {
		require_once ABSPATH . 'wp-admin/includes/admin.php';
		$file_return = wp_handle_upload( $file, array( 'test_form' => false ) );

		if ( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
			return false;
		} else {
			$filename = $file_return['file'];
			$attachment = array(
				'post_mime_type' => $file_return['type'],
				'post_content' => '',
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'guid' => $file_return['url'],
			);
			if ( $title ) {
				$attachment['post_title'] = $title;
			}
			$attachment_id = wp_insert_attachment( $attachment, $filename );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );

			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
			wp_update_attachment_metadata( $attachment_id, $attachment_data );
			if ( 0 < intval( $attachment_id ) ) {
				return $attachment_id;
			}
		}
		return false;
	}

	/**
	 * Send json data from image
	 */
	public function addNewImage() {
		$data = array();

		if ( empty( $_FILES ) ) {
			$data['error'] = false;
			$data['message'] = __( 'Please select an image to upload!','sktbuilder' );
		} elseif ( $file['size'] > 5242880 ) { // Maximum image size is 5M
			$data['size'] = $files[0]['size'];
			$data['error'] = false;
			$data['message'] = __( 'Image is too large. It must be less than 5M!','sktbuilder' );
		} else {
			$data['message'] = '';

			if ( isset( $_FILES['image'] ) ) {
				$file = $_FILES['image'];
				$attachment_id = $this->uploadFile( $file, false );

				if ( is_numeric( $attachment_id ) ) {
					$img_thumb = wp_get_attachment_image_src( $attachment_id, 'full' );
					$data['success'] = true;
					$data['url'] = $img_thumb[0];
				}
			}

			if ( ! $attachment_id ) {
				$data['error'] = false;
				$data['message'] = __( 'An error has occured. Your image was not added.','sktbuilder' );
			}
		}

		echo json_encode( $data );
		die();
	}

	/**
	 * Send json data from video
	 */
	public function addNewVideo() {
		$data = array();

		if ( empty( $_FILES ) ) {
			$data['error'] = false;
			$data['message'] = __( 'Please select an image to upload!','sktbuilder' );
		} elseif ( $file['size'] > 8388608 ) { // Maximum image size is 8M
			$data['size'] = $files[0]['size'];
			$data['error'] = false;
			$data['message'] = __( 'Video is too large. It must be less than 8M!','sktbuilder' );
		} else {
			$data['message'] = '';
			if ( isset( $_FILES['video'] ) ) {
				$file = $_FILES['video'];
				$attachment_id = $this->uploadFile( $file, false );

				if ( is_numeric( $attachment_id ) ) {
					$url = wp_get_attachment_url( $attachment_id );
					$data['success'] = true;
					$data['url'] = $url;
				}
			}

			if ( ! $attachment_id ) {
				$data['error'] = false;
				$data['message'] = __( 'An error has occured. Your image was not added.','sktbuilder' );
			}
		}

		echo json_encode( $data );
		die();
	}

	/**
 	 * Register a custom menu page.
 	 */
	public function registerSktbuilderAdminMenu() {
		add_menu_page(
			__('SKT Builder Manager', 'sktbuilder'),
			__( 'SKT Builder', 'sktbuilder' ),
			'manage_options',
			'sktbuilder',
			array( $this, 'adminPage' ),
			plugins_url( 'assets/img/sktbuilder_logo_admin_bar.png', __FILE__ ),
			21
		);

		add_submenu_page(
			'sktbuilder',
			__('Manage Libraries','menu-test'),
			__('Manage Libraries','menu-test'),
			'manage_options',
			'sktbuilder-manage-libs',
			array( $this, 'manageLibs' )
		);

		$this->actionsNoticeLibs();
	}

	/**
	 * Display a custom sktbuilder page
	 */
	public function adminPage() {
		include_once( 'views/page-sktbuilder.php' );
	}

	/**
	 * Display a sktbuilder manage library page
	 */
	public function manageLibs() {
		include_once( 'views/page-manage-libs.php' );
	}

	public function actionsNoticeLibs() {
		if ( false !== ( $action_notice = get_transient( "sktbuilder_action" ) ) ) {
			add_settings_error($action_notice[0]['setting'], $action_notice[0]['code'], $action_notice[0]['message'], $action_notice[0]['type']);
			delete_transient( "sktbuilder_action" );
		}

		// remove action
		if ( (isset($_GET['action']) && 'remove' === $_GET['action']) ) {

			$list_libs = get_option( 'sktbuilder_libraries' );
			unset( $list_libs[array_search( urldecode_deep( $_GET['lib_url'] ), $list_libs )] );

			if ( update_option( 'sktbuilder_libraries', $list_libs ) ) {
				add_settings_error('sktbuilder_action', esc_attr( 'updated' ), __( 'Library has been deleted', 'sktbuilder' ), 'updated' );
				set_transient('sktbuilder_action', get_settings_errors(), 30);
				wp_safe_redirect('admin.php?page=sktbuilder-manage-libs');
			} else {
				add_settings_error('sktbuilder_action', esc_attr( 'error' ), __( "Library can't been deleted", 'sktbuilder' ), 'error');
				set_transient('sktbuilder_action', get_settings_errors(), 30);
			}
		}
	}


	/**
	 * Determines if the nonce variable associated with the options page is set
	 * and is valid.
	 *
	 * @access private
	 * 
	 * @return boolean False if the field isn't set or the nonce value is invalid;
	 * otherwise, true.
	 */
	private function hasValidNonce() {
	    // If the field isn't even in the $_POST, then it's invalid.
	    if ( ! isset( $_POST['_wpnonce'] ) ) { // Input var okay.
	        return false;
	    }
	 
	    $field  = wp_unslash( $_POST['_wpnonce'] );
	    $action = 'sktbuilder_add_lib';
	 
	    return wp_verify_nonce( $field, $action );
	}

	/**
	 * Redirect to the page from which we came (which should always be the
	 * admin page. If the referred isn't set, then we redirect the user to
	 * the login page.
	 *
	 * @access private
	 */
	private function redirect() {
		// To make the Coding Standards happy, we have to initialize this.
		if ( ! isset( $_POST['_wp_http_referer'] ) ) { // Input var okay.
			$_POST['_wp_http_referer'] = wp_login_url();
		}

		// Sanitize the value of the $_POST collection for the Coding Standards.
		$url = sanitize_text_field(
			wp_unslash( $_POST['_wp_http_referer'] ) // Input var okay.
		);

		// Finally, redirect back to the admin page.
		wp_safe_redirect( urldecode( $url ) );
		exit;
	}

	/**
	 * Post action hook
	 */
	public function addNewLib() {
		if ( isset( $_POST['lib_url'] ) && '' !== $_POST['lib_url'] ) {
			$this->addLibraryByUrl($_POST['lib_url']);
		} else if ( isset( $_FILES['lib_file'] ) && '' !== $_FILES['lib_file']['name'] ) {
			$this->addLibraryByArchive( $_FILES['lib_file'] );
		} else {
			add_settings_error('sktbuilder_action', esc_attr( 'error' ), __( "You need to fill in one of the fields", 'sktbuilder' ), 'error');
			set_transient( 'sktbuilder_action', get_settings_errors(), 30 );
			$this->redirect();
		}
	}


	/**
	 * Addd library by url
	 * @param (String) $lib_url
	 */
	public function addLibraryByUrl( $lib_url = '' ) {
		if ( ! ( $this->hasValidNonce() && current_user_can( 'manage_options' ) ) ) {
			$this->redirect();
		}

		if ( ! $data = @file_get_contents( $lib_url ) ) {
			add_settings_error('sktbuilder_action', esc_attr( 'error' ), __( "The field isn't set or the value is invalid", 'sktbuilder' ), 'error');
			set_transient( 'sktbuilder_action', get_settings_errors(), 30 );
			$this->redirect();
		}

		// decode the JSON data
		$lib = json_decode($data, true);

		if ( json_last_error() === 0 ) {
			// JSON is valid
			$list_libs = get_option( 'sktbuilder_libraries' );

			if ( ! $list_libs )
				$list_libs = array();

			array_push( $list_libs, $lib_url );

			if ( update_option( 'sktbuilder_libraries', $list_libs ) ) {
				add_settings_error('sktbuilder_action', esc_attr( 'updated' ), __( 'The library has been successfully added', 'sktbuilder' ), 'updated');
				set_transient( 'sktbuilder_action', get_settings_errors(), 30 );
			} else {
				add_settings_error('sktbuilder_action', esc_attr( 'error' ), __( 'The library has not been added', 'sktbuilder' ), 'error');
				set_transient( 'sktbuilder_action', get_settings_errors(), 30 );
			}
		}

		$this->redirect();
	}

	/**
	 * Addd library by url
	 * @param (Array) $file
	 */
	public function addLibraryByArchive( $file = array() ) {
		WP_Filesystem();

		$upload = wp_upload_dir();
		$upload_dir = $upload['basedir'];
		$upload_dir = $upload_dir . '/sktbuilder';

		if ( wp_mkdir_p( $upload_dir ) ) {
			// Set default folder "sktbuilder"
			add_filter( 'upload_dir', array($this, 'UploadDir') );

			$uploadedfile = $_FILES['lib_file'];

			$allowed_file_types = array( 'zip' =>'application/zip' );
	    	$overrides = array('test_form' => false, 'mimes' => $allowed_file_types);
			$movefile = wp_handle_upload( $uploadedfile, $overrides );

			if ( $movefile && ! isset( $movefile['error'] ) ) {
				$unzipfile = unzip_file( $movefile['file'], $upload_dir );

				if ( $unzipfile ) {
					$json_url = str_replace( ".zip", "/lib.json", $movefile['url'] );

					if ( @file_get_contents( $json_url ) ) {
						$list_libs = get_option( 'sktbuilder_libraries' );

						if ( $list_libs === '' )
							$list_libs = array();

						array_push( $list_libs, $json_url );
		    			update_option( 'sktbuilder_libraries', $list_libs );
					} else {
						add_settings_error('sktbuilder_action', esc_attr( 'error' ), __( 'File "lib.json" was not found', 'sktbuilder' ), 'error');
						set_transient( 'sktbuilder_action', get_settings_errors(), 30 );
					}
					add_settings_error('sktbuilder_action', esc_attr( 'notice' ), __( "The library's URL has been added", 'sktbuilder' ), 'notice');
					set_transient( 'sktbuilder_action', get_settings_errors(), 30 );
				} else {
					add_settings_error('sktbuilder_action', esc_attr( 'error' ), __( "There was an error unzipping the file", 'sktbuilder' ), 'error');
					set_transient( 'sktbuilder_action', get_settings_errors(), 30 );
				}
			} else {
			    /**
			     * Error generated by _wp_handle_upload()
			     * @see _wp_handle_upload() in wp-admin/includes/file.php
			     */
				add_settings_error('sktbuilder_action', esc_attr( 'error' ), $movefile['error'], 'error');
				set_transient( 'sktbuilder_action', get_settings_errors(), 30 );
			}

			// Remove default folder "sktbuilder"
			remove_filter( 'upload_dir', array( $this, 'UploadDir') );

			$this->redirect();
		} else {
			add_settings_error('sktbuilder_action', esc_attr( 'error' ), __( "You don’t have a permission to upload or create files", 'sktbuilder' ), 'error');
			set_transient( 'sktbuilder_action', get_settings_errors(), 30 );
		}
	}

	/**
	 * Change default upload folder
	 * @param (array) $dirs
	 */
	public function UploadDir( $dirs ) {
	    $dirs['subdir'] = '/sktbuilder';
	    $dirs['path'] = $dirs['basedir'] . '/sktbuilder';
	    $dirs['url'] = $dirs['baseurl'] . '/sktbuilder';
	    return $dirs;
	}

	/**
	 * Get sktbuilder pages
	 * @return (array) json pages
	 */
	private function getSktbuilderPages() {
		global $post;

		$args = array(
			'post_type'      => 'page',
			'order'            => 'ASC',
			'meta_query' => array(
					array(
						'key' => 'sktbuilder_data'
					)
				)
		);
		// all sktbuilder page
		$sktbuilder_pages = get_posts( $args );

		$pages = array();
		foreach ($sktbuilder_pages as $value) {
			if ($post->ID !== $value->ID) {
				$pages[] = array('title' => ( $value->post_title != '' ? $value->post_title : esc_html__( "No title", 'sktbuilder' ) ), 'url' => $this->getEditWithSktbuilderUrl( $value->ID ));
			}
		}

		return json_encode($pages, true);
	}
}
$sktbuilder = new Sktbuilder( );