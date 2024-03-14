<?php
/**
 * Stats view.
 *
 * @package GoogleAnalytics
 */

// Variables passed in (with fallbacks in case they aren't).
$age_chart    = true === isset( $age_chart ) ? $age_chart : array();
$gender_chart = true === isset( $gender_chart ) ? $gender_chart : array();
$event_count_chart = true === isset( $event_count_chart ) ? $event_count_chart : array();

$account_data     = json_decode( get_option( 'googleanalytics_account_data', true ), true );
$selected_data    = json_decode( get_option( 'googleanalytics_selected_account', true ), true );
$demo_enabled     = get_option( 'googleanalytics_demographic' );
$demo_enabled     = false === empty( $demo_enabled );
$credentials      = GOOGLE_APPLICATION_CREDENTIALS;
$myfile           = file_get_contents( $credentials, 'r' ); // phpcs:ignore
$client_obj       = json_decode( $myfile );
$client           = new Ga_Admin();
$client           = $client->getGa4Client();
$token_response   = $client->getAccessToken();
$client_stuff     = (array) $client;
$client_obj       = array_values( $client_stuff )[4];
$ga4_demo_enabled = 'on' === get_option( 'googleanalytics-ga4-demo' );
$ga4_property     = get_option( 'googleanalytics-ga4-property' );
$ga4_property     = true === isset( $ga4_property ) ? $ga4_property : false;
$internal_prop    = $ga4_property;
$ua_prop_used     = get_option( 'googleanalytics-view-id' );

if ( true === is_array( $account_data ) ) {
	foreach ( $account_data as $properties ) {
		if ( $properties['id'] === $selected_data[0] ) {
			foreach ( $properties['webProperties'] as $web_property ) {
				if ( $web_property['webPropertyId'] === $selected_data[1] ) {
					$internal_prop = $web_property['internalWebPropertyId'];
				}
			}
		}
	}
}

$ts              = filter_input( INPUT_GET, 'ts', FILTER_SANITIZE_STRING );
$selected_page   = false === empty( $ts ) ? '' : 'selected';
$selected_source = false === empty( $ts ) ? 'selected' : '';
$report_url      = '';

if ( true === is_array( $selected_data ) ) {
	$report_url = 'https://analytics.google.com/analytics/web/#/report/content-pages/a' . $selected_data[0] . 'w' . $internal_prop . 'p' . $selected_data[2];
}

$source_page_url          = false === empty( $ts ) ? str_replace(
	'content-pages',
	'trafficsources-all-traffic',
	$report_url
) : $report_url;
$demographic_page_url     = str_replace( 'content-pages', 'visitors-demographics-overview', $report_url );
$type_label               = false === empty( $ts ) ? 'Traffic Sources' : 'Pages/Posts';
$source_url               = 'admin.php?page=googleanalytics&ts=1';
$send_data                = get_option( 'googleanalytics_send_data' );
$need_account_demo_enable = array() === $gender_chart && array() === $age_chart;

// Filter GA Action.
$ga_action = filter_input( INPUT_GET, 'ga_action', FILTER_SANITIZE_STRING );
$date_range = Ga_Helper::get_date_range_from_request();
$days_in_english = Ga_Helper::get_period_in_days_words( $date_range['from'], $date_range['to'] );

