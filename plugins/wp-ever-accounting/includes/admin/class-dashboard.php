<?php
/**
 * EverAccounting Admin Overview Page.
 *
 * @package     EverAccounting
 * @subpackage  Admin
 * @version     1.1.0
 */

namespace EverAccounting\Admin;

defined( 'ABSPATH' ) || exit();

/**
 * Class Dashboard
 *
 * @package EverAccounting\Admin
 */
class Dashboard {
	/**
	 * Dashboard constructor.
	 */
	public function __construct() {
		add_action( 'load-toplevel_page_eaccounting', array( __CLASS__, 'dashboard_setup' ) );
	}

	/**
	 * Setup the dashboard
	 *
	 * @since 1.1.0
	 */
	public static function dashboard_setup() {
		add_meta_box( 'total-income', false, array( __CLASS__, 'render_total_income_widget' ), 'ea-overview', 'top', 'high', array( 'col' => '4' ) );
		add_meta_box( 'total-expense', false, array( __CLASS__, 'render_total_expense_widget' ), 'ea-overview', 'top', 'high', array( 'col' => '4' ) );
		add_meta_box( 'total-profit', false, array( __CLASS__, 'render_total_profit_widget' ), 'ea-overview', 'top', 'high', array( 'col' => '4' ) );
		add_meta_box( 'cash-flow', __( 'Cash Flow', 'wp-ever-accounting' ), array( __CLASS__, 'render_cashflow' ), 'ea-overview', 'middle', 'high', array( 'col' => '12' ) );
		add_meta_box( 'income-category-chart', __( 'Income by categories', 'wp-ever-accounting' ), array( __CLASS__, 'render_incomes_categories' ), 'ea-overview', 'advanced', 'high', array( 'col' => '6' ) );
		add_meta_box( 'expense-category-chart', __( 'Expense by categories', 'wp-ever-accounting' ), array( __CLASS__, 'render_expenses_categories' ), 'ea-overview', 'advanced', 'high', array( 'col' => '6' ) );
		add_meta_box( 'latest-income', __( 'Latest Incomes', 'wp-ever-accounting' ), array( __CLASS__, 'render_latest_incomes' ), 'ea-overview', 'advanced', 'high', array( 'col' => '4' ) );
		add_meta_box( 'latest-expense', __( 'Latest Expenses', 'wp-ever-accounting' ), array( __CLASS__, 'render_latest_expenses' ), 'ea-overview', 'advanced', 'high', array( 'col' => '4' ) );
		add_meta_box( 'account-balance', __( 'Account Balances', 'wp-ever-accounting' ), array( __CLASS__, 'render_account_balances' ), 'ea-overview', 'advanced', 'high', array( 'col' => '4' ) );
		do_action( 'eaccounting_dashboard_setup' );
	}

	/**
	 * Get dashboard income year
	 *
	 * @since 1.1.0
	 */
	public static function get_dashboard_income_year() {
		if ( 'yes' === eaccounting_get_option( 'dashboard_transactions_limit' ) ) {
			return date_i18n( 'Y' );
		}

		return null;
	}

