<?php
/**
 * Slider Pro admin class.
 * 
 * @since 4.0.0
 */
class BQW_SliderPro_Admin {

	/**
	 * Current class instance.
	 * 
	 * @since 4.0.0
	 * 
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Stores the hook suffixes for the plugin's admin pages.
	 * 
	 * @since 4.0.0
	 * 
	 * @var array
	 */
	protected $plugin_screen_hook_suffixes = array();

	/**
	 * Current class instance of the public Slider Pro class.
	 * 
	 * @since 4.0.0
	 * 
	 * @var object
	 */
	protected $plugin = null;

	/**
	 * Plugin slug.
	 * 
	 * @since 4.0.0
	 * 
	 * @var string
	 */
	protected $plugin_slug = '';

	/**
	 * Initialize the admin by registering the required actions.
	 *
	 * @since 4.0.0
	 */
	private function __construct() {
		$this->plugin = BQW_SliderPro::get_instance();
		$this->plugin_slug = $this->plugin->get_plugin_slug();

		// load the admin CSS and JavaScript
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		add_action( 'wp_ajax_sliderpro_get_slider_data', array( $this, 'ajax_get_slider_data' ) );
		add_action( 'wp_ajax_sliderpro_save_slider', array( $this, 'ajax_save_slider' ) );
		add_action( 'wp_ajax_sliderpro_preview_slider', array( $this, 'ajax_preview_slider' ) );
		add_action( 'wp_ajax_sliderpro_update_presets', array( $this, 'ajax_update_presets' ) );
		add_action( 'wp_ajax_sliderpro_get_preset_settings', array( $this, 'ajax_get_preset_settings' ) );
		add_action( 'wp_ajax_sliderpro_get_breakpoints_preset', array( $this, 'ajax_get_breakpoints_preset' ) );
		add_action( 'wp_ajax_sliderpro_delete_slider', array( $this, 'ajax_delete_slider' ) );
		add_action( 'wp_ajax_sliderpro_duplicate_slider', array( $this, 'ajax_duplicate_slider' ) );
		add_action( 'wp_ajax_sliderpro_export_slider', array( $this, 'ajax_export_slider' ) );
		add_action( 'wp_ajax_sliderpro_import_slider', array( $this, 'ajax_import_slider' ) );
		add_action( 'wp_ajax_sliderpro_add_slides', array( $this, 'ajax_add_slides' ) );
		add_action( 'wp_ajax_sliderpro_load_main_image_editor', array( $this, 'ajax_load_main_image_editor' ) );
		add_action( 'wp_ajax_sliderpro_load_thumbnail_editor', array( $this, 'ajax_load_thumbnail_editor' ) );
		add_action( 'wp_ajax_sliderpro_load_caption_editor', array( $this, 'ajax_load_caption_editor' ) );
		add_action( 'wp_ajax_sliderpro_load_html_editor', array( $this, 'ajax_load_html_editor' ) );
		add_action( 'wp_ajax_sliderpro_load_layers_editor', array( $this, 'ajax_load_layers_editor' ) );
		add_action( 'wp_ajax_sliderpro_add_layer_settings', array( $this, 'ajax_add_layer_settings' ) );
		add_action( 'wp_ajax_sliderpro_load_settings_editor', array( $this, 'ajax_load_settings_editor' ) );
		add_action( 'wp_ajax_sliderpro_load_content_type_settings', array( $this, 'ajax_load_content_type_settings' ) );
		add_action( 'wp_ajax_sliderpro_add_breakpoint', array( $this, 'ajax_add_breakpoint' ) );
		add_action( 'wp_ajax_sliderpro_add_breakpoint_setting', array( $this, 'ajax_add_breakpoint_setting' ) );
		add_action( 'wp_ajax_sliderpro_get_taxonomies', array( $this, 'ajax_get_taxonomies' ) );
		add_action( 'wp_ajax_sliderpro_clear_all_cache', array( $this, 'ajax_clear_all_cache' ) );
		add_action( 'wp_ajax_sliderpro_close_getting_started', array( $this, 'ajax_close_getting_started' ) );
		add_action( 'wp_ajax_sliderpro_close_image_size_warning', array( $this, 'ajax_close_image_size_warning' ) );
		add_action( 'wp_ajax_sliderpro_close_custom_css_js_warning', array( $this, 'ajax_close_custom_css_js_warning' ) );
	}

	/**
	 * Return the current class instance.
	 *
	 * @since 4.0.0
	 * 
	 * @return object The instance of the current class.
	 */
	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Returns the hook suffixes for the plugin's admin pages.
	 *
	 * @since 4.8.0
	 */
	public function get_plugin_screen_hook_suffixes() {
		return $this->plugin_screen_hook_suffixes;
	}

	/**
	 * Adds to the list of screen hook suffixes.
	 *
	 * @since 4.8.0
	 */
	public function add_plugin_screen_hook_suffix( $screen_hook_suffix ) {
		return $this->plugin_screen_hook_suffixes[] = $screen_hook_suffix;
	}

