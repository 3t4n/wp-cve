<?php
/**
 * Plugin Name: User Session Control
 * Description: View and manage all active user sessions in a custom admin screen.
 * Version: 0.3.1
 * Author: Frankie Jarrett
 * Author URI: https://frankiejarrett.com
 * Text Domain: user-session-control
 * License: GPL-2.0
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Copyright © 2016 Frankie Jarrett. All Rights Reserved.
 */

/**
 * Define plugin constants
 */
define( 'USER_SESSION_CONTROL_VERSION', '0.3.1' );
define( 'USER_SESSION_CONTROL_PLUGIN', plugin_basename( __FILE__ ) );
define( 'USER_SESSION_CONTROL_DIR', plugin_dir_path( __FILE__ ) );
define( 'USER_SESSION_CONTROL_URL', plugin_dir_url( __FILE__ ) );
define( 'USER_SESSION_CONTROL_LANG_PATH', dirname( USER_SESSION_CONTROL_PLUGIN ) . '/languages' );
define( 'USER_SESSION_CONTROL_ADMIN_PARENT_SLUG', 'users.php' );
define( 'USER_SESSION_CONTROL_ADMIN_PAGE_SLUG', 'user-session-control' );

/**
 * Load languages
 *
 * @action plugins_loaded
 *
 * @return void
 */
function usc_i18n() {
	load_plugin_textdomain( 'user-session-control', false, USER_SESSION_CONTROL_LANG_PATH );
}
add_action( 'plugins_loaded', 'usc_i18n' );

/**
 * Register custom submenu
 *
 * @action admin_menu
 *
 * @return array
 */
function usc_register_user_submenu() {
	add_submenu_page( USER_SESSION_CONTROL_ADMIN_PARENT_SLUG, __( 'User Session Control', 'user-session-control' ), __( 'Sessions', 'user-session-control' ), 'manage_options', USER_SESSION_CONTROL_ADMIN_PAGE_SLUG, 'usc_user_submenu_callback' );
}

if ( is_network_admin() ) {
	add_action( 'network_admin_menu', 'usc_register_user_submenu' );
} else {
	add_action( 'admin_menu', 'usc_register_user_submenu' );
}

/**
 * Callback for the custom submenu screen content
 *
 * @return void
 */
