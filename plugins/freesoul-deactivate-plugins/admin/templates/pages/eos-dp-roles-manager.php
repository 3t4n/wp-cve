<?php
/**
 * Template Role Manager.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Roles manager settings callback.
function eos_dp_pro_roles_manager_callback() {
	if ( ! current_user_can( 'activate_plugins' ) && function_exists( 'eos_dp_active_plugins' ) ) {
		?>
		<h2><?php esc_html_e( 'Sorry, you have not the right for this page', 'freesoul-deactivate-plugins' ); ?></h2>
		<?php
		return;
	}
	wp_enqueue_script( 'fdp-pro-settings', EOS_DP_SETTINGS_JS_URL, array( 'eos-dp-backend' ), true );
	wp_enqueue_script( 'fdp-pro-roles-manager', EOS_DP_PLUGIN_URL . '/admin/assets/js/fdp-roles-manager.js', array( 'eos-dp-backend' ), true );
	wp_localize_script(
		'fdp-pro-settings',
		'fdp_setts_js',
		array(
			'ajaxurl'  => admin_url( 'admin-ajax.php' ),
			'action'   => 'eos_dp_pro_save_settings',
			'opts_key' => 'eos_dp_roles_manager',
		)
	);
	global $wp_roles;
	$current_user   = wp_get_current_user();
	$administrators = get_users( array( 'role' => 'administrator' ) );
	$admin_email    = eos_dp_get_option( 'admin_email' );
	$opts           = eos_dp_get_option( 'eos_dp_pro_main' );
	$opts           = isset( $opts['eos_dp_roles_manager'] ) ? $opts['eos_dp_roles_manager'] : false;
	$value          = '';
	$roles          = $other_admins = false;
	if ( $opts && isset( $opts['fdp-roles-manager'] ) ) {
		$opts = $opts['fdp-roles-manager'];
		if ( '' !== $opts ) {
			$value        = $opts;
			$opts         = json_decode( str_replace( '\\', '', sanitize_text_field( $opts ) ), true );
			$roles        = $opts['roles'];
			$other_admins = $opts['other_admins'];
		}
	}
	wp_nonce_field( 'fdp_setts_nonce', 'fdp_setts_nonce' );
	eos_dp_navigation();
	?>
	<style id="fdp-roles-manager-css">
	@media screen and (min-width:600px){
		#fdp-roles th:first-child{
			max-width: 200px;
			width: 200px;
		}
		#fdp-roles td:not(td:first-child),
		#fdp-administrators td:not(td:first-child){
			text-align:center
		}
		#fdp-roles .eos-dp-plugin-visibility,
		#fdp-administrators .eos-dp-plugin-visibility{
			margin-top:0
		}
	}
	</style>
	<section id="eos-dp-roles-manager">
		<div class="eos-dp-margin-top-32">
			<h2><?php esc_html_e( 'Decide who can see Freesoul Deactivate Plugins.', 'eos-dp-pro' ); ?></h2>
			<div id="eos-dp-wrp">
				<div class="eos-dp-margin-top-32">
				<table id="fdp-roles" class="wp-list-table widefat fixed striped table-view-list" data-opt_name="roles">
				<thead>
					<tr>
						<th><span class="dashicons dashicons-hammer"></span><?php esc_html_e( 'Role', 'eos-dp-pro' ); ?></th>
						<th><span class="dashicons dashicons-admin-generic"></span><?php esc_html_e( 'FDP Settings', 'eos-dp-pro' ); ?></th>
						<th><span class="dashicons dashicons-admin-post"></span><?php esc_html_e( 'Single Post Settings', 'eos-dp-pro' ); ?></th>
						<th>
							<span class="eos-dp-plugin-visibility dashicons dashicons-admin-plugins">
								<span class="dashicons dashicons-visibility"></span>
								<span class="dashicons dashicons-hidden"></span>
							</span>
							<?php esc_html_e( 'Visibility in the page of plugins', 'eos-dp-pro' ); ?>
						</th>
					</tr>
				</thead>
				<tbody>
			  <?php
				foreach ( $wp_roles->roles as $role_slug => $arr ) {
					if ( in_array( $role_slug, array( 'administrator', 'subscriber', 'customer' ) ) ) {
						continue;
					}
						$chks_values = isset( $roles[ $role_slug ] ) ? $roles[ $role_slug ] : array( false, false, false );
						$extra_class = ! in_array( 'activate_plugins', array_keys( $arr['capabilities'] ) ) ? ' eos-hidden' : '';
					?>
				<tr id="fdp-role-<?php echo esc_attr( $role_slug ); ?>-row" class="fdp-role-row" data-opt_name="<?php echo esc_attr( $role_slug ); ?>">
				  <td id="fdp-role-<?php echo esc_attr( $role_slug ); ?>" class="fdp-role"><?php echo esc_html( $arr['name'] ); ?></td>
				  <td class="eos-no-events"><input type="checkbox" id="fdp-role-<?php echo esc_attr( $role_slug ); ?>-gs" class="fdp-role-gs<?php echo esc_attr( $extra_class ); ?>"<?php echo $chks_values[0] || 'fdp_plugins_manager' === $role_slug ? ' checked' : ''; ?> /></td>
				  <td><input type="checkbox" id="fdp-role-<?php echo esc_attr( $role_slug ); ?>-sp" class="fdp-role-sp<?php echo esc_attr( $extra_class ); ?>"<?php echo 'fdp_plugins_manager' !== $role_slug && $chks_values[1] ? ' checked' : ''; ?> /></td>
				  <td><input type="checkbox" id="fdp-role-<?php echo esc_attr( $role_slug ); ?>-pp" class="fdp-role-pp<?php echo esc_attr( $extra_class ); ?>"<?php echo $chks_values[2] ? ' checked' : ''; ?> /></td>
				</tr>
			  <?php } ?>
			  </tbody>
					</table>
				</div>
				<div class="eos-dp-margin-top-32">
					<table id="fdp-administrators" class="wp-list-table widefat fixed striped table-view-list" data-opt_name="other_admins">
			  <thead>
							<tr>
				  <th><span class="dashicons dashicons-admin-users"></span><?php esc_html_e( 'Administrators', 'eos-dp-pro' ); ?></th>
				  <th><span class="dashicons dashicons-admin-generic"></span><?php esc_html_e( 'FDP Settings', 'eos-dp-pro' ); ?></th>
				  <th><span class="dashicons dashicons-admin-post"></span><?php esc_html_e( 'Single Post Settings', 'eos-dp-pro' ); ?></th>
								<th>
									<span class="eos-dp-plugin-visibility dashicons dashicons-admin-plugins">
										<span class="dashicons dashicons-visibility"></span>
										<span class="dashicons dashicons-hidden"></span>
									</span>
									<?php esc_html_e( 'Visibility in the page of plugins', 'eos-dp-pro' ); ?></th>
				</tr>
			  </thead>
			  <tbody>
			  <?php
				foreach ( $administrators as $userObj ) {
					$user = $userObj->data;
					if ( in_array( $role_slug, array( 'administrator', 'subscriber', 'customer' ) ) ) {
						continue;
					}
					$main_admin  = $user->user_email === $admin_email;
					$switch_url  = $admin_email !== $userObj->user_email && $current_user->user_login !== $userObj->user_login && class_exists( 'user_switching' ) ? ' <a href="' . esc_url( add_query_arg( array( 'redirect_to' => urlencode( user_switching::current_url() ) ), user_switching::switch_to_url( $userObj ) ) ) . '">' . sprintf( esc_html__( 'Switch to %s', 'eos-dp-pro' ), $user->user_login ) . '</a>' : '';
					$chks_values = ! $main_admin && isset( $other_admins[ strtolower( $user->user_login ) ] ) ? $other_admins[ sanitize_key( strtolower( $user->user_login ) ) ] : array( true, true, true );
					$you         = $current_user->user_email === $user->user_email ? ' ' . esc_html__( '(You)', 'eos-dp-pro ' ) : '';
					if ( '' !== $you ) {
						$chks_values[0] = true;
					}
					?>
				<tr id="fdp-administrator-<?php echo esc_attr( $user->user_login ); ?>-row" class="fdp-administrator-row<?php echo $main_admin ? ' eos-no-events' : ''; ?>" data-opt_name="<?php echo esc_attr( strtolower( $user->user_login ) ); ?>">
				  <td id="fdp-role-<?php echo esc_attr( $role_slug ); ?>" class="fdp-role"><span><?php echo get_avatar( $user->user_email, 32 ); ?></span><span>
											  <?php
												echo esc_html( $user->user_login );
												echo $main_admin ? ' ' . esc_html__( '(Main Administrator)', 'eos-dp-pro' ) : '';
												echo esc_html( $you );
												echo wp_kses_post( $switch_url );
												?>
					</span></td>
				  <td><input type="checkbox" id="fdp-administrator-<?php echo esc_attr( $user->user_login ); ?>-gs" class="fdp-administrator-gs<?php echo '' !== $you ? ' eos-no-events eos-hidden' : ''; ?>"<?php echo $chks_values[0] ? ' checked' : ''; ?> /></td>
				  <td><input type="checkbox" id="fdp-administrator-<?php echo esc_attr( $user->user_login ); ?>-sp" class="fdp-administrator-sp"<?php echo $chks_values[1] ? ' checked' : ''; ?> /></td>
				  <td><input type="checkbox" id="fdp-administrator-<?php echo esc_attr( $user->user_login ); ?>-pp" class="fdp-administrator-pp"<?php echo $chks_values[2] ? ' checked' : ''; ?> /></td>
				</tr>
			  <?php } ?>
			  </tbody>
					</table>
					<?php if ( ! class_exists( 'user_switching' ) ) { ?>
					<p><?php printf( esc_html__( 'If you want to check what other users see in the backend, try the plugin %s' ), '<a href="' . esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=user-switching' ) ) . '" target="_blank">Switching User</a>' ); ?></p>
					<?php } ?>
				</div>
			</div>
			<input type="hidden" id="fdp-roles-manager" class="fdp-opt" value="<?php echo esc_attr( $value ); ?>" />
		</div>
		<?php eos_dp_save_button(); ?>
	</section>
	<?php
}
