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
$editor = JEditor::getInstance(JFactory::getApplication()->get('editor'));
$sitelogo = VikRentItems::getSiteLogo();
$sendemailwhen = VikRentItems::getSendEmailWhen();
$backlogo = VikRentItems::getBackendLogo();
$attachical = VikRentItems::attachIcal();
?>

<div class="vri-config-maintab-left">
	<fieldset class="adminform">
		<div class="vri-params-wrap">
			<legend class="adminlegend"><?php echo JText::translate('VRPANELFOUR'); ?></legend>
			<div class="vri-params-container">
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGTHREEONE'); ?></div>
					<div class="vri-param-setting"><input type="text" name="fronttitle" value="<?php echo VikRentItems::getFrontTitle(); ?>" size="10"/></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGFOURLOGO'); ?></div>
					<div class="vri-param-setting">
						<div class="vri-param-setting-block">
							<?php echo (!empty($sitelogo) ? "<a href=\"".VRI_ADMIN_URI."resources/".$sitelogo."\" target=\"_blank\" class=\"vrimodal vri-item-img-modal\"><i class=\"" . VikRentItemsIcons::i('image') . "\"></i>" . $sitelogo . "</a>" : ""); ?>
							<input type="file" name="sitelogo" size="35"/>
						</div>
					</div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRICONFIGLOGOBACKEND'); ?></div>
					<div class="vri-param-setting">
						<div class="vri-param-setting-block">
						<?php
						if (!empty($backlogo)) {
							?>
							<a href="<?php echo VRI_ADMIN_URI . "resources/{$backlogo}"; ?>" target="_blank" class="vrimodal vri-item-img-modal"><?php VikRentItemsIcons::e('image'); ?> <?php echo $backlogo; ?></a>
							<?php
						} else {
							?>
							<a href="<?php echo VRI_ADMIN_URI . "vikrentitems.png"; ?>" target="_blank" class="vrimodal vri-item-img-modal"><?php VikRentItemsIcons::e('image'); ?> vikrentitems.png</a>
							<?php
						}
						?>
							<input type="file" name="backlogo" size="35"/>
						</div>
					</div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRICONFIGSENDEMAILWHEN'); ?></div>
					<div class="vri-param-setting">
						<select name="sendemailwhen">
							<option value="1"><?php echo JText::translate('VRICONFIGSMSSENDWHENCONFPEND'); ?></option>
							<option value="2"<?php echo $sendemailwhen > 1 ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRICONFIGSMSSENDWHENCONF'); ?></option>
						</select>
					</div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo $vri_app->createPopover(array('title' => JText::translate('VRICONFIGATTACHICAL'), 'content' => JText::translate('VRICONFIGATTACHICALHELP'))); ?> <?php echo JText::translate('VRICONFIGATTACHICAL'); ?></div>
					<div class="vri-param-setting">
						<select name="attachical">
							<option value="1"<?php echo $attachical === 1 ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRICONFIGSENDTOADMIN') . ' + ' . JText::translate('VRICONFIGSENDTOCUSTOMER'); ?></option>
							<option value="2"<?php echo $attachical === 2 ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRICONFIGSENDTOADMIN'); ?></option>
							<option value="3"<?php echo $attachical === 3 ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRICONFIGSENDTOCUSTOMER'); ?></option>
							<option value="0"<?php echo $attachical === 0 ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRNO'); ?></option>
						</select>
					</div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRISENDPDF'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('sendpdf', JText::translate('VRYES'), JText::translate('VRNO'), (VikRentItems::sendPDF() ? 'yes' : 0), 'yes', 0); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGFOURTWO'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('allowstats', JText::translate('VRYES'), JText::translate('VRNO'), (VikRentItems::allowStats() ? 'yes' : 0), 'yes', 0); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGFOURTHREE'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('sendmailstats', JText::translate('VRYES'), JText::translate('VRNO'), (VikRentItems::sendMailStats() ? 'yes' : 0), 'yes', 0); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGFOURORDMAILFOOTER'); ?></div>
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
								echo $editor->display( "footerordmail", VikRentItems::getFooterOrdMail(), 500, 350, 70, 20 );
							} catch (Throwable $t) {
								echo $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . '<br/>';
							}
						} else {
							// we cannot catch Fatal Errors in PHP 5.x
							echo $editor->display( "footerordmail", VikRentItems::getFooterOrdMail(), 500, 350, 70, 20 );
						}
						?>
					</div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGFOURFOUR'); ?></div>
					<div class="vri-param-setting"><textarea name="disclaimer" rows="7" cols="50"><?php echo VikRentItems::getDisclaimer(); ?></textarea></div>
				</div>
			</div>
		</div>
	</fieldset>
</div>
