<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Download_Attachments_Settings class.
 *
 * @class Download_Attachments_Settings
 */
class Download_Attachments_Settings {

	private $attachment_links;
	private $download_box_displays;
	private $contents;
	private $download_methods;
	private $redirect_targets;
	private $libraries;
	private $choices;
	private $tabs;
	public $post_types;

	/**
	 * Constructor class.
	 *
	 * @return void
	 */
	public function __construct() {
		//actions
		add_action( 'admin_menu', [ $this, 'settings_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'after_setup_theme', [ $this, 'load_defaults' ] );
		add_action( 'wp_loaded', [ $this, 'load_post_types' ] );
	}

	/**
	 * Load defaults.
	 *
	 * @return void
	 */
	public function load_defaults() {
		$this->tabs = [
			'general' => [
				'name'		=> __( 'General', 'download-attachments' ),
				'key'		=> 'download_attachments_general',
				'submit'	=> 'save_da_general',
				'reset'		=> 'reset_da_general'
			],
			'display' => [
				'name'		=> __( 'Display', 'download-attachments' ),
				'key'		=> 'download_attachments_display',
				'submit'	=> 'save_da_display',
				'reset'		=> 'reset_da_display'
			],
			'admin' => [
				'name'		=> __( 'Admin', 'download-attachments' ),
				'key'		=> 'download_attachments_admin',
				'submit'	=> 'save_da_admin',
				'reset'		=> 'reset_da_admin'
			]
		];

		$this->choices = [
			'yes'	=> __( 'Enable', 'download-attachments' ),
			'no'	=> __( 'Disable', 'download-attachments' )
		];

		$this->libraries = [
			'all'	=> __( 'All files', 'download-attachments' ),
			'post'	=> __( 'Attached to a post only', 'download-attachments' )
		];

		$this->attachment_links = [
			'media_library'	=> __( 'Media Library', 'download-attachments' ),
			'modal'			=> __( 'Modal', 'download-attachments' )
		];

		$this->download_box_displays = [
			'before_content'	=> __( 'before the content', 'download-attachments' ),
			'after_content'		=> __( 'after the content', 'download-attachments' ),
			'manually'			=> __( 'manually', 'download-attachments' )
		];

		$this->download_methods = [
			'force'		=> __( 'Force download', 'download-attachments' ),
			'redirect'	=> __( 'Redirect to file', 'download-attachments' )
		];

		$this->contents = [
			'caption'		=> __( 'caption', 'download-attachments' ),
			'description'	=> __( 'description', 'download-attachments' )
		];

		$this->redirect_targets = [
			'_blank'	=> __( '_blank', 'download-attachments' ),
			'_self'		=> __( '_self', 'download-attachments' )
		];
	}

	/**
	 * Load post types.
	 *
	 * @return void
	 */
	public function load_post_types() {
		$this->post_types = apply_filters( 'da_post_types', array_merge( [ 'post', 'page' ], get_post_types( [ '_builtin' => false, 'public' => true ], 'names' ) ) );

		sort( $this->post_types, SORT_STRING );
	}

	/**
	 * Add options page menu.
	 *
	 * @return void
	 */
	public function settings_page() {
		add_options_page( __( 'Attachments', 'download-attachments' ), __( 'Attachments', 'download-attachments' ), 'manage_options', 'download-attachments', [ $this, 'options_page' ] );
	}

	/**
	 * Options page output callback.
	 *
	 * @return void
	 */
	public function options_page() {
		$tab_key = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general';

		echo '
		<div class="wrap">
			<h1>' . esc_html__( 'Download Attachments', 'download-attachments' ) . '</h1>
			<h2 class="nav-tab-wrapper">';

		foreach ( $this->tabs as $key => $name ) {
			echo '
			<a class="nav-tab' . ( $tab_key === $key ? ' nav-tab-active' : '' ) . '" href="' . esc_url( admin_url( 'options-general.php?page=download-attachments&tab=' . $key ) ) . '">' . esc_html( $name['name'] ) . '</a>';
		}

		echo '
			</h2>
			<div class="download-attachments-settings">
				<div class="df-sidebar">
					<div class="df-credits">
						<h3 class="hndle">' . esc_html__( 'Download Attachments', 'download-attachments' ) . ' ' . esc_attr( Download_Attachments()->defaults['version'] ) . '</h3>
						<div class="inside">
							<h4 class="inner">' . esc_html__( 'Need support?', 'download-attachments' ) . '</h4>
							<p class="inner">' . sprintf( __( 'If you are having problems with this plugin, please browse it\'s <a href="%s" target="_blank">Documentation</a> or talk about them in the <a href="%s" target="_blank">Support forum</a>', 'download-attachments' ), 'http://www.dfactory.co/docs/download-attachments/?utm_source=download-attachments-settings&utm_medium=link&utm_campaign=docs', 'http://www.dfactory.co/support/?utm_source=download-attachments-settings&utm_medium=link&utm_campaign=support' ) . '</p>
							<hr/>
							<h4 class="inner">' . esc_html__( 'Do you like this plugin?', 'download-attachments' ) . '</h4>
							<p class="inner">' . sprintf( __( '<a href="%s" target="_blank">Rate it 5</a> on WordPress.org', 'download-attachments' ), 'https://wordpress.org/support/plugin/download-attachments/reviews/?filter=5' ) . '<br />' .
							sprintf( __( 'Blog about it & link to the <a href="%s" target="_blank">plugin page</a>.', 'download-attachments' ), 'http://www.dfactory.co/products/download-attachments/?utm_source=download-attachments-settings&utm_medium=link&utm_campaign=blog-about' ) . '<br />' .
							sprintf( __( 'Check out our other <a href="%s" target="_blank">WordPress plugins</a>.', 'download-attachments' ), 'http://www.dfactory.co/products/?utm_source=download-attachments-settings&utm_medium=link&utm_campaign=other-plugins' ) . '
							</p>
							<hr/>
							<p class="df-link inner">' . esc_html__( 'Created by', 'download-attachments' ) . ' <a href="http://www.dfactory.co/?utm_source=download-attachments-settings&utm_medium=link&utm_campaign=created-by" target="_blank" title="dFactory - Quality plugins for WordPress"><img src="' . esc_url( DOWNLOAD_ATTACHMENTS_URL ) . '/images/logo-dfactory.png" title="dFactory - Quality plugins for WordPress" alt="dFactory - Quality plugins for WordPress"/></a></p>
						</div>
					</div>
				</div>';

		echo '
				<form action="options.php" method="post" >';

		wp_nonce_field( 'update-options' );

		settings_fields( $this->tabs[$tab_key]['key'] );
		do_settings_sections( $this->tabs[$tab_key]['key'] );

		echo '
					<p class="submit">';
		submit_button( '', 'primary ' . $this->tabs[$tab_key]['submit'], $this->tabs[$tab_key]['submit'], false );
		echo ' ';
		submit_button( __( 'Reset to defaults', 'download-attachments' ), 'secondary ' . $this->tabs[$tab_key]['reset'], $this->tabs[$tab_key]['reset'], false );

		echo '
					</p>
				</form>
			</div>
			<div class="clear"></div>
		</div>';
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		// general section
		register_setting( 'download_attachments_general', 'download_attachments_general', [ $this, 'validate_general' ] );
		add_settings_section( 'download_attachments_general', esc_html__( 'General settings', 'download-attachments' ), '', 'download_attachments_general' );
		add_settings_field( 'da_general_label', esc_html__( 'Label', 'download-attachments' ), [ $this, 'da_general_label' ], 'download_attachments_general', 'download_attachments_general' );
		add_settings_field( 'da_general_user_roles', esc_html__( 'User roles', 'download-attachments' ), [ $this, 'da_general_user_roles' ], 'download_attachments_general', 'download_attachments_general' );
		add_settings_field( 'da_general_post_types', esc_html__( 'Supported post types', 'download-attachments' ), [ $this, 'da_general_post_types' ], 'download_attachments_general', 'download_attachments_general' );
		add_settings_field( 'da_general_download_method', esc_html__( 'Download method', 'download-attachments' ), [ $this, 'da_general_download_method' ], 'download_attachments_general', 'download_attachments_general' );
		add_settings_field( 'da_general_pretty_urls', esc_html__( 'Pretty URLs', 'download-attachments' ), [ $this, 'da_general_pretty_urls' ], 'download_attachments_general', 'download_attachments_general' );
		add_settings_field( 'da_general_encrypt_urls', esc_html__( 'Encrypt URLs', 'download-attachments' ), [ $this, 'da_general_encrypt_urls' ], 'download_attachments_general', 'download_attachments_general' );
		add_settings_field( 'da_general_reset_downloads', esc_html__( 'Reset count', 'download-attachments' ), [ $this, 'da_general_reset_downloads' ], 'download_attachments_general', 'download_attachments_general' );
		add_settings_field( 'da_general_deactivation_delete', esc_html__( 'Deactivation', 'download-attachments' ), [ $this, 'da_general_deactivation_delete' ], 'download_attachments_general', 'download_attachments_general' );

		// frontend section
		register_setting( 'download_attachments_display', 'download_attachments_general', [ $this, 'validate_general' ] );
		add_settings_section( 'download_attachments_display', esc_html__( 'Display settings', 'download-attachments' ), '', 'download_attachments_display' );
		add_settings_field( 'da_general_frontend_display', esc_html__( 'Fields display', 'download-attachments' ), [ $this, 'da_general_frontend_display' ], 'download_attachments_display', 'download_attachments_display' );
		add_settings_field( 'da_general_display_style', esc_html__( 'Display style', 'download-attachments' ), [ $this, 'da_general_display_style' ], 'download_attachments_display', 'download_attachments_display' );
		add_settings_field( 'da_general_frontend_content', esc_html__( 'Downloads description', 'download-attachments' ), [ $this, 'da_general_frontend_content' ], 'download_attachments_display', 'download_attachments_display' );
		add_settings_field( 'da_general_css_style', esc_html__( 'Use CSS style', 'download-attachments' ), [ $this, 'da_general_css_style' ], 'download_attachments_display', 'download_attachments_display' );
		add_settings_field( 'da_general_download_box_display', esc_html__( 'Display position', 'download-attachments' ), [ $this, 'da_general_download_box_display' ], 'download_attachments_display', 'download_attachments_display' );

		// admin section
		register_setting( 'download_attachments_admin', 'download_attachments_general', [ $this, 'validate_general' ] );
		add_settings_section( 'download_attachments_admin', esc_html__( 'Admin settings', 'download-attachments' ), '', 'download_attachments_admin' );
		add_settings_field( 'da_general_backend_display', esc_html__( 'Fields display', 'download-attachments' ), [ $this, 'da_general_backend_display' ], 'download_attachments_admin', 'download_attachments_admin' );
		add_settings_field( 'da_general_backend_content', esc_html__( 'Downloads description', 'download-attachments' ), [ $this, 'da_general_backend_content' ], 'download_attachments_admin', 'download_attachments_admin' );
		add_settings_field( 'da_restrict_edit_downloads', esc_html__( 'Restrict Edit', 'download-attachments' ), [ $this, 'da_restrict_edit_downloads' ], 'download_attachments_admin', 'download_attachments_admin' );
		add_settings_field( 'da_general_attachment_link', esc_html__( 'Edit attachment link', 'download-attachments' ), [ $this, 'da_general_attachment_link' ], 'download_attachments_admin', 'download_attachments_admin' );
		add_settings_field( 'da_general_libraries', esc_html__( 'Media Library', 'download-attachments' ), [ $this, 'da_general_libraries' ], 'download_attachments_admin', 'download_attachments_admin' );
		add_settings_field( 'da_general_downloads_in_media_library', esc_html__( 'Downloads count', 'download-attachments' ), [ $this, 'da_general_downloads_in_media_library' ], 'download_attachments_admin', 'download_attachments_admin' );
	}

