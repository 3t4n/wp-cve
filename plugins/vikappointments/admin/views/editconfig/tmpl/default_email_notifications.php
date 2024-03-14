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

$options = JHtml::fetch('vaphtml.admin.statuscodes', $group = '');

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigEmailNotifications". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('EmailNotifications');

?>

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPCONFIGBOOKINGNOTIF'); ?></h3>
	</div>

	<div class="config-fieldset-body">

		<?php echo $vik->alert(JText::translate('VAPCONFIGBOOKINGNOTIF_HELP'), 'info'); ?>
		
		<!-- SEND TO CUSTOMER WHEN - Dropdown -->

		<?php
		$params['mailcustwhen'] = (array) json_decode($params['mailcustwhen']);

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG71')); ?>
			<select name="mailcustwhen[]" multiple>
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['mailcustwhen']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- SEND TO EMPLOYEE WHEN - Dropdown -->

		<?php
		$params['mailempwhen'] = (array) json_decode($params['mailempwhen']);

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG72')); ?>
			<select name="mailempwhen[]" multiple>
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['mailempwhen']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- SEND TO ADMIN WHEN - Dropdown -->

		<?php
		$params['mailadminwhen'] = (array) json_decode($params['mailadminwhen']);

		echo $vik->openControl(JText::translate("VAPMANAGECONFIG73")); ?>
			<select name="mailadminwhen[]" multiple>
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['mailadminwhen']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigEmailNotifications","key":"booking","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the E-mail > Notifications > Booking fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['booking']))
		{
			echo $forms['booking'];

			// unset details form to avoid displaying it twice
			unset($forms['booking']);
		}
		?>

	</div>
	
</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigEmailNotifications","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the E-mail > Notifications tab.
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

JText::script('VAPFILTERSELECTSTATUS');
?>

<script>

	jQuery(function($) {
		$('select[name="mailcustwhen[]"]')
			.add('select[name="mailempwhen[]"]')
			.add('select[name="mailadminwhen[]"]')
			.select2({
				placeholder: Joomla.JText._('VAPFILTERSELECTSTATUS'),
				allowClear: true,
				width: '100%',
			});
	});

</script>
