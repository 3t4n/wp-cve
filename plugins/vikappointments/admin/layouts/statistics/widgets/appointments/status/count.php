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

$codes = array();

// create lookup of status codes
foreach (JHtml::fetch('vaphtml.status.find', array('code', 'name', 'color'), array('appointments' => 1)) as $code)
{
	$codes[$code->code] = $code;
}

JText::script('VAP_N_RESERVATIONS');
JText::script('VAP_N_RESERVATIONS_1');

?>

<style>

	.canvas-align-center {
		height: calc(100% - 20px) !important;
	}

</style>

<div class="canvas-align-center appointments-status-count">
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
	if (typeof APPOINTMENTS_STATUS_COUNT === 'undefined') {
		var APPOINTMENTS_STATUS_COUNT = {};
	}

	// init callbacks pool in case the caller didn't declare it
	if (typeof WIDGET_CALLBACKS === 'undefined') {
		var WIDGET_CALLBACKS = {};
	}

	(function($) {
		'use strict';

		// create lookup of status codes
		const statusCodes = <?php echo json_encode($codes); ?>;

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
		WIDGET_CALLBACKS[<?php echo $widget->getID(); ?>] = function(widget, data, config) {
			// get widget ID
			var id = $(widget).attr('id');

			if ($.isEmptyObject(data)) {
				// show "no results" box in case of empty list
				$(widget).find('.canvas-align-center.appointments-status-count').hide();
				$(widget).find('.no-results').show();
			} else {
				// hide "no results" box if there is at least a status
				$(widget).find('.no-results').hide();
				$(widget).find('.canvas-align-center.appointments-status-count').show();
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

			$.each(data, (k, v) => {
				// make sure the status code is supported
				if (statusCodes.hasOwnProperty(k)) {
					var label = statusCodes[k].name;

					<?php
					if (isset($data))
					{
						?>
						// display number of appointments if we are printing the document
						label += ' (' + v + ')';
						<?php
					}
					?>

					// push status count
					chartData.datasets[0].data.push(v);
					// hide highlight on hover
					chartData.datasets[0].hoverBorderColor.push('#0000');
					// push label
					chartData.labels.push(label);
					// fetch background color
					chartData.datasets[0].backgroundColor.push('#' + statusCodes[k].color);
				}
			});

			// init chart from scratch if NULL
			if (!APPOINTMENTS_STATUS_COUNT.hasOwnProperty(id)) {
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
							label: (tooltipItem, data) => {
								// keep default label
								var label = data.labels[tooltipItem.index] || '';

								<?php
								if (!isset($data))
								{
									?>
									// display formatted appointments only if we are NOT printing the document
									if (label) {
										label += ': ';

										var count = parseInt(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]);
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
							},
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
					// the percentage of the chart that is cut out of the middle
					cutoutPercentage: 70,
				};
				
				// get 2D canvas for DOUGHNUT chart
				var canvas = $(widget).find('canvas')[0];
				var ctx    = canvas.getContext('2d');

				// init chart from scratch if undefined
				APPOINTMENTS_STATUS_COUNT[id] = new Chart(ctx, {
					type:    'doughnut',
					data:    chartData,
					options: options,
				});
			} else {
				// update chart data
				APPOINTMENTS_STATUS_COUNT[id].data = chartData;

				// refresh chart
				APPOINTMENTS_STATUS_COUNT[id].update();
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
					$('.appointments-status-count'),
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
