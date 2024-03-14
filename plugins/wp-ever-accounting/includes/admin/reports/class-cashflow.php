<?php
/**
 * Admin Report Expenses By Date.
 *
 * Extended by reports to show charts and stats in admin.
 *
 * @author      EverAccounting
 * @category    Admin
 * @package     EverAccounting\Admin
 * @version     1.1.0
 */

namespace EverAccounting\Admin\Report;

defined( 'ABSPATH' ) || exit();

/**
 * Cashflow Class
 *
 * @package EverAccounting\Admin\Report
 */
class CashFlow extends Report {
	/**
	 * Get report.
	 *
	 * @since 1.1.0
	 *
	 * @param array $args Report arguments.
	 *
	 * @return array|mixed|void
	 */
	public function get_report( $args = array() ) {
		$this->maybe_clear_cache( $args );
		if ( empty( $args['year'] ) ) {
			echo '<p>';
			esc_html_e( 'Please select a year to generate the report.', 'wp-ever-accounting' );
			echo '</p>';

			return false;
		}
		$report = false;
		if ( empty( $report ) ) {
			$start_date = $this->get_start_date( $args['year'] );
			$end_date   = $this->get_end_date( $args['year'] );
			$period     = $this->get_dates_in_period( $start_date, $end_date );
			$dates      = array_fill_keys( array_keys( $period ), 0 );
			$income     = $this->calculate_total_income( $start_date, $end_date );
			$income     = array_merge( $dates, $income );
			$expense    = $this->calculate_total_expense( $start_date, $end_date );
			$expense    = array_merge( $dates, $expense );
			$profit     = array_map(
				function ( $in, $ex ) {
					return $in - $ex;
				},
				$income,
				$expense
			);
			$profit     = array_combine( array_keys( $dates ), $profit );
			$report     = array(
				'incomes'  => $income,
				'expenses' => $expense,
				'profits'  => $profit,
				'dates'    => $period,
			);
		}

		return $report;
	}

