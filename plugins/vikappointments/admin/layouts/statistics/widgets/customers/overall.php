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
 */
extract($displayData);

// include chart JS dependencies
JHtml::fetch('vaphtml.assets.chartjs');

JText::script('VAP_N_RESERVATIONS');
JText::script('VAP_N_RESERVATIONS_1');

?>

<style>

	.canvas-align-center {
		height: calc(100% - 20px) !important;
	}

</style>

<div class="canvas-align-center customers-overall-chart">
	<canvas></canvas>
</div>

<div class="no-results" style="display: none;">
	<?php echo VAPApplication::getInstance()->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS')); ?>
</div>

<div class="widget-floating-box">

	<span class="badge badge-info pull-left range"></span>

	<span class="badge badge-info pull-left datefrom" style="margin-right: 4px;"></span>
	<span class="badge badge-important pull-left dateto"></span>

</div>

<script>

	/**
	 * Defines a pool of charts, if undefined.
	 *
	 * @var object
	 */
	if (typeof CUSTOMERS_OVERALL_CHART === 'undefined') {
		var CUSTOMERS_OVERALL_CHART = {};
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
		 * @param 	mixed 	widget  The widget selector.
		 * @param 	mixed   data    The AJAX response.
		 * @param 	object  config  The widget configuration.
		 *
		 * @return 	void
		 */
		WIDGET_CALLBACKS[<?php echo $widget->getID(); ?>] = (widget, data, config) => {
			// get widget ID
			var id = $(widget).attr('id');

			if ($.isEmptyObject(data)) {
				// show "no results" box in case of empty list
				$(widget).find('.canvas-align-center.customers-overall-chart').hide();
				$(widget).find('.no-results').show();

				// we don't need to go ahead
				return;
			}

			// hide "no results" box if there is at least a status
			$(widget).find('.no-results').hide();
			$(widget).find('.canvas-align-center.customers-overall-chart').show();

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
				// keep default label
				var label = data.labels[item.index] || '';

				<?php
				if (!isset($data))
				{
					?>
					if (label) {
						label += ': ';
					}


					let value = data.datasets[item.datasetIndex].data[item.index];

					// format as currency in case of earning
					if (config.valuetype == 'total') {
						label += Currency.getInstance().format(value);
					}
					// format number of orders
					else
					{
						var count = parseInt(value);
						var langk = 'VAP_N_RESERVATIONS';

						// format label by fetching singular/plural form
						if (count == 1) {
							label += Joomla.JText._(langk + '_1');
						} else {
							label += Joomla.JText._(langk).replace(/%d/, count);
						}
					}
					<?php
				}
				?>

				return ' ' + label;
			}

			// prepare chart data
			var chartData = {
				// dataset options
				datasets: [{
					// dataset values
					data: [],
					// dataset color
					backgroundColor: [],
					hoverBorderColor: [],
				}],
				// dataset labels
				labels: [],
			};

			$.each(data, (id_user, customer) => {
				var label = customer.name;

				<?php
				if (isset($data))
				{
					?>
					let value;

					if (config.valuetype == 'total') {
						value = Currency.getInstance().format(customer.total);
					} else {
						// display number of appointments if we are printing the document
						value = customer.count;
					}

					label += ' (' + value + ')';
					<?php
				}
				?>

				// push status count
				chartData.datasets[0].data.push(customer[config.valuetype]);
				// hide highlight on hover
				chartData.datasets[0].hoverBorderColor.push('#0000');
				// push label
				chartData.labels.push(label);
				// fetch background color
				chartData.datasets[0].backgroundColor.push('#' + customer.color);
			});

			// init chart from scratch if NULL
			if (!CUSTOMERS_OVERALL_CHART.hasOwnProperty(id)) {
				// prepare chart configuration
				var options = {
					// hide legend
					legend: {
						// draw legend only if we are printing the document
						display: <?php echo isset($data) ? 'true' : 'false'; ?>,
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
									backgroundColor: meta.backgroundColor[tooltipItem.index],
								};
							},
						},
					},
					animation: {
						// unset duration in case we are exporting the chart
						duration: <?php echo isset($data) ? 0 : 1000; ?>,
					},
				};
				
				// get 2D canvas for DOUGHNUT chart
				var canvas = $(widget).find('canvas')[0];
				var ctx    = canvas.getContext('2d');

				// init chart from scratch if undefined
				CUSTOMERS_OVERALL_CHART[id] = new Chart(ctx, {
					type:    'pie',
					data:    chartData,
					options: options,
				});
			} else {
				// update chart data
				CUSTOMERS_OVERALL_CHART[id].data = chartData;

				// update format callbacks
				CUSTOMERS_OVERALL_CHART[id].options.tooltips.callbacks.label = formatPointTooltip;

				// refresh chart
				CUSTOMERS_OVERALL_CHART[id].update();
			}

			// update badges
			if (config.datefrom || config.dateto) {
				// at least a date was selected, show "from" and "to"
				$(widget).find('.badge.datefrom').text(config.datefrom ? config.datefrom : '--');
				$(widget).find('.badge.dateto').text(config.dateto ? config.dateto : '--');

				// hide default range
				$(widget).find('.badge.range').text('');
			} else {
				// hide both empty dates
				$(widget).find('.badge.datefrom').text('');
				$(widget).find('.badge.dateto').text('');

				// retrieve selected range text
				var range = $('select[name="<?php echo $widget->getName() . '_' . $widget->getID(); ?>_range"]')
					.find('option[value="' + config.range + '"]')
						.text();

				// show range
				$(widget).find('.badge.range').text(range);
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
					$('.customers-overall-chart'),
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
