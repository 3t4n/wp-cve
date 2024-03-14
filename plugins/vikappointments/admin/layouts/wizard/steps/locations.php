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

$progress = $step->getProgress();

$vik = VAPApplication::getInstance();

// get Google API Key configuration
if ($step->getGoogleAK() === '')
{
	$id = $step->getID();

	echo $vik->alert(JText::translate('VAP_WIZARD_STEP_LOCATIONS_GOOGLE_API_KEY_WARN'));
	?>
	<div class="wizard-form">

		<!-- GOOGLE API KEY - Text -->

		<div class="controls">
			<input type="text" name="wizard[<?php echo $id; ?>][googleapikey]" value="" class="required form-control" placeholder="Google API Key" />
		</div>

	</div>

	<script>
		(function($) {
			'use strict';

			VAPWizard.addPreflight('<?php echo $id; ?>', function(role, step) {
				if (role != 'process') {
					return true;
				}

				// create form validator
				var validator = new VikFormValidator(step);

				// validate form
				if (!validator.validate()) {
					// prevent request
					return false;
				}

				return true;
			});
		})(jQuery);
	</script>
	<?php
}
else if ($locations = $step->getLocations())
{
	?>
	<ul class="wizard-step-summary">
		<?php
		

		// display at most 3 locations
		for ($i = 0; $i < min(array(3, count($locations))); $i++)
		{
			?>
			<li>
				<b><?php echo $locations[$i]->name; ?></b>
			</li>
			<?php
		}

		// count remaining locations
		$remaining = count($locations) - 3;

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
