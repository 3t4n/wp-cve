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

$params = $this->params;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigGlobalCalendars". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('GlobalCalendars');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">

		<!-- CALENDAR LAYOUT - Select -->

		<?php
		$options = array(
			JHtml::fetch('select.option', 'weekly', 'VAPFREQUENCYTYPE1'),
			JHtml::fetch('select.option', 'monthly', 'VAPFREQUENCYTYPE2'),
		);	
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG124'),
			'content' => JText::translate('VAPMANAGECONFIG124_DESC'),
		)); 

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG124') . $help); ?>
			<select name="calendarlayoutsite" class="medium">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['calendarlayoutsite'], true); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- NUMBER OF CALENDARS - Select -->

		<?php
		$options = array();
		
		for ($mon = 1; $mon <= 12; $mon++)
		{
			$options[] = JHtml::fetch('select.option', $mon, $mon);
		}

		$monthlyControl = array();
		$monthlyControl['style'] = $params['calendarlayoutsite'] == 'monthly' ? '' : 'display:none;';
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG19'),
			'content' => JText::translate('VAPMANAGECONFIG19_DESC'),
		)); 

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG19') . $help, 'monthly-control', $monthlyControl); ?>
			<select name="numcals" class="short">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['numcals']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- NUMBER OF DAYS - Select -->

		<?php
		$options = array();
		
		for ($day = 1; $day <= 7; $day++)
		{
			$options[] = JHtml::fetch('select.option', $day, $day);
		}

		$weeklyControl = array();
		$weeklyControl['style'] = $params['calendarlayoutsite'] == 'weekly' ? '' : 'display:none;';
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG125'),
			'content' => JText::translate('VAPMANAGECONFIG125_DESC'),
		)); 

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG125') . $help, 'weekly-control', $weeklyControl); ?>
			<select name="calendarweekdays" class="short">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['calendarweekdays']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- NUMBER OF MONTHS - Select -->

		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG26'),
			'content' => JText::translate('VAPMANAGECONFIG26_DESC'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG26') . $help); ?>
			<select name="nummonths" class="short">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['nummonths']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- SHOW CALENDARS FROM - Number -->

		<?php
		$months = JHtml::fetch('vikappointments.months');
		$years = JHtml::fetch('vikappointments.years', -10, 10);

		if (empty($params['calsfromyear']))
		{
			$params['calsfromyear'] = (int) date('Y');
		}

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG56'),
			'content' => JText::translate('VAPMANAGECONFIG57'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG56') . $help); ?>
			<div class="inline-fields">
				<select name="calsfrom" class="small-medium">
					<?php echo JHtml::fetch('select.options', $months, 'value', 'text', $params['calsfrom']); ?>
				</select>

				<select name="calsfromyear" class="small-medium">
					<?php echo JHtml::fetch('select.options', $years, 'value', 'text', $params['calsfromyear']); ?>
				</select>
			</div>
		<?php echo $vik->closeControl(); ?>
		
		<!-- DAYS LEGEND - Select -->

		<?php
		$yes = $vik->initRadioElement('', '', $params['legendcal'] == 1);
		$no  = $vik->initRadioElement('', '', $params['legendcal'] == 0);
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG68'), 'monthly-control', $monthlyControl);
		echo $vik->radioYesNo('legendcal', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- FIRST WEEK DAY - Select -->

		<?php
		$days = JHtml::fetch('vikappointments.days');
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG94')); ?>
			<select name="firstday" class="small-medium">
				<?php echo JHtml::fetch('select.options', $days, 'value', 'text', $params['firstday']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigGlobalCalendars","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Global > Calendars > Calendars fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['basic']))
		{
			echo $forms['basic'];

			// unset details form to avoid displaying it twice
			unset($forms['basic']);
		}
		?>

	</div>
	
</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigGlobalCalendars","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Global > Calendars tab.
 *
 * @since 1.7
 */
foreach ($forms as $formTitle => $formHtml)
{
	?>
	<div class="config-fieldset">
		
		<div class="config-fieldset-head">
			<h3><?php echo JText::translate($formTitle); ?></h3>
		</div>

		<div class="config-fieldset-body">
			<?php echo $formHtml; ?>
		</div>
		
	</div>
	<?php
}
?>

<script>

	(function($) {
		'use strict';

		$(function() {
			$('select[name="calendarlayoutsite"]').on('change', function() {
				if ($(this).val() == 'weekly') {
					$('.monthly-control').hide();
					$('.weekly-control').show();
				} else {
					$('.weekly-control').hide();
					$('.monthly-control').show();
				}
			});
		});
	})(jQuery);

</script>
