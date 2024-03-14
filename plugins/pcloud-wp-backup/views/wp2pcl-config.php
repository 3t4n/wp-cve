<?php
/**
 * View file
 *
 * @file wp2pcl-config.php
 * @package pcloud_wp_backup
 */

use Pcloud\Classes\wp2pcloudfuncs;

$auth      = wp2pcloudfuncs::get_storred_val( PCLOUD_AUTH_KEY );
$auth_mail = wp2pcloudfuncs::get_storred_val( PCLOUD_AUTH_MAIL );

$php_extensions            = get_loaded_extensions();
$has_archive_ext_installed = array_search( 'zip', $php_extensions, true );

$lastbackupdt_tm  = intval( wp2pcloudfuncs::get_storred_val( PCLOUD_LAST_BACKUPDT ) );
$last_backup_data = ( $lastbackupdt_tm > 9999 ) ? gmdate( 'd.m.Y H:i:s', $lastbackupdt_tm ) : '';

if ( PCLOUD_DEBUG ) {
	$freg = array(
		't'        => 'Test',
		'2_minute' => '2 Minute',
		'1_hour'   => '1 Hour',
		'4_hours'  => '4 Hours',
		'daily'    => '1 day',
		'weekly'   => '1 week',
		'monthly'  => '1 month',
	);
} else {
	$freg = array(
		'4_hours' => '4 Hours',
		'daily'   => '1 Day',
		'weekly'  => '1 Week',
		'monthly' => '1 Month',
	);
}

$sched = wp2pcloudfuncs::get_storred_val( PCLOUD_SCHDATA_KEY );

$wp2pcl_withmysql_chk = 'checked="checked"';
$wp2pcl_withmysql     = wp2pcloudfuncs::get_storred_val( PCLOUD_SCHDATA_INCLUDE_MYSQL );
if ( empty( $wp2pcl_withmysql ) || intval( $wp2pcl_withmysql ) < 1 ) {
	$wp2pcl_withmysql_chk = '';
}

$next_sch = wp_next_scheduled( 'wp2pcl_run_pcloud_backup_hook' );

$pl_dir_arr = explode( '/', plugin_dir_path( dirname( __FILE__ ) ) );

$img_url = '';

if ( count( $pl_dir_arr ) > 3 ) {
	$pl_dir  = '/' . $pl_dir_arr[ count( $pl_dir_arr ) - 4 ] . '/' . $pl_dir_arr[ count( $pl_dir_arr ) - 3 ] . '/' . $pl_dir_arr[ count( $pl_dir_arr ) - 2 ];
	$img_url = rtrim( $pl_dir . '/assets/img/', '/' );
}


$wp2pcl_api_location = wp2pcloudfuncs::get_storred_val( PCLOUD_API_LOCATIONID );
if ( empty( $wp2pcl_api_location ) || intval( $wp2pcl_api_location ) < 1 ) {
	wp2pcloudfuncs::set_storred_val( PCLOUD_API_LOCATIONID, 1 );
	$wp2pcl_api_server = 1;
}

$wp2pcl_api_server = wp2pcloudfuncs::get_api_ep_hostname();

if ( ! isset( $plugin_path ) ) {
	$plugin_path = plugins_url( '/', __FILE__ );
}

$next_backup = gmdate( 'r', wp_next_scheduled( 'wp2pcl_run_pcloud_backup_hook' ) );
$lang        = get_bloginfo( 'language' );
$nonce       = wp_create_nonce();
$msg         = '';

if ( isset( $_GET['msg'] ) && isset( $_GET['wp2pcl_nonce'] ) && wp_verify_nonce( sanitize_key( $_GET['wp2pcl_nonce'] ) ) ) {
	$msg = sanitize_text_field( wp_unslash( $_GET['msg'] ) );
}

$auth_url = '#';

if ( empty( $auth ) ) {

	$auth_url  = 'https://my.pcloud.com/oauth2/authorize?client_id=' . esc_html( PCLOUD_OAUTH_CLIENT_ID );
	$auth_url .= '&amp;response_type=token';
	$auth_url .= '&amp;force_reapprove=true';
	$auth_url .= '&amp;returnqueryparams=1';
	$auth_url .= '&amp;state=' . rawurlencode( admin_url( 'options-general.php?page=wp2pcloud_settings' ) );
	$auth_url .= '&amp;redirect_uri=' . rawurlencode( 'https://wpoauth2.pcloud.com/' );
}

