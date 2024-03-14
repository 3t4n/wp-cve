<?php

defined( 'ABSPATH' ) || die();

class Cookie_Notice_Consent_Logger {
	
	/**
	 * Constructor
	 */
	public function __construct( $instance ) {
		$this->cnc = $instance;
		// Add actions in init, since settings need to be loaded earlier
		add_action( 'init', array( $this, 'init_logger' ), 50 );
	}
	
	/**
	 * Initialize logger
	 */
	public function init_logger() {
		if( $this->cnc->settings->get_option( 'consent_settings', 'log_consents' ) )
			$this->add_actions();
		$this->setup_auto_purger();
	}
	
	/**
	 * Add actions
	 */
	public function add_actions() {
		// Register consent log CPT
		add_action( 'init', array( $this, 'register_cpt' ), 51 );
		// Add admin actions
		add_action( 'admin_init', array( $this, 'manage_admin_views' ) );
		// Add logs menu page
		add_action( 'admin_menu', array( $this, 'add_menu_page' ), 99 );
		// Define logs as options submenu page
		add_action( 'parent_file', array( $this, 'define_parent_file' ) );
		// Add custom columns
		add_filter( 'manage_cookie_consent_posts_columns', array( $this, 'set_post_list_column_header' ) );
		add_action( 'manage_cookie_consent_posts_custom_column' , array( $this, 'set_post_list_column_content' ), 10, 2 );
	}
	
	/**
	 * Register consent log CPT
	 */
	public function register_cpt() {
		$cookie_consent_labels = array(
			'edit_item'				=> __( 'View Cookie Consent', 'cookie-notice-consent' ),
			'search_items'			=> __( 'Search Consents', 'cookie-notice-consent' ),
			'not_found'				=> __( 'No consents found.', 'cookie-notice-consent' ),
			'not_found_in_trash'	=> __( 'No consents found in Trash.', 'cookie-notice-consent' )
		);
		$cookie_consent_args = array(
			'label'						=> __( 'Cookie Consents', 'cookie-notice-consent' ),
			'labels'					=> $cookie_consent_labels,
			'public'					=> false,
			'publicly_queryable'		=> false,
			'exclude_from_search'		=> true,
			'show_ui'					=> true,
			'query_var'					=> false,
			'menu_position'				=> 99,
			'show_in_menu'				=> false,
			'show_in_nav_menus'			=> false,
			'show_in_admin_bar'			=> false,
			'menu_icon'					=> false,
			'rewrite'					=> false,
			'has_archive'				=> false,
			'capability_type'			=> 'post',
			'capabilities'				=> array( 'create_posts' => false ),
			'map_meta_cap'				=> true,
			'show_in_rest'				=> false,
			'hierarchical'				=> false,
			'supports'					=> array( 'title' ),
		);
		register_post_type( 'cookie_consent', $cookie_consent_args );
	}
	
	/**
	 * Add submenu page
	 */
	public function add_menu_page() {
		add_submenu_page(
			'options-general.php',
			__( 'Cookie Consents', 'cookie-notice-consent' ),
			__( 'Cookie Consents', 'cookie-notice-consent' ),
			'manage_options',
			'edit.php?post_type=cookie_consent',
			false,
			99
		);
	}
	
	/**
	 * Define the parent file for the consent menu item
	 */
	public function define_parent_file( $parent_file ) {
		global $current_screen;
		if( 'cookie_consent' == $current_screen->post_type )
			$parent_file = 'options-general.php';
		return $parent_file;
	}
	
	/**
	 * Set consent table header columns
	 */
	public function set_post_list_column_header( $columns ) {
		$date = $columns['date'];
		unset( $columns['date'] );
		$columns['title'] = __( 'UUID', 'cookie-notice-consent' );
		$columns['cookie_categories'] = __( 'Cookie Categories', 'cookie-notice-consent' );
		$columns['date'] = $date;
		return $columns;
	}
	