	/**
	 * Render total income
	 *
	 * @since 1.1.0
	 */
	public static function render_total_income_widget() {
		$total_income     = eaccounting_get_total_income( self::get_dashboard_income_year() );
		$total_receivable = eaccounting_get_total_receivable();
		?>
		<div class="ea-widget-card">
			<div class="ea-widget-card__icon">
				<span class="dashicons dashicons-money-alt"></span>
			</div>
			<div class="ea-widget-card__content">
				<div class="ea-widget-card__primary">
					<span class="ea-widget-card__title"><?php esc_html_e( 'Total Sales', 'wp-ever-accounting' ); ?></span>
					<span class="ea-widget-card__amount"><?php echo esc_html( eaccounting_format_price( $total_income ) ); ?></span>
				</div>

				<div class="ea-widget-card__secondary">
					<span class="ea-widget-card__title"><?php esc_html_e( 'Receivable', 'wp-ever-accounting' ); ?></span>
					<span class="ea-widget-card__amount"><?php echo esc_html( eaccounting_format_price( $total_receivable ) ); ?></span>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render total expense
	 *
	 * @since 1.1.0
	 */
	public static function render_total_expense_widget() {
		$total_expense = eaccounting_get_total_expense( self::get_dashboard_income_year() );
		$total_payable = eaccounting_get_total_payable();
		?>
		<div class="ea-widget-card alert">
			<div class="ea-widget-card__icon">
				<span class="dashicons dashicons-money-alt"></span>
			</div>
			<div class="ea-widget-card__content">
				<div class="ea-widget-card__primary">
					<span class="ea-widget-card__title"><?php esc_html_e( 'Total Expenses', 'wp-ever-accounting' ); ?></span>
					<span class="ea-widget-card__amount"><?php echo esc_html( eaccounting_format_price( $total_expense ) ); ?></span>
				</div>

				<div class="ea-widget-card__secondary">
					<span class="ea-widget-card__title"><?php esc_html_e( 'Payable', 'wp-ever-accounting' ); ?></span>
					<span class="ea-widget-card__amount"><?php echo esc_html( eaccounting_format_price( $total_payable ) ); ?></span>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render total profit
	 *
	 * @since 1.1.0
	 */
	public static function render_total_profit_widget() {
		$total_profit   = eaccounting_get_total_profit( self::get_dashboard_income_year() );
		$total_upcoming = eaccounting_get_total_upcoming_profit();
		?>
		<div class="ea-widget-card success">
			<div class="ea-widget-card__icon">
				<span class="dashicons dashicons-money-alt"></span>
			</div>
			<div class="ea-widget-card__content">
				<div class="ea-widget-card__primary">
					<span class="ea-widget-card__title"><?php esc_html_e( 'Total Profit', 'wp-ever-accounting' ); ?></span>
					<span class="ea-widget-card__amount"><?php echo esc_html( eaccounting_format_price( $total_profit ) ); ?></span>
				</div>

				<div class="ea-widget-card__secondary">
					<span class="ea-widget-card__title"><?php esc_html_e( 'Upcoming', 'wp-ever-accounting' ); ?></span>
					<span class="ea-widget-card__amount"><?php echo esc_html( eaccounting_format_price( $total_upcoming ) ); ?></span>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render cashflow
	 *
	 * @since 1.1.0
	 */
	public static function render_cashflow() {
		require_once dirname( __FILE__ ) . '/reports/class-report.php';
		require_once dirname( __FILE__ ) . '/reports/class-cashflow.php';
		$year   = date_i18n( 'Y' );
		$init   = new \EverAccounting\Admin\Report\CashFlow();
		$report = $init->get_report( array( 'year' => $year ) );
		?>
		<div class="ea-card__inside" style="position: relative; height:300px;">
			<canvas id="ea-cashflow-chart" height="300" width="0"></canvas>
			<script>
				window.addEventListener('DOMContentLoaded', function () {
					var ctx = document.getElementById('ea-cashflow-chart').getContext('2d');
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
											label: function (t, d) {
												var xLabel = d.datasets[t.datasetIndex].label;
												return xLabel + ': ' + eaccountingi10n.currency['symbol'] + Number((t.yLabel).toFixed(1)).toLocaleString();
											}
										}
									},
									scales: {
										yAxes: [{
											barPercentage: 1.6,
											gridLines: {
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
		<?php
	}

	/**
	 * Render income categories
	 *
	 * @since 1.1.0
	 */
	public static function render_incomes_categories() {
		require_once dirname( __FILE__ ) . '/reports/class-report.php';
		global $wpdb;
		$report     = new \EverAccounting\Admin\Report\Report();
		$start_date = $report->get_start_date();
		$end_date   = $report->get_end_date();
		$sql        = $wpdb->prepare(
			"SELECT SUM(t.amount) amount, t.currency_code, t.currency_rate, t.category_id, c.name category, c.color
		                     FROM {$wpdb->prefix}ea_transactions t
		                     LEFT JOIN {$wpdb->prefix}ea_categories c on c.id=t.category_id
		                     WHERE c.type = %s AND t.payment_date BETWEEN %s AND %s
		                     GROUP BY t.currency_code,t.currency_rate, t.category_id ",
			'income',
			$start_date,
			$end_date
		);
		$results    = $wpdb->get_results( $sql );
		$data       = array();
		foreach ( $results as $result ) {
			$amount = eaccounting_price_to_default( $result->amount, $result->currency_code, $result->currency_rate );
			if ( isset( $data[ $result->category ] ) ) {
				$data[ $result->category_id ]['amount'] = (int) ( $data[ $result->category ]['amount'] + $amount );
			} else {
				$data[ $result->category_id ] = array(
					'name'   => $result->category,
					'color'  => $result->color,
					'amount' => (int) $amount,
				);
			}
		}
		$data  = eaccounting_collect( $data )->sort(
			function ( $a, $b ) {
				return $b['amount'] > $a['amount'];
			}
		);
		$chart = $data->copy()->take( 5 );
		$rest  = $data->slice( 5 )->all();
		$total = array_sum( wp_list_pluck( $rest, 'amount' ) );
		$chart->push(
			array(
				'name'   => esc_html__( 'Others', 'wp-ever-accounting' ),
				'color'  => eaccounting_get_random_color(),
				'amount' => (int) $total,
			)
		);
		$chart   = $chart->all();
		$labels  = wp_list_pluck( $chart, 'name' );
		$colors  = wp_list_pluck( $chart, 'color' );
		$amounts = wp_list_pluck( $chart, 'amount' );
		?>
		<div class="chart-container" style="position: relative; height:300px; width:100%">
			<canvas id="ea-income-categories-chart"></canvas>
		</div>
		<script>
			window.addEventListener('DOMContentLoaded', function () {
				var data = {
					labels: <?php echo wp_json_encode( array_values( $labels ) ); ?>,
					datasets: [
						{
							data: <?php echo wp_json_encode( array_values( $amounts ) ); ?>,
							backgroundColor: <?php echo wp_json_encode( array_values( $colors ) ); ?>
						}
					]
				};
				new Chart(document.getElementById('ea-income-categories-chart'), {
					type: 'doughnut',
					data: data,
					options: {
						responsive: true,
						maintainAspectRatio: false,
						legend: {
							display: true,
							position: 'right'
						},
						tooltips: {
							callbacks: {
								label: function (tooltipItem, data) {
									let label = data.labels[tooltipItem.index];
									let value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
									return ' ' + label + ': ' + eaccountingi10n.currency['symbol'] + Number((value).toFixed(1)).toLocaleString();
								}
							}
						},
					}
				});
			});
		</script>
		<?php
	}

	/**
	 * Render expense categories
	 *
	 * @since 1.1.0
	 */
	public static function render_expenses_categories() {
		require_once dirname( __FILE__ ) . '/reports/class-report.php';
		global $wpdb;
		$report     = new \EverAccounting\Admin\Report\Report();
		$start_date = $report->get_start_date();
		$end_date   = $report->get_end_date();
		$sql        = $wpdb->prepare(
			"SELECT SUM(t.amount) amount, t.currency_code, t.currency_rate, t.category_id, c.name category, c.color
		                     FROM {$wpdb->prefix}ea_transactions t
		                     LEFT JOIN {$wpdb->prefix}ea_categories c on c.id=t.category_id
		                     WHERE c.type = %s AND t.payment_date BETWEEN %s AND %s
		                     GROUP BY t.currency_code,t.currency_rate, t.category_id ",
			'expense',
			$start_date,
			$end_date
		);
		$results    = $wpdb->get_results( $sql );
		$data       = array();
		foreach ( $results as $result ) {
			$amount = eaccounting_price_to_default( $result->amount, $result->currency_code, $result->currency_rate );
			if ( isset( $data[ $result->category ] ) ) {
				$data[ $result->category_id ]['amount'] = (int) ( $data[ $result->category ]['amount'] + $amount );
			} else {
				$data[ $result->category_id ] = array(
					'name'   => $result->category,
					'color'  => $result->color,
					'amount' => (int) $amount,
				);
			}
		}
		$data  = eaccounting_collect( $data )->sort(
			function ( $a, $b ) {
				return $b['amount'] > $a['amount'];
			}
		);
		$chart = $data->copy()->take( 5 );
		$rest  = $data->slice( 5 )->all();
		$total = array_sum( wp_list_pluck( $rest, 'amount' ) );
		$chart->push(
			array(
				'name'   => esc_html__( 'Others', 'wp-ever-accounting' ),
				'color'  => eaccounting_get_random_color(),
				'amount' => (int) $total,
			)
		);
		$chart   = $chart->all();
		$labels  = wp_list_pluck( $chart, 'name' );
		$colors  = wp_list_pluck( $chart, 'color' );
		$amounts = wp_list_pluck( $chart, 'amount' );
		?>
		<div class="chart-container" style="position: relative; height:300px; width:100%">
			<canvas id="ea-expense-categories-chart"></canvas>
		</div>
		<script>
			window.addEventListener('DOMContentLoaded', function () {
				var data = {
					labels: <?php echo wp_json_encode( array_values( $labels ) ); ?>,
					datasets: [
						{
							data: <?php echo wp_json_encode( array_values( $amounts ) ); ?>,
							backgroundColor: <?php echo wp_json_encode( array_values( $colors ) ); ?>
						}
					]
				};
				new Chart(document.getElementById('ea-expense-categories-chart'), {
					type: 'doughnut',
					data: data,
					options: {
						responsive: true,
						maintainAspectRatio: false,
						legend: {
							display: true,
							position: 'right'
						},
						tooltips: {
							callbacks: {
								label: function (tooltipItem, data) {
									let label = data.labels[tooltipItem.index];
									let value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
									return ' ' + label + ': ' + eaccountingi10n.currency['symbol'] + Number((value).toFixed(1)).toLocaleString();
								}
							}
						},
					}
				});
			});
		</script>
		<?php
	}

	/**
	 * Render latest income
	 *
	 * @since 1.1.0
	 */
	public static function render_latest_incomes() {
		global $wpdb;
		$incomes = $wpdb->get_results(
			$wpdb->prepare(
				"
		SELECT t.payment_date, c.name, t.amount, t.currency_code
		FROM {$wpdb->prefix}ea_transactions t
		LEFT JOIN {$wpdb->prefix}ea_categories as c on c.id=t.category_id
		WHERE t.type= 'income'
		AND t.currency_code != ''
		AND c.type != 'other'
		ORDER BY t.payment_date DESC
		LIMIT %d
		",
				5
			)
		);

		if ( empty( $incomes ) ) {
			echo sprintf(
				'<p class="ea-card__inside">%s</p>',
				esc_html__( 'There is no income records.', 'wp-ever-accounting' )
			);

			return;
		}
		?>
		<table class="ea-table">
			<thead>
			<tr>
				<th><?php esc_html_e( 'Date', 'wp-ever-accounting' ); ?></th>
				<th><?php esc_html_e( 'Category', 'wp-ever-accounting' ); ?></th>
				<th><?php esc_html_e( 'Amount', 'wp-ever-accounting' ); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ( $incomes as $income ) : ?>
				<tr>
					<td><?php echo esc_html( $income->payment_date ); ?></td>
					<td><?php echo esc_html( $income->name ); ?></td>
					<td><?php echo esc_html( eaccounting_format_price( $income->amount, $income->currency_code ) ); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Render latest expense
	 *
	 * @since 1.1.0
	 */
	public static function render_latest_expenses() {
		global $wpdb;
		$expenses = $wpdb->get_results(
			$wpdb->prepare(
				"
		SELECT t.payment_date, c.name, t.amount, t.currency_code
		FROM {$wpdb->prefix}ea_transactions t
		LEFT JOIN {$wpdb->prefix}ea_categories as c on c.id=t.category_id
		WHERE t.type= 'expense'
		AND t.currency_code != ''
		AND c.type != 'other'
		ORDER BY t.payment_date DESC
		LIMIT %d
		",
				5
			)
		);
		if ( empty( $expenses ) ) {
			echo sprintf(
				'<p class="ea-card__inside">%s</p>',
				esc_html__( 'There is no expense records.', 'wp-ever-accounting' )
			);

			return;
		}

		?>
		<table class="ea-table">
			<thead>
			<tr>
				<th><?php esc_html_e( 'Date', 'wp-ever-accounting' ); ?></th>
				<th><?php esc_html_e( 'Category', 'wp-ever-accounting' ); ?></th>
				<th><?php esc_html_e( 'Amount', 'wp-ever-accounting' ); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ( $expenses as $expense ) : ?>
				<tr>
					<td><?php echo esc_html( $expense->payment_date ); ?></td>
					<td><?php echo esc_html( $expense->name ); ?></td>
					<td><?php echo esc_html( eaccounting_format_price( $expense->amount, $expense->currency_code ) ); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Render account balances
	 *
	 * @since 1.1.0
	 */
	public static function render_account_balances() {
		global $wpdb;
		$accounts = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT a.name, a.opening_balance, a.currency_code,
				SUM(CASE WHEN t.type='income' then amount WHEN t.type='expense' then - amount END ) balance
				FROM {$wpdb->prefix}ea_accounts a
				LEFT JOIN {$wpdb->prefix}ea_transactions as t ON t.account_id=a.id
				GROUP BY a.id
				ORDER BY balance DESC
				LIMIT %d",
				5
			)
		);

		foreach ( $accounts as $key => $account ) {
			$total            = $account->balance + $account->opening_balance;
			$account->balance = eaccounting_format_price( $total, $account->currency_code );
			$accounts[ $key ] = $account;
		}

		if ( empty( $accounts ) ) {
			echo sprintf(
				'<p class="ea-card__inside">%s</p>',
				esc_html__( 'There is not accounts.', 'wp-ever-accounting' )
			);

			return;
		}

		?>
		<table class="ea-table">
			<thead>
			<tr>
				<th><?php esc_html_e( 'Account', 'wp-ever-accounting' ); ?></th>
				<th><?php esc_html_e( 'Balance', 'wp-ever-accounting' ); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ( $accounts as $account ) : ?>
				<tr>
					<td><?php echo esc_html( $account->name ); ?></td>
					<td><?php echo esc_html( $account->balance ); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	}
}

return new Dashboard();
