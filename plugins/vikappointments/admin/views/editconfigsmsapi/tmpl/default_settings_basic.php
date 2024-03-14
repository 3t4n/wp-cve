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

JHtml::fetch('vaphtml.assets.intltel', 'input[name="smsapiadminphone"]');

$params = $this->params;

$vik = VAPApplication::getInstance();

list($params['smsapitocust'], $params['smsapitoemp'], $params['smsapitoadmin']) = explode(',', $params['smsapito']);

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigsmsapiSettingsDetails". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('SettingsDetails');

?>

<!-- BASIC -->

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPCONFIGTABNAME4'); ?></h3>
	</div>

	<div class="config-fieldset-body">

		<!-- SMS APIs FILE - Select -->
		
		<?php
		$options = JHtml::fetch('vaphtml.admin.smsdrivers', $blank = '');
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIGSMS1')); ?>
			<select name="smsapi" id="smsapiselect">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['smsapi']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- ENABLE AUTO SMS APIs - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['smsenabled'] == 1, 'onclick="smsEnabledValueChanged(1);"');
		$no  = $vik->initRadioElement('', '', $params['smsenabled'] == 0, 'onclick="smsEnabledValueChanged(0);"');
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIGSMS2'),
			'content' => JText::translate('VAPMANAGECONFIGSMS2_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIGSMS2') . $help);
		echo $vik->radioYesNo('smsenabled', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- SMS TO CUSTOMERS - Checkbox -->
		
		<?php
		$control = array();
		$control['style'] = $params['smsenabled'] ? '' : 'display:none;';

		// send to customers
		$yes = $vik->initRadioElement('', '', $params['smsapitocust'] == 1);
		$no  = $vik->initRadioElement('', '', $params['smsapitocust'] == 0);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIGSMS3'),
			'content' => JText::translate('VAPMANAGECONFIGSMS3_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIGSMS3') . $help, 'smsenabled-child', $control);
		echo $vik->radioYesNo('smsapitocust', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- SMS TO EMPLOYEES - Checkbox -->
		
		<?php
		// send to customers
		$yes = $vik->initRadioElement('', '', $params['smsapitoemp'] == 1);
		$no  = $vik->initRadioElement('', '', $params['smsapitoemp'] == 0);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIGSMS9'),
			'content' => JText::translate('VAPMANAGECONFIGSMS9_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIGSMS9') . $help, 'smsenabled-child', $control);
		echo $vik->radioYesNo('smsapitoemp', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- SMS TO ADMIN - Checkbox -->
		
		<?php
		// send to customers
		$yes = $vik->initRadioElement('', '', $params['smsapitoadmin'] == 1);
		$no  = $vik->initRadioElement('', '', $params['smsapitoadmin'] == 0);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIGSMS10'),
			'content' => JText::translate('VAPMANAGECONFIGSMS10_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIGSMS10') . $help, 'smsenabled-child', $control);
		echo $vik->radioYesNo('smsapitoadmin', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- ADMIN PHONE - Text -->
		
		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIGSMS4'),
			'content' => JText::translate('VAPMANAGECONFIGSMS4_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIGSMS4') . $help); ?>
			<input type="tel" name="smsapiadminphone" value="<?php echo $params['smsapiadminphone']; ?>" />
		<?php echo $vik->closeControl(); ?>

		<!-- SMS API ESTIMATE - Form -->

		<?php
		$can_estimate = false;
		
		try
		{
			$smsdriver = $vik->getSmsInstance($params['smsapi']);

			if (method_exists($smsdriver, 'estimate'))
			{ 
				$can_estimate = true;

				echo $vik->openControl(JText::translate('VAPMANAGECONFIGSMS7')); ?>
					<span id="usercreditsp">/</span>

					<button type="button" class="btn" id="estimate-credit-btn">
						<?php echo JText::translate("VAPMANAGECONFIGSMS8"); ?>
					</button>
				<?php echo $vik->closeControl();
			}
		}
		catch (Exception $e)
		{
			// no SMS driver
		}
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigsmsapiSettingsDetails","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Settings > Settings > SMS APIs fieldset.
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

