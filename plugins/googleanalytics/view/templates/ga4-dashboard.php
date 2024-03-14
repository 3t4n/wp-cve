<?php

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;

$ga4_demo = get_option('googleanalytics-ga4-demo');
$page_list_count_data = [];
$gender_count_data = [];
$age_count_data = [];
$analytics_service = new BetaAnalyticsDataClient( [
	'credentials' => Google\ApiCore\CredentialsWrapper::build( [
		'scopes'  => [
			'https://www.googleapis.com/auth/analytics.readonly',
		],
		'keyFile' => [
			'type'          => 'authorized_user',
			'client_id'     => $client_obj['client_id'],
			'client_secret' => $client_obj['client_secret'],
			'refresh_token' => $token_response["access_token"]
		],
	] ),
] );

$from = false === empty($date_range['from']) ? $date_range['from'] : '8daysAgo';
$to = false === empty($date_range['to']) ? $date_range['to'] : 'today';

$response = $analytics_service->runReport([
	'property' => $ga4_property,
	'dateRanges' => [
		new DateRange([
			'start_date' => $from,
			'end_date' => $to,
		]),
	],
	'dimensions' => [
		new Dimension(['name' => 'date']),
	],
	'metrics' => [
		new Metric(['name' => 'screenPageViews',]),
	],
	'orderBys' => [
		new OrderBy([
			'dimension' => new OrderBy\DimensionOrderBy([
				'dimension_name' => 'date', // your dimension here
				'order_type' => OrderBy\DimensionOrderBy\OrderType::ALPHANUMERIC
			]),
			'desc' => false,
		]),
	],
]);

$page_list_response = $analytics_service->runReport([
	'property' => $ga4_property,
	'dateRanges' => [
		new DateRange([
			'start_date' => $from,
			'end_date' => $to,
		]),
	],
	'dimensions' => [
		new Dimension(['name' => 'landingPage']),
	],
	'metrics' => [
		new Metric(['name' => 'screenPageViews',]),
	],
]);

foreach ($page_list_response->getRows() as $row) :
	$metrics = $row->getMetricValues();

	foreach ($row->getDimensionValues() as $index => $dimension_value) :
		$page = $dimension_value->getValue();
		$page_list_count_data[$page] = $metrics[$index]->getValue();
	endforeach;
endforeach;

$pageViewCount = array_sum(array_values($page_list_count_data));

$user_response = $analytics_service->runReport([
	'property' => $ga4_property,
	'dateRanges' => [
		new DateRange([
			'start_date' => $from,
			'end_date' => $to,
		]),
	],
	'dimensions' => [
		new Dimension(['name' => 'date',]),
	],
	'metrics' => [
		new Metric(['name' => 'newUsers',]),
	],
	'orderBys' => [
		new OrderBy([
			'dimension' => new OrderBy\DimensionOrderBy([
				'dimension_name' => 'date', // your dimension here
				'order_type' => OrderBy\DimensionOrderBy\OrderType::ALPHANUMERIC
			]),
			'desc' => false,
		]),
	],
]);

$gender_chart_response = $analytics_service->runReport([
	'property' => $ga4_property,
	'dateRanges' => [
		new DateRange([
			'start_date' => $from,
			'end_date' => $to,
		]),
	],
	'dimensions' => [
		new Dimension(['name' => 'userGender',]),
	],
	'metrics' => [
		new Metric(['name' => 'newUsers',]),
	],
]);

$age_chart_response = $analytics_service->runReport([
	'property' => $ga4_property,
	'dateRanges' => [
		new DateRange([
			'start_date' => $from,
			'end_date' => $to,
		]),
	],
	'dimensions' => [
		new Dimension(['name' => 'userAgeBracket',]),
	],
	'metrics' => [
		new Metric(['name' => 'newUsers',]),
	],
]);

foreach ($gender_chart_response->getRows() as $gender_row) :
	$metrics = $gender_row->getMetricValues();

	foreach ($gender_row->getDimensionValues() as $index => $dimension_value) :
		$page = $dimension_value->getValue();

		if ('unknown' !== $page) {
			$gender_count_data[$page] = $metrics[$index]->getValue();
		}
	endforeach;
