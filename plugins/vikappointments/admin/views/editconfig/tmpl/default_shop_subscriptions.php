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
 * called "onDisplayViewConfigShopSubscriptions". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('ShopSubscriptions');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">

		<!-- ALLOW USER REGISTRATION - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['subscrreguser'] == 1);
		$no  = $vik->initRadioElement('', '', $params['subscrreguser'] == 0);
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG108'));
		echo $vik->radioYesNo('subscrreguser', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- MANDATORY SUBSCRIPTIONS - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['subscrmandatory'] == 1);
		$no  = $vik->initRadioElement('', '', $params['subscrmandatory'] == 0);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG121'),
			'content' => JText::translate('VAPMANAGECONFIG121_DESC2'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG121') . $help);
		echo $vik->radioYesNo('subscrmandatory', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- MANDATORY THRESHOLD - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['subscrthreshold'] == 1);
		$no  = $vik->initRadioElement('', '', $params['subscrthreshold'] == 0);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG126'),
			'content' => JText::translate('VAPMANAGECONFIG126_DESC'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG126') . $help);
		echo $vik->radioYesNo('subscrthreshold', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigShopSubscription","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Shop > Subscriptions > Details fieldset.
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
<!-- {"rule":"customizer","event":"onDisplayViewConfigShopSubscriptions","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Shop > Subscriptions tab.
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
