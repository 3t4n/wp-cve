<?php
/**
 * Dashboard Widget view.
 *
 * @package GoogleAnalytics
 */

$ga_nonce            = isset( $ga_nonce ) ? $ga_nonce : '';
$more_details_url    = isset( $more_details_url ) ? $more_details_url : '#';
$show_trigger_button = isset( $show_trigger_button ) ? $show_trigger_button : false;
?>
<div class="wrap ga-wrap">
	<div class="form-group">
		<select id="range-selector" autocomplete="off">
			<option value="7daysAgo"><?php esc_html_e( 'Last 7 Days', 'googleanalytics' ); ?></option>
			<option value="30daysAgo" selected="selected">
				<?php esc_html_e( 'Last 30 Days', 'googleanalytics' ); ?></option>
			<option value="90daysAgo"><?php esc_html_e( 'Last 90 Days', 'googleanalytics' ); ?></option>
		</select>

		<select id="metrics-selector" autocomplete="off">
			<option value="pageviews"><?php esc_html_e( 'Pageviews', 'googleanalytics' ); ?></option>
			<option value="sessions"><?php esc_html_e( 'Visits', 'googleanalytics' ); ?></option>
			<option value="users"><?php esc_html_e( 'Users', 'googleanalytics' ); ?></option>
			<option value="organicSearches"><?php esc_html_e( 'Organic Search', 'googleanalytics' ); ?></option>
			<option value="visitBounceRate"><?php esc_html_e( 'Bounce Rate', 'googleanalytics' ); ?></option>
		</select>

		<div class="ga-loader-wrapper">
			<div class="ga-loader"></div>
		</div>
	</div>

	<div>
		<div id="chart_div" style="width: 100%;">
			<?php if ( true === $show_trigger_button ) : ?>
				<div style="text-align: center">
					<div style="margin: 20px auto;">
						<button id="ga-widget-trigger" style="border: 1px solid #cccccc;width: 60%; padding: 10px"
								class="button-link">
							<?php esc_html_e( 'Click here to get data', 'googleanalytics' ); ?>
						</button>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<div id="ga_widget_error" class="notice notice-warning hidden"></div>
		<div>
			<div id="boxes-container">
				<div class="ga-box-row">
					<?php if ( false === empty( $boxes ) ) : ?>
						<?php $iter = 1; ?>
						<?php foreach ( $boxes as $k => $v ) : ?>
					<div class="ga-box-column ga-box-dashboard">
						<div style="color: grey; font-size: 13px;" id="ga_box_dashboard_label_<?php echo esc_attr( $k ); ?>">
							<?php echo esc_html( $v['label'] ); ?></div>
						<div style="font-size: 15px;" id="ga_box_dashboard_value_<?php echo esc_attr( $k ); ?>">
							<?php echo esc_html( $v['value'] ); ?></div>
					</div>
							<?php if ( ( ( $iter ++ ) % 3 ) === 0 ) : ?>
				</div>
				<div class="ga-box-row">
					<?php endif; ?>
					<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<div style="margin-top: 5px;">
	<?php
	echo wp_kses_post(
		sprintf(
			'<a href="%s">' . __( 'Show more details' ) . '</a>',
			esc_url( $more_details_url )
		)
	);
	?>
			</div>
</div>

<script type="text/javascript">
	const GA_NONCE = '<?php echo esc_js( $ga_nonce ); ?>';
	const GA_NONCE_FIELD = '<?php echo esc_js( Ga_Admin_Controller::GA_NONCE_FIELD_NAME ); ?>';
	<?php if ( false === $show_trigger_button ) : ?>
		<?php if ( false === empty( $chart ) ) : ?>
	dataArr = [ [ 'Day', 'Pageviews' ],
			<?php
			$arr = '';
			foreach ( $chart as $row ) {
				if ( $arr ) {
					$arr .= ',';
				}
				$arr .= "['" . $row['day'] . "'," . $row['current'] . ']';
			}

			echo esc_js( $arr );
			?>
		];

	ga_dashboard.init( dataArr, true );
	ga_dashboard.events( dataArr );
	<?php endif; ?>
	<?php else : ?>
	dataArr = [ [ 'Day', 'Pageviews' ], [] ];
	ga_dashboard.init( false, false );
	ga_dashboard.events();
	<?php endif; ?>
</script>
