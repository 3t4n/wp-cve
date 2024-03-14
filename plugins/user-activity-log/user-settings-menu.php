<?php
/**
 * User Settings Menu.
 *
 * @package User Activity Log
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'ual_settings_panel' ) ) {
	/**
	 * Settings panel.
	 */
	function ual_settings_panel() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Settings', 'user-activity-log' ); ?></h1>
			<div class="tab_parent_parent ualParentTabs">
				<h2 class="nav-tab-wrapper nav-tab-wrapper">
					<a class="nav-tab nav-tab-active ualGeneralSettings" data-href="ualGeneralSettings" href="javascript:void(0)" >
						<?php esc_html_e( 'General Settings', 'user-activity-log' ); ?>
					</a>
					<a class="nav-tab ualUserSettings" data-href="ualUserSettings" href="javascript:void(0)">
						<?php esc_html_e( 'User Settings', 'user-activity-log' ); ?>
					</a>
					<a class="nav-tab ualEmailSettings" data-href="ualEmailSettings" href="javascript:void(0)">
						<?php esc_html_e( 'Email Notification', 'user-activity-log' ); ?>
					</a>
					<a class="nav-tab ualDebugSettings" data-href="ualDebugSettings" href="javascript:void(0)">
						<?php esc_html_e( 'System Status', 'user-activity-log' ); ?>
					</a>
					<a class="nav-tab ual-pro-feature" href="javascript:void(0)">
						<?php esc_html_e( 'Hook Settings', 'user-activity-log' ); ?>
					</a>
					<a class="nav-tab ual-pro-feature" href="javascript:void(0)">
						<?php esc_html_e( 'Password Settings', 'user-activity-log' ); ?>
					</a>
					<a class="nav-tab ual-pro-feature" href="javascript:void(0)">
						<?php esc_html_e( 'Role Manager', 'user-activity-log' ); ?>
					</a>
					<a class="nav-tab ual-pro-feature" href="javascript:void(0)">
						<?php esc_html_e( 'Custom Event Settings', 'user-activity-log' ); ?>
					</a>
				</h2>
			</div>
			<div class="ualTabContentWrap">
				<div id="ualGeneralSettings" style="display: none" class="ualpContentDiv"><?php ual_general_settings(); ?></div>
				<div id="ualUserSettings" style="display: none" class="ualpContentDiv"><?php ual_user_activity_setting_function(); ?></div>
				<div id="ualEmailSettings" style="display: none" class="ualpContentDiv"><?php ual_email_settings(); ?></div>
				<div id="ualDebugSettings" style="display: none" class="ualpContentDiv"><?php ual_debug_settings(); ?></div>

			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'ual_user_activity_setting_function' ) ) :
	/**
	 * User activity Settings.
	 */
	function ual_user_activity_setting_function() {
		global $wpdb;
		$class         = '';
		$message       = '';
		$paged         = 1;
		$total_pages   = 1;
		$srno          = 0;
		$recordperpage = 10;
		$display       = 'roles';
		$search        = '';
		if ( isset( $_GET['paged'] ) && ! empty( $_GET['paged'] ) ) {
			$paged = intval( $_GET['paged'] );
		}
		$offset = ( $paged - 1 ) * $recordperpage;
		if ( isset( $_GET['display'] ) && ! empty( $_GET['display'] ) ) {
			$display = sanitize_text_field( wp_unslash( $_GET['display'] ) );
		}
		if ( 'users' == $display ) {
			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				$table_name = $wpdb->base_prefix . 'users';
			} else {
				$table_name = $wpdb->prefix . 'users';
			}
			$select_query      = $wpdb->prepare( "SELECT * from {$wpdb->base_prefix}users LIMIT %d,%d", $offset, $recordperpage );
			$total_items_query = $wpdb->prepare( "SELECT count(*) FROM {$wpdb->base_prefix}users" );
		} else {
			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				$table_name = $wpdb->base_prefix . 'usermeta as um';
			} else {
				$table_name = $wpdb->prefix . 'usermeta as um';
			}
			$select_query      = $wpdb->prepare( "SELECT distinct um.meta_value from {$wpdb->base_prefix}usermeta as um WHERE um.meta_key='{$wpdb->prefix}capabilities' LIMIT %d,%d", $offset, $recordperpage );
			$total_items_query = $wpdb->prepare( "SELECT count(distinct um.meta_value) FROM {$wpdb->base_prefix}usermeta as um WHERE um.meta_key='{$wpdb->prefix}capabilities'" );
		}
		if ( isset( $_GET['txtsearch'] ) && ! empty( $_GET['txtsearch'] ) ) {
			if ( 'users' == $display ) {
				$search            = sanitize_text_field( wp_unslash( $_GET['txtsearch'] ) );
				$select_query      = $wpdb->prepare( "SELECT * from {$wpdb->base_prefix}users WHERE user_login like %s or user_email like %s or display_name like %s LIMIT %d,%d", '%' . $wpdb->esc_like( $search ) . '%', '%' . $wpdb->esc_like( $search ) . '%', '%' . $wpdb->esc_like( $search ) . '%', $offset, $recordperpage );
				$total_items_query = $wpdb->prepare( "SELECT count(*) FROM {$wpdb->base_prefix}users WHERE user_login like %s or user_email like %s or display_name like %s", '%' . $wpdb->esc_like( $search ) . '%', '%' . $wpdb->esc_like( $search ) . '%', '%' . $wpdb->esc_like( $search ) . '%' );
			}
		}
		if ( isset( $_POST['saveLogin'] ) && isset( $_POST['_wp_role_email_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wp_role_email_nonce'] ) ), '_wp_role_email_action' ) ) {
			if ( 'users' == $display ) {
				$enableusertemp = (array) get_option( 'enable_user_list_temp', true );
				update_option( 'enable_user_list', $enableusertemp );
			}
			if ( 'roles' == $display ) {
				$enablerole        = isset( $_POST['rolesID'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['rolesID'] ) ) : array();
				$enable_user_login = array();
				$c_rol             = count( (array) $enablerole );
				for ( $i = 0; $i < $c_rol; $i++ ) {
					$condition = "um.meta_key='" . $wpdb->prefix . "capabilities' and um.meta_value like '%" . $enablerole[ $i ] . "%' and u.ID = um.user_id";
					if ( function_exists( 'is_multisite' ) && is_multisite() ) {
						$get_user = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->base_prefix}usermeta as um, {$wpdb->base_prefix}users as u WHERE %s", $condition ) );
					} else {
						$get_user = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->base_prefix}usermeta as um, {$wpdb->base_prefix}users as u WHERE %s", $condition ) );
					}

					foreach ( $get_user as $k => $v ) {
						$enable_user_login[] = $v->user_login;
					}
				}
				update_option( 'enable_role_list', $enablerole );
				update_option( 'enable_user_list', $enable_user_login );
			}
			$post_id    = '';
			$class      = 'updated';
			$message    = esc_html__( 'Settings saved successfully.', 'user-activity-log' );
			$action     = 'Settings updated';
			$obj_type   = 'User Activity Log';
			$post_title = 'User Settings updated';
			ual_get_activity_function( $action, $obj_type, $class, $post_title );
		}

		// query for display all the users data start.
		$get_user_data = '';
		$get_data      = '';
		if ( 'users' == $display ) {
			$get_user_data = $wpdb->get_results( $select_query );
			$total_items   = $wpdb->get_var( $total_items_query, 0, 0 );
		} else {
			$get_data    = $wpdb->get_results( $select_query );
			$total_items = $wpdb->get_var( $total_items_query, 0, 0 );
		}

		// query for pagination.
		$total_pages = ceil( $total_items / $recordperpage );
		$next_page   = (int) $paged + 1;
		if ( $next_page > $total_pages ) {
			$next_page = $total_pages;
		}
		$prev_page = (int) $paged - 1;
		if ( $prev_page < 1 ) {
			$prev_page = 1;
		}
		?>
		<div class="wrap">
			<?php
			if ( ! empty( $class ) && ! empty( $message ) ) {
				ual_admin_notice_message( $class, $message );
			}
			$q_stt = isset( $_SERVER['QUERY_STRING'] ) ? sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) ) : '';
			?>
			<form class="sol-form" method="POST" action="<?php echo esc_url( admin_url( 'admin.php' ) ) . '?' . esc_html( $q_stt ); ?>">
				<div class="sol-box-border">
					<div class="ual-overlay" style="display: none"></div>
					<?php wp_nonce_field( 'ual_action_nonce', 'ual_nonce' ); ?>
					<h3 class="sol-header-text"><?php esc_html_e( 'Select Users/Roles', 'user-activity-log' ); ?></h3>
					<p><?php esc_html_e( 'Email will be sent upon login of these selected users/roles.', 'user-activity-log' ); ?></p>
					<!-- Search Box start -->
					<?php
					if ( 'users' == $display ) {
						?>
						<div class="sol-search-user-div">
							<p class="search-box">
								<label class="screen-reader-text" for="search-input"><?php esc_html_e( 'Search', 'user-activity-log' ); ?> :</label>
								<input id="user-search-input" class="sol-search-user" type="search" title="<?php esc_attr_e( 'Search user by username,email,firstname and lastname', 'user-activity-log' ); ?>" width="275px" placeholder="<?php esc_attr_e( 'Username, Email, Firstname, Lastname', 'user-activity-log' ); ?>" value="<?php echo esc_attr( $search ); ?>" name="txtSearchinput">
								<input id="search-submit" class="button" type="submit" value="<?php esc_attr_e( 'Search', 'user-activity-log' ); ?>" name="btnSearch_user_role">
							</p>
						</div>
						<?php
					}
					?>
					<!-- Search Box end -->
					<div class="tablenav top 
					<?php
					if ( 'roles' == $display ) {
						echo 'sol-display-roles';}
					?>
					">
						<!-- Drop down menu for user and Role Start -->
						<div class="alignleft actions sol-dropdown">
							<select class="user_role" name="user_role">
								<option selected value="roles"><?php esc_html_e( 'Role', 'user-activity-log' ); ?></option>
								<option <?php selected( $display, 'users' ); ?> value="users"><?php esc_html_e( 'User', 'user-activity-log' ); ?></option>
							</select>
						</div>
						<!-- Drop down menu for user and Role end -->
						<input class="button-secondary action sol-filter-btn" type="submit" value="<?php esc_html_e( 'Filter', 'user-activity-log' ); ?>" name="btn_filter_user_role">
						<!-- top pagination start -->
						<div class="tablenav-pages">
							<?php $items = $total_items . ' ' . _n( 'item', 'items', $total_items, 'user-activity-log' ); ?>
							<span class="displaying-num"><?php echo esc_html( $items ); ?></span>
							<div class="tablenav-pages" 
							<?php
							if ( (int) $total_pages <= 1 ) {
								echo 'style="display:none;"';
							}
							?>
							>
								<span class="pagination-links">
									<?php if ( '1' == $paged ) { ?>
										<span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>
										<span class="tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>
									<?php } else { ?>
										<a class="first-page 
										<?php
										if ( '1' == $paged ) {
											echo 'disabled';}
										?>
										" href="<?php echo esc_attr( admin_url( 'admin.php?page=general_settings_menu' ) ) . '&paged=1&display=' . esc_attr( $display ) . '&txtsearch=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the first page', 'user-activity-log' ); ?>">&laquo;</a>
										<a class="prev-page 
										<?php
										if ( '1' == $paged ) {
											echo 'disabled';}
										?>
										" href="<?php echo esc_attr( admin_url( 'admin.php?page=general_settings_menu' ) ) . '&paged=' . esc_attr( $prev_page ) . '&display=' . esc_attr( $display ) . '&txtsearch=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the previous page', 'user-activity-log' ); ?>">&lsaquo;</a>
									<?php } ?>
									<span class="paging-input">
										<input class="current-page" type="text" size="1" value="<?php echo esc_attr( $paged ); ?>" name="paged" title="<?php esc_attr_e( 'Current page', 'user-activity-log' ); ?>"> <?php esc_attr_e( 'of', 'user-activity-log' ); ?>
										<span class="total-pages"><?php echo esc_html( $total_pages ); ?></span>
									</span>
									<a class="next-page 
									<?php
									if ( $paged == $total_pages ) {
										echo 'disabled';}
									?>
									" href="<?php echo esc_attr( admin_url( 'admin.php?page=general_settings_menu' ) ) . '&paged=' . esc_attr( $next_page ) . '&display=' . esc_attr( $display ) . '&txtsearch=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the next page', 'user-activity-log' ); ?>">&rsaquo;</a>
									<a class="last-page 
									<?php
									if ( $paged == $total_pages ) {
										echo 'disabled';}
									?>
									" href="<?php echo esc_attr( admin_url( 'admin.php?page=general_settings_menu' ) ) . '&paged=' . esc_attr( $total_pages ) . '&display=' . esc_attr( $display ) . '&txtsearch=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the last page', 'user-activity-log' ); ?>">&raquo;</a>
								</span>
							</div>
						</div>
						<!-- top pagination end -->
					</div>
					<!-- display users details start -->
					<table class="widefat post fixed striped" cellspacing="0" style="
					<?php
					if ( 'users' == $display ) {
						echo 'display:table';
					}
					if ( 'roles' == $display ) {
						echo 'display:none';
					}
					?>
					">
						<thead>
							<tr>
								<th scope="col" class="check-column"><input type="checkbox" /></th>
								<th width="50px" scope="col"><?php esc_html_e( 'No.', 'user-activity-log' ); ?></th>
								<th scope="col"><?php esc_html_e( 'User', 'user-activity-log' ); ?></th>
								<th scope="col"><?php esc_html_e( 'First name', 'user-activity-log' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Last name', 'user-activity-log' ); ?></th>
								<th scope="col" class="role-width"><?php esc_html_e( 'Role', 'user-activity-log' ); ?></th>
								<th scope="col" class="email-id-width"><?php esc_html_e( 'Email address', 'user-activity-log' ); ?></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th scope="col" class="check-column"><input type="checkbox" /></th>
								<th width="50px" scope="col"><?php esc_html_e( 'No.', 'user-activity-log' ); ?></th>
								<th scope="col"><?php esc_html_e( 'User', 'user-activity-log' ); ?></th>
								<th scope="col"><?php esc_html_e( 'First name', 'user-activity-log' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Last name', 'user-activity-log' ); ?></th>
								<th scope="col" class="role-width"><?php esc_html_e( 'Role', 'user-activity-log' ); ?></th>
								<th scope="col" class="email-id-width"><?php esc_html_e( 'Email address', 'user-activity-log' ); ?></th>
							</tr>
						</tfoot>
						<tbody>
							<?php
							if ( $get_user_data ) {
								$srno = 1 + $offset;
								foreach ( $get_user_data as $data ) {
									$u_d        = get_userdata( $data->ID );
									$first_name = $u_d->user_firstname;
									$last_name  = $u_d->user_lastname;
									?>
									<tr>
										<?php
										$user_enable = (array) get_option( 'enable_user_list' );
										$checked     = '';
										if ( '' != $user_enable ) :
											if ( in_array( $data->user_login, $user_enable ) ) {
												$checked = 'checked=checked';
											}
										endif;
										?>
										<th scope="row" class="check-column ual-check-user"><input type="checkbox" <?php echo esc_html( $checked ); ?> name="usersID[]" value="<?php echo esc_attr( $data->user_login ); ?>" /></th>
										<td>
										<?php
											echo esc_attr( $srno );
											$srno++;
										?>
										</td>
										<td><?php echo esc_html( ucfirst( $data->user_login ) ); ?></td>
										<td><?php echo esc_html( ucfirst( $first_name ) ); ?></td>
										<td><?php echo esc_html( ucfirst( $last_name ) ); ?></td>
										<td>
										<?php
											global $wp_roles;
											$role_name = array();
											$user      = new WP_User( $data->ID );
										if ( ! empty( $user->roles ) && is_array( $user->roles ) ) {
											foreach ( $user->roles as $user_r ) {
												$role_name[] = $wp_roles->role_names[ $user_r ];
											}
											$role_name = implode( ', ', $role_name );
											echo esc_html( $role_name );
										}
										?>
											</td>
										<td class="email-id-width"><?php echo esc_html( $data->user_email ); ?></td>
									</tr>
									<?php
								}
							} else {
								echo '<tr class="no-items">';
								echo '<td class="colspanchange" colspan="7">' . esc_html__( 'No record found.', 'user-activity-log' ) . '</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>
					<!-- display users details end -->
					<!-- display roles details start -->
					<table class="widefat post fixed sol-display-roles striped" cellspacing="0" style="
					<?php
					if ( 'users' == $display ) {
						echo 'display:none';
					}
					if ( 'roles' == $display ) {
						echo 'display:table';
					}
					?>
					">
						<thead>
							<tr>
								<th scope="col" class="check-column"><input type="checkbox" /></th>
								<th scope="col"><?php esc_html_e( 'No.', 'user-activity-log' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Role', 'user-activity-log' ); ?></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th scope="col" class="check-column"><input type="checkbox" /></th>
								<th scope="col"><?php esc_html_e( 'No.', 'user-activity-log' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Role', 'user-activity-log' ); ?></th>
							</tr>
						</tfoot>
						<tbody>
							<?php
							if ( $get_data ) {
								$srno = 1 + $offset;
								foreach ( $get_data as $data ) {
									$final_roles = maybe_unserialize( $data->meta_value );
									$final_roles = key( $final_roles );
									?>
									<tr>
										<?php
										$role_enable = (array) get_option( 'enable_role_list' );
										$checked     = '';
										if ( '' != $role_enable ) :
											if ( in_array( $final_roles, $role_enable ) ) {
												$checked = 'checked=checked';
											}
										endif;
										?>
										<th scope="row" class="check-column ual-check-user">
											<input type="checkbox" <?php echo esc_html( $checked ); ?> name="rolesID[]" value="<?php echo esc_attr( $final_roles ); ?>" />
										</th>
										<td>
										<?php
											echo esc_html( $srno );
											$srno++;
										?>
											</td>
										<td><?php echo esc_html( ucfirst( $final_roles ) ); ?></td>
									</tr>
									<?php
								}
							} else {
								echo '<tr class="no-items">';
								echo '<td class="colspanchange" colspan="3">' . esc_html__( 'No record found.', 'user-activity-log' ) . '</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>
					<!-- display roles details end -->
					<!-- bottom pagination start -->
					<div class="tablenav top 
					<?php
					if ( 'roles' == $display ) {
						echo 'sol-display-roles';}
					?>
					">
						<div class="tablenav-pages">
							<span class="displaying-num"><?php echo esc_html( $items ); ?></span>
							<div class="tablenav-pages" 
							<?php
							if ( (int) $total_pages <= 1 ) {
								echo 'style="display:none;"';
							}
							?>
							>
								<span class="pagination-links">
									<?php if ( '1' == $paged ) { ?>
										<span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>
										<span class="tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>
									<?php } else { ?>
										<a class="first-page 
										<?php
										if ( '1' == $paged ) {
											echo 'disabled';}
										?>
										" href="<?php echo esc_attr( admin_url( 'admin.php?page=general_settings_menu' ) ) . '&paged=1&display=' . esc_attr( $display ) . '&txtsearch=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the first page', 'user-activity-log' ); ?>">&laquo;</a>
										<a class="prev-page 
										<?php
										if ( '1' == $paged ) {
											echo 'disabled';}
										?>
										" href="<?php echo esc_attr( admin_url( 'admin.php?page=general_settings_menu' ) ) . '&paged=' . esc_attr( $prev_page ) . '&display=' . esc_attr( $display ) . '&txtsearch=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the previous page', 'user-activity-log' ); ?>">&lsaquo;</a>
									<?php } ?>
									<span class="paging-input">
										<span class="current-page" title="<?php esc_attr_e( 'Current page', 'user-activity-log' ); ?>"><?php echo esc_html( $paged ); ?></span>
										<span class="total-pages"><?php echo esc_html( $total_pages ); ?></span>
									</span>
									<a class="next-page 
									<?php
									if ( $paged == $total_pages ) {
										echo 'disabled';}
									?>
									" href="<?php echo esc_attr( admin_url( 'admin.php?page=general_settings_menu' ) ) . '&paged=' . esc_attr( $next_page ) . '&display=' . esc_attr( $display ) . '&txtsearch=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the next page', 'user-activity-log' ); ?>">&rsaquo;</a>
									<a class="last-page 
									<?php
									if ( $paged == $total_pages ) {
										echo 'disabled';}
									?>
									" href="<?php echo esc_attr( admin_url( 'admin.php?page=general_settings_menu' ) ) . '&paged=' . esc_attr( $total_pages ) . '&display=' . esc_attr( $display ) . '&txtsearch=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the last page', 'user-activity-log' ); ?>">&raquo;</a>
								</span>
							</div>
						</div>
					</div>
					<!-- bottom pagination end -->
					<?php
					wp_nonce_field( '_wp_role_email_action', '_wp_role_email_nonce' );
					?>
					<p class="submit">
						<input id="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save Changes', 'user-activity-log' ); ?>" name="saveLogin">
					</p>
				</div>
			</form>
			<?php ual_advertisment_sidebar(); ?>
		</div>
		<?php
	}

endif;

if ( ! function_exists( 'ual_email_settings' ) ) :
	/**
	 * Email settings.
	 */
	function ual_email_settings() {
		$class = '';
		$msg   = '';
		add_option( 'enable_email' );
		add_option( 'to_email' );
		add_option( 'from_email' );
		add_option( 'email_message' );
		global $current_user;
		wp_get_current_user();
		$to_email     = get_option( 'to_email' ) ? get_option( 'to_email' ) : $current_user->user_email;
		$from_email   = get_option( 'from_email' ) ? get_option( 'from_email' ) : get_option( 'admin_email' );
		$email_enable = get_option( 'enable_email' ) ? get_option( 'enable_email' ) : 0;
		$user_details = '[user_details]';

		$mail_msgs  = esc_html__( 'Hi ', 'user-activity-log' );
		$mail_msgs .= $current_user->display_name . ',';
		$mail_msgs .= "\n\n" . esc_html__( 'Following user is logged in your site', 'user-activity-log' ) . " \n$user_details";
		$mail_msgs .= "\n\n" . esc_html__( 'Thanks', 'user-activity-log' ) . ",\n";
		$mail_msgs .= home_url();

		$mail_msg = get_option( 'email_message' ) ? get_option( 'email_message' ) : $mail_msgs;
		if ( isset( $_POST['btnsolEmail'] ) && isset( $_POST['_wp_email_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wp_email_nonce'] ) ), '_wp_email_action' ) ) {
			$to_email     = isset( $_POST['sol-mail-to'] ) ? sanitize_email( wp_unslash( $_POST['sol-mail-to'] ) ) : '';
			$from_email   = isset( $_POST['sol-mail-from'] ) ? sanitize_email( wp_unslash( $_POST['sol-mail-from'] ) ) : '';
			$mail_msg     = isset( $_POST['sol-mail-msg'] ) ? sanitize_textarea_field( wp_unslash( $_POST['sol-mail-msg'] ) ) : '';
			$email_enable = isset( $_POST['emailEnable'] ) ? intval( $_POST['emailEnable'] ) : '';
			update_option( 'enable_email', $email_enable );
			if ( isset( $_POST['emailEnable'] ) ) {
				if ( '1' == $_POST['emailEnable'] ) {
					if ( '' == $mail_msg ) {
						$msg   = esc_html__( 'Please enter message', 'user-activity-log' );
						$class = 'error';
					} elseif ( '' == $to_email || '' == $from_email ) {
						$msg   = esc_html__( 'Please enter the email address', 'user-activity-log' );
						$class = 'error';
					} elseif ( ! filter_var( $to_email, FILTER_VALIDATE_EMAIL ) || ! filter_var( $from_email, FILTER_VALIDATE_EMAIL ) || ! is_email( $to_email ) || ! is_email( $from_email ) ) {
						$msg   = esc_html__( 'Please enter valid email address', 'user-activity-log' );
						$class = 'error';
					} else {
						update_option( 'to_email', $to_email );
						update_option( 'from_email', $from_email );
						update_option( 'email_message', $mail_msg );
						$msg   = esc_html__( 'Settings saved successfully.', 'user-activity-log' );
						$class = 'updated';
					}
				}
				$post_id    = '';
				$action     = 'Settings updated';
				$obj_type   = 'User Activity Log';
				$post_title = 'Email Settings updated';
				ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
			}
		}
		?>
		<div class="wrap">
			<?php
			if ( '' != $msg ) {
				ual_admin_notice_message( $class, $msg );
			}
			$q_stt = isset( $_SERVER['QUERY_STRING'] ) ? sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) ) : '';
			?>
			<form class="sol-form" method="POST" action="<?php echo esc_url( admin_url( 'admin.php' ) ) . '?' . esc_html( $q_stt ); ?>">
				<div class="sol-box-border">
					<h3 class="sol-header-text"><?php esc_html_e( 'Email', 'user-activity-log' ); ?></h3>
					<p class="margin_bottom_30"><?php esc_html_e( 'This email will be sent upon login of selected users/roles.', 'user-activity-log' ); ?></p>
					<table class="sol-email-table" cellspacing="0">
						<tr>
							<th><?php esc_html_e( 'Enable?', 'user-activity-log' ); ?></th>
							<td>
								<input type="radio" <?php checked( $email_enable, 1 ); ?> value="1" id="enableEmail" name="emailEnable" class="ui-helper-hidden-accessible">
								<label class="ui-button ui-widget ui-state-default ui-button-text-only ui-corner-left" for="enableEmail" role="button">
									<span class="ui-button-text"><?php esc_html_e( 'Yes', 'user-activity-log' ); ?></span>
								</label>
								<input type="radio" <?php checked( $email_enable, 0 ); ?> value="0" id="disableEmail" name="emailEnable" class="ui-helper-hidden-accessible">
								<label class="ui-button ui-widget ui-state-default ui-button-text-only ui-corner-right"for="disableEmail" role="button">
									<span class="ui-button-text"><?php esc_html_e( 'No', 'user-activity-log' ); ?></span>
								</label>
							</td>
						</tr>
						<tr class="fromEmailTr">
							<th><?php esc_html_e( 'From Email', 'user-activity-log' ); ?></th>
							<td>
								<input type="email" name="sol-mail-from" value="<?php echo esc_attr( $from_email ); ?>">
								<p class="description"><?php esc_html_e( 'The source Email address', 'user-activity-log' ); ?></p>
							</td>
						</tr>
						<tr class="toEmailTr">
							<th><?php esc_html_e( 'To Email', 'user-activity-log' ); ?></th>
							<td>
								<input type="email" name="sol-mail-to" value="<?php echo esc_attr( $to_email ); ?>">
								<p class="description"><?php esc_html_e( 'The Email address notifications will be sent to', 'user-activity-log' ); ?></p>
							</td>
						</tr>
						<tr class="messageTr">
							<th><?php esc_html_e( 'Message', 'user-activity-log' ); ?></th>
							<td>
								<textarea cols="50" name="sol-mail-msg" rows="5"><?php echo esc_attr( $mail_msg ); ?></textarea>
								<p class="description"><?php esc_html_e( 'Customize the message as per your requirement', 'user-activity-log' ); ?></p>
							</td>
						</tr>
					</table>
					<?php
					wp_nonce_field( '_wp_email_action', '_wp_email_nonce' );
					?>
					<p class="submit">
						<input class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save Changes', 'user-activity-log' ); ?>" name="btnsolEmail">
					</p>
				</div>
			</form>
			<?php ual_advertisment_sidebar(); ?>
		</div>
		<?php
	}

