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
 * called "onDisplayViewConfigGlobalGDPR". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('GlobalGDPR');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">
		
		<!-- GDPR - Checkbox -->

		<?php
		$yes = $vik->initRadioElement('', '', $params['gdpr'] == 1, 'onclick="jQuery(\'.gdpr-child\').show();"');
		$no  = $vik->initRadioElement('', '', $params['gdpr'] == 0, 'onclick="jQuery(\'.gdpr-child\').hide();"');

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG112'),
			'content' => JText::translate('VAPMANAGECONFIG112_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG112') . $help);
		echo $vik->radioYesNo('gdpr', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- PRIVACY POLICY - Text -->

		<?php
		$control = array();
		$control['style'] = $params['gdpr'] == 0 ? 'display:none;' : '';

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG113'), 'gdpr-child', $control); ?>
			<input type="text" name="policylink" value="<?php echo $this->escape($params['policylink']); ?>" size="64" />
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigGlobalGDPR","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Global > GDPR > GDPR fieldset.
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
<!-- {"rule":"customizer","event":"onDisplayViewConfigGlobalGDPR","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Global > GDPR tab.
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
