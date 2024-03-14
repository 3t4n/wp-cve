<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.0.3
 *
 * @package    Courtres
 * @subpackage Courtres/public/partials
 */
?>

<?php

if ( ! function_exists( 'proba' ) ) {
	function proba( $sto ) {
		echo '<pre>';
		print_r( $sto );
		echo '</pre>'; }
}

if (!isset($courtID)) { $courtID=""; }

if ( ! $this->isCourtPremium( $courtID ) ) {
	echo( esc_html__( 'Free version allow one Court only.', 'court-reservation' ) );
	return;
}

	$courts_full = $this->getAllCourts();
	// proba($courts_full);

	$atts = array_change_key_case( (array) $atts, CASE_LOWER );
	// proba($atts);

if ( ! isset( $atts['id'] ) ) {

	$atts_=array();
	foreach ( $courts_full as $court_full ) {
		// proba($court_full->id);
		$court_each    = $court_full->id;
		$court_ispis[] = $this->getCourtByID( $court_each );
		$atts_[] = $court_full->id;
		$cr_ids=array();
		// proba($court_ispis);
	}
	$atts['id']=implode(",",$atts_);
	$cr_ids[] = str_replace(",","_",$atts['id']);

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

if ( is_user_logged_in() ) {
	$username = wp_get_current_user()->display_name;
}

	$players = $this->getAvailablePlayers();

if (!isset($court_ispis) || !is_array($court_ispis)) { $court_ispis=array(); }

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

	if (!isset($court_ispis[0])) { $court_ispis[0]=(object)[]; }
	if (!isset($court_ispis[0]->days)) { $court_ispis[0]->days=0; }
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


	foreach ($court_ispis as $rb => $court_ob)
	{ 
		if ($rb!=0) { $courts_ob.="_"; }
		if (!isset($court_ob)) { $court_ob=(object)[]; }
		if (!isset($court_ob->id)) { $court_ob->id=0; }
		if (!isset($courts_ob)) { $courts_ob=""; }
		$courts_ob.=$court_ob->id;
	}
	$courtID=$courts_ob; ?>

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

<!-- CR-DIALOG-RESERVE -->

<?php 
if (!isset($court) || !is_object($court)) { $court = new stdClass(); }
if (!property_exists("court", "name")) { $court->name=""; }
if (!property_exists("court", "id")) { $court->id=""; }
?>


<div id="cr-dialog-reserve-<?php echo esc_attr( $courtID ); ?>" style="display:none;" class="cr-dialog-reserve" title="<?php echo esc_attr( $court->name ); ?> <?php echo esc_html__( 'Reservation', 'court-reservation' ); ?>">
	<form id="cr-form-reserve-<?php echo esc_attr( $courtID ); ?>" class="resform" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" >
		<input type="hidden" name="action" value="add_reservation">
		<input type="hidden" name="courtid" value="<?php echo esc_attr( $court->id ); ?>" />
		<input type="hidden" name="maxhours" value="<?php echo esc_attr( $maxhours ); ?>" />
		<input type="hidden" name="halfhour" value="<?php echo esc_attr( $halfhour ); ?>" />
		<input type="hidden" name="date" />
		<input type="hidden" name="day" />
		<input type="hidden" name="hour" />
		<input type="hidden" name="minstart" />
		<input type="hidden" name="minplayer" />
		<!-- <input type="hidden" name="minend" /> -->
		<table class="table table-striped form-fields-table">
			<tr>
				<td><?php echo esc_html__( 'Date', 'court-reservation' ); ?></td>
				<td>
					<div class="date"><span id="date">&ndash;</span></div>
					<!-- <span id="time">&ndash;</span> -->
				</td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Player', 'court-reservation' ); ?></td>
				<td>
					<?php if ( $mayEdit ) { ?>
						<?php echo esc_html( $username ); ?>
					<?php } else { ?>
						<input list="playerid" placeholder="Type or click to select">
						<datalist name="playerid" id="playerid">
							<option value="0" selected>-</option>
							<?php
							for ( $day = 0;$day < sizeof( $players );
							$day++ ) {
								$player = $players[ $day ];
								?>
								<option value="<?php echo esc_attr( $player->user_login ); ?>"><?php echo esc_html( $player->display_name ); ?></option>
							<?php } ?>
						</datalist>
						<div>
							<a class="login_button" id="cr-show-login"><?php echo esc_html__( 'Login', 'court-reservation' ); ?></a>
						</div>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Type', 'court-reservation' ); ?>*</td>
				<td>
					<select name="type" class="reservation-type-select" autocomplete="off" required>
						<option value=""><?php echo esc_html__( 'Select from list', 'court-reservation' ); ?></option>
						<?php foreach ( $availableReservationTypes as $type ) : ?>
							<option value="<?php echo esc_html__( $type, 'court-reservation' ); ?>" data-maxplayers="<?php echo esc_attr( $maxPlayers[ $type ] ); ?>" data-minplayers="<?php echo esc_attr( $minPlayers[ $type ] ); ?>" data-duration="<?php echo esc_attr( $matchDurations[ $type ] ); ?>"><?php echo esc_html__( $type, 'court-reservation' ); ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
		</table>
	</form>
	<div class="cr-preloader-overlay in-relative-block" id="plo-add-reserv"><div class="cr-preloader"></div></div>
</div>
<!-- 20.05.2019, astoian - colors -->
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

<!-- CR-TABLE -->
<?php if (!property_exists("court", "close")) { $court->close=""; } ?>

<div class="table-responsive container-reservations" id="cr-table-<?php echo esc_attr($courtID); ?>" data-navigator-step="<?php echo esc_attr($court_ob->days); ?>" data-hour-close="<?php echo esc_attr($court->close); ?>">

	<?php if ( $mayEdit ) : ?>
		<?php echo esc_html( $username ); ?>&ensp;
		<a href="<?php echo esc_attr(wp_logout_url( add_query_arg( $_GET ) )); ?>" class="menu-link">Logout</a>
	<?php else : ?>
		<a href="<?php echo esc_attr(wp_login_url( add_query_arg( $_GET ) )); ?>" class="login-link menu-link">Login</a>
	<?php endif; ?>

	<?php
	if ( $this->iscalenderviewnavigator() ) {

		if ( isset( $_POST['from_day'] ) ) {
			$plus     = sanitize_text_field( $_POST['from_day'] ) + $court_ispis[0]->days;
			$plus_mob = sanitize_text_field( $_POST['from_day'] ) + 1;
			$minus    = sanitize_text_field( $_POST['from_day'] ) - $court_ispis[0]->days;
			if ( $minus < 0 ) {
				$minus = 0; }
		} else {
			$plus     = $court_ispis[0]->days;
			$minus    = 0;
			$plus_mob = 1; }

		get_court_calendar($courtID,$nowTZTS,$fromDay,$tillDay,"full-view");
/*
		<div class="navigator">
			<form action="" method="post" name="navigacija" style="width: 100%;">
			<button type="submit" value="<?php echo esc_attr( $minus ); ?>" name="from_day" style="float: left; margin-right: 10px; font-weight: normal;   margin: 0 5px; margin-bottom: 0px; display: flex; flex-direction: row; margin-bottom: 0; font-weight: normal; text-align: center; vertical-align: middle; touch-action: manipulation; cursor: pointer !important; background-image: none; white-space: nowrap; padding: 6px 12px; font-size: inherit; line-height: 1.428571; border-radius: 4px; user-select: none; color: #3c4043; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background-color: transparent; border: 1px solid rgb(218, 220, 224); text-decoration: none; box-shadow: none; padding: 5px 12px; text-align: center; cursor: pointer !important; white-space: nowrap; font-size: inherit; line-height: 1.428571; color: #3c4043; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);">
				<span>&lt;</span>
			</button>
			<button type="submit" value="0" name="from_day" style="float: left; margin-right: 10px; font-weight: normal;   margin: 0 5px; margin-bottom: 0px; display: flex; flex-direction: row; margin-bottom: 0; font-weight: normal; text-align: center; vertical-align: middle; touch-action: manipulation; cursor: pointer !important; background-image: none; white-space: nowrap; padding: 6px 12px; font-size: inherit; line-height: 1.428571; border-radius: 4px; user-select: none; color: #3c4043; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background-color: transparent; border: 1px solid rgb(218, 220, 224); text-decoration: none; box-shadow: none; padding: 5px 12px; text-align: center; cursor: pointer !important; white-space: nowrap; font-size: inherit; line-height: 1.428571; color: #3c4043; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);">
				<span><?php echo esc_html__( 'Today', 'court-reservation' ); ?></span>
			</button>
			<button type="submit" class="mob_minus" value="<?php echo esc_attr( $plus ); ?>" name="from_day" style="float: left; margin-right: 10px; font-weight: normal;   margin: 0 5px; margin-bottom: 0px; display: flex; flex-direction: row; margin-bottom: 0; font-weight: normal; text-align: center; vertical-align: middle; touch-action: manipulation; cursor: pointer !important; background-image: none; white-space: nowrap; padding: 6px 12px; font-size: inherit; line-height: 1.428571; border-radius: 4px; user-select: none; color: #3c4043; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background-color: transparent; border: 1px solid rgb(218, 220, 224); text-decoration: none; box-shadow: none; padding: 5px 12px; text-align: center; cursor: pointer !important; white-space: nowrap; font-size: inherit; line-height: 1.428571; color: #3c4043; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);">
				<span>&gt;</span>
			</button>
			<button type="submit" class="mob_plus" value="<?php echo esc_attr( $plus_mob ); ?>" name="from_day" style="float: left; margin-right: 10px; font-weight: normal;   margin: 0 5px; margin-bottom: 0px; display: flex; flex-direction: row; margin-bottom: 0; font-weight: normal; text-align: center; vertical-align: middle; touch-action: manipulation; cursor: pointer !important; background-image: none; white-space: nowrap; padding: 6px 12px; font-size: inherit; line-height: 1.428571; border-radius: 4px; user-select: none; color: #3c4043; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); background-color: transparent; border: 1px solid rgb(218, 220, 224); text-decoration: none; box-shadow: none; padding: 5px 12px; text-align: center; cursor: pointer !important; white-space: nowrap; font-size: inherit; line-height: 1.428571; color: #3c4043; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);">
				<span>&gt;</span>
			</button>
			<div id="cr-today-my" style="float: right;"><?php echo esc_html(date_i18n( 'F', strtotime( '+0 day', $nowTZTS ) )) . ' ' . esc_html(date_i18n( 'Y', strtotime( '+0 day', $nowTZTS ) )); ?></div>
			</form>
		</div>
	<?php */ } ?>

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

					if (!isset($court_ispis_)) { $court_ispis_=(object)[]; }
					if (!isset($court_ispis_->id)) { $court_ispis_->id=0; }

					$court_each = $court_ispis_->id;
					$court      = $this->getCourtByID( $court_each );
					// print_r($court);


			if (!isset($court)) { $court=(object)[]; }
			if (!isset($court->open)) { $court->open=0; }
			if (!isset($court->close)) { $court->close=0; }

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
								echo wp_kses( $this->getTD_multi( $court_ispis_2, $day, $j, $klasa, 0, 30, date_i18n( 'Y-m-d', strtotime( '+' . $day . ' day', $nowTZTS ) ) ), $allowed_html );
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
									echo wp_kses( $this->getTD_multi( $court_ispis_2, $day, $j, $klasa, 30, 0, date_i18n( 'Y-m-d', strtotime( '+' . $day . ' day', $nowTZTS ) ) ), $allowed_html );
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
</div>

<!-- CR-DIALOG-LOGIN -->
<?php if ( ! is_user_logged_in() ) { ?>
	<div id="cr-dialog-login-<?php echo esc_attr( $courtID ); ?>" title="<?php echo esc_attr__( 'Login', 'court-reservation' ); ?>" style="display:none">
		<div id="login-error" style="display:none">
			<strong><?php echo esc_html__( 'ERROR', 'court-reservation' ); ?></strong>: <span id="login-error-text"></span>
			<!-- <a class="lost" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php echo esc_html__( 'Forgot password?', 'court-reservation' ); ?></a> -->
		</div>
		<form id="cr-form-login-<?php echo esc_attr( $courtID ); ?>" class="cr-login-form" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"> 
			<input type="hidden" name="action" value="ajax_login">
			<table class="table table-striped">
					<tr>
						<td>
							<label><?php echo esc_html( __( 'Login', 'court-reservation' ) ); ?></label>
							<input id="username" type="text" name="username">
						</td>
					</tr>
					<tr>
						<td>
							<label><?php echo esc_html( __( 'Password', 'court-reservation' ) ); ?></label>
							<input id="password" type="password" name="password">
							<a class="lost" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php echo esc_attr( __( 'Forgot password?', 'court-reservation' ) ); ?></a>
						</td>
					</tr>
				</table>
			<?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
		</form>
	</div>
<?php } ?>
