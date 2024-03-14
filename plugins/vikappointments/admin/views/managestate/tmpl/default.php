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

$state = $this->state;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewState". The event method receives the
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

<form name="adminForm" action="index.php" method="post" id="adminForm" class="managestate">

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
	
		<div class="<?php echo $forms ? 'span6' : 'span12'; ?>">
			<?php echo $vik->openEmptyFieldset(); ?>
				
				<!-- STATE NAME - Text -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGESTATE1') . '*'); ?>
					<input type="text" name="state_name" class="input-xxlarge input-large-text required" value="<?php echo $state->state_name; ?>" size="40" />
				<?php echo $vik->closeControl(); ?>
				
				<!-- STATE 2 CODE - Text -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGESTATE2') . '*'); ?>
					<input type="text" name="state_2_code" class="required" value="<?php echo $state->state_2_code; ?>" size="20" maxlength="2" />
				<?php echo $vik->closeControl(); ?>
				
				<!-- STATE 3 CODE - Text -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGESTATE3')); ?>
					<input type="text" name="state_3_code" value="<?php echo $state->state_3_code; ?>" size="20" maxlength="3" />
				<?php echo $vik->closeControl(); ?>
				
				<!-- PUBLISHED - Checkbox -->

				<?php
				$yes = $vik->initRadioElement('', '', $state->published == 1);
				$no  = $vik->initRadioElement('', '', $state->published == 0);
				
				echo $vik->openControl(JText::translate('VAPMANAGESTATE4'));
				echo $vik->radioYesNo('published', $yes, $no, false);
				echo $vik->closeControl();
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewState","key":"state","type":"field"} -->

				<?php	
				/**
				 * Look for any additional fields to be pushed within
				 * the "Details" fieldset (left-side).
				 *
				 * @since 1.7
				 */
				if (isset($forms['state']))
				{
					echo $forms['state'];

					// unset details form to avoid displaying it twice
					unset($forms['state']);
				}
				?>
				
			<?php echo $vik->closeEmptyFieldset(); ?>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewState","type":"fieldset"} -->

		<?php
		if ($forms)
		{
			?>
			<div class="span6 full-width">
				<?php
				/**
				 * Iterate remaining forms to be displayed within
				 * the sidebar.
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
			<?php
		}
		?>

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>

	<?php
	if ($this->isTmpl)
	{
		?>
		<input type="hidden" name="tmpl" value="component" />
		<?php
	}
	?>
	
	<input type="hidden" name="id" value="<?php echo $state->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="id_country" value="<?php echo $state->id_country; ?>" />
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
			if (Joomla.submitbutton('state.save') === false) {
				// invalid fields, enable button again
				jQuery(button).prop('disabled', false);
			}
		}

		// transfer submit button instance to parent for being clicked
		window.parent.modalStateSaveButton = document.adminForm.tmplSaveButton;

		// transfer created ID to parent
		window.parent.modalSavedStateData = <?php echo $this->state->id > 0 ? json_encode($this->state) : 0; ?>;
		<?php
	}
	?>

</script>
