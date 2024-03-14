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

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigGlobalTimezone". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('GlobalTimezone');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">
		
		<!-- MULTI TIMEZONE - Checkbox -->

		<?php
		$yes = $vik->initRadioElement('', '', $params['multitimezone'] == 1);
		$no  = $vik->initRadioElement('', '', $params['multitimezone'] == 0);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG86'),
			'content' => JText::translate('VAPMANAGECONFIG86_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG86') . $help);
		echo $vik->radioYesNo('multitimezone', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- CURRENT TIMEZONE - Label -->

		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG87'),
			'content' => JText::sprintf('VAPMANAGECONFIG87_DESC', JFactory::getApplication()->get('offset', 'UTC')),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG87') . $help); ?>
			<span class="badge badge-info">
				<?php echo str_replace('_', ' ', date_default_timezone_get()); ?>
			</span>

			<span class="badge badge-important">
				<?php echo date('Y-m-d H:i:s T'); ?>
			</span>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigGlobalTimezone","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Global > Timezone > Timezone fieldset.
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

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigGlobalTimezone","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Global > Timezone tab.
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