function usc_user_submenu_callback() {
	if (
		! empty( $_GET['_wpnonce'] )
		&&
		! empty( $_GET['action'] )
		&&
		'destroy_session' === $_GET['action']
		&&
		! empty( $_GET['user_id'] )
		&&
		! empty( $_GET['token_hash'] )
	) {
		$user_id = absint( $_GET['user_id'] );

		if ( false === wp_verify_nonce( $_GET['_wpnonce'], sprintf( 'destroy_session_nonce-%d', $user_id ) ) ) {
			wp_die( __( 'Cheatin&#8217; uh?', 'user-session-control' ) );
		}

		usc_destroy_user_session( $user_id, $_GET['token_hash'] );
	}

	$results = usc_get_all_sessions();
	$sorted  = array();
	$spp     = ! empty( $_GET['sessions_per_page'] ) ? absint( $_GET['sessions_per_page'] ) : 20;
	$paged   = ! empty( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
	$offset  = absint( ( $paged - 1 ) * $spp );
	$orderby = ! empty( $_GET['orderby'] ) ? $_GET['orderby'] : 'created';
	$order   = ! empty( $_GET['order'] ) ? $_GET['order'] : 'desc';

	foreach ( $results as $result ) {
		if ( 'ip' === $orderby ) {
			$sorted[] = str_replace( '.', '', $result[ $orderby ] );
		} else {
			$sorted[] = $result[ $orderby ];
		}
	}

	// Loose comparison needed
	if ( 'asc' == $order ) {
		array_multisort( $sorted, SORT_ASC, $results );
	} else {
		array_multisort( $sorted, SORT_DESC, $results );
	}

	$total_sessions = count( $results );
	$pages          = absint( ceil( $total_sessions / $spp ) );

	$results = array_slice( $results, $offset, $spp );

	switch ( $order ) {
		case 'asc':
			$order_flip = 'desc';
			break;
		case 'desc':
			$order_flip = 'asc';
			break;
		default:
			$order_flip = 'desc';
	}

	$columns = array(
		'username'   => __( 'Username', 'user-session-control' ),
		'name'       => __( 'Name', 'user-session-control' ),
		'email'      => __( 'E-mail', 'user-session-control' ),
		'role'       => __( 'Role', 'user-session-control' ),
		'created'    => __( 'Created', 'user-session-control' ),
		'expiration' => __( 'Expires', 'user-session-control' ),
		'ip'         => __( 'IP Address', 'user-session-control' ),
	);

	if ( is_network_admin() ) {
		unset( $columns['role'] );
	}

	$users = usc_get_users_with_sessions();

	global $wp_roles;

	ob_start();

	$first_link = $base_link  = add_query_arg(
		array(
			'page' => USER_SESSION_CONTROL_ADMIN_PAGE_SLUG,
		),
		admin_url( USER_SESSION_CONTROL_ADMIN_PARENT_SLUG )
	);
	$last_link  = add_query_arg( array( 'paged' => $pages ), $first_link );
	$prev_link  = ( $paged > 2 ) ? add_query_arg( array( 'paged' => absint( $paged - 1 ), 'sessions_per_page' => $spp, ), $first_link ) : $first_link;
	$next_link  = ( $pages > $paged ) ? add_query_arg( array( 'paged' => absint( $paged + 1 ), 'sessions_per_page' => $spp, ), $first_link ) : $last_link;
	?>
	<div class="tablenav-pages">
		<span class="displaying-num"><?php printf( __( '%s items', 'user-session-control' ), number_format( $total_sessions ) ) ?></span>

		<?php if ( $pages > 1 ) : ?>

			<span class="pagination-links">
				<a class="first-page<?php echo ( 1 === $paged ) ? ' disabled' : null ?>" title="<?php esc_attr_e( 'Go to the first page' ) ?>" href="<?php echo esc_url( $first_link ) ?>">«</a>
				<a class="prev-page<?php echo ( 1 === $paged ) ? ' disabled' : null ?>" title="<?php esc_attr_e( 'Go to the previous page' ) ?>" href="<?php echo esc_url( $prev_link ) ?>">‹</a>
				<span class="paging-input">
					<?php echo absint( $paged ) ?> <?php _e( 'of' ) ?> <span class="total-pages"><?php echo absint( $pages ) ?></span>
				</span>
				<a class="next-page<?php echo ( $pages === $paged ) ? ' disabled' : null ?>" title="<?php esc_attr_e( 'Go to the next page' ) ?>" href="<?php echo esc_url( $next_link ) ?>">›</a>
				<a class="last-page<?php echo ( $pages === $paged ) ? ' disabled' : null ?>" title="<?php esc_attr_e( 'Go to the last page' ) ?>" href="<?php echo esc_url( $last_link ) ?>">»</a>
			</span>

		<?php endif; ?>

	</div>
	<?php
	$pagination = ob_get_clean();
	?>
	<div class="wrap">

		<h2><?php _e( 'User Session Control', 'user-session-control' ) ?></h2>

		<p><?php _e( 'Total Sessions:', 'user-session-control' ) ?> <strong><?php echo number_format( $total_sessions ) ?></strong></p>

		<p><?php _e( 'Total Unique Users:', 'user-session-control' ) ?> <strong><?php echo number_format( absint( $users->total_users ) ) ?></strong></p>

		<form method="get">

			<div class="tablenav top">

				<?php echo $pagination // xss ok ?>

				<br class="clear">

			</div>

			<table class="wp-list-table widefat fixed users">
				<thead>
					<tr>
						<?php foreach ( $columns as $slug => $name ) : ?>
							<th scope="col" class="manage-column column-<?php echo esc_attr( $slug ) ?> <?php echo ( $slug === $orderby ) ? 'sorted' : 'sortable' ?> <?php echo ( $slug === $orderby && $order ) ? esc_attr( strtolower( $order ) ) : 'desc' ?>">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => $slug, 'order' => ( $slug === $orderby ) ? esc_attr( $order_flip ) : 'asc' ) ) ) ?>">
									<span><?php echo esc_html( $name ) ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<?php foreach ( $columns as $slug => $name ) : ?>
							<th scope="col" class="manage-column column-<?php echo esc_attr( $slug ) ?> <?php echo ( $slug === $orderby ) ? 'sorted' : 'sortable' ?> <?php echo ( $slug === $orderby && $order ) ? esc_attr( strtolower( $order ) ) : 'desc' ?>">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => $slug, 'order' => ( $slug === $orderby ) ? esc_attr( $order_flip ) : 'asc' ) ) ) ?>">
									<span><?php echo esc_html( $name ) ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
						<?php endforeach; ?>
					</tr>
				</tfoot>
				<tbody>
					<?php $i = 0 ?>
					<?php foreach ( $results as $result ) : $i++ ?>
						<?php
						$role_label  = ! empty( $wp_roles->roles[ $result['role'] ]['name'] ) ? translate_user_role( $wp_roles->roles[ $result['role'] ]['name'] ) : $result['role'];
						$date_format = get_option( 'date_format', 'F j, Y' ) . ' @ ' . get_option( 'time_format', 'g:i A' );
						$user_id     = absint( $result['user_id'] );
						$edit_link   = add_query_arg(
							array(
								'wp_http_referer' => urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ),
							),
							self_admin_url( sprintf( 'user-edit.php?user_id=%d', $user_id ) )
						);
						$destroy_link = add_query_arg(
							array(
								'action'     => 'destroy_session',
								'user_id'    => $user_id,
								'token_hash' => $result['token_hash'],
								'_wpnonce'   => wp_create_nonce( sprintf( 'destroy_session_nonce-%d', $user_id ) ),
							)
						);
						$created    = is_network_admin() ? $result['created'] : strtotime( get_date_from_gmt( date( 'Y-m-d H:i:s', $result['created'] ) ) );
						$expiration = is_network_admin() ? $result['expiration'] : strtotime( get_date_from_gmt( date( 'Y-m-d H:i:s', $result['expiration'] ) ) );
						$ip_column = apply_filters( 'usc_user_ip_html', esc_html( $result['ip'] ), $result );
						?>
						<tr <?php echo ( 0 !== $i % 2 ) ? 'class="alternate"' : '' ?>>
							<td class="username column-username">
								<?php echo get_avatar( $user_id, 32 ) ?>
								<strong>
									<a href="<?php echo esc_url( $edit_link ) ?>">
										<?php echo esc_html( $result['username'] ) ?>
									</a>
									<?php if ( is_multisite() && is_super_admin( $user_id ) ) : ?>
										- <?php _e( 'Super Admin' ) ?>
									<?php endif; ?>
								</strong>
								<br>
								<div class="row-actions">
									<?php if ( wp_get_session_token() === $result['token_hash'] ) : ?>
										<span class="edit"><a href="<?php echo esc_url( $edit_link ) ?>"><?php _e( 'Edit', 'user-session-control' ) ?></a></span>
									<?php else : ?>
										<span class="edit"><a href="<?php echo esc_url( $edit_link ) ?>"><?php _e( 'Edit', 'user-session-control' ) ?></a> | </span>
										<span class="trash"><a href="<?php echo esc_url( $destroy_link ) ?>" class="submitdelete"><?php _e( 'Destroy Session', 'user-session-control' ) ?></a></span>
									<?php endif; ?>
								</div>
							</td>
							<td><?php echo esc_html( $result['name'] ) ?></td>
							<td>
								<a href="mailto:<?php echo esc_attr( $result['email'] ) ?>" title="<?php esc_attr_e( 'E-mail:', 'user-session-control' ) ?> <?php echo esc_attr( $result['email'] ) ?>"><?php echo esc_html( $result['email'] ) ?></a>
							</td>

							<?php if ( ! is_network_admin() ) : ?>
								<td><?php echo esc_html( $role_label ) ?></td>
							<?php endif; ?>

							<td>
								<strong><?php printf( __( '%s ago' ), human_time_diff( $result['created'] ) ) ?></strong>
								<br>
								<small><?php echo esc_html( date_i18n( $date_format, $created ) ) ?></small>
							</td>
							<td>
								<strong><?php printf( __( 'in %s', 'user-session-control' ), human_time_diff( $result['expiration'] ) ) ?></strong>
								<br>
								<small><?php echo esc_html( date_i18n( $date_format, $expiration ) ) ?></small>
							</td>
							<td><?php echo $ip_column ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<div class="tablenav bottom">

				<?php echo $pagination // xss ok ?>

				<br class="clear">

			</div>

		</form>

	</div>
	<?php
}