if ( false === $demo_enabled && false === $ga4_demo_enabled ) {
	echo wp_kses(
		Ga_Helper::ga_wp_notice(
			__( 'Visualize gender and age data with our new demographic feature.' ),
			'warning',
			false,
			array(
				'url'   => Ga_Helper::create_url(
					Ga_Helper::get_current_url(),
					array( Ga_Controller_Core::ACTION_PARAM_NAME => 'demo-ad' )
				),
				'label' => __( 'Access Now', 'googleanalytics' ),
			)
		),
		array(
			'button' => array(
				'class'   => array(),
				'onclick' => array(),
			),
			'div'    => array(
				'class' => array(),
			),
			'p'      => array(),
		)
	);
}
?>
<div class="wrap ga-wrap" id="ga-stats-container" data-scroll="<?php echo esc_attr( $ga_action ); ?>">
	<?php if (false === empty($selected_data)) : ?>
		<div class="dashboard-title">Universal Analytics</div>
	<?php endif; ?>
	<div class="filter-choices">
		<div>
			<?php
			Ga_Template::load(
				'templates/date-custom-range-filter',
				array(
					'date_from' => $date_range['from'],
					'date_to'   => $date_range['to'],
				)
			);
			?>
		</div>
	</div>
	<?php

	if ( false === empty( $chart ) ) : ?>
		<div class="ga-panel ga-panel-default">
			<div class="ga-panel-heading">
				<strong>
					<?php echo esc_html( 'Pageviews - ' . $days_in_english ); ?>
				</strong>
			</div>
			<div class="ga-panel-body ga-chart">
				<div id="chart_div" style="width: 100%;"></div>
				<div class="ga-loader-wrapper stats-page">
					<div class="ga-loader stats-page-loader"></div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $boxes ) ) : ?>
		<div class="ga-panel ga-panel-default">
			<div class="ga-panel-heading"><strong><?php echo esc_html( 'Comparison - ' . $days_in_english ); ?></strong>
			</div>
			<div class="ga-panel-body">
				<div class="ga-row">
					<?php foreach ( $boxes as $box ) : ?>
						<div class="ga-box">
							<div class="ga-panel ga-panel-default">
								<div class="ga-panel-body ga-box-centered">
									<div class="ga-box-label"><?php echo esc_html( $box['label'] ); ?></div>
									<div class="ga-box-diff" style="color: <?php echo esc_attr( $box['color'] ); ?>;">
										<?php echo esc_html( Ga_Helper::format_percent( $box['diff'] ) ); ?>
									</div>
									<div class="ga-box-comparison"><?php echo esc_html( $box['comparison'] ); ?></div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
	endif;

	if ( false === empty( get_option( 'ga4-token' ) ) && true === empty( get_option( 'googleanalytics-view-id' ) ) ) {
		echo '';
	} else {
		require plugin_dir_path( __FILE__ ) . '/templates/demographic-chart.php';
	}

	if ( ! empty( $sources ) ) :
		?>
		<div class="filter-choices" id="traffic-sources">
			<a href="<?php echo esc_url( get_admin_url( '', 'admin.php?page=googleanalytics#traffic-sources' ) ); ?>"
			class="<?php echo esc_attr( $selected_page ); ?>">
				<?php esc_html_e( 'Page View', 'googleanalytics' ); ?>
			</a>
			<a href="<?php echo esc_url( get_admin_url( '', 'admin.php?page=googleanalytics&ts=1#traffic-sources' ) ); ?>"
			class="<?php echo esc_attr( $selected_source ); ?>">
				<?php esc_html_e( 'Traffic Source', 'googleanalytics' ); ?>
			</a>
		</div>
		<div class="ga-panel ga-panel-default">
			<div class="ga-panel-heading">
				<strong><?php echo esc_html( 'Top 10 ' . $type_label . ' by page views' ); ?></strong>
			</div>
			<div class="ga-panel-body">

				<div id="table-container">
					<table class="ga-table">
						<tr>
							<td colspan="2">
							</td>
							<th style="text-align: right;">
								<?php echo esc_html( 'Pageviews' ); ?>
							</th>
							<th style="text-align: right;">
								<?php echo '%'; ?>
							</th>
						</tr>
						<tr>
							<td colspan="2"></td>
							<td class="ga-col-pageviews" style="text-align: right">
								<div style="font-size: 16px;"><?php echo esc_html( $sources['total'] ); ?></div>
								<div style="color: grey; font-size: 10px;">% of
									Total:
									<?php
									echo esc_html(
										Ga_Helper::format_percent(
											( false === empty( $sources['total'] ) ) ? number_format(
												$sources['sum'] / $sources['total'] * 100,
												2,
												'.',
												' '
											) : 100
										)
									);
									?>
									(<?php echo esc_html( $sources['sum'] ); ?>)
								</div>
							</td>
							<td class="ga-col-progressbar" style="text-align: right">
								<div style="font-size: 16px;"><?php echo esc_html( $sources['total'] ); ?></div>
								<div style="color: grey; font-size: 10px;">% of
									Total:
									<?php
									echo esc_html(
										Ga_Helper::format_percent(
											( false === empty( $sources['total'] ) ) ? number_format(
												$sources['sum'] / $sources['total'] * 100,
												2,
												'.',
												' '
											) : 100
										)
									);
									?>
									(<?php echo esc_html( $sources['sum'] ); ?>)
								</div>
							</td>
						</tr>
						<?php foreach ( $sources['rows'] as $key => $source ) : ?>
							<tr>
								<td style="width: 5%;text-align: right"><?php echo esc_html( $key ); ?>.</td>
								<td class="ga-col-name">
									<?php
									if ( '(direct) / (none)' !== $source['name'] ) :
										$single_breakdown = false === empty( $ts ) ?
											'/explorer-table.plotKeys=%5B%5D&_r.drilldown=analytics.sourceMedium:' :
											'/explorer-table.plotKeys=%5B%5D&_r.drilldown=analytics.pagePath:';
										?>
										<a class="ga-source-name"
										href="
										<?php
											echo esc_url(
												$source_page_url . $single_breakdown . str_replace(
													'+',
													'%20',
													str_replace(
														'2F',
														'~2F',
														str_replace( '%', '', rawurlencode( $source['url'] ) )
													)
												)
											);
										?>
													/"
										target="_blank"><?php echo esc_html( $source['name'] ); ?></a>
									<?php else : ?>
										<?php echo esc_html( $source['name'] ); ?>
									<?php endif; ?>
								</td>
								<td style="text-align: right"><?php echo esc_html( $source['number'] ); ?></td>
								<td>
									<div class="progress">
										<div class="progress-bar" role="progressbar"
											aria-valuenow="<?php echo esc_attr( $source['percent'] ); ?>" aria-valuemin="0"
											aria-valuemax="100"
											style="width: <?php echo esc_attr( $source['percent'] ); ?>%;"></div>
										<span style="margin-left: 10px;">
											<?php echo esc_html( Ga_Helper::format_percent( $source['percent'] ) ); ?>
										</span>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					</table>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php
	if ( ! empty( $chart ) ) :
		?>
		<script type="text/javascript">

			ga_charts.init( function() {

					var data = new google.visualization.DataTable();

					data.addColumn( 'string', '<?php echo esc_js( __( 'Day', 'googleanalytics' ) ); ?>' );
					data.addColumn( 'number', '<?php echo esc_js( __( 'Pageviews', 'googleanalytics' ) ); ?>' );
					data.addColumn( { type: 'string', role: 'tooltip', 'p': { 'html': true } } );

					<?php foreach ( $chart as $row ) : ?>
					data.addRow( [
						'<?php echo esc_js( $row['day'] ); ?>',
						<?php echo esc_js( $row['current'] ); ?>,
						ga_charts.createTooltip( '<?php echo esc_js( $row['day'] ); ?>',
							'<?php echo esc_js( $row['current'] ); ?>'
						)
					] );
					<?php endforeach; ?>
					ga_charts.events( data );
					ga_charts.drawChart( data );
					ga_loader.hide();

					<?php
					// Event Count
					$event_count_data    = array();
					$event_count_data[0] = array(
						__( 'Device', 'googleanalytics' ),
						__( 'Device Breakdown', 'googleanalytics' ),
					);

					$x = 1;
					foreach ($event_count_chart as $event_type => $amount) {
						$event_count_data[ $x ] = array( $event_type, intval( $amount ) );
						$x ++;
					}
					?>
					// Demographic gender chart
					<?php
					$demo_gender_data[0] = array( 'Gender', 'The gender of visitors' );

					$x = 1;
					foreach ( $gender_chart as $gender_type => $amount ) {
						$demo_gender_data[ $x ] = array( ucfirst( $gender_type ), intval( $amount ) );
						$x ++;
					}
					?>

					ga_charts.drawDemoGenderChart(<?php echo wp_json_encode( $demo_gender_data ); ?>);
					ga_loader.hide();

					// Demographic age chart
					<?php
					$demo_age_data[0] = array( 'Age', 'Average age range of visitors' );

					$x = 1;

					foreach ( $age_chart as $age_type => $amount ) {
						$demo_age_data[ $x ] = array( $age_type, intval( $amount ) );
						$x ++;
					}
					?>
					ga_charts.drawDemoAgeChart(<?php echo wp_json_encode( $demo_age_data ); ?>);

					// Device chart.
					<?php
					$demo_device_data    = array();
					$demo_device_data[0] = array(
						__( 'Device', 'googleanalytics' ),
						__( 'Device Breakdown', 'googleanalytics' ),
					);

					$x = 1;
					foreach ( $device_chart as $age_type => $amount ) {
						$demo_device_data[ $x ] = array( $age_type, intval( $amount ) );
						$x ++;
					}
					?>
					ga_charts.drawDemoDeviceChart(<?php echo wp_json_encode( $demo_device_data ); ?>);

					ga_loader.hide();

					<?php if ( Ga_Helper::are_features_enabled() && ! empty( $send_data ) && 'true' === $send_data ) : ?>
					ga_events.sendDemoData(<?php echo esc_js( get_option( 'googleanalytics_demo_data' ) ); ?>);
						<?php
						update_option( 'googleanalytics_demo_date', gmdate( 'Y-m-d' ) );
						update_option( 'googleanalytics_send_data', 'false' );
					endif;
					?>
				},
			);
		</script>
		<?php
	endif;

	if ( false !== $ga4_property && true === empty( $ua_prop_used ) ) {
		include 'templates/ga4-dashboard.php';
	}

	include 'templates/demo-popup.php';
	?>
</div>
