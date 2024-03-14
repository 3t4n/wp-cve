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

$coupon = $this->coupon;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewCoupon". The event method receives the
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

			<!-- DETAILS -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMANAGECOUPONFIELDSET1'));
					echo $this->loadTemplate('details');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCoupon","key":"details","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Details" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['details']))
					{
						echo $forms['details'];

						// unset details form to avoid displaying it twice
						unset($forms['details']);
					}
						
					echo $vik->closeFieldset();
					?>
				</div>
			</div>

			<!-- NOTES -->

			<div class="row-fluid">
				<div class="span12">
					<?php echo $vik->openFieldset(JText::translate('VAPMANAGECOUPON16')); ?>

					<textarea name="notes" class="full-width" style="height: 160px; resize: vertical;"><?php echo $coupon->notes; ?></textarea>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCoupon","key":"notes","type":"field"} -->
					
					<?php
					/**
					 * Look for any additional fields to be pushed within
					 * the "Notes" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['notes']))
					{
						echo $forms['notes'];

						// unset details form to avoid displaying it twice
						unset($forms['notes']);
					}
						
					echo $vik->closeFieldset();
					?>
				</div>
			</div>

		</div>

		<!-- SIDEBAR -->

		<div class="span5 full-width">

			<!-- USAGES -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMANAGECOUPONFIELDSET2'));
					echo $this->loadTemplate('usages');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCoupon","key":"usages","type":"field"} -->
					
					<?php
					/**
					 * Look for any additional fields to be pushed within
					 * the "Usages" fieldset (sidebar).
					 *
					 * @since 1.7
					 */
					if (isset($forms['usages']))
					{
						echo $forms['usages'];

						// unset details form to avoid displaying it twice
						unset($forms['usages']);
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
					<!-- {"rule":"customizer","event":"onDisplayViewCoupon","key":"publishing","type":"field"} -->
					
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

			<!-- ASSIGNMENTS -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPFIELDSETASSOC'));
					echo $this->loadTemplate('assoc');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCoupon","key":"assoc","type":"field"} -->
					
					<?php
					/**
					 * Look for any additional fields to be pushed within
					 * the "Assignments" fieldset (sidebar).
					 *
					 * @since 1.7
					 */
					if (isset($forms['assoc']))
					{
						echo $forms['assoc'];

						// unset details form to avoid displaying it twice
						unset($forms['assoc']);
					}

					echo $vik->closeFieldset();
					?>
				</div>
			</div>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayViewCoupon","type":"fieldset"} -->

			<?php
			/**
			 * Iterate remaining forms to be displayed
			 * at the end of the sidebar.
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

	<input type="hidden" name="id" value="<?php echo $coupon->id; ?>" />
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
