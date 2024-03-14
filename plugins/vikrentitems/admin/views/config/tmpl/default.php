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
$vri_app->prepareModalBox();

?>
<div class="vri-admin-body vri-config-body">

	<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">

		<div class="vri-config-tabs-wrap">

			<dl class="tabs" id="tab_group_id">
				<dt style="display:none;"></dt>
				<dd style="display:none;"></dd>
				<dt class="tabs <?php echo $this->curtabid == 1 ? 'open' : 'closed'; ?>" data-ptid="1" style="cursor: pointer;">
					<span>
						<h3>
							<?php VikRentItemsIcons::e('sliders-h'); ?>
							<a href="javascript:void(0);"><?php echo JText::translate('VRPANELONE'); ?></a>
						</h3>
					</span>
				</dt>
				<dt class="tabs <?php echo $this->curtabid == 2 ? 'open' : 'closed'; ?>" data-ptid="2" style="cursor: pointer;">
					<span>
						<h3>
							<?php VikRentItemsIcons::e('funnel-dollar'); ?>
							<a href="javascript:void(0);"><?php echo JText::translate('VRPANELTWO'); ?></a>
						</h3>
					</span>
				</dt>
				<dt class="tabs <?php echo $this->curtabid == 3 ? 'open' : 'closed'; ?>" data-ptid="3" style="cursor: pointer;">
					<span>
						<h3>
							<?php VikRentItemsIcons::e('pencil-alt'); ?>
							<a href="javascript:void(0);"><?php echo JText::translate('VRPANELTHREE'); ?></a>
						</h3>
					</span>
				</dt>
				<dt class="tabs <?php echo $this->curtabid == 4 ? 'open' : 'closed'; ?>" data-ptid="4" style="cursor: pointer;">
					<span>
						<h3>
							<?php VikRentItemsIcons::e('user-cog'); ?>
							<a href="javascript:void(0);"><?php echo JText::translate('VRPANELFOUR'); ?></a>
						</h3>
					</span>
				</dt>
				<dt class="tabs <?php echo $this->curtabid == 5 ? 'open' : 'closed'; ?>" data-ptid="5" style="cursor: pointer;">
					<span>
						<h3>
							<?php VikRentItemsIcons::e('truck'); ?>
							<a href="javascript:void(0);"><?php echo JText::translate('VRPANELFIVE'); ?></a>
						</h3>
					</span>
				</dt>
				<dt class="vri-renewsession-dt">
					<a href="javascript: void(0);" class="vriflushsession" onclick="vriFlushSession();"><?php echo JText::translate('VRICONFIGFLUSHSESSION'); ?></a>
				</dt>
			</dl>

		</div>

		<div class="current">
			<dd class="tabs" id="pt1" style="display: <?php echo $this->curtabid == 1 ? 'block' : 'none'; ?>;">
				<div class="vri-admin-container vri-config-tab-container">
					<?php echo $this->loadTemplate('one'); ?>
				</div>
			</dd>
			<dd class="tabs" id="pt2" style="display: <?php echo $this->curtabid == 2 ? 'block' : 'none'; ?>;">
				<div class="vri-admin-container vri-config-tab-container">
					<?php echo $this->loadTemplate('two'); ?>
				</div>
			</dd>
			<dd class="tabs" id="pt3" style="display: <?php echo $this->curtabid == 3 ? 'block' : 'none'; ?>;">
				<div class="vri-admin-container vri-config-tab-container">
					<?php echo $this->loadTemplate('three'); ?>
				</div>
			</dd>
			<dd class="tabs" id="pt4" style="display: <?php echo $this->curtabid == 4 ? 'block' : 'none'; ?>;">
				<div class="vri-admin-container vri-config-tab-container">
					<?php echo $this->loadTemplate('four'); ?>
				</div>
			</dd>
			<dd class="tabs" id="pt5" style="display: <?php echo $this->curtabid == 5 ? 'block' : 'none'; ?>;">
				<div class="vri-admin-container vri-config-tab-container">
					<?php echo $this->loadTemplate('five'); ?>
				</div>
			</dd>
		</div>

		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="com_vikrentitems"/>
		<?php echo JHtml::_('form.token'); ?>
	</form>

</div>

<script type="text/javascript">
function vriFlushSession() {
	if (confirm('<?php echo addslashes(JText::translate('VRICONFIGFLUSHSESSIONCONF')); ?>')) {
		location.href='index.php?option=com_vikrentitems&task=renewsession';
	} else {
		return false;
	}
}
jQuery(document).ready(function() {
	jQuery('dt.tabs').click(function() {
		var ptid = jQuery(this).attr('data-ptid');
		jQuery('dt.tabs').removeClass('open').addClass('closed');
		jQuery(this).removeClass('closed').addClass('open');
		jQuery('dd.tabs').hide();
		jQuery('dd#pt'+ptid).show();
		var nd = new Date();
		nd.setTime(nd.getTime() + (365*24*60*60*1000));
		document.cookie = "vriConfPt="+ptid+"; expires=" + nd.toUTCString() + "; path=/; SameSite=Lax";
	});
});
</script>
