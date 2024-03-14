<?php

namespace WP_VGWORT;

/**
 * Participant Page View Class
 *
 * holds all things necessary to set up the pixel list page template
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Page_Participants extends Page {
	/**
	 * @var object instance of the participant table class
	 */
	public object $list_table_participants;

	/**
	 * constructor
	 */
	public function __construct( object $plugin ) {
		parent::__construct( $plugin );

        // add submenu item
		add_action( 'admin_menu', [$this, 'add_participants_submenu'] );

		$this->list_table_participants = new List_Table_Participants();
		$this->list_table_participants->prepare_items();

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_script' ] );
		add_action( 'wp_ajax_participant_save', [ $this, 'participant_save' ] );
		add_action( 'wp_ajax_participant_delete', [ $this, 'participant_delete' ] );

		if ( ! $this->check_all_participants_has_last_name() ) {
			add_action( 'admin_notices', [ $this, 'display_last_name_notice' ] );
		}
	}

	/**
	 * add the submenu for the participants overview
	 *
	 * @return void
	 */

	public function add_participants_submenu() {
		$page_metis_participants_hook = add_submenu_page( 'metis-dashboard', esc_html__( 'VG WORT METIS Beteiligtenübersicht', 'vgw-metis' ), esc_html__( 'Beteiligte', 'vgw-metis' ), 'manage_options', 'metis-participant', array(
			$this,
			'render'
		), 5 );
	}

	/**
	 * Loads the template of the view > render page
	 *
	 * @return void
	 */
	public function render(): void {
		$this->plugin->notifications->display_notices();
		$this->list_table_participants->read_data();
		require_once 'partials/participants.php';
	}

	/**
	 * load script for metis list table
	 *
	 * @return void
	 */
	public function enqueue_script(): void {

		wp_enqueue_script( 'wp_metis_list_table_script', plugin_dir_url( __FILE__ ) . '../admin/js/list-table.js', [ 'jquery' ] );
		wp_localize_script(
			'wp_metis_list_table_script',
			'wp_metis_list_table_obj',
			[
				'columns'       => $this->list_table_participants->get_json_columns(),
				'upsert_action' => "participant_save",
				'delete_action' => "participant_delete"
			]
		);

	}

	/**
	 * upsert participant if id is given it will update otherwise will insert
	 *
	 * @return int | null
	 */
	public function participant_save(): int|null {
		$wp_user          = true;
		$return_value     = false;
		$participant_data = (object) $_REQUEST['data'];

		if ( $return_value = Db_Participants::upsert_participant( $participant_data ) ) {
			if ( $participant_data->wp_user != '' ) {
				if ( $wp_user = get_user_by( 'login', $participant_data->wp_user ) ) {
					$wp_user = wp_update_user( [
						'ID'         => $wp_user->ID,
						'first_name' => $participant_data->first_name,
						'last_name'  => $participant_data->last_name
					] );
				}
			}
		}

		return $return_value && $wp_user;
	}

	/**
	 * will delete participant with given id
	 *
	 * @return int|null
	 */
	public function participant_delete( bool $force_delete = false ): int|null {
		$id =  (int) $_REQUEST['id'] ;
		if ( $force_delete == false ) {
			$participant = Db_Participants::get_participant_by_id( $id );
			// Participant which comes from wp user only can be deleted
			// by deleting wordpress user
			if ( $participant->wp_user != '' ) {
				return wp_send_json_error( [ 'message' => esc_html__( 'Beteiligte mit Benutzernamen können nur über Wordpress Benutzer gelöscht werden!', 'vgw-metis' ) ] );
			}
		}

		return Db_Participants::delete_participant( $id );
	}

	/**
	 * check if all participants has a last name
	 *
	 * @return int|null
	 */
	public function check_all_participants_has_last_name(): bool|int|null {
		if ( ( $count = Db_Participants::get_participants_with_no_last_name() ) > 0 ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * display admin warning notice: last name is missing
	 *
	 * @return void
	 */
	public function display_last_name_notice(): void {
		?>
		<div class="notice notice-warning is-dismissible">
			<p><?php esc_html_e( 'VG WORT METIS: In der Beteiligtenverwaltung sind WordPress-Benutzer ohne Nachnamen vorhanden. Bitte vervollständigen Sie diese über die Benutzerverwaltung von WordPress oder direkt in der Beteiligtenverwaltung!', 'vgw-metis' ); ?></p>
		</div>
		<?php
	}


}

