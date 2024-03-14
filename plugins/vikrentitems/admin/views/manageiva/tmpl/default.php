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

?>
<form name="adminForm" id="adminForm" action="index.php" method="post">
	<div class="vri-admin-container">
		<div class="vri-config-maintab-left">
			<fieldset class="adminform">
				<div class="vri-params-wrap">
					<legend class="adminlegend"><?php echo JText::translate('VRIADMINLEGENDDETAILS'); ?></legend>
					<div class="vri-params-container">
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWIVAONE'); ?></div>
							<div class="vri-param-setting"><input type="text" name="aliqname" value="<?php echo count($row) ? htmlspecialchars($row['name']) : ''; ?>" size="30"/></div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWIVATWO'); ?></div>
							<div class="vri-param-setting"><input type="number" step="any" name="aliqperc" value="<?php echo count($row) ? $row['aliq'] : ''; ?>"/> %</div>
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
