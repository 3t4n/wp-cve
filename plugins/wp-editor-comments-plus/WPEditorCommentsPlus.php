<?php
/**
 * WP Editor Comments Plus
 *
 * @package   wp-editor-comments-plus
 * @author    Neo Snc <neosnc1@gmail.com>
 * @license   GPL-2.0+
 * @link      https://wordpress.org/plugins/wp-editor-comments-plus/
 * @copyright 4-29-2016 Neo Snc
 */

/**
 * WP Editor Comments Plus class.
 *
 * @package WPEditorCommentsPlus
 * @author  Neo Snc <neosnc1@gmail.com>
 */
class WPEditorCommentsPlus {
	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = "1.1.4";

		/**
		 * Name of this plugin.
		 *
		 * @since    1.0.0
		 *
		 * @var      object
		 */
		protected $plugin_name = "WP Editor Comments Plus";

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = "wp-editor-comments-plus";

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		define( 'wpecp_prefix', 'wpecp_' );
		define( wpecp_prefix . 'local_dev', false );
		define( wpecp_prefix . 'javascript_globals', 'wpecpGlobals' );
		define( wpecp_prefix . 'ajax_prefix', 'wpecp_ajax_' );
		define( wpecp_ajax_prefix . 'option_update_delay', 2000 );
		define( wpecp_ajax_prefix . 'confirmation_delay', 3000 );
		define( wpecp_ajax_prefix . 'add_comment', wpecp_prefix . 'add_comment' );
		define( wpecp_ajax_prefix . 'update_comment', wpecp_prefix . 'update_comment' );
		define( wpecp_ajax_prefix . 'editing_enabled', wpecp_prefix . 'editing_enabled' );
		define( wpecp_ajax_prefix . 'editing_expiration', wpecp_prefix . 'editing_expiration' );
		define( wpecp_ajax_prefix . 'custom_classes', wpecp_prefix . 'custom_classes' );
		define( wpecp_ajax_prefix . 'wordpress_ids', wpecp_prefix . 'wordpress_ids' );
		define( wpecp_ajax_prefix . 'custom_toolbars', wpecp_prefix . 'custom_toolbars' );

		define( wpecp_prefix . 'toolbar1', 'bold italic strikethrough bullist numlist blockquote hr alignleft aligncenter alignright image link unlink wp_more spellchecker wp_adv' );
		define( wpecp_prefix . 'toolbar2', 'formatselect underline alignjustify forecolor pastetext removeformat charmap outdent indent undo redo wp_help' );
		define( wpecp_prefix . 'toolbar3', 'fontselect fontsizeselect' );
		define( wpecp_prefix . 'plugins', 'charmap,colorpicker,compat3x,directionality,fullscreen,hr,image,lists,paste,tabfocus,textcolor,wordpress,wpautoresize,wpdialogs,wpeditimage,wpemoji,wplink,wpview' );

		define( wpecp_prefix . 'regex_html_class', '/([^0-9a-z-_ ])+/i' );
		define( wpecp_prefix . 'regex_html_id', '/([^0-9a-z-_.# ])+/i' );

		// CSS Classes
		define( wpecp_prefix . 'css_prefix', wpecp_prefix . 'css_' );
		define( wpecp_prefix . 'id_prefix', wpecp_prefix . 'id_' );
		define( wpecp_css_prefix . 'button_class', 'wpecp-button' );
		define( wpecp_css_prefix . 'edit_container', 'wpecp-edit-container' );
		define( wpecp_css_prefix . 'edit_button_class', 'wpecp-edit-comment' );
		define( wpecp_css_prefix . 'reply_button_class', 'wpecp-reply-comment' );
		define( wpecp_css_prefix . 'submit_button_class', 'wpecp-submit-comment' );
		define( wpecp_css_prefix . 'submit_edit_button_class', 'wpecp-submit-edit' );
		define( wpecp_css_prefix . 'cancel_edit_button_class', 'wpecp-cancel-edit' );
		define( wpecp_css_prefix . 'comment_reply_button_class', 'comment-reply-link' );
		define( wpecp_css_prefix . 'comment_content', wpecp_prefix . 'comment_content' );
		define( wpecp_css_prefix . 'edit', 'wpecp-edit' );
		define( wpecp_css_prefix . 'editor', 'wpecp-editor' );
		define( wpecp_css_prefix . 'post_id', wpecp_prefix . 'post_id' );
		define( wpecp_css_prefix . 'comment_id', wpecp_prefix . 'comment_id' );
		define( wpecp_css_prefix . 'nonce', wpecp_prefix . 'nonce' );

