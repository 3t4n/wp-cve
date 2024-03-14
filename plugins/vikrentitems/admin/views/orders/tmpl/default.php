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

$rows = $this->rows;
$lim0 = $this->lim0;
$navbut = $this->navbut;
$all_locations = $this->all_locations;
$plocation = $this->plocation;
$plocationw = $this->plocationw;
$orderby = $this->orderby;
$ordersort = $this->ordersort;
$allitems = $this->allitems;

$mainframe = JFactory::getApplication();
$dbo = JFactory::getDbo();
JHtml::fetch('behavior.tooltip');
$nowdf = VikRentItems::getDateFormat(true);
if ($nowdf == "%d/%m/%Y") {
	$df = 'd/m/Y';
} elseif ($nowdf == "%m/%d/%Y") {
	$df = 'm/d/Y';
} else {
	$df = 'Y/m/d';
}
$juidf = $nowdf == "%d/%m/%Y" ? 'dd/mm/yy' : ($nowdf == "%m/%d/%Y" ? 'mm/dd/yy' : 'yy/mm/dd');
$currencysymb = VikRentItems::getCurrencySymb(true);
$nowtf = VikRentItems::getTimeFormat(true);
$vri_app = VikRentItems::getVriApplication();
$vri_app->loadSelect2();
$document = JFactory::getDocument();
$document->addStyleSheet(VRI_SITE_URI.'resources/jquery-ui.min.css');
JHtml::fetch('jquery.framework', true, true);
JHtml::fetch('script', VRI_SITE_URI.'resources/jquery-ui.min.js');
$ldecl = '
jQuery(function($){'."\n".'
	$.datepicker.regional["vikrentitems"] = {'."\n".'
		closeText: "'.JText::translate('VRIJQCALDONE').'",'."\n".'
		prevText: "'.JText::translate('VRIJQCALPREV').'",'."\n".'
		nextText: "'.JText::translate('VRIJQCALNEXT').'",'."\n".'
		currentText: "'.JText::translate('VRIJQCALTODAY').'",'."\n".'
		monthNames: ["'.JText::translate('VRMONTHONE').'","'.JText::translate('VRMONTHTWO').'","'.JText::translate('VRMONTHTHREE').'","'.JText::translate('VRMONTHFOUR').'","'.JText::translate('VRMONTHFIVE').'","'.JText::translate('VRMONTHSIX').'","'.JText::translate('VRMONTHSEVEN').'","'.JText::translate('VRMONTHEIGHT').'","'.JText::translate('VRMONTHNINE').'","'.JText::translate('VRMONTHTEN').'","'.JText::translate('VRMONTHELEVEN').'","'.JText::translate('VRMONTHTWELVE').'"],'."\n".'
		monthNamesShort: ["'.mb_substr(JText::translate('VRMONTHONE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTWO'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTHREE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHFOUR'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHFIVE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHSIX'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHSEVEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHEIGHT'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHNINE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHELEVEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTWELVE'), 0, 3, 'UTF-8').'"],'."\n".'
		dayNames: ["'.JText::translate('VRISUNDAY').'", "'.JText::translate('VRIMONDAY').'", "'.JText::translate('VRITUESDAY').'", "'.JText::translate('VRIWEDNESDAY').'", "'.JText::translate('VRITHURSDAY').'", "'.JText::translate('VRIFRIDAY').'", "'.JText::translate('VRISATURDAY').'"],'."\n".'
		dayNamesShort: ["'.mb_substr(JText::translate('VRISUNDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIMONDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRITUESDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIWEDNESDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRITHURSDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIFRIDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRISATURDAY'), 0, 3, 'UTF-8').'"],'."\n".'
		dayNamesMin: ["'.mb_substr(JText::translate('VRISUNDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRIMONDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRITUESDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRIWEDNESDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRITHURSDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRIFRIDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRISATURDAY'), 0, 2, 'UTF-8').'"],'."\n".'
		weekHeader: "'.JText::translate('VRIJQCALWKHEADER').'",'."\n".'
		dateFormat: "'.$juidf.'",'."\n".'
		firstDay: '.VikRentItems::getFirstWeekDay().','."\n".'
		isRTL: false,'."\n".'
		showMonthAfterYear: false,'."\n".'
		yearSuffix: ""'."\n".'
	};'."\n".'
	$.datepicker.setDefaults($.datepicker.regional["vikrentitems"]);'."\n".'
});';
$document->addScriptDeclaration($ldecl);
$filtnc = $mainframe->getUserStateFromRequest("vri.orders.filtnc", 'filtnc', '', 'string');
$cid = VikRequest::getVar('cid', array(0));
$pcust_id = $mainframe->getUserStateFromRequest("vri.orders.cust_id", 'cust_id', 0, 'int');