	/**
	 * Loads the admin CSS files.
	 *
	 * It loads the public and admin CSS, and also the public custom CSS.
	 *
	 * @since 4.0.0
	 */
	public function enqueue_admin_styles() {
		if ( ! isset( $this->plugin_screen_hook_suffixes ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( in_array( $screen->id, $this->plugin_screen_hook_suffixes ) ) {
			if ( get_option( 'sliderpro_load_unminified_scripts' ) == true ) {
				wp_enqueue_style( $this->plugin_slug . '-admin-style', plugins_url( 'admin/assets/css/sliderpro-admin.css', dirname( __FILE__ ) ), array(), BQW_SliderPro::VERSION );
				wp_enqueue_style( $this->plugin_slug . '-plugin-style', plugins_url( 'public/assets/css/slider-pro.css', dirname( __FILE__ ) ), array(), BQW_SliderPro::VERSION );
				wp_enqueue_style( $this->plugin_slug . '-lightbox-style', plugins_url( 'public/assets/libs/fancybox/jquery.fancybox.css', dirname( __FILE__ ) ), array(), BQW_SliderPro::VERSION );
			} else {
				wp_enqueue_style( $this->plugin_slug . '-admin-style', plugins_url( 'admin/assets/css/sliderpro-admin.min.css', dirname( __FILE__ ) ), array(), BQW_SliderPro::VERSION );
				wp_enqueue_style( $this->plugin_slug . '-plugin-style', plugins_url( 'public/assets/css/slider-pro.min.css', dirname( __FILE__ ) ), array(), BQW_SliderPro::VERSION );
				wp_enqueue_style( $this->plugin_slug . '-lightbox-style', plugins_url( 'public/assets/libs/fancybox/jquery.fancybox.min.css', dirname( __FILE__ ) ), array(), BQW_SliderPro::VERSION );
			}
			
			$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : -1;

			if ( strpos( $screen->id, $this->plugin_slug . '-new' ) || $id !== -1 ) {
				wp_enqueue_style( 'wp-codemirror' );
			}

			if ( get_option( 'sliderpro_is_custom_css') == true ) {
				wp_add_inline_style( $this->plugin_slug . '-plugin-style', stripslashes( get_option( 'sliderpro_custom_css' ) ) );
			}
		}
	}

	/**
	 * Loads the admin JS files.
	 *
	 * It loads the public and admin JS, and also the public custom JS.
	 * Also, it passes the PHP variables to the admin JS file.
	 *
	 * @since 4.0.0
	 */
	public function enqueue_admin_scripts() {
		if ( ! isset( $this->plugin_screen_hook_suffixes ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( in_array( $screen->id, $this->plugin_screen_hook_suffixes ) ) {
			if ( function_exists( 'wp_enqueue_media' ) ) {
		    	wp_enqueue_media();
			}
			
			if ( get_option( 'sliderpro_load_unminified_scripts' ) == true ) {
				wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'admin/assets/js/sliderpro-admin.js', dirname( __FILE__ ) ), array( 'jquery' ), BQW_SliderPro::VERSION );
				wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'public/assets/js/jquery.sliderPro.js', dirname( __FILE__ ) ), array( 'jquery' ), BQW_SliderPro::VERSION );
				wp_enqueue_script( $this->plugin_slug . '-lightbox-script', plugins_url( 'public/assets/libs/fancybox/jquery.fancybox.js', dirname( __FILE__ ) ), array(), BQW_SliderPro::VERSION );
			} else {
				wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'admin/assets/js/sliderpro-admin.min.js', dirname( __FILE__ ) ), array( 'jquery' ), BQW_SliderPro::VERSION );
				wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'public/assets/js/jquery.sliderPro.min.js', dirname( __FILE__ ) ), array( 'jquery' ), BQW_SliderPro::VERSION );
				wp_enqueue_script( $this->plugin_slug . '-lightbox-script', plugins_url( 'public/assets/libs/fancybox/jquery.fancybox.min.js', dirname( __FILE__ ) ), array(), BQW_SliderPro::VERSION );
			}

			$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : -1;

			if ( function_exists('wp_enqueue_code_editor') && ( strpos( $screen->id, $this->plugin_slug . '-new' ) || $id !== -1 ) ) {
				wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
			}

			wp_localize_script( $this->plugin_slug . '-admin-script', 'sp_js_vars', array(
				'admin' => admin_url( 'admin.php' ),
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'plugin' => plugins_url( '', dirname( __FILE__ ) ),
				'page' => isset( $_GET['page'] ) && ( $_GET['page'] === 'sliderpro-new' || ( isset( $_GET['id'] ) && isset( $_GET['action'] ) && $_GET['action'] === 'edit' ) ) ? 'single' : 'all',
				'id' => $id,
				'lad_nonce' => wp_create_nonce( 'load-slider-data' . $id ),
				'sa_nonce' => wp_create_nonce( 'save-slider' . $id ),
				'cp_nonce' => wp_create_nonce( 'close-panel' ),
				'no_image' => __( 'Click to add image', 'sliderpro' ),
				'remove_custom_css_js_warning' => __( 'Are you sure you want to remove the existing custom CSS and/or JavaScript? <br/> Only do this after you\'ve copied the existing code in another place.', 'sliderpro' ),
				'posts_slides' => __( 'Posts slides', 'sliderpro' ),
				'gallery_slides' => __( 'Gallery slides', 'sliderpro' ),
				'flickr_slides' => __( 'Flickr slides', 'sliderpro' ),
				'slider_delete' => __( 'Are you sure you want to delete this slider?', 'sliderpro' ),
				'slide_delete' => __( 'Are you sure you want to delete this slide?', 'sliderpro' ),
				'preset_name' => __( 'Preset Name:', 'sliderpro' ),
				'preset_update' => __( 'Are you sure you want to override the selected preset?', 'sliderpro' ),
				'preset_delete' => __( 'Are you sure you want to delete the selected preset?', 'sliderpro' ),
				'yes' => __( 'Yes', 'sliderpro' ),
				'cancel' => __( 'Cancel', 'sliderpro' ),
				'save' => __( 'Save', 'sliderpro' ),
				'slider_update' => __( 'Slider updated.', 'sliderpro' ),
				'slider_create' => __( 'Slider created.', 'sliderpro' ),
				'slider_importing' => __( 'Importing...', 'sliderpro' )
			) );
		}
	}

	/**
	 * Create the plugin menu.
	 *
	 * @since 4.0.0
	 */
	public function add_admin_menu() {
		$plugin_settings = BQW_SliderPro_Settings::getPluginSettings();
		$access = get_option( 'sliderpro_access', $plugin_settings['access']['default_value'] );

		$restricted_pages = apply_filters( 'sliderpro_restricted_pages' , array() );

		add_menu_page(
			'Slider Pro',
			'Slider Pro',
			$access,
			$this->plugin_slug,
			array( $this, 'render_slider_page' ),
			plugins_url( 'admin/assets/css/images/sp-icon.png', dirname( __FILE__ ) )
		);

		if ( ! in_array( $this->plugin_slug, $restricted_pages ) ) {
			$this->plugin_screen_hook_suffixes['all_sliders'] = add_submenu_page(
				$this->plugin_slug,
				__( 'Slider Pro', 'sliderpro' ),
				__( 'All Sliders', 'sliderpro' ),
				$access,
				$this->plugin_slug,
				array( $this, 'render_slider_page' )
			);
		}

		if ( ! in_array( $this->plugin_slug . '-new', $restricted_pages ) ) {
			$this->plugin_screen_hook_suffixes['add_new'] = add_submenu_page(
				$this->plugin_slug,
				__( 'Add New Slider', 'sliderpro' ),
				__( 'Add New', 'sliderpro' ),
				$access,
				$this->plugin_slug . '-new',
				array( $this, 'render_new_slider_page' )
			);
		}

		if ( ! in_array( $this->plugin_slug . '-settings', $restricted_pages ) ) {
			$this->plugin_screen_hook_suffixes['plugin_settings'] = add_submenu_page(
				$this->plugin_slug,
				__( 'Plugin Settings', 'sliderpro' ),
				__( 'Plugin Settings', 'sliderpro' ),
				$access,
				$this->plugin_slug . '-settings',
				array( $this, 'render_plugin_settings_page' )
			);
		}

		if ( ! in_array( $this->plugin_slug . '-documentation', $restricted_pages ) ) {
			$this->plugin_screen_hook_suffixes['documentation'] = add_submenu_page(
				$this->plugin_slug,
				__( 'Documentation', 'sliderpro' ),
				__( 'Documentation', 'sliderpro' ),
				$access,
				$this->plugin_slug . '-documentation',
				array( $this, 'render_documentation_page' )
			);
		}
		
		do_action('sliderpro_admin_menu');
	}

	/**
	 * Renders the slider page.
	 *
	 * Based on the 'action' parameter, it will render
	 * either an individual slider page or the list
	 * of all the sliders.
	 *
	 * If an individual slider page is rendered, delete
	 * the transients that store the post names and posts data,
	 * in order to trigger a new fetching of them.
	 * 
	 * @since 4.0.0
	 */
	public function render_slider_page() {
		if ( isset( $_GET['id'] ) && isset( $_GET['action'] ) && $_GET['action'] === 'edit' ) {
			$slider = $this->plugin->get_slider( intval( $_GET['id'] ) );

			if ( $slider !== false ) {
				$slider_id = $slider['id'];
				$slider_name = $slider['name'];
				$slider_settings = $slider['settings'];
				$panels_state = $slider['panels_state'];

				$slides = isset( $slider['slides'] ) ? $slider['slides'] : false;

				delete_transient( 'sliderpro_post_names' );
				delete_transient( 'sliderpro_posts_data' );

				include_once( 'views/slider/slider.php' );
			} else {
				include_once( 'views/sliders/sliders.php' );
			}
		} else {
			include_once( 'views/sliders/sliders.php' );
		}
	}

	/**
	 * Renders the page for a new slider.
	 *
	 * Also, delete the transients that store
	 * the post names and posts data,
	 * in order to trigger a new fetching of them.
	 * 
	 * @since 4.0.0
	 */
	public function render_new_slider_page() {
		$slider_name = 'My Slider';

		delete_transient( 'sliderpro_post_names' );
		delete_transient( 'sliderpro_posts_data' );

		include_once( 'views/slider/slider.php' );
	}

	/**
	 * Renders the plugin settings page.
	 *
	 * It also checks if new data was posted, and saves
	 * it in the options table.
	 * 
	 * @since 4.0.0
	 */
	public function render_plugin_settings_page() {
		$plugin_settings = BQW_SliderPro_Settings::getPluginSettings();
		$load_stylesheets = get_option( 'sliderpro_load_stylesheets', $plugin_settings['load_stylesheets']['default_value'] );
		$load_js_in_all_pages = get_option( 'sliderpro_load_js_in_all_pages', $plugin_settings['load_js_in_all_pages']['default_value'] );
		$load_unminified_scripts = get_option( 'sliderpro_load_unminified_scripts', $plugin_settings['load_unminified_scripts']['default_value'] );
		$cache_expiry_interval = get_option( 'sliderpro_cache_expiry_interval', $plugin_settings['cache_expiry_interval']['default_value'] );
		$max_sliders_on_page = get_option( 'sliderpro_max_sliders_on_page', $plugin_settings['max_sliders_on_page']['default_value'] );
		$hide_inline_info = get_option( 'sliderpro_hide_inline_info', $plugin_settings['hide_inline_info']['default_value'] );
		$hide_getting_started_info = get_option( 'sliderpro_hide_getting_started_info', $plugin_settings['hide_getting_started_info']['default_value'] );
		$hide_image_size_warning = get_option( 'sliderpro_hide_image_size_warning', $plugin_settings['hide_image_size_warning']['default_value'] );
		$access = get_option( 'sliderpro_access', $plugin_settings['access']['default_value'] );

		if ( isset( $_POST['plugin_settings_update'] ) && current_user_can( 'customize' ) ) {
			check_admin_referer( 'plugin-settings-update', 'plugin-settings-nonce' );

			if ( isset( $_POST['load_stylesheets'] ) && array_key_exists( $_POST['load_stylesheets'] , $plugin_settings['load_stylesheets']['available_values'] ) ) {
				$load_stylesheets = $_POST['load_stylesheets'];
				update_option( 'sliderpro_load_stylesheets', $load_stylesheets );
			}

			if ( isset( $_POST['load_js_in_all_pages'] ) ) {
				$load_js_in_all_pages = true;
				update_option( 'sliderpro_load_js_in_all_pages', true );
			} else {
				$load_js_in_all_pages = false;
				update_option( 'sliderpro_load_js_in_all_pages', false );
			}

			if ( isset( $_POST['load_unminified_scripts'] ) ) {
				$load_unminified_scripts = true;
				update_option( 'sliderpro_load_unminified_scripts', true );
			} else {
				$load_unminified_scripts = false;
				update_option( 'sliderpro_load_unminified_scripts', false );
			}

			if ( isset( $_POST['cache_expiry_interval'] ) ) {
				$cache_expiry_interval = intval( $_POST['cache_expiry_interval'] );
				update_option( 'sliderpro_cache_expiry_interval', $cache_expiry_interval );
			}

			if ( isset( $_POST['max_sliders_on_page'] ) ) {
				$max_sliders_on_page = intval( $_POST['max_sliders_on_page'] );
				update_option( 'sliderpro_max_sliders_on_page', $max_sliders_on_page );
			}

			if ( isset( $_POST['hide_inline_info'] ) ) {
				$hide_inline_info = true;
				update_option( 'sliderpro_hide_inline_info', true );
			} else {
				$hide_inline_info = false;
				update_option( 'sliderpro_hide_inline_info', false );
			}

			if ( isset( $_POST['hide_getting_started_info'] ) ) {
				$hide_getting_started_info = true;
				update_option( 'sliderpro_hide_getting_started_info', true );
			} else {
				$hide_getting_started_info = false;
				update_option( 'sliderpro_hide_getting_started_info', false );
			}

			if ( isset( $_POST['hide_image_size_warning'] ) ) {
				$hide_image_size_warning = true;
				update_option( 'sliderpro_hide_image_size_warning', true );
			} else {
				$hide_image_size_warning = false;
				update_option( 'sliderpro_hide_image_size_warning', false );
			}

			if ( isset( $_POST['access'] ) && array_key_exists( $_POST['access'], $plugin_settings['access']['available_values'] ) ) {
				$access = $_POST['access'];
				update_option( 'sliderpro_access', $access );
			}
		}
		
		include_once( 'views/settings//plugin-settings.php' );
	}

	/**
	 * Renders the documentation page.
	 * 
	 * @since 4.0.0
	 */
	public function render_documentation_page() {
		echo '<iframe class="sliderpro-documentation" src="' . plugins_url( 'documentation/documentation.html', dirname( __FILE__ ) ) . '" width="100%" height="100%"></iframe>';
	}

	/**
	 * AJAX call for getting the slider's data.
	 *
	 * @since 4.0.0
	 * 
	 * @return string The slider data, as JSON-encoded array.
	 */
	public function ajax_get_slider_data() {
		$nonce = $_GET['nonce'];
		$id = intval( $_GET['id'] );

		if ( ! wp_verify_nonce( $nonce, 'load-slider-data' . $id ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		$slider = $this->get_slider_data( $id );

		echo json_encode( $slider );

		die();
	}

	/**
	 * Return the slider's data.
	 *
	 * @since 4.0.0
	 * 
	 * @param  int   $id The id of the slider.
	 * @return array     The slider data.
	 */
	public function get_slider_data( $id ) {
		return $this->plugin->get_slider( $id );
	}

	/**
	 * AJAX call for saving the slider.
	 *
	 * It can be called when the slider is created, updated
	 * or when a slider is imported. If the slider is 
	 * imported, it returns a row in the list of sliders.
	 *
	 * @since 4.0.0
	 */
	public function ajax_save_slider() {
		$data = json_decode( stripslashes( $_POST['data'] ), true );
		$nonce = $data['nonce'];
		$action = $data['action'];

		$slider_data = BQW_SliderPro_Validation::validate_slider_data( $data );
		$id = $slider_data['id'];

		if ( ! wp_verify_nonce( $nonce, 'save-slider' . $id ) || ! current_user_can( 'edit_posts' ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		$slider_id = $this->save_slider( $slider_data );

		if ( $action === 'save' ) {
			echo json_encode( $slider_id );
		} else if ( $action === 'import' ) {
			$slider_name = $slider_data['name'];
			$slider_created = date( 'm-d-Y' );
			$slider_modified = date( 'm-d-Y' );
			$total_pages = isset( $_POST['pages'] ) ? intval( $_POST['pages'] ) : 1;
			$current_page = isset( $_POST['sp_page'] ) ? intval( $_POST['sp_page'] ) : 1;

			include( 'views/sliders/sliders-row.php' );
		}

		die();
	}

	/**
	 * Save the slider.
	 *
	 * It either creates a new slider or updates and existing one.
	 *
	 * For existing sliders, the slides and layers are deleted and 
	 * re-inserted in the database.
	 *
	 * The cached slider is deleted every time the slider is saved.
	 *
	 * @since 4.0.0
	 * 
	 * @param  array $slider_data The data of the slider that's saved.
	 * @return int                The id of the saved slider.
	 */
	public function save_slider( $slider_data ) {
		global $wpdb;

		$id = $slider_data['id'];
		$slides_data = $slider_data['slides'];

		if ( $id === -1 ) {
			$wpdb->insert($wpdb->prefix . 'slider_pro_sliders', array( 'name' => $slider_data['name'],
																		'settings' => json_encode( $slider_data['settings'] ),
																		'created' => date( 'm-d-Y' ),
																		'modified' => date( 'm-d-Y' ),
																		'panels_state' => json_encode( $slider_data['panels_state'] ) ), 
																		array( '%s', '%s', '%s', '%s', '%s' ) );
			
			$id = $wpdb->insert_id;
		} else {
			$wpdb->update( $wpdb->prefix . 'slider_pro_sliders', array( 'name' => $slider_data['name'], 
																	 	'settings' => json_encode( $slider_data['settings'] ),
																	 	'modified' => date( 'm-d-Y' ),
																		'panels_state' => json_encode( $slider_data['panels_state'] ) ), 
																	   	array( 'id' => $id ), 
																	   	array( '%s', '%s', '%s', '%s' ), 
																	   	array( '%d' ) );
				
			$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "slider_pro_slides WHERE slider_id = %d", $id ) );

			$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "slider_pro_layers WHERE slider_id = %d", $id ) );
		}

		foreach ( $slides_data as $slide_data ) {
			$slide = array('slider_id' => $id,
							'label' => isset( $slide_data['label'] ) ? $slide_data['label'] : '',
							'position' => isset( $slide_data['position'] ) ? $slide_data['position'] : '',
							'visibility' => isset( $slide_data['visibility'] ) ? $slide_data['visibility'] : '',
							'main_image_id' => isset( $slide_data['main_image_id'] ) ? $slide_data['main_image_id'] : '',
							'main_image_source' => isset( $slide_data['main_image_source'] ) ? $slide_data['main_image_source'] : '',
							'main_image_retina_source' => isset( $slide_data['main_image_retina_source'] ) ? $slide_data['main_image_retina_source'] : '',
							'main_image_small_source' => isset( $slide_data['main_image_small_source'] ) ? $slide_data['main_image_small_source'] : '',
							'main_image_medium_source' => isset( $slide_data['main_image_medium_source'] ) ? $slide_data['main_image_medium_source'] : '',
							'main_image_large_source' => isset( $slide_data['main_image_large_source'] ) ? $slide_data['main_image_large_source'] : '',
							'main_image_retina_small_source' => isset( $slide_data['main_image_retina_small_source'] ) ? $slide_data['main_image_retina_small_source'] : '',
							'main_image_retina_medium_source' => isset( $slide_data['main_image_retina_medium_source'] ) ? $slide_data['main_image_retina_medium_source'] : '',
							'main_image_retina_large_source' => isset( $slide_data['main_image_retina_large_source'] ) ? $slide_data['main_image_retina_large_source'] : '',
							'main_image_alt' => isset( $slide_data['main_image_alt'] ) ? $slide_data['main_image_alt'] : '',
							'main_image_title' => isset( $slide_data['main_image_title'] ) ? $slide_data['main_image_title'] : '',
							'main_image_width' => isset( $slide_data['main_image_width'] ) ? $slide_data['main_image_width'] : '',
							'main_image_height' => isset( $slide_data['main_image_height'] ) ? $slide_data['main_image_height'] : '',
							'main_image_link' => isset( $slide_data['main_image_link'] ) ? $slide_data['main_image_link'] : '',
							'main_image_link_title' => isset( $slide_data['main_image_link_title'] ) ? $slide_data['main_image_link_title'] : '',
							'thumbnail_source' => isset( $slide_data['thumbnail_source'] ) ? $slide_data['thumbnail_source'] : '',
							'thumbnail_retina_source' => isset( $slide_data['thumbnail_retina_source'] ) ? $slide_data['thumbnail_retina_source'] : '',
							'thumbnail_alt' => isset( $slide_data['thumbnail_alt'] ) ? $slide_data['thumbnail_alt'] : '',
							'thumbnail_title' => isset( $slide_data['thumbnail_title'] ) ? $slide_data['thumbnail_title'] : '',
							'thumbnail_link' => isset( $slide_data['thumbnail_link'] ) ? $slide_data['thumbnail_link'] : '',
							'thumbnail_link_title' => isset( $slide_data['thumbnail_link_title'] ) ? $slide_data['thumbnail_link_title'] : '',
							'thumbnail_content' => isset( $slide_data['thumbnail_content'] ) ? $slide_data['thumbnail_content'] : '',
							'caption' => isset( $slide_data['caption'] ) ? $slide_data['caption'] : '',
							'html' => isset( $slide_data['html'] ) ? $slide_data['html'] : '',
							'settings' => isset( $slide_data['settings'] ) ? json_encode( $slide_data['settings'] ) : '');

			$wpdb->insert( $wpdb->prefix . 'slider_pro_slides', $slide, array( '%d', '%s', '%d', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ) );

			if ( ! empty( $slide_data[ 'layers' ] ) ) {
				$slide_id = $wpdb->insert_id;
				$layers_data = $slide_data[ 'layers' ];

				foreach ( $layers_data as $layer_data ) {
					$layer = array('slider_id' => $id,
									'slide_id' => $slide_id,
									'position' => isset( $layer_data['position'] ) ? $layer_data['position'] : 0,
									'name' => isset( $layer_data['name'] ) ? $layer_data['name'] : '',
									'type' => isset( $layer_data['type'] ) ? $layer_data['type'] : '',
									'text' => isset( $layer_data['text'] ) ? $layer_data['text'] : '',
									'heading_type' => isset( $layer_data['heading_type'] ) ? $layer_data['heading_type'] : '',
									'image_source' => isset( $layer_data['image_source'] ) ? $layer_data['image_source'] : '',
									'image_alt' => isset( $layer_data['image_alt'] ) ? $layer_data['image_alt'] : '',
									'image_link' => isset( $layer_data['image_link'] ) ? $layer_data['image_link'] : '',
									'image_retina' => isset( $layer_data['image_retina'] ) ? $layer_data['image_retina'] : '',
									'video_source' => isset( $layer_data['video_source'] ) ? $layer_data['video_source'] : '',
									'video_id' => isset( $layer_data['video_id'] ) ? $layer_data['video_id'] : '',
									'video_poster' => isset( $layer_data['video_poster'] ) ? $layer_data['video_poster'] : '',
									'video_retina_poster' => isset( $layer_data['video_retina_poster'] ) ? $layer_data['video_retina_poster'] : '',
									'video_load_mode' => isset( $layer_data['video_load_mode'] ) ? $layer_data['video_load_mode'] : '',
									'video_params' => isset( $layer_data['video_params'] ) ? $layer_data['video_params'] : '',
									'settings' =>  isset( $layer_data['settings'] ) ? json_encode( $layer_data['settings'] ) : ''
									);

					$wpdb->insert( $wpdb->prefix . 'slider_pro_layers', $layer, array( '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ) );
				}
			}
		}
		
		delete_transient( 'sliderpro_cache_' . $id );

		if ( ( $lightbox_sliders = get_option( 'sliderpro_lightbox_sliders' ) ) !== false ) {
			if ( isset( $lightbox_sliders[ $id ] ) ) {
				unset( $lightbox_sliders[ $id ] );
				update_option( 'sliderpro_lightbox_sliders', $lightbox_sliders );
			}
		}

		return $id;
	}

	/**
	 * AJAX call for previewing the slider.
	 *
	 * Receives the current data from the database (in the sliders page)
	 * or from the current settings (in the slider page) and prints the
	 * HTML markup and the inline JavaScript for the slider.
	 *
	 * @since 4.0.0
	 */
	public function ajax_preview_slider() {
		$slider = BQW_SliderPro_Validation::validate_slider_data( json_decode( stripslashes( $_POST['data'] ), true ) );
		$slider_output = $this->plugin->output_slider( $slider, false ) . $this->plugin->get_inline_scripts();

		echo $slider_output;

		die();	
	}

	/**
	 * AJAX call for updating the setting presets.
	 *
	 * @since 4.0.0
	 */
	public function ajax_update_presets() {
		$allowed_methods = array( 'save-new', 'update', 'delete' );
		$nonce = $_POST['nonce'];
		$method = in_array( $_POST['method'], $allowed_methods ) ? $_POST['method'] : '';
		$name = sanitize_text_field( $_POST['name'] );
		$settings = BQW_SliderPro_Validation::validate_slider_settings( json_decode( stripslashes( $_POST['settings'] ), true ) );

		if ( ! wp_verify_nonce( $nonce, 'update-presets' ) || ! current_user_can( 'edit_posts' ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		$presets = get_option( 'sliderpro_setting_presets' );

		if ( $presets === false ) {
			$presets = array();
		}

		if ( $method === 'save-new' || $method === 'update' ) {
			$presets[ $name ] = $settings;
		} else if ( $method === 'delete' ) {
			unset( $presets[ $name ] );
		}

		update_option( 'sliderpro_setting_presets', $presets );

		die();
	}

	/**
	 * AJAX call for retrieving the preset settings.
	 *
	 * @since 4.0.0
	 */
	public function ajax_get_preset_settings() {
		$name = sanitize_text_field( $_GET['name'] );

		$presets = get_option( 'sliderpro_setting_presets' );

		if ( isset( $presets[ $name ] ) ) {
			echo json_encode( $presets[ $name ] );
		}

		die();
	}

	/**
	 * AJAX call for retrieving the preset settings.
	 *
	 * @since 4.0.0
	 */
	public function ajax_get_breakpoints_preset() {
		$breakpoints_data = BQW_SliderPro_Validation::validate_slider_breakpoint_settings( json_decode( stripslashes( $_GET['data'] ), true ) );

		foreach ( $breakpoints_data as $breakpoint_settings ) {
			include( 'views/slider/breakpoint.php' );
		}

		die();
	}

	/**
	 * AJAX call for duplicating a slider.
	 *
	 * Loads a slider from the database and re-saves it with an id of -1, 
	 * which will determine the save function to add a new slider in the 
	 * database.
	 *
	 * It returns a new slider row in the list of all sliders.
	 *
	 * @since 4.0.0
	 */
	public function ajax_duplicate_slider() {
		$nonce = $_POST['nonce'];
		$original_slider_id = intval( $_POST['id'] );
		$total_pages = isset( $_POST['total_pages'] ) ? intval( $_POST['total_pages'] ) : 1;
		$current_page = isset( $_POST['current_page'] ) ? intval( $_POST['current_page'] ) : 1;

		if ( ! wp_verify_nonce( $nonce, 'duplicate-slider' . $original_slider_id ) || ! current_user_can( 'edit_posts' ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		if ( ( $original_slider = $this->plugin->get_slider( $original_slider_id ) ) !== false ) {
			$original_slider['id'] = -1;
			$slider_id = $this->save_slider( $original_slider );
			$slider_name = $original_slider['name'];
			$slider_created = date( 'm-d-Y' );
			$slider_modified = date( 'm-d-Y' );

			include( 'views/sliders/sliders-row.php' );
		}

		die();
	}

	/**
	 * AJAX call for deleting a slider.
	 *
	 * It's called from the list of sliders, when the
	 * 'Delete' link is clicked.
	 *
	 * It calls the 'delete_slider()' method and passes
	 * it the id of the slider to be deleted.
	 *
	 * @since 4.0.0
	 */
	public function ajax_delete_slider() {
		$nonce = $_POST['nonce'];
		$id = intval( $_POST['id'] );

		if ( ! wp_verify_nonce( $nonce, 'delete-slider' . $id ) || ! current_user_can( 'delete_posts' ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		echo $this->delete_slider( $id ); 

		die();
	}

	/**
	 * Delete the slider indicated by the id.
	 *
	 * @since 4.0.0
	 * 
	 * @param  int $id The id of the slider to be deleted.
	 * @return int     The id of the slider that was deleted.
	 */
	public function delete_slider( $id ) {
		global $wpdb;

		$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "slider_pro_slides WHERE slider_id = %d", $id ) );
		$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "slider_pro_layers WHERE slider_id = %d", $id ) );
		$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "slider_pro_sliders WHERE id = %d", $id ) );

		return $id;
	}

	/**
	 * AJAX call for exporting a slider.
	 *
	 * It loads a slider from the database and encodes 
	 * its data as JSON, after removing the id of the slider.
	 *
	 * The JSON string created is presented in a modal window.
	 *
	 * @since 4.0.0
	 */
	public function ajax_export_slider() {
		$nonce = $_POST['nonce'];
		$id = intval( $_POST['id'] );

		if ( ! wp_verify_nonce( $nonce, 'export-slider' . $id ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		$slider = $this->plugin->get_slider( $id );

		if ( $slider !== false ) {
			unset( $slider['id'] );
			$export_string = json_encode( $slider );

			include( 'views/sliders/export-window.php' );
		}

		die();
	}

	/**
	 * AJAX call for displaying the modal window
	 * for importing a slider.
	 *
	 * @since 4.0.0
	 */
	public function ajax_import_slider() {
		include( 'views/sliders/import-window.php' );

		die();
	}

	/**
	 * Create a slide from the passed data.
	 *
	 * Receives some data, like the main image, or
	 * the slide's content type. A new slide is created by 
	 * passing 'false' instead of any data.
	 *
	 * @since 4.0.0
	 * 
	 * @param  array|bool $data The data of the slide or false, if the slide is new.
	 */
	public function create_slide( $data ) {
		$slide_default_settings = BQW_SliderPro_Settings::getSlideSettings();

		$slide_type = $slide_default_settings['content_type']['default_value'];
		$slide_image = '';

		if ( $data !== false ) {
			$slide_type = isset( $data['settings'] ) && isset( $data['settings']['content_type'] ) ? $data['settings']['content_type'] : $slide_type;
			$slide_image = isset( $data['main_image_source'] ) ? $data['main_image_source'] : $slide_image;
		}

		include( 'views/slide/slide.php' );
	}
	
	/**
	 * AJAX call for adding multiple or a single slide.
	 *
	 * If it receives any data, it tries to create multiple
	 * slides by passing the data that was received, and if
	 * it doesn't receive any data it tries to create a
	 * single slide.
	 *
	 * @since 4.0.0
	 */
	public function ajax_add_slides() {
		if ( isset( $_POST['data'] ) ) {
			$slides_data = BQW_SliderPro_Validation::validate_slider_slides( json_decode( stripslashes( $_POST['data'] ), true ) );

			foreach ( $slides_data as $slide_data ) {
				$this->create_slide( $slide_data );
			}
		} else {
			$this->create_slide( false );
		}

		die();
	}

	/**
	 * AJAX call for displaying the main image editor.
	 *
	 * The aspect of the editor will depend on the slide's
	 * content type. Dynamic slides will not have the possibility
	 * to load images from the library.
	 *
	 * @since 4.0.0
	 */
	public function ajax_load_main_image_editor() {
		$slide_default_settings = BQW_SliderPro_Settings::getSlideSettings();

		$data = reset( BQW_SliderPro_Validation::validate_slider_slides( array( json_decode( stripslashes( $_POST['data'] ), true ) ) ) );
		$content_type = isset( $_POST['content_type'] ) && array_key_exists( $_POST['content_type'], $slide_default_settings['content_type']['available_values'] ) ? $_POST['content_type'] : $slide_default_settings['content_type']['default_value'];
		$content_class = $content_type === 'custom' ? 'custom' : 'dynamic';

		include( 'views/slide-editors/main-image-editor.php' );

		die();
	}

	/**
	 * AJAX call for displaying the thumbnail editor.
	 *
	 * The aspect of the editor will depend on the slide's
	 * content type. Dynamic slides will not have the possibility
	 * to load images from the library.
	 *
	 * @since 4.0.0
	 */
	public function ajax_load_thumbnail_editor() {
		$slide_default_settings = BQW_SliderPro_Settings::getSlideSettings();

		$data = reset( BQW_SliderPro_Validation::validate_slider_slides( array( json_decode( stripslashes( $_POST['data'] ), true ) ) ) );
		$content_type = isset( $_POST['content_type'] ) && array_key_exists( $_POST['content_type'], $slide_default_settings['content_type']['available_values'] ) ? $_POST['content_type'] : $slide_default_settings['content_type']['default_value'];
		$content_class = $content_type === 'custom' ? 'custom' : 'dynamic';

		include( 'views/slide-editors/thumbnail-editor.php' );

		die();
	}

	/**
	 * AJAX call for displaying the Caption editor.
	 *
	 * @since 4.0.0
	 */
	public function ajax_load_caption_editor() {
		$slide_default_settings = BQW_SliderPro_Settings::getSlideSettings();

		$caption_content = wp_kses_post( $_POST['data'] );
		$content_type = isset( $_POST['content_type'] ) && array_key_exists( $_POST['content_type'], $slide_default_settings['content_type']['available_values'] ) ? $_POST['content_type'] : $slide_default_settings['content_type']['default_value'];

		include( 'views/slide-editors/caption-editor.php' );

		die();
	}

	/**
	 * AJAX call for displaying the inline HTML editor.
	 *
	 * @since 4.0.0
	 */
	public function ajax_load_html_editor() {
		$slide_default_settings = BQW_SliderPro_Settings::getSlideSettings();
		global $allowedposttags;

		$allowed_html = array_merge(
			$allowedposttags,
			array(
				'iframe' => array(
					'src' => true,
					'width' => true,
					'height' => true,
					'allow' => true,
					'allowfullscreen' => true,
					'class' => true,
					'id' => true
				),
				'source' => array(
					'src' => true,
					'type' => true
				)
			)
		);

		$allowed_html = apply_filters( 'sliderpro_allowed_html', $allowed_html );

		$html_content = wp_kses( $_POST['data'], $allowed_html );
		$content_type = isset( $_POST['content_type'] ) && array_key_exists( $_POST['content_type'], $slide_default_settings['content_type']['available_values'] ) ? $_POST['content_type'] : $slide_default_settings['content_type']['default_value'];

		include( 'views/slide-editors/html-editor.php' );

		die();
	}

	/**
	 * AJAX call for displaying the layers editor.
	 *
	 * @since 4.0.0
	 */
	public function ajax_load_layers_editor() {
		$slide_default_settings = BQW_SliderPro_Settings::getSlideSettings();
		$layer_default_settings = BQW_SliderPro_Settings::getLayerSettings();

		$layers = BQW_SliderPro_Validation::validate_slide_layers( json_decode( stripslashes( $_POST['data'] ), true ) );
		$content_type = isset( $_POST['content_type'] ) && array_key_exists( $_POST['content_type'], $slide_default_settings['content_type']['available_values'] ) ? $_POST['content_type'] : $slide_default_settings['content_type']['default_value'];
		
		include( 'views/slide-editors/layers-editor.php' );

		die();
	}

	/**
	 * AJAX call for adding a new block of layer settings
	 *
	 * It receives the id and type of the layer, and creates 
	 * the appropriate setting fields.
	 *
	 * @since 4.0.0
	 */
	public function ajax_add_layer_settings() {
		$layer_default_settings = BQW_SliderPro_Settings::getLayerSettings();
		$layer = array();
		$layer_id = intval( $_POST['id'] );
		$layer_type = isset( $_POST['type'] ) && array_key_exists( $_POST['type'], $layer_default_settings['type']['available_values'] ) ? $_POST['type'] : $layer_default_settings['type']['default_value'];
		$layer_settings;
		global $allowedposttags;
		
		if ( isset( $_POST['settings'] ) ) {
			$layer_settings = BQW_SliderPro_Validation::validate_layer_settings( json_decode( stripslashes( $_POST['settings'] ), true ) );
		}

		if ( isset( $_POST['text'] ) ) {
			$allowed_html = array_merge(
				$allowedposttags,
				array(
					'iframe' => array(
						'src' => true,
						'width' => true,
						'height' => true,
						'allow' => true,
						'allowfullscreen' => true,
						'class' => true,
						'id' => true
					),
					'source' => array(
						'src' => true,
						'type' => true
					)
				)
			);

			$allowed_html = apply_filters( 'sliderpro_allowed_html', $allowed_html );
			
			$layer['text'] = wp_kses( $_POST['text'], $allowed_html );
		}

		if ( isset( $_POST['heading_type'] ) ) {
			$layer['heading_type'] = array_key_exists( $_POST['heading_type'], $layer_default_settings['heading_type']['available_values'] ) ? $_POST['heading_type'] : $layer_default_settings['heading_type']['default_value'];
		}

		if ( isset( $_POST['image_source'] ) ) {
			$layer['image_source'] = sanitize_text_field( $_POST['image_source'] );
		}

		if ( isset( $_POST['image_alt'] ) ) {
			$layer['image_alt'] = sanitize_text_field( $_POST['image_alt'] );
		}

		if ( isset( $_POST['image_link'] ) ) {
			$layer['image_link'] = sanitize_text_field( $_POST['image_link'] );
		}

		if ( isset( $_POST['image_retina'] ) ) {
			$layer['image_retina'] = sanitize_text_field( $_POST['image_retina'] );
		}

		include( 'views/slide-editors/layer-settings.php' );

		die();
	}

	/**
	 * AJAX call for displaying the slide's settings editor.
	 *
	 * @since 4.0.0
	 */
	public function ajax_load_settings_editor() {
		$slide_default_settings = BQW_SliderPro_Settings::getSlideSettings();
		$slide_settings = BQW_SliderPro_Validation::validate_slide_settings( json_decode( stripslashes( $_POST['data'] ), true ) );
		$content_type = isset( $slide_settings['content_type'] ) && array_key_exists( $slide_settings['content_type'], $slide_default_settings['content_type']['available_values'] ) ? $slide_settings['content_type'] : $slide_default_settings['content_type']['default_value'];
		
		include( 'views/slide-editors/settings-editor.php' );

		die();
	}

	/**
	 * AJAX call for displaying the setting fields associated 
	 * with the current content type of the slide.
	 *
	 * It's called when the content type is changed manually 
	 * in the slide's settings window
	 *
	 * @since 4.0.0
	 */
	public function ajax_load_content_type_settings() {
		$slide_default_settings = BQW_SliderPro_Settings::getSlideSettings();
		$type = isset( $_POST['type'] ) && array_key_exists( $_POST['type'], $slide_default_settings['content_type']['available_values'] ) ? $_POST['type'] : $slide_default_settings['content_type']['default_value'];
		$slide_settings = BQW_SliderPro_Validation::validate_slide_settings( json_decode( stripslashes( $_POST['data'] ), true ) );

		echo $this->load_content_type_settings( $type, $slide_settings );

		die();
	}

	/**
	 * Return the setting fields associated with the content type.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $type           The slide's content type.
	 * @param  array  $slide_settings The slide's settings.
	 */
	public function load_content_type_settings( $type, $slide_settings = NULL ) {
		$slide_default_settings = BQW_SliderPro_Settings::getSlideSettings();

		$path = 'views/slide-settings/' . $slide_default_settings['content_type']['available_values'][ $type ]['file_name'];
		include( $path );
	}

	/**
	 * Return the names of all registered post types
	 *
	 * It arranges the data in an associative array that contains
	 * the name of the post type as the key and and an array, containing 
	 * both the post name and post value, as the value:
	 *
	 * name => ( name, label )
	 *
	 * After the data is fetched, it is stored in a transient for 5 minutes.
	 * Before fetching the data, the function tries to get the data
	 * from the transient.
	 *
	 * @since 4.0.0
	 * 
	 * @return array The list of names for the registered post types.
	 */
	public function get_post_names() {
		$result = array();
		$post_names_transient = get_transient( 'sliderpro_post_names' );

		if ( $post_names_transient === false ) {
			$post_types = get_post_types( '', 'objects' );

			unset( $post_types['attachment'] );
			unset( $post_types['revision'] );
			unset( $post_types['nav_menu_item'] );

			foreach ( $post_types as $post_type ) {
				$result[ $post_type->name ] = array( 'name' => $post_type->name , 'label' => $post_type->label );
			}

			set_transient( 'sliderpro_post_names', $result, 5 * 60 );
		} else {
			$result = $post_names_transient;
		}

		return $result;
	}

	/**
	 * AJAX call for getting the registered taxonomies.
	 *
	 * It's called when the post names are selected manually
	 * in the slide's settings window.
	 *
	 * @since 4.0.0
	 */
	public function ajax_get_taxonomies() {
		$post_names_raw = json_decode( stripslashes( $_GET['post_names'] ), true );
		$post_names = array();

		foreach ( $post_names_raw as $post_name ) {
			array_push( $post_names, sanitize_text_field( $post_name ) );
		}

		echo json_encode( $this->get_taxonomies_for_posts( $post_names ) );

		die();
	}

	/**
	 * Loads the taxonomies associated with the selected post names.
	 *
	 * It tries to find cached data for post names and their taxonomies,
	 * stored in the 'sliderpro_posts_data' transient. If there is any
	 * cached data and if selected post names are in the cached data, those
	 * post names and their taxonomy data are added to the result. Post names 
	 * that are not found in the transient are added to the list of posts to load.
	 * After these posts are loaded, the transient is updated to include the
	 * newly loaded post names, and their taxonomy data.
	 *
	 * While the transient will contain all the post names and taxonomies
	 * loaded in the past and those requested now, the result will include
	 * only post names and taxonomies requested now.
	 *
	 * @since 4.0.0
	 * 
	 * @param  array $post_names The array of selected post names.
	 * @return array             The array of selected post names and their taxonomies.
	 */
	public function get_taxonomies_for_posts( $post_names ) {
		$result = array();
		$posts_to_load = array();

		$posts_data_transient = get_transient( 'sliderpro_posts_data' );

		if ( $posts_data_transient === false || empty( $posts_data_transient ) === true ) {
			$posts_to_load = $post_names;
			$posts_data_transient = array();
		} else {
			foreach ( $post_names as $post_name ) {
				if ( array_key_exists( $post_name, $posts_data_transient ) === true ) {
					$result[ $post_name ] = $posts_data_transient[ $post_name ];
				} else {
					array_push( $posts_to_load, $post_name );
				}
			}
		}

		foreach ( $posts_to_load as $post_name ) {
			$taxonomies = get_object_taxonomies( $post_name, 'objects' );

			$result[ $post_name ] = array();

			foreach ( $taxonomies as $taxonomy ) {
				$terms = get_terms( $taxonomy->name, 'objects' );

				if ( ! empty( $terms ) ) {
					$result[ $post_name ][ $taxonomy->name ] = array(
						'name' => $taxonomy->name,
						'label' => $taxonomy->label,
						'terms' => array()
					);

					foreach ( $terms as $term ) {
						$result[ $post_name ][ $taxonomy->name ]['terms'][ $term->name ] = array(
							'name' => $term->name,
							'slug' => $term->slug,
							'full' => $taxonomy->name . '|' . $term->slug
						);
					}
				}
			}

			$posts_data_transient[ $post_name ] = $result[ $post_name ];
		}

		set_transient( 'sliderpro_posts_data', $posts_data_transient, 5 * 60 );
		
		return $result;
	}

	/**
	 * AJAX call for adding a new breakpoint section.
	 *
	 * @since 4.0.0
	 */
	public function ajax_add_breakpoint() {
		$width = floatval( $_GET['data'] );

		include( 'views/slider/breakpoint.php' );

		die();
	}

	/**
	 * AJAX call for adding a new breakpoint setting.
	 *
	 * @since 4.0.0
	 */
	public function ajax_add_breakpoint_setting() {
		$setting_name = sanitize_text_field( $_GET['data'] );

		echo $this->create_breakpoint_setting( $setting_name, false );

		die();
	}

	/**
	 * Return the HTML markup for the breakpoint setting.
	 *
	 * Generates a unique number that will be attributed to
	 * the label and to the input/select field.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $name  The name of the setting.
	 * @param  mixed  $value The value of the setting. If false, the default setting value will be assigned.
	 * @return string        The HTML markup for the setting.
	 */
	public function create_breakpoint_setting( $name, $value ) {
		$setting = BQW_SliderPro_Settings::getSettings( $name );
		$setting_value = $value !== false ? $value : $setting['default_value'];
		$setting_html = '';
		$uid = mt_rand();

		if ( $setting['type'] === 'number' || $setting['type'] === 'mixed' ) {
            $setting_html = '
            	<tr>
            		<td>
            			<label data-info="' . $setting['description'] . '" for="breakpoint-' . $name . '-' . $uid . '">' . $setting['label'] . '</label>
            		</td>
            		<td class="setting-cell">
            			<input id="breakpoint-' . $name . '-' . $uid . '" class="breakpoint-setting" type="text" name="' . $name . '" value="' . esc_attr( $setting_value ) . '" />
            			<span class="remove-breakpoint-setting"></span>
            		</td>
            	</tr>';
        } else if ( $setting['type'] === 'boolean' ) {
            $setting_html = '
            	<tr>
            		<td>
            			<label data-info="' . $setting['description'] . '" for="breakpoint-' . $name . '-' . $uid . '">' . $setting['label'] . '</label>
            		</td>
            		<td class="setting-cell">
            			<input id="breakpoint-' . $name . '-' . $uid . '" class="breakpoint-setting" type="checkbox" name="' . $name . '"' . ( $setting_value === true ? ' checked="checked"' : '' ) . ' />
            			<span class="remove-breakpoint-setting"></span>
            		</td>
            	</tr>';
        } else if ( $setting['type'] === 'select' ) {
            $setting_html ='
            	<tr>
            		<td>
            			<label data-info="' . $setting['description'] . '" for="breakpoint-' . $name . '-' . $uid . '">' . $setting['label'] . '</label>
            		</td>
            		<td class="setting-cell">
            			<select id="breakpoint-' . $name . '-' . $uid . '" class="breakpoint-setting" name="' . $name . '">';
            
            foreach ( $setting['available_values'] as $value_name => $value_label ) {
                $setting_html .= '<option value="' . $value_name . '"' . ( $setting_value == $value_name ? ' selected="selected"' : '' ) . '>' . $value_label . '</option>';
            }
            
            $setting_html .= '
            			</select>
            			<span class="remove-breakpoint-setting"></span>
            		</td>
            	</tr>';
        }

        return $setting_html;
	}

	/**
	 * AJAX call for deleting the cached sliders
	 * stored using transients.
	 *
	 * It's called from the Plugin Settings page.
	 *
	 * @since 4.0.0
	 */
	public function ajax_clear_all_cache() {
		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'clear-all-cache' ) || ! current_user_can( 'edit_posts' ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		global $wpdb;

		$wpdb->query( "DELETE FROM " . $wpdb->prefix . "options WHERE option_name LIKE '%sliderpro_cache_%' AND NOT option_name = 'sliderpro_cache_expiry_interval'" );

		if ( get_option( 'sliderpro_lightbox_sliders' ) !== false ) {
			update_option( 'sliderpro_lightbox_sliders', array() );
		}

		echo true;

		die();
	}

	/**
	 * AJAX call for closing the Getting Started info box.
	 *
	 * @since 4.0.0
	 */
	public function ajax_close_getting_started() {
		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'close-panel' ) || ! current_user_can( 'manage_options' ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		update_option( 'sliderpro_hide_getting_started_info', true );

		die();
	}

	/**
	 * AJAX call for closing the Custom CSS & JS warning box.
	 *
	 * @since 4.7.0
	 */
	public function ajax_close_custom_css_js_warning() {
		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'close-panel' ) || ! current_user_can( 'manage_options' ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		update_option( 'sliderpro_hide_custom_css_js_warning', true );

		delete_option( 'sliderpro_custom_css' );
		delete_option( 'sliderpro_custom_js' );
		delete_option( 'sliderpro_is_custom_css' );
		delete_option( 'sliderpro_is_custom_js' );

		die();
	}

	/**
	 * AJAX call for closing the image size warning box.
	 *
	 * @since 4.6.0
	 */
	public function ajax_close_image_size_warning() {
		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'close-panel' ) || ! current_user_can( 'manage_options' ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		update_option( 'sliderpro_hide_image_size_warning', true );

		die();
	}
}