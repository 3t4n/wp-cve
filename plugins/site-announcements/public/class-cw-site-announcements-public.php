<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://codewrangler.io
 * @since      1.0.0
 *
 * @package    CW_Site_Announcements
 * @subpackage CW_Site_Announcements/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CW_Site_Announcements
 * @subpackage CW_Site_Announcements/public
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */
class CW_Site_Announcements_Public {

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
		$this->get_announcement();

	}

	public function register_post_types() {

		register_post_type(
			'cw-announcement',
			array(
				'labels'             => array(
					'name'               => _x( 'Announcements', 'post type general name', 'cw-announcements' ),
					'singular_name'      => _x( 'Announcement', 'post type singular name', 'cw-announcements' ),
					'menu_name'          => _x( 'Announcements', 'admin menu', 'cw-announcements' ),
					'name_admin_bar'     => _x( 'Announcement', 'add new on admin bar', 'cw-announcements' ),
					'add_new'            => _x( 'Add New', 'cw-announcement', 'cw-announcements' ),
					'add_new_item'       => __( 'Add New Announcement', 'cw-announcements' ),
					'new_item'           => __( 'New Announcement', 'cw-announcements' ),
					'edit_item'          => __( 'Edit Announcement', 'cw-announcements' ),
					'view_item'          => __( 'View Announcement', 'cw-announcements' ),
					'all_items'          => __( 'All Announcements', 'cw-announcements' ),
					'search_items'       => __( 'Search Announcements', 'cw-announcements' ),
					'parent_item_colon'  => __( 'Parent Announcements:', 'cw-announcements' ),
					'not_found'          => __( 'No Announcements Found.', 'cw-announcements' ),
					'not_found_in_trash' => __( 'No Announcements Found in Trash.', 'cw-announcements' )
					),
				'description'        => __( 'Description.', 'cw-announcements' ),
				'menu_icon'          => 'dashicons-megaphone',
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				//'rewrite'            => array( 'slug' => 'announcement' ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'supports'           => array(
						'title',
						'editor',
						'author',
						'comments',
					),
			)
		);

	}

	public function get_announcement() {
		$args = array(
			'post_type' => 'cw-announcement',
			'posts_per_page' => 1,
			);

		$a = get_posts( $args );

		if( $a ) {
			$a = $a[0];
			$a = new CW_Announcement( $a->ID );
		} else {
			$a = false;
		}

		return $a;
	}

	public function output_announcement() {

		$a = $this->get_announcement();

		$output = '';

		$container_class = is_admin_bar_showing() ? 'cw-announcement cw-admin-bar-showing animated' : 'cw-announcement animated';
		$modal_class = is_admin_bar_showing() ? 'cw-announcement-modal animated cw-admin-bar-showing' : 'cw-announcement-modal animated';

		if( $a ):
			$announcement = $a;

			$user_hidden = isset( $_COOKIE['cw_hide_announcement_' . $a->ID ] ) ? true : false;

			if( !$user_hidden ) {
				if( '' == $announcement->content ) {
					$name = '' == $announcement->url ? '<h4 style="color: ' . $announcement->text_color . '">' . $announcement->name . '</h4>' : '<a style="color: ' . $announcement->text_color . '" href="' . esc_url( $announcement->url ) . '"><h4 style="color: ' . $announcement->text_color . '" class="cw-announcement-title">' . $announcement->name . '</h4></a>';
				} else {
					$name = '' == $announcement->url ? '<a style="color: ' . $announcement->text_color . '" class="cw-launch-modal" href=""><h4 class="cw-announcement-title" style="color: ' . $announcement->text_color . '">' . $announcement->name . '</h4></a>' : '<a style="color: ' . $announcement->text_color . '" href="' . esc_url( $announcement->url ) . '"><h4 style="color: ' . $announcement->text_color . '" class="cw-announcement-title">' . $announcement->name . '</h4></a>';
				}

				$output .= '<div style="background-color: ' . $announcement->background_color . '" data-announcement-id="' . $a->ID . '" class="' . $container_class . '">';
				$output .= '<div class="cw-inner">';
				$output .=  $name;
				$output .= '</div>';
				if( $announcement->closable ) {
					$output .= '<span style="color: ' . $announcement->text_color . '" class="cw-close-button dashicons dashicons-no-alt"></span>';
				}
				$output .= '</div>';
				$output .= '<div class="' . $modal_class . '">';
				$output .= '<span class="cw-modal-close dashicons dashicons-no"></span>';
				$output .= '<div class="cw-modal-inner">';
				$output .= $announcement->content;
				$output .= '</div>';
				$output .= '</div>';
			}

		endif;

		echo $output;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cw-site-announcements-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( $this->plugin_name .'animate', plugin_dir_url( __FILE__ ) . 'css/animate.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name . 'js-cookie', plugin_dir_url( __FILE__ ) . 'js/js.cookie.js', array(), $this->version, false );

		$a = $this->get_announcement();

		if( $a ) {
			$cw_data = array(
				'admin_bar' => is_admin_bar_showing(),
				'user_hidden' => isset( $_COOKIE['cw_hide_announcement_' . $a->ID ] ) ? true : false,
				'closable' => $a->closable,
				'closable_duration' => $a->closable_duration,
			);
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cw-site-announcements-public.js', array( 'jquery', $this->plugin_name . 'js-cookie' ), $this->version, false );
			wp_localize_script( $this->plugin_name, 'CW', $cw_data );
		}

	}

}
