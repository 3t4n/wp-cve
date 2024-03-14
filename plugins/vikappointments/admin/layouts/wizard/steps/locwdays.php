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
	// go ahead only if completed
	return;
}

$config = VAPFactory::getConfig();

?>

<ul class="wizard-step-summary">
	<?php
	$assoc = $step->getAssignments();

	// display at most 3 assignments
	for ($i = 0; $i < min(array(3, count($assoc))); $i++)
	{
		if (VAPDateHelper::isNull($assoc[$i]->tsdate))
		{
			// get day of the week
			$date = JDate::getInstance()->dayToString($assoc[$i]->day, $abbr = true);
		}
		else
		{
			// get date of the year
			$date = JHtml::fetch('date', $assoc[$i]->tsdate, $config->get('dateformat'));
		}

		$from = JHtml::fetch('vikappointments.min2time', $assoc[$i]->fromts);
		$to   = JHtml::fetch('vikappointments.min2time', $assoc[$i]->endts);
		?>
		<li>
			<b><?php echo $assoc[$i]->name; ?></b> - <?php echo $date; ?>, <?php echo $from; ?> - <?php echo $to; ?>
		</li>
		<?php
	}

	// count remaining records
	$remaining = count($assoc) - 3;

	if ($remaining > 0)
	{
		?>
		<li><?php echo JText::plural('VAPWIZARDOTHER_N_ITEMS', $remaining); ?></li>
		<?php
	}
	?>
</ul>
