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
$cats = $this->cats;
$carats = $this->carats;
$optionals = $this->optionals;
$places = $this->places;
$all_items = $this->all_items;
$grouped_items = $this->grouped_items;

JHtml::fetch('jquery.framework', true, true);
JHtml::fetch('script', VRI_SITE_URI.'resources/jquery-ui.sortable.min.js');

$vri_app = VikRentItems::getVriApplication();
$vri_app->loadSelect2();
$vri_app->prepareModalBox('.vrimodal', '', true);
$document = JFactory::getDocument();
$document->addStyleSheet(VRI_SITE_URI.'resources/jquery.fancybox.css');
JHtml::fetch('script', VRI_SITE_URI.'resources/jquery.fancybox.js');
$currencysymb = VikRentItems::getCurrencySymb(true);
$arrcats = array();
$arrcarats = array();
$arropts = array();
if (count($row)) {
	$oldcats = explode(";", $row['idcat']);
	foreach ($oldcats as $oc) {
		if (!empty($oc)) {
			$arrcats[$oc] = $oc;
		}
	}
	$oldcarats = explode(";", $row['idcarat']);
	foreach ($oldcarats as $ocr) {
		if (!empty($ocr)) {
			$arrcarats[$ocr] = $ocr;
		}
	}
	$oldopts = explode(";", $row['idopt']);
	foreach ($oldopts as $oopt) {
		if (!empty($oopt)) {
			$arropts[$oopt] = $oopt;
		}
	}
}

//more images
$morei = count($row) ? explode(';;', $row['moreimgs']) : array();
$actmoreimgs = "";
if (count($morei)) {
	foreach ($morei as $ki => $mi) {
		if (!empty($mi)) {
			$actmoreimgs .= '<li class="vri-editcar-currentphoto">';
			$actmoreimgs .= '<a href="'.VRI_ADMIN_URI.'resources/big_'.$mi.'" target="_blank" class="vrimodal"><img src="'.VRI_ADMIN_URI.'resources/thumb_'.$mi.'" class="maxfifty"/></a>';
			$actmoreimgs .= '<a class="vri-rm-extraimg-lnk" onclick="return confirm(\'' . addslashes(JText::translate('VRIDELCONFIRM')) . '\');" href="index.php?option=com_vikrentitems&task=removemoreimgs&elemid='.$row['id'].'&imgind='.$ki.'"><i class="'.VikRentItemsIcons::i('times-circle').'"></i></a>';
			$actmoreimgs .= '<input type="hidden" name="imgsorting[]" value="'.$mi.'"/>';
			$actmoreimgs .= '</li>';
		}
	}
}
//end more images

