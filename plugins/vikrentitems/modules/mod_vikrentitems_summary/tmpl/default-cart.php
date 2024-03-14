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

$tf = ModVikrentitemsSummaryHelper::getTimeFormat();

$totitems = 0;

$price = is_array($vrisessioncart) && count($vrisessioncart) > 0 ? $vrisessioncart[key($vrisessioncart)][0]['price'] : array();
$dateformat = ModVikrentitemsSummaryHelper::getDateFormat();
if ($dateformat == "%d/%m/%Y") {
	$jsdf = 'd/m/Y';
} elseif ($dateformat == "%m/%d/%Y") {
	$jsdf = 'm/d/Y';
} else {
	$jsdf = 'Y/m/d';
}
if (is_array($vrisessioncart)) {
	foreach ($vrisessioncart as $iditem => $itemarrparent) {
		foreach ($itemarrparent as $ind => $itemarr) {
			$totitems += $itemarr['units'];
		}
	}
}
?>
<div class="<?php echo $params->get('moduleclass_sfx'); ?>">
	<div class="vri-modsummarycart-cont">
		<div class="vri-modsummarycart-top">
			<div class="vri-modsummarycart-top-inner">
				<span class="vri-modsummarycart-yourcart"><?php echo JText::translate('VRIMCARTYOURCART'); ?></span><span class="vri-modsummarycart-totitems"><?php echo JText::sprintf('VRIMCARTTOTITEMS', $totitems); ?></span>
			</div>
		</div>
	<?php
if ($totitems > 0) {
	?>
		<div class="vri-modsummarycart-bottom">
			<div>
	<?php
	if (intval($params->get('showdates')) == 1) {
		?>
			<div class="vri-modsummarycart-bottom-inner">
		<?php
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
		?>
			</div>
		<?php
	}
	?>
			<div class="vri-modsummarycart-bottom-innermiddle">
				<span class="vri-modsummarycart-yourtot"><?php echo JText::translate('VRIMTMPTOTDUE'); ?></span><span class="vri-modsummarycart-tot"><?php echo $currencysymb.' '.ModVikrentitemsSummaryHelper::numberFormat($totdue, 2); ?></span>
			</div>
			<div class="vri-modsummarycart-bottom-innerlast">
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
		</div>
	<?php
}
	?>
	</div>
</div>
<?php
if ($totitems > 0) {
	?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".vri-modsummarycart-cont").hover(function(){
		jQuery(this).addClass("parent-open");
		//jQuery(".vri-modsummarycart-bottom").slideToggle();
	}, function(){
		jQuery(this).removeClass("parent-open");
		//jQuery(".vri-modsummarycart-bottom").slideToggle();
	});
});
</script>
	<?php
}
