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
 * called "onDisplayViewConfigappApiDetails". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('ApiDetails');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">

		<!-- REGISTER LOGS - Checkbox -->

		<?php
		$yes = $vik->initRadioElement('', '',  $config->getBool('apifw'), 'onclick="toggleApiFrameworkFields(1);"');
		$no  = $vik->initRadioElement('', '', !$config->getBool('apifw'), 'onclick="toggleApiFrameworkFields(0);"');

		echo $vik->openControl(JText::translate('VAPAPICONFIG1'));
		echo $vik->radioYesNo('apifw', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- MAX FAIL ATTEMPTS - Number -->

		<?php
		$control = array();
		$control['style'] = $config->getBool('apifw') ? '' : 'display:none;';

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPWEBHOOKCONFIG1'),
			'content' => JText::translate('VAPAPICONFIG1_HELP'),
		));

		echo $vik->openControl(JText::translate('VAPWEBHOOKCONFIG1') . $help, 'apifw-child', $control); ?>
			<input type="number" name="apimaxfail" value="<?php echo $config->getUint('apimaxfail'); ?>" step="1" min="0" max="9999" />
		<?php echo $vik->closeControl(); ?>

		<!-- LOGGING MODE - Select -->

		<?php
		$options = array(
			JHtml::fetch('select.option', 0, JText::translate('VAPCONFIGSENDMAILWHEN3')),
			JHtml::fetch('select.option', 1, JText::translate('VAPMANAGECONFIGCRON3')),
			JHtml::fetch('select.option', 2, JText::translate('VAPMANAGECONFIGCRON4')),
		);

		echo $vik->openControl(JText::translate('VAPWEBHOOKCONFIG4'), 'apifw-child', $control); ?>
			<select name="apilogmode" class="medium">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $config->get('apilogmode')); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- AUTO FLUSH - Select -->

		<?php
		$options = array(
			JHtml::fetch('select.option',  1, JText::translate('VAPAPICONFIG2_OPT1')),
			JHtml::fetch('select.option',  7, JText::translate('VAPAPICONFIG2_OPT2')),
			JHtml::fetch('select.option', 30, JText::translate('VAPAPICONFIG2_OPT3')),
			JHtml::fetch('select.option',  0, JText::translate('VAPCONFIGSENDMAILWHEN3')),
		);

		echo $vik->openControl(JText::translate('VAPAPICONFIG2'), 'apifw-child', $control); ?>
			<select name="apilogflush" class="medium">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $config->get('apilogflush')); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigappApiDetails","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the API > Settings > Details fieldset.
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
<!-- {"rule":"customizer","event":"onDisplayViewConfigappApiDetails","type":"fieldset"} -->

<?php
// iterate remaining forms to be displayed as new fieldsets
// within the API > Settings tab
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
?>

<script>
	function toggleApiFrameworkFields(is) {
		if (is) {
			jQuery('.apifw-child').show();
			jQuery('#apiusers-btn, #apiplugins-btn').removeClass('disabled');
		} else {
			jQuery('.apifw-child').hide();
			jQuery('#apiusers-btn, #apiplugins-btn').addClass('disabled');
		}
	}
</script>
