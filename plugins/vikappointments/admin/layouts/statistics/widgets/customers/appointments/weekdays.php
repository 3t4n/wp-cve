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

// get list of preset colors
$colors = JHtml::fetch('vaphtml.color.preset', $list = true, $group = false);

JText::script('VAP_N_RESERVATIONS');
JText::script('VAP_N_RESERVATIONS_1');

?>

<div class="canvas-align-bottom customers-weekdays-chart">
	<canvas></canvas>
</div>

<div class="no-results" style="display:none;">
	<?php echo VAPApplication::getInstance()->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS')); ?>
</div>

<script>

	/**
	 * Defines a pool of charts, if undefined.
	 *
	 * @var object
	 */
	if (typeof CUSTOMERS_WEEKDAYS_CHARTS === 'undefined') {
		var CUSTOMERS_WEEKDAYS_CHARTS = {};
	}

	// init callbacks pool in case the caller didn't declare it
	if (typeof WIDGET_CALLBACKS === 'undefined') {
		var WIDGET_CALLBACKS = {};
	}

	(function($) {
		'use strict';

		// get default system preset of colors
		const colorsPreset = <?php echo json_encode($colors); ?>;

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

			if (!data) {
				// show "no results" box in case of empty list
				$(widget).find('.customers-weekdays-chart').hide();
				$(widget).find('.no-results').show();

				// do not need to go ahead
				return false;
			}

			// hide "no results" box if there is at least a status
			$(widget).find('.no-results').hide();
			$(widget).find('.customers-weekdays-chart').show();

			// start from first position of the preset
			let colorIndex = 0;

			// prepare chart data
			var chartData = {
				labels: Object.keys(data),
				datasets: {},
			};

			let suggestedMax = 0;

			// iterate all dates
			$.each(data, (lbl, customers) => {
				// iterate all coupons
				$.each(customers, (id_user, customer) => {
					// check whether we already created the dataset for this coupon code
					if (!chartData.datasets.hasOwnProperty(id_user)) {
						// get progressive color
						let color = colorsPreset[colorIndex++ % colorsPreset.length];

						chartData.datasets[id_user] = {
							// the label string that appears when hovering the mouse above the lines intersection points
							label: customer.name,
							// the background color drawn behind the line (99 = 60% opacity)
							backgroundColor: "#" + color + "99",
							// the fill color of the line
							borderColor: "#" + color,
							// the line dataset
							data: [],
						};
					}

					// include value within the dataset
					chartData.datasets[id_user].data.push(customer.total);

					// take maximum amount
					suggestedMax = Math.max(suggestedMax, parseInt(customer.total) + 1);
				});
			});

			// convert datasets into a linear array
			chartData.datasets = Object.values(chartData.datasets);

			// init chart from scratch if NULL
			if (!CUSTOMERS_WEEKDAYS_CHARTS.hasOwnProperty(id)) {
				// prepare chart configuration
				var options = {
					legend: {
						// draw legend only if explicitly requested
						display: <?php echo !empty($legend) ? 'true' : 'false'; ?>,
					},
					// axes handling
					scales: {
						// Y Axis properties
						yAxes: [{
							ticks: {
								// make sure the chart starts at 0
								beginAtZero: true,
								// ignore real numbers
								precision: 0,
								// increase ceil by one for a better readability
								suggestedMax: suggestedMax,
							},
						}],
					},
					// tooltip handling
					tooltips: {
						// tooltip callbacks are used to customize default texts
						callbacks: {
							// format the tooltip text displayed when hovering a point
							label: (item, data) => {
								// extract customer name from dataset
								let customer = data.datasets[item.datasetIndex].label;

								let label = '';

								var count = parseInt(item.value);
								var langk = 'VAP_N_RESERVATIONS';

								// format label by fetching singular/plural form
								if (count == 1) {
									label = Joomla.JText._(langk + '_1');
								} else {
									label = Joomla.JText._(langk).replace(/%d/, count);
								}

								if (customer) {
									label = customer + ': ' + label;
								}

								// create label in the form "COUPON: TOTAL"
								return ' ' + label;
							},
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
				CUSTOMERS_WEEKDAYS_CHARTS[id] = new Chart(ctx, {
					type:    'bar',
					data:    chartData,
					options: options,
				});
			}
			// otherwise update labels and values
			else {
				// update chart data
				CUSTOMERS_WEEKDAYS_CHARTS[id].data = chartData;

				// refresh suggested max
				CUSTOMERS_WEEKDAYS_CHARTS[id].options.scales.yAxes[0].ticks.suggestedMax = suggestedMax;

				// refresh chart
				CUSTOMERS_WEEKDAYS_CHARTS[id].update();
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
				// auto-invoke callback on page loading completion
				WIDGET_CALLBACKS[<?php echo $widget->getID(); ?>](
					// exported assume, there should be only one widget displayed
					$('.customers-weekdays-chart'),
					// JSON-encode the passed chart data
					<?php echo json_encode($data); ?>,
					// JSON-encode the widget configuration
					<?php echo json_encode($widget->getOptions()); ?>
				);
			});
			<?php
		}
		?>
	})(jQuery);

</script>
