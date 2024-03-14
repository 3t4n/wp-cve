<?php
/**
 * Dashboard Page - Template 1
 *
 * @package frontend-dashboard
 */

//phpcs:ignore
$dashboard_container = new FED_Routes( $_REQUEST );
$menu                = $dashboard_container->setDashboardMenuQuery();

$is_mobile = fed_get_menu_mobile_attributes();
$column    = 'col-md-12';
if ( is_active_sidebar( 'fed_dashboard_right_sidebar' ) ) {
	$column = 'col-md-9';
}
$logo = fedt_get_website_logo();

do_action( 'fed_before_dashboard_container' );
?>
	<div class="bc_fed fed_dashboard_container">
		<?php
		//phpcs:ignore
		echo fed_loader(); ?>
		<?php do_action( 'fed_inside_dashboard_container_top' ); ?>
		<?php
		if ( ! $menu instanceof WP_Error ) {
			do_action( 'fed_dashboard_content_outside_top' );
			//phpcs:ignore
			do_action( 'fed_dashboard_content_outside_top_' . fed_get_data( 'menu_request.menu_slug', $menu ) );
			?>
			<div class="row fed_dashboard_wrapper">
				<div class="col-md-2">
					<div class=" fed_dashboard_menus fed_template1">
						<div class="custom-collapse fed_menu_items">
							<button class="bg_secondary collapse-toggle visible-xs visible-sm  <?php echo esc_attr( $is_mobile['d'] ); ?>"
									type="button"
									data-toggle="collapse" data-parent="custom-collapse"
									data-target="#fed_template1_template"
									aria-expanded="<?php echo esc_attr( $is_mobile['expand'] ); ?>">
								<span class=""><i class="fa fa-bars"></i>Menu</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<div class="list-group fed_menu_ul collapse <?php echo esc_attr( $is_mobile['in'] ); ?>"
									id="fed_template1_template">
								<div class="list-group-item fedt_profile_picture" data-behavior="on-hover">
									<div class="text-center menu_image_container">
										<a href="<?php echo esc_url( get_author_posts_url( get_current_user_id() ) ); ?>">
											<div class="menu_image" data-url="<?php echo esc_url( add_query_arg( array(
												'fed_nonce' => wp_create_nonce( 'fed_nonce' ),
											), fed_get_ajax_form_action( 'fedt_upload_profile_image' ) ) ); ?>">
												<div class="fed_profile_image_edit fed_hide"
														data-behavior="on-hover-show">
													<i class="fa fa-pencil"></i>
													Edit
												</div>

												<div class="fedt_profile_image">
													<?php
													//phpcs:ignore
													echo fed_get_avatar(
														get_current_user_id(), '',
														'image-responsive img-circle'
													);
													?>
												</div>
											</div>
											<div class="user_name text-uppercase">
												<?php echo esc_attr( fed_get_display_name_by_id( get_current_user_id() ) ); ?>
											</div>
										</a>
									</div>
								</div>
								<div class="fed_frontend_dashboard_menu">
									<nav>
										<?php

										fed_display_dashboard_menu( $menu );

										fed_get_collapse_menu()
										?>
									</nav>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-10">
					<div class="fed_dashboard_items">
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-7">
								<?php echo wp_kses_post( $logo ); ?>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-5">
								<div class="pull-right"></div>
							</div>
						</div>
						<div class="row">
							<div class="<?php echo esc_attr( $column ); ?>">
								<?php
								//phpcs:ignore
								echo fed_show_alert( 'fed_dashboard_top_message' );

								do_action( 'fed_dashboard_content_top' );
								//phpcs:ignore
								do_action( 'fed_dashboard_content_top_' . fed_get_data( 'menu_request.menu_slug',
										$menu ) );
								$dashboard_container->getDashboardContent( $menu );
								do_action( 'fed_dashboard_content_bottom' );
								//phpcs:ignore
								do_action( 'fed_dashboard_content_bottom_' . fed_get_data( 'menu_request.menu_slug',
										$menu ) );
								?>
							</div>
							<?php if ( is_active_sidebar( 'fed_dashboard_right_sidebar' ) ) { ?>
								<div class="col-md-3 fed_ads">
									<div class="bc_fed widget-area" role="complementary">
										<?php dynamic_sidebar( 'fed_dashboard_right_sidebar' ); ?>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<?php
			do_action( 'fed_dashboard_content_outside_bottom' );
			//phpcs:ignore
			do_action( 'fed_dashboard_content_outside_bottom_' . fed_get_data( 'menu_request.menu_slug', $menu ) );
		}
		if ( $menu instanceof WP_Error ) {
			?>
			<div class="row fed_dashboard_wrapper fed_error">
				<?php fed_get_403_error_page(); ?>
			</div>
			<?php
		}
		?>
	</div>
<?php
do_action( 'fed_after_dashboard_container' );
