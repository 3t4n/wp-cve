<?php

/**
 * Created by PhpStorm.
 * User: kdv
 * Date: 14.01.2017
 * Time: 13:40
 */
class SucuriClear {
	/**
	 *
	 */
	const PAGE_TITILE = 'Clear Sucuri Cache Settings';
	/**
	 *
	 */
	const PLUGIN_TITILE = 'Clear Sucuri Cache';
	/**
	 *
	 */
	const MENU_TITILE = 'Clear Sucuri Cache';
	/**
	 *
	 */
	const MENU_SLUG = 'sucuri-clear';
	/**
	 * @var array
	 */
	static $options = array();
	/**
	 * @var string
	 */
	static $options_name = "sucuri-clearr";
	/**
	 * @var array
	 */
	static $routes = [
		'sucuri' => 'https://waf.sucuri.net/api?v2'
	];
	/**
	 * @var array
	 */
	static $defaults = array(
		"sucuri_key" => false,
		"sucuri_secret"   => false,
		"autopurge" => 0,
	);

	/**
	 * Creates an instance
	 */
	public static function init() {
		new SucuriClear();
	}

	/**
	 *
	 */
	function __construct() {
		$this->get_options();
		add_action( 'admin_menu', array( $this, 'load_menu' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		if ( isset( self::$options['sucuri_key'] ) && self::$options['sucuri_key'] && self::$options['sucuri_secret'] ) {
			add_action( 'admin_bar_menu', array( $this, 'custom_adminbar_menu' ), 15 );
			if ( isset( self::$options['autopurge'] ) && self::$options['autopurge'] == 1 ) {
				add_action( 'save_post', [ $this, 'clear_cache' ] );
				add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			}
		}
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts_styles' ) );
		if ( is_user_logged_in() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts_styles' ) );
			add_action( 'admin_footer', [ $this, 'thickbox' ], 12 );
		}
		if ( is_admin() ) {
			add_action( 'admin_footer', [ $this, 'thickbox' ], 12 );
		}
		add_action( 'wp_ajax_clear_sucuri_cache', array( $this, 'clear_cache' ) );
		add_action( 'wp_ajax_nopriv_clear_sucuri_cache', array( $this, 'clear_cache' ) );
		add_action( 'admin_notices', array( $this, 'sample_admin_notice_info' ) );
		add_filter( 'plugin_action_links_' . SUCURIPURGER_PLUGIN_BASENAME, array( $this, 'plugin_settings_link' ), 10, 2 );
	}

	/**
	 * @param $links
	 *
	 * @return mixed
	 */
	function plugin_settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=' . self::MENU_SLUG . '">Settings</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * @param bool $meta
	 */
	function custom_adminbar_menu( $meta = true ) {
		global $wp_admin_bar;
		if ( ! is_user_logged_in() ) {
			return;
		}
		if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
			return;
		}
		$wp_admin_bar->add_menu( array(
			'parent' => 'top-secondary',
			'id'     => self::MENU_SLUG . '-clear',
			'title'  => __( 'Clear', self::MENU_SLUG ),
			'href'   => '#',
			'meta'   => array(
				'title'    => 'Clear whole Cache',
				'class'    => 'sucuri-clear-clear',
				'tabindex' => - 1,
				'rel'      => 'all'
			)
		) );
		$wp_admin_bar->add_menu( array(
			'parent' => self::MENU_SLUG . '-clear',
			'title'  => __( 'Specific Files' ),
			'id'     => self::MENU_SLUG . '-clear-specific-files',
			'href'   => '#sucuri-purger-modal',
			'meta'   => array(
				'title' => 'Purge individuals file by URL',
				'class' => 'sucuri_clear_files_thickbox_trigger',
			),
		) );
	}

	/**
	 * @param $name
	 * @param $options
	 */
	static function store_options( $name, $options ) {
		add_option( $name, $options ) OR update_option( $name, $options );
	}

	/**
	 * Get options
	 */
	function get_options() {
		$stored_options = get_option( self::$options_name );

		self::$options = array_merge( self::$defaults, $stored_options );
	}

	/**
	 * Enqueue plugin's style and script
	 */
	function register_scripts_styles() {
		wp_enqueue_style( self::MENU_SLUG . 'style', plugins_url( 'style.css', __FILE__ ) );
		wp_enqueue_script( self::MENU_SLUG . 'script', plugins_url( '/js/script.js', __FILE__ ), array( 'jquery' ),
			true );
		wp_localize_script( self::MENU_SLUG . 'script', 'csc_ajaxurl',
			array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}

	/**
	 * Run admin page method
	 */
	function load_menu() {
		add_menu_page( self::PAGE_TITILE, self::MENU_TITILE, 'edit_others_posts', self::MENU_SLUG,
			array( $this, 'create_admin_page' ), plugins_url( 'images/icon.png', __FILE__ ), 6 );
	}

	/**
	 * Store defaults
	 */
	public static function plugin_activation() {
		SucuriClear::store_options( self::$options_name, self::$defaults );
	}

	/**
	 * Removes plugin settings on deactivation
	 */
	public static function plugin_deactivation() {
		delete_option( SucuriClear::$options_name );
	}

	/**
	 * Info section if empty settings
	 */
	function sample_admin_notice_info() {
		$this->get_options();
		if ( isset( $_GET['page'] ) && $_GET['page'] == self::MENU_SLUG &&
		     ( self::$options['sucuri_key'] == '' || self::$options['sucuri_secret'] == false )
		): ?>
			<div class="notice notice-success is-dismissible">
			<p><?php
				$link = '<a target="_blank" title="https://waf.sucuri.net/"
                    href="https://waf.sucuri.net/">page</a>';
				printf( esc_html__( 'You will need to enter your Sucuri CloudProxy API key and CloudProxy API secret for
				 this website first. You can locate the API key by going to your Sucuri Firewall Settings %s', 'sucuri-clear' ), $link ); ?></p>
			</div><?php
		endif;
	}

	/**
	 * Makes admin page
	 */
	public function create_admin_page() {
		// Set class property
		$this->get_options(); ?>
		<div class="wrap">
			<form method="post" action="options.php"><?php
				// This prints out all hidden setting fields
				settings_fields( self::$options_name . '_group' );
				do_settings_sections( self::MENU_SLUG );
				if ( self::$options['sucuri_key'] !== false && ! empty( self::$options['sucuri_key'] ) &&
				     ! empty( self::$options['sucuri_secret'] )
				) {
					$this->render_clear_button();
				}
				submit_button(); ?>
			</form>
		</div>
	<?php
	}

	/**
	 * Renders clear button obviously
	 */
	function render_clear_button() {
		echo '<button onclick="jQuery(\'#wp-admin-bar-sucuri-clear-clear>a\').click(); return false;" class="ab-item button-primary sucuri-clear-clear" tabindex="-1" href="#">Clear  <span class="dashicons dashicons-networking"></span></span></button>';
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {
		register_setting( self::$options_name . '_group', // Option group
			self::$options_name, // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section( 'setting_section_id', // ID
			self::PAGE_TITILE, // Title
			array( $this, 'print_section_info' ), // Callback
			self::MENU_SLUG // Page
		);

		add_settings_field( 'sucuri_key', // ID
			'Sucuri Key', // Title
			array( $this, 'sucuri_key_callback' ), // Callback
			self::MENU_SLUG, // Page
			'setting_section_id' // Section
		);

		add_settings_field( 'sucuri_secret', // ID
			'Sucuri Secret ', // Title
			array( $this, 'sucuri_secret_callback' ), // Callback
			self::MENU_SLUG, // Page
			'setting_section_id' // Section
		);

		add_settings_field( 'autopurge', // ID
			'Clear Cache after Save Post action', // Title
			array( $this, 'autopurge_callback' ), // Callback
			self::MENU_SLUG, // Page
			'setting_section_id' // Section
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input ) {
		$new_input = array();

		$new_input['autopurge'] = (int)isset( $input['autopurge'] );

		if ( isset( $input['sucuri_key'] ) ) {
			$new_input['sucuri_key'] = sanitize_text_field( $input['sucuri_key'] );
		}

		if ( isset( $input['sucuri_secret'] ) ) {
			$new_input['sucuri_secret'] = sanitize_text_field( $input['sucuri_secret'] );
		}

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info() {
		print 'Plugin clears entire cache for domain';
	}

	/**
	 * Renders sucuri_secret input
	 */
	public function sucuri_secret_callback() {
		printf( '<input class="regular-text" type="text" id="sucuri_secret" name="' . self::$options_name .
		        '[sucuri_secret]" value="%s" />',
			isset( self::$options['sucuri_secret'] ) ? esc_attr( self::$options['sucuri_secret'] ) : '' );
	}

	/**
	 * Renders sucuri_key setting input
	 */
	public function sucuri_key_callback() {
		printf( '<input autosuggest="true" class="regular-text" type="text" id="sucuri_key" name="' .
		        self::$options_name . '[sucuri_key]" value="%s" />', isset( self::$options['sucuri_key'] ) ? esc_attr( self::$options['sucuri_key'] ) : '' );
	}

	/**
	 * Renders autopurge checkbox
	 */
	public function autopurge_callback() {
		printf( '<input class="checkbox" type="checkbox" id="autopurge" name="' . self::$options_name .
		        '[autopurge]" "%s" />', checked( 1,  self::$options['autopurge'], false ));
	}

	/**
	 * Method which is called from js - clears whole cache
	 */
	public function clear_cache( $rel = false ) {
		$rel = !$rel?$_POST['rel']:$rel;
		$this->get_options();
		$options    = self::$options;
		$sucuri_key   = $options['sucuri_key'];
		$sucuri_secret    = $options['sucuri_secret'];
		$path = 'https://waf.sucuri.net/api?k='.trim($sucuri_key).'&s='.trim($sucuri_secret).'&a=clearcache';
		if ( $rel == 'file' ) {
			$url_query = explode('/', rtrim($_POST['file'], '/') );
			$home_url = get_home_url();
			$home_url_query = explode('/', rtrim($home_url, '/') );
			$rel_path  = $url_query[count($url_query)-1];
			$rel_home_path  = $home_url_query[count($home_url_query)-1];
			if( $rel_path == $rel_home_path){
				$path .= '&file=/';
			} else {
				$path .= '&file=/'.$rel_path.'/';
			}
		}
		$request = wp_remote_get($path, ['timeout' => 5000]);
		$body = wp_remote_retrieve_body( $request );
		if ( ! is_wp_error( $request ) && wp_remote_retrieve_response_code( $request ) == 200 && stripos($body,'error') === false ) {
			$result = [ 'success' => true,
			            'msg'     => $body
			];
		} else if ( is_wp_error( $request ) ) {
			$error_string = $request->get_error_message();
			$result     = json_encode( [
					'success' => false,
					'msg'     => $error_string
				] );
		} else {
			$result = [ 'success'=>false,
		                'msg'     => wp_remote_retrieve_response_message( $request )
			];
		}
		$response_body = json_encode($result);
		if( defined('DOING_AJAX') && $_POST['action'] == 'clear_sucuri_cache' ) {
			echo $response_body;
			die;
		} else {
			add_filter( 'redirect_post_location', array( $this, 'add_notice_query_var' ), 99 );
		}
	}


	public function admin_notices() {
		if ( ! isset( $_GET['SUCURI_CACHE_CLEAR'] ) ) {
			return;
		}
		?>
		<div class="updated notice notice-success is-dismissible">
			<p><?php esc_html_e( 'Sucuri cache clear attempt was done', 'cf-purger' ); ?></p>
		</div>
	<?php
	}

	public function add_notice_query_var( $location ) {
		remove_filter( 'redirect_post_location', array( $this, 'add_notice_query_var' ), 99 );
		return add_query_arg( array( 'SUCURI_CACHE_CLEAR' => 'ID' ), $location );
	}

	/**
	 * Renders Thickbox for Purge Individual files
	 */
	function thickbox() { ?>
		<!-- Modal -->
		<div id="sucuri-purger-modal" class="cfp-modal md-effect-2">
			<div class="cfp-modal__content">
				<span class="cfp-modal__button-close dashicons dashicons-no"></span>

				<div class="cfp-modal__header">
					<h4 class="cfp-modal__title">Purge individual files by URL</h4>

					<div class="cfp-control__text">
						<p>This option can be used to remove a file from the CloudProxy cache.</p>

						<p><strong>Note:</strong>
							This will reflect live as soon as you click the clear cache button.
						</p>
					</div>
				</div>
				<div class="cfp-modal__body">
					<div class="cfp-modal__inputs">
						<input type="text" name="file" placeholder="http://www.domain.com/wp-content/theme/style.css" class="cfp-modal__input"/>
					</div>
				</div>
				<footer class="cfp-modal__footer cfp-clearfix">
					<a href="#" class="sucuri-modal__button-submit button-primary" rel="file"
					   data-action=""><?php _e( 'Purge Individual Files', 'cf-purger' ); ?></a>
				</footer>
				<!-- Modal content-->
			</div>
		</div>
		<div class="cfp-modal-backdrop"></div>
	<?php
	}


}