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
JHtml::fetch('formbehavior.chosen');
JHtml::fetch('vaphtml.assets.fontawesome');

$rows = $this->rows;

$filters = $this->filters;

$vik = VAPApplication::getInstance();

$user = JFactory::getUser();

$canEdit = $user->authorise('core.edit', 'com_vikappointments');

$is_searching = $this->hasFilters();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewUsernotesList". The event method receives the
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
		<!-- {"rule":"customizer","event":"onDisplayViewUsernotesList","type":"search","key":"search"} -->

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

		<div class="btn-group pull-right hidden-phone">
			<a href="index.php?option=com_vikappointments&amp;view=tags&amp;group=usernotes" class="btn">
				<?php echo JText::translate('VAPMANAGETAGS'); ?>
			</a>
		</div>

	</div>

	<div class="btn-toolbar" id="vap-search-tools" style="height: 32px;<?php echo ($is_searching ? '' : 'display: none;'); ?>">

		<?php
		$options = array(
			JHtml::fetch('select.option', '', JText::translate('VAPFILTERSELECTSTATUS')),
			JHtml::fetch('select.option', 1, JText::translate('VAPPUBLIC')),
			JHtml::fetch('select.option', 0, JText::translate('VAPPRIVATE')),
		);
		?>
		<div class="btn-group pull-left">
			<select name="status" id="vap-status-sel" class="<?php echo (strlen($filters['status']) ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['status']); ?>
			</select>
		</div>

		<?php
		$tags = JHtml::fetch('vaphtml.admin.tags', 'usernotes', $blank = JText::translate('VAPFILTERSELECTTAG'));

		if ($tags)
		{
			?>
			<div class="btn-group pull-left">
				<select name="tag" id="vap-tag-sel" class="<?php echo ($filters['tag'] ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
					<?php echo JHtml::fetch('select.options', $tags, 'value', 'text', $filters['tag']); ?>
				</select>
			</div>
			<?php
		}
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewUsernotesList","type":"search","key":"filters"} -->

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
	<!-- {"rule":"customizer","event":"onDisplayUsernotesTableTH","type":"th"} -->

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayUsernotesTableTD","type":"td"} -->

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">

		<?php echo $vik->openTableHead(); ?>
			<tr>

				<th width="1%">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>

				<!-- ID -->

				<th class="<?php echo $vik->getAdminThClass('left hidden-phone nowrap'); ?>" width="1%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGESERVICE1', 'n.id', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- TITLE -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="25%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEREVIEW2', 'n.title', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- VISIBILITY -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="8%" style="text-align: center;">
					<?php echo JText::translate('VAPPUBLIC'); ?>
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

				<!-- AUTHOR -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone left'); ?>" width="10%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGERESERVATION41'); ?>
				</th>

				<!-- CREATION DATE -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone left'); ?>" width="10%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', JText::translate('VAPMANAGEMEDIA14'), 'n.createdon', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- MODIFICATION DATE -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone left'); ?>" width="10%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', JText::translate('VAPMANAGEMEDIA19'), 'n.modifiedon', $this->orderDir, $this->ordering); ?>
				</th>

			</tr>
		<?php echo $vik->closeTableHead(); ?>

		<?php
		for ($i = 0, $n = count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];
			?>
			<tr class="row<?php echo ($i % 2); ?>">

				<td>
					<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>">
				</td>
				
				<!-- ID -->
				
				<td class="hidden-phone">
					<?php echo $row['id']; ?>
				</td>

				<!-- TITLE -->
				
				<td>
					<div class="td-primary td-pull-left">
						<?php
						if ($canEdit)
						{
							?>
							<a href="index.php?option=com_vikappointments&amp;task=usernote.edit&amp;cid[]=<?php echo $row['id']; ?>">
								<?php echo $row['title']; ?>
							</a>
							<?php
						}
						else
						{
							echo $row['title'];
						}
						?>
					</div>

					<div class="td-pull-right">
						<?php
						// load tag details
						$tags = $this->tagModel->readTags($row['tags'], '*');

						foreach ($tags as $tag)
						{
							// render tag
							echo JHtml::fetch('vikappointments.tag', $tag, 'icon', array('class' => 'big', 'style' => 'margin-left: 2px;'));
						}
						?>
					</div>
				</td>

				<!-- VISIBILITY -->
				
				<td style="text-align: center;">
					<?php
					if ($row['status'])
					{
						?><i class="fas fa-eye ok big hasTooltip" title="<?php echo $this->escape(JText::translate('VAPPUBLIC')); ?>"></i><?php
					}
					else
					{
						?><i class="fas fa-low-vision no big hasTooltip" title="<?php echo $this->escape(JText::translate('VAPPRIVATE')); ?>"></i><?php
					}
					?>
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

				<!-- AUTHOR -->

				<td class="hidden-phone">
					<?php echo $row['author_name']; ?>
				</td>

				<!-- CREATION DATE -->

				<td class="hidden-phone">
					<?php echo JHtml::fetch('date', $row['createdon'], JText::translate('DATE_FORMAT_LC5')); ?>
				</td>

				<!-- MODIFICATION DATE -->

				<td class="hidden-phone">
					<?php
					if (!VAPDateHelper::isNull($row['modifiedon']))
					{
						echo JHtml::fetch('date', $row['modifiedon'], JText::translate('DATE_FORMAT_LC5'));
					}
					else
					{
						echo '--';
					}
					?>
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
	<input type="hidden" name="view" value="usernotes" />

	<input type="hidden" name="id_user" value="<?php echo $filters['id_user']; ?>" />
	<input type="hidden" name="id_parent" value="<?php echo $filters['id_parent']; ?>" />
	<input type="hidden" name="group" value="<?php echo $this->escape($filters['group']); ?>" />

	<input type="hidden" name="filter_order" value="<?php echo $this->ordering; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->orderDir; ?>" />

	<?php echo JHtml::fetch('form.token'); ?>
	<?php echo $this->navbut; ?>
</form>

<script>

	jQuery(function($) {
		VikRenderer.chosen('.btn-toolbar');
	});
	
	function clearFilters() {
		jQuery('#vapkeysearch').val('');
		jQuery('#vap-status-sel').updateChosen('');
		jQuery('#vap-tag-sel').updateChosen('');
		
		document.adminForm.submit();
	}
	
</script>
