<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.hardkod.ru
 * @since      1.0.1
 *
 * @package    Ya_Turbo
 * @subpackage Ya_Turbo/includes
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 *
 * @since      1.0.0
 * @package    Ya_Turbo
 * @subpackage Ya_Turbo/includes
 * @author     hardkod.ru <hello@hardkod.ru>
 */

class Ya_Turbo_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Admin init action
	 */
	public function init () {
		add_filter('plugin_action_links', array($this, 'action_links'), 10, 2);
	}

	/**
	 * Plugin action links
	 *
	 * @param $links
	 * @param $file
	 *
	 * @return array $links
	 */
	public function action_links ( $links, $file ) {

		if ( YATURBO_BASENAME === $file ) {
			$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=' . YATURBO_FEED ) ) . '">'
			                    . __( 'Settings', YATURBO_FEED ) . '</a>';
			array_unshift( $links, $settings_link );
		}

		return $links;
	}

	/**
	 * Admin menu
	 */
	public function menu () {

		/* Index admin page */
		add_menu_page( 'Ya Turbo', 'Ya Turbo', 'manage_options', YATURBO_FEED, array( $this, 'options_page' ) );

		/* Add new feed Page */
		add_submenu_page( YATURBO_FEED, __( 'Add feed', YATURBO_FEED), __( 'Add feed', YATURBO_FEED), 'manage_options',
			YATURBO_FEED . '-add', array($this, 'options_page__add_new') );

		/* Edit page */
		add_submenu_page( null, __( 'Edit feed', YATURBO_FEED), __( 'Edit feed', YATURBO_FEED), 'manage_options',
			YATURBO_FEED . '-edit', array($this, 'options_page__edit_feed') );

		/* Delete page */
		add_submenu_page( null, __( 'Delete feed', YATURBO_FEED), __( 'Delete feed', YATURBO_FEED), 'manage_options',
			YATURBO_FEED . '-del', array($this, 'options_page__del_feed') );
		
		flush_rewrite_rules();
	}

	public function options_page () {

		global $wpdb;

		$table = $wpdb->prefix . YATURBO_DB_FEEDS;

		$sql = /** @lang sql **/
			"SELECT `type`, `status`, `slug`, `title`, `language`, 
					`limit`, `id`
			FROM {$table}
			WHERE 1
			ORDER BY id DESC";

		$data = $wpdb->get_results( $sql );

		require YATURBO_PATH . '/admin/partials/ya-turbo-admin-display.php';
	}

	/**
	 * Add metaboxes
	 */
	public function metabox () {

		$post_types = $this->get_post_types();

		if ( !empty( $post_types )) {

			$callback = array( $this, 'metabox_html' );

			foreach ( $post_types as $type ) {
				add_meta_box( YATURBO_FEED, __('Yandex Turbo', YATURBO_FEED),  $callback, $type, 'advanced');
			}
		}
	}

	/**
	 * Metabox fields
	 */
	public function metabox_html () {

		global $post;

		wp_nonce_field(
			YATURBO_FEED . '_metabox_nonce',
			YATURBO_FEED . '_metabox_nonce'
		);

		$yandex_related = get_post_meta( $post->ID, 'turbo_yandex_related', true );
		$yandex_related = esc_textarea( $yandex_related );
		$description    = __( 'Enter comma separated post_id list.', YATURBO_FEED );

		$label = '< ' . __('Yandex:Related', YATURBO_FEED) . ' >';

		print <<<TPL
		<fieldset>
			<label for="turbo_yandex_related">{$label}</label>
			<textarea id="turbo_yandex_related" 
					  name="turbo_yandex_related"
			          cols="40" 
			          rows="4" 
			          class="widefat" >{$yandex_related}</textarea>
			<p>{$description}</p>
		</fieldset>
TPL;
	}

	/**
	 * Save metabox data
	 */
	public function save_post ( $post_id ) {

		/* nonce */
		if ( ! isset( $_POST[ YATURBO_FEED . '_metabox_nonce' ] ) )
			return $post_id;

		$nonce = $_POST[ YATURBO_FEED . '_metabox_nonce' ];

		if ( ! wp_verify_nonce( $nonce, YATURBO_FEED . '_metabox_nonce' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		$turbo_yandex_related = sanitize_text_field( $_POST['turbo_yandex_related'] );
		$turbo_yandex_related = implode( ',', $this->sanitize_int_list($turbo_yandex_related) );

		update_post_meta( $post_id, 'turbo_yandex_related', $turbo_yandex_related );
	}

	/**
	 * Add new feed
	 */
	public function options_page__add_new () {

		global $wpdb;

		$error = $message = array();

		$data = array(
			'post_types' => $this->get_post_types()
		);

		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {

			$nonce = $_POST['_wpnonce'];

			if ( ! wp_verify_nonce( $nonce, YATURBO_FEED ) ) {

				$error[] = __( 'Invalid request', YATURBO_FEED );

			} else {

				$slug   = sanitize_title_with_dashes( $_POST['slug'] );

				if ( !$slug ) {
					$error[] = __( 'Slug field is required', YATURBO_FEED );
				}

				$language       = esc_html( $_POST['language'] );

				if( mb_strlen($language) > 2 ) {
					$error[] = __( 'Language code is too large', YATURBO_FEED );
				}

				$orderby      = esc_html( $_POST['orderby'] );

				if ( !in_array($orderby, $this->get_orderby_options()) ) {
					$error[] = __( 'Invalid order', YATURBO_FEED );
				}

				$order = esc_html( $_POST['order'] );

				if ( !in_array($order, $this->get_order_options()) ) {
					$error[] = __( 'Invalid order', YATURBO_FEED );
				}

				$title        = trim( esc_html( $_POST['title'] ) );

				if ( $title == "" ) {
					$title = __( 'No name', YATURBO_FEED );
				}

				$cache        = absint( $_POST['cache'] );
				$limit        = absint( $_POST['limit'] );
				$description  = esc_html( $_POST['description'] );
				$nopostid     = $this->sanitize_int_list( $_POST['nopostid'] );

				if ( !isset($_POST['post']) ) {

					$error[] = __( 'Post type required', YATURBO_FEED );

				} elseif ( !$this->is_all( $_POST['post'], $this->get_post_types() )) {

					$error[] = __( 'Post type no valid', YATURBO_FEED );
				}

				if ( empty( $error ) ) {

					$post = $_POST['post'];

					$settings = serialize(array(
						'cache'     => $cache,
						'post'      => $post,
						'nopostid'  => $nopostid,
						'orderby'   => esc_sql($orderby),
						'order'     => esc_sql($order),
					));

					$numRows = $wpdb->insert(
						$wpdb->prefix . YATURBO_DB_FEEDS,
						array(
							'type'           => YATURBO_FEED_TYPE_TURBO,
							'status'         => YATURBO_FEED_STATUS_ACTIVE,
							'slug'           => esc_sql($slug),
							'title'          => esc_sql($title),
							'description'    => esc_sql($description),
							'limit'          => esc_sql($limit),
							'language'       => esc_sql($language),
							'settings'       => $settings,
						)
					);

					if ( false === $numRows || $wpdb->last_error !== '' ) {

						$error[] = __( 'Duplicate slug entry', YATURBO_FEED );

					} else {

						$link = add_query_arg(array(
							'page' => YATURBO_FEED . '-edit',
							'id' => $wpdb->insert_id,
						), admin_url('admin.php'));

						$message[] = __( 'Feed has been created', YATURBO_FEED ) . ' <a href="' . $link . '">'
						                . __( 'View feed', YATURBO_FEED )
									. '</a>';
					}
				}
			}
		}

		require YATURBO_PATH . '/admin/partials/ya-turbo-admin-add-feed.php';
	}

	public function options_page__edit_feed () {

		global $wpdb;

		$error = $message = array();

		$feed = null;

		$data = array(
			'post_types' => $this->get_post_types()
		);

		$id = absint($_GET['id']);

		$feed = $this->load_feed( $id );

		if ( !$feed ) {
			$error[] = __( 'Feed not found', YATURBO_FEED );
		}

		/* Submit */
		if ( empty($error) && 'POST' == $_SERVER['REQUEST_METHOD'] ) {

			$nonce = $_POST['_wpnonce'];

			if ( ! wp_verify_nonce( $nonce, YATURBO_FEED ) ) {

				$error[] = __( 'Invalid request', YATURBO_FEED );

			} else {

				$pid  = absint( $_POST['id'] );

				$status  = absint( $_POST['status'] );

				if ( !in_array( $status, $this->get_status_options() )) {
					$error[] = __( 'Invalid status', YATURBO_FEED );
				}

				if ( !$pid || $pid != $id) {
					$error[] = __( 'Invalid feed id', YATURBO_FEED );
				}

				$title  = trim( esc_html( $_POST['title'] ) );

				if ( !mb_strlen( $title ) ) {
					$error[] = __( 'Title field is required', YATURBO_FEED );
				}

				$slug   = sanitize_title_with_dashes( $_POST['slug'] );

				if ( !$slug ) {
					$error[] = __( 'Slug field is required', YATURBO_FEED );
				}

				$language       = esc_html( $_POST['language'] );

				if( mb_strlen($language) > 2 ) {
					$error[] = __( 'Language code is too large', YATURBO_FEED );
				}

				$orderby      = esc_html( $_POST['orderby'] );

				if ( !in_array($orderby, $this->get_orderby_options()) ) {
					$error[] = __( 'Invalid order', YATURBO_FEED );
				}

				$order = esc_html( $_POST['order'] );

				if ( !in_array($order, $this->get_order_options()) ) {
					$error[] = __( 'Invalid order', YATURBO_FEED );
				}

				$cache        = absint( $_POST['cache'] );
				$limit        = absint( $_POST['limit'] );
				$description  = esc_html( $_POST['description'] );
				$nopostid     = $this->sanitize_int_list( $_POST['nopostid'] );

				if ( !isset($_POST['post']) ) {

					$error[] = __( 'Post type required', YATURBO_FEED );

				} elseif ( !$this->is_all( $_POST['post'], $this->get_post_types() )) {

					$error[] = __( 'Post type no valid', YATURBO_FEED );
				}

				if ( empty( $error ) ) {

					$post = $_POST['post'];

					$numRows = $wpdb->update(
						$wpdb->prefix . YATURBO_DB_FEEDS,
						array(
							'type'           => YATURBO_FEED_TYPE_TURBO,
							'status'         => $status,
							'slug'           => esc_sql($slug),
							'title'          => esc_sql($title),
							'description'    => esc_sql($description),
							'limit'          => esc_sql($limit),
							'language'       => esc_sql($language),
							'settings'       => serialize(array(
									'cache'     => $cache,
									'post'      => $post,
									'nopostid'  => $nopostid,
									'orderby'   => esc_sql($orderby),
									'order'     => esc_sql($order),
								)
							),
						),
						array(
							'id' => $id
						)
					);

					/* cache */
					$cache_key = YATURBO_FEED . '-' . wp_hash( esc_sql( $slug ) );
					delete_transient( $cache_key );

					if ( false === $numRows || $wpdb->last_error !== '' ) {

						$error[] = __( 'Duplicate slug entry', YATURBO_FEED );

					} else {

						$link = add_query_arg(array(
							'page' => YATURBO_FEED . '-edit',
							'id' => $wpdb->insert_id,
						), admin_url('admin.php'));

						$link_all = add_query_arg(array(
							'page' => YATURBO_FEED,
						), admin_url('admin.php'));

						$message[] = __( 'Feed has been saved', YATURBO_FEED )
						             . '. <a href="' . $link_all . '">' . __( 'View all', YATURBO_FEED ). '</a>';

						$feed = $this->load_feed( $id );
					}
				}

			}
		}

		require YATURBO_PATH . '/admin/partials/ya-turbo-admin-edit-feed.php';
	}

	/**
	 * Delete feed action
	 */
	public function options_page__del_feed () {

		global $wpdb;

		$message = $error = array();

		if ( !check_admin_referer('feed-del', YATURBO_FEED) ) {
			wp_die(__( 'Not allowed', YATURBO_FEED));
		}

		$id = absint($_GET['id']);

		$feed = $this->load_feed( $id );

		$rows_deleted = $wpdb->delete(
			$wpdb->prefix . YATURBO_DB_FEEDS,
			array(
				'id' => $id
			)
		);

		if( $feed && $rows_deleted !== false ) {

			$message[] = __( 'Feed has been deleted', YATURBO_FEED );

		} else {
			$error[] = __( 'Feed not found', YATURBO_FEED );
		}

		require YATURBO_PATH . '/admin/partials/ya-turbo-admin-del-feed.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/* This function is provided for demonstration purposes only. */
		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/* This function is provided for demonstration purposes only. */
//		 wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('wp-ajax-response');

	}

	/**
	 * Post types list
	 *
	 * @return array
	 */
	protected function get_post_types () {
		$post_types = get_post_types( array( 'public' => true ), 'names' );
		return array_diff($post_types, array("attachment"));
	}

	/**
	 * Load feed data
	 *
	 * @param $id
	 * @return stdClass|false
	 */
	protected function load_feed( $fid = null ) {

		global $wpdb;

		if ( !$fid ) {
			return FALSE;
		}

		$table  = $wpdb->prefix . YATURBO_DB_FEEDS;
		$sql    = /** @lang sql **/ "SELECT * FROM {$table} WHERE id = %d";
		$query  = $wpdb->prepare( $sql, $fid );
		$feed = $wpdb->get_row($query);

		if( $feed ) {
			$feed->settings = unserialize( $feed->settings );
		}

		return $feed;
	}

	/**
	 * OrderBy select element options
	 */
	protected function get_orderby_options() {
		return array(
			'date',
			'modified',
			'rand',
			'id'
		);
	}

	/**
	 * Order select element options
	 *
	 * @return array
	 */
	protected function get_order_options() {
		return array(
			'ASC',
			'DESC',
		);
	}

	/**
	 * Feed status element options
	 *
	 * @return array
	 */
	protected function get_status_options() {
		return array (
			YATURBO_FEED_STATUS_ACTIVE,
			YATURBO_FEED_STATUS_DISABLED,
		);
	}

	/**
	 * Sanitize comma separated INTs list
	 *
	 * @param $string
	 */
	protected function sanitize_int_list( $string ) {
		$arr  = explode( ',', $string );
		$ints = array_filter( $arr, 'is_numeric' );
		return array_unique( array_map( 'trim', $ints ) );
	}

	/**
	 * Check if array has only values from predicate
	 * @return bool
	 */
	protected function is_all( $array, $predicate ) {
		return count( array_intersect( $array, $predicate ) ) == count( $array );
	}

}
