<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.0.3
 *
 * @package    Courtres
 * @subpackage Courtres/admin/partials
 */
?>

<?php

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die();
}

// 2021-03-14, astoian - if not ultimate, stop it
if ( ! $this->isCourtUltimate() ) {
	include 'courtres-notice-upgrade.php';
	wp_die( esc_html__( 'Piramid creation is allowed in Ultimate Version only.', 'court-reservation' ) );
}

$piramid            = array();
$notices            = array(
	'error'   => array(),
	'success' => array(),
);
$is_piramid_created = false;
$is_piramid_edited  = false;
$is_piramid_deleted = false;
$db_fields          = Courtres_Entity_Piramid::get_db_fields();

$action     = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : 'create';
$piramid_id = isset( $_GET['piramidID'] ) ? (int) $_GET['piramidID'] : false;


// function_exists("ppr") ? ppr($_POST, __FILE__.' $_POST') : false;

if ( isset( $_POST['submit'] ) && $_POST['submit'] == __( 'Save', 'court-reservation' ) ) {
	// Saving or editing...

	$piramid=$_POST['piramid'];
	array_walk($piramid, function(&$value, &$key) 
	{
		$value[$key] = sanitize_text_field($value[$key]);
	});

	$piramid['duration_ts'] = intval( $piramid['duration']['hours'] ) * 3600 + intval( $piramid['duration']['min'] ) * 60;
	$piramid['lifetime_ts'] = intval( $piramid['lifetime']['hours'] ) * 3600 + intval( $piramid['lifetime']['min'] ) * 60;
	$piramid['locktime_ts'] = intval( $piramid['locktime']['hours'] ) * 3600 + intval( $piramid['locktime']['min'] ) * 60;
	$piramid['players']     = isset( $piramid['players'] ) ? $piramid['players'] : array();

	if ( ! wp_verify_nonce( $_POST['cr_piramid_form_nonce'], 'cr_piramid_form' ) ) {
		$notices['error'][] = __( 'Error checking security code', 'courtres' );
	}
	if ( ! $piramid['duration_ts'] ) {
		$notices['error'][] = $db_fields['duration_ts']['title'] . ' ' . __( 'can not be zero', 'courtres' );
	}
	if ( ! $piramid['lifetime_ts'] ) {
		$notices['error'][] = $db_fields['lifetime_ts']['title'] . ' ' . __( 'can not be zero', 'courtres' );
	}
	if ( ! $piramid['locktime_ts'] ) {
		$notices['error'][] = $db_fields['locktime_ts']['title'] . ' ' . __( 'can not be zero', 'courtres' );
	}
	if ( count( $piramid['players'] ) < Courtres_Entity_Piramid::MIN_PLAYERS ) {
		$notices['error'][] = __( 'The number of players must be more than', 'courtres' ) . ' ' . Courtres_Entity_Piramid::MIN_PLAYERS;
	}
	if ( count( $piramid['players'] ) >= Courtres_Entity_Piramid::MAX_PLAYERS ) {
		$notices['error'][] = __( 'The number of players must be less than', 'courtres' ) . ' ' . Courtres_Entity_Piramid::MIN_PLAYERS;
	}

	// continue if no errors
	if ( ! $notices['error'] ) {
		switch ( $_POST['action'] ) {
			case 'create':
				// create record in DB
				$piramid['design'] = serialize( $piramid['design'] );
				$piramid_id        = Courtres_Entity_Piramid::insert( $piramid );
				if ( $piramid_id && $piramid['players'] ) {
					$piramid_class = Courtres_Entity_Piramid::get_instance( $piramid_id );
					$piramid_class->save_players( $piramid['players'] );
				}
				$notices['success'][] = __( 'Succesfully created!', 'courtres' );
				$is_piramid_created   = true;
				break;

			case 'edit':
				$piramid_class = Courtres_Entity_Piramid::get_instance( $piramid_id );

				if ( isset( $_POST['delete'] ) ) {
					// delete records in DB
					$is_piramid_deleted = true;
				} else {
					// update records in DB
					$piramid_class->update(
						array(
							'data'         => array(
								'name'        => $piramid['name'],
								'duration_ts' => $piramid['duration_ts'],
								'lifetime_ts' => $piramid['lifetime_ts'],
								'locktime_ts' => $piramid['locktime_ts'],
								'mode'        => $piramid['mode'],
								'design'      => serialize( $piramid['design'] ),
							),
							'where'        => array( 'id' => $piramid_id ),
							'format'       => array( '%s', '%d', '%d', '%d', '%s', '%s' ),
							'where_format' => array( '%d' ),
						)
					);
					$piramid_class->update_players( $piramid['players'] );
					$notices['success'][] = __( 'Succesfully changed!', 'courtres' );
					$is_piramid_edited    = true;
				}
				break;

			default:
				// code...
				break;
		}
	}
} elseif ( ( isset( $_POST['delete'] ) && $_POST['delete'] == __( 'Delete', 'court-reservation' ) ) ) {
	// Deleting...
	$piramid    = isset( $_POST['piramid'] ) ? sanitize_text_field( $_POST['piramid'] ) : false;
	$piramid_id = isset( $_POST['piramid']['id'] ) ? intval( $_POST['piramid']['id'] ) : false;
	if ( $piramid_id ) {
		$piramid_class = Courtres_Entity_Piramid::get_instance( $piramid_id );
		if ( $piramid_class->delete_by_id( $piramid_id ) ) {
			$notices['success'][] = __( 'Succesfully deleted!', 'courtres' );
			$is_piramid_deleted   = true;
		}
	}
} else {
	// Defaults
	if ( isset( $piramid_id ) && $piramid_id > 0 ) {
		$piramid = Courtres_Entity_Piramid::get_by_id( $piramid_id );
		if ( $piramid ) {
			$piramid['players'] = Courtres_Entity_Piramids_Players::get_by_piramid_id( $piramid_id );
			$piramid['design']  = unserialize( $piramid['design'] );
			$piramid['design']  = wp_parse_args( $piramid['design'], $db_fields['design']['default_value'] );
		}
	}
	if ( ! $piramid ) {
		$piramid = array(
			'id'          => false,
			'name'        => $db_fields['name']['default_value'],
			'mode'        => $db_fields['mode']['default_value'],
			'duration_ts' => $db_fields['duration_ts']['default_value'],
			'lifetime_ts' => $db_fields['lifetime_ts']['default_value'],
			'locktime_ts' => $db_fields['locktime_ts']['default_value'],
			'players'     => array(),
			'design'      => $db_fields['design']['default_value'],
		);
	}

	$piramid['duration'] = array(
		'hours' => floor( $piramid['duration_ts'] / 3600 ),
		'min'   => (int) date_i18n( 'i', $piramid['duration_ts'] ),
	);

	$piramid['lifetime'] = array(
		'hours' => floor( $piramid['lifetime_ts'] / 3600 ),
		'min'   => (int) date_i18n( 'i', $piramid['lifetime_ts'] ),
	);
	$piramid['locktime'] = array(
		'hours' => floor( $piramid['locktime_ts'] / 3600 ),
		'min'   => (int) date_i18n( 'i', $piramid['locktime_ts'] ),
	);
}