$loc_options = '';
if (is_array($all_locations)) {
	$loc_options = '<option></option>'."\n";
	$loc_options .= '<optgroup label="'.JText::translate('VRIORDERSLOCFILTERPICK').'">'."\n";
	foreach ($all_locations as $location) {
		$loc_options .= '<option data-locw="pickup" value="'.$location['id'].'"'.($plocationw == 'pickup' && $location['id'] == $plocation ? ' selected="selected"' : '').'>'.$location['name'].'</option>'."\n";
	}
	$loc_options .= '</optgroup>'."\n";
	$loc_options .= '<optgroup label="'.JText::translate('VRIORDERSLOCFILTERDROP').'">'."\n";
	foreach ($all_locations as $location) {
		$loc_options .= '<option data-locw="dropoff" value="'.$location['id'].'"'.($plocationw == 'dropoff' && $location['id'] == $plocation ? ' selected="selected"' : '').'>'.$location['name'].'</option>'."\n";
	}
	$loc_options .= '</optgroup>'."\n";
	$loc_options .= '<optgroup label="'.JText::translate('VRIORDERSLOCFILTERPICKDROP').'">'."\n";
	foreach ($all_locations as $location) {
		$loc_options .= '<option data-locw="both" value="'.$location['id'].'"'.($plocationw == 'both' && $location['id'] == $plocation ? ' selected="selected"' : '').'>'.$location['name'].'</option>'."\n";
	}
	$loc_options .= '</optgroup>'."\n";
}

if (empty($rows)) {
	$rows = [];
	?>
<p class="warn"><?php echo JText::translate('VRNOORDERSFOUND'); ?></p>
	<?php
}