	/**
	 * Setting: label.
	 *
	 * @return void
	 */
	public function da_general_label() {
		echo '
		<div id="da_general_label">
			<input type="text" class="regular-text" name="download_attachments_general[label]" value="' . esc_attr( Download_Attachments()->options['label'] ) . '"/>
			<br/>
			<p class="description">' . esc_html__( 'Enter download attachments list label.', 'download-attachments' ) . '</p>
		</div>';
	}

	/**
	 * Setting: post types.
	 *
	 * @return void
	 */
	public function da_general_post_types() {
		echo '
		<div id="da_general_post_types">
			<fieldset>';

		foreach ( $this->post_types as $val ) {
			echo '
				<input id="da-general-post-types-' . esc_attr( $val ) . '" type="checkbox" name="download_attachments_general[post_types][]" value="' . esc_attr( $val ) . '" ' . checked( true, ( isset( Download_Attachments()->options['post_types'][$val] ) ? Download_Attachments()->options['post_types'][$val] : false ), false ) . '/><label for="da-general-post-types-' . esc_attr( $val ) . '">' . esc_html( $val ) . '</label>';
		}

		echo '
				<p class="description">' . esc_html__( 'Select which post types would you like to enable for your downloads.', 'download-attachments' ) . '</p>
			</fieldset>
		</div>';
	}

