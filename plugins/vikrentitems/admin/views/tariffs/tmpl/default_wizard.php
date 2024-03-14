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
$name = $this->itemrows['name'];
$currencysymb = VikRentItems::getCurrencySymb(true);

?>
<div class="vri-info-overlay-block vri-info-overlay-block-animation">
	<a class="vri-info-overlay-close" href="javascript: void(0);"></a>
	<div class="vri-info-overlay-content vri-info-overlay-content-wizard vri-info-overlay-content-hidden">
		<h3><?php echo "{$name} - " . JText::translate('VRINSERTFEE'); ?></h3>
		<div class="vri-overlay-wizard-wrap">
			<form method="post" action="index.php?option=com_vikrentitems">
				<div class="vri-tariffs-wizard-help-wrap">
					<p>
						<span><?php echo JText::translate('VRIWIZARDTARIFFSMESS'); ?></span>
						<?php echo $vri_app->createPopover(array('title' => JText::translate('VRINSERTFEE'), 'content' => JText::translate('VRIWIZARDTARIFFSHELP'), 'placement' => 'bottom')); ?>
					</p>
					<h4><?php echo JText::translate('VRIWIZARDTARIFFSWHTC'); ?></h4>
				</div>
				<div class="vri-tariffs-wizard-prices-wrap">
				<?php
				foreach ($this->prices as $pr) {
					?>
					<div class="vri-tariffs-wizard-price">
						<span class="vri-tariffs-wizard-price-name"><?php echo $pr['name']; ?></span>
						<span class="vri-tariffs-wizard-price-cost">
							<span class="vri-tariffs-wizard-price-cost-currency"><?php echo $currencysymb; ?></span>
							<span class="vri-tariffs-wizard-price-cost-amount">
								<input type="number" min="1" step="any" name="dprice<?php echo $pr['id']; ?>" value=""/>
							</span>
						</span>
					</div>
					<?php
				}
				?>
				</div>
				<div class="vri-tariffs-wizard-prices-submit">
					<input type="submit" class="btn btn-success" name="newdispcost" value="<?php echo JText::translate('VRINSERTFEE'); ?>"/>
				</div>
				<input type="hidden" name="task" value="tariffs" />
				<input type="hidden" name="ddaysfrom" value="1" />
				<input type="hidden" name="ddaysto" value="30" />
				<input type="hidden" name="cid[]" value="<?php echo $this->itemrows['id']; ?>" />
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
var vridialog_on = false;
function hideVriWizard() {
	if (vridialog_on === true) {
		jQuery(".vri-info-overlay-block").fadeOut(400, function () {
			jQuery(".vri-info-overlay-content").hide().addClass("vri-info-overlay-content-hidden").removeClass("vri-info-overlay-content-animated");
		});
		vridialog_on = false;
	}
}
function showVriWizard() {
	jQuery(".vri-info-overlay-block").fadeIn(400, function () {
		jQuery(".vri-info-overlay-content").show().addClass("vri-info-overlay-content-animated").removeClass("vri-info-overlay-content-hidden");
	});
	vridialog_on = true;
}
jQuery(document).ready(function() {
<?php
if (empty($this->rows)) {
	?>
	showVriWizard();
	<?php
}
?>
	// modal handling
	jQuery(document).keydown(function(e) {
		if (e.keyCode == 27) {
			hideVriWizard();
		}
	});
	jQuery(document).mouseup(function(e) {
		if (!vridialog_on) {
			return false;
		}
		var vri_overlay_cont = jQuery(".vri-info-overlay-content");
		if (!vri_overlay_cont.is(e.target) && vri_overlay_cont.has(e.target).length === 0) {
			hideVriWizard();
		}
	});
});
</script>
