<?php

namespace ZPOS\Admin\Reports;

use WP_User_Query;

if (!defined('ABSPATH')) {
	exit(); // Exit if accessed directly
}

class ReportSalesByOrderType extends \WC_Admin_Report
{
	/**
	 * Chart colors.
	 *
	 * @var array
	 */
	public $chart_colours = [];

	/**
	 * Customers.
	 *
	 * @var array
	 */
	public $customers = [];

	/**
	 * Get the legend for the main chart sidebar.
	 *
	 * @return array
	 */
	public function get_chart_legend()
	{
		$legend = [];

		$legend[] = [
			'title' => '<strong>POS Sales</strong>',
			'color' => $this->chart_colours['pos_sales'],
			'highlight_series' => 2,
		];

		$legend[] = [
			'title' => '<strong>Online Sales</strong>',
			'color' => $this->chart_colours['web_sales'],
			'highlight_series' => 3,
		];

		return $legend;
	}

	/**
	 * Get chart widgets.
	 *
	 * @return array
	 */
	public function get_chart_widgets()
	{
		$widgets = [];

		$widgets[] = [
			'title' => '',
			'callback' => [$this, 'pos_vs_web'],
		];

		return $widgets;
	}

	/**
	 * Output customers vs guests chart.
	 */
	public function pos_vs_web()
	{
		$pos_data = $this->get_order_report_data([
			'data' => [
				'ID' => [
					'type' => 'post_data',
					'function' => 'COUNT',
					'name' => 'total_orders',
				],
			],
			'where_meta' => [
				[
					'meta_key' => '_pos_type',
					'meta_value' => 'pos',
					'operator' => '=',
				],
			],
			'filter_range' => true,
			'nocache' => true,
		]);

		$web_data = $this->get_order_report_data([
			'data' => [
				'ID' => [
					'type' => 'post_data',
					'function' => 'COUNT',
					'name' => 'total_orders',
				],
			],
			'where_meta' => [
				[
					'meta_key' => '_created_via',
					'meta_value' => 'checkout',
					'operator' => '=',
				],
			],
			'filter_range' => true,
			'nocache' => true,
		]);
		?>
		<div class="chart-container">
			<div class="chart-placeholder pos_vs_web pie-chart" style="height:200px"></div>
			<ul class="pie-chart-legend">
				<li
					style="border-color: <?= $this->chart_colours['pos_orders'] ?>">POS
				</li>
				<li
					style="border-color: <?= $this->chart_colours['web_orders'] ?>">Online
				</li>
			</ul>
		</div>
		<script type="text/javascript">
			jQuery(function () {
				jQuery.plot(
					jQuery('.chart-placeholder.pos_vs_web'),
					[
						{
							label: 'POS',
							data: "<?= $pos_data->total_orders ?>",
							color: '<?= $this->chart_colours['pos_orders'] ?>',
						},
						{
							label: 'Online',
							data: "<?= $web_data->total_orders ?>",
							color: '<?= $this->chart_colours['web_orders'] ?>',
						}
					],
					{
						grid: {
							hoverable: true
						},
						series: {
							pie: {
								show: true,
								radius: 1,
								innerRadius: 0.6,
								label: {
									show: false
								}
							},
							enable_tooltip: true,
							append_tooltip: "<?= ' ' . __('orders', 'woocommerce') ?>",
						},
						legend: {
							show: false
						}
					}
				);

				jQuery('.chart-placeholder.customers_vs_guests').resize();
			});
		</script>
		<?php
	}