	/**
	 * Setting: download method.
	 *
	 * @return void
	 */
	public function da_general_download_method() {
		echo '
		<div id="da_general_download_method">
			<fieldset>';

		foreach ( $this->download_methods as $val => $trans ) {
			echo '
				<input id="da-general-download-method-' . esc_attr( $val ) . '" type="radio" name="download_attachments_general[download_method]" value="' . esc_attr( $val ) . '" ' . checked( $val, Download_Attachments()->options['download_method'], false ) . '/><label for="da-general-download-method-' . esc_attr( $val ) . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<p class="description">' . esc_html__( 'Select download method.', 'download-attachments' ) . '</p>
				<div id="da_general_download_method_target"' . ( Download_Attachments()->options['download_method'] === 'force' ? ' style="display: none;"' : '' ) . '>';

		foreach ( $this->redirect_targets as $target ) {
			echo '
					<label><input id="da_general_download_method_target_label-' . esc_attr( $target ) . '" type="radio" name="download_attachments_general[link_target]" value="' . esc_attr( $target ) . '" ' . checked( $target, Download_Attachments()->options['link_target'], false ) . ' />' . esc_html( $target ) . '</label>';
		}

		echo '
					<p class="description">' . esc_html__( 'Select redirect to file link target.', 'download-attachments' ) . '</p>
				</div>
			</fieldset>
		</div>';
	}

	/**
	 * Setting: admin fields display.
	 *
	 * @return void
	 */
	public function da_general_backend_display() {
		echo '
		<div id="da_general_backend_display">
			<fieldset>';

		foreach ( Download_Attachments()->columns as $val => $trans ) {
			if ( ! in_array( $val, [ 'title', 'index', 'icon', 'exclude' ], true ) )
				echo '
				<input id="da-general-backend-display-' . esc_attr( $val ) . '" type="checkbox" name="download_attachments_general[backend_columns][]" value="' . esc_attr( $val ) . '" ' . checked( true, Download_Attachments()->options['backend_columns'][$val], false ) . '/><label for="da-general-backend-display-' . esc_attr( $val ) . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<p class="description">' . esc_html__( 'Select which columns would you like to enable on backend for your downloads.', 'download-attachments' ) . '</p>
			</fieldset>
		</div>';
	}

	/**
	 * Setting: frontend fields display.
	 *
	 * @return void
	 */
	public function da_general_frontend_display() {
		echo '
		<div id="da_general_frontend_display">
			<fieldset>';

		foreach ( Download_Attachments()->columns as $val => $trans ) {
			if ( ! in_array( $val, [ 'id', 'type', 'title', 'exclude' ], true ) )
				echo '
				<input id="da-general-frontend-display-' . esc_attr( $val ) . '" type="checkbox" name="download_attachments_general[frontend_columns][]" value="' . esc_attr( $val ) . '" ' . checked( true, Download_Attachments()->options['frontend_columns'][$val], false ) . '/><label for="da-general-frontend-display-' . esc_attr( $val ) . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<p class="description">' . esc_html__( 'Select which columns would you like to enable on frontend for your downloads.', 'download-attachments' ) . '</p>
			<fieldset>
		</div>';
	}

	/**
	 * Setting: CSS style.
	 *
	 * @return void
	 */
	public function da_general_css_style() {
		echo '
		<div id="da_general_css_style">
			<fieldset>';

		foreach ( $this->choices as $val => $trans ) {
			echo '
				<input id="da-general-css-style-' . esc_attr( $val ) . '" type="radio" name="download_attachments_general[use_css_style]" value="' . esc_attr( $val ) . '" ' . checked( ( $val === 'yes' ), Download_Attachments()->options['use_css_style'], false ) . '/><label for="da-general-css-style-' . esc_attr( $val ) . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<p class="description">' . esc_html__( 'Select if you\'d like to use bultin CSS style.', 'download-attachments' ) . '</p>
			</fieldset>
		</div>';
	}

	/**
	 * Setting: pretty URLs.
	 *
	 * @return void
	 */
	public function da_general_pretty_urls() {
		echo '
		<div id="da_general_pretty_urls">
			<fieldset>';

		foreach ( $this->choices as $val => $trans ) {
			echo '
				<input id="da-general-pretty-urls-' . esc_attr( $val ) . '" type="radio" name="download_attachments_general[pretty_urls]" value="' . esc_attr( $val ) . '" ' . checked( ( $val === 'yes' ), Download_Attachments()->options['pretty_urls'], false ) . '/><label for="da-general-pretty-urls-' . esc_attr( $val ) . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<p class="description">' . esc_html__( 'Enable if you want to use pretty URLs.', 'download-attachments' ) . '</p>
				<div id="da_general_download_link"' . ( ! Download_Attachments()->options['pretty_urls'] ? ' style="display: none;"' : '' ) . '>
					<label for="da_general_download_link_label">' . esc_html__( 'Slug', 'download-attachments' ) . '</label>: <input id="da_general_download_link_label" type="text" name="download_attachments_general[download_link]" class="regular-text" value="' . esc_attr( Download_Attachments()->options['download_link'] ) . '"/>
					<p class="description"><code>' . esc_url( site_url() ) . '/<strong>' . esc_html( Download_Attachments()->options['download_link'] ) . '</strong>/123/</code></p>
					<p class="description">' . esc_html__( 'Download link slug.', 'download-attachments' ) . '</p>
				</div>
			</fieldset>
		</div>';
	}

	/**
	 * Setting: encrypt URLs.
	 *
	 * @return void
	 */
	public function da_general_encrypt_urls() {
		echo '
		<div id="da_general_encrypt_urls">
			<fieldset>';

		foreach ( $this->choices as $val => $trans ) {
			echo '
				<input id="da-general-encrypt-urls-' . esc_attr( $val ) . '" type="radio" name="download_attachments_general[encrypt_urls]" value="' . esc_attr( $val ) . '" ' . checked( ( $val === 'yes' ), isset( Download_Attachments()->options['encrypt_urls'] ) ? Download_Attachments()->options['encrypt_urls'] : Download_Attachments()->defaults['general']['encrypt_urls'] , false ) . '/><label for="da-general-encrypt-urls-' . esc_attr( $val ) . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<p class="description">' . esc_html__( 'Enable if you want to encrypt the attachment ids in generated URL\'s.', 'download-attachments' ) . '</p>
			</fieldset>
		</div>';
	}

	/**
	 * Setting: display position.
	 *
	 * @return void
	 */
	public function da_general_download_box_display() {
		echo '
		<div id="da_general_download_box_display">
			<fieldset>';

		foreach ( $this->download_box_displays as $val => $trans ) {
			echo '
				<input id="da-general-download-box-display-' . esc_attr( $val ) . '" type="radio" name="download_attachments_general[download_box_display]" value="' . esc_attr( $val ) . '" ' . checked( $val, Download_Attachments()->options['download_box_display'], false ) . '/><label for="da-general-download-box-display-' . esc_attr( $val ) . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<p class="description">' . esc_html__( 'Select where you would like your download attachments to be displayed.', 'download-attachments' ) . '</p>
			</fieldset>
		</div>';
	}

	/**
	 * Setting: display style.
	 *
	 * @return void
	 */
	public function da_general_display_style() {
		echo '
		<div id="da_general_display_style">
			<fieldset>';

		foreach ( Download_Attachments()->display_styles as $val => $trans ) {
			echo '
				<input id="da-general-display-style-' . esc_attr( $val ) . '" type="radio" name="download_attachments_general[display_style]" value="' . esc_attr( $val ) . '" ' . checked( $val, isset( Download_Attachments()->options['display_style'] ) ? esc_attr( Download_Attachments()->options['display_style'] ) : Download_Attachments()->defaults['general']['display_style'], false ) . '/><label for="da-general-display-style-' . esc_attr( $val ) . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<p class="description">' . esc_html__( 'Select display style for file attachments.', 'download-attachments' ) . '</p>
			</fieldset>
		</div>';
	}

	/**
	 * Setting: admin downloads description.
	 *
	 * @return void
	 */
	public function da_general_backend_content() {
		echo '
		<div id="da_general_backend_content">
			<fieldset>';

		foreach ( $this->contents as $val => $trans ) {
			echo '
				<input id="da-general-backend-content-' . esc_attr( $val ) . '" type="checkbox" name="download_attachments_general[backend_content][]" value="' . esc_attr( $val ) . '" ' . checked( true, ( isset( Download_Attachments()->options['backend_content'][$val] ) ? Download_Attachments()->options['backend_content'][$val] : false ), false ) . '/><label for="da-general-backend-content-' . esc_attr( $val ) . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<p class="description">' . esc_html__( 'Select what fields to use on backend for download attachments description.', 'download-attachments' ) . '</p>
			</fieldset>
		</div>';
	}

	/**
	 * Setting: restrict edit.
	 *
	 * @return void
	 */
	public function da_restrict_edit_downloads() {
		echo '
		<div id="da_restrict_edit_downloads">
			<fieldset>
				<label><input type="checkbox" name="download_attachments_general[restrict_edit_downloads]" value="1" ' . checked( true, Download_Attachments()->options['restrict_edit_downloads'], false ) . ' />' . esc_html__( 'Enable to restrict downloads count editing to admins only.', 'download-attachments' ) . '</label>
			</fieldset>
		</div>';
	}

	/**
	 * Setting: frontend downloads description.
	 *
	 * @return void
	 */
	public function da_general_frontend_content() {
		echo '
		<div id="da_general_frontend_content">
			<fieldset>';

		foreach ( $this->contents as $val => $trans ) {
			echo '
				<input id="da-general-frontend-content-' . esc_attr( $val ) . '" type="checkbox" name="download_attachments_general[frontend_content][]" value="' . esc_attr( $val ) . '" ' . checked( true, ( isset( Download_Attachments()->options['frontend_content'][$val] ) ? Download_Attachments()->options['frontend_content'][$val] : false ), false ) . '/><label for="da-general-frontend-content-' . esc_attr( $val ) . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<p class="description">' . esc_html__( 'Select what fields to use on frontend for download attachments description.', 'download-attachments' ) . '</p>
			</fieldset>
		</div>';
	}

	/**
	 * Setting: edit attachment link.
	 *
	 * @return void
	 */
	public function da_general_attachment_link() {
		echo '
		<div id="da_general_attachment_link">
			<fieldset>';

		foreach ( $this->attachment_links as $val => $trans ) {
			echo '
				<input id="da-general-attachment-link-' . esc_attr( $val ) . '" type="radio" name="download_attachments_general[attachment_link]" value="' . esc_attr( $val ) . '" ' . checked( $val, Download_Attachments()->options['attachment_link'], false ) . '/><label for="da-general-attachment-link-' . esc_attr( $val ) . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<p class="description">' . esc_html__( 'Select where you would like to edit download attachments.', 'download-attachments' ) . '</p>
			</fieldset>
		</div>';
	}

	/**
	 * Setting: media library.
	 *
	 * @return void
	 */
	public function da_general_libraries() {
		echo '
		<div id="da_general_libraries">
			<fieldset>';

		foreach ( $this->libraries as $val => $trans ) {
			echo '
				<input id="da-general-libraries-' . esc_attr( $val ) . '" type="radio" name="download_attachments_general[library]" value="' . esc_attr( $val ) . '" ' . checked( $val, Download_Attachments()->options['library'], false ) . '/><label for="da-general-libraries-' . esc_attr( $val ) . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<p class="description">' . esc_html__( 'Select which attachments should be visible in Media Library window.', 'download-attachments' ) . '</p>
			</fieldset>
		</div>';
	}

	/**
	 * Setting: downloads count.
	 *
	 * @return void
	 */
	public function da_general_downloads_in_media_library() {
		echo '
		<div id="da_general_downloads_in_media_library">
			<fieldset>';

		foreach ( $this->choices as $val => $trans ) {
			echo '
				<input id="da-general-downloads-in-media-library-' . esc_attr( $val ) . '" type="radio" name="download_attachments_general[downloads_in_media_library]" value="' . esc_attr( $val ) . '" ' . checked( ( $val === 'yes' ), Download_Attachments()->options['downloads_in_media_library'], false ) . '/><label for="da-general-downloads-in-media-library-' . esc_attr( $val ) . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<p class="description">' . esc_html__( 'Enable if you want to display downloads count in your Media Library columns.', 'download-attachments' ) . '</p>
			</fieldset>
		</div>';
	}

	/**
	 * Setting: user roles.
	 *
	 * @global object $wp_roles
	 *
	 * @return void
	 */
	public function da_general_user_roles() {
		global $wp_roles;

		$editable_roles = get_editable_roles();

		echo '
		<div id="da_general_user_roles">
			<fieldset>';

		foreach ( $editable_roles as $val => $trans ) {
			$role = $wp_roles->get_role( $val );

			// admins have access by default
			if ( $role->has_cap( 'manage_options' ) )
				continue;

			echo '
				<input id="da-general-user-roles-' . esc_attr( $val ) . '" type="checkbox" name="download_attachments_general[user_roles][]" value="' . esc_attr( $val ) . '" ' . checked( true, in_array( $val, ( isset( Download_Attachments()->options['user_roles'] ) ? Download_Attachments()->options['user_roles'] : Download_Attachments()->defaults['general']['user_roles'] ) ), false ) . '/><label for="da-general-user-roles-' . esc_attr( $val ) . '">' . esc_html( translate_user_role( $wp_roles->role_names[$val] ) ) . '</label>';
		}

		echo '
				<p class="description">' . esc_html__( 'Select user roles allowed to add, remove and manage attachments.', 'download-attachments' ) . '</p>
			</fieldset>
		</div>';
	}

	/**
	 * Setting: deactivation.
	 *
	 * @return void
	 */
	public function da_general_deactivation_delete() {
		echo '
		<div id="da_general_deactivation_delete">
			<fieldset>';

		foreach ( $this->choices as $val => $trans ) {
			echo '
				<input id="da-general-deactivation-delete-' . esc_attr( $val ) . '" type="radio" name="download_attachments_general[deactivation_delete]" value="' . esc_attr( $val ) . '" ' . checked( ( $val === 'yes' ), Download_Attachments()->options['deactivation_delete'], false ) . '/><label for="da-general-deactivation-delete-' . esc_attr( $val ) . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<p class="description">' . esc_html__( 'Enable if you want all plugin data to be deleted on deactivation.', 'download-attachments' ) . '</p>
			</fieldset>
		</div>';
	}

	/**
	 * Setting: reset count.
	 *
	 * @return void
	 */
	public function da_general_reset_downloads() {
		echo '
		<div id="da_general_deactivation_delete">
			<fieldset>';

		submit_button( esc_html__( 'Reset downloads', 'download-attachments' ), 'secondary', 'reset_da_downloads', false );

		echo '
				<p class="description">' . esc_html__( 'Click to reset the downloads count for all the attachments.', 'download-attachments' ) . '</p>
			</fieldset>
		</div>';
	}

	/**
	 * Validate general settings, reset general settings, reset download counts.
	 *
	 * @global object $wp_roles
	 * @global object $wpdb
	 *
	 * @param array $input
	 * @return array
	 */
	public function validate_general( $input ) {
		global $wp_roles;
		global $wpdb;

		// get old input for saving tabs
		$old_input = Download_Attachments()->options;

		// save general
		if ( isset( $_POST['save_da_general'] ) ) {
			$new_input = $old_input;

			// label
			$new_input['label'] = sanitize_text_field( $input['label'] );

			// capabilities
			$user_roles = [];

			foreach ( $wp_roles->roles as $role_name => $role_text ) {
				$role = $wp_roles->get_role( $role_name );

				if ( ! $role->has_cap( 'manage_options' ) ) {
					if ( ! empty( $input['user_roles'] ) && in_array( $role_name, array_map( 'sanitize_key', $input['user_roles'] ) ) ) {
						$role->add_cap( Download_Attachments()->get_capability() );
						$user_roles[] = $role_name;
					} else
						$role->remove_cap( Download_Attachments()->get_capability() );
				}
			}

			$new_input['user_roles'] = $user_roles;

			// post types
			$post_types = [];
			$input['post_types'] = isset( $input['post_types'] ) ? $input['post_types'] : [];

			foreach ( $this->post_types as $post_type ) {
				$post_types[$post_type] = in_array( $post_type, $input['post_types'], true );
			}

			$new_input['post_types'] = $post_types;

			// download method
			$new_input['download_method'] = isset( $input['download_method'], $this->download_methods[$input['download_method']] ) ? $input['download_method'] : Download_Attachments()->defaults['general']['download_method'];

			// download method - redirect to file target
			$new_input['link_target'] = isset( $input['link_target'], $this->redirect_targets[$input['link_target']] ) ? $input['link_target'] : Download_Attachments()->defaults['general']['link_target'];

			// encrypt urls
			$new_input['encrypt_urls'] = isset( $input['encrypt_urls'], $this->choices[$input['encrypt_urls']] ) ? ( $input['encrypt_urls'] === 'yes' ) : Download_Attachments()->defaults['general']['encrypt_urls'];

			// pretty urls
			$new_input['pretty_urls'] = isset( $input['pretty_urls'], $this->choices[$input['pretty_urls']] ) ? ( $input['pretty_urls'] === 'yes' ) : Download_Attachments()->defaults['general']['pretty_urls'];

			// download link
			if ( $new_input['pretty_urls'] ) {
				$new_input['download_link'] = sanitize_title( $input['download_link'], Download_Attachments()->defaults['general']['download_link'] );

				if ( $new_input['download_link'] === '' )
					$new_input['download_link'] = Download_Attachments()->defaults['general']['download_link'];

				if ( $new_input['encrypt_urls'] )
					$rule = $new_input['download_link'] . '/([A-Za-z0-9_,-]+)/?';
				else
					$rule = $new_input['download_link'] . '/(\d+)/?';

				// add new rule
				add_rewrite_rule( $rule, 'index.php?' . $new_input['download_link'] . '=$matches[1]', 'top' );
			} else
				$new_input['download_link'] = Download_Attachments()->defaults['general']['download_link'];

			// rewrite rules
			flush_rewrite_rules();

			// deactivation delete
			$new_input['deactivation_delete'] = isset( $input['deactivation_delete'] ) && in_array( $input['deactivation_delete'], array_keys( $this->choices ), true ) ? ( $input['deactivation_delete'] === 'yes' ) : Download_Attachments()->defaults['general']['deactivation_delete'];

			$input = $new_input;
		// save display
		} elseif ( isset( $_POST['save_da_display'] ) ) {
			$new_input = $old_input;

			// frontend columns
			$columns = [];
			$input['frontend_columns'] = isset( $input['frontend_columns'] ) ? $input['frontend_columns'] : [];

			foreach ( Download_Attachments()->columns as $column => $text ) {
				if ( in_array( $column, [ 'id', 'type', 'exclude' ], true ) )
					continue;
				elseif ( $column === 'title' )
					$columns[$column] = true;
				else
					$columns[$column] = in_array( $column, $input['frontend_columns'], true );
			}

			$new_input['frontend_columns'] = $columns;

			// frontend content
			$contents = [];
			$input['frontend_content'] = isset( $input['frontend_content'] ) ? $input['frontend_content'] : [];

			foreach ( $this->contents as $content => $trans ) {
				$contents[$content] = in_array( $content, $input['frontend_content'], true );
			}

			$new_input['frontend_content'] = $contents;

			// display style
			$new_input['display_style'] = isset( $input['display_style'], Download_Attachments()->display_styles[$input['display_style']] ) ? $input['display_style'] : Download_Attachments()->defaults['general']['display_style'];

			// use css style
			$new_input['use_css_style'] = isset( $input['use_css_style'] ) && in_array( $input['use_css_style'], array_keys( $this->choices ), true ) ? ( $input['use_css_style'] === 'yes' ) : Download_Attachments()->defaults['general']['use_css_style'];

			// download box display
			$new_input['download_box_display'] = isset( $input['download_box_display'] ) && in_array( $input['download_box_display'], array_keys( $this->download_box_displays ), true ) ? $input['download_box_display'] : Download_Attachments()->defaults['general']['download_box_display'];

			$input = $new_input;
		// save admin
		} elseif ( isset( $_POST['save_da_admin'] ) ) {
			$new_input = $old_input;

			// backend columns
			$columns = [];
			$input['backend_columns'] = isset( $input['backend_columns'] ) ? $input['backend_columns'] : [];

			foreach ( Download_Attachments()->columns as $column => $text ) {
				if ( in_array( $column, [ 'index', 'icon', 'exclude' ], true ) )
					continue;
				elseif ( $column === 'title' )
					$columns[$column] = true;
				else
					$columns[$column] = in_array( $column, $input['backend_columns'], true );
			}

			$new_input['backend_columns'] = $columns;

			$new_input['restrict_edit_downloads'] = array_key_exists( 'restrict_edit_downloads', $input );

			// backend content
			$contents = [];
			$input['backend_content'] = isset( $input['backend_content'] ) ? $input['backend_content'] : [];

			foreach ( $this->contents as $content => $trans ) {
				$contents[$content] = in_array( $content, $input['backend_content'], true );
			}

			$new_input['backend_content'] = $contents;

			// attachment link
			$new_input['attachment_link'] = isset( $input['attachment_link'] ) && in_array( $input['attachment_link'], array_keys( $this->attachment_links ), true ) ? $input['attachment_link'] : Download_Attachments()->defaults['general']['attachment_link'];

			// library
			$new_input['library'] = isset( $input['library'] ) && in_array( $input['library'], array_keys( $this->libraries ), true ) ? $input['library'] : Download_Attachments()->defaults['general']['library'];

			// downloads in media library
			$new_input['downloads_in_media_library'] = isset( $input['downloads_in_media_library'] ) && in_array( $input['downloads_in_media_library'], array_keys( $this->choices ), true ) ? ( $input['downloads_in_media_library'] === 'yes' ) : Download_Attachments()->defaults['general']['downloads_in_media_library'];

			$input = $new_input;
		// reset general
		} elseif ( isset( $_POST['reset_da_general'] ) ) {
			$new_input = $old_input;

			// capabilities
			$new_input['user_roles'] = [];

			foreach ( $wp_roles->roles as $role_name => $display_name ) {
				$role = $wp_roles->get_role( $role_name );

					if ( $role->has_cap( 'upload_files' ) ) {
						$role->add_cap( Download_Attachments()->get_capability() );

						$new_input['user_roles'][] = $role_name;
					} else {
						$role->remove_cap( Download_Attachments()->get_capability() );
					}
			}

			$keys = [ 'label', 'link_target', 'download_method', 'post_types', 'pretty_urls', 'download_link', 'encrypt_urls', 'deactivation_delete' ];

			foreach ( $keys as $key ) {
				if ( array_key_exists( $key, Download_Attachments()->defaults['general'] ) )
					$new_input[$key] = Download_Attachments()->defaults['general'][$key];
			}

			// rewrite rules
			flush_rewrite_rules();

			$input = $new_input;

			add_settings_error( 'reset_general_settings', 'reset_general_settings', esc_html__( 'General settings restored to defaults.', 'download-attachments' ), 'updated' );
		// reset display
		} elseif ( isset( $_POST['reset_da_display'] ) ) {
			$new_input = $old_input;

			$keys = [ 'frontend_columns', 'display_style', 'frontend_content', 'use_css_style', 'download_box_display' ];

			foreach ( $keys as $key ) {
				if ( array_key_exists( $key, Download_Attachments()->defaults['general'] ) )
					$new_input[$key] = Download_Attachments()->defaults['general'][$key];
			}

			$input = $new_input;

			add_settings_error( 'reset_display_settings', 'reset_display_settings', esc_html__( 'Display settings restored to defaults.', 'download-attachments' ), 'updated' );
		// reset admin
		} elseif ( isset( $_POST['reset_da_admin'] ) ) {
			$new_input = $old_input;

			$keys = [ 'backend_columns', 'restrict_edit_downloads', 'backend_content', 'attachment_link', 'library', 'downloads_in_media_library' ];

			foreach ( $keys as $key ) {
				if ( array_key_exists( $key, Download_Attachments()->defaults['general'] ) )
					$new_input[$key] = Download_Attachments()->defaults['general'][$key];
			}

			$input = $new_input;

			add_settings_error( 'reset_admin_settings', 'reset_admin_settings', esc_html__( 'Admin settings restored to defaults.', 'download-attachments' ), 'updated' );
		// reset downloads
		} elseif ( isset( $_POST['reset_da_downloads'] ) ) {
			// lets use wpdb to reset downloads a lot faster than normal update_post_meta for each post_id
			$result = $wpdb->update( $wpdb->postmeta, [ 'meta_value' => 0 ], [ 'meta_key' => '_da_downloads' ], '%d', '%s' );

			$input = Download_Attachments()->options;

			if ( $result === false )
				add_settings_error( 'reset_downloads', 'reset_downloads_error', esc_html__( 'Error occurred while resetting the downloads count.', 'download-attachments' ), 'error' );
			else
				add_settings_error( 'reset_downloads', 'reset_downloads_updated', esc_html__( 'Attachments downloads count has been reset.', 'download-attachments' ), 'updated' );
		}

		return $input;
	}
}

new Download_Attachments_Settings();