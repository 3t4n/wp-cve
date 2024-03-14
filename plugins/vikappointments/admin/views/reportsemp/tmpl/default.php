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

JHtml::fetch('formbehavior.chosen');
JHtml::fetch('vaphtml.assets.chartjs');

$vik = VAPApplication::getInstance();

?>

<style>
	/**
	 * Hide summary badges from pie chart.
	 */
	.widget-floating-box {
		display: none;
	}
</style>

<script>

	/**
	 * A lookup of preflights to be used before refreshing
	 * the contents of the widgets.
	 *
	 * If needed, a widget can register its own callback
	 * to be executed before the AJAX request is started.
	 *
	 * The property name MUST BE equals to the ID of 
	 * the widget that is registering its callback.
	 *
	 * @var object
	 */
	var WIDGET_PREFLIGHTS = {};

	/**
	 * A lookup of callbacks to be used when refreshing
	 * the contents of the widgets.
	 *
	 * If needed, a widget can register its own callback
	 * to be executed once the AJAX request is completed.
	 *
	 * The property name MUST BE equals to the ID of 
	 * the widget that is registering its callback.
	 *
	 * @var object
	 */
	var WIDGET_CALLBACKS = {};

</script>

<form action="index.php" method="POST" name="adminForm" id="adminForm">

	<?php echo $vik->openCard(); ?>

		<div class="btn-toolbar" style="height: 32px;">

			<div class="btn-group pull-left">
				<?php echo $vik->calendar($this->filters['datefrom'], 'datefrom', 'vapstartdate'); ?>
			</div>

			<div class="btn-group pull-left">
				<?php echo $vik->calendar($this->filters['dateto'], 'dateto', 'vapenddate'); ?>
			</div>

			<?php
			$options = array(
				JHtml::fetch('select.option', 'total', JText::translate('VAPREPORTSVALUETYPEOPT1')),
				JHtml::fetch('select.option', 'count', JText::translate('VAPREPORTSVALUETYPEOPT2')),
			);
			?>

			<div class="btn-group pull-left">
				<select name="valuetype" id="vap-valuetype-sel">
					<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $this->filters['valuetype'])?>
				</select>
			</div>

			<?php
			$options = array(
				JHtml::fetch('select.option', 1, JText::translate('VAPDOWNLOADREPORTOPT1')),
				JHtml::fetch('select.option', 0, JText::translate('VAPDOWNLOADREPORTOPT2')),
			);
			?>

			<div class="btn-group pull-left">
				<select name="checkin" id="vap-checkin-sel">
					<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $this->filters['checkin'])?>
				</select>
			</div>

			<div class="btn-group pull-left">
				<button type="button" class="btn" id="submit-search">
					<?php echo JText::translate('VAPRESERVATIONBUTTONFILTER'); ?>
				</button>
			</div>

		</div>
		
		<div class="vap-charts-pool">
			
			<?php
			foreach ($this->employees as $employee)
			{
				echo $vik->openFieldset($employee->nickname); ?>
				
					<div class="vap-empchart-box">
						
						<div class="vap-linechart-container">
							<div class="vap-linechart-wrapper" id="widget-<?php echo $employee->lineChart->getID(); ?>" data-widget="<?php echo $this->escape($employee->lineChart->getName()); ?>">
								<?php echo $employee->lineChart->display(array('legend' => true)); ?>
							</div>
						</div>
						
						<div class="vap-piechart-container">
							<div class="vap-piechart-wrapper" id="widget-<?php echo $employee->pieChart->getID(); ?>" data-widget="<?php echo $this->escape($employee->pieChart->getName()); ?>">
								<?php echo $employee->pieChart->display(); ?>
							</div>
						</div>

						<input type="hidden" name="cid[]" value="<?php echo $employee->id; ?>" />
						
					</div>

				<?php
				echo $vik->closeFieldset();
			}
			?>

		</div>

	<?php echo $vik->closeCard(); ?>
	
	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="view" value="reportsemp" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="return" value="<?php echo $this->from; ?>" />

</form>

<script>

	(function($) {
		'use strict';

		/**
		 * Prepare any chart to be responsive.
		 */
		Chart.defaults.global.responsive = true;

		/**
		 * Updates the configuration of the last edited widget.
		 *
		 * @param 	integer  id      The widget ID.
		 * @param 	object   config  The widget configuration.
		 *
		 * @return 	void
		 */
		const updateWidgetContents = (id, config) => {
			// keep a reference to the widget
			var box = $('#widget-' + id);

			// get widget class
			var widget = box.data('widget');

			// prepare request data
			Object.assign(config, {
				id:     id,
				widget: widget,
				tmp:    true,
			});

			if (WIDGET_PREFLIGHTS.hasOwnProperty(id)) {
				// let the widget prepares the contents without
				// waiting for the request completion
				WIDGET_PREFLIGHTS[id](box, config);
			}

			// make request to load widget dataset
			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=analytics.loadwidgetdata'); ?>',
				config,
				(resp) => {
					// check if the widget registered its own update method
					if (WIDGET_CALLBACKS.hasOwnProperty(id)) {
						// let the widget callback finalizes the update
						WIDGET_CALLBACKS[id](box, resp, config);
					} else {
						if (typeof resp === 'string') {
							// replace widget body with returned string/HTML
							$(box).html(resp);
						}
					}
				},
				(error) => {
					// do nothing on error
				}
			);
		}

		$('#submit-search').on('click', () => {
			// manually clear task for a correct refresh
			document.adminForm.task.value = '';
			// refresh form on button click
			Joomla.submitbutton('');
		});

		$(function() {
			VikRenderer.chosen('.btn-toolbar');

			<?php
			// iterate dashboard widgets
			foreach ($this->employees as $employee)
			{
				?>
				// load line chart
				updateWidgetContents('<?php echo $employee->lineChart->getID(); ?>', <?php echo json_encode($employee->lineChart->getOptions()); ?>);
				// load pie chart
				updateWidgetContents('<?php echo $employee->pieChart->getID(); ?>', <?php echo json_encode($employee->pieChart->getOptions()); ?>);
				<?php
			}
			?>
		});
	})(jQuery);
	
</script>
