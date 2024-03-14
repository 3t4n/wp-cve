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
 * @param 	string 	 id      The ID of the tab.
 * @param 	boolean	 active  True if active, false otherwise.
 * @param 	array    tabs    A list of supported sub-tabs.
 * @param 	object   setup   An object containing some tabs options.
 * @param 	string   hook    The alias that will be used by plugin events.
 * @param 	string   suffix  An optional suffix to support different configurations.
 * @param 	string   before  An optional HTML to place before the fieldsets.
 * @param 	string   after   An optional HTML to place after the fieldsets.
 */
extract($displayData);

$suffix = isset($suffix) ? $suffix : '';

$cookie = JFactory::getApplication()->input->cookie;
$activeTabPane = $cookie->getString('vikappointments_config' . $suffix . '_tab' . $id, '');

$tmp = array();

$active_found = false;

foreach ($tabs as $title => $html)
{
	// make ID safe
	$k = strtolower(preg_replace("/[^a-z0-9\-_]+/i", '', $title));

	// check whether the resulting ID is empty or
	// already occupied by a different tab
	if (strlen($k) == 0 || isset($tmp[$k]))
	{
		// append list length to make it unique
		$k .= count($tmp);
	}

	if (empty($activeTabPane))
	{
		$activeTabPane = $k;	
	}

	$data = new stdClass;
	$data->id     = $k;
	$data->title  = JText::translate($title);
	$data->icon   = !empty($setup->icons[$title]) ? $setup->icons[$title] : 'fas fa-plug';
	$data->html   = $html;
	$data->active = $activeTabPane == $k;

	$active_found = $active_found || $data->active;

	$tmp[$k] = $data;
}

$tabs = $tmp;
unset($tmp);

if ($tabs && !$active_found)
{
	// no active tab found, probably the current selected tab
	// was implemented by a plugin, which is no more active
	reset($tabs)->active = true;
}

?>

<div id="vaptabview<?php echo $id; ?>" class="vaptabview" style="<?php echo ($active ? '' : 'display: none;'); ?>">

	<?php
	if (count($tabs) > 1)
	{
		?>
		<div class="config-panel-subnav">
			<ul>
				<?php
				foreach ($tabs as $tab)
				{
					?>
					<li class="<?php echo $tab->active ? 'active' : ''; ?>" data-id="<?php echo $tab->id; ?>">
						<i class="<?php echo $tab->icon; ?>"></i>
						
						<span class="hidden-phone">
							<?php echo $tab->title; ?>
						</span>
					</li>
					<?php
				}
				?>
			</ul>
		</div>
		<?php
	}
	?>

	<div class="config-panel-tabview">

		<?php

		if (isset($before))
		{
			echo $before;
		}

		foreach ($tabs as $tab)
		{
			?>
			<div class="config-panel-tabview-inner" data-id="<?php echo $tab->id; ?>" style="<?php echo ($tab->active ? '' : 'display:none;'); ?>">
				<?php echo $tab->html; ?>
			</div>
			<?php
		}

		if (isset($after))
		{
			echo $after;
		}
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfig<?php echo $suffix . $hook; ?>","type":"tab"} -->

	</div>
	
</div>
