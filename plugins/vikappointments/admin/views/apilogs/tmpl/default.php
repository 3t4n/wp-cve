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
JHtml::fetch('vaphtml.assets.fontawesome');
JHtml::fetch('vaphtml.assets.toast', 'bottom-right');

$rows = $this->rows;

$filters = $this->filters;

$ordering = $this->ordering;

$vik = VAPApplication::getInstance();

$is_searching = $this->hasFilters();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewApilogsList". The event method receives the
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
		<!-- {"rule":"customizer","event":"onDisplayViewApilogsList","type":"search","key":"search"} -->

		<?php
		// plugins can use the "search" key to introduce custom
		// filters within the search bar
		if (isset($forms['search']))
		{
			echo $forms['search'];
		}

		// in case a plugin needs to use the filter bar, display the button
		if (isset($forms['filters']))
		{
			?>
			<div class="btn-group pull-left">
				<button type="button" class="btn <?php echo ($is_searching ? 'btn-primary' : ''); ?>" onclick="vapToggleSearchToolsButton(this);">
					<?php echo JText::translate('JSEARCH_TOOLS'); ?>&nbsp;<i class="fas fa-caret-<?php echo ($is_searching ? 'up' : 'down'); ?>" id="vap-tools-caret"></i>
				</button>
			</div>
			<?php
		}
		?>
		
		<div class="btn-group pull-left">
			<button type="button" class="btn" onclick="clearFilters();">
				<?php echo JText::translate('JSEARCH_FILTER_CLEAR'); ?>
			</button>
		</div>
	</div>

	<div class="btn-toolbar" id="vap-search-tools" style="height: 32px;<?php echo ($is_searching ? '' : 'display: none;'); ?>">

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewApilogsList","type":"search","key":"filters"} -->

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
	<!-- {"rule":"customizer","event":"onDisplayApilogsTableTH","type":"th"} -->

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayApilogsTableTD","type":"td"} -->

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		
		<?php echo $vik->openTableHead(); ?>
			<tr>
				
				<th width="1%">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>

				<!-- ID -->
				
				<th class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>" width="1%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGEGROUP1'); ?>
				</th>

				<!-- CREATED ON -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="15%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEMEDIA14', 'l.createdon', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- CONTENT -->
				
				<th class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>" width="30%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGECRONJOBLOG3'); ?>
				</th>

				<!-- PAYLOAD -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="2%" style="text-align: center;">
					&nbsp;	
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

				<!-- STATUS -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="15%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGECRONJOBLOG4', 'l.status', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- IP ADDRESS -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="10%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGEAPIUSER17'); ?>
				</th>

			</tr>
		<?php echo $vik->closeTableHead(); ?>

		<?php
		$kk = 0;
		for ($i = 0; $i < count($rows); $i++)
		{
			$row = $rows[$i];
			?>
			<tr class="row<?php echo $kk; ?>">

				<td>
					<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>">
				</td>

				<!-- ID -->
				
				<td class="hidden-phone">
					<?php echo $row['id']; ?>
				</td>

				<!-- CREATION DATE -->
				
				<td>
					<span class="hasTooltip" title="<?php echo $this->escape(JHtml::fetch('date', $row['createdon'], JText::translate('DATE_FORMAT_LC2'))); ?>">
						<?php echo JHtml::fetch('date.relative', $row['createdon'], null, null, JText::translate('DATE_FORMAT_LC2')); ?>
					</span>

					<div>
						<?php
						if ($row['application'] || $row['username'])
						{
							?>
							<span class="badge">
								<?php echo $row['application'] ? $row['application'] : $row['username']; ?>
							</span>
							<?php
						}
						?>
					</div>
				</td>

				<!-- CONTENT -->

				<td class="hidden-phone">
					<?php echo nl2br($row['content']); ?>
				</td>

				<!-- PAYLOAD -->

				<td style="text-align: center;">
					<?php
					if ($row['payload'])
					{
						?>
						<a href="javascript:void(0)" onclick="openPayloadModal(<?php echo $row['id']; ?>);">
							<i class="fas fa-code-branch big"></i>
						</a>
						<?php
					}
					else
					{
						?>
						<a class="disabled">
							<i class="fas fa-code-branch big"></i>
						</a>
						<?php
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

				<!-- STATUS -->
				
				<td style="text-align: center;">
					<b style="text-transform:uppercase;color:#<?php echo ($row['status'] ? '090' : '900'); ?>">
						<?php echo JText::translate($row['status'] ? 'VAPCRONLOGSTATUSOK' : 'VAPCRONLOGSTATUSERROR'); ?>
					</b>
				</td>

				<!-- IP ADDRESS -->
				
				<td style="text-align: center;" class="hidden-phone">
					<?php echo $row['ip']; ?>
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
	<input type="hidden" name="view" value="apilogs" />
	<input type="hidden" name="id_login" value="<?php echo $filters['id_login']; ?>" />

	<input type="hidden" name="filter_order" value="<?php echo $this->ordering; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->orderDir; ?>" />

	<?php echo JHtml::fetch('form.token'); ?>
	<?php echo $this->navbut; ?>
</form>

<?php
foreach ($rows as $row)
{
	$this->currentLog = $row;

	echo JHtml::fetch(
		'bootstrap.renderModal',
		'jmodal-payload-' . $row['id'],
		array(
			'title'       => JText::translate('VAPMANAGEAPIUSER22'),
			'closeButton' => true,
			'keyboard'    => false, 
			'bodyHeight'  => 80,
			'footer'      => '<button type="button" class="btn" data-role="copy-payload" data-id="' . $row['id'] . '">' . JText::translate('VAPCOPY') . '</button>',
		),
		$this->loadTemplate('payload')
	);
}

JText::script('VAPSYSTEMCONFIRMATIONMSG');
JText::script('VAPCOPIED');
?>

<script>
	
	function clearFilters() {
		jQuery('#vapkeysearch').val('');
		
		document.adminForm.submit();
	}

	function openPayloadModal(id) {
		vapOpenJModal('payload-' + id, null, true);
	}

	function vapOpenJModal(id, url, jqmodal) {
		<?php echo $vik->bootOpenModalJS(); ?>
	}

	(function($) {
		'use strict';

		$(function() {
			$('button[data-role="copy-payload"]').on('click', function() {
				const textarea  = $('#payload_copy_' + $(this).data('id'));
				
				copyToClipboard(textarea[0]).then(() => {
					ToastMessage.dispatch(Joomla.JText._('VAPCOPIED'));
				}).catch((err) => {
					// do nothing on error
				});
			});
		});

		Joomla.submitbutton = (task) => {
			if (task == 'apilog.truncate' && !confirm(Joomla.JText._('VAPSYSTEMCONFIRMATIONMSG'))) {
				return false;
			}
			
			Joomla.submitform(task, document.adminForm);
		}
	})(jQuery);
	
</script>
