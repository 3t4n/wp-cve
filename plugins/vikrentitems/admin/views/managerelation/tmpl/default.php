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
$wsel = $this->wsel;
$wseltwo = $this->wseltwo;

if (strlen($wsel) > 0) {
	?>
	<form name="adminForm" id="adminForm" action="index.php" method="post">
		<div class="vri-admin-container">
			<div class="vri-config-maintab-left">
				<fieldset class="adminform">
					<div class="vri-params-wrap">
						<legend class="adminlegend"><?php echo JText::translate('VRIADMINLEGENDDETAILS'); ?></legend>
						<div class="vri-params-container">
							<div class="vri-param-container">
								<div class="vri-param-label"><?php echo JText::translate('VRIRELATIONNAME'); ?> </div>
								<div class="vri-param-setting"><input type="text" name="relname" value="<?php echo count($row) ? htmlspecialchars($row['relname']) : ''; ?>" size="30"/></div>
							</div>
							<div class="vri-param-container">
								<div class="vri-param-label"><?php echo JText::translate('VRINEWRELATIONSEL'); ?> </div>
								<div class="vri-param-setting">
									<div style="float: left; margin-right: 20px; min-height: 170px;"><?php echo $wsel; ?></div>
									<div style="float: left; margin-right: 20px;  min-height: 170px; border-right: 1px dotted #cccccc;">&nbsp;</div>
									<div style="float: left; min-height: 170px;"><?php echo $wseltwo; ?></div>
								</div>

							</div>
						</div>
					</div>
				</fieldset>
			</div>
		</div>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="com_vikrentitems" />
	<?php
	if (count($row)) {
		?>
		<input type="hidden" name="where" value="<?php echo $row['id']; ?>">
		<?php
	}
	?>
		<?php echo JHtml::fetch('form.token'); ?>
	</form>
	<?php
} else {
	?>
	<p class="err"><a href="index.php?option=com_vikrentitems&amp;task=newitem"><?php echo JText::translate('VRNOITEMSFOUNDSEASONS'); ?></a></p>
	<form action="index.php?option=com_vikrentitems" method="post" name="adminForm" id="adminForm">
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="option" value="com_vikrentitems" />
		<?php echo JHtml::fetch('form.token'); ?>
	</form>
	<?php
}
