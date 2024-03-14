<?php

/**
 * The piramid public-facing functionality of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.5.0
 *
 * @package    Courtres
 * @subpackage Courtres/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Courtres
 * @subpackage Courtres/public
 * @author     Webmühle e.U. <office@webmuehle.at>
 */
class Piramids_Public extends Courtres_Entity_Piramid {


	/**
	 * The ID of this plugin.
	 *
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The version of assets of this plugin.
	 *
	 * @var      string    $version    The current version of this plugin.
	 */
	private $assets_version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version           The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name    = $plugin_name;
		$this->version        = $version;
		$this->assets_version = $version . '.11';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 */
	public function enqueue_styles() {
	}


	/**
	 * Register the JavaScript for the public-facing side of the site.
	 */
	public function enqueue_scripts() {
	}


	/**
	 * Add the courtpiramid shortcode: [courtpyramid id="#id#" courts="1,2..."]
	 *
	 * @param  array $atts    [description]
	 */
	public function public_shortcode_courtpyramid( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'id'     => false,
				'courts' => false,
			),
			$atts,
			'courtpyramid'
		);

		$piramid = array();
		if ( $atts['id'] ) {
			$piramid_id = $atts['id'];
			$piramid    = Courtres_Entity_Piramid::get_by_id( $piramid_id );
			if ( $piramid ) {
				$piramid['players'] = Courtres_Entity_Piramids_Players::get_by_piramid_id( $piramid_id );
				$atts['piramid']    = $piramid;
			}
		}

		if ( ! $atts['courts'] ) {
			$atts['courts'] = Courtres_Public::getCourts();
		}

		// check rigths for authorized wp user
		$player_user                   = false;
		$can_create_challenge          = false; // user can create the challenge
		$needs_authorize_as_challenged = false; // to accept the challenge by direct link (from email) you need autorized as challenged user
		$user_can_accept               = false; // user can accept the challenge
		$is_accepted                   = false; // succesfully_accepted by direct link (from email)
		$accepting_challenge           = false; // the challenge for accept by direct link (from email)

		if ( is_user_logged_in() && current_user_can( 'place_reservation' ) ) {
			$player_user          = wp_get_current_user();
			$can_create_challenge = true;
		}
		$the_player = false;
		if ( $player_user ) {
			$found_key  = array_search( $player_user->ID, array_column( $piramid['players'], 'player_id' ) );
			$the_player = $found_key == false ? false : $piramid['players'][ $found_key ];
		}
		$atts['player_user'] = $player_user;
		$atts['the_player']  = $the_player;

		// accept the challenge by params redirected from email link
		$query_vars = array(
			'challenge' => get_query_var( 'challenge' ),
			'action'    => get_query_var( 'action' ),
		);
		if ( $query_vars['challenge'] && $query_vars['action'] ) {
			switch ( $query_vars['action'] ) {
				case 'accept':
					$user_can_accept     = $player_user ? Courtres_Entity_Challenges::user_can_accept( $player_user->ID ) : false;
					$challenges_class    = Courtres_Entity_Challenges::get_instance( $query_vars['challenge'] );
					$accepting_challenge = $challenges_class->get_full_data();
					if ( $user_can_accept ) {
						$res = $challenges_class->set_accepted();
						if ( $accepting_challenge && $res ) {
							$is_accepted = $res ? true : false;
						}
					} else {
						if ( $accepting_challenge && $accepting_challenge['status'] == 'created' ) {
							$needs_authorize_as_challenged = true;
						}
					}
					break;

				default:
					// code...
					break;
			}
		} else {
			$user_can_accept = $player_user ? Courtres_Entity_Challenges::user_can_accept( $player_user->ID ) : false;
		}

		// When the challenge is not accepted by the challenged player within 24 hours, the challenge is automatically deleted
		$lifetime_ts = $piramid['lifetime_ts'];
		Courtres_Entity_Challenges::delete_created_expired( $lifetime_ts );
		Courtres_Entity_Challenges::delete_accepted_expired( $lifetime_ts );

		// Set status "played" to challenges with status "scheduled" and current timestamp > challenge["end_ts"]
		Courtres_Entity_Challenges::set_played();

		ob_start();
		include 'partials/' . $this->plugin_name . '-public-piramid.php';

		wp_enqueue_style( $this->plugin_name . 'datepicker', plugin_dir_url( __FILE__ ) . 'css/piramid-public.css', array(), $this->assets_version, 'all' );
		wp_enqueue_style( $this->plugin_name . 'piramid', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.datepicker.min.css', array(), $this->assets_version, 'all' );

		wp_add_inline_style( $this->plugin_name . 'inline_piramid', plugin_dir_url( __FILE__ ) . 'css/inline_css.php' );

		// 2021-03-13, astoian - load deps before use
		wp_enqueue_script( 'jquery-ui-datepicker' );

		wp_enqueue_script(
			$this->plugin_name . 'piramid',
			plugin_dir_url( __FILE__ ) . 'js/piramid-public.js',
			array(
				'jquery',
				'jquery-ui-datepicker',
			),
			$this->assets_version,
			false
		);
		wp_localize_script(
			$this->plugin_name . 'piramid',
			$this->plugin_name . '_params',
			array(
				'ajax_url'                      => admin_url( 'admin-ajax.php' ),
				'user_can_accept'               => $user_can_accept,
				'needs_authorize_as_challenged' => $needs_authorize_as_challenged,
				'login_href'                    => wp_login_url( add_query_arg( $_GET ) ),
				'is_accepted'                   => $is_accepted,
				'accepting_challenge'           => $accepting_challenge,
				'trans'                         => array(
					'Challenge someone'           => __( 'Challenge someone', 'court-reservation' ),
					'Accepting the challenge'     => __( 'Accepting the challenge', 'court-reservation' ),
					'To comfirm the challenge you need to login as' => __( 'To comfirm the challenge you need to login as', 'court-reservation' ),
					'login'                       => __( 'login', 'court-reservation' ),
					'You have been challenged'    => __( 'You have been challenged', 'court-reservation' ),
					'You have been challenged by' => __( 'You have been challenged by', 'court-reservation' ),
					'Accept the challenge?'       => __( 'Accept the challenge?', 'court-reservation' ),
					'The challenge accepted'      => __( 'The challenge accepted', 'court-reservation' ),
					'The challenge'               => __( 'The challenge', 'court-reservation' ),
					'was succeffully acepted!'    => __( 'was succeffully acepted!', 'court-reservation' ),
					'Game Date is required'       => __( 'Game Date is required', 'court-reservation' ),
					'Game Time is required'       => __( 'Game Time is required', 'court-reservation' ),
					'Court is required'           => __( 'Court is required', 'court-reservation' ),
					'Winner is required'          => __( 'Winner is required', 'court-reservation' ),
					'Delete Challenge?'           => __( 'Delete Challenge?', 'court-reservation' ),
				),
			)
		);

		return ob_get_clean();
	}



	/**
	 * Add the courtchallenges shortcode: [courtchallenges piramid_id="id" statuses="played, closed"]
	 *
	 * @param  array $atts    [description]
	 */
	public function public_shortcode_courtchallenges( $atts ) {
		$atts = shortcode_atts(
			array(
				'title'      => __( 'Challenges', 'courtres' ),
				'piramid_id' => false,
				'statuses'   => false,
			),
			$atts,
			'courtchallenges'
		);

		if ( $atts['piramid_id'] && $atts['statuses'] ) {
			$atts['piramid'] = Courtres_Entity_Piramid::get_by_id( $atts['piramid_id'] );
			$statuses_str    = sanitize_text_field( str_replace( ' ', '', $atts['statuses'] ) );
			if ( $statuses_str ) {
				$atts['challenges'] = Courtres_Entity_Challenges::get_by_statuses( $atts['piramid_id'], explode( ',', $statuses_str ) );
			}
		}

		ob_start();
		include 'partials/' . $this->plugin_name . '-public-challenges.php';
		return ob_get_clean();
	}


	/**
	 * Create challenge from piramid-public.js
	 * Called by ajax
	 *
	 * @return
	 */
	function create_challenge() {
		$response = array(
			'request' => $_POST,
			'errors'  => array(),
			'success' => false,
		);
		if ( empty( $_POST ) || ! wp_verify_nonce( $_POST['create_challenge_nonce'], 'create_challenge' ) ) {
			$response['errors'][] = __( 'Error checking security code', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}
				$db_field = Courtres_Entity_Challenges::get_db_fields();
		$name             = isset( $_POST['name'] ) ? intval( $_POST['name'] ) : $db_field['name']['default_value'];
		$piramid_id       = isset( $_POST['piramid_id'] ) ? intval( $_POST['piramid_id'] ) : false;
		$challenger_id    = isset( $_POST['challenger_id'] ) ? intval( $_POST['challenger_id'] ) : false;
		$challenged_id    = isset( $_POST['challenged_id'] ) ? intval( $_POST['challenged_id'] ) : false;
		$piramid_url      = isset( $_POST['piramid_url'] ) ? sanitize_url( $_POST['piramid_url'] ) : home_url();
		if ( ! $piramid_id || ! $challenger_id || ! $challenged_id ) {
			$response['errors'][] = __( 'Not all required data received', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}
				$challenger_wpuser = get_user_by( 'ID', $challenger_id );
		$challenged_wpuser         = get_user_by( 'ID', $challenged_id );
		$name                      = $challenger_wpuser->display_name . ' ' . __( 'Challenge', 'court-reservation' ) . ' ' . $challenged_wpuser->display_name;

		$args = array(
			'name'             => $name,
			'piramid_id'       => ( isset( $_POST['piramid_id'] ) ? intval( $_POST['piramid_id'] ) : esc_html( $db_field['piramid_id']['default_value'] ) ),
			'challenger_id'    => $challenger_id,
			'challenged_id'    => $challenged_id,
			'piramid_url'      => $piramid_url,
			'_wp_http_referer' => esc_url( $_POST['_wp_http_referer'] ),
		);
		$res  = Courtres_Entity_Challenges::create( $args );

		// Player you challenge is already challenged by another player
		if ( $res == -1 ) {
			$response['errors'][] = __( 'Player you challenge is already challenged by another player', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}

		// Player has challenges as challenger
		if ( $res == -2 ) {
			$response['errors'][] = __( 'Challenging more than one person is not allowed', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}

		// You cannot challenge [#player_name#] yet. The cool-down phase is set to [#number_days#] days.
		if ( is_array( $res ) && isset( $res['closed_challenges'] ) ) {
			$date_format = get_option( 'date_format' );
			$time_format = get_option( 'time_format' );

			$closed_challenges = $res['closed_challenges'][0];
			$piramid           = Courtres_Entity_Piramid::get_by_id( $piramid_id );

			$lock_expired_ts   = strtotime( $closed_challenges['closed_dt'] ) + $piramid['locktime_ts'];
			$lock_expired_text = date_i18n( $date_format, $lock_expired_ts ) . ', ' . date_i18n( $time_format, $lock_expired_ts );

			$response['errors'][] = __( 'You cannot challenge', 'courtres' ) . ' ' . $closed_challenges['challenged']['wp_user']->display_name . __( ' yet. The cool-down phase will be expired at', 'courtres' ) . ' ' . $lock_expired_text;
			echo json_encode( $response );
			wp_die();
		}

		if ( $res ) {
			$response['success']      = true;
			$response['challenge_id'] = $res;
		} else {
			$response['errors'][] = __( 'Error inserting into db', 'vaa' );
		}

		echo json_encode( $response );
		wp_die();
	}

	/**
	 * Accepting challenge from piramid-public.js
	 * Called by ajax
	 */
	function accept_challenge() {
		$response = array(
			'request' => $_POST,
			'errors'  => array(),
			'success' => false,
		);

		// if ( empty( $_POST ) || ! wp_verify_nonce( $_POST['accept_nonce'], 'accept_nonce' ) ) {
		if ( empty( $_POST ) ) {
			$response['errors'][] = __( 'Error checking security code', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}

		$challenge_id = isset( $_POST['challenge_id'] ) ? intval( $_POST['challenge_id'] ) : false;
		if ( ! $challenge_id ) {
			$response['errors'][] = __( 'Challenge id is not received', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}

		$challenges_class = Courtres_Entity_Challenges::get_instance( $challenge_id );
		$res              = $challenges_class->set_accepted();
		if ( ! $res ) {
			$response['errors'][] = __( 'Error accepting the challenge', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}

		if ( $res ) {
			$response['success'] = true;
		}

		echo json_encode( $response );
		wp_die();
	}


	/**
	 * Accepting the challenge by direct email link
	 *
	 * @param int $challenge_id in query vars
	 * @return
	 */
	function accept_challenge_by_email_link() {
		$params = array(
			'challenge' => get_query_var( 'cr-challenge' ),
			'action'    => get_query_var( 'cr-action' ),
		);
		if ( $params['challenge'] && $params['action'] ) {
			$challenges_class = Courtres_Entity_Challenges::get_instance( $params['challenge'] );
			$challenge        = $challenges_class->get_db_data();
			if ( $challenge ) {
				// $post = Courtres_Entity_Piramid::get_post_with_shortcode($challenge["piramid_id"]);
				// if($post){
				// wp_redirect( get_permalink($post->ID) . "?" .  http_build_query($params) );
				// }
				return;
			} else {
				add_action( 'wp_footer', array( $this, 'show_no_challenges_alert' ), 30 );
			}
		}
		return;
	}

	/*
	* Called in the footer if was apllied the email link to expired (and deleted) challenge
	*/
	function show_no_challenges_alert() {
		echo '<script>alert("The challenge is expired or has been deleted.")</script>';
	}


	/**
	 * Scheduling challenge from piramid-public.js
	 * Called by ajax
	 *
	 * @return
	 */
	function schedule_challenge() {
		$response = array(
			'request' => $_POST,
			'errors'  => array(),
			'success' => false,
		);
		$res      = false;

		if ( empty( $_POST ) || ! wp_verify_nonce( $_POST['schedule_challenge_nonce'], 'schedule_challenge' ) ) {
			$response['errors'][] = __( 'Error checking security code', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}

		$challenge_id = isset( $_POST['challenge_id'] ) ? intval( $_POST['challenge_id'] ) : false;
		$court_id     = isset( $_POST['cr_game'] ) && $_POST['cr_game']['court_id'] ? intval( $_POST['cr_game']['court_id'] ) : false;

		$start_ts = false;
		if ( isset( $_POST['cr_game'] ) && $_POST['cr_game']['date'] && $_POST['cr_game']['time']['h'] && $_POST['cr_game']['time']['m'] ) {
			$datetime = sprintf( '%s %s:%s', sanitize_text_field( $_POST['cr_game']['date'] ), sanitize_text_field( $_POST['cr_game']['time']['h'] ), sanitize_text_field( $_POST['cr_game']['time']['m'] ) );
			$start_ts = strtotime( $datetime );
		}
		if ( ! $challenge_id ) {
			$response['errors'][] = __( 'Challenge id is not received', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}
		if ( ! $start_ts ) {
			$response['errors'][] = __( 'Start of game date and time is not received', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}
		$start_ar    = array(
			'h' => intval( $_POST['cr_game']['time']['h'] ),
			'm' => intval( $_POST['cr_game']['time']['m'] ),
		);
		$duration_ts = isset( $_POST['duration_ts'] ) ? intval( $_POST['duration_ts'] ) : 0;
		$end_ts      = $start_ts + $duration_ts;
		$end_h_dec   = $start_ar['h'] + $start_ar['m'] / 60 + $duration_ts / 3600;

		// compare start and end of game with the opening hours of the court
		$courtres_public = new Courtres_Public( $this->plugin_name, $this->version );
		$court           = $courtres_public->getCourtByID( $court_id );
		if ( $start_ar['h'] < $court->open ) {
			$response['errors'][] = __( 'The start of the game cannot be ealier than the opening hours of the court', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}
		if ( $end_h_dec > $court->close ) {
			$response['errors'][] = __( 'The end of the game cannot be later than the closing times of the court', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}

		$challenger_name = 'Player 1';
		if ( isset( $_POST['challenger_id'] ) ) {
			$u1              = get_user_by( 'id', sanitize_text_field( $_POST['challenger_id'] ) );
			$challenger_name = $u1->display_name;
		}

		$challenged_name = 'Player 2';
		if ( isset( $_POST['challenged_id'] ) ) {
			$u1              = get_user_by( 'id', sanitize_text_field( $_POST['challenged_id'] ) );
			$challenged_name = $u1->display_name;
		}

		// first: create event to make a court reservation
		$courtres_admin = new Courtres_Admin( $this->plugin_name, $this->version );
		$result         = $courtres_admin->create_event(
			array(
				'name'       => __( 'Challenge', 'courtres' ) . ': ' . $challenger_name . ' vs. ' . $challenged_name, // "Challenge " . $challenge_id,
				'court_id'   => $court_id,
				'event_date' => sanitize_text_field( $_POST['cr_game']['date'] ),
				'start'      => array(
					'h' => sanitize_text_field( $_POST['cr_game']['time']['h'] ),
					'm' => sanitize_text_field( $_POST['cr_game']['time']['m'] )
				),
				'end'        => array(
					'h' => (int) date_i18n( 'H', $end_ts ),
					'm' => (int) date_i18n( 'i', $end_ts ),
				),
				'check_all'  => false,
				'type'       => 'challenge',
			)
		);

		if ( $result['errors'] ) {
			if ( key_exists( 'overlaps', $result['errors'] ) ) {
				$result['errors'][] = __( 'The court is already reserved at that time. Please find another time', 'courtres' );
				unset( $result['errors']['overlaps'] );
			}
			$response['errors'] = array_merge( $response['errors'], $result['errors'] );
			echo json_encode( $response );
			wp_die();
		}

		// second: update challenge data
		$args                = array(
			'id'       => $challenge_id,
			'court_id' => $court_id,
			'event_id' => $result['success']['event_id'],
			'start_ts' => $start_ts,
			'end_ts'   => $end_ts,
			'status'   => 'scheduled',
		);
		$response['args_db'] = $args;

		$challenges_class = Courtres_Entity_Challenges::get_instance( $challenge_id );
		$res              = $challenges_class->update(
			array(
				'data'         => $args,
				'where'        => array( 'id' => $challenge_id ),
				'format'       => array( '%d', '%d', '%d', '%d', '%d', '%s' ),
				'where_format' => array( '%d' ),
			)
		);

		if ( $res ) {
			$response['success'] = true;
		} else {
			$response['errors'][] = __( 'Error updating the challenge', 'vaa' );
		}

		echo json_encode( $response );
		wp_die();
	}


	/**
	 * Deleting challenge from piramid-public.js
	 * Called by ajax
	 */
	function delete_challenge() {
		$response = array(
			'request' => $_POST,
			'errors'  => array(),
			'success' => false,
		);
		$res      = false;

		// if ( empty( $_POST ) || ! wp_verify_nonce( $_POST['delete_nonce'], 'delete_nonce' ) ) {
		if ( empty( $_POST ) ) {
			$response['errors'][] = __( 'Error checking security code', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}

		$challenge_id = isset( $_POST['challenge_id'] ) ? intval( $_POST['challenge_id'] ) : false;
		if ( ! $challenge_id ) {
			$response['errors'][] = __( 'Challenge id is not received', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}

		$challenges_class = Courtres_Entity_Challenges::get_instance( $challenge_id );
		$status           = $challenges_class->get_status();

		$res = $challenges_class->delete_by_id();
		if ( ! $res ) {
			$response['errors'][] = __( 'Error deleting the challenge', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}

		// to delete linked event for scheduled challenges
		if ( $status == 'scheduled' ) {
			global $wpdb;
			$res = $wpdb->delete( $this->getTable( 'events' ), array( 'id' => $challenges_class->get_event_id() ) );
			if ( ! $res ) {
				$response['errors'][] = __( 'No one challenge event deleted', 'courtres' );
				echo json_encode( $response );
				wp_die();
			}
		}

		$response['success'] = true;
		echo json_encode( $response );
		wp_die();
	}


	/**
	 * Entering challenge result from piramid-public.js
	 * Called by ajax
	 *
	 * @return
	 */
	function enter_challenge_result() {
		$response = array(
			'request' => $_POST,
			'errors'  => array(),
			'success' => false,
		);
		$res      = false;

		if ( empty( $_POST ) || ! wp_verify_nonce( $_POST['enter_results_nonce'], 'enter_results' ) ) {
			$response['errors'][] = __( 'Error checking security code', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}
		$challenge_id = isset( $_POST['challenge_id'] ) ? intval( $_POST['challenge_id'] ) : false;
		if ( ! $challenge_id ) {
			$response['errors'][] = __( 'Challenge id is not received', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}
		$winner_id = isset( $_POST['cr_results']['winner'] ) ? intval( $_POST['cr_results']['winner'] ) : false;
		if ( ! $winner_id ) {
			$response['errors'][] = __( 'Winner is undefined', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}
		$results_san = $_POST['cr_results']['sets'];

		if (isset( $_POST['cr_results']['sets'] ))
		{
			$results_str_ = array();
			foreach ($results_san as $result_key => $result_san)
			{
				if (is_array($result_san)) { $results_str_[$result_key] = array_map( 'sanitize_text_field', $result_san ); }
				else { $results_str_[$result_key] = sanitize_text_field( $result_san ); }
			}
			$results_str = serialize( $results_str_ );
		}
		else { $results_str = false; }


		/*
		$results_str = isset( $_POST['cr_results']['sets'] ) ? serialize( array_map( 'sanitize_text_field', $_POST['cr_results']['sets'] ) ) : false;
		*/

		if ( ! $results_str ) {
			$response['errors'][] = __( 'Games result is undefined', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}

		// update challenge data
		$args = array(
			'id'        => $challenge_id,
			'winner_id' => $winner_id,
			'results'   => $results_str,
			'status'    => 'closed',
			'closed_dt' => date_i18n( 'Y-m-d H:i:s' ),
		);

		$challenges_class = Courtres_Entity_Challenges::get_instance( $challenge_id );

		$res = $challenges_class->update(
			array(
				'data'         => $args,
				'where'        => array( 'id' => $challenge_id ),
				'format'       => array( '%d', '%d', '%s', '%s', '%s' ),
				'where_format' => array( '%d' ),
			)
		);

		$challenge = $challenges_class->get_db_data();
		if ( $winner_id == $challenge['challenger_id'] ) {
			// re-order the piramid
			Courtres_Entity_Piramids_Players::reorder( $challenge['piramid_id'], $challenge['challenged_id'], $challenge['challenger_id'] );
		}

		if ( $res ) {
			$response['success'] = true;
		} else {
			$response['errors'][] = __( 'Error enter the challenge results', 'vaa' );
		}

		echo json_encode( $response );
		wp_die();
	}

	// 2021-03-14, astoian - if allow to show more the one court, if someone created directly in DB
	public function isCourtUltimate() {
		 // false - premium or higher, true - plan name exactly
		if ( ! cr_fs()->is_plan_or_trial( 'ultimate', false ) ) {
			return false;
		}
		return true;
	}

}
