<?php

namespace WP_VGWORT;


/**
 * Plugin Admin Initialization
 *
 * Load Dependencies, Add Actions & Filters, Add Admin Menu, Initialize Pages.
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Admin {
	/**
	 * @var Page_Settings Settings Page Class
	 */
	private Page_Settings $page_settings;

	/**
	 * @var Page_Pixels Pixel Overview Page Class
	 */
	private Page_Pixels $page_pixels;

	/**
	 * @var Page_Dashboard Dashboard Page Class
	 */
	private Page_Dashboard $page_dashboard;

	/**
	 * @var Page_Messages Messages / Posts with Pixels Page Class
	 */
	private Page_Messages $page_messages;

	/**
	 * @var Page_Message Create Message Page Class
	 */
	private Page_Message $page_message;


	/**
	 * @var Page_Participants Participant Page Class
	 */
	private Page_Participants $page_participant;


	/**
	 * @var object holds plugin reference
	 */
	private object $plugin;

	/**
	 * constructor
	 */
	public function __construct( $plugin ) {
		// set plugin reference
		$this->plugin = $plugin;
		// add menu & global admin css
		$this->add_hooks();
		// Init the Dashboard page (also creates the main menu item, all other menu items will be sub menu items of this one)
		$this->page_dashboard = new Page_Dashboard( $this->plugin );
		//init the Settings page
		$this->page_settings = new Page_Settings( $this->plugin );
		// init the Pixel page
		$this->page_pixels = new Page_Pixels( $this->plugin );
		// Init the Messages List / Posts with Pixels page
		$this->page_messages = new Page_Messages( $this->plugin );
		// Init the Create Message page
		$this->page_message = new Page_Message( $this->plugin );
		// Init the Participant page
		$this->page_participant = new Page_Participants( $this->plugin );

	}

	/**
	 * Register the stylesheets for the admin area
	 *
	 * @return void
	 */
	public function enqueue_styles(): void {
		wp_enqueue_style( 'vgw-metis-admin-css', $this->plugin->locations['url'] . 'admin/css/vgw-metis-admin.css' );
	}

	/**
	 * saves the screen options for the plugin admin pages
	 *
	 * @param $screen_option
	 * @param $option
	 * @param $value
	 *
	 * @return mixed
	 */
	public static function set_screen( $screen_option, $option, $value ): mixed {
		return $value;
	}

	/**
	 * Adds all hooks for the admin class
	 *
	 * @return void
	 */
	private function add_hooks(): void {
		// add save screen options hook
		add_filter( 'set-screen-option', [ $this, 'set_screen' ], 10, 3 );
		// add JS
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_script' ] );
		// add CSS
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		// add extra plugin colums to posts overview table
		add_filter( 'manage_post_posts_columns', [ $this, 'manage_post' ] );
		// add extra plugin colums to pages overview table
		add_filter( 'manage_page_posts_columns', [ $this, 'manage_page' ] );
		// add bulk actions to  posts overview
		add_filter( 'bulk_actions-edit-post', [ $this, 'define_bulk_actions' ] );
		// add bulk action handler for posts
		add_filter( 'handle_bulk_actions-edit-post', [ $this, 'handle_bulk_actions' ], 10, 3 );
		// add bulk actions to  pages overview
		add_filter( 'bulk_actions-edit-page', [ $this, 'define_bulk_actions' ] );
		// add bulk action handler for pages
		add_filter( 'handle_bulk_actions-edit-page', [ $this, 'handle_bulk_actions' ], 10, 3 );
		// add content to the custom plugin columns in posts overview
		add_action( 'manage_post_posts_custom_column', [ $this, 'manage_post_content' ], 10, 2 );
		// add content to the custom plugin columns in pages overview
		add_action( 'manage_page_posts_custom_column', [ $this, 'manage_page_content' ], 10, 2 );
		// intercept save post and add plugin related save
		add_action( 'save_post', [ $this, 'save_post' ], 10, 3 );
		// adsd save post for gutenberg editor
		add_action( 'wp_ajax_gutenberg_save_post', [ $this, 'gutenberg_save_post' ] );
		// register participant after WordPress user update
		add_action( 'user_register', [ $this, 'participant_upsert' ], 10, 2 );

		// update participant from user
		add_action( 'profile_update', [ $this, 'participant_user_updated' ], 10, 3 );

		// delete participant after WordPress user is deleted
		add_action( 'delete_user', [ $this, 'participant_delete' ], 10, 1 );

		// schedule hook for timing
		add_filter( 'cron_schedules', [ $this, 'add_cron_jobs' ] );
		// schedule and call the cron hook for every day
		add_action( 'do_cron_hook', [ $this, 'do_cron_call' ] );
		// add delete hook to disable pixel
		add_action( 'delete_post', '\WP_VGWORT\Services::disable_pixel_by_post_id', 10, 2 );

		if ( ! wp_next_scheduled( 'do_cron_hook' ) ) {
			wp_schedule_event( time(), 'everyday', 'do_cron_hook' );
		}
		// init metaboxes > load needed actions
		new Metabox( $this->plugin );
	}


	/**
	 * load the corresponding script
	 *
	 * @return void
	 */
	public function enqueue_script(): void {
		// TODO Move is gutenberg active to services
		if ( Metabox::is_gutenberg_active() ) {
			// enqueue script
			wp_enqueue_script( 'wp_metis_gutenberg_script', plugin_dir_url( __FILE__ ) . '../admin/js/gutenberg.js', [ 'jquery' ] );
			wp_localize_script(
				'wp_metis_gutenberg_script',
				'wp_metis_gutenberg_obj',
				[
					'gutenberg_not_loaded' => esc_html__( 'Der Gutenberg Editor ist nicht aktiviert / geladen.', 'vgw-metis' ),
				]
			);

		}
	}

	/**
	 * add extra plugin columns to posts overview table
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function manage_post( $columns ): array {
		return array_merge( $columns, $this->create_custom_columns() );
	}

	/**
	 * add extra plugin columns to pages overview table
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function manage_page( $columns ): array {
		return array_merge( $columns, $this->create_custom_columns() );
	}

	/**
	 * fill posts overview with custom plugin columns
	 *
	 * @param $column_key
	 * @param $post_id
	 *
	 * @return void
	 */
	public function manage_post_content( $column_key, $post_id ): void {
		$metis_data = $this->get_pixel_by_post_id( $post_id );
		$this->fill_custom_columns( $column_key, $metis_data, $post_id );
	}

	/**
	 * fill pages overview with custom plugin columns
	 *
	 * @param $column_key
	 * @param $post_id
	 *
	 * @return void
	 */
	public function manage_page_content( $column_key, $post_id ): void {
		$metis_data = $this->get_pixel_by_post_id( $post_id );
		$this->fill_custom_columns( $column_key, $metis_data, $post_id );
	}

	/**
	 * helper function to get pixel data by post id
	 *
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function get_pixel_by_post_id( $post_id ): mixed {
		// TODO why not get_pixel_by_postid? Can this be done more efficient / simpler?
		$all_pixels = DB_Pixels::get_all_pixels();
		$key        = array_search( $post_id, array_column( $all_pixels, 'post_id' ) );

		if ( $key !== false ) {
			return $all_pixels[ $key ];
		} else {
			return $this->initialize_custom_columns();
		}
	}

	/**
	 * get the column headers of the plugins custom columns for post type list view
	 *
	 * @return array of columns to be added
	 */
	public function create_custom_columns(): array {
		$metis_columns['assigned']                  = esc_html__( 'Status', 'vgw-metis' );
		$metis_columns['public_identification_id']  = esc_html__( 'Öffentlicher Identifikationscode', 'vgw-metis' );
		$metis_columns['private_identification_id'] = esc_html__( 'Privater Identifikationscode', 'vgw-metis' );
		$metis_columns['text_type']                 = esc_html__( 'Textart', 'vgw-metis' );
		$metis_columns['char_count']                = esc_html__( 'Anzahl Zeichen', 'vgw-metis' );

		return $metis_columns;
	}

	/**
	 * get default values for custom plugin columns
	 *
	 * @return array
	 */
	public function initialize_custom_columns(): array {
		$metis_data["assigned"]                  = null;
		$metis_data["public_identification_id"]  = "";
		$metis_data["private_identification_id"] = "";
		$metis_data["text_type"]                 = "";
		$metis_data["char_count"]                = "";
		$metis_data["active"]                    = null;
		$metis_data["disabled"]                  = false;

		return $metis_data;
	}

	/**
	 * fill the custom plugin columns with the mapped pixel data
	 *
	 * @param $column_key
	 * @param $metis_data
	 * @param $post_id
	 *
	 * @return void
	 */
	public function fill_custom_columns( $column_key, $metis_data, $post_id ): void {
		switch ( $column_key ) {
			case 'assigned':
				esc_html_e( List_Table_Pixels::get_state_label( $metis_data['assigned'], $metis_data['active'], $metis_data['disabled'] ) );

				break;
			case 'public_identification_id':
				if ( $metis_data['assigned'] && $metis_data['active'] ) {
					esc_html_e( $metis_data['public_identification_id'] );
				} else {
					echo '';
				}
				break;
			case 'private_identification_id':
				if ( $metis_data['assigned'] && $metis_data['active'] ) {
					esc_html_e( $metis_data['private_identification_id'] );
				} else {
					echo '';
				}
				break;
			case 'text_type':
				$text_type = get_post_meta( $post_id, '_metis_text_type', true );
				if ( isset( $text_type ) ) {
					esc_html_e( ucfirst( $text_type ) );
				} else {
					echo '-';
				}
				break;
			case 'char_count':
				$text_length = get_post_meta( $post_id, '_metis_text_length', true );
				if ( isset( $text_length ) ) {
					esc_html_e( ucfirst( $text_length ) );
				} else {
					echo '-';
				}
				break;
			default:
				echo '';
		}
	}


	/**
	 * Defines Bulk Actions for post and page Table
	 *
	 * @return array bulk actions
	 */
	public function define_bulk_actions( $bulk_actions ): array {
		$bulk_actions['assign_pixels']   = esc_html__( 'Zählmarken zuweisen', 'vgw-metis' );
		$bulk_actions['unassign_pixels'] = esc_html__( 'Zählmarken entfernen', 'vgw-metis' );

		return $bulk_actions;
	}

	/**
	 * to allow for static call in tests (manually trigger bulk action)
	 *
	 */
	public static function handle_bulk_actions_static( string $redirect_url, string $action, array $post_ids ): string {

		if ( $action == 'assign_pixels' ) {
			$order_result = Services::order_pixels_if_needed( count( $post_ids ) );

			if ( $order_result === false ) {
				// TODO Show Error Notice
			}

			foreach ( $post_ids as $post_id ) {
				Services::assign_pixel_to_post( $post_id );
				Services::set_text_type( $post_id );
				Services::set_text_length( $post_id );
				Services::check_post_and_save_text_length_change( $post_id );
			}
		}
		if ( $action == 'unassign_pixels' ) {
			foreach ( $post_ids as $post_id ) {
				Services::unassign_pixel_from_post( $post_id );
			}
		}

		return $redirect_url;
	}

	/**
	 * Handels the bulk action for assigning and unassigning the metis pixel
	 *
	 * @param string $redirect_url
	 * @param string $action
	 * @param array $post_ids
	 *
	 * @return string
	 */
	public function handle_bulk_actions( string $redirect_url, string $action, array $post_ids ): string {
		return self::handle_bulk_actions_static( $redirect_url, $action, $post_ids );
	}

	/**
	 * On save for post and page the Metis Type will be set and the length of the content will be calculated
	 * and set to metadata, also check if we need to create a new text limit change record
	 *
	 * @param int $post_id
	 * @param \WP_Post $post
	 * @param bool $update
	 * @param \WP_Post|null $post_before
	 *
	 * @return void
	 */
	public function save_post( int $post_id ): void {
		// set text type accordingly
		Services::set_text_type( $post_id );
		// set text length accordingly
		Services::set_text_length( $post_id );

		// check if we need to create a new text limit change record
		$current_text_length = (int) get_post_meta( $post_id, "_metis_text_length", true );
		$pixel               = Db_Pixels::get_pixel_by_post_id( $post_id );
		if ( is_object( $pixel ) ) {
			$public_identification_id = $pixel->public_identification_id;
		} else {
			$public_identification_id = '';
		}

		Services::add_text_limit_change_if_needed( $post_id, $public_identification_id, $current_text_length );
	}

	/**
	 * WITH GUTENBERG EDITOR: On AJAX save for post and page the Metis Type will be set and the length of the content
	 * will be calculated and set to metadata, also check if we need to create a new text limit change record
	 *
	 * @return void
	 */
	public function gutenberg_save_post(): void {
		$post_id = (int) $_POST['post_id'];

		// set text type accordingly
		Services::set_text_type( $post_id );
		// set text length accordingly
		Services::set_text_length( $post_id );

		// check if we need to create a new text limit change record
		$current_text_length = (int) get_post_meta( $post_id, "_metis_text_length", true );
		$pixel               = Db_Pixels::get_pixel_by_post_id( $post_id );
		if ( is_object( $pixel ) ) {
			$public_identification_id = $pixel->public_identification_id;
		} else {
			$public_identification_id = '';
		}

		Services::add_text_limit_change_if_needed( $post_id, $public_identification_id, $current_text_length );

		wp_die();
	}


	/**
	 * Called wenn User was updated
	 *
	 * @param int $user_id , $old_user_data, $user_data
	 *
	 * @return void
	 */
	public function participant_user_updated( $user_id, $old_user_data, $userdata ) {
		$this->participant_upsert( $user_id, $userdata );
	}


	/**
	 * Change data (first_name, last_name) in participant when user is a participant in metis
	 *
	 * @param int $user_id , $old_user_data, $user_data
	 *
	 * @return int | null
	 */
	public function participant_upsert( $user_id, $user_data ): int|null {

		$roles = get_userdata( $user_id )->roles;
		if ( $roles != null && count( $roles ) > 0 && $roles[0] == 'subscriber' ) {
			return null;
		}

		if ( $participant = Db_Participants::get_participant_by_wp_username( $user_data["user_login"] ) ) {
			$participant->first_name = $user_data["first_name"];
			$participant->last_name  = $user_data["last_name"];

		} else {
			$participant = (object)
			[
				"first_name"  => $user_data["first_name"],
				"last_name"   => $user_data["last_name"],
				"wp_user"     => $user_data["user_login"],
				"file_number" => "",
				"involvement" => Common::INVOLVEMENT_AUTHOR
			];

		}

		return Db_Participants::upsert_participant( $participant );
	}

	/**
	 * Delete participant when a WordPress user was deleted
	 *
	 * @param int $user_id
	 *
	 * @return boolean
	 */
	public function participant_delete( $user_id ): bool {
		$user_data = get_userdata( $user_id );
		if ( $participant = Db_Participants::get_participant_by_wp_username( $user_data->data->user_login ) ) {
			return Db_Participants::delete_participant( $participant->id );
		}

		return true;
	}


	/**
	 * Schedule cron job for everyday
	 *
	 * @param $schedules
	 *
	 * @return array
	 */
	public function add_cron_jobs( $schedules ): array {
		$schedules['everyday'] = array(
			'interval' => 60 * 60 * 24, // time in seconds
			'display'  => 'Every Day'
		);

		return $schedules;
	}

	/**
	 * Will be called every day from scheduled hook
	 *
	 * @return void
	 */
	public function do_cron_call(): void {
		Services::check_all_pixels();
	}

}
