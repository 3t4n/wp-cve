<?php 
/*
 * Magic Template Holder
 *
 * @package     Magic Template Holder
 * @author      Nora
 * @copyright   2016 Nora https://wp-works.net
 * @license     GPL-2.0+
 * 
 * @wordpress-plugin
 * Plugin Name: Magic Template Holder
 * Plugin URI: https://wp-works.net
 * Description: Enable to handle templates easily on editor page.
 * Version: 1.0.12
 * Author: Nora
 * Author URI: https://wp-works.net
 * Text Domain: magic-template-holder
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/


if( ! defined( 'ABSPATH' ) ) exit;
if( ! is_admin() ) return;

// Consts
	if( ! defined( 'MTH_NAME' ) ) define( 'MTH_NAME', 'Magic Template Holder' );
	if( ! defined( 'MTH_PREFIX' ) ) define( 'MTH_PREFIX', 'mth-' );
	if( ! defined( 'MTH_OPTION' ) ) define( 'MTH_OPTION', 'mth_' );
	if( ! defined( 'MTH_MODS' ) ) define( 'MTH_MODS', 'mth_mods_' );
	if( ! defined( 'MTH_POST_META' ) ) define( 'MTH_POST_META', '_mth_post_meta_' );

// プラグインのディレクトリ
	if( ! defined( 'MTH_DIR_URL' ) ) define( 'MTH_DIR_URL', plugin_dir_url( __FILE__ ) );
	if( ! defined( 'MTH_DIR_PATH' ) ) define( 'MTH_DIR_PATH', plugin_dir_path( __FILE__ ) );

// サイトの名前
	if( ! defined( 'SITE_NAME' ) ) define( 'SITE_NAME', get_bloginfo( 'name' ) );
// サイトの詳細
	if( ! defined( 'SITE_DESCRIPTION' ) ) define( 'SITE_DESCRIPTION', get_bloginfo( 'description' ) );
// サイトのホームURL
	if( ! defined( 'SITE_URL' ) ) define( 'SITE_URL', esc_url( home_url() ) );

if( ! class_exists( 'Magic_Template_Holder' ) ) {
/**
 * Magic Template Holder
**/
final class Magic_Template_Holder {

	#
	# Vars
	#
		/**
		 * Template Holder
		 * 
		 * @var array
		**/
		protected $mth_template_holder = array();

		/**
		 * Is Edit Page
		 * 
		 * @var bool
		**/
		public $is_edit_page = false;

	#
	# Tools
	#
		/**
		 * Check if is in Edit Page 
		 * 
		 * @param  string  $new_edit what page to check for accepts new - new post page ,edit - edit post page, null for either
		 * 
		 * @return void
		 */
		function set_is_edit_page( $new_edit = null ) {

			// Check if is Admin
			if ( ! is_admin() ) {
				return;
			}

			global $pagenow;
			$this->is_edit_page = in_array( $pagenow, array( 'post.php', 'post-new.php' ) );

		}

	#
	# Init
	#
		/**
		 * Constructor
		**/
		function __construct() {

			$this->set_is_edit_page();

			# Text Domain
				load_plugin_textdomain(
					'magic-template-holder',
					false,
					dirname( plugin_basename( __FILE__ ) ) . '/languages'
				);

				$this->includes();
				$this->init_hooks();

		}

		/**
		 * Includes files and Init some classes
		**/
		function includes() {

			if ( ! $this->is_edit_page ) {
				return;
			}

			# Media Buttons
				# Media Buttons Class
					require_once( 'class-media-button.php' );
					$editor_buttons = array();

				# Insert Templates
					$args = array(
						'class' => 'mth-tempalte-media-button insert-mth-template',
						'text'  => esc_html__( 'Insert a Template', 'magic-template-holder' ),
						'icon'  => '<span class="wp-media-buttons-icon dashicons dashicons-editor-paste-text"></span>'
					);
					$editor_buttons['insert-template'] = new Nora_Editor_Button( $args );

				# Make Templates
					$args2 = array(
						'class' => 'mth-tempalte-media-button make-mth-template',
						'text'  => esc_html__( 'Make a Template', 'magic-template-holder' ),
						'icon'  => '<span class="wp-media-buttons-icon dashicons dashicons-welcome-add-page"></span>'
					);
					$editor_buttons['make-template'] = new Nora_Editor_Button( $args2 );

		}

