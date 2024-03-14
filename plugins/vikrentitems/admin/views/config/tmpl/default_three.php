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

$vri_app = VikRentItems::getVriApplication();
/**
 * @wponly - cannot load iFrame with FancyBox, so we use the BS's Modal
 */
if (function_exists('wp_enqueue_code_editor')) {
	// WP >= 4.9.0
	wp_enqueue_code_editor(array('type' => 'php'));
}
$vri_app->getJmodalScript();
echo $vri_app->getJmodalHtml('vri-tplfiles', JText::translate('VRCONFIGEDITTMPLFILE'));
//
$editor = JEditor::getInstance(JFactory::getApplication()->get('editor'));
$document = JFactory::getDocument();
$document->addStyleSheet(VRI_SITE_URI.'resources/jquery.fancybox.css');
JHtml::fetch('script', VRI_SITE_URI.'resources/jquery.fancybox.js');
$themesel = '<select name="theme">';
$themesel .= '<option value="default">default</option>';
$themes = glob(VRI_SITE_PATH.DS.'themes'.DS.'*');
$acttheme = VikRentItems::getTheme();
if (count($themes) > 0) {
	$strip = VRI_SITE_PATH.DS.'themes'.DS;
	foreach ($themes as $th) {
		if (is_dir($th)) {
			$tname = str_replace($strip, '', $th);
			if ($tname != 'default') {
				$themesel .= '<option value="'.$tname.'"'.($tname == $acttheme ? ' selected="selected"' : '').'>'.$tname.'</option>';
			}
		}
	}
}
$themesel .= '</select>';
$firstwday = VikRentItems::getFirstWeekDay(true);
?>

<div class="vri-config-maintab-left">
	<fieldset class="adminform">
		<div class="vri-params-wrap">
			<legend class="adminlegend"><?php echo JText::translate('VRICONFIGPAYMPART'); ?></legend>
			<div class="vri-params-container">
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRCONFIGFIRSTWDAY'); ?></div>
					<div class="vri-param-setting">
						<select name="firstwday" style="float: none;">
							<option value="0"<?php echo $firstwday == '0' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRISUNDAY'); ?></option>
							<option value="1"<?php echo $firstwday == '1' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRIMONDAY'); ?></option>
							<option value="2"<?php echo $firstwday == '2' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRITUESDAY'); ?></option>
							<option value="3"<?php echo $firstwday == '3' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRIWEDNESDAY'); ?></option>
							<option value="4"<?php echo $firstwday == '4' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRITHURSDAY'); ?></option>
							<option value="5"<?php echo $firstwday == '5' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRIFRIDAY'); ?></option>
							<option value="6"<?php echo $firstwday == '6' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRISATURDAY'); ?></option>
						</select>
					</div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGTHREETEN'); ?></div>
					<div class="vri-param-setting"><input type="number" name="numcalendars" value="<?php echo VikRentItems::numCalendars(); ?>" min="0"/></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRCONFIGTHUMBSIZE'); ?></div>
					<div class="vri-param-setting"><input type="number" step="any" name="thumbswidth" value="<?php echo VikRentItems::getThumbnailsWidth(); ?>" min="0"/> px</div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGTHREENINE'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('showpartlyreserved', JText::translate('VRYES'), JText::translate('VRNO'), (VikRentItems::showPartlyReserved() ? 'yes' : 0), 'yes', 0); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRCONFIGEMAILTEMPLATE'); ?></div>
					<div class="vri-param-setting">
						<div class="btn-wrapper input-append">
							<button type="button" class="btn vri-edit-tmpl" data-tmpl-path="<?php echo urlencode(VRI_SITE_PATH.DS.'helpers'.DS.'email_tmpl.php'); ?>"><i class="icon-edit"></i> <?php echo JText::translate('VRCONFIGEDITTMPLFILE'); ?></button>
							<button type="button" class="btn vri-edit-tmpl vri-preview-btn" title="<?php echo addslashes(JText::translate('VRIPREVIEW')); ?>" data-prew-path="<?php echo urlencode(VRI_SITE_PATH.DS.'helpers'.DS.'email_tmpl.php'); ?>"><?php VikRentItemsIcons::e('eye'); ?></button>
						</div>
					</div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRCONFIGPDFTEMPLATE'); ?></div>
					<div class="vri-param-setting"><button type="button" class="btn vri-edit-tmpl" data-tmpl-path="<?php echo urlencode(VRI_SITE_PATH.DS.'helpers'.DS.'pdf_tmpl.php'); ?>"><i class="icon-edit"></i> <?php echo JText::translate('VRCONFIGEDITTMPLFILE'); ?></button></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label">Custom CSS Overrides</div>
					<!-- @wponly  the path of the file is different in WP, it's inside /resources -->
					<div class="vri-param-setting"><button type="button" class="btn vri-edit-tmpl" data-tmpl-path="<?php echo urlencode(VRI_SITE_PATH.DS.'resources'.DS.'vikrentitems_custom.css'); ?>"><i class="icon-edit"></i> <?php echo JText::translate('VRCONFIGEDITTMPLFILE'); ?></button></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGTHREESIX'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('showfooter', JText::translate('VRYES'), JText::translate('VRNO'), (VikRentItems::showFooter() ? 'yes' : 0), 'yes', 0); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGTHEME'); ?></div>
					<div class="vri-param-setting"><?php echo $themesel; ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGTHREESEVEN'); ?></div>
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
								echo $editor->display( "intromain", VikRentItems::getIntroMain(), 500, 350, 70, 20 );
							} catch (Throwable $t) {
								echo $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . '<br/>';
							}
						} else {
							// we cannot catch Fatal Errors in PHP 5.x
							echo $editor->display( "intromain", VikRentItems::getIntroMain(), 500, 350, 70, 20 );
						}
						?>
					</div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGTHREEEIGHT'); ?></div>
					<div class="vri-param-setting"><textarea name="closingmain" rows="5" cols="50"><?php echo VikRentItems::getClosingMain(); ?></textarea></div>
				</div>
			</div>
		</div>
	</fieldset>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".vri-edit-tmpl").click(function() {
		var vri_tmpl_path = jQuery(this).attr("data-tmpl-path");
		var vri_prew_path = jQuery(this).attr("data-prew-path");
		if (!vri_tmpl_path && !vri_prew_path) {
			return;
		}
		var basetask = !vri_tmpl_path ? 'tmplfileprew' : 'edittmplfile';
		var basepath = !vri_tmpl_path ? vri_prew_path : vri_tmpl_path;
		// @wponly - we use the BS's Modal to open the template files editing page
		vriOpenJModal('vri-tplfiles', "index.php?option=com_vikrentitems&task=" + basetask + "&path=" + basepath + "&tmpl=component");
	});
});
</script>
