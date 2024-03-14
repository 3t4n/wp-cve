<?php  
/**
 * @package     VikRentItems
 * @subpackage  mod_vikrentitems_summary
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$session = JFactory::getSession();
$vrisessioncart = $session->get('vriCart', '');
$vrisesspickup = $session->get('vripickupts', '');
$vrisessdropoff = $session->get('vrireturnts', '');
$vrisessdays = $session->get('vridays', '');
$vrisesspickuploc = $session->get('vriplace', '');
$vrisessdropoffloc = $session->get('vrireturnplace', '');
$totdue = $session->get('vikrentitems_ordertotal', '');

$pitemid = JFactory::getApplication()->input->getInt('Itemid', 0);
$pitemid = empty($pitemid) ? $params->get('itemid', 0) : $pitemid;

if (is_array($vrisessioncart) && count($vrisessioncart) > 0) {
	$price = $vrisessioncart[key($vrisessioncart)][0]['price'];
	$dateformat = ModVikrentitemsSummaryHelper::getDateFormat();
	if ($dateformat == "%d/%m/%Y") {
		$jsdf = 'd/m/Y';
	} elseif ($dateformat == "%m/%d/%Y") {
		$jsdf = 'm/d/Y';
	} else {
		$jsdf = 'Y/m/d';
	}
	$tf = ModVikrentitemsSummaryHelper::getTimeFormat();
	$totitems = 0;
	foreach ($vrisessioncart as $iditem => $itemarrparent) {
		foreach ($itemarrparent as $ind => $itemarr) {
			$totitems += $itemarr['units'];
		}
	}
	?>
<div class="<?php echo $params->get('moduleclass_sfx'); ?>">
	<div class="vikrentitemsumsdiv">
	<?php
	if (intval($params->get('showdates')) == 1) {
		if (array_key_exists('hours', $price)) {
		?>
		<div class="vrimodsumrentalfor">
			<div class="vrirentalfortwomodsum"><p><?php echo JText::translate('VRMDAL'); ?> <span class="vrirentalfordatemodsum"><?php echo date($jsdf.' '.$tf, $vrisesspickup); ?></span></p><p><?php echo JText::translate('VRMAL'); ?> <span class="vrirentalfordatemodsum"><?php echo date($tf, $vrisessdropoff); ?></span></p></div>
		</div>
		<?php
		} else {
		?>
		<div class="vrimodsumrentalfor">
			<div class="vrirentalfortwomodsum"><p><?php echo JText::translate('VRMDAL'); ?> <span class="vrirentalfordatemodsum"><?php echo date($jsdf.' '.$tf, $vrisesspickup); ?></span></p><p><?php echo JText::translate('VRMAL'); ?> <span class="vrirentalfordatemodsum"><?php echo date($jsdf.' '.$tf, $vrisessdropoff); ?></span></p></div>
		</div>
		<?php
		}
	}
	?>
		<div class="vrimodsummarytotitemsdiv">
			<div class="vrimodsummarytotitems">
				<span><?php echo JText::translate('VRIMTOTITEMS'); ?></span><span class="vrimodsummarynum"><?php echo $totitems; ?></span>
			</div>
			<div class="vrimodsummarytotal">
				<span><?php echo JText::translate('VRIMTMPTOTDUE'); ?></span><span class="vrimodsummarynumtot"><?php echo $currencysymb.' '.ModVikrentitemsSummaryHelper::numberFormat($totdue, 2); ?></span>
			</div>
		</div>
	<?php
    if (intval($params->get('showgotosumm')) == 1) {
		?>
		<div class="vrimodsummarygosummary">
			<a class="btn" href="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&task=oconfirm&place='.$vrisesspickuploc.'&returnplace='.$vrisessdropoffloc.'&days='.$vrisessdays.'&pickup='.$vrisesspickup.'&release='.$vrisessdropoff.(!empty($pitemid) ? '&Itemid='.$pitemid : '')); ?>"><?php echo JText::translate('VRIMGOTOSUMMARY'); ?></a>
		</div>
		<?php
	}
    ?>
	</div>
</div>
	<?php
}