		/**
		 * Add Actions and Filters
		**/
		function init_hooks() {

			add_action( 'init', array( $this, 'mth_add_post_type_init' ) );
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_print_scripts', array( $this, 'admin_print_scripts' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'mth_enqueue_editor_scripts' ) );

			add_action( 'admin_footer-post-new.php', array( $this, 'mth_popup_box' ) );
			add_action( 'admin_footer-post.php', array( $this, 'mth_popup_box' ) );

			add_action( 'wp_ajax_mth_make_template_from_content', array( $this, 'mth_make_template_from_content' ) );

		}

	#
	# Actions
	#
		// カスタム投稿タイプ「mth-template」を追加
		function mth_add_post_type_init() {
			
			$labels = array(
				'name'               => __( 'MTH Templates', 'magic-template-holder' ),
				'singular_name'      => __( 'MTH Templates', 'magic-template-holder' ),
				'menu_name'          => __( 'MTH Templates', 'magic-template-holder' ),
				'name_admin_bar'     => __( 'MTH Template', 'magic-template-holder' ),
				'add_new'            => __( 'Add new', 'magic-template-holder' ),
				'add_new_item'       => __( 'Add New MTH Template', 'magic-template-holder' ),
				'new_item'           => __( 'New MTH Template', 'magic-template-holder' ),
				'edit_item'          => __( 'Edit MTH Template', 'magic-template-holder' ),
				'view_item'          => __( 'View MTH Template', 'magic-template-holder' ),
				'all_items'          => __( 'All MTH Templates', 'magic-template-holder' ),
				'search_items'       => __( 'Search MTH Templates', 'magic-template-holder' ),
				'parent_item_colon'  => __( 'Parent MTH Templates:', 'magic-template-holder' ),
				'not_found'          => __( 'No MTH Templates found.', 'magic-template-holder' ),
				'not_found_in_trash' => __( 'No MTH Templates found in Trash.', 'magic-template-holder' )
			);

			$args = array(
				'labels'              => $labels,
				'public'              => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'query_var'           => false,
				'rewrite'             => array( 'slug' => 'mth-templates' ),
				'capability_type'     => 'post',
				'has_archive'         => false,
				'hierarchical'        => false,
				'menu_position'       => null,
				'supports'            => array( 'title', 'editor', 'author' )
			);

			register_post_type( 'mth-template', $args );
			
			$labels = array(
				'name'              => __( 'Groups', 'magic-template-holder' ),
				'singular_name'     => __( 'Group', 'magic-template-holder' ),
				'search_items'      => __( 'Search Groups', 'magic-template-holder' ),
				'all_items'         => __( 'All Groups', 'magic-template-holder' ),
				'parent_item'       => __( 'Parent Group', 'magic-template-holder' ),
				'parent_item_colon' => __( 'Parent Group:', 'magic-template-holder' ),
				'edit_item'         => __( 'Edit Group', 'magic-template-holder' ),
				'update_item'       => __( 'Update Group', 'magic-template-holder' ),
				'add_new_item'      => __( 'Add New Group', 'magic-template-holder' ),
				'new_item_name'     => __( 'New Group Name', 'magic-template-holder' ),
				'menu_name'         => __( 'Group', 'magic-template-holder' ),
			);

			$args = array(
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => false,
				'rewrite'           => array( 'slug' => 'mth-template-group' ),
			);

			register_taxonomy( 'mth-template-group', array( 'mth-template' ), $args );

		}

		// Add Button
		function admin_init() {

			if ( ! $this->is_edit_page ) {
				return;
			}

			# check user permissions
				if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
					return;
				}

