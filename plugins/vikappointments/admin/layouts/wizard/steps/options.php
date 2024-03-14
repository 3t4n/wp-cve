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

if ($progress == 0)
{
	// go ahead only if progress is not 0%
	return;
}
?>

<ul class="wizard-step-summary">
	<?php
	$options = $step->getExtraOptions();

	// display at most 3 options
	for ($i = 0; $i < min(array(3, count($options))); $i++)
	{
		?>
		<li>
			<?php echo JHtml::fetch('vaphtml.admin.stateaction', $options[$i]->id_service ? 1 : 0); ?>
			<b><?php echo $options[$i]->name; ?></b>
		</li>
		<?php
	}

	// count remaining options
	$remaining = count($options) - 3;

	if ($remaining > 0)
	{
		?>
		<li><?php echo JText::plural('VAPWIZARDOTHER_N_ITEMS', $remaining); ?></li>
		<?php
	}
	?>
</ul>

<?php
if ($progress == 50)
{
	// missing assigned services, display warning message
	echo VAPApplication::getInstance()->alert(JText::translate('VAP_WIZARD_STEP_OPTIONS_SERVICE_WARN'));
}