	/**
	 * Set consent table column contents
	 */
	public function set_post_list_column_content( $column, $post_id ) {
		switch( $column ) {
			case 'cookie_categories' :
				$this->cnc->helper->pretty_print_logged_categories( $post_id );
				break;
		}
	}
	
	/**
	 * Filter out invalid cookie categories
	 */
	public function allowed_cookie_categories_filter( $input ) {
		$allowed = $this->cnc->helper->get_active_cookie_categories();
		$result = array_intersect( $allowed, $input );
		return $result;
	}
	
	/**
	 * Save consent log to db
	 */
	public function add_cookie_consent_log( $data ) {
		// Construct meta data to save
		$meta = array(
			'uuid'				=> !empty( $data->uuid ) ? $data->uuid : '',
			'categories'		=> !empty( $data->categories ) ? serialize( $this->cnc->logger->allowed_cookie_categories_filter( $data->categories ) ) : serialize( '' ),
			'remote_addr'		=> !empty( $data->remote_addr ) ? ( $this->cnc->settings->get_option( 'consent_settings', 'anonymize_consent_log_ips' ) ? wp_privacy_anonymize_ip( $data->remote_addr ) : $data->remote_addr ) : '',
			'http_user_agent'	=> !empty( $data->http_user_agent ) ? $data->http_user_agent : ''
		);
		// Insert post with meta into db
		wp_insert_post( array(
			'post_type'			=> 'cookie_consent',
			'post_status'		=> 'publish',
			'post_title'		=> !empty( $data->uuid ) ? wp_strip_all_tags( $data->uuid ) : '',
			'meta_input'		=> $meta
		) );
		_e( 'Cookie consent saved.', 'cookie-notice-consent' );
	}
	
	/**
	 * Single consent view functions and filters
	 */
	public function manage_admin_views() {
		global $typenow;
		if( empty( $typenow ) ) {
			// try to pick it up from the query string
			if( !empty( $_GET['post'] ) ) {
				$post = get_post( $_GET['post'] );
				$typenow = $post->post_type;
			}
		}
		if( 'cookie_consent' == $typenow ) {
			// Posts list: remove Quick Edit, remove Edit, add View
			add_filter( 'post_row_actions', array( $this, 'consent_post_row_actions' ), 10, 2 );
			// Bulk actions: Remove Edit
			add_filter( 'bulk_actions-edit-cookie_consent', array( $this, 'consent_bulk_actions' ) );
			// Restore from trash: set status to publish
			add_action( 'transition_post_status', array( $this, 'consent_untrash_status' ), 10, 3 );
			// View screen: Allow only 1-column layout
			add_action( 'in_admin_header', array( $this, 'consent_screen_layout' ) );
			// View screen: Inject js to set title to readonly
			add_action( 'edit_form_after_title', array( $this, 'consent_title_js' ), 100 );
			// View screen: Remove default meta boxes, add consent data
			add_action( 'add_meta_boxes', array( $this, 'consent_meta_boxes' ) );
		}
	}
		
	/**
	 * Set post table actions
	 */
	public function consent_post_row_actions( $actions, $post ) {
		// Remove "Quick Edit"
		unset( $actions['inline hide-if-no-js'] );
		// Remove "Edit"
		unset( $actions['edit'] );
		// Save "Trash" if present (means we're not in trash view)
		if( isset( $actions['trash'] ) ) {
			$trash_action = $actions['trash'];
			unset( $actions['trash'] );
			// Add View link
			if( $post && $post->ID ) {
				$actions['view'] = sprintf( '<a href="%s" title="%s">%s</a>', get_edit_post_link( $post->ID ), __( 'View', 'cookie-notice-consent' ), __( 'View', 'cookie-notice-consent' ) );
				// Re-add Trash as last item
				$actions['trash'] = $trash_action;
			}
		}
		return $actions;
	}
	
	/**
	 * Set post table bulk actions
	 */
	public function consent_bulk_actions( $actions ) {
		// Remove Edit bulk action
		unset( $actions['edit'] );
		return $actions;
	}
	
