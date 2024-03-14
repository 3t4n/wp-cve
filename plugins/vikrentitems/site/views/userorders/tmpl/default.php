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

$userorders = $this->userorders;
$customer_details = $this->customer_details;
$navig = $this->navig;

$nowdf = VikRentItems::getDateFormat();
if ($nowdf == "%d/%m/%Y") {
	$df = 'd/m/Y';
} elseif ($nowdf == "%m/%d/%Y") {
	$df = 'm/d/Y';
} else {
	$df = 'Y/m/d';
}
$nowtf = VikRentItems::getTimeFormat();
$pitemid = VikRequest::getString('Itemid', '', 'request');
?>

<form action="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&view=userorders'.(!empty($pitemid) ? '&Itemid='.$pitemid : '')); ?>" method="post">
	<div class="vri-searchorderdiv">
		<div class="vri-searchorderinner">
			<span class="vri-searchordertitle"><?php echo JText::translate('VRSEARCHCONFIRMNUMB'); ?></span>
		</div>
		<div class="vri-searchorder-enterpin">
			<span><?php echo JText::translate('VRCONFIRMNUMBORPIN'); ?></span>
			<input type="text" name="confirmnumber" value="<?php echo is_array($customer_details) && array_key_exists('pin', $customer_details) ? $customer_details['pin'] : ''; ?>" size="12"/> <input type="submit" class="btn vri-searchordersubmit" name="vri-searchorder" value="<?php echo JText::translate('VRSEARCHCONFIRMNUMBBTN'); ?>"/>
		</div>
	</div>
</form>

<?php

if (is_array($userorders) && count($userorders) > 0) {
	?>
<br clear="all"/>
<div class="table-responsive">
	<table class="table vri-orderslisttable">
		<thead>
			<tr><td class="vri-orderslisttdhead vri-orderslisttdhead-first">&nbsp;</td><td class="vri-orderslisttdhead"><?php echo JText::translate('VRCONFIRMNUMB'); ?></td><td class="vri-orderslisttdhead"><?php echo JText::translate('VRIUSERRESDATE'); ?></td><td class="vri-orderslisttdhead"><?php echo JText::translate('VRPICKUP'); ?></td><td class="vri-orderslisttdhead"><?php echo JText::translate('VRRETURN'); ?></td><td class="vri-orderslisttdhead"><?php echo JText::translate('VRDAYS'); ?></td><td><?php echo JText::translate('VRIUSERRESSTATUS'); ?></td></tr>
		</thead>
		<tbody>
	<?php
	foreach ($userorders as $ord) {
		/**
		 * The column confirmnumber is not available, but
		 * it's composed by the SID-TS values of the order.
		 * 
		 * @since 	1.6
		 */
		if ($ord['status'] == 'confirmed') {
			$ord['confirmnumber'] = $ord['sid'].'-'.$ord['ts'];
		} else {
			$ord['confirmnumber'] = '';
		}
		//
		$bstatus = 'confirmed';
		$saystatus = JText::translate('VRIONFIRMED');
		$icon_status = '<i class="fas fa-check-circle"></i>';
		if ($ord['status'] == 'standby') {
			$bstatus = 'standby';
			$saystatus = JText::translate('VRSTANDBY');
			$icon_status = '<i class="fas fa-exclamation-circle"></i>';
		} elseif ($ord['status'] != 'confirmed') {
			$bstatus = 'cancelled';
			$saystatus = JText::translate('VRCANCELLED');
			$icon_status = '<i class="fas fa-times-circle"></i>';
		}
		?>
		<tr><td class="vri-order-status-cell vri-order-status-cell-<?php echo $bstatus; ?>"><?php echo $icon_status; ?></td><td><a href="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&view=order&sid='.$ord['sid'].'&ts='.$ord['ts'].(!empty($pitemid) ? '&Itemid='.$pitemid : '')); ?>"><?php echo (!empty($ord['confirmnumber']) ? $ord['confirmnumber'] : ($ord['status'] == 'standby' ? JText::translate('VRINATTESA') : '--------')); ?></a></td><td><?php echo date($df.' '.$nowtf, $ord['ts']); ?></td><td><?php echo date($df, $ord['ritiro']); ?></td><td><?php echo date($df, $ord['consegna']); ?></td><td><?php echo $ord['days']; ?></td><td class="vri-order-status-lbl vri-order-status-lbl-<?php echo $bstatus; ?>"><span><?php echo $saystatus; ?></span></td></tr>
		<?php
	}
	?>
		</tbody>
	</table>
</div>
	<?php
}

//pagination
if (strlen($navig) > 0) {
	?>
	<div class="pagination"><?php echo $navig; ?></div>
	<?php
}
