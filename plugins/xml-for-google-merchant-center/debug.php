<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
function xfgmc_debug_page() {
	wp_clean_plugins_cache();
	wp_clean_update_cache();
	add_filter( 'pre_site_transient_update_plugins', '__return_null' );
	wp_update_plugins();
	remove_filter( 'pre_site_transient_update_plugins', '__return_null' );
	if ( isset( $_REQUEST['xfgmc_submit_debug_page'] ) ) {
		if ( ! empty( $_POST ) && check_admin_referer( 'xfgmc_nonce_action', 'xfgmc_nonce_field' ) ) {
			if ( isset( $_POST['xfgmc_keeplogs'] ) ) {
				xfgmc_optionUPD( 'xfgmc_keeplogs', sanitize_text_field( $_POST['xfgmc_keeplogs'] ) );
			} else {
				xfgmc_optionUPD( 'xfgmc_keeplogs', '0' );
			}
			if ( isset( $_POST['xfgmc_disable_notices'] ) ) {
				xfgmc_optionUPD( 'xfgmc_disable_notices', sanitize_text_field( $_POST['xfgmc_disable_notices'] ) );
			} else {
				xfgmc_optionUPD( 'xfgmc_disable_notices', '0' );
			}
			if ( isset( $_POST['xfgmc_enable_five_min'] ) ) {
				xfgmc_optionUPD( 'xfgmc_enable_five_min', sanitize_text_field( $_POST['xfgmc_enable_five_min'] ) );
			} else {
				xfgmc_optionUPD( 'xfgmc_enable_five_min', '0' );
			}
		}
	}
	$xfgmc_keeplogs = xfgmc_optionGET( 'xfgmc_keeplogs' );
	$xfgmc_disable_notices = xfgmc_optionGET( 'xfgmc_disable_notices' );
	$xfgmc_enable_five_min = xfgmc_optionGET( 'xfgmc_enable_five_min' );
	?>
	<div class="wrap">
		<h1>
			<?php _e( 'Debug page', 'xml-for-google-merchant-center' ); ?> v.
			<?php echo xfgmc_optionGET( 'xfgmc_version' ); ?>
		</h1>
		<div id="dashboard-widgets-wrap">
			<div id="dashboard-widgets" class="metabox-holder">
				<div id="postbox-container-1" class="postbox-container">
					<div class="meta-box-sortables">
						<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
							<div class="postbox">
								<h2 class="hndle">
									<?php _e( 'Logs', 'xml-for-google-merchant-center' ); ?>
								</h2>
								<div class="inside">
									<p>
										<?php if ( $xfgmc_keeplogs === 'on' ) {
											$upload_dir = wp_get_upload_dir();
											echo '<strong>' . __( "Log-file here", 'xfgmc' ) . ':</strong><br /><a href="' . $upload_dir['baseurl'] . '/xfgmc/plugin.log" target="_blank">' . $upload_dir['basedir'] . '/xfgmc/plugin.log</a>';
										} ?>
									</p>
									<table class="form-table">
										<tbody>
											<tr>
												<th scope="row"><label for="xfgmc_keeplogs">
														<?php _e( 'Keep logs', 'xml-for-google-merchant-center' ); ?>
													</label><br />
													<input class="button" id="xfgmc_submit_clear_logs" type="submit"
														name="xfgmc_submit_clear_logs"
														value="<?php _e( 'Clear logs', 'xml-for-google-merchant-center' ); ?>" />
												</th>
												<td class="overalldesc">
													<input type="checkbox" name="xfgmc_keeplogs" id="xfgmc_keeplogs" <?php checked( $xfgmc_keeplogs, 'on' ); ?> /><br />
													<span class="description">
														<?php _e( 'Do not check this box if you are not a developer', 'xml-for-google-merchant-center' ); ?>!
													</span>
												</td>
											</tr>
											<tr>
												<th scope="row"><label for="xfgmc_disable_notices">
														<?php _e( 'Disable notices', 'xml-for-google-merchant-center' ); ?>
													</label></th>
												<td class="overalldesc">
													<input type="checkbox" name="xfgmc_disable_notices"
														id="xfgmc_disable_notices" <?php checked( $xfgmc_disable_notices, 'on' ); ?> /><br />
													<span class="description">
														<?php _e( 'Disable notices about XML-construct', 'xml-for-google-merchant-center' ); ?>!
													</span>
												</td>
											</tr>
											<tr>
												<th scope="row"><label for="xfgmc_enable_five_min">
														<?php _e( 'Enable', 'xml-for-google-merchant-center' ); ?> five_min
													</label></th>
												<td class="overalldesc">
													<input type="checkbox" name="xfgmc_enable_five_min"
														id="xfgmc_enable_five_min" <?php checked( $xfgmc_enable_five_min, 'on' ); ?> /><br />
													<span class="description">
														<?php _e( 'Enable the five minute interval for CRON', 'xml-for-google-merchant-center' ); ?>
													</span>
												</td>
											</tr>
											<tr>
												<th scope="row"><label for="button-primary"></label></th>
												<td class="overalldesc"></td>
											</tr>
											<tr>
												<th scope="row"><label for="button-primary"></label></th>
												<td class="overalldesc">
													<?php wp_nonce_field( 'xfgmc_nonce_action', 'xfgmc_nonce_field' ); ?><input
														id="button-primary" class="button-primary" type="submit"
														name="xfgmc_submit_debug_page"
														value="<?php _e( 'Save', 'xml-for-google-merchant-center' ); ?>" /><br />
													<span class="description">
														<?php _e( 'Click to save the settings', 'xml-for-google-merchant-center' ); ?>
													</span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div id="postbox-container-2" class="postbox-container">
					<div class="meta-box-sortables">
						<!-- div class="postbox">
	  <h2 class="hndle"><?php _e( 'Reset plugin settings', 'xml-for-google-merchant-center' ); ?></h2>
	  <div class="inside">		
		<p><?php _e( 'Reset plugin settings can be useful in the event of a problem', 'xml-for-google-merchant-center' ); ?>.</p>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
			<?php wp_nonce_field( 'xfgmc_nonce_action_reset', 'xfgmc_nonce_field_reset' ); ?><input class="button-primary" type="submit" name="xfgmc_submit_reset" value="<?php _e( 'Reset plugin settings', 'xml-for-google-merchant-center' ); ?>" />	 
		</form>
	  </div>
	 </div -->
						<div class="postbox">
							<h2 class="hndle">
								<?php _e( 'Request simulation', 'xml-for-google-merchant-center' ); ?>
							</h2>
							<div class="inside">
								<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post"
									enctype="multipart/form-data">
									<?php $resust_simulated = '';
									$resust_report = '';
									if ( isset( $_POST['xfgmc_num_feed'] ) ) {
										$xfgc_num_feed = sanitize_text_field( $_POST['xfgmc_num_feed'] );
									} else {
										$xfgc_num_feed = '1';
									}
									if ( isset( $_POST['xfgmc_simulated_post_id'] ) ) {
										$xfgmc_simulated_post_id = sanitize_text_field( $_POST['xfgmc_simulated_post_id'] );
									} else {
										$xfgmc_simulated_post_id = '';
									}
									if ( isset( $_REQUEST['xfgmc_submit_simulated'] ) ) {
										if ( ! empty( $_POST ) && check_admin_referer( 'xfgmc_nonce_action_simulated', 'xfgmc_nonce_field_simulated' ) ) {
											$post_id = (int) $xfgmc_simulated_post_id;

											$result_get_unit_obj = new XFGMC_Get_Unit( $post_id, $xfgc_num_feed );
											$simulated_result_xml = $result_get_unit_obj->get_result();

											$resust_report_arr = $result_get_unit_obj->get_skip_reasons_arr();

											if ( empty( $resust_report_arr ) ) {
												$resust_report = 'Всё штатно';
											} else {
												foreach ( $result_get_unit_obj->get_skip_reasons_arr() as $value ) {
													$resust_report .= $value . PHP_EOL;
												}
											}
											$resust_simulated = $simulated_result_xml;
										}
									} ?>
									<table class="form-table">
										<tbody>
											<tr>
												<th scope="row"><label for="xfgmc_simulated_post_id">postId</label></th>
												<td class="overalldesc">
													<input type="number" min="1" name="xfgmc_simulated_post_id"
														value="<?php echo $xfgmc_simulated_post_id; ?>">
												</td>
											</tr>
											<tr>
												<th scope="row"><label for="xfgmc_enable_five_min">numFeed</label></th>
												<td class="overalldesc">
													<select style="width: 100%" name="xfgmc_num_feed" id="xfgmc_num_feed">
														<?php if ( is_multisite() ) {
															$cur_blog_id = get_current_blog_id();
														} else {
															$cur_blog_id = '0';
														}
														$xfgmc_settings_arr = xfgmc_optionGET( 'xfgmc_settings_arr' );
														$xfgmc_settings_arr_keys_arr = array_keys( $xfgmc_settings_arr );
														for ( $i = 0; $i < count( $xfgmc_settings_arr_keys_arr ); $i++ ) :
															$numFeed = (string) $xfgmc_settings_arr_keys_arr[ $i ];
															if ( $xfgmc_settings_arr[ $numFeed ]['xfgmc_feed_assignment'] === '' ) {
																$feed_assignment = '';
															} else {
																$feed_assignment = ' (' . $xfgmc_settings_arr[ $numFeed ]['xfgmc_feed_assignment'] . ')';
															} ?>
															<option value="<?php echo $numFeed; ?>" <?php selected( $xfgc_num_feed, $numFeed ); ?>><?php _e( 'Feed', 'xml-for-google-merchant-center' ); ?> 		<?php echo $numFeed; ?>:
																feed-xml-<?php echo $cur_blog_id; ?>.xml<?php echo $feed_assignment; ?></option>
														<?php endfor; ?>
													</select>
												</td>
											</tr>
											<tr>
												<th scope="row" colspan="2"><textarea rows="4"
														style="width: 100%;"><?php echo htmlspecialchars( $resust_report ); ?></textarea>
												</th>
											</tr>
											<tr>
												<th scope="row" colspan="2"><textarea rows="16"
														style="width: 100%;"><?php echo htmlspecialchars( $resust_simulated ); ?></textarea>
												</th>
											</tr>
										</tbody>
									</table>
									<?php wp_nonce_field( 'xfgmc_nonce_action_simulated', 'xfgmc_nonce_field_simulated' ); ?><input
										class="button-primary" type="submit" name="xfgmc_submit_simulated"
										value="<?php _e( 'Simulated', 'xml-for-google-merchant-center' ); ?>" />
								</form>
							</div>
						</div>
					</div>
				</div>
				<div id="postbox-container-3" class="postbox-container">
					<div class="meta-box-sortables">
						<div class="postbox">
							<h2 class="hndle">
								<?php _e( 'Possible problems', 'xml-for-google-merchant-center' ); ?>
							</h2>
							<div class="inside">
								<?php
								$possible_problems_arr = xfgmc_possible_problems_list();
								if ( $possible_problems_arr[1] > 0 ) { // $possibleProblemsCount > 0) {
									echo '<ol>' . $possible_problems_arr[0] . '</ol>';
								} else {
									echo '<p>' . __( 'Self-diagnosis functions did not reveal potential problems', 'xml-for-google-merchant-center' ) . '.</p>';
								}
								?>
							</div>
						</div>
						<div class="postbox">
							<h2 class="hndle">
								<?php _e( 'Sandbox', 'xml-for-google-merchant-center' ); ?>
							</h2>
							<div class="inside">
								<?php
								require_once plugin_dir_path( __FILE__ ) . '/sandbox.php';
								try {
									xfgmc_run_sandbox();
								} catch (Exception $e) {
									echo 'Exception: ', $e->getMessage(), "\n";
								}
								?>
							</div>
						</div>
					</div>
				</div>
				<div id="postbox-container-2" class="postbox-container">
					<div class="meta-box-sortables">
						<?php do_action( 'xfgmc_before_support_project' ); ?>

						<div class="postbox">
							<h2 class="hndle">
								<?php _e( 'Send data about the work of the plugin', 'xml-for-google-merchant-center' ); ?>
							</h2>
							<div class="inside">
								<p>
									<?php _e( 'Sending statistics you help make the plugin even better', 'xml-for-google-merchant-center' ); ?>!
									<?php _e( 'The following data will be transferred', 'xml-for-google-merchant-center' ); ?>:
								</p>
								<ul class="xfgmc_ul">
									<li>
										<?php _e( 'URL XML-feed', 'xml-for-google-merchant-center' ); ?>
									</li>
									<li>
										<?php _e( 'File generation status', 'xml-for-google-merchant-center' ); ?>
									</li>
									<li>
										<?php _e( 'Is the multisite mode enabled?', 'xml-for-google-merchant-center' ); ?>
									</li>
								</ul>
								<p>
									<?php _e( 'Did my plugin help you upload your products to the Google Merchant Center', 'xml-for-google-merchant-center' ); ?>?
								</p>
								<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post"
									enctype="multipart/form-data">
									<p>
										<input type="radio" name="xfgmc_its_ok" value="yes">
										<?php _e( 'Yes', 'xml-for-google-merchant-center' ); ?><br />
										<input type="radio" name="xfgmc_its_ok" value="no">
										<?php _e( 'No', 'xml-for-google-merchant-center' ); ?><br />
									</p>
									<p>
										<?php _e( "If you don't mind to be contacted in case of problems, please enter your email address", "xfgmc" ); ?>.
									</p>
									<p><input type="email" name="xfgmc_email"></p>
									<p>
										<?php _e( "Your message", "xfgmc" ); ?>:
									</p>
									<p><textarea rows="5" cols="40" name="xfgmc_message"
											placeholder="<?php _e( 'Enter your text to send me a message (You can write me in Russian or English). I check my email several times a day', 'xml-for-google-merchant-center' ); ?>"></textarea>
									</p>
									<?php wp_nonce_field( 'xfgmc_nonce_action_send_stat', 'xfgmc_nonce_field_send_stat' ); ?><input
										class="button-primary" type="submit" name="xfgmc_submit_send_stat"
										value="<?php _e( 'Send data', 'xml-for-google-merchant-center' ); ?>" />
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
} /* end функция страницы debug-а xfgmc_debug_page */