	/**
	 * If consent gets restored from trash, set its status to publish
	 */
	public function consent_untrash_status( $new_status, $old_status, $post ) {
		if( 'trash' === $old_status && 'draft' === $new_status && 'cookie_consent' === $post->post_type ) {
			wp_update_post( array( 'ID' => $post->ID, 'post_status' => 'publish' ) );
		}
	}
	
	/**
	 * Set consent post view layout options
	 */
	public function consent_screen_layout() {
		// set default/only layout option as single column
		add_screen_option( 'layout_columns', array( 'max' => 1, 'default' => 1 ) );
	}
	
	/**
	 * Inject script in order to set title to readonly
	 */
	public function consent_title_js( $post ) {
		?>
		<script>document.getElementById( 'title' ).setAttribute( 'readonly', 'readonly' );</script>
		<?php
	}
	
	/**
	 * Remove default meta boxes, add custom meta box
	 */
	public function consent_meta_boxes() {
		// Remove submit/save metabox
		remove_meta_box( 'submitdiv', 'cookie_consent', 'side' );
		// Remove custom fields metabox
		remove_meta_box( 'postcustom', 'cookie_consent', 'normal' );
		// Add our own metabox
		add_meta_box( 'consentdata', __( 'Consent Data', 'cookie-notice-consent' ), array( $this, 'render_consent_admin_view' ), 'cookie_consent' );
	}
	
	/**
	 * Custom meta box content
	 */
	public function render_consent_admin_view(){
		?>
		<table class="cookie-notice-consent__consent-data-table">
			<tr>
				<td><?php _e( 'UUID', 'cookie-notice-consent' ); ?></td>
				<td><?php echo get_post_meta( get_the_id(), 'uuid', true ); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'Date/Time', 'cookie-notice-consent' ); ?></td>
				<td><?php echo get_the_date( get_option( 'date_format' ) ); ?> <?php echo get_the_time( get_option( 'time_format' ) ); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'Remote Address', 'cookie-notice-consent' ); ?></td>
				<td><?php echo get_post_meta( get_the_id(), 'remote_addr', true ); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'HTTP User Agent', 'cookie-notice-consent' ); ?></td>
				<td><?php echo get_post_meta( get_the_id(), 'http_user_agent', true ); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'Cookie Categories', 'cookie-notice-consent' ); ?></td>
				<td><?php $this->cnc->helper->pretty_print_logged_categories( get_the_id() ); ?></td>
			</tr>
		</table>
		<?php
	}
	
	/**
	 * Setup consent auto-purger schedule and action
	 */
	public function setup_auto_purger() {
		// Setup scheduled event if logging and log purging is on
		if( $this->cnc->settings->get_option( 'consent_settings', 'log_consents' ) && $this->cnc->settings->get_option( 'consent_settings', 'auto_purge_consents' ) ) {
			// Add event if none exists
			if( !wp_next_scheduled( 'cookie_notice_consent_purger' ) )
				wp_schedule_event( time(), 'twicedaily', 'cookie_notice_consent_purger' );
			// Add action to event
			add_action( 'cookie_notice_consent_purger', array( $this, 'auto_purge_consents' ) );
		} else {
			// Clear event if purging is off
			if( wp_next_scheduled( 'cookie_notice_consent_purger' ) )
				wp_clear_scheduled_hook( 'cookie_notice_consent_purger' );
		}
	}
	
	/**
	 * Consent auto-purger action for trashing old consents
	 */
	public function auto_purge_consents() {
		// Get auto-purge interval from settings
		$older_than = $this->cnc->settings->get_option( 'consent_settings', 'auto_purge_consents_interval' );
		// Get logs to purge
		$to_purge = get_posts( array(
			'post_type' => 'cookie_consent',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'date_query' => array(
				array(
					'before' => $older_than . ' ago'
				)
			)
		) );
		// Trash all of them
		foreach( $to_purge as $postid ) {
			wp_trash_post( $postid );
		}
	}
	
}
