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

JHtml::fetch('bootstrap.tooltip', '.hasTooltip');
JHtml::fetch('vaphtml.assets.select2');
JHtml::fetch('vaphtml.assets.fontawesome');

$status = $this->status;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewStatuscode". The event method receives the
 * view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView();

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">

	<?php echo $vik->openCard(); ?>

		<!-- MAIN -->

		<div class="span7">

			<!-- STATUS CODE -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMANAGERESERVATION12'));
					echo $this->loadTemplate('status');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewStatuscode","key":"status","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Status" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['status']))
					{
						echo $forms['status'];

						// unset details form to avoid displaying it twice
						unset($forms['status']);
					}
						
					echo $vik->closeFieldset();
					?>
				</div>
			</div>

			<!-- DESCRIPTION -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMANAGEOPTION3'));
					echo $vik->getEditor()->display('description', $status->description, '100%', 550, 40, 20);
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewStatuscode","key":"description","type":"field"} -->
					
					<?php
					/**
					 * Look for any additional fields to be pushed within
					 * the "Description" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['description']))
					{
						echo $forms['description'];

						// unset details form to avoid displaying it twice
						unset($forms['description']);
					}
						
					echo $vik->closeFieldset();
					?>
				</div>
			</div>

		</div>

		<!-- SIDEBAR -->

		<div class="span5">

			<!-- ROLES -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPSTATUSCODEROLES'));
					echo $this->loadTemplate('roles');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewStatuscode","key":"roles","type":"field"} -->
					
					<?php
					/**
					 * Look for any additional fields to be pushed within
					 * the "Roles" fieldset (sidebar).
					 *
					 * @since 1.7
					 */
					if (isset($forms['roles']))
					{
						echo $forms['roles'];

						// unset details form to avoid displaying it twice
						unset($forms['roles']);
					}

					echo $vik->closeFieldset();
					?>
				</div>
			</div>

			<!-- PUBLISHING -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('JGLOBAL_FIELDSET_PUBLISHING'));
					echo $this->loadTemplate('publishing');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewStatuscode","key":"publishing","type":"field"} -->
					
					<?php
					/**
					 * Look for any additional fields to be pushed within
					 * the "Publishing" fieldset (sidebar).
					 *
					 * @since 1.7
					 */
					if (isset($forms['publishing']))
					{
						echo $forms['publishing'];

						// unset details form to avoid displaying it twice
						unset($forms['publishing']);
					}

					echo $vik->closeFieldset();
					?>
				</div>
			</div>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayViewStatuscode","type":"fieldset"} -->

			<?php
			/**
			 * Iterate remaining forms to be displayed within
			 * the sidebar (below "Publishing" fieldset).
			 *
			 * @since 1.7
			 */
			foreach ($forms as $formName => $formHtml)
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

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>

	<input type="hidden" name="id" value="<?php echo $status->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<script>

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
