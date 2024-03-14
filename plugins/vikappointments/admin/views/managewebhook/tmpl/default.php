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

$webhook = $this->webhook;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewWebhook". The event method receives the
 * view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView();

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">

	<?php echo $vik->openCard(); ?>

		<!-- DETAILS -->
	
		<div class="span7 full-width">
			<?php
			echo $vik->openFieldset(JText::translate('VAPCUSTFIELDSLEGEND1'));
			echo $this->loadTemplate('details');
			?>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayViewWebhook","key":"webhook","type":"field"} -->

			<?php	
			/**
			 * Look for any additional fields to be pushed within
			 * the "Details" fieldset (left-side).
			 *
			 * @since 1.7
			 */
			if (isset($forms['webhook']))
			{
				echo $forms['webhook'];

				// unset details form to avoid displaying it twice
				unset($forms['webhook']);
			}
			
			echo $vik->closeFieldset();
			?>
		</div>

		<div class="span5 full-width">

			<!-- PARAMS -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMANAGEPAYMENT8'));
					echo $this->loadTemplate('params');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewWebhook","key":"params","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Parameters" fieldset (right-side).
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

			<!-- LOGS -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMANAGECRONJOB5'));
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewWebhook","key":"logs","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Logs" fieldset (right-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['logs']))
					{
						echo $forms['logs'];

						// unset details form to avoid displaying it twice
						unset($forms['logs']);
					}

					// display custom fields before the logs
					echo $this->loadTemplate('logs');

					echo $vik->closeFieldset();
					?>
				</div>
			</div>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayViewWebhook","type":"fieldset"} -->

			<?php
			if ($forms)
			{
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
			}
			?>

		</div>

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<input type="hidden" name="id" value="<?php echo $webhook->id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_vikappointments"/>
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
