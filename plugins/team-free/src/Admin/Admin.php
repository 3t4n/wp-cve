<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since        2.0.0
 * @version      2.0.0
 *
 * @package    WP_Team
 * @subpackage WP_Team/admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\WPTeam\Admin;

use ShapedPlugin\WPTeam\Admin\Configs\Member\Member_Meta;
use ShapedPlugin\WPTeam\Admin\Configs\Generator;
use ShapedPlugin\WPTeam\Admin\Configs\Settings;
use ShapedPlugin\WPTeam\Admin\Configs\Tools;
use ShapedPlugin\WPTeam\Admin\DB_Updater;
use ShapedPlugin\WPTeam\Admin\Preview\SPTP_Preview;
use ShapedPlugin\WPTeam\Traits\Singleton;

/**
 * Admin class
 */
class Admin {

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
	 * Script and Style minified version suffix.
	 *
	 * @since 2.0.0
	 * @access protected
	 * @var string
	 */
	protected $min;

	/**
	 * All setting option.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $sptp_option
	 */
	private $sptp_options;

	/**
	 * Rename member name.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $sptp_member_name
	 */
	private $sptp_member_name;

	/**
	 * Rename team name.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $sptp_team_name
	 */
	private $sptp_team_name;

	/**
	 * Rename group name.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $sptp_group_name
	 */
	private $sptp_group_name;

	/**
	 * Rename team slug.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $sptp_team_slug
	 */
	private $sptp_team_slug;
	/**
	 * Member singular name
	 *
	 * @var mixed
	 */
	private $sptp_member_singular_name;
	/**
	 * Member singular name
	 *
	 * @var mixed
	 */
	private $sptp_member_plural_name;
	/**
	 * Group plural name
	 *
	 * @var mixed
	 */
	private $sptp_group_singular_name;
	/**
	 * Group singular name
	 *
	 * @var mixed
	 */
	private $sptp_group_plural_name;

