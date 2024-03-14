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

		<!-- ENABLE PACKAGES - Checkbox -->

		<?php
		$yes = $vik->initRadioElement('', '', false);
		$no  = $vik->initRadioElement('', '', true);

		echo $vik->openControl(JText::translate('VAPWIZARDENABLE'));
		echo $vik->radioYesNo("wizard[{$id}][enablepackages]", $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- MANDATORY PURCHASE -->

		<?php
		$yes = $vik->initRadioElement('', '', false);
		$no  = $vik->initRadioElement('', '', true);

		$help = $vik->createPopover([
			'title'   => JText::translate('VAPMANAGECONFIG121'),
			'content' => JText::translate('VAPMANAGECONFIG121_DESC'),
		]);

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG121') . $help, 'packs-enable', ['style' => 'display:none;']);
		echo $vik->radioYesNo("wizard[{$id}][packsmandatory]", $yes, $no);
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
				$('input[name="wizard[<?php echo $id; ?>][enablepackages]"]').on('change', function() {
					if ($(this).is(':checked')) {
						$('.packs-enable').show();
					} else {
						$('.packs-enable').hide();
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
			<?php echo JHtml::fetch('vaphtml.admin.stateaction', $config->getBool('enablepackages')); ?>
			<b><?php echo JText::translate('VAPMANAGECONFIG109'); ?></b>
		</li>

		<?php
		if ($config->getBool('enablepackages'))
		{
			?>
			<li>
				<?php echo JHtml::fetch('vaphtml.admin.stateaction', $config->getBool('packsmandatory')); ?>
				<b><?php echo JText::translate('VAPMANAGECONFIG121'); ?></b>
			</li>
			<?php
		}
		?>
	</ul>
	<?php
}
