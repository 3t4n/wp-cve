<?php

namespace Omnipress;

defined( 'ABSPATH' ) || exit;

/**
 * Register template's type for blocks supports.
 *
 * @since 1.2.0
 */
class BlockTemplates {




	/**
	 * @var string
	 *
	 * Set post type params
	 */
	private $prefix = 'op';
	public string $type;
	public string $name;
	public string $singular_name;
	public string $slug;
	private string $plugin_slug = 'omnipress';
	private string $menu        = 'omnipress-templates';


	/**
	 * Type constructor.
	 *
	 * When register template types.
	 */
	public function __construct( string $singular, string $plural, string $slug ) {

		$this->singular_name = $singular;
		$this->name          = $plural;
		$this->slug          = $this->prefix . '-' . strtolower( $slug );
		$this->type          = $this->slug;

		/**
		 * Register template type just before register omnipress blocks.
		 */
		add_action( 'omnipress_before_blocks_register', array( $this, 'register_template_types' ) );
	}


	/**
	 * Register template types.
	 *
	 * @return void
	 */
	public function register_template_types() {
		$labels = array(
			'name'               => __( $this->name, $this->plugin_slug ),
			'singular_name'      => __( $this->singular_name, $this->plugin_slug ),
			'add_new'            => __( 'Add New', $this->plugin_slug ),
			'add_new_item'       => __( 'Add New ' . $this->singular_name, $this->plugin_slug ),
			'edit_item'          => __( 'Edit ' . $this->singular_name, $this->plugin_slug ),
			'new_item'           => __( 'New ' . $this->singular_name, $this->plugin_slug ),
			'all_items'          => __( 'All ' . $this->name, $this->plugin_slug ),
			'view_item'          => __( 'View ' . $this->name, $this->plugin_slug ),
			'search_items'       => __( 'Search ' . $this->name, $this->plugin_slug ),
			'not_found'          => __( 'No ' . strtolower( $this->name ) . ' found', $this->plugin_slug ),
			'not_found_in_trash' => __( 'No ' . strtolower( $this->name ) . ' found in Trash', $this->plugin_slug ),
			'parent_item_colon'  => '',
			'menu_name'          => __( $this->name, $this->plugin_slug ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => $this->menu,
			'show_in_rest'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $this->slug ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => 8,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'author', 'excerpt', 'comments' ),
			'yarpp_support'      => true,
		);

		register_post_type( $this->type, $args );
	}
}
