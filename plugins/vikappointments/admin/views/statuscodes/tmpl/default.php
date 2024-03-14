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

JHtml::fetch('formbehavior.chosen');

$rows = $this->rows;

$filters = $this->filters;

$vik = VAPApplication::getInstance();

$multi_lang = VikAppointments::isMultilanguage();

$user = JFactory::getUser();

$canEdit      = $user->authorise('core.edit', 'com_vikappointments');
$canEditState = $user->authorise('core.edit.state', 'com_vikappointments');
$canOrder     = $this->ordering == 's.ordering';

if ($canOrder && $canEditState)
{
	$saveOrderingUrl = 'index.php?option=com_vikappointments&task=statuscode.saveOrderAjax&tmpl=component';
	JHtml::fetch('vaphtml.scripts.sortablelist', 'statuscodesList', 'adminForm', $this->orderDir, $saveOrderingUrl);
}

$is_searching = $this->hasFilters();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewStatuscodesList". The event method receives the
 * view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayListView($is_searching);

?>

<form action="index.php?option=com_vikappointments" method="post" name="adminForm" id="adminForm">

	<div class="btn-toolbar" style="height: 32px;">

		<div class="btn-group pull-left input-append">
			<input type="text" name="keys" id="vapkeysearch" size="32" 
				value="<?php echo $this->escape($filters['keys']); ?>" placeholder="<?php echo $this->escape(JText::translate('JSEARCH_FILTER_SUBMIT')); ?>" />

			<button type="submit" class="btn">
				<i class="fas fa-search"></i>
			</button>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewStatuscodesList","type":"search","key":"search"} -->

		<?php
		// plugins can use the "search" key to introduce custom
		// filters within the search bar
		if (isset($forms['search']))
		{
			echo $forms['search'];
		}
		?>

		<div class="btn-group pull-left">
			<button type="button" class="btn <?php echo ($is_searching ? 'btn-primary' : ''); ?>" onclick="vapToggleSearchToolsButton(this);">
				<?php echo JText::translate('JSEARCH_TOOLS'); ?>&nbsp;<i class="fas fa-caret-<?php echo ($is_searching ? 'up' : 'down'); ?>" id="vap-tools-caret"></i>
			</button>
		</div>
		
		<div class="btn-group pull-left">
			<button type="button" class="btn" onclick="clearFilters();">
				<?php echo JText::translate('JSEARCH_FILTER_CLEAR'); ?>
			</button>
		</div>

	</div>

	<div class="btn-toolbar" id="vap-search-tools" style="height: 32px;<?php echo ($is_searching ? '' : 'display: none;'); ?>">

		<?php
		$options = array(
			JHtml::fetch('select.option', '', 'VAPFILTERSELECTGROUP'),
			JHtml::fetch('select.option', 'appointments', 'VAPMENUTITLEHEADER2'),
			JHtml::fetch('select.option', 'packages', 'VAPMENUPACKAGES'),
			JHtml::fetch('select.option', 'subscriptions', 'VAPMENUSUBSCRIPTIONS'),
		);
		?>
		<div class="btn-group pull-left">
			<select name="group" id="vap-group-sel" class="<?php echo ($filters['group'] ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['group'], true); ?>
			</select>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewStatuscodesList","type":"search","key":"filters"} -->

		<?php
		// plugins can use the "filters" key to introduce custom
		// filters within the search bar
		if (isset($forms['filters']))
		{
			echo $forms['filters'];
		}
		?>

	</div>

