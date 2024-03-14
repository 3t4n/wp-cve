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

if (!$step->isCompleted())
{
	// go ahead only if the step is completed
	return;
}
?>

<ul class="wizard-step-summary">
	<?php
	$packages = $step->getPackages();

	// display at most 3 packages
	for ($i = 0; $i < min(array(3, count($packages))); $i++)
	{
		?>
		<li>
			<?php echo JHtml::fetch('vaphtml.admin.stateaction', $packages[$i]->published); ?>
			<b><?php echo $packages[$i]->name; ?></b>
		</li>
		<?php
	}

	// count remaining packages
	$remaining = count($packages) - 3;

	if ($remaining > 0)
	{
		?>
		<li><?php echo JText::plural('VAPWIZARDOTHER_N_ITEMS', $remaining); ?></li>
		<?php
	}
	?>
</ul>
