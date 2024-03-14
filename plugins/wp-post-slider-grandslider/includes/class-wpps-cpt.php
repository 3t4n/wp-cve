<?php
/**
 * Define the custom post type functionality.
 *
 * Loads and defines the custom post type for this plugin
 * so that it is ready for admin menu under a different post type.
 *
 * @link       https://forhad.net/
 * @since      1.9.0
 *
 * @package    WP_Post_Slider_Grandslider
 * @subpackage WP_Post_Slider_Grandslider/includes
 */

/**
 * Define the custom post type functionality.
 */
class WP_Post_Slider_Grandslider_CPT {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.9.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.9.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.9.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Custom Post Type of the Plugin.
	 *
	 * @since    1.9.0
	 */
	public function wppsgs_post_type() {

		$capability = apply_filters( 'wppsgs_slider_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		$labels     = apply_filters(
			'wppsgs_slider_post_type_labels',
			array(
				'name'               => esc_html_x( 'Slider Groups', 'wp-post-slider-grandslider' ),
				'singular_name'      => esc_html_x( 'Sliders', 'wp-post-slider-grandslider' ),
				'add_new'            => esc_html__( '+ Add a Slider', 'wp-post-slider-grandslider' ),
				'add_new_item'       => esc_html__( 'Add New Slider Group', 'wp-post-slider-grandslider' ),
				'edit_item'          => esc_html__( 'Edit Slider Group', 'wp-post-slider-grandslider' ),
				'new_item'           => esc_html__( 'New Slider Group', 'wp-post-slider-grandslider' ),
				'view_item'          => esc_html__( 'View Slider Group', 'wp-post-slider-grandslider' ),
				'search_items'       => esc_html__( 'Search Sliders', 'wp-post-slider-grandslider' ),
				'not_found'          => esc_html__( 'No Slider Group found.', 'wp-post-slider-grandslider' ),
				'not_found_in_trash' => esc_html__( 'No Slider Group found in trash.', 'wp-post-slider-grandslider' ),
				'parent_item_colon'  => esc_html__( 'Parent Item:', 'wp-post-slider-grandslider' ),
				'menu_name'          => esc_html__( 'Slider Settings', 'wp-post-slider-grandslider' ),
				'all_items'          => esc_html__( 'Slider Groups', 'wp-post-slider-grandslider' ),
			)
		);

		$args = apply_filters(
			'wppsgs_slider_post_type_args',
			array(
				'labels'              => $labels,
				'public'              => false,
				'hierarchical'        => false,
				'exclude_from_search' => true,
				'show_ui'             => $show_ui,
				'show_in_admin_bar'   => false,
				'menu_position'       => apply_filters( 'wppsgs_slider_menu_position', 55 ),
				'menu_icon'           => 'data:image/svg+xml;base64,' . base64_encode( '<svg style="fill:rgba(240,246,252,.6);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M222.7 32.15C227.7 49.08 218.1 66.9 201.1 71.94C121.8 95.55 64 169.1 64 255.1C64 362 149.1 447.1 256 447.1C362 447.1 448 362 448 255.1C448 169.1 390.2 95.55 310.9 71.94C293.9 66.9 284.3 49.08 289.3 32.15C294.4 15.21 312.2 5.562 329.1 10.6C434.9 42.07 512 139.1 512 255.1C512 397.4 397.4 511.1 256 511.1C114.6 511.1 0 397.4 0 255.1C0 139.1 77.15 42.07 182.9 10.6C199.8 5.562 217.6 15.21 222.7 32.15V32.15z"/></svg>' ),
				'rewrite'             => false,
				'query_var'           => false,
				'imported'            => true,
				'supports'            => array( 'title' ),
			)
		);
		register_post_type( 'wppsgs_slider', $args );

		/**
		 * Adding Post type for Testimonial.
		 */
		$labels = apply_filters(
			'wppsgs_tmonial_post_type_labels',
			array(
				'name'               => esc_html_x( 'Testimonials', 'wp-post-slider-grandslider' ),
				'singular_name'      => esc_html_x( 'Testimonials', 'wp-post-slider-grandslider' ),
				'add_new'            => esc_html__( '+ Add Testimonial', 'wp-post-slider-grandslider' ),
				'add_new_item'       => esc_html__( '+ Add Testimonial', 'wp-post-slider-grandslider' ),
				'edit_item'          => esc_html__( 'Edit Testimonial Group', 'wp-post-slider-grandslider' ),
				'new_item'           => esc_html__( 'New Testimonial', 'wp-post-slider-grandslider' ),
				'view_item'          => esc_html__( 'View Testimonial Group', 'wp-post-slider-grandslider' ),
				'search_items'       => esc_html__( 'Search Testimonials', 'wp-post-slider-grandslider' ),
				'not_found'          => esc_html__( 'No Testimonial Group found.', 'wp-post-slider-grandslider' ),
				'not_found_in_trash' => esc_html__( 'No Testimonial Group found in trash.', 'wp-post-slider-grandslider' ),
				'parent_item_colon'  => esc_html__( 'Parent Item:', 'wp-post-slider-grandslider' ),
				'menu_name'          => esc_html__( 'Testimonial Settings', 'wp-post-slider-grandslider' ),
				'all_items'          => esc_html__( 'Testimonials', 'wp-post-slider-grandslider' ),
			)
		);

		$args = apply_filters(
			'wppsgs_tmonial_post_type_args',
			array(
				'labels'              => $labels,
				'exclude_from_search' => true,
				'show_ui'             => true,
				'show_in_admin_bar'   => false,
				'rewrite'             => false,
				'query_var'           => false,
				'supports'            => array( 'title' ),
				'show_in_menu'        => 'edit.php?post_type=wppsgs_slider',
			)
		);
		register_post_type( 'wppsgs_tmonial', $args );

	}

