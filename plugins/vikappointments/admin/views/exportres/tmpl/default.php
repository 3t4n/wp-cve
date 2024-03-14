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
JHtml::fetch('vaphtml.assets.fontawesome');

$vik = VAPApplication::getInstance();

$vik->attachPopover();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewExportres".
 * It is also possible to use "onDisplayViewExportresSidebar"
 * to include any additional fieldsets within the right sidebar.
 * The event method receives the view instance as argument.
 *
 * @since 1.7
 */
$forms        = $this->onDisplayView();
$sidebarForms = $this->onDisplayView('Sidebar');

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">

	<?php echo $vik->openCard(); ?>

		<!-- MAIN -->

		<div class="span6 full-width">

			<!-- DETAILS -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPCUSTFIELDSLEGEND1'));
					echo $this->loadTemplate('details');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewExportres","key":"export","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Details" fieldset (left-side).
					 *
					 * NOTE: retrieved from "onDisplayViewExportres" hook.
					 *
					 * @since 1.7
					 */
					if (isset($forms['export']))
					{
						echo $forms['export'];

						// unset details form to avoid displaying it twice
						unset($forms['export']);
					}
					
					echo $vik->closeFieldset();
					?>
				</div>
			</div>
				
			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayViewExportres","type":"fieldset"} -->

			<?php
			/**
			 * Iterate remaining forms to be displayed within
			 * the main panel (below "Details" fieldset).
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

		<!-- SIDEBAR -->

		<div class="span6 full-width">

			<!-- PARAMS -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMANAGEPAYMENT8'));
					echo $this->loadTemplate('params');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewExportres","key":"params","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Parameters" fieldset (right-side).
					 *
					 * NOTE: retrieved from "onDisplayViewExportres" hook.
					 *
					 * @since 1.7
					 */
					if (isset($forms['params']))
					{
						echo $forms['params'];

						// unset details form to avoid displaying it twice
						unset($forms['params']);
					}

					echo $vik->closeFieldset();
					?>
				</div>
			</div>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayViewExportresSidebar","type":"fieldset"} -->

			<?php
			/**
			 * Iterate remaining forms to be displayed within
			 * the sidebar (below "Parameters" fieldset).
			 *
			 * @since 1.7
			 */
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

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<?php
	foreach ($this->data->cid as $id)
	{
		?> 
		<input type="hidden" name="cid[]" value="<?php echo $id; ?>" />
		<?php
	}
	?>
	
	<input type="hidden" name="type" value="<?php echo $this->data->type; ?>" />

	<input type="hidden" name="view" value="exportres" />
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