	/**
	 * Get calculated total income.
	 *
	 * @since 1.1.0
	 *
	 * @param string $start_date Start date.
	 * @param string $end_date End date.
	 * @param string $format Format.
	 *
	 * @return array
	 */
	protected function calculate_total_income( $start_date, $end_date, $format = '%Y-%m' ) {
		global $wpdb;
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT DATE_FORMAT(payment_date, '$format') `date`, SUM(amount) amount, currency_code, currency_rate
					   FROM {$wpdb->prefix}ea_transactions
					   WHERE type=%s AND payment_date BETWEEN %s AND %s AND category_id NOT IN (SELECT id from {$wpdb->prefix}ea_categories WHERE type='other')
					   GROUP BY currency_code, currency_rate, payment_date",
				'income',
				$start_date,
				$end_date
			)
		);
		$income  = array();
		foreach ( $results as $result ) {
			if ( ! isset( $income[ $result->date ] ) ) {
				$income[ $result->date ] = 0;
			}
			$amount                   = eaccounting_price_to_default( $result->amount, $result->currency_code, $result->currency_rate );
			$income[ $result->date ] += $amount;
		}

		return $income;
	}

	/**
	 * Get calculated total expense.
	 *
	 * @since 1.1.0
	 *
	 * @param string $start_date Start date.
	 * @param string $end_date End date.
	 * @param string $format Format.
	 *
	 * @return array
	 */
	protected function calculate_total_expense( $start_date, $end_date, $format = '%Y-%m' ) {
		global $wpdb;
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT DATE_FORMAT(payment_date, '$format') `date`, SUM(amount) amount, currency_code, currency_rate
					   FROM {$wpdb->prefix}ea_transactions
					   WHERE type=%s AND payment_date BETWEEN %s AND %s AND category_id NOT IN (SELECT id from {$wpdb->prefix}ea_categories WHERE type='other')
					   GROUP BY currency_code, currency_rate, payment_date",
				'expense',
				$start_date,
				$end_date
			)
		);
		$expense = array();
		foreach ( $results as $result ) {
			if ( ! isset( $expense[ $result->date ] ) ) {
				$expense[ $result->date ] = 0;
			}
			$amount                    = eaccounting_price_to_default( $result->amount, $result->currency_code, $result->currency_rate );
			$expense[ $result->date ] += $amount;
		}

		return $expense;
	}

	/**
	 * Output report.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function output() {
		$year   = filter_input( INPUT_GET, 'year', FILTER_SANITIZE_NUMBER_INT, array( 'options' => array( 'default' => wp_date( 'Y' ) ) ) );
		$report = $this->get_report( array( 'year' => $year ) );
		$filter = filter_input( INPUT_GET, 'filter', FILTER_SANITIZE_STRING );
		$report = wp_parse_args(
			$report,
			array(
				'dates'    => array(),
				'incomes'  => array(),
				'expenses' => array(),
				'profits'  => array(),
			)
		)
		?>
		<div class="ea-card">
			<div class="ea-card__header">
				<h3 class="ea-card__title"><?php esc_html_e( 'Cashflow report', 'wp-ever-accounting' ); ?></h3>
				<div class="ea-card__toolbar">
					<form action="<?php echo esc_url( admin_url( 'admin.php?page=ea-reports' ) ); ?>>" method="get">
						<?php esc_html_e( 'Filter', 'wp-ever-accounting' ); ?>
						<?php
						eaccounting_select2(
							array(
								'placeholder' => __( 'Year', 'wp-ever-accounting' ),
								'name'        => 'year',
								'options'     => eaccounting_get_report_years(),
								'value'       => $year,
							)
						);
						?>
						<input type="hidden" name="page" value="ea-reports">
						<input type="hidden" name="tab" value="cashflow">
						<input type="hidden" name="filter" value="true">
						<button type="submit" class="button-primary button"><?php esc_html_e( 'Submit', 'wp-ever-accounting' ); ?></button>
						<?php if ( ! empty( $filter ) ) : ?>
							<a class="button-secondary button" href="<?php echo esc_url( admin_url( 'admin.php?page=ea-reports&tab=cashflow' ) ); ?>"><?php esc_html_e( 'Reset', 'wp-ever-accounting' ); ?></a>
						<?php endif; ?>
					</form>
				</div>
			</div>
			<?php if ( ! empty( $year ) ) : ?>
				<div class="ea-card__inside">
					<div class="chart-container" style="position: relative; height:300px; width:100%">
						<canvas id="ea-expenses-chart" height="300" width="0"></canvas>
					</div>
					<script>
						window.addEventListener('DOMContentLoaded', function () {
							var ctx = document.getElementById('ea-expenses-chart').getContext('2d');
							new Chart(
									ctx,
									{
										type: 'line',
										data: {
											'labels': <?php echo wp_json_encode( array_values( $report['dates'] ) ); ?>,
											'datasets': [
												{
													label: '<?php echo esc_html__( 'Income', 'wp-ever-accounting' ); ?>',
													data: <?php echo wp_json_encode( array_values( $report['incomes'] ) ); ?>,
													backgroundColor: 'rgba(54, 68, 255, 0.1)',
													borderColor: 'rgb(54, 68, 255)',
													borderWidth: 4,
													fill: false,
													pointBackgroundColor: 'rgb(54, 68, 255)'
												},
												{
													label: '<?php echo esc_html__( 'Expense', 'wp-ever-accounting' ); ?>',
													data: <?php echo wp_json_encode( array_values( $report['expenses'] ) ); ?>,
													backgroundColor: 'rgba(242, 56, 90, 0.1)',
													borderColor: 'rgb(242, 56, 90)',
													borderWidth: 4,
													fill: false,
													pointBackgroundColor: 'rgb(242, 56, 90)'
												},
												{
													label: '<?php echo esc_html__( 'Profit', 'wp-ever-accounting' ); ?>',
													data: <?php echo wp_json_encode( array_values( $report['profits'] ) ); ?>,
													backgroundColor: 'rgba(0, 198, 137, 0.1)',
													borderColor: 'rgb(0, 198, 137)',
													borderWidth: 4,
													fill: false,
													pointBackgroundColor: 'rgb(0, 198, 137)'
												}
											]
										},
										options: {
											responsive: true,
											maintainAspectRatio: false,
											tooltips: {
												YrPadding: 12,
												backgroundColor: "#000000",
												bodyFontColor: "#e5e5e5",
												bodySpacing: 4,
												intersect: 0,
												mode: "nearest",
												position: "nearest",
												titleFontColor: "#ffffff",
												callbacks: {
													label: function(tooltipItem, data) {
														let label = data.labels[tooltipItem.index];
														let value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
														return ' ' + label + ': ' + eaccountingi10n.currency['symbol'] + Number((value).toFixed(1)).toLocaleString();
													}
												}
											},
											scales: {
												yAxes: [{
													barPercentage: 1.6,
													gridLines: {
														// borderDash: [1],
														// borderDashOffset: [2],
														color: "rgba(29,140,248,0.1)",
														drawBorder: false,
														zeroLineColor: "transparent",
													},
													ticks: {
														padding: 10,
														fontColor: '#9e9e9e',
														beginAtZero: true,
														callback: function (value, index, values) {
															return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
														}
													}
												}],
												xAxes: [{
													gridLines: {
														color: "rgba(29,140,248,0.0)",
														drawBorder: false,
														zeroLineColor: "transparent",
													},
													ticks: {
														fontColor: "#9e9e9e",
														suggestedMax: 125,
														suggestedMin: 60,
													}
												}]
											},
											legend: {
												display: false
											}
										}
									}
							);

						})
					</script>
				</div>

				<div class="ea-card__body">
					<div class="ea-table-report">
						<table class="wp-list-table widefat fixed striped">
							<thead>
							<tr>
								<th>&mdash;</th>
								<?php foreach ( $report['dates'] as $date ) : ?>
									<th class="align-right"><?php echo esc_html( $date ); ?></th>
								<?php endforeach; ?>
							</tr>
							</thead>

							<tbody>
							<?php if ( ! empty( $report['incomes'] ) ) : ?>
								<tr>
									<th><?php esc_html_e( 'Income', 'wp-ever-accounting' ); ?></th>
									<?php foreach ( $report['incomes'] as $income ) : ?>
										<td><?php echo esc_html( eaccounting_format_price( $income ) ); ?></td>
									<?php endforeach; ?>
								</tr>
								<tr>
									<th><?php esc_html_e( 'Expense', 'wp-ever-accounting' ); ?></th>
									<?php foreach ( $report['expenses'] as $expense ) : ?>
										<td><?php echo esc_html( eaccounting_format_price( $expense ) ); ?></td>
									<?php endforeach; ?>
								</tr>
							<?php else : ?>
								<tr class="no-results">
									<td colspan="13">
										<p><?php esc_html_e( 'No records found', 'wp-ever-accounting' ); ?></p>
									</td>
								</tr>
							<?php endif; ?>

							</tbody>

							<?php if ( ! empty( $report['profits'] ) ) : ?>
								<tfoot>
								<tr>
									<th><?php esc_html_e( 'Profit', 'wp-ever-accounting' ); ?></th>
									<?php foreach ( $report['profits'] as $profit ) : ?>
										<th class="align-right"><?php echo esc_html( eaccounting_format_price( $profit ) ); ?></th>
									<?php endforeach; ?>
								</tr>
								</tfoot>
							<?php endif; ?>
						</table>
					</div>
				</div>

				<div class="ea-card__footer">
					<a class="button button-secondary" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'refresh_report', 'yes' ), 'refresh_report' ) ); ?>">
						<?php esc_html_e( 'Reset Cache', 'wp-ever-accounting' ); ?>
					</a>
				</div>
			<?php else : ?>
				<div class="ea-card__inside">
					<p><?php esc_html_e( 'Please select financial year.', 'wp-ever-accounting' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
		<?php

	}
}
