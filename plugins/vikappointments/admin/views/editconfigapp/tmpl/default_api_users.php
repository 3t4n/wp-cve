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

$config = $this->config;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigappApiUsers". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('ApiUsers');

?>

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPCONFIGAPPTITLE3'); ?></h3>
	</div>

	<div class="config-fieldset-body">

		<!-- USERS MANAGEMENT - Button -->

		<div class="control-group">
			<a href="index.php?option=com_vikappointments&amp;view=apiusers" class="btn<?php echo $config->getBool('apifw') ? '' : ' disabled'; ?>" id="apiusers-btn">
				<?php echo JText::translate('VAPCONFIGSEEAPIUSERS'); ?>
			</a>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigappApiUsers","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the API > Applications > Users fieldset.
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

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPCONFIGAPPTITLE4'); ?></h3>
	</div>

	<div class="config-fieldset-body">

		<!-- PLUGINS MANAGEMENT - Button -->

		<div class="control-group">
			<a href="index.php?option=com_vikappointments&amp;view=apiplugins" class="btn<?php echo $config->getBool('apifw') ? '' : ' disabled'; ?>" id="apiplugins-btn">
				<?php echo JText::translate('VAPCONFIGSEEAPIPLUGINS'); ?>
			</a>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigappApiUsers","key":"plugins","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the API > Applications > Plugins fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['plugins']))
		{
			echo $forms['plugins'];

			// unset details form to avoid displaying it twice
			unset($forms['plugins']);
		}
		?>

	</div>

</div>



<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigappApiUsers","type":"fieldset"} -->

<?php
// iterate remaining forms to be displayed as new fieldsets
// within the API > Applications tab
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

JText::script('VAPFORMCHANGEDCONFIRMTEXT');
?>

<script>

	jQuery(function($) {
		$('#apiusers-btn, #apiplugins-btn').on('click', function() {
			if ($(this).hasClass('disabled')) {
				event.preventDefault();
				event.stopPropagation();
				return false;
			}

			if (!configObserver.isChanged()) {
				// nothing has changed, go ahead
				return true;
			}

			// ask for a confirmation
			let r = confirm(Joomla.JText._('VAPFORMCHANGEDCONFIRMTEXT'));

			if (!r) {
				// do not leave the page
				event.preventDefault();
				event.stopPropagation();
				return false;
			}
		});
	});

</script>
