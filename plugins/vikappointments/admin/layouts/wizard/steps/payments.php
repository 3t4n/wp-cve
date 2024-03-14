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
?>

<ul class="wizard-step-summary">
	<?php
	$payments = $step->getPayments();

	// display at most 3 payments
	for ($i = 0; $i < min(array(3, count($payments))); $i++)
	{
		?>
		<li>
			<?php echo JHtml::fetch('vaphtml.admin.stateaction', $payments[$i]->published); ?>
			<b><?php echo $payments[$i]->name; ?></b>
			<span class="badge badge-info"><?php echo $payments[$i]->file; ?></span>
		</li>
		<?php
	}

	// count remaining payments
	$remaining = count($payments) - 3;

	if ($remaining > 0)
	{
		?>
		<li><?php echo JText::plural('VAPWIZARDOTHER_N_ITEMS', $remaining); ?></li>
		<?php
	}
	?>
</ul>
