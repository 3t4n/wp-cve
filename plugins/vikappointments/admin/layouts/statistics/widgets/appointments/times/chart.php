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

JText::script('VAP_N_RESERVATIONS');
JText::script('VAP_N_RESERVATIONS_1');

?>

<div class="canvas-align-bottom appointments-times-chart">
	<canvas></canvas>
</div>

<script>

	/**
	 * Defines a pool of charts, if undefined.
	 *
	 * @var object
	 */
	if (typeof APPOINTMENTS_TIMES_CHARTS === 'undefined') {
		var APPOINTMENTS_TIMES_CHARTS = {};
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

			// in case the chart already exists and the config type doesn't match, unset the cached chart, because
			// we have to manually recreate it, as the chart type have been changed from line to bar or viceversa
			if (APPOINTMENTS_TIMES_CHARTS.hasOwnProperty(id) && APPOINTMENTS_TIMES_CHARTS[id].config.type != config.chart) {
				APPOINTMENTS_TIMES_CHARTS[id].destroy();
				delete APPOINTMENTS_TIMES_CHARTS[id];
			}

			// prepare chart data
			var chartData = {
				labels: Object.keys(data).map((elem) => {
					// remove literal added to keep the correct ordering of the object keys
					return elem.replace(/^H/, '');
				}),
				datasets: [],
			};

			chartData.datasets.push({
				// the label string that appears when hovering the mouse above the lines intersection points
				label: "Dataset",
				// the background color drawn behind the line (99 = 60% opacity)
				backgroundColor: "#" + config.color + "99",
				// the fill color of the line
				borderColor: "#" + config.color,
				// the line dataset
				data: Object.values(data).map((elem) => {
					if (config.chart == 'radar') {
						return Math.round(elem.percent);
					}

					return elem.count;
				}),
			});

			// init chart from scratch if NULL
			if (!APPOINTMENTS_TIMES_CHARTS.hasOwnProperty(id)) {
				var options;

				if (config.chart == 'bar') {
					// prepare chart configuration
					options = {
						// axes handling
						scales: {
							// Y Axis properties
							yAxes: [{
								// make sure the chart starts at 0
								ticks: {
									beginAtZero: true,
								},
							}],
						},
						// tooltip handling
						tooltips: {
							// tooltip callbacks are used to customize default texts
							callbacks: {
								// format the tooltip text displayed when hovering a point
								label: (item, data) => {
									var label = '';

									var count = parseInt(item.value);
									var langk = 'VAP_N_RESERVATIONS';

									// format label by fetching singular/plural form
									if (count == 1) {
										label = Joomla.JText._(langk + '_1');
									} else {
										label = Joomla.JText._(langk).replace(/%d/, count);
									}

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
					};
				} else {
					// prepare chart configuration
					options = {
						// tooltip handling
						tooltips: {
							// tooltip callbacks are used to customize default texts
							callbacks: {
								// format the hour title displayed above the point
								title: (item, data) => {
									if (item[0].value == 0) {
										// do not show title for null items
										return '';
									}

									return data.labels[item[0].index];
								},
								// format the tooltip text displayed when hovering a point
								label: (item, data) => {
									return Math.round(item.value) + '%';
								},
							},
						},
					};
				}

				// turn off legend
				options.legend = {
					display: false,
				};

				// unset duration in case we are exporting the chart
				options.animation = {
					duration: <?php echo isset($data) ? 0 : 1000; ?>,
				};

				// get 2D canvas for LINE chart
				var canvas = $(widget).find('canvas')[0];
				var ctx    = canvas.getContext('2d');

				// init chart from scratch if undefined
				APPOINTMENTS_TIMES_CHARTS[id] = new Chart(ctx, {
					type:    config.chart,
					data:    chartData,
					options: options,
				});
			}
			// otherwise update labels and values
			else {
				// update chart data
				APPOINTMENTS_TIMES_CHARTS[id].data = chartData;

				// refresh chart
				APPOINTMENTS_TIMES_CHARTS[id].update();
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
					$('.appointments-times-chart'),
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
