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

JHtml::fetch('vaphtml.assets.select2');

$closure = $this->closure;

$vik = VAPApplication::getInstance();

$times = JHtml::fetch('vikappointments.times', array(
	'step'  => 5,
	'value' => 'int',
));

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewClosure".
 * It is also possible to use "onDisplayViewClosureSidebar"
 * to include any additional fieldsets within the right sidebar.
 * The event method receives the view instance as argument.
 *
 * @since 1.7
 */
$detailsForms = $this->onDisplayView();
$sidebarForms = $this->onDisplayView('Sidebar');

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">

	<?php echo $vik->openCard(); ?>

		<div class="span7">
	
			<?php echo $vik->openFieldset($sidebarForms ? JText::translate('VAPCUSTFIELDSLEGEND1') : ''); ?>

				<!-- EMPLOYEES - Select -->

				<?php
				echo $vik->openControl(JText::translate('VAPMANAGECUSTOMF10') . '*');

				// load employees and group them
				$options = JHtml::fetch('vaphtml.admin.employees', $strict = false, $blank = false, $group = true);

				$attrs = array('class' => 'required');

				if ($closure->id)
				{
					// edit, use only the assigned employee
					$val = array_shift($closure->id_employees);
					$name = 'id_employee';
				}
				else
				{
					// create, inject all selected employees
					$val = $closure->id_employees;
					// allow multiple selection
					$attrs['multiple'] = true;
					$name = 'id_employees[]';
				}

				// create dropdown attributes
				$params = array(
					'id'          => 'vap-employees-sel',
					'group.items' => null,
					'list.select' => $val,
					'list.attr'   => $attrs,
				);

				// render select
				echo JHtml::fetch('select.groupedList', $options, $name, $params);

				echo $vik->closeControl();
				?>

				<!-- FROM DATE - Calendar -->

				<?php
				echo $vik->openControl(JText::translate('VAPMANAGEWD2') . '*');
				echo $vik->calendar(VAPDateHelper::sql2date($closure->fromdate), 'fromdate', null, null, array('class' => 'required'));
				echo $vik->closeControl();
				?>

				<!-- FROM TIME - Select -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGEWD3') . '*'); ?>
					<select name="fromtime" class="time-select">
						<?php echo JHtml::fetch('select.options', $times, 'value', 'text', $closure->fromtime); ?>
					</select>
				<?php echo $vik->closeControl(); ?>

				<!-- TO TIME - Select -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGEWD4') . '*'); ?>
					<select name="totime" class="time-select">
						<?php echo JHtml::fetch('select.options', $times, 'value', 'text', $closure->totime); ?>
					</select>
				<?php echo $vik->closeControl(); ?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewClosure","type":"field"} -->

				<?php	
				/**
				 * Look for any additional fields to be pushed within
				 * the main fieldset (left-side).
				 */
				foreach ($detailsForms as $formField)
				{
					echo $formField;
				}
				?>

			<?php echo $vik->closeFieldset(); ?>

		</div>

		<?php
		if ($sidebarForms)
		{
			?>
			<div class="span5 full-width">

				<?php
				// iterate forms to be displayed within the sidebar panel
				foreach ($sidebarForms as $formName => $formHtml)
				{
					$title = JText::translate($formName);
					?>
					<div class="row-fluid">
						<div class="span12">
							<?php
							echo $vik->openFieldset($title);
							echo $formHtml;
							echo $vik->closeFieldset();
							?>
						</div>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewClosureSidebar","type":"fieldset"} -->

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<input type="hidden" name="id" value="<?php echo $closure->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<script>
	
	jQuery(function($) {
		$('#vap-employees-sel').select2({
			placeholder: '--',
			allowClear: false,
			width: '90%',
		});

		$('.time-select').select2({
			allowClear: false,
			width: 200,
		});
	});

	// validate

	var validator = new VikFormValidator('#adminForm');

	Joomla.submitbutton = function(task) {
		if (task.indexOf('save') !== -1) {
			if (validator.validate()) {
				Joomla.submitform(task, document.adminForm);    
			}
		} else {
			Joomla.submitform(task, document.adminForm);
		}
	}
	
</script>
