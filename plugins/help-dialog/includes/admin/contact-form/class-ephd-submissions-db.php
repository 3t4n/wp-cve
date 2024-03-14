<?php  // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * CRUD for Help Dialog submissions data
 *
 * @property string primary_key
 * @property string table_name
 */
class EPHD_Submissions_DB extends EPHD_DB {

	const PER_PAGE = 20;

	const STATUS_EMAIL_PENDING = 'email_pending';
	const STATUS_EMAIL_SENT = 'email_sent';
	const STATUS_EMAIL_ERROR = 'email_error';

	const NOTIFICATION_STATUS_ERROR = 'error';

	const NOTIFICATION_STATUS_NO_EMAIL = 'no_submission_email';

	const PRIMARY_KEY = 'submission_id';

	/**
	 * Maximum length of public fields
	 */
	 /** ! ===== >  changes will require upgrade to the table **/
	const PAGE_NAME_LENGTH = 200;  // this has the same length as 'name' field inside the WordPress terms table
	const NAME_LENGTH = 50;
	const EMAIL_LENGTH = 50;
	const SUBJECT_LENGTH = 100;
	const COMMENT_LENGTH = 3000;
	const NOTIFICATION_DETAILS_LENGTH = 500;

	/**
	 * Get things started
	 *
	 * @access  public
	 */
	public function __construct() {

		parent::__construct();

		global $wpdb;

		$this->table_name  = $wpdb->prefix . 'ephd_submissions';
		$this->primary_key = 'submission_id';
	}

	/**
	 * Get columns and formats
	 *
	 * @access  public
	 */
	public function get_column_format() {
		return array(
			'submission_id'         => '%d',
			'page_id'               => '%d',
			'page_name'             => '%s',
			'submit_date'           => '%s',
			'name'                  => '%s',
			'email'                 => '%s',
			'subject'               => '%s',
			'comment'               => '%s',
			'status'                => '%s',
			'notification_status'   => '%s',
			'notification_details'  => '%s',
			'user_ip'               => '%s',
		);
	}

	/**
	 * Get default column values
	 *
	 * @return array
	 */
	public function get_column_defaults() {
		return array(
			'page_id'               => 0,
			'page_name'             => '',
			'submit_date'           => date('Y-m-d H:i:s'),
			'name'                  => '',
			'email'                 => '',
			'subject'               => '',
			'comment'               => '',
			'status'                => '',
			'notification_status'   => '',
			'notification_details'  => '',
			'user_ip'               => '',
		);
	}

	/**
	 * Insert a new submission record
	 *
	 * @param $page_id
	 * @param $page_name
	 * @param $submit_date
	 * @param $name
	 * @param $email
	 * @param $subject
	 * @param $comment
	 * @param $status
	 * @param $notification_status
	 * @param $notification_details
	 * @param $user_ip
	 *
	 * @return int|WP_Error
	 */
	public function insert_submission( $page_id, $page_name, $submit_date, $name, $email, $subject, $comment, $status, $notification_status, $notification_details, $user_ip ) {

		// insert the record
		$record = array(
			'page_id'               => $page_id,
			'page_name'             => $page_name,
			'submit_date'           => $submit_date,
			'name'                  => $name,
			'email'                 => $email,
			'subject'               => $subject,
			'comment'               => $comment,
			'status'                => $status,
			'notification_status'   => $notification_status,
			'notification_details'  => $notification_details,
			'user_ip'               => $user_ip,
		);

		$submission_id = parent::insert_record( $record );
		if ( empty( $submission_id ) ) {
			return new WP_Error( 'db-insert-error', 'Could not insert Help Dialog submission record' );
		}

		return $submission_id;
	}

	/**
	 * Update submission record with email status details
	 *
	 * @param $submission_id
	 * @param $notification_status
	 * @param $notification_details
	 *
	 * @return void|WP_Error
	 */
	public function update_submission( $submission_id, $notification_status, $notification_details ) {

		// update the record
		$data = array(
			'notification_status'   => $notification_status,
			'notification_details'  => $notification_details,
		);

		$result = parent::update_record( $submission_id, $data );
		if ( empty( $result ) ) {
			return new WP_Error( 'db-update-error', 'Could not update Help Dialog submission record: ' . $submission_id );
		}

		return;
	}

	/**
	 * Get submissions
	 *
	 * @param int $page_number
	 *
	 * @return array|WP_Error
	 */
	public function get_submissions( $page_number=1 ) {

		global $wpdb;

		$page_number = empty( $page_number ) || $page_number < 1 ? 0 : $page_number;
		$offset = ( $page_number - 1 ) * self::PER_PAGE;

		$submissions = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $this->table_name ORDER BY " . self::PRIMARY_KEY . " DESC LIMIT %d, %d ", $offset, self::PER_PAGE ) );

		if ( ! empty( $wpdb->last_error ) ) {
			$wpdb_last_error = $wpdb->last_error;   // add_log changes last_error so store it first
			EPHD_Logging::add_log( "DB failure: ", $wpdb_last_error );
			return new WP_Error( 'DB failure', $wpdb_last_error );
		}

		// always return array - parent class already logs WP Error
		if ( empty( $submissions ) || is_wp_error( $submissions ) || ! is_array( $submissions ) ) {
			$submissions = [];
		}

		// filter output data of submissions
		return self::filter_output( $submissions );
	}

