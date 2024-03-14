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

$dbo = JFactory::getDbo();
$app = JFactory::getApplication();
$vri_app = VikRentItems::getVriApplication();
$vri_app->loadSelect2();

$filtni = $app->getUserStateFromRequest("vri.items.filtni", 'filtni', '', 'string');
$filtcateg = $app->getUserStateFromRequest("vri.items.filtcateg", 'filtcateg', 0, 'int');
?>
<form action="index.php?option=com_vikrentitems&amp;task=items" method="post" name="itemsform">
	<div style="width: 100%; display: inline-block;" class="btn-toolbar vri-btn-toolbar" id="filter-bar">
		<div class="btn-group pull-left">
			<select name="filtcateg" id="filtcateg" onchange="document.itemsform.submit();">
				<option value=""><?php echo JText::translate('VRIFILTCATEGORYANY'); ?></option>
			<?php
			foreach ($this->all_cats as $cat) {
				?>
				<option value="<?php echo $cat['id']; ?>"<?php echo $cat['id'] == $filtcateg ? ' selected="selected"' : ''; ?>><?php echo $cat['name']; ?></option>
				<?php
			}
			?>
			</select>
		</div>
		<div class="btn-group pull-left input-append">
			<input type="text" name="filtni" id="filtni" value="<?php echo $filtni; ?>" size="40" placeholder="<?php echo $this->escape(JText::translate('VRIFILTINAME')); ?>"/>
			<button type="button" class="btn btn-secondary" onclick="document.itemsform.submit();"><i class="icon-search"></i></button>
		</div>
		<div class="btn-group pull-left">
			<button type="button" class="btn btn-secondary" onclick="document.getElementById('filtni').value='';document.getElementById('filtcateg').value='';document.itemsform.submit();"><?php echo JText::translate('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
	</div>
	<input type="hidden" name="task" value="items" />
	<input type="hidden" name="option" value="com_vikrentitems" />
</form>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#filtcateg').select2();
});
</script>
<?php
if (empty($rows)) {
	?>
	<p class="err"><?php echo JText::translate('VRNOITEMSFOUND'); ?></p>
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
	if (pressbutton == 'removeitem') {
		if (confirm('<?php echo JText::translate('VRJSDELITEM'); ?>?')) {
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
					<th class="title center" width="30">
						<a href="index.php?option=com_vikrentitems&amp;task=items&amp;vriorderby=id&amp;vriordersort=<?php echo ($orderby == "id" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "id" && $ordersort == "ASC" ? "vri-list-activesort" : ($orderby == "id" ? "vri-list-activesort" : "")); ?>">
							<?php echo JText::translate( 'VRIDASHUPRESONE' ); ?><?php echo ($orderby == "id" && $ordersort == "ASC" ? '<i class="' . VikRentItemsIcons::i('sort-up') . '"></i>' : ($orderby == "id" ? '<i class="' . VikRentItemsIcons::i('sort-down') . '"></i>' : '<i class="' . VikRentItemsIcons::i('sort') . '"></i>')); ?>
						</a>
					</th>
					<th class="title left" width="150">
						<a href="index.php?option=com_vikrentitems&amp;task=items&amp;vriorderby=name&amp;vriordersort=<?php echo ($orderby == "name" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "name" && $ordersort == "ASC" ? "vri-list-activesort" : ($orderby == "name" ? "vri-list-activesort" : "")); ?>">
							<?php echo JText::translate( 'VRPVIEWITEMONE' ); ?><?php echo ($orderby == "name" && $ordersort == "ASC" ? '<i class="' . VikRentItemsIcons::i('sort-up') . '"></i>' : ($orderby == "name" ? '<i class="' . VikRentItemsIcons::i('sort-down') . '"></i>' : '<i class="' . VikRentItemsIcons::i('sort') . '"></i>')); ?>
						</a>
					</th>
					<th class="title left" width="150"><?php echo JText::translate( 'VRPVIEWITEMTWO' ); ?></th>
					<th class="title center" align="center" width="150"><?php echo JText::translate( 'VRPVIEWITEMTHREE' ); ?></th>
					<th class="title center" align="center" width="150"><?php echo JText::translate( 'VRPVIEWITEMFOUR' ); ?></th>
					<th class="title left" width="150"><?php echo JText::translate( 'VRPVIEWITEMFIVE' ); ?></th>
					<th class="title center" align="center" width="100">
						<a href="index.php?option=com_vikrentitems&amp;task=items&amp;vriorderby=units&amp;vriordersort=<?php echo ($orderby == "units" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "units" && $ordersort == "ASC" ? "vri-list-activesort" : ($orderby == "units" ? "vri-list-activesort" : "")); ?>">
							<?php echo JText::translate( 'VRPVIEWITEMSEVEN' ); ?><?php echo ($orderby == "units" && $ordersort == "ASC" ? '<i class="' . VikRentItemsIcons::i('sort-up') . '"></i>' : ($orderby == "units" ? '<i class="' . VikRentItemsIcons::i('sort-down') . '"></i>' : '<i class="' . VikRentItemsIcons::i('sort') . '"></i>')); ?>
						</a>
					</th>
					<th class="title center" align="center" width="100">
						<a href="index.php?option=com_vikrentitems&amp;task=items&amp;vriorderby=avail&amp;vriordersort=<?php echo ($orderby == "avail" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "avail" && $ordersort == "ASC" ? "vri-list-activesort" : ($orderby == "avail" ? "vri-list-activesort" : "")); ?>">
							<?php echo JText::translate( 'VRPVIEWITEMSIX' ); ?><?php echo ($orderby == "avail" && $ordersort == "ASC" ? '<i class="' . VikRentItemsIcons::i('sort-up') . '"></i>' : ($orderby == "avail" ? '<i class="' . VikRentItemsIcons::i('sort-down') . '"></i>' : '<i class="' . VikRentItemsIcons::i('sort') . '"></i>')); ?>
						</a>
					</th>
				</tr>
			</thead>
		<?php
		$kk = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$q = "SELECT COUNT(*) AS `totdisp` FROM `#__vikrentitems_dispcost` WHERE `iditem`='".$row['id']."' ORDER BY `#__vikrentitems_dispcost`.`days`;";
			$dbo->setQuery($q);
			$dbo->execute();
			$lines = $dbo->loadAssocList();
			$tot = $lines[0]['totdisp'];
			$categories = "";
			if (!empty($row['idcat'])) {
				$cat = explode(";", $row['idcat']);
				$catids = array();
				foreach ($cat as $k => $cc) {
					if (!empty($cc)) {
						$catids[] = (int)$cc;
					}
				}
				if (count($catids)) {
					$q = "SELECT `name` FROM `#__vikrentitems_categories` WHERE `id` IN (".implode(', ', $catids).");";
					$dbo->setQuery($q);
					$dbo->execute();
					if ($dbo->getNumRows() > 0) {
						$lines = $dbo->loadAssocList();
						$catnames = array();
						foreach ($lines as $ll) {
							$catnames[] = $ll['name'];
						}
						$categories = implode(", ", $catnames);
					}
				}
			}
			
			$caratteristiche = "";
			if (!empty($row['idcarat'])) {
				$tmpcarat = explode(";", $row['idcarat']);
				$caratteristiche = VikRentItems::totElements($tmpcarat);
			}
			
			$optionals = "";
			if (!empty($row['idopt'])) {
				$tmpopt = explode(";", $row['idopt']);
				$optionals = VikRentItems::totElements($tmpopt);
			}
			
			$luogo = "";
			if (!empty($row['idplace'])) {
				$explace = explode(";", $row['idplace']);
				$q = "SELECT `id`,`name` FROM `#__vikrentitems_places` WHERE `id`=".$dbo->quote($explace[0]).";";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					$lines = $dbo->loadAssoc();
					$luogo = $lines['name'];
					if (@count($explace) > 2) {
						$luogo .= " ...";
					}
				}
			}
			
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);"></td>
				<td class="center"><a href="index.php?option=com_vikrentitems&amp;task=edititem&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['id']; ?></a></td>
				<td><?php echo $row['isgroup'] > 0 ? '<i class="vriicn-stack" title="'.JText::translate('VRITEMISAGROUP').'"></i> ' : ''; ?><a href="index.php?option=com_vikrentitems&amp;task=edititem&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td><?php echo $categories; ?></td>
				<td class="center"><?php echo $caratteristiche; ?></td>
				<td class="center"><?php echo $optionals; ?></td>
				<td><?php echo $luogo; ?></td>
				<td class="center"><?php echo $row['units']; ?></td>
				<td class="center"><a href="index.php?option=com_vikrentitems&amp;task=modavail&amp;cid[]=<?php echo $row['id']; ?>"><?php echo (intval($row['avail'])=="1" ? "<i class=\"fa fa-check vri-icn-img\" style=\"color: #099909;\" title=\"".JText::translate('VRMAKENOTAVAIL')."\"></i>" : "<i class=\"fa fa-times-circle vri-icn-img\" style=\"color: #ff0000;\" title=\"".JText::translate('VRMAKEAVAIL')."\"></i>"); ?></a></td>
			</tr>
			<?php
			$kk = 1 - $kk;
			unset($categories);
		}
		?>
		</table>
	</div>
	<input type="hidden" name="option" value="com_vikrentitems" />
	<?php
	echo '<input type="hidden" name="filtni" value="'.$filtni.'" />';
	echo '<input type="hidden" name="filtcateg" value="'.$filtcateg.'" />';
	?>
	<input type="hidden" name="task" value="items" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::fetch( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>
<?php
}
