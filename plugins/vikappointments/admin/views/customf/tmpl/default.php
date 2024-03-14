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

$currency = VAPFactory::getCurrency();

$canEdit      = $user->authorise('core.edit', 'com_vikappointments');
$canEditState = $user->authorise('core.edit.state', 'com_vikappointments');
$canOrder     = $this->ordering == 'f.ordering';

if ($canOrder && $canEditState)
{
	$saveOrderingUrl = 'index.php?option=com_vikappointments&task=customf.saveOrderAjax&tmpl=component';
	JHtml::fetch('vaphtml.scripts.sortablelist', 'customFieldsList', 'adminForm', $this->orderDir, $saveOrderingUrl, array('group' => $this->filters['group']));
}

$is_searching = $this->hasFilters();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewCustomfList". The event method receives the
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
		<!-- {"rule":"customizer","event":"onDisplayViewOptionsList","type":"search","key":"search"} -->

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

		<?php
		$options = array();
		$options[] = JHtml::fetch('select.option', 0, JText::translate('VAPMENUCUSTOMERS'));
		$options[] = JHtml::fetch('select.option', 1, JText::translate('VAPMENUEMPLOYEES'));
		?>
		<div class="btn-group pull-right">
			<select name="group" id="vap-group-sel" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['group']); ?>
			</select>
		</div>

	</div>

	<div class="btn-toolbar" id="vap-search-tools" style="height: 32px;<?php echo ($is_searching ? '' : 'display: none;'); ?>">

		<?php
		$options = array();
		$options[] = JHtml::fetch('select.option', '', JText::translate('VAPFILTERSELECTTYPE'));
			
		// get list of supported types
		$types = VAPCustomFieldsFactory::getSupportedTypes();

		foreach ($types as $k => $type)
		{
			$options[] = JHtml::fetch('select.option', $k, $type);
		}
		?>
		<div class="btn-group pull-left">
			<select name="type" id="vap-type-sel" class="<?php echo (!empty($filters['type']) ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['type']); ?>
			</select>
		</div>

		<?php
		if ($filters['group'] == 0)
		{
			$options = array();
			$options[] = JHtml::fetch('select.option', -1, JText::translate('VAPFILTERSELECTRULE'));

			// get list of supported rules
			$rules = VAPCustomFieldsFactory::getSupportedRules();

			foreach ($rules as $k => $rule)
			{
				$options[] = JHtml::fetch('select.option', $k, $rule);
			}
			?>
			<div class="btn-group pull-left">
				<select name="rule" id="vap-rule-sel" class="<?php echo ($filters['rule'] != -1 ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
					<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['rule']); ?>
				</select>
			</div>

			<?php
			$options = array();
			$options[] = JHtml::fetch('select.option', -1, JText::translate('VAPFILTERSELECTOWNER'));
			$options[] = JHtml::fetch('select.option', 0, JText::translate('VAPMENUTITLEHEADER3'));
			$options[] = JHtml::fetch('select.option', 1, JText::translate('VAPMENUEMPLOYEES'));
			$options[] = JHtml::fetch('select.option', 2, JText::translate('VAPMENUSERVICES'));
			?>
			<div class="btn-group pull-left">
				<select name="owner" id="vap-owner-sel" class="<?php echo ($filters['owner'] != -1 ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
					<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['owner']); ?>
				</select>
			</div>

		<?php } ?>

		<?php
		$options = array();
		$options[] = JHtml::fetch('select.option', '', JText::translate('VAPFILTERSELECTSTATUS'));
		$options[] = JHtml::fetch('select.option', 1, JText::translate('VAPREQUIRED'));
		$options[] = JHtml::fetch('select.option', 0, JText::translate('VAPOPTIONAL'));
		$options[] = JHtml::fetch('select.option', 2, JText::translate('VAPMANAGECUSTOMF14'));
		?>
		<div class="btn-group pull-left">
			<select name="status" id="vap-status-sel" class="<?php echo (strlen($filters['status']) ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['status']); ?>
			</select>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewOptionsList","type":"search","key":"filters"} -->

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
	<!-- {"rule":"customizer","event":"onDisplayCustomfTableTH","type":"th"} -->

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayCustomfTableTD","type":"td"} -->

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>" id="customFieldsList">
		<?php echo $vik->openTableHead(); ?>
			<tr>

				<th width="1%">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>

				<!-- ID -->

				<th class="<?php echo $vik->getAdminThClass('left hidden-phone nowrap'); ?>" width="1%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEOPTION1', 'f.id', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- NAME -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="15%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGECUSTOMF1', 'f.name', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- TYPE -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="10%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGECUSTOMF2'); ?>
				</th>

				<!-- RULE -->

				<?php
				if ($filters['group'] == 0)
				{
					?>
					<th class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>" width="10%" style="text-align: left;">
						<?php echo JText::translate('VAPMANAGECUSTOMF12'); ?>
					</th>
					<?php
				}
				?>

				<!-- REQUIRED -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="5%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGECUSTOMF3'); ?>
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
				
				<?php
				if ($filters['group'] == 0)
				{
					?>
					<!-- EMPLOYEE -->
					
					<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="10%" style="text-align: center;">
						<?php echo JText::translate('VAPMANAGECUSTOMF10'); ?>
					</th>

					<!-- SERVICES -->
					
					<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="8%" style="text-align: center;">
						<?php echo JText::translate('VAPMENUSERVICES'); ?>
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
					<?php echo JHtml::fetch('vaphtml.admin.sort', '<i class="fas fa-sort"></i>', 'f.ordering', $this->orderDir, $this->ordering); ?>
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

				<!-- NAME -->
				
				<td>
					<div class="td-primary">
						<?php
						if ($canEdit)
						{
							?>
							<a href="index.php?option=com_vikappointments&amp;task=customf.edit&amp;cid[]=<?php echo $row['id']; ?>">
								<?php echo JText::translate($row['name']); ?>
							</a>
							<?php
						}
						else
						{
							echo JText::translate($row['name']);
						}
						?>
					</div>
				</td>

				<!-- TYPE -->
				
				<td>
					<?php
					if (isset($types[$row['type']]))
					{
						echo $types[$row['type']];
					}
					else
					{
						?>
						<span class="badge badge-warning"><?php echo $row['type']; ?></span>
						<?php
					}
					?>
				</td>

				<!-- RULE -->

				<?php
				if ($filters['group'] == 0)
				{
					?>
					<td class="hidden-phone">
						<?php 
						if ($row['rule'])
						{
							switch ($row['rule'])
							{
								case 'nominative':
									$clazz = 'male';
									break;
								
								case 'email':
									$clazz = 'envelope';
									break;

								case 'phone':
									$clazz = 'mobile-alt';
									break;

								case 'state':
									$clazz = 'map';
									break;

								case 'city':
									$clazz = 'map-signs';
									break;

								case 'address':
									$clazz = 'road';
									break;

								case 'zip':
									$clazz = 'map-marker-alt';
									break;

								case 'company':
									$clazz = 'building';
									break;

								case 'vatnum':
									$clazz = 'briefcase';
									break;

								default:
									$clazz = 'plug';
							}

							?>
							<span class="td-pull-left">
								<?php
								if (isset($rules[$row['rule']]))
								{
									echo $rules[$row['rule']];
								}
								else
								{
									?>
									<span class="badge badge-warning"><?php echo $row['rule']; ?></span>
									<?php
								}
								?>
							</span>

							<span class="td-pull-right">
								<i class="fas fa-<?php echo $clazz; ?> big" style="width:32px;text-align:center;"></i>
							</span>
							<?php
						}
						?>
					</td>
					<?php
				}
				?>

				<!-- REQUIRED -->
				
				<td style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.stateaction', $row['required'], $row['id'], 'customf.required', $canEditState && $row['type'] != 'separator'); ?>
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
				
				if ($filters['group'] == 0)
				{
					?>
					<!-- EMPLOYEE -->
					
					<td style="text-align: center;" class="hidden-phone">
						<?php echo $row['ename'] ? $row['ename'] : JText::translate('VAPMANAGECUSTOMF11'); ?>
					</td>

					<!-- SERVICE -->

					<td style="text-align: center;" class="hidden-phone">
						<?php echo !empty($row['services_count']) ? $row['services_count'] : JText::translate('VAPMANAGECUSTOMF11'); ?>
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
						<a href="index.php?option=com_vikappointments&amp;view=langcustomf&amp;id_customf=<?php echo $row['id']; ?>">
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
	<input type="hidden" name="view" value="customf" />

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
		// jQuery('#vap-group-sel').updateChosen(0);
		jQuery('#vap-type-sel').updateChosen('');
		jQuery('#vap-rule-sel').updateChosen(-1);
		jQuery('#vap-owner-sel').updateChosen(-1);
		jQuery('#vap-status-sel').updateChosen('');
		
		document.adminForm.submit();
	}

</script>
