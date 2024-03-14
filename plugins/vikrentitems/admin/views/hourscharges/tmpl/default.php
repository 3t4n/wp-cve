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

$itemrows = $this->itemrows;
$rows = $this->rows;
$prices = $this->prices;
$allc = $this->allc;

$vri_app = VikRentItems::getVriApplication();
$vri_app->loadSelect2();

$currencysymb = VikRentItems::getCurrencySymb(true);
$iditem = $itemrows['id'];
$name = $itemrows['name'];
if (is_file(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.$itemrows['img']) && getimagesize(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.$itemrows['img'])) {
	$img = '<img align="middle" class="maxninety" alt="Item Image" src="' . VRI_ADMIN_URI . 'resources/'.$itemrows['img'].'" />';
} else {
	$img = '<i class="' . VikRentItemsIcons::i('image', 'vri-enormous-icn') . '"></i>';
}
?>

<div class="vri-admin-container">
	<div class="vri-config-maintab-left">
		<fieldset class="adminform">
			<div class="vri-params-wrap">
				<legend class="adminlegend">
					<div class="vri-quickres-head">
						<span><?php echo $name . " - " . JText::translate('VRINSERTFEE'); ?></span>
						<div class="vri-quickres-head-right">
							<form name="vrichitem" method="post" action="index.php?option=com_vikrentitems">
								<input type="hidden" name="task" value="hourscharges"/>
								<select name="cid[]" id="vri-item-selection" onchange="javascript: document.vrichitem.submit();">
								<?php
								foreach ($allc as $cc) {
									?>
									<option value="<?php echo $cc['id']; ?>"<?php echo $cc['id'] == $iditem ? ' selected="selected"' : ''; ?>><?php echo $cc['name']; ?></option>
									<?php
								}
								?>
								</select>
							</form>
						</div>
					</div>
				</legend>
				<div class="vri-params-container vri-tariffs-params-container">
					<div class="vri-param-container">
						<div class="vri-param-label">
							<div class="vri-center">
								<?php echo $img; ?>
							</div>
						</div>
						<div class="vri-param-setting">
							<div class="vri-fares-tabs">
								<div class="dailyprices">
									<a href="index.php?option=com_vikrentitems&task=tariffs&cid[]=<?php echo $iditem; ?>"><?php echo JText::translate('VRIDAILYFARES'); ?></a>
								</div>
								<div class="hourschargesactive"><?php echo JText::translate('VRIHOURSCHARGES'); ?></div>
								<div class="hourlyprices">
									<a href="index.php?option=com_vikrentitems&task=tariffshours&cid[]=<?php echo $iditem; ?>"><?php echo JText::translate('VRIHOURLYFARES'); ?></a>
								</div>
							</div>
						<?php
						if (empty($prices)) {
							?>
							<p class="err">
								<span><?php echo JText::translate('VRMSGONE'); ?></span>
								<a href="index.php?option=com_vikrentitems&task=newprice"><?php echo JText::translate('VRHERE'); ?></a>
							</p>
							<?php
						}
						?>
							<form name="newd" method="post" action="index.php?option=com_vikrentitems" onsubmit="javascript: if (!document.newd.hhoursfrom.value.match(/\S/)){alert('<?php echo addslashes(JText::translate('VRMSGTWO')); ?>'); return false;} else {return true;}">
								<div class="vri-insertrates-cont">
									<div class="vri-insertrates-top">
										<div class="vri-ratestable-lbl"><?php echo JText::translate('VRIHOURS'); ?></div>
										<div class="vri-ratestable-nights">
											<div class="vri-ratestable-night-from">
												<span><?php echo JText::translate('VRDAYSFROM'); ?></span>
												<input type="number" name="hhoursfrom" id="hhoursfrom" value="<?php echo !is_array($prices) ? '1' : ''; ?>" min="1" />
											</div>
											<div class="vri-ratestable-night-to">
												<span><?php echo JText::translate('VRDAYSTO'); ?></span>
												<input type="number" name="hhoursto" id="hhoursto" value="<?php echo !is_array($prices) ? '30' : ''; ?>" min="1" max="999" />
											</div>
										</div>
									</div>
									<div class="vri-insertrates-bottom">
										<div class="vri-ratestable-lbl"><?php echo JText::translate('VRIHOURLYCHARGES'); ?></div>
										<div class="vri-ratestable-newprices">
									<?php
									if (is_array($prices)) {
										foreach ($prices as $pr) {
											?>
											<div class="vri-ratestable-newprice">
												<span class="vri-ratestable-newprice-name"><?php echo $pr['name']; ?></span>
												<span class="vri-ratestable-newprice-cost">
													<span class="vri-ratestable-newprice-cost-currency"><?php echo $currencysymb; ?></span>
													<span class="vri-ratestable-newprice-cost-amount">
														<input type="number" min="0" step="any" name="hprice<?php echo $pr['id']; ?>" value=""/>
													</span>
												</span>
											<?php
											if (!empty($pr['attr'])) {
												?>
												<div class="vri-ratestable-newprice-attribute">
													<span class="vri-ratestable-newprice-name"><?php echo $pr['attr']; ?></span>
													<span class="vri-ratestable-newprice-cost">
														<input type="text" name="hattr<?php echo $pr['id']; ?>" value="" size="10"/>
													</span>
												</div>
												<?php
											}
											?>
											</div>
											<?php
										}
									}
									?>
										</div>
									</div>
								</div>
								<div class="vri-insertrates-save">
									<input type="submit" class="btn vri-config-btn" name="newdispcost" value="<?php echo JText::translate('VRINSERT'); ?>"/>
									<input type="hidden" name="cid[]" value="<?php echo $iditem; ?>"/>
									<input type="hidden" name="task" value="hourscharges"/>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
		</fieldset>
	</div>

	<div class="vri-config-maintab-right">
		<fieldset class="adminform">
			<div class="vri-params-wrap">
				<div class="vri-params-container vri-list-table-container">
				<?php
				if (empty($rows)) {
					?>
					<p class="warn"><?php echo JText::translate('VRNOTARFOUND'); ?></p>
					<form name="adminForm" id="adminForm" action="index.php" method="post">
						<input type="hidden" name="task" value="">
						<input type="hidden" name="option" value="com_vikrentitems">
					</form>
					<?php
				} else {
					$mainframe = JFactory::getApplication();
					$lim = $mainframe->getUserStateFromRequest("com_vikrentitems.limit", 'limit', 15, 'int');
					$lim0 = VikRequest::getVar('limitstart', 0, '', 'int');
					$allpr = array();
					$tottar = array();
					foreach ($rows as $r) {
						if (!array_key_exists($r['idprice'], $allpr)) {
							$allpr[$r['idprice']] = VikRentItems::getPriceAttr($r['idprice']);
						}
						$tottar[$r['ehours']][] = $r;
					}
					$prord = array();
					$prvar = '';
					foreach ($allpr as $kap => $ap) {
						$prord[] = $kap;
						$prvar .= "<th class=\"title center\" width=\"150\">".VikRentItems::getPriceName($kap).(!empty($ap) ? " - ".$ap : "")."</th>\n";
					}
					$totrows = count($tottar);
					$tottar = array_slice($tottar, $lim0, $lim, true);
					?>
					<form action="index.php?option=com_vikrentitems" method="post" name="adminForm" id="adminForm" class="vri-list-form">
						<div class="vri-tariffs-updaterates-cont">
							<input type="submit" name="modtarhourscharges" value="<?php echo JText::translate('VRPVIEWTARTWO'); ?>" onclick="vrRateSetTask(event);" class="btn vri-config-btn" />
						</div>
						<div class="table-responsive">
							<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped vri-list-table">
								<thead>
								<tr>
									<th width="20" class="title left">
										<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
									</th>
									<th class="title left" width="100" style="text-align: left;"><?php echo JText::translate('VRPVIEWTARONE'); ?></th>
									<?php echo $prvar; ?>
								</tr>
								</thead>
							<?php
							$k = 0;
							$i = 0;
							foreach ($tottar as $kt => $vt) {
								$multiid = "";
								foreach ($prord as $ord) {
									foreach ($vt as $kkkt => $vvv) {
										if ($vvv['idprice'] == $ord) {
											$multiid .= $vvv['id'].";";
											break;
										}
									}
								}
								?>
								<tr class="row<?php echo $k; ?>">
									<td class="left">
										<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $multiid; ?>" onclick="Joomla.isChecked(this.checked);">
									</td>
									<td class="left"><?php echo $kt; ?></td>
								<?php
								foreach ($prord as $ord) {
									$thereis = false;
									foreach ($vt as $kkkt => $vvv) {
										if ($vvv['idprice'] == $ord) {
											echo "<td class=\"center\"><input type=\"number\" min=\"0\" step=\"any\" name=\"cost".$vvv['id']."\" value=\"".$vvv['cost']."\" />".(!empty($vvv['attrdata'])? " - <input type=\"text\" name=\"attr".$vvv['id']."\" value=\"".$vvv['attrdata']."\" size=\"10\"/>" : "")."</td>\n";
											$thereis = true;
											break;
										}
									}
									if (!$thereis) {
										echo "<td></td>\n";
									}
									unset($thereis);
								}
								?>
								</tr>
								<?php
								unset($multiid);
								$k = 1 - $k;
								$i++;
							}
							?>
							</table>
						</div>
						<input type="hidden" name="elemid" value="<?php echo $itemrows['id']; ?>" />
						<input type="hidden" name="cid[]" value="<?php echo $itemrows['id']; ?>" />
						<input type="hidden" name="option" value="com_vikrentitems" />
						<input type="hidden" name="task" id="vrtask" value="hourscharges" />
						<input type="hidden" name="tarmodhourscharges" id="vrtarmod" value="" />
						<input type="hidden" name="boxchecked" value="0" />
						<?php echo JHtml::fetch( 'form.token' ); ?>
						<?php
						jimport('joomla.html.pagination');
						$pageNav = new JPagination( $totrows, $lim0, $lim );
						$navbut = "<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
						echo $navbut;
						?>
					</form>
					<?php
					}
					?>
				</div>
			</div>
		</fieldset>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#hhoursfrom').change(function() {
		var fnights = parseInt(jQuery(this).val());
		if (!isNaN(fnights)) {
			jQuery('#hhoursto').attr('min', fnights);
			var tnights = jQuery('#hhoursto').val();
			if (!(tnights.length > 0)) {
				jQuery('#hhoursto').val(fnights);
			} else {
				if (parseInt(tnights) < fnights) {
					jQuery('#hhoursto').val(fnights);
				}
			}
		}
	});
	jQuery("#vri-item-selection").select2();
});
function vrRateSetTask(event) {
	event.preventDefault();
	document.getElementById('vrtarmod').value = '1';
	document.getElementById('vrtask').value = 'items';
	document.adminForm.submit();
}
</script>