endforeach;

$gender_count_data = array_reverse($gender_count_data);

foreach ($age_chart_response->getRows() as $gender_row) :
	$metrics = $gender_row->getMetricValues();

	foreach ($gender_row->getDimensionValues() as $index => $dimension_value) :
		$page = $dimension_value->getValue();

		if ('unknown' !== $page) {
			$age_count_data[$page] = $metrics[$index]->getValue();
		}
	endforeach;
endforeach;

$ga4_device_chart_response = $analytics_service->runReport([
	'property' => $ga4_property,
	'dateRanges' => [
		new DateRange([
			'start_date' => $from,
			'end_date' => $to,
		]),
	],
	'dimensions' => [
		new Dimension(['name' => 'deviceCategory',]),
	],
	'metrics' => [
		new Metric(['name' => 'newUsers',]),
	],
]);

foreach ($ga4_device_chart_response->getRows() as $device_row) :
	$metrics = $device_row->getMetricValues();

	foreach ($device_row->getDimensionValues() as $index => $dimension_value) :
		$page = $dimension_value->getValue();
		$ga4_demo_device_data[$page] = $metrics[$index]->getValue();
	endforeach;
endforeach;

$x = 1;
?>
<script type="text/javascript">
	ga_charts.init( function() {
		const pageSessionData = new google.visualization.DataTable();
		const userData = new google.visualization.DataTable();

		pageSessionData.addColumn( 'string', '<?php echo esc_js( __( 'Day', 'googleanalytics' ) ); ?>' );
		pageSessionData.addColumn( 'number', '<?php echo esc_js( __( 'Page Views', 'googleanalytics' ) ); ?>' );
		pageSessionData.addColumn( { type: 'string', role: 'tooltip', 'p': { 'html': true } } );

		userData.addColumn( 'string', '<?php echo esc_js( __( 'Day', 'googleanalytics' ) ); ?>' );
		userData.addColumn( 'number', '<?php echo esc_js( __( 'New Users', 'googleanalytics' ) ); ?>' );
		userData.addColumn( { type: 'string', role: 'tooltip', 'p': { 'html': true } } );

		<?php foreach ($response->getRows() as $row) :
			$metrics = $row->getMetricValues();

			foreach ($row->getDimensionValues() as $index => $dimension_value) :
				$date = date('M d', strtotime($dimension_value->getValue()));
				$page_session_count_data[$date] = $metrics[$index]->getValue();
			endforeach;
		endforeach;

		foreach ($user_response->getRows() as $user_row) :
			$metrics = $user_row->getMetricValues();

			foreach ($user_row->getDimensionValues() as $index => $dimension_value) :
				$date = date('M d', strtotime($dimension_value->getValue()));

				$user_count_data[$date] = $metrics[$index]->getValue();
			endforeach;
		endforeach;
		?>

		// Page Sessions.
		<?php foreach($page_session_count_data as $date => $value) : ?>
			pageSessionData.addRow( [
				'<?php echo esc_js($date); ?>',
				<?php echo esc_js( $value ); ?>,
				ga_charts.createPageTooltip( '<?php echo esc_js($date); ?>',
					'<?php echo esc_js( $value ); ?>'
				)
			] );
		<?php endforeach; ?>

		// User data.
		<?php foreach($user_count_data as $date => $value) : ?>
		userData.addRow( [
			'<?php echo esc_js($date); ?>',
			<?php echo esc_js( $value ); ?>,
			ga_charts.createUserTooltip( '<?php echo esc_js($date); ?>',
				'<?php echo esc_js( $value ); ?>'
			)
		] );
		<?php endforeach; ?>

		ga_charts.events( pageSessionData );
		ga_charts.drawPageSessionChart( pageSessionData );
		ga_charts.drawUserChart( userData );

		// GA4 Demographic gender chart.
		<?php
		$demo_gender_data[0] = array( 'Gender', 'The gender of visitors' );

		$x = 1;
		foreach ( $gender_count_data as $gender_type => $amount ) {
			$demo_gender_data[ $x ] = array( ucfirst( $gender_type ), intval( $amount ) );
			$x ++;
		}
		?>

		ga_charts.drawDemoGenderGa4Chart(<?php echo wp_json_encode( $demo_gender_data ); ?>);
		ga_loader.hide();

		// Demographic age chart
		<?php
		$demo_ga4_age_data[0] = array( 'Age', 'Average age range of visitors' );

		$x = 1;

		foreach ( $age_count_data as $age_type => $amount ) {
			$demo_ga4_age_data[ $x ] = array( $age_type, intval( $amount ) );
			$x ++;
		}
		?>
		ga_charts.drawDemoAgeGa4Chart(<?php echo wp_json_encode( $demo_ga4_age_data ); ?>);

		// Device chart.
		<?php
		$ga4_demo_count_data    = array();
		$ga4_demo_count_data[0] = array(
			__( 'Device', 'googleanalytics' ),
			__( 'Device Breakdown', 'googleanalytics' ),
		);

		$x = 1;
		foreach ( $ga4_demo_device_data as $age_type => $amount ) {
			$ga4_demo_count_data[ $x ] = array( $age_type, intval( $amount ) );
			$x ++;
		}
		?>
		ga_charts.drawGa4DemoDeviceChart(<?php echo wp_json_encode($ga4_demo_count_data); ?>);

		ga_loader.hide();
	} );
