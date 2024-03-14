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
	<p class="warn"><?php echo JText::translate('VRINORELATIONS'); ?></p>
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
					<th class="title left" width="200" align="left"><?php echo JText::translate( 'VRPSHOWRELATIONSONE' ); ?></th>
					<th class="title center" width="100"><?php echo JText::translate( 'VRPSHOWRELATIONSTWO' ); ?></th>
					<th class="title center" width="100"><?php echo JText::translate( 'VRPSHOWRELATIONSTHREE' ); ?></th>
				</tr>
			</thead>
			<?php
			$k = 0;
			$i = 0;
			for ($i = 0, $n = count($rows); $i < $n; $i++) {
				$row = $rows[$i];
				$totleft = explode(';', $row['relone']);
				$totright = explode(';', $row['reltwo']);
				?>
				<tr class="row<?php echo $k; ?>">
					<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
					<td><a href="index.php?option=com_vikrentitems&amp;task=editrelation&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['relname']; ?></a></td>
					<td class="center"><?php echo (count($totleft) - 1); ?></td>
					<td class="center"><?php echo (count($totright) - 1); ?></td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
		</table>
	</div>
	<input type="hidden" name="option" value="com_vikrentitems" />
	<input type="hidden" name="task" value="relations" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::fetch( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>
<?php
}
