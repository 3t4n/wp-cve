<?php
/**
 * Displays documentation page template.
 *
 * @package SWPTLS
 */

?>
<div class="gswpts_dashboard_container" id="toplevel_page_gswpts-dashboard">
	<div class="ui segment gswpts_loader">
		<div class="ui active inverted dimmer">
			<div class="ui massive text loader"></div>
		</div>
		<p></p>
		<p></p>
		<p></p>
	</div>

	<div class="child_container mt-4 dashboard_content transition hidden">
		<div class="row heading_row mr-0 ml-0 mb-3">
			<div class="col-12 p-0 mt-2 d-flex justify-content-between align-items-center">
				<div class="d-flex justify-content-start p-0 align-items-center">
					<img src="<?php echo esc_url( SWPTLS_BASE_URL . 'assets/public/images/logo_30_30.svg' ); ?>" alt="">
					<span class="ml-2">
						<strong><?php echo esc_html( SWPTLS_PLUGIN_NAME ); ?></strong>
					</span>
					<span class="gswpts_changelogs"></span>
				</div>
				<span>
					<a class="ui violet button m-0" href="https://swptls.wppool.dev/" target="_blank">
						<?php esc_html_e( 'View Demo', 'sheetstowptable' ); ?>
						<span class="ml-2"><i class="fas fa-eye"></i></span>
					</a>
				</span>
			</div>
		</div>

		<div class="gswpts_grid_container">
			<!-- Start video section -->
			<div class="video_box dash_boxes" style="display: flex; justify-content: center; flex-direction: column">
				<?php printf( '<h2 class="p-0 m-t-0 m-b-4">%s <i>%s</i></h2', esc_html__( 'Welcome to', 'sheetstowptable' ), esc_html( SWPTLS_PLUGIN_NAME ) ); ?>
				<p>
				</p>
				<iframe style="width: 100%; border-radius: 8px;" height="370"
					src="https://www.youtube.com/embed/BW3urHKzNP0"
					title="How to install and use Google Spreadsheets to WP Table Live Sync" frameborder="0"
					allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
					allowfullscreen>
				</iframe>
			</div>
			<!-- End of video section -->

			<!-- Start help center -->
			<div class="gswpts_help_center dash_boxes">
				<div class="help_container">
					<div class="first_col">
						<span><?php esc_html_e( 'Need more help ?', 'sheetstowptable' ); ?></span>
						<p><?php esc_html_e( 'We provide professional support to all our users via our ticketing system.', 'sheetstowptable' ); ?></p>
						<a href="https://wppool.dev/google-sheets-to-wordpress-table-live-sync/" target="_blank"><?php esc_html_e( 'Get Help', 'sheetstowptable' ); ?></a>
					</div>
					<div class="second_col">
						<img src="<?php echo esc_url( SWPTLS_BASE_URL . 'assets/public/images/need_help.svg' ); ?>"
							alt="need-help">
					</div>
				</div>
			</div>
			<!-- End of help center -->

			<?php if ( ! swptls()->helpers->check_pro_plugin_exists() ) { ?>
			<!-- Start Get Pro section -->
			<div class="gswpts_pro_box dash_boxes">
				<div class="col-12 p-0 pt-4 m-0 d-flex">
					<h2 class="pro_title"><?php esc_html_e( 'Get Pro ✨', 'sheetstowptable' ); ?></h2>
					<h5>
						<?php esc_html_e( 'Get the most out of the plugin. Go Pro!', 'sheetstowptable' ); ?>
					</h5>
					<div class="col-12 d-flex p-0 info_wrapper">
						<div class="col-md-12 col-lg-6 p-0">
							<br>
							<ul class="p-0 m-0">
								<li class="d-flex align-items-center mb-3">
									<span class="mr-3">
										<i class="fas fa-check-square" style="color: #2ecc40; font-size: 18px"></i>
									</span>
									<span
										style="font-size: 15px; margin-top: -5px;"><?php esc_html_e( 'Responsive table. The plugin allows collapsing on mobile and tablet screens', 'sheetstowptable' ); ?></span>
								</li>

								<li class="d-flex align-items-center mb-3">
									<span class="mr-3">
										<i class="fas fa-check-square" style="color: #2ecc40; font-size: 18px"></i>
									</span>
									<span
										style="font-size: 15px; margin-top: -5px;"><?php esc_html_e( 'Data Caching feature cache the sheet data & therefore the table will load faster.', 'sheetstowptable' ); ?></span>
								</li>

								<li class="d-flex align-items-center mb-3">
									<span class="mr-3">
										<i class="fas fa-check-square" style="color: #2ecc40; font-size: 18px"></i>
									</span>
									<span
										style="font-size: 15px; margin-top: -5px;"><?php esc_html_e( 'Link Support to import links from sheet. All the URL\'s will be shown as link in table.', 'sheetstowptable' ); ?></span>
								</li>

								<li class="d-flex align-items-center mb-3">
									<span class="mr-3">
										<i class="fas fa-check-square" style="color: #2ecc40; font-size: 18px"></i>
									</span>
									<span
										style="font-size: 15px; margin-top: -5px;"><?php esc_html_e( 'Table Styles. Pre-built amazing table styles. Each styles is different from one another.', 'sheetstowptable' ); ?></span>
								</li>

								<li class="d-flex align-items-center mb-3">
									<span class="mr-3">
										<i class="fas fa-check-square" style="color: #2ecc40; font-size: 18px"></i>
									</span>
									<span
										style="font-size: 15px; margin-top: -5px;"><?php esc_html_e( 'Hide your google sheet table column on desktop screen OR mobile screens.', 'sheetstowptable' ); ?></span>
								</li>

								<li class="d-flex align-items-center mb-3">
									<span class="mr-3">
										<i class="fas fa-check-square" style="color: #2ecc40; font-size: 18px"></i>
									</span>
									<span
										style="font-size: 15px; margin-top: -5px;"><?php esc_html_e( 'Hide Row. Hide your google sheet table rows based on your custom row selection.', 'sheetstowptable' ); ?></span>
								</li>

								<li class="d-flex align-items-center mb-3">
									<span class="mr-3">
										<i class="fas fa-check-square" style="color: #2ecc40; font-size: 18px"></i>
									</span>
									<span
										style="font-size: 15px; margin-top: -5px;"><?php esc_html_e( 'Unlimited Row Sync. Fetch unlimited rows from Google spreadsheet', 'sheetstowptable' ); ?></span>
								</li>

								<li class="d-flex align-items-center mb-3">
									<span class="mr-3">
										<i class="fas fa-check-square" style="color: #2ecc40; font-size: 18px"></i>
									</span>
									<span
										style="font-size: 15px; margin-top: -5px;"><?php esc_html_e( 'And much more.', 'sheetstowptable' ); ?> 
										<a href="https://wordpress.org/plugins/sheets-to-wp-table-live-sync/" target="_blank"><?php esc_html_e( 'All Pro features', 'sheetstowptable' ); ?></a></span>
								</li>
							</ul>

							<div class="col-12 pl-0 pb-5 pt-4">
								<a class="ui get_pro_btn  m-0" href="https://wppool.dev/sheets-to-wp-table-live-sync/"
									target="blank">
									<?php esc_html_e( 'Buy Now', 'sheetstowptable' ); ?>
								</a>
							</div>
						</div>
						<div class="col-md-12 col-lg-6 p-0 m-0 d-flex align-items-center premium_svg">
							<img src="<?php echo esc_url( SWPTLS_BASE_URL . 'assets/public/images/premium.svg' ); ?>"
								alt="premium">
						</div>
					</div>
				</div>
			</div>
			<!-- End of Get Pro section -->
			<?php } ?>

			<!-- Made by section  -->
			<div class="made_by">
				<div class="made_by_container">
					<img src="<?php echo esc_url( SWPTLS_BASE_URL . 'assets/public/images/made-by.svg' ); ?>" alt="">
					<div class="extras">
						<span>
							<a href="https://wppool.dev/" target="_blank">wppool.dev</a>
							<span>|</span>
						</span>
						<span>
							<a href="https://www.youtube.com/watch?v=_LWcaErh8jw&list=PLd6WEu38CQSyY-1rzShSfsHn4ZVmiGNLP"
								target="_blank"><?php esc_html_e( 'Documentation', 'sheetstowptable' ); ?></a>
							<span>|</span>
						</span>
						<span>
							<a href="https://wppool.dev/google-sheets-to-wordpress-table-live-sync/"
								target="_blank"><?php esc_html_e( 'Support Center', 'sheetstowptable' ); ?></a>
						</span>
					</div>
				</div>
			</div>
			<!-- End of made by section  -->
		</div>
	</div>
</div>