$base_item_pms = array(
	'minquant' => 1,
	'custptitle' => '',
	'custptitlew' => '',
	'metakeywords' => '',
	'metadescription' => '',
);
$item_jsparams = count($row) && !empty($row['jsparams']) ? json_decode($row['jsparams'], true) : array();
$item_jsparams = !is_array($item_jsparams) || !count($item_jsparams) ? $base_item_pms : $item_jsparams;
$editor = JEditor::getInstance(JFactory::getApplication()->get('editor'));
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".vri-sortable").sortable({
		helper: 'clone'
	});
	jQuery(".vri-sortable").disableSelection();
	jQuery('#ccat, #cplace, #cretplace').select2();
	jQuery('.vri-select-all').click(function() {
		var nextsel = jQuery(this).next("select");
		nextsel.find("option").prop('selected', true);
		nextsel.trigger('change');
	});
});
function showResizeSel() {
	if (document.adminForm.autoresize.checked == true) {
		jQuery('#resizesel').show();
	} else {
		jQuery('#resizesel').hide();
	}
	return true;
}
function vriSelDropLocation() {
	var picksel = document.getElementById('cplace');
	var dropsel = document.getElementById('cretplace');
	for(i = 0; i < picksel.length; i++) {
		if (picksel.options[i].selected == false) {
			if (dropsel.options[i].selected == true) {
				dropsel.options[i].selected = false;
			}
		} else {
			if (dropsel.options[i].selected == false) {
				dropsel.options[i].selected = true;
			}
		}
	}
	// trigger the change event for select2
	jQuery('#cretplace').trigger('change');
}
function showResizeSelMore() {
	if (document.adminForm.autoresizemore.checked == true) {
		document.getElementById('resizeselmore').style.display='block';
	} else {
		document.getElementById('resizeselmore').style.display='none';
	}
	return true;
}
function addMoreImages() {
	var ni = document.getElementById('myDiv');
	var numi = document.getElementById('moreimagescounter');
	var num = (document.getElementById('moreimagescounter').value -1)+ 2;
	numi.value = num;
	var newdiv = document.createElement('div');
	var divIdName = 'my'+num+'Div';
	newdiv.setAttribute('id',divIdName);
	newdiv.innerHTML = '<input type=\'file\' name=\'cimgmore[]\' size=\'35\'/><br/>';
	ni.appendChild(newdiv);
}
function toggleDeliveryCost() {
	if (document.adminForm.delivery.checked == true) {
		document.getElementById('overdeliverycost').style.display='block';
	} else {
		document.getElementById('overdeliverycost').style.display='none';
	}
	return true;
}
function toggleMinQuantity(status) {
	if (status) {
		jQuery("#minitemquant").show();
	} else {
		jQuery("#minitemquant").hide();
	}
}
function toggleGroupItems(active) {
	document.getElementById('itemgroupcont').style.display = (active ? 'block' : 'none');
}
function updateGroupedItems() {
	var grouped = document.getElementById('childids');
	var container = document.getElementById('itemgroup-right');
	for (var i = 0; i < grouped.length; i++) {
		var rel_elem = document.getElementById('itemgroup-rel'+grouped.options[i].value);
		if (grouped.options[i].selected) {
			if (!rel_elem) {
				var itid = grouped.options[i].value;
				var itname = grouped.options[i].dataset.itName;
				var itunits = grouped.options[i].dataset.itUnits;
				//create element to append, because ".innerHTML += '...'" would reset the input values
				var newel = document.createElement("div");
				newel.id = "itemgroup-rel"+itid;
				//
				var spname = document.createElement("span");
				spname.className = "itemgroup-rel-name";
				spname.appendChild(document.createTextNode(itname));
				newel.appendChild(spname);
				//
				var spunits = document.createElement("span");
				spunits.className = "itemgroup-rel-units";
				var spunits_child = document.createElement("span");
				spunits_child.appendChild(document.createTextNode("<?php echo addslashes(JText::translate('VRNEWITEMISGROUPUNITS')); ?>"));
				var units_inp = document.createElement("input");
				units_inp.type = "number";
				units_inp.name = "groupunits["+itid+"]";
				units_inp.value = "1";
				units_inp.min = "1";
				units_inp.max = itunits;
				spunits.appendChild(spunits_child);
				spunits.appendChild(document.createTextNode(" "));
				spunits.appendChild(units_inp);
				//
				newel.appendChild(spunits);
				//
				container.appendChild(newel);
				//
			}
		} else {
			if (rel_elem) {
				rel_elem.parentElement.removeChild(rel_elem);
			}
		}
	}
}
</script>
<input type="hidden" value="0" id="moreimagescounter" />

