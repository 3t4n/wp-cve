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

JHtml::fetch('vaphtml.assets.fancybox');
JHtml::fetch('vaphtml.assets.fontawesome');

$rows = $this->rows;

$filters = $this->filters;

$vik = VAPApplication::getInstance();

$multi_lang = VikAppointments::isMultilanguage();

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_vikappointments');

$gallery = array();

foreach ($rows as $media)
{
	/**
	 * Append timestamp of creation date in order to
	 * automatically remove the cached image in case
	 * of changes to the media file.
	 *
	 * @since 1.7
	 */
	$gallery[] = VAPMEDIA_URI . $media['name'] . '?' . $media['timestamp'];
}

$is_searching = $this->hasFilters();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewMediaList". The event method receives the
 * view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayListView($is_searching);

?>

<form action="index.php?option=com_vikappointments" method="post" name="adminForm" id="adminForm">

	<div class="btn-toolbar" style="height:32px;">
		<div class="btn-group pull-left input-append">
			<input type="text" name="keysearch" id="vapkeysearch" size="32" 
				value="<?php echo $this->escape($filters['keysearch']); ?>" placeholder="<?php echo $this->escape(JText::translate('JSEARCH_FILTER_SUBMIT')); ?>" />

			<button type="submit" class="btn">
				<i class="fas fa-search"></i>
			</button>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewMediaList","type":"search","key":"search"} -->

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
		<!-- {"rule":"customizer","event":"onDisplayViewMediaList","type":"search","key":"filters"} -->

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
	<!-- {"rule":"customizer","event":"onDisplayMediaTableTH","type":"th"} -->

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayMediaTableTD","type":"td"} -->

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>

				<th width="1%">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>

				<!-- NAME -->

				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="32%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEMEDIA2', 'name', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- IMAGE SIZE -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="10%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEMEDIA12', 'size', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- FILE SIZE -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="10%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEMEDIA13', 'filesize', $this->orderDir, $this->ordering); ?>
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

				<!-- CREATION DATE -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="15%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEMEDIA14', 'date', $this->orderDir, $this->ordering); ?>
				</th>

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

				<!-- IMAGE PREVIEW -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="15%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGEMEDIA3'); ?>
				</th>

			</tr>
		<?php echo $vik->closeTableHead(); ?>

		<?php
		for ($i = 0; $i < count($rows); $i++)
		{
			$row = $rows[$i];	 
			?>
			<tr class="row<?php echo $i % 2; ?>">

				<td>
					<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['name']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>">
				</td>

				<!-- NAME -->

				<td>
					<div class="name break-word">
						<?php
						if ($canEdit)
						{
							?>
							<a href="index.php?option=com_vikappointments&amp;task=media.edit&amp;cid[]=<?php echo $row['name']; ?>"><?php echo $row['name']; ?></a>
							<?php
						}
						else
						{
							echo $row['name'];
						}
						?>
					</div>
				</td>

				<!-- IMAGE SIZE -->
				
				<td style="text-align: center;" class="hidden-phone">
					<?php echo $row['width'] . ' x ' . $row['height']; ?>
				</td>

				<!-- FILE SIZE -->

				<td style="text-align: center;" class="hidden-phone">
					<?php echo $row['size']; ?>
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

				<!-- CREATION DATE -->

				<td style="text-align: center;" class="hidden-phone">
					<?php echo $row['creation']; ?>
				</td>

				<!-- LANGUAGES -->

				<?php
				if ($multi_lang && $canEdit)
				{
					?>
					<td style="text-align: center;">
						<a href="index.php?option=com_vikappointments&amp;view=langmedia&amp;image=<?php echo $row['name']; ?>">
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

				<!-- IMAGE PREVIEW -->

				<td style="text-align: center;">
					<a href="javascript: void(0);" class="vapmodal" onClick="vapOpenGallery(<?php echo $i; ?>);">
						<img src="<?php echo VAPMEDIA_SMALL_URI . $row['name'] . '?' . $row['timestamp']; ?>" style="max-width: 64px;height: auto;" />
					</a>
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
	<input type="hidden" name="view" value="media" />

	<input type="hidden" name="filter_order" value="<?php echo $this->ordering; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->orderDir; ?>" />

	<?php echo JHtml::fetch('form.token'); ?>
	<?php echo $this->navbut; ?>
</form>

<script type="text/javascript">

	function vapOpenGallery(index) {
		var instance = vapOpenModalImage(<?php echo json_encode($gallery); ?>);

		if (index > 0) {
			// jump to selected image ('0' turns off the animation)
			instance.jumpTo(index, 0);
		}
	}

	function clearFilters() {
		jQuery('#vapkeysearch').val('');
		
		document.adminForm.submit();
	}

</script>
