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
// 2021-03-14, astoian - if not ultimate, stop it
if ( ! $this->isCourtUltimate() ) {
	echo( esc_html__( 'Piramid is allowed in Ultimate version only.', 'court-reservation' ) );
	return;
}


// function_exists("fppr") ? fppr($atts, __FILE__.' $atts') : false;
$piramid     = isset( $atts['piramid'] ) && $atts['piramid'] ? $atts['piramid'] : array();
$design      = isset( $atts['piramid']['design'] ) && $atts['piramid']['design'] ? unserialize( $atts['piramid']['design'] ) : array();
$courts      = isset( $atts['courts'] ) && $atts['courts'] ? $atts['courts'] : array();
$player_user = $atts['player_user'];
$the_player  = $atts['the_player'];
?>


<?php if ( $piramid ) : ?>
<section class="cr-piramid" id="cr-piramid-<?php echo esc_attr( $piramid['id'] ); ?>" data-the_player = '<?php echo json_encode( $the_player ); ?>'>
	
	<?php if ( $player_user ) : ?>
		<?php echo esc_html( $player_user->display_name ); ?>&ensp;
		<a href="<?php echo esc_url(wp_logout_url( add_query_arg( $_GET ) )); ?>" class="menu-link">Logout</a>
	<?php else : ?>
		<a href="<?php echo esc_url(wp_login_url( add_query_arg( $_GET ) )); ?>" class="login-link menu-link">Login</a>
	<?php endif; ?>
	<br />

	<?php if ( isset( $piramid['players'] ) && $piramid['players'] ) : ?>
	<div class="cr-players-list" type="1" start="1">
		<?php
		$counter = 0;
		$row_len = 1;

		foreach ( $piramid['players'] as $key => $player ) :
			$enabled = $can_create_challenge &&
							$player_user && $player['player_id'] != $player_user->ID &&
							$the_player && $player['sort'] < $the_player['sort'] && $player['sort'] >= $the_player['sort'] - $row_len;
			?>
			<button 
				class="cr-player-item<?php echo ( $player_user && $player['player_id'] == $player_user->ID ? ' current' : '' ); ?>" 
				name="player" type="button" value="<?php echo esc_attr( $player['sort'] ); ?>" 
				data-player='<?php echo json_encode( $player ); ?>' 
				<?php disabled( $enabled, false ); ?>
			>
				<span class="position"><?php echo esc_html( $player['sort'] + 1 ); ?></span>
				<span class="name"><?php echo esc_html( $player['display_name'] ); ?></span>
			</button>
			<?php
			$counter++;
			if ( $counter == $row_len ) :
				$counter = 0;
				$row_len++;
				?>
				<div class="flex-break"></div>
			<?php endif; ?>
		<?php endforeach; ?>

		<!--   Dummy slots   -->
		<?php $last_number = $player['sort'] + 1; ?>
		<?php if ( $counter > 0 ) : ?>
			<?php if ( $counter < $row_len ) : ?>
				<?php for ( $i = 0; $i + $counter < $row_len; $i++ ) : ?>
					<button class="cr-player-item dummy" name="player" type="button" value="<?php echo esc_attr( $i ); ?>" disabled="disabled">
						<span class="position"><?php echo esc_html( $last_number + $i + 1 ); ?></span>
						<span class="name"></span>
					</button>
				<?php endfor; ?>
			<?php endif; ?>
		<?php endif; ?>

	</div>
	<?php endif; ?>

	<?php
	/*  Challenges Tables  */
	?>
	<p></p>
	<section class="cr-challenges-section">
		<?php echo do_shortcode( '[courtchallenges piramid_id="' . $piramid['id'] . '" title="' . __( 'Active challenges', 'court-reservation' ) . '" statuses="created, accepted, scheduled"]' ); ?>
		<?php echo do_shortcode( '[courtchallenges piramid_id="' . $piramid['id'] . '" title="' . __( 'Played challenges', 'court-reservation' ) . '" statuses="played, closed"]' ); ?>
	</section>
	<!-- DIALOG CREATE CHALLENGE -->
	<div class="cr-piramid-dialog cr-dialog-create-challenge" id="cr-dialog-create-challenge-<?php echo esc_html($piramid['id']); ?>" title="<?php esc_html_e( 'Create challenge', 'court-reservation' ); ?>" style="display: none;">
		<form class="cr-form cr-create-challenge-form" autocomplete="off">
			<input type="hidden" name="piramid_id" value="<?php echo esc_attr( $piramid['id'] ); ?>">
			<input type="hidden" name="challenger_id" value="<?php echo esc_attr( $player_user ? $player_user->ID : false ); ?>">
			<input type="hidden" name="challenged_id" value="">
			<p class="content"><strong><span class="challenger-name"></span> vs. <span class="challenged-name"></span>.</strong><br />
			<?php esc_html_e( 'Do you want to save the challenge?', 'court-reservation' ); ?></p>
			<?php wp_nonce_field( 'create_challenge', 'create_challenge_nonce' ); ?>
		  </form>
	</div>

	<!-- DIALOG SCHEDULE CHALLENGE -->
	<div class="cr-piramid-dialog cr-dialog-schedule-challenge" id="cr-dialog-schedule-challenge-<?php echo esc_attr( $piramid['id'] ); ?>" title="<?php esc_html_e( 'Schedule challenge', 'court-reservation' ); ?>" style="display: none;">
		<form class="cr-form cr-schedule-challenge-form" id="cr-schedule-challenge-form" autocomplete="off">
			<p class="content" autofocus><?php esc_html_e( 'Do you want to save the challenge?', 'court-reservation' ); ?></p>
			
			<fieldset>

				<label for="cr-game-date-<?php echo esc_attr( $piramid['id'] ); ?>"><?php esc_html_e( 'Game Date', 'court-reservation' ); ?>*</label>
				<input id="cr-game-date-<?php echo esc_attr( $piramid['id'] ); ?>" name="cr_game[date]" class="cr-input-field cr-game-date datepicker" data-date_format="dd-mm-yy" type="text" placeholder="<?php esc_html_e( 'Select a game date', 'court-reservation' ); ?>" value="" size="10" required>
				<br />

				<label for="cr-court-select"><?php esc_html_e( 'Court', 'court-reservation' ); ?>*</label>
				<select name="cr_game[court_id]" class="cr-input-field cr-court-select" id="cr-court-select" required >
					<option value=""><?php echo esc_attr__( 'Choose court', 'court-reservation' ); ?></option>
					<?php foreach ( $courts as $court ) : ?>
						<option value="<?php echo esc_attr( $court['id'] ); ?>"><?php echo esc_html( $court['name'] ); ?></option>
					<?php endforeach; ?>
				</select>
				<br />
				
				<label for="cr-game-time"><?php esc_html_e( 'Time', 'court-reservation' ); ?>*</label>
				<span class="cr-input-field cr-game-time">
					<input type="number" name="cr_game[time][h]" class="select-start-h" id="cr-game-time" min="0" max="23" maxlength="2" size="2" value="" required />&nbsp;:&nbsp;
					<select name="cr_game[time][m]" id="select-start-m" class="select-start-m">
						<option value="00">00</option>
						<?php
						if ( $this->ishalfhour() ) :
							?>
							<option value="30">30</option><?php endif; ?>
					</select>
				</span>

			</fieldset>
			<input type="hidden" name="duration_ts" value="<?php echo esc_attr( $piramid['duration_ts'] ); ?>">
			<?php wp_nonce_field( 'schedule_challenge', 'schedule_challenge_nonce' ); ?>
			
		  </form>
	</div>

	<!-- DIALOG ENTER CHALLENGE RESULTS -->
	<div class="cr-piramid-dialog cr-dialog-enter-results" id="cr-dialog-enter-results-<?php echo esc_attr( $piramid['id'] ); ?>" title="<?php esc_html_e( 'Enter Results', 'court-reservation' ); ?>" style="display: none;">
		<form class="cr-form cr-enter-results-form" id="cr-enter-results-form" autocomplete="off">
			<div class="content cr-row" autofocus>
				<div class="cr-col cr-ta-center">
					<strong><span class="challenger-name"></span></strong>
				</div>
				<div class="cr-col cr-ta-center">vs.</div>
				<div class="cr-col cr-ta-center">
					<strong><span class="challenged-name"></span></strong>
				</div>
			</div>
			<fieldset>
				<div class="winner-radioboxs cr-row">
					<div class="cr-col cr-ta-center">
					<label><?php esc_html_e( 'Winner', 'court-reservation' ); ?>:*&ensp;</label>
						<input type="radio" name="cr_results[winner]" id="cr-results-winner-challenger" value="" />
					</div>
					<div class="cr-col cr-ta-center"></div>
					<div class="cr-col cr-ta-center">
						<input type="radio" name="cr_results[winner]" id="cr-results-winner-challenged" value="" />
					</div>
				</div>
				<?php $sets_quantity = $piramid['mode'] == 'Best Of Three' ? 3 : 1; ?>
				<ul class="sets-list">
					<?php for ( $i = 0; $i < $sets_quantity; $i++ ) : ?>
						<li class="set-item cr-row">
							<div class="cr-col cr-ta-center">
								<label for="cr-results-set-<?php echo esc_attr( $i ); ?>-#challenger_id#"><?php echo esc_html( $i + 1 ); ?>. <?php esc_html_e( 'Set', 'court-reservation' ); ?>&ensp;</label>
								<input type="number" name="cr_results[sets][<?php echo esc_attr( $i ); ?>][#challenger_id#]" class="cr-input-field" id="cr-results-set-<?php echo esc_attr( $i ); ?>-#challenger_id#" min="0" maxlength="2" size="3" value="" />
							</div>
							<div class="cr-col cr-ta-center"></div>
							<div class="cr-col cr-ta-center">
								<input type="number" name="cr_results[sets][<?php echo esc_attr( $i ); ?>][#challenged_id#]" class="cr-input-field" id="cr-results-set-<?php echo esc_attr( $i ); ?>-#challenged_id#" min="0" maxlength="2" size="3" value="" />
							</div>
						</li>
					<?php endfor; ?>
				</ul>
			</fieldset>
			<?php wp_nonce_field( 'enter_results', 'enter_results_nonce' ); ?>
		  </form>
	</div>

	<!-- DIALOG COMFIRM -->
	<div class="cr-piramid-dialog cr-dialog-comfirm" id="cr-dialog-comfirm-<?php echo esc_attr( $piramid['id'] ); ?>" title="<?php esc_html_e( 'Dialog comfirm title', 'court-reservation' ); ?>" style="display: none;">
		  <p class="content"><?php esc_html_e( 'Dialog comfirm content', 'court-reservation' ); ?></p>
	</div>
	
	<!-- Custom style from piramid design options -->
	<style type="text/css">
		.cr-player-item{
			background-color: <?php echo esc_attr( $design['btn_colors']['enabled'] ); ?>!important;
			border-color: <?php echo esc_attr( $design['btn_border']['color'] ); ?>!important;
			border-width: <?php echo esc_attr( $design['btn_border']['width'] ); ?>!important;
			border-style: solid!important;
			flex-basis: <?php echo esc_attr( $design['btn_sizes']['width'] ); ?>!important;
			max-width: <?php echo esc_attr( $design['btn_sizes']['width'] ); ?>!important;
			height: <?php echo esc_attr( $design['btn_sizes']['height'] ); ?>!important;
		}
		.cr-player-item .name{
			color: <?php echo esc_attr( $design['btn_txt']['color'] ); ?>!important;
			font-size: <?php echo esc_attr( $design['btn_txt']['size'] ); ?>!important;
			line-height: <?php echo esc_attr( $design['btn_txt']['line_height'] ); ?>!important;
		}
		.cr-player-item:disabled{
			background-color: <?php echo esc_attr( $design['btn_colors']['disabled'] ); ?>!important;
		}
		.cr-player-item:hover {
			background-color: <?php echo esc_attr( $design['btn_colors']['hover'] ); ?>!important;
		}
		.cr-player-item.current {
			background-color: <?php echo esc_attr( $design['btn_colors']['current'] ); ?>!important;
		}
		.cr-challenge-item, .cr-challenge-item a{
			background-color: <?php echo esc_attr( $design['btn_colors']['enabled'] ); ?>!important;
			color: <?php echo esc_attr( $design['btn_txt']['color'] ); ?>!important;
		}
		@media screen and (max-width: <?php echo esc_attr( $design['viewport']['max_width'] ); ?>) {
			.cr-player-item{
				flex-basis: 100%!important;
				max-width: 100%!important;
			}
			.cr-challenges-block{
				flex-basis: 100%;
				width: 100%;
			}
		}
	</style>

</section>
<?php endif; ?>