</script>
<div class="dashboard-title">GA4 Dashboard</div>

<?php
if (true === empty($page_list_count_data)) :
    echo wp_kses(
        Ga_Helper::ga_wp_notice(
            __( 'You don\'t appear to have enough page view data. Please come back at a later date once you do.' ),
            'warning',
            false,
            array(

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
else :
    ?>
<div id="page_session_chart_div"></div>

<?php require plugin_dir_path( __FILE__ ) . 'ga4-demographic-chart.php'; ?>

<div class="ga-panel ga-panel-default" style="width:100%; max-width:1210px; margin-top: 2rem;">
	<div class="ga-panel-heading">
		<strong><?php echo esc_html( 'Top 10 Pages/Posts by page views' ); ?></strong>
	</div>
	<div class="ga-panel-body">
		<div id="table-container">
			<table class="ga-table">
				<tr>
					<th style="text-align: right;">
						<?php echo esc_html( 'Url' ); ?>
					</th>
					<th style="text-align: right;">
						<?php echo esc_html( 'Pageviews' ); ?>
					</th>
					<th style="text-align: right;">
						<?php echo '%'; ?>
					</th>
				</tr>
				<?php foreach ( array_slice($page_list_count_data, 0, 10) as $page => $metric ) :
					$percentage = round((float)($metric / $pageViewCount) * 100 )
					?>
					<tr>
						<td class="ga-col-name">
							<?php
							if ( '(direct) / (none)' !== $page ) :
								$single_breakdown = false === empty( $ts ) ?
									'/explorer-table.plotKeys=%5B%5D&_r.drilldown=analytics.sourceMedium:' :
									'/explorer-table.plotKeys=%5B%5D&_r.drilldown=analytics.pagePath:';
								?>
								<a class="ga-source-name"
								   href="
										<?php
								   echo esc_url(
									   $page . $single_breakdown . str_replace(
										   '+',
										   '%20',
										   str_replace(
											   '2F',
											   '~2F',
											   str_replace( '%', '', rawurlencode( $page ) )
										   )
									   )
								   );
								   ?>
													/"
								   target="_blank"><?php echo esc_html( $page ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $page ); ?>
							<?php endif; ?>
						</td>
						<td style="text-align: right"><?php echo esc_html( $metric ); ?></td>
						<td>
							<div class="progress">
								<div class="progress-bar" role="progressbar"
									 aria-valuenow="<?php echo esc_attr( $percentage ); ?>" aria-valuemin="0"
									 aria-valuemax="100"
									 style="width: <?php echo esc_attr( $percentage ); ?>%;"></div>
								<span style="margin-left: 10px;">
									<?php echo esc_html( Ga_Helper::format_percent( $percentage ) ); ?>
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
<div id="user_chart_div"></div>
