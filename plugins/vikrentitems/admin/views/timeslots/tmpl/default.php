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
	<p class="warn"><?php echo JText::translate('VRINOTIMESLOTS'); ?></p>
	<form action="index.php?option=com_vikrentitems" method="post" name="adminForm" id="adminForm">
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="option" value="com_vikrentitems" />
	</form>
	<?php
} else {

?>

<form class="vri-list-form" action="index.php?option=com_vikrentitems" method="post" name="adminForm" id="adminForm">
	<div class="table-responsive">
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped vri-list-table">
			<thead>
				<tr>
					<th width="20">
						<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
					</th>
					<th class="title left" width="200" align="left"><?php echo JText::translate( 'VRPSHOWTIMESLOTSONE' ); ?></th>
					<th class="title center" width="100"><?php echo JText::translate( 'VRPSHOWTIMESLOTSTWO' ); ?></th>
					<th class="title center" width="100"><?php echo JText::translate( 'VRPSHOWTIMESLOTSTHREE' ); ?></th>
					<th class="title center" width="150"><?php echo JText::translate( 'VRITIMESLOTDAYS' ); ?></th>
					<th class="title center" width="250"><?php echo JText::translate( 'VRPSHOWTIMESLOTSFOUR' ); ?></th>
				</tr>
			</thead>
			<?php
			$k = 0;
			$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			?>
			<tr class="row<?php echo $k; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikrentitems&amp;task=edittimeslot&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['tname']; ?></a></td>
				<td class="center"><?php echo ($row['fromh'] < 10 ? '0' : '').$row['fromh'].':'.($row['fromm'] < 10 ? '0' : '').$row['fromm']; ?></td>
				<td class="center"><?php echo ($row['toh'] < 10 ? '0' : '').$row['toh'].':'.($row['tom'] < 10 ? '0' : '').$row['tom']; ?></td>
				<td class="center"><?php echo $row['days']; ?></td>
				<td class="center"><?php echo $row['global'] == 1 ? "Y" : "N"; ?></td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
			
		</table>
	</div>
	<input type="hidden" name="option" value="com_vikrentitems" />
	<input type="hidden" name="task" value="timeslots" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::fetch( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>
<?php
}