		// WordPress IDs
		define( wpecp_id_prefix . 'comments', '#comments' );
		define( wpecp_id_prefix . 'respond', '#respond' );
		define( wpecp_id_prefix . 'comment_form', '#commentform' );
		define( wpecp_id_prefix . 'comment_textarea', '#comment' );
		define( wpecp_id_prefix . 'cancel_comment_reply', '#cancel-comment-reply-link' );
		define( wpecp_id_prefix . 'submit_comment', '#submit' );


		// Retrieve Options

		// TCP Custom CSS Button Classes
		$this->option_custom_classes_all = preg_replace( wpecp_regex_html_class, '', get_option( wpecp_ajax_custom_classes . '_all' ) );
		$this->option_custom_classes_reply = preg_replace( wpecp_regex_html_class, '', get_option( wpecp_ajax_custom_classes . '_reply' ) );
		$this->option_custom_classes_edit = preg_replace( wpecp_regex_html_class, '', get_option( wpecp_ajax_custom_classes . '_edit' ) );
		$this->option_custom_classes_submit = preg_replace( wpecp_regex_html_class, '', get_option( wpecp_ajax_custom_classes . '_submit' ) );
		$this->option_custom_classes_cancel = preg_replace( wpecp_regex_html_class, '', get_option( wpecp_ajax_custom_classes . '_cancel' ) );

		// sanitize WordPress options
		$this->option_editing_enabled = sanitize_html_class( get_option( wpecp_ajax_editing_enabled ) );
		$this->option_editing_enabled = ( $this->option_editing_enabled === 'off' ) ? 'off' : 'on';
		$this->option_editing_expiration = sanitize_key( get_option( wpecp_ajax_editing_expiration ) );

		$this->option_show_toolbars = false;

		$this->option_toolbar1 = preg_replace( wpecp_regex_html_class, '', get_option( wpecp_ajax_custom_toolbars . '_toolbar1' ) );
		$this->option_toolbar1 = ( trim( $this->option_toolbar1 ) == false ) ? wpecp_toolbar1 : $this->option_toolbar1;
		if ( $this->option_toolbar1 == 'none' ) { $this->option_toolbar1 = false;	} else { $this->option_show_toolbars = true; }

		$this->option_toolbar2 = preg_replace( wpecp_regex_html_class, '', get_option( wpecp_ajax_custom_toolbars . '_toolbar2' ) );
		$this->option_toolbar2 = ( trim( $this->option_toolbar2 ) == false ) ? wpecp_toolbar2 : $this->option_toolbar2;
		if ( $this->option_toolbar2 == 'none' ) { $this->option_toolbar2 = false;	} else { $this->option_show_toolbars = true; }

		// if toolbar1 is hidden and toolbar2 is not, replace toolbar1 with toolbar2.
		// this is done to initialize tinymce's configuration correctly.
		if ( ! $this->option_toolbar1 && $this->option_toolbar2 != false ) {
			$this->option_toolbar1 = $this->option_toolbar2;
			$this->option_toolbar2 = false;
		}

		$this->option_toolbar3 = preg_replace( wpecp_regex_html_class, '', get_option( wpecp_ajax_custom_toolbars . '_toolbar3' ) );
		$this->option_toolbar3 = ( trim( $this->option_toolbar3 ) == false ) ? '' : $this->option_toolbar3;
		if ( $this->option_toolbar3 == 'none' || strlen( $this->option_toolbar3 ) == 0 ) { $this->option_toolbar3 = false; } else { $this->option_show_toolbars = true; }

		$this->option_toolbar4 = preg_replace( wpecp_regex_html_class, '', get_option( wpecp_ajax_custom_toolbars . '_toolbar4' ) );
		$this->option_toolbar4 = ( trim( $this->option_toolbar4 ) == false ) ? '' : $this->option_toolbar4;
		if ( $this->option_toolbar4 == 'none' || strlen( $this->option_toolbar4 ) == 0 ) { $this->option_toolbar4 = false; } else { $this->option_show_toolbars = true; }