$users = get_users(
	array(
		'role__in' => array( 'Player', 'Administrator' ),
		'orderby'  => 'display_name',
		'order'    => 'ASC',
		'fields'   => array(
			'ID',
			'user_login',
			'display_name',
		),
	)
);

// function_exists("ppr") ? ppr($piramid, __FILE__.' $piramid') : false;

?>

<div class="wrap">

	<?php foreach ( $notices as $key => $notice ) : ?>
		<?php if ( $notice ) : ?>
			<div id="message" class="notice notice-<?php echo esc_attr( $key ); ?> is-dismissible"><p>
				<?php echo esc_html( implode( '. ', $notice ) ); ?>
			</p></div>
		<?php endif; ?>
	<?php endforeach; ?>

	<a class="page-title-action" href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=1' )); ?>"><?php echo esc_html__( 'Back to list', 'court-reservation' ); ?></a>&emsp;
	<h1 class="wp-heading-inline"><?php echo ( isset( $piramid ) && $piramid['id'] > 0 ) ? esc_html( $piramid['name'] ) . esc_html__( ' edit', 'court-reservation' ) : esc_html__( 'Create Pyramid', 'court-reservation' ); ?></h1>
	<hr class="wp-header-end">

	<?php if ( $is_piramid_created ) : ?>
		<a class="page-title-action" href="<?php echo esc_html(admin_url( 'admin.php?page=courtres-piramid' )); ?>"><?php echo esc_html__( 'Create new', 'court-reservation' ); ?></a>
	<?php elseif ( $is_piramid_deleted ) : ?>
	<?php else : ?>

		<form method="post" class="cr-piramid-form">
			
			<?php wp_nonce_field( 'cr_piramid_form', 'cr_piramid_form_nonce' ); ?>
			<input type="hidden" name="piramid[id]" value="<?php echo esc_attr( $piramid['id'] ); ?>" />
			<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>" />
			
			<table class="t-form">
				<colgroup>
				   <col width="130">
				   <col width="250">
				   <col>
				  </colgroup>
				<?php if ( $piramid['id'] ) : ?>
					<tr>
						<td><label>id</label></td>
						<td><?php echo esc_html( $piramid['id'] ); ?></td>
						<td></td>
					</tr>
				<?php endif; ?>
				<tr>
					<td><label for="cr-input-piramid-name"><?php echo esc_html__( $db_fields['name']['title'], 'court-reservation' ); ?>*</label></td>
					<td><input type="text" name="piramid[name]" id="cr-input-piramid-name" maxlength="128" required autocomplete="off" autofocus value="<?php echo esc_attr( $piramid['name'] ); ?>" /></td>
					<td></td>
				</tr>
				<tr>
					<td><label for="cr-input-piramid-mode"><?php echo esc_html__( $db_fields['mode']['title'], 'court-reservation' ); ?>*</label></td>
					<td>
						<select name="piramid[mode]" autocomplete="off">
							<option value="One Set" <?php selected( $piramid['mode'], 'One Set' ); ?>>One Set</option>
							<option value="Best Of Three" <?php selected( $piramid['mode'], 'Best Of Three' ); ?>>Best Of Three</option>
						</select>
					</td>
					<td></td>
				</tr>
				<tr>
					<td><label for="cr-input-piramid-duration"><?php echo esc_html__( 'Challenges duration', 'court-reservation' ); ?> (<?php esc_html_e( 'hours', 'court-reservation' ); ?> : <?php esc_html_e( 'min', 'court-reservation' ); ?>)*</label></td>
					<td>
						<input type="number" name="piramid[duration][hours]" id="cr-input-piramid-duration" min="0" required autocomplete="off" value="<?php echo esc_html( $piramid['duration']['hours'] ); ?>" />&nbsp;:&nbsp;
						<select name="piramid[duration][min]">
							<option value="0" <?php selected( $piramid['duration']['min'], 0 ); ?>>00</option>
							<option value="30" <?php selected( $piramid['duration']['min'], 30 ); ?>>30</option>
						</select>
					</td>
					<td>
						<!-- <i> -->
						<?php // _e("This is the duration of the game. This setting has an influence on the player frontend. The player only chooses a starting point, let's say 16:00. The admin has set the game duration to 120 minutes. So the reservation is automatically set from 16:00 to 18:00.", 'court-reservation'); ?>
						<!-- </i> -->
					</td>
				</tr>
				<tr>
					<td><label for="cr-input-piramid-lifetime"><?php echo esc_html__( 'Challenges life time', 'court-reservation' ); ?> (<?php esc_html_e( 'hours', 'court-reservation' ); ?> : <?php esc_html_e( 'min', 'court-reservation' ); ?>)*</label></td>
					<td>
						<input type="number" name="piramid[lifetime][hours]" id="cr-input-piramid-lifetime" min="0" required autocomplete="off" value="<?php echo esc_html( $piramid['lifetime']['hours'] ); ?>" />&nbsp;:&nbsp;
						<select name="piramid[lifetime][min]">
							<option value="0" <?php selected( $piramid['lifetime']['min'], 0 ); ?>>00</option>
							<option value="30" <?php selected( $piramid['lifetime']['min'], 30 ); ?>>30</option>
						</select>
					</td>
					<td>
						<!-- <i> -->
							<?php // _e("The starting point should be the time when the player challenges another player(time stamp). After the expiry, the challenge should simply be deleted so that there are not too many open claims all at once. However, if it has been accepted and / or an appointment has been made, the challenge should no longer be deleted.", 'court-reservation'); ?>
						<!-- </i> -->
					</td>
				</tr>
				<tr>
					<td><label for="cr-input-piramid-locktime"><?php echo esc_html__( 'Challenges lock time', 'court-reservation' ); ?> (<?php esc_html_e( 'hours', 'court-reservation' ); ?> : <?php esc_html_e( 'min', 'court-reservation' ); ?>)*</label></td>
					<td>
						<input type="number" name="piramid[locktime][hours]" id="cr-input-piramid-locktime" min="0" required autocomplete="off" value="<?php echo esc_html( $piramid['locktime']['hours'] ); ?>" />&nbsp;:&nbsp;
						<select name="piramid[locktime][min]">
							<option value="0" <?php selected( $piramid['locktime']['min'], 0 ); ?>>00</option>
							<option value="30" <?php selected( $piramid['locktime']['min'], 30 ); ?>>30</option>
						</select>
					</td>
					<td>
						<!-- <i> -->
							<?php // _e("This is more relevant if the challenger loses and wants to play again right away. Then he should have to wait at least x days. But I would also say this also applies in the event that the challenger wins and he takes the place of the other. The loser should then only be able to claim back after x days.", 'court-reservation'); ?>
						<!-- </i> -->
					</td>
				</tr>
			</table>

			<div class="cr-players-checklist">
				<h3>
					<?php esc_html_e( 'Select players', 'court-reservation' ); ?> (<?php echo esc_html__( 'min. ', 'court-reservation' ) . esc_html(Courtres_Entity_Piramid::MIN_PLAYERS) . esc_html__( ', max. ', 'court-reservation' ) . esc_html(Courtres_Entity_Piramid::MAX_PLAYERS); ?>)
				</h3>
				<p>
					<?php if ( $users ) : ?>
						<ul>
							<?php	foreach ( $users as $key => $user ) : ?>
								<li class="cr-piramid-player-item">
									<input type="checkbox" id="player-<?php echo esc_html( $key ); ?>" class="cr-piramid-player-checkbox" value="<?php echo esc_attr( $user->ID ); ?>" autocomplete="off" <?php checked( ( array_search( $user->ID, array_column( $piramid['players'], 'player_id' ) ) !== false ), true ); ?> />&nbsp;<label for="player-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $user->display_name ); ?></label><br>
								</li>
							<?php	endforeach; ?>
						</ul>
					<?php endif; ?>
				</p>
			</div>

			<h3><?php esc_html_e( 'Selected players. You can assing the player position by drag-and-drop', 'court-reservation' ); ?></h3>
			<div class="cr-selected-players">
				<ul class="sortable">
					<?php if ( $piramid['players'] ) : ?>
						<?php	foreach ( $piramid['players'] as $key => $player ) : ?>
							<li class="cr-selected-player-item">
								<span class="position"><?php echo esc_html( $player['sort'] + 1 ); ?></span>.
								<span class="player-name"><?php echo esc_html( $player['display_name'] ); ?></span>
								<input type="hidden" name="piramid[players][<?php echo esc_attr( $player['player_id'] ); ?>][player_id]" class="player-id" value="<?php echo esc_attr( $player['player_id'] ); ?>" />
								<input type="hidden" name="piramid[players][<?php echo esc_attr( $player['player_id'] ); ?>][display_name]" class="player-name" value="<?php echo esc_attr( $player['display_name'] ); ?>" />
								<input type="hidden" name="piramid[players][<?php echo esc_attr( $player['player_id'] ); ?>][sort]" class="player-sort" value="<?php echo esc_attr( $player['sort'] ); ?>" />
							</li>
						<?php	endforeach; ?>
					<?php endif; ?>
				</ul>
			</div>

			<p>&ensp;</p>
			<h3><?php esc_html_e( 'User Interface', 'court-reservation' ); ?></h3>
			<table class="t-form">
				<colgroup>
				   <col width="130">
				   <col width="250">
				   <col />
				</colgroup>
				<tr>
					<td>
						<?php esc_html_e( 'Fields text size with unit', 'court-reservation' ); ?> (<i><?php esc_html_e( 'example', 'court-reservation' ); ?>: 12px</i>)
					</td>
					<td style="position: relative;">
						<input type="text" name="piramid[design][btn_txt][size]" placeholder="<?php echo esc_attr( $db_fields['design']['default_value']['btn_txt']['size'] ); ?>" value="<?php echo esc_attr( $piramid['design']['btn_txt']['size'] ); ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<?php esc_html_e( 'Fields text line height with unit', 'court-reservation' ); ?> (<i><?php esc_html_e( 'example', 'court-reservation' ); ?>: 16px</i>)
					</td>
					<td style="position: relative;">
						<input type="text" name="piramid[design][btn_txt][line_height]" placeholder="<?php echo esc_attr( $db_fields['design']['default_value']['btn_txt']['line_height'] ); ?>" value="<?php echo esc_attr( $piramid['design']['btn_txt']['line_height'] ); ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<?php esc_html_e( 'Fields text color', 'court-reservation' ); ?>
					</td>
					<td style="position: relative;">
						<input class="color-input" data-huebee="" name="piramid[design][btn_txt][color]" placeholder="<?php echo esc_attr( $db_fields['design']['default_value']['btn_txt']['color'] ); ?>" value="<?php echo esc_attr( $piramid['design']['btn_txt']['color'] ); ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<?php esc_html_e( 'Enabled fields color', 'court-reservation' ); ?>
					</td>
					<td style="position: relative;">
						<input class="color-input" data-huebee="" name="piramid[design][btn_colors][enabled]" placeholder="<?php echo esc_attr( $db_fields['design']['default_value']['btn_colors']['enabled'] ); ?>" value="<?php echo esc_attr( $piramid['design']['btn_colors']['enabled'] ); ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<?php esc_html_e( 'Disabled fields color', 'court-reservation' ); ?>
					</td>
					<td style="position: relative;">
						<input class="color-input" data-huebee="" name="piramid[design][btn_colors][disabled]" placeholder="<?php echo esc_attr( $db_fields['design']['default_value']['btn_colors']['disabled'] ); ?>" value="<?php echo esc_attr( $piramid['design']['btn_colors']['disabled'] ); ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<?php esc_html_e( 'Field hover and active color', 'court-reservation' ); ?>
					</td>
					<td style="position: relative;">
						<input class="color-input" data-huebee="" name="piramid[design][btn_colors][hover]" placeholder="<?php echo esc_attr( $db_fields['design']['default_value']['btn_colors']['hover'] ); ?>" value="<?php echo esc_attr( $piramid['design']['btn_colors']['hover'] ); ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<?php esc_html_e( 'Logged-in User Field color', 'court-reservation' ); ?>
					</td>
					<td style="position: relative;">
						<input class="color-input" data-huebee="" name="piramid[design][btn_colors][current]" placeholder="<?php echo esc_attr( $db_fields['design']['default_value']['btn_colors']['current'] ); ?>" value="<?php echo esc_attr( $piramid['design']['btn_colors']['current'] ); ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<?php esc_html_e( 'Field border color', 'court-reservation' ); ?>
					</td>
					<td style="position: relative;">
						<input class="color-input" data-huebee="" name="piramid[design][btn_border][color]" placeholder="<?php echo esc_attr( $db_fields['design']['default_value']['btn_border']['color'] ); ?>" value="<?php echo esc_attr( $piramid['design']['btn_border']['color'] ); ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<?php esc_html_e( 'Field border width with unit', 'court-reservation' ); ?> (<i><?php esc_html_e( 'example', 'court-reservation' ); ?>: 1px</i>)
					</td>
					<td style="position: relative;">
						<input type="text" name="piramid[design][btn_border][width]" placeholder="<?php echo esc_attr( $db_fields['design']['default_value']['btn_border']['width'] ); ?>" value="<?php echo esc_attr( $piramid['design']['btn_border']['width'] ); ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<?php esc_html_e( 'Field width with unit', 'court-reservation' ); ?> (<i><?php esc_html_e( 'example', 'court-reservation' ); ?>: 110px</i>)
					</td>
					<td style="position: relative;">
						<input type="text" name="piramid[design][btn_sizes][width]" placeholder="<?php echo esc_attr( $db_fields['design']['default_value']['btn_sizes']['width'] ); ?>" value="<?php echo esc_attr( $piramid['design']['btn_sizes']['width'] ); ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<?php esc_html_e( 'Field height with unit', 'court-reservation' ); ?> (<i><?php esc_html_e( 'example', 'court-reservation' ); ?>: 40px</i>)
					</td>
					<td style="position: relative;">
						<input type="text" name="piramid[design][btn_sizes][height]" placeholder="<?php echo esc_attr( $db_fields['design']['default_value']['btn_sizes']['height'] ); ?>" value="<?php echo esc_attr( $piramid['design']['btn_sizes']['height'] ); ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<?php esc_html_e( 'Add pixel input for "Max. width viewport size for mobile design', 'court-reservation' ); ?> (<i><?php esc_html_e( 'example', 'court-reservation' ); ?>: 576px</i>)
					</td>
					<td style="position: relative;">
						<input type="text" name="piramid[design][viewport][max_width]" placeholder="<?php echo esc_attr( $db_fields['design']['default_value']['viewport']['max_width'] ); ?>" value="<?php echo esc_attr( $piramid['design']['viewport']['max_width'] ); ?>" />
					</td>
				</tr>
			</table>

			<p><input class="button" type="submit" name="submit" value=<?php esc_html_e( 'Save', 'court-reservation' ); ?> /></p>
			
			<p>&ensp;</p>
			<?php if ( isset( $piramid ) && $piramid['id'] > 0 ) : ?>
				<h3><?php echo esc_html__( 'Delete Piramid', 'court-reservation' ); ?></h3>
				<p><input class="button" type="submit" name="delete" value=<?php echo esc_html__( 'Delete', 'court-reservation' ); ?> /></p>
			<?php endif; ?>

		</form>

	<?php endif; ?>
</div>
