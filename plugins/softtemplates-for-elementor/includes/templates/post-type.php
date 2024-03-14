<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    SoftHopper
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Core_Templates_Post_Type' ) ) {

	/**
	 * Define Soft_template_Core_Templates_Post_Type class
	 */
	class Soft_template_Core_Templates_Post_Type {

		/**
		 * Post type slug.
		 *
		 * @var string
		 */
		public $post_type = 'soft-template-core';

		/**
		 * Template meta cache key
		 *
		 * @var string
		 */
		public $cache_key = '_softtemplate_template_cache';

		/**
		 * Template type arg for URL
		 * @var string
		 */
		public $type_tax = 'softtemplate_library_type';

		/**
		 * Site conditions
		 * @var array
		 */
		private $conditions = array();

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			add_action( 'init', array( $this, 'register_post_type' ) );

			if ( is_admin() ) {
				add_action( 'admin_menu', array( $this, 'add_templates_page' ), 20 );
				add_action( 'add_meta_boxes_' . $this->slug(), array( $this, 'disable_metaboxes' ), 9999 );
				add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );
			}

			add_action( 'template_include', array( $this, 'set_editor_template' ), 9999 );
			add_action( 'soft-template-core/blank-page/before-content', array( $this, 'template_before' ) );
			add_action( 'soft-template-core/blank-page/after-content', array( $this, 'template_after' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_template_styles' ) );

			add_filter( 'views_edit-' . $this->post_type, array( $this, 'print_type_tabs' ) );

			add_action( 'admin_action_softtemplate_create_new_template', array( $this, 'create_template' ) );

			add_filter( 'manage_' . $this->slug() . '_posts_columns', array( $this, 'set_post_columns' ) );
			add_action( 'manage_' . $this->slug() . '_posts_custom_column', array( $this, 'post_columns' ), 10, 2 );

		}

		/**
		 * Set required post columns
		 *
		 * @param [type] $columns [description]
		 */
		public function set_post_columns( $columns ) {

			unset( $columns['taxonomy-' . $this->type_tax ] );
			unset( $columns['date'] );

			$columns['type']       = __( 'Type', 'soft-template-core' );
			$columns['conditions'] = __( 'Active Conditions', 'soft-template-core' );
			$columns['date']       = __( 'Date', 'soft-template-core' );

			$this->set_conditions();

			return $columns;

		}

		public function set_conditions() {

			$all_conditions = soft_template_core()->conditions->get_site_conditions();
			$to_store       = $all_conditions;

			foreach ( $all_conditions as $type => $type_conditions ) {

				$entire_found = false;
				$found        = array();

				foreach ( $type_conditions as $post_id => $post_condition ) {

					if ( ! isset( $post_condition['main'] ) ) {
						continue;
					}

					if ( 'entire' === $post_condition['main'] && false === $entire_found ) {
						$entire_found = $post_id;
					} elseif ( 'entire' === $post_condition['main'] && $entire_found ) {
						unset( $to_store[ $type ][ $entire_found ] );
						$entire_found = $post_id;
					}

					if ( 'entire' !== $post_condition['main'] ) {

						$verbosed = soft_template_core()->conditions->post_conditions_verbose( $post_id );
						

						if ( ! in_array( $verbosed, $found ) ) {
							$found[] = $verbosed;
						} else {
							unset( $to_store[ $type ][ $post_id ] );
						}

					}
				}

			}

			$this->conditions = $to_store;

		}

		/**
		 * Manage post columns content
		 *
		 * @return [type] [description]
		 */
		public function post_columns( $column, $post_id ) {

			$structure = soft_template_core()->structures->get_post_structure( $post_id );

			switch ( $column ) {

				case 'type':

					if ( $structure ) {

						$link = add_query_arg( array(
							$this->type_tax => $structure->get_id(),
						) );

						printf( '<a href="%1$s">%2$s</a>', $link, $structure->get_single_label() );

					}

					break;

				case 'conditions':

					echo '<div class="softtemplate-conditions">';

					printf(
						'<div class="softtemplate-conditions-list">%1$s</div>',
						soft_template_core()->conditions->post_conditions_verbose( $post_id )
					);

					//var_dump( $this->conditions[ $structure->get_id() ] );

					if ( $structure && isset( $this->conditions[ $structure->get_id() ] ) ) {

						if ( ! empty( $this->conditions[ $structure->get_id() ][ $post_id ] ) ) {
							printf(
								'<div class="softtemplate-conditions-active"><span class="dashicons dashicons-yes"></span>%1$s</div>',
								__( 'Active', 'soft-template-core' )
							);
						}
					}

					echo '</div>';

					break;

			}

		}

		/**
		 * Create new template
		 *
		 * @return [type] [description]
		 */
		public function create_template() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_die(
					esc_html__( 'You don\'t have permissions to do this', 'soft-template-core' ),
					esc_html__( 'Error', 'soft-template-core' )
				);
			}

			$type = isset( $_REQUEST['template_type'] ) ? sanitize_key( $_REQUEST['template_type'] ) : false;

			if ( ! $type || ! array_key_exists( $type, soft_template_core()->templates_manager->get_library_types() ) ) {
				wp_die(
					esc_html__( 'Incorrect template type. Please try again.', 'soft-template-core' ),
					esc_html__( 'Error', 'soft-template-core' )
				);
			}

			$documents = Elementor\Plugin::instance()->documents;
			$doc_type  = $documents->get_document_type( $type );

			if ( ! $doc_type ) {
				wp_die(
					esc_html__( 'Incorrect template type. Please try again.', 'soft-template-core' ),
					esc_html__( 'Error', 'soft-template-core' )
				);
			}

			$post_data = array(
				'post_type'  => $this->slug(),
				'meta_input' => array(
					'_elementor_edit_mode' => 'builder',
				),
				'tax_input'  => array(
					$this->type_tax => $type,
				),
				'meta_input' => array(
					$doc_type::TYPE_META_KEY   => $type,
					'_elementor_page_settings' => array(
						'softtemplate_conditions' => array(),
					),
				),
			);

			$title = isset( $_REQUEST['template_name'] ) ? esc_html( $_REQUEST['template_name'] ) : '';

			if ( $title ) {
				$post_data['post_title'] = $title;
			}

			$template_id = wp_insert_post( $post_data );

			if ( ! $template_id ) {
				wp_die(
					esc_html__( 'Can\'t create template. Please try again', 'soft-template-core' ),
					esc_html__( 'Error', 'soft-template-core' )
				);
			}

			if ( version_compare( ELEMENTOR_VERSION, '2.6.0', '<' ) ) {
				$redirect = Elementor\Utils::get_edit_link( $template_id );
			} else {
				$redirect = Elementor\Plugin::$instance->documents->get( $template_id )->get_edit_url();
			}

			wp_redirect( $redirect );
			die();

		}

		/**
		 * Enqueue template related styles from Elementor
		 *
		 * @return void
		 */
		public function enqueue_template_styles() {

			$parts = array(
				'header',
			);

			foreach ( $parts as $part ) {

				$template = false;

				if ( $template ) {
					$css = new \Elementor\Post_CSS_File( $template );
					$css->enqueue();
				}

			}
		}

		/**
		 * Templates post type slug
		 *
		 * @return string
		 */
		public function slug() {
			return $this->post_type;
		}

		/**
		 * Disable metaboxes from Softtemplate Templates
		 *
		 * @return void
		 */
		public function disable_metaboxes() {
			global $wp_meta_boxes;
			unset( $wp_meta_boxes[ $this->slug() ]['side']['core']['pageparentdiv'] );
		}

		/**
		 * Add opening wrapper if defined in manifes
		 *
		 * @return void
		 */
		public function template_before() {

			$editor = soft_template_core()->config->get( 'editor' );

			if ( isset( $editor['template_before'] ) ) {
				echo $editor['template_before'];
			}

		}

		/**
		 * Add closing wrapper if defined in manifes
		 *
		 * @return void
		 */
		public function template_after() {

			$editor = soft_template_core()->config->get( 'editor' );

			if ( isset( $editor['template_after'] ) ) {
				echo $editor['template_after'];
			}

		}

		/**
		 * Register templates post type
		 *
		 * @return void
		 */
		public function register_post_type() {

			$args = array(
				'labels' => array(
					'name'               => esc_html__( 'Template Parts', 'soft-template-core' ),
					'singular_name'      => esc_html__( 'Template', 'soft-template-core' ),
					'add_new'            => esc_html__( 'Add New', 'soft-template-core' ),
					'add_new_item'       => esc_html__( 'Add New Template', 'soft-template-core' ),
					'edit_item'          => esc_html__( 'Edit Template', 'soft-template-core' ),
					'new_item'           => esc_html__( 'Add New Template', 'soft-template-core' ),
					'view_item'          => esc_html__( 'View Template', 'soft-template-core' ),
					'search_items'       => esc_html__( 'Search Template', 'soft-template-core' ),
					'not_found'          => esc_html__( 'No Templates Found', 'soft-template-core' ),
					'not_found_in_trash' => esc_html__( 'No Templates Found In Trash', 'soft-template-core' ),
					'menu_name'          => esc_html__( 'Template Library', 'soft-template-core' ),
				),
				'public'              => true,
				'hierarchical'        => false,
				'show_ui'             => true,
				'show_in_rest' 		  => true,
				'show_in_menu'        => false,
				'show_in_nav_menus'   => false,
				'can_export'          => true,
				'exclude_from_search' => true,
				'capability_type'     => 'post',
				'rewrite'             => false,
				'supports'            => array( 'title', 'editor', 'thumbnail', 'author', 'elementor' ),
			);

			register_post_type(
				$this->slug(),
				apply_filters( 'soft-template-core/templates/post-type/args', $args )
			);

			$tax_args = array(
				'hierarchical'      => false,
				'show_in_rest' 		=> true,
				'show_ui'           => true,
				'show_in_nav_menus' => false,
				'show_admin_column' => true,
				'query_var'         => is_admin(),
				'rewrite'           => false,
				'public'            => false,
				'label'             => __( 'Type', 'soft-template-core' ),
			);

			register_taxonomy(
				$this->type_tax,
				$this->slug(),
				apply_filters( 'soft-template-core/templates/type-tax/args', $tax_args )
			);

		}

		/**
		 * Menu page
		 */
		public function add_templates_page() {

			add_submenu_page(
				soft_template_core()->dashboard->slug(),
				esc_html__( 'Template Library', 'soft-template-core' ),
				esc_html__( 'Template Library', 'soft-template-core' ),
				'edit_pages',
				'edit.php?post_type=' . $this->slug()
			);

		}

		/**
		 * Print library types tabs
		 *
		 * @return [type] [description]
		 */
		public function print_type_tabs( $edit_links ) {

			$tabs = soft_template_core()->templates_manager->get_library_types();
			$tabs = array_merge(
				array(
					'all' => esc_html__( 'All', 'soft-template-core' ),
				),
				$tabs
			);

			$active_tab = isset( $_GET[ $this->type_tax ] ) ? sanitize_key( $_GET[ $this->type_tax ] ) : 'all';
			$page_link  = admin_url( 'edit.php?post_type=' . $this->slug() );

			if ( ! array_key_exists( $active_tab, $tabs ) ) {
				$active_tab = 'all';
			}

			include soft_template_core()->get_template( 'template-types-tabs.php' );

			return $edit_links;
		}

		/**
		 * Editor templates.
		 *
		 * @param  string $template Current template name.
		 * @return string
		 */
		public function set_editor_template( $template ) {

			$found = false;

			if ( is_singular( $this->slug() ) ) {
				$found    = true;
				$template = soft_template_core()->plugin_path( 'templates/blank.php' );
			}

			if ( $found ) {
				do_action( 'soft-template-core/post-type/editor-template/found' );
			}

			return $template;

		}

		/**
		 * Add an export link to the template library action links table list.
		 *
		 * @param array $actions
		 * @param object $post
		 *
		 * @return array
		 */
		public function post_row_actions( $actions, $post ) {

			if ( $this->post_type === $post->post_type ) {

				$actions['export-template'] = sprintf(
					'<a href="%1$s">%2$s</a>',
					$this->get_export_link( $post->ID ),
					esc_html__( 'Export Template', 'soft-template-core' )
				);

			}

			return $actions;
		}

		/**
		 * Get template export link.
		 *
		 * @param int $template_id The template ID.
		 *
		 * @return string
		 */
		private function get_export_link( $template_id ) {
			return add_query_arg(
				array(
					'action'         => 'elementor_library_direct_actions',
					'library_action' => 'export_template',
					'source'         => 'local',
					'_nonce'         => wp_create_nonce( 'elementor_ajax' ),
					'template_id'    => $template_id,
				),
				esc_url( admin_url( 'admin-ajax.php' ) )
			);
		}

	}

}