<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">
	<div class="vri-admin-container">
		<div class="vri-config-maintab-left">
			<fieldset class="adminform">
				<div class="vri-params-wrap">
					<legend class="adminlegend"><?php echo JText::translate('VRIADMINLEGENDDETAILS'); ?></legend>
					<div class="vri-params-container">
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWITEMFIVE'); ?></div>
							<div class="vri-param-setting"><input type="text" name="cname" value="<?php echo count($row) ? htmlspecialchars($row['name']) : ''; ?>" size="40"/></div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWITEMEIGHT'); ?></div>
							<div class="vri-param-setting">
								<?php echo $vri_app->printYesNoButtons('cavail', JText::translate('VRYES'), JText::translate('VRNO'), ((count($row) && intval($row['avail']) == 1) || !count($row) ? 'yes' : 0), 'yes', 0); ?>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWITEMNINE'); ?></div>
							<div class="vri-param-setting"><input type="number" name="units" value="<?php echo count($row) ? $row['units'] : '1'; ?>" min="1" style="width: 50px !important;"/></div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWITEMSIX'); ?></div>
							<div class="vri-param-setting">
								<div class="vri-param-setting-block">
									<?php echo (count($row) && is_file(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.$row['img']) ? "<a href=\"".VRI_ADMIN_URI."resources/".$row['img']."\" target=\"_blank\" class=\"vrimodal vri-item-img-modal\"><i class=\"" . VikRentItemsIcons::i('image') . "\"></i>" . $row['img'] . "</a>" : ""); ?>
									<input type="file" name="cimg" size="35"/>
								</div>
								<div class="vri-param-setting-block">
									<span class="vri-resize-lb-cont">
										<label for="autoresize" style="display: inline-block;"><?php echo JText::translate('VRNEWOPTNINE'); ?></label>
										<input type="checkbox" id="autoresize" name="autoresize" value="1" onclick="showResizeSel();"/>
									</span>
									<span id="resizesel" style="display: none;"><span><?php echo JText::translate('VRNEWOPTTEN'); ?></span><input class="vri-small-input" type="text" name="resizeto" value="250" size="3"/> px</span>
								</div>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label">
								<div class="vri-param-label-top">
									<span><?php echo JText::translate('VRMOREIMAGES'); ?></span>
									<a class="btn vri-config-btn" href="javascript: void(0);" onclick="addMoreImages();"><?php VikRentItemsIcons::e('plus-circle'); ?> <?php echo JText::translate('VRADDIMAGES'); ?></a>
								</div>
							</div>
							<div class="vri-param-setting">
								<div class="vri-param-setting-block">
									<ul class="vri-sortable"><?php echo $actmoreimgs; ?></ul>
									<input type="file" name="cimgmore[]" size="35"/>
									<div id="myDiv" style="display: block;"></div>
								</div>
								<div class="vri-param-setting-block">
									<span class="vri-resize-lb-cont">
										<label for="autoresizemore" style="display: inline-block;"><?php echo JText::translate('VRRESIZEIMAGES'); ?></label> 
										<input type="checkbox" id="autoresizemore" name="autoresizemore" value="1" onclick="showResizeSelMore();"/> 
									</span>
									<span id="resizeselmore" style="display: none;"><span><?php echo JText::translate('VRNEWOPTTEN'); ?></span><input class="vri-small-input" type="text" name="resizetomore" value="600" size="3"/> px</span>
								</div>
							</div>
						</div>
					<?php
					if (is_array($cats) && count($cats)) {
						?>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWITEMONE'); ?></div>
							<div class="vri-param-setting">
								<select name="ccat[]" id="ccat" multiple="multiple">
								<?php
								foreach ($cats as $cat) {
									?>
									<option value="<?php echo $cat['id']; ?>"<?php echo (array_key_exists($cat['id'], $arrcats) ? ' selected="selected"' : ''); ?>><?php echo $cat['name']; ?></option>
									<?php
								}
								?>
								</select>
							</div>
						</div>
						<?php
					}
					if (is_array($places) && count($places)) {
						$actplac = count($row) ? explode(";", $row['idplace']) : array();
						$actretplac = count($row) ? explode(";", $row['idretplace']) : array();
						?>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWITEMTWO'); ?></div>
							<div class="vri-param-setting">
								<select name="cplace[]" id="cplace" multiple="multiple" onchange="vriSelDropLocation();">
								<?php
								foreach ($places as $place) {
									?>
									<option value="<?php echo $place['id']; ?>"<?php echo (in_array($place['id'], $actplac) ? ' selected="selected"' : ''); ?>><?php echo $place['name']; ?></option>
									<?php
								}
								?>
								</select>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWITEMDROPLOC'); ?></div>
							<div class="vri-param-setting">
								<select name="cretplace[]" id="cretplace" multiple="multiple">
								<?php
								foreach ($places as $place) {
									?>
									<option value="<?php echo $place['id']; ?>"<?php echo (in_array($place['id'], $actretplac) ? ' selected="selected"' : ''); ?>><?php echo $place['name']; ?></option>
									<?php
								}
								?>
								</select>
							</div>
						</div>
						<?php
					}
					if (is_array($carats) && count($carats)) {
						?>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWITEMTHREE'); ?></div>
							<div class="vri-param-setting">
								<div class="vri-itementries-cont">
								<?php
								$nn = 0;
								foreach ($carats as $kcarat => $carat) {
									?>
									<div class="vri-itementry-cont">
										<input type="checkbox" name="ccarat[]" id="carat<?php echo $kcarat; ?>" value="<?php echo $carat['id']; ?>"<?php echo (array_key_exists($carat['id'], $arrcarats) ? ' checked="checked"' : '') ?>/>
										<label for="carat<?php echo $kcarat; ?>"><?php echo $carat['name']; ?></label>
									</div>
									<?php
									$nn++;
									if (($nn % 3) == 0) {
										echo "</div>\n<div class=\"vri-itementries-cont\">\n";
									}
								}
								?>
								</div>
							</div>
						</div>
						<?php
					}
					if (is_array($optionals) && count($optionals)) {
						?>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWITEMFOUR'); ?></div>
							<div class="vri-param-setting">
								<div class="vri-itementries-cont">
								<?php
								$nn = 0;
								foreach ($optionals as $kopt => $optional) {
									?>
									<div class="vri-itementry-cont">
										<input type="checkbox" name="coptional[]" id="opt<?php echo $kopt; ?>" value="<?php echo $optional['id']; ?>"<?php echo (array_key_exists($optional['id'], $arropts) ? ' checked="checked"' : '') ?>/>
										<label for="opt<?php echo $kopt; ?>"><?php echo $optional['name']. ' ' . $currencysymb . $optional['cost']; ?></label>
									</div>
									<?php
									$nn++;
									if (($nn % 3) == 0) {
										echo "</div>\n<div class=\"vri-itementries-cont\">\n";
									}
								}
								?>
								</div>
							</div>
						</div>
						<?php
					}
					if (count($all_items) > 0) {
						?>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWITEMISGROUP'); ?></div>
							<div class="vri-param-setting">
								<?php echo $vri_app->printYesNoButtons('isgroup', JText::translate('VRYES'), JText::translate('VRNO'), (count($row) && $row['isgroup'] > 0 ? 1 : 0), 1, 0, 'toggleGroupItems(this.checked);'); ?>
								<div id="itemgroupcont" class="itemgroupcont" style="display: <?php echo count($row) && $row['isgroup'] > 0 ? "block" : "none"; ?>;">
									<p><strong><?php echo JText::translate('VRNEWITEMISGROUPSEL'); ?></strong></p>
									<div class="itemgroup-left">
										<select name="childid[]" id="childids" multiple="multiple" size="<?php echo count($all_items) > 8 ? 8 : count($all_items); ?>" onchange="updateGroupedItems();">
										<?php
										foreach ($all_items as $it) {
											$childgrouped = false;
											foreach ($grouped_items as $git) {
												if ($git['childid'] == $it['id']) {
													$childgrouped = true;
													break;
												}
											}
											?>
											<option value="<?php echo $it['id']; ?>" data-it-name="<?php echo $it['name']; ?>" data-it-units="<?php echo $it['units']; ?>"<?php echo $childgrouped ? ' selected="selected"' : ''; ?>><?php echo $it['name']; ?></option>
											<?php
										}
										?>
										</select>
									</div>
									<div class="itemgroup-right" id="itemgroup-right">
									<?php
									foreach ($grouped_items as $git) {
										?>
										<div id="itemgroup-rel<?php echo $git['childid']; ?>">
											<span class="itemgroup-rel-name"><?php echo $git['name']; ?></span>
											<span class="itemgroup-rel-units"><span><?php echo JText::translate('VRNEWITEMISGROUPUNITS'); ?></span> <input type="number" name="groupunits[<?php echo $git['childid']; ?>]" value="<?php echo $git['units']; ?>" min="1" max="<?php echo $git['maxunits']; ?>" /></span>
										</div>
										<?php
									}
									?>
									</div>
								</div>
							</div>
						</div>
						<?php
					} else {
						echo '<input type="hidden" name="isgroup" value="0" />';
					}
					?>
					</div>
				</div>
			</fieldset>
			<fieldset class="adminform">
				<div class="vri-params-wrap">
					<legend class="adminlegend"><?php echo JText::translate('VRNEWITEMPARAMETERS'); ?></legend>
					<div class="vri-params-container">
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIUSTSTARTINGFROM'); ?></div>
							<div class="vri-param-setting">
								<?php echo $currencysymb; ?> &nbsp; <input type="number" step="any" name="startfrom" value="<?php echo count($row) ? $row['startfrom'] : ''; ?>"/> 
								&nbsp;&nbsp; 
								<?php echo $vri_app->createPopover(array('title' => JText::translate('VRIUSTSTARTINGFROM'), 'content' => JText::translate('VRIUSTSTARTINGFROMHELP'))); ?>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRICUSTSTARTINGFROMTEXT'); ?></div>
							<div class="vri-param-setting">
								<input type="text" name="startfromtext" value="<?php echo count($row) ? VikRentItems::getItemParam($row['params'], 'startfromtext') : 'VRI_PERHOUR'; ?>" size="20"/>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIASKQUANTITY'); ?></div>
							<div class="vri-param-setting">
								<?php echo $vri_app->printYesNoButtons('askquantity', JText::translate('VRYES'), JText::translate('VRNO'), ((count($row) && intval($row['askquantity']) == 1) || !count($row) ? 'yes' : 0), 'yes', 0, 'toggleMinQuantity(this.checked);'); ?>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIPARAMITEMSHOWDISCQUANTAB'); ?></div>
							<div class="vri-param-setting">
								<?php echo $vri_app->printYesNoButtons('discsquantstab', JText::translate('VRYES'), JText::translate('VRNO'), ((count($row) && intval(VikRentItems::getItemParam($row['params'], 'discsquantstab')) == 1) || !count($row) ? 'yes' : 0), 'yes', 0); ?>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIHOURLYCALENDAR'); ?></div>
							<div class="vri-param-setting">
								<?php echo $vri_app->printYesNoButtons('hourlycalendar', JText::translate('VRYES'), JText::translate('VRNO'), ((count($row) && intval(VikRentItems::getItemParam($row['params'], 'hourlycalendar')) == 1) || !count($row) ? 'yes' : 0), 'yes', 0); ?>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIUSETIMESLOTS'); ?></div>
							<div class="vri-param-setting">
								<?php echo $vri_app->printYesNoButtons('timeslots', JText::translate('VRYES'), JText::translate('VRNO'), ((count($row) && intval(VikRentItems::getItemParam($row['params'], 'timeslots')) == 1) || !count($row) ? 'yes' : 0), 'yes', 0); ?>
								&nbsp;&nbsp; 
								<?php echo $vri_app->createPopover(array('title' => JText::translate('VRIUSETIMESLOTS'), 'content' => JText::translate('VRIUSETIMESLOTSHELP'))); ?>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIUSEDELIVERY'); ?></div>
							<div class="vri-param-setting">
								<?php echo $vri_app->printYesNoButtons('delivery', JText::translate('VRYES'), JText::translate('VRNO'), (count($row) && intval(VikRentItems::getItemParam($row['params'], 'delivery')) == 1 ? 'yes' : 0), 'yes', 0, 'toggleDeliveryCost();'); ?>
								<div id="overdeliverycost" style="display: <?php echo (count($row) && intval(VikRentItems::getItemParam($row['params'], 'delivery')) == 1 ? "block" : "none"); ?>;">
									<?php echo JText::translate('VRIOVERDELIVERY'); ?> 
									<input type="number" step="any" name="overdelcost" value="<?php echo count($row) ? VikRentItems::getItemParam($row['params'], 'overdelcost') : '0.00'; ?>"/> 
									<?php echo $currencysymb; ?>
								</div>
							</div>
						</div>
						<div class="vri-param-container" id="minitemquant" style="display: <?php echo ((count($row) && intval($row['askquantity']) == 1) || !count($row) ? "" : "none"); ?>;">
							<div class="vri-param-label"><?php echo JText::translate('VRIMINITEMQUANTITY'); ?></div>
							<div class="vri-param-setting">
								<input type="number" name="minquant" value="<?php echo ((count($row) && intval($item_jsparams['minquant']) < 1) || !count($row) ? '1' : (int)$item_jsparams['minquant']); ?>" min="1" style="width: 50px !important;"/>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIAUTOSETDROPDAY'); ?></div>
							<div class="vri-param-setting">
								<input type="number" name="dropdaysplus" min="0" value="<?php echo count($row) ? VikRentItems::getItemParam($row['params'], 'dropdaysplus') : '0'; ?>" style="width: 50px !important;"/> <?php echo JText::translate('VRIDAYSAFTERPICKUP'); ?>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIEXTRAEMAILITEM'); ?></div>
							<div class="vri-param-setting">
								<input type="text" name="extraemail" size="30" value="<?php echo count($row) ? VikRentItems::getItemParam($row['params'], 'extraemail') : ''; ?>" />
							</div>
						</div>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="vri-config-maintab-right">
			<fieldset class="adminform">
				<div class="vri-params-wrap">
					<legend class="adminlegend"><?php echo JText::translate('VRIDESCRIPTIONS'); ?></legend>
					<div class="vri-params-container">
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRITEMSHORTDESCR'); ?></div>
							<div class="vri-param-setting"><textarea name="shortdesc" rows="5" cols="40"><?php echo count($row) ? $row['shortdesc'] : ''; ?></textarea></div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWITEMSEVEN'); ?></div>
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
										echo $editor->display( "cdescr", (count($row) ? $row['info'] : ''), 400, 200, 70, 20 );
									} catch (Throwable $t) {
										echo $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . '<br/>';
									}
								} else {
									// we cannot catch Fatal Errors in PHP 5.x
									echo $editor->display( "cdescr", (count($row) ? $row['info'] : ''), 400, 200, 70, 20 );
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</fieldset>
			<fieldset class="adminform">
				<div class="vri-params-wrap">
					<legend class="adminlegend"><?php echo JText::translate('VRNEWITEMSEFPARAMETERS'); ?></legend>
					<div class="vri-params-container">
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIPARAMPAGETITLE'); ?></div>
							<div class="vri-param-setting">
								<input type="text" id="custptitle" name="custptitle" value="<?php echo htmlspecialchars($item_jsparams['custptitle']); ?>"/>
							</div>
						</div>
						<div class="vri-param-container vri-param-child">
							<div class="vri-param-label"></div>
							<div class="vri-param-setting">
								<select name="custptitlew">
									<option value="before"<?php echo $item_jsparams['custptitlew'] == 'before' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRIPARAMPAGETITLEBEFORECUR'); ?></option>
									<option value="after"<?php echo $item_jsparams['custptitlew'] == 'after' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRIPARAMPAGETITLEAFTERCUR'); ?></option>
									<option value="replace"<?php echo $item_jsparams['custptitlew'] == 'replace' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRIPARAMPAGETITLEREPLACECUR'); ?></option>
								</select>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIPARAMKEYWORDSMETATAG'); ?></div>
							<div class="vri-param-setting">
								<textarea name="metakeywords" id="metakeywords" rows="3" cols="40"><?php echo htmlspecialchars($item_jsparams['metakeywords']); ?></textarea>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIPARAMDESCRIPTIONMETATAG'); ?></div>
							<div class="vri-param-setting">
								<textarea name="metadescription" id="metadescription" rows="4" cols="40"><?php echo htmlspecialchars($item_jsparams['metadescription']); ?></textarea>
							</div>
						</div>
						<!-- @wponly  removed SEF alias field -->
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
	<input type="hidden" name="actmoreimgs" value="<?php echo $row['moreimgs']; ?>">
	<?php
}
?>
	<input type="hidden" name="option" value="com_vikrentitems">
	<?php echo JHtml::fetch('form.token'); ?>
</form>
