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

	if (isset($_POST['ponisti'])) { $_SESSION['cr_from']=0; }
	if (isset($_POST['prethodni'])) { $_SESSION['cr_from']=$_SESSION['cr_from']-$_POST['prethodni']; }
	if (isset($_POST['sljedeci'])) { $_SESSION['cr_from']=$_SESSION['cr_from']+$_POST['sljedeci']; }

	$atts = array_change_key_case( (array) $atts, CASE_LOWER );
if ( ! isset( $atts['id'] ) ) {
	echo( esc_html__( 'Court Reservation ID not set.', 'court-reservation' ) );
	return;}
	$courtID = (int) $atts['id'];
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

if ( is_user_logged_in() ) {
	$username = wp_get_current_user()->display_name;
}

	$players = $this->getAvailablePlayers();

	$this->blocks                        = $this->getBlocksRepeatFutureByID( $court->id );
	$this->reservations                  = $this->getCurrentReservationsByID( $court->id, $court->days + 1 );
	$this->isReservatedPerPersonInFuture = $this->countUpcomingUserReservations( wp_get_current_user()->ID );
	$this->isSeveralReservePerson        = $this->getOptionValue( 'several_reserve_person' );

	$maxhours                  = $this->getMaxHours();
	$halfhour                  = $this->ishalfhour() ? '1' : '';
	$fromDay                   = isset( $_SESSION['cr_from'] ) ? intval( $_SESSION['cr_from'] ) : 0; // $court->days;
	$tillDay                   = $fromDay === 0 ? $court->days : $fromDay + $court->days;
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



<!-- CR-DIALOG-RESERVE -->
<div id="cr-dialog-reserve-<?php echo esc_attr( $courtID ); ?>" class="cr-dialog-reserve" title="<?php echo esc_attr( $court->name ); ?> <?php echo esc_html__( 'Reservation', 'court-reservation' ); ?>" style="display:none;">
	<form id="cr-form-reserve-<?php echo esc_attr( $courtID ); ?>" class="resform" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" >
		<input type="hidden" name="action" value="add_reservation">
		<input type="hidden" name="courtid" value="<?php echo esc_attr( $court->id ); ?>" />
		<input type="hidden" name="maxhours" value="<?php echo esc_attr( $maxhours ); ?>" />
		<input type="hidden" name="halfhour" value="<?php echo esc_attr( $halfhour ); ?>" />
		<input type="hidden" name="date" />
		<input type="hidden" name="day" />
		<input type="hidden" name="hour" />
		<input type="hidden" name="minstart" />
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
<div class="table-responsive container-reservations" id="cr-table-<?php echo esc_attr( $courtID ); ?>" data-hour-close="<?php echo esc_attr( $court->close ); ?>" data-navigator-step="<?php echo esc_attr( $court->days ); ?>">

	<?php if ( $mayEdit ) : ?>
		<?php echo esc_html( $username ); ?>&ensp;
		<a href="<?php echo esc_attr(wp_logout_url( add_query_arg( $_GET ) )); ?>" class="menu-link">Logout</a>
	<?php else : ?>
		<a href="<?php echo esc_attr(wp_login_url( add_query_arg( $_GET ) )); ?>" class="login-link menu-link">Login</a>
	<?php endif; ?>

	<?php if ( $this->iscalenderviewnavigator() ) { 

		get_court_calendar($courtID,$nowTZTS,$fromDay,$tillDay,"single-view");

	} ?>
	<table class="table reservations" id="cr-reservations-<?php echo esc_attr( $courtID ); ?>">
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
						// date_i18n( $timeFormat, strtotime( '2000-01-01 ' . $j . ':00' ) );
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
								'style'     => array(),
								'court-id'  => array(),
								'data-now'  => array(),
								'data-cell' => array(),
								'data-gid'  => array(),
								'rowspan'   => array(),
							),
							'br' => array(),
						);
						echo wp_kses( $this->getTD( $court, $day, $j, 0, 30, date_i18n( 'Y-m-d', strtotime( '+' . $day . ' day', $nowTZTS ) ) ), $allowed_html );
						// echo htmlentities($this->getTD( $court, $day, $j, 0, 30, date_i18n("Y-m-d", strtotime('+'.$day.' day', $nowTZTS)) ));
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
							echo '<th>' . esc_html( $t0 ) . ' &ndash; ' . esc_html( $t1 ) . '</th>';
						?>
						<?php
						for ( $day = $fromDay;$day < $tillDay;$day++ ) {
							$allowed_html = array(
								'a'  => array(
									'class'             => array(),
									'style'     	    => array(),
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
									'style'     => array(),
									'court-id'  => array(),
									'data-now'  => array(),
									'data-cell' => array(),
									'data-gid'  => array(),
									'rowspan'   => array(),
								),
								'br' => array(),
							);
							echo wp_kses( $this->getTD( $court, $day, $j, 30, 0, date_i18n( 'Y-m-d', strtotime( '+' . $day . ' day', $nowTZTS ) ) ), $allowed_html );
						}
						?>
					</tr>
				<?php } ?>
			<?php } ?>
		</tbody>
	</table>
</div>

<!-- CR-DIALOG-LOGIN -->
<?php if ( ! is_user_logged_in() ) { ?>
	<div id="cr-dialog-login-<?php echo esc_attr( $courtID ); ?>" title="<?php echo esc_attr__( 'Login', 'court-reservation' ); ?>" style="display:none">
		<div id="login-error" style="display:none">
			<strong><?php echo esc_html__( 'ERROR', 'court-reservation' ); ?></strong>: <span id="login-error-text"></span>
			<!-- <a class="lost" href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php echo esc_html__( 'Forgot password?', 'court-reservation' ); ?></a> -->
		</div>
		<form id="cr-form-login-<?php echo esc_attr( $courtID ); ?>" class="cr-login-form" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"> 
			<input type="hidden" name="action" value="ajax_login">
			<table class="table table-striped">
					<tr>
						<td>
							<label><?php echo esc_html__( 'Login', 'court-reservation' ); ?></label>
							<input id="username" type="text" name="username">
						</td>
					</tr>
					<tr>
						<td>
							<label><?php echo esc_html__( 'Password', 'court-reservation' ); ?></label>
							<input id="password" type="password" name="password">
							<a class="lost" href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php echo esc_html__( 'Forgot password?', 'court-reservation' ); ?></a>
						</td>
					</tr>
				</table>
			<?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
		</form>
	</div>
<?php } ?>
