<?php

add_action( 'fed_enqueue_script_style_frontend', 'fedt_enqueue_template1' );

function fedt_enqueue_template1() {
	wp_enqueue_script( 'fedt_common', plugins_url( '/assets/js/fedt_script.js', FED_TEMPLATES_PLUGIN ), array() );
	wp_enqueue_script( 'fedt_template1', plugins_url( '/assets/js/fedt_template1.js', FED_TEMPLATES_PLUGIN ), array() );

	wp_enqueue_style(
		'fedt_common',
		plugins_url( '/assets/css/fedt_style.css', FED_TEMPLATES_PLUGIN ),
		array(), FED_TEMPLATES_PLUGIN_VERSION, 'all'
	);
	wp_enqueue_style(
		'fedt_template1',
		plugins_url( '/assets/css/fedt_template1.css', FED_TEMPLATES_PLUGIN ),
		array(), FED_TEMPLATES_PLUGIN_VERSION, 'all'
	);
}

/**
 * @return string
 */
function fedt_get_website_logo() {
	$upl_settings = get_option( 'fed_admin_settings_upl' );
	if ( isset( $upl_settings['settings']['fed_upl_website_logo'] ) && 0 !== $upl_settings['settings']['fed_upl_website_logo'] ) {
		$logo_id    = $upl_settings['settings']['fed_upl_website_logo'];
		$logo_image = wp_get_attachment_url( $logo_id );
		$width      = isset( $upl_settings['settings']['fed_upl_website_logo_width'] ) && ! empty( $upl_settings['settings']['fed_upl_website_logo_width'] ) ? 'width:' . $upl_settings['settings']['fed_upl_website_logo_width'] . 'px;' : '';
		$height     = isset( $upl_settings['settings']['fed_upl_website_logo_height'] ) && ! empty( $upl_settings['settings']['fed_upl_website_logo_height'] ) ? 'height:' . $upl_settings['settings']['fed_upl_website_logo_height'] . 'px' : '';
		$logo       = '<div class="p-b-20"><img class="img-responsive" style=" ' . $width . ' ' . $height . '"  src="' . $logo_image . '"/></div>';
	} else {
		$blog_name = get_bloginfo( 'name' );
		$logo      = '<h1>' . $blog_name . '</h1>';
	}

	return $logo;
}

add_action( 'init', 'fedt_load_text_domain' );
function fedt_load_text_domain() {
	load_plugin_textdomain( 'frontend-dashboard-templates', false, FED_TEMPLATES_PLUGIN_NAME . '/languages' );
}


/**
 * User Templates
 *
 * @param $fed_user_attr
 * @param $user_id
 */
function fedt_show_user_by_role( $fed_user_attr, $user_id ) {
	$user = new WP_User_Query(
		array(
			'include' => (int) $user_id,
			'role'    => $fed_user_attr->role,
		)
	);
	if ( ! $user->get_total() ) {
		?>
		<div class="alert alert-info text-center">
			<button type="button"
					class="close"
					data-dismiss="alert"
					aria-hidden="true">&times;
			</button>
			<strong><?php _e( 'Sorry!', 'frontend-dashboard' ); ?></strong>
			<?php _e( 'No user found...', 'frontend-dashboard' ); ?>
		</div>
		<?php
	} else {
		$results = $user->get_results();
		fedt_show_user_profile_page( $results[0] );
	}
	?>

	<?php
}

/**
 * @param $user
 */
