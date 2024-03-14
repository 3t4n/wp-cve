<?php
defined( 'ABSPATH' ) || exit;

$post_id         = $args['post_id'];
$plugin_info     = $args['plugin_info'];
$core            = $args['core'];
$cpt             = $args['cpt'];
$current_time    = ( current_datetime()->getTimestamp() );
$timer_settings  = $cpt->settings->get_settings( null, $post_id );
$target_time     = $timer_settings['timer_interval'];
$timezone_string = $timer_settings['timer_timezone'];
$target_timezone = $cpt->adjust_timezone( $timezone_string );
try {
	$arrival_time = \DateTime::createFromFormat( 'Y-m-d\TH:i', $target_time, $target_timezone )->getTimestamp();
} catch ( \Exception $e ) {
	$arrival_time = $current_time;
}
?>


<div class="timer-configurations-metabox">
	<div class="colors-wrapper col-12 my-2 p-3 bg-white shadow-sm">
		<!-- Timer Interval Fields -->
		<h5><?php printf( esc_html__( 'Timer Interval', '%s' ), $plugin_info['text_domain'] ); ?></h5>
		<?php $cpt->settings->print_fields( 'general', 'interval' ); ?>
		<!-- Countdown Timer -->
		<h5 class="mt-3"><?php printf( esc_html__( 'CountDown Timer', '%s' ), $plugin_info['text_domain'] ); ?></h5>
		<div class="container-fluid">
			<div class="row p-3">
				<div class="col">
					<div class="settings-list row">
						<div class="tab-section-wrapper col-12 my-3 p-3 bg-white shadow-lg">

							<!-- CountDowm Timer -->
							<div class="countdown-timer-preview-container">
								<div class="container p-3">
									<div id="<?php echo esc_attr( $plugin_info['classes_prefix'] . '-countdown-timer-' . $post_id ); ?>" class="<?php echo esc_attr( $plugin_info['classes_prefix'] . '-countdown-timer-container' ); ?>">
										<div class="<?php echo esc_attr( $plugin_info['classes_prefix'] . '-countdown-timer-wrapper' ); ?>">
											<div id="<?php echo esc_attr( $plugin_info['classes_prefix'] . '-flipdown' ); ?>"
												class="flipdown <?php echo esc_attr( $plugin_info['classes_prefix'] . '-flipper' ); ?> flipper-dark <?php echo esc_attr( $plugin_info['classes_prefix'] . '-flipdown' ); ?>"
												data-datetime="<?php echo esc_attr( $arrival_time ); ?>"
												data-template="ddd|HH|ii|ss"
												data-labels="Days|Hours|Minutes|Seconds"
												data-now="<?php echo esc_attr( $current_time ); ?>"
											>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Timer Shortcode -->
							<div class="countdown-timer-shortcode-container mb-3 py-3 px-3 shadow-sm rounded bg-white">
								<div class="container d-flex justify-content-center align-items-center">
									<strong class="me-3"><?php esc_html_e( 'Timer Shortcode:', 'simple-countdown' ); ?></strong>
									<code class="<?php echo esc_attr( $plugin_info['classes_prefix'] . '-countdown-timer-shortcode' ); ?>">[<?php echo esc_attr( str_replace( '-', '_', $plugin_info['classes_prefix'] . '-countdown' ) ); ?> id="<?php echo esc_attr( $post_id ); ?>"]</code>
									<?php $cpt::clipboard_icon( '.' . $plugin_info['classes_prefix'] . '-countdown-timer-shortcode' ); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Timer Colors -->
		<h5 class="mt-3"><span class="me-3"><?php printf( esc_html__( 'Timer Colors', '%s' ), $plugin_info['text_domain'] ); ?></span><?php $core->pro_btn(); ?></h5>
		<div class="container-fluid">
			<div class="row">
				<div class="col">
					<div class="settings-list row">
						<div class="tab-section-wrapper col-12 my-3 p-3 bg-white shadow-lg">
							<div class="container-fluid border mt-4 bg-light opacity-50">
								<div class="row">
									<!-- Days -->
									<div class="border settings-group my-4 py-4 col-md-3">
										<h4><?php esc_html_e( 'Days', 'simple-countdown' ); ?></h4>
										<div class="countgroup-wrapper subtitle">
											<!-- Title Color -->
											<div class="my-5 row countdown-rotor-color">
												<div class="col-4">
													<h6><?php esc_html_e( 'Title Color', 'simple-countdown' ); ?></h6>
												</div>
												<div class="col-8">
													<label>
														<?php $cpt->settings->print_field( 'colors_days_title_color', false ); ?>
													</label>
												</div>
											</div>
											<!-- Front Color -->
											<div class="my-5 row countdown-rotor-color">
												<div class="col-4">
													<h6><?php esc_html_e( 'Front Color', 'simple-countdown' ); ?></h6>
												</div>
												<div class="col-8">
													<label>
														<?php $cpt->settings->print_field( 'colors_days_counter_front_color', false ); ?>
													</label>
												</div>
											</div>
											<!-- Back Color -->
											<div class="my-5 row countdown-rotor-color">
												<div class="col-4">
													<h6><?php esc_html_e( 'Back Color', 'simple-countdown' ); ?></h6>
												</div>
												<div class="col-8">
													<label>
														<?php $cpt->settings->print_field( 'colors_days_counter_back_color', false ); ?>
													</label>
												</div>
											</div>
											<!-- Divider Color -->
											<div class="my-5 row countdown-rotor-color">
												<div class="col-4">
													<h6><?php esc_html_e( 'Divider Color', 'simple-countdown' ); ?></h6>
												</div>
												<div class="col-8">
													<label>
														<?php $cpt->settings->print_field( 'colors_days_divider_color', false ); ?>
													</label>
												</div>
											</div>
										</div>
									</div>
									<!-- Hours -->
									<div class="border settings-group my-4 py-4 col-md-3">
										<h4><?php esc_html_e( 'Hours', 'simple-countdown' ); ?></h4>
										<div class="countgroup-wrapper subtitle">
											<!-- Title Color -->
											<div class="my-5 row countdown-rotor-color">
												<div class="col-4">
													<h6><?php esc_html_e( 'Title Color', 'simple-countdown' ); ?></h6>
												</div>
												<div class="col-8">
													<label>
														<?php $cpt->settings->print_field( 'colors_hours_title_color', false ); ?>
													</label>
												</div>
											</div>
											<!-- Front Color -->
											<div class="my-5 row countdown-rotor-color">
												<div class="col-4">
													<h6><?php esc_html_e( 'Front Color', 'simple-countdown' ); ?></h6>
												</div>
												<div class="col-8">
													<label>
														<?php $cpt->settings->print_field( 'colors_hours_counter_front_color', false ); ?>
													</label>
												</div>
											</div>
											<!-- Back Color -->
											<div class="my-5 row countdown-rotor-color">
												<div class="col-4">
													<h6><?php esc_html_e( 'Back Color', 'simple-countdown' ); ?></h6>
												</div>
												<div class="col-8">
													<label>
														<?php $cpt->settings->print_field( 'colors_hours_counter_back_color', false ); ?>
													</label>
												</div>
											</div>
											<!-- Divider Color -->
											<div class="my-5 row countdown-rotor-color">
												<div class="col-4">
													<h6><?php esc_html_e( 'Divider Color', 'simple-countdown' ); ?></h6>
												</div>
												<div class="col-8">
													<label>
														<?php $cpt->settings->print_field( 'colors_hours_divider_color', false ); ?>
													</label>
												</div>
											</div>
										</div>
									</div>
									<!-- Minutes -->
									<div class="border settings-group my-4 py-4 col-md-3">
										<h4><?php esc_html_e( 'Minutes', 'simple-countdown' ); ?></h4>
										<div class="countgroup-wrapper subtitle">
											<!-- Title Color -->
											<div class="my-5 row countdown-rotor-color">
												<div class="col-4">
													<h6><?php esc_html_e( 'Title Color', 'simple-countdown' ); ?></h6>
												</div>
												<div class="col-8">
													<label>
														<?php $cpt->settings->print_field( 'colors_minutes_title_color', false ); ?>
													</label>
												</div>
											</div>
											<!-- Front Color -->
											<div class="my-5 row countdown-rotor-color">
												<div class="col-4">
													<h6><?php esc_html_e( 'Front Color', 'simple-countdown' ); ?></h6>
												</div>
												<div class="col-8">
													<label>
														<?php $cpt->settings->print_field( 'colors_minutes_counter_front_color', false ); ?>
													</label>
												</div>
											</div>
											<!-- Back Color -->
											<div class="my-5 row countdown-rotor-color">
												<div class="col-4">
													<h6><?php esc_html_e( 'Back Color', 'simple-countdown' ); ?></h6>
												</div>
												<div class="col-8">
													<label>
														<?php $cpt->settings->print_field( 'colors_minutes_counter_back_color', false ); ?>
													</label>
												</div>
											</div>
											<!-- Divider Color -->
											<div class="my-5 row countdown-rotor-color">
												<div class="col-4">
													<h6><?php esc_html_e( 'Divider Color', 'simple-countdown' ); ?></h6>
												</div>
												<div class="col-8">
													<label>
														<?php $cpt->settings->print_field( 'colors_minutes_divider_color', false ); ?>
													</label>
												</div>
											</div>
										</div>
									</div>
									<!-- Seconds -->
									<div class="border settings-group my-4 py-4 col-md-3">
										<h4><?php esc_html_e( 'Seconds', 'simple-countdown' ); ?></h4>
										<div class="countgroup-wrapper subtitle">
											<!-- Title Color -->
											<div class="my-5 row countdown-rotor-color">
												<div class="col-4">
													<h6><?php esc_html_e( 'Title Color', 'simple-countdown' ); ?></h6>
												</div>
												<div class="col-8">
													<label>
														<?php $cpt->settings->print_field( 'colors_seconds_title_color', false ); ?>
													</label>
												</div>
											</div>
											<!-- Front Color -->
											<div class="my-5 row countdown-rotor-color">
												<div class="col-4">
													<h6><?php esc_html_e( 'Front Color', 'simple-countdown' ); ?></h6>
												</div>
												<div class="col-8">
													<label>
														<?php $cpt->settings->print_field( 'colors_seconds_counter_front_color', false ); ?>
													</label>
												</div>
											</div>
											<!-- Back Color -->
											<div class="my-5 row countdown-rotor-color">
												<div class="col-4">
													<h6><?php esc_html_e( 'Back Color', 'simple-countdown' ); ?></h6>
												</div>
												<div class="col-8">
													<label>
														<?php $cpt->settings->print_field( 'colors_seconds_counter_back_color', false ); ?>
													</label>
												</div>
											</div>
											<!-- Divider Color -->
											<div class="my-5 row countdown-rotor-color">
												<div class="col-4">
													<h6><?php esc_html_e( 'Divider Color', 'simple-countdown' ); ?></h6>
												</div>
												<div class="col-8">
													<label>
														<?php $cpt->settings->print_field( 'colors_seconds_divider_color', false ); ?>
													</label>
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
	<?php $cpt->settings->nonce_field(); ?>
</div>
<?php $cpt::loader_html( $plugin_info['classes_prefix'] ); ?>
