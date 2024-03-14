<?php

namespace WP_VGWORT;

use GuzzleHttp\Psr7\Request;

/**
 * Create Message Page View Class
 *
 * shows a form to submit a message
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Page_Message extends Page {
	/**
	 * @var string any given warning message
	 */
	public string $warning_message = '';

	/**
	 * @var string any given error message
	 */
	public string $error_message = '';

	/**
	 * @var int given post id
	 */
	public int $post_id = 0;

	/**
	 * @var Pixel | null pixel according to post id
	 */
	public Pixel|null $pixel = null;


	/**
	 * @var array|null array of participants
	 */
	public array|null $participants = null;

	/**
	 * @var object | null participant data of current logged in user
	 */
	public object|null $current_user_as_participant = null;


	/**
	 * constructor
	 */
	public function __construct( object $plugin ) {
		parent::__construct( $plugin );

		// add submenu item
		add_action( 'admin_menu', [ $this, 'add_message_submenu' ] );

		// load needed js
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_script' ] );

		// add save message hook
		add_action( 'admin_post_wp_metis_save_message', [ $this, 'save_message' ] );
	}

	/**
	 * add the invisible submenu for the create message page (in order to have a page name we can link to)
	 *
	 * @return void
	 */

	public function add_message_submenu() {
		add_submenu_page( 'metis-messages', esc_html__( 'VG WORT METIS Meldung erstellen', 'vgw-metis' ), esc_html__( 'Meldung erstellen', 'vgw-metis' ), 'manage_options', 'metis-message', array(
			$this,
			'render'
		) );


	}

	/**
	 * load all the data needed for displaying the form and check for errors
	 *
	 * @return void
	 */
	public function load_data(): void {
		// get post id
		$this->post_id = empty( $_REQUEST['post_id'] ) ? 0 : (int) $_REQUEST['post_id'];

		// check if we have a post id
		if ( $this->post_id === 0 ) {
			$this->error_message = 'Es wurde keine Beitrags-ID angegeben.';

			return;
		}

		// load current users participant data
		$this->current_user_as_participant = Db_Participants::get_participant_by_wp_username( wp_get_current_user()->user_login );

		// load participants
		$this->participants = Db_Participants::get_all_participants();

		if ( empty( $this->participants ) ) {
			$this->error_message = 'Beteiligtenliste konnte nicht geladen werden.';

			return;
		}

		// check if we have at least one text limit change
		$post_has_text_limit_changes = Services::post_has_text_limit_changes( $this->post_id );

		if ( $post_has_text_limit_changes === false ) {
			$this->error_message = 'Es ist keine Historie zu Textlängenänderungen vorhanden. Versuchen Sie den Text noch einmal zu speichern.';

			return;
		}

		// load pixel and check if message can be submitted
		$this->pixel = new Pixel( Db_Pixels::get_pixel_by_post_id( $this->post_id ) );

		if ( empty( $this->pixel ) || ! $this->pixel->public_identification_id || ! $this->pixel->private_identification_id ) {
			$this->error_message = 'Keine Zählmarke und/oder kein Beitrag zur ID gefunden.';

			return;
		} else {
			$this->pixel->text_type   = Common::sanitize_text_type( get_post_meta( $this->post_id, '_metis_text_type', true ) );
			$this->pixel->text_length = (int) get_post_meta( $this->post_id, '_metis_text_length', true );

			if ( Common::get_text_message_state( $this->pixel ) !== Common::STATE_MESSAGE_NOT_REPORTED ) {
				$this->error_message = 'Dieser Beitrag ist nicht meldefähig.';

				return;
			}
		}

	}

	/**
	 * Loads the template of the view > render page
	 *
	 * @return void
	 */
	public function render(): void {
		$this->plugin->notifications->display_notices();

		$this->load_data();

		$this->show_text_length_limit_warning();

		if ( $this->error_message ) {
			require_once 'partials/message_error.php';
		} else {
			require_once 'partials/message.php';
		}

		$this->plugin->notifications->empty_notifications();
	}


	/**
	 * sets text limit warning messages for the view
	 *
	 * @return void
	 */
	public function show_text_length_limit_warning(): void {
		if ( Services::post_has_text_limit_changes( $this->post_id ) === false ) {
			return;
		}
		$text_length_limits  = Services::post_has_reached_text_limit_with_latest_pid( $this->post_id );
		$current_text_length = Services::calculate_post_text_length( $this->post_id );

		if ( $current_text_length < Common::DEFAULT_TEXT_LENGTH_MIN ) {
			// this case should have been prevented before (by not ever showing the create message button), better safe than sorry :)
			$this->warning_message = 'Der Text ist zu kurz für eine Meldefähigkeit.';
		} else if ( $current_text_length < Common::LONG_TEXT_LENGTH_MIN ) {
			if ( $text_length_limits->default_text_limit_reached !== true ) {
				$this->warning_message = "Achtung: Der vorliegende Text befand sich für mehr als 50% des Zeitraums seit der Zuweisung der Zählmarke ($text_length_limits->period_start_date) unterhalb der melderelevanten Textlänge (" . Common::DEFAULT_TEXT_LENGTH_MIN . " Zeichen). Beachten Sie, dass dies gegebenenfalls eine Auswirkung auf die Bestätigung Ihrer Meldung haben könnte.";
			}
		} else {
			if ( $text_length_limits->long_text_limit_reached !== true ) {
				$this->warning_message = "Achtung: Der vorliegende Text befand sich für mehr als 50% des Zeitraums seit der Zuweisung der Zählmarke ($text_length_limits->period_start_date) unterhalb der melderelevanten Textlänge (" . Common::LONG_TEXT_LENGTH_MIN . " Zeichen). Beachten Sie, dass dies gegebenenfalls eine Auswirkung auf die Bestätigung Ihrer Meldung haben könnte.";
			}
		}
	}

	/**
	 * load the corresponding script
	 *
	 * @return void
	 */
	public function enqueue_script(): void {
		$screen = get_current_screen();

		if ( ! empty( $screen ) && $screen->id === 'admin_page_metis-message' ) {
			// enqueue script
			wp_enqueue_script( 'wp_metis_create_message', plugin_dir_url( __FILE__ ) . '../admin/js/create-message.js', [ 'jquery' ] );
			wp_localize_script(
				'wp_metis_create_message',
				'wp_metis_create_message_obj',
				[
					'prompt_add_url'          => esc_html__( 'Bitte geben Sie eine weitere URL ein.', 'vgw-metis' ),
					'msg_no_valid_url'        => esc_html__( 'Die eingegebene URL ist ungültig. (Die URL muss u.a. mit http:// oder https:// beginnen)!', 'vgw-metis' ),
					'msg_remove_url'          => esc_html__( 'URL entfernen', 'vgw-metis' ),
					'option_author'           => esc_html__( 'Autor', 'vgw-metis' ),
					'option_translator'       => esc_html__( 'Übersetzer', 'vgw-metis' ),
					'option_verlag'           => esc_html__( 'Verlag', 'vgw-metis' ),
					'author'                  => Common::INVOLVEMENT_AUTHOR,
					'translator'              => Common::INVOLVEMENT_TRANSLATOR,
					'publisher'               => Common::INVOLVEMENT_PUBLISHER,
					'msg_at_least_one_author' => esc_html__( 'Fehler: Es muss mindestens ein Autor zur Meldung angegeben werden.', 'vgw-metis' )
				]
			);

		}
	}

	/**
	 * checks, validates and transforms all needed data for submitting a message to a single object
	 *
	 * @return void
	 */
	public function save_message(): void {
		// get post id
		$this->post_id = empty( $_REQUEST['post_id'] ) ? 0 : (int) $_REQUEST['post_id'];

		// check form nonce
		if ( empty( $_POST['message-form-nonce'] )
		     || ! wp_verify_nonce( $_POST['message-form-nonce'], 'wp_metis_save_message' )
		) {
			wp_redirect( admin_url( 'admin.php?page=metis-message&post_id=' . $this->post_id . '&notice=wp_metis_save_message_nonce_error' ) );
		}

		// sanitize form post data
		$form_data                            = new \stdClass();
		$form_data->private_identification_id = empty( $_POST['private_identification_id'] ) ? null : sanitize_key( $_POST['private_identification_id'] );
		$form_data->text_type                 = empty( $_POST['text_type'] ) ? null : sanitize_key( $_POST['text_type'] );
		$form_data->text_length               = empty( $_POST['text_length'] ) ? null : (int) $_POST['text_length'];
		$form_data->permalink                 = empty( $_POST['permalink'] ) ? null : $_POST['permalink'];
		// for dev environment: localhost wont be accepted as permalink domain ...
		if ( defined( 'PRIORIT_DEBUG' ) ) {
			if ( PRIORIT_DEBUG === true ) {
				$form_data->permalink = empty( $_POST['permalink'] ) ? null : 'https://www.debug.vgwort/post-1';
			}
		}
		$form_data->text  = empty( $_POST['text'] ) ? null : base64_encode( sanitize_text_field( $_POST['text'] ) );
		$form_data->title = empty( $_POST['title'] ) ? null : sanitize_text_field( $_POST['title'] );

		$form_data->urls = [ $form_data->permalink ];
		if ( ! empty( $_POST['urls'] ) && is_array( $_POST['urls'] ) && count( $_POST['urls'] ) ) {
			foreach ( $_POST['urls'] as $url ) {
				$form_data->urls[] = sanitize_url( $url );
			}
		}

		if ( empty( $_POST['participants'] ) || ! is_array( $_POST['participants'] ) || ! count( $_POST['participants'] ) ) {
			$form_data->participants = [];
		} else {
			$form_data->participants = [];
			foreach ( $_POST['participants'] as $participant ) {
				$participant = json_decode( stripcslashes( $participant ) );

				$sanitized_participant              = new \stdClass();
				$sanitized_participant->id          = (int) $participant->id;
				$sanitized_participant->first_name  = sanitize_text_field( $participant->first_name );
				$sanitized_participant->last_name   = sanitize_text_field( $participant->last_name );
				$sanitized_participant->file_number = (int) $participant->file_number;
				$sanitized_participant->involvement = sanitize_key( $participant->involvement );

				$form_data->participants[] = $sanitized_participant;
			}
		}

		// check if at least one author
		$has_author = false;
		foreach ( $form_data->participants as $participant ) {
			if ( strtoupper( $participant->involvement ) === Common::INVOLVEMENT_AUTHOR ) {
				$has_author = true;
			}
		}

		if ( ! $has_author ) {
			wp_redirect( admin_url( 'admin.php?page=metis-message&post_id=' . $this->post_id . '&notice=wp_metis_save_message_no_author' ) );
		}

		// check if it has title
		if ( empty( $form_data->title ) ) {
			wp_redirect( admin_url( 'admin.php?page=metis-message&post_id=' . $this->post_id . '&notice=wp_metis_save_message_no_title' ) );
		}

		// check if it has text
		if ( empty( $form_data->text ) ) {
			wp_redirect( admin_url( 'admin.php?page=metis-message&post_id=' . $this->post_id . '&notice=wp_metis_save_message_no_text' ) );
		}

		// check if it has text type
		if ( empty( $form_data->text_type ) || ! ( strtolower( $form_data->text_type ) === Common::TEXT_TYPE_LYRIC || strtolower( $form_data->text_type ) === Common::TEXT_TYPE_DEFAULT ) ) {
			wp_redirect( admin_url( 'admin.php?page=metis-message&post_id=' . $this->post_id . '&notice=wp_metis_save_message_no_text_type' ) );
		}

		// check if it has permalink
		if ( empty( $form_data->permalink ) ) {
			wp_redirect( admin_url( 'admin.php?page=metis-message&post_id=' . $this->post_id . '&notice=wp_metis_save_message_no_permalink' ) );
		}

		// check if it has private identification id
		if ( empty( $form_data->private_identification_id ) ) {
			wp_redirect( admin_url( 'admin.php?page=metis-message&post_id=' . $this->post_id . '&notice=wp_metis_save_message_no_private_identification_id' ) );
		}

		// prepare message data for api
		$message                          = new \stdClass();
		$message->privateidentificationid = $form_data->private_identification_id;
		$message->shorttext               = $form_data->title;
		$message->text                    = $form_data->text;
		$message->lyric                   = ! ( strtolower( $form_data->text_type ) === Common::TEXT_TYPE_DEFAULT );
		$message->participants            = [];

		foreach ( $form_data->participants as $participant ) {
			// add all but the message creator to the participants array
			if ( get_current_user_id() === $participant->id ) {
				// message creator will be identified by api key sent with the request
				$message->involvement = strtoupper( $participant->involvement );
			} else {
				$new_participant              = new \stdClass();
				$new_participant->firstName   = $participant->first_name;
				$new_participant->surName     = $participant->last_name;
				$new_participant->cardNumber  = $participant->file_number ? $participant->file_number : null;
				$new_participant->involvement = strtoupper( $participant->involvement );
				$message->participants[]      = $new_participant;
			}
		}

		if ( ! empty( $form_data->urls ) && count( $form_data->urls ) ) {
			$webranges = [];

			$urls       = new \stdClass();
			$urls->urls = [];

			foreach ( $form_data->urls as $url ) {
				$urls->urls[] = $url;
			}

			$message->webranges[] = $urls;
		}

		// add text length limit changes
		$text_lenght_limits = Db_Text_Limit_Changes::get_text_limit_changes_with_lastest_pid_by_post_id( (int) $_REQUEST['post_id'] );

		$text_length_limits_api_format = [];

		if ( $text_lenght_limits && count( $text_lenght_limits ) ) {
			foreach ( $text_lenght_limits as $limit_change ) {
				$obj                             = new Text_Limit_Change();
				$date                            = new \DateTime( $limit_change['changed_at'] );
				$obj->changedAt                  = $date->format( 'Y-m-d\TH:i:s.\0\0\0' );
				$obj->textLength                 = (int) $limit_change['text_length'];
				$text_length_limits_api_format[] = $obj;
			}
		}

		$message->textLengthChanges = $text_length_limits_api_format;

		// submit message
		$this->submit_message( $message );
	}

	/**
	 * takes the message payload, sends it to the server and returns with response
	 *
	 * (either message creation date or error with message, returns false on other error)
	 *
	 * @param $payload
	 *
	 * @return void
	 */
	private function submit_message( $payload ): void {

		$error_response_status_code = 0;
		$error_response_body        = null;

		$request = new Request( "POST", "/api/cms/metis/rest/message/v1.0/save-message", [
			'headers' => [
				'Content-Type' => 'application/json',
			]
		], json_encode( $payload, JSON_UNESCAPED_SLASHES ) );

		try {
			$result = Restclient::$client->send( $request );
		} catch ( \GuzzleHttp\Exception\BadResponseException $e ) {
			$error_response_status_code = (int) $e->getCode();
			$error_response_body        = json_decode( $e->getResponse()->getBody() );
		} catch ( \Exception $e ) {

		}


		// success
		if ( ! empty( $result ) && (int) $result->getStatusCode() === 200 ) {
			$response_body = json_decode( $result->getBody()->getContents() );

			// save message creation date
			$date      = new \DateTime( $response_body->createdDate );
			$text_date = $date->format( 'Y-m-d H:i:s' );

			if ( Db_Messages::set_message_created_date( $payload->privateidentificationid, $text_date ) ) {
				wp_redirect( admin_url( 'admin.php?page=metis-messages&notice=wp_metis_save_message_success' ) );
			} else {
				wp_redirect( admin_url( 'admin.php?page=metis-messages&notice=wp_metis_save_message_success_but_creation_date_save_error' ) );
			}

			// error 400, 500 or other error
		} else {
			switch ( $error_response_status_code ) {
				// field validation error
				case 400:
					if ( is_object( $error_response_body ) && ! empty( $error_response_body->errorField && ! empty( $error_response_body->message ) ) ) {
						$custom_text = urlencode( esc_html_x( 'Feldname: ', 'vgw-metis' ) . $error_response_body->errorField . esc_html_x( ', Fehler: ', 'vgw-metis' ) . $error_response_body->message );
						wp_redirect( admin_url( 'admin.php?page=metis-message&post_id=' . $this->post_id . '&notice=wp_metis_save_message_validation_error&custom_text=' . $custom_text ) );
					} else {
						wp_redirect( admin_url( 'admin.php?page=metis-message&post_id=' . $this->post_id . '&notice=wp_metis_save_message_unknown_validation_error', 'vgw-metis' ) );
					}
					break;
				// professional error
				case 500:
					if ( ! empty( $error_response_body->message ) && ! empty( $error_response_body->message->errormsg ) ) {
						$custom_text = (int) $error_response_body->message->errorcode . '. ' . urlencode( esc_html( $error_response_body->message->errormsg ) );
						wp_redirect( admin_url( 'admin.php?page=metis-message&post_id=' . $this->post_id . '&notice=wp_metis_save_message_professional_error&custom_text=' . $custom_text ) );
					} else {
						// general error
						wp_redirect( admin_url( 'admin.php?page=metis-message&post_id=' . $this->post_id . '&notice=wp_metis_save_message_unknown_professional_error', 'vgw-metis' ) );
					}
					break;
				default:
					// general error
					wp_redirect( admin_url( 'admin.php?page=metis-message&post_id=' . $this->post_id . '&notice=wp_metis_save_message_general_error', 'vgw-metis' ) );
			}
		}
	}

	/**
	 * returns the translated label according to pixel text type
	 *
	 * @return string
	 */
	public function get_text_type_label(): string {
		return match ( strtolower( $this->pixel->text_type ) ) {
			Common::TEXT_TYPE_DEFAULT => __( 'anderer Text', 'vgw-metis' ),
			Common::TEXT_TYPE_LYRIC => __( 'Lyrik', 'vgw-metis' ),
			default => Common::TEXT_TYPE_EMPTY,
		};
	}

	/**
	 * register all notifications for messages  (called in plugin bootstrap)
	 *
	 * @param Notifications $notifications
	 *
	 * @return void
	 */
	public static function register_notifications( Notifications &$notifications ): void {
		$notifications->add_notice_by_key( 'wp_metis_save_message_nonce_error', esc_html__( 'Fehler: Unsichere Formularübermittlung.', 'vgw-metis' ) );
		$notifications->add_notice_by_key( 'wp_metis_save_message_no_author', esc_html__( 'Fehler: Es muss mindestens ein Autor zu einer Meldung angegeben werden.', 'vgw-metis' ) );
		$notifications->add_notice_by_key( 'wp_metis_save_message_no_title', esc_html__( 'Fehler: Es muss Text-Titel zu einer Meldung angegeben werden.', 'vgw-metis' ) );
		$notifications->add_notice_by_key( 'wp_metis_save_message_no_text', esc_html__( 'Fehler: Es muss Text zu einer Meldung angegeben werden.', 'vgw-metis' ) );
		$notifications->add_notice_by_key( 'wp_metis_save_message_no_text_type', esc_html__( 'Fehler: Es muss ein gültiger Text-Typ zu einer Meldung angegeben werden.', 'vgw-metis' ) );
		$notifications->add_notice_by_key( 'wp_metis_save_message_no_permalink', esc_html__( 'Fehler: Es muss ein Permalink zu einer Meldung angegeben werden.', 'vgw-metis' ) );
		$notifications->add_notice_by_key( 'wp_metis_save_message_no_private_identification_id', esc_html__( 'Fehler: Privater Identifikationsschlüssel zur Meldung fehlt.', 'vgw-metis' ) );
		$notifications->add_notice_by_key( 'wp_metis_save_message_validation_error', esc_html__( 'Validierungsfehler: ', 'vgw-metis' ) );
		$notifications->add_notice_by_key( 'wp_metis_save_message_unknown_validation_error', esc_html__( 'Ein unbekannter Validierungsfehler ist aufgetreten.', 'vgw-metis' ) );
		$notifications->add_notice_by_key( 'wp_metis_save_message_professional_error', esc_html__( 'Meldefehler: ', 'vgw-metis' ) );
		$notifications->add_notice_by_key( 'wp_metis_save_message_unknown_professional_error', esc_html__( 'Ein unbekannter fachlicher Fehler ist aufgetreten.', 'vgw-metis' ) );
		$notifications->add_notice_by_key( 'wp_metis_save_message_general_error', esc_html__( 'Ein allgemeiner Fehler bei der Meldung ist aufgetreten.', 'vgw-metis' ) );
		$notifications->add_notice_by_key( 'wp_metis_save_message_success', esc_html__( 'Meldung erfolgreich.', 'vgw-metis' ), 'success' );
		$notifications->add_notice_by_key( 'wp_metis_save_message_success_but_creation_date_save_error', esc_html__( 'Meldung erfolgreich, jedoch konnte das Meldungserstellungsdatum nicht zur Zählmarke gespeichert werden.', 'vgw-metis' ) );
	}
}
