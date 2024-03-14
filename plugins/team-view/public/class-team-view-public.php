<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wenthemes.com
 * @since      1.0.0
 *
 * @package    Team_View
 * @subpackage Team_View/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Team_View
 * @subpackage Team_View/public
 * @author     WEN Themes <info@wenthemes.com>
 */
class Team_View_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'font-awesome', TEAM_VIEW_URL . '/lib/font-awesome/css/font-awesome.css', array(), '4.7.0' );
		wp_enqueue_style( $this->plugin_name, TEAM_VIEW_URL . '/public/css/team-view-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/team-view-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register custom post type.
	 *
	 * @since    1.0.0
	 */
	public function custom_post_types() {

		$labels = array(
			'name'               => esc_html_x( 'Team Members', 'post type general name', 'team-view' ),
			'singular_name'      => esc_html_x( 'Team Member', 'post type singular name', 'team-view' ),
			'menu_name'          => esc_html_x( 'Team Members', 'admin menu', 'team-view' ),
			'name_admin_bar'     => esc_html_x( 'Team Member', 'add new on admin bar', 'team-view' ),
			'add_new'            => esc_html__( 'Add New', 'team-view' ),
			'add_new_item'       => esc_html__( 'Add New Team Member', 'team-view' ),
			'new_item'           => esc_html__( 'New Team Member', 'team-view' ),
			'edit_item'          => esc_html__( 'Edit Team Member', 'team-view' ),
			'view_item'          => esc_html__( 'View Team Member', 'team-view' ),
			'all_items'          => esc_html__( 'All Team Members', 'team-view' ),
			'search_items'       => esc_html__( 'Search Team Members', 'team-view' ),
			'parent_item_colon'  => esc_html__( 'Parent Team Members:', 'team-view' ),
			'not_found'          => esc_html__( 'No team members found.', 'team-view' ),
			'not_found_in_trash' => esc_html__( 'No team members found in Trash.', 'team-view' ),
			);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_icon'          => 'dashicons-groups',
			'supports'           => array( 'title', 'thumbnail', 'page-attributes' ),
			);

		register_post_type( 'tv_member', $args );

	}


}
