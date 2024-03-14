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

if (!$step->isCompleted())
{
	?>
	<div class="wizard-form">

		<div style="display: flex; justify-content: space-between;">
			<select name="wizard[<?php echo $id; ?>][type]" class="required">
				<option value="vat"><?php echo JText::translate('VAPWIZARDTAXVATINCL'); ?></option>
				<option value="+%"><?php echo JText::translate('VAPWIZARDTAXVATEXCL'); ?></option>
			</select>

			<div class="input-append" style="margin-left: 5px;">
				<input type="number" name="wizard[<?php echo $id; ?>][amount]" value="20" min="0" max="100" step="any" />
				<span class="btn">%</span>
			</div>
		</div>

		<?php echo $vik->alert(JText::translate('VAP_WIZARD_STEP_TAXES_DESC_ADV'), 'info'); ?>

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

				// validate form
				if (!validator.validate()) {
					// prevent request
					return false;
				}

				return true;
			});

			$(function() {
				VikRenderer.chosen('[data-id="<?php echo $id; ?>"] select');
			});
		})(jQuery);
	</script>
	<?php
}
else
{
	?>
	<ul class="wizard-step-summary">
		<?php
		$taxes = $step->getTaxes();

		// display at most 3 taxes
		for ($i = 0; $i < min(array(3, count($taxes))); $i++)
		{
			?>
			<li>
				<b><?php echo $taxes[$i]->name; ?></b>
			</li>
			<?php
		}

		// count remaining taxes
		$remaining = count($taxes) - 3;

		if ($remaining > 0)
		{
			?>
			<li><?php echo JText::plural('VAPWIZARDOTHER_N_ITEMS', $remaining); ?></li>
			<?php
		}
		?>
	</ul>
	<?php
}
