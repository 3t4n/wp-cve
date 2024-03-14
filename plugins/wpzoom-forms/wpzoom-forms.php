<?php
/**
 * WPZOOM Forms - Custom forms for WordPress, by WPZOOM.
 *
 * @package   WPZOOM_Forms
 * @author    WPZOOM
 * @copyright 2023 WPZOOM
 * @license   GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: WPZOOM Forms
 * Plugin URI:  https://www.wpzoom.com/plugins/wpzoom-forms
 * Description: Simple, user-friendly contact form plugin for WordPress that utilizes Gutenberg blocks for easy form building and customization.
 * Author:      WPZOOM
 * Author URI:  https://www.wpzoom.com
 * Version:     1.1.4
 * License:     GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// settings page url attribute
define( 'WPZOOM_FORMS_SETTINGS_PAGE', 'wpzf-settings' );

if ( ! defined( 'WPZOOM_FORMS_VERSION' ) ) {
	define( 'WPZOOM_FORMS_VERSION', get_file_data( __FILE__, [ 'Version' ] )[0] ); // phpcs:ignore
}

define( 'WPZOOM_FORMS__FILE__', __FILE__ );
define( 'WPZOOM_FORMS_PLUGIN_BASE', plugin_basename( WPZOOM_FORMS__FILE__ ) );
define( 'WPZOOM_FORMS_PLUGIN_DIR', dirname( WPZOOM_FORMS_PLUGIN_BASE ) );

define( 'WPZOOM_FORMS_PATH', plugin_dir_path( WPZOOM_FORMS__FILE__ ) );
define( 'WPZOOM_FORMS_URL', plugin_dir_url( WPZOOM_FORMS__FILE__ ) );

// Instance the plugin
$wpzoom_forms = new WPZOOM_Forms();

// Register plugin activation hook
register_activation_hook( __FILE__, array( $wpzoom_forms, 'activate' ) );

// Hook the plugin into WordPress
add_action( 'init', array( $wpzoom_forms, 'init' ), 9 );



/**
 * Class WPZOOM_Forms
 *
 * Main container class of the ZOOM Forms WordPress plugin.
 *
 * @since 1.0.0
 */
class WPZOOM_Forms {
	/**
	 * The version of this plugin.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public const VERSION = '1.1.3';

	/**
	 * Whether the plugin has been initialized.
	 *
	 * @var    boolean
	 * @access public
	 * @since  1.0.0
	 */
	public $initialized = false;

