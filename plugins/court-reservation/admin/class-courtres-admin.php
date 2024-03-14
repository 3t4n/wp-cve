<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.webmuehle.at
 * @since      1.0.3
 *
 * @package    Courtres
 * @subpackage Courtres/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Courtres
 * @subpackage Courtres/admin
 * @author     Webmühle e.U. <office@webmuehle.at>
 */
class Courtres_Admin extends Courtres_Base {


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.3
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.3
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $assets_version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.3
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name    = $plugin_name;
		$this->version        = $version;
		$this->assets_version = $version . '.01';
	}

	public function get_version() {
		 return $this->version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.3
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Courtres_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Courtres_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/courtres-admin.css', array(), $this->assets_version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-pricing', plugin_dir_url( __FILE__ ) . 'css/courtres-pricing.css', array(), $this->version, 'all' );
		// 20.05.2019, astoian - color picker
		wp_enqueue_style( $this->plugin_name . '-ui-cp', plugin_dir_url( __FILE__ ) . 'css/huebee.css', array(), $this->version, 'all' );
		// +RA 2020-05-09
		// enqueue styles for jquery-ui-datepicker
		wp_enqueue_style( 'jqueryui', plugin_dir_url( __FILE__ ) . 'vendor/jquery-ui/jquery-ui.css', false, null );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.3
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'freemius-checkout', plugin_dir_url( __FILE__ ) . 'js/checkout.min.js');

		// 20.05.2019, astoian - color picker
		wp_enqueue_script( $this->plugin_name . '-ui-cp', plugin_dir_url( __FILE__ ) . 'js/huebee.pkgd.min.js', array( 'jquery' ), $this->version, false );

		// 2021-03-13, astoian - load deps before use
		 // +RA 2020-05-09
		wp_enqueue_script( 'jquery-ui-datepicker' );
		// for arrange players in piramids
		// wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script( 'jquery-ui-sortable' );

		// 2021-03-13, astoina - add deps to wait for them
		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/courtres-admin.js',
			array(
				'jquery',
				'jquery-ui-datepicker',
				'jquery-ui-sortable',
			),
			$this->assets_version,
			false
		);
		// needed for ajax calls
		wp_localize_script(
			$this->plugin_name,
			'js_data',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);

	}

	private function handleError( $msg, $code = 400 ) {
		status_header( $code );
		echo esc_html( $msg );
	}

	// private function doesOverlap($hour, $from, $to)
	// {
	// return ($hour >= $from && $hour < $to);
	// }

	private function doesOverlapEvent( $resTimeStep, $eventBlock ) {
		$res_time_step    = (int) $resTimeStep->format( 'H' ) + (int) $resTimeStep->format( 'i' ) / 60;
		$event_start_m    = property_exists( $eventBlock, 'start_ts' ) && $eventBlock->start_ts ? date_i18n( 'i', $eventBlock->start_ts ) : 0;
		$event_start_time = (int) $eventBlock->start + (int) $event_start_m / 60;

		$event_end_m    = property_exists( $eventBlock, 'end_ts' ) && $eventBlock->end_ts ? date_i18n( 'i', $eventBlock->end_ts ) : 0;
		$event_end_time = (int) $eventBlock->end + (int) $event_end_m / 60;
		return ( $res_time_step >= $event_start_time && $res_time_step < $event_end_time );
	}

	private function doesOverlapReservation( $resTimeStep, $reservation ) {
		$res_time_step           = (int) $resTimeStep->format( 'H' ) + (int) $resTimeStep->format( 'i' ) / 60;
		$reservation_time_minute = (int) $reservation->time + (int) $reservation->minute / 60;
		/*
		error_log("<br>");
		error_log("res_time_step - $res_time_step");
		error_log("<br>");
		error_log("reservation_time_minute - $reservation_time_minute");
		*/

		return ( $res_time_step === $reservation_time_minute );
	}


	// this is for ajax call
	// to save (create) reservation
	public function add_reservation() {
		 $theTime = getCurrentDateTime();
		$nowTZ    = new DateTime( $theTime['datetime'] );
		// $nowTZ = $nowTZ->modify('+1 day');
		$nowTZTS = $nowTZ->format( 'U' );
		if ( ! current_user_can( 'place_reservation' ) ) {
			return $this->handleError( __( 'No permission.', 'court-reservation' ) );
		}

		if ( isset( $_REQUEST['delete'] ) && isset( $_REQUEST['id'] ) ) { // delete reservation
			$reservation = $this->getReservationByID( sanitize_text_field( $_REQUEST['id'] ) );
			if ( $reservation == null || $reservation->userid != wp_get_current_user()->ID ) {
				return $this->handleError( __( 'Wrong ID or no permissions.', 'court-reservation' ) );
			}

			if ( ! is_null( $reservation->gid ) && $reservation->gid !== '' ) {
				// return $this->handleError('Wrong  '.$reservation->date . ' - ' .$reservation->gid);
				$sdt = ( new DateTime( $reservation->date ) )->format( 'Y-m-d' );
				$this->deleteReservationByDateGid( $sdt, $reservation->gid );
			} else {
				$this->deleteReservationByID( $reservation->id );
			}

			status_header( 200 );
			echo esc_html( $reservation->id );
			return;
		}

		// check if we got a full dataset
		if ( ! isset( $_REQUEST['day'] ) ||
			! isset( $_REQUEST['hour'] ) ||
			! isset( $_REQUEST['hourplus'] ) ||
			! isset( $_REQUEST['minstart'] ) ||
			// !isset($_REQUEST['minend']) ||
			! isset( $_REQUEST['type'] ) ||
			! isset( $_REQUEST['courtid'] ) ) {
			return $this->handleError( __( 'Missing Data.', 'court-reservation' ) );
		}

		// don't allow below minimum of players

		$player_min    = sanitize_text_field( $_POST['player-min'] );
		$player_number = sanitize_text_field( $_POST['player-number'] );
		$player_number_= 0;

		if (isset($_POST['partners']))
		{
			foreach ($_POST['partners'] as $player_number1)
			{
				if ($player_number1 != "" && is_numeric($player_number1))
				{
					$player_number_++;
				}
			}
		}

		if ( $player_number_ < $player_min ) {
			return $this->handleError( __( 'Please select more players!', 'court-reservation' ) );
		}

		// before 1.5.0 >
		$partnerid  = isset( $_POST['partnerid'] ) ? (int) $_POST['partnerid'] : false;
		$partnerid2 = isset( $_POST['partnerid2'] ) ? (int) $_POST['partnerid2'] : false;
		$partnerid3 = isset( $_POST['partnerid3'] ) ? (int) $_POST['partnerid3'] : false;
		// < before 1.5.0

		// players
		$partners = isset( $_POST['partners'] ) ? $_POST['partners'] : array(); // from 1.5.0

		// court
		$court = $this->getCourtByID( sanitize_text_field( $_REQUEST['courtid'] ) );

		// check court hour restrains
		$hourstart = (int) $_REQUEST['hour'];
		if ( $hourstart < $court->open || $hourstart > $court->close ) {
			return $this->handleError( __( 'Invalid open hour.', 'court-reservation' ) );
		}
		$minstart = (int) $_POST['minstart'];

		// check court hour plus restrains
		// $timeStart = new DateTime();
		$theTime   = getCurrentDateTime();
		$timeStart = new DateTime( $theTime['datetime'] );
		$timeStart->setTime( $hourstart, $minstart, 0 );
		$minuteplus = (int) $_REQUEST['hourplus'];
		// $timeEnd = clone $timeStart;
		// $timeEnd->add(new DateInterval('PT' . $minuteplus . 'M'));
		// $hourend = (int) $timeEnd->format('H') + (int) ($minuteplus / 60);
		// $minend = ($minuteplus - $minstart) % 60; //if start 30 min and end 30 min
		$timeStep = clone $timeStart;

		for ( $minStep = 30; $minStep <= $minuteplus; $minStep += 30 ) {
			$res_time_step = (int) $timeStep->format( 'H' ) + (int) $timeStep->format( 'i' ) / 60;
			if ( $res_time_step < $court->open || $res_time_step >= $court->close ) {
				return $this->handleError( __( 'Invalid close hour.', 'court-reservation' ) );
			}
			$timeStep->add( new DateInterval( 'PT30M' ) );
		}

		$dateStr  = date( 'Y-m-d', strtotime( '+' . (int) $_REQUEST['day'] . ' day', $nowTZTS ) );
		$timeStep = clone $timeStart;
		for ( $minStep = 30; $minStep <= $minuteplus; $minStep += 30 ) {
			// check for blocks
			$eventsBlocks = $this->getBlocksByID( $court->id );
			$datetime     = new DateTime( $theTime['datetime'] );
			$datetime->modify( '+' . (int) $_REQUEST['day'] . ' day' );
			$dayWeek = (int) $datetime->format( 'N' );
			foreach ( $eventsBlocks as $eventBlock ) {
				if ( $eventBlock->weekly_repeat == 1 ) {
					$dayWeekEvent = (int) date( 'N', strtotime( $eventBlock->event_date ) );
					if ( $dayWeekEvent != $dayWeek ) {
						continue;
					}
				} else {
					$dateEvent = ( new DateTime( $eventBlock->event_date ) )->format( 'Y-m-d' );
					if ( $dateStr != $dateEvent ) {
						continue;
					}
				}
				if ( $this->doesOverlapEvent( $timeStep, $eventBlock ) ) {
					return $this->handleError( __( "You can't make a reservation that overlaps in time with an event.", 'court-reservation' ) );
				}
			}
			$timeStep->add( new DateInterval( 'PT30M' ) );
		}

		$timeStep = clone $timeStart;
		for ( $minStep = 30; $minStep <= $minuteplus; $minStep += 30 ) {
			// check for reservations
			$reservations = $this->getCurrentReservationsByID( $court->id );
			foreach ( $reservations as $reservation ) {
				$reservationDateStr = ( new DateTime( $reservation->date ) )->format( 'Y-m-d' );
				if ( $dateStr != $reservationDateStr ) {
					continue;
				}
				// if ($h == $res->time) {
				if ( $this->doesOverlapReservation( $timeStep, $reservation ) ) {
					return $this->handleError( __( "You can't make a reservation that overlaps in time with another one.", 'court-reservation' ) );
				}
			}
			$timeStep->add( new DateInterval( 'PT30M' ) );
		}

		global $wpdb;
		$player         = wp_get_current_user();
		$table_settings = $this->getTable( 'settings' );

		// +RA 2020-05-12
		// Check and return if the current logged in user already have reservation(s) and several reservations for one person is not available
		if ( ! $this->getOptionValue( 'several_reserve_person' ) ) {
			if ( $this->countUpcomingUserReservations( $player->ID ) ) {
				return $this->handleError( __( "You can't make more than one reservation or you are already invited.", 'court-reservation' ) );
			}
		}

		// +RA 2020-05-14
		// Check and return if any reservation already exists
		// fppr($_REQUEST, __FILE__.' $_REQUEST');
		if ( $this->countCourtReservationsByPeriod( $_REQUEST ) ) {
			return $this->handleError( __( "You can't make more than one reservation. This time slot is already reserved.", 'court-reservation' ) );
		}

		$half_hour        = false;
		$option_half_hour = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'half_hour_reservation'" );
		if ( $option_half_hour && $option_half_hour->option_value === '1' ) {
			$half_hour = true;
		}

		// all good!
		$gid = uniqid();
		// 16.03.2019, astoian - if half hour then
		// Save the reseravation
		if ( $half_hour ) {
			$timeStep         = clone $timeStart;
			$is_some_inserted = false;
			for ( $min = 30; $min <= $minuteplus; $min += 30 ) {
				$res = $wpdb->insert(
					$this->getTable( 'reservations' ),
					array(
						'courtid'    => (int) $_POST['courtid'],
						'type'       => sanitize_text_field( $_POST['type'] ),
						'userid'     => $player->ID,
						'partnerid'  => $partnerid,
						'partnerid2' => $partnerid2,
						'partnerid3' => $partnerid3,
						'date'       => $dateStr,
						'time'       => (int) $timeStep->format( 'H' ),
						'minute'     => (int) $timeStep->format( 'i' ),
						'gid'        => $gid,
					),
					array(
						'%d',
						'%s',
						'%d',
						'%d',
						'%d',
						'%d',
						'%s',
						'%d',
						'%d',
						'%s',
					)
				);
				$timeStep->add( new DateInterval( 'PT30M' ) );
				if ( $res && ! $is_some_inserted ) {
					$is_some_inserted = true;
				}
			}
		} else { // 16.03.2019, astoian - if not half hour, then add 2 entries with 30 min
			$timeStep         = clone $timeStart;
			$is_some_inserted = false;
			for ( $min = 30; $min <= $minuteplus; $min += 30 ) {
				$res = $wpdb->insert(
					$this->getTable( 'reservations' ),
					array(
						'courtid'    => (int) $_POST['courtid'],
						'type'       => sanitize_text_field( $_POST['type'] ),
						'userid'     => $player->ID,
						'partnerid'  => $partnerid,
						'partnerid2' => $partnerid2,
						'partnerid3' => $partnerid3,
						'date'       => $dateStr,
						'time'       => (int) $timeStep->format( 'H' ),
						'minute'     => (int) $timeStep->format( 'i' ),
						'gid'        => $gid,
					),
					array(
						'%d',
						'%s',
						'%d',
						'%d',
						'%d',
						'%d',
						'%s',
						'%d',
						'%d',
						'%s',
					)
				);
				$timeStep->add( new DateInterval( 'PT30M' ) );
				if ( $res && ! $is_some_inserted ) {
					$is_some_inserted = true;
				}
			}
		}

		// if at least one reservation row is inserted
		// save the reservation's players
		if ( $is_some_inserted ) {
			$all_players = array_merge( array( $player->ID ), $partners ); // $player->ID = author of the reservation
			$this->saveReservPlayers( $gid, $all_players );
		}

		// $table_settings = $this->getTable('settings');
		$notify_players       = false;
		$email_notify_players = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'email_notify_players'" );
		if ( $email_notify_players && $email_notify_players->option_value === '1' ) {
			$notify_players = true;
			$email_template = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_template'" )->option_value;

			$option_email_1 = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_1'" )->option_value;
			$option_email_2 = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_2'" )->option_value;
			$option_email_3 = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_3'" )->option_value;
			$option_email_4 = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_4'" )->option_value;
			$option_email_5 = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_5'" )->option_value;
			$option_email_6 = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_6'" )->option_value;
			$option_email_7 = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_7'" )->option_value;
			$option_email_8 = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_8'" )->option_value;
		}

		// 23.01.2019, astoian - if need to notify players
		if ( $notify_players ) {

			if (!isset($option_email_5) || $option_email_8=="") { $option_email_5="#2b87da"; }
			if (!isset($option_email_6) || $option_email_8=="") { $option_email_6="white"; }
			if (!isset($option_email_7) || $option_email_8=="") { $option_email_7="white"; }
			if (!isset($option_email_8) || $option_email_8=="") { $option_email_8="#4e4e4e"; } 

			$timeEnd = clone $timeStep;
			$hourend = (int) $timeEnd->format( 'H' );
			$minend  = (int) $timeEnd->format( 'i' );

			// send mail
			$dateStr        = date_i18n( get_option( 'date_format' ), strtotime( $dateStr ) );
			$hour_from_till = $hourstart . ':' . ( $minstart ? $minstart : '00' ) . ' - ' . $hourend . ':' . ( $minend ? $minend : '00' );

			// from 1.5.0 >
			$wp_users = array();
			if ( $partners ) {
				$wp_users = get_users(
					array(
						'include' => $partners,
						'orderby' => 'display_name',
						'order'   => 'ASC',
						'fields'  => array( 'display_name', 'user_email' ),
					)
				);
			}
			$recepients    = array_merge( array( $player ), $wp_users );
			$players_names = $wp_users ? wp_list_pluck( $wp_users, 'display_name' ) : false;
			$players_list  = $players_names ? implode( ', ', $players_names ) : '';
			// <from 1.5.0

			$subject = sprintf(
				__( 'New reservation on %1$s on %2$s at %3$s for %4$s and %5$s.', 'court-reservation' ),
				$court->name,
				$dateStr,
				$hour_from_till,
				$player->display_name,
				$players_list
			);

			if ( $email_template ) {
				$placeholders = array(
					'/\[court_name\]/'          => $court->name,
					'/\[date_on\]/'             => $dateStr,
					'/\[hours_from_till\]/'     => $hour_from_till,
					'/\[player_name_creator\]/' => $player->display_name,
					'/\[players_list\]/'        => $players_list,
				);
				$message_      = preg_replace( array_keys( $placeholders ), $placeholders, $email_template );
			}

			$message__='<!DOCTYPE html>

<html lang="en-EN-formal">

	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

		<title>Court Reservation</title>

	</head>

	<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="padding: 0;">';

			$message___=email_message($message_,$option_email_3,$option_email_4,$option_email_5,$option_email_6,$option_email_7,$option_email_8);

			$message=$message__ . $message___ . '
	</body>

</html>';

			if (!isset($option_email_1)) { $option_email_1=wp_get_current_user()->display_name; }
			if (!isset($option_email_2)) { $option_email_2=wp_get_current_user()->user_email; }
			$header1 = 'From: ' . sanitize_text_field($option_email_1) . '<' . sanitize_email($option_email_2) . '>';
			$headers = array('Content-Type: text/html; charset=UTF-8',$header1);

			try {
				foreach ( $recepients as $recepient ) {
					wp_mail( $recepient->user_email, $subject, $message, $headers );
				}
			} catch ( \Throwable $th ) {
				return $this->handleError( __( 'E-Mail is not sent.', 'court-reservation' ) );
			}
		}

		status_header( 200 );
		$wpdb->insert_id;
	}

	public function countCourtReservationsByPeriod( $params ) {
		$paramsDate = $params['date'];
		$datetime   = DateTime::createFromFormat( 'Y-m-d', $paramsDate );
		$datetime->setTime( 0, 0 );
		// // echo "datetime - ".$datetime." -0- ";
		$dateStr = $datetime->format( 'Y-m-d H:i:s' );
		global $wpdb;
		// 2020-05-19 astoian - loop every 30 mins :(
		$minstart = intval( $params['minstart'] );
		$hourplus = intval( $params['hourplus'] );
		$hour     = intval( $params['hour'] );
		for ( $minIndex = $minstart; $minIndex < $hourplus; $minIndex += 30 ) {
			$res = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(id) AS countCourtReservations FROM {$this->getTable('reservations')}
				WHERE courtid = %d AND date = %s AND time = %d AND minute = %d",
					$params['courtid'],
					$dateStr,
					$hour,
					$minstart
				)
			);
			// echo "res - $res -3- ";
			// echo "\n<br>\n";
			// 2020-05-19 astoian - if found something, then exit
			if ( $res ) {
				return $res;
			}
			$minstart += 30;
			if ( ( $minstart % 60 ) === 0 ) {
				$hour++;
				$minstart = 0;
			}
		}
		return $res;
	}

	public function ajax_login() {
		// echo "ajax_login222";
		// First check the nonce, if it fails the function will break
		check_ajax_referer( 'ajax-login-nonce', 'security' );
		// Nonce is checked, get the POST data and sign user on
		$info                  = array();
		$info['user_login']    = sanitize_text_field( $_POST['username'] );
		$info['user_password'] = sanitize_text_field( $_POST['password'] );
		$info['remember']      = true;

		$user_signon = wp_signon( $info, true );
		if ( is_wp_error( $user_signon ) ) {
			echo json_encode(
				array(
					'loggedin' => false,
					'message'  => __(
						'Wrong username or password.',
						'court-reservation'
					),
				)
			);
		} else {
			wp_set_current_user( $user_signon->ID );
			wp_set_auth_cookie( $user_signon->ID );
			status_header( 200 );
			echo json_encode(
				array(
					'loggedin'     => true,
					'display_name' => $user_signon->display_name,
					'message'      => __( 'Login successful, redirecting...' ),
				)
			);
		}
		wp_die();
	}

	public function add_admin_page() {
		add_menu_page(
			'Manage Reservations',
			__( 'Reservations', 'court-reservation' ),
			'manage_options',
			( $this->plugin_name ) . '',
			array( $this, 'load_admin_settings' ),
			'dashicons-calendar',
			6
		);

		add_submenu_page(
			$this->plugin_name,
			'Manage Settings',
			__( 'Settings', 'court-reservation' ),
			'manage_options',
			( $this->plugin_name ) . '',
			array( $this, 'load_admin_settings' )
		);

		add_submenu_page(
			$this->plugin_name,
			'Members',
			__( 'Members', 'court-reservation' ),
			'manage_options',
			( $this->plugin_name ) . '-users',
			array( $this, 'load_admin_users' )
			// admin_url('users.php') ,
			// admin_url('users.php?page='.($this->plugin_name) . '-users') ,
			// ''
		);

		add_submenu_page(
			null,
			'Edit Members',
			'Edit Members',
			'manage_options',
			( $this->plugin_name ) . '-user',
			array( $this, 'load_admin_user' )
		);

		add_submenu_page(
			null,
			'Upgrade',
			__( 'Upgrade', 'court-reservation' ),
			'manage_options',
			( $this->plugin_name ) . '-upgrade',
			array( $this, 'load_admin_upgrade' )
		);

		add_submenu_page(
			null,
			'Edit Court',
			'Edit Court',
			'manage_options',
			( $this->plugin_name ) . '-court',
			array( $this, 'load_admin_court' )
		);

		add_submenu_page(
			null,
			'Edit Pyramid',
			'Edit Pyramid',
			'manage_options',
			( $this->plugin_name ) . '-piramid',
			array( $this, 'load_admin_piramid' )
		);

		add_submenu_page(
			$this->plugin_name,
			'Manage Events',
			__( 'Events', 'court-reservation' ),
			'manage_options',
			( $this->plugin_name ) . '-events',
			array( $this, 'load_admin_events' )
		);

		add_submenu_page(
			null,
			'Edit Events',
			'Edit Events',
			'manage_options',
			( $this->plugin_name ) . '-event',
			array( $this, 'load_admin_event' )
		);

		add_submenu_page(
			$this->plugin_name,
			'Reservations',
			__( 'Reservations', 'court-reservation' ),
			'manage_options',
			( $this->plugin_name ) . '-reservations',
			array( $this, 'load_admin_reservations' )
		);

		add_submenu_page(
			$this->plugin_name,
			'Challenges',
			__( 'Challenges', 'court-reservation' ),
			'manage_options',
			( $this->plugin_name ) . '-challenges',
			array( $this, 'load_admin_challenges' )
		);
	}

	public function load_admin_courts() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-courts.php';
	}

	public function load_admin_court() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-court.php';
	}

	public function load_admin_piramid() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-piramid.php';
	}

	public function load_admin_events() {
		// require_once plugin_dir_path(__FILE__) . 'partials/courtres-events.php';
		$tab = ( ! empty( $_GET['tab'] ) ) ? sanitize_text_field( $_GET['tab'] ) : '0';
		if ( $tab == '0' ) {
			require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-events.php';
		} elseif ( $tab == '1' ) {
			require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-events.php';
		} else {
			require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-events.php';
		}
	}

	public function load_admin_event() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-event.php';
	}

	public function load_admin_reservations() {
		 require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-reservations.php';
	}

	public function load_admin_challenges() {
		require_once plugin_dir_path( __FILE__ ) . 'class-courtres-base-list-table.php';
		require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-challenges.php';
	}

	public function load_admin_settings() {
		 // $tab = (!empty($_GET['tab'])) ? esc_attr($_GET['tab']) : '1';
		$tab = ( ! empty( $_GET['tab'] ) ) ? sanitize_text_field( $_GET['tab'] ) : '0';

		switch ( $tab ) {
			case '0':
				require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-courts.php';
				break;
			case '1':
				require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-piramids.php';
				break;
			case '2':
				require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-settings.php';
				break;
			case '3':
				require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-ui.php';
				break;
			case '5':
				require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-emailtemplate.php';
				break;
			case '5preview':
				require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-emailtemplatepreview.php';
				break;

			default:
				require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-upgrade.php';
				break;
		}

		/*
		if ($tab == '0') {
			require_once plugin_dir_path(__FILE__) . 'partials/courtres-courts.php';
		} else if ($tab == '1') {
			require_once plugin_dir_path(__FILE__) . 'partials/courtres-settings.php';
		} else if ($tab == '3') {
			require_once plugin_dir_path(__FILE__) . 'partials/courtres-ui.php';
		} else {
			// $cr_fs = cr_fs();
			// $cr_fs->_pricing_page_render();
			require_once plugin_dir_path(__FILE__) . 'partials/courtres-upgrade.php';
		}*/
	}

	public function load_admin_upgrade() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-upgrade.php';
	}

	public function load_admin_users() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-users.php';
	}

	public function load_admin_user() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/courtres-user.php';
	}

	public function getCourtByID( $courtID ) {
		global $wpdb;
		$table_courts = $this->getTable( 'courts' );
		return $wpdb->get_row( "SELECT * FROM $table_courts WHERE id = $courtID" );
	}

	/**
	 * Get reservations list for Upcoming Reservations srcreen in admin
	 *
	 * @param  [type] $order      [description]
	 * @param  [type] $way        ASC or DESC
	 * @param  [type] $start_date [description]
	 * @param  [type] $final_date [description]
	 * @param  [type] $gid        [description]
	 * @return [type]             [description]
	 */
	public function getReservations( $order = null, $way = null, $start_date = null, $final_date = null, $gid = null ) {
		global $wpdb;
		$theTime = getCurrentDateTime();
		// 2020-07-23 as - for differt UTC is very important, db could have another time_zone
		// $wpdb->get_results('SET @@time_zone = "'.$theTime["offset"].'";');

		$order_by           = $sql_where = $sql_wpuser_join = $sql_wpuser_select = '';
		$sql_and_conditions = array();

		/*  Order by  */
		switch ( $order ) {
			case 'court':
				$order_by = $this->getTable( 'courts' ) . '.name ';
				break;
			case 'player':
				$order_by = $wpdb->users . '.display_name ';
				break;
			case 'date':
				$order_by = "reservations.date $way, reservations.time $way, reservations.minute";
				break;
			case 'type':
				$order_by = 'reservations.type ';
				break;
			default:
				$order_by = "reservations.date $way, reservations.time $way, reservations.minute";
		}

		/*
		  Filter  */
		// if start_date is selected
		if ( $start_date ) {
			$start_date           = date_i18n( 'Y-m-d H:i:s', strtotime( $start_date ), false );
			$sql_and_conditions[] = "`reservations`.date >= '$start_date'";
		}

		// if start_date is selected
		if ( $final_date ) {
			$final_date           = date_i18n( 'Y-m-d H:i:s', strtotime( $final_date ), false );
			$sql_and_conditions[] = "`reservations`.date <= '$final_date'";
		}

		// if reservation gid is selected
		if ( $gid ) {
			$sql_and_conditions[] = "`reservations`.gid = '$gid'";
		}

		$sql_where = $sql_and_conditions ? 'WHERE ' . implode( ' AND ', $sql_and_conditions ) : '';

		/*  Joins */
		$sql_courts_join         = sprintf( ' LEFT JOIN %1$s ON %1$s.id = %2$s.courtid', $this->getTable( 'courts' ), 'reservations' );
		$sql_courts_select       = sprintf( ', %1$s.name AS courtname', $this->getTable( 'courts' ) );
				$sql_rp_join     = sprintf( ' LEFT JOIN %1$s ON %1$s.reservation_gid = %2$s.gid', $this->getTable( 'reserv_players' ), 'reservations' );
		$sql_rp_select           = sprintf( ', GROUP_CONCAT(%1$s.player_id) AS players, GROUP_CONCAT(%1$s.is_author) AS is_author', $this->getTable( 'reserv_players' ) );
				$sql_wpuser_join = sprintf( ' LEFT JOIN %1$s ON %1$s.ID = %2$s.userid', $wpdb->users, 'reservations' );
		$sql_wpuser_select       = sprintf( ', %1$s.display_name AS user_display_name', $wpdb->users );

		$group_by = ' GROUP BY `reservations`.id';

		/*  Get reservations query  */
		$res = $wpdb->get_results(
			"SELECT reservations.*{$sql_courts_select}{$sql_rp_select}{$sql_wpuser_select}
			FROM {$this->getTable('reservations')} as reservations
			{$sql_courts_join}
			{$sql_rp_join}
			{$sql_wpuser_join}
			{$sql_where} 
			{$group_by} 
			ORDER BY " . $order_by . ' ' . $way
		);
		// fppr($wpdb->last_query, __FILE__.' $wpdb->last_query');
		// fppr($res, __FILE__.' $res');

		foreach ( $res as $key => &$item ) {
			// remove author from result
			$partners   = explode( ',', $item->players );
			$is_authors = explode( ',', $item->is_author );
			$author_key = array_search( 1, $is_authors );
			if ( $author_key !== false ) {
				array_splice( $partners, $author_key, 1 );
			}
						$item->players_names = array();
			if ( $partners ) {
				$wp_users            = get_users(
					array(
						'include' => $partners,
						'orderby' => 'display_name',
						'order'   => 'ASC',
					)
				);
				$item->players_names = wp_list_pluck( $wp_users, 'display_name' );
			}
		}
		// < From 1.5.0

		return $res;
	}

	public function getReservationByID( $reservationID ) {
		global $wpdb;
		return $wpdb->get_row( "SELECT * FROM {$this->getTable('reservations')} WHERE id = $reservationID" );
	}

	public function getReservationsByGID( $gid ) {
		global $wpdb;
		return $wpdb->get_results( "SELECT * FROM {$this->getTable('reservations')} WHERE `gid` = '$gid'" );
	}

	public function getReservationByUser( $userID ) {
		global $wpdb;
		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$this->getTable('reservations')} WHERE userid = %d", $userID ) );
	}

	public function countUserReservations( $userID ) {
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) AS userReservations  FROM {$this->getTable('reservations')} WHERE userid = %d", $userID ) );
	}

	public function getUpcomingUserReservations( $userID ) {
		global $wpdb;
		$theTime = getCurrentDateTime();
		$res     = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$this->getTable('reservations')}
			WHERE userid = %d AND ( date > %s OR (date = %s AND time >= %d AND minute >= %d) )",
				$userID,
				$theTime['date'],
				$theTime['date'],
				$theTime['hour'],
				$theTime['minute']
			)
		);
		return $res;
	}

	public function deleteReservationByID( $reservationID ) {
		global $wpdb;

		$r   = $this->getReservationByID( $reservationID );
		$res = $wpdb->delete( $this->getTable( 'reservations' ), array( 'id' => $reservationID ), array( '%d' ) );
		// from 1.5.0 >
		if ( $res ) {
			$reservations = $this->getReservationsByGID( $r->gid );
			if ( ! count( $reservations ) ) {
				$wpdb->query( "DELETE FROM {$this->getTable('reserv_players')} WHERE `reservation_gid` = '" . $r->gid . "'" );
			}
		}
		// < from 1.5.0
		return $res;
	}

	public function deleteReservationByDateGid( $sdt, $gid ) {
		global $wpdb;
		$res = $wpdb->query( "DELETE FROM {$this->getTable('reservations')} WHERE DATE(date) = '$sdt' AND gid = '$gid'" );
		// from 1.5.0 >
		if ( $res ) {
			$wpdb->query( "DELETE FROM {$this->getTable('reserv_players')} WHERE `reservation_gid` = '$gid'" );
		}
		// < from 1.5.0
	}

	public function cleanUpReservations() {
		 global $wpdb;
		$wpdb->query( "DELETE FROM {$this->getTable('reservations')} WHERE date < DATE_SUB(CURDATE(), INTERVAL 1 DAY)" );
	}

	public function getCurrentReservationsByID( $courtID ) {
		global $wpdb;

		$theTime = getCurrentDateTime();
		// 2020-07-23 as - for differt UTC is very important, db could have another time_zone
		// $wpdb->get_results('SET @@time_zone = "'.$theTime["offset"].'";');

		return $wpdb->get_results(
			"SELECT * FROM {$this->getTable('reservations')} WHERE courtid = $courtID AND
			date >= CURDATE()
		ORDER BY date, time"
		);
	}

	public function getBlocksByID( $courtID ) {
		global $wpdb;
		$table_blocks = $this->getTable( 'events' );
		return $wpdb->get_results( "SELECT * FROM $table_blocks WHERE courtid = $courtID ORDER BY dow" );
	}

	private function getWeekdays() {
		return array(
			__( 'Sunday', 'court-reservation' ),
			__( 'Monday', 'court-reservation' ),
			__( 'Tuesday', 'court-reservation' ),
			__( 'Wednesday', 'court-reservation' ),
			__( 'Thursday', 'court-reservation' ),
			__( 'Friday', 'court-reservation' ),
			__( 'Saturday', 'court-reservation' ),
		);
	}

	private function getRepeat( $v ) {
		return ( is_null( $v ) ) ? __( 'Yes', 'court-reservation' ) : sprintf( __( 'No, at %1$s only', 'court-reservation' ), date_i18n( get_option( 'date_format' ), strtotime( $v ) ) );
	}

	// 17.01.2019, astoian - premium checks
	private function isCourtsAdd() {
		// 2021-03-13, astoian
		// false - premium or higher, true - plan name exactly
		if ( ! cr_fs()->is_plan_or_trial( 'premium', false ) ) {
			global $wpdb;
			$table_name = $this->getTable( 'courts' );
			$num_rows   = $wpdb->get_var( "SELECT COUNT(id) FROM $table_name" );
			return $num_rows >= 1 ? false : true;
		}
		return true;
	}

	private function isCourtsAddRedirect() {
		if ( ! $this->isCourtsAdd() ) {
			wp_redirect( plugin_dir_path( __FILE__ ) . 'partials/courtres-courts.php' );
			wp_die();
		}
	}

	// 18.01.2019, astoian - if allow to show more the one court, if someone created directly in DB
	public function isCourtPremium( $courtID ) {
		global $wpdb;
		// 2021-03-13, astoian
		// false - premium or higher, true - plan name exactly
		if ( ! cr_fs()->is_plan_or_trial( 'premium', false ) ) {
			$table_courts = $this->getTable( 'courts' );
			$court_first  = $wpdb->get_row( "SELECT * FROM $table_courts ORDER BY id ASC" );
			if ( isset( $court_first ) && $courtID != $court_first->id ) {
				return false;
			}
		}
		return true;
	}

	private function isPiramidsAdd() {
		// false - premium or higher, true - plan name exactly
		if ( ! cr_fs()->is_plan_or_trial( 'ultimate', false ) ) {
			return false;
		}
		return true;
	}

	private function isPiramidsAddRedirect() {
		if ( ! $this->isPiramidsAdd() ) {
			wp_redirect( plugin_dir_path( __FILE__ ) . 'partials/courtres-piramids.php' );
			wp_die();
		}
	}


	// 2021-03-14, astoian - if allow to show more the one court, if someone created directly in DB
	public function isCourtUltimate() {
		 // false - premium or higher, true - plan name exactly
		if ( ! cr_fs()->is_plan_or_trial( 'ultimate', false ) ) {
			return false;
		}
		return true;
	}

	// 27.01.2019, astoian
	// return users-object of extended class
	public function get_users_table() {
		 require_once plugin_dir_path( __FILE__ ) . 'class-courtres-users.php';
		return new CR_Users_List_Table();
	}

	/**
	 * get array of available date formats
	 *
	 * @param string  Example: "d.m. = German\r\nm.d. = USA"
	 * @return array  Example: array("d.m." => "German", "m.d." => "U.S.");
	 */
	public function getDateformats( $str = '' ) {
		$delimiter = '=';
		$str       = preg_replace( array( '/ /' ), array( '' ), $str );
		$items     = explode( "\r\n", $str );

		$dateformats = array();
		foreach ( $items as $item ) {
			$arr           = explode( $delimiter, $item );
			$dateformats[] = array(
				'format' => $arr[0],
				'name'   => $arr[1],
			);
		}
		return $dateformats;
	}


	/**
	 * Get option data by name from db
	 *
	 * @param string option name
	 * @return object of the opotion db row || false if not exists
	 */
	public function getOption( $name ) {
		global $wpdb;
		$table_name = $this->getTable( 'settings' );
		$res        = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = '" . $name . "'" );
		return $res;
	}

	/**
	 * Add or Delete reservation type
	 * Calls from ajax request on setting page near Choose available reservation types option
	 *
	 * @param string
	 * @return json
	 */
	function edit_reservation_type() {
		$responce            = array();
		$responce['errors']  = array();
		$responce['request'] = $_REQUEST;

		$reservation_type = isset( $_REQUEST['reservation_type'] ) ? sanitize_text_field( $_REQUEST['reservation_type'] ) : '';
		if ( ! $reservation_type ) {
			$responce['errors'][] = __( 'New reservation type is empty', 'court-reservation' );
			echo json_encode( $responce );
			wp_die();
		}

		$option_reservation_types = $this->getOption( 'reservation_types' );
		if ( ! $option_reservation_types ) {
			$responce['errors'][] = __( 'Reservation types not found in the db', 'court-reservation' );
			echo json_encode( $responce );
			wp_die();
		}

		$reservation_types = $option_reservation_types->option_value ? unserialize( $option_reservation_types->option_value ) : array();

		switch ( $_REQUEST['action_type'] ) {
			case 'add':
				$reservation_types[] = $reservation_type;
				break;
			case 'delete':
				$found_key = array_search( $reservation_type, $reservation_types );
				if ( $found_key === false ) {
					$responce['errors'][] = __( 'Reservation type not found to delete', 'court-reservation' );
					echo json_encode( $responce );
					wp_die();
				}
				unset( $reservation_types[ $found_key ] );
				break;

			default:
				$responce['errors'][] = __( 'Unknown action type', 'court-reservation' );
				echo json_encode( $responce );
				wp_die();
				break;
		}

		global $wpdb;
		$res = $wpdb->update(
			$this->getTable( 'settings' ),
			array( 'option_value' => serialize( $reservation_types ) ),
			array( 'option_id' => $option_reservation_types->option_id ),
			array( '%s' )
		);
		if ( $res === false ) {
			$responce['errors'][] = __( 'Error updating reservation types', 'court-reservation' );
		} else {
			$responce['message'][] = __( 'Reservation types successfully changed!', 'court-reservation' );
		}

		$responce['reservation_types'] = $reservation_types;

		echo json_encode( $responce );
		wp_die();
	}

	// this is for ajax call
	function get_players_select_options() {
		$responce            = array();
		$responce['errors']  = array();
		$responce['request'] = $_REQUEST;
		$players             = $this->getAvailablePlayers(); ?>

		<option value="0"><?php echo esc_html__( 'Select partner', 'court-reservation' ); ?></option> 
									 <?php
										foreach ( $players as $key => $player ) {
											?>
		<option value="<?php echo esc_html($player->id); ?>"><?php echo esc_html($player->display_name); ?></option> 
											<?php
										}
	}


	/**
	 * Create new or edit existing ($params["event_id"] exists) event reservation
	 *
	 * @param array with keys as in $defaults
	 * @return array result with messages and result["success"]["event_id"] if success
	 */
	public function create_event( $params ) {
		$defaults = array(
			'name'                   => false,
			'court_id'               => false,
			'event_date'             => false, // date of the checked period in d-m-Y
			'start'                  => array(
				'h' => false,
				'm' => 0,
			), // start time of the checked period
			'end'                    => array(
				'h' => false,
				'm' => 0,
			), // end time of the checked period in timestamp
			'event_id'               => false, // exists if event was edited
			'is_event_weekly_repeat' => false,
			'dow'                    => false,
			'event_date_week'        => 0,
			'check_all'              => true, // true - find all intersected events or reservations, true - finish check if one intersected event or reservation found
			'type'                   => false, // = challenge for challenges
		);
		$params   = wp_parse_args( $params, $defaults );

				global $wpdb;
		$event_table              = $this->getTable( 'events' );
		$result                   = array();
		$result['errors']         = array();
		$result['success']        = array();
		$is_half_hour_reservation = $this->getOptionValue( 'half_hour_reservation' );

		// check courts existing
		if ( ! $params['court_id'] ) {
			$result['errors'][] = __( 'Please create a court first.', 'court-reservation' );
		}

		if ( $params['is_event_weekly_repeat'] == 1 ) {
			$curEventDateWeek     = $params['event_date_week'];
			$last_sundy           = date( 'Y-m-d', strtotime( 'last sunday' ) );
			$event_timestamp      = strtotime( $last_sundy . '+ ' . $curEventDateWeek . ' days' );
			$params['event_date'] = date_i18n( 'Y-m-d', $event_timestamp );
		}

		if ( ! $params['event_date'] || ! $params['start']['h'] || ! $params['end']['h'] ) {
			$result['errors'][] = __( 'Not all needed parameters was received', 'court-reservation' );
		}

		if ( ! $result['errors'] ) {
			$posted_start_time = (int) $params['start']['h'] + (int) $params['start']['m'] / 60;
			$posted_end_time   = (int) $params['end']['h'] + (int) $params['end']['m'] / 60;
			$event_timestamp   = $params['event_date'] ? strtotime( $params['event_date'] ) : false;

			$start_datetime = $params['event_date'] . ' ' . $params['start']['h'] . ':' . $params['start']['m'];
			$start_ts       = strtotime( $start_datetime );

			$end_datetime = $params['event_date'] . ' ' . $params['end']['h'] . ':' . $params['end']['m'];
			$end_ts       = strtotime( $end_datetime );

			if ( $posted_start_time >= $posted_end_time ) {
				$result['errors'][] = __( 'Start must be before end of event.', 'court-reservation' );
			}
		}

		$check_errors     = $this->check_period( $params );
		$result['errors'] = array_merge( $result['errors'], $check_errors );

		if ( ! $result['errors'] ) {
			/*  Working with DB  */
			$fields        = array(
				'name'          => $params['name'],
				'start'         => $params['start']['h'],
				'end'           => $params['end']['h'],
				'dow'           => $params['event_date_week'],
				'repeatone'     => ( isset( $repeatone ) ? $repeatone : null ),
				'courtid'       => $params['court_id'],
				'event_date'    => ( $event_timestamp ? date_i18n( 'Y-m-d', $event_timestamp ) : false ),
				'weekly_repeat' => $params['is_event_weekly_repeat'],
				'start_ts'      => $start_ts,
				'end_ts'        => $end_ts,
				'type'          => $params['type'],
			);
			$fields_format = array(
				'%s',
				'%d',
				'%d',
				'%d',
				'%s',
				'%d',
				'%s',
				'%d',
				'%d',
				'%d',
				'%s',
			);

			if ( $params['event_id'] ) {
				// edit
				$res                           = $wpdb->update( $event_table, $fields, array( 'id' => (int) $params['event_id'] ), $fields_format );
				$result['success']['event_id'] = (int) $params['event_id'];
				$result['success']['message']  = $res === false ? __( 'Updating error. Try to reactivate the plugin to solve this issue.', 'court-reservation' ) : __( 'Successfully changed!', 'court-reservation' );
			} else {
				// create
				$res = $wpdb->insert( $event_table, $fields, $fields_format );
				// function_exists("fppr") ? fppr($wpdb->last_query, __FILE__.' $wpdb->last_query') : false;
				$result['success']['message']  = $res === false ? __( 'Creating error. Try to reactivate the plugin to solve this issue.', 'court-reservation' ) : __( 'Successfully created!', 'court-reservation' );
				$result['success']['event_id'] = $wpdb->insert_id;
			}
		}
		return $result;
	}


	/**
	 * Export expired reservations
	 *
	 * @param string $type = {'reservations' || 'events' || 'piramid_challenges'}
	 * @return
	 */
	public function get_expired( $type ) {
		$result           = array();
		$result['errors'] = array();
		if ( ! in_array( $type, array( 'reservations', 'events', 'challenges' ) ) ) {
			$result['errors'][] = __( 'Wrong reservation type', 'courtres' );
			return false;
		}
		$table_name = $this->getTable( $type );

		switch ( $type ) {
			case 'reservations':
				$reservations   = $this->getReservations( 'date', 'desc', false, date_i18n( 'Y-m-d H:i:s' ) );
				$reservations_j = $this->joinReservations( $reservations );
				foreach ( $reservations_j as $key => $reservation ) {
					if ( $reservation->end_ts > time() ) {
						unset( $reservations_j[ $key ] );
					}
				}
				$results = $reservations_j;
				break;

			case 'events':
				global $wpdb;
				$sql_where = sprintf( ' WHERE events.end_ts < %d AND weekly_repeat = 0', (int) current_time( 'timestamp' ) );
				$results   = $wpdb->get_results( sprintf( 'SELECT events.* FROM %s as events%s ORDER BY `end_ts` DESC', $this->getTable( 'events' ), $sql_where ) );
				break;

			case 'challenges':
				$results = Courtres_Entity_Challenges::get_expired();
				break;

			default:
				$results = array();
				break;
		}
		return $results;
	}


	private function prepare_to_csv( array $data ) {
		$results     = array();
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );
		// function_exists("fppr") ? fppr($data, __FILE__.' $data') : false;

		foreach ( $data as $row ) {
			if ( ! is_object( $row ) && is_array( $row ) ) {
				$row = (object) $row;
			}
			$result = array(
				'id'    => $row->id,
				'start' => date_i18n( $date_format, $row->start_ts ) . ' ' . date_i18n( $time_format, $row->start_ts ),
				'end'   => date_i18n( $date_format, $row->end_ts ) . ' ' . date_i18n( $time_format, $row->end_ts ),
			);
			if ( isset( $row->courtid ) ) {
				$result['court_id'] = $row->courtid;
			} elseif ( isset( $row->court_id ) ) {
				$result['court_id'] = $row->court_id;
			}
			isset( $row->type ) ? $result['type'] = $result['type'] = $row->type : false;

			// for user reservations
			isset( $row->gid ) ? $result['gid']                     = $row->gid : false;
			isset( $row->courtname ) ? $result['courtname']         = $row->courtname : false;
			isset( $row->user_display_name ) ? $result['creator']   = $row->user_display_name : false;
			isset( $row->players_names ) ? $result['players_names'] = implode( ', ', $row->players_names ) : false;

			// for events reservations
			isset( $row->name ) ? $result['name'] = $row->name : false;

			// for challenges
			isset( $row->name ) ? $result['name']             = $row->name : false;
			isset( $row->piramid_id ) ? $result['piramid_id'] = $row->piramid_id : false;
			isset( $row->status ) ? $result['status']         = $row->status : false;
			isset( $row->challenger ) ? $result['challenger'] = $row->challenger['wp_user']->display_name : false;
			isset( $row->challenged ) ? $result['challenged'] = $row->challenged['wp_user']->display_name : false;
			isset( $row->winner ) ? $result['winner']         = $row->winner['wp_user']->display_name : false;

			$results[] = $result;
		}
		return $results;
	}


	private function export_csv( array $data ) {
		$temp = new \SplTempFileObject();
		if ( count( $data ) ) {
			$temp->fputcsv( array_keys( $data[0] ), static::CSV_DELIMITER );
		}
		foreach ( $data as $row ) {
			$temp->fputcsv( $row, static::CSV_DELIMITER );
		}
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment;filename=expired.csv' );
		$temp->rewind();
		$temp->fpassthru();
	}


	// this is for ajax call
	// download_csv
	function download_csv() {
		if ( wp_verify_nonce( $_POST['export_expired_nonce'], 'export_expired' ) ) {
			$data = $this->get_expired( sanitize_text_field( $_POST['target'] ) );
			$this->export_csv( $this->prepare_to_csv( $data ) );
			wp_die();
		}
	}

}
