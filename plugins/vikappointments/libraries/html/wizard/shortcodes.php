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

$shortcodes = $step->getShortcodes();

if (!count($shortcodes))
{
	// go ahead only in case of shortcodes
	return;
}

$vik = VAPApplication::getInstance();

?>

<ul class="wizard-step-summary">
	<?php
	// display at most 4 shortcodes
	for ($i = 0; $i < min(array(4, count($shortcodes))); $i++)
	{
		?>
		<li>
			<b><?php echo $shortcodes[$i]->name; ?></b>
			<span class="badge badge-important"><?php echo JText::translate($shortcodes[$i]->title); ?></span>
		</li>
		<?php
	}

	// count remaining shortcodes
	$remaining = count($shortcodes) - 4;

	if ($remaining > 0)
	{
		?>
		<li><?php echo JText::plural('VAPWIZARDOTHER_N_ITEMS', $remaining); ?></li>
		<?php
	}
	?>
</ul>

<?php
if ($step->needShortcode('appointments'))
{
	echo $vik->alert(__('Define a shortcode also for the appointments section.', 'vikappointments'));
}
else if ($step->needShortcode('packages'))
{
	echo $vik->alert(__('Define a shortcode also for the packages section.', 'vikappointments'));
}
else if ($step->needShortcode('subscriptions'))
{
	echo $vik->alert(__('Define a shortcode also for the subscriptions section.', 'vikappointments'));
}
