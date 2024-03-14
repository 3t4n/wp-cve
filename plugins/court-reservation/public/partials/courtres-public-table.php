<?php

/**
 * Provide a public-facing view for the plugin for ajax
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.3.0
 *
 * @package    Courtres
 * @subpackage Courtres/public/partials
 */
?>

<?php
  $atts = array_change_key_case( (array) $atts, CASE_LOWER );
if ( $courtID == 0 ) {
	echo( esc_html__( 'Court Reservation ID invalid.', 'court-reservation' ) );
	return;}

if ( ! $this->isCourtPremium( $courtID ) ) {
	echo( esc_html__( 'Free version allow one Court only.', 'court-reservation' ) );
	return;
}

  $court = $this->getCourtByID( $courtID );
if ( $court == null ) {
	echo( esc_html__( 'Court not found.', 'court-reservation' ) );
	return;
}

  $mayEdit  = current_user_can( 'place_reservation' );
  $username = '';

  $players = $this->getAvailablePlayers();

  $this->blocks                        = $this->getBlocksRepeatFutureByID( $court->id );
  $this->reservations                  = $this->getCurrentReservationsByID( $court->id, $court->days + 1 );
  $this->isReservatedPerPersonInFuture = $this->countUpcomingUserReservations( wp_get_current_user()->ID );
  $this->isSeveralReservePerson        = $this->getOptionValue( 'several_reserve_person' );

  $maxhours            = $this->getMaxHours();
  $halfhour            = $this->ishalfhour() ? '1' : '';
  $fromDay             = isset( $_REQUEST['navigator_step'] ) ? intval( $_REQUEST['navigator_step'] ) : 0; // $court->days;
  $tillDay             = $fromDay === 0 ? $court->days : $fromDay + $court->days;
  $_SESSION['cr_from'] = $fromDay;

  $availableReservationTypes = $this->getAvailableReservationTypes();
  $dateformats               = $this->getDateformats();
  $dateformat                = $this->getDateFormat();
  $found_index               = array_search( "$dateformat", array_column( $dateformats, 'format' ) );
  $dateFormatName            = $dateformats[ $found_index ]['name'];
  $timeFormat                = ( $dateFormatName == 'USA' ) ? 'h:i a' : 'H:i';
  $theTime                   = getCurrentDateTime();
  $nowTZ                     = new DateTime( $theTime['datetime'] );
  $nowTZTS                   = $nowTZ->format( 'U' );
?>

<!-- CR-TABLE -->
  <table style="display:none;" class="table reservations" id="cr-reservations-<?php echo esc_attr( $courtID ); ?>" data-navigator-my="<?php echo esc_html(date_i18n( 'F', strtotime( '+' . $fromDay . ' day', $nowTZTS ) )) . ' ' . esc_html(date_i18n( 'Y', strtotime( '+' . $fromDay . ' day', $nowTZTS ) )); ?>">
	<thead>
	  <th><?php echo esc_html__( 'Time', 'court-reservation' ); ?></th>
	  <?php
		for ( $day = $fromDay; $day < $tillDay; $day++ ) {
			echo '<th>' . esc_html(date_i18n( 'l', strtotime( '+' . $day . ' day', $nowTZTS ) )) . '<br/>' . esc_html(date_i18n( $dateformat, strtotime( '+' . $day . ' day', $nowTZTS ) )) . '</th>';
		}
		?>
	</thead>
	<tbody>
	  <?php for ( $j = $court->open; $j < $court->close; $j++ ) { ?>
		<!-- hour row -->
		<tr>
			<?php
			// 2020-06-18 astoian: bug for ngix, time is not formated correcty for AM:FM
			// $t0 = date_i18n( $timeFormat, strtotime( '2000-01-01 ' . $j . ':00' ) );
			$t0 = date( $timeFormat, strtotime( $j . ':00' ) );
			if ( $this->ishalfhour() ) {
				$t1 = date( $timeFormat, strtotime( $j . ':00 + 30 min' ) );
			} else {
				$t1 = date( $timeFormat, strtotime( $j . ':00 + 1 hour' ) );
			}
			echo '<th>' . esc_html( $t0 ) . ' &ndash; ' . esc_html( $t1 ) . '</th>';
			?>
			<?php
			for ( $day = $fromDay;$day < $tillDay;$day++ ) {
							$allowed_html = array(
								'a'  => array(
									'class'             => array(),
									'court-id'          => array(),
									'data-day'          => array(),
									'data-id'           => array(),
									'data-hour'         => array(),
									'data-hourD'        => array(),
									'data-date'         => array(),
									'data-date-display' => array(),
									'data-time'         => array(),
									'data-min-start'    => array(),
									'data-min-player'   => array(),
								),
								'td' => array(
									'class'     => array(),
									'court-id'  => array(),
									'data-now'  => array(),
									'data-cell' => array(),
									'data-gid'  => array(),
									'rowspan'   => array(),
								),
							);
				echo wp_kses( $this->getTD( $court, $day, $j, 0, 30, date_i18n( 'Y-m-d', strtotime( '+' . $day . ' day', $nowTZTS ) ) ), $allowed_html );
			}
			?>
		</tr>
		<!-- half hour row -->
			<?php if ( $this->ishalfhour() ) { ?>
		  <tr>
				<?php
				$t0     = date( $timeFormat, strtotime( $j . ':00 + 30 min' ) );
				$t1     = date( $timeFormat, strtotime( $j . ':00 + 1 hour' ) );
				$fromto = '<th>' . esc_html( $t0 ) . ' &ndash; ' . esc_html( $t1 ) . '</th>';
							$allowed_html = array(
								'th'  => array(
									'class'             => array(),
									'court-id'          => array(),
									'data-day'          => array(),
									'data-id'           => array(),
									'data-hour'         => array(),
									'data-hourD'        => array(),
									'data-date'         => array(),
									'data-date-display' => array(),
									'data-time'         => array(),
									'data-min-start'    => array(),
									'data-min-player'   => array(),
								),
							);
				echo wp_kses( $fromto, $allowed_html );
				?>
				<?php
				for ( $day = $fromDay;$day < $tillDay;$day++ ) {
							$allowed_html = array(
								'a'  => array(
									'class'             => array(),
									'court-id'          => array(),
									'data-day'          => array(),
									'data-id'           => array(),
									'data-hour'         => array(),
									'data-hourD'        => array(),
									'data-date'         => array(),
									'data-date-display' => array(),
									'data-time'         => array(),
									'data-min-start'    => array(),
									'data-min-player'   => array(),
								),
								'td' => array(
									'class'     => array(),
									'court-id'  => array(),
									'data-now'  => array(),
									'data-cell' => array(),
									'data-gid'  => array(),
									'rowspan'   => array(),
								),
							);
							echo wp_kses( $this->getTD( $court, $day, $j, 30, 0, date_i18n( 'Y-m-d', strtotime( '+' . $day . ' day', $nowTZTS ) ) ), $allowed_html );
				}
				?>
		  </tr>
		<?php } ?>
	  <?php } ?>
	</tbody>
  </table>

