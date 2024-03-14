<?php
/**
 * Logged in User form
 */

$details = fed_register_only();
$login   = fed_get_login_url();
do_action( 'fed_before_register_only_form' );
?>
	<div class="bc_fed container fed_login_container">
		<?php
		//phpcs:ignore
		echo fed_loader(); ?>
		<div class="row flex-center">
			<div class="col-md-6">
				<div class="flex-center">
					<?php
					// phpcs:ignore
					echo fedt_get_website_logo(); ?>
				</div>
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php esc_attr_e( $details['menu']['name'],
								'frontend-dashboard-templates' ); ?></h3>
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

										do_action( 'fed_register_below_form_field', $content );
										?>
									</div>
									<?php
								}
								?>
								<div class="row">
									<div class="col-md-12 flex_center">
										<input type="hidden"
												name="submit"
												value="register"/>
										<button class="btn btn-primary" type="submit">
											<?php
											esc_attr_e( $details['button'], 'frontend-dashboard-templates' );
											?>
										</button>
									</div>

									<?php if ( $login ) { ?>
										<div class="col-md-12 padd_top_20 text-center">
											<a href="<?php echo esc_url( $login ); ?>">
												<?php
												esc_attr_e(
													'Already have an account?', 'frontend-dashboard'
												);
												?>
											</a>
										</div>
									<?php } ?>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
do_action( 'fed_after_register_only_form' );