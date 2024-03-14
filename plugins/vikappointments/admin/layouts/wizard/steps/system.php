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

/**
 * Layout variables
 * -----------------
 * @var  VAPWizardStep  $step  The wizard step instance.
 */
extract($displayData);

$vik = VAPApplication::getInstance();

$id = $step->getID();

$config = VAPFactory::getConfig();

if (!$step->isCompleted())
{
	// get list of currencies
	$currencies = $step->getCurrencies();

	?>
	<div class="wizard-form">

		<!-- AGENCY NAME - Text -->

		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIG0')); ?>
			<input type="text" name="wizard[<?php echo $id; ?>][agencyname]" value="<?php echo $this->escape($config->get('agencyname')); ?>" class="required" />
		<?php echo $vik->closeControl(); ?>

		<!-- ADMIN E-MAIL - Email -->

		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIG1')); ?>
			<input type="email" name="wizard[<?php echo $id; ?>][adminemail]" value="<?php echo $this->escape($config->get('adminemail')); ?>" class="required" />
		<?php echo $vik->closeControl(); ?>

		<!-- CURRENCY - Select -->

		<?php
		$options = array();

		$options[] = JHtml::fetch('select.option', '', JText::translate('VAPWIZARDCURROTHER'));

		foreach ($currencies as $code => $format)
		{
			$options[] = JHtml::fetch('select.option', $code, $code . ' - ' . $format['currency']);
		}

		$code = $config->get('currencyname');
		$symb = $config->get('currencysymb');
		?>

		<?php echo $vik->openControl(JText::translate('VAPCONFIGGLOBTITLE6')); ?>
			<select name="wizard[<?php echo $id; ?>][currency]">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $code); ?>
			</select>

			<input type="text" name="wizard[<?php echo $id; ?>][currencyname]" value="<?php echo $this->escape($code); ?>" size="6" class="hasTooltip" title="<?php echo $this->escape(JText::translate('VAPWIZARDCURRCODE')); ?>" style="margin-left:5px;max-width:80px;<?php echo !isset($currencies[$code]) ? '' : 'display:none;'; ?>" />
			<input type="text" name="wizard[<?php echo $id; ?>][currencysymb]" value="<?php echo $this->escape($symb); ?>" size="6" class="hasTooltip" title="<?php echo $this->escape(JText::translate('VAPWIZARDCURRSYMB')); ?>" style="margin-left:5px;max-width:80px;<?php echo !isset($currencies[$code]) ? '' : 'display:none;'; ?>" />
		<?php echo $vik->closeControl(); ?>

		<!-- DATE FORMAT - Select -->

		<?php
		$options = array(
			JHtml::fetch('select.option', 'Y/m/d', 'VAPCONFIGDATEFORMAT1'),
			JHtml::fetch('select.option', 'm/d/Y', 'VAPCONFIGDATEFORMAT2'),
			JHtml::fetch('select.option', 'd/m/Y', 'VAPCONFIGDATEFORMAT3'),
			JHtml::fetch('select.option', 'Y-m-d', 'VAPCONFIGDATEFORMAT4'),
			JHtml::fetch('select.option', 'm-d-Y', 'VAPCONFIGDATEFORMAT5'),
			JHtml::fetch('select.option', 'd-m-Y', 'VAPCONFIGDATEFORMAT6'),
			JHtml::fetch('select.option', 'Y.m.d', 'VAPCONFIGDATEFORMAT7'),
			JHtml::fetch('select.option', 'm.d.Y', 'VAPCONFIGDATEFORMAT8'),
			JHtml::fetch('select.option', 'd.m.Y', 'VAPCONFIGDATEFORMAT9'),
		);
		?>

		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIG5')); ?>
			<select name="wizard[<?php echo $id; ?>][dateformat]">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $config->get('dateformat'), true); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- TIME FORMAT - Checkbox -->

		<?php
		$yes = $vik->initRadioElement('', '', $config->get('timeformat') == 'H:i');
		$no  = $vik->initRadioElement('', '', $config->get('timeformat') == 'h:i A');

		echo $vik->openControl(JText::translate('VAPWIZARDFORMATH24'));
		echo $vik->radioYesNo("wizard[{$id}][timeformat]", $yes, $no);
		echo $vik->closeControl();
		?>

	</div>

	<script>
		(function($) {
			'use strict';

			VAPWizard.addPreflight('<?php echo $id; ?>', (role, step) => {
				if (role != 'process') {
					return true;
				}

				// create form validator
				const validator = new VikFormValidator(step);

				if ($('select[name="wizard[<?php echo $id; ?>][currency]"]').val().length == 0) {
					validator.registerFields('input[name="wizard[<?php echo $id; ?>][currencyname]"]');
					validator.registerFields('input[name="wizard[<?php echo $id; ?>][currencysymb]"]');
				}

				// validate form
				if (!validator.validate()) {
					// prevent request
					return false;
				}

				return true;
			});

			$(function() {
				VikRenderer.chosen('[data-id="<?php echo $id; ?>"] select');

				$('select[name="wizard[<?php echo $id; ?>][currency]"]').on('change', function() {
					if ($(this).val().length) {
						$('input[name="wizard[<?php echo $id; ?>][currencyname]"]').hide();
						$('input[name="wizard[<?php echo $id; ?>][currencysymb]"]').hide();
					} else {
						$('input[name="wizard[<?php echo $id; ?>][currencyname]"]').show();
						$('input[name="wizard[<?php echo $id; ?>][currencysymb]"]').show();
					}
				});
			});
		})(jQuery);
	</script>
	<?php
}
else
{
	?>
	<ul class="wizard-step-summary">
		<li>
			<b><?php echo $config->get('agencyname'); ?></b>
		</li>
		<li>
			<b><?php echo $config->get('adminemail'); ?></b>
		</li>
		<li>
			<b><?php echo $config->get('currencyname'); ?></b>
			<span class="badge badge-success"><?php echo VAPFactory::getCurrency($reload = true)->format(1234.56); ?></span>
		</li>
		<li>
			<span class="badge badge-important">
				<?php echo JHtml::fetch('date', 'now', $config->get('dateformat')); ?>
			</span>
			<span class="badge badge-warning">
				<?php echo JHtml::fetch('date', 'now', $config->get('timeformat')); ?>
			</span>
		</li>
	</ul>
	<?php
}
