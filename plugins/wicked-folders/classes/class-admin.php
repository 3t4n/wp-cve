<?php

namespace Wicked_Folders;

use Wicked_Folders;

// Disable direct load
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

final class Admin {

	private static $instance;

	private $admin_notices = array();

	private function __construct() {

		$post_types = Wicked_Folders::post_types();

		add_action( 'admin_enqueue_scripts',				array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_init',							array( $this, 'admin_init' ) );
		add_action( 'admin_menu',							array( $this, 'admin_menu' ), 10000 );
		add_action( 'pre_get_posts',						array( $this, 'pre_get_posts' ) );
		add_action( 'admin_notices', 						array( $this, 'admin_notices' ) );
		add_action( 'network_admin_notices', 				array( $this, 'admin_notices' ) );
		add_action( 'manage_posts_custom_column' , 			array( $this, 'post_custom_column_content' ), 10, 2 );
		add_action( 'restrict_manage_posts', 				array( $this, 'restrict_manage_posts' ) );
		add_action( 'wp_enqueue_media', 					array( $this, 'wp_enqueue_media' ) );
		add_action( 'admin_footer', 						array( $this, 'admin_footer' ) );

		add_filter( 'wpseo_primary_term_taxonomies', 		array( $this, 'wpseo_primary_term_taxonomies' ), 3, 10 );
		add_filter( 'wp_terms_checklist_args', 				array( $this, 'wp_terms_checklist_args' ), 10, 2 );
		add_filter( 'post_row_actions', 					array( $this, 'post_row_actions' ), 10, 2);
		add_filter( 'page_row_actions', 					array( $this, 'page_row_actions' ), 10, 2);
		add_filter( 'admin_body_class', 					array( $this, 'admin_body_class' ) );
		add_filter( 'update_footer', 						array( $this, 'update_footer' ), 20 );
		add_filter( 'manage_posts_columns', 				array( $this, 'manage_posts_columns' ) );
		add_filter( 'manage_pages_columns', 				array( $this, 'manage_posts_columns' ) );
		add_filter( 'post_column_taxonomy_links', 			array( $this, 'post_column_taxonomy_links' ), 10, 3 );

		// Add move-to-folder column to LifterLMS Lessons
		add_filter( 'manage_edit-lesson_columns', 			array( $this, 'manage_posts_columns' ) );
		add_filter( 'manage_edit-pretty-link_columns', 		array( $this, 'manage_posts_columns' ) );

		// Add move-to-folder to Ed School theme types
		add_filter( 'manage_edit-agc_course_columns', 			array( $this, 'manage_posts_columns' ), 100 );
		add_filter( 'manage_edit-layout_block_columns', 		array( $this, 'manage_posts_columns' ), 100 );

		// Add move-to-folder to Testimonial Rotator plugin types
		add_filter( 'manage_edit-testimonial_columns', 			array( $this, 'manage_posts_columns' ), 100 );
		add_filter( 'manage_edit-testimonial_rotator_columns', 	array( $this, 'manage_posts_columns' ), 100 );

		// Add move-to-folder to Woody Code Snippets types
		add_filter( 'manage_edit-wbcr-snippets_columns', 	array( $this, 'manage_posts_columns' ), 20 );

		add_filter( 'plugin_action_links_wicked-folders/wicked-folders.php', array( $this, 'plugin_action_links' ) );

		if ( in_array( 'page', $post_types ) ) {
			add_filter( 'manage_pages_page_wicked_page_folders_columns', 	array( $this, 'page_folder_view_columns' ) );
			add_action( 'manage_pages_custom_column', 						array( $this, 'page_custom_column_content' ), 10, 2 );
		}

	}

	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new Admin();
		}
		return self::$instance;
	}

	public function add_admin_notice( $message, $class = 'notice notice-success' ) {
		// notice-success
		// notice-warning
		// notice-error
		$this->admin_notices[] = array(
			'message' 	=> $message,
			'class' 	=> $class,
		);

	}

	public function admin_notices() {
		foreach ( $this->admin_notices as $notice ) {
			printf( '<div class="%1$s"><p>%2$s</p></div>', $notice['class'], $notice['message'] );
		}
	}

	public function admin_body_class( $classes ) {
		$state = $this->get_screen_state();

		if ( $this->is_folders_page() ) {
			$classes .= ' wicked-folders-page ';
		}

		if ( $this->is_folder_pane_enabled_page() ) {
			$classes .= ' wicked-folders-enabled ';
		}

		return $classes;
	}

	public function update_footer( $content ) {

		if ( $this->is_folders_page() ) {
			$content = '';
		}

		return $content;

	}

    public function admin_enqueue_scripts() {
		static $done = false;

		// Only run this once per request
		if ( $done ) return;

		global $typenow;

		$version 					= Wicked_Folders::plugin_version();
		$after_ajax_scripts 		= array();
		$is_woocommerce_active 		= false;
		$is_wpml_active 			= false;
		$is_tablepress_active 		= false;
		$is_wider_admin_menu_active = false;
		$in_footer 					= true;
		$dist_url 					= untrailingslashit( apply_filters( 'wicked_folders_build_directory_url', plugin_dir_url( dirname( __FILE__ ) ) . 'dist' ) );
		$deps 						= array(
			'jquery',
			'jquery-ui-resizable',
			'jquery-ui-draggable',
			'jquery-ui-droppable',
			'jquery-ui-sortable',
			'lodash',
			'wp-element',
			'wp-data',
			'wp-data-controls',
			'wp-polyfill',
			'wp-i18n',
			'wp-api-fetch',
			'wp-hooks',
			'wp-components'
		);

		if ( function_exists( 'is_plugin_active' ) ) {
			$is_woocommerce_active 		= is_plugin_active( 'woocommerce/woocommerce.php' );
			$is_wpml_active 			= is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' );
			$is_tablepress_active 		= is_plugin_active( 'tablepress/tablepress.php' );
			$is_wider_admin_menu_active = is_plugin_active( 'wider-admin-menu/wider-admin-menu.php' );
		}

		if ( function_exists( 'plugins_url' ) ) {
			if ( $is_wpml_active ) {
				$after_ajax_scripts[] = plugins_url( 'sitepress-multilingual-cms/res/js/post-edit-languages.js' );
			}

			if ( isset( $typenow ) ) {
				if ( $is_woocommerce_active && 'shop_order' == $typenow ) {
					$after_ajax_scripts[] = plugins_url( 'woocommerce/assets/js/admin/wc-enhanced-select.min.js' );
				}
			}

			if ( $is_tablepress_active && isset( $_GET['page'] ) && 'tablepress' == $_GET['page'] ) {
				$after_ajax_scripts[] = plugins_url( 'tablepress/admin/js/list.js' );
			}
		}

		// Load scripts in footer when Thrive Quiz Builder is active; for some
		// reason media library isn't loading when scripts are loaded in head
		if ( isset( $_GET['post_type'] ) && 'tqb_quiz' == $_GET['post_type'] ) {
			$in_footer = true;
		}

		$in_footer = apply_filters( 'wicked_folders_enqueue_scripts_in_footer', $in_footer );

		wp_register_script( 'wicked-folders-select2', plugin_dir_url( dirname( __FILE__ ) ) . 'vendor/select2/js/select2.full.min.js', array(), $version, $in_footer );
		wp_register_script( 'wicked-folders-admin', plugin_dir_url( dirname( __FILE__ ) ) . 'js/admin.js', array(), $version, $in_footer );

		wp_register_style( 'wicked-folders-select2', plugin_dir_url( dirname( __FILE__ ) ) . 'vendor/select2/css/select2.min.css', array(), $version );
		wp_register_style( 'wicked-folders-admin', plugin_dir_url( dirname( __FILE__ ) ) . 'css/admin.css', array(), $version );

		if ( defined( 'WICKED_FOLDERS_APP' ) ) {
			wp_register_script( 'wicked-folders-app', WICKED_FOLDERS_APP, $deps, $version, $in_footer );
		} else {
			wp_register_script( 'wicked-folders-app', "{$dist_url}/folders.js", $deps, $version, $in_footer );
			wp_register_style( 'wicked-folders-app', "{$dist_url}/folders.css", array(), $version );
		}

		// TODO: change to use i18n functions
		wp_localize_script( 'wicked-folders-app', 'wickedFoldersL10n', $this->get_l10n() );

		if ( $this->is_folder_pane_enabled_page() ) {
			// Note: get_screen_state uses get_current_screen which will lead to
			// errors when calling before get_current_screen is defined; therefore
			// this call has been moved inside of the current if condition (which
			// returns false if get_current_screen is undefined). This can happen
			// from calling wp_enqueue_media too early which some themes might do.
			// See https://wordpress.org/support/topic/fatal-error-after-upgrading-to-2-7-1/
			$state 					= $this->get_screen_state();
			$post_type 				= $this->get_current_screen_post_type();
			$folders 				= new Folder_Collection( $post_type, $state->sort_mode );
			$folder 				= Folder_Factory::get_folder( $state->folder, $post_type );
			$taxonomy 				= Wicked_Folders::get_tax_name( $post_type );

			// If the currently selected folder is a post hierarchy dynamic folder,
			// load the folder's ancestors (otherwise they won't be in the tree and
			// the selected folder won't be visible).
			if ( is_a( $folder, 'Wicked_Folders\Post_Hierarchy_Dynamic_Folder' ) ) {
				$folder->fetch();

				$folders->add( $folder );

				$ancestor_ids = $folder->get_ancestor_ids();

				unset( $ancestor_ids[ array_search( 'dynamic_root', $ancestor_ids ) ] );
				unset( $ancestor_ids[ array_search( 'dynamic_hierarchy_0', $ancestor_ids ) ] );

				foreach ( $ancestor_ids as $ancestor_id ) {
					$ancestor = Folder_Factory::get_folder( $ancestor_id, $post_type );
					$ancestor->fetch();
					
					$folders->add( $ancestor );
				}
			}

			$state->include_children = Wicked_Folders::include_children( $post_type );

			$state->maybe_change_selected_folder( $folders );
			$state->can_add_folders = apply_filters( 'wicked_folders_can_create_folders', true, get_current_user_id(), $taxonomy );

			wp_localize_script( 'wicked-folders-app', 'wickedFoldersSettings', array(
				'ajaxURL' 			=> admin_url( 'admin-ajax.php' ),
				'restURL' 			=> rest_url(),
				'afterAjaxScripts' 	=> apply_filters( 'wicked_folders_after_ajax_scripts', $after_ajax_scripts ),
				'isElementorActive' => isset( $_GET['action'] ) && 'elementor' == $_GET['action'] ? true : false,
				'instances' => array(
					'folderPane' => array(
						'folders' 	=> $folders,
						'state' 	=> $state,
						'postType' 	=> $post_type,
					),
				),
			) );

			wp_enqueue_style( 'wicked-folders-admin' );
			wp_enqueue_style( 'wicked-folders-select2' );
			wp_enqueue_style( 'wicked-folders-app' );

			/*
			wp_add_inline_script(
				'wicked-folders-app',
				'wp.apiFetch.use( wp.apiFetch.createPreloadingMiddleware( ' . wp_json_encode( array( "/wicked-folders/v1/folders?post_type={$post_type}" => $folders ) ) . ' ) )',
			);
			*/
			
			$css = "
				body.wp-admin.wicked-object-folder-pane #wpcontent {padding-left: " . (int ) ( $state->tree_pane_width + 11 ) . "px;}
				body.wp-admin.wicked-object-folder-pane #wpfooter {left: " . ( int ) ( $state->tree_pane_width - 6 ) . "px;}
				#wicked-folder-pane .wicked-content {width: " . ( int ) ( $state->tree_pane_width - 12 ) . "px;}
				#wicked-folder-pane .wicked-resizer {width: " . ( int ) $state->tree_pane_width . "px;}

				body.rtl.wp-admin.wicked-folder-pane #wpcontent {padding-left: 0; padding-right: " . ( int ) ( $state->tree_pane_width + 11 ) . "px;}
				body.rtl.wp-admin.wicked-folder-pane #wpfooter {left: 0; right: " . ( int ) ( $state->tree_pane_width - 6 ) . "px;}

				#wpwrap #e-admin-top-bar-root {width: calc(100% - " . ( int ) ( $state->tree_pane_width + 154 ) . "px);}
			";

			if ( $is_wider_admin_menu_active ) {
				$wider_admin_menu_settings = get_option( 'wpmwam_options' );

				if ( isset( $wider_admin_menu_settings['wpmwam_width'] ) ) {
					$menu_width = $wider_admin_menu_settings['wpmwam_width'];

					$css .= "
						#wicked-folder-pane .wicked-content,
						#wicked-folder-pane .wicked-resizer {left: " . ( int ) $menu_width . "px;}
					";
				}
			}
						
			//wp_add_inline_style( 'wicked-folders-admin', $css );
			wp_add_inline_style( 'wicked-folders-admin', "html {--wicked-folders-tree-pane-width: {$state->tree_pane_width}px;" );

			wp_enqueue_script( 'wicked-folders-admin' );
			wp_enqueue_script( 'wicked-folders-app' );
			wp_enqueue_script( 'wicked-folders-select2' );		
		}

		// The admin CSS is needed on a few non-folder pages also...
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;

		if ( ( isset( $_GET['page'] ) && 'wicked_folders_settings' == $_GET['page'] ) || 'wf_collection_policy' == $typenow || ( false !== $screen && isset( $screen->base ) && isset( $screen->action ) && 'media' == $screen->base && 'add' == $screen->action ) ) {
			wp_enqueue_style( 'wicked-folders-admin' );
		}

		$done = true;
	}

	public function wp_enqueue_media() {

		// TODO: refactor and enqueue scripts here as well rather than calling
		// admin_enqueue_scripts which may load unnecessary scripts
		$this->admin_enqueue_scripts();

	}

	public function get_screen_state( $screen_id = false, $user_id = false, $lang = false ) {
		$screen = get_current_screen();

		// TODO: consider statically caching screen states
		if ( ! $screen_id ) $screen_id = $screen ? get_current_screen()->id : false;
		if ( ! $user_id ) $user_id = get_current_user_id();
		if ( ! $lang ) $lang = Wicked_Folders::get_language();

		return new Screen_State( $screen_id, $user_id, $lang );

	}

    public function admin_init() {

 		$post_type = $this->get_current_screen_post_type();

		if ( $this->is_folders_page() ) {
			// $post_type is not set at this point yet when the post type is 'post'
			if ( ! $post_type ) $post_type = 'post';
			$slug = Wicked_Folders::get_tax_name( $post_type );
			add_filter( 'manage_' . $this->get_screen_id_by_menu_slug( $slug ) . '_columns', array( $this, 'post_folder_view_columns' ) );
			add_filter( 'manage_' . $this->get_screen_id_by_menu_slug( $slug ) . '_sortable_columns', array( $this, 'posts_sortable_columns' ) );
		}

		// Enable legacy folder pages for people who had the plugin installed
		// before folders were integrated into the post list pages
		$enable_folder_pages = get_option( 'wicked_folders_enable_folder_pages', null );

		if ( null === $enable_folder_pages ) {
			update_option( 'wicked_folders_enable_folder_pages', true );
		}

	}

	/**
	 * Returns the post type from the page querystring parameter.
	 */
	public static function folder_page_post_type() {
		$post_type = false;
		// Assumes page is in format wf_{$post_type}_folders
		$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
		if ( preg_match( '/^wf_([A-Z0-9_\-]*)_folders$/i', $page ) ) {
			// Remove wicked_ prefix
			$post_type = substr( $page, 3 );
			// Remove _folders suffix
			$post_type = substr( $post_type, 0, -8 );
		}
		return $post_type;
	}

	/**
	 * Returns the post type for the current screen.
	 */
	public static function get_current_screen_post_type() {
		global $typenow;

		$type = $typenow;
		$page = basename( $_SERVER['PHP_SELF'] );

		// $typenow isn't always set
		if ( ! $type && 'upload.php' == $page && empty( $_GET['page'] ) ) $type = 'attachment';

		// Assume post if we're on the edit page and no post type is available
		if ( ! $type && 'edit.php' == $page && empty( $_GET['page'] ) ) $type = 'post';

		if ( 'users.php' == $page && empty( $_GET['page'] ) ) $type = Wicked_Folders::get_user_post_type_name();

		if ( 'plugins.php' == $page && empty( $_GET['page'] ) ) $type = Wicked_Folders::get_plugin_post_type_name();

		if ( 'admin.php' == $page && isset( $_GET['page'] ) && ( 'gf_edit_forms' == $_GET['page'] || 'gf_new_form' == $_GET['page'] ) && empty( $_GET['id'] ) ) $type = Wicked_Folders::get_gravity_forms_form_post_type_name();

		if ( 'admin.php' == $page && isset( $_GET['page'] ) && 'gf_entries' == $_GET['page'] && empty( $_GET['lid'] ) ) $type = Wicked_Folders::get_gravity_forms_entry_post_type_name();

		if ( 'admin.php' == $page && isset( $_GET['page'] ) && 'tablepress' == $_GET['page'] && ( empty( $_GET['action'] ) || ( isset( $_GET['action'] ) && 'list' == $_GET['action'] ) ) ) $type = 'tablepress_table';

		if ( 'admin.php' == $page && isset( $_GET['page'] ) && 'wc-orders' == $_GET['page'] ) $type = 'shop_order';

		// Give pro version (and perhaps others) a chance to determine the post type
		$type = apply_filters( 'wicked_folders_get_current_screen_post_type', $type );

		return $type;
	}

	/**
	 * Returns a screen ID for a given admin menu slug.  Note: this function
	 * must be called in admin_init or later.
	 *
	 * @param $slug
	 *  The slug that was used when the page was registered with add_menu_page
	 *  or add_submenu_page.
	 *
	 * @return string
	 *  A screen ID.
	 */
	public static function get_screen_id_by_menu_slug( $slug ) {
		global $_parent_pages;
	    $parent = is_array( $_parent_pages ) && array_key_exists( $slug, $_parent_pages ) ? $_parent_pages[ $slug ] : '';
	    return get_plugin_page_hookname( $slug, $parent );
	}

	/**
	 * Checks if we are on the posts page in the admin and folders are enabled
	 * for the post type being viewed.
	 *
	 * @return bool
	 */
	public function is_folder_pane_enabled_page() {
		// Handle AJAX quick/inline edit scenario
		if ( isset( $_POST['action'] ) && 'inline-save' == $_POST['action'] &&  isset( $_POST['post_type'] ) && Wicked_Folders::enabled_for( $_POST['post_type'] ) ) {
			return true;
		}

		$enabled        = true;
		$type 			= $this->get_current_screen_post_type();
		$page 			= basename( $_SERVER['PHP_SELF'] );
		$allowed_pages  = array( 'edit.php', 'upload.php', 'users.php', 'plugins.php', 'admin.php' );

		// Only enable folder pane on certain pages
		if ( ! in_array( $page, $allowed_pages ) ) $enabled = false;

		$enabled = apply_filters( 'wicked_folders_enable_object_folder_pane', $enabled );

		if ( ! $enabled ) return false;

		return Wicked_Folders::enabled_for( $type );
	}

	public static function is_folders_page() {

		$post_types = Wicked_Folders::post_types();
		$screen 	= function_exists( 'get_current_screen' ) ? get_current_screen() : false;

		// AJAX requests
		if ( isset( $_GET['action'] ) && 'wicked_folders_get_contents' == $_GET['action'] ) {
			return true;
		}

		foreach ( $post_types as $post_type ) {
			if ( empty( $screen ) ) {
				// In case it's too early for get_current_screen()...
				if ( isset( $_GET['page'] ) && false !== strpos( $_GET['page'], Wicked_Folders::get_tax_name( $post_type ) ) ) {
					return true;
				}
			} elseif ( false !== strpos( $screen->id, Wicked_Folders::get_tax_name( $post_type ) ) ) {
				return true;
			}
		}

		return false;

	}

	/**
	 * WordPress admin_menu action.
	 */
    public function admin_menu() {

		$menu_items 			= array();
		$post_types 			= Wicked_Folders::post_type_objects();
		$enable_taxonomy_pages 	= get_option( 'wicked_folders_enable_taxonomy_pages', false );
		$filter_args 			= array(
			'post_types' => $post_types,
		);

		foreach ( $post_types as $post_type ) {
			if ( $enable_taxonomy_pages ) {
				// Folder management (i.e. folder taxonomy) menu item
				$taxonomy 	= Wicked_Folders::get_tax_name( $post_type->name );
				$menu_item 	= array(
					'parent_slug' 	=> 'edit.php?post_type=' . $post_type->name,
					'capability' 	=> 'edit_posts',
					'menu_slug' 	=> 'edit-tags.php?taxonomy=' . $taxonomy . '&post_type=' . $post_type->name,
					'page_title' 	=> sprintf( __( 'Manage %1$s Folders', 'wicked-folders' ), $post_type->labels->singular_name ),
					'menu_title' 	=> sprintf( __( 'Manage %1$s Folders', 'wicked-folders' ), $post_type->labels->singular_name ),
					'callback' 		=> null,
					'taxonomy' 		=> $taxonomy,
				);
				if ( 'post' == $post_type->name ) {
					$menu_item['parent_slug'] = 'edit.php';
				}
				if ( is_string( $post_type->show_in_menu ) ) {
					$menu_item['parent_slug'] = $post_type->show_in_menu;
				}
				if ( $post_type->_builtin ) {
					$menu_item['page_title'] = __( 'Manage Folders', 'wicked-folders' );
					$menu_item['menu_title'] = __( 'Manage Folders', 'wicked-folders' );
				}
				$menu_items[] = $menu_item;
			}
		}

		$menu_items = apply_filters( 'wicked_folders_admin_menu_items', $menu_items, $filter_args );

		foreach ( $menu_items as $menu_item ) {
			add_submenu_page( $menu_item['parent_slug'], $menu_item['page_title'], $menu_item['menu_title'], $menu_item['capability'], $menu_item['menu_slug'], $menu_item['callback'] );
		}

		// Add menu item for settings page
		$parent_slug 	= 'options-general.php';
		$page_title 	= __( 'Wicked Folders Settings', 'wicked-folders' );
		$menu_title 	= __( 'Wicked Folders', 'wicked-folders' );
		$capability 	= 'manage_options';
		$menu_slug 		= 'wicked_folders_settings';
		$callback 		= array( $this, 'settings_page' );

		add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback );
    }

	/**
	 * Column headers for 'post' type.
	 */
	public function manage_posts_columns( $columns ) {
		global $typenow;

		if ( $this->is_folders_page() ) {
			$columns = array(
				'wicked_move' 	=> '<div class="wicked-move-multiple" title="' . __( 'Move selected items', 'wicked-folders' ) . '"><span class="wicked-move-file dashicons dashicons-move"></span><div class="wicked-items"></div></div>',
				'cb' 			=> '<input type="checkbox" />',
				'title' 		=> 'Title',
				'author' 		=> 'Author',
				'date' 			=> 'Date',
			);
		} elseif ( $this->is_folder_pane_enabled_page() ) {
			$taxonomy = get_taxonomy( Wicked_Folders::get_tax_name( $typenow ) );

			$a = array( 'wicked_move' => '<div class="wicked-move-multiple" title="' . __( 'Move selected items', 'wicked-folders' ) . '"><span class="wicked-move-file dashicons dashicons-move"></span><div class="wicked-items"></div><span class="screen-reader-text">' . __( 'Move to Folder', 'wicked-folders' ) . '</span></div>' );
			$columns = $a + $columns;

			// Some plugins completely override the columns for their post type;
			// re-add the folder taxonomy columns for these instances
			if ( isset( $taxonomy->show_admin_column ) && $taxonomy->show_admin_column ) {
				$columns[ 'taxonomy-' . $taxonomy->name ] = $taxonomy->label;
			}
		}
		return $columns;
	}

	/**
	 * manage_pages_page_wicked_page_folders_columns filter.
	 *
	 * @return array
	 *  Array of columns when viewing pages in folder view.
	 */
	public function page_folder_view_columns( $columns ) {
		//$columns = apply_filters( 'manage_pages_columns', $columns );	
		$columns = array(
			'wicked_move' 	=> '<div class="wicked-move-multiple" title="' . __( 'Move selected items', 'wicked-folders' ) . '"><span class="wicked-move-file dashicons dashicons-move"></span><div class="wicked-items"></div></div>',
			'cb' 			=> '<input type="checkbox" />',
			'title' 		=> __( 'Title', 'wicked-folders' ),
			'author' 		=> __( 'Author', 'wicked-folders' ),
			'date' 			=> __( 'Date', 'wicked-folders' ),
			'wicked_sort' 	=> __( 'Sort', 'wicked-folders' ),
		);
		return $columns;
	}

	/**
	 * Column header filter for non-page post types.
	 */
	public function post_folder_view_columns( $columns ) {
		$columns = array(
			'wicked_move' 	=> '<div class="wicked-move-multiple" title="' . __( 'Move selected items', 'wicked-folders' ) . '"><span class="wicked-move-file dashicons dashicons-move"></span><div class="wicked-items"></div></div>',
			'cb' 			=> '<input type="checkbox" />',
			'title' 		=> __( 'Title', 'wicked-folders' ),
			'author' 		=> __( 'Author', 'wicked-folders' ),
			'date' 			=> __( 'Date', 'wicked-folders' ),
			'wicked_sort' 	=> __( 'Sort', 'wicked-folders' ),
		);
		return $columns;
	}

	public function page_custom_column_content( $column_name, $post_id ) {
		if ( 'wicked_move' == $column_name ) {
			$title = get_the_title();

			if ( ! $title ) $title = __( '(no title)', 'wordpress' );

			echo '<div class="wicked-move-multiple" data-object-id="' . esc_attr( $post_id ) . '"><span class="wicked-move-file dashicons dashicons-move"></span><div class="wicked-items"><div class="wicked-item" data-object-id="' . esc_attr( $post_id ) . '">' . esc_html( $title ) . '</div></div>';
		}
		if ( 'wicked_sort' == $column_name ) {
			echo '<a class="wicked-sort" href="#"><span class="dashicons dashicons-menu"></span></a>';
		}
	}

	public function post_custom_column_content( $column_name, $post_id ) {
		if ( 'wicked_move' == $column_name ) {
			$title = get_the_title();

			if ( ! $title ) $title = __( '(no title)', 'wordpress' );

			echo '<div class="wicked-move-multiple" data-object-id="' . esc_attr( $post_id ) . '"><span class="wicked-move-file dashicons dashicons-move"></span><div class="wicked-items"><div class="wicked-item" data-object-id="' . esc_attr( $post_id ) . '">' . esc_html( $title ) . '</div></div>';
		}

		if ( 'wicked_sort' == $column_name ) {
			echo '<a class="wicked-sort" href="#"><span class="dashicons dashicons-menu"></span></a>';
		}
	}

	public function posts_sortable_columns( $columns ) {
		$columns['wicked_sort'] = 'wicked_folder_order';
		return $columns;
	}

	public function settings_page() {

		$active_tab 	= 'general';
		$is_pro_active 	= is_plugin_active( 'wicked-folders-pro/wicked-folders-pro.php' );

		if ( ! empty( $_GET['tab'] ) ) {
			$active_tab = sanitize_text_field( $_GET['tab'] );
		}

		$tabs = array(
			array(
				'label' 	=> __( 'Settings', 'wicked-folders' ),
				'callback' 	=> array( $this, 'settings_page_general' ),
				'slug'		=> 'general',
			),
		);

		$tabs = apply_filters( 'wicked_folders_setting_tabs', $tabs );

		include( dirname( dirname( __FILE__ ) ) . '/admin-templates/settings-page.php' );

	}

	public function settings_page_general() {

		$is_pro_active 						= is_plugin_active( 'wicked-folders-pro/wicked-folders-pro.php' );
		$is_woocommerce_active 				= is_plugin_active( 'woocommerce/woocommerce.php' );
		$is_gravity_forms_active 			= is_plugin_active( 'gravityforms/gravityforms.php' );
		$enabled_posts_types 				= Wicked_Folders::post_types();
		$dynamic_folders_enabled_posts_types= Wicked_Folders::dynamic_folder_post_types();
		$license_key 						= get_option( 'wicked_folders_pro_license_key', false );
		$license_data 						= get_site_option( 'wicked_folders_pro_license_data' );
		$valid_license 						= isset( $license_data->license ) && 'valid' == $license_data->license ? true : false;
		$show_folder_contents_in_tree_view 	= get_option( 'wicked_folders_show_folder_contents_in_tree_view', false );
		$sync_upload_folder_dropdown 		= get_option( 'wicked_folders_sync_upload_folder_dropdown', false );
		$enable_folder_pages 				= get_option( 'wicked_folders_enable_folder_pages', false );
		$include_children 					= get_option( 'wicked_folders_include_children', false );
		$include_attachment_children 		= get_option( 'wicked_folders_include_attachment_children', false );
		$show_hierarchy_in_folder_column 	= get_option( 'wicked_folders_show_hierarchy_in_folder_column', false );
		$show_unassigned_folder 			= get_option( 'wicked_folders_show_unassigned_folder', true );
		$show_folder_search 				= get_option( 'wicked_folders_show_folder_search', true );
		$show_item_counts 					= get_option( 'wicked_folders_show_item_counts', true );
		$show_breadcrumbs 					= get_option( 'wicked_folders_show_breadcrumbs', true );
		$enable_ajax_nav 					= get_option( 'wicked_folders_enable_ajax_nav', true );
		$unsupported_types 					= array( 'shop_webhook', 'wf_collection_policy', 'nf_sub', 'wp_navigation' );
		$attachment_post_type 				= get_post_type_object( 'attachment' );
		$post_types 						= get_post_types( array(
			'show_ui' => true,
		), 'objects' );
		$pro_post_types 					= array(
			'attachment',
			'acf',
			'shop_order',
			'shop_coupon',
			'tablepress_table',
			Wicked_Folders::get_user_post_type_name(),
			Wicked_Folders::get_plugin_post_type_name(),
			Wicked_Folders::get_gravity_forms_form_post_type_name(),
			Wicked_Folders::get_gravity_forms_entry_post_type_name(),
		);

		// Add stub post types
		if ( $user_stub_post_type = get_post_type_object( Wicked_Folders::get_user_post_type_name() ) ) {
			// Settings page only shows post types where show_ui is true
			$user_stub_post_type->show_ui = true;

			$post_types[] = $user_stub_post_type;
		}

		if ( $plugin_stub_post_type = get_post_type_object( Wicked_Folders::get_plugin_post_type_name() ) ) {
			// Settings page only shows post types where show_ui is true
			$plugin_stub_post_type->show_ui = true;

			$post_types[] = $plugin_stub_post_type;
		}

		if ( $is_gravity_forms_active && $gravity_form_stub_post_type = get_post_type_object( Wicked_Folders::get_gravity_forms_form_post_type_name() ) ) {
			// Settings page only shows post types where show_ui is true
			$gravity_form_stub_post_type->show_ui = true;

			$post_types[] = $gravity_form_stub_post_type;
		}

		if ( $is_gravity_forms_active && $gravity_entry_stub_post_type = get_post_type_object( Wicked_Folders::get_gravity_forms_entry_post_type_name() ) ) {
			// Settings page only shows post types where show_ui is true
			$gravity_entry_stub_post_type->show_ui = true;

			$post_types[] = $gravity_entry_stub_post_type;
		}

		if ( $tablepress_post_type = get_post_type_object( 'tablepress_table' ) ) {
			$tablepress_post_type->show_ui = true;

			$post_types[] = $tablepress_post_type;
		}

		// Exclude unsupported types
		foreach ( $unsupported_types as $type ) {
			unset( $post_types[ $type ] );
		}

		if ( isset( $post_types['elementor_library'] ) ) {
			$post_types['elementor_library']->label = __( 'Elementor Templates', 'wicked-folders' );
		}

		if ( $is_pro_active && ! is_multisite() ) {
			// TODO: replace with filter
			$license_status = Wicked_Folders\Pro\Plugin::get_license_status_text();
		}

		$post_types = apply_filters( 'wicked_folders_settings_post_types', $post_types );

		usort( $post_types, array( $this, 'sort_post_types_compare' ) );

		include( dirname( dirname( __FILE__ ) ) . '/admin-templates/settings-page-general.php' );

	}

	public function pre_get_posts( $query ) {
		// Only filter admin queries
		if ( is_admin() ) {

			// Initalize variables
			$filter_query 		= false;
			$folder_id 			= false;
			$taxonomy 			= false;
			$action 			= isset( $_REQUEST['action'] ) ? sanitize_key( $_REQUEST['action'] ) : false;
			$post_type 			= $query->get( 'post_type' );
			$include_children 	= false;

			// Only filter certain queries...
			if ( $query->is_main_query() ) $filter_query = true;
			if ( 'query-attachments' == $action && isset( $_REQUEST['query']['wf_attachment_folders'] ) ) $filter_query  = true;

			// Skip all other queries
			if ( ! $filter_query ) return;

			if ( Wicked_Folders::enabled_for( $post_type ) ) {
				$taxonomy   = Wicked_Folders::get_tax_name( $post_type );
				$folder_id  = isset( $_GET['folder'] ) ? sanitize_text_field( $_GET['folder'] ) : false;

				// Check for taxonomy filter
				if ( isset( $_GET[ $taxonomy ] ) ) {
					$folder_id = sanitize_text_field( $_GET[ $taxonomy ] );
				}

				// Folder parameter is named differently on post list pages
				if ( isset( $_GET["wicked_{$post_type}_folder_filter"] ) ) {
					$folder_id = sanitize_text_field( $_GET["wicked_{$post_type}_folder_filter"] );
				}

				// Folder parameter in different in query attachment requests
				if ( isset( $_REQUEST['query']['wf_attachment_folders'] ) ) {
					$folder_id = sanitize_text_field( $_REQUEST['query']['wf_attachment_folders'] );
				}

				// If we don't have a folder, check the screen state
				if ( false === $folder_id && $this->is_folder_pane_enabled_page() ) {
					$state 			= $this->get_screen_state();
					$folder_id 		= $state->folder;
				}

                // Nothing to do if we don't have a folder
                if ( ! $folder_id ) return;

                // Get the folder object
                $folder = Folder_Factory::get_folder( $folder_id, $post_type );

				// Term folders
				if ( $folder && 'Wicked_Folders\Term_Folder' == get_class( $folder) ) {
					$include_children = Wicked_Folders::include_children( $post_type, $folder->id );

					// If the query is being filtered by a taxonomy query string
					// parameter, make sure it respects the include children
					// setting (by default, WordPress includes child terms when
					// the query is filtered via the taxonomy query string
					// parameter)
					if ( isset( $_GET[ $taxonomy ] ) ) {
						if ( isset( $query->tax_query->queries ) && is_array( $query->tax_query->queries ) ) {
							$tax_queries = $query->tax_query->queries;

							for ( $index = count( $tax_queries ) - 1; $index > -1; $index-- ) {
								if ( $tax_queries[ $index ]['taxonomy'] == $taxonomy ) {
									$tax_queries[ $index ]['include_children'] = $include_children;
								}
							}

							$query->set( 'tax_query', $tax_queries );
						}
					} else {
						$tax_query = array(
							array(
								'taxonomy' 			=> $taxonomy,
								'field' 			=> 'term_id',
								'terms' 			=> $folder->id,
								'include_children' 	=> $include_children,
							),
						);

						$query->set( 'tax_query', $tax_query );
					}

					if ( isset( $query->tax_query->queries ) && is_array( $query->tax_query->queries ) ) {
						foreach ( $query->tax_query->queries as $index => $tax_query ) {
							if ( $tax_query['taxonomy'] == $taxonomy ) {
								$query->tax_query->queries[ $index ]['include_children'] = $include_children;
							}
						}
					}
				}

				// Dynamic folders
				if ( $folder && $folder->is_dynamic ) {
					// Folder tax queries won't work with dynamic folders so remove
					Wicked_Folders::remove_tax_query( $query, 'wf_attachment_folders' );

					$folder->pre_get_posts( $query );
				}

				$can_view_others_items = apply_filters( 'wicked_folders_can_view_others_items', true, get_current_user_id(), $taxonomy );

				if ( ! $can_view_others_items ) {
					$query->set( 'author', get_current_user_id() );
				}

				// It appears that Polylang doesn't always filter posts when
				// where there isn't a 'lang' parameter in the URL.  When
				// returning to a folder, the 'lang' parameter usually isn't
				// present and items for all languages are displayed.  Add a tax
				// query for the language to fix this.

				// Update 2021-10-26: This no longer appears to be working and
				// is causing all folders to be empty on sites that use Polylang.
				// Removing for now
				/*
				if ( $folder && function_exists( 'pll_current_language' ) ) {
					$tax_query = $query->get( 'tax_query' );
					$tax_query = is_array( $tax_query ) ? $tax_query : array();

					$tax_query[] = array(
						'taxonomy' 	=> 'language',
						'field' 	=> 'slug',
						'operator' 	=> 'IN',
						'terms' 	=> array( pll_current_language() ),
					);

					$query->set( 'tax_query', $tax_query );
				}
				*/
			}

		}
	}

	public function post_row_actions( $actions, $post ) {
		if ( $this->is_folders_page() ) {
			if ( isset( $actions['inline hide-if-no-js'] ) ) {
				unset( $actions['inline hide-if-no-js'] );
			}
		}

		return $actions;
	}

	public function page_row_actions( $actions, $post ) {
		if ( $this->is_folders_page() ) {
			if ( isset( $actions['inline hide-if-no-js'] ) ) {
				unset( $actions['inline hide-if-no-js'] );
			}
		}

		return $actions;
	}

	/**
	 * wp_terms_checklist_args filter.
	 */
	public function wp_terms_checklist_args( $args, $post_id ) {
		$post_types = Wicked_Folders::post_types();

		// WordPress displays hierarchical taxonomies as a flat list in the
		// category meta box when some categories are selected; disable this to
		// preserve the folder hierarchy in the folder category meta boxes
		foreach ( $post_types as $post_type ) {
			if ( isset( $args['taxonomy'] ) ) {
				if ( $args['taxonomy'] == Wicked_Folders::get_tax_name( $post_type ) ) {
					$args['checked_ontop'] = false;
				}
			}
		}

		return $args;
	}

	/**
	 * wpseo_primary_term_taxonomies filter.
	 */
	public function wpseo_primary_term_taxonomies( $taxonomies, $post_type, $all_taxonomies ) {

		$folder_taxonomies = Wicked_Folders::taxonomies();

		// Remove Yoast primary category feature from folder taxonomies
		foreach ( $folder_taxonomies as $taxonomy ) {
			if ( isset( $taxonomies[ $taxonomy ] ) ) {
				unset( $taxonomies[ $taxonomy ] );
			}
		}

		return $taxonomies;

	}

	/**
	 * Handles saving plugin settings.
	 */
	public function save_settings() {

		$action = isset( $_REQUEST['action'] ) ? sanitize_key( $_REQUEST['action'] ) : false;

		if ( 'wicked_folders_save_settings' == $action && wp_verify_nonce( $_REQUEST['nonce'], 'wicked_folders_save_settings' ) ) {
			// Only handle save requests
			if ( isset( $_POST['submit'] ) ) {
				$post_types 						= isset( $_POST['post_type'] ) ? array_map( 'sanitize_key', $_POST['post_type'] ) : array();
				$dynamic_folder_post_types 			= isset( $_POST['dynamic_folder_post_type'] ) ? array_map( 'sanitize_key', $_POST['dynamic_folder_post_type'] )  : array();
				$show_folder_contents_in_tree_view 	= isset( $_POST['show_folder_contents_in_tree_view'] );
				$sync_upload_folder_dropdown 		= isset( $_POST['sync_upload_folder_dropdown'] );
				$enable_folder_pages 				= isset( $_POST['enable_folder_pages'] );
				$include_children 					= isset( $_POST['include_children'] );
				$include_attachment_children 		= isset( $_POST['include_attachment_children'] );
				$show_hierarchy_in_folder_column 	= isset( $_POST['show_hierarchy_in_folder_column'] );
				$show_unassigned_folder 			= isset( $_POST['show_unassigned_folder'] );
				$show_folder_search 				= isset( $_POST['show_folder_search'] );
				$show_item_counts 					= isset( $_POST['show_item_counts'] );
				$show_breadcrumbs 					= isset( $_POST['show_breadcrumbs'] );
				$enable_ajax_nav 					= isset( $_POST['enable_ajax_nav'] );

				add_option( 'wicked_folders_show_breadcrumbs', $show_breadcrumbs );

				// Note: booleans are cast to integers because passing false
				// when the option doesn't already exist will result in
				// WordPress not adding the option (and hence the option
				// setting not getting saved)
				update_option( 'wicked_folders_post_types', $post_types );
				update_option( 'wicked_folders_dynamic_folder_post_types', $dynamic_folder_post_types );
				update_option( 'wicked_folders_show_folder_contents_in_tree_view', ( int ) $show_folder_contents_in_tree_view );
				update_option( 'wicked_folders_sync_upload_folder_dropdown', ( int ) $sync_upload_folder_dropdown );
				update_option( 'wicked_folders_enable_folder_pages', ( int ) $enable_folder_pages );
				update_option( 'wicked_folders_include_children', ( int ) $include_children );
				update_option( 'wicked_folders_include_attachment_children', ( int ) $include_attachment_children );
				update_option( 'wicked_folders_show_hierarchy_in_folder_column', ( int ) $show_hierarchy_in_folder_column );
				update_option( 'wicked_folders_show_unassigned_folder', ( int ) $show_unassigned_folder );
				update_option( 'wicked_folders_show_folder_search', ( int ) $show_folder_search );
				update_option( 'wicked_folders_show_item_counts', ( int ) $show_item_counts );
				update_option( 'wicked_folders_show_breadcrumbs', ( int ) $show_breadcrumbs );
				update_option( 'wicked_folders_enable_ajax_nav', ( int ) $enable_ajax_nav );

				$this->add_admin_notice( __( 'Your changes have been saved.', 'wicked-folders' ) );
			}
		}

	}

	public function restrict_manage_posts( $post_type ) {

		if ( $post_type && Wicked_Folders::enabled_for( $post_type ) ) {

			$folder = 0;

			if ( isset( $_GET["wicked_{$post_type}_folder_filter"] ) ) {
				$folder = ( int ) $_GET["wicked_{$post_type}_folder_filter"];
			}

			wp_dropdown_categories( array(
				'orderby'           => 'name',
				'order'             => 'ASC',
				'show_option_none'  => __( 'All folders', 'wicked-folders' ),
				'taxonomy'          => Wicked_Folders::get_tax_name( $post_type ),
				'depth'             => 0,
				'hierarchical'      => true,
				'hide_empty'        => false,
				'option_none_value' => 0,
				'name' 				=> "wicked_{$post_type}_folder_filter",
				'id' 				=> "wicked-{$post_type}-folder-filter",
				'selected' 			=> $folder,
			) );
		}

	}

	public function plugin_action_links( $links ) {

        $settings_link = '<a href="' . esc_url( menu_page_url( 'wicked_folders_settings', 0 ) ) . '">' . __( 'Settings', 'wicked-folders' ) . '</a>';

        array_unshift( $links, $settings_link );

        return $links;

    }

	public function admin_footer() {
		$screen = get_current_screen();

		// Don't output the folder pane on the media grid view page; the folder
		// pane is part of the media library itself
		if ( 'upload' == $screen->base && 'grid' == $this->get_media_library_mode() ) return;

		// Only add folder pane to edit page
		if ( $this->is_folder_pane_enabled_page() ) {
			echo '<div id="wicked-folder-pane"></div>';
		}
	}

	/**
	 * Helper function that loads the children of lazy folders if the folder is
	 * expanded.
	 *
	 * @param array $folders
	 *  An array of Wicked_Folder objects.
	 * @param array $expanded_folders
	 *  An array of folder IDs.
	 * @return array
	 *  An array of Wicked_Folder objects.
	 */
	public function get_expanded_lazy_dynamic_folders( $folders, $expanded_folders ) {
		$lazy_folders = array();

		// Loop through folders
		foreach ( $folders as $folder ) {
			// We're only worried about lazy dynamic folders
			if ( true == $folder->lazy && is_a( $folder, 'Wicked_Folders\Dynamic_Folder' ) ) {
				// Only fetch children if the folder *and* its parent is expanded
				if ( in_array( $folder->id, $expanded_folders ) && in_array( $folder->parent, $expanded_folders ) ) {
				//if ( in_array( $folder->id, $expanded_folders ) ) {
					// Get the folder's children
					$child_folders = $folder->get_child_folders();

					// Get the children's children
					$child_expanded_folders = $this->get_expanded_lazy_dynamic_folders( $child_folders, $expanded_folders );

					// Merge them all together
					$lazy_folders = array_merge( $lazy_folders, $child_folders, $child_expanded_folders );
				}
			}
		}

		return $lazy_folders;
	}

	/**
	 * Helper function to get the mode that the media library is in.
	 */
	public function get_media_library_mode() {
		$mode  = get_user_option( 'media_library_mode', get_current_user_id() ) ? get_user_option( 'media_library_mode', get_current_user_id() ) : 'grid';
		$modes = array( 'grid', 'list' );

		if ( isset( $_GET['mode'] ) && in_array( $_GET['mode'], $modes ) ) {
			$mode = sanitize_text_field( $_GET['mode'] );
		}

		return $mode;
	}

	/**
	 * 'post_column_taxonomy_links' filter.
	 *
	 */
	public function post_column_taxonomy_links( $term_links, $taxonomy, $terms = array() ) {
		// This should be false if not a folder taxonomy
		$post_type = Wicked_Folders::get_post_name_from_tax_name( $taxonomy );

		if ( $post_type ) {
			if ( true == get_option( 'wicked_folders_show_hierarchy_in_folder_column', false ) ) {
				$term_links 		= array();
				$taxonomy_object 	= get_taxonomy( $taxonomy );
				$separator 			= apply_filters( 'wicked_folders_breadcrumb_separator', ' <span class="wicked-folders-breacrumb-separator">&rsaquo;</span> ' );

				foreach ( $terms as $term ) {
					$link_class = '';
					$crumbs 	= array();
					$url_args 	= array();
					$parents 	= get_ancestors( $term->term_id, $taxonomy, 'taxonomy' );
					$parents 	= array_reverse( $parents );
					$parents[] 	= $term->term_id;

					foreach ( $parents as $parent_term_id ) {
						$parent_term = get_term( $parent_term_id, $taxonomy );

						// Note: much of the following logic is copied from
						// WP_Posts_List_Table::column_default()
						if ( 'post' != $post_type ) {
							$url_args['post_type'] = $post_type;
						}

						if ( $taxonomy_object->query_var ) {
							$url_args[ $taxonomy_object->query_var ] = $parent_term->slug;
						} else {
							$url_args['taxonomy'] = $taxonomy;
							$url_args['term']     = $parent_term->slug;
						}

						if ( $term->term_id == $parent_term_id ) {
							$link_class = ' class="wicked-folders-in-folder"';
						}

						$url 	= add_query_arg( $url_args, 'edit.php' );

						$label 	= esc_html( sanitize_term_field( 'name', $parent_term->name, $parent_term->term_id, $taxonomy, 'display' ) );

						$crumbs[] = sprintf(
							'<a%s href="%s">%s</a>',
							$link_class,
							esc_url( $url ),
							$label
						);
					}

					$term_links[] = '<span class="wicked-folders-taxonomy-breadcrumb-links">' . join( $separator, $crumbs ) . '</span>';

					$term_links = apply_filters( 'wicked_folders_post_column_taxonomy_links', $term_links, $taxonomy, $terms );
				}
			}
		}

		return $term_links;
	}

	private function sort_post_types_compare( $a, $b ) {
		return strcmp( $a->label, $b->label );
	}

	private function get_l10n() {
		return array(
			'allMedia' 					=> __( 'All media', 'wicked-folders' ),
			'allFolders' 				=> __( 'All folders', 'wicked-folders' ),
			'delete' 					=> __( 'Delete', 'wicked-folders' ),
			'folder' 					=> __( 'Folder', 'wicked-folders' ),
			'addNewFolderLink' 			=> __( 'Add New Folder', 'wicked-folders' ),
			'editFolderLink' 			=> __( 'Edit Folder', 'wicked-folders' ),
			'cloneFolderLink' 			=> __( 'Clone Folder', 'wicked-folders' ),
			'deleteFolderLink' 			=> __( 'Delete Folder', 'wicked-folders' ),
			'folderSelectDefault' 		=> __( 'Parent Folder', 'wicked-folders' ),
			'expandAllFoldersLink' 		=> __( 'Expand All', 'wicked-folders' ),
			'collapseAllFoldersLink' 	=> __( 'Collapse All', 'wicked-folders' ),
			'save' 						=> __( 'Save', 'wicked-folders' ),
			'deleteFolderConfirmation' 	=> __( "Are you sure you want to delete the selected folder? Sub folders will be assigned to the folder's parent. Items in the folder will not be deleted.", 'wicked-folders' ),
			'hideAssignedItems' 		=> __( 'Hide assigned items', 'wicked-folders' ),
			'cloneFolderTooltip' 		=> __( 'Creates a copy of the currently selected folder containing the same items.', 'wicked-folders' ),
			'cloneFolderSuccess' 		=> __( 'Successfully cloned folder.', 'wicked-folders' ),
			'cloneChildFolders' 		=> __( 'Clone child folders also', 'wicked-folders' ),
			'cloneChildFoldersTooltip' 	=> __( 'If checked, descendant folders of the currently selected folder will also be cloned. Otherwise, only the currently selected folder will be cloned.', 'wicked-folders' ),
			'hideAssignedItemsTooltip' 	=> __( "Check this box to hide items that have already been assigned to one or more folders.  This can be useful for determining which items you haven't already placed in a folder.", 'wicked-folders' ),
			'folders' 					=> __( 'Folders', 'wicked-folders' ),
			'attachmentFolders' 		=> __( 'Attachment Folders', 'wicked-folders' ),
			'toggleFolders'				=> __( 'Toggle folders', 'wicked-folders' ),
			'cancel'					=> __( 'Cancel', 'wicked-folders' ),
			'folderName'				=> __( 'Folder Name', 'wicked-folders' ),
			'assignToFolder'			=> __( 'Assign to folder...', 'wicked-folders' ),
			'errorFetchingChildFolders' => __( "An error occurred while fetching the folder's children.", 'wicked-folders' ),
			'confirmUnassign' 			=> __( 'This will unassign the selected item(s) from all folders.  Are you sure you want to continue?', 'wicked-folders' ),
			'navigationFailure' 		=> __( 'An error occurred while attempting to navigate to the folder.  Please see following console messages for more details.', 'wicked-folders' ),
			'settings' 					=> __( 'Settings', 'wicked-folders' ),
			'owner' 					=> __( 'Owner', 'wicked-folders' ),
		);
	}
}