	use Singleton;

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
		// Check for developer mode.
		$this->min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) || ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '' : '.min';

		$this->sptp_options              = get_option( '_sptp_settings' );
		$this->sptp_member_singular_name = ( ! empty( $this->sptp_options['rename_member_singular'] ) ) ? $this->sptp_options['rename_member_singular'] : __( 'Member', 'team-free' );
		$this->sptp_member_plural_name   = ( ! empty( $this->sptp_options['rename_member_plural'] ) ) ? $this->sptp_options['rename_member_plural'] : __( 'Members', 'team-free' );
		$this->sptp_group_singular_name  = ( ! empty( $this->sptp_options['rename_group_singular'] ) ) ? $this->sptp_options['rename_group_singular'] : __( 'Group', 'team-free' );
		$this->sptp_group_plural_name    = ( ! empty( $this->sptp_options['rename_group_plural'] ) ) ? $this->sptp_options['rename_group_plural'] : __( 'Groups', 'team-free' );
		$this->sptp_team_name            = ( ! empty( $this->sptp_options['rename_team'] ) ) ? $this->sptp_options['rename_team'] : __( 'Teams', 'team-free' );

		Member_Meta::metaboxes( 'sptp_member', '_sptp_add_member', $this->sptp_member_singular_name );
		Tools::metaboxes( '_sptp_tools' );
		Settings::metaboxes( '_sptp_settings' );
		Generator::preview_metabox( 'sptp_preview_display' );
		Generator::layout_metaboxes( '_sptp_generator_layout' );
		Generator::metaboxes( '_sptp_generator' );
		Generator::output_metaboxes( '_sptp_generator_output_sidebar' );
		// Database updater.
		new DB_Updater();
		new SPTP_Preview();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Team_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Team_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$current_screen    = get_current_screen();
		$current_post_type = $current_screen->post_type;
		if ( 'sptp_member' === $current_post_type || 'sptp_generator' === $current_post_type ) {
			// Main style.
			wp_enqueue_style( 'wp-team-spf', SPT_PLUGIN_ROOT . 'src/Admin/css/style' . $this->min . '.css', array(), $this->version, 'all' );
			// Main RTL styles.
			if ( is_rtl() ) {
				wp_enqueue_style( 'wp-team-spf-rtl', SPT_PLUGIN_ROOT . 'src/Admin/css/style-rtl' . $this->min . '.css', array(), $this->version, 'all' );
			}
			wp_enqueue_style( 'team-free-fontawesome' );

		}
		if ( 'sptp_generator' === $current_post_type ) {
			wp_enqueue_style( 'team-free-swiper' );
			wp_enqueue_style( SPT_PLUGIN_SLUG );
		}

		// Review notice style.
		wp_enqueue_style( 'sptp-review-notice', SPT_PLUGIN_ROOT . 'src/Admin/css/review-notice' . $this->min . '.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Team_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Team_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$current_screen    = get_current_screen();
		$current_post_type = $current_screen->post_type;
		if ( 'sptp_member' === $current_post_type || 'sptp_generator' === $current_post_type ) {
			wp_enqueue_script( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'js/team-admin.js', array( 'jquery' ), $this->version, true );
		}
		if ( 'sptp_generator' === $current_post_type ) {
			wp_enqueue_script( 'team-free-swiper' );
			wp_enqueue_style( 'sptp_element_block_icon', SPT_PLUGIN_ROOT . 'src/Admin/css/fontello.css', array(), SPT_PLUGIN_VERSION, 'all' );
		}
	}

	/**
	 * Register the widget for the public-facing side of the site.
	 *
	 * @param mixed $widget .
	 * @since    2.0.0
	 */
	public function register_wpteam_widget( $widget ) {
		register_widget( 'ShapedPlugin\WPTeam\Admin\WP_Team_Widget' );
		return $widget;
	}

	/**
	 * Register member post type from Team Pro plugin
	 *
	 * @since    2.0.0
	 */
	public function sptp_member_post_type() {
		$capability = apply_filters( 'sp_wp_team_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		$labels     = apply_filters(
			'sp_wp_team_member_post_labels',
			array(
				/* translators: %s is replaced with 'member plural name' */
				'name'                  => wp_sprintf( ( esc_html__( 'All %s', 'team-free' ) ), $this->sptp_member_plural_name ),
				/* translators: %s is replaced with 'member singular name' */
				'singular_name'         => wp_sprintf( ( esc_html__( '%1$1s %2$2s', 'team-free' ) ), $this->sptp_team_name, $this->sptp_member_singular_name ),
				/* translators: %s is replaced with 'add new member' */
				'add_new'               => wp_sprintf( esc_html__( 'Add New %s', 'team-free' ), $this->sptp_member_singular_name ),
				/* translators: %s is replaced with 'member singular name' */
				'add_new_item'          => wp_sprintf( esc_html__( 'Add New %s', 'team-free' ), $this->sptp_member_singular_name ),
				/* translators: %s is replaced with 'member singular name' */
				'edit_item'             => wp_sprintf( esc_html__( 'Edit %s', 'team-free' ), $this->sptp_member_singular_name ),
				/* translators: %s is replaced with 'member singular name' */
				'new_item'              => wp_sprintf( esc_html__( 'New %s', 'team-free' ), $this->sptp_member_singular_name ),
				/* translators: %s is replaced with 'member singular name' */
				'all_items'             => wp_sprintf( esc_html__( 'All %s', 'team-free' ), $this->sptp_member_plural_name ),
				/* translators: %s is replaced with 'member singular name' */
				'view_item'             => wp_sprintf( esc_html__( 'View %s', 'team-free' ), $this->sptp_member_singular_name ),
				/* translators: %s is replaced with 'member singular name' */
				'search_items'          => wp_sprintf( esc_html__( 'Search %s', 'team-free' ), $this->sptp_member_singular_name ),
				/* translators: %s is replaced with 'team name' */
				'not_found'             => wp_sprintf( esc_html__( 'No %1$1s %2$2s Found', 'team-free' ), $this->sptp_team_name, $this->sptp_member_singular_name ),
				/* translators: %s is replaced with 'team name' */
				'not_found_in_trash'    => wp_sprintf( esc_html__( 'No %1$1s %2$2s Found in Trash', 'team-free' ), $this->sptp_team_name, $this->sptp_member_singular_name ),
				'parent_item_colon'     => null,
				'menu_name'             => __( 'WP Team', 'team-free' ),
				/* translators: %s is replaced with 'member singular name' */
				'featured_image'        => wp_sprintf( esc_html__( '%s Image', 'team-free' ), $this->sptp_member_singular_name ),
				/* translators: %s is replaced with 'member singular name' */
				'set_featured_image'    => wp_sprintf( esc_html__( 'Set %s image', 'team-free' ), $this->sptp_member_singular_name ),
				/* translators: %s is replaced with 'member singular name' */
				'remove_featured_image' => wp_sprintf( esc_html__( 'Remove %s image', 'team-free' ), $this->sptp_member_singular_name ),
			)
		);
		// Base 64 encoded SVG image.
		$menu_icon = 'data:image/svg+xml;base64,' . base64_encode(
			'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 828 828" style="enable-background:new 0 0 828 828;" xml:space="preserve">
			<style type="text/css">
				.st0{fill:#A0A5AA;}
			</style>
			<g>
				<g>
					<path class="st0" d="M366.5,523.4l-0.7,3l-49.6,14.8c-14.1,4.4-30.4,45.9-43,111.1c42.2,25.2,91.1,38.5,140.7,38.5    c5.9,0,12.6,0,18.5-0.7c43-3,85.9-15.6,122.9-37.8c-12.6-64.4-28.9-107.4-43-111.1l-49.6-14.8l-0.7-3c-0.7-2.2-2.2-4.4-5.2-5.9    l-5.9-3.7l3.7-4.4c4.4-4.4,7.4-8.9,8.9-11.8c5.9-8.1,10.4-17,13.3-25.9c1.5-3.7,3-7.4,4.4-11.1l0.7-1.5l1.5-0.7    c3.7-3,5.2-6.7,5.2-11.1v-14.8c0-3-0.7-5.9-3-8.9L485,422v-22.2c0-33.3-27.4-60.7-60.7-60.7h-21.5c-33.3,0-60.7,27.4-60.7,60.7    V422l-0.7,1.5c-1.5,2.2-3,5.9-3,8.9v14.8c0,4.4,2.2,8.9,5.2,11.1l1.5,0.7l0.7,1.5c0.7,3.7,2.2,7.4,3.7,10.4    c3.7,8.9,8.1,17.8,14.1,25.9c3,4.4,5.9,8.1,8.9,11.8l3.7,4.4l-5.2,3.7C369.5,519.7,367.3,521.2,366.5,523.4z"/>
					<path class="st0" d="M248.1,636c1.5-8.1,3.7-16.3,5.9-25.2c18.5-76.3,37.8-91.1,54.1-96.3l28.1-8.1c-4.4-7.4-8.9-15.6-11.8-23.7    c-0.7-2.2-2.2-5.2-3-7.4l0,0l-30.4-8.9l-0.7-3c-0.7-2.2-2.2-4.4-5.2-5.9l-5.2-4.4l3.7-4.4c3-3.7,5.9-7.4,8.9-11.8    c5.9-8.1,10.4-17,13.3-25.2c1.5-3.7,3-7.4,4.4-11.1l0.7-1.5l1.5-0.7c3.7-3,5.2-6.7,5.2-11.1v-14.8c0-3-0.7-5.9-3-8.9L314,362    v-22.2c0-33.3-27.4-60.7-60.7-60.7h-21.5c-33.3,0-60.7,27.4-60.7,60.7V362l-0.7,1.5c-1.5,2.2-3,5.9-3,8.9v14.8    c0,4.4,2.2,8.9,5.2,11.1l1.5,0.7l0.7,1.5c0.7,3.7,2.2,7.4,4.4,10.4c3.7,8.9,8.1,17.8,14.1,25.9c3,4.4,5.9,8.1,8.9,11.8l3.7,4.4    l-5.2,3.7c-3,2.2-4.4,3.7-5.2,5.9l-0.7,3l-49.6,14.8h-0.7C159.9,542.7,197,597.5,248.1,636z"/>
					<path class="st0" d="M595.4,278.3h-21.5c-33.3,0-60.7,27.4-60.7,60.7v22.2l-0.7,1.5c-1.5,3-2.2,5.9-2.2,8.9v14.8    c0,4.4,2.2,8.9,5.2,11.1l1.5,0.7l0.7,1.5c1.5,3.7,2.2,7.4,3.7,10.4c3.7,8.9,8.1,17.8,14.1,25.9c2.2,3,5.2,7.4,8.9,11.8l3.7,4.4    l-5.2,3.7c-3,2.2-4.4,3.7-5.2,5.9l-0.7,3l-30.4,8.9l0,0c-0.7,3-2.2,5.2-3,8.1c-3,7.4-7.4,15.6-11.8,23.7l28.1,8.1    c16.3,4.4,35.5,20,54.1,94.8c2.2,8.9,4.4,17.8,5.9,26.7c15.6-11.8,30.4-25.2,43-40c28.9-33.3,50.4-73.3,60.7-115.5h-0.7    l-49.6-14.8l-0.7-3c-0.7-2.2-2.2-4.4-5.2-5.9l-5.2-3l4.4-4.4c3-3.7,5.9-7.4,8.9-11.8c5.9-8.1,10.4-17,13.3-25.2    c1.5-3.7,3-7.4,4.4-11.1l0.7-1.5l1.5-0.7c3.7-3,5.2-6.7,5.2-11.1v-14.8c0-3-0.7-5.9-3-8.9l-0.7-1.5v-22.2    C656.1,305.7,629.4,278.3,595.4,278.3z"/>
				</g>
				<g>
					<path class="st0" d="M414.3,746.3c-183.1,0-332-148.9-332-332s148.9-332,332-332s332,148.9,332,332S597.4,746.3,414.3,746.3z     M414.3,117.8c-163.5,0-296.5,133-296.5,296.5s133,296.5,296.5,296.5s296.5-133,296.5-296.5S577.8,117.8,414.3,117.8z"/>
				</g>
			</g>
			</svg>'
		);

		$args = apply_filters(
			'sp_wp_team_member_post_args',
			array(
				'labels'              => $labels,
				'public'              => true,
				'has_archive'         => false,
				'capability_type'     => 'post',
				'supports'            => array( 'title', 'editor', 'thumbnail' ),
				'rewrite'             => array(
					'slug'       => 'team',
					'with_front' => false,
				),
				'exclude_from_search' => apply_filters( 'sp_team_member_exclude_from_search', false ),
				'menu_icon'           => $menu_icon,
				'menu_position'       => 80,
				'show_ui'             => $show_ui,
			)
		);

		register_post_type( 'sptp_member', $args );
	}

	/**
	 * Register sptp_generator custom post type
	 *
	 * @since    2.0.0
	 */
	public function sptp_generator_post_type() {
		$capability = apply_filters( 'sp_wp_team_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		$labels     = apply_filters(
			'sp_wp_team_post_type_labels',
			array(
				'name'               => __( 'All Teams', 'team-free' ),
				'singular_name'      => __( 'Team', 'team-free' ),
				'add_new'            => __( 'Add New Team', 'team-free' ),
				'add_new_item'       => __( 'Add New Team', 'team-free' ),
				'edit_item'          => __( 'Edit Team', 'team-free' ),
				'new_item'           => __( 'New Generator', 'team-free' ),
				/* translators: %s is replaced with 'Singular team name' */
				'all_items'          => wp_sprintf( __( 'Manage %s', 'team-free' ), $this->sptp_team_name ),
				'view_item'          => __( 'View Generator', 'team-free' ),
				'search_items'       => __( 'Search Generator', 'team-free' ),
				'not_found'          => __( 'No Generator Found', 'team-free' ),
				'not_found_in_trash' => __( 'No Generator Found in Trash', 'team-free' ),
				'parent_item_colon'  => null,
				/* translators: %s is replaced with 'Singular team name' */
				'menu_name'          => wp_sprintf( __( '%s Generator', 'team-free' ), $this->sptp_team_name ),
			)
		);
		$args       = apply_filters(
			'sp_wp_team_post_type_args',
			array(
				'labels'              => $labels,
				'has_archive'         => true,
				'capability_type'     => 'post',
				'supports'            => array( 'title' ),
				'rewrite'             => array( 'slug' => 'generator' ),
				'show_in_menu'        => 'edit.php?post_type=sptp_member',
				'public'              => false,
				'publicly_queryable'  => false,
				'show_ui'             => $show_ui,
				'exclude_from_search' => true,
				'show_in_nav_menus'   => false,
				'has_archive'         => false,
				'rewrite'             => true,
				'show_in_rest'        => true,
			)
		);

		register_post_type( 'sptp_generator', $args );

	}

	/**
	 * Rename member columns for WP Team plugin.
	 *
	 * @since    2.0.0
	 * @param  mixed $columns columns of all member page.
	 */
	public function set_member_columns( $columns ) {
		return array(
			'cb'       => '<input type="checkbox" />',
			'title'    => __( 'Name', 'team-free' ),
			'position' => __( 'Position', 'team-free' ),
			'image'    => __( 'Image', 'team-free' ),
		);
	}

	/**
	 * Get data in member columns for WP Team plugin.
	 *
	 * @since    2.0.0
	 * @param  mixed   $column columns of all member page.
	 * @param integer $post_id post id of member.
	 */
	public function get_member_columns( $column, $post_id ) {

		$member_info = get_post_meta( $post_id, '_sptp_add_member', true );
		if ( is_array( $member_info ) ) {
			if ( has_post_thumbnail( $post_id ) ) {
				$image_url = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
			} else {
				$image_url = isset( $member_info['member_image_gallery'] ) ? wp_get_attachment_url( $member_info['member_image_gallery'] ) : '';
			}
			$feature_image = '<img src="' . $image_url . '" class="list-image"/>';
			switch ( $column ) {
				case 'position':
					echo isset( $member_info['sptp_job_title'] ) ? esc_html( $member_info['sptp_job_title'] ) : '';
					break;
				case 'image':
					echo wp_kses(
						$feature_image,
						array(
							'img' => array(
								'src'   => array(),
								'class' => array(),
							),
						)
					);
					break;
				default:
					break;
			}
		}
	}

	/**
	 * Rename columns in all team page for WP Team plugin.
	 *
	 * @since    2.0.0
	 * @param  mixed $columns columns of all team page.
	 */
	public function set_generator_columns( $columns ) {
		return array(
			'cb'        => '<input type="checkbox" />',
			'title'     => __( 'Name', 'team-free' ),
			'shortcode' => __( 'Shortcode', 'team-free' ),
			'layout'    => __( 'Layout', 'team-free' ),
			'date'      => __( 'Date', 'team-free' ),
		);
	}

	/**
	 * Get generator columns
	 *
	 * @since    2.0.0
	 * @param  mixed   $column columns of all team page.
	 * @param integer $post_id post id of team.
	 */
	public function get_generator_columns( $column, $post_id ) {

		$team_layout = get_post_meta( $post_id, '_sptp_generator_layout', true );
		switch ( $column ) {
			case 'shortcode':
				echo '<div class="sptp-after-copy-text"><i class="fa fa-check-circle"></i>  Shortcode  Copied to Clipboard! </div>';
				echo "<input style='width: 230px; padding: 6px; cursor: pointer;' readonly='readonly' type='text' onclick='this.select()' value='";
				echo '[wpteam id="' . esc_html( $post_id ) . '"]';
				echo "'/>";
				break;
			case 'layout':
				echo isset( $team_layout['layout_preset'] ) ? esc_html( $team_layout['layout_preset'] ) : '';
				break;
			default:
				echo '';
		}

	}

	/**
	 * 'Member Name' from 'Enter Title Here'
	 *
	 * @since    2.0.0
	 * @param mixed $input post type input.
	 */
	public function member_name( $input ) {
		if ( 'sptp_member' === get_post_type() ) {
			return wp_sprintf( '%s Name', $this->sptp_member_name );
		}
		return $input;
	}

	/**
	 * Hide publishing action.
	 *
	 * @since    2.0.0
	 */
	public function hide_publishing_actions() {
		$sptp_post_type = 'sptp_generator';
		global $post;
		if ( $post->post_type == $sptp_post_type ) {
		}
	}

	/**
	 * Bottom review notice.
	 *
	 * @param string $text The review notice.
	 * @return string
	 */
	public function sptp_review_text( $text ) {
		$screen = get_current_screen();
		if ( is_object( $screen ) && ( 'sptp_member' === $screen->post_type || 'sptp_generator' === $screen->post_type ) ) {
			$url  = 'https://wordpress.org/support/plugin/team-free/reviews/?filter=5#new-post';
			$text = sprintf( wp_kses_post( 'Enjoying <strong>WP Team?</strong> Please rate us <span class="spwpteam-footer-text-star">★★★★★</span> <a href="%s" target="_blank">WordPress.org</a>. Your positive feedback will help us grow more. Thank you! 😊', 'team-free' ), esc_url( $url ) );
		}

		return $text;
	}

	/**
	 * Bottom version notice.
	 *
	 * @param string $text The version notice.
	 * @return string
	 */
	public function sptp_version_text( $text ) {
		$screen = get_current_screen();
		if ( is_object( $screen ) && 'sptp_member' === $screen->post_type ) {
			$text = 'WP Team ' . $this->version;
		}

		return $text;
	}

	/**
	 * Custom post type Save and update alert in Admin Dashboard created by WP Team
	 *
	 * @param array $messages alert messages.
	 */
	public function sptp_update( $messages ) {
		global $post, $post_ID;
		$messages['sptp_generator'][1] = __( 'Team Updated', 'team-free' );
		$messages['sptp_generator'][6] = __( 'Team Published', 'team-free' );
		/* translators: %s is replaced with respective permalink */
		$messages['sptp_member'][1] = wp_sprintf( __( 'Member Updated. <a href="%s">View Member</a>', 'team-free' ), esc_url( get_permalink( $post_ID ) ) );
		/* translators: %s is replaced with respective permalink */
		$messages['sptp_member'][6] = wp_sprintf( __( 'Member Published. <a href="%s">View Member</a>', 'team-free' ), esc_url( get_permalink( $post_ID ) ) );
		return $messages;
	}

	/**
	 * Redirect to help page after activation.
	 *
	 * @param string $plugin_admin Path to the plugin file, relative to the plugin.
	 * @return void
	 */
	public function redirect_to_help( $plugin_admin ) {
		if ( SPT_PLUGIN_BASENAME === $plugin_admin ) {
			exit( esc_url( wp_safe_redirect( admin_url( 'edit.php?post_type=sptp_member&page=team_help' ) ) ) );
		}
	}
}