/**
 * Get all raw session meta from all users
 *
 * @return array
 */
function usc_get_all_sessions_raw() {
	global $wpdb;

	$results  = array();
	$sessions = $wpdb->get_results( "SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'session_tokens' LIMIT 0, 9999" );
	$sessions = wp_list_pluck( $sessions, 'meta_value' );
	$sessions = array_map( 'maybe_unserialize', $sessions );

	foreach ( $sessions as $session ) {
		$results = array_merge( $results, $session );
	}

	return (array) $results;
}

/**
 * Get all users with active sessions
 *
 * @return object WP_User
 */
function usc_get_users_with_sessions() {
	$args = array(
		'number'     => 9999,
		'blog_id'    => is_network_admin() ? 0 : get_current_blog_id(),
		'meta_query' => array(
			array(
				'key'     => 'session_tokens',
				'compare' => 'EXISTS',
			),
		),
	);

	$users = new WP_User_Query( $args );

	return $users;
}

/**
 * Get all sessions from all users
 *
 * @return array
 */
function usc_get_all_sessions() {
	$results  = array();
	$users    = usc_get_users_with_sessions()->get_results();
	$sessions = usc_get_all_sessions_raw();

	foreach ( $users as $user ) {
		$user_sessions = get_user_meta( $user->ID, 'session_tokens', true );

		foreach ( $sessions as $session ) {
			foreach ( $user_sessions as $token_hash => $user_session ) {
				// Loose comparison needed
				if ( $user_session != $session ) {
					continue;
				}
				$results[] = array(
					'user_id'    => $user->ID,
					'username'   => $user->user_login,
					'name'       => $user->display_name,
					'email'      => $user->user_email,
					'role'       => ! empty( $user->roles[0] ) ? $user->roles[0] : '',
					'created'    => $user_session['login'],
					'expiration' => $user_session['expiration'],
					'ip'         => $user_session['ip'],
					'user_agent' => $user_session['ua'],
					'token_hash' => $token_hash,
				);
			}
		}
	}

	return (array) $results;
}

/**
 * Destroy a specfic session for a specfic user
 *
 * @param int     $user_id
 * @param string  $token_hash
 *
 * @return void
 */
function usc_destroy_user_session( $user_id, $token_hash ) {
	$session_tokens = get_user_meta( $user_id, 'session_tokens', true );

	unset( $session_tokens[ $token_hash ] );

	update_user_meta( $user_id, 'session_tokens', $session_tokens );
}
