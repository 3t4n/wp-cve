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
$orderby = $this->orderby;
$ordersort = $this->ordersort;

if (empty($rows)) {
	?>
<p class="warn"><?php echo JText::translate('VRNOSTATSFOUND'); ?></p>
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
				<th class="title left" width="150">
					<a href="index.php?option=com_vikrentitems&amp;task=stats&amp;vrorderby=ts&amp;vrordersort=<?php echo ($orderby == "ts" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "ts" && $ordersort == "ASC" ? "vri-list-activesort" : ($orderby == "ts" ? "vri-list-activesort" : "")); ?>">
						<?php echo JText::translate( 'VRPVIEWSTATSONE' ); ?><?php echo ($orderby == "ts" && $ordersort == "ASC" ? '<i class="' . VikRentItemsIcons::i('sort-up') . '"></i>' : ($orderby == "ts" ? '<i class="' . VikRentItemsIcons::i('sort-down') . '"></i>' : '<i class="' . VikRentItemsIcons::i('sort') . '"></i>')); ?>
					</a>
				</th>
				<th class="title left" width="150"><?php echo JText::translate( 'VRPVIEWSTATSTWO' ); ?></th>
				<th class="title left" width="150">
					<a href="index.php?option=com_vikrentitems&amp;task=stats&amp;vrorderby=pickup&amp;vrordersort=<?php echo ($orderby == "pickup" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "pickup" && $ordersort == "ASC" ? "vri-list-activesort" : ($orderby == "pickup" ? "vri-list-activesort" : "")); ?>">
						<?php echo JText::translate( 'VRPVIEWSTATSTHREE' ); ?><?php echo ($orderby == "pickup" && $ordersort == "ASC" ? '<i class="' . VikRentItemsIcons::i('sort-up') . '"></i>' : ($orderby == "pickup" ? '<i class="' . VikRentItemsIcons::i('sort-down') . '"></i>' : '<i class="' . VikRentItemsIcons::i('sort') . '"></i>')); ?>
					</a>
				</th>
				<th class="title left" width="150">
					<a href="index.php?option=com_vikrentitems&amp;task=stats&amp;vrorderby=dropoff&amp;vrordersort=<?php echo ($orderby == "dropoff" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "dropoff" && $ordersort == "ASC" ? "vri-list-activesort" : ($orderby == "dropoff" ? "vri-list-activesort" : "")); ?>">
						<?php echo JText::translate( 'VRPVIEWSTATSFOUR' ); ?><?php echo ($orderby == "dropoff" && $ordersort == "ASC" ? '<i class="' . VikRentItemsIcons::i('sort-up') . '"></i>' : ($orderby == "dropoff" ? '<i class="' . VikRentItemsIcons::i('sort-down') . '"></i>' : '<i class="' . VikRentItemsIcons::i('sort') . '"></i>')); ?>
					</a>
				</th>
				<th class="title left" width="150"><?php echo JText::translate( 'VRPVIEWSTATSFIVE' ); ?></th>
				<th class="title left" width="150"><?php echo JText::translate( 'VRPVIEWSTATSSIX' ); ?></th>
				<th class="title center" width="150">
					<a href="index.php?option=com_vikrentitems&amp;task=stats&amp;vrorderby=res&amp;vrordersort=<?php echo ($orderby == "res" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "res" && $ordersort == "ASC" ? "vri-list-activesort" : ($orderby == "res" ? "vri-list-activesort" : "")); ?>">
						<?php echo JText::translate( 'VRPVIEWSTATSSEVEN' ); ?><?php echo ($orderby == "res" && $ordersort == "ASC" ? '<i class="' . VikRentItemsIcons::i('sort-up') . '"></i>' : ($orderby == "res" ? '<i class="' . VikRentItemsIcons::i('sort-down') . '"></i>' : '<i class="' . VikRentItemsIcons::i('sort') . '"></i>')); ?>
					</a>
				</th>
			</tr>
			</thead>
		<?php
		$nowdf = VikRentItems::getDateFormat(true);
		if ($nowdf == "%d/%m/%Y") {
			$df = 'd/m/Y';
		} elseif ($nowdf == "%m/%d/%Y") {
			$df = 'm/d/Y';
		} else {
			$df = 'Y/m/d';
		}
		$nowtf = VikRentItems::getTimeFormat(true);
		$kk = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			if (!empty($row['place'])) {
				$exp = explode(";", $row['place']);
				$place = VikRentItems::getPlaceName($exp[0]).(!empty($exp[1]) && $exp[0]!=$exp[1] ? " - ".VikRentItems::getPlaceName($exp[1]) : "");
			} else {
				$place = "";
			}
			$cat = JText::translate('VRANYTHING');
			if (!empty($row['cat'])) {
				$cat = ($row['cat'] == "all" ? JText::translate('VRANYTHING') : VikRentItems::getCategoryName($row['cat']));
			}
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><?php echo date($df.' '.$nowtf, $row['ts']); ?></td>
				<td><?php echo $row['ip']; ?></td>
				<td><?php echo date($df.' '.$nowtf, $row['ritiro']); ?></td>
				<td><?php echo date($df.' '.$nowtf, $row['consegna']); ?></td>
				<td><?php echo $place; ?></td>
				<td><?php echo $cat; ?></td>
				<td class="center"><?php echo intval($row['res']); ?></td>
			</tr>
			<?php
			$kk = 1 - $kk;
		}
		?>
		</table>
	</div>
	<input type="hidden" name="option" value="com_vikrentitems" />
	<input type="hidden" name="task" value="stats" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::fetch( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>
<?php
}