			# check if Rich Editor is enabled
				if ( 'true' == get_user_option( 'rich_editing' ) ) {
					add_filter( 'mce_external_plugins', array( $this ,'mce_external_plugins' ) );
					add_filter( 'mce_buttons', array( $this, 'mce_buttons' ) );
				}

		}
			// mce_external_plugins
			function mce_external_plugins( $plugin_array ) {
				$plugin_array['mth_buttons'] = esc_url( MTH_DIR_URL . 'js/mce-buttons.js' );
				return $plugin_array;
			}

			// MCE Button
			function mce_buttons( $buttons ) {
				array_push( $buttons, 'mth-insert-template', 'mth-make-template' );
				return $buttons;
			}
			// Button Style
			function admin_print_scripts() {
				echo '<style>
				i.mce-i-dashicons-download:before {
					font-family: dashicons;
				    content: "\f316";
				}
				i.mce-i-dashicons-upload:before {
					font-family: dashicons;
				    content: "\f317";
				}
				</style>
				';
			}

		// Add Option Page
		function admin_menu() {

			# Setting Page
				if( current_user_can( 'manage_options' ) ) {

					add_submenu_page( 
						'edit.php?post_type=mth-template',
						esc_html__( 'Settings', 'magic-template-holder' ), 
						esc_html__( 'Settings', 'magic-template-holder' ), 
						'manage_options', 
						'mth_template_options', 
						array( $this, 'mth_template_options' ) 
					);

				}

			#

		}

			// Setting Page
			function mth_template_options() {

				require_once( 'templates/option-page.php' );

			}

		// エディター用のCSS・JSエンキュー
		function mth_enqueue_editor_scripts( $hook ) {

			// Register
				// Style
					wp_enqueue_style( 
						'mth-templates-css',
						MTH_DIR_URL . 'css/mth-templates.css'
					);

				// Media Buttons
					wp_register_script(
						'mth-media-buttons-js',
						MTH_DIR_URL . 'js/media-buttons.js',
						array( 'jquery', 'underscore' ),
						false,
						true
					);

				// QuickTags
					wp_register_script(
						'mth-quicktags-js',
						MTH_DIR_URL . 'js/quicktags.js',
						array( 'jquery', 'underscore', 'mth-media-buttons-js' ),
						false,
						true
					);

			// Localize
				// Translated Strings Object
					$args = array(
						'mthAjaxUrl' => esc_url( admin_url( 'admin-ajax.php' ) ),
						'mthGroupCount' => __( '%1$s（ Num : %2$d ）', 'magic-template-holder' ),
						'insertTemplate' => __( 'Insert a Template', 'magic-template-holder' ),
						'makeTemplate' => __( 'Make a new Template', 'magic-template-holder' ),
						'close' => __( 'Close', 'magic-template-holder' ),
						'cancel' => __( 'Cancel', 'magic-template-holder' ),
						'draftSuffix' => __( '( Draft)', 'magic-template-holder' )
					);
					wp_localize_script( 'jquery', 'mthLocalizedData', $args );
				// MCE Buttons
					$args = array(
						'templateObjects' => $this->mth_get_templates_list(),
						'templateGroupObjects' => $this->mth_get_template_groups()
					);
					wp_localize_script( 'jquery', 'templatesData', $args );

			// Enqueue
				// Style
					wp_enqueue_style( 'mth-templates-css' );
				// QuickTags
					if ( $this->is_edit_page ) {
						wp_enqueue_script( 'mth-quicktags-js' );
					}

		}

		// Popup Window
		function mth_popup_box() { 

			require_once( MTH_DIR_PATH . 'templates/popup-forms.php' );
			wp_nonce_field( 'mth-templates', 'mth-templates-nonce' );

		}

	#
	# AJAX用
	#
		function mth_get_templates_list() {

			//if( isset( $_REQUEST[ 'mth_template_nonce' ] ) ) $mth_template_nonce = $_REQUEST[ 'mth_template_nonce' ];

			//if( ! wp_verify_nonce( $mth_template_nonce, 'mth-templates' ) ) return;

			$mth_templates_args = array(
				'post_type'      => 'mth-template',
				'post_status'    => array( 'publish', 'draft' ),
				'posts_per_page' => -1,
				'orderby'        => 'ID',
				'order'          => 'ASC',
			);

			if( ! empty( $_REQUEST[ 'mthTemplateGroups' ] ) ) { $mth_template_group = $_REQUEST[ 'mthTemplateGroups' ];	}
			

			if( isset( $mth_template_group ) ) { 
				$template_group = array();
				foreach( $mth_template_group as $index => $value ) {
					if( $value ) 
						$template_group[] = $value;
				}
				if( is_array( $template_group ) ) {
					$mth_templates_args[ 'tax_query' ] = array(
						array(
							'taxonomy' => 'mth-template-group',
							'field' => 'id',
							'terms' => $template_group,
							'operator' => 'AND'
						)
					);
				}
			}

			$mth_templates = get_posts( $mth_templates_args );

			if( ! empty( $mth_templates ) ) {
				foreach( $mth_templates as $mth_template_key => $template ) {

					$class_group = '';
					$template_groups = get_the_terms( $template->ID, 'mth-template-group' );
					if( ! empty( $template_groups ) ) {
						foreach( $template_groups as $template_group ) {
							$class_group .= 'group-' . $template_group->term_id . ' ';
						}
						$class_group = substr( $class_group, 0, -1 );
					}

					$mth_templates[ $mth_template_key ]->group_classes = $class_group;
				
				}
			} else {
				$mth_templates = array();
			}

			return $mth_templates;

			//wp_die( json_encode( $mth_templates ) );

		}

		function mth_get_template_groups() {
			$template_groups = get_terms( 'mth-template-group' );
			return ( ! empty( $template_groups ) ? $template_groups : array() );
		}

		function mth_get_the_template_group_classes( $id = 0 ) {

			$class_group = '';
			$template_groups = get_the_terms( $id, 'mth-template-group' );
			if( ! empty( $template_groups ) ) {
				foreach( $template_groups as $template_group ) {
					$class_group .= ' group-' . $template_group->term_id;
				}
			}
			wp_die( $class_group );
		}

		function mth_get_the_template_group_classes_return( $id = 0 ) {

			$class_group = '';
			$template_groups = get_the_terms( $id, 'mth-template-group' );
			if( ! empty( $template_groups ) ) {
				foreach( $template_groups as $template_group ) {
					$class_group .= ' group-' . $template_group->term_id;
				}
			}
			return $class_group;
		}

		function mth_get_template() {

			//if( isset( $_REQUEST[ 'mth_template_nonce' ] ) ) $mth_template_nonce = $_REQUEST[ 'mth_template_nonce' ];

			//if( ! wp_verify_nonce( $mth_template_nonce, 'mth-templates' ) ) return;

			if( isset( $_REQUEST[ 'mthTemplateId' ] ) ) { $mth_template_id = $_REQUEST[ 'mthTemplateId' ]; }

			$template = get_post( $mth_template_id );

			$template = array(
				'content' => $template->post_content
			);

			wp_die( wp_json_encode( $template ) );

		}

		function mth_make_template_from_content() {

			if( isset( $_REQUEST[ 'mth_template_nonce' ] ) ) $mth_template_nonce = $_REQUEST[ 'mth_template_nonce' ];

			if( ! wp_verify_nonce( $mth_template_nonce, 'mth-templates' ) ) return;

			if( isset( $_REQUEST[ 'templateTitle' ] ) ) { $template_title = $_REQUEST[ 'templateTitle' ]; }
			if( isset( $_REQUEST[ 'templateGroup' ] ) ) { $template_group = $_REQUEST[ 'templateGroup' ]; }
			if( isset( $_REQUEST[ 'templateText' ] ) ) { $template_text = $_REQUEST[ 'templateText' ]; }

			$args = array(
				'post_type'  => 'mth-template',
				'post_title' => $template_title,
				'post_status' => 'publish',
				'post_content' => wp_kses_post( $template_text )
			);

			$insert_template_id = wp_insert_post( $args );
			//unset( $matched_title );

			if( isset( $insert_template_id ) ) {

				if( $template_group != '' ) {

					$template_group_preg_str = '/([^, ]+)/';
					if ( preg_match_all( $template_group_preg_str, $template_group, $script_handles_preg_str_s )) {
						$template_groups_array = $script_handles_preg_str_s[ 0 ];
					}
					$tt_id = wp_set_object_terms( $insert_template_id, $template_groups_array, 'mth-template-group', false );
				}

			}

			$inserted_template = get_post( $insert_template_id );
			$inserted_template->post_title .= ' ' . __( '( Added )', 'magic-template-holder' );

			$return = array(
				'template_object' => $inserted_template,
				'template_groups' => $this->get_the_template_groups( $insert_template_id ),
				'group_classes' => $this->mth_get_the_template_group_classes_return( $insert_template_id )
			);

			wp_die( wp_json_encode( $return ) );

		}

		function get_the_template_groups( $template_id = 0 ) {

			$template_groups = get_the_terms( $template_id, 'mth-template-group' );
			return $template_groups;

		}

}
}


new Magic_Template_Holder;

?>