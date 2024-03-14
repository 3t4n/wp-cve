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

$city = $this->city;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewCity". The event method receives the
 * view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView();

if ($this->isTmpl)
{
	// we are in a modal box, add some padding
	JFactory::getDocument()->addStyleDeclaration('#adminForm { padding: 10px; }');
	JHtml::fetch('behavior.core');
}

?>

<form name="adminForm" action="index.php" method="post" id="adminForm" class="managecity">

	<?php
	if ($this->isTmpl)
	{
		?>
		<div class="btn-toolbar" style="display:none;">
			<div class="btn-group pull-left">
				<button type="button" class="btn btn-success" name="tmplSaveButton" onclick="vapValidateFieldsAndDisableButton(this);">
					<i class="icon-apply"></i>&nbsp;<?php echo JText::translate('VAPSAVE'); ?>
				</button>
			</div>
		</div>
		<?php
	}
	?>
	
	<?php echo $vik->openCard(); ?>

		<!-- MAIN -->

		<div class="span6">

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMANAGECITYTITLE1'));
					echo $this->loadTemplate('city');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCity","key":"city","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "City" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['city']))
					{
						echo $forms['city'];

						// unset details form to avoid displaying it twice
						unset($forms['city']);
					}
						
					echo $vik->closeFieldset();
					?>
				</div>
			</div>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayViewCity","type":"fieldset"} -->

			<?php
			/**
			 * Iterate remaining forms to be displayed within
			 * the sidebar (below "City" fieldset).
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

		<!-- MAP -->

		<div class="span6">
			<?php
			echo $vik->openFieldset(JText::translate('VAPMANAGECITYTITLE2'));
			echo $this->loadTemplate('map');
			echo $vik->closeFieldset();
			?>
		</div>

	<?php echo $vik->closeCard(); ?>

	<?php
	if ($this->isTmpl)
	{
		?>
		<input type="hidden" name="tmpl" value="component" />
		<?php
	}
	?>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<input type="hidden" name="id" value="<?php echo $city->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="id_state" value="<?php echo $city->id_state; ?>" />
</form>

<script>

	// validate

	var validator = new VikFormValidator('#adminForm');

	Joomla.submitbutton = function(task) {
		// check if we clicked a "save" button and the form is not valid
		if (task.indexOf('save') !== -1 && !validator.validate()) {
			// abort request
			return false;
		}

		// submit form
		Joomla.submitform(task, document.adminForm);
		return true;
	}

	<?php
	if ($this->isTmpl)
	{
		?>
		function vapValidateFieldsAndDisableButton(button) {
			if (jQuery(button).prop('disabled')) {
				// button already submitted
				return false;
			}

			// disable button
			jQuery(button).prop('disabled', true);

			// submit form
			if (Joomla.submitbutton('city.save') === false) {
				// invalid fields, enable button again
				jQuery(button).prop('disabled', false);
			}
		}

		// transfer submit button instance to parent for being clicked
		window.parent.modalCitySaveButton = document.adminForm.tmplSaveButton;

		// transfer created ID to parent
		window.parent.modalSavedCityData = <?php echo $this->city->id > 0 ? json_encode($this->city) : 0; ?>;
		<?php
	}
	?>
	
</script>
