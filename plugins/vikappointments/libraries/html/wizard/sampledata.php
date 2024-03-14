<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  html.wizard
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

if ($step->isCompleted())
{
	return;
}

$id = $step->getID();

// load supported sample data packages
$sampledata = $step->getSampleData();

$vik = VAPApplication::getInstance();

if ($sampledata)
{
	JText::script('VAPSAVE');

	?>
	<p>
		<?php _e('Select one of the available sample data and proceed with the installation.', 'vikappointments'); ?>
	</p>

	<div class="wizard-form">

		<?php echo $vik->openControl(__('Sample Data')); ?>
			<select name="wizard[<?php echo $id; ?>][sampledata]" class="required">
				<?php
				$options = array();
				$options[] = JHtml::fetch('select.option', '', JText::translate('JGLOBAL_SELECT_AN_OPTION'));

				foreach ($sampledata as $sd)
				{
					$options[] = JHtml::fetch('select.option', $sd->id, $sd->title);
				}

				echo JHtml::fetch('select.options', $options);
				?>
			</select>
		<?php echo $vik->closeControl(); ?>

	</div>

	<script>
		(function($) {
			'use strict';

			let progressInterval;

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

				let savingText = wp.i18n.__('Saving');
				let stepButton = $(step).find('button[data-role="process"]');

				// change text button to inform the user that the step is processing the request
				stepButton.html(savingText + ' <i class="fas fa-hourglass-start"></i>');

				let iter = 0;

				let icons = [
					'fa-hourglass-start',
					'fa-hourglass-half',
					'fa-hourglass-end',
				];

				// simulate progress
				progressInterval = setInterval(() => {
					iter = (iter + 1) % 3;

					stepButton.html(savingText + ' <i class="fas ' + icons[iter] + '"></i>');
				}, 512);

				// retrieve form data
				let data = VAPWizard.getFormData('<?php echo $id; ?>', true);

				// inject argument to reload all steps
				data.push({name: 'reload_all', value: true});

				return $.param(data);
			});

			VAPWizard.addPostflight('<?php echo $id; ?>', (role, step) => {
				clearInterval(progressInterval);

				// restore text button on both success and failure
				$(step).find('button[data-role="process"]').text(Joomla.JText._('VAPSAVE'));
			});

			$(function() {
				VikRenderer.chosen('[data-id="<?php echo $id; ?>"] select');
			});
		})(jQuery);
	</script>
	<?php

	echo $vik->alert(__('Notice that the installation of the sample data might restore the database to the factory settings. So, if you already created some records, you might lose them.', 'vikappointments'));
}
else
{
	// no available sample data
	echo $vik->alert(__('There are no sample data available for your version. Please try to update the plugin to the latest version.', 'vikappointments'), 'error');
	?>
	<script>
		(function($) {
			'use strict';

			$(function() {
				$('[data-id="<?php echo $id; ?>"] button[data-role="process"]').hide();
			});
		})(jQuery);
	</script>
	<?php
}