function fedt_show_user_profile_page( $user ) {
	/**
	 * Collect Menu, User Information and Menu Items
	 */
	$profiles    = fed_array_group_by_key( fed_fetch_user_profile_by_dashboard(), 'menu' );
	$menus       = fed_fetch_table_rows_with_key( BC_FED_TABLE_MENU, 'menu_slug' );
	$upl_options = get_option( 'fed_admin_settings_upl' );
	/**
	 * Get author recent Posts
	 */
	$post_count   = isset( $upl_options['settings']['fed_upl_no_recent_post'] ) ? $upl_options['settings']['fed_upl_no_recent_post'] : 5;
	$author_query = array(
		'posts_per_page' => $post_count,
		'author'         => $user->ID,
		'order'          => 'DESC',
	);
	$author_posts = new WP_Query( $author_query );
	$logo         = fedt_get_website_logo();
	?>

	<div id="primary fed_user_profile" class="bc_fed fed-profile-area container">

		<div class="row fed_template1_header">
			<div class="col-md-12">
				<a href="<?php echo site_url(); ?>">
					<?php echo $logo; ?>
				</a>
			</div>
		</div>

		<div class="row fed_profile_container">
			<div class="col-md-3">
				<div class="row">
					<div class="col-md-12 fed_profile_picture">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="fed_profile_full_name text-center">
									<h3 class="panel-title">
										<?php esc_attr_e( $user->get( 'display_name' ), 'frontend-dashboard' ); ?>
									</h3>
								</div>
							</div>
							<div class="panel-body">
								<?php echo fed_get_avatar( $user->ID, $user->display_name, 'img-responsive' ); ?>
							</div>

							<div class="panel-footer">

								<?php
								do_action( 'fed_show_support_button_at_user_profile', $user );

								if ( $upl_options['settings']['fed_upl_disable_desc'] === 'no' ) {
								?>
									<div class="row">
										<div class="col-md-12 fed_profile_description">
											<?php esc_attr_e( $user->get( 'description' ), 'frontend-dashboard' ); ?>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-9 fed_dashboard_container">
				<div class="row fed_dashboard_wrapper">
					<div class="col-md-12 fed_dashboard_menus">
						<ul class="nav nav-pills nav-justified list-group ">
							<?php
							$first = true;
							foreach ( $profiles as $index => $profile ) {
								if ( $menus[ $index ]['show_user_profile'] !== 'Enable' ) {
									continue;
								}
								if ( $first ) {
									$first  = false;
									$active = 'active';
								} else {
									$active = '';
								}
								?>
								<li class="fed_menu_slug <?php echo $active; ?>"
									data-menu="<?php echo $menus[ $index ]['menu_slug']; ?>"
								>
									<a href="#<?php echo $menus[ $index ]['menu_slug']; ?>">
										<span class="<?php echo $menus[ $index ]['menu_image_id']; ?>"></span>
										<?php esc_attr_e( ucwords( $menus[ $index ]['menu'] ), 'frontend-dashboard' ); ?>
									</a>
								</li>
								<?php
							}
							?>

							<li class="fed_menu_slug"
								data-menu="post"
							>
								<a href="#post">
									<span class="fa fa-newspaper-o"></span>
									Post
								</a>
							</li>
						</ul>
					</div>
					<div class="col-md-12 fed_dashboard_items">
						<?php
						$first = true;
						foreach ( $profiles as $index => $item ) {
							if ( $menus[ $index ]['show_user_profile'] !== 'Enable' ) {
								continue;
							}
							if ( $first ) {
								$first  = false;
								$active = '';
							} else {
								$active = 'hide';
							}
							?>
							<div class="panel panel-primary fed_dashboard_item <?php echo $active . ' ' . $index; ?>">
								<div class="panel-body">
									<?php
									foreach ( $item as $single_item ) {
										if ( $single_item['show_user_profile'] !== 'Enable' ) {
											continue;
										}

										if ( $single_item['input_meta'] === 'user_pass' || $single_item['input_meta'] === 'confirmation_password' ) {
											continue;
										}
										if ( in_array( $single_item['input_meta'], fed_no_update_fields(), false ) ) {
											$single_item['readonly'] = 'readonly';
										}
										if ( count( array_intersect( $user->roles, unserialize( $single_item['user_role'] ) ) ) <= 0 ) {
											continue;
										}

										?>
										<div class="row fed_dashboard_item_field">
											<div class="fed_dashboard_label_name fed_header_font_color col-md-4 text-right-md text-right-not-sm text-right-not-xs">
												<?php esc_attr_e( $single_item['label_name'], 'frontend-dashboard' ); ?>
											</div>
											<div class="col-md-8">
												<?php echo fed_process_author_details( $user, $single_item ); ?>
											</div>

										</div>
										<?php
									}
									?>
								</div>
							</div>
						<?php } ?>

						<div class="panel panel-primary fed_dashboard_item post hide">
							<div class="panel-body">
								<div class="row fed_dashboard_item_field">
									<div class="col-md-12">
										<?php
										while ( $author_posts->have_posts() ) :
											$author_posts->the_post();
											?>
											<div class="fedt_post_container">
												<div class="fed_header_font_color">
													<a href="<?php the_permalink(); ?>"
													   title="<?php the_title_attribute(); ?>">
														<?php the_title(); ?>
													</a>
												</div>
												<div class="fedt_post_excerpt">
													<?php the_excerpt(); ?>
												</div>
											</div>
										<?php
										endwhile;
										?>
									</div>

								</div>

							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<?php
	do_action( 'fed_user_profile_below' );

}

/**
 * @param $fed_user_attr
 */
function fedt_show_users_by_role( $fed_user_attr ) {
	$user_roles    = fed_get_user_roles();
	$get_user_role = $fed_user_attr->role;
	$current_url   = get_site_url() . '/' . $get_user_role . '/';
	?>
<div class="bc_fed fed_user_roles_container container <?php echo $get_user_role; ?>">

	<?php
	if ( ! array_key_exists( $get_user_role, $user_roles ) ) {
		?>
		<div class="alert alert-danger">
			<button type="button"
					class="close"
					data-dismiss="alert"
					aria-hidden="true">&times;
			</button>
			<strong>Sorry!</strong>
			The User Role " <?php echo $get_user_role; ?>" is not available on your domain,
			please
			check the spelling assigned to the
			<strong>"role"</strong>
			short-code
		</div>
		<?php
	} else {
		$get_all_users = new WP_User_Query( array( 'role' => $get_user_role ) );
		if ( ! $get_all_users->get_total() ) {
			?>
			<div class="alert alert-info">
				<button type="button"
						class="close"
						data-dismiss="alert"
						aria-hidden="true">&times;
				</button>
				<strong>Sorry!</strong>
				There are no User assigned to this User Role " <?php echo $get_user_role; ?>"
			</div>
			<?php
		} else {
			$chunks = array_chunk( $get_all_users->get_results(), 4 );
			$logo   = fedt_get_website_logo();
			foreach ( $chunks as $chunk ) {
				?>
				<div class="row fed_user_role_single">

					<div class="row fed_template1_header">
						<div class="col-md-12">
							<a href="<?php echo site_url(); ?>">
								<?php echo $logo; ?>
							</a>
						</div>
					</div>

					<?php
					foreach ( $chunk as $get_all_user ) {
						$name  = $get_all_user->get( 'display_name' );
						$email = $get_all_user->get( 'user_email' );
						?>
						<div class="col-md-3">
							<div class="panel panel-primary">
								<div class="panel-body">
									<?php echo fed_get_avatar( $email, $name ); ?>
								</div>
								<div class="panel-footer bg-primary">
									<h3 class="panel-title">
										<a target="_blank"
										   href="<?php echo get_permalink() . '?fed_user_profile=' . $get_all_user->ID; ?>">
											<?php echo $name; ?>
										</a>
									</h3>
								</div>
							</div>
						</div>

						<?php
					}
					?>
				</div>
				<?php
			}
		}
		?>
		</div>
		<?php
	}
}

/**
 * Hide Admin Menu Bar
 */
function fedt_admin_user_profile_hide_bar_tab() {
	$fed_admin_options = get_option( 'fed_admin_settings_upl_hide_admin_bar' );
	$user_role         = isset( $fed_admin_options['hide_admin_menu_bar']['role'] ) ? array_keys( $fed_admin_options['hide_admin_menu_bar']['role'] ) : array();
	$user_roles        = fed_get_user_roles();
	$fed_admin_options = get_option( 'fed_admin_setting_upl_hide_bar' );
	$array             = array(
		'form'   => array(
			'method' => '',
			'class'  => 'fed_admin_menu fed_ajax',
			'attr'   => '',
			'action' => array(
				'url'    => '',
				'action' => 'fed_admin_setting_form',
			),
			'nonce'  => array(
				'action' => '',
				'name'   => '',
			),
			'loader' => '',
		),
		'hidden' => array(
			'fed_admin_unique' => array(
				'input_type' => 'hidden',
				'user_value' => 'fed_admin_setting_upl_hide_bar',
				'input_meta' => 'fed_admin_unique',
			),
		),
		'input'  => array(
			'hide_admin_bar' => array(
				'col'     => 'col-md-12',
				'header'  => 'Hide Admin Bar to the below User Roles',
				'sub_col' => 'col-md-4',
			),
		),
	);

	foreach ( $user_roles as $key => $role ) {
		$c_value = in_array( $key, $user_role, false ) ? 'Enable' : 'Disable';
		$array['input']['hide_admin_bar']['extra']['input'][ $key ] = array(
			'input_meta'    => 'hide_menu_bar[role][' . $key . ']',
			'user_value'    => $c_value,
			'input_type'    => 'checkbox',
			'label'         => $role,
			'default_value' => 'Enable',
		);
	}
	$c_value = in_array( 'fed_disable_all_user', $user_role, false ) ? 'Enable' : 'Disable';
	$array['input']['hide_admin_bar']['extra']['input']['fed_disable_all_user'] = array(
		'input_meta'    => 'hide_menu_bar[role][fed_disable_all_user]',
		'user_value'    => $c_value,
		'input_type'    => 'checkbox',
		'label'         => __( 'Unregistered Users', 'frontend-dashboard-templates' ),
		'default_value' => 'Enable',
	);

	$new_value = apply_filters( 'fed_admin_upl_hide_bar_template', $array, $fed_admin_options );

	fed_common_simple_layout( $new_value );
}
