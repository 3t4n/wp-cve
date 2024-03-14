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

		<!-- ENABLE SUBSCRIPTIONS - Checkbox -->

		<?php
		$yes = $vik->initRadioElement('', '', false);
		$no  = $vik->initRadioElement('', '', true);

		echo $vik->openControl(JText::translate('VAPWIZARDENABLE'));
		echo $vik->radioYesNo("wizard[{$id}][enablesubscr]", $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- MANDATORY PURCHASE -->

		<?php
		$yes = $vik->initRadioElement('', '', false);
		$no  = $vik->initRadioElement('', '', true);

		$help = $vik->createPopover([
			'title'   => JText::translate('VAPMANAGECONFIG121'),
			'content' => JText::translate('VAPMANAGECONFIG121_DESC2'),
		]);

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG121') . $help, 'subscr-enable', ['style' => 'display:none;']);
		echo $vik->radioYesNo("wizard[{$id}][subscrmandatory]", $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- SUBSCRIPTION THRESHOLD -->

		<?php
		$yes = $vik->initRadioElement('', '', true);
		$no  = $vik->initRadioElement('', '', false);

		$help = $vik->createPopover([
			'title'   => JText::translate('VAPMANAGECONFIG126'),
			'content' => JText::translate('VAPMANAGECONFIG126_DESC'),
		]);

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG126') . $help, 'subscr-enable', ['style' => 'display:none;']);
		echo $vik->radioYesNo("wizard[{$id}][subscrthreshold]", $yes, $no);
		echo $vik->closeControl();
		?>

	</div>

	<script>
		(function($) {
			'use strict';

			VAPWizard.addPostflight('<?php echo $id; ?>', (role, step, error) => {
				if (!error) {
					$(step).find('.vap-quest-popover').popover({
						sanitize:  false,
						container: 'body',
						trigger:   'hover',
						html:      true,
					});
				}
			});

			$(function() {
				$('input[name="wizard[<?php echo $id; ?>][enablesubscr]"]').on('change', function() {
					if ($(this).is(':checked')) {
						$('.subscr-enable').show();
					} else {
						$('.subscr-enable').hide();
					}
				});
			});
		})(jQuery);
	</script>
	<?php
}
else
{
	$config = VAPFactory::getConfig();

	?>
	<ul class="wizard-step-summary">
		<li>
			<?php echo JHtml::fetch('vaphtml.admin.stateaction', $config->getBool('enablesubscr')); ?>
			<b><?php echo JText::translate('VAPWIZARDENABLESUBSCR'); ?></b>
		</li>

		<?php
		if ($config->getBool('enablesubscr'))
		{
			?>
			<li>
				<?php echo JHtml::fetch('vaphtml.admin.stateaction', $config->getBool('subscrmandatory')); ?>
				<b><?php echo JText::translate('VAPMANAGECONFIG121'); ?></b>
			</li>

			<li>
				<?php echo JHtml::fetch('vaphtml.admin.stateaction', $config->getBool('subscrthreshold')); ?>
				<b><?php echo JText::translate('VAPMANAGECONFIG126'); ?></b>
			</li>
			<?php
		}
		?>
	</ul>
	<?php
}