	/**
	 * The path to this plugin's root directory.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $plugin_dir_path;

	/**
	 * The URL to this plugin's root directory.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $plugin_dir_url;

	/**
	 * The path to this plugin's "main" directory.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $main_dir_path;

	/**
	 * The URL to this plugin's "main" directory.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $main_dir_url;

	/**
	 * The URL to this plugin's "dist" directory.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $dist_dir_url;

	/**
	 * Initializes the plugin and sets up needed hooks and features.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 * @see    ZOOM_Forms::block_setup()
	 */
	public function init() {
		if ( false === $this->initialized ) {
			$this->plugin_dir_path = plugin_dir_path( __FILE__ );
			$this->plugin_dir_url  = plugin_dir_url( __FILE__ );
			$this->main_dir_path   = trailingslashit( $this->plugin_dir_path . 'build' );
			$this->main_dir_url    = trailingslashit( $this->plugin_dir_url . 'build' );
			$this->dist_dir_url    = trailingslashit( $this->plugin_dir_url . 'dist' );

			load_plugin_textdomain( 'wpzoom-forms', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

			add_filter( 'allowed_block_types_all',                      array( $this, 'filter_allowed_block_types' ),        10, 2 );
			add_filter( 'block_categories_all',                         array( $this, 'filter_block_categories' ),           10, 2 );
			add_filter( 'post_row_actions',                             array( $this, 'modify_row_actions' ),                10, 2 );
			add_filter( 'bulk_actions-edit-wpzf-form',                  array( $this, 'remove_bulk_actions' ),               10 );
			add_filter( 'bulk_actions-edit-wpzf-submission',            array( $this, 'remove_bulk_actions' ),               10 );
			add_filter( 'manage_edit-wpzf-form_columns',                array( $this, 'post_list_columns_form' ),            10 );
			add_filter( 'manage_edit-wpzf-submission_columns',          array( $this, 'post_list_columns_submit' ),          10 );
			add_filter( 'manage_edit-wpzf-submission_sortable_columns', array( $this, 'post_list_sortable_columns_submit' ), 10 );
			add_filter( 'screen_options_show_screen',                   array( $this, 'remove_screen_options' ),             10, 2 );
			add_filter( 'views_edit-wpzf-form',                         array( $this, 'post_list_views' ),                   10 );
			add_filter( 'list_table_primary_column',                    array( $this, 'post_list_primary_column' ),          10, 2 );
			add_action( 'admin_menu',                                   array( $this, 'admin_menu' ),                        10 );
			add_action( 'admin_enqueue_scripts',                        array( $this, 'admin_enqueue_scripts' ),             100 );
			add_action( 'enqueue_block_editor_assets',                  array( $this, 'register_backend_assets' ),           10 );
			add_action( 'enqueue_block_assets',                         array( $this, 'register_frontend_assets' ),          10 );
			add_action( 'wp_enqueue_scripts',                           array( $this, 'enqueue_frontend_scripts' ),          10 );
			add_action( 'all_admin_notices',                            array( $this, 'admin_page_header' ),                 1 );
			add_action( 'in_admin_footer',                              array( $this, 'admin_page_footer' ),                 10 );
			add_filter( 'admin_body_class',                             array( $this, 'admin_body_class_filter' ),           10 );
			add_action( 'manage_wpzf-form_posts_custom_column',         array( $this, 'post_list_custom_columns_form' ),     10, 2 );
			add_action( 'manage_wpzf-submission_posts_custom_column',   array( $this, 'post_list_custom_columns_submit' ),   10, 2 );
			add_action( 'pre_get_posts',                                array( $this, 'sort_custom_column' ),                10 );
			add_action( 'in_admin_header',                              array( $this, 'remove_meta_boxes' ),                 100 );
			add_action( 'add_meta_boxes_wpzf-submission',               array( $this, 'add_meta_boxes' ),                    10 );
			add_action( 'admin_post_wpzf_submit',                       array( $this, 'action_form_post' ),                  10 );
            add_action( 'admin_post_nopriv_wpzf_submit',                array( $this, 'action_form_post' ),                  10 );

			register_post_type(
				'wpzf-form',
				array(
					'label'               => __( 'WPZOOM Forms', 'wpzoom-forms' ),
					'labels'              => array(
						'name'                     => _x( 'WPZOOM Forms', 'post type general name', 'wpzoom-forms' ),
						'singular_name'            => _x( 'Form', 'post type singular name', 'wpzoom-forms' ),
						'add_new'                  => _x( 'Add New', 'post', 'wpzoom-forms' ),
						'add_new_item'             => __( 'Add New Form', 'wpzoom-forms' ),
						'edit_item'                => __( 'Edit Form', 'wpzoom-forms' ),
						'new_item'                 => __( 'New Form', 'wpzoom-forms' ),
						'view_item'                => __( 'View Form', 'wpzoom-forms' ),
						'view_items'               => __( 'View Forms', 'wpzoom-forms' ),
						'search_items'             => __( 'Search Forms', 'wpzoom-forms' ),
						'not_found'                => __( 'No forms found.', 'wpzoom-forms' ),
						'not_found_in_trash'       => __( 'No forms found in Trash.', 'wpzoom-forms' ),
						'all_items'                => __( 'All Forms', 'wpzoom-forms' ),
						'archives'                 => __( 'Form Archives', 'wpzoom-forms' ),
						'attributes'               => __( 'Form Attributes', 'wpzoom-forms' ),
						'insert_into_item'         => __( 'Insert into form', 'wpzoom-forms' ),
						'uploaded_to_this_item'    => __( 'Uploaded to this form', 'wpzoom-forms' ),
						'featured_image'           => _x( 'Featured image', 'post', 'wpzoom-forms' ),
						'set_featured_image'       => _x( 'Set featured image', 'post', 'wpzoom-forms' ),
						'remove_featured_image'    => _x( 'Remove featured image', 'post', 'wpzoom-forms' ),
						'use_featured_image'       => _x( 'Use as featured image', 'post', 'wpzoom-forms' ),
						'filter_items_list'        => __( 'Filter forms list', 'wpzoom-forms' ),
						'items_list_navigation'    => __( 'Forms list navigation', 'wpzoom-forms' ),
						'items_list'               => __( 'Forms list', 'wpzoom-forms' ),
						'item_published'           => __( 'Form saved.', 'wpzoom-forms' ),
						'item_published_privately' => __( 'Form saved privately.', 'wpzoom-forms' ),
						'item_reverted_to_draft'   => __( 'Form reverted to draft.', 'wpzoom-forms' ),
						'item_scheduled'           => __( 'Form scheduled.', 'wpzoom-forms' ),
						'item_updated'             => __( 'Form updated.', 'wpzoom-forms' )
					),
					'public'              => true,
					'exclude_from_search' => true,
					'publicly_queryable'  => false,
					'show_in_rest'        => true,
					'menu_position'       => 30,
					'menu_icon'           => 'dashicons-email-alt2',
					'supports'            => array( 'title', 'editor', 'custom-fields' )
				)
			);
			
			register_post_type(
				'wpzf-submission',
				array(
					'label'               => __( 'WPZOOM Form Submissions', 'wpzoom-forms' ),
					'labels'              => array(
						'name'                     => _x( 'WPZOOM Form Submissions', 'post type general name', 'wpzoom-forms' ),
						'singular_name'            => _x( 'WPZOOM Form Submission', 'post type singular name', 'wpzoom-forms' ),
						'add_new'                  => _x( 'Add New', 'post', 'wpzoom-forms' ),
						'add_new_item'             => __( 'Add New Submission', 'wpzoom-forms' ),
						'edit_item'                => __( 'WPZOOM Form Submission', 'wpzoom-forms' ),
						'new_item'                 => __( 'New Submission', 'wpzoom-forms' ),
						'view_item'                => __( 'View Submission', 'wpzoom-forms' ),
						'view_items'               => __( 'View Submissions', 'wpzoom-forms' ),
						'search_items'             => __( 'Search Submissions', 'wpzoom-forms' ),
						'not_found'                => __( 'No submissions found.', 'wpzoom-forms' ),
						'not_found_in_trash'       => __( 'No submissions found in Trash.', 'wpzoom-forms' ),
						'all_items'                => __( 'Submissions', 'wpzoom-forms' ),
						'archives'                 => __( 'Submission Archives', 'wpzoom-forms' ),
						'attributes'               => __( 'Submission Attributes', 'wpzoom-forms' ),
						'insert_into_item'         => __( 'Insert into submission', 'wpzoom-forms' ),
						'uploaded_to_this_item'    => __( 'Uploaded to this submission', 'wpzoom-forms' ),
						'featured_image'           => _x( 'Featured image', 'post', 'wpzoom-forms' ),
						'set_featured_image'       => _x( 'Set featured image', 'post', 'wpzoom-forms' ),
						'remove_featured_image'    => _x( 'Remove featured image', 'post', 'wpzoom-forms' ),
						'use_featured_image'       => _x( 'Use as featured image', 'post', 'wpzoom-forms' ),
						'filter_items_list'        => __( 'Filter form submission list', 'wpzoom-forms' ),
						'items_list_navigation'    => __( 'Form Submissions list navigation', 'wpzoom-forms' ),
						'items_list'               => __( 'Form Submissions list', 'wpzoom-forms' ),
						'item_published'           => __( 'Form Submission saved.', 'wpzoom-forms' ),
						'item_published_privately' => __( 'Form Submission saved privately.', 'wpzoom-forms' ),
						'item_reverted_to_draft'   => __( 'Form Submission reverted to draft.', 'wpzoom-forms' ),
						'item_scheduled'           => __( 'Form Submission scheduled.', 'wpzoom-forms' ),
						'item_updated'             => __( 'Form Submission updated.', 'wpzoom-forms' )
					),
					'public'              => true,
					'exclude_from_search' => true,
					'publicly_queryable'  => false,
					'show_in_nav_menus'   => false,
					'show_in_admin_bar'   => false,
					'show_in_rest'        => false,
					'show_in_menu'        => 'edit.php?post_type=wpzf-form',
					'menu_position'       => 31,
					'menu_icon'           => 'dashicons-email-alt2',
					'supports'            => array( '' ),
					'capabilities'        => array(
						'create_posts'  => 'do_not_allow',
						'publish_posts' => 'do_not_allow',
						'edit_posts'    => 'edit_posts',
						'delete_posts'  => 'delete_posts'
					),
					'map_meta_cap'        => true
				)
			);

			register_post_status(
				'spam',
				array(
					'label'       => esc_html_x( 'Spam', 'post', 'wpzoom-forms' ),
					'label_count' => _n_noop( 'Spam <span class="count">(%s)</span>', 'Spam <span class="count">(%s)</span>', 'wpzoom-forms' ),
					'public'      => false
				)
			);

			register_meta(
				'post',
				'_form_method',
				array(
					'object_subtype'    => 'wpzf-form',
					'show_in_rest'      => true,
					'single'            => true,
					'type'              => 'string',
					'default'           => 'email',
					'sanitize_callback' => 'sanitize_text_field',
					'auth_callback'     => function() { return current_user_can( 'edit_posts' ); }
				)
			);

			register_meta(
				'post',
				'_form_email',
				array(
					'object_subtype'    => 'wpzf-form',
					'show_in_rest'      => true,
					'single'            => true,
					'type'              => 'string',
					'default'           => '',
					'sanitize_callback' => 'sanitize_text_field',
					'auth_callback'     => function() { return current_user_can( 'edit_posts' ); }
				)
			);

			add_shortcode( 'wpzf_form', array( $this, 'shortcode_output' ) );

			$form_pto           = get_post_type_object( 'wpzf-form' );
			$form_pto->template = array( array( 'wpzoom-forms/form' ) );

			if ( $this->is_post_type( 'wpzf-form' ) ) {
				$this->forms_display();
				$this->register_blocks();
			} elseif ( $this->is_post_type( 'wpzf-submission' ) ) {
				$this->submissions_display();
			} else {
				$this->register_form_block();
			}

			$this->initialized = true;
		}
	}

	/**
	 * Runs once during the activation of the plugin to run some one-time setup functions.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 * @see    ZOOM_Forms::init()
	 */
	public function activate() {
		$this->init();

		flush_rewrite_rules();

		// If this is the first time activating the plugin...
		if ( ! get_option( 'wpzf-form_first-activate' ) ) {
			// Insert an initial example form post
			wp_insert_post( array(
				'post_type'    => 'wpzf-form',
				'post_status'  => 'publish',
				'post_title'   => __( 'Example Form', 'wpzoom-forms' ),
				'post_content' => "<!-- wp:wpzoom-forms/form -->\n<div class=\"wp-block-wpzoom-forms-form\"><!-- wp:group -->\n<div class=\"wp-block-group\"><!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column {\"width\":\"100%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:100%\"><!-- wp:wpzoom-forms/text-name-field {\"id\":\"input_name\",\"name\":\"Name\",\"label\":\"Name\",\"className\":\"fullwidth\"} -->\n<label for=\"input_name\"><label for=\"input_name\">Name</label><sup class=\"wp-block-wpzoom-forms-required\">*</sup></label><input type=\"text\" name=\"input_name\" id=\"input_name\" placeholder=\"\" required class=\"wp-block-wpzoom-forms-text-name-field fullwidth\"/>\n<!-- /wp:wpzoom-forms/text-name-field -->\n\n<!-- wp:wpzoom-forms/text-email-field {\"id\":\"input_email\",\"name\":\"Email\",\"label\":\"Email\",\"replyto\":true,\"className\":\"fullwidth\"} -->\n<label for=\"input_email\"><label for=\"input_email\">Email</label><sup class=\"wp-block-wpzoom-forms-required\">*</sup></label><input type=\"email\" name=\"input_email\" id=\"input_email\" placeholder=\"\" required data-replyto=\"true\" class=\"wp-block-wpzoom-forms-text-email-field fullwidth\"/>\n<!-- /wp:wpzoom-forms/text-email-field -->\n\n<!-- wp:wpzoom-forms/text-plain-field {\"id\":\"input_subject\",\"name\":\"Subject\",\"label\":\"Subject\",\"subject\":true,\"className\":\"fullwidth\"} -->\n<label for=\"input_subject\"><label for=\"input_subject\">Subject</label><sup class=\"wp-block-wpzoom-forms-required\">*</sup></label><input type=\"text\" name=\"input_subject\" id=\"input_subject\" placeholder=\"\" required data-subject=\"true\" class=\"wp-block-wpzoom-forms-text-plain-field fullwidth\"/>\n<!-- /wp:wpzoom-forms/text-plain-field -->\n\n<!-- wp:wpzoom-forms/textarea-field {\"id\":\"input_message\",\"name\":\"Message\",\"label\":\"Message\",\"className\":\"fullwidth\"} -->\n<label for=\"input_message\"><label for=\"input_message\">Message</label><sup class=\"wp-block-wpzoom-forms-required\">*</sup></label><textarea name=\"input_message\" id=\"input_message\" cols=\"55\" rows=\"10\" placeholder=\"\" required class=\"wp-block-wpzoom-forms-textarea-field fullwidth\"></textarea>\n<!-- /wp:wpzoom-forms/textarea-field --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column {\"width\":\"30%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:30%\"><!-- wp:wpzoom-forms/submit-field {\"id\":\"input_submit\"} -->\n<input type=\"submit\" id=\"input_submit\" value=\"Submit\" class=\"wp-block-wpzoom-forms-submit-field\"/>\n<!-- /wp:wpzoom-forms/submit-field --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"width\":\"70%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:70%\"><!-- wp:paragraph {\"align\":\"right\",\"style\":{\"typography\":{\"fontSize\":16}}} -->\n<p class=\"has-text-align-right\" style=\"font-size:16px\">Fields marked with <strong class=\"has-accent-color has-text-color\">*</strong> are required.</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:group --></div>\n<!-- /wp:wpzoom-forms/form -->",
				'meta_input'   => array(
					'_form_method' => 'email',
					'_form_email'  => trim( get_option( 'admin_email' ) )
				)
			) );

			// Make sure we don't insert an example form on every activation
			update_option( 'wpzf-form_first-activate', true );
		}
	}

	/**
	 * Modifies the way the backend forms list is displayed.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function forms_display() {
		global $wp_post_statuses;

		$publish                          = $wp_post_statuses['publish'];
		$publish->label                   = __( 'Saved', 'wpzoom-forms' );
		$publish->label_count[0]          = __( 'Saved <span class="count">(%s)</span>', 'wpzoom-forms' );
		$publish->label_count[1]          = __( 'Saved <span class="count">(%s)</span>', 'wpzoom-forms' );
		$publish->label_count['singular'] = __( 'Saved <span class="count">(%s)</span>', 'wpzoom-forms' );
		$publish->label_count['plural']   = __( 'Saved <span class="count">(%s)</span>', 'wpzoom-forms' );

		$wp_post_statuses['publish']      = $publish;

		$wp_post_statuses = array_diff_key(
			$wp_post_statuses,
			array_flip( array(
				'future',
				'pending',
				'private',
				'request-pending',
				'request-confirmed',
				'request-failed',
				'request-completed'
			) )
		);

		add_filter( 'post_date_column_status', function() { return __( 'Last Modified', 'wpzoom-forms' ); } );
		add_filter( 'post_date_column_time',   function( $time, $post ) {
			return sprintf(
				__( '%1$s at %2$s', 'wpzoom-forms' ),
				get_the_modified_time( __( 'Y/m/d', 'wpzoom-forms' ), $post ),
				get_the_modified_time( __( 'g:i a', 'wpzoom-forms' ), $post )
			);
		}, 10, 2 );
	}

	/**
	 * Modifies the way the backend submissions list is displayed.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function submissions_display() {
		global $wp_post_statuses;

		$wp_post_statuses = array_diff_key(
			$wp_post_statuses,
			array_flip( [
				'future',
				'pending',
				'private',
				'request-pending',
				'request-confirmed',
				'request-failed',
				'request-completed'
			] )
		);

		add_filter( 'post_date_column_status', function() { return __( 'Submitted', 'wpzoom-forms' ); } );
	}

	/**
	 * Registers all the custom blocks for this plugin.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function register_blocks() {
		register_block_type(
			'wpzoom-forms/form',
			array(
				'script'        => 'wpzoom-forms-js-frontend-formblock',
				'style'         => 'wpzoom-forms-css-frontend-formblock',
				'editor_script' => 'wpzoom-forms-js-backend-main',
				'editor_style'  => 'wpzoom-forms-css-backend-main'
			)
		);

		foreach ( array( 'checkbox', 'email', 'label', 'name', 'phone', 'plain', 'radio', 'select', 'submit', 'textarea', 'website' ) as $block ) {
			register_block_type( $this->main_dir_path . 'fields/' . $block . '/block.json' );
		}
	}

	/**
	 * Registers the main form block used for inserting a form into a regular post/page.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function register_form_block() {
		register_block_type(
			'wpzoom-forms/form-block',
			array(
				'attributes'    => array(
					'formId' => array(
						'type'    => 'string',
						'default' => '-1'
					),
					'align' => array(
						'type'    => 'string',
						'default' => 'none'
					)
				),
				'script'          => 'wpzoom-forms-js-frontend-formblock',
				'style'           => 'wpzoom-forms-css-frontend-formblock',
				'editor_script'   => 'wpzoom-forms-js-backend-formblock',
				'editor_style'    => 'wpzoom-forms-css-backend-formblock',
				'render_callback' => array( $this, 'form_block_render' )
			)
		);
	}

	/**
	 * Filters the allowed Gutenberg block types for a given post.
	 *
	 * @access public
	 * @param  array                   $allowed_block_types  An array of all the allowed block types.
	 * @param  WP_Block_Editor_Context $block_editor_context The current block editor context.
	 * @return array
	 * @since  1.0.0
	 */
	public function filter_allowed_block_types( $allowed_block_types, $block_editor_context ) {
		if ( null !== $block_editor_context->post && 'wpzf-form' == $block_editor_context->post->post_type ) {
			$allowed_block_types = array(
				'wpzoom-forms/form',
				'wpzoom-forms/text-plain-field',
				'wpzoom-forms/text-name-field',
				'wpzoom-forms/text-email-field',
				'wpzoom-forms/text-website-field',
				'wpzoom-forms/text-phone-field',
				'wpzoom-forms/textarea-field',
				'wpzoom-forms/select-field',
				'wpzoom-forms/checkbox-field',
				'wpzoom-forms/radio-field',
				'wpzoom-forms/label-field',
				'wpzoom-forms/submit-field',
				'core/paragraph',
				'core/heading',
				'core/list',
				'core/quote',
				'core/code',
				'core/preformatted',
				'core/pullquote',
				'core/table',
				'core/verse',
				'core/image',
				'core/gallery',
				'core/audio',
				'core/cover',
				'core/file',
				'core/media-text',
				'core/video',
				'core/buttons',
				'core/columns',
				'core/group',
				'core/more',
				'core/nextpage',
				'core/separator',
				'core/spacer'
			);
		}

		return $allowed_block_types;
	}

	/**
	 * Adds needed categories to the Gutenberg block categories, if not already present.
	 *
	 * @access public
	 * @param  array                   $categories           Array containing all registered Gutenberg block categories.
	 * @param  WP_Block_Editor_Context $block_editor_context The current block editor context.
	 * @return array
	 * @since  1.0.0
	 */
	public function filter_block_categories( $categories, $block_editor_context ) {
		if ( null !== $block_editor_context->post && 'wpzf-form' == $block_editor_context->post->post_type ) {
			$category_slugs = wp_list_pluck( $categories, 'slug' );

			if ( ! in_array( 'wpzoom-forms', $category_slugs, true ) ) {
				array_unshift(
					$categories,
					array(
						'slug' => 'wpzoom-forms',
						'title' => __( 'WPZOOM Forms', 'wpzoom-forms' ),
						'icon' => 'wordpress'
					)
				);
			}
		} else {
			$category_slugs = wp_list_pluck( $categories, 'slug' );

			if ( ! in_array( 'wpzoom-blocks', $category_slugs, true ) ) {
				$categories = array_merge(
					$categories,
					array(
						array(
							'slug' => 'wpzoom-blocks',
							'title' => __( 'WPZOOM Blocks', 'wpzoom-forms' ),
							'icon' => 'wordpress'
						)
					)
				);
			}
		}

		return $categories;
	}

	/**
	 * Add some extra admin menu items.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function admin_menu() {
		
		global $submenu;

		$page_title = esc_html__( 'WPZOOM Forms Settings Page', 'wpzoom-forms' );

		add_submenu_page(
			'edit.php?post_type=wpzf-form',
			$page_title,
			esc_html__( 'Settings', 'wpzoom-forms' ),
			'manage_options',
			'wpzf-settings',
			array( $this, 'render_settings_page' )
		);

		$amount = 0;
		foreach ( wp_count_posts( 'wpzf-submission', 'readable' ) as $key => $value ) {
			$amount += intval( $value );
		}

		$submenu['edit.php?post_type=wpzf-form'][11][0] = sprintf(
			'%1$s <span class="awaiting-mod count-%2$s"><span class="pending-count" aria-hidden="true">%2$s</span><span class="comments-in-moderation-text screen-reader-text">%3$s</span></span>',
			esc_html__( 'Submissions', 'wpzoom-forms' ),
			$amount,
			sprintf( _n( '%s Submission', '%s Submissions', $amount, 'wpzoom-forms' ), number_format_i18n( $amount ) )
		);
	}

	/**
	 * Registers needed scripts for use on the admin backend.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function admin_enqueue_scripts() {
		if ( 'wpzf-submission' == get_post_type() ) {
			wp_dequeue_script( 'autosave' );

			wp_enqueue_style(
				'wpzoom-forms-css-backend-submissions',
				trailingslashit( $this->main_dir_url ) . 'submissions/backend/style.css',
				array(),
				$this::VERSION
			);
		}

		$current_page = get_current_screen()->id;

		if ( 'edit-wpzf-form' == $current_page || 'wpzf-form' == $current_page || 'edit-wpzf-submission' == $current_page || 'wpzf-submission' == $current_page || 'wpzf-form_page_wpzf-settings' == $current_page ) {
			wp_enqueue_style(
				'wpzoom-forms-css-backend-main',
				trailingslashit( $this->main_dir_url ) . 'main/backend/style.css',
				array(),
				$this::VERSION
			);
		}
	}

	/**
	 * Registers needed scripts and styles for use on the backend.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function register_backend_assets() {
		if ( 'wpzf-form' == get_post_type() ) {
			wp_register_script(
				'wpzoom-forms-js-backend-main',
				trailingslashit( $this->main_dir_url ) . 'main/backend/script.js',
				array( 'wp-blocks', 'wp-components', 'wp-core-data', 'wp-data', 'wp-element', 'wp-i18n', 'wp-polyfill' ),
				$this::VERSION,
				true
			);

			wp_localize_script(
				'wpzoom-forms-js-backend-main',
				'wpzf_formblock',
				array(
					'admin_url'   => trailingslashit( admin_url() ),
					'admin_email' => '' . get_site_option( 'admin_email', '' )
				)
			);

			wp_register_style(
				'wpzoom-forms-css-backend-main',
				trailingslashit( $this->main_dir_url ) . 'main/backend/style.css',
				array(),
				$this::VERSION
			);
		} else {
			wp_register_script(
				'wpzoom-forms-js-backend-formblock',
				trailingslashit( $this->main_dir_url ) . 'form-block/backend/script.js',
				array( 'wp-blocks', 'wp-components', 'wp-core-data', 'wp-data', 'wp-element', 'wp-i18n', 'wp-polyfill' ),
				$this::VERSION,
				true
			);

			wp_localize_script(
				'wpzoom-forms-js-backend-formblock',
				'wpzf_formblock',
				array(
					'admin_url'   => trailingslashit( admin_url() ),
					'admin_email' => '' . get_site_option( 'admin_email', '' )
				)
			);

			wp_register_style(
				'wpzoom-forms-css-backend-formblock',
				trailingslashit( $this->main_dir_url ) . 'form-block/backend/style.css',
				array(),
				$this::VERSION
			);
		}
	}

	/**
	 * Registers needed scripts and styles for use on the frontend.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function register_frontend_assets() {

		$depends         = array( 'jquery', 'wp-blocks', 'wp-components', 'wp-core-data', 'wp-data', 'wp-element', 'wp-i18n', 'wp-polyfill' );
		$enableRecaptcha = WPZOOM_Forms_Settings::get( 'wpzf_global_captcha_service' );
		$recaptchaType    = ! empty( WPZOOM_Forms_Settings::get( 'wpzf_global_captcha_type' ) ) ? WPZOOM_Forms_Settings::get( 'wpzf_global_captcha_type' ) : 'v2';
		$site_key        = esc_attr( sanitize_text_field( WPZOOM_Forms_Settings::get( 'wpzf_global_captcha_site_key' ) ) );

		if ( 'recaptcha' == $enableRecaptcha ) {

			if( 'v2' == $recaptchaType ) {
				wp_register_script(
					'google-recaptcha',
					'https://www.google.com/recaptcha/api.js',
					array(),
					$this::VERSION,
					true
				);
			}
			elseif( 'v3' == $recaptchaType ) {
				wp_register_script(
					'google-recaptcha',
					"https://www.google.com/recaptcha/api.js?render={$site_key}",
					array(),
					$this::VERSION,
					true
				);
			}

			$depends[] = 'google-recaptcha';
		}
		
		wp_register_script(
			'wpzoom-forms-js-frontend-formblock',
			trailingslashit( $this->dist_dir_url ) . 'assets/frontend/js/script.js',
			$depends,
			$this::VERSION,
			true
		);

		$use_theme_style = boolval( WPZOOM_Forms_Settings::get( 'wpzf_use_theme_styles' ) );

		wp_register_style(
			'wpzoom-forms-css-frontend-formblock',
			( $use_theme_style ? trailingslashit( $this->main_dir_url ) . 'form-block/frontend/style.css' : false ),
			array(),
			$this::VERSION
		);

	}

	/**
	 * Enqueues needed scripts and styles for use on the frontend.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function enqueue_frontend_scripts() {

		$global_load_assets = boolval( WPZOOM_Forms_Settings::get( 'wpzf_global_assets_load' ) );

		if ( $global_load_assets ) {
			wp_enqueue_style( 'wpzoom-forms-css-frontend-formblock' );
			wp_enqueue_script( 'wpzoom-forms-js-frontend-formblock' );
		}
	}

	/**
	 * Changes the row actions in post lists for certain post types.
	 *
	 * @access public
	 * @param  array   $actions An array of all the possible post row actions.
	 * @param  WP_Post $post    The post object for the post that is being displayed.
	 * @return array
	 * @since  1.0.0
	 */
	public function modify_row_actions( $actions, $post ) {
		if ( 'wpzf-form' == $post->post_type || 'wpzf-submission' == $post->post_type ) {
			if ( 'wpzf-submission' == $post->post_type && isset( $actions['edit'] ) ) {
				$actions['edit'] = preg_replace( '/\<a([^>]+)\>(.+)\<\/a\>/i', '<a$1>' . __( 'View', 'wpzoom-forms' ) . '</a>', $actions['edit'] );
			}

			if ( isset( $actions['inline hide-if-no-js'] ) ) {
				unset( $actions['inline hide-if-no-js'] );
			}
		}

		return $actions;
	}

	/**
	 * Removes the Inline Edit option from post types it doesn't make sense for.
	 *
	 * @access public
	 * @param  array  $actions An array of all the possible bulk actions.
	 * @return array
	 * @since  1.0.0
	 */
	public function remove_bulk_actions( $actions ) {
		if ( isset( $actions['edit'] ) ) {
			unset( $actions['edit'] );
		}

		return $actions;
	}

	/**
	 * Changes the columns displayed in the post list for the form custom post type.
	 *
	 * @access public
	 * @param  array  $columns An array of all the columns.
	 * @return array
	 * @since  1.0.0
	 */
	public function post_list_columns_form( $columns ) {
		return array(
			'cb'        => $columns['cb'],
			'title'     => __( 'Title', 'wpzoom-forms' ),
			'shortcode' => __( 'Shortcode', 'wpzoom-forms' ),
			'date'      => $columns['date']
		);
	}

	/**
	 * Changes the columns displayed in the post list for the submissions custom post type.
	 *
	 * @access public
	 * @param  array  $columns An array of all the columns.
	 * @return array
	 * @since  1.0.0
	 */
	public function post_list_columns_submit( $columns ) {
		return array(
			'cb'   => $columns['cb'],
			'desc' => __( 'Submission', 'wpzoom-forms' ),
			'form' => __( 'Form', 'wpzoom-forms' ),
			'date' => $columns['date']
		);
	}

	/**
	 * Changes the sortable columns displayed in the post list for the submissions custom post type.
	 *
	 * @access public
	 * @param  array  $columns An array of all the sortable columns.
	 * @return array
	 * @since  1.0.0
	 */
	public function post_list_sortable_columns_submit( $columns ) {
		$columns['desc'] = 'wpzf_desc';
		$columns['form'] = 'wpzf_form';

		return $columns;
	}

	/**
	 * Changes the column content displayed in the post list for the form custom post type.
	 *
	 * @access public
	 * @param  string $column  The name of the column to display.
	 * @param  int    $post_id The ID of the current post.
	 * @return array
	 * @since  1.0.0
	 */
	public function post_list_custom_columns_form( $column, $post_id ) {
		if ( 'shortcode' == $column ) {
			printf( '<input type="text" value="[wpzf_form id=&quot;%s&quot;]" readonly />', $post_id );
		}
	}

	/**
	 * Changes the column content displayed in the post list for the submissions custom post type.
	 *
	 * @access public
	 * @param  string $column  The name of the column to display.
	 * @param  int    $post_id The ID of the current post.
	 * @return array
	 * @since  1.0.0
	 */
	public function post_list_custom_columns_submit( $column, $post_id ) {
		if ( 'desc' == $column ) {
			$data = get_post_meta( $post_id, '_wpzf_fields', true );
			$title = __( '[Unknown]', 'wpzoom-forms' );

			if ( ! is_null( $data ) && false !== $data && is_array( $data ) && ! empty( $data ) ) {
				$title = '';

				foreach ( $data as $name => $value ) {
					$title .= '<span class="field-name">' . esc_html( substr( $name, 0, 250 ) ) . ( strlen( $name ) > 250 ? '&hellip;' : '' ) . '</span>
					           <span class="field-value">' . esc_html( substr( $value, 0, 250 ) ) . ( strlen( $value ) > 250 ? '&hellip;' : '' ) . '</span>';
				}
			}

			printf( '<a href="%s" class="row-title">%s</a>', esc_url( get_edit_post_link( $post_id ) ), $title );
		} elseif ( 'form' == $column ) {
			$form_id = intval( get_post_meta( $post_id, '_wpzf_form_id', true ) );
			$form_name = __( '[Unknown]', 'wpzoom-forms' );

			if ( $form_id > 0 ) {
				$form_name = $form_id;

				if ( ! is_null( get_post( $form_id ) ) ) {
					$form_name = '<a href="' . esc_url( get_edit_post_link( $form_id ) ) . '">' . get_the_title( $form_id ) . '</a>';
				}
			}

			echo wp_kses( $form_name, array( 'a' => array( 'href' => array() ) ) );
		}
	}

	/**
	 * Sets what the primary column is in the post list for certain post types.
	 *
	 * @access public
	 * @param  string $default The default/primary column.
	 * @param  string $screen  Which screen is currently being rendered.
	 * @return array
	 * @since  1.0.0
	 */
	public function post_list_primary_column( $default, $screen ) {
		if ( 'edit-wpzf-submission' == $screen ) {
			$default = 'desc';
		}

		return $default;
	}

	/**
	 * Handles sorting of custom columns in the post list for certain post types.
	 *
	 * @access public
	 * @param  WP_Query $query The current query.
	 * @return array
	 * @since  1.0.0
	 */
	public function sort_custom_column( $query ) {
		$orderby = $query->get( 'orderby' );

		if ( 'wpzf_desc' == $orderby || 'wpzf_form' == $orderby ) {
			$key = 'wpzf_desc' == $orderby ? '_wpzf_fields' : '_wpzf_form_id';

			$query->set( 'meta_query', array(
				'relation' => 'OR',
				array(
					'key'     => $key,
					'compare' => 'NOT EXISTS'
				),
				array(
					'key'     => $key
				)
			) );

			$query->set( 'orderby', 'meta_value' );
		}
	}

	/**
	 * Changes things on the edit screen of certain post types.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function remove_meta_boxes() {
		global $current_screen;

		if ( 'wpzf-submission' == $current_screen->post_type && 'wpzf-submission' == $current_screen->id ) {
			global $wp_meta_boxes;

			$wp_meta_boxes = array(
				'wpzf-submission' => array(
					'advanced' => array(),
					'side'     => array(),
					'normal'   => array(
						'high' => array(
							'wpzf-submission-mb' => $wp_meta_boxes['wpzf-submission']['normal']['high']['wpzf-submission-mb']
						)
					)
				)
			);

			add_screen_option( 'layout_columns', array( 'max' => 1, 'default' => 1 ) );
		}
	}

	/**
	 * Removes screen options where not needed.
	 *
	 * @access public
	 * @param  bool      $show_screen Whether to show Screen Options tab.
	 * @param  WP_Screen $screen      Current WP_Screen instance.
	 * @return bool
	 * @since  1.0.0
	 */
	public function remove_screen_options( $show_screen, $screen ) {
		return 'wpzf-submission' == $screen->post_type && 'post' == $screen->base ? false : $show_screen;
	}

	/**
	 * Changes the views items for certain post types.
	 *
	 * @access public
	 * @param  array  $views All the possible views.
	 * @return bool
	 * @since  1.0.0
	 */
	public function post_list_views( $views ) {
		if ( isset( $views['publish'] ) ) {
			unset( $views['publish'] );
		}

		$current_page = get_current_screen()->id;

		?>
		<div class="wpzf_wrap wpzf_settings-add-new">
			<?php
			if ( 'edit-wpzf-form' == $current_page ) {
				echo '<a href="' . esc_url( admin_url( 'post-new.php?post_type=wpzf-form' ) ) . '" class="button-primary">' . __( 'Add new form', 'wpzoom-forms' ) . '</a>';
			}
			?>
		</div>
		<?php

		return $views;
	}

	/**
	 * Adds custom meta boxes to certain post types.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'wpzf-submission-mb',
			__( 'Submission', 'wpzoom-forms' ),
			array( $this, 'submission_meta_box' ),
			'wpzf-submission',
			'normal',
			'high'
		);
	}

	/**
	 * Outputs the content for the submission meta box.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function submission_meta_box() {
		$fields = get_post_meta( get_the_ID(), '_wpzf_fields', true );

		if ( ! is_null( $fields ) && false !== $fields && is_array( $fields ) && ! empty( $fields ) ) {
			echo '<ul class="wpzf-submission-view">';

			$form_id = intval( get_post_meta( get_the_ID(), '_wpzf_form_id', true ) );
			$form_name = __( '[Unknown]', 'wpzoom-forms' );

			if ( $form_id > 0 ) {
				$form_name = $form_id;

				if ( ! is_null( get_post( $form_id ) ) ) {
					$form_name = '<a href="' . esc_url( get_edit_post_link( $form_id ) ) . '">' . get_the_title( $form_id ) . '</a>';
				}
			}

			$form_name = wp_kses( $form_name, array( 'a' => array( 'href' => array() ) ) );

			echo '<li class="top"><h3>' . sprintf( __( 'Form: %s', 'wpzoom-forms' ), $form_name ) . '</h3></li>';

			foreach ( $fields as $name => $value ) {
				echo '<li><h3>' . esc_html( $name ) . '</h3><div>' . make_clickable( apply_filters( 'the_content', esc_html( $value ) ) ) . '</div></li>';
			}

			echo '</ul>';

			return;
		}

		printf( '<p class="empty">%s</p>', __( 'Submission is empty&hellip;', 'wpzoom-forms' ) );
	}

	/**
	 * Returns whether the current page is related to the given post type.
	 *
	 * @access public
	 * @param  string $type The type to check for.
	 * @return bool
	 * @since  1.0.0
	 */
	public function is_post_type( $type ) {
		$typenow = '';

		if ( is_admin() && current_user_can( 'edit_posts' ) ) {
			if ( isset( $_REQUEST['post_type'] ) && post_type_exists( sanitize_key( $_REQUEST['post_type'] ) ) ) {
				$typenow = sanitize_key( $_REQUEST['post_type'] );
			} else {
				$post_id = -1;

				if ( isset( $_GET['post'] ) && isset( $_POST['post_ID'] ) && (int) $_GET['post'] !== (int) $_POST['post_ID'] ) {
					// Do nothing
				} elseif ( isset( $_GET['post'] ) ) {
					$post_id = (int) $_GET['post'];
				} elseif ( isset( $_POST['post_ID'] ) ) {
					$post_id = (int) $_POST['post_ID'];
				}

				if ( $post_id > -1 ) {
					$post = get_post( $post_id );

					if ( ! is_null( $post ) && $post instanceof WP_Post ) {
						$typenow = $post->post_type;
					}
				}
			}
		}

		return $type == $typenow;
	}

	/**
	 * Called to render the form block on the frontend.
	 *
	 * @access public
	 * @param  array    $attributes An array containing the attributes for the block.
	 * @param  string   $content    A string containing the content of the block.
	 * @param  WP_Block $block      The WP_Block representation of the block.
	 * @return string
	 * @since  1.0.0
	 */
	public function form_block_render( $attributes, $content = '', $block = null ) {
		global $current_screen;

		$current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( is_admin() || ( ! is_null( $current_screen ) && $current_screen->is_block_editor() ) ) return '';

		$align = isset( $attributes['align'] ) && ! empty( $attributes['align'] ) ? $attributes['align'] : 'none';

		$content = sprintf(
			'<!-- ZOOM Forms Start -->
			<form id="wpzf-%2$s" method="post" action="%1$s" class="wpzoom-forms_form%6$s">
			<input type="hidden" name="action" value="wpzf_submit" />
			<input type="hidden" name="form_id" value="%2$s" />
			%3$s
			%4$s
			%5$s
			</form>
			<!-- ZOOM Forms End -->',
			admin_url( 'admin-post.php' ),
			intval( $attributes['formId'] ),
			wp_nonce_field( 'wpzf_submit', '_wpnonce', true, false ),
			( isset( $_GET['success'] )
				? '<div class="notice ' . ( '1' == $_GET['success'] ? 'success' : 'error' ) . '"><p>' .
				  ( '1' == $_GET['success'] ? __( 'Submitted successfully!', 'wpzoom-forms' ) : __( 'Submission failed!', 'wpzoom-forms' ) ) .
				  '</p></div>'
				: ''
			),
			preg_replace(
				array( '/<!--(.*)-->/Uis', '/<(input|textarea|select)(.*)name="([^"]+)"/Uis' ),
				array( '', '<$1$2name="wpzf_$3"' ),
				get_post_field( 'post_content', intval( $attributes['formId'] ), 'display' )
			),
			( 'none' !== $align ? ' align' . $align : '' )
		);

		preg_match( '/<input(?:.*)name="([^"]+)"(?:.*)data-replyto="true"/is', $content, $match1 );
		preg_match( '/<input(?:.*)name="([^"]+)"(?:.*)data-subject="true"/is', $content, $match2 );

		if ( ! empty( $match1 ) && is_array( $match1 ) && isset( $match1[1] ) ) {
			$content = preg_replace( '/<\/form>/is', '<input type="hidden" name="wpzf_replyto" value="' . $match1[1] . '" /></form>', $content );
		}

		if ( ! empty( $match2 ) && is_array( $match2 ) && isset( $match2[1] ) ) {
			$content = preg_replace( '/<\/form>/is', '<input type="hidden" name="wpzf_subject" value="' . $match2[1] . '" /></form>', $content );
		}

		$enableRecaptcha  = WPZOOM_Forms_Settings::get( 'wpzf_global_captcha_service' );
		$recaptchaType    = ! empty( WPZOOM_Forms_Settings::get( 'wpzf_global_captcha_type' ) ) ? WPZOOM_Forms_Settings::get( 'wpzf_global_captcha_type' ) : 'v2';
		$site_key         = esc_attr( sanitize_text_field( WPZOOM_Forms_Settings::get( 'wpzf_global_captcha_site_key' ) ) );

		if( 'recaptcha' == $enableRecaptcha ) {
			$content = preg_replace( '/<input([^>]*)type="submit"([^>]*)class="([^"]+)"/i', '<input $1 type="submit" data-sitekey="' . $site_key . '" data-callback="wpzf_submit" data-action="submit" $2 class="$3 g-recaptcha"', $content );
		}

		return $content;
	}

	/**
	 * Render the contents of the settings page in the admin.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function render_settings_page() {
		do_action( 'wpzoom_forms_admin_page' );
	}

	/**
	 * Page header used on all admin pages.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function admin_page_header() {

		$current_page = get_current_screen()->id;

		if ( 'edit-wpzf-form' == $current_page || 'edit-wpzf-submission' == $current_page || 'wpzf-submission' == $current_page || 'wpzf-form_page_wpzf-settings' == $current_page ) {
			?>
			<header class="wpzoom-new-admin-wrap wpzoom-new-admin_settings-header">
				<h1 class="wpzoom-new-admin_settings-main-title wp-heading">
					<?php
					echo apply_filters(
						'wpzf_admin-header-title',
						sprintf(
							__( 'WPZOOM Forms <small>by <a href="%s" target="_blank" title="WPZOOM - WordPress themes with modern features and professional support">WPZOOM</a></small>', 'wpzoom-forms' ),
							esc_url( 'https://www.wpzoom.com/' )
						)
					);
					?>

					<span class="wpzoom-new-admin_settings-main-title-version">
						<?php printf( esc_html__( 'V. %s', 'wpzoom-forms' ), self::VERSION ); ?>
					</span>
				</h1>

				<nav class="wpzoom-new-admin_settings-main-nav">
					<ul>
						<?php
						$pages = apply_filters(
							'wpzoom_forms_settings_menu_items',
							array(
								'edit-wpzf-form' => array(
									'name' => __( 'Forms', 'wpzoom-forms' ),
									'url'  => admin_url( 'edit.php?post_type=wpzf-form' ),
								),
								'edit-wpzf-submission' => array(
									'name'  => __( 'Submissions', 'wpzoom-forms' ),
									'url'   => admin_url( 'edit.php?post_type=wpzf-submission' ),
									'altid' => 'wpzf-submission'
								),
								'wpzf-form_page_wpzf-settings' => array(
									'name' => esc_html__( 'Settings', 'wpzoom-forms' ),
									'url'  => admin_url( 'edit.php?post_type=wpzf-form&page=wpzf-settings' ),
								),
							)
						);

						foreach ( $pages as $id => $atts ) {
							printf(
								// translators: %1$s = possible class attribute, %2$s = page url, %3$s = page name.
								_x( '<li%1$s><a href="%2$s">%3$s</a></li>', 'Main menu page item', 'wpzoom-forms' ),
								( $current_page === $id || ( isset( $atts['altid'] ) && $current_page == $atts['altid'] ) ? ' class="active"' : '' ),
								esc_url( $atts['url'] ),
								esc_html( $atts['name'] )
							);
						}
						?>
					</ul>
				</nav>
			</header>
			<?php
		}

		if ( 'edit-wpzf-form' == $current_page || 'wpzf-form' == $current_page ) {
			$forms_count = wp_count_posts( 'wpzf-form' );

			if ( $forms_count->publish < 1 && $forms_count->draft < 1 && $forms_count->trash < 1 ) {
				?>
				<div class="wpzf_no-forms">
					<div class="left-column">
						<h3>
							<?php esc_html_e( 'Hi there!', 'wpzoom-forms' ); ?>
						</h3>

						<h2>
							<?php esc_html_e( 'It appears that you haven&rsquo;t made any forms yet.', 'wpzoom-forms' ); ?>
						</h2>

						<p>
							<?php esc_html_e( 'With WPZOOM Forms, you have the ability to create contact forms, surveys, and various other types of forms effortlessly and swiftly.', 'wpzoom-forms' ); ?>
						</p>

						<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=wpzf-form' ) ); ?>" class="button-primary wpzf-add-form-btn">
							<?php esc_html_e( 'Add new form', 'wpzoom-forms' ); ?>
						</a>
					</div>

					<div class="right-column">
						<svg width="360" height="360" fill="none" xmlns="http://www.w3.org/2000/svg">
							<mask id="a" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="25" y="140" width="304" height="175">
								<path d="M305.985 314.458H47.535a24.293 24.293 0 0 1-3.753-.29c-3.966-.621-7.575-2.21-10.528-4.493a21.616 21.616 0 0 1-2.11-1.862.535.535 0 0 0 .502-.168l94.603-106.18 50.511 30.403 50.49-30.425 94.623 106.202a.531.531 0 0 0 .504.168 21.988 21.988 0 0 1-4.388 3.406 22.998 22.998 0 0 1-6.489 2.603 24 24 0 0 1-5.515.636zm16.809-7.078a.517.517 0 0 0-.126-.438l-37.526-42.119c.465.15.961.263 1.488.328 4.522.558 10.117-.941 13.717 7.925 1.072 2.64 2.755 3.646 4.524 3.646 4.168 0 8.8-5.601 6.971-8.59-2.605-4.255 2.223-7.364 4.674-12.255 1.591-3.176-.007-5.899-3.066-7.309-2.354-1.081-6.664-.35-7.987-3.181-.961-2.057-.308-4.881-.825-7.094-.735-3.151-2.695-4.059-4.592-4.059-1.46 0-2.883.538-3.683 1.003-1.056.616-2.896 1.835-4.431 1.835-1.137 0-2.108-.671-2.466-2.751-.628-3.647-4.487-5.975-8.17-5.975-1.26 0-2.499.273-3.581.859-.469.23-.835.496-1.121.781-2.489 2.275 0 5.251 0 5.251-4.981 4.511.69 11.772 2.376 13.454 1.684 1.682 3.294 4.052 2.067 8.025-.341 1.111-.408 2.215-.23 3.241l-52.631-59.072 24.464-14.743 75.566-45.435V294.001c0 4.5-1.578 8.66-4.253 12.039-.366.461-.753.909-1.159 1.34zm-292.07 0c-.024-.025-.048-.053-.073-.078-3.327-3.577-5.337-8.222-5.337-13.301V140.707l75.566 45.486 24.443 14.714-94.471 106.035a.522.522 0 0 0-.128.438z" fill="#fff" />
							</mask>
							<g mask="url(#a)">
								<path d="M305.983 314.458H47.534a24.564 24.564 0 0 1-3.752-.29c-3.966-.621-7.575-2.21-10.528-4.493a21.583 21.583 0 0 1-2.111-1.862.537.537 0 0 0 .503-.168l94.603-106.18 50.509 30.404 50.491-30.426 94.622 106.202a.538.538 0 0 0 .504.168 21.982 21.982 0 0 1-4.387 3.406 23.044 23.044 0 0 1-6.49 2.604c-1.762.415-3.61.635-5.515.635zm16.809-7.078a.513.513 0 0 0-.125-.438l-37.525-42.118c.465.15.961.262 1.488.327 4.52.559 10.117-.941 13.717 7.925 1.071 2.641 2.754 3.647 4.523 3.647 4.169 0 8.799-5.602 6.971-8.591-2.604-4.255 2.222-7.364 4.674-12.255 1.59-3.176-.009-5.899-3.067-7.308-2.352-1.082-6.663-.351-7.986-3.181-.961-2.058-.309-4.882-.825-7.094-.734-3.152-2.695-4.06-4.591-4.06-1.462 0-2.885.538-3.684 1.004-1.057.615-2.896 1.834-4.43 1.834-1.136 0-2.108-.67-2.468-2.751-.626-3.646-4.486-5.974-8.168-5.974-1.261 0-2.499.273-3.582.858a4.227 4.227 0 0 0-1.122.781c-2.488 2.276 0 5.252 0 5.252-4.98 4.51.691 11.771 2.378 13.453 1.683 1.683 3.293 4.053 2.066 8.025-.341 1.111-.409 2.215-.23 3.241l-52.631-59.071 24.464-14.743 75.566-45.436V294.001c0 4.501-1.579 8.661-4.254 12.04-.365.46-.751.908-1.159 1.339zm-292.067 0-.074-.078c-3.327-3.577-5.337-8.222-5.337-13.301V140.708l75.565 45.485 24.444 14.715-94.471 106.034a.522.522 0 0 0-.127.438z" fill="#083EA7" />
							</g>
							<mask id="b" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="25" y="52" width="304" height="180">
								<path d="m176.76 231.868-50.511-30.403.038-.04a.533.533 0 0 0-.399-.883.53.53 0 0 0-.396.178l-.169.187-24.443-14.714-75.565-45.486-.315-.19 17.42-10.512 58.46-35.278 65.693-39.64c3.197-1.93 6.689-2.894 10.184-2.894 3.496 0 6.993.966 10.19 2.894L252.64 94.74l60.632 36.579 15.248 9.198-.314.19-75.566 45.435-24.464 14.743-.148-.165a.53.53 0 0 0-.75-.044.533.533 0 0 0-.045.749l.017.018-50.49 30.425z" fill="#fff" />
							</mask>
							<g mask="url(#b)">
								<path d="m176.76 231.868-50.511-30.404.038-.039a.533.533 0 0 0-.399-.883.53.53 0 0 0-.396.178l-.169.187-24.443-14.714-75.565-45.486-.315-.19 17.42-10.512 58.46-35.277 65.693-39.643c3.196-1.929 6.69-2.89 10.184-2.89 3.497 0 6.993.964 10.189 2.89l65.695 39.655 60.63 36.579 15.249 9.198-.315.189-75.564 45.436-24.466 14.743-.147-.165a.529.529 0 0 0-.749-.044.533.533 0 0 0-.045.749l.017.018-50.491 30.425z" fill="#242628" />
							</g>
							<mask id="c" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="30" y="200" width="97" height="108">
								<path d="M31.25 307.823a.536.536 0 0 1-.107-.01 26.971 26.971 0 0 1-.418-.433.522.522 0 0 1 .127-.438l94.472-106.035.926.558-94.604 106.18a.53.53 0 0 1-.397.178z" fill="#fff" />
							</mask>
							<g mask="url(#c)">
								<path d="M31.25 307.823a.536.536 0 0 1-.107-.01 26.971 26.971 0 0 1-.418-.433.522.522 0 0 1 .127-.438l94.472-106.035.926.558-94.604 106.18a.53.53 0 0 1-.397.178z" fill="#fff" />
							</g>
							<mask id="d" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="227" y="200" width="96" height="108">
								<path d="M322.272 307.823a.531.531 0 0 1-.399-.178L227.25 201.443l.926-.558 52.631 59.072c.386 2.223 1.927 4.085 4.335 4.866l37.526 42.119a.517.517 0 0 1 .126.438c-.136.147-.276.29-.417.433a.533.533 0 0 1-.105.01z" fill="#fff" />
							</mask>
							<g mask="url(#d)">
								<path d="M322.27 307.822a.532.532 0 0 1-.397-.177l-94.622-106.202.924-.558 52.63 59.072c.387 2.223 1.927 4.085 4.336 4.866l37.527 42.118a.523.523 0 0 1 .126.439c-.137.147-.277.29-.417.433a.533.533 0 0 1-.107.009z" fill="#fff" />
							</g>
							<mask id="e" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="274" y="228" width="44" height="49">
								<path d="M304.871 276.722c-1.769 0-3.452-1.006-4.524-3.646-3.6-8.866-9.196-7.367-13.717-7.925a8.029 8.029 0 0 1-1.488-.328c-2.409-.781-3.949-2.643-4.336-4.866-.178-1.026-.11-2.13.231-3.241 1.227-3.973-.384-6.343-2.067-8.025-1.686-1.682-7.357-8.943-2.376-13.454 0 0-2.489-2.976 0-5.251-1.877 1.87-.259 4.62-.259 4.62l1.38-.961c5.861.078 3.5 6.673 3.5 6.673l.369.188s.638-.686 1.207-.686c.236 0 .462.118.625.451.552 1.136-.808 3.259-.808 3.259s.497 1.239 1.636 1.239c.502 0 1.129-.24 1.892-.936 1.882-1.707 2.845-3.868 2.567-6.854.072.398.24.894.619 1.269.364.366.861.549 1.471.549.055 0 .113-.003.17-.005.901-.048 2.186-.446 3.548-.869 1.769-.548 3.691-1.144 5.219-1.144.793 0 1.48.163 1.982.576.743.608 1.014 1.699.836 3.341l.205.02c.189-1.712-.11-2.866-.908-3.521-.544-.446-1.277-.619-2.115-.619-1.563 0-3.5.599-5.282 1.152-1.347.418-2.622.811-3.497.858a2.709 2.709 0 0 1-.156.005c-.554 0-1.001-.162-1.327-.485-.665-.664-.617-1.79-.615-1.8l-.208-.01c0 .01-.003.088.002.205a16.274 16.274 0 0 0-.634-2.743c-1.102-3.484-4.236-5.246-7.219-5.246-1.081 0-2.14.23-3.079.693 1.082-.586 2.321-.859 3.581-.859 3.683 0 7.542 2.328 8.169 5.975.359 2.08 1.33 2.751 2.467 2.751 1.535 0 3.375-1.219 4.431-1.835.8-.465 2.223-1.003 3.683-1.003 1.897 0 3.857.908 4.592 4.059.517 2.213-.136 5.037.825 7.094 1.323 2.831 5.633 2.1 7.987 3.181 3.058 1.41 4.657 4.133 3.066 7.309-2.452 4.891-7.279 8-4.675 12.255 1.83 2.989-2.802 8.59-6.97 8.59zm3.297-27.44-.028.205c.048.005 4.725.673 6.133 2.966.451.736.519 1.569.203 2.478-.66 1.902-1.894 3.487-2.983 4.886-1.516 1.942-2.823 3.619-2.339 5.604l.201-.05c-.459-1.887.818-3.529 2.298-5.429 1.102-1.409 2.346-3.008 3.019-4.943.338-.969.263-1.863-.224-2.654-1.46-2.375-6.084-3.036-6.28-3.063z" fill="#fff" />
							</mask>
							<g mask="url(#e)">
								<path d="M304.871 276.723c-1.77 0-3.455-1.006-4.526-3.647-3.601-8.866-9.194-7.367-13.717-7.925a8.002 8.002 0 0 1-1.488-.328c-2.409-.781-3.947-2.643-4.335-4.865-.179-1.027-.11-2.13.23-3.242 1.228-3.972-.382-6.343-2.066-8.024-1.688-1.683-7.358-8.944-2.376-13.454 0 0-2.49-2.976 0-5.252-1.879 1.87-.26 4.621-.26 4.621l1.38-.961c5.861.077 3.502 6.673 3.502 6.673l.368.188s.636-.686 1.206-.686c.238 0 .462.118.625.45.553 1.137-.808 3.259-.808 3.259s.498 1.239 1.638 1.239c.5 0 1.128-.24 1.889-.936 1.884-1.707 2.846-3.867 2.567-6.853.075.398.241.893.62 1.269.365.365.863.548 1.471.548.056 0 .114-.003.172-.005.899-.048 2.185-.445 3.546-.869 1.77-.548 3.693-1.144 5.22-1.144.793 0 1.479.163 1.983.576.741.609 1.012 1.7.835 3.342l.205.02c.188-1.712-.111-2.866-.907-3.522-.545-.446-1.278-.618-2.116-.618-1.563 0-3.5.598-5.281 1.151-1.35.418-2.622.811-3.499.859-.053.002-.102.005-.155.005-.556 0-1.001-.163-1.328-.486-.664-.663-.617-1.79-.614-1.8l-.207-.009c0 .009-.003.087 0 .205-.111-.849-.324-1.76-.634-2.744-1.101-3.484-4.235-5.246-7.219-5.246a6.967 6.967 0 0 0-3.079.694c1.081-.586 2.321-.859 3.582-.859 3.682 0 7.541 2.328 8.168 5.975.36 2.08 1.331 2.75 2.468 2.75 1.535 0 3.375-1.219 4.431-1.834.8-.466 2.221-1.004 3.682-1.004 1.898 0 3.856.909 4.592 4.06.517 2.213-.136 5.036.827 7.093 1.322 2.831 5.632 2.101 7.986 3.182 3.059 1.409 4.655 4.132 3.065 7.308-2.451 4.892-7.278 8-4.675 12.256 1.831 2.988-2.802 8.59-6.968 8.59zm3.294-27.441-.027.205c.049.005 4.724.674 6.132 2.967.454.735.52 1.569.205 2.478-.661 1.902-1.895 3.486-2.985 4.885-1.516 1.943-2.821 3.62-2.337 5.605l.199-.05c-.459-1.888.819-3.53 2.299-5.43 1.103-1.409 2.345-3.008 3.02-4.943.338-.969.263-1.862-.224-2.653-1.46-2.375-6.085-3.036-6.282-3.064z" fill="#242628" />
							</g>
							<mask id="f" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="261" y="282" width="18" height="42">
								<path d="M270.028 282.812s-3.402.526-7.229 3.079a1.904 1.904 0 0 0-.843 1.82c.459 3.619 2.344 15.338 8.556 26.785.326.603.422 1.306.276 1.977l-1.079 5.011-2.002 1.872h5.156l3.433-9.266-1.468-.463a2.772 2.772 0 0 1-1.94-2.596l-.173-9.939c.148-4.43-2.722-7.487-2.722-7.487l8.235-.398-8.2-10.395z" fill="#fff" />
							</mask>
							<g mask="url(#f)">
								<path d="M270.028 282.812s-3.402.526-7.229 3.079a1.898 1.898 0 0 0-.844 1.82c.46 3.619 2.343 15.338 8.556 26.785.326.603.422 1.306.275 1.977l-1.077 5.011-2.004 1.872h5.156l3.435-9.266-1.469-.463a2.774 2.774 0 0 1-1.941-2.596l-.171-9.939c.146-4.43-2.723-7.486-2.723-7.486l8.235-.399-8.199-10.395z" fill="url(#g)" />
							</g>
							<mask id="h" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="265" y="313" width="13" height="13">
								<path d="M265.694 324.26c-.469.613 5.053 1.422 7.844.083 0 0 3.869-6.716 3.869-10.173 0 0-.309-.703-2.58-.543 0 0-1.3 7.947-2.669 8.838-1.373.891-3.837.09-3.837.09s-1.934.801-2.627 1.705z" fill="#fff" />
							</mask>
							<g mask="url(#h)">
								<path d="M265.693 324.26c-.469.614 5.052 1.422 7.842.083 0 0 3.869-6.716 3.869-10.172 0 0-.307-.704-2.58-.543 0 0-1.299 7.946-2.669 8.837-1.372.892-3.836.091-3.836.091s-1.935.801-2.626 1.704z" fill="#242628" />
							</g>
							<mask id="i" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="242" y="284" width="36" height="41">
								<path d="M277.186 293.753c-.045-.078-8.864-1.059-8.864-1.059s.288 2.676-.615 6.358c-.901 3.684-13.853 19.641-13.853 19.641l1.358 4.295c-4.198 1.46-8.328 1.692-12.393.744l8.416-5.039s7.876-26.775 12.701-31.553l8.842-3.051 4.408 9.664z" fill="#fff" />
							</mask>
							<g mask="url(#i)">
								<path d="M277.185 293.754c-.046-.078-8.864-1.059-8.864-1.059s.287 2.675-.615 6.357c-.9 3.685-13.853 19.641-13.853 19.641l1.356 4.295c-4.197 1.46-8.327 1.693-12.391.744l8.415-5.039s7.876-26.774 12.701-31.553l8.842-3.051 4.409 9.665z" fill="url(#j)" />
							</g>
							<mask id="k" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="264" y="278" width="39" height="17">
								<path d="M296.968 278.172s5.083 7.541 5.5 10.98c.416 3.44-1.393 4.839-6.725 5.069-5.334.23-19.568.225-19.568.225l-12.152-10.357s8.855-3.151 11.158-4.043c2.301-.891 21.787-1.874 21.787-1.874z" fill="#fff" />
							</mask>
							<g mask="url(#k)">
								<path d="M296.969 278.172s5.082 7.541 5.5 10.98c.415 3.439-1.392 4.839-6.724 5.069-5.334.23-19.571.225-19.571.225l-12.149-10.357s8.854-3.152 11.156-4.043c2.302-.891 21.788-1.874 21.788-1.874z" fill="#001C37" />
							</g>
							<mask id="l" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="240" y="321" width="16" height="5">
								<path d="M254.7 321.542s1.46 1.411.474 2.983c0 0-11.582 2.048-14.726 1.119 0 0 .653-1.487 4.193-3.179l-.261.201c-.331.252-.136.781.278.758 2.334-.133 7.164-.561 10.042-1.882z" fill="#fff" />
							</mask>
							<g mask="url(#l)">
								<path d="M254.7 321.542s1.46 1.411.474 2.983c0 0-11.582 2.048-14.726 1.119 0 0 .653-1.487 4.193-3.179l-.261.201c-.331.252-.136.781.278.758 2.334-.133 7.164-.561 10.042-1.882z" fill="#242628" />
							</g>
							<mask id="m" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="272" y="249" width="31" height="29">
								<path d="M283.223 277.173s-5.916-5.622-8.829-9.344a7.887 7.887 0 0 1-1.343-7.111c1.33-4.47 3.694-9.098 3.91-9.819.331-1.107 6.967-1.059 6.967-1.059s13.058 8.455 18.189 22.775l-5.764 1.79s-3.409-5.707-6.777-9.334c0 0 5.829 7.682 6.035 10.825l-12.388 1.277z" fill="#fff" />
							</mask>
							<g mask="url(#m)">
								<path d="M283.222 277.173s-5.915-5.622-8.829-9.344a7.89 7.89 0 0 1-1.343-7.111c1.332-4.47 3.696-9.098 3.91-9.819.331-1.106 6.968-1.059 6.968-1.059s13.057 8.455 18.188 22.775l-5.762 1.79s-3.412-5.707-6.779-9.334c0 0 5.83 7.682 6.035 10.825l-12.388 1.277z" fill="#1FDE91" />
							</g>
							<mask id="n" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="276" y="243" width="8" height="8">
								<path d="M276.157 244.726c.07.228 1.802 5.957 1.937 6.075.136.12 3.799.315 5.523-.858l-2.926-6.11-4.534.893z" fill="#fff" />
							</mask>
							<g mask="url(#n)">
								<path d="M276.158 244.726c.069.228 1.8 5.957 1.936 6.075.136.12 3.8.315 5.523-.859l-2.927-6.11-4.532.894z" fill="url(#o)" />
							</g>
							<mask id="p" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="272" y="232" width="13" height="14">
								<path d="M279.003 232.694s-3.127 1.317-5.252 5.825c-2.128 4.508-.539 6.588 1.455 6.873 1.995.288 4.976.243 8.132-2.668 3.154-2.911-.271-11.592-4.335-10.03z" fill="#fff" />
							</mask>
							<g mask="url(#p)">
								<path d="M279.003 232.694s-3.128 1.317-5.253 5.825c-2.128 4.508-.539 6.588 1.455 6.873 1.997.288 4.976.243 8.133-2.668 3.154-2.911-.272-11.591-4.335-10.03z" fill="url(#q)" />
							</g>
							<mask id="r" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="275" y="228" width="14" height="17">
								<path d="M284.245 244.769c-1.14 0-1.636-1.239-1.636-1.239s1.36-2.123.808-3.259c-.164-.333-.389-.451-.625-.451-.57 0-1.207.686-1.207.686l-.369-.188s2.361-6.595-3.5-6.673l-1.38.961s-1.618-2.75.258-4.62a4.172 4.172 0 0 1 1.122-.781 6.96 6.96 0 0 1 3.079-.693c2.983 0 6.117 1.762 7.218 5.246.311.984.522 1.895.635 2.743.005.123.02.291.055.478.279 2.986-.685 5.147-2.567 6.854-.762.695-1.39.936-1.891.936z" fill="#fff" />
							</mask>
							<g mask="url(#r)">
								<path d="M284.246 244.768c-1.14 0-1.635-1.239-1.635-1.239s1.358-2.122.806-3.259c-.163-.332-.389-.45-.625-.45-.57 0-1.205.686-1.205.686l-.371-.188s2.362-6.596-3.499-6.673l-1.379.961s-1.62-2.751.257-4.621c.287-.285.653-.55 1.122-.781a6.956 6.956 0 0 1 3.079-.693c2.984 0 6.118 1.762 7.219 5.247.311.983.521 1.894.635 2.743.003.122.019.29.055.478.277 2.986-.684 5.146-2.566 6.853-.765.696-1.392.936-1.893.936z" fill="#242628" />
							</g>
							<mask id="s" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="280" y="239" width="4" height="5">
								<path d="M280.594 241.37s1.192-3.397 2.782-2.045c1.591 1.354-1.292 3.138-2.421 3.717l-.361-1.672z" fill="#fff" />
							</mask>
							<g mask="url(#s)">
								<path d="M280.593 241.37s1.191-3.397 2.782-2.045c1.591 1.354-1.294 3.139-2.421 3.717l-.361-1.672z" fill="url(#t)" />
							</g>
							<mask id="u" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="271" y="272" width="33" height="8">
								<path d="m271.167 277.591 4.348-4.18 6.015 2.536s5.989.923 9.414 0l3.427-.927 7.746-2.405 1.214 3.429c.309.871 0 1.837-.755 2.371-.67.475-1.455.998-1.957 1.216-1.019.446-25.139-.308-25.139-.308l-4.313-1.732z" fill="#fff" />
							</mask>
							<g mask="url(#u)">
								<path d="m271.167 277.591 4.348-4.18 6.016 2.535s5.987.924 9.412 0l3.428-.926 7.747-2.405 1.215 3.429c.308.871 0 1.837-.757 2.37-.67.476-1.456.999-1.958 1.217-1.016.445-25.138-.308-25.138-.308l-4.313-1.732z" fill="url(#v)" />
							</g>
							<mask id="w" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="288" y="236" width="15" height="5">
								<path d="m302.754 240.716-.206-.02c.178-1.642-.093-2.733-.835-3.341-.502-.413-1.19-.576-1.983-.576-1.528 0-3.45.596-5.219 1.144-1.362.423-2.647.821-3.547.869-.058.002-.116.005-.171.005-.61 0-1.107-.183-1.47-.549-.379-.375-.547-.871-.62-1.269-.015-.157-.035-.315-.055-.478-.005-.117-.003-.195-.003-.205l.208.01c-.002.01-.05 1.136.615 1.8.326.323.773.485 1.327.485.053 0 .103-.002.156-.005.876-.047 2.15-.44 3.498-.858 1.781-.553 3.718-1.152 5.281-1.152.838 0 1.571.173 2.116.619.797.655 1.096 1.809.908 3.521z" fill="#fff" />
							</mask>
							<g mask="url(#w)">
								<path d="m302.753 240.717-.206-.02c.177-1.642-.094-2.734-.836-3.342-.503-.413-1.189-.575-1.981-.575-1.53 0-3.45.595-5.22 1.143-1.364.424-2.647.821-3.549.869-.056.002-.115.005-.169.005-.61 0-1.106-.183-1.47-.548-.38-.376-.549-.871-.622-1.269-.013-.158-.034-.316-.053-.478-.005-.118-.003-.196-.003-.206l.209.01c-.003.01-.051 1.137.613 1.8.327.323.774.486 1.329.486.051 0 .102-.003.155-.005.876-.048 2.151-.441 3.498-.859 1.781-.553 3.717-1.151 5.282-1.151.838 0 1.569.172 2.113.618.798.656 1.098 1.81.91 3.522z" fill="#424242" />
							</g>
							<mask id="x" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="288" y="236" width="1" height="1">
								<path d="M288.703 236.979a3.421 3.421 0 0 1-.056-.478c.021.163.041.321.056.478z" fill="#fff" />
							</mask>
							<g mask="url(#x)">
								<path d="M288.701 236.98a3.415 3.415 0 0 1-.055-.478c.019.163.039.32.055.478z" fill="url(#y)" />
							</g>
							<mask id="z" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="308" y="249" width="7" height="17">
								<path d="M309.154 265.421c-.484-1.985.823-3.662 2.339-5.604 1.089-1.399 2.323-2.984 2.983-4.886.316-.909.248-1.742-.203-2.478-1.408-2.293-6.085-2.961-6.132-2.966l.027-.205c.196.027 4.82.688 6.28 3.063.487.791.563 1.685.224 2.654-.673 1.934-1.917 3.534-3.019 4.943-1.48 1.9-2.757 3.542-2.298 5.429l-.201.05z" fill="#fff" />
							</mask>
							<g mask="url(#z)">
								<path d="M309.153 265.422c-.484-1.985.823-3.662 2.337-5.605 1.09-1.399 2.326-2.983 2.984-4.885.316-.909.25-1.743-.201-2.479-1.409-2.292-6.086-2.961-6.132-2.966l.027-.205c.196.028 4.819.688 6.278 3.064.487.791.564 1.684.226 2.653-.674 1.935-1.919 3.534-3.019 4.943-1.481 1.9-2.758 3.542-2.299 5.43l-.201.05z" fill="#424242" />
							</g>
							<mask id="A" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="285" y="299" width="4" height="25">
								<path d="M288.083 324h-2.291v-24.71h2.291V324z" fill="#fff" />
							</mask>
							<g mask="url(#A)">
								<path d="M288.083 324h-2.29v-24.71h2.29V324z" fill="#242628" />
							</g>
							<mask id="B" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="276" y="322" width="21" height="3">
								<path d="M296.967 324.475h-20.06a1.49 1.49 0 0 1 1.491-1.487h17.079c.823 0 1.49.666 1.49 1.487z" fill="#fff" />
							</mask>
							<g mask="url(#B)">
								<path d="M296.966 324.475h-20.061a1.49 1.49 0 0 1 1.491-1.486h17.08c.822 0 1.49.665 1.49 1.486z" fill="#242628" />
							</g>
							<mask id="C" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="274" y="293" width="26" height="8">
								<path d="M299.947 298.431a2.468 2.468 0 0 1-2.464 2.458h-20.296a2.468 2.468 0 0 1-2.464-2.458v-2.222a2.467 2.467 0 0 1 2.464-2.456h20.296a2.467 2.467 0 0 1 2.464 2.456v2.222z" fill="#fff" />
							</mask>
							<g mask="url(#C)">
								<path d="M299.949 298.431a2.47 2.47 0 0 1-2.464 2.458h-20.296a2.467 2.467 0 0 1-2.463-2.458v-2.223a2.464 2.464 0 0 1 2.463-2.455h20.296a2.466 2.466 0 0 1 2.464 2.455v2.223z" fill="#242628" />
							</g>
							<mask id="D" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="261" y="277" width="28" height="3">
								<path d="M287.621 279.563h-.058l-25.224-.027a1.127 1.127 0 1 1 .003-2.253l25.224.028c.625 0 1.129.505 1.129 1.129a1.132 1.132 0 0 1-1.074 1.123z" fill="#fff" />
							</mask>
							<g mask="url(#D)">
								<path d="M287.624 279.563h-.06l-25.222-.028a1.126 1.126 0 0 1-1.127-1.126c0-.623.508-1.136 1.127-1.126l25.225.027c.624 0 1.13.506 1.13 1.129a1.132 1.132 0 0 1-1.073 1.124z" fill="#242628" />
							</g>
							<mask id="E" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="237" y="256" width="38" height="24">
								<path d="M239.665 256.716h21.006c1.551 0 2.961.893 3.623 2.29l9.71 20.542H247.9a2.431 2.431 0 0 1-2.263-1.534l-7.53-19.008a1.674 1.674 0 0 1 1.558-2.29z" fill="#fff" />
							</mask>
							<g mask="url(#E)">
								<path d="M239.665 256.716h21.006c1.551 0 2.961.893 3.623 2.29l9.71 20.542H247.9a2.431 2.431 0 0 1-2.263-1.534l-7.53-19.008a1.674 1.674 0 0 1 1.558-2.29z" fill="#242628" />
							</g>
							<mask id="F" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="215" y="279" width="108" height="6">
								<path d="M322.877 280.482a.938.938 0 0 0-.221-.806 1.07 1.07 0 0 0-.803-.353H216.599c-.409 0-.79.195-1.011.518a1.062 1.062 0 0 0-.078 1.071l1.142 2.311c.261.528.828.866 1.45.866h103.031c.579 0 1.076-.383 1.187-.916l.557-2.691z" fill="#fff" />
							</mask>
							<g mask="url(#F)">
								<path d="M322.876 280.482a.94.94 0 0 0-.222-.806 1.077 1.077 0 0 0-.804-.353H216.596c-.409 0-.79.196-1.008.519-.219.32-.25.726-.079 1.071l1.14 2.31c.261.528.83.866 1.452.866H321.13c.58 0 1.076-.383 1.188-.916l.558-2.691z" fill="#1FDE91" />
							</g>
							<mask id="G" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="221" y="281" width="22" height="49">
								<path d="M222.498 329.136a.883.883 0 0 1-.364-.078.95.95 0 0 1-.484-1.239l19.373-45.963a.915.915 0 0 1 1.212-.495c.469.207.685.76.484 1.239l-19.373 45.965a.92.92 0 0 1-.848.571z" fill="#fff" />
							</mask>
							<g mask="url(#G)">
								<path d="M222.498 329.136a.883.883 0 0 1-.364-.078.95.95 0 0 1-.484-1.239l19.373-45.963a.915.915 0 0 1 1.212-.495c.469.207.685.76.484 1.239l-19.373 45.965a.92.92 0 0 1-.848.571z" fill="#1FDE91" />
							</g>
							<mask id="H" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="224" y="281" width="22" height="49">
								<path d="M244.322 329.136a.922.922 0 0 1-.85-.571l-19.37-45.965a.95.95 0 0 1 .481-1.239.918.918 0 0 1 1.215.495l19.372 45.963a.95.95 0 0 1-.484 1.239.89.89 0 0 1-.364.078z" fill="#fff" />
							</mask>
							<g mask="url(#H)">
								<path d="M244.322 329.136a.922.922 0 0 1-.85-.571l-19.37-45.965a.95.95 0 0 1 .481-1.239.918.918 0 0 1 1.215.495l19.372 45.963a.95.95 0 0 1-.484 1.239.89.89 0 0 1-.364.078z" fill="#1FDE91" />
							</g>
							<mask id="I" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="297" y="281" width="23" height="49">
								<path d="M298.785 329.136a.907.907 0 0 1-.367-.078.95.95 0 0 1-.482-1.239l19.371-45.963a.918.918 0 0 1 1.214-.495c.469.207.685.76.484 1.239l-19.372 45.965a.924.924 0 0 1-.848.571z" fill="#fff" />
							</mask>
							<g mask="url(#I)">
								<path d="M298.785 329.136a.914.914 0 0 1-.367-.078.95.95 0 0 1-.481-1.239l19.37-45.963a.918.918 0 0 1 1.214-.495c.469.207.686.76.486 1.238l-19.375 45.966a.922.922 0 0 1-.847.571z" fill="#1FDE91" />
							</g>
							<mask id="J" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="300" y="281" width="22" height="49">
								<path d="M320.606 329.136a.924.924 0 0 1-.848-.571L300.386 282.6a.951.951 0 0 1 .484-1.239.917.917 0 0 1 1.214.495l19.37 45.963a.949.949 0 0 1-.481 1.239.907.907 0 0 1-.367.078z" fill="#fff" />
							</mask>
							<g mask="url(#J)">
								<path d="M320.607 329.136a.923.923 0 0 1-.849-.571L300.385 282.6a.951.951 0 0 1 .483-1.239.918.918 0 0 1 1.215.495l19.37 45.963a.948.948 0 0 1-.48 1.239.9.9 0 0 1-.366.078z" fill="#1FDE91" />
							</g>
							<mask id="K" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="37" y="31" width="272" height="201">
								<path d="m37.887 148.035 139.064 83.741 131.13-78.792V31H48.806l-10.92 117.035z" fill="#fff" />
							</mask>
							<g mask="url(#K)">
								<path d="M270.976 66.156H58.964c-3.739 0-6.835 3.235-6.835 6.966v171.639h249.263V96.978l-30.416-30.822z" fill="#fff" />
								<path d="m301.274 96.979-27.82.24c-.742.007-1.316-.283-1.841-.8-.524-.519-.577-1.224-.577-1.959V66.37l30.238 30.609z" fill="#81909C" />
							</g>
							<path d="M335.988 197.782v21.17c0 1.725-1.055 3.124-2.355 3.124h-77.309c-1.078 0-2.02.976-2.282 2.365l-1.389 7.372c-.182.976-1.208 1.059-1.479.115l-1.786-6.225c-.349-1.211-1.225-2.013-2.199-2.013h-3.323c-1.3 0-2.354-1.399-2.354-3.123v-21.762c0-1.715 1.043-3.11 2.334-3.124l89.767-1.022c1.311-.015 2.375 1.387 2.375 3.123z" fill="#fff" />
							<path d="M243.993 208.501c0 5.36 4.246 9.708 9.482 9.708 5.237 0 9.482-4.348 9.482-9.708 0-5.362-4.245-9.709-9.482-9.709-5.236 0-9.482 4.347-9.482 9.709z" fill="#1FDE91" />
							<mask id="L" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="250" y="201" width="7" height="8">
								<path d="M256.911 204.565c0 1.744-1.418 3.627-3.164 3.627-1.749 0-3.167-1.883-3.167-3.627 0-1.744 1.418-2.981 3.167-2.981 1.746 0 3.164 1.237 3.164 2.981z" fill="#fff" />
							</mask>
							<g mask="url(#L)">
								<path d="M256.911 204.565c0 1.744-1.418 3.627-3.164 3.627-1.749 0-3.167-1.883-3.167-3.627 0-1.744 1.418-2.981 3.167-2.981 1.746 0 3.164 1.237 3.164 2.981z" fill="#242628" />
							</g>
							<mask id="M" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="247" y="209" width="13" height="6">
								<path d="M249.817 214.908h7.728c.813 0 1.533-.526 1.782-1.299l.414-1.297a1.86 1.86 0 0 0-1.127-2.318c-3.146-1.148-6.398-1.001-9.728.105a1.859 1.859 0 0 0-1.194 2.253l.321 1.176c.221.814.959 1.38 1.804 1.38z" fill="#fff" />
							</mask>
							<g mask="url(#M)">
								<path d="M249.817 214.908h7.728c.813 0 1.533-.526 1.782-1.299l.414-1.297a1.86 1.86 0 0 0-1.127-2.318c-3.146-1.148-6.398-1.001-9.728.105a1.859 1.859 0 0 0-1.194 2.253l.321 1.176c.221.814.959 1.38 1.804 1.38z" fill="#242628" />
							</g>
							<mask id="N" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="268" y="209" width="61" height="4">
								<path d="M328.603 211.033c0 1.014-.858 1.837-1.917 1.837h-56.427c-1.059 0-1.917-.823-1.917-1.837s.858-1.834 1.917-1.834h56.427c1.059 0 1.917.82 1.917 1.834z" fill="#fff" />
							</mask>
							<g mask="url(#N)">
								<path d="M328.601 211.033c0 1.014-.859 1.837-1.917 1.837h-56.426c-1.061 0-1.917-.823-1.917-1.837 0-1.013.856-1.833 1.917-1.833h56.426c1.058 0 1.917.82 1.917 1.833z" fill="#242628" />
							</g>
							<mask id="O" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="268" y="215" width="34" height="4">
								<path d="M301.329 216.787c0 .922-.748 1.667-1.671 1.667h-29.583a1.668 1.668 0 1 1 0-3.336h29.583a1.67 1.67 0 0 1 1.671 1.669z" fill="#fff" />
							</mask>
							<g mask="url(#O)">
								<path d="M301.328 216.788c0 .921-.748 1.667-1.67 1.667h-29.582a1.667 1.667 0 1 1 0-3.337h29.582c.922 0 1.67.746 1.67 1.67z" fill="#242628" />
							</g>
							<mask id="P" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="303" y="215" width="26" height="4">
								<path d="M328.665 216.787a1.67 1.67 0 0 1-1.673 1.667h-21.35a1.667 1.667 0 1 1 0-3.336h21.35a1.67 1.67 0 0 1 1.673 1.669z" fill="#fff" />
							</mask>
							<g mask="url(#P)">
								<path d="M328.666 216.787a1.67 1.67 0 0 1-1.674 1.667h-21.349a1.667 1.667 0 1 1 0-3.336h21.349a1.67 1.67 0 0 1 1.674 1.669z" fill="#242628" />
							</g>
							<path d="M301.368 202.131a2.345 2.345 0 0 1-2.347 2.342h-28.487a2.345 2.345 0 0 1-2.349-2.342v-.191a2.345 2.345 0 0 1 2.349-2.342h28.487a2.346 2.346 0 0 1 2.347 2.342v.191zM82.198 81.84h9.38v.938h-9.38zM82.198 90.282h9.38v.938h-9.38zM82.198 82.778h.938v.938h-.938zM82.198 89.344h.938v.938h-.938zM90.64 89.344h.938v.938h-.938z" fill="#1FDE91" />
							<path fill="#1FDE91" d="M81.26 82.778h.938v7.504h-.938zM91.578 82.778h.938v7.504h-.938zM83.136 83.716h.938v.938h-.938zM89.702 83.716h.938v.938h-.938zM84.074 84.654h.938v.938h-.938zM88.764 84.654h.938v.938h-.938zM85.012 85.592h.938v.938h-.938zM87.826 85.592h.938v.938h-.938z" />
							<path fill="#1FDE91" d="M85.95 86.529h1.876v.938H85.95zM90.64 82.778h.938v.938h-.938z" />
							<rect x="81.26" y="151.468" width="182.938" height="14.146" rx="1.286" fill="#fff" />
							<rect x="86.26" y="157.058" width="33" height="2" rx="1" fill="#8B8D8F" />
							<rect x="80.869" y="151.077" width="183.72" height="14.928" rx="1.677" stroke="#242628" stroke-opacity=".5" stroke-width=".782" />
							<rect x="81.26" y="127.644" width="182.938" height="14.146" rx="1.286" fill="#fff" />
							<rect x="86.26" y="134.058" width="33" height="2" rx="1" fill="#8B8D8F" />
							<rect x="80.869" y="127.253" width="183.72" height="14.928" rx="1.677" stroke="#242628" stroke-opacity=".5" stroke-width=".782" />
							<rect x="81.26" y="103.726" width="182.938" height="14.146" rx="1.286" fill="#fff" />
							<rect x="86.26" y="110.058" width="33" height="2" rx="1" fill="#8B8D8F" />
							<rect x="80.869" y="103.335" width="183.72" height="14.928" rx="1.677" stroke="#242628" stroke-opacity=".5" stroke-width=".782" />
							<path d="M104.812 83.524c-1.74 0-2.736 1.26-2.736 3.156 0 1.956 1.128 3.024 2.748 3.024 1.524 0 2.448-.84 2.448-2.244v-.036h-2.652v-1.356h4.08V91h-1.296l-.096-1.008c-.48.672-1.476 1.14-2.664 1.14-2.46 0-4.176-1.788-4.176-4.488 0-2.664 1.74-4.56 4.38-4.56 2.004 0 3.552 1.164 3.804 2.952h-1.62c-.276-1.032-1.164-1.512-2.22-1.512zm7.983 7.632c-1.764 0-3-1.284-3-3.12 0-1.86 1.212-3.144 2.952-3.144 1.776 0 2.904 1.188 2.904 3.036v.444l-4.464.012c.108 1.044.66 1.572 1.632 1.572.804 0 1.332-.312 1.5-.876h1.356c-.252 1.296-1.332 2.076-2.88 2.076zm-.036-5.064c-.864 0-1.392.468-1.536 1.356h2.976c0-.816-.564-1.356-1.44-1.356zM118.78 91h-1.464v-4.704h-1.14v-1.224h1.14v-1.848h1.464v1.848h1.152v1.224h-1.152V91zm6.028-7.116a.889.889 0 0 1-.9-.888c0-.492.396-.876.9-.876.48 0 .876.384.876.876a.884.884 0 0 1-.876.888zM124.076 91v-5.928h1.464V91h-1.464zm4.441 0h-1.464v-5.928h1.356l.12.768c.372-.6 1.092-.948 1.896-.948 1.488 0 2.256.924 2.256 2.46V91h-1.464v-3.3c0-.996-.492-1.476-1.248-1.476-.9 0-1.452.624-1.452 1.584V91zm10.853 0h-1.464v-4.704h-1.14v-1.224h1.14v-1.848h1.464v1.848h1.152v1.224h-1.152V91zm1.655-2.976c0-1.848 1.332-3.12 3.168-3.12s3.168 1.272 3.168 3.12-1.332 3.12-3.168 3.12-3.168-1.272-3.168-3.12zm1.464 0c0 1.08.696 1.812 1.704 1.812s1.704-.732 1.704-1.812-.696-1.812-1.704-1.812-1.704.732-1.704 1.812zm9.912-2.952h1.464V91h-1.356l-.108-.792c-.36.564-1.128.948-1.92.948-1.368 0-2.172-.924-2.172-2.376v-3.708h1.464v3.192c0 1.128.444 1.584 1.26 1.584.924 0 1.368-.54 1.368-1.668v-3.108zm2.511 2.952c0-1.836 1.212-3.132 2.964-3.132 1.62 0 2.724.9 2.88 2.328h-1.464c-.168-.672-.66-1.02-1.356-1.02-.936 0-1.56.708-1.56 1.824s.576 1.812 1.512 1.812c.732 0 1.248-.36 1.404-1.008h1.476c-.18 1.38-1.332 2.328-2.88 2.328-1.8 0-2.976-1.248-2.976-3.132zm8.41 2.976h-1.464v-8.928h1.476v3.768c.372-.576 1.068-.948 1.92-.948 1.464 0 2.232.924 2.232 2.46V91h-1.464v-3.3c0-.996-.492-1.476-1.236-1.476-.924 0-1.464.648-1.464 1.536V91z" fill="#242628" />
							<path fill="#1FDE91" d="M158.26 148.058h2.736v54.717h-2.736z" />
							<path fill="#1FDE91" d="M160.995 150.794h2.736v2.736h-2.736zM171.939 161.737h2.736v2.736h-2.736zM182.883 172.681h2.736v2.736h-2.736zM166.468 156.265h2.736v2.736h-2.736zM177.411 167.209h2.736v2.736h-2.736zM188.354 178.152h2.736v2.736h-2.736zM169.203 189.096h2.736v2.736h-2.736z" />
							<path fill="#1FDE91" d="M166.468 191.831h2.736v2.736h-2.736zM163.731 194.567h2.736v2.736h-2.736zM160.995 197.303h2.736v2.736h-2.736zM180.146 183.624h2.736v8.208h-2.736zM182.883 191.831h2.736v5.472h-2.736zM174.675 191.831h2.736v5.472h-2.736zM171.939 186.36h2.736v5.472h-2.736zM185.618 197.303h2.736v5.472h-2.736zM177.411 197.303h2.736v5.472h-2.736zM188.354 202.775h2.736v5.472h-2.736zM180.146 202.775h2.736v5.472h-2.736zM163.731 153.53h2.736v2.736h-2.736zM174.675 164.473h2.736v2.736h-2.736zM185.618 175.416h2.736v2.736h-2.736zM169.203 159.001h2.736v2.736h-2.736zM180.146 169.945h2.736v2.736h-2.736zM180.146 180.888h13.679v2.736h-13.679zM182.883 208.247h5.472v2.736h-5.472z" />
							<defs>
								<linearGradient id="g" x1="279.23" y1="322.708" x2="271.012" y2="285.385" gradientUnits="userSpaceOnUse">
									<stop stop-color="#FF928E" />
									<stop offset="1" stop-color="#FEB3B1" />
								</linearGradient>
								<linearGradient id="j" x1="292.659" y1="233.667" x2="273.411" y2="280.645" gradientUnits="userSpaceOnUse">
									<stop stop-color="#FF928E" />
									<stop offset="1" stop-color="#FEB3B1" />
								</linearGradient>
								<linearGradient id="o" x1="285.033" y1="237.355" x2="293.362" y2="257.803" gradientUnits="userSpaceOnUse">
									<stop stop-color="#FF928E" />
									<stop offset="1" stop-color="#FEB3B1" />
								</linearGradient>
								<linearGradient id="q" x1="-716.313" y1="340.056" x2="-703.14" y2="338.684" gradientUnits="userSpaceOnUse">
									<stop stop-color="#FF928E" />
									<stop offset="1" stop-color="#FEB3B1" />
								</linearGradient>
								<linearGradient id="t" x1="-715.046" y1="342.488" x2="-701.896" y2="341.119" gradientUnits="userSpaceOnUse">
									<stop stop-color="#FF928E" />
									<stop offset="1" stop-color="#FEB3B1" />
								</linearGradient>
								<linearGradient id="v" x1="352.794" y1="272.43" x2="310.594" y2="268.666" gradientUnits="userSpaceOnUse">
									<stop stop-color="#FF928E" />
									<stop offset="1" stop-color="#FEB3B1" />
								</linearGradient>
								<linearGradient id="y" x1="222.273" y1="156.367" x2="354.285" y2="289.941" gradientUnits="userSpaceOnUse">
									<stop stop-color="#B05B34" />
									<stop offset="1" stop-color="#3225BE" />
								</linearGradient>
							</defs>
						</svg>
					</div>
				</div>
				<?php
			}
		}
	}

	/**
	 * Page footer used on all admin pages.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function admin_page_footer() {
		$current_page = get_current_screen()->id;

		if ( 'edit-wpzf-form' == $current_page || 'wpzf-form' == $current_page || 'edit-wpzf-submission' == $current_page || 'wpzf-submission' == $current_page || 'wpzf-form_page_wpzf-settings' == $current_page ) {
			?>
			<footer class="wpzoom-new-admin_settings-footer">
				<div class="wpzoom-new-admin_settings-footer-wrap">
					<h3 class="wpzoom-new-admin_settings-footer-logo">
						<a href="https://www.wpzoom.com/" target="_blank" title="<?php esc_html_e( 'WPZOOM - WordPress themes with modern features and professional support', 'wpzoom-forms' ); ?>">
							<?php _e( 'WPZOOM', 'wpzoom-forms' ); ?>
						</a>
					</h3>

					<ul class="wpzoom-new-admin_settings-footer-links">
						<li class="wpzoom-new-admin_settings-footer-links-themes">
							<a href="https://www.wpzoom.com/themes/" target="_blank" title="<?php _e( 'Check out our themes', 'wpzoom-forms' ); ?>">
								<?php _e( 'Our Themes', 'wpzoom-forms' ); ?>
							</a>
						</li>

                        <li class="wpzoom-new-admin_settings-footer-links-themes">
                            <a href="https://www.wpzoom.com/plugins/" target="_blank" title="<?php _e( 'Check out our plugins', 'wpzoom-forms' ); ?>">
                                <?php _e( 'Our Plugins', 'wpzoom-forms' ); ?>
                            </a>
                        </li>

						<li class="wpzoom-new-admin_settings-footer-links-blog">
							<a href="https://www.wpzoom.com/blog/" target="_blank" title="<?php _e( 'See the latest updates on our blog', 'wpzoom-forms' ); ?>">
								<?php _e( 'Blog', 'wpzoom-forms' ); ?>
							</a>
						</li>

                        <li class="wpzoom-new-admin_settings-footer-links-themes">
                            <a href="https://www.wpzoom.com/documentation/wpzoom-forms/" target="_blank" title="<?php _e( 'Documentation', 'wpzoom-forms' ); ?>">
                                <?php _e( 'Documentation', 'wpzoom-forms' ); ?>
                            </a>
                        </li>

						<li class="wpzoom-new-admin_settings-footer-links-support">
							<a href="https://www.wpzoom.com/support/" target="_blank" title="<?php _e( 'Get support', 'wpzoom-forms' ); ?>">
								<?php _e( 'Support', 'wpzoom-forms' ); ?>
							</a>
						</li>
					</ul>
				</div>
			</footer>
			<?php
		}
	}

	public function admin_body_class_filter( $classes ) {
		$current_page = get_current_screen()->id;

		if ( 'edit-wpzf-form' == $current_page || 'wpzf-form' == $current_page || 'edit-wpzf-submission' == $current_page || 'wpzf-submission' == $current_page || 'wpzf-form_page_wpzf-settings' == $current_page ) {
			$classes .= ' wpzoom-new-admin';
		}

		$forms_count = wp_count_posts( 'wpzf-form' );

		if ( 'edit-wpzf-form' == $current_page && $forms_count->publish < 1 && $forms_count->draft < 1 && $forms_count->trash < 1 ) {
			$classes .= ' wpzf-no-forms';
		}

		return $classes;
	}

	/**
	 * Returns whether the given input is considered spam by checking it with Akismet.
	 *
	 * @since  1.0.4
	 * @access public
	 * @param  array  $input The input to check for spam.
	 * @return bool          Whether it is spam.
	 */
	public function not_spam( $input ) {

		if( is_callable( array( 'Akismet', 'get_api_key' ) ) && is_callable( array( 'Akismet', 'http_post' ) ) ) {

			$request    = array(
				'comment_type'         => 'contact-form',
				'comment_author'       => isset( $input['name'] ) ? $input['name'] : '',
				'comment_author_email' => isset( $input['from'] ) ? $input['from'] : '',
				'comment_author_url'   => isset( $input['url'] ) ? $input['url'] : '',
				'comment_content'      => isset( $input['message'] ) ? $input['message'] : '',
				'blog'                 => get_option( 'home' ),
				'user_ip'              => $this->get_remote_address(),
				// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				'user_agent'           => isset( $_SERVER['HTTP_USER_AGENT'] ) ? wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) : null,
				'referrer'             => wp_get_referer() ? wp_get_referer() : null,
				// phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				'permalink'            => get_permalink(),
				'blog_lang'            => get_locale(),
				'blog_charset'         => get_bloginfo( 'charset' ),
				'user_role'            => Akismet::get_user_roles( get_current_user_id() ),
				'is_test'              => false,
			
			);

			$response = Akismet::http_post( build_query( $request ), 'comment-check' );

			if( ! empty( $response ) && isset( $response[1] ) && 'true' === trim( $response[1] ) ) {
				return false;
			}

		}

		return true;
	}

	/**
	 * Returns the remote address of the connected peer.
	 *
	 * @since  1.0.4
	 * @access public
	 * @return string The remote address, or empty string on failure.
	 */
	public function get_remote_address() {
		$server_variable_keys = array(
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR'
		);

		foreach ( $server_variable_keys as $key ) {
			if ( array_key_exists( $key, $_SERVER ) === true ) {
				foreach ( array_map( 'trim', explode( ',', $_SERVER[ $key ] ) ) as $ip ) {
					if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false ) {
						return $ip;
					}
				}
			}
		}

		return '';
	}

