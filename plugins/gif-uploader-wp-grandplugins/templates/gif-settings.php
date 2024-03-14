<?php defined( 'ABSPATH' ) || exit(); ?>

<div class="col-md-12">
	<!-- === GIF Select Section === -->
	<div class="gif-settings-container">
		<div class="col-md-6 select-gif-btn-section">
			<h3 class="my-3"><?php esc_html_e( 'Settings', 'wp-gif-editor' ); ?></h3>
		</div>
		<div class="gpls-wgr-wp-gif-editor-gif-main-settings-settings-wrapper bg-white shadow-lg px-4 py-3 me-5">
			<div class="container-fluid">
				<div class="row">
					<div class="col">
						<div class="settings-list row form-table">
							<!-- Section -->
							<div class="tab-section-wrapper col-12 my-3 p-3 bg-white shadow-lg">
								<h4><?php esc_html_e( 'Optimization', 'wp-gif-uploader' ); ?></h4>
								<h6 class="notice notice-success p-4" style="display:flex;align-items:center;">
									<?php esc_html_e( 'This feature is part of ', 'wp-gif-editor' ); ?><?php $core->pro_btn( '', 'Pro' ); ?> <span class="ms-1"><?php esc_html_e( 'Version', 'wp-gif-editor' ); ?></span>
								</h6>
								<div class="container-fluid border mt-4">

									<!-- Section Fields -->
									<div class="settings-group my-4 py-4 col-md-12  ">
										<div class="row">

											<!-- Field Label -->
											<div class="col-md-3 d-flex align-items-center mb-4 mb-md-0">
												<h6 class=""><?php esc_html_e( 'First Frame image', 'wp-gif-uploader' ); ?></h6>
											</div>
											<!-- Field input -->
											<div class="col-md-9 d-flex align-items-center">
												<div class="input w-100">
													<!-- Input Heading -->

													<!-- Input  -->
													<input  type="checkbox" class="disabled" disabled>

													<!-- Input Suffix -->
													<?php esc_html_e( 'Use GIF\'s first frame image at page load, then load the GIF after the page is loaded.', 'wp-gif-uploader' ); ?>
													<!-- Input Footer -->
												</div>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