	/**
	 * Get Submissions count by status
	 *
	 * @param $status
	 *
	 * @return array
	 */
	public function get_submissions_count_by_status( $status=false ) {

		if ( empty( $status ) ) {
			$status = self::STATUS_EMAIL_PENDING;
		}

		$success_count = $this->get_number_of_rows_by_where_clause( array(
			'status'              => $status,
			'notification_status' => array(
				'value'    => self::NOTIFICATION_STATUS_ERROR,
				'operator' => '!='
			)
		) );

		$error_count = $this->get_number_of_rows_by_where_clause( array(
			'status'              => $status,
			'notification_status' => self::NOTIFICATION_STATUS_ERROR
		) );

		return array(
			'success' => $success_count,
			'error'   => $error_count,
			'total'   => $success_count + $error_count
		);

	}

	/**
	 * Delete a submission by primary key
	 *
	 * @param $primary_key
	 * @return bool
	 */
	public function delete_submission( $primary_key ) {
		return $this->delete_record( $primary_key );
	}

	/**
	 * Delete all submissions
	 *
	 * @return bool
	 */
	public function delete_all_submissions() {
		return $this->clear_table();
	}

	/**
	 * Get list of submission's fields with their translated titles which need to display as columns
	 *
	 * @return array
	 */
	public static function get_submission_column_fields() {
		return [
			'submit_date'           => __( 'Date', 'help-dialog' ),
			'name'                  => __( 'Name', 'help-dialog' ),
			'email'                 => __( 'Email', 'help-dialog' ),
			'page_name'             => __( 'Page Name', 'help-dialog' ),
			'notification_status'   => __( 'Notification Status', 'help-dialog' ),
		];
	}

	/**
	 * Get list of submission's fields with their translated titles which need to display as rows
	 *
	 * @return array
	 */
	public static function get_submission_row_fields() {
		return [
			'subject' => __( 'Subject', 'help-dialog' ),
			'comment' => __( 'Comment', 'help-dialog' ),
		];
	}

	/**
	 * Get list of submission's optional fields with their translated titles which need to display as rows
	 *
	 * @return array
	 */
	public static function get_submission_optional_row_fields() {
		return [
			'notification_details' => __( 'Notification Details', 'help-dialog' ),
		];
	}

	/**
	 * Get list of submission's notification statuses with their translated titles which need to display
	 *
	 * @return array
	 */
	public static function get_submission_notification_statuses() {
		return [
			'error'               => __( 'Error', 'help-dialog' ),
			'sent'                => __( 'Sent', 'help-dialog' ),
			'received'            => __( 'Submission Received', 'help-dialog' ),
			'no_submission_email' => __( 'No Destination Email', 'help-dialog' ),
		];
	}

	/**
	 * Get total number of submissions
	 *
	 * @return int
	 */
	public function get_total_number_of_submissions() {
		return $this->get_number_of_rows();
	}

	/**
	 * Update page name from  old value to new value
	 *
	 * @param $old_name
	 * @param $new_name
	 */
	public function update_page_name( $old_name, $new_name ) {
		global $wpdb;

		$result = $wpdb->update(
			$this->table_name,
			array( 'page_name' => $new_name ),
			array( 'page_name' => $old_name ),
			array( '%s' ),
			array( '%s' )
		);
		if ( $result === false && ! empty( $wpdb->last_error ) ) {
			$wpdb_last_error = $wpdb->last_error;
			EPHD_Logging::add_log( "DB failure: ", $wpdb_last_error );
		}
	}

	/**
	 * Filter output of Submissions
	 *
	 * @param $submissions
	 *
	 * @return array
	 */
	private function filter_output( $submissions ) {

		$global_config = ephd_get_instance()->global_config_obj->get_config();

		$notification_statuses = self::get_submission_notification_statuses();

		$show_settings_url = true;

		foreach ( $submissions as $index => $data ) {

			// change format of showing submissions datetime
			$submissions[$index]->submit_date = date( 'M j, Y g:ia', strtotime( $data->submit_date ) );

			// Change notification status
			$notification_status = $data->notification_status;
			if ( isset( $notification_statuses[$notification_status] ) ) {
				$submissions[$index]->notification_status = $notification_statuses[$notification_status];
			} else {
				$submissions[$index]->notification_status = $notification_status;
			}
			if ( $show_settings_url && $notification_status == self::NOTIFICATION_STATUS_NO_EMAIL && empty( $global_config['contact_submission_email'] ) ) {
				$submissions[$index]->notification_status .= '<div><a href="#" data-target="contact-form-setup" class="ephd-admin__step-cta-box__link">' . __( 'Click here to update settings', 'help-dialog' ) . '</div>';
				$show_settings_url = false;
			}

		}

		return $submissions;
	}

	/**
	 * Create the table
	 *
	 * @access  public
	 */
	public function create_table() {
		global $wpdb;

		$collate = $wpdb->has_cap( 'collation' ) ? $wpdb->get_charset_collate() : '';

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (
	                submission_id 		BIGINT(20) NOT NULL AUTO_INCREMENT,
	                submit_date 	    datetime NOT NULL,
	                page_id             SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0,
	                page_name 		    VARCHAR(" . self::PAGE_NAME_LENGTH . ") NOT NULL,
	                name 	    		VARCHAR(" . self::NAME_LENGTH . ") NOT NULL,
	                email   			VARCHAR(" . self::EMAIL_LENGTH . ") NOT NULL,
	                subject 			VARCHAR(" . self::SUBJECT_LENGTH . ") NOT NULL,
	                comment 			VARCHAR(" . self::COMMENT_LENGTH . ") NOT NULL,
	                status 				VARCHAR(50) NOT NULL,
	                notification_status	VARCHAR(50) NOT NULL,
	                notification_details VARCHAR(" . self::NOTIFICATION_DETAILS_LENGTH . ") NOT NULL,
	                user_ip 			VARCHAR(50) NOT NULL,
	                PRIMARY KEY (submission_id),               
	                KEY ix_ephd_date (submit_date)
		) " . $collate . ";";

		dbDelta( $sql );
	}
}