	/**
	 * Callback that is triggered when a form is submitted on the frontend.
	 *
	 * @access public
	 * @return void
	 * @since  1.0.0
	 */
	public function action_form_post() {
		$success = false;
		$url     = isset( $_POST['_wp_http_referer'] ) ? sanitize_text_field( wp_unslash( $_POST['_wp_http_referer'] ) ) : home_url();
		$form_id = -1;

		if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'wpzf_submit' ) ) {
			$form_id = isset( $_POST['form_id'] ) ? intval( $_POST['form_id'] ) : -1;
			$blocks  = parse_blocks( $form_id > -1 ? get_post_field( 'post_content', $form_id, 'raw' ) : '' );

			//Check if recaptcha is enabled and the form passes it's check
			if ( 'recaptcha' == WPZOOM_Forms_Settings::get( 'wpzf_global_captcha_service' ) ) {
				$captcha = false;

				if ( isset( $_POST['g-recaptcha-response'] ) ) {
					$captcha = trim( sanitize_text_field( $_POST['g-recaptcha-response'] ) );
				}

				if( 'v3' ==  WPZOOM_Forms_Settings::get( 'wpzf_global_captcha_type' ) && isset( $_POST['recaptcha_token'] ) ) {
					$captcha = trim( sanitize_text_field( $_POST['recaptcha_token'] ) );
				}
				

				if ( ! empty( $captcha ) ) {
					$secret = trim( sanitize_text_field( WPZOOM_Forms_Settings::get( 'wpzf_global_captcha_secret_key' ) ) );

					if ( ! empty( $secret ) ) {
						$response = file_get_contents(
							sprintf(
								'https://www.google.com/recaptcha/api/siteverify?secret=%1$s&response=%2$s&remoteip=%3$s',
								$secret,
								$captcha,
								$_SERVER['REMOTE_ADDR']
							)
						);

						if ( false !== $response && ! empty( $response ) ) {
							$json = json_decode( $response );

							if( 'v3' ==  WPZOOM_Forms_Settings::get( 'wpzf_global_captcha_type' ) ) { 
								if ( null !== $json && is_object( $json ) && true === $json->success && $json->score >= 0.5 ) {
									$captcha_check_passed = true;
								}
							}
							else {
								if ( null !== $json && is_object( $json ) && true === $json->success ) {
									$captcha_check_passed = true;
								}
							}

						}
					}
				}
			} else {
				$captcha_check_passed = true;
			}

			if ( $captcha_check_passed && count( $blocks ) > 0 ) {
				$clean_site_name    = sanitize_text_field( get_bloginfo( 'name' ) );
				$input_blocks   = $this->get_input_blocks( $blocks );
				$form_method    = get_post_meta( $form_id, '_form_method', true ) ?: 'email';
				$form_email     = get_post_meta( $form_id, '_form_email', true );
				$fallback_email = trim( get_option( 'admin_email' ) );
				$sendto         = sanitize_email( false !== $form_email && ! empty( $form_email ) && filter_var( $form_email, FILTER_VALIDATE_EMAIL ) ? $form_email : $fallback_email );

				if ( 'email' == $form_method ) {
					$email_body = '<html>
						<head>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							<meta http-equiv="X-UA-Compatible" content="IE=edge">
							<meta name="viewport" content="width=device-width">

							<style type="text/css">
								body {
									-ms-text-size-adjust: 100%; width: 100% !important; height: 100%; line-height: 1.6;
									font-family: -apple-system, BlinkMacSystemFont, avenir next, avenir, segoe ui, helvetica neue, helvetica, Cantarell, Ubuntu, roboto, noto, arial, sans-serif;
								}
								a { color: #4477bd; }
								a:hover {
								color: #e2911a !important;
								}
								a:active {
								color: #0d3d62 !important;
								}
								p{
									margin:10px 0;
									padding:0;
									font-family: -apple-system, BlinkMacSystemFont, avenir next, avenir, segoe ui, helvetica neue, helvetica, Cantarell, Ubuntu, roboto, noto, arial, sans-serif;
								}
								table{
									border-collapse:collapse;
								}
								h1,h2,h3,h4,h5,h6{
									display:block;
									margin:0;
									padding:0;
								}
								img,a img{
									border:0;
									height:auto;
									outline:none;
									text-decoration:none;
								}
								body,#bodyTable,#bodyCell{
									height:100%;
									margin:0;
									padding:0;
									width:100%;
								}
								#outlook a{
									padding:0;
								}
								img{
									-ms-interpolation-mode:bicubic;
								}
								table{
									mso-table-lspace:0pt;
									mso-table-rspace:0pt;
								}
								p,a,li,td,blockquote{
									mso-line-height-rule:exactly;
								}
								a[href^=tel],a[href^=sms]{
									color:inherit;
									cursor:default;
									text-decoration:none;
								}
								p,a,li,td,body,table,blockquote{
									-ms-text-size-adjust:100%;
									-webkit-text-size-adjust:100%;
								}
								a[x-apple-data-detectors]{
									color:inherit !important;
									text-decoration:none !important;
									font-size:inherit !important;
									font-family:inherit !important;
									font-weight:inherit !important;
									line-height:inherit !important;
								}
								@media only screen and (max-width: 480px){
									body,table,td,p,a,li,blockquote{
										-webkit-text-size-adjust:none !important;
									}
								}
								@media only screen and (max-width: 480px){
									body{
										width:100% !important;
										min-width:100% !important;
									}
								}
							</style>
						</head>
						<body style="height: 100%;margin: 0;padding: 0;width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
							<div style="font-family:-apple-system, BlinkMacSystemFont, avenir next, avenir, segoe ui, helvetica neue, helvetica, Cantarell, Ubuntu, roboto, noto, arial, sans-serif;box-sizing:border-box;font-size:14px;line-height:1.5;max-width:600px;overflow:visible;display:block;margin:0">
								<table width="100%" cellpadding="0" cellspacing="0" style="font-family:-apple-system, BlinkMacSystemFont, avenir next, avenir, segoe ui, helvetica neue, helvetica, Cantarell, Ubuntu, roboto, noto, arial, sans-serif;box-sizing:border-box;font-size:14px;line-height:1.5">
								<tbody>
								<tr style="font-family:-apple-system, BlinkMacSystemFont, avenir next, avenir, segoe ui, helvetica neue, helvetica, Cantarell, Ubuntu, roboto, noto, arial, sans-serif;box-sizing:border-box;font-size:14px;line-height:1.5">
									<td style="font-family:-apple-system, BlinkMacSystemFont, avenir next, avenir, segoe ui, helvetica neue, helvetica, Cantarell, Ubuntu, roboto, noto, arial, sans-serif;box-sizing:border-box;font-size:14px;line-height:1.5;vertical-align:top;color:#222222;padding:25px" valign="top">';
					$replyto = '';
					$sbj = '';
					$raw_content = array(
						'_wpzf_fields'  => array()
					);

					foreach ( $_REQUEST as $key => $value ) {
						if ( strpos( $key, 'wpzf_' ) === 0 ) {
							$id = substr( $key, 5 );
							$name = isset( $input_blocks[ $id ] ) ? $input_blocks[ $id ] : __( 'Unnamed Input', 'wpzoom-forms' );

                            if ( 'wpzf_replyto' == $key ) {
								$replyto = sanitize_text_field( $value );
								continue;
							} elseif ( 'wpzf_subject' == $key ) {
								$sbj = sanitize_text_field( $value );
								continue;
							}

							$email_body .= '<strong>' . esc_html( wp_unslash( $name ) ) . ':</strong><br/><br/>' . esc_html( wp_unslash( $value ) ) . '<br/><br/><hr/><br/>';
							$raw_content['_wpzf_fields'][ $name ] = sanitize_text_field( $value );
						}
					}

					$fromaddr     = ! empty( $replyto ) && isset( $_REQUEST[ $replyto ] ) ? sanitize_email( $_REQUEST[ $replyto ] ) : $sendto;
					$cleanname    = sanitize_text_field( get_bloginfo( 'name' ) );
					$subjectline  = ! empty( $sbj ) && isset( $_REQUEST[ $sbj ] ) ? sanitize_text_field( $_REQUEST[ $sbj ] ) : esc_html__( 'New Form Submission!', 'wpzoom-forms' );
					$subjectline .= sprintf( __( ' -- %s', 'wpzoom-forms' ), $cleanname );

					$email_body   = '<html style="background-color:#dddddd;"><body style="background-color:#dddddd;padding:2em;"><div style="background-color:#ffffff;width:70%;padding:2em;border-radius:10px;box-shadow:0px 5px 5px #aaaaaa;">' . preg_replace( '/<br\/><br\/><hr\/><br\/>$/is', '', $email_body ) . '</div></body></html>';

					$headers      = sprintf(
						"Content-Type: text/html; charset=UTF-8\r\nFrom: %s <%s>\r\nReply-To: %s",
						$cleanname,
						$fromaddr,
						$fromaddr
					);

					$email_body  .= '</td>
								</tr>
								</tbody></table>
							</div>

							<div style="font-family:-apple-system, BlinkMacSystemFont, avenir next, avenir, segoe ui, helvetica neue, helvetica, Cantarell, Ubuntu, roboto, noto, arial, sans-serif;box-sizing:border-box;font-size:14px;line-height:1.5;max-width:600px;overflow:visible;display:block;margin:0">
								<table width="100%" cellpadding="0" cellspacing="0" style="font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;line-height:1.5">
									<tbody><tr style="font-family:-apple-system, BlinkMacSystemFont, avenir next, avenir, segoe ui, helvetica neue, helvetica, Cantarell, Ubuntu, roboto, noto, arial, sans-serif;box-sizing:border-box;font-size:14px;line-height:1.5">
									<td style="font-family:-apple-system, BlinkMacSystemFont, avenir next, avenir, segoe ui, helvetica neue, helvetica, Cantarell, Ubuntu, roboto, noto, arial, sans-serif;box-sizing:border-box;font-size:14px;line-height:1.5;vertical-align:top;width:100%;clear:both;color:#777;border-top-width:1px;border-top-color:#d0d0d0;border-top-style:solid;padding:25px" valign="top">
										<p>Sent from <a href="' . esc_attr( get_bloginfo( 'url' ) ) . '">' . $cleanname . '</a> using the <strong>WPZOOM Forms</strong> plugin.</p>
										<br style="font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;line-height:1.5">
									</td>
									</tr>
								</tbody></table>
							</div>
						</body>
					</html>';

					$details = array(
						'from'    => $fromaddr,
						'message' => $raw_content
					);

					if ( $this->not_spam( $details ) ) {
						$success = wp_mail( $sendto, $subjectline, $email_body, $headers );
					}
				} elseif ( 'db' == $form_method ) {
					$content = array(
						'_wpzf_form_id' => $form_id,
						'_wpzf_fields'  => array()
					);

					$replyto = '';
					$sbj = '';

					foreach ( $_REQUEST as $key => $value ) {
						if ( strpos( $key, 'wpzf_' ) === 0 ) {
							$id   = substr( $key, 5 );
							$name = isset( $input_blocks[ $id ] ) ? $input_blocks[ $id ] : __( 'Unnamed Input', 'wpzoom-forms' );

							if ( 'wpzf_replyto' == $key || 'wpzf_subject' == $key ) {
								if ( 'wpzf_replyto' == $key ) {
									$replyto = sanitize_text_field( $value );
								} elseif ( 'wpzf_subject' == $key ) {
									$sbj = sanitize_text_field( $value );
								}

								continue;
							}

							$content['_wpzf_fields'][ $name ] = sanitize_text_field( $value );
						}
					}

					$details = array(
						'from'    => '',
						'message' => $content
					);

					if ( ! empty( $replyto ) ) {
						$fromaddr = isset( $_REQUEST[ $replyto ] ) ? sanitize_email( $_REQUEST[ $replyto ] ) : $sendto;
						$details['from'] = $fromaddr;
					}

					if ( $this->not_spam( $details ) ) {
						$success = false !== $content && 0 < wp_insert_post( array(
							'post_type'      => 'wpzf-submission',
							'post_status'    => 'publish',
							'comment_status' => 'closed',
							'ping_status'    => 'closed',
							'post_title'     => __( 'Submission', 'wpzoom-forms' ),
							'post_author'    => 1,
							'post_category'  => array( 1 ),
							'post_content'   => __( 'Submission', 'wpzoom-forms' ),
							'meta_input'     => $content
						) );
					}
				}
			}
		}

		wp_safe_redirect(
			urldecode( add_query_arg( 'success', ( $success ? '1' : '0' ), $url ) ) .
			( $form_id > -1 ? '#wpzf-' . $form_id : '' )
		);

		exit;
	}

	/**
	 * Filters a hierarchical array of Gutenberg blocks to return just the blocks added by this plugin.
	 *
	 * @access public
	 * @param  array $blocks A hierarchical array of Gutenberg blocks.
	 * @return array
	 * @since  1.0.0
	 */
	public function get_input_blocks( $blocks ) {
		$found = array();

		if ( is_array( $blocks ) && count( $blocks ) > 0 ) {
			for ( $i = 0; $i < count( $blocks ); $i++ ) { 
				$block = $blocks[ $i ];

				if ( isset( $block['blockName'] ) &&
				     preg_match( '/^wpzoom\-forms\//i', $block['blockName'] ) &&
				     ! preg_match( '/(label|submit)\-field$/i', $block['blockName'] ) &&
				     isset( $block['attrs'] ) ) {
					$attrs = $block['attrs'];

					if ( array_key_exists( 'id', $attrs ) && array_key_exists( 'name', $attrs ) ) {
						$found[ $attrs['id'] ] = $attrs['name'];
					}
				}

				if ( isset( $block['innerBlocks'] ) ) {
					$found = array_merge( $found, $this->get_input_blocks( $block['innerBlocks'] ) );
				}
			}
		}

		return $found;
	}

	/**
	 * Returns the output for the form shortcode.
	 *
	 * @access public
	 * @param  array|string $atts    Shortcode attributes array or empty string.
	 * @param  string       $content The shortcode content, or null if not set.
	 * @param  string       $tag     The shortcode name.
	 * @return string                The shortcode output.
	 * @since  1.0.0
	 */
	public function shortcode_output( $atts, $content, $tag ) {
		$id     = is_array( $atts ) && array_key_exists( 'id', $atts ) ? intval( $atts['id'] ) : -1;
		$output = '';

		if ( $id > 0 ) {
			wp_enqueue_script( 'wpzoom-forms-js-frontend-formblock' );
			wp_enqueue_style( 'wpzoom-forms-css-frontend-formblock' );

			$output = $this->form_block_render( array( 'formId' => $id ) );
		}

		return $output;
	}
}

if( ! function_exists ( 'wpzoom_forms_load_files' ) ) {
	function wpzoom_forms_load_files() {

		//Load Settings Panel
		require_once 'classes/class-wpzoom-forms-settings-fields.php';
		require_once 'classes/class-wpzoom-forms-settings-page.php';
	
	}
	add_action( 'plugin_loaded', 'wpzoom_forms_load_files' );
}


/**
 * Check if the Elementor Page Builder is enabled load the widget
 */
if ( defined( 'ELEMENTOR_VERSION' ) && is_callable( 'Elementor\Plugin::instance' ) ) {
	require_once 'elementor/wpzoom-forms-elementor.php';
}
