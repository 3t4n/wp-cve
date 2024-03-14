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

$editor = JEditor::getInstance(JFactory::getApplication()->get('editor'));
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
							<div class="vri-param-label"><?php echo JText::translate('VRNEWCATONE'); ?> </div>
							<div class="vri-param-setting"><input type="text" name="catname" value="<?php echo count($row) ? htmlspecialchars($row['name']) : ''; ?>" size="40"/></div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWCATTWO'); ?></div>
							<div class="vri-param-setting">
								<div class="vri-param-setting-block">
									<?php echo (count($row) && is_file(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.$row['img']) ? '<a href="'.VRI_ADMIN_URI.'resources/'.$row['img'].'" class="vrimodal vri-item-img-modal" target="_blank"><i class="' . VikRentItemsIcons::i('image') . '"></i> '.$row['img'].'</a>' : ""); ?>
									<input type="file" name="catimg" size="35"/>
								</div>
								<div class="vri-param-setting-block">
									<span class="vri-resize-lb-cont">
										<label style="display: inline;" for="autoresize"><?php echo JText::translate('VRNEWOPTNINE'); ?></label> 
										<input type="checkbox" id="autoresize" name="autoresize" value="1" onclick="showResizeSel();"/> 
									</span>
									<span id="resizesel" style="display: none;"><span><?php echo JText::translate('VRNEWOPTTEN'); ?></span><input type="text" name="resizeto" value="250" size="3" class="vri-small-input"/> px</span>
								</div>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWCATDESCR'); ?> </div>
							<div class="vri-param-setting">
								<?php
								if (interface_exists('Throwable')) {
									/**
									 * With PHP >= 7 supporting throwable exceptions for Fatal Errors
									 * we try to avoid issues with third party plugins that make use
									 * of the WP native function get_current_screen().
									 * 
									 * @wponly
									 */
									try {
										echo $editor->display( "descr", (count($row) ? $row['descr'] : ''), 400, 200, 70, 20 );
									} catch (Throwable $t) {
										echo $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . '<br/>';
									}
								} else {
									// we cannot catch Fatal Errors in PHP 5.x
									echo $editor->display( "descr", (count($row) ? $row['descr'] : ''), 400, 200, 70, 20 );
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
