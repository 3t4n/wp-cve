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

$vri_app = VikRentItems::getVriApplication();
$vri_app->loadSelect2();
$currencysymb = VikRentItems::getCurrencySymb(true);
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#iditems').select2();
		jQuery('.vri-select-all').click(function() {
			var nextsel = jQuery(this).next("select");
			nextsel.find("option").prop('selected', true);
			nextsel.trigger('change');
		});
	});
</script>
<?php
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
							<div class="vri-param-label"><?php echo JText::translate('VRIDISCNAME'); ?></div>
							<div class="vri-param-setting"><input type="text" name="discname" value="<?php echo count($row) ? htmlspecialchars($row['discname']) : ''; ?>" size="30"/></div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRINEWDISCQUANT'); ?></div>
							<div class="vri-param-setting"><input type="number" min="1" name="quantity" value="<?php echo count($row) ? $row['quantity'] : '2'; ?>"/></div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWDISCVALUE'); ?></div>
							<div class="vri-param-setting">
								<input type="number" step="any" name="diffcost" value="<?php echo count($row) ? $row['diffcost'] : ''; ?>"/> 
								<select name="val_pcent" id="val_pcent">
									<option value="2"<?php echo count($row) && $row['val_pcent'] == 2 ? " selected=\"selected\"" : ""; ?>>%</option>
									<option value="1"<?php echo count($row) && $row['val_pcent'] == 1 ? " selected=\"selected\"" : ""; ?>><?php echo $currencysymb; ?></option>
								</select> 
								&nbsp;<?php echo $vri_app->createPopover(array('title' => JText::translate('VRIDISCOUNTSTITLETXT'), 'content' => JText::translate('VRIDISCOUNTSHELP'))); ?>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIDISCIFGREATQUANT'); ?></div>
							<div class="vri-param-setting">
								<?php echo $vri_app->printYesNoButtons('ifmorequant', JText::translate('VRYES'), JText::translate('VRNO'), (count($row) && intval($row['ifmorequant']) == 1 ? 1 : 0), 1, 0); ?>
							</div>
						</div>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="vri-config-maintab-right">
			<fieldset class="adminform">
				<div class="vri-params-wrap">
					<legend class="adminlegend"><?php echo JText::translate('VRIADMINLEGENDSETTINGS'); ?></legend>
					<div class="vri-params-container">
						<div class="vri-param-container">
							<div class="vri-param-label"> <?php echo JText::translate('VRINEWDISCITEMS'); ?></div>
							<div class="vri-param-setting"> <span class="vri-select-all"><?php echo JText::translate('VRISELECTALL'); ?></span> <?php echo $wsel; ?></div>
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
