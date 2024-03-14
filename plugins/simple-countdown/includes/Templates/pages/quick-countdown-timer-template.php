<?php

use GPLSCore\GPLS_PLUGIN_WPSCTR\Cpts\CountDownTimerCPT;

defined( 'ABSPATH' ) || exit;

$core          = $args['core'];
$plugin_info   = $args['plugin_info'];
$template_page = $args['template_page'];
?>

<h3><?php esc_html_e( 'Quick Count Down Timer', 'simple-countdown' ); ?></h3>

<?php
$template_page->print_fields();

$current_time = ( current_datetime()->getTimestamp() );
$target_time  = DateTime::createFromFormat( 'Y-m-d\TH:i', date( 'Y-m-d\TH:i', strtotime( '+1 week' ) ), wp_timezone() );
$timer_id     = sanitize_title( wp_generate_password( 8, false, false ) );
?>

<!-- CountDowm Timer -->
<div class="countdown-timer-preview-container">
	<div class="container p-3">
		<div id="<?php echo esc_attr( $plugin_info['classes_prefix'] . '-flipdown' ); ?>"
			class="flipdown <?php echo esc_attr( $plugin_info['classes_prefix'] . '-flipper' ); ?> flipper-dark <?php echo esc_attr( $plugin_info['classes_prefix'] . '-flipdown' ); ?>"
			data-datetime="<?php echo esc_attr( $target_time->getTimeStamp() ); ?>"
			data-template="ddd|HH|ii|ss"
			data-labels="Days|Hours|Minutes|Seconds"
			data-now="<?php echo esc_attr( $current_time ); ?>"
		>
		</div>
	</div>
</div>

<!-- Timer Shortcode -->
<div class="countdown-timer-shortcode-container my-2 px-3 py-5 shadow-sm rounded bg-white">
	<div class="container d-flex justify-content-center align-items-center">
		<strong class="me-1"><?php esc_html_e( 'Timer Shortcode:', 'simple-countdown' ); ?></strong>
		<div class="shortcode-wrapper d-flex align-items-center">
			<code class="<?php echo esc_attr( $plugin_info['classes_prefix'] . '-quick-countdown-shortcode' ); ?> me-1" >[<?php echo esc_attr( str_replace( '-', '_', $plugin_info['classes_prefix'] . '-quick-countdown' ) ); ?> id="<?php echo esc_attr( $timer_id ); ?>" datetime="<span class="shortcode-interval-time"><?php echo esc_attr( $target_time->getTimeStamp() ); ?></span>"]</code>
			<?php CountDownTimerCPT::clipboard_icon( '.' . $plugin_info['classes_prefix'] . '-quick-countdown-shortcode' ); ?>
		</div>
	</div>
</div>

<!-- Timer Colors -->
<div class="countdown-timer-colors-container bg-light">
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<div class="settings-list row">
					<div class="tab-section-wrapper col-12 my-3 p-3 bg-white shadow-lg">
						<div class="container-fluid border mt-4">
							<h4 class="mt-3 mb-3"><span class="me-3"><?php esc_html_e( 'Timer Colors', 'simple-countdown' ); ?></span><?php $core->pro_btn(); ?></h4>
							<div class="row p-1" style="background: #EEE;opacity: 0.5;">
								<!-- Days -->
								<div class="border settings-group my-4 py-4 col-md-3">
									<h4><?php esc_html_e( 'Days', 'simple-countdown' ); ?></h4>
									<div class="countgroup-wrapper subtitle">
										<!-- Days Colors -->
										<?php
										$days_colors = array( 'colors_days_title_color', 'colors_days_counter_front_color', 'colors_days_counter_back_color', 'colors_days_divider_color' );
										foreach ( $days_colors as $days_color_field_key ) :
											$field = $template_page->settings->get_field( $days_color_field_key );
										?>
										<div class="my-5 row countdown-rotor-color">
											<div class="col-4">
												<h6><?php printf( esc_html__( '%s', 'simple-countdown' ), $field['input_label'] ); ?></h6>
											</div>
											<div class="col-8">
												<label>
													<?php $template_page->settings->print_field( $days_color_field_key, false ); ?>
												</label>
											</div>
										</div>
										<?php endforeach; ?>
									</div>
								</div>
								<!-- Hours -->
								<div class="border settings-group my-4 py-4 col-md-3">
									<h4><?php esc_html_e( 'Hours', 'simple-countdown' ); ?></h4>
									<div class="countgroup-wrapper subtitle">
										<!-- Hours Colors -->
										<?php
										$days_colors = array( 'colors_hours_title_color', 'colors_hours_counter_front_color', 'colors_hours_counter_back_color', 'colors_hours_divider_color' );
										foreach ( $days_colors as $days_color_field_key ) :
											$field = $template_page->settings->get_field( $days_color_field_key );
										?>
										<div class="my-5 row countdown-rotor-color">
											<div class="col-4">
												<h6><?php printf( esc_html__( '%s', 'simple-countdown' ), $field['input_label'] ); ?></h6>
											</div>
											<div class="col-8">
												<label>
													<?php $template_page->settings->print_field( $days_color_field_key, false ); ?>
												</label>
											</div>
										</div>
										<?php endforeach; ?>
									</div>
								</div>
								<!-- Minutes -->
								<div class="border settings-group my-4 py-4 col-md-3">
									<h4><?php esc_html_e( 'Minutes', 'simple-countdown' ); ?></h4>
									<div class="countgroup-wrapper subtitle">
										<!-- Minutes Colors -->
										<?php
										$days_colors = array( 'colors_minutes_title_color', 'colors_minutes_counter_front_color', 'colors_minutes_counter_back_color', 'colors_minutes_divider_color' );
										foreach ( $days_colors as $days_color_field_key ) :
											$field = $template_page->settings->get_field( $days_color_field_key );
										?>
										<div class="my-5 row countdown-rotor-color">
											<div class="col-4">
												<h6><?php printf( esc_html__( '%s', 'simple-countdown' ), $field['input_label'] ); ?></h6>
											</div>
											<div class="col-8">
												<label>
													<?php $template_page->settings->print_field( $days_color_field_key, false ); ?>
												</label>
											</div>
										</div>
										<?php endforeach; ?>
									</div>
								</div>
								<!-- Seconds -->
								<div class="border settings-group my-4 py-4 col-md-3">
									<h4><?php esc_html_e( 'Seconds', 'simple-countdown' ); ?></h4>
									<div class="countgroup-wrapper subtitle">
										<!-- Seconds Colors -->
										<?php
										$days_colors = array( 'colors_seconds_title_color', 'colors_seconds_counter_front_color', 'colors_seconds_counter_back_color', 'colors_seconds_divider_color' );
										foreach ( $days_colors as $days_color_field_key ) :
											$field = $template_page->settings->get_field( $days_color_field_key );
										?>
										<div class="my-5 row countdown-rotor-color">
											<div class="col-4">
												<h6><?php printf( esc_html__( '%s', 'simple-countdown' ), $field['input_label'] ); ?></h6>
											</div>
											<div class="col-8">
												<label>
													<?php $template_page->settings->print_field( $days_color_field_key, false ); ?>
												</label>
											</div>
										</div>
										<?php endforeach; ?>
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