?>
<div id="wp2pcloud" data-lang="<?php echo esc_attr( $lang ); ?>" data-pluginurl="<?php echo esc_url( $plugin_path ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>">

	<div id="wp2pcloud-error" class="error notice" style="display: none"><p></p></div>

	<?php if ( ! $has_archive_ext_installed ) : ?>
		<div id="wp2pcloud-error" class="error notice">
			<p class="pcl_transl" data-i10nk="no_zip_zlib_ext_found">
				No "zip" PHP extension has been found, backup will not be possible.
				Please, contact the support of your hosting company and request the extension to be enabled for your website.
			</p>
		</div>
	<?php endif; ?>

	<?php if ( 'restore_ok' === $msg ) : ?>
		<div id="wp2pcloud-message" class="updated below-h2">
			<p class="pcl_transl" data-i10nk="restore_ok">Your files and database has been restored successfully!</p>
		</div>
	<?php endif; ?>

	<div id="wp2pcloud-settings">

		<?php if ( ! $auth ) : ?>

			<div id="wp2pcloud-login-form">
				<?php if ( ! empty( $img_url ) ) : ?>
					<div><img src="<?php echo esc_url( $img_url . '/logo-pcloud.png' ); ?>" alt="Pcloud"/></div>
				<?php endif; ?>
				<div class="login-header" style="padding-top: 30px">
					<a href="<?php print esc_url( $auth_url ); ?>">
						<span data-i10nk="login_withpcl_acc">Authenticate with pCloud</span>
					</a>
				</div>
			</div>

		<?php else : ?>

			<div class="wp2pcloud-register-wrap">

				<h2 class="pcl_transl" data-i10nk="backup_area_title">pCloud Backup</h2>

				<!-- show link info -->
				<div class="updated notice is-dismissible pcl_top_info">
					<span class="pcl_transl" data-i10nk="your_acc_is_linked">Your account is linked with pCloud</span>,
					<span id="pcloud_info" style="padding-left: 10px"></span>
					<button type="button" class="button wpb2pcloud_unlink_account pcl_transl" data-i10nk="unlink_acc">
						unlink your account
					</button>
				</div>
				<div class="pcl_dbg_area">
					<button type="button" name="dbg" value="" id="pcl_dbg_tgl">debug</button>
				</div>

				<div class="log_show notice is-dismissible" style="border-left:0;"></div>

				<table class="widefat wp2pcloud-register-backups-table">
					<colgroup>
						<col style="width: auto"/>
						<col style="width: 100px"/>
						<col style="width: 100px"/>
						<col style="width: 100px"/>
					</colgroup>
					<thead>
					<tr>
						<th class="pcl_transl" data-i10nk="date_time">Date / Time</th>
						<th class="pcl_transl" data-i10nk="size">Size</th>
						<th class="pcl_transl" data-i10nk="restore">Restore</th>
						<th class="pcl_transl" data-i10nk="download">Download</th>
					</tr>
					</thead>
					<tbody id="pcloudListBackups">
					<tr>
						<td colspan="4" class="pcl_transl" data-i10nk="no_backups_yet">This is where your backups will
							appear once you have some.
						</td>
					</tr>
					</tbody>
				</table>

			</div>

			<div class="schedule">
				<h4 class="pcl_transl" data-i10nk="next_sched_bk_title">Next scheduled backup</h4>
				<?php
				if ( wp_next_scheduled( 'wp2pcl_run_pcloud_backup_hook' ) ) {
					echo '<span class="pcl_transl" data-i10nk="next_bk_perf_on">Next backup will performed on </span>' . esc_html( $next_backup );
				} else {
					echo '<span class="pcl_transl" data-i10nk="no_backups_after_check">There are no scheduled backups</span>';
				}
				?>
			</div>

			<div class="wp2pcloud-register-wrap">
				<?php if ( $has_archive_ext_installed ) : ?>
				<button type="button" id="run_wp_backup_now" class="button pcl_transl" data-i10nk="cta_backup_now">Make
					backup now
				</button>
				<?php endif; ?>
			</div>

			<div class="wp2pcloud-register-wrap" style="padding-bottom: 30px">
				<h4 class="pcl_transl" data-i10nk="incl_db_backup_ttl">Database backup:</h4>

				<form action="" id="wp2_incl_db_form" autocomplete="off">

					<div id="setting-error-mysql-settings_updated" class="updated settings-error below-h2">
						<p class="pcl_transl" data-i10nk="your_sett_saved">Your settings are saved</p>
					</div>

					<div id="" class="below-h2">
						<label for="wp2pcl_withmysql" data-i10nk="incl_db_backup_lbl">
							Include Database ( MySQL ) in the backup:
						</label>
						<input type="checkbox" name="wp2pcl_withmysql" id="wp2pcl_withmysql" value="1"
									<?php echo esc_attr( $wp2pcl_withmysql_chk ); ?> />
					</div>

					<input type="hidden" name="wp2pcl_nonce" value="<?php echo esc_attr( $nonce ); ?>"/>

				</form>

			</div>

			<div class="wp2pcloud-register-wrap">
				<h3 class="pcl_transl" data-i10nk="sch_backup_area_ttl">Schedule backup</h3>

				<form action="" id="wp2pcloud_form" autocomplete="off">

					<div id="setting-error-settings_updated" class="updated settings-error below-h2">
						<p class="pcl_transl" data-i10nk="your_sett_saved">Your settings are saved</p>
					</div>

					<div id="wp2pcloud_form_min_int">
						<label for="freq" class="pcl_transl" data-i10nk="min_auto_bk_interval">Minimum auto-backup
							interval:</label>
						<select name="freq" id="freq">
							<option value="none" selected="selected" class="pcl_transl" data-i10nk="no_sched_set"> ---
								no scheduled backups ---
							</option>
							<?php foreach ( $freg as $k => $period ) : ?>
								<option
									<?php if ( $sched === $k ) : ?>
										selected='selected'
									<?php endif; ?>
										value="<?php echo esc_attr( $k ); ?>" class="pcl_transl"
										data-i10nk="sched_i_<?php echo esc_attr( $k ); ?>"><?php echo esc_attr( $period ); ?></option>
							<?php endforeach; ?>
						</select>
						<button type="submit" class="button button-primary pcl_transl" data-i10nk="save_settings">Save
							settings
						</button>
					</div>

					<div style="display: flex; align-items: center; padding-top: 10px;">
						<label for="freq" class="pcl_transl" data-i10nk="last_auto_backup">Last auto backup:&nbsp;&nbsp;</label>
						<?php if ( empty( $last_backup_data ) ) : ?>
							<em class="pcl_transl" data-i10nk="no_bk_so_far">no auto backups so far</em>
						<?php else : ?>
							<?php echo esc_html( $last_backup_data ); ?>
						<?php endif; ?>
					</div>

					<input type="hidden" name="wp2pcl_nonce" value="<?php echo esc_attr( $nonce ); ?>"/>

				</form>
			</div>

		<?php endif; ?>
	</div>
</div>
