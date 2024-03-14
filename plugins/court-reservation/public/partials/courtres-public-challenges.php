<?php

/**
 * Provide a public-facing view for the plugin for ajax
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.5.0
 *
 * @package    Courtres
 * @subpackage Courtres/public/partials
 */
?>

<?php
// function_exists("fppr") ? fppr($atts, __FILE__.' $atts') : false;
$challenges      = isset( $atts['challenges'] ) && $atts['challenges'] ? $atts['challenges'] : false;
$date_format     = get_option( 'date_format' );
$time_format     = get_option( 'time_format' );
$current_user_id = get_current_user_id();
$lifetime_ts     = $atts['piramid']['lifetime_ts'];
$locktime_ts     = $atts['piramid']['locktime_ts'];
?>

<div class="cr-challenges-block">
	<?php if ( $challenges ) : ?>
		<p><strong><?php echo esc_html( $atts['title'] ); ?></strong></p>
		<ul class="cr-challenges-list">
			<?php foreach ( $challenges as $challenge ) : ?>
				<?php
				$item_title = '';
				switch ( $challenge['status'] ) {
					case 'created':
						$action       = 'accept';
						$action_title = __( 'accept', 'courtres' );
						$status_title = __( 'Not yet accepted', 'courtres' );

						$descr_big = __( 'Game date not yet scheduled', 'courtres' );

						$descr_small = $status_title;
						if ( $current_user_id == $challenge['challenged_id'] ) {
							$descr_small .= ' (<a href="javascript:void(0);" class="cr-challenge-action ' . $action . '" data-' . $action . '_nonce="' . wp_create_nonce( $action . '_nonce' ) . '">' . $action_title . '</a>)';
						}

						$expired_ts = strtotime( $challenge['created_dt'] ) + $lifetime_ts;
						$item_title = ' title="' . __( 'Will expire', 'courtres' ) . ': ' . date_i18n( $date_format, $expired_ts ) . ', ' . date_i18n( $time_format, $expired_ts ) . '"';
						break;

					case 'accepted':
						$action       = 'delete';
						$action_title = __( 'delete', 'courtres' );
						$status_title = __( $challenge['status'], 'courtres' );

						if ( $current_user_id == $challenge['challenger_id'] || $current_user_id == $challenge['challenged_id'] ) {
							$descr_big = '<a href="javascript:void(0);" class="cr-challenge-action schedule" data-schedule_nonce="' . wp_create_nonce( 'schedule_nonce' ) . '">' . __( 'Schedule game date', 'courtres' ) . '</a>';
						} else {
							$descr_big = __( 'Game date not yet scheduled', 'courtres' );
						}

						$descr_small = $status_title;
						if ( $current_user_id == $challenge['challenger_id'] || $current_user_id == $challenge['challenged_id'] ) {
							$descr_small .= ' (<a href="javascript:void(0);" class="cr-challenge-action ' . $action . '" data-' . $action . '_nonce="' . wp_create_nonce( $action . '_nonce' ) . '">' . $action_title . '</a>)';
						}

						$expired_ts = strtotime( $challenge['accepted_dt'] ) + $lifetime_ts;
						$item_title = ' title="' . __( 'Will expire', 'courtres' ) . ': ' . date_i18n( $date_format, $expired_ts ) . ', ' . date_i18n( $time_format, $expired_ts ) . '"';
						break;

					case 'scheduled':
						$action       = 'delete';
						$action_title = __( 'delete', 'courtres' );
						$status_title = __( $challenge['status'], 'courtres' );
						$time_period  = date_i18n( $time_format, $challenge['start_ts'] ) . ' - ' . date_i18n( $time_format, $challenge['end_ts'] );

						$descr_big = date_i18n( $date_format, $challenge['start_ts'] ) . ', ' . $time_period;

						$descr_small = $status_title;
						if ( $current_user_id == $challenge['challenger_id'] || $current_user_id == $challenge['challenged_id'] ) {
							$descr_small .= ' (<a href="javascript:void(0);" class="cr-challenge-action ' . $action . '" data-' . $action . '_nonce="' . wp_create_nonce( $action . '_nonce' ) . '">' . $action_title . '</a>)';
						}
						break;

					case 'played':
						$action       = 'record_result';
						$action_title = __( 'record result', 'courtres' );
						$status_title = __( $challenge['status'], 'courtres' );
						$time_period  = date_i18n( $time_format, $challenge['start_ts'] ) . ' - ' . date_i18n( $time_format, $challenge['end_ts'] );

						$descr_big = date_i18n( $date_format, $challenge['start_ts'] ) . ', ' . $time_period;

						$descr_small = $status_title;
						if ( $current_user_id == $challenge['challenger_id'] || $current_user_id == $challenge['challenged_id'] ) {
							$descr_small .= ' (<a href="javascript:void(0);" class="cr-challenge-action ' . $action . '" data-' . $action . '_nonce="' . wp_create_nonce( $action . '_nonce' ) . '">' . $action_title . '</a>)';
						}
						break;

					case 'closed':
						$action       = '';
						$action_title = '';
						$status_title = __( $challenge['status'], 'courtres' );
						$time_period  = date_i18n( $time_format, $challenge['start_ts'] ) . ' - ' . date_i18n( $time_format, $challenge['end_ts'] );
						$date         = date_i18n( $date_format, $challenge['start_ts'] ) . ', ' . $time_period;

						$lock_expired_ts = strtotime( $challenge['closed_dt'] ) + $locktime_ts;
						$item_title      = ' title="' . __( 'Cool-down phase expiration', 'courtres' ) . ': ' . date_i18n( $date_format, $lock_expired_ts ) . ', ' . date_i18n( $time_format, $lock_expired_ts ) . '"';

						$results     = unserialize( $challenge['results'] );
						$set_results = array();
						foreach ( $results as $key => $points ) {
							$set_results[] =
								( $points[ $challenge['challenger_id'] ] !== '' ? $points[ $challenge['challenger_id'] ] : '-' )
								. ':'
								. ( $points[ $challenge['challenged_id'] ] !== '' ? $points[ $challenge['challenged_id'] ] : '-' );
						}

						$descr_big   = implode( ' ', $set_results );
						$descr_small = __( 'Winner ', 'courtres' ) . ' - ' . $challenge['winner']['wp_user']->display_name . '. ' . __( 'Played at ', 'courtres' ) . $date;
						break;

					default:
						$action       = '';
						$action_title = '';
						$status_title = __( $challenge['status'], 'courtres' );
						$time_period  = '';
						$descr_big    = '';
						$descr_small  = '';
						break;
				}
				?>

				<li class="cr-challenge-item" data-id="<?php echo esc_attr( $challenge['id'] ); ?>" data-status="<?php echo esc_attr( $challenge['status'] ); ?>" data-challenge='<?php echo json_encode( $challenge ); ?>'<?php echo esc_html( $item_title ); ?>>
					<p class="cr-challenge-row main">
						<span class="cr-challenge-cell player"><?php echo esc_html( $challenge['challenger']['wp_user']->display_name ); ?></span>
						<span class="cr-challenge-cell vs">vs.</span>
						<span class="cr-challenge-cell player"><?php echo esc_html( $challenge['challenged']['wp_user']->display_name ); ?></span>
						<span class="cr-challenge-cell descr"><?php 
							$allowed_html = [
    								'a' => [
        								'class' => true,
        								'href'  => true,
        								'data-schedule_nonce' => true,
    									],
								]; 
								echo wp_kses( $descr_big,$allowed_html ); ?></span>
					</p>
					<p class="cr-challenge-row additional">
						<span class="cr-challenge-cell player"><?php echo isset( $challenge['challenger']['sort'] ) ? esc_html( $challenge['challenger']['sort'] + 1 ) : ''; ?></span>
						<span class="cr-challenge-cell vs">&ensp;</span>
						<span class="cr-challenge-cell player"><?php echo isset( $challenge['challenged']['sort'] ) ? esc_html( $challenge['challenged']['sort'] + 1 ) : ''; ?></span>
						<span class="cr-challenge-cell descr"><?php 
							$allowed_html = [
    								'a' => [
        								'class' => true,
        								'href'  => true,
        								'data-accept-nonce' => true,
    									],
								]; 
								echo wp_kses( $descr_small,$allowed_html ); ?></span>
					</p>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