<?php
if (count($rows) == 0)
{
	echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
}
else
{
	/**
	 * Trigger event to display custom columns.
	 *
	 * @since 1.7
	 */
	$columns = $this->onDisplayTableColumns();
	?>

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayStatuscodesTableTH","type":"th"} -->

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayStatuscodesTableTD","type":"td"} -->

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>" id="statuscodesList">
		<?php echo $vik->openTableHead(); ?>
			<tr>

				<th width="1%">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>

				<!-- ID -->

				<th class="<?php echo $vik->getAdminThClass('left hidden-phone nowrap'); ?>" width="1%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEOPTION1', 's.id', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- NAME -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="15%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEOPTION2', 's.name', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- DESCRIPTION -->
				
				<th class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>" width="30%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGEOPTION3'); ?>
				</th>

				<!-- CODE -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="8%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGECOUPON2', 's.code', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- COLOR -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="8%" style="text-align: center;">
					<?php echo JText::translate('VAPCOLOR'); ?>
				</th>

				<!-- CUSTOM -->

				<?php
				foreach ($columns as $k => $col)
				{
					?>
					<th data-id="<?php echo $this->escape($k); ?>" class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>">
						<?php echo $col->th; ?>
					</th>
					<?php
				}
				?>

				<!-- LANGUAGES -->

				<?php
				if ($multi_lang && $canEdit)
				{
					?>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="8%" style="text-align: center;">
						<?php echo JText::translate('VAPLANGUAGES');?>
					</th>
					<?php
				}
				?>

				<!-- ORDERING -->

				<th class="<?php echo $vik->getAdminThClass('hidden-phone nowrap'); ?>" width="1%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', '<i class="fas fa-sort"></i>', 's.ordering', $this->orderDir, $this->ordering); ?>
				</th>

			</tr>
		<?php echo $vik->closeTableHead(); ?>
		
		<?php
		for ($i = 0, $n = count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];

			$desc = strip_tags((string) $row['description']);

			if (strlen($desc) > 300)
			{
				$desc = trim(mb_substr($desc, 0, 256, 'UTF-8'), ' .') . '...';
			}
			?>
			<tr class="row<?php echo ($i % 2); ?>">
				
				<td>
					<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>">
				</td>

				<!-- ID -->
				
				<td class="hidden-phone">
					<?php echo $row['id']; ?>
				</td>

				<!-- NAME -->
				
				<td>
					<div class="td-primary">
						<?php
						if ($canEdit)
						{
							?>
							<a href="index.php?option=com_vikappointments&amp;task=statuscode.edit&amp;cid[]=<?php echo $row['id']; ?>">
								<?php echo $row['name']; ?>
							</a>
							<?php
						}
						else
						{
							echo $row['name'];
						}
						?>
					</div>
				</td>

				<!-- DESCRIPTION -->
				
				<td class="hidden-phone">
					<?php echo $desc; ?>
				</td>

				<!-- CODE -->
				
				<td style="text-align: center;">	
					<?php echo $row['code']; ?>
				</td>

				<!-- COLOR -->
				
				<td style="text-align: center;">	
					<span style="background: #<?php echo $row['color']; ?>;display: inline-block;width: 16px;height: 16px;border-radius: 4px;"></span>
				</td>

				<!-- CUSTOM -->

				<?php
				foreach ($columns as $k => $col)
				{
					?>
					<td data-id="<?php echo $this->escape($k); ?>" class="hidden-phone">
						<?php echo isset($col->td[$i]) ? $col->td[$i] : ''; ?>
					</td>
					<?php
				}
				?>

				<!-- LANGUAGES -->

				<?php
				if ($multi_lang && $canEdit)
				{
					?>
					<td style="text-align: center;">
						<a href="index.php?option=com_vikappointments&amp;view=langstatuscodes&amp;id_status_code=<?php echo $row['id']; ?>">
							<?php
							foreach ($row['languages'] as $lang)
							{
								echo ' ' . JHtml::fetch('vaphtml.site.flag', $lang) . ' ';
							}
							?>
						</a>
					</td>
					<?php
				}
				?>

				<!-- ORDERING -->

				<td class="order nowrap center hidden-phone">
					<?php echo JHtml::fetch('vaphtml.admin.sorthandle', $row['ordering'], $canEditState, $canOrder); ?>
				</td>

			</tr>
			<?php
		}
		?>
	</table>
	<?php
}
?>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="statuscodes" />
	
	<input type="hidden" name="filter_order" value="<?php echo $this->ordering; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->orderDir; ?>" />

	<?php echo JHtml::fetch('form.token'); ?>
	<?php echo $this->navbut; ?>
</form>

<?php
JText::script('VAP_STATUS_CODES_FACTORY_RESET_CONFIRM');
?>

<script>

	(function($) {
		'use strict';

		window['clearFilters'] = () => {
			$('#vapkeysearch').val('');
			$('#vap-group-sel').updateChosen('');
			
			document.adminForm.submit();
		}

		// override the Joomla submit button
		Joomla.submitbutton = (task) => {
			const msg = Joomla.JText._('VAP_STATUS_CODES_FACTORY_RESET_CONFIRM');

			// ask for a manual confirmation before applying the factory reset
			if (task == 'statuscode.restore' && !confirm(msg)) {
				return false;
			}

			Joomla.submitform(task, document.adminForm);
		}

		$(function() {
			VikRenderer.chosen('.btn-toolbar');
		});
	})(jQuery);
	
</script>