endif;

add_action( 'wp_login', 'ual_send_email', 99 );
if ( ! function_exists( 'ual_send_email' ) ) {
	/**
	 * Send email when selected user login.
	 *
	 * @param string $login current username when login.
	 */
	function ual_send_email( $login ) {
		if ( get_option( 'enable_email' ) ) {
			$current_user1 = get_user_by( 'login', $login );
			$current_user  = ! empty( $current_user1->user_login ) ? $current_user1->user_login : '-';
			$enable_unm    = get_option( 'enable_user_list' );
			$c_num         = count( $enable_unm );
			for ( $i = 0; $i < $c_num; $i++ ) {
				if ( $enable_unm[ $i ] == $current_user ) {
					$to_email   = get_option( 'to_email' );
					$from_email = get_option( 'from_email' );
					$ip         = ual_get_ip();
					$firstname  = ucfirst( $current_user1->user_firstname );
					$lastname   = ucfirst( $current_user1->user_lastname );

					$user_firstnm = ! empty( $firstname ) ? ucfirst( $firstname ) : '-';
					$user_lastnm  = ! empty( $lastname ) ? ucfirst( $lastname ) : '-';
					$user_email   = ! empty( $current_user1->user_email ) ? $current_user1->user_email : '-';

					$modified_date = current_time( 'mysql' );
					$modified_date = strtotime( $modified_date );

					$date_format = get_option( 'date_format' );
					$time_format = get_option( 'time_format' );

					$date      = gmdate( $date_format, $modified_date );
					$time      = gmdate( $time_format, $modified_date );
					$user_reg  = $date;
					$user_reg .= ' ';
					$user_reg .= $time;

					$current_user = ucfirst( $current_user );
					$user_details = "<table cellspacing='0' border='1px solid #ccc' class='sol-msg' style='margin-top:30px'>
                                    <tr>
                                        <td style='padding:5px 10px;'>" . esc_html__( 'Username', 'user-activity-log' ) . "</td>
                                        <td style='padding:5px 10px;'>" . esc_html__( 'Firstname', 'user-activity-log' ) . "</td>
                                        <td style='padding:5px 10px;'>" . esc_html__( 'Lastname', 'user-activity-log' ) . "</td>
                                        <td style='padding:5px 10px;'>" . esc_html__( 'Email', 'user-activity-log' ) . "</td>
                                        <td style='padding:5px 10px;'>" . esc_html__( 'Date Time', 'user-activity-log' ) . "</td>
                                        <td style='padding:5px 10px;'>" . esc_html__( 'IP address', 'user-activity-log' ) . "</td>
                                    </tr>
                                    <tr>
                                        <td style='padding:5px 10px;'>$current_user</td>
                                        <td style='padding:5px 10px;'>$user_firstnm</td>
                                        <td style='padding:5px 10px;'>$user_lastnm</td>
                                        <td style='padding:5px 10px;'>$user_email</td>
                                        <td style='padding:5px 10px;'>$user_reg</td>
                                        <td style='padding:5px 10px;'>$ip</td>
                                    </tr>
                                </table><br/><br/>";

					$mail_msg = htmlentities( get_option( 'email_message' ) );
					$mail_msg = str_replace( '[user_details]', $user_details, $mail_msg );

					if ( '' != $to_email && '' != $mail_msg && '' != $from_email ) {
						$headers  = 'From: ' . wp_strip_all_tags( $from_email ) . "\r\n";
						$headers .= 'Reply-To: ' . wp_strip_all_tags( $from_email ) . "\r\n";
						$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
						wp_mail( $to_email, esc_html__( 'User Login Notification', 'user-activity-log' ), $mail_msg, $headers );
					}
				}
			}
		}
	}
}


