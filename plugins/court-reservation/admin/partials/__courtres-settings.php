<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.1.0
 *
 * @package    Settings
 * @subpackage Courtres/admin/settigns
 */
?>

<?php
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die();
}

global $wpdb;
$table_name = $this->getTable( 'settings' );

if ( isset( $_POST['submit'] ) ) {
	echo "<pre>"; print_r($_POST); 

	$email_notify_players = '1';
	if ( isset( $_POST['email_notify_players'] ) ) {
		// Checkbox is selected
		$email_notify_players = '1';
	} else {
		$email_notify_players = '0';
	}
	// save email_notify_players
	if ( isset( $_POST['option_email_id'] ) && (int) $_POST['option_email_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => $email_notify_players,
			),
			array( 'option_id' => (int) $_POST['option_email_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'email_notify_players',
				'option_value' => $email_notify_players,
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_max_h
	if ( isset( $_POST['option_max_h_id'] ) && (int) $_POST['option_max_h_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_max_h'] ),
			),
			array( 'option_id' => (int) $_POST['option_max_h_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'max_hours_per_reservation',
				'option_value' => sanitize_text_field( $_POST['option_max_h'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}

	// save option_half_hour
	$option_half_hour_value = '1';
	if ( isset( $_POST['option_half_hour'] ) ) {
		// Checkbox is selected
		$option_half_hour_value = '1';
	} else {
		$option_half_hour_value = '0';
	}
	if ( isset( $_POST['option_half_hour_id'] ) && (int) $_POST['option_half_hour_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => $option_half_hour_value,
			),
			array( 'option_id' => (int) $_POST['option_half_hour_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'half_hour_reservation',
				'option_value' => $option_half_hour_value,
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_several_reserve_person
	$option_several_reserve_person_value = '1';
	if ( isset( $_POST['option_several_reserve_person'] ) ) {
		// Checkbox is selected
		$option_several_reserve_person_value = '1';
	} else {
		$option_several_reserve_person_value = '0';
	}
	if ( isset( $_POST['option_several_reserve_person_id'] ) && (int) $_POST['option_several_reserve_person_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => $option_several_reserve_person_value,
			),
			array( 'option_id' => (int) $_POST['option_several_reserve_person_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'several_reserve_person',
				'option_value' => $option_several_reserve_person_value,
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}
	// save option_several_reserve_person
	$option_calender_view_navigator_value = '1';
	if ( isset( $_POST['option_calender_view_navigator'] ) ) {
		// Checkbox is selected
		$option_calender_view_navigator_value = '1';
	} else {
		$option_calender_view_navigator_value = '0';
	}
	if ( isset( $_POST['option_calender_view_navigator_id'] ) && (int) $_POST['option_calender_view_navigator_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => $option_calender_view_navigator_value,
			),
			array( 'option_id' => (int) $_POST['option_calender_view_navigator_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'calender_view_navigator',
				'option_value' => $option_calender_view_navigator_value,
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}

	// save option_available_reservation_types
	if ( isset( $_POST['option_available_reservation_types'] ) ) {

		$option_available_san = array();
		if ( count( $_POST['option_available_reservation_types'] ) > 0 ) {
			foreach ( $_POST['option_available_reservation_types'] as $option_available_san_k => $option_available_san_v ) {
				$option_available_san[ $option_available_san_k ] = sanitize_text_field( $option_available_san_v );
			}
		}

		if ( (int) $_POST['option_available_reservation_types_id'] > 0 ) { // edit
			/*
			$wpdb->update($table_name,
				array(
					// 'option_value' => serialize($option_available_san),
				),
				array('option_id' => (int) $_POST['option_available_reservation_types_id']),
				array('%s')
			); */
			update_option( 'available_reservation_types', $option_available_san );
			$message = __( 'Successfully changed!', 'court-reservation' );
		} else { // create
			/*
			$wpdb->insert($table_name,
				array(
					'option_name' => 'available_reservation_types',
					// 'option_value' => serialize($option_available_san),
				),
				array('%s', '%s')
			); */
			add_option( 'available_reservation_types', $option_available_san );
			$message = __( 'Successfully created!', 'court-reservation' );
		}
	}

	// save option_email_template
	if ( isset( $_POST['option_email_template'] ) && (int) $_POST['option_email_template_id'] > 0 ) { // edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => sanitize_text_field( $_POST['option_email_template'] ),
			),
			array( 'option_id' => (int) $_POST['option_email_template_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else { // create
		if (!isset($_POST['option_email_template'])) { $_POST['option_email_template']=""; }
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_email_template',
				'option_value' => sanitize_text_field( $_POST['option_email_template'] ),
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}

	// save option_is_email_template_updated
	$option_is_email_template_updated = isset( $_POST['option_is_email_template_updated'] ) ? sanitize_text_field( $_POST['option_is_email_template_updated'] ) : false;
	if ( ! $_POST['option_is_email_template_updated_id'] ) {
		// create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_is_email_template_updated',
				'option_value' => intval( $option_is_email_template_updated ),
			),
			array( '%s', '%d' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}


	// save option_is_team_mate_mandatory
	$option_is_team_mate_mandatory = '0';
	if ( isset( $_POST['option_is_team_mate_mandatory'] ) ) {
		// Checkbox is selected
		$option_is_team_mate_mandatory = '1';
	}
	if ( $_POST['option_is_team_mate_mandatory_id'] ) {
		// edit
		$wpdb->update(
			$table_name,
			array(
				'option_value' => $option_is_team_mate_mandatory,
			),
			array( 'option_id' => (int) $_POST['option_is_team_mate_mandatory_id'] ),
			array( '%s' )
		);
		$message = __( 'Successfully changed!', 'court-reservation' );
	} else {
		// create
		$wpdb->insert(
			$table_name,
			array(
				'option_name'  => 'option_is_team_mate_mandatory',
				'option_value' => $option_is_team_mate_mandatory,
			),
			array( '%s', '%s' )
		);
		$message = __( 'Successfully created!', 'court-reservation' );
	}


	if ( isset( $_POST['option_reservation_type_color'] ) ) {
			update_option( 'option_reservation_type_color', array_map( 'sanitize_text_field', $_POST['option_reservation_type_color'] ) );
			$message = __( 'Successfully changed!', 'court-reservation' );
	}

	// save option_max_players_for_reserv_type
	if ( isset( $_POST['option_max_players_for_reserv_type'] ) ) {
		if ( (int) $_POST['option_max_players_for_reserv_type_id'] > 0 ) { // edit
			/*
			$wpdb->update($table_name,
				array(
					// 'option_value' => serialize($_POST['option_max_players_for_reserv_type']),
				),
				array('option_id' => (int) $_POST["option_max_players_for_reserv_type_id"]),
				array('%s')
			);
			 */
			update_option( 'max_players_for_reserv_type', array_map( 'sanitize_text_field', $_POST['option_max_players_for_reserv_type'] ) );
			$message = __( 'Successfully changed!', 'court-reservation' );
		} else { // create
			/*
			$wpdb->insert($table_name,
				array(
					'option_name' => 'max_players_for_reserv_type',
					// 'option_value' => serialize($_POST['option_max_players_for_reserv_type']),
				),
				array('%s', '%s')
			);
			 */
			add_option( 'max_players_for_reserv_type', array_map( 'sanitize_text_field', $_POST['option_max_players_for_reserv_type'] ) );
			$message = __( 'Successfully created!', 'court-reservation' );
		}
	}

$min_players_for_reserv_type = get_option( 'court_min_players_for_reserv_type' );
print_r($min_players_for_reserv_type);
	// save option_min_players_for_reserv_type
	if ( isset( $_POST['option_min_players_for_reserv_type'] ) ) {
		/*
		if ( (int) $_POST['option_min_players_for_reserv_type_id'] > 0 ) { // edit
			echo "----   update!  ---  ";
			print_r($_POST['option_min_players_for_reserv_type']);
			/*
			$wpdb->update($table_name,
				array(
					// 'option_value' => serialize($_POST['option_min_players_for_reserv_type']),
				),
				array('option_id' => (int) $_POST["option_min_players_for_reserv_type_id"]),
				array('%s')
			); */
			update_option( 'court_min_players_for_reserv_type', array_map( 'sanitize_text_field', $_POST['option_min_players_for_reserv_type'] ) );
			$message = __( 'Successfully changed!', 'court-reservation' );
			/*
		} else { // create
			echo "----   create!  ---  ";
			print_r($_POST['option_min_players_for_reserv_type']);
			/*
			$wpdb->insert($table_name,
				array(
					'option_name' => 'court_min_players_for_reserv_type',
					// 'option_value' => serialize($_POST['option_min_players_for_reserv_type']),
				),
				array('%s', '%s')
			); *
			add_option( 'court_min_players_for_reserv_type', array_map( 'sanitize_text_field', $_POST['option_min_players_for_reserv_type'] ) );
			$message = __( 'Successfully created!', 'court-reservation' );
		}
		 */
	}

$min_players_for_reserv_type = get_option( 'court_min_players_for_reserv_type' );
print_r($min_players_for_reserv_type);
	echo "</pre>";

	// save or update option_match_durations
	if ( isset( $_POST['match_durations'] ) ) {
		$match_durations_ts = array();
		foreach ( $_POST['match_durations'] as $key => $match_duration ) {
			$match_durations_ts[ $key ] = $match_duration['hours'] * 3600 + $match_duration['min'] * 60;
		}

		if ( get_option( 'match_durations_ts' ) ) {
				update_option( 'match_durations_ts', $match_durations_ts );
			$message = __( 'Successfully changed!', 'court-reservation' );
		} else {
				add_option( 'match_durations_ts', $match_durations_ts );
			$message = __( 'Successfully created!', 'court-reservation' );
		}
		/*
		if (isset($_POST["option_match_durations_ts_id"]) && (int) $_POST["option_match_durations_ts_id"] > 0) { // edit
			/*
			$wpdb->update($table_name,
				array(
					// 'option_value' => serialize($match_durations_ts),
				),
				array('option_id' => (int) $_POST["option_match_durations_ts_id"]),
				array('%s')
			); *
			update_option( 'match_durations_ts', $match_durations_ts );
			$message = __('Successfully changed!', 'court-reservation');
		} else { // create
			/*
			$wpdb->insert($table_name,
				array(
					'option_name' => 'match_durations_ts',
					// 'option_value' => serialize($match_durations_ts),
				),
				array('%s', '%s')
			); *
			add_option( 'match_durations_ts', $match_durations_ts );
			$message = __('Successfully created!', 'court-reservation');
		}
		*/
	}
}
// < submit


$option_email = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'email_notify_players'" );
if ( ! isset( $option_email ) ) {
	$option_email               = new stdClass();
	$option_email->option_id    = 0;
	$option_email->option_name  = 'email_notify_players';
	$option_email->option_value = '0';
}

$option_max_h = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'max_hours_per_reservation'" );
if ( ! isset( $option_max_h ) ) {
	$option_max_h               = new stdClass();
	$option_max_h->option_id    = 0;
	$option_max_h->option_name  = 'max_hours_per_reservation';
	$option_max_h->option_value = static::DEFAULT_MAX_HOURS;
}

$option_half_hour = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'half_hour_reservation'" );
if ( ! isset( $option_half_hour ) ) {
	$option_half_hour               = new stdClass();
	$option_half_hour->option_id    = 0;
	$option_half_hour->option_name  = 'half_hour_reservation';
	$option_half_hour->option_value = '0';
}

$option_several_reserve_person = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'several_reserve_person'" );
if ( ! isset( $option_several_reserve_person ) ) {
	$option_several_reserve_person               = new stdClass();
	$option_several_reserve_person->option_id    = 0;
	$option_several_reserve_person->option_name  = 'several_reserve_person';
	$option_several_reserve_person->option_value = '0';
}

$option_calender_view_navigator = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'calender_view_navigator'" );
if ( ! isset( $option_calender_view_navigator ) ) {
	$option_calender_view_navigator               = new stdClass();
	$option_calender_view_navigator->option_id    = 0;
	$option_calender_view_navigator->option_name  = 'calender_view_navigator';
	$option_calender_view_navigator->option_value = '0';
}

// List of possible reservations types
$option_reservation_types = $this->getOption( 'reservation_types' );
if ( ! isset( $option_reservation_types ) ) {
	$option_reservation_types               = new stdClass();
	$option_reservation_types->option_id    = 0;
	$option_reservation_types->option_name  = 'reservation_types';
	$option_reservation_types->option_value = serialize( WP_FS_POSSIBLE_RESERVATIONS_TYPES );
}
$reservationTypes   = unserialize( $option_reservation_types->option_value );
$mlReservationTypes = array();
foreach ( $reservationTypes as $type ) {
	$mlReservationTypes[] = esc_html__( $type, 'court-reservation' );
}

// Available reservation types
$option_available_reservation_types = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'available_reservation_types'" );
/*
if (!isset($option_available_reservation_types)) {
	$option_available_reservation_types = new stdClass();
	$option_available_reservation_types->option_id = 0;
	$option_available_reservation_types->option_name = 'available_reservation_types';
	// $option_available_reservation_types->option_value = serialize(WP_FS_POSSIBLE_RESERVATIONS_TYPES);
} */
// $availableReservationTypes = unserialize($option_available_reservation_types->option_value);
$availableReservationTypes = get_option( 'available_reservation_types' );

// option_is_email_template_updated
// using to check if email_template is updated to new version
$option_is_email_template_updated = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_is_email_template_updated'" );
if ( ! isset( $option_is_email_template_updated ) ) {
	$option_is_email_template_updated               = new stdClass();
	$option_is_email_template_updated->option_id    = 0;
	$option_is_email_template_updated->option_name  = 'option_is_email_template_updated';
	$option_is_email_template_updated->option_value = true;
	$message_type                                   = 'warning';
	$message                                        = __( 'Court Reservation Plugin: You need to update your template for E-Mail notifications!', 'courtres' );
}

// option_email_template
$option_email_template            = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_email_template'" );
$email_templates_default_settings = Courtres::get_default_settings( 'email_template' );
if ( isset( $option_email_template ) ) {
	if ( $option_email_template->option_value == $email_templates_default_settings['old'] ) {
		$option_email_template->option_value = $email_templates_default_settings['1.5.0'];
	}
} else {
	$option_email_template               = new stdClass();
	$option_email_template->option_id    = 0;
	$option_email_template->option_name  = 'option_email_template';
	$option_email_template->option_value = $email_templates_settings['1.5.0'];
}


// option_is_team_mate_mandatory
$option_is_team_mate_mandatory = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_is_team_mate_mandatory'" );
if ( ! isset( $option_is_team_mate_mandatory ) ) {
	$option_is_team_mate_mandatory               = new stdClass();
	$option_is_team_mate_mandatory->option_id    = 0;
	$option_is_team_mate_mandatory->option_name  = 'option_is_team_mate_mandatory';
	$option_is_team_mate_mandatory->option_value = false;
}

// Reservation_type_color
$option_reservation_type_color = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_reservation_type_color'" );
if ( !isset( $option_reservation_type_color ) ) {
	$option_reservation_type_color               = new stdClass();
	$option_reservation_type_color->option_id    = 0;
	$option_reservation_type_color->option_name  = 'reservation_type_color';
	$option_reservation_type_color->option_value = '';
}
$reservation_type_color = get_option( 'option_reservation_type_color' );

// Max players for reservation types
echo "<pre>";
$option_max_players_for_reserv_type = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'max_players_for_reserv_type'" );
echo $table_name;
print_r($option_max_players_for_reserv_type);
if ( ! isset( $option_max_players_for_reserv_type ) ) {
	$option_max_players_for_reserv_type               = new stdClass();
	$option_max_players_for_reserv_type->option_id    = 0;
	$option_max_players_for_reserv_type->option_name  = 'max_players_for_reserv_type';
	$option_max_players_for_reserv_type->option_value = '';
}
$max_players_for_reserv_type = get_option( 'max_players_for_reserv_type' );

// $max_players_for_reserv_type = $option_max_players_for_reserv_type->option_value;


// Min players for reservation types
$option_min_players_for_reserv_type = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'min_players_for_reserv_type'" );
print_r($option_min_players_for_reserv_type);

echo "</pre>";
if ( ! isset( $option_min_players_for_reserv_type ) ) {
	$option_min_players_for_reserv_type               = new stdClass();
	$option_min_players_for_reserv_type->option_id    = 0;
	$option_min_players_for_reserv_type->option_name  = 'court_min_players_for_reserv_type';
	$option_min_players_for_reserv_type->option_value = '';
}
$min_players_for_reserv_type = get_option( 'court_min_players_for_reserv_type' );
// $min_players_for_reserv_type = unserialize($option_min_players_for_reserv_type->option_value);

// match_durations for reservation types
// values from db or default
// $option_match_durations_ts = $wpdb->get_row("SELECT * FROM $table_name WHERE option_name = 'match_durations_ts'");
$option_match_durations_ts = get_option( 'match_durations_ts' );
if ( ! isset( $option_match_durations_ts ) ) {
	$option_match_durations_ts               = new stdClass();
	$option_match_durations_ts->option_id    = 0;
	$option_match_durations_ts->option_name  = 'match_durations_ts';
	$option_match_durations_ts->option_value = '';
	if ( isset( $reservationTypes ) ) {
		foreach ( $reservationTypes as $key => $type ) {
			$match_durations_ts[ $type ] = 0;
		}
	}
} else {
	foreach ( $option_match_durations_ts as $key => $value ) {
		$match_durations_ts[ $key ] = $value;
	}
}

$match_durations = array();
if ( isset( $match_durations_ts ) ) {
	foreach ( $match_durations_ts as $key => $match_duration_ts ) {
		$match_durations[ $key ] = array(
			'hours' => floor( $match_duration_ts / 3600 ),
			'min'   => (int) date_i18n( 'i', $match_duration_ts ),
		);
	}
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php
require 'courtres-notice-upgrade.php';
require 'courtres-notice-message.php';

?>

<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php echo esc_html__( 'Settings', 'court-reservation' ); ?>
	</h1>
	<hr class="wp-header-end">

	<div class="cr-tabs-wrap">
		<div class="item1">
			<div class="cr-widget-right">
				<?php
					require 'courtres-widget-upgrade.php';
				?>
			</div>
		</div>
		<div  class="item2">
			<h2 class="nav-tab-wrapper wp-clearfix">
				<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=0' )); ?>" class="nav-tab">
					<?php echo esc_html__( 'Courts', 'court-reservation' ); ?>
				</a>
				<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=1' )); ?>" class="nav-tab">
					<?php echo esc_html__( 'Pyramids', 'court-reservation' ); ?>
				</a>
				<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=2' )); ?>" class="nav-tab nav-tab-active"><?php echo esc_html__( 'Settings', 'court-reservation' ); ?></a>
				<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=3' )); ?>" class="nav-tab">
					<?php echo esc_html__( 'User Interface', 'court-reservation' ); ?>
				</a>
				<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=5' )); ?>" class="nav-tab">
					<?php echo esc_html__( 'E-mail Notification', 'court-reservation' ); ?>
				</a>
				<?php if ( ! cr_fs()->is_plan( 'ultimate' ) ) { ?>
					<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=4' )); ?>" class="nav-tab">
						<?php echo esc_html__( 'Upgrade', 'court-reservation' ); ?>
					</a>
				<?php } ?>
			</h2>

			<form method="post">
				<input type="hidden" name="option_email_id" value="<?php echo esc_attr( $option_email->option_id ); ?>" />
				<input type="hidden" name="option_max_h_id" value="<?php echo esc_attr( $option_max_h->option_id ); ?>" />
				<input type="hidden" name="option_half_hour_id" value="<?php echo esc_attr( $option_half_hour->option_id ); ?>" />
				<input type="hidden" name="option_several_reserve_person_id" value="<?php echo esc_attr( $option_several_reserve_person->option_id ); ?>" />
				<input type="hidden" name="option_calender_view_navigator_id" value="<?php echo esc_attr( $option_calender_view_navigator->option_id ); ?>" />
				<input type="hidden" name="option_reservation_types_id" value="<?php echo esc_attr( $option_reservation_types->option_id ); ?>" />
				<input type="hidden" name="option_available_reservation_types_id" value="<?php echo esc_attr( $option_available_reservation_types->option_id ); ?>" />
				<input type="hidden" name="option_email_template_id" value="<?php echo esc_attr( $option_email_template->option_id ); ?>" />
				<input type="hidden" name="option_is_team_mate_mandatory_id" value="<?php echo esc_attr( $option_is_team_mate_mandatory->option_id ); ?>" />
				<input type="hidden" name="option_max_players_for_reserv_type_id" value="<?php echo esc_attr( $option_max_players_for_reserv_type->option_id ); ?>" />
				<input type="hidden" name="option_min_players_for_reserv_type_id" value="<?php echo esc_attr( $option_min_players_for_reserv_type->option_id ); ?>" />
				<input type="hidden" name="option_is_email_template_updated_id" value="<?php echo esc_attr( $option_is_email_template_updated->option_id ); ?>" />
				<input type="hidden" name="option_is_email_template_updated" value="<?php echo esc_attr( $option_is_email_template_updated->option_value ); ?>" />
<?php /*
				<input type="hidden" name="option_match_durations_ts_id" value="<?php echo esc_attr( $option_match_durations_ts->option_id ); ?>" />
*/ ?>

				<table class="t-form">
					<tr>
						<td>
							<?php echo esc_html__( 'Max. hours to reserve per reservation (used by if the Fixed Match Duration option for the reservation type not defined)', 'court-reservation' ); ?>
						</td>
						<td>
							<input type="number" name="option_max_h" min="0" max="24" maxlength="2" value="<?php echo esc_html( $option_max_h->option_value ); ?>">
							<div class="tooltip">
								<div class="symbol">
									<span>?</span>
								</div>
								<span class="tooltiptext tooltip-right">
									<?php echo esc_html__( 'Max. hours a member is allowed to reserve a court.', 'court-reservation' ); ?> 
									<?php echo esc_html(static::DEFAULT_MAX_HOURS) . ' ' . esc_html__( 'by default', 'court-reservation' ); ?></span>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo esc_html__( 'Half-hour reservation', 'court-reservation' ); ?>
						</td>
						<td>
							<label class="switch">
								<input type="checkbox" name="option_half_hour" <?php echo ( $option_half_hour->option_value === '1' ) ? 'checked' : ''; ?>>
								<span class="slider round"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo esc_html__( 'Several reservations per person', 'court-reservation' ); ?>
						</td>
						<td>
							<label class="switch">
								<input type="checkbox" name="option_several_reserve_person" <?php echo ( $option_several_reserve_person->option_value === '1' ) ? 'checked' : ''; ?>>
								<span class="slider round"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo esc_html__( 'Calendar navigation', 'court-reservation' ); ?>
						</td>
						<td>
							<label class="switch">
								<input type="checkbox" name="option_calender_view_navigator" <?php echo ( $option_calender_view_navigator->option_value === '1' ) ? 'checked' : ''; ?>>
								<span class="slider round"></span>
							</label>
						</td>
					</tr>
<?php /* removing this function
					<tr>
						<td>
							<?php echo esc_html__( 'Team mate is mandatory', 'court-reservation' ); ?>
						</td>
						<td>
							<label class="switch">
								<input type="checkbox" name="option_is_team_mate_mandatory" <?php checked( $option_is_team_mate_mandatory->option_value ); ?> value="1">
								<span class="slider round"></span>
							</label>
						</td>
					</tr>
*/ ?>
					<tr class="cr-reserv-type-row">
						<td>
							<?php echo esc_html__( 'Choose available reservation types', 'court-reservation' ); ?>
						</td>
						<td>
							<ul class="cr-reserv-type-list">
								<?php foreach ( $reservationTypes as $type ) : ?>
									<li>
										<label for="<?php echo esc_attr( $type ); ?>"><span><?php echo esc_html_e( $type, 'court-reservation' ); ?></span></label>
										<label class="switch">
											<input type="checkbox" id="<?php echo esc_attr( $type ); ?>" name="option_available_reservation_types[<?php echo esc_attr( $type ); ?>]" <?php if (is_array($availableReservationTypes)) { checked( in_array( $type, $availableReservationTypes ) ); } ?> value="<?php echo esc_attr( $type ); ?>">
											<span class="slider round"></span>
										</label>
										<a href="javascript:void(0);" class="cr-delete-reserv-type-link cr-no-underline"><span class="dashicons dashicons-no-alt"></span></a>&emsp;  
										<br>
										
										<!-- Max. Number of Other Players -->
										<label for="max_players_for_reserv_type_<?php echo esc_attr( $type ); ?>"><span><?php echo esc_html_e( 'Max. Number of Other Players', 'court-reservation' ); ?></span></label>
										<?php
											$max_players = isset( $max_players_for_reserv_type[ $type ] ) ? esc_html( $max_players_for_reserv_type[ $type ] ) : 0;
										?>
										<input type="number" min="1" max="100" id="max_players_for_reserv_type_<?php echo esc_html( $type ); ?>" name="option_max_players_for_reserv_type[<?php echo esc_attr( $type ); ?>]" value="<?php echo esc_html( $max_players ); ?>" size="5">
										<br>
										
										<!-- Min. Number of Other Players -->
										<label for="min_players_for_reserv_type_<?php echo esc_attr( $type ); ?>"><span><?php echo esc_html_e( 'Min. Number of Other Players', 'court-reservation' ); ?></span></label>
										<?php
											$min_players = isset( $min_players_for_reserv_type[ $type ] ) ? $min_players_for_reserv_type[ $type ] : 0;
										?>
										<input type="number" min="0" max="100" id="min_players_for_reserv_type_<?php echo esc_attr( $type ); ?>" name="option_min_players_for_reserv_type[<?php echo esc_attr( $type ); ?>]" value="<?php echo esc_html( $min_players ); ?>" size="5">
										<br>

										<!-- Fixed Match Duration -->
										<label for="option_fixed_match_durations_<?php echo esc_attr( $type ); ?>"><span><?php echo esc_html_e( 'Max Match Duration', 'court-reservation' ); ?></span></label>
										<?php
											$match_duration = isset( $match_durations[ $type ] ) ? $match_durations[ $type ] : array(
												'hours' => 0,
												'min'   => 0,
											);
											?>
										<input type="number" name="match_durations[<?php echo esc_attr( $type ); ?>][hours]" id="option_fixed_match_durations_<?php echo esc_attr( $type ); ?>" min="0" size="5" autocomplete="off" value="<?php echo esc_attr( $match_duration['hours'] ); ?>" />&nbsp;:&nbsp;
										<select name="match_durations[<?php echo esc_attr( $type ); ?>][min]">
											<option value="0" <?php selected( $match_duration['min'], 0 ); ?>>00</option>
											<option value="30" <?php selected( $match_duration['min'], 30 ); ?>>30</option>
										</select>
										<br>
									    <?php if ( cr_fs()->is_plan( 'ultimate' ) ) { ?>
										<?php echo esc_html_e( 'Choose a color', 'court-reservation' ); 
										$reservation_color = isset( $reservation_type_color[ $type ] ) ? $reservation_type_color[ $type ] : 0; ?>
										<input class="color-input" data-huebee name="option_reservation_type_color[<?php echo esc_attr( $type ); ?>]" value="<?php echo esc_attr( $reservation_color ); ?>" placeholder="<?php echo esc_attr__( 'default', 'court-reservation' ); ?>">
										<br>
									    <?php } ?>


										<br>
									</li>
								<?php endforeach; ?>
							</ul>
							<p class="cr-new-reserv-type-block">
								<button class="button cr-show-new-reserv-type-input" type="button" title="<?php echo esc_html__( 'You can add reservation type', 'court-reservation' ); ?>">+</button>
								<span class="cr-new-reserv-type-input-block">
									<input type="text" name="custom_reservation_type" class="cr-new-reserv-type" placeholder="<?php echo esc_html__( 'Add your reservation type', 'court-reservation' ); ?>" value="">
									<button class="button cr-save-new-reserv-type" type="button"><?php echo esc_html__( 'Save', 'court-reservation' ); ?></button>
								</span>
							</p>
						</td>
					</tr>
					<tr><td></td><td></td></tr>
					<tr>
						<td>
							<input class="button" type="submit" name="submit" value="<?php echo esc_html__( 'Save', 'court-reservation' ); ?>" />
						</td>
						<td></td>
					</tr>
			</form>
		</div>

	</div>

	<p></p>
</div>