	/**
	 * Output the report.
	 */
	public function output_report()
	{
		$ranges = [
			'year' => __('Year', 'woocommerce'),
			'last_month' => __('Last month', 'woocommerce'),
			'month' => __('This month', 'woocommerce'),
			'7day' => __('Last 7 days', 'woocommerce'),
		];

		$this->chart_colours = [
			'pos_orders' => '#e3f2fd',
			'pos_sales' => '#0d47a1',
			'pos_taxes' => '#2196f3',
			'pos_net_profit' => '#90caf9',

			'web_orders' => '#fff3e0',
			'web_sales' => '#bf360c',
			'web_taxes' => '#ff9800',
			'web_net_profit' => '#ffcc80',
		];

		$current_range = !empty($_GET['range']) ? sanitize_text_field($_GET['range']) : '7day';

		if (!in_array($current_range, ['custom', 'year', 'last_month', 'month', '7day'])) {
			$current_range = '7day';
		}

		$this->check_current_range_nonce($current_range);
		$this->calculate_current_range($current_range);

		$admin_users = new WP_User_Query([
			'role' => 'administrator',
			'fields' => 'ID',
		]);

		$manager_users = new WP_User_Query([
			'role' => 'shop_manager',
			'fields' => 'ID',
		]);

		$users_query = new WP_User_Query([
			'fields' => ['user_registered'],
			'exclude' => array_merge($admin_users->get_results(), $manager_users->get_results()),
		]);

		$this->customers = $users_query->get_results();

		foreach ($this->customers as $key => $customer) {
			if (
				strtotime($customer->user_registered) < $this->start_date ||
				strtotime($customer->user_registered) > $this->end_date
			) {
				unset($this->customers[$key]);
			}
		}

		include WC()->plugin_path() . '/includes/admin/views/html-report-by-date.php';
	}

