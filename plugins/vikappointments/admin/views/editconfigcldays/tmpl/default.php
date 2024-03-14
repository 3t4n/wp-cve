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

JHtml::fetch('bootstrap.tooltip', '.hasTooltip');
JHtml::fetch('vaphtml.assets.select2');
JHtml::fetch('vaphtml.assets.toast', 'bottom-right');

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigcldays". The event method receives the
 * view instance as argument.
 *
 * @since 1.6.6
 */
$forms = $this->onDisplayView();

/**
 * Recover selected tab from the browser cookie.
 *
 * @since 1.7
 */
$this->selectedTab = JFactory::getApplication()->input->cookie->get('vikappointments_configcldays_tab', 1, 'uint');

$tabs = $custTabs = array();

// build default tabs: closing days, closing periods
$tabs[] = JText::translate('VAPMANAGECONFIG11');
$tabs[] = JText::translate('VAPMANAGECONFIG28');

/**
 * Iterate all form items to be displayed as custom tabs within the nav bar.
 *
 * @since 1.6.6
 */
foreach ($forms as $tabName => $tabForms)
{
	// include tab
	$custTabs[] = JText::translate($tabName);
}

// make sure the selected tab is still available
if ($this->selectedTab > count($tabs) + count($custTabs))
{
	// reset to first tab
	$this->selectedTab = 1;
}
?>

<div class="configuration-panel">

	<div id="configuration-navbar">
		<ul>
			<?php
			foreach (array_merge($tabs, $custTabs) as $i => $tab)
			{
				$key = $i + 1;
				?>
				<li id="vaptabli<?php echo $key; ?>" class="vaptabli<?php echo ($this->selectedTab == $key ? ' vapconfigtabactive' : ''); ?>" data-id="<?php echo $key; ?>">
					<a href="javascript: void(0);"><?php echo $tab; ?></a>
				</li>
				<?php
			}
			?>
		</ul>
	</div>

	<?php
	// print config search bar
	// VAPLoader::import('libraries.widget.layout');
	// echo UIWidgetLayout::getInstance('searchbar')->display();
	?>

	<div id="configuration-body">

		<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">
			
			<?php
			// display default tab panes
			echo $this->loadTemplate('closingdays');
			echo $this->loadTemplate('closingperiods');
			
			$i = 0;

			/**
			 * Iterate all form items to be displayed as new panels of custom tabs.
			 *
			 * @since 1.6.6
			 */
			foreach ($forms as $formName => $formHtml)
			{
				// sanitize form name
				$key = count($tabs) + (++$i);

				?>
				<div id="vaptabview<?php echo $key; ?>" class="vaptabview" style="<?php echo ($this->selectedTab != $key ? 'display: none;' : ''); ?>">
					<?php echo $formHtml; ?>
				</div>
				<?php
			}
			?>

			<?php echo JHtml::fetch('form.token'); ?>
			
			<input type="hidden" name="option" value="com_vikappointments" />
			<input type="hidden" name="task" value=""/>
		</form>

	</div>

</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigcldays","type":"tab"} -->

<!-- SCRIPT -->

<?php
echo JLayoutHelper::render('configuration.script', array('suffix' => 'cldays'));
