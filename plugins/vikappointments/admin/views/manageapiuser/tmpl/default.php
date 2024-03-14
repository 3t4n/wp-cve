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

$user = $this->user;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewApiuser". The event method receives the
 * view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView();

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">

	<?php echo $vik->openCard(); ?>

		<!-- RIGHT SIDE -->
	
		<div class="span6 full-width">

			<!-- APPLICATION -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMANAGEAPIUSER8'));
					echo $this->loadTemplate('application');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewApiuser","key":"application","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Application" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['application']))
					{
						echo $forms['application'];

						// unset application form to avoid displaying it twice
						unset($forms['application']);
					}
					
					echo $vik->closeFieldset();
					?>
				</div>
			</div>

			<!-- IP RESTRICTIONS -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMANAGEAPIUSER5'));
					echo $this->loadTemplate('ip');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewApiuser","key":"ip","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "IP" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['ip']))
					{
						echo $forms['ip'];

						// unset IP form to avoid displaying it twice
						unset($forms['ip']);
					}
					
					echo $vik->closeFieldset();
					?>
				</div>
			</div>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayViewApiuser","type":"fieldset"} -->

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

		<!-- PLUGINS -->

		<div class="span6 full-width">
			<?php
			echo $vik->openFieldset(JText::translate('VAPMANAGEAPIUSER21'));
			echo $this->loadTemplate('plugins');
			?>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayViewApiuser","key":"plugins","type":"field"} -->

			<?php	
			/**
			 * Look for any additional fields to be pushed within
			 * the "Plugins" fieldset (right-side).
			 *
			 * @since 1.7
			 */
			if (isset($forms['plugins']))
			{
				echo $forms['plugins'];

				// unset plugins form to avoid displaying it twice
				unset($forms['plugins']);
			}

			echo $vik->closeFieldset();
			?>
		</div>

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<input type="hidden" name="id" value="<?php echo $user->id; ?>"/>
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
