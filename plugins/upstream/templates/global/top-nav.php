<?php
/**
 * Top-nav template
 *
 * @package UpStream
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_archive() ) {
	$client_id   = (int) upstream_project_client_id();
	$client_logo = upstream_client_logo( $client_id );
} else {
	$client_logo = null;
}

$plugin_options = get_option( 'upstream_general' );
?>

<?php do_action( 'upstream_before_top_nav' ); ?>

<!-- top navigation -->
<div class="top_nav">
	<div class="nav_menu">
		<nav class="d-flex justify-content-between">
			<div class="nav toggle">
				<a id="menu_toggle">
					<i class="fa fa-bars"></i>
				</a>
			</div>
			<ul class="navbar-nav pb-1 pt-1 upstream-top-dropdown-menu">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
					<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
						<li>
							<a class="dropdown-item" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>">
								<i class="fa fa-home pull-right"></i>
								<?php
								echo esc_html(
									sprintf(
										// translators: %s: menu label.
										esc_html__( 'My %s', 'upstream' ),
										upstream_project_label_plural()
									)
								);
								?>
							</a>
						</li>

						<?php echo wp_kses_post( apply_filters( 'upstream_additional_nav_content', null ) ); ?>

						<li>
							<a class="dropdown-item" href="<?php echo esc_url( upstream_admin_support( $plugin_options ) ); ?>" target="_blank"
							rel="noreferrer noopener">
								<i class="fa fa-question-circle pull-right"></i><?php echo esc_html( upstream_admin_support_label( $plugin_options ) ); ?>
							</a>
						</li>

						<?php if ( is_user_logged_in() ) : ?>

							<li>
								<a class="dropdown-item" href="<?php echo esc_url( upstream_logout_url() ); ?>">
									<i class="fa fa-sign-out pull-right"></i><?php esc_html_e( 'Log Out', 'upstream' ); ?>
								</a>
							</li>

						<?php endif; ?>
					</ul>
				</li>
			</ul>
		</nav>
	</div>
</div>
<!-- /top navigation -->

<?php do_action( 'upstream_after_top_nav' ); ?>