		$this->option_wp_id_comments = preg_replace( wpecp_regex_html_id, '', get_option( wpecp_ajax_wordpress_ids . '_comments' ) );
		$this->option_wp_id_comments = ( trim( $this->option_wp_id_comments ) == false ) ? wpecp_id_comments : $this->option_wp_id_comments;
		$this->option_wp_id_respond = preg_replace( wpecp_regex_html_id, '', get_option( wpecp_ajax_wordpress_ids . '_respond' ) );
		$this->option_wp_id_respond = ( trim( $this->option_wp_id_respond ) == false ) ? wpecp_id_respond : $this->option_wp_id_respond;
		$this->option_wp_id_comment_form = preg_replace( wpecp_regex_html_id, '', get_option( wpecp_ajax_wordpress_ids . '_comment_form' ) );
		$this->option_wp_id_comment_form = ( trim( $this->option_wp_id_comment_form ) == false ) ? wpecp_id_comment_form : $this->option_wp_id_comment_form;
		$this->option_wp_id_comment_textarea = preg_replace( wpecp_regex_html_id, '', get_option( wpecp_ajax_wordpress_ids . '_comment_textarea' ) );
		$this->option_wp_id_comment_textarea = ( trim( $this->option_wp_id_comment_textarea ) == false ) ? wpecp_id_comment_textarea : $this->option_wp_id_comment_textarea;
		$this->option_wp_id_comment_reply_link = preg_replace( wpecp_regex_html_id, '', get_option( wpecp_ajax_wordpress_ids . '_reply' ) );
		$this->option_wp_id_comment_reply_link = ( trim( $this->option_wp_id_comment_reply_link ) == false ) ? wpecp_css_comment_reply_button_class : $this->option_wp_id_comment_reply_link;
		$this->option_wp_id_cancel_comment_reply = preg_replace( wpecp_regex_html_id, '', get_option( wpecp_ajax_wordpress_ids . '_cancel' ) );
		$this->option_wp_id_cancel_comment_reply = ( trim( $this->option_wp_id_cancel_comment_reply ) == false ) ? wpecp_id_cancel_comment_reply : $this->option_wp_id_cancel_comment_reply;
		$this->option_wp_id_submit_comment = preg_replace( wpecp_regex_html_id, '', get_option( wpecp_ajax_wordpress_ids . '_submit' ) );
		$this->option_wp_id_submit_comment = ( trim( $this->option_wp_id_submit_comment ) == false ) ? wpecp_id_submit_comment : $this->option_wp_id_submit_comment;

		add_filter( 'plugin_action_links_' . wpecp_plugin_file, array( $this, 'add_plugin_action_links' ), 10, 4 );

