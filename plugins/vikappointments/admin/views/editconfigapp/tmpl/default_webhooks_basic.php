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
 * called "onDisplayViewConfigappWebhookDetails". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('WebhookDetails');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">

		<!-- MAX FAIL ATTEMPTS - Number -->

		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPWEBHOOKCONFIG1'),
			'content' => JText::translate('VAPWEBHOOKCONFIG1_HELP'),
		));

		echo $vik->openControl(JText::translate('VAPWEBHOOKCONFIG1') . $help); ?>
			<input type="number" name="webhooksmaxfail" value="<?php echo $config->getUint('webhooksmaxfail'); ?>" step="1" min="0" max="9999" />
		<?php echo $vik->closeControl(); ?>

		<!-- USE LOGS - Checkbox -->

		<?php
		$yes = $vik->initRadioElement('', '',  $config->getBool('webhooksuselog'), 'onclick="useLogValueChanged(1);"');
		$no  = $vik->initRadioElement('', '', !$config->getBool('webhooksuselog'), 'onclick="useLogValueChanged(0);"');

		echo $vik->openControl(JText::translate('VAPWEBHOOKCONFIG4'));
		echo $vik->radioYesNo('webhooksuselog', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- GROUP LOGS - Select -->

		<?php
		$control = array();
		$control['style'] = $config->getBool('webhooksuselog') ? '' : 'display:none;';

		$options = array(
			JHtml::fetch('select.option', 'day', JText::translate('VAPMANAGECONFIGRECSINGOPT1')),
			JHtml::fetch('select.option', 'week', JText::translate('VAPMANAGECONFIGRECSINGOPT2')),
			JHtml::fetch('select.option', 'month', JText::translate('VAPMANAGECONFIGRECSINGOPT3')),
		);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPWEBHOOKCONFIG2'),
			'content' => JText::translate('VAPWEBHOOKCONFIG2_HELP'),
		));

		echo $vik->openControl(JText::translate('VAPWEBHOOKCONFIG2') . $help, 'uselog-child', $control); ?>
			<select name="webhooksgroup" class="medium">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $config->get('webhooksgroup')); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- LOGS PATH - Text -->

		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPWEBHOOKCONFIG3'),
			'content' => JText::translate('VAPWEBHOOKCONFIG3_HELP'),
		));

		// get saved path
		$path = $config->get('webhookslogspath');

		if (!$path)
		{
			// use default path
			$path = JFactory::getApplication()->get('log_path', '');
		}

		echo $vik->openControl(JText::translate('VAPWEBHOOKCONFIG3') . $help, 'uselog-child', $control); ?>
			<input type="text" name="webhookslogspath" value="<?php echo $this->escape($path); ?>" size="64" />
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigappWebhookDetails","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Webhooks > Settings > Details fieldset.
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

		<!-- WEBHOOKS MANAGEMENT - Button -->

		<?php echo $vik->openControl(''); ?>
			<a href="index.php?option=com_vikappointments&amp;view=webhooks" class="btn" id="webhooks-btn">
				<?php echo JText::translate('VAPCONFIGSEEWEBHOOKS'); ?>
			</a>
		<?php echo $vik->closeControl(); ?>

	</div>

</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigappWebhookDetails","type":"fieldset"} -->

<?php
// iterate remaining forms to be displayed as new fieldsets
// within the Webhooks > Settings tab
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
		$('#webhooks-btn').on('click', (event) => {
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

	function useLogValueChanged(is) {
		if (is) {
			jQuery('.uselog-child').show();
		} else {
			jQuery('.uselog-child').hide();
		}
	}

</script>
