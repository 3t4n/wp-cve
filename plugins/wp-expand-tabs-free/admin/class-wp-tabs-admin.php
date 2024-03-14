<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/admin
 * @author     ShapedPlugin <help@shapedplugin.com>
 */
class WP_Tabs_Admin {

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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Tabs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Tabs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$current_screen        = get_current_screen();
		$the_current_post_type = $current_screen->post_type;
		if ( 'sp_wp_tabs' === $the_current_post_type ) {
			wp_enqueue_style( 'font-awesome', WP_TABS_URL . '/public/css/font-awesome.min.css', array(), WP_TABS_VERSION, 'all' );
		}
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-tabs-admin.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the widget for the public-facing side of the site.
	 *
	 * @param array $widget Register widget.
	 * @since    2.0.1
	 */
	public function register_wptabs_widget( $widget ) {
		register_widget( 'WP_Tabs_Widget' );
		return $widget;
	}

	/**
	 * Function creates tabs duplicate as a draft.
	 */
	public function duplicate_wp_tabs() {
		global $wpdb;
		if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'sp_duplicate_tabs' === $_REQUEST['action'] ) ) ) {
			wp_die( esc_html__( 'No tabs to duplicate has been supplied!', 'wp-expand-tabs-free' ) );
		}

		/*
		* Nonce verification
		*/
		if ( ! isset( $_GET['sp_duplicate_tabs_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['sp_duplicate_tabs_nonce'] ) ), basename( __FILE__ ) ) ) {
			return;
		}

		/*
		* Get the original shortcode id
		*/
		$post_id    = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : absint( $_POST['post'] );
		$capability = apply_filters( 'sp_wp_tabs_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		if ( ! $show_ui && get_post_type( $post_id ) !== 'sp_wp_tabs' ) {
			wp_die( esc_html__( 'No shortcode to duplicate has been supplied!', 'wp-expand-tabs-free' ) );
		}

		/*
		* and all the original shortcode data then
		*/
		$post = get_post( $post_id );

		$current_user    = wp_get_current_user();
		$new_post_author = $current_user->ID;

		/*
		* if shortcode data exists, create the shortcode duplicate
		*/
		if ( isset( $post ) && null !== $post ) {
			/*
			* New shortcode data array.
			*/
			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'draft',
				'post_title'     => $post->post_title,
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order,
			);

			/*
			* insert the shortcode by wp_insert_post() function
			*/
			$new_post_id = wp_insert_post( $args );

			/*
			* get all current post terms ad set them to the new post draft
			*/
			$taxonomies = get_object_taxonomies( $post->post_type ); // returns array of taxonomy names for post type, ex array("category", "post_tag").
			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
				wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
			}

			/*
			* Duplicate all post meta.
			*/
			$post_meta_infos = get_post_custom( $post_id );
			// Duplicate all post meta just.
			foreach ( $post_meta_infos as $key => $values ) {
				foreach ( $values as $value ) {
					$value = wp_slash( maybe_unserialize( $value ) ); // Unserialize data to avoid conflicts.
					add_post_meta( $new_post_id, $key, $value );
				}
			}
			// Finally, redirect to the edit post screen for the new draft.
			wp_safe_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) );

			exit;
		} else {
			wp_die( esc_html__( 'Tabs creation failed, could not find original tabs: ', 'wp-expand-tabs-free' ) . esc_html( $post_id ) );
		}
	}

	/**
	 * Add the duplicate link to action list for post_row_actions.
	 *
	 * @param array  $actions Duplicate post link.
	 * @param object $post Duplicate post.
	 */
	public function sp_duplicate_tabs_link( $actions, $post ) {
		$capability = apply_filters( 'sp_wp_tabs_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		if ( $show_ui && 'sp_wp_tabs' === $post->post_type ) {
			$actions['duplicate'] = '<a href="' . wp_nonce_url( 'admin.php?action=sp_duplicate_tabs&post=' . $post->ID, basename( __FILE__ ), 'sp_duplicate_tabs_nonce' ) . '" rel="permalink">' . __( 'Duplicate', 'wp-expand-tabs-free' ) . '</a>';
		}
		return $actions;
	}

	/**
	 * Redirect after activation.
	 *
	 * @param string $file Path to the plugin file, relative to the plugin.
	 * @return void
	 */
	public function sp_tabs_redirect_after_activation( $file ) {
		if ( WP_TABS_BASENAME === $file ) {
			exit( esc_url( wp_safe_redirect( admin_url( 'edit.php?post_type=sp_wp_tabs&page=tabs_help' ) ) ) );
		}
	}

}
