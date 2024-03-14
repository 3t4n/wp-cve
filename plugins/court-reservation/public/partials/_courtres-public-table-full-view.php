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

if ( ! function_exists( 'proba' ) ) {
	function proba( $sto ) {
		echo '<pre>';
		print_r( $sto );
		echo '</pre>'; }
}


if ( ! $this->isCourtPremium( $courtID ) ) {
	echo( esc_html__( 'Free version allow one Court only.', 'court-reservation' ) );
	return;
}

	$courts_full = $this->getAllCourts();
	// proba($courts_full);

	$atts = array_change_key_case( (array) $atts, CASE_LOWER );
	// proba($atts);

if ( ! isset( $atts['id'] ) ) {

	foreach ( $courts_full as $court_full ) {
		// proba($court_full->id);
		$court_each    = $court_full->id;
		$court_ispis[] = $this->getCourtByID( $court_each );
		// proba($court_ispis);
	}
} else {
	$ideki = explode( ',', $atts['id'] );
	foreach ( $ideki as $idek ) {
		foreach ( $courts_full as $court_full ) {
			// proba($court_full->id);
			$court_each = $court_full->id;
			if ( $court_each == $idek ) {
				$court_ispis[] = $this->getCourtByID( $court_each );
			}
			// proba($court_ispis);
		}
	}
}

	$mayEdit  = current_user_can( 'place_reservation' );
	$username = '';

	$players = $this->getAvailablePlayers();


foreach ( $court_ispis as $court_ispis_pojedini ) {


	if ( ! isset( $this->blocks[0] ) ) {
		$this->blocks = $this->getBlocksRepeatFutureByID( $court_ispis_pojedini->id );
	} else {
		$blokici = $this->getBlocksRepeatFutureByID( $court_ispis_pojedini->id );
		foreach ( $blokici as $blokic ) {
			$this->blocks[] = $blokic;
		}
	}
	$this->reservations = $this->getCurrentReservationsByID( $court_ispis_pojedini->id, $court_ispis_pojedini->days + 1 );
}

	$this->isReservatedPerPersonInFuture = $this->countUpcomingUserReservations( wp_get_current_user()->ID );
	$this->isSeveralReservePerson        = $this->getOptionValue( 'several_reserve_person' );

	$maxhours                  = $this->getMaxHours();
	$halfhour                  = $this->ishalfhour() ? '1' : '';
	$fromDay                   = isset( $_POST['from_day'] ) ? sanitize_text_field( $_POST['from_day'] ) : 0; // $court->days;
	$tillDay                   = $fromDay === 0 ? $court_ispis[0]->days : $fromDay + $court_ispis[0]->days;
	$availableReservationTypes = $this->getAvailableReservationTypes();
	$maxPlayers                = $this->getMaxPlayers();
	$minPlayers                = $this->getMinPlayers();
	$dateformats               = $this->getDateformats();
	$dateformat                = $this->getDateFormat();
	$found_index               = array_search( "$dateformat", array_column( $dateformats, 'format' ) );
	$isTeamMateMandatory       = $this->isTeamMateMandatory();
	$dateFormatName            = $dateformats[ $found_index ]['name'];
	$timeFormat                = ( $dateFormatName == 'USA' ) ? 'h:i a' : 'H:i';
	$matchDurations            = $this->getMatchDurations();


	$theTime = getCurrentDateTime();
	$nowTZ   = new DateTime( $theTime['datetime'] );
	$nowTZTS = $nowTZ->format( 'U' );
	$nowUTC  = date_i18n( 'l', $nowTZTS );
?>

<style>
.mob_plus { display: none !important; }

@media (max-width: 900px)
{
	.mob_minus { display: none !important; }
	table.reservations td, table.reservations th { white-space: normal; }
	.mob_width { width: 54px; }
	.mob_width100 { width: 100px; }
	<?php /* tr { display: block; } */ ?>
	.block_issue { display: block; }
	table.reservations td { padding: .75rem; }
	thead { background: transparent !important; }
	.mob_plus { display: flex !important; }
}
</style>

