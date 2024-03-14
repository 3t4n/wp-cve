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
 * @var  boolean              $legend  True to display the legend, false otherwise.
 */
extract($displayData);

// include chart JS dependencies
JHtml::fetch('vaphtml.assets.chartjs');

// get list of preset colors
$colors = JHtml::fetch('vaphtml.color.preset', $list = true, $group = false);

JText::script('VAP_N_RESERVATIONS');
JText::script('VAP_N_RESERVATIONS_1');

?>

<div class="canvas-align-bottom services-employees-chart" id="services-employees-chart-<?php echo $widget->getID(); ?>">
	<canvas></canvas>
</div>

<script>

	/**
	 * Defines a pool of charts, if undefined.
	 *
	 * @var object
	 */
	if (typeof SERVICES_EMPLOYEES_CHARTS === 'undefined') {
		var SERVICES_EMPLOYEES_CHARTS = {};
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

			// start from first position of the preset
			let colorIndex = 0;

			// retrieve selected service name
			var serviceName = $('select[name="<?php echo $widget->getName() . '_' . $widget->getID(); ?>_service"]')
				.find('option[value="' + config.service + '"]')
					.text().trim();

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

				return value;
			}

			/**
			 * Create callback used to format the title displayed
			 * with the tooltips of the hovered points, according
			 * to the saved configuration.
			 *
			 * @param 	mixed 	items  The items to display.
			 * @param 	mixed 	data   The chart data.
			 *
			 * @return 	string  The formatted value.
			 */
			const formatPointTitle = (items, data) => {
				if (serviceName) {
					// build title using the format [SERVICE NAME] - [MONTH]
					return serviceName + ' - ' + items[0].label;
				}

				return items[0].label;
			};

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

				// extract service name from dataset
				let service = data.datasets[item.datasetIndex].label;

				// format as currency in case of earning
				if (config.valuetype == 'total') {
					label = Currency.getInstance().format(item.value);
				}
				// format number of orders
				else
				{
					var count = parseInt(item.value);
					var langk = 'VAP_N_RESERVATIONS';

					// format label by fetching singular/plural form
					if (count == 1) {
						label = Joomla.JText._(langk + '_1');
					} else {
						label = Joomla.JText._(langk).replace(/%d/, count);
					}
				}

				if (service) {
					label = service + ': ' + label;
				}

				return ' ' + label;
			}

			// in case the chart already exists and the config type doesn't match, unset the cached chart, because
			// we have to manually recreate it, as the chart type have been changed from line to bar or viceversa
			if (SERVICES_EMPLOYEES_CHARTS.hasOwnProperty(id) && SERVICES_EMPLOYEES_CHARTS[id].config.type != config.chart) {
				SERVICES_EMPLOYEES_CHARTS[id].destroy();
				delete SERVICES_EMPLOYEES_CHARTS[id];
			}

			// prepare chart data
			var chartData = {
				labels: Object.keys(data),
				datasets: {},
			};

			// iterate all dates
			$.each(data, (lbl, employees) => {
				// iterate all employees
				$.each(employees, (id_employee, employee) => {
					// check whether we already created the dataset for this employee
					if (!chartData.datasets.hasOwnProperty(id_employee)) {
						// get progressive color
						employee.color = colorsPreset[colorIndex++ % colorsPreset.length];

						if (config.chart == 'bar') {
							// nope, init data set
							chartData.datasets[id_employee] = {
								// the label string that appears when hovering the mouse above the lines intersection points
								label: employee.name,
								// the background color drawn behind the line (99 = 60% opacity)
								backgroundColor: "#" + employee.color + "99",
								// the fill color of the line
								borderColor: "#" + employee.color,
								// the line dataset
								data: [],
							};
						} else {
							// nope, init data set
							chartData.datasets[id_employee] = {
								// the label string that appears when hovering the mouse above the lines intersection points
								label: employee.name,
								// the background color drawn behind the line (33 = 20% opacity)
								backgroundColor: "#" + employee.color + "33",
								// the fill color of the line
								borderColor: "#" + employee.color,
								// the fill color of the points
								pointBackgroundColor: "#" + employee.color,
								// the border color of the points
								pointBorderColor: "#fff",
								// the radius of the points (in pixel)
								pointRadius: 4,
								// the fill color of the points when hovered
								pointHoverBackgroundColor: "#fff",
								// the border color of the points when hovered
								pointHoverBorderColor: "#" + employee.color,
								// the radius of the points (in pixel) when hovered
								pointHoverRadius: 5,
								// the line dataset
								data: [],
							};
						}
					}

					// include value within the dataset
					chartData.datasets[id_employee].data.push(employee[config.valuetype]);
				});
			});

			// convert datasets into a linear array
			chartData.datasets = Object.values(chartData.datasets);

			// init chart from scratch if NULL
			if (!SERVICES_EMPLOYEES_CHARTS.hasOwnProperty(id)) {
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
							// prepend service name before the tooltip title
							title: formatPointTitle,
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
				SERVICES_EMPLOYEES_CHARTS[id] = new Chart(ctx, {
					type:    config.chart,
					data:    chartData,
					options: options,
				});
			}
			// otherwise update labels and values
			else {
				// update chart data
				SERVICES_EMPLOYEES_CHARTS[id].data = chartData;

				// update format callbacks
				SERVICES_EMPLOYEES_CHARTS[id].options.scales.yAxes[0].ticks.callback = formatAxisY;
				SERVICES_EMPLOYEES_CHARTS[id].options.tooltips.callbacks.title = formatPointTitle;
				SERVICES_EMPLOYEES_CHARTS[id].options.tooltips.callbacks.label = formatPointTooltip;

				// refresh chart
				SERVICES_EMPLOYEES_CHARTS[id].update();
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
					$('#services-employees-chart-<?php echo $widget->getID(); ?>'),
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
