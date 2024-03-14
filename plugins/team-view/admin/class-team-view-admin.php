<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wenthemes.com
 * @since      1.0.0
 *
 * @package    Team_View
 * @subpackage Team_View/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Team_View
 * @subpackage Team_View/admin
 * @author     WEN Themes <info@wenthemes.com>
 */
class Team_View_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles( $hook ) {

		$screen = get_current_screen();

		if ( ( 'post.php' === $hook || 'post-new.php' === $hook ) && 'tv_member' === $screen->post_type ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/team-view-admin.css', array(), $this->version, 'all' );
		}


	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts( $hook ) {

		$screen = get_current_screen();

		if ( ( 'post.php' === $hook || 'post-new.php' === $hook ) && 'tv_member' === $screen->post_type ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/team-view-admin.js', array( 'jquery', 'jquery-ui-sortable' ), $this->version, false );
		}

	}

	/**
	 * Render social links field.
	 *
	 * @since 1.0.0
	 */
	public function render_callback_for_social_links( $field, $value, $object_id, $object_type, $field_type ) {

		$limit = 5;

		printf( '<p>%1$s %2$s</p>', esc_html__( 'Enter full URL. (eg, https://facebook.com/yourprofile)', 'team-view' ), esc_html__( 'Drag and drop for sorting links.', 'team-view' ) );

		echo '<ul class="team-view-social-links-wrap">';

		for ( $i = 1; $i <= $limit ; $i++ ) {
			?>
			<li>
				<?php
				echo $field_type->input( array(
					'name'  => $field_type->_name( '[]' ),
					'value' => ( isset( $value[ $i - 1 ] ) ) ? $value[ $i - 1 ] : '',
					) );
					?>
					<i class="dashicons dashicons-move"></i>
				</li>
			<?php
		}

		echo '</ul><!-- .team-view-social-links-wrap -->';

	}

	/**
	 * Sanitize social links.
	 *
	 * @since 1.0.0
	 */
	public function sanitize_social_links( $override_value, $value ) {

		if ( ! empty( $value ) && is_array( $value ) ) {
			$new_value = array();

			foreach ( $value as $v ) {
				if ( $v ) {
					$new_value[] = esc_url_raw( $v );
				}
			}

			$value = $new_value;
		}

		return $value;

	}


	/**
	 * Add metabox.
	 *
	 * @since 1.0.0
	 */
	public function add_metabox() {

		$prefix = '_team_view_';

		$cmb_member_details = new_cmb2_box( array(
			'id'           => $prefix . 'member_details',
			'title'        => esc_html__( 'Member Details', 'team-view' ),
			'object_types' => array( 'tv_member' ),
		) );

		if ( ! is_object( $cmb_member_details ) ) {
			return;
		}

		$cmb_member_details->add_field( array(
			'name' => esc_html__( 'Position', 'team-view' ),
			'desc' => esc_html__( 'Eg. Project Manager', 'team-view' ),
			'id'   => $prefix . 'position',
			'type' => 'text',
		) );

		$cmb_member_details->add_field( array(
			'name' => esc_html__( 'Social Profile', 'team-view' ),
			'id'   => $prefix . 'social',
			'type' => 'social_links',
		) );

	}

}