<?php
$allowed_html = array(
	'style'  => array()
);
echo wp_kses($this->option_ui_tbl_brdr_clr(), $allowed_html);
echo wp_kses($this->option_ui_tbl_bg_clr_1(), $allowed_html);
echo wp_kses($this->option_ui_tbl_bg_clr_2(), $allowed_html);
echo wp_kses($this->option_ui_tbl_bg_clr_3(), $allowed_html);
echo wp_kses($this->option_ui_tbl_bg_clr_4(), $allowed_html);
echo wp_kses($this->option_ui_link_clr(), $allowed_html);
echo wp_kses($this->option_ui_button_clr(), $allowed_html);
echo wp_kses($this->option_ui_table_cell_width(), $allowed_html);
echo wp_kses($this->option_ui_table_cell_height(), $allowed_html);
echo wp_kses($this->option_ui_table_cell_mouseover_background(), $allowed_html);
echo wp_kses($this->option_ui_table_cell_mouseover_linktext(), $allowed_html); ?>

	<table class="table reservations" id="cr-reservations-<?php echo esc_attr( $courtID ); ?>" style="width: auto; margin: 0 auto; min-width: 0;">
		<thead>
			<tr>

			<?php
			/*
			<th><?php echo  esc_html__('Time', 'court-reservation');?></th>
			 */
			?>

			<th class="mob_minus"></th>
			<?php

			if ( isset( $court_ispis ) ) {
				$broj_court = count( $court_ispis );
			} else {
				$broj_court = 0;
			}

			for ( $day = $fromDay; $day < $tillDay; $day++ ) {
				?>
					<td class="mob_minus" style="border-left: 6px solid white; width: 0; border-top: none; border-right: none; border-bottom: none; height: auto; padding: 0;"></td>
					<?php
					echo "<th class='block_issue";
					if ( $day != $fromDay ) {
						echo ' mob_minus'; }
					echo "' colspan='" . esc_attr( $broj_court ) . "'>" . esc_html(date_i18n( 'l', strtotime( '+' . $day . ' day', $nowTZTS ) )) . '<br/>' . esc_html(date_i18n( $dateformat, strtotime( '+' . $day . ' day', $nowTZTS ) )) . '</th>';
			}

				echo "</tr><tr><th class='mob_width'> &nbsp; </th>";

			for ( $day = $fromDay; $day < $tillDay; $day++ ) {
				?>

					<td class="mob_minus" style="border-left: 6px solid white; width: 0; border-top: none; border-right: none; border-bottom: none; height: auto; padding: 0;"></td>
					<?php

					foreach ( $court_ispis as $court_ispis_ ) {
						echo "<th class='mob_width100";
						if ( $day != $fromDay ) {
							echo ' mob_minus'; }
						echo "'>";
						echo esc_html( $court_ispis_->name );
						// proba($court_ispis_);
						echo '</th>';
					}
			}
			?>
		</thead>
		<tbody>
			<?php

				// for($j=$court->open; $j<$court->close; $j++) {
				// foreach($court_ispis as $court_ispis_)

					$court_each = $court_ispis_->id;
					$court      = $this->getCourtByID( $court_each );
					// print_r($court);


			for ( $j = $court->open; $j < $court->close; $j++ ) {
				?>



				<tr>
					<?php

						// 2020-06-18 astoian: bug for ngix, time is not formated correcty for AM:FM
						// date_i18n( $timeFormat, strtotime( '2000-01-01 ' . $j . ':00' ) );
						$t0 = date( $timeFormat, strtotime( $j . ':00' ) );
					if ( $this->ishalfhour() ) {
						$t1 = date( $timeFormat, strtotime( $j . ':00 + 30 min' ) );
					} else {
						$t1 = date( $timeFormat, strtotime( $j . ':00 + 1 hour' ) );
					}
						echo "<th class='mob_width'>" . esc_html( ( $t0 ) ) . ' &ndash; ' . esc_html( ( $t1 ) ) . '</th>';
					?>
						<!-- hour row -->

					<?php
					for ( $day = $fromDay; $day < $tillDay; $day++ ) {
						?>
							<td class="mob_minus" style="border-left: 6px solid white; width: 0;"></td>
							<?php
							foreach ( $court_ispis as $court_ispis_ ) {
								$con_court     = $court_ispis_->id;
								$court_ispis_2 = $this->getCourtByID( $con_court );
								if ( $day == $fromDay ) {
									$klasa = 'mob_OK mob_width100';
								} else {
									$klasa = 'mob_minus'; }

								$allowed_html = array(
									'a'  => array(
										'class'           => array(),
										'court-id'        => array(),
										'data-day'        => array(),
										'data-id'         => array(),
										'data-hour'       => array(),
										'data-hourD'      => array(),
										'data-date'       => array(),
										'data-date-display' => array(),
										'data-time'       => array(),
										'data-min-start'  => array(),
										'data-min-player' => array(),
									),
									'td' => array(
										'class'     => array(),
										'style'     => array(),
										'court-id'  => array(),
										'data-now'  => array(),
										'data-gid'  => array(),
										'rowspan'   => array(),
										'data-cell' => array(),
									),
									'br' => array(),
								);
								echo wp_kses( $this->getTD_multi( $court_ispis_2, $day, $j, 0, 30, date_i18n( 'Y-m-d', strtotime( '+' . $day . ' day', $nowTZTS ) ), $klasa ), $allowed_html );
								// echo $this->getTD_multi( $court_ispis_2, $day, $j, 0, 30, date_i18n("Y-m-d", strtotime('+'.$day.' day', $nowTZTS)), $klasa );
							}
					}
					?>
				</tr>

				<!-- half hour row -->
				<?php if ( $this->ishalfhour() ) { ?>
				<tr>
						<?php
							$t0     = date( $timeFormat, strtotime( $j . ':00 + 30 min' ) );
							$t1     = date( $timeFormat, strtotime( $j . ':00 + 1 hour' ) );
							echo "<th class='mob_width'>" . esc_html( ( $t0 ) ) . ' &ndash; ' . esc_html( ( $t1 ) ) . '</th>';
						?>
						<?php

						for ( $day = $fromDay;$day < $tillDay;$day++ ) {
							?>
								<td class="mob_minus" style="border-left: 6px solid white; width: 0;"></td>
								<?php
								// echo $this->getTD( $court, $day, $j, 30, 0, date_i18n("Y-m-d", strtotime('+'.$day.' day', $nowTZTS)) );
								foreach ( $court_ispis as $court_ispis_ ) {
									$con_court     = $court_ispis_->id;
									$court_ispis_2 = $this->getCourtByID( $con_court );
									if ( $day == $fromDay ) {
										$klasa = 'mob_OK mob_width100';
									} else {
										$klasa = 'mob_minus'; }
									$allowed_html = array(
										'a'  => array(
											'class'      => array(),
											'court-id'   => array(),
											'data-day'   => array(),
											'data-id'    => array(),
											'data-hour'  => array(),
											'data-hourD' => array(),
											'data-date'  => array(),
											'data-date-display' => array(),
											'data-time'  => array(),
											'data-min-start' => array(),
											'data-min-player' => array(),
										),
										'td' => array(
											'class'     => array(),
											'style'     => array(),
											'court-id'  => array(),
											'data-now'  => array(),
											'data-gid'  => array(),
											'rowspan'   => array(),
											'data-cell' => array(),
										),
										'br' => array(),
									);
									echo wp_kses( $this->getTD_multi( $court_ispis_2, $day, $j, 30, 0, date_i18n( 'Y-m-d', strtotime( '+' . $day . ' day', $nowTZTS ) ), $klasa ), $allowed_html );
									// echo $this->getTD_multi( $court_ispis_2, $day, $j, 30, 0, date_i18n("Y-m-d", strtotime('+'.$day.' day', $nowTZTS)), $klasa );
								}
						}
						?>
				</tr>
					<?php
				}
			}
			?>
		</tbody>
	</table>
