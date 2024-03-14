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

$row = $this->row;

$vri_app = VikRentItems::getVriApplication();

$dbo = JFactory::getDbo();
$q = "SELECT * FROM `#__vikrentitems_iva`;";
$dbo->setQuery($q);
$dbo->execute();
if ($dbo->getNumRows() > 0) {
	$ivas = $dbo->loadAssocList();
	$wiva = "<select name=\"praliq\">\n<option value=\"\"></option>\n";
	foreach ($ivas as $iv) {
		$wiva .= "<option value=\"".$iv['id']."\"".(count($row) && $iv['id'] == $row['idiva'] ? " selected=\"selected\"" : "").">".(empty($iv['name']) ? $iv['aliq']."%" : $iv['name']."-".$iv['aliq']."%")."</option>\n";
	}
	$wiva .= "</select>\n";
} else {
	$wiva = "<a href=\"index.php?option=com_vikrentitems&task=iva\">".JText::translate('NESSUNAIVA')."</a>";
}
?>
<form name="adminForm" id="adminForm" action="index.php" method="post">
	<div class="vri-admin-container">
		<div class="vri-config-maintab-left">
			<fieldset class="adminform">
				<div class="vri-params-wrap">
					<legend class="adminlegend"><?php echo JText::translate('VRIADMINLEGENDDETAILS'); ?></legend>
					<div class="vri-params-container">
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWPRICEONE'); ?><sup>*</sup></div>
							<div class="vri-param-setting"><input type="text" name="price" value="<?php echo count($row) ? htmlspecialchars($row['name']) : ''; ?>" size="40"/></div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWPRICETWO'); ?> <?php echo $vri_app->createPopover(array('title' => JText::translate('VRNEWPRICETWO'), 'content' => JText::translate('VRIPRATTRHELP'))); ?></div>
							<div class="vri-param-setting"><input type="text" name="attr" value="<?php echo count($row) ? $row['attr'] : ''; ?>" size="40"/></div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWPRICETHREE'); ?></div>
							<div class="vri-param-setting"><?php echo $wiva; ?></div>
						</div>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
	
	<input type="hidden" name="task" value="">
<?php
if (count($row)) {
	?>
	<input type="hidden" name="whereup" value="<?php echo $row['id']; ?>">
	<?php
}
?>
	<input type="hidden" name="option" value="com_vikrentitems" />
	<?php echo JHtml::fetch('form.token'); ?>
</form>
