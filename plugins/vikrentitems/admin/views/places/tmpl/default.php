<?php
/**
 * @package     VikRentItems
 * @subpackage  com_vikrentitems
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

defined('ABSPATH') or die('No script kiddies please!');

$rows = $this->rows;
$lim0 = $this->lim0;
$navbut = $this->navbut;

if (empty($rows)) {
	?>
	<p class="warn"><?php echo JText::translate('VRNOPLACESFOUND'); ?></p>
	<form action="index.php?option=com_vikrentitems" method="post" name="adminForm" id="adminForm">
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="option" value="com_vikrentitems" />
	</form>
	<?php
} else {
	
	?>
<script type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'removeplace') {
		if (confirm('<?php echo JText::translate('VRJSDELPLACES'); ?> ?')) {
			submitform( pressbutton );
			return;
		} else{
			return false;
		}
	}

	// do field validation
	try {
		document.adminForm.onsubmit();
	}
	catch(e) {}
	submitform( pressbutton );
}
</script>

<form class="vri-list-form" action="index.php?option=com_vikrentitems" method="post" name="adminForm" id="adminForm">
	<div class="table-responsive">
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped vri-list-table">
			<thead>
				<tr>
					<th width="20">
						<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
					</th>
					<th class="title left" width="150"><?php echo JText::translate( 'VRPVIEWPLACESONE' ); ?></th>
					<th class="title left" width="150"><?php echo JText::translate( 'VRILOCADDRESS' ); ?></th>
					<th class="title center" width="100" align="center"><?php echo JText::translate( 'VRIPLACELAT' ); ?></th>
					<th class="title center" width="100" align="center"><?php echo JText::translate( 'VRIPLACELNG' ); ?></th>
					<th class="title left" width="150"><?php echo JText::translate( 'VRIPLACEDESCR' ); ?></th>
					<th class="title center" width="150" align="center"><?php echo JText::translate( 'VRIPLACEOPENTIME' ); ?></th>
					<th class="title center" width="100" align="center"><?php echo JText::translate( 'VRIORDERING' ); ?></th>
				</tr>
			</thead>
		<?php
		$k = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$opentime = "";
			if (!empty($row['opentime'])) {
				$parts = explode("-", $row['opentime']);
				$openat = VikRentItems::getHoursMinutes($parts[0]);
				$closeat = VikRentItems::getHoursMinutes($parts[1]);
				$opentime = ((int)$openat[0] < 10 ? "0".$openat[0] : $openat[0]).":".((int)$openat[1] < 10 ? "0".$openat[1] : $openat[1])." - ".((int)$closeat[0] < 10 ? "0".$closeat[0] : $closeat[0]).":".((int)$closeat[1] < 10 ? "0".$closeat[1] : $closeat[1]);
			}
			?>
			<tr class="row<?php echo $k; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikrentitems&amp;task=editplace&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td><?php echo $row['address']; ?></td>
				<td class="center"><?php echo $row['lat']; ?></td>
				<td class="center"><?php echo $row['lng']; ?></td>
				<td><?php echo strip_tags($row['descr']); ?></td>
				<td class="center"><?php echo $opentime; ?></td>
				<td class="center">
					<a href="index.php?option=com_vikrentitems&amp;task=sortlocation&amp;cid[]=<?php echo $row['id']; ?>&amp;mode=up"><?php VikRentItemsIcons::e('arrow-up', 'vri-icn-img'); ?></a> 
					<a href="index.php?option=com_vikrentitems&amp;task=sortlocation&amp;cid[]=<?php echo $row['id']; ?>&amp;mode=down"><?php VikRentItemsIcons::e('arrow-down', 'vri-icn-img'); ?></a>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
	</div>
	<input type="hidden" name="option" value="com_vikrentitems" />
	<input type="hidden" name="task" value="places" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::fetch( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>
<?php
}
