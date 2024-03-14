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
JHtml::fetch('bootstrap.tooltip', '.hasTooltip');
JHtml::fetch('vaphtml.assets.fontawesome');

$rows = $this->rows;

$filters = $this->filters;

$ordering = $this->ordering;

$vik = VAPApplication::getInstance();

$canEdit      = JFactory::getUser()->authorise('core.edit', 'com_vikappointments');
$canEditState = JFactory::getUser()->authorise('core.edit.state', 'com_vikappointments');

$is_searching = $this->hasFilters();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewApiusersList". The event method receives the
 * view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayListView($is_searching);

?>

<form action="index.php?option=com_vikappointments" method="post" name="adminForm" id="adminForm">

	<div class="btn-toolbar" style="height: 32px;">
		<div class="btn-group pull-left input-append">
			<input type="text" name="keysearch" id="vapkeysearch" size="32" 
				value="<?php echo $this->escape($filters['keysearch']); ?>" placeholder="<?php echo $this->escape(JText::translate('JSEARCH_FILTER_SUBMIT')); ?>" />

			<button type="submit" class="btn">
				<i class="fas fa-search"></i>
			</button>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewApiusersList","type":"search","key":"search"} -->

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

		<div class="btn-group pull-right">
			<a href="index.php?option=com_vikappointments&amp;view=apibans" class="btn">
				<i class="fas fa-ban"></i>&nbsp;
				<?php echo JText::translate('VAPMANAGEAPIUSER16'); ?>
			</a>
			
			<a href="index.php?option=com_vikappointments&amp;view=apilogs" class="btn">
				<i class="fas fa-tasks"></i>&nbsp;
				<?php echo JText::translate('VAPMANAGEAPIUSER12'); ?>
			</a>
		</div>
	</div>

	<div class="btn-toolbar" id="vap-search-tools" style="height: 32px;<?php echo ($is_searching ? '' : 'display: none;'); ?>">

		<?php
		$options = array(
			JHtml::fetch('select.option', -1, 'JOPTION_SELECT_PUBLISHED'),
			JHtml::fetch('select.option', 1, 'JPUBLISHED'),
			JHtml::fetch('select.option', 0, 'JUNPUBLISHED'),
		);
		?>
		<div class="btn-group pull-left">
			<select name="active" id="vap-active-sel" class="<?php echo ($filters['active'] != -1 ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['active'], true); ?>
			</select>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewApiusersList","type":"search","key":"filters"} -->

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
	<!-- {"rule":"customizer","event":"onDisplayApiusersTableTH","type":"th"} -->

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayApiusersTableTD","type":"td"} -->

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		
		<?php echo $vik->openTableHead(); ?>
			<tr>
				
				<th width="1%">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>

				<!-- ID -->
				
				<th class="<?php echo $vik->getAdminThClass('left hidden-phone nowrap'); ?>" width="1%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEGROUP1', 'a.id', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- APPLICATION -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="15%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEAPIUSER2', 'a.application', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- USERNAME -->
				
				<th class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>" width="15%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEAPIUSER3', 'a.username', $this->orderDir, $this->ordering); ?>
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

				<!-- IP RESTRICTIONS -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="8%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGEAPIUSER5'); ?>
				</th>

				<!-- STATUS -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="8%" style="text-align: center;">
					<?php echo JText::translate('VAPACTIVE'); ?>
				</th>

				<!-- LOGS -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="8%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGECRONJOB5'); ?>
				</th>

				<!-- LAST LOGIN -->
				
				<th class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>" width="12%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEAPIUSER7', 'a.last_login', $this->orderDir, $this->ordering); ?>
				</th>

			</tr>
		<?php echo $vik->closeTableHead(); ?>

		<?php
		$kk = 0;
		for ($i = 0; $i < count($rows); $i++)
		{
			$row = $rows[$i];

			$ips = (array) json_decode($row['ips']);

			$name = strlen($row['application']) ? $row['application'] : $row['username'];

			?>
			<tr class="row<?php echo $kk; ?>">

				<td>
					<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>">
				</td>

				<!-- ID -->
				
				<td class="hidden-phone">
					<?php echo $row['id']; ?>
				</td>

				<!-- APPLICATION -->
				
				<td>
					<div class="td-primary">
						<?php
						if ($canEdit)
						{
							?>
							<a href="index.php?option=com_vikappointments&amp;task=apiuser.edit&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $name; ?></a>
							<?php
						}
						else
						{
							echo $name;
						}
						?>
					</div>
				</td>

				<!-- USERNAME -->
				
				<td class="hidden-phone">
					<?php echo $row['username']; ?>
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

				<!-- IP RESTRICTIONS -->
				
				<td style="text-align: center;" class="hidden-phone">
					<?php
					if ($ips)
					{
						?>
						<a href="javascript:void(0)">
							<i class="fas fa-globe-americas big hasTooltip" title="<?php echo $this->escape(implode('<br />', $ips)); ?>"></i>
						</a>
						<?php
					}
					else
					{
						?>
						<a class="disabled">
							<i class="fas fa-globe-americas big"></i>
						</a>
						<?php
					}
					?>
				</td>

				<!-- STATUS -->
				
				<td style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.stateaction', $row['active'], $row['id'], 'apiuser.activate', $canEditState); ?>
				</td>

				<!-- LOGS -->
				
				<td style="text-align: center;">
					<?php
					if ($row['log'] !== null)
					{
						?>
						<a href="index.php?option=com_vikappointments&amp;view=apilogs&amp;id_login=<?php echo $row['id']; ?>">
							<i class="fas fa-file-alt big"></i>
						</a>
						<?php
					}
					else
					{
						?>
						<a class="disabled">
							<i class="fas fa-file-alt big"></i>
						</a>
						<?php
					}
					?>
				</td>

				<!-- LAST LOGIN -->
				
				<td class="hidden-phone">
					<span style="float: left;margin-left: 10px;">
						<?php
						if ($row['last_login'] > 0)
						{
							echo JHtml::fetch('date.relative', $row['last_login'], null, null, JText::translate('DATE_FORMAT_LC2'));
						}
						else
						{
							echo JText::translate('VAPMANAGEAPIUSER10');
						}
						?>
					</span>

					<span style="float: right;margin-right: 10px;">
						<?php
						if ($row['log'] === null && VAPDateHelper::isNull($row['last_login']))
						{
							// no activity
							$color = '999';
						}
						else if ($row['log'] === null || $row['log']['status'])
						{
							// success
							$color = '090';
						}
						else
						{
							// failure
							$color = '900';
						}
						?> 

						<i class="fas fa-circle big" style="color: #<?php echo $color; ?>;"></i>
					</span>
				</td>

			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php
}
?>
	
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="apiusers" />

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
		jQuery('#vap-active-sel').updateChosen(-1);
		
		document.adminForm.submit();
	}
	
</script>