if ( ! function_exists( 'ual_general_settings' ) ) {
	/**
	 * General settings.
	 */
	function ual_general_settings() {
		global $wpdb;
		$table_nm = $wpdb->prefix . 'ualp_user_activity';
		if ( isset( $_GET['db'] ) && 'reset' == $_GET['db'] ) {
			$nonce = '';
			if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'my-nonce' ) ) {
				// This nonce is not valid.
				return false;
			} else {
				if ( $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}ualp_user_activity'" ) ) {
					$wpdb->query( "TRUNCATE {$wpdb->prefix}ualp_user_activity" );
					$class   = 'updated';
					$message = esc_html__( 'All activities from the database has been deleted successfully.', 'user-activity-log' );
					ual_admin_notice_message( $class, $message );
				}
			}
		}
		$log_day      = '30';
		$ual_allow_ip = '';
		if ( isset( $_POST['submit_display'] ) && isset( $_POST['_wp_ualp_general_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wp_ualp_general_nonce'] ) ), '_wp_ualp_general_action' ) ) {
			$time_ago = isset( $_POST['logdel'] ) ? intval( $_POST['logdel'] ) : '';
			if ( isset( $_POST['ualAllowIp'] ) ) {
				update_option( 'ualpAllowIp', '1' );
			} else {
				update_option( 'ualpAllowIp', '0' );
			}
			if ( isset( $_POST['ualIPtypes'] ) ) {
				update_option( 'ualIPtypes', sanitize_text_field( wp_unslash( $_POST['ualIPtypes'] ) ) );
			} else {
				update_option( 'ualIPtypes', 'REMOTE_ADDR' );
			}
			if ( isset( $_POST['ualp_allow_stats_report_dashbord_widget'] ) ) {
				update_option( 'ualp_allow_stats_report_dashbord_widget', '1' );
			} else {
				update_option( 'ualp_allow_stats_report_dashbord_widget', '0' );
			}

			if ( isset( $_POST['ualDeleteData'] ) ) {
				update_option( 'ualDeleteData', '1' );
			} else {
				update_option( 'ualDeleteData', '0' );
			}

			if ( isset( $_POST['logs_failed_login'] ) && ! empty( $_POST['logs_failed_login'] ) ) {
				update_option( 'logs_failed_login', sanitize_text_field( wp_unslash( $_POST['logs_failed_login'] ) ) );
			}
			if ( isset( $_POST['login_failed_existing_user'] ) && ! empty( $_POST['login_failed_existing_user'] ) ) {
				update_option( 'login_failed_existing_user', sanitize_text_field( wp_unslash( $_POST['login_failed_existing_user'] ) ) );
			} else {
				update_option( 'login_failed_existing_user', '0' );
			}
			if ( isset( $_POST['login_failed_non_existing_user'] ) && ! empty( $_POST['login_failed_non_existing_user'] ) ) {
				update_option( 'login_failed_non_existing_user', sanitize_text_field( wp_unslash( $_POST['login_failed_non_existing_user'] ) ) );
			} else {
				update_option( 'login_failed_non_existing_user', '0' );
			}

			if ( ! empty( $time_ago ) ) {
				update_option( 'ualpKeepLogsDay', $time_ago );
			}
			if ( ! empty( $time_ago ) || isset( $_POST['ualAllowIp'] ) || isset( $_POST['logs_failed_login'] ) || isset( $_POST['ualp_allow_stats_report_dashbord_widget'] ) ) {
				$action     = 'Settings updated';
				$post_title = 'General Settings updated';
				$class      = 'updated';
				$obj_type   = 'User Activity Log';
				$message    = esc_html__( 'Settings saved successfully.', 'user-activity-log' );
				ual_admin_notice_message( $class, $message );
				ual_get_activity_function( $action, $obj_type, $class, $post_title );
			}
		}
		$log_day                                 = get_option( 'ualpKeepLogsDay' );
		$ual_allow_ip                            = get_option( 'ualpAllowIp' );
		$ual_ip_types                            = get_option( 'ualIPtypes', 'REMOTE_ADDR' );
		$ualp_allow_stats_report_dashbord_widget = get_option( 'ualp_allow_stats_report_dashbord_widget', true );
		$ual_delete_data                         = get_option( 'ualDeleteData' );
		$logs_failed_login                       = get_option( 'logs_failed_login' );
		$login_failed_non_existing_user          = get_option( 'login_failed_non_existing_user' );
		$login_failed_existing_user              = get_option( 'login_failed_existing_user' );

		$q_stt = isset( $_SERVER['QUERY_STRING'] ) ? sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) ) : '';
		?>
		<div class="wrap">
			<form class="sol-form" method="POST" action="<?php echo esc_url( admin_url( 'admin.php' ) ) . '?' . esc_html( $q_stt ); ?>" name="general_setting_form">
				<div class="sol-box-border">
					<h3 class="sol-header-text"><?php esc_html_e( 'Display Option', 'user-activity-log' ); ?></h3>
					<p class="margin_bottom_30"><?php esc_html_e( 'There are some basic options for display User Action Log', 'user-activity-log' ); ?></p>
					<table class="sol-email-table">
						<tr>
							<th><?php esc_html_e( 'Enable IP Address For Log', 'user-activity-log' ); ?></th>
							<td>
								<input id="ualAllowIp" type="checkbox" value="1" <?php checked( '1', $ual_allow_ip ); ?> name="ualAllowIp">&nbsp;<label for="ualAllowIp"><?php esc_html_e( 'Allow IP Address of users to log.', 'user-activity-log' ); ?></label>
							</td>
						</tr>
						<?php if( 1 == $ual_allow_ip ) { ?>
							<tr class="ual_get_ips">
								<th><?php esc_html_e( 'How does User Activity Log get IPs', 'user-activity-log' ); ?></th>
								<td>
									<input id="ualIPtypes-default" type="radio" value="REMOTE_ADDR" <?php checked( 'REMOTE_ADDR', $ual_ip_types ); ?> name="ualIPtypes">&nbsp;<label for="ualIPtypes-default"><?php esc_html_e( 'Let Plugin use the most secure method to get visitor IP addresses. Prevents spoofing and works with most sites. (Recommended)', 'user-activity-log' ); ?></label><br>
									<input id="ualIPtypes-HTTP_X_FORWARDED_FOR" type="radio" value="HTTP_X_FORWARDED_FOR" <?php checked( 'HTTP_X_FORWARDED_FOR', $ual_ip_types ); ?> name="ualIPtypes">&nbsp;<label for="ualIPtypes-HTTP_X_FORWARDED_FOR"><?php esc_html_e( 'Use the X-Forwarded-For HTTP header. Only use if you have a front-end proxy or spoofing may result.', 'user-activity-log' ); ?></label><br>
									<input id="ualIPtypes-HTTP_X_REAL_IP" type="radio" value="HTTP_X_REAL_IP" <?php checked( 'HTTP_X_REAL_IP', $ual_ip_types ); ?> name="ualIPtypes">&nbsp;<label for="ualIPtypes-HTTP_X_REAL_IP"><?php esc_html_e( 'Use the X-Real-IP HTTP header. Only use if you have a front-end proxy or spoofing may result.', 'user-activity-log' ); ?></label><br>
									<div class="ual-left"><?php esc_html_e( 'Detected IP(s):', 'user-activity-log' ); ?> <span id="howGetIPs-preview-all"><strong><?php echo esc_html( ual_get_ip() ); ?></strong></span></div>
									<div class="ual-left"><?php esc_html_e( 'Your IP with this setting:', 'user-activity-log' ); ?> <span id="howGetIPs-preview-single"><?php echo esc_html( ual_get_ip() ); ?></span></div>
								</td>
							</tr>
						<?php } ?>
						<tr>
							<th><?php esc_html_e( 'Enable Stats Report on Dashboard Widget', 'user-activity-log' ); ?></th>
							<td>
								<input id="ualp_allow_stats_report_dashbord_widget" type="checkbox" value="1" <?php checked( '1', $ualp_allow_stats_report_dashbord_widget ); ?> name="ualp_allow_stats_report_dashbord_widget">&nbsp;<label for="ualp_allow_stats_report_dashbord_widget"><?php esc_html_e( 'Display Stats Report on Dashboard Widget.', 'user-activity-log' ); ?></label>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Keep logs for', 'user-activity-log' ); ?></th>
							<td>
								<input type="number" step="1" min="1" placeholder="30" value="<?php echo esc_attr( $log_day ); ?>" name="logdel">&nbsp;<?php esc_html_e( 'Days', 'user-activity-log' ); ?>
								<p><?php esc_html_e( 'Maximum number of days to keep activity log. Leave blank to keep activity log forever', 'user-activity-log' ); ?> (<?php esc_html_e( 'not recommended', 'user-activity-log' ); ?>).</p>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Keep Failed Login Logs', 'user-activity-log' ); ?></th>
							<td>
								<select name="logs_failed_login">
									<option value="yes" 
									<?php
									if ( 'yes' == $logs_failed_login ) {
										echo 'selected="selected"'; }
									?>
									><?php esc_html_e( 'Keep', 'user-activity-log' ); ?></option>
									<option value="no" 
									<?php
									if ( 'no' == $logs_failed_login ) {
										echo 'selected="selected"'; }
									?>
									><?php esc_html_e( "Don't Keep", 'user-activity-log' ); ?> (<?php esc_html_e( 'Not recommended', 'user-activity-log' ); ?>)</option>
								</select>
							</td>
						</tr>
						<tr class="no_of_failed_login">
							<th><?php esc_html_e( 'Number of failed login for existing user', 'user-activity-log' ); ?></th>
							<td>
								<input type="number" step="1" min="0" placeholder="0" value="<?php echo esc_attr( $login_failed_existing_user ); ?>" name="login_failed_existing_user">
								<p><?php esc_html_e( 'Number of login attempts to log. Enter 0 to log all failed login attempts.', 'user-activity-log' ); ?></p>
							</td>
						</tr>
						<tr class="no_of_failed_login">
							<th><?php esc_html_e( 'Number of failed login for non existing user', 'user-activity-log' ); ?></th>
							<td>
								<input type="number" step="1" min="0" placeholder="0" value="<?php echo esc_attr( $login_failed_non_existing_user ); ?>" name="login_failed_non_existing_user">
								<p><?php esc_html_e( 'Number of login attempts to log. Enter 0 to log all failed login attempts.', 'user-activity-log' ); ?></p>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Delete Log Activities', 'user-activity-log' ); ?></th>
							<td>
								<?php $nonce = wp_create_nonce( 'my-nonce' ); ?>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=general_settings_menu' ) ); ?>&db=reset&_wpnonce=<?php echo esc_attr( $nonce ); ?>" onClick="return confirm('<?php esc_html_e( 'Are you sure want to Reset Database?', 'user-activity-log' ); ?>');"><?php esc_html_e( 'Reset Database', 'user-activity-log' ); ?></a>
								<p><span class="red"><?php esc_html_e( 'Warning', 'user-activity-log' ); ?>: &nbsp;</span><?php esc_html_e( 'Clicking this will delete all activities from the database.', 'user-activity-log' ); ?></p>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Delete data on deletion of plugin', 'user-activity-log' ); ?></th>
							<td>
								<input id="ualDeleteData" type="checkbox" value="1" <?php checked( '1', $ual_delete_data ); ?> name="ualDeleteData">&nbsp;<label for="ualDeleteData"><?php esc_html_e( 'Delete data on deletion of plugin.', 'user-activity-log' ); ?></label>
							</td>
						</tr>
					</table>
					<?php wp_nonce_field( '_wp_ualp_general_action', '_wp_ualp_general_nonce' ); ?>
					<p class="submit">
						<input id="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save Changes', 'user-activity-log' ); ?>" name="submit_display">
					</p>
				</div>
			</form>
			<?php ual_advertisment_sidebar(); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'ual_debug_settings' ) ) {
	/**
	 * Debug settings
	 */
	function ual_debug_settings() {
		?>
		<form class="ualpSettingsForm">
			<div class="padding_left_right_15">
				<h3 class="ualpHeaderTitle"><?php echo esc_html__( 'Database size', 'user-activity-log' ); ?></h3>
					<?php
					global $wpdb;
					$table_name                   = $wpdb->prefix . 'ualp_user_activity';
					$sql_table_size               = sprintf( 'SELECT table_name AS "table_name", round(((data_length + index_length) / 1024 / 1024), 2) "size_in_mb" FROM information_schema.TABLES WHERE table_schema = "%1$s" AND table_name IN ("%2$s");', DB_NAME, $table_name );
					$ualp_table_size_result       = $wpdb->get_results( $sql_table_size );
					$ualp_user_activity_row_table = (int) $wpdb->get_var( "select count(*) FROM {$wpdb->prefix}ualp_user_activity" );

					$ualp_table_size_result[0]->num_rows = $ualp_user_activity_row_table;

					echo "<table class='widefat'>";
					printf( '<thead><tr><th>%1$s</th><th>%2$s</th><th>%3$s</th></tr></thead>', esc_html_x( 'Table name', 'ualp debug', 'user-activity-log' ), esc_html_x( 'Size', 'ualp debug', 'user-activity-log' ), esc_html_x( 'Rows', 'ualp debug', 'user-activity-log' ) );
					$loopnum = 0;
					foreach ( $ualp_table_size_result as $ualp_table ) {
						// translators:.
						$size = sprintf( esc_html_x( '%s MB', 'ualp debug', 'user-activity-log' ), $ualp_table->size_in_mb );
						// translators:.
						$rows = sprintf( esc_html_x( '%s rows', 'ualp debug', 'user-activity-log' ), number_format_i18n( $ualp_table->num_rows, 0 ) );
						printf( '<tr class="%4$s"><td>%1$s</td><td>%2$s</td><td>%3$s</td></tr>', $ualp_table->table_name, $size, $rows, $loopnum % 2 ? ' alt ' : '' );
						$loopnum++;
					}
					echo '</table>';
					?>
				<h3 class="ualpHeaderTitle"><?php echo esc_html__( 'Active Plugins', 'user-activity-log' ); ?> (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)</h3>
				<?php
				$active_plugins = (array) get_option( 'active_plugins', array() );
				echo "<table class='widefat'>";
				printf( '<thead><tr><th>%1$s</th><th>%2$s</th><th>%3$s</th></tr></thead>', esc_html_x( 'Plugin name', 'ualp debug', 'user-activity-log' ), esc_html_x( 'Plugin file path', 'ualp debug', 'user-activity-log' ), esc_html_x( 'Author / Version', 'ualp debug', 'user-activity-log' ) );
				if ( is_multisite() ) {
					$network_activated_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
					$active_plugins            = array_merge( $active_plugins, $network_activated_plugins );
				}
				$args_kses = args_kses();
				foreach ( $active_plugins as $plugin ) {
					$plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
					$dirname        = dirname( $plugin );
					$version_string = '';
					$network_string = '';

					if ( ! empty( $plugin_data['Name'] ) ) {

						// Link the plugin name to the plugin url if available.
						$plugin_name = esc_html( $plugin_data['Name'] );

						if ( ! empty( $plugin_data['PluginURI'] ) ) {
							$plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . esc_attr__( 'Visit plugin homepage', 'user-activity-log' ) . '" target="_blank">' . $plugin_name . '</a>';
						}

						if ( strstr( $dirname, 'woocommerce-' ) && strstr( $plugin_data['PluginURI'], 'woothemes.com' ) ) {
							$version_data = get_transient( md5( $plugin ) . '_version_data' );
							if ( false === $version_data ) {
								$changelog = wp_safe_remote_get( 'http://dzv365zjfbd8v.cloudfront.net/changelogs/' . $dirname . '/changelog.txt' );
								$cl_lines  = explode( "\n", wp_remote_retrieve_body( $changelog ) );
								if ( ! empty( $cl_lines ) ) {
									foreach ( $cl_lines as $line_num => $cl_line ) {
										if ( preg_match( '/^[0-9]/', $cl_line ) ) {

											$date         = str_replace( '.', '-', trim( substr( $cl_line, 0, strpos( $cl_line, '-' ) ) ) );
											$version      = preg_replace( '~[^0-9,.]~', '', stristr( $cl_line, 'version' ) );
											$update       = trim( str_replace( '*', '', $cl_lines[ $line_num + 1 ] ) );
											$version_data = array(
												'date'    => $date,
												'version' => $version,
												'update'  => $update,
												'changelog' => $changelog,
											);
											set_transient( md5( $plugin ) . '_version_data', $version_data, DAY_IN_SECONDS );
											break;
										}
									}
								}
							}

							if ( ! empty( $version_data['version'] ) && version_compare( $version_data['version'], $plugin_data['Version'], '>' ) ) {
								$version_string = ' &ndash; <strong style="color:red;">' . esc_html( sprintf( '%s ' . esc_html_x( 'is available', 'Version info', 'user-activity-log' ), $version_data['version'] ) ) . '</strong>';
							}

							if ( false != $plugin_data['Network'] ) {
								$network_string = ' &ndash; <strong style="color:black;">' . __( 'Network enabled', 'user-activity-log' ) . '</strong>';
							}
						}
						?>
						<tr>
							<td><?php echo wp_kses( $plugin_name, $args_kses ); ?></td>
							<td ><?php echo wp_kses( $plugin, $args_kses ); ?></td>
							<td><?php echo sprintf( esc_html_x( 'by', 'by author', 'user-activity-log' ) . ' %s', wp_kses( $plugin_data['Author'], $args_kses ) ) . ' &ndash; ' . esc_html( $plugin_data['Version'] ) . esc_html( $version_string ) . esc_html( $network_string ); ?></td>
						</tr>
						<?php
					}
				}
				echo '</table>';
				?>
			</div>
		</form>
		<?php
	}
}
