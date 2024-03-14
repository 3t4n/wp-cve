<?php

use Elementor\Controls_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Xpro_Saved_Templates_Settings extends Elementor\Core\Base\Document {

	public function get_name() {
		return 'xpro_content';
	}

	public static function get_type() {
		return 'xpro_content';
	}

	public static function get_title() {
		return __( 'Template', 'xpro-elementor-addons' );
	}

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['cpt']           = array( 'xpro_content', 'xpro-themer' );
		$properties['register_type'] = true;
		$properties['support_kit']   = true;

		return $properties;
	}

	public static function get_preview_as_default() {
		return '';
	}

	public static function get_preview_as_options() {
		return array();
	}

	protected function register_controls() {

		// Default Document Settings
		parent::register_controls();

		// Get Available Post Types
		$post_types = $this->get_custom_types_of( 'post', false );

		// Get Available Taxonomies
		$post_taxonomies = $this->get_custom_types_of( 'tax', false );

		$this->start_controls_section(
			'section_xpro_preview_settings',
			array(
				'label' => __( 'Preview Settings', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			)
		);

		$default_archives = array(
			'archive/posts'            => __( 'Posts Archive', 'xpro-elementor-addons' ),
			'product_archive/products' => __( 'Products Archive', 'xpro-elementor-addons' ),
			'archive/author'           => __( 'Author Archive', 'xpro-elementor-addons' ),
			'archive/date'             => __( 'Date Archive', 'xpro-elementor-addons' ),
			'archive/search'           => __( 'Search Results', 'xpro-elementor-addons' )
		);

		$taxonomy_archives = $post_taxonomies;

		$query = '';

		$this->add_control(
			'preview_source',
			array(
				'label'   => __( 'Preview Source', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $query,
				'groups'  => array(
					''         => __( 'Select', 'xpro-elementor-addons' ),
					'single'   => array(
						'label'   => __( 'Singular', 'xpro-elementor-addons' ),
						'options' => $post_types,
					),
					'archive'  => array(
						'label'   => __( 'Archives', 'xpro-elementor-addons' ),
						'options' => $default_archives,
					),
					'taxonomy' => array(
						'label'   => __( 'Taxonomies', 'xpro-elementor-addons' ),
						'options' => $taxonomy_archives,
					),
				),
			)
		);

		$wp_users = $this->get_users();
		reset( $wp_users );
		$first_user_id = key( $wp_users );

		$this->add_control(
			'preview_archive_author',
			array(
				'label'     => __( 'Select Author', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT2,
				'options'   => $this->get_users(),
				'default'   => $first_user_id,
				'condition' => array(
					'preview_source' => 'archive/author',
				),
			)
		);

		$this->add_control(
			'preview_archive_search',
			array(
				'label'     => __( 'Search Keyword', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'a',
				'condition' => array(
					'preview_source' => 'archive/search',
				),
			)
		);

		// Posts
		foreach ( $post_types as $slug => $title ) {
			$latest_post = get_posts( 'post_type=' . $slug . '&numberposts=1' );

			$this->add_control(
				'preview_single_' . $slug,
				array(
					'label'       => 'Select ' . $title,
					'type'        => Controls_Manager::SELECT2,
					'label_block' => true,
					'default'     => ! empty( $latest_post ) ? $latest_post[0]->ID : '',
					'options'     => $this->get_posts_by_post_type( $slug ),
					'condition'   => array(
						'preview_source' => $slug,
					),
				)
			);
		}

		// Taxonomies
		foreach ( $post_taxonomies as $slug => $title ) {
			if ( 'category' === $slug || 'post_tag' === $slug ) {
				$title = 'Post ' . $title;
			}

			$terms = get_terms( $slug, 'orderby=date&hide_empty=0&number=1' );

			$this->add_control(
				'preview_archive_' . $slug,
				array(
					'label'       => 'Select ' . $title,
					'type'        => Controls_Manager::SELECT2,
					'label_block' => true,
					'default'     => ! empty( $terms ) ? $terms[0]->term_id : '',
					'options'     => $this->get_terms_by_taxonomy( $slug ),
					'condition'   => array(
						'preview_source' => $slug,
					),
				)
			);
		}

		$this->add_control(
			'submit_preview_changes',
			array(
				'type'      => Controls_Manager::RAW_HTML,
				'raw'       => '<div class="elementor-update-preview editor-xpro-preview-update"><span>Update Preview</span><button class="elementor-button elementor-button-success"><i class="eicon eicon-spinner"></i>Apply</button>',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
	}

	public function print_content() {
		$plugin = Plugin::instance();
		if ( $plugin->preview->is_preview_mode( $this->get_main_id() ) ) {
			echo '' . $plugin->preview->builder_wrapper( '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo '' . $this->get_content(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	public function get_content( $with_css = false ) {
		$this->switch_to_preview_query();

		$content = parent::get_content( $with_css );

		Elementor\Plugin::instance()->db->restore_current_query();

		return $content;
	}

	public function switch_to_preview_query() {
		if ( 'xpro_content' === get_post_type( get_the_ID() ) ) {
			$document = Elementor\Plugin::instance()->documents->get_doc_or_auto_save( get_the_ID() );
			Elementor\Plugin::instance()->db->switch_to_query( $document->get_document_query_args() );
		}
	}

	public function get_document_query_args() {
		$settings = $this->get_settings();
		$source   = $settings['preview_source'];
		$args     = false;

		// Default Archives
		switch ( $source ) {
			case 'archive/posts':
				$args = array( 'post_type' => 'post' );
				break;
			case 'product_archive/products':
				$args = array( 'post_type' => 'product' );
				break;
			case 'archive/author':
				$args = array( 'author' => $settings['preview_archive_author'] );
				break;

			case 'archive/search':
				$args = array( 's' => $settings['preview_archive_search'] );
				break;
		}

		// Taxonomy Archives
		foreach ( $this->get_custom_types_of( 'tax', false ) as $slug => $title ) {
			if ( $slug === $source ) {
				$args = $this->get_tax_query_args( $slug, $settings[ 'preview_archive_' . $slug ] );
			}
		}

		// Singular Posts
		foreach ( $this->get_custom_types_of( 'post', false ) as $slug => $title ) {
			if ( $slug === $source ) {
				// Get Post
				$post = get_posts(
					array(
						'post_type'        => $source,
						'numberposts'      => 1,
						'orderby'          => 'date',
						'order'            => 'DESC',
						'suppress_filters' => false,
					)
				);

				$args = array( 'post_type' => $source );

				$post_id = $settings[ 'preview_single_' . $slug ];

				if ( ! empty( $post ) && '' === $post_id ) {
					$args['p'] = $post[0]->ID;
				} else {
					$args['p'] = $post_id;
				}
			}
		}

		// Default
		if ( false === $args ) {
			// Get Post
			$post = get_posts(
				array(
					'post_type'        => 'post',
					'numberposts'      => 1,
					'orderby'          => 'date',
					'order'            => 'DESC',
					'suppress_filters' => false,
				)
			);

			$args = array( 'post_type' => 'post' );

			// Last Post for Single Pages
			if ( ! empty( $post ) ) {
				$args['p'] = $post[0]->ID;
			}
		}

		return $args;
	}

	public function get_tax_query_args( $tax, $terms ) {
		$terms = empty( $terms ) ? array( 'all' ) : $terms;

		$args = array(
			'tax_query' => array(
				array(
					'taxonomy' => $tax,
					'terms'    => $terms,
					'field'    => 'id',
				),
			)
		);

		return $args;
	}

	public function get_elements_raw_data( $data = null, $with_html_content = false ) {

		$this->switch_to_preview_query();

		$editor_data = parent::get_elements_raw_data( $data, $with_html_content );

		Elementor\Plugin::instance()->db->restore_current_query();

		return $editor_data;
	}

	public function render_element( $data ) {

		$this->switch_to_preview_query();

		$render_html = parent::render_element( $data );

		Elementor\Plugin::instance()->db->restore_current_query();

		return $render_html;
	}

	public function get_container_attributes() {
		$attributes = parent::get_container_attributes();

		if ( is_singular() ) {
			$post_classes         = get_post_class( '', get_the_ID() );
			$attributes['class'] .= ' ' . implode( ' ', $post_classes );
		}

		return $attributes;
	}

	/**
	 ** Get Available Custom Post Types or Taxonomies
	 */
	public static function get_custom_types_of( $query, $exclude_defaults = true ) {
		// Taxonomies
		if ( 'tax' === $query ) {
			$custom_types = get_taxonomies( array( 'show_in_nav_menus' => true ), 'objects' );

			// Post Types
		} else {
			$custom_types = get_post_types( array( 'show_in_nav_menus' => true ), 'objects' );
		}

		$custom_type_list = array();

		foreach ( $custom_types as $key => $value ) {
			if ( $exclude_defaults ) {
				if ( $key != 'post' && $key != 'page' && $key != 'category' && $key != 'post_tag' ) {
					$custom_type_list[ $key ] = $value->label;
				}
			} else {
				$custom_type_list[ $key ] = $value->label;
			}
		}

		return $custom_type_list;
	}

	/**
	 ** Get Library Template Slug
	 */
	public static function get_template_slug( $data, $page, $post_id = '' ) {
		if ( is_null( $data ) ) {
			return;
		}

		$template = null;

		// Find a Custom Condition
		foreach ( $data as $id => $conditions ) {

			if ( in_array( $page . '/' . $post_id, $conditions ) ) {
				$template = $id;
			} elseif ( in_array( $page . '/all', $conditions ) ) {
				$template = $id;
			} elseif ( in_array( $page, $conditions ) ) {
				$template = $id;
			}
		}

		// If a Custom NOT Found, use Global
		if ( is_null( $template ) ) {
			foreach ( $data as $id => $conditions ) {
				if ( in_array( 'global', $conditions ) ) {
					$template = $id;
				}
			}
		}

		// tmp remove after 2 months
		$templates_loop = new \WP_Query(
			array(
				'post_type'      => 'xpro_content',
				'name'           => $template,
				'posts_per_page' => 1,
			)
		);

		if ( ! $templates_loop->have_posts() ) {
			return null;
		} else {
			return $template;
		}
	}

	/**
	 ** Get All Users
	 */
	public static function get_users() {
		$users = array();

		if ( is_admin() ) {
			foreach ( get_users() as $key => $user ) {
				$users[ $user->data->ID ] = $user->data->user_nicename;
			}
		}

		return $users;
	}

	/**
	 ** Get Posts of Post Type
	 */
	public static function get_posts_by_post_type( $slug ) {
		$posts = array();

		if ( is_admin() ) {
			$query = get_posts(
				array(
					'post_type'      => $slug,
					'posts_per_page' => - 1,
				)
			);

			foreach ( $query as $post ) {
				$posts[ $post->ID ] = $post->post_title;
			}
		}

		return $posts;
	}

	/**
	 ** Get Terms of Taxonomy
	 */
	public static function get_terms_by_taxonomy( $slug ) {

		if ( ( 'product_cat' === $slug || 'product_tag' === $slug ) && ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$query = get_terms(
			$slug,
			array(
				'hide_empty'     => false,
				'posts_per_page' => - 1,
			)
		);

		$taxonomies = array();

		foreach ( $query as $tax ) {
			$taxonomies[ $tax->term_id ] = $tax->name;
		}

		return $taxonomies;
	}

}