<!-- PARAMS -->

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPMANAGECONFIGSMS5'); ?></h3>
	</div>

	<div class="config-fieldset-body" id="vap-smsapi-params-table">
		<?php
		if (empty($params['smsapi']))
		{
			echo $vik->alert(JText::translate('VAPMANAGEPAYMENT9'));
		}
		?>
	</div>

</div>

<div id="smsapi-no-params" style="display:none;">
	<?php echo $vik->alert(JText::translate('VAPMANAGEPAYMENT9')); ?>
</div>

<div id="smsapi-connection-err" style="display:none;">
	<?php echo $vik->alert(JText::translate('VAP_AJAX_GENERIC_ERROR'), 'error'); ?>
</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigsmsapiSettingsDetails","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Settings > Settings tab.
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
?>

<style>
	.vap-uc-text-green{
		color: green;
		font-weight: bold;
	}
	
	.vap-uc-text-red{
		color: red;
		font-weight: bold;
	}
</style>

<?php
JText::script('VAPFILTERSELECTFILE');
JText::script('VAP_AJAX_GENERIC_ERROR');
?>

<script>

	jQuery(function($) {
		$('#smsapiselect').select2({
			placeholder: Joomla.JText._('VAPFILTERSELECTFILE'),
			allowClear: true,
			width: 300,
		});

		$('#smsapiselect').on('change', function() {
			var driver = $(this).val();

			// destroy select2 
			$('#vap-smsapi-params-table select').select2('destroy');
			
			$('#vap-smsapi-params-table').html('');

			if (!driver) {
				// no driver selected, display message
				$('#vap-smsapi-params-table').html($('#smsapi-no-params').html());
				return false;
			}

			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=configsmsapi.driverfields'); ?>',
				{
					driver: driver,
				},
				function(resp) {
					$('#vap-smsapi-params-table').html(resp);

					// render select
					$('#vap-smsapi-params-table select').each(function() {
						$(this).select2({
							// disable search for select with 3 or lower options
							minimumResultsForSearch: $(this).find('option').length > 3 ? 0 : -1,
							allowClear: false,
							width: 285,
						});
					});

					// init helpers
					$('#vap-smsapi-params-table .vap-quest-popover').popover({sanitize: false, container: 'body', trigger: 'hover', html: true});

					$('#vap-smsapi-params-table').trigger('smsapi.load');
				},
				function(error) {
					// display connection error message
					$('#vap-smsapi-params-table').html($('#smsapi-connection-err').html());
				}
			);
		});

		<?php
		if (!empty($params['smsapi']))
		{
			?>$('#smsapiselect').trigger('change');<?php
		}
		?>

		$('#estimate-credit-btn').on('click', () => {
			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=configsmsapi.apicredit'); ?>',
				{
					driver: '<?php echo $params['smsapi']; ?>',
					phone:  $('input[name="smsapiadminphone"]').intlTelInput('getNumber'),
				},
				(credit) => {
					credit = parseFloat(credit);

					if (isNaN(credit)) {
						credit = 0;
					}

					if (credit > 0) {
						$('#usercreditsp').addClass('vap-uc-text-green');
						$('#usercreditsp').removeClass('vap-uc-text-red');
					} else {
						$('#usercreditsp').addClass('vap-uc-text-red');
						$('#usercreditsp').removeClass('vap-uc-text-green');
					}

					$('#usercreditsp').html(Currency.getInstance().format(credit));
				},
				function(error) {
					if (!error.responseText) {
						// use default connection lost error
						error.responseText = Joomla.JText._('VAP_AJAX_GENERIC_ERROR');
					}

					$('#usercreditsp').html('/');

					// raise error
					alert(error.responseText);
				}
			);
		});
	});

	function smsEnabledValueChanged(is) {
		if (is) {
			jQuery('.smsenabled-child').show();
		} else {
			jQuery('.smsenabled-child').hide();
		}
	}

</script>
