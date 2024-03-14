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

$lic_key = $this->lic_key;
$lic_date = $this->lic_date;
$is_pro = $this->is_pro;

$nowdf = VikRentItems::getDateFormat();
if ($nowdf == "%d/%m/%Y") {
	$df = 'd/m/Y';
} elseif ($nowdf == "%m/%d/%Y") {
	$df = 'm/d/Y';
} else {
	$df = 'Y/m/d';
}

$valid_until = date($df, $lic_date);

?>
<div class="viwppro-cnt viwpro-procnt">
	<div class="viwpro-procnt-inner">
		<div class="vikwppro-header">
			<div class="vikwppro-header-inner">
				<div class="vikwppro-header-text">
					<h2><?php echo JText::translate('VRIPROTHANKSUSE'); ?></h2>
					<h3><?php echo JText::translate('VRIPROTHANKSLIC'); ?></h3>
				</div>
			</div>
		</div>
		<div class="vikwppro-licencecnt">
			<div class="col col-md-6 col-sm-12 vikwppro-licencetext">
				<div>
					<h3><?php echo JText::sprintf('VRILICKEYVALIDUNTIL', $valid_until); ?></h3>
					<h4><?php echo JText::translate('VRIPROGETRENEWLICFROM'); ?></h4>
					<a href="https://vikwp.com/" class="vikwp-btn-link" target="_blank"><?php VikRentItemsIcons::e('rocket'); ?> <?php echo JText::translate('VRIPROGETRENEWLIC'); ?></a>
				</div>
				<span class="icon-background"><?php VikRentItemsIcons::e('rocket'); ?></span>
			</div>
			<div class="col col-md-6 col-sm-12 vikwppro-licenceform">
				<form>				
					<div class="vikwppro-licenceform-inner">
						<h4><?php //echo JText::translate('VRIPROALREADYHAVEKEY'); ?> Already have Vik Rent Items PRO? <br /> <small>Enter your licence key here</small></h4>
						<div>
							<span class="vikwppro-inputspan"><?php VikRentItemsIcons::e('key'); ?><input type="text" name="key" id="lickey" value="<?php echo htmlspecialchars($lic_key); ?>" class="licence-input" autocomplete="off" /></span>
							<button type="button" class="btn btn-primary" id="vikwpvalidate" onclick="vikWpValidateLicenseKey();"><?php echo JText::translate('VRIPROVALNUPD'); ?></button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
var vikwp_running = false;
function vikWpValidateLicenseKey() {
	if (vikwp_running) {
		// prevent double submission until request is over
		return;
	}
	// start running
	vikWpStartValidation();

	// request
	var lickey = document.getElementById('lickey').value;
	jQuery.ajax({
		type: "POST",
		url: "admin.php",
		data: { option: "com_vikrentitems", task: "license.validate", key: lickey }
	}).done(function(res) {
		if (res.indexOf('e4j.error') >= 0) {
			// stop the request
			vikWpStopValidation();
			alert(res.replace("e4j.error.", ""));
			return;
		}
		var obj_res = JSON.parse(res);
		document.location.href = 'admin.php?option=com_vikrentitems&view=getpro';
	}).fail(function() {
		// stop the request
		vikWpStopValidation();
		alert("Request Failed");
	});

}
function vikWpStartValidation() {
	vikwp_running = true;
	jQuery('#vikwpvalidate').prepend('<?php VikRentItemsIcons::e('refresh', 'fa-spin'); ?>');
}
function vikWpStopValidation() {
	vikwp_running = false;
	jQuery('#vikwpvalidate').find('i').remove();
}
jQuery(document).ready(function() {
	jQuery('#lickey').keyup(function() {
		jQuery(this).val(jQuery(this).val().trim());
	});
	jQuery('#lickey').keypress(function(e) {
		if (e.which == 13) {
			// enter key code pressed, run the validation
			vikWpValidateLicenseKey();
			return false;
		}
	});
});
</script>