$filters_set = false;
?>
<form action="index.php?option=com_vikrentitems&task=orders" method="post" name="adminForm" id="adminForm" class="vri-allorders-fm">

	<div id="filter-bar" class="btn-toolbar vri-btn-toolbar" style="width: 100%; display: inline-block;">
		<div class="btn-group pull-left input-append">
			<input type="text" name="filtnc" id="filtnc" autocomplete="off" placeholder="<?php echo JText::translate('VRIFILTCNAMECNUMB'); ?>" value="<?php echo (strlen($filtnc) > 0 ? $filtnc : ''); ?>" size="30" />
			<button type="submit" class="btn"><i class="icon-search"></i></button>
		</div>
		<?php
		$cust_id_filter = false;
		if ($rows && array_key_exists('customer_fullname', $rows[0])) {
			//customer ID filter
			$cust_id_filter = true;
		}
		?>
		<div class="btn-group pull-left input-append">
			<input type="text" id="customernominative" autocomplete="off" placeholder="<?php echo JText::translate('VRCUSTOMERNOMINATIVE'); ?>" value="<?php echo $cust_id_filter ? htmlspecialchars($rows[0]['customer_fullname']) : ''; ?>" size="30" />
			<button type="button" class="btn<?php echo $cust_id_filter ? ' btn-danger' : ''; ?>" onclick="<?php echo $cust_id_filter ? 'document.location.href=\'index.php?option=com_vikrentitems&task=orders\'' : 'document.getElementById(\'customernominative\').focus();'; ?>"><i class="<?php echo $cust_id_filter ? 'icon-remove' : 'icon-user'; ?>"></i></button>
			<div id="vri-allbsearchcust-res" class="vri-allbsearchcust-res" style="display: none;"></div>
		</div>
		<div class="btn-group pull-left">
			<button type="button" class="btn" id="vri-search-tools-btn" onclick="if(jQuery(this).hasClass('btn-primary')){jQuery('#vri-search-tools-cont').hide();jQuery(this).removeClass('btn-primary');}else{jQuery('#vri-search-tools-cont').show();jQuery(this).addClass('btn-primary');}"><?php echo JText::translate('JSEARCH_TOOLS'); ?> <span class="caret"></span></button>
		</div>
		<div class="btn-group pull-left">
			<button type="button" class="btn" onclick="jQuery('#filter-bar, #vri-search-tools-cont').find('input, select').val('');document.getElementById('cust_id').value='';document.adminForm.submit();"><?php echo JText::translate('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>

		<div id="vri-search-tools-cont" class="js-stools-container-filters clearfix" style="display: none;">
			<div class="btn-group pull-left vri-sel2-font">
			<?php
			$piditem = $mainframe->getUserStateFromRequest("vri.orders.iditem", 'iditem', 0, 'int');
			if (count($allitems) > 0) {
				$filters_set = !empty($piditem) || $filters_set;
				$rsel = '<select name="iditem" id="iditem-selfilt"><option></option>';
				foreach ($allitems as $item) {
					$rsel .= '<option value="'.$item['id'].'"'.(!empty($piditem) && $piditem == $item['id'] ? ' selected="selected"' : '').'>'.$item['name'].'</option>';
				}
				$rsel .= '</select>';
			}
			echo $rsel;
			?>
			</div>
			<div class="btn-group pull-left vri-sel2-font">
				<select name="idpayment" id="idpayment-selfilt">
					<option></option>
				<?php
				$pidpayment = $mainframe->getUserStateFromRequest("vri.orders.idpayment", 'idpayment', 0, 'int');
				$payment_filter = '';
				if (!empty($pidpayment)) {
					$filters_set = !empty($pidpayment) || $filters_set;
					$payment_filter = '&amp;idpayment='.$pidpayment;
				}
				$allpayments = array();
				$q = "SELECT `id`,`name` FROM `#__vikrentitems_gpayments` ORDER BY `name` ASC;";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					$allpayments = $dbo->loadAssocList();
				}
				foreach ($allpayments as $paym) {
					?>
					<option value="<?php echo $paym['id']; ?>"<?php echo $paym['id'] == $pidpayment ? ' selected="selected"' : ''; ?>><?php echo $paym['name']; ?></option>
					<?php
				}
				?>
				</select>
			</div>
			<div class="btn-group pull-left vri-sel2-font">
				<select name="status" id="status-selfilt">
					<option></option>
				<?php
				$pstatus = $mainframe->getUserStateFromRequest("vri.orders.status", 'status', '', 'string');
				$filters_set = !empty($pstatus) || $filters_set;
				$status_filter = !empty($pstatus) ? '&amp;status='.$pstatus : '';
				?>
					<option value="confirmed"<?php echo $pstatus == 'confirmed' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRCONFIRMED'); ?></option>
					<option value="standby"<?php echo $pstatus == 'standby' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRSTANDBY'); ?></option>
					<option value="cancelled"<?php echo $pstatus == 'cancelled' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRCANCELLED'); ?></option>
					<option value="closure"<?php echo $pstatus == 'closure' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRDBTEXTROOMCLOSED'); ?></option>
				</select>
			</div>
			<?php
			if (is_array($all_locations)) {
				$filters_set = !empty($plocation) || $filters_set;
				?>
			<div class="btn-group pull-left vri-sel2-font">
				<select name="location" id="locfilter" onchange="vriUpdateLocFilter(this);"><?php echo $loc_options; ?></select>
				<input type="hidden" name="locationw" id="locwfilter" value="<?php echo empty($plocationw) ? 'pickup' : $plocationw; ?>" />
			</div>
				<?php
			}
			?>
			<div class="btn-group pull-left vri-sel2-font">
			<?php
			$dates_filter = '';
			$pdatefilt = $mainframe->getUserStateFromRequest("vri.orders.datefilt", 'datefilt', 0, 'int');
			$pdatefiltfrom = $mainframe->getUserStateFromRequest("vri.orders.datefiltfrom", 'datefiltfrom', '', 'string');
			$pdatefiltto = $mainframe->getUserStateFromRequest("vri.orders.datefiltto", 'datefiltto', '', 'string');
			if (!empty($pdatefilt) && (!empty($pdatefiltfrom) || !empty($pdatefiltto))) {
				$filters_set = true;
				$dates_filter = '&amp;datefilt='.$pdatefilt.(!empty($pdatefiltfrom) ? '&amp;datefiltfrom='.$pdatefiltfrom : '').(!empty($pdatefiltto) ? '&amp;datefiltto='.$pdatefiltto : '');
			}
			$datesel = '<select name="datefilt" id="datefilt-selfilt" onchange="vriToggleDateFilt(this.value);"><option></option>';
			$datesel .= '<option value="1"'.(!empty($pdatefilt) && $pdatefilt == 1 ? ' selected="selected"' : '').'>'.JText::translate('VRPCHOOSEBUSYORDATE').'</option>';
			$datesel .= '<option value="2"'.(!empty($pdatefilt) && $pdatefilt == 2 ? ' selected="selected"' : '').'>'.JText::translate('VRIEXPCSVPICK').'</option>';
			$datesel .= '<option value="3"'.(!empty($pdatefilt) && $pdatefilt == 3 ? ' selected="selected"' : '').'>'.JText::translate('VRIEXPCSVDROP').'</option>';
			$datesel .= '</select>';
			echo $datesel;
			?>
			</div>
			<div class="btn-group pull-left" id="vri-dates-cont" style="display: <?php echo (!empty($pdatefilt) && (!empty($pdatefiltfrom) || !empty($pdatefiltto)) ? 'inline-block' : 'none'); ?>;">
				<input type="text" id="vri-date-from" placeholder="<?php echo JText::translate('VRNEWSEASONONE'); ?>" value="<?php echo $pdatefiltfrom; ?>" size="10" name="datefiltfrom" />&nbsp;-&nbsp;<input type="text" id="vri-date-to" placeholder="<?php echo JText::translate('VRNEWSEASONTWO'); ?>" value="<?php echo $pdatefiltto; ?>" size="10" name="datefiltto" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn"><i class="icon-search"></i> <?php echo JText::translate('VRPVIEWORDERSSEARCHSUBM'); ?></button>
			</div>
		</div>
	</div>

	<div class="table-responsive">
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="table table-striped vri-orderslist-table">
			<thead>
				<tr>
					<th width="20">
						<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle">
					</th>
					<th class="title center" width="20" align="center">
						<a href="index.php?option=com_vikrentitems&amp;task=orders<?php echo ($cust_id_filter ? '&amp;cust_id='.$pcust_id : '').$dates_filter.$status_filter.$payment_filter; ?>&amp;vriorderby=id&amp;vriordersort=<?php echo ($orderby == "id" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "id" && $ordersort == "ASC" ? "vri-orderslist-activesort" : ($orderby == "id" ? "vri-orderslist-activesort" : "")); ?>">
							<?php echo 'ID'.($orderby == "id" && $ordersort == "ASC" ? '<i class="fas fa-sort-up"></i>' : ($orderby == "id" ? '<i class="fas fa-sort-down"></i>' : '<i class="fas fa-sort"></i>')); ?>
						</a>
					</th>
					<th class="title left" width="110">
						<a href="index.php?option=com_vikrentitems&amp;task=orders<?php echo ($cust_id_filter ? '&amp;cust_id='.$pcust_id : '').$dates_filter.$status_filter.$payment_filter; ?>&amp;vriorderby=ts&amp;vriordersort=<?php echo ($orderby == "ts" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "ts" && $ordersort == "ASC" ? "vri-orderslist-activesort" : ($orderby == "ts" ? "vri-orderslist-activesort" : "")); ?>">
							<?php echo JText::translate('VRPVIEWORDERSONE').($orderby == "ts" && $ordersort == "ASC" ? '<i class="fas fa-sort-up"></i>' : ($orderby == "ts" ? '<i class="fas fa-sort-down"></i>' : '<i class="fas fa-sort"></i>')); ?>
						</a>
					</th>
					<th class="title left" width="200"><?php echo JText::translate( 'VRPVIEWORDERSTWO' ); ?></th>
					<th class="title left" width="200"><?php echo JText::translate( 'VRPVIEWORDERSTOTITEMS' ); ?></th>
					<th class="title left" width="110">
						<a href="index.php?option=com_vikrentitems&amp;task=orders<?php echo ($cust_id_filter ? '&amp;cust_id='.$pcust_id : '').$dates_filter.$status_filter.$payment_filter; ?>&amp;vriorderby=pickupts&amp;vriordersort=<?php echo ($orderby == "pickupts" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "pickupts" && $ordersort == "ASC" ? "vri-orderslist-activesort" : ($orderby == "pickupts" ? "vri-orderslist-activesort" : "")); ?>">
							<?php echo JText::translate('VRPVIEWORDERSFOUR').($orderby == "pickupts" && $ordersort == "ASC" ? '<i class="fas fa-sort-up"></i>' : ($orderby == "pickupts" ? '<i class="fas fa-sort-down"></i>' : '<i class="fas fa-sort"></i>')); ?>
						</a>
					</th>
					<th class="title left" width="110">
						<a href="index.php?option=com_vikrentitems&amp;task=orders<?php echo ($cust_id_filter ? '&amp;cust_id='.$pcust_id : '').$dates_filter.$status_filter.$payment_filter; ?>&amp;vriorderby=dropoffts&amp;vriordersort=<?php echo ($orderby == "dropoffts" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "dropoffts" && $ordersort == "ASC" ? "vri-orderslist-activesort" : ($orderby == "dropoffts" ? "vri-orderslist-activesort" : "")); ?>">
							<?php echo JText::translate('VRPVIEWORDERSFIVE').($orderby == "dropoffts" && $ordersort == "ASC" ? '<i class="fas fa-sort-up"></i>' : ($orderby == "dropoffts" ? '<i class="fas fa-sort-down"></i>' : '<i class="fas fa-sort"></i>')); ?>
						</a>
					</th>
					<th class="title center" width="70" align="center">
						<a href="index.php?option=com_vikrentitems&amp;task=orders<?php echo ($cust_id_filter ? '&amp;cust_id='.$pcust_id : '').$dates_filter.$status_filter.$payment_filter; ?>&amp;vriorderby=days&amp;vriordersort=<?php echo ($orderby == "days" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "days" && $ordersort == "ASC" ? "vri-orderslist-activesort" : ($orderby == "days" ? "vri-orderslist-activesort" : "")); ?>">
							<?php echo JText::translate('VRPVIEWORDERSSIX').($orderby == "days" && $ordersort == "ASC" ? '<i class="fas fa-sort-up"></i>' : ($orderby == "days" ? '<i class="fas fa-sort-down"></i>' : '<i class="fas fa-sort"></i>')); ?>
						</a>
					</th>
					<th class="title center" width="110" align="center">
						<a href="index.php?option=com_vikrentitems&amp;task=orders<?php echo ($cust_id_filter ? '&amp;cust_id='.$pcust_id : '').$dates_filter.$status_filter.$payment_filter; ?>&amp;vriorderby=total&amp;vriordersort=<?php echo ($orderby == "total" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "total" && $ordersort == "ASC" ? "vri-orderslist-activesort" : ($orderby == "total" ? "vri-orderslist-activesort" : "")); ?>">
							<?php echo JText::translate('VRPVIEWORDERSSEVEN').($orderby == "total" && $ordersort == "ASC" ? '<i class="fas fa-sort-up"></i>' : ($orderby == "total" ? '<i class="fas fa-sort-down"></i>' : '<i class="fas fa-sort"></i>')); ?>
						</a>
					</th>
					<th class="title center" width="30"> </th>
					<th class="title center" width="100" align="center">
						<a href="index.php?option=com_vikrentitems&amp;task=orders<?php echo ($cust_id_filter ? '&amp;cust_id='.$pcust_id : '').$dates_filter.$status_filter.$payment_filter; ?>&amp;vriorderby=status&amp;vriordersort=<?php echo ($orderby == "status" && $ordersort == "ASC" ? "DESC" : "ASC"); ?>" class="<?php echo ($orderby == "status" && $ordersort == "ASC" ? "vri-orderslist-activesort" : ($orderby == "status" ? "vri-orderslist-activesort" : "")); ?>">
							<?php echo JText::translate('VRPVIEWORDERSEIGHT').($orderby == "status" && $ordersort == "ASC" ? '<i class="fas fa-sort-up"></i>' : ($orderby == "status" ? '<i class="fas fa-sort-down"></i>' : '<i class="fas fa-sort"></i>')); ?>
						</a>
					</th>
				</tr>
			</thead>
		<?php
		$monsmap = array(
			JText::translate('VRSHORTMONTHONE'),
			JText::translate('VRSHORTMONTHTWO'),
			JText::translate('VRSHORTMONTHTHREE'),
			JText::translate('VRSHORTMONTHFOUR'),
			JText::translate('VRSHORTMONTHFIVE'),
			JText::translate('VRSHORTMONTHSIX'),
			JText::translate('VRSHORTMONTHSEVEN'),
			JText::translate('VRSHORTMONTHEIGHT'),
			JText::translate('VRSHORTMONTHNINE'),
			JText::translate('VRSHORTMONTHTEN'),
			JText::translate('VRSHORTMONTHELEVEN'),
			JText::translate('VRSHORTMONTHTWELVE')
		);
		$maxhmore = VikRentItems::getHoursMoreRb() * 3600;
		$aehourschbaspcompare = VikRentItems::applyExtraHoursChargesBasp();
		$kk = 0;
		$i = 0;
		for ($i = 0, $n = count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$items = VikRentItems::loadOrdersItemsData($row['id']);
			$all_items_names = array();
			$all_items_ids = array();
			foreach ($items as $itbooked) {
				if (!isset($all_items_ids[$itbooked['iditem']])) {
					$all_items_ids[$itbooked['iditem']] = 0;
				}
				$all_items_ids[$itbooked['iditem']] += $itbooked['itemquant'];
				$all_items_names[$itbooked['iditem']] = $itbooked['item_name'];
			}
			$items_booked_arr = array();
			foreach ($all_items_ids as $idit => $totidit) {
				array_push($items_booked_arr, $all_items_names[$idit].($totidit > 1 ? ' x'.$totidit : ''));
			}
			$isdue = $row['order_total'];
			$checkhourscharges = 0;
			$hoursdiff = 0;
			$ppickup = $row['ritiro'];
			$prelease = $row['consegna'];
			$secdiff = $prelease - $ppickup;
			$daysdiff = $secdiff / 86400;
			if (is_int($daysdiff)) {
				if ($daysdiff < 1) {
					$daysdiff = 1;
				}
			} else {
				if ($daysdiff < 1) {
					$daysdiff = 1;
					$checkhourly = true;
					$ophours = $secdiff / 3600;
					$hoursdiff = intval(round($ophours));
					if ($hoursdiff < 1) {
						$hoursdiff = 1;
					}
				} else {
					$sum = floor($daysdiff) * 86400;
					$newdiff = $secdiff - $sum;
					if ($maxhmore >= $newdiff) {
						$daysdiff = floor($daysdiff);
					} else {
						$daysdiff = ceil($daysdiff);
						$ehours = intval(round(($newdiff - $maxhmore) / 3600));
						$checkhourscharges = $ehours;
						if ($checkhourscharges > 0) {
							$aehourschbasp = $aehourschbaspcompare;
						}
					}
				}
			}
			//Customer Details
			$custdata = $row['custdata'];
			$custdata_parts = explode("\n", $row['custdata']);
			if (count($custdata_parts) > 2 && strpos($custdata_parts[0], ':') !== false && strpos($custdata_parts[1], ':') !== false) {
				//get the first two fields
				$custvalues = array();
				foreach ($custdata_parts as $custdet) {
					if (strlen($custdet) < 1) {
						continue;
					}
					$custdet_parts = explode(':', $custdet);
					if (count($custdet_parts) >= 2) {
						unset($custdet_parts[0]);
						array_push($custvalues, trim(implode(':', $custdet_parts)));
					}
					if (count($custvalues) > 1) {
						break;
					}
				}
				if (count($custvalues) > 1) {
					$custdata = implode(' ', $custvalues);
				}
			}
			if (strlen($custdata) > 45) {
				$custdata = substr($custdata, 0, 45)." ...";
			}
			$q = "SELECT `c`.*,`co`.`idorder` FROM `#__vikrentitems_customers` AS `c` LEFT JOIN `#__vikrentitems_customers_orders` `co` ON `c`.`id`=`co`.`idcustomer` WHERE `co`.`idorder`=".$row['id'].";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$cust_country = $dbo->loadAssocList();
				$cust_country = $cust_country[0];
				if (!empty($cust_country['first_name'])) {
					$custdata = $cust_country['first_name'].' '.$cust_country['last_name'];
					if (!empty($cust_country['country'])) {
						if (file_exists(VRI_ADMIN_PATH.DS.'resources'.DS.'countries'.DS.$row['country'].'.png')) {
							$custdata .= '<img src="'.VRI_ADMIN_URI.'resources/countries/'.$row['country'].'.png'.'" title="'.$row['country'].'" class="vri-country-flag vri-country-flag-left"/>';
						}
					}
				}
			} elseif (!empty($row['nominative'])) {
				$custdata = $row['nominative'];
				if (!empty($row['country'])) {
					if (file_exists(VRI_ADMIN_PATH.DS.'resources'.DS.'countries'.DS.$row['country'].'.png')) {
						$custdata .= '<img src="'.VRI_ADMIN_URI.'resources/countries/'.$row['country'].'.png'.'" title="'.$row['country'].'" class="vri-country-flag vri-country-flag-left"/>';
					}
				}
			}
			$custdata = $row['closure'] > 0 || JText::translate('VRDBTEXTROOMCLOSED') == $row['custdata'] ? '<span class="vriordersitemclosed"><i class="'.VikRentItemsIcons::i('ban').'"></i> '.JText::translate('VRDBTEXTROOMCLOSED').'</span>' : $custdata;
			//
			$status_lbl = '';
			if ($row['status'] == 'confirmed') {
				$status_lbl = "<span class=\"label label-success vri-status-label\">".JText::translate('VRCONFIRMED')."</span>";
			} elseif ($row['status'] == 'standby') {
				$status_lbl = "<span class=\"label label-warning vri-status-label\">".JText::translate('VRSTANDBY')."</span>";
			} elseif ($row['status'] == 'cancelled') {
				$status_lbl = "<span class=\"label label-error vri-status-label\" style=\"background-color: #d9534f;\">".JText::translate('VRCANCELLED')."</span>";
			}
			$ts_info = getdate($row['ts']);
			$ts_wday = JText::translate('VR'.strtoupper(substr($ts_info['weekday'], 0, 3)));
			$ritiro_info = getdate($row['ritiro']);
			$ritiro_wday = JText::translate('VR'.strtoupper(substr($ritiro_info['weekday'], 0, 3)));
			$consegna_info = getdate($row['consegna']);
			$consegna_wday = JText::translate('VR'.strtoupper(substr($consegna_info['weekday'], 0, 3)));
			?>
			<tr class="row<?php echo $kk; ?>">
				<td class="skip">
					<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onclick="Joomla.isChecked(this.checked);">
				</td>
				<td class="center">
					<a class="vri-orderid" href="index.php?option=com_vikrentitems&amp;task=editorder&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['id']; ?></a>
				</td>
				<td>
					<a class="vri-orderslist-viewdet-link" href="index.php?option=com_vikrentitems&amp;task=editorder&amp;cid[]=<?php echo $row['id']; ?>">
						<div class="vri-orderslist-viewdet">
							<div class="vri-orderslist-viewdet-open">
								<i class="fas fa-external-link-alt"></i>
							</div>
							<div class="vri-orderslist-viewdet-fulldate">
								<div class="vri-orderslist-viewdet-date">
								<?php
								if (strpos($df, 'd') < strpos($df, 'm')) {
									//assuming d/m/Y or similar
									?>
									<span><?php echo $ts_info['mday']; ?></span>
									<span><?php echo $monsmap[($ts_info['mon'] - 1)]; ?></span>
									<?php
								} else {
									//assuming m/d/Y or similar
									?>
									<span><?php echo $monsmap[($ts_info['mon'] - 1)]; ?></span>
									<span><?php echo $ts_info['mday']; ?></span>
									<?php
								}
								?>
									<span><?php echo $ts_info['year']; ?></span>
								</div>
								<div class="vri-orderslist-viewdet-time">
									<span class="vri-orderslist-viewdet-wday"><?php echo $ts_wday; ?></span>
									<span class="vri-orderslist-viewdet-hour"><?php echo date($nowtf, $row['ts']); ?></span>
								</div>
							</div>
						</div>
					</a>
				</td>
				<td>
					<?php echo $custdata; ?>
				</td>
				<td>
					<?php echo implode(', ', $items_booked_arr); ?>
				</td>
				<td>
					<div class="vri-orderslist-booktime vri-orderslist-booktime-pickup">
						<div class="vri-orderslist-booktime-fulldate">
							<div class="vri-orderslist-booktime-date">
								<span><?php echo date($df, $row['ritiro']); ?></span>
							</div>
							<div class="vri-orderslist-booktime-time">
								<span class="vri-orderslist-booktime-twrap">
									<span class="vri-orderslist-booktime-wday"><?php echo $ritiro_wday; ?></span>
									<span class="vri-orderslist-booktime-hour"><?php echo date($nowtf, $row['ritiro']); ?></span>
								</span>
							</div>
						</div>
					</div>
				</td>
				<td>
					<div class="vri-orderslist-booktime vri-orderslist-booktime-pickup">
						<div class="vri-orderslist-booktime-fulldate">
							<div class="vri-orderslist-booktime-date">
								<span><?php echo date($df, $row['consegna']); ?></span>
							</div>
							<div class="vri-orderslist-booktime-time">
								<span class="vri-orderslist-booktime-twrap">
									<span class="vri-orderslist-booktime-wday"><?php echo $consegna_wday; ?></span>
									<span class="vri-orderslist-booktime-hour"><?php echo date($nowtf, $row['consegna']); ?></span>
								</span>
							</div>
						</div>
					</div>
				</td>
				<td class="center">
					<?php echo ($row['hourly'] == 1 && $hoursdiff > 0 ? $hoursdiff.' '.JText::translate('VRIHOURS') : $row['days']); ?>
				</td>
				<td class="center">
					<div class="vri-orderslist-total-wrap">
						<div class="vri-orderslist-total-amount">
							<span><?php echo $currencysymb; ?></span>
							<span><?php echo VikRentItems::numberFormat($isdue); ?></span>
						</div>
					<?php
					if (!empty($row['totpaid'])) {
						?>
						<div class="vri-orderslist-total-totpaid">
							<span><?php echo $currencysymb; ?></span>
							<span><?php echo VikRentItems::numberFormat($row['totpaid']); ?></span>
						</div>
						<?php
					}
					?>
					</div>
				</td>
				<td class="center">
					<?php echo (!empty($row['adminnotes']) ? '<span class="hasTooltip vri-admin-tipsicon" title="'.htmlentities(nl2br($row['adminnotes'])).'"><i class="fas fa-comment-dots"></i></span>' : ''); ?>
				</td>
				<td class="center">
					<?php echo $status_lbl; ?>
				</td>
			</tr>
			<?php
			$kk = 1 - $kk;
		}
		?>
		</table>
	</div>
	<input type="hidden" name="option" value="com_vikrentitems" />
	<input type="hidden" name="cust_id" id="cust_id" value="<?php echo !empty($pcust_id) ? $pcust_id : ''; ?>" />
	<input type="hidden" name="task" value="orders" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::fetch( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<script type="text/javascript">
if (jQuery.isFunction(jQuery.fn.tooltip)) {
	jQuery(".hasTooltip").tooltip();
} else {
	jQuery.fn.tooltip = function(){};
}
function vriToggleDateFilt(dtype) {
	if (!(dtype.length > 0)) {
		document.getElementById('vri-dates-cont').style.display = 'none';
		document.getElementById('vri-date-from').value = '';
		document.getElementById('vri-date-to').value = '';
		return true;
	}
	document.getElementById('vri-dates-cont').style.display = 'inline-block';
	return true;
}
function vriUpdateLocFilter(elem) {
	var locw = jQuery(elem).find('option:selected').attr('data-locw');
	jQuery('#locwfilter').val(locw);
}
jQuery(function() {
	// select2 filters
	jQuery('select#iditem-selfilt').select2({width: '150px', placeholder: '<?php echo addslashes(JText::translate('VRITEMFILTER')); ?>', allowClear: true});
	jQuery('select#idpayment-selfilt').select2({width: '150px', placeholder: '<?php echo addslashes(JText::translate('VRFILTERBYPAYMENT')); ?>', allowClear: true});
	jQuery('select#status-selfilt').select2({width: '150px', placeholder: '<?php echo addslashes(JText::translate('VRFILTERBYSTATUS')); ?>', allowClear: true});
	jQuery('select#locfilter').select2({width: '150px', placeholder: '<?php echo addslashes(JText::translate('VRIORDERSLOCFILTERANY')); ?>', allowClear: true});
	jQuery('select#datefilt-selfilt').select2({width: '150px', placeholder: '<?php echo addslashes(JText::translate('VRFILTERBYDATES')); ?>', allowClear: true});
	//
	jQuery('.vri-orderslist-viewdet-link').click(function(e) {
		if (e && e.target.tagName.toUpperCase() == 'I') {
			//open the link in a new window
			e.preventDefault();
			window.open(jQuery(this).attr('href'), '_blank');
		}
	});
	jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ '' ] );
	jQuery('#vri-date-from').datepicker({
		showOn: 'focus',
		dateFormat: '<?php echo $juidf; ?>',
		onSelect: function( selectedDate ) {
			jQuery('#vri-date-to').datepicker('option', 'minDate', selectedDate);
		}
	});
	jQuery('#vri-date-to').datepicker({
		showOn: 'focus',
		dateFormat: '<?php echo $juidf; ?>',
		onSelect: function( selectedDate ) {
			jQuery('#vri-date-from').datepicker('option', 'maxDate', selectedDate);
		}
	});
	//Search customer - Start
	var vricustsdelay = (function(){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
	})();
	function vriCustomerSearch(words) {
		jQuery("#vri-allbsearchcust-res").hide().html("");
		jQuery("#customernominative").addClass('vri-allbsearchcust-loading-inp');
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { option: "com_vikrentitems", task: "searchcustomer", kw: words, nopin: 1, tmpl: "component" }
		}).done(function(cont) {
			if (cont.length) {
				var obj_res = JSON.parse(cont);
				jQuery("#vri-allbsearchcust-res").html(obj_res[1]);
			} else {
				jQuery("#vri-allbsearchcust-res").html("");
			}
			jQuery("#vri-allbsearchcust-res").show();
			jQuery("#customernominative").removeClass('vri-allbsearchcust-loading-inp');
		}).fail(function() {
			jQuery("#customernominative").removeClass('vri-allbsearchcust-loading-inp');
			alert("Error Searching.");
		});
	}
	jQuery("#customernominative").keyup(function(event) {
		vricustsdelay(function() {
			var keywords = jQuery("#customernominative").val();
			if (keywords.length > 1) {
				if ((event.which > 96 && event.which < 123) || (event.which > 64 && event.which < 91) || event.which == 13) {
					vriCustomerSearch(keywords);
				}
			} else {
				if (jQuery("#vri-allbsearchcust-res").is(":visible")) {
					jQuery("#vri-allbsearchcust-res").hide();
				}
			}
		}, 600);
	});
	jQuery(document).on('click', '.vri-custsearchres-entry', function() {
		var customer_id = jQuery(this).attr('data-custid');
		if (customer_id.length) {
			document.location.href = 'index.php?option=com_vikrentitems&task=orders&cust_id='+customer_id;
		}
	});
	//Search customer - End
	jQuery(".vri-orderslist-table tr td").not(".skip").click(function() {
		//the checkbox for the booking is on the first TD of the row
		var trcbox = jQuery(this).parent("tr").find("td").first().find("input[type='checkbox']");
		if (!trcbox || !trcbox.length) {
			return;
		}
		trcbox.prop('checked', !(trcbox.prop('checked')));
		if (typeof Joomla !== 'undefined' && Joomla != null) {
			Joomla.isChecked(trcbox.prop('checked'));
		}
	});
	jQuery(".vri-orderslist-table tr").dblclick(function() {
		if (document.selection && document.selection.empty) {
			document.selection.empty();
		} else if (window.getSelection) {
			var sel = window.getSelection();
			sel.removeAllRanges();
		}
		//the link to the booking details page is on the third TD of the row
		var olink = jQuery(this).find("td").first().next().next().find("a");
		if (!olink || !olink.length) {
			return;
		}
		document.location.href = olink.attr("href");
	});
	<?php
	if ($filters_set) {
		?>
	jQuery("#vri-search-tools-btn").trigger("click");
		<?php
	}
	?>
});
</script>
