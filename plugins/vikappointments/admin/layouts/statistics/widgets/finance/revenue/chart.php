<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Layout variables
 * -----------------
 * @var  VAPStatisticsWidget  $widget  The instance of the widget to be displayed.
 * @var  mixed                $data    The chart data to immediately display.
 */
extract($displayData);

// include chart JS dependencies
JHtml::fetch('vaphtml.assets.chartjs');

JText::script('VAP_N_ORDERS');
JText::script('VAP_N_ORDERS_1');

?>

<div class="canvas-align-bottom finance-revenue-chart">
	<canvas></canvas>
</div>

<script>

	/**
	 * Defines a pool of charts, if undefined.
	 *
	 * @var object
	 */
	if (typeof FINANCE_REVENUE_CHARTS === 'undefined') {
		var FINANCE_REVENUE_CHARTS = {};
	}

	// init callbacks pool in case the caller didn't declare it
	if (typeof WIDGET_CALLBACKS === 'undefined') {
		var WIDGET_CALLBACKS = {};
	}

	(function($) {
		'use strict';

		/**
		 * Register callback to be executed after
		 * completing the update request.
		 *
		 * @param 	mixed   widget  The widget selector.
		 * @param 	mixed   data    The AJAX response.
		 * @param 	object  config  The widget configuration.
		 *
		 * @return 	void
		 */
		WIDGET_CALLBACKS[<?php echo $widget->getID(); ?>] = (widget, data, config) => {
			// get widget ID
			var id = $(widget).attr('id');

			// fetch color
			if (config.color && config.color.match(/^#?[0-9a-f]{6,6}$/i))
			{
				// use specified color
				config.color = config.color.replace(/^#/, '');
			}
			else
			{
				// use default one
				config.color = 'e68714';
			}

			/**
			 * Create callback used to format the values displayed
			 * on the Y axis, according to the saved configuration.
			 *
			 * @param 	mixed 	value  The value passed by the chart.
			 *
			 * @return 	string  The formatted value.
			 */
			const formatAxisY = (value) => {
				// format as currency in case of earning
				if (config.valuetype == 'total') {
					// do not display decimal values on Y axis
					return Currency.getInstance().format(value, 0);
				}

				// otherwise just return the plain number
				return value;
			}

			/**
			 * Create callback used to format the values displayed
			 * with the tooltips of the hovered points, according
			 * to the saved configuration.
			 *
			 * @param 	mixed 	item  The item to display.
			 * @param 	mixed 	data  The chart data.
			 *
			 * @return 	string  The formatted value.
			 */
			const formatPointTooltip = (item, data) => {
				var label = '';

				// format as currency in case of earning
				if (config.valuetype == 'total') {
					label = Currency.getInstance().format(item.value);
				}
				// format number of orders
				else
				{
					var count = parseInt(item.value);
					var langk = 'VAP_N_ORDERS';

					// format label by fetching singular/plural form
					if (count == 1) {
						label = Joomla.JText._(langk + '_1');
					} else {
						label = Joomla.JText._(langk).replace(/%d/, count);
					}
				}

				return ' ' + label;
			}

			// in case the chart already exists and the config type doesn't match, unset the cached chart, because
			// we have to manually recreate it, as the chart type have been changed from line to bar or viceversa
			if (FINANCE_REVENUE_CHARTS.hasOwnProperty(id) && FINANCE_REVENUE_CHARTS[id].config.type != config.chart) {
				FINANCE_REVENUE_CHARTS[id].destroy();
				delete FINANCE_REVENUE_CHARTS[id];
			}

			// prepare chart data
			var chartData = {
				labels: Object.keys(data),
				datasets: [],
			};

			if (config.chart == 'bar') {
				chartData.datasets.push({
					// the label string that appears when hovering the mouse above the lines intersection points
					label: "Dataset",
					// the background color drawn behind the line (99 = 60% opacity)
					backgroundColor: "#" + config.color + "99",
					// the fill color of the line
					borderColor: "#" + config.color,
					// the line dataset
					data: Object.values(data),
				});
			} else {
				chartData.datasets.push({
					// the label string that appears when hovering the mouse above the lines intersection points
					label: "Dataset",
					// the background color drawn behind the line (33 = 20% opacity)
					backgroundColor: "#" + config.color + "33",
					// the fill color of the line
					borderColor: "#" + config.color,
					// the fill color of the points
					pointBackgroundColor: "#" + config.color,
					// the border color of the points
					pointBorderColor: "#fff",
					// the radius of the points (in pixel)
					pointRadius: 4,
					// the fill color of the points when hovered
					pointHoverBackgroundColor: "#fff",
					// the border color of the points when hovered
					pointHoverBorderColor: "#" + config.color,
					// the radius of the points (in pixel) when hovered
					pointHoverRadius: 5,
					// the line dataset
					data: Object.values(data),
				});
			}

			// init chart from scratch if NULL
			if (!FINANCE_REVENUE_CHARTS.hasOwnProperty(id)) {
				// prepare chart configuration
				var options = {
					// turn off legend
					legend: {
						display: false,
					},
					// axes handling
					scales: {
						// Y Axis properties
						yAxes: [{
							// make sure the chart starts at 0
							ticks: {
								// format value as currency
								callback: formatAxisY,
								beginAtZero: true,
							},
						}],
					},
					// tooltip handling
					tooltips: {
						// tooltip callbacks are used to customize default texts
						callbacks: {
							// format the tooltip text displayed when hovering a point
							label: formatPointTooltip,
							// change label colors because, by default, the legend background is blank
							labelColor: (tooltipItem, chart) => {
								// get tooltip item meta data
								var meta = chart.data.datasets[tooltipItem.datasetIndex];

								return {
									// use white border
									borderColor: 'rgb(0,0,0)',
									// use same item background color
									backgroundColor: meta.borderColor,
								};
							},
						},
					},
					animation: {
						// unset duration in case we are exporting the chart
						duration: <?php echo isset($data) ? 0 : 1000; ?>,
					},
				};

				// get 2D canvas for LINE chart
				var canvas = $(widget).find('canvas')[0];
				var ctx    = canvas.getContext('2d');

				// init chart from scratch if undefined
				FINANCE_REVENUE_CHARTS[id] = new Chart(ctx, {
					type:    config.chart,
					data:    chartData,
					options: options,
				});
			}
			// otherwise update labels and values
			else {
				// update chart data
				FINANCE_REVENUE_CHARTS[id].data = chartData;

				// update format callbacks
				FINANCE_REVENUE_CHARTS[id].options.scales.yAxes[0].ticks.callback = formatAxisY;
				FINANCE_REVENUE_CHARTS[id].options.tooltips.callbacks.label = formatPointTooltip;

				// refresh chart
				FINANCE_REVENUE_CHARTS[id].update();
			}
		}

		<?php

		////////////////////////
		///// EXPORT UTILS /////
		////////////////////////

		if (isset($data))
		{
			?>
			$(function() {
				onInstanceReady(() => {
					// Delay execution until the currency has been declared.
					// This because the latest versions of WordPress seems to load the
					// dashboard widgets after sending the headers.
					return typeof Currency !== 'undefined' && Currency.getInstance().symbol;
				}).then(() => {
					// auto-invoke callback on page loading completion
					WIDGET_CALLBACKS[<?php echo $widget->getID(); ?>](
						// exported assume, there should be only one widget displayed
						$('.finance-revenue-chart'),
						// JSON-encode the passed chart data
						<?php echo json_encode($data); ?>,
						// JSON-encode the widget configuration
						<?php echo json_encode($widget->getOptions()); ?>
					);
				});
			});
			<?php
		}
		?>
	})(jQuery);

</script>
