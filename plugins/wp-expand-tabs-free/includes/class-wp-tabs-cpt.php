<?php
/**
 * Define the custom post type functionality.
 *
 * Loads and defines the custom post type for this plugin
 * so that it is ready for admin menu under a different post type.
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/includes
 */

/**
 * Define the custom post type functionality.
 */
class WP_Tabs_CPT {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
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
	 * @since    2.0.0
	 */
	public function sptpro_post_type() {
		if ( post_type_exists( 'sp_wp_tabs' ) ) {
			return;
		}
		$capability = apply_filters( 'sp_wp_tabs_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		$labels     = apply_filters(
			'sp_wp_tabs_post_type_labels',
			array(
				'name'               => esc_html_x( 'Tab Groups', 'wp-expand-tabs-free' ),
				'singular_name'      => esc_html_x( 'Tabs', 'wp-expand-tabs-free' ),
				'add_new'            => esc_html__( 'Add New', 'wp-expand-tabs-free' ),
				'add_new_item'       => esc_html__( 'Add New Tab Group', 'wp-expand-tabs-free' ),
				'edit_item'          => esc_html__( 'Edit Tab Group', 'wp-expand-tabs-free' ),
				'new_item'           => esc_html__( 'New Tab Group', 'wp-expand-tabs-free' ),
				'view_item'          => esc_html__( 'View Tab Group', 'wp-expand-tabs-free' ),
				'search_items'       => esc_html__( 'Search Tabs', 'wp-expand-tabs-free' ),
				'not_found'          => esc_html__( 'No Tab Group found.', 'wp-expand-tabs-free' ),
				'not_found_in_trash' => esc_html__( 'No Tab Group found in trash.', 'wp-expand-tabs-free' ),
				'parent_item_colon'  => esc_html__( 'Parent Item:', 'wp-expand-tabs-free' ),
				'menu_name'          => esc_html__( 'WP Tabs', 'wp-expand-tabs-free' ),
				'all_items'          => esc_html__( 'Tab Groups', 'wp-expand-tabs-free' ),
			)
		);

		$args = apply_filters(
			'sp_wp_tabs_post_type_args',
			array(
				'labels'              => $labels,
				'public'              => false,
				'hierarchical'        => false,
				'exclude_from_search' => true,
				'show_ui'             => $show_ui,
				'show_in_admin_bar'   => false,
				'menu_position'       => apply_filters( 'sp_wp_tabs_menu_position', 115 ),
				'menu_icon'           => 'data:image/svg+xml;base64,' . base64_encode(
					'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 65.8 55.4" style="enable-background:new 0 0 65.8 55.4;" xml:space="preserve">
					<style type="text/css">
						.st0{fill:#A0A5AA;}
					</style>
					<g>
						<g>
							<rect x="22.1" y="15.9" class="st0" width="37.2" height="7.7"/>
							<rect x="22.1" y="5.5" class="st0" width="7.7" height="11.7"/>
							<rect x="39.4" y="5.9" class="st0" width="7.7" height="11.3"/>
						</g>
						<g>
							<path class="st0" d="M65.8,55.4H0V0h65.8V55.4z M7.9,47.5h50V7.9h-50C7.9,7.9,7.9,47.5,7.9,47.5z"/>
						</g>
					</g>
					</svg>'
				),
				'rewrite'             => false,
				'query_var'           => false,
				'imported'            => true,
				'supports'            => array(
					'title',
				),
			)
		);
		register_post_type( 'sp_wp_tabs', $args );

	}

	/**
	 * Change Tabs updated messages.
	 *
	 * @param string $messages The Update messages.
	 * @return statement
	 */
	public function sptpro_updated_messages( $messages ) {
		global $post, $post_ID;
		$messages['sp_wp_tabs'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => sprintf( __( 'Tabs updated.', 'wp-expand-tabs-free' ) ),
			2  => '',
			3  => '',
			4  => __( ' updated.', 'wp-expand-tabs-free' ),
			5  => isset( $_GET['revision'] ) ? sprintf( esc_html( 'Tabs restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => sprintf( __( 'Tabs published.', 'wp-expand-tabs-free' ) ),
			7  => __( 'Tabs saved.', 'wp-expand-tabs-free' ),
			8  => sprintf( __( 'Tabs submitted.', 'wp-expand-tabs-free' ) ),
			9  => sprintf( wp_kses_post( 'Tabs scheduled for: <strong>%1$s</strong>.', 'wp-expand-tabs-free' ), date_i18n( __( 'M j, Y @ G:i', 'wp-expand-tabs-free' ), strtotime( $post->post_date ) ) ),
			10 => sprintf( __( 'Tabs draft updated.', 'wp-expand-tabs-free' ) ),
		);
		return $messages;
	}

	/**
	 * Add new custom columns.
	 *
	 * @param [type] $columns The columns.
	 * @return statement
	 */
	public function sptpro_admin_column( $columns ) {
		return array(
			'cb'        => '<input type="checkbox" />',
			'title'     => __( 'Name', 'wp-expand-tabs-free' ),
			'shortcode' => __( 'Shortcode', 'wp-expand-tabs-free' ),
			'date'      => __( 'Date', 'wp-expand-tabs-free' ),
		);
	}

	/**
	 * Display admin columns content.
	 *
	 * @param mix    $column The columns.
	 * @param string $post_id The post ID.
	 * @return void
	 */
	public function sptpro_admin_field( $column, $post_id ) {
		switch ( $column ) {
			case 'shortcode':
				echo '<div class="sp_tab-after-copy-text"><i class="fa fa-check-circle"></i>  Shortcode  Copied to Clipboard! </div><input title="copy the shortcode" style="width: 150px; padding: 6px; text-align: center; cursor: pointer;" type="text" onClick="this.select();" readonly="readonly" value="[wptabs id=&quot;' . esc_attr( $post_id ) . '&quot;]"/>';
				break;
			default:
				echo '';
		}
	}

}
