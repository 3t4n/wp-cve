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
$formatvals = VikRentItems::getNumberFormatData(true);
$formatparts = explode(':', $formatvals);
?>

<div class="vri-config-maintab-left">
	<fieldset class="adminform">
		<div class="vri-params-wrap">
			<legend class="adminlegend"><?php echo JText::translate('VRICONFIGCURRENCYPART'); ?></legend>
			<div class="vri-params-container">
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGTHREECURNAME'); ?></div>
					<div class="vri-param-setting"><input type="text" name="currencyname" value="<?php echo VikRentItems::getCurrencyName(); ?>" size="10"/></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGTHREECURSYMB'); ?></div>
					<div class="vri-param-setting"><input type="text" name="currencysymb" value="<?php echo VikRentItems::getCurrencySymb(true); ?>" size="10"/></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGTHREECURCODEPP'); ?></div>
					<div class="vri-param-setting"><input type="text" name="currencycodepp" value="<?php echo VikRentItems::getCurrencyCodePp(); ?>" size="10"/></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRCONFIGNUMDECIMALS'); ?></div>
					<div class="vri-param-setting"><input type="number" name="numdecimals" value="<?php echo $formatparts[0]; ?>" min="0"/></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRCONFIGNUMDECSEPARATOR'); ?></div>
					<div class="vri-param-setting"><input type="text" name="decseparator" value="<?php echo $formatparts[1]; ?>" size="2"/></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRCONFIGNUMTHOSEPARATOR'); ?></div>
					<div class="vri-param-setting"><input type="text" name="thoseparator" value="<?php echo $formatparts[2]; ?>" size="2"/></div>
				</div>
			</div>
		</div>
	</fieldset>
</div>

<div class="vri-config-maintab-right">
	<fieldset class="adminform">
		<div class="vri-params-wrap">
			<legend class="adminlegend"><?php echo JText::translate('VRICONFIGPAYMPART'); ?></legend>
			<div class="vri-params-container">
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGTWOFIVE'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('ivainclusa', JText::translate('VRYES'), JText::translate('VRNO'), (VikRentItems::ivaInclusa(true) ? 'yes' : 0), 'yes', 0); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGTWOTHREE'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('paytotal', JText::translate('VRYES'), JText::translate('VRNO'), (VikRentItems::payTotal() ? 'yes' : 0), 'yes', 0); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGTWOFOUR'); ?></div>
					<div class="vri-param-setting"><input type="number" name="payaccpercent" value="<?php echo VikRentItems::getAccPerCent(); ?>" min="0" step="any"/> <select id="typedeposit" name="typedeposit"><option value="pcent">%</option><option value="fixed"<?php echo (VikRentItems::getTypeDeposit(true) == "fixed" ? ' selected="selected"' : ''); ?>><?php echo VikRentItems::getCurrencySymb(); ?></option></select></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGTWOSIX'); ?></div>
					<div class="vri-param-setting"><input type="text" name="paymentname" value="<?php echo VikRentItems::getPaymentName(); ?>" size="25"/></div>
				</div>
			</div>
		</div>
	</fieldset>
</div>