	/**
	 * Output an export link.
	 */
	public function get_export_button()
	{
		$current_range = !empty($_GET['range']) ? sanitize_text_field($_GET['range']) : '7day'; ?>
		<a
			href="#"
			download="report-<?= esc_attr($current_range) ?>-<?= date_i18n(
	'Y-m-d',
	current_time('timestamp')
) ?>.csv"
			class="export_csv"
			data-export="chart"
			data-xaxes="<?php esc_attr_e('Date', 'woocommerce'); ?>"
			data-groupby="<?= $this->chart_groupby ?>"
		>
			<?php _e('Export CSV', 'woocommerce'); ?>
		</a>
		<?php
	}

	/**
	 * Output the main chart.
	 */
	public function get_main_chart()
	{
		global $wp_locale;

		$pos_data = $this->get_order_report_data([
			'data' => [
				'ID' => [
					'type' => 'post_data',
					'function' => 'COUNT',
					'name' => 'total_orders',
				],
				'_order_total' => [
					'type' => 'meta',
					'function' => 'SUM',
					'name' => 'total_sales',
				],
				'_order_tax' => [
					'type' => 'meta',
					'function' => 'SUM',
					'name' => 'total_taxes',
				],
				'post_date' => [
					'type' => 'post_data',
					'function' => '',
					'name' => 'post_date',
				],
			],
			'where_meta' => [
				[
					'meta_key' => '_pos_type',
					'meta_value' => 'pos',
					'operator' => '=',
				],
			],
			'group_by' => $this->group_by_query,
			'order_by' => 'post_date ASC',
			'query_type' => 'get_results',
			'filter_range' => true,
			'nocache' => true,
		]);

		$web_data = $this->get_order_report_data([
			'data' => [
				'ID' => [
					'type' => 'post_data',
					'function' => 'COUNT',
					'name' => 'total_orders',
				],
				'_order_total' => [
					'type' => 'meta',
					'function' => 'SUM',
					'name' => 'total_sales',
				],
				'_order_tax' => [
					'type' => 'meta',
					'function' => 'SUM',
					'name' => 'total_taxes',
				],
				'post_date' => [
					'type' => 'post_data',
					'function' => '',
					'name' => 'post_date',
				],
			],
			'where_meta' => [
				[
					'meta_key' => '_created_via',
					'meta_value' => 'checkout',
					'operator' => '=',
				],
			],
			'group_by' => $this->group_by_query,
			'order_by' => 'post_date ASC',
			'query_type' => 'get_results',
			'filter_range' => true,
			'nocache' => true,
		]);

		list($pos_data, $web_data) = array_map(
			function ($data) {
				return array_map(function ($el) {
					$post_date = $el->post_date;
					unset($el->post_date);
					$el->net_profit = (string) (1 * $el->total_sales - 1 * $el->total_taxes);
					$el->post_date = $post_date;
					return $el;
				}, $data);
			},
			[$pos_data, $web_data]
		);

		$pos_orders = $this->prepare_chart_data(
			$pos_data,
			'post_date',
			'total_orders',
			$this->chart_interval,
			$this->start_date,
			$this->chart_groupby
		);
		$pos_sales = $this->prepare_chart_data(
			$pos_data,
			'post_date',
			'total_sales',
			$this->chart_interval,
			$this->start_date,
			$this->chart_groupby
		);
		$pos_taxes = $this->prepare_chart_data(
			$pos_data,
			'post_date',
			'total_taxes',
			$this->chart_interval,
			$this->start_date,
			$this->chart_groupby
		);
		$pos_profit = $this->prepare_chart_data(
			$pos_data,
			'post_date',
			'net_profit',
			$this->chart_interval,
			$this->start_date,
			$this->chart_groupby
		);

		$web_orders = $this->prepare_chart_data(
			$web_data,
			'post_date',
			'total_orders',
			$this->chart_interval,
			$this->start_date,
			$this->chart_groupby
		);
		$web_sales = $this->prepare_chart_data(
			$web_data,
			'post_date',
			'total_sales',
			$this->chart_interval,
			$this->start_date,
			$this->chart_groupby
		);
		$web_taxes = $this->prepare_chart_data(
			$web_data,
			'post_date',
			'total_taxes',
			$this->chart_interval,
			$this->start_date,
			$this->chart_groupby
		);
		$web_profit = $this->prepare_chart_data(
			$web_data,
			'post_date',
			'net_profit',
			$this->chart_interval,
			$this->start_date,
			$this->chart_groupby
		);

		// Encode in json format
		$chart_data = json_encode([
			'pos_orders' => array_values($pos_orders),
			'pos_sales' => array_values($pos_sales),
			'pos_taxes' => array_values($pos_taxes),
			'web_orders' => array_values($web_orders),
			'web_sales' => array_values($web_sales),
			'web_taxes' => array_values($web_taxes),
			'pos_net_profit' => array_values($pos_profit),
			'web_net_profit' => array_values($web_profit),
		]);
		?>
		<div class="chart-container">
			<div class="chart-placeholder main"></div>
		</div>
		<script type="text/javascript">
			var main_chart;

			jQuery(function () {
				var chart_data = jQuery.parseJSON('<?= $chart_data ?>');
				var drawGraph = function (highlight) {
					var series = [
						{
							label: "POS orders",
							data: chart_data.pos_orders,
							color: '<?= $this->chart_colours['pos_orders'] ?>',
							bars: {
								fillColor: '<?= $this->chart_colours['pos_orders'] ?>',
								fill: true,
								show: true,
								lineWidth: 0,
								barWidth: <?= $this->barwidth ?>
								* 0.5,
								align: 'center'
							},
							yaxis: 1,
							shadowSize: 0,
							enable_tooltip: true,
							append_tooltip: " pos orders",
						},
						{
							label: "Web orders",
							data: chart_data.web_orders,
							color: '<?= $this->chart_colours['web_orders'] ?>',
							bars: {
								fillColor: '<?= $this->chart_colours['web_orders'] ?>',
								fill: true,
								show: true,
								lineWidth: 0,
								barWidth: <?= $this->barwidth ?>
								* 0.5,
								align: 'center'
							},
							yaxis: 1,
							shadowSize: 0,
							enable_tooltip: true,
							append_tooltip: " web orders",
						},
						{
							label: "POS Sales",
							data: chart_data.pos_sales,
							color: '<?= $this->chart_colours['pos_sales'] ?>',
							lines: {
								fillColor: '<?= $this->chart_colours['pos_sales'] ?>',
								fill: false,
								show: true,
								lineWidth: 2,
								barWidth: <?= $this->barwidth ?>
								* 0.5,
								align: 'center'
							},
							points: {
								show: true,
								lineWidth: 2,
								radius: 5,
								fill: true,
								fillColor: '#fff'
							},
							yaxis: 2,
							shadowSize: 0,
							enable_tooltip: true,
							append_tooltip: " pos sales",
						},
						{
							label: "Web Sales",
							data: chart_data.web_sales,
							color: '<?= $this->chart_colours['web_sales'] ?>',
							lines: {
								fillColor: '<?= $this->chart_colours['web_sales'] ?>',
								fill: false,
								show: true,
								lineWidth: 2,
								barWidth: <?= $this->barwidth ?>
								* 0.5,
								align: 'center'
							},
							points: {
								show: true,
								lineWidth: 2,
								radius: 5,
								fill: true,
								fillColor: '#fff'
							},
							yaxis: 2,
							shadowSize: 0,
							enable_tooltip: true,
							append_tooltip: " web sales",
						},
						{
							label: "POS Taxes",
							data: chart_data.pos_taxes,
							color: '<?= $this->chart_colours['pos_taxes'] ?>',
							lines: {
								fillColor: '<?= $this->chart_colours['pos_taxes'] ?>',
								fill: false,
								show: true,
								lineWidth: 2,
								barWidth: <?= $this->barwidth ?>
								* 0.5,
								align: 'center'
							},
							points: {
								show: true,
								lineWidth: 2,
								radius: 5,
								fill: true,
								fillColor: '#fff'
							},
							yaxis: 2,
							shadowSize: 0,
							enable_tooltip: true,
							append_tooltip: " pos taxes",
						},
						{
							label: "Web Taxes",
							data: chart_data.web_taxes,
							color: '<?= $this->chart_colours['web_taxes'] ?>',
							lines: {
								fillColor: '<?= $this->chart_colours['web_taxes'] ?>',
								fill: false,
								show: true,
								lineWidth: 2,
								barWidth: <?= $this->barwidth ?>
								* 0.5,
								align: 'center'
							},
							points: {
								show: true,
								lineWidth: 2,
								radius: 5,
								fill: true,
								fillColor: '#fff'
							},
							yaxis: 2,
							shadowSize: 0,
							enable_tooltip: true,
							append_tooltip: " web taxes",
						},
						{
							label: "POS Net Profit",
							data: chart_data.pos_net_profit,
							color: '<?= $this->chart_colours['pos_net_profit'] ?>',
							lines: {
								fillColor: '<?= $this->chart_colours['pos_net_profit'] ?>',
								fill: false,
								show: true,
								lineWidth: 2,
								barWidth: <?= $this->barwidth ?>
								* 0.5,
								align: 'center'
							},
							points: {
								show: true,
								lineWidth: 2,
								radius: 5,
								fill: true,
								fillColor: '#fff'
							},
							yaxis: 2,
							shadowSize: 0,
							enable_tooltip: true,
							append_tooltip: " pos net profit",
						},
						{
							label: "Web Net Profit",
							data: chart_data.web_net_profit,
							color: '<?= $this->chart_colours['web_net_profit'] ?>',
							lines: {
								fillColor: '<?= $this->chart_colours['web_net_profit'] ?>',
								fill: false,
								show: true,
								lineWidth: 2,
								barWidth: <?= $this->barwidth ?>
								* 0.5,
								align: 'center'
							},
							points: {
								show: true,
								lineWidth: 2,
								radius: 5,
								fill: true,
								fillColor: '#fff'
							},
							yaxis: 2,
							shadowSize: 0,
							enable_tooltip: true,
							append_tooltip: " web net profit",
						},
					];

					if (highlight !== 'undefined' && series[highlight]) {
						highlight_series = series[highlight];

						highlight_series.color = '#9c5d90';

						if (highlight_series.bars)
							highlight_series.bars.fillColor = '#9c5d90';

						if (highlight_series.lines) {
							highlight_series.lines.lineWidth = 5;
						}
					}

					main_chart = jQuery.plot(
						jQuery('.chart-placeholder.main'),
						series,
						{
							legend: {
								show: false
							},
							grid: {
								color: '#aaa',
								borderColor: 'transparent',
								borderWidth: 0,
								hoverable: true
							},
							xaxes: [{
								color: '#aaa',
								position: "bottom",
								tickColor: 'transparent',
								mode: "time",
								timeformat: "<?= 'day' === $this->chart_groupby ? '%d %b' : '%b' ?>",
								monthNames: <?= json_encode(array_values($wp_locale->month_abbrev)) ?>,
								tickLength: 1,
								minTickSize: [1, "<?= $this->chart_groupby ?>"],
								tickSize: [1, "<?= $this->chart_groupby ?>"],
								font: {
									color: "#aaa"
								}
							}],
							yaxes: [
								{
									min: 0,
									minTickSize: 1,
									tickDecimals: 0,
									color: "#d4d9dc",
									font: { color: "#aaa" }
								},
								{
									position: "right",
									min: 0,
									tickDecimals: 2,
									alignTicksWithAxis: 1,
									color: "transparent",
									font: { color: "#aaa" }
								}
							],
						}
					);
					jQuery('.chart-placeholder').resize();
				}

				drawGraph();

				jQuery('.highlight_series').hover(
					function () {
						drawGraph(jQuery(this).data('series'));
					},
					function () {
						drawGraph();
					}
				);
			});
		</script>
		<?php
	}
}
