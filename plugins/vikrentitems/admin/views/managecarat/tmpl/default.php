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
$vri_app->loadSelect2();
$document = JFactory::getDocument();
$document->addStyleSheet(VRI_SITE_URI.'resources/jquery.fancybox.css');
JHtml::fetch('script', VRI_SITE_URI.'resources/jquery.fancybox.js');

?>
<script type="text/javascript">
function showResizeSel() {
	if (document.adminForm.autoresize.checked == true) {
		jQuery('#resizesel').show();
	} else {
		jQuery('#resizesel').hide();
	}
	return true;
}
jQuery(document).ready(function() {
	jQuery('#iditems').select2();
	jQuery('.vri-select-all').click(function() {
		var nextsel = jQuery(this).next("select");
		nextsel.find("option").prop('selected', true);
		nextsel.trigger('change');
	});
	jQuery('.vrimodal').fancybox();
});
</script>

<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">
	<div class="vri-admin-container">
		<div class="vri-config-maintab-left">
			<fieldset class="adminform">
				<div class="vri-params-wrap">
					<legend class="adminlegend"><?php echo JText::translate('VRIADMINLEGENDDETAILS'); ?></legend>
					<div class="vri-params-container">
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWCARATONE'); ?></div>
							<div class="vri-param-setting">
								<input type="text" name="caratname" value="<?php echo count($row) ? htmlspecialchars($row['name']) : ''; ?>" size="40"/>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWCARATTWO'); ?></div>
							<div class="vri-param-setting">
								<div class="vri-param-setting-block">
									<?php echo (count($row) && !empty($row['icon']) && file_exists(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.$row['icon']) ? '<a href="'.VRI_ADMIN_URI.'resources/'.$row['icon'].'" class="vrimodal vri-item-img-modal" target="_blank"><i class="' . VikRentItemsIcons::i('image') . '"></i> '.$row['icon'].'</a> ' : ""); ?>
									<input type="file" name="caraticon" size="35"/>
								</div>
								<div class="vri-param-setting-block">
									<span class="vri-resize-lb-cont">
										<label style="display: inline;" for="autoresize"><?php echo JText::translate('VRNEWOPTNINE'); ?></label> 
										<input type="checkbox" id="autoresize" name="autoresize" value="1" onclick="showResizeSel();"/> 
									</span>
									<span id="resizesel" style="display: none;">&nbsp;
										<?php echo JText::translate('VRNEWOPTTEN'); ?>: <input type="number" name="resizeto" value="50" min="0"/> px
									</span>								
								</div>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWCARATTHREE'); ?></div>
							<div class="vri-param-setting">
								<input type="text" name="carattextimg" value="<?php echo count($row) ? htmlspecialchars($row['textimg']) : ''; ?>" size="40"/>
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
							<div class="vri-param-label"> <?php echo JText::translate('VRIITEMSASSIGNED'); ?></div>
							<div class="vri-param-setting"> 
								<span class="vri-select-all"><?php echo JText::translate('VRISELECTALL'); ?></span>
								<select name="iditems[]" multiple="multiple" id="iditems">
								<?php
								foreach ($this->allitems as $rid => $item) {
									$is_item_assigned = (count($row) && is_array($item['idcarat']) && in_array((string)$row['id'], $item['idcarat']));
									?>
									<option value="<?php echo $rid; ?>"<?php echo $is_item_assigned ? ' selected="selected"' : ''; ?>><?php echo $item['name']; ?></option>
									<?php
								}
								?>
								</select>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIPOSITIONORDERING'); ?></div>
							<div class="vri-param-setting">
								<input type="number" name="ordering" value="<?php echo count($row) ? $row['ordering'] : ''; ?>"/>
							<?php
							if (!count($row)) {
								?>
								<span class="vri-param-setting-comment"><?php echo JText::translate('VRIPOSITIONORDERINGHELP'); ?></span>
								<?php
							}
							?>
							</div>
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
