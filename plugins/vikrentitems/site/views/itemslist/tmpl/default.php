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

$items = $this->items;
$category = $this->category;
$vri_tn = $this->vri_tn;
$navig = $this->navig;

$document = JFactory::getDocument();
//load jQuery lib e jQuery UI
if (VikRentItems::loadJquery()) {
	JHtml::fetch('jquery.framework', true, true);
	JHtml::fetch('script', VRI_SITE_URI.'resources/jquery-1.12.4.min.js');
}
$document->addStyleSheet(VRI_SITE_URI.'resources/jquery-ui.min.css');
//load jQuery UI
JHtml::fetch('script', VRI_SITE_URI.'resources/jquery-ui.min.js');

$currencysymb = VikRentItems::getCurrencySymb();
$pitemid = VikRequest::getInt('Itemid', 0, 'request');

?>
<div class="vri-page-content">
<?php

if (is_array($category)) {
	?>
	<h3 class="vriclistheadt"><?php echo $category['name']; ?></h3>
	<?php
	if (strlen($category['descr']) > 0) {
		?>
		<div class="vricatdescr">
			<?php echo $category['descr']; ?>
		</div>
		<?php
	}
} else {
	echo VikRentItems::getFullFrontTitle($vri_tn);
}

?>
	<div class="vrilistcontainer">
<?php
foreach ($items as $c) {
	$carats = VikRentItems::getItemCaratOriz($c['idcarat'], $vri_tn);
	?>
		<div class="vri-list-item-block<?php echo $c['isgroup'] > 0 ? ' vri-list-item-kit-block' : ''; ?>">
			<div class="vri-list-item-inner">
			<?php
			if (!empty($c['img'])) {
				$imgpath = is_file(VRI_ADMIN_PATH.DS.'resources'.DS.'vthumb_'.$c['img']) ? VRI_ADMIN_URI.'resources/vthumb_'.$c['img'] : VRI_ADMIN_URI.'resources/'.$c['img'];
				?>
				<div class="vri-list-item-img">
					<img src="<?php echo $imgpath; ?>" alt="<?php $c['name']; ?>" class="vrilistimg"/>
				</div>
				<?php
			}
			?>
				<div class="vri-list-item-descr">
					<span class="vrilistitemname"><?php echo $c['name']; ?></span>
					<span class="vrilistitemcat"><?php echo VikRentItems::sayCategory($c['idcat'], $vri_tn); ?></span>
					<div class="vrilistitemdescr">
						<?php
						/**
						 * @wponly 	we try to parse any shortcode inside the short description of the item
						 */
						echo do_shortcode(wpautop($c['shortdesc']));
						?>
					</div>
				<?php
				if (!empty($carats)) {
					?>
					<div class="vrilistitemcarats"><?php echo $carats; ?></div>
					<?php
				}
				?>
				</div>
			</div>
			<div class="vri-list-item-cont">
				<div class="vrilistcostdivcont">
					<?php
					if ($c['cost'] > 0) {
					?>
					<div class="vrilistdivcost">
						<span class="vriliststartfrom"><?php echo JText::translate('VRILISTSFROM'); ?></span>
						<span class="item_cost"><?php echo $currencysymb; ?> <?php echo VikRentItems::numberFormat(( strlen($c['startfrom']) > 0 ? $c['startfrom'] : $c['cost'] )); ?></span>
						<span class="vriliststartfromtext"><?php echo JText::translate(VikRentItems::getItemParam($c['params'], 'startfromtext')); ?></span>
					</div>
					<?php
					}
					?>
					<span class="vrilistgoonlist"><a href="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&view=itemdetails&elemid='.$c['id'].(!empty($pitemid) ? '&Itemid=' . $pitemid : '')); ?>"><?php echo $c['isgroup'] > 0 ? JText::translate('VRILISTKITPICK') : JText::translate('VRILISTPICK'); ?></a></span>
				</div>
			</div>
		</div>
	<?php
}
?>
	</div>

<?php
//pagination
if (strlen($navig) > 0) {
	?>
	<div class="vri-pagination"><?php echo $navig; ?></div>
	<?php
}
?>
</div>