	/**
	 * Change Sliders updated messages.
	 *
	 * @param string $messages The Update messages.
	 * @return statement
	 */
	public function wppsgs_updated_messages( $messages ) {
		global $post, $post_ID;
		$messages['wppsgs_slider'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => sprintf( __( 'Sliders updated.', 'wp-post-slider-grandslider' ) ),
			2  => '',
			3  => '',
			4  => __( ' updated.', 'wp-post-slider-grandslider' ),
			5  => isset( $_GET['revision'] ) ? sprintf( esc_html( 'Sliders restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => sprintf( __( 'Sliders published.', 'wp-post-slider-grandslider' ) ),
			7  => __( 'Sliders saved.', 'wp-post-slider-grandslider' ),
			8  => sprintf( __( 'Sliders submitted.', 'wp-post-slider-grandslider' ) ),
			9  => sprintf( wp_kses_post( 'Sliders scheduled for: <strong>%1$s</strong>.', 'wp-post-slider-grandslider' ), date_i18n( __( 'M j, Y @ G:i', 'wp-post-slider-grandslider' ), strtotime( $post->post_date ) ) ),
			10 => sprintf( __( 'Sliders draft updated.', 'wp-post-slider-grandslider' ) ),
		);
		return $messages;
	}

	/**
	 * Add new custom columns.
	 *
	 * @param [type] $columns The columns.
	 * @return statement
	 */
	public function wppsgs_admin_column( $columns ) {
		return array(
			'cb'        => '<input type="checkbox" />',
			'title'     => __( 'Name', 'wp-post-slider-grandslider' ),
			'shortcode' => __( 'Shortcode', 'wp-post-slider-grandslider' ),
			'date'      => __( 'Date', 'wp-post-slider-grandslider' ),
		);
	}

	/**
	 * Display admin columns content.
	 *
	 * @param mix    $column The columns.
	 * @param string $post_id The post ID.
	 * @return void
	 */
	public function wppsgs_admin_field( $column, $post_id ) {
		switch ( $column ) {
			case 'shortcode':
				echo '<input title="Copy the Shortcode" style="width:180px;padding:2px 12px;color:#e91e63;text-align:center;cursor:copy;" type="text" onClick="this.select();" readonly="readonly" value="[wppsgs_Slider id=&quot;' . esc_attr( $post_id ) . '&quot;]"/>';
				break;
			default:
				echo '';
		}
	}

}
