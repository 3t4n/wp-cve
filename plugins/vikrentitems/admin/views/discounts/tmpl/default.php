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
	<p class="warn"><?php echo JText::translate('VRINODISCOUNTS'); ?></p>
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
					<th class="title left" width="150"><?php echo JText::translate( 'VRPSHOWDISCOUNTSONE' ); ?></th>
					<th class="title center" width="100"><?php echo JText::translate( 'VRPSHOWDISCOUNTSTWO' ); ?></th>
					<th class="title center" width="100"><?php echo JText::translate( 'VRPSHOWDISCOUNTSTHREE' ); ?></th>
					<th class="title center" width="250"><?php echo JText::translate( 'VRPSHOWDISCOUNTSFOUR' ); ?></th>
				</tr>
			</thead>
		<?php
		$currencysymb = VikRentItems::getCurrencySymb(true);
		$k = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			?>
			<tr class="row<?php echo $k; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td><a href="index.php?option=com_vikrentitems&amp;task=editdiscount&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['discname']; ?></a></td>
				<td class="center"><?php echo $row['quantity']; ?></td>
				<td class="center"><?php echo $row['ifmorequant'] == 1 ? "Y" : "N"; ?></td>
				<td class="center"><?php echo (intval($row['val_pcent']) == 1 ? $currencysymb.' ' : ''); ?><?php echo $row['diffcost']; ?><?php echo (intval($row['val_pcent']) == 1 ? '' : ' %'); ?></td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
	</div>
	<input type="hidden" name="option" value="com_vikrentitems" />
	<input type="hidden" name="task" value="discounts" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::fetch( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>
<?php
}
