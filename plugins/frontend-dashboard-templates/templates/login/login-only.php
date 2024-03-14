<?php
/**
 * Logged in User form
 */

if ( isset( $_GET['action'], $_GET['key'], $_GET['login'] ) && ( 'fed_reset' === $_GET['action'] ) ) {
	$details = fed_reset_password_only();
	$type    = 'reset_password';
} else {
	$details = fed_login_only();
	$type    = 'login';
}
$registration = fed_get_registration_url();
$forgot       = fed_get_forgot_password_url();

do_action( 'fed_before_login_only_form' );
?>
	<div class="bc_fed container fed_login_container">
		<?php echo fed_loader(); ?>
		<div class="row flex-center">
			<div class="col-md-6">
				<div class="flex-center">
					<?php
					// phpcs:ignore
					echo fedt_get_website_logo(); ?>
				</div>
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">
							<?php
							// translators: %s: Menu Item.
							printf( esc_attr__( '%s', 'frontend-dashboard-templates' ), $details['menu']['name'] );
							?>
						</h3>
					</div>
					<div class="panel-body">
						<div class="fed_tab_content"
								data-id="<?php echo esc_attr( $details['menu']['id'] ); ?>">
							<form method="post"
									class="fed_form_post"
							>
								<?php
								$contents = $details['content'];
								uasort( $contents, 'fed_sort_by_order' );
								foreach ( $contents as $content ) {
									?>
									<div class="form-group">
										<?php
										//phpcs:ignore
										echo fed_show_form_label( $content );
										?>
										<?php
										//phpcs:ignore
										echo $content['input'];
										?>
									</div>
									<?php
								}
								?>
								<div class="form-group">
									<div class="text-center">
										<input type="hidden" name="submit" value="<?php echo esc_attr( $type ); ?>"/>
										<button class="btn btn-primary" type="submit">
											<?php esc_attr_e( $details['button'], 'frontend-dashboard-templates' ); ?>
										</button>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12 padd_top_20 text-center">
										<?php if ( $registration ) { ?>
											<a href="<?php echo esc_url( $registration ); ?>">
												<?php
												esc_attr_e(
													'Create an account',
													'frontend-dashboard'
												);
												?>
											</a> |
										<?php } ?>
										<?php
										if ( $forgot ) {
											?>
											<a href="<?php echo esc_url( $forgot ); ?>">
												<?php esc_attr_e( 'Lost Password?', 'frontend-dashboard-templates' ); ?>
											</a>
											<?php
										}
										?>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
do_action( 'fed_after_login_only_form' );