		// Ajax methods
		add_action( 'wp_ajax_nopriv_' . wpecp_ajax_add_comment, array( $this, 'action_ajax_request' ) );
		add_action( 'wp_ajax_' . wpecp_ajax_add_comment, array( $this, 'action_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_' . wpecp_ajax_update_comment, array( $this, 'action_ajax_request' ) );
		add_action( 'wp_ajax_' . wpecp_ajax_update_comment, array( $this, 'action_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_' . wpecp_ajax_editing_enabled, array( $this, 'action_ajax_request' ) );
		add_action( 'wp_ajax_' . wpecp_ajax_editing_enabled, array( $this, 'action_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_' . wpecp_ajax_editing_expiration, array( $this, 'action_ajax_request' ) );
		add_action( 'wp_ajax_' . wpecp_ajax_editing_expiration, array( $this, 'action_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_' . wpecp_ajax_custom_classes, array( $this, 'action_ajax_request' ) );
		add_action( 'wp_ajax_' . wpecp_ajax_custom_classes, array( $this, 'action_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_' . wpecp_ajax_wordpress_ids, array( $this, 'action_ajax_request' ) );
		add_action( 'wp_ajax_' . wpecp_ajax_wordpress_ids, array( $this, 'action_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_' . wpecp_ajax_custom_toolbars, array( $this, 'action_ajax_request' ) );
		add_action( 'wp_ajax_' . wpecp_ajax_custom_toolbars, array( $this, 'action_ajax_request' ) );

		// initialize admin functions
		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		// load WP Editor Comments Plus
		add_action( 'init', array( $this, 'initialize_wp_editor_comments_plus' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn"t been set, set it now.
		if (null == self::$instance) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate($network_wide) {
		// TODO: Define activation functionality here
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate($network_wide) {
		// TODO: Define deactivation functionality here
	}

	/**
	 * Add plugin settings link
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_action_links ( $actions, $plugin_file, $plugin_data, $context ) {
		$wpecp_plugin_links = array(
			'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">Settings</a>',
		);
		return array_merge( $actions, $wpecp_plugin_links );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters("plugin_locale", get_locale(), $domain);

		load_textdomain($domain, WP_LANG_DIR . "/" . $domain . "/" . $domain . "-" . $locale . ".mo");
		load_plugin_textdomain($domain, false, dirname(plugin_basename(__FILE__)) . "/lang/");
	}

	/**
	 * Initialize the plugin functions.
	 *
	 * @since    1.0.0
	 */
	public function initialize_wp_editor_comments_plus() {
		if ( is_admin() ) { return; }

		// Editor JavaScript Globals
		$this->wpecp_plugin_javascript_globals = array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'editorStyles' => includes_url( "js/tinymce/skins/wordpress/wp-content.css", __FILE__ ),
			'optionUpdateDelay' => wpecp_ajax_option_update_delay,
			'addCommentAction' => wpecp_ajax_add_comment,
			'updateCommentAction' => wpecp_ajax_update_comment,
			'editingExpiration' => $this->option_editing_expiration,

			wpecp_prefix . 'plugins' => wpecp_plugins,
			wpecp_prefix . 'show_toolbars' => $this->option_show_toolbars,
			wpecp_prefix . 'toolbar1' => $this->option_toolbar1,
			wpecp_prefix . 'toolbar2' => $this->option_toolbar2,
			wpecp_prefix . 'toolbar3' => $this->option_toolbar3,
			wpecp_prefix . 'toolbar4' => $this->option_toolbar4,

			// Classes
			wpecp_css_prefix . 'button' => wpecp_css_button_class,
			wpecp_css_prefix . 'edit_button' => wpecp_css_edit_button_class,
			wpecp_css_prefix . 'reply_button' => wpecp_css_reply_button_class,
			wpecp_css_prefix . 'submit_button' => wpecp_css_submit_button_class,
			wpecp_css_prefix . 'edit_container' => wpecp_css_edit_container,
			wpecp_css_prefix . 'submit_edit_button' => wpecp_css_submit_edit_button_class,
			wpecp_css_prefix . 'cancel_edit_button' => wpecp_css_cancel_edit_button_class,
			wpecp_css_prefix . 'comment_reply_button' => wpecp_css_comment_reply_button_class,
			wpecp_css_prefix . 'edit' => wpecp_css_edit,
			wpecp_css_prefix . 'editor' => wpecp_css_editor,
			wpecp_css_prefix . 'comment_content' => wpecp_css_comment_content,
			wpecp_css_prefix . 'post_id' => wpecp_css_comment_id,
			wpecp_css_prefix . 'comment_id' => wpecp_css_comment_id,
			wpecp_css_prefix . 'nonce' => wpecp_css_nonce,
			wpecp_css_prefix . 'button_custom' => $this->option_custom_classes_all,
			wpecp_css_prefix . 'reply_button_custom' => $this->option_custom_classes_reply,
			wpecp_css_prefix . 'edit_button_custom' => $this->option_custom_classes_edit,
			wpecp_css_prefix . 'submit_button_custom' => $this->option_custom_classes_submit,
			wpecp_css_prefix . 'cancel_button_custom' => $this->option_custom_classes_cancel,
			// IDs
			wpecp_id_prefix . 'comments' => $this->option_wp_id_comments,
			wpecp_id_prefix . 'respond' => $this->option_wp_id_respond,
			wpecp_id_prefix . 'comment_form' => $this->option_wp_id_comment_form,
			wpecp_id_prefix . 'comment_textarea' => $this->option_wp_id_comment_textarea,
			wpecp_id_prefix . 'comment_reply' => $this->option_wp_id_comment_reply_link,
			wpecp_id_prefix . 'cancel_comment_reply' => $this->option_wp_id_cancel_comment_reply,
			wpecp_id_prefix . 'submit_comment' => $this->option_wp_id_submit_comment

		);

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Define custom functionality.
		add_filter( 'tiny_mce_before_init', array( $this, 'filter_format_tinymce' ), 10 );
		add_filter( 'mce_buttons', array( $this, 'filter_tinymce_buttons_1' ), 10, 2 );
		add_filter( 'mce_buttons_2', array( $this, 'filter_tinymce_buttons_2' ), 10 );
		add_filter( 'preprocess_comment', array( $this, 'filter_customize_allowed_tags' ), 10 );
		add_filter( 'comment_form_defaults', array( $this, 'filter_comment_form_defaults' ), 10 );
		add_filter( 'comment_form_field_comment', array( $this, 'filter_tinymce_editor' ), 10 );
		add_filter( 'comment_reply_link', array( $this, 'filter_comment_reply_link' ), 10, 3 );
		add_filter( 'comment_reply_link_args', array( $this, 'filter_comment_reply_link_args' ), 10, 3 );
		add_filter( 'comment_text', array( $this, 'filter_comment_editing' ), 10, 2 );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if (!isset($this->plugin_screen_hook_suffix)) {
			return;
		}

		$screen = get_current_screen();
		if ($screen->id == $this->plugin_screen_hook_suffix) {
			wp_enqueue_style( "jquery-ui", plugins_url( "src/styles/jquery-ui.min.css", __FILE__ ), array(), $this->version );
		}

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		global $wp_version;
		wp_enqueue_style( $this->plugin_slug . "-dashicons-css", includes_url( "css/dashicons.min.css", __FILE__ ) );
		wp_enqueue_style( $this->plugin_slug . "-editor-buttons-css", includes_url( "css/editor.min.css", __FILE__ ) );
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if (!isset($this->plugin_screen_hook_suffix)) {
			return;
		}

		$screen = get_current_screen();

		if ( $screen->id == $this->plugin_screen_hook_suffix ) {

			// Admin JavaScript Globals
			$this->wpecp_admin_javascript_globals = array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'optionUpdateDelay' => wpecp_ajax_option_update_delay,
				'optionConfirmationDelay' => wpecp_ajax_confirmation_delay,
				'editingEnabledAction' => wpecp_ajax_editing_enabled,
				'editingExpirationAction' => wpecp_ajax_editing_expiration,
				'customClassesAction' => wpecp_ajax_custom_classes,
				'wordpressIdsAction' => wpecp_ajax_wordpress_ids,
				'customToolbarsAction' => wpecp_ajax_custom_toolbars
			);

			wp_enqueue_script( 'jquery-ui-core', array( 'jquery' ) );
			wp_enqueue_script( 'jquery-ui-spinner', array( 'jquery-ui-core' ) );

			if ( wpecp_local_dev ) {
				wp_register_script( $this->plugin_slug . '-admin-script', 'http://localhost:8000/assets/wpEditorCommentsPlus.js', array( 'jquery', 'backbone' ),	$this->version, false );
			} else {
				wp_register_script( $this->plugin_slug . "-admin-script", plugins_url( "dist/assets/wpEditorCommentsPlus.js", __FILE__), array( 'jquery', 'backbone' ), $this->version );
			}

			wp_localize_script( $this->plugin_slug . '-admin-script', wpecp_javascript_globals, json_encode( $this->wpecp_admin_javascript_globals ) );
			wp_enqueue_script( $this->plugin_slug . '-admin-script' );

		}

	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( comments_open() ) {
			// replace comment-reply to handle tinymce form moves
		  wp_deregister_script( 'comment-reply' );

			if ( wpecp_local_dev ) {
				wp_register_script( $this->plugin_slug . '-plugin-script', 'http://localhost:8000/assets/wpEditorCommentsPlus.js', array( 'jquery', 'backbone' ),	$this->version, true );
			} else {
				wp_register_script( $this->plugin_slug . "-plugin-script", plugins_url( "dist/assets/wpEditorCommentsPlus.js", __FILE__ ), array( 'jquery', 'backbone' ),	$this->version, false );
			}
			// Instantiate Javascript Globals for plugin script
			wp_localize_script( $this->plugin_slug . '-plugin-script', wpecp_javascript_globals, json_encode( $this->wpecp_plugin_javascript_globals ) );
			wp_enqueue_script( $this->plugin_slug . '-plugin-script' );
		}
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		if ( current_user_can( 'administrator' ) ) {
			$this->plugin_screen_hook_suffix = add_options_page( __( $this->plugin_name . " - Settings", $this->plugin_slug ),
				__( $this->plugin_name, $this->plugin_slug ), "read", $this->plugin_slug, array( $this, "display_plugin_admin_page" ) );
		}
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		if ( current_user_can( 'administrator' ) ) {
			include_once("views/admin.php");
		}
	}


	/**
	* check if current user can edit comment
	* @since    1.0.0
	*/
	public function user_can_edit( $comment_user_id ) {
		global $current_user;
		if ( ! $current_user ) { get_currentuserinfo(); }
		$can_edit = current_user_can( 'moderate_comments' );

		// if user can moderate comments (admin) then user can edit
		if ( $can_edit ) { return true; }
		// else if user is comment author then user can edit
		else if ( $comment_user_id == $current_user->ID ) { return true; }
		// else user cannot edit
		else { return false; }
	}



	/**
	 * @since    1.0.0
	 */
	public function wpecp_add_comment( $post_id, $content ) {
		global 	$post,
				$current_user;

		get_currentuserinfo();

		if ( ! current_user_can( 'edit_posts' ) ) { wp_send_json_error( 'permission denied' ); }

		$add_comment = array(
			'comment_post_ID' => $post_id,
			'comment_content' => $content,
			'user_id' => $current_user->ID,
			'comment_author' => $current_user->display_name,
			'comment_author_url' => $current_user->user_url,
			'comment_author_email' => $current_user->user_email
		);

		if ( wp_new_comment( $add_comment ) ) {
			wp_send_json( $add_comment );
		} else {
			wp_send_json_error( 'failed to update comment' );
		}
	}

	/**
	 * @since    1.0.0
	 */
	public function wpecp_update_comment( $post_id, $comment_id, $content ) {
		global 	$post,
				$current_user;

		get_currentuserinfo();
		$comment = get_comment( $comment_id );
		$comment_age = current_time( 'timestamp' ) - strtotime( $comment->comment_date );
		$comment_age = floor( $comment_age / 60 );

		if ( ! current_user_can( 'edit_posts' ) &&
			 $current_user->ID != $comment->user_id ) { wp_send_json_error( 'permission denied' ); }

		// comment editing has expiration period
		if ( $this->option_editing_expiration > 0 &&
			// if the comment is past editing period expiration
			$comment_age > $this->option_editing_expiration &&
			// user is not an administrator
			! current_user_can( 'administrator' ) ) { wp_send_json_error( 'permission denied' ); }

		$update = array(
			'comment_ID' => $comment_id,
			'comment_content' => stripslashes( $content )
		);

		if ( wp_update_comment( $update ) ) {
			wp_send_json( $update );
		} else {
			wp_send_json_error( 'failed to update comment' );
		}
	}

	/**
	 * @since    1.0.0
	 */
	public function wpecp_save_option( $option, $value ) {
		if ( ! current_user_can( 'manage_options' ) ) { wp_send_json_error( 'permission denied' ); }
		return update_option( $option, $value );
	}

	/**
	 * @since    1.0.0
	 */
	public function action_ajax_request() {

		// validate ajax request variables
		$result = false;
		$action = sanitize_key( $_REQUEST[ 'action' ] );
		$security = sanitize_text_field( $_REQUEST[ 'security' ] );

		// check for valid ajax request variables
		if ( ! $action ||
			 ! $security ) { wp_send_json_error( 'bad request' ); }

		global $allowedtags;
 		// add additional tags to allowed tags in comments
 		$allowedtags = array_merge( $allowedtags, $this->wpecp_new_tags() );

		switch ( $action ) {
			case wpecp_ajax_add_comment:
				$post_id = intval( $_REQUEST[ 'postId' ] );
				$comment_id = intval( $_REQUEST[ 'commentId' ] );
				$content = wp_kses( $_REQUEST[ 'content' ], $allowedtags );
				// check ajax referer's security nonce
				check_ajax_referer( wpecp_ajax_add_comment . $post_id, 'security' );

				$result = $this->wpecp_add_comment( $post_id, $content );
			break;

			case wpecp_ajax_update_comment:
				$post_id = intval( $_REQUEST[ 'postId' ] );
				$comment_id = intval( $_REQUEST[ 'commentId' ] );
				$content = wp_kses( $_REQUEST[ 'content' ], $allowedtags );
				if ( ! $comment_id ) { wp_send_json_error( 'bad request' ); }
				// check ajax referer's security nonce
				check_ajax_referer( wpecp_ajax_update_comment . $comment_id, 'security' );

				$result = $this->wpecp_update_comment( $post_id, $comment_id, $content );
			break;

			case wpecp_ajax_editing_enabled:
				check_ajax_referer( wpecp_ajax_editing_enabled, 'security' );
				// if user is not administrator
				if ( ! current_user_can( 'administrator' ) ) { wp_send_json_error( 'bad request' ); }
				$content = sanitize_key( $_REQUEST[ 'content' ] );
				$result = $this->wpecp_save_option( wpecp_ajax_editing_enabled, $content );
			break;

			case wpecp_ajax_editing_expiration:
				check_ajax_referer( wpecp_ajax_editing_expiration, 'security' );
				// if user is not administrator
				if ( ! current_user_can( 'administrator' ) ) { wp_send_json_error( 'bad request' ); }
				$content = sanitize_key( $_REQUEST[ 'content' ] );
				$result = $this->wpecp_save_option( wpecp_ajax_editing_expiration, $content );
			break;

			case wpecp_ajax_custom_classes:
				check_ajax_referer( wpecp_ajax_custom_classes, 'security' );
				// if user is not administrator
				if ( ! current_user_can( 'administrator' ) ) { wp_send_json_error( 'bad request' ); }
				foreach( $_REQUEST[ 'content' ] as $key => $option ) {
					$option = preg_replace( wpecp_regex_html_class, '', $option );
					if ( ! sanitize_key( $key ) ) { $result = false; }
					else { $result = $this->wpecp_save_option( wpecp_ajax_custom_classes . $key, $option ); }
				}

				$result = true;
			break;

			case wpecp_ajax_wordpress_ids:
				check_ajax_referer( wpecp_ajax_wordpress_ids, 'security' );
				// if user is not administrator
				if ( ! current_user_can( 'administrator' ) ) { wp_send_json_error( 'bad request' ); }
				foreach( $_REQUEST[ 'content' ] as $key => $option ) {
					$option = preg_replace( wpecp_regex_html_id, '', $option );
					if ( ! sanitize_key( $key ) ) { $result = false; break; }
					else { $result = $this->wpecp_save_option( wpecp_ajax_wordpress_ids . $key, $option ); }
				}

				$result = true;
			break;

			case wpecp_ajax_custom_toolbars:
				check_ajax_referer( wpecp_ajax_custom_toolbars, 'security' );
				// if user is not administrator
				if ( ! current_user_can( 'administrator' ) ) { wp_send_json_error( 'bad request' ); }
				foreach( $_REQUEST[ 'content' ] as $key => $option ) {
					$option = preg_replace( wpecp_regex_html_class, '', strtolower( $option ) );
					if ( ! sanitize_key( $key ) ) { $result = false; break; }
					else { $result = $this->wpecp_save_option( wpecp_ajax_custom_toolbars . $key, $option ); }
				}
				$result = true;
			break;
		}

		wp_send_json( $result );
	}


	/**
	 * @since    1.0.0
	 */
	 public function filter_tinymce_buttons_1( $buttons ) {
		 $buttons = ( $this->option_toolbar1 == false ) ? [] : explode( ' ', $this->option_toolbar1 );

		 return $buttons;
	 }

	/**
	 * @since    1.0.0
	 */
	 public function filter_tinymce_buttons_2( $buttons ) {
		 $buttons = ( $this->option_toolbar2 == false ) ? [] : explode( ' ', $this->option_toolbar2 );

		 return $buttons;
	 }

	/**
	 * @since    1.0.0
	 */
	 public function filter_format_tinymce( $args ) {
	 	$args['remove_linebreaks'] = false;
	 	$args['gecko_spellcheck'] = true;
	 	$args['keep_styles'] = true;
	 	$args['accessibility_focus'] = true;
	 	$args['tabfocus_elements'] = 'major-publishing-actions';
	 	$args['media_strict'] = false;
	 	$args['paste_data_images'] = true;
	 	$args['paste_remove_styles'] = false;
	 	$args['paste_remove_spans'] = false;
	 	$args['paste_strip_class_attributes'] = 'none';
	 	$args['paste_text_use_dialog'] = true;
	 	$args['wpeditimage_disable_captions'] = true;
		$args['plugins'] = wpecp_plugins;
	 	//$args['content_css'] = get_template_directory_uri() . "/editor-style.css";
	 	$args['wpautop'] = true;
	 	$args['apply_source_formatting'] = false;
	  $args['block_formats'] = "Paragraph=p; Preformatted=pre; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4";
	 	if ( ! $this->option_show_toolbars ) { $args['toolbar'] = $this->option_show_toolbars; }
	 	$args['toolbar1'] = $this->option_toolbar1;
	 	$args['toolbar2'] = $this->option_toolbar2;
	 	$args['toolbar3'] = $this->option_toolbar3;
	 	$args['toolbar4'] = $this->option_toolbar4;

	 	return $args;
	 }

	/**
	 * @since    1.0.0
	 */
	public function filter_comment_form_defaults( $args ) {
		$args[ 'comment_field' ] = $this->filter_tinymce_editor();

		return $args;
	}

	/**
	 * @since    1.0.0
	 */
	public function filter_tinymce_editor() {
		// remove # from comment textarea id
		$comment_textarea = str_replace( '#', '', $this->option_wp_id_comment_textarea );
		$editor_config = array(
			'skin' => 'wp_theme',
	    'textarea_rows' => 12,
	    'teeny' => false,
			'tinymce' => array(
				'plugins' => wpecp_plugins,
				'theme_advanced_buttons1' => $this->option_toolbar1,
        'theme_advanced_buttons2' => $this->option_toolbar2,
				'theme_advanced_buttons3' => $this->option_toolbar3,
        'theme_advanced_buttons4' => $this->option_toolbar4
			),
			'wpeditimage_disable_captions' => true,
	    'quicktags' => false,
	    'media_buttons' => false
		);
		if ( ! $this->option_show_toolbars ) { $editor_config['toolbar'] = $this->option_show_toolbars; }

	  ob_start();

	  wp_editor( '', $comment_textarea, $editor_config );

	  $editor = ob_get_contents();

	  ob_end_clean();

	  return $editor;
	}

	/**
	 * @since    1.0.0
	 */
	public function filter_comment_editing( $content, $comment ) {

		if ( ! $this->user_can_edit( $comment->user_id ) ) { return $content; }

		$comment_id = $comment->comment_ID;
		$post_id = $comment->comment_post_ID;
		$nonce = wp_create_nonce( wpecp_ajax_update_comment . $comment_id );

		$wpecp_content = sprintf(
			'<div class="' . wpecp_css_comment_content . '" id="' . wpecp_css_comment_content . '%d" data-' . wpecp_css_post_id . '="%d" data-' . wpecp_css_comment_id . '="%d"	data-' . wpecp_css_nonce . '="%s">%s</div>',
			$comment_id,
			$post_id,
			$comment_id,
			$nonce,
			$content
		);

		$wpecp_editor = sprintf(
			'<div class="' . wpecp_css_editor . '" data-' . wpecp_css_comment_id . '="%d"></div>',
			$comment_id
		);

		return $wpecp_content . $wpecp_editor;
	}

	/**
	 * @since    1.0.0
	 */
	public function filter_comment_reply_link( $args ) {
		global $current_user;

		// insert custom CSS classes
		$custom_classes = $this->option_custom_classes_all . ' ' . $this->option_custom_classes_reply;
		$args = str_replace( "class='" . $this->option_wp_id_comment_reply_link . "'", 'class="' . $this->option_wp_id_comment_reply_link . ' ' . $custom_classes . '"', $args );

		return $args;
	}

	/**
	 * @since    1.0.0
	 */
	public function filter_comment_reply_link_args( $args, $comment, $post ) {
		global $current_user;

		$comment_age = current_time( 'timestamp' ) - strtotime( $comment->comment_date );
		$comment_age = floor( $comment_age );

		// Insert edit button targets
		// If editing option is enabled
		if ( ( $this->option_editing_enabled === 'on' &&
				// if user is logged in
				is_user_logged_in() &&
				// if user created this comment
				$comment->user_id == $current_user->ID &&
				// if comment editing does not expire
				( $this->option_editing_expiration == 0 ||
				// or comment editing has not expired
					$comment_age <= $this->option_editing_expiration )
			) ||
			// Or user is administrator
			current_user_can( 'administrator' )
			) {

			$nonce = wp_create_nonce( wpecp_ajax_update_comment . $comment->comment_ID );

			$wpecp_edit_link = '<div class="' . wpecp_css_edit . '" data-' . wpecp_css_comment_id . '="' . $comment->comment_ID . '"></div>' . PHP_EOL;

			$args[ 'before' ] .= $wpecp_edit_link;
		}

		return $args;
	}

	/**
	* customise list of allowed HTML tags in comments
	* @since    1.0.0
	*/
	public function wpecp_new_tags() {
		// additionally allowed tags
		$new_tags = array(
			'a' => array(
				'href' => true,
				'title' => true,
				'target' => true
			),
			'del' => true,
			'strong' => true,
			'em' => true,
			'h1' => array(
				'style' => true
			),
			'h2' => array(
				'style' => true
			),
			'h3' => array(
				'style' => true
			),
			'h4' => array(
				'style' => true
			),
			'h5' => array(
				'style' => true
			),
			'h6' => array(
				'style' => true
			),
			'img' => array(
				'src' => true,
				'alt' => true,
				'style' => true,
				'title' => true
			),
			'p' => array(
				'style' => true,
			),
			'pre' => true,
			'span' => array(
				'style' => true,
			),
			'ol' => array(
				'style' => true,
			),
			'ul' => array(
				'style' => true,
			),
			'li' => array(
				'style' => true,
			)
		);

		return $new_tags;
	}

	/**
	* customise list of allowed HTML tags in comments
	* @since    1.0.0
	*/
	public function filter_customize_allowed_tags( $comment_data ) {
		global $allowedtags;

		$allowedtags = array_merge( $allowedtags, $this->wpecp_new_tags() );

		return $comment_data;
	}

